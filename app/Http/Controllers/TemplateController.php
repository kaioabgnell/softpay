<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Template::latest()->paginate(20);
        return view('templates.index', compact('templates'));
    }

    public function create()
    {
        return view('templates.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'            => 'required|string|max:72',
            'meta_id'         => 'required|string|max:64',
            'idioma'          => 'required|string|max:10',
            'usa_client_name' => 'boolean',
            'categoria'       => 'nullable|string|max:50',
        ]);

        $data['empresa_id']      = auth()->user()->empresa_id;
        $data['usa_client_name'] = $request->boolean('usa_client_name');

        Template::create($data);

        return redirect()->route('templates.index')->with('sucesso', 'Template criado com sucesso.');
    }

    public function edit(Template $template)
    {
        return view('templates.edit', compact('template'));
    }

    public function update(Request $request, Template $template)
    {
        $data = $request->validate([
            'nome'            => 'required|string|max:72',
            'meta_id'         => 'required|string|max:64',
            'idioma'          => 'required|string|max:10',
            'usa_client_name' => 'boolean',
            'categoria'       => 'nullable|string|max:50',
        ]);

        $data['usa_client_name'] = $request->boolean('usa_client_name');

        $template->update($data);

        return redirect()->route('templates.index')->with('sucesso', 'Template atualizado com sucesso.');
    }

    public function destroy(Template $template)
    {
        $template->delete();
        return redirect()->route('templates.index')->with('sucesso', 'Template removido.');
    }
}
