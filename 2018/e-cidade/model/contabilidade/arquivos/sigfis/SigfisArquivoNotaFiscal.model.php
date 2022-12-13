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

require_once ("interfaces/iPadArquivoTxtBase.interface.php");
require_once ("model/contabilidade/arquivos/sigfis/SigfisArquivoBase.model.php");

/** 
 DIversos - 160078, 202939, 203563, 200638, 10852, 201001, 201979, 203021, 162394, 200711, 92713, 201995, 200335, 92809, 201489,
            161799, 202301, 210788, 201300, 200301, 161857,201668, 201640, 161795, 12435, 161850, 211961, 202895, 205391, 213061,
            201970, 161861, 210811, 201627, 200933, 11670, 200521, 201732, 201733, 162130 
 * Classe que processa as informações para serem inseridas no
 * arquivo NotaFisc.txt
 * @author vinicius.silva@dbseller.com.br
 * @package contabilidade
 * @subpackage sigfis
 */

class SigfisArquivoNotaFiscal extends SigfisArquivoBase implements iPadArquivoTXTBase {
	
	protected $iCodigoLayout = 131;
	protected $sNomeArquivo  = "NotaFisc";

  public function gerarDados() {
		$iInstituicaoSessao = db_getsession('DB_instit');

    $oDadoConfig    = db_stdClass::getDadosInstit();
    $clConLanCamEmp = db_utils::getDao('conlancamemp');

    $sCampos  = "(case length(cgm.z01_cgccpf) when 14 then 2 when 11 then 1 end ) as tipo_pessoa, ";
    $sCampos .= "pagordem.e50_obs, z01_cgccpf, z01_nome, e50_data,         ";
    $sCampos .= "empempenho.e60_codemp, empempenho.e60_anousu, conlancam.c70_data, orcdotacao.o58_orgao,           ";
    $sCampos .= "min(pagordem.e50_codord) as e50_codord, to_char(max(conlancam.c70_data),'YYYYmm') as competencia, orcdotacao.o58_unidade,   	         ";
    $sCampos .= "sum(case c53_tipo when 30 then conlancam.c70_valor  																	                         ";
    $sCampos .= "							 when 31 then (conlancam.c70_valor * -1) end) as valor_pago                         ";
    
    $sWhere   = " conlancam.c70_anousu = {$this->iAnoUso} and empempenho.e60_instit = {$iInstituicaoSessao}                    ";
    $sWhere  .= " and empempenho.e60_anousu = {$this->iAnoUso}                                                                 ";
    $sWhere  .= " and conhistdoc.c53_tipo in (30,31)                                                                           ";
    $sWhere  .= " and z01_numcgm not in (160078, 202939, 203563, 200638, 10852, 201001, 201979, 203021, 162394, 200711, 92713, 201995, ";
    $sWhere  .= " 200335, 92809, 201489, 161799, 202301, 210788, 201300, 200301, 161857,201668, 201640, 161795, 12435, 161850,     ";
    $sWhere  .= " 211961, 202895, 205391, 213061, 201970, 161861, 210811, 201627, 200933, 11670, 200521, 201732, 201733, 162130)";
    $sWhere  .= " and conlancam.c70_data between cast('{$this->dtDataInicial}' as date) and cast('{$this->dtDataFinal}' as date) ";
    $sWhere  .= " group by empempenho.e60_codemp, z01_numcgm, z01_cgccpf, z01_nome, e50_data, pagordem.e50_obs, empempenho.e60_anousu, c70_data, orcdotacao.o58_orgao, ";
    $sWhere  .= " orcdotacao.o58_unidade";

    //$sWhere  .= " having max(conlancam.c70_data) between cast('{$this->dtDataInicial}' as date) and cast('{$this->dtDataFinal}' as date) ";
    
    $sOrdem   = " e60_codemp";
    
    $sSqlConLanCamEmp = $clConLanCamEmp->sql_query_pagamentoEmpenho(null , $sCampos, $sOrdem, $sWhere);

    $sSqlNota = "select c70_data, o58_unidade, e60_codemp, z01_cgccpf, z01_nome, tipo_pessoa, o58_orgao, 
                        sum(valor_pago) as valor_pago, max(e69_dtnota) as e69_dtnota, max(e11_seriefiscal) as e11_seriefiscal, 
                        max(e50_obs) as e50_obs, sum(e70_valor) as e70_valor, max(e50_data) as e50_data,max(e69_numero) as e69_numero
                 from ($sSqlConLanCamEmp) as x 
                              inner join pagordemnota        on e50_codord     = e71_codord
                              inner join empnota             on e69_codnota    = e71_codnota 
                              inner join empnotaele          on e70_codnota    = e69_codnota
                              left join empnotadadospitnotas on e13_empnota    = e69_codnota
                              left join empnotadadospit      on e11_sequencial = e13_empnotadadospit
                 group by c70_data, o58_unidade, e60_codemp, z01_cgccpf, z01_nome, tipo_pessoa, o58_orgao
                 order by e60_codemp
                 ";
// die($sSqlNota);

    // = $clConLanCamEmp->sql_query_pagamentoEmpenho(null , $sCampos, $sOrdem, $sWhere);
    $rsConLanCamEmp    = $clConLanCamEmp->sql_record($sSqlNota);
    
    $this->addLog("=====Arquivo".$this->getNomeArquivo()." Erros:\n");
    
    if ($clConLanCamEmp->numrows > 0) {
      
      if (empty($this->sCodigoTribunal)) {
        throw new Exception("O código do tribunal deve ser informado para geração do arquivo");
      }
      
      for ($i = 0; $i < $clConLanCamEmp->numrows; $i++) {
        
        $oDadosQuery = new stdClass();
        $oDadosQuery = db_utils::fieldsMemory($rsConLanCamEmp, $i);
        
        if ($oDadosQuery->valor_pago == 0 ){
           continue;
        }

        /**
         * Verifica se a Conta retornada possui vinculo com a conta do Sigfis
         */
//        if ($oVinculo = SigfisVinculoConta::getVinculoConta($oDadosQuery->c61_codcon)) {
          
          $sObServacao = str_replace("\n", " ", $oDadosQuery->e50_obs);
          $sObServacao = str_replace("\r", " ", $sObServacao);
    		
          $aData = explode('-', $oDadosQuery->c70_data);
    		  $iDataPagamentoNota = $aData[2].$aData[1].$aData[0];
    		  $iAnoPagamentoNota = $aData[0];
    		
          $aDataNota = explode('-', $oDadosQuery->e69_dtnota);
    		  $iDataNota = $aDataNota[2].$aDataNota[1].$aDataNota[0];
    		  $iAnoMesNota = $aDataNota[0].$aDataNota[1];
    		
          $fValorDecimal = db_formatar($oDadosQuery->e70_valor, 'p');
    	    $iValorSemSeparador = str_replace('.', '', $fValorDecimal);
    	  
          $sObjetoNota = utf8_decode(str_replace(array('\n', '\r'), ' ', $oDadosQuery->e50_obs));
          
          $oDados                = new stdClass();
//          $sUnidadeOrcamentaria  = str_pad($oDadosQuery->o58_orgao, 2, '0', STR_PAD_LEFT);
          $sUnidadeOrcamentaria = str_pad($oDadosQuery->o58_unidade,4, ' ', STR_PAD_LEFT);
          
          $dtPagamento           = $this->formataData($oDadosQuery->c70_data);
          $dtEmissao   = $this->formataData($oDadosQuery->e50_data);
          
          $oDados->cd_Unidade              = str_pad($this->sCodigoTribunal,    4, ' ', STR_PAD_LEFT);
          $oDados->cd_UnidadeOrcamentaria  = str_pad($sUnidadeOrcamentaria,     4, ' ', STR_PAD_LEFT); 
          $oDados->nu_Empenho              = str_pad($oDadosQuery->e60_codemp, 10, ' ', STR_PAD_RIGHT);
          $oDados->dt_PagamentoEmpenho     = $dtPagamento;
	        $oDados->nu_NotaFiscal           = str_pad(substr($oDadosQuery->e69_numero,0,10), 10, ' ', STR_PAD_RIGHT);
          $oDados->dt_Ano                  = $iAnoPagamentoNota;      
	        $oDados->nu_SerieNota            = str_pad($oDadosQuery->e11_seriefiscal,        3, ' ', STR_PAD_RIGHT);
	        $oDados->nu_SubSerieNota         = str_pad('',                             3, ' ', STR_PAD_RIGHT);
          $oDados->nu_CGCEmitente          = str_pad($oDadosQuery->z01_cgccpf, 14, ' ', STR_PAD_RIGHT);
          $oDados->nm_EmitenteNota         = str_pad(substr($oDadosQuery->z01_nome, 0, 30), 50, ' ', STR_PAD_RIGHT);
          $oDados->tp_EmitenteNota         = $oDadosQuery->tipo_pessoa;
	        $oDados->dt_NotaFiscal           = $iDataNota;
	        $oDados->vl_NotaFiscal           = str_pad($iValorSemSeparador,           16, ' ', STR_PAD_LEFT);
	        $oDados->Reservado_tce           = str_pad(' ',                           16, ' ', STR_PAD_RIGHT);
	        $oDados->de_ObjetoNota           = str_pad(substr( str_replace( "\n", "", $sObjetoNota ), 0, 120), 120, ' ', STR_PAD_RIGHT);
	        $oDados->dt_AnoMes               = $iAnoMesNota;
	        $oDados->cd_Orgao                = str_pad($oDadosQuery->o58_orgao,              4, ' ', STR_PAD_LEFT);
	        $oDados->nu_EmpenhoSup           = str_pad(' ',            10, ' ', STR_PAD_RIGHT);
	        $oDados->codigolinha             = 418;
  
          $this->aDados[] = $oDados;
/*
        } else {
          $sErroLog  = "Estrutural {$oDadosQuery->c60_estrut} - Conta{$oDadosQuery->e50_codord} -> ";
          $sErroLog .= "sem Vinculo com plano do SIGFIS - Conta *NÃO* Adicionada ao Arquivo.\n";
          $this->addLog($sErroLog);
        } */
      }
    }
    
    $this->addLog("===== Fim do Arquivo: ".$this->getNomeArquivo()."\n");
  }

}
?>