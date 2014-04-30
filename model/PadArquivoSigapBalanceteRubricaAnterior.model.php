<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
 * Prove dados para a geração do arquivo do balancete ds rubricas do ano anterior no periodo 
 * do municipio para o SIGAP
 * @package Pad
 * @author  Iuri Guncthnigg
 * @version $Revision: 1.1 $
 */
final class PadArquivoSigapBalanceteRubricaAnterior extends PadArquivoSigap {
  
  /**
   * 
   */
  public function __construct() {
    
    $this->sNomeArquivo = "BalanceteRubricaAnterior";
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
    /**
     * Separamos a data do em ano, mes, dia
     */
    list($iAno, $iMes, $iDia) = explode("-",$this->sDataFinal);
    
    $oInstituicao   = db_stdClass::getDadosInstit(db_getsession("DB_instit"));
    $sListaInstit   = db_getsession("DB_instit");
    $sWhere         =  "o58_instit in ({$sListaInstit})";
    $iAnoUsu        = (db_getsession("DB_anousu")-1);
    $sDataIni       = $iAnoUsu."-01-01";
    $sDataFim       = $iAnoUsu."-12-31";
    $sSqlDadosRubrica  = " select o58_orgao, ";
    $sSqlDadosRubrica .= "         o58_unidade, ";
    $sSqlDadosRubrica .= "   o58_funcao, ";
    $sSqlDadosRubrica .= "   o58_subfuncao, ";
    $sSqlDadosRubrica .= "   o58_programa, ";
    $sSqlDadosRubrica .= "         o58_projativ, ";
    $sSqlDadosRubrica .= "       o58_codele as c67_codele, ";
    $sSqlDadosRubrica .= "   o56_elemento, ";
    $sSqlDadosRubrica .= "   o58_codigo,";
    for ($i = 1; $i <= 12; $i++) {
      
      $iUltimoDiaMes = cal_days_in_month(CAL_GREGORIAN, $i, $iAnoUsu);
      $iMes          = str_pad($i, 2, "0",STR_PAD_LEFT);         
      $sSqlDadosRubrica .= "round(sum(case when c70_data >= '{$iAnoUsu}-{$iMes}-01' 
                                            and c70_data < '{$iAnoUsu}-{$iMes}-{$iUltimoDiaMes}' 
                                            and c71_coddoc = 1 then c70_valor end) ,2) as emp{$i},";
                                            
