<?php

use Classes\PostgresMigration;

class M5291 extends PostgresMigration
{

public function up(){
    $sql = <<<'SQL'

    select fc_executa_ddl($$
  insert into db_sysarquivo values (3986, 'emissaogeral', 'Tabela que registra as informações das Emissões Gerais do Sistema', 'tr01', '2016-11-04', 'Emissao Geral', 0, 'f', 'f', 'f', 'f' );
  insert into db_sysarqmod values (46,3986);
  insert into db_sysarquivo values (3987, 'emissaogeralregistro', 'Tabela que salva as informações dos registros gerados nas Emissões Gerais no Sistema', 'tr02', '2016-11-04', 'Emissao Geral Registro', 0, 'f', 'f', 'f', 'f' );
  insert into db_sysarqmod values (46,3987);
  insert into db_sysarquivo values (3988, 'emissaogeralmatricula', 'Tabela que vincula os registros das Emissões Gerais do Sistemas com as suas matrículas, quando necessário.', 'tr03', '2016-11-04', 'Emissao Geral Matrícula', 0, 'f', 'f', 'f', 'f' );
  insert into db_sysarqmod values (46,3988);
  insert into db_sysarquivo values (3989, 'emissaogeralinscricao', 'Tabela que vincula os registros das Emissões Gerais do Sistemas com as suas inscrições, quando necessário.', 'tr04', '2016-11-04', 'Emissão Geral Inscrição', 0, 'f', 'f', 'f', 'f' );
  insert into db_sysarqmod values (46,3989);
  insert into db_syscampo values(22126,'tr01_sequencial','int4','Código Sequencial da Emissão Geral','0', 'Código da Emissão Geral',10,'f','f','f',1,'text','Código da Emissão Geral');
  insert into db_syscampo values(22127,'tr01_data','date','Data da Emissão da Emissão Geral','null', 'Data Emissão',10,'f','f','f',1,'text','Data Emissão');
  insert into db_syscampo values(22128,'tr01_usuario','int4','Usuário que processou a Emissão Geral','0', 'Usuário',10,'f','f','f',1,'text','Usuário');
  insert into db_syscampo values(22129,'tr01_tipoemissao','int4','Tipo de Emissão Geral','0', 'Tipo de Emissão',2,'f','f','f',1,'text','Tipo de Emissão');
  insert into db_syscampo values(22130,'tr01_instit','int4','Instituição','0', 'Instituição',10,'f','f','f',1,'text','Instituição');
  insert into db_syscampo values(22131,'tr01_convenio','int4','Convênio da Emissão Geral','0', 'Convênio',10,'t','f','f',1,'text','Convênio');
  insert into db_syscampo values(22132,'tr01_hora','char(5)','Hora da Emissão Geral','', 'Hora da Emissão',5,'f','t','f',0,'text','Hora da Emissão');
  insert into db_syscampo values(22160,'tr01_parametros','text','Parâmetros','','Parâmetros' ,1 ,'true' ,'false' ,'false' ,0 ,'text' ,'Parâmetros');
  insert into db_syscampo values(22133,'tr02_sequencial','int4','Código do Registro Emissão Geral','0', 'Código do Registro',10,'f','f','f',1,'text','Código do Registro');
  insert into db_syscampo values(22134,'tr02_emissaogeral','int4','Código da Emissão Geral','0', 'Código da Emissão Geral',10,'f','f','f',1,'text','Código da Emissão Geral');
  insert into db_syscampo values(22135,'tr02_numcgm','int4','CGM vinculado ao registro da Emissão Geral','0', 'CGM',10,'f','f','f',1,'text','CGM');
  insert into db_syscampo values(22136,'tr02_numpre','int4','Numpre do registro','0', 'Numpre',8,'f','f','f',1,'text','Numpre');
  insert into db_syscampo values(22138,'tr02_parcela','int4','Parcela do registro gerado na Emissão Geral','0', 'Parcela',10,'f','f','f',1,'text','Parcela');
  insert into db_syscampo values(22139,'tr02_situacao','int4','Situação do registro em relação a Emissão Geral(vide regra de Negócio da Emissão Geral a que se refere).','0', 'Situação',2,'f','f','f',1,'text','Situação');
  insert into db_syscampo values(22140,'tr03_sequencial','int4','Código do Registro da Emissão Geral','0', 'Código do Registro',10,'f','f','f',1,'text','Código do Registro');
  insert into db_syscampo values(22141,'tr03_emissaogeral','int4','Código da Emissão Geral','0', 'Código da Emissão Geral',10,'f','f','f',1,'text','Código da Emissão Geral');
  insert into db_syscampo values(22142,'tr03_emissaogeralregistro','int4','Código do Registro','0', 'Código do Registro',10,'f','f','f',1,'text','Código do Registro');
  insert into db_syscampo values(22143,'tr03_matric','int4','Matrícula vinculada ao registro','0', 'Matrícula',10,'f','f','f',1,'text','Matrícula');
  insert into db_syscampo values(22144,'tr04_sequencial','int4','Código Sequencial da tabela de vínculo do Registro com a Inscrição','0', 'Código Sequencial',10,'f','f','f',1,'text','Código Sequencial');
  insert into db_syscampo values(22145,'tr04_emissaogeralregistro','int4','Código da Emissão Geral','0', 'Código da Emissão Geral',10,'f','f','f',1,'text','Código da Emissão Geral');
  insert into db_syscampo values(22146,'tr04_inscr','int4','Inscrição vinculada ao Registro','0', 'Inscrição',10,'f','f','f',1,'text','Inscrição');
  insert into db_sysarqcamp values(3986,22126,1,0);
  insert into db_sysarqcamp values(3986,22127,2,0);
  insert into db_sysarqcamp values(3986,22128,3,0);
  insert into db_sysarqcamp values(3986,22129,4,0);
  insert into db_sysarqcamp values(3986,22130,5,0);
  insert into db_sysarqcamp values(3986,22131,6,0);
  insert into db_sysarqcamp values(3986,22132,7,0);
  insert into db_sysarqcamp values(3986,22160,8,0);
  insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3986,22126,1,22126);
  insert into db_sysarqcamp values(3987,22133,1,0);
  insert into db_sysarqcamp values(3987,22134,2,0);
  insert into db_sysarqcamp values(3987,22135,3,0);
  insert into db_sysarqcamp values(3987,22136,4,0);
  insert into db_sysarqcamp values(3987,22138,5,0);
  insert into db_sysarqcamp values(3987,22139,6,0);
  insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3987,22133,1,22134);
  insert into db_sysforkey values(3986,22128,1,109,0);
  insert into db_sysforkey values(3986,22130,1,83,0);
  insert into db_sysforkey values(3986,22131,1,2185,0);
  insert into db_syssequencia values(1000615, 'emissaogeral_tr01_sequencial_seq', 1, 1, 9223372036854775807, 1, 1 );
  update db_sysarqcamp set codsequencia = 1000615 where codarq = 3986 and codcam = 22126;
  insert into db_syssequencia values(1000616, 'emissaogeralregistro_tr02_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
  update db_sysarqcamp set codsequencia = 1000616 where codarq = 3987 and codcam = 22133;
  insert into db_sysforkey values(3987,22134,1,3986,0);
  insert into db_sysforkey values(3987,22135,1,42,0);
  insert into db_sysarqcamp values(3988,22140,1,0);
  insert into db_sysarqcamp values(3988,22142,2,0);
  insert into db_sysarqcamp values(3988,22143,3,0);
  insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3988,22140,1,22143);
  insert into db_sysforkey values(3988,22142,1,3987,0);
  insert into db_sysforkey values(3988,22143,1,27,0);
  insert into db_syssequencia values(1000617, 'emissaogeralmatricula_tr03_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
  update db_sysarqcamp set codsequencia = 1000617 where codarq = 3988 and codcam = 22140;
  insert into db_sysarqcamp values(3989,22144,1,0);
  insert into db_sysarqcamp values(3989,22145,2,0);
  insert into db_sysarqcamp values(3989,22146,3,0);
  insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3989,22144,1,22146);
  insert into db_sysforkey values(3989,22145,1,3987,0);
  insert into db_sysforkey values(3989,22146,1,41,0);
  insert into db_syssequencia values(1000618, 'emissaogeralinscricao_tr04_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
  update db_sysarqcamp set codsequencia = 1000618 where codarq = 3989 and codcam = 22144;
$$);


select fc_executa_ddl($$
  CREATE SEQUENCE tributario.emissaogeral_tr01_sequencial_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;

  CREATE SEQUENCE tributario.emissaogeralinscricao_tr04_sequencial_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;


  CREATE SEQUENCE tributario.emissaogeralmatricula_tr03_sequencial_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;


  CREATE SEQUENCE tributario.emissaogeralregistro_tr02_sequencial_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;
$$);

-- TABELAS E ESTRUTURA

-- Módulo: tributario
CREATE TABLE if not exists tributario.emissaogeral(
tr01_sequencial   int4 NOT NULL default 0,
tr01_data   date NOT NULL default null,
tr01_usuario    int4 NOT NULL default 0,
tr01_tipoemissao    int4 NOT NULL default 0,
tr01_instit   int4 NOT NULL default 0,
tr01_convenio   int4  default 0,
tr01_hora   char(5) ,
tr01_parametros text,
CONSTRAINT emissaogeral_sequ_pk PRIMARY KEY (tr01_sequencial));


-- Módulo: tributario
CREATE TABLE if not exists tributario.emissaogeralinscricao(
tr04_sequencial   int4 NOT NULL default 0,
tr04_emissaogeralregistro   int4 NOT NULL default 0,
tr04_inscr    int4 default 0,
CONSTRAINT emissaogeralinscricao_sequ_pk PRIMARY KEY (tr04_sequencial));


-- Módulo: tributario
CREATE TABLE if not exists tributario.emissaogeralmatricula(
tr03_sequencial   int4 NOT NULL default 0,
tr03_emissaogeralregistro   int4 NOT NULL default 0,
tr03_matric   int4 default 0,
CONSTRAINT emissaogeralmatricula_sequ_pk PRIMARY KEY (tr03_sequencial));


-- Módulo: tributario
CREATE TABLE if not exists tributario.emissaogeralregistro(
tr02_sequencial   int4 NOT NULL default 0,
tr02_emissaogeral   int4 NOT NULL default 0,
tr02_numcgm   int4 NOT NULL default 0,
tr02_numpre   int4 NOT NULL default 0,
tr02_parcela    int4 NOT NULL default 0,
tr02_situacao   int4 default 0,
CONSTRAINT emissaogeralregistro_sequ_pk PRIMARY KEY (tr02_sequencial));




-- CHAVE ESTRANGEIRA

select fc_executa_ddl($$
  ALTER TABLE tributario.emissaogeral
  ADD CONSTRAINT emissaogeral_instit_fk FOREIGN KEY (tr01_instit)
  REFERENCES db_config;

  ALTER TABLE tributario.emissaogeral
  ADD CONSTRAINT emissaogeral_convenio_fk FOREIGN KEY (tr01_convenio)
  REFERENCES cadconvenio;

  ALTER TABLE tributario.emissaogeral
  ADD CONSTRAINT emissaogeral_usuario_fk FOREIGN KEY (tr01_usuario)
  REFERENCES db_usuarios;

  ALTER TABLE tributario.emissaogeralinscricao
  ADD CONSTRAINT emissaogeralinscricao_emissaogeralregistro_fk FOREIGN KEY (tr04_emissaogeralregistro)
  REFERENCES emissaogeralregistro;

  ALTER TABLE tributario.emissaogeralinscricao
  ADD CONSTRAINT emissaogeralinscricao_inscr_fk FOREIGN KEY (tr04_inscr)
  REFERENCES issbase;

  ALTER TABLE tributario.emissaogeralmatricula
  ADD CONSTRAINT emissaogeralmatricula_emissaogeralregistro_fk FOREIGN KEY (tr03_emissaogeralregistro)
  REFERENCES emissaogeralregistro;

  ALTER TABLE tributario.emissaogeralmatricula
  ADD CONSTRAINT emissaogeralmatricula_matric_fk FOREIGN KEY (tr03_matric)
  REFERENCES iptubase;

  ALTER TABLE tributario.emissaogeralregistro
  ADD CONSTRAINT emissaogeralregistro_emissaogeral_fk FOREIGN KEY (tr02_emissaogeral)
  REFERENCES emissaogeral;

  ALTER TABLE tributario.emissaogeralregistro
  ADD CONSTRAINT emissaogeralregistro_numcgm_fk FOREIGN KEY (tr02_numcgm)
  REFERENCES cgm;
$$);

select fc_executa_ddl($$
  insert into db_sysarquivo
    values (3999, 'movimentoocorrenciacobrancaregistrada', 'Movimento da Ocorrência da Cobrança Registrada', 'k169', '2016-11-28', 'Movimento da Ocorrência da Cobrança Registrada', 0, 'f', 'f', 'f', 'f' );
  insert into db_sysarqmod values (5,3999);

  insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
    values ( 22204 ,'k169_sequencial' ,'int4' ,'Sequencial' ,'' ,'Sequencial' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Sequencial' ),
           ( 22205 ,'k169_codigo' ,'varchar(2)' ,'Código do Movimento' ,'' ,'Código do Movimento' ,2 ,'false' ,'true' ,'false' ,0 ,'text' ,'Código do Movimento' ),
           ( 22206 ,'k169_descricao' ,'varchar(500)' ,'Descrição' ,'' ,'Descrição' ,500 ,'false' ,'true' ,'false' ,0 ,'text' ,'Descrição' );

  insert into db_syssequencia values(1000627, 'movimentoocorrenciacobrancaregistrada_k169_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

  insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia )
    values ( 3999 ,22204 ,1 ,1000627 ),
           ( 3999 ,22205 ,2 ,0 ),
           ( 3999 ,22206 ,3 ,0 );

  insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3999,22204,1,22204);
$$);


select fc_executa_ddl($$
  insert into db_sysarquivo
    values (3997, 'ocorrenciacobrancaregistrada', 'Ocorrências do Retorno do Arquivo de Cobrança Registrada', 'k149', '2016-11-28', 'Ocorrência Cobranca Registrada', 0, 'f', 'f', 'f', 'f' );
  insert into db_sysarqmod values (5,3997);

  insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
    values ( 22196 ,'k149_sequencial' ,'int4' ,'Sequencial' ,'' ,'Sequencial' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Sequencial' ),
           ( 22197 ,'k149_banco' ,'varchar(10)' ,'Código do Banco' ,'' ,'Código do Banco' ,10 ,'false' ,'true' ,'false' ,0 ,'text' ,'Código do Banco' ),
           ( 22198 ,'k149_codigo' ,'varchar(2)' ,'Código da Ocorrência' ,'' ,'Código da Ocorrência' ,2 ,'false' ,'true' ,'false' ,0 ,'text' ,'Código da Ocorrência' ),
           ( 22199 ,'k149_descricao' ,'text' ,'Descrição' ,'' ,'Descrição da Ocorrência' ,1 ,'false' ,'false' ,'false' ,0 ,'text' ,'Descrição da Ocorrência' ),
           ( 22207 ,'k149_movimento' ,'int4' ,'Movimento' ,'' ,'Movimento' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Movimento' );

  insert into db_syssequencia values(1000625, 'ocorrenciacobrancaregistrada_k149_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

  insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia )
    values ( 3997 ,22196 ,1 ,1000625 ),
           ( 3997 ,22197 ,2 ,0 ),
           ( 3997 ,22198 ,3 ,0 ),
           ( 3997 ,22199 ,4 ,0 ),
           ( 3997 ,22207 ,5 ,0 );

  insert into db_sysprikey (codarq,codcam,sequen,camiden)
    values (3997,22196,1,22196);

  insert into db_sysforkey
    values (3997,22197,1,1185,0),
           (3997,22207,1,3999,0);
$$);

select fc_executa_ddl($$
  insert into db_sysarquivo
    values (3998, 'retornocobrancaregistrada', 'Retorno do Arquivo de Cobrança Registrada', 'k168', '2016-11-28', 'Retorno Cobrança Registrada', 0, 'f', 'f', 'f', 'f' );
  insert into db_sysarqmod values (5,3998);

  insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
    values ( 22200 ,'k168_sequencial' ,'int4' ,'Sequencial' ,'' ,'Sequencial' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Sequencial' ),
           ( 22201 ,'k168_numpre' ,'int4' ,'Numpre do Recibo' ,'' ,'Numpre' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Numpre' );

  insert into db_syssequencia values(1000626, 'retornocobrancaregistrada_k168_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

  insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia )
    values ( 3998 ,22200 ,1 ,1000626 ),
           ( 3998 ,22201 ,2 ,0 );

  insert into db_sysprikey (codarq,codcam,sequen,camiden)
    values (3998,22200,1,22200);
$$);

select fc_executa_ddl($$
  insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
    values ( 10338 ,'Retorno Cobrança Registrada' ,'Retorno Cobrança Registrada' ,'arr2_retornocobrancaregistrada001.php' ,'1' ,'1' ,'Relatório do retorno da cobrança registrada' ,'true' );
  insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo )
    values ( 30 ,10338 ,460 ,1985522 );
$$);

select fc_executa_ddl($$
  insert into db_sysarquivo
    values (4000, 'ocorrenciaretornocobrancaregistrada', 'Vínculo entre o retorno da cobrança registrada e a ocorrência', 'k170', '2016-11-29', 'Ocorrência Retorno Cobrança Registrada', 0, 'f', 'f', 'f', 'f' );
  insert into db_sysarqmod values (5,4000);

  insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
    values ( 22208 ,'k170_sequencial' ,'int4' ,'Sequêncial' ,'' ,'Sequêncial' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Sequêncial' ),
           ( 22209 ,'k170_retornocobrancaregistrada' ,'int4' ,'Retorno Cobrança Registrada' ,'' ,'Retorno Cobrança Registrada' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Retorno Cobrança Registrada' ),
           ( 22210 ,'k170_ocorrenciacobrancaregistrada' ,'int4' ,'Ocorrência Cobrança Registrada' ,'' ,'Ocorrência Cobrança Registrada' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Ocorrência Cobrança Registrada' );

  insert into db_syssequencia values(1000628, 'ocorrenciaretornocobrancaregistrada_k170_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

  insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia )
    values ( 4000 ,22208 ,1 ,1000628 ),
           ( 4000 ,22209 ,2 ,0 ),
           ( 4000 ,22210 ,3 ,0 );

  insert into db_sysprikey (codarq,codcam,sequen,camiden)
    values(4000,22208,1,22208);

  insert into db_sysforkey
    values (4000,22210,1,3997,0),
           (4000,22209,1,3998,0);
$$);


select fc_executa_ddl($$
  create sequence caixa.movimentoocorrenciacobrancaregistrada_k169_sequencial_seq
  increment 1
  minvalue 1
  maxvalue 9223372036854775807
  start 1
  cache 1;

  create table caixa.movimentoocorrenciacobrancaregistrada(
   k169_sequencial      int4 not null  default nextval('movimentoocorrenciacobrancaregistrada_k169_sequencial_seq'),
   k169_codigo      varchar(2) not null ,
   k169_descricao       varchar(500) ,
   constraint movimentoocorrenciacobrancaregistrada_sequ_pk primary key (k169_sequencial)
  );

  insert into movimentoocorrenciacobrancaregistrada (k169_sequencial, k169_codigo, k169_descricao)
    values (nextval('movimentoocorrenciacobrancaregistrada_k169_sequencial_seq'), '02', 'ENTRADA CONFIRMADA'),
           (nextval('movimentoocorrenciacobrancaregistrada_k169_sequencial_seq'), '03', 'ENTRADA REJEITADA'),
           (nextval('movimentoocorrenciacobrancaregistrada_k169_sequencial_seq'), '09', 'BAIXA');
$$);

select fc_executa_ddl($$
  create sequence caixa.ocorrenciacobrancaregistrada_k149_sequencial_seq
  increment 1
  minvalue 1
  maxvalue 9223372036854775807
  start 1
  cache 1;

  create table caixa.ocorrenciacobrancaregistrada(
    k149_sequencial     int4 not null  default nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'),
    k149_banco      varchar(10) not null ,
    k149_codigo     varchar(2) not null ,
    k149_descricao      text ,
    k149_movimento      int4 ,
    constraint ocorrenciacobrancaregistrada_sequ_pk primary key (k149_sequencial),
    constraint ocorrenciacobrancaregistrada_banco_fk foreign key (k149_banco) references db_bancos,
    constraint ocorrenciacobrancaregistrada_movimento_fk foreign key (k149_movimento) references movimentoocorrenciacobrancaregistrada
  );
  insert into ocorrenciacobrancaregistrada (k149_sequencial, k149_banco, k149_codigo, k149_descricao, k149_movimento)
    values
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '00', 'Entrada Confirmada', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '01', 'Código do Banco Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '02', 'Código do Registro Detalhe Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '03', 'Código do Segmento Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '04', 'Código de Movimento Não Permitido para Carteira', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '05', 'Código de Movimento Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '06', 'Tipo/Número de Inscrição do Cedente Inválidos', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '07', 'Agência/Conta/DV Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '08', 'Nosso Número Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '09', 'Nosso Número Duplicado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '10', 'Carteira Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '11', 'Forma de Cadastramento do Título Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '12', 'Tipo de Documento Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '13', 'Identificação da Emissão do Bloqueto Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '14', 'Identificação da Distribuição do Bloqueto Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '15', 'Características da Cobrança Incompatíveis', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '16', 'Data de Vencimento Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '17', 'Data de Vencimento Anterior a Data de Emissão', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '18', 'Vencimento Fora do Prazo de Operação', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '19', 'Título a Cargo de Bancos Correspondentes com Vencimento Inferior a XX Dias', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '20', 'Valor do Título Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '21', 'Espécie do Título Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '22', 'Espécie do Título Não Permitida para a Carteira', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '23', 'Aceite Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '24', 'Data da Emissão Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '25', 'Data da Emissão Posterior a Data de Entrada', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '26', 'Código de Juros de Mora Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '27', 'Valor/Taxa de Juros de Mora Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '28', 'Código do Desconto Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '29', 'Valor do Desconto Maior ou Igual ao Valor do Título', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '30', 'Desconto a Conceder Não Confere', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '31', 'Concessão de Desconto - Já Existe Desconto Anterior', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '32', 'Valor do IOF Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '33', 'Valor do Abatimento Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '34', 'Valor do Abatimento Maior ou Igual ao Valor do Título', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '35', 'Valor a Conceder Não Confere', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '36', 'Concessão de Abatimento - Já Existe Abatimento Anterior', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '37', 'Código para Protesto Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '38', 'Prazo para Protesto Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '39', 'Pedido de Protesto Não Permitido para o Título', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '40', 'Título com Ordem de Protesto Emitida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '41', 'Pedido de Cancelamento/Sustação para Títulos sem Instrução de Protesto', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '42', 'Código para Baixa/Devolução Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '43', 'Prazo para Baixa/Devolução Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '44', 'Código da Moeda Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '45', 'Nome do Sacado Não Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '46', 'Tipo/Número de Inscrição do Sacado Inválidos', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '47', 'Endereço do Sacado Não Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '48', 'CEP Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '49', 'CEP Sem Praça de Cobrança (Não Localizado)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '50', 'CEP Referente a um Banco Correspondente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '51', 'CEP incompatível com a Unidade da Federação', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '52', 'Unidade da Federação Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '53', 'Tipo/Número de Inscrição do Sacador/Avalista Inválidos', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '54', 'Sacador/Avalista Não Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '55', 'Nosso número no Banco Correspondente Não Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '56', 'Código do Banco Correspondente Não Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '57', 'Código da Multa Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '58', 'Data da Multa Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '59', 'Valor/Percentual da Multa Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '60', 'Movimento para Título Não Cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '61', 'Alteração da Agência Cobradora/DV Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '62', 'Tipo de Impressão Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '63', 'Entrada para Título já Cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '64', 'Número da Linha Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '65', 'Código do Banco para Débito Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '66', 'Agência/Conta/DV para Débito Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '67', 'Dados para Débito incompatível com a Identificação da Emissão do Bloqueto', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '68', 'Débito Automático Agendado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '69', 'Débito Não Agendado - Erro nos Dados da Remessa', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '70', 'Débito Não Agendado - Sacado Não Consta do Cadastro de Autorizante', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '71', 'Débito Não Agendado - Cedente Não Autorizado pelo Sacado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '72', 'Débito Não Agendado - Cedente Não Participa da Modalidade Débito Automático', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '73', 'Débito Não Agendado - Código de Moeda Diferente de Real (R$)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '74', 'Débito Não Agendado - Data Vencimento Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '75', 'Débito Não Agendado, Conforme seu Pedido, Título Não Registrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '76', 'Débito Não Agendado, Tipo/Num. Inscrição do Debitado, Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '77', 'Transferência para Desconto Não Permitida para a Carteira do Título', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '78', 'Data Inferior ou Igual ao Vencimento para Débito Automático', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '79', 'Data Juros de Mora Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '80', 'Data do Desconto Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '81', 'Tentativas de Débito Esgotadas - Baixado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '82', 'Tentativas de Débito Esgotadas - Pendente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '83', 'Limite Excedido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '84', 'Número Autorização Inexistente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '85', 'Título com Pagamento Vinculado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '86', 'Seu Número Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '87', 'e-mail/SMS enviado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '88', 'e-mail Lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '89', 'e-mail/SMS devolvido - endereço de e-mail ou número do celular incorreto', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '90', 'e-mail devolvido - caixa postal cheia', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '91', 'e-mail/número do celular do sacado não informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '92', 'Sacado optante por Bloqueto Eletrônico - e-mail não enviado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '93', 'Código para emissão de bloqueto não permite envio de e-mail', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '94', 'Código da Carteira inválido para envio e-mail.', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '95', 'Contrato não permite o envio de e-mail', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '96', 'Número de contrato inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '97', 'Rejeição da alteração do prazo limite de recebimento (a data deve ser informada no campo 28.3.p)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '98', 'Rejeição de dispensa de prazo limite de recebimento', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '99', 'Rejeição da alteração do número do título dado pelo cedente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A1', 'Rejeição da alteração do número controle do participante', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A2', 'Rejeição da alteração dos dados do sacado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A3', 'Rejeição da alteração dos dados do sacador/avalista', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A4', 'Sacado DDA', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A5', 'Registro Rejeitado - Título já Liquidado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A6', 'Código do Convenente Inválido ou Encerrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A7', 'Título já se encontra na situação Pretendida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A8', 'Valor do Abatimento inválido para cancelamento', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A9', 'Não autoriza pagamento parcial', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'B1', 'Autoriza recebimento parcial', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02'));

  insert into ocorrenciacobrancaregistrada (k149_sequencial, k149_banco, k149_codigo, k149_descricao, k149_movimento)
    values
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '01', 'Código do Banco Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '02', 'Código do Registro Detalhe Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '03', 'Código do Segmento Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '04', 'Código de Movimento Não Permitido para Carteira', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '05', 'Código de Movimento Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '06', 'Tipo/Número de Inscrição do Cedente Inválidos', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '07', 'Agência/Conta/DV Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '08', 'Nosso Número Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '09', 'Nosso Número Duplicado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '10', 'Carteira Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '11', 'Forma de Cadastramento do Título Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '12', 'Tipo de Documento Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '13', 'Identificação da Emissão do Bloqueto Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '14', 'Identificação da Distribuição do Bloqueto Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '15', 'Características da Cobrança Incompatíveis', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '16', 'Data de Vencimento Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '17', 'Data de Vencimento Anterior a Data de Emissão', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '18', 'Vencimento Fora do Prazo de Operação', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '19', 'Título a Cargo de Bancos Correspondentes com Vencimento Inferior a XX Dias', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '20', 'Valor do Título Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '21', 'Espécie do Título Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '22', 'Espécie do Título Não Permitida para a Carteira', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '23', 'Aceite Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '24', 'Data da Emissão Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '25', 'Data da Emissão Posterior a Data de Entrada', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '26', 'Código de Juros de Mora Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '27', 'Valor/Taxa de Juros de Mora Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '28', 'Código do Desconto Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '29', 'Valor do Desconto Maior ou Igual ao Valor do Título', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '30', 'Desconto a Conceder Não Confere', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '31', 'Concessão de Desconto - Já Existe Desconto Anterior', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '32', 'Valor do IOF Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '33', 'Valor do Abatimento Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '34', 'Valor do Abatimento Maior ou Igual ao Valor do Título', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '35', 'Valor a Conceder Não Confere', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '36', 'Concessão de Abatimento - Já Existe Abatimento Anterior', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '37', 'Código para Protesto Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '38', 'Prazo para Protesto Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '39', 'Pedido de Protesto Não Permitido para o Título', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '40', 'Título com Ordem de Protesto Emitida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '41', 'Pedido de Cancelamento/Sustação para Títulos sem Instrução de Protesto', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '42', 'Código para Baixa/Devolução Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '43', 'Prazo para Baixa/Devolução Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '44', 'Código da Moeda Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '45', 'Nome do Sacado Não Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '46', 'Tipo/Número de Inscrição do Sacado Inválidos', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '47', 'Endereço do Sacado Não Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '48', 'CEP Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '49', 'CEP Sem Praça de Cobrança (Não Localizado)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '50', 'CEP Referente a um Banco Correspondente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '51', 'CEP incompatível com a Unidade da Federação', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '52', 'Unidade da Federação Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '53', 'Tipo/Número de Inscrição do Sacador/Avalista Inválidos', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '54', 'Sacador/Avalista Não Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '55', 'Nosso número no Banco Correspondente Não Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '56', 'Código do Banco Correspondente Não Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '57', 'Código da Multa Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '58', 'Data da Multa Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '59', 'Valor/Percentual da Multa Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '60', 'Movimento para Título Não Cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '61', 'Alteração da Agência Cobradora/DV Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '62', 'Tipo de Impressão Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '63', 'Entrada para Título já Cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '64', 'Número da Linha Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '65', 'Código do Banco para Débito Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '66', 'Agência/Conta/DV para Débito Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '67', 'Dados para Débito incompatível com a Identificação da Emissão do Bloqueto', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '68', 'Débito Automático Agendado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '69', 'Débito Não Agendado - Erro nos Dados da Remessa', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '70', 'Débito Não Agendado - Sacado Não Consta do Cadastro de Autorizante', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '71', 'Débito Não Agendado - Cedente Não Autorizado pelo Sacado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '72', 'Débito Não Agendado - Cedente Não Participa da Modalidade Débito Automático', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '73', 'Débito Não Agendado - Código de Moeda Diferente de Real (R$)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '74', 'Débito Não Agendado - Data Vencimento Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '75', 'Débito Não Agendado, Conforme seu Pedido, Título Não Registrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '76', 'Débito Não Agendado, Tipo/Num. Inscrição do Debitado, Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '77', 'Transferência para Desconto Não Permitida para a Carteira do Título', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '78', 'Data Inferior ou Igual ao Vencimento para Débito Automático', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '79', 'Data Juros de Mora Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '80', 'Data do Desconto Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '81', 'Tentativas de Débito Esgotadas - Baixado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '82', 'Tentativas de Débito Esgotadas - Pendente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '83', 'Limite Excedido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '84', 'Número Autorização Inexistente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '85', 'Título com Pagamento Vinculado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '86', 'Seu Número Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '87', 'e-mail/SMS enviado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '88', 'e-mail Lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '89', 'e-mail/SMS devolvido - endereço de e-mail ou número do celular incorreto', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '90', 'e-mail devolvido - caixa postal cheia', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '91', 'e-mail/número do celular do sacado não informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '92', 'Sacado optante por Bloqueto Eletrônico - e-mail não enviado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '93', 'Código para emissão de bloqueto não permite envio de e-mail', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '94', 'Código da Carteira inválido para envio e-mail.', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '95', 'Contrato não permite o envio de e-mail', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '96', 'Número de contrato inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '97', 'Rejeição da alteração do prazo limite de recebimento (a data deve ser informada no campo 28.3.p)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '98', 'Rejeição de dispensa de prazo limite de recebimento', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '99', 'Rejeição da alteração do número do título dado pelo cedente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A1', 'Rejeição da alteração do número controle do participante', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A2', 'Rejeição da alteração dos dados do sacado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A3', 'Rejeição da alteração dos dados do sacador/avalista', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A4', 'Sacado DDA', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A5', 'Registro Rejeitado - Título já Liquidado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A6', 'Código do Convenente Inválido ou Encerrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A7', 'Título já se encontra na situação Pretendida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A8', 'Valor do Abatimento inválido para cancelamento', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A9', 'Não autoriza pagamento parcial', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'B1', 'Autoriza recebimento parcial', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03'));

  insert into ocorrenciacobrancaregistrada (k149_sequencial, k149_banco, k149_codigo, k149_descricao, k149_movimento)
    values
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '09', 'Comandada Banco', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '09')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '10', 'Comandada Cliente Arquivo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '09')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '11', 'Comandada Cliente On-line', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '09')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '12', 'Decurso Prazo - Cliente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '09')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '13', 'Decurso Prazo - Banco', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '09')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '14', 'Protestado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '09')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '15', 'Título Excluído', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '09'));

