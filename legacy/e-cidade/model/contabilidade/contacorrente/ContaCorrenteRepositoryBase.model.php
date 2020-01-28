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
 * Classe abstrata, mãe das classes de repositórios para o relatório balancete de contas correntes
 * Nesta classe são criados os objetos e arrays necessários para a montagem do relatório
 * @package contabilidade
 * @subpackage contacorrente
 * @author Acácio Schneider <acacio.schneider@dbseller.com.br>
 * @version $Revision: 1.14 $
 */
abstract class ContaCorrenteRepositoryBase {

  /**
   * Objeto ContaCorrente
   * @var ContaCorrente
   */
  protected $oContaCorrente;

  /**
   * Data Inicial para o relatório
   * @var string date
   */
  protected $dtInicial;

  /**
   * Data Final para o relatório
   * @var string date
   */
  protected $dtFinal;

  /**
   * Dia da data inicial que veio do filtro da tela
   * @var integer
   */
  protected $iDiaInicial;

  /**
   * Dia da data final que veio do filtro da tela
   * @var integer
   */
  protected $iDiaFinal;

  /**
   * Mes da data inicial que veio do filtro da tela
   * @var integer
   */
  protected $iMesInicial;

  /**
   * Mes da data final que veio do filtro da tela
   * @var integer
   */
  protected $iMesFinal;

  /**
   * Ano da data inicial que veio do filtro da tela
   * @var integer
   */
  protected $iAnoInicial;

  /**
   * Ano da data final que veio do filtro da tela
   * @var integer
   */
  protected $iAnoFinal;

  /**
   * Array de contas contábeis (conplano PCASP)
   * @var array ContaPlanoPCASP
   */
  protected $aContasContabeis;

  /**
   * Array de valores da tabela contacorrentedetalhe
   * @var array
   */
  protected $aContaCorrenteDetalhe;

  /**
   * Constante para natureza de conta devedora (campo c60_naturezasaldo da tabela conplano)
   */
  const NATUREZA_DEVEDORA = 1;

  /**
   * Constante para natureza de conta credora (campo c60_naturezasaldo da tabela conplano)
   */
  const NATUREZA_CREDORA  = 2;

  /**
   * Setamos os atributos da conta corrente em questao e setamos os atributos de data da classe
   */
  public function __construct($iContaCorrente, $dtInicial, $dtFinal) {

    $this->oContaCorrente = ContaCorrenteRepository::getContaCorrenteByCodigo($iContaCorrente);
    $this->dtInicial      = $dtInicial;
    $this->dtFinal        = $dtFinal;

    /**
     * Quebramos a data para setar os atributos de dia mes e ano utilizados para filtrar os lançamentos
     */
    list($this->iAnoInicial, $this->iMesInicial, $this->iDiaInicial) = explode("-", $this->dtInicial);
    list($this->iAnoFinal,   $this->iMesFinal,   $this->iDiaFinal)   = explode("-", $this->dtFinal);
  }

  /**
   * Seta os atributos das contas correntes e joga no array de dados $this->aContaCorrenteDetalhe
   */
  protected function getContasContabeis() {

    /**
     * Para cada conta contábil devemos buscar suas movimentações e calcular seus respectivos saldos
     */
    foreach($this->aContaCorrenteDetalhe as $iDetalhe => $oDetalhe) {

      $oDaoConplano        = db_utils::getDao("conplano");
      $sCampos             = "c60_codcon, c61_reduz, c60_descr, c60_estrut";
      $sWhere              = "     c61_reduz = {$oDetalhe->c19_reduz}";
      $sWhere             .= " and c61_anousu = {$oDetalhe->c19_conplanoreduzanousu}";
      $sWhere             .= " and c61_instit = {$oDetalhe->c19_instit}";
      $sSqlBuscaContaPCASP = $oDaoConplano->sql_query_reduz(null, $sCampos, null, $sWhere);
      $rsBuscaContaPCASP   = $oDaoConplano->sql_record($sSqlBuscaContaPCASP);

      if ($oDaoConplano->numrows == 0) {
        return false;
      }

      $oStdContaPCASP = db_utils::fieldsMemory($rsBuscaContaPCASP, 0);
      $this->aContaCorrenteDetalhe[$iDetalhe]->contaPCASP = $oStdContaPCASP;

      $mCodigoCGM = null;
      if (isset($oDetalhe->c19_numcgm) && $oDetalhe->c19_numcgm != "") {
        $mCodigoCGM = $oDetalhe->c19_numcgm;
      }
      $aMovimentacoes      = $this->getMovimentacoes($oDetalhe, $oStdContaPCASP->c60_codcon, $oStdContaPCASP->c60_estrut, $mCodigoCGM);
      $this->aContaCorrenteDetalhe[$iDetalhe]->aMovimentacoes = $aMovimentacoes;
    }

  }

