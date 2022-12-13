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

/**
 * Classe que abstrai Metas de um programa
 * As meta estão diretamente ligadas a um programa
 *
 * @author bruno.silva
 * @author acacio.schneider
 * @package orcamento
 * @version $Revision: 1.17 $
 */
class ProgramaMeta {

  /**
   * Sequencial da tabela orcmeta
   * @var integer
   */
  private $iCodigoSequencial;

  /**
   * Descrição completa da Meta
   * @var string
   */
  private $sMeta;

  /**
   * Descrição sucinta da Meta
   * @var string
   */
  private $sDescricao;

  /**
   * Array com as iniciativas da meta
   * @var ProgramaIniciativa array
   */
  private $aIniciativas = array();

  /**
   * Código do Objetivo
   * @var integer
   */
  private $iCodigoObjetivo;

  /**
   * Objetivo a qual a meta pertence
   * @var ProgramaObjetivo
   */
  protected $oProgramaObjetivo;

  /**
   * @var ProgramaMetaIndice[]
   */
  protected $indices = array();

  /**
   * Recupera a propriedade do sequencial do Objetivo, presente na tabela orcmeta
   * @return integer
   */
  public function getCodigoSequencial() {
    return $this->iCodigoSequencial;
  }

  /**
   * Atribui na propriedade do sequencial do Objetivo, presente na tabela orcmeta
   * @return integer
   */
  public function setCodigoSequencial($iCodigoSequencial) {
    $this->iCodigoSequencial = $iCodigoSequencial;
  }

  /**
   * Recupera a propriedade da descrição da meta, presente na tabela orcmeta
   * @return string
   */
  public function getMeta() {
    return $this->sMeta;
  }

  /**
   * Atribui na propriedade da descrição da meta, presente na tabela orcmeta
   * @return string
   */
  public function setMeta($sMeta) {
    $this->sMeta = $sMeta;
  }

  /**
   * Recupera a propriedade da descrição sucinta da meta, presente na tabela orcmeta
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Atribui na propriedade da descrição sucinta da meta, presente na tabela orcmeta
   * @return string
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Retorna as iniciativas da meta
   * @return array
   */
  public function getIniciativas() {

    if (empty($this->aIniciativas)) {
      $this->buscaIniciativas();
    }
    return $this->aIniciativas;
  }

  /**
   * Retorna a iniciativa vinculada a meta, de Código $iCodigo
   * @return ProgramaIniciativa
   */
  public function getIniciativaPorCodigo($iCodigo) {

    $this->getIniciativas();
    foreach ($this->aIniciativas as $oIniciativa) {

      if ($oIniciativa->getCodigoSequencial() == $iCodigo) {
        return $oIniciativa;
      }
    }
  }

  /**
   * Retorna o código do objetivo
   * @return integer
   */
  public function getCodigoObjetivo() {
    return $this->iCodigoObjetivo;
  }

  /**
   * Seta o código do objetivo da meta
   * @param integer $iCodigoObjetivo
   */
  public function setCodigoObjetivo($iCodigoObjetivo) {
    $this->iCodigoObjetivo = $iCodigoObjetivo;
  }


  /**
   * Construtor da classe ProgramaMeta, recebe por parâmetro um sequencial da tabela orcmeta
   * Caso vier com valor vazio, constrói objeto com propriedades não setadas
   * @param  integer   $iCodigoSequencial
   * @throws DBException
   */
  public function __construct($iCodigoSequencial = null) {

    if (!empty($iCodigoSequencial)) {

      $oDAOOrcMeta = db_utils::getDao("orcmeta");
      $sSQL        = $oDAOOrcMeta->sql_query_file(null, "*", null, "o145_sequencial ={$iCodigoSequencial}");
      $rsResultado = $oDAOOrcMeta->sql_record($sSQL);

      if ($oDAOOrcMeta->erro_status == "0") {

        $sMensagemErro  = "Erro Técnico: erro ao carregar dados da Meta {$iCodigoSequencial}.";
        $sMensagemErro .= $oDAOOrcMeta->erro_msg;
        throw new DBException($sMensagemErro);
      }

      $oMeta                   = db_utils::fieldsMemory($rsResultado, 0);
      $this->iCodigoSequencial = $oMeta->o145_sequencial;
      $this->sDescricao        = $oMeta->o145_descricao;
      $this->sMeta             = $oMeta->o145_meta;
      $this->iCodigoObjetivo   = $oMeta->o145_orcobjetivo;
    }
  }

