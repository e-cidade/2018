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

require_once ('model/PadArquivoSigap.model.php');
/**
 * Prove dados para a geração do arquivo do Balancete da despesa no periodo 
 * do municipio para o SIGAP
 * @package Pad
 * @author  Iuri Guncthnigg
 * @version $Revision: 1.7 $
 */
final class PadArquivoSigapBalanceteDespesa extends PadArquivoSigap {
  
  /**
   * 
   */
  public function __construct() {
    
    $this->sNomeArquivo = "BalanceteDespesa";
    $this->aDados       = array();
  }
  
  /**
   * Gera os dados para utilizacao posterior. Metodo geralmente usado 
   * em conjuto com a classe PadArquivoEscritorXML
   * @return true;
   */
  public function gerarDados() {
    
    if (empty($this->sDataInicial)) {
      throw new Exception("Data inicial nao informada!");
    }
    
    if (empty($this->sDataFinal)) {
      throw new Exception("Data final não informada!");
    }
    
    list($iAno, $iMes, $iDia) = explode("-",$this->sDataFinal);    
    $lUsaSubElemento    = false;
    $oParamOrcamento = db_stdClass::getParametro("orcparametro", array($iAno));
    if ($oParamOrcamento[0]->o50_subelem == 't') {
      $lUsaSubElemento = true; 
    }
        
    /**
     * Separamos a data do em ano, mes, dia
     */
    $oInstituicao = db_stdClass::getDadosInstit(db_getsession("DB_instit"));
    $sListaInstit = db_getsession("DB_instit");
    $sWhere       = "w.o58_instit in ({$sListaInstit}) ";
    db_query("begin");
    db_query("create temp table t as select * from orcdotacao where o58_anousu = ".db_getsession("DB_anousu"));
    
    $iAno    = db_getsession("DB_anousu");
    if ($lUsaSubElemento){
      $rsDotacaoSaldo = db_dotacaosaldo(8, 1, 4, true, $sWhere, $iAno,  
                                        $this->sDataInicial, $this->sDataFinal,'8','0',false,'1',true,"sim");
    }else { 
      $rsDotacaoSaldo = db_dotacaosaldo(8, 1, 4, true, $sWhere, $iAno,
                                       $this->sDataInicial, $this->sDataFinal);
    }
    pg_exec("rollback");
    $iTotalLinhas = pg_num_rows($rsDotacaoSaldo);
    for ($i = 0; $i < $iTotalLinhas; $i++) {
      
      $oDespesa            = db_utils::fieldsMemory($rsDotacaoSaldo, $i);

      if($oDespesa->o58_codigo == 0) {
        continue;
      }
      
      $sSqlSuplementacoes  = "  select sum(case when c71_coddoc in (7,52,53,54,55,71) then c70_valor else 0 end ) - ";
      $sSqlSuplementacoes .= "  sum(case when c71_coddoc in (8) then c70_valor else 0 end ) ";
      $sSqlSuplementacoes .= "  as sup , ";
      $sSqlSuplementacoes .= "  sum(case when c71_coddoc in (62,56,58,59,60,61,64) then c70_valor else 0 end ) - ";
      $sSqlSuplementacoes .= "  sum(case when c71_coddoc in (10) then c70_valor else 0 end ) ";
      $sSqlSuplementacoes .= "  as cre, ";
      $sSqlSuplementacoes .= "  sum(case when c71_coddoc in (63) then c70_valor else 0 end )- ";
      $sSqlSuplementacoes .= "  sum(case when c71_coddoc in (14) then c70_valor else 0 end ) ";
      $sSqlSuplementacoes .= "  as esp ";
      $sSqlSuplementacoes .= "  from conlancamdoc  ";
      $sSqlSuplementacoes .= "  inner join conlancam on c70_codlan = c71_codlan ";
      $sSqlSuplementacoes .= "  inner join conlancamdot on c73_codlan = c71_codlan  ";
      $sSqlSuplementacoes .= "  inner join conlancamsup on c79_codlan = c71_codlan  ";
      $sSqlSuplementacoes .= "  where c71_coddoc in (7,8,10,14,52,53,54,55,56,58,59,60,61,62,63,64,71) ";
      $sSqlSuplementacoes .= "  and c71_data between '{$this->sDataInicial}' and '{$this->sDataFinal}' ";
      $sSqlSuplementacoes .= "  and c73_coddot = $oDespesa->o58_coddot and ";
      $sSqlSuplementacoes .= "  c73_anousu = ".db_getsession("DB_anousu");
        
      $sSqlDesdobramento  = "select sum(case when c71_coddoc in (7,52,53,54,55) then c70_valor else 0 end ) as sup , ";
      $sSqlDesdobramento .= "       sum(case when c71_coddoc in (56,58,59,60,61,64) then c70_valor else 0 end ) as cre, ";
      $sSqlDesdobramento .= "       sum(case when c71_coddoc in (62,63) then c70_valor else 0 end ) as esp ";
      $sSqlDesdobramento .= "  from conlancamdoc  ";
      $sSqlDesdobramento .= "        inner join conlancam on c70_codlan = c71_codlan ";
      $sSqlDesdobramento .= "        inner join conlancamdot on c73_codlan = c71_codlan  ";
      $sSqlDesdobramento .= "        inner join conlancamsup on c79_codlan = c71_codlan ";
      $sSqlDesdobramento .= "        inner join orcdotacao  on c73_coddot = o58_coddot and "; 
      $sSqlDesdobramento .= "                              o58_anousu = ".db_getsession("DB_anousu")." and "; 
      $sSqlDesdobramento .= "                              o58_orgao = $oDespesa->o58_orgao and  ";
      $sSqlDesdobramento .= "                              o58_unidade = $oDespesa->o58_unidade and  ";
      $sSqlDesdobramento .= "                              o58_funcao = $oDespesa->o58_funcao and  ";
      $sSqlDesdobramento .= "                              o58_subfuncao = $oDespesa->o58_subfuncao and  ";
      $sSqlDesdobramento .= "                              o58_programa = $oDespesa->o58_programa and  ";
      $sSqlDesdobramento .= "                              o58_projativ = $oDespesa->o58_projativ ";
      $sSqlDesdobramento .= "                          and o58_codigo = $oDespesa->o58_codigo ";
      $sSqlDesdobramento .= "        inner join orcelemento on o56_codele = o58_codele and o56_anousu = o58_anousu ";
      $sSqlDesdobramento .= "  where c71_coddoc in (7,52,53,54,55,56,58,59,60,61,62,63,64) ";
      $sSqlDesdobramento .= "    and c71_data between '{$this->sDataInicial}' and '{$this->sDataFinal}' ";
      //$sql_desdobramento .= "  /* and c73_coddot = $o58_coddot */ ";
      $sSqlDesdobramento .= "    and substr(o56_elemento,1,7)='".substr($oDespesa->o58_elemento,0,7)."' ";
      $sSqlDesdobramento .= "    and c73_anousu = ".db_getsession("DB_anousu");
      
      if ($lUsaSubElemento) {
          $rsSuplementacoes = db_query($sSqlDesdobramento);      
      } else {
          $rsSuplementacoes = db_query($sSqlDesdobramento);     
      }  
      
      $nSuplementacoes        = 0;
      $nCreditoEspecias       = 0;
      $nCreditoExtraordinario = 0;
      if (pg_num_rows($rsSuplementacoes) >0 ) {
        
        $oSuplementacao         = db_utils::fieldsMemory($rsSuplementacoes, 0);
        $nSuplementacoes        = $oSuplementacao->sup; 
        $nCreditoEspecias       = $oSuplementacao->cre; 
        $nCreditoExtraordinario = $oSuplementacao->esp; 
          
      }
      
      /**
       * verifica qual o código do Recurso
       */
      $sSqlRecurso     = "select o15_codtri from orctiporec where o15_codigo = {$oDespesa->o58_codigo}";
      $rsrecurso       = db_query($sSqlRecurso);
      $iCodigoRecurso  = db_utils::fieldsMemory($rsrecurso, 0)->o15_codtri;
      $sDiaMesAno      =  "{$iAno}-".str_pad($iMes, 2, "0", STR_PAD_LEFT)."-".str_pad($iDia, 2, "0", STR_PAD_LEFT);
      $oDotacaoRetorno = new stdClass();
      $oDotacaoRetorno->bdeCodigoEntidade                = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT);
      $oDotacaoRetorno->bdeMesAnoMovimento               = $sDiaMesAno;
      $oDotacaoRetorno->bdeCodigoOrgao                   = str_pad($oDespesa->o58_orgao, 2, 0, STR_PAD_LEFT);
      $oDotacaoRetorno->bdeCodigoUnidadeOrcamentaria     = str_pad($oDespesa->o58_unidade, 2, 0, STR_PAD_LEFT);
      $oDotacaoRetorno->bdeCodigoFuncao                  = str_pad($oDespesa->o58_funcao, 2, 0, STR_PAD_LEFT);
      $oDotacaoRetorno->bdeCodigoSubFuncao               = str_pad($oDespesa->o58_subfuncao, 3, 0, STR_PAD_LEFT);
      $oDotacaoRetorno->bdeCodigoPrograma                = str_pad($oDespesa->o58_programa, 4 , 0, STR_PAD_LEFT);
      $oDotacaoRetorno->bdeCodigoProjetoAtividade        = str_pad($oDespesa->o58_projativ, 5, 0, STR_PAD_LEFT);
      $oDotacaoRetorno->bdeCodigoElemento                = str_pad(substr($oDespesa->o58_elemento, 1, 14), 15, "0",
                                                                   STR_PAD_RIGHT);
      $oDotacaoRetorno->bdeCodigoRecursoVinculado        = str_pad($iCodigoRecurso, 6, 0, STR_PAD_LEFT);
      $oDotacaoRetorno->bdeDotacaoInicial                = $this->corrigeValor($oDespesa->dot_ini, 13);
      $oDotacaoRetorno->bdeAtualizacaoMonetaria          = $this->corrigeValor(0, 13);
      $oDotacaoRetorno->bdeCreditoSuplementar            = $this->corrigeValor($nSuplementacoes, 13);
      $oDotacaoRetorno->bdeCreditoEspecial               = $this->corrigeValor($nCreditoEspecias, 13);
      $oDotacaoRetorno->bdeCreditoExtraordinario         = $this->corrigeValor($nCreditoExtraordinario, 13);
      $oDotacaoRetorno->bdeReducaoDotacoes               = $this->corrigeValor(abs($oDespesa->reduzido_acumulado), 13);
      $oDotacaoRetorno->bdeSuplementacaoRecursoVinculado = $this->corrigeValor(0, 13);
      $oDotacaoRetorno->bdeValorEmpenhado                = $this->corrigeValor(abs(
                                                                  round($oDespesa->empenhado-$oDespesa->anulado,2)), 13);
      $oDotacaoRetorno->bdeValorLiquidado                = $this->corrigeValor(abs($oDespesa->liquidado), 13);                                                                  
      $oDotacaoRetorno->bdeValorPago                     = $this->corrigeValor(abs($oDespesa->pago), 13);                                                                  
      $oDotacaoRetorno->bdeValorLimitadoLRF              = $this->corrigeValor(0, 13);                                                                  
      $oDotacaoRetorno->bdeValorRecomposicaoDotacaoLRF   = $this->corrigeValor(0, 13);
      
      $oDotacaoRetorno->bdeValorPrevisaoRealizacaoTerminoExercicioLRF = $this->corrigeValor(0, 13);                                                                  
      $oDotacaoRetorno->bdeReducaoPorRecursoVinculado                 = $this->corrigeValor(0, 13);  

      $nValorPrevisaoAtualizada                          = round(($oDespesa->dot_ini + ($oDespesa->suplementado - $oDespesa->reduzido)), 2);
      $oDotacaoRetorno->bdeValorDotacaoAtualizada        = $this->corrigeValor(abs($nValorPrevisaoAtualizada), 13);
      
      $this->aDados[] =  $oDotacaoRetorno; 
    }
    
