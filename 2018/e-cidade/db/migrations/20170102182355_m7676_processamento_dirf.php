<?php

use Classes\PostgresMigration;

class M7676ProcessamentoDirf extends PostgresMigration
{
    public function up() {

     /*
      * rhdirfgeracaopessoalpensionista
      */
     $this->execute("insert into db_sysarquivo values (4019, 'rhdirfgeracaopessoalpensionista', 'Valor do Pensionista na Dirf', 'rh202', '2017-01-02', 'Valor do Pensionista na Dirf', 0, 'f', 'f', 'f', 'f' )");
     $this->execute("insert into db_sysarqmod values (28,4019)");
     $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22311 ,'rh202_sequencial' ,'int4' ,'Código' ,'Código' ,'Código' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código' );");
     $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 4019 ,22311 ,1 ,0 )");
     $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22313 ,'rh202_numcgm' ,'int4' ,'Cgm' ,'' ,'Cgm' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Cgm' )");
     $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 4019 ,22313 ,2 ,0 );");
     $this->execute("insert into db_syscampo values(22315,'rh202_rhdirfgeracaopessoal','int4','Geração dados pessoal','0', 'Geração dados pessoal',10,'f','f','f',1,'text','Geração dados pessoal');");
     $this->execute("insert into db_sysarqcamp values(4019,22315,3,0);");
     $this->execute("insert into db_sysprikey (codarq,codcam,sequen,camiden) values(4019,22311,1,22313);");
     $this->execute("insert into db_sysforkey values(4019,22313,1,42,0);");
     $this->execute("insert into db_sysforkey values(4019,22315,1,3137,0);");
     $this->execute("insert into db_sysindices values(4408,'rhdirfgeracaopessoalpensionista_cgm_in',4019,'0');");
     $this->execute("insert into db_syscadind values(4408,22313,1);");
     $this->execute("insert into db_sysindices values(4409,'rhdirfgeracaopessoalpensionista_rhdirfgeracaopessoal_in',4019,'0');");
     $this->execute("insert into db_syscadind values(4409,22315,1);");
     $this->execute("insert into db_syssequencia values(1000645, 'rhdirfgeracaopessoalpensionista_rh202_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);");
     $this->execute("update db_sysarqcamp set codsequencia = 1000645 where codarq = 4019 and codcam = 22311;");


     $this->execute("create sequence pessoal.rhdirfgeracaopessoalpensionista_rh202_sequencial_seq");
     $tabela = $this->table('rhdirfgeracaopessoalpensionista', array('schema'=>'pessoal', 'id'=> false, 'primary_key'=>'rh202_sequencial', 'constraint'=>' rhdirfgeracaopessoalpensionista_rh202_sequencial_pk'));
     $tabela->addColumn('rh202_sequencial', 'integer')
        ->addColumn('rh202_numcgm', 'integer')
        ->addColumn('rh202_rhdirfgeracaopessoal', 'integer')
        ->addForeignKey('rh202_rhdirfgeracaopessoal', 'pessoal.rhdirfgeracaodadospessoal', 'rh96_sequencial', array('constraint' => 'rhdirfgeracaopessoalpensionista_rhdirfgeracaopessoal_fk'))
        ->addForeignKey('rh202_numcgm', 'protocolo.cgm', 'z01_numcgm',  array('constraint'=>'rhdirfgeracaopessoalpensionista_numcgm_fk'))
        ->addIndex(array('rh202_rhdirfgeracaopessoal'), array('name' => 'rhdirfgeracaopessoalpensionista_rhdirfgeracaopessoal_in'))
        ->addIndex(array('rh202_numcgm'), array('name' => 'rhdirfgeracaopessoalpensionista_cgm_in'))
        ->create();
      $this->execute("ALTER TABLE pessoal.rhdirfgeracaopessoalpensionista ALTER COLUMN rh202_sequencial SET DEFAULT nextval('pessoal.rhdirfgeracaopessoalpensionista_rh202_sequencial_seq')");

      /**
       * rhdirfgeracaopessoalpensionistavalor
       */
      $this->execute("insert into db_sysarquivo values (4020, 'rhdirfgeracaopessoalpensionistavalor', 'rhdirfgeracaopessoalpensionistavalor', 'rh203', '2017-01-03', 'rhdirfgeracaopessoalpensionistavalor', 0, 'f', 'f', 'f', 'f' );");
      $this->execute("insert into db_sysarqmod values (28,4020);");
      $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22316 ,'rh203_sequencial' ,'int4' ,'Código Sequencial' ,'' ,'Código Sequencial' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código Sequencial' );");
      $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 4020 ,22316 ,1 ,0 );");
      $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22317 ,'rh203_rhdirfgeracaopessoalpensionista' ,'int4' ,'rhdirfgeracaopessoalpensionista' ,'' ,'Pensionista' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Pensionista' );");
      $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 4020 ,22317 ,2 ,0 );");
      $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22318 ,'rh203_rhdirfgeracaodadospessoalvalor' ,'int4' ,'Valor processado' ,'' ,'Valor processado' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Valor processado' );");
      $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 4020 ,22318 ,3 ,0 );");
      $this->execute("insert into db_sysprikey (codarq,codcam,sequen,camiden) values(4020,22316,1,22317);");
      $this->execute("insert into db_sysforkey values(4020,22317,1,4019,0);");
      $this->execute("insert into db_sysforkey values(4020,22318,1,3139,0);");
      $this->execute("insert into db_sysindices values(4410,'rhdirfgeracaopessoalpensionistavalor_pensionista_in',4020,'0');");
      $this->execute("insert into db_syscadind values(4410,22317,1);");
      $this->execute("insert into db_sysindices values(4411,'rhdirfgeracaopessoalpensionistavalor_valor_in',4020,'0');");
      $this->execute("insert into db_syscadind values(4411,22318,1);");
      $this->execute("insert into db_syssequencia values(1000646, 'rhdirfgeracaopessoalpensionistavalor_rh203_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);");
      $this->execute("update db_sysarqcamp set codsequencia = 1000646 where codarq = 4020 and codcam = 22316;");

      $this->execute("create sequence pessoal.rhdirfgeracaopessoalpensionistavalor_rh203_sequencial_seq");
      $tabela = $this->table('rhdirfgeracaopessoalpensionistavalor', array('schema'=>'pessoal', 'id'=> false, 'primary_key'=>'rh203_sequencial', 'constraint'=>' rhdirfgeracaopessoalpensionistavalor_rh203_sequencial_pk'));
      $tabela->addColumn('rh203_sequencial', 'integer')
        ->addColumn('rh203_rhdirfgeracaopessoalpensionista', 'integer')
        ->addColumn('rh203_rhdirfgeracaodadospessoalvalor', 'integer')
        ->addForeignKey('rh203_rhdirfgeracaopessoalpensionista', 'pessoal.rhdirfgeracaopessoalpensionista', 'rh202_sequencial', array('constraint' => 'rhdirfgeracaopessoalpensionistavalor_rhdirfgeracaopessoalpensionista_fk'))
        ->addForeignKey('rh203_rhdirfgeracaodadospessoalvalor', 'pessoal.rhdirfgeracaodadospessoalvalor', 'rh98_sequencial',  array('constraint'=>'rhdirfgeracaopessoalpensionistavalor_rhdirfgeracaodadospessoalvalor_fk'))
        ->addIndex(array('rh203_rhdirfgeracaopessoalpensionista'), array('name' => 'rhdirfgeracaopessoalpensionistavalor_pensionista_in'))
        ->addIndex(array('rh203_rhdirfgeracaodadospessoalvalor'), array('name' => 'rhdirfgeracaopessoalpensionistavalor_valor_in'))
        ->create();
      $this->execute("ALTER TABLE pessoal.rhdirfgeracaopessoalpensionistavalor ALTER COLUMN rh203_sequencial SET DEFAULT nextval('pessoal.rhdirfgeracaopessoalpensionistavalor_rh203_sequencial_seq')");

      /**
       * rhdirfgeracaodadospessoalvalorprevidencia
       *
       */
      $this->execute("insert into db_sysarquivo values (4022, 'rhdirfgeracaopessoalvalorprevidencia', 'rhdirfgeracaopessoalvalorprevidencia', 'rh204', '2017-01-04', 'rhdirfgeracaopessoalvalorprevidencia', 0, 'f', 'f', 'f', 'f' );");
      $this->execute("insert into db_sysarqmod values (28,4022);");
      $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22319 ,'rh204_sequencial' ,'int4' ,'rhdirfgeracaopessoalvalorprevidencia' ,'rhdirfgeracaopessoalvalorprevidencia' ,'Código' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código' );");
      $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 4022 ,22319 ,1 ,0 );");
      $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22320 ,'rh204_rhdirfgeracaodadospessoalvalor' ,'int4' ,'rhdirfgeracaodadospessoalvalor' ,'' ,'Valor da Dirf' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Valor da Dirf' );");
      $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 4022 ,22320 ,2 ,0 );");
      $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 22321 ,'rh204_numcgm' ,'int4' ,'Previdência' ,'' ,'Previdência' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Previdência' );");
      $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 4022 ,22321 ,3 ,0 );");
      $this->execute("delete from db_sysprikey where codarq = 4022;");
      $this->execute("insert into db_sysprikey (codarq,codcam,sequen,camiden) values(4022,22319,1,22320);");
      $this->execute("delete from db_sysforkey where codarq = 4022 and referen = 0;");
      $this->execute("insert into db_sysforkey values(4022,22320,1,3139,0);");
      $this->execute("delete from db_sysforkey where codarq = 4022 and referen = 0;");
      $this->execute("insert into db_sysforkey values(4022,22321,1,42,0);");
      $this->execute("insert into db_sysindices values(4412,'rhdirfgeracaopessoalvalorprevidencia_valor_in',4022,'0');");
      $this->execute("insert into db_syscadind values(4412,22320,1);");
      $this->execute("insert into db_sysindices values(4413,'rhdirfgeracaopessoalvalorprevidencia_cgm_in',4022,'0');");
      $this->execute("insert into db_syscadind values(4413,22321,1);");
      $this->execute("insert into db_syssequencia values(1000647, 'rhdirfgeracaopessoalvalorprevidencia_rh204_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);");
      $this->execute("update db_sysarqcamp set codsequencia = 1000647 where codarq = 4022 and codcam = 22319;");

      $this->execute("create sequence pessoal.rhdirfgeracaopessoalvalorprevidencia_rh204_sequencial_seq");
      $tabela = $this->table('rhdirfgeracaopessoalvalorprevidencia', array('schema'=>'pessoal', 'id'=> false, 'primary_key'=>'rh204_sequencial', 'constraint'=>' rhdirfgeracaopessoalvalorprevidencia_rh204_sequencial_pk'));
      $tabela->addColumn('rh204_sequencial', 'integer')
        ->addColumn('rh204_rhdirfgeracaodadospessoalvalor', 'integer')
        ->addColumn('rh204_numcgm', 'integer')
        ->addForeignKey('rh204_numcgm', 'protocolo.cgm', 'z01_numcgm', array('constraint' => 'rhdirfgeracaopessoalvalorprevidencia_numcgm_fk'))
        ->addForeignKey('rh204_rhdirfgeracaodadospessoalvalor', 'pessoal.rhdirfgeracaodadospessoalvalor', 'rh98_sequencial',  array('constraint'=>'rhdirfgeracaopessoalvalorprevidenciar_rhdirfgeracaodadospessoalvalor_fk'))
        ->addIndex(array('rh204_numcgm'), array('name' => 'rhdirfgeracaopessoalvalorprevidencia_numcgm_in'))
        ->addIndex(array('rh204_rhdirfgeracaodadospessoalvalor'), array('name' => 'rhdirfgeracaopessoalvalorprevidencia_valor_in'))
        ->create();
      $this->execute("ALTER TABLE pessoal.rhdirfgeracaopessoalvalorprevidencia ALTER COLUMN rh204_sequencial SET DEFAULT nextval('pessoal.rhdirfgeracaopessoalvalorprevidencia_rh204_sequencial_seq')");
    }


    public function down() {

      $this->execute("delete from db_syssequencia where codsequencia in(1000645, 1000646, 1000647)");
      $this->execute("delete from db_syscadind where codind in(4408, 4409, 4410, 4411, 4412, 4413)");
      $this->execute("delete from db_sysindices where codind in(4408, 4409, 4410, 4411, 4412, 4413)");
      $this->execute("delete from db_sysforkey where codarq in(4019, 4020, 4022)");
      $this->execute("delete from db_sysprikey where codarq in(4019, 4020, 4022)");
      $this->execute("delete from db_sysarqcamp where codarq in(4019, 4020, 4022)");
      $this->execute("delete from db_syscampo where codcam in(22311, 22313, 22315, 22316, 22317, 22318, 22319, 22320, 22321); ");
      $this->execute("delete from db_sysarqmod where codarq in(4019, 4020, 4022)");
      $this->execute("delete from db_sysarquivo where codarq in(4019, 4020, 4022)");

      $this->table('rhdirfgeracaopessoalvalorprevidencia',  array('schema'=>'pessoal'))->drop();
      $this->execute('drop sequence pessoal.rhdirfgeracaopessoalvalorprevidencia_rh204_sequencial_seq');

      $this->table('rhdirfgeracaopessoalpensionistavalor',  array('schema'=>'pessoal'))->drop();
      $this->execute('drop sequence pessoal.rhdirfgeracaopessoalpensionistavalor_rh203_sequencial_seq');

      $this->table('rhdirfgeracaopessoalpensionista',  array('schema'=>'pessoal'))->drop();
      $this->execute('drop sequence pessoal.rhdirfgeracaopessoalpensionista_rh202_sequencial_seq');

    }
}