      $sSqlDadosRubrica .= "round(sum(case when c70_data >= '{$iAnoUsu}-{$iMes}-01' 
                                            and c70_data < '{$iAnoUsu}-{$iMes}-{$iUltimoDiaMes}' 
                                            and c71_coddoc = 2 then c70_valor end) ,2) as eemp{$i},";
                                                                                        
      $sSqlDadosRubrica .= "round(sum(case when c70_data >= '{$iAnoUsu}-{$iMes}-01' 
                                            and c70_data < '{$iAnoUsu}-{$iMes}-{$iUltimoDiaMes}' 
                                            and c71_coddoc in(3, 23) then c70_valor end) ,2) as liq{$i}, ";
                                                                                        
      $sSqlDadosRubrica .= "round(sum(case when c70_data >= '{$iAnoUsu}-{$iMes}-01' 
                                            and c70_data < '{$iAnoUsu}-{$iMes}-{$iUltimoDiaMes}' 
                                            and c71_coddoc in(4, 24) then c70_valor end) ,2) as eliq{$i}, ";
                                                                                        
      $sSqlDadosRubrica .= "round(sum(case when c70_data >= '{$iAnoUsu}-{$iMes}-01' 
                                            and c70_data < '{$iAnoUsu}-{$iMes}-{$iUltimoDiaMes}' 
                                            and c71_coddoc in(5) then c70_valor end) ,2) as pag{$i}, ";
                                            
      $sSqlDadosRubrica .= "round(sum(case when c70_data >= '{$iAnoUsu}-{$iMes}-01' 
                                            and c70_data < '{$iAnoUsu}-{$iMes}-{$iUltimoDiaMes}' 
                                            and c71_coddoc in(6) then c70_valor end) ,2) as epag{$i} ";
      if ($i < 12) {
        $sSqlDadosRubrica .= ",";                                        
      }
                                                                                        
    }
    $aMeses = array( 1 => "Janeiro",
                     2 => "Fevereiro", 
                     3 => "Marco",
                     4 => "Abril", 
                     5 => "Maio", 
                     6 => "Junho", 
                     7 => "Julho", 
                     8 => "Agosto", 
                     9 => "Setembro", 
                    10 => "Outubro", 
                    11 => "Novembro", 
                    12 => "Dezembro", 
                   );
    
    $sSqlDadosRubrica .= "from orcdotacao ";
    $sSqlDadosRubrica .= "    inner join conlancamdot on o58_anousu = c73_anousu and o58_coddot  = c73_coddot ";
    $sSqlDadosRubrica .= "     inner join conlancamdoc on c73_codlan = c71_codlan ";
    $sSqlDadosRubrica .= "      inner join conlancam    on c70_codlan = c73_codlan ";
    $sSqlDadosRubrica .= "      inner join conlancamele on c67_codlan = c73_codlan    ";
    $sSqlDadosRubrica .= "     inner join orcelemento  on o58_anousu = o56_anousu and o56_codele = c67_codele ";
    $sSqlDadosRubrica .= "where o58_anousu = {$iAnoUsu} and {$sWhere} ";
    $sSqlDadosRubrica .= "group by o58_orgao, ";
    $sSqlDadosRubrica .= "        o58_unidade, ";
    $sSqlDadosRubrica .= "         o58_funcao, ";
    $sSqlDadosRubrica .= "        o58_subfuncao, ";
    $sSqlDadosRubrica .= "        o58_programa, ";
    $sSqlDadosRubrica .= "         o58_projativ, ";
    $sSqlDadosRubrica .= "        o58_codele, ";
    $sSqlDadosRubrica .= "o56_elemento, ";
    $sSqlDadosRubrica .= "         o58_codigo";
    $rsBalancete   = db_query($sSqlDadosRubrica);
    $iTotalLinhas  = pg_num_rows($rsBalancete);
    for ($i = 1; $i < $iTotalLinhas; $i++) {
      
      $oDespesa   = db_utils::fieldsMemory($rsBalancete, $i);
      $sDiaMesAno =  "{$iAno}-".str_pad($iMes, 2, "0", STR_PAD_LEFT)."-".str_pad($iDia, 2, "0", STR_PAD_LEFT);
      
      $oDespesaRetorno = new stdClass();
      $oDespesaRetorno->bdaCodigoEntidade                 = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT);
      $oDespesaRetorno->bdaMesAnoMovimento                = $sDiaMesAno;
      $oDespesaRetorno->bdaCodigoOrgao                   = str_pad($oDespesa->o58_orgao, 2, 0, STR_PAD_LEFT);
      $oDespesaRetorno->bdaCodigoUnidadeOrcamentaria     = str_pad($oDespesa->o58_unidade, 2, 0, STR_PAD_LEFT);
      $oDespesaRetorno->bdaCodigoFuncao                  = str_pad($oDespesa->o58_funcao, 2, 0, STR_PAD_LEFT);
      $oDespesaRetorno->bdaCodigoSubFuncao               = str_pad($oDespesa->o58_subfuncao, 3, 0, STR_PAD_LEFT);
      $oDespesaRetorno->bdaCodigoPrograma                = str_pad($oDespesa->o58_programa, 4 , 0, STR_PAD_LEFT);
      $oDespesaRetorno->bdaCodigoProjetoAtividade        = str_pad($oDespesa->o58_projativ, 5, 0, STR_PAD_LEFT);
      $oDespesaRetorno->bdaCodigoElemento                = str_pad($oDespesa->o56_elemento,15,"0");
      $oDespesaRetorno->bdaCodigoRecursoVinculado        = str_pad($oDespesa->o58_codigo, 4, 0, STR_PAD_LEFT);;
      for ($j = 1; $j <= 12; $j++) {

        $oDespesaRetorno->{"bdaValorEmpenhado{$aMeses[$j]}"} = $oDespesa->{"emp{$j}"} - $oDespesa->{"eemp{$j}"};
        $oDespesaRetorno->{"bdaValorLiquidado{$aMeses[$j]}"} = $oDespesa->{"liq{$j}"} - $oDespesa->{"eliq{$j}"};
        $oDespesaRetorno->{"bdaValorPago{$aMeses[$j]}"}      = $oDespesa->{"pag{$j}"} - $oDespesa->{"epag{$j}"};
      }
      $this->aDados[] =  $oDespesaRetorno; 
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
                        "bdaCodigoEntidade",
                        "bdaMesAnoMovimento",
                        "bdaCodigoOrgao",
                        "bdaCodigoUnidadeOrcamentaria",
                        "bdaCodigoFuncao",
                        "bdaCodigoSubFuncao",
                        "bdaCodigoPrograma",
                        "bdaCodigoProjetoAtividade",
                        "bdaCodigoElemento",
                        "bdaCodigoRecursoVinculado",
                        "bdaValorEmpenhadoJaneiro",
                        "bdaValorEmpenhadoFevereiro",
                        "bdaValorEmpenhadoMarco",
                        "bdaValorEmpenhadoAbril",
                        "bdaValorEmpenhadoMaio",
                        "bdaValorEmpenhadoJunho",
                        "bdaValorEmpenhadoJulho",
                        "bdaValorEmpenhadoAgosto",
                        "bdaValorEmpenhadoSetembro",
                        "bdaValorEmpenhadoOutubro",
                        "bdaValorEmpenhadoNovembro",
                        "bdaValorEmpenhadoDezembro",
                        "bdaValorLiquidadoJaneiro",
                        "bdaValorLiquidadoFevereiro",
                        "bdaValorLiquidadoMarco",
                        "bdaValorLiquidadoAbril",
                        "bdaValorLiquidadoMaio",
                        "bdaValorLiquidadoJunho",
                        "bdaValorLiquidadoJulho",
                        "bdaValorLiquidadoAgosto",
                        "bdaValorLiquidadoSetembro",
                        "bdaValorLiquidadoOutubro",
                        "bdaValorLiquidadoNovembro",
                        "bdaValorLiquidadoDezembro",
                        "bdaValorPagoJaneiro",
                        "bdaValorPagoFevereiro",
                        "bdaValorPagoMarco",
                        "bdaValorPagoAbril",
                        "bdaValorPagoMaio",
                        "bdaValorPagoJunho",
                        "bdaValorPagoJulho",
                        "bdaValorPagoAgosto",
                        "bdaValorPagoSetembro",
                        "bdaValorPagoOutubro",
                        "bdaValorPagoNovembro",
                        "bdaValorPagoDezembro",
                       );
    return $aElementos;  
  }
  
}

?>