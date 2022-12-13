<?php

use Classes\PostgresMigration;

class M8644AutorizacaoHorasExtras extends PostgresMigration
{
  public function up()
  {
    $this->execute("insert into db_syscampo values(1009360,'rh200_autorizahoraextra','bool','Controla a permissão de horas extras para o servidores no ponto eletrônico.','true', 'Calcular H.E. Somente Com Autorização',1,'f','f','f',5,'text','Calcular H.E. Somente Com Autorização')");
    $this->execute("insert into db_sysarqcamp values(4024,1009360,12,0)");

    $this->adicionarCampoPontoEletronicoConfiguracoes();
    $this->adicionarNaturezaAssentamento();
  }

  public function down() {

    $this->execute("delete from db_sysarqcamp where codcam = 1009360");
    $this->execute("delete from db_syscampo where codcam = 1009360");

    $this->removerCampoPontoEletronicoConfiguracoes();
  }

  public function adicionarCampoPontoEletronicoConfiguracoes() {

    $tabela = $this->table('pontoeletronicoconfiguracoesgerais', array('schema' => 'recursoshumanos'));

    if(!$tabela->hasColumn('rh200_autorizahoraextra')) {

      $tabela->addColumn('rh200_autorizahoraextra', 'boolean', array('default' => true))
        ->save();
    }

  }

  public function removerCampoPontoEletronicoConfiguracoes() {

    $tabela = $this->table('pontoeletronicoconfiguracoesgerais', array('schema' => 'recursoshumanos'));

    if($tabela->hasColumn('rh200_autorizahoraextra')) {

      $tabela->removeColumn('rh200_autorizahoraextra')
        ->save();
    }
  }

  public function adicionarNaturezaAssentamento() {

    $naturezasTipoAssentamentos = $this->fetchAll("SELECT * 
                                                     FROM pessoal.naturezatipoassentamento 
                                                    WHERE rh159_descricao = 'Autorização H.E.'");

    if(empty($naturezasTipoAssentamentos)) {

      $this->table('naturezatipoassentamento', array('schema'=>'pessoal'))
        ->insert(array('rh159_sequencial', 'rh159_descricao'), array(array(7, 'Autorização H.E.')
        ))->saveData();
    }

    $this->execute("SELECT setval('naturezatipoassentamento_rh159_sequencial_seq', (SELECT max (rh159_sequencial) FROM naturezatipoassentamento))");
  }

  public function removerNaturezaAssentamento() {

    $this->execute('delete from naturezatipoassentamento where rh159_sequencial = 7');
    $this->execute("SELECT setval('naturezatipoassentamento_rh159_sequencial_seq', (SELECT max (rh159_sequencial) FROM naturezatipoassentamento))");
  }
}
