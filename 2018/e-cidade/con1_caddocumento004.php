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
require_once("classes/db_caddocumento_classe.php");
require_once("classes/db_caddocumentoatributo_classe.php");
require_once("classes/db_cadtipodocumento_classe.php");

$cldocumento     = new cl_caddocumento;
$cltipodocumento = new cl_cadtipodocumento;

  /*
$cldocumentoatributo = new cl_documentoatributo;
  */
db_postmemory($HTTP_POST_VARS);

$db_opcao = 1;
$db_botao = true;
$sqlerro  = false;

if (isset($incluir)) {
  
  db_inicio_transacao();
  
  $cldocumento->db44_descricao        = $db44_descricao;
  $cldocumento->db44_cadtipodocumento = $db123_sequencial; 
  $cldocumento->incluir($db44_sequencial);
  $erro_msg = $cldocumento->erro_msg; 
  if($cldocumento->erro_status == 0) {
    $sqlerro = true;
  } 
  
  db_fim_transacao($sqlerro);
  
  $db44_sequencial = $cldocumento->db44_sequencial;
  $db_opcao        = 1;
  $db_botao        = true;
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
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmcaddocumento.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if (isset($incluir)) {
  
  if ($sqlerro == true) {
    
    db_msgbox($erro_msg);
    if ($cldocumento->erro_campo!="") {
      
      echo "<script> document.form1.".$cldocumento->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldocumento->erro_campo.".focus();</script>";
    };
  } else {
     
   db_msgbox($erro_msg);
   db_redireciona("con1_caddocumento005.php?liberaaba=true&chavepesquisa=$db44_sequencial");
  }
}
?>