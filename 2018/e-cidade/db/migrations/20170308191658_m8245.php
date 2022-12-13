<?php

use Classes\PostgresMigration;

class M8245 extends PostgresMigration
{
    public function up()
    {
        $this->execute("CREATE TABLE w_bkp_db_usuariosrhlota_m8245 AS SELECT * FROM db_usuariosrhlota");
        $this->execute("DELETE FROM db_usuariosrhlota");
    }
    
    public function down()
    {
        $this->execute("INSERT INTO db_usuariosrhlota SELECT * FROM w_bkp_db_usuariosrhlota_m8245");
    }
}
