<?php

use Classes\PostgresMigration;

class M8646EventoRegimeTrabalho extends PostgresMigration
{

  public function up ()
  {

    $this->upEstruturaDicionarioDeDados();
    $this->upCriacaoTabelas();
    $this->execute("insert into pessoal.naturezatipoassentamento values (8, 'H.E. Manual');");
    $this->execute("insert into configuracoes.db_itensmenu values( 10433, 'Manutenção de Eventos', 'Manutenção dos eventos do ponto eletrônico', 'rec4_manutencaoeventos001.php', '1', '1', 'Manutenção dos eventos do ponto eletrônico', '1')");
    $this->execute("insert into configuracoes.db_itensfilho (id_item, codfilho) values(10433,1);");
    $this->execute("insert into configuracoes.db_menu values(10384,10433,6,2323);");
  }

  public function down()
  {

    $this->downEstruturaDicionarioDeDados();
    $this->downCriacaoTabelas();
    $this->execute("delete from pessoal.naturezatipoassentamento where rh159_sequencial = 8;");

    $this->execute("delete from configuracoes.db_menu where id_item = 10384 and id_item_filho = 10433;");
    $this->execute("delete from configuracoes.db_itensfilho where id_item = 10433;");
    $this->execute("delete from configuracoes.db_itensmenu where id_item = 10433;");

  }

  private function upCriacaoTabelas()
  {

    $tabelaEvento = $this->table('pontoeletronicoevento', array('schema' => 'recursoshumanos', 'id' => 'rh207_sequencial', 'primary_key' => array('rh207_sequencial')));
    $tabelaEvento
      ->addColumn('rh207_titulo'       , 'string', array('limit' => 100))
      ->addColumn('rh207_datainicial'  , 'date')
      ->addColumn('rh207_datafinal'    , 'date')
      ->addColumn('rh207_entrada_1'    , 'string', array('limit' => 5))
      ->addColumn('rh207_saida_1'      , 'string', array('limit' => 5))
      ->addColumn('rh207_horasextras_1', 'integer')
      ->addColumn('rh207_entrada_2'    , 'string', array('limit' => 5, 'null' => true))
      ->addColumn('rh207_saida_2'      , 'string', array('limit' => 5, 'null' => true))
      ->addColumn('rh207_horasextras_2', 'integer', array('null' => true))
      ->addColumn('rh207_instit',        'integer', array('null' => false))
      ->addForeignKey('rh207_instit', 'db_config', 'codigo')
      ->create();

    $tabelaEventoMatricula = $this->table('pontoeletronicoeventomatricula', array('schema' => 'recursoshumanos', 'id' => 'rh208_sequencial', 'primary_key' => array('rh208_sequencial')));
    $tabelaEventoMatricula
      ->addColumn('rh208_pontoeletronicoevento', 'integer')
      ->addColumn('rh208_rhpessoal', 'integer')
      ->addForeignKey('rh208_pontoeletronicoevento', 'pontoeletronicoevento', 'rh207_sequencial')
      ->addForeignKey('rh208_rhpessoal', 'rhpessoal', 'rh01_regist')
      ->create();

    $tabelaAssentamentoHora = $this->table('assentamentohoraextra', array('schema' => 'recursoshumanos', 'id' => 'h17_sequencial', 'primary_key' => array('h17_sequencial')));
    $tabelaAssentamentoHora
      ->addColumn('h17_assenta', 'integer')
      ->addColumn('h17_hora', 'string', array('limit' => 5))
      ->addColumn('h17_tipo', 'integer')
      ->addForeignKey('h17_assenta', 'assenta', 'h16_codigo')
      ->create();

  }

  private function downCriacaoTabelas()
  {
    $tabelaEventoMatricula = $this->table('pontoeletronicoeventomatricula', array('schema' => 'recursoshumanos'));
    $tabelaEventoMatricula->drop();

    $tabelaEvento = $this->table('pontoeletronicoevento', array('schema' => 'recursoshumanos'));
    $tabelaEvento->drop();

    $tabelaAssentamentoHora = $this->table('assentamentohoraextra', array('schema' => 'recursoshumanos'));
    $tabelaAssentamentoHora->drop();
  }


