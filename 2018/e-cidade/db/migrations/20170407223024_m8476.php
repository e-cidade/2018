<?php

use Classes\PostgresMigration;

class M8476 extends PostgresMigration
{
    public function up()
    {
      $iAno = date('Y');
      $contas = $this->fetchAll("select c21_codcon as conta, grupo[1] as grupo from (select c.c21_codcon, array_agg(c21_congrupo) as grupo, count(c.*) from conplanoorcamentogrupo c where c.c21_anousu = {$iAno} group by c.c21_codcon having(count(c.*)) = 1 ) as x where exists(select * from conplanoorcamentogrupo co where co.c21_codcon = x.c21_codcon and co.c21_congrupo <> any(x.grupo) and co.c21_anousu > {$iAno})");

      foreach ($contas as $conta) {
        $this->execute("delete from conplanoorcamentogrupo where c21_codcon = {$conta['conta']} and c21_anousu > {$iAno}");
      }
    }

    public function down()
    {

    }
}
