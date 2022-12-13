<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));

db_app::import("contabilidade.*");
$oParam     = db_utils::postMemory($_GET);
$iAnoSessao = db_getsession("DB_anousu");

/**
 * Verifico que método utilizar para buscar as contas na conplano
 */
$oDaoConPlano = db_utils::getDao("conplano");
$sCamposPlano = " c61_reduz as reduzido, c60_descr  as descricao";
$sWherePlano  = "";

$sMetodoConta = 'getContaDebito';
if (isset($oParam->lContaCredito) && $oParam->lContaCredito == "true") {
  $sMetodoConta = "getContaCredito";
}

try {
  $oEventoContabil = new EventoContabil(getDocumentoPorTipoInclusao($oParam->iTipoTransferencia), $iAnoSessao);
  $aLancamentos    = $oEventoContabil->getEventoContabilLancamento();
  $aInLancamentos  = array();

  foreach ($aLancamentos as $oLancamento) {

    if ($oLancamento->getOrdem() == 1) {

      $aRegrasLancamento = $oLancamento->getRegrasLancamento();

      foreach ($aRegrasLancamento as $oContaRegraLancamento) {

        $aInLancamentos[] = $oContaRegraLancamento->$sMetodoConta();
      }
      break;
    }
    break;
  }
} catch(Exception $eErro) {
  die($eErro->getMessage());
}

$sInLancamentos = implode(",", $aInLancamentos);
$sWherePlano    = " conplanoreduz.c61_reduz in( {$sInLancamentos} )";

if (isset($oParam->pesquisa_chave) && !empty($oParam->pesquisa_chave)) {
  $sWherePlano    .= " and conplanoreduz.c61_reduz = {$oParam->pesquisa_chave} ";
}

$oRotulo        = new rotulocampo;

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oRotulo->label("c61_reduz");
$oRotulo->label("c60_descr");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload='document.form2.c61_reduz.focus();'>
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr>
            <td width="4%" align="right" nowrap >
              <?php echo $Lc61_reduz?>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
	              db_input("c61_reduz",5,$Ic61_reduz,true,"text",4,"","c61_reduz");
	            ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap>
              <?php echo $Lc60_descr?>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
	              db_input("c60_descr",40,$Ic60_descr,true,"text",4,"","c60_descr");
	            ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_saltes.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?php
      $dbwhere="";

      if (!isset($pesquisa_chave)) {

        if(isset($c61_reduz) && (trim($c61_reduz)!="") ) {
          $sWherePlano .= " and c61_reduz::text like '$c61_reduz%' ";
        } else if(isset($c60_descr) && (trim($c60_descr) != "") ) {
          $sWherePlano .= " and c60_descr ilike '%$c60_descr%' ";
        }

        $sSqlDadosConta = $oDaoConPlano->sql_query(null, null, $sCamposPlano, null, $sWherePlano);
        db_lovrot($sSqlDadosConta, 15, "()", "", $funcao_js, "", "NoMe", array(), false);
      }else{

        if ($pesquisa_chave != null && $pesquisa_chave != "") {

          $sSqlDadosConta = $oDaoConPlano->sql_query(null, null, $sCamposPlano, null, $sWherePlano);
          $rsContas       = $oDaoConPlano->sql_record($sSqlDadosConta);
          if ($oDaoConPlano->numrows != 0) {

            db_fieldsmemory($rsContas,0);
            echo "<script>".$funcao_js."('$descricao',false);</script>";
          } else {
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        } else {
	       echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<?php
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?php
}

function getDocumentoPorTipoInclusao($iTipoOperacao) {

  $iCodigoDocumento = 0;
  switch ($iTipoOperacao) {

    /**
     * Transferencia Financeira
     */
    case 1:
  	case 2:
  	  $iCodigoDocumento = 120;
	    break;
  	case 3:
  	case 4:
  	  $iCodigoDocumento = 130;
	  break;

		/**
		 * Transferencia Bancaria
		 */
  	case 5:
  	case 6:
  	  $iCodigoDocumento = 140;
		break;

    /**
     * Caução
     */
  	case 7:
  	case 8:
  	  $iCodigoDocumento = 150;
	  break;
  	case 9:
  	case 10:
  	  $iCodigoDocumento = 151;
	  break;

	  /**
	   * Depósito de Diversas Origens
	   */
  	case 11:
  	case 12:
  	  $iCodigoDocumento = 160;
  	break;

  	case 13:
  	case 14:
  	  $iCodigoDocumento = 161;
	  break;
  }

  return $iCodigoDocumento;
}
?>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
