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

require_once("model/contabilidade/contacorrente/ContaCorrenteRepositoryBase.model.php");

/**
 * Classe repositório com dados para o relatório balancete de conta corrente Adiantamento Concessao
 * @package contabilidade
 * @subpackage contacorrente
 * @author Acácio Schneider <acacio.schneider@dbseller.com.br>
 * @version $Revision: 1.7 $
 */
class AdiantamentoConcessaoRepository extends ContaCorrenteRepositoryBase {

  /**
   * @param string $dtInicial - Data Inicial do relatório, utilizado nos filtros
   * @param string $dtFinal   - Data Final   do relatório, utilizado nos filtros
   */
  public function __construct($dtInicial, $dtFinal) {

    parent::__construct(AdiantamentoConcessao::CONTA_CORRENTE, $dtInicial, $dtFinal);
    $this->setAtributos();
  }

  /**
   * Busca os dados para a conta corrente de Adiantamento de concessão
   * Adiciona no array $this->aContaCorrenteDetalhe o resultado da busca
   */
  private function setAtributos() {
    
    $oDaoContaCorrenteDetalhe = db_utils::getDao("contacorrentedetalhe");
    $sCampos                  = "c19_contacorrente, c19_instit,  c19_numcgm, c19_orcunidadeanousu ";
    $sCampos                 .= ", c19_orcunidadeorgao, c19_orcunidadeunidade, c19_reduz, c19_conplanoreduzanousu";
    $sCampos                 .= ", c19_orcorgaoanousu, c19_orcorgaoorgao";
    $sWhere                   = "     c19_contacorrente = " . AdiantamentoConcessao::CONTA_CORRENTE;
    $sWhere                  .= " and c69_data between '{$this->dtInicial}' and '{$this->dtFinal}'";
    $sWhere                  .= " group by {$sCampos} ";
    $sOrder                   = "c19_instit, c19_reduz";
    $sSqlBuscaLancamentos     = $oDaoContaCorrenteDetalhe->sql_query_lancamentos(null, $sCampos, $sOrder, $sWhere);
    $rsBuscaLancamentos       = $oDaoContaCorrenteDetalhe->sql_record($sSqlBuscaLancamentos);

    if ($oDaoContaCorrenteDetalhe->numrows == 0) {
      return false;
    }
    
    for($iLancamento = 0; $iLancamento < $oDaoContaCorrenteDetalhe->numrows; $iLancamento++) {
      $this->aContaCorrenteDetalhe[] = db_utils::fieldsMemory($rsBuscaLancamentos, $iLancamento);
    }

    /**
     * Buscamos as contas contábeis (conplano) e agrupamos os dados da conta corrente
     */
    $this->getContasContabeis();
    $this->agrupar();
  }