  -- Caixa
  insert into ocorrenciacobrancaregistrada (k149_sequencial, k149_banco, k149_codigo, k149_descricao, k149_movimento)
    values
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '00', 'Entrada Confirmada', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AA', 'Cód Desconto Preenchido, Obrig Data e Valor/Perc', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AB', 'Cod Desconto Obrigatório p/ Cód Mov = 7', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AC', 'Forma de Cadastramento Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AD', 'Data de Desconto deve estar em Ordem Crescente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AE', 'Data de Desconto é Posterior a Data de Vencimento', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AF', 'Título não está com situação \'Em Aberto\'', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AG', 'Título já está Vencido / Vencendo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AH', 'Não existe desconto a ser cancelado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AI', 'Data solicitada p/ Prot/Dev é anterior a data atual', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AJ', 'Código do Sacado Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AK', 'Número da Parcela Invalida ou Fora de Sequencia', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AL', 'Estorno de Envio Não Permitido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AM', 'Nosso Numero Fora de Sequencia', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VA', 'Arq.Ret.Inexis. P/ Redisp. Nesta Dt/Nro', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VB', 'Registro Duplicado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VC', 'Cedente deve ser padrão CNAB240', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VD', 'Ident. Banco Sacado Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VE', 'Num Docto Cobr Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VF', 'Vlr/Perc a ser concedido inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VG', 'Data de Inscrição Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VH', 'Data Movto Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VI', 'Data Inicial Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VJ', 'Data Final Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VK', 'Banco de Sacado já cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VL', 'Cedente não cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VM', 'Número de Lote Duplicado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VN', 'Forma de Emissão de Bloqueto Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VO', 'Forma Entrega Bloqueto Inválida p/ Emissão via Banco', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VP', 'Forma Entrega Bloqueto Invalida p/ Emissão via Cedente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VQ', 'Opção para Endosso Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VR', 'Tipo de Juros ao Mês Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VS', 'Percentual de Juros ao Mês Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VT', 'Percentual / Valor de Desconto Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VU', 'Prazo de Desconto Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VV', 'Preencher Somente Percentual ou Valor', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VW', 'Prazo de Multa Invalido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VX', 'Perc. Desconto tem que estar em ordem decrescente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VY', 'Valor Desconto tem que estar em ordem descrescente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VZ', 'Dias/Data desconto tem que estar em ordem decrescente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WA', 'Vlr Contr p/ aquisição de Bens Inválid', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WB', 'Vlr Contr p/ Fundo de Reserva Inválid', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WC', 'Vlr Rend. Aplicações Financ Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WD', 'Valor Multa/Juros Monetarios Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WE', 'Valor Premios de Seguro Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WF', 'Valor Custas Judiciais Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WG', 'Valor Reembolso de Despesas Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WH', 'Valor Outros Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WI', 'Valor de Aquisição de Bens Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WJ', 'Valor Devolvido ao Consorciado Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WK', 'Vlr Desp. Registro de Contrato Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WL', 'Valor de Rendimentos Pagos Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WM', 'Data de Descrição Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WN', 'Valor do Seguro Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WO', 'Data de Vencimento Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WP', 'Data de Nascimento Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WQ', 'CPF/CNPJ do Aluno Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WR', 'Data de Avaliação Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WS', 'CPF/CNPJ do Locatario Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WT', 'Literal da Remessa Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WU', 'Tipo de Registro Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WV', 'Modelo Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WW', 'Código do Banco de Sacados Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WX', 'Banco de Sacados não Cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WY', 'Qtde dias para Protesto tem que estar entre 2 e 90', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WZ', 'Não existem Sacados para este Banco', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XA', 'Preço Unitario do Produto Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XB', 'Preço Total do Produto Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XC', 'Valor Atual do Bem Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XD', 'Quantidade de Bens Entregues Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XE', 'Quantidade de Bens Distribuidos Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XF', 'Quantidade de Bens não Distribuidos Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XG', 'Número da Próxima Assembléia Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XH', 'Horario da Próxima Assembléia Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XI', 'Data da Próxima Assembléia Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XJ', 'Número de Ativos Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XK', 'Número de Desistentes Excluidos Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XL', 'Número de Quitados Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XM', 'Número de Contemplados Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XN', 'Número de não Contemplados Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XO', 'Data da Última Assembléia Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XP', 'Quantidade de Prestações Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XQ', 'Data de Vencimento da Parcela Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XR', 'Valor da Amortização Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XS', 'Código do Personalizado Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XT', 'Valor da Contribuição Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XU', 'Percentual da Contribuição Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XV', 'Valor do Fundo de Reserva Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XW', 'Número Parcela Inválido ou Fora de Sequência', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XX', 'Percentual Fundo de Reserva Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XY', 'Prz Desc/Multa Preenchido, Obrigat.Perc. ou Valor', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XZ', 'Valor Taxa de Administração Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YA', 'Data de Juros Inválida ou Não Informada', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YB', 'Data Desconto Inválida ou Não Informada', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YC', 'E-mail Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YD', 'Código de Ocorrência Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YE', 'Sacado já Cadastrado (Banco de Sacados)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YF', 'Sacado não Cadastrado (Banco de Sacados)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YG', 'Remessa Sem Registro Tipo 9', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YH', 'Identificação da Solicitação Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YI', 'Quantidade Bloquetos Solicitada Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YJ', 'Trailler do Arquivo não Encontrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YK', 'Tipo Inscrição do Responsable Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YL', 'Número Inscrição do Responsable Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YM', 'Ajuste de Vencimento Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YN', 'Ajuste de Emissão Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YO', 'Código de Modelo Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YP', 'Vía de Entrega Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YQ', 'Espécie Banco de Sacado Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YR', 'Aceite Banco de Sacado Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YS', 'Sacado já Cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YT', 'Sacado não Cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YU', 'Número do Telefone Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YV', 'CNPJ do Condomínio Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YW', 'Indicador de Registro de Título Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YX', 'Valor da Nota Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YY', 'Qtde de dias para Devolução tem que estar entre 5 e 120', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YZ', 'Quantidade de Produtos Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZA', 'Perc. Taxa de Administração Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZB', 'Valor do Seguro Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZC', 'Percentual do Seguro Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZD', 'Valor da Diferença da Parcela Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZE', 'Perc. Da Diferença da Parcela Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZF', 'Valor Reajuste do Saldo de Caixa Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZG', 'Perc. Reajuste do Saldo de Caixa Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZH', 'Valor Total a Pagar Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZI', 'Percentual ao Total a Pagar Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZJ', 'Valor de Outros Acréscimos Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZK', 'Perc. De Outros Acréscimos Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZL', 'Valor de Outras Deduções Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZM', 'Perc. De Outras Deduções Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZN', 'Valor da Contribuição Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZO', 'Percentual da Contribuição Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZP', 'Valor de Juros/Multa Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZQ', 'Percentual de Juros/Multa Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZR', 'Valor Cobrado Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZS', 'Percentual Cobrado Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZT', 'Valor Disponibilizado em Caixa Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZU', 'Valor Depósito Bancario Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZV', 'Valor Aplicações Financieras Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZW', 'Data/Valor Preenchidos, Obrigatório Dódigo Desconto', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZX', 'Valor Cheques em Cobrança Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZY', 'Desconto c/ valor Fixo, Obrigatório Valor do Título', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZZ', 'Código Movimento Inválido p/ Segmento Y8', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '01', 'Código do Banco Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '02', 'Código do Registro Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '03', 'Código do Segmento Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '04', 'Código do Movimento não Permitido p/ Carteira', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '05', 'Código do Movimento Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '06', 'Tipo Número Inscrição Cedente Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '07', 'Agencia/Conta/DV Inválidos', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '08', 'Nosso Número Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '09', 'Nosso Número Duplicado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '10', 'Carteira Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '11', 'Data de Geração Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '12', 'Tipo de Documento Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '13', 'Identif. Da Emissão do Bloqueto Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '14', 'Identif. Da Distribuição do Bloqueto Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '15', 'Características Cobrança Incompatíveis', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '16', 'Data de Vencimento Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '17', 'Data de Vencimento Anterior a Data de Emissão', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '18', 'Vencimento fora do prazo de operação', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '19', 'Título a Cargo de Bco Correspondentes c/ Vencto Inferior a XX Dias', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '20', 'Valor do Título Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '21', 'Espécie do Título Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '22', 'Espécie do Título Não Permitida para a Carteira', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '23', 'Aceite Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '24', 'Data da Emissão Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '25', 'Data da Emissão Posterior a Data de Entrada', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '26', 'Código de Juros de Mora Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '27', 'Valor/Taxa de Juros de Mora Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '28', 'Código do Desconto Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '29', 'Valor do Desconto Maior ou Igual ao Valor do Título', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '30', 'Desconto a Conceder Não Confere', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '31', 'Concessão de Desconto - Já Existe Desconto Anterior', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '32', 'Valor do IOF Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '33', 'Valor do Abatimento Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '34', 'Valor do Abatimento Maior ou Igual ao Valor do Título', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '35', 'Valor Abatimento a Conceder Não Confere', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '36', 'Concessão de Abatimento - Já Existe Abatimento Anterior', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '37', 'Código para Protesto Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '38', 'Prazo para Protesto Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '39', 'Pedido de Protesto Não Permitido para o Título', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '40', 'Título com Ordem de Protesto Emitida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '41', 'Pedido Cancelamento/Sustação p/ Títulos sem Instrução Protesto', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '42', 'Código para Baixa/Devolução Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '43', 'Prazo para Baixa/Devolução Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '44', 'Código da Moeda Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '45', 'Nome do Sacado Não Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '46', 'Tipo/Número de Inscrição do Sacado Inválidos', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '47', 'Endereço do Sacado Não Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '48', 'CEP Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '49', 'CEP Sem Praça de Cobrança (Não Localizado)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '50', 'CEP Referente a um Banco Correspondente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '51', 'CEP incompatível com a Unidade da Federação', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '52', 'Unidade da Federação Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '53', 'Tipo/Número de Inscrição do Sacador/Avalista Inválidos', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '54', 'Sacador/Avalista Não Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '55', 'Nosso número no Banco Correspondente Não Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '56', 'Código do Banco Correspondente Não Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '57', 'Código da Multa Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '58', 'Data da Multa Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '59', 'Valor/Percentual da Multa Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '60', 'Movimento para Título Não Cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '61', 'Alteração da Agência Cobradora/DV Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '62', 'Tipo de Impressão Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '63', 'Entrada para Título já Cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '64', 'Entrada Inválida para Cobrança Caucionada', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '65', 'CEP do Sacado não encontrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '66', 'Agencia Cobradora não encontrada', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '67', 'Agencia Cedente não encontrada', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '68', 'Movimentação inválida para título', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '69', 'Alteração de dados inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '70', 'Apelido do cliente não cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '71', 'Erro na composição do arquivo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '72', 'Lote de serviço inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '73', 'Código do Cedente inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '74', 'Cedente não pertencente a Cobrança Eletrônica', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '75', 'Nome da Empresa inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '76', 'Nome do Banco inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '77', 'Código da Remessa inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '78', 'Data/Hora Geração do arquivo inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '79', 'Número Sequencial do arquivo inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '80', 'Versão do Lay out do arquivo inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '81', 'Literal REMESSA-TESTE - Válido só p/ fase testes', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '82', 'Literal REMESSA-TESTE - Obrigatório p/ fase testes', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '83', 'Tp Número Inscrição Empresa inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '84', 'Tipo de Operação inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '85', 'Tipo de serviço inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '86', 'Forma de lançamento inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '87', 'Número da remessa inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '88', 'Número da remessa menor/igual remessa anterior', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '89', 'Lote de serviço divergente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '90', 'Número sequencial do registro inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '91', 'Erro seq de segmento do registro detalhe', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '92', 'Cod movto divergente entre grupo de segm', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '93', 'Qtde registros no lote inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '94', 'Qtde registros no lote divergente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '95', 'Qtde lotes no arquivo inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '96', 'Qtde lotes no arquivo divergente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '97', 'Qtde registros no arquivo inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '98', 'Qtde registros no arquivo divergente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '99', 'Código de DDD inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02'));

