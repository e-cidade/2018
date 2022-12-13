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


require_once("model/contabilidade/planoconta/interface/ISistemaConta.interface.php");
/**
 *
 * Sistema de Conta do tipo Financeiro Banco,
 *
 * @author dbseller
 * @name SistemaContaFinanceiroBanco
 * @package contabilidade
 * @subpackage planoconta
 */
class SistemaContaFinanceiroBanco implements ISistemaConta{

  private $iCodigo = 6;
  private $sDescricao = 'FINANCEIRO - BANCO';

  public function integrarDados(ContaPlano $oContaPlano) {

    $this->persisteSaltes($oContaPlano);
    $this->persisteEmpAgeTipo($oContaPlano);
  }

  /**
   * Salva os dados em saltes
   */
  private function persisteSaltes(ContaPlano $oContaPlano) {

    /**
     * Configuração da Data
     */
    $dtAtualizacao = explode("-",date("Y-m-d",db_getsession("DB_datausu")),3);
    $dtAno         = $dtAtualizacao[0];
    $dtMes         = $dtAtualizacao[1];
    $dtDia         = $dtAtualizacao[2];
    $dtAtualizacao = date('Y-m-d', mktime(0,0,0, $dtMes, $dtDia-1, $dtAno));

    $oDaoSaltes         = db_utils::getDao('saltes');
    /*
     * Caso já existir os dados da saltes, nao devemos incluir novamente.
     */
    $sSqlVerificaSaltes = $oDaoSaltes->sql_query_file($oContaPlano->getReduzido());
    $rsVerificaSaltes   = $oDaoSaltes->sql_record($sSqlVerificaSaltes);
    if ($oDaoSaltes->numrows > 0) {
      return true;
    }
    /**
     * Cria uma instancia de Saltes e seta os Dados
     */
    $oDaoSaltes->k13_conta         = $oContaPlano->getReduzido();
    $oDaoSaltes->k13_reduz         = $oContaPlano->getReduzido();
    $oDaoSaltes->k13_saldo         = "0";
    $oDaoSaltes->k13_vlratu        = "0";
    $oDaoSaltes->k13_datvlr        = $dtAtualizacao;
    $oDaoSaltes->k13_ident         = "";
    $oDaoSaltes->k13_dtimplantacao = date("Y-m-d",db_getsession("DB_datausu"));
    $oDaoSaltes->k13_descr         = substr($oContaPlano->getDescricao(),0,40);
    $oDaoSaltes->k13_limite        = null;

    /**
     * Inclui os dados
     */
     $oDaoSaltes->incluir($oContaPlano->getReduzido());
     if ($oDaoSaltes->erro_status == 0) {
       throw new Exception($oDaoSaltes->erro_msg);
     }
     return true;
  }

  /**
   * Salva dados em empagetipo
   */
  private function persisteEmpAgeTipo(ContaPlano $oContaPlano) {

    $oDaoEmpAgeTipo = db_utils::getDao("empagetipo");
    $sSqlDados     = $oDaoEmpAgeTipo->sql_query_file(null,
                                                      "*",
                                                      null,
                                                      "e83_conta = {$oContaPlano->getReduzido()}"
                                                     );

    $rsVerificaEmpAgeTipo = $oDaoEmpAgeTipo->sql_record($sSqlDados);
    if ($oDaoEmpAgeTipo->numrows > 0) {
      return true;
    }
    $oDaoEmpAgeTipo->e83_descr    = $oContaPlano->getDescricao();
    $oDaoEmpAgeTipo->e83_conta    = $oContaPlano->getReduzido();
    $oDaoEmpAgeTipo->e83_codmod   = "3";
    $oDaoEmpAgeTipo->e83_convenio = "0";
    $oDaoEmpAgeTipo->e83_codigocompromisso = "00";

    /**
     *
     */
    $iProximoCheque = $oDaoEmpAgeTipo->getMaxCheque($oContaPlano->getReduzido());
    if (empty($iProximoCheque)) {
      $iProximoCheque = 0;
    }
    $oDaoEmpAgeTipo->e83_sequencia = "{$iProximoCheque}";
    $oDaoEmpAgeTipo->incluir(null);

    if ($oDaoEmpAgeTipo->erro_status == 0) {
      throw new Exception($oDaoEmpAgeTipo->erro_msg);
    }
    return true;
  }

  /**
   *
   * Exclui dados
   * @param ContaPlano $oContaPlano
   */
  public function excluirDadosIntegrados(ContaPlano $oContaPlano) {

    if ($this->hasContaTesouraria($oContaPlano)) {

      throw new Exception("Conta não pode ser excluida. Conta com movimentacao na tesouraria.!");
    } else {

      $oDaoSaltes     = db_utils::getDao('saltes');

      $sWhere     = "k13_reduz = {$oContaPlano->getReduzido()} ";
      $sSqlSaltes =  $oDaoSaltes->sql_query_file(null, $campos="*", null, $sWhere);
      $rsSaltes   = $oDaoSaltes->sql_record($sSqlSaltes);
      if ($oDaoSaltes->numrows > 0) {

        $oSaltes    = db_utils::fieldsMemory($rsSaltes, 0);
        $oDaoSaltes->k13_conta          = $oSaltes->k13_conta;
        $oDaoSaltes->k13_limite         = date("Y-m-d", db_getsession("DB_datausu"));
        $oDaoSaltes->k13_descr          = $oSaltes->k13_descr;
        $oDaoSaltes->k13_saldo          = $oSaltes->k13_saldo;
        $oDaoSaltes->k13_ident          = $oSaltes->k13_ident;
        $oDaoSaltes->k13_vlratu         = $oSaltes->k13_vlratu;
        $oDaoSaltes->k13_datvlr         = $oSaltes->k13_datvlr;
        $oDaoSaltes->k13_dtimplantacao  = $oSaltes->k13_dtimplantacao;

        $oDaoSaltes->alterar($oSaltes->k13_conta);

        if ($oDaoSaltes->erro_status == "0") {

          throw new Exception("Erro ao  desativar a conta na Tesouraria! \n" .$oDaoSaltes->erro_msg);
        }
      }
    }
  }

  /**
   *
   * Verifica se o reduzido possui saldo na tesouraria.
   * Se tiver não pode deixar cancelar
   *
   * @param ContaPlano $oContaPlano
   * @throws Exception
   */
  private function hasContaTesouraria (ContaPlano $oContaPlano) {

    $oDaoSaltes     = db_utils::getDao('saltes');

    $sWhere  = " saltes.k13_reduz = {$oContaPlano->getReduzido()} ";
    $sWhere .= "    and (corrente.k12_conta         is not null          ";
    $sWhere .= "         or placaixarec.k81_conta   is not null          ";
    $sWhere .= "         or slip.k17_codigo         is not null)         ";
    $sWhere .= "    and k13_vlratu > 0 		 															 ";


    $sSqlSaltes     = $oDaoSaltes->sql_query_movimentacao_tesouraria(null, "*", null, $sWhere);
    $rsSaltes       = $oDaoSaltes->sql_record($sSqlSaltes);

    if ($oDaoSaltes->numrows > 0) {
      return true;

    }
    return false;
  }


  /**
  * Retorna o código do tipo Financeiro - Banco
  * @see ISistemaConta::getCodigoSistemaConta()
  */
  public function getCodigoSistemaConta() {
    return $this->iCodigo;
  }

  public function getDescricao() {
    return $this->sDescricao;
  }

}