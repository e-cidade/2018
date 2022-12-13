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


class ImportacaoRelatorioLegal {

  /**
   * codigo do relatorio a ser importado
   * @var integer
   */
  private $iCodigoRelatorio;

  /**
   * objeto json do Relatorio a ser importado
   * @var object
   */
  private $oRelatorioJson;

  /**
   * Constante do caminho da mensagem do model
   * @var string
   */
  const CAMINHO_MENSAGENS = "financeiro/contabilidade/ImportacaoRelatorioLegal.";

  public function __construct( $iCodigoRelatorio = null, $sCaminhoArquivo ) {

    // se instanciar a classe sem o caminho do arquivo lançamos exceção
    if (!isset($sCaminhoArquivo)) {
      throw new ParameterException( _M( self::CAMINHO_MENSAGENS. "sem_arquivo_selecionado"));
    }
    // se nao abrir o arquivo lançamos exceção
    if ( !file_exists($sCaminhoArquivo)) {
      throw new ParameterException( _M( self::CAMINHO_MENSAGENS. "falha_abrir_arquivo"));
    }
    // se vier o parametro $iCodigoRelatorio setamos ele na propriedade
    if (!empty($iCodigoRelatorio)) {
      $this->setCodigoRelatorio($iCodigoRelatorio);
    }

    $sObjectJson = json_decode(file_get_contents($sCaminhoArquivo, FILE_TEXT));
    $this->setRelatorioJson($sObjectJson);
  }

  /**
   * define o objeto json a ser poercorrido e incluido
   * @param object $oRelatorioJson
   */
  private function setRelatorioJson( $oRelatorioJson ){
    $this->oRelatorioJson = $oRelatorioJson;
  }

  /**
   * retorna o objeto json pronto para ser incluido
   * @return object
   */
  private function getRelatorioJson(){
    return $this->oRelatorioJson;
  }

  /**
   * define o codigo do Relatorio, utilizado na alteração de dados
   * @param integer $iCodigoRelatorio
   */
  private function setCodigoRelatorio($iCodigoRelatorio){
    $this->iCodigoRelatorio = $iCodigoRelatorio;
  }

  /**
   * retorna o codigo do Relatorio para importacao
   * @return integer
   */
  public function getCodigoRelatorio(){
    return $this->iCodigoRelatorio;
  }

  /**
   * metodo responsavel pelos dados do relatorio
   *
   * @return stdClass da orcparamrel
   */
  private function getDadosRelatorio(){

    $iRelatorioPassado = $this->getCodigoRelatorio();
    $oRelatorio        = $this->getRelatorioJson();

    $oDadosOrcParamRel = new stdClass();
    $oDadosOrcParamRel->o42_codparrel        = !empty($iRelatorioPassado) ? $iRelatorioPassado : null;
    $oDadosOrcParamRel->o42_descrrel         = $oRelatorio->descricao;
    $oDadosOrcParamRel->o42_orcparamrelgrupo = $oRelatorio->grupo_relatorio;
    $oDadosOrcParamRel->o42_notapadrao       = $oRelatorio->nota_padrao;
    return $oDadosOrcParamRel;
  }

  /**
   * retorna os periodos vinculados ao relatorio, para que seja feito o vinculo na orcparamrelperiodos
   * @return array $aPeriodosRelatorio
   */
  private function getPeriodosRelatorio(){

    $oRelatorio         = $this->getRelatorioJson();
    $aPeriodosRelatorio = $oRelatorio->periodos_relatorio;
    return $aPeriodosRelatorio;
  }

  /**
   * metodo que irá retornar as linhas do relatorio, com suas colunas vinculadas e seus filtros padroes
   * para incluirmos nas tabelas: orcparamseq
   *                              orcparamseqorcparamseqcoluna
   *                              orcparamseqfiltropadrao
   * @return array $aLinhas
   */
  private function getLinhasRelatorio() {

    $oRelatorio = $this->getRelatorioJson();
    $aLinhasRelatorio = $oRelatorio->linhas;
    return $aLinhasRelatorio;
  }

