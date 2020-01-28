<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
 * model base para contacorrente
 * @author  rafael.lopes rafael.lopes@dbseller.com.br
 * @name    ContaCorrenteBase
 * @package contabilidade
 */
abstract class ContaCorrenteBase {

  /**
   * objeto institui��o
   * @var object
   */
  protected $oInstituicao;

  /**
   * objeto plano de contas
   * @var object
   */
  protected $oContaPlano;

  /**
   * data de Lancamento
   * @var date
   */
  protected $dDataLancamento;

  /**
   * C�digo do lan�amento (conlancamval)
   * @var integer
   */
  protected $iCodigoLancamento;

  /**
   * C�digo reduzido da conta no plano de contas PCASP
   * @var integer
   */
  protected $iCodigoReduzido;

  /**
   * Lan�amento auxiliar do lan�amento cont�bil
   * @var ILancamentoAuxiliar - Objeto que implemente a interface de Lan�amento Auxiliar
   */
  protected $oLancamentoAuxiliar;

  /**
   * Constante para opera��o de Cr�dito
   */
  const OPERACAO_CREDITO = "C";

  /**
   * Constante para opera��o de D�bito
   */
  const OPERACAO_DEBITO = "D";

  /**
   * Tipo de Lan�amento
   * C - Cr�dito
   * D - D�bito
   * @var string
   */
  protected $sTipoLancamento;

  /**
   * Valor do lan�amento
   * @var float
   */
  protected $nValorLancamento;

  /**
   * Data que o lan�amento foi realizado
   * @var unknown
   */
  protected $dtLancamento;

  /**
   * Conta Corrente
   * @var ContaCorrente
   */
  protected $oContaCorrente;

  /**
   * Seta as propriedades padr�o para a execu��o do conta corrente
   * @param integer $iCodigoLancamento
   * @param integer $iCodigoReduzido
   * @param ILancamentoAuxiliar $oLancamentoAuxiliar
   * @throws BusinessException
   */
  public function __construct($iCodigoLancamento, $iCodigoReduzido, ILancamentoAuxiliar $oLancamentoAuxiliar) {

    $this->iCodigoLancamento   = $iCodigoLancamento;
    $this->iCodigoReduzido     = $iCodigoReduzido;
    $this->oLancamentoAuxiliar = $oLancamentoAuxiliar;
    $iInstituicaoSessao        = db_getsession('DB_instit');
    $iAnoSessao                = db_getsession('DB_anousu');
    $this->setInstituicao(InstituicaoRepository::getInstituicaoByCodigo($iInstituicaoSessao));
    $this->setContaPlano(ContaPlanoPCASPRepository::getContaByCodigo(null,
                                                                     $iAnoSessao,
                                                                     $iCodigoReduzido,
                                                                     $iInstituicaoSessao
                                                                    )
                        );

    /**
     * Buscamos os valores da tabela conlancamval para sabermos se � um lan�amento a cr�dito ou a d�bito
     */
    $oDaoConLancamVal    = db_utils::getDao("conlancamval");
    $sCamposBusca        = "c69_sequen, c69_credito, c69_debito, c69_data, c69_valor";
    $sSqlBuscaLancamento = $oDaoConLancamVal->sql_query_file($this->iCodigoLancamento, $sCamposBusca, null, null);
    $rsBuscaLancamento   = $oDaoConLancamVal->sql_record($sSqlBuscaLancamento);
    if ($oDaoConLancamVal->numrows == 0) {
      throw new BusinessException("Lan�amento {$this->iCodigoLancamento} n�o encontrado.");
    }
    $oStdLancamento         = db_utils::fieldsMemory($rsBuscaLancamento, 0);
    $this->sTipoLancamento  = ($oStdLancamento->c69_debito == $this->iCodigoReduzido) ? self::OPERACAO_DEBITO :
                                                                                        self::OPERACAO_CREDITO;
    $this->nValorLancamento = $oStdLancamento->c69_valor;
    return true;
  }

