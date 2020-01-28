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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
require("std/db_stdClass.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
require_once("libs/db_libcontabilidade.php");
require_once("libs/db_liborcamento.php");
require_once("libs/db_libpostgres.php");
include("libs/JSON.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_layouttxt.php");
include("model/manad.model.php");

$oGet           = db_utils::postMemory($_GET);
$oJson          = new services_json();
$oFormDados     = $oJson->decode(str_replace("\\","",$oGet->sStrJson));
$oArquivosManad = new manad();

$sArqName       = "tmp/{$oFormDados->sNomeArquivo}sva.txt";
$oLayoutTxt     = new db_layouttxt(73, $sArqName);

$aEscape        = array("\r\n", "\n", "\r", "\t");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0"  topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<?

db_criatermometro('termometro','Concluido...','blue',1);
flush();

  
$aDataIni = explode("/", $oFormDados->sDataini);
$sDtIni   = str_replace('/','',$oFormDados->sDataini);
$aDataFim = explode("/", $oFormDados->sDatafim);
$sDtFim   = str_replace('/','',$oFormDados->sDatafim);
$iAnoInicio      = $aDataIni[2]; 

$sSqlAnoEmpenho  = "SELECT min(e60_anousu) as ano";
$sSqlAnoEmpenho .= "  From empresto ";
$sSqlAnoEmpenho .= "       inner join empempenho on e60_numemp = e91_numemp ";
$sSqlAnoEmpenho .= " where e91_anousu = {$aDataFim[2]}";
$rsAnoEmpenho    = db_query($sSqlAnoEmpenho);
if ($rsAnoEmpenho) {
  if (pg_num_rows($rsAnoEmpenho) > 0) {
    $iAnoInicio = db_utils::fieldsMemory($rsAnoEmpenho, 0)->ano;
  }
}

$sDtIni   = $aDataIni[0].$aDataIni[1].$iAnoInicio;
$sDataIniParametro = implode("-",array_reverse(explode("/",$oFormDados->sDataini)));
$sDataFimParametro = implode("-",array_reverse(explode("/",$oFormDados->sDatafim)));
$aLinhasArquivo  = array();
$aLinhasArquivoT = array();

$sSql0000 = $oArquivosManad->getSql0000(db_getsession('DB_instit'),
                                        $oFormDados->sMunic,
                                        $sDtIni,
                                        $sDtFim,
                                        $oFormDados->iFinalidade);
$aLinhasArquivo[244]['sql']   = $sSql0000;
$aLinhasArquivo[244]['ident'] = "0000";
$aLinhasArquivo[244]['tipolinha'] = 1;

$sSql0001 = $oArquivosManad->getSql0001();
$aLinhasArquivo[245]['sql']   = $sSql0001;
$aLinhasArquivo[245]['ident'] = "0001";
$aLinhasArquivo[245]['tipolinha'] = 3;

$sSql0100 = $oArquivosManad->getSql0100(db_getsession('DB_instit'));
$aLinhasArquivo[246]['sql']   = $sSql0100;
$aLinhasArquivo[246]['ident'] = "0100";
$aLinhasArquivo[246]['tipolinha'] = 3;

$sSql0990 = $oArquivosManad->getSql0990();
$aLinhasArquivo[247]['sql']   = $sSql0990;
$aLinhasArquivo[247]['ident'] = "0990";
$aLinhasArquivo[247]['tipolinha'] = 3;

$sSqlK001 = $oArquivosManad->getSqlK001();
$aLinhasArquivo[258]['sql']   = $sSqlK001;
$aLinhasArquivo[258]['ident'] = "K001";
$aLinhasArquivo[258]['tipolinha'] = 3;

$sSqlK050 = $oArquivosManad->getSqlK050(db_getsession('DB_instit'),$sDataIniParametro,$sDataFimParametro );
$aLinhasArquivo[248]['sql']   = $sSqlK050;
$aLinhasArquivo[248]['ident'] = "K050";
$aLinhasArquivo[248]['tipolinha'] = 3;

$sSqlK100 = $oArquivosManad->getSqlK100(db_getsession('DB_instit'));
$aLinhasArquivo[249]['sql']   = $sSqlK100;
$aLinhasArquivo[249]['ident'] = "K100";
$aLinhasArquivo[249]['tipolinha'] = 3;

$sSqlK150 = $oArquivosManad->getSqlK150(db_getsession('DB_instit'));
$aLinhasArquivo[250]['sql']   = $sSqlK150;
$aLinhasArquivo[250]['ident'] = "K150";
$aLinhasArquivo[250]['tipolinha'] = 3;

$sSqlK250 = $oArquivosManad->getSqlK250(db_getsession('DB_instit'),$sDataIniParametro,$sDataFimParametro);
$aLinhasArquivo[251]['sql']   = $sSqlK250;
$aLinhasArquivo[251]['ident'] = "K250";
$aLinhasArquivo[251]['tipolinha'] = 3;
			
$sSqlK300 = $oArquivosManad->getSqlK300(db_getsession('DB_instit'),$sDataIniParametro,$sDataFimParametro);
$aLinhasArquivo[252]['sql']   = $sSqlK300;
$aLinhasArquivo[252]['ident'] = "K300";
$aLinhasArquivo[252]['tipolinha'] = 3;
			
$iContadorGeral    = 0;
$iTotalizadorGeral = 0;
$aTotaisPorLinha   = array();
//$aLinhasArquivo = array();
foreach ($aLinhasArquivo as $chave => $aValor) {
	
	$sSqlTMP    = $aValor['sql'];
	$sIdent     = $aValor['ident'];
	$iTipoLinha = $aValor['tipolinha'];
	$rsTMP      = db_query($sSqlTMP);
	$iTMP       = pg_num_rows($rsTMP);
	if (! $rsTMP){
		//fechaJanela("Erro processando tabela : {$sIdent}");
		die("ERRO : ".pg_last_error().$sSqlTMP);
		flush();
	}
	
	for($i = 0;$i < $iTMP; $i++){
	  
	  db_atutermometro($i,$iTMP,'termometro',1,"Processando Tabela : {$sIdent}");
	  $oLinha = db_utils::fieldsMemory($rsTMP,$i);
	  
	  if ( $chave = 251 && isset( $oLinha->cod_ocorr ) && empty( $oLinha->cod_ocorr ) ) {
	    $oLinha->cod_ocorr = html_entity_decode( "&nbsp;&nbsp;" );
	  }
	  
	  $oLayoutTxt->setByLineOfDBUtils($oLinha,$iTipoLinha,$sIdent);
	  if (strtoupper(substr($sIdent,0,1)) == 'K') {
  	  $iContadorGeral++;
	  }
	  $iTotalizadorGeral++;
	}
	if (strtoupper(substr($sIdent,0,1)) == 'K') {
	  $aTotaisPorLinha[$sIdent] = $iTMP;
	}
}
$oK990 = new stdClass();
$oK990->reg       = 'K990';
$oK990->qtd_lin_k = ($iContadorGeral+1);
$oLayoutTxt->setByLineOfDBUtils($oK990, 3, 'K990');
$iTotalizadorGeral++; 
$iTotalLinhasBlocoL = 0;
/**
 * Arquivos de Pagamento
 */
$oL001 = new stdClass();
$oL001->reg       = 'L001';
$oL001->ind_mov   = '0';
$oLayoutTxt->setByLineOfDBUtils($oL001, 3, 'L001');
$iTotalizadorGeral++;
$iTotalLinhasBlocoL++; 
$aTotaisPorLinha["L001"] = 1;

$aEmpenhos = $oArquivosManad->getLancamentosEmpenho($sDataIniParametro, $sDataFimParametro, $iAnoInicio);
$iTMP      = count($aEmpenhos);
$i         = 0;
$aTotaisPorLinha["L050"] = $iTMP;
foreach ($aEmpenhos as $oEmpenho) {
  
  db_atutermometro($i,$iTMP,'termometro',1,"Processando Tabela : L050");
  $oEmpenho->vl_emp = number_format($oEmpenho->vl_emp, 2, ",","");
  if ($oEmpenho->cod_progr == '0') {
    $oEmpenho->cod_progr = '000';
  }
  if (!isset($oArquivosManad->aCredores[$oEmpenho->cod_credor])) {
    $oArquivosManad->aCredores[$oEmpenho->cod_credor] = array(); 
  }
  if (!in_array($oEmpenho->e60_anousu, $oArquivosManad->aCredores[$oEmpenho->cod_credor])) {
    $oArquivosManad->aCredores[$oEmpenho->cod_credor][] = $oEmpenho->e60_anousu;  
  }
  if (trim($oEmpenho->hist_emp) == '') {
    $oEmpenho->hist_emp = 'Sem historico';
  }

  $oEmpenho->hist_emp = str_replace($aEscape, '', $oEmpenho->hist_emp);

  $oLayoutTxt->setByLineOfDBUtils($oEmpenho, 3, "L050");
  $iContadorGeral++;
  $iTotalizadorGeral++; 
  $iTotalLinhasBlocoL++;
  $i++;
}
unset($aEmpenhos);

$aLiquidacoes = $oArquivosManad->getLancamentosLiquidacao($sDataIniParametro, $sDataFimParametro, $iAnoInicio);
$iTMP         = count($aLiquidacoes);
$i            = 0;
$aTotaisPorLinha["L100"] = $iTMP;
foreach ($aLiquidacoes as $oLiquidacao) {
  
  db_atutermometro($i,$iTMP,'termometro',1,"Processando Tabela : L100");
  $oLiquidacao->vl_liquid = number_format($oLiquidacao->vl_liquid, 2, ",","");
  $oLayoutTxt->setByLineOfDBUtils($oLiquidacao, 3, "L100");
  $iContadorGeral++;
  $iTotalizadorGeral++; 
  $iTotalLinhasBlocoL++;
  $i++;
}

unset($aLiquidacoes);

$aPagamentos = $oArquivosManad->getLancamentosPagamento($sDataIniParametro, $sDataFimParametro, $iAnoInicio);
$iTMP         = count($aPagamentos);
$i            = 0;
$aTotaisPorLinha["L150"] = $iTMP;
foreach ($aPagamentos as $oPagamento) {
  
  db_atutermometro($i,$iTMP,'termometro',1,"Processando Tabela : L150");
  $oPagamento->vl_pgto = number_format($oPagamento->vl_pgto, 2, ",","");
  $oLayoutTxt->setByLineOfDBUtils($oPagamento, 3, "L150");
  $iContadorGeral++;
  $iTotalizadorGeral++;
  $iTotalLinhasBlocoL++; 
  $i++;
}
unset($aPagamentos);

$aBalancReceita = $oArquivosManad->getDadosBalanceteReceita($sDataIniParametro, $sDataFimParametro, $iAnoInicio);
$iTMP           = count($aBalancReceita);
$i              = 0;
$aTotaisPorLinha["L200"] = $iTMP;
foreach ($aBalancReceita as $oReceita) {
  
  db_atutermometro($i,$iTMP,'termometro',1,"Processando Tabela : L200");
  $oLayoutTxt->setByLineOfDBUtils($oReceita, 3, "L200");
  $iContadorGeral++;
  $iTotalizadorGeral++; 
  $iTotalLinhasBlocoL++;
  $i++;
}
unset($aBalancReceita);

$aBalancDespesa = $oArquivosManad->getDadosBalanceteDespesa($sDataIniParametro, $sDataFimParametro, $iAnoInicio);
$iTMP           = count($aBalancDespesa);
$i              = 0;
$aTotaisPorLinha["L250"] = $iTMP;
foreach ($aBalancDespesa as $oDespesa) {
  
  db_atutermometro($i,$iTMP,'termometro',1,"Processando Tabela : L250");
  $oLayoutTxt->setByLineOfDBUtils($oDespesa, 3, "L250");
  $iContadorGeral++;
  $iTotalizadorGeral++; 
  $iTotalLinhasBlocoL++;
  $i++;
}
unset($aBalancDespesa);

$aDecretos               = $oArquivosManad->getDadosDecretos($sDataIniParametro, $sDataFimParametro, $iAnoInicio);
$iTMP                    = count($aDecretos);
$i                       = 0;
$aTotaisPorLinha["L300"] = $iTMP;
foreach ($aDecretos as $oDecreto) {
  
  db_atutermometro($i,$iTMP,'termometro',1,"Processando Tabela : L300");
  $oLayoutTxt->setByLineOfDBUtils($oDecreto, 3, "L300");
  $iContadorGeral++;
  $iTotalizadorGeral++;
  $iTotalLinhasBlocoL++; 
  $i++;
}

unset($aDecretos);

$aArquivosAuxiliares = array();
$aArquivosAuxiliares[350]['metodo']    = "getDadosOrgao";  
$aArquivosAuxiliares[350]['ident']     = "L350";
$aArquivosAuxiliares[350]['tipolinha'] = 3;
/*
 * Unidades
 */
$aArquivosAuxiliares[400]['metodo']    = "getDadosUnidades";  
$aArquivosAuxiliares[400]['ident']     = "L400";
$aArquivosAuxiliares[400]['tipolinha'] = 3;

/*
 * Funcao
 */
$aArquivosAuxiliares[450]['metodo']    = "getDadosFuncao";  
$aArquivosAuxiliares[450]['ident']     = "L450";
$aArquivosAuxiliares[450]['tipolinha'] = 3;

/*
 * SubFuncao
 */
$aArquivosAuxiliares[500]['metodo']    = "getDadosSubFuncao";  
$aArquivosAuxiliares[500]['ident']     = "L500";
$aArquivosAuxiliares[500]['tipolinha'] = 3;

/*
 * Programa
 */
$aArquivosAuxiliares[550]['metodo']    = "getDadosPrograma";  
$aArquivosAuxiliares[550]['ident']     = "L550";
$aArquivosAuxiliares[550]['tipolinha'] = 3;



/*
 * Projativ
 */
$aArquivosAuxiliares[600]['metodo']    = "getDadosSubPrograma";  
$aArquivosAuxiliares[600]['ident']     = "L600";
$aArquivosAuxiliares[600]['tipolinha'] = 3;
/*
 * Projativ
 */
$aArquivosAuxiliares[650]['metodo']    = "getDadosProjAtiv";  
$aArquivosAuxiliares[650]['ident']     = "L650";
$aArquivosAuxiliares[650]['tipolinha'] = 3;

/*
 * Rubricas de despesa
 */
$aArquivosAuxiliares[700]['metodo']    = "getDadosRubricas";  
$aArquivosAuxiliares[700]['ident']     = "L700";
$aArquivosAuxiliares[700]['tipolinha'] = 3;

/*
 * Rubricas de despesa
 */
//$aArquivosAuxiliares = array();
$aArquivosAuxiliares[750]['metodo']    = "getDadosFornecedores";  
$aArquivosAuxiliares[750]['ident']     = "L750";
$aArquivosAuxiliares[750]['tipolinha'] = 3;
foreach ($aArquivosAuxiliares as $iIndice => $oArquivo) {
  
  $aLinhas                             = $oArquivosManad->{$oArquivo['metodo']}($sDataIniParametro, $sDataFimParametro, 
                                                                                $iAnoInicio);
  $iTMP                                = count($aLinhas);
  $i                                   = 0;
  $aTotaisPorLinha[$oArquivo['ident']] = $iTMP;
  foreach ($aLinhas as $oLinha) {

    db_atutermometro($i,$iTMP,'termometro',1,"Processando Tabela : {$oArquivo['ident']}");
    $oLayoutTxt->setByLineOfDBUtils($oLinha, $oArquivo['tipolinha'], 
                                    $oArquivo['ident']);
    $iContadorGeral++;
    $iTotalizadorGeral++; 
    $iTotalLinhasBlocoL++;
    $i++;
  }  
  unset($aLinhas);
}
/**
 * totalizador de linhas do bloco L
 */
$o990 = new stdClass();
$o990->reg       = 'L990';
$o990->qtd_lin_l = ($iTotalLinhasBlocoL+1);
$oLayoutTxt->setByLineOfDBUtils($o990, 3, 'L990');
$iTotalizadorGeral++;

$aTotaisPorLinha["L990"] = 1;

$o9001 = new stdClass();
$o9001->reg     = '9001';
$o9001->ind_mov = '0';
$oLayoutTxt->setByLineOfDBUtils($o9001, 3, '9001');
$iTotalizadorGeral++;

$aTotaisPorLinha["9001"] = 1;
$aTotaisPorLinha["0000"] = 1;
$aTotaisPorLinha["0001"] = 1;
$aTotaisPorLinha["0100"] = 1;          	
$aTotaisPorLinha["0990"] = 1;          	
$aTotaisPorLinha["K990"] = 1;          	
$aTotaisPorLinha["9990"] = 1;          	
$aTotaisPorLinha["9999"] = 1;          	
foreach ($aTotaisPorLinha as $sChave => $iValor) {
  
  $o9001 = new stdClass();      
  $o9001->reg     = '9900';
  $o9001->tip_reg = $sChave;
  $o9001->qtd_reg = $iValor;
  $oLayoutTxt->setByLineOfDBUtils($o9001, 3, '9900');
  $iTotalizadorGeral++;     
}

$o9001->reg     = '9900';
$o9001->tip_reg = '9900';
$o9001->qtd_reg = count($aTotaisPorLinha)+1;
$oLayoutTxt->setByLineOfDBUtils($o9001, 3, '9900');
$iTotalizadorGeral++;

$o9001->reg       = '9990';
$o9001->qtd_lin_9 = count($aTotaisPorLinha)+4;
$oLayoutTxt->setByLineOfDBUtils($o9001, 3, '9990');
$iTotalizadorGeral++;

$o9999 = new stdClass();
$o9999->reg     = '9999';
$o9999->qtd_lin = $iTotalizadorGeral+1;
$oLayoutTxt->setByLineOfDBUtils($o9999, 5, '9999');     

flush();

$nomearquivos = "{$sArqName}#Dowload do Arquivo gerado MANAD|";

echo "<script>";
echo "  listagem = '$nomearquivos';";
echo "  parent.js_montarlista(listagem,'form1');";
echo "</script>";

fechaJanela("Arquivos gerados com sucesso !");

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
  echo "  parent.db_iframe_gerararquivos.hide();";
  echo "</script>";
  exit;


}

?>
</body>
</html>