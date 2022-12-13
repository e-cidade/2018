<?php

use Classes\PostgresMigration;

class M6592 extends PostgresMigration
{

public function up(){
    $sql = <<<'SQL'

---------------------------------------------------------------------------------------------------------------
------------------------------------- INICIO PRE (dicionário de dados) ----------------------------------------
---------------------------------------------------------------------------------------------------------------
--Inserções da tabela grupotaxadiversos
insert into db_sysarquivo select 3971, 'grupotaxadiversos', 'Agrupa várias taxas para gerar o mesmo débito.', 'y118', '2016-09-22', 'Grupo de Taxas de Diversos', 0, 'f', 'f', 't', 't' from db_sysarquivo where not exists (select 1 from db_sysarquivo where codarq = 3971) limit 1;

delete from db_sysarqmod where codarq = 3971;
insert into db_sysarqmod values (25,3971);

insert into db_syscampo select 22046,'y118_sequencial','int8','Sequencial da tabela','0', 'Sequencial',19,'f','f','f',1,'text','Sequencial' from db_syscampo where not exists (select 1 from db_syscampo where codcam = 22046) limit 1;
insert into db_syscampo select 22047,'y118_descricao','varchar(100)','Descrição do grupo de taxas.','', 'Descrição',100,'f','t','f',0,'text','Descrição' from db_syscampo where not exists (select 1 from db_syscampo where codcam = 22047) limit 1;
insert into db_syscampo select 22048,'y118_inflator','varchar(5)','Código do inflator','', 'Código Inflator',5,'f','t','f',0,'text','Código Inflator' from db_syscampo where not exists (select 1 from db_syscampo where codcam = 22048) limit 1;
insert into db_syscampo select 22050,'y118_procedencia','int4','Procedência do débito','0', 'Procedência',19,'f','f','f',1,'text','Procedência' from db_syscampo where not exists (select 1 from db_syscampo where codcam = 22050) limit 1;
update db_syscampo set nomecam = 'y119_natureza', conteudo = 'text', descricao = 'Natureza da taxa', valorinicial = '', rotulo = 'Natureza', nulo = 'f', tamanho = 100, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Natureza' where codcam = 22053;

delete from db_sysarqcamp where codarq = 3971;
insert into db_sysarqcamp values(3971,22046,1,0);
insert into db_sysarqcamp values(3971,22047,2,0);
insert into db_sysarqcamp values(3971,22048,3,0);
insert into db_sysarqcamp values(3971,22050,5,0);

delete from db_sysprikey where codarq = 3971;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3971,22046,1,22046);

delete from db_sysforkey where codarq = 3971;
insert into db_sysforkey values(3971,22048,1,81,0);
insert into db_sysforkey values(3971,22050,1,374,0);

insert into db_sysindices select 4381,'grupotaxadiversos_procedencia_in',3971,'0' from db_sysindices where not exists (select 1 from db_sysindices where codind = 4381) limit 1;
insert into db_sysindices select 4382,'grupotaxadiversos_inflator_in',3971,'0' from db_sysindices where not exists (select 1 from db_sysindices where codind = 4382) limit 1;

delete from db_syscadind where codind IN (4381,4382);
insert into db_syscadind values(4381,22050,1);
insert into db_syscadind values(4382,22048,1);

insert into db_syssequencia select 1000603, 'grupotaxadiversos_y118_sequencial_seq', 1, 1, 9223372036854775807, 1, 1 from db_syssequencia where not exists (select 1 from db_syssequencia where codsequencia = 1000603) limit 1;
update db_sysarqcamp set codsequencia = 1000603 where codarq = 3971 and codcam = 22046;
--Ajuste na chave estrangeira de inflatores
update db_sysindices set nomeind = 'grupotaxadiversos_inflator_in',campounico = '0' where codind = 4382;
delete from db_syscadind where codind = 4382;
insert into db_syscadind values(4382,22048,1);
delete from db_sysforkey where codarq = 3971 and referen = 81;
insert into db_sysforkey values(3971,22048,1,80,0);
delete from db_sysarqcamp where codarq = 3971;
insert into db_sysarqcamp values(3971,22046,1,1000603);
insert into db_sysarqcamp values(3971,22047,2,0);
insert into db_sysarqcamp values(3971,22048,3,0);
insert into db_sysarqcamp values(3971,22050,4,0);
update db_syscampo set nomecam = 'y118_inflator', conteudo = 'varchar(5)', descricao = 'Código do inflator', valorinicial = '', rotulo = 'Código Inflator', nulo = 'f', tamanho = 5, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Código Inflator' where codcam = 22048;
delete from db_syscampodep where codcam = 22048;
delete from db_syscampodef where codcam = 22048;

--Inserções da tabela taxadiversos
insert into db_sysarquivo select 3973, 'taxadiversos', 'Taxas diversas.', 'y119', '2016-09-22', 'Taxas Diversas', 0, 'f', 'f', 't', 't' from db_sysarquivo where not exists (select 1 from db_sysarquivo where codarq = 3973) limit 1;
insert into db_sysarqmod select 25, 3973 from db_sysarqmod where not exists(select 1 from db_sysarqmod where codmod = 25 and codarq = 3973) limit 1;

insert into db_syscampo select 22051,'y119_sequencial','int4','Sequencial da tabela','0', 'Sequencial',19,'f','f','f',1,'text','Sequencial' from db_syscampo where not exists (select 1 from db_syscampo where codcam = 22051) limit 1;
insert into db_syscampo select 22052,'y119_grupotaxadiversos','int4','Grupo de taxas','0', 'Grupo',19,'f','f','f',1,'text','Grupo' from db_syscampo where not exists (select 1 from db_syscampo where codcam = 22052) limit 1;
insert into db_syscampo select 22053,'y119_natureza','text','Natureza da taxa','', 'Natureza',1,'f','t','f',0,'text','Natureza' from db_syscampo where not exists (select 1 from db_syscampo where codcam = 22053) limit 1;
insert into db_syscampo select 22054,'y119_formula','int4','Fórmula da taxa','0', 'Fórmula',19,'f','f','f',1,'text','Fórmula' from db_syscampo where not exists (select 1 from db_syscampo where codcam = 22054) limit 1;
insert into db_syscampo select 22055,'y119_unidade','varchar(50)','Unidade para cálculo da taxa','', 'Unidade',50,'f','t','f',0,'text','Unidade' from db_syscampo where not exists (select 1 from db_syscampo where codcam = 22055) limit 1;
insert into db_syscampo select 22056,'y119_tipo_periodo','char(1)','Tipo do período se aberto, sem data final ou fixo','', 'Tipo de Período',1,'f','t','f',0,'text','Tipo de Período' from db_syscampo where not exists (select 1 from db_syscampo where codcam = 22056) limit 1;
insert into db_syscampo select 22125,'y119_tipo_calculo','char(1)','Tipo de cálculo, Geral ou Único. Se uma taxa geral será recalculada anualmente, se única ser calculada apenas no lançamento.','', 'Tipo de Cálculo',1,'f','t','f',0,'text','Tipo de Cálculo' from db_syscampo where not exists (select 1 from db_syscampo where codcam = 22125) limit 1;
update db_syscampo set nomecam = 'y119_natureza', conteudo = 'text', descricao = 'Natureza da taxa', valorinicial = '', rotulo = 'Natureza', nulo = 'f', tamanho = 100, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Natureza' where codcam = 22053;

delete from db_sysarqcamp where codarq = 3973;
insert into db_sysarqcamp values(3973,22051,1,0);
insert into db_sysarqcamp values(3973,22052,2,0);
insert into db_sysarqcamp values(3973,22053,3,0);
insert into db_sysarqcamp values(3973,22054,4,0);
insert into db_sysarqcamp values(3973,22055,5,0);
insert into db_sysarqcamp values(3973,22056,6,0);
insert into db_sysarqcamp values(3973,22125,7,0);

delete from db_sysprikey where codarq = 3973;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3973,22051,1,22051);