  insert into ocorrenciacobrancaregistrada (k149_sequencial, k149_banco, k149_codigo, k149_descricao, k149_movimento)
    values
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AA', 'Cód Desconto Preenchido, Obrig Data e Valor/Perc', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AB', 'Cod Desconto Obrigatório p/ Cód Mov = 7', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AC', 'Forma de Cadastramento Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AD', 'Data de Desconto deve estar em Ordem Crescente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AE', 'Data de Desconto é Posterior a Data de Vencimento', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AF', 'Título não está com situação \'Em Aberto\'', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AG', 'Título já está Vencido / Vencendo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AH', 'Não existe desconto a ser cancelado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AI', 'Data solicitada p/ Prot/Dev é anterior a data atual', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AJ', 'Código do Sacado Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AK', 'Número da Parcela Invalida ou Fora de Sequencia', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AL', 'Estorno de Envio Não Permitido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AM', 'Nosso Numero Fora de Sequencia', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VA', 'Arq.Ret.Inexis. P/ Redisp. Nesta Dt/Nro', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VB', 'Registro Duplicado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VC', 'Cedente deve ser padrão CNAB240', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VD', 'Ident. Banco Sacado Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VE', 'Num Docto Cobr Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VF', 'Vlr/Perc a ser concedido inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VG', 'Data de Inscrição Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VH', 'Data Movto Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VI', 'Data Inicial Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VJ', 'Data Final Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VK', 'Banco de Sacado já cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VL', 'Cedente não cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VM', 'Número de Lote Duplicado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VN', 'Forma de Emissão de Bloqueto Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VO', 'Forma Entrega Bloqueto Inválida p/ Emissão via Banco', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VP', 'Forma Entrega Bloqueto Invalida p/ Emissão via Cedente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VQ', 'Opção para Endosso Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VR', 'Tipo de Juros ao Mês Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VS', 'Percentual de Juros ao Mês Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VT', 'Percentual / Valor de Desconto Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VU', 'Prazo de Desconto Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VV', 'Preencher Somente Percentual ou Valor', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VW', 'Prazo de Multa Invalido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VX', 'Perc. Desconto tem que estar em ordem decrescente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VY', 'Valor Desconto tem que estar em ordem descrescente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VZ', 'Dias/Data desconto tem que estar em ordem decrescente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WA', 'Vlr Contr p/ aquisição de Bens Inválid', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WB', 'Vlr Contr p/ Fundo de Reserva Inválid', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WC', 'Vlr Rend. Aplicações Financ Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WD', 'Valor Multa/Juros Monetarios Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WE', 'Valor Premios de Seguro Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WF', 'Valor Custas Judiciais Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WG', 'Valor Reembolso de Despesas Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WH', 'Valor Outros Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WI', 'Valor de Aquisição de Bens Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WJ', 'Valor Devolvido ao Consorciado Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WK', 'Vlr Desp. Registro de Contrato Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WL', 'Valor de Rendimentos Pagos Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WM', 'Data de Descrição Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WN', 'Valor do Seguro Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WO', 'Data de Vencimento Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WP', 'Data de Nascimento Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WQ', 'CPF/CNPJ do Aluno Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WR', 'Data de Avaliação Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WS', 'CPF/CNPJ do Locatario Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WT', 'Literal da Remessa Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WU', 'Tipo de Registro Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WV', 'Modelo Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WW', 'Código do Banco de Sacados Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WX', 'Banco de Sacados não Cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WY', 'Qtde dias para Protesto tem que estar entre 2 e 90', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WZ', 'Não existem Sacados para este Banco', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XA', 'Preço Unitario do Produto Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XB', 'Preço Total do Produto Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XC', 'Valor Atual do Bem Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XD', 'Quantidade de Bens Entregues Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XE', 'Quantidade de Bens Distribuidos Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XF', 'Quantidade de Bens não Distribuidos Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XG', 'Número da Próxima Assembléia Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XH', 'Horario da Próxima Assembléia Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XI', 'Data da Próxima Assembléia Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XJ', 'Número de Ativos Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XK', 'Número de Desistentes Excluidos Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XL', 'Número de Quitados Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XM', 'Número de Contemplados Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XN', 'Número de não Contemplados Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XO', 'Data da Última Assembléia Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XP', 'Quantidade de Prestações Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XQ', 'Data de Vencimento da Parcela Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XR', 'Valor da Amortização Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XS', 'Código do Personalizado Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XT', 'Valor da Contribuição Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XU', 'Percentual da Contribuição Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XV', 'Valor do Fundo de Reserva Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XW', 'Número Parcela Inválido ou Fora de Sequência', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XX', 'Percentual Fundo de Reserva Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XY', 'Prz Desc/Multa Preenchido, Obrigat.Perc. ou Valor', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XZ', 'Valor Taxa de Administração Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YA', 'Data de Juros Inválida ou Não Informada', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YB', 'Data Desconto Inválida ou Não Informada', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YC', 'E-mail Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YD', 'Código de Ocorrência Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YE', 'Sacado já Cadastrado (Banco de Sacados)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YF', 'Sacado não Cadastrado (Banco de Sacados)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YG', 'Remessa Sem Registro Tipo 9', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YH', 'Identificação da Solicitação Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YI', 'Quantidade Bloquetos Solicitada Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YJ', 'Trailler do Arquivo não Encontrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YK', 'Tipo Inscrição do Responsable Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YL', 'Número Inscrição do Responsable Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YM', 'Ajuste de Vencimento Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YN', 'Ajuste de Emissão Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YO', 'Código de Modelo Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YP', 'Vía de Entrega Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YQ', 'Espécie Banco de Sacado Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YR', 'Aceite Banco de Sacado Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YS', 'Sacado já Cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YT', 'Sacado não Cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YU', 'Número do Telefone Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YV', 'CNPJ do Condomínio Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YW', 'Indicador de Registro de Título Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YX', 'Valor da Nota Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YY', 'Qtde de dias para Devolução tem que estar entre 5 e 120', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YZ', 'Quantidade de Produtos Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZA', 'Perc. Taxa de Administração Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZB', 'Valor do Seguro Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZC', 'Percentual do Seguro Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZD', 'Valor da Diferença da Parcela Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZE', 'Perc. Da Diferença da Parcela Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZF', 'Valor Reajuste do Saldo de Caixa Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZG', 'Perc. Reajuste do Saldo de Caixa Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZH', 'Valor Total a Pagar Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZI', 'Percentual ao Total a Pagar Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZJ', 'Valor de Outros Acréscimos Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZK', 'Perc. De Outros Acréscimos Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZL', 'Valor de Outras Deduções Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZM', 'Perc. De Outras Deduções Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZN', 'Valor da Contribuição Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZO', 'Percentual da Contribuição Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZP', 'Valor de Juros/Multa Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZQ', 'Percentual de Juros/Multa Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZR', 'Valor Cobrado Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZS', 'Percentual Cobrado Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZT', 'Valor Disponibilizado em Caixa Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZU', 'Valor Depósito Bancario Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZV', 'Valor Aplicações Financieras Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZW', 'Data/Valor Preenchidos, Obrigatório Dódigo Desconto', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZX', 'Valor Cheques em Cobrança Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZY', 'Desconto c/ valor Fixo, Obrigatório Valor do Título', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZZ', 'Código Movimento Inválido p/ Segmento Y8', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '01', 'Código do Banco Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '02', 'Código do Registro Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '03', 'Código do Segmento Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '04', 'Código do Movimento não Permitido p/ Carteira', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '05', 'Código do Movimento Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '06', 'Tipo Número Inscrição Cedente Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '07', 'Agencia/Conta/DV Inválidos', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '08', 'Nosso Número Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '09', 'Nosso Número Duplicado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '10', 'Carteira Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '11', 'Data de Geração Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '12', 'Tipo de Documento Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '13', 'Identif. Da Emissão do Bloqueto Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '14', 'Identif. Da Distribuição do Bloqueto Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '15', 'Características Cobrança Incompatíveis', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '16', 'Data de Vencimento Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '17', 'Data de Vencimento Anterior a Data de Emissão', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '18', 'Vencimento fora do prazo de operação', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '19', 'Título a Cargo de Bco Correspondentes c/ Vencto Inferior a XX Dias', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '20', 'Valor do Título Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '21', 'Espécie do Título Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '22', 'Espécie do Título Não Permitida para a Carteira', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '23', 'Aceite Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '24', 'Data da Emissão Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '25', 'Data da Emissão Posterior a Data de Entrada', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '26', 'Código de Juros de Mora Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '27', 'Valor/Taxa de Juros de Mora Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '28', 'Código do Desconto Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '29', 'Valor do Desconto Maior ou Igual ao Valor do Título', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '30', 'Desconto a Conceder Não Confere', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '31', 'Concessão de Desconto - Já Existe Desconto Anterior', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '32', 'Valor do IOF Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '33', 'Valor do Abatimento Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '34', 'Valor do Abatimento Maior ou Igual ao Valor do Título', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '35', 'Valor Abatimento a Conceder Não Confere', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '36', 'Concessão de Abatimento - Já Existe Abatimento Anterior', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '37', 'Código para Protesto Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '38', 'Prazo para Protesto Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '39', 'Pedido de Protesto Não Permitido para o Título', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '40', 'Título com Ordem de Protesto Emitida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '41', 'Pedido Cancelamento/Sustação p/ Títulos sem Instrução Protesto', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '42', 'Código para Baixa/Devolução Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '43', 'Prazo para Baixa/Devolução Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '44', 'Código da Moeda Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '45', 'Nome do Sacado Não Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '46', 'Tipo/Número de Inscrição do Sacado Inválidos', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '47', 'Endereço do Sacado Não Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '48', 'CEP Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '49', 'CEP Sem Praça de Cobrança (Não Localizado)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '50', 'CEP Referente a um Banco Correspondente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '51', 'CEP incompatível com a Unidade da Federação', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '52', 'Unidade da Federação Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '53', 'Tipo/Número de Inscrição do Sacador/Avalista Inválidos', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '54', 'Sacador/Avalista Não Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '55', 'Nosso número no Banco Correspondente Não Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '56', 'Código do Banco Correspondente Não Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '57', 'Código da Multa Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '58', 'Data da Multa Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '59', 'Valor/Percentual da Multa Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '60', 'Movimento para Título Não Cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '61', 'Alteração da Agência Cobradora/DV Inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '62', 'Tipo de Impressão Inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '63', 'Entrada para Título já Cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '64', 'Entrada Inválida para Cobrança Caucionada', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '65', 'CEP do Sacado não encontrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '66', 'Agencia Cobradora não encontrada', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '67', 'Agencia Cedente não encontrada', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '68', 'Movimentação inválida para título', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '69', 'Alteração de dados inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '70', 'Apelido do cliente não cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '71', 'Erro na composição do arquivo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '72', 'Lote de serviço inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '73', 'Código do Cedente inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '74', 'Cedente não pertencente a Cobrança Eletrônica', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '75', 'Nome da Empresa inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '76', 'Nome do Banco inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '77', 'Código da Remessa inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '78', 'Data/Hora Geração do arquivo inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '79', 'Número Sequencial do arquivo inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '80', 'Versão do Lay out do arquivo inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '81', 'Literal REMESSA-TESTE - Válido só p/ fase testes', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '82', 'Literal REMESSA-TESTE - Obrigatório p/ fase testes', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '83', 'Tp Número Inscrição Empresa inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '84', 'Tipo de Operação inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '85', 'Tipo de serviço inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '86', 'Forma de lançamento inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '87', 'Número da remessa inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '88', 'Número da remessa menor/igual remessa anterior', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '89', 'Lote de serviço divergente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '90', 'Número sequencial do registro inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '91', 'Erro seq de segmento do registro detalhe', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '92', 'Cod movto divergente entre grupo de segm', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '93', 'Qtde registros no lote inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '94', 'Qtde registros no lote divergente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '95', 'Qtde lotes no arquivo inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '96', 'Qtde lotes no arquivo divergente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '97', 'Qtde registros no arquivo inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '98', 'Qtde registros no arquivo divergente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '99', 'Código de DDD inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03'));

