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

require_once (modification("dbforms/db_funcoes.php"));
require_once (modification("libs/JSON.php"));
require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_utils.php"));
require_once (modification("std/db_stdClass.php"));
require_once (modification("model/Avaliacao.model.php"));
require_once (modification("model/AvaliacaoGrupo.model.php"));
require_once (modification("model/AvaliacaoPergunta.model.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_libpostgres.php"));
require_once (modification("libs/db_sessoes.php"));
$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = 1;
$oRetorno->itens   = array();
try {

  switch($oParam->exec) {

    case "getDadosAvaliacao":

      $oAvaliacao                                    = new Avaliacao($oParam->iAvaliacao);
      $_SESSION["oAvaliacao_{$oParam->iAvaliacao}"]  = $oAvaliacao;

      $oRetorno->avaliacao             = new stdClass();
      $oRetorno->avaliacao->descricao  = urlencode($oAvaliacao->getDescricao());
      $oRetorno->avaliacao->observacao = urlencode($oAvaliacao->getObservacao());
      $oRetorno->avaliacao->grupos     = array();
      $oAvaliacao->setAvaliacaoGrupo($oParam->iGrupoResposta);
      $oRetorno->avaliacao->gruporespostas = $oAvaliacao->getAvaliacaoGrupo();
      $aGrupos = $oAvaliacao->getGruposPerguntas();
      foreach ($aGrupos as $oGrupoTemp) {

        $oGrupo = new stdClass();
        $oGrupo->codigo    = $oGrupoTemp->getGrupo();
        $oGrupo->descricao = urlencode($oGrupoTemp->getDescricao());

        $oRetorno->avaliacao->grupos[] = $oGrupo;
      }
      break;

    case "getPerguntasPorGrupo":

      $oAvaliacao          = $_SESSION["oAvaliacao_{$oParam->iAvaliacao}"];
      $oRetorno->perguntas = array();
      foreach ($oAvaliacao->getPerguntas($oParam->iGrupo) as $oPerguntaTemp) {

         $oPergunta                = new stdClass();
         $oPergunta->codigo        = $oPerguntaTemp->getCodigo();
         $oPergunta->descricao     = urlencode($oPerguntaTemp->getDescricao());
         $oPergunta->obrigatoria   = $oPerguntaTemp->isObrigatoria();
         $oPergunta->tipo          = $oPerguntaTemp->getTipo();
         $oPergunta->respostas     = array();
          foreach ($oPerguntaTemp->getRespostas() as $oOpcaoResposta) {

            $oTmpOpcaoResposta                    = clone($oOpcaoResposta);
            $oTmpOpcaoResposta->descricaoresposta = urlencode($oOpcaoResposta->descricaoresposta);
            $oPergunta->respostas[]               = $oTmpOpcaoResposta;
          }
         $oPergunta->identificador = $oPerguntaTemp->getIdentificador();
         array_push($oRetorno->perguntas, $oPergunta);
      }
      break;

    case "salvarRepostas":

      $oAvaliacao = $_SESSION["oAvaliacao_{$oParam->iAvaliacao}"];
      $aPerguntas = $oAvaliacao->getPerguntas();

      foreach ($oAvaliacao->getPerguntas() as $oPergunta) {

        foreach ($oParam->perguntas as $oResposta) {

          if ($oPergunta->getCodigo() == $oResposta->codigo) {
            $oPergunta->setResposta($oResposta->respostas);
          }
        }
      }
      $_SESSION["oAvaliacao_{$oParam->iAvaliacao}"] = $oAvaliacao;
      break;

    case "salvarAvaliacao" :

      $oAvaliacao = $_SESSION["oAvaliacao_{$oParam->iAvaliacao}"];

      try {

        db_inicio_transacao();
        foreach ($oAvaliacao->getPerguntas() as $oPergunta) {
          $oPergunta->salvarRespostas();
        }
        db_fim_transacao(false);
      } catch (Exception $eErro) {

        db_fim_transacao(true);
        $oRetorno->message = urlencode($eErro->getMessage());
        $oRetorno->status  = 2;
      }
      break;
  }
} catch (Exception $e) {

  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($e->getMessage());
}
echo $oJson->encode($oRetorno);