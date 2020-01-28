<?php

use Classes\PostgresMigration;

class M5291 extends PostgresMigration
{

public function up(){
    $sql = <<<'SQL'

    select fc_executa_ddl($$
  insert into db_sysarquivo values (3986, 'emissaogeral', 'Tabela que registra as informa��es das Emiss�es Gerais do Sistema', 'tr01', '2016-11-04', 'Emissao Geral', 0, 'f', 'f', 'f', 'f' );
  insert into db_sysarqmod values (46,3986);
  insert into db_sysarquivo values (3987, 'emissaogeralregistro', 'Tabela que salva as informa��es dos registros gerados nas Emiss�es Gerais no Sistema', 'tr02', '2016-11-04', 'Emissao Geral Registro', 0, 'f', 'f', 'f', 'f' );
  insert into db_sysarqmod values (46,3987);
  insert into db_sysarquivo values (3988, 'emissaogeralmatricula', 'Tabela que vincula os registros das Emiss�es Gerais do Sistemas com as suas matr�culas, quando necess�rio.', 'tr03', '2016-11-04', 'Emissao Geral Matr�cula', 0, 'f', 'f', 'f', 'f' );
  insert into db_sysarqmod values (46,3988);
  insert into db_sysarquivo values (3989, 'emissaogeralinscricao', 'Tabela que vincula os registros das Emiss�es Gerais do Sistemas com as suas inscri��es, quando necess�rio.', 'tr04', '2016-11-04', 'Emiss�o Geral Inscri��o', 0, 'f', 'f', 'f', 'f' );
  insert into db_sysarqmod values (46,3989);
  insert into db_syscampo values(22126,'tr01_sequencial','int4','C�digo Sequencial da Emiss�o Geral','0', 'C�digo da Emiss�o Geral',10,'f','f','f',1,'text','C�digo da Emiss�o Geral');
  insert into db_syscampo values(22127,'tr01_data','date','Data da Emiss�o da Emiss�o Geral','null', 'Data Emiss�o',10,'f','f','f',1,'text','Data Emiss�o');
  insert into db_syscampo values(22128,'tr01_usuario','int4','Usu�rio que processou a Emiss�o Geral','0', 'Usu�rio',10,'f','f','f',1,'text','Usu�rio');
  insert into db_syscampo values(22129,'tr01_tipoemissao','int4','Tipo de Emiss�o Geral','0', 'Tipo de Emiss�o',2,'f','f','f',1,'text','Tipo de Emiss�o');
  insert into db_syscampo values(22130,'tr01_instit','int4','Institui��o','0', 'Institui��o',10,'f','f','f',1,'text','Institui��o');
  insert into db_syscampo values(22131,'tr01_convenio','int4','Conv�nio da Emiss�o Geral','0', 'Conv�nio',10,'t','f','f',1,'text','Conv�nio');
  insert into db_syscampo values(22132,'tr01_hora','char(5)','Hora da Emiss�o Geral','', 'Hora da Emiss�o',5,'f','t','f',0,'text','Hora da Emiss�o');
  insert into db_syscampo values(22160,'tr01_parametros','text','Par�metros','','Par�metros' ,1 ,'true' ,'false' ,'false' ,0 ,'text' ,'Par�metros');
  insert into db_syscampo values(22133,'tr02_sequencial','int4','C�digo do Registro Emiss�o Geral','0', 'C�digo do Registro',10,'f','f','f',1,'text','C�digo do Registro');
  insert into db_syscampo values(22134,'tr02_emissaogeral','int4','C�digo da Emiss�o Geral','0', 'C�digo da Emiss�o Geral',10,'f','f','f',1,'text','C�digo da Emiss�o Geral');
  insert into db_syscampo values(22135,'tr02_numcgm','int4','CGM vinculado ao registro da Emiss�o Geral','0', 'CGM',10,'f','f','f',1,'text','CGM');
  insert into db_syscampo values(22136,'tr02_numpre','int4','Numpre do registro','0', 'Numpre',8,'f','f','f',1,'text','Numpre');
  insert into db_syscampo values(22138,'tr02_parcela','int4','Parcela do registro gerado na Emiss�o Geral','0', 'Parcela',10,'f','f','f',1,'text','Parcela');
  insert into db_syscampo values(22139,'tr02_situacao','int4','Situa��o do registro em rela��o a Emiss�o Geral(vide regra de Neg�cio da Emiss�o Geral a que se refere).','0', 'Situa��o',2,'f','f','f',1,'text','Situa��o');
  insert into db_syscampo values(22140,'tr03_sequencial','int4','C�digo do Registro da Emiss�o Geral','0', 'C�digo do Registro',10,'f','f','f',1,'text','C�digo do Registro');
  insert into db_syscampo values(22141,'tr03_emissaogeral','int4','C�digo da Emiss�o Geral','0', 'C�digo da Emiss�o Geral',10,'f','f','f',1,'text','C�digo da Emiss�o Geral');
  insert into db_syscampo values(22142,'tr03_emissaogeralregistro','int4','C�digo do Registro','0', 'C�digo do Registro',10,'f','f','f',1,'text','C�digo do Registro');
  insert into db_syscampo values(22143,'tr03_matric','int4','Matr�cula vinculada ao registro','0', 'Matr�cula',10,'f','f','f',1,'text','Matr�cula');
  insert into db_syscampo values(22144,'tr04_sequencial','int4','C�digo Sequencial da tabela de v�nculo do Registro com a Inscri��o','0', 'C�digo Sequencial',10,'f','f','f',1,'text','C�digo Sequencial');
  insert into db_syscampo values(22145,'tr04_emissaogeralregistro','int4','C�digo da Emiss�o Geral','0', 'C�digo da Emiss�o Geral',10,'f','f','f',1,'text','C�digo da Emiss�o Geral');
  insert into db_syscampo values(22146,'tr04_inscr','int4','Inscri��o vinculada ao Registro','0', 'Inscri��o',10,'f','f','f',1,'text','Inscri��o');
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

-- M�dulo: tributario
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


-- M�dulo: tributario
CREATE TABLE if not exists tributario.emissaogeralinscricao(
tr04_sequencial   int4 NOT NULL default 0,
tr04_emissaogeralregistro   int4 NOT NULL default 0,
tr04_inscr    int4 default 0,
CONSTRAINT emissaogeralinscricao_sequ_pk PRIMARY KEY (tr04_sequencial));


-- M�dulo: tributario
CREATE TABLE if not exists tributario.emissaogeralmatricula(
tr03_sequencial   int4 NOT NULL default 0,
tr03_emissaogeralregistro   int4 NOT NULL default 0,
tr03_matric   int4 default 0,
CONSTRAINT emissaogeralmatricula_sequ_pk PRIMARY KEY (tr03_sequencial));


-- M�dulo: tributario
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
    values (3999, 'movimentoocorrenciacobrancaregistrada', 'Movimento da Ocorr�ncia da Cobran�a Registrada', 'k169', '2016-11-28', 'Movimento da Ocorr�ncia da Cobran�a Registrada', 0, 'f', 'f', 'f', 'f' );
  insert into db_sysarqmod values (5,3999);

  insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
    values ( 22204 ,'k169_sequencial' ,'int4' ,'Sequencial' ,'' ,'Sequencial' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Sequencial' ),
           ( 22205 ,'k169_codigo' ,'varchar(2)' ,'C�digo do Movimento' ,'' ,'C�digo do Movimento' ,2 ,'false' ,'true' ,'false' ,0 ,'text' ,'C�digo do Movimento' ),
           ( 22206 ,'k169_descricao' ,'varchar(500)' ,'Descri��o' ,'' ,'Descri��o' ,500 ,'false' ,'true' ,'false' ,0 ,'text' ,'Descri��o' );

  insert into db_syssequencia values(1000627, 'movimentoocorrenciacobrancaregistrada_k169_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

  insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia )
    values ( 3999 ,22204 ,1 ,1000627 ),
           ( 3999 ,22205 ,2 ,0 ),
           ( 3999 ,22206 ,3 ,0 );

  insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3999,22204,1,22204);
$$);


select fc_executa_ddl($$
  insert into db_sysarquivo
    values (3997, 'ocorrenciacobrancaregistrada', 'Ocorr�ncias do Retorno do Arquivo de Cobran�a Registrada', 'k149', '2016-11-28', 'Ocorr�ncia Cobranca Registrada', 0, 'f', 'f', 'f', 'f' );
  insert into db_sysarqmod values (5,3997);

  insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
    values ( 22196 ,'k149_sequencial' ,'int4' ,'Sequencial' ,'' ,'Sequencial' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Sequencial' ),
           ( 22197 ,'k149_banco' ,'varchar(10)' ,'C�digo do Banco' ,'' ,'C�digo do Banco' ,10 ,'false' ,'true' ,'false' ,0 ,'text' ,'C�digo do Banco' ),
           ( 22198 ,'k149_codigo' ,'varchar(2)' ,'C�digo da Ocorr�ncia' ,'' ,'C�digo da Ocorr�ncia' ,2 ,'false' ,'true' ,'false' ,0 ,'text' ,'C�digo da Ocorr�ncia' ),
           ( 22199 ,'k149_descricao' ,'text' ,'Descri��o' ,'' ,'Descri��o da Ocorr�ncia' ,1 ,'false' ,'false' ,'false' ,0 ,'text' ,'Descri��o da Ocorr�ncia' ),
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
    values (3998, 'retornocobrancaregistrada', 'Retorno do Arquivo de Cobran�a Registrada', 'k168', '2016-11-28', 'Retorno Cobran�a Registrada', 0, 'f', 'f', 'f', 'f' );
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
    values ( 10338 ,'Retorno Cobran�a Registrada' ,'Retorno Cobran�a Registrada' ,'arr2_retornocobrancaregistrada001.php' ,'1' ,'1' ,'Relat�rio do retorno da cobran�a registrada' ,'true' );
  insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo )
    values ( 30 ,10338 ,460 ,1985522 );
$$);

select fc_executa_ddl($$
  insert into db_sysarquivo
    values (4000, 'ocorrenciaretornocobrancaregistrada', 'V�nculo entre o retorno da cobran�a registrada e a ocorr�ncia', 'k170', '2016-11-29', 'Ocorr�ncia Retorno Cobran�a Registrada', 0, 'f', 'f', 'f', 'f' );
  insert into db_sysarqmod values (5,4000);

  insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
    values ( 22208 ,'k170_sequencial' ,'int4' ,'Sequ�ncial' ,'' ,'Sequ�ncial' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Sequ�ncial' ),
           ( 22209 ,'k170_retornocobrancaregistrada' ,'int4' ,'Retorno Cobran�a Registrada' ,'' ,'Retorno Cobran�a Registrada' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Retorno Cobran�a Registrada' ),
           ( 22210 ,'k170_ocorrenciacobrancaregistrada' ,'int4' ,'Ocorr�ncia Cobran�a Registrada' ,'' ,'Ocorr�ncia Cobran�a Registrada' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Ocorr�ncia Cobran�a Registrada' );

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
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '01', 'C�digo do Banco Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '02', 'C�digo do Registro Detalhe Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '03', 'C�digo do Segmento Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '04', 'C�digo de Movimento N�o Permitido para Carteira', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '05', 'C�digo de Movimento Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '06', 'Tipo/N�mero de Inscri��o do Cedente Inv�lidos', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '07', 'Ag�ncia/Conta/DV Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '08', 'Nosso N�mero Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '09', 'Nosso N�mero Duplicado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '10', 'Carteira Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '11', 'Forma de Cadastramento do T�tulo Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '12', 'Tipo de Documento Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '13', 'Identifica��o da Emiss�o do Bloqueto Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '14', 'Identifica��o da Distribui��o do Bloqueto Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '15', 'Caracter�sticas da Cobran�a Incompat�veis', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '16', 'Data de Vencimento Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '17', 'Data de Vencimento Anterior a Data de Emiss�o', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '18', 'Vencimento Fora do Prazo de Opera��o', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '19', 'T�tulo a Cargo de Bancos Correspondentes com Vencimento Inferior a XX Dias', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '20', 'Valor do T�tulo Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '21', 'Esp�cie do T�tulo Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '22', 'Esp�cie do T�tulo N�o Permitida para a Carteira', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '23', 'Aceite Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '24', 'Data da Emiss�o Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '25', 'Data da Emiss�o Posterior a Data de Entrada', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '26', 'C�digo de Juros de Mora Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '27', 'Valor/Taxa de Juros de Mora Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '28', 'C�digo do Desconto Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '29', 'Valor do Desconto Maior ou Igual ao Valor do T�tulo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '30', 'Desconto a Conceder N�o Confere', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '31', 'Concess�o de Desconto - J� Existe Desconto Anterior', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '32', 'Valor do IOF Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '33', 'Valor do Abatimento Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '34', 'Valor do Abatimento Maior ou Igual ao Valor do T�tulo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '35', 'Valor a Conceder N�o Confere', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '36', 'Concess�o de Abatimento - J� Existe Abatimento Anterior', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '37', 'C�digo para Protesto Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '38', 'Prazo para Protesto Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '39', 'Pedido de Protesto N�o Permitido para o T�tulo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '40', 'T�tulo com Ordem de Protesto Emitida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '41', 'Pedido de Cancelamento/Susta��o para T�tulos sem Instru��o de Protesto', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '42', 'C�digo para Baixa/Devolu��o Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '43', 'Prazo para Baixa/Devolu��o Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '44', 'C�digo da Moeda Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '45', 'Nome do Sacado N�o Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '46', 'Tipo/N�mero de Inscri��o do Sacado Inv�lidos', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '47', 'Endere�o do Sacado N�o Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '48', 'CEP Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '49', 'CEP Sem Pra�a de Cobran�a (N�o Localizado)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '50', 'CEP Referente a um Banco Correspondente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '51', 'CEP incompat�vel com a Unidade da Federa��o', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '52', 'Unidade da Federa��o Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '53', 'Tipo/N�mero de Inscri��o do Sacador/Avalista Inv�lidos', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '54', 'Sacador/Avalista N�o Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '55', 'Nosso n�mero no Banco Correspondente N�o Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '56', 'C�digo do Banco Correspondente N�o Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '57', 'C�digo da Multa Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '58', 'Data da Multa Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '59', 'Valor/Percentual da Multa Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '60', 'Movimento para T�tulo N�o Cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '61', 'Altera��o da Ag�ncia Cobradora/DV Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '62', 'Tipo de Impress�o Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '63', 'Entrada para T�tulo j� Cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '64', 'N�mero da Linha Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '65', 'C�digo do Banco para D�bito Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '66', 'Ag�ncia/Conta/DV para D�bito Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '67', 'Dados para D�bito incompat�vel com a Identifica��o da Emiss�o do Bloqueto', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '68', 'D�bito Autom�tico Agendado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '69', 'D�bito N�o Agendado - Erro nos Dados da Remessa', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '70', 'D�bito N�o Agendado - Sacado N�o Consta do Cadastro de Autorizante', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '71', 'D�bito N�o Agendado - Cedente N�o Autorizado pelo Sacado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '72', 'D�bito N�o Agendado - Cedente N�o Participa da Modalidade D�bito Autom�tico', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '73', 'D�bito N�o Agendado - C�digo de Moeda Diferente de Real (R$)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '74', 'D�bito N�o Agendado - Data Vencimento Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '75', 'D�bito N�o Agendado, Conforme seu Pedido, T�tulo N�o Registrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '76', 'D�bito N�o Agendado, Tipo/Num. Inscri��o do Debitado, Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '77', 'Transfer�ncia para Desconto N�o Permitida para a Carteira do T�tulo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '78', 'Data Inferior ou Igual ao Vencimento para D�bito Autom�tico', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '79', 'Data Juros de Mora Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '80', 'Data do Desconto Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '81', 'Tentativas de D�bito Esgotadas - Baixado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '82', 'Tentativas de D�bito Esgotadas - Pendente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '83', 'Limite Excedido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '84', 'N�mero Autoriza��o Inexistente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '85', 'T�tulo com Pagamento Vinculado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '86', 'Seu N�mero Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '87', 'e-mail/SMS enviado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '88', 'e-mail Lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '89', 'e-mail/SMS devolvido - endere�o de e-mail ou n�mero do celular incorreto', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '90', 'e-mail devolvido - caixa postal cheia', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '91', 'e-mail/n�mero do celular do sacado n�o informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '92', 'Sacado optante por Bloqueto Eletr�nico - e-mail n�o enviado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '93', 'C�digo para emiss�o de bloqueto n�o permite envio de e-mail', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '94', 'C�digo da Carteira inv�lido para envio e-mail.', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '95', 'Contrato n�o permite o envio de e-mail', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '96', 'N�mero de contrato inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '97', 'Rejei��o da altera��o do prazo limite de recebimento (a data deve ser informada no campo 28.3.p)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '98', 'Rejei��o de dispensa de prazo limite de recebimento', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '99', 'Rejei��o da altera��o do n�mero do t�tulo dado pelo cedente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A1', 'Rejei��o da altera��o do n�mero controle do participante', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A2', 'Rejei��o da altera��o dos dados do sacado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A3', 'Rejei��o da altera��o dos dados do sacador/avalista', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A4', 'Sacado DDA', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A5', 'Registro Rejeitado - T�tulo j� Liquidado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A6', 'C�digo do Convenente Inv�lido ou Encerrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A7', 'T�tulo j� se encontra na situa��o Pretendida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A8', 'Valor do Abatimento inv�lido para cancelamento', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A9', 'N�o autoriza pagamento parcial', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'B1', 'Autoriza recebimento parcial', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02'));

  insert into ocorrenciacobrancaregistrada (k149_sequencial, k149_banco, k149_codigo, k149_descricao, k149_movimento)
    values
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '01', 'C�digo do Banco Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '02', 'C�digo do Registro Detalhe Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '03', 'C�digo do Segmento Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '04', 'C�digo de Movimento N�o Permitido para Carteira', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '05', 'C�digo de Movimento Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '06', 'Tipo/N�mero de Inscri��o do Cedente Inv�lidos', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '07', 'Ag�ncia/Conta/DV Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '08', 'Nosso N�mero Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '09', 'Nosso N�mero Duplicado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '10', 'Carteira Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '11', 'Forma de Cadastramento do T�tulo Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '12', 'Tipo de Documento Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '13', 'Identifica��o da Emiss�o do Bloqueto Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '14', 'Identifica��o da Distribui��o do Bloqueto Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '15', 'Caracter�sticas da Cobran�a Incompat�veis', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '16', 'Data de Vencimento Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '17', 'Data de Vencimento Anterior a Data de Emiss�o', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '18', 'Vencimento Fora do Prazo de Opera��o', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '19', 'T�tulo a Cargo de Bancos Correspondentes com Vencimento Inferior a XX Dias', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '20', 'Valor do T�tulo Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '21', 'Esp�cie do T�tulo Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '22', 'Esp�cie do T�tulo N�o Permitida para a Carteira', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '23', 'Aceite Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '24', 'Data da Emiss�o Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '25', 'Data da Emiss�o Posterior a Data de Entrada', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '26', 'C�digo de Juros de Mora Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '27', 'Valor/Taxa de Juros de Mora Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '28', 'C�digo do Desconto Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '29', 'Valor do Desconto Maior ou Igual ao Valor do T�tulo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '30', 'Desconto a Conceder N�o Confere', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '31', 'Concess�o de Desconto - J� Existe Desconto Anterior', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '32', 'Valor do IOF Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '33', 'Valor do Abatimento Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '34', 'Valor do Abatimento Maior ou Igual ao Valor do T�tulo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '35', 'Valor a Conceder N�o Confere', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '36', 'Concess�o de Abatimento - J� Existe Abatimento Anterior', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '37', 'C�digo para Protesto Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '38', 'Prazo para Protesto Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '39', 'Pedido de Protesto N�o Permitido para o T�tulo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '40', 'T�tulo com Ordem de Protesto Emitida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '41', 'Pedido de Cancelamento/Susta��o para T�tulos sem Instru��o de Protesto', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '42', 'C�digo para Baixa/Devolu��o Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '43', 'Prazo para Baixa/Devolu��o Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '44', 'C�digo da Moeda Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '45', 'Nome do Sacado N�o Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '46', 'Tipo/N�mero de Inscri��o do Sacado Inv�lidos', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '47', 'Endere�o do Sacado N�o Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '48', 'CEP Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '49', 'CEP Sem Pra�a de Cobran�a (N�o Localizado)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '50', 'CEP Referente a um Banco Correspondente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '51', 'CEP incompat�vel com a Unidade da Federa��o', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '52', 'Unidade da Federa��o Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '53', 'Tipo/N�mero de Inscri��o do Sacador/Avalista Inv�lidos', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '54', 'Sacador/Avalista N�o Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '55', 'Nosso n�mero no Banco Correspondente N�o Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '56', 'C�digo do Banco Correspondente N�o Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '57', 'C�digo da Multa Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '58', 'Data da Multa Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '59', 'Valor/Percentual da Multa Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '60', 'Movimento para T�tulo N�o Cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '61', 'Altera��o da Ag�ncia Cobradora/DV Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '62', 'Tipo de Impress�o Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '63', 'Entrada para T�tulo j� Cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '64', 'N�mero da Linha Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '65', 'C�digo do Banco para D�bito Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '66', 'Ag�ncia/Conta/DV para D�bito Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '67', 'Dados para D�bito incompat�vel com a Identifica��o da Emiss�o do Bloqueto', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '68', 'D�bito Autom�tico Agendado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '69', 'D�bito N�o Agendado - Erro nos Dados da Remessa', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '70', 'D�bito N�o Agendado - Sacado N�o Consta do Cadastro de Autorizante', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '71', 'D�bito N�o Agendado - Cedente N�o Autorizado pelo Sacado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '72', 'D�bito N�o Agendado - Cedente N�o Participa da Modalidade D�bito Autom�tico', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '73', 'D�bito N�o Agendado - C�digo de Moeda Diferente de Real (R$)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '74', 'D�bito N�o Agendado - Data Vencimento Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '75', 'D�bito N�o Agendado, Conforme seu Pedido, T�tulo N�o Registrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '76', 'D�bito N�o Agendado, Tipo/Num. Inscri��o do Debitado, Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '77', 'Transfer�ncia para Desconto N�o Permitida para a Carteira do T�tulo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '78', 'Data Inferior ou Igual ao Vencimento para D�bito Autom�tico', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '79', 'Data Juros de Mora Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '80', 'Data do Desconto Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '81', 'Tentativas de D�bito Esgotadas - Baixado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '82', 'Tentativas de D�bito Esgotadas - Pendente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '83', 'Limite Excedido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '84', 'N�mero Autoriza��o Inexistente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '85', 'T�tulo com Pagamento Vinculado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '86', 'Seu N�mero Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '87', 'e-mail/SMS enviado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '88', 'e-mail Lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '89', 'e-mail/SMS devolvido - endere�o de e-mail ou n�mero do celular incorreto', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '90', 'e-mail devolvido - caixa postal cheia', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '91', 'e-mail/n�mero do celular do sacado n�o informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '92', 'Sacado optante por Bloqueto Eletr�nico - e-mail n�o enviado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '93', 'C�digo para emiss�o de bloqueto n�o permite envio de e-mail', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '94', 'C�digo da Carteira inv�lido para envio e-mail.', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '95', 'Contrato n�o permite o envio de e-mail', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '96', 'N�mero de contrato inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '97', 'Rejei��o da altera��o do prazo limite de recebimento (a data deve ser informada no campo 28.3.p)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '98', 'Rejei��o de dispensa de prazo limite de recebimento', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '99', 'Rejei��o da altera��o do n�mero do t�tulo dado pelo cedente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A1', 'Rejei��o da altera��o do n�mero controle do participante', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A2', 'Rejei��o da altera��o dos dados do sacado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A3', 'Rejei��o da altera��o dos dados do sacador/avalista', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A4', 'Sacado DDA', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A5', 'Registro Rejeitado - T�tulo j� Liquidado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A6', 'C�digo do Convenente Inv�lido ou Encerrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A7', 'T�tulo j� se encontra na situa��o Pretendida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A8', 'Valor do Abatimento inv�lido para cancelamento', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'A9', 'N�o autoriza pagamento parcial', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', 'B1', 'Autoriza recebimento parcial', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03'));

  insert into ocorrenciacobrancaregistrada (k149_sequencial, k149_banco, k149_codigo, k149_descricao, k149_movimento)
    values
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '09', 'Comandada Banco', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '09')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '10', 'Comandada Cliente Arquivo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '09')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '11', 'Comandada Cliente On-line', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '09')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '12', 'Decurso Prazo - Cliente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '09')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '13', 'Decurso Prazo - Banco', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '09')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '14', 'Protestado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '09')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '001', '15', 'T�tulo Exclu�do', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '09'));

  -- Caixa
  insert into ocorrenciacobrancaregistrada (k149_sequencial, k149_banco, k149_codigo, k149_descricao, k149_movimento)
    values
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '00', 'Entrada Confirmada', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AA', 'C�d Desconto Preenchido, Obrig Data e Valor/Perc', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AB', 'Cod Desconto Obrigat�rio p/ C�d Mov = 7', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AC', 'Forma de Cadastramento Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AD', 'Data de Desconto deve estar em Ordem Crescente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AE', 'Data de Desconto � Posterior a Data de Vencimento', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AF', 'T�tulo n�o est� com situa��o \'Em Aberto\'', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AG', 'T�tulo j� est� Vencido / Vencendo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AH', 'N�o existe desconto a ser cancelado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AI', 'Data solicitada p/ Prot/Dev � anterior a data atual', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AJ', 'C�digo do Sacado Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AK', 'N�mero da Parcela Invalida ou Fora de Sequencia', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AL', 'Estorno de Envio N�o Permitido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AM', 'Nosso Numero Fora de Sequencia', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VA', 'Arq.Ret.Inexis. P/ Redisp. Nesta Dt/Nro', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VB', 'Registro Duplicado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VC', 'Cedente deve ser padr�o CNAB240', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VD', 'Ident. Banco Sacado Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VE', 'Num Docto Cobr Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VF', 'Vlr/Perc a ser concedido inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VG', 'Data de Inscri��o Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VH', 'Data Movto Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VI', 'Data Inicial Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VJ', 'Data Final Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VK', 'Banco de Sacado j� cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VL', 'Cedente n�o cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VM', 'N�mero de Lote Duplicado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VN', 'Forma de Emiss�o de Bloqueto Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VO', 'Forma Entrega Bloqueto Inv�lida p/ Emiss�o via Banco', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VP', 'Forma Entrega Bloqueto Invalida p/ Emiss�o via Cedente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VQ', 'Op��o para Endosso Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VR', 'Tipo de Juros ao M�s Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VS', 'Percentual de Juros ao M�s Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VT', 'Percentual / Valor de Desconto Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VU', 'Prazo de Desconto Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VV', 'Preencher Somente Percentual ou Valor', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VW', 'Prazo de Multa Invalido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VX', 'Perc. Desconto tem que estar em ordem decrescente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VY', 'Valor Desconto tem que estar em ordem descrescente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VZ', 'Dias/Data desconto tem que estar em ordem decrescente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WA', 'Vlr Contr p/ aquisi��o de Bens Inv�lid', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WB', 'Vlr Contr p/ Fundo de Reserva Inv�lid', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WC', 'Vlr Rend. Aplica��es Financ Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WD', 'Valor Multa/Juros Monetarios Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WE', 'Valor Premios de Seguro Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WF', 'Valor Custas Judiciais Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WG', 'Valor Reembolso de Despesas Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WH', 'Valor Outros Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WI', 'Valor de Aquisi��o de Bens Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WJ', 'Valor Devolvido ao Consorciado Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WK', 'Vlr Desp. Registro de Contrato Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WL', 'Valor de Rendimentos Pagos Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WM', 'Data de Descri��o Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WN', 'Valor do Seguro Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WO', 'Data de Vencimento Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WP', 'Data de Nascimento Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WQ', 'CPF/CNPJ do Aluno Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WR', 'Data de Avalia��o Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WS', 'CPF/CNPJ do Locatario Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WT', 'Literal da Remessa Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WU', 'Tipo de Registro Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WV', 'Modelo Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WW', 'C�digo do Banco de Sacados Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WX', 'Banco de Sacados n�o Cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WY', 'Qtde dias para Protesto tem que estar entre 2 e 90', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WZ', 'N�o existem Sacados para este Banco', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XA', 'Pre�o Unitario do Produto Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XB', 'Pre�o Total do Produto Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XC', 'Valor Atual do Bem Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XD', 'Quantidade de Bens Entregues Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XE', 'Quantidade de Bens Distribuidos Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XF', 'Quantidade de Bens n�o Distribuidos Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XG', 'N�mero da Pr�xima Assembl�ia Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XH', 'Horario da Pr�xima Assembl�ia Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XI', 'Data da Pr�xima Assembl�ia Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XJ', 'N�mero de Ativos Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XK', 'N�mero de Desistentes Excluidos Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XL', 'N�mero de Quitados Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XM', 'N�mero de Contemplados Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XN', 'N�mero de n�o Contemplados Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XO', 'Data da �ltima Assembl�ia Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XP', 'Quantidade de Presta��es Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XQ', 'Data de Vencimento da Parcela Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XR', 'Valor da Amortiza��o Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XS', 'C�digo do Personalizado Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XT', 'Valor da Contribui��o Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XU', 'Percentual da Contribui��o Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XV', 'Valor do Fundo de Reserva Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XW', 'N�mero Parcela Inv�lido ou Fora de Sequ�ncia', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XX', 'Percentual Fundo de Reserva Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XY', 'Prz Desc/Multa Preenchido, Obrigat.Perc. ou Valor', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XZ', 'Valor Taxa de Administra��o Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YA', 'Data de Juros Inv�lida ou N�o Informada', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YB', 'Data Desconto Inv�lida ou N�o Informada', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YC', 'E-mail Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YD', 'C�digo de Ocorr�ncia Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YE', 'Sacado j� Cadastrado (Banco de Sacados)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YF', 'Sacado n�o Cadastrado (Banco de Sacados)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YG', 'Remessa Sem Registro Tipo 9', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YH', 'Identifica��o da Solicita��o Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YI', 'Quantidade Bloquetos Solicitada Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YJ', 'Trailler do Arquivo n�o Encontrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YK', 'Tipo Inscri��o do Responsable Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YL', 'N�mero Inscri��o do Responsable Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YM', 'Ajuste de Vencimento Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YN', 'Ajuste de Emiss�o Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YO', 'C�digo de Modelo Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YP', 'V�a de Entrega Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YQ', 'Esp�cie Banco de Sacado Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YR', 'Aceite Banco de Sacado Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YS', 'Sacado j� Cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YT', 'Sacado n�o Cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YU', 'N�mero do Telefone Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YV', 'CNPJ do Condom�nio Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YW', 'Indicador de Registro de T�tulo Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YX', 'Valor da Nota Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YY', 'Qtde de dias para Devolu��o tem que estar entre 5 e 120', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YZ', 'Quantidade de Produtos Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZA', 'Perc. Taxa de Administra��o Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZB', 'Valor do Seguro Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZC', 'Percentual do Seguro Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZD', 'Valor da Diferen�a da Parcela Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZE', 'Perc. Da Diferen�a da Parcela Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZF', 'Valor Reajuste do Saldo de Caixa Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZG', 'Perc. Reajuste do Saldo de Caixa Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZH', 'Valor Total a Pagar Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZI', 'Percentual ao Total a Pagar Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZJ', 'Valor de Outros Acr�scimos Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZK', 'Perc. De Outros Acr�scimos Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZL', 'Valor de Outras Dedu��es Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZM', 'Perc. De Outras Dedu��es Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZN', 'Valor da Contribui��o Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZO', 'Percentual da Contribui��o Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZP', 'Valor de Juros/Multa Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZQ', 'Percentual de Juros/Multa Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZR', 'Valor Cobrado Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZS', 'Percentual Cobrado Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZT', 'Valor Disponibilizado em Caixa Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZU', 'Valor Dep�sito Bancario Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZV', 'Valor Aplica��es Financieras Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZW', 'Data/Valor Preenchidos, Obrigat�rio D�digo Desconto', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZX', 'Valor Cheques em Cobran�a Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZY', 'Desconto c/ valor Fixo, Obrigat�rio Valor do T�tulo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZZ', 'C�digo Movimento Inv�lido p/ Segmento Y8', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '01', 'C�digo do Banco Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '02', 'C�digo do Registro Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '03', 'C�digo do Segmento Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '04', 'C�digo do Movimento n�o Permitido p/ Carteira', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '05', 'C�digo do Movimento Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '06', 'Tipo N�mero Inscri��o Cedente Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '07', 'Agencia/Conta/DV Inv�lidos', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '08', 'Nosso N�mero Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '09', 'Nosso N�mero Duplicado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '10', 'Carteira Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '11', 'Data de Gera��o Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '12', 'Tipo de Documento Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '13', 'Identif. Da Emiss�o do Bloqueto Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '14', 'Identif. Da Distribui��o do Bloqueto Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '15', 'Caracter�sticas Cobran�a Incompat�veis', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '16', 'Data de Vencimento Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '17', 'Data de Vencimento Anterior a Data de Emiss�o', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '18', 'Vencimento fora do prazo de opera��o', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '19', 'T�tulo a Cargo de Bco Correspondentes c/ Vencto Inferior a XX Dias', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '20', 'Valor do T�tulo Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '21', 'Esp�cie do T�tulo Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '22', 'Esp�cie do T�tulo N�o Permitida para a Carteira', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '23', 'Aceite Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '24', 'Data da Emiss�o Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '25', 'Data da Emiss�o Posterior a Data de Entrada', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '26', 'C�digo de Juros de Mora Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '27', 'Valor/Taxa de Juros de Mora Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '28', 'C�digo do Desconto Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '29', 'Valor do Desconto Maior ou Igual ao Valor do T�tulo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '30', 'Desconto a Conceder N�o Confere', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '31', 'Concess�o de Desconto - J� Existe Desconto Anterior', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '32', 'Valor do IOF Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '33', 'Valor do Abatimento Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '34', 'Valor do Abatimento Maior ou Igual ao Valor do T�tulo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '35', 'Valor Abatimento a Conceder N�o Confere', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '36', 'Concess�o de Abatimento - J� Existe Abatimento Anterior', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '37', 'C�digo para Protesto Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '38', 'Prazo para Protesto Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '39', 'Pedido de Protesto N�o Permitido para o T�tulo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '40', 'T�tulo com Ordem de Protesto Emitida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '41', 'Pedido Cancelamento/Susta��o p/ T�tulos sem Instru��o Protesto', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '42', 'C�digo para Baixa/Devolu��o Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '43', 'Prazo para Baixa/Devolu��o Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '44', 'C�digo da Moeda Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '45', 'Nome do Sacado N�o Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '46', 'Tipo/N�mero de Inscri��o do Sacado Inv�lidos', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '47', 'Endere�o do Sacado N�o Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '48', 'CEP Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '49', 'CEP Sem Pra�a de Cobran�a (N�o Localizado)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '50', 'CEP Referente a um Banco Correspondente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '51', 'CEP incompat�vel com a Unidade da Federa��o', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '52', 'Unidade da Federa��o Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '53', 'Tipo/N�mero de Inscri��o do Sacador/Avalista Inv�lidos', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '54', 'Sacador/Avalista N�o Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '55', 'Nosso n�mero no Banco Correspondente N�o Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '56', 'C�digo do Banco Correspondente N�o Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '57', 'C�digo da Multa Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '58', 'Data da Multa Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '59', 'Valor/Percentual da Multa Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '60', 'Movimento para T�tulo N�o Cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '61', 'Altera��o da Ag�ncia Cobradora/DV Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '62', 'Tipo de Impress�o Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '63', 'Entrada para T�tulo j� Cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '64', 'Entrada Inv�lida para Cobran�a Caucionada', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '65', 'CEP do Sacado n�o encontrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '66', 'Agencia Cobradora n�o encontrada', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '67', 'Agencia Cedente n�o encontrada', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '68', 'Movimenta��o inv�lida para t�tulo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '69', 'Altera��o de dados inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '70', 'Apelido do cliente n�o cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '71', 'Erro na composi��o do arquivo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '72', 'Lote de servi�o inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '73', 'C�digo do Cedente inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '74', 'Cedente n�o pertencente a Cobran�a Eletr�nica', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '75', 'Nome da Empresa inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '76', 'Nome do Banco inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '77', 'C�digo da Remessa inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '78', 'Data/Hora Gera��o do arquivo inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '79', 'N�mero Sequencial do arquivo inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '80', 'Vers�o do Lay out do arquivo inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '81', 'Literal REMESSA-TESTE - V�lido s� p/ fase testes', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '82', 'Literal REMESSA-TESTE - Obrigat�rio p/ fase testes', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '83', 'Tp N�mero Inscri��o Empresa inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '84', 'Tipo de Opera��o inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '85', 'Tipo de servi�o inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '86', 'Forma de lan�amento inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '87', 'N�mero da remessa inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '88', 'N�mero da remessa menor/igual remessa anterior', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '89', 'Lote de servi�o divergente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '90', 'N�mero sequencial do registro inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '91', 'Erro seq de segmento do registro detalhe', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '92', 'Cod movto divergente entre grupo de segm', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '93', 'Qtde registros no lote inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '94', 'Qtde registros no lote divergente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '95', 'Qtde lotes no arquivo inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '96', 'Qtde lotes no arquivo divergente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '97', 'Qtde registros no arquivo inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '98', 'Qtde registros no arquivo divergente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '99', 'C�digo de DDD inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '02'));

  insert into ocorrenciacobrancaregistrada (k149_sequencial, k149_banco, k149_codigo, k149_descricao, k149_movimento)
    values
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AA', 'C�d Desconto Preenchido, Obrig Data e Valor/Perc', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AB', 'Cod Desconto Obrigat�rio p/ C�d Mov = 7', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AC', 'Forma de Cadastramento Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AD', 'Data de Desconto deve estar em Ordem Crescente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AE', 'Data de Desconto � Posterior a Data de Vencimento', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AF', 'T�tulo n�o est� com situa��o \'Em Aberto\'', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AG', 'T�tulo j� est� Vencido / Vencendo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AH', 'N�o existe desconto a ser cancelado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AI', 'Data solicitada p/ Prot/Dev � anterior a data atual', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AJ', 'C�digo do Sacado Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AK', 'N�mero da Parcela Invalida ou Fora de Sequencia', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AL', 'Estorno de Envio N�o Permitido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'AM', 'Nosso Numero Fora de Sequencia', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VA', 'Arq.Ret.Inexis. P/ Redisp. Nesta Dt/Nro', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VB', 'Registro Duplicado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VC', 'Cedente deve ser padr�o CNAB240', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VD', 'Ident. Banco Sacado Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VE', 'Num Docto Cobr Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VF', 'Vlr/Perc a ser concedido inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VG', 'Data de Inscri��o Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VH', 'Data Movto Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VI', 'Data Inicial Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VJ', 'Data Final Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VK', 'Banco de Sacado j� cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VL', 'Cedente n�o cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VM', 'N�mero de Lote Duplicado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VN', 'Forma de Emiss�o de Bloqueto Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VO', 'Forma Entrega Bloqueto Inv�lida p/ Emiss�o via Banco', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VP', 'Forma Entrega Bloqueto Invalida p/ Emiss�o via Cedente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VQ', 'Op��o para Endosso Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VR', 'Tipo de Juros ao M�s Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VS', 'Percentual de Juros ao M�s Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VT', 'Percentual / Valor de Desconto Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VU', 'Prazo de Desconto Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VV', 'Preencher Somente Percentual ou Valor', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VW', 'Prazo de Multa Invalido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VX', 'Perc. Desconto tem que estar em ordem decrescente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VY', 'Valor Desconto tem que estar em ordem descrescente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'VZ', 'Dias/Data desconto tem que estar em ordem decrescente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WA', 'Vlr Contr p/ aquisi��o de Bens Inv�lid', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WB', 'Vlr Contr p/ Fundo de Reserva Inv�lid', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WC', 'Vlr Rend. Aplica��es Financ Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WD', 'Valor Multa/Juros Monetarios Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WE', 'Valor Premios de Seguro Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WF', 'Valor Custas Judiciais Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WG', 'Valor Reembolso de Despesas Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WH', 'Valor Outros Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WI', 'Valor de Aquisi��o de Bens Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WJ', 'Valor Devolvido ao Consorciado Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WK', 'Vlr Desp. Registro de Contrato Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WL', 'Valor de Rendimentos Pagos Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WM', 'Data de Descri��o Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WN', 'Valor do Seguro Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WO', 'Data de Vencimento Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WP', 'Data de Nascimento Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WQ', 'CPF/CNPJ do Aluno Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WR', 'Data de Avalia��o Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WS', 'CPF/CNPJ do Locatario Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WT', 'Literal da Remessa Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WU', 'Tipo de Registro Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WV', 'Modelo Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WW', 'C�digo do Banco de Sacados Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WX', 'Banco de Sacados n�o Cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WY', 'Qtde dias para Protesto tem que estar entre 2 e 90', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'WZ', 'N�o existem Sacados para este Banco', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XA', 'Pre�o Unitario do Produto Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XB', 'Pre�o Total do Produto Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XC', 'Valor Atual do Bem Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XD', 'Quantidade de Bens Entregues Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XE', 'Quantidade de Bens Distribuidos Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XF', 'Quantidade de Bens n�o Distribuidos Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XG', 'N�mero da Pr�xima Assembl�ia Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XH', 'Horario da Pr�xima Assembl�ia Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XI', 'Data da Pr�xima Assembl�ia Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XJ', 'N�mero de Ativos Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XK', 'N�mero de Desistentes Excluidos Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XL', 'N�mero de Quitados Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XM', 'N�mero de Contemplados Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XN', 'N�mero de n�o Contemplados Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XO', 'Data da �ltima Assembl�ia Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XP', 'Quantidade de Presta��es Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XQ', 'Data de Vencimento da Parcela Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XR', 'Valor da Amortiza��o Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XS', 'C�digo do Personalizado Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XT', 'Valor da Contribui��o Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XU', 'Percentual da Contribui��o Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XV', 'Valor do Fundo de Reserva Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XW', 'N�mero Parcela Inv�lido ou Fora de Sequ�ncia', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XX', 'Percentual Fundo de Reserva Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XY', 'Prz Desc/Multa Preenchido, Obrigat.Perc. ou Valor', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'XZ', 'Valor Taxa de Administra��o Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YA', 'Data de Juros Inv�lida ou N�o Informada', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YB', 'Data Desconto Inv�lida ou N�o Informada', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YC', 'E-mail Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YD', 'C�digo de Ocorr�ncia Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YE', 'Sacado j� Cadastrado (Banco de Sacados)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YF', 'Sacado n�o Cadastrado (Banco de Sacados)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YG', 'Remessa Sem Registro Tipo 9', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YH', 'Identifica��o da Solicita��o Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YI', 'Quantidade Bloquetos Solicitada Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YJ', 'Trailler do Arquivo n�o Encontrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YK', 'Tipo Inscri��o do Responsable Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YL', 'N�mero Inscri��o do Responsable Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YM', 'Ajuste de Vencimento Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YN', 'Ajuste de Emiss�o Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YO', 'C�digo de Modelo Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YP', 'V�a de Entrega Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YQ', 'Esp�cie Banco de Sacado Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YR', 'Aceite Banco de Sacado Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YS', 'Sacado j� Cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YT', 'Sacado n�o Cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YU', 'N�mero do Telefone Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YV', 'CNPJ do Condom�nio Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YW', 'Indicador de Registro de T�tulo Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YX', 'Valor da Nota Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YY', 'Qtde de dias para Devolu��o tem que estar entre 5 e 120', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'YZ', 'Quantidade de Produtos Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZA', 'Perc. Taxa de Administra��o Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZB', 'Valor do Seguro Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZC', 'Percentual do Seguro Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZD', 'Valor da Diferen�a da Parcela Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZE', 'Perc. Da Diferen�a da Parcela Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZF', 'Valor Reajuste do Saldo de Caixa Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZG', 'Perc. Reajuste do Saldo de Caixa Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZH', 'Valor Total a Pagar Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZI', 'Percentual ao Total a Pagar Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZJ', 'Valor de Outros Acr�scimos Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZK', 'Perc. De Outros Acr�scimos Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZL', 'Valor de Outras Dedu��es Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZM', 'Perc. De Outras Dedu��es Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZN', 'Valor da Contribui��o Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZO', 'Percentual da Contribui��o Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZP', 'Valor de Juros/Multa Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZQ', 'Percentual de Juros/Multa Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZR', 'Valor Cobrado Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZS', 'Percentual Cobrado Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZT', 'Valor Disponibilizado em Caixa Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZU', 'Valor Dep�sito Bancario Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZV', 'Valor Aplica��es Financieras Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZW', 'Data/Valor Preenchidos, Obrigat�rio D�digo Desconto', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZX', 'Valor Cheques em Cobran�a Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZY', 'Desconto c/ valor Fixo, Obrigat�rio Valor do T�tulo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', 'ZZ', 'C�digo Movimento Inv�lido p/ Segmento Y8', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '01', 'C�digo do Banco Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '02', 'C�digo do Registro Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '03', 'C�digo do Segmento Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '04', 'C�digo do Movimento n�o Permitido p/ Carteira', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '05', 'C�digo do Movimento Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '06', 'Tipo N�mero Inscri��o Cedente Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '07', 'Agencia/Conta/DV Inv�lidos', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '08', 'Nosso N�mero Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '09', 'Nosso N�mero Duplicado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '10', 'Carteira Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '11', 'Data de Gera��o Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '12', 'Tipo de Documento Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '13', 'Identif. Da Emiss�o do Bloqueto Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '14', 'Identif. Da Distribui��o do Bloqueto Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '15', 'Caracter�sticas Cobran�a Incompat�veis', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '16', 'Data de Vencimento Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '17', 'Data de Vencimento Anterior a Data de Emiss�o', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '18', 'Vencimento fora do prazo de opera��o', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '19', 'T�tulo a Cargo de Bco Correspondentes c/ Vencto Inferior a XX Dias', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '20', 'Valor do T�tulo Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '21', 'Esp�cie do T�tulo Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '22', 'Esp�cie do T�tulo N�o Permitida para a Carteira', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '23', 'Aceite Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '24', 'Data da Emiss�o Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '25', 'Data da Emiss�o Posterior a Data de Entrada', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '26', 'C�digo de Juros de Mora Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '27', 'Valor/Taxa de Juros de Mora Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '28', 'C�digo do Desconto Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '29', 'Valor do Desconto Maior ou Igual ao Valor do T�tulo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '30', 'Desconto a Conceder N�o Confere', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '31', 'Concess�o de Desconto - J� Existe Desconto Anterior', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '32', 'Valor do IOF Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '33', 'Valor do Abatimento Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '34', 'Valor do Abatimento Maior ou Igual ao Valor do T�tulo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '35', 'Valor Abatimento a Conceder N�o Confere', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '36', 'Concess�o de Abatimento - J� Existe Abatimento Anterior', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '37', 'C�digo para Protesto Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '38', 'Prazo para Protesto Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '39', 'Pedido de Protesto N�o Permitido para o T�tulo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '40', 'T�tulo com Ordem de Protesto Emitida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '41', 'Pedido Cancelamento/Susta��o p/ T�tulos sem Instru��o Protesto', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '42', 'C�digo para Baixa/Devolu��o Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '43', 'Prazo para Baixa/Devolu��o Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '44', 'C�digo da Moeda Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '45', 'Nome do Sacado N�o Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '46', 'Tipo/N�mero de Inscri��o do Sacado Inv�lidos', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '47', 'Endere�o do Sacado N�o Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '48', 'CEP Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '49', 'CEP Sem Pra�a de Cobran�a (N�o Localizado)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '50', 'CEP Referente a um Banco Correspondente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '51', 'CEP incompat�vel com a Unidade da Federa��o', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '52', 'Unidade da Federa��o Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '53', 'Tipo/N�mero de Inscri��o do Sacador/Avalista Inv�lidos', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '54', 'Sacador/Avalista N�o Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '55', 'Nosso n�mero no Banco Correspondente N�o Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '56', 'C�digo do Banco Correspondente N�o Informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '57', 'C�digo da Multa Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '58', 'Data da Multa Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '59', 'Valor/Percentual da Multa Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '60', 'Movimento para T�tulo N�o Cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '61', 'Altera��o da Ag�ncia Cobradora/DV Inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '62', 'Tipo de Impress�o Inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '63', 'Entrada para T�tulo j� Cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '64', 'Entrada Inv�lida para Cobran�a Caucionada', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '65', 'CEP do Sacado n�o encontrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '66', 'Agencia Cobradora n�o encontrada', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '67', 'Agencia Cedente n�o encontrada', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '68', 'Movimenta��o inv�lida para t�tulo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '69', 'Altera��o de dados inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '70', 'Apelido do cliente n�o cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '71', 'Erro na composi��o do arquivo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '72', 'Lote de servi�o inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '73', 'C�digo do Cedente inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '74', 'Cedente n�o pertencente a Cobran�a Eletr�nica', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '75', 'Nome da Empresa inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '76', 'Nome do Banco inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '77', 'C�digo da Remessa inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '78', 'Data/Hora Gera��o do arquivo inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '79', 'N�mero Sequencial do arquivo inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '80', 'Vers�o do Lay out do arquivo inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '81', 'Literal REMESSA-TESTE - V�lido s� p/ fase testes', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '82', 'Literal REMESSA-TESTE - Obrigat�rio p/ fase testes', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '83', 'Tp N�mero Inscri��o Empresa inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '84', 'Tipo de Opera��o inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '85', 'Tipo de servi�o inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '86', 'Forma de lan�amento inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '87', 'N�mero da remessa inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '88', 'N�mero da remessa menor/igual remessa anterior', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '89', 'Lote de servi�o divergente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '90', 'N�mero sequencial do registro inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '91', 'Erro seq de segmento do registro detalhe', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '92', 'Cod movto divergente entre grupo de segm', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '93', 'Qtde registros no lote inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '94', 'Qtde registros no lote divergente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '95', 'Qtde lotes no arquivo inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '96', 'Qtde lotes no arquivo divergente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '97', 'Qtde registros no arquivo inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '98', 'Qtde registros no arquivo divergente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '104', '99', 'C�digo de DDD inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03'));

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
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '01', 'C�digo do Banco inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '02', 'C�digo de registro detalhe inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '03', 'C�digo do Segmento inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '04', 'C�digo do movimento n�o permitido para a carteira', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '05', 'C�digo do movimento inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '06', 'Tipo/N�mero de inscri��o do Benefici�rio inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '07', 'Ag�ncia/conta/DV inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '08', 'Nosso N�mero inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '09', 'Nosso n�mero duplicado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '10', 'Carteira inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '11', 'Forma de cadastramento do t�tulo inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '12', 'Tipo de documento inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '13', 'Identifica��o da emiss�o do bloqueto inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '14', 'Identifica��o da distribui��o do bloqueto inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '15', 'Caracter�sticas da cobran�a incompat�veis - se a carteira e a moeda forem v�lidas e n�o existir esp�cie para combina��o carteira/moeda:\nEsp�cie inv�lida\nInstru��o inv�lida\nSem cadastro de esp�cie (8355, 8251, 8150, 8352)\nData de registro inv�lida\nIntervalo entre as datas de registro e processamento � maior que o definido pelo sistema\nC�digo de instru��o duplo\nPra�a inv�lida\nCobradora inv�lida\nCidade inv�lida\nAg�ncia ou NC da Ag�ncia do Benefici�rio inv�lido\nEsp�cie inv�lida para o CEP (CUBRS para CEP de outro estado)\nValor, data, taxa ou c�digo de instru��o inv�lido\nBenefici�rio n�o cadastrado\nOpera��o bloqueada para p/a esp�cie\nValor do t�tulo n�o confere para devolu��o/baixa\nAltera��es n�o permitidas para o t�tulo\nAltera��es n�o permitidas para t�tulo em cart�rio\nBloqueio Administrativo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '16', 'Data de vencimento inv�lida:\nVerifica se a data � num�rica, diferente de zeros e em formato v�lido (ddmmaaaa).\nVerifica se a altera��o de vencimento � permitida para o t�tulo (cart�rio, carteira)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '17', 'Data de vencimento anterior a data de emiss�o', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '18', 'Vencimento fora do prazo de opera��o', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '19', 'T�tulo a cargo de Bancos Correspondentes com vencimento inferior a XX dias', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '20', 'Valor do t�tulo inv�lido (n�o num�rico)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '21', 'Esp�cie do t�tulo inv�lida (arquivo de registro)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '22', 'Esp�cie n�o permetida para a carteira', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '23', 'Aceite inv�lido - verifica conte�do v�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '24', 'Data de emiss�o inv�lida - verifica se a data � num�rica e se est� no formato v�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '25', 'Data de emiss�o posterior a data de processamento', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '26', 'C�digo de juros de mora inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '27', 'Valor/taxa de juros de mora inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '28', 'C�digo do desconto inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '29', 'Valor do desconto maior ou igual ao valor do t�tulo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '30', 'Desconto a conceder n�o confere:\nInstru��o de desconto inv�lida\nTaxa ou valor inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '32', 'Valor do IOF inv�lido:\nVerifica se o campo � num�rico\nQuando for moeda AA - CUB e carteira 1 - Cobran�a Simples, verifica se � menor ou igual a 99999,99', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '33', 'Valor do abatimento inv�lido - para registro de t�tulo verifica se o campo � num�rico e para concess�o/cancelamento de abatimento indica o erro se:\nInstru��o de abatimento inv�lida\nValor inv�lido na instru��o abatimento\nMovimento n�o for permitido para o t�tulo (t�tulo em cart�rio ou carteira desconto)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '34', 'Valor do abatimento maior ou igual ao valor do t�tulo', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '35', 'Abatimento a conceder n�o confere', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '36', 'Concess�o de abatimento - j� existe abatimento anterior', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '37', 'C�digo para protesto inv�lido - rejeita o t�tulo se o campo for diferente de branco, 0, 1 ou 3', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '38', 'Prazo para protesto inv�lido - se o c�digo for \'1\' verifica se o campo � num�rico', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '39', 'Pedido de protesto n�o permitido para o t�tulo - n�o permite protesto para as carteiras R, S e N', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '40', 'T�tulo com ordem de protesto emitida (para retorno de altera��o)', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '41', 'Pedido de cancelamento/susta��o de protesto inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '42', 'C�digo para baixa/devolu��o ou instru��o inv�lido - verifica se o c�digo � branco, 0, 1 ou 2', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '43', 'Prazo para baixa/devolu��o inv�lido - se o c�digo � \'1\' verifica se o campo prazo � num�rico', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '44', 'C�digo da moeda inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '45', 'Nome do Pagador inv�lido ou altera��o do Pagador n�o permitida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '46', 'Tipo/n�mero de inscri��o do Pagador inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '47', 'Endere�o n�o informado ou altera��o de endere�o n�o permitida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '48', 'CEP inv�lido ou altera��o de CEP n�o permitida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '49', 'CEP sem pra�a de cobran�a ou altera��o de cidade n�o permitida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '50', 'CEP referente a um Banco Correspondente', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '51', 'CEP incompat�vel com a unidade da federa��o', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '52', 'Unidade de Federa��o inv�lida ou altera��o de UF n�o permitida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '53', 'Tipo/N�mero de inscri��o do Sacador/Avalista inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '54', 'Sacador/Avalista n�o informado - para esp�cie AD o nome do Sacador � obrigat�rio', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '57', 'C�digo da multa inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '58', 'Data da multa inv�lida', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '59', 'Valor/percentual da multa inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '60', 'Movimento para t�tulo n�o cadastrado - altera��o ou devolu��o', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '62', 'Tipo de impress�o inv�lido - Segmento 3S\nRejeita quando a mensagem gen�rica possuir o tipo de impress�o diferente de B,C,E,G e no cadastro for \'N\'\nRejeita quando a mensagem espec�fica possuir o tipo de impress�o diferente de 2,3,D,F', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '63', 'Entrada para t�tulo j� cadastrado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '79', 'Data de juros de mora inv�lido - valida data ou prazo na instru��o de juros', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '80', 'Data do desconto inv�lida - valida data ou prazo da instru��o de desconto', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '81', 'CEP inv�lido do Sacador', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '83', 'Tipo/N�mero de inscri��o do Sacador inv�lido', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '84', 'Sacador n�o informado', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03')),
  (nextval('ocorrenciacobrancaregistrada_k149_sequencial_seq'), '041', '86', 'Seu n�mero inv�lido (para retorno de altera��o).', (select k169_sequencial from movimentoocorrenciacobrancaregistrada where k169_codigo = '03'));
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
  insert into db_layoutcampos values (15317, 889, 'banco', 'C�DIGO DO BANCO NA COMPENSA��O', 2, 1, '', 3, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15318, 889, 'lote_servico', 'LOTE DE SERVI�O', 2, 4, '', 4, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15319, 889, 'tipo_registro', 'TIPO DE REGISTRO', 1, 8, '3', 1, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15320, 889, 'sequencial', 'N�MERO SEQU�NCIAL DO REGISTRO NO LOTE', 2, 9, '', 5, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15321, 889, 'segmento', 'C�DIGO SEGMENTO DO REGISTRO DETALHE', 1, 14, 'T', 1, true, true, 'd', '', 0 );
  insert into db_layoutcampos values (15322, 889, 'exclusivo_febraban', 'USO EXCLUSIVO FEBRABAN', 1, 15, '', 1, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15323, 889, 'codigo_movimento', 'C�DIGO MOVIMENTO RETORNO', 1, 16, '', 2, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15324, 889, 'codigo_agencia', 'C�DIGO DA AG�NCIA', 2, 18, '', 5, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15325, 889, 'dv_agencia', 'D�GITO VERIFICADOR AG�NCIA', 1, 23, '', 1, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15326, 889, 'uso_exclusivo_banco', 'USO EXCLUSIVO DO BANCO', 1, 24, '', 34, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15327, 889, 'codigo_carteira', 'C�DIGO DA CARTEIRA', 1, 58, '', 1, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15328, 889, 'uso_exclusivo_banco_1', 'USO EXCLUSIVO DO BANCO', 1, 59, '', 15, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15329, 889, 'data_vencimento', 'DATA DE VENCIMENTO', 1, 74, '', 8, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15330, 889, 'valor', 'VALOR NOMINAL DO T�TULO', 1, 82, '', 15, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15331, 889, 'codigo_banco', 'C�DIGO DO BANCO', 2, 97, '', 3, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15332, 889, 'codigo_agencia_cobranca', 'C�DIGO DA AG�NCIA COBR/RECEB', 1, 100, '', 5, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15333, 889, 'dv_agencia_cobranca', 'D�GITO VERIFICADOR DA AG�NCIA DA COBR', 1, 105, '', 1, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15334, 889, 'identificacao_titulo', 'IDENTIFICA��O DO T�TULO NA EMPRESA', 1, 106, '', 25, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15335, 889, 'codigo_moeda', 'C�DIGO DA MOEDA', 2, 131, '', 2, false, true, 'e', '', 0 );
  insert into db_layoutcampos values (15336, 889, 'tipo_inscricao', 'TIPO DE INSCRI��O DO SACADO', 1, 133, '', 1, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15337, 889, 'numero_inscricao', 'N�MERO DE INSCRI��O DO SACADO', 1, 134, '', 15, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15338, 889, 'nome', 'NOME DO SACADO', 1, 149, '', 40, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15339, 889, 'uso_exclusivo_febraban_1', 'USO EXCLUSIVO FEBRABAN', 1, 189, '', 10, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15340, 889, 'valor_tarifa', 'VALOR DAS TARIFAS/CUSTAS', 1, 199, '', 15, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15341, 889, 'motivo', 'MOTIVO DA OCORR�NCIA', 1, 214, '', 10, false, true, 'd', '', 0 );
  insert into db_layoutcampos values (15342, 889, 'uso_exclusivo_febraban_2', 'USO EXCLUSIVO DA FEBRABAN', 1, 224, '', 17, false, true, 'd', '', 0 );
