<?
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


class _db_fields {
  //
}

class db_utils {
  
  
  function db_utils()
  {
    
  }
  
  function fieldsMemory($rs, $idx, $formata=false, $mostra = false)
  {
    $oFields   = new _db_fields();
    $numFields = pg_num_fields($rs);
    for ($i = 0; $i < $numFields; $i++) {
      
      $sFieldName     = @pg_field_name($rs, $i);
      $sFieldType     = @pg_field_type($rs, $i);
      $sValor         = @pg_result($rs, $idx, $sFieldName);
      if ($formata) {
        
        switch ($sFieldType) {
          
        case "date" :
          if ($sValor != null) {
            $sValor = implode(array_reverse(explode("-",$sValor)));
          }
          break;
          default :
          $sValor  = stripslashes($sValor);
          break;
        }
        
      }
      if ($mostra) {
        echo $sFieldName ." => ".$sValor." <br>";
      }
      
      $oFields->$sFieldName = $sValor;
    }
    return $oFields;
  }
  
  function postMemory($aVetor, $mostra=false)
  {
    
    $oFields   = new _db_fields();
    for ($i = 0; $i < count($aVetor); $i++) {
      
      $sFieldName     = key($aVetor);
      $sValor         = current($aVetor);
      if ($mostra) {
        
        echo $sFieldName ." => ".$sValor." <br>";
      }
      
      $oFields->$sFieldName = $sValor;
      next($aVetor);
    }
    return $oFields;
  }
  
}

?>