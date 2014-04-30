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
 * @version $Revision: 1.7 $
 */
final class PadArquivoSigapPagamento extends PadArquivoSigap {
  
  /**
   * 
   */
  public function __construct() {
    
    $this->sNomeArquivo = "Pagamento";
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
    $sSqlPagamentos .= "       case when c69_debito = c82_reduz then ";
    $sSqlPagamentos .= "       c69_credito  else c69_debito ";
    $sSqlPagamentos .= "       end as contrapartida,    ";
    $sSqlPagamentos .= "       db_config.codtrib as orgao_unidade, ";
    $sSqlPagamentos .= "       e60_instit ";
    $sSqlPagamentos .= "  from conlancamemp    ";
    $sSqlPagamentos .= "       inner join conlancam      on c70_codlan = c75_codlan ";
    $sSqlPagamentos .= "       inner join conlancampag   on c82_codlan = c70_codlan ";
    $sSqlPagamentos .= "       inner join conlancamval   on c69_codlan = c70_codlan ";
    $sSqlPagamentos .= "                                and  ( c69_debito =  c82_reduz or c69_credito = c82_reduz) ";
    $sSqlPagamentos .= "       inner join empempenho     on e60_numemp    = c75_numemp ";
    $sSqlPagamentos .= "       inner join db_config      on db_config.codigo = empempenho.e60_instit ";
    $sSqlPagamentos .= "       inner join conlancamdoc   on c71_codlan = c75_codlan ";
    $sSqlPagamentos .= "       inner join conhistdoc     on c53_coddoc = c71_coddoc ";
    $sSqlPagamentos .= "                                and (c53_tipo  = 30 or c53_tipo = 31)  ";
    $sSqlPagamentos .= "       left  join conlancamord   on c80_codlan = c70_codlan ";
    $sSqlPagamentos .= "       left  join conlancamcompl on c72_codlan = c70_codlan ";
    $sSqlPagamentos .= " where c75_data between '{$this->sDataInicial}' and '{$this->sDataFinal}' ";
    $sSqlPagamentos .= "   and e60_emiss <= '{$this->sDataFinal}' {$sWhere} ";     
    $sSqlPagamentos .= "   and e60_anousu = ".db_getsession("DB_anousu");     
    $sSqlPagamentos .= " union all ";
    
    $sSqlPagamentos  .= "select e60_anousu as ano, ";
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
    $sSqlPagamentos .= "       case when c69_debito = c82_reduz then ";
    $sSqlPagamentos .= "          c69_credito  else c69_debito ";
    $sSqlPagamentos .= "       end as contra_partida,    ";
    $sSqlPagamentos .= "       db_config.codtrib as orgao_unidade, ";
    $sSqlPagamentos .= "       e60_instit ";
    $sSqlPagamentos .= "  from empresto ";
    $sSqlPagamentos .= "       inner join conlancamemp on c75_numemp = e91_numemp ";
    $sSqlPagamentos .= "       inner join conlancam    on c70_codlan = c75_codlan ";
    $sSqlPagamentos .= "       inner join conlancampag on c82_codlan = c70_codlan ";
    $sSqlPagamentos .= "       inner join conlancamval on c69_codlan = c70_codlan ";
    $sSqlPagamentos .= "                              and  ( c69_debito   =  c82_reduz or c69_credito = c82_reduz) ";
    $sSqlPagamentos .= "       inner join empempenho on e60_numemp = c75_numemp ";
    $sSqlPagamentos .= "       inner join db_config on db_config.codigo = empempenho.e60_instit ";
    $sSqlPagamentos .= "       inner join conlancamdoc on c71_codlan = c75_codlan ";
    $sSqlPagamentos .= "       inner join conhistdoc on c53_coddoc = c71_coddoc ";
    $sSqlPagamentos .= "                            and (c53_tipo = 30 or c53_tipo = 31)  ";
    $sSqlPagamentos .= "       left outer join conlancamord on c80_codlan = c70_codlan ";
    $sSqlPagamentos .= "       left outer join conlancamcompl on c72_codlan = c70_codlan ";
    $sSqlPagamentos .= " where e91_anousu = ".db_getsession("DB_anousu");
    $sSqlPagamentos .= "   and e91_rpcorreto is true ";
    $sSqlPagamentos .= "   and c75_data between '{$this->sDataInicial}' and '{$this->sDataFinal}' ";
    $sSqlPagamentos .= "   and e60_emiss <= '{$this->sDataFinal}' {$sWhere} ";             
    $sSqlPagamentos .= " order by ano,e60_codemp ";
    $rsPagamentos    = db_query($sSqlPagamentos);
    $iTotalLinhas = pg_num_rows($rsPagamentos);
    $iTotal=0; 
    $this->addLog("\n".str_repeat("-", 30)."Pagamento".str_repeat("-", 30)."\n");
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
      $sSqlVerificaLiquidacao .= "  and  c70_data between '{$this->sDataInicial}' and '{$oPagamento->c75_data}'";
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
      
      $oPagamentoRetorno                              = new stdClass();
      $oPagamentoRetorno->pagCodigoEntidade           = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT);
      $sDiaMesAno                                     = "{$iAno}-".str_pad($iMes, 2, "0", STR_PAD_LEFT)."-".str_pad($iDia, 2, "0", STR_PAD_LEFT);
      $oPagamentoRetorno->pagMesAnoMovimento          = $sDiaMesAno;
      $sNumeroEmpenho                                 = str_pad($oPagamento->e60_codemp, 5, "0", STR_PAD_LEFT);
      $oPagamentoRetorno->pagEmpenhoNumero            = $oPagamento->ano.$sNumeroEmpenho;
      $oPagamentoRetorno->pagNumeroPagamento          = $oPagamento->c75_codlan;
      $oPagamentoRetorno->pagNumeroLiquidacao         = $iCodigoLiquidacao;
      $oPagamentoRetorno->pagDataPagamento            = $oPagamento->c75_data;
      $oPagamentoRetorno->pagValorPagamento           = number_format($oPagamento->c70_valor,2,".","");
      $oPagamentoRetorno->pagSinalPagamento           = $oPagamento->sinal;
      if ($oPagamento->historico == "") {
        $historico = "Lançamento de Pagamento Número: {$oPagamento->historico} ";
      }
      
