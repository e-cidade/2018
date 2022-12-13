<?php

use Classes\PostgresMigration;

class M6842 extends PostgresMigration
{

public function up(){
    $sql = <<<'SQL'

    insert into db_syscampo
     select 21987,'z01_registromunicipio','bool','Controla se o CGS é do município','t', 'CGS do Município',1,'f','f','f',5,'text','CGS do Município'
       from db_syscampo
      where not exists(select 1 from db_syscampo where nomecam = 'z01_registromunicipio') limit 1;

insert into db_sysarqcamp
     select 1010144, 21987, 80, 0
       from db_sysarqcamp
      where not exists(select 1 from db_sysarqcamp where codarq = 1010144 and codcam = 21987) limit 1;

select fc_executa_ddl('alter table cgs_und add column z01_registromunicipio boolean default true not null;');    

SQL;
  
    $this->execute($sql);
  }

public function down(){}

}
