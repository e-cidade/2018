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

require_once modification("model/arrecadacao/abatimento/Abatimento.model.php");
require_once modification("model/arrecadacao/Debito.php");
require_once modification("libs/exceptions/DBException.php");
require_once modification("libs/exceptions/BusinessException.php");

/**
 * Representação de Abatimento do Tipo Desconto, que é executado em débito que estão em aberto.
 * @author Rafael Serpa Nery <rafael.nery@dbseller.com.br>
 * @author Vitor Rocha <vitor@dbseller.com.br>
 * @package     Arrecadacao
 * @subpackage  Abatimento
 */
class Desconto extends Abatimento {

  /**
   * Historico de Cancelamento da histcalc
   */
  const HISTORICO              = 918;
  const HISTORICO_CANCELAMENTO = 10918;

  /**
   * Numpre do recibo gerado para o desconto
   * @var integer
   */
  private $iNumpre;

  /**
   * Numpar do recibo gerado para o desconto
   * @var integer
   */
  private $iNumpar;

  /**
   * Codigo da receita do recibo gerado para o desconto
   * @var integer
   */
  private $iCodigoReceita;

  /**
   * codigo de tipo do debito
   *
   * @var integer
   * @access private
   */
  private $iTipoDebito;

  /**
   * Valor descontado dos juros
   * @var numeric
   */
  private $nValorDescontadoJuros;

  /**
   * Valor descontado da multa
   * @var numeric
   */
  private $nValorDescontadoMulta;

  /**
   * Valor descontado da correção
   * @var numeric
   */
  private $nValorDescontadoCorrecao;

  /**
   * Descrição da receita
   * @var string
   */
  private $sDescRec;

  /**
   * Construtor da classe
   *
   * @param integer $iCodigoAbatimento
   */
  public function __construct($iCodigoAbatimento = null){

    parent::__construct($iCodigoAbatimento);

    if ( empty($iCodigoAbatimento) ) {
      return false;
    }

    $sCamposDesconto = 'arreckey.k00_tipo, arreckey.k00_numpre, arreckey.k00_numpar, arreckey.k00_receit, k128_valorabatido, k128_correcao, k128_juros, k128_multa';
    $sWhereDesconto  = 'k125_sequencial = ' . $iCodigoAbatimento;
    $oDaoAbatimento  = db_utils::getDao('abatimento');
    $sSqlDesconto    = $oDaoAbatimento->sql_queryDescontos($sCamposDesconto, $sWhereDesconto);
    $rsDesconto      = $oDaoAbatimento->sql_record($sSqlDesconto);

    if ( $oDaoAbatimento->erro_status == '0' ) {
      throw new DBException($oDaoAbatimento->erro_msg);
    }

    $oAbatimento = db_utils::fieldsMemory($rsDesconto, 0);

    $this->iNumpre                  = $oAbatimento->k00_numpre;
    $this->iNumpar                  = $oAbatimento->k00_numpar;
    $this->iCodigoReceita           = $oAbatimento->k00_receit;
    $this->nValorDescontadoCorrecao = $oAbatimento->k128_correcao;
    $this->nValorDescontadoJuros    = $oAbatimento->k128_juros;
    $this->nValorDescontadoMulta    = $oAbatimento->k128_multa;
    $this->iTipoDebito              = $oAbatimento->k00_tipo;
  }

  /**
   * Define o numpre para o recibo do desconto
   * @param integer $iNumpre
   */
  public function setNumpre($iNumpre){
    $this->iNumpre = $iNumpre;
  }

  /**
   * Retorna o numpre do recibo do desconto
   * @return integer
   */
  public function getNumpre(){
    return $this->iNumpre;
  }

  /**
   * Define o numpar do recibo do desconto
   * @param integer $iNumpar
   */
  public function setNumpar($iNumpar){
    $this->iNumpar = $iNumpar;
  }

  /**
   * Retorna o numpar do recibo do desconto
   * @return integer
   */
  public function getNumpar(){
    return $this->iNumpar;
  }

  /**
   * Define o código da receita do recibo do desconto
   * @param integer
   */
  public function setCodigoReceita($iCodigoReceita){
    $this->iCodigoReceita = $iCodigoReceita;
  }

  /**
   * Retorna o código da receita do recibo do desconto
   * @return integer
   */
  public function getCodigoReceita(){
    return $this->iCodigoReceita;
  }

