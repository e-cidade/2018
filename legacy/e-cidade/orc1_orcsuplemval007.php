<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_liborcamento.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_orcsuplem_classe.php");
require_once("classes/db_orcprojeto_classe.php");
require_once("classes/db_orcsuplemval_classe.php");
require_once("classes/db_orcdotacao_classe.php");   // instancia da classe dotação
require_once("classes/db_orcreceita_classe.php"); // receita
require_once("classes/db_orcorgao_classe.php"); // receita

db_app::import("orcamento.suplementacao.*");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clorcsuplemval = new cl_orcsuplemval;
$clorcdotacao   = new cl_orcdotacao;  // instancia da classe dotação
$clorcsuplem    = new cl_orcsuplem;
$clorcorgao     = new cl_orcorgao;
$clorcprojeto   = new cl_orcprojeto;



$clorcsuplem->rotulo->label();
$clorcsuplemval->rotulo->label();
$clorcorgao->rotulo->label();
$clorcdotacao->rotulo->label();


$op =1 ;
$db_opcao = 1;
$db_botao = true;
$anousu = db_getsession("DB_anousu");
$o39_codproj = (isset($o39_codproj)&&!empty($o39_codproj))?$o39_codproj:'null';

/**
 * verifica o tipo da Suplementacao
 */
$sSqlDadosProjeto = $clorcprojeto->sql_query_projeto($o39_codproj, "o138_sequencial,o39_usalimite");
$rsDadosProjeto   = $clorcprojeto->sql_record($sSqlDadosProjeto);
$oDadosProjeto    = db_utils::fieldsMemory($rsDadosProjeto, 0);
//------------------------------------------
if (isset($pesquisa_dot) && $o47_coddot!=""){

	// foi clicado no botão "pesquisa" da tela
   $res = $clorcdotacao->sql_record($clorcdotacao->sql_query(db_getsession("DB_anousu"),$o47_coddot));
   if ($clorcdotacao->numrows > 0 ){

      db_fieldsmemory($res,0); // deve existir 1 registro

      $resdot= db_dotacaosaldo(8,2,2,"true","o58_coddot=$o47_coddot",db_getsession("DB_anousu"),$anousu.'-01-01',$anousu.'-12-31');
      db_fieldsmemory($resdot,0);
       // $atual_menos_reservado 
   }  
}  
//------------------------------------------
$limpa_dados = false;

