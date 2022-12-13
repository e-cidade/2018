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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("libs/db_liborcamento.php");
include ("dbforms/db_classesgenericas.php");
include ("dbforms/db_funcoes.php");
include ("classes/db_pcproc_classe.php");
include ("classes/db_pcprocitem_classe.php");
include ("classes/db_pcdotac_classe.php");
include ("classes/db_orcdotacao_classe.php");
include ("classes/db_orcelemento_classe.php");
include ("classes/db_solicitempcmater_classe.php");
include ("classes/db_solicitemele_classe.php");
include ("classes/db_solicitem_classe.php");
include ("classes/db_pcmater_classe.php");
$clpcproc = new cl_pcproc;
$clpcprocitem = new cl_pcprocitem;
$clpcdotac = new cl_pcdotac;
$clorcdotacao = new cl_orcdotacao;
$clorcelemento = new cl_orcelemento;
$clsolicitempcmater = new cl_solicitempcmater;
$clsolicitemele = new cl_solicitemele;
$clsolicitem = new cl_solicitem;
$clpcmater = new cl_pcmater;
$clrotulo = new rotulocampo;
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);
$db_opcao = 22;
if(isset($chavepesquisa)){
  $db_opcao = 2;
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
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
      include("forms/db_frmsolicitemele.php");
      ?>
      </center>
    </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<script>
<?
if($db_opcao==22){
  echo "
    document.form1.pesquisar.click();
  ";
}else if($db_opcao==2){
  echo "
    iframe_solicitemele.location.href = 'com1_solicitemeleiframe001.php?pc80_codproc=$chavepesquisa';
  ";
}
?>
</script>