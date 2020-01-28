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

require_once(modification('libs/db_app.utils.php'));
require_once(modification('model/pessoal/RubricaRepository.model.php'));
require_once(modification('model/pessoal/EventoFinanceiroFolha.model.php'));
require_once(modification("fpdf151/pdf.php"));

/**
 * Classse de Definicao do calculo da Folha
 *
 * @abstract
 * @package  Pessoal
 * @author   Rafael Serpa Nery <rafael.nery@dbseller.com.br>
 */
abstract class CalculoFolha {

  const CALCULO_SALARIO         = "gerfsal";
  const CALCULO_ADIANTAMENTO    = "gerfadi";
  const CALCULO_FERIAS          = "gerffer";
  const CALCULO_COMPLEMENTAR    = "gerfcom";
  const CALCULO_13o             = "gerfs13";
  const CALCULO_RESCISAO        = "gerfres";
  const CALCULO_PONTO_FIXO      = "gerffx";
  const CALCULO_PROVISAO_FERIAS = "gerfprovfer";
  const CALCULO_PROVISAO_13o    = "gerfprovs13";
  const CALCULO_SUPLEMENTAR     = "suplementar";
  const MENSAGENS               = "recursoshumanos.pessoal.CalculoFolha.";

  /**
   * Folha que está sendo Calculada em tempo de excução
   */
  public static $oFolhaAtual;

  /**
   * Tabela do calculo
   *
   * @var string
   * @access protected
   */
  protected $sTabela;

  /**
   * Sigla da tabela
   *
   * @var string
   * @access protected
   */
  protected $sSigla;

  /**
   * Servidor proprietário do calculo
   *
   * @var Servidor
   * @access private
   */
  private $oServidor;

  /**
   * Array com matrículas a exlcuir do histórico do ponto
   *
   * @var Array
   * @access public
   */
  public static $aMatriculasExcluirHistoricoPonto;

  /**
   * Construtor da Classe
   *
   * @param Servidor $oServidor
   */
  public function __construct ( Servidor $oServidor ) {
    $this->oServidor = $oServidor;
  }

  /**
   * Retorna a sigla da tabela
   */
  public function getSigla() {
    return $this->sSigla;
  }

  /**
   * Retorna instancia do Servidor
   * @return Servidor
   */
  public function getServidor () {
    return $this->oServidor;
  }

  /**
   * Retorna Ano da competencia
   */
  public function getAnoCompetencia () {
    return $this->oServidor->getAnoCompetencia();
  }

  /**
   * Retorna Mes da competencia
   */
  public function getMesCompetencia () {
    return $this->oServidor->getMesCompetencia();
  }

  /**
   * Retorna o nome da tabela que se está efetuando o cálculo financeiro
   */
  public function getTabela () {
    return $this->sTabela;
  }

  /**
   * Função para gerar calculo para o mes selecionado
   */
  abstract public function gerar ();

  /**
   * Retorna as movimentações por rubrica e semestre
   *
   * @param null|integer $iSemestre
   * @param null|string $sRubrica
   * @return array
   * @throws DBException
   */
  public function getMovimentacoes( $iSemestre = null, $sRubrica = null) {

    $oDaoGeracaoFolha = db_utils::getDao($this->sTabela);
    $sWhere           = "    {$this->sSigla}_regist = {$this->oServidor->getMatricula()}                    ";
    $sWhere          .= "and {$this->sSigla}_anousu = {$this->oServidor->getAnoCompetencia()}               ";
    $sWhere          .= "and {$this->sSigla}_mesusu = {$this->oServidor->getMesCompetencia()}               ";
    $sWhere          .= "and {$this->sSigla}_instit = {$this->oServidor->getInstituicao()->getSequencial()} ";

    if (!empty($iSemestre)) {
      $sWhere        .= "and {$this->sSigla}_semest = {$iSemestre} ";
    }

    if (!empty($sRubrica)) {
      $sWhere .= "and {$this->sSigla}_rubric = '{$sRubrica}' ";
    }

    $sSql  = $oDaoGeracaoFolha->sql_query_file( null,
                                                null,
                                                null,
                                                null,
                                                " {$this->sSigla}_rubric as codigo_rubrica,
                                                  {$this->sSigla}_valor  as valor_rubrica,
                                                  {$this->sSigla}_pd     as provento_desconto,
                                                  {$this->sSigla}_quant  as quantidade_rubrica ",
                                                null,
                                                $sWhere);

    if ( $this->sTabela == 'gerfres' ) {

      $sSql  = $oDaoGeracaoFolha->sql_query_file( null,
                                                  null,
                                                  null,
                                                  null,
                                                  null,
                                                  " {$this->sSigla}_rubric as codigo_rubrica,
                                                    {$this->sSigla}_valor  as valor_rubrica,
                                                    {$this->sSigla}_pd     as provento_desconto,
                                                    {$this->sSigla}_quant  as quantidade_rubrica ",
                                                  null,
                                                  $sWhere);

    }

    $rsMovimentacoes = db_query($sSql);

    if ( !$rsMovimentacoes ) {
      throw new DBException(_M(self::MENSAGENS . "erro_buscar_movimentacoes"));
    }

    if ( pg_num_rows($rsMovimentacoes) == 0 ) {
      return array();
    }

    $aMovimentacoes  =  array();

    foreach ( db_utils::getCollectionbyRecord($rsMovimentacoes) as  $oMovimentacao ) {

      $oRetorno = new stdClass();
      $oRetorno->oRubrica          = new Rubrica($oMovimentacao->codigo_rubrica);
      $oRetorno->nQuantidade       = $oMovimentacao->quantidade_rubrica;
      $oRetorno->nValor            = $oMovimentacao->valor_rubrica;
      $oRetorno->iProventoDesconto = $oMovimentacao->provento_desconto;
      $aMovimentacoes[]            = $oRetorno;
    }

    return $aMovimentacoes;
  }

