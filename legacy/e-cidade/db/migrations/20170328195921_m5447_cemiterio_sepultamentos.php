<?php

use Classes\PostgresMigration;

class M5447CemiterioSepultamentos extends PostgresMigration
{

    public function up()
    {
        /* Insere campos no dicionario de dados */
        $this->execute("insert into db_syscampo values(22424,'cm01_c_nomemedico','varchar(60)','M�dico','', 'M�dico',60,'t','t','f',0,'text','M�dico')");
        $this->execute("insert into db_syscampo values(22425,'cm01_c_nomehospital','varchar(60)','Hospital','', 'Hospital',60,'t','t','f',0,'text','Hospital')");
        $this->execute("insert into db_syscampo values(22426,'cm01_c_nomefuneraria','varchar(60)','Funer�ria','', 'Funer�ria',60,'t','t','f',0,'text','Funer�ria')");

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

        $this->execute("update db_syscampo set descricao = 'Oss�rio', rotulo = 'Oss�rio', rotulorel = 'Oss�rio' where nomecam = 'cm06_i_ossoario'");
        $this->execute("update db_syscampo set descricao = 'C�digo Oss�rio/ Jazigo', rotulo = 'C�digo Oss�rio/ Jazigo', rotulorel = 'C�digo Oss�rio/ Jazigo' where nomecam = 'cm30_i_ossoariojazigo'");
        $this->execute("update db_syscampo set descricao = 'Est� no Oss�rio', rotulo = 'Est� no Oss�rio', rotulorel = 'Est� no Oss�rio' where nomecam = 'cm27_c_ossoario'");
        $this->execute("update db_syscampo set descricao = 'C�digo Oss�rio/ Jazigo', rotulo = 'C�digo Oss�rio/ Jazigo', rotulorel = 'C�digo Oss�rio/ Jazigo' where nomecam = 'cm28_i_codigo'");
        $this->execute("update db_syscampo set descricao = 'N Oss�rio/ Jazigo', rotulo = 'N Oss�rio/ Jazigo', rotulorel = 'N Oss�rio/ Jazigo' where nomecam = 'cm28_i_ossoariojazigo'");
        $this->execute("update db_syscampo set descricao = 'Oss�rio/ Jazigo', rotulo = 'Oss�rio/ Jazigo', rotulorel = 'Oss�rio/ Jazigo' where nomecam = 'cm26_i_ossoariojazigo'");
        $this->execute("update db_syscampo set descricao = 'Oss�rio', rotulo = 'Oss�rio', rotulorel = 'Oss�rio' where nomecam = 'cm12_i_ossoariopart'");
        $this->execute("update db_syscampo set descricao = 'Taxa Oss�rio/ Jazigo', rotulo = 'Taxa Oss�rio/ Jazigo', rotulorel = 'Taxa Oss�rio/ Jazigo' where nomecam = 'cm30_i_codigo'");
        $this->execute("update db_syscampo set descricao = 'C�digo Oss�rio/ Jazigo', rotulo = 'C�digo Oss�rio/ Jazigo', rotulorel = 'C�digo Oss�rio/ Jazigo' where nomecam = 'cm25_i_codigo'");

        $this->execute("update db_itensmenu set descricao = 'Oss�rios / Jazigos', help = 'Oss�rios / Jazigos', desctec = 'Oss�rios / Jazigos' where id_item = 289622");
        $this->execute("update db_itensmenu set descricao = 'Propriet�rio de Oss�rio', help = 'Propriet�rio de Oss�rio', desctec = 'Propriet�rio de Oss�rio' where id_item = 289623");
        $this->execute("update db_itensmenu set descricao = 'Oss�rio/jazigo', help = 'Oss�rio/jazigo', desctec = 'Cadastro de Oss�rio/Jazigo' where id_item = 289603");
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

        $this->execute("update db_syscampo set descricao = 'Osso�rio', rotulo = 'Osso�rio', rotulorel = 'Osso�rio' where nomecam = 'cm06_i_ossoario'");
        $this->execute("update db_syscampo set descricao = 'C�digo Osso�rio/ Jazigo', rotulo = 'C�digo Osso�rio/ Jazigo', rotulorel = 'C�digo Osso�rio/ Jazigo' where nomecam = 'cm30_i_ossoariojazigo'");
        $this->execute("update db_syscampo set descricao = 'Est� no Osso�rio', rotulo = 'Est� no Osso�rio', rotulorel = 'Est� no Osso�rio' where nomecam = 'cm27_c_ossoario'");
        $this->execute("update db_syscampo set descricao = 'C�digo Osso�rio/ Jazigo', rotulo = 'C�digo Osso�rio/ Jazigo', rotulorel = 'C�digo Osso�rio/ Jazigo' where nomecam = 'cm28_i_codigo'");
        $this->execute("update db_syscampo set descricao = 'N Osso�rio/ Jazigo', rotulo = 'N Osso�rio/ Jazigo', rotulorel = 'N Osso�rio/ Jazigo' where nomecam = 'cm28_i_ossoariojazigo'");
        $this->execute("update db_syscampo set descricao = 'Osso�rio/ Jazigo', rotulo = 'Osso�rio/ Jazigo', rotulorel = 'Osso�rio/ Jazigo' where nomecam = 'cm26_i_ossoariojazigo'");
        $this->execute("update db_syscampo set descricao = 'Osso�rio', rotulo = 'Osso�rio', rotulorel = 'Osso�rio' where nomecam = 'cm12_i_ossoariopart'");
        $this->execute("update db_syscampo set descricao = 'Taxa Osso�rio/ Jazigo', rotulo = 'Taxa Osso�rio/ Jazigo', rotulorel = 'Taxa Osso�rio/ Jazigo' where nomecam = 'cm30_i_codigo'");
        $this->execute("update db_syscampo set descricao = 'C�digo Osso�rio/ Jazigo', rotulo = 'C�digo Osso�rio/ Jazigo', rotulorel = 'C�digo Osso�rio/ Jazigo' where nomecam = 'cm25_i_codigo'");

        $this->execute("update db_itensmenu set descricao = 'Osso�rios / Jazigos', help = 'Osso�rios / Jazigos', desctec = 'Osso�rios / Jazigos' where id_item = 289622");
        $this->execute("update db_itensmenu set descricao = 'Propriet�rio de Osso�rio', help = 'Propriet�rio de Osso�rio', desctec = 'Propriet�rio de Osso�rio' where id_item = 289623");
        $this->execute("update db_itensmenu set descricao = 'Osso�rio/jazigo', help = 'Osso�rio/jazigo', desctec = 'Cadastro de Osso�rio/Jazigo' where id_item = 289603");
    }
}
