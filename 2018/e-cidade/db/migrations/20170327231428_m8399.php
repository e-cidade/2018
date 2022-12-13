<?php

use Classes\PostgresMigration;

class M8399 extends PostgresMigration
{
    public function up() {

      $this->execute("update orcparamseq set o69_origem = 2 where o69_codparamrel = 164 and o69_codseq = 53");
      $this->execute("update orcparamseqorcparamseqcoluna set o116_formula = '#dot_ini' where o116_codparamrel = 164 and o116_codseq = 53");
    }

    public function down() {}
}
