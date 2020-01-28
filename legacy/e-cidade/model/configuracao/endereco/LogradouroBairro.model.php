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
 * CLasse VO para intersecao das ruas e bairros
 * @author dbseller
 *
 */
class LogradouroBairro {

  /**
   * codigo do bairro
   * @var integer
   */
  protected $iCodigo = null;
  /**
   * instancia Rua
   * @var Logradouro
   */
  protected $oLogradouro = null;

  /**
   *Instancia do bairro
   * @var Bairro
   */
  protected $oBairro  = null;

  public function __construct($iCodigo = null) {

    if (!empty($iCodigo)) {

      if (!DBNumber::isInteger($iCodigo)) {
        throw new ParameterException('Parâmetro codigo deve ser do tipo inteiro');
      }
      $oDaoRuaBairro  = new cl_cadenderbairrocadenderrua();
      $sSqlRuaBairro = $oDaoRuaBairro->sql_query_file($iCodigo);
      $rsRuaBairro   = $oDaoRuaBairro->sql_record($sSqlRuaBairro);
      if ($oDaoRuaBairro->numrows == 0) {
        throw new BusinessException('Registro não encontrado no sistema');
      }

      $oDadosBairroRua   = db_utils::fieldsMemory($rsRuaBairro, 0);
      $this->iCodigo     = $oDadosBairroRua->db87_sequencial;
      $this->oLogradouro = new Logradouro((int)$oDadosBairroRua->db87_cadenderrua);
      $this->oBairro     = new Bairro($oDadosBairroRua->db87_cadenderbairro);
    }
  }

  /**
   * Retorna o codigo
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }
  
  /**
   * Retorna o Logradouro
   * @return Logradouro
   */
  public function getLogradouro() {
    return $this->oLogradouro;
  }

  /**
   * Retorna o Bairro
   * @return Bairro
   */
  public function getBairro() {
    return $this->oBairro;
  }
}