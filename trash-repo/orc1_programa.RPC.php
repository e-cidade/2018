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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("libs/JSON.php");
require_once("libs/exceptions/DBException.php");
require_once("libs/exceptions/BusinessException.php");
require_once("std/db_stdClass.php");


$oJson                  = new services_json();
$oParametro             = $oJson->decode(str_replace('\\', "" , $_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

try{

  db_inicio_transacao();

  switch ($oParametro->exec) {

    case "salvarObjetivo":

      $oOrgao    = new Orgao($oParametro->iOrgao, db_getsession("DB_anousu"));
      $oObjetivo = new ProgramaObjetivo($oParametro->iCodigoObjetivo);
      $oObjetivo->setOrgao($oOrgao);
      $oObjetivo->setDescricao(db_stdClass::normalizeStringJsonEscapeString($oParametro->sDescricao));
      $oObjetivo->setObjetivo(db_stdClass::normalizeStringJsonEscapeString($oParametro->sObjetivo));
      $oObjetivo->salvar();

      $iCodigoObjetivo = $oObjetivo->getCodigoSequencial();

      $oRetorno->sMessage        = "Objetivo {$iCodigoObjetivo} salvo com sucesso.";
      $oRetorno->iCodigoObjetivo = $iCodigoObjetivo;
      break;

    case "excluirObjetivo":

      $oObjetivo = new ProgramaObjetivo($oParametro->iCodigoObjetivo);
      $oObjetivo->excluir();

      $oRetorno->sMessage = "Objetivo {$oObjetivo->getCodigoSequencial()} exclu�do com sucesso.";
      break;

    case "adicionarMeta":

      $oObjetivo = new ProgramaObjetivo($oParametro->iCodigoObjetivo);
      $oObjetivo->adicionaMeta(db_stdClass::normalizeStringJsonEscapeString($oParametro->sDescricao),
                               db_stdClass::normalizeStringJsonEscapeString($oParametro->sMeta));
      $oObjetivo->salvar();

      $oRetorno->sMessage = "Meta vinculada com sucesso.";

      break;

    case "buscaMetas":

      $oObjetivo = new ProgramaObjetivo($oParametro->iCodigoObjetivo);
      $aMetas    = $oObjetivo->getMetas();

      $oRetorno->aMetas = array();
      foreach($aMetas as $oMeta) {

        $oStdMeta = new stdClass();
        $oStdMeta->iCodigoMeta    = $oMeta->getCodigoSequencial();
        $oStdMeta->sDescricaoMeta = urlencode($oMeta->getDescricao());
        $oRetorno->aMetas[]       = $oStdMeta;
      }

      break;

    case "buscaMetaPorCodigo":

      $oObjetivo = new ProgramaObjetivo($oParametro->iCodigoObjetivo);
      $oMeta     = $oObjetivo->getMetaPorCodigo($oParametro->iCodigoMeta);

      $oRetorno->oMeta                 = new stdClass();
      $oRetorno->oMeta->iCodigoMeta    = $oMeta->getCodigoSequencial();
      $oRetorno->oMeta->sDescricaoMeta = urlencode($oMeta->getDescricao());
      $oRetorno->oMeta->sMeta          = urlencode($oMeta->getMeta());

      break;


    case "alterarMeta":

      $oObjetivo = new ProgramaObjetivo($oParametro->iCodigoObjetivo);
      $oMeta     = $oObjetivo->getMetaPorCodigo($oParametro->iCodigoMeta);
      $oMeta->setDescricao(db_stdClass::normalizeStringJsonEscapeString($oParametro->sDescricao));
      $oMeta->setMeta(db_stdClass::normalizeStringJsonEscapeString($oParametro->sMeta));
      $oMeta->salvar();

      $oRetorno->sMessage = "Meta alterada com sucesso.";
      break;


    case "excluirMetas":

      $oObjetivo = new ProgramaObjetivo($oParametro->iCodigoObjetivo);

      foreach($oParametro->aMetas as $iMeta) {

        $oMeta = $oObjetivo->getMetaPorCodigo($iMeta);
        $oMeta->excluir();
      }

      $oRetorno->sMessage = "Metas exclu�das com sucesso.";
      break;

    case "adicionarIniciativa":

      $oObjetivo    = new ProgramaObjetivo($oParametro->iCodigoObjetivo);
      $oMeta        = $oObjetivo->getMetaPorCodigo($oParametro->iCodigoMeta);
      $oMeta->adicionaIniciativa(db_stdClass::normalizeStringJsonEscapeString($oParametro->sDescricao),
                                 db_stdClass::normalizeStringJsonEscapeString($oParametro->sIniciativa),
                                 $oParametro->iAno
                                 );
      $oMeta->salvar();

      $oRetorno->sMessage = "Iniciativa adicionada com sucesso.";

    break;

    case "buscaIniciativas":

      $oObjetivo    = new ProgramaObjetivo($oParametro->iCodigoObjetivo);
      $oMeta        = $oObjetivo->getMetaPorCodigo($oParametro->iCodigoMeta);
      $aIniciativas = $oMeta->getIniciativas();

      $oRetorno->aIniciativas = array();
      foreach ($aIniciativas as $oIniciativa) {

        $oStdIniciativa = new stdClass();
        $oStdIniciativa->iCodigoIniciativa    = $oIniciativa->getCodigoSequencial();
        $oStdIniciativa->sDescricaoIniciativa = urlencode($oIniciativa->getDescricao());
        $oRetorno->aIniciativas[]             = $oStdIniciativa;
      }

    break;

    case "buscaIniciativaPorCodigo":

      $oObjetivo    = new ProgramaObjetivo($oParametro->iCodigoObjetivo);
      $oMeta        = $oObjetivo->getMetaPorCodigo($oParametro->iCodigoMeta);
      $oIniciativa  = $oMeta->getIniciativaPorCodigo($oParametro->iCodigoIniciativa);

      $oStdIniciativa                       = new stdClass();
      $oStdIniciativa->iCodigoIniciativa    = $oIniciativa->getCodigoSequencial();
      $oStdIniciativa->sDescricaoIniciativa = urlencode($oIniciativa->getDescricao());
      $oStdIniciativa->sIniciativa          = urlencode($oIniciativa->getIniciativa());
      $oStdIniciativa->iAno                 = urlencode($oIniciativa->getAno());
      $oRetorno->oIniciativa                = $oStdIniciativa;
    break;

    case "alterarIniciativa":

      $oObjetivo   = new ProgramaObjetivo($oParametro->iCodigoObjetivo);
      $oMeta       = $oObjetivo->getMetaPorCodigo($oParametro->iCodigoMeta);
      $oIniciativa = $oMeta->getIniciativaPorCodigo($oParametro->iCodigoIniciativa);
      $oIniciativa->setDescricao(db_stdClass::normalizeStringJsonEscapeString($oParametro->sDescricao));
      $oIniciativa->setAno(db_stdClass::normalizeStringJsonEscapeString($oParametro->iAno));
      $oIniciativa->setIniciativa(db_stdClass::normalizeStringJsonEscapeString($oParametro->sIniciativa));
      $oIniciativa->salvar();

      $oRetorno->sMessage = "Iniciativa alterada com sucesso.";
    break;

    case "excluirIniciativas":

      $oObjetivo   = new ProgramaObjetivo($oParametro->iCodigoObjetivo);
      $oMeta       = $oObjetivo->getMetaPorCodigo($oParametro->iCodigoMeta);

      foreach($oParametro->aIniciativas as $iIniciativa) {

        $oIniciativa       = $oMeta->getIniciativaPorCodigo($iIniciativa);
        $oIniciativa->excluir();
      }

      $oRetorno->sMessage = "Iniciativas exclu�das com sucesso.";

      break;


    case "getIniciativaVinculadaProjetoAtividade":


      $oProgramaProjetoAtividade = new ProgramaProjetoAtividade($oParametro->iCodigoProjeto, $oParametro->iAnoProjeto);
      $aIniciativas              = $oProgramaProjetoAtividade->getIniciativas();
      $aIniciativasRetorno       = array();
      if (count($aIniciativas) > 0) {

        foreach ($aIniciativas as $iCodigoIniciativa => $oIniciativa) {

          $oStdIniciativa                  = new stdClass();
          $oStdIniciativa->o147_sequencial = $iCodigoIniciativa;
          $oStdIniciativa->o147_descricao  = urlencode($oIniciativa->getDescricao());
          $aIniciativasRetorno[] = $oStdIniciativa;
          unset($oStdIniciativa);
        }
      }
      $oRetorno->aIniciativasProjetoAtividade = $aIniciativasRetorno;

      break;

    case "excluirVinculoIniciativaProjeto":

      $oProgramaProjetoAtividade = new ProgramaProjetoAtividade($oParametro->iCodigoProjeto, $oParametro->iAnoProjeto);
      foreach ($oParametro->aIniciativasExcluir as $aDadosIniciativa) {
        $oProgramaProjetoAtividade->removerIniciativa(new ProgramaIniciativa($aDadosIniciativa[0]));
      }

      break;


    case "vincularIniciativaProjeto":

      $oProgramaProjetoAtividade = new ProgramaProjetoAtividade($oParametro->iCodigoProjeto, $oParametro->iAnoProjeto);
      $oProgramaProjetoAtividade->vincularIniciativa(new ProgramaIniciativa($oParametro->iCodigoIniciativa));
      $oRetorno->sMessage = "Iniciativa vinculada com sucesso.";
      break;

    case "vincularObjetivoPrograma":

      $oPrograma = new Programa($oParametro->iCodigoPrograma, $oParametro->iAnoPrograma);
      $oObjetivo = new ProgramaObjetivo($oParametro->iCodigoObjetivo);

      if (!empty($oObjetivo)) {

        $oPrograma->adicionaObjetivo($oObjetivo);
        $sMessage = "Objetivo {$oObjetivo->getCodigoSequencial()} vinculado com sucesso ao Programa {$oPrograma->getCodigoPrograma()}.";

        $oRetorno->sMessage = $sMessage;
      }

      break;

    case "desvincularObjetivoPrograma":

      $oPrograma = new Programa($oParametro->iCodigoPrograma, $oParametro->iAnoPrograma);
      foreach($oParametro->aObjetivos as $iCodigoObjetivo) {

        $oObjetivo = new ProgramaObjetivo($iCodigoObjetivo);
        $oPrograma->excluirObjetivo($oObjetivo);
      }

      $oRetorno->sMessage = "Objetivos desvinculados com sucesso.";

      break;

    case "buscaObjetivosVinculadosPrograma":

      $oPrograma  = new Programa($oParametro->iCodigoPrograma, $oParametro->iAnoPrograma);
      $aObjetivos = $oPrograma->getObjetivos();

      $oRetorno->aObjetivos = array();

      foreach ($aObjetivos as $oObjetivo) {

        $oStdObjetivo = new stdClass();
        $oStdObjetivo->iCodigoObjetivo    = $oObjetivo->getCodigoSequencial();
        $oStdObjetivo->sDescricaoObjetivo = urlencode($oObjetivo->getDescricao());
        $oRetorno->aObjetivos[]           = $oStdObjetivo;
      }
      break;

    case "pesquisaObjetivo":

      $oObjetivo                    = new ProgramaObjetivo($oParametro->iCodigoObjetivo);
      $oRetorno->o143_objetivo      = urlencode($oObjetivo->getObjetivo());
      $oRetorno->o143_descricao     = urlencode($oObjetivo->getDescricao());
      $oRetorno->o143_orcorgaoorgao = $oObjetivo->getOrgao()->getCodigoOrgao();
      break;
  }

  db_fim_transacao(false);
  $oRetorno->sMessage = urlencode($oRetorno->sMessage);

} catch(Exception $oException) {

  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($oException->getMessage());
  db_fim_transacao(true);
}

echo $oJson->encode($oRetorno);