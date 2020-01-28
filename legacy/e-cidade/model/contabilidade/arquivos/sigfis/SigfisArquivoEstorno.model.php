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
 * Classe que processa as informaчѕes para serem inseridas no
 * arquivo EstorEmp.txt
 * @author vinicius.silva@dbseller.com.br
 * @package contabilidade
 * @subpackage sigfis
 */

class SigfisArquivoEstorno extends SigfisArquivoBase implements iPadArquivoTXTBase {
	
	protected $iCodigoLayout = 127;
	protected $sNomeArquivo  = 'EstorEmp';
	
	public function gerarDados() {
		
		$oDaoEmpempenho = db_utils::getDao('empempenho');
		$iAnoSessao = db_getsession('DB_anousu');
		$iInstituicaoSessao = db_getsession('DB_instit');
		
		$sCampos  = " db_config.codtrib,      ";
    $sCampos .= " orcdotacao.o58_orgao,   ";
    $sCampos .= " orcdotacao.o58_unidade, ";
    $sCampos .= " empempenho.e60_codemp,  ";
    $sCampos .= " empempenho.e60_anousu,  ";
    $sCampos .= " empanulado.e94_codanu,  ";
    $sCampos .= " empanulado.e94_data,    ";
    $sCampos .= " empanulado.e94_motivo,  ";
    $sCampos .= " empanulado.e94_valor,   ";
    $sCampos .= " orcdotacao.o58_orgao    ";

    
		$sCampos  = " db_config.codtrib,      ";
    $sCampos .= " orcdotacao.o58_orgao,   ";
    $sCampos .= " orcdotacao.o58_unidade, ";
    $sCampos .= " empempenho.e60_codemp,  ";
    $sCampos .= " empempenho.e60_anousu,  ";
    $sCampos .= " conlancam.c70_codlan as e94_codanu,  ";
    $sCampos .= " conlancam.c70_data as e94_data,    ";
    $sCampos .= " 'Anulacao de Empenhos do exercicio' as e94_motivo,  ";
    $sCampos .= " conlancam.c70_valor as e94_valor,   ";
    $sCampos .= " orcdotacao.o58_orgao    ";

  

    $sWhereBuscaEstornos  = "     empempenho.e60_anousu = {$iAnoSessao}                                           ";
    $sWhereBuscaEstornos .= " and empanulado.e94_data between '{$this->dtDataInicial}' and '{$this->dtDataFinal}' ";
    $sWhereBuscaEstornos .= " and empempenho.e60_instit = {$iInstituicaoSessao}                                   "; 
    
    
    //$sSqlBuscaEstornos = $oDaoEmpempenho->sql_query_buscaestornos(null, $sCampos, null, $sWhereBuscaEstornos);
    
    $sSqlBuscaEstornos = " select $sCampos 
                           from conlancam 
                                inner join conlancamdoc on c70_codlan = c71_codlan
                                inner join conlancamemp on c70_codlan = c75_codlan
                                inner join empempenho on c75_numemp = e60_numemp
                                inner join db_config on codigo = e60_instit
                                inner join orcdotacao on o58_anousu = e60_anousu
                                                     and o58_coddot = e60_coddot
                           where c70_data between  '{$this->dtDataInicial}' and '{$this->dtDataFinal}'
                             and c71_coddoc in (2)
                             and empempenho.e60_instit = {$iInstituicaoSessao} 
                         ";

    $rsBuscaEstornos   = $oDaoEmpempenho->sql_record($sSqlBuscaEstornos);
    
    $aEstornos         = db_utils::getColectionByRecord($rsBuscaEstornos);
    
    if (count($aEstornos) > 0) {
    	
      if (empty($this->sCodigoTribunal)) {
        throw new Exception("O cѓdigo do tribunal deve ser informado para geraчуo do arquivo");
      }
      
    	foreach ($aEstornos as $oEstorno) {
    		
    		/**
         * forчando o decimal nos casos onde o valor do estorno vem inteiro
         */
        $fValorDecimal      = db_formatar($oEstorno->e94_valor, 'p');
        $iValorSemSeparador = str_replace('.', '', $fValorDecimal);
        
        /**
         * extraindo o ano e o mъs da data do estorno 
         */
        $aData = explode('-', $oEstorno->e94_data);
        $iAnoMes = $aData[0].$aData[1];
        
        /**
         * Manipulmos o campo o54_descr eliminando quebras de linha
         */
        $sMotivoestorno = utf8_decode(str_replace(array('\n', '\r'), ' ', substr($oEstorno->e94_motivo, 0, 120)));
    		
    	  $oDadosLinha = new stdClass();
    	  $oDadosLinha->cd_Unidade              = str_pad($this->sCodigoTribunal, 4, ' ', STR_PAD_LEFT);
    	  $oDadosLinha->cd_UnidadeOrcamentaria  = str_pad($oEstorno->o58_unidade, 4, ' ', STR_PAD_LEFT);
    	  $oDadosLinha->nu_Empenho              = str_pad($oEstorno->e60_codemp, 10, ' ', STR_PAD_RIGHT);
    	  $oDadosLinha->dt_Ano                  = $oEstorno->e60_anousu;
    	  $oDadosLinha->nu_Estorno              = str_pad($oEstorno->e94_codanu,  6, ' ', STR_PAD_LEFT);
    	  $oDadosLinha->dt_Estorno              = str_replace('/', '', db_formatar($oEstorno->e94_data,"d"));
    	  $oDadosLinha->de_MotivoEstorno        = str_pad($sMotivoestorno,      120, ' ', STR_PAD_RIGHT);
    	  $oDadosLinha->vl_Estorno              = str_pad($iValorSemSeparador,   16, ' ', STR_PAD_LEFT);
    	  $oDadosLinha->st_DespesaLiquidada     = 2;
    	  $oDadosLinha->dt_AnoMes               = $iAnoMes;
    	  $oDadosLinha->cd_Orgao                = str_pad($oEstorno->o58_orgao,   4, ' ', STR_PAD_LEFT);
    	  $oDadosLinha->Reservado_tce           = str_pad('0',                   10, ' ', STR_PAD_RIGHT);
    	  $oDadosLinha->codigolinha             = 414;
    	  $this->aDados[]                       = $oDadosLinha; 
    	}
    } 
    
    return $this->aDados;
	}
}
?>