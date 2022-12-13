<?php

use Classes\PostgresMigration;

class M9795ConfiguracaoEsocial extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<< SQL
insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
        values ( 10475 ,'Configuração' ,'Configuração' ,'' ,'1' ,'1' ,'Configuração' ,'true' ),
            ( 10476 ,'Envio do Certificado' ,'Envio do certificado' ,'eso4_enviocertificado001.php' ,'1' ,'1' ,'Envio do certificado digital para a api' ,'true' );

insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo )
        values ( 32 ,10475 ,494 ,10216 ),
            ( 10475 ,10476 ,1 ,10216 );
SQL
        );

        $this->dicionario();
        $this->estrutura();
    }

    private function dicionario()
    {
        $this->execute(<<< SQL
insert into db_sysarquivo values (1010244, 'esocialenvio', 'Itens enviados para o eSocial', 'rh213', '2017-12-04', 'Itens enviados para o eSocial', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (81,1010244);
insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
values ( 1009543 ,'rh213_sequencial' ,'int4' ,'Código' ,'' ,'Código' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código' ),
        ( 1009544 ,'rh213_evento' ,'int4' ,'Código do Evento' ,'' ,'Código do Evento' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código do Evento' ),
        ( 1009545 ,'rh213_empregador' ,'int4' ,'empregador' ,'' ,'empregador' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'empregador' ),
        ( 1009546 ,'rh213_responsavelpreenchimento' ,'varchar(255)' ,'Responsável Preenchimento' ,'' ,'Responsável Preenchimento' ,255 ,'false' ,'true' ,'false' ,0 ,'text' ,'Responsável Preenchimento' ),
        ( 1009547 ,'rh213_dados' ,'text' ,'Dados' ,'' ,'Dados' ,1 ,'false' ,'true' ,'false' ,0 ,'text' ,'Dados' ),
        ( 1009548 ,'rh213_md5' ,'varchar(32)' ,'MD5' ,'' ,'MD5' ,32 ,'false' ,'true' ,'false' ,0 ,'text' ,'MD5' ),
        ( 1009549 ,'rh213_situacao' ,'int4' ,'Situacao' ,'' ,'Situacao' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Situacao' );

insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia )
values ( 1010244, 1009543, 1, 0),
        ( 1010244, 1009544, 2, 0),
        ( 1010244, 1009545, 3, 0),
        ( 1010244, 1009546, 4, 0),
        ( 1010244, 1009547, 5, 0),
        ( 1010244, 1009548, 6, 0);

insert into db_syscampodef ( codcam ,defcampo ,defdescr )
values ( 1009549 ,'1' ,'Fila Local' ),
        ( 1009549 ,'2' ,'Enviado API' ),
        ( 1009549 ,'3' ,'Erro no Processamento' ),
        ( 1009549 ,'4' ,'Processado no eSocial' ),
        ( 1009549 ,'5' ,'Em análise no eSocial' );

insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010244 ,1009549 ,7 ,0 );
insert into db_syssequencia values(1000704, 'esocialenvio_rh213_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000704 where codarq = 1010244 and codcam = 1009543;
SQL
        );
    }

    private function estrutura()
    {
        $this->execute(<<< SQL
CREATE SEQUENCE esocialenvio_rh213_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

CREATE TABLE esocialenvio(
rh213_sequencial		int4 NOT NULL  default nextval('esocialenvio_rh213_sequencial_seq'),
rh213_evento		int4 NOT NULL ,
rh213_empregador		int4 NOT NULL ,
rh213_responsavelpreenchimento		varchar(255) NOT NULL ,
rh213_dados		text NOT NULL ,
rh213_md5		varchar(32) NOT NULL ,
rh213_situacao		int4 ,
CONSTRAINT esocialenvio_sequ_pk PRIMARY KEY (rh213_sequencial));

CREATE  INDEX esocialfila_evento_empregador_in ON esocialenvio(rh213_evento,rh213_empregador,rh213_responsavelpreenchimento);

SQL
        );
    }

    public function down()
    {
        $this->execute(<<< SQL
delete from db_menu where id_item_filho in (10475, 10476) AND modulo = 10216;
delete from db_itensmenu where id_item  in (10475, 10476);
SQL
        );
        // dow dicionario
        $this->execute(<<< SQL
delete from db_sysarqcamp where codcam in (1009543, 1009544, 1009545, 1009546, 1009547, 1009548, 1009549);
delete from db_syssequencia where codsequencia = 1000704;
delete from db_syscampodef where codcam = 1009549;
delete from db_syscampo where  codcam in (1009543, 1009544, 1009545, 1009546, 1009547, 1009548, 1009549);
delete from db_sysarqmod where codarq = 1010244;
delete from db_sysarquivo where codarq = 1010244;

SQL
        );

        // estrutura
        $this->execute(<<< SQL
drop table if exists esocialenvio cascade;
drop sequence if exists esocialenvio_rh213_sequencial_seq;
SQL
        );
    }
}
