<?php
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
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_recibo_classe.php");

$clrotulo = new rotulocampo;
$clrotulo->label("k00_numpre");

$clRecibo = new cl_recibo();

$oPost = db_utils::postMemory($_POST);

if (isset($oPost->k00_numpre)) {
	$sSqlDadosRecibo = $clRecibo->sql_query_file(null,"distinct k00_numpre, k00_numcgm, k00_tipo",null,"k00_numpre = {$oPost->k00_numpre}");
	$rsDadosRecibo   = $clRecibo->sql_record($sSqlDadosRecibo);
	if ($clRecibo->numrows == 0) {
		db_msgbox("Nenhum recibo avulso foi encontrado com o numpre {$oPost->k00_numpre}");
		db_redireciona();
	}

	$oDadosRecibo = db_utils::fieldsMemory($rsDadosRecibo, 0);

	$sUrl  = "cai4_recibo003.php?iNumpre={$oDadosRecibo->k00_numpre}&tipo={$oDadosRecibo->k00_tipo}&ver_inscr=";
	$sUrl .= "&numcgm={$oDadosRecibo->k00_numcgm}&emrec=t&CHECK10=&tipo_debito={$oDadosRecibo->k00_tipo}&lReemissao=true";
	$sUrl .= "&k03_tipo={$oDadosRecibo->k00_tipo}&k03_parcelamento=f&k03_perparc=f&ver_numcgm={$oDadosRecibo->k00_numcgm}";

	echo "<script> ";
	echo "   window.open('{$sUrl}','','location=0'); ";
	echo "</script>";

}

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <?
   if (db_getsession("DB_id_usuario") != 1) {
   	
   	$sString  = "<center>";
   	$sString .= "<fieldset style=\"width: 500px; margin-top: 25px;\">";
   	$sString .= "<p>";
   	$sString .= "<b>Rotina bloqueada</b>";
   	$sString .= "</p>";
   	$sString .= "</fieldset>";
   	$sString .= "</center>";
   	
   	db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
   	die($sString);
   }
  ?>
    <table  border="0" cellpadding="0" cellspacing="0">
      <tr> 
        <td width="360" height="18">&nbsp;</td>
        <td width="263">&nbsp;</td>
        <td width="25">&nbsp;</td>
        <td width="140">&nbsp;</td>
     </tr>
  </table>
  <center>
    <form name='form1' method="POST">
    <table width="250">
      <tr>
        <td align="center">
          <fieldset>
            <legend><b>Reemisao de recibo</legend>
            <table>
              <tr>
                <td nowrap title="<?=@$k00_numpre?>">
                 <?=$Lk00_numpre?>
                </td>
                <td nowrap> 
                 <? db_input('k00_numpre',10,$Ik00_numpre,true,'text',1)  ?>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td align="center">
          <input type='button' value='Reemitir Recibo' id='reemitir' name='reemitir' onclick='js_Reemitir()'>
        </td>
      </tr>
    </table>
    </form>
  </center>
  </body>
</html>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>

function js_Reemitir() {
	if ($F(k00_numpre) == "") {
		alert("Informe o Numpre do Recibo Avulso que deseja reemitir");
		return false;
	}	

	if (confirm('Reemitir Recibo?')) {
    document.form1.submit();
		return true;
	}
}
</script>