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
 * Classe que abstrai os Objetivos de um Programa dentro do Orçamento
 *
 * @author    bruno.silva
 * @author    acacio.schneider
 * @package   orcamento
 * @version   $Revision: 1.12 $
 */
class ProgramaObjetivo {

  /**
   * Sequencial da tabela orcobjetivo
   * @var integer
   */
  private $iCodigoSequencial;


  /**
   * Descrição completa do Objetivo
   * @var string
   */
  private $sObjetivo;


  /**
   * Descrição sucinta do Objetivo
   * @var string
   */
  private $sDescricao;

  /**
   * Array com as metas do objetivo
   * @var ProgramaMeta[]
   */
  private $aMetas = array();

  /**
   * Órgão do objetivo
   * @var Orgao
   */
  private $oOrgao;

  /**
   * Retorna o órgão do objetivo
   * @return Orgao
   */
  public function getOrgao() {
    return $this->oOrgao;
  }

  /**
   * Seta o órgão do objetivo
   * @param Orgao $oOrgao
   */
  public function setOrgao(Orgao $oOrgao) {
    $this->oOrgao = $oOrgao;
  }

  /**
   * Recupera a propriedade do sequencial do Objetivo, presente na tabela orcobjetivo
   * campo:   o143_sequencial (não nulo)
   * @return integer
   */
  public function getCodigoSequencial() {
    return $this->iCodigoSequencial;
  }

  /**
   * Recupera a propriedade da descrição do Objetivo, presente na tabela orcobjetivo
   * campo:   o143_objetivo(text)
   * @return string
   */
  public function getObjetivo() {
    return $this->sObjetivo;
  }

  /**
   * Atribui na propriedade da descrição, do Objetivo, presente na tabela orcobjetivo um objetivo
   * campo:   o143_objetivo(text)
   * @param  string
   */
  public function setObjetivo($sObjetivo) {
    $this->sObjetivo = $sObjetivo;
  }

  /**
   * Recupera a propriedade da descrição sucinta do Objetivo, presente na tabela orcobjetivo
   * campo:   o143_descricao
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Atribui na propriedade da descrição sucinta do Objetivo, presente na tabela orcobjetivo, uma descrição(text)
   * campo:   o143_descricao
   * @param  string
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Retorna as metas da objetivo
   * @return ProgramaMeta[]
   */
  public function getMetas() {

    if (empty($this->aMetas)) {
      $this->buscaMetas();
    }
    return $this->aMetas;
  }

  /**
   * Retorna a meta vinculada ao objetivo, de Código $iCodigo
   * @return ProgramaMeta
   */
  public function getMetaPorCodigo($iCodigo) {

    $this->getMetas();
    foreach ($this->aMetas as $oMeta) {

      if ($oMeta->getCodigoSequencial() == $iCodigo) {
        return $oMeta;
      }
    }
    return null;
  }

  /**
   * Construtor da classe ProgramaObjetivo, recebe como parametro um sequencial da tabela orcobjetivo
   * Caso contrário, constroi um objeto sem as propriedades setadas
   * @param   integer   $iCodigoSequencial
   * @throws  DBException
   */
  public function __construct($iCodigoSequencial = null) {

    if (!empty($iCodigoSequencial)) {

      $oDAOOrcObjetivo = db_utils::getDao("orcobjetivo");
      $sSQL            = $oDAOOrcObjetivo->sql_query_file(null, "*", null, "o143_sequencial ={$iCodigoSequencial}");
      $rsResultado     = $oDAOOrcObjetivo->sql_record($sSQL);

      if ($oDAOOrcObjetivo->erro_status == "0") {

        $sMensagemErro  = "Erro Técnico: erro ao carregar dados do Objetivo {$iCodigoSequencial}.";
        $sMensagemErro .= $oDAOOrcObjetivo->erro_msg;
        throw new DBException($sMensagemErro);
      }

      $oObjetivo               = db_utils::fieldsMemory($rsResultado, 0);
      $this->iCodigoSequencial = $oObjetivo->o143_sequencial;
      $this->sDescricao        = $oObjetivo->o143_descricao;
      $this->sObjetivo         = $oObjetivo->o143_objetivo;
      $this->oOrgao            = new Orgao($oObjetivo->o143_orcorgaoorgao, $oObjetivo->o143_orcorgaoanousu);
    }
  }

