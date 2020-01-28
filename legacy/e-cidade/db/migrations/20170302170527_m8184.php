<?php

use Classes\PostgresMigration;

class M8184 extends PostgresMigration
{
  private $tabela_db_syscampo;
  private $tabela_db_sysarqcamp;
  private $tabela_db_sysforkey;

  public function up()
  {
    $this->upDDL();
    $this->upDicionarioDados();

    $this->incluirFormulaPadraoHorasPonto();
  }

  public function upDicionarioDados()
  {
    $this->tabela_db_syscampo     = $this->table('db_syscampo',      array('schema'=>'configuracoes'));
    $this->tabela_db_sysarqcamp   = $this->table('db_sysarqcamp',    array('schema'=>'configuracoes'));
    $this->tabela_db_sysforkey    = $this->table('db_sysforkey',     array('schema'=>'configuracoes'));

    $this->upPRETabela_pontoeletronicoconfiguracoesgerais();
    $this->upPRETabela_pontoeletronicoarquivodata();

    $this->tabela_db_syscampo->saveData();
    $this->tabela_db_sysarqcamp->saveData();
    $this->tabela_db_sysforkey->saveData();
  }

  public function down()
  {
    $this->downDDL();

    $this->execute("DELETE FROM configuracoes.db_sysforkey    WHERE codarq = 4024 AND codcam = 22395");
    $this->execute("DELETE FROM configuracoes.db_sysarqcamp   WHERE codarq = 4024 AND codcam IN (22395)");
    $this->execute("DELETE FROM configuracoes.db_sysarqcamp   WHERE codarq = 4014 AND codcam IN (22396,22397,22398)");
    $this->execute("DELETE FROM configuracoes.db_syscampo     WHERE codcam IN (
        22396,22397,22398,
        22395
    )");

    $this->execute("UPDATE configuracoes.db_syscampo SET nomecam = 'rh197_horas_extras_50' where codcam = 22270");
    $this->execute("UPDATE configuracoes.db_syscampo SET nomecam = 'rh197_horas_extras_75' where codcam = 22271");
    $this->execute("UPDATE configuracoes.db_syscampo SET nomecam = 'rh197_horas_extras_100' where codcam = 22272");
  }

  public function upPRETabela_pontoeletronicoconfiguracoesgerais()
  {
    $this->tabela_db_syscampo->insert(array('codcam','nomecam','conteudo','descricao','valorinicial','rotulo','tamanho','nulo','maiusculo','autocompl','aceitatipo','tipoobj','rotulorel'),array(
        array(22395,'rh200_tipoasse_faltas_dsr','int4','Assentamentos de faltas para desconto do DSR','0', 'Faltas desconto DSR',19,'f','f','f',1,'text','Faltas desconto DSR')
    ));

    $this->tabela_db_sysarqcamp->insert(array('codarq','codcam','seqarq','codsequencia'), array(
        array(4024,22395,11,0)
    ));

    $this->tabela_db_sysforkey->insert(array('codarq', 'codcam', 'sequen', 'referen', 'tipoobjrel'), array(
        array(4024,22395,1,596,0)
    ));
  }
  
  public function upPRETabela_pontoeletronicoarquivodata()
  {
    $this->execute("UPDATE configuracoes.db_syscampo SET nomecam = 'rh197_horas_extras_50_d' where codcam = 22270");
    $this->execute("UPDATE configuracoes.db_syscampo SET nomecam = 'rh197_horas_extras_75_d' where codcam = 22271");
    $this->execute("UPDATE configuracoes.db_syscampo SET nomecam = 'rh197_horas_extras_100_d' where codcam = 22272");

    $this->tabela_db_syscampo->insert(array('codcam','nomecam','conteudo','descricao','valorinicial','rotulo','tamanho','nulo','maiusculo','autocompl','aceitatipo','tipoobj','rotulorel'),array(
      array(22396,'rh197_horas_extras_50_n','text','Horas extras 50% noturna','0', 'Horas Extras 50% Noturna',5,'t','f','f',1,'text','Horas Extras 50% Noturna'),
      array(22397,'rh197_horas_extras_75_n','text','Horas extras 75% noturnas','0', 'Horas Extras 75% Noturna',5,'t','f','f',1,'text','Horas Extras 75% Noturna'),
      array(22398,'rh197_horas_extras_100_n','text','Horas extras 100% noturnas','0', 'Horas Extras 100% Noturna',5,'t','f','f',1,'text','Horas Extras 100% Noturna')
    ));

    $this->tabela_db_sysarqcamp->insert(array('codarq','codcam','seqarq','codsequencia'), array(
      array(4014,22396,12,0),
      array(4014,22397,13,0),
      array(4014,22398,14,0)
    ));
  }

