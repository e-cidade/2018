<?php

use Classes\PostgresMigration;

class M8524AcertoPortarias extends PostgresMigration
{
    public function up()
    {
        $this->execute( <<<SQL
            create table w_portaria_sem_assentamento_funcional as
            select
                   h33_assenta,
                   h31_portariatipo                      as tipo_portaria,
                   h31_numero||'/'||h31_anousu           as numero_portaria,
                   to_char(h31_dtportaria, 'DD/MM/YYYY') as data_portaria,
                   to_char(h31_dtinicio, 'DD/MM/YYYY')   as data_inicio_portaria,
                   h16_regist||' - '||z01_nome           as servidor,
                   h31_usuario||' - '||nome              as usuario
            from portariaassenta
            inner join portaria    on h31_sequencial = h33_portaria
            inner join assenta     on h16_codigo     = h33_assenta
            inner join db_usuarios on id_usuario     = h31_usuario
            inner join rhpessoal   on rh01_regist    = h16_regist
            inner join cgm         on z01_numcgm     = rh01_numcgm
            where h33_assenta not in (select distinct rh193_assentamento_funcional
                                        from assentamentofuncional)
                                       order by h31_anousu, h31_numero desc, h31_dtportaria;
SQL
        );
        $this->execute( <<<SQL
            insert into recursoshumanos.assentamentofuncional
            select h33_assenta, null from w_portaria_sem_assentamento_funcional;
SQL
        );
    }

    public function down()
    {
        $this->execute( <<<SQL
            delete from recursoshumanos.assentamentofuncional
            using w_portaria_sem_assentamento_funcional
            where assentamentofuncional.rh193_assentamento_funcional = w_portaria_sem_assentamento_funcional.h33_assenta;

            drop table if exists w_portaria_sem_assentamento_funcional;
SQL
        );
    }
}
