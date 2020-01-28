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
 * Classe VO para Controle dos emails de um cidadao
 * @author dbseller
 *
 */
class CidadaoEmail {

  /**
   * Endereço do email
   * @var string
   */
  private $sEmail;

  /**
   * Email principal
   * @var string
   */
  private $lPrincipal = false;

  /**
   * Instancia um novo email de contato do cidadão
   * @param string $sEmail endereço de email
   * @param boolean $lPrincipal email é principal
   */
  public function __construct($sEmail, $lPrincipal) {

    $this->setEmail($sEmail);
    $this->setPrincipal($lPrincipal);
  }

  /**
   * Define o email de contato
   * @param unknown $sEmail string email
   */
  public function setEmail($sEmail) {

    if (!DBString::isEmail($sEmail)) {
      throw new ParameterException("Email {$sEmail} não é um endereço de email válido.");
    }
    $this->sEmail = $sEmail;
  }

  /**
   * Define se o email é principal
   * @param boolean $lPrincipal
   * @throws ParameterException
   */
  public function setPrincipal($lPrincipal) {

    if (!is_bool($lPrincipal)) {
      throw new ParameterException('Parametro $lPrincipal deve ser um boolean.');
    }
    $this->lPrincipal = $lPrincipal;
  }

  /**
   * retorna o endereço de email
   * @return string
   */
  public function getEmail() {
    return $this->sEmail;
  }

  /**
   * Verifica se o email passado como parametro é principal
   */
  public function isPrincipal () {
    return $this->lPrincipal;
  }
}