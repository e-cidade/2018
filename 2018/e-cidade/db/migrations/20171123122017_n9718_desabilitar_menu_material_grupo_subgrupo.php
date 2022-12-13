<?php

use Classes\PostgresMigration;

class N9718DesabilitarMenuMaterialGrupoSubgrupo extends PostgresMigration
{
    public function up()
    {
        $this->execute("update db_itensmenu set libcliente = 'false' where id_item = 8788;");
    }

    public function down()
    {
        $this->execute("update db_itensmenu set libcliente = 'true' where id_item = 8788;");
    }
}
