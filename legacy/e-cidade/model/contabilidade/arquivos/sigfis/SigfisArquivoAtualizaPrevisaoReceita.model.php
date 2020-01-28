<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
 * Classe que processa as informaчѕes para serem inseridas no
 * arquivo APrevRec.txt
 * @author vinicius.silva@dbseller.com.br
 * @package contabilidade
 * @subpackage sigfis
 */
class SigfisArquivoAtualizaPrevisaoReceita extends SigfisArquivoBase implements iPadArquivoTXTBase {
	
	protected $iCodigoLayout = 123;
	protected $sNomeArquivo  = 'APrevRec';
	
	public function gerarDados() {
		
		$oDaoOrcreceita     = db_utils::getDao('orcreceita');
		$iAnoSessao         = db_getsession('DB_anousu');
		$iInstituicaoSessao = db_getsession('DB_instit');
		
		/**
		 * Efetuamos a busca no banco de dados para retornar as atualizaчѕes de
		 * previsуo de receita
		 */
		$sCampos                  = " orcreceita.o70_anousu,                ";
		$sCampos                 .= " db_config.codtrib,                    ";
		$sCampos                 .= " orcfontes.o57_fonte,                  ";
		$sCampos                 .= " orcsuplemrec.o85_valor,               ";
		$sCampos                 .= " orcsuplemlan.o49_data                 ";
		$sWhereBuscaAtualizacoes  = "     orcreceita.o70_anousu = {$iAnoSessao}                                             ";
		$sWhereBuscaAtualizacoes .= " and orcsuplemlan.o49_data between '{$this->dtDataInicial}' and '{$this->dtDataFinal}' ";
		$sWhereBuscaAtualizacoes .= " and orcreceita.o70_instit = {$iInstituicaoSessao}                                     ";
		$sSqlBuscaAtualizacoes    = $oDaoOrcreceita->sql_query_atualizacoesprevisao(null, null, $sCampos, null, $sWhereBuscaAtualizacoes);
		$rsSqlBuscaAtualizacoes   = $oDaoOrcreceita->sql_record($sSqlBuscaAtualizacoes);
    $oAtualizacoes            = db_utils::getColectionByRecord($rsSqlBuscaAtualizacoes);
    
    if ($oAtualizacoes > 0) {
    	
      if (empty($this->sCodigoTribunal)) {
        throw new Exception("O cѓdigo do tribunal deve ser informado para geraчуo do arquivo");
      }
      
    	foreach ($oAtualizacoes as $oAtualizacao) {
    		
    		$oDaoOrcfontes = db_utils::getDao('orcfontes');
    		$sWhereFontes  = " o57_anousu = {$this->iAnoUso} ";
    		$sWhereFontes .= " and o57_fonte = '{$oAtualizacao->o57_fonte}'";
    		$sSqlOrcFontes = $oDaoOrcfontes->sql_query_file(null, null, "*", null, $sWhereFontes);
    		$rsOrcFontes   = $oDaoOrcfontes->sql_record($sSqlOrcFontes);
    		
    		/**
    		 * Testando os dados de vinculo de receita no arquivo XML de vinculaчуo
    		 */
    		$aReceitaSoma = array();
    		if ($oDaoOrcfontes->numrows == 1) {
    			
    			$iCodigoConta = db_utils::fieldsMemory($rsOrcFontes, 0)->o57_codfon;
    			if ($oVinculo = SigfisVinculoReceita::getVinculoReceita($iCodigoConta)) {
    				
    				if (!isset($aValores[$oVinculo->receitatce])) {
    					$aReceitaSoma[$oVinculo->receitatce] = $oAtualizacao->o85_valor;
    				} else {
    					$aReceitaSoma[$oVinculo->receitatce] += $oAtualizacao->o85_valor;
    				}
    			} else {
    				
    				$sErroLog  = "Receita {$oAtualizacao->o57_fonte} do ano de {$this->iAnoUso} ";
            $sErroLog .= "nуo tem vinculo com Receita Sigfis.\n";
            $this->addLog($sErroLog);
    			}
    		} else {
    			$sErroLog  = "Receita {$oAtualizacao->o57_fonte} do ano de {$this->iAnoUso} retornou mais de um registro.\n";
          $this->addLog($sErroLog);
    		}
    		
    		if (count($aReceitaSoma) > 0) {
    			
    			foreach ($aReceitaSoma as $sFonte => $nValor) {
		    		
		    		/**
		         * recuperando ano e mes
		         */
		        $aDadosData     = explode('-', $oAtualizacao->o49_data);
		        $sDataFormatada = $aDadosData[0].$aDadosData[1]; 
		    		
		    		$oDadosLinha = new stdClass();
		    		$oDadosLinha->dt_Ano           = $oAtualizacao->o70_anousu;
		    		$oDadosLinha->cd_Unidade       = str_pad($this->sCodigoTribunal,             4, ' ', STR_PAD_LEFT);
		    		$oDadosLinha->cd_ItemReceita   = $sFonte;
		    		$oDadosLinha->tp_Atual_Receita = 1;
		    		$oDadosLinha->vl_Receita       = str_pad(number_format($nValor, 2, '', ''), 16, ' ', STR_PAD_LEFT);
		    		$oDadosLinha->dt_AnoMes        = $sDataFormatada;
		    		$oDadosLinha->codigolinha      = 410;
		    		$this->aDados[]                = $oDadosLinha;
    			}
    		} 
    	}
    } 
    
    return $this->aDados;
	}
}
?>