  /**
   * Retorna Array com os eventos financeiros do servidor
   *
   * @param null|integer $iSemestre
   * @param null|mixed $mRubrica
   * @return EventoFinanceiroFolha[]
   * @throws DBException
   */
  public function getEventosFinanceiros( $iSemestre = null, $mRubrica = null) {

    if (is_array($mRubrica)) {

      $aRubricas = array();

      // Verifica se existir um collection de objetos Rubrica transforma em array de strings
      foreach ($mRubrica as $oRubrica) {

        if ($oRubrica instanceof Rubrica) {
          $aRubricas[] = $oRubrica->getCodigo();
        }
      }

      if (!empty($aRubricas)) {
        $mRubrica = $aRubricas;
      }
    }

    return $this->__getEventosFinanceiros($iSemestre, $mRubrica);
  }

  /**
   * Retorna Array com os eventos financeiros do servidor
   *
   * @param null|integer $iSemestre
   * @param null|mixed $mRubrica
   * @return array
   * @throws DBException
   */
  protected function __getEventosFinanceiros($iSemestre = null, $mRubrica = null) {

    $oDaoGeracaoFolha = db_utils::getDao($this->sTabela);
    $sWhere           = "     {$this->sSigla}_regist = {$this->oServidor->getMatricula()}                    ";
    $sWhere          .= " and {$this->sSigla}_anousu = {$this->oServidor->getAnoCompetencia()}               ";
    $sWhere          .= " and {$this->sSigla}_mesusu = {$this->oServidor->getMesCompetencia()}               ";
    $sWhere          .= " and {$this->sSigla}_instit = {$this->oServidor->getInstituicao()->getSequencial()} ";

    if ( $iSemestre != "" ) {
      $sWhere .= " and {$this->sSigla}_semest = {$iSemestre} ";
    }

    // Verifica se foi informado alguma rúbrica
    if ( !empty($mRubrica) ) {

      $sWhere .= " and {$this->sSigla}_rubric ";

      if ( is_array($mRubrica) ) {

        $sRubrica = implode("','", $mRubrica);
        $sWhere  .= " in ('{$sRubrica}') ";
      } else {
        $sWhere .= " = '{$mRubrica}' ";
      }
    }

    switch ( $this->sTabela ) {

      default :

        $sSql  = $oDaoGeracaoFolha->sql_query_file( null,
                                                    null,
                                                    null,
                                                    null,
                                                    " {$this->sSigla}_rubric as codigo_rubrica,
                                                      {$this->sSigla}_valor  as valor_rubrica,
                                                      {$this->sSigla}_pd     as provento_desconto,
                                                      {$this->sSigla}_quant  as quantidade_rubrica ",
                                                    null,
                                                    $sWhere);
      break;

      case CalculoFolha::CALCULO_RESCISAO :
      case CalculoFolha::CALCULO_PROVISAO_FERIAS :
      case CalculoFolha::CALCULO_FERIAS :

        $sSql  = $oDaoGeracaoFolha->sql_query_file( null,
                                                    null,
                                                    null,
                                                    null,
                                                    null,
                                                    " {$this->sSigla}_rubric as codigo_rubrica,
                                                      {$this->sSigla}_valor  as valor_rubrica,
                                                      {$this->sSigla}_pd     as provento_desconto,
                                                      {$this->sSigla}_quant  as quantidade_rubrica ",
                                                    null,
                                                    $sWhere);


      break;
    }

    $rsMovimentacoes = db_query($sSql);

    if ( !$rsMovimentacoes ) {
      throw new DBException(pg_last_error() . _M(self::MENSAGENS . 'erro_buscar_movimentacoes'));
    }

    $aMovimentacoes  =  array();

    for( $iEvento = 0; $iEvento < pg_num_rows($rsMovimentacoes); $iEvento++ ) {

      $oMovimentacao = db_utils::fieldsMemory($rsMovimentacoes, $iEvento);
      $oEvento       = new EventoFinanceiroFolha();
      $oRubrica      = RubricaRepository::getInstanciaByCodigo($oMovimentacao->codigo_rubrica, $this->getServidor()->getInstituicao()->getCodigo());

      $oEvento->setServidor($this->oServidor);
      $oEvento->setRubrica($oRubrica);
      $oEvento->setQuantidade($oMovimentacao->quantidade_rubrica);
      $oEvento->setValor($oMovimentacao->valor_rubrica);
      $oEvento->setNatureza($oMovimentacao->provento_desconto);
      $oEvento->setCalculo($this);

      $aMovimentacoes[] = $oEvento;
    }

    return $aMovimentacoes;
  }

  /**
   * Retorna Array com os eventos financeiros do servidor
   *
   * @param integer $iTipoFolha
   * @param null|integer $iSemestre
   * @return array
   * @throws DBException
   */
  public function getEventosFinanceirosHistorico($iTipoFolha, $iSemestre = null) {

    $oDaoHistorico = new cl_rhhistoricocalculo;

    $aCampo[] = 'rh143_rubrica    AS rubrica';
    $aCampo[] = 'rh143_valor      AS valor';
    $aCampo[] = 'rh143_tipoevento AS tipo';
    $aCampo[] = 'rh143_quantidade AS quantidade';

    $aWhere[] = "rh143_regist    = {$this->oServidor->getMatricula()}";
    $aWhere[] = "rh141_anousu    = {$this->oServidor->getAnoCompetencia()}";
    $aWhere[] = "rh141_mesusu    = {$this->oServidor->getMesCompetencia()}";
    $aWhere[] = "rh141_instit    = {$this->oServidor->getInstituicao()->getSequencial()}";
    $aWhere[] = "rh141_tipofolha = {$iTipoFolha}";

    if ($iSemestre) {
      $aWhere[] = "rh141_codigo  = {$iSemestre}";
    }

    $sWhere = implode(' AND ', $aWhere);
    $sCampo = implode(', ', $aCampo);

    $sSql        = $oDaoHistorico->sql_query(null, $sCampo, null, $sWhere);
    $rsHistorico = db_query($sSql);

    if (!$rsHistorico) {
      throw new DBException(_M(self::MENSAGENS . 'erro_buscar_movimentacoes'));
    }

    if (pg_num_rows($rsHistorico) == 0) {
      return array();
    }

    $aEvento    = array();
    $aHistorico = db_utils::getCollectionbyRecord($rsHistorico);

    foreach ($aHistorico as $oHistorico) {

      $oEvento  = new EventoFinanceiroFolha();
      $oRubrica = RubricaRepository::getInstanciaByCodigo($oHistorico->rubrica);

      $oEvento->setServidor($this->oServidor);
      $oEvento->setRubrica($oRubrica);
      $oEvento->setQuantidade($oHistorico->quantidade);
      $oEvento->setValor($oHistorico->valor);
      $oEvento->setNatureza($oHistorico->tipo);

      $aEvento[] = $oEvento;
    }

    return $aEvento;
  }