delete from db_sysforkey where codarq = 3973;
insert into db_sysforkey values(3973,22052,1,3971,0);
insert into db_sysforkey values(3973,22054,1,3820,0);

insert into db_sysindices select 4383,'taxadiversos_grupotaxadiversos_in',3973,'0' from db_sysindices where not exists (select 1 from db_sysindices where codind = 4383) limit 1;
insert into db_sysindices select 4384,'taxadiversos_formula_in',3973,'0' from db_sysindices where not exists (select 1 from db_sysindices where codind = 4384) limit 1;

delete from db_syscadind where codind IN (4383, 4384);
insert into db_syscadind values(4383,22052,1);
insert into db_syscadind values(4384,22054,1);

insert into db_syssequencia select 1000604, 'taxadiversos_y119_sequencial_seq', 1, 1, 9223372036854775807, 1, 1 from db_syssequencia where not exists (select 1 from db_syssequencia where codsequencia = 1000604) limit 1;
update db_sysarqcamp set codsequencia = 1000604 where codarq = 3973 and codcam = 22051;

--Inserções da tabela lancamentotaxadiversos
insert into db_sysarquivo select 3974, 'lancamentotaxadiversos', 'Tabela para lançamento das taxas', 'y120', '2016-09-23', 'Lançamento de Taxas diversas', 0, 'f', 'f', 't', 't' where not exists (select 1 from db_sysarquivo where codarq = 3974);
insert into db_sysarqmod select 25, 3974 from db_sysarqmod where not exists(select 1 from db_sysarqmod where codmod = 25 and codarq = 3974) limit 1;

