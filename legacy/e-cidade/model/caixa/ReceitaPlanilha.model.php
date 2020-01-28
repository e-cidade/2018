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
 * Caminho das mensagens utilizado pelo Model
 */
define("URL_MENSAGEM_RECEITAPLANILHA", "financeiro.caixa.ReceitaPlanilha.");

/**
 * Receita Planilha
 * guarda informacoes da receita de uma planilha
 * @package caixa
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.9 $
 */
class ReceitaPlanilha {

  /**
   * Codigo do Lancamento da Receita
   * codigo sequencial
   * @var integer
   */
  private $iCodigo;

  /**
   * Conta da tesouraria
   * @var contaTesouraria
   */
  private $oContaTesouraria;

  /**
   * Tipo da receita de acordo com a tabrec
   * @var integer
   */
  private $iTipoReceita;

  /**
   * Descrição completa da Receita
   */

  private $sDescricaoReceita;

  /**
   * Valor da Receita
   * @var double
   */
  private $nValor;

  /**
   * Observacao da Receita
   * @var string
   */
  private $sObservacao;

  /**
   * Recurso da Receita
   * na tabela placaixarec o campo de referencia eh k81_codigo
   * @var Recurso
   */
  private $oRecurso;

  /**
   * Data de recebimento
   * @var DBDate
   */
  private $oDataRecebimento;

  /**
   * Operacao bancaria
   * @var string
   */
  private $sOperacaoBancaria;

  /**
   * A Origem da Receita receita pode assumir os seguintes valores:
   *   1 - CGM
   *   2 - Inscricao
   *   3 - Matricula
   * @var integer
   */
  private $iOrigem;

  /**
   * CGM vai ser definido de acordo com a origem.
   * Exemplo:
   *   Se origem = 1  define o cgm selecionado
   *   Se origem = 2  busca o cgm da inscricao (issbase)
   *   Se origem = 3  busca o cgm da matricula (iptubase)
   * @var CgmBase
   */
  private $oCgm;

  /**
   * Caracteristica Peculiar
   * @var CaracteristicaPeculiar
   */
  private $oCaracteristicaPeculiar;

  /**
   * Numero da Inscricao
   * @var integer
   */
  private $iInscricao;

  /**
   * Numero da Matricula.
   * @var integer
   */
  private $iMatricula;


  /**
   * @todo esqueci de buscar a matricula ou inscricao se origem for 2 ou 3
   * @param integer $iCodigo
   */
  public function __construct($iCodigo = null) {

    if (!empty($iCodigo)) {

      $oDaoReceitaPlanilha = db_utils::getDao('placaixarec');
      $sSqlReceitaPlanilha = $oDaoReceitaPlanilha->sql_query_matric_inscr($iCodigo);
      $rsReceitaPlanilha   = $oDaoReceitaPlanilha->sql_record($sSqlReceitaPlanilha);

      if ($rsReceitaPlanilha && $oDaoReceitaPlanilha->numrows > 0) {

        $oReceitaPlanilha              = db_utils::fieldsMemory($rsReceitaPlanilha, 0);
        $this->sDescricaoReceita       = $oReceitaPlanilha->k02_drecei;
        $this->iCodigo                 = $oReceitaPlanilha->k81_seqpla;
        $this->oContaTesouraria        = new contaTesouraria($oReceitaPlanilha->k81_conta);
        $this->iTipoReceita            = $oReceitaPlanilha->k81_receita;
        $this->nValor                  = $oReceitaPlanilha->k81_valor;
        $this->sObservacao             = $oReceitaPlanilha->k81_obs;
        $this->oRecurso                = new Recurso($oReceitaPlanilha->k81_codigo);
        $this->oDataRecebimento        = new DBDate($oReceitaPlanilha->k81_datareceb);
        $this->sOperacaoBancaria       = $oReceitaPlanilha->k81_operbanco;
        $this->iOrigem                 = $oReceitaPlanilha->k81_origem;
        $this->oCgm                    = CgmFactory::getInstanceByCgm($oReceitaPlanilha->k81_numcgm);
        $this->oCaracteristicaPeculiar = new CaracteristicaPeculiar($oReceitaPlanilha->k81_concarpeculiar);
        $this->iInscricao              = $oReceitaPlanilha->k76_inscr;
        $this->iMatricula              = $oReceitaPlanilha->k77_matric;

      }
    }

    return $this;
  }