  /**
   * Função para retornar as rubricas utilizadas no calculo
   *
   * @return array
   * @throws Exception
   */
  public function getRubricas() {


    $oDaoRhrubricas = db_utils::getDao('rhrubricas');
    $sSql           = $oDaoRhrubricas->sql_queryRubricas( $this->oServidor->getMatricula(),
                                                          $this->sTabela,
                                                          $this->sSigla,
                                                          $this->oServidor->getMesCompetencia(),
                                                          $this->oServidor->getAnoCompetencia() );
    $rsRubricas = db_query($sSql);

    if ( !$rsRubricas ) {
      throw new Exception("Erro ao buscar rubricas da competencia: {$this->oServidor->getMesCompetencia()} / {$this->oServidor->getAnoCompetencia()}");
    }

    if(pg_num_rows($rsRubricas) == 0) {
      return array();
    }

    $aRubricas = array();

    foreach(db_utils::getCollectionByRecord($rsRubricas) as $oRubrica) {
      $aRubricas[] = RubricaRepository::getInstanciaByCodigo($oRubrica->codigo_rubrica);
    }

    return $aRubricas;
  }

  /**
   * Limpa a tabela do calculo.
   *
   * @param null|string $sRubrica
   * @return bool
   * @throws Exception
   */
  public function limpar($sRubrica = null) {
    $iAnoCompetencia = $this->getServidor()->getAnoCompetencia();
    $iMesCompetencia = $this->getServidor()->getMesCompetencia();
    $iMatricula      = $this->getServidor()->getMatricula();

    $oDaoCalculo = db_utils::getDao($this->sTabela);
    $sWhere  = "    {$this->sSigla}_instit = ".db_getsession('DB_instit');
    $sWhere .= "and {$this->sSigla}_regist = $iMatricula ";
    $sWhere .= "and {$this->sSigla}_anousu = $iAnoCompetencia ";
    $sWhere .= "and {$this->sSigla}_mesusu = $iMesCompetencia ";

    if (!empty($sRubrica)){
      $sWhere .= "and {$this->sSigla}_rubric = '$sRubrica' ";
    }

    if($this->sTabela == CalculoFolha::CALCULO_RESCISAO || $this->sTabela == CalculoFolha::CALCULO_FERIAS) {
      $oDaoCalculo->excluir(null,null,null,null,null,$sWhere);
    } else {
      $oDaoCalculo->excluir(null,null,null,null,$sWhere);
    }


    /**
     * Erro ao excluir registro
     */
    if ( $oDaoCalculo->erro_status == "0" ) {
      throw new Exception($oDaoCalculo->erro_msg);
    }

    $this->aRegistros = array();
    return true;
  }

  /**
   * Carrega os eventos do financeiro
   *
   * @return array
   * @throws DBException
   */
  public function carregarEventos() {
    return $this->aRegistros = $this->getEventosFinanceiros();
  }

  /**
   * Adiciona um evento financeiro da folha
   *
   * @param EventoFinanceiroFolha $oEvento
   */
  public function adicionarEvento( EventoFinanceiroFolha $oEvento ) {

    $this->aRegistros[] = $oEvento;
  }

  /**
   * Salva os dados da folha
   *
   * @return bool
   * @throws Exception
   */
  public function salvar() {

    $oDaoFolha = db_utils::getDao($this->sTabela);

    foreach ( $this->aRegistros as $oRegistro ) {

      $oDaoFolha->{"{$this->sSigla}_valor"}  = "{$oRegistro->getValor()}";      //Forçando ser string por causa do DAO
      $oDaoFolha->{"{$this->sSigla}_pd"}     = $oRegistro->getNatureza();
      $oDaoFolha->{"{$this->sSigla}_quant"}  = "{$oRegistro->getQuantidade()}"; //Forçando ser string por causa do DAO
      $oDaoFolha->{"{$this->sSigla}_lotac"}  = $oRegistro->getServidor()->getCodigoLotacao();
      $oDaoFolha->{"{$this->sSigla}_semest"} = "0";
      $oDaoFolha->{"{$this->sSigla}_instit"} = $oRegistro->getServidor()->getInstituicao()->getSequencial();

      if($this->sTabela == CalculoFolha::CALCULO_RESCISAO || $this->sTabela == CalculoFolha::CALCULO_FERIAS) {

        $oDaoFolha->{"{$this->sSigla}_tpp"}    = $oRegistro->getTipoParaPagamento();

        $oDaoFolha->incluir(
          $this->getAnoCompetencia(),
          $this->getMesCompetencia(),
          $oRegistro->getServidor()->getMatricula(),
          $oRegistro->getRubrica()->getCodigo(),
          $oRegistro->getTipoParaPagamento()
        );

      } else {

        $oDaoFolha->incluir(
          $this->getAnoCompetencia(),
          $this->getMesCompetencia(),
          $oRegistro->getServidor()->getMatricula(),
          $oRegistro->getRubrica()->getCodigo()
        );
      }

      if ( $oDaoFolha->erro_status == "0" ) {
        throw new Exception($oDaoFolha->erro_msg);
      }

    }

    return true;
  }


