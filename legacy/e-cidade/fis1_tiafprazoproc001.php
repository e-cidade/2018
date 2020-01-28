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
include("classes/db_tiafprazo_classe.php");
include("classes/db_tiaf_classe.php");
include("classes/db_tiafprazoproc_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_POST_VARS,2);
$cltiafprazoproc = new cl_tiafprazoproc;
$cltiafprazo = new cl_tiafprazo;
$cltiaf = new cl_tiaf;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$db_opcao = 1;
$db_botao = true;
$tipobotao = "Incluir";

//////////// INCLUIR //////////////////////////////

if(isset($incluir) && $incluir == "Incluir"){
  db_inicio_transacao();
  $cltiafprazo->y96_codtiaf = $y90_codtiaf;
  $cltiafprazo->y96_prazo = $y96_prazo;
  $cltiafprazo->y96_codigo = $cltiafprazo->y96_codigo;
  $cltiafprazo->incluir();
  if ($cltiafprazo->erro_status==0){
	$erro_msg= $cltiafprazo->erro_msg;
	db_msgbox("Incluir ".$erro_msg);
    $sqlerro=true;
  }  
  if(isset($y97_codproc) && $y97_codproc != ""){
	  $cltiafprazoproc->y97_codproc  = $y97_codproc;
	  $cltiafprazoproc->y97_codprazo = $cltiafprazo->y96_codigo;
	  $cltiafprazoproc->incluir($y90_codtiaf);
	  if ($cltiafprazo->erro_status==0){
		$erro_msg= $cltiafprazo->erro_msg;
		db_msgbox("Incluir ".$erro_msg);
	    $sqlerro=true;
	  }
  }
  db_fim_transacao($sqlerro);
}

//////////////ALTERAR /////////////////////////////

if(isset($incluir) && $incluir == "Alterar"){
  db_inicio_transacao();
  $cltiafprazo->y96_codtiaf = $y90_codtiaf;
  $cltiafprazo->y96_prazo = $y96_prazo;
  $cltiafprazo->y96_codigo = $cltiafprazo->y96_codigo;
  $cltiafprazo->incluir();
  if ($cltiafprazo->erro_status==0){
	$erro_msg= $cltiafprazo->erro_msg;
	db_msgbox("Alterar ".$erro_msg);
    $sqlerro=true;
  }  
  if(isset($y97_codproc) && $y97_codproc != ""){
	  $cltiafprazoproc->y97_codproc  = $y97_codproc;
	  $cltiafprazoproc->y97_codprazo = $cltiafprazo->y96_codigo;
	  $cltiafprazoproc->incluir($y90_codtiaf);
	  if ($cltiafprazo->erro_status==0){
		$erro_msg= $cltiafprazo->erro_msg;
		db_msgbox("Alterar ".$erro_msg);
	    $sqlerro=true;
	  }
  }
  db_fim_transacao($sqlerro);
}

////////////////// EXCLUIR /////////////////////////////

if(isset($incluir) && $incluir == "Excluir"){
  db_inicio_transacao();
  $cltiafprazo->y96_codtiaf = $y90_codtiaf;
  $cltiafprazo->y96_prazo = $y96_prazo;
  $cltiafprazo->y96_codigo = $cltiafprazo->y96_codigo;
  $cltiafprazo->incluir();
  if ($cltiafprazo->erro_status==0){
	$erro_msg= $cltiafprazo->erro_msg;
	db_msgbox("Excluir ".$erro_msg);
    $sqlerro=true;
  }  
  if(isset($y97_codproc) && $y97_codproc != ""){
	  $cltiafprazoproc->y97_codproc  = $y97_codproc;
	  $cltiafprazoproc->y97_codprazo = $cltiafprazo->y96_codigo;
	  $cltiafprazoproc->incluir($y90_codtiaf);
	  if ($cltiafprazo->erro_status==0){
		$erro_msg= $cltiafprazo->erro_msg;
		db_msgbox("Excluir ".$erro_msg);
	    $sqlerro=true;
	  }
  }
  db_fim_transacao($sqlerro);
}
///////////////////////////////////////////////////

if (isset($opcao) && $opcao != ""){
	$tipobotao = ucfirst($opcao);
	if (isset($opcao) && $opcao == "excluir"){
		$db_opcao = 3;
	}elseif (isset($opcao) && $opcao == "alterar"){
		$db_opcao = 2;
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
		include("forms/db_frmtiafprazoproc.php");
		/* <table width="790" border="1" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
		  <tr> 
		    <td width="360" height="18">&nbsp;</td>
		    <td width="263">&nbsp;</td>
		    <td width="25">&nbsp;</td>
		    <td width="140">&nbsp;</td>
		  </tr>
		</table> */
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
if(isset($incluir)){
  if($cltiafprazoproc->erro_status=="0"){
    $cltiafprazoproc->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cltiafprazoproc->erro_campo!=""){
      echo "<script> document.form1.".$cltiafprazoproc->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cltiafprazoproc->erro_campo.".focus();</script>";
    }
  }else{
    $cltiafprazoproc->erro(true,true);
  }
}
?>