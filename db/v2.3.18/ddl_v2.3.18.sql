/**
 * --------------------------------------------------------------------------------------------------------------------
 * TIME A INICIO
 * --------------------------------------------------------------------------------------------------------------------
 */

/**
 * TIME A
 * Tarefa #80154
 */
CREATE SEQUENCE rhempenhofolhaexcecaoregra_rh128_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;


CREATE TABLE rhempenhofolhaexcecaoregra(
rh128_sequencial    int4 NOT NULL default 0,
rh128_descricao   varchar(100) ,
CONSTRAINT rhempenhofolhaexcecaoregra_sequ_pk PRIMARY KEY (rh128_sequencial));

alter table rhempenhofolhaexcecaorubrica
    add column rh74_codele int4  default null,
    add column rh74_tipofolha    int4  default 0,
    add column rh74_rhempenhofolhaexcecaoregra   int4 default null;

DROP INDEX if exists rhempenhofolhaexcecaorubrica_in;

CREATE UNIQUE INDEX rhempenhofolhaexcecaorubrica_in ON rhempenhofolhaexcecaorubrica(rh74_instit,rh74_rubric,rh74_anousu,rh74_tipofolha);

ALTER TABLE rhempenhofolhaexcecaorubrica
ADD CONSTRAINT rhempenhofolhaexcecaorubrica_rhempenhofolhaexcecaoregra_fk FOREIGN KEY (rh74_rhempenhofolhaexcecaoregra)
REFERENCES rhempenhofolhaexcecaoregra;

ALTER TABLE rhempenhofolhaexcecaorubrica
ADD CONSTRAINT rhempenhofolhaexcecaorubrica_ae_codele_fk FOREIGN KEY (rh74_codele, rh74_anousu)
REFERENCES orcelemento;


/**
 * Depara das exceções para empenhos
 */
insert into rhempenhofolhaexcecaoregra
            ( rh128_sequencial,
              rh128_descricao )
     select rh74_sequencial,
            rh74_rubric || ' - ' || trim( rh27_descr ) || ' / ' || rh74_anousu
       from rhempenhofolhaexcecaorubrica
            inner join rhrubricas on rh74_rubric = rh27_rubric
                                 and rh74_instit = rh27_instit
   order by rh74_sequencial;

update rhempenhofolhaexcecaorubrica set rh74_rhempenhofolhaexcecaoregra = rh74_sequencial;

select setval( 'rhempenhofolhaexcecaoregra_rh128_sequencial_seq', ( select max( rh128_sequencial ) from rhempenhofolhaexcecaoregra ) );

/**
 * FIM TAREFA #80154
 */

/**
 * TIME A
 * Tarefa #81921
 */

CREATE SEQUENCE bancohoras_rh126_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE bancohoras(
rh126_sequencial  int8 NOT NULL default 0,
rh126_regist      int4 NOT NULL default 0,
rh126_soma        bool NOT NULL default 'f',
rh126_data        date NOT NULL default null,
rh126_horas       int4 NOT NULL default 0,
rh126_minutos     int4 NOT NULL default 0,
rh126_observacao  text ,
CONSTRAINT bancohoras_sequ_pk PRIMARY KEY (rh126_sequencial));

ALTER TABLE bancohoras
ADD CONSTRAINT bancohoras_regist_fk FOREIGN KEY (rh126_regist)
REFERENCES rhpessoal;

CREATE  INDEX bancohoras_regist_in ON bancohoras(rh126_regist);

/**
 * FIM TAREFA #81921
 */


/**
 * TAREFA 81917
 */

 /** COLUNA DE DATA PREVIDENCIA CFPESS **/
ALTER TABLE cfpess ADD COLUMN r11_datainiciovigenciarpps    date default null;


CREATE SEQUENCE regimeprevidenciainssirf_rh129_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;


CREATE TABLE regimeprevidenciainssirf(
rh129_sequencial    int4 NOT NULL default 0,
rh129_regimeprevidencia   int4 NOT NULL default 0,
rh129_codigo    int8 NOT NULL default 0,
rh129_instit    int4 default 0,
CONSTRAINT regimeprevidenciainssirf_sequ_pk PRIMARY KEY (rh129_sequencial));


