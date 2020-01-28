<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

class ArquivoConsignadoManualRepository {

  /**
   * Instância da classe
   * @var ArquivoConsignadoManualRepository
   */
  private static $oInstance;

  /**
   * @var ArquivoConsignadoManual[]
   */
  public $itens = array();

  private function __construct() {}

  private function __clone() {}

  /**
   * Retorna uma instância da classe
   * @return ArquivoConsignadoManualRepository
   */
  public static function getInstance() {

    if (ArquivoConsignadoManualRepository::$oInstance == null) {
      ArquivoConsignadoManualRepository::$oInstance = new ArquivoConsignadoManualRepository();
    }
    return ArquivoConsignadoManualRepository::$oInstance;
  }

  /**
   * @param stdClass
   * @return ArquivoConsignadoManual
   */
  public function make($oDados) {

    $oArquivoConsignado = new ArquivoConsignadoManual();
    $oArquivoConsignado->setCodigo($oDados->rh151_sequencial);
    $oArquivoConsignado->setServidor(ServidorRepository::getInstanciaByCodigo($oDados->rh152_regist));
    $oArquivoConsignado->setRubrica(RubricaRepository::getInstanciaByCodigo($oDados->rh153_rubrica));
    $oArquivoConsignado->setCompetencia(new DBCompetencia($oDados->rh151_ano, $oDados->rh151_mes));
    $oArquivoConsignado->setNumeroDeParcelas($oDados->rh153_totalparcelas);
    $oArquivoConsignado->setValorDaParcela($oDados->rh153_valordescontar);
    $oArquivoConsignado->setProcessado((boolean)$oDados->rh151_processado);
    $oArquivoConsignado->setSituacao($oDados->rh151_situacao);
    $oArquivoConsignado->setTipo($oDados->rh151_tipoconsignado);
    $oArquivoConsignado->setInstituicao(InstituicaoRepository::getInstituicaoByCodigo($oDados->rh151_instit));
    $oArquivoConsignado->setBanco(new Banco($oDados->rh151_banco));
    $oArquivoConsignado->setCodigoConsignadoOrigem($oDados->rh151_consignadoorigem);
    return $oArquivoConsignado;
  }