  insert into ocorrenciacobrancaregistrada (k149_sequencial, k149_banco, k149_codigo, k149_descricao, k149_movimento)
    values
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '09', 'Comandada Banco', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '09')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '10', 'Comandada Cliente via Arquivo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '09')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '11', 'Comandada Cliente On-line', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '09')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '12', 'Decurso Prazo - Cliente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '09')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '13', 'Decurso Prazo - Banco', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '09')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '14', 'Protestado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '09'));

  -- Banrisul
  insert into ocorrenciacobrancaregistrada (k149_sequencial, k149_banco, k149_codigo, k149_descricao, k149_movimento)
    values
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '00', 'Entrada Confirmada', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', 'A4', 'Pagador DDA', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02'));

  insert into ocorrenciacobrancaregistrada (k149_sequencial, k149_banco, k149_codigo, k149_descricao, k149_movimento)
    values
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '09', 'Comandado Banco', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '09')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '10', 'Comandado cliente Arquivo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '09')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '11', 'Comandado cliente On-Line', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '09')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '12', 'Decurso prazo - cliente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '09')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', 'AA', 'Baixa por Pagamento', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '09'));

  insert into ocorrenciacobrancaregistrada (k149_sequencial, k149_banco, k149_codigo, k149_descricao, k149_movimento)
    values
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '01', 'Código do Banco inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '02', 'Código de registro detalhe inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '03', 'Código do Segmento inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '04', 'Código do movimento não permitido para a carteira', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '05', 'Código do movimento inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '06', 'Tipo/Número de inscrição do Beneficiário inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '07', 'Agência/conta/DV inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '08', 'Nosso Número inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '09', 'Nosso número duplicado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '10', 'Carteira inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '11', 'Forma de cadastramento do título inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '12', 'Tipo de documento inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '13', 'Identificação da emissão do bloqueto inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '14', 'Identificação da distribuição do bloqueto inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '15', 'Características da cobrança incompatíveis - se a carteira e a moeda forem válidas e não existir espécie para combinação carteira/moeda:\nEspécie inválida\nInstrução inválida\nSem cadastro de espécie (8355, 8251, 8150, 8352)\nData de registro inválida\nIntervalo entre as datas de registro e processamento é maior que o definido pelo sistema\nCódigo de instrução duplo\nPraça inválida\nCobradora inválida\nCidade inválida\nAgência ou NC da Agência do Beneficiário inválido\nEspécie inválida para o CEP (CUBRS para CEP de outro estado)\nValor, data, taxa ou código de instrução inválido\nBeneficiário não cadastrado\nOperação bloqueada para p/a espécie\nValor do título não confere para devolução/baixa\nAlterações não permitidas para o título\nAlterações não permitidas para título em cartório\nBloqueio Administrativo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '16', 'Data de vencimento inválida:\nVerifica se a data é numérica, diferente de zeros e em formato válido (ddmmaaaa).\nVerifica se a alteração de vencimento é permitida para o título (cartório, carteira)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '17', 'Data de vencimento anterior a data de emissão', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '18', 'Vencimento fora do prazo de operação', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '19', 'Título a cargo de Bancos Correspondentes com vencimento inferior a XX dias', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '20', 'Valor do título inválido (não numérico)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '21', 'Espécie do título inválida (arquivo de registro)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '22', 'Espécie não permetida para a carteira', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '23', 'Aceite inválido - verifica conteúdo válido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '24', 'Data de emissão inválida - verifica se a data é numérica e se está no formato válido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '25', 'Data de emissão posterior a data de processamento', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '26', 'Código de juros de mora inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '27', 'Valor/taxa de juros de mora inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '28', 'Código do desconto inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '29', 'Valor do desconto maior ou igual ao valor do título', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '30', 'Desconto a conceder não confere:\nInstrução de desconto inválida\nTaxa ou valor inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '32', 'Valor do IOF inválido:\nVerifica se o campo é numérico\nQuando for moeda AA - CUB e carteira 1 - Cobrança Simples, verifica se é menor ou igual a 99999,99', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '33', 'Valor do abatimento inválido - para registro de título verifica se o campo é numérico e para concessão/cancelamento de abatimento indica o erro se:\nInstrução de abatimento inválida\nValor inválido na instrução abatimento\nMovimento não for permitido para o título (título em cartório ou carteira desconto)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '34', 'Valor do abatimento maior ou igual ao valor do título', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '35', 'Abatimento a conceder não confere', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '36', 'Concessão de abatimento - já existe abatimento anterior', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '37', 'Código para protesto inválido - rejeita o título se o campo for diferente de branco, 0, 1 ou 3', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '38', 'Prazo para protesto inválido - se o código for \'1\' verifica se o campo é numérico', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '39', 'Pedido de protesto não permitido para o título - não permite protesto para as carteiras R, S e N', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '40', 'Título com ordem de protesto emitida (para retorno de alteração)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '41', 'Pedido de cancelamento/sustação de protesto inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '42', 'Código para baixa/devolução ou instrução inválido - verifica se o código é branco, 0, 1 ou 2', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '43', 'Prazo para baixa/devolução inválido - se o código é \'1\' verifica se o campo prazo é numérico', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '44', 'Código da moeda inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '45', 'Nome do Pagador inválido ou alteração do Pagador não permitida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '46', 'Tipo/número de inscrição do Pagador inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '47', 'Endereço não informado ou alteração de endereço não permitida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '48', 'CEP inválido ou alteração de CEP não permitida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '49', 'CEP sem praça de cobrança ou alteração de cidade não permitida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '50', 'CEP referente a um Banco Correspondente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '51', 'CEP incompatível com a unidade da federação', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '52', 'Unidade de Federação inválida ou alteração de UF não permitida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '53', 'Tipo/Número de inscrição do Sacador/Avalista inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '54', 'Sacador/Avalista não informado - para espécie AD o nome do Sacador é obrigatório', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '57', 'Código da multa inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '58', 'Data da multa inválida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '59', 'Valor/percentual da multa inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '60', 'Movimento para título não cadastrado - alteração ou devolução', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '62', 'Tipo de impressão inválido - Segmento 3S\nRejeita quando a mensagem genérica possuir o tipo de impressão diferente de B,C,E,G e no cadastro for \'N\'\nRejeita quando a mensagem específica possuir o tipo de impressão diferente de 2,3,D,F', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '63', 'Entrada para título já cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '79', 'Data de juros de mora inválido - valida data ou prazo na instrução de juros', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '80', 'Data do desconto inválida - valida data ou prazo da instrução de desconto', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '81', 'CEP inválido do Sacador', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '83', 'Tipo/Número de inscrição do Sacador inválido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '84', 'Sacador não informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '86', 'Seu número inválido (para retorno de alteração).', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03'));
$$);

