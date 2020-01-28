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

require_once ('SigfisArquivoBase.model.php');
require_once ('model/ppadespesa.model.php');

/**
 * Classe que processa as informações para serem inseridas no
 * arquivo ProgramaPPA.txt
 * @author vinicius.silva@dbseller.com.br
 * @package contabilidade
 * @subpackage sigfis
 */

class SigfisArquivoProgramaPpa extends SigfisArquivoBase implements iPadArquivoTXTBase {
	
  protected $iCodigoLayout = 110;
  protected $sNomeArquivo  = 'Programappa';
	
	public function gerarDados() {
		
		$oDaoPpaintegracao    = db_utils::getDao('ppaintegracao');
		$oDaoOrcprograma      = db_utils::getDao('orcprograma');
		
		$iAnoSessao           = db_getsession('DB_anousu');
    $iInstituicaoSessao   = db_getsession('DB_instit');
    $oDadosInstit         = db_stdClass::getDadosInstit();
    
    $sCampos               = "     ppaintegracao.*                                           ";
		$sWhereBuscaVersaoPpa  = "     ppaintegracao.o123_ano            = {$iAnoSessao}         ";
    $sWhereBuscaVersaoPpa .= " AND ppaintegracao.o123_situacao       = 1                     ";
    $sWhereBuscaVersaoPpa .= " AND ppaintegracao.o123_tipointegracao = 1                     ";
    $sWhereBuscaVersaoPpa .= " AND ppaintegracao.o123_instit         = {$iInstituicaoSessao} ";
    $sSqlBuscaVersaoPpa    = $oDaoPpaintegracao->sql_query_versaoppa(null, $sCampos, null, $sWhereBuscaVersaoPpa);
    $rsSqlBuscaVersaoPpa   = $oDaoPpaintegracao->sql_record($sSqlBuscaVersaoPpa);
    $oBuscaVersaoPpa       = db_utils::getColectionByRecord($rsSqlBuscaVersaoPpa);
    
    if (count($oBuscaVersaoPpa) > 0) {
    
      if (empty($this->sCodigoTribunal)) {
        throw new Exception("O código do tribunal deve ser informado para geração do arquivo");
      }
      
	    foreach ($oBuscaVersaoPpa as $oVersao) {
	    	
	    	$oPpaDespesa     = new ppaDespesa($oVersao->o123_ppaversao);
	    	$aProgramas      = $oPpaDespesa->getQuadroEstimativas(null, 5);
	    	
	    	foreach ($aProgramas as $oPrograma) {
	    		
	    		$sCampos              = " *,                                                   ";
          $sCampos             .= " (SELECT COUNT(*)                                     ";
          $sCampos             .= "    FROM orcprogramaunidade                           ";
          $sCampos             .= "   WHERE o14_orcprograma = 0                          ";
          $sCampos             .= "     AND o14_anousu = 2011) AS quantidade_unidades    ";
	    		$sWhereBuscaPrograma  = "     orcprograma.o54_anousu   = {$iAnoSessao}         ";
	    		$sWhereBuscaPrograma .= " AND orcprograma.o54_programa = {$oPrograma->iCodigo} ";
	    		$sSqlBuscaPrograma = $oDaoOrcprograma->sql_query_buscaprogramasigfis(null, null, $sCampos, 
	    		                                                                     null, $sWhereBuscaPrograma);
	    		$rsSqlBuscaPrograma = $oDaoOrcprograma->sql_record($sSqlBuscaPrograma);
	    		$oInfosPrograma = db_utils::fieldsMemory($rsSqlBuscaPrograma, 0);
	    		
	    		/**
	    		 * Avalia se as datas e a unidade gestora retornadas pela consulta são válidas. Se elas não forem, efetuamos
	    		 * uma nova busca para resgatar os valores corretos 
	    		 */
	    		if ($oInfosPrograma->o17_dataini != '') {
	    	  	$iAnoInicio         = date('Y', $oInfosPrograma->o17_dataini);
	    	  } else {
	    	  	
	    	  	$sSqlBuscaMenorAno   = " SELECT MIN(o54_anousu) AS o54_anousu                        ";
	    	  	$sSqlBuscaMenorAno  .= " FROM orcprograma WHERE o54_programa = {$oPrograma->iCodigo} ";
	    	  	$rsSqlBuscaMenorAno  = $oDaoOrcprograma->sql_record($sSqlBuscaMenorAno); 
	    	  	$oMenorAno           = db_utils::fieldsMemory($rsSqlBuscaMenorAno, 0);
	          $iAnoInicio          = $oMenorAno->o54_anousu; 
	        }
	        
	        if ($oInfosPrograma->o17_datafin != '') {
	        	$iAnoFim            = date('Y', $oInfosPrograma->o17_datafim);
	        } else {
	        	
	        	$sSqlBuscaMaiorAno   = " SELECT MAX(o54_anousu) AS o54_anousu                        ";
	        	$sSqlBuscaMaiorAno  .= " FROM orcprograma WHERE o54_programa = {$oPrograma->iCodigo} ";
	        	$rsSqlBuscaMaiorAno  = $oDaoOrcprograma->sql_record($sSqlBuscaMaiorAno);
	        	$oMaiorAno           = db_utils::fieldsMemory($rsSqlBuscaMaiorAno, 0);
	        	$iAnoFim             = $oMaiorAno->o54_anousu;
	        }
	        
	        if ($oInfosPrograma->quantidade_unidades == 1) {
	        	$iUnidadeGestora    = $oInfosPrograma->quantidade_unidades;
	        } else {
	        	$iUnidadeGestora    = $oDadosInstit->codtrib;
	        }

	        /**
           * forçando o decimal nos casos onde o valor da suplementação vem inteiro
           */
	        $fValorDecimal =  db_formatar($oPrograma->aEstimativas[$iAnoSessao], 'p');
	        $iValorSemSeparador = str_replace('.', '', $fValorDecimal);
	        
	        /**
	         * Manipulmos o campo o54_descr eliminando quebras de linha
	         */
	        $sFinalidadePrograma = utf8_decode(str_replace(array('\n', '\r'), ' ', $oInfosPrograma->o54_finali));
	        
	        /**
	         * Montamos o objeto que constituirá a linha do arquivo e após a mesma estar
	         * pronta jogamos para o array de dados que foi herdado da 'SigfisArquivoBase'
	         */
	    		$oDadosLinha = new stdClass();
	    		$oDadosLinha->cd_subprograma = str_pad($oPrograma->iCodigo,                    4, ' ', STR_PAD_LEFT);
	    		$oDadosLinha->Reservado_tce  = str_pad('',                                     2, ' ', STR_PAD_RIGHT);
	    		$oDadosLinha->de_subprograma = str_pad($oInfosPrograma->o54_descr,            50, ' ', STR_PAD_RIGHT);
	    		$oDadosLinha->cd_unidade     = str_pad($this->sCodigoTribunal,                 4, ' ', STR_PAD_LEFT);
	    		$oDadosLinha->Reservado_tce2 = str_pad('',                                     4, ' ', STR_PAD_RIGHT);
	    		$oDadosLinha->de_objetivo    = str_pad(substr($sFinalidadePrograma, 0, 120), 120, ' ', STR_PAD_RIGHT);
	    		$oDadosLinha->vl_SubPrograma = str_pad($iValorSemSeparador,                   16, ' ', STR_PAD_LEFT);
	    		$oDadosLinha->Dt_AnoInicio   = $iAnoInicio;
	    		$oDadosLinha->Dt_AnoFim      = $iAnoFim;
	    		$oDadosLinha->codigolinha    = 397;
	    		$this->aDados[] = $oDadosLinha;
	    	}
	    	
	    }
	    
    } 
    
    return $this->aDados;
	}
	
}
?>