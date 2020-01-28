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
include("classes/db_pcorcamdescla_classe.php");
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clpcorcamdescla = new cl_pcorcamdescla;
$db_opcao = 1;
$db_botao = true;

$vetor = split(",",$orcamitem);
$item  = $vetor[sizeof($vetor)-1];

$result_descla = $clpcorcamdescla->sql_record($clpcorcamdescla->sql_query_file(null,null,"*",null,"pc32_orcamitem = $item and pc32_orcamforne = $pc32_orcamforne"));
if($clpcorcamdescla->numrows > 0){
  db_fieldsmemory($result_descla,0);
  $db_opcao = 2;
}

$sqlerro  = false;
$erro_msg = "";
if(isset($incluir)){
  db_inicio_transacao();
  for($i = 0; $i < count($vetor); $i++){
       $clpcorcamdescla->incluir($vetor[$i],$pc32_orcamforne);
       if ($clpcorcamdescla->erro_status == 0){
            $sqlerro  = true;
            $erro_msg = $clpcorcamdescla->erro_msg;
            break;
       }
  }

  if (trim($erro_msg)!=""){
       db_msgbox($erro_msg);
  }

  db_fim_transacao($sqlerro);
}else if(isset($alterar)){
  db_inicio_transacao();
  for($i = 0; $i < count($vetor); $i++){
       $clpcorcamdescla->alterar($vetor[$i],$pc32_orcamforne);
       if ($clpcorcamdescla->erro_status == 0){
            $sqlerro  = true;
            $erro_msg = $clpcorcamdescla->erro_msg;
            break;
       }
  }

  if (trim($erro_msg)!=""){
       db_msgbox($erro_msg);
  }

  db_fim_transacao($sqlerro);
}else if(isset($excluir)){
  db_inicio_transacao();
  for($i = 0; $i < count($vetor); $i++){
       $clpcorcamdescla->excluir($vetor[$i],$pc32_orcamforne);
       if ($clpcorcamdescla->erro_status == 0){
            $sqlerro  = true;
            $erro_msg = $clpcorcamdescla->erro_msg;
            break;
       }
  }
  
  if (trim($erro_msg)!=""){
       db_msgbox($erro_msg);
  }

  db_fim_transacao($sqlerro);
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
<!--
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
-->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmpcorcamdescla.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
//db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($incluir) || isset($alterar) || isset($excluir)){
  if($clpcorcamdescla->erro_status=="0"){
    $clpcorcamdescla->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clpcorcamdescla->erro_campo!=""){
      echo "<script> document.form1.".$clpcorcamdescla->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clpcorcamdescla->erro_campo.".focus();</script>";
    };
  }else{
    echo "
    <script>
    document.form1.fechar.click();
    </script>
    ";
  };
};
?>