<?php
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

use ECidade\Patrimonial\Acordo\RegimeCompetencia\Model\Reconhecimento;
use ECidade\Patrimonial\Acordo\RegimeCompetencia\Repository\Reconhecimento as ReconhecimentoRepository;
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_libcontabilidade.php"));

$oJson               = JSON::create();
$oRetorno            = new stdClass();
$oRequest            = $oJson->parse(str_replace("\\","",$_POST["json"]));
$oRetorno->erro      = false;
$iIdUsuario          = db_getsession('DB_id_usuario');

$oReconhecimentoRepository = new ReconhecimentoRepository();
$instituicao               = InstituicaoRepository::getInstituicaoSessao();
try {

  db_inicio_transacao();

  switch ($oRequest->exec) {

    case 'getAcordosParaReconhecimento':

      $competencia       = null;
      $oAcordo           = null;
      $reconhecimentos   = null;
      $oCompetenciaAtual = new DBDate(date('Y-m-d', db_getsession("DB_datausu")));
      $oCompetenciaAtual = $oCompetenciaAtual->getCompetencia();
          
      if (!empty($oRequest->acordo)) {
        $oAcordo = AcordoRepository::getByCodigo($oRequest->acordo);
      }

      if (!empty($oRequest->competencia)) {

        $competencia = DBCompetencia::createFromString($oRequest->competencia);

        if ($competencia->comparar($oCompetenciaAtual, DBCompetencia::COMPARACAO_MAIOR)) {
          throw new BusinessException("Competência não pode ser maior que a atual.");
        }

        $reconhecimentos = $oReconhecimentoRepository->getAcordosParaReconhecimento($instituicao, $competencia, $oAcordo);
      } else {
        $reconhecimentos = $oReconhecimentoRepository->getReconhecimentosAbertosAteCompetencia($instituicao, $oCompetenciaAtual, $oAcordo);
      } 

      $aDadosReconhecimentos = array();

      foreach ($reconhecimentos as $codigo => $reconhecimento) {

        $oAcordo                            = $reconhecimento->getAcordo();
        $dadosReconhecimento                = new \stdClass();
        $dadosReconhecimento->codigo        = $reconhecimento->getCodigo();
        $dadosReconhecimento->acordo        = $oAcordo->getCodigo();
        $dadosReconhecimento->resumo        = $oAcordo->getResumoObjeto();
        $dadosReconhecimento->numero_acordo = $oAcordo->getNumero()."/".$oAcordo->getAno();
        $dadosReconhecimento->competencia   = $reconhecimento->getCompetencia()->getCompetencia(DBCompetencia::FORMATO_MMAAAA);
        $dadosReconhecimento->valor         = number_format($reconhecimento->getValor(), 2, ',', '.');
        $aDadosReconhecimentos[] = $dadosReconhecimento;
      }

      if (empty($aDadosReconhecimentos)) {
        throw new BusinessException("Nenhum acordo para reconhecimento encontrado.");
      }

      $oRetorno->reconhecimentos = $aDadosReconhecimentos;

      break;

    case 'processar':

      if (empty($oRequest->reconhecimentos)) {
        throw new ParameterException('Reconhecimentos nao informados');
      }

      foreach ($oRequest->reconhecimentos as $dadosReconhecimento) {

        $acordo         = AcordoRepository::getByCodigo($dadosReconhecimento->acordo);
        $reconhecimento = new Reconhecimento();
        $reconhecimento->setAcordo($acordo);
        $reconhecimento->setCompetencia(DBCompetencia::createFromString($dadosReconhecimento->competencia));
        $reconhecimento->processar(db_getsession("DB_anousu"));
      }
      $oRetorno->message = 'Reconhecimento dos regimes foi realizada com sucesso.';
      break;  
    
  }
  db_fim_transacao(false);
} catch (Exception $e) {
  db_fim_transacao(true);

  $oRetorno->erro = true;
  $oRetorno->message = $e->getMessage();
}
echo $oJson->stringify($oRetorno);