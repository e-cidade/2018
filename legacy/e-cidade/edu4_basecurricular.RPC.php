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
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));

$oJson               = new services_json();
$oParam              = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oErro               = new stdClass();
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';
define('ARQUIVO_MENSAGEM_BASECURRICULAR_RPC', 'educacao.escola.edu4_basecurricular_RPC.');


try {

  db_inicio_transacao();

  switch($oParam->sExecucao) {

    case 'buscaBaseCurricular':

      if ( empty($oParam->iBaseCurricular) ) {
        throw new ParameterException( _M(ARQUIVO_MENSAGEM_BASECURRICULAR_RPC . 'informe_base') );
      }

      $oBaseCurricular               = new BaseCurricular( $oParam->iBaseCurricular );
      $oRetorno->iCurso              = $oBaseCurricular->getCurso()->getCodigo();
      $oRetorno->sCurso              = urlencode($oBaseCurricular->getCurso()->getNome());
      $oRetorno->sNomeBase           = urlencode($oBaseCurricular->getDescricao());
      $oRetorno->sTurno              = urlencode($oBaseCurricular->getTurno());
      $oRetorno->iRegimeMatricula    = $oBaseCurricular->getRegimeMatricula()->getCodigo();
      $oRetorno->sRegimeMatricula    = urlencode($oBaseCurricular->getRegimeMatricula()->getNome());
      $oRetorno->iEtapaInicial       = $oBaseCurricular->getEtapaInicial()->getCodigo();
      $oRetorno->sEtapaInicial       = urlencode($oBaseCurricular->getEtapaInicial()->getNome());
      $oRetorno->iEtapaFinal         = $oBaseCurricular->getEtapaFinal()->getCodigo();
      $oRetorno->sEtapaFinal         = urlencode($oBaseCurricular->getEtapaFinal()->getNome());
      $oRetorno->sFrequencia         = urlencode($oBaseCurricular->getFrequencia());
      $oRetorno->sControleFrequencia = urlencode($oBaseCurricular->getControleFrequencia());
      $oRetorno->lConcluiCurso       = $oBaseCurricular->encerraCurso();
      $oRetorno->lAtiva              = $oBaseCurricular->isAtiva();
      $oRetorno->sObservacao         = urlencode($oBaseCurricular->getObservacao());
    break;
  }

  db_fim_transacao();

} catch ( Exception $oErro ) {

  db_fim_transacao(true);
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode($oErro->getMessage());
}

$oRetorno->erro = $oRetorno->iStatus == 2;
echo $oJson->encode($oRetorno);