  /**
   * metodo que irá consistenciar os relatorios, verifica se existe caso exista alteramos senao incluimos na
   * orcparamrel
   * @throws DBException
   * @return boolean
   */
  private function consistenciaRelatorio(){

    $oRelatorio    = $this->getDadosRelatorio();
    $oDaoRelatorio = new cl_orcparamrel();

    $oDaoRelatorio->o42_codparrel        = $oRelatorio->o42_codparrel;
    $oDaoRelatorio->o42_descrrel         = addslashes(urldecode($oRelatorio->o42_descrrel));
    $oDaoRelatorio->o42_orcparamrelgrupo = $oRelatorio->o42_orcparamrelgrupo;
    $oDaoRelatorio->o42_notapadrao       = addslashes(urldecode($oRelatorio->o42_notapadrao));

    if (!empty($oRelatorio->o42_codparrel)) {

      $sSqlRelatorio = $oDaoRelatorio->sql_query_file($oRelatorio->o42_codparrel);
      $rsRelatorio   = $oDaoRelatorio->sql_record($sSqlRelatorio);

      if ($oDaoRelatorio->numrows >= 1) {
        $oDaoRelatorio->alterar($oDaoRelatorio->o42_codparrel);
      }
    } else {

      $oDaoRelatorio->o42_codparrel = null;
      $oDaoRelatorio->incluir(null);
    }

    if ($oDaoRelatorio->erro_status == '0') {
      throw new DBException($oDaoRelatorio->erro_msg);
    }

    $this->setCodigoRelatorio($oDaoRelatorio->o42_codparrel);
    return true;
  }

  /**
   * metodo que irá vincular os periodos e relatorio na  orcparamrelperiodos
   * @throws DBException
   * @return boolean
   */
  private function vincularPeriodos() {

    $aPeriodosRelatorio = $this->getPeriodosRelatorio();

    foreach ($aPeriodosRelatorio as $iPeriodosRelatorio => $oDadosPeriodos) {

      $oDaoPeriodos = new cl_orcparamrelperiodos();

      $oDaoPeriodos->o113_sequencial  = null;
      $oDaoPeriodos->o113_periodo     = $oDadosPeriodos->periodo;
      $oDaoPeriodos->o113_orcparamrel = $this->getCodigoRelatorio();

      $oDaoPeriodos->incluir(null);

      if ($oDaoPeriodos->erro_status == '0') {
        throw new DBException($oDaoPeriodos->erro_msg);
      }
    }
    return true;
  }

