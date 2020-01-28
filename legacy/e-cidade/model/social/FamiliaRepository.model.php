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
 * Repositorio de Objetos Familia
 * @package social
 * 
 */
class FamiliaRepository {

  protected $aFamilias = array();
  
  protected static $oInstance;
  
  protected function __construct() {
    
  }
  /**
   * Retorna a Instacia do Repostory
   * @return FamiliaRepository
   */
  protected function getInstance() {
    
    if (self::$oInstance == null) {
      self::$oInstance = new FamiliaRepository();
    }
    return self::$oInstance;
  }
  
  /**
   * Retorna uma instancia de Familia pelo codigo
   * @param integer $iCodigoFamilia
   * @return Familia
   */
  public static function getFamiliaByCodigo($iCodigoFamilia) {
  
    if (!array_key_exists($iCodigoFamilia, FamiliaRepository::getInstance()->aFamilias)) {
      FamiliaRepository::getInstance()->aFamilias[$iCodigoFamilia] = new Familia($iCodigoFamilia);
    }
    return FamiliaRepository::getInstance()->aFamilias[$iCodigoFamilia];
  }
  
  /**
   * Retorna a familia pelo codigo da familia no cadastro Unico
   * @return Familia
   */
  public static function getFamiliaPorCodigoFamiliar($iCodigoFamiliar) {

    $oDaoFamilia       = new cl_cidadaofamiliacadastrounico();
    $sWhere            = "as15_codigofamiliarcadastrounico = '{$iCodigoFamiliar}'";
    $sSqlDadosFamilia  = $oDaoFamilia->sql_query_file(null, "as15_cidadaofamilia", null, $sWhere); 
    $rsDadosFamilia    = $oDaoFamilia->sql_record($sSqlDadosFamilia);
    $iCodigoSequencial = '';
    if ($oDaoFamilia->numrows > 0) {
      
       $iCodigoSequencial = db_utils::fieldsMemory($rsDadosFamilia, 0)->as15_cidadaofamilia;
       if (!isset(FamiliaRepository::getInstance()->aFamilias[$iCodigoSequencial])) {
         
         $oFamilia = new Familia($iCodigoSequencial);
         FamiliaRepository::getInstance()->adicionarFamilia($oFamilia);
       }
       
    }
    if (isset(FamiliaRepository::getInstance()->aFamilias[$iCodigoSequencial])) {
      return FamiliaRepository::getInstance()->aFamilias[$iCodigoSequencial];
    } else {
      return false;
    }
  }

  /**
   * Adiciona uma Familia ao Repositorio
   * @param Familia $oFamilia
   */
  public static function adicionarFamilia(Familia $oFamilia) {
    FamiliaRepository::getInstance()->aFamilias[$oFamilia->getCodigoSequencial()] = $oFamilia;
  }
  
  public static function removerFamilia(Familia $oFamilia) {
    
    if (isset(FamiliaRepository::getInstance()->aFamilias[$oFamilia->getCodigoSequencial()])) {
      unset(FamiliaRepository::getInstance()->aFamilias[$oFamilia->getCodigoSequencial()]);
    }
  }
  
  public static function getFamiliasByFilter($sFilter, $sSort = '') {
    
    $oDaoFamilia  = db_utils::getDao("cidadaofamilia");
    $sWhere       = $sFilter;
    $sCampos      = "distinct as04_sequencial as codigo_familia, ";
    $sCampos     .= " ov02_nome as nome";
    $sSqlFamilia  = $oDaoFamilia->sql_query_completa(null, $sCampos, $sSort, $sWhere);
    $rsFamilia    = $oDaoFamilia->sql_record($sSqlFamilia);
    if ($oDaoFamilia->numrows > 0) {
      
      for ($iFamilia = 0; $iFamilia < $oDaoFamilia->numrows; $iFamilia++) {
        
        $oDadosFamilia = db_utils::fieldsMemory($rsFamilia, $iFamilia);
        FamiliaRepository::getInstance()->adicionarFamilia(new Familia($oDadosFamilia->codigo_familia));
        unset($oDadosFamilia);
      }
    }
    return FamiliaRepository::getInstance()->aFamilias;   
  }
  
  protected function __clone(){}
  
  /**
   * 
   * @param unknown_type $iCodigoGrupoRespostaAvaliacao
   * @return Familia
   */
  public static function getFamiliaPorAvaliacao($iCodigoGrupoRespostaAvaliacao) {
    
    
    $oDaoFamiliaAvaliacao = db_utils::getDao("cidadaofamiliaavaliacao");
    $sWhere               = "as06_avaliacaogruporesposta = {$iCodigoGrupoRespostaAvaliacao}";
    $sSqlAvaliacao        = $oDaoFamiliaAvaliacao->sql_query_file(null, "as06_cidadaofamilia", null, $sWhere);
    $rsAvaliacao          = $oDaoFamiliaAvaliacao->sql_record($sSqlAvaliacao);
    $iLinhas              = $oDaoFamiliaAvaliacao->numrows;
    
    if ($rsAvaliacao && $iLinhas > 0) {
      
      for ($i = 0; $i < $iLinhas; $i++) {
        
        $iCodigoFamilia = db_utils::fieldsMemory($rsAvaliacao, $i)->as06_cidadaofamilia;
        return FamiliaRepository::getInstance()->getFamiliaByCodigo($iCodigoFamilia);
      }
    }
    return false; 
  }
}

?>