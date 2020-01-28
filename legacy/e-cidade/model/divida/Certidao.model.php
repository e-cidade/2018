<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
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

use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\CobrancaRegistrada;

/**
 *  Model para controle das CDA's
 *
 * @author Roberto Carneiro <roberto@dbseller.com.br>
 * @package divida
 */
class Certidao {

  /**
   * Constante do arquivo de mensagens
   */
  const MENSAGENS = "tributario.divida.Certidao.";

  /**
   * Sequencial da Certidao
   * @var integer
   */
  private $iSequencial = null;

  /**
   * Date de Emissão
   * @var date
   */
  private $dDataEmissao = null;

  /**
   * Oid do parágrafo da Certidão
   * @var oid
   */
  private $iOid = null;

  /**
   * Usuário que emitiu a Certidão
   * @var UsuarioSistema
   */
  private $oUsuario = null;

  /**
   * Instituição da Certidão
   * @var Instituicao
   */
  private $oInstituicao = null;

  /**
   * Array de Inconsistencias
   * @var array
   */
  private $aInconsistencias = array();

  /**
   * Construtor da classe
   *
   * @param integer $iSequencial Código da Certidão
   */
  public function __construct( $iSequencial = null ) {

    $oDaoCertidao = new cl_certid;
    $rsCertidao   = null;

    if ( !is_null($iSequencial) ) {

      $sSqlCertidao = $oDaoCertidao->sql_query_file($iSequencial);
      $rsCertidao   = $oDaoCertidao->sql_record($sSqlCertidao);
    }

    if ( !empty($rsCertidao) ) {

      $oCertidao = db_utils::fieldsMemory($rsCertidao, 0);

      $this->iSequencial  = $oCertidao->v13_certid;
      $this->dDataEmissao = $oCertidao->v13_dtemis;
      $this->iOid         = $oCertidao->v13_memo;
      $this->oUsuario     = new UsuarioSistema( $oCertidao->v13_login );
      $this->oInstituicao = new Instituicao( $oCertidao->v13_instit  );
    }
  }

  /**
   * Validamos se a Certidão está em cobrança extrajudicial
   * @return boolean
   */
  public function isCobrancaExtrajudicial() {

    /**
     * Este método é alterado por um extension no plugin IntegraçãoCRA
     */

    $oDaoCertidao = new cl_certid;
    $sSqlCertidao = $oDaoCertidao->sql_query_movimentacao( $this->iSequencial );
    $rsCertidao   = $oDaoCertidao->sql_record( $sSqlCertidao );

    if( $rsCertidao ){

      $oCertidao    = db_utils::fieldsMemory( $rsCertidao, 0 );
      if ( isset($oCertidao->v32_tipo ) ) {

        if ( $oCertidao->v32_tipo == CertidMovimentacao::TIPO_MOVIMENTACAO_ENVIADO ||
             $oCertidao->v32_tipo == CertidMovimentacao::TIPO_MOVIMENTACAO_PROTESTADO ) {
          return true;
        }
      }
    }

    return false;
  }

  /**
   * Validamos se em algum momento a Certidão possuiu cobrança extrajudicial
   * @return boolean
   */
  public static function hasCobrancaExtrajudicial($iCertidao) {

    $oDaoCertidao = new cl_certid;
    $sSqlCertidao = $oDaoCertidao->sql_query_movimentacao( $iCertidao );
    $rsCertidao   = $oDaoCertidao->sql_record( $sSqlCertidao );

    if ( $rsCertidao && $oDaoCertidao->numrows > 0 ) {
      return true;
    }

    $oDaoCertidao = new cl_acertid;
    $sSqlCertidao = $oDaoCertidao->sql_query_movimentacao( $iCertidao );
    $rsCertidao   = $oDaoCertidao->sql_record( $sSqlCertidao );

    if ( $rsCertidao && $oDaoCertidao->numrows > 0 ) {
      return true;
    }

    return false;
  }

  /**
   * Buscamos os dados do débito de divida
   * @return array
   */
  public function getNumpreNumpar( $lBuscarReparcelamento = false ) {

    $oDaoCertidao = new cl_certid;
    $sSqlCertidao = $oDaoCertidao->sql_queryCertidao($this->iSequencial, "certidao", "numpre, numpar, numcgm, tipo_cda", $lBuscarReparcelamento);
    $rsCertidao   = $oDaoCertidao->sql_record($sSqlCertidao);

    return db_utils::getCollectionByRecord($rsCertidao);
  }

