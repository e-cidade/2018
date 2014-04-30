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
include("classes/db_contcearquivoresp_classe.php");
include("classes/db_contcearquivo_classe.php");
include("dbforms/db_funcoes.php");
require_once('libs/db_utils.php');

db_postmemory($_POST);
db_postmemory($_GET);

$clcontcearquivoresp = new cl_contcearquivoresp;
$clcontcearquivo = new cl_contcearquivo;
$db_opcao = 22;
$db_botao = false;
$lLiberaAbaArquivos = false;
if(isset($alterar) || isset($excluir) || isset($incluir)){
  $sqlerro = false;
}
if(isset($incluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clcontcearquivoresp->incluir($c12_sequencial);
    $erro_msg = $clcontcearquivoresp->erro_msg;
    if($clcontcearquivoresp->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($alterar)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clcontcearquivoresp->alterar($c12_sequencial);
    $erro_msg = $clcontcearquivoresp->erro_msg;
    if($clcontcearquivoresp->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($excluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clcontcearquivoresp->excluir($c12_sequencial);
    $erro_msg = $clcontcearquivoresp->erro_msg;
    if($clcontcearquivoresp->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($opcao)){
   $result = $clcontcearquivoresp->sql_record($clcontcearquivoresp->sql_query($c12_sequencial));
   if($result!=false && $clcontcearquivoresp->numrows>0){
     db_fieldsmemory($result,0);
   }
}

/**
 *  Verificando se foram incluidos todos os responsaveis para liberar a aba da emissao dos arquivos
 */
$sSqlVerificaResponsaveis = $clcontcearquivoresp->sql_query_file(null,"count(*) as quantidade_responsaveis",null,"c12_contcearquivo = {$c12_contcearquivo}");
$rsVerificaResponsaveis   = $clcontcearquivoresp->sql_record($sSqlVerificaResponsaveis);
if ($clcontcearquivoresp->numrows > 0) {
	$oNumResponsaveis = db_utils::fieldsMemory($rsVerificaResponsaveis,0);
	/**
	 * Se o numero de responsaveis for igual a 5 quer dizer que ja foram incluidos todos, pois temos um indice unico
	 *  na tabela contcearquivo pelos campos c12_contcearquivo e c12_tipo
	 */
	if ($oNumResponsaveis->quantidade_responsaveis == 5 ) {
		$lLiberaAbaArquivos = true;
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmcontcearquivoresp.php");
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
    if($clcontcearquivoresp->erro_campo!=""){
        echo "<script> document.form1.".$clcontcearquivoresp->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clcontcearquivoresp->erro_campo.".focus();</script>";
    }
}
if ($lLiberaAbaArquivos) {
	$sDisable = 'false';
} else {
	$sDisable = 'true';
}
echo "<script> parent.document.formaba.contcearquivosgeracao.disabled={$sDisable}; </script>";
?>