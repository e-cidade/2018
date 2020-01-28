<?

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
