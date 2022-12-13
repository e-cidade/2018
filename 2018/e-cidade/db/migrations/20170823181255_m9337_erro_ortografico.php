<?php

use Classes\PostgresMigration;

class M9337ErroOrtografico extends PostgresMigration
{

    public function up()
    {
      $this->execute("UPDATE db_syscampo SET rotulo = 'Alíquota Predial' WHERE nomecam = 'j30_alipre'");
      $this->execute("UPDATE db_syscampo SET rotulo = 'Alíquota Territorial' WHERE nomecam = 'j30_aliter'");
    }

    public function down()
    {
      $this->execute("UPDATE db_syscampo SET rotulo = 'Aliquota Predial' WHERE nomecam = 'j30_alipre'");
      $this->execute("UPDATE db_syscampo SET rotulo = 'Aliquota Territorial' WHERE nomecam = 'j30_aliter'");
    }
}
