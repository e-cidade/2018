<?php 
  
  unset($iChaveParcelas, $sSqlVerifParcel, $sMensagemCarne);
  
  $sSqlVerifParcel  = " SELECT db_reciboweb.k99_numpre                                               ";
  $sSqlVerifParcel .= "   FROM db_reciboweb                                                          ";
  $sSqlVerifParcel .= "        INNER JOIN termo ON termo.v07_numpre       = db_reciboweb.k99_numpre  ";
  $sSqlVerifParcel .= "                        AND (termo.v07_totpar - 1) = db_reciboweb.k99_numpar  ";
  $sSqlVerifParcel .= "  WHERE k99_numpre_n = {$this->numnov_recibo}                                 ";
  $sSqlVerifParcel .= "    AND v07_desconto = 22                                                     ";
  
  $resultVerifParcel = pg_query($sSqlVerifParcel);
  
  if (pg_num_rows($resultVerifParcel) > 0 ) {
    
    $sMensagemCarne = "Solicitamos seu comparecimento no Setor de Cadatro e Atendimento para reparcelamento de débitos pendentes.";
    $this->historico .= $sMensagemCarne;
  }

?>