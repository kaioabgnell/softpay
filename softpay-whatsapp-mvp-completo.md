# SoftPay WhatsApp — Especificação completa do MVP

> Documento de implementação do MVP. Inclui decisões, modelo de dados + **migrations (Laravel)**, integração com a **Kapso**, funcionalidades (rotas, serviço, job de envio) e o **design system** do painel.
> **Stack assumida (a confirmar):** PHP 8.1 · Laravel 10 · Blade + Tailwind CSS · Vanilla JS · MySQL · filas (queue) para envio assíncrono.

---

## 1. Visão e decisões fechadas

**O que é:** plataforma SaaS multiempresa para envio de mensagens ativas no WhatsApp pela **API oficial**, usando a **Kapso** (https://kapso.com) como provedor de envio. Resolve a dor de quem apanha de banimento usando APIs não-oficiais (Z-API, Evolution, etc.) — aqui tudo passa pela integração oficial.

**Decisões já tomadas nesta rodada:**

| Tema | Decisão |
|---|---|
| Provedor de envio | **Kapso**. A API key é gerada no painel da Kapso e fica em `KAPSO_API_KEY` no `.env`. Nada de credencial hardcoded. |
| `telefone` do cliente | **VARCHAR(20)**, em formato E.164 (ex: `+5562999999999`). |
| Log de mensagens | **Criar tabela de logs** (`mensagens`) com status, id do provedor, payload e erro. |
| Opt-in / LGPD | O opt-in/entrega fica a cargo da **API terceira (Kapso/Meta)**. Mantemos um campo `opt_out` provisionado em `clientes` para acionar se/quando precisar — sem fluxo de consentimento no MVP. |
| `client_name` (flag) | Vira **boolean** (`usa_client_name`) no lugar do `1=sim / 2=não` do desenho. |
| Nomenclatura | Padronizado em **`empresa`** (o desenho usava `store`). |
| Número remetente | **Compartilhado** no MVP: um único número para todas as empresas, definido em `KAPSO_DEFAULT_SENDER` no `.env`. Número próprio por empresa fica para o futuro (colunas já reservadas em `empresas`). |
| Acesso | **Autocadastro**: a empresa se registra sozinha. O registro cria a `empresa` + o primeiro `user` com papel `admin_empresa`. |
| Templates | **CRUD manual**: a empresa cadastra o `meta_id` de um template já existente e aprovado na Kapso/Meta. Sem sincronização automática no MVP. |

---

## 2. Stack e organização

- **Backend:** Laravel 10 (PHP 8.1).
- **Views:** Blade + Tailwind CSS (design system na seção 6).
- **Interatividade:** Vanilla JS (modais, seleção de template). Alpine.js é opcional se quiser reduzir JS manual.
- **Banco:** MySQL.
- **Filas:** envio de mensagem roda em **job assíncrono** (database/redis queue), nunca síncrono no clique.
- **Auth:** Laravel Breeze (Blade) para login/área de membros.

### 2.1 Acesso mysql 
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=softpay_local
DB_USERNAME=root
DB_PASSWORD=
```

---

## 3. Modelo de dados

### Visão geral das entidades

```
empresas (tenant / lojista)
 ├── users          (login da área de membros, pertence a uma empresa)
 ├── templates      (mensagens aprovadas na Meta, referenciadas por meta_id)
 ├── clientes       (contatos finais que recebem mensagem)
 └── mensagens      (log de cada envio: cliente + template + status)
```

### 3.1 Migrations (Laravel 10)

**`empresas`**

```php
Schema::create('empresas', function (Blueprint $table) {
    $table->id();
    $table->string('nome');
    $table->string('documento', 18)->nullable();          // CNPJ/CPF
    $table->string('whatsapp_numero', 20)->nullable();     // FUTURO: número próprio por empresa (não usado no MVP — remetente é compartilhado via .env)
    $table->string('kapso_sender_id')->nullable();         // FUTURO: remetente próprio na Kapso (não usado no MVP)
    $table->boolean('ativo')->default(true);
    $table->timestamps();
});
```

**`users`** (substitui a migration padrão para incluir `empresa_id` e `papel`)

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->foreignId('empresa_id')->nullable()->constrained('empresas')->nullOnDelete();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->enum('papel', ['admin_plataforma', 'admin_empresa', 'operador'])
          ->default('admin_empresa');
    $table->rememberToken();
    $table->timestamps();
});
```

**`templates`**

```php
Schema::create('templates', function (Blueprint $table) {
    $table->id();
    $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
    $table->string('nome', 72);                  // identificação interna
    $table->string('meta_id', 64);               // ID do template aprovado na Meta
    $table->boolean('usa_client_name')->default(false); // antes era 1/2 no desenho
    $table->string('categoria')->nullable();     // futuro: marketing / utility / authentication
    $table->timestamps();

    $table->index('empresa_id');
});
```

**`clientes`**

```php
Schema::create('clientes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
    $table->string('nome', 120);
    $table->string('telefone', 20);              // E.164, ex: +5562999999999
    $table->boolean('opt_out')->default(false);  // LGPD: bloqueia envio se true
    $table->timestamps();

    $table->index('empresa_id');
});
```

**`mensagens`** (log de envios)

```php
Schema::create('mensagens', function (Blueprint $table) {
    $table->id();
    $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
    $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
    $table->foreignId('template_id')->constrained('templates')->cascadeOnDelete();
    $table->enum('status', ['pendente', 'enviada', 'entregue', 'lida', 'falhou'])
          ->default('pendente');
    $table->string('provider_message_id')->nullable(); // id retornado pela Kapso
    $table->json('payload')->nullable();               // requisição enviada (auditoria)
    $table->text('erro')->nullable();                  // mensagem de erro se falhou
    $table->timestamp('enviada_em')->nullable();
    $table->timestamps();

    $table->index(['empresa_id', 'status']);
});
```

### 3.2 Relações dos models (Eloquent)

```php
// Empresa
public function users()     { return $this->hasMany(User::class); }
public function templates() { return $this->hasMany(Template::class); }
public function clientes()  { return $this->hasMany(Cliente::class); }
public function mensagens() { return $this->hasMany(Mensagem::class); }

// Cliente / Template / Mensagem -> belongsTo(Empresa::class)
// Mensagem -> belongsTo(Cliente::class), belongsTo(Template::class)
```

### 3.3 Isolamento por empresa (multi-tenant)

Toda query precisa ser escopada pela empresa logada. Recomendado um **Global Scope** nos models `Template`, `Cliente` e `Mensagem`:

```php
// app/Models/Scopes/EmpresaScope.php
class EmpresaScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (auth()->check() && auth()->user()->empresa_id) {
            $builder->where('empresa_id', auth()->user()->empresa_id);
        }
    }
}
```

Aplicado via `protected static function booted()` em cada model. Isso garante que uma empresa nunca enxergue dados de outra, mesmo por engano.

---

## 4. Integração Kapso (`.env`)

A API key é gerada no painel da Kapso ("Manage API keys for programmatic access to this project's resources") e configurada por variável de ambiente.

**`.env`**

```ini
KAPSO_API_URL=https://api.kapso.com   # confirmar URL base na doc da Kapso
KAPSO_API_KEY=                         # chave gerada no painel da Kapso
KAPSO_DEFAULT_SENDER=                  # número/remetente COMPARTILHADO usado por todas as empresas no MVP
```

**`config/services.php`**

```php
'kapso' => [
    'url'    => env('KAPSO_API_URL'),
    'key'    => env('KAPSO_API_KEY'),
    'sender' => env('KAPSO_DEFAULT_SENDER'),
],
```

**Serviço (`app/Services/KapsoService.php`)** — encapsula a chamada à API. Os nomes exatos dos campos do payload entram quando você passar a documentação da Kapso.

```php
class KapsoService
{
    public function enviarTemplate(string $telefone, string $metaId, ?string $clientName = null): array
    {
        $payload = [
            'to'          => $telefone,   // E.164
            'from'        => config('services.kapso.sender'), // remetente compartilhado (MVP)
            'template_id' => $metaId,
        ];

        if ($clientName !== null) {
            $payload['variables'] = ['client_name' => $clientName];
        }

        $response = Http::withToken(config('services.kapso.key'))
            ->baseUrl(config('services.kapso.url'))
            ->post('/messages', $payload);  // endpoint a confirmar na doc

        $response->throw();
        return $response->json();
    }
}
```

---

## 5. Funcionalidades (escopo do MVP)

### Tarefa 0 — Base (autocadastro + tenant)
Login e área de membros via Breeze, com **registro self-service**: na tela de cadastro a empresa informa os dados dela + os dados do primeiro usuário. O `RegisterController` cria, na mesma transação, o registro em `empresas` e o `user` vinculado com `papel = admin_empresa`, e já autentica. A partir daí, todo o sistema opera no contexto da empresa logada (tenant scoping da seção 3.3).

```php
// App\Http\Controllers\Auth\RegisteredUserController@store (customizado)
DB::transaction(function () use ($request) {
    $empresa = Empresa::create(['nome' => $request->empresa_nome]);
    $user = User::create([
        'empresa_id' => $empresa->id,
        'name'       => $request->name,
        'email'      => $request->email,
        'password'   => Hash::make($request->password),
        'papel'      => 'admin_empresa',
    ]);
    Auth::login($user);
});
```

### Tarefa 1 — Modelo de dados
Migrations da seção 3 + models com relações e Global Scope.

### Tarefa 2 — Navegação + CRUD
Sidebar com **Templates** e **Clientes**. Cada um com CRUD completo (listar, criar, editar, remover).

### Tarefa 3 — Página de Clientes + envio manual
Tabela `Nome · Telefone · Ações`. Ações: ✏️ editar · 🗑️ deletar · 🟢 botão WhatsApp.
Ao clicar no botão WhatsApp → modal de seleção de template → confirma → dispara o job → Kapso → Meta → cliente. Cada envio grava uma linha em `mensagens`.

### 5.1 Rotas (`routes/web.php`)

```php
Route::middleware('auth')->group(function () {
    Route::resource('templates', TemplateController::class)->except(['show']);
    Route::resource('clientes', ClienteController::class)->except(['show']);

    // envio manual de template para um cliente
    Route::post('clientes/{cliente}/enviar', [EnvioController::class, 'enviar'])
        ->name('clientes.enviar');
});

// webhook de status (futuro/opcional) — fora do middleware auth
Route::post('webhooks/kapso', [KapsoWebhookController::class, 'handle']);
```

### 5.2 Fluxo de envio (job assíncrono)

```php
// EnvioController@enviar
public function enviar(Request $request, Cliente $cliente)
{
    $data = $request->validate(['template_id' => 'required|exists:templates,id']);
    $template = Template::findOrFail($data['template_id']);

    if ($cliente->opt_out) {
        return back()->with('erro', 'Cliente optou por não receber mensagens.');
    }

    $mensagem = Mensagem::create([
        'empresa_id'  => auth()->user()->empresa_id,
        'cliente_id'  => $cliente->id,
        'template_id' => $template->id,
        'status'      => 'pendente',
    ]);

    EnviarMensagemJob::dispatch($mensagem);

    return back()->with('sucesso', 'Mensagem enfileirada para envio.');
}
```

```php
// EnviarMensagemJob@handle
public function handle(KapsoService $kapso): void
{
    $m = $this->mensagem;
    try {
        $clientName = $m->template->usa_client_name ? $m->cliente->nome : null;
        $res = $kapso->enviarTemplate($m->cliente->telefone, $m->template->meta_id, $clientName);

        $m->update([
            'status'              => 'enviada',
            'provider_message_id' => $res['id'] ?? null,
            'payload'             => $res,
            'enviada_em'          => now(),
        ]);
    } catch (\Throwable $e) {
        $m->update(['status' => 'falhou', 'erro' => $e->getMessage()]);
    }
}
```

### 5.3 Regra do `client_name`
Se `template.usa_client_name === true`, o job envia a variável `client_name` com o `cliente.nome`. Caso contrário, manda só o `meta_id`. O nome do parâmetro é sempre `client_name` (padronizado).

---

## 6. Design System — SoftPay (painel)

> Linguagem visual SaaS moderna, cantos arredondados e alto impacto — **adaptada das regras de layout fornecidas**, porém traduzida de "site de marketing" para "painel/app" (sidebar, tabelas, formulários, modais). Camada de ação monocromática (preto/branco) + um **acento verde** como assinatura da marca, coerente com WhatsApp.

### 6.1 Cores

| Token | Hex | Uso |
|---|---|---|
| `primary` | `#111111` | CTA principal, títulos |
| `primary-active` | `#242424` | estado pressionado |
| `brand-accent` | `#1FA855` | acento da marca / botão de WhatsApp / status "enviada" (verde WhatsApp refinado) |
| `canvas` | `#ffffff` | fundo das telas |
| `surface-soft` | `#f8f9fa` | fundo da sidebar, divisores suaves |
| `surface-card` | `#f5f5f5` | cards, linhas zebra de tabela |
| `hairline` | `#e5e7eb` | bordas 1px, divisores de tabela/input |
| `ink` | `#111111` | texto principal |
| `body` | `#374151` | texto corrido |
| `muted` | `#6b7280` | texto secundário, labels |
| `surface-dark` | `#101010` | rodapé / barra inferior (único surface escuro) |
| `success` | `#10b981` | confirmações |
| `warning` | `#f59e0b` | alertas |
| `error` | `#ef4444` | erros de validação / status "falhou" |

A camada de ação é **monocromática**: CTA principal é preto (`#111`), não colorido. O verde aparece pontualmente — no botão de WhatsApp e em status positivos — nunca no CTA primário genérico.

### 6.2 Tipografia

- **Inter** para tudo (display e corpo). Display em peso **600** com letter-spacing negativo (-0.5px a -2px conforme o tamanho); corpo em 400/500, tracking 0.
- Nunca passar de peso 600 em títulos (700 fica "gritado").

| Token | Tamanho / Peso | Uso |
|---|---|---|
| display-lg | 32px / 600 / -1px | título de página |
| display-sm | 24px / 600 / -0.5px | título de seção/modal |
| title-md | 18px / 600 | títulos de card |
| body-md | 16px / 400 | texto padrão |
| body-sm | 14px / 400 | texto de tabela, fine-print |
| caption | 13px / 500 | badges, labels |
| button | 14px / 600 | botões |

### 6.3 Raio, espaçamento e elevação

- **Raio:** botões/inputs `8px` · cards/tabelas/modais `12px` · pills/badges `9999px` · avatares `circular`.
- **Espaçamento (base 4px):** 4 · 8 · 12 · 16 · 24 · 32 · 48. Padding interno de card: 24–32px.
- **Elevação:** sombra suave (`0 1px 2px rgba(0,0,0,.05)` / `0 4px 12px rgba(0,0,0,.08)`). Sem neumorfismo, sem glass. Modais com sombra média + overlay escuro translúcido.

### 6.4 Telas e componentes

**App shell** — sidebar fixa à esquerda (`surface-soft`, ~240px) com logo SoftPay no topo e itens **Templates** e **Clientes** (item ativo com fundo branco arredondado + texto `ink`). Topbar branca (64px) com nome da empresa logada à direita e menu do usuário.

**Login** — card central branco, raio 12px, sombra suave, sobre `canvas`. Campos com raio 8px, borda `hairline`; foco escurece a borda para `ink`. CTA preto full-width.

**Lista de Clientes** — tabela em card branco com cabeçalho `Nome · Telefone · Ações`. Linhas com divisor `hairline`, hover em `surface-card`. Coluna Ações alinhada à direita com três botões-ícone circulares (36px): editar (lápis), deletar (lixeira, ícone em `error` no hover) e **WhatsApp (ícone em `brand-accent`)**.

**Modal "Enviar template"** — abre ao clicar no botão de WhatsApp. Título `display-sm`, um `<select>` de templates da empresa, e CTA "Enviar" (verde `brand-accent`). Fecha por overlay ou botão secundário "Cancelar".

**Formulários (Cliente / Template)** — card branco, campos empilhados, labels em `muted/caption`, CTA preto "Salvar" + secundário branco "Cancelar".

**Badges de status (na tela de mensagens, fase seguinte)** — pill `caption`: `enviada` verde, `entregue/lida` em tons de `success`, `falhou` em `error`, `pendente` em `muted`.

### 6.5 Do's & Don'ts
- **Do:** CTA principal preto; verde só no WhatsApp/sucesso; cards brancos com raio 12px e sombra suave; sidebar como único surface "soft"; ícones de ação circulares.
- **Don't:** colorir o CTA primário; usar raio acima de 16px (vira cara de app consumer); espalhar surfaces escuros (reservado a rodapé/barra inferior); peso de display acima de 600.

---

## 7. Decisões fechadas e o que ainda falta

**Fechado nesta rodada** (já refletido no documento): número remetente **compartilhado** via `.env`; **autocadastro** da empresa; **CRUD manual** de templates pelo `meta_id`.

**Ainda em aberto — 1 decisão:**

1. **Status de entrega:** quer já receber `entregue/lida/falhou` via **webhook da Kapso** (rota `webhooks/kapso` já provisionada), ou no MVP basta registrar `enviada/falhou` no momento do disparo? Recomendo o caminho simples agora (`enviada/falhou`) e ligar o webhook na fase seguinte.

**Atenção operacional (consequência do número compartilhado):** como todas as empresas disparam pelo mesmo número, a **reputação/qualidade do número é compartilhada** — se uma empresa abusar, o risco de restrição recai sobre todas, justamente o problema que o produto promete resolver. Além disso, os templates referenciados precisam existir/estar aprovados na conta (WABA) desse número compartilhado. Vale ter, mesmo no MVP, um teto simples de envios por empresa e um campo `ativo` para suspender quem abusar (já existe em `empresas`). Quando migrar para número próprio por empresa, esse risco desaparece.

---

## 8. Roadmap pós-MVP (já mapeado, fora do escopo agora)

Disparo em massa (broadcast) · importação de contatos via CSV · inbox de respostas · agendamento · relatórios e métricas · variáveis além de `client_name` (mapeamento em JSON) · automações/robô · cobrança e medição de consumo por empresa · papéis/permissões refinados.