  /**
   * Define tipo de debito
   *
   * @param integer $iTipoDebito
   * @access public
   * @return void
   */
  public function setTipoDebito($iTipoDebito) {
    $this->iTipoDebito = $iTipoDebito;
  }

  /**
   * Retorna o tipo de debito
   *
   * @access public
   * @return integer
   */
  public function getTipoDebito() {

    return $this->iTipoDebito;
  }

  /**
   * Seta os valores que compõe o desconto
   * @param numeric $nValorDescontadoJuros
   * @param numeric $nValorDescontadoMulta
   * @param numeric $nValorDescontadoCorrecao
   * @return void
   */
  public function setComposicaoDesconto($nValorDescontadoJuros, $nValorDescontadoMulta, $nValorDescontadoCorrecao) {
  	$this->nValorDescontadoJuros    = $nValorDescontadoJuros;
  	$this->nValorDescontadoMulta    = $nValorDescontadoMulta;
  	$this->nValorDescontadoCorrecao = $nValorDescontadoCorrecao;
  }

  /**
   * Retorna o valor descontado dos juros
   * @return numeric
   */
  public function getValorDescontadoJuros() {
  	return $this->nValorDescontadoJuros;
  }

  /**
   * Retorna o valor descontado da multa
   * @return numeric
   */
  public function getValorDescontadoMulta() {
  	return $this->nValorDescontadoMulta;
  }

  /**
   * Retorna o valor descontado da correção
   * @return numeric
   */
  public function getValorDescontadoCorrecao() {
  	return $this->nValorDescontadoCorrecao;
  }

  /**
   * Retorna o valor descontado da correção
   * @return numeric
   */
  public function getDescRec() {
    return $this->sDescRec;
  }

  /**
   * Salva ou altera o Descontto
   * @return boolean
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new DBException("Não existe transação ativa.");
    }

    $this->setTipoAbatimento( Abatimento::TIPO_DESCONTO );
    $this->setSituacao( Abatimento::SITUACAO_ATIVO );
    $this->setValorDisponivel( $this->getValor() );
    parent::salvar();

    $this->lancarValorDesconto();
    $this->lancarHistorico( Desconto::HISTORICO, "Desconto Manual");
    $this->modificarValorDebito();

    return true;
  }

  /**
   * Lança Histórico para o Débito
   *
   * @throws DBException - Quando Houver erro em Query
   * @return boolean
   */
  private function lancarHistorico( $iCodigo, $sDescricao ) {
    //@todo vincular a figura débito

    $oDaoArrehist = new cl_arrehist();
    $oDaoArrehist->k00_numpre     = $this->getNumpar();
    $oDaoArrehist->k00_numpar     = $this->getNumpar();
    $oDaoArrehist->k00_hist       = $iCodigo;
    $oDaoArrehist->k00_dtoper     = $this->getDataLancamento()->getDate();
    $oDaoArrehist->k00_hora       = $this->getHoraLancamento();
    $oDaoArrehist->k00_id_usuario = $this->getUsuario()->getIdUsuario();
    $oDaoArrehist->k00_histtxt    = $sDescricao;
    $oDaoArrehist->k00_limithist  = '';
    $oDaoArrehist->k00_idhist     = null;

    $oDaoArrehist->incluir(null);

    if ($oDaoArrehist->erro_status == '0') {
      throw new DBException("Erro ao incluir Histório para a numpre: {$this->getNumpre()}, parcela {$this->getNumpar()}. \nErro: (arrehist) {$oDaoArrehist->erro_msg}");
    }

    return true;
  }