  /**
   *
   */
  public static function persist(ArquivoConsignadoManual $oArquivo, $lRecriarParcelas = false) {

    if (!db_utils::inTransaction()) {
      throw new DBException("Nao existe transação com o banco de dados");
    }

    if (ArquivoConsignadoManual::temFinanciamentoNoMesmoBancoECompetencia($oArquivo)) {
      throw  new BusinessException("O servidor {$oArquivo->getServidor()->getCgm()->getNome()} já possui um financiamento em aberto com os dados informados.");
    }

    /**
     * Verificamos se o consignado que estamos realizando é um refinanciamento ou portabilidade,
     * caso sim, removos as parcelas do financiamento anterior
     */
    if (in_array($oArquivo->getSituacao(), array(ArquivoConsignadoManual::SITUACAO_PORTADO, ArquivoConsignadoManual::SITUACAO_REFINANCIADO))) {

      if ($oArquivo->getConsignadoOrigem() == '') {
        throw new BusinessException('Para realializar um refinanciamento ou uma portabilidade, é necessário informar um consignado de origem.');
      }

      $consignadoOrigem = $oArquivo->getConsignadoOrigem();
      $aParcelasRemoverConsignadoOrigem = ArquivoConsignadoManualParcelaRepository::getParcelasDoFinanciamentoApartirDaCompetencia($consignadoOrigem, $oArquivo->getCompetencia());
      foreach ($aParcelasRemoverConsignadoOrigem as $oParcela) {
        ArquivoConsignadoManualParcelaRepository::remove($oParcela);
      }
    }

    $aParcelasConsignado = array();

    if (self::deveAlterarParcelasDoContrato($oArquivo) && !$lRecriarParcelas) {

      if($oArquivo->getCodigo()) {
        $aParcelasConsignado = ArquivoConsignadoManualParcelaRepository::getParcelasDoFinanciamentoApartirDaCompetencia($oArquivo, $oArquivo->getCompetencia());
      }
      
      foreach ($aParcelasConsignado as $oParcela) {

        if ($oParcela->isProcessado()) {

          if($oArquivo->getSituacao() == ArquivoConsignadoManual::SITUACAO_CANCELADO) {
            continue;
          }

          if ($oArquivo->getSituacao() == ArquivoConsignadoManual::SITUACAO_NORMAL && !$lRecriarParcelas) {
            throw new BusinessException("Este contrato não pode ser alterado pois possui parcelas já descontadas.");
          }
        }
        ArquivoConsignadoManualParcelaRepository::remove($oParcela);
      }
    }

    $oDaoMovimento = new cl_rhconsignadomovimento();
    $oDaoMovimento->rh151_ano              = $oArquivo->getCompetencia()->getAno();
    $oDaoMovimento->rh151_mes              = $oArquivo->getCompetencia()->getMes();
    $oDaoMovimento->rh151_banco            = $oArquivo->getBanco()->getCodigo();
    $oDaoMovimento->rh151_tipoconsignado   = ArquivoConsignadoManual::TIPO_MANUAL;
    $oDaoMovimento->rh151_processado       = $oArquivo->isProcessado() ? "true": "false";
    $oDaoMovimento->rh151_relatorio        = 'null';
    $oDaoMovimento->rh151_arquivo          = 'null';
    $oDaoMovimento->rh151_instit           = $oArquivo->getInstituicao()->getCodigo();
    $oDaoMovimento->rh151_nomearquivo      = "CONSIGNADO MANUAL";
    $oDaoMovimento->rh151_consignadoorigem = $oArquivo->getCodigoConsignadoOrigem();
    $oDaoMovimento->rh151_situacao         = $oArquivo->getSituacao();
    $iCodigoArquivo =  $oArquivo->getCodigo();
    
    if (empty($iCodigoArquivo)) {

      $oDaoMovimento->incluir(null);
      $oArquivo->setCodigo($oDaoMovimento->rh151_sequencial);

    } else {

      $oDaoMovimento->rh151_sequencial = $oArquivo->getCodigo();
      $oDaoMovimento->alterar($iCodigoArquivo);
    }


    if (!$oArquivo->isProcessado() && !in_array($oArquivo->getSituacao(), array(ArquivoConsignadoManual::SITUACAO_CANCELADO, ArquivoConsignadoManual::SITUACAO_INATIVO)) && !$lRecriarParcelas) {

      $aParcelas = $oArquivo->adicionarParcelas($oArquivo->getParcelaInicial());
      foreach ($aParcelas as $oParcela) {
        ArquivoConsignadoManualParcelaRepository::persist($oParcela, $oArquivo);
      }
    }
    
    if ($oDaoMovimento->erro_status == 0) {
      throw new BusinessException($oDaoMovimento->erro_msg);
    }
  }

  /**
   *
   * Remove os dados do consignado
   * @param \ArquivoConsignadoManual $arquivoConsignadoManual
   * @return bool
   * @throws \BusinessException
   * @throws \DBException
   */
  public static function remove(ArquivoConsignadoManual $arquivoConsignadoManual) {

    if ($arquivoConsignadoManual->getCodigo() == '') {

      unset($arquivoConsignadoManual);
      return true;
    }
    if (!db_utils::inTransaction()) {
      throw new DBException("Nao existe transação com o banco de dados");
    }

    if ($arquivoConsignadoManual->temMovimentacao()) {
      throw new BusinessException("O consignado já possui um refinanciamento ou portabilidade. Não é possível remover.");
    }

    if ($arquivoConsignadoManual->temParcelasProcessadas()) {
      throw new BusinessException("O consignado já possui parcelas processadas. Não é possível remover.");
    }

    $parcelas = $arquivoConsignadoManual->getParcelas();
    foreach ($parcelas as $oParcela) {
      ArquivoConsignadoManualParcelaRepository::remove($oParcela);
    }
    $oDaoConsigando = new cl_rhconsignadomovimento;
    $oDaoConsigando->excluir($arquivoConsignadoManual->getCodigo());
    if ($oDaoConsigando->erro_status == 0) {
      throw new BusinessException("Não foi possível remover o consignado.\n".$oDaoConsigando->erro_msg);
    }
    unset($arquivoConsignadoManual);
    return true;
  }

