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
include("classes/db_db_sysfuncoes_classe.php");
include("classes/db_db_sysfuncoesparam_classe.php");
include("classes/db_db_sysfuncoescliente_classe.php");
$cldb_sysfuncoes = new cl_db_sysfuncoes;
$cldb_sysfuncoescliente = new cl_db_sysfuncoescliente;
  /*
$cldb_sysfuncoesparam = new cl_db_sysfuncoesparam;
  */
db_postmemory($HTTP_POST_VARS);
$db_opcao = 22;
$db_botao = false;
if(isset($alterar)){
  $sqlerro=false;
  db_inicio_transacao();
  $cldb_sysfuncoes->alterar($codfuncao);
  if($cldb_sysfuncoes->erro_status==0){
    $sqlerro=true;
  } 
  $erro_msg = "db_sysfuncoes : ".$cldb_sysfuncoes->erro_msg; 
  
  if(isset($db41_cliente) && $db41_cliente != ""){
//    db_msgbox("entrou para excluir");
    $cldb_sysfuncoescliente->excluir(null,"db41_funcao = $codfuncao ");
    if($cldb_sysfuncoescliente->erro_status==0){
  //    db_msgbox("erro");
      $sqlerro = true;
      $erro_msg = "db_sysfuncoescliente : ".$cldb_sysfuncoescliente->erro_msg; 
    } 
    $cldb_sysfuncoescliente->db41_funcao = $codfuncao;
    $cldb_sysfuncoescliente->incluir(null);
    if($cldb_sysfuncoescliente->erro_status==0){
      $sqlerro  = true;
      $erro_msg = "db_sysfuncoescliente : ".$cldb_sysfuncoescliente->erro_msg; 
    } 
  }else{
//    db_msgbox("entrou para excluir");
    $cldb_sysfuncoescliente->excluir(null,"db41_funcao = $codfuncao ");
    if($cldb_sysfuncoescliente->erro_status==0){
  //    db_msgbox("erro");
      $sqlerro = true;
      $erro_msg = "db_sysfuncoescliente : ".$cldb_sysfuncoescliente->erro_msg; 
    } 
    
  }

  db_fim_transacao($sqlerro);
   $db_opcao = 2;
   $db_botao = true;
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $db_botao = true;
   $result = $cldb_sysfuncoes->sql_record($cldb_sysfuncoes->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmdb_sysfuncoes.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($cldb_sysfuncoes->erro_campo!=""){
      echo "<script> document.form1.".$cldb_sysfuncoes->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldb_sysfuncoes->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
  }
}
if(isset($chavepesquisa)){
 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.db_sysfuncoesparam.disabled=false;
         top.corpo.iframe_db_sysfuncoesparam.location.href='con1_db_sysfuncoesparam001.php?db42_funcao=".@$codfuncao."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('db_sysfuncoesparam');";
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