  /**
   * Comparamos a data da movimentação atual com a anterior
   *
   * @param  DBDate $oDataMovimentacao
   * @return boolean
   */
  public function validaDataMovimentacao( DBDate $oDataMovimentacao ) {

    $oDaoCertidao = new cl_certid;
    $sSqlCertidao = $oDaoCertidao->sql_query_movimentacao( $this->iSequencial );
    $rsCertidao   = $oDaoCertidao->sql_record( $sSqlCertidao );
    $oCertidao    = db_utils::fieldsMemory( $rsCertidao, 0 );

    if ( !empty($oCertidao) ) {

      $oDataMovimentacaoAnterior = new DBDate( db_formatar($oCertidao->v32_datamovimentacao, 'd') );
      $iResultado  = DBDate::calculaIntervaloEntreDatas( $oDataMovimentacao, $oDataMovimentacaoAnterior, 'd' );

      if ( $iResultado < 0 ) {
        return false;
      }
    }

    return true;
  }

  /**
   * Retorna ultima data de movimentação da certidao
   *
   * @todo mover para movimentacao
   * @return string data de movimentação
   */
  public function getDataUltimaMovimentacao(){

    $oDaoCertidao = new cl_certid;
    $sSqlDataUltimaMovimentacao = $oDaoCertidao->sql_query_movimentacao( $this->iSequencial );
    $rsDataUltimaMovimentacao   = $oDaoCertidao->sql_record( $sSqlDataUltimaMovimentacao );
    if( $rsDataUltimaMovimentacao ){

      $sDataUltimaMovimentacao  = db_utils::fieldsMemory( $rsDataUltimaMovimentacao, 0 )->v32_datamovimentacao;
    }

    return db_formatar($sDataUltimaMovimentacao, 'd');
  }

  /**
   * Validamos o tipo de movimentação
   *
   * @param  integer $iTipo tipo de movimentação
   * @return boolean
   */
  public function validaTipoMovimentacao( $iTipo ) {

    $oDaoCertidao = new cl_certid;
    $sSqlCertidao = $oDaoCertidao->sql_query_movimentacao( $this->iSequencial );
    $oCertidao    = db_utils::fieldsMemory( $oDaoCertidao->sql_record($sSqlCertidao), 0 );

    if ( !empty($oCertidao) ) {

      if ( $iTipo               == CertidMovimentacao::TIPO_MOVIMENTACAO_PROTESTADO &&
           $oCertidao->v32_tipo == CertidMovimentacao::TIPO_MOVIMENTACAO_ENVIADO) {
        return true;
      }

      if ( $iTipo               == CertidMovimentacao::TIPO_MOVIMENTACAO_RESGATADO &&
           ( $oCertidao->v32_tipo == CertidMovimentacao::TIPO_MOVIMENTACAO_ENVIADO ||
             $oCertidao->v32_tipo == CertidMovimentacao::TIPO_MOVIMENTACAO_PROTESTADO ) ) {
        return true;
      }
    }

    return false;
  }

  /**
   * Buscamos os dados da certidão no arrecad
   *
   * @param  string $sCampos
   * @return array/boolean
   */
  public function getArrecad($sCampos) {

    $oDaoCertidao = new cl_certid;
    $sSql         = $oDaoCertidao->sql_query_arrecad($sCampos, $this->iSequencial);
    $rsCertidao   = $oDaoCertidao->sql_record($sSql);

    return db_utils::getCollectionByRecord($rsCertidao);
  }

  /**
   * Buscamos o código da inicial da certidão, caso exista
   * @return integer/boolean
   */
  public function getInicial() {

    $oDaoCertidao = new cl_certid;
    $sSql         = $oDaoCertidao->sql_query_inicial_cda( $this->iSequencial );
    $rsCertidao   = $oDaoCertidao->sql_record( $sSql );
    $oCertidao    = db_utils::fieldsMemory($rsCertidao, 0);

    if ( empty($oCertidao->v51_inicial) ) {
      return false;
    }

    return $oCertidao->v51_inicial;
  }

