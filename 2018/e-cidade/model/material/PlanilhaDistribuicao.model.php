<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBselller Servicos de Informatica
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

/**
 * Class PlanilhaDistribuicao
 */
class PlanilhaDistribuicao {

  /**
   * @var integer
   */
  private $iCodigo;

  /**
   * @var string
   */
  private $sDescricao;

  /**
   *
   * @var MaterialAlmoxarifado[]
   */
  private $aMateriais = array();

  /**
   *
   * @var DBDepartamento[]
   */
  private $aDepartamentos = array();

  const LINHA_DEPARTAMENTOS         = 1;
  const LINHA_INICIO_MATERIAIS      = 4;
  const COLUNA_CODIGO_MATERIAL      = 0;
  const COLUNA_INICIO_DEPARTAMENTOS = 2;

  /**
   *
   * @param integer $iCodigo Sequencial da planilha
   * @throws ParameterException
   */
  public function __construct($iCodigo = null) {

    $this->iCodigo = $iCodigo;
    if (empty($this->iCodigo)) {
      return;
    }

    $oDaoPlanilha = new cl_planilhadistribuicao;
    $sSqlPlanilha = $oDaoPlanilha->sql_query_file($iCodigo);
    $rsPlanilha   = $oDaoPlanilha->sql_record($sSqlPlanilha);
    if ($oDaoPlanilha->numrows == 0) {
      throw new ParameterException("Planilha não encontrada.");
    }
    $oPlanilha = db_utils::fieldsMemory($rsPlanilha, 0);
    $this->iCodigo    = $oPlanilha->pd01_sequencial;
    $this->sDescricao = $oPlanilha->pd01_descricao;
  }

  /**
   *
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   *
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   *
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   *
   * @param integer $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @param MaterialAlmoxarifado $oMaterial
   */
  public function adicionarMaterial(MaterialAlmoxarifado $oMaterial) {
    $this->aMateriais[] = $oMaterial;
  }

  /**
   * @param MaterialAlmoxarifado[] $aMateriais
   * @throws ParameterException
   */
  public function setMateriais(array $aMateriais) {

    if (!$aMateriais[0] instanceof MaterialAlmoxarifado) {
      throw new ParameterException("Material informado não é um material do almoxarifado.");
    }
    $this->aMateriais = $aMateriais;
  }

  /**
   * @param DBDepartamento $oDepartamento
   */
  public function adicionarDepartamento(DBDepartamento $oDepartamento) {
    $this->aDepartamentos[] = $oDepartamento;
  }

  /**
   * @param DBDepartamento[] $aDepartamentos
   * @throws ParameterException
   */
  public function setDepartamentos(array $aDepartamentos) {

    if (!$aDepartamentos[0] instanceof DBDepartamento) {
      throw new ParameterException("Informe um Departamento do e-cidade.");
    }
    $this->aDepartamentos = $aDepartamentos;
  }

  /**
   * Remove registros em tabelas relacionadas
   */
  private function removerVinculos() {

    $oDaoDepartamentos = new cl_planilhadistribuicaodepart;
    $oDaoDepartamentos->excluir(null, "pd02_planilhadistribuicao = {$this->iCodigo}");

    $oDaoMateriais = new cl_planilhadistribuicaomaterial;
    $oDaoMateriais->excluir(null, "pd03_planilhadistribuicao = {$this->iCodigo}");
  }