insert into db_syscampo select 22057,'y120_sequencial','int4','Sequencial da tabela','0', 'Sequencial',19,'f','f','f',1,'text','Sequencial' where not exists (select 1 from db_syscampo where codcam = 22057);
insert into db_syscampo select 22058,'y120_cgm','int4','Cgm ao qual a taxa será vinculada.','0', 'CGM',19,'f','f','f',1,'text','CGM' where not exists (select 1 from db_syscampo where codcam = 22058);
insert into db_syscampo select 22059,'y120_taxadiversos','int4','Taxa diversa que será calculada','0', 'Taxa',19,'f','f','f',1,'text','Taxa' where not exists (select 1 from db_syscampo where codcam = 22059);
insert into db_syscampo select 22060,'y120_unidade','float8','Quantidade de unidades para ser calculada a taxa','0', 'Unidade',19,'f','f','f',4,'text','Unidade' where not exists (select 1 from db_syscampo where codcam = 22060);
insert into db_syscampo select 22079, 'y120_periodo', 'float8', 'Período para cálculo da taxa', '0', 'Período', 19, 't', 'f', 'f', 4, 'text', 'Período'  where not exists (select 1 from db_syscampo where codcam = 22079);
insert into db_syscampo select 22061,'y120_datainicio','date','Data de início','null', 'Data de Início',10,'f','f','f',1,'text','Data de Início' where not exists (select 1 from db_syscampo where codcam = 22061);
insert into db_syscampo select 22062,'y120_datafim','date','Data de fim','null', 'Data de fim',10,'f','f','f',1,'text','Data de fim' where not exists (select 1 from db_syscampo where codcam = 22062);
insert into db_syscampo select 22124,'y120_issbase','int4','Código da Inscrição Municipal do CGM.','0', 'Inscrição Municipal',10,'t','f','f',1,'text','Inscrição Municipal' where not exists (select 1 from db_syscampo where codcam = 22124);
update db_syscampo set nomecam = 'y120_datainicio', conteudo = 'date', descricao = 'Data de início', valorinicial = 'null', rotulo = 'Data de Início', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Data de Início' where codcam = 22061;
update db_syscampo set nomecam = 'y120_datafim', conteudo = 'date', descricao = 'Data de fim', valorinicial = 'null', rotulo = 'Data de fim', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Data de fim' where codcam = 22062;
update db_syscampo set nomecam = 'y120_periodo', conteudo = 'float8', descricao = 'Período para cálculo da taxa', valorinicial = '0', rotulo = 'Período', nulo = 't', tamanho = 19, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Período' where codcam = 22079;
update db_syscampo set nomecam = 'y120_cgm', conteudo = 'int4', descricao = 'Cgm ao qual a taxa será vinculada.', valorinicial = '0', rotulo = 'CGM', nulo = 't', tamanho = 19, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'CGM' where codcam = 22058;

