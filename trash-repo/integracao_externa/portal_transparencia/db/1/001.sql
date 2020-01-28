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
                                       'Define-se como Despesa P�blica o conjunto de disp�ndios do Municipio ou de outra pessoa de direito p�blico para o funcionamento dos servi�os p�blicos. Nesse sentido, a despesa � parte do or�amento, ou seja, aquela em que se encontram classificadas todas as autoriza��es para gastos com as v�rias atribui��es e fun��es governamentais. Em outras palavras, as despesas p�blicas formam o complexo da distribui��o e emprego das receitas para custeio de diferentes setores da administra��o.',
                                       '/despesas',
                                       true);
                                       
                                       
insert into paginaprincipalitens values (nextval('paginaprincipalitens_id_seq'),
                                       'Receitas',
                                       'Receita P�blica � a soma de ingressos, impostos, taxas, contribui��es e outras fontes de recursos, arrecadados para atender �s despesas p�blicas.',
                                       '/receitas',
                                       true);
                                       
insert into paginaprincipalitens values (nextval('paginaprincipalitens_id_seq'),
                                       'Di�rias',
                                       'Define-se como Di�ria a indeniza��o que faz jus o servidor ou agente pol�tico que se deslocar, temporariamente, da respectiva localidade onde tem exerc�cio, a servi�o ou para participar de evento de interesse da administra��o p�blica, pr�via e formalmente autorizada pelo ordenador de despesas ou pessoa delegada por ele, destinada a cobrir as despesas de alimenta��o, hospedagem e locomo��o urbana (realizada por qualquer meio de transporte de cunho local).',
                                       '/despesas/loadDiarias',
                                       true);
                                       
insert into paginaprincipalitens values (nextval('paginaprincipalitens_id_seq'),
                                       'Outras Informa��es',
                                       'Espa�o destinado a publica��es da Entidade relacionadas a gest�o da transpar�ncia.',
                                       'outras_informacoes',
                                       true);