  /**
   * Cria os vínculos com as tabelas relacionadas
   */
  private function criarVinculos() {

    $oDaoDepartamentos = new cl_planilhadistribuicaodepart;
    foreach ($this->getDepartamentos() as $oDepartamento) {

      $oDaoDepartamentos->pd02_planilhadistribuicao = $this->iCodigo;
      $oDaoDepartamentos->pd02_departamento         = $oDepartamento->getCodigo();
      $oDaoDepartamentos->incluir(null);

      if ($oDaoDepartamentos->erro_status == "0") {

        $sMensagemErro  = "Não foi possível associar o departamento a Planilha de Distribuição.\n";
        $sMensagemErro .= str_replace("\\n", "\n", $oDaoDepartamentos->erro_msg);
        throw new DBException($sMensagemErro);
      }
    }

    $oDaoMateriais = new cl_planilhadistribuicaomaterial;
    foreach ($this->getMateriais() as $oMaterial) {

      $oDaoMateriais->pd03_planilhadistribuicao = $this->iCodigo;
      $oDaoMateriais->pd03_material             = $oMaterial->getCodigo();
      $oDaoMateriais->incluir(null);

      if ($oDaoMateriais->erro_status == "0") {

        $sMensagemErro  = "Não foi possível associar o material a Planilha de Distribuição.\n";
        $sMensagemErro .= str_replace("\\n", "\n", $oDaoMateriais->erro_msg);
        throw new DBException($sMensagemErro);
      }
    }
  }

  /**
   * Salva a planilha e os vínculos
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new DBException("Sem transação com banco de dados");
    }

    $oDaoPlanilha = new cl_planilhadistribuicao;
    $oDaoPlanilha->pd01_sequencial = $this->iCodigo;
    $oDaoPlanilha->pd01_descricao  = $this->sDescricao;

    if (empty($this->iCodigo)) {

      $oDaoPlanilha->incluir(null);
      $this->iCodigo = $oDaoPlanilha->pd01_sequencial;
    } else {

      $this->removerVinculos();
      $oDaoPlanilha->alterar($this->iCodigo);
    }

    if ($oDaoPlanilha->erro_status == "0") {

      $sMensagemErro  = "Não foi possível salvar a Planilha de Distribuição.\n";
      $sMensagemErro .= str_replace("\\n", "\n", $oDaoPlanilha->erro_msg);
      throw new DBException($sMensagemErro);
    }

    $this->criarVinculos();
  }

  /**
   *
   * @return MaterialAlmoxarifado[]
   */
  public function getMateriais() {

    if (!empty($this->iCodigo) && count($this->aMateriais) == 0) {

      $oDaoMateriais    = new cl_planilhadistribuicaomaterial;
      $sWhereMateriais  = "pd03_planilhadistribuicao = {$this->iCodigo}";
      $sSqlMateriais    = $oDaoMateriais->sql_query_file(null, 'pd03_material', null, $sWhereMateriais);
      $rsMateriais      = $oDaoMateriais->sql_record($sSqlMateriais);

      for ($iRowMaterial = 0; $iRowMaterial < $oDaoMateriais->numrows; $iRowMaterial++) {
        $this->aMateriais[] = new MaterialAlmoxarifado(db_utils::fieldsMemory($rsMateriais, $iRowMaterial)->pd03_material);
      }
    }
    return $this->aMateriais;
  }

  /**
   *
   * @return DBDepartamento[]
   */
  public function getDepartamentos() {

    if (!empty($this->iCodigo) && count($this->aDepartamentos) == 0) {

      $oDaoDepartamentos    = new cl_planilhadistribuicaodepart;
      $sWhereDepartamentos  = "pd02_planilhadistribuicao = {$this->iCodigo}";
      $sSqlDepartamentos    = $oDaoDepartamentos->sql_query_file(null, 'pd02_departamento', null, $sWhereDepartamentos);
      $rsDepartamentos      = $oDaoDepartamentos->sql_record($sSqlDepartamentos);

      for ($iRowDepartamento = 0; $iRowDepartamento < $oDaoDepartamentos->numrows; $iRowDepartamento++) {

        $iCodigoDepartamento    = db_utils::fieldsMemory($rsDepartamentos, $iRowDepartamento)->pd02_departamento;
        $this->aDepartamentos[] = DBDepartamentoRepository::getDBDepartamentoByCodigo($iCodigoDepartamento);
      }
    }
    return $this->aDepartamentos;
  }

