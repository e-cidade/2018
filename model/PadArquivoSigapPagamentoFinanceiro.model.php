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
 * Prove dados para a geração do arquivo dos pagamentos que possuiram movimentacao no periodo 
 * do municipio para o SIGAP
 * @package Pad
 * @author  Iuri Guncthnigg
 * @version $Revision: 1.2 $
 */
final class PadArquivoSigapPagamentoFinanceiro extends PadArquivoSigap {
  
  /**
   * 
   */
  public function __construct() {
    
    $this->sNomeArquivo = "PagamentoFinanceiro";
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
    $sWhere          =  " and e60_instit in ({$sListaInstit})";
    $sSqlPagamentos  = "select e60_anousu as ano, ";
    $sSqlPagamentos .= "       trim(e60_codemp)::integer as e60_codemp, ";
    $sSqlPagamentos .= "       e60_numemp, ";
    $sSqlPagamentos .= "       c75_codlan, ";
    $sSqlPagamentos .= "       c75_data, ";
    $sSqlPagamentos .= "       round(c70_valor,2) as c70_valor, ";
    $sSqlPagamentos .= "       case when c53_tipo =30 then ";
    $sSqlPagamentos .= "           '+'  else '-' ";  
    $sSqlPagamentos .= "       end as sinal, ";
    $sSqlPagamentos .= "       case when c72_complem is null then '' else c72_complem end || ";
    $sSqlPagamentos .= "            ' Ordem Pagto:' || c80_codord as historico, ";
    $sSqlPagamentos .= "       c75_codlan as operacao, ";
    $sSqlPagamentos .= "       c82_reduz as conta_pagadora, ";
    $sSqlPagamentos .= "       db_config.codtrib as orgao_unidade, ";
    $sSqlPagamentos .= "       e60_instit, ";
    $sSqlPagamentos .= "      e91_cheque, ";
    $sSqlPagamentos .= "      e97_codforma ";
    $sSqlPagamentos .= "  from conlancamemp    ";
    $sSqlPagamentos .= "       inner join conlancam      on c70_codlan = c75_codlan ";
    $sSqlPagamentos .= "       inner join conlancampag   on c82_codlan = c70_codlan ";
    $sSqlPagamentos .= "       inner join empempenho     on e60_numemp = c75_numemp ";
    $sSqlPagamentos .= "       inner join db_config      on db_config.codigo = empempenho.e60_instit ";
    $sSqlPagamentos .= "       inner join conlancamdoc   on c71_codlan = c75_codlan ";
    $sSqlPagamentos .= "       inner join conhistdoc     on c53_coddoc = c71_coddoc ";
    $sSqlPagamentos .= "                                and (c53_tipo  = 30 or c53_tipo = 31)  ";
    $sSqlPagamentos .= "       left  join conlancamord   on c80_codlan = c70_codlan ";
    $sSqlPagamentos .= "       left  join conlancamcompl on c72_codlan = c70_codlan ";
    $sSqlPagamentos .= "       left  join conlancamcorgrupocorrente on c23_conlancam = c70_codlan ";
    $sSqlPagamentos .= "       left  join corgrupocorrente on k105_sequencial = c23_corgrupocorrente ";
    $sSqlPagamentos .= "       left  join corconf    on k105_data   = corconf.k12_data ";
    $sSqlPagamentos .= "                            and k105_autent = corconf.k12_autent ";
    $sSqlPagamentos .= "                            and k105_id     = corconf.k12_id ";
    $sSqlPagamentos .= "       left  join empageconfche on corconf.k12_codmov =  e91_codcheque";
    $sSqlPagamentos .= "       left  join corempagemov  on k105_data   = corempagemov.k12_data ";
    $sSqlPagamentos .= "                               and k105_autent = corempagemov.k12_autent ";
    $sSqlPagamentos .= "                               and k105_id     = corempagemov.k12_id ";
    $sSqlPagamentos .= "       left  join empagemovforma on  corempagemov.k12_codmov = e97_codmov";
    $sSqlPagamentos .= " where c75_data between '{$this->sDataInicial}' and '{$this->sDataFinal}' ";
    $sSqlPagamentos .= "   and e60_emiss <= '{$this->sDataFinal}' {$sWhere} ";     
    $sSqlPagamentos .= "   and e60_anousu = ".db_getsession("DB_anousu"); 
    
    $sSqlPagamentos .= " union all ";
    
    $sSqlPagamentos .= "select e60_anousu as ano, ";
    $sSqlPagamentos .= "       trim(e60_codemp)::integer as e60_codemp, ";
    $sSqlPagamentos .= "       e60_numemp, ";
    $sSqlPagamentos .= "       c75_codlan, ";
    $sSqlPagamentos .= "       c75_data, ";
    $sSqlPagamentos .= "       round(c70_valor,2) as c70_valor, ";
    $sSqlPagamentos .= "       case when c53_tipo =30 then ";
    $sSqlPagamentos .= "          '+'  else '-' ";  
    $sSqlPagamentos .= "       end as sinal, ";
    $sSqlPagamentos .= "       case when c72_complem is null then '' ";
    $sSqlPagamentos .= "            else c72_complem end || ' Ordem Pagto:' || c80_codord as historico, ";
    $sSqlPagamentos .= "       c75_codlan as operacao, ";
    $sSqlPagamentos .= "       c82_reduz as conta_pagadora, ";
    $sSqlPagamentos .= "       db_config.codtrib as orgao_unidade, ";
    $sSqlPagamentos .= "       e60_instit, ";
    $sSqlPagamentos .= "      e91_cheque, ";
    $sSqlPagamentos .= "      e97_codforma ";
    $sSqlPagamentos .= "  from empresto ";
    $sSqlPagamentos .= "       inner join conlancamemp on c75_numemp = e91_numemp ";
    $sSqlPagamentos .= "       inner join conlancam    on c70_codlan = c75_codlan ";
    $sSqlPagamentos .= "       inner join conlancampag on c82_codlan = c70_codlan ";
    $sSqlPagamentos .= "       inner join empempenho on e60_numemp = c75_numemp ";
    $sSqlPagamentos .= "       inner join db_config on db_config.codigo = empempenho.e60_instit ";
    $sSqlPagamentos .= "       inner join conlancamdoc on c71_codlan = c75_codlan ";
    $sSqlPagamentos .= "       inner join conhistdoc on c53_coddoc = c71_coddoc ";
    $sSqlPagamentos .= "                            and (c53_tipo = 30 or c53_tipo = 31)  ";
    $sSqlPagamentos .= "       left outer join conlancamord on c80_codlan = c70_codlan ";
    $sSqlPagamentos .= "       left outer join conlancamcompl on c72_codlan = c70_codlan ";
    $sSqlPagamentos .= "       left  join conlancamcorgrupocorrente on c23_conlancam = c70_codlan ";
    $sSqlPagamentos .= "       left  join corgrupocorrente on k105_sequencial = c23_corgrupocorrente ";
    $sSqlPagamentos .= "       left  join corconf    on k105_data   = corconf.k12_data ";
    $sSqlPagamentos .= "                            and k105_autent = corconf.k12_autent ";
    $sSqlPagamentos .= "                            and k105_id     = corconf.k12_id ";
    $sSqlPagamentos .= "       left  join empageconfche on corconf.k12_codmov =  e91_codcheque";
    $sSqlPagamentos .= "       left  join corempagemov  on k105_data   = corempagemov.k12_data ";
    $sSqlPagamentos .= "                               and k105_autent = corempagemov.k12_autent ";
    $sSqlPagamentos .= "                               and k105_id     = corempagemov.k12_id ";
    $sSqlPagamentos .= "       left  join empagemovforma on  corempagemov.k12_codmov = e97_codmov";
    $sSqlPagamentos .= " where e91_anousu = ".db_getsession("DB_anousu");
    $sSqlPagamentos .= "   and e91_rpcorreto is true ";
    $sSqlPagamentos .= "   and c75_data between '{$this->sDataInicial}' and '{$this->sDataFinal}' ";
    $sSqlPagamentos .= "   and e60_emiss <= '{$this->sDataFinal}' {$sWhere} ";             
    $sSqlPagamentos .= " order by ano,c75_data,c75_codlan,e60_codemp ";
   
    
    $rsPagamentos = db_query($sSqlPagamentos);
    $iTotalLinhas = pg_num_rows($rsPagamentos);
    $iTotal=0; 
    $this->addLog("\n".str_repeat("-", 30)."PagamentoFinanciero".str_repeat("-", 30)."\n");
    for ($i = 0; $i < $iTotalLinhas; $i++) {
      
      
      $oPagamento = db_utils::fieldsMemory($rsPagamentos, $i);
      $sSqlVerificaLiquidacao  = "SELECT c66_codlan ";
      $sSqlVerificaLiquidacao .= "  from conlancamord  ";
      $sSqlVerificaLiquidacao .= "       inner join pagordemnota  on c80_codord = e71_codord ";
      $sSqlVerificaLiquidacao .= "       inner join conlancamnota on c66_codnota = e71_codnota";
      $sSqlVerificaLiquidacao .= "       inner join conlancamdoc  on c66_codlan  = c71_codlan";
      $sSqlVerificaLiquidacao .= "       inner join conlancam     on c70_codlan  = c71_codlan";
      //$sSqlVerificaLiquidacao .= "                               and c71_coddoc in(3, 23, 33)";
      $sSqlVerificaLiquidacao .= " where c80_codlan = {$oPagamento->c75_codlan}";
      $sSqlVerificaLiquidacao .= "  and  c70_data   <= '{$oPagamento->c75_data}'";
      $rsVerificaLancamento    = db_query($sSqlVerificaLiquidacao);
      $iCodigoLiquidacao       = $oPagamento->c75_codlan;
      if (pg_num_rows($rsVerificaLancamento) > 0) {
        
        $iCodigoLiquidacao         = db_utils::fieldsMemory($rsVerificaLancamento, 0)->c66_codlan;
        $iCodigoLiquidacaoUtilizar = $iCodigoLiquidacao;
      } else {
        //$iCodigoLiquidacao = $iCodigoLiquidacaoUtilizar;
        $iTotal++;
        $sLog   = "Pagamento sem liquidacoes:{$oPagamento->c75_codlan} - {$oPagamento->e60_numemp} - {$oPagamento->ano}";
        $sLog  .= " - ($oPagamento->sinal)$oPagamento->c70_valor\n";
        $this->addLog($sLog);
      }
      
      $iFormaPagamento  = '0';
      $iNumeroDocumento = '0';
      switch ($oPagamento->e97_codforma) {
        
        case 1 ;
        
          $iFormaPagamento  = '01';
          $iNumeroDocumento = '0';
          break;
          
        case 2 ;
        
          $iFormaPagamento  = '02';
          $iNumeroDocumento = $oPagamento->e91_cheque;
          break;

        case 3 ;
        
          $iFormaPagamento = '04';
          $iNumeroDocumento = '0';
          break;
            
        default:
          
          $iFormaPagamento  = '01';
          $iNumeroDocumento = '0';
          break;  
          
      }
      $sDiaMesAno  =  "{$iAno}-".str_pad($iMes, 2, "0", STR_PAD_LEFT)."-".str_pad($iDia, 2, "0", STR_PAD_LEFT);
      $oPagamentoRetorno                              = new stdClass();
      $oPagamentoRetorno->pfiCodigoEntidade           = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT);
      $oPagamentoRetorno->pfiMesAnoMovimento          = $sDiaMesAno;
      $sNumeroEmpenho  = str_pad($oPagamento->e60_codemp, 5, "0", STR_PAD_LEFT);
      $oPagamentoRetorno->pfiEmpenhoNumero            = $oPagamento->ano.$sNumeroEmpenho;
      $oPagamentoRetorno->pfiNumeroPagamento          = $oPagamento->c75_codlan;
      $oPagamentoRetorno->pfiNumeroLiquidacao         = $iCodigoLiquidacao;
      $oPagamentoRetorno->pfiDataDocumento            = $oPagamento->c75_data;
      $oPagamentoRetorno->pfiValorPagamento           = number_format($oPagamento->c70_valor,2,".","");
      $oPagamentoRetorno->pfiSinalPagamento           = $oPagamento->sinal;
      $oPagamentoRetorno->pfiNumDocumento             = $iNumeroDocumento;
      $oPagamentoRetorno->pfiTipoDocumento            = $iFormaPagamento;
      $iContaPagadora    = $oPagamento->conta_pagadora;
      // estrutural da conta a debito
      $sSqlContaPagadora  = "select c60_estrut "; 
      $sSqlContaPagadora .= "  from conplanoreduz ";
      $sSqlContaPagadora .= "       inner join conplano on c60_codcon = c61_codcon and c60_anousu = c61_anousu ";
      $sSqlContaPagadora .= "  where c61_reduz = ".$oPagamento->conta_pagadora;
      $rsContaPagadora     = db_query($sSqlContaPagadora);
      if (pg_num_rows($rsContaPagadora) > 0) { 
       $iContaPagadora  = str_pad(pg_result($rsContaPagadora,0,"c60_estrut"), 20,'0', STR_PAD_RIGHT);
      }
      $iContaCredito = $iContaPagadora;
      $oPagamentoRetorno->pfiCodigoContaBalancete = $iContaCredito;
      array_push($this->aDados, $oPagamentoRetorno);
      
    }
    $this->addLog("\n".str_repeat("-", 30)."fim log PagamentoFinanceiro".str_repeat("-", 30)."\n");
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
                        "pfiCodigoEntidade",
                        "pfiMesAnoMovimento",
                        "pfiCodigoContaBalancete",
                        "pfiEmpenhoNumero",
                        "pfiNumeroLiquidacao",
                        "pfiNumeroPagamento",
                        "pfiSinalPagamento",
                        "pfiNumDocumento",
                        "pfiDataDocumento",
                        "pfiTipoDocumento",
                        "pfiValorPagamento"             
                       );
    return $aElementos;  
  }
  
}

?>