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

require_once modification("model/configuracao/InstituicaoRepository.model.php");
/**
 * Planilha de Arrecadacao
 * Eh um conjunto de receita do municipio para fins de arrecadacao.
 * @package caixa
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.16 $
 */
class PlanilhaArrecadacao {

  /**
   * Codigo da planilha
   * @var integer
   */
  private $iCodigo;

  /**
   * Data de inclusao da planilha
   * @var DBDate
   */
  private $oDataCriacao;

  /**
   * Data de autencicacao da planilha
   * @var DBDate
   */
  private $oDataAutenticacao;

  /**
   * Instituicao a qual a planilha pertence / foi criada
   * @var Instituicao
   */
  private $oInstituicao;

  /**
   * Colecao de Receitas pertencentes a planilha
   * @var array de ReceitaPlanilha
   */
  private $aReceitaPlanilha;

  /**
   * @var string
   */
  private $sProcessoAdministrativo;

  /**
   * Contrutor da classe
   * Se recebido um codigo de planilha, carrega as receitas da planilha
   * @param integer $iCodigo da planilha
   */
  public function __construct($iCodigo = null) {

    if (!empty($iCodigo)) {

      $oDaoPlanilha = new cl_placaixa();
      $sSqlPlanilha = $oDaoPlanilha->sql_query_processo($iCodigo);
      $rsPlanilha   = $oDaoPlanilha->sql_record($sSqlPlanilha);

      if ($rsPlanilha && $oDaoPlanilha->numrows > 0) {

        $oPlanilha          = db_utils::fieldsMemory($rsPlanilha, 0);
        $this->iCodigo      = $oPlanilha->k80_codpla;
        $this->iInstituicao = InstituicaoRepository::getInstituicaoByCodigo($oPlanilha->k80_instit);
        $this->oDataCriacao = new DBDate($oPlanilha->k80_data);

        if (!empty($oPlanilha->k80_dtaut)) {
          $this->oDataAutenticacao = new DBDate($oPlanilha->k80_dtaut);
        }

        if (!empty($oPlanilha->k144_numeroprocesso)) {
          $this->sProcessoAdministrativo = trim($oPlanilha->k144_numeroprocesso);
        }
      }
    }
    return $this;
  }

  /**
   * Retorna o codigo da planilha
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }


  /**
   * Retorna a Instituicao a qual planilha pertence
   * @return Instituicao
   */
  public function getInstituicao() {
    return $this->oInstituicao;
  }

  /**
   * define a instituicao a qual a planilha esta sendo criada
   * @param Instituicao oInstituicao
   */
  public function setInstituicao(Instituicao $oInstituicao) {
    $this->oInstituicao = $oInstituicao;
  }

  /**
   * Retorna uma instancia de DBData com a data da autenticacao
   * @return DBDate
   */
  public function getDataCriacao() {
    return $this->oDataCriacao;
  }

  /**
   * define a data da criacao da planilha
   * a string recebida deve estar no seguinte formato:
   *      dia/mes/ano  --> 21/03/2012
   *      ano-mes-dia  --> 2012-03-21
   * @param string $sDataCriacao
   */
  public function setDataCriacao($sDataCriacao) {
    $this->oDataCriacao = new DBDate($sDataCriacao);
  }

  /**
   * Retorna uma instancia de DBData com a data da autenticacao
   * @return DBDate
   */
  public function getDataAutenticacao() {
    return $this->oDataAutenticacao;
  }


  /**
   * @param $sDataAutenticacao
   */
  public function setDataAutenticacao($sDataAutenticacao) {
    $this->oDataAutenticacao = new DBDate($sDataAutenticacao);
  }

  /**
   * Retorna uma colecao de receitas vinculadas a planilha
   * @return ReceitaPlanilha[]
   */
  public function getReceitasPlanilha() {

    if (!empty($this->iCodigo) && count($this->aReceitaPlanilha) == 0) {

      $oDaoPlanilhaReceita = db_utils::getDao('placaixarec');
      $sWhere              = " k81_codpla = {$this->iCodigo}";
      $sSqlPlanilhaReceita = $oDaoPlanilhaReceita->sql_query_file(null, " k81_seqpla ", null, $sWhere);
      $rsPlanilhaReceita   = $oDaoPlanilhaReceita->sql_record($sSqlPlanilhaReceita);
      $iNumeroRegistro     = $oDaoPlanilhaReceita->numrows;

      if ($rsPlanilhaReceita && $iNumeroRegistro > 0) {

        for ($i = 0; $i < $iNumeroRegistro; $i++) {

          $oReceitaPlanilha         = new ReceitaPlanilha(db_utils::fieldsMemory($rsPlanilhaReceita, $i)->k81_seqpla);
          $this->aReceitaPlanilha[] = $oReceitaPlanilha;
        }
      }
    }
    return $this->aReceitaPlanilha;
  }

  /**
   * Adiciona uma instacia de ReceitaPlanilha a Planilha
   * @param ReceitaPlanilha $oReceitaPlanilha
   */
  public function adicionarReceitaPlanilha(ReceitaPlanilha $oReceitaPlanilha) {

    $this->aReceitaPlanilha[] = $oReceitaPlanilha;
  }