delete from db_sysarqcamp where codarq = 3974;
insert into db_sysarqcamp values(3974,22057,1,1000605);
insert into db_sysarqcamp values(3974,22058,2,0);
insert into db_sysarqcamp values(3974,22059,3,0);
insert into db_sysarqcamp values(3974,22060,4,0);
insert into db_sysarqcamp values(3974,22079,5,0);
insert into db_sysarqcamp values(3974,22061,6,0);
insert into db_sysarqcamp values(3974,22062,7,0);
insert into db_sysarqcamp values(3974,22124,8,0);

delete from db_sysprikey where codarq = 3974;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3974,22057,1,22057);

delete from db_sysforkey where codarq = 3974;
insert into db_sysforkey values(3974,22058,1,42,0);
insert into db_sysforkey values(3974,22059,1,3973,0);
insert into db_sysforkey values(3974,22124,1,41,0);

insert into db_sysindices select 4385,'lancamentotaxadiversos_cgm_in',3974,'0' where not exists (select 1 from db_sysindices where codind = 4385);
insert into db_sysindices select 4386,'lancamentotaxadiversos_taxadiversos_in',3974,'0' where not exists (select 1 from db_sysindices where codind = 4386);
insert into db_sysindices select 4389,'lancamentotaxadiversos_issbase_in',3974,'0' where not exists (select 1 from db_sysindices where codind = 4389);

delete from db_syscadind where codind IN (4385,4386,4389);
insert into db_syscadind values(4385,22058,1);
insert into db_syscadind values(4386,22059,1);
insert into db_syscadind values(4389,22124,1);

insert into db_syssequencia select 1000605, 'lancamentotaxadiversos_y120_sequencial_seq', 1, 1, 9223372036854775807, 1, 1 where not exists (select 1 from db_syssequencia where codsequencia = 1000605);
update db_sysarqcamp set codsequencia = 1000605 where codarq = 3974 and codcam = 22057;

--Inserções da tabela taxavaloresreferencia
insert into db_sysarquivo select 3975, 'taxavaloresreferencia', 'Tabela para armazenar os valores de referência das taxas diversas.', 'y121', '2016-09-23', 'Valores de Referência das taxas', 0, 'f', 't', 't', 't' where not exists (select 1 from db_sysarquivo where codarq = 3975);
insert into db_sysarqmod select 25, 3975 from db_sysarqmod where not exists(select 1 from db_sysarqmod where codmod = 25 and codarq = 3975) limit 1;

insert into db_syscampo select 22070,'y121_sequencial','int4','Sequencial da tabela','0', 'Sequencial',19,'f','f','f',1,'text','Sequencial' where not exists (select 1 from db_syscampo where codcam = 22070);
insert into db_syscampo select 22071,'y121_descricao','varchar(100)','Descrição do valor de referência da taxa','', 'Descrição',100,'f','t','f',0,'text','Descrição' where not exists (select 1 from db_syscampo where codcam = 22071);
insert into db_syscampo select 22072,'y121_valor','float8','Valor base de referência para a taxa','0', 'Valor Base',19,'f','f','f',4,'text','Valor Base' where not exists (select 1 from db_syscampo where codcam = 22072);
insert into db_syscampo select 22091,'y121_data_base','date','Data para atualização do valor base das taxas.','','Data Base',10,'false','false','false',1,'text','Data Base' where not exists (select 1 from db_syscampo where codcam = 22091) limit 1;

delete from db_sysarqcamp where codarq = 3975;
insert into db_sysarqcamp values(3975,22070,1,0);
insert into db_sysarqcamp values(3975,22071,2,0);
insert into db_sysarqcamp values(3975,22072,3,0);
insert into db_sysarqcamp values(3975,22091,4,0);

delete from db_sysprikey where codarq = 3975;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3975,22070,1,22070);

insert into db_syssequencia select 1000606, 'taxavaloresreferencia_y121_sequencial_seq', 1, 1, 9223372036854775807, 1, 1 where not exists (select 1 from db_syssequencia where codsequencia = 1000606);
update db_sysarqcamp set codsequencia = 1000606 where codarq = 3975 and codcam = 22070;