  /**
   * Pre calculo da folha
   *
   * @param string $sTipoFolha
   * @return FolhaPagamentoComplementar|FolhaPagamentoSalario|FolhaPagamentoSuplementar
   * @throws BusinessException
   * @throws DBException
   */
  public static function preCalcular( $sTipoFolha, $sMatriculas = '')  {

    $lFolhaAberta = false;

    switch ($sTipoFolha) {

      case self::CALCULO_SALARIO  :

        $sClass = "CalculoFolhaSalario";
        $oFolha = FolhaPagamentoSalario::getFolhaAberta();

        if (!$oFolha) {

          $oFolha = FolhaPagamentoSuplementar::getFolhaAberta();

          if (!$oFolha) {
            throw new BusinessException(_M(self::MENSAGENS . 'nao_existe_folha_salario_aberta'));
          }

          $aFolhasFechadasCompetencia        = FolhaPagamentoSuplementar::getFolhasFechadasCompetencia($oFolha->getCompetencia());
          $aFolhasFechadasCompetenciaSalario = FolhaPagamentoSalario::getFolhasFechadasCompetencia($oFolha->getCompetencia());
          $aFolhasFechadasCompetencia        = array_merge($aFolhasFechadasCompetencia,$aFolhasFechadasCompetenciaSalario);

        } else {
          $aFolhasFechadasCompetencia = FolhaPagamentoSalario::getFolhasFechadasCompetencia($oFolha->getCompetencia());
        }
      break;
      case self::CALCULO_COMPLEMENTAR:

        $sClass                     = "CalculoFolhaComplementar";
        $oFolha                     = FolhaPagamentoComplementar::getFolhaAberta();
        $oCompetencia               = DBPessoal::getCompetenciaFolha();
        $aFolhasFechadasCompetencia = array();

        if (!$oFolha) {
          throw new BusinessException(_M(self::MENSAGENS . 'nao_existe_folha_complementar_aberta'));
        }

        $oCompetencia               = $oFolha->getCompetencia();
        $aFolhasFechadasCompetencia = FolhaPagamentoComplementar::getFolhasFechadasCompetencia($oCompetencia);
      break;

      default:
        return true;
      break;
    }

    if ( !( $oFolha instanceof FolhaPagamento ) ) {
      throw new BusinessException(_M(self::MENSAGENS . 'nao_existe_folha_aberta'));
    }

    $aServidoresHistoricoPonto = ServidorRepository::getServidoresNoPontoPorFolhaPagamento($oFolha, false, $sMatriculas);
    $aServidoresDuploVinculo   = ServidorRepository::getServidoresNoPontoPorFolhaPagamento($oFolha, true, $sMatriculas);

    $oFolha->salvarHistoricoPonto( $aServidoresHistoricoPonto );//ServidorRepository::getServidoresNoPontoPorFolhaPagamento($oFolha) );

    /**
     * Sempre será todas as fechadas mais a folha que estou calculando.
     */
    $aFolhasFechadasCompetencia[] = $oFolha;

    foreach ($aServidoresDuploVinculo  as $oServidor ) {

      $oPonto = $oServidor->getPonto($oFolha->getTabelaPonto());
      $oPonto->limpar();
      $oPonto->carregarRegistros();

      foreach ( $aFolhasFechadasCompetencia as $oFolhaPagamento ) {

        foreach ($oFolhaPagamento->getHistoricoRegistrosPonto($oServidor) as $oRegistro ) {
          $oPonto->adicionarRegistro($oRegistro, false);
        }
      }
      $oPonto->salvar();
    }

    /**
     *  Separa as matriculas que foram virtualmente utilizadas no cálculo, para que sejam excluidos os dados do ponto na
     *  função FolhaPagamento::retornarPonto
     *  @todo  verificar esses 2 foreach, quando é calculo individual o primeiro é utilizado, cálculo geral o segundo é utilizado.
     */

    $aMatriculasSelecionadas      = array();
    $aMatriculasUtilizadasCalculo = explode(',', $sMatriculas);

    foreach ($aMatriculasUtilizadasCalculo as $iMatricula) {

      if ( empty($iMatricula) ) {
        continue;
      }

      $aMatriculasSelecionadas[$iMatricula] = $iMatricula;
    }

    foreach ($aServidoresDuploVinculo as $oServidor) {

      $iMatricula = $oServidor->getMatricula();

      if ( empty($iMatricula) ) {
        continue;
      }

      $aMatriculasSelecionadas[$oServidor->getMatricula()] = $oServidor->getMatricula();
    }

    self::$aMatriculasExcluirHistoricoPonto = array_diff(array_keys($aMatriculasSelecionadas), array_keys($aServidoresHistoricoPonto));

    $oRetorno              = new stdClass();
    $oRetorno->oFolha      = $oFolha;
    $oRetorno->aServidoresHistoricoPonto = $aServidoresHistoricoPonto;
    $oRetorno->aServidoresDuploVinculo   = $aServidoresDuploVinculo;
    $oRetorno->aMatriculasSelecionadas   = $aMatriculasSelecionadas;

    return $oRetorno;
  }

