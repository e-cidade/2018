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
include("classes/db_pcorcamitemproc_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clpcorcam = new cl_pcorcam;
$clpcparam = new cl_pcparam;
$clpcorcamitemproc = new cl_pcorcamitemproc;
$db_opcao = 22;
$db_botao = false;
$db_open  = false;
$instit=db_getsession("DB_instit");

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
  $result_clpcorcam = $clpcorcam->sql_record($clpcorcam->sql_query_file($retorno));
  $numrows_clpcorcam = $clpcorcam->numrows;
  if($numrows_clpcorcam > 0){
    db_fieldsmemory($result_clpcorcam,0);
  }
}else if(isset($chavepesquisa)){
  $db_opcao = 2;
  $db_botao = true;
  $db_open  = true;
}
$db_chama = 'alterar';
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
	include("forms/db_frmprocesso.php");
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
  }
}
if(isset($retorno)){
  echo "
  <script>     
      function js_db_libera(){
         parent.document.formaba.fornec.disabled=false;
         top.corpo.iframe_fornec.location.href='com1_fornec001.php?solic=false&pc21_codorc=$retorno&robson='teste'&p80_codproc=$pc80_codproc';
       }
    js_db_libera();
  </script>  ";
  
}else{
  if(isset($chavepesquisa) && $chavepesquisa!=""){
    $pc20_codorc = $chavepesquisa;
  }
  if(isset($pc20_codorc) && trim($pc20_codord)!=""){
    echo "
    <script>
        function js_db_bloqueia(){
           parent.document.formaba.fornec.disabled=true;
           top.corpo.iframe_fornec.location.href='com1_fornec001.php?solic=false&pc21_codorc=".@$pc20_codorc."&p80_codproc=$pc80_codproc';
        }\n
      js_db_bloqueia();
    </script>\n
         ";
  }else{
    echo "
    <script>
        function js_db_bloqueia(){
           parent.document.formaba.fornec.disabled=false;
           top.corpo.iframe_fornec.location.href='com1_fornec001.php?solic=false&p80_codproc=$pc80_codproc';
        }\n
      js_db_bloqueia();
    </script>\n
         ";
  }
}

if($db_opcao==22){
	
  echo "<script>document.form1.pesquisar.click();</script>";
}
if($db_open==true){
	
  $result_solic = $clpcorcamitemproc->sql_record($clpcorcamitemproc->sql_query_solicitem(null,null," distinct pc81_codproc ","","pc22_codorc=".@$chavepesquisa." and (e54_autori is null or (e54_autori is not null and e54_anulad is not null))"));
  if($clpcorcamitemproc->numrows>0){
    db_fieldsmemory($result_solic,0);
    echo "<script>
              top.corpo.iframe_orcam.location.href = 'com1_processo005.php?retorno=$chavepesquisa&pc80_codproc=$pc81_codproc';
          </script>
            ";
  }else{
    $result_pcorcamitem = $clpcorcam->sql_record($clpcorcam->sql_query_solproc(null,"pc20_codorc","","pc20_codorc=$chavepesquisa and pc22_codorc is null"));
    if($clpcorcam->numrows!=0){
    echo "<script>           
            top.corpo.iframe_orcam.location.href = 'com1_selsolicproc001.php?pc22_codorc=$chavepesquisa&op=alterar';
	  </script>
	  ";
    }else{
    echo "<script>
            alert('Usuário: \\n\\nOrçamento inexistente ou foi gerada autorização de empenho para este processo de compras.\\n\\nAdministrador:');
            top.corpo.iframe_orcam.location.href = 'com1_processo005.php';
          </script>";
    }
  }
}
?>