--Inclusão de menus
insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) select 10310 ,'Taxas' ,'Cadastro de Taxas diversas' ,'' ,'1' ,'1' ,'Menu para cadastro de grupo de taxas e de taxas diversas' ,'true' where not exists (select 1 from db_itensmenu where id_item = 10310);
delete from db_menu where id_item_filho = 10310 AND modulo = 277;
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 29 ,10310 ,271 ,277 );
insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) select 10311 ,'Grupos' ,'Grupos de Taxas diversas' ,'fis1_grupotaxadiversos001.php' ,'1' ,'1' ,'Menu para agrupamento de taxas diversas' ,'true' where not exists (select 1 from db_itensmenu where id_item = 10311);
delete from db_menu where id_item_filho = 10311 AND modulo = 277;
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10310 ,10311 ,1 ,277 );
insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) select 10312 ,'Natureza' ,'Taxas diversas' ,'fis1_taxadiversos001.php' ,'1' ,'1' ,'Menu para cadastro de taxas diversas' ,'true' where not exists (select 1 from db_itensmenu where id_item = 10312);
delete from db_menu where id_item_filho = 10312 AND modulo = 277;
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10310 ,10312 ,2 ,277 );
    
insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) select 10313 ,'Taxas' ,'Inclusão e cálculo de taxas' ,'' ,'1' ,'1' ,'Menu para inclusão de uma taxas para um CGM e cálculo geral de taxas' ,'true' where not exists (select 1 from db_itensmenu where id_item = 10313);
delete from db_menu where id_item_filho = 10313 AND modulo = 277;
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 1818 ,10313 ,115 ,277 );
insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) select 10314 ,'Lançamento' ,'Lança uma taxa' ,'fis4_lancamentotaxadiversos.php' ,'1' ,'1' ,'Menu para lançar uma taxa para um contribuinte.' ,'true' where not exists (select 1 from db_itensmenu where id_item = 10314);
delete from db_menu where id_item_filho = 10314 AND modulo = 277;
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10313 ,10314 ,1 ,277 );
insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) select 10315 ,'Cálculo Geral' ,'Cálculo geral de taxas' ,'fis4_calculotaxadiversos.php' ,'1' ,'1' ,'Menu para cálculo geral de taxas.' ,'true' where not exists (select 1 from db_itensmenu where id_item = 10315);
delete from db_menu where id_item_filho = 10315 AND modulo = 277;
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10313 ,10315 ,2 ,277 );

--- diversoslancamentotaxa
insert into db_sysarquivo select 3978, 'diversoslancamentotaxa', 'Guarda os Débitos de Diversos lançados para um Taxa', 'dv14', '2016-10-04', 'Diversos Lançados', 0, 'f', 't', 't', 't' where not exists (select 1 from db_sysarquivo where codarq = 3978) limit 1;

delete from db_sysarqmod where codarq = 3978;
insert into db_sysarqmod values (27,3978);

insert into db_syscampo select 22087, 'dv14_sequencial', 'int4', 'Identificador da Ligação', '', 'Código', 10, 'false', 'false', 'false', 1, 'text', 'Código' where not exists (select 1 from db_syscampo where codcam = 22087) limit 1;
insert into db_syscampo select 22088, 'dv14_diversos', 'int4', 'Código do diversos', '', 'Código do Diverso', 10, 'false', 'false', 'false', 1, 'text', 'Código do Diverso' where not exists (select 1 from db_syscampo where codcam = 22088) limit 1;
insert into db_syscampo select 22089, 'dv14_lancamentotaxadiversos', 'int4', 'Sequencial da tabela.', '', 'Código do Lançamento', 19, 'false', 'false', 'false', 1, 'text', 'Código do Lançamento' where not exists (select 1 from db_syscampo where codcam = 22089) limit 1;
insert into db_syscampo select 22094 ,'dv14_data_calculo' ,'date' ,'Data do Cálculo geral da taxa de diversos.' ,'' ,'Data do Cálculo' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Data do Cálculo' where not exists (select 1 from db_syscampo where codcam = 22094) limit 1;

delete from db_syscampodep where codcam IN (22088, 22089);
insert into db_syscampodep (codcam, codcampai) values (22088, 3470);
insert into db_syscampodep (codcam, codcampai) values (22089, 22057);

