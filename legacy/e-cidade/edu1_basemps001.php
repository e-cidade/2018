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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_basemps_classe.php");
include("classes/db_baseserie_classe.php");
include("classes/db_serie_classe.php");
include("classes/db_base_classe.php");
include("classes/db_turma_classe.php");
include("classes/db_regencia_classe.php");
db_postmemory($_POST);
$clbaseserie = new cl_baseserie;
$clserie     = new cl_serie;
$clbasemps   = new cl_basemps;
$clbase      = new cl_base;
$clturma     = new cl_turma;
$clregencia  = new cl_regencia;
$db_opcao    = 1;
$db_botao    = true;

$ed34_lancarhistorico = 'true';
if (isset($ed34_c_condicao) && $ed34_c_condicao == 'OP') {
 $ed34_lancarhistorico = $ed34_lancarhistorico == 't'? 'true':'false';
}
if(isset($incluir)) {

  $result = $clbasemps->sql_record($clbasemps->sql_query_file("","max(ed34_i_ordenacao)",""," ed34_i_base = $ed34_i_base AND ed34_i_serie = $ed34_i_serie"));
  if ($clbasemps->numrows>0) {

    db_fieldsmemory($result,0);
    if($max==""){
      $max = 0;
    }
  } else {
   $max = 0;
  }
  db_inicio_transacao();
  $clbasemps->ed34_i_ordenacao = ($max+1);
  $clbasemps->incluir($ed34_i_codigo);
  db_fim_transacao();
}
if (isset($alterar)) {

  $db_opcao = 2;
  db_inicio_transacao();
  $clbasemps->alterar($ed34_i_codigo);
  db_fim_transacao();
}
if (isset($excluir)) {

    $db_opcao = 3;
  db_inicio_transacao();
  $clbasemps->excluir($ed34_i_codigo);
  db_fim_transacao();
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
    <?include("forms/db_frmbasemps.php");?>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed34_i_disciplina",true,1,"ed34_i_disciplina",true);
</script>
<?
if(isset($incluir)){
 if($clbasemps->erro_status=="0"){
  $clbasemps->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clbasemps->erro_campo!=""){
   echo "<script> document.form1.".$clbasemps->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clbasemps->erro_campo.".focus();</script>";
  };
 }else{
  ?>
  <script>
   js_OpenJanelaIframe('','db_iframe_outraserie','func_outraserie.php?disciplina=<?=$ed34_i_disciplina?>&base=<?=$ed34_i_base?>&serie=<?=$ed34_i_serie?>&nperiodos=<?=$ed34_i_qtdperiodo?>&condicao=<?=$ed34_c_condicao?>&discglob=<?=$discglob?>&qtdper=<?=$qtdper?>&lLancarHistorico=<?=$ed34_lancarhistorico?>','Incluir Disciplina <?=$ed232_c_descr?> em outras etapas',true,60,160,400,230);
  </script>
  <?
  //$clbasemps->erro(false,true);
 };
};
if(isset($alterar)){
 if($clbasemps->erro_status=="0"){
  $clbasemps->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clbasemps->erro_campo!=""){
   echo "<script> document.form1.".$clbasemps->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clbasemps->erro_campo.".focus();</script>";
  };
 }else{
  ?>
  <script>
   js_OpenJanelaIframe('','db_iframe_outraseriealt','func_outraseriealt.php?disciplina=<?=$ed34_i_disciplina?>&base=<?=$ed34_i_base?>&serie=<?=$ed34_i_serie?>&nperiodos=<?=$ed34_i_qtdperiodo?>&condicao=<?=$ed34_c_condicao?>&discglob=<?=$discglob?>&qtdper=<?=$qtdper?>&lLancarHistorico=<?=$ed34_lancarhistorico?>','Alterar Disciplina <?=$ed232_c_descr?> em outras etapas',true,60,160,400,230);
  </script>
  <?
  //$clbasemps->erro(false,true);
 };
};
if(isset($excluir)){
 if($clbasemps->erro_status=="0"){
  $clbasemps->erro(true,false);
 }else{
  $clbasemps->erro(true,true);
 };
};
if(isset($cancelar)){
 echo "<script>location.href='".$clbasemps->pagina_retorno."'</script>";
}
?>
<script>
function js_refresh(){
 <?
 $sql = $clbaseserie->sql_query("","si.ed11_i_sequencia as inicial,sf.ed11_i_sequencia as final,si.ed11_i_ensino as ensino",""," ed87_i_codigo = $ed34_i_base");
 $result = $clbaseserie->sql_record($sql);
 db_fieldsmemory($result,0);
 $sql1 = $clserie->sql_query_file("","ed11_i_codigo,ed11_c_descr","ed11_i_sequencia"," ed11_i_sequencia >= $inicial AND ed11_i_sequencia <= $final AND ed11_i_ensino = $ensino");
 $result1 = $clserie->sql_record($sql1);
 $sql2 = $clbase->sql_query_file("","ed31_c_descr,ed31_i_curso",""," ed31_i_codigo = $ed34_i_base");
 $result2 = $clbase->sql_record($sql2);
 db_fieldsmemory($result2,0);
 for($x=0;$x<$clserie->numrows;$x++){
  $num = $x+1;
  db_fieldsmemory($result1,$x);
  ?>
   parent.iframe_b<?=$num?>.location.href="edu1_basemps001.php?ed34_i_base=<?=$ed34_i_base?>&ed31_c_descr=<?=$ed31_c_descr?>&curso=<?=$ed31_i_curso?>&ed34_i_serie=<?=$ed11_i_codigo?>&ed11_c_descr=<?=$ed11_c_descr?>&discglob=<?=$discglob?>&qtdper=<?=$qtdper?>";
  <?
 }
 ?>
}
</script>
