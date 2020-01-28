<?php

use Classes\PostgresMigration;

class M8311AjustesSaldoContabil extends PostgresMigration
{
  public function up()
  {

    $this->execute("drop table if exists bkp_conlancam_8311;");
    $this->execute("create table bkp_conlancam_8311 as select c70_codlan,
                                                              c70_data as contabilidade,
                                                              c86_data as tesouraria,
                                                              c71_coddoc 
                                                         from conlancam 
                                                              inner join conlancamcorrente on c70_codlan = c86_conlancam 
                                                              inner join conlancamdoc on c71_codlan = c70_codlan 
                                                        where c86_data <> c70_data 
                                                          and c70_anousu >= 2017");

    $sSqlQuery = $this->fetchAll(" select c70_codlan,
                                            c70_data as contabilidade,
                                            c86_data as tesouraria,
                                            c71_coddoc 
                                       from conlancam 
                                            inner join conlancamcorrente on c70_codlan = c86_conlancam 
                                            inner join conlancamdoc on c71_codlan = c70_codlan 
                                      where c86_data <> c70_data 
                                        and c70_anousu >= 2017");


    foreach ( $sSqlQuery as $sQuery ) {

      $iCodigo =  $sQuery['c70_codlan'];
      $sData = $sQuery['tesouraria'] ;

      $this->execute ( "update conlancam    set c70_data = '{$sData}' where c70_codlan in ( {$iCodigo} )");
      $this->execute ( "update conlancamemp set c75_data = '{$sData}' where c75_codlan in ( {$iCodigo} )");
      $this->execute ( "update conlancamdoc set c71_data = '{$sData}' where c71_codlan in ( {$iCodigo} )");
      $this->execute ( "update conlancamemp set c75_data = '{$sData}' where c75_codlan in ( {$iCodigo} )");
      $this->execute ( "update conlancamdoc set c71_data = '{$sData}' where c71_codlan in ( {$iCodigo} )");
      $this->execute ( "update conlancamdot set c73_data = '{$sData}' where c73_codlan in ( {$iCodigo} )");
      $this->execute ( "update conlancamrec set c74_data = '{$sData}' where c74_codlan in ( {$iCodigo} )");
      $this->execute ( "update conlancamord set c80_data = '{$sData}' where c80_codlan in ( {$iCodigo} )");
      $this->execute ( "update conlancamcgm set c76_data = '{$sData}' where c76_codlan in ( {$iCodigo} )");
      $this->execute ( "update conlancamdig set c78_data = '{$sData}' where c78_codlan in ( {$iCodigo} )");
      $this->execute ( "update conlancamsup set c79_data = '{$sData}' where c79_codlan in ( {$iCodigo} )");
      $this->execute ( "update conlancaminscricaopassivo set c37_data = '{$sData}' where c37_conlancam in ( {$iCodigo} )");

      $this->execute(
<<<STRING
  
  drop table if exists bkp_conlancamval;
  drop table if exists bkp_contacorrentedetalheconlancamval;
  
  create temp table bkp_conlancamval as select * from conlancamval where c69_codlan in ({$iCodigo});
  update bkp_conlancamval set c69_data = '{$sData}';
  
  create temp table bkp_contacorrentedetalheconlancamval as select * from contacorrentedetalheconlancamval where c28_conlancamval in (select c69_sequen from bkp_conlancamval);
  delete from contacorrentedetalheconlancamval where c28_conlancamval in (select c69_sequen from bkp_conlancamval);
  
  delete from conlancamval where c69_codlan in (select c69_codlan from bkp_conlancamval);
  insert into conlancamval select * from bkp_conlancamval;
  insert into contacorrentedetalheconlancamval select * from bkp_contacorrentedetalheconlancamval;
STRING
      );
    }
  }

  public function down()
  {

  }
}
