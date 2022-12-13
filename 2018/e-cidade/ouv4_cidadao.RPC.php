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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/exceptions/BusinessException.php"));
require_once(modification("libs/exceptions/ParameterException.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("std/DBDate.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oJson               = new Services_JSON();
$oParam              = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';

try {
  
  switch($oParam->sExecucao) {
    
    /**
     * Salva os dados do cidadão
     *
     * @param integer iCidadao    - Código do cidadão
     * @param string sNome        - Nome do cidadão
     * @param string sIdentidade  - Identidade do cidadão
     * @param string sCpf         - CPF do cidadão
     * @param string dtNascimento - Data de nascimento do cidadão ('00/00/0000')
     * @param string sSexo        - Sexo do cidadão ('M' / 'F')
     * @param string sEndereco    - Endereço do cidadão
     * @param integer iNumero     - Número do endereço do cidadão
     * @param string sBairro      - Bairro do cidadão
     * @param string sComplemento - Complemento do endereço do cidadão
     * @param string sUf          - UF do estado do cidadão ('RS')
     * @param string sMunicipio   - Município do cidadão
     * @param string sCep         - CEP do cidadão
     * @param array aEmail        - Array com os dados do email
     *               stdClass sPrincipal - Informa se o email é o principal ou não ('Sim' / 'Não')
     *                        sEmail     - Email
     * @param array aTelefone - Array com os telefones cadastrados para o cidadão
     *               stdClass string sPrincipal   - Informa se o telefone é o principal ou não ('Sim' / 'Não')
     *                        integer iCodigoTipo - Código do tipo de telefone
     *                        integer iDDD        - DDD do telefone
     *                        integer iRamal      - Ramal do telefone
     *                        text sObservacoes   - Observações em relação ao telefone
     */
  	case 'salvar':
  	  
  	  db_inicio_transacao();
  	  
  	  if (isset($oParam->iCidadao)) {
  	    
  	    $oCidadao = CidadaoRepository::getCidadaoByCodigo($oParam->iCidadao);
  	    $oCidadao->setNome(db_stdClass::normalizeStringJson($oParam->sNome));
  	    $oCidadao->setIdentidade(db_stdClass::normalizeStringJson($oParam->sIdentidade));
  	    $oCidadao->setCpfCnpj(db_stdClass::normalizeStringJson($oParam->sCpf));
  	    
  	    if (!empty($oParam->dtNascimento)) {
  	      
    	    $oDataNascimento = new DBDate(db_stdClass::normalizeStringJson($oParam->dtNascimento));
    	    $dtNascimento    = $oDataNascimento->getDate();
    	    $oCidadao->setDataNascimento($dtNascimento);
  	    }
  	    
  	    $oCidadao->setSexo($oParam->sSexo);
  	    $oCidadao->setSituacaoCidadao(2);
  	    $oCidadao->setAtivo(true);
  	    $oCidadao->setEndereco(db_stdClass::normalizeStringJson($oParam->sEndereco));
  	    $oCidadao->setNumero($oParam->iNumero);
  	    $oCidadao->setBairro(db_stdClass::normalizeStringJson($oParam->sBairro));
  	    $oCidadao->setComplemento(db_stdClass::normalizeStringJson($oParam->sComplemento));
  	    $oCidadao->setUF(db_stdClass::normalizeStringJson($oParam->sUf));
  	    $oCidadao->setMunicipio(db_stdClass::normalizeStringJson($oParam->sMunicipio));
  	    $oCidadao->setCEP(db_stdClass::normalizeStringJson($oParam->sCep));
  	    $oCidadao->removerEmail();
  	    
  	    foreach ($oParam->aEmail as $oEmail) {
  	      
  	      $lPrincipalEmail = ( strtolower( db_stdClass::normalizeStringJson($oEmail->sPrincipal) ) == 'sim');
  	      $oCidadao->adicionarEmail(db_stdClass::normalizeStringJson($oEmail->sEmail), $lPrincipalEmail);
  	    }
  	    
  	    foreach ($oParam->aTelefone as $oTelefone) {
  	      
  	      $lPrincipalTelefone = ( strtolower( db_stdClass::normalizeStringJson($oTelefone->sPrincipal) ) == 'sim');
  	      
  	      $oCidadao->adicionarTelefone(
  	                                    $oTelefone->iNumero,
  	                                    $oTelefone->iCodigoTipo,
  	                                    $lPrincipalTelefone,
  	                                    $oTelefone->iDDD,
  	                                    db_stdClass::normalizeStringJson($oTelefone->iRamal),
  	                                    db_stdClass::normalizeStringJson($oTelefone->sObservacoes)
                                      );
  	    }
  	    
  	    $oCidadao->salvar();
  	    $oRetorno->iCodigoCidadao = $oCidadao->getCodigo();
  	    $oRetorno->sMensagem = urlencode(_M('patrimonial.ouvidoria.ouv4_cidadao.cidadao_salvo'));
  	  }
  	  
  	  db_fim_transacao();
  	  break;
  	  
  	/**
  	 * Retorna os dados de um cidadão
  	 */
  	case 'getDados':
  	  
  	  if (!empty($oParam->iCidadao)) {
  	    
  	    $oCidadao = CidadaoRepository::getCidadaoByCodigo($oParam->iCidadao);
  	    $oRetorno->sNome        = urlencode($oCidadao->getNome());
  	    $oRetorno->sIdentidade  = urlencode($oCidadao->getIdentidade());
  	    $oRetorno->sCpf         = urlencode($oCidadao->getCpfCnpj());
  	    $oRetorno->dtNascimento = urlencode($oCidadao->getDataNascimento());
  	    $oRetorno->sSexo        = urlencode($oCidadao->getSexo());
  	    $oRetorno->sEndereco    = urlencode($oCidadao->getEndereco());
  	    $oRetorno->iNumero      = $oCidadao->getNumero();
  	    $oRetorno->sBairro      = urlencode($oCidadao->getBairro());
  	    $oRetorno->sComplemento = urlencode($oCidadao->getComplemento());
  	    $oRetorno->sUf          = urlencode($oCidadao->getUF());
  	    $oRetorno->sMunicipio   = urlencode($oCidadao->getMunicipio());
  	    $oRetorno->sCep         = urlencode($oCidadao->getCEP());
  	    $oRetorno->aEmail       = array();
  	    $oRetorno->aTelefones   = array();
  	    
  	    foreach ($oCidadao->getEmails() as $oEmail) {
  	      
  	      $oDadosEmail             = new stdClass();
  	      $oDadosEmail->sEmail     = urlencode($oEmail->getEmail());
  	      $oDadosEmail->sPrincipal = urlencode($oEmail->isPrincipal() ? 'Sim' : 'Não');
  	      $oDadosEmail->lPrincipal = $oEmail->isPrincipal();
  	      $oRetorno->aEmail[]      = $oDadosEmail;
  	    }
  	    
  	    foreach ($oCidadao->getTelefones() as $oTelefone) {
  	      
  	      $oDadosTelefone               = new stdClass();
  	      $oDadosTelefone->iCodigo      = $oTelefone->getCodigoTelefone();
  	      $oDadosTelefone->iTipo        = $oTelefone->getCodigoTipoTelefone();
  	      $oDadosTelefone->sDDD         = urlencode($oTelefone->getDDD());
  	      $oDadosTelefone->iNumero      = $oTelefone->getNumeroTelefone();
  	      $oDadosTelefone->sRamal       = urlencode($oTelefone->getRamal());
  	      $oDadosTelefone->sPrincipal   = urlencode($oTelefone->isTelefonePrincipal() ? 'Sim' : 'Não');
  	      $oDadosTelefone->lPrincipal   = $oTelefone->isTelefonePrincipal();
  	      $oDadosTelefone->sObservacoes = urlencode($oTelefone->getObservacao());
  	      $oRetorno->aTelefones[]       = $oDadosTelefone;
  	    }
  	  }
  	  break;
  }
} catch (ParameterException $oErro) {

  db_fim_transacao(true);
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode($oErro->getMessage());
} catch (BusinessException $oErro) {

  db_fim_transacao(true);
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode($oErro->getMessage());
} catch (DBException $oErro) {

  db_fim_transacao(true);
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode($oErro->getMessage());
}

echo $oJson->encode($oRetorno);
?>