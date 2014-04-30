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
include("classes/db_veiccadcentral_classe.php");
include("classes/db_veiccadcentraldepart_classe.php");

db_postmemory($HTTP_POST_VARS);

$clveiccadcentral       = new cl_veiccadcentral;
$clveiccadcentraldepart = new cl_veiccadcentraldepart;

$db_opcao = 1;
$db_botao = true;
$sqlerro  = false;
$erro_msg = "";
if(isset($incluir)){
  db_inicio_transacao();

  $res_veiccadcentraldepart = $clveiccadcentraldepart->sql_record($clveiccadcentraldepart->sql_query(null,"ve37_coddepto",null,"ve36_coddepto = $ve36_coddepto"));
  if ($clveiccadcentraldepart->numrows > 0){
    $erro_msg = "Departamento vinculado a central. Verifique.";
    $sqlerro  = true;
    $clveiccadcentral->erro_campo = "ve36_coddepto";
  }

  if ($sqlerro == false){
    $clveiccadcentral->incluir($ve36_sequencial);

    $erro_msg = $clveiccadcentral->erro_msg;
    if ($clveiccadcentral->erro_status == "0"){
      $sqlerro = true;
    } else {
      $ve36_sequencial = $clveiccadcentral->ve36_sequencial;
      $ve36_coddepto   = $clveiccadcentral->ve36_coddepto;
    }
  }

  if ($sqlerro == false){
    $clveiccadcentraldepart->ve37_coddepto       = $ve36_coddepto;
    $clveiccadcentraldepart->ve37_veiccadcentral = $ve36_sequencial;

    $clveiccadcentraldepart->incluir(null);
    if ($clveiccadcentraldepart->erro_status == "0"){
      $sqlerro  = true;
      $erro_msg = $clveiccadcentraldepart->erro_msg;
      $clveiccadcentral->erro_campo = "ve36_coddepto";
    }
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmveiccadcentral.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","ve36_coddepto",true,1,"ve36_coddepto",true);
</script>
<?
if(isset($incluir)){
  if($clveiccadcentral->erro_status=="0" || $sqlerro==true){
    db_msgbox($erro_msg);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clveiccadcentral->erro_campo!=""){
      echo "<script> document.form1.".$clveiccadcentral->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clveiccadcentral->erro_campo.".focus();</script>";
    }
  }else{
    db_msgbox($erro_msg);
    db_redireciona("vei1_veiccadcentral005.php?chavepesquisa=$ve36_sequencial&liberaaba=true");
  }
}
?>