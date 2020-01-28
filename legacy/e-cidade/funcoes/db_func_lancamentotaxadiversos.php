<?
$campos  = "distinct lancamentotaxadiversos.y120_sequencial,                                                     ";
$campos .= "lancamentotaxadiversos.y120_cgm,                                                                     ";
$campos .= "cgm.z01_nome,                                                                                        ";
$campos .= "lancamentotaxadiversos.y120_taxadiversos,                                                            ";
$campos .= "taxadiversos.y119_natureza,                                                                          ";
$campos .= "lancamentotaxadiversos.y120_unidade,                                                                 ";
$campos .= "lancamentotaxadiversos.y120_periodo,                                                                 ";
$campos .= "lancamentotaxadiversos.y120_datainicio,                                                              ";
$campos .= "lancamentotaxadiversos.y120_datafim,                                                                 ";
$campos .= "lancamentotaxadiversos.y120_issbase,                                                                 ";
$campos .= "diversos.dv05_obs as db_observacao,                                                                  ";
$campos .= "case when diversoslancamentotaxa.dv14_diversos is null then 0 else 1 end as db_taxa_tem_calculo      ";