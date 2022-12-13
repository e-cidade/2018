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
 * Classe repositório com dados para o relatório balancete de conta corrente Domicilio Bancário
 * @package contabilidade
 * @subpackage contacorrente
 * @author Acácio Schneider <acacio.schneider@dbseller.com.br>
 * @version $Revision: 1.5 $
 */ 
class DomicilioBancarioRepository extends ContaCorrenteRepositoryBase {

  /**
   * @param string $dtInicial - Data Inicial do relatório, utilizado nos filtros
   * @param string $dtFinal   - Data Final   do relatório, utilizado nos filtros
   */ 
  public function __construct($dtInicial, $dtFinal) {

    parent::__construct(DomicilioBancario::CONTA_CORRENTE, $dtInicial, $dtFinal);
    $this->setDados();
  }

  /**
   * Busca os dados para a conta corrente de Domicilio Bancário
   * Adiciona no array $this->aContaCorrenteDetalhe o resultado da busca
   */
  private function setDados() {
    
    $oDaoContaCorrenteDetalhe = db_utils::getDao("contacorrentedetalhe");
    $sCampos                  = "c19_contacorrente, c19_instit, c19_contabancaria, c19_reduz, c19_conplanoreduzanousu, c19_sequencial";
    $sWhere                   = "     c19_contacorrente = " . DomicilioBancario::CONTA_CORRENTE;
    $sWhere                  .= " and c69_data between '{$this->dtInicial}' and '{$this->dtFinal}'";
    $sWhere                  .= " group by {$sCampos} ";
    $sOrder                   = "c19_instit, c19_contabancaria, c19_reduz";
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
       * Buscamos os dados da conta bancária
       */
      $oDaoContaBancaria    = db_utils::getDao("contabancaria");
      $sCamposContaBancaria = "db90_descr, db83_conta, db83_dvconta, db89_digito, db89_codagencia";
      $sSqlContaBancaria    = $oDaoContaBancaria->sql_query($oConta->c19_contabancaria, $sCamposContaBancaria);
      $rsContaBancaria      = $oDaoContaBancaria->sql_record($sSqlContaBancaria);

      if ($oDaoContaBancaria->numrows == 0) {
        continue;
      }

      $oStdContaBancaria = db_utils::fieldsMemory($rsContaBancaria, 0);

      /**
       * Agrupa pela instituição e pela conta bancária
       */
      $sAgrupamento = $oConta->c19_instit.$oConta->c19_contabancaria;
      
      $oStdInstituicao = new stdClass();
      $oStdInstituicao->sIdentificador = "Instituição";
      $oStdInstituicao->sValor         = $sInstituicao;
      
      $oStdBanco = new stdClass();
      $oStdBanco->sIdentificador = "Banco";
      $oStdBanco->sValor         = $oStdContaBancaria->db90_descr;
      
      $oStdAgencia = new stdClass();
      $oStdAgencia->sIdentificador = "Agência";
      $oStdAgencia->sValor         = $oStdContaBancaria->db89_codagencia;
      
      $oStdDvAgencia = new stdClass();
      $oStdDvAgencia->sIdentificador = "Dígito Verificador da Agencia";
      $oStdDvAgencia->sValor         = $oStdContaBancaria->db89_digito;
      
      $oStdContaCorrenteBanco = new stdClass();
      $oStdContaCorrenteBanco->sIdentificador = "Conta Corrente";
      $oStdContaCorrenteBanco->sValor         = $oStdContaBancaria->db83_conta;
      
      $oStdDvContaCorrenteBanco = new stdClass();
      $oStdDvContaCorrenteBanco->sIdentificador = "Dígito Verificador da Conta Corrente";
      $oStdDvContaCorrenteBanco->sValor         = $oStdContaBancaria->db83_dvconta;
      
      $aContas[$sAgrupamento]->aCabecalho   = array();
      $aContas[$sAgrupamento]->aCabecalho[] = $oStdInstituicao;
      $aContas[$sAgrupamento]->aCabecalho[] = $oStdBanco;
      $aContas[$sAgrupamento]->aCabecalho[] = $oStdAgencia;
      $aContas[$sAgrupamento]->aCabecalho[] = $oStdDvAgencia;
      $aContas[$sAgrupamento]->aCabecalho[] = $oStdContaCorrenteBanco;
      $aContas[$sAgrupamento]->aCabecalho[] = $oStdDvContaCorrenteBanco;
       
      $aContas[$sAgrupamento]->aContas[] = $oConta;
    }

    $this->aContaCorrenteDetalhe = $aContas;
  }

}

?>