$$);

update db_layoutcampos set db52_ident = true where db52_codigo = 14975;

select fc_executa_ddl($$
  insert into db_sysarquivo values (3991, 'emissaogeralparcelaunica', 'Parcela �nica usada na emiss�o geral', 'tr05', '2016-11-16', 'Parcela �nica', 0, 'f', 'f', 'f', 'f' );
  insert into db_sysarqmod values (46,3991);
  insert into db_syscampo values(22155,'tr05_sequencial','int4','C�digo sequencial do v�nculo entre Parcela �nica e Emiss�o geral','0', 'Parcela �nica',10,'f','f','f',1,'text','Parcela �nica');
  insert into db_syscampo values(22156,'tr05_emissaogeral','int4','C�digo da Emiss�o Geral','0', 'Emiss�o Geral',10,'f','f','f',1,'text','Emiss�o Geral');
  insert into db_syscampo values(22157,'tr05_dataoperacao','date','Data de Opera��o da Parcela �nica','null', 'Data de Opera��o',10,'f','f','f',0,'text','Data de Opera��o');
  insert into db_syscampo values(22158,'tr05_datavencimento','date','Data de Vencimento da Parcela �nica','null', 'Data de Vencimento',10,'f','f','f',1,'text','Data de Vencimento');
  insert into db_syscampo values(22159,'tr05_percentual','int4','Percentual de desconto da Parcela �nica usada na Emiss�o Geral','0', 'Percentual',10,'f','f','f',1,'text','Percentual');
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

  insert into db_syscampo values(22164,'k00_nossonumero','varchar(20)','Nosso N�mero','0', 'Nosso N�mero',10,'f','f','f',0,'text','Nosso N�mero');
  delete from db_sysarqcamp where codarq = 1575;
  insert into db_sysarqcamp values(1575,361,1,0);
  insert into db_sysarqcamp values(1575,9206,2,0);
  insert into db_sysarqcamp values(1575,9207,3,0);
  insert into db_sysarqcamp values(1575,22164,4,0);

  insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10336 ,'Emiss�o Geral de IPTU' ,'Emiss�o Geral de IPTU' ,'cad4_emiteiptuNovo.php' ,'1' ,'1' ,'Emiss�o Geral de IPTU' ,'true' );
  insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,10336 ,475 ,578 );
  update db_itensmenu set id_item = 1576 , descricao = 'Emiss�o Geral de IPTU' , help = 'Emiss�o Geral de IPTU' , funcao = 'cad4_emiteiptu.php' , itemativo = '1' , manutencao = '1' , desctec = 'Gera layout dos carnes de iptu.' , libcliente = 'false' where id_item = 1576;
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