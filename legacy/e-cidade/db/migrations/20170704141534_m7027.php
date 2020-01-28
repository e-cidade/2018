<?php

use Classes\PostgresMigration;

class M7027 extends PostgresMigration
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

insert into w_agua_db_sysarquivo values (3977, 'aguaisencaocgm', 'Isenções para utilização no cálculo de água.', 'x56', '2016-10-03', 'Isenções por CGM', 0, 'f', 'f', 'f', 'f' );
insert into w_agua_db_sysarqmod values (43,3977);

insert into w_agua_db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22080 ,'x56_sequencial' ,'int4' ,'Código da isenção' ,'' ,'Código' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código' );
insert into w_agua_db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3977 ,22080 ,1 ,0 );

insert into w_agua_db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22081 ,'x56_aguaisencaotipo' ,'int4' ,'Código do Tipo de Isenção' ,'' ,'Tipo de Isenção' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Tipo de Isenção' );
insert into w_agua_db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3977 ,22081 ,2 ,0 );

insert into w_agua_db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22082 ,'x56_cgm' ,'int4' ,'CGM' ,'' ,'Nome/Razão Social' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Nome/Razão Social' );
insert into w_agua_db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3977 ,22082 ,3 ,0 );

insert into w_agua_db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22083 ,'x56_datainicial' ,'date' ,'Data Inicial de vigência da isenção' ,'' ,'Data Inicial' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Data Inicial' );
insert into w_agua_db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3977 ,22083 ,4 ,0 );

insert into w_agua_db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22084 ,'x56_datafinal' ,'date' ,'Data Final de vigência da isenção' ,'' ,'Data Final' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Data Final' );
insert into w_agua_db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3977 ,22084 ,5 ,0 );

insert into w_agua_db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22085 ,'x56_processo' ,'varchar(30)' ,'Número do Processo' ,'' ,'Número do Processo' ,30 ,'true' ,'false' ,'false' ,0 ,'text' ,'Número do Processo' );
insert into w_agua_db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3977 ,22085 ,6 ,0 );

insert into w_agua_db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22086 ,'x56_observacoes' ,'text' ,'Observações' ,'' ,'Observações' ,10 ,'true' ,'false' ,'false' ,0 ,'text' ,'Observações' );
insert into w_agua_db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3977 ,22086 ,7 ,0 );

insert into w_agua_db_syssequencia values(1000608, 'aguaisencaocgm_x56_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update w_agua_db_sysarqcamp set codsequencia = 1000608 where codarq = 3977 and codcam = 22080;

insert into w_agua_db_sysprikey (codarq,codcam,sequen,camiden) values(3977,22080,1,22080);

insert into w_agua_db_sysforkey values(3977,22081,1,1435,0);
insert into w_agua_db_sysforkey values(3977,22082,1,42,0);

insert into w_agua_db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10320 ,'Isenções por CGM' ,'Isenções por CGM' ,'' ,'1' ,'1' ,'Isenções por CGM' ,'true' );
insert into w_agua_db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 3332 ,10320 ,25 ,4555 );

insert into w_agua_db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10321 ,'Inclusão' ,'Inclusão' ,'agu4_aguaisencaocgm.php?iOpcao=1' ,'1' ,'1' ,'Inclusão de isenção por CGM' ,'true' );
insert into w_agua_db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10320 ,10321 ,1 ,4555 );

insert into w_agua_db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10322 ,'Alteração' ,'Alteração' ,'agu4_aguaisencaocgm.php?iOpcao=2' ,'1' ,'1' ,'Alteração de isenção por CGM' ,'true' );
insert into w_agua_db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10320 ,10322 ,2 ,4555 );

insert into w_agua_db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10323 ,'Exclusão' ,'Exclusão' ,'agu4_aguaisencaocgm.php?iOpcao=3' ,'1' ,'1' ,'Exclusão de isenção por CGM' ,'true' );
insert into w_agua_db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10320 ,10323 ,3 ,4555 );

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

select fc_executa_ddl('create sequence agua.aguaisencaocgm_x56_sequencial_seq increment 1 minvalue 1 maxvalue 9223372036854775807 start 1 cache 1;') as aguaisencaocgm_x56_sequencial_seq;

create table if not exists agua.aguaisencaocgm(
  x56_sequencial      int4 not null default nextval('aguaisencaocgm_x56_sequencial_seq'),
  x56_aguaisencaotipo int4 not null,
  x56_cgm             int4 not null,
  x56_datainicial     date not null,
  x56_datafinal       date,
  x56_processo        varchar(30) ,
  x56_observacoes     text,
  constraint aguaisencaocgm_sequ_pk primary key (x56_sequencial),
  constraint aguaisencaocgm_aguaisencaotipo_fk foreign key (x56_aguaisencaotipo) references aguaisencaotipo,
  constraint aguaisencaocgm_cgm_fk foreign key (x56_cgm) references cgm
);    

SQL;
  
    $this->execute($sql);
  }

public function down(){}

}