delete from db_sysarqcamp where codarq = 3978;
insert into db_sysarqcamp (codarq, codcam, seqarq, codsequencia) values (3978, 22087, 1, 0);
insert into db_sysarqcamp (codarq, codcam, seqarq, codsequencia) values (3978, 22088, 2, 0);
insert into db_sysarqcamp (codarq, codcam, seqarq, codsequencia) values (3978, 22089, 3, 0);
insert into db_sysarqcamp (codarq, codcam, seqarq, codsequencia) values (3978, 22094, 4, 0);

delete from db_sysprikey where codarq = 3978;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3978,22087,1,22089);

delete from db_sysforkey where codarq = 3978;
insert into db_sysforkey values(3978, 22088, 1, 372, 0);
insert into db_sysforkey values(3978, 22089, 1, 3974, 0);

insert into db_syssequencia select 1000609, 'diversoslancamentotaxa_dv14_sequencial_seq', 1, 1, 9223372036854775807, 1, 1 where not exists (select 1 from db_syssequencia where codsequencia = 1000609) limit 1;
update db_sysarqcamp set codsequencia = 1000609 where codarq = 3978 and codcam = 22087;

---------------------------------------------------------------------------------------------------------------
---------------------------------------------- INICIO DDL -----------------------------------------------------
---------------------------------------------------------------------------------------------------------------

