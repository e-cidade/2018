<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
require_once("dbforms/db_funcoes.php");
require_once("classes/db_taxaserv_classe.php");
require_once("classes/db_taxaservval_classe.php");
require_once("libs/JSON.php");

$cltaxaserv         = new cl_taxaserv;
$cltaxaservval      = new cl_taxaservval;
$oJson              = new services_json;
$oRetorno           = new stdClass;
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno->numrows  = 0;

switch ($oParam->exec) {

  case "listarTaxaServicos":

  	$dtLancamento       = implode("-",array_reverse(explode("/",$oParam->dtlancamento)));

    $sSqlTaxaServVal    = "  select cm35_taxaserv,                                                                    ";
    $sSqlTaxaServVal   .= "         cm35_valor as valortaxa                                                           ";
    $sSqlTaxaServVal   .= "    from taxaservval                                                                       ";
    $sSqlTaxaServVal   .= "   where cm35_taxaserv = {$oParam->codtaxaserv}                                            ";
    $sSqlTaxaServVal   .= "     and '{$dtLancamento}' >= cm35_dataini                                                 ";
    $sSqlTaxaServVal   .= "     and '{$dtLancamento}' <= cm35_datafin  ;                                              ";

    $rsTaxaServVal      = $cltaxaservval->sql_record($sSqlTaxaServVal);

    if ( $cltaxaservval->numrows > 0 ) {

    	$oTaxaServVal = db_utils::fieldsMemory($rsTaxaServVal,0);

    	$oRetorno->numrows    = $cltaxaservval->numrows;
    	$oRetorno->oValorTaxa = $oTaxaServVal->valortaxa;
    }

    break;

  case "calcularValores":

		$dtLancamento        = implode("-",array_reverse(explode("/",$oParam->dtlancamento)));
		$dtVencimento        = implode("-",array_reverse(explode("/",$oParam->dtvencimento)));
    $oParam->vlcorrigido = str_replace(".","",$oParam->vlcorrigido);
		$iVlCorrigido        = str_replace(",",".",$oParam->vlcorrigido);
		$iAnoUsu             = db_getsession('DB_anousu');

  	$sSqlTaxaServ  = $cltaxaserv->sql_query($oParam->codtaxaserv,"taxaserv.*",null,"");
  	$rsSqlTaxaServ = $cltaxaserv->sql_record($sSqlTaxaServ);
    if ( $cltaxaserv->numrows > 0 ) {
      $oTaxaServ = db_utils::fieldsMemory($rsSqlTaxaServ,0);
    }

    $sSqlTaxaServVal    = "  select cm35_taxaserv,                                                                    ";
    $sSqlTaxaServVal   .= "         cm35_dataini,                                                                     ";
    $sSqlTaxaServVal   .= "         cm35_datafin                                                                      ";
    $sSqlTaxaServVal   .= "    from taxaservval                                                                       ";
    $sSqlTaxaServVal   .= "   where cm35_taxaserv = {$oParam->codtaxaserv}                                            ";
    $sSqlTaxaServVal   .= "     and cast('{$dtLancamento}' as date) between cm35_dataini and cm35_datafin             ";
    $rsTaxaServVal      = $cltaxaservval->sql_record($sSqlTaxaServVal);

    if ( $cltaxaservval->numrows > 0 ) {

      $oTaxaServVal = db_utils::fieldsMemory($rsTaxaServVal,0);

	    $sSqlFcCorre   = "  select fc_corre( {$oTaxaServ->cm11_i_receita},                                              ";
	    $sSqlFcCorre  .= "                   '{$dtLancamento}',                                                         ";
	    $sSqlFcCorre  .= "                    {$iVlCorrigido},                                                          ";
	    $sSqlFcCorre  .= "                   '{$oTaxaServVal->cm35_dataini}',                                           ";
	    $sSqlFcCorre  .= "                    {$iAnoUsu},                                                               ";
	    $sSqlFcCorre  .= "                   '{$dtVencimento}'                                                          ";
	    $sSqlFcCorre  .= "                  ) as valorcorrigido                                                         ";

	    $rsSqlFcCorre  = db_query($sSqlFcCorre);
	    $iNumRows      = pg_num_rows($rsSqlFcCorre);
	    if ( $iNumRows > 0 ) {

	      $oFcCorre = db_utils::fieldsMemory($rsSqlFcCorre,0);
	      $oRetorno->numrows         = $iNumRows;
	      $oRetorno->oValorCorrigido = $oFcCorre->valorcorrigido;
	    }
    }

    break;
}

echo $oJson->encode($oRetorno);