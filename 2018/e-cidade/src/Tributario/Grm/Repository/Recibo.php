<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 02/03/17
 * Time: 11:08
 */

namespace ECidade\Tributario\Grm\Repository;


use ECidade\Tributario\Grm\Recibo as ReciboModel;
use Ecidade\Tributario\Grm\TipoRecolhimento AS TipoRecolhimentoModel;
use ECidade\Tributario\Grm\UnidadeGestora as UnidadeGestoraModel;

class Recibo {

  /**
   * Persiste os dados do Recibo
   * @param \ECidade\Tributario\Grm\Recibo $recibo
   * @throws \DBException
   */
  public function persist(ReciboModel $recibo) {

    $oDaoRecibo = new \cl_guiarecolhimento();
    $oDaoRecibo->k174_sequencial            = null;
    $oDaoRecibo->k174_unidadegestora        = $recibo->getUnidadeGestora()->getCodigo();
    $oDaoRecibo->k174_tiporecolhimento      = $recibo->getTipoRecolhimento()->getCodigo();
    $oDaoRecibo->k174_cgm                   = $recibo->getCgm()->getCodigo();
    $oDaoRecibo->k174_numpre                = $recibo->getNumpre();
    $oDaoRecibo->k174_numeroreferencia      = $recibo->getNumeroReferencia();
    $oDaoRecibo->k174_competencia           = $recibo->getCompetencia();
    $oDaoRecibo->k174_datavencimento        = $recibo->getDataEmissao()->getDate();
    $oDaoRecibo->k174_valor                 = $recibo->getValor();
    $oDaoRecibo->k174_desconto              = $recibo->getValorDesconto();
    $oDaoRecibo->k174_multa                 = $recibo->getValorMulta();
    $oDaoRecibo->k174_juros                 = $recibo->getValorJuros();
    $oDaoRecibo->k174_outrosacrescimos      = $recibo->getValorOutrosAcrescimento();
    $oDaoRecibo->k174_outrasdeducoes        = $recibo->getValorOutrasDeducoes();
    $oDaoRecibo->k174_valortotal            = $recibo->getValorTotal();

    if ($recibo->getProcesso() != '') {
      $oDaoRecibo->k174_processo = $recibo->getProcesso()->getCodProcesso();
    }
    $oDaoRecibo->k174_atributodinamicovalor = $this->persisirAtributosDinamicos($recibo->getAtributos());
    if ($recibo->getCodigo() == '') {

      $oDaoRecibo->incluir(null);
      $recibo->setCodigo($oDaoRecibo->k174_sequencial);
    } else {

      $oDaoRecibo->k174_sequencial = $recibo->getCodigo();
      $oDaoRecibo->alterar($recibo->getCodigo());

    }
    if ($oDaoRecibo->erro_status == 0) {
      throw new \DBException("Erro ao incluir GRM\n{$oDaoRecibo->erro_msg}");
    }
  }

  /**
   * Retorna os Recibos da Unidade Gestora
   * @param \ECidade\Tributario\Grm\UnidadeGestora $unidadeGestora
   * @param null                                   $where
   * @return ReciboModel[]
   * @throws \DBException
   */
  public function getRecibosPagosDaUnidadeGestora(UnidadeGestoraModel $unidadeGestora, $where = null) {

    $oDaoRecibo   = new \cl_guiarecolhimento();

    $whereUnidade  = "k171_sequencial = {$unidadeGestora->getCodigo()}";
    $whereUnidade .= " and k00_numpre is not null ";
    if (!empty($where)) {
      $whereUnidade .= " and {$where}";
    }
    $sCampos         = "distinct guiarecolhimento.*, z01_numcgm, k00_dtpaga, k177_cidadao, k177_cidadaoseq";
    $sSqlDadosRecibo = $oDaoRecibo->sql_query_guias_arrepaga($sCampos, $whereUnidade, "k174_tiporecolhimento, k00_dtpaga");
    $rsGuiaRecolhimento = db_query($sSqlDadosRecibo);
    if (!$rsGuiaRecolhimento) {
      throw new \DBException('Erro ao pesquisar guias pagas da Unidade gestora.');
    }
    $oInstancia = $this;
    $aRecibos  = \db_utils::makeCollectionFromRecord($rsGuiaRecolhimento, function($oDados) use ($oInstancia, $unidadeGestora) {

      $oRecibo = $oInstancia->make($oDados);
      $oRecibo->setDataPagamento(new \DBDate($oDados->k00_dtpaga));
      $oRecibo->setUnidadeGestora($unidadeGestora);
      return $oRecibo;
    });
    return $aRecibos;
  }

