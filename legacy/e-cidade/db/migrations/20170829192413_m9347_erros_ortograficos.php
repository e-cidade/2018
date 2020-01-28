<?php

use Classes\PostgresMigration;

class M9347ErrosOrtograficos extends PostgresMigration
{
    public function up()
    {
      $this->execute(
<<<SQL
       UPDATE db_syscampo SET descricao = 'Código da característica',
                                 rotulo = 'Característica Principal',
                              rotulorel = 'Característica Principal'
        WHERE codcam = 3586;

        UPDATE db_syscampo SET descricao = 'Código da característica',
                                  rotulo = 'Cód. Característica',
                               rotulorel = 'Cód. Característica'
        WHERE codcam = 56;

SQL
        );
    }

    public function down()
    {
      $this->execute(
<<<SQL
       UPDATE db_syscampo SET descricao = 'Código da caracteristica',
                                 rotulo = 'Característica principal',
                              rotulorel = 'Característica principal'
        WHERE codcam = 3586;

        UPDATE db_syscampo SET descricao = 'Código da caracteristica',
                                  rotulo = 'Cód. Caracteristica',
                               rotulorel = 'Cód. Caracteristica'
        WHERE codcam = 56;

SQL
        );
    }
}