  /**
   * Lança valor modificado do Débito
   * @throws DBException       - Quando Houver erro em Query
   * @throws BusinessException - Quando não encontrar numpre/numpar/receita
   * @return boolean
   */
  private function modificarValorDebito() {

    $oDaoArrecad     = new cl_arrecad();

    $sWhereArrecad   = "     k00_numpre = {$this->getNumpre()}        ";
    $sWhereArrecad  .= " and k00_numpar = {$this->getNumpar()}        ";
    $sWhereArrecad  .= " and k00_receit = {$this->getCodigoReceita()} ";

    $sSqlValorDebito = $oDaoArrecad->sql_query_file(null, "k00_valor", null, $sWhereArrecad);
    $rsValorDebito   = db_query($sSqlValorDebito);

    if (!$rsValorDebito) {
      throw new DBException('Erro ao buscar o valor do débito. Erro técnico: ' . pg_last_error());
    }

    if (pg_num_rows($rsValorDebito) == 0) {
      throw new BusinessException("Débito não encontrado.");
    }

    $oArrecad               = db_utils::fieldsMemory($rsValorDebito, 0);
    $oDaoArrecad->k00_valor = ($oArrecad->k00_valor - $this->getValor());
    $oDaoArrecad->alterar(null, $sWhereArrecad);

    if ( $oDaoArrecad->erro_status == '0' ) {
      throw new DBException("Erro ao alterar valor do débito no arrecad. \nErro: {$oDaoArrecad->erro_msg}");
    }
    return true;
  }

  /**
   * Modifica Valor do Arrecad
   * @throws Exception
   * @return boolean
   */
  private function lancarValorDesconto() {
    /**
     * Verifica se ja existe arreckey
     * @todo vincular a figura débito
     */
    $oDaoArreckey             = new cl_arreckey();

    $sWhereArreckey           = "     k00_numpre = {$this->getNumpre()}         ";
    $sWhereArreckey          .= " and k00_numpar = {$this->getNumpar()}         ";
    $sWhereArreckey          .= " and k00_receit = {$this->getCodigoReceita()}  ";
    $sWhereArreckey          .= " and k00_hist   = " . Desconto::HISTORICO;

    $sSqlArreckey             = $oDaoArreckey->sql_query_file(null, 'k00_sequencial', null, $sWhereArreckey);
    $rsArreckey               = $oDaoArreckey->sql_record($sSqlArreckey);

    $oDaoArreckey->k00_numpre = $this->getNumpre();
    $oDaoArreckey->k00_numpar = $this->getNumpar();
    $oDaoArreckey->k00_receit = $this->getCodigoReceita();
    $oDaoArreckey->k00_hist   = Desconto::HISTORICO;
    $oDaoArreckey->k00_tipo   = $this->getTipoDebito();

    if ( $oDaoArreckey->numrows > 0 ) {

      $oArreckey                = db_utils::fieldsMemory($rsArreckey, 0);
      $oDaoArreckey->k00_sequencial = $oArreckey->k00_sequencial;
      $oDaoArreckey->alterar( $oArreckey->k00_sequencial );

    } else {
      $oDaoArreckey->incluir(null);
    }

    if ( $oDaoArreckey->erro_status == "0" ) {
      throw new DBException("Erro ao salvar dados de historico do desconto. \nErro: (arreckey) {$oDaoArreckey->erro_msg}");
    }

    /**
     * Inclui ligacao entre abatimento e arreckey
     */
    $oDaoAbatimentoArreckey                    = new cl_abatimentoarreckey();
    $oDaoAbatimentoArreckey->k128_arreckey     = $oDaoArreckey->k00_sequencial;
    $oDaoAbatimentoArreckey->k128_abatimento   = $this->getCodigo();
    $oDaoAbatimentoArreckey->k128_valorabatido = $this->getValor();
    $oDaoAbatimentoArreckey->k128_correcao     = $this->getValorDescontadoCorrecao();
    $oDaoAbatimentoArreckey->k128_juros        = $this->getValorDescontadoJuros();
    $oDaoAbatimentoArreckey->k128_multa        = $this->getValorDescontadoMulta();
    $oDaoAbatimentoArreckey->incluir(null);

    /**
     * Erro ao incluir na abatimentoarreckey
    */
    if ( $oDaoAbatimentoArreckey->erro_status == "0" ) {
      throw new DBException("Erro ao incluir vínculo do desconto com a Composição. \nErro: (abatimentoarreckey) {$oDaoAbatimentoArreckey->erro_msg}");
    }
    return true;
  }

