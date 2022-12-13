<?php

use Classes\PostgresMigration;

class PrimaryKeyTransmaterM9697 extends PostgresMigration
{
    public function up()
    {
        $this->execute("insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1120,6829,1,6830)");
        $this->execute("insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1120,6830,2,6830)");
    }

    public function down()
    {
        $this->execute("delete from db_sysprikey where codarq = 1120");
    }
}