  /**
   * metodo que irá consistenciar as linhas alterando ou incluindo conforme necessidade na orcparamseq
   * @throws DBException
   * @return boolean
   */
  private function consistenciaLinha() {

    $aLinhas = $this->getLinhasRelatorio();
    foreach ($aLinhas as $oDadosLinha) {

      $oDaoLinha = new cl_orcparamseq();
      $sSqlLinha = $oDaoLinha->sql_query_file ( $this->getCodigoRelatorio(), $oDadosLinha->codigo);
      $rsLinha   = $oDaoLinha->sql_record($sSqlLinha);

      $oDaoLinha->o69_codparamrel    = $this->getCodigoRelatorio();
      $oDaoLinha->o69_codseq         = $oDadosLinha->codigo;
      $oDaoLinha->o69_descr          = addslashes(urldecode($oDadosLinha->descricao));
      $oDaoLinha->o69_grupo          = $oDadosLinha->grupo;
      $oDaoLinha->o69_grupoexclusao  = $oDadosLinha->grupo_exclusao;
      $oDaoLinha->o69_nivel          = $oDadosLinha->nivel;
      $oDaoLinha->o69_verificaano    = $oDadosLinha->verifica_ano;
      $oDaoLinha->o69_labelrel       = addslashes(urldecode($oDadosLinha->label));
      $oDaoLinha->o69_manual         = $oDadosLinha->digital_manual;
      $oDaoLinha->o69_totalizador    = $oDadosLinha->totalizadora;
      $oDaoLinha->o69_ordem          = $oDadosLinha->ordem;
      $oDaoLinha->o69_nivellinha     = $oDadosLinha->nivel_linha;
      $oDaoLinha->o69_observacao     = addslashes(urldecode($oDadosLinha->observacao));
      $oDaoLinha->o69_desdobrarlinha = $oDadosLinha->desdobrar;
      $oDaoLinha->o69_origem         = $oDadosLinha->origem;
      $oDaoLinha->o69_libnivel       = $oDadosLinha->libera_nivel;
      $oDaoLinha->o69_librec         = $oDadosLinha->libera_rec;
      $oDaoLinha->o69_libsubfunc     = $oDadosLinha->libera_sub_func;
      $oDaoLinha->o69_libfunc        = $oDadosLinha->liberafunc;

      if ($oDaoLinha->numrows >= 1) {
        $oDaoLinha->alterar( $this->getCodigoRelatorio(), $oDaoLinha->o69_codseq );
      } else {

        $oDaoLinha->incluir( $this->getCodigoRelatorio(), $oDaoLinha->o69_codseq );
      }
      if ($oDaoLinha->erro_status == '0') {
        throw new DBException($oDaoLinha->erro_msg);
      }
    }
    return true;
  }

  /**
   * metodo que irá verificar manutenção na orcparamseqorcparamseqcoluna que é o vinculo entre linha e coluna
   * se necessario altera senao inclui.
   * @throws DBException
   * @return boolean
   */
  private function vincularColunas(){

    $aLinhas = $this->getLinhasRelatorio();

    foreach ($aLinhas as $oDadosLinha) {

      $aColunas  = $oDadosLinha->aColunas;
      $iLinhaSeq = $oDadosLinha->codigo;

      foreach ($aColunas as $iColuna => $oDadosColuna) {

        $oDaoColuna = new cl_orcparamseqorcparamseqcoluna();

        $oDaoColuna->o116_sequencial        = null;
        $oDaoColuna->o116_codseq            = $iLinhaSeq;
        $oDaoColuna->o116_codparamrel       = $this->getCodigoRelatorio();
        $oDaoColuna->o116_orcparamseqcoluna = $oDadosColuna->coluna;
        $oDaoColuna->o116_ordem             = $oDadosColuna->ordem;
        $oDaoColuna->o116_periodo           = $oDadosColuna->periodo;
        $oDaoColuna->o116_formula           = addslashes($oDadosColuna->formula);
        $oDaoColuna->incluir(null);

        if ($oDaoColuna->erro_status == '0') {
          throw new DBException($oDaoColuna->erro_msg);
        }
      }
    }
    return true;
  }

  /**
   * metodo será responsavel por alterar / incluir filtro padrao
   * na orcparamseqfiltropadrao
   * @return boolean
   */
  private function vincularFiltroPadrao() {

    $aLinhas = $this->getLinhasRelatorio();
    foreach ($aLinhas as $oDadosLinha) {

      $aFiltros  = $oDadosLinha->aFiltros;
      $iLinhaSeq = $oDadosLinha->codigo;
      foreach ($aFiltros as $iFiltro => $oDadosFiltro) {

        $oDaoFiltro = new cl_orcparamseqfiltropadrao();

        $oDaoFiltro->o132_sequencial  = null;
        $oDaoFiltro->o132_orcparamrel = $this->getCodigoRelatorio();
        $oDaoFiltro->o132_orcparamseq = $iLinhaSeq;
        $oDaoFiltro->o132_anousu      = $oDadosFiltro->ano;
        $oDaoFiltro->o132_filtro      = urldecode($oDadosFiltro->filtro);

        $oDaoFiltro->incluir(null);

        if ($oDaoFiltro->erro_status == '0') {
          throw new DBException($oDaoFiltro->erro_msg);
        }
      }
    }
    return true;
  }


