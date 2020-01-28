<?php

use Classes\PostgresMigration;

class M6756 extends PostgresMigration
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

insert into w_agua_db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10306 ,'Cadastro de Contratos' ,'Cadastro de Contratos' ,'' ,'1' ,'1' ,'Cadastro de Contratos' ,'true' );
insert into w_agua_db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 3470 ,10306 ,41 ,4555 );

insert into w_agua_db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10307 ,'Inclusão' ,'Inclusão de contrato' ,'agu1_aguacontrato001.php?iOpcao=1' ,'1' ,'1' ,'Inclusão de contrato' ,'true' );
insert into w_agua_db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10306 ,10307 ,1 ,4555 );

insert into w_agua_db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10308 ,'Alteração' ,'Alteração de contrato' ,'agu1_aguacontrato001.php?iOpcao=2' ,'1' ,'1' ,'Alteração de contrato' ,'true' );
insert into w_agua_db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10306 ,10308 ,2 ,4555 );

insert into w_agua_db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10309 ,'Exclusão' ,'Exclusão de contrato' ,'agu1_aguacontrato001.php?iOpcao=3' ,'1' ,'1' ,'Exclusão de contrato' ,'true' );
insert into w_agua_db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10306 ,10309 ,3 ,4555 );

insert into w_agua_db_sysarquivo values (3966, 'aguacontrato', 'Contrato para fornecimento de água e esgoto.', 'x54', '2016-09-15', 'Contrato', 0, 'f', 'f', 'f', 'f' );
insert into w_agua_db_sysarqmod values (43,3966);

insert into w_agua_db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22031 ,'x54_sequencial' ,'int4' ,'Código do Contrato' ,'' ,'Código' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código' );
insert into w_agua_db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3966 ,22031 ,1 ,0 );

insert into w_agua_db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22032 ,'x54_aguabase' ,'int4' ,'Matrícula' ,'' ,'Matrícula' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Matrícula' );
insert into w_agua_db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3966 ,22032 ,2 ,0 );

insert into w_agua_db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22033 ,'x54_diavencimento' ,'int4' ,'Dia de vencimento das faturas.' ,'' ,'Dia de Vencimento' ,2 ,'true' ,'false' ,'false' ,1 ,'text' ,'Dia de Vencimento' );
insert into w_agua_db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3966 ,22033 ,3 ,0 );

insert into w_agua_db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22034 ,'x54_datavalidadecadastro' ,'date' ,'Data de validade do cadastro social, caso o contratante seja beneficiado por desconto da categoria Residencial Social.' ,'' ,'Validade do Cadastro Social' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Validade do Cadastro Social' );
insert into w_agua_db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3966 ,22034 ,4 ,0 );

insert into w_agua_db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22035 ,'x54_datainicial' ,'date' ,'Data de início do contrato.' ,'' ,'Data Inicial' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Data Inicial' );
insert into w_agua_db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3966 ,22035 ,5 ,0 );

insert into w_agua_db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22036 ,'x54_datafinal' ,'date' ,'Data de encerramento do contrato.' ,'' ,'Data FInal' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Data FInal' );
insert into w_agua_db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3966 ,22036 ,6 ,0 );

insert into w_agua_db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22040 ,'x54_nis' ,'varchar(20)' ,'Número NIS' ,'' ,'NIS' ,20 ,'true' ,'false' ,'false' ,1 ,'text' ,'NIS' );
insert into w_agua_db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3966 ,22040 ,7 ,0 );

insert into w_agua_db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22041 ,'x54_cgm' ,'int4' ,'CGM' ,'' ,'CGM' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'CGM' );
insert into w_agua_db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3966 ,22041 ,8 ,0 );

insert into w_agua_db_sysprikey (codarq,codcam,sequen,camiden) values(3966,22031,1,22031);