      $oPagamentoRetorno->pagHistoricoPagamento       = substr($oPagamento->historico, 0, 255);
      $oPagamentoRetorno->pagCodigoOperacao           = str_pad($oPagamento->c75_codlan, 30,0,STR_PAD_LEFT);
      $iContaPagadora                                 = $oPagamento->conta_pagadora;
      
      // estrutural da conta a debito
      $sSqlContaPagadora  = "select c60_estrut "; 
      $sSqlContaPagadora .= "  from conplanoreduz ";
      $sSqlContaPagadora .= "       inner join conplano on c60_codcon = c61_codcon and c60_anousu = c61_anousu ";
      $sSqlContaPagadora .= "  where c61_reduz = ".$oPagamento->conta_pagadora;
      $rsContaPagadora    = db_query($sSqlContaPagadora);
      if (pg_num_rows($rsContaPagadora) > 0) { 
        $iContaPagadora  = str_pad(pg_result($rsContaPagadora,0,"c60_estrut"), 20,'0', STR_PAD_RIGHT);
      }
      
      $iContraPartida     = $oPagamento->contrapartida;
      $sSqlContraPartida  = "select c60_estrut "; 
      $sSqlContraPartida .= "  from conplanoreduz ";
      $sSqlContraPartida .= "       inner join conplano on c60_codcon = c61_codcon and c60_anousu = c61_anousu ";
      $sSqlContraPartida .= "  where c61_reduz = ".$oPagamento->contrapartida;
      $rsContraPartida    = db_query($sSqlContraPartida);
      if (pg_num_rows($rsContraPartida) > 0) { 
        $iContraPartida  = str_pad(pg_result($rsContraPartida,0,"c60_estrut"), 20,'0', STR_PAD_RIGHT);
      }
      
      $iContaDebito  = $iContraPartida;
      $iContaCredito = $iContaPagadora;
      
      $oPagamentoRetorno->pagDebitoCodigoContaBalancete             = $iContaDebito;                                                                              
      $oPagamentoRetorno->pagCreditoCodigoContaBalanceteVerificacao = $iContaCredito;   
      
      $iTamanhoCampo = strlen($oPagamento->orgao_unidade);
      if ($iTamanhoCampo != 4) {
      	
      	$sMsg  = "Identificação do Orgão/Unidade da instituição ({$oPagamento->orgao_unidade}) está incorreto. \\n ";
      	$sMsg .= "Solicitar para o setor responsável pela configuração do sistema a alteração da informação no Menu: \\n \\n "; 
      	$sMsg .= "Configuração -> Cadastros -> Instituições -> Alteração. ";
      	
      	throw new Exception($sMsg);
      }
      
      $sOrgao                                                       = substr($oPagamento->orgao_unidade, 0, 2);
      $sUnidade                                                     = substr($oPagamento->orgao_unidade, 2, 2);
      $oPagamentoRetorno->pagDebitoCodigoOrgao                      = str_pad($sOrgao, 2, "0", STR_PAD_LEFT);
      $oPagamentoRetorno->pagDebitoCodigoUnidadeOrcamentaria        = str_pad($sUnidade, 2, "0", STR_PAD_LEFT);
      $oPagamentoRetorno->pagCreditoOrgao                           = str_pad($sOrgao, 2, "0", STR_PAD_LEFT);
      $oPagamentoRetorno->pagCreditoOrgaoUnidadeOrcamentaria        = str_pad($sUnidade, 2, "0", STR_PAD_LEFT);    
      $oPagamentoRetorno->pagProcesso                               = $oPagamento->e60_numemp;
      
      array_push($this->aDados, $oPagamentoRetorno);
    }
    
    $this->addLog("\n".str_repeat("-", 30)."fim log Pagamento".str_repeat("-", 30)."\n");
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
                         "pagCodigoEntidade",
                         "pagMesAnoMovimento",
                         "pagEmpenhoNumero",
                         "pagNumeroPagamento",
                         "pagNumeroLiquidacao",
                         "pagDataPagamento",
                         "pagValorPagamento",
                         "pagSinalPagamento",
                         "pagHistoricoPagamento",
                         "pagCodigoOperacao",
                         "pagDebitoCodigoContaBalancete",
                         "pagCreditoCodigoContaBalanceteVerificacao",
                         "pagDebitoCodigoOrgao",
                         "pagDebitoCodigoUnidadeOrcamentaria",
                         "pagCreditoOrgao",
                         "pagCreditoOrgaoUnidadeOrcamentaria",  
                         "pagProcesso"
                       );
                       
    return $aElementos;  
  }
}
?>