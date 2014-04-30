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
include("classes/db_pcorcam_classe.php");
include("classes/db_pcparam_classe.php");
include("classes/db_pcorcamitemsol_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);
$clpcorcam = new cl_pcorcam;
$clpcparam = new cl_pcparam;
$clpcorcamitemsol = new cl_pcorcamitemsol;
$db_opcao = 22;
$db_botao = false;
$db_open  = false;
if(isset($alterar)){
  $db_opcao = 2;
  $db_botao = true;
  $sqlerro = false;
  db_inicio_transacao();
  $clpcorcam->alterar($pc20_codorc);
  $pc20_codorc = $clpcorcam->pc20_codorc;
  if($clpcorcam->erro_status==0){
    $sqlerro=true;
  }
  $erro_msg = $clpcorcam->erro_msg;
  db_fim_transacao($sqlerro);
}else if(isset($retorno)){
  $db_opcao = 2;
  $db_botao = true;
  $result_clpcorcam = $clpcorcam->sql_record($clpcorcam->sql_query_file($retorno,"pc20_codorc,pc20_hrate,pc20_dtate,pc20_obs,pc20_obs,pc20_prazoentrega, pc20_validadeorcamento"));
  $numrows_clpcorcam = $clpcorcam->numrows;
  if($numrows_clpcorcam > 0){
    db_fieldsmemory($result_clpcorcam,0);
  }
}else if(isset($chavepesquisa)){
  $db_opcao = 2;
  $db_botao = true;
  $db_open  = true;
}
$db_chama = "alterar";
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmorcamento.php");
	?>
    </center>
    </td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar) && $erro_msg!=""){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clpcorcam->erro_campo!=""){
      echo "<script> document.form1.".$clpcorcam->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clpcorcam->erro_campo.".focus();</script>";
    };
  }else{
    $retorno = "$pc20_codorc";
  }
}
if(isset($retorno)){
  echo "
  <script>     
      function js_db_libera(){
         parent.document.formaba.fornec.disabled=false;
         top.corpo.iframe_fornec.location.href='com1_fornec001.php?pc21_codorc=$retorno&solic=true&pc10_numero=$pc10_numero';
       }\n
    js_db_libera();
  </script>\n
       ";
}else{
  if(isset($chavepesquisa) && $chavepesquisa!=""){
    $pc20_codorc = $chavepesquisa;
  }
  echo "
  <script>
      function js_db_bloqueia(){
         parent.document.formaba.fornec.disabled=true;
         top.corpo.iframe_fornec.location.href='com1_fornec001.php?solic=true&pc21_codorc=".@$pc20_codorc."';
      }\n
    js_db_bloqueia();
  </script>\n
       ";
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
if($db_open==true){
  $result_itemsol = $clpcorcamitemsol->sql_record($clpcorcamitemsol->sql_query_solicitem(null,null,"distinct pc11_numero",""," pc22_codorc=$chavepesquisa and pc81_solicitem is null"));
  if($clpcorcamitemsol->numrows>0){
    db_fieldsmemory($result_itemsol,0);
    echo "<script>
            top.corpo.iframe_orcam.location.href = 'com1_orcamento005.php?retorno=$chavepesquisa&pc10_numero=$pc11_numero';
          </script>
         ";
  }else{
    $result_pcorcamitem = $clpcorcam->sql_record($clpcorcam->sql_query_solproc(null,"pc20_codorc","","pc20_codorc=$chavepesquisa and pc22_codorc is null"));
    if($clpcorcam->numrows!=0){
    echo "<script>
            top.corpo.iframe_orcam.location.href = 'com1_selsolic001.php?op=alterar&sol=true';
	  </script>
	  ";
    }else{
    echo "<script>
            alert('Usuário:\\n\\nOrçamento inexistente ou solicitação incluída em processo de compras.\\n\\nAdministrador.');
	    top.corpo.iframe_orcam.location.href = 'com1_orcamento005.php';
	  </script>
	  ";
    }
  }
}
?>