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
 * Prove dados para a geração do arquivo de comprovante de liquidacao no periodo 
 * Prove dados para a geração do arquivo dos dados da despesa do comprovante liquidacao no periodo
 * do municipio para o SIGAP
 * @package Pad
 * @author  Luiz Marcelo Schmitt
 * @version $Revision: 1.7 $
 */
final class PadArquivoSigapComprovanteLiquidacao extends PadArquivoSigap {
  
  /**
   * Metodo construtor da classe
   */
  public function __construct() {
    
    $this->sNomeArquivo = "ComprovanteLiquidacao";
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
    $oInstituicao  = db_stdClass::getDadosInstit(db_getsession("DB_instit"));
    $sListaInstit  = db_getsession("DB_instit");

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
    $sSqlLiquidacoes .= "        e60_instit,";
    $sSqlLiquidacoes .= "       e63_codhist, ";
    $sSqlLiquidacoes .= "       e69_numero, ";
    $sSqlLiquidacoes .= "       e69_dtnota ";
    $sSqlLiquidacoes .= "   from conlancamemp";
    $sSqlLiquidacoes .= "        inner join conlancam    on c70_codlan = c75_codlan";
    $sSqlLiquidacoes .= "        inner join empempenho   on e60_numemp = c75_numemp";
    $sSqlLiquidacoes .= "        left  join empemphist   on e60_numemp = e63_numemp";
    $sSqlLiquidacoes .= "        inner join cgm          on e60_numcgm = z01_numcgm";
    $sSqlLiquidacoes .= "        inner join conlancamdoc  on c71_codlan = c75_codlan";
    $sSqlLiquidacoes .= "        inner join conlancamnota on c66_codlan = c75_codlan";
    $sSqlLiquidacoes .= "        inner join empnota       on c66_codnota = e69_codnota";
    $sSqlLiquidacoes .= "        inner join empnotaele    on e69_codnota = e70_codnota";
    $sSqlLiquidacoes .= "        inner join conhistdoc   on c53_coddoc   = c71_coddoc ";
    $sSqlLiquidacoes .= "                               and (c53_tipo in(20, 21)) ";        
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
    $sSqlLiquidacoes .= "       e60_instit ,";
    $sSqlLiquidacoes .= "       e63_codhist, ";
    $sSqlLiquidacoes .= "       e69_numero, "; 
    $sSqlLiquidacoes .= "       e69_dtnota "; 
    $sSqlLiquidacoes .= "  from empresto  ";
    $sSqlLiquidacoes .= "       inner join conlancamemp on e91_numemp = c75_numemp ";
    $sSqlLiquidacoes .= "       inner join conlancam    on c70_codlan = c75_codlan ";
    $sSqlLiquidacoes .= "       inner join empempenho   on e60_numemp = c75_numemp ";
    $sSqlLiquidacoes .= "       left  join empemphist   on e60_numemp = e63_numemp";
    $sSqlLiquidacoes .= "       inner join cgm          on e60_numcgm = z01_numcgm ";
    $sSqlLiquidacoes .= "       inner join conlancamdoc on c71_codlan = c75_codlan ";
    $sSqlLiquidacoes .= "       inner join conlancamnota on c66_codlan = c75_codlan";
    $sSqlLiquidacoes .= "       inner join empnota       on c66_codnota = e69_codnota";
    $sSqlLiquidacoes .= "       inner join empnotaele    on e69_codnota = e70_codnota";
    $sSqlLiquidacoes .= "       inner join conhistdoc   on c53_coddoc = c71_coddoc ";
    $sSqlLiquidacoes .= "                              and (c53_tipo in(20, 21) or c53_coddoc = 31) ";      
    //$sSqlLiquidacoes .= "  where (c75_data between '".db_getsession("DB_anousu")."-01-31' and '{$this->sDataFinal}' ";
    $sSqlLiquidacoes .= "  where (c75_data <= '{$this->sDataFinal}' ";
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
    $sSqlLiquidacoes .= "     order by c75_data";
    $rsLiquidacoes           = db_query($sSqlLiquidacoes);
    $iTotalLinhasLiquidacoes = pg_num_rows($rsLiquidacoes);
    for ($i = 0; $i < $iTotalLinhasLiquidacoes; $i++) {
      
      $sDiaMesAno      =  "{$iAno}-".str_pad($iMes, 2, "0", STR_PAD_LEFT)."-".str_pad($iDia, 2, "0", STR_PAD_LEFT);
      $oLiquidacao     = db_utils::fieldsMemory($rsLiquidacoes, $i);
      $sNumeroEmpenho  = str_pad($oLiquidacao->e60_codemp, 5, "0", STR_PAD_LEFT);
      if (empty($oLiquidacao->e63_emphist)) {
        $oLiquidacao->e63_emphist = 1;
      }
      
      $sModeloNotaFiscal = '';
      $sResponsavelNota  = '';
      if ($oLiquidacao->e63_emphist == 1) {
        
        $sModeloNotaFiscal = 'e-notafiscal';
        $sResponsavelNota  = $oInstituicao->pref;  
      }
      $oLiquidacaoRetorno                               = new stdClass();
      $oLiquidacaoRetorno->cliCodigoEntidade            = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT);
      $oLiquidacaoRetorno->cliMesAnoMovimento           = $sDiaMesAno;
      
      $oLiquidacaoRetorno->cliEmpenhoNumero             = $oLiquidacao->ano.$sNumeroEmpenho;
      $oLiquidacaoRetorno->cliNumeroLiquidacao          = $oLiquidacao->c75_codlan;
      $oLiquidacaoRetorno->cliDataLiquidacao            = $oLiquidacao->c75_data;
      $oLiquidacaoRetorno->cliValorComprovante          = number_format($oLiquidacao->c70_valor,2,".","");
      $oLiquidacaoRetorno->cliNumDocumento              = $oLiquidacao->e69_numero;
      $oLiquidacaoRetorno->cliDataDocumento             = $oLiquidacao->e69_dtnota;
      $oLiquidacaoRetorno->cliTipoDocumento             = $oLiquidacao->e63_emphist;
      $oLiquidacaoRetorno->cliModeloNF                  = $sModeloNotaFiscal;
      $oLiquidacaoRetorno->cliAutorizacaoNotaFiscal     = $sResponsavelNota;
      $oLiquidacaoRetorno->cliSinal                     = $oLiquidacao->sinal;
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
                         "cliCodigoEntidade",
                         "cliMesAnoMovimento",
                         "cliEmpenhoNumero",
                         "cliNumeroLiquidacao",
                         "cliNumDocumento",
                         "cliDataDocumento",
                         "cliTipoDocumento",
                         "cliModeloNF",
                         "cliAutorizacaoNotaFiscal",
                         "cliSinal",
                         "cliValorComprovante"
                       );
    return $aElementos;  
  }
  
  private function corrigeValor($valor, $quant) {
  	
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