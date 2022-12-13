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
 * Classe que processa as informações para serem inseridas no
 * arquivo LiqEmp.txt
 * @author vinicius.silva@dbseller.com.br
 * @package contabilidade
 * @subpackage sigfis
 */

class SigfisArquivoLiquidacao extends SigfisArquivoBase implements iPadArquivoTXTBase {
	
	protected $iCodigoLayout = 128;
	protected $sNomeArquivo  = 'LiqEmp';
	
	public function gerarDados() {
		
		$oDaoEmpempenho     = db_utils::getDao('empempenho');
    $iAnoSessao         = db_getsession('DB_anousu');
    $iInstituicaoSessao = db_getsession('DB_instit');

    $sOrdem  .= " empempenho.e60_codemp,                                           ";
    $sOrdem  .= " conlancam.c70_data,                                              ";
    $sOrdem  .= " empempenho.e60_anousu                                           ";
    
    $sCampos  = " orcdotacao.o58_orgao,                                            ";
    $sCampos .= " orcdotacao.o58_unidade,                                          ";
    $sCampos .= " empempenho.e60_codemp,                                           ";
    $sCampos .= " db_config.codtrib,                                               ";
    $sCampos .= " conlancam.c70_data,                                              ";
    $sCampos .= " sum(case when c53_tipo = 20 
                           then conlancam.c70_valor 
                           else conlancam.c70_valor*(-1) 
                  end) as c70_valor,                                             ";
    $sCampos .= " empempenho.e60_anousu,                                           ";
    $sCampos .= " orcdotacao.o58_orgao                                             ";
    $sWhereBuscaLiquidacoes  = "     e60_anousu = {$iAnoSessao}                                           ";
    $sWhereBuscaLiquidacoes .= " and c53_tipo   in (20, 21)                                               ";
    $sWhereBuscaLiquidacoes .= " and c70_data between '{$this->dtDataInicial}' and '{$this->dtDataFinal}' ";
    $sWhereBuscaLiquidacoes .= " and e60_instit = {$iInstituicaoSessao}                                   ";
    $sWhereBuscaLiquidacoes .= " group by orcdotacao.o58_orgao, orcdotacao.o58_unidade, 
                                          empempenho.e60_codemp, 
                                          db_config.codtrib, 
                                          conlancam.c70_data,
                                          empempenho.e60_anousu,
                                          orcdotacao.o58_orgao";
    $sSqlBuscaLiquidacoes   = $oDaoEmpempenho->sql_query_buscaliquidacoes(null, $sCampos, 
                                                                          $sOrdem, $sWhereBuscaLiquidacoes);
    $rsBuscaLiquidacoes     = $oDaoEmpempenho->sql_record($sSqlBuscaLiquidacoes);

    if ($oDaoEmpempenho->numrows > 0) {
    	
      if (empty($this->sCodigoTribunal)) {
        throw new Exception("O código do tribunal deve ser informado para geração do arquivo");
      }
      
    	for ($iLiquidacao = 0; $iLiquidacao < $oDaoEmpempenho->numrows; $iLiquidacao++) {
    		
    		$oLiquidacao = db_utils::fieldsMemory($rsBuscaLiquidacoes, $iLiquidacao);
    		
    		/**
         * forçando o decimal nos casos onde o valor do estorno vem inteiro
         */

        //if($oLiquidacao->c70_valor > 0 ) {
        //  continue;
       // }

        if($oLiquidacao->c70_valor == 0 ) {
          continue;
        }

        $fValorDecimal      = db_formatar($oLiquidacao->c70_valor, 'p');
        $iValorSemSeparador = str_replace('.', '', $fValorDecimal);
        
        /**
         * extraindo o ano e o mês da data do estorno e transformando a data para o formato correto 
         */
        $aData   = explode('-', $oLiquidacao->c70_data);
        $sData   = $aData[2].$aData[1].$aData[0];
        $sAnoMes = $aData[0].$aData[1];
        
    		$oDadosLinha = new stdClass();
//    		$oDadosLinha->cd_UnidadeOrcamentaria  = str_pad($oLiquidacao->o58_orgao,     2, '0', STR_PAD_LEFT);
    		$oDadosLinha->cd_UnidadeOrcamentaria  = str_pad($oLiquidacao->o58_unidade,   4, ' ', STR_PAD_LEFT);
    		$oDadosLinha->nu_Empenho              = str_pad($oLiquidacao->e60_codemp,   10, ' ', STR_PAD_RIGHT);
    		$oDadosLinha->cd_Unidade              = str_pad($this->sCodigoTribunal,      4, ' ', STR_PAD_LEFT);
    		$oDadosLinha->dt_Liquidacao           = $sData;
    		$oDadosLinha->vl_Liquidacao           = str_pad($iValorSemSeparador,        16, ' ', STR_PAD_LEFT);
    		$oDadosLinha->dt_Ano                  = str_pad($oLiquidacao->e60_anousu,    4, ' ', STR_PAD_LEFT);
    		$oDadosLinha->dt_AnoMes               = $sAnoMes;
    		$oDadosLinha->cd_Orgao                = str_pad($oLiquidacao->o58_orgao,     4, ' ', STR_PAD_LEFT);
    		$oDadosLinha->Reservado_tce           = str_pad('0',                        10, ' ', STR_PAD_RIGHT);
    		$oDadosLinha->codigolinha             = 415;
    		$this->aDados[]                       = $oDadosLinha;
    		unset($oLiquidacao);
    	}
    	
    } 
    
    return $this->aDados;
	}
}
?>