if (isset($incluir)) {
  
  $sSqlValorTotalOrcamento  = "select sum(o58_valor) as valororcamento ";
  $sSqlValorTotalOrcamento .= "  from orcdotacao ";
  $sSqlValorTotalOrcamento .= " where o58_anousu = ".db_getsession("DB_anousu");
  $rsValorOrcamento        = db_query($sSqlValorTotalOrcamento);
  $nValorOrcamento         = 0;
  if (pg_num_rows($rsValorOrcamento) > 0) {
    $nValorOrcamento = db_utils::fieldsMemory($rsValorOrcamento, 0)->valororcamento;
  }
  /**
   * Verificamos se existe parametro para o orcamento no ano 
   */
  $sqlerro        = false;
  $limpa_dados    = true;
  $nPercentualLoa = 0;
  $aParametro = db_stdClass::getParametro("orcsuplementacaoparametro", array(db_getsession("DB_anousu")));
  if (count($aParametro) > 0) {
    $nPercentualLoa = $aParametro[0]->o134_percentuallimiteloa;
  } else {
    
    db_msgbox("Parametros das suplementações não configurados.");
    $sqlerro     = true;
  }
  $limiteloa            = ($nPercentualLoa*$nValorOrcamento)/100;
  $sSqlSuplementacoes   = $clorcsuplem->sql_query(null,"*","o46_codsup","orcprojeto.o39_codproj= $o39_codproj");
  $rsSuplementacoes     = $clorcsuplem->sql_record($sSqlSuplementacoes);
  $aSuplementacao       = db_utils::getCollectionByRecord($rsSuplementacoes);
  $valorutilizado       = 0;
  if ($oDadosProjeto->o39_usalimite == 't') {
        
    foreach ($aSuplementacao as $oSuplem) {
          
      $oSuplementacao = new Suplementacao($oSuplem->o46_codsup);
      $valorutilizado += $oSuplementacao->getvalorSuplementacao();  
     }
     if ($valorutilizado + $o47_valor > $limiteloa) {
      
       $sMsgLimite  = "Limite de {$nPercentualLoa} do LOA foi ultrapassado.\\nNão poderá ser realizado a suplementação.\\n";   
       $sMsgLimite .= "Valor Orçamento: ".trim((db_formatar($nValorOrcamento, "f")))."\\n";
       $sMsgLimite .= "Valor Limite: ".trim((db_formatar($limiteloa, "f")))."\\n";   
       $sMsgLimite .= "Valor Utilizado: ".trim((db_formatar($valorutilizado, "f")))."\\n";   
       db_msgbox($sMsgLimite);
       $sqlerro     = true;
       $limpa_dados = false;
     } 
  }
  
  // pressionado botao incluir na tela
  db_inicio_transacao();
  if ((isset($o47_coddot)  && $o47_coddot != "") && !$sqlerro) {
    
    
    $clorcsuplemval->o47_valor          = $o47_valor; 
    $clorcsuplemval->o47_anousu         = db_getsession("DB_anousu");
    $clorcsuplemval->o47_concarpeculiar = "{$o58_concarpeculiar}";
    $clorcsuplemval->incluir($o46_codsup,db_getsession("DB_anousu"),$o47_coddot);
    if ($clorcsuplemval->erro_status == 0){
       $sqlerro = true;
       db_msgbox($clorcsuplemval->erro_msg);
       $limpa_dados = false;
    }  
  } else if (isset($o07_sequencial) && $o07_sequencial != ""  && !$sqlerro) {
    
    /**
     * incluimos a projecao para criarmos a suplementação
     */
    $oDaoDespesaPPA = db_utils::getDao("orcsuplemdespesappa");
    $oDaoDespesaPPA->o136_orcsuplem            = $o46_codsup;
    $oDaoDespesaPPA->o136_ppaestimativadespesa = $o07_sequencial;
    $oDaoDespesaPPA->o136_valor                = abs($o47_valor);
    $oDaoDespesaPPA->o136_concarpeculiar       = $o58_concarpeculiar;
    $oDaoDespesaPPA->incluir(null);
    if ($oDaoDespesaPPA->erro_status == 0) {
      
      $sqlerro = true;
      db_msgbox($oDaoDespesaPPA->erro_msg);
      $limpa_dados = false;
    } 
  }
  db_fim_transacao($sqlerro);
   
} elseif(isset($opcao) && $opcao=="excluir" ){
  
  
  
  $limpa_dados = true;
  // clicou no exlcuir, já exlcui direto, nem confirma nada
  db_inicio_transacao();
  $sqlerro  = false;
  if ($tipo == 1) {
    
    $clorcsuplemval->excluir($o46_codsup,$anousu,$o47_coddot);
    if ($clorcsuplemval->erro_status == 0){
       $sqlerro = true;
       $limpa_dados = false;
    }  
    db_msgbox($clorcsuplemval->erro_msg);
  } else {
    
    $oDaoDespesaPPA = db_utils::getDao("orcsuplemdespesappa");
    $oDaoDespesaPPA->excluir($o47_coddot);
    if ($oDaoDespesaPPA->erro_status == 0) {

      $sqlerro     = true;
      $limpa_dados = false;
    }  
    db_msgbox($oDaoDespesaPPA->erro_msg);
  }
  db_fim_transacao($sqlerro);

}   
if ($limpa_dados ==true) {
  
   $o47_coddot     = "";
   $o58_orgao      = "";
   $o40_descr      = "";
   $o56_elemento   = "";
   $o56_descr      = "";
   $o58_codigo     = "";
   $o15_descr      = "";
   $o47_valor      = "";
   $o07_sequencial = "";
   $atual_menos_reservado = "";
}  

// --------------------------------------
// calcula total das reduções
$oSuplementacao = new Suplementacao($o46_codsup);
$soma_suplem    = $oSuplementacao->getvalorSuplementacao(); 
// --------------------------------------


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="480" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmorcsuplemval.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?

if(isset($incluir) || isset($alterar) || isset($excluir)){
  if($clorcsuplemval->erro_status=="0"){
      $clorcsuplemval->erro(true,false);
      $db_botao=true;
      echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
      if($clorcsuplemval->erro_campo!=""){
        echo "<script> document.form1.".$clorcsuplemval->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clorcsuplemval->erro_campo.".focus();</script>";
      };
  }else{
       $clorcsuplemval->erro(true,false);
  };
};

?>