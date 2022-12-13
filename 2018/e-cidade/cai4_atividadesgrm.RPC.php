<?php

/*
*     E-cidade Software Publico para Gestao Municipal
*  Copyright (C) 2016  DBselller Servicos de Informatica
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

use ECidade\Financeiro\Tesouraria\Repository\Receita as ReceitaRepository;
use ECidade\Tributario\Grm\Repository\Recibo as GuiaRepository;
use ECidade\Tributario\Grm\Repository\UnidadeGestora as UnidadeGestoraRepository;
use ECidade\Tributario\Grm\Repository\TipoRecolhimento as tipoRecolhimentoRepository;
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("libs/JSON.php");


$oParam                 = \JSON::create()->parse(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = true;
$oRetorno->sMessage     = '';
$oRetorno->erro         = false;

$oUnidadeGestoraRepository   = new UnidadeGestoraRepository();
$oTipoRecolhimentoRepository = new tipoRecolhimentoRepository();
$oReceitaRepository          = new ReceitaRepository();
$oGuiaRepository             = new GuiaRepository();
try {

  db_inicio_transacao();
  switch ($oParam->exec) {

  case 'getGuias':

    $sWhere = getFiltro($oParam);
    $oDepartamento = DBDepartamentoRepository::getDBDepartamentoByCodigo(db_getsession("DB_coddepto"));
    $oUnidadeGestora = $oUnidadeGestoraRepository->getById($oParam->unidade_gestora);
    $aGuias = $oGuiaRepository->getGuiasParaMovimentacaoNoDepartamento($oUnidadeGestora, $oDepartamento, true, $sWhere);
    $oRetorno->guias = array();

    foreach ($aGuias as $oGuia) {

      $oWorkFlow = $oGuia->getTipoRecolhimento()->getWorkflow();
      $oAtividadeInicial = $oWorkFlow->getAtividadeNaOrdem(1);
      $oDadosGuia = new \stdClass();
      $oDadosGuia->guia = $oGuia->getCodigo();
      $oDadosGuia->processo = '';
      $oDadosGuia->cgm = $oGuia->getCgm()->getCodigo() . ' ' . $oGuia->getCgm()->getNome();
      $oDadosGuia->recolhimento = $oGuia->getTipoRecolhimento()->getCodigoRecolhimento() . " - " . $oGuia->getTipoRecolhimento()->getNome();
      $oDadosGuia->valor_total = $oGuia->getValorTotal();
      $oDadosGuia->atividade = '';
      $oDadosGuia->ordem_atividade = 1;
      $oDadosGuia->atributos = '';
      $oDadosGuia->workflow = $oWorkFlow->getCodigo();
      $oProcesso = $oGuia->getProcesso();

      if (!empty($oProcesso)) {

        $iOrdemAtividade = $oProcesso->getPosicaoAtualAndamentoPadrao();
        $oDadosGuia->ordem_atividade = $iOrdemAtividade == 0 ? 1 : $iOrdemAtividade;
        $oDadosGuia->processo = $oProcesso->getCodProcesso();
      }
      $oAtividade = $oWorkFlow->getAtividadeNaOrdem($oDadosGuia->ordem_atividade);
      if (!empty($oAtividade)) {

        $oDadosGuia->atividade = $oAtividade->getNome();
        $oDadosGuia->atributos = $oAtividade->getGrupoAtributos();
      }
      $oRetorno->guias[] = $oDadosGuia;
    }

    break;

    case 'salvarAtividade':
  
      $oGuia = $oGuiaRepository->getById($oParam->guia);
      if (empty($oGuia)) {
        throw new BusinessException("Guia {$oParam->guia} não encontrada");
      }
  
      $oProcesso = $oGuia->getProcesso();
      $oWorkFlow = $oGuia->getTipoRecolhimento()->getWorkflow();
      $atividade = $oWorkFlow->getAtividadeNaOrdem($oParam->ordem_atividade);
      $oUsuario = UsuarioSistemaRepository::getPorCodigo(db_getsession("DB_id_usuario"));
      if (empty($oProcesso)) {
  
        $oProcesso = new processoProtocolo();
        $oProcesso->setCgm($oGuia->getCgm()->getCodigo());
        $oProcesso->setAnoProcesso(db_getsession("DB_anousu"));
        $oProcesso->setDataProcesso(date('Y-m-d', db_getsession("DB_datausu")));
        $oProcesso->setInterno('false');
        $oProcesso->setDespacho('');
        $oProcesso->setObservacao('Processo de andamento da de recolhimento ' . $oGuia->getCodigo());
        $oProcesso->setPublico('false');
        $oProcesso->setRequerente($oGuia->getCgm()->getNome());
        $oProcesso->setTipoProcesso($oWorkFlow->getTipoProcesso());
        $oProcesso->salvar();
        $oGuia->setProcesso($oProcesso);
        $oGuiaRepository->persist($oGuia);
        $oDepartamento = $atividade->getDepartamento();
        $iCodigoTransferencia = $oProcesso->transferirPorAndamentoPadrao($oUsuario->getCodigo(), $oDepartamento->getCodigo());
        $iProximoDepto = $oProcesso->getProximoDeptoAndamentoPadrao();
        $oProcesso->receber($iCodigoTransferencia, $iProximoDepto, $oUsuario->getCodigo(), 'Processo iniciado');
  
      }
  
      $oMovimentacao = new \ECidade\Tributario\Grm\GuiaMovimentacao();
      $oMovimentacao->setAtividade($atividade);
      $oMovimentacao->setData(new DBDate(date('Y-m-d', db_getsession('DB_datausu'))));
      $oMovimentacao->setUsuario($oUsuario);
      $oMovimentacao->setConcluido($oParam->concluido);
      $oMovimentacao->setGrupoAtributos($oParam->atributos);
      $oMovimentacao->setObservacao($oParam->observacao);
      $oMovimentacao->setGuia($oGuia);
      $oMovimentacao->setProcesso($oProcesso);
      $oMovimentacao->movimentar();
      $oRetorno->sMessage = 'Movimentação realizada com sucesso.';
      break;
  
    case 'consultarGuiasProcesso':
  
      $sWhere          = getFiltro($oParam);      
      $oUnidadeGestora = $oUnidadeGestoraRepository->getById($oParam->unidade_gestora);
      $aGuias          = $oGuiaRepository->getRecibosPagosDaUnidadeGestora($oUnidadeGestora, $sWhere);
      $oRetorno->guias = array();
  
      foreach ($aGuias as $oGuia) {
  
        $oProcesso = $oGuia->getProcesso();  
        if (empty($oProcesso)) {
          continue;
        }
        $oWorkFlow = $oGuia->getTipoRecolhimento()->getWorkflow();
        $oAtividadeInicial = $oWorkFlow->getAtividadeNaOrdem(1);
        $oDadosGuia               = new \stdClass();
        $oDadosGuia->guia         = $oGuia->getCodigo();
        $oDadosGuia->processo     = '';
        $oDadosGuia->cgm          = $oGuia->getCgm()->getCodigo() . ' ' . $oGuia->getCgm()->getNome();
        $oDadosGuia->recolhimento = $oGuia->getTipoRecolhimento()->getCodigoRecolhimento() . " - " . $oGuia->getTipoRecolhimento()->getNome();
        $oDadosGuia->valor_total  = $oGuia->getValorTotal();        
        if (!empty($oProcesso)) {  
          $oDadosGuia->processo = $oProcesso->getCodProcesso();
        }        
        $oRetorno->guias[] = $oDadosGuia;
      }
  
    break;

  }
  db_fim_transacao(false);
} catch (Exception $eErro) {


  db_fim_transacao(true);
  $oRetorno->erro     = true;
  $oRetorno->iStatus  = false;
  $oRetorno->sMessage = $eErro->getMessage();
}


function getFiltro($oParam) {
  
  $aWhere = array("k172_workflow is not null");
  if (!empty($oParam->tipo_recolhimento)) {
    $aWhere[] = 'k174_tiporecolhimento = '.(int)$oParam->tipo_recolhimento;
  }

  if (!empty($oParam->especie_ingresso)) {
    $aWhere[] = 'k172_especieingresso = '.(int)$oParam->especie_ingresso;
  }
  $sFiltroData = '';
  if (!empty($oParam->data_inicial)) {

    $oDataInicial = new DBDate($oParam->data_inicial);
    $aWhere[] = "k00_dtpaga >= '".$oDataInicial->getDate()."'";

  }

  if (!empty($oParam->data_final)) {
    $oDataFinal = new DBDate($oParam->data_final);
    $aWhere[]   = "k00_dtpaga <= '".$oDataFinal->getDate()."'";

  }

  return $sWhere = implode(" and ", $aWhere);

}
echo \JSON::create()->stringify($oRetorno);