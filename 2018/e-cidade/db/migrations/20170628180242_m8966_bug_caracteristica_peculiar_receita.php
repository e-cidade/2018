<?php

use Classes\PostgresMigration;

class M8966BugCaracteristicaPeculiarReceita extends PostgresMigration
{
  public function down(){}

  /**
   * Corrige os lançamentos contábeis que estão com caracteristica diferente do cadastro da receita
   */
  public function up()
  {
    $this->execute(
      <<<STRING
      update conlancamconcarpeculiar 
         set c08_concarpeculiar = (orcreceita.o70_concarpeculiar)
        from orcreceita
             inner join conlancamrec  on orcreceita.o70_codrec = conlancamrec.c74_codrec
                                     and orcreceita.o70_anousu = conlancamrec.c74_anousu
       where conlancamconcarpeculiar.c08_codlan = conlancamrec.c74_codlan
         and o70_anousu = 2017
         and o70_concarpeculiar <> c08_concarpeculiar
STRING

    );
  }
}
