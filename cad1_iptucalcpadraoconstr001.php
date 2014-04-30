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
include("classes/db_iptucalcpadraoconstr_classe.php");
include("classes/db_iptucalcpadrao_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cliptucalcpadraoconstr = new cl_iptucalcpadraoconstr;
$cliptucalcpadrao = new cl_iptucalcpadrao;
$db_opcao = 22;
$db_botao = false;

//echo "j11_matric= $j11_matric  forma=$forma  exec=$exec perc = $perc j11_iptucalcpadrao= $j11_iptucalcpadrao ";  

if(isset($alterar) || isset($excluir) || isset($incluir)){
  $sqlerro = false;
  /*
$cliptucalcpadraoconstr->j11_sequencial = $j11_sequencial;
$cliptucalcpadraoconstr->j11_iptucalcpadrao = $j11_iptucalcpadrao;
$cliptucalcpadraoconstr->j11_matric = $j11_matric;
$cliptucalcpadraoconstr->j11_idcons = $j11_idcons;
$cliptucalcpadraoconstr->j11_vlrcons = $j11_vlrcons;
  */
}
if(isset($incluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $cliptucalcpadraoconstr->incluir($j11_sequencial);
    $erro_msg = $cliptucalcpadraoconstr->erro_msg;
    if($cliptucalcpadraoconstr->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($alterar)){
  if($sqlerro==false){
    db_inicio_transacao();
    $cliptucalcpadraoconstr->alterar($j11_sequencial);
    $erro_msg = $cliptucalcpadraoconstr->erro_msg;
    if($cliptucalcpadraoconstr->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($excluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $cliptucalcpadraoconstr->excluir($j11_sequencial);
    $erro_msg = $cliptucalcpadraoconstr->erro_msg;
    if($cliptucalcpadraoconstr->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($opcao)){
   $result = $cliptucalcpadraoconstr->sql_record($cliptucalcpadraoconstr->sql_query($j11_sequencial));
   if($result!=false && $cliptucalcpadraoconstr->numrows>0){
     db_fieldsmemory($result,0);
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
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmiptucalcpadraoconstr.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
    db_msgbox($erro_msg);
/*
    if($clpagordemrec->erro_campo!=""){
        echo "<script> document.form1.".$cliptucalcpadraoconstr->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$cliptucalcpadraoconstr->erro_campo.".focus();</script>";
    }
*/
}
?>