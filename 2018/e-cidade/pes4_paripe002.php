<?php
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

require_once 'libs/db_stdlib.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';
require_once 'libs/db_usuariosonline.php';
require_once 'libs/db_utils.php';

require_once 'classes/db_ipe_classe.php';
require_once 'classes/db_cfpess_classe.php';

require_once 'dbforms/db_funcoes.php';

$oPost = db_utils::postMemory($_POST); 

db_postmemory($HTTP_POST_VARS);

$oIpe       = new cl_ipe;
$oDaoCfpess = new cl_cfpess;

$clipe      = new cl_ipe;
$oDaocfpess   = new cl_cfpess;

$db_opcao = 2;
$db_botao = true;

if ( isset($alterar) ) {

  db_inicio_transacao();
  
  if ( trim($r11_recpatrafasta) == 't' ) {
    $lRecPatraFasta = "true";
  } else {
    $lRecPatraFasta = "false";
  }
  
	$sSqlCfpess = $oDaocfpess->sql_query_file(db_anofolha(), db_mesfolha(),db_getsession("DB_instit"), " * ");
  $rsCfpess   = $oDaocfpess->sql_record($sSqlCfpess);

  if ($oDaocfpess->numrows > 0) {

    db_fieldsmemory($rsCfpess, 0);

    $iColunas = pg_num_fields($rsCfpess);

    for( $iIndice = 0; $iIndice < $iColunas; $iIndice++) {

      $dcoluna = pg_fieldname($rsCfpess, $iIndice);
      $dtipoco = pg_field_type($rsCfpess, $iIndice);

      if ( strpos( trim($dtipoco), "bool" ) === false ) {

        if (trim($$dcoluna) == "") {

          if (trim($dtipoco) == "date") {
            $$dcoluna = null;
          } else if (strpos(trim($dtipoco),"float") == true || strpos(trim($dtipoco),"int") == true) {
            $$dcoluna = "0";
          } else{
            $$dcoluna = "";
          }
        }

      } else {

        if (trim($$dcoluna) == 't') {
          $$dcoluna = "true";
        } else {
          $$dcoluna = "false";
        }
      }

      $oDaocfpess->$dcoluna = $$dcoluna;
    }
  }

  $oDaocfpess->r11_recpatrafasta = $lRecPatraFasta;
	$oDaocfpess->r11_codipe        = $oPost->r11_codipealt;
  $oDaocfpess->r11_valor         = $oPost->r11_valoralt;
  $oDaocfpess->r11_dtipe         = $oPost->r11_dtipealt;
	$oDaocfpess->r11_percentualipe = $oPost->r11_percentualipe;
  $oDaocfpess->r11_instit        = db_getsession("DB_instit");  

	$oDaocfpess->alterar(db_anofolha(), db_mesfolha(), db_getsession("DB_instit"));
	
  db_fim_transacao();
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
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:25px;">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
        <?php include("forms/db_frmparipe.php"); ?>
      </center>
    </td>
  </tr>
</table>
<?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>
</body>
</html>
<?php
if ( isset($alterar) ) {

  if ( $oDaocfpess->erro_status == "0" ) {

    $oDaocfpess->erro(true, false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ( $oDaocfpess->erro_campo != "" ) {

      echo "<script> document.form1.".$oDaocfpess->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaocfpess->erro_campo.".focus();</script>";
    }
  }else{
    $oDaocfpess->erro(true,true);
  }
}
?>