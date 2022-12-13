<?php

use Classes\PostgresMigration;

class M5449 extends PostgresMigration
{

public function up(){
    $sql = <<<'SQL'
    
drop table if exists upx_db_itensmenu;
drop table if exists upx_db_menu;

drop table if exists upx_db_sysarquivo;
drop table if exists upx_db_sysarqmod;
drop table if exists upx_db_sysprikey;
drop table if exists upx_db_sysforkey;
drop table if exists upx_db_syssequencia;
drop table if exists upx_db_sysindices;
drop table if exists upx_db_syscadind;
drop table if exists upx_db_syscampo;
drop table if exists upx_db_sysarqcamp;

create temporary table upx_db_sysarquivo   as select * from db_sysarquivo   limit 0;
create temporary table upx_db_sysarqmod    as select * from db_sysarqmod    limit 0;

create temporary table upx_db_syscampo as select * from db_syscampo limit 0;
create temporary table upx_db_sysprikey    as select * from db_sysprikey    limit 0;
create temporary table upx_db_sysforkey    as select * from db_sysforkey    limit 0;
create temporary table upx_db_syssequencia as select * from db_syssequencia limit 0;
create temporary table upx_db_sysindices   as select * from db_sysindices   limit 0;
create temporary table upx_db_syscadind    as select * from db_syscadind    limit 0;
create temporary table upx_db_sysarqcamp   as select * from db_sysarqcamp   limit 0;
create temporary table upx_db_itensmenu as select * from db_itensmenu limit 0;
create temporary table upx_db_menu      as select * from db_menu limit 0;

insert into upx_db_itensmenu
values ( 10332 ,'Configuração da Nota' ,'Configuração da Nota' ,'' ,'1' ,'1' ,'Configura o estrutural da nota' ,'true' ),
       ( 10333 ,'Inclusão' ,'Inclusão' ,'edu4_configuraestruturanota001.php?db_opcao=1' ,'1' ,'1' ,'Inclui a configuração da nota' ,'true' ),
       ( 10334 ,'Alteração' ,'Alteração' ,'edu4_configuraestruturanota001.php?db_opcao=2' ,'1' ,'1' ,'Altera a configuração da nota' ,'true' ),
       ( 10335 ,'Exclusão' ,'Exclusão' ,'edu4_configuraestruturanota001.php?db_opcao=3' ,'1' ,'1' ,'Exclui a configuração da nota' ,'true' );

insert into upx_db_menu
values ( 1100791 ,1100857 ,19 ,7159 ),
       ( 1100857 ,1100858 ,4 ,7159 ),
       ( 1100857 ,1100859 ,5 ,7159 ),
       ( 1100857 ,1100860 ,6 ,7159 ),
       ( 3470 ,1100865 ,43 ,7159 ),
       ( 1100865 ,1100866 ,5 ,7159 ),
       ( 1100865 ,1100867 ,6 ,7159 ),
       ( 9081 ,10332 ,4 ,7159 ),
       ( 10332 ,10333 ,1 ,7159 ),
       ( 10332 ,10334 ,2 ,7159 ),
       ( 10332 ,10335 ,3 ,7159 ),
       ( 1100865 ,10331 ,7 ,7159 );


insert into db_itensmenu
  select * from upx_db_itensmenu
   where not exists ( select 1 from db_itensmenu where db_itensmenu.id_item = upx_db_itensmenu.id_item);

insert into db_menu
  select * from upx_db_menu
   where not exists ( select 1 from db_menu
                       where db_menu.id_item       = upx_db_menu.id_item
                         and db_menu.id_item_filho = upx_db_menu.id_item_filho
                         and db_menu.modulo        = upx_db_menu.modulo
                    );

insert into upx_db_sysarquivo values (3990, 'avaliacaoestruturanotapadrao', 'Configuração da Nota na secretaria de educação.', 'ed139', '2016-11-10', 'Configuração da Nota', 0, 'f', 'f', 'f', 'f' );
insert into upx_db_sysarqmod values (1008004,3990);
insert into upx_db_syscampo
values (22148,'ed139_sequencial','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
       (22149,'ed139_db_estrutura','int4','Estrutural da nota','0', 'Estrutural',10,'f','f','f',1,'text','Estrutural'),
       (22150,'ed139_ativo','bool','Se a configuração esta ativa.','f', 'Ativo',1,'f','f','f',5,'text','Ativo'),
       (22151,'ed139_arredondamedia','bool','Se deve arredondar a média.','f', 'Arredonda média',1,'f','f','f',5,'text','Arredonda média'),
       (22152,'ed139_regraarredondamento','int4','Se houver, usa a regra de arredondamento aplicada. ','0', 'Regra de arredondamento',10,'t','f','f',1,'text','Regra de arredondamento'),
       (22153,'ed139_observacao','varchar(300)','Observação','', 'Observação',300,'t','t','f',0,'text','Observação'),
       (22154,'ed139_ano','int4','Ano que a configuração é aplicada','0', 'Ano',10,'f','f','f',1,'text','Ano');

insert into upx_db_sysarqcamp
values (3990,22148,1,0),
       (3990,22149,2,0),
       (3990,22150,3,0),
       (3990,22151,4,0),
       (3990,22152,5,0),
       (3990,22153,6,0),
       (3990,22154,7,0);

insert into upx_db_sysprikey values(3990,22148,1,22148);

insert into upx_db_sysforkey
values (3990,22149,1,898,0),
       (3990,22152,1,3368,0);

insert into upx_db_syssequencia values(1000619, 'avaliacaoestruturanotapadrao_ed139_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

insert into upx_db_sysindices
values (4390,'avaliacaoestruturanotapadrao_db_estrutura_in',3990,'0'),
       (4391,'avaliacaoestruturanotapadrao_regraarredondamento_in',3990,'0'),
       (4392,'avaliacaoestruturanotapadrao_ano_in',3990,'0');

insert into upx_db_syscadind
values (4390,22149,1),
       (4391,22152,1),
       (4392,22154,1);


insert into db_sysarquivo
select * from upx_db_sysarquivo
 where not exists ( select 1 from db_sysarquivo where db_sysarquivo.codarq = upx_db_sysarquivo.codarq);

insert into db_sysarqmod
select * from upx_db_sysarqmod
 where not exists ( select 1 from db_sysarqmod where db_sysarqmod.codarq = upx_db_sysarqmod.codarq);

insert into db_syscampo
select * from upx_db_syscampo
 where not exists ( select 1 from db_syscampo where db_syscampo.codcam = upx_db_syscampo.codcam);

insert into db_sysarqcamp
  select * from upx_db_sysarqcamp
   where not exists ( select 1 from db_sysarqcamp where db_sysarqcamp.codcam = upx_db_sysarqcamp.codcam);

insert into db_sysprikey
select * from upx_db_sysprikey
 where not exists( select 1 from db_sysprikey where db_sysprikey.codarq = upx_db_sysprikey.codarq and db_sysprikey.codcam = upx_db_sysprikey.codcam);

insert into db_sysforkey
select * from upx_db_sysforkey
 where not exists( select 1 from db_sysforkey where db_sysforkey.codarq = upx_db_sysforkey.codarq and db_sysforkey.codcam = upx_db_sysforkey.codcam);

insert into db_syssequencia
select * from upx_db_syssequencia
 where not exists( select 1 from db_syssequencia where db_syssequencia.codsequencia = upx_db_syssequencia.codsequencia);

insert into db_sysindices
select * from upx_db_sysindices
 where not exists( select 1 from db_sysindices where db_sysindices.codind = upx_db_sysindices.codind);

insert into db_syscadind
select * from upx_db_syscadind
 where not exists( select 1 from db_syscadind where db_syscadind.codind = upx_db_syscadind.codind);

update db_sysarqcamp set codsequencia = 1000619 where codarq = 3990 and codcam = 22148;
update db_syscampo   set nulo = 't'  where codcam = 1009219;
delete from db_syscampodep where codcam = 1009219;
delete from db_syscampodef where codcam = 1009219;

select fc_executa_ddl('CREATE SEQUENCE escola.avaliacaoestruturanotapadrao_ed139_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;');

CREATE TABLE  IF NOT EXISTS escola.avaliacaoestruturanotapadrao(
  ed139_sequencial          int4 NOT NULL default 0,
  ed139_db_estrutura        int4 NOT NULL default 0,
  ed139_ativo               bool NOT NULL default 'f',
  ed139_arredondamedia      bool NOT NULL default 'f',
  ed139_regraarredondamento int4 default null,
  ed139_observacao          varchar(300)  ,
  ed139_ano                 int4 default 0,
  CONSTRAINT avaliacaoestruturanotapadrao_sequ_pk PRIMARY KEY (ed139_sequencial),
  CONSTRAINT avaliacaoestruturanotapadrao_estrutura_fk FOREIGN KEY (ed139_db_estrutura) REFERENCES configuracoes.db_estrutura,
  CONSTRAINT avaliacaoestruturanotapadrao_regraarredondamento_fk FOREIGN KEY (ed139_regraarredondamento) REFERENCES escola.regraarredondamento
);

select fc_executa_ddl('
  CREATE  INDEX avaliacaoestruturanotapadrao_db_estrutura_in ON avaliacaoestruturanotapadrao(ed139_db_estrutura);
  CREATE  INDEX avaliacaoestruturanotapadrao_ano_in ON avaliacaoestruturanotapadrao(ed139_ano);
  CREATE  INDEX avaliacaoestruturanotapadrao_regraarredondamento_in ON avaliacaoestruturanotapadrao(ed139_regraarredondamento);
');

select fc_executa_ddl('alter table formaavaliacao alter COLUMN ed37_i_escola drop not null;');

SQL;
  
    $this->execute($sql);
  }

public function down(){}

}