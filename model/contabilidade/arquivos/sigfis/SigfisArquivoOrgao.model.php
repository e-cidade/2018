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
require_once ("model/contabilidade/arquivos/sigfis/SigfisVinculoOrgaoResponsavel.model.php");


/**
 * Classe que processa as informaчѕes para serem inseridas no
 * arquivo Orgao.txt
 * @author vinicius.silva@dbseller.com.br
 * @package contabilidade
 * @subpackage sigfis
 */

class SigfisArquivoOrgao extends SigfisArquivoBase implements iPadArquivoTXTBase {
	
	protected $iCodigoLayout = 112;
	protected $sNomeArquivo  = 'Orgao';
	
  public function gerarDados() {
		
		$oDaoOrcorgao        = db_utils::getDao('orcorgao');
		
		$iAnoSessao          = db_getsession('DB_anousu');
    $iInstituicaoSessao  = db_getsession('DB_instit');
    $oDadosInstit        = db_stdClass::getDadosInstit();
		
		$sCampos             = " distinct orcorgao.o40_anousu, ";
		$sCampos            .= " db_config.nomeinst,  ";
		$sCampos            .= " orcorgao.o40_orgao,  ";
		$sCampos            .= " to_ascII(orcorgao.o40_descr,'LATIN2') as o40_descr   ";
		$sWhereBuscaOrgaos   = "     o40_anousu = {$iAnoSessao}         ";
		$sWhereBuscaOrgaos  .= " and o58_instit = {$iInstituicaoSessao} ";
		$sSqlBuscaOrgaos     = $oDaoOrcorgao->sql_query_dotacao(null, null, $sCampos, null , $sWhereBuscaOrgaos);
		
		$rsSqlBuscaOrgaos    = $oDaoOrcorgao->sql_record($sSqlBuscaOrgaos);
		$aOrgaos             = db_utils::getColectionByRecord($rsSqlBuscaOrgaos);
		
		if (count($aOrgaos) > 0) {
			
		  if (empty($this->sCodigoTribunal)) {
		    throw new Exception("O cѓdigo do tribunal deve ser informado para geraчуo do arquivo");
		  }
		  
			foreach ($aOrgaos as $oOrgao) {
				
				if ($oResponsavelOrgao = SigfisVinculoOrgaoResponsavel::getVinculoOrgaoResponsavel($oOrgao->o40_orgao)) {

					$iCpfResponsavel     = $oResponsavelOrgao->cpfresponsavel;
					$iTipoGestaoCreditos = $oResponsavelOrgao->tipogestaocreditos;
					$sDataInicioGestao   = $oResponsavelOrgao->datainiciogestao;
					$iTipoOrdenador      = $oResponsavelOrgao->tipoordenador;
				} else {
					
					$sErroLog = "O Orgуo {$oOrgao->o40_orgao} nуo possui responsсvel vinculado.\n";
          $this->addLog($sErroLog);
				}
				
				$sDataInicioGestao = str_replace("/", "", $sDataInicioGestao);
				$oDadosLinha = new stdClass();
				$oDadosLinha->Dt_Ano          = str_pad($oOrgao->o40_anousu,    4, ' ', STR_PAD_LEFT);
				$oDadosLinha->Cd_Unidade      = str_pad($this->sCodigoTribunal, 4, ' ', STR_PAD_LEFT);
				$oDadosLinha->Cd_Orgao        = str_pad($oOrgao->o40_orgao,     4, ' ', STR_PAD_LEFT);
				$oDadosLinha->De_Orgao        = str_pad(utf8_decode(substr($oOrgao->o40_descr, 0, 50)), 50, ' ', STR_PAD_RIGHT);
				$oDadosLinha->Reservado_TCE   = str_repeat('0', 6);
				$oDadosLinha->Cd_CPFOrdenador = str_pad($iCpfResponsavel,      11, ' ', STR_PAD_RIGHT);
				$oDadosLinha->Tp_Credito      = str_pad($iTipoGestaoCreditos,   1, ' ', STR_PAD_LEFT);
				$oDadosLinha->Dt_Iniciogestao = str_pad($sDataInicioGestao,     8, ' ', STR_PAD_RIGHT);
				$oDadosLinha->Tp_ordenador    = str_pad($iTipoOrdenador,        1, ' ', STR_PAD_LEFT);
				$oDadosLinha->codigolinha     = 399;
				$this->aDados[] = $oDadosLinha;
			}
			
		} 
		
		return $this->aDados;
	}
	
}
?>