  /**
   * Executa pós cálculo da folha de pagamento
   *
   * @param object $oFolha
   * @param array $aServidoresCalculo - Servidores que participaram dos calculo(Eles mesmos+duplo vinculo)
   * @param array $aServidoresPonto   - Servidores que deveriam estar ponto no momento do calculo
   * @return bool|string
   * @throws DBException
   */
  public static function posCalcular($oFolha, $aServidoresCalculo, $oDadosFolha) {

    $aMatriculasSelecionadas = $oDadosFolha->aMatriculasSelecionadas;

    /**
     * Exclui o historico de calculo das matriculas calculadas
     */
    if ( count($aMatriculasSelecionadas) > 0 ) {

      $sMatriculasCalculo     = implode(',', $aMatriculasSelecionadas);
      $oDaoRhHistoricoCalculo = new cl_rhhistoricocalculo();

      $oDaoRhHistoricoCalculo->excluir(null, "rh143_folhapagamento = {$oFolha->getSequencial()} and rh143_regist in ({$sMatriculasCalculo})");

      if ( $oDaoRhHistoricoCalculo->erro_status == 0) {
        throw new DBException($oDaoRhHistoricoCalculo->erro_msg);
      }
    }
    /**
     *  Separa as matriculas que foram virtualmente utilizadas no cálculo, para que sejam excluidos os dados do ponto na
     *  função FolhaPagamento::retornarPonto
     */
    if ( count(self::$aMatriculasExcluirHistoricoPonto) > 0 ) {

      $sMatriculasPonto     = implode(',', self::$aMatriculasExcluirHistoricoPonto);
      $oDaoRhHistoricoPonto = new cl_rhhistoricoponto();
      $oDaoRhHistoricoPonto->excluir(null, "rh144_folhapagamento = {$oFolha->getSequencial()} and rh144_regist in ({$sMatriculasPonto})");

      if ( $oDaoRhHistoricoPonto->erro_status == 0) {
        throw new DBException($oDaoRhHistoricoPonto->erro_msg);
      }
    }
    /**
     * Busca as Folha de Pagamento que Estão fechadas(salaário/Complementar)
     */
    $aFolhasFechadasCompetencia   = FolhaPagamento::getFolhasFechadasCompetencia($oFolha->getCompetencia(), $oFolha->getTipoFolha());

    if ( $oFolha->getTipoFolha() == FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR ) {

      $aFolhasSalario             = FolhaPagamento::getFolhasFechadasCompetencia($oFolha->getCompetencia(), FolhaPagamento::TIPO_FOLHA_SALARIO );
      $aFolhasFechadasCompetencia = array_merge($aFolhasFechadasCompetencia, $aFolhasSalario);
    }

    $aServidores = ServidorRepository::getServidoresNoCalculoPorFolhaPagamento($oFolha, $aMatriculasSelecionadas );

    /**
     * Percorremos os servidores que foram calculados
     */
    foreach ($aServidores  as $oServidor ) {

      /**
       * Armazena em memória os eventos resultantes do calculo atual
       * Porem este array não é associativo
       */
      $oCalculoAtual                = $oServidor->getCalculoFinanceiro($oFolha->getTabelaCalculo());

      $aEventosFinanceirosResultado = $oCalculoAtual->getEventosFinanceiros();
      $aEventosFinanceirosAtuais    = $oCalculoAtual->getEventosFinanceiros();
      $aEventosAtuaisAssociados     = array();

      for ( $iIndiceEvento = 0; $iIndiceEvento < count($aEventosFinanceirosAtuais); $iIndiceEvento++ ) {

        $oEventoAtual                             = $aEventosFinanceirosAtuais[$iIndiceEvento];
        $sCodigoRubrica                           = $oEventoAtual->getRubrica()->getCodigo();

        $aEventosAtuaisAssociados[$sCodigoRubrica]= $oEventoAtual;
      }
      /**
       * Agora os eventos financeiros da folha atual estão em um array associativo para facilitar as buscas
       * dos registros fechados
       * Esse que será gravado no banco
       */
      $aEventosFinanceirosAtuais  = $aEventosAtuaisAssociados;
      unset($aEventosAtuaisAssociados);

      $aEventosFinanceirosFechados = array();

      /**
       * Limpa a tabela do Calculo
       */
      $oCalculoAtual->limpar();

      /**
       * Percorre as folhas fechadas
       */
      foreach ( $aFolhasFechadasCompetencia as $oFolhaFechada ) {
        /**
         * Percorre os eventos financeiros diminuindo os valores quando houver
         */
        foreach ($oFolhaFechada->getHistoricoEventosFinanceiros($oServidor) as $oEventoHistorico ) {
          $aEventosFinanceirosFechados[] = $oEventoHistorico;
        }
      }

      foreach ( $aEventosFinanceirosFechados as $oEventoFechado ) {

        $sRubricaFechada = $oEventoFechado->getRubrica()->getCodigo();

        if ( !array_key_exists($sRubricaFechada, $aEventosFinanceirosAtuais) ) {

          /**
           * Quando não houver no atual e existir no historico
           */
          continue;
        }

        $oEventoAtual  = $aEventosFinanceirosAtuais[$sRubricaFechada];
        $nValorAtual   = $oEventoAtual->getValor();
        $nValorFechado = $oEventoFechado->getValor();

        /**
         * Verifica se é cálculo da folha suplementar e está percorrenco a rubrica R997
         */
        if ( $oFolha->getTipoFolha() == FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR ) {

          if($sRubricaFechada == 'R997') {

            global $D902;

            /**
             * Verifica se o valor para a rubrica é maior ou igual ao teto de dedução
             * Se for verifica se o servidor é duplo vínculo, se for verifica idade e
             * vínculo, inativo ou pensionista com mais de 65 anos não salva no histórico
             * o valor da rubrica para que a reconstrução da gerfsal ocorra com o valor correto
             */
            if( $nValorAtual >= $D902 && $oServidor->hasServidorVinculado() ) {

              $oServidorVinculado        = $oServidor->getServidorVinculado();
              $sVinculoServidorVinculado = $oServidor->getVinculo()->getTipo();

              if(     $oServidorVinculado->getIdade() >= 65
                  && ($sVinculoServidorVinculado == VinculoServidor::VINCULO_INATIVO || $sVinculoServidorVinculado == VinculoServidor::VINCULO_PENSIONISTA))
              {
                unset($aEventosFinanceirosAtuais[$sRubricaFechada]);
                continue;
              }
            }
          }
        }

        if ( $nValorAtual <= $nValorFechado ) {
          unset($aEventosFinanceirosAtuais[$sRubricaFechada]);
        } elseif ( $nValorAtual > $nValorFechado ) {
           $oEventoAtual->setValor($nValorAtual -  $nValorFechado);
        }
      }
      /**
       * Percorre persistindo os dados no banco
       */
      foreach ($aEventosFinanceirosAtuais as $oEvento ) {
        $oCalculoAtual->adicionarEvento($oEvento);
      }

      $oCalculoAtual->salvar();
      $oServidor->getPonto($oFolha->getTabelaPonto())->limpar();
      $oFolha->salvarHistoricoCalculo(array($oServidor));
      $oCalculoAtual->limpar();
    }


    if ($oFolha->getTipoFolha() == FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR) {

      $oDaoRhHistoricoCalculo = new cl_rhhistoricocalculo();
      $oDaoGerfcom            = new cl_gerfcom();
      $sWhereGerfCom          = "     r48_anousu = ". $oFolha->getCompetencia()->getAno();
      $sWhereGerfCom         .= " and r48_mesusu = ". $oFolha->getCompetencia()->getMes();

      $sSqlDadosConsolidados = $oDaoRhHistoricoCalculo->sql_query_dados_consolidados(null, $oFolha);

      if(!empty($aMatriculasSelecionadas) && count($aMatriculasSelecionadas) > 0) {

        $sWhereGerfCom         .= " and r48_regist in (". implode(",", $aMatriculasSelecionadas) .")";
        $sSqlDadosConsolidados  = $oDaoRhHistoricoCalculo->sql_query_dados_consolidados($aMatriculasSelecionadas, $oFolha);

      }

      $oDaoGerfcom->excluir(null, null, null, null, $sWhereGerfCom);
      $rsGerfcom             = db_query("insert into gerfcom ". $sSqlDadosConsolidados);
      if(!$rsGerfcom){
        throw new BusinessException(pg_last_error());
      }
    }

    /**
     * Recria gerfsal com base  no histórico
     */
    if (  $oFolha->getTipoFolha() == FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR ||
          $oFolha->getTipoFolha() == FolhaPagamento::TIPO_FOLHA_SALARIO ) {

      $oDaoHistorico = new cl_rhhistoricocalculo();
      $oDaoGerfsal   = new cl_gerfsal();

      $sWhere  = "     r14_instit = ".db_getsession('DB_instit');
      $sWhere .= " and r14_anousu = {$oFolha->getCompetencia()->getAno()} ";
      $sWhere .= " and r14_mesusu = {$oFolha->getCompetencia()->getMes()} ";

      $sSqlDadosConsolidados = $oDaoHistorico->sql_query_dados_consolidados(null, $oFolha);

      if(!empty($aMatriculasSelecionadas) && count($aMatriculasSelecionadas) > 0 && $oFolha->getTipoFolha() == FolhaPagamento::TIPO_FOLHA_SALARIO) {

        $sWhere               .= " and r14_regist in (". implode(",", $aMatriculasSelecionadas) .") ";
        $sSqlDadosConsolidados = $oDaoHistorico->sql_query_dados_consolidados($aMatriculasSelecionadas, $oFolha);
      }

      $sSqlGerfsal           = $oDaoGerfsal->excluir(null, null, null, null, $sWhere);
      $rsGerfSal             = db_query("insert into gerfsal ". $sSqlDadosConsolidados ."--", null, "SQL", true);

      if(!$rsGerfSal){
        throw new BusinessException(pg_last_error());
      }
    }

    $oFolha->retornarPonto($aMatriculasSelecionadas );

    $aServidoresRelatorio    = array();
    $aServidoresSelecionados = array();
    $iAnoUsu = $oFolha->getCompetencia()->getAno();
    $iMesUsu = $oFolha->getCompetencia()->getMes();

    /**
     * Percorre os servidores selecionados para realizar o calculo para verificar
     * se o total de proventos é menor que o total de desconto,
     * caso seja adiciona ao servidor ao array para geração do relatório de inconcistências
     */
    foreach ($aServidoresCalculo as $aServidor) {

      $iMatricula                = $aServidor['r01_regist'];
      $aServidoresSelecionados[] = $iMatricula;
      $lServidorValido           = CalculoFolha::verificaValoresValidosFolha($oFolha->getSequencial(),
                                                                             $aServidor['r01_regist']);

      if (!$lServidorValido) {

        $oServidor = ServidorRepository::getInstanciaByCodigo($iMatricula, $iAnoUsu, $iMesUsu);
        $aServidor['nome']      = $oServidor->getCgm()->getNome();
        $aServidor['mensagem']  = 'Servidor com líquido negativo';
        $aServidoresRelatorio[] = $aServidor;
      }
    }

    return true;
  }

