<?php
/**
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

/**
 * 
 * 
 * @package Pessoal
 * @author $Author: dbmarcos $
 * @version $Revision: 1.1 $
 */

class BaseRepository {
  
  const MENSAGEM = 'recursoshumanos.pessoal.BaseRepository.';
  
  /**
   *
   * @var BaseRepository 
   */
  private static $oInstance;
  
  /**
   *
   * @var Base
   */
  public $aBases = array();
  
  /**
   * Construtor private evita que a classe seja instanciada publicamente
   */
  private function __construct() { }
  
  /**
   * Retorna instância do repository
   *
   * @access public
   * @return BaseRepository
   */
  public static function getInstance() {
    
    if(BaseRepository::$oInstance == null) {
      BaseRepository::$oInstance = new BaseRepository();
    }

    return BaseRepository::$oInstance;
  }
  
  /**
   * Remove a base no repository
   * 
   * @param Base $oBase
   * @return Boolean
   */
  public static function removeBase(Base $oBase) {
    
    $iAno         = $oBase->getCompetencia()->getAno();
    $iMes         = $oBase->getCompetencia()->getMes();
    $iCodigo      = $oBase->getCodigo();
    $iInstituicao =  $oBase->getInstituicao()->getCodigo();
    $sChave       = "{$iAno}{$iMes}{$iCodigo}{$iInstituicao}";
    
    if(array_key_exists($sChave, BaseRepository::getInstance()->aBases)) {
      unset(BaseRepository::getInstance()->aBases[$sChave]);
    }
    
    return true;
  }
  
  /**
   * Adiciona a base no repository
   * 
   * @param Base $oBase
   * @return Boolean
   */
  public static function adicionarBase(Base $oBase) {
    
    $iAno         = $oBase->getCompetencia()->getAno();
    $iMes         = $oBase->getCompetencia()->getMes();
    $iCodigo      = $oBase->getCodigo();
    $iInstituicao =  $oBase->getInstituicao()->getCodigo();
    $sChave       = "{$iAno}{$iMes}{$iCodigo}{$iInstituicao}";
    
    if(!array_key_exists($sChave, BaseRepository::getInstance()->aBases)) {
      BaseRepository::getInstance()->aBases[$sChave] = $oBase;
    }
    
    return true;
  }
  
  /**
   * Retorna a base
   * 
   * @param String $sCodigo
   * @param DBCompetencia $oCompetencia
   * @param Instituicao $oInstituicao
   * @return Base
   */
  public static function getBase($sCodigo, DBCompetencia $oCompetencia, Instituicao $oInstituicao) {
    
    if (array_key_exists($sCodigo, BaseRepository::getInstance()->aBases)) {
      
      foreach (BaseRepository::getInstance()->aBases as $oBase) {
        
        if ($oInstituicao->getCodigo() == $oBase->getInstituicao()->getCodigo() &&
            $oCompetencia->getAno()    == $oBase->getCompetencia()->getAno()    &&  
            $oCompetencia->getMes()    == $oBase->getCompetencia()->getMes()) {
          
          return $oBase;
        }
      }
    }
    
    return BaseRepository::procurarBase($sCodigo, $oCompetencia, $oInstituicao);   
  }
  
  /**
   * Procura a base conforme os parâmetros passados
   * 
   * @param String $sCodigo
   * @param DBCompetencia $oCompetencia
   * @param Instituicao $oInstituicao
   * @return Base
   * @throws DBException
   */
  private static function procurarBase($sCodigo, DBCompetencia $oCompetencia, Instituicao $oInstituicao) {
    
    $oDaoBases = new cl_bases();
    $sSqlBases = $oDaoBases->sql_query($oCompetencia->getAno(), $oCompetencia->getMes(), $sCodigo, $oInstituicao->getCodigo());
    $rsBases   = db_query($sSqlBases);
    
    if (!$rsBases) {
     throw new DBException(_M(self::MENSAGEM . 'erro_pesquisar_bases'));
    }

    if (pg_num_rows($rsBases) == 0) {
      return new Base();
    }
    
    $oDadosBases  = db_utils::fieldsMemory($rsBases, 0);    
    
    $oBase = new Base($oDadosBases->r08_codigo, $oCompetencia, $oInstituicao);
    $oBase->setNome($oDadosBases->r08_descr);
    $oBase->setCalculoPontoFixo($oDadosBases->r08_pfixo);
    $oBase->setCalculoQuantidade($oDadosBases->r08_calqua);
    $oBase->setValorMesAnterior($oDadosBases->r08_mesant);
    
    $sSqlRubricas         = $oDaoBases->sql_query_rubricas($oCompetencia->getAno(), 
                                                           $oCompetencia->getMes(), 
                                                           $oDadosBases->r08_codigo, 
                                                           $oInstituicao->getCodigo(), 
                                                           "r09_rubric");
    $rsRubricas           = db_query($sSqlRubricas);
    
    if (!$rsRubricas) {
     throw new DBException(_M(self::MENSAGEM . 'erro_pesquisar_rubricas'));
    }
    
    $aRubricasEncontradas = db_utils::getCollectionByRecord($rsRubricas);
    $aRubricas            = array();
    
    foreach ($aRubricasEncontradas as $oRubrica) {
      $aRubricas[] = RubricaRepository::getInstanciaByCodigo($oRubrica->r09_rubric, $oInstituicao->getCodigo());
    }
    
    $oBase->setRubricas($aRubricas);
    BaseRepository::adicionarBase($oBase); 
    return $oBase;
  } 
  
}
