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
include("classes/db_vac_aplica_classe.php");
include("classes/db_vac_aplicaanula_classe.php");
include("dbforms/db_funcoes.php");
require("libs/db_utils.php");
include("classes/db_vac_calendario_classe.php");
include("classes/db_vac_vacinadoserestricao_classe.php");
include("classes/db_vac_vacinadose_classe.php");
include("classes/db_vac_vacina_classe.php");
include("classes/db_vac_dose_classe.php");
include("classes/db_cgs_und_classe.php");
require_once("libs/db_stdlibwebseller.php");
require_once("ext/php/adodb-time.inc.php");
$clvac_aplicaanula         = new cl_vac_aplicaanula;
$clvac_vacinadoserestricao = new cl_vac_vacinadoserestricao;
$clvac_calendario          = new cl_vac_calendario;
$clvac_vacinadose          = new cl_vac_vacinadose;
$clvac_vacina              = new cl_vac_vacina;
$clvac_dose                = new cl_vac_dose;
$clcgs_und                 = new cl_cgs_und;

db_postmemory($HTTP_POST_VARS);
$clvac_aplica = new cl_vac_aplica;
$db_opcao = 1;
$db_botao = true;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.cabec {
  text-align: center;
  font-size: 12;
  color: #DEB887;
  background-color:#444444;
  border:1px solid $FFFFFF;
}
.corpo {
  font-size: 16;
  color: #444444;
  background-color:#eaeaea;
}

</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<br><br>
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmvac_aplicavacina.php");
	?>
    </center>
	</td>
  </tr>
</table>
</center>
</body>
</html>
<script>
js_tabulacaoforms("form1", "vc16_i_cgs", true ,1 ,"vc16_i_cgs" ,true);
</script>