  /**
   * Retorna linhas do cabeçalho
   *
   * @return array
   */
  private function gerarCabecalho() {

    $aColunasDescricao    = array(null,null);

    $aLinhas = array();
    $iDepartamentosNaoAtendidos = 0;
    $aDepartamentosAtendidos = $this->getDepartamentosAtendidos();
    $aColunasCodigo = array(null,null);
    foreach ($this->getDepartamentos() as $iChave => $oDepartamento) {

      /**
       * Se nenhum almoxarifado atende o departamento
       */
      if (!in_array($oDepartamento->getCodigo(), $aDepartamentosAtendidos)) {
        $iDepartamentosNaoAtendidos++;
        continue;
      }

      $aColunasCodigo[]    = $oDepartamento->getCodigo();
      $aColunasDescricao[] = $oDepartamento->getNomeDepartamento();
    }

    $iQuantidadeColunas   = count($this->getDepartamentos()) - $iDepartamentosNaoAtendidos;
    $aDepartamento        = array_fill(0, $iQuantidadeColunas, 'Departamento');
    $aColunasDepartamento = array_merge(array(null,null), $aDepartamento);

    /**
     * Repete a coluna "Quantidade" para cada departamento
     */
    $aQuantidade = array_fill(0, $iQuantidadeColunas, 'Quantidade');
    $aLinhas[] = $aColunasDepartamento;
    $aLinhas[] = $aColunasCodigo;
    $aLinhas[] = $aColunasDescricao;
    $aLinhas[] = array_merge(array('Código', 'Material - Unidade'), $aQuantidade);

    return $aLinhas;
  }

  /**
   * Retorna linhas com os materiais
   *
   * @return array
   */
  private function gerarLinhas() {

    $aLinhas = array();
    foreach ($this->getMateriais() as $oMaterial) {

      if (!$oMaterial->ativo()) {
        continue;
      }
      $aLinhas[] = array($oMaterial->getCodigo(), "{$oMaterial->getDescricao()} - {$oMaterial->getUnidade()->getAbreviatura()}");
    }

    return $aLinhas;
  }

  /**
   * Gera o CSV
   *
   * @return string Nome do arquivo
   */
  public function gerar() {

    $sNomeArquivo     = "tmp/planilhadistribuicao_{$this->iCodigo}.csv";
    $hArquivo         = fopen($sNomeArquivo, 'w');
    $aLinhasCorpo     = $this->gerarLinhas();
    $aLinhasCabecalho = $this->gerarCabecalho();
    $aLinhas          = array_merge($aLinhasCabecalho, $aLinhasCorpo);

    foreach ($aLinhas as $aLinha) {
      fputcsv($hArquivo, $aLinha, ';', '"');
    }
    fclose($hArquivo);

    return $sNomeArquivo;
  }

  /**
   * Lista de departamentos atendidos
   *
   * @return array Códigos dos departamentos atendidos
   */
  public function getDepartamentosAtendidos($iAlmoxarifado = null) {

    $oDaoDbAlmoxDepto = new cl_db_almoxdepto;
    $sCampos          = 'distinct m92_depto';
    $sWhere           = null;
    $sOrdem           = 'm92_depto';
    if ($iAlmoxarifado !== null) {
      $sWhere = "m92_codalmox = {$iAlmoxarifado}";
    }
    $sSqlAtendidos = $oDaoDbAlmoxDepto->sql_query_file(null, null, $sCampos, $sOrdem, $sWhere);
    $rsAtendidos   = $oDaoDbAlmoxDepto->sql_record($sSqlAtendidos);
    if (!$rsAtendidos) {
      throw new Exception("Não foi possível buscar os departamentos atendidos pelo almoxarifado {$iAlmoxarifado}.");
    }
    for ($iIndice = 0; $iIndice < $oDaoDbAlmoxDepto->numrows; $iIndice++) {

      $oAtendido    = db_utils::fieldsMemory($rsAtendidos, $iIndice);
      $aAtendidos[] = $oAtendido->m92_depto;
    }

    return $aAtendidos;
  }

