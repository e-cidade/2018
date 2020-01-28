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

require_once ("libs/db_autoload.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/exceptions/BusinessException.php");
require_once ("libs/exceptions/ParameterException.php");
require_once ("std/db_stdClass.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");

$oJson               = new Services_JSON();
$oParam              = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';

try {
  
  switch($oParam->sExecucao) {
    
    /**
     * Salva os dados do cidad�o
     *
     * @param integer iCidadao    - C�digo do cidad�o
     * @param string sNome        - Nome do cidad�o
     * @param string sIdentidade  - Identidade do cidad�o
     * @param string sCpf         - CPF do cidad�o
     * @param string dtNascimento - Data de nascimento do cidad�o ('00/00/0000')
     * @param string sSexo        - Sexo do cidad�o ('M' / 'F')
     * @param string sEndereco    - Endere�o do cidad�o
     * @param integer iNumero     - N�mero do endere�o do cidad�o
     * @param string sBairro      - Bairro do cidad�o
     * @param string sComplemento - Complemento do endere�o do cidad�o
     * @param string sUf          - UF do estado do cidad�o ('RS')
     * @param string sMunicipio   - Munic�pio do cidad�o
     * @param string sCep         - CEP do cidad�o
     * @param array aEmail        - Array com os dados do email
     *               stdClass sPrincipal - Informa se o email � o principal ou n�o ('Sim' / 'N�o')
     *                        sEmail     - Email
     * @param array aTelefone - Array com os telefones cadastrados para o cidad�o
     *               stdClass string sPrincipal   - Informa se o telefone � o principal ou n�o ('Sim' / 'N�o')
     *                        integer iCodigoTipo - C�digo do tipo de telefone
     *                        integer iDDD        - DDD do telefone
     *                        integer iRamal      - Ramal do telefone
     *                        text sObservacoes   - Observa��es em rela��o ao telefone
     */
  	case 'salvar':
  	  
  	  db_inicio_transacao();
  	  
  	  if (isset($oParam->iCidadao)) {
  	    
  	    $oCidadao = CidadaoRepository::getCidadaoByCodigo($oParam->iCidadao);
  	    $oCidadao->setNome(db_stdClass::normalizeStringJson($oParam->sNome));
  	    $oCidadao->setIdentidade(db_stdClass::normalizeStringJson($oParam->sIdentidade));
  	    $oCidadao->setCpfCnpj(db_stdClass::normalizeStringJson($oParam->sCpf));
  	    
  	    if (!empty($oParam->dtNascimento)) {
  	      
    	    $oDataNascimento = new DBDate($oParam->dtNascimento);
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
  	 * Retorna os dados de um cidad�o
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
  	      $oDadosEmail->sPrincipal = urlencode($oEmail->isPrincipal() ? 'Sim' : 'N�o');
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
  	      $oDadosTelefone->sPrincipal   = urlencode($oTelefone->isTelefonePrincipal() ? 'Sim' : 'N�o');
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