  /**
   * Persiste a planilha no banco
   * @throws BusinessException em caso de erro
   * @return boolean
   */
  public function salvar () {

    if (!db_utils::inTransaction()) {
      throw new BusinessException("Sem transação ativa.");
    }

    $oDaoPlanilha             = db_utils::getDao('placaixa');

    if (empty($this->iCodigo)) {

      $oDaoPlanilha->k80_instit = $this->oInstituicao->getSequencial();
      $oDaoPlanilha->k80_data   = $this->oDataCriacao->getDate();
      $oDaoPlanilha->incluir(null);
      $this->iCodigo = $oDaoPlanilha->k80_codpla;
    } else {

      $oDaoPlanilha->k80_codpla =  $this->iCodigo;
      $oDaoPlanilha->alterar($this->iCodigo);
    }

    if ($oDaoPlanilha->erro_status == 0) {
      throw new BusinessException($oDaoPlanilha->erro_msg);
    }


    $this->salvarProcessoAdministrativo();


    if (count($this->aReceitaPlanilha) > 0) {

      foreach ($this->aReceitaPlanilha as $oReceitaPlanilha) {
        $oReceitaPlanilha->salvar($this->iCodigo);
      }
    }

    return true;
  }

  /**
   * @return bool
   * @throws BusinessException
   */
  private function salvarProcessoAdministrativo() {

    $this->excluirProcessoAdministrativo();
    $this->sProcessoAdministrativo = trim($this->sProcessoAdministrativo);
    if (empty($this->sProcessoAdministrativo)) {
      return false;
    }

    $oDaoPlanilhaProcesso = new cl_placaixaprocesso();
    $oDaoPlanilhaProcesso->k144_sequencial     = null;
    $oDaoPlanilhaProcesso->k144_placaixa       = $this->getCodigo();
    $oDaoPlanilhaProcesso->k144_numeroprocesso = $this->sProcessoAdministrativo;
    $oDaoPlanilhaProcesso->incluir(null);
    if ($oDaoPlanilhaProcesso->erro_status == "0") {
      throw new BusinessException(_M("financeiro.caixa.PlanilhaArrecadacao.vincular_processo_administrativo"));
    }
    return true;
  }

  /**
   * Autentica uma planilha de arrecadação
   * @return boolean
   */
  public function autenticar() {

    $oAutenticacaoPlanilha = new AutenticacaoPlanilha($this);
    $oAutenticacaoPlanilha->autenticar();
    return true;
  }

  /**
   * Estorna uma planilha autenticada.
   * @throws BusinessException
   * @return boolean
   */
  public function estornar() {

    $oAutenticacaoPlanilha = new AutenticacaoPlanilha($this);
    $oAutenticacaoPlanilha->estornar();
    return true;
  }

  /**
   * Método que verifica se as receitas da planilha possuem lancamento contabil
   * @return boolean
   */
  public function existeLancamentoContabil() {

    $oDaoCorPlaCaixa       = db_utils::getDao('corplacaixa');
    $sWherePlanilha        = "placaixarec.k81_codpla = {$this->getCodigo()}";
    $sSqlBuscaAutenticacao = $oDaoCorPlaCaixa->sql_query_planilha_receita(null, null, null, "*", null, $sWherePlanilha);
    $rsBuscaAutenticacao   = $oDaoCorPlaCaixa->sql_record($sSqlBuscaAutenticacao);
    if ($oDaoCorPlaCaixa->numrows == 0) {
      return false;
    }
    return true;
  }

  /**
   * metodo de exclusão de planilha não autenticada
   * @return boolean
   * @throws DBException|BusinessException
   */
  public function excluir (){

    if ($this->existeLancamentoContabil()) {
      throw new BusinessException( _M("financeiro.caixa.PlanilhaArrecadacao.possui_lancamento_contabil") );
    }

    $this->excluirReceitas();
    $this->excluirProcessoAdministrativo();

  	$oDaoPlacaixa = db_utils::getDao("placaixa");
  	$oDaoPlacaixa->excluir($this->getCodigo());
  	if ($oDaoPlacaixa->erro_status == '0' || $oDaoPlacaixa->erro_status == 0) {
  		throw new DBException("Não foi possível excluir a planilha.");
  	}
  	return true;
  }

  /**
   * Exclui as receitas vinculadas na planilha
   * @return true
   */
  public function excluirReceitas() {

    $aReceitas = $this->getReceitasPlanilha();
    if (count($aReceitas) > 0) {

      foreach ($aReceitas as $oReceitaPlanilha) {
        $oReceitaPlanilha->excluir();
      }
    }
    return true;
  }

  /**
   * @return bool
   * @throws BusinessException
   */
  private function excluirProcessoAdministrativo() {

    $oDaoPlanilhaProcesso = new cl_placaixaprocesso();
    $oDaoPlanilhaProcesso->excluir(null, "k144_placaixa = {$this->getCodigo()}");
    if ($oDaoPlanilhaProcesso->erro_status == "0") {
      throw new BusinessException(_M("financeiro.caixa.PlanilhaArrecadacao.exclusao_processo_administrativo"));
    }
    return true;
  }

  /**
   * @param $sProcesso
   */
  public function setProcessoAdministrativo($sProcesso) {
    $this->sProcessoAdministrativo = $sProcesso;
  }

  /**
   * @return string
   */
  public function getProcessoAdministrativo() {
    return $this->sProcessoAdministrativo;
  }
}