-- Cria tabela e sequence para tabela grupotaxadiversos
select fc_executa_ddl('
  create sequence fiscal.grupotaxadiversos_y118_sequencial_seq
    increment 1
    minvalue 1
    maxvalue 9223372036854775807
    start 1
    cache 1;
');

CREATE table IF NOT EXISTS fiscal.grupotaxadiversos (
  y118_sequencial       int4         NOT NULL default nextval('fiscal.grupotaxadiversos_y118_sequencial_seq'),
  y118_descricao        varchar(100) NOT NULL,
  y118_inflator         varchar(5)   NOT NULL,
  y118_procedencia      int4         NOT NULL,
  CONSTRAINT grupotaxadiversos_sequencial_pk PRIMARY KEY (y118_sequencial),
  CONSTRAINT grupotaxadiversos_inflator_fk FOREIGN KEY (y118_inflator) REFERENCES inflatores.inflan,
  CONSTRAINT grupotaxadiversos_procedencia_fk FOREIGN KEY (y118_procedencia) REFERENCES diversos.procdiver
);
select fc_executa_ddl('CREATE INDEX grupotaxadiversos_procedencia_in ON fiscal.grupotaxadiversos(y118_procedencia)');
select fc_executa_ddl('CREATE INDEX grupotaxadiversos_inflator_in ON fiscal.grupotaxadiversos(y118_inflator)');

-- Cria tabela e sequence para tabela taxadiversos
select fc_executa_ddl('
  create sequence fiscal.taxadiversos_y119_sequencial_seq
    increment 1
    minvalue 1
    maxvalue 9223372036854775807
    start 1
    cache 1;
') as taxadiversos_y119_sequencial_seq;

CREATE table IF NOT EXISTS fiscal.taxadiversos (
  y119_sequencial          int4         NOT NULL default nextval('fiscal.taxadiversos_y119_sequencial_seq'),
  y119_grupotaxadiversos   int4         NOT NULL,
  y119_natureza            text         NOT NULL,
  y119_formula             int4         NOT NULL,
  y119_unidade             varchar(50)  NOT NULL,
  y119_tipo_periodo        char(1)      NOT NULL,
  y119_tipo_calculo        char(1)      NOT NULL,
  CONSTRAINT taxadiversos_sequencial_pk PRIMARY KEY (y119_sequencial),
  CONSTRAINT taxadiversos_grupotaxadiversos_fk FOREIGN KEY (y119_grupotaxadiversos) REFERENCES fiscal.grupotaxadiversos,
  CONSTRAINT taxadiversos_formula_fk FOREIGN KEY (y119_formula) REFERENCES configuracoes.db_formulas
);
select fc_executa_ddl('CREATE INDEX taxadiversos_grupotaxadiversos_in ON fiscal.taxadiversos(y119_grupotaxadiversos)');
select fc_executa_ddl('CREATE INDEX taxadiversos_formula_in ON fiscal.taxadiversos(y119_formula)');

-- Cria tabela e sequence para tabela lancamentotaxadiversos
select fc_executa_ddl('
  create sequence fiscal.lancamentotaxadiversos_y120_sequencial_seq
    increment 1
    minvalue 1
    maxvalue 9223372036854775807
    start 1
    cache 1;
') as lancamentotaxadiversos_y120_sequencial_seq;

CREATE table IF NOT EXISTS fiscal.lancamentotaxadiversos (
  y120_sequencial     int4      NOT NULL default nextval('fiscal.lancamentotaxadiversos_y120_sequencial_seq'),
  y120_cgm            int4,
  y120_taxadiversos   int4      NOT NULL,
  y120_unidade        float8    NOT NULL,
  y120_periodo        float8,
  y120_datainicio     date,
  y120_datafim        date,
  y120_issbase        int4,
  CONSTRAINT lancamentotaxadiversos_sequencial_pk PRIMARY KEY (y120_sequencial),
  CONSTRAINT lancamentotaxadiversos_cgm_fk FOREIGN KEY (y120_cgm) REFERENCES protocolo.cgm,
  CONSTRAINT lancamentotaxadiversos_taxadiversos_fk FOREIGN KEY (y120_taxadiversos) REFERENCES fiscal.taxadiversos,
  CONSTRAINT lancamentotaxadiversos_issbase_fk FOREIGN KEY (y120_issbase) REFERENCES issqn.issbase
);
select fc_executa_ddl('CREATE INDEX lancamentotaxadiversos_cgm_in ON fiscal.lancamentotaxadiversos(y120_cgm)');
select fc_executa_ddl('CREATE INDEX lancamentotaxadiversos_taxadiversos_in ON fiscal.lancamentotaxadiversos(y120_taxadiversos)');
select fc_executa_ddl('CREATE INDEX lancamentotaxadiversos_issbase_in ON fiscal.lancamentotaxadiversos(y120_issbase)');

-- Cria tabela e sequence para tabela taxavaloresreferencia
select fc_executa_ddl('
  create sequence fiscal.taxavaloresreferencia_y121_sequencial_seq
    increment 1
    minvalue 1
    maxvalue 9223372036854775807
    start 1
    cache 1;
') as taxavaloresreferencia_y121_sequencial_seq;

CREATE table IF NOT EXISTS fiscal.taxavaloresreferencia (
  y121_sequencial         int4           NOT NULL default nextval('fiscal.taxavaloresreferencia_y121_sequencial_seq'),
  y121_descricao          varchar(100)   NOT NULL,
  y121_valor              float8         NOT NULL,
  y121_data_base          date           NOT NULL default current_date,
  CONSTRAINT taxavaloresreferencia_sequencial_pk PRIMARY KEY (y121_sequencial)
);
select fc_executa_ddl('CREATE UNIQUE INDEX taxavaloresreferencia_descricao_un ON fiscal.taxavaloresreferencia(y121_descricao)');

SELECT fc_executa_ddl('CREATE SEQUENCE diversos.diversoslancamentotaxa_dv14_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;');
CREATE TABLE IF NOT EXISTS diversos.diversoslancamentotaxa(
  dv14_sequencial              int4 NOT NULL default nextval('diversos.diversoslancamentotaxa_dv14_sequencial_seq'),
  dv14_diversos                int4 NOT NULL,
  dv14_lancamentotaxadiversos  int4 NOT NULL,
  dv14_data_calculo            date,
  CONSTRAINT diversoslancamentotaxa_sequ_pk                   PRIMARY KEY (dv14_sequencial),
  CONSTRAINT diversoslancamentotaxa_diversos_fk               FOREIGN KEY (dv14_diversos)               REFERENCES diversos.diversos,
  CONSTRAINT diversoslancamentotaxa_lancamentotaxadiversos_fk FOREIGN KEY (dv14_lancamentotaxadiversos) REFERENCES fiscal.lancamentotaxadiversos
);
---------------------------------------------------------------------------------------------------------------
---------------------------------------- FINAL TRIBUTARIO -----------------------------------------------------
---------------------------------------------------------------------------------------------------------------

SQL;
  
    $this->execute($sql);
  }

public function down(){}

}