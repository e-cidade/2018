<?php

use Classes\PostgresMigration;

class M8810GuiaRecolhimento extends PostgresMigration
{

  public function up() {

    $linha = $this->fetchRow('select * from caixa.cadmodcarne where cadmodcarne.k47_sequencial = 97');
    if (!$linha) {
      $this->execute("insert into caixa.cadmodcarne values (97, 'GRM - Guia de Recolhimento')");
    }

    $linha = $this->fetchRow('select * from configuracoes.db_syscampo where codcam = 1009310');
    if (!$linha) {
      $this->table('db_cadattdinamicoatributos', array('schema' => 'configuracoes'))->addColumn('db109_ativo', 'boolean', array('default' => true))->update();
    }

    $this->inserirDicionario();
  }

  private function inserirDicionario() {

    $linha = $this->fetchRow('select * from configuracoes.db_syscampo where codcam = 1009310');

    if (!$linha) {

      $this->execute(
        <<<STRING
insert into configuracoes.db_syscampo values(1009310,'db109_ativo','bool','Define se o atributo dinâmico encontra-se ativo.','f', 'Ativo',1,'t','f','f',5,'text','Ativo');
delete from configuracoes.db_sysarqcamp where codarq = 3163;
insert into configuracoes.db_sysarqcamp values(3163,17885,1,2065);
insert into configuracoes.db_sysarqcamp values(3163,17886,2,0);
insert into configuracoes.db_sysarqcamp values(3163,17887,3,0);
insert into configuracoes.db_sysarqcamp values(3163,17888,4,0);
insert into configuracoes.db_sysarqcamp values(3163,17889,5,0);
insert into configuracoes.db_sysarqcamp values(3163,17890,6,0);
insert into configuracoes.db_sysarqcamp values(3163,21709,7,0);
insert into configuracoes.db_sysarqcamp values(3163,1009287,8,0);
insert into configuracoes.db_sysarqcamp values(3163,1009310,9,0);


insert into configuracoes.db_syscampo values(1009315,'k177_sequencial','int4','Código','Código', 'Código',10,'f','f','f',1,'text','Código');
insert into configuracoes.db_syscampo values(1009316,'k177_guiarecolhimento','int4','Guia de Recolhimento','0', 'Guia de Recolhimento',10,'f','f','f',1,'text','Guia de Recolhimento');
insert into configuracoes.db_syscampo values(1009317,'k177_cidadao','int4','Código Cidadão','0', 'Código Cidadão',10,'f','f','f',1,'text','Código Cidadão');
insert into configuracoes.db_syscampo values(1009318,'k177_cidadaoseq','int4','Seq. Cidadão','0', 'Seq. Cidadão',10,'f','f','f',1,'text','Seq. Cidadão');
insert into configuracoes.db_sysarquivo values (1010203, 'guiarecolhimentocidadao', 'guiarecolhimentocidadao', 'k177', '2017-06-05', 'guiarecolhimentocidadao', 0, 'f', 'f', 'f', 'f' );
insert into configuracoes.db_sysarqmod values (5,1010203);
delete from configuracoes.db_sysarqcamp where codarq = 1010203;
insert into configuracoes.db_sysarqcamp values(1010203,1009315,1,0);
insert into configuracoes.db_sysarqcamp values(1010203,1009316,2,0);
insert into configuracoes.db_sysarqcamp values(1010203,1009317,3,0);
insert into configuracoes.db_sysarqcamp values(1010203,1009318,4,0);
insert into configuracoes.db_syssequencia values(1000669, 'guiarecolhimentocidadao_k177_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update configuracoes.db_sysarqcamp set codsequencia = 1000669 where codarq = 1010203 and codcam = 1009315;
delete from configuracoes.db_sysforkey where codarq = 1010203 and referen = 0;
insert into configuracoes.db_sysforkey values(1010203,1009316,1,4033,0);
delete from configuracoes.db_sysforkey where codarq = 1010203 and referen = 0;
insert into configuracoes.db_sysforkey values(1010203,1009317,1,2595,0);
insert into configuracoes.db_sysforkey values(1010203,1009318,2,2595,0);
insert into configuracoes.db_sysindices values(1008199,'guiarecolhimentocidadao_guiarecolhimento_in',1010203,'0');
insert into configuracoes.db_syscadind values(1008199,1009316,1);
delete from configuracoes.db_sysprikey where codarq = 1010203;
insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1010203,1009315,1,1009316);
STRING
      );

      $tableGuiaCidadao = $this->table('guiarecolhimentocidadao', array('schema' => 'caixa', 'primary_key' => array('k177_cidadaoseq'), 'id' => 'k177_sequencial'));
      $tableGuiaCidadao->addColumn('k177_guiarecolhimento', 'integer');
      $tableGuiaCidadao->addForeignKey('k177_guiarecolhimento', 'caixa.guiarecolhimento', 'k174_sequencial');
      $tableGuiaCidadao->addColumn('k177_cidadao', 'integer');
      $tableGuiaCidadao->addColumn('k177_cidadaoseq', 'integer');
      $tableGuiaCidadao->addForeignKey(array('k177_cidadao', 'k177_cidadaoseq'), 'ouvidoria.cidadao', array('ov02_sequencial', 'ov02_seq'));
      $tableGuiaCidadao->create();

    }

  }

  public function down() {

    $this->execute('update caixa.modcarnepadraocadmodcarne 
                           set m01_cadmodcarne = 93 
                         where m01_cadmodcarne = 97 
                           and m01_modcarnepadrao = 26');

    $this->execute("delete from caixa.cadmodcarne where k47_sequencial = 97;");
    $this->table('db_cadattdinamicoatributos', array('schema' => 'configuracoes'))->removeColumn('db109_ativo')->update();

    $this->execute(
      "
      delete from configuracoes.db_sysarqcamp where codcam = 1009310 and codarq = 3163;
      delete from configuracoes.db_syscampo where codcam = 1009310;
      "
    );

    $this->execute(
      <<<STRING
delete from configuracoes.db_sysprikey where codarq = 1010203;
delete from configuracoes.db_syscadind where codcam in (1009315,1009316,1009317,1009318); 
delete from configuracoes.db_sysindices where codarq = 1010203;
delete from configuracoes.db_sysforkey where codarq = 1010203;
delete from configuracoes.db_syssequencia where codsequencia = 1000669;
delete from configuracoes.db_sysarqcamp where codarq = 1010203;
delete from configuracoes.db_sysarqmod where codarq = 1010203;
delete from configuracoes.db_sysarquivo where codarq = 1010203;
delete from configuracoes.db_syscampo where codcam in (1009315,1009316,1009317,1009318); 
STRING
    );

    $this->table('guiarecolhimentocidadao', array('schema' => 'caixa'))->drop();


  }
}