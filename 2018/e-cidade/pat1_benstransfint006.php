<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_benstransf_classe.php");
include("classes/db_benstransfcodigo_classe.php");
include("classes/db_benstransfdes_classe.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_db_depusu_classe.php");
include("classes/db_db_depart_classe.php");
$cldb_depusu = new cl_db_depusu;
$clbenstransf = new cl_benstransf;
$clbenstransfcodigo = new cl_benstransfcodigo;
$clbenstransfdes = new cl_benstransfdes;
$cldb_usuarios = new cl_db_usuarios;
$cldb_depart = new cl_db_depart;
db_postmemory($HTTP_POST_VARS);
$db_opcao = 3;
$db_botao = true;

if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();
  if($sqlerro==false){
    $clbenstransfdes->t94_codtran=$t93_codtran;
    $clbenstransfdes->excluir($t93_codtran);
    if($clbenstransfdes->erro_status==0){
      $sqlerro=true;
    } 
    $erro_msg = $clbenstransfdes->erro_msg;
  } 
  if($sqlerro == false){ 
    $clbenstransfcodigo->sql_record($clbenstransfcodigo->sql_query_file($t93_codtran));
    if($clbenstransfcodigo->numrows>0){
      $clbenstransfcodigo->t95_codtran=$t93_codtran;
      $clbenstransfcodigo->excluir($t93_codtran);
      if($clbenstransfcodigo->erro_status==0){
	$sqlerro=true;
      } 
      $erro_msg = $clbenstransfcodigo->erro_msg;
    }
  }
  if($sqlerro == false){ 
    $clbenstransf->excluir($t93_codtran);
    if($clbenstransf->erro_status==0){
      $sqlerro=true;
    } 
    $erro_msg = $clbenstransf->erro_msg; 
  } 
  db_fim_transacao($sqlerro);
}else if(isset($chavepesquisa)){
   $result = $clbenstransfdes->sql_record($clbenstransfdes->sql_query($chavepesquisa,null,"t94_codtran as t93_codtran,t94_depart,db_depart.descrdepto as depto_destino,t93_data,t93_depart"));
   db_fieldsmemory($result,0);
}else{
$db_opcao = 33;
$db_botao = false;
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmbenstransf.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($excluir)){
  db_msgbox($erro_msg);
  if($sqlerro==true){
    if($clbenstransf->erro_campo!=""){
      echo "<script> document.form1.".$clbenstransf->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clbenstransf->erro_campo.".focus();</script>";
    };
  }else{
 echo "
  <script>
    function js_db_tranca(){
      parent.location.href='pat1_benstransf003.php';
    }\n
    js_db_tranca();
  </script>\n
 ";
  }
}
if(isset($chavepesquisa)){
 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.benstransfcodigo.disabled=false;
         top.corpo.iframe_benstransfcodigo.location.href='pat1_benstransfcodigo001.php?db_opcaoal=33&t95_codtran=".@$t93_codtran."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('benstransfcodigo');";
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}
 if($db_opcao==22||$db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
 }
?>