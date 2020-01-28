<?
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

/**
 * 
 * @todo refatorar toda a rotina de inclusão e alteração de um atendimento da ouvidoria
 * 
 */
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");
require_once("std/db_stdClass.php");
require_once("libs/db_app.utils.php");
 

db_app::import("protocolo.ProcessoProtocoloNumeracao");

$oPost    = db_utils::postMemory($_POST);
$oJson    = new services_json();
$lErro    = false;
$sMsgErro = '';


require_once("std/db_stdClass.php");
$oStdClass = new db_stdClass();

require_once("classes/db_cidadao_classe.php");
$clCidadao = new cl_cidadao();

require_once("classes/db_cidadaoemail_classe.php");
$clCidadaoEmail = new cl_cidadaoemail();

require_once("classes/db_cidadaotelefone_classe.php");
$clCidadaoTelefone = new cl_cidadaotelefone();

require_once("classes/db_cidadaotiporetorno_classe.php");
$clCidadaoTipoRetorno = new cl_cidadaotiporetorno();

require_once("classes/db_tipoproc_classe.php");
$clTipoProc = new cl_tipoproc();

require_once("classes/db_procdoctipo_classe.php");
$clDocTipoProc = new cl_procdoctipo();

require_once("classes/db_cgm_classe.php");
$clCgm = new cl_cgm();

require_once("classes/db_tipoprocformareclamacao_classe.php");
$clTipoProcFormaReclamacao = new cl_tipoprocformareclamacao();

require_once("classes/db_formareclamacao_classe.php");
$clFormaReclamacao = new cl_formareclamacao();

require_once("classes/db_tiporetorno_classe.php");
$clTipoRetorno = new cl_tiporetorno();

require_once("classes/db_ouvidoriaatendimento_classe.php");
$clOuvidoriaAtendimento = new cl_ouvidoriaatendimento();

require_once("classes/db_ouvidoriaatendimentolocal_classe.php");
$clOuvidoriaAtendimentoLocal = new cl_ouvidoriaatendimentolocal();

require_once("classes/db_andpadrao_classe.php");
$clAndPadrao = new cl_andpadrao();

require_once("classes/db_ouvidoriaatendimentotiporetorno_classe.php");
$clOuvidoriaAtendimentoTipoRetorno = new cl_ouvidoriaatendimentotiporetorno();

require_once("classes/db_ouvidoriaatendimentocgm_classe.php");
$clOuvidoriaAtendimentoCgm = new cl_ouvidoriaatendimentocgm();

require_once("classes/db_ouvidoriaatendimentocidadao_classe.php");
$clOuvidoriaAtendimentoCidadao = new cl_ouvidoriaatendimentocidadao();

require_once("classes/db_ouvidoriaatendimentodoc_classe.php");
$clOuvidoriaAtendimentoDoc = new cl_ouvidoriaatendimentodoc();

require_once("classes/db_ouvidoriaatendimentoretornoemail_classe.php");
$clOuvidoriaAtendimentoRetornoEmail = new cl_ouvidoriaatendimentoretornoemail();

require_once("classes/db_ouvidoriaatendimentoretornoender_classe.php");
$clOuvidoriaAtendimentoRetornoEnder = new cl_ouvidoriaatendimentoretornoender();

require_once("classes/db_ouvidoriaatendimentoretornotelefone_classe.php");
$clOuvidoriaAtendimentoRetornoTelefone = new cl_ouvidoriaatendimentoretornotelefone();

require_once("classes/db_protprocesso_classe.php");
$clProtProcesso = new cl_protprocesso();

require_once("classes/db_db_config_classe.php");
$clDBConfig = new cl_db_config();

require_once("classes/db_processoouvidoria_classe.php");
$clProcessoOuvidoria = new cl_processoouvidoria();

require_once("model/processoOuvidoria.model.php");
$oProcessoOuvidoria = new processoOuvidoria();


