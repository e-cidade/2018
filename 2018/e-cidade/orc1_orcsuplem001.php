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

require("libs/db_stdlib.php");
require("std/db_stdClass.php");
require("libs/db_utils.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_orcsuplem_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_orcsuplemtipo_classe.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_orcprojeto_classe.php");
include("classes/db_orcreservasup_classe.php");
include("classes/db_orcreserva_classe.php");
include("classes/db_orcsuplemrec_classe.php");
include("classes/db_orcsuplemval_classe.php");
include("classes/db_orcsuplementacaoparametro_classe.php");
db_app::import("orcamento.suplementacao.*");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$clorcsuplem     = new cl_orcsuplem;
$clorcsuplemrec  = new cl_orcsuplemrec;
$clorcsuplemval  = new cl_orcsuplemval;
$clorcreservasup = new cl_orcreservasup;
$clorcreserva    = new cl_orcreserva;
$clorcprojeto    = new cl_orcprojeto;
$clorcsuplemtipo = new cl_orcsuplemtipo;
$clcriaabas      = new cl_criaabas;

$clorcprojeto->rotulo->label();

$db_opcao = 1;
$db_botao = true;

if(isset($excluir) && $codsup !=""){
  db_inicio_transacao();
  // apaga orcreservasup
  // apaga orcreserva
  // apaga orcsuplemrec
  // apaga orcsuplemval
  // apaga orcsuplem
  $sqlerro     = false;
  // procura todas as reserva da suplementação
  $res = $clorcreservasup->sql_record($clorcreservasup->sql_query_file(null,"o81_codres",null,"o81_codsup = $codsup"));
  if ($clorcreservasup->numrows > 0){
      $rows = $clorcreservasup->numrows;
      for ($x=0;$x < $rows ;$x++){
          db_fieldsmemory($res,$x);
          $clorcreservasup->excluir($o81_codres);
          if ($clorcreservasup->erro_status == 0){
               $sqlerro = true;
              db_msgbox($clorcreservasup->erro_msg);
          }  
          $clorcreserva->excluir($o81_codres); // nessa tabela podem existir varias reservas
          if ($clorcreserva->erro_status == 0){
               $sqlerro = true;
               db_msgbox($clorcreserva->erro_msg);
          }
      }
  }       
  $clorcsuplemrec->excluir($codsup);
  if ($clorcsuplemrec->erro_status == 0){
     $sqlerro = true;
     db_msgbox($clorcsuplemrec->erro_msg);
  }

  $clorcsuplemval->excluir($codsup,db_getsession("DB_anousu"));
  if ($clorcsuplemval->erro_status == 0){
      $sqlerro = true;
      db_msgbox($clorcsuplemval->erro_msg);
  }
  $clorcsuplem->excluir($codsup);  
  if ($clorcsuplem->erro_status == "0" ){
      $sqlerro = true;
      db_msgbox($clorcsuplem->erro_msg);
  }  

  db_fim_transacao($sqlerro);

}

$o39_usalimite        = 'f';
$o138_sequencial      = '';
$lDisabled = false;
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
$nPercentualLoa = 0;
$aParametro = db_stdClass::getParametro("orcsuplementacaoparametro", array(db_getsession("DB_anousu")));
if (count($aParametro) > 0) {
  $nPercentualLoa = $aParametro[0]->o134_percentuallimiteloa;
} else {
  
  db_msgbox("Parametros das suplementações não configurados.");
  $lDisabled = true;
}
if (isset($chavepesquisa) && $chavepesquisa !="") {
  
   $sSqlProjeto = $clorcprojeto->sql_query_projeto($chavepesquisa);
   $res =  $clorcprojeto->sql_record($sSqlProjeto);
   if ($clorcprojeto->numrows > 0){
      
     db_fieldsmemory($res,0);
     $limiteloa            = db_formatar(($nPercentualLoa*$nValorOrcamento)/100,'f');
     $sSqlSuplementacoes   = $clorcsuplem->sql_query(null,"*","o46_codsup","orcprojeto.o39_codproj= $o39_codproj");
     $rsSuplementacoes     = $clorcsuplem->sql_record($sSqlSuplementacoes);
     $aSuplementacao       = db_utils::getCollectionByRecord($rsSuplementacoes);
     $valorutilizado       = 0;
     if ($o39_usalimite == 't') {
        
       foreach ($aSuplementacao as $oSuplem) {
          
         $oSuplementacao = new Suplementacao($oSuplem->o46_codsup);
         $valorutilizado += $oSuplementacao->getvalorSuplementacao();  
       }
     }
   }  
}  
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_incluir(projeto,tiposup){
  js_OpenJanelaIframe('top.corpo','db_iframe_suplementacao','orc1_orcsuplem008.php?projeto='+projeto+'&tiposup='+tiposup,'Pesquisa',true);
}  
function js_alterar(projeto,codsup){
  js_OpenJanelaIframe('top.corpo','db_iframe_suplementacao','orc1_orcsuplem008.php?projeto='+projeto+'&codsup='+codsup,'Pesquisa',true);
  db_iframe_suplementacao.liberarJanBTFechar(false) ;
} 
function js_excluir(projeto,codsup){ 
  if (confirm('Deseja Excluir a Suplementação '+codsup)==true){
    <?  echo " location.href='".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+projeto+'&excluir=true&projeto='+projeto+'&codsup='+codsup";  ?>
  }
}  