select fc_executa_ddl($$
  create sequence caixa.retornocobrancaregistrada_k168_sequencial_seq
  increment 1
  minvalue 1
  maxvalue 9223372036854775807
  start 1
  cache 1;

  create table caixa.retornocobrancaregistrada(
    k168_sequencial     int4 not null  default nextval('retornocobrancaregistrada_k168_sequencial_seq'),
    k168_numpre     int4 not null ,
    constraint retornocobrancaregistrada_sequ_pk primary key (k168_sequencial)
  );
$$);

select fc_executa_ddl($$
  create sequence caixa.ocorrenciaretornocobrancaregistrada_k170_sequencial_seq
  increment 1
  minvalue 1
  maxvalue 9223372036854775807
  start 1
  cache 1;

  create table caixa.ocorrenciaretornocobrancaregistrada(
    k170_sequencial     int4 not null  default nextval('ocorrenciaretornocobrancaregistrada_k170_sequencial_seq'),
    k170_retornocobrancaregistrada      int4 not null ,
    k170_ocorrenciacobrancaregistrada       int4 ,
    constraint ocorrenciaretornocobrancaregistrada_sequ_pk primary key (k170_sequencial),
    constraint ocorrenciaretornocobrancaregistrada_retornocobrancaregistrada_fk foreign key (k170_retornocobrancaregistrada) references retornocobrancaregistrada,
    constraint ocorrenciaretornocobrancaregistrada_ocorrenciacobrancaregistrada_fk foreign key (k170_ocorrenciacobrancaregistrada) references ocorrenciacobrancaregistrada
  );
$$);

