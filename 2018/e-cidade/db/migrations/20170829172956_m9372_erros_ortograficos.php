<?php

use Classes\PostgresMigration;

class M9372ErrosOrtograficos extends PostgresMigration
{
    public function up()
    {
      $this->execute(
<<<SQL
       UPDATE db_syscampo SET descricao = 'C�digo da zona de entrega',
                                 rotulo = 'C�digo da zona de entrega',
                              rotulorel = 'C�digo da zona de entrega'
        WHERE codcam = 8044;
       UPDATE db_syscampo SET descricao = 'Descri��o da zona de entrega',
                                 rotulo = 'Descri��o da zona de entrega',
                              rotulorel = 'Descri��o da zona de entrega'
        WHERE codcam = 8045;

       UPDATE db_syscampo SET descricao = 'Endere�o da zona de entrega',
                                 rotulo = 'Endere�o da zona de entrega',
                              rotulorel = 'Endere�o da zona de entrega'
       WHERE codcam = 8054 ;
SQL
        );
    }

    public function down()
    {
      $this->execute(
<<<SQL
       UPDATE db_syscampo SET descricao = 'Codigo da zona de entrega',
                                 rotulo = 'Codigo da zona de entrega',
                              rotulorel = 'Codigo da zona de entrega'
        WHERE codcam = 8044;
       UPDATE db_syscampo SET descricao = 'Descricao da zona de entrega',
                                 rotulo = 'Descricao da zona de entrega',
                              rotulorel = 'Descricao da zona de entrega'
        WHERE codcam = 8045;

       UPDATE db_syscampo SET descricao = 'Endereco da zona de entrega',
                                 rotulo = 'Endereco da zona de entrega',
                              rotulorel = 'Endereco da zona de entrega'
       WHERE codcam = 8054 ;
SQL
        );
    }
}
