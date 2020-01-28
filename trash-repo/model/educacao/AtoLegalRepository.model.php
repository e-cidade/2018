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
 * Repositoy para os Atos Legais
 * @package   Educacao
 * @author    Trucolo - trucolo@dbseller.com.br
 * @version   $Revision: 1.3 $
 */

class AtoLegalRepository {

  private $aAtoLegal = array();
  private static $oInstance;

  private function __construct() {

  }
  private function __clone() {

  }

  /**
   * Retorna a inst�ncia do Reposit�rio
   * @return AtoLegalRepository
   */
  protected function getInstance() {

    if(self::$oInstance == null) {
      self::$oInstance = new AtoLegalRepository();
    }
    return self::$oInstance;
  }

  /**
   * Verifica se o ato legal possui inst�ncia, se n�o instancia e retorna a inst�ncia de Ato Legal
   * @param integer $iCodigoAtoLegal
   * @return AtoLegal
   */
  public static function getAtoLegalByCodigo($iCodigoAtoLegal) {

    if (!array_key_exists($iCodigoAtoLegal, AtoLegalRepository::getInstance()->aAtoLegal)) {
      AtoLegalRepository::getInstance()->aAtoLegal[$iCodigoAtoLegal] = new AtoLegal($iCodigoAtoLegal);
    }

    return AtoLegalRepository::getInstance()->aAtoLegal[$iCodigoAtoLegal];
  }

  /**
   * Busca o ato legal pelo n�mero
   * @param integer $iNumero
   * @return AtoLegal
   */
  public static function getAtoLegalByNumero($iNumero) {

    $oDaoAtoLegal   = db_utils::getDao('atolegal');
    $sWhereAtoLegal = "{ed05_c_numero = $iNumero}";
    $sSqlAtoLegal   = $oDaoAtoLegal->sql_query_file(null, 'ed05_i_codigo', '', $sWhereAtoLegal);
    $rsAtoLegal     = $oDaoAtoLegal->sql_record($sSqlAtoLegal);

    if ($oDaoAtoLegal->numrows > 0) {
      return AtoLegalRepository::getInstance()->getAtoLegalByCodigo(db_utils::fieldsMemory($rsAtoLegal, 0)->ed05_i_codigo);
    }
    return false;
  }

  /**
   * Adiciona um Ato Legal ao reposit�rio
   * @param AtoLegal $oAtoLegal
   * @return boolean
   */
  public static function adicionarAtoLegal(AtoLegal $oAtoLegal) {

    if(!array_key_exists($oAtoLegal->getCodigoAtoLegal(), AtoLegalRepository::getInstance()->aAtoLegal)) {
      AtoLegalRepository::getInstance()->aAtoLegal[$oAtoLegal->getCodigoAtoLegal()] = $oAtoLegal;
    }
    return true;
  }

  /**
   * Remove um Ato Legal do reposit�rio
   * @param AtoLegal $oAtoLegal
   * @return boolean
   */
  public static function removerAtoLegal(AtoLegal $oAtoLegal) {

    if (array_key_exists($oAtoLegal->getCodigoAtoLegal(), AtoLegalRepository::getInstance()->aAtoLegal)) {
      unset(AtoLegalRepository::getInstance()->aAtoLegal[$oAtoLegal->getCodigoAtoLegal()]);
    }
    return true;
  }
  
  public static function getAtosLegaisByEscola(Escola $oEscola) {
  	
    $sWhere = " ed19_i_escola = {$oEscola->getCodigo()} ";
    
    $oDaoAtoEscola = new cl_atoescola();
    $sSqlAtoEscola = $oDaoAtoEscola->sql_query_file(null, "ed19_i_ato", null, $sWhere);
    $rsAtoEscola   = $oDaoAtoEscola->sql_record($sSqlAtoEscola);
    $iLinhas       = $oDaoAtoEscola->numrows;
    
    $aAtosLegais = array();
    
    for ($i = 0; $i < $iLinhas; $i++) {
      $aAtosLegais[] = AtoLegalRepository::getInstance()->getAtoLegalByCodigo(db_utils::fieldsMemory($rsAtoEscola, $i)->ed19_i_ato); 
    }
    
    return $aAtosLegais;
  }
}