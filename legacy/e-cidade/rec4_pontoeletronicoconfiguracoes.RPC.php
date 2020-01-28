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

use ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\Model\Justificativa as JustificativaModel;
use ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\Repository\Justificativa as JustificativaRepository;

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("libs/JSON.php");

$oPost       = db_utils::postMemory($_REQUEST);
$oPost->json = str_replace("\\","",$oPost->json);
$oParametro  = JSON::create()->parse($oPost->json);
$oRetorno    = (object)array( 'erro' => false, 'mensagem'=> '');

try {

  db_inicio_transacao();

  switch ($oParametro->exec) {

    case 'salvarConfiguracoesGerais':

      $oDaoConfiguracoesGerais  = new cl_pontoeletronicoconfiguracoesgerais;
      $iCodigoInstituicao       = db_getsession('DB_instit');
      $sSqlConfiguracoesGerais  = $oDaoConfiguracoesGerais->sql_query_file(null, "*", null, "rh200_instituicao = {$iCodigoInstituicao}");
      $rsSqlConfiguracoesGerais = db_query($sSqlConfiguracoesGerais);

      if(!$rsSqlConfiguracoesGerais) {
        throw new DBException("Ocorreu um erro ao buscar as configurações gerais para a instituição.");
      }

      $sAcao = 'incluir';
      $oDaoConfiguracoesGerais->rh200_sequencial  = null;

      if(pg_num_rows($rsSqlConfiguracoesGerais) > 0) {

        $sAcao = db_utils::makeFromRecord($rsSqlConfiguracoesGerais, function ($oRetorno) use ($oDaoConfiguracoesGerais, $sAcao) {
          $oDaoConfiguracoesGerais->rh200_sequencial = $oRetorno->rh200_sequencial;
          return 'alterar';
        });
      }

      $aTiposConfigurados = (array)$oParametro;
      unset($aTiposConfigurados['exec']);
      $aCodigosRepetidos = array_count_values($aTiposConfigurados);

      foreach ($aCodigosRepetidos as $tipo => $codigo) {
        if(!empty($tipo)) {
          if($codigo > 1) {
            throw new BusinessException("O tipo de assentamento do sequencial: {$tipo} já foi informado.\nSelecione outro tipo.");
          }
        }
      }

      $oDaoConfiguracoesGerais->rh200_tipoasse_extra50diurna    = $oParametro->rh200_tipoasse_extra50diurna;
      $oDaoConfiguracoesGerais->rh200_tipoasse_extra75diurna    = $oParametro->rh200_tipoasse_extra75diurna;
      $oDaoConfiguracoesGerais->rh200_tipoasse_extra100diurna   = $oParametro->rh200_tipoasse_extra100diurna;
      $oDaoConfiguracoesGerais->rh200_tipoasse_extra50noturna   = $oParametro->rh200_tipoasse_extra50noturna;
      $oDaoConfiguracoesGerais->rh200_tipoasse_extra75noturna   = $oParametro->rh200_tipoasse_extra75noturna;
      $oDaoConfiguracoesGerais->rh200_tipoasse_extra100noturna  = $oParametro->rh200_tipoasse_extra100noturna;
      $oDaoConfiguracoesGerais->rh200_tipoasse_adicionalnoturno = $oParametro->rh200_tipoasse_adicionalnoturno;
      $oDaoConfiguracoesGerais->rh200_tipoasse_falta            = $oParametro->rh200_tipoasse_falta;
      $oDaoConfiguracoesGerais->rh200_tipoasse_faltas_dsr       = $oParametro->rh200_tipoasse_faltas_dsr;
      $oDaoConfiguracoesGerais->rh200_instituicao               = $iCodigoInstituicao;
      $oDaoConfiguracoesGerais->rh200_autorizahoraextra         = $oParametro->rh200_autorizahoraextra;

      $oDaoConfiguracoesGerais->{$sAcao}($oDaoConfiguracoesGerais->rh200_sequencial);

      if($oDaoConfiguracoesGerais->erro_status == '0') {
        throw new DBException($oDaoConfiguracoesGerais->erro_msg);
      }


      $oRetorno->mensagem = 'Configurações salvas com sucesso.';

      break;

    case 'salvarConfiguracoesLotacao':

      $oDaoConfiguracoesLotacao = new cl_pontoeletronicoconfiguracoeslotacao;
      $oDaoConfiguracoesLotacao->rh195_sequencial = null;
      $sAcao = 'incluir';

      $sSqlConfiguracoesLotacao  = $oDaoConfiguracoesLotacao->sql_query_file(null, "*", null, "rh195_lotacao = {$oParametro->rh195_lotacao}");
      $rsConfiguracoesLotacao    = db_query($sSqlConfiguracoesLotacao);

      if(!$rsConfiguracoesLotacao) {
        throw new DBException("Ocorreu um erro ao buscar as configurações gerais para a instituição.");
      }

      if(pg_num_rows($rsConfiguracoesLotacao) > 0) {

        $sAcao = db_utils::makeFromRecord($rsConfiguracoesLotacao, function ($oRetorno) use ($oDaoConfiguracoesLotacao, $sAcao) {
          $oDaoConfiguracoesLotacao->rh195_sequencial = $oRetorno->rh195_sequencial;
          return 'alterar';
        });
      }

      $oDaoConfiguracoesLotacao->rh195_lotacao         = $oParametro->rh195_lotacao;
      $oDaoConfiguracoesLotacao->rh195_tolerancia      = $oParametro->rh195_tolerancia;
      $oDaoConfiguracoesLotacao->rh195_hora_extra_50   = $oParametro->rh195_hora_extra_50;
      $oDaoConfiguracoesLotacao->rh195_hora_extra_75   = $oParametro->rh195_hora_extra_75;
      $oDaoConfiguracoesLotacao->rh195_hora_extra_100  = $oParametro->rh195_hora_extra_100;
      $oDaoConfiguracoesLotacao->rh195_supervisor      = $oParametro->rh195_supervisor;

      $oDaoConfiguracoesLotacao->{$sAcao}($oDaoConfiguracoesLotacao->rh195_sequencial);

      if($oDaoConfiguracoesLotacao->erro_status == '0') {
        throw new DBException($oDaoConfiguracoesLotacao->erro_msg);
      }

      $oRetorno->mensagem = 'Configurações salvas com sucesso.';

      break;

    case 'getConfiguracoesLotacaoPorLotacao':

      $oRetorno->configuracoes = (object)array(
        'rh195_tolerancia'     => null,
        'rh195_hora_extra_50'  => null,
        'rh195_hora_extra_75'  => null,
        'rh195_hora_extra_100' => null,
        'rh195_supervisor'     => null,
        'nome_supervisor'      => null
      );

      if(!empty($oParametro->iCodigoLotacao)) {

        $oDaoConfiguracoesLotacao = new cl_pontoeletronicoconfiguracoeslotacao;
        $sSqlConfiguracoesLotacao = $oDaoConfiguracoesLotacao->sql_query_join_cgm(
          null,
          "pontoeletronicoconfiguracoeslotacao.*, z01_nome as nome_supervisor",
          null,
          "rh195_lotacao = {$oParametro->iCodigoLotacao}"
        );
        $rsConfiguracoesLotacao   = db_query($sSqlConfiguracoesLotacao);

        if(!$rsConfiguracoesLotacao) {
          throw new DBException("Ocorreu um erro ao buscar as configurações de lotação.");
        }

        if(pg_num_rows($rsConfiguracoesLotacao) > 0) {

          $oRetorno->configuracoes = db_utils::makeFromRecord($rsConfiguracoesLotacao, function ($oResponse) {
            return $oResponse;
          });
        }
      }
      break;

    case 'salvarJustificativa':

      if(!isset($oParametro->iCodigo)) {
        throw new ParameterException('Código não enviado.');
      }

      if(empty($oParametro->sDescricao)) {
        throw new ParameterException('Descrição não informada.');
      }

      if(empty($oParametro->sAbreviacao)) {
        throw new ParameterException('Abreviação não informada.');
      }

      if(empty($oParametro->tiposAssentamentos)) {
        throw new ParameterException("Selecione ao menos um tipo de assentamento.");
      }

      $oJustificativaModel = new JustificativaModel();
      $oJustificativaModel->setCodigo($oParametro->iCodigo);
      $oJustificativaModel->setDescricao($oParametro->sDescricao);
      $oJustificativaModel->setAbreviacao($oParametro->sAbreviacao);

      $oJustificativaRepository = new JustificativaRepository();
      $oJustificativaModel      = $oJustificativaRepository->add($oJustificativaModel, InstituicaoRepository::getInstituicaoSessao());

      $oJustificativaRepository->removeTiposAssentamento($oJustificativaModel);

      foreach ($oParametro->tiposAssentamentos as $codigoTipoAssentamento) {

        $justificativaConfigurada = $oJustificativaRepository->getJustificativaPorTipoAssentamento($codigoTipoAssentamento);

        if($justificativaConfigurada !== null) {

          $tipoAssentamentoConfigurado = TipoAssentamentoRepository::getInstanciaPorCodigo($codigoTipoAssentamento);

          $sMensagemValidacao  = "Já existe uma justificativa vinculada ao tipo de assentamento:\n";
          $sMensagemValidacao .= "\nJustificativa: {$justificativaConfigurada->getDescricao()}";
          $sMensagemValidacao .= "\nTipo de Assentamento: {$tipoAssentamentoConfigurado->getDescricao()}";
          $sMensagemValidacao .= "\n\nSelecione outro tipo de assentamento.";

          throw new BusinessException($sMensagemValidacao);
        }

        $oJustificativaRepository->addTipoAssentamento($oJustificativaModel, $codigoTipoAssentamento);
      }

      $oRetorno->mensagem = 'Justificativa salva com sucesso.';
      $oRetorno->iCodigo  = $oJustificativaModel->getCodigo();

      break;

    case 'excluirJustificativa':

      if(empty($oParametro->iCodigo)) {
        throw new ParameterException('Código não informado.');
      }

      $oJustificativaModel = new JustificativaModel();
      $oJustificativaModel->setCodigo($oParametro->iCodigo);

      $oJustificativaRepository = new JustificativaRepository();
      $oJustificativaRepository->removeAll($oJustificativaModel);

      $oRetorno->mensagem = 'Justificativa excluída com sucesso.';

      break;

    case 'buscarTiposAssentamentosConfigurados':

      if(empty($oParametro->codigoJustificativa)) {
        throw new ParameterException("Informe o código da Justificativa.");
      }

      $oRetorno->tiposAssentamentos = array();

      $oJustificativaModel = new JustificativaModel();
      $oJustificativaModel->setCodigo($oParametro->codigoJustificativa);

      $oJustificativaRepository = new JustificativaRepository();

      foreach($oJustificativaRepository->getTiposAssentamentoPorJustificativa($oJustificativaModel) as $oTipoAssentamento) {
        $oRetorno->tiposAssentamentos[] = $oTipoAssentamento->getSequencial();
      }

      break;

    case 'buscarTiposAssentamentos':

      $oRetorno->tiposAssentamentos = array();
      $aTipoAssentamentos           = TipoAssentamentoRepository::getInstanciasPorNatureza(Assentamento::NATUREZA_JUSTIFICATIVA);

      foreach($aTipoAssentamentos as $oTipoAssentamento) {

        $oDadosRetorno              = new stdClass();
        $oDadosRetorno->sequencial  = $oTipoAssentamento->getSequencial();
        $oDadosRetorno->codigo      = $oTipoAssentamento->getCodigo();
        $oDadosRetorno->descricao   = $oTipoAssentamento->getDescricao();

        $oRetorno->tiposAssentamentos[] = $oDadosRetorno;
      }

      break;
  }

  db_fim_transacao(false);

} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->erro     = true;
  $oRetorno->mensagem = $eErro->getMessage();
}

echo JSON::create()->stringify($oRetorno);