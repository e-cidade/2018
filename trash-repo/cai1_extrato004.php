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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once('std/db_stdClass.php');
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_extrato_classe.php");
require_once("classes/db_extratolinha_classe.php");
$clextrato = new cl_extrato;
  /*
$clextratolinha = new cl_extratolinha;
  */
db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = true;

if(isset($incluir)){
	
	$oDadosPrefeitura = db_stdClass::getDadosInstit(db_getsession('DB_instit'));
	
  $sqlerro=false;
  db_inicio_transacao();
  $clextrato->k85_codbco       = $k85_codbco;
  $clextrato->k85_dtproc       = date('Y-m-d',db_getsession('DB_datausu'));
  $clextrato->k85_dtarq        = date('Y-m-d',db_getsession('DB_datausu'));
  $clextrato->k85_convenio     = $k85_convenio;
  $clextrato->k85_seqarq       = 1;
  $clextrato->k85_nomearq      = "INCLUSAO MANUAL";
  $clextrato->k85_tipoinclusao = 2;
  $clextrato->k85_conteudo     = "INCLUSAO MANUAL";
  $clextrato->k85_cnpj         = $oDadosPrefeitura->cgc;
  $clextrato->incluir($k85_sequencial);
  $erro_msg = $clextrato->erro_msg; 
  if($clextrato->erro_status==0){
    $sqlerro=true;
  } 

  db_fim_transacao($sqlerro);

  $k85_sequencial= $clextrato->k85_sequencial;
  $db_opcao = 1;
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmextratomanual.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clextrato->erro_campo!=""){
      echo "<script> document.form1.".$clextrato->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clextrato->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
   db_redireciona("cai1_extrato005.php?liberaaba=true&chavepesquisa=$k85_sequencial");
  }
}
?>