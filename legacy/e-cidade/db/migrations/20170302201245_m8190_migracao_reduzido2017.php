<?php

use Classes\PostgresMigration;

class M8190MigracaoReduzido2017 extends PostgresMigration
{

    public function up()
    {
        $sAcerto = <<<EOT
insert into conplanoreduz
select
 c61_codcon,
 2017 as c61_anousu,
 c61_reduz ,
 c61_instit,
 c61_codigo,
 c61_contrapartida
from conplanoreduz as a
     inner join conplano on conplano.c60_codcon = a.c61_codcon
                        and conplano.c60_anousu = a.c61_anousu
where a.c61_anousu = 2016
  and exists (select 1 from conplano xxx where xxx.c60_codcon = conplano.c60_codcon and xxx.c60_anousu = 2017)
  and not exists (select 1
                    from conplanoreduz as b
                   where b.c61_codcon = a.c61_codcon
                     and b.c61_anousu = 2017
                     and b.c61_reduz  = a.c61_reduz
                     and b.c61_instit  = a.c61_instit
                 );

EOT;
        $this->execute($sAcerto);

    }

    public function down()
    {
        // não tem
    }

}
