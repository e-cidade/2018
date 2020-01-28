<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
 * Model referente a rotina do MEI ( Micro Empreendedor Individual )
 * @package issqn
 * @author Felipe Nunes Ribeiro 
 * @revision $Author: dbfelipe $
 * @version $Revision: 1.1 $
 */
class Mei {
  
	private $sCodSIAFI = '';
	
  function __construct() {

  	
    $oDaoDBConfig = db_utils::getDao('db_config');

    /**
     *  Consulta do código SIAFI do município
     */
    $rsSIAFI = $oDaoDBConfig->sql_record($oDaoDBConfig->sql_query_siafi(db_getsession('DB_instit'),'q110_codigo'));
   
    if ( $oDaoDBConfig->numrows > 0 ) {
      $this->sCodSIAFI = db_utils::fieldsMemory($rsSIAFI,0)->q110_codigo;
    } else {
      throw new Exception("Erro: Código SIAFI não encontrado!"); 
    }
  	
    
  }

  /**
   * Método de importação do Aquivo txt MEI
   *
   * @param string $sNomeArquivo    
   * @param string $sCaminhoArquivo
   */
  function importaArquivo($sNomeArquivo='',$sCaminhoArquivo='') {
  	
  	$sMsgErro = "Importação de Arquivo MEI abortada!";
  	
    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}, nenhuma transação encontrada!");
    }
  	
    if ( trim($sNomeArquivo) == '' ) {
  	  throw new Exception("{$sMsgErro}, nome do arquivo não informado!");
    }
    
    if ( trim($sCaminhoArquivo) == '' ) {
      throw new Exception("{$sMsgErro}, caminho do arquivo não informado!");
    }    
    
    $oParIssqn                            = db_utils::getDao('parissqn');
    $oMeiImporta                          = db_utils::getDao('meiimporta');
    $oMunicipioSIAFI                      = db_utils::getDao('municipiosiafi');
    $oMeiImportaLinha                     = db_utils::getDao('meiimportalinha');
    $oMeiImportaLinhaEmpresa              = db_utils::getDao('meiimportalinhaempresa');
    $oMeiImportaLinhaEmpresaMeiEvento     = db_utils::getDao('meiimportalinhaempresameievento');
    $oMeiImportaLinhaContador             = db_utils::getDao('meiimportalinhacontador');
    $oMeiImportaLinhaContadorMeiEvento    = db_utils::getDao('meiimportalinhacontadormeievento');
    $oMeiImportaLinhaAtividade            = db_utils::getDao('meiimportalinhaatividade');
    $oMeiImportaLinhaAtividadeMeiEvento   = db_utils::getDao('meiimportalinhaatividademeievento');
    $oMeiImportaLinhaResponsavel          = db_utils::getDao('meiimportalinharesponsavel');
    $oMeiImportaLinhaResponsavelMeiEvento = db_utils::getDao('meiimportalinharesponsavelmeievento');
    
    /**
     *  Valida a data de geração do arquivo pelo nome do arquivo
     */
   	$aNomeArquivo = explode("-",$sNomeArquivo);
   	$sDataArquivo = $aNomeArquivo[3];

   	if ( strlen(trim($sDataArquivo)) != 8 ) {
      $sMsgErro .= "\\nData do arquivo inválida!";
      throw new Exception($sMsgErro);              		
   	}
   	
   	$iAnoArquivo = substr($sDataArquivo,0,4);
   	$iMesArquivo = substr($sDataArquivo,4,2);

    /**
     *  Verifica se já foi processado arquivo da competencia atual
     */
    $sWhereUltimaImp  = "     q104_anousu =  {$iAnoArquivo}";
    $sWhereUltimaImp .= " and q104_mesusu = '{$iMesArquivo}'";
    $sSqlUltimaImp    = $oMeiImporta->sql_query_file(null,"*",null,$sWhereUltimaImp);
    $rsUltimaImp      = $oMeiImporta->sql_record($sSqlUltimaImp);   	
   	
    if ( $oMeiImporta->numrows > 0 ) {
	    $sMsgErro .= "\\nArquivo de competência {$iMesArquivo}/{$iAnoArquivo} já processado!";
	    throw new Exception($sMsgErro);           
    }

    /**
     *  Consulta data de implantação do MEI
     */
    $rsParIssqn = $oParIssqn->sql_record($oParIssqn->sql_query_file(null,"q60_dataimpmei",null,"q60_dataimpmei is not null"));
    
    if ( $oParIssqn->numrows > 0 ) {
    	
    	$dtDataImpMei = db_utils::fieldsMemory($rsParIssqn,0)->q60_dataimpmei;
    	list($iAnoDataImpMei,$iMesDataImpMei,$iDiaDataImpMei) = explode("-",$dtDataImpMei);
    	
      /*
       *  Verifica se existe registro da data de implantação do MEI   
       */
      $sWhereUltimaImp  = "     q104_anousu =  {$iAnoDataImpMei}";
      $sWhereUltimaImp .= " and q104_mesusu = '{$iMesDataImpMei}'";
      $sSqlUltimaImp    = $oMeiImporta->sql_query_file(null,"*",null,$sWhereUltimaImp);
      $rsUltimaImp      = $oMeiImporta->sql_record($sSqlUltimaImp);    	
    	
    	
    	if ( $oMeiImporta->numrows > 0 ) {
    		
		    /**
		     * Calcula a competencia de processamento anterior
		     */
		    if ( $iMesArquivo == 01 ) {
		      $iMesUltimaImp = 12;
		      $iAnoUltimaImp = ($iAnoArquivo-1);
		    } else {
		      $iMesUltimaImp = ($iMesArquivo-1);
		      $iAnoUltimaImp = $iAnoArquivo;
		    }
		    
		    /**
		     *  Verifica se foi processado o arquivo referente a competencia anterior
		     */
		    $sWhereUltimaImp  = "     q104_anousu =  {$iAnoUltimaImp}";
		    $sWhereUltimaImp .= " and q104_mesusu = '{$iMesUltimaImp}'";
		    $sSqlUltimaImp    = $oMeiImporta->sql_query_file(null,"*",null,$sWhereUltimaImp);
		    $rsUltimaImp      = $oMeiImporta->sql_record($sSqlUltimaImp);    		
    		
        if ( $oMeiImporta->numrows ==  0 ) {    		
	    		$sMsgErro .= "\\nArquivo de competência {$iMesUltimaImp}/{$iAnoUltimaImp} não processado!";
		      throw new Exception($sMsgErro);
        }
        
      } else {
      	/**
       	 *  Caso não tenha sido processado algum arquivo então é verificado se a data 
       	 *  do arquivo corresponde a data de implantação do MEI 
       	 */
        if ( $iAnoArquivo != $iAnoDataImpMei || $iMesArquivo != $iMesDataImpMei ) {
	        $sMsgErro .= "\\nCompetência do arquivo difere da implantação!";
	        throw new Exception($sMsgErro);         
        } 
    	}
    	
    } else {
    	throw new Exception("{$sMsgErro},\\nParâmentros de ISSQN não configurados!");
    }
   	
    
    /**
     *  Classe que transforma o arquivo em um array de objeto apartir do cadastro de layout
     */
    try {
	    $oDBLayoutReader = new DBLayoutReader(84,$sCaminhoArquivo);   
	    $aLinhasArquivo  = $oDBLayoutReader->getLines();
    } catch ( Exception $eException ) {
      throw new Exception("{$sMsgErro}\\n{$eException->getMessage()}");
    }
    
    
    /**
     * 
     * Estrutura de dados do Array, que agrupa as informações do arquivo por CNPJ do MEI e Eventos 
     *  
     * $aDadosArquivo['CNPJ_MEI']['sRecibo']                                 = $sRecibo;      -- Recibo de Solicitação
     * $aDadosArquivo['CNPJ_MEI']['aEventos']['sCodEvento']['aAtividades'][] = $oAtividade;   -- Array com todas Atividades
     * $aDadosArquivo['CNPJ_MEI']['aEventos']['sCodEvento']['oEmpresa']      = $oEmpresa;     -- Dados da Empresa
     * $aDadosArquivo['CNPJ_MEI']['aEventos']['sCodEvento']['oResponsavel']  = $oResponsavel; -- Dados do Responsável
     * $aDadosArquivo['CNPJ_MEI']['aEventos']['sCodEvento']['oContador']     = $oContador;    -- Dados do Contador ou Escritório Contábil
     * 
     */
    
    $aDadosArquivo   = array();
    $aCodSIAFI       = array();
     
    
    foreach ( $aLinhasArquivo as $iIndLinha => $oLinha ) {

    	
      if ( $oLinha->co_convenio != $this->sCodSIAFI ) {
      	$sMsgErro .= "\\nCódigo do município do arquivo TXT inválido ( Linha: ".($iIndLinha+1)." ). Contate suporte!";
        throw new Exception($sMsgErro);
      }
      
      
      /**
       *  Percorre os 8 eventos de cada linha
       */
      for ( $iIndEvent=1; $iIndEvent <= 8; $iIndEvent++ ) {
          
      	/**
      	 *  Caso o convênio esteja em branco deve pular para o próximo registro
      	 */
        if ( trim($oLinha->{"co_evento".$iIndEvent}) == '' ) {
          continue;
        } else {
            
        	
          $oEvento = new stdClass();
          $oEvento->sCodigo = $oLinha->{"co_evento".$iIndEvent};
          $oEvento->dtData  = $oLinha->{"dt_evento".$iIndEvent};
          $oEvento->sTipo   = $oLinha->{"tp_evento".$iIndEvent};
          
          /**
           *  Linha do arquivo referente aos dados cadastrais da Empresa   
           */
          if ( $oLinha->co_tipo_registro == '01' ) {
                  	
          	/**
          	 *  Dados referente a empresa do MEI 
          	 */
          	if ( trim($oLinha->nm_empresarial_01) != '' || ((int)$oEvento->sCodigo >= 500 && (int)$oEvento->sCodigo <= 599)) {
          		
	          	$oEmpresa = new stdClass();
	          	$oEmpresa->sNome              = $oLinha->nm_empresarial_01;
	          	$oEmpresa->sCnpj              = $oLinha->nu_cnpj;
	          	$oEmpresa->sCnpjMatriz        = $oLinha->nu_cnpj_estabelecimento_matriz;
	          	$oEmpresa->nCapitalSocial     = $oLinha->nu_capital_social;
	          	$oEmpresa->sNomeFantasia      = $oLinha->nm_fantasia;
	          	$oEmpresa->sTipoLogradouro    = $oLinha->co_tipo_logradouro;
	          	$oEmpresa->sLogradouro        = $oLinha->nm_logradouro;
	          	$oEmpresa->sNumero            = $oLinha->nu_logradouro;
	          	$oEmpresa->sComplemento       = $oLinha->nm_complemento_logradouro;
	          	$oEmpresa->sBairro            = addslashes($oLinha->nm_bairro);
	          	$oEmpresa->iMunicipio         = (trim($oLinha->co_municipio) != ''?$oLinha->co_municipio:0);
	          	$oEmpresa->sUF                = $oLinha->nm_uf;
	          	$oEmpresa->sCep               = $oLinha->nu_cep;
	          	$oEmpresa->sReferencia        = $oLinha->nm_referencia;
	          	$oEmpresa->sTelefone          = $oLinha->nu_ddd_telefone_1." ".$oLinha->nu_telefone_1;
	          	$oEmpresa->sTelefoneComercial = $oLinha->nu_ddd_telefone_2." ".$oLinha->nu_telefone_2;
	          	$oEmpresa->sFax               = $oLinha->nu_fax." ".$oLinha->nu_fax;
	          	$oEmpresa->sEmail             = $oLinha->nm_correio_eletronico;
	          	$oEmpresa->sCaixaPostal       = $oLinha->nu_caixa_postal;
	          	$oEmpresa->sInscrMei          = $oLinha->in_inscricao_mei;

	            $aDadosArquivo[$oLinha->nu_cnpj]['aEventos'][$oEvento->sCodigo]['oEmpresa'] = $oEmpresa;
	            
	            $aCodSIAFI[] = $oLinha->co_municipio;
	            
          	}
            
          	/**
          	 *  Dados referente ao responsável pela empresa do MEI
          	 */
            if ( trim($oLinha->nm_responsavel) != '' ) {
            	
	          	$oResponsavel = new stdClass();
	          	$oResponsavel->sNome            = $oLinha->nm_responsavel;
	          	$oResponsavel->sCpf             = $oLinha->nu_cpf_responsavel;
	            $oResponsavel->sTipoLogradouro  = $oLinha->co_tipo_logradouro_responsavel;
	            $oResponsavel->sLogradouro      = $oLinha->nm_logradouro_responsavel;
	            $oResponsavel->sNumero          = $oLinha->nu_logradouro_responsavel;
	            $oResponsavel->sComplemento     = $oLinha->nm_complemento_logradouro_responsavel;
	            $oResponsavel->sBairro          = addslashes($oLinha->nm_bairro_responsavel);
	            $oResponsavel->iMunicipio       = $oLinha->co_municipio_responsavel;
	            $oResponsavel->sUF              = $oLinha->co_uf_responsavel;
	            $oResponsavel->sCep             = $oLinha->nu_cep_responsavel;
	            $oResponsavel->sTelefone        = $oLinha->nu_ddd_telefone_responsavel." ".$oLinha->nu_telefone_responsavel;
	            $oResponsavel->sFax             = $oLinha->nu_ddd_fax_responsavel." ".$oLinha->nu_fax_responsavel;
	            $oResponsavel->sEmail           = $oLinha->nm_correio_eletronico_responsavel;            
	          	
	          	$aDadosArquivo[$oLinha->nu_cnpj]['aEventos'][$oEvento->sCodigo]['oResponsavel'] = $oResponsavel;
	          	
	          	$aCodSIAFI[] = $oLinha->co_municipio_responsavel;
	          	
            }
          	
          	/**
          	 *  Caso tenha no arquivo empresa contábil e contador deve ser gravado 
          	 *  na tabela escrito apenas o cadastro do contador
          	 */
          	if ( trim($oLinha->nu_seq_contador_pf) != '' ) {
          		
	            $oContador = new stdClass();
	            $oContador->sUfCrc           = $oLinha->nm_uf_contador_pf;
	            $oContador->sCodCrc          = $oLinha->nu_seq_contador_pf;
	            $oContador->dtDataCrc        = $oLinha->dt_registro_crc_contador_pf;
	            $oContador->sCnpjCpf         = $oLinha->nu_cpf_contador_pf;
	            $oContador->sNome            = $oLinha->nm_contador_pf;
	            $oContador->sTipoLogradouro  = $oLinha->co_tipo_logradouro_contador_pf;
	            $oContador->sLogradouro      = $oLinha->nm_logradouro_contador_pf;
	            $oContador->sNumero          = $oLinha->nu_logradouro_contador_pf;
	            $oContador->sComplemento     = $oLinha->nm_complemento_logradouro_contador_pf;
	            $oContador->sBairro          = addslashes($oLinha->nm_bairro_contador_pf);
	            $oContador->iMunicipio       = $oLinha->co_municipio_contador_pf;
	            $oContador->sUF              = $oLinha->nm_uf_contador_pf;
	            $oContador->sCep             = $oLinha->nu_cep_contador_pf;
	            $oContador->sTelefone        = $oLinha->nu_ddd_telefone_contador_pf." ".$oLinha->nu_telefone_contador_pf;
	            $oContador->sFax             = $oLinha->nu_ddd_fax_contador_pf." ".$oLinha->nu_fax_contador_pf;
	            $oContador->sEmail           = $oLinha->nm_correio_eletronico_contador_pf;

	            $aDadosArquivo[$oLinha->nu_cnpj]['aEventos'][$oEvento->sCodigo]['oContador'] = $oContador;
	            
	            $aCodSIAFI[] = $oLinha->co_municipio_contador_pf;
	            
          	} else if ( trim($oLinha->nu_seq_crc_empresa_contabil) != '' ) {
          		
              $oContador = new stdClass();
              $oContador->sUfCrc           = $oLinha->nm_uf_crc_empresa_contabil;
              $oContador->sCodCrc          = $oLinha->nu_seq_crc_empresa_contabil;
              $oContador->dtDataCrc        = $oLinha->dt_registro_crc_empresa_contabil;
              $oContador->sCnpjCpf         = $oLinha->nu_cnpj_empresa_contabil;
              $oContador->sNome            = $oLinha->nm_empresa_contabil;
              $oContador->sTipoLogradouro  = $oLinha->co_tipo_logradouro_empresa_contabil_complementar;
              $oContador->sLogradouro      = $oLinha->nm_logradouro_empresa_contabil_complementar;
              $oContador->sNumero          = $oLinha->nu_logradouro_empresa_contabil_complementar;
              $oContador->sComplemento     = $oLinha->nm_complemento_logradouro_empresa_contabil_complementar;
              $oContador->sBairro          = addslashes($oLinha->nm_bairro_empresa_contabil_complementar);
              $oContador->iMunicipio       = $oLinha->co_municipio_empresa_contabil_complementar;
              $oContador->sUF              = $oLinha->co_uf_empresa_contabil_complementar;
              $oContador->sCep             = $oLinha->nu_cep_empresa_contabil_complementar;
              $oContador->sTelefone        = $oLinha->nu_ddd_telefone_empresa_contabil_complementar." ".$oLinha->nu_telefone_empresa_contabil_complementar;
              $oContador->sFax             = '';
              $oContador->sEmail           = '';
              
	          	$aDadosArquivo[$oLinha->nu_cnpj]['aEventos'][$oEvento->sCodigo]['oContador'] = $oContador;
          		
              $aCodSIAFI[] = $oLinha->co_municipio_empresa_contabil_complementar;
              
          	}
          	
          	
          /**
           *  Linha do arquivo referente as Atividades da Empresa  
           */	
		      } else if ( $oLinha->co_tipo_registro == '04' ) {
		  
		        for ( $iIndAtiv=1; $iIndAtiv <= 99; $iIndAtiv++ ) {
		              
		          $oAtividade = new stdClass();
		          
		          /*
		           *  No arquivo a atividade primária fica em local diferente das atividades secundárias
		           */
		          if ( $iIndAtiv == 1 ) {
		            $sCnae      = $oLinha->co_cnae_fiscal;
		            $lPrincipal = 'true';
		          } else {
		            $sCnae      = $oLinha->{"co_cnae_fiscal_secundaria".$iIndAtiv};
		            $lPrincipal = 'false';
		          }
		
		          if ( trim($sCnae) == '' ) {
		            continue;
		          }
		          		              
		          $oAtividade->sCnae      = $sCnae;  
		          $oAtividade->sDescricao = $oLinha->{"nm_objeto_social".$iIndAtiv};
		          $oAtividade->lPrincipal = $lPrincipal;
		
			        $aDadosArquivo[$oLinha->nu_cnpj]['aEventos'][$oEvento->sCodigo]['aAtividades'][] = $oAtividade;
		              
		        }
		      }
		      
		      $aDadosArquivo[$oLinha->nu_cnpj]['aEventos'][$oEvento->sCodigo]['oEvento'] = $oEvento;
		      $aDadosArquivo[$oLinha->nu_cnpj]['sRecibo'] = $oLinha->nu_recibo_solicitacao;
		      
        }
      }
    }

    
    /**
     *  Cria um array contendo todas as descrições dos municipios SIAFI 
     *  utilizado no arquivo txt, pois nele é apenas informado o código
     */
    $aDescrSIAFI = array();
    
    if ( count($aCodSIAFI) > 0 ) {
    	
    	$sListaSIAFI = implode("','",array_unique($aCodSIAFI));
    	
    	$sCamposSIAFI     = " q110_codigo,   ";
    	$sCamposSIAFI    .= " q110_descricao ";
    	$sWhereSIAFI      = " q110_codigo in ('{$sListaSIAFI}')";

    	$sSqlSIAFI        = $oMunicipioSIAFI->sql_query_file(null,$sCamposSIAFI,"q110_codigo",$sWhereSIAFI);
    	$rsMunicipioSIAFI = $oMunicipioSIAFI->sql_record($sSqlSIAFI);
    	$iLinhasSIAFI     = pg_num_rows($rsMunicipioSIAFI); 
    	
    	$aDescrSIAFI['0'] = '';
          	
    	for ( $iIndSIAFI=0; $iIndSIAFI < $iLinhasSIAFI; $iIndSIAFI++ ) {

    		$oSIAFI = db_utils::fieldsMemory($rsMunicipioSIAFI,$iIndSIAFI);
    		$aDescrSIAFI[$oSIAFI->q110_codigo] = $oSIAFI->q110_descricao; 
    		
    	}
    	
    }
    
    /**
     *  Gera OID para gravação do arquivo na base
     */
    $oidGrava       = pg_lo_create();
    $sStringArquivo = file_get_contents($sCaminhoArquivo);
   
    if ( !$sStringArquivo ) {
      throw new Exception("{$sMsgErro},\\nFalha ao abrir o arquivo [{$sCaminhoArquivo}].") ;        
    }

    $oLargeObject = pg_lo_open($oidGrava, "w");
   
    if (!$oLargeObject) {
      throw new Exception("{$sMsgErro}\\nFalha ao buscar objeto do banco de dados") ;
    }

    $lObjetoEscrito = pg_lo_write($oLargeObject,$sStringArquivo);
  
    if (!$lObjetoEscrito) {
   	  throw new Exception("{$sMsgErro},\\nFalha na escrita do objedo no banco de dados") ;
    }

    pg_lo_close($oLargeObject);

    
    $oMeiImporta->q104_anousu     = $iAnoArquivo; 
    $oMeiImporta->q104_mesusu     = $iMesArquivo;
    $oMeiImporta->q104_id_usuario = db_getsession('DB_id_usuario');
    $oMeiImporta->q104_arquivo    = $oidGrava;
    $oMeiImporta->q104_nomearq    = $sNomeArquivo;
    $oMeiImporta->q104_xml        = '';
   
    $oMeiImporta->incluir(null);   
   
    if ($oMeiImporta->erro_status == 0){
      throw new Exception("{$sMsgErro}\\n{$oMeiImporta->erro_msg}") ;
    }

    /**
     *  Insere nas tabelas filhas apartir do array $aDadosArquivo 
     */
    foreach ( $aDadosArquivo as $iCnpj => $oDadosEmpresa ) {
    	
    	$oMeiImportaLinha->q105_meiimporta        = $oMeiImporta->q104_sequencial;
    	$oMeiImportaLinha->q105_cnpj              = $iCnpj;
    	$oMeiImportaLinha->q105_recibosolicitacao = $oDadosEmpresa['sRecibo'];
    	
      $oMeiImportaLinha->incluir(null);   
   
	    if ($oMeiImportaLinha->erro_status == 0){
	      throw new Exception("{$sMsgErro}\\n{$oMeiImportaLinha->erro_msg}") ;
	    }    	
	    
    	foreach ( $oDadosEmpresa['aEventos'] as $sCodEvento => $aDadosEvento ) {

    		
    		if ( isset($aDadosEvento['oEmpresa']) ){

    			$oEmpresa = $aDadosEvento['oEmpresa'];
    			
				  $oMeiImportaLinhaEmpresa->q107_meiimportalinha   = $oMeiImportaLinha->q105_sequencial;    
				  $oMeiImportaLinhaEmpresa->q107_municipio         = $aDescrSIAFI[$oEmpresa->iMunicipio];
				  $oMeiImportaLinhaEmpresa->q107_cnpj              = $oEmpresa->sCnpj;
				  $oMeiImportaLinhaEmpresa->q107_cnpjmatriz        = $oEmpresa->sCnpjMatriz;   
				  $oMeiImportaLinhaEmpresa->q107_nome              = $oEmpresa->sNome;   
				  $oMeiImportaLinhaEmpresa->q107_capitalsocial     = $oEmpresa->nCapitalSocial;  
				  $oMeiImportaLinhaEmpresa->q107_nomefantasia      = $oEmpresa->sNomeFantasia;
				  $oMeiImportaLinhaEmpresa->q107_tipologradouro    = $oEmpresa->sTipoLogradouro; 
				  $oMeiImportaLinhaEmpresa->q107_logradouro        = $oEmpresa->sLogradouro;
				  $oMeiImportaLinhaEmpresa->q107_numero            = $oEmpresa->sNumero; 
				  $oMeiImportaLinhaEmpresa->q107_complemento       = $oEmpresa->sComplemento;
				  $oMeiImportaLinhaEmpresa->q107_bairro            = $oEmpresa->sBairro;
				  $oMeiImportaLinhaEmpresa->q107_uf                = $oEmpresa->sUF;
				  $oMeiImportaLinhaEmpresa->q107_cep               = $oEmpresa->sCep;
				  $oMeiImportaLinhaEmpresa->q107_referencia        = $oEmpresa->sReferencia;
				  $oMeiImportaLinhaEmpresa->q107_telefone          = $oEmpresa->sTelefone;
				  $oMeiImportaLinhaEmpresa->q107_telefonecomercial = $oEmpresa->sTelefoneComercial;
				  $oMeiImportaLinhaEmpresa->q107_fax               = $oEmpresa->sFax;
				  $oMeiImportaLinhaEmpresa->q107_email             = $oEmpresa->sEmail;
				  $oMeiImportaLinhaEmpresa->q107_caixapostal       = $oEmpresa->sCaixaPostal;

				  if ( trim($oEmpresa->sInscrMei) == 'S' ) {
				    $oMeiImportaLinhaEmpresa->q107_inscrmei = 'true';
				  } else {
				  	$oMeiImportaLinhaEmpresa->q107_inscrmei = 'false';
				  }

				  
 		      $oMeiImportaLinhaEmpresa->incluir(null);
   
		      if ($oMeiImportaLinhaEmpresa->erro_status == 0){
		        throw new Exception("{$sMsgErro}\\n{$oMeiImportaLinhaEmpresa->erro_msg}") ;
		      }

		      $oMeiImportaLinhaEmpresaMeiEvento->q112_meiimportalinhaempresa = $oMeiImportaLinhaEmpresa->q107_sequencial;
		      $oMeiImportaLinhaEmpresaMeiEvento->q112_meievento              = $aDadosEvento['oEvento']->sCodigo;
    		  $oMeiImportaLinhaEmpresaMeiEvento->q112_data                   = $aDadosEvento['oEvento']->dtData;
		      $oMeiImportaLinhaEmpresaMeiEvento->q112_processado             = 'false';
		      
		      $oMeiImportaLinhaEmpresaMeiEvento->incluir(null);
		      
    		  if ($oMeiImportaLinhaEmpresaMeiEvento->erro_status == 0){
            throw new Exception("{$sMsgErro}\\n{$oMeiImportaLinhaEmpresaMeiEvento->erro_msg}") ;
          }
    		
    		}
    		
        if ( isset($aDadosEvento['oResponsavel']) ){

        	$oResponsavel = $aDadosEvento['oResponsavel'];
        	
          $oMeiImportaLinhaResponsavel->q108_meiimportalinha = $oMeiImportaLinha->q105_sequencial;    
          $oMeiImportaLinhaResponsavel->q108_municipio       = $aDescrSIAFI[$oResponsavel->iMunicipio];
          $oMeiImportaLinhaResponsavel->q108_cpf             = $oResponsavel->sCpf;   
          $oMeiImportaLinhaResponsavel->q108_nome            = $oResponsavel->sNome;   
          $oMeiImportaLinhaResponsavel->q108_tipologradouro  = $oResponsavel->sTipoLogradouro; 
          $oMeiImportaLinhaResponsavel->q108_logradouro      = $oResponsavel->sLogradouro;
          $oMeiImportaLinhaResponsavel->q108_numero          = $oResponsavel->sNumero; 
          $oMeiImportaLinhaResponsavel->q108_complemento     = $oResponsavel->sComplemento;
          $oMeiImportaLinhaResponsavel->q108_bairro          = $oResponsavel->sBairro;
          $oMeiImportaLinhaResponsavel->q108_uf              = $oResponsavel->sUF;
          $oMeiImportaLinhaResponsavel->q108_cep             = $oResponsavel->sCep;
          $oMeiImportaLinhaResponsavel->q108_telefone        = $oResponsavel->sTelefone;
          $oMeiImportaLinhaResponsavel->q108_fax             = $oResponsavel->sFax;
          $oMeiImportaLinhaResponsavel->q108_email           = $oResponsavel->sEmail;
          
          $oMeiImportaLinhaResponsavel->incluir(null);   
   
          if ($oMeiImportaLinhaResponsavel->erro_status == 0){
            throw new Exception("{$sMsgErro}\\n{$oMeiImportaLinhaResponsavel->erro_msg}") ;
          }

          $oMeiImportaLinhaResponsavelMeiEvento->q113_meiimportalinharesponsavel = $oMeiImportaLinhaResponsavel->q108_sequencial;
          $oMeiImportaLinhaResponsavelMeiEvento->q113_meievento                  = $aDadosEvento['oEvento']->sCodigo;
          $oMeiImportaLinhaResponsavelMeiEvento->q113_data                       = $aDadosEvento['oEvento']->dtData;
          $oMeiImportaLinhaResponsavelMeiEvento->q113_processado                 = 'false';
          
          $oMeiImportaLinhaResponsavelMeiEvento->incluir(null);
          
          if ($oMeiImportaLinhaResponsavelMeiEvento->erro_status == 0){
            throw new Exception("{$sMsgErro}\\n{$oMeiImportaLinhaResponsavelMeiEvento->erro_msg}") ;
          }
        
        }
    		
        if ( isset($aDadosEvento['oContador']) ){

        	$oContador = $aDadosEvento['oContador'];
        	
          $oMeiImportaLinhaContador->q109_meiimportalinha = $oMeiImportaLinha->q105_sequencial;    
          $oMeiImportaLinhaContador->q109_municipio       = $aDescrSIAFI[$oContador->iMunicipio];
          $oMeiImportaLinhaContador->q109_ufcrc           = $oContador->sUfCrc;  
          $oMeiImportaLinhaContador->q109_codigocrc       = $oContador->sCodCrc;
          $oMeiImportaLinhaContador->q109_datacrc         = $oContador->dtDataCrc;
          $oMeiImportaLinhaContador->q109_cnpjcpf         = $oContador->sCnpjCpf;
          $oMeiImportaLinhaContador->q109_nome            = $oContador->sNome;   
          $oMeiImportaLinhaContador->q109_tipologradouro  = $oContador->sTipoLogradouro; 
          $oMeiImportaLinhaContador->q109_logradouro      = $oContador->sLogradouro;
          $oMeiImportaLinhaContador->q109_numero          = $oContador->sNumero; 
          $oMeiImportaLinhaContador->q109_complemento     = $oContador->sComplemento;
          $oMeiImportaLinhaContador->q109_bairro          = $oContador->sBairro;
          $oMeiImportaLinhaContador->q109_uf              = $oContador->sUF;
          $oMeiImportaLinhaContador->q109_cep             = $oContador->sCep;
          $oMeiImportaLinhaContador->q109_telefone        = $oContador->sTelefone;
          $oMeiImportaLinhaContador->q109_fax             = $oContador->sFax;
          $oMeiImportaLinhaContador->q109_email           = $oContador->sEmail;
          
          $oMeiImportaLinhaContador->incluir(null);   
   
          if ($oMeiImportaLinhaContador->erro_status == 0){
            throw new Exception("{$sMsgErro}\\n{$oMeiImportaLinhaContador->erro_msg}") ;
          }

          $oMeiImportaLinhaContadorMeiEvento->q114_meiimportalinhacontador = $oMeiImportaLinhaContador->q109_sequencial;
          $oMeiImportaLinhaContadorMeiEvento->q114_meievento               = $aDadosEvento['oEvento']->sCodigo;
          $oMeiImportaLinhaContadorMeiEvento->q114_data                    = $aDadosEvento['oEvento']->dtData;
          $oMeiImportaLinhaContadorMeiEvento->q114_processado              = 'false';
          
          $oMeiImportaLinhaContadorMeiEvento->incluir(null);
          
          if ($oMeiImportaLinhaContadorMeiEvento->erro_status == 0){
            throw new Exception("{$sMsgErro}\\n{$oMeiImportaLinhaContadorMeiEvento->erro_msg}") ;
          }
        
        }    		
        
        if ( isset($aDadosEvento['aAtividades']) ){

        	foreach ( $aDadosEvento['aAtividades'] as $oAtividade ) {

	          $oMeiImportaLinhaAtividade->q106_meiimportalinha = $oMeiImportaLinha->q105_sequencial;
	          $oMeiImportaLinhaAtividade->q106_cnae            = $oAtividade->sCnae;
	          $oMeiImportaLinhaAtividade->q106_descricao       = $oAtividade->sDescricao;
	          $oMeiImportaLinhaAtividade->q106_principal       = $oAtividade->lPrincipal;
	          
	          $oMeiImportaLinhaAtividade->incluir(null);   
	   
	          if ($oMeiImportaLinhaAtividade->erro_status == 0){
	            throw new Exception("{$sMsgErro}\\n{$oMeiImportaLinhaAtividade->erro_msg}") ;
	          }
	
	          $oMeiImportaLinhaAtividadeMeiEvento->q111_meiimportalinhaatividade = $oMeiImportaLinhaAtividade->q106_sequencial;
	          $oMeiImportaLinhaAtividadeMeiEvento->q111_meievento                = $aDadosEvento['oEvento']->sCodigo;
	          $oMeiImportaLinhaAtividadeMeiEvento->q111_data                     = $aDadosEvento['oEvento']->dtData;
	          $oMeiImportaLinhaAtividadeMeiEvento->q111_processado               = 'false';
	          
	          $oMeiImportaLinhaAtividadeMeiEvento->incluir(null);
	          
	          if ($oMeiImportaLinhaAtividadeMeiEvento->erro_status == 0){
	            throw new Exception("{$sMsgErro}\\n{$oMeiImportaLinhaAtividadeMeiEvento->erro_msg}") ;
	          }
        	}
        }       
    	}
    }
  }
}

?>