  /**
   * @param $iCodigo
   * @return ArquivoConsignadoManual
   * @throws \BusinessException
   *
   */
  public static function getByCodigo($iCodigo) {

    if (empty(self::getInstance()->itens[$iCodigo])) {

      $oDaoConsignado    = new cl_rhconsignadomovimentomanual();
      $sWhere            = "rh151_sequencial = {$iCodigo}";
      $sWhere           .= " and rh151_tipoconsignado = '" . ArquivoConsignado::TIPO_MANUAL."'";
      $aCampos           = array('rh151_sequencial','rh152_regist','rh153_rubrica','rh151_ano',
                            'rh151_mes','rh153_totalparcelas','rh153_valordescontar','rh151_processado::int','rh151_situacao',
                            'rh151_tipoconsignado','rh151_instit','rh151_banco','rh151_consignadoorigem');
      $sCampos           = implode(', ', $aCampos);
      $sSqlFinanciamento = $oDaoConsignado->sql_query_dados_financiamento(null, $sCampos, 'rh151_sequencial limit 1', $sWhere);
      $rsFinanciamento   = db_query($sSqlFinanciamento);
      if (!$rsFinanciamento) {
        throw  new BusinessException("Não foi possivel pesquisar os dados financiamento {$iCodigo}");
      }
      if (pg_num_rows($rsFinanciamento) == 0) {
        throw  new BusinessException("Não foi encontrado financiamento com o código {$iCodigo}");
      }
      self::getInstance()->itens[$iCodigo] = self::getInstance()->make(db_utils::fieldsMemory($rsFinanciamento, 0));
    }
    return self::getInstance()->itens[$iCodigo];
  }

  /**
   * @param \Servidor $servidor
   * @param \Rubrica  $rubrica
   * @param \Banco    $banco
   * @return \ArquivoConsignadoManual
   * @throws \BusinessException
   * @internal param $iCodigo
   */
  public static function getByServidorBancoRubrica(Servidor $servidor, Rubrica $rubrica, Banco $banco) {

    $oInstituicao   = InstituicaoRepository::getInstituicaoSessao();
    $oDaoConsignado = new cl_rhconsignadomovimentomanual();
    $aWhere[] = "rh151_instit      = ".$oInstituicao->getCodigo();
    $aWhere[] = "rh153_rubrica     = '".$rubrica->getCodigo()."'";
    $aWhere[] = "rh151_banco       = '".$banco->getCodigo()."'";
    $aWhere[] = "rh152_regist      = '".$servidor->getMatricula()."'";
    $aWhere[] = "rh151_tipoconsignado = '" . ArquivoConsignado::TIPO_MANUAL."'";
    $sWhere= implode(" and ", $aWhere);
    $sSqlFinanciamento = $oDaoConsignado->sql_query_dados_financiamento(null, "*", 'rh151_sequencial limit 1', $sWhere);
    $rsFinanciamento   = db_query($sSqlFinanciamento);
    if (!$rsFinanciamento) {
      throw  new BusinessException("Não foi possivel pesquisar os financiamentos do servidor {$servidor->getMatricula()}");
    }

    if (pg_num_rows($rsFinanciamento) > 0) {

      $oDadosFinanciamento = db_utils::fieldsMemory($rsFinanciamento, 0);
      if (empty(ArquivoConsignadoManualRepository::getInstance()->itens[$oDadosFinanciamento->rh151_sequencial])) {
        ArquivoConsignadoManualRepository::getInstance()->itens[$oDadosFinanciamento->rh151_sequencial] = ArquivoConsignadoManualRepository::getInstance()->make(db_utils::fieldsMemory($rsFinanciamento, 0));
      }
      return ArquivoConsignadoManualRepository::getInstance()->itens[$oDadosFinanciamento->rh151_sequencial];
    }
    return null;
  }
  
