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

require_once ("model/pessoal/relatorios/RelatorioFolhaPagamento.model.php");

/**
 * @fileoverview Classe para geração do resumo da folha
 *
 * @author       Rafael Nery  rafael.nery@dbseller.com.br
 *               
 * @package      Pessoal
 * @revision     $Author: dbjeferson.belmiro $
 * @version      $Revision: 1.5 $
 */
class resumoFolha extends RelatorioFolhaPagamento {

  /**
   * Tipos de Vinculos
   * @var string
   */
  protected $sVinculoServidor  = "";

  /**
   * Array com o total de Servidores por filtro selecionado
   */
  protected $aServidoresFiltro = array();

  /**
   * Array de dados referentes aos filtros de quebra
   */
  protected $aDadosFiltro      = array();

  /**
   * Array de objetos com os dados do totalizador conforme filtro
   */
  protected $aDadosTotalizador = array();
  
  /**
   * Codigo da previdencia
   */
  protected $sFiltroPrevidencia= null;

  /**
   * Ordenação do Relatório
   * R - RUBRICA
   * D - DESCRICAO
   * Enter description here ...
   * @var unknown_type
   */
  protected $sOrdemRelatorio  = "rubrica";
  /** 
   * Construtor da classe
   */
  public  function __construct(){
    parent::__construct();
  }

  /**
   * Processa retorno de dados, para o resumo e define o valor dos dados extra-resumo,
   * como total de funcionarios por filtro e dados dos valores patronais
   * @throws Exception - Quando houver erro no sql .
   * @return array
   */
  public  function processaDadosResumoFolha(){

    /**
     * array de retorno de Dados
     */
    $aRetorno             = array();
    $aFuncionariosRubrica = array();

    $sCamposRubricas = " case when rh23_rubric is not null                                                      \n";
    $sCamposRubricas.= "      then 'e-'                                                                         \n";
    $sCamposRubricas.= "      else                                                                              \n";
    $sCamposRubricas.= "           case when rh75_rubric is not null and e01_sequencial = 2                     \n";
    $sCamposRubricas.= "                then 'r-'                                                               \n";
    $sCamposRubricas.= "                else                                                                    \n";
    $sCamposRubricas.= "                     case when rh75_rubric is not null and e01_sequencial = 3           \n";
    $sCamposRubricas.= "                          then 'p-'                                                     \n";
    $sCamposRubricas.= "                          else                                                          \n";
    $sCamposRubricas.= "                               case when rh75_rubric is not null and e01_sequencial = 4 \n";
    $sCamposRubricas.= "                                    then 'd-'                                           \n";
    $sCamposRubricas.= "                                    else ''                                             \n";
    $sCamposRubricas.= "                               end                                                      \n";
    $sCamposRubricas.= "                     end                                                                \n";
    $sCamposRubricas.= "           end                                                                          \n";
    $sCamposRubricas.= " end  as tipo_emp,                                                                      \n";
    $sCamposRubricas.= " rh02_tbprev,                                                                           \n";
    $sCamposRubricas.= " rh56_princ                                                                             \n";

    /**
     * Monta string where 
     */
    $sWhere          = $this->sFiltroPrevidencia;
    
    if ( !empty($sWhere) ) {
      $sWhere       .= !empty($this->sVinculoServidor) ? " AND " . $this->sVinculoServidor : "";
    } 
    $aStrSqlResumoFolha = $this->retornaSQLBaseRelatorio($sWhere, $this->sOrdemRelatorio, $sCamposRubricas);
    
    /**
     * Percorre as strings retornadas executa o sql e monta o array de retorno
     */
    foreach ($aStrSqlResumoFolha as $sSqlResumoFolha) {
      
      
      $rsDadosFolha = db_query($sSqlResumoFolha);

      if ($rsDadosFolha) {

        /**
         * Percorre os dados da folha selecionada com while e retornado dados da consulta com o fetch_object
         * pois com a "db_utils::getCollectionByRecord", não há como separar os campos a serem usados e
         * pode ocorrer estouro de memória pelo volume dados
         */
        while ( $oDadosSqlFolha = pg_fetch_object($rsDadosFolha) ) {
          
          $iIndiceRegistros = $oDadosSqlFolha->rubrica;
          if ($this->sOrdemRelatorio == "D") {
            $iIndiceRegistros = $oDadosSqlFolha->descr_rubrica;
          }
          $sIndiceFiltro                               = (string)$oDadosSqlFolha->estrutfiltro;
          $aFuncionariosRubrica[$oDadosSqlFolha->rubrica][$oDadosSqlFolha->matricula_servidor] = $oDadosSqlFolha->matricula_servidor;
           
          $oDadosRetorno                               = new stdClass();
          $oDadosRetorno->rubrica                      = $oDadosSqlFolha->rubrica;
          $oDadosRetorno->label_rubrica                = $oDadosSqlFolha->tipo_emp.$oDadosSqlFolha->rubrica;
          $oDadosRetorno->descr_rubrica                = $oDadosSqlFolha->descr_rubrica;
          $oDadosRetorno->natureza_rubrica             = $oDadosSqlFolha->tipo_emp;
          $oDadosRetorno->provento_desconto            = $oDadosSqlFolha->provento_desconto;

          if ( isset($aRetorno[$sIndiceFiltro][$iIndiceRegistros]) ) {

            $oDadosExistentes                          = $aRetorno[$sIndiceFiltro][$iIndiceRegistros];
            $oDadosRetorno->valor_rubrica              = $oDadosSqlFolha->valor_rubrica + $oDadosExistentes->valor_rubrica;
            $oDadosRetorno->quant_rubrica              = $oDadosSqlFolha->quant_rubrica + $oDadosExistentes->quant_rubrica;
          } else {                                     
                                                       
            $oDadosRetorno->valor_rubrica              = $oDadosSqlFolha->valor_rubrica;
            $oDadosRetorno->quant_rubrica              = $oDadosSqlFolha->quant_rubrica;
          }

          $oDadosRetorno->funcionarios                 = count($aFuncionariosRubrica[$oDadosSqlFolha->rubrica]);
          $aRetorno[$sIndiceFiltro][$iIndiceRegistros] = $oDadosRetorno;

          /**
           * Cria incrementa os dados de descricao do filtro
           */
          $oDadosFiltro                       = new stdClass();
          $oDadosFiltro->iCodigo              = $oDadosSqlFolha->codigofiltro;
          $oDadosFiltro->sDescricao           = $oDadosSqlFolha->descrifiltro;
          $oDadosFiltro->sEstrutural          = $oDadosSqlFolha->estrutfiltro;
          
          $this->aDadosFiltro[$sIndiceFiltro] = $oDadosFiltro;
          /**
           * adiciona dados para a contagem de matriculas por filtro
           */
          $this->aServidoresFiltro[$sIndiceFiltro][$oDadosSqlFolha->matricula_servidor] = $oDadosSqlFolha->matricula_servidor;
          
          /**
           * Adiciona dados ao totalizador
           */
          $this->geraTotalizador($oDadosSqlFolha);
        }
        unset($aFuncionariosRubrica,$oDadosSqlFolha);

        asort($aRetorno[$sIndiceFiltro]);
        
      } else {
        
        throw new Exception ("Erro na Consulta SQL \n".pg_last_error());
      }
    }
    return $aRetorno;
  }
  