ALTER TABLE regimeprevidenciainssirf
ADD CONSTRAINT regimeprevidenciainssirf_codigo_instit_fk FOREIGN KEY (rh129_codigo,rh129_instit)
REFERENCES inssirf;

ALTER TABLE regimeprevidenciainssirf
ADD CONSTRAINT regimeprevidenciainssirf_regimeprevidencia_fk FOREIGN KEY (rh129_regimeprevidencia)
REFERENCES regimeprevidencia;


-- INDICES

CREATE  INDEX regimeprevidenciainssirf_instit_in ON regimeprevidenciainssirf(rh129_instit);

CREATE  INDEX regimeprevidenciainssirf_codigo_in ON regimeprevidenciainssirf(rh129_codigo);

CREATE UNIQUE INDEX regimeprevidenciainssirf_codigo_instit_un ON regimeprevidenciainssirf(rh129_codigo,rh129_instit);

/**
 * Time A Tarefa #81921
 */
alter table tipoasse add column h12_vinculaperiodoaquisitivo bool default false;

CREATE SEQUENCE rhferiasassenta_rh131_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE rhferiasassenta(
 rh131_sequencial   int4 NOT NULL default 0,
 rh131_assenta    int4 NOT NULL default 0,
 rh131_rhferias   int4 default 0,
 CONSTRAINT rhferiasassenta_sequ_pk PRIMARY KEY (rh131_sequencial) );

ALTER TABLE rhferiasassenta
ADD CONSTRAINT rhferiasassenta_assenta_fk FOREIGN KEY (rh131_assenta)
REFERENCES assenta;

ALTER TABLE rhferiasassenta
ADD CONSTRAINT rhferiasassenta_rhferias_fk FOREIGN KEY (rh131_rhferias)
REFERENCES rhferias;

CREATE  INDEX rhferiasassenta_sequencial_in ON rhferiasassenta(rh131_sequencial);
CREATE UNIQUE INDEX rhferiasassenta_assenta_rhferias_un ON rhferiasassenta(rh131_assenta,rh131_rhferias);

/**
 * Fim tarefa #81921
 */


 
/**
* --------------------------------------------------------------------------------------------------------------------
* TIME C INICIO
* --------------------------------------------------------------------------------------------------------------------
*/
 
CREATE SEQUENCE avaliacaoclassificacao_ed335_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE avaliacaoclassificacao(
ed335_sequencial    int4 NOT NULL default 0,
ed335_trocaserie    int8 NOT NULL default 0,
ed335_disciplina    int8 NOT NULL default 0,
ed335_avaliacao   varchar(200) ,
CONSTRAINT avaliacaoclassificacao_sequ_pk PRIMARY KEY (ed335_sequencial));

ALTER TABLE avaliacaoclassificacao ADD CONSTRAINT avaliacaoclassificacao_trocaserie_fk FOREIGN KEY (ed335_trocaserie)
REFERENCES trocaserie;

ALTER TABLE avaliacaoclassificacao ADD CONSTRAINT avaliacaoclassificacao_disciplina_fk FOREIGN KEY (ed335_disciplina)
REFERENCES disciplina;

CREATE  INDEX avaliacaoclassificacao_trocaserie_in ON avaliacaoclassificacao(ed335_trocaserie);
CREATE  INDEX avaliacaoclassificacao_disciplina_in ON avaliacaoclassificacao(ed335_disciplina);

ALTER TABLE edu_parametros ADD COLUMN ed233_reclassificaetapaanterior bool default 'f';

ALTER TABLE historicomps ADD COLUMN ed62_observacao text;

ALTER TABLE historicompsfora ADD COLUMN ed99_observacao text;

ALTER TABLE matricula ADD COLUMN ed60_tipoingresso int4 default 1;

ALTER TABLE matricula ADD CONSTRAINT matricula_tipoingresso_fk FOREIGN KEY (ed60_tipoingresso) REFERENCES tipoingresso;
CREATE INDEX matricula_tipoingresso_in ON matricula(ed60_tipoingresso);


/**
* --------------------------------------------------------------------------------------------------------------------
* TIME C - FIM
* --------------------------------------------------------------------------------------------------------------------
*/
