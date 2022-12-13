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
include("classes/db_tiaftipodoc_classe.php");
include("classes/db_tiafdoc_classe.php");
include("classes/db_tiaf_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS,2);

$clrotulo      = new rotulocampo;
$cltiaftipodoc = new cl_tiaftipodoc;
$cltiafdoc     = new cl_tiafdoc;
$cltiaf        = new cl_tiaf;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

$cltiaftipodoc->rotulo->label();
$cltiafdoc->rotulo->label();
$cltiaf->rotulo->label();
$clrotulo->label("z01_nome");
$clrotulo->label("q03_descr");
$sqlerro = false;
//$opcao = "incluir";

/////////////// VARIAVEIS DO FORMULARIO /////////////

//$tipobotao = "Incluir";                 //tipo do botao
$db_botao = false;  
echo "<script> passa = 's'; </script>";   //variavel js para a func saber se passa para proxima ab ou naun
$tipoancgeral = "1";                      //Tipo geral das ancoras
$tipoanctiaf = "3";                       //tipo da ancora do tiaf 
$db_opcao = "1";                          //opcao geral dos campos
 
/////////////// ROTINA DE INCLUS?O NO BANCO //////////////

//db_msgbox($tipobotao);
//db_msgbox($incluir);
if (isset($incluir) && $incluir == "Incluir"){
	//db_msgbox("entrou!");
	db_inicio_transacao();
	$cltiafdoc->y99_coddoc = $cltiafdoc->y99_coddoc;
	$cltiafdoc->y99_codtiaf = $y90_codtiaf;
	$cltiafdoc->y99_tiafdoc = $y98_tiafdoc;
	$cltiafdoc->y99_dtini   = $y99_dataini_ano."-".$y99_dataini_mes."-".$y99_dataini_dia;
	$cltiafdoc->y99_dtfim   = $y99_datafim_ano."-".$y99_datafim_mes."-".$y99_datafim_dia;
	$cltiafdoc->y99_obs     = $y99_obs;
	$cltiafdoc->incluir($cltiafdoc->y99_coddoc); 
	if ($cltiafdoc->erro_status==0){
		$erro_msg= $cltiafdoc->erro_msg;
    	$sqlerro=true;
    	//db_msgbox("Erro!");
    }else{
        
    }
	db_fim_transacao($sqlerro);
	//db_msgbox($erro_msg."  ".$erro_msg);
} 

////////////// ROTINA DE EXCLUS?O //////////////////////////////

if (isset($incluir) && $incluir == "Excluir"){
	db_inicio_transacao();
	$cltiafdoc->excluir("$y99_coddoc");
	$y99_coddoc = "";
	if ($cltiafdoc->erro_status==0){
		$erro_msg= $cltiafdoc->erro_msg;
    	$sqlerro=true;
    }else{
        
    }
	db_fim_transacao($sqlerro);
	//db_msgbox($erro_msg."  ".$erro_msg);
}

////////////// ROTINA DE ALTERAÇÃO //////////////////////////////

if (isset($incluir) && $incluir == "Alterar"){
	db_inicio_transacao();
	$cltiafdoc->y99_coddoc  = $y99_coddoc;
	$cltiafdoc->y99_codtiaf = $y90_codtiaf;
	$cltiafdoc->y99_tiafdoc = $y98_tiafdoc;
	$cltiafdoc->y99_dtini   = $y99_dataini_ano."-".$y99_dataini_mes."-".$y99_dataini_dia;
	$cltiafdoc->y99_dtfim   = $y99_datafim_ano."-".$y99_datafim_mes."-".$y99_datafim_dia;
	$cltiafdoc->y99_obs     = $y99_obs;
	$cltiafdoc->alterar($y99_coddoc); 
	if ($cltiafdoc->erro_status==0){
    	$erro_msg= $cltiafdoc->erro_msg;
    	$sqlerro=true;
    }else{
        
    }
	db_fim_transacao($sqlerro);
	
}

//////////////////////////////////////////////////////////////////
 
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">

</table >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	    <?
	    	include("forms/db_frmtiaf002.php");
	    ?> 
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
  if($cltiafdoc->erro_status=="0"){
    $cltiafdoc->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>";
    if($cltiafdoc->erro_campo!=""){
      echo "<script> document.form1.".$cltiafdoc->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cltiafdoc->erro_campo.".focus();</script>";
    }
  }else{
     /*echo "<script>
               parent.iframe_g2.location.href='fis1_tiafaba002.php?y90_codtiaf=".$cltiaf->y90_codtiaf."';\n
               parent.iframe_g1.location.href='fis1_tiafaba001.php?chavepesquisa=".@$codigo."';\n
               parent.mo_camada('g2');
               parent.document.formaba.matrequiitem.disabled = false;\n
	 </script>";*/
  }
}
?>