  /**
   * Verifica se o total de proventos, da folha do servidor informado como parâmetro,
   * não é menor que o seu total de desconto caso isso ocorra é excluido os dados de
   * cálculo e retorna false.
   *
   * @param integer $iCodigoFolha
   * @param integer $iMatricula
   * @return bool
   * @throws DBException
   */
  public static function verificaValoresValidosFolha($iCodigoFolha, $iMatricula){

    $oDaoHistoricoCalculo = new cl_rhhistoricocalculo();

    /**
     * Total de proventos da folha.
     */
    $sSqlProventosFolha = $oDaoHistoricoCalculo->sql_query_proventos_folha($iCodigoFolha, $iMatricula);
    $rsProventosFolha   = db_query($sSqlProventosFolha);

    if(!$rsProventosFolha || pg_num_rows($rsProventosFolha) == 0 ) {
      throw new DBException("Ocorreu um erro ao buscar os proventos da folha");
    }

    $oTotalProventos    = db_utils::fieldsMemory($rsProventosFolha, 0);

    /**
     * Total de descontos da folha.
     */
    $sSqlDescontosFolha = $oDaoHistoricoCalculo->sql_query_descontos_folha($iCodigoFolha, $iMatricula);
    $rsDescontosFolha   = db_query($sSqlDescontosFolha);

    if(!$rsDescontosFolha || pg_num_rows($rsDescontosFolha) == 0 ) {
      throw new DBException("Ocorreu um erro ao buscar os descontos da folha");
    }

    $oTotalDescontos    = db_utils::fieldsMemory($rsDescontosFolha, 0);

    /**
     * Se o total de proventos for menor que o total de descontos,
     * remove o calculo da folha atual
     */
    if ($oTotalProventos->totalproventos < $oTotalDescontos->totaldescontos) {

      $oDaoHistoricoCalculo->excluir(null, "rh143_folhapagamento = {$iCodigoFolha} and rh143_regist = {$iMatricula}");

      if ($oDaoHistoricoCalculo->erro_status == 0) {
        throw new DBException(_M(self::MENSAGENS . "erro_excluir_historico_calculo"));
      }

      return false;
    }
    return true;
  }

  /**
   * Gera o relatório com as inconsistências encontradas
   *
   * @param array $aServidores
   * @param integer $iAnoUsu
   * @param integer $iMesUsu
   *
   * @return string
   */
  public static function geraRelatoriosInconsistencias($aServidores, $iAnoUsu, $iMesUsu) {

    global $head1, $head3;

    $head1 = "RELATÓRIO DE INCONSISTÊNCIAS";
    $head3 = "PERÍODO : {$iAnoUsu} / {$iMesUsu}";

    /**
     * Configurações do PDF
     */
    $oPdf = new PDF();
    $oPdf->Open();
    $oPdf->AliasNbPages();
    $oPdf->setfillcolor(235);
    $oPdf->addpage();
    $oPdf->setfont('arial', 'b', 8);
    $oPdf->cell(15, 5, 'Matrícula', 1, 0, "C", 1);
    $oPdf->cell(80, 5, 'Nome', 1, 0, "C", 1);
    $oPdf->cell(95, 5, 'Motivo', 1, 1, "C", 1);
    $oPdf->setfont('arial', '', 8);

    foreach ($aServidores as $aServidor) {

      $oPdf->cell(15, 5, $aServidor['r01_regist'], 1, 0, "C", 0);
      $oPdf->cell(80, 5, $aServidor['nome'],       1, 0, "L", 0);
      $oPdf->cell(95, 5, $aServidor['mensagem'],   1, 1, "L", 0);
    }

    $sArquivo = "tmp/inconsistencias_" . date('YmdHis') . ".pdf";
    $oPdf->Output($sArquivo, false, true);

    return $sArquivo;
  }

