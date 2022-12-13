<?php

// default config
$config = array(

  'paths' => array(
    'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations'
  ),

  'migration_base_class' => 'Classes\PostgresMigration',

  'environments' => array(
    'default_migration_table' => 'phinxlog_ecidade',
  )

);


// dynamic config
$environment = 'ecidade';

$config['environments']['default_database'] = $environment;

require 'libs/db_conn.php';

// utiliza configuracao do db_conn (producao)
$host = $DB_SERVIDOR;
$name = $DB_BASE;
$user = $DB_USUARIO;
$pass = $DB_SENHA;
$port = $DB_PORTA;

// utiliza configuracao da sessao (producao, desenvolvimento, teste)
if (function_exists('db_getsession') && isset($_SESSION['DB_servidor'])) {

  $host = db_getsession("DB_servidor");
  $name = db_getsession("DB_base");
  $user = db_getsession("DB_user");
  $pass = db_getsession("DB_senha");
  $port = db_getsession("DB_porta");
}

$config['environments'][$environment] = array(
  'adapter' =>  'pgsql',
  'host' => $host,
  'name' => $name,
  'user' => $user,
  'pass' => $pass,
  'port' => $port,
  'charset' =>  'latin1'
);

return $config;