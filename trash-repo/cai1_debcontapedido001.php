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
include("classes/db_debcontapedido_classe.php");
include("classes/db_debcontapedidocgm_classe.php");
include("classes/db_debcontapedidomatric_classe.php");
include("classes/db_debcontapedidoinscr_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$cldebcontapedido = new cl_debcontapedido;
$cldebcontapedidocgm = new cl_debcontapedidocgm;
$cldebcontapedidomatric = new cl_debcontapedidomatric;
$cldebcontapedidoinscr = new cl_debcontapedidoinscr;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $cldebcontapedido->d63_datalanc=date("Y-m-d",db_getsession("DB_datausu"));
  $cldebcontapedido->d63_horalanc=db_hora();
  $cldebcontapedido->d63_instit=db_getsession("DB_instit");
  $cldebcontapedido->incluir($d63_codigo);
  $codigo=$cldebcontapedido->d63_codigo;
  $erro_msg=$cldebcontapedido->erro_msg;   
  if ($cldebcontapedido->erro_status==0){
  	$sqlerro=true;
  }
  if ($sqlerro==false){
  if (isset ($tipo) && $tipo == "CGM") {
  	$cldebcontapedidocgm->d70_numcgm=$codtipo;
	$cldebcontapedidocgm->incluir($codigo);
	if ($cldebcontapedidocgm->erro_status==0){
  		$sqlerro=true;
  	}
  } else	if (isset ($tipo) && $tipo == "MATRIC") {
  	$cldebcontapedidomatric->d68_matric=$codtipo;
	$cldebcontapedidomatric->incluir($codigo);
	if ($cldebcontapedidomatric->erro_status==0){
  		$sqlerro=true;
  	}
	
  } else  if (isset ($tipo) && $tipo == "INSCR") {
	$cldebcontapedidoinscr->d69_inscr=$codtipo;
	$cldebcontapedidoinscr->incluir($codigo);
	if ($cldebcontapedidoinscr->erro_status==0){
  		$sqlerro=true;
  	}
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
<table width="790" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmdebcontapedido.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
	db_msgbox($erro_msg);
  if($cldebcontapedido->erro_status=="0"){  	
    //$cldebcontapedido->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cldebcontapedido->erro_campo!=""){
      echo "<script> document.form1.".$cldebcontapedido->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldebcontapedido->erro_campo.".focus();</script>";
    };
  }else{
  	echo "<script>
               location.href='cai1_debcontapedido002.php?chavepesquisa=$codigo';
               parent.iframe_debito.location.href='cai4_debcontapeddeb001.php?tipo=$tipo&codtipo=$codtipo&codigo=$codigo';\n
               parent.mo_camada('debito');
               parent.document.formaba.debito.disabled = false;\n
	 </script>";
    //$cldebcontapedido->erro(true,true);
  };
};
?>