  public static function hasCalculo(FolhaPagamento $oFolhaPagamento) {

    $oDaoFolha        = db_utils::getDao($oFolhaPagamento->getTabelaCalculo());
    $sWhere           = "    {$oFolhaPagamento->getSiglaCalculo()}_anousu = {$oFolhaPagamento->getCompetencia()->getAno()}        ";
    $sWhere          .= "and {$oFolhaPagamento->getSiglaCalculo()}_mesusu = {$oFolhaPagamento->getCompetencia()->getMes()}        ";
    $sWhere          .= "and {$oFolhaPagamento->getSiglaCalculo()}_instit = {$oFolhaPagamento->getInstituicao()->getSequencial()} ";

    $sSql  = $oDaoFolha->sql_query_file( null,
                                          null,
                                          null,
                                          null,
                                          "*",
                                          null,
                                          $sWhere);

    if ( $oFolhaPagamento->getTabelaCalculo() == self::CALCULO_RESCISAO ) {

      $sSql  = $oDaoFolha->sql_query_file( null,
                                           null,
                                           null,
                                           null,
                                           null,
                                           "*",
                                           null,
                                           $sWhere);

    }

    $rsCalculo = db_query($sSql);

    if ( !$rsCalculo ) {
      throw new DBException(_M(self::MENSAGENS . "erro_calculo"));
    }

    if(pg_num_rows($rsCalculo) > 0) {
      return true;
    } else {
      return false;
    }

  }

  /**
   * Método responsável por processar a integridade do histórico cálculo no histórico ponto.
   * Senão existir eventos financeiros no histórico ponto, os eventos financeiros do histórico cálculo serão deletados.
   *
   * @static
   * @access public
   * @param FolhaPagamento $oFolhaPagamento
   * @throws DBException
   */
  public static function processarIntegridadeHistoricoCalculo(FolhaPagamento $oFolhaPagamento, $aServidoresCalcular = null) {

    $aServidoresCalculo = ServidorRepository::getServidoresHistoricoCalculo($oFolhaPagamento, $aServidoresCalcular);

    if (!empty($aServidoresCalculo)) {

      foreach ($aServidoresCalculo as $oServidor) {

        $aEventosFinanceirosPonto = $oFolhaPagamento->getHistoricoRegistrosPonto($oServidor);

        if (empty($aEventosFinanceirosPonto)) {

          $aEventosFinanceirosCalculo = $oFolhaPagamento->getHistoricoEventosFinanceiros($oServidor);

          foreach ($aEventosFinanceirosCalculo as $oEventoFinanceiroCalculo) {
            $oFolhaPagamento->excluirRubricaHistoricoCalculo($oEventoFinanceiroCalculo->getServidor()->getMatricula(),
                                                             $oEventoFinanceiroCalculo->getRubrica()->getCodigo());
          }
        }
      }
    }
  }

  /**
   * Verifica a ocorrencia e o valor da parcela de isenção para o servidor vinculado
   *
   * @param  Servidor $oServidorAtual
   *
   * @return mixed false|Number
   */
  public function verificarParcelaIsentaAposentadoPensionistaServidorVinculado(Servidor $oServidorAtual, $sRubrica) {

    $mValorVinculado = false;

    switch ($this->getTabela()) {

      case CalculoFolha::CALCULO_COMPLEMENTAR:
      case CalculoFolha::CALCULO_SALARIO:
      case CalculoFolha::CALCULO_RESCISAO:

        if($oServidorAtual->hasVinculadoInativoPensionistaMaior65Anos()) {

          $oServidorVinculado                               = $oServidorAtual->getServidorVinculado();

          if($oServidorVinculado->getCalculoFinanceiro(CalculoFolha::CALCULO_COMPLEMENTAR) instanceof CalculoFolha) {
            $aEventosFinanceirosComplementarServidorVinculado = $oServidorVinculado->getCalculoFinanceiro(CalculoFolha::CALCULO_COMPLEMENTAR)->getEventosFinanceiros(null, $sRubrica);
            $mValorVinculado = 0;
          }

          $sCalculoTipo = CalculoFolha::CALCULO_SALARIO;
          if($this->getTabela() == CalculoFolha::CALCULO_RESCISAO) {
            $sCalculoTipo = CalculoFolha::CALCULO_RESCISAO;
          }

          if($oServidorVinculado->getCalculoFinanceiro($sCalculoTipo) instanceof CalculoFolha) {
            $aEventosFinanceirosSalarioServidorVinculado      = $oServidorVinculado->getCalculoFinanceiro($sCalculoTipo)->getEventosFinanceiros(null, $sRubrica);
            $mValorVinculado = 0;
          }

          LogCalculoFolha::write('');
          if(!empty($aEventosFinanceirosComplementarServidorVinculado) && count($aEventosFinanceirosComplementarServidorVinculado) > 0) {

            LogCalculoFolha::write("Verificando eventos financeiros de complementar do servidor vinculado.");
            $oEventoFinanceiroComplementarServidorVinculado = $aEventosFinanceirosComplementarServidorVinculado[0];
            $mValorVinculado                               += $oEventoFinanceiroComplementarServidorVinculado->getValor();
            LogCalculoFolha::write('Valor da isencao da folha complementar do servidor vinculado.........: ' . $oEventoFinanceiroComplementarServidorVinculado->getValor());
          }

          if(!empty($aEventosFinanceirosSalarioServidorVinculado) && count($aEventosFinanceirosSalarioServidorVinculado) > 0) {
            $oEventoFinanceiroSalarioServidorVinculado = $aEventosFinanceirosSalarioServidorVinculado[0];
            $mValorVinculado                          += $oEventoFinanceiroSalarioServidorVinculado->getValor();
            LogCalculoFolha::write('Valor da isencao da folha de salário/rescisão do servidor vinculado..: ' . $oEventoFinanceiroSalarioServidorVinculado->getValor());
          }
          LogCalculoFolha::write('Valor total da isencao para o servidor vinculado ....................: ' . $mValorVinculado);
          LogCalculoFolha::write('');
        }
        break;

      case CalculoFolha::CALCULO_13o:

        if($oServidorAtual->hasVinculadoInativoPensionistaMaior65Anos()) {

          $oServidorVinculado                               = $oServidorAtual->getServidorVinculado();

          if($oServidorVinculado->getCalculoFinanceiro(CalculoFolha::CALCULO_13o) instanceof CalculoFolha) {
            $aEventosFinanceirosDecimoServidorVinculado      = $oServidorVinculado->getCalculoFinanceiro(CalculoFolha::CALCULO_13o)->getEventosFinanceiros(null, $sRubrica);
            $mValorVinculado = 0;
          }

          LogCalculoFolha::write('');

          if(!empty($aEventosFinanceirosDecimoServidorVinculado) && count($aEventosFinanceirosDecimoServidorVinculado) > 0) {
            $oEventoFinanceiroDecimoServidorVinculado = $aEventosFinanceirosDecimoServidorVinculado[0];
            $mValorVinculado                          += $oEventoFinanceiroDecimoServidorVinculado->getValor();
            LogCalculoFolha::write('Valor da isencao da folha de 13 salário do servidor vinculado........: ' . $oEventoFinanceiroDecimoServidorVinculado->getValor());
          }
          LogCalculoFolha::write('Valor total da isencao para o servidor vinculado ....................: ' . $mValorVinculado);
          LogCalculoFolha::write('');
        }

        break;
    }

    return $mValorVinculado;
  }