  /**
   * Atualiza o saldo da conta corrente na tabela contacorrentesaldo
   * @param integer $iContaCorrenteSequencial - Sequencial na tabela contacorrentedetalhe
   * @return string Retornamos uma string com os valores D (d�bito) ou C (cr�dito)
   */
  protected function atualizarSaldo($iContaCorrenteSequencial, $dtLancamento = null) {

    $sTipoLancamento = $this->sTipoLancamento;

    /**
		 * Podemos estar reprocessando um lan�amento, a� n�o podemos alterar a sua data.
     */
    if ($dtLancamento === null) {
      $dtLancamento = date("Y-m-d", db_getsession("DB_datausu"));
    }

    list($iAno, $iMes, $iDia) = explode("-", $dtLancamento);

    $oDaoContaCorrenteSaldo = db_utils::getDao("contacorrentesaldo");

    $sWhere  = "     c29_contacorrentedetalhe = {$iContaCorrenteSequencial}";
    $sWhere .= " and c29_anousu = {$iAno}";
    $sWhere .= " and c29_mesusu = {$iMes}";

    $sSqlBuscaSaldo = $oDaoContaCorrenteSaldo->sql_query_file(null, "*", null, $sWhere);
    $rsBuscaSaldo   = $oDaoContaCorrenteSaldo->sql_record($sSqlBuscaSaldo);

    /**
     * Caso j� haver registros na contacorrentesaldo, s� atualizamos os campos necess�rios
     */
    if ($oDaoContaCorrenteSaldo->numrows > 0) {

      $oStdSaldo                              = db_utils::fieldsMemory($rsBuscaSaldo, 0);
      $oDaoContaCorrenteSaldo->c29_sequencial = $oStdSaldo->c29_sequencial;

      /**
       * Se for tipo cr�dito atualizamos incrementamops no campo cr�dito o valor do lan�amento
       * E deixamos o campo d�bito com o mesmo valor
       */
      if ($sTipoLancamento == self::OPERACAO_CREDITO) {

        $oDaoContaCorrenteSaldo->c29_credito = $oStdSaldo->c29_credito + $this->nValorLancamento;
      } else {
        /**
         * E se for tipo d�bito fazemos o contr�rio.
         * O campo cr�dito fica com o mesmo valor
         * No campo d�bito incrementamos o valor do lan�amento
         */
        $oDaoContaCorrenteSaldo->c29_debito = $oStdSaldo->c29_debito + $this->nValorLancamento;
      }

      $oDaoContaCorrenteSaldo->alterar($oStdSaldo->c29_sequencial);

    } else {

      /**
       * Se n�o inclu�mos um registro novo com os valores.
       */
      if ($sTipoLancamento == self::OPERACAO_CREDITO) {

        /**
         * Se for tipo cr�dito inserimos o registro com o valor do lan�amento na coluna c29_cr�dito
         * E o campo d�bito � setado para 0
         */
        $oDaoContaCorrenteSaldo->c29_credito = $this->nValorLancamento;
        $oDaoContaCorrenteSaldo->c29_debito  = "0";
      } else {

        /**
         * Se n�o fazemos o contr�rio
         * O campo cr�dito � setado para 0
         * E o campo d�bito vai com o valor do lan�amento na coluna c23_debito
         */
        $oDaoContaCorrenteSaldo->c29_credito = "0";
        $oDaoContaCorrenteSaldo->c29_debito  = $this->nValorLancamento;
      }

      $oDaoContaCorrenteSaldo->c29_contacorrentedetalhe = $iContaCorrenteSequencial;
      $oDaoContaCorrenteSaldo->c29_anousu               = $iAno;
      $oDaoContaCorrenteSaldo->c29_mesusu               = $iMes;
      $oDaoContaCorrenteSaldo->c29_sequencial           = null;
      $oDaoContaCorrenteSaldo->incluir($oDaoContaCorrenteSaldo->c29_sequencial);
    }

    if ($oDaoContaCorrenteSaldo->erro_status == "0") {
      throw new BusinessException("N�o foi poss�vel salvar o saldo da conta corrente.");
    }

    /**
     * Retornamos o tipo do lan�amento, para poder vincular da tabela contacorrentedetalhe com a
     * contacorrentedetalheconlancamval e deixamos o campo c28_tipo com D (d�bito) ou C (cr�dito)
     */
    return $sTipoLancamento;
  }