  /**
   * Buscamos os abatimentos vinculados aos débitos da CDA
   *
   * @param  string  $sCampos
   * @param  integer $iTipoAbatimento
   *
   * @return array/boolean
   */
  public function getAbatimento( $sCampos = "*", $iTipoAbatimento = 1 ) {

    $oDaoCertidao = new cl_certid;
    $sSql         = $oDaoCertidao->sql_query_abatimento( $this->iSequencial, $sCampos, $iTipoAbatimento );
    $rsCertidao   = $oDaoCertidao->sql_record($sSql);

    if ( empty($rsCertidao) ) {
      return false;
    }

    return db_utils::getCollectionByRecord($rsCertidao);
  }

  /**
   * Busca o código senquencial da certidão
   * @return integer
   */
  public function getSequencial() {
    return $this->iSequencial;
  }

  /**
   * Busca a data de emissão
   * @return date
   */
  public function getDataEmissao() {
    return $this->dDataEmissao;
  }

  /**
   * Altera a date de emissão
   * @param date
   */
  public function setDateEmissao( $dDataEmissao ) {
    $this->dDataEmissao = $dDataEmissao;
  }

  /**
   * Busca o oid do parágrafo da certidão
   * @return integer
   */
  public function getOid() {
    return $this->iOid;
  }

  /**
   * Altera o oid da certidão
   * @param integer
   */
  public function setOid( $iOid) {
    $this->iOid = $iOid;
  }

  /**
   * Busca o Usuário da Certidão
   * @return UsuarioSistema
   */
  public function getUsuario() {
    return $this->oUsuario;
  }

  /**
   * Altera o Usuário da certidão
   * @param UsuarioSistema $oUsuario
   */
  public function setUsuario( UsuarioSistema $oUsuario ) {
    $this->oUsuario = $oUsuario;
  }

  /**
   * Busca a Instituição
   * @return Instituição
   */
  public function getInstituicao() {
    return $this->oInstituicao;
  }

  /**
   * Altera a Instituição da certidão
   * @param Instituicao $oInstituicao
   */
  public function setInstituicao( Instituicao $oInstituicao) {
    $this->oInstituicao = $oInstituicao;
  }

  /**
   * Busca as inconsistencias do recibo
   * @return array
   */
  public function getInconsistencias() {
    return $this->aInconsistencias;
  }

  /**
   * Verifica se existe pagamento total e parcial para a certidao
   * @return mix object / boolean
   */
  public function getPagamento(){

    $oDaoCertidao               = new cl_certid;

    /**
     * Verificamos se possui pagamento total
     */
    $sSqlVerificaPagamentoTotal = $oDaoCertidao->sql_query_verificaPagamento( $this->iSequencial );
    $rsVerificaPagamentoTotal   = $oDaoCertidao->sql_record( $sSqlVerificaPagamentoTotal );

    if ( $rsVerificaPagamentoTotal  ) {

      if( $oDaoCertidao->numrows > 0 ){

        return db_utils::fieldsMemory($rsVerificaPagamentoTotal, 0);
      }
    }

    /**
     * Verificamos se possui pagamento parcial vinculado
     */
    $sSqlVerificaPagamentoParcial = $oDaoCertidao->sql_query_verificaPagamento( $this->iSequencial, true );
    $rsVerificaPagamentoparcial   = $oDaoCertidao->sql_record( $sSqlVerificaPagamentoParcial );

    if ( $rsVerificaPagamentoparcial  ) {

      if( $oDaoCertidao->numrows > 0 ){

        return db_utils::fieldsMemory($rsVerificaPagamentoparcial, 0);
      }
    }

    return false;
  }