  /**
   * Cria totalizador do resumo com base no recordset enviado 
   * @param  object $oDadosSql
   * @return object 
   */
  private function geraTotalizador($oDadosSql) {
     
    /**
     * Dados a Serem manipulados
     */
    $nValorProventos        = 0;
    $nValorDescontos        = 0;
    $nValorLiquido          = 0;
    $nTotalBasePrevidencia  = 0;
    $nValorBasePrevidencia1 = 0;
    $nValorBasePrevidencia2 = 0;
    $nValorBasePrevidencia3 = 0;
    $nValorBasePrevidencia4 = 0;
    $nValorBaseFGTS         = 0;
    $nValorFGTS             = 0;
    $nValorBaseIRRF         = 0;
    $nValorEmpenhos         = 0;
    $nValorPontoExtra       = 0;
    $nValorRetencao         = 0;
    $nValorDeducao          = 0;
    $nValorDiferenca        = 0;
    $iTotalFuncionarios     = 0;
    

    if ($oDadosSql->provento_desconto != 3) {
      
      if ( $oDadosSql->rubrica < 'R950') {
        
        if ( $oDadosSql->provento_desconto == 1 ) {
          
          $nValorProventos += $oDadosSql->valor_rubrica;
          
          switch ($oDadosSql->tipo_emp){
            case 'e-': //empenhos[
              $nValorEmpenhos   += $oDadosSql->valor_rubrica;
              break;
            case 'r-': //retencao
              $nValorRetencao   -= $oDadosSql->valor_rubrica;
              break;
            case 'd-': //deducao
              $nValorDeducao    += $oDadosSql->valor_rubrica;
              break;
            case 'p-': //P.Extra
              $nValorPontoExtra += $oDadosSql->valor_rubrica;
              break;
            case ''://Diferenca
              $nValorDiferenca  += $oDadosSql->valor_rubrica;
              break;
          }
        } elseif( $oDadosSql->provento_desconto == 2 ) {

          $nValorDescontos   += $oDadosSql->valor_rubrica;
          
          switch ($oDadosSql->tipo_emp){
            case 'e-': //empenhos[
              $nValorEmpenhos   -= $oDadosSql->valor_rubrica;
              break;
            case 'r-': //retencao
              $nValorRetencao   += $oDadosSql->valor_rubrica;
              break;
            case 'd-': //deducao
              $nValorDeducao    -= $oDadosSql->valor_rubrica;
              break;
            case 'p-': //P.Extra
              $nValorPontoExtra -= $oDadosSql->valor_rubrica;
              break;
            case ''://Diferenca
              $nValorDiferenca  -= $oDadosSql->valor_rubrica;
              break;
          }
        }
      }
    } else {
      if ( $oDadosSql->rubrica == 'R981' ) {
        
        $nValorBaseIRRF += $oDadosSql->valor_rubrica;
        
      } elseif ( $oDadosSql->rubrica == 'R992' ) {
        
        $nTotalBasePrevidencia += $oDadosSql->valor_rubrica;
        
        /**
         * Separa valores por tipo de previdencia
         */
        
        if ( $oDadosSql->rh02_tbprev == 1 ) {
          
          $nValorBasePrevidencia1 += $oDadosSql->valor_rubrica;
          
        } elseif ( $oDadosSql->rh02_tbprev == 2 ) {
          
          $nValorBasePrevidencia2 += $oDadosSql->valor_rubrica;
          
        } elseif ( $oDadosSql->rh02_tbprev == 3 ) {
          
          $nValorBasePrevidencia3 += $oDadosSql->valor_rubrica;
          
        } elseif ( $oDadosSql->rh02_tbprev == 4 ) {
          
          $nValorBasePrevidencia4 += $oDadosSql->valor_rubrica;
          
        }
        
      } elseif ( $oDadosSql->rubrica == 'R991') {
        
        $nValorBaseFGTS    += $oDadosSql->valor_rubrica;
        $nValorFGTS        += round($oDadosSql->valor_rubrica * 0.08, 2);
      }
    }
    
    $nValorLiquido = $nValorProventos - $nValorDescontos;
    
    /**
     * Objeto com os dasdos de retorno do totalizador 
     */
    $oDadosRetorno = new stdClass();                                                                                                                                                                          
    
    /**
     * Valida se o filtro utilzado existe, 
     * caso exista soma os valoes necessários
     * caso não cria as variáveis com os valores
     */
    if ( isset($this->aDadosTotalizador[(string)$oDadosSql->estrutfiltro]) ) {
      
      $oDadosSoma = $this->aDadosTotalizador[(string)$oDadosSql->estrutfiltro]; 
      
      $oDadosRetorno->nValorProventos          = $oDadosSoma->nValorProventos        + $nValorProventos;
      $oDadosRetorno->nValorDescontos          = $oDadosSoma->nValorDescontos        + $nValorDescontos;
      $oDadosRetorno->nValorLiquido            = $oDadosSoma->nValorLiquido          + $nValorLiquido;
      $oDadosRetorno->nTotalBasePrevidencia    = $oDadosSoma->nTotalBasePrevidencia  + $nTotalBasePrevidencia;
      $oDadosRetorno->nValorBasePrevidencia1   = $oDadosSoma->nValorBasePrevidencia1 + $nValorBasePrevidencia1;
      $oDadosRetorno->nValorBasePrevidencia2   = $oDadosSoma->nValorBasePrevidencia2 + $nValorBasePrevidencia2;
      $oDadosRetorno->nValorBasePrevidencia3   = $oDadosSoma->nValorBasePrevidencia3 + $nValorBasePrevidencia3;
      $oDadosRetorno->nValorBasePrevidencia4   = $oDadosSoma->nValorBasePrevidencia4 + $nValorBasePrevidencia4;
      $oDadosRetorno->nValorBaseFGTS           = $oDadosSoma->nValorBaseFGTS         + $nValorBaseFGTS;
      $oDadosRetorno->nValorFGTS               = $oDadosSoma->nValorFGTS             + $nValorFGTS;
      $oDadosRetorno->nValorBaseIRRF           = $oDadosSoma->nValorBaseIRRF         + $nValorBaseIRRF;
      $oDadosRetorno->nValorEmpenhos           = $oDadosSoma->nValorEmpenhos         + $nValorEmpenhos;
      $oDadosRetorno->nValorPontoExtra         = $oDadosSoma->nValorPontoExtra       + $nValorPontoExtra;
      $oDadosRetorno->nValorRetencao           = $oDadosSoma->nValorRetencao         + $nValorRetencao;
      $oDadosRetorno->nValorDeducao            = $oDadosSoma->nValorDeducao          + $nValorDeducao;
      $oDadosRetorno->nValorDiferenca          = $oDadosSoma->nValorDiferenca        + $nValorDiferenca;

    } else {
      $oDadosRetorno->nValorProventos          = $nValorProventos;
      $oDadosRetorno->nValorDescontos          = $nValorDescontos;
      $oDadosRetorno->nValorLiquido            = $nValorLiquido;
      $oDadosRetorno->nTotalBasePrevidencia    = $nTotalBasePrevidencia;
      $oDadosRetorno->nValorBasePrevidencia1   = $nValorBasePrevidencia1;
      $oDadosRetorno->nValorBasePrevidencia2   = $nValorBasePrevidencia2;
      $oDadosRetorno->nValorBasePrevidencia3   = $nValorBasePrevidencia3;
      $oDadosRetorno->nValorBasePrevidencia4   = $nValorBasePrevidencia4;
      $oDadosRetorno->nValorBaseFGTS           = $nValorBaseFGTS;
      $oDadosRetorno->nValorFGTS               = $nValorFGTS;
      $oDadosRetorno->nValorBaseIRRF           = $nValorBaseIRRF;
      $oDadosRetorno->nValorEmpenhos           = $nValorEmpenhos;
      $oDadosRetorno->nValorPontoExtra         = $nValorPontoExtra;
      $oDadosRetorno->nValorRetencao           = $nValorRetencao;
      $oDadosRetorno->nValorDeducao            = $nValorDeducao;
      $oDadosRetorno->nValorDiferenca          = $nValorDiferenca;
    }
    /**
     * Total de funcionarios por filtro
     */
    $oDadosRetorno->iTotalFuncionarios      = count($this->getTotalFuncionariosPorFiltro($oDadosSql->estrutfiltro));
    
    $this->aDadosTotalizador[(string)$oDadosSql->estrutfiltro] = $oDadosRetorno;
  }

