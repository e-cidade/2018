<?
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

/**
 * @fileoverview Emissao de TXT para o INSS - SISOBRA
 * @version   $Revision: 1.12 $
 * @revision  $Author: dbrafael.nery $
 */
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");  

require_once("dbforms/db_layouttxt.php");
require_once("dbforms/db_funcoes.php");

require_once("classes/db_db_config_classe.php");
require_once("classes/db_db_sistemaexterno_classe.php");
require_once("classes/db_obras_classe.php");
require_once("classes/db_obrasenvio_classe.php");
require_once("classes/db_obrasenvioreg_classe.php");
require_once("classes/db_obrasenvioreghab_classe.php");

$oJson                  = new services_json();
$oSistemaExterno        = new cl_db_sistemaexterno;
$oObras                 = new cl_obras; 
$oObrasEnvio            = new cl_obrasenvio;
$oObrasEnvioReg         = new cl_obrasenvioreg;
$oObrasEnvioRegHab      = new cl_obrasenvioreghab;
$oRetorno               = new stdClass();
$oDbConfig              = new cl_db_config;

$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"])); 
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

$aDadosRetorno          = array();

try {
	
  switch ($oParam->exec) {
    
    case "gerarTXT" :
     	unset($_SESSION['aInconsistencia']);
      db_putsession("aInconsistencia", array());
    	
    	$iMes            = str_pad($oParam->iMes, 2, "0", STR_PAD_LEFT);
    	$iAno            = $oParam->iAno;
    	$lAviso          = $oParam->lAviso;
    	$iCodLayOut      = 162; 
    	
    	$hNomeArquivo    = date("His", db_getsession("DB_datausu"));
    	$dNomeArquivo    = date("Ymd", db_getsession("DB_datausu"));
    	$pArquivoTxt     = "/tmp/obras_{$iMes}_{$iAno}_{$dNomeArquivo}_{$hNomeArquivo}.txt";
    	
    	$aErros          = array();
    	
    	$aHabiteseGerado = array();
    	$iInconsistencia = 0;
    	$lIncluir        = false;
    	$iUltimoDiaMes   = date("d", strtotime("{$iAno}-{$iMes}-".cal_days_in_month(CAL_GREGORIAN, $iMes,$iAno)));
    	
    	$oLayoutTxt      = new db_layouttxt($iCodLayOut, $pArquivoTxt);
    	
    	$aDadosGeracao   = array(); 
    	//$aInconsistencia = array();
    	// Fun��es auxiliares
    	
    	/**
    	 * fuction para adicionar inconsistencias no array 
    	 *
    	 * @param string $sCampo         - Campo a ser validado
    	 * @param integer $iRegistroObra - codigo obra com problema
    	 * @param string $sDetalhe       - tipo do registro de erro (alvara, cnpj etc. )
    	 * @param boolean $lObrigatorio  - Se o campo � obrigat�rio = true
    	 */
    	function validaDados( $mValor, $iRegistroObra, $sMensagem, $iTamanhoCampo, $lObrigatorio = true, $sAlinhamento = "R") {

    		if ($sAlinhamento == "R") {
    			 
    			$cStrPad  = STR_PAD_RIGHT;
    			$sComplea = " ";
    		}else if ($sAlinhamento == "LS") {
    			 
    			$cStrPad  = STR_PAD_LEFT;
    			$sComplea = " ";
    		} else {
    			 
    			$cStrPad = STR_PAD_LEFT;
    			$sComplea = "0";
    		}

    		$aErros = db_getsession("aInconsistencia");
    	  
        $oErros = new stdClass();
    		if ( empty($mValor) ) {

    			if( $lObrigatorio == true ) {
    				$oErros->tipo   = "ERRO";
    			} else {
    				$oErros->tipo   = "AVISO";
    			}
    			$oErros->registro = is_string($iRegistroObra) ? $iRegistroObra : "Obra : {$iRegistroObra} ";
    			$oErros->detalhe  = urlencode($sMensagem);
    			$aErros[]         = $oErros;
    			db_putsession("aInconsistencia", $aErros);
    			 
    		}
    		return str_pad(trim($mValor), $iTamanhoCampo, $sComplea, $cStrPad);
    	}
    	 
    	/**
    	 * Verifica se � CPF ou CNPJ
    	 *
    	 * @param integer $iCgcCpf
    	 * @param integer $iRegistroObra
    	 * @return integer
    	 */
    	function getTipoIdentificacao($iCgcCpf, $iRegistroObra, $sDetalhe, $iNumCgm) {

    		$aErros = db_getsession("aInconsistencia");
    		 
    		// CNPJ
    		if( strlen($iCgcCpf) > 11 ) {

    			if( validaCNPJ($iCgcCpf) == true ) {
    				return 1; // cnpj
    			} else {

    				$oErros = new stdClass();
    				$oErros->tipo     = "ERRO";
    				$oErros->registro = urlencode("Obra : {$iRegistroObra} ");
    				$oErros->detalhe  = urlencode("Numcgm: {$iNumCgm} - CNPJ do {$sDetalhe} inv�lido: \"{$iCgcCpf}\"");
    				$aErros[] = $oErros;
    				//arsort($aErros);
    				db_putsession("aInconsistencia", $aErros);
    			}

    		}
    		// CPF
    		else {
    			 
    			if( validaCPF($iCgcCpf) == true ) {
    				return 3;// $iCgcCpf; die(); // cpf
    			} else {

    				$oErros = new stdClass();
    				$oErros->tipo     = "ERRO";
    				$oErros->registro = urlencode("Obra : {$iRegistroObra} ");
    				$oErros->detalhe  = urlencode("Numcgm: {$iNumCgm} - CPF {$sDetalhe} inv�lido: \"{$iCgcCpf}\" ");
    				$aErros[] = $oErros;
    				//arsort($aErros);
    				db_putsession("aInconsistencia", $aErros);
    			}
    		}
      return 0;//erro
    	}

    	/**
    	 * Valida CPF
    	 * 
    	 * @param string $cpf
    	 * @return boolean
    	 */
    	function validaCPF($cpf) {
    		
    		/**
    		 *  Verifiva se o n�mero digitado cont�m todos os digitos
    		 */
    		$cpf = str_pad(ereg_replace('[^0-9]', '', $cpf), 11, '0', STR_PAD_LEFT);

    		/**
    		 * Verifica se nenhuma das sequ�ncias abaixo foi digitada, caso seja, retorna falso
    		 */
    		if ( strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || 
    				                       $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999') {  
    			return false;

    		}	else	{   
    			
    			/**
    			 * Calcula os n�meros para verificar se o CPF � verdadeiro
    			 */     				
    			for ($t = 9; $t < 11; $t++) {
    				
    				for ($d = 0, $c = 0; $c < $t; $c++) {
    					$d += $cpf{$c} * (($t + 1) - $c);
    				}

    				$d = ((10 * $d) % 11) % 10;

    				if ($cpf{$c} != $d) {
    					return false;
    				}
    			}

    			return true;
    		}
    	}    	
    	 
    	/**
    	 * VERFICA CNPJ
    	 */
    	function validaCNPJ($cnpj) {

    		if ((int)$cnpj == 0) {
    			return false;
    		}
    		if (strlen($cnpj) != 14) {
    			return false;
    		}
    		$soma = 0;
    		$soma += ($cnpj[0] * 5);
    		$soma += ($cnpj[1] * 4);
    		$soma += ($cnpj[2] * 3);
    		$soma += ($cnpj[3] * 2);
    		$soma += ($cnpj[4] * 9);
    		$soma += ($cnpj[5] * 8);
    		$soma += ($cnpj[6] * 7);
    		$soma += ($cnpj[7] * 6);
    		$soma += ($cnpj[8] * 5);
    		$soma += ($cnpj[9] * 4);
    		$soma += ($cnpj[10] * 3);
    		$soma += ($cnpj[11] * 2);
    		 
    		$d1 = $soma % 11;
    		$d1 = $d1 < 2 ? 0 : 11 - $d1;
    		 
    		$soma = 0;
    		$soma += ($cnpj[0] * 6);
    		$soma += ($cnpj[1] * 5);
    		$soma += ($cnpj[2] * 4);
    		$soma += ($cnpj[3] * 3);
    		$soma += ($cnpj[4] * 2);
    		$soma += ($cnpj[5] * 9);
    		$soma += ($cnpj[6] * 8);
    		$soma += ($cnpj[7] * 7);
    		$soma += ($cnpj[8] * 6);
    		$soma += ($cnpj[9] * 5);
    		$soma += ($cnpj[10] * 4);
    		$soma += ($cnpj[11] * 3);
    		$soma += ($cnpj[12] * 2);
    		 
    		$d2 = $soma % 11;
    		$d2 = $d2 < 2 ? 0 : 11 - $d2;
    		 
    		if ($cnpj[12] == $d1 && $cnpj[13] == $d2) {
    			return true;
    		}
    		else {
    			return false;
    		}
    	}
    	 
    	//=============  PROCESSAMENTO ===== //
    	 
    	// SQL e variaveis para definir o header
    	 
    	$iInstit         = db_getsession("DB_instit");
    	$sWherHeader     = "codigo = {$iInstit} ";
    	$sSqlHeader      = $oDbConfig->sql_query(null, "*", null, $sWherHeader);
    	$rsHeader        = $oDbConfig->sql_record($sSqlHeader);
    	$aHeader         = db_utils::fieldsMemory($rsHeader, 0);
    	$nCnpj           = validaCNPJ($aHeader->cgc) ? $aHeader->cgc : '';
                       
      $sCompetencia    = $iAno.$iMes;
      $dDataEnvio      = date("Ymd", db_getsession("DB_datausu"));
      $dHoraEnvio      = date("His");  // hora atual
      $sErroLinha1     = "Linha 1 Arquivo";
      $iDepartamento   = db_getsession("DB_coddepto");     
      
      // sinpas da institui��o
      $iCodInpasInstit = $oSistemaExterno->getCodigoSistemaExternoMunic(1, $aHeader->numcgm);
      
      $sSqlSisobra     = $oObras->sql_queryDadosSisobra($iMes, $iAno); 
      $rsSisobra       = $oObras->sql_record($sSqlSisobra);
      $aDadosSisobra   = db_utils::getCollectionByRecord($rsSisobra, false, false, false);
      
      $sComObras       = 2; // sem obras
                       
      $iTotalObras     = count($aDadosSisobra);      
      
      /**
       * Quantidade de linhas do arquivo incrementado mais dois, pro header e footer
       */
      $iNumeroLinhas = $iTotalObras + 2;
      
      if ($iTotalObras == 0 ) {
      	throw new Exception(_M('tributario.projetos.pro4_gerarTxtINSS.sem_obras'));
      }
      
      if($iTotalObras > 0){
      	$sComObras   = 1;  // possui obras no arquivo
      }
      
    	//----------------   defini��o do HEADER do arquivo ======================================
    	
    	$oHeader = new stdClass();
    	
    	$oHeader->identificador           = 1;
    	$oHeader->email                   = validaDados($aHeader->email,      $sErroLinha1, "E-mail da institui��o inv�lido", 60, false);
    	$oHeader->telefone                = validaDados($aHeader->telef,      $sErroLinha1, "Telefone da institui��o inv�lido", 14);
    	$oHeader->bairro                  = validaDados($aHeader->bairro,     $sErroLinha1, "Bairro da institui��o inv�lido", 20);
    	$oHeader->uf                      = validaDados($aHeader->uf,         $sErroLinha1, "UF da institui��o inv�lido", 2);
      $oHeader->cep                     = validaDados($aHeader->cep,        $sErroLinha1, "CEP da institui��o inv�lido", 8);
      $oHeader->complemento_endereco    = validaDados($aHeader->db21_compl, $sErroLinha1, "Complemento do endere�o da institui��o inv�lido", 10,false); 
      $oHeader->numero                  = validaDados($aHeader->numero,     $sErroLinha1, "N�mero do endere�o da institui��o inv�lido", 10);
      $oHeader->endereco_prefeitura     = validaDados($aHeader->ender,      $sErroLinha1, "Endere�o da prefeitura inv�lido", 55);
      $oHeader->nome_cidade             = validaDados($aHeader->munic,      $sErroLinha1, "Nome da cidade da institui��o inv�lido", 30);
      $oHeader->departamento_secretaria = validaDados($iDepartamento,       $sErroLinha1, "Departamento da secret�ria inv�lido", 55);  
      $oHeader->nome_prefeitura         = validaDados($aHeader->nomeinst,   $sErroLinha1, "Nome da prefeitura inv�lido", 55);
      $oHeader->origem                  = 44;       // dado FIXO
      $oHeader->movimento               = $sComObras;
      $oHeader->competencia             = $sCompetencia;
      $oHeader->data_envio              = $dDataEnvio;   
      $oHeader->hora_envio              = $dHoraEnvio;    
      $oHeader->codigo_municipio        = validaDados($iCodInpasInstit,     $sErroLinha1, "C�digo do munic�pio inv�lido", 5);    
      $oHeader->cnpj_prefeitura         = validaDados($nCnpj,               $sErroLinha1, "CNPJ da prefeitura inv�lido", 14);
      
      if( $oLayoutTxt->setByLineOfDBUtils($oHeader, 1, "1") == false ) {
        throw new Exception (_M('tributario.projetos.pro4_gerarTxtINSS.erro_gerar_linha_header'));
      }     

      
      //===================  DADOS do REAPONSAVEL  CONSTRUTOR e OBRA, unica linha ==================================//
      
      
      
      foreach ($aDadosSisobra as $iIndiceSisobra => $oObra ) {
      	/**
      	 * Define algumas vari�veis para utiliza��o dentroa linha
      	 */
      	$iTipoIdentificacaoResponsavel   = 0;
      	$iTipoIdentificacaoConstrutor    = 0;
      	$iCodigoObra                     = (int)$oObra->codigoobra;
      	                                 
      	$iTipoIdentificacaoResponsavel   = getTipoIdentificacao($oObra->cpfresponsavel, $iCodigoObra, "responsavel", $oObra->cgmresponsavel);
        $iTipoIdentificacaoConstrutor    = getTipoIdentificacao($oObra->cpfconstrutor , $iCodigoObra, "construtor" , $oObra->cgmconstrutor);
                                         
	      $iCodigoSinpasResponsavel        = $oSistemaExterno->getCodigoSistemaExternoMunic(1, $oObra->cgmresponsavel);
	      $iCodigoSinpasConstrutor         = $oSistemaExterno->getCodigoSistemaExternoMunic(1, $oObra->cgmconstrutor);
	                                       
	      $sDataObra                       = str_replace('-', '', $oObra->dataobra);
	      $sDataInicioObra                 = str_replace('-', '', $oObra->datainicioobra);
        $sDataFimObra                    = str_pad( str_replace('-', '', $oObra->datafimobra), 8, " ", STR_PAD_RIGHT);

        /*
         * caso venha registros referente a acrescimo, demoli��o etc.
         * passamos db_formatar, para deixar 8,2, senao, o dado ir� em branco
         */
        $aSubstituir = array('.',',');
        
        if (!empty($oObra->areaobra)) {
	        $iAreaConstrucao = validaDados(str_replace($aSubstituir , "",db_formatar($oObra->areaobra, 'f')),  $iCodigoObra, "�rea da obra inv�lida", 8, false, "L");
        } else {
        	$iAreaConstrucao = str_repeat(' ' , 8);
        }   
        
        if (!empty($oObra->areademolicao)) {
          $iAreaDemolicao  = validaDados(str_replace($aSubstituir , "",db_formatar($oObra->areademolicao, 'f')),  $iCodigoObra, "�rea da obra inv�lida", 8, false, "L");
        } else {
        	$iAreaDemolicao  = str_repeat(' ' , 8);
        }
        
        if (!empty($oObra->areaacrescimo)) {        	
          $iAreaAcrescimo  = validaDados(str_replace($aSubstituir , "",db_formatar($oObra->areaacrescimo, 'f')),  $iCodigoObra, "�rea da obra inv�lida", 8, false, "L");        	
        } else {              	
        	$iAreaAcrescimo  = str_repeat(' ' , 8);
        }
        
        
        if (!empty($oObra->areaexistente)) {        	
          $iAreaExistente  = validaDados(str_replace($aSubstituir , "",db_formatar($oObra->areaexistente, 'f')),  $iCodigoObra, "�rea da obra inv�lida", 8, false, "L");        	
        } else {        	
        	$iAreaExistente  = str_repeat(' ' , 8);
        }
        
        if (!empty($oObra->areareforma)) {        	
          $iAreaReforma    =  validaDados(str_replace($aSubstituir , "",db_formatar($oObra->areareforma, 'f')),  $iCodigoObra, "�rea da obra inv�lida", 8, false, "L");
        } else {        	
        	$iAreaReforma    = str_repeat(' ' , 8);
        }    
        
	      
      	/**
      	 * Objeto com os dados necess�rios para salvar no banco de dados
      	 * Adiciona Codigo da obra para ser salvo no banco de dados
      	 */
      	$oDadosObraGeracao               = new stdClass();
      	$oDadosObraGeracao->ob17_codobra = $iCodigoObra;
      	$sCepObra                        = empty($oObra->cepobra) ? $aHeader->cep : $oObra->cepobra;
      	
      	
	      $oLinha2 = new stdClass();
	      $oLinha2->identificador                        = 2; 
				$oLinha2->responsavel_tipo_identificacao       = str_pad($iTipoIdentificacaoResponsavel,                                                             1, " ", STR_PAD_RIGHT);
				$oLinha2->responsavel_identificacao            = str_pad($oObra->cpfresponsavel,                                                                    14, " ", STR_PAD_RIGHT);
				$oLinha2->responsavel_nome                     = validaDados($oObra->nomeresponsavel,        $iCodigoObra, "Nome Respons�vel inv�lido",             55);
				$oLinha2->responsavel_endereco                 = validaDados($oObra->enderecoresponsavel,    $iCodigoObra, "Endere�o do Respons�vel inv�lido",      55);
				$oLinha2->responsavel_bairro                   = validaDados($oObra->bairroresponsavel,      $iCodigoObra, "Bairro do Respons�vel inv�lido",        20);
				$oLinha2->responsavel_cep                      = validaDados($oObra->cepresponsavel,         $iCodigoObra, "CEP do Respons�vel inv�lido",            8);
				$oLinha2->responsavel_uf                       = validaDados($oObra->ufresponsavel,          $iCodigoObra, "UF do Respons�vel inv�lido",             2);
				$oLinha2->responsavel_codigo_municipio         = validaDados($iCodigoSinpasResponsavel,      $iCodigoObra, "C�digo SINPAS do munic�pio do endere�o do respons�vel n�o Encontrado", 6, false, "LS");
				$oLinha2->responsavel_ddd_telefone             = str_repeat(" ", 4);                                           
				$oLinha2->responsavel_telefone                 = validaDados($oObra->telefoneresponsavel,    $iCodigoObra, "Telefone Respons�vel inv�lido",         12, false);
				$oLinha2->responsavel_ddd_fax                  = str_repeat(" ", 4);                                           
        $oLinha2->responsavel_fax                      = validaDados($oObra->faxresponsavel,         $iCodigoObra, "Fax Respons�vel inv�lido",              12, false);
				$oLinha2->responsavel_email                    = validaDados($oObra->emailresponsavel,       $iCodigoObra, "Email Respons�vel inv�lido",            60, false);
        $oLinha2->responsavel_tipo_vinculo             = validaDados($oObra->tipovinculoresponsavel, $iCodigoObra, "Tipo de Vinculo Respons�vel inv�lido >{$oObra->tipovinculoresponsavel}< ",   2, true , "L");
				$oLinha2->responsavel_data_vinculo             = validaDados($sDataInicioObra,               $iCodigoObra, "Data de inicio da obra inv�lida",        8);                                          
        $oLinha2->responsavel_endereco_correspondencia = validaDados($oObra->enderecoresponsavel,    $iCodigoObra, "Endere�o Respons�vel inv�lido",         55, false);
				$oLinha2->responsavel_bairro_correspondencia   = validaDados($oObra->bairroresponsavel,      $iCodigoObra, "Bairro Respons�vel inv�lido",           20, false);
				$oLinha2->responsavel_cep_correspondencia      = validaDados($oObra->cpfresponsavel,         $iCodigoObra, "CEP Respons�vel inv�lido",               8, false);
	      $oLinha2->responsavel_codigo_sinpas            = validaDados($iCodigoSinpasResponsavel,      $iCodigoObra, "C�digo SINPAS do munic�pio para Correspondencia do respons�vel n�o Encontrado", 6, true, "LS");
				                                                                                                                         
        $oLinha2->construtor_tipo_identificacao        = str_pad($iTipoIdentificacaoConstrutor,  1,  " ", STR_PAD_RIGHT);
        $oLinha2->construtor_identificacao             = str_pad($oObra->cpfconstrutor,         14,  " ", STR_PAD_RIGHT);
        $oLinha2->construtor_nome                      = validaDados($oObra->nomeconstrutor,         $iCodigoObra, "Nome construtor inv�lido",              55, false);
        $oLinha2->construtor_endereco                  = validaDados($oObra->enderecoconstrutor,     $iCodigoObra, "Endere�o do construtor inv�lido",       55, false);
        $oLinha2->construtor_bairro                    = validaDados($oObra->bairroconstrutor,       $iCodigoObra, "Bairro do construtor inv�lido",         20, false);
        $oLinha2->construtor_cep                       = validaDados($oObra->cepconstrutor,          $iCodigoObra, "CEP do construtor inv�lido",             8, false);
        $oLinha2->construtor_uf                        = validaDados($oObra->ufconstrutor,           $iCodigoObra, "UF do construtor inv�lido",              2, false);
        $oLinha2->construtor_codigo_sinpas             = validaDados($iCodigoSinpasConstrutor,       $iCodigoObra, "C�dido SINPAS do munic�pio do construtor inv�lido",  6, false, "LS");

        $oLinha2->obra_numero_alvara                   = validaDados($oObra->alvaraobra,             $iCodigoObra, "N�mero alvar� inv�lido",                12,  true, "L"); 
        $oLinha2->obra_data_alvara                     = validaDados($sDataObra,                     $iCodigoObra, "Data alvara inv�lida",                   8);
        $oLinha2->obra_nome_obra                       = validaDados($oObra->nomeobra,               $iCodigoObra, "Nome da obra inv�lido",                 55);
        $oLinha2->obra_endereco                        = validaDados($oObra->enderobra,              $iCodigoObra, "Endere�o da obra inv�lido",             55);
        $oLinha2->obra_bairro                          = validaDados($oObra->bairroobra,             $iCodigoObra, "Bairro da obra inv�lido",               20);
        $oLinha2->obra_cep                             = validaDados($sCepObra,                      $iCodigoObra, "CEP da obra inv�lido. Confira cadastro de Logradouro.", 8);
        $oLinha2->obra_uf                              = validaDados($aHeader->uf,                   $iCodigoObra, "UF da obra inv�lido",                    2);
        $oLinha2->obra_codigo_sinpas                   = validaDados($iCodInpasInstit,               $iCodigoObra, "C�digo SINPAS do munic�pio da obra inv�lido", 6, true, "LS");
        $oLinha2->obra_ddd_telefone                    = str_repeat(" ", 4);
        $oLinha2->obra_telefone                        = validaDados($oObra->telefoneobra,           $iCodigoObra, "Telefone da obra inv�lido",             12, false);
        $oLinha2->obra_ddd_fax                         = str_repeat(" ", 4);                                       
        $oLinha2->obra_fax                             = validaDados($oObra->faxobra,                $iCodigoObra, "Fax da obra inv�lido",                  12, false);
        $oLinha2->obra_data_inicio                     = validaDados($sDataInicioObra,               $iCodigoObra, "Data de inicio da obra inv�lida",        8);
        $oLinha2->obra_data_fim                        = $sDataFimObra;

// echo " $oObra->tipoocupacaoacrescimo -- $oObra->tipoacrescimo -- $iAreaAcrescimo -- $iAreaExistente ";

        $oLinha2->obra_tipo_ocupacao_acrescimo   = validaDados($oObra->tipoocupacaoacrescimo,  $iCodigoObra, "Tipo ocupa��o de acrescimo inv�lido",    1, false, "L");
        $oLinha2->obra_tipo_acrescimo            = validaDados($oObra->tipoacrescimo,          $iCodigoObra, "Tipo acrescimo inv�lido",                1, false, "L");
        $oLinha2->obra_area_acrescimo            = $iAreaAcrescimo;
        $oLinha2->obra_area_existente            = $iAreaExistente;
        $oLinha2->obra_tipo_ocupacao_construcao  = validaDados($oObra->tipoocupacaoobra,       $iCodigoObra, "Tipo de ocupa��o da obra inv�lido",      1, false, "L");
        $oLinha2->obra_tipo_construcao           = validaDados($oObra->tipoconstrucaoobra,     $iCodigoObra, "Tipo de constru��o da obra inv�lido",    1, false, "L");
        $oLinha2->obra_area_construcao           = $iAreaConstrucao;
        $oLinha2->obra_tipo_ocupacao_demolicao   = validaDados($oObra->tipoocupacaodemolicao,  $iCodigoObra, "Tipo de ocupa��o de demoli��o inv�lido",  1, false, "L");
        $oLinha2->obra_tipo_demolicao            = validaDados($oObra->tipodemolicao,          $iCodigoObra, "Tipo de demoli��o inv�lido",             1, false, "L");
        $oLinha2->obra_area_demolida             = $iAreaDemolicao;
        $oLinha2->obra_tipo_ocupaco_reforma      = validaDados($oObra->tipoocupacaoreforma,    $iCodigoObra, "Tipo ocupa��o da reforma inv�lido",      1, false, "L");
        $oLinha2->obra_tipo_reforma              = validaDados($oObra->tiporeforma,            $iCodigoObra, "Tipo de reforma inv�lido",               1, false, "L");
        $oLinha2->obra_area_reforma              = str_replace("." , "", $iAreaReforma);

        if ( ! empty($iAreaAcrescimo) && $iAreaAcrescimo > 0 ) {

          $oLinha2->obra_tipo_ocupacao_construcao  = ""; 
          $oLinha2->obra_tipo_construcao           = ""; 
          $oLinha2->obra_area_construcao           = ""; 
          $oLinha2->obra_tipo_ocupacao_demolicao   = "";
          $oLinha2->obra_tipo_demolicao            = "";
          $oLinha2->obra_area_demolida             = "";
          $oLinha2->obra_tipo_ocupaco_reforma      = "";
          $oLinha2->obra_tipo_reforma              = "";
          $oLinha2->obra_area_reforma              = "";
          
        } else if ( ! empty($iAreaReforma) && $iAreaReforma > 0 ) {

          $oLinha2->obra_tipo_ocupacao_acrescimo   = "";
          $oLinha2->obra_tipo_acrescimo            = "";
          $oLinha2->obra_area_acrescimo            = "";
          $oLinha2->obra_tipo_ocupacao_construcao  = ""; 
          $oLinha2->obra_tipo_construcao           = ""; 
          $oLinha2->obra_area_construcao           = ""; 
          $oLinha2->obra_tipo_ocupacao_demolicao   = "";
          $oLinha2->obra_tipo_demolicao            = "";
          $oLinha2->obra_area_demolida             = "";
        } else if ( ! empty($iAreaDemolicao) && $iAreaDemolicao > 0 ) {

          $oLinha2->obra_tipo_ocupacao_acrescimo   = "";
          $oLinha2->obra_tipo_acrescimo            = "";
          $oLinha2->obra_area_acrescimo            = "";
          $oLinha2->obra_tipo_ocupacao_construcao  = ""; 
          $oLinha2->obra_tipo_construcao           = ""; 
          $oLinha2->obra_area_construcao           = ""; 
          $oLinha2->obra_tipo_ocupaco_reforma      = "";
          $oLinha2->obra_tipo_reforma              = "";
          $oLinha2->obra_area_reforma              = "";
        } else if ( ! empty($iAreaConstrucao) && $iAreaConstrucao > 0 ) {

          $oLinha2->obra_tipo_ocupacao_acrescimo   = "";
          $oLinha2->obra_tipo_acrescimo            = "";
          $oLinha2->obra_area_acrescimo            = "";
          $oLinha2->obra_tipo_ocupaco_reforma      = "";
          $oLinha2->obra_tipo_reforma              = "";
          $oLinha2->obra_area_reforma              = "";
          $oLinha2->obra_tipo_ocupacao_demolicao   = "";
          $oLinha2->obra_tipo_demolicao            = "";
          $oLinha2->obra_area_demolida             = "";
          $oLinha2->obra_area_existente            = "";
        }

        $oLinha2->numeros_unidades               = validaDados($oObra->iunidades,              $iCodigoObra, "Sem unidades para obra",                 5, true , "L");
        $oLinha2->numero_pavimentos              = validaDados($oObra->ipavimentos,            $iCodigoObra, "Sem pavdimentos para a Obra",            5, true , "L");
				

        if( $oLayoutTxt->setByLineOfDBUtils($oLinha2, 3, "2") == false ) {
        	throw new Exception (_M('tributario.projetos.pro4_gerarTxtINSS.erro_gerar_linha_responsavel'));
        }
         
        /**
         * Registros do habite-se, e nao haver habite-se, ignora a linha
         */
        if ($oObra->numerohabitese != "" || !empty($oObra->numerohabitese)) {

        	if (!empty($oObra->areahabitese)) {
        		$iAreaHabite    =  validaDados(str_replace($aSubstituir , "",db_formatar($oObra->areahabitese, 'f')),  $iCodigoObra, "�rea do Habite-se inv�lida", 8, false, "L");
        	} else {
        		$iAreaHabite    = str_repeat(' ' , 8);
        	}
        	$dtHabite = str_replace('-', '', $oObra->dataobra);       	
        	        	
        	$oLinha3 = new stdClass();
        	$oLinha3->identificador = 3;
        	$oLinha3->numero_habite = validaDados($oObra->numerohabitese, $iCodigoObra, "N�mero do habite-se inv�lido", 1);
        	$oLinha3->data_habite   = validaDados($dtHabite,              $iCodigoObra, "Data do habite-se inv�lida",   8); 
        	$oLinha3->area_habite   = $iAreaHabite;
        	$oLinha3->tipo_habite   = validaDados($oObra->tipohabitese,   $iCodigoObra, "Tipo de habite-se inv�lido",   1);

        	$oDadosObraGeracao->ob18_codhabite   = $oObra->codigohabitese;
        	
        	if( $oLayoutTxt->setByLineOfDBUtils($oLinha3, 3, "3") == false ) {
        		throw new Exception (_M('tributario.projetos.pro4_gerarTxtINSS.erro_gerar_linha_habitese'));
        	}
        	
        	/**
        	 * Incrementado pois validador conta numero de linhas como quantidade de registros
        	 */
        	$iNumeroLinhas++;
        }

        $aDadosGeracao[]   = $oDadosObraGeracao;
      }

      
      /**
       * Trailer do arquivo
       */      
			$oTrailer = new stdClass();
			$oTrailer->identificador         = 4;
      $oTrailer->quantidades_registros     = str_pad( (int)$iNumeroLinhas, 6, "0", STR_PAD_LEFT);

      if( $oLayoutTxt->setByLineOfDBUtils($oTrailer, 5, "4") == false ) {
        throw new Exception (_M('tributario.projetos.pro4_gerarTxtINSS.erro_gerar_linha_fechamento_lote'));
      }           
      

    	/**
    	 * se haver erro ou aviso criamos ele na se��o para o relatorio
    	 * e a variavel $iInconsistencia passa para 1
    	 */
    	$aInconsistencia  = db_getsession("aInconsistencia");
    	rsort($aInconsistencia);
    	
    	if (count($aInconsistencia) > 0) {
        $iInconsistencia = 1;
      } else {
        $lIncluir        = true;
      }
      
      /**
       * caso o usuario decida imprimir o arqui vo com somente avisos           
       * setamos $iInconsistencia para 0, para nao exibir mais a tela de avisos 
       */
      if ($lAviso == 'true') {
      	
        $iInconsistencia = 0;
        $lIncluir        = true;
      }
      
      if ($lIncluir) {

      	try {
          db_inicio_transacao();
      		/**
      		 * Se nao haver erros e avisos, ou o usuario decidiu imprimir com avisos
      		 * salvamos o arquivo gerado
      		 */
      		$oObrasEnvio->ob16_data    = $dNomeArquivo;
      		$oObrasEnvio->ob16_hora    = $hNomeArquivo;
      		$oObrasEnvio->ob16_login   = db_getsession("DB_id_usuario");
      		$oObrasEnvio->ob16_dtini   = "{$iAno}-{$iMes}-01";
      		$oObrasEnvio->ob16_dtfim   = "{$iAno}-{$iMes}-{$iUltimoDiaMes}";
      		$oObrasEnvio->ob16_nomearq = $pArquivoTxt;
      		$oObrasEnvio->ob16_arq     = file_get_contents($pArquivoTxt);
      		$oObrasEnvio->incluir(null);
      		if ( (int)$oObrasEnvio->erro_status == 0 ) {
      			throw new Exception ($oObrasEnvio->erro_msg);
      		}

      		/*
      		 * percorremos o array de obras geradas para as inclusoes
      	 */
      		foreach ($aDadosGeracao as $oObrasGeradas) {

      			$oObrasEnvioReg->ob17_codobrasenvio = $oObrasEnvio->ob16_codobrasenvio;
      			$oObrasEnvioReg->ob17_codobra       = $oObrasGeradas->ob17_codobra;
      			$oObrasEnvioReg->incluir(null);
      			if ( (int)$oObrasEnvioReg->erro_status == 0 ) {
      				throw new Exception ($oObrasEnvioReg->erro_msg);
      			}

      			/**
      			 * Caso houver habite-se na competencia tenta incluir
      			 */
      			if ( isset($oObrasGeradas->ob18_codhabite) ) {
      				 
      				$oObrasEnvioRegHab->ob18_codobraenvioreg = $oObrasEnvioReg->ob17_codobrasenvioreg;
      				$oObrasEnvioRegHab->ob18_codhabite       = $oObrasGeradas->ob18_codhabite;
      				
      				$oObrasEnvioRegHab->incluir(null);
      				if ( (int)$oObrasEnvioRegHab->erro_status == 0 ) {
      					throw new Exception ($oObrasEnvioRegHab->erro_msg);
      				}
      			}
      		}
      		db_fim_transacao(false);
      	} catch (Exception $eErroBanco) {
      	  
      		db_fim_transacao(true);
      		$oParms = new stdClass();
      		$oParms->sErro = $eErroBanco->getMessage();
      		throw new Exception(_M('tributario.projetos.pro4_gerarTxtINSS.erro_processar_arquivo', $oParms));
      	}
      }

      $oRetorno->sArquivo         = $pArquivoTxt;
      $oRetorno->aErros           = $aInconsistencia;
      $oRetorno->iInconsistencia  = $iInconsistencia;

    break;
    
    default:
      throw new Exception(_M('tributario.projetos.pro4_gerarTxtINSS.definia_opcao'));
    break;
  }
  $oRetorno->sMessage = urlencode($oRetorno->sMessage);
  echo $oJson->encode($oRetorno);
  
} catch (Exception $eErro){
  
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);
}

?>