  /**
   * Através do código da conta Descobrimos a sua natureza (credora ou devedora)
   * Setamos o saldo anterior
   * Buscamos os valores de cada lançamento e somamos os débitos e créditos
   * Atravéz dos débitos e créditos somados calculamos o saldo final conforme regra para cada natureza
   * @param stdClass $oDetalhe    - Resultado da busca na classe filha pelos filtros, busca que é feita na
   *                                tabela contacorrentedetalhe conforme atributos do contacorrente
   * @param integer $iCodigoConta - conplano.c60_codcon
   * @param integer $iEstrutural  - Estrutural da conta (conplano.c60_estrut)
   * @return stdClass             - Objeto com os valores das movimentações já definido
   */
  private function getMovimentacoes($oDetalhe, $iCodigoConta, $iEstrutural, $iCredor = null) {

    $iAno      = $oDetalhe->c19_conplanoreduzanousu;
    $iReduzido = $oDetalhe->c19_reduz;

    $iNaturezaConta = $this->getNaturezaConta($iAno, $iCodigoConta);

    $fSaldoAnterior              = $this->getValoresAnterior($iReduzido, $iNaturezaConta);
    $oStdRetorno                 = new stdClass();
    $oStdRetorno->fSaldoAnterior = $fSaldoAnterior;
    $oStdRetorno->iNaturezaConta = $iNaturezaConta;
    $oStdRetorno->iEstrutural    = $iEstrutural;

    $oDaoConLancamVal     = db_utils::getDao("conlancamval");
    $sCampos              = " c69_valor, c28_tipo";
    $sWhere               = "     c69_data between '{$this->dtInicial}' and '{$this->dtFinal}'";
    $sWhere              .= " and c19_reduz = {$iReduzido}";
    if (!empty($iCredor)) {
    	$sWhere .= " and c19_numcgm = {$iCredor} ";
    }
    $sSqlBuscaLancamentos = $oDaoConLancamVal->sql_query_contacorrentedetalhe(null, $sCampos, null, $sWhere);
    $rsBuscaLancamentos   = $oDaoConLancamVal->sql_record($sSqlBuscaLancamentos);

    $fDebito  = 0;
    $fCredito = 0;
    if ($oDaoConLancamVal->numrows > 0) {

      for ($iLancamento = 0; $iLancamento < $oDaoConLancamVal->numrows; $iLancamento++) {

        $oStdLancamentos = db_utils::fieldsMemory($rsBuscaLancamentos, $iLancamento);

        if ($oStdLancamentos->c28_tipo == "D") {
          $fDebito += $oStdLancamentos->c69_valor;
        } else {
          $fCredito += $oStdLancamentos->c69_valor;
        }

      }
    }

    $oStdRetorno->fDebito  = $fDebito;
    $oStdRetorno->fCredito = $fCredito;

    $this->calculaSaldoFinal($oStdRetorno);
    return $oStdRetorno;
  }