  /**
   * Retorna array de matriculas do filtro selecionado
   * @param string $sEstruturalFiltro
   */
  public  function getTotalFuncionariosPorFiltro($sEstruturalFiltro) {
    /**
     * array de retorno da Funcao
     */
    $aRetorno = array();

    foreach ($this->aServidoresFiltro[$sEstruturalFiltro] as $iMatricula) {
      $aRetorno[$iMatricula] = $iMatricula;
    }

    return $aRetorno;
  }

  /**
   * Retorna Array Unico com os funcionários que foram listados no relatório
   * @return array;
   */
  public  function getTotalFuncionarios() {

    /**
     * Array de Retorno da Funcao
     */
    $aRetorno = array();

    foreach ($this->aServidoresFiltro as $aMatriculas) {

      foreach ($aMatriculas as $iMatricula) {
        $aRetorno[$iMatricula] = $iMatricula;
      }
    }
    return $aRetorno;
  }
  
  /**
   * Retorna dados do Totalizador
   */
  public  function getDadosTotalizador($sEstrutural) {
    return $this->aDadosTotalizador[$sEstrutural];
  }

  /**
   * Retorna dados do filtro informado
   */
  public  function getDadosFiltro($sEstrutural) {
    return $this->aDadosFiltro[$sEstrutural];
  }