function js_fechar(){
  db_iframe_suplementacao.hide();
  chave = document.form1.o39_codproj.value;
  <?
  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  ?>
}  

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_orcprojeto','func_orcprojeto001.php?funcao_js=parent.js_preenchepesquisa|o39_codproj','Pesquisa',true);
}

function js_preenchepesquisa(chave){
  db_iframe_orcprojeto.hide();
  <?
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  ?>
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr> 
<td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
<br><br>

<form name=form1>
<table border=0 style="border:1px solid #999999 ">
<tr>
<td colspan=2>
   <fieldset><legend><b>Projeto</b></legend>
    <table border=0>
    <tr>
      <td><b> Projeto </b></td>
      <td><? db_input('o39_codproj',4,$Io39_codproj,true,'text',3) ?></td>
    </tr>
    <tr>
      <td><b>Descrição</b></td>
      <td><? db_input('o39_descr',40,'',true,'text',3) ?></td>
    </tr>
    <tr>
      <td><b>Data Fechamento </b></td>
      <td><? db_inputdata('o51_data',@$o51_data_dia,@$o51_data_mes,@$o51_data_ano,true,'text',3) ?></td>
    </tr>
    <?
    if ($o39_usalimite == 't') {
    ?>
      <tr>
      <td><b>Limite LOA: </b></td>
      <td><? db_input('limiteloa',20,'',true,'text',3);
      ?></td>
    </tr>
    <tr>
      <td><b>Valor Utilizado: </b></td>
      <td><? db_input('valorutilizado',20,'',true,'text',3);
      ?></td>
    </tr>
    <?  
    }
     ?>
    </table>    
    </fieldset>
</td>   
<td valign=top>
   <fieldset><legend><b>Nova Suplementação</b></legend>
   <table border=0>
   <tr>
     <td><b>Tipo </b></td>
     <td> <? 
      if ($db_opcao == 1) {

        $sSqlTipoSuplem = $clorcsuplemtipo->sql_query("","o48_tiposup as o46_tiposup,o48_descr","o48_tiposup");
	      $rtipo          = $clorcsuplemtipo->sql_record($sSqlTipoSuplem);  
        db_fieldsmemory($rtipo,0);
        db_selectrecord("o46_tiposup",$rtipo,false,$db_opcao);
        
	    } else {  
        db_input('o46_tiposup',6,'',true,'true',3);
	    } 
	 ?>
     </td>
   </tr>
   <tr>
     <td colspan=2> &nbsp; </td>
   </tr>
   <tr>
      <td> &nbsp; </td>
      <td align=center>
          <input style="width:145px" type=button name="" 
             value="Lançar Suplementação" 
             onclick="js_incluir(<?=$o39_codproj?>,document.form1.o46_tiposup.value); " <?=(@$o51_data!="" || $lDisabled?"disabled":""); ?>  >
          <input style="width:145px" type=button name="" value="Pesquisar Projeto" onclick="js_pesquisa();"></td>
    </tr>

   </table>
   </fieldset>

</td>
</tr>

<tr>
<td colspan=3 height=15px> </td>
</tr>

<tr>
<td colspan=3 height=400px valign=top>
  <fieldset><legend><b>Suplementações</b></legend> 
  <div style="height:300px;overflow-y:scroll">
  <table border=0 width=98%>
  <tr style="background-color:#AAAAAA;height:15px" >
   <td>CODSUP</td>
   <td COLSPAN=2>TIPO </td>
   <td>DATA</td>
   <td>PROCESSADO </td>
   <td>USUÁRIO </td>
   <td>ALTERAR </td>
   <td>EXCLUIR </td>

  </tr>
  <?
   // se projeto processado, botoes alterar e excluir são bloqueados
   // se o51_codproj = processado 
   if (!isset($o39_codproj)){ 
     $o39_codproj='null';
   };
   $res = $clorcsuplem->sql_record($clorcsuplem->sql_query(null,"*","o46_codsup","orcprojeto.o39_codproj= $o39_codproj" ));
   if ($clorcsuplem->numrows > 0){
      $op = '';
      for ($x=0;$x < $clorcsuplem->numrows;$x++){
         db_fieldsmemory($res,$x,true);
	 $op = '';
	 ?>
         <tr style="background-color:white;height:15px"> 
	  <td><?=$o46_codsup ?></td>
	  <td><?=$o46_tiposup ?></td>
          <td><?=$o48_descr ?></td>
          <td><?=$o46_data ?></td>
          <td><?=$o49_data ?></td>
          <td><?=$nome ?></td>
          <?
	    if ($o49_data!='') {
	       $op='disabled';
	    }  
	  ?>
          <td><input type=button value=Alterar onClick="js_alterar(<?=$o39_codproj?>,<?=$o46_codsup ?>); " <?=$op?> ></td>
          <td><input type=button value=Excluir onClick="js_excluir(<?=$o39_codproj?>,<?=$o46_codsup ?>); " <?=$op?> ></td>
	 </tr>
	 <?
      }	
   }    
  ?>
  </table>
  <div>
  </fieldset>
</td>
</tr>
</table>

</form>



 <?
  // include("forms/db_frmorcsuplem.php");
  ?>
</td>
</tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if (!isset($chavepesquisa)){
  echo "<script> js_pesquisa(); </script>";
}  

?>