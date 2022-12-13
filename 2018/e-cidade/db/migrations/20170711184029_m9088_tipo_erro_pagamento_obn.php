<?php

use Classes\PostgresMigration;

class M9088TipoErroPagamentoObn extends PostgresMigration
{
  public function up()
  {

    $rowErroBanco = $this->fetchRow('select * from empenho.errobanco where e92_sequencia in (1000000, 1000001, 1000002)');

    if (count($rowErroBanco) === 0 || $rowErroBanco === false) {

      $this->execute("insert into errobanco values (1000000, '01', 'PROCESSADO', 't' , 2), (1000001, '09', 'NÃO PROCESSADO', 'f' , 2), (1000002, '00', 'ERRO NÃO PROCESSADO', 'f' , 2)");
      $this->execute("insert into db_errobanco values ('001' , 1000000), ('001', 1000001), ('001', 1000002);");
    }
  }

  public function down()
  {

  }
}
