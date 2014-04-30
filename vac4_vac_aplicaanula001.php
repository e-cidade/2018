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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_vac_aplica_classe.php");
require_once("classes/db_vac_sala_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");

$iDepartamento    = db_getsession("DB_coddepto");
$oDaoVacSala      = new cl_vac_sala;
$sSql             = $oDaoVacSala->sql_query_file("","*",""," vc01_i_unidade=$iDepartamento ");
$rsResult         = $oDaoVacSala->sql_record($sSql);
db_postmemory($HTTP_POST_VARS);
$oDaoVacAplicaanula = db_utils::getdao('vac_aplicaanula');

$db_opcao = 1;
$db_botao = true;

if (isset($anular)) {

  db_inicio_transacao();
  $oDaoVacAplicaanula->vc18_i_aplica  = $vc18_i_aplica;
  $oDaoVacAplicaanula->vc18_i_usuario = db_getsession('DB_id_usuario');
  $oDaoVacAplicaanula->vc18_d_data    = date('Y-m-d', db_getsession('DB_datausu'));
  $oDaoVacAplicaanula->vc18_c_hora    = date('H:i');
  $oDaoVacAplicaanula->incluir(null);
  db_fim_transacao($oDaoVacAplicaanula->erro_status == '0' ? true : false);

}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<br><br><br>
<?if ($oDaoVacSala->numrows == 0) {

    echo"<br><br><center><strong><b> Departamento não é um sala de vacinação! </b></strong></center></center></center>";
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    exit;

  }?>
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
        <fieldset style='width: 60%;'> <legend><b>Anular Aplicação de Vacina</b></legend>
	        <?
	        require_once("forms/db_frmvac_aplicaanula.php");
	        ?>
        </fieldset>
      </center>
	</td>
  </tr>
</table>
<script>
  js_tabulacaoforms("form1", "vc16_i_cgs", true, 1, "vc16_i_cgs", true);
</script>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if (isset($anular)) {

  if ($oDaoVacAplicaanula->erro_status == '0') {
    $oDaoVacAplicaanula->erro(true,false);
  } else {
    $oDaoVacAplicaanula->erro(true,true);
  }

}
?>