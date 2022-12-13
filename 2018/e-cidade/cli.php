<?php

// only cli executions
if ( php_sapi_name() != 'cli' ) {
  exit(2);
}

require_once(__DIR__ . DIRECTORY_SEPARATOR . 'bootstrap.php');

use \ECidade\V3\Extension\Request;
use \ECidade\V3\Extension\Registry;

// @todo Revisar essa logica
// Criamos um request fake para poder utilizar o recursos dos modifications.
$fakeRequest = new Request();
Registry::set('app.request', $fakeRequest);

// OLD FrontIntegracaoExterna.php code

$options = getopt('e:d:', array('executable:', 'dir:'));
$myArgs = $argv;

$path = ECIDADE_PATH;

// diretorio aonde vai ser executado
if ( !empty($options['dir']) ) {
  $path = rtrim($options['dir'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
  array_splice($myArgs, 1, 2);
}

$file = null;

// arquivo que serс executado
if ( !empty($options['executable']) ) {
  $file = str_replace($path, '', $options['executable']);
  array_splice($myArgs, 1, 2);
  $myArgs[0] = $file;
}

// troca o argv pelos arguments jс processados
// como se fossem os originais da execuчуo
$_SERVER['argv'] = $argv = $myArgs;
// @todo ajustar o argc aqui tambщm

// busca a modification (se houver)
$file = modification($path . $file);

// altera o diretorio atual para o diretorio no qual deve ser executado o script
chdir($path);

// inclui o arquivo para inicio de execucao
require($file);