if ( $oPost->sMethod == 'consultaDadosTipoProcesso') {

	$aListaDocumentos      = array();
	$aListaFormaReclamacao = array();
	$lIdentificado         = false;
	
	// Verifica se o tipo de processo exige identificação
	$rsTipoIdentificacao = $clTipoProc->sql_record($clTipoProc->sql_query_file($oPost->iCodTipoProc,'p51_identificado'));
	
	if ( $clTipoProc->numrows > 0  ) {
		$oTipoIdentificacao = db_utils::fieldsMemory($rsTipoIdentificacao,0); 
		if ( $oTipoIdentificacao->p51_identificado == 't') {
		  $lIdentificado = true;
		}
	}

	// Consulta os documentos exigidos pelo tipo de processo
	$sCamposDocumentos    = "p56_coddoc,";
	$sCamposDocumentos   .= "p56_descr  ";
	$rsConsultaDocumentos = $clDocTipoProc->sql_record($clDocTipoProc->sql_query($oPost->iCodTipoProc,null,$sCamposDocumentos));
	
  if ( $clDocTipoProc->numrows > 0 ) {
    $aListaDocumentos = db_utils::getColectionByRecord($rsConsultaDocumentos,false,false,true);  	
	}
	
  // Consulta as formas de reclamação cadastradas para o tipo de processo
	$sCamposFormaReclamacao  = "p42_sequencial,";
  $sCamposFormaReclamacao .= "p42_descricao  ";
  $sWhereFormaReclamacao   = "p43_tipoproc = {$oPost->iCodTipoProc}";
  
  $sSqlFormaReclamacao     = $clTipoProcFormaReclamacao->sql_query(null,$sCamposFormaReclamacao,null,$sWhereFormaReclamacao);
  $rsFormaReclamacao       = $clTipoProcFormaReclamacao->sql_record($sSqlFormaReclamacao);
  
  if ( $clTipoProcFormaReclamacao->numrows > 0  ) {
    $aListaFormaReclamacao = db_utils::getColectionByRecord($rsFormaReclamacao,false,false,true);    
  }	
	
  
  $aRetorno = array("lIdentificado"         =>$lIdentificado,
	                  "aListaDocumentos"      =>$aListaDocumentos,
                    "aListaFormaReclamacao" =>$aListaFormaReclamacao);

  echo $oJson->encode($aRetorno);
  
  
} else if ( $oPost->sMethod == 'consultaDataPrevista') {  
  
  $sDataPrevista = '';	
  $dtDataIni     = db_getsession('DB_datausu');
  
  if ( isset($oPost->iCodAtendimento) && trim($oPost->iCodAtendimento) != '' ) {
  	$sWhereAtend = " ov09_ouvidoriaatendimento = {$oPost->iCodAtendimento} ";
  	$rsDataProc  = $clProcessoOuvidoria->sql_record($clProcessoOuvidoria->sql_query(null,"p58_dtproc",null,$sWhereAtend));
  	if ( $clProcessoOuvidoria->numrows > 0 ) {
  		$oDataProc = db_utils::fieldsMemory($rsDataProc,0);
      $aDataProc = explode('-',$oDataProc->p58_dtproc);
      $dtDataIni = mktime(0,0,0,$aDataProc[1],$aDataProc[2],$aDataProc[0]); 
  	}
  }

  if ( isset($oPost->iCodProc) && trim($oPost->iCodProc) != '' ) {
    $rsDataProc  = $clProtProcesso->sql_record($clProtProcesso->sql_query_file($oPost->iCodProc,"p58_dtproc"));
    if ( $clProtProcesso->numrows > 0 ) {
      $oDataProc = db_utils::fieldsMemory($rsDataProc,0);
      $aDataProc = explode('-',$oDataProc->p58_dtproc);
      $dtDataIni = mktime(0,0,0,$aDataProc[1],$aDataProc[2],$aDataProc[0]); 
    }
  }   
  
  $rsDiasAndPadrao = $clAndPadrao->sql_record($clAndPadrao->sql_query($oPost->iCodTipoProc,null,"sum(p53_dias) as totaldias")); 
 
  if ( $rsDiasAndPadrao && $clAndPadrao->numrows > 0 ) {
  	$oAndPadrao    = db_utils::fieldsMemory($rsDiasAndPadrao,0);
  	$sDataPrevista = date('d/m/Y',$oStdClass->getIntervaloDiasUteis($dtDataIni,$oAndPadrao->totaldias));
  }
	
  $aRetorno = array("sDataPrevista"=>$sDataPrevista);	
	
	echo $oJson->encode($aRetorno);
	
} else if ( $oPost->sMethod == 'consultaDadosRequerente') {

  
	$aListaEmail       = array();
  $aListaEnder       = array();
  $aListaTelefone    = array();  
  $aListaTipoRetorno = array();
  $lTemProcessos     = false;
  
  
	if ( $oPost->sTipoRequerente == 'CGM' ) {

		$sWhereProcTitular  = "     ov11_cgm    = {$oPost->iCodRequerente}   ";
		$sWhereProcTitular .= " and ov01_instit = ".db_getsession('DB_instit');
    $sWhereProcTitular .= " and p68_codproc is null                      ";
    
		$sSqlVerificaProc   = $clOuvidoriaAtendimento->sql_query_proctitular(null,"*","ov01_sequencial limit 1",$sWhereProcTitular);
		$rsVerificaProc     = $clOuvidoriaAtendimento->sql_record($sSqlVerificaProc);

		if ( $clOuvidoriaAtendimento->numrows > 0 ) {
			$lTemProcessos = true;
		}
		
		
		$rsDadosCgm = $clCgm->sql_record($clCgm->sql_query_file($oPost->iCodRequerente));
		
		if ( $clCgm->numrows > 0 ) {

			$oDadosCgm = db_utils::fieldsMemory($rsDadosCgm,0);
			
      // Cria a lista de Endereços para retorno
			$oEnderereco = new stdClass();
		  $oEnderereco->ov12_endereco = $oDadosCgm->z01_ender;
		  $oEnderereco->ov12_numero   = $oDadosCgm->z01_numero;
		  $oEnderereco->ov12_compl    = $oDadosCgm->z01_compl;
		  $oEnderereco->ov12_bairro   = $oDadosCgm->z01_bairro;
		  $oEnderereco->ov12_munic    = $oDadosCgm->z01_munic;
		  $oEnderereco->ov12_uf       = $oDadosCgm->z01_uf;
		  $oEnderereco->ov12_cep      = $oDadosCgm->z01_cep;       
			
			$aListaEnder[] = $oEnderereco;
			
      // Cria a lista de Telefones para retorno
      if ( trim($oDadosCgm->z01_telef) != '' ) {
        $oTeleFoneRes = new stdClass();
        $oTeleFoneRes->ov14_numero       = $oDadosCgm->z01_telef;     
        $oTeleFoneRes->ov14_tipotelefone = 1;
        $oTeleFoneRes->ov23_descricao    = 'Residencial';
			  $oTeleFoneRes->ov14_ddd          = '';           
			  $oTeleFoneRes->ov14_ramal        = '';
			  $oTeleFoneRes->ov14_obs          = '';
        $aListaTelefone[] = $oTeleFoneRes;
      } 			
			
			if ( trim($oDadosCgm->z01_telcel) != '' ) {
				$oTeleFoneCel = new stdClass();
				$oTeleFoneCel->ov14_numero       = $oDadosCgm->z01_telcel;     
				$oTeleFoneCel->ov14_tipotelefone = 2;
				$oTeleFoneCel->ov23_descricao    = 'Celular';
        $oTeleFoneCel->ov14_ddd          = '';           
        $oTeleFoneCel->ov14_ramal        = '';
        $oTeleFoneCel->ov14_obs          = '';
        $aListaTelefone[] = $oTeleFoneCel;				
			} 
			
      if ( trim($oDadosCgm->z01_telcon) != '' ) {
        $oTeleFoneCom = new stdClass();
        $oTeleFoneCom->ov14_numero       = $oDadosCgm->z01_telcon;     
        $oTeleFoneCom->ov14_tipotelefone = 3;
        $oTeleFoneCom->ov23_descricao    = 'Comercial';
        $oTeleFoneCom->ov14_ddd          = '';           
        $oTeleFoneCom->ov14_ramal        = '';
        $oTeleFoneCom->ov14_obs          = '';
        $aListaTelefone[] = $oTeleFoneCom;        
      }

      if ( trim($oDadosCgm->z01_celcon) != '' ) {
        $oTeleFoneCelCom = new stdClass();
        $oTeleFoneCelCom->ov14_numero       = $oDadosCgm->z01_celcon;     
        $oTeleFoneCelCom->ov14_tipotelefone = 3;
        $oTeleFoneCelCom->ov23_descricao    = 'Comercial';
        $oTeleFoneCelCom->ov14_obs          = 'Celular';
        $oTeleFoneCelCom->ov14_ddd          = '';           
        $oTeleFoneCelCom->ov14_ramal        = '';
        $aListaTelefone[] = $oTeleFoneCelCom;
      }

      if ( trim($oDadosCgm->z01_fax) != '' ) {
        $oTeleFoneFax = new stdClass();
        $oTeleFoneFax->ov14_numero       = $oDadosCgm->z01_fax;     
        $oTeleFoneFax->ov14_tipotelefone = 4;
        $oTeleFoneFax->ov23_descricao    = 'Fax';
        $oTeleFoneFax->ov14_ddd          = '';           
        $oTeleFoneFax->ov14_ramal        = '';
        $oTeleFoneFax->ov14_obs          = '';
        $aListaTelefone[] = $oTeleFoneFax;        
      }                   
      
      
      // Cria a lista de Emails para retorno
      if ( trim($oDadosCgm->z01_email) != '' ) {
        $oEmail  = new stdClass();
        $oEmail->ov13_email = $oDadosCgm->z01_email;
        $aListaEmail[]      = $oEmail;  
      }
      
		  if ( trim($oDadosCgm->z01_emailc) != '' ) {
        $oEmailCom  = new stdClass();
        $oEmailCom->ov13_email = $oDadosCgm->z01_emailc;
        $aListaEmail[]      = $oEmailCom; 
      }

      
      if ( count($aListaEnder) > 0 ) {
        $oTipoRetorno = new stdClass();
        $oTipoRetorno->ov04_tiporetorno = 1;
        $aListaTipoRetorno[] = $oTipoRetorno;
        $oTipoRetorno = new stdClass();
        $oTipoRetorno->ov04_tiporetorno = 2;
        $aListaTipoRetorno[] = $oTipoRetorno;        
      }             
      
      if ( count($aListaTelefone) > 0 ) {
        $oTipoRetorno = new stdClass();
        $oTipoRetorno->ov04_tiporetorno = 4;
        $aListaTipoRetorno[] = $oTipoRetorno;
      }       
      
			if ( count($aListaEmail) > 0 ) {
				$oTipoRetorno = new stdClass();
				$oTipoRetorno->ov04_tiporetorno = 3;
				$aListaTipoRetorno[] = $oTipoRetorno;
			} 
		}
		
	} else {
		
		
	  $sWhereProcTitular  = "      ov10_cidadao = {$oPost->iCodRequerente}   ";
    $sWhereProcTitular .= " and ov01_instit   = ".db_getsession('DB_instit');
    $sWhereProcTitular .= " and p68_codproc is null                        ";
    
    $sSqlVerificaProc   = $clOuvidoriaAtendimento->sql_query_proctitular(null,"*","ov01_sequencial limit 1",$sWhereProcTitular);
    $rsVerificaProc     = $clOuvidoriaAtendimento->sql_record($sSqlVerificaProc);

    if ( $clOuvidoriaAtendimento->numrows > 0 ) {
      $lTemProcessos = true;
    }		

		$rsCidadoEnder = $clCidadao->sql_record($clCidadao->sql_query_file($oPost->iCodRequerente,$oPost->iSeq));
		
		if ( $clCidadao->numrows > 0 ) {
			
      $oCidadao = db_utils::fieldsMemory($rsCidadoEnder,0);
      
      $sWhereCidadaoTipoRet  = "     ov04_cidadao = {$oCidadao->ov02_sequencial} ";
      $sWhereCidadaoTipoRet .= " and ov04_seq     = {$oCidadao->ov02_seq}        ";
      $sSqlCidadaoTipoRet    = $clCidadaoTipoRetorno->sql_query_file(null,"*",null,$sWhereCidadaoTipoRet);
      $rsCidadaoTipoRetorno  = $clCidadaoTipoRetorno->sql_record($sSqlCidadaoTipoRet);
      $iNroCidadaoTipoRet    = pg_num_rows($rsCidadaoTipoRetorno);
      $lIncluiEnder          = true;
      
      if ( $iNroCidadaoTipoRet > 0 ) {
      	
      	$aListaTipoRetorno = db_utils::getColectionByRecord($rsCidadaoTipoRetorno);
      	
      	for ( $iInd=0; $iInd < $iNroCidadaoTipoRet; $iInd++ ) {
      		
          $oTipoRetorno = db_utils::fieldsMemory($rsCidadaoTipoRetorno,$iInd);
          
          if ( ( $oTipoRetorno->ov04_tiporetorno == 1 || $oTipoRetorno->ov04_tiporetorno == 2 ) && $lIncluiEnder ) {
			      
			      $oEndererecoRetorno = new stdClass();
			      $oEndererecoRetorno->ov12_endereco = $oCidadao->ov02_endereco;
			      $oEndererecoRetorno->ov12_numero   = $oCidadao->ov02_numero;
			      $oEndererecoRetorno->ov12_compl    = $oCidadao->ov02_compl;
			      $oEndererecoRetorno->ov12_bairro   = $oCidadao->ov02_bairro;
			      $oEndererecoRetorno->ov12_munic    = $oCidadao->ov02_munic;
			      $oEndererecoRetorno->ov12_uf       = $oCidadao->ov02_uf;
			      $oEndererecoRetorno->ov12_cep      = $oCidadao->ov02_cep;			      
          	
          	$aListaEnder[] = $oEndererecoRetorno;
			      
			      $lIncluiEnder = false;
          	
          } else if ( $oTipoRetorno->ov04_tiporetorno == 3 ) {
          	
          	$sWhereRetornoEmail  = "     ov08_cidadao = {$oCidadao->ov02_sequencial} ";
          	$sWhereRetornoEmail .= " and ov08_seq     = {$oCidadao->ov02_seq}        ";
            $rsRetornoEmail      = $clCidadaoEmail->sql_record($clCidadaoEmail->sql_query_file(null,"*",null,$sWhereRetornoEmail));
            $iLinhasEmail        = $clCidadaoEmail->numrows;
             
            if ( $iLinhasEmail > 0 ) {
            	for ( $iIndEmail=0; $iIndEmail < $iLinhasEmail; $iIndEmail++ ) {
            		$oEmail = db_utils::fieldsMemory($rsRetornoEmail,$iIndEmail,false,false,true);
            		$oEmailRetorno = new stdClass();
                $oEmailRetorno->ov13_email = $oEmail->ov08_email;
                $aListaEmail[] = $oEmailRetorno;            		
            	}
            }
            
            
          } else if ( $oTipoRetorno->ov04_tiporetorno == 4 ) {
          	
            $sWhereRetornoTelefone  = "     ov07_cidadao = {$oCidadao->ov02_sequencial} ";
            $sWhereRetornoTelefone .= " and ov07_seq     = {$oCidadao->ov02_seq}        ";
            $rsRetornoTelefone      = $clCidadaoTelefone->sql_record($clCidadaoTelefone->sql_query(null,"*",null,$sWhereRetornoTelefone));
            $iLinhasTelefone        = $clCidadaoTelefone->numrows;
            
            if ( $iLinhasTelefone > 0 ) {
              for ( $iIndTelefone=0; $iIndTelefone < $iLinhasTelefone; $iIndTelefone++ ) {
                $oTelefone = db_utils::fieldsMemory($rsRetornoTelefone,$iIndTelefone,false,false,true);
                
                $oTeleFoneRetorno = new stdClass();
				        $oTeleFoneRetorno->ov14_numero       = $oTelefone->ov07_numero;
				        $oTeleFoneRetorno->ov14_tipotelefone = $oTelefone->ov07_tipotelefone;
				        $oTeleFoneRetorno->ov23_descricao    = $oTelefone->ov23_descricao;
				        $oTeleFoneRetorno->ov14_obs          = $oTelefone->ov07_obs;
				        $oTeleFoneRetorno->ov14_ddd          = $oTelefone->ov07_ddd;           
				        $oTeleFoneRetorno->ov14_ramal        = $oTelefone->ov07_ramal;

                $aListaTelefone[] = $oTeleFoneRetorno;

              }
            }
          }
      	}
      }
		}
		
	}
	
  
  $aRetorno = array("aListaTipoRetorno"=>$aListaTipoRetorno,
                    "aListaEnder"      =>$aListaEnder,
                    "aListaEmail"      =>$aListaEmail,
                    "aListaTelefone"   =>$aListaTelefone,
                    "lTemProcessos"    =>$lTemProcessos);

  echo $oJson->encode($aRetorno);
  
} else if ( $oPost->sMethod == 'consultaDadosTela') {

  
  $aListaFormaReclamacao = array();
  $aListaTipoRetorno     = array();
  
  
  $rsFormaReclamacao = $clFormaReclamacao->sql_record($clFormaReclamacao->sql_query_file());
  
  if ( $clFormaReclamacao->numrows > 0 ) {
  	$aListaFormaReclamacao = db_utils::getColectionByRecord($rsFormaReclamacao,false,false,true);
  }
  
  
  $rsTipoRetorno = $clTipoRetorno->sql_record($clTipoRetorno->sql_query_file());
  
  if ( $clTipoRetorno->numrows > 0 ) {
  	$aListaTipoRetorno = db_utils::getColectionByRecord($rsTipoRetorno,false,false,true);
  }
  
  
  $aRetorno = array("aListaTipoRetorno"     =>$aListaTipoRetorno,
                    "aListaFormaReclamacao" =>$aListaFormaReclamacao);

  echo $oJson->encode($aRetorno);
  
  
} else if ( $oPost->sMethod == 'incluirAtendimento') {

	
  $oAtendimento = $oJson->decode(str_replace("\\","",$oPost->oAtendimento));	
  $aDocumento   = $oJson->decode(str_replace("\\","",$oPost->aDocumento));
  $oRetorno     = $oJson->decode(str_replace("\\","",$oPost->oRetorno));

  
  if ( $oPost->sTipo == 'finaliza' ) {
    $iSituacao = 2;   	
  } else {
  	$iSituacao = 1;
  }
  
  $lErro = false;
  db_inicio_transacao();

  if ($oAtendimento->ov01_tipoidentificacao == 2 ) {
    
    if ($oAtendimento->oTitularAtendimento->sTipo == "CGM") {
      
     /**
      * caso o usuário quer usar o endereço do CGm como Local, devemos verificar se 
      * já existe um local cadastrado como endereço para os dados do endereço.
      * para ser um endereço valido, o endereo do CGM deverá ter ligação com as ruas do CTM. 
      */
      if ($oAtendimento->usarenderecocgm) {
         
        $oDaoCgmRuas       = db_utils::getDao("db_cgmruas");
        $sCampos           = "z01_compl, z01_numero, ruas.j14_codigo,j14_nome "; 
        $sSqlDadosEndereco = $oDaoCgmRuas->sql_query($oAtendimento->oTitularAtendimento->iCodigo, $sCampos);
        $rsDadosEndereco   = $oDaoCgmRuas->sql_record($sSqlDadosEndereco);
        if ($oDaoCgmRuas->numrows == 0) {

          $lErro = true;
          $sMsgErro  = "O CGM {$oAtendimento->oTitularAtendimento->iCodigo} não possui endereço válido.\n";
          $sMsgErro .= "Para continuar o Cadastro, corriga o endereço do CGM ou cadastre o local.";
        }

        if (!$lErro) {
          
          $iCodigoLocal = '';
          $oEnderecoCgm = db_utils::fieldsMemory($rsDadosEndereco, 0);
          
          /*
           * Verificamos se nao existe nenhum local cadastrado pra o endereço do CGM. 
           * caso já exista devemos usar o local existente.  caso nao existe é incluido um novo
           * local e vinculado ao Atendimento
           */
          $oDaoOuvidoriaLocalEndereco = db_utils::getDao("ouvidoriacadlocalender");
          $sWhere  = "ov26_ruas = {$oEnderecoCgm->j14_codigo} "; 
          $sWhere .= " and trim(ov26_numero)      =  '".trim($oEnderecoCgm->z01_numero)."'"; 
          $sWhere .= " and trim(ov26_complemento) =  '".trim($oEnderecoCgm->z01_compl)."'";
          $sSqlEnderecoLocal = $oDaoOuvidoriaLocalEndereco->sql_query_file(null, 
                                                                           "ov26_ouvidoriacadlocal", 
                                                                           null, 
                                                                           $sWhere
                                                                           );
          $rsEnderecoLocal   = $oDaoOuvidoriaLocalEndereco->sql_record($sSqlEnderecoLocal);
          if ($oDaoOuvidoriaLocalEndereco->numrows > 0) {

            $iCodigoLocal = db_utils::fieldsMemory($rsEnderecoLocal, 0)->ov26_ouvidoriacadlocal;
          } else {
            
            $oDaoOuvidoriaCadEnderLocal = db_utils::getDao("ouvidoriacadlocal");
            $sDescricaoLocal            = "{$oEnderecoCgm->j14_nome}";
            if (trim($oEnderecoCgm->z01_numero) != "") {
              $sDescricaoLocal     .= ", {$oEnderecoCgm->z01_numero}";
            }
            if (trim($oEnderecoCgm->z01_compl) != "") {
              $sDescricaoLocal     .= ", {$oEnderecoCgm->z01_compl}";
            }
            $oDaoOuvidoriaCadEnderLocal->ov25_descricao = $sDescricaoLocal;
            $oDaoOuvidoriaCadEnderLocal->incluir(null);
            if ($oDaoOuvidoriaCadEnderLocal->erro_status == 0) {
              
              $lErro = true;
              $sMsgErro = $oDaoOuvidoriaCadEnderLocal->erro_msg;
            }
            
            if (!$lErro) {
              
              $oDaoOuvidoriaLocalEndereco->ov26_complemento       = $oEnderecoCgm->z01_compl;
              $oDaoOuvidoriaLocalEndereco->ov26_numero            = $oEnderecoCgm->z01_numero;
              $oDaoOuvidoriaLocalEndereco->ov26_ouvidoriacadlocal = $oDaoOuvidoriaCadEnderLocal->ov25_sequencial;
              $oDaoOuvidoriaLocalEndereco->ov26_ruas              = $oEnderecoCgm->j14_codigo;
              $oDaoOuvidoriaLocalEndereco->incluir(null);
              if ($oDaoOuvidoriaCadEnderLocal->erro_status == 0) {
              
                $lErro    = true;
                $sMsgErro = $oDaoOuvidoriaCadEnderLocal->erro_msg;
              }
              $iCodigoLocal = $oDaoOuvidoriaCadEnderLocal->ov25_sequencial;
            }
          }
          
          if (!$lErro && $iCodigoLocal != "") {
            $oAtendimento->ov24_ouvidoriacadlocal = $iCodigoLocal;
          }
        }
      }
    }
  }
  if (!$lErro) {
    
    
  	$clOuvidoriaAtendimento->ov01_tipoprocesso      = $oAtendimento->ov01_tipoprocesso;
  	$clOuvidoriaAtendimento->ov01_formareclamacao   = $oAtendimento->ov01_formareclamacao;
  	$clOuvidoriaAtendimento->ov01_tipoidentificacao = $oAtendimento->ov01_tipoidentificacao;
  	$clOuvidoriaAtendimento->ov01_usuario           = $oAtendimento->ov01_usuario;
  	$clOuvidoriaAtendimento->ov01_depart            = $oAtendimento->ov01_depart;
  	$clOuvidoriaAtendimento->ov01_instit            = db_getsession('DB_instit');
  	$clOuvidoriaAtendimento->ov01_anousu            = db_getsession('DB_anousu');
  	$clOuvidoriaAtendimento->ov01_dataatend         = implode('-',array_reverse(explode('/',$oAtendimento->ov01_dataatend)));  
  	$clOuvidoriaAtendimento->ov01_horaatend         = $oAtendimento->ov01_horaatend; 
  	$clOuvidoriaAtendimento->ov01_requerente        = utf8_decode($oAtendimento->ov01_requerente); 
  	$clOuvidoriaAtendimento->ov01_solicitacao       = utf8_decode(pg_escape_string(db_stdClass::db_stripTagsJson($oAtendimento->ov01_solicitacao)));
  	$clOuvidoriaAtendimento->ov01_executado         = utf8_decode(pg_escape_string(db_stdClass::db_stripTagsJson($oAtendimento->ov01_executado))); 
  	$clOuvidoriaAtendimento->ov01_situacaoouvidoriaatendimento = $iSituacao;
  	$clOuvidoriaAtendimento->ov01_sequencial        = $oAtendimento->ov01_sequencial;
  	
  	
  	$oOuvidoriaParam = db_utils::getDao("ouvidoriaparametro");
  	$sSqlParametro   = $oOuvidoriaParam->sql_query_file(db_getsession("DB_instit"), db_getsession("DB_anousu"), 
  	                                                    "ov06_tiponumprocesso");
  	$rsParametro     = $oOuvidoriaParam->sql_record($sSqlParametro);
  	
  	$iTipoControleNumeracao = 1;
  	if ($rsParametro && $oOuvidoriaParam->numrows == 1) {
  	  $iTipoControleNumeracao = 2;
  	}
  	
  	$ov01_numero = null;
  	if (isset($oAtendimento->ov01_sequencial) && empty($oAtendimento->ov01_sequencial)) {

	    // Consulta Numero do Atendimento
	    if ($iTipoControleNumeracao == 1) { // Sequencial infinito
	      
  	    $sSqlNumeroAtendimento = "  select max(ov01_numero) + 1 as seq from ouvidoriaatendimento";
  	    $rsNumeroAtendimento   = db_query($sSqlNumeroAtendimento);
  	    if ( $rsNumeroAtendimento ) {
  	      $oNumeroAtendimento = db_utils::fieldsMemory($rsNumeroAtendimento,0);
  	      $ov01_numero = $oNumeroAtendimento->seq;
  	    }
	    } else if ($iTipoControleNumeracao == 2) {
	      
	      $sSqlAnoAtendimento = "  select 1 from ouvidoriaatendimento where ov01_anousu = " . db_getsession("DB_anousu");
	      $rsAnoAtendimento   = db_query($sSqlAnoAtendimento);
	      
	      if ($rsAnoAtendimento && pg_num_rows($rsAnoAtendimento) > 0) { //Sequencial por ano
	        
	        $sSqlProximoNumero  = "select max(ov01_numero) + 1 as seq from ouvidoriaatendimento where ov01_anousu = ";
	        $sSqlProximoNumero .= db_getsession("DB_anousu");
	        $rsProximoNumero    = db_query($sSqlProximoNumero); 
	        $oNumeroAtendimento = db_utils::fieldsMemory($rsProximoNumero,0);
	        $ov01_numero = $oNumeroAtendimento->seq;
	      } else {
	        $ov01_numero = 1;
	      }
	    }
	    
  		$clOuvidoriaAtendimento->ov01_numero            = $ov01_numero;
  		$clOuvidoriaAtendimento->incluir(null);
  	} else {
  		
  		$clOuvidoriaAtendimento->alterar($oAtendimento->ov01_sequencial);
  		$sSqlBuscaNumeroAtendimento = $clOuvidoriaAtendimento->sql_query_file($oAtendimento->ov01_sequencial, "ov01_numero");
  		$rsBuscaNumeroAtendimento   = $clOuvidoriaAtendimento->sql_record($sSqlBuscaNumeroAtendimento);
  		$clOuvidoriaAtendimento->ov01_numero = db_utils::fieldsMemory($rsBuscaNumeroAtendimento, 0)->ov01_numero;
  	}
  
    if ( $clOuvidoriaAtendimento->erro_status == 0 ) {
    	$lErro    = true;
    	$sMsgErro = $clOuvidoriaAtendimento->erro_msg;
    }
  }
  
  if ( !$lErro && $oAtendimento->ov01_tipoidentificacao == 2 ) {
  	                      
    if ($oAtendimento->oTitularAtendimento->sTipo == "CGM") {
     	
    	$sWhereVinculoCgm    = "ov11_ouvidoriaatendimento = {$clOuvidoriaAtendimento->ov01_sequencial}";
    	$sSqlBuscaVinculoCgm = $clOuvidoriaAtendimentoCgm->sql_query_file(null, 'ov11_sequencial', null, $sWhereVinculoCgm);
    	$rsBuscaVinculoCgm   = $clOuvidoriaAtendimentoCgm->sql_record($sSqlBuscaVinculoCgm);
    	if ($clOuvidoriaAtendimentoCgm->numrows > 0) {
    		$clOuvidoriaAtendimentoCgm->excluir(null, $sWhereVinculoCgm);
    		if ($clOuvidoriaAtendimentoCgm->erro_status == 0) {
    			
    			$lErro    = true;
    			$sMsgErro = $clOuvidoriaAtendimentoCgm->erro_msg;
    		}
    	}
    	
    	if (!$lErro) {
    		
	    	$clOuvidoriaAtendimentoCgm->ov11_ouvidoriaatendimento = $clOuvidoriaAtendimento->ov01_sequencial;
	    	$clOuvidoriaAtendimentoCgm->ov11_cgm                  = $oAtendimento->oTitularAtendimento->iCodigo; 
	    	$clOuvidoriaAtendimentoCgm->incluir(null);
	    	if ( $clOuvidoriaAtendimentoCgm->erro_status == 0 ) {
			    $lErro    = true;
			    $sMsgErro = $clOuvidoriaAtendimentoCgm->erro_msg;    	  	
	    	}
    	}
    } else {
    	
    	$sWhereVinculoCidadao    = "ov10_ouvidoriaatendimento = {$clOuvidoriaAtendimento->ov01_sequencial}";
    	$sSqlBuscaVinculoCidadao = $clOuvidoriaAtendimentoCidadao->sql_query_file(null, 'ov10_sequencial', null, $sWhereVinculoCidadao);
    	$rsBuscaVinculoCidadao   = $clOuvidoriaAtendimentoCidadao->sql_record($sSqlBuscaVinculoCidadao);
    	if ($clOuvidoriaAtendimentoCidadao->numrows > 0) {
    		$clOuvidoriaAtendimentoCidadao->excluir(null, $sWhereVinculoCidadao);
    		if ($clOuvidoriaAtendimentoCidadao->erro_status == 0) {
    			 
    			$lErro    = true;
    			$sMsgErro = $clOuvidoriaAtendimentoCidadao->erro_msg;
    		}
    	}
    	
    	if (!$lErro) {
    	
	      $clOuvidoriaAtendimentoCidadao->ov10_ouvidoriaatendimento = $clOuvidoriaAtendimento->ov01_sequencial;
	      $clOuvidoriaAtendimentoCidadao->ov10_cidadao              = $oAtendimento->oTitularAtendimento->iCodigo; 
	      $clOuvidoriaAtendimentoCidadao->ov10_seq                  = $oAtendimento->oTitularAtendimento->iSeq;
	      $clOuvidoriaAtendimentoCidadao->incluir(null);
	      if ( $clOuvidoriaAtendimentoCidadao->erro_status == 0 ) {
	        $lErro    = true;
	        $sMsgErro = $clOuvidoriaAtendimentoCidadao->erro_msg;          
	      }    	
    	}
    }
  }
  
 if( !$lErro  && trim($oAtendimento->ov24_ouvidoriacadlocal) != '' ) {
 	
    $clOuvidoriaAtendimentoLocal->ov24_ouvidoriaatendimento = $clOuvidoriaAtendimento->ov01_sequencial;
    $clOuvidoriaAtendimentoLocal->ov24_ouvidoriacadlocal    = $oAtendimento->ov24_ouvidoriacadlocal;

    if ($clOuvidoriaAtendimento->ov01_sequencial != "") {
    	
    	$sWhereAtendimento = "ov24_ouvidoriaatendimento = {$clOuvidoriaAtendimento->ov01_sequencial}";
    	$clOuvidoriaAtendimentoLocal->excluir(null, $sWhereAtendimento);
    	
    	if ($clOuvidoriaAtendimentoLocal->erro_status == 0) {
    		$sMsgErro = $clOuvidoriaAtendimentoLocal->erro_msg;
    		$lErro    = true;
    	}
    }
    $clOuvidoriaAtendimentoLocal->incluir(null);

    if ( $clOuvidoriaAtendimentoLocal->erro_status == 0 ) {
      $sMsgErro = $clOuvidoriaAtendimentoLocal->erro_msg;
      $lErro    = true;
    }
    
  }
	if ( !$lErro ) {
		if ( count($aDocumento) > 0 ) {
			
			$sWhereAtendimentoDocumentos = "ov19_ouvidoriaatendimento = {$clOuvidoriaAtendimento->ov01_sequencial}";
			$clOuvidoriaAtendimentoDoc->excluir(null, $sWhereAtendimentoDocumentos);
			if ($clOuvidoriaAtendimentoDoc->erro_status == 0) {
				
				$lErro = true;
				$sMsgErro = $clOuvidoriaAtendimentoDoc->erro_msg;
			}
			
			if (!$lErro) {
				
				foreach ($aDocumento as $iInd => $oDocumento ){
					
					$clOuvidoriaAtendimentoDoc->ov19_ouvidoriaatendimento = $clOuvidoriaAtendimento->ov01_sequencial;
					$clOuvidoriaAtendimentoDoc->ov19_procdoc              = $oDocumento->ov19_procdoc;
					$clOuvidoriaAtendimentoDoc->ov19_entregue             = $oDocumento->ov19_entregue;
					$clOuvidoriaAtendimentoDoc->incluir(null);
				  if ( $clOuvidoriaAtendimentoDoc->erro_status == 0 ) {
				    $lErro    = true;
				    $sMsgErro = $clOuvidoriaAtendimentoDoc->erro_msg;
				    break;
				  }				
				}
			}
		}
	}
  
  if ( !$lErro ) {
  	
  	$lIncluiEnder = true;
  	
  	if ( count($oRetorno->aTipoRetorno) > 0 ) {
  		
  		/**
  		 * Excluirmos todos os tipos de retorno já cadastrados para o atendimento e incluímos todos novamente
  		 */
  		$sWhereAtendimentoTipoRetorno = "ov17_ouvidoriaatendimento = {$clOuvidoriaAtendimento->ov01_sequencial}";
  		$clOuvidoriaAtendimentoTipoRetorno->excluir(null, $sWhereAtendimentoTipoRetorno);
  		if ($clOuvidoriaAtendimentoTipoRetorno->erro_status == 0) {
  			$lErro    = true;
  			$sMsgErro = $clOuvidoriaAtendimentoTipoRetorno->erro_msg;
  		}
  		
  		$sWhereExcluirEndereco = "ov12_ouvidoriaantendimento = {$clOuvidoriaAtendimento->ov01_sequencial}";
  		$clOuvidoriaAtendimentoRetornoEnder->excluir(null, $sWhereExcluirEndereco);
  		if ($clOuvidoriaAtendimentoRetornoEnder->erro_status == 0) {
  			$lErro    = true;
  			$sMsgErro = $clOuvidoriaAtendimentoRetornoEnder->erro_msg;
  		}
  		
  		$sWhereExcluirEmail    = "ov13_ouvidoriaantendimento = {$clOuvidoriaAtendimento->ov01_sequencial}";
  		$clOuvidoriaAtendimentoRetornoEmail->excluir(null, $sWhereExcluirEmail);
  		if ($clOuvidoriaAtendimentoRetornoEmail->erro_status == 0) {
  			$lErro    = true;
  			$sMsgErro = $clOuvidoriaAtendimentoRetornoEmail->erro_msg;
  		}
  		
  		$sWhereExcluirTelefone = "ov14_ouvidoriaatendimento = {$clOuvidoriaAtendimento->ov01_sequencial}";
  		$clOuvidoriaAtendimentoRetornoTelefone->excluir(null, $sWhereExcluirTelefone);
  		if ($clOuvidoriaAtendimentoRetornoTelefone->erro_status == 0) {
  			$lErro    = true;
  			$sMsgErro = $clOuvidoriaAtendimentoRetornoTelefone->erro_msg;
  		}
  		
  		if (!$lErro) {
  			
	  		foreach ( $oRetorno->aTipoRetorno as $iInd => $oTipoRetorno ) {
	  			
	  			$clOuvidoriaAtendimentoTipoRetorno->ov17_tiporetorno          = $oTipoRetorno->ov17_tiporetorno; 
	  			$clOuvidoriaAtendimentoTipoRetorno->ov17_ouvidoriaatendimento = $clOuvidoriaAtendimento->ov01_sequencial;
	   			$clOuvidoriaAtendimentoTipoRetorno->incluir(null);
	   			
	   			if ( $clOuvidoriaAtendimentoTipoRetorno->erro_status == 0 ) {
	   				$lErro    = true;
	   				$sMsgErro = $clOuvidoriaAtendimentoTipoRetorno->erro_msg;
	   				break;
	   			}
	  			
	  			if ( $oTipoRetorno->ov17_tiporetorno == 1 || $oTipoRetorno->ov17_tiporetorno == 2 ) {
	  				foreach ( $oRetorno->aRetornoEndereco as $iIndEnd => $oRetornoEndereco ){
	  					if ( $lIncluiEnder ) {
		  					$clOuvidoriaAtendimentoRetornoEnder->ov12_ouvidoriaantendimento = $clOuvidoriaAtendimento->ov01_sequencial;
		  					$clOuvidoriaAtendimentoRetornoEnder->ov12_endereco              = $oRetornoEndereco->ov12_endereco;
		  					$clOuvidoriaAtendimentoRetornoEnder->ov12_numero                = $oRetornoEndereco->ov12_numero;
		  					$clOuvidoriaAtendimentoRetornoEnder->ov12_compl                 = $oRetornoEndereco->ov12_compl;
		  					$clOuvidoriaAtendimentoRetornoEnder->ov12_bairro                = $oRetornoEndereco->ov12_bairro;
		  					$clOuvidoriaAtendimentoRetornoEnder->ov12_munic                 = $oRetornoEndereco->ov12_munic;
		  					$clOuvidoriaAtendimentoRetornoEnder->ov12_uf                    = $oRetornoEndereco->ov12_uf;
		  					$clOuvidoriaAtendimentoRetornoEnder->ov12_cep                   = $oRetornoEndereco->ov12_cep;
		  					$clOuvidoriaAtendimentoRetornoEnder->incluir(null);
				        if ( $clOuvidoriaAtendimentoRetornoEnder->erro_status == 0 ) {
				          $lErro    = true;
				          $sMsgErro = $clOuvidoriaAtendimentoRetornoEnder->erro_msg;
				          break;
				        }
				        $lIncluiEnder = false;
	  					}         					
	  				}
	  			} else if ( $oTipoRetorno->ov17_tiporetorno == 3 ) {
	          foreach ( $oRetorno->aRetornoEmail as $iIndEmail => $oRetornoEmail ){
	          	$clOuvidoriaAtendimentoRetornoEmail->ov13_ouvidoriaantendimento = $clOuvidoriaAtendimento->ov01_sequencial;          
	          	$clOuvidoriaAtendimentoRetornoEmail->ov13_email                 = $oRetornoEmail->ov13_email;
	            $clOuvidoriaAtendimentoRetornoEmail->incluir(null);
	            if ( $clOuvidoriaAtendimentoRetornoEmail->erro_status == 0 ) {
	              $lErro    = true;
	              $sMsgErro = $clOuvidoriaAtendimentoRetornoEmail->erro_msg;
	              break;
	            }                 	
	          }
	        } else if ( $oTipoRetorno->ov17_tiporetorno == 4 ) {
	        	foreach ( $oRetorno->aRetornoTelefone as $iIndTel => $oRetornoTelefone ){
	        		$clOuvidoriaAtendimentoRetornoTelefone->ov14_ouvidoriaatendimento = $clOuvidoriaAtendimento->ov01_sequencial;
	        		$clOuvidoriaAtendimentoRetornoTelefone->ov14_numero               = $oRetornoTelefone->ov14_numero;
	        		$clOuvidoriaAtendimentoRetornoTelefone->ov14_ddd                  = $oRetornoTelefone->ov14_ddd;
	        		$clOuvidoriaAtendimentoRetornoTelefone->ov14_ramal                = $oRetornoTelefone->ov14_ramal;
	        		$clOuvidoriaAtendimentoRetornoTelefone->ov14_tipotelefone         = $oRetornoTelefone->ov14_tipotelefone;
	        		$clOuvidoriaAtendimentoRetornoTelefone->ov14_obs                  = $oRetornoTelefone->ov14_obs;
	        		$clOuvidoriaAtendimentoRetornoTelefone->incluir(null);
	            if ( $clOuvidoriaAtendimentoRetornoTelefone->erro_status == 0 ) {
	              $lErro    = true;
	              $sMsgErro = $clOuvidoriaAtendimentoRetornoTelefone->erro_msg;
	              break;
	            }               		
	        	}
	        }           			
	  		}
  		}
  	}
  }
  	
  if ( !$lErro ) {
  	 $sMsgErro = "Atendimento nº {$clOuvidoriaAtendimento->ov01_numero}/{$clOuvidoriaAtendimento->ov01_anousu} salvo com sucesso.";
  }
	
  if ( !$lErro && $oPost->sTipo == 'geraProcesso' ) {
      	
		if ( $oAtendimento->oTitularAtendimento->sTipo == 'CGM' ){
			$iCgm = $oAtendimento->oTitularAtendimento->iCodigo;
		} else {
			$rsConfig = $clDBConfig->sql_record($clDBConfig->sql_query_file(db_getsession('DB_instit')));
			if ( $clDBConfig->numrows > 0 ) {
				$oConfig = db_utils::fieldsMemory($rsConfig,0);
        $iCgm    = $oConfig->numcgm;				
			}
		}

    $iNumeroProcesso = '';
    try {
      $iNumeroProcesso = ProcessoProtocoloNumeracao::getProximoNumero();
    } catch (Exception $eErro) {
      $lErro = true;
      $sMsgErro = $eErro->getMessage();
    }
		 
    if ( !$lErro ) {
      $clProtProcesso->p58_codigo     = $oAtendimento->ov01_tipoprocesso;
      $clProtProcesso->p58_dtproc     = implode('-',array_reverse(explode('/',$oAtendimento->ov01_dataatend)));
      $clProtProcesso->p58_id_usuario = $oAtendimento->ov01_usuario;
      $clProtProcesso->p58_numcgm     = $iCgm;
      $clProtProcesso->p58_numero     = $iNumeroProcesso;
      $clProtProcesso->p58_requer     = utf8_decode($oAtendimento->ov01_requerente);
      $clProtProcesso->p58_coddepto   = $oAtendimento->ov01_depart;
      $clProtProcesso->p58_obs        = utf8_decode(pg_escape_string(db_stdClass::db_stripTagsJson($oAtendimento->ov01_solicitacao)));
      $clProtProcesso->p58_despacho   = utf8_decode(pg_escape_string(db_stdClass::db_stripTagsJson($oAtendimento->ov01_executado)));
      $clProtProcesso->p58_hora       = db_hora();
      $clProtProcesso->p58_interno    = 'false';
      $clProtProcesso->p58_publico    = 'false';
      $clProtProcesso->p58_ano        = db_getsession("DB_anousu");
      $clProtProcesso->p58_instit     = db_getsession('DB_instit');
      $clProtProcesso->incluir(null);
      
      if ( $clProtProcesso->erro_status == 0 ) {
        $lErro    = true;
        $sMsgErro = $clProtProcesso->erro_msg;
      }       		
    }
		
    if ( !$lErro ) {
    	
    	$clProcessoOuvidoria->ov09_ouvidoriaatendimento = $clOuvidoriaAtendimento->ov01_sequencial; 
    	$clProcessoOuvidoria->ov09_protprocesso         = $clProtProcesso->p58_codproc;
    	$clProcessoOuvidoria->ov09_principal            = 'true';
    	$clProcessoOuvidoria->incluir(null);
    	
      if ( $clProcessoOuvidoria->erro_status == 0 ) {
	      $lErro    = true;
	      $sMsgErro = $clProcessoOuvidoria->erro_msg;
      }    	
    	
    }
    
    if ( !$lErro ) {
     $sMsgErro .= "\nGerado com sucesso Processo nº {$clProtProcesso->p58_codproc}";
    }    
      	
  }
  
  if ( !$lErro && $oPost->sTipo == 'anexaProcesso' ) {
  	
    $clProcessoOuvidoria->ov09_ouvidoriaatendimento = $clOuvidoriaAtendimento->ov01_sequencial; 
    $clProcessoOuvidoria->ov09_protprocesso         = $oPost->iCodProc;
    $clProcessoOuvidoria->ov09_principal            = 'false';
    $clProcessoOuvidoria->incluir(null);
      
    if ( $clProcessoOuvidoria->erro_status == 0 ) {
      $lErro    = true;
      $sMsgErro = $clProcessoOuvidoria->erro_msg;
    }  	
  	
    if ( !$lErro ) {
      $sMsgErro .= "\nAnexado ao Processo nº {$oPost->iCodProc}";
    }        

    try {
      $oProcessoOuvidoria->incluirDespachoInterno($oPost->iCodProc,$oAtendimento->ov01_solicitacao);
    } catch (Exception $eException) {
    	$lErro    = true;
    	$sMsgErro = $eException->getMessage();  
    }
    
  }
  
  db_fim_transacao($lErro);

  $aRetorno = array("lErro"=>$lErro,
                    "sMsg" =>urlencode($sMsgErro),
                    "iAtendimento"=>$clOuvidoriaAtendimento->ov01_numero,
                    "iAno"=>db_getsession('DB_anousu'));		

  echo $oJson->encode($aRetorno);
  
  
} else if ( $oPost->sMethod == 'consultaAtendimento') {

	
	
	$aListaDoc         = array();
	$aListaEnder       = array();
	$aListaEmail       = array();
	$aListaTelefone    = array();
  $aListaTipoRetorno = array();
	
	$rsDadosAtendimento = $clOuvidoriaAtendimento->sql_record($clOuvidoriaAtendimento->sql_query_titular($oPost->iCodAtendimento));
	
	if ( $clOuvidoriaAtendimento->numrows > 0 ) {
		
		$oAtendimento = db_utils::fieldsMemory($rsDadosAtendimento,0,false, false,true);

    $rsDadosDoc = $clOuvidoriaAtendimentoDoc->sql_record($clOuvidoriaAtendimentoDoc->sql_query(null,"*",null,"ov19_ouvidoriaatendimento = {$oPost->iCodAtendimento} "));		
    
    /**
     * Verifica existencia de tramite inicial
     * Caso não exista, campos são  liberados para edição
     **/
    $oDaoProctransferproc = db_utils::getDao("proctransferproc");
    $sSqlProctransferproc = $oDaoProctransferproc->sql_query_file(null,$oAtendimento->ov09_protprocesso);
    $rsProctransferproc   = $oDaoProctransferproc->sql_record($sSqlProctransferproc);
    $oAtendimento->hasTramiteInicial = false;
    
    if($oDaoProctransferproc->numrows > 0 ) {
      $oAtendimento->hasTramiteInicial = true;
    }
    
    if ( $clOuvidoriaAtendimentoDoc->numrows > 0 ) {
    	$aListaDoc = db_utils::getColectionByRecord($rsDadosDoc,0,false,false,true);
    }

  	$rsDadosTipoRetorno = $clOuvidoriaAtendimentoTipoRetorno->sql_record($clOuvidoriaAtendimentoTipoRetorno->sql_query_file(null,"*",null,"ov17_ouvidoriaatendimento = {$oPost->iCodAtendimento} "));   
    if ( $clOuvidoriaAtendimentoTipoRetorno->numrows > 0 ) {
      $aListaTipoRetorno = db_utils::getColectionByRecord($rsDadosTipoRetorno,0,false,false,true);
    }    
    
		$rsRetornoEnder = $clOuvidoriaAtendimentoRetornoEnder->sql_record($clOuvidoriaAtendimentoRetornoEnder->sql_query_file(null,"*",null,"ov12_ouvidoriaantendimento = {$oPost->iCodAtendimento}"));
    if ( $clOuvidoriaAtendimentoRetornoEnder->numrows > 0 ) {
    	$aListaEnder = db_utils::getColectionByRecord($rsRetornoEnder,0,false,false,true);    
    }

	  $rsRetornoEmail = $clOuvidoriaAtendimentoRetornoEmail->sql_record($clOuvidoriaAtendimentoRetornoEmail->sql_query_file(null,"*",null,"ov13_ouvidoriaantendimento = {$oPost->iCodAtendimento}"));
    if ( $clOuvidoriaAtendimentoRetornoEmail->numrows > 0 ) {
      $aListaEmail = db_utils::getColectionByRecord($rsRetornoEmail,0,false,false,true);    
    }

	  $rsRetornoTelefone = $clOuvidoriaAtendimentoRetornoTelefone->sql_record($clOuvidoriaAtendimentoRetornoTelefone->sql_query_file(null,"*",null,"ov14_ouvidoriaatendimento = {$oPost->iCodAtendimento}"));
    if ( $clOuvidoriaAtendimentoRetornoTelefone->numrows > 0 ) {
      $aListaTelefone = db_utils::getColectionByRecord($rsRetornoTelefone,0,false,false,true);    
    }    
    
	} else {
		$lErro    = true;
		$sMsgErro = "Nenhum registro encontrado!";
	}
  
  $aRetorno = array("lErro"            =>$lErro,
                    "sMsg"             =>urlencode($sMsgErro),
                    "oAtendimento"     =>$oAtendimento,
                    "aListaDoc"        =>$aListaDoc, 
                    "aListaTipoRetorno"=>$aListaTipoRetorno,  
									  "aListaEnder"      =>$aListaEnder,
									  "aListaEmail"      =>$aListaEmail,
									  "aListaTelefone"   =>$aListaTelefone);   

  echo $oJson->encode($aRetorno);
  
  
} else if ( $oPost->sMethod == 'alterarAtendimento') {

  $oAtendimento = $oJson->decode(str_replace("\\","",$oPost->oAtendimento));  
  $aDocumento   = $oJson->decode(str_replace("\\","",$oPost->aDocumento));
  $oRetorno     = $oJson->decode(str_replace("\\","",$oPost->oRetorno));

  
  db_inicio_transacao();

  $clOuvidoriaAtendimento->ov01_sequencial        = $oPost->iCodAtendimento;
  $clOuvidoriaAtendimento->ov01_solicitacao       = utf8_decode(pg_escape_string(db_stdClass::db_stripTagsJson($oAtendimento->ov01_solicitacao))); 
  $clOuvidoriaAtendimento->ov01_executado         = utf8_decode(pg_escape_string(db_stdClass::db_stripTagsJson($oAtendimento->ov01_executado))); 
  $clOuvidoriaAtendimento->alterar($oPost->iCodAtendimento);

  if ( $clOuvidoriaAtendimento->erro_status == 0 ) {
    $lErro    = true;
    $sMsgErro = $clOuvidoriaAtendimento->erro_msg;
  }
  
  if ( !$lErro ) {
  	
  	$sWhereProcesso     = "    ov09_ouvidoriaatendimento = {$oPost->iCodAtendimento}";
  	$sWhereProcesso    .= " and ov09_principal is true                              ";
  	$sSqlDadosProcesso  = $clProcessoOuvidoria->sql_query_file(null,"ov09_protprocesso",null,$sWhereProcesso);
  	$rsDadosProcesso    = $clProcessoOuvidoria->sql_record($sSqlDadosProcesso);
  	
  	if ( $clProcessoOuvidoria->numrows > 0 ) {
  		
	  	$oDadosProcesso  = db_utils::fieldsMemory($rsDadosProcesso,0);
	  	
	  	$clProtProcesso->p58_codproc  = $oDadosProcesso->ov09_protprocesso; 
	  	$clProtProcesso->p58_obs      = utf8_decode(pg_escape_string(db_stdClass::db_stripTagsJson($oAtendimento->ov01_solicitacao)));
	  	$clProtProcesso->p58_despacho = utf8_decode(pg_escape_string(db_stdClass::db_stripTagsJson($oAtendimento->ov01_executado)));
	
	  	$clProtProcesso->alterar($oDadosProcesso->ov09_protprocesso);
	  	
	  	if ( $clProtProcesso->erro_status == 0 ) {
	  		$lErro    = true;
	  		$sMsgErro = $clProtProcesso->erro_msg;
	  	}
	  	
  	}
  }
  
  if ( !$lErro ) {
    if ( count($aDocumento) > 0 ) {
      foreach ($aDocumento as $iInd => $oDocumento ){

        $sWhereAlterarDocumento  = "     ov19_ouvidoriaatendimento = {$oPost->iCodAtendimento}   ";
        $sWhereAlterarDocumento .= " and ov19_procdoc              = {$oDocumento->ov19_procdoc} ";
        $sSqlDocumentos = $clOuvidoriaAtendimentoDoc->sql_query_file(null,"ov19_sequencial",null,$sWhereAlterarDocumento);
        $rsConsultaDocumentos    = $clOuvidoriaAtendimentoDoc->sql_record($sSqlDocumentos);

        if ( $clOuvidoriaAtendimentoDoc->numrows > 0 ) {
        	$oDadosDocumento = db_utils::fieldsMemory($rsConsultaDocumentos,0);
        	$clOuvidoriaAtendimentoDoc->ov19_sequencial = $oDadosDocumento->ov19_sequencial;
	        $clOuvidoriaAtendimentoDoc->ov19_entregue   = $oDocumento->ov19_entregue;
	        $clOuvidoriaAtendimentoDoc->alterar($oDadosDocumento->ov19_sequencial);
	        if ( $clOuvidoriaAtendimentoDoc->erro_status == 0 ) {
	          $lErro    = true;
	          $sMsgErro = $clOuvidoriaAtendimentoDoc->erro_msg;
	          break;
	        }
        }
                 
      } 
    }
  }
  
  if ( !$lErro ) {
  	$clOuvidoriaAtendimentoTipoRetorno->excluir(null,"ov17_ouvidoriaatendimento = {$oPost->iCodAtendimento}");
  	if ( $clOuvidoriaAtendimentoTipoRetorno->erro_status == 0 ) {
  		$lErro = true;
  		$sMsgErro = $clOuvidoriaAtendimentoTipoRetorno->erro_msg;
  	}
  }
  if ( !$lErro ) {
    $clOuvidoriaAtendimentoRetornoEnder->excluir(null,"ov12_ouvidoriaantendimento = {$oPost->iCodAtendimento}");
  	
    if ( $clOuvidoriaAtendimentoRetornoEnder->erro_status == 0 ) {
      $lErro = true;
      $sMsgErro = $clOuvidoriaAtendimentoRetornoEnder->erro_msg;
    }
  }
  if ( !$lErro ) {
    $clOuvidoriaAtendimentoRetornoEmail->excluir(null,"ov13_ouvidoriaantendimento = {$oPost->iCodAtendimento}");
    if ( $clOuvidoriaAtendimentoRetornoEmail->erro_status == 0 ) {
      $lErro = true;
      $sMsgErro = $clOuvidoriaAtendimentoRetornoEmail->erro_msg;
    }
  }      
  if ( !$lErro ) {
    $clOuvidoriaAtendimentoRetornoTelefone->excluir(null,"ov14_ouvidoriaatendimento = {$oPost->iCodAtendimento}");
    if ( $clOuvidoriaAtendimentoRetornoTelefone->erro_status == 0 ) {
      $lErro = true;
      $sMsgErro = $clOuvidoriaAtendimentoRetornoTelefone->erro_msg;
    }
  }        
  
  if ( !$lErro ) {
    if ( count($oRetorno->aTipoRetorno) > 0 ) {
      foreach ( $oRetorno->aTipoRetorno as $iInd => $oTipoRetorno ){
        
        $clOuvidoriaAtendimentoTipoRetorno->ov17_tiporetorno          = $oTipoRetorno->ov17_tiporetorno; 
        $clOuvidoriaAtendimentoTipoRetorno->ov17_ouvidoriaatendimento = $clOuvidoriaAtendimento->ov01_sequencial;
        $clOuvidoriaAtendimentoTipoRetorno->incluir(null);
        
        if ( $clOuvidoriaAtendimentoTipoRetorno->erro_status == 0 ) {
          $lErro    = true;
          $sMsgErro = $clOuvidoriaAtendimentoTipoRetorno->erro_msg;
          break;
        }
        
        if ( $oTipoRetorno->ov17_tiporetorno == 1 || $oTipoRetorno->ov17_tiporetorno == 2 ) {
          foreach ( $oRetorno->aRetornoEndereco as $iIndEnd => $oRetornoEndereco ){
            $clOuvidoriaAtendimentoRetornoEnder->ov12_ouvidoriaantendimento = $clOuvidoriaAtendimento->ov01_sequencial;
            $clOuvidoriaAtendimentoRetornoEnder->ov12_endereco              = $oRetornoEndereco->ov12_endereco;
            $clOuvidoriaAtendimentoRetornoEnder->ov12_numero                = $oRetornoEndereco->ov12_numero;
            $clOuvidoriaAtendimentoRetornoEnder->ov12_compl                 = $oRetornoEndereco->ov12_compl;
            $clOuvidoriaAtendimentoRetornoEnder->ov12_bairro                = $oRetornoEndereco->ov12_bairro;
            $clOuvidoriaAtendimentoRetornoEnder->ov12_munic                 = $oRetornoEndereco->ov12_munic;
            $clOuvidoriaAtendimentoRetornoEnder->ov12_uf                    = $oRetornoEndereco->ov12_uf;
            $clOuvidoriaAtendimentoRetornoEnder->ov12_cep                   = $oRetornoEndereco->ov12_cep;
            $clOuvidoriaAtendimentoRetornoEnder->incluir(null);
            if ( $clOuvidoriaAtendimentoRetornoEnder->erro_status == 0 ) {
              $lErro    = true;
              $sMsgErro = $clOuvidoriaAtendimentoRetornoEnder->erro_msg;
              break;
            }                   
          }
        } else if ( $oTipoRetorno->ov17_tiporetorno == 3 ) {
          foreach ( $oRetorno->aRetornoEmail as $iIndEmail => $oRetornoEmail ){
            $clOuvidoriaAtendimentoRetornoEmail->ov13_ouvidoriaantendimento = $clOuvidoriaAtendimento->ov01_sequencial;          
            $clOuvidoriaAtendimentoRetornoEmail->ov13_email                 = $oRetornoEmail->ov13_email;
            $clOuvidoriaAtendimentoRetornoEmail->incluir(null);
            if ( $clOuvidoriaAtendimentoRetornoEmail->erro_status == 0 ) {
              $lErro    = true;
              $sMsgErro = $clOuvidoriaAtendimentoRetornoEmail->erro_msg;
              break;
            }                   
          }
        } else if ( $oTipoRetorno->ov17_tiporetorno == 4 ) {
          foreach ( $oRetorno->aRetornoTelefone as $iIndTel => $oRetornoTelefone ){
            $clOuvidoriaAtendimentoRetornoTelefone->ov14_ouvidoriaatendimento = $clOuvidoriaAtendimento->ov01_sequencial;
            $clOuvidoriaAtendimentoRetornoTelefone->ov14_numero               = $oRetornoTelefone->ov14_numero;
            $clOuvidoriaAtendimentoRetornoTelefone->ov14_ddd                  = $oRetornoTelefone->ov14_ddd;
            $clOuvidoriaAtendimentoRetornoTelefone->ov14_ramal                = $oRetornoTelefone->ov14_ramal;
            $clOuvidoriaAtendimentoRetornoTelefone->ov14_tipotelefone         = $oRetornoTelefone->ov14_tipotelefone;
            $clOuvidoriaAtendimentoRetornoTelefone->ov14_obs                  = $oRetornoTelefone->ov14_obs;
            $clOuvidoriaAtendimentoRetornoTelefone->incluir(null);
            if ( $clOuvidoriaAtendimentoRetornoTelefone->erro_status == 0 ) {
              $lErro    = true;
              $sMsgErro = $clOuvidoriaAtendimentoRetornoTelefone->erro_msg;
              break;
            }                   
          }
        }                 
      }
    }
  }
    
  if ( !$lErro ) {
     $sMsgErro = "Alteração concluída com sucesso!!";
  }
  
  db_fim_transacao($lErro);
  
  $aRetorno = array("lErro"=>$lErro,
                    "sMsg" =>urlencode($sMsgErro));   

  echo $oJson->encode($aRetorno);
  
  
}  
?>