  public function importar() {

    if ( !db_utils::inTransaction() ) {
      throw new DBException(_M( self::CAMINHO_MENSAGENS . 'sem_transacao_ativa'));
    }

    $aAnosConfigurados = $this->getAnoConfiguracaoUsuario();
    if (!empty($aAnosConfigurados)) {

      $oMensagem = (object)array('anos' => implode(',', $aAnosConfigurados));
      throw new BusinessException(_M( self::CAMINHO_MENSAGENS . 'configuracao_usuario', $oMensagem));
    }

    $iCodigoRelatorio = $this->getCodigoRelatorio();

    if (!empty($iCodigoRelatorio)) {

      $this->limparPeriodos();
      $this->limparColunas();
      $this->limparFiltroPadrao();
    }

    $this->consistenciaRelatorio();
    $this->vincularPeriodos();
    $this->consistenciaLinha();
    $this->vincularColunas();
    $this->vincularFiltroPadrao();

    return true;
  }

  /**
   * @return stdClass[]
   * @throws Exception
   */
  public function getAnoConfiguracaoUsuario() {

    $oDaoParametro      = new cl_orcparamseqfiltroorcamento();
    $sSqlBuscaParametro = $oDaoParametro->sql_query_file(null, "o133_anousu", null, "o133_orcparamrel = {$this->getCodigoRelatorio()}");
    $rsBuscaParametro   = db_query($sSqlBuscaParametro);
    if (!$rsBuscaParametro) {
      throw new Exception("Ocorreu um erro ao verificar as configurações do usuário.");
    }

    $aAnos = array();
    for ($iRowAno = 0; $iRowAno < pg_num_rows($rsBuscaParametro); $iRowAno++) {
      array_push($aAnos, db_utils::fieldsMemory($rsBuscaParametro, $iRowAno)->o133_anousu);
    }
    return $aAnos;
  }

  /**
   * Remove os periodos vinculados
   * @throws \Exception
   * @return boolean
   */
  private function limparPeriodos() {

    $iCodigoRelatorio = $this->getCodigoRelatorio();

    if (empty($iCodigoRelatorio)) {
      return false;
    }

    $oDaoPeriodos = new cl_orcparamrelperiodos();
    $oDaoPeriodos->excluir(null, "o113_orcparamrel = {$iCodigoRelatorio}");

    if ($oDaoPeriodos->erro_status == '0') {
      throw new DBException($oDaoPeriodos->erro_msg);
    }

    return true;
  }

  /**
   * Remove as colunas
   * @throws \Exception
   * @return boolean
   */
  private function limparColunas() {

    $iCodigoRelatorio = $this->getCodigoRelatorio();

    if (empty($iCodigoRelatorio)) {
      return false;
    }

    $oDaoColunas = new cl_orcparamseqorcparamseqcoluna();
    $oDaoColunas->excluir(null, "o116_codparamrel = {$iCodigoRelatorio}");

    if ($oDaoColunas->erro_status == '0') {
      throw new DBException($oDaoColunas->erro_msg);
    }

    return true;
  }


  /**
   * Remove o filtro padrão
   * @throws \Exception
   * @return boolean
   */
  private function limparFiltroPadrao() {

    $iCodigoRelatorio = $this->getCodigoRelatorio();

    if (empty($iCodigoRelatorio)) {
      return false;
    }

    $oDaoFiltro = new cl_orcparamseqfiltropadrao();
    $oDaoFiltro->excluir(null, "o132_orcparamrel = {$iCodigoRelatorio}");

    if ($oDaoFiltro->erro_status == '0') {
      throw new DBException($oDaoFiltro->erro_msg);
    }

    return true;
  }
}