  /**
   * Função que verifica a natureza da mesma e efetua os cálculos necessários
   * 1 - Credora:  Entrada (crédito), Saída (débito)
   * 2 - Devedora: Entrada (débito), Saída(crédito)
   *
   * Resultado Final: (Saldo Inicial + Entrada) - Saída
   *
   * Nesta função também já deixamos o campo sSaldoAnterior e sSaldoFinal formatado para o relatório
   */
  private function calculaSaldoFinal(&$oMovimentacoes) {

  	/**
  	 * @todo: ver possibilidade de refatoração
  	 */
  	$aCredores  = array(2,6,8);
  	$aDevedores = array(1,5,7);

  	$sSubstrEstrutural = substr($oMovimentacoes->iEstrutural, 0, 1);

  	if(in_array($sSubstrEstrutural, $aCredores)) {
  		$oMovimentacoes->iNaturezaConta = self::NATUREZA_CREDORA;
  	} else {
  		$oMovimentacoes->iNaturezaConta = self::NATUREZA_DEVEDORA;
  	}

    if ($oMovimentacoes->iNaturezaConta == self::NATUREZA_CREDORA) {
      /**
       * Regras para conta de natureza CREDORA
       */

      if ($oMovimentacoes->fSaldoAnterior < 0) {
        /**
         * Não podemos deixar nenhum valor negativo
         * Multiplicamos o valor por -1 e deixamos como valor a débito (saída de conta credora), ou seja, a
         * conta está com saldo negativo
         */

        $oMovimentacoes->sSaldoAnterior  = trim(db_formatar($oMovimentacoes->fSaldoAnterior * (-1), "f"));
        $oMovimentacoes->sSaldoAnterior .= " D";
      } else {
        /**
         * Se não somente deixamos o valor com a string "C" de débito (entrada de conta credora), ou seja, a
         * conta está com saldo positivo
         */

        $oMovimentacoes->sSaldoAnterior  = trim(db_formatar($oMovimentacoes->fSaldoAnterior, "f") . " C");
      }

      $oMovimentacoes->fSaldoFinal = ($oMovimentacoes->fSaldoAnterior + $oMovimentacoes->fCredito) - $oMovimentacoes->fDebito;

      if ($oMovimentacoes->fSaldoFinal < 0) {

        $oMovimentacoes->sSaldoFinal  = trim(db_formatar($oMovimentacoes->fSaldoFinal * (-1), "f"));
        $oMovimentacoes->sSaldoFinal .= " D";
      } else {
        $oMovimentacoes->sSaldoFinal  = trim(db_formatar($oMovimentacoes->fSaldoFinal, "f") . " C");
      }

    } else if($oMovimentacoes->iNaturezaConta == self::NATUREZA_DEVEDORA) {
      /**
       * Regras para conta de natureza DEVEDORA
       */

      if ($oMovimentacoes->fSaldoAnterior < 0) {
        /**
         * Não podemos deixar nenhum valor negativo
         * Multiplicamos o valor por -1 e deixamos como valor a crédito (saída de conta devedora), ou seja, a
         * conta está com saldo negativo
         * O mesmo se faz com o saldo final depois
         */

        $oMovimentacoes->sSaldoAnterior  = trim(db_formatar($oMovimentacoes->fSaldoAnterior * (-1), "f"));
        $oMovimentacoes->sSaldoAnterior .= " C";
      } else {
        /**
         * Se não somente deixamos o valor com a string "D" de débito (entrada de conta devedora), ou seja, a
         * conta está com saldo positivo
         */
        $oMovimentacoes->sSaldoAnterior  = trim(db_formatar($oMovimentacoes->fSaldoAnterior, "f") . " D");
      }

      $oMovimentacoes->fSaldoFinal = ($oMovimentacoes->fSaldoAnterior + $oMovimentacoes->fDebito) - $oMovimentacoes->fCredito;

      if ($oMovimentacoes->fSaldoFinal < 0) {

        $oMovimentacoes->sSaldoFinal  = trim(db_formatar($oMovimentacoes->fSaldoFinal * (-1), "f"));
        $oMovimentacoes->sSaldoFinal .= " C";
      } else {
        $oMovimentacoes->sSaldoFinal  = trim(db_formatar($oMovimentacoes->fSaldoFinal, "f") . " D");
      }
    }

    return;
  }

