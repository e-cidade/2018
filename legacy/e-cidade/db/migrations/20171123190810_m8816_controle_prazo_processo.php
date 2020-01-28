<?php

use Classes\PostgresMigration;

class M8816ControlePrazoProcesso extends PostgresMigration
{
    public function up()
    {
        $sSql = <<<EOL
    INSERT INTO db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 10471 ,'Notificação de Movimentação de Processos' ,'Configuração da notificação enviada ao usuário sobre processos vencidos.' ,'pro1_mensageriaprocesso001.php' ,'1' ,'1' ,'Configuração da notificação enviada ao usuário quando o mesmo possui processos vinculado a ele e estes estão vencidos.' ,'false' );
    INSERT INTO db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 8880 ,10471 ,5 ,604 );

    INSERT INTO db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 10472 ,'Gestão de Processos Vencidos' ,'Configuração dos responsáveis pelos processos vencidos.' ,'prot4_gestaoprocessosvencidos001.php' ,'1' ,'1' ,'Configuração dos responsáveis pelos processos vencidos.' ,'false' );
    INSERT INTO db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 8880 ,10472 ,6 ,604 );

    INSERT INTO db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 10474 ,'Processos Vencidos' ,'Relatório de processos vencidos.' ,'pro2_processosvencidos001.php' ,'1' ,'1' ,'Relatório de processos vencidos.' ,'false' );
    INSERT INTO db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 30 ,10474 ,471 ,604 );

    INSERT INTO db_sysarquivo VALUES (1010238, 'mensageriaprocesso', 'Armazena a configuração da mensagem de notificação do vencimento dos processos.', 'p101', '2017-11-23', 'Mensageria do Processo', 0, 'f', 'f', 'f', 'f' );
    INSERT INTO db_sysarqmod VALUES (4,1010238);
    INSERT INTO db_syscampo VALUES(1009522,'p101_sequencial','int4','Código sequencial.','0', 'Código',10,'f','f','f',1,'text','Código');
    INSERT INTO db_syscampo VALUES(1009523,'p101_assunto','varchar(255)','Assunto da mensagem de notificação.','', 'Assunto',255,'f','t','f',0,'text','Assunto');
    INSERT INTO db_syscampo VALUES(1009524,'p101_mensagem','text','Mensagem de notificação de processo vencido.','', 'Mensagem',1,'f','t','f',0,'text','Mensagem');
    INSERT INTO db_syscampo VALUES(1009525,'p101_notificarreceberprocesso','bool','Notifica um servidor quando um processo é transferido ou tramitado para ele.','f', 'Notificar ao Receber Processo',1,'f','f','f',5,'text','Notificar ao Receber Processo');
    INSERT INTO db_syscampo VALUES(1009526,'p101_notificardatavencimento','bool','Notifica o servidor quando o processo atingiu seu prazo limite para movimentação.','f', 'Notificar na Data de Vencimento',1,'f','f','f',5,'text','Notificar na Data de Vencimento');
    INSERT INTO db_syscampo VALUES(1009527,'p101_diasprazo','int4','Quantidade de dias que o servidor tem para movimentar um processo a partir de seu recebimento.','0', 'Dias de Prazo para Movimentação',10,'f','f','f',1,'text','Dias de Prazo para Movimentação');
    INSERT INTO db_sysarqcamp VALUES(1010238,1009522,1,0);
    INSERT INTO db_sysarqcamp VALUES(1010238,1009523,2,0);
    INSERT INTO db_sysarqcamp VALUES(1010238,1009524,3,0);
    INSERT INTO db_sysarqcamp VALUES(1010238,1009525,4,0);
    INSERT INTO db_sysarqcamp VALUES(1010238,1009526,5,0);
    INSERT INTO db_sysarqcamp VALUES(1010238,1009527,6,0);
    INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010238,1009522,1,1009522);
    INSERT INTO db_syssequencia VALUES(1000699, 'mensageriaprocesso_p101_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
    UPDATE db_sysarqcamp SET codsequencia = 1000699 WHERE codarq = 1010238 AND codcam = 1009522;
    
    INSERT INTO db_sysarquivo VALUES (1010240, 'gestaoprocessovencido', 'Armazena o usuário responsável pela gestão de processos e que pode visualizar no relatório de processos vencidos de todos os departamentos.', 'p102', '2017-11-24', 'Gestão de Processos Vencidos', 0, 'f', 'f', 'f', 'f' );
    INSERT INTO db_sysarqmod VALUES (4,1010240);
    INSERT INTO db_syscampo VALUES(1009530,'p102_sequencial','int4','Código sequencial.','0', 'Código',10,'f','f','f',1,'text','Código');
    INSERT INTO db_syscampo VALUES(1009531,'p102_db_usuarios','int4','Código do usuário responsável pelos processos vencidos.','0', 'Código do Usuário',10,'f','f','f',1,'text','Código do Usuário');
    INSERT INTO db_sysarqcamp VALUES(1010240,1009530,1,0);
    INSERT INTO db_sysarqcamp VALUES(1010240,1009531,2,0);
    INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010240,1009530,1,1009530);
    INSERT INTO db_sysforkey VALUES(1010240,1009531,1,109,0);
    INSERT INTO db_sysindices VALUES(1008235,'gestaoprocessovencido_db_usuario_in',1010240,'0');
    INSERT INTO db_syscadind VALUES(1008235,1009531,1);
    INSERT INTO db_syssequencia VALUES(1000700, 'gestaoprocessovencido_p102_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
    UPDATE db_sysarqcamp SET codsequencia = 1000700 WHERE codarq = 1010240 AND codcam = 1009530;

    INSERT INTO db_sysarquivo VALUES (1010241, 'gestaodepartamentoprocesso', 'Armazena o usuário responsável pela gestão de processos em um departamento e que pode visualizar o relatório de processos vencidos com somente dados do departamento em que é responsável.', 'p103', '2017-11-24', 'Gestão de Processos Vencidos no Departamento', 0, 'f', 'f', 'f', 'f' );
    INSERT INTO db_sysarqmod VALUES (4,1010241);
    INSERT INTO db_syscampo VALUES(1009532,'p103_sequencial','int4','Código sequencial.','0', 'Código',10,'f','f','f',1,'text','Código');
    INSERT INTO db_syscampo VALUES(1009533,'p103_db_depart','int4','Código do departamento que será administrado os processos vencidos.','0', 'Código do Departamento',10,'f','f','f',1,'text','Código do Departamento');
    INSERT INTO db_syscampo VALUES(1009534,'p103_db_usuarios','int4','Código do usuário responsável pelos processos vencidos em um departamento.','0', 'Código do Usuário',10,'f','f','f',1,'text','Código do Usuário');
    INSERT INTO db_sysarqcamp VALUES(1010241,1009532,1,0);
    INSERT INTO db_sysarqcamp VALUES(1010241,1009533,2,0);
    INSERT INTO db_sysarqcamp VALUES(1010241,1009534,3,0);
    INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010241,1009532,1,1009532);
    INSERT INTO db_sysforkey VALUES(1010241,1009533,1,154,0);
    INSERT INTO db_sysforkey VALUES(1010241,1009534,1,109,0);
    INSERT INTO db_sysindices VALUES(1008236,'gestaodepartamentoprocesso_db_depart_in',1010241,'0');
    INSERT INTO db_syscadind VALUES(1008236,1009533,1);
    INSERT INTO db_sysindices VALUES(1008237,'gestaodepartamentoprocesso_db_usuarios_in',1010241,'0');
    INSERT INTO db_syscadind VALUES(1008237,1009534,1);
    INSERT INTO db_syssequencia VALUES(1000701, 'gestaodepartamentoprocesso_p103_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
    UPDATE db_sysarqcamp SET codsequencia = 1000701 WHERE codarq = 1010241 AND codcam = 1009532;

    CREATE SEQUENCE protocolo.mensageriaprocesso_p101_sequencial_seq
    INCREMENT 1
    MINVALUE 1
    MAXVALUE 9223372036854775807
    START 1
    CACHE 1;

    CREATE TABLE protocolo.mensageriaprocesso (
    p101_sequencial               INT4         NOT NULL DEFAULT 0,
    p101_assunto                  VARCHAR(255) NOT NULL, 
    p101_mensagem                 TEXT         NOT NULL,
    p101_notificarreceberprocesso BOOL         NOT NULL DEFAULT FALSE,
    p101_notificardatavencimento  BOOL         NOT NULL DEFAULT FALSE,
    p101_diasprazo                INT4         NOT NULL DEFAULT 0,
    CONSTRAINT mensageriaprocesso_sequ_pk PRIMARY KEY (p101_sequencial));

    INSERT INTO mensageriaprocesso VALUES (nextval('mensageriaprocesso_p101_sequencial_seq'), 'Prazo para Movimentação do Processo', 'O prazo para movimentar o processo [numero]/[ano] venceu dia [data_final] e deve ter andamento.', FALSE, FALSE, 5);

    CREATE SEQUENCE protocolo.gestaoprocessovencido_p102_sequencial_seq
    INCREMENT 1
    MINVALUE 1
    MAXVALUE 9223372036854775807
    START 1
    CACHE 1;

    CREATE TABLE protocolo.gestaoprocessovencido (
    p102_sequencial  INT4 NOT NULL DEFAULT 0,
    p102_db_usuarios INT4 NOT NULL DEFAULT 0,
    CONSTRAINT gestaoprocessovencido_sequ_pk PRIMARY KEY (p102_sequencial));

    ALTER TABLE gestaoprocessovencido
    ADD CONSTRAINT gestaoprocessovencido_usuarios_fk FOREIGN KEY (p102_db_usuarios)
    REFERENCES db_usuarios;

    CREATE INDEX gestaoprocessovencido_db_usuario_in ON gestaoprocessovencido(p102_db_usuarios);

    CREATE SEQUENCE protocolo.gestaodepartamentoprocesso_p103_sequencial_seq
    INCREMENT 1
    MINVALUE 1
    MAXVALUE 9223372036854775807
    START 1
    CACHE 1;

    CREATE TABLE protocolo.gestaodepartamentoprocesso (
    p103_sequencial  INT4 NOT NULL DEFAULT 0,
    p103_db_depart   INT4 NOT NULL DEFAULT 0,
    p103_db_usuarios INT4 NOT NULL DEFAULT 0,
    CONSTRAINT gestaodepartamentoprocesso_sequ_pk PRIMARY KEY (p103_sequencial));

    ALTER TABLE gestaodepartamentoprocesso
    ADD CONSTRAINT gestaodepartamentoprocesso_usuarios_fk FOREIGN KEY (p103_db_usuarios)
    REFERENCES db_usuarios;
    
    ALTER TABLE gestaodepartamentoprocesso
    ADD CONSTRAINT gestaodepartamentoprocesso_depart_fk FOREIGN KEY (p103_db_depart)
    REFERENCES db_depart;

    CREATE INDEX gestaodepartamentoprocesso_db_depart_in ON gestaodepartamentoprocesso(p103_db_depart);

    CREATE INDEX gestaodepartamentoprocesso_db_usuarios_in ON gestaodepartamentoprocesso(p103_db_usuarios);

EOL;
        $this->execute($sSql);
    }

    public function down()
    {
        $sSql = <<<EOL
        DELETE FROM db_menu WHERE id_item_filho = 10471 AND modulo = 604;
        DELETE FROM db_itensmenu WHERE id_item = 10471;

        DELETE FROM db_menu WHERE id_item_filho = 10472 AND modulo = 604;
        DELETE FROM db_itensmenu WHERE id_item = 10472;

        DELETE FROM db_menu WHERE id_item_filho = 10474 AND modulo = 604;
        DELETE FROM db_itensmenu WHERE id_item = 10474;
        
        DELETE FROM db_syssequencia WHERE codsequencia = 1000699;
        DELETE FROM db_sysprikey WHERE codarq  = 1010238;
        DELETE FROM db_sysarqcamp WHERE codarq = 1010238;
        DELETE FROM db_syscampo WHERE codcam IN (1009522, 1009523, 1009524, 1009525, 1009526, 1009527);
        DELETE FROM db_sysarqmod WHERE codarq = 1010238;
        DELETE FROM db_sysarquivo WHERE codarq = 1010238;

        DELETE FROM db_syssequencia WHERE codsequencia = 1000700;
        DELETE FROM db_syscadind WHERE codind = 1008235 AND codcam = 1009531 AND sequen = 1;
        DELETE FROM db_sysindices WHERE codind = 1008235;
        DELETE FROM db_sysforkey WHERE codarq = 1010240 AND referen = 109;
        DELETE FROM db_sysprikey WHERE codarq = 1010240;
        DELETE FROM db_sysarqcamp WHERE codarq = 1010240;
        DELETE FROM db_syscampo WHERE codcam IN (1009530, 1009531);
        DELETE FROM db_sysarqmod WHERE codarq = 1010240;
        DELETE FROM db_sysarquivo WHERE codarq = 1010240;

        DELETE FROM db_syssequencia WHERE codsequencia = 1000701;
        DELETE FROM db_syscadind WHERE codind = 1008237 AND codcam = 1009534 AND sequen = 1;
        DELETE FROM db_syscadind WHERE codind = 1008236 AND codcam = 1009533 AND sequen = 1;
        DELETE FROM db_sysindices WHERE codind IN (1008236, 1008237);
        DELETE FROM db_sysforkey WHERE codarq = 1010241 AND referen IN (109, 154);
        DELETE FROM db_sysprikey WHERE codarq = 1010241;
        DELETE FROM db_sysarqcamp WHERE codarq = 1010241;
        DELETE FROM db_syscampo WHERE codcam IN (1009532, 1009533, 1009534);
        DELETE FROM db_sysarqmod WHERE codarq = 1010241;
        DELETE FROM db_sysarquivo WHERE codarq = 1010241;

        DROP SEQUENCE IF EXISTS mensageriaprocesso_p101_sequencial_seq;
        DROP TABLE IF EXISTS mensageriaprocesso CASCADE;

        DROP SEQUENCE IF EXISTS gestaoprocessovencido_p102_sequencial_seq;
        DROP TABLE IF EXISTS gestaoprocessovencido CASCADE;

        DROP SEQUENCE IF EXISTS gestaodepartamentoprocesso_p103_sequencial_seq;
        DROP TABLE IF EXISTS gestaodepartamentoprocesso CASCADE;
EOL;

        $this->execute($sSql);
    }
}
