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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("libs/db_liborcamento.php");
include ("dbforms/db_funcoes.php");
include ("dbforms/db_classesgenericas.php");
include ("classes/db_solicitempcmater_classe.php");
include ("classes/db_solicitemele_classe.php");
include ("classes/db_pcdotac_classe.php");
include ("classes/db_orcelemento_classe.php");
include ("classes/db_orcparametro_classe.php");
$clsolicitempcmater = new cl_solicitempcmater;
$clsolicitemele = new cl_solicitemele;
$clpcdotac = new cl_pcdotac;
$clorcelemento = new cl_orcelemento;
$clorcparametro = new cl_orcparametro;
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_POST_VARS,2);db_postmemory($HTTP_GET_VARS,2);

$db_opcao = 1;
$db_botao = false;

$sqlerro = false;
if(isset($incluir)){
  db_inicio_transacao();
  $clsolicitempcmater->incluir($pc16_codmater, $pc16_solicitem);
  $erro_msg = $clsolicitempcmater->erro_msg;
  if($clsolicitempcmater->erro_status == 0) {
 	$sqlerro = true;
  }
  if($sqlerro==false){
    $clsolicitemele->incluir($pc16_solicitem,$o56_codele);
    $erro_msg = $clsolicitemele->erro_msg;
    if($clsolicitemele->erro_status == 0){
      $sqlerro = true;
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
      <?
      include ("forms/db_frmselmater.php");
      ?>
    </center>
    </td>
  </tr>
</table>
<?
if(!isset($libera)){
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
}
?>
</body>
</html>
<?
if(isset ($incluir)) {
  if($sqlerro == true){
		$erro_msg = str_replace("\n", "\\n", $erro_msg);
		db_msgbox($erro_msg);		
		if($clsolicitempcmater->erro_campo != "") {
	      echo "<script> document.form1.".$clsolicitempcmater->erro_campo.".style.backgroundColor='#99A9AE';</script>";
		  echo "<script> document.form1.".$clsolicitempcmater->erro_campo.".focus();</script>";
	 	}
  }else{
    echo "
          <script>
            top.corpo.location.href = 'com1_liberasol001.php?solicita=".$pc10_numero."';
          </script>
         ";
  }
}
if(isset($arr_pcdotac) && sizeof($arr_pcdotac)>1){
  db_msgbox($msg_alert);
}