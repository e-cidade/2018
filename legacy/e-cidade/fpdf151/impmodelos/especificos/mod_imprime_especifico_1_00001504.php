<?php 
  
  unset($iChaveParcelas, $sSqlVerifParcel, $sMensagemCarne);
  
  $sSqlVerifParcel  = " SELECT DISTINCT true                                                     ";
  $sSqlVerifParcel .= "   FROM arrecad                                                           ";
  $sSqlVerifParcel .= "        INNER JOIN termo ON termo.v07_numpre = arrecad.k00_numpre         ";
  $sSqlVerifParcel .= "                        AND (termo.v07_totpar - 1) = arrecad.k00_numpar   ";
  $sSqlVerifParcel .= "  WHERE arrecad.k00_numpre = ".substr($this->descr9, 0, 8)."              ";
  $sSqlVerifParcel .= "    AND arrecad.k00_numpar = ".(int)substr($this->descr9, 8, 11)."        ";
  
  $resultVerifParcel = pg_query($sSqlVerifParcel);
  
  if (pg_num_rows($resultVerifParcel) > 0 ) {
    
    $sMensagemCarne = "Solicitamos seu comparecimento no Setor de Cadatro e Atendimento para reparcelamento de débitos pendentes.";
    
    $this->descr12_2 .= $sMensagemCarne;
    
    if (strlen($this->descr4_2) <= 20) { 
    
      $this->descr4_2  .= $sMensagemCarne;
    }
  }

?>