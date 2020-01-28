<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once ('model/contabilidade/arquivos/sigfis/SigfisArquivoBase.model.php');
require_once ('model/ppadespesa.model.php');

class SigfisArquivoPrograma extends SigfisArquivoBase {
	
  public function getCodigoLayout() {
    return '110'; 
  }
  
  public function getNomeArquivo() {
     return 'programa';
  }
		
	public function gerarDados() {
		
		$oDaoPpaintegracao    = db_utils::getDao('ppaintegracao');
		$oDaoOrcprograma      = db_utils::getDao('orcprograma');
		
		$iAnoSessao           = db_getsession('DB_anousu');
    $iInstituicaoSessao   = db_getsession('DB_instit');
    $oDadosInstit         = db_stdClass::getDadosInstit();
    
		$sSqlBuscaVersaoPpa   = " SELECT DISTINCT ppaintegracao.*                                                         ";
		$sSqlBuscaVersaoPpa  .= "            FROM  ppaintegracaodespesa                                                   ";
		$sSqlBuscaVersaoPpa  .= "      INNER JOIN ppaintegracao                                                           ";
		$sSqlBuscaVersaoPpa  .= "              ON ppaintegracao.o123_sequencial = ppaintegracaodespesa.o121_ppaintegracao ";
		$sSqlBuscaVersaoPpa  .= "           WHERE ppaintegracao.o123_ano            = {$iAnoSessao}                       ";
    $sSqlBuscaVersaoPpa  .= "             AND ppaintegracao.o123_situacao       = 1                                   ";
    $sSqlBuscaVersaoPpa  .= "             AND ppaintegracao.o123_tipointegracao = 1                                   ";
    $sSqlBuscaVersaoPpa  .= "             AND ppaintegracao.o123_instit         = {$iInstituicaoSessao}               ";
    $rsSqlBuscaVersaoPpa  = $oDaoPpaintegracao->sql_record($sSqlBuscaVersaoPpa);
    $oBuscaVersaoPpa      = db_utils::getCollectionByRecord($rsSqlBuscaVersaoPpa);
    
    if (count($oBuscaVersaoPpa) > 0) {
    
	    foreach ($oBuscaVersaoPpa as $oVersao) {
	    	
	    	$oPpaDespesa     = new ppaDespesa($oVersao->o123_ppaversao);
	    	$aProgramas      = $oPpaDespesa->getQuadroEstimativas(null, 5);
	    	
	    	foreach ($aProgramas as $oPrograma) {
	    		
	    		$sSqlBuscaPrograma  = "    SELECT *,                                                                                           ";
	    		$sSqlBuscaPrograma .= "           (SELECT COUNT(*)                                                                             ";
	    		$sSqlBuscaPrograma .= "              FROM orcprogramaunidade                                                                   ";
	    		$sSqlBuscaPrograma .= "             WHERE o14_orcprograma = 0                                                                  ";
	    		$sSqlBuscaPrograma .= "               AND o14_anousu = 2011) AS quantidade_unidades                                            ";
	    		$sSqlBuscaPrograma .= "      FROM orcprograma                                                                                  ";
	    		$sSqlBuscaPrograma .= " LEFT JOIN orcprogramaunidade       ON orcprogramaunidade.o14_anousu         = orcprograma.o54_anousu   ";
	    		$sSqlBuscaPrograma .= "                                   AND orcprogramaunidade.o14_orcprograma    = orcprograma.o54_programa ";
	    		$sSqlBuscaPrograma .= " LEFT JOIN orcprogramahorizontetemp ON orcprogramahorizontetemp.o17_anousu   = orcprograma.o54_anousu   ";
	    		$sSqlBuscaPrograma .= "                                   AND orcprogramahorizontetemp.o17_programa = orcprograma.o54_programa ";
	    		$sSqlBuscaPrograma .= "     WHERE orcprograma.o54_anousu   = {$iAnoSessao}                                                     ";
	    		$sSqlBuscaPrograma .= "       AND orcprograma.o54_programa = {$oPrograma->iCodigo}                                             ";
	    		$rsSqlBuscaPrograma = $oDaoOrcprograma->sql_record($sSqlBuscaPrograma);
	    		$oInfosPrograma = db_utils::fieldsMemory($rsSqlBuscaPrograma, 0);
	    		
	    		/**
	    		 * Avalia se as datas e a unidade gestora retornadas pela consulta são válidas. Se elas não forem, efetuamos
	    		 * uma nova busca para resgatar os valores corretos 
	    		 */
	    		if ($oInfosPrograma->o17_dataini != '') {
	    	  	$iAnoInicio         = date('Y', $oInfosPrograma->o17_dataini);
	    	  } else {
	    	  	
	    	  	$sSqlBuscaMenorAno  = "SELECT MIN(o54_anousu) AS o54_anousu FROM orcprograma WHERE o54_programa = {$oPrograma->iCodigo}";
	    	  	$rsSqlBuscaMenorAno = $oDaoOrcprograma->sql_record($sSqlBuscaMenorAno); 
	    	  	$oMenorAno          = db_utils::fieldsMemory($rsSqlBuscaMenorAno, 0);
	          $iAnoInicio         = $oMenorAno->o54_anousu; 
	        }
	        
	        if ($oInfosPrograma->o17_datafin != '') {
	        	$iAnoFim            = date('Y', $oInfosPrograma->o17_datafim);
	        } else {
	        	
	        	$sSqlBuscaMaiorAno  = "SELECT MAX(o54_anousu) AS o54_anousu FROM orcprograma WHERE o54_programa = {$oPrograma->iCodigo}";
	        	$rsSqlBuscaMaiorAno = $oDaoOrcprograma->sql_record($sSqlBuscaMaiorAno);
	        	$oMaiorAno          = db_utils::fieldsMemory($rsSqlBuscaMaiorAno, 0);
	        	$iAnoFim            = $oMaiorAno->o54_anousu;
	        }
	        
	        if ($oInfosPrograma->quantidade_unidades == 1) {
	        	$iUnidadeGestora    = $oInfosPrograma->quantidade_unidades;
	        } else {
	        	$iUnidadeGestora    = $oDadosInstit->codtrib;
	        }
	        
	        /**
	         * Montamos o objeto que constituirá a linha do arquivo e após a mesma estar
	         * pronta jogamos para o array de dados que foi herdado da 'SigfisArquivoBase'
	         */
	    		$oDadosLinha = new stdClass();
	    		$oDadosLinha->cd_subprograma = $oPrograma->iCodigo;
	    		$oDadosLinha->de_subprograma = $oInfosPrograma->o54_descr;
	    		$oDadosLinha->cd_unidade     = $iUnidadeGestora;
	    		$oDadosLinha->de_objetivo    = $oInfosPrograma->o54_finali;
	    		$oDadosLinha->vl_SubPrograma = $oPrograma->aEstimativas[$iAnoSessao];
	    		$oDadosLinha->Dt_AnoInicio   = $iAnoInicio;
	    		$oDadosLinha->Dt_AnoFim      = $iAnoFim;
	    		$oDadosLinha->codigolinha    = 400;
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