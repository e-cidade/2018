<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("libs/db_utils.php");
include ("libs/JSON.php");

require_once ("dbforms/db_funcoes.php");

include ("libs/db_liborcamento.php");
include ("libs/db_libcontabilidade.php");
include ("libs/db_libtxt.php");

require_once ('classes/db_contcearquivo_classe.php');
require_once ('classes/db_contcearquivooid_classe.php');
require_once ('classes/db_orcparametro_classe.php');

require_once ("con4_padbal_rec.php");
require_once ("classes/db_db_config_classe.php");
require_once ("con4_padbal_desp.php");
require_once ("con4_padbal_ver.php");
require_once ("con4_padcta_disp.php");
require_once ("con4_padcta_oper.php");
require_once ("con4_padrd_extra.php");
require_once ("con4_padreceita.php");
require_once ("con4_padrubrica.php");
require_once ("con4_padempenho.php");
require_once ("con4_padliquidac.php");
require_once ("con4_padpagament.php");
require_once ("con4_paddecreto.php");
require_once ("con4_padorgao.php");
require_once ("con4_paduniorcam.php");
require_once ("con4_padfuncao.php");
require_once ("con4_padsubfunc.php");
require_once ("con4_padprograma.php");
require_once ("con4_padprojativ.php");
require_once ("con4_padcredor.php");
require_once ("con4_padrecurso.php");
require_once ("con4_padsubprog.php");
require_once ("con4_padbrec_ant.php");
require_once ("con4_padrec_ant.php");
require_once ("con4_padbrub_ant.php");
require_once ("con4_padbver_ant.php");
require_once ("con4_padbvmovant.php");
require_once ("con4_padsubfunc.php");
require_once ("classes/db_conarquivospad_classe.php");

