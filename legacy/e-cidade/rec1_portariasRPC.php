<?php
/**
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
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_utils.php"));

$oPost  = db_utils::postMemory($_POST);
$oJson  = new services_json();

$clportaria					         = new cl_portaria();
$clportariaassenta			     = new cl_portariaassenta();
$clportariatipodoccoletiva   = new cl_portariatipodoccoletiva();
$clportariatipodocindividual = new cl_portariatipodocindividual();
$clrhparam					         = new cl_rhparam();


if ($oPost->sAcao == "consultaPortarias") {

  if (isset($oPost->iAnoUsu) && trim($oPost->iAnoUsu) != "" ) {
  	$iAnoUsu = $oPost->iAnoUsu;
  } else {
  	$iAnoUsu = db_getsession("DB_anousu");
  }

  $sWhere  = " cast( h31_numero as integer ) between {$oPost->iPortariaInicial} and {$oPost->iPortariaFinal} ";
  if( $oPost->iPortariaInicial == $oPost->iPortariaFinal ){

    $sWhere  = " h31_numero = '$oPost->iPortariaInicial' ";
  }

  $sWhere .= " and h31_anousu = {$iAnoUsu} ";
  $sWhereVerificaLotacao = " and h16_regist in (select distinct rh02_regist from rhpessoalmov
                                                 where rh02_anousu = ".DBPessoal::getAnoFolha()."
                                                   and rh02_mesusu = ".DBPessoal::getMesFolha()."
                                                   and rh02_lota in (select rh157_lotacao
                                                                       from db_usuariosrhlota
                                                                      where rh157_usuario = ".db_getsession("DB_id_usuario")."))";

  $rsConsultaPortarias = $clportaria->sql_record($clportaria->sql_query_assentamento_funcional(null,"*",null,$sWhere, $sWhereVerificaLotacao, 2));
  $iNroLinhasPort 	   = $clportaria->numrows;

  if ( $iNroLinhasPort > 0 ) {

  	for ( $i=0; $i < $iNroLinhasPort; $i++ ) {

  	  $oPortaria = db_utils::fieldsMemory($rsConsultaPortarias,$i);
  	  $aPortarias[] = $oPortaria->h31_numero;
  	}

  	$oVariavelPortaria = new stdClass();
  	$oVariavelPortaria->sNome  = '$portaria';
  	$sValor	 		   = implode("','",$aPortarias);
  	$oVariavelPortaria->sValor = "'{$sValor}'";
  	$aRetornoParametros[] = $oVariavelPortaria;


  	$oVariavelAno = new stdClass();
  	$oVariavelAno->sNome  = '$ano';
  	$oVariavelAno->sValor = $iAnoUsu;
  	$aRetornoParametros[] = $oVariavelAno;


    $rsConsultaModParam = $clrhparam->sql_record($clrhparam->sql_query_file(null,"*",null," h36_instit = ".db_getsession("DB_instit")));
  	$iNroLinhasParam    = $clrhparam->numrows;

  	if( $iNroLinhasParam > 0 ) {
  	  $oModParam = db_utils::fieldsMemory($rsConsultaModParam,0);
  	  $sRetornoTipoIndividual = $oModParam->h36_modportariaindividual;
  	  $sRetornoTipoColetiva   = $oModParam->h36_modportariacoletiva;
  	} else {
  	  $sRetornoTipoIndividual = "";
  	  $sRetornoTipoColetiva   = "";
  	}


	$rsConsultaTipo = $clportaria->sql_record($clportaria->sql_query_assentamento_funcional(null,"h31_portariatipo",null,$sWhere, $sWhereVerificaLotacao, 2));
	$iNroLinhasTipo = $clportaria->numrows;


		$oTipo = db_utils::fieldsMemory($rsConsultaTipo,0);


	  	$rsConsultaTipoIndividual = $clportariatipodocindividual->sql_record($clportariatipodocindividual->sql_query_file(null,"h37_modportariaindividual",null,"h37_portariatipo = {$oTipo->h31_portariatipo}"));
	  	$iNroLinhasTipoIndividual = $clportariatipodocindividual->numrows;


	    if ( $iNroLinhasTipoIndividual > 0 ) {
	      $oTipoIndividual 		  = db_utils::fieldsMemory($rsConsultaTipoIndividual,0);
	      $sRetornoTipoIndividual = $oTipoIndividual->h37_modportariaindividual;
	    }

	    $rsConsultaTipoColetiva = $clportariatipodoccoletiva->sql_record($clportariatipodoccoletiva->sql_query_file(null,"h38_modportariacoletiva",null," h38_portariatipo = {$oTipo->h31_portariatipo}"));
	    $iNroLinhasTipoColetiva = $clportariatipodoccoletiva->numrows;

	  	if ( $iNroLinhasTipoColetiva > 0 ) {
	      $oTipoColetiva 	    = db_utils::fieldsMemory($rsConsultaTipoColetiva,0);
	      $sRetornoTipoColetiva = $oTipoColetiva->h38_modportariacoletiva;
	  	}


	    $aRetorno = array(
	    				  "aParametros"   =>$aRetornoParametros,
	    				  "iModIndividual"=>$sRetornoTipoIndividual,
	    				  "iModColetiva"  =>$sRetornoTipoColetiva,
	    				  "erro"		  =>false
	    				 );

  } else {

	$aRetorno = array("msg"=>urlencode("Nenhuma portaria cadastrada!"),"erro"=>true);

  }

  echo $oJson->encode($aRetorno);

}


?>