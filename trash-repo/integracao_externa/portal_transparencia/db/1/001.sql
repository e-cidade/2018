CREATE SEQUENCE paginaprincipalitens_id_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE paginaprincipalitens(
id         int4          NOT NULL default 0,
descricao  varchar(100)  NOT NULL default 0,
resumo     text          NOT NULL default 0,
acao       varchar(100)  NOT NULL default 0,
habilitado boolean       NOT NULL default true,
CONSTRAINT paginaprincipalitens_sequ_pk PRIMARY KEY (id));

insert into paginaprincipalitens values (nextval('paginaprincipalitens_id_seq'),
                                       'Despesas',
                                       'Define-se como Despesa Pública o conjunto de dispêndios do Municipio ou de outra pessoa de direito público para o funcionamento dos serviços públicos. Nesse sentido, a despesa é parte do orçamento, ou seja, aquela em que se encontram classificadas todas as autorizações para gastos com as várias atribuições e funções governamentais. Em outras palavras, as despesas públicas formam o complexo da distribuição e emprego das receitas para custeio de diferentes setores da administração.',
                                       '/despesas',
                                       true);
                                       
                                       
insert into paginaprincipalitens values (nextval('paginaprincipalitens_id_seq'),
                                       'Receitas',
                                       'Receita Pública é a soma de ingressos, impostos, taxas, contribuições e outras fontes de recursos, arrecadados para atender às despesas públicas.',
                                       '/receitas',
                                       true);
                                       
insert into paginaprincipalitens values (nextval('paginaprincipalitens_id_seq'),
                                       'Diárias',
                                       'Define-se como Diária a indenização que faz jus o servidor ou agente político que se deslocar, temporariamente, da respectiva localidade onde tem exercício, a serviço ou para participar de evento de interesse da administração pública, prévia e formalmente autorizada pelo ordenador de despesas ou pessoa delegada por ele, destinada a cobrir as despesas de alimentação, hospedagem e locomoção urbana (realizada por qualquer meio de transporte de cunho local).',
                                       '/despesas/loadDiarias',
                                       true);
                                       
insert into paginaprincipalitens values (nextval('paginaprincipalitens_id_seq'),
                                       'Outras Informações',
                                       'Espaço destinado a publicações da Entidade relacionadas a gestão da transparência.',
                                       'outras_informacoes',
                                       true);
