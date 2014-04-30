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
 * Classe para controle de dotacoes
 * Controla a dotacao e seus saldos durante o exercicio
 * @author Iuri Guntchnigg iuri@dbseller.com.br
 * @package orcamento
 * @version $Revision: 1.6 $
 */
class Dotacao {

  protected $iCodigo;
  protected $iAnoUsu;
  protected $iOrgao;
  protected $iUnidade;
  protected $iFuncao;
  protected $iSubFuncao;
  protected $iPrograma;
  protected $iProjAtiv;
  protected $iElemento;
  protected $iRecurso;
  protected $iLocalizador;
  protected $nSaldoFinal;
  protected $nSaldoAtual;
  protected $nSaldoReservado;
  protected $nSaldoEmpenhado;
  protected $nSaldoAtualMenosReservado;
  protected $nValor;
  protected $iAutorizacao;

  /**
   * Método construttor, Recebe com parametro o reduzido e o ano da dotacao
   * Define os dados básicos da dotacao
   * @param integer $iCodDot Código reduzido da dotacao (orcdotacao.o58_coddot)
   * @param integer $iAnoUsu Ano da dotacao (orcdotacao.o58_anousu)
   */
  function __construct($iCodDot, $iAnoUsu) {

     require_once("libs/db_liborcamento.php");
     $rsDotacaoSaldo        = db_dotacaosaldo(8, 2, 2, "true", "o58_coddot={$iCodDot}", $iAnoUsu);
     
     $oDadosDotacao         = db_utils::fieldsMemory($rsDotacaoSaldo, 0);
     $this->iCodigo         = $oDadosDotacao->o58_coddot;
     $this->iAnoUsu         = $iAnoUsu;
     $this->iOrgao          = $oDadosDotacao->o58_orgao;
     $this->iUnidade        = $oDadosDotacao->o58_unidade;
     $this->iFuncao         = $oDadosDotacao->o58_funcao;
     $this->iSubFuncao      = $oDadosDotacao->o58_subfuncao;
     $this->iPrograma       = $oDadosDotacao->o58_programa;
     $this->iProjAtiv       = $oDadosDotacao->o58_projativ;
     $this->iElemento       = $oDadosDotacao->o58_elemento;
     $this->iRecurso        = $oDadosDotacao->o58_codigo;
     $this->nSaldoFinal     = $oDadosDotacao->atual_menos_reservado;
     $this->nSaldoAtual     = $oDadosDotacao->atual;
     $this->nSaldoReservado = $oDadosDotacao->reservado;
     $this->nSaldoEmpenhado = $oDadosDotacao->empenhado_acumulado;
     $this->nSaldoAtualMenosReservado = ($oDadosDotacao->atual_menos_reservado);
  }

  /**
   * Retorna o ano da Dotacao
   * Retorna o ano em que a dotacao foi definida
   * @return integer
   */
  public function getAnoUsu() {

    return $this->iAnoUsu;
  }

  /**
   * Retorna o codigo Reduzido da dotacao
   * Retorna o codigo reduzido
   * @return integer Codigo Reduzido da Dotacao
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna o elemento de despesa sa dotacao
   *
   * @return integer
   */
  public function getElemento() {
    return $this->iElemento;
  }

  /**
   * Retorna a funcao em que a dotacao esta vinculada
   * @return integer codigo da funcao
   */
  public function getFuncao() {
    return $this->iFuncao;
  }

  /**
   * Retorna o localizador de gastos da dotacao
   * @return integer codigo do localizador de gastos
   */
  public function getLocalizador() {
    return $this->iLocalizador;
  }

  /**
   * Retorna o orgao da dotacao
   * @return integer
   */
  public function getOrgao() {
    return $this->iOrgao;
  }

  /**
   * Retorna o programa da dotacao
   * Indica em qual programa de governo sera gasto o valor da dotacao
   * Os dados do programa está na tabela orcprograma
   *
   * @return integer codigo do programa
   */
  public function getPrograma() {
    return $this->iPrograma;
  }

  /**
   * Retorna o projeto/ativdade da dotacao
   * Indica o projeto/Atividade em qual a dotacao sera gasta
   * os dados sao da tabela orcprojativ
   * @return integer codigo do projeto/atividade
   */
  public function getProjAtiv() {

    return $this->iProjAtiv;
  }

