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
 * Prove dados para a geração do arquivo do balancete da receita do ano anterior no periodo 
 * do municipio para o SIGAP
 * @package Pad
 * @author  Iuri Guncthnigg
 * @version $Revision: 1.1 $
 */
final class PadArquivoSigapBalanceteReceitaAnterior extends PadArquivoSigap {
  
  /**
   * 
   */
  public function __construct() {
    
    $this->sNomeArquivo = "BalanceteReceitaAnterior";
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
    $sWhere         =  "o70_instit in ({$sListaInstit})";
    $iAnoUsu        = (db_getsession("DB_anousu")-1);
    $sDataIni       = $iAnoUsu."-01-01";
    $sDataFim       = $iAnoUsu."-12-31";
    $sSqlBalAnterior = db_receitasaldo(11, 1, 3, true, $sWhere, $iAnoUsu, $sDataIni, $sDataFim, true);
    
    $sSqlAgrupado = "select case when fc_conplano_grupo($iAnoUsu, substr(o57_fonte,1,1) || '%', 9000 ) is false "; 
    $sSqlAgrupado .= "           then substr(o57_fonte,2,14) else substr(o57_fonte,1,15) end as o57_fonte, ";
    $sSqlAgrupado .= "      o57_descr, ";
    $sSqlAgrupado .= "      saldo_inicial, ";
    $sSqlAgrupado .= "      saldo_arrecadado_acumulado, ";  
    $sSqlAgrupado .= "      x.o70_codigo, ";
    $sSqlAgrupado .= "      x.o70_codrec,           ";
    $sSqlAgrupado .= "      coalesce(o70_instit,0) as o70_instit, ";
    $sSqlAgrupado .= "      fc_nivel_plano2005(x.o57_fonte) as nivel "; 
    $sSqlAgrupado .= " from ({$sSqlBalAnterior}) as x  ";
    $sSqlAgrupado .= "      left join orcreceita on orcreceita.o70_codrec = x.o70_codrec and o70_anousu={$iAnoUsu} ";
    $sSqlAgrupado .= " order by o57_fonte "; 
    $rsBalancete   = db_query($sSqlAgrupado);
    $iTotalLinhas  = pg_num_rows($rsBalancete);
    for ($i = 1; $i < $iTotalLinhas; $i++) {
      
      $oReceita   = db_utils::fieldsMemory($rsBalancete, $i);
      $sDiaMesAno =  "{$iAno}-".str_pad($iMes, 2, "0", STR_PAD_LEFT)."-".str_pad($iDia, 2, "0", STR_PAD_LEFT);
      
      $oReceitaRetorno = new stdClass();
      
      $oReceitaRetorno->braCodigoEntidade                 = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT);
      $oReceitaRetorno->braMesAnoMovimento                = $sDiaMesAno;
      $oReceitaRetorno->braContaReceita                   = str_pad($oReceita->o57_fonte, 20, 0, STR_PAD_RIGHT);
      $oReceitaRetorno->braCodigoOrgaoUnidadeOrcamentaria = str_pad($oInstituicao->codtrib, 4, "0", STR_PAD_LEFT);
      $nSaldoInicial = $oReceita->saldo_inicial;
      $oReceita->saldo_inicial = str_pad(number_format(abs($oReceita->saldo_inicial),2,".",""), 13,'0', STR_PAD_LEFT);
      $oReceitaRetorno->braValorReceitaOrcada  = $oReceita->saldo_inicial;
      $oReceita->saldo_arrecadado_acumulado = str_pad(number_format(abs($oReceita->saldo_arrecadado_acumulado) 
                                                           ,2,".",""), 12,'0', STR_PAD_LEFT);
      $oReceitaRetorno->braValorReceitaRealizada   = $oReceita->saldo_arrecadado_acumulado;
      $oReceitaRetorno->braCodigoRecursoVinculado  = str_pad($oReceita->o70_codigo, 4, "0", STR_PAD_LEFT); 
      $oReceitaRetorno->braDescricaoContaReceita   = substr($oReceita->o57_descr, 0, 255); 
      $oReceitaRetorno->braTipoNivelConta          = ($oReceita->o70_codrec==0?'S':'A'); 
      $oReceitaRetorno->braNumeroNivelContaReceita = $oReceita->nivel;
      $this->aDados[] =  $oReceitaRetorno; 
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
                        "braCodigoEntidade",
                        "braMesAnoMovimento",
                        "braContaReceita",
                        "braCodigoOrgaoUnidadeOrcamentaria",
                        "braValorReceitaOrcada",
                        "braValorReceitaRealizada",
                        "braCodigoRecursoVinculado",
                        "braDescricaoContaReceita",
                        "braTipoNivelConta",
                        "braNumeroNivelContaReceita"
                       );
    return $aElementos;  
  }
  
}

?>