    return true;
  }
  
  
  /**
   * Publica quais elementos/Campos estão disponiveis para 
   * o uso no momento da geração do arquivo
   *
   * @return array com elementos disponibilizados para a geração dos arquivo
   */
  public function getNomeElementos() {
    
    $aElementos = array(
                        "bdeCodigoEntidade",
                        "bdeMesAnoMovimento",
                        "bdeCodigoOrgao",
                        "bdeCodigoUnidadeOrcamentaria",
                        "bdeCodigoFuncao",
                        "bdeCodigoSubFuncao",
                        "bdeCodigoPrograma",
                        "bdeCodigoProjetoAtividade",
                        "bdeCodigoElemento",
                        "bdeCodigoRecursoVinculado",
                        "bdeDotacaoInicial",
                        "bdeAtualizacaoMonetaria",
                        "bdeCreditoSuplementar",
                        "bdeCreditoEspecial",
                        "bdeCreditoExtraordinario",
                        "bdeReducaoDotacoes",
                        "bdeSuplementacaoRecursoVinculado",
                        "bdeReducaoPorRecursoVinculado",
                        "bdeValorEmpenhado",
                        "bdeValorLiquidado",
                        "bdeValorPago",
                        "bdeValorLimitadoLRF",
                        "bdeValorRecomposicaoDotacaoLRF",
                        "bdeValorPrevisaoRealizacaoTerminoExercicioLRF",
                        "bdeValorDotacaoAtualizada"
                       );
                       
    return $aElementos;  
  }
  
  private function corrigeValor ($valor, $quant) {
    
    if (empty($valor)) {
      $valor = 0;
    }

    if ($valor < 0) {
      
      $valor *= -1;
      $valor  = "-".str_pad(number_format($valor, 2, ".",""),  $quant-1, '0', STR_PAD_LEFT);
    } else {
      $valor  = str_pad(number_format($valor, 2, ".",""), $quant, '0', STR_PAD_LEFT);
    }
    
    return $valor;
  }
}
?>