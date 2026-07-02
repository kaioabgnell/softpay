# ************************************************************
# Sequel Pro SQL dump
# Versão 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.5.5-10.4.28-MariaDB)
# Base de Dados: softpay_local
# Tempo de Geração: 2026-07-02 16:05:16 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump da tabela clientes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) unsigned NOT NULL,
  `nome` varchar(120) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `opt_out` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `clientes_empresa_id_index` (`empresa_id`),
  CONSTRAINT `clientes_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;

INSERT INTO `clientes` (`id`, `empresa_id`, `nome`, `telefone`, `opt_out`, `created_at`, `updated_at`)
VALUES
	(4,1,'Kaio Gomes','+5562982879325',0,'2026-07-01 19:37:30','2026-07-01 19:37:30'),
	(5,1,'Daniel','+5562984858745',0,'2026-07-01 20:39:15','2026-07-01 20:39:15');

/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;


# Dump da tabela empresas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `empresas`;

CREATE TABLE `empresas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `documento` varchar(18) DEFAULT NULL,
  `whatsapp_numero` varchar(20) DEFAULT NULL,
  `kapso_sender_id` varchar(255) DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `empresas` WRITE;
/*!40000 ALTER TABLE `empresas` DISABLE KEYS */;

INSERT INTO `empresas` (`id`, `nome`, `documento`, `whatsapp_numero`, `kapso_sender_id`, `ativo`, `created_at`, `updated_at`)
VALUES
	(1,'Demo Store','00.000.000/0001-00','+5519998702206','662449836959951',1,'2026-06-29 14:37:37','2026-07-01 20:06:10');

/*!40000 ALTER TABLE `empresas` ENABLE KEYS */;
UNLOCK TABLES;


# Dump da tabela failed_jobs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump da tabela jobs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`)
VALUES
	(1,'default','{\"uuid\":\"85d1ddfe-c8a4-4646-8d76-c60124efb8f7\",\"displayName\":\"App\\\\Jobs\\\\EnviarMensagemJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\EnviarMensagemJob\",\"command\":\"O:26:\\\"App\\\\Jobs\\\\EnviarMensagemJob\\\":1:{s:8:\\\"mensagem\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:19:\\\"App\\\\Models\\\\Mensagem\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}}\"}}',0,NULL,1782934654,1782934654);

/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;


