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

require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oJson                           = new services_json(0,true);
$oParam                          = $oJson ->decode(str_replace("\\","",$_POST["json"]));
$oRetorno                        = new stdClass();
$oRetorno->status                = true;
$oRetorno->erro                  = false;
$oRetorno->message               = '';
$oRetorno->estrutura_suplementar = !!DBPessoal::verificarUtilizacaoEstruturaSuplementar();

define('MENSAGENS', 'recursoshumanos.pessoal.pes4_assentamento.');

try {

  db_inicio_transacao();//Begin
  switch ($oParam->exec) {

    case "buscarServidoresAssentamento":

      $oRetorno->erro      = false;
      $oRetorno->servidores= getServidoresEAssentamentoPorTipoAssentamento($oParam->iTipoAssentamento);
      break;

    case "buscarAssentamentoSubstituicao":

      $oRetorno->erro      = true;
      $oVarErros           = new stdClass();
      $oRetorno->message   = urlencode(_M(MENSAGENS ."erro_buscar_assentamento_substituicao", $oVarErros));

      break;

    case "salvarAssentamentoSubstituicao":

      $oRetorno->erro      = true;
      $oVarErros           = new stdClass();
      $oRetorno->message   = urlencode(_M(MENSAGENS ."erro_salvar_assentamento_substituicao", $oVarErros));

      break;

    case "excluirAssentamentoSubstituicao":

      $oRetorno->erro      = true;
      $oVarErros           = new stdClass();
      $oRetorno->message   = urlencode(_M(MENSAGENS ."erro_excluir_assentamento_substituicao", $oVarErros));

      break;

    case "buscarServidoresAssentamentoSubstituicao":

      $oRetorno->aResposta = array();
      $oRetorno->erro      = true;
      $oVarErros           = new stdClass();
      $aServidores         = array();
      $oRetorno->message   = urlencode(_M(MENSAGENS ."erro_buscar_servidores_assentamento", $oVarErros));
      $aServidores         = AssentamentoRepository::getServidoresAssentamentoSubstituicao();

      if(count($aServidores)> 0){
        $oRetorno->erro      = false;
        $oRetorno->message   = '';

        foreach ($aServidores as $oServidor) {

          $oStdServidor                  = new stdClass();
          $oStdServidor->sMatricula      = $oServidor->getMatricula();
          $oStdServidor->sNome           = $oServidor->getCgm()->getNome();
          $oStdServidor->sAnoCompetencia = $oServidor->getAnoCompetencia();
          $oStdServidor->sMesCompetencia = $oServidor->getMesCompetencia();
          $oStdServidor->nTotalDiasPagar = 0;
          $oStdServidor->nTotalDiasPagos = 0;

          $aAssentamentos = $oServidor->getAssentamentosSubstituicao();

          if(count($aAssentamentos) > 0){

            foreach ($aAssentamentos as $oAssentamento) {

              if($oAssentamento->hasLote() === false){
                $oStdServidor->nTotalDiasPagar += $oAssentamento->getDias();
              } else {
                $oStdServidor->nTotalDiasPagos += $oAssentamento->getDias();
              }
            }
          }

          $oRetorno->aResposta[] = $oStdServidor;
        }
      } else {
        $oRetorno->message   = urlencode(_M(MENSAGENS ."servidores_assentamento_vazio"));
      }
      break;

    case "buscarAssentamentosServidor":

      $oRetorno->aItems    = array();
      $oRetorno->erro      = false;
      $aServidores         = array();

      $aAssentamentos   = AssentamentoRepository::getAssentamentosSubstituicaoServidor($oParam->iMatricula);
      $oRetorno->aItems = $aAssentamentos;

      if (count($aAssentamentos) == 0) {

        $oRetorno->erro      = false;
        $oRetorno->message   = urlencode(_M(MENSAGENS ."servidor_assentamentos_vazio"));
      }

      break;

    case "lancarAssentamentosSubstituicaoPonto":

      $oRetorno->erro      = true;

      $iAnousu    = $oParam->iAnousu;
      $iMesusu    = $oParam->iMesusu;
      $iFolha     = $oParam->iFolha;
      $iMatricula = $oParam->iMatricula;
      $aRegistros = $oParam->aRegistros;

      $oCompetencia       = new DBCompetencia($iAnousu, $iMesusu);
      $oInstituicao       = InstituicaoRepository::getInstituicaoByCodigo(db_getsession('DB_instit'));
      $oServidor          = ServidorRepository::getInstanciaByCodigo($iMatricula, $iAnousu, $iMesusu);
      $aLotesRegistros    = LoteRegistrosPontoRepository::getLotesAssentamentosByMatricula($iMatricula, $oCompetencia, Assentamento::NATUREZA_SUBSTITUICAO);
      $lNovoLoteRegistros = true;
      $oFolhaPagamento    = FolhaPagamentoFactory::construirPeloTipo($iFolha);

      if ( count($aRegistros) == 0 ) {
        throw new BusinessException('Ao menos um registro deve ser selecionado.');
      }

      /**
       * Busca lote existente em folha aberta na competencia, caso exista
       * retorna o lote para adicionar novos assentamentos aos registros
       */
      if(count($aLotesRegistros) > 0) {

        foreach ($aLotesRegistros as $oLote) {

          $oFolha = $oLote->getFolhaPagamento();

          if($oFolha->getTipoFolha() == $iFolha && $oFolha->isAberto()) {

            $lNovoLoteRegistros = false;
            $oLote->cancelarConfirmacao();
            $oLoteRegistroPontoCriado = $oLote;
          }
        }
      }

      if ( count($aRegistros) > 0) {

        /**
         * Verifica se o lote é novo
         */
        if($lNovoLoteRegistros) {

          $oLoteRegistroPonto = new LoteRegistrosPonto();
          $oLoteRegistroPonto->setCompetencia($oCompetencia);
          $oLoteRegistroPonto->setInstituicao($oInstituicao);
          $oLoteRegistroPonto->setDescricao('Lote Substituição Ponto');
          $oLoteRegistroPonto->setSituacao(LoteRegistrosPonto::ABERTO);
          $oLoteRegistroPonto->setUsuario(UsuarioSistemaRepository::getPorCodigo(db_getsession('DB_id_usuario')));
          $oLoteRegistroPonto->setTipoPonto($iFolha);

          $oLoteRegistroPontoCriado  = LoteRegistrosPontoRepository::persist($oLoteRegistroPonto);
        }

        /**
         * Carrega as rubricas para o lançamento da substituição
         */
        $oParametrosPessoal        = ParametrosPessoalRepository::getParametros($oCompetencia, $oInstituicao);
        $oRubricaExercicioAnterior = $oParametrosPessoal->getRubricaExercicioAnteriorSubstituicao();
        $oRubricaExercicioAtual    = $oParametrosPessoal->getRubricaExercicioAtualSubstituicao();


        $iQuantidadeTotalAnterior     = 0;
        $nValorTotalAnterior          = 0;
        $iQuantidadeTotalAtual        = 0;
        $nValorTotalAtual             = 0;

        foreach ($aRegistros as $aRegistro) {

          $iSequencialAssentamento = $aRegistro[1];
          $sDataInicio             = $aRegistro[2];
          $sDataFim                = $aRegistro[3];
          $iQuantidade             = $aRegistro[4];
          $nValor                  = str_replace('.', '', $aRegistro[5]);
          $nValor                  = str_replace(',', '.', $nValor);

          $aAnoLancamento          = explode('/', $sDataFim);
          $iAnoLancamento          = $aAnoLancamento[2];

          /**
           * Caso o ano do lançamento seja do exercicio anterior, definimos para o registro
           * do ponto a Rubrica configurada para o exercicio anterior, caso contrário
           * definimos a rubrica configurada para o exercicio atual.
           */
          if ($iAnoLancamento != $iAnousu) {

            $iQuantidadeTotalAnterior    += $iQuantidade;
            $nValorTotalAnterior         += $nValor;
          } else {

            $iQuantidadeTotalAtual       += $iQuantidade;
            $nValorTotalAtual            += $nValor;
          }

          /**
           * salvamos na tabela assentaloteregistroponto
           * @todo  Organizar isso
           */
          $oDaoAssentaLoteRegistroPonto = new cl_assentaloteregistroponto();
          $oDaoAssentaLoteRegistroPonto->rh160_sequencial        = null;
          $oDaoAssentaLoteRegistroPonto->rh160_assentamento      = $iSequencialAssentamento;
          $oDaoAssentaLoteRegistroPonto->rh160_loteregistroponto = $oLoteRegistroPontoCriado->getSequencial();
          $oDaoAssentaLoteRegistroPonto->incluir(null);
        }

        if ( ($nValorTotalAnterior+$nValorTotalAtual) == 0 ) {
          throw new BusinessException("Não é possivel lançar registros com valor zerado.");
        }

        if($lNovoLoteRegistros) { //Se lote novo adiciona novos registros

          if ( $iQuantidadeTotalAtual > 0 && $nValorTotalAtual > 0 ) {

            $oRegistroPonto = new RegistroLoteRegistrosPonto();
            $oRegistroPonto->setCodigoLote($oLoteRegistroPontoCriado->getSequencial());
            $oRegistroPonto->setRubrica($oRubricaExercicioAtual);
            $oRegistroPonto->setServidor($oServidor);
            $oRegistroPonto->setQuantidade($iQuantidadeTotalAtual);
            $oRegistroPonto->setValor($nValorTotalAtual);
            $oRegistroPonto->setInstituicao($oInstituicao);
            $oRegistroPonto->setFolhaPagamento($oFolhaPagamento);
            $oRegistroPonto->setCompetencia('');
            RegistroLoteRegistrosPontoRepository::persist($oRegistroPonto);
          }

          if ( $iQuantidadeTotalAnterior > 0 && $nValorTotalAnterior > 0 ) {

            $oRegistroPonto = new RegistroLoteRegistrosPonto();
            $oRegistroPonto->setCodigoLote($oLoteRegistroPontoCriado->getSequencial());
            $oRegistroPonto->setRubrica($oRubricaExercicioAnterior);
            $oRegistroPonto->setServidor($oServidor);
            $oRegistroPonto->setQuantidade($iQuantidadeTotalAnterior);
            $oRegistroPonto->setValor($nValorTotalAnterior);
            $oRegistroPonto->setInstituicao($oInstituicao);
            $oRegistroPonto->setFolhaPagamento($oFolhaPagamento);
            $oRegistroPonto->setCompetencia('');
            RegistroLoteRegistrosPontoRepository::persist($oRegistroPonto);
          }

          if(DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {

            $oDaoLoteRegistroPontoRhFolhaPagamento = new cl_loteregistropontorhfolhapagamento();
            $oDaoLoteRegistroPontoRhFolhaPagamento->rh162_sequencial        = null;
            $oDaoLoteRegistroPontoRhFolhaPagamento->rh162_loteregistroponto = $oLoteRegistroPontoCriado->getSequencial();
            $oDaoLoteRegistroPontoRhFolhaPagamento->rh162_rhfolhapagamento  = $oFolhaPagamento->getFolhaAberta()->getSequencial();
            $oDaoLoteRegistroPontoRhFolhaPagamento->incluir(null);
          }

        } else { //Se lote existente deve buscar os registros para adicionar os novos assentamentos ao existente

          $aRegistrosLoteExistente = $oLoteRegistroPontoCriado->getRegistroPonto();

          if(count($aRegistrosLoteExistente) < 1) {
            throw new BusinessException(_M(MENSAGENS. "erro_buscar_assentamento_substituicao"));
          }

          foreach ($aRegistrosLoteExistente as $oRegistroLote) {

            if($oRegistroLote->getRubrica()->getCodigo() == $oRubricaExercicioAnterior->getCodigo()) {

              $oRegistroLote->setValor($oRegistroLote->getValor() + $nValorTotalAnterior);
              $oRegistroLote->setQuantidade($oRegistroLote->getQuantidade() + $iQuantidadeTotalAnterior);
            }

            if($oRegistroLote->getRubrica()->getCodigo() == $oRubricaExercicioAtual->getCodigo()) {

              $oRegistroLote->setValor($oRegistroLote->getValor() + $nValorTotalAtual);
              $oRegistroLote->setQuantidade($oRegistroLote->getQuantidade() + $iQuantidadeTotalAtual);
            }

            RegistroLoteRegistrosPontoRepository::persist($oRegistroLote);
          }
        }

        $oLoteCriado = LoteRegistrosPontoRepository::getInstanceByCodigo($oLoteRegistroPontoCriado->getSequencial());
        $oLoteCriado->confirmarLote();
        $oRetorno->message = urlencode("Assentamento Lançado ao Ponto com sucesso.");
      }

      $oRetorno->erro    = false;
      $aAssentamentos    = AssentamentoRepository::getAssentamentosSubstituicaoServidor($iMatricula);
      $oRetorno->aItems  = $aAssentamentos;

      break;

    case "cancelarLancamentoAssentamentosSubstituicaoPonto":

      $iAnousu        = $oParam->iAnousu;
      $iMesusu        = $oParam->iMesusu;
      $iMatricula     = $oParam->iMatricula;
      $aRegistros     = $oParam->aRegistros;
      $oCompetencia   = new DBCompetencia($iAnousu, $iMesusu);

      foreach ($aRegistros as $aRegistro) {

        $oAssentamento = AssentamentoFactory::getByCodigo($aRegistro[1]);
        $oLoteRegistro = $oAssentamento->getLote();

        if (empty($oLoteRegistro)) {
          continue;
        }

        /**
         * Cancela Lote
         */
        if ( $oLoteRegistro->cancelarConfirmacao() ) {

          $oRetorno->erro      = false;
          $oRetorno->message   = urlencode(_M(MENSAGENS ."sucesso_cancelar_confirmacao_lote"));
        }

        /**
         * Remove o vinculo com a tabela loteregistropontorhfolhapagamento quando utilizando estrutura suplementar
         */
        if(DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {

          $oDaoLoteRegistroPontoRhFolhapagamento = new cl_loteregistropontorhfolhapagamento();
          $oDaoLoteRegistroPontoRhFolhapagamento->excluir(null, "rh162_loteregistroponto = {$oLoteRegistro->getSequencial()}");

          if ( $oDaoLoteRegistroPontoRhFolhapagamento->erro_status == "0" ) {
            throw new DBException($oDaoLoteRegistroPontoRhFolhapagamento->erro_msg); //@todo externar isso
          }
        }

        /**
         * Remove o vinculo do lote com o assentamento.
         */
        $oDaoAssentaLoteRegistroPonto = new cl_assentaloteregistroponto();
        $oDaoAssentaLoteRegistroPonto->excluir(null, "rh160_loteregistroponto = {$oLoteRegistro->getSequencial()}");

        if ( $oDaoAssentaLoteRegistroPonto->erro_status == "0" ) {
          throw new DBException($oDaoAssentaLoteRegistroPonto->erro_msg); //@todo externar isso
        }

        /**
         * Remover Lote
         */
        if ( LoteRegistrosPontoRepository::remover($oLoteRegistro) === true ) {

          $oRetorno->erro    = false;
          $oRetorno->message = urlencode(_M(MENSAGENS ."sucesso_excluir_lote"));
        }
      }

      $oRetorno->erro    = false;
      $oRetorno->message = urlencode(_M(MENSAGENS ."sucesso_cancelar_lancamento_assentamento"));
      $aAssentamentos    = AssentamentoRepository::getAssentamentosSubstituicaoServidor($iMatricula);
      $oRetorno->aItems  = $aAssentamentos;

      break;

    case "lancarAssentamentosPonto":

      $oRetorno->erro      = true;

      $iTipoFolha          = $oParam->iTipoFolha;
      $aRegistros          = $oParam->aRegistros;

      if ( count($aRegistros) == 0 ) {
        throw new BusinessException('Ao menos um registro deve ser selecionado.');
      }

      $oTipoAssentamento = TipoAssentamentoRepository::getInstanciaPorCodigo($oParam->iTipoAssentamento);

      $sComportamento = LoteRegistrosPonto::SOMAR_RUBRICA;

      if(!empty($oParam->sComportamento)) {
        if($oParam->sComportamento == 'T') {
          $sComportamento = LoteRegistrosPonto::SUBSTITUIR_RUBRICA;
        }
      }

      $sMensagem = _M(MENSAGENS ."sucesso_processar_lancamento_assentamento");

      foreach ($aRegistros as $oRegistro) {

        $oAssentamento     = AssentamentoFactory::getByCodigo($oRegistro->iSequencialAssentamento);

        if ($oAssentamento->getLote() && $oRegistro->sOpcao == 'incluir') {

          $oRetorno->servidores = getServidoresEAssentamentoPorTipoAssentamento($oAssentamento->getTipoAssentamento());
          throw new BusinessException("Existem itens já lançados no ponto para esta seleção, por favor tente novamente.");
        }

        $oServidor         = ServidorRepository::getInstanciaByCodigo(
          $oRegistro->iMatricula,
          DBPessoal::getCompetenciaFolha()->getAno(),
          DBPessoal::getCompetenciaFolha()->getMes()
        );

        if($oRegistro->sOpcao == 'incluir') {

          $oFolhaPagamento   = FolhaPagamentoFactory::construirPeloTipo($iTipoFolha);
          lancarAssentamento( $oTipoAssentamento, $oServidor, $oAssentamento, $oFolhaPagamento, $sComportamento);
        }

        if($oRegistro->sOpcao == 'cancelar') {

          cancelarLancamentoAssentamento( $oTipoAssentamento, $oServidor, $oAssentamento );
          $sMensagem = _M(MENSAGENS ."sucesso_cancelamento_assentamento");
        }
      }

      $oRetorno->message    = urlencode($sMensagem);
      $oRetorno->erro       = false;
      $oRetorno->servidores = getServidoresEAssentamentoPorTipoAssentamento($oAssentamento->getTipoAssentamento());

      break;

    case 'getAssentamento':

      $oAssentamento           = AssentamentoFactory::getByCodigo($oParam->iCodigoAssentamento);
      $oRetorno->oAssentamento = $oAssentamento->toJSON();

      break;

    case 'getNaturezaAssentamento':

      if(!isset($oParam->iTipoAssentamento) && !isset($oParam->iCodigoTipoPortaria)) {
        throw new BusinessException(_M(MENSAGENS ."erro_tipo_assentamento_nao_informado"));
      }

      if ( !empty($oParam->iTipoAssentamento) ) {
        $sWhere = "h12_codigo = '{$oParam->iTipoAssentamento}'";
      } elseif ( isset($oParam->iCodigoTipoPortaria) ) {
        $sWhere = "h12_codigo in (select distinct h30_tipoasse from portariatipo where h30_sequencial = {$oParam->iCodigoTipoPortaria} limit 1)";
      }


      $oDaoTipoAssentamento = new cl_tipoasse();
      $sSql                 = $oDaoTipoAssentamento->sql_query(null, "*", null, $sWhere);
      $rsSql                = db_query($sSql);

      if(!$rsSql) {
        throw new DBException("Ocorreu um erro ao consultar a natureza do tipo de assentamento.");
      }

      $oRetorno->natureza     = '';

      if(pg_num_rows($rsSql) > 0) {
        $oRetorno->natureza   = strtolower(db_utils::fieldsMemory($rsSql, 0)->rh159_descricao);
      }

      break;

    case "verificaNaturezaTipoAssentamentoSubstituicao":

      $oRetorno->erro      = true;
      $oRetorno->message   = urlencode(_M(MENSAGENS ."erro_buscar_natureza_tipo_assentamento"));

      $oParam->tipoAssentamento;
      $oTipoAssentamento  = new cl_tipoasse();
      $rsTipoAssentamento = $oTipoAssentamento->sql_record($oTipoAssentamento->sql_query_file(null, "*", null, "h12_assent = '{$oParam->tipoAssentamento}'"));

      if(is_resource($rsTipoAssentamento) && $oTipoAssentamento->numrows > 0) {
        $oRetorno->tipoAssentamentoSubstituicao = false;
        $oRetorno->erro                         = false;
        $oRetorno->message                      = '';
        $oDaoTipoAssentamento                   = db_utils::fieldsMemory($rsTipoAssentamento, 0);

        if($oDaoTipoAssentamento->h12_natureza == AssentamentoSubstituicao::CODIGO_NATUREZA){
          $oRetorno->tipoAssentamentoSubstituicao = true;
        }
      }

      break;

  }

  db_fim_transacao();//Commit
} catch (Exception $oErro) {

  db_fim_transacao(true);

  $oRetorno->erro    = true;
  $oRetorno->status  = false;
  $oRetorno->message = urlencode($oErro->getMessage());
}

header('Content-Type: application/json');
echo JSON::create()->stringify($oRetorno);


/**
 * Métodos da Requisição
 */

/**
 * Retorna os servidores e seus assentamentos por tipo de dados
 *
 * @param mixed $iTipo
 */
function getServidoresEAssentamentoPorTipoAssentamento($iTipo) {

  $oDataInicio          = null;
  $oInfTipoassentamento = InformacoesFinanceirasTipoAssentamento::getInstance(TipoAssentamentoRepository::getInstanciaPorCodigo($iTipo));

  if($oInfTipoassentamento instanceof InformacoesFinanceirasTipoAssentamento && $oInfTipoassentamento->getDataInicio() instanceof DBDate) {
    $oDataInicio        = $oInfTipoassentamento->getDataInicio();
  }

  $aServidores          = ServidorRepository::getServidoresPorTipoAssentamento($iTipo, $oDataInicio);
  $aDadosServidores     = array();
  foreach ($aServidores as $oServidor) {

    $oDadosServidor = new stdClass();
    $oDadosServidor->matricula     = $oServidor->getMatricula();
    $oDadosServidor->nome          = $oServidor->getCgm()->getNome();
    $oDadosServidor->assentamentos = array();
    $aAssentamentos                = AssentamentoRepository::getAssentamentosPorServidor($oServidor, $iTipo, $oDataInicio);
    $oCompetencia                  = DBPessoal::getCompetenciaFolha();

    foreach ($aAssentamentos as $oAssentamento ) {

      $oLote = $oAssentamento->getLote();

      if(!$oLote || DBPessoal::getCompetenciaFolha()->comparar($oAssentamento->getLote()->getCompetencia(), DBCompetencia::COMPARACAO_IGUAL) ){

        $oDadosAssentamento = new stdClass();
        $oDadosAssentamento->codigo          = $oAssentamento->getCodigo();
        $oDadosAssentamento->data_inicio     = $oAssentamento->getDataConcessao()->getDate();
        $oDadosAssentamento->data_termino    = $oAssentamento->getDataTermino() instanceof DBDate ? $oAssentamento->getDataTermino()->getDate() : null;
        $oDadosAssentamento->dias            = $oAssentamento->getDias();
        $oDadosAssentamento->quantidade      = $oAssentamento->getQuantidadePorFormula();
        $oDadosAssentamento->valor           = $oAssentamento->getValorPorFormula();
        $oDadosAssentamento->lancado_ponto   = !!$oLote;
        $oDadosAssentamento->folha_aberta    = false;

        if($oAssentamento->getErro() != null) {
          throw new BusinessException($oAssentamento->getErro());
        }

        if($oLote && $oLote->getFolhaPagamento() && $oLote->getFolhaPagamento()->isAberto()) {
          $oDadosAssentamento->folha_aberta  = true;
        }

        if($oAssentamento->getQuantidadePorFormula() > 0 || $oAssentamento->getValorPorFormula() > 0) {
          $oDadosServidor->assentamentos[] = $oDadosAssentamento;
        }
      }
    }

    if(count($oDadosServidor->assentamentos) > 0) {
      $aDadosServidores[$oServidor->getMatricula()] = $oDadosServidor;
    }
  }
  sort($aDadosServidores);

  return $aDadosServidores;
}

function lancarAssentamento(TipoAssentamento $oTipoAssentamento, Servidor $oServidor, Assentamento $oAssentamento, FolhaPagamento $oFolhaPagamento, $sComportamento = LoteRegistrosPonto::SOMAR_RUBRICA) {

  $oCompetencia     = DBPessoal::getCompetenciaFolha();
  $oInstituicao     = InstituicaoRepository::getInstituicaoSessao();

  $oLoteRegistro = new LoteRegistrosPonto;
  $oLoteRegistro->setCompetencia($oCompetencia);
  $oLoteRegistro->setInstituicao($oInstituicao);
  $oLoteRegistro->setDescricao('Lote do Assentamento: '. $oAssentamento->getCodigo());
  $oLoteRegistro->setSituacao(LoteRegistrosPonto::ABERTO);
  $oLoteRegistro->setUsuario(UsuarioSistemaRepository::getPorCodigo(db_getsession('DB_id_usuario')));
  LoteRegistrosPontoRepository::persist($oLoteRegistro);

  /**
   * Salvamos na tabela que vincula um lote a uma folha de FolhaPagamento
   */
  if(DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {

    $oDaoLoteRegistroPontoRhFolhaPagamento = new cl_loteregistropontorhfolhapagamento();
    $oDaoLoteRegistroPontoRhFolhaPagamento->rh162_sequencial        = null;
    $oDaoLoteRegistroPontoRhFolhaPagamento->rh162_loteregistroponto = $oLoteRegistro->getSequencial();
    $oDaoLoteRegistroPontoRhFolhaPagamento->rh162_rhfolhapagamento  = $oFolhaPagamento->getFolhaAberta()->getSequencial();
    $oDaoLoteRegistroPontoRhFolhaPagamento->incluir(null);
  }

  /**
   * Salvamos na tabela assentaloteregistroponto
   */
  $oDaoAssentaLoteRegistroPonto = new cl_assentaloteregistroponto();
  $oDaoAssentaLoteRegistroPonto->rh160_sequencial        = null;
  $oDaoAssentaLoteRegistroPonto->rh160_assentamento      = $oAssentamento->getCodigo();
  $oDaoAssentaLoteRegistroPonto->rh160_loteregistroponto = $oLoteRegistro->getSequencial();
  $oDaoAssentaLoteRegistroPonto->incluir(null);

  if($oDaoAssentaLoteRegistroPonto->erro_status == 0) {
    throw new BusinessException("Error Processing Request");
  }

  $oRegistroLoteRegistros = new RegistroLoteRegistrosPonto();
  $oRegistroLoteRegistros->setCodigoLote($oLoteRegistro->getSequencial());
  $oRegistroLoteRegistros->setRubrica($oTipoAssentamento->getRubricaTipoAssentamentoFinanceiro());
  $oRegistroLoteRegistros->setServidor($oServidor);
  $oRegistroLoteRegistros->setQuantidade($oAssentamento->getQuantidadePorFormula());
  $oRegistroLoteRegistros->setValor($oAssentamento->getValorPorFormula());
  $oRegistroLoteRegistros->setInstituicao($oInstituicao);
  $oRegistroLoteRegistros->setFolhaPagamento($oFolhaPagamento);
  RegistroLoteRegistrosPontoRepository::persist($oRegistroLoteRegistros);

  $oLoteRegistro = LoteRegistrosPontoRepository::getInstanceByCodigo($oLoteRegistro->getSequencial());
  $oLoteRegistro->setTipoLancamentoPonto($sComportamento);
  $oLoteRegistro->confirmarLote();
}

function cancelarLancamentoAssentamento(TipoAssentamento $oTipoAssentamento, Servidor $oServidor, Assentamento $oAssentamentoCancelamento) {

  $oCompetencia           = DBPessoal::getCompetenciaFolha();
  $oInstituicao           = InstituicaoRepository::getInstituicaoSessao();
  $aAssentamentosServidor = AssentamentoRepository::getAssentamentosPorServidor($oServidor, $oTipoAssentamento->getSequencial());
  $aLotesRetorno          = array();

  foreach ($aAssentamentosServidor as $oAssentamento) {

    $oLote = $oAssentamento->getLote();

    if($oLote){

      if($oLote->getFolhaPagamento()) {

        if(!$oLote->getFolhaPagamento()->isAberto()){
          continue;
        }

        $oLote->cancelarConfirmacao();
        $iSequencialFolhaPagamento = $oLote->getFolhaPagamento()->getSequencial();
      }

      if ( $oAssentamento->getCodigo() == $oAssentamentoCancelamento->getCodigo() ) {

        /**
         * Se utiliza estrutura suplementar remove o vínculo entre a folha e o lote
         */
        if(DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {

          /**
           * Remove o vinculo do lote com o assentamento.
           */
          $oDaoLoteRegistroPontoRhFolhapagamento    = new cl_loteregistropontorhfolhapagamento();
          $sWhereLoteRegistroPontoRhFolhapagamento  = "     rh162_loteregistroponto = {$oLote->getSequencial()}";
          $sWhereLoteRegistroPontoRhFolhapagamento .= " and rh162_rhfolhapagamento  = {$iSequencialFolhaPagamento}";
          $oDaoLoteRegistroPontoRhFolhapagamento->excluir(null, $sWhereLoteRegistroPontoRhFolhapagamento);

          if ( $oDaoLoteRegistroPontoRhFolhapagamento->erro_status == "0" ) {
            throw new DBException($oDaoLoteRegistroPontoRhFolhapagamento->erro_msg); //@todo externar isso
          }
        }

        /**
         * Remove o vinculo do lote com o assentamento.
         */
        $oDaoAssentaLoteRegistroPonto       = new cl_assentaloteregistroponto();
        $sWhereDaoAssentaLoteRegistroPonto  = "     rh160_loteregistroponto = {$oLote->getSequencial()}";
        $sWhereDaoAssentaLoteRegistroPonto .= " and rh160_assentamento      = {$oAssentamentoCancelamento->getCodigo()}";
        $oDaoAssentaLoteRegistroPonto->excluir(null, $sWhereDaoAssentaLoteRegistroPonto);

        if ( $oDaoAssentaLoteRegistroPonto->erro_status == "0" ) {
          throw new DBException($oDaoAssentaLoteRegistroPonto->erro_msg); //@todo externar isso
        }

        LoteRegistrosPontoRepository::remover($oLote);

      } else {

        /**
         * Guardando lotes que devem parmanecer como lançados no ponto
         */
        $aLotesRetorno[] = array('sequencial_lote'=>$oLote->getSequencial(),
          'sequencial_folha_pagamento' => $iSequencialFolhaPagamento
        );
      }
    }
  }


  /**
   * Retorna lotes que estavam lançados novamente para o ponto
   */
  foreach ($aLotesRetorno as $aItemLote) {

    $oLoteRetorno = LoteRegistrosPontoRepository::getInstanceByCodigo($aItemLote["sequencial_lote"]);
    $oLoteRetorno->setTipoLancamentoPonto(LoteRegistrosPonto::SOMAR_RUBRICA);

    if($oLoteRetorno->getSituacao() != LoteRegistrosPonto::CONFIRMADO){

      if($oLoteRetorno->getFolhaPagamento() && $oLoteRetorno->getFolhaPagamento()->isAberto()) {
        $oLoteRetorno->confirmarLote();
      }
    }
  }
}