  private function upEstruturaDicionarioDeDados()
  {

    $this->execute(
      <<<SQLUP

insert into configuracoes.db_syscampo values(1009362,'rh207_sequencial','int4','Código sequencial','0', 'Código',10,'f','f','f',1,'text','Código');
insert into configuracoes.db_syscampo values(1009363,'rh207_titulo','varchar(100)','Descrição do evento','', 'Título',100,'f','t','f',0,'text','Título');
insert into configuracoes.db_syscampo values(1009364,'rh207_datainicial','date','Data Inicial','null', 'Data Inicial',10,'f','f','f',1,'text','Data Inicial');
insert into configuracoes.db_syscampo values(1009365,'rh207_datafinal','date','Data Final','null', 'Data Final',10,'f','f','f',1,'text','Data Final');
insert into configuracoes.db_syscampo values(1009366,'rh207_entrada_1','varchar(5)','Entrada 1','', 'Entrada 1',5,'f','t','f',0,'text','Entrada 1');
insert into configuracoes.db_syscampo values(1009367,'rh207_saida_1','varchar(5)','Saída 1','', 'Saída 1',5,'f','t','f',0,'text','Saída 1');
insert into configuracoes.db_syscampo values(1009368,'rh207_horasextras_1','int4','Horas Extras 1','0', 'Horas Extras 1',10,'f','f','f',1,'text','Horas Extras 1');
insert into configuracoes.db_syscampo values(1009369,'rh207_entrada_2','varchar(5)','Entrada 2','', 'Entrada 2',5,'t','t','f',0,'text','Entrada 2');
insert into configuracoes.db_syscampo values(1009370,'rh207_saida_2','varchar(5)','Saída 2','', 'Saída 2',5,'t','t','f',0,'text','Saída 2');
insert into configuracoes.db_syscampo values(1009371,'rh207_horasextras_2','int4','Horas Extras 2','0', 'Horas Extras 2',10,'t','f','f',1,'text','Horas Extras 2');
insert into configuracoes.db_syscampo values(1009385,'rh207_instit','int4','Código da Instituição','0', 'Instituição',10,'f','f','f',1,'text','Instituição');
insert into configuracoes.db_sysarquivo values (1010211, 'pontoeletronicoevento', 'Evento para o ponto eletrônico', 'rh207', '2017-07-27', 'pontoeletronicoevento', 0, 'f', 'f', 'f', 'f' );
insert into configuracoes.db_sysarqmod values (29,1010211);
delete from configuracoes.db_sysarqcamp where codarq = 1010211;
insert into configuracoes.db_sysarqcamp values(1010211,1009362,1,0);
insert into configuracoes.db_sysarqcamp values(1010211,1009363,2,0);
insert into configuracoes.db_sysarqcamp values(1010211,1009364,3,0);
insert into configuracoes.db_sysarqcamp values(1010211,1009365,4,0);
insert into configuracoes.db_sysarqcamp values(1010211,1009366,5,0);
insert into configuracoes.db_sysarqcamp values(1010211,1009367,6,0);
insert into configuracoes.db_sysarqcamp values(1010211,1009368,7,0);
insert into configuracoes.db_sysarqcamp values(1010211,1009369,8,0);
insert into configuracoes.db_sysarqcamp values(1010211,1009370,9,0);
insert into configuracoes.db_sysarqcamp values(1010211,1009371,10,0);
insert into configuracoes.db_sysarqcamp values(1010211,1009385,11,0);
insert into configuracoes.db_sysforkey values(1010211,1009385,1,83,0);
insert into configuracoes.db_syssequencia values(1000676, 'pontoeletronicoevento_rh207_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update configuracoes.db_sysarqcamp set codsequencia = 1000676 where codarq = 1010211 and codcam = 1009362;
delete from configuracoes.db_sysprikey where codarq = 1010211;
insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1010211,1009362,1,1009363);
insert into configuracoes.db_sysindices values(1008209,'pontoeletronicoevento_sequencial_in',1010211,'0');
insert into configuracoes.db_syscadind values(1008209,1009362,1);


insert into configuracoes.db_syscampo values(1009372,'rh208_sequencial','int4','Código sequencial','0', 'Código',10,'f','f','f',1,'text','Código');
insert into configuracoes.db_syscampo values(1009373,'rh208_pontoeletronicoevento','int4','Código do Evento','0', 'Código do Evento',10,'f','f','f',1,'text','Código do Evento');
insert into configuracoes.db_syscampo values(1009374,'rh208_rhpessoal','int4','Código da Matrícula','0', 'Código da Matrícula',10,'f','f','f',1,'text','Código da Matrícula');
insert into configuracoes.db_sysarquivo values (1010212, 'pontoeletronicoeventomatricula', 'Vinculo entre o evento e a matrícula', 'rh208', '2017-07-27', 'pontoeletronicoeventomatricula', 0, 'f', 'f', 'f', 'f' );
insert into configuracoes.db_sysarqmod values (29,1010212);
delete from configuracoes.db_sysarqcamp where codarq = 1010212;
insert into configuracoes.db_sysarqcamp values(1010212,1009372,1,0);
insert into configuracoes.db_sysarqcamp values(1010212,1009373,2,0);
insert into configuracoes.db_sysarqcamp values(1010212,1009374,3,0);
delete from configuracoes.db_sysforkey where codarq = 1010212 and referen = 0;
insert into configuracoes.db_sysforkey values(1010212,1009374,1,1153,0);
delete from configuracoes.db_sysforkey where codarq = 1010212 and referen = 0;
insert into configuracoes.db_sysforkey values(1010212,1009373,1,1010211,0);
insert into configuracoes.db_syssequencia values(1000677, 'pontoeletronicoeventomatricula_rh208_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update configuracoes.db_sysarqcamp set codsequencia = 1000677 where codarq = 1010212 and codcam = 1009372;
delete from configuracoes.db_sysprikey where codarq = 1010212;
insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1010212,1009372,1,1009373);
insert into configuracoes.db_sysindices values(1008210,'pontoeletronicoeventomatricula_pontoeletronicoevento_in',1010212,'0');
insert into configuracoes.db_syscadind values(1008210,1009373,1);

insert into configuracoes.db_syscampo values(1009375,'h17_assenta','int4','Código do Assentamento','0', 'Código do Assentamento',10,'f','f','f',1,'text','Código do Assentamento');
insert into configuracoes.db_syscampo values(1009376,'h17_hora','varchar(5)','Hora Extra','0', 'Hora Extra',5,'f','f','f',4,'text','Hora Extra');
insert into configuracoes.db_syscampo values(1009377,'h17_tipo','int4','Define o tipo de hora: HE50, HE75, HE100 ...','0', 'Tipo de Hora',10,'f','f','f',1,'text','Tipo de Hora');
insert into configuracoes.db_syscampo values(1009379,'h17_sequencial','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código');
insert into configuracoes.db_sysarquivo values (1010213, 'assentamentohoraextra', 'assentamentohoraextra', 'h17', '2017-07-31', 'assentamentohoraextra', 0, 'f', 'f', 'f', 'f' );
insert into configuracoes.db_sysarqmod values (29,1010213);
delete from configuracoes.db_sysarqcamp where codarq = 1010213;
insert into configuracoes.db_sysarqcamp values(1010213,1009379,1,0);
insert into configuracoes.db_sysarqcamp values(1010213,1009375,2,0);
insert into configuracoes.db_sysarqcamp values(1010213,1009376,3,0);
insert into configuracoes.db_sysarqcamp values(1010213,1009377,4,0);
delete from configuracoes.db_sysprikey where codarq = 1010213;
insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1010213,1009379,1,1009375);
delete from configuracoes.db_sysforkey where codarq = 1010213 and referen = 0;
insert into configuracoes.db_sysforkey values(1010213,1009375,1,528,0);
insert into configuracoes.db_sysindices values(1008211,'assentamentohoraextra_assenta_in',1010213,'0');
insert into configuracoes.db_syscadind values(1008211,1009375,1);

insert into configuracoes.db_syssequencia values(1000678, 'assentamentohoraextra_h17_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update configuracoes.db_sysarqcamp set codsequencia = 1000678 where codarq = 1010213 and codcam = 1009379;

SQLUP

    );
  }

  private function downEstruturaDicionarioDeDados()
  {

    $this->execute(
      <<<SQLDOWN
     
delete from configuracoes.db_syscadind where codind  in (1008209, 1008210, 1008211);
delete from configuracoes.db_sysindices where codarq in (1010211, 1010212, 1010213);
delete from configuracoes.db_sysprikey where codarq  in (1010211, 1010212, 1010213);
delete from configuracoes.db_syssequencia where codsequencia in (1000676, 1000677, 1000678);
delete from configuracoes.db_sysforkey where codarq in (1010211, 1010212, 1010213);
delete from configuracoes.db_sysarqcamp where codarq in (1010211, 1010212, 1010213);
delete from configuracoes.db_sysarqmod where codarq  in (1010211, 1010212, 1010213);
delete from configuracoes.db_sysarquivo where codarq in (1010211, 1010212, 1010213);
delete from configuracoes.db_syscampo where codcam   in (1009362,1009363,1009364,1009365,1009366,1009367,1009368,1009369,1009370,1009371, 1009372, 1009373, 1009374, 1009375, 1009376, 1009377, 1009379, 1009385);

SQLDOWN

    );

  }
}