  /**
   * Salva os dados da Meta, caso o sequêncial seja nulo
   * Do contrário, altera a Meta
   */
  public function salvar() {

    $oDAOOrcMeta                    = db_utils::getDao("orcmeta");
    $oDAOOrcMeta->o145_descricao    = $this->sDescricao;
    $oDAOOrcMeta->o145_meta         = $this->sMeta;
    $oDAOOrcMeta->o145_orcobjetivo  = $this->iCodigoObjetivo;

    if (empty($this->iCodigoSequencial)) {
      $oDAOOrcMeta->incluir(null);
    } else {

      $oDAOOrcMeta->o145_sequencial = $this->iCodigoSequencial;
      $oDAOOrcMeta->alterar($this->iCodigoSequencial);
    }

    if ($oDAOOrcMeta->erro_status == "0") {

      $sMensagemErro  = "Erro Técnico: erro ao salvar dados da Meta.";
      $sMensagemErro .= $oDAOOrcMeta->erro_msg;
      throw new DBException($sMensagemErro);
    }

    $this->iCodigoSequencial = $oDAOOrcMeta->o145_sequencial;
    $this->salvarIndices();

    /**
     * Salva Cada uma das Iniciativas
     */
    foreach($this->aIniciativas as $oIniciativa) {

      $oIniciativa->setCodigoMeta($this->iCodigoSequencial);
      $oIniciativa->salvar();
    }

  }

  /**
   * Busca as iniciativas a partir de uma meta
   * @return ProgramaIniciativa  array
   * @throws DBException
   */
  private function buscaIniciativas() {

    if (!empty($this->iCodigoSequencial)) {

      $oDaoOrcIniciativa    = db_utils::getDao("orciniciativa");
      $sWhereIniciativas    = "o147_orcmeta = {$this->iCodigoSequencial}";
      $sSqlBuscaIniciativas = $oDaoOrcIniciativa->sql_query_file(null, "*", 'o147_ano', $sWhereIniciativas);
      $rsBuscaIniciativas   = db_query($sSqlBuscaIniciativas);

      if (!$rsBuscaIniciativas) {

        $sMensagemErro  = "Erro Técnico: erro ao carregar Iniciativas da Meta {$this->iCodigoSequencial}.";
        $sMensagemErro .= pg_last_error();
        throw new DBException($sMensagemErro);
      }

      $this->aIniciativas = array();
      $iTotalIniciativas  = pg_num_rows($rsBuscaIniciativas);

      /**
       * Adiciona cada iniciativa vinculada ao array da propriedade
       */
      for ($iIniciativa = 0; $iIniciativa < $iTotalIniciativas; $iIniciativa++) {

        $oStdIniciativa       = db_utils::fieldsMemory($rsBuscaIniciativas, $iIniciativa);
        $oIniciativa          = new ProgramaIniciativa($oStdIniciativa->o147_sequencial);
        $this->aIniciativas[] = $oIniciativa;
      }
    }
  }

  /**
   * Vincula  Iniciativa a uma Meta
   * @param $oProgramaIniciativa
   */
  public function adicionaIniciativa(ProgramaIniciativa $oProgramaIniciativa) {

    $this->getIniciativas();
    $this->aIniciativas[] = $oProgramaIniciativa;
  }

  /**
   * Exclui as iniciativas da meta e a meta em si
   * @throws DBException
   */
  public function excluir () {

    foreach($this->getIniciativas() as $oIniciativa) {
      $oIniciativa->excluir();
    }

    foreach($this->getIndices() as $oIndice) {
      $oIndice->excluir();
    }

    $oDaoOrcMeta = db_utils::getDao("orcmeta");
    $oDaoOrcMeta->o145_sequencial = $this->iCodigoSequencial;
    $oDaoOrcMeta->excluir($this->iCodigoSequencial);

    if ($oDaoOrcMeta->erro_status == "0") {

      $sMensagem = "Erro ao excluir meta {$this->iCodigoSequencial}";
      throw new DBException($sMensagem);
    }
  }

  /**
   * @param $iCodigoIniciativa
   *
   * @throws BusinessException
   */
  public function excluirIniciativa($iCodigoIniciativa) {


    if (empty($this->aIniciativas)) {
      $this->buscaIniciativas();
    }

    $iNumeroIniciativas = count($this->aIniciativas);
    $lEncontrou         = false;

    /**
     * Procura a Iniciativa no array de Iniciativas
     */
    for ($iIniciativa = 0; $iIniciativa < $iNumeroIniciativas; $iIniciativa++) {

      /**
       * Retira iniciativa do array e exclui vinculo, quando encontrada
       */
      if ($this->aIniciativas[$iIniciativa]->getCodigoSequencial() == $iCodigoIniciativa) {

        $this->aIniciativas[$iIniciativa]->excluir();
        unset($this->aIniciativas[$iIniciativa]);
        $lEncontrou = true;
        break;
      }
    }

    if (!$lEncontrou) {

      $sMensagem = "Iniciativa {$iCodigoIniciativa} não vinculada a meta {$this->iCodigoSequencial}.";
      throw new BusinessException($sMensagem);
    }
  }

