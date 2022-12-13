<?php
require_once(modification("dbforms/db_funcoes.php"));

require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("classes/db_rharquivossiprev_classe.php"));
require_once(modification("fpdf151/pdf.php"));
$_SESSION['DB_itemmenu_acessado'] = 8747;
$_SESSION['DB_modulo']            = 952;

$erros = unserialize(file_get_contents("tmp/erros_siprev.txt"));
ksort($erros);
// kill($erros);

define('ALTURA_LINHA', 5);
define('PREENCHE', true);
define('QUEBRA', true);
define('BORDA', true);
define('LARGURA_MAXIMA', 279);

$arquivos = array(
  "01"   => "Servidores",
  "02"   => "Dependentes",
  "03"   => "Órgãos",
  "04"   => "Carreiras",
  "05"   => "Cargos",
  "06"   => "Alíquotas",
  "07"   => "Pensionistas",
  "08.1" => "Históricos Funcionais - RGPS",
  "08.2" => "Históricos Funcionais - RPPS",
  "09"   => "Históricos Financeiros",
  "10"   => "Benefícios dos Servidores",
  "11"   => "Benefícios dos Pensionistas",
  "12"   => "Tempo de Contribuição - RGPS",
  "13"   => "Tempo de Contribuição - RPPS",
  "14"   => "Tempos Fictícios",
  "15"   => "Tempo sem Contribuição",
  "16"   => "Funções Gratificadas",
);

$head2  = "Relatório de Inconsistências SIPREV";
$pdf = new PDF("L");
$pdf->open();
$pdf->aliasNbPages();
$pdf->setAutoPageBreak(false);
$pdf->setfillcolor(235);
$pdf->setfont('arial', 'b', 8);

$escreverPDF = function($grupo, $chave, &$pdf) use($arquivos) {

  // dump(!!$grupo, $grupo);

  /**
   * Item sem erro não será impresso
   */
  if (!$grupo) {
    return;
  }

  $cabecalhos = getHeaders($chave);

  escreverCabecalho("Arquivo:  {$chave} - " . $arquivos[$chave], $cabecalhos, $pdf);

  $linha = 0;


  foreach ($grupo as $dados) {

    if (++$linha == 31) {
      $linha = 0;
      escreverCabecalho("Arquivo:  {$chave} - " . $arquivos[$chave], $cabecalhos, $pdf);
    }
    escreverLinha($dados, $cabecalhos, $pdf);
  }
};

array_walk($erros, $escreverPDF, $pdf);
$pdf->output();


function escreverCabecalho($titulo, array $itens, FPDF &$pdf) {

  $pdf->addPage("L");
  $pdf->setfont('arial','b',8);
  $pdf->cell(LARGURA_MAXIMA, ALTURA_LINHA, mb_strtoupper($titulo), BORDA, QUEBRA, "C", PREENCHE);

  for($indice = 1, $quantidade = count($itens); $indice <= $quantidade; $indice++) {

    $quebra = $indice == $quantidade;
    $item   = $itens[$indice-1];
    $pdf->cell($item['largura'], ALTURA_LINHA, $item['conteudo'], BORDA, $quebra, "C", PREENCHE);
  }
}

function escreverLinha($dados, $cabecalhos, &$pdf) {

  $pdf->setfont('arial','',8);

  $itens = count($cabecalhos);
  $indice = 0;
  foreach ($cabecalhos as $item) {

    $quebra = ++$indice == $itens;
    list($chave, $valor)      = each($dados);
    list($largura, $conteudo) = each($item);
    $pdf->cell($item['largura'], ALTURA_LINHA, " " .$valor, BORDA, $quebra, "L", !PREENCHE);
  }
}

function getHeaders($arquivoID) {

  switch($arquivoID) {
    case "01":
    case "02":
    case "08.1":
    case "08.2":
    case "10":
    case "11":
    case "16":
      $headers = array(
        array("largura" => 80,                   "conteudo" => "Instituição"),
        array("largura" => 100,                  "conteudo" => "Servidor"),
        array("largura" => LARGURA_MAXIMA - 180, "conteudo" => "Erro Encontrado"),
      );
      break;
    case "03":
      $headers = array(
        array("largura" => 130,                  "conteudo" => "Instituição"),
        array("largura" => LARGURA_MAXIMA - 130, "conteudo" => "Erro Encontrado"),
      );
      break;
    case "07":
      $headers = array(
        array("largura" => 80,                   "conteudo" => "Instituição"),
        array("largura" => 100,                  "conteudo" => "Pensionista"),
        array("largura" => LARGURA_MAXIMA - 180, "conteudo" => "Erro Encontrado"),
      );
      break;
    case "09":
      $headers = array();
      break;
    case "12":
      $headers = array();
      break;
    case "13":
      $headers = array();
      break;
    case "14":
      $headers = array();
      break;
    case "15":
      $headers = array();
      break;
    case "04":
    case "05":
    case "06":
      $headers = array();
      break;
  }

  return $headers;
}