  /**
   * metodo responsavel pelo vinculo
   * conlancamval a tabela contacorrentedetalhe
   * na tabela contacorrentedetalheconlancamval
   * @param integer $iContaCorrenteDetalhe
   * @param string  $sTipoLancamento - Define se o lan�amento foi a cr�dito ou a d�bito
   */
  protected function vincularLancamentos($iContaCorrenteDetalhe, $sTipoLancamento) {

    if(!db_utils::inTransaction()) {
      throw new DBException("ERRO [1] - N�o foi encontrada transa��o com o banco de dados. Procedimento abortado.");
    }

    $oDaoContaCorrenteDetalheConlancamval = db_utils::getDao("contacorrentedetalheconlancamval");

    $oDaoContaCorrenteDetalheConlancamval->c28_contacorrentedetalhe = $iContaCorrenteDetalhe;
    $oDaoContaCorrenteDetalheConlancamval->c28_conlancamval         = $this->iCodigoLancamento;
    $oDaoContaCorrenteDetalheConlancamval->c28_tipo                 = $sTipoLancamento;
    $oDaoContaCorrenteDetalheConlancamval->incluir(null);
    if ($oDaoContaCorrenteDetalheConlancamval->erro_status == 0) {

      $sMsgErro  = "ERRO [2] - N�o foi poss�vel vincular o lan�amento {$this->iCodigoLancamento} com a ";
      $sMsgErro .= "conta corrente {$iContaCorrenteDetalhe}.\n{$oDaoContaCorrenteDetalheConlancamval->erro_msg}";
      throw new DBException($sMsgErro);
    }
  }

  /**
   * M�todo est�tico que atualiza o saldo da conta corrente quando estamos em um "reprocessamento" de lan�amento
   *
   * Para o funcionamento correto, � preciso passar um objeto do tipo stdClass com as
   * propriedades da tabela conlancamval
   * @param stdClass $oStdConLancamVal
   * @return boolean
   *
   * @todo - refatorar para receber um objeto do tipo LancamentoContabilPartida
   *
   */
  public static function atualizarSaldoContaCorrenteReprocessamento($oStdConLancamVal) {

    $oDaoContaCorrenteSaldo   = db_utils::getDao("contacorrentesaldo");
    list($iAno, $iMes, $iDia) = explode("-", $oStdConLancamVal->c69_data);
    $sCampos                  = "c29_sequencial, c28_tipo, c29_debito, c29_credito";
    $sWhere                   = "     c69_sequen = {$oStdConLancamVal->c69_sequen} ";
    $sWhere                  .= " and c29_mesusu = {$iMes} ";
    $sWhere                  .= " and c29_anousu = {$iAno} ";
    $sSqlBuscaSaldo           = $oDaoContaCorrenteSaldo->sql_query_buscasaldo(null, $sCampos, null, $sWhere);
    $rsBuscaSaldo             = $oDaoContaCorrenteSaldo->sql_record($sSqlBuscaSaldo);

    /**
     * Se encontramos registros nas tabelas referentes a conta corrente
     * devemos atualizar os seus saldos
     * Caso n�o encontre simplesmente n�o faz nada.
     */
    if ($oDaoContaCorrenteSaldo->numrows > 0) {

      for ($iReduzido = 0; $iReduzido < $oDaoContaCorrenteSaldo->numrows; $iReduzido++) {

        $oStdBuscaSaldo                 = db_utils::fieldsMemory($rsBuscaSaldo, $iReduzido);
        $oDaoContaCorrenteSaldoAuxiliar = db_utils::getDao("contacorrentesaldo");

        /**
				 * Verifica qual a opera��o do lan�amento (cr�dito ou d�bito)
				 */
        if ($oStdBuscaSaldo->c28_tipo == self::OPERACAO_CREDITO) {
          $oDaoContaCorrenteSaldoAuxiliar->c29_credito = $oStdBuscaSaldo->c29_credito - $oStdConLancamVal->c69_valor;
        } else {
          $oDaoContaCorrenteSaldoAuxiliar->c29_debito = $oStdBuscaSaldo->c29_debito - $oStdConLancamVal->c69_valor;
        }

        $oDaoContaCorrenteSaldoAuxiliar->c29_sequencial = $oStdBuscaSaldo->c29_sequencial;
        $oDaoContaCorrenteSaldoAuxiliar->alterar($oStdBuscaSaldo->c29_sequencial);
        if ($oDaoContaCorrenteSaldoAuxiliar->erro_status == "0") {
          throw new BusinessException("Erro ao atualizar saldo da Conta Corrente. Contate o suporte.");
        }

        /**
         * Ap�s atualizarmos o saldo da conta corrente, exclu�mos o v�nculo entre o detalhamento da conta corrente
         * e os valores que nela constavam
         */
        $oDaoConLancamValContaCorrente = db_utils::getDao("contacorrentedetalheconlancamval");
        $oDaoConLancamValContaCorrente->excluir(null, "c28_conlancamval = {$oStdConLancamVal->c69_sequen}");
        if ($oDaoConLancamValContaCorrente->erro_status == "0") {
          throw new BusinessException("N�o foi poss�vel excluir o v�nculo entre a conta corrente e o lancamento cont�bil.");
        }
      }
    }
    return true;
  }

