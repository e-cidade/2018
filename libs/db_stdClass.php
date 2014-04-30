<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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


 class db_stdClass {
   
   /**
    * Retorna os dados da instituicao. caso nao seje informado a instituiηγo sera
    * retornado a instituicao da sessao
    *
    * @param integer $iInstit Cσdigo da instituicao;
    * @return object
    */
   function getDadosInstit($iInstit = null) {
     
      if (empty($iInstit)) {
        $iInstit = db_getsession("DB_instit");
      }
      
      $sSqlInstit = "select * from db_config where codigo = {$iInstit}";
      $rsInstit   = db_query($sSqlInstit);
      return db_utils::fieldsMemory($rsInstit, 0);
   }
   
   /**
    * Retorna os parametros configurados para a tabela de configuracao especificada.
    *
    * @param string $sClassParametro nome da classe de parametro
    * @param array $aKeys  parametros chaves da classe (metodo sql_query_file)
    * @param string $sFields lista de campos
    * @return object db_utils
    */
   function getParametro($sClassParametro, $aKeys = null, $sFields = "*") {
     
     /*
      * TODO buscar tabelas de parametro da tabela db_sysarquivo 
      */
     $aClassesValidas = array (
                               "empparametro",
                               "caiparametro",
                               "numpref",
                               "tarefaparam",
                               "cfiptu",
                               "",
                              );
                              
     if (empty($sFields)) {
       $sFields = "*";                              
     }
     if (!in_array($sClassParametro, $aClassesValidas)) {
       return false;
                                
     }
     $oRetorno       = false;
     $oClass         = db_utils::getDao($sClassParametro);
     $oReflectMethod = new ReflectionMethod ("cl_{$sClassParametro}::sql_query_file");
     $i = 0;
     foreach ($oReflectMethod->getParameters() as $i => $param) {

       $svar   = $param->getName();
       if (!$param->isOptional() || isset($aKeys[$i])) {
         $aParam[] = $aKeys[$i];
       } else if ($param->getName() == "campos" ){
         $aParam[] = $sFields;
       } else {
         $aParam[] = null;
       }
       $i++;
     }
     
     $sRetornoSql  = call_user_func_array(array(&$oClass,"sql_query_file"), $aParam);
     $rsRetornoSql = call_user_func_array(array(&$oClass,"sql_record"), array($sRetornoSql));
     $iNumRows     = $oClass->numrows;
     $oRetorno     = db_utils::getColectionByRecord($rsRetornoSql);     
     return $oRetorno;
   }
}   
   


?>