  /**
   * Retorna o recurso da dotacao
   * O Recurso da dotacao indica a origem da verba para o gasto.
   * Os dados do recurso sao da tabea orctiporec
   * @return integer codigo do recurso
   */
  public function getRecurso() {
    return $this->iRecurso;
  }

  /**
   * Retorna a Subfuncao da dotacao
   * Subfuncao dotacao indica como a dotacao sera utilizada.
   * Os dados da subfuncao estao na tabela Subfuncao
   * @return integer Codigo da subfuncao
   */
  public function getSubFuncao() {
    return $this->iSubFuncao;
  }


  /**
   * Retorna a unidade orcamentaria da dotacao
   * Unidade orcamentaria da dotacao é onde a dotacao sera utilizada  os dados da Unidade estao na tabela orcunidade
   * @return integer codigo da Unidade
   */
  public function getUnidade() {
    return $this->iUnidade;
  }

  /**
   * Retorna o saldo atual da dotacao
   * Retorna o saldo acumulado ate o dia da dotacao.
   * @return float
   */
  public function getSaldoAtual() {
    return $this->nSaldoAtual;
  }

  /**
   * Retorna o total empenhado
   * O valor retornado é o valor de todas os valores empenhados durante o ano.
   * @return float
   */
  public function getSaldoEmpenhado() {
    return $this->nSaldoEmpenhado;
  }

  /**
   * Retorna o saldo final da dotacao
   * @return Float
   */
  public function getSaldoFinal() {
    return $this->nSaldoFinal;
  }

  /**
   * Retorna o saldo reservado da dotacao
   * Retorna o valor de todas as reservas de saldo ativas para dotacao
   * @return float
   */
  public function getSaldoReservado() {

    return $this->nSaldoReservado;
  }

  /**
   * Retorna o saldo atual da dotacao;
   * O saldo Retornado e o saldo atual da dotacoa, menos o total de reservas de saldo
   * existentes para a dotacao
   * @return float;
   */
  public function getSaldoAtualMenosReservado() {

    return $this->nSaldoAtualMenosReservado;
  }

  /**
   * Retorna o valor previsto para a dotacao
   * @return float
   */
  public function getValor() {
    return $this->nValor;
  }

  /**
   * Seta o Valor presvisto da dotacao
   * @param float $nValor
   */
  public function setValor($nValor) {
    $this->nValor = $nValor;
  }

  /**
   * Retorna a Autorizacao
   * @return integer
   */
  public function getAutorizacao() {
    return $this->iAutorizacao;
  }

  /**
   * Seta a Autorizacao
   * @param integer $iAutorizacao
   */
  public function setAutorizacao($iAutorizacao) {
    $this->iAutorizacao = $iAutorizacao;
  }


  /**
   * Valor previsto do orcamento de despesa
   * Retorna o valor previsto de todas as  dotacoes no ano $iAno e para a Instituicao $iInstituicao.
   * o Valor retornado é o valors previsto no ppa no ano anterior.
   * @example
   * <code>
   *  $nValorPrevisto =  Dotacao::getValorPrevistoNoAno(2012, 1);
   * </code>
   *
   * @param integer $iAno Ano do
   * @param integer $iInstituicao
   * @return number
   */
  static public function getValorPrevistoNoAno($iAno, $iInstituicao) {

    $nValorPrevisto = 0;
    $oDaoOrcDotacao = db_utils::getDao("orcdotacao");
    $sWhere         = "o58_anousu = {$iAno} and o58_instit = {$iInstituicao}";
    $sSqlDotacao    = $oDaoOrcDotacao->sql_query_file(null,
                                                      null,
                                                      "coalesce(sum(o58_valor), 0) as valor_total",
                                                      null,
                                                      $sWhere
                                                     );
    $rsDotacao      = $oDaoOrcDotacao->sql_record($sSqlDotacao);
    if ($oDaoOrcDotacao->numrows == 1) {
      $nValorPrevisto = db_utils::fieldsMemory($rsDotacao, 0)->valor_total;
    }
    return $nValorPrevisto;
  }

}

?>