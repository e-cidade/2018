<?
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
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_lotenumero_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_protprocesso_classe.php");
include("classes/db_lotenumero_proc_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$cllotenumero = new cl_lotenumero;
$cllotenumero_proc = new cl_lotenumero_proc;
$clprotprocesso = new cl_protprocesso;

$db_opcao = 22;
$db_botao = false;

if(isset($alterar)){
$sqlerro = false;
$db_opcao = 2;
db_inicio_transacao();

  if($sqlerro == false){
    $codigo =  $j12_codigo;
    $cllotenumero->alterar($codigo);
    $erro_msg=$cllotenumero->erro_msg;
    if($cllotenumero->erro_status==0){
      $sqlerro=true;
    }
  }   
 
  if($sqlerro == false){
    $codigo =  $j12_codigo;
    $cllotenumero_proc->j11_processo = $p58_codproc;
    $cllotenumero_proc->j11_codigo = $j12_codigo;
    $cllotenumero_proc->alterar($j12_codigo);    
    $erro_msg=$cllotenumero->erro_msg;
    if($cllotenumero->erro_status==0){
      $sqlerro=true;
    }
  }   
db_fim_transacao();  
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   //retorno da tabela lotenumero
   $result = $cllotenumero->sql_record($cllotenumero->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);

   //retorno da tabela lotenumero_proc
   @$result2 = $cllotenumero_proc->sql_record($cllotenumero_proc->sql_query($chavepesquisa)); 
   @db_fieldsmemory($result2,0);
   $db_botao = true;
   
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" style="margin-top:25px;" >

<table align="center" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmlotenumero.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($alterar)){
  if($cllotenumero->erro_status=="0"){
    $cllotenumero->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cllotenumero->erro_campo!=""){
      echo "<script> document.form1.".$cllotenumero->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cllotenumero->erro_campo.".focus();</script>";
    };
  }else{
    $cllotenumero->erro(true,true);
  };
};
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>