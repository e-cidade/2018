<?php

use Classes\PostgresMigration;

class M6571 extends PostgresMigration
{

public function up(){
    $sql = <<<'SQL'
    
    --Menu Evento financeiro Automático
insert into db_itensmenu select 10255 ,'Evento Financeiro Automático' ,'Configuração de Eventos Financeiros Automáticos' ,'pes4_eventofinanceiroautomatico001.php' ,'1' ,'1' ,'Configuração dos Eventos financeiros que devem ser lançados automaticamente em um determinado mês.' ,'true' from db_itensmenu where not exists (select 1 from db_itensmenu where id_item = 10255) limit 1; 
insert into db_menu select 3516 ,10255 ,15 ,952 from db_menu where not exists (select 1 from db_menu where id_item_filho = 10255) limit 1;

-- Tabela eventofinanceiroautomatico
insert into db_sysarquivo select 3955, 'eventofinanceiroautomatico', 'Tabela que armazena os dados de configuração para eventos financeiros automaticos', 'rh181', '2016-08-04', 'Evento Financeiro Automatico', 0, 'f', 'f', 'f', 'f' from db_sysarquivo where not exists (select 1 from db_sysarquivo where codarq = 3955) limit 1;
insert into db_sysarqmod select 28,3955 from db_sysarqmod where not exists (select 1 from db_sysarqmod where codarq = 3955) limit 1;
insert into db_syscampo select 21972,'rh181_sequencial','int4','Sequencial da configuração dos eventos financeiros automaticos','0', 'Sequêncial',10,'f','f','t',1,'text','Sequêncial' from db_syscampo where not exists (select 1 from db_syscampo where codcam = 21972) limit 1;
insert into db_syscampo select 21973,'rh181_descricao','varchar(56)','Descrição do evento financeiro automatico','', 'Descrição',56,'f','f','f',0,'text','Descrição' from db_syscampo where not exists (select 1 from db_syscampo where codcam = 21973) limit 1;
insert into db_syscampo select 21974,'rh181_rubrica','varchar(4)','Rubrica a ser lançado no pondo de salário','', 'Rubrica',4,'f','f','f',3,'text','Rubrica' from db_syscampo where not exists (select 1 from db_syscampo where codcam = 21974) limit 1;
insert into db_syscampo select 21975,'rh181_mes','int4','Mês de lançamento do evento financeiro','0', 'Mês',2,'f','f','f',1,'text','Mês' from db_syscampo where not exists (select 1 from db_syscampo where codcam = 21975) limit 1;
insert into db_syscampo select 21976,'rh181_selecao','int4','Seleção para qual deve ser lançado o evento financeiro','0', 'Seleção',10,'f','f','f',1,'text','Seleção' from db_syscampo where not exists (select 1 from db_syscampo where codcam = 21976) limit 1;
insert into db_syscampo select 21977,'rh181_instituicao','int4','Instituição a qual esta configuração pertence','0', 'Intituição',20,'f','f','f',1,'text','Intituição' from db_syscampo where not exists (select 1 from db_syscampo where codcam = 21977) limit 1;
insert into db_sysarqcamp select 3955,21972,1,0 from db_sysarqcamp where not exists (select 1 from db_sysarqcamp where codcam = 21972) limit 1;
insert into db_sysarqcamp select 3955,21973,2,0 from db_sysarqcamp where not exists (select 1 from db_sysarqcamp where codcam = 21973) limit 1;
insert into db_sysarqcamp select 3955,21974,3,0 from db_sysarqcamp where not exists (select 1 from db_sysarqcamp where codcam = 21974) limit 1;
insert into db_sysarqcamp select 3955,21975,4,0 from db_sysarqcamp where not exists (select 1 from db_sysarqcamp where codcam = 21975) limit 1;
insert into db_sysarqcamp select 3955,21976,5,0 from db_sysarqcamp where not exists (select 1 from db_sysarqcamp where codcam = 21976) limit 1;
insert into db_sysarqcamp select 3955,21977,6,0 from db_sysarqcamp where not exists (select 1 from db_sysarqcamp where codcam = 21977) limit 1;
insert into db_sysprikey (codarq,codcam,sequen,camiden) select 3955,21972,1,21972 from db_sysprikey where not exists (select 1 from db_sysprikey where codarq = 3955) limit 1;
insert into db_sysindices select 4372,'eventofinanceiroautomatico_rubrica_mes_selecao_instituicao_un',3955,'1' from db_sysindices where not exists (select 1 from db_sysindices where codind = 4372) limit 1;
insert into db_syscadind select 4372,21974,1 from db_syscadind where not exists(select 1 from db_syscadind where codcam = 21974) limit 1;
insert into db_syscadind select 4372,21975,2 from db_syscadind where not exists(select 1 from db_syscadind where codcam = 21975) limit 1;
insert into db_syscadind select 4372,21976,3 from db_syscadind where not exists(select 1 from db_syscadind where codcam = 21976) limit 1;
insert into db_syscadind select 4372,21977,4 from db_syscadind where not exists(select 1 from db_syscadind where codcam = 21977) limit 1;
insert into db_sysforkey select 3955,21976,1,591,0 from db_sysforkey where not exists (select 1 from db_sysforkey where codcam = 21976 and referen = 591) limit 1;
insert into db_sysforkey select 3955,21977,2,591,0 from db_sysforkey where not exists (select 1 from db_sysforkey where codcam = 21977 and referen = 591) limit 1;
insert into db_sysforkey select 3955,21977,1,83,0 from db_sysforkey where not exists (select 1 from db_sysforkey where codcam = 21977 and referen = 83) limit 1;
insert into db_sysforkey select 3955,21974,1,1177,0 from db_sysforkey where not exists (select 1 from db_sysforkey where codcam = 21974 and referen = 1177) limit 1;
insert into db_sysforkey select 3955,21977,2,1177,0 from db_sysforkey where not exists (select 1 from db_sysforkey where codcam = 21977 and referen = 1177) limit 1;
insert into db_syssequencia select 1000590, 'eventofinanceiroautomatico_rh181_sequencial_seq', 1, 1, 9223372036854775807, 1, 1 from db_syssequencia where not exists (select 1 from db_syssequencia where codsequencia = 1000590) limit 1;
update db_sysarqcamp set codsequencia = 1000590 where codarq = 3955 and codcam = 21972;

---------------------------------------------------------------------------------------------------------------
---------------------------------------- DDL ------------------------------------------------------------------
---------------------------------------------------------------------------------------------------------------

--  Criando  sequences
select fc_executa_ddl('CREATE SEQUENCE pessoal.eventofinanceiroautomatico_rh181_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1');

-- Módulo: pessoal
CREATE TABLE IF NOT EXISTS eventofinanceiroautomatico(
rh181_sequencial    int4 NOT NULL default 0,
rh181_descricao   varchar(56) NOT NULL ,
rh181_rubrica   varchar(4) NOT NULL ,
rh181_mes   int4 NOT NULL default 0,
rh181_selecao   int4 NOT NULL default 0,
rh181_instituicao   int4 default 0,
CONSTRAINT eventofinanceiroautomatico_sequ_pk PRIMARY KEY (rh181_sequencial));

-- CHAVE ESTRANGEIRA
ALTER TABLE IF EXISTS eventofinanceiroautomatico DROP CONSTRAINT IF EXISTS eventofinanceiroautomatico_instituicao_fk;

ALTER TABLE IF EXISTS eventofinanceiroautomatico
ADD CONSTRAINT eventofinanceiroautomatico_instituicao_fk FOREIGN KEY (rh181_instituicao)
REFERENCES db_config;

ALTER TABLE IF EXISTS eventofinanceiroautomatico DROP CONSTRAINT IF EXISTS eventofinanceiroautomatico_selecao_instituicao_fk;

ALTER TABLE IF EXISTS eventofinanceiroautomatico
ADD CONSTRAINT eventofinanceiroautomatico_selecao_instituicao_fk FOREIGN KEY (rh181_selecao,rh181_instituicao)
REFERENCES selecao;

ALTER TABLE IF EXISTS eventofinanceiroautomatico DROP CONSTRAINT IF EXISTS eventofinanceiroautomatico_rubrica_instituicao_fk;

ALTER TABLE IF EXISTS eventofinanceiroautomatico
ADD CONSTRAINT eventofinanceiroautomatico_rubrica_instituicao_fk FOREIGN KEY (rh181_rubrica,rh181_instituicao)
REFERENCES rhrubricas;

-- INDICES
select fc_executa_ddl('CREATE UNIQUE INDEX pessoal.eventofinanceiroautomatico_rubrica_mes_selecao_instituicao_un ON eventofinanceiroautomatico(rh181_rubrica,rh181_mes,rh181_selecao,rh181_instituicao);');

SQL;
  
    $this->execute($sql);
  }

public function down(){}

}