  /**
   * @param \Servidor $servidor
   *
   * @return \ArquivoConsignadoManual[]
   * @throws \BusinessException
   */
  public static function getContratosAtivosByServidor(Servidor $servidor) {

    return ArquivoConsignadoManualRepository::getContratosAtivos($servidor);
  }
  /**
   * @param \Servidor $servidor
   *
   * @return \ArquivoConsignadoManual[]
   * @throws \BusinessException
   */
  public static function getContratosAtivos($servidor = null) {

    $aWhere    = array();
    $sMensagem = "Não foi possivel pesquisar os financiamentos";
    if ($servidor instanceof Servidor) {
      $sMensagem .= " do servidor {$servidor->getMatricula()}";
      $aWhere[]   = "rh152_regist      = '".$servidor->getMatricula()."'";
    } 

    $oInstituicao      = InstituicaoRepository::getInstituicaoSessao();
    $oDaoConsignado    = new cl_rhconsignadomovimentomanual();
    $aCampos           = array('rh151_sequencial', 'rh152_regist', 'rh151_banco', 'rh151_processado::int');
    $aCampos           = array_merge($aCampos, array('rh151_situacao', 'rh151_consignadoorigem', 'rh151_ano'));
    $aCampos           = array_merge($aCampos, array('rh151_mes', 'rh151_instit', 'rh151_tipoconsignado'));
    $aWhere[]          = "rh151_instit      = ".$oInstituicao->getCodigo();
    //$aWhere[]          = "rh182_processado is false";
    $aWhere[]          = "rh151_tipoconsignado = '" . ArquivoConsignado::TIPO_MANUAL."'";
    $sCampos           = ' distinct '. implode(',', $aCampos);
    $sCampos          .= ' , (select r.rh153_rubrica ';
    $sCampos          .= '      from rhconsignadomovimentoservidorrubrica r ';
    $sCampos          .= '     where r.rh153_sequencial = rhconsignadomovimentoservidorrubrica.rh153_sequencial limit 1) as rh153_rubrica';
    $sCampos          .= ' , (select r.rh153_valordescontar ';
    $sCampos          .= '      from rhconsignadomovimentoservidorrubrica r ';
    $sCampos          .= '     where r.rh153_sequencial = rhconsignadomovimentoservidorrubrica.rh153_sequencial limit 1) as rh153_valordescontar';
    $sWhere            = implode(" and ", $aWhere);
    $sSqlFinanciamento = $oDaoConsignado->sql_query_dados_financiamento(null, $sCampos, 'rh151_sequencial', $sWhere);
    $rsFinanciamento   = db_query($sSqlFinanciamento);
    
    if (!$rsFinanciamento) {
      throw  new BusinessException($sMensagem);
    }

    if (pg_num_rows($rsFinanciamento) > 0) {

      $aFinanciamentos = array();
      $aFinanciamentos = db_utils::makeCollectionFromRecord($rsFinanciamento, function ($oDadosFinanciamento) {

        $oDadosFinanciamento->rh182_processado     = true;
        $oDadosFinanciamento->rh153_totalparcelas  = 0;

        if (empty(ArquivoConsignadoManualRepository::getInstance()->itens[$oDadosFinanciamento->rh151_sequencial])) {
          ArquivoConsignadoManualRepository::getInstance()->itens[$oDadosFinanciamento->rh151_sequencial] = ArquivoConsignadoManualRepository::getInstance()->make($oDadosFinanciamento);
        }
        return ArquivoConsignadoManualRepository::getInstance()->itens[$oDadosFinanciamento->rh151_sequencial];
      });

      return $aFinanciamentos;
    }

    return null; 
  }

  /**
   * Verifica se deve ser alterado as parcelas do contrato
   * @param \ArquivoConsignado $oArquivo
   * @return bool
   */
  public static function deveAlterarParcelasDoContrato(ArquivoConsignado $oArquivo) {

    $aListaSituacoes =  array(ArquivoConsignadoManual::SITUACAO_NORMAL, ArquivoConsignadoManual::SITUACAO_CANCELADO);
    return in_array($oArquivo->getSituacao(), $aListaSituacoes ) && !$oArquivo->isProcessado();

  }
}