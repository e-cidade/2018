<?php

use Classes\PostgresMigration;

class M8373AtualizaAnexoi extends PostgresMigration
{
  public function up(){
    $this->execute("update orcamento.orcparamseq set o69_labelrel = 'Outras Receitas de Capital' where o69_codparamrel = 163 and o69_codseq = 63");
  }

  public function down(){
    $this->execute("update orcamento.orcparamseq set o69_labelrel = 'Receitas de Capital Diversas' where o69_codparamrel = 163 and o69_codseq = 63");
  }
}