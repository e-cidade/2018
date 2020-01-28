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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("model/dbGeradorRelatorio.model.php");
require_once("model/dbColunaRelatorio.php");
require_once("model/dbFiltroRelatorio.php");
require_once("model/dbOrdemRelatorio.model.php");
require_once("model/dbPropriedadeRelatorio.php");
require_once("model/dbVariaveisRelatorio.php");
require_once("classes/db_db_relatorio_classe.php");
require_once("classes/db_db_itensmenu_classe.php");
require_once("classes/db_db_permissao_classe.php");
require_once("classes/db_db_menu_classe.php");
require_once("classes/db_db_relatoriousuario_classe.php");
require_once("classes/db_db_relatoriodepart_classe.php");
require_once("classes/db_db_geradorrelatoriotemplate_classe.php");



$oPost  = db_utils::postMemory($_POST);

if (!isset($_SESSION['objetoXML'])) {
  $oXML = new dbGeradorRelatorio();
  $_SESSION['lAlteraRelatorio'] = false;
}else{
  $oXML = unserialize($_SESSION['objetoXML']);
}

$oJson    = new services_json();
$lErro    = false;
$sMsgErro = '';


if ( $oPost->tipo == "consultaCampos" ) {

	try {

    $aCampos = $oXML->getDadosCampos();

    foreach ($aCampos as $iInd => $oCampo) {

      $oRetornoColuna = new stdClass();
      $oRetornoColuna->iId              = $oCampo->getId();
      $oRetornoColuna->sNome            = urlencode($oCampo->getNome());
      $oRetornoColuna->sAlias           = urlencode($oCampo->getAlias());
      $oRetornoColuna->iLargura         = $oCampo->getLargura();
      $oRetornoColuna->sAlinhamento     = $oCampo->getAlinhamento();
      $oRetornoColuna->sAlinhamentoCab  = $oCampo->getAlinhamentoCab();
      $oRetornoColuna->sMascara         = $oCampo->getMascara();
      $oRetornoColuna->sTotalizar       = $oCampo->getTotalizar();
      $oRetornoColuna->lQuebra          = $oCampo->getQuebra();
      $aRetornaCampos[] = $oRetornoColuna;

    }

	} catch ( Exception $eException ) {
    $sMsgErro   	  = $eException->getMessage();
    $lErro          = true;
  }

  try {

    $aVariaveis        = $oXML->getVariaveis();
    $aRetornaVariaveis = array();

    foreach ($aVariaveis as $sNome => $oVariavel){
      $oRetornoVariavel = new stdClass();
      $oRetornoVariavel->sNome     = $oVariavel->getNome();
      $oRetornoVariavel->sLabel    = $oVariavel->getLabel();
      $oRetornoVariavel->sValor    = $oVariavel->getValor();
      $oRetornoVariavel->sTipoDado = $oVariavel->getTipoDado();

      $aRetornaVariaveis[] = $oRetornoVariavel;
    }

  } catch ( Exception $eException ) {
    $sMsgErro       = $eException->getMessage();
    $lErro          = true;
  }

  if ( $lErro ) {

  	unset($_SESSION['objetoXML']);

	  $aRetorno  = array( "msg" =>urlencode($sMsgErro),
                        "erro"=>true );
  } else {

    $aRetorno  = array( "aCampos"   =>$aRetornaCampos,
                        "aVariaveis"=>$aRetornaVariaveis,
                        "erro"      =>false );
  }


  echo $oJson->encode($aRetorno);


//************************************************************************************************************************//
// Inclui colunas e seus atributos
//************************************************************************************************************************//

} else if ($oPost->tipo == "incluirCampos") {

	$aObjCampos = $oJson->decode(str_replace("\\","",$oPost->aObjCampos));

	foreach ($aObjCampos as $oCampos){

		$oColunaRelatorio = new dbColunaRelatorio( $oCampos->iId,
	                                             utf8_decode($oCampos->sNome),
	                                             utf8_decode($oCampos->sAlias),
	                                             $oCampos->iLargura,
	                                             $oCampos->sAlinhamento,
	                                             $oCampos->sAlinhamentoCab,
	                                             $oCampos->sMascara,
	                                             $oCampos->sTotalizar,
	                                             $oCampos->lQuebra);

	  $oXML->addColuna($oColunaRelatorio);

	  $oRetornoColuna = new stdClass();

 	  $oRetornoColuna->iId       		    = $oColunaRelatorio->getId();
	  $oRetornoColuna->sNome    		    = urlencode($oColunaRelatorio->getNome());
    $oRetornoColuna->sAlias    		    = urlencode($oColunaRelatorio->getAlias());
    $oRetornoColuna->iLargura  	      = $oColunaRelatorio->getLargura();
	  $oRetornoColuna->sAlinhamento   	= $oColunaRelatorio->getAlinhamento();
	  $oRetornoColuna->sAlinhamentoCab  = $oColunaRelatorio->getAlinhamentoCab();
	  $oRetornoColuna->sMascara 	      = $oColunaRelatorio->getMascara();
	  $oRetornoColuna->sTotalizar 	    = $oColunaRelatorio->getTotalizar();
	  $oRetornoColuna->lQuebra          = $oColunaRelatorio->getQuebra();


	  $aRetornaCampos[] = $oRetornoColuna;

	}

	$_SESSION['objetoXML'] = serialize($oXML);


	echo $oJson->encode($aRetornaCampos);




  //************************************************************************************************************************//
  // Altera uma coluna do relatório
  //************************************************************************************************************************//



  } else if ($oPost->tipo == "alterarCampos") {

  	$aReplace = array("\\","(",")");

  	$objCampo = $oJson->decode(str_replace($aReplace,"",$oPost->objCampo));

  	$oColuna  = new dbColunaRelatorio( $objCampo->iId,
  	                                   utf8_decode($objCampo->sNome),
  	                                   utf8_decode($objCampo->sAlias),
  	                                   $objCampo->iLargura,
  	                                   $objCampo->sAlinhamento,
  	                                   $objCampo->sAlinhamentoCab,
  	                                   $objCampo->sMascara,
  	                                   $objCampo->sTotalizar,
  	                                   $objCampo->lQuebra);

    $oXML->addColuna($oColuna);

  	$_SESSION['objetoXML'] = serialize($oXML);



  //************************************************************************************************************************//
  // Exclui uma coluna do relatório
  //************************************************************************************************************************//


  } else if ($oPost->tipo == "excluirCampos") {


  	$aCampos = split(",",$oPost->aCampos);

  	foreach ( $aCampos as $sNomeCampo){
 	    unset($oXML->aColunas["Principal"][$sNomeCampo]);
  	}

  	$_SESSION['objetoXML'] = serialize($oXML);



  //************************************************************************************************************************//
  // Inclui um filtro no relatório
  //************************************************************************************************************************//

  } else if ($oPost->tipo == "incluirFiltro") {


   	if ($oPost->tipoCampo == "d"){
		  $aData  = explode("/",$oPost->sValor);
		  $sValor = implode("-",array_reverse($aData));
  	} else {
  	  $sValor = $oPost->sValor;
  	}

  	switch ($oPost->sCondicao){
  	  case "Igual":
  		$sCond = " = ";
  	  break;
  	  case "Diferente":
  	  	$sCond = " <> ";
  	  break;
  	  case "Maior":
  	  	$sCond = " > ";
  	  break;
  	  case "Menor":
  	  	$sCond = " < ";
  	  break;
  	  case "MaiorIgual":
  	  	$sCond = " >= ";
  	  break;
  	  case "MenorIgual":
  	  	$sCond = " <= ";
  	  break;
  	  case "Contendo":
  	  	$sCond  = " in ";
  	  break;
  	  case "Nulo":
  	  	$sCond = " is null ";
  	  break;
  	  case "Preenchido":
  	  	$sCond = " is not null ";
  	  break;
  	}

  	$oFiltroRelatorio = new dbFiltroRelatorio($oPost->sCampo,$sCond,$sValor,$oPost->sOperador);

  	$oXML->addFiltro($oFiltroRelatorio);

  	$oFiltros = new stdClass();

  	$oFiltros->sCampo 	  = urlencode($oFiltroRelatorio->getCampo());
  	$oFiltros->sCondicao  = $oFiltroRelatorio->getCondicao();
	  $oFiltros->sOperador  = $oFiltroRelatorio->getOperador();
  	$oFiltros->sValor     = urlencode($oPost->sValor);

  	$aFiltros[] = $oFiltros;

  	$_SESSION['objetoXML'] = serialize($oXML);

	  echo $oJson->encode($aFiltros);




  //************************************************************************************************************************//
  // Exclui um filtro do relatório
  //************************************************************************************************************************//

  } else if ($oPost->tipo == "excluirFiltro") {


	$aObjFiltros = $oJson->decode(str_replace("\\","",$oPost->aObjFiltros));


	foreach ($aObjFiltros as $sInd => $oFiltros){
	  unset($oXML->aFiltros["Principal"]["{$oFiltros->sCampo}{$oFiltros->sCondicao}{$oFiltros->sValor}"]);
	}


	$_SESSION['objetoXML'] = serialize($oXML);




  //************************************************************************************************************************//
  // Inclui variáveis
  //************************************************************************************************************************//

  } else if ($oPost->tipo == "incluirVariaveis") {


		$oPostVariavel = $oJson->decode(str_replace("\\","",$oPost->objVariavel));

		unset($oXML->aVariaveis[$oPostVariavel->sNome]);

		$oVariavel     = new dbVariaveisRelatorio( utf8_decode($oPostVariavel->sNome),
				  								                     utf8_decode($oPostVariavel->sLabel),
			                  										   utf8_decode($oPostVariavel->sValor),
			                                         utf8_decode($oPostVariavel->sTipoDado));

		$oXML->addVariavel($oPostVariavel->sNome,$oVariavel);

	  $oRetornoVariavel = new stdClass();
		$oRetornoVariavel->sNome     = urlencode($oVariavel->getNome());
		$oRetornoVariavel->sLabel    = urlencode($oVariavel->getLabel());
		$oRetornoVariavel->sValor    = urlencode($oVariavel->getValor());
	  $oRetornoVariavel->sTipoDado = urlencode($oVariavel->getTipoDado());

	  $aRetornaVariaveis[] = $oRetornoVariavel;

		$_SESSION['objetoXML'] = serialize($oXML);

		echo $oJson->encode($aRetornaVariaveis);


  //************************************************************************************************************************//
  // Exclui variáveis
  //************************************************************************************************************************//

  } else if ($oPost->tipo == "excluirVariaveis") {


  	$aReplace = array("\\","(",")");
  	$aPostVar = $oJson->decode(str_replace($aReplace	,"",$oPost->aObjVariavel));

		foreach ( $aPostVar as $sInd => $oPostVariavel ) {
		  unset($oXML->aVariaveis[$oPostVariavel->sNome]);
		}

		$_SESSION['objetoXML'] = serialize($oXML);




  //************************************************************************************************************************//
  // Incluir as propriedades do relatório
  //************************************************************************************************************************//

  } else if ($oPost->tipo == "incluirPropriedades") {


  	$objPostPropriedades = $oJson->decode(str_replace("\\","",$oPost->objPropriedades));

		$oPropriedades = new dbPropriedadeRelatorio( $objPostPropriedades->sNome,
																								 $objPostPropriedades->iVersao,
																								 $objPostPropriedades->sLayout,
																								 $objPostPropriedades->sFormato,
																							 	 $objPostPropriedades->sOrientacao,
																								 $objPostPropriedades->iMargemSup,
																								 $objPostPropriedades->iMargemInf,
																								 $objPostPropriedades->iMargemEsq,
																								 $objPostPropriedades->iMargemDir,
                                                 $objPostPropriedades->sTipoSaida);
		$oXML->addPropriedades($oPropriedades);

		$_SESSION['objetoXML'] = serialize($oXML);


  //************************************************************************************************************************//
  // Incluir ordem do relatório
  //************************************************************************************************************************//

  } else if ($oPost->tipo == "incluirOrdem") {


    $aReplace = array("\\","(",")");
	  $aObjCampos = $oJson->decode(str_replace($aReplace,"",$oPost->aObjCampos));

	  if (isset($oXML->aOrdem["Principal"])) {
		  unset($oXML->aOrdem["Principal"]);
	  }

	  foreach ($aObjCampos as $sInd => $oCampos){

		  if ( $oCampos ) {

			  $oOrdemRelatorio = new dbOrdemRelatorio( $oCampos->iId,
			                                           $oCampos->sNome,
			                                           $oCampos->sAscDesc,
			                                           $oCampos->sAlias);
			  $oXML->addOrdem($oOrdemRelatorio);

		  }
		}

		$_SESSION['objetoXML'] = serialize($oXML);


  //************************************************************************************************************************//
  // Visualiza um relatório apartir do objeto em sessão, sem salvar nada no banco
  //************************************************************************************************************************//

  } else if ($oPost->tipo == "visualizarRelatorio") {

		$lErro = false;

		try {
      $oXML->addConsulta();
		} catch (Exception $e){
	 	  $sMsgErro = $e->getMessage();
	 	  $lErro = true;
		}

		if (!$lErro) {

			try {
			  $oXML->buildXML();
			} catch (Exception $e){
		 	  $sMsgErro = $e->getMessage();
		 	  $lErro = true;
			}

			if (!$lErro) {

			  $oXML->converteAgt($oXML->getBuffer());

			  $sArquivo   	     = "geraRelatorio".date("YmdHis").db_getsession("DB_id_usuario").".agt";
			  $sCaminhoRelatorio = "tmp/".$sArquivo;

			  $rsRelatorioTemp   = fopen($sCaminhoRelatorio,"w");

			  fputs($rsRelatorioTemp ,$oXML->getBufferAgt());
			  fclose($rsRelatorioTemp);


			  $aObjVariaveis = $oXML->getVariaveis();

			  $aVariaveis	 = array();

			  foreach ($aObjVariaveis as $sNome => $oVariavel){

			  	$oRetornoVariavel = new stdClass();
			    $oRetornoVariavel->sNome     = urlencode($oVariavel->getNome());
			    $oRetornoVariavel->sLabel    = urlencode($oVariavel->getLabel());
			    $oRetornoVariavel->sValor    = urlencode($oVariavel->getValor());
          $oRetornoVariavel->sTipoDado = urlencode($oVariavel->getTipoDado());

			    $aVariaveis[] = $oRetornoVariavel;

			  }
			}
		}

	  if ( !$lErro ) {
		  $aRetorno = array("caminho"=>$sCaminhoRelatorio,"erro"=>false,"variaveis"=>$aVariaveis);
		} else {
		  $aRetorno = array("msg"=>urlencode($sMsgErro)  ,"erro"=>true);
		}

		echo $oJson->encode($aRetorno);


  //************************************************************************************************************************//
  // Inclui o relatório no banco
  //************************************************************************************************************************//

  } else if ($oPost->tipo == "salvarRelatorio") {

	$cldb_relatorio 	     = new cl_db_relatorio();
	$cldb_relatoriousuario = new cl_db_relatoriousuario();
	$cldb_relatoriodepart  = new cl_db_relatoriodepart();

	// Retira alias dos campos do relatório
	if( $oPost->tipoRelatorio == 2 ){
	  $oXML->converteColunaDocumento($oXML->getColunas());
	}

	try {
	  $oXML->addConsulta();
	} catch (Exception $e){
  	  $sMsgErro = $e->getMessage();
  	  $lErro = true;
	}

	try {
	  $oXML->buildXML();
	} catch (Exception $e){
  	$sMsgErro = $e->getMessage();
  	$lErro = true;
	}

	$oPropriedades = $oXML->getPropriedades();

	if (trim($oPropriedades->getNome()) == ""){
	  $sMsgErro = "Inclusão abortada, favor incluir Nome do Relatório";
  	  $lErro    = true;
	}

	if (!$lErro) {

	  db_inicio_transacao();

	  $cldb_relatorio->db63_db_gruporelatorio  = $oPost->grupoRelatorio;
	  $cldb_relatorio->db63_db_tiporelatorio   = $oPost->tipoRelatorio;
	  $cldb_relatorio->db63_nomerelatorio	     = "{$oPropriedades->getNome()}";
	  $cldb_relatorio->db63_versao_xml		     = $oPropriedades->getVersao();
	  $cldb_relatorio->db63_data		  	       = date("Y-m-d",db_getsession("DB_datausu"));
	  $cldb_relatorio->db63_xmlestruturarel    = addslashes($oXML->getBuffer());
	  $cldb_relatorio->db63_db_relatorioorigem = $oXML->getOrigemRelatorio();
	  $cldb_relatorio->incluir(null);

	  if($cldb_relatorio->erro_status == 0){
	    $lErro = true;
	    $sMsgErro = $cldb_relatorio->erro_msg;
	  }

	  if (!$lErro) {

  	 	$cldb_relatoriousuario->db09_db_relatorio = $cldb_relatorio->db63_sequencial;
	  	$cldb_relatoriousuario->db09_db_usuarios  = db_getsession("DB_id_usuario");
	  	$cldb_relatoriousuario->incluir(null);

	  	if($cldb_relatoriousuario->erro_status == 0){
	      $lErro = true;
	      $sMsgErro = $cldb_relatoriousuario->erro_msg;
      }

	    $cldb_relatoriodepart->db07_db_relatorio = $cldb_relatorio->db63_sequencial;
	    $cldb_relatoriodepart->db07_db_depart	 = db_getsession("DB_coddepto");
	    $cldb_relatoriodepart->incluir(null);

	    if($cldb_relatoriodepart->erro_status == 0){
	      $lErro = true;
	      $sMsgErro = $cldb_relatoriodepart->erro_msg;
      }

	  }

	  db_fim_transacao($lErro);
	}

	if (!$lErro){
	  if( isset($_SESSION['objetoXML']) ){
      unset($_SESSION['objetoXML']);
    }
    $aRetorno = array( "msg"=>urlencode('Inclusão feita com sucesso!'),
                       "erro"=>false);
	} else {
	  $aRetorno = array( "msg"=>urlencode($sMsgErro),
	                     "erro"=>true);
	}

  echo $oJson->encode($aRetorno);


  //************************************************************************************************************************//
  // Altera o registros do relatório no banco
  //************************************************************************************************************************//

  } else if ($oPost->tipo == "alterarRelatorio") {


	$cldb_relatorio = new cl_db_relatorio();

	try {
	  $oXML->addConsulta();
	} catch (Exception $e){
 	  $sMsgErro = $e->getMessage();
 	  $lErro = true;
	}
	try {
	  $oXML->buildXML();
	} catch (Exception $e){
 	  $sMsgErro = $e->getMessage();
 	  $lErro = true;
	}

	$oPropriedades = $oXML->getPropriedades();

	if (trim($oPropriedades->getNome()) == ""){
	  $sMsgErro = "Inclusão abortada, favor incluir Nome do Relatório";
    $lErro    = true;
	}

	if (!$lErro) {

	  db_inicio_transacao();

	  $cldb_relatorio->db63_db_gruporelatorio = $oPost->grupoRelatorio;
	  $cldb_relatorio->db63_db_tiporelatorio  = $oPost->tipoRelatorio;
	  $cldb_relatorio->db63_nomerelatorio	    = "{$oPropriedades->getNome()}";
	  $cldb_relatorio->db63_versao_xml		    = $oPropriedades->getVersao();
	  $cldb_relatorio->db63_data		  	      = date("Y-m-d",db_getsession("DB_datausu"));
	  $cldb_relatorio->db63_xmlestruturarel   = addslashes($oXML->getBuffer());
	  $cldb_relatorio->db63_sequencial		    = $oXML->getCodRelatorio();
	  $cldb_relatorio->alterar($oXML->getCodRelatorio());

	  if($cldb_relatorio->erro_status == 0){
	    $lErro    = true;
	    $sMsgErro = $cldb_relatorio->erro_msg;
	  }

	  db_fim_transacao($lErro);
	}

	if (!$lErro){
	  if( isset($_SESSION['objetoXML']) ){
      unset($_SESSION['objetoXML']);
    }
    $aRetorno = array("msg"=>urlencode('Alteração feita com sucesso!'),"erro"=>false);
	} else {
	  $aRetorno = array("msg"=>urlencode($sMsgErro),"erro"=>true);

	}

    echo $oJson->encode($aRetorno);




  //************************************************************************************************************************//
  // Exclui o relatório do banco
  //************************************************************************************************************************//

  } else if ($oPost->tipo == "excluirRelatorio") {

    $cldb_relatorio 	   			= new cl_db_relatorio();
	$cldb_relatoriousuario 			= new cl_db_relatoriousuario();
	$cldb_relatoriodepart  			= new cl_db_relatoriodepart();
 	$cldb_geradorrelatoriotemplate  = new cl_db_geradorrelatoriotemplate();

 	$lSqlErro = false;

 	db_inicio_transacao();

 	$cldb_relatoriodepart->excluir(null," db07_db_relatorio = {$oPost->codRelatorio}");

 	if ($cldb_relatoriodepart->erro_status == 0){
 	  $lSqlErro = true;
 	  $sMsgErro = urlencode($cldb_relatoriodepart->erro_msg);
 	}


 	$cldb_relatoriousuario->excluir(null," db09_db_relatorio = {$oPost->codRelatorio}");

 	if ($cldb_relatoriousuario->erro_status == 0){
 	  $lSqlErro = true;
 	  $sMsgErro = urlencode($cldb_relatoriousuario->erro_msg);
 	}


	$rsConsultaTemplate = $cldb_geradorrelatoriotemplate->sql_record($cldb_geradorrelatoriotemplate->sql_query(null,"db15_sequencial",null," db15_db_relatorio = {$oPost->codRelatorio}"));

	if ($cldb_geradorrelatoriotemplate->numrows > 0 ){
	  $oTemplate = db_utils::fieldsMemory($rsConsultaTemplate,0);
	  $cldb_geradorrelatoriotemplate->excluir($oTemplate->db15_sequencial);
	}

	$cldb_relatorio->excluir($oPost->codRelatorio);

	if ($cldb_relatorio->erro_status == 0){
 	  $lSqlErro = true;
 	  $sMsgErro = urlencode($cldb_relatorio->erro_msg);
 	}

	db_fim_transacao($lSqlErro);

 	if (!$lSqlErro){
	  $aRetorno = array("idRel"=>$oPost->codRelatorio,"erro"=>false);
	} else {
	  $aRetorno = array("msg"=>$sMsgErro,"erro"=>true);
	}

  echo $oJson->encode($aRetorno);

  //************************************************************************************************************************//
  // Retorna as informações do relatório apartir do código e monta um objeto na sessão
  //************************************************************************************************************************//

  } else if ($oPost->tipo == "buscaDadosRelatorio") {

    try{

      $aRetornaCampos             = array();
      $aRetornaCamposConfigurados = array();
      $aRetornaOrdem              = array();
      $aRetornaFiltros            = array();
      $aRetornoVariaveis          = array();

      $cldb_relatorio = new cl_db_relatorio();

      $aCampos = $oXML->getDadosCampos();

      foreach ($aCampos as $iInd => $oCampo) {
        $oRetornoColuna = new stdClass();
        $oRetornoColuna->iId              = $oCampo->getId();
        $oRetornoColuna->sNome            = urlencode($oCampo->getNome());
        $oRetornoColuna->sAlias           = urlencode($oCampo->getAlias());
        $oRetornoColuna->iLargura         = $oCampo->getLargura();
        $oRetornoColuna->sAlinhamento     = $oCampo->getAlinhamento();
        $oRetornoColuna->sAlinhamentoCab  = $oCampo->getAlinhamentoCab();
        $oRetornoColuna->sMascara         = $oCampo->getMascara();
        $oRetornoColuna->sTotalizar       = $oCampo->getTotalizar();
        $oRetornoColuna->lQuebra          = $oCampo->getQuebra();
        $aRetornaCampos[] = $oRetornoColuna;
      }


      $aColunas = $oXML->getColunas();

      if ( !empty($aColunas) ) {
        foreach ( $aColunas as $sNomeCampo => $oCampoConfigurado) {

          $oRetornoColunaConf = new stdClass();
          $oRetornoColunaConf->iId              = $oCampoConfigurado->getId();
          $oRetornoColunaConf->sNome            = urlencode($oCampoConfigurado->getNome());
          $oRetornoColunaConf->sAlias           = urlencode($oCampoConfigurado->getAlias());
          $oRetornoColunaConf->iLargura         = $oCampoConfigurado->getLargura();
          $oRetornoColunaConf->sAlinhamento     = $oCampoConfigurado->getAlinhamento();
          $oRetornoColunaConf->sAlinhamentoCab  = $oCampoConfigurado->getAlinhamentoCab();
          $oRetornoColunaConf->sMascara         = $oCampoConfigurado->getMascara();
          $oRetornoColunaConf->sTotalizar       = $oCampoConfigurado->getTotalizar();
          $oRetornoColunaConf->lQuebra          = $oCampoConfigurado->getQuebra();
          $aRetornaCamposConfigurados[]         = $oRetornoColunaConf;

        }
      }

      $aOrdens = $oXML->getOrdem();

      foreach ($aOrdens as $iInd => $aOrdem){
        foreach ($aOrdem as $sInd => $oOrdem){
          $oRetornoOrdem = new stdClass();
          $oRetornoOrdem->iId      = $oOrdem->getId();
          $oRetornoOrdem->sNome    = urlencode($oOrdem->getNome());
          $oRetornoOrdem->sAscDesc = $oOrdem->getAscDesc();
          $oRetornoOrdem->sAlias   = urlencode($oOrdem->getAlias());
          $aRetornaOrdem[] = $oRetornoOrdem;
        }
      }


      $aFiltros = $oXML->getFiltros();

      foreach ($aFiltros as $iInd => $aFiltro){
        foreach ($aFiltro as $sInd => $oFiltro){
          $oRetornoFiltro = new stdClass();
          $oRetornoFiltro->sOperador = $oFiltro->getOperador();
          $oRetornoFiltro->sCampo    = urlencode($oFiltro->getCampo());
          $oRetornoFiltro->sCondicao = $oFiltro->getCondicao();
          $oRetornoFiltro->sValor    = urlencode($oFiltro->getValor());
          $aRetornaFiltros[] = $oRetornoFiltro;
        }
      }

      $oPropriedades = $oXML->getPropriedades();

      $oRetornoPropriedades = new stdClass();
      $oRetornoPropriedades->iVersao     = $oPropriedades->getVersao();
      $oRetornoPropriedades->sNome       = urlencode($oPropriedades->getNome());
      $oRetornoPropriedades->sOrientacao = $oPropriedades->getOrientacao();
      $oRetornoPropriedades->sFormato    = $oPropriedades->getFormato();
      $oRetornoPropriedades->sLayout     = $oPropriedades->getLayout();
      $oRetornoPropriedades->iMargemDir  = $oPropriedades->getMargemDir();
      $oRetornoPropriedades->iMargemEsq  = $oPropriedades->getMargemEsq();
      $oRetornoPropriedades->iMargemInf  = $oPropriedades->getMargemInf();
      $oRetornoPropriedades->iMargemSup  = $oPropriedades->getMargemSup();
    $oRetornoPropriedades->sTipoSaida  = $oPropriedades->getTipoSaida();


      $aVariaveis = $oXML->getVariaveis();

      foreach ($aVariaveis as $sNomeVar => $oVariavel ){
        $oRetornoVariaveis = new stdClass();
        $oRetornoVariaveis->sNome     = $oVariavel->getNome();
        $oRetornoVariaveis->sLabel    = urlencode($oVariavel->getLabel());
        $oRetornoVariaveis->sValor    = urlencode($oVariavel->getValor());
        $oRetornoVariaveis->sTipoDado = $oVariavel->getTipoDado();

        $aRetornoVariaveis[] = $oRetornoVariaveis;
      }


      $rsConsultaTipoGrupo = $cldb_relatorio->sql_record($cldb_relatorio->sql_query_file($oXML->getCodRelatorio(),"db63_db_tiporelatorio as tiporel,db63_db_gruporelatorio as gruporel "));
      $oRetornoTipoGrupo   = db_utils::fieldsMemory($rsConsultaTipoGrupo,0);

      $aRetorno = array(
                        "aCampos"            =>$aRetornaCampos,
                        "aCamposConfigurados"=>$aRetornaCamposConfigurados,
                        "aOrdem"             =>$aRetornaOrdem,
                        "aFiltros"           =>$aRetornaFiltros,
                        "oPropriedades"      =>$oRetornoPropriedades,
                        "aVariaveis"         =>$aRetornoVariaveis,
                        "oTipoGrupo"         =>$oRetornoTipoGrupo
                       );


      $_SESSION['objetoXML'] = serialize($oXML);


    } catch (Exception $oException) {
      $aRetorno = array("msg"=>$oException->getMessage(),"erro"=>true);
    }

    echo $oJson->encode($aRetorno);

  //************************************************************************************************************************//
  // Retorna todos relatório por departamento ou usuário
  //************************************************************************************************************************//

  } else if ($oPost->tipo == "consultaRelatorios") {

    $cldb_relatoriousuario = new cl_db_relatoriousuario();
    $cldb_relatoriodepart  = new cl_db_relatoriodepart();
    $cldb_relatorio        = new cl_db_relatorio();

  if ($oPost->sTipoPesquisa == "Depto"){

	  $rsConsultaRelatorios = $cldb_relatoriodepart->sql_record($cldb_relatoriodepart->sql_query(null,"db63_nomerelatorio as nomeRelatorio, db63_sequencial as idRel","db63_sequencial"," db07_db_depart = ".$oPost->codDepto));
	  $iNroLinhas = $cldb_relatoriodepart->numrows;

	} elseif ($oPost->sTipoPesquisa == "Usuario") {

		$rsConsultaRelatorios = $cldb_relatoriousuario->sql_record($cldb_relatoriousuario->sql_query(null,"db63_nomerelatorio as nomeRelatorio, db63_sequencial as idRel","db63_sequencial","db09_db_usuarios = ".$oPost->idUsuario));
	  $iNroLinhas = $cldb_relatoriousuario->numrows;

	}	else {

		$sWhere               = "(    not exists (select 1 from db_relatoriodepart where db07_db_relatorio = db_relatorio.db63_sequencial)  ";
		$sWhere              .= " and not exists (select 1 from db_relatoriousuario where db09_db_relatorio = db_relatorio.db63_sequencial)) ";
		$rsConsultaRelatorios = $cldb_relatorio->sql_record($cldb_relatorio->sql_query_file(null,"db63_nomerelatorio as nomeRelatorio, db63_sequencial as idRel","db63_sequencial", $sWhere));
		$iNroLinhas           = $cldb_relatorio->numrows;

	}

 	if ( $iNroLinhas > 0 ){
 	  $aRetornaRel = db_utils::getCollectionByRecord($rsConsultaRelatorios,false,false,true);
 	  $aRetorno    = array("objRel"=>$aRetornaRel,"erro"=>false);
	} else {
	  $aRetorno    = array("msg"=>urlencode("Nenhum relatório cadastrado!"),"erro"=>true);
	}

    echo $oJson->encode($aRetorno);



  //************************************************************************************************************************//
  // Remove o objeto da sessão
  //************************************************************************************************************************//


  } else if ($oPost->tipo == "retiraObjetoSessao") {


    if( isset($_SESSION['objetoXML']) ){
  	  unset($_SESSION['objetoXML']);
    }



  //************************************************************************************************************************//
  // Consulta Variáveis
  //************************************************************************************************************************//


  } else if ($oPost->tipo == "consultaVariaveis") {

  	$aRetorno   = array();
  	$aVariaveis = $oXML->getVariaveis();

  	foreach ( $aVariaveis as $sInd => $objVariavel ){

  	  $objRetornoVariavel = new stdClass();
  	  $objRetornoVariavel->sNome     = utf8_encode($objVariavel->getNome());
  	  $objRetornoVariavel->sLabel    = utf8_encode($objVariavel->getLabel());
  	  $objRetornoVariavel->sValor    = utf8_encode($objVariavel->getValor());
      $objRetornoVariavel->sTipoDado = utf8_encode($objVariavel->getTipoDado());
  	  $aRetorno[] = $objRetornoVariavel;
    }

  	echo $oJson->encode($aRetorno);



  } else  if ( $oPost->tipo == "incluirConsulta" ) {

  	unset($oXML->aColunas);
  	unset($oXML->aOrdem);
  	unset($oXML->aVariaveis);
  	unset($oXML->aFiltros);

  	if ( isset($oPost->sql) ) {

      /**
       * Alteração realizada por motivos de sql injection detectado pelo firewall do cliente
       */
      $oPost->sql = base64_decode($oPost->sql);

  		$sSql = stripslashes($oPost->sql);
  		$oXML->setOrigemRelatorio(1);

  		try {
  		  $oXML->addSqlFrom(str_replace(";","",$sSql));
  		} catch ( Exception $eException ) {
  			$sMsgErro = $eException->getMessage();
  			$lErro    = true;
  		}

  	  try {
        $oXML->verificaVariaveisConsulta();
      } catch ( Exception $eException ) {
        $sMsgErro = $eException->getMessage();
        $lErro    = true;
      }


  	} else if (isset($oPost->view)) {

  		$oXML->setOrigemRelatorio(2);

  		try {
        $oXML->addSqlFrom($oPost->view);
      } catch ( Exception $eException ) {
        $sMsgErro = $eException->getMessage();
        $lErro    = true;
      }

  	} else {
 		  $sMsgErro = 'Nenhuma consulta informada!';
  		$lErro    = true;
  	}

  	if ($lErro) {
  	  $aRetorno  = array( "msg" =>urlencode($sMsgErro),
  	                      "erro"=>true );
  	} else {

      $_SESSION['objetoXML'] = serialize($oXML);

      $aRetorno  = array( "erro"=>false );
  	}

    echo $oJson->encode($aRetorno);



 } else  if ( $oPost->tipo == "consultaSQL" ) {


	 try {
	   $sSql = $oXML->getSqlFrom('Principal');
	 } catch ( Exception $eException ) {
	   $sMsgErro = $eException->getMessage();
	   $lErro    = true;
	 }


	 if ($lErro) {
	   $aRetorno  = array( "msg" =>urlencode($sMsgErro),
	                       "erro"=>true );
	 } else {
	   $aRetorno  = array( "sSql"=>urlencode($sSql),
	                       "erro"=>false );
	 }

	 echo $oJson->encode($aRetorno);


 } else if ( $oPost->tipo == "carregarRelatorio" ) {

    if ( isset($oPost->iCodRelatorio) ) {

    	try {
 	      $oXML = new dbGeradorRelatorio($oPost->iCodRelatorio);
    	} catch ( Exception $eException ){
        $sMsgErro = $eException->getMessage();
        $lErro    = true;
    	}

    } else {
      $sMsgErro = 'Código do relatório não informado!';
      $lErro    = true;
    }

    if ($lErro) {
      $aRetorno  = array( "msg" =>urlencode($sMsgErro),
                          "erro"=>true );
    } else {

      if( isset($_SESSION['objetoXML']) ){
        unset($_SESSION['objetoXML']);
      }

      $_SESSION['objetoXML']        = serialize($oXML);
      $_SESSION['lAlteraRelatorio'] = true;

      if ($oXML->getOrigemRelatorio() == 1){
      	$lSql = true;
      } else {
      	$lSql = false;
      }

      $aRetorno  = array( "lSql"=>$lSql,
                          "erro"=>false );
    }

    echo $oJson->encode($aRetorno);

 } else if ( $oPost->tipo == "verificaAlteracao" ) {


 	  if ( $_SESSION['lAlteraRelatorio'] ) {
 	  	$aRetorno = array("lAlteracao"=>true);
 	  } else {
 	  	$aRetorno = array("lAlteracao"=>false);
 	  }

 	  echo $oJson->encode($aRetorno);

  } else if ($oPost->tipo == "cadastrarMenu") {

  	$oDaoDbrelatorio = new cl_db_relatorio();
  	$oDaoDbitensmenu = new cl_db_itensmenu();
  	$oDaoDbpermissao = new cl_db_permissao();
  	$oDaoDbmenu      = new cl_db_menu();
  	$sMensagemErro   = "";
  	$lErroTransacao  = false;

  	db_inicio_transacao();

  	$rsDbrelatorio = $oDaoDbrelatorio->sql_record($oDaoDbrelatorio->sql_query_file($oPost->iCodRelatorio));

  	$oDbrelatorio  = db_utils::fieldsMemory($rsDbrelatorio,0);

    /**
     * Cadastrando item de menu para o relatorio criado pelo gerador de relatorio
     *   Item de menu e criado com o nome do relatorio
     */
  	$oDaoDbitensmenu->id_item    = null;
    $oDaoDbitensmenu->descricao  = $oDbrelatorio->db63_nomerelatorio;
    $oDaoDbitensmenu->help       = $oDbrelatorio->db63_nomerelatorio;
    $oDaoDbitensmenu->funcao     = "sys4_geradorteladinamica001.php?iCodRelatorio={$oPost->iCodRelatorio}";
    $oDaoDbitensmenu->itemativo  = "1";
    $oDaoDbitensmenu->manutencao = "1";
    $oDaoDbitensmenu->desctec    = $oDbrelatorio->db63_nomerelatorio;
    $oDaoDbitensmenu->libcliente = "true";
 	  $oDaoDbitensmenu->incluir(null);
 	  if ($oDaoDbitensmenu->erro_status == '0') {
 	  	$lErroTransacao = true;
 	  	$sMensagemErro  = $oDaoDbitensmenu->erro_msg;
 	  }

 	  $rsSequenciaMenu = $oDaoDbmenu->sql_record($oDaoDbmenu->sql_query_file(null,
 	                                                                         "(max(menusequencia)+1) as menusequencia",
 	                                                                          null,
 	                                                                         "id_item = {$oPost->itemPai}"));
 	  $oMenuSequencia = db_utils::fieldsMemory($rsSequenciaMenu,0);
    /**
     * Organizando o item de menu abaixo do item selecionado
     */
 	  $oDaoDbmenu->id_item        = $oPost->itemPai;
    $oDaoDbmenu->id_item_filho  = $oDaoDbitensmenu->id_item;
    $oDaoDbmenu->menusequencia  = $oMenuSequencia->menusequencia;
    $oDaoDbmenu->modulo         = $oPost->iModulo;
    $oDaoDbmenu->incluir();
    if ($oDaoDbmenu->erro_status == '0') {
      $lErroTransacao = true;
      $sMensagemErro  = $oDaoDbmenu->erro_msg;
    }
    /**
     * Liberando permissao de menu para o usuario que criou o relatorio
     */
 	  $oDaoDbpermissao->id_item        = $oDaoDbitensmenu->id_item;
    $oDaoDbpermissao->id_usuario     = db_getsession('DB_id_usuario');
    $oDaoDbpermissao->permissaoativa = '1';
    $oDaoDbpermissao->anousu         = db_getsession('DB_anousu');
    $oDaoDbpermissao->id_instit      = db_getsession('DB_instit');
    $oDaoDbpermissao->id_modulo      = $oPost->iModulo;
    $oDaoDbpermissao->incluir(db_getsession('DB_id_usuario'), $oDaoDbitensmenu->id_item, db_getsession('DB_anousu'),
                              db_getsession('DB_instit'), $oPost->iModulo);
    if ($oDaoDbpermissao->erro_status == '0') {
      $lErroTransacao = true;
      $sMensagemErro  = $oDaoDbpermissao->erro_msg;
    }

    DBMenu::limpaCache();

 	  db_fim_transacao($lErroTransacao);

    $oRetorno = new stdClass();
    $oRetorno->sMensagem = urlencode($sMensagemErro);
    $oRetorno->lErro     = $lErroTransacao;

    echo $oJson->encode($oRetorno);

  } else if ($oPost->tipo == 'exportarRelatorio') {

  	$oRetorno = new stdClass();

  	$oRetorno->iStatus = 1;

  	try {

  		$oDBGeradorRelatorio     = new dbGeradorRelatorio($oPost->iCodigoRelatorio);

  		$oRetorno->sNomeArquivo  = $oDBGeradorRelatorio->exportar();

  	} catch (Exception $oException) {

  		$oRetorno->iStatus   = 2;
  		$oRetorno->sMensagem = urlencode($oException->getMessage());

  	}

  	echo $oJson->encode($oRetorno);

  } else if ($oPost->tipo == 'importarRelatorio') {

  	$oRetorno = new stdClass();
  	$oRetorno->iStatus = 1;

  	try {

  		$iGrupoRelatorio = $oPost->codigo_grupo;
  		$iTipoRelatorio  = $oPost->codigo_tipo;

  		$oDomDocument = new DOMDocument();

  		/**
  		 * Utilizado operador de silêncio (@) para evitar warning decorrente da classe DOMDocument, quando é carregado um arquivo
  		 * xml inválido
  		 */
  		if ( !@$oDomDocument->load($oPost->arquivo) ) {
  			throw new Exception(_M('configuracao.configuracao.sys4_consultaviewRPC.erro_importar_arquivo'));
  		}

  		dbGeradorRelatorio::importar($oDomDocument, $iGrupoRelatorio, $iTipoRelatorio);

  	} catch (Exception $oException) {

  		$oRetorno->iStatus   = 2;
  		$oRetorno->sMensagem = urlencode($oException->getMessage());

  	}

  	echo $oJson->encode($oRetorno);

  }