  /**
   * Método que exclui a receita de uma planilha de arrecadação
   * @throws BusinessException
   * @return boolean true
   */
  public function excluir() {

    $oDaoPlaCaixaRecInscr = new cl_placaixarecinscr();
    $oDaoPlaCaixaRecInscr->excluir(null, "k76_placaixarec = {$this->iCodigo}");
    if ($oDaoPlaCaixaRecInscr->erro_status === "0") {
      throw new BusinessException(_M(URL_MENSAGEM_RECEITAPLANILHA."exclusao_vinculo_inscricao"));
    }

    $oDaoPlaCaixaRecMatric = new cl_placaixarecmatric();
    $oDaoPlaCaixaRecMatric->excluir(null, "k77_placaixarec = {$this->iCodigo}");
    if ($oDaoPlaCaixaRecMatric->erro_status === "0") {
      throw new BusinessException(_M(URL_MENSAGEM_RECEITAPLANILHA."exclusao_vinculo_matricula"));
    }

    $oDaoEmpenhoFolha = new cl_rhempenhofolharubricaplanilha();
    $oDaoEmpenhoFolha->excluir(null, "rh111_placaixarec = {$this->iCodigo}");
    if ($oDaoPlaCaixaRecMatric->erro_status == "0") {
      throw new BusinessException(_M(URL_MENSAGEM_RECEITAPLANILHA."exclusao_vinculo_folha"));
    }

    $oDaoPlaCaixaRec = new cl_placaixarec();
    $oDaoPlaCaixaRec->excluir($this->iCodigo);
    if ($oDaoPlaCaixaRec->erro_status === "0") {
      throw new BusinessException(_M(URL_MENSAGEM_RECEITAPLANILHA."exclusao_receita_planilha"));
    }
    return true;
  }

  /**
   * Retorna descrição completa da receita
   * @return string
   */
  public function getDescricaoReceita() {
    return $this->sDescricaoReceita;
  }

  /**
   * Seta descrição completa da receita
   * @param string $sDescricaoReceita
   */
  public function setDescricaoReceita($sDescricaoReceita) {
    $this->sDescricaoReceita = $sDescricaoReceita;
  }


  /**
   * Retorna o codigo de lancamento da receita.
   * @return integer codigo sequencial
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Seta o codigo de lancamento da receita.
   * @param integer $iCodigoSequencial
   */
  public function setCodigo($iCodigoSequencial) {
    $this->iCodigo = $iCodigoSequencial;
  }


  /**
   * Retorna a conta da tesouraria
   * @return contaTesouraria
   */
  public function getContaTesouraria() {
    return $this->oContaTesouraria;
  }

  /**
   * define uma instancia da conta da tesouraria
   * @param contaTesouraria $oContaTesouraria
   */
  public function setContaTesouraria(contaTesouraria $oContaTesouraria) {
    $this->oContaTesouraria = $oContaTesouraria;
  }

  /**
   * Retorna o tipo da receita
   * vinculo com a tabrec
   * @return integer
   */
  public function getTipoReceita() {
    return $this->iTipoReceita;
  }

  /**
   * Define o tipo da receita de acordo com a tabrec
   * tabela placaixarec -- campo k81_receita
   * @param integer
   */
  public function setTipoReceita($iTipoReceita) {
    $this->iTipoReceita = $iTipoReceita;
  }

  /**
   * Retorna o valor da receita
   * @return double
   */
  public function getValor() {
    return $this->nValor;
  }

  /**
   * Define o valor da receita
   * @param double $nValor
   */
  public function setValor($nValor) {
    $this->nValor = $nValor;
  }

