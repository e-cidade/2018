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
include("classes/db_pcorcam_classe.php");
include("classes/db_pcorcamitemproc_classe.php");

$clpcorcam 				 = new cl_pcorcam;
$clpcorcamitemproc = new cl_pcorcamitemproc;

$clpcorcam->rotulo->label();

db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);

$rsVerificaAut = $clpcorcamitemproc->sql_record($clpcorcamitemproc->sql_query_solicitem(null,null," distinct pc81_codproc ","","pc22_codorc=".$pc20_codorc."and e54_autori is not null and e54_anulad is null"));

if($clpcorcamitemproc->numrows > 0 ){
  db_msgbox("Não é possível julgar orçamento, existe uma autorização para esse orçamento de processo de compra!");
  db_redireciona("com1_selorc001.php?julg=true&sol=false");
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
    <td height="450" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
        <?
        include("forms/db_frmpcorcamtroca.php");
        ?>
    </center>
    </td>
  </tr>
</table>
</body>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</html>
<?
if(isset($pc20_codorc) && trim($pc20_codorc)){
  echo "<script>iframe_solicitem.location.href = 'com1_trocpcorcamtroca.php?orcamento=$pc20_codorc&sol=$sol';</script>";
}
?>