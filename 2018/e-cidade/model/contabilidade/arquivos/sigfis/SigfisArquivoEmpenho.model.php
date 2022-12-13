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
require_once ("model/contabilidade/arquivos/sigfis/model/contabilidade/arquivos/sigfis/SigfisArquivoBase.model.php");
require_once ("model/contabilidade/arquivos/sigfis/SigfisVinculoRecurso.model.php");

/**
 * Classe que processa as informações para serem inseridas no
 * arquivo Empenho.txt
 * @author vinicius.silva@dbseller.com.br
 * @package contabilidade
 * @subpackage sigfis
 */

class SigfisArquivoEmpenho extends SigfisArquivoBase implements iPadArquivoTXTBase {

	protected $iCodigoLayout = 203;

	protected $sNomeArquivo  = 'Empenho';
	
	public function gerarDados() {
		
		$oDaoEmpempenho = db_utils::getDao('empempenho');
		$iAnoSessao     = db_getsession('DB_anousu');
		$iInstituicaoSessao = db_getsession('DB_instit');

	  $this->setCodigoLayout(203);
    if( $iAnoSessao < 2013 ){
  	  $this->setCodigoLayout(126);
    }
		
		$sCampos  = " db_config.codtrib,                             ";
    $sCampos .= " orcdotacao.o58_orgao,                          ";
    $sCampos .= " orcdotacao.o58_unidade,                        ";
    $sCampos .= " empempenho.e60_codemp,                         ";
    $sCampos .= " empempenho.e60_numerol,                        ";
    $sCampos .= " empempenho.e60_anousu,                         ";
    $sCampos .= " orcprojativ.o55_tipo,                          ";
    $sCampos .= " orcdotacao.o58_projativ,                       ";
    $sCampos .= " orcdotacao.o58_codigo,                         ";
    $sCampos .= " orcelemento.o56_elemento,                      ";
    $sCampos .= " empempenho.e60_vlremp,                         ";
    $sCampos .= " to_ascii(empempenho.e60_resumo,'latin2') as e60_resumo, ";
    $sCampos .= " CASE                                           "; 
    $sCampos .= "   WHEN empempenho.e60_codtipo = 1 THEN 3       ";
    $sCampos .= "   WHEN empempenho.e60_codtipo = 2 THEN 2       ";
    $sCampos .= "   WHEN empempenho.e60_codtipo = 3 THEN 1       ";
    $sCampos .= " END AS e60_codtipo,                            ";
    $sCampos .= " empempenho.e60_emiss,                          ";
    $sCampos .= " cgm.z01_nome,                                  ";
    $sCampos .= " empempenho.e60_emiss,                          ";
    $sCampos .= " cgm.z01_cgccpf,                                ";
    $sCampos .= " CASE                                           ";
    $sCampos .= "   WHEN char_length(cgm.z01_cgccpf) = 11 THEN 1 ";
    $sCampos .= "   WHEN char_length(cgm.z01_cgccpf) = 14 THEN 2 ";
    $sCampos .= "   ELSE 1                                       ";
    $sCampos .= " END AS tipo_pessoa_credor,                     ";
    $sCampos .= " orcdotacao.o58_orgao,                          ";
    $sCampos .= " orcdotacao.o58_funcao,                         ";
    $sCampos .= " orcdotacao.o58_subfuncao,                      ";
    $sCampos .= " orcdotacao.o58_programa                        ";
    $sWhereBuscaEmpenhos  = "     empempenho.e60_anousu = {$iAnoSessao}                                            ";
    $sWhereBuscaEmpenhos .= " and empempenho.e60_emiss between '{$this->dtDataInicial}' and '{$this->dtDataFinal}' ";
    $sWhereBuscaEmpenhos .= " and empempenho.e60_instit = {$iInstituicaoSessao}                                    ";
    $sSqlBuscaEmpenhos    = $oDaoEmpempenho->sql_query_buscaempenhos(null, $sCampos, null, $sWhereBuscaEmpenhos);
    $rsSqlBuscaEmpenhos   = $oDaoEmpempenho->sql_record($sSqlBuscaEmpenhos);
    $oEmpenhos            = db_utils::getCollectionByRecord($rsSqlBuscaEmpenhos);
    
    if (count($oEmpenhos) > 0) {
    	
      if (empty($this->sCodigoTribunal)) {
        throw new Exception("O código do tribunal deve ser informado para geração do arquivo");
      }
      
    	foreach ($oEmpenhos as $oEmpenho) {
    		
    		/**
    		 * forçando o decimal nos casos onde o valor do empenho vem inteiro
    		 */
    		$fValorEmpenhoDecimal = db_formatar($oEmpenho->e60_vlremp, 'p');
    		$iValorEmpenhoSemSeparador = str_replace('.', '', $fValorEmpenhoDecimal);
    		
    		/**
    		 * recuperando ano e mes
    		 */
    		$aDadosData     = explode('-', $oEmpenho->e60_emiss);
    		$sDataFormatada = $aDadosData[0].$aDadosData[1];

    		/**
    		 * Recuperando os dados do XML
    		 */
    		$iFonteRecurso = '';
    		if ($oVinculoRecurso = SigfisVinculoRecurso::getVinculoRecurso($oEmpenho->o58_codigo)) {
    			$iFonteRecurso = $oVinculoRecurso->recursotce;
    		} else {
    			
    			$sErroLog = "O Recurso {$oEmpenho->o58_codigo} não possui vínculo com o SIGFIS.\n";
          $this->addLog($sErroLog);
    		}
    		
    		/**
    		 * Manipulmos o campo e60_resumo eliminando quebras de linha
    		 */
    		$sHistorico  = str_replace(array("\n", "\r", "<br>"), " ", trim($oEmpenho->e60_resumo));
    		
    		$oDadosLinha = new stdClass();
    		$oDadosLinha->cd_Unidade                 = str_pad($this->sCodigoTribunal,              4, ' ', STR_PAD_LEFT);
//    		$oDadosLinha->cd_UnidadeOrcamentaria     = str_pad($oEmpenho->o58_orgao,                2, '0', STR_PAD_LEFT);
    		$oDadosLinha->cd_UnidadeOrcamentaria     = str_pad($oEmpenho->o58_unidade,              4, ' ', STR_PAD_LEFT);
    		$oDadosLinha->nu_Empenho                 = str_pad($oEmpenho->e60_codemp,              10, ' ', STR_PAD_RIGHT);
    		$oDadosLinha->nu_ProcessoLicitatorio     = str_pad( str_repeat(' ', 36), 36, ' ', STR_PAD_RIGHT );
    		$oDadosLinha->dt_Ano                     = $oEmpenho->e60_anousu;
    		$oDadosLinha->Tp_ProjetoAtividade        = str_pad($oEmpenho->o55_tipo,                 1, ' ', STR_PAD_LEFT);
    		$oDadosLinha->nu_ProjetoAtividade        = str_pad($oEmpenho->o58_projativ,             4, ' ', STR_PAD_LEFT);
    		$oDadosLinha->cd_FonteRecurso            = str_pad($oEmpenho->o58_codigo,               4, ' ', STR_PAD_LEFT); // nao vem mais do XML
    		$oDadosLinha->Reservado_tce              = str_repeat('0', 14);
    		$oDadosLinha->cd_Elemento                = str_pad(substr($oEmpenho->o56_elemento, 1, 8), 8, ' ', STR_PAD_LEFT);
    		$oDadosLinha->vl_Empenho                 = str_pad($iValorEmpenhoSemSeparador,         16, ' ', STR_PAD_LEFT);
    		$oDadosLinha->de_Historico               = str_pad(substr(trim(addslashes($sHistorico)), 0, 255), 255, ' ', STR_PAD_RIGHT);
    		$oDadosLinha->Tp_Empenho                 = $oEmpenho->e60_codtipo;
    		$oDadosLinha->dt_Empenho                 = str_replace('/', '', db_formatar($oEmpenho->e60_emiss,"d"));
    		$oDadosLinha->nu_Contrato                = str_pad('',                                 16, ' ', STR_PAD_RIGHT); // ?...
    		$oDadosLinha->nm_Credor                  = str_pad(substr($oEmpenho->z01_nome, 0, 50), 50, ' ', STR_PAD_RIGHT);
    		$oDadosLinha->dt_AnoMes                  = $sDataFormatada;
    		$oDadosLinha->nu_CGC_Credor              = str_pad($oEmpenho->z01_cgccpf,              14, ' ', STR_PAD_RIGHT);
    		$oDadosLinha->Tp_Pessoa                  = $oEmpenho->tipo_pessoa_credor;
    		$oDadosLinha->cd_Orgao                   = str_pad($oEmpenho->o58_orgao,                4, ' ', STR_PAD_LEFT);
    		$oDadosLinha->cd_Dispensa                = str_pad('',                                 16, ' ', STR_PAD_RIGHT); // ?...
    		$oDadosLinha->Reservado_tce2             = '0';
    		$oDadosLinha->cd_Funcao                  = str_pad($oEmpenho->o58_funcao,               2, ' ', STR_PAD_LEFT);
    		$oDadosLinha->cd_Programa                = str_pad($oEmpenho->o58_subfuncao,            4, ' ', STR_PAD_LEFT);
    		$oDadosLinha->cd_SubPrograma             = str_pad($oEmpenho->o58_programa,             4, ' ', STR_PAD_LEFT);
    		$oDadosLinha->St_contrato_aplicavel      = 'N'; // padrão
    		$oDadosLinha->St_licitacao_sujeito       = ( trim($oDadosLinha->nu_ProcessoLicitatorio) == ""?'N':'S' ); // padrão
    		$oDadosLinha->NU_CONVENIO                = str_pad('',                                 16, ' ', STR_PAD_RIGHT); // ?...
    		$oDadosLinha->NU_TERMOPARCERIA           = str_pad('',                                 16, ' ', STR_PAD_RIGHT); // ?...
    		$oDadosLinha->ST_CONVENIO_APLICAVEL      = 'N'; // padrão
    		$oDadosLinha->ST_TERMOPARCERIA_APLICAVEL = 'N'; // padrão

        $oDadosLinha->Nu_Aditivo                 = str_pad('',                                 16, ' ', STR_PAD_RIGHT); // ?...
        if(db_getsession('DB_anousu') < 2013 ){
          $oDadosLinha->codigolinha                = 413;
        }else{
          $oDadosLinha->st_semcpfcnpj              = (trim($oEmpenho->z01_cgccpf) == '' or ($oEmpenho->z01_cgccpf)+0 == 0 ? '0' : '1');
          $oDadosLinha->codigolinha                = 668;
        }
    		$this->aDados[] = $oDadosLinha;
    	}
    	
    } 
    
    return $this->aDados;
	}
}
?>