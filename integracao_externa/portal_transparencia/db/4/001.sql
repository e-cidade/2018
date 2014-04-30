------------SERVIDOR------------------------------------------------------------------------------
CREATE TABLE servidores (
  id             integer NOT NULL, --matricula
  instituicao_id integer,
  nome           character varying(255) NOT NULL,
  cpf            character varying(14) NOT NULL,
  admissao       timestamp without time zone NOT NULL,
  rescisao       timestamp without time zone,
  CONSTRAINT servidores_id_pk PRIMARY KEY (id),
  CONSTRAINT servidores_instituicoes_fk FOREIGN KEY (instituicao_id) REFERENCES instituicoes(id)
);

--create index servidores_instituicao_in on servidores using btree ( instituicao );
create index servidores_nome_in        on servidores using btree ( nome );

CREATE TABLE servidor_movimentacoes (
  id           bigserial NOT NULL,
  servidor_id  integer NOT NULL, --
  ano          integer NOT NULL, --
  mes          integer NOT NULL, --
  salario_base numeric,

  cargo        character varying(255) NOT NULL,
  lotacao      character varying(255) NOT NULL,
  vinculo      character varying(255) NOT NULL,
  CONSTRAINT servidor_movimentacoes_id_pk         PRIMARY KEY (id), 
  CONSTRAINT servidor_movimentacoes_servidores_fk FOREIGN KEY (servidor_id) REFERENCES servidores(id)
);

create index servidores_ano_in     on servidor_movimentacoes using btree ( ano );
create index servidores_mes_in     on servidor_movimentacoes using btree ( mes );
create index servidores_cargo_in   on servidor_movimentacoes using btree ( cargo );
create index servidores_lotacao_in on servidor_movimentacoes using btree ( lotacao );
create index servidores_vinculo_in on servidor_movimentacoes using btree ( vinculo );
create index servidores_ano_mes_in on servidor_movimentacoes using btree ( ano, mes );

/*
ano         = 1996
mes         = 1
matricula   = 1
nome        = NILO SOARES GONCALVES
cpf         = 08705259053
cargo       = PREFEITO MUNICIPAL            
lotacao     = GABINETE DO PREFEITO
vinculo     = EXTRA QUADRO
admissao    = 1993-01-01
rescisao    = 1996-12-31
instituicao = PREFEITURA MUNICIPAL DE ALEGRETE
*/

----------FOLHA------------------------------------------------------------------------------------

CREATE TABLE folha_pagamento (
  id                        bigserial,
  servidor_movimentacao_id  bigint,
  rubrica                   character varying(10),
  descr_rubrica             character varying(255),
  valor                     numeric(20,2) NOT NULL,
  quantidade                numeric(20,2) NOT NULL,
  tiporubrica               character varying(255),
  tipofolha                 character varying(255),
  CONSTRAINT folha_pagamento_id_pk PRIMARY KEY (id),
  CONSTRAINT folha_pagamento_servidor_movimentacoes_fk FOREIGN KEY (servidor_movimentacao_id) REFERENCES servidor_movimentacoes(id)
);

/*
ano           | 1996
mes           | 1
matricula     | 1
rubrica       | R913
descr_rubrica | % IRRF S/SALARIO
valor         | 1053.45
quantidade    | 0
tiporubrica   | desconto
tipofolha     | salario
instit        | 1
*/
------------ASSENTAMENTOS--------------------------------------------------------------------------

CREATE TABLE assentamentos (
  id              bigserial NOT NULL,
  servidor_id     bigint    NOT NULL,
  descricao       character varying(255),
  numero_portaria character varying(50),
  ato_oficial     character varying(255),
  data_concessao  timestamp without time zone,
  data_termino    timestamp without time zone,
  quantidade_dias integer   NOT NULL,
  historico       text,
  CONSTRAINT assentamentos_id_pk PRIMARY KEY (id),
  CONSTRAINT assentamentos_servidores_fk FOREIGN KEY (servidor_id) REFERENCES servidores(id)
);
/*
h12_assent | 1
h16_regist | 654
h12_descr  | CONCEDE ESTABILIDADE
h16_nrport | P19881451
h16_atofic | 
h16_dtconc | 1988-12-22
h16_dtterm | 1988-12-22
h16_quant  | 0
h16_histor | 
*/
