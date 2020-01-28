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
 * Prove dados para a geração do arquivo das liquidações que possuiram movimentacao no periodo 
 * do municipio para o SIGAP
 * @package Pad
 * @author  Iuri Guncthnigg
 * @version $Revision: 1.5 $
 */
final class PadArquivoSigapLiquidacao extends PadArquivoSigap {
  
  /**
   * 
   */
  public function __construct() {
    
    $this->sNomeArquivo = "Liquidacao";
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
    $sListaInstit = db_getsession("DB_instit");
    
    $sSqlLiquidacoes  = " select e60_anousu as ano, ";
    $sSqlLiquidacoes .= "        trim(e60_codemp)::integer as e60_codemp, ";
    $sSqlLiquidacoes .= "        e60_numemp,";
    $sSqlLiquidacoes .= "        c75_codlan,";
    $sSqlLiquidacoes .= "        c75_data,";
    $sSqlLiquidacoes .= "        round(c70_valor,2)::float8 as c70_valor,";
    $sSqlLiquidacoes .= "        case when c53_tipo = 20 "; 
    $sSqlLiquidacoes .= "             then '+'"; 
    $sSqlLiquidacoes .= "        else '-' end as sinal,";
    $sSqlLiquidacoes .= "        'Liquidação Número :'||c70_codlan as historico,";
    $sSqlLiquidacoes .= "        c75_codlan as operacao,";
    $sSqlLiquidacoes .= "        z01_cgccpf,";
    $sSqlLiquidacoes .= "        e60_instit";
    $sSqlLiquidacoes .= "   from conlancamemp";
    $sSqlLiquidacoes .= "        inner join conlancam    on c70_codlan = c75_codlan";
    $sSqlLiquidacoes .= "        inner join empempenho   on e60_numemp = c75_numemp";
    $sSqlLiquidacoes .= "        inner join cgm          on e60_numcgm = z01_numcgm";
    $sSqlLiquidacoes .= "        inner join conlancamdoc on c71_codlan = c75_codlan";
    $sSqlLiquidacoes .= "        inner join conhistdoc   on c53_coddoc = c71_coddoc ";
    $sSqlLiquidacoes .= "                               and (c53_tipo = 20 or c53_tipo = 21) ";        
    $sSqlLiquidacoes .= "  where c75_data between '{$this->sDataInicial}' and '{$this->sDataFinal}'";
    $sSqlLiquidacoes .= "   and e60_emiss  <='{$this->sDataFinal}'";
    $sSqlLiquidacoes .= "   and e60_anousu  = {$iAno}";
    $sSqlLiquidacoes .= "   and e60_instit in ($sListaInstit)";
    
    $sSqlLiquidacoes .= " union all ";     
    
    $sSqlLiquidacoes .= " select e60_anousu as ano, ";
    $sSqlLiquidacoes .= "        trim(e60_codemp)::integer as e60_codemp, ";
    $sSqlLiquidacoes .= "        e60_numemp,";
    $sSqlLiquidacoes .= "        c75_codlan, ";
    $sSqlLiquidacoes .= "        c75_data, ";
    $sSqlLiquidacoes .= "        round(c70_valor,2)::float8 as c70_valor, ";
    $sSqlLiquidacoes .= "        case when c53_tipo = 20 then ";
    $sSqlLiquidacoes .= "           '+'  else '-' end as sinal, ";
    $sSqlLiquidacoes .= "       'Liquidação Número :'||c70_codlan as historico, ";
    $sSqlLiquidacoes .= "       c75_codlan as operacao, ";
    $sSqlLiquidacoes .= "       z01_cgccpf,";
    $sSqlLiquidacoes .= "       e60_instit "; 
    $sSqlLiquidacoes .= "  from empresto  ";
    $sSqlLiquidacoes .= "       inner join conlancamemp on e91_numemp = c75_numemp ";
    $sSqlLiquidacoes .= "       inner join conlancam    on c70_codlan = c75_codlan ";
    $sSqlLiquidacoes .= "       inner join empempenho   on e60_numemp = c75_numemp ";
    $sSqlLiquidacoes .= "       inner join cgm          on e60_numcgm = z01_numcgm ";
    $sSqlLiquidacoes .= "       inner join conlancamdoc on c71_codlan = c75_codlan ";
    $sSqlLiquidacoes .= "       inner join conhistdoc   on c53_coddoc = c71_coddoc ";
    $sSqlLiquidacoes .= "                              and ((c53_tipo  = 20 or c53_tipo = 21) or c53_coddoc = 31) ";      
    $sSqlLiquidacoes .= "  where (c75_data <= '{$this->sDataFinal}' ";
//    $sSqlLiquidacoes .= "  where (c75_data between '".db_getsession("DB_anousu")."-01-31' and '{$this->sDataFinal}' ";
    $sSqlLiquidacoes .= "         or exists (select 1 from 
                                           conlancamemp a inner join conlancam on c70_codlan = a.c75_codlan 
                                           inner join conlancamdoc on a.c75_codlan = c71_codlan
                                           inner join conhistdoc on c71_coddoc = c53_coddoc
                                           where a.c75_data between '{$this->sDataInicial}' and '{$this->sDataFinal}'
                                             and c53_tipo in (30, 31)
                                             and a.c75_numemp = e60_numemp))";
    $sSqlLiquidacoes .= "     and e60_emiss <='{$this->sDataFinal}' ";
    $sSqlLiquidacoes .= "     and e60_anousu < ".db_getsession("DB_anousu");
    $sSqlLiquidacoes .= "     and e60_instit in ($sListaInstit) and e91_anousu = ".db_getsession("DB_anousu");
    $sSqlLiquidacoes .= "  order by c75_data";
    $rsLiquidacoes    = db_query($sSqlLiquidacoes);
    $iTotalLinhas = pg_num_rows($rsLiquidacoes);
     
    for ($i = 0; $i < $iTotalLinhas; $i++) {
      
      $sDiaMesAno  =  "{$iAno}-".str_pad($iMes, 2, "0", STR_PAD_LEFT)."-".str_pad($iDia, 2, "0", STR_PAD_LEFT);
      $oLiquidacao = db_utils::fieldsMemory($rsLiquidacoes, $i);
      
      $oLiquidacaoRetorno                                = new stdClass();
      //$oLiquidacaoRetorno->empCodigoEntidade             = str_pad(db_getsession("DB_instit"), 4, "0", STR_PAD_LEFT);
      $oLiquidacaoRetorno->liqCodigoEntidade             = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT);
      $oLiquidacaoRetorno->liqMesAnoMovimento           = $sDiaMesAno;
      $sNumeroEmpenho  = str_pad($oLiquidacao->e60_codemp, 5, "0", STR_PAD_LEFT);
      $oLiquidacaoRetorno->liqEmpenhoNumero             = $oLiquidacao->ano.$sNumeroEmpenho;
      $oLiquidacaoRetorno->liqNumeroLiquidacao          = $oLiquidacao->c75_codlan;
      $oLiquidacaoRetorno->liqDataLiquidacao            = $oLiquidacao->c75_data;
      $oLiquidacaoRetorno->liqValorLiquidacao           = number_format($oLiquidacao->c70_valor,2,".","");
      $oLiquidacaoRetorno->liqSinal                     = $oLiquidacao->sinal;
      $oLiquidacaoRetorno->liqHistoricoLiquidacao       = $oLiquidacao->historico;
      $oLiquidacaoRetorno->liqCodigoOperacao            = "";
      $iTamanhoPad  = strlen($oLiquidacao->z01_cgccpf);
      $oLiquidacaoRetorno->liqCnpjCpf                   = str_pad($oLiquidacao->z01_cgccpf, $iTamanhoPad, 0, STR_PAD_LEFT);
      $oLiquidacaoRetorno->liqProcesso                  = $oLiquidacao->e60_numemp;
      array_push($this->aDados, $oLiquidacaoRetorno);
      
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
                        "liqCodigoEntidade",
                        "liqMesAnoMovimento",
                        "liqEmpenhoNumero",
                        "liqNumeroLiquidacao",
                        "liqDataLiquidacao",
                        "liqValorLiquidacao",
                        "liqSinal",
                        "liqHistoricoLiquidacao",
                        "liqCodigoOperacao",
                        "liqCnpjCpf",
                        "liqProcesso",
                       );
    return $aElementos;  
  }
  
}

?>