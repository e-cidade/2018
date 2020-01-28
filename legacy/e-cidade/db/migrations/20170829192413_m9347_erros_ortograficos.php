<?php

use Classes\PostgresMigration;

class M9347ErrosOrtograficos extends PostgresMigration
{
    public function up()
    {
      $this->execute(
<<<SQL
       UPDATE db_syscampo SET descricao = 'C�digo da caracter�stica',
                                 rotulo = 'Caracter�stica Principal',
                              rotulorel = 'Caracter�stica Principal'
        WHERE codcam = 3586;

        UPDATE db_syscampo SET descricao = 'C�digo da caracter�stica',
                                  rotulo = 'C�d. Caracter�stica',
                               rotulorel = 'C�d. Caracter�stica'
        WHERE codcam = 56;

SQL
        );
    }

    public function down()
    {
      $this->execute(
<<<SQL
       UPDATE db_syscampo SET descricao = 'C�digo da caracteristica',
                                 rotulo = 'Caracter�stica principal',
                              rotulorel = 'Caracter�stica principal'
        WHERE codcam = 3586;

        UPDATE db_syscampo SET descricao = 'C�digo da caracteristica',
                                  rotulo = 'C�d. Caracteristica',
                               rotulorel = 'C�d. Caracteristica'
        WHERE codcam = 56;

SQL
        );
    }
}