# Dump da tabela mensagens
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mensagens`;

CREATE TABLE `mensagens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) unsigned NOT NULL,
  `cliente_id` bigint(20) unsigned NOT NULL,
  `template_id` bigint(20) unsigned NOT NULL,
  `status` enum('pendente','enviada','entregue','lida','falhou') NOT NULL DEFAULT 'pendente',
  `provider_message_id` varchar(255) DEFAULT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
  `erro` text DEFAULT NULL,
  `enviada_em` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mensagens_cliente_id_foreign` (`cliente_id`),
  KEY `mensagens_template_id_foreign` (`template_id`),
  KEY `mensagens_empresa_id_status_index` (`empresa_id`,`status`),
  CONSTRAINT `mensagens_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mensagens_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mensagens_template_id_foreign` FOREIGN KEY (`template_id`) REFERENCES `templates` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `mensagens` WRITE;
/*!40000 ALTER TABLE `mensagens` DISABLE KEYS */;

INSERT INTO `mensagens` (`id`, `empresa_id`, `cliente_id`, `template_id`, `status`, `provider_message_id`, `payload`, `erro`, `enviada_em`, `created_at`, `updated_at`)
VALUES
	(8,1,4,3,'falhou',NULL,NULL,'HTTP request returned status code 404:\n{\"error\":{\"message\":\"(#132001) Template name does not exist in the translation\",\"type\":\"OAuthException\",\"code\":132001,\"e (truncated...)\n',NULL,'2026-07-01 20:04:32','2026-07-01 20:04:33'),
	(9,1,4,3,'falhou',NULL,NULL,'HTTP request returned status code 404:\n{\"error\":{\"message\":\"(#132001) Template name does not exist in the translation\",\"type\":\"OAuthException\",\"code\":132001,\"e (truncated...)\n',NULL,'2026-07-01 20:06:49','2026-07-01 20:06:51'),
	(10,1,4,3,'falhou',NULL,NULL,'HTTP request returned status code 400:\n{\"error\":{\"message\":\"(#134100) Only marketing messages supported\",\"type\":\"OAuthException\",\"code\":134100,\"error_data\":{\"m (truncated...)\n',NULL,'2026-07-01 20:13:40','2026-07-01 20:13:42'),
	(12,1,4,3,'enviada','wamid.HBgMNTU2MjgyODc5MzI1FQIAERgUQ0U2MUM3NjFGRkU1QUNDMjY2REYA',X'7B226D6573736167696E675F70726F64756374223A227768617473617070222C22636F6E7461637473223A5B7B22696E707574223A222B35353632393832383739333235222C2277615F6964223A22353536323832383739333235227D5D2C226D65737361676573223A5B7B226964223A2277616D69642E4842674D4E5455324D6A67794F4463354D7A49314651494145526755513055324D554D334E6A4647526B553151554E444D6A593252455941222C226D6573736167655F737461747573223A226163636570746564227D5D7D',NULL,'2026-07-01 20:20:42','2026-07-01 20:20:36','2026-07-01 20:20:42'),
	(13,1,4,3,'enviada','wamid.HBgMNTU2MjgyODc5MzI1FQIAERgUQ0UzMjVGQzE4OUU4MDNCMzI3NTUA',X'7B226D6573736167696E675F70726F64756374223A227768617473617070222C22636F6E7461637473223A5B7B22696E707574223A222B35353632393832383739333235222C2277615F6964223A22353536323832383739333235227D5D2C226D65737361676573223A5B7B226964223A2277616D69642E4842674D4E5455324D6A67794F4463354D7A493146514941455267555130557A4D6A5647517A45344F5555344D444E434D7A49334E545541222C226D6573736167655F737461747573223A226163636570746564227D5D7D',NULL,'2026-07-01 20:40:28','2026-07-01 20:40:26','2026-07-01 20:40:28'),
	(14,1,4,3,'enviada','wamid.HBgMNTU2MjgyODc5MzI1FQIAERgUQ0U5NTJBQUI4QjYwRDI5NEJDRDkA',X'7B226D6573736167696E675F70726F64756374223A227768617473617070222C22636F6E7461637473223A5B7B22696E707574223A222B35353632393832383739333235222C2277615F6964223A22353536323832383739333235227D5D2C226D65737361676573223A5B7B226964223A2277616D69642E4842674D4E5455324D6A67794F4463354D7A49314651494145526755513055354E544A4251554934516A5977524449354E454A4452446B41222C226D6573736167655F737461747573223A226163636570746564227D5D7D',NULL,'2026-07-01 20:42:55','2026-07-01 20:42:54','2026-07-01 20:42:55');

/*!40000 ALTER TABLE `mensagens` ENABLE KEYS */;
UNLOCK TABLES;


# Dump da tabela migrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;

INSERT INTO `migrations` (`id`, `migration`, `batch`)
VALUES
	(1,'2014_10_12_100000_create_password_reset_tokens_table',1),
	(2,'2019_08_19_000000_create_failed_jobs_table',1),
	(3,'2019_12_14_000001_create_personal_access_tokens_table',1),
	(4,'2024_01_01_000001_create_empresas_table',1),
	(5,'2024_01_01_000002_create_users_table',1),
	(6,'2024_01_01_000003_create_templates_table',1),
	(7,'2024_01_01_000004_create_clientes_table',1),
	(8,'2024_01_01_000005_create_mensagens_table',1),
	(9,'2026_06_29_143741_create_jobs_table',2),
	(10,'2026_07_01_000001_add_idioma_to_templates_table',3);

/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;


# Dump da tabela password_reset_tokens
# ------------------------------------------------------------

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump da tabela personal_access_tokens
# ------------------------------------------------------------

DROP TABLE IF EXISTS `personal_access_tokens`;

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump da tabela templates
# ------------------------------------------------------------

DROP TABLE IF EXISTS `templates`;

CREATE TABLE `templates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) unsigned NOT NULL,
  `nome` varchar(72) NOT NULL,
  `meta_id` varchar(64) NOT NULL,
  `idioma` varchar(10) NOT NULL DEFAULT 'pt_BR',
  `usa_client_name` tinyint(1) NOT NULL DEFAULT 0,
  `categoria` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `templates_empresa_id_index` (`empresa_id`),
  CONSTRAINT `templates_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `templates` WRITE;
/*!40000 ALTER TABLE `templates` DISABLE KEYS */;

INSERT INTO `templates` (`id`, `empresa_id`, `nome`, `meta_id`, `idioma`, `usa_client_name`, `categoria`, `created_at`, `updated_at`)
VALUES
	(3,1,'template1','template1','pt_BR',1,'utility','2026-07-01 19:35:30','2026-07-01 20:19:48');

/*!40000 ALTER TABLE `templates` ENABLE KEYS */;
UNLOCK TABLES;


# Dump da tabela users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `papel` enum('admin_plataforma','admin_empresa','operador') NOT NULL DEFAULT 'admin_empresa',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_empresa_id_foreign` (`empresa_id`),
  CONSTRAINT `users_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `empresa_id`, `name`, `email`, `email_verified_at`, `password`, `papel`, `remember_token`, `created_at`, `updated_at`)
VALUES
	(1,1,'Kaio Gomes Admin','admin@softpay.test',NULL,'$2y$12$5rmhsqpqpA4JXe8do5bfE.QTIJB5HhsVfLLTFnmGVzRGYTCvGjITG','admin_empresa',NULL,'2026-06-29 14:37:38','2026-07-01 20:28:17');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