  /**
   * Busca os valores de crédito e débito da tabela contacorrentedetalhesaldo
   * @param integer $iSequencialDetalhe - Código sequencial do detalhe da conta
   * @param integer $iReduzido - Reduzido da conta no plano de contas PCASP (conplanoreduz)
   * @param integer $iNaturezaConta - Natureza da conta (credora ou devedora)
   */
  private function getValoresAnterior($iReduzido, $iNaturezaConta) {

    $iMes         = $this->iMesInicial - 1;
    $iAno         = $this->iAnoInicial;
    $iAnoAnterior = $iAno - 1;

    /**
     * Pega o saldo do mês anterior FECHADO
     */
    $oDaoContaCorrenteDetalhe = db_utils::getDao("contacorrentedetalhe");
    $sCampos                = " sum(c29_debito) as c29_debito, sum(c29_credito) as c29_credito";
    $sWhere                 = "      c19_reduz   = {$iReduzido}";
    $sWhere                .= " and (c29_anousu <= {$iAnoAnterior}";
    $sWhere .= " or (c29_anousu = {$iAno}";
    $sWhere .= "     and c29_mesusu <= {$iMes})";
    $sWhere .= " )";
    $sSqlSaldoAnterior      = $oDaoContaCorrenteDetalhe->sql_query_saldo(null, $sCampos, null, $sWhere);
    $rsSaldoAnterior        = $oDaoContaCorrenteDetalhe->sql_record($sSqlSaldoAnterior);

    $iDebitoAnterior  = 0;
    $iCreditoAnterior = 0;

    if ($oDaoContaCorrenteDetalhe->numrows > 0) {

      $oStdSaldoAnterior  = db_utils::fieldsMemory($rsSaldoAnterior, 0);
      $iDebitoAnterior   = $oStdSaldoAnterior->c29_debito;
      $iCreditoAnterior  = $oStdSaldoAnterior->c29_credito;
    }

    if ((int)$this->iDiaInicial != 1) {

      $oDaoConLancamVal     = db_utils::getDao("conlancamval");
      $sCampos              = "c69_valor, c28_tipo";
      $dtInicial            = $this->iAnoInicial. "-" . $this->iMesInicial . "-01";
      $dtFinal              = $this->iAnoInicial. "-" . $this->iMesInicial . "-" . ($this->iDiaInicial - 1);
      $sWhere               = "     c69_data between '{$dtInicial}' and '{$dtFinal}'";
      $sWhere              .= " and c19_reduz = {$iReduzido}";
      $sSqlBuscaLancamentos = $oDaoConLancamVal->sql_query_contacorrentedetalhe(null, $sCampos, null, $sWhere);
      $rsBuscaLancamentos   = $oDaoConLancamVal->sql_record($sSqlBuscaLancamentos);

      if ($oDaoConLancamVal->numrows > 0) {

        for ($iLancamento = 0; $iLancamento < $oDaoConLancamVal->numrows; $iLancamento++) {

          $oStdLancamento = db_utils::fieldsMemory($rsBuscaLancamentos, $iLancamento);

          if ($oStdLancamento->c28_tipo == "D") {
            $iDebitoAnterior += $oStdLancamento->c69_valor;
          } else {
            $iCreditoAnterior += $oStdLancamento->c69_valor;
          }
        }
      }
    }

    return $this->getSaldoAnterior($iNaturezaConta, $iCreditoAnterior, $iDebitoAnterior);
  }

  /**
   * Retorna o saldo anterior da conta
   * @param integer $iNaturezaConta   - Natureza da conta (credora ou devedora)
   * @param integer $iCreditoAnterior - Credito do mes anterior na tabela contacorrentesaldo
   * @param integer $iDebitoAnterior  - Debito do mes anterior na tabela contacorrentesaldo
   * @return float  $fSaldoAnterior   - Saldo da conta no mes anterior com base nos calculos
   */
  private function getSaldoAnterior($iNaturezaConta, $iCreditoAnterior, $iDebitoAnterior) {

  	$fSaldoAnterior = 0;

  	switch ($iNaturezaConta) {

  		case self::NATUREZA_CREDORA:

  			$fSaldoAnterior = $iCreditoAnterior - $iDebitoAnterior;
  			break;

  		case self::NATUREZA_DEVEDORA:

  			$fSaldoAnterior = $iDebitoAnterior - $iCreditoAnterior;
  			break;
  	}

    return $fSaldoAnterior;
  }

  /**
   * Retorna os dados para impressão do relatório do conta corrente
   * @return array[] stdClass
   */
  public function getDados() {
    return $this->aContaCorrenteDetalhe;
  }

  /**
   * Seleciona na tabela conplano a natureza da conta (credora ou devededora)
   * @param  integer $iAno           - Ano da conta
   * @param  integer $iCodConta      - Código da conta (c60_codcon)
   * @return integer $iNaturezaSaldo - Natureza da conta (1 -> Devedor, 2 -> Credor) Ver constantes da classe
   */
  private function getNaturezaConta($iAno, $iCodigoConta) {

    $oDaoConplano   = db_utils::getDao("conplano");
    $sCampos        = "c60_naturezasaldo";
    $sSqlBuscaConta = $oDaoConplano->sql_query_file($iCodigoConta, $iAno, $sCampos);
    $rsBuscaConta   = $oDaoConplano->sql_record($sSqlBuscaConta);

    if ($oDaoConplano->numrows > 0) {

      $iNaturezaSaldo = db_utils::fieldsMemory($rsBuscaConta, 0)->c60_naturezasaldo;
      return $iNaturezaSaldo;
    }
  }

}

?>