  /**
   * Retorna a Observacao
   * @return string Observacao
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

  /**
   * Define uma observacao para a receita
   * @param string $sObservacao
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }

  /**
   * Retorna o recurso da receita
   * na tabela placaixarec o campo de referencia eh k81_codigo
   * @return Recurso
   */
  public function getRecurso() {
    return $this->oRecurso;
  }

  /**
   * Define um recurso para a receita
   * na tabela placaixarec o campo de referencia eh k81_codigo
   * @param Recurso $oRecurso
   */
  public function setRecurso(Recurso $oRecurso) {
    $this->oRecurso = $oRecurso;
  }


  /**
   * Retorna a data de recebimento (na realidade eh a data de inclusao)
   * @return DBDate $oDataRecebimento
   */
  public function getDataRecebimento() {
    return $this->oDataRecebimento;
  }

  /**
   * Define a data de recebimento (na realidade eh a data de inclusao)
   * @param DBDate $oDataRecebimento
   */
  public function setDataRecebimento(DBDate $oDataRecebimento) {
    $this->oDataRecebimento = $oDataRecebimento;
  }

  /**
   * Retorna a operacao bancaria
   * @return string
   */
  public function getOperacaoBancaria() {
    return $this->sOperacaoBancaria;
  }

  /**
   * Retorna a operacao bancaria
   * @param string
   */
  public function setOperacaoBancaria($sOperacaoBancaria) {
    $this->sOperacaoBancaria = $sOperacaoBancaria;
  }

  /**
   * A Origem da Receita receita pode assumir os seguintes valores:
   *   1 - CGM
   *   2 - Inscricao
   *   3 - Matricula
   * @return integer
   */
  public function getOrigem() {
    return $this->iOrigem;
  }

  /**
   * A Origem da Receita receita pode assumir os seguintes valores:
   *   1 - CGMCaracteristicaPeculiar
   *   2 - Inscricao
   *   3 - Matricula
   * @param integer
   */
  public function setOrigem($iOrigem) {
    $this->iOrigem = $iOrigem;
  }


  /**
   * CGM vai ser definido de acordo com a origem.
   * Exemplo:
   *   Se origem = 1  define o cgm selecionado
   *   Se origem = 2  busca o cgm da inscricao (issbase)
   *   Se origem = 3  busca o cgm da matricula (iptubase)
   * @return CgmBase
   */
  public function getCGM() {
    return $this->oCgm;
  }

 /**
   * CGM vai ser definido de acordo com a origem.
   * Exemplo:
   *   Se origem = 1  define o cgm selecionado
   *   Se origem = 2  busca o cgm da inscricao (issbase)
   *   Se origem = 3  busca o cgm da matricula (iptubase)
   * @param CgmBase
   */
  public function setCGM(CgmBase $oCgm) {
    $this->oCgm = $oCgm;
  }

  /**
   * Retorna a caracteristica peculiar
   * @return CaracteristicaPeculiar
   */
  public function getCaracteristicaPeculiar() {
    return $this->oCaracteristicaPeculiar;
  }

  /**
   * Define a caracteristica peculiar
   * @param CaracteristicaPeculiar $oCaracteristicaPeculiar
   */
  public function setCaracteristicaPeculiar(CaracteristicaPeculiar $oCaracteristicaPeculiar) {
    $this->oCaracteristicaPeculiar = $oCaracteristicaPeculiar;
  }

  /**
   * Retorna o numero da Inscricao
   * @return integer
   */
  public function getInscricao() {
    return $this->iInscricao;
  }

  /**
   * Define o numero da Inscricao
   * @param integer $iInscricao
   */
  public function setInscricao($iInscricao) {
    $this->iInscricao = $iInscricao;
  }


  /**
   * Retorna o numero da Matricula
   * @return integer
   */
  public function getMatricula() {
    return $this->iMatricula;
  }

  /**
   * Define o numero da Matricula
   * @param integer $iMatricula
   */
  public function setMatricula($iMatricula) {
    $this->iMatricula = $iMatricula;
  }