insert into w_agua_db_syssequencia values(1000600, 'aguacontrato_x54_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update w_agua_db_sysarqcamp set codsequencia = 1000600 where codarq = 3966 and codcam = 22031;

insert into w_agua_db_sysforkey values(3966,22032,1,1426,0);
insert into w_agua_db_sysforkey values(3966,22041,1,42,0);

-- Ligação
insert into w_agua_db_sysarquivo values (3968, 'aguacontratoligacao', 'Vincula hidrômetro com contrato.', 'x55', '2016-09-15', 'Ligação de Água', 0, 'f', 'f', 'f', 'f' );
insert into w_agua_db_sysarqmod values (43,3968);

insert into w_agua_db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22037 ,'x55_sequencial' ,'int4' ,'Código sequencial.' ,'' ,'Código' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código' );
insert into w_agua_db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3968 ,22037 ,1 ,0 );

insert into w_agua_db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22038 ,'x55_aguahidromatric' ,'int4' ,'Hidrômetro' ,'' ,'Hidrômetro' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Hidrômetro' );
insert into w_agua_db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3968 ,22038 ,2 ,0 );

insert into w_agua_db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22039 ,'x55_aguacontrato' ,'int4' ,'Contrato.' ,'' ,'Contrato' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Contrato' );
insert into w_agua_db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3968 ,22039 ,3 ,0 );

insert into w_agua_db_sysprikey (codarq,codcam,sequen,camiden) values(3968,22037,1,22037);

-- FK Hidrômetro
insert into w_agua_db_sysforkey values(3968,22038,1,1421,0);

-- FK Contato
insert into w_agua_db_sysforkey values(3968,22039,1,3966,0);

