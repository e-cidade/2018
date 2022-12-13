<?php

use Classes\PostgresMigration;

class M8403RelatorioAnexo4 extends PostgresMigration
{

  public function up()
  {
    $this->execute("update orcparamseqorcparamseqcoluna set o116_formula = '#saldo_anterior' where o116_codparamrel = 164 and o116_codseq in (58,59,60) and o116_orcparamseqcoluna = 178;");
  }

  public function down()
  {

  }

}
