<?php

use Classes\PostgresMigration;

class M8538AlteracaoCampoValor extends PostgresMigration
{

  public function up(){
    $this->execute(
<<<SQL
      create table empenho.w_8538_empageconfchecanc as
        select
          e93_codcheque,
          e93_codmov,
          e93_valor
        from
          empenho.empageconfchecanc;
      alter table empenho.empageconfchecanc drop column e93_valor;
      alter table empenho.empageconfchecanc add column e93_valor numeric;

      update empenho.empageconfchecanc set e93_valor = e91_valor from empenho.empageconfche where e91_codcheque = e93_codcheque and e91_codmov = e93_codmov;

SQL
    );
  }

  public function down(){

    $this->execute(
<<<SQL
      alter table empenho.empageconfchecanc drop column e93_valor;
      alter table empenho.empageconfchecanc add column e93_valor real;

      update empenho.empageconfchecanc set e93_valor = x.e93_valor from empenho.w_8538_empageconfchecanc as x where x.e93_codcheque = empageconfchecanc.e93_codcheque and x.e93_codmov = empageconfchecanc.e93_codmov;

      drop table empenho.w_8538_empageconfchecanc;
SQL
    );
  }
}