  /**
   * Retorna valores para calculo dos valores patronais
   * @return Object
   */
  public  function getDadosValoresPatronais() {

    /**
     * Monta SQL dos Valores Patronais
     */
    $sValoresPatronais  = " select distinct                           ";
    $sValoresPatronais .= "        r33_codtab,                        ";
    $sValoresPatronais .= "        r33_nome,                          ";
    $sValoresPatronais .= "        r33_ppatro                         ";
    $sValoresPatronais .= "   from inssirf                            ";
    $sValoresPatronais .= "  where r33_anousu = {$this->iAno}         ";
    $sValoresPatronais .= "    and r33_mesusu = {$this->iMes}         ";
    $sValoresPatronais .= "    and r33_codtab > 2                     ";
    $sValoresPatronais .= "    and r33_instit = {$this->iInstituicao} ";

    $rsValoresPatronais    = db_query($sValoresPatronais);
    $iRowsValoresPatronais = pg_num_rows($rsValoresPatronais);


    if($rsValoresPatronais && $iRowsValoresPatronais > 0){

      /**
       * Valores padrão para base dos valores patronais
       */
      $oValoresPatronais = new stdClass();
      $oValoresPatronais->aBasePrevidencia1 = array("sNome" => "BASE PREV.1", "nValor" => 0);
      $oValoresPatronais->aBasePrevidencia2 = array("sNome" => "BASE PREV.2", "nValor" => 0);
      $oValoresPatronais->aBasePrevidencia3 = array("sNome" => "BASE PREV.3", "nValor" => 0);
      $oValoresPatronais->aBasePrevidencia4 = array("sNome" => "BASE PREV.4", "nValor" => 0);

      $aValoresPatronais = db_utils::getCollectionByRecord($rsValoresPatronais);

      foreach ($aValoresPatronais as $oRowValPatronais){

        switch ($oRowValPatronais->r33_codtab) {
          case 3:

            $oValoresPatronais->aBasePrevidencia1["sNome"]  = substr($oRowValPatronais->r33_nome,0,15);
            $oValoresPatronais->aBasePrevidencia1["nValor"] = $oRowValPatronais->r33_ppatro;
            break;
          case 4:

            $oValoresPatronais->aBasePrevidencia2["sNome"]  = substr($oRowValPatronais->r33_nome,0,15);
            $oValoresPatronais->aBasePrevidencia2["nValor"] = $oRowValPatronais->r33_ppatro;
            break;
          case 5:

            $oValoresPatronais->aBasePrevidencia3["sNome"]  = substr($oRowValPatronais->r33_nome,0,15);
            $oValoresPatronais->aBasePrevidencia3["nValor"] = $oRowValPatronais->r33_ppatro;
            break;
          case 6:

            $oValoresPatronais->aBasePrevidencia4["sNome"]  = substr($oRowValPatronais->r33_nome,0,15);
            $oValoresPatronais->aBasePrevidencia4["nValor"] = $oRowValPatronais->r33_ppatro;
            break;
        }
      }
    }

    return $oValoresPatronais;
  }

