<?php
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
 * controla filtros para relatorios de orcamento
 *
 */
class filtroOrcamento {
  
  private $aInstituicoes = array();
  /**
   * Enter description here...
   *
   * @param array $aInstituicoes lista com as instituicoes
   */
  function __construct($aInstituicoes = null) {
    $this->aInstituicoes = $aInstituicoes ;
  }
  
  /**
   * Retorna os orgaos 
   * @return array lista dos orgaos
   */
  function getOrgaos($iAnoUsu) {
    
    $oDaoOrcOrgao = db_utils::getDao("orcorgao");
    $sSqlOrgaos   = $oDaoOrcOrgao->sql_query_file($iAnoUsu,null,"o40_orgao as orgao, o40_descr as descricao","1");
    $rsOrgaos     = $oDaoOrcOrgao->sql_record($sSqlOrgaos);
    $aOrgaos      = db_utils::getColectionByRecord($rsOrgaos,false, false, true);
    return $aOrgaos;
     
  }
  
  /**
   * Retorna as Unidades
   * @param integer $iAnoUsu ano base 
   * @return array lista das unidades
   */
  function getUnidades($iAnoUsu) {
    
    $oDaoOrcunidade = db_utils::getDao("orcunidade");
    $sSqlUnidades   = $oDaoOrcunidade->sql_query($iAnoUsu, 
                                                 null,null,
                                                 "o41_orgao as orgao,
                                                  o41_unidade as unidade,
                                                  o41_descr as descricao",
                                                  "o41_orgao, o41_unidade");
    $rsUnidades     = $oDaoOrcunidade->sql_record($sSqlUnidades);
    $aUnidades      = db_utils::getColectionByRecord($rsUnidades,false, false, true);
    return $aUnidades;
     
  }
  
  /**
   * Retorna as funcoes
   *
   * @param  integer $iAnoUsu ano corrente
   * @return array lista de funcoes
   */
  function getFuncoes($iAnoUsu) {
    
    $oDaoOrcFuncao = db_utils::getDao("orcfuncao");
    $sSqlFuncao    = $oDaoOrcFuncao->sql_query_file(null,
                                                    "o52_funcao as funcao, 
                                                    o52_descr as descricao",
                                                    "o52_funcao"
                                                   );
    $rsFuncao      = $oDaoOrcFuncao->sql_record($sSqlFuncao);
    $aFuncoes      = db_utils::getColectionByRecord($rsFuncao, false, false, true);
    return $aFuncoes;
    
  }
  
  /**
   * Retorna as subfuncoes
   *
   * @param  integer $iAnoUsu ano corrente
   * @return array lista de subfuncoes
   */
  function getSubFuncoes($iAnoUsu) {
    
    $oDaoOrcSubFuncao = db_utils::getDao("orcsubfuncao");
    $sSqlSubFuncao    = $oDaoOrcSubFuncao->sql_query_file(null,
                                                          "o53_subfuncao as subfuncao,
                                                           o53_descr as descricao",
                                                          "o53_subfuncao"
                                                         );
    $rsSubFuncao      = $oDaoOrcSubFuncao->sql_record($sSqlSubFuncao);
    $aSubFuncoes      = db_utils::getColectionByRecord($rsSubFuncao, false, false, true);
    return $aSubFuncoes;
    
  }
  
  /**
   * Retorna os programas
   *
   * @param integer $iAnousu ano acorrente
   * @return array lista de programas
   */
  function getProgramas($iAnousu) {
    
    $oDaoOrcPrograma = db_utils::getDao("orcprograma");
    $sSqlPrograma    = $oDaoOrcPrograma->sql_query_file($iAnousu,
                                                        null,
                                                        "o54_programa as programa, o54_descr as descricao",
                                                        "o54_programa"
                                                       );
    $rsPrograma      = $oDaoOrcPrograma->sql_record($sSqlPrograma);
    $aProgramas      = db_utils::getColectionByRecord($rsPrograma, false, false, true);
    return $aProgramas;
    
  }
  
  /**
   * Retorna os projetos/atividades
   *
   * @param integer $iAnousu ano acorrente
   * @return array lista de projetos atividades
   */
  function getProjAtiv($iAnousu) {
    
    $oDaoOrcProjAtiv = db_utils::getDao("orcprojativ");
    $sSqlProjAtiv    = $oDaoOrcProjAtiv->sql_query_file($iAnousu, 
                                                        null,
                                                        "o55_Projativ as projativ, o55_descr as descricao",
                                                        "o55_projativ"
                                                       );
    $rsProjAtiv      = $oDaoOrcProjAtiv->sql_record($sSqlProjAtiv);
    $aProjAtivs      = db_utils::getColectionByRecord($rsProjAtiv, false, false, true);
    return $aProjAtivs;
    
  }
  
  function getElementos($iAnoUsu) {
    
    $sWhere          = "o58_anousu = {$iAnoUsu}";
    $oDaoOrcElemento = db_utils::getDao("orcelemento");
    $sSqlElemento    = $oDaoOrcElemento->sql_query_dotacao(null,
                                                           "distinct o56_elemento as elemento, o56_descr as descricao",
                                                           "o56_elemento",
                                                           $sWhere 
                                                          );
    $rsElemento      = $oDaoOrcElemento->sql_record($sSqlElemento);
    $aElementos      = db_utils::getColectionByRecord($rsElemento, false, false, true);
    return $aElementos;
  }
  /**
   * Retorna os recursos
   *
   * @return array lista de recursos
   */
  function getRecursos() {
    
    $oDaoOrcRecurso = db_utils::getDao("orctiporec");
    $sSqlRecurso    = $oDaoOrcRecurso->sql_query_file(null,
                                                      "o15_codigo as recurso,
                                                       o15_descr as descricao",
                                                      "o15_codigo"
                                                      );
    $rsRecurso      = $oDaoOrcRecurso->sql_record($sSqlRecurso);
    $aRecursos      = db_utils::getColectionByRecord($rsRecurso, false, false, true);
    return $aRecursos;
    
  }
}

?>