select fc_executa_ddl($$
  insert into db_layoutlinha values (889, 263, 'SEGMENTO T', 3, 240, 0, 0, '', '', false );
  insert into db_layoutcampos values (15317, 889, 'banco', 'CÓDIGO DO BANCO NA COMPENSAÇÃO', 2, 1, '', 3, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15318, 889, 'lote_servico', 'LOTE DE SERVIÇO', 2, 4, '', 4, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15319, 889, 'tipo_registro', 'TIPO DE REGISTRO', 1, 8, '3', 1, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15320, 889, 'sequencial', 'NÚMERO SEQUÊNCIAL DO REGISTRO NO LOTE', 2, 9, '', 5, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15321, 889, 'segmento', 'CÓDIGO SEGMENTO DO REGISTRO DETALHE', 1, 14, 'T', 1, true, true, 'd', '', 0 );
  insert into db_layoutcampos values (15322, 889, 'exclusivo_febraban', 'USO EXCLUSIVO FEBRABAN', 1, 15, '', 1, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15323, 889, 'codigo_movimento', 'CÓDIGO MOVIMENTO RETORNO', 1, 16, '', 2, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15324, 889, 'codigo_agencia', 'CÓDIGO DA AGÊNCIA', 2, 18, '', 5, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15325, 889, 'dv_agencia', 'DÍGITO VERIFICADOR AGÊNCIA', 1, 23, '', 1, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15326, 889, 'uso_exclusivo_banco', 'USO EXCLUSIVO DO BANCO', 1, 24, '', 34, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15327, 889, 'codigo_carteira', 'CÓDIGO DA CARTEIRA', 1, 58, '', 1, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15328, 889, 'uso_exclusivo_banco_1', 'USO EXCLUSIVO DO BANCO', 1, 59, '', 15, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15329, 889, 'data_vencimento', 'DATA DE VENCIMENTO', 1, 74, '', 8, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15330, 889, 'valor', 'VALOR NOMINAL DO TÍTULO', 1, 82, '', 15, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15331, 889, 'codigo_banco', 'CÓDIGO DO BANCO', 2, 97, '', 3, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15332, 889, 'codigo_agencia_cobranca', 'CÓDIGO DA AGÊNCIA COBR/RECEB', 1, 100, '', 5, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15333, 889, 'dv_agencia_cobranca', 'DÍGITO VERIFICADOR DA AGÊNCIA DA COBR', 1, 105, '', 1, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15334, 889, 'identificacao_titulo', 'IDENTIFICAÇÃO DO TÍTULO NA EMPRESA', 1, 106, '', 25, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15335, 889, 'codigo_moeda', 'CÓDIGO DA MOEDA', 2, 131, '', 2, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15336, 889, 'tipo_inscricao', 'TIPO DE INSCRIÇÃO DO SACADO', 1, 133, '', 1, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15337, 889, 'numero_inscricao', 'NÚMERO DE INSCRIÇÃO DO SACADO', 1, 134, '', 15, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15338, 889, 'nome', 'NOME DO SACADO', 1, 149, '', 40, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15339, 889, 'uso_exclusivo_febraban_1', 'USO EXCLUSIVO FEBRABAN', 1, 189, '', 10, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15340, 889, 'valor_tarifa', 'VALOR DAS TARIFAS/CUSTAS', 1, 199, '', 15, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15341, 889, 'motivo', 'MOTIVO DA OCORRÊNCIA', 1, 214, '', 10, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15342, 889, 'uso_exclusivo_febraban_2', 'USO EXCLUSIVO DA FEBRABAN', 1, 224, '', 17, false, true, 'd', '', 0 );
$$);

