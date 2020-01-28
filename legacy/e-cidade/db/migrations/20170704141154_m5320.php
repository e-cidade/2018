<?php

use Classes\PostgresMigration;

class M5320 extends PostgresMigration
{

public function up(){
    $sql = <<<'SQL'

insert into db_sysarquivo select 3980, 'pontosalariodatalimite', 'Tabela que irá vincular um evento do ponto de salário a um período inicial e final', 'rh183', '2016-10-07', 'pontosalariodatalimite', 0, 'f', 't', 't', 't'  where not exists(select 1 from db_sysarquivo where codarq = 3980);
insert into db_sysarqmod  select 28,3980 where not exists(select 1 from db_sysarqmod where codmod = 28 and codarq = 3980);
insert into db_syscampo   select 22112,'rh183_sequencial','int4','Código sequencial da tabela','0', 'Código',20,'f','f','t',1,'text','Código' where not exists(select 1 from db_syscampo where codcam = 22112);
insert into db_syscampo   select 22095,'rh183_rubrica','varchar(5)','Rubrica que terá uma tada de inicio e fim','', 'Rubrica',5,'t','f','f',0,'text','Rubrica' where not exists(select 1 from db_syscampo where codcam = 22095);
insert into db_syscampo   select 22110,'rh183_quantidade','int4','Quantidade da Rubrica','0', 'Quantidade',20,'f','f','f',1,'text','Quantidade' where not exists(select 1 from db_syscampo where codcam = 22110);
insert into db_syscampo   select 22111,'rh183_valor','float4','Valor da rubrica','0', 'Valor',10,'f','f','f',4,'text','Valor' where not exists(select 1 from db_syscampo where codcam = 22111);
insert into db_syscampo   select 22096,'rh183_datainicio','date','Data de inicio da rubrica','null', 'Data Início',20,'f','f','f',1,'text','Data Início' where not exists(select 1 from db_syscampo where codcam = 22096);
insert into db_syscampo   select 22097,'rh183_datafim','date','Data final da rubrica','null', 'Data Final',20,'f','f','f',1,'text','Data Final' where not exists(select 1 from db_syscampo where codcam = 22097);
insert into db_syscampo   select 22098,'rh183_matricula','int4','Matrícula do Servidor','0', 'Matrícula',20,'f','f','f',1,'text','Matrícula' where not exists(select 1 from db_syscampo where codcam = 22098);
insert into db_syscampo   select 22099,'rh183_instituicao','int4','Instituição ao qual a rubrica e o periodo estão vinculados','0', 'Instituição',4,'f','f','f',1,'text','Instituição' where not exists(select 1 from db_syscampo where codcam = 22099);
insert into db_syssequencia select 1000612, 'pontosalariodatalimite_rh183_sequencial_seq', 1, 1, 9223372036854775807, 1, 1 where not exists(select 1 from db_syssequencia where codsequencia = 1000612);
update db_sysarqcamp set codsequencia = 1000612 where codarq = 3980 and codcam = 22112;
insert into db_sysarqcamp select 3980,22112,1,1000612 where not exists(select 1 from db_sysarqcamp where codcam = 22112);
insert into db_sysarqcamp select 3980,22110,5,0 where not exists(select 1 from db_sysarqcamp where codcam = 22110);
insert into db_sysarqcamp select 3980,22111,6,0 where not exists(select 1 from db_sysarqcamp where codcam = 22111);
insert into db_sysarqcamp select 3980,22095,1,0 where not exists(select 1 from db_sysarqcamp where codcam = 22095);
insert into db_sysarqcamp select 3980,22096,2,0 where not exists(select 1 from db_sysarqcamp where codcam = 22096);
insert into db_sysarqcamp select 3980,22097,3,0 where not exists(select 1 from db_sysarqcamp where codcam = 22097);
insert into db_sysarqcamp select 3980,22098,4,0 where not exists(select 1 from db_sysarqcamp where codcam = 22098);
insert into db_sysarqcamp select 3980,22099,5,0 where not exists(select 1 from db_sysarqcamp where codcam = 22099);
insert into db_sysforkey  select 3980,22095,1,1177,0 where not exists(select 1 from db_sysforkey where codcam = 22095);
insert into db_sysforkey  select 3980,22099,2,1177,0 where not exists(select 1 from db_sysforkey where codcam = 22099);
insert into db_sysprikey  select 3980,22112,1,22112 where not exists(select 1 from db_sysprikey where codarq = 3980);
insert into db_syscampo   select 22106,'rh27_periodolancamento','bool','informa se a rubrica poderá ser lançada nos pontos por um período especifico.','f', 'Período de Lançamento',1,'f','f','f',5,'text','Período de Lançamento' where not exists(select 1 from db_syscampo where codcam = 22106);
insert into db_sysarqcamp select 1177,22106,30,0 where not exists(select 1 from db_sysarqcamp where codcam = 22106);

select fc_executa_ddl('CREATE SEQUENCE pessoal.pontosalariodatalimite_rh183_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1');

CREATE TABLE if not exists pontosalariodatalimite(
rh183_sequencial    int4 default nextval('pessoal.pontosalariodatalimite_rh183_sequencial_seq'),
rh183_rubrica   varchar(5)  ,
rh183_datainicio    date NOT NULL default null,
rh183_datafim   date NOT NULL default null,
rh183_matricula   int4 NOT NULL default 0,
rh183_quantidade    int4 NOT NULL default 0,
rh183_valor   float4 NOT NULL default 0,
rh183_instituicao   int4 NOT NULL default 0);

select fc_executa_ddl('ALTER TABLE pontosalariodatalimite
ADD CONSTRAINT pontosalariodatalimite_rubrica_instituicao_fk FOREIGN KEY (rh183_rubrica,rh183_instituicao)
REFERENCES rhrubricas');

select fc_executa_ddl('alter table rhrubricas add column rh27_periodolancamento bool default \'f\'');

SQL;
  
    $this->execute($sql);
  }

public function down(){}

}