  /**
   * Tenta converter uma string para float, usando ponto e depois usando vírgula.
   * Retorna falso se não consegui fazer a conversão.
   *
   * @param  string $sValor
   * @return float|false
   */
  private function toFloat($sValor) {

    $aOpcoesPonto   = array('options' => array('decimal' => '.'));
    $aOpcoesVirgula = array('options' => array('decimal' => ','));
    $nFloatPonto    = filter_var($sValor, FILTER_VALIDATE_FLOAT, $aOpcoesPonto);
    $nFloatVirgula  = filter_var($sValor, FILTER_VALIDATE_FLOAT, $aOpcoesVirgula);

    if ($nFloatPonto !== false) {
      return $nFloatPonto;
    }

    return $nFloatVirgula;
  }

  /**
   * Tenta converter uma string para inteiro.
   * Retorna falso caso não consiga fazer a conversão.
   *
   * @param  string $sValor
   * @return integer|false
   */
  private function toInteger($sValor) {
    return filter_var($sValor, FILTER_VALIDATE_INT);
  }

  /**
   * Gera requisições de material a partir de um CSV
   *
   * @param  string  $sNomeArquivo
   * @param  integer $iAlmoxarifado
   * @throws FileException
   * @throws BusinessException
   * @throws Exception
   * @throws DBException
   * @return string Caminho do arquivo CSV com código das requisições geradas
   */
  public function importar($sNomeArquivo, $iAlmoxarifado) {

    if (!db_utils::inTransaction()) {
      throw new DBException("Sem transação com banco de dados");
    }

    if (!file_exists($sNomeArquivo)) {
      throw new FileException("Arquivo '{$sNomeArquivo}' não encontrado.");
    }

    if (!is_readable($sNomeArquivo)) {
      throw new FileException("Falha ao tentar fazer a leitura do arquivo '{$sNomeArquivo}'.");
    }

    $hArquivo = fopen($sNomeArquivo, 'r');
    if (!$hArquivo) {
      throw new FileException("Ocorreu um erro ao tentar ler o arquivo '{$sNomeArquivo}'.");
    }

    $aLinhas = array();
    while (($aDados = fgetcsv($hArquivo, 0, ';', '"')) !== false) {
      $aLinhas[] = $aDados;
    }

    /**
     * Percorre toda a planilha e valida os dados informados
     */
    $aDepartamentosAtendidos  = $this->getDepartamentosAtendidos($iAlmoxarifado);
    $lSemDados                = true;
    $aRequisicoes             = array(array('Código da Requisição', 'Departamento'));
    $nQtdColunasDepartamentos = !empty($aLinhas[self::LINHA_DEPARTAMENTOS]) ? count($aLinhas[self::LINHA_DEPARTAMENTOS]) : 0;
    $aDadosRequisicoes = array();
    for ($iColunaDepartamento = self::COLUNA_INICIO_DEPARTAMENTOS; $iColunaDepartamento < $nQtdColunasDepartamentos; $iColunaDepartamento++) {

      $iDataHora     = time();
      $sDepartamento = $aLinhas[self::LINHA_DEPARTAMENTOS][$iColunaDepartamento];
      $iDepartamento = $this->toInteger($sDepartamento);

      /**
       * Percorre todas as linhas da coluna para saber se alguma tem quantidade e material.
       */
      $lPreenchido = false;
      for ($iLinhaAtual = self::LINHA_INICIO_MATERIAIS; $iLinhaAtual < count($aLinhas); $iLinhaAtual++) {

        /**
         * Se encontrou material e quantidade em ao menos uma linha
         */
        if (!empty($aLinhas[$iLinhaAtual][self::COLUNA_CODIGO_MATERIAL]) && !empty($aLinhas[$iLinhaAtual][$iColunaDepartamento])) {

          $lPreenchido = true;
          break;
        }
      }

      /**
       * Se não tem pelo menos uma linha preenchida com departamento e material
       */
      if (!$lPreenchido) {
        continue;
      }

      /**
       * Departamento não informado
       */
      if (empty($sDepartamento)) {
        continue;
      }

      /**
       * Valor inválido
       */
      if ($iDepartamento === false) {
        throw new BusinessException("Valor {$sDepartamento} inválido para a coluna departamento.");
      }

      /**
       * Verifica se departamento existe e trata a mensagem de erro
       */
      try {
        $oDepartamento = new DBDepartamento($iDepartamento);
      } catch (Exception $oException) {
        throw new Exception("O departamento de código {$iDepartamento} não existe.");
      }

      /**
       * Nenhum almoxarifado atende o departamento
       */
      if (!in_array($iDepartamento, $aDepartamentosAtendidos)) {
        throw new BusinessException("O almoxarifado {$iAlmoxarifado} não atende o departamento {$iDepartamento}.");
      }

      $oDadosRequisicao = new stdClass;
      $oDadosRequisicao->m40_data      = date('Y-m-d', $iDataHora);
      $oDadosRequisicao->m40_almox     = $iAlmoxarifado;
      $oDadosRequisicao->m40_depto     = $iDepartamento;
      $oDadosRequisicao->m40_login     = db_getsession('DB_id_usuario');
      $oDadosRequisicao->m40_hora      = date('H:i', $iDataHora);
      $oDadosRequisicao->m40_obs       = 'Requisição automática gerada por Planilha de Distribuição.';
      $oDadosRequisicao->m40_auto      = 'false';
      $oDadosRequisicao->oDepartamento = $oDepartamento;
      $oDadosRequisicao->aItens        = array();

      for ($iLinhaAtual = self::LINHA_INICIO_MATERIAIS; $iLinhaAtual < count($aLinhas); $iLinhaAtual++) {

        $sMaterial   = $aLinhas[$iLinhaAtual][self::COLUNA_CODIGO_MATERIAL];
        $iMaterial   = $this->toInteger($sMaterial);
        $nQuantidade = 0;
        $sQuantidade = '';
        if (!empty($aLinhas[$iLinhaAtual][$iColunaDepartamento])) {

          $sQuantidade = $aLinhas[$iLinhaAtual][$iColunaDepartamento];
          $nQuantidade = $this->toFloat($sQuantidade);
        }

        /**
         * Material não informado
         */
        if (empty($sMaterial)) {
          continue;
        }

        /**
         * Quantidade não informada
         */
        if (empty($sQuantidade)) {
          continue;
        }

        /**
         * Valor inválido
         */
        if ($iMaterial === false) {
          throw new BusinessException("Valor {$sMaterial} inválido para a coluna código do material.");
        }

        /**
         * Valor inválido
         */
        if ($nQuantidade === false) {
          throw new BusinessException("Quantidade {$sQuantidade} inválida para o departamento de código {$iDepartamento} e material de código {$iMaterial}.");
        }

        /**
         * Verifica se material existe e trata a mensagem de erro
         */
        try {
          $oMaterial = new MaterialAlmoxarifado($iMaterial);
        } catch (Exception $oException) {
          throw new Exception("O material de código {$iMaterial} não existe.");
        }

        /**
         * Material deve estar ativo
         */
        if (!$oMaterial->ativo()) {
          throw new BusinessException("O material de código {$oMaterial->getCodigo()} não está ativo.");
        }

        /**
         * Quantidade não pode ser negativa
         */
        if ($nQuantidade < 0) {
          throw new BusinessException("Foi informada uma quantidade negativa para o material de código {$oMaterial->getCodigo()} e departamento de código {$iDepartamento}.");
        }

        $oItemRequisicao = new stdClass;
        $oItemRequisicao->m41_codmatmater = $iMaterial;
        $oItemRequisicao->m41_codunid     = $oMaterial->getUnidade()->getCodigo();
        $oItemRequisicao->m41_quant       = $nQuantidade;
        $oItemRequisicao->m41_obs         = '';

        $oDadosRequisicao->aItens[] = $oItemRequisicao;

        /**
         * Se chegou até aqui então pelo menos uma quantidade foi informada na planilha
         */
        $lSemDados = false;
      }

      $aDadosRequisicoes[$iColunaDepartamento] = $oDadosRequisicao;
    }

    /**
     * Nenhuma quantidade foi informada na planilha
     */
    if ($lSemDados) {
      throw new BusinessException('A planilha importada não possui dados para inclusão de requisição.');
    }

    /**
     * Persiste as requisições
     */
    foreach ($aDadosRequisicoes as $oDadosRequisicao) {

      /**
       * Deve haver pelo menos um item na requisção
       */
      if (count($oDadosRequisicao->aItens) === 0) {
        continue;
      }

      $oDaoMaterialRequisicao = new cl_matrequi;
      $oDaoMaterialRequisicao->m40_data  = $oDadosRequisicao->m40_data;
      $oDaoMaterialRequisicao->m40_almox = $oDadosRequisicao->m40_almox;
      $oDaoMaterialRequisicao->m40_depto = $oDadosRequisicao->m40_depto;
      $oDaoMaterialRequisicao->m40_login = $oDadosRequisicao->m40_login;
      $oDaoMaterialRequisicao->m40_hora  = $oDadosRequisicao->m40_hora;
      $oDaoMaterialRequisicao->m40_obs   = $oDadosRequisicao->m40_obs;
      $oDaoMaterialRequisicao->m40_auto  = $oDadosRequisicao->m40_auto;
      $oDaoMaterialRequisicao->incluir(null);
      if ($oDaoMaterialRequisicao->erro_status == "0") {
        throw new DBException("Erro interno. Não foi possível salvar a requisição para o departamento de código {$oDadosRequisicao->m40_depto}.");
      }

      foreach ($oDadosRequisicao->aItens as $oItem) {

        $oDaoMaterialItemRequisicao = new cl_matrequiitem;
        $oDaoMaterialItemRequisicao->m41_codmatrequi = $oDaoMaterialRequisicao->m40_codigo;
        $oDaoMaterialItemRequisicao->m41_codmatmater = $oItem->m41_codmatmater;
        $oDaoMaterialItemRequisicao->m41_codunid     = $oItem->m41_codunid;
        $oDaoMaterialItemRequisicao->m41_quant       = $oItem->m41_quant;
        $oDaoMaterialItemRequisicao->m41_obs         = $oItem->m41_obs;
        $oDaoMaterialItemRequisicao->incluir(null);
        if ($oDaoMaterialItemRequisicao->erro_status == "0") {
          throw new DBException("Erro interno. Não foi possível associar o material de código {$oItem->m41_codmatmater} para o departamento de código {$oDadosRequisicao->m40_depto}.");
        }
      }

      $aRequisicoes[] = array($oDaoMaterialRequisicao->m40_codigo, "{$oDadosRequisicao->oDepartamento->getCodigo()} - {$oDadosRequisicao->oDepartamento->getNomeDepartamento()}");
    }

    /**
     * Gera CSV com código da requisição e departamento
     */
    $sNomeArquivoRetorno = 'tmp/retorno_planilhadistribuicao_' . time() . '.csv';
    $hArquivoRetorno = fopen($sNomeArquivoRetorno, 'w');
    foreach ($aRequisicoes as $aColunasLinha) {
      fputcsv($hArquivoRetorno, $aColunasLinha, ';', '"');
    }
    fclose($hArquivoRetorno);

    return $sNomeArquivoRetorno;
  }

}
