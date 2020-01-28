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

/**
 * Classe responsavel retornar os dados da instituição para o webservice
 * @author Everton Catto Heckler <everton.heckler@dbseller.com.br>
 * @package webservices
 */

class InstituicaoWebservice {
  
  /**
   * Instancia da Prefeitura
   * @var Instituicao
   */
  protected $oInstituicao;
  
  /**
   * Código da Instituição
   * @var integer
   */
  protected $iCodigoInstituicao = null;
  
  /**
   * Boolean para identificar uma prefeitura
   * @var boolean
   */
  protected $lPrefeitura = true;
  
  /**
   * Arquivo Imagem do Logo da Instituição
   * @var string
   */
  protected $sImagemLogo;
  
  
  /**
   * Instancia o webservice
   * @param integer $iCodigoInstituicao Código da Instituição
   */
  public function __construct($iCodigoInstituicao) { 

    $this->oInstituicao = new Instituicao($iCodigoInstituicao);
  }
  
  /**
   * Seta Código da Instituição
   * @param integer $iCodigoInstituicao Código da Instituição
   */
  public function setCodigo($iCodigoInstituicao) {
  
    $this->iCodigoInstituicao = $iCodigoInstituicao; 
  }
  
  /**
   * Seta o tipo de instituição
   * @param integer Codigo do Tipo de Instituição
   */
  public function setPrefeitura($lPrefeitura) {
  
    $this->lPrefeitura = $lPrefeitura;
  }
  
  
  /**
   * Retorna os dados da instituição
   * @return stdClass
   */
  public function getDadosInstituicao() {
    
    if (!empty($this->iCodigoInstituicao) && !empty($this->iCodigoTipoInstituicao)) {
      throw new Exception('Nenhum parâmetro exigido foi informado.');
    }
    
    if (!empty($this->iCodigoInstituicao)) {
     
      $this->oInstituicao = new Instituicao($this->iCodigoInstituicao);
    } else {

      $oInstituicao = new Instituicao();
      $this->oInstituicao = $oInstituicao->getDadosPrefeitura();
    }
    
    $oRetorno = new stdClass();
    
    $oRetorno->sDescricao           = utf8_encode($this->oInstituicao->getDescricao());
    $oRetorno->sDescricaoAbreviada  = utf8_encode($this->oInstituicao->getDescricaoAbreviada());
    $oRetorno->sCnpj                = utf8_encode($this->oInstituicao->getCNPJ());
    $oRetorno->sLogradouro          = utf8_encode($this->oInstituicao->getLogradouro());
    $oRetorno->sMunicipio           = utf8_encode($this->oInstituicao->getMunicipio());
    $oRetorno->sBairro              = utf8_encode($this->oInstituicao->getBairro());
    $oRetorno->sTelefone            = utf8_encode($this->oInstituicao->getTelefone());
    $oRetorno->sSite                = utf8_encode($this->oInstituicao->getSite());
    $oRetorno->sEmail               = utf8_encode($this->oInstituicao->getEmail());
    $oRetorno->sIbge                = utf8_encode($this->oInstituicao->getCodigoIbge());
    $oRetorno->iNumeroCgm           = utf8_encode($this->oInstituicao->getNumeroCgm());
    $oRetorno->sNumero              = utf8_encode($this->oInstituicao->getNumero());
    $oRetorno->sComplemento         = utf8_encode($this->oInstituicao->getComplemento());
    $oRetorno->sUf                  = utf8_encode($this->oInstituicao->getUf());
    $oRetorno->sCep                 = utf8_encode($this->oInstituicao->getCep());
    $oRetorno->sFax                 = utf8_encode($this->oInstituicao->getFax());
   
    $oRetorno->sLogoPrefeituraBaseEncode = NULL;
    
    if ($this->oInstituicao->getImagemLogo() != "") {
     
      $sCaminhoImagem   = 'imagens/files/'.$this->oInstituicao->getImagemLogo();
      $oArquivo         = fopen($sCaminhoImagem, 'r');
      $oArquivoConteudo = fread($oArquivo, filesize($sCaminhoImagem));
      
      $oRetorno->sLogoPrefeituraBaseEncode =  base64_encode($oArquivoConteudo);
    }
    
    return $oRetorno;
  }
}