  /**
   * Codigo da previdencia a ser utilizada
   * @param integer $iTabelaPrevidencia
   */
  public  function setTabelaPrevidencia($iTabelaPrevidencia){
    
    if($iTabelaPrevidencia <> 0){
      $this->sFiltroPrevidencia = " rh02_tbprev = {$iTabelaPrevidencia} ";
    }
  }

  /**
   * Define filtro do Vinculo do servidor
   * Caso esteja vazio ou incorreto o deixa vazio
   * @param string $sVinculo
   */
  public  function setVinculo($sVinculo){

    switch ($sVinculo) {
//       case "g" :
//         $this->sVinculoServidor = " rh30_vinculo = 'G' ";
//         break;
      case "a" :
        $this->sVinculoServidor = " rh30_vinculo = 'A' ";
        break;
      case "i" :
        $this->sVinculoServidor = " rh30_vinculo = 'I' ";
        break;
      case "p" :
        $this->sVinculoServidor = " rh30_vinculo = 'P' ";
        break;
      case "ip":
        $this->sVinculoServidor = " rh30_vinculo in ('I','P') ";
        break;
      default:
        $this->sVinculoServidor = null;
      break;
    }
  }

  /**
   * Define a Ordenação do relatório
   * @param unknown_type $sSiglaOrdem
   */
  public  function setOrdemRelatorio($sSiglaOrdem) {
    
    switch ($sSiglaOrdem) {
      case "R": //numerica
      default :
        $this->sOrdemRelatorio = "rubrica";
      break;
      case "D":
        $this->sOrdemRelatorio = "descr_rubrica";
      break;
    }
  }
}