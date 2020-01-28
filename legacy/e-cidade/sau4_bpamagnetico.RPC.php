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
require_once(modification("std/DBDate.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_layouttxt.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_stdlibwebseller.php"));

$oJson             = new Services_JSON();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

try {

  switch ($oParam->exec) {

  	case 'gerarBPA':

      if ( empty($oParam->iFechamento) ) {
        throw new ParameterException("Competencia não foi informada!");
      }
      if ( empty($oParam->iUnidade) ) {
        throw new ParameterException("Nenhuma UPS foi selecionada!");
      }

  	  $oCompetencia  = new CompetenciaAtendimento($oParam->iFechamento);

  	  $iTipoLayout = BPAMagnetico::BPA_CONSOLIDADO;
  	  if ($oParam->sTipo == '02') {
  	    $iTipoLayout = BPAMagnetico::BPA_INDIVIDUAL;
  	  }

  	  $sNomeArquivo            = "/tmp/{$oParam->sNomeArquivo}";
  	  $sArquivoInconsistencia  = "tmp/erro_bpa_magnetico.json";

      $oBPAMagnetico = new BPAMagnetico($iTipoLayout, $sNomeArquivo, $oCompetencia, new DBLogJSON($sArquivoInconsistencia));
      $oBPAMagnetico->setInstituicao(new Instituicao(db_getsession("DB_instit")));

      /**
       * Adiciona as unidades filtradas
       */
      if (!empty($oParam->iUnidade)) {

        foreach (explode(",", $oParam->iUnidade) as $iUnidade) {

          if ( empty($iUnidade) ) {
            continue;
          }

          $oBPAMagnetico->adicionarUnidades(UnidadeProntoSocorroRepository::getUnidadeProntoSocorroByCodigo($iUnidade));
        }
      }

      $oBPAMagnetico->setVersaoSistema(DB_VERSION);

      db_inicio_transacao();
      $oBPAMagnetico->escreverArquivo();
      db_fim_transacao();

      /**
       * Retorno dos dados para cliente
       */
      $oRetorno->oDadosBPA              = $oBPAMagnetico->getInformacoesCabecalho();
      $oRetorno->sNomeArquivo           = urlencode($sNomeArquivo);
      $oRetorno->lTemInconsistencia     = $oBPAMagnetico->temInconsistencia();
      $oRetorno->sArquivoInconsistencia = urlencode($sArquivoInconsistencia);

  	  break;

  }

} catch (ParameterException $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
} catch (BusinessException $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
} catch (DBException $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
}

echo $oJson->encode($oRetorno);
?>