  /**
   * Retorna um objeto do tipo ProgramaObjetivo ao qual a meta pertence
   * @return ProgramaObjetivo
   */
  public function getObjetivo() {

    if (!empty($this->iCodigoObjetivo)) {
      $this->oProgramaObjetivo = new ProgramaObjetivo($this->iCodigoObjetivo);
    }
    return $this->oProgramaObjetivo;
  }

  /**
   * @param ProgramaMetaIndice $indice
   */
  public function adicionarIndice(ProgramaMetaIndice $indice) {
    $this->indices[$indice->getAno()] = $indice;
  }

  /**
   * Salva os índices para as metas
   * @return bool
   * @throws DBException
   */
  private function salvarIndices() {

    $daoMetaIndice = new cl_orcmetaindices();
    $daoMetaIndice->excluir(null, "o154_orcmeta = {$this->iCodigoSequencial}");
    if ($daoMetaIndice->erro_status === "0") {
      throw new DBException("Ocorreu um erro ao excluir os índices para a meta {$this->sDescricao}.");
    }

    foreach ($this->indices as $indiceMeta) {

      $daoMetaIndice = new cl_orcmetaindices();
      $daoMetaIndice->o154_sequencial    = null;
      $daoMetaIndice->o154_orcmeta       = $this->iCodigoSequencial;
      $daoMetaIndice->o154_ano           = $indiceMeta->getAno();
      $daoMetaIndice->o154_indice        = $indiceMeta->getIndice();
      $daoMetaIndice->o154_unidademedida = $indiceMeta->getUnidadeMedida();
      $daoMetaIndice->incluir(null);
      if ($daoMetaIndice->erro_status === "0") {
        throw new DBException("Ocorreu um erro ao salvar os índices para a meta {$this->sDescricao}. {$daoMetaIndice->erro_sql}");
      }
    }
    return true;
  }

  /**
   * @return ProgramaMetaIndice[]|array
   * @throws DBException
   */
  public function getIndices() {

    if (!empty($this->iCodigoSequencial) && empty($this->indices)) {

      $daoIndice     = new cl_orcmetaindices();
      $buscaIndices  = $daoIndice->sql_query_file(null, "*", 'o154_ano', "o154_orcmeta = {$this->iCodigoSequencial}");
      $rsBuscaIndice = db_query($buscaIndices);
      if (!$rsBuscaIndice) {
        throw new DBException("Ocorreu um erro ao buscar os índices para a meta {$this->sDescricao}.");
      }

      $this->indices = db_utils::makeCollectionFromRecord($rsBuscaIndice, function ($dadosIndice) {

        $indiceMeta = new ProgramaMetaIndice();
        $indiceMeta->setAno($dadosIndice->o154_ano);
        $indiceMeta->setIndice($dadosIndice->o154_indice);
        $indiceMeta->setUnidadeMedida($dadosIndice->o154_unidademedida);

        return $indiceMeta;
      });
    }

    return $this->indices;
  }

  /**
   * @return float|int
   */
  public function getIndicesSomados() {

    $totalIndices = 0;
    foreach ($this->getIndices() as $indice) {
      $totalIndices += $indice->getIndice();
    }
    return $totalIndices;
  }

  /**
   * @return int|false
   */
  public function getAnoMinimoIndice() {

    $indices = $this->getIndices();
    if (count($indices) > 0) {
      return $indices[0]->getAno();
    }
    return false;
  }

  /**
   * Retorna maior ano do índice
   * @return int|false
   */
  public function getAnoMaximoIndice() {

    $indices = $this->getIndices();
    if (count($indices) > 0) {
      return $indices[(count($indices)-1)]->getAno();
    }
    return false;
  }

  /**
   * Retorna a unidade de medida encontrada para o primeiro indice
   * @return string|false
   */
  public function getUnidadeMedidaIndice() {

    $indices = $this->getIndices();
    if (count($indices) > 0) {
      return $indices[0]->getUnidadeMedida();
    }
    return false;
  }

  /**
   * Retorna os dados do indice para o ano informado no parâmetro
   *
   * @param $ano
   * @return bool|mixed|ProgramaMetaIndice
   * @throws ParameterException
   */
  public function getIndiceNoAno($ano) {

    if (empty($ano)) {
      throw new ParameterException("Não foi informado um ano para ser retornado o índice.");
    }
    foreach ($this->getIndices() as $indice) {

      if ((int)$indice->getAno() === (int)$ano) {
        return $indice;
      }
    }
    return false;
  }
}