  /**
   * Retorna os descontos da origem pesquisada
   *
   * @param  string  $sTipoOrigem    - Tipo da Origem do Abatimento
   * @param  integer $iChavePesquisa - Código da Origem do Abatimento
   * @throws ParameterException Disparado quando for informado origem inválida
   * @throws DBException Disparado quando ocorrer erro em query
   * @return Desconto[]
   */
  public static function getDescontosPorOrigem($sTipoOrigem, $iChavePesquisa) {

    $oDaoAbatimento = new cl_abatimento();

    $aTiposOrigem = array(
        Debito::ORIGEM_CGM        => "numcgm",
        Debito::ORIGEM_INSCRICAO  => "inscr",
        Debito::ORIGEM_MATRICULA  => "matric"
    );

    if (!isset($aTiposOrigem[$sTipoOrigem])) {
      throw new ParameterException("Tipo de Origem não existe");
    }

    $sWhere = "exists ( select 1                                          ";
    $sWhere.= "           from arre{$aTiposOrigem[$sTipoOrigem]}                                 ";
    $sWhere.= "          where arre{$aTiposOrigem[$sTipoOrigem]}.k00_numpre = arreckey.k00_numpre";
    $sWhere.= "            and arre{$aTiposOrigem[$sTipoOrigem]}.k00_{$aTiposOrigem[$sTipoOrigem]} = {$iChavePesquisa}) ";
    $sWhere.= "                                                           ";

    $sSqlDescontos = $oDaoAbatimento->sql_queryDescontos('k125_sequencial', $sWhere);

    $rsDescontos = db_query($sSqlDescontos);
    if (!$rsDescontos) {
      throw new DBException("Erro ao buscar os dados do desconto. Erro técnico: ". pg_last_error());
    }

    $aDadosDescontos = db_utils::getCollectionByRecord($rsDescontos);

    $aDescontos = array();

    foreach ($aDadosDescontos as $oDadosDesconto) {
      $iCodigoAbatimento = $oDadosDesconto->k125_sequencial;
      $aDescontos[] = new Desconto($iCodigoAbatimento);
    }

    return $aDescontos;
  }

  /**
   * Cancelar desconto
   * - Altera tipo do abatimento para cancelado
   * - Atualiza arrecad somando valor com desconto
   * - Adiciona historico 919 - cancelamento de desconto manual
   *
   * @access public
   * @return boolean
   */
  public function cancelar() {

    if ( $this->getCodigo() == null ) {
      throw new Exception('Código do abatimento não encontrado.');
    }

    /**
     * Altera situacao do abatimento para cancelado
     */
    $this->setSituacao(Abatimento::SITUACAO_CANCELADO);
    parent::salvar();

    $oDaoArrecad   = db_utils::getDao('arrecad');
    $sWhereDebito  = "     k00_numpre = {$this->getNumpre()}        ";
    $sWhereDebito .= " and k00_numpar = {$this->getNumpar()}        ";
    $sWhereDebito .= " and k00_receit = {$this->getCodigoReceita()} ";
    $sSqlDebito    = $oDaoArrecad->sql_query_file(null, 'k00_valor as valor', null, $sWhereDebito);
    $rsDebito      = $oDaoArrecad->sql_record($sSqlDebito);

    /**
     * Erro ao buscar valor do debito na arrecad
     */
    if ( $oDaoArrecad->erro_status == "0" ) {
      throw new Exception($oDaoArrecad->erro_msg);
    }

    /**
     * Valor do debito sem desconto
     */
    $nValorDebito = db_utils::fieldsMemory($rsDebito, 0)->valor;

    /**
     * Soma valor do debito com valor do desconto
     */
    $oDaoArrecad->k00_valor = $nValorDebito + $this->getValor();

    /**
     * Atualiza arrecad retornando valor do desconto ao debito
     */
    $oDaoArrecad->alterar(null, $sWhereDebito);

    /**
     * Erro ao alterar arrecad
     */
    if ( $oDaoArrecad->erro_status == "0" ) {
      throw new Exception($oDaoArrecad->erro_msg);
    }

    /**
     * Lanca historio do tipo 919 - cancelamento de desconto
     */
    $this->lancarHistorico(DESCONTO::HISTORICO_CANCELAMENTO, 'Cancelamento de desconto manual');

    return true;
  }

  /**
   * Valida se numpre está disponível para inclusão ou cancelamento de desconto,
   * verificando se existe algum recibo válido para esse numpre.
   * @param integer $iNumpre
   * @return boolean
   */
  public static function validarProcessamento($iNumpre) {

    require_once(modification("model/recibo.model.php"));

    $aRecibos = Recibo::getRecibosByNumpreDebito($iNumpre);

    foreach ($aRecibos as $oRecibo) {

      if ( $oRecibo->isValido() ) {
        return false;
      }
    }
    return true;
  }
}