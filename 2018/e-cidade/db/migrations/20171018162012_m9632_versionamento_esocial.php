<?php

use Classes\PostgresMigration;

class M9632VersionamentoEsocial extends PostgresMigration
{

    public function up()
    {
        $sSql = <<< SQL
        -- esocialformulariotipo
        insert into db_sysarquivo values (1010231, 'esocialformulariotipo', 'Tipo de Formulário do eSocial', 'rh209', '2017-10-17', 'Tipo de Formulário', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (29,1010231);

        insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1009469 ,'rh209_sequencial' ,'int4' ,'Código' ,'' ,'Código' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código' );
        insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010231 ,1009469 ,1 ,0 );

        insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1009470 ,'rh209_descricao' ,'varchar(100)' ,'Descrição do tipo' ,'' ,'Descrição' ,100 ,'false' ,'false' ,'false' ,0 ,'text' ,'Descrição' );
        insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010231 ,1009470 ,2 ,0 );

        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010231,1009469,1,1009469);

        insert into db_syssequencia values(1000694, 'esocialformulariotipo_rh209_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1000694 where codarq = 1010231 and codcam = 1009469;
        -- /esocialformulariotipo


        -- esocialversao
        insert into db_sysarquivo values (1010232, 'esocialversao', 'Versão do eSocial em uso.', 'rh210', '2017-10-18', 'Versão do eSocial', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (29,1010232);

        insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1009471 ,'rh210_sequencial' ,'int4' ,'Código' ,'' ,'Código' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código' );
        insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010232 ,1009471 ,1 ,0 );

        insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1009472 ,'rh210_versao' ,'varchar(10)' ,'Versão do eSocial' ,'' ,'Versão' ,10 ,'false' ,'false' ,'false' ,0 ,'text' ,'Versão' );
        insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010232 ,1009472 ,2 ,0 );

        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010232,1009471,1,1009471);

        insert into db_syssequencia values(1000695, 'esocialversao_rh210_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1000695 where codarq = 1010232 and codcam = 1009471;
        -- /esocialversao


        -- esocialversaoformulario
        insert into db_sysarquivo values (1010233, 'esocialversaoformulario', 'Vincula um formulário a um tipo e versão do eSocial.', 'rh211', '2017-10-18', 'eSocial Versão do Formulário', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (29,1010233);

        insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1009474 ,'rh211_sequencial' ,'int4' ,'Código' ,'' ,'Código' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código' );
        insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010233 ,1009474 ,1 ,0 );

        insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1009475 ,'rh211_versao' ,'varchar(10)' ,'Versão' ,'' ,'Versão' ,10 ,'false' ,'false' ,'false' ,0 ,'text' ,'Versão' );
        insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010233 ,1009475 ,2 ,0 );

        insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1009476 ,'rh211_avaliacao' ,'int4' ,'Formulário do eSocial' ,'' ,'Formulário' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Formulário' );
        insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010233 ,1009476 ,3 ,0 );

        insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1009477 ,'rh211_esocialformulariotipo' ,'int4' ,'Tipo de Formulário do eSocial' ,'' ,'Tipo de Formulário' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Tipo de Formulário' );
        insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010233 ,1009477 ,4 ,0 );

        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010233,1009474,1,1009474);
        insert into db_sysforkey values(1010233,1009476,1,2980,0);
        insert into db_sysforkey values(1010233,1009477,1,1010231,0);

        insert into db_syssequencia values(1000696, 'esocialversaoformulario_rh211_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1000696 where codarq = 1010233 and codcam = 1009474;
        -- /esocialversaoformulario

        create sequence recursoshumanos.esocialformulariotipo_rh209_sequencial_seq
        increment 1
        minvalue 1
        maxvalue 9223372036854775807
        start 1
        cache 1;

        create sequence recursoshumanos.esocialversao_rh210_sequencial_seq
        increment 1
        minvalue 1
        maxvalue 9223372036854775807
        start 1
        cache 1;

        create sequence recursoshumanos.esocialversaoformulario_rh211_sequencial_seq
        increment 1
        minvalue 1
        maxvalue 9223372036854775807
        start 1
        cache 1;

        create table recursoshumanos.esocialformulariotipo(
        rh209_sequencial    int4 not null default nextval('esocialformulariotipo_rh209_sequencial_seq'),
        rh209_descricao     varchar(100) not null,
        constraint esocialformulariotipo_sequ_pk primary key (rh209_sequencial));

        create table recursoshumanos.esocialversao(
        rh210_sequencial    int4 not null default nextval('esocialversao_rh210_sequencial_seq'),
        rh210_versao        varchar(10) not null,
        constraint esocialversao_sequ_pk primary key (rh210_sequencial));

        create table recursoshumanos.esocialversaoformulario(
        rh211_sequencial                 int4 not null default nextval('esocialversaoformulario_rh211_sequencial_seq'),
        rh211_versao                     varchar(10) not null ,
        rh211_avaliacao                  int4 not null ,
        rh211_esocialformulariotipo int4 not null,
        constraint esocialversaoformulario_sequ_pk primary key (rh211_sequencial));

        alter table esocialversaoformulario
        add constraint esocialversaoformulario_avaliacao_fk foreign key (rh211_avaliacao)
        references avaliacao;

        alter table esocialversaoformulario
        add constraint esocialversaoformulario_esocialformulariotipo_fk foreign key (rh211_esocialformulariotipo)
        references esocialformulariotipo;

        insert into esocialformulariotipo values(1, 'Empregador');
        insert into esocialformulariotipo values(2, 'Rubrica');
        insert into esocialformulariotipo values(3, 'Servidor');
        select setval('esocialformulariotipo_rh209_sequencial_seq', 3);

        insert into esocialversaoformulario values(nextval('esocialversaoformulario_rh211_sequencial_seq'), '2.1', 3000009, 1);
        insert into esocialversaoformulario values(nextval('esocialversaoformulario_rh211_sequencial_seq'), '2.1', 3000010, 2);
        insert into esocialversaoformulario values(nextval('esocialversaoformulario_rh211_sequencial_seq'), '2.1', 3000008, 3);

        insert into esocialversaoformulario values(nextval('esocialversaoformulario_rh211_sequencial_seq'), '2.3', 3000010, 2);
        insert into esocialversao values(nextval('esocialversao_rh210_sequencial_seq'), '2.1');
SQL;
        $this->execute($sSql);
    }

    public function down()
    {
        $sSql = <<<SQL
            drop table if exists esocialversaoformulario;

            drop table if exists esocialformulariotipo;

            drop table if exists esocialversao;

            drop sequence if exists esocialversaoformulario_rh211_sequencial_seq;

            drop sequence if exists esocialversao_rh210_sequencial_seq;

            drop sequence if exists esocialformulariotipo_rh209_sequencial_seq;

            delete from db_sysforkey where codarq in(1010233, 1010232, 1010231);
            delete from db_sysprikey where codarq in(1010233, 1010232, 1010231);
            delete from db_sysarqcamp where codarq in(1010233, 1010232, 1010231);
            delete from db_sysarqmod where codarq in(1010233, 1010232, 1010231);
            delete from db_sysarquivo where codarq in(1010233, 1010232, 1010231);

            -- esocialformulariotipo
            delete from db_syscampo where codcam in(1009469, 1009470);

            -- esocialversao
            delete from db_syscampo where codcam in(1009471, 1009472);

            -- esocialversaoformulario
            delete from db_syscampo where codcam in(1009474, 1009475, 1009476, 1009477);

            delete from db_syssequencia where codsequencia in(1000694, 1000695, 1000696);
SQL;
        $this->execute($sSql);
    }
}
