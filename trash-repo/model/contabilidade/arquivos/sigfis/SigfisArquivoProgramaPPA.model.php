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

require_once ('SigfisArquivoBase.model.php');
require_once ('model/ppadespesa.model.php');

/**
 * Classe que processa as informa��es para serem inseridas no
 * arquivo ProgramaPPA.txt
 * @author vinicius.silva@dbseller.com.br
 * @package contabilidade
 * @subpackage sigfis
 */

class SigfisArquivoProgramaPPA extends SigfisArquivoBase implements iPadArquivoTXTBase {
	
  protected $iCodigoLayout = 110;
  protected $sNomeArquivo  = 'programappa';
	
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
	    		 * Avalia se as datas e a unidade gestora retornadas pela consulta s�o v�lidas. Se elas n�o forem, efetuamos
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
           * for�ando o decimal nos casos onde o valor da suplementa��o vem inteiro
           */
	        $fValorDecimal =  db_formatar($oPrograma->aEstimativas[$iAnoSessao], 'p');
	        $iValorSemSeparador = str_replace('.', '', $fValorDecimal);
	        
	        /**
	         * Montamos o objeto que constituir� a linha do arquivo e ap�s a mesma estar
	         * pronta jogamos para o array de dados que foi herdado da 'SigfisArquivoBase'
	         */
	    		$oDadosLinha = new stdClass();
	    		$oDadosLinha->cd_subprograma = str_pad($oPrograma->iCodigo,           4, ' ', STR_PAD_LEFT);
	    		$oDadosLinha->de_subprograma = str_pad($oInfosPrograma->o54_descr,   50, ' ', STR_PAD_RIGHT);
	    		$oDadosLinha->cd_unidade     = str_pad($iUnidadeGestora,              4, ' ', STR_PAD_LEFT);
	    		$oDadosLinha->de_objetivo    = str_pad($oInfosPrograma->o54_finali, 120, ' ', STR_PAD_RIGHT);
	    		$oDadosLinha->vl_SubPrograma = str_pad($iValorSemSeparador,          16, ' ', STR_PAD_LEFT);
	    		$oDadosLinha->Dt_AnoInicio   = $iAnoInicio;
	    		$oDadosLinha->Dt_AnoFim      = $iAnoFim;
	    		$oDadosLinha->codigolinha    = 397;
	    		$this->aDados[] = $oDadosLinha;
	    	}
	    	
	    }
	    
    } else {
    	throw new Exception("Nenhum registro retornado para o ano {$iAnoSessao}.");
    }
    
    return $this->aDados;
	}
	
}
?>