  /**
   * Salva os dados da Objetivo, caso o sequêncial seja nulo
   * Do contrário, altera o Objetivo
   * Salva as Metas do objetivo
   * @throws  DBException
   */
  public function salvar() {

    $oDAOOrcObjetivo                      = db_utils::getDao("orcobjetivo");
    $oDAOOrcObjetivo->o143_descricao      = $this->sDescricao;
    $oDAOOrcObjetivo->o143_objetivo       = $this->sObjetivo;
    $oDAOOrcObjetivo->o143_orcorgaoorgao  = $this->oOrgao->getCodigoOrgao();
    $oDAOOrcObjetivo->o143_orcorgaoanousu = $this->oOrgao->getAno();

    if (empty($this->iCodigoSequencial)) {
      $oDAOOrcObjetivo->incluir(null);
    } else {

      $oDAOOrcObjetivo->o143_sequencial = $this->iCodigoSequencial;
      $oDAOOrcObjetivo->alterar($this->iCodigoSequencial);
    }

    if ($oDAOOrcObjetivo->erro_status == "0") {

      $sMensagemErro  = "Erro Técnico: erro ao salvar dados do Objetivo.";
      $sMensagemErro .= $oDAOOrcObjetivo->erro_msg;
      throw new DBException($sMensagemErro);
    }

    $this->iCodigoSequencial = $oDAOOrcObjetivo->o143_sequencial;

    /**
     * Salva metas uma-a-uma
     */
    foreach($this->aMetas as $oMeta) {

      $oMeta->setCodigoObjetivo($this->iCodigoSequencial);
      $oMeta->salvar();
    }
  }

  /**
   * Busca as metas vinculadas, a partir do objetivo instanciado
   * Adiciona as metas no array de metas do objeto
   * @throws DBException
   */
  private function buscaMetas() {

    if (!empty($this->iCodigoSequencial)) {

      $oDaoOrcMeta    = db_utils::getDao("orcmeta");
      $sWhereMetas    = "o145_orcobjetivo = {$this->iCodigoSequencial}";
      $sSqlBuscaMetas = $oDaoOrcMeta->sql_query_file(null, "*", "o145_sequencial", $sWhereMetas);
      $rsBuscaMetas   = db_query($sSqlBuscaMetas);

      if (!$rsBuscaMetas) {

        $sMensagemErro  = "Erro Técnico: erro ao carregar Metas do Objetivo {$this->iCodigoSequencial}.";
        $sMensagemErro .= pg_last_error();
        throw new DBException($sMensagemErro);
      }

      $this->aMetas     = array();
      $iQuantidadeMetas = pg_num_rows($rsBuscaMetas);

      for ($iMeta = 0; $iMeta < $iQuantidadeMetas; $iMeta++) {

        $oStdMeta       = db_utils::fieldsMemory($rsBuscaMetas, $iMeta);
        $oMeta          = new ProgramaMeta($oStdMeta->o145_sequencial);
        $this->aMetas[] = $oMeta;
      }
    }
  }

  /**
   * Vincula  uma Meta a um Objetivo
   * @param ProgramaMeta $oProgramaMeta
   */
  public function adicionaMeta(ProgramaMeta $oProgramaMeta) {
    $this->aMetas[] = $oProgramaMeta;
  }

  /**
   * Excluir  Vinculo de uma Meta com o Objetivo caso a meta já esteja vinculada ao objetivo
   * @param  integer       $iCodigoMeta
   * @throws DBException
   * @throws BusinessException
   */
  public function excluirMeta($iCodigoMeta) {

    if (empty($this->aMetas)) {
      $this->buscaMetas();
    }

    $lEncontrouMeta = false;
    $iNumeroMetas   = count($this->aMetas);

    for($iMeta = 0; $iMeta < $iNumeroMetas; $iMeta++ ) {

      if ($this->aMetas[$iMeta]->getCodigoSequencial() == $iCodigoMeta) {

        $oMeta = new ProgramaMeta($iCodigoMeta);
        $oMeta->excluir();
        unset($this->aMetas[$iMeta]);
        break;
      }
    }

    if ($lEncontrouMeta == false) {
      throw new BusinessException("Meta {$iCodigoMeta} não vinculada ao objetivo {$this->iCodigoSequencial}.");
    }
  }

  /**
   * Exclui as metas do objetivo e também o objetivo em si
   * @throws DBException
   */
  public function excluir() {

    $this->getMetas();

    foreach($this->aMetas as $oMeta) {
      $oMeta->excluir();
    }

    $oDaoOrcObjetivo = db_utils::getDao("orcobjetivo");
    $oDaoOrcObjetivo->o143_sequencial = $this->iCodigoSequencial;
    $oDaoOrcObjetivo->excluir($this->iCodigoSequencial);

    if ($oDaoOrcObjetivo->erro_status == "0") {

      $sMensagem = "Erro Técnico ao excluir objetivo {$this->iCodigoSequencial}";
      throw new DBException($sMensagem);
    }
  }
}
?>