<?php 
  /**
   * Define espécie do documento
   * 
   */
  $this->especie_doc     = "DAM"; 
  /**
   * Define código da carteira 
   */
  $sSqlCarteiraConvenio  = "select ar13_carteira,                                                     ";
  $sSqlCarteiraConvenio .= "       ar13_variacao                                                      ";
  $sSqlCarteiraConvenio .= "  from cadconvenio                                                        ";
  $sSqlCarteiraConvenio .= "       inner join conveniocobranca on ar13_cadconvenio = ar11_sequencial  ";
  $sSqlCarteiraConvenio .= " where ar11_sequencial = {$this->codigoConvenio};                         ";
  
  $rsSqlCarteiraConvenio = db_query($sSqlCarteiraConvenio);
  $oCarteiraConvenio     = db_utils::fieldsMemory($rsSqlCarteiraConvenio,0);
  $this->carteira        = $oCarteiraConvenio->ar13_carteira."/".str_pad($oCarteiraConvenio->ar13_variacao,3,'0',STR_PAD_LEFT);
 
  $this->ufcgm           = "";
  $this->descr11_2       = "";
?>