  /**
   * Retorna o c�digo do lan�amento (conlancamval)
   * @return integer
   */
  public function getCodigoLancamento() {
    return $this->iCodigoLancamento;
  }

  /**
   * Retorna o c�digo reduzido da conta no plano de contas PCASP
   * @return integer
   */
  public function getCodigoReduzido() {
    return $this->iCodigoReduzido;
  }

  /**
   * Retorna o objeto da institui��o
   * @return object
   */
  public function getInstituicao() {
    return $this->oInstituicao;
  }

  /**
   * Recebe o objeto institui��o
   * @param $oInstituicao
   */
  public function setInstituicao(Instituicao $oEntidade) {
    $this->oInstituicao = $oEntidade;
  }

  /**
   * Retorna o objeto do plano de contas
   * @return ContaPlanoPCASP
   */
  public function getContaPlano() {
    return $this->oContaPlano;
  }

  /**
   * Recebe o objeto plano de conta
   * @param $oContaPlano
   */
  public function setContaPlano(ContaPlanoPCASP $oContaPlano) {
    $this->oContaPlano = $oContaPlano;
  }


  /**
   * Retorna data do DataLancamento
   * @return date
   */
  public function getDataLancamento() {
    return $this->dDataLancamento;
  }

  /**
   * Recebe a data do DataLancamento
   * @param date $dDataLancamento
   */
  public function setDataLancamento($dDataLancamento) {
    $this->dDataLancamento = $dDataLancamento;
  }

  /**
   * Retorna o objeto Lan�amento Auxiliar
   * @return ILancamentoAuxiliar
   */
  public function getLancamentoAuxiliar() {
    return $this->oLancamentoAuxiliar;
  }

  /**
   * Seta a conta corrente
   * @param ContaCorrente $oContaCorrente
   */
  public function setContaCorrente(ContaCorrente $oContaCorrente) {
    $this->oContaCorrente = $oContaCorrente;
  }

  /**
   * Retorna a conta corrente
   * @return ContaCorrente
   */
  public function getContaCorrente() {
    return $this->oContaCorrente;
  }
  
  /**
   * metodo para reprocessar saldo das conta corrente
   * @param iCodigoContaCorrenteDetalhe integer
   * @param dtLancamento date
   */
  public function reprocessarSaldo($iCodigoContaCorrenteDetalhe, $dtLancamento){
  	
  	return $this->atualizarSaldo($iCodigoContaCorrenteDetalhe, $dtLancamento);
  }
  
}
?>