  /**
   * Calcula o valor da parcela de isenção considerando valor de servidor vinculado
   * quando existir
   *
   * @param  Number $nValorIsencao   Valor do teto da Isenção normalmente o valor da global diversos $D902
   * @param  Number $nValorAtual     O valor de isenção para a matricula atual
   * @param  Number $nValorVinculado O valor de isenção para a matrícula vinculada
   *
   * @return Number
   */
  public function calcularParcelaIsentaAposentadoPensionista($nValorTeto, $nValorMaximoAtual, $nValorAtual, $nValorVinculado = null) {

    LogCalculoFolha::write('');
    LogCalculoFolha::write(' -------- Calculando valor da parcela isenta de aposentado/pensionista com mais de 65 anos --------------');
    LogCalculoFolha::write('');
    LogCalculoFolha::write('Parametros passados:');
    LogCalculoFolha::write('Valor do teto.....................................................: '.$nValorTeto);
    LogCalculoFolha::write('Valor maximo para o servidor atual................................: '.$nValorMaximoAtual);
    LogCalculoFolha::write('Valor do servidor atual...........................................: '.$nValorAtual);
    LogCalculoFolha::write('Valor do servidor vinculado.......................................: '.$nValorVinculado);

    if(empty($nValorAtual)) {
      $nValorAtual   = 0;
    }

    $nValorComplementarAtual = 0;

    if($nValorMaximoAtual != $nValorAtual) {
      $nValorComplementarAtual = $nValorMaximoAtual - $nValorAtual;
    }

    /**
     * Verifica se foi passado valor de isenção para servidor vinculado
     */
    if(!empty($nValorVinculado)) {

      /**
       * Verifica se o valor passado referente ao servidor vinculado
       * é igual ou maior que a isenção se for o atual fica zerado
       */
      if($nValorVinculado >= $nValorTeto) {
        LogCalculoFolha::write('Valor vinculado maior que teto, zerando o valor do servidor atual: 0');
        return 0;
      }

      /**
       * Verifica se a soma do valor do vinculado e o maximo do atual for maior que o teto
       */
      if( ($nValorVinculado + $nValorMaximoAtual + $nValorComplementarAtual) >= $nValorTeto ) {

        /**
         * Verifica se o vinculado é menor que o teto coloca no atual a diferença
         */
        if($nValorVinculado < $nValorTeto) {

          LogCalculoFolha::write('Valor vinculado < que o teto.');
          LogCalculoFolha::write("Executando a fórmula ({$nValorTeto} - {$nValorVinculado} - ({$nValorMaximoAtual} - {$nValorAtual}))");
          $nValorAtual = $nValorTeto - $nValorVinculado - ($nValorMaximoAtual - $nValorAtual);
        }
      } else { // Soma de ambos vinculos não alcanca o teto

        $nValorAtual = $nValorMaximoAtual;
      }
    } else { // Se não foi passado valor vinculado ou seja é um vínculo ou vinculado não tem cálculo

      LogCalculoFolha::write('');
      LogCalculoFolha::write('Valor vinculado não foi passado....................................');

      /**
       * Verifica se o atual é maior ou igual a isenção/teto e não deixa
       * gravar maior que teto so verifica se não for passado valor vinculado
       */
      if($nValorAtual >= $nValorTeto) {
        LogCalculoFolha::write('Valor atual maior/igual ao teto, retornando o teto................: '.$nValorAtual);
        LogCalculoFolha::write('');
        return $nValorTeto;
      }

      /**
       * Verifica se a o maximo é maior que o atual e se o maxio é
       * menor que o teto, se sim retorna o maximo ocorre quando
       * o atual possui complementar e mesmo assim não atinge o teto e o
       * vinculado ou não existe ou ainda não está calculado
       */
      if(($nValorMaximoAtual > $nValorAtual) && $nValorMaximoAtual < $nValorTeto) {
        LogCalculoFolha::write('Valor atual < que o máximo e máximo < que o teto, retorna o máximo..: '.$nValorMaximoAtual);
        LogCalculoFolha::write('');
        return $nValorMaximoAtual;
      }
    }
    LogCalculoFolha::write('Valor atual: '.$nValorAtual);
    LogCalculoFolha::write('');

    return $nValorAtual;
  }

  /**
   * Retorna o valor liquido da folha de pegamento
   * @return float
   */
  public function getValorLiquido() {

    $nValorLiquido = 0;
    $aEventos = $this->getEventosFinanceiros();
    foreach ($aEventos as $oEvento) {

      if ($oEvento->getNatureza() == 3) {
        continue;
      }
      $nValorLiquido += $oEvento->getNatureza() == 1 ? $oEvento->getValor() : $oEvento->getValor() * -1;
    }
    return $nValorLiquido;
  }
}
