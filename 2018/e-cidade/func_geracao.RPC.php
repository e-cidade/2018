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

require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("classes/db_recibounicageracao_classe.php"));

$oJson             = new services_json();
$oParam            = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["json"])));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = 1;
$lErro             = false;
$sMensagem         = "";
$oGeracao          = new cl_recibounicageracao;

switch($oParam->exec) {

  case 'geracao' :

  	$aDadosGeracao  = array();
  	$sWhereGeracao  = "ar40_ativo is true ";

  	$sInnerGeracao  = "inner join recibounica on ar40_sequencial        = k00_recibounicageracao \n";
  	$sInnerGeracao .= "inner join arrecad     on recibounica.k00_numpre = arrecad.k00_numpre     \n";
  	$sInnerGeracao .= "inner join cgm         on arrecad.k00_numcgm     = cgm.z01_numcgm         \n";
  	$sInnerGeracao .= "inner join arretipo    on arrecad.k00_tipo       = arretipo.k00_tipo      \n";
  	$sInnerGeracao .= "inner join cadtipo     on cadtipo.k03_tipo       = arretipo.k03_tipo      \n";


    $iCgm          = $oParam->iCgm;
    $iMatricula    = $oParam->iMatricula;
    $iInscricao    = $oParam->iInscricao;
    $iTipoGeracao  = $oParam->iTipoGeracao;
    $iTipoDebito   = $oParam->iTipoDebito;
    $iNumpre       = $oParam->iNumpre;
    $dtGeracao 	   = implode("-", array_reverse(explode("/",$oParam->dtGeracao)));
    $sTipoGeracao  = "";
    switch ($iTipoGeracao){
    	case 'I' :
    		$sTipoGeracao = "Individual";
    	break;

    	case 'G' :
    		$sTipoGeracao = "Geral";
    	break;

    }

    $sCamposGeracao  = "ar40_sequencial,           \n";
    $sCamposGeracao .= "ar40_dtoperacao,           \n";
    $sCamposGeracao .= "ar40_dtvencimento,        \n";
    $sCamposGeracao .= "k03_descr,                 \n";
    $sCamposGeracao .= "recibounica.k00_percdes,   \n";
    $sCamposGeracao .= "ar40_dtvencimento,         \n";
    $sCamposGeracao .= "ar40_percentualdesconto,   \n";
    $sCamposGeracao .= "ar40_ativo,                \n";
    $sCamposGeracao .= "ar40_observacao,           \n";
    $sCamposGeracao .= "case when ar40_tipogeracao = 'I' then 'Individual' else
                             'Geral'
                        end as ar40_tipogeracao,                \n";


    $sCamposGeracao .= "case  ar40_tipogeracao
                        when 'G'
                          then 'Varias'
                        else
                            case when
	                                 (select true from recibounica
	                                     inner join arreinscr on recibounica.k00_numpre =  arreinscr.k00_numpre
	                                     where k00_recibounicageracao = ar40_sequencial)
	                               then
                                   (select 'Inscrição '||array_to_string(array_accum(arreinscr.k00_inscr),',') from recibounica
                                     inner join arreinscr on recibounica.k00_numpre =  arreinscr.k00_numpre
                                     where k00_recibounicageracao = ar40_sequencial)
                                 else
                                     case when
                                          (select true from recibounica
		                                       inner join arrematric on recibounica.k00_numpre =  arrematric.k00_numpre
		                                       where k00_recibounicageracao = ar40_sequencial group by arrematric.k00_numpre)
		                                 then
		                                   (select 'Matricula '||array_to_string(array_accum(arrematric.k00_matric),',') from recibounica
		                                     inner join arrematric on recibounica.k00_numpre =  arrematric.k00_numpre
		                                     where k00_recibounicageracao = ar40_sequencial)
		                                 else
			                                  (select 'CGM '||array_to_string(array_accum(arrenumcgm.k00_numcgm),',') from recibounica
	                                         inner join arrenumcgm on recibounica.k00_numpre =  arrenumcgm.k00_numpre
	                                         where k00_recibounicageracao = ar40_sequencial)
		                                 end
		                        end
                        end      as sOrigem                                                                         \n";


    //array_to_string(array_acum(arreinscr.k00_inscr),',')
    if($iCgm != null){

    	$sWhereGeracao  .= " and arrecad.k00_numcgm = {$iCgm}                                    \n";
    	$sInnerGeracao  .= " inner join arrenumcgm on arrecad.k00_numpre = arrenumcgm.k00_numpre \n";
    	$sCamposGeracao .= ",z01_nome ,z01_numcgm,arrecad.k00_numcgm                             \n";
    }

    if($iMatricula != null){

    	$sWhereGeracao  .= " and arrematric.k00_matric = {$iMatricula}                           \n";
    	$sInnerGeracao  .= " inner join arrematric on arrematric.k00_numpre = arrecad.k00_numpre \n";
    	$sCamposGeracao .= ",z01_nome ,z01_numcgm,arrematric.k00_matric                          \n";
    }

    if($iInscricao != null){
      $sWhereGeracao  .= " and arreinscr.k00_inscr = {$iInscricao}                                       \n";
      $sInnerGeracao  .= " inner join arreinscr on arreinscr.k00_numpre = arrecad.k00_numpre   \n";
      $sCamposGeracao .= ",z01_nome ,z01_numcgm,arreinscr.k00_inscr                            \n";
    }

    if($iTipoGeracao != null){
      $sWhereGeracao .= " and ar40_tipogeracao = '{$iTipoGeracao}' ";
    }

    if($iTipoDebito != null){
      $sWhereGeracao .= " and cadtipo.k03_tipo = {$iTipoDebito} ";
    }

    if($iNumpre != null){

      $sWhereGeracao  .= " and arrecad.k00_numpre = {$iNumpre} ";
      $sCamposGeracao .= ",z01_nome ,z01_numcgm,arrecad.k00_numpre                           \n";
    }

    if($dtGeracao != null){
      $sWhereGeracao .= " and ar40_dtoperacao = '{$dtGeracao}' ";
    }


    /*
     * para saber tipo debito da geração
     * recibounicageracao inner join recibounica inner join arrecad
     * inner join arretipo
     * pegando o campo k03_tipo
     *
     */

    $sSqlGerados  = "select distinct {$sCamposGeracao} from recibounicageracao  \n";
    $sSqlGerados .= "   {$sInnerGeracao}                                          ";
    $sSqlGerados .= "  where {$sWhereGeracao}                                   \n";

    $rsGerados    = db_query($sSqlGerados);

    if(!$rsGerados){
      $oRetorno->status   = 0;
      $oRetorno->sMessage = "Erro ao consultar Cota Única";
    }

    $aGerados     = db_utils::getCollectionByRecord($rsGerados, false, false, false);

    //echo $sSqlGerados; die();

    foreach ($aGerados as $iIndiceGerado => $oValorGerado){

    	$oDadosGerado = new stdClass();
    	$oDadosGerado->ar40_sequencial         = $oValorGerado->ar40_sequencial;
    	$oDadosGerado->ar40_dtoperacao         = db_formatar($oValorGerado->ar40_dtoperacao, "d");
    	$oDadosGerado->ar40_dtvencimento       = $oValorGerado->ar40_dtvencimento;
    	$oDadosGerado->ar40_percentualdesconto = $oValorGerado->ar40_percentualdesconto;
    	$oDadosGerado->ar40_tipogeracao        = $oValorGerado->ar40_tipogeracao;
    	$oDadosGerado->ar40_observacao         = $oValorGerado->ar40_observacao;
    	$oDadosGerado->sOrigem                 = $oValorGerado->sorigem;
    	$oDadosGerado->sTipoDebito             = $oValorGerado->k03_descr;
    	$oDadosGerado->k00_dtvenc              = db_formatar($oValorGerado->ar40_dtvencimento, 'd');
    	$oDadosGerado->k00_percdes             = $oValorGerado->k00_percdes;
    	// se vier na consulta definimos o nome
    	if(isset($oValorGerado->z01_nome)){
    	  $oDadosGerado->z01_nome              = $oValorGerado->z01_nome;
    	}else{
    		$oDadosGerado->z01_nome              = "";
    	}
    	// se vier na consulta definimos o numpre
      if(isset($oValorGerado->k00_numpre)){
        $oDadosGerado->k00_numpre              = $oValorGerado->k00_numpre;
      }else{
        $oDadosGerado->k00_numpre              = "";
      }

      $aDadosGeracao[] = $oDadosGerado;

    }


    $oRetorno->dados      = $aDadosGeracao;
   /*
    echo "<pre>";
    print_r($oRetorno);
    echo "</pre>";
    die();
   */
  break;

}

echo $oJson->encode($oRetorno);
