<?php

use Classes\PostgresMigration;

class M8372 extends PostgresMigration
{
    public function up()
    {
      $this->execute("update orcparamrel set o42_notapadrao = (select o42_notapadrao from orcparamrel where o42_codparrel = 145) where o42_codparrel = 163");
      $this->execute("update orcparamrel set o42_notapadrao = 'Fonte: Sistema E-Cidade, Unidade Respons�vel: [nome_departamento], Data de emiss�o [data_emissao] e hora de emiss�o [hora_emissao]' where o42_codparrel in (96, 162)");
    }

    public function down()
    {
    }
}