  /**
   * Vincula a receita a uma planilha
   * @param integer $iCodigoPlanilha
   * @throws BusinessException
   * @return boolean
   */
  public function salvar($iCodigoPlanilha) {

    if (!db_utils::inTransaction()) {
      throw new BusinessException("Transação com a base de dados não encontrada.");
    }

    if (empty($iCodigoPlanilha) || !is_numeric($iCodigoPlanilha)) {
      throw new BusinessException("Número da planilha não informado.");
    }

    $oDaoReceitaPlanilha = db_utils::getDao('placaixarec');
    $oDaoReceitaPlanilha->k81_codpla         = $iCodigoPlanilha;
    $oDaoReceitaPlanilha->k81_conta          = $this->oContaTesouraria->getCodigoConta();
    $oDaoReceitaPlanilha->k81_receita        = $this->iTipoReceita;
    $oDaoReceitaPlanilha->k81_valor          = $this->nValor;
    $oDaoReceitaPlanilha->k81_obs            = addslashes($this->sObservacao);
    $oDaoReceitaPlanilha->k81_codigo         = $this->oRecurso->getCodigo();
    $oDaoReceitaPlanilha->k81_datareceb      = $this->oDataRecebimento->convertTo(DBDate::DATA_EN);
    $oDaoReceitaPlanilha->k81_operbanco      = $this->sOperacaoBancaria;
    $oDaoReceitaPlanilha->k81_origem         = $this->iOrigem;
    $oDaoReceitaPlanilha->k81_numcgm         = $this->oCgm->getCodigo();
    $oDaoReceitaPlanilha->k81_concarpeculiar = $this->oCaracteristicaPeculiar->getSequencial();
    $oDaoReceitaPlanilha->k81_seqpla         = $this->iCodigo;

    if(empty($this->iCodigo)) {
      $oDaoReceitaPlanilha->incluir(null);
    } else {
      $oDaoReceitaPlanilha->alterar($this->iCodigo);
    }

    if ($oDaoReceitaPlanilha->erro_status == 0) {
      throw new BusinessException($oDaoReceitaPlanilha->erro_msg);
    }

    $this->iCodigo = $oDaoReceitaPlanilha->k81_seqpla;

    switch ($this->iOrigem) {

      case 2 :

        $this->vincularComInscricao();
        break;

      case 3:

        $this->vinculaComMatricula();
        break;
    }
    return true;
  }

  /**
   * Vincula as receitas com a matricula informada no cadastro
   * @throws BusinessException
   * @return boolean true
   */
  protected function vinculaComMatricula() {

    $oDaoReceitaMatricula = db_utils::getDao('placaixarecmatric');

    $oDaoReceitaMatricula->k77_sequencial  = null;
    $oDaoReceitaMatricula->k77_placaixarec = $this->iCodigo;
    $oDaoReceitaMatricula->k77_matric      = $this->iMatricula;

    $oDaoReceitaMatricula->incluir(null);

    if ($oDaoReceitaMatricula->erro_status == 0) {

      $sMsgErro  = "Não foi possível vincular a receita com a matricula. \n";
      $sMsgErro .= $oDaoReceitaMatricula->erro_msg;
      throw new BusinessException($sMsgErro);
    }
    return true;
  }

  /**
   * Vincula as receitas com a inscrição informada no cadastro
   * @throws BusinessException
   * @return boolean true
   */
  protected function vincularComInscricao() {

    $oDaoReceitaInscricao = db_utils::getDao('placaixarecinscr');

    $oDaoReceitaInscricao->k76_sequencial  = null;
    $oDaoReceitaInscricao->k76_placaixarec = $this->iCodigo;
    $oDaoReceitaInscricao->k76_inscr       = $this->iInscricao;

    $oDaoReceitaInscricao->incluir(null);

    if ($oDaoReceitaInscricao->erro_status == 0) {

      $sMsgErro  = "Não foi possível vincular a receita com a inscricao. \n";
      $sMsgErro .= $oDaoReceitaInscricao->erro_msg;
      throw new BusinessException($sMsgErro);
    }
    return true;
  }

}