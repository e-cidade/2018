<?php

use Classes\PostgresMigration;

class M8547AcertoCgmTipoEmpresa extends PostgresMigration
{
    public function up()
    {
        $sSqlCreateTable_w_8547_cgmtipoempresa = "
            create table w_8547_cgmtipoempresa as select z03_sequencial,
                                                         z03_numcgm,
                                                         z03_tipoempresa
                                                    from cgmtipoempresa
                                                   where (select count(1)
                                                            from cgmtipoempresa as cte
                                                           where cte.z03_numcgm = cgmtipoempresa.z03_numcgm) > 1
                                                order by 2 desc";

        $sSqlCreateTable_w_8547_cgmtipoempresa_drop = "
            create table w_8547_cgmtipoempresa_drop as select z03_numcgm, min(z03_sequencial)
                                                         from w_8547_cgmtipoempresa
                                                        group by z03_numcgm
                                                        order by z03_numcgm";

        $sSqlDelete_cgmtipoempresa = "
            delete from cgmtipoempresa
                  where z03_sequencial in (select z03_sequencial
                                             from w_8547_cgmtipoempresa)";

        $this->execute($sSqlCreateTable_w_8547_cgmtipoempresa);
        $this->execute($sSqlCreateTable_w_8547_cgmtipoempresa_drop);
        $this->execute($sSqlDelete_cgmtipoempresa);
    }

    public function down()
    {
        $sSqlInsert_cgmtipoempresa = " insert into cgmtipoempresa select * from w_8547_cgmtipoempresa";
        $sSqlDrop_w_8547_cgmtipoempresa = " drop table w_8547_cgmtipoempresa";
        $sSqlDrop_w_8547_cgmtipoempresa_drop = " drop table w_8547_cgmtipoempresa_drop";

        $this->execute($sSqlInsert_cgmtipoempresa);
        $this->execute($sSqlDrop_w_8547_cgmtipoempresa);
        $this->execute($sSqlDrop_w_8547_cgmtipoempresa_drop);
    }
}