  /**
   * Agrupa conforme as regras da conta corrente
   */
  private function agrupar() {

    $aContas = array();

    /**
     * Para cada índice do array, buscamos seus atributos e os agrupamos
     */
    foreach ($this->aContaCorrenteDetalhe as $oConta) {

      /**
       * Busca a instituição
       */
      $oDaoDbConfig  = db_utils::getDao("db_config");
      $sCamposInstit = "nomeinst";
      $sSqlInstit    = $oDaoDbConfig->sql_query_file($oConta->c19_instit, $sCamposInstit);
      $rsInstit      = $oDaoDbConfig->sql_record($sSqlInstit);

      if ($oDaoDbConfig->numrows == 0) {
        continue;
      }

      $sInstituicao = db_utils::fieldsMemory($rsInstit, 0)->nomeinst;

      /**
       * Busca a unidade
       */
      $oDaoOrcUnidade = db_utils::getDao("orcunidade");

      $sSqlUnidade    = $oDaoOrcUnidade->sql_query_file($oConta->c19_orcunidadeanousu, $oConta->c19_orcunidadeorgao, $oConta->c19_orcunidadeunidade);
      $rsUnidade      = $oDaoOrcUnidade->sql_record($sSqlUnidade);

      if ($oDaoOrcUnidade->numrows == 0) {
        continue;
      }

      $sUnidade = db_utils::fieldsMemory($rsUnidade, 0)->o41_unidade;

      /**
       * Busca o órgão
       */
      $oDaoOrcOrgao = db_utils::getDao("orcorgao");
      $sSqlOrgao    = $oDaoOrcOrgao->sql_query_file($oConta->c19_orcorgaoanousu, $oConta->c19_orcorgaoorgao);
      $rsOrgao      = $oDaoOrcOrgao->sql_record($sSqlOrgao);

      if ($oDaoOrcOrgao->numrows == 0) {
        continue;
      }

      $sOrgao = db_utils::fieldsMemory($rsOrgao, 0)->o40_descr;

      /**
       * Busca os dados no cgm
       */
      $oDaoCgm    = db_utils::getDao("cgm");
      $sCamposCgm = "z01_nome, z01_cgccpf";
      $sSqlCgm    = $oDaoCgm->sql_query_file($oConta->c19_numcgm, $sCamposCgm);
      $rsCgm      = $oDaoCgm->sql_record($sSqlCgm);

      if ($oDaoCgm->numrows == 0) {
        continue;
      }

      $oStdCgm     = db_utils::fieldsMemory($rsCgm, 0);
      $sNomeCredor = $oStdCgm->z01_nome;
      $sCpfCnpj    = $oStdCgm->z01_cgccpf;
      $iCodigoCgm  = $oConta->c19_numcgm;

      /**
       * Montamos o agrupador
       */
      $sAgrupamento  = $oConta->c19_instit . $oConta->c19_numcgm;
      $sAgrupamento .= $oConta->c19_orcunidadeorgao . $oConta->c19_orcunidadeunidade . $oConta->c19_orcunidadeanousu;
      $sAgrupamento .= $oConta->c19_orcorgaoanousu . $oConta->c19_orcorgaoorgao;

      $oStdInstituicao = new stdClass();
      $oStdInstituicao->sIdentificador = "Instituição";
      $oStdInstituicao->sValor         = $sInstituicao;

      $oStdOrgao = new stdClass();
      $oStdOrgao->sIdentificador = "Órgão";
      $oStdOrgao->sValor = $sOrgao;

      $oStdUnidade = new stdClass();
      $oStdUnidade->sIdentificador = "Unidade";
      $oStdUnidade->sValor         = $sUnidade;

      $oStdNomeCredor = new stdClass();
      $oStdNomeCredor->sIdentificador = "Nome do credor";
      $oStdNomeCredor->sValor         = $sNomeCredor;

      $oStdCpfCnpj = new stdClass();
      $oStdCpfCnpj->sIdentificador = "CPF/CNPJ";
      $oStdCpfCnpj->sValor       = $sCpfCnpj;

      $oStdCodigoCgm = new stdClass();
      $oStdCodigoCgm->sIdentificador = "Código no CGM";
      $oStdCodigoCgm->sValor         = $iCodigoCgm;

      $aContas[$sAgrupamento]->aCabecalho    = array();
      $aContas[$sAgrupamento]->aCabecalho[]  = $oStdInstituicao;
      $aContas[$sAgrupamento]->aCabecalho[]  = $oStdOrgao;
      $aContas[$sAgrupamento]->aCabecalho[]  = $oStdUnidade;
      $aContas[$sAgrupamento]->aCabecalho[]  = $oStdNomeCredor;
      $aContas[$sAgrupamento]->aCabecalho[]  = $oStdCpfCnpj;
      $aContas[$sAgrupamento]->aCabecalho[]  = $oStdCodigoCgm;

      $aContas[$sAgrupamento]->aContas[] = $oConta;
    }

    $this->aContaCorrenteDetalhe = $aContas;
  }
}
?>