update db_layoutcampos set db52_ident = true where db52_codigo = 14975;

select fc_executa_ddl($$
  insert into db_sysarquivo values (3991, 'emissaogeralparcelaunica', 'Parcela Única usada na emissão geral', 'tr05', '2016-11-16', 'Parcela Única', 0, 'f', 'f', 'f', 'f' );
  insert into db_sysarqmod values (46,3991);
  insert into db_syscampo values(22155,'tr05_sequencial','int4','Código sequencial do vínculo entre Parcela Única e Emissão geral','0', 'Parcela Única',10,'f','f','f',1,'text','Parcela Única');
  insert into db_syscampo values(22156,'tr05_emissaogeral','int4','Código da Emissão Geral','0', 'Emissão Geral',10,'f','f','f',1,'text','Emissão Geral');
  insert into db_syscampo values(22157,'tr05_dataoperacao','date','Data de Operação da Parcela única','null', 'Data de Operação',10,'f','f','f',0,'text','Data de Operação');
  insert into db_syscampo values(22158,'tr05_datavencimento','date','Data de Vencimento da Parcela Única','null', 'Data de Vencimento',10,'f','f','f',1,'text','Data de Vencimento');
  insert into db_syscampo values(22159,'tr05_percentual','int4','Percentual de desconto da Parcela Única usada na Emissão Geral','0', 'Percentual',10,'f','f','f',1,'text','Percentual');
  delete from db_sysarqcamp where codarq = 3991;
  insert into db_sysarqcamp values(3991,22155,1,0);
  insert into db_sysarqcamp values(3991,22156,2,0);
  insert into db_sysarqcamp values(3991,22157,3,0);
  insert into db_sysarqcamp values(3991,22158,4,0);
  insert into db_sysarqcamp values(3991,22159,5,0);
  insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3991,22155,1,22155);
  insert into db_syssequencia values(1000620, 'emissaogeralparcelaunica_tr05_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
  update db_sysarqcamp set codsequencia = 1000620 where codarq = 3991 and codcam = 22155;
  delete from db_sysforkey where codarq = 3991 and referen = 0;
  insert into db_sysforkey values(3991,22156,1,3986,0);

  insert into db_syscampo values(22164,'k00_nossonumero','varchar(20)','Nosso Número','0', 'Nosso Número',10,'f','f','f',0,'text','Nosso Número');
  delete from db_sysarqcamp where codarq = 1575;
  insert into db_sysarqcamp values(1575,361,1,0);
  insert into db_sysarqcamp values(1575,9206,2,0);
  insert into db_sysarqcamp values(1575,9207,3,0);
  insert into db_sysarqcamp values(1575,22164,4,0);

  insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10336 ,'Emissão Geral de IPTU' ,'Emissão Geral de IPTU' ,'cad4_emiteiptuNovo.php' ,'1' ,'1' ,'Emissão Geral de IPTU' ,'true' );
  insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,10336 ,475 ,578 );
  update db_itensmenu set id_item = 1576 , descricao = 'Emissão Geral de IPTU' , help = 'Emissão Geral de IPTU' , funcao = 'cad4_emiteiptu.php' , itemativo = '1' , manutencao = '1' , desctec = 'Gera layout dos carnes de iptu.' , libcliente = 'false' where id_item = 1576;
$$);

select fc_executa_ddl($$
  CREATE SEQUENCE tributario.emissaogeralparcelaunica_tr05_sequencial_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;
$$);

CREATE TABLE if not exists tributario.emissaogeralparcelaunica(
tr05_sequencial   int4 NOT NULL default 0,
tr05_emissaogeral   int4 NOT NULL default 0,
tr05_dataoperacao   date NOT NULL default null,
tr05_datavencimento   date NOT NULL default null,
tr05_percentual   int4 default 0,
CONSTRAINT emissaogeralparcelaunica_sequ_pk PRIMARY KEY (tr05_sequencial));

select fc_executa_ddl($$
  ALTER TABLE tributario.emissaogeralparcelaunica
  ADD CONSTRAINT emissaogeralparcelaunica_emissaogeral_fk FOREIGN KEY (tr05_emissaogeral)
  REFERENCES emissaogeral;

  ALTER TABLE recibocodbar
  ADD COLUMN k00_nossonumero varchar(20);
$$);    

SQL;
  
    $this->execute($sql);
  }

public function down(){}

}