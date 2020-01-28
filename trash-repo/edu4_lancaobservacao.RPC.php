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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("std/db_stdClass.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("model/educacao/avaliacao/iFormaObtencao.interface.php");
require_once ("model/educacao/avaliacao/iElementoAvaliacao.interface.php");


db_app::import("educacao.*");
db_app::import("educacao.avaliacao.*");
db_app::import("exceptions.*");

$iEscola           = db_getsession("DB_coddepto");
$oJson             = new Services_JSON();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

try {

  switch($oParam->exec) {

    case 'getObservacaoAluno':
      
      $oMatricula          = MatriculaRepository::getAlunoByMatricula($oParam->iMatricula);
      $oRegencia           = RegenciaRepository::getRegenciaByCodigo($oParam->iRegencia);
      $oAvaliacaoPeriodica = AvaliacaoPeriodicaRepository::getAvaliacaoPeriodicaByCodigo($oParam->iPeriodo);
      
      db_inicio_transacao();
      
      $oDiarioClasse = $oMatricula->getDiarioDeClasse();
      $oAvaliacao    = $oDiarioClasse->getDisciplinasPorRegenciaPeriodo($oRegencia, $oAvaliacaoPeriodica);
      
      db_fim_transacao();
      
      $oRetorno->sObservacao = urlencode($oAvaliacao->getObservacao());
      
      break;
      
    case 'salvarObservacao':
      
      $oMatricula          = MatriculaRepository::getAlunoByMatricula($oParam->iMatricula);
      $oAvaliacaoPeriodica = AvaliacaoPeriodicaRepository::getAvaliacaoPeriodicaByCodigo($oParam->iPeriodo);
      $sObservacao         = db_stdClass::normalizeStringJsonEscapeString($oParam->sObservacao);
      
      db_inicio_transacao();

      foreach ($oParam->aRegencias as $iCodigoRegencia) {
        
        $oRegencia           = RegenciaRepository::getRegenciaByCodigo($iCodigoRegencia);
        $oDiarioClasse = $oMatricula->getDiarioDeClasse();
        $oAvaliacao    = $oDiarioClasse->getDisciplinasPorRegenciaPeriodo($oRegencia, $oAvaliacaoPeriodica);
        $oAvaliacao->setObservacao($sObservacao);
        $oDiarioClasse->salvar();
      }
      
      db_fim_transacao();
      
      $oRetorno->message = "Observação salva.";
      
      break;
  }
} catch (ParameterException $oErro) {
  
  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = $oErro->getMessage();
} catch (BusinessException $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = $oErro->getMessage();
} catch (DBException $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = $oErro->getMessage();
}

$oRetorno->message = urlencode($oRetorno->message);
echo $oJson->encode($oRetorno);