  /**
   * @param $dados
   * @return \ECidade\Tributario\Grm\Recibo
   */
  public function make($dados) {

    $oTipoRecolhimentoRepository = new TipoRecolhimento();
    $oTipoRecolhimento           = $oTipoRecolhimentoRepository->getTipoRecolhimento($dados->k174_tiporecolhimento);
    $oUnidadeGestoraRepository   = new UnidadeGestora();
    $oUnidadeGestora             = $oUnidadeGestoraRepository->getById($dados->k174_unidadegestora);
    $oRecibo = new ReciboModel();
    $oRecibo->setCodigo($dados->k174_sequencial);
    $oRecibo->setNumeroReferencia($dados->k174_numeroreferencia);
    $oRecibo->setCgm(\CgmRepository::getByCodigo($dados->k174_cgm));
    $oRecibo->setNumpre($dados->k174_numpre);
    $oRecibo->setCompetencia($dados->k174_competencia);
    $oRecibo->setDataEmissao(new \DBDate($dados->k174_datavencimento));
    $oRecibo->setDataVencimento(new \DBDate($dados->k174_datavencimento));
    $oRecibo->setValor($dados->k174_valor);
    $oRecibo->setValorJuros($dados->k174_juros);
    $oRecibo->setValorDesconto($dados->k174_desconto);
    $oRecibo->setValorMulta($dados->k174_multa);
    $oRecibo->setValorOutrosAcrescimento($dados->k174_outrosacrescimos);
    $oRecibo->setValorOutrasDeducoes($dados->k174_outrasdeducoes);
    $oRecibo->setValorTotal($dados->k174_valortotal);
    $oRecibo->setTipoRecolhimento($oTipoRecolhimento);
    $oRecibo->setUnidadeGestora($oUnidadeGestora);
    $oRecibo->setPlainData($dados);
    $oRecibo->getAtributos();

    if (!empty($dados->k177_cidadao)) {
      $oRecibo->setCidadao(new \Cidadao($dados->k177_cidadao, $dados->k177_cidadaoseq));
    }

    return $oRecibo;

  }

  /**
   * @param $codigo
   * @return \ECidade\Tributario\Grm\Recibo
   * @throws \BusinessException
   */
  public function getById($codigo) {

    $oDaoRecibo = new \cl_guiarecolhimento();
    $dadosGuia = $oDaoRecibo->findBydId($codigo);
    if (empty($dadosGuia)) {
      throw new \BusinessException("Não existe guia com código {$codigo}");
    }
    return $this->make($dadosGuia);
  }

