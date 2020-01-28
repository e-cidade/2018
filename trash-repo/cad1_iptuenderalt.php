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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_iptuender_classe.php");
require_once("classes/db_iptubase_classe.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$db_botao     = 1;
$db_opcao     = 1;

$cliptubase   = new cl_iptubase;
$cliptuender  = new cl_iptuender;
$cliptubase->rotulo->label();
$cliptuender->rotulo->label();
$cliptuender->rotulo->tlabel();

if(isset($alterando)){
  $j43_matric = $j01_matric;
}

if(isset($atualizar)){

   db_inicio_transacao();
   $result = $cliptuender->sql_record($cliptuender->sql_query($j43_matric,"j43_matric","",""));
   @db_fieldsmemory($result,0);

   if($cliptuender->numrows==0){
     $cliptuender->incluir($j43_matric);
   }else{
     $cliptuender->alterar($j43_matric);
   }

   db_fim_transacao();

}else if(isset($excluir)){
  $cliptuender->excluir($j43_matric);
}else if(isset($j43_matric)){

  $result = $cliptuender->sql_record($cliptuender->sql_query($j43_matric,"iptuender.*#cgm.z01_nome","",""));

  if($cliptuender->numrows!=0){

    @db_fieldsmemory($result,0);
    $db_botao=3;
  }else{

    $result = $cliptubase->sql_record($cliptubase->sql_query($j43_matric,"z01_nome",""));
     @db_fieldsmemory($result,0);
    $db_botao=1;

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
<style type="text/css">
<!--
td {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
}
input {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
  height: 17px;
  border: 1px solid #999999;
}
-->
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<br /><br />
<table  height="430" align="center" width="790" border="0" cellspacing="0" cellpadding="0">
<form name="form1" method="post" action="">
  <tr>
    <td align="center" valign="top" bgcolor="#CCCCCC">
    <center>
     <?
    include("forms/db_frmiptuenderalt.php");
    ?>
    </td>
  </tr>
</form>
</table>
</body>
</html>
<?
if(isset($atualizar)||isset($excluir)){
  if($cliptuender->erro_status=="0"){
    $cliptuender->erro(true,false);
    if($cliptuender->erro_campo!=""){
      echo "<script> document.form1.".$cliptuender->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cliptuender->erro_campo.".focus();</script>";
    }
  }else{
    $cliptuender->erro(true,false);
    db_redireciona("cad1_iptuenderalt.php?j43_matric=$j43_matric");
  }
}
?>