insert into w_agua_db_syssequencia values(1000601, 'aguacontratoligacao_x55_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update w_agua_db_sysarqcamp set codsequencia = 1000601 where codarq = 3968 and codcam = 22037;

-- Removida obrigatoriedade da Matrícula no Cadastro de Hidrômetro
update w_agua_db_syscampo set nulo = 'true' where codcam = 8432;

-- # Agua Categoria Consumo
insert into w_agua_db_sysarquivo
  values (3969, 'aguacategoriaconsumo', 'Categoria de Consumo', 'x13', '2016-09-22', 'Categoria de Consumo', 0, 'f', 'f', 'f', 'f' );

insert into w_agua_db_sysarqmod
  values (43, 3969);

insert into w_agua_db_syscampo
  values ( 22042 ,'x13_sequencial' ,'int4' ,'Código' ,'' ,'Código' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código' ),
         ( 22043 ,'x13_exercicio' ,'int4' ,'Exercício' ,'' ,'Exercício' ,4 ,'false' ,'false' ,'false' ,1 ,'text' ,'Exercicio' ),
         ( 22044 ,'x13_descricao' ,'varchar(100)' ,'Descrição da Categoria' ,'' ,'Descrição' ,100 ,'false' ,'false' ,'false' ,0 ,'text' ,'Descrição' );

insert into w_agua_db_sysarqcamp
  values ( 3969 ,22042 ,1 ,0 ),
         ( 3969 ,22043 ,2 ,0 ),
         ( 3969 ,22044 ,3 ,0 );

insert into w_agua_db_syssequencia values (1000602, 'aguacategoriaconsumo_x13_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update w_agua_db_sysarqcamp set codsequencia = 1000602 where codarq = 3969 and codcam = 22042;
insert into w_agua_db_sysprikey values (3969,22042,1,22042);

-- # Agua Estrutura Tarifaria
insert into w_agua_db_sysarquivo
  values (3972, 'aguaestruturatarifaria', 'Estrutura Tarifária', 'x37', '2016-09-22', 'Estrutura Tarifária', 0, 'f', 'f', 'f', 'f' );

insert into w_agua_db_sysarqmod
  values (43, 3972);

insert into w_agua_db_syscampo
  values ( 22063 ,'x37_sequencial' ,'int4' ,'Código' ,'' ,'Código' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código' ),
         ( 22064 ,'x37_aguaconsumotipo' ,'int4' ,'Código Tipo de Consumo' ,'' ,'Código Tipo de Consumo' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código Tipo de Consumo' ),
         ( 22065 ,'x37_tipoestrutura' ,'int4' ,'Tipo de Estrutura' ,'' ,'Tipo de Estrutura' ,2 ,'false' ,'false' ,'false' ,1 ,'text' ,'Tipo de Estrutura' ),
         ( 22066 ,'x37_valorinicial' ,'int4' ,'Valor Inicial do Intervalo' ,'0' ,'Valor Inicial' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Valor Inicial' ),
         ( 22067 ,'x37_valorfinal' ,'int4' ,'Valor Final do Intervalo' ,'0' ,'Valor Final' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Valor Final' ),
         ( 22068 ,'x37_valor' ,'float4' ,'Valor' ,'0' ,'Valor' ,10 ,'true' ,'false' ,'false' ,4 ,'text' ,'Valor' ),
         ( 22069 ,'x37_percentual' ,'float4' ,'Percentual' ,'0' ,'Percentual' ,10 ,'true' ,'false' ,'false' ,4 ,'text' ,'Percentual' ),
         ( 22075 ,'x37_aguacategoriaconsumo' ,'int4' ,'Código da Categoria de Consumo' ,'' ,'Código da Categoria de Consumo' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código da Categoria de Consumo' );

insert into w_agua_db_sysarqcamp
  values ( 3972 ,22063 ,1 ,0 ),
         ( 3972 ,22064 ,2 ,0 ),
         ( 3972 ,22065 ,3 ,0 ),
         ( 3972 ,22066 ,4 ,0 ),
         ( 3972 ,22067 ,5 ,0 ),
         ( 3972 ,22068 ,6 ,0 ),
         ( 3972 ,22069 ,7 ,0 ),
         ( 3972 ,22075 ,8 ,0 );

insert into w_agua_db_sysforkey
  values (3972, 22075, 1, 3969, 0);

-- # Agua Contrato < Agua Categoria Consumo
insert into w_agua_db_syscampo
  values ( 22074 ,'x54_aguacategoriaconsumo' ,'int4' ,'Código da Categoria de Consumo' ,'' ,'Código da Categoria de Consumo' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código da Categoria de Consumo' );
insert into w_agua_db_sysarqcamp
  values ( 3966 ,22074 ,9 ,0 );
insert into w_agua_db_sysforkey
  values (3966, 22074, 1, 3969, 0);

insert into w_agua_db_syssequencia values (1000607, 'aguaestruturatarifaria_x37_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update w_agua_db_sysarqcamp set codsequencia = 1000607 where codarq = 3972 and codcam = 22063;

insert into w_agua_db_sysprikey values (3972, 22063, 1, 22063);
insert into w_agua_db_sysforkey values (3972, 22064, 1, 1445, 0);

-- # Menu Categorias de Consumo
insert into w_agua_db_itensmenu
  values ( 10316 ,'Categorias de Consumo' ,'Inclusão, Alteração e Exclusão de Categorias de Consumo' ,'' ,'1' ,'1' ,'Inclusão, Alteração e Exclusão de Categorias de Consumo Usadas Para o Calculo de Água e Esgoto.' ,'true' ),
         ( 10317 ,'Inclusão' ,'Inclusão de Categorias de Consumo' ,'agu4_aguacategoriaconsumo001.php?iOpcao=1' ,'1' ,'1' ,'Inclusão de Categorias de Consumo' ,'true' ),
         ( 10318 ,'Alteração' ,'Alteração de Categorias de Consumo' ,'agu4_aguacategoriaconsumo001.php?iOpcao=2' ,'1' ,'1' ,'Alteração de Categorias de Consumo' ,'true' ),
         ( 10319 ,'Exclusão' ,'Exclusão de Categorias de Consumo' ,'agu4_aguacategoriaconsumo001.php?iOpcao=3' ,'1' ,'1' ,'Exclusão de Categorias de Consumo' ,'true' );

insert into w_agua_db_menu
  values ( 4615 ,10316 ,6 ,4555 ),
         ( 10316 ,10317 ,1 ,4555 ),
         ( 10316 ,10318 ,2 ,4555 ),
         ( 10316 ,10319 ,3 ,4555 );

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

select fc_executa_ddl('
create sequence agua.aguacontrato_x54_sequencial_seq
increment 1
minvalue 1
maxvalue 9223372036854775807
start 1
cache 1;') as aguacontrato_x54_sequencial_seq;

select fc_executa_ddl('
create sequence agua.aguacontratoligacao_x55_sequencial_seq
increment 1
minvalue 1
maxvalue 9223372036854775807
start 1
cache 1;') as aguacontratoligacao_x55_sequencial_seq;

create table if not exists agua.aguacontrato(
x54_sequencial           int4 not null default nextval('aguacontrato_x54_sequencial_seq'),
x54_aguabase             int4,
x54_diavencimento        int4,
x54_datavalidadecadastro date,
x54_datainicial          date not null,
x54_datafinal            date,
x54_nis                  varchar(20),
x54_cgm                  int4 not null,
x54_aguacategoriaconsumo int4 not null,
constraint aguacontrato_sequ_pk primary key (x54_sequencial),
constraint aguacontrato_aguabase_fk foreign key (x54_aguabase) references agua.aguabase(x01_matric),
constraint aguacontrato_cgm_fk foreign key (x54_cgm) references protocolo.cgm(z01_numcgm));

create table if not exists agua.aguacontratoligacao(
x55_sequencial      int4 not null default nextval('aguacontratoligacao_x55_sequencial_seq'),
x55_aguahidromatric int4 not null,
x55_aguacontrato    int4,
constraint aguacontratoligacao_sequ_pk primary key (x55_sequencial),
constraint aguacontratoligacao_aguahidromatric_fk foreign key (x55_aguahidromatric) references agua.aguahidromatric(x04_codhidrometro),
constraint aguacontratoligacao_aguacontrato_fk foreign key (x55_aguacontrato) references agua.aguacontrato(x54_sequencial));

alter table agua.aguahidromatric alter column x04_matric drop not null;

-- # Agua Categoria Consumo
select fc_executa_ddl('
CREATE SEQUENCE agua.aguacategoriaconsumo_x13_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;') as aguacategoriaconsumo_x13_sequencial_seq;

CREATE TABLE IF NOT EXISTS agua.aguacategoriaconsumo (
x13_sequencial  int4 NOT NULL  default nextval('aguacategoriaconsumo_x13_sequencial_seq'),
x13_exercicio       int4 NOT NULL,
x13_descricao       varchar(100) NOT NUll,
CONSTRAINT aguacategoriaconsumo_sequ_pk PRIMARY KEY (x13_sequencial)
);

-- # Agua Estrutura Tarifaria
select fc_executa_ddl('
CREATE SEQUENCE agua.aguaestruturatarifaria_x37_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;
') as aguaestruturatarifaria_x37_sequencial_seq;

CREATE TABLE IF NOT EXISTS  agua.aguaestruturatarifaria(
x37_sequencial          int4 NOT NULL  default nextval('aguaestruturatarifaria_x37_sequencial_seq'),
x37_aguaconsumotipo     int4 NOT NULL ,
x37_tipoestrutura         int4 NOT NULL ,
x37_valorinicial        int4  default 0,
x37_valorfinal          int4  default 0,
x37_valor                 float4  default 0,
x37_percentual          float4 default 0,
x37_aguacategoriaconsumo        int4 NOT NULL,
CONSTRAINT aguaestruturatarifaria_sequ_pk PRIMARY KEY (x37_sequencial),
CONSTRAINT aguaestruturatarifaria_aguaconsumotipo_fk FOREIGN KEY (x37_aguaconsumotipo) REFERENCES agua.aguaconsumotipo,
CONSTRAINT aguaestruturatarifaria_aguacategoriaconsumo_fk FOREIGN KEY (x37_aguacategoriaconsumo) REFERENCES agua.aguacategoriaconsumo
);

-- # Agua Contrato < Agua Categoria Consumo
select fc_executa_ddl('
ALTER TABLE agua.aguacontrato
ADD CONSTRAINT aguacontrato_aguacategoriaconsumo_fk FOREIGN KEY (x54_aguacategoriaconsumo)
REFERENCES agua.aguacategoriaconsumo;
') as alter_table_aguacontrato_aguacategoriaconsumo;

SQL;
  
    $this->execute($sql);
  }

public function down(){}

}