  /**
   * Retorna os Recibos da Unidade Gestora
   * @param \ECidade\Tributario\Grm\UnidadeGestora $unidadeGestora
   * @param \DBDepartamento                        $DBDepartamento
   * @param bool                                   $ativas
   * @return \ECidade\Tributario\Grm\Recibo[]
   * @throws \DBException
   * @internal param null $where
   */
  public function getGuiasParaMovimentacaoNoDepartamento(UnidadeGestoraModel $unidadeGestora, \DBDepartamento $DBDepartamento, $ativas = true, $where) {

    $oDaoRecibo    = new \cl_guiarecolhimento();
    $whereUnidade  = "k171_sequencial = {$unidadeGestora->getCodigo()}";
    $whereUnidade .= " and (p61_coddepto = {$DBDepartamento->getCodigo()} or p61_coddepto is null) ";
    if ($ativas) {
      $whereUnidade .= " and not exists(select 1 from procarquiv where p67_codproc = p58_codproc) ";
    }
    if (!empty($where)) {
      $whereUnidade .= " and {$where} ";
    }

    $sCampos         = "guiarecolhimento.*, z01_numcgm, k00_dtpaga";
    $sSqlDadosRecibo = $oDaoRecibo->sql_query_guias_workflow($sCampos, $whereUnidade, "k174_tiporecolhimento, k00_dtpaga");

    $rsGuiaRecolhimento = db_query($sSqlDadosRecibo);
    if (!$rsGuiaRecolhimento) {
      throw new \DBException('Erro ao pesquisar guias pagas da Unidade gestora.');
    }
    $oInstancia = $this;
    $aRecibos  = \db_utils::makeCollectionFromRecord($rsGuiaRecolhimento, function($oDados) use ($oInstancia, $unidadeGestora, $DBDepartamento) {

      $oRecibo = $oInstancia->make($oDados);
      $oWorkFlow                    = $oRecibo->getTipoRecolhimento()->getWorkflow();
      $oAtividadeInicial            = $oWorkFlow->getAtividadeNaOrdem(1);
      if ($oDados->k174_processo == '' && $oAtividadeInicial->getDepartamento()->getCodigo() != $DBDepartamento->getCodigo()) {
        return \db_utils::ITERATION_CONTINUE;
      }
      $oRecibo->setDataPagamento(new \DBDate($oDados->k00_dtpaga));
      $oRecibo->setUnidadeGestora($unidadeGestora);
      return $oRecibo;
    });
    return $aRecibos;
  }

  /**
   * @param \Ecidade\Tributario\Grm\TipoRecolhimento $tipoRecolhimento
   * @return ReciboModel[]
   * @throws \DBException
   */
  public function getRecibosDoTipoDeRecolhimento(TipoRecolhimentoModel $tipoRecolhimento) {

    $oDaoRecibo = new \cl_guiarecolhimento();
    $sSql       = $oDaoRecibo->sql_query_file(NULL, '*', NULL, 'k174_tiporecolhimento = '.$tipoRecolhimento->getCodigo());

    $rsGuiaRecolhimento = db_query($sSql);
    if (!$rsGuiaRecolhimento) {
      throw new \DBException('Erro ao pesquisar guias pagas do Tipo de Recolhimento');
    }

    $oInstancia = $this;
    $aRecibos  = \db_utils::makeCollectionFromRecord($rsGuiaRecolhimento, function($oDados) use ($oInstancia) {
      $oRecibo = $oInstancia->make($oDados);

      return $oRecibo;
    });
    return $aRecibos;
  }

  /**
   * @param $atributos
   * @return int
   * @throws \BusinessException
   */
  private function persisirAtributosDinamicos($atributos) {

    $oDaoAtributoDinamicoGrupo = new \cl_db_cadattdinamicovalorgrupo();
    $oDaoAtributoDinamicoGrupo->incluir(null);
    if ($oDaoAtributoDinamicoGrupo->erro_status == 0) {
      throw new \BusinessException("Erro ao salvar dados dos grupo de valores de atributo dinâmico.");
    }
    $iCodigoValorGrupo         = $oDaoAtributoDinamicoGrupo->db120_sequencial;
    $oDaoAtributoDinamicoValor = new \cl_db_cadattdinamicoatributosvalor();
    foreach ($atributos as $atributo) {

      $oDaoAtributoDinamicoValor->db110_cadattdinamicovalorgrupo   = $iCodigoValorGrupo;
      $oDaoAtributoDinamicoValor->db110_db_cadattdinamicoatributos = $atributo->codigo_atributo;
      $oDaoAtributoDinamicoValor->db110_valor                      = $atributo->valor_plano;
      $oDaoAtributoDinamicoValor->incluir(null);
      if ($oDaoAtributoDinamicoValor->erro_status == 0) {
        throw new \BusinessException("Erro ao salvar dados de valores de atributo dinâmico.");
      }
    }
    return $iCodigoValorGrupo;
  }

}