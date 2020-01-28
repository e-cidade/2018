<?php

use Classes\PostgresMigration;

class M7255 extends PostgresMigration
{

public function up(){
    $sql = <<<'SQL'

drop table if exists w_agua_db_sysarquivo;
create temporary table w_agua_db_sysarquivo   as select * from db_sysarquivo   limit 0;

drop table if exists w_agua_db_sysarqmod;
create temporary table w_agua_db_sysarqmod    as select * from db_sysarqmod    limit 0;

drop table if exists w_agua_db_syscampo;
create temporary table w_agua_db_syscampo     as select * from db_syscampo     limit 0;

drop table if exists w_agua_db_sysarqcamp;
create temporary table w_agua_db_sysarqcamp   as select * from db_sysarqcamp   limit 0;

drop table if exists w_agua_db_sysprikey;
create temporary table w_agua_db_sysprikey    as select * from db_sysprikey    limit 0;

drop table if exists w_agua_db_syssequencia;
create temporary table w_agua_db_syssequencia as select * from db_syssequencia limit 0;

drop table if exists w_agua_db_sysforkey;
create temporary table w_agua_db_sysforkey    as select * from db_sysforkey    limit 0;

drop table if exists w_agua_db_itensmenu;
create temporary table w_agua_db_itensmenu    as select * from db_itensmenu    limit 0;

drop table if exists w_agua_db_menu;
create temporary table w_agua_db_menu         as select * from db_menu         limit 0;

-- Água: Cadastro de Economias
insert into w_agua_db_sysarquivo
  values (3983, 'aguacontratoeconomia', 'Agua Contrato Economia', 'x38', '2016-10-10', 'Agua Contrato Economia', 0, 'f', 'f', 'f', 'f' );

insert into w_agua_db_sysarqmod
  values (43, 3983);

insert into w_agua_db_syscampo
  values ( 22113 ,'x38_sequencial' ,'int4' ,'Código da Economia' ,'' ,'Código da Economia ' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código da Economia ' ),
         ( 22114 ,'x38_aguacontrato' ,'int4' ,'Código do Contrato' ,'' ,'Código do Contrato' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código do Contrato' ),
         ( 22115 ,'x38_cgm' ,'int4' ,'Nome/Razão Social' ,'' ,'Nome/Razão Social' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Nome/Razão Social' ),
         ( 22116 ,'x38_aguacategoriaconsumo' ,'int4' ,'Categoria de Consumo' ,'' ,'Categoria de Consumo' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Categoria de Consumo' ),
         ( 22117 ,'x38_datavalidadecadastro' ,'date' ,'Data de Validade do Cadastro' ,'' ,'Data de Validade do Cadastro' ,10 ,'true' ,'false' ,'false' ,0 ,'text' ,'Data de Validade do Cadastro' ),
         ( 22118 ,'x38_nis' ,'varchar(20)' ,'Número de Identificação Social' ,'' ,'Número de Identificação Social' ,20 ,'true' ,'false' ,'false' ,0 ,'text' ,'Número de Identificação Social' );

insert into w_agua_db_sysarqcamp
  values ( 3983 ,22113 ,1 ,0 ),
         ( 3983 ,22114 ,2 ,0 ),
         ( 3983 ,22115 ,3 ,0 ),
         ( 3983 ,22116 ,4 ,0 ),
         ( 3983 ,22117 ,5 ,0 ),
         ( 3983 ,22118 ,6 ,0 );

insert into w_agua_db_syssequencia
  values (1000613, 'aguacontratoeconomia_x38_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update w_agua_db_sysarqcamp set codsequencia = 1000613 where codarq = 3983 and codcam = 22113;

insert into w_agua_db_sysprikey
  values (3983, 22113, 1, 22113);

insert into w_agua_db_sysforkey
  values (3983, 22114, 1, 3966, 0),
         (3983, 22115, 1, 42, 0),
         (3983, 22116, 1, 3969, 0);

-- Água: Tipos de Contrato
insert into w_agua_db_sysarquivo
  values (3985, 'aguatipocontrato', 'Tipo de Contrato', 'x39', '2016-10-11', 'Tipo de Contrato', 0, 'f', 'f', 'f', 'f' );

insert into w_agua_db_sysarqmod
  values (43, 3985);

insert into w_agua_db_syscampo
  values ( 22119 ,'x39_sequencial' ,'int4' ,'Código' ,'' ,'Código' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código' ),
         ( 22120 ,'x39_descricao' ,'varchar(100)' ,'Descrição' ,'' ,'Descrição' ,100 ,'false' ,'false' ,'false' ,0 ,'text' ,'Descrição' );

insert into w_agua_db_sysarqcamp
  values ( 3985 ,22119 ,1 ,0 ),
         ( 3985 ,22120 ,2 ,0 );

insert into w_agua_db_itensmenu
  values ( 10326 ,'Cadastro de Tipos de Contrato' ,'Cadastro de Tipos de Contrato' ,'' ,'1' ,'1' ,'Cadastro de Tipos de Contrato' ,'true' ),
         ( 10327 ,'Inclusão' ,'Inclusão de Tipo de Contrato' ,'agu1_aguatipocontrato.php?iOpcao=1' ,'1' ,'1' ,'Inclusão de Tipo de Contrato' ,'true' ),
         ( 10328 ,'Alteração' ,'Alteração de Tipo de Contrato' ,'agu1_aguatipocontrato.php?iOpcao=2' ,'1' ,'1' ,'Alteração de Tipo de Contrato' ,'true' ),
         ( 10329 ,'Exclusão' ,'Exclusão de Tipo de Contrato' ,'agu1_aguatipocontrato.php?iOpcao=3' ,'1' ,'1' ,'Exclusão de Tipo de Contrato' ,'true' );

insert into w_agua_db_menu
  values ( 3470 ,10326 ,42 ,4555 ),
         ( 10326 ,10327 ,1 ,4555 ),
         ( 10326 ,10328 ,2 ,4555 ),
         ( 10326 ,10329 ,3 ,4555 );

-- Água: Contrato
update db_syscampo set nulo = 'true' where codcam = 22074;

insert into w_agua_db_syscampo
  values ( 22122 ,'x54_condominio' ,'bool' ,'Contrato de Condomínio' ,'' ,'Condomínio' ,1 ,'true' ,'false' ,'false' ,5 ,'text' ,'Condomínio' ),
         ( 22123 ,'x54_aguatipocontrato' ,'int4' ,'Tipo de Contrato' ,'' ,'Tipo de Contrato' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Tipo de Contrato' );

insert into w_agua_db_sysarqcamp
  values ( 3966 ,22122 ,10 ,0 ),
         ( 3966 ,22123 ,11 ,0 );

insert into w_agua_db_sysforkey
  values (3966, 22123, 1, 3985, 0);

insert into w_agua_db_syssequencia
  values (1000614, 'aguatipocontrato_x39_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update w_agua_db_sysarqcamp set codsequencia = 1000614 where codarq = 3985 and codcam = 22119;

insert into w_agua_db_sysprikey
  values (3985, 22119, 1, 22119);

insert into db_itensmenu
  select * from w_agua_db_itensmenu    where not exists(select 1 from db_itensmenu where w_agua_db_itensmenu.id_item = db_itensmenu.id_item);

insert into db_menu
  select * from w_agua_db_menu         where not exists(select 1 from db_menu where w_agua_db_menu.id_item = db_menu.id_item and w_agua_db_menu.id_item_filho = db_menu.id_item_filho);

insert into db_sysarquivo
  select * from w_agua_db_sysarquivo   where not exists(select 1 from db_sysarquivo where w_agua_db_sysarquivo.codarq = db_sysarquivo.codarq);

insert into db_sysarqmod
  select * from w_agua_db_sysarqmod    where not exists(select 1 from db_sysarqmod where db_sysarqmod.codarq = w_agua_db_sysarqmod.codarq);

insert into db_syscampo
  select * from w_agua_db_syscampo     where not exists(select 1 from db_syscampo where db_syscampo.codcam = w_agua_db_syscampo.codcam);

insert into db_sysarqcamp
  select * from w_agua_db_sysarqcamp   where not exists(select 1 from db_sysarqcamp where db_sysarqcamp.codcam = w_agua_db_sysarqcamp.codcam);

insert into db_sysprikey
  select * from w_agua_db_sysprikey    where not exists(select 1 from db_sysprikey where db_sysprikey.codcam = w_agua_db_sysprikey.codcam);

insert into db_syssequencia
  select * from w_agua_db_syssequencia where not exists(select 1 from db_syssequencia where db_syssequencia.codsequencia = w_agua_db_syssequencia.codsequencia);

insert into db_sysforkey
  select * from w_agua_db_sysforkey    where not exists(select 1 from db_sysforkey where db_sysforkey.codcam = w_agua_db_sysforkey.codcam);

-- Água: Cadastro de Economias
select fc_executa_ddl('CREATE SEQUENCE agua.aguacontratoeconomia_x38_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1') as aguacontratoeconomia_x38_sequencial_seq;
create table if not exists agua.aguacontratoeconomia(
x38_sequencial                int4 not null default nextval('aguacontratoeconomia_x38_sequencial_seq'),
x38_aguacontrato                int4 not null,
x38_cgm                       int4 not null,
x38_aguacategoriaconsumo        int4 not null,
x38_datavalidadecadastro        date,
x38_nis                       varchar(20),
constraint aguacontratoeconomia_sequ_pk primary key (x38_sequencial),
constraint aguacontratoeconomia_cgm_fk foreign key (x38_cgm) references cgm,
constraint aguacontratoeconomia_aguacontrato_fk foreign key (x38_aguacontrato) references aguacontrato,
constraint aguacontratoeconomia_aguacategoriaconsumo_fk foreign key (x38_aguacategoriaconsumo) references aguacategoriaconsumo
);

-- Água: Tipos de Contrato
select fc_executa_ddl('CREATE SEQUENCE agua.aguatipocontrato_x39_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1') as aguatipocontrato_x39_sequencial_seq;
create table if not exists agua.aguatipocontrato(
x39_sequencial      int4 not null  default nextval('aguatipocontrato_x39_sequencial_seq'),
x39_descricao         varchar(100) ,
constraint aguatipocontrato_sequ_pk primary key (x39_sequencial)
);

-- Água: Contrato
select fc_executa_ddl('alter table agua.aguacontrato alter column x54_aguacategoriaconsumo drop not null;');
select fc_executa_ddl('alter table agua.aguacontrato add column x54_condominio bool;');
select fc_executa_ddl('alter table agua.aguacontrato add column x54_aguatipocontrato int4;');
select fc_executa_ddl('alter table agua.aguacontrato add constraint aguacontrato_aguatipocontrato_fk foreign key (x54_aguatipocontrato) references aguatipocontrato;');
-- Fim módulo Água

SQL;
  
    $this->execute($sql);
  }

public function down(){}

}