  /**
   * Criamos o Recibo da Certidão para cobrança em cartório
   *
   * @param  Instituicao $oInstituicao
   * @param  DBDate      $oDataVencimento
   * @param  DBDate      $oDataEmissao
   * @param  integer     Ano do exercício
   * @return object      Objeto contendo nome do arquivo do recibo e dados para o relatório recibo/certidão
   */
  public function gerarReciboCobrancaEmCartorio( Instituicao $oInstituicao, DBDate $oDataVencimento, DBDate $oDataEmissao, $iAnousu ) {

    try {

      $oRegraEmissao  = new regraEmissao(19,
                                         2,
                                         $oInstituicao->getSequencial(),
                                         date("Y-m-d",db_getsession("DB_datausu")),
                                         db_getsession('DB_ip'),
                                         true,
                                         null);

    } catch (Exception $oErro) {
      throw new BusinessException( _M( self::MENSAGENS . "erro_regra_emissao" ) );
    }

    /**
     * Buscamos os numpres dos débitos vinculados à certidão
     */
    $aNumpresNumpar = $this->getNumpreNumpar( true );

    try {

      /**
       * Emitimos o recibo na base de dados
       */
      $oRecibo = new Recibo(2, null, 1);
      foreach ($aNumpresNumpar as $oNumpresNumpar) {

        if ($oNumpresNumpar->tipo_cda == "Parcelamento") {
          $oNumpresNumpar->numpar = 0;
        }

        $oRecibo->addNumpre($oNumpresNumpar->numpre, $oNumpresNumpar->numpar);
        $oIdentificacao = CgmFactory::getInstanceByCgm($oNumpresNumpar->numcgm);
      }

      /* @note: Valida se for cobranca registrada */
      $lCobrancaRegistrada = CobrancaRegistrada::validaConvenioCobranca($oRegraEmissao->getConvenio());

      /* @note: Se cobranca registrada verifica se a data de vencimento é um dia util */
      if ($lCobrancaRegistrada) {

        $sUltimoDiaUtilVencimento = "select fc_ultimo_dia_util('{$oDataVencimento->getDate()}'::date) as vencimento";
        $rsVencimento   = db_query($sUltimoDiaUtilVencimento);

        if ( !$rsVencimento ) {
          throw new BusinessException( _M( self::MENSAGENS . "erro_ultimo_dia_util") );
        }

        $oVencimento = db_utils::fieldsMemory($rsVencimento, 0);
        $oDataVencimento = new DBDate(date("Y-m-d", strtotime($oVencimento->vencimento)));

      }

      /**
       * Regra de calculo de juros e multa
       */
      $sDataVencimento    = $oDataEmissao->getDate();
      $oParametrosDivida  = db_stdClass::getParametro("pardiv", array($oInstituicao->getSequencial()));

      if (isset($oParametrosDivida[0]->v04_cobrarjurosmultacda) && $oParametrosDivida[0]->v04_cobrarjurosmultacda == 't') {
        $sDataVencimento = $oDataVencimento->getDate();
      }

      $oRecibo->setNumBco( $oRegraEmissao->getCodConvenioCobranca() );
      $oRecibo->setDataRecibo( $oDataEmissao->getDate() );
      $oRecibo->setDataVencimentoRecibo( $sDataVencimento );
      $oRecibo->setExercicioRecibo( $iAnousu );
      $oRecibo->emiteRecibo($lConvenioCobrancaValido);

      $iNumnov             = $oRecibo->getNumpreRecibo();

      /* @note: se cobranca registrada adiciona a fila da remessa*/
      if ($lCobrancaRegistrada) {
        CobrancaRegistrada::adicionarRecibo($oRecibo, $oRegraEmissao->getConvenio());
      }

    } catch(Exception $oErro) {

      $oStdMensagemErro                  = new stdClass();
      $oStdMensagemErro->codigo_certidao = $this->iSequencial;
      throw new BusinessException( _M( self::MENSAGENS . "erro_emite_recibo", $oStdMensagemErro ) );
    }

    /**
     * Altera data de vencimento do recibo sem projetar juros/multa/correção dos valores
     */
    $oDaoReciboPaga = new cl_recibopaga();
    $sSqlReciboPaga = $oDaoReciboPaga->sql_query_altera_data_vencimento($iNumnov, $oDataVencimento->getDate());
    $rsReciboPaga   = db_query($sSqlReciboPaga);

    if( empty($rsReciboPaga) ){
      throw new BusinessException( _M( self::MENSAGENS . "erro_atualiza_vencimento" ) );
    }

    /**
     * Atualizamos a data de vencimento do recibo, para que não haja pproblemas no momento do pagamento
     */
    $sSqlValoresPorReceita = $oDaoReciboPaga->sql_query_valores_receita($oInstituicao->getSequencial(), $iAnousu, $iNumnov);
    $rsValoresPorReceita   = db_query($sSqlValoresPorReceita);

    if (empty($rsValoresPorReceita)) {
      throw new DBException( _M( self::MENSAGENS . "erro_valores_receita") );
    }

    $iNumnovFormatado = db_sqlformatar($iNumnov,8,'0');
    $iNumnovFormatado = $iNumnovFormatado.db_CalculaDV($iNumnovFormatado);

    $iValorBarra = db_formatar(str_replace('.', '', str_pad(number_format($oRecibo->getTotalRecibo(), 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');

    $iTerceiroDigito = 6;

    $oConvenio = new convenio($oRegraEmissao->getConvenio(), $iNumnov, 0, $oRecibo->getTotalRecibo(), $iValorBarra, $oDataVencimento->getDate(), $iTerceiroDigito);

    /**
     * Iniciamos a montagem do pdf do recibo
     */
    $oPdf                  = $oRegraEmissao->getObjPdf();
    $oPdf->linha_digitavel = $oConvenio->getLinhaDigitavel();
    $oPdf->codigobarras    = $oConvenio->getCodigoBarra();

    // Identificação Prefeitura
    $oPdf->logo            = $oInstituicao->getImagemLogo();
    $oPdf->prefeitura      = $oInstituicao->getDescricao();
    $oPdf->tipo_convenio   = $oConvenio->getTipoConvenio();
    $oPdf->uf_config       = $oInstituicao->getUf();
    $oPdf->enderpref       = $oInstituicao->getLogradouro();
    $oPdf->municpref       = $oInstituicao->getMunicipio();
    $oPdf->telefpref       = $oInstituicao->getTelefone();
    $oPdf->emailpref       = $oInstituicao->getEmail();
    $oPdf->cgcpref         = $oInstituicao->getCNPJ();

    // Indentificação contribuinte
    $sLogradouro  = $oIdentificacao->getLogradouro();
    $sNome        = $oIdentificacao->getNome();
    $sComplemento = $oIdentificacao->getComplemento();
    $sNumCgm      = $oIdentificacao->getCodigo();
    $sMunicipio   = $oIdentificacao->getMunicipio();
    $iNumero      = $oIdentificacao->getNumero();
    $sBairro      = $oIdentificacao->getBairro();
    $sCep         = $oIdentificacao->getCep();

    if( $oIdentificacao->isFisico() ){
      $sCnpjCpf   = $oIdentificacao->getCpf();
    }
    if( $oIdentificacao->isJuridico() ){
      $sCnpjCpf   = $oIdentificacao->getCnpj();
    }

    /**
     * Quadro Identificação
     */
    $oPdf->descr11_1          = $oPdf->nome  = $sNome;
    $oPdf->descr11_2          = $oPdf->ender = $sLogradouro;
    $oPdf->munic              = $sMunicipio;
    $oPdf->bairrocontri       = $sBairro;
    $oPdf->cep                = $sCep;
    $oPdf->cgccpf             = $sCnpjCpf;

    /**
     * Quadro Direito
     */
    $oPdf->tipoinscr          = "Numcgm :";
    $oPdf->nrinscr            = "$sNumCgm";
    $oPdf->tipolograd         = "Logradouro : {$sLogradouro}";
    $oPdf->nomepriimo         = $sLogradouro;
    $oPdf->tipocompl          = 'N' . chr(176) . "/Compl :";
    $oPdf->complpri           = $sComplemento;
    $oPdf->nrpri              = $iNumero;
    $oPdf->tipobairro         = "Bairro :";
    $oPdf->bairropri          = $sBairro;

    $oPdf->datacalc           = date('d/m/Y', db_getsession('DB_datausu')) . " ";
    $oPdf->predatacalc        = date('d/m/Y', db_getsession('DB_datausu')) . " ";

    /**
     * Valores por Receita
     */
    $iTotalReceitas = pg_num_rows($rsValoresPorReceita);

    /**
     * Verifica limite de receitas para o modelo especifico
     */
    if ($iTotalReceitas >= 50 ){

      $oStdMensagemErro                  = new stdClass();
      $oStdMensagemErro->codigo_certidao = $this->iSequencial;
      throw new BusinessException( _M( self::MENSAGENS . "limite_receitas", $oStdMensagemErro ) );
    }

    /**
     * Colocamos as receitas no pdf através de seu recordset
     */
    for ($iIndice = 0; $iIndice < $iTotalReceitas; $iIndice++) {

      $oLinhaReceita = db_utils::fieldsMemory($rsValoresPorReceita, $iIndice);
      $oPdf->arraycodtipo[$iIndice]       = $oLinhaReceita->codtipo;
      $oPdf->arraycodhist[$iIndice]       = $oLinhaReceita->k00_hist;
      $oPdf->arraycodreceitas[$iIndice]   = $oLinhaReceita->k00_receit;
      $oPdf->arrayreduzreceitas[$iIndice] = $oLinhaReceita->codreduz;
      $oPdf->arraydescrreceitas[$iIndice] = $oLinhaReceita->k02_drecei;
      $oPdf->arrayvalreceitas[$iIndice]   = $oLinhaReceita->valor;
    }

    $oPdf->descr12_1          = $oPdf->historico  = "Certidão do Foro";
    $oPdf->histparcel         = "Histórico das parcelas";
    $oPdf->dtparapag          = $oPdf->dtvenc = $oDataVencimento->getDate(DBDate::DATA_PTBR);

    /**
     * Obtem dados de pagamento
     */
    $sWhere1    = " r.k00_hist  <> 918";
    $sWhere2    = " r.k00_hist  =  918";

    $sSqlDadosPagamento = $oDaoReciboPaga->sql_query_dados_pagamento($iAnousu, $sWhere1, $sWhere2, $iNumnov);
    $rsDadosPagamento = db_query($sSqlDadosPagamento) or die($sSqlDadosPagamento);

    if (empty($rsDadosPagamento)) {
      throw new DBException(_M(self::MENSAGENS."erro_dados_pagamento"));
    }

    $oPdf->recorddadospagto = $rsDadosPagamento;
    $oPdf->linhasdadospagto = pg_num_rows($rsDadosPagamento);
    $oPdf->receita          = 'k00_receit';
    $oPdf->valor            = 'valor';
    $oPdf->receitared       = 'codreduz';
    $oPdf->dreceita         = 'k02_descr';
    $oPdf->ddreceita        = 'k02_drecei';

    /**
     * Ficha de Compensação
     */
    $oPdf->numpre    = str_pad($iNumnov, 8, "0", STR_PAD_LEFT)."000";
    $oPdf->descr9    = $oPdf->numpre;

    $oDadoDBBancos            = new cl_db_bancos;
    $rsConsultaBanco          = $oDadoDBBancos->sql_record($oDadoDBBancos->sql_query_file($oConvenio->getCodBanco()));

    if (empty($rsConsultaBanco)) {
      throw new DBException( _M( self::MENSAGENS . "erro_dados_banco" ) );
    }

    $oBanco                   = db_utils::fieldsMemory($rsConsultaBanco,0);
    $oPdf->numbanco           = $oBanco->db90_codban."-".$oBanco->db90_digban;
    $oPdf->banco              = $oBanco->db90_abrev;

    $oPdf->sMensagemCaixa     = '';

    /**
     * Parcela
     */
    $oPdf->descr10            = '1/1';

    $oPdf->valtotal           = db_formatar($oRecibo->getTotalRecibo(),'f');
    $oPdf->linhadigitavel     = $oConvenio->getLinhaDigitavel();
    $oPdf->codigo_barras      = $oPdf->codigobarras  = $oConvenio->getCodigoBarra();

    $oPdf->data_processamento = date('d/m/Y'); // Data do servidor
    $oPdf->agencia_cedente    = $oConvenio->getAgenciaCedente();
    $oPdf->carteira           = $oConvenio->getCarteira();
    $oPdf->nosso_numero       = $oConvenio->getNossoNumero();
    $oPdf->especie            = 'R$';
    $oPdf->tipo_exerc         = '19 / ' . $iAnousu; //Fixo Certidão de Divida Ativa
    $oPdf->imprime();

    /**
     * Criamos o arquivo contendo o recibo
     */
    $oPdfArquivo              = $oPdf->objpdf;
    $sNomeArquivo             = "tmp/recibo_{$this->iSequencial}_{$sCnpjCpf}_" . time() . ".pdf";
    $oPdf->objpdf->Output($sNomeArquivo, false, true);

    /**
     * Preparamos os dados pro retorno
     */
    $oRetorno = new StdClass();
    $oRetorno->sNomeArquivo    = $sNomeArquivo;
    $oRetorno->aDadosRelatorio = array( 'iCertidao'    => $this->iSequencial,
                                        'sNome'        => $sNome,
                                        'iArrecadacao' => $oPdf->numpre,
                                        'iNumnov'      => $iNumnov,
                                        'iValor'       => db_formatar($oRecibo->getTotalRecibo(),'f') );
    $oRetorno->oDataVencimento = $oDataVencimento;

    return $oRetorno;
  }
}
