<?php

use Classes\PostgresMigration;

class M9632VersionamentoFormularioEsocial extends PostgresMigration
{
    public function up()
    {
        $this->adicionaDicionario();
        $this->alteraMenu();
        $this->criaTabela();
    }

    public function down()
    {
        $this->removeDicionario();
        $this->retornaMenu();
        $this->removeTabela();
    }

    public function adicionaDicionario()
    {
        //adicionando o campo db104_identificadorcampo no dicionario
        $this->execute( <<<SQL
            insert into db_syscampo values(1009473,'db104_identificadorcampo','varchar(255)','Esse campo deve identificar a op��o da resposta. Usado para identificar as respostas dos formul�rios do e-Social Recomenda��es: - N�o usar espa�o; - N�o usar caracteres especiais; - Pode usar letras e n�meros; OBSERVA��O: N�O DEVE repetir o mesmo identificador no mesmo formul�rio','', 'identificador do campo',255,'t','t','f',0,'text','identificador do campo');
            insert into db_sysarqcamp values(2985,1009473,8,0);
SQL
        );
        // adiciona menu Vers�o dos Layouts
        $this->execute( <<<SQL
            insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10465 ,'Vers�o dos Layouts' ,'Vers�o dos Layouts' ,'eso4_versao001.php' ,'1' ,'1' ,'Vers�o dos Layouts' ,'true' );
            insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,10465 ,491 ,10216 );
SQL
        );
    }

    public function removeDicionario()
    {
        $this->execute( <<<SQL
            delete from db_sysarqcamp where codarq = 2985 and codcam = 1009473;
            delete from db_syscampo where codcam = 1009473;
            delete from db_menu where id_item_filho = 10465;
            delete from db_itensmenu where id_item = 10465;
SQL
                );
    }

    public function alteraMenu()
    {
        $this->execute( <<<SQL
        delete from db_menu where id_item_filho = 10244 AND modulo = 10216;
        insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,10244 ,490 ,10216 );
        update db_itensmenu set id_item = 10220 , descricao = 'Dados do Servidor' , help = 'Dados do Servidor' , funcao = 'eso4_conferenciadados001.php' , itemativo = '1' , manutencao = '1' , desctec = 'Manuten��o S2100 - Tabela de Rubricas' , libcliente = 'true' where id_item = 10220;
        update db_itensmenu set id_item = 10426 , descricao = 'Tabela de Rubricas' , help = 'Tabela de Rubricas' , funcao = 'con4_manutencaoformulario001.php?esocial=2' , itemativo = '1' , manutencao = '1' , desctec = 'Realiza a manuten��o e formul�rios para o cadastro de Rubricas' , libcliente = 'true' where id_item = 10426;
SQL
        );
    }

    public function retornaMenu()
    {
        $this->execute( <<<SQL
        delete from db_menu where id_item_filho = 10244 AND modulo = 10216 ;
        insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values (29, 10244, 270, 10216);
        update db_itensmenu set id_item = 10220 , descricao = 'Manuten��o S2100 - Dados do Servidor' , help = 'Manuten��o S2100 - Dados do Servidor' , funcao = 'eso4_conferenciadados001.php' , itemativo = '1' , manutencao = '1' , desctec = 'Manuten��o S2100 - Tabela de Rubricas' , libcliente = 'true' where id_item = 10220;
        update db_itensmenu set id_item = 10426 , descricao = 'Manuten��o S1010 - Tabela de Rubricas' , help = 'Manuten��o S1010 - Tabela de Rubricas' , funcao = 'con4_manutencaoformulario001.php?formulario=3000010' , itemativo = '1' , manutencao = '1' , desctec = 'Realiza a manuten��o e formul�rios para o cadastro de Rubricas' , libcliente = 'true' where id_item = 10426;
SQL
        );
    }

    public function criaTabela()
    {
        // altera tabela avaliacaoperguntaopcao adicionando o campo db104_identificadorcampo
        $this->execute('alter table avaliacaoperguntaopcao add column db104_identificadorcampo varchar(255);');
    }

    public function removeTabela()
    {
        // altera tabela avaliacaoperguntaopcao removendo o campo db104_identificadorcampo
        $this->execute('alter table avaliacaoperguntaopcao drop column db104_identificadorcampo;');
    }

}
