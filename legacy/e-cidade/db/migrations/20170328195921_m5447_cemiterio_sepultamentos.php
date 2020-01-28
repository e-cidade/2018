<?php

use Classes\PostgresMigration;

class M5447CemiterioSepultamentos extends PostgresMigration
{

    public function up()
    {
        /* Insere campos no dicionario de dados */
        $this->execute("insert into db_syscampo values(22424,'cm01_c_nomemedico','varchar(60)','Médico','', 'Médico',60,'t','t','f',0,'text','Médico')");
        $this->execute("insert into db_syscampo values(22425,'cm01_c_nomehospital','varchar(60)','Hospital','', 'Hospital',60,'t','t','f',0,'text','Hospital')");
        $this->execute("insert into db_syscampo values(22426,'cm01_c_nomefuneraria','varchar(60)','Funerária','', 'Funerária',60,'t','t','f',0,'text','Funerária')");

        /* Vincula campos a tabela sepulta*/
        $this->execute("insert into db_sysarqcamp values(1797,22424,20,0)");
        $this->execute("insert into db_sysarqcamp values(1797,22425,21,0)");
        $this->execute("insert into db_sysarqcamp values(1797,22426,22,0)");

        /* Adiciona campos a tabela sepultamentos*/
        $this->table('sepultamentos',    array('schema'=>'cemiterio'))
                ->addColumn('cm01_c_nomemedico', 'string', array('null' => true, 'default' => 'null', 'limit' => 60))
                ->addColumn('cm01_c_nomehospital', 'string', array('null' => true, 'default' => 'null', 'limit' => 60))
                ->addColumn('cm01_c_nomefuneraria', 'string', array('null' => true, 'default' => 'null', 'limit' => 60))
                ->changeColumn('cm01_i_medico', 'integer', array('null' => true, 'default' => null))
                ->changeColumn('cm01_i_hospital', 'integer', array('null' => true, 'default' => null))
                ->changeColumn('cm01_i_funeraria', 'integer', array('null' => true, 'default' => null))
                ->save();

        $this->execute("update db_syscampo set descricao = 'Ossário', rotulo = 'Ossário', rotulorel = 'Ossário' where nomecam = 'cm06_i_ossoario'");
        $this->execute("update db_syscampo set descricao = 'Código Ossário/ Jazigo', rotulo = 'Código Ossário/ Jazigo', rotulorel = 'Código Ossário/ Jazigo' where nomecam = 'cm30_i_ossoariojazigo'");
        $this->execute("update db_syscampo set descricao = 'Está no Ossário', rotulo = 'Está no Ossário', rotulorel = 'Está no Ossário' where nomecam = 'cm27_c_ossoario'");
        $this->execute("update db_syscampo set descricao = 'Código Ossário/ Jazigo', rotulo = 'Código Ossário/ Jazigo', rotulorel = 'Código Ossário/ Jazigo' where nomecam = 'cm28_i_codigo'");
        $this->execute("update db_syscampo set descricao = 'N Ossário/ Jazigo', rotulo = 'N Ossário/ Jazigo', rotulorel = 'N Ossário/ Jazigo' where nomecam = 'cm28_i_ossoariojazigo'");
        $this->execute("update db_syscampo set descricao = 'Ossário/ Jazigo', rotulo = 'Ossário/ Jazigo', rotulorel = 'Ossário/ Jazigo' where nomecam = 'cm26_i_ossoariojazigo'");
        $this->execute("update db_syscampo set descricao = 'Ossário', rotulo = 'Ossário', rotulorel = 'Ossário' where nomecam = 'cm12_i_ossoariopart'");
        $this->execute("update db_syscampo set descricao = 'Taxa Ossário/ Jazigo', rotulo = 'Taxa Ossário/ Jazigo', rotulorel = 'Taxa Ossário/ Jazigo' where nomecam = 'cm30_i_codigo'");
        $this->execute("update db_syscampo set descricao = 'Código Ossário/ Jazigo', rotulo = 'Código Ossário/ Jazigo', rotulorel = 'Código Ossário/ Jazigo' where nomecam = 'cm25_i_codigo'");

        $this->execute("update db_itensmenu set descricao = 'Ossários / Jazigos', help = 'Ossários / Jazigos', desctec = 'Ossários / Jazigos' where id_item = 289622");
        $this->execute("update db_itensmenu set descricao = 'Proprietário de Ossário', help = 'Proprietário de Ossário', desctec = 'Proprietário de Ossário' where id_item = 289623");
        $this->execute("update db_itensmenu set descricao = 'Ossário/jazigo', help = 'Ossário/jazigo', desctec = 'Cadastro de Ossário/Jazigo' where id_item = 289603");
    }