require_once ("dbforms/db_layouttxt.php");
require_once ("model/tceArrecadacaoMunicipal.model.php");
require_once ("model/tceCadastroFuncionarios.model.php");
require_once ("model/tceFolhaTabelaTotalizadores.model.php");
require_once ("model/tceLivroDiarioGeral.model.php");
require_once ("model/tceFolhaPagamento.model.php");
require_once ("model/tceCadastro.model.php");
require_once ("model/tceLeiaute.model.php");
require_once ("model/tceLeiame.model.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<?

$oGet = db_utils::postMemory($_GET);

$oContcearquivoDAO    = db_utils::getDao('contcearquivo');
$oContcearquivooidDAO = db_utils::getDao('contcearquivooid');

$oJson = new services_json();
$aArquivos    = $oJson->decode(str_replace("\\", "", $oGet->sArquivos));
$aArquivosPAD = $oJson->decode(str_replace("\\", "", $oGet->sArquivosPad));

$oDaoDBConfig = new cl_db_config;
$oDados = new stdClass();

$tribinst = db_getsession("DB_instit");

$instituicoes[0] = "0000";
$rsInstituicoes  = $oDaoDBConfig->sql_record($oDaoDBConfig->sql_query_file(null, 
                                                                            'codigo, 
                                                                             codtrib', '
                                                                             tribinst, 
                                                                             codigo', 
                                                                             'tribinst = '.db_getsession("DB_instit")));


if ($oDaoDBConfig->numrows > 0) {

  $sInstituicoes = '';
  $sVirgula = ""; 
  for ($i = 0; $i < $oDaoDBConfig->numrows; $i ++) {

    $instituicoes[pg_result($rsInstituicoes, $i, 'codigo')] = pg_result($rsInstituicoes, $i, 'codtrib');
    $sInstituicoes .= $sVirgula.pg_result($rsInstituicoes, $i, 'codigo');
    $sVirgula       = ",";
  }

  if ( $sInstituicoes == "" ) {
    $sErroMgs = "Nenhuma instituicao configurada. Verifique o campo [tribinst] no cadastro de institucoes!";
    $lErro    = true;
  }

} else {
  die("<br>Nenhuma instituicao configurada. Verifique o campo [tribinst] no cadastro de institucoes!");
}

/**
 * Busca os filtros do cadastro gerado
 */
$sSqlDadosFiltro = $oContcearquivoDAO->sql_query($oGet->codigogeracao);
$rsDadosFiltro = $oContcearquivoDAO->sql_record($sSqlDadosFiltro);
if ($oContcearquivoDAO->numrows > 0) {
  $oDadosFiltro = db_utils::fieldsMemory($rsDadosFiltro, 0);
} else {
  fechaJanela("Dados da geração não encontrados !");
}

$nomearquivos = "";
$iInstit               = $oDadosFiltro->c11_instit;
$arr_AnoUsu               = explode('-', $oDadosFiltro->c11_datageracao);
$iAnoUsu               = $arr_AnoUsu[0];
$sDataInicial          = $oDadosFiltro->c11_dataini;
$sDataFinal            = $oDadosFiltro->c11_datafim;
$oDados->diapagfolha   = $oDadosFiltro->c11_diapagtofolha;
$oDados->codigoremessa = $oGet->codigogeracao;
$sCodRemessa           = $oDadosFiltro->c11_codigoremessa;
$sDataGeracao          = date('Y-m-d', db_getsession('DB_datausu'));
$sNomeArquivo = getNomeArquivo($iInstit, $sDataInicial, $sDataFinal, $sCodRemessa);
/**
 * Array com mapeamento dos codigos dos arquivos(selecionado na tela, referente ao) para
 * as classes de negocio responsaveis por gera-los
 * 
 */
$aArquivosGerados = array ();
$aClasseArquivos  = array (
						                "33" => "tceLivroDiarioGeral", 
						                "34" => "tceFolhaPagamento", 
						                "35" => "tceCadastroFuncionarios", 
						                "36" => "tceArrecadacaoMunicipal", 
						                "37" => "tceFolhaTabelaTotalizadores", 
						                "39" => "tceCadastro",
                            "48" => "tceLeiame" 
                          );
                          
$oLeiaute = new tceLeiaute($iInstit, $sCodRemessa, $sDataInicial, $sDataFinal, $oDados);

$nomearquivos .= "tmp/".strtoupper($oLeiaute->getNomeArquivo())."#Dowload do Arquivo gerado ".strtoupper($oLeiaute->getNomeArquivo())."|";
//
// Foreach gerando os arquivos de informacoes digitais.
//


//echo "INICIO DO PROCESSAMENTO [LOTE]: ".date('H:i:s')."<br>";


foreach ( $aArquivos as $iCodArquivo ) {
  
  if (! key_exists($iCodArquivo, $aClasseArquivos)) {
    continue;
  }
    
  $oArquivoTCE        = new $aClasseArquivos [$iCodArquivo]($iInstit, $sCodRemessa, $sDataInicial, $sDataFinal, $oDados, $oLeiaute, $sInstituicoes);
  $aArquivosGerados[] = $oArquivoTCE->getNomeArquivo();
  
  $nomearquivos .= "tmp/".strtoupper($oArquivoTCE->getNomeArquivo())."#Dowload do Arquivo gerado ".strtoupper($oArquivoTCE->getNomeArquivo())."|";
  
 // echo "INICIO DO PROCESSAMENTO ARQUIVO [".$oArquivoTCE->getNomeArquivo()."] : ".date('H:i:s')."<br>";
  $oArquivoTCE->geraArquivo();
 // echo "FINAL DO PROCESSAMENTO ARQUIVO [".$oArquivoTCE->getNomeArquivo()."] : ".date('H:i:s')."<br>";
  /**
   * Salvando a geracao na db_layouttxtgeracao
   */
  
  unset($oArquivoTCE);

}

//echo "FINAL DO PROCESSAMENTO [LOTE]: ".date('H:i:s')."<br>";

$aArquivosGerados[] = $oLeiaute->getNomeArquivo();
$oLeiaute->geraArquivo();

/**
 * Gerando os arquivo do PAD
 *  
 */

// verifica se o orcamento foi feito no elemento ou subelemento
$oOrcparametroDAO = db_utils::getDao('orcparametro');
$rsSubelemento    = $oOrcparametroDAO->sql_record($oOrcparametroDAO->sql_query_file($iAnoUsu));
if ($oOrcparametroDAO->numrows > 0) {
  $oSubelemento = db_utils::fieldsMemory($rsSubelemento, 0);
}
if (isset($oSubelemento->o50_subelem) && $oSubelemento->o50_subelem == 't') {
  $subelemento = 'sim'; // true 
} else {
  $subelemento = 'nao'; // false, evitar problemas no select
}


$aClassesArquivosPAD = array (
				                       "41" => "bal_rec",
				                       "42" => "receita",
				                       "43" => "empenho",
				                       "44" => "liquidac",
				                       "45" => "pagament",
				                       "46" => "bal_desp",
				                       "47" => "decreto",
				                       "49" => "bal_ver",
															 "50" => "brec_ant",
															 "51" => "rec_ant",
															 "52" => "brub_ant",
															 "53" => "bver_ant",
															 "54" => "orgao",
															 "55" => "uniorcam",
															 "56" => "funcao",
															 "57" => "subfunc",
															 "58" => "programa",
															 "59" => "subprog",
															 "60" => "projativ",
															 "61" => "cta_disp",
															 "62" => "cta_oper",
															 "63" => "rd_extra",
															 "64" => "rubrica",
															 "65" => "recurso",
															 "66" => "credor",
                               "95" => "bvmovant",
				                     );

$sHeaderPAD = headerPad($iInstit,$sDataInicial,$sDataFinal,$sDataGeracao, $sCodRemessa);
/**
 * Capturando o buffer de saida
 */
ob_start();
foreach ( $aArquivosPAD as $iCodArquivo ) {
	
  $contador = 0;
  if (! key_exists($iCodArquivo, $aClassesArquivosPAD)) {
    continue;
  }
  
  $cl_classe          = new $aClassesArquivosPAD[$iCodArquivo]($sHeaderPAD);
  $teste              = $cl_classe->processa($sInstituicoes, $sDataInicial, $sDataFinal, db_getsession("DB_instit"), $subelemento);
  $aArquivosGerados[] =  strtoupper($aClassesArquivosPAD[$iCodArquivo].".TXT");
  
  $nomearquivos .= "tmp/".strtoupper($aClassesArquivosPAD[$iCodArquivo]).".TXT#Dowload do Arquivo gerado ".strtoupper($aClassesArquivosPAD[$iCodArquivo]).".TXT|";
  
  /**
   * Salvando a geracao na db_layouttxtgeracao
   */
  
  unset($cl_classe);

}
/**
 * Finalizando o bloco de captura do buffer descartando seu conteudo
 */
@ob_end_clean();

compactaArquivos($aArquivosGerados, $sNomeArquivo);

$lErro    = false;

db_inicio_transacao();

$sArquivoGerado = "tmp/".$sNomeArquivo.".zip";
$oidgrava = pg_lo_create();
$dados    = file_get_contents($sArquivoGerado);
if (!$dados) {
  $sErroMgs = "Falha ao abrir o arquivo [{$sArquivoGerado}].";
  $lErro    = true;
}

$objeto   = pg_lo_open($conn, $oidgrava, "w");
if (!$objeto) {
	$sErroMgs = "Falha ao buscar objedo do banco de dados";
	$lErro    = true;
}

$lOjetoEscrito = pg_lo_write($objeto, $dados);
if (!$lOjetoEscrito) {
  $sErroMgs = "Falha na escrita do objedo no banco de dados";
  $lErro    = true;
}

pg_lo_close($objeto);

/**
 * Incluindo na contcearquivooid o oid do arquivo
 */
$oContcearquivooidDAO->c14_contcearquivo = $oGet->codigogeracao;
$oContcearquivooidDAO->c14_arquivo       = $oidgrava;
$oContcearquivooidDAO->incluir(null);
if ($oContcearquivooidDAO->erro_status == "0") {
	$sErroMgs = $oContcearquivooidDAO->erro_msg;
	$lErro    = true;
}

db_fim_transacao();

//$arquivo
     
$nomearquivos .= "tmp/".$sNomeArquivo.".zip#Dowload do Arquivo completo. $sNomeArquivo.zip|";

//$nomearquivos = $arquivo."#Dowload do Arquivo gerado.|";

echo "<script>";
echo "  listagem = '$nomearquivos';";
echo "  parent.js_montarlista(listagem,'form1');";
echo "</script>";

fechaJanela("Arquivos gerados com sucesso !");

/**
 * Funcao para retornar o nome do aquivo
 *
 * @param  integer     $iInstit
 * @param  string      $sDataini
 * @param  string      $sDatafim
 * @param  string      $sRemessa
 * @return string                 nome do arquivo
 */
function getNomeArquivo($iInstit, $sDataini, $sDatafim, $sRemessa) {

  $sNomeArquivo = "";
  $sSqlInstit = "select cgc,db21_tipoinstit from db_config where codigo = {$iInstit} ";
  $rsInstit = pg_query($sSqlInstit);
  $iNumRows = pg_num_rows($rsInstit);
  if ($iNumRows == 0) {
    return false;
  }
  $oInstit = db_utils::fieldsMemory($rsInstit, 0);
  switch ( $oInstit->db21_tipoinstit) {
    case '1' :
      $sTipoGoverno = 'P';
    break;
    case '2' :
      $sTipoGoverno = 'C';
    break;
    case '6' :
      $sTipoGoverno = 'A';
    break;
    case '7' :
      $sTipoGoverno = 'A';
    break;
    case '8' :
      $sTipoGoverno = 'F';
    break;
    case '9' :
      $sTipoGoverno = 'E';
    break;
    case '10' :
      $sTipoGoverno = 'E';
    break;
    case '11' :
      $sTipoGoverno = 'E';
    break;
    case '12' :
      $sTipoGoverno = 'E';
    break;
    default :
      $sTipoGoverno = 'O';
  
  }
  
  $cnpj          = $oInstit->cgc;
  $sDataInicial  = implode("", array_reverse(explode("-", $sDataini)));
  $sDataFinal    = implode("", array_reverse(explode("-", $sDatafim)));
  $sDataGeracao  = date('dmY', db_getsession('DB_datausu'));
  $iCodigoRemssa = str_pad($sRemessa, 12, STR_PAD_RIGHT);
  $sNomeArquivo  = "{$cnpj}.{$sDataInicial}.{$sDataFinal}.{$sDataGeracao}.{$sTipoGoverno}.{$iCodigoRemssa}";
  
  return $sNomeArquivo;

}

/**
 * Funcao para fechar a janela e mostrar uma mensagem ao usuario
 *
 * @param string $sMensagem   Mensagem para o usuario
 */
function fechaJanela($sMensagem = "") {

  if ($sMensagem != "") {
    db_msgbox($sMensagem);
  }
  
  echo "<script>";
  echo "  parent.db_iframe_processa.hide();";
  echo "</script>";
  exit;


}

function headerPad($iInstit=1,$sDataInicial='',$sDataFinal='',$sDataGeracao='', $iCodigoRemessa='') {

  $rs = pg_query("select nomeinst,cgc from db_config where codigo= ".$iInstit);
  $oInstit = db_utils::fieldsMemory($rs, 0);
  
  $ini = split("-", $sDataInicial);
  $ini = "$ini[2]$ini[1]$ini[0]";
  $fim = split("-", $sDataFinal);
  $fim = "$fim[2]$fim[1]$fim[0]";
  $dt  = split("-", $sDataGeracao);
  $dt  = "$dt[2]$dt[1]$dt[0]";
  $iCodigoRemessa = str_pad($iCodigoRemessa, 12, "0", STR_PAD_LEFT);
  return formatar($oInstit->cgc, 14, 'n') . $ini . $fim . $dt . formatar($oInstit->nomeinst, 80, 'c').$iCodigoRemessa;

}

?>
</body>
</html>