  public function upDDL()
  {
    $this->table('pontoeletronicoconfiguracoesgerais', array('schema'=>'recursoshumanos'))
         ->addColumn('rh200_tipoasse_faltas_dsr',       'integer',    array('null'=>true))
         ->addForeignKey('rh200_tipoasse_faltas_dsr',   'recursoshumanos.tipoasse',     'h12_codigo',       array('constraint'=>'pontoeletronicoconfiguracoesgerais_rh200_tipoasse_faltas_dsr_fk'))
         ->save();

    $this->execute("ALTER TABLE recursoshumanos.pontoeletronicoarquivodata RENAME COLUMN rh197_horas_extras_50 TO rh197_horas_extras_50_d");
    $this->execute("ALTER TABLE recursoshumanos.pontoeletronicoarquivodata RENAME COLUMN rh197_horas_extras_75 TO rh197_horas_extras_75_d");
    $this->execute("ALTER TABLE recursoshumanos.pontoeletronicoarquivodata RENAME COLUMN rh197_horas_extras_100 TO rh197_horas_extras_100_d");
  
    $this->table('pontoeletronicoarquivodata', array('schema'=>'recursoshumanos'))
         ->addColumn('rh197_horas_extras_50_n',       'string',    array('limit'=>5, 'null'=>true))
         ->addColumn('rh197_horas_extras_75_n',       'string',    array('limit'=>5, 'null'=>true))
         ->addColumn('rh197_horas_extras_100_n',      'string',    array('limit'=>5, 'null'=>true))
         ->save();
  }

  public function downDDL()
  {
    $this->table('pontoeletronicoconfiguracoesgerais', array('schema'=>'recursoshumanos'))
         ->removeColumn('rh200_tipoasse_faltas_dsr')
         ->save();

    $this->execute("ALTER TABLE recuroshumanos.pontoeletronicoarquivodata RENAME COLUMN rh197_horas_extras_50_d TO rh197_horas_extras_50");
    $this->execute("ALTER TABLE recuroshumanos.pontoeletronicoarquivodata RENAME COLUMN rh197_horas_extras_75_d TO rh197_horas_extras_75");
    $this->execute("ALTER TABLE recuroshumanos.pontoeletronicoarquivodata RENAME COLUMN rh197_horas_extras_100_d TO rh197_horas_extras_100");
    
    $this->table('pontoeletronicoarquivodata', array('schema'=>'recursoshumanos'))
         ->removeColumn('rh197_horas_extras_50_n')
         ->removeColumn('rh197_horas_extras_75_n')
         ->removeColumn('rh197_horas_extras_100_n')
         ->save();
  }

  public function incluirFormulaPadraoHorasPonto()
  {
    $formulasHorasPonto = $this->fetchAll("SELECT * FROM db_formulas WHERE db148_nome ILIKE 'FALTAS_DSR'");

    if(empty($formulasHorasPonto)) {
      $this->table('db_formulas', array('schema'=>'configuracoes'))->insert(array('db148_nome','db148_descricao','db148_formula'), array(
        array('FALTAS_DSR','RETORNA A QUANTIDADE DE FALTAS PARA O DESCONTO DO DSR','select h16_quant from assenta where h16_codigo = [CODIGO_ASSENTAMENTO]')
      ))->saveData();
    }
  }
}