    public function down()
    {

        $this->execute('DELETE FROM db_sysarqcamp WHERE codarq = 1797 AND codcam IN (22424, 22425, 22426)');

        $this->execute('DELETE FROM db_syscampo WHERE codcam IN (22424, 22425, 22426)');

        $this->table('sepultamentos', array('schema' => 'cemiterio'))
                ->removeColumn('cm01_c_nomemedico')
                ->removeColumn('cm01_c_nomehospital')
                ->removeColumn('cm01_c_nomefuneraria')
                ->save();

        $this->execute("update db_syscampo set descricao = 'Ossoário', rotulo = 'Ossoário', rotulorel = 'Ossoário' where nomecam = 'cm06_i_ossoario'");
        $this->execute("update db_syscampo set descricao = 'Código Ossoário/ Jazigo', rotulo = 'Código Ossoário/ Jazigo', rotulorel = 'Código Ossoário/ Jazigo' where nomecam = 'cm30_i_ossoariojazigo'");
        $this->execute("update db_syscampo set descricao = 'Está no Ossoário', rotulo = 'Está no Ossoário', rotulorel = 'Está no Ossoário' where nomecam = 'cm27_c_ossoario'");
        $this->execute("update db_syscampo set descricao = 'Código Ossoário/ Jazigo', rotulo = 'Código Ossoário/ Jazigo', rotulorel = 'Código Ossoário/ Jazigo' where nomecam = 'cm28_i_codigo'");
        $this->execute("update db_syscampo set descricao = 'N Ossoário/ Jazigo', rotulo = 'N Ossoário/ Jazigo', rotulorel = 'N Ossoário/ Jazigo' where nomecam = 'cm28_i_ossoariojazigo'");
        $this->execute("update db_syscampo set descricao = 'Ossoário/ Jazigo', rotulo = 'Ossoário/ Jazigo', rotulorel = 'Ossoário/ Jazigo' where nomecam = 'cm26_i_ossoariojazigo'");
        $this->execute("update db_syscampo set descricao = 'Ossoário', rotulo = 'Ossoário', rotulorel = 'Ossoário' where nomecam = 'cm12_i_ossoariopart'");
        $this->execute("update db_syscampo set descricao = 'Taxa Ossoário/ Jazigo', rotulo = 'Taxa Ossoário/ Jazigo', rotulorel = 'Taxa Ossoário/ Jazigo' where nomecam = 'cm30_i_codigo'");
        $this->execute("update db_syscampo set descricao = 'Código Ossoário/ Jazigo', rotulo = 'Código Ossoário/ Jazigo', rotulorel = 'Código Ossoário/ Jazigo' where nomecam = 'cm25_i_codigo'");

        $this->execute("update db_itensmenu set descricao = 'Ossoários / Jazigos', help = 'Ossoários / Jazigos', desctec = 'Ossoários / Jazigos' where id_item = 289622");
        $this->execute("update db_itensmenu set descricao = 'Proprietário de Ossoário', help = 'Proprietário de Ossoário', desctec = 'Proprietário de Ossoário' where id_item = 289623");
        $this->execute("update db_itensmenu set descricao = 'Ossoário/jazigo', help = 'Ossoário/jazigo', desctec = 'Cadastro de Ossoário/Jazigo' where id_item = 289603");
    }
}
