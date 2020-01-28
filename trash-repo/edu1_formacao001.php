<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("classes/db_formacao_classe.php");
include("classes/db_rechumano_classe.php");
include("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
db_postmemory($HTTP_POST_VARS);
$clformacao = new cl_formacao;
$clrechumano = new cl_rechumano;
$db_opcao = 1;
$db_botao = true;
function PegaValores($array,$tamanho){
 $retorno = "";
 for($x=1;$x<=$tamanho;$x++){
  $tem = false;
  for($y=0;$y<count($array);$y++){
   if($array[$y]==$x){
    $retorno .= "1";
    $tem = true;
    break;
   }
  }
  if($tem==false){
   $retorno .= "0";
  }
 }
 return $retorno;
}
if(isset($incluir)){
 db_inicio_transacao();
 $clformacao->incluir($ed27_i_codigo);
 db_fim_transacao();
}
if(isset($alterar)){
 db_inicio_transacao();
 $db_opcao = 2;
 $clformacao->alterar($ed27_i_codigo);
 db_fim_transacao();
}
if(isset($excluir)){
 db_inicio_transacao();
 $db_opcao = 3;
 $clformacao->excluir($ed27_i_codigo);
 db_fim_transacao();
}
if(isset($alterar2)){
}
$campos = "case when ed20_i_tiposervidor = 1
            then ed284_i_rhpessoal
            else ed285_i_cgm
           end as identificacao,
           case when ed20_i_tiposervidor = 1
            then cgmrh.z01_nome
            else cgmcgm.z01_nome
           end as z01_nome,
           ed20_i_tiposervidor,
           ed20_i_escolaridade
          ";
$result11 = $clrechumano->sql_record($clrechumano->sql_query("",$campos,""," ed20_i_codigo = $ed27_i_rechumano"));
db_fieldsmemory($result11,0);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, prototype.js, widgets/windowAux.widget.js,strings.js,widgets/dbtextField.widget.js,
               dbViewAvaliacoes.classe.js,dbmessageBoard.widget.js,dbautocomplete.widget.js,dbcomboBox.widget.js,
               datagrid.widget.js");
  db_app::load("estilos.css,grid.style.css");
?>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Formação do Recurso Humano</b></legend>
    <?include("forms/db_frmformacao.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed27_i_cursoformacao",true,1,"ed27_i_cursoformacao",true);
</script>
<?
if (isset($incluir)) {
	
  if ($clformacao->erro_status == "0") {
  	
  $clformacao->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clformacao->erro_campo!=""){
   echo "<script> document.form1.".$clformacao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clformacao->erro_campo.".focus();</script>";
  }
 }else{
  db_redireciona("edu1_formacao001.php?ed27_i_rechumano=$ed27_i_rechumano"); 	
 }
}
if(isset($alterar)){
 if($clformacao->erro_status=="0"){
  $clformacao->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clformacao->erro_campo!=""){
   echo "<script> document.form1.".$clformacao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clformacao->erro_campo.".focus();</script>";
  }
 }else{
  db_redireciona("edu1_formacao001.php?ed27_i_rechumano=$ed27_i_rechumano");
 }
}
if(isset($excluir)){
 if($clformacao->erro_status=="0"){
  $clformacao->erro(true,false);
 }else{
  db_redireciona("edu1_formacao001.php?ed27_i_rechumano=$ed27_i_rechumano");
 }
}
if(isset($alterar2)){
 db_redireciona("edu1_formacao001.php?ed27_i_rechumano=$ed27_i_rechumano");
}
if(isset($cancelar)){
 db_redireciona("edu1_formacao001.php?ed27_i_rechumano=$ed27_i_rechumano");
}
?>