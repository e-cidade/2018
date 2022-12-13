<?php

use Classes\PostgresMigration;

class M7315 extends PostgresMigration
{
  private $tabela_db_sysarquivo;
  private $tabela_db_sysarqmod;
  private $tabela_db_syscampo;
  private $tabela_db_sysarqcamp;
  private $tabela_db_sysprikey;
  private $tabela_db_sysforkey;
  private $tabela_db_syssequencia;
  private $tabela_db_sysindices;
  private $tabela_db_syscadind;

  public function up()
  {
    $this->upDDL();
    $this->upDicionarioDados();

    $this->adicionarMenus();
    $this->migrarDados();
    $this->adicionarLayoutPontoEletronico();
    $this->removerPlugin();

    $this->adicionarNaturezaAssentamento();
    $this->adicionarFeriadosPadrao();

    $this->incluirFormulaPadraoHorasPonto();
  }

  public function upDicionarioDados()
  {
    $this->tabela_db_sysarquivo   = $this->table('db_sysarquivo',    array('schema'=>'configuracoes'));
    $this->tabela_db_sysarqmod    = $this->table('db_sysarqmod',     array('schema'=>'configuracoes'));
    $this->tabela_db_syscampo     = $this->table('db_syscampo',      array('schema'=>'configuracoes'));
    $this->tabela_db_syscampodef  = $this->table('db_syscampodef',   array('schema'=>'configuracoes'));
    $this->tabela_db_sysarqcamp   = $this->table('db_sysarqcamp',    array('schema'=>'configuracoes'));
    $this->tabela_db_sysprikey    = $this->table('db_sysprikey',     array('schema'=>'configuracoes'));
    $this->tabela_db_sysforkey    = $this->table('db_sysforkey',     array('schema'=>'configuracoes'));
    $this->tabela_db_syssequencia = $this->table('db_syssequencia',  array('schema'=>'configuracoes'));
    $this->tabela_db_sysindices   = $this->table('db_sysindices',    array('schema'=>'configuracoes'));
    $this->tabela_db_syscadind    = $this->table('db_syscadind',     array('schema'=>'configuracoes'));

    $this->upPRETabela_tipoassedb_depart();
    $this->upPRETabela_db_departrhlocaltrab();
    $this->upPRETabela_configuracoesdatasefetividade();
    $this->upPRETabela_tiporegistro();
    $this->upPRETabela_jornada();
    $this->upPRETabela_jornadahoras();
    $this->upPRETabela_gradeshorarios();
    $this->upPRETabela_gradeshorariosjornada();
    $this->upPRETabela_escalaservidor();
    $this->upPRETabela_assentamentofuncional();
    $this->upPRETabela_pontoeletronicojustificativa();
    $this->upPRETabela_pontoeletronicojustificativatipoasse();
    $this->upPRETabela_pontoeletronicoconfiguracoeslotacao();
    $this->upPreTabela_pontoeletronicoconfiguracoesgerais();
    $this->upPRETabela_pontoeletronicoarquivo();
    $this->upPRETabela_pontoeletronicoarquivodata();
    $this->upPRETabela_pontoeletronicoarquivodataregistro();
    $this->upPreTabela_pontoeletronicoregistrojustificativa();
    $this->upPreTabela_assentamentojustificativaperiodo();
    $this->upPRETabela_assenta();

    $this->tabela_db_sysarquivo->saveData();
    $this->tabela_db_sysarqmod->saveData();
    $this->tabela_db_syscampo->saveData();
    $this->tabela_db_syscampodef->saveData();
    $this->tabela_db_sysarqcamp->saveData();
    $this->tabela_db_sysprikey->saveData();
    $this->tabela_db_sysforkey->saveData();
    $this->tabela_db_syssequencia->saveData();
    $this->tabela_db_sysindices->saveData();
    $this->tabela_db_syscadind->saveData();
  }

  public function down()
  {
    $this->manterDadosParaPlugin();
    $this->downDDL();

    $this->execute("DELETE FROM db_syscadind    WHERE codind IN (4405, 4404, 4403, 4400, 4401, 4402, 4398, 4396, 4416, 4417)");
    $this->execute("DELETE FROM db_sysindices   WHERE codarq IN (4016, 4015, 4014, 4013, 4012, 4011, 4010, 4009, 4008, 4007, 4006, 4005, 4004, 4003, 4002, 4001, 4024, 4417)");
    $this->execute("DELETE FROM db_sysforkey    WHERE codarq IN (4029, 4028, 4024, 4016, 4015, 4014, 4013, 4012, 4011, 4010, 4009, 4008, 4007, 4006, 4005, 4004, 4003, 4002, 4001)");
    $this->execute("DELETE FROM db_sysprikey    WHERE codarq IN (4029, 4028, 4024, 4016, 4015, 4014, 4013, 4012, 4011, 4010, 4009, 4008, 4007, 4006, 4005, 4004, 4003, 4002, 4001)");
    $this->execute("DELETE FROM db_syssequencia WHERE codsequencia IN (1000653, 1000649, 1000642,1000641,1000640,1000639,1000638,1000637, 1000636, 1000635, 1000634, 1000633, 1000632, 1000631, 1000630, 1000629)");
    $this->execute("DELETE FROM db_sysarqcamp   WHERE codarq IN (4029, 4028, 4024, 4016, 4015, 4014, 4013, 4012, 4011, 4010, 4009, 4008, 4007, 4006, 4005, 4004, 4003, 4002, 4001)");
    $this->execute("DELETE FROM db_syscampodef  WHERE codcam IN (22226)");
    $this->execute("DELETE FROM db_syscampo     WHERE codcam IN (
            22254,22255,22256,22257,
            22258,22259,22260,22261,22262,22356,
            22263,22264,22265,22266,22267,22269,22270,22271,22272,22273,
            22274,22275,22276,22277,22278,22279,22280,
            22281,22282,22283,22284,22285,22286,22287,22288,22289,22290,22291,22292,22293,22337,22338,
            22294,22295,22296,22297,
            22327,22328,22329,22330,22331,22332,22333,22334,22335,22336,
            22357,22358,22359,
            22360,22361,

            22221,22222,22223,22224,22225,22226,22253,
            22216,22217,22218,22219,
            22213,22214,22215,
            22227,22228,
            22229,22230,22231,22344,
            22232,22233,22235,22236,
            22237,22238,22239,
            22240,22241,22242,22243,
            22244,22245,22246,22247,22248,
            22249,22250,22326
        )");
    $this->execute("DELETE FROM db_sysarqmod  WHERE codarq IN (4029, 4028, 4024, 4016, 4015, 4014, 4013, 4012, 4011, 4010, 4009, 4008, 4007, 4006, 4005, 4004, 4003, 4002, 4001)");
    $this->execute("DELETE FROM db_sysarquivo WHERE codarq IN (4029, 4028, 4024, 4016, 4015, 4014, 4013, 4012, 4011, 4010, 4009, 4008, 4007, 4006, 4005, 4004, 4003, 4002, 4001)");

    $this->execute("DELETE FROM db_menu
                              WHERE id_item_filho IN(10360,10361,10365,10366,10367,10368,10369,10370,10371,10372,10373,10374,10375,10376,10377,10378,10379,10380,10384,10385,10388,10389,10391,10392,10399)
                                AND modulo = 2323");

    $this->execute("DELETE FROM db_itensmenu WHERE id_item IN(10360,10361,10365,10366,10367,10368,10369,10370,10371,10372,10373,10374,10375,10376,10377,10378,10379,10380,10384,10385,10388,10389,10391,10392,10399)");

    $this->execute("DELETE FROM db_layoutcampos WHERE db52_codigo IN (15368,15369,15370,15371,15372,15373,15374,15375,15376,15377, 15378, 15379, 15380, 15381, 15382, 15383, 15384, 15385, 15386, 15387, 15388, 15389, 15390, 15391, 15392, 15393, 15394, 15395, 15396, 15397, 15398, 15399, 15400, 15401, 15402, 15403, 15404, 15405, 15406, 15407, 15408, 15409, 15410, 15411)");
    $this->execute("DELETE FROM db_layoutlinha WHERE db51_codigo IN (893,894,895,896,897,898)");
    $this->execute("DELETE FROM db_layouttxt WHERE db50_codigo = 278");
    $this->execute("DELETE FROM db_layouttxtgrupo WHERE db56_sequencial = 8");
    $this->execute("DELETE FROM db_layouttxtgrupotipo WHERE db57_sequencial = 3");
  }

  public function upPRETabela_tipoassedb_depart()
  {
    $this->tabela_db_sysarquivo->insert(array('codarq','nomearq','descricao','sigla','dataincl','rotulo','tipotabela','naolibclass','naolibfunc','naolibprog','naolibform'), array(
      array(4001,'tipoassedb_depart','Tabela de vínculo entre departamento e tipo de assentamento.','rh184','2016-12-13','Vínculo entre departamento e tipo de assentamento',0,'f','f','t','t')
    ));

    $this->tabela_db_sysarqmod->insert(array('codmod','codarq'), array(
      array(29,4001)
    ));

    $this->tabela_db_syscampo->insert(array('codcam','nomecam','conteudo','descricao','valorinicial','rotulo','tamanho','nulo','maiusculo','autocompl','aceitatipo','tipoobj','rotulorel'),array(
      array(22213,'rh184_sequencial','int4','Código sequencial da tabela.','0','Código',19,'f','f','f',1,'text','Código'),
      array(22214,'rh184_db_depart','int4','Código do departamento','0','Departamento',19,'f','f','f',1,'text','Departamento'),
      array(22215,'rh184_tipoasse','int4','Código do Tipo de assentamento.','0','Tipo de Assentamento',19,'f','f','f',1,'text','Tipo de Assentamento')
    ));

    $this->tabela_db_sysarqcamp->insert(array('codarq','codcam','seqarq','codsequencia'), array(
      array(4001, 22213, 1, 0),
      array(4001, 22214, 2, 0),
      array(4001, 22215, 3, 0)
    ));

    $this->tabela_db_sysprikey->insert(array('codarq','codcam','sequen','camiden'), array(
      array(4001, 22213, 1, 22213)
    ));

    $this->tabela_db_sysforkey->insert(array('codarq', 'codcam', 'sequen', 'referen', 'tipoobjrel'), array(
      array(4001, 22214, 1, 154, 0),
      array(4001, 22215, 1, 596, 0)
    ));

    $this->tabela_db_syssequencia->insert(array('codsequencia','nomesequencia','incrseq','minvalueseq','maxvalueseq','startseq','cacheseq'), array(
      array(1000629,'tipoassedb_depart_rh184_sequencial_seq',1,1,9223372036854775807,1,1)
    ));
    $this->execute('update db_sysarqcamp set codsequencia = 1000629 where codarq = 4001 and codcam = 22213');

    $this->tabela_db_sysindices->insert(array('codind', 'nomeind', 'codarq', 'campounico'), array(
      array(4396, 'tipoassedb_depart_db_depart_tipoasse_in', 4001, '0')
    ));

    $this->tabela_db_syscadind->insert(array('codind', 'codcam', 'sequen'), array(
      array(4396, 22214, 1),
      array(4396, 22215, 2)
    ));
  }

  public function upPRETabela_db_departrhlocaltrab()
  {
    $this->tabela_db_sysarquivo->insert(array('codarq','nomearq','descricao','sigla','dataincl','rotulo','tipotabela','naolibclass','naolibfunc','naolibprog','naolibform'), array(
      array(4002, 'db_departrhlocaltrab', 'Tabela que vincula departamento ao local de trabalho.', 'rh185', '2016-12-13', 'Vincula entre departamento ao local de trabalho', 0, 'f', 'f', 't', 't' )
    ));

    $this->tabela_db_sysarqmod->insert(array('codmod','codarq'), array(
      array(29,4002)
    ));

    $this->tabela_db_syscampo->insert(array('codcam','nomecam','conteudo','descricao','valorinicial','rotulo','tamanho','nulo','maiusculo','autocompl','aceitatipo','tipoobj','rotulorel'), array(
      array(22216,'rh185_sequencial','int4','Código sequencial da tabela.','0', 'Código',19,'f','f','f',1,'text','Código'),
      array(22217,'rh185_db_depart','int4','Vínculo com departamento','0', 'Departamento',19,'f','f','f',1,'text','Departamento'),
      array(22218,'rh185_rhlocaltrab','int4','Vínculo com local de trabalho','0', 'Local de Trabalho',19,'f','f','f',1,'text','Local de Trabalho'),
      array(22219,'rh185_instit','int4','Vínculo com a instituição','0', 'Instituição',19,'f','f','f',1,'text','Instituição')
    ));

    $this->tabela_db_sysarqcamp->insert(array('codarq','codcam','seqarq','codsequencia'), array(
      array(4002,22216,1,0),
      array(4002,22217,2,0),
      array(4002,22218,3,0),
      array(4002,22219,4,0)
    ));

    $this->tabela_db_sysprikey->insert(array('codarq','codcam','sequen','camiden'), array(
      array(4002,22216,1,22216)
    ));

    $this->tabela_db_sysforkey->insert(array('codarq', 'codcam', 'sequen', 'referen', 'tipoobjrel'), array(
      array(4002,22217,1,154,0),
      array(4002,22218,1,1542,0),
      array(4002,22219,2,1542,0)
    ));

    $this->tabela_db_syssequencia->insert(array('codsequencia','nomesequencia','incrseq','minvalueseq','maxvalueseq','startseq','cacheseq'), array(
      array(1000630, 'db_departrhlocaltrab_rh185_sequencial_seq', 1, 1, 9223372036854775807, 1, 1)
    ));
    $this->execute("update db_sysarqcamp set codsequencia = 1000630 where codarq = 4002 and codcam = 22216");

    $this->tabela_db_sysindices->insert(array('codind', 'nomeind', 'codarq', 'campounico'), array(
      array(4398,'db_departrhlocaltrab_db_depart_rhlocaltrab_instit_in',4002,'0')
    ));

    $this->tabela_db_syscadind->insert(array('codind', 'codcam', 'sequen'), array(
      array(4398,22217,1),
      array(4398,22218,2),
      array(4398,22219,3)
    ));
  }

  public function upPRETabela_configuracoesdatasefetividade()
  {
    $this->tabela_db_sysarquivo->insert(array('codarq','nomearq','descricao','sigla','dataincl','rotulo','tipotabela','naolibclass','naolibfunc','naolibprog','naolibform'), array(
      array(4003, 'configuracoesdatasefetividade', 'Tabela que configura a efetividade', 'rh186', '2016-12-13', 'Configuração da efetividade', 0, 'f', 'f', 't', 't' )
    ));

    $this->tabela_db_sysarqmod->insert(array('codmod','codarq'), array(
      array(29,4003)
    ));

    $this->tabela_db_syscampo->insert(array('codcam','nomecam','conteudo','descricao','valorinicial','rotulo','tamanho','nulo','maiusculo','autocompl','aceitatipo','tipoobj','rotulorel'), array(
      array(22221,'rh186_exercicio',                 'int4',       'Exercício da efetividade',           '0',    'Exercício',                   19, 't', 'f', 'f', 1, 'text', 'Exercício'),
      array(22222,'rh186_competencia',               'varchar(2)', 'Competência da configuração',         '',    'Competência',                 2,  't', 't', 'f', 0, 'text', 'Competência'),
      array(22223,'rh186_datainicioefetividade',     'date',       'Data de Início da Efetividade',      'null', 'Data Início Efetividade',     10, 't', 'f', 'f', 1, 'text', 'Data Início Efetividade'),
      array(22224,'rh186_datafechamentoefetividade', 'date',       'Data de fechamento da efetividade.', 'null', 'Data Fechamento Efetividade', 10, 't', 'f', 'f', 1, 'text', 'Data Fechamento Efetividade'),
      array(22225,'rh186_dataentregaefetividade',    'date',       'Data de entrega da efetividade.',    'null', 'Data Entrega Efetividade',    10, 't', 'f', 'f', 1, 'text', 'Data Entrega Efetividade'),
      array(22226,'rh186_processado',                'bool',       'Processado',                         'f',    'Processado',                   1, 'f', 'f', 'f', 5, 'text', 'Processado'),
      array(22253,'rh186_instituicao',               'int4',       'Instituição',                        '0',    'Instituição',                 19, 'f', 'f', 'f', 1, 'text', 'Instituição')
    ));

    $this->tabela_db_sysarqcamp->insert(array('codarq','codcam','seqarq','codsequencia'), array(
      array(4003,22221,1,0),
      array(4003,22222,2,0),
      array(4003,22223,3,0),
      array(4003,22224,4,0),
      array(4003,22225,5,0),
      array(4003,22226,6,0),
      array(4003,22253,7,0)
    ));

    $this->tabela_db_sysprikey->insert(array('codarq','codcam','sequen','camiden'), array(
      array(4003,22221,1,22221),
      array(4003,22222,2,22221),
      array(4003,22253,3,22221)
    ));

    $this->tabela_db_sysforkey->insert(array('codarq', 'codcam', 'sequen', 'referen', 'tipoobjrel'), array(
      array(4003,22253,1,83,0)
    ));

    $this->tabela_db_syscampodef->insert(array('codcam', 'defcampo', 'defdescr'), array(
      array(22226,'f','')
    ));
  }

  public function upPRETabela_tiporegistro()
  {
    $this->tabela_db_sysarquivo->insert(array('codarq','nomearq','descricao','sigla','dataincl','rotulo','tipotabela','naolibclass','naolibfunc','naolibprog','naolibform'), array(
      array(4004, 'tiporegistro', 'Tabela com os tipos de registro', 'rh187', '2016-12-13', 'Tipo de registro', 0, 'f', 'f', 't', 't' )
    ));

    $this->tabela_db_sysarqmod->insert(array('codmod','codarq'), array(
      array(29,4004)
    ));

    $this->tabela_db_syscampo->insert(array('codcam','nomecam','conteudo','descricao','valorinicial','rotulo','tamanho','nulo','maiusculo','autocompl','aceitatipo','tipoobj','rotulorel'), array(
      array(22227,'rh187_sequencial','int4','Código sequencial da tabela','0', 'Código',19,'f','f','f',1,'text','Código'),
      array(22228,'rh187_descricao','varchar(50)','Descrição do tipo de registro','', 'Descrição',50,'f','t','f',0,'text','Descrição')
    ));

    $this->tabela_db_sysarqcamp->insert(array('codarq','codcam','seqarq','codsequencia'), array(
      array(4004,22227,1,0),
      array(4004,22228,2,0)
    ));

    $this->tabela_db_sysprikey->insert(array('codarq','codcam','sequen','camiden'), array(
      array(4004,22227,1,22227)
    ));

    $this->tabela_db_syssequencia->insert(array('codsequencia','nomesequencia','incrseq','minvalueseq','maxvalueseq','startseq','cacheseq'), array(
      array(1000631, 'tiporegistro_rh187_sequencial_seq', 1, 1, 9223372036854775807, 1, 1)
    ));
    $this->execute("update db_sysarqcamp set codsequencia = 1000631 where codarq = 4004 and codcam = 22227");
  }

  public function upPRETabela_jornada()
  {
    $this->tabela_db_sysarquivo->insert(array('codarq','nomearq','descricao','sigla','dataincl','rotulo','tipotabela','naolibclass','naolibfunc','naolibprog','naolibform'), array(
      array(4005, 'jornada', 'Tabela com as jornadas de trabalho dos servidores', 'rh188', '2016-12-13', 'Jornadas de trabalho', 0, 'f', 'f', 't', 't' )
    ));

    $this->tabela_db_sysarqmod->insert(array('codmod','codarq'), array(
      array(29,4005)
    ));

    $this->tabela_db_syscampo->insert(array('codcam','nomecam','conteudo','descricao','valorinicial','rotulo','tamanho','nulo','maiusculo','autocompl','aceitatipo','tipoobj','rotulorel'), array(
      array(22229,'rh188_sequencial','int4','Código sequencial da tabela','0', 'Código',19,'f','f','f',1,'text','Código'),
      array(22230,'rh188_descricao','varchar(50)','Descrição das jornadas de trabalho','', 'Descrição',50,'f','t','f',0,'text','Descrição'),
      array(22231,'rh188_fixo','bool','Se a jornada é fixa','f', 'Fixo',1,'f','f','f',5,'text','Fixo'),
      array(22344,'rh188_tipo','char(1)','Informa se a jornada é F-Folga, D-DSR ou T-Trabalhado','', 'Tipo',1,'t','t','f',0,'text','Tipo')
    ));

    $this->tabela_db_sysarqcamp->insert(array('codarq','codcam','seqarq','codsequencia'), array(
      array(4005,22229,1,0),
      array(4005,22230,2,0),
      array(4005,22231,3,0),
      array(4005,22344,4,0)
    ));

    $this->tabela_db_sysprikey->insert(array('codarq','codcam','sequen','camiden'), array(
      array(4005,22229,1,22229)
    ));

    $this->tabela_db_syssequencia->insert(array('codsequencia','nomesequencia','incrseq','minvalueseq','maxvalueseq','startseq','cacheseq'), array(
      array(1000632, 'jornada_rh188_sequencial_seq', 1, 1, 9223372036854775807, 1, 1)
    ));
    $this->execute("update db_sysarqcamp set codsequencia = 1000632 where codarq = 4005 and codcam = 22229");
  }

  public function upPRETabela_jornadahoras()
  {
    $this->tabela_db_sysarquivo->insert(array('codarq','nomearq','descricao','sigla','dataincl','rotulo','tipotabela','naolibclass','naolibfunc','naolibprog','naolibform'), array(
      array(4006, 'jornadahoras', 'Horas da jornada de trabalho', 'rh189', '2016-12-13', 'Jornada de trabalho', 0, 'f', 'f', 't', 't' )
    ));

    $this->tabela_db_sysarqmod->insert(array('codmod','codarq'), array(
      array(29,4006)
    ));

    $this->tabela_db_syscampo->insert(array('codcam','nomecam','conteudo','descricao','valorinicial','rotulo','tamanho','nulo','maiusculo','autocompl','aceitatipo','tipoobj','rotulorel'), array(
      array(22232,'rh189_sequencial','int4','Código sequencial da tabela','0', 'Código',19,'f','f','f',1,'text','Código'),
      array(22233,'rh189_jornada','int4','Vínculo com a tabela de jornadas','0', 'Jornada',19,'f','f','f',1,'text','Jornada'),
      array(22235,'rh189_tiporegistro','int4','Tipo de registro computado','0', 'Tipo de Registro',19,'f','f','f',1,'text','Tipo de Registro'),
      array(22236,'rh189_hora','varchar(19)','Hora registrada','', 'Hora',19,'f','t','f',0,'text','Hora')
    ));

    $this->tabela_db_sysarqcamp->insert(array('codarq','codcam','seqarq','codsequencia'), array(
      array(4006,22232,1,0),
      array(4006,22233,2,0),
      array(4006,22235,3,0),
      array(4006,22236,4,0)
    ));

    $this->tabela_db_sysprikey->insert(array('codarq','codcam','sequen','camiden'), array(
      array(4006,22232,1,22232)
    ));

    $this->tabela_db_sysforkey->insert(array('codarq', 'codcam', 'sequen', 'referen', 'tipoobjrel'), array(
      array(4006,22233,1,4005,0),
      array(4006,22235,1,4004,0)
    ));

    $this->tabela_db_syssequencia->insert(array('codsequencia','nomesequencia','incrseq','minvalueseq','maxvalueseq','startseq','cacheseq'), array(
      array(1000633, 'jornadahoras_rh189_sequencial_seq', 1, 1, 9223372036854775807, 1, 1)
    ));
    $this->execute("update db_sysarqcamp set codsequencia = 1000633 where codarq = 4006 and codcam = 22232");

    $this->tabela_db_sysindices->insert(array('codind', 'nomeind', 'codarq', 'campounico'), array(
      array(4402,'jornadahoras_jornada_tiporegistro_in',4006,'0')
    ));

    $this->tabela_db_syscadind->insert(array('codind', 'codcam', 'sequen'), array(
      array(4402,22233,1),
      array(4402,22235,2)
    ));
  }

  public function upPRETabela_gradeshorarios()
  {
    $this->tabela_db_sysarquivo->insert(array('codarq','nomearq','descricao','sigla','dataincl','rotulo','tipotabela','naolibclass','naolibfunc','naolibprog','naolibform'), array(
      array(4007, 'gradeshorarios', 'Tabela com as grades de horários', 'rh190', '2016-12-13', 'Grades de horários', 0, 'f', 'f', 't', 't' )
    ));

    $this->tabela_db_sysarqmod->insert(array('codmod','codarq'), array(
      array(29,4007)
    ));

    $this->tabela_db_syscampo->insert(array('codcam','nomecam','conteudo','descricao','valorinicial','rotulo','tamanho','nulo','maiusculo','autocompl','aceitatipo','tipoobj','rotulorel'), array(
      array(22237,'rh190_sequencial','int4','Código sequencial da tabela','0', 'Código',19,'f','f','f',1,'text','Código'),
      array(22238,'rh190_descricao','varchar(50)','Descrição da grade de horários','', 'Descrição',50,'f','t','f',0,'text','Descrição'),
      array(22239,'rh190_database','date','Data base da grade de horários','null', 'Data Base',10,'f','f','f',1,'text','Data Base')
    ));

    $this->tabela_db_sysarqcamp->insert(array('codarq','codcam','seqarq','codsequencia'), array(
      array(4007,22237,1,0),
      array(4007,22238,2,0),
      array(4007,22239,3,0)
    ));

    $this->tabela_db_sysprikey->insert(array('codarq','codcam','sequen','camiden'), array(
      array(4007,22237,1,22237)
    ));

    $this->tabela_db_syssequencia->insert(array('codsequencia','nomesequencia','incrseq','minvalueseq','maxvalueseq','startseq','cacheseq'), array(
      array(1000634, 'gradeshorarios_rh190_sequencial_seq', 1, 1, 9223372036854775807, 1, 1)
    ));
    $this->execute("update db_sysarqcamp set codsequencia = 1000634 where codarq = 4007 and codcam = 22237");
  }

  public function upPRETabela_gradeshorariosjornada()
  {
    $this->tabela_db_sysarquivo->insert(array('codarq','nomearq','descricao','sigla','dataincl','rotulo','tipotabela','naolibclass','naolibfunc','naolibprog','naolibform'), array(
      array(4008, 'gradeshorariosjornada', 'Tabela que vincula a grade de horário com as jornadas', 'rh191', '2016-12-13', 'Vinculo da grade de horário com as jornadas', 0, 'f', 'f', 't', 't' )
    ));

    $this->tabela_db_sysarqmod->insert(array('codmod','codarq'), array(
      array(29,4008)
    ));

    $this->tabela_db_syscampo->insert(array('codcam','nomecam','conteudo','descricao','valorinicial','rotulo','tamanho','nulo','maiusculo','autocompl','aceitatipo','tipoobj','rotulorel'), array(
      array(22240,'rh191_sequencial','int4','Código sequencial da tabela','0', 'Código',19,'f','f','f',1,'text','Código'),
      array(22241,'rh191_gradehorarios','int4','Vínculo com a grade de horários','0', 'Grades de Horários',19,'f','f','f',1,'text','Grades de Horários'),
      array(22242,'rh191_ordemhorario','int4','Ordenação dos horários','0', 'Ordem',19,'f','f','f',1,'text','Ordem'),
      array(22243,'rh191_jornada','int4','Vínculo com a tabela de jornada','0', 'Jornada',19,'f','f','f',1,'text','Jornada')
    ));

    $this->tabela_db_sysarqcamp->insert(array('codarq','codcam','seqarq','codsequencia'), array(
      array(4008,22240,1,0),
      array(4008,22241,2,0),
      array(4008,22242,3,0),
      array(4008,22243,4,0)
    ));

    $this->tabela_db_sysprikey->insert(array('codarq','codcam','sequen','camiden'), array(
      array(4008,22240,1,22240)
    ));

    $this->tabela_db_sysforkey->insert(array('codarq', 'codcam', 'sequen', 'referen', 'tipoobjrel'), array(
      array(4008,22241,1,4007,0),
      array(4008,22243,1,4005,0)
    ));

    $this->tabela_db_syssequencia->insert(array('codsequencia','nomesequencia','incrseq','minvalueseq','maxvalueseq','startseq','cacheseq'), array(
      array(1000635, 'gradeshorariosjornada_rh191_sequencial_seq', 1, 1, 9223372036854775807, 1, 1)
    ));
    $this->execute("update db_sysarqcamp set codsequencia = 1000635 where codarq = 4008 and codcam = 22240");

    $this->tabela_db_sysindices->insert(array('codind', 'nomeind', 'codarq', 'campounico'), array(
      array(4401,'gradeshorariosjornada_gradeshorarios_ordemhorario_jornada_in',4008,'0')
    ));

    $this->tabela_db_syscadind->insert(array('codind', 'codcam', 'sequen'), array(
      array(4401,22241,1),
      array(4401,22242,2),
      array(4401,22243,3)
    ));
  }

  public function upPRETabela_escalaservidor()
  {
    $this->tabela_db_sysarquivo->insert(array('codarq','nomearq','descricao','sigla','dataincl','rotulo','tipotabela','naolibclass','naolibfunc','naolibprog','naolibform'), array(
      array(4009, 'escalaservidor', 'Tabela com as escalas do servidor', 'rh192', '2016-12-13', 'Escalas do servidor', 0, 'f', 'f', 't', 't' )
    ));

    $this->tabela_db_sysarqmod->insert(array('codmod','codarq'), array(
      array(29,4009)
    ));

    $this->tabela_db_syscampo->insert(array('codcam','nomecam','conteudo','descricao','valorinicial','rotulo','tamanho','nulo','maiusculo','autocompl','aceitatipo','tipoobj','rotulorel'), array(
      array(22244,'rh192_sequencial','int4','Código sequencial da tabela','0', 'Código',19,'f','f','f',1,'text','Código'),
      array(22245,'rh192_gradeshorarios','int4','Vínculo com a tabela de grades de horários','0', 'Grades de Horários',19,'f','f','f',1,'text','Grades de Horários'),
      array(22246,'rh192_regist','int4','Matrícula do servidor','0', 'Matrícula',19,'f','f','f',1,'text','Matrícula'),
      array(22247,'rh192_instit','int4','Código da instituição','0', 'Instituição',19,'f','f','f',1,'text','Instituição'),
      array(22248,'rh192_dataescala','date','Data da escala','null', 'Data da Escala',10,'f','f','f',1,'text','Data da Escala')
    ));

    $this->tabela_db_sysarqcamp->insert(array('codarq','codcam','seqarq','codsequencia'), array(
      array(4009,22244,1,0),
      array(4009,22245,2,0),
      array(4009,22246,3,0),
      array(4009,22247,4,0),
      array(4009,22248,5,0)
    ));

    $this->tabela_db_sysprikey->insert(array('codarq','codcam','sequen','camiden'), array(
      array(4009,22244,1,22244)
    ));

    $this->tabela_db_sysforkey->insert(array('codarq', 'codcam', 'sequen', 'referen', 'tipoobjrel'), array(
      array(4009,22245,1,4007,0),
      array(4009,22247,1,83,0)
    ));

    $this->tabela_db_syssequencia->insert(array('codsequencia','nomesequencia','incrseq','minvalueseq','maxvalueseq','startseq','cacheseq'), array(
      array(1000636, 'escalaservidor_rh192_sequencial_seq', 1, 1, 9223372036854775807, 1, 1)
    ));
    $this->execute("update db_sysarqcamp set codsequencia = 1000636 where codarq = 4009 and codcam = 22244");

    $this->tabela_db_sysindices->insert(array('codind', 'nomeind', 'codarq', 'campounico'), array(
      array(4400,'escalaservidor_gradeshorarios_regist_instit_in',4009,'0')
    ));

    $this->tabela_db_syscadind->insert(array('codind', 'codcam', 'sequen'), array(
      array(4400,22245,1),
      array(4400,22246,2),
      array(4400,22247,3)
    ));
  }

  public function upPRETabela_assentamentofuncional()
  {
    $this->tabela_db_sysarquivo->insert(array('codarq','nomearq','descricao','sigla','dataincl','rotulo','tipotabela','naolibclass','naolibfunc','naolibprog','naolibform'), array(
      array(4010, 'assentamentofuncional', 'Tabela que vincula um assentamento afim de torná-lo um assentamento de vida funcional', 'rh193', '2016-12-13', 'Assentamento de vida funcional', 0, 'f', 't', 't', 't' )
    ));

    $this->tabela_db_sysarqmod->insert(array('codmod','codarq'), array(
      array(29,4010)
    ));

    $this->tabela_db_syscampo->insert(array('codcam','nomecam','conteudo','descricao','valorinicial','rotulo','tamanho','nulo','maiusculo','autocompl','aceitatipo','tipoobj','rotulorel'), array(
      array(22249,'rh193_assentamento_funcional','int4','Vínculo com a tabela de assentamentos','0', 'Assentamento de Vida Funcional',19,'f','f','f',1,'text','Assentamento de Vida Funcional'),
      array(22250,'rh193_assentamento_efetividade','int4','Vínculo com a tabela assenta','0', 'Assentamento de Efetividade',19,'f','f','f',1,'text','Assentamento de Efetividade')
    ));

    $this->tabela_db_sysarqcamp->insert(array('codarq','codcam','seqarq','codsequencia'), array(
      array(4010,22249,1,0),
      array(4010,22250,2,0)
    ));

    $this->tabela_db_sysforkey->insert(array('codarq', 'codcam', 'sequen', 'referen', 'tipoobjrel'), array(
      array(4010,22249,1,528,0),
      array(4010,22250,1,528,0)
    ));

    $this->tabela_db_sysindices->insert(array('codind', 'nomeind', 'codarq', 'campounico'), array(
      array(4403,'assentamentofuncional_un_in',4010,'1')
    ));

    $this->tabela_db_syscadind->insert(array('codind', 'codcam', 'sequen'), array(
      array(4403,22249,1)
    ));
  }

  public function upPreTabela_pontoeletronicojustificativa()
  {
    $this->tabela_db_sysarquivo->insert(array('codarq','nomearq','descricao','sigla','dataincl','rotulo','tipotabela','naolibclass','naolibfunc','naolibprog','naolibform'), array(
      array(4011, 'pontoeletronicojustificativa', 'Tabela de justificativas do ponto eletrônico', 'rh194', '2016-12-21', 'Tabela de justificativas', 0, 'f', 'f', 't', 't' )
    ));

    $this->tabela_db_sysarqmod->insert(array('codmod','codarq'), array(
      array(29,4011)
    ));

    $this->tabela_db_syscampo->insert(array('codcam','nomecam','conteudo','descricao','valorinicial','rotulo','tamanho','nulo','maiusculo','autocompl','aceitatipo','tipoobj','rotulorel'), array(
      array(22294,'rh194_sequencial','int4','Código sequencial da tabela','0', 'Código',19,'f','f','f',1,'text','Código'),
      array(22295,'rh194_descricao','varchar(50)','Descrição da justificativa','', 'Descrição',50,'f','t','f',0,'text','Descrição'),
      array(22296,'rh194_sigla','varchar(3)','Sigla para a justificativa, será exibida no espelho ponto','', 'Sigla',3,'f','t','f',0,'text','Sigla'),
      array(22297,'rh194_instituicao','int4','Vínculo com a instituição','0', 'Instituição',19,'f','f','f',1,'text','Instituição')
    ));

    $this->tabela_db_sysarqcamp->insert(array('codarq','codcam','seqarq','codsequencia'), array(
      array(4011,22294,1,0),
      array(4011,22295,2,0),
      array(4011,22296,3,0),
      array(4011,22297,4,0)
    ));

    $this->tabela_db_syssequencia->insert(array('codsequencia','nomesequencia','incrseq','minvalueseq','maxvalueseq','startseq','cacheseq'), array(
      array(1000642, 'pontoeletronicojustificativa_rh194_sequencial_seq', 1, 1, 9223372036854775807, 1, 1)
    ));
    $this->execute('update db_sysarqcamp set codsequencia = 1000642 where codarq = 4011 and codcam = 22294');

    $this->tabela_db_sysprikey->insert(array('codarq','codcam','sequen','camiden'), array(
      array(4011,22294,1,22294)
    ));

    // $this->tabela_db_sysforkey->insert(array('codarq', 'codcam', 'sequen', 'referen', 'tipoobjrel'), array(
    // ));

    $this->tabela_db_sysindices->insert(array('codind', 'nomeind', 'codarq', 'campounico'), array(
      array(4405,'pontoeletronicojustificativa_un_in',4011,'1')
    ));

    $this->tabela_db_syscadind->insert(array('codind', 'codcam', 'sequen'), array(
      array(4405,22296,1),
      array(4405,22297,2)
    ));
  }

  public function upPreTabela_pontoeletronicojustificativatipoasse()
  {
    $this->tabela_db_sysarquivo->insert(array('codarq','nomearq','descricao','sigla','dataincl','rotulo','tipotabela','naolibclass','naolibfunc','naolibprog','naolibform'), array(
      array(4028, 'pontoeletronicojustificativatipoasse', 'Tabela de vínculo entre justificativas e tipos de assentamentos.', 'rh205', '2017-02-13', 'Vínculo entre justificativas e tipos assentamentos', 0, 'f', 't', 't', 't' )
    ));

    $this->tabela_db_sysarqmod->insert(array('codmod','codarq'), array(
      array(29,4028)
    ));

    $this->tabela_db_syscampo->insert(array('codcam','nomecam','conteudo','descricao','valorinicial','rotulo','tamanho','nulo','maiusculo','autocompl','aceitatipo','tipoobj','rotulorel'), array(
      array(22357,'rh205_sequencial','int4','Código sequencial da tabela','0', 'Código',19,'f','f','f',1,'text','Código'),
      array(22358,'rh205_pontoeletronicojustificativa','int4','Vínculo com a tabela de justificativas','0', 'Justificativa',19,'f','f','f',1,'text','Justificativa'),
      array(22359,'rh205_tipoasse','int4','Vínculo com a tabela de tipos de assentamento','0', 'Tipo de Assentamento',19,'f','f','f',1,'text','Tipo de Assentamento')
    ));

    $this->tabela_db_sysarqcamp->insert(array('codarq','codcam','seqarq','codsequencia'), array(
      array(4028,22357,1,0),
      array(4028,22358,2,0),
      array(4028,22359,3,0)
    ));

    $this->tabela_db_syssequencia->insert(array('codsequencia','nomesequencia','incrseq','minvalueseq','maxvalueseq','startseq','cacheseq'), array(
      array(1000653, 'pontoeletronicojustificativatipoasse_rh205_sequencial_seq', 1, 1, 9223372036854775807, 1, 1)
    ));
    $this->execute('update db_sysarqcamp set codsequencia = 1000653 where codarq = 4028 and codcam = 22357');

    $this->tabela_db_sysprikey->insert(array('codarq','codcam','sequen','camiden'), array(
      array(4028,22357,1,22357)
    ));

    $this->tabela_db_sysforkey->insert(array('codarq', 'codcam', 'sequen', 'referen', 'tipoobjrel'), array(
      array(4028,22359,1,596,0),
      array(4028,22358,1,4011,0)
    ));
  }

  public function upPRETabela_pontoeletronicoconfiguracoeslotacao()
  {
    $this->tabela_db_sysarquivo->insert(array('codarq','nomearq','descricao','sigla','dataincl','rotulo','tipotabela','naolibclass','naolibfunc','naolibprog','naolibform'), array(
      array(4012, 'pontoeletronicoconfiguracoeslotacao', 'Tabela de configurações de horas e rubricas para lançamento dos valores no ponto.', 'rh195', '2016-12-21', '', 0, 'f', 'f', 't', 't' )
    ));

    $this->tabela_db_sysarqmod->insert(array('codmod','codarq'), array(
      array(29,4012)
    ));

    $this->tabela_db_syscampo->insert(array('codcam','nomecam','conteudo','descricao','valorinicial','rotulo','tamanho','nulo','maiusculo','autocompl','aceitatipo','tipoobj','rotulorel'), array(
      array(22281,'rh195_sequencial','int4','Código sequencial da tabela','0', 'Código',19,'f','f','f',1,'text','Código'),
      array(22283,'rh195_tolerancia','int4','Tolerância das batidas','0', 'Tolerância',19,'f','f','f',1,'text','Tolerância'),
      array(22284,'rh195_hora_extra_50','text','Tempo quando começa a contar como hora extra de 50%','', 'Horas Extras 50% Até',5,'f','t','f',0,'text','Horas Extras 50%'),
      array(22285,'rh195_hora_extra_75','text','Tempo para começar a contar como horas extras 75%','', 'Horas Extras 75%',5,'t','t','f',0,'text','Horas Extras 75%'),
      array(22286,'rh195_hora_extra_100','text','Tempo que começa a contar como hora extra 100%','', 'Horas Extras 100%',5,'t','t','f',0,'text','Horas Extras 100%'),
      array(22337,'rh195_lotacao','int4','Lotação','0', 'Lotação',19,'f','f','f',1,'text','Lotação'),
      array(22338,'rh195_supervisor','int4','Supervisor responsável por assinar o espelho ponto','0', 'Supervisor',19,'f','f','f',1,'text','Supervisor')
    ));

    $this->tabela_db_sysarqcamp->insert(array('codarq','codcam','seqarq','codsequencia'), array(
      array(4012,22281,1,0),
      array(4012,22283,2,0),
      array(4012,22284,3,0),
      array(4012,22285,4,0),
      array(4012,22286,5,0),
      array(4012,22337,6,0),
      array(4012,22338,7,0)
    ));

    $this->tabela_db_syssequencia->insert(array('codsequencia','nomesequencia','incrseq','minvalueseq','maxvalueseq','startseq','cacheseq'), array(
      array(1000641, 'pontoeletronicoconfiguracoes_rh195_sequencial_seq', 1, 1, 9223372036854775807, 1, 1)
    ));
    $this->execute("update db_sysarqcamp set codsequencia = 1000641 where codarq = 4012 and codcam = 22281");

    $this->tabela_db_sysprikey->insert(array('codarq','codcam','sequen','camiden'), array(
      array(4012,22281,1,22281)
    ));

    $this->tabela_db_sysforkey->insert(array('codarq', 'codcam', 'sequen', 'referen', 'tipoobjrel'), array(
      array(4012,22337,1,894,0),
      array(4012,22338,1,1153,0)
    ));
  }

  public function upPreTabela_pontoeletronicoconfiguracoesgerais()
  {
    $this->tabela_db_sysarquivo->insert(array('codarq','nomearq','descricao','sigla','dataincl','rotulo','tipotabela','naolibclass','naolibfunc','naolibprog','naolibform'), array(
      array(4024, 'pontoeletronicoconfiguracoesgerais', 'Configurações gerais da efetividade', 'rh200', '2017-01-18', 'Configurações gerais da efetividade', 0, 'f', 'f', 't', 't' )
    ));

    $this->tabela_db_sysarqmod->insert(array('codmod','codarq'), array(
      array(29,4024)
    ));

    $this->tabela_db_syscampo->insert(array('codcam','nomecam','conteudo','descricao','valorinicial','rotulo','tamanho','nulo','maiusculo','autocompl','aceitatipo','tipoobj','rotulorel'), array(
      array(22327,'rh200_sequencial','int4','Código sequencial da tabela','0', 'Código',19,'f','f','f',1,'text','Código'),
      array(22328,'rh200_instituicao','int4','Instituição da configuração','0', 'Instituição',19,'f','f','f',1,'text','Instituição'),
      array(22329,'rh200_tipoasse_extra50diurna','int4','Horas extras 50% diurnas','0', 'Horas Extras 50% Diurna',19,'t','f','f',1,'text','Horas Extras 50% Diurna'),
      array(22330,'rh200_tipoasse_extra75diurna','int4','Horas extras 75% diurnas','0', 'Horas Extras 75% Diurna',19,'t','f','f',1,'text','Horas Extras 75% Diurna'),
      array(22331,'rh200_tipoasse_extra100diurna','int4','Horas extras 100% diurnas','0', 'Horas Extras 100% Diurna',19,'t','f','f',1,'text','Horas Extras 100% Diurna'),
      array(22332,'rh200_tipoasse_extra50noturna','int4','Horas extras 50% noturnas','0', 'Horas Extras 50% Noturna',19,'t','f','f',1,'text','Horas Extras 50% Noturna'),
      array(22333,'rh200_tipoasse_extra75noturna','int4','Horas extras 75% noturna','0', 'Horas Extras 75% Noturna',19,'t','f','f',1,'text','Horas Extras 75% Noturna'),
      array(22334,'rh200_tipoasse_extra100noturna','int4','Horas extras 100% noturnas','0', 'Horas Extras 100% Noturna',19,'t','f','f',1,'text','Horas Extras 100% Noturna'),
      array(22335,'rh200_tipoasse_adicionalnoturno','int4','Horas adicional noturno','0', 'Horas Adicional Noturno',19,'t','f','f',1,'text','Horas Adicional Noturno'),
      array(22336,'rh200_tipoasse_falta','int4','Horas falta','0', 'Horas Falta',19,'t','f','f',1,'text','Horas Falta')
    ));

    $this->tabela_db_sysarqcamp->insert(array('codarq','codcam','seqarq','codsequencia'), array(
      array(4024,22327,1,0),
      array(4024,22328,2,0),
      array(4024,22329,3,0),
      array(4024,22330,4,0),
      array(4024,22331,5,0),
      array(4024,22332,6,0),
      array(4024,22333,7,0),
      array(4024,22334,8,0),
      array(4024,22335,9,0),
      array(4024,22336,10,0)
    ));

    $this->tabela_db_syssequencia->insert(array('codsequencia','nomesequencia','incrseq','minvalueseq','maxvalueseq','startseq','cacheseq'), array(
      array(1000649, 'pontoeletronicoconfiguracoesgerais_rh200_sequencial_seq', 1, 1, 9223372036854775807, 1, 1)
    ));
    $this->execute("update db_sysarqcamp set codsequencia = 1000649 where codarq = 4024 and codcam = 22327");

    $this->tabela_db_sysprikey->insert(array('codarq','codcam','sequen','camiden'), array(
      array(4024,22327,1,22327)
    ));
    
    $this->tabela_db_sysforkey->insert(array('codarq', 'codcam', 'sequen', 'referen', 'tipoobjrel'), array(
      array(4024,22328,1,83,0),
      array(4024,22329,1,596,0),
      array(4024,22330,2,596,0),
      array(4024,22331,3,596,0),
      array(4024,22332,4,596,0),
      array(4024,22333,5,596,0),
      array(4024,22334,6,596,0),
      array(4024,22335,7,596,0),
      array(4024,22336,8,596,0)
    ));

    $this->tabela_db_sysindices->insert(array('codind', 'nomeind', 'codarq', 'campounico'), array(
      array(4416,'pontoeletronicoconfiguracoesgerais_un_in',4024,'1')
    ));
    
    $this->tabela_db_syscadind->insert(array('codind', 'codcam', 'sequen'), array(
      array(4416,22328,1)
    ));
  }

  public function upPreTabela_pontoeletronicoarquivo()
  {
    $this->tabela_db_sysarquivo->insert(array('codarq','nomearq','descricao','sigla','dataincl','rotulo','tipotabela','naolibclass','naolibfunc','naolibprog','naolibform'), array(
      array(4013, 'pontoeletronicoarquivo', 'Tabela que guarda o arquivo do ponto importado', 'rh196', '2016-12-21', 'Tabela com arquivo do ponto importado', 0, 'f', 'f', 't', 't' )
    ));

    $this->tabela_db_sysarqmod->insert(array('codmod','codarq'), array(
      array(29,4013)
    ));

    $this->tabela_db_syscampo->insert(array('codcam','nomecam','conteudo','descricao','valorinicial','rotulo','tamanho','nulo','maiusculo','autocompl','aceitatipo','tipoobj','rotulorel'), array(
      array(22274,'rh196_sequencial','int4','Código sequencial da tabela','0', 'Código',19,'f','f','f',1,'text','Código'),
      array(22275,'rh196_instituicao','int4','Vínculo com a efetividade, instituição','0', 'Instituição',19,'f','f','f',1,'text','Instituição'),
      array(22276,'rh196_efetividade_exercicio','int4','Vínculo com a efetividade, exercício','0', 'Exercício da Efetividade',19,'f','f','f',1,'text','Exercício da Efetividade'),
      array(22277,'rh196_efetividade_competencia','varchar(2)','Vínculo com a efetividade, competência','', 'Competência da Efetividade',2,'f','t','f',0,'text','Competência da Efetividade'),
      array(22278,'rh196_ano','int4','Ano da competência','0', 'Ano da Competência',19,'t','f','f',1,'text','Ano da Competência'),
      array(22279,'rh196_mes','int4','Mês da competência','0', 'Mês da competência',19,'t','f','f',1,'text','Mês da competência'),
      array(22280,'rh196_arquivo','oid','Arquivo de marcaçõs do ponto','', 'Arquivo',1,'f','f','f',0,'text','Arquivo')
    ));

    $this->tabela_db_sysarqcamp->insert(array('codarq','codcam','seqarq','codsequencia'), array(
      array(4013,22274,1,0),
      array(4013,22276,2,0),
      array(4013,22277,3,0),
      array(4013,22275,4,0),
      array(4013,22278,5,0),
      array(4013,22279,6,0),
      array(4013,22280,7,0)
    ));

    $this->tabela_db_syssequencia->insert(array('codsequencia','nomesequencia','incrseq','minvalueseq','maxvalueseq','startseq','cacheseq'), array(
      array(1000640, 'pontoeletronicoarquivo_rh196_sequencial_seq', 1, 1, 9223372036854775807, 1, 1)
    ));
    $this->execute("update db_sysarqcamp set codsequencia = 1000640 where codarq = 4013 and codcam = 22274");

    $this->tabela_db_sysprikey->insert(array('codarq','codcam','sequen','camiden'), array(
      array(4013,22274,1,22274)
    ));
    // $this->tabela_db_sysforkey->insert(array('codarq', 'codcam', 'sequen', 'referen', 'tipoobjrel'), array(
    // ));
    // $this->tabela_db_sysindices->insert(array('codind', 'nomeind', 'codarq', 'campounico'), array(
    // ));
    // $this->tabela_db_syscadind->insert(array('codind', 'codcam', 'sequen'), array(
    // ));
  }

  public function upPreTabela_pontoeletronicoarquivodata()
  {
    $this->tabela_db_sysarquivo->insert(array('codarq','nomearq','descricao','sigla','dataincl','rotulo','tipotabela','naolibclass','naolibfunc','naolibprog','naolibform'), array(
      array(4014, 'pontoeletronicoarquivodata', 'Tabela com as datas de marcações do registro de ponto.', 'rh197', '2016-12-22', 'Datas das marcações do arquivo', 0, 'f', 'f', 't', 't' )
    ));

    $this->tabela_db_sysarqmod->insert(array('codmod','codarq'), array(
      array(29,4014)
    ));

    $this->tabela_db_syscampo->insert(array('codcam','nomecam','conteudo','descricao','valorinicial','rotulo','tamanho','nulo','maiusculo','autocompl','aceitatipo','tipoobj','rotulorel'), array(
      array(22263,'rh197_sequencial','int4','Código sequencial da tabela','0', 'Código',19,'f','f','f',1,'text','Código'),
      array(22264,'rh197_pontoeletronicoarquivo','int4','Vínculo com o arquivo de marcações do ponto','0', 'Arquivo',19,'f','f','f',1,'text','Arquivo'),
      array(22265,'rh197_data','date','Data do registro do ponto','null', 'Data',10,'f','f','f',1,'text','Data'),
      array(22266,'rh197_matricula','int4','Mátricula do servidor','0', 'Mátricula',19,'t','f','f',1,'text','Mátricula'),
      array(22267,'rh197_horas_trabalhadas','text','Horas trabalhadas','0', 'Horas Trabalhadas',5,'t','t','f',0,'text','Horas Trabalhadas'),
      array(22269,'rh197_horas_falta','text','Horas de falta','0', 'Horas Falta',5,'t','t','f',0,'text','Horas Falta'),
      array(22270,'rh197_horas_extras_50','text','Horas extras 50%','', 'Horas Extras 50%',5,'t','t','f',0,'text','Horas Extras 50%'),
      array(22271,'rh197_horas_extras_75','text','Horas extras 75%','', 'Horas Extras 75%',5,'t','t','f',0,'text','Horas Extras 75%'),
      array(22272,'rh197_horas_extras_100','text','Horas extras 100%','', 'Horas Extras 100%',5,'t','t','f',0,'text','Horas Extras 100%'),
      array(22273,'rh197_horas_adicinal_noturno','text','Horas de adicional noturno','', 'Horas Adicional Noturno',5,'t','t','f',0,'text','Horas Adicional Noturno'),
      array(22326,'rh197_pis','varchar(11)','Número PIS do servidor.','', 'PIS',11,'f','t','f',0,'text','PIS')
    ));

    $this->tabela_db_sysarqcamp->insert(array('codarq','codcam','seqarq','codsequencia'), array(
      array(4014,22263,1,0),
      array(4014,22264,2,0),
      array(4014,22265,3,0),
      array(4014,22266,4,0),
      array(4014,22267,5,0),
      array(4014,22269,7,0),
      array(4014,22270,8,0),
      array(4014,22271,9,0),
      array(4014,22272,10,0),
      array(4014,22273,11,0),
      array(4014,22326,12,0)
    ));

    $this->tabela_db_syssequencia->insert(array('codsequencia','nomesequencia','incrseq','minvalueseq','maxvalueseq','startseq','cacheseq'), array(
      array(1000639, 'pontoeletronicoarquivodata_rh197_sequencial_seq', 1, 1, 9223372036854775807, 1, 1)
    ));
    $this->execute("update db_sysarqcamp set codsequencia = 1000639 where codarq = 4014 and codcam = 22263");

    $this->tabela_db_sysprikey->insert(array('codarq','codcam','sequen','camiden'), array(
      array(4014,22263,1,22263)
    ));

    // $this->tabela_db_sysforkey->insert(array('codarq', 'codcam', 'sequen', 'referen', 'tipoobjrel'), array(
    // ));

    $this->tabela_db_sysindices->insert(array('codind', 'nomeind', 'codarq', 'campounico'), array(
      array(4404,'pontoeletronicoarquivodata_pontoeletronicoarquivo_data_pis_un_in',4014,'1')
    ));

    $this->tabela_db_syscadind->insert(array('codind', 'codcam', 'sequen'), array(
      array(4404,22264,1),
      array(4404,22265,2),
      array(4404,22326,3)
    ));
  }

  public function upPreTabela_pontoeletronicoarquivodataregistro()
  {
    $this->tabela_db_sysarquivo->insert(array('codarq','nomearq','descricao','sigla','dataincl','rotulo','tipotabela','naolibclass','naolibfunc','naolibprog','naolibform'), array(
      array(4015, 'pontoeletronicoarquivodataregistro', 'Tabela que guarda as marcações do arquivo de ponto eletrônico.', 'rh198', '2016-12-22', 'Marcações do arquivo de ponto', 0, 'f', 'f', 't', 't' )
    ));

    $this->tabela_db_sysarqmod->insert(array('codmod','codarq'), array(
      array(29,4015)
    ));

    $this->tabela_db_syscampo->insert(array('codcam','nomecam','conteudo','descricao','valorinicial','rotulo','tamanho','nulo','maiusculo','autocompl','aceitatipo','tipoobj','rotulorel'), array(
      array(22258,'rh198_sequencial','int4','Código sequencial da tabela','0', 'Código',19,'f','f','f',1,'text','Código'),
      array(22259,'rh198_pontoeletronicoarquivodata','int4','Vínculo com a tabela que guarda das datas dos registros.','0', 'Data Registro',19,'f','f','f',1,'text','Data Registro'),
      array(22260,'rh198_registro','text','Registros de entrada e saída do arquivo ponto','', 'Registro',5,'t','t','f',0,'text','Registro'),
      array(22261,'rh198_registro_manual','bool','Informa se o registro foi feito manualmente','f', 'Registro Manual',1,'f','f','f',5,'text','Registro Manual'),
      array(22262,'rh198_ordem','int4','Ordem dos registros do ponto no dia','0', 'Ordem',19,'f','f','f',1,'text','Ordem'),
      array(22356,'rh198_data','date','Data da batida do ponto.','null', 'Data',10,'f','f','f',1,'text','Data')
    ));

    $this->tabela_db_sysarqcamp->insert(array('codarq','codcam','seqarq','codsequencia'), array(
      array(4015,22258,1,0),
      array(4015,22259,2,0),
      array(4015,22260,3,0),
      array(4015,22261,4,0),
      array(4015,22262,5,0),
      array(4015,22356,6,0)
    ));

    $this->tabela_db_syssequencia->insert(array('codsequencia','nomesequencia','incrseq','minvalueseq','maxvalueseq','startseq','cacheseq'), array(
      array(1000638, 'pontoeletronicoarquivodataregistro_rh198_sequencial_seq', 1, 1, 9223372036854775807, 1, 1)
    ));
    $this->execute("update db_sysarqcamp set codsequencia = 1000638 where codarq = 4015 and codcam = 22258");

    $this->tabela_db_sysprikey->insert(array('codarq','codcam','sequen','camiden'), array(
      array(4015,22258,1,22258)
    ));

     $this->tabela_db_sysforkey->insert(array('codarq', 'codcam', 'sequen', 'referen', 'tipoobjrel'), array(
       array(4015,22259,1,4014,0)
     ));

     $this->tabela_db_sysindices->insert(array('codind', 'nomeind', 'codarq', 'campounico'), array(
       array(4417,'pontoeletronicoarquivodataregistro_pontoeletronicoarquivodata_in',4015,'0')
     ));

     $this->tabela_db_syscadind->insert(array('codind', 'codcam', 'sequen'), array(
       array(4417,22259,1)
     ));
  }

  public function upPreTabela_pontoeletronicoregistrojustificativa()
  {
    $this->tabela_db_sysarquivo->insert(array('codarq','nomearq','descricao','sigla','dataincl','rotulo','tipotabela','naolibclass','naolibfunc','naolibprog','naolibform'), array(
      array(4016, 'pontoeletronicoregistrojustificativa', 'Tabela que vincula uma justificativa à uma marcação de horário.', 'rh199', '2016-12-22', 'Vinculo entre justificativa e marcação', 0, 'f', 'f', 't', 't' )
    ));

    $this->tabela_db_sysarqmod->insert(array('codmod','codarq'), array(
      array(29,4016)
    ));

    $this->tabela_db_syscampo->insert(array('codcam','nomecam','conteudo','descricao','valorinicial','rotulo','tamanho','nulo','maiusculo','autocompl','aceitatipo','tipoobj','rotulorel'), array(
      array(22254,'rh199_sequencial','int4','Código sequencial da tabela','0', 'Código',19,'f','f','f',1,'text','Código'),
      array(22255,'rh199_pontoeletronicoarquivodataregistro','int4','Vínculo com o registro da marcação do ponto','0', 'Registro',19,'f','f','f',1,'text','Registro'),
      array(22256,'rh199_pontoeletronicojustificativa','int4','Vínculo com a justificativa para marcação','0', 'Justificativa',19,'f','f','f',1,'text','Justificativa'),
      array(22257,'rh199_tipo','char(1)','Tipo de justificativa P para parcial e T para total.','', 'Tipo de Justificativa',1,'f','t','f',0,'text','Tipo de Justificativa')
    ));

    $this->tabela_db_sysarqcamp->insert(array('codarq','codcam','seqarq','codsequencia'), array(
      array(4016,22254,1,0),
      array(4016,22255,2,0),
      array(4016,22256,3,0),
      array(4016,22257,4,0)
    ));

    $this->tabela_db_syssequencia->insert(array('codsequencia','nomesequencia','incrseq','minvalueseq','maxvalueseq','startseq','cacheseq'), array(
      array(1000637, 'pontoeletronicoregistrojustificativa_rh199_sequencial_seq', 1, 1, 9223372036854775807, 1, 1)
    ));
    $this->execute("update db_sysarqcamp set codsequencia = 1000637 where codarq = 4016 and codcam = 22254");

    $this->tabela_db_sysprikey->insert(array('codarq','codcam','sequen','camiden'), array(
      array(4016,22254,1,22254)
    ));

    // $this->tabela_db_sysforkey->insert(array('codarq', 'codcam', 'sequen', 'referen', 'tipoobjrel'), array(
    // ));
    // $this->tabela_db_sysindices->insert(array('codind', 'nomeind', 'codarq', 'campounico'), array(
    // ));
    // $this->tabela_db_syscadind->insert(array('codind', 'codcam', 'sequen'), array(
    // ));
  }

  public function upPreTabela_assentamentojustificativaperiodo()
  {
    $this->tabela_db_sysarquivo->insert(array('codarq','nomearq','descricao','sigla','dataincl','rotulo','tipotabela','naolibclass','naolibfunc','naolibprog','naolibform'), array(
      array(4029, 'assentamentojustificativaperiodo', 'Tabela que determina se um assentamento de justificativa é de período específico ou integral', 'rh206', '2017-02-13', 'Período do assentamento de justificativa', 0, 'f', 'f', 't', 't' )
    ));

    $this->tabela_db_sysarqmod->insert(array('codmod','codarq'), array(
      array(29,4029)
    ));

    $this->tabela_db_syscampo->insert(array('codcam','nomecam','conteudo','descricao','valorinicial','rotulo','tamanho','nulo','maiusculo','autocompl','aceitatipo','tipoobj','rotulorel'), array(
      array(22360,'rh206_codigo','int4','Código sequencial da tabela','0', 'Código',19,'f','f','f',1,'text','Código'),
      array(22361,'rh206_periodo','int4','Período do assentamento de justificativa.','0', 'Período',19,'f','f','f',1,'text','Período')
    ));

    $this->tabela_db_sysarqcamp->insert(array('codarq','codcam','seqarq','codsequencia'), array(
      array(4029,22360,1,0),
      array(4029,22361,2,0)
    ));

    $this->tabela_db_sysprikey->insert(array('codarq','codcam','sequen','camiden'), array(
      array(4029,22360,1,22360),
      array(4029,22361,2,22360)
    ));

    $this->tabela_db_sysforkey->insert(array('codarq', 'codcam', 'sequen', 'referen', 'tipoobjrel'), array(
      array(4029,22360,1,528,0)
    ));
  }

  public function upPRETabela_assenta() {
    $this->execute("update db_syscampo set nomecam = 'h16_perc', conteudo = 'float8', descricao = 'Quantidade de Horas', valorinicial = '0', rotulo = 'Qtde de Horas', nulo = 'f', tamanho = 15, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Qtde de Horas' where codcam = 3667");
  }

  public function upDDL()
  {
    $this->upDDLTabelasEfetividade();
    $this->upDDLTabelasPontoEletronico();
  }

  public function upDDLTabelasEfetividade()
  {
    $this->execute("CREATE SEQUENCE recursoshumanos.tipoassedb_depart_rh184_sequencial_seq");
    $this->table('tipoassedb_depart', array('schema'=>'recursoshumanos', 'id'=>false, 'primary_key'=>array('rh184_sequencial'), 'constraint'=>'tipoassedb_depart_sequencial_pk'))
      ->addColumn('rh184_sequencial', 'integer')
      ->addColumn('rh184_db_depart',  'integer')
      ->addColumn('rh184_tipoasse',   'integer')
      ->addForeignKey('rh184_db_depart', 'configuracoes.db_depart',   'coddepto', array('constraint'=>'tipoassedb_depart_db_depart_fk'))
      ->addForeignKey('rh184_tipoasse',  'recursoshumanos.tipoasse',  'h12_codigo', array('constraint'=>'tipoassedb_depart_tipoasse_fk'))
      ->addIndex(array('rh184_db_depart','rh184_tipoasse'),  array('unique'=>false, 'name'=>'tipoassedb_depart_db_depart_tipoasse_in'))
      ->save();
    $this->execute("ALTER TABLE recursoshumanos.tipoassedb_depart ALTER COLUMN rh184_sequencial SET DEFAULT nextval('recursoshumanos.tipoassedb_depart_rh184_sequencial_seq')");

    $this->execute("CREATE SEQUENCE recursoshumanos.db_departrhlocaltrab_rh185_sequencial_seq");
    $this->table('db_departrhlocaltrab', array('schema'=>'recursoshumanos', 'id'=>false, 'primary_key'=>array('rh185_sequencial'), 'constraint'=>'db_departrhlocaltrab_sequencial_pk'))
      ->addColumn('rh185_sequencial',      'integer')
      ->addColumn('rh185_db_depart',       'integer')
      ->addColumn('rh185_rhlocaltrab',     'integer')
      ->addColumn('rh185_instit',          'integer')
      ->addForeignKey('rh185_db_depart',   'configuracoes.db_depart',  'coddepto',                          array('constraint'=>'db_departrhlocaltrab_rh185_db_depart_fk'))
      ->addForeignKey(array('rh185_rhlocaltrab', 'rh185_instit'), 'pessoal.rhlocaltrab', array('rh55_codigo', 'rh55_instit'), array('constraint'=>'db_departrhlocaltrab_rhlocaltrab_fk'))
      ->addIndex(array('rh185_db_depart','rh185_rhlocaltrab','rh185_instit'),  array('unique'=>false, 'name'=>'db_departrhlocaltrab_db_depart_rhlocaltrab_instit_in'))
      ->save();
    $this->execute("ALTER TABLE recursoshumanos.db_departrhlocaltrab ALTER COLUMN rh185_sequencial SET DEFAULT nextval('recursoshumanos.db_departrhlocaltrab_rh185_sequencial_seq')");

    $this->table('configuracoesdatasefetividade', array('schema'=>'recursoshumanos', 'id'=>false, 'primary_key'=>array('rh186_exercicio', 'rh186_competencia', 'rh186_instituicao'), 'constraint'=>'configuracoesdatasefetividade_pk'))
      ->addColumn('rh186_exercicio',                  'integer', array('null'=>true))
      ->addColumn('rh186_competencia',                'string',  array('null'=>true, 'limit'=>2))
      ->addColumn('rh186_datainicioefetividade',      'date',    array('null'=>true))
      ->addColumn('rh186_datafechamentoefetividade',  'date',    array('null'=>true))
      ->addColumn('rh186_dataentregaefetividade',     'date',    array('null'=>true))
      ->addColumn('rh186_processado',                 'boolean', array('null'=>true, 'default'=>false))
      ->addColumn('rh186_instituicao',                'integer')
      ->save();
    $this->execute("ALTER TABLE recursoshumanos.configuracoesdatasefetividade ALTER COLUMN rh186_instituicao SET DEFAULT fc_getsession('DB_instit')::int");

    $this->execute("CREATE SEQUENCE recursoshumanos.tiporegistro_rh187_sequencial_seq");
    $this->table('tiporegistro', array('schema'=>'recursoshumanos', 'id'=>false, 'primary_key'=>'rh187_sequencial', 'constraint'=>'tiporegistro_sequencial_pk'))
      ->addColumn('rh187_sequencial',  'integer')
      ->addColumn('rh187_descricao',   'string',  array('limit'=>50))
      ->save();
    $this->execute("ALTER TABLE recursoshumanos.tiporegistro ALTER COLUMN rh187_sequencial SET DEFAULT nextval('recursoshumanos.tiporegistro_rh187_sequencial_seq')");

    $this->execute("CREATE SEQUENCE recursoshumanos.jornada_rh188_sequencial_seq");
    $this->table('jornada', array('schema'=>'recursoshumanos', 'id'=>false, 'primary_key'=>'rh188_sequencial', 'constraint'=>'jornada_sequencial_pk'))
      ->addColumn('rh188_sequencial',   'integer')
      ->addColumn('rh188_descricao',    'string',    array('limit'=>50))
      ->addColumn('rh188_fixo',         'boolean')
      ->addColumn('rh188_tipo',         'char',      array('null'=>true, 'limit' => 1))
      ->save();
    $this->execute("ALTER TABLE recursoshumanos.jornada ALTER COLUMN rh188_sequencial SET DEFAULT nextval('recursoshumanos.jornada_rh188_sequencial_seq')");

    $this->execute("CREATE SEQUENCE recursoshumanos.jornadahoras_rh189_sequencial_seq");
    $this->table('jornadahoras', array('schema'=>'recursoshumanos', 'id'=>false, 'primary_key'=>'rh189_sequencial', 'constraint'=>'jornadahoras_sequencial_pk'))
      ->addColumn('rh189_sequencial',    'integer')
      ->addColumn('rh189_jornada',       'integer')
      ->addColumn('rh189_tiporegistro',  'integer')
      ->addColumn('rh189_hora',          'string', array('limit'=>5))
      ->addForeignKey('rh189_jornada',      'recursoshumanos.jornada',      'rh188_sequencial',  array('constraint'=>'jornadahoras_rh189_jornada_fk'))
      ->addForeignKey('rh189_tiporegistro', 'recursoshumanos.tiporegistro', 'rh187_sequencial',  array('constraint'=>'jornadahoras_rh189_tiporegistro_fk'))
      ->addIndex(array('rh189_jornada', 'rh189_jornada'),  array('unique'=>false, 'name'=>'jornadahoras_jornada_tiporegistro_in'))
      ->save();
    $this->execute("ALTER TABLE recursoshumanos.jornadahoras ALTER COLUMN rh189_sequencial SET DEFAULT nextval('recursoshumanos.jornadahoras_rh189_sequencial_seq')");

    $this->execute("CREATE SEQUENCE recursoshumanos.gradeshorarios_rh190_sequencial_seq");
    $this->table('gradeshorarios', array('schema'=>'recursoshumanos', 'id'=>false, 'primary_key'=>'rh190_sequencial', 'constraint'=>'gradeshorarios_sequencial_pk'))
      ->addColumn('rh190_sequencial', 'integer')
      ->addColumn('rh190_descricao',  'string',  array('limit'=>50))
      ->addColumn('rh190_database',   'date')
      ->save();
    $this->execute("ALTER TABLE recursoshumanos.gradeshorarios ALTER COLUMN rh190_sequencial SET DEFAULT nextval('recursoshumanos.gradeshorarios_rh190_sequencial_seq')");

    $this->execute("CREATE SEQUENCE recursoshumanos.gradeshorariosjornada_rh191_sequencial_seq");
    $this->table('gradeshorariosjornada', array('schema'=>'recursoshumanos', 'id'=>false, 'primary_key'=>'rh191_sequencial', 'constraint'=>'gradeshorariosjornada_sequencial_pk'))
      ->addColumn('rh191_sequencial',     'integer')
      ->addColumn('rh191_gradehorarios',  'integer')
      ->addColumn('rh191_ordemhorario',   'integer')
      ->addColumn('rh191_jornada',        'integer')
      ->addForeignKey('rh191_gradehorarios',   'recursoshumanos.gradeshorarios',  'rh190_sequencial',  array('constraint'=>'gradeshorariosjornada_rh191_gradehorarios_fk'))
      ->addForeignKey('rh191_jornada',          'recursoshumanos.jornada',         'rh188_sequencial',  array('constraint'=>'gradeshorariosjornada_rh191_jornada_fk'))
      ->addIndex(array('rh191_gradehorarios', 'rh191_ordemhorario', 'rh191_jornada'),  array('unique'=>false, 'name'=>'gradeshorariosjornada_gradeshorarios_ordemhorario_jornada_in'))
      ->save();
    $this->execute("ALTER TABLE recursoshumanos.gradeshorariosjornada ALTER COLUMN rh191_sequencial SET DEFAULT nextval('recursoshumanos.gradeshorariosjornada_rh191_sequencial_seq')");

    $this->execute("CREATE SEQUENCE recursoshumanos.escalaservidor_rh192_sequencial_seq");
    $this->table('escalaservidor', array('schema'=>'recursoshumanos', 'id'=>false, 'primary_key'=>'rh192_sequencial', 'constraint'=>'escalaservidor_sequencial_pk'))
      ->addColumn('rh192_sequencial',     'integer')
      ->addColumn('rh192_gradeshorarios', 'integer')
      ->addColumn('rh192_regist',         'integer')
      ->addColumn('rh192_instit',         'integer')
      ->addColumn('rh192_dataescala',     'date')
      ->addForeignKey('rh192_gradeshorarios',  'recursoshumanos.gradeshorarios',  'rh190_sequencial',   array('constraint'=>'escalaservidor_rh192_gradeshorarios_fk'))
      ->addForeignKey('rh192_instit',          'configuracoes.db_config',         'codigo',             array('constraint'=>'escalaservidor_rh192_instit_fk'))
      ->addIndex(array('rh192_gradeshorarios', 'rh192_regist', 'rh192_instit'),  array('unique'=>false, 'name'=>'escalaservidor_gradeshorarios_regist_instit_in'))
      ->save();
    $this->execute("ALTER TABLE recursoshumanos.escalaservidor ALTER COLUMN rh192_sequencial SET DEFAULT nextval('recursoshumanos.escalaservidor_rh192_sequencial_seq')");

    $this->table('assentamentofuncional', array('schema'=>'recursoshumanos', 'id'=>false, 'primary_key'=>'rh193_assentamento_funcional', 'constraint'=>'assentamentofuncional_assentamento_funcional_pk'))
      ->addColumn('rh193_assentamento_funcional',     'integer')
      ->addColumn('rh193_assentamento_efetividade',   'integer', array('null'=>true))
      ->addForeignKey('rh193_assentamento_funcional',    'recursoshumanos.assenta',   'h16_codigo',   array('constraint'=>'assentamentofuncional_rh193_assentamento_funcional_fk'))
      ->addForeignKey('rh193_assentamento_efetividade',  'recursoshumanos.assenta',   'h16_codigo',   array('constraint'=>'assentamentofuncional_rh193_assentamento_efetividade_fk'))
      ->addIndex('rh193_assentamento_funcional',  array('unique'=>false, 'name'=>'assentamentofuncional_un_in'))
      ->save();
  }

  public function upDDLTabelasPontoEletronico()
  {
    $this->execute("CREATE SEQUENCE recursoshumanos.pontoeletronicojustificativa_rh194_sequencial_seq");
    $this->table('pontoeletronicojustificativa', array('schema'=>'recursoshumanos', 'id'=>false, 'primary_key'=>'rh194_sequencial', 'constraint'=>'pontoeletronicojustificativa_sequencial_pk'))
         ->addColumn('rh194_sequencial',      'integer')
         ->addColumn('rh194_descricao',       'string',     array('limit'=>50))
         ->addColumn('rh194_sigla',           'string',     array('limit'=>3))
         ->addColumn('rh194_instituicao',     'integer')
         ->addForeignKey('rh194_instituicao',    'configuracoes.db_config',     'codigo',   array('constraint'=>'pontoeletronicojustificativa_rh194_instituicao_fk'))
         ->addIndex(array('rh194_sigla', 'rh194_instituicao'), array('unique'=>true, 'name'=>'pontoeletronicojustificativa_un_in'))
         ->save();
    $this->execute("ALTER TABLE recursoshumanos.pontoeletronicojustificativa ALTER COLUMN rh194_sequencial SET DEFAULT nextval('recursoshumanos.pontoeletronicojustificativa_rh194_sequencial_seq')");

    $this->execute("CREATE SEQUENCE recursoshumanos.pontoeletronicoconfiguracoeslotacao_rh195_sequencial_seq");
    $this->table('pontoeletronicoconfiguracoeslotacao', array('schema'=>'recursoshumanos', 'id'=>false, 'primary_key'=>'rh195_sequencial', 'constraint'=>'pontoeletronicoconfiguracoeslotacao_sequencial_pk'))
         ->addColumn('rh195_sequencial',                   'integer')
         ->addColumn('rh195_tolerancia',                   'integer')
         ->addColumn('rh195_hora_extra_50',                'string')
         ->addColumn('rh195_hora_extra_75',                'string',     array('null'=>true))
         ->addColumn('rh195_hora_extra_100',               'string',     array('null'=>true))         
         ->addColumn('rh195_lotacao',                      'integer')
         ->addColumn('rh195_supervisor',                   'integer')
         ->addForeignKey('rh195_lotacao',        'pessoal.rhlota',              'r70_codigo',   array('constraint'=>'pontoeletronicoconfiguracoeslotacao_rh195_lotacao_fk'))
         ->addForeignKey('rh195_supervisor',     'pessoal.rhpessoal',           'rh01_regist',  array('constraint'=>'pontoeletronicoconfiguracoeslotacao_rh195_supervisor_fk'))
         ->save();
    $this->execute("ALTER TABLE recursoshumanos.pontoeletronicoconfiguracoeslotacao ALTER COLUMN rh195_sequencial SET DEFAULT nextval('recursoshumanos.pontoeletronicoconfiguracoeslotacao_rh195_sequencial_seq')");
    
    $this->execute("CREATE SEQUENCE recursoshumanos.pontoeletronicoconfiguracoesgerais_rh200_sequencial_seq");
    $this->table('pontoeletronicoconfiguracoesgerais', array('schema'=>'recursoshumanos', 'id'=>false, 'primary_key'=>'rh200_sequencial', 'constraint'=>'pontoeletronicoconfiguracoesgerais_sequencial_pk'))
         ->addColumn('rh200_sequencial',                   'integer')
         ->addColumn('rh200_instituicao',                  'integer')
         ->addColumn('rh200_tipoasse_extra50diurna',       'integer',    array('null'=>true))
         ->addColumn('rh200_tipoasse_extra75diurna',       'integer',    array('null'=>true))
         ->addColumn('rh200_tipoasse_extra100diurna',      'integer',    array('null'=>true))
         ->addColumn('rh200_tipoasse_extra50noturna',      'integer',    array('null'=>true))
         ->addColumn('rh200_tipoasse_extra75noturna',      'integer',    array('null'=>true))
         ->addColumn('rh200_tipoasse_extra100noturna',     'integer',    array('null'=>true))
         ->addColumn('rh200_tipoasse_adicionalnoturno',    'integer',    array('null'=>true))
         ->addColumn('rh200_tipoasse_falta',               'integer',    array('null'=>true))
         ->addForeignKey('rh200_instituicao',    'configuracoes.db_config',     'codigo',       array('constraint'=>'pontoeletronicoconfiguracoesgerais_rh200_instituicao_fk'))
         ->addForeignKey('rh200_tipoasse_extra50diurna',     'recursoshumanos.tipoasse',     'h12_codigo',       array('constraint'=>'pontoeletronicoconfiguracoesgerais_rh200_tipoasse_extra50diurna_fk'))
         ->addForeignKey('rh200_tipoasse_extra75diurna',     'recursoshumanos.tipoasse',     'h12_codigo',       array('constraint'=>'pontoeletronicoconfiguracoesgerais_rh200_tipoasse_extra75diurna_fk'))
         ->addForeignKey('rh200_tipoasse_extra100diurna',    'recursoshumanos.tipoasse',     'h12_codigo',       array('constraint'=>'pontoeletronicoconfiguracoesgerais_rh200_tipoasse_extra100diurna_fk'))
         ->addForeignKey('rh200_tipoasse_extra50noturna',    'recursoshumanos.tipoasse',     'h12_codigo',       array('constraint'=>'pontoeletronicoconfiguracoesgerais_rh200_tipoasse_extra50noturna_fk'))
         ->addForeignKey('rh200_tipoasse_extra75noturna',    'recursoshumanos.tipoasse',     'h12_codigo',       array('constraint'=>'pontoeletronicoconfiguracoesgerais_rh200_tipoasse_extra75noturna_fk'))
         ->addForeignKey('rh200_tipoasse_extra100noturna',   'recursoshumanos.tipoasse',     'h12_codigo',       array('constraint'=>'pontoeletronicoconfiguracoesgerais_rh200_tipoasse_extra100noturna_fk'))
         ->addForeignKey('rh200_tipoasse_adicionalnoturno',  'recursoshumanos.tipoasse',     'h12_codigo',       array('constraint'=>'pontoeletronicoconfiguracoesgerais_rh200_tipoasse_adicionalnoturno_fk'))
         ->addForeignKey('rh200_tipoasse_falta',             'recursoshumanos.tipoasse',     'h12_codigo',       array('constraint'=>'pontoeletronicoconfiguracoesgerais_rh200_tipoasse_falta_fk'))
         ->addIndex('rh200_instituicao',    array('unique'=>true, 'name'=>'pontoeletronicoconfiguracoesgerais_un_in'))
         ->save();
    $this->execute("ALTER TABLE recursoshumanos.pontoeletronicoconfiguracoesgerais ALTER COLUMN rh200_sequencial SET DEFAULT nextval('recursoshumanos.pontoeletronicoconfiguracoesgerais_rh200_sequencial_seq')");

    $this->execute("CREATE SEQUENCE recursoshumanos.pontoeletronicoarquivo_rh196_sequencial_seq");
    $this->table('pontoeletronicoarquivo', array('schema'=>'recursoshumanos', 'id'=>false, 'primary_key'=>'rh196_sequencial', 'constraint'=>'pontoeletronicoarquivo_sequencial_pk'))
         ->addColumn('rh196_sequencial',               'integer')
         ->addColumn('rh196_efetividade_exercicio',    'integer')
         ->addColumn('rh196_efetividade_competencia',  'string',   array('limit'=>2))
         ->addColumn('rh196_instituicao',              'integer')
         ->addColumn('rh196_ano',                      'integer',  array('null'=>true))
         ->addColumn('rh196_mes',                      'integer',  array('null'=>true))
         ->addForeignKey('rh196_instituicao',                 'configuracoes.db_config',        'codigo',                                        array('constraint'=>'pontoeletronicoarquivo_rh196_instituicao_fk'))
         ->addForeignKey(array('rh196_efetividade_exercicio', 'rh196_efetividade_competencia', 'rh196_instituicao'), 'recursoshumanos.configuracoesdatasefetividade', array('rh186_exercicio', 'rh186_competencia', 'rh186_instituicao'), array('constraint'=>'pontoeletronicoarquivo_rh196_efetividade_fk'))
         ->save();
    $this->execute("ALTER TABLE recursoshumanos.pontoeletronicoarquivo ALTER COLUMN rh196_sequencial SET DEFAULT nextval('recursoshumanos.pontoeletronicoarquivo_rh196_sequencial_seq')");
    $this->execute("ALTER TABLE recursoshumanos.pontoeletronicoarquivo ADD COLUMN rh196_arquivo oid");

    $this->execute("CREATE SEQUENCE recursoshumanos.pontoeletronicoarquivodata_rh197_sequencial_seq");
    $this->table('pontoeletronicoarquivodata', array('schema'=>'recursoshumanos', 'id'=>false, 'primary_key'=>'rh197_sequencial', 'constraint'=>'pontoeletronicoarquivodata_sequencial_pk'))
         ->addColumn('rh197_sequencial',                'integer')
         ->addColumn('rh197_pontoeletronicoarquivo',    'integer')
         ->addColumn('rh197_data',                      'date')
         ->addColumn('rh197_matricula',                 'integer', array('null'=>true))
         ->addColumn('rh197_horas_trabalhadas',         'string',  array('limit'=>5, 'null'=>true))
         ->addColumn('rh197_horas_atraso',              'string',  array('limit'=>5, 'null'=>true))
         ->addColumn('rh197_horas_falta',               'string',  array('limit'=>5, 'null'=>true))
         ->addColumn('rh197_horas_extras_50',           'string',  array('limit'=>5, 'null'=>true))
         ->addColumn('rh197_horas_extras_75',           'string',  array('limit'=>5, 'null'=>true))
         ->addColumn('rh197_horas_extras_100',          'string',  array('limit'=>5, 'null'=>true))
         ->addColumn('rh197_horas_adicinal_noturno',    'string',  array('limit'=>5, 'null'=>true))
         ->addColumn('rh197_pis',                       'string',  array('limit'=>11))
         ->addForeignKey('rh197_pontoeletronicoarquivo',  'recursoshumanos.pontoeletronicoarquivo',     'rh196_sequencial',   array('constraint'=>'pontoeletronicoarquivodata_rh197_pontoeletronicoarquivo_fk'))
         ->addIndex(array('rh197_pontoeletronicoarquivo', 'rh197_data', 'rh197_pis'),    array('unique'=>true, 'name'=>'pontoeletronicoarquivodata_pontoeletronicoarquivo_data_pis_un_in'))
      ->save();
    $this->execute("ALTER TABLE recursoshumanos.pontoeletronicoarquivodata ALTER COLUMN rh197_sequencial SET DEFAULT nextval('recursoshumanos.pontoeletronicoarquivodata_rh197_sequencial_seq')");

    $this->execute("CREATE SEQUENCE recursoshumanos.pontoeletronicoarquivodataregistro_rh198_sequencial_seq");
    $this->table('pontoeletronicoarquivodataregistro', array('schema'=>'recursoshumanos', 'id'=>false, 'primary_key'=>'rh198_sequencial', 'constraint'=>'pontoeletronicoarquivodataregistro_sequencial_pk'))
         ->addColumn('rh198_sequencial',                   'integer')
         ->addColumn('rh198_pontoeletronicoarquivodata',   'integer')
         ->addColumn('rh198_registro',                     'string', array('null' => true))
         ->addColumn('rh198_registro_manual',              'boolean')
         ->addColumn('rh198_ordem',                        'integer')
         ->addColumn('rh198_data',                         'date')
         ->addForeignKey('rh198_pontoeletronicoarquivodata',    'recursoshumanos.pontoeletronicoarquivodata',     'rh197_sequencial',   array('constraint'=>'pontoeletronicoarquivodataregistro_rh198_pontoeletronicoarquivodata_fk'))
         ->save();
    $this->execute("ALTER TABLE recursoshumanos.pontoeletronicoarquivodataregistro ALTER COLUMN rh198_sequencial SET DEFAULT nextval('recursoshumanos.pontoeletronicoarquivodataregistro_rh198_sequencial_seq')");
    $this->execute("ALTER TABLE recursoshumanos.pontoeletronicoarquivodataregistro ALTER COLUMN rh198_registro_manual SET DEFAULT false");
    $this->execute("ALTER TABLE recursoshumanos.pontoeletronicoarquivodataregistro ALTER COLUMN rh198_ordem SET DEFAULT 0");

    $this->execute("CREATE SEQUENCE recursoshumanos.pontoeletronicoregistrojustificativa_rh199_sequencial_seq");
    $this->table('pontoeletronicoregistrojustificativa', array('schema'=>'recursoshumanos', 'id'=>false, 'primary_key'=>'rh199_sequencial', 'constraint'=>'pontoeletronicoregistrojustificativa_sequencial_pk'))
         ->addColumn('rh199_sequencial',                           'integer')
         ->addColumn('rh199_pontoeletronicoarquivodataregistro',   'integer')
         ->addColumn('rh199_pontoeletronicojustificativa',         'integer')
         ->addColumn('rh199_tipo',                                 'char',      array('limit'=>1))
         ->addForeignKey('rh199_pontoeletronicoarquivodataregistro', 'recursoshumanos.pontoeletronicoarquivodataregistro',  'rh198_sequencial',   array('constraint'=>'pontoeletronicoarquivodataregistro_fk'))
         ->addForeignKey('rh199_pontoeletronicojustificativa',       'recursoshumanos.pontoeletronicojustificativa',        'rh194_sequencial',   array('constraint'=>'pontoeletronicojustificativa_fk'))
         ->save();
    $this->execute("ALTER TABLE recursoshumanos.pontoeletronicoregistrojustificativa ALTER COLUMN rh199_sequencial SET DEFAULT nextval('recursoshumanos.pontoeletronicoregistrojustificativa_rh199_sequencial_seq')");
    
    $this->execute("CREATE SEQUENCE recursoshumanos.pontoeletronicojustificativatipoasse_rh205_sequencial_seq");
    $this->table('pontoeletronicojustificativatipoasse', array('schema'=>'recursoshumanos', 'id'=>false, 'primary_key'=>'rh205_sequencial', 'constraint'=>'pontoeletronicojustificativatipoasse_sequencial_pk'))
         ->addColumn('rh205_sequencial',                         'integer')
         ->addColumn('rh205_pontoeletronicojustificativa',       'integer')
         ->addColumn('rh205_tipoasse',                           'integer')
         ->addForeignKey('rh205_pontoeletronicojustificativa', 'recursoshumanos.pontoeletronicojustificativa',  'rh194_sequencial',   array('constraint'=>'pontoeletronicojustificativa_fk'))
         ->addForeignKey('rh205_tipoasse',                     'recursoshumanos.tipoasse',                      'h12_codigo',         array('constraint'=>'tipoasse_fk'))
         ->save();
    $this->execute("ALTER TABLE recursoshumanos.pontoeletronicojustificativatipoasse ALTER COLUMN rh205_sequencial SET DEFAULT nextval('recursoshumanos.pontoeletronicojustificativatipoasse_rh205_sequencial_seq')");
    
    $this->table('assentamentojustificativaperiodo', array('schema'=>'recursoshumanos', 'id'=>false, 'primary_key'=>array('rh206_codigo', 'rh206_periodo'), 'constraint'=>'assentamentojustificativa_codigo_pk'))
         ->addColumn('rh206_codigo',                 'integer')
         ->addColumn('rh206_periodo',                'integer')
         ->addForeignKey('rh206_codigo',       'recursoshumanos.assenta',       'h16_codigo',         array('constraint'=>'assentamento_fk'))
         ->save();
  }

  public function downDDL()
  {
    $sTableJustificativaTipoasse = $this->table('pontoeletronicojustificativatipoasse', array('schema'=>'recursoshumanos'));

    if($sTableJustificativaTipoasse->exists()) {

      $sTableJustificativaTipoasse->drop();
      $this->execute("DROP SEQUENCE IF EXISTS recursoshumanos.pontoeletronicojustificativatipoasse_rh205_sequencial_seq");
    }

    $this->table('pontoeletronicoregistrojustificativa', array('schema'=>'recursoshumanos'))->drop();
    $this->execute("DROP SEQUENCE IF EXISTS recursoshumanos.pontoeletronicoregistrojustificativa_rh199_sequencial_seq");

    $this->table('pontoeletronicoarquivodataregistro', array('schema'=>'recursoshumanos'))->drop();
    $this->execute("DROP SEQUENCE IF EXISTS recursoshumanos.pontoeletronicoarquivodataregistro_rh198_sequencial_seq");

    $this->table('pontoeletronicoarquivodata', array('schema'=>'recursoshumanos'))->drop();
    $this->execute("DROP SEQUENCE IF EXISTS recursoshumanos.pontoeletronicoarquivodata_rh197_sequencial_seq");

    $this->table('pontoeletronicoarquivo', array('schema'=>'recursoshumanos'))->drop();
    $this->execute("DROP SEQUENCE IF EXISTS recursoshumanos.pontoeletronicoarquivo_rh196_sequencial_seq");

    $sTableConfiguracoesLotacao = $this->table('pontoeletronicoconfiguracoeslotacao', array('schema'=>'recursoshumanos'));

    if($sTableConfiguracoesLotacao->exists()) {

      $sTableConfiguracoesLotacao->drop();
      $this->execute("DROP SEQUENCE IF EXISTS recursoshumanos.pontoeletronicoconfiguracoeslotacao_rh195_sequencial_seq");
    }

    $sTableConfiguracoesGerais = $this->table('pontoeletronicoconfiguracoesgerais', array('schema'=>'recursoshumanos'));

    if($sTableConfiguracoesGerais->exists()) {

      $sTableConfiguracoesGerais->drop();
      $this->execute("DROP SEQUENCE IF EXISTS recursoshumanos.pontoeletronicoconfiguracoesgerais_rh200_sequencial_seq");
    }

    $this->table('pontoeletronicojustificativa', array('schema'=>'recursoshumanos'))->drop();
    $this->execute("DROP SEQUENCE IF EXISTS recursoshumanos.pontoeletronicojustificativa_rh194_sequencial_seq");

    $sTableAssentamentoJustificativaPeriodo = $this->table('assentamentojustificativaperiodo', array('schema'=>'recursoshumanos'));

    if($sTableAssentamentoJustificativaPeriodo->exists()) {
      $this->table('assentamentojustificativaperiodo', array('schema'=>'recursoshumanos'))->drop();
    }

    $this->table('assentamentofuncional', array('schema'=>'recursoshumanos'))->drop();

    $this->table('escalaservidor', array('schema'=>'recursoshumanos'))->drop();
    $this->execute("DROP SEQUENCE recursoshumanos.escalaservidor_rh192_sequencial_seq");

    $this->table('gradeshorariosjornada', array('schema'=>'recursoshumanos'))->drop();
    $this->execute("DROP SEQUENCE recursoshumanos.gradeshorariosjornada_rh191_sequencial_seq");

    $this->table('gradeshorarios', array('schema'=>'recursoshumanos'))->drop();
    $this->execute("DROP SEQUENCE recursoshumanos.gradeshorarios_rh190_sequencial_seq");

    $this->table('jornadahoras', array('schema'=>'recursoshumanos'))->drop();
    $this->execute("DROP SEQUENCE recursoshumanos.jornadahoras_rh189_sequencial_seq");

    $this->table('jornada', array('schema'=>'recursoshumanos'))->drop();
    $this->execute("DROP SEQUENCE recursoshumanos.jornada_rh188_sequencial_seq");

    $this->table('tiporegistro', array('schema'=>'recursoshumanos'))->drop();
    $this->execute("DROP SEQUENCE recursoshumanos.tiporegistro_rh187_sequencial_seq");

    $this->table('configuracoesdatasefetividade', array('schema'=>'recursoshumanos'))->drop();

    $this->table('db_departrhlocaltrab', array('schema'=>'recursoshumanos'))->drop();
    $this->execute("DROP SEQUENCE recursoshumanos.db_departrhlocaltrab_rh185_sequencial_seq");

    $this->table('tipoassedb_depart', array('schema'=>'recursoshumanos'))->drop();
    $this->execute("DROP SEQUENCE recursoshumanos.tipoassedb_depart_rh184_sequencial_seq");
  }

  public function adicionarMenus()
  {
    //Estrutura de menus novos
    $this->table('db_itensmenu', array('schema'=>'configuracoes'))->insert(array('id_item', 'descricao', 'help', 'funcao', 'itemativo', 'manutencao', 'desctec', 'libcliente'), array(
      array( 10360 ,'Mês Atual' ,'Mês Atual' ,'rec2_novagradeefetividade001.php' ,'1' ,'1' ,'Grade de Efetividade (Novo)' ,'true' ),
      array( 10361 ,'Próximo Mês' ,'Próximo Mês' ,'rec2_consultagradeefetividade001.php' ,'1' ,'1' ,'Consulta Outras Grades de Efetividade' ,'true' ),
      array( 10365 ,'Efetividade' ,'Efetividade' ,'' ,'1' ,'1' ,'Efetividade' ,'true' ),
      array( 10366 ,'Jornadas' ,'Jornadas' ,'rec4_cadastrojornadas001.php' ,'1' ,'1' ,'Cadastro de Jornadas.' ,'true' ),
      array( 10367 ,'Escala de Trabalho' ,'Escala de Trabalho' ,'rec4_cadastrogradeshorarios001.php' ,'1' ,'1' ,'Cadastro de Escala de Trabalho.' ,'true' ),
      array( 10368 ,'Efetividade' ,'Efetividade' ,'' ,'1' ,'1' ,'Procedimentos da Efetividade.' ,'true' ),
      array( 10369 ,'Parâmetros' ,'Parâmetros' ,'' ,'1' ,'1' ,'Parâmetros da efetividade.' ,'true' ),
      array( 10370 ,'Vínculo Assentamentos/Afastamentos por Departamento' ,'Vínculo Assentamentos/Afastamentos por Departamento' ,'rec4_tipoassedb_depart001.php' ,'1' ,'1' ,'Vínculo Assentamentos/Afastamentos por Departamento' ,'true' ),
      array( 10371 ,'Vínculo Locais de Trabalho por Departamento' ,'Vínculo Locais de Trabalho por Departamento' ,'rec4_db_departrhlocaltrab001.php' ,'1' ,'1' ,'Vínculo Locais de Trabalho por Departamento' ,'true' ),
      array( 10372 ,'Períodos de Efetividade' ,'Períodos de Efetividade' ,'rec4_configuracoesefetividade001.php' ,'1' ,'1' ,'Períodos de Efetividade' ,'true' ),
      array( 10373 ,'Encerrar Período' ,'Encerrar Período' ,'rec4_encerramentoefetividade001.php' ,'1' ,'1' ,'Encerrar Período' ,'true' ),
      array( 10374 ,'Reabrir Período' ,'Reabrir Período' ,'rec4_reabertuarefetividade001.php' ,'1' ,'1' ,'Reabrir Período' ,'true' ),
      array( 10375 ,'Manutenção da Escala de Funcionários' ,'Manutenção da Escala de Funcionários' ,'rec4_escalaservidores001.php' ,'1' ,'1' ,'Manutenção da Escala de Funcionários' ,'true' ),
      array( 10376 ,'Assentamentos de Efetividade' ,'Assentamentos de Efetividade' ,'' ,'1' ,'1' ,'Assentamentos de Efetividade' ,'true' ),
      array( 10377 ,'Inclusão' ,'Inclusão' ,'rec1_assenta_efetividade001.php' ,'1' ,'1' ,'Inclusão de assentamentos de efetividade.' ,'true' ),
      array( 10378 ,'Alteração' ,'Alteração' ,'rec1_assenta_efetividade002.php' ,'1' ,'1' ,'Alteração de assentamentos de efetividade.' ,'true' ),
      array( 10379 ,'Exclusão' ,'Exclusão' ,'rec1_assenta_efetividade003.php' ,'1' ,'1' ,'Exclusão de assentamentos de efetividade.' ,'true' ),
      array( 10380 ,'Importar Assentamentos para Vida Funcional' ,'Importar Assentamentos para Vida Funcional' ,'rec4_importarassentamentos001.php' ,'1' ,'1' ,'Importar Assentamentos para Vida Funcional' ,'true' ),
      array( 10384 ,'Ponto Eletrônico' ,'Ponto Eletrônico' ,'' ,'1' ,'1' ,'Menus referentes a procedimentos do ponto eletrônico.' ,'true' ),
      array( 10385 ,'Importar Arquivo' ,'Importar Arquivo' ,'rec4_pontoeletronicoimportararquivo.php' ,'1' ,'1' ,'Importação do arquivo do ponto.' ,'true' ),
      array( 10388 ,'Ponto Eletrônico' ,'Ponto Eletrônico' ,'' ,'1' ,'1' ,'Relatórios do ponto eletrônico' ,'true' ),
      array( 10389 ,'Espelho Ponto' ,'Espelho Ponto' ,'rec2_espelhoponto001.php' ,'1' ,'1' ,'Espelho ponto dos servidores.' ,'true' ),
      array( 10391 ,'Configurações' ,'Configurações' ,'rec4_pontoeletronicoconfiguracoes001.php' ,'1' ,'1' ,'Configurações de assentamentos/rubricas do ponto eletrônico.' ,'true' ),
      array( 10392 ,'Manutenção' ,'Manutenção' ,'rec4_pontoeletronicomanutencao001.php' ,'1' ,'1' ,'Rotina para manutenção do ponto eletrônico dos servidores.' ,'true' ),
      array( 10399 ,'Efetividade' ,'Efetividade' ,'' ,'1' ,'1' ,'Menu com os relatórios referentes a efetividade.' ,'true' ),
    ))->saveData();

    //vínculos dos menus
    $this->table('db_menu', array('schema'=>'configuracoes'))->insert(array('id_item', 'id_item_filho', 'menusequencia', 'modulo'), array(
      array( 29    ,10365 ,272 ,2323 ),
      array( 10365 ,10366 ,1   ,2323 ),
      array( 10365 ,10367 ,2   ,2323 ),
      array( 30    ,10388 ,464 ,2323 ),
      array( 30    ,10399 ,465 ,2323 ),
      array( 32    ,10368 ,476 ,2323 ),
      array( 32    ,10384 ,478 ,2323 ),
      array( 10368 ,10369 ,1   ,2323 ),
      array( 10369 ,10370 ,1   ,2323 ),
      array( 10369 ,10371 ,2   ,2323 ),
      array( 10369 ,10372 ,3   ,2323 ),
      array( 10368 ,10373 ,2   ,2323 ),
      array( 10368 ,10374 ,3   ,2323 ),
      array( 10368 ,10375 ,4   ,2323 ),
      array( 5574  ,10376 ,7   ,2323 ),
      array( 5574  ,10380 ,8   ,2323 ),
      array( 10376 ,10377 ,1   ,2323 ),
      array( 10376 ,10378 ,2   ,2323 ),
      array( 10376 ,10379 ,3   ,2323 ),
      array( 10388 ,10389 ,1   ,2323 ),
      array( 10384 ,10391 ,1   ,2323 ),
      array( 10384 ,10385 ,2   ,2323 ),
      array( 10384 ,10392 ,3   ,2323 ),
      array( 10399 ,10360 ,1   ,2323 ),
      array( 10399 ,10361 ,2   ,2323 )
    ))->saveData();

    $pluginInstaladoNaBase = $this->fetchRow("SELECT * FROM db_plugin WHERE db145_nome = 'CadastroEfetividade'");
    $pluginInstalado       = array();

    if(!empty($pluginInstaladoNaBase)) {

      array_walk($pluginInstaladoNaBase, function($item, $key) use (&$pluginInstalado) {
        if(!is_int($key)) {
          $pluginInstalado[$key] = $item;
        }
      });
    }

    if(!empty($pluginInstalado)) {

      $pluginInstalado       = (object)$pluginInstalado;
      $menusInstaladosPlugin = $this->fetchAll("SELECT * FROM db_itensmenu INNER JOIN db_pluginitensmenu ON db146_db_itensmenu = id_item WHERE db146_db_plugin = {$pluginInstalado->db145_sequencial} ");
      $menusPlugin           = array();
      $menusPluginExcluir    = array();

      if(!empty($menusInstaladosPlugin)) {

        foreach ($menusInstaladosPlugin as $menuInstaladoPlugin) {

          $menuPlugin         = array();

          array_walk($menuInstaladoPlugin, function($item, $key) use (&$menuPlugin) {
            if(!is_int($key)) {
              $menuPlugin[$key] = $item;
            }
          });

          $menuPlugin           = (object)$menuPlugin;
          $menusPlugin[]        = $menuPlugin;
          $menusPluginExcluir[] = $menuPlugin->id_item;
        }

        $vinculosMenus       = $this->execute("DELETE FROM db_menu WHERE id_item_filho IN (". implode(',', $menusPluginExcluir) .")");
        $vinculosMenusPLugin = $this->execute("DELETE FROM db_pluginitensmenu WHERE db146_db_itensmenu IN (". implode(',', $menusPluginExcluir) .")");
        $menusExcluidos      = $this->execute("DELETE FROM db_itensmenu WHERE id_item IN (". implode(',', $menusPluginExcluir) .")");

        if($vinculosMenus != $menusExcluidos) {
          throw new Exception("Não foi possível excluir todos os menus e seus vínculos do plugin: {$pluginInstalado->db145_nome}");
        }
      }
    }
  }

  public function fazerBackupDadosTabelasEsquemaPlugin()
  {
    //Rodar pl que cria as tabelas do plugin
    //Em clientes que não tenham plugin instalado criar tabelas de backup vazias para facilicar a migração dos dados

    //função para fazer backup das tabelas do plugin
    $this->execute("
            create or replace function fc_backup_tabelas_plugin_efetividade() returns boolean as $$

            DECLARE

              aTabelas         text[];
              iTotalTabelas    integer;
              iIssue           integer;
              lRaise           boolean default false;
              recordTabelas    record;
              sSql             text;
              lExisteTabela    integer default null;
              sNomeTabelaCriar text default '';

            begin

              lRaise   := case when fc_getsession('DB_debugon') is null then false else true end;
              iIssue   := 7315;
              aTabelas := array['configuracoesdatasefetividade',
                                'db_departrhlocaltrab',
                                'escalaservidor',
                                'gradeshorarios',
                                'gradeshorariosjornada',
                                'jornada',
                                'jornadahoras',
                                'rhvisavalecadgradeefetividade',
                                'rhvisavalegradeefetividade',
                                'tipoassedb_depart',
                                'tiporegistro',
                                'vtffuncgradeefetividade',
                                'assentamentofuncional'];
              iTotalTabelas := array_upper(aTabelas, 1);

              perform fc_debug('Início do Debug do backup de atualização das tabelas do plugin de Efetividade', lRaise, true, false);
              perform fc_debug('');

              for iPosicao in 1..iTotalTabelas loop

                perform fc_debug('-- Verificando existência da tabela ' || aTabelas[iPosicao]);

                select tablename
                  into recordTabelas
                  from pg_tables
                 where schemaname = 'plugins'
                   and tablename  = aTabelas[iPosicao]::VARCHAR;

                if not found then
                  perform fc_debug('   -- Tabela ' || aTabelas[iPosicao] || ' não encontrada. --');
                end if;

                perform fc_debug('-- Criando tabelas de backup --');
                
                if recordTabelas.tablename is not null then

                  perform fc_debug('-- Cliente possui plugin instalado --');

                  if recordTabelas.tablename = 'configuracoesdatasefetividade' then

                    sSql := 'create table w_bkp_'|| recordTabelas.tablename ||'_'||iIssue||' as
                                                 select plugins.configuracoesdatasefetividade.*, db_config.codigo 
                                                   from plugins.'|| recordTabelas.tablename || ' LEFT JOIN db_config ON exercicio <> codigo';
                  
                  elsif recordTabelas.tablename = 'jornada' then

                    sSql := 'create table w_bkp_'|| recordTabelas.tablename ||'_'||iIssue||' as
                                                 select *, case when fixo 
                                                                then case when descricao ~* ''.*folga.*''
                                                                          then ''F''
                                                                          else ''D''
                                                                      end
                                                                else ''T''
                                                            end::char
                                                   from plugins.'|| recordTabelas.tablename;

                  else 

                    sSql := 'create table w_bkp_'|| recordTabelas.tablename ||'_'||iIssue||' as
                                                 select *
                                                   from plugins.'|| recordTabelas.tablename;
                  end if;

                else

                  perform fc_debug('-- Cliente NÂO possui plugin instalado --');
                  sSql := 'create table w_bkp_' || aTabelas[iPosicao]::VARCHAR ||'_'||iIssue||' (';

                  case

                    when aTabelas[iPosicao]::VARCHAR = 'configuracoesdatasefetividade' then 

                      sSql := sSql ||'  exercicio                  integer not null,';
                      sSql := sSql ||'  competencia                varchar(2),';
                      sSql := sSql ||'  datainicioefetividade      date,';
                      sSql := sSql ||'  datafechamentoefetividade  date,';
                      sSql := sSql ||'  dataentregaefetividade     date,';
                      sSql := sSql ||'  processado                 bool,';
                      sSql := sSql ||'  instituicao                integer';
                
                    when aTabelas[iPosicao]::VARCHAR = 'rhvisavalegradeefetividade' then
                      
                      sSql := sSql ||'  instit         int4,';
                      sSql := sSql ||'  rubricprovento varchar(4)';

                    when aTabelas[iPosicao]::VARCHAR = 'vtffuncgradeefetividade' then
                      
                      sSql := sSql ||'  regist           int4       not null,';
                      sSql := sSql ||'  instit           int4       not null,';
                      sSql := sSql ||'  codigo           char(4)    not null,';
                      sSql := sSql ||'  difere           boolean    not null,';
                      sSql := sSql ||'  efetividade      bool       not null,';
                      sSql := sSql ||'  formapagamento   char(1)    not null,';
                      sSql := sSql ||'  passagensdiarias int4';

                    when aTabelas[iPosicao]::VARCHAR = 'assentamentofuncional' then
                      
                      sSql := sSql ||'  assentamento_funcional   integer not null,';
                      sSql := sSql ||'  assentamento_efetividade integer';

                    else

                      sSql := sSql ||'  sequencial   integer not null,';

                      case
                        when aTabelas[iPosicao]::VARCHAR = 'db_departrhlocaltrab' then
                          
                          sSql := sSql ||'  db_depart   integer not null,';
                          sSql := sSql ||'  rhlocaltrab integer not null,';
                          sSql := sSql ||'  instit      integer not null';

                        when aTabelas[iPosicao]::VARCHAR = 'escalaservidor' then
                          
                          sSql := sSql ||'  gradeshorarios integer not null,';
                          sSql := sSql ||'  regist         integer not null,';
                          sSql := sSql ||'  instit         integer not null,';
                          sSql := sSql ||'  dataescala     date    not null';

                        when aTabelas[iPosicao]::VARCHAR = 'gradeshorarios' then
                          
                          sSql := sSql ||'  descricao  varchar(50), ';
                          sSql := sSql ||'  database   date';

                        when aTabelas[iPosicao]::VARCHAR = 'gradeshorariosjornada' then
                          
                          sSql := sSql ||'  gradeshorarios integer,';
                          sSql := sSql ||'  ordemhorario   integer,';
                          sSql := sSql ||'  jornada        integer ';

                        when aTabelas[iPosicao]::VARCHAR = 'jornada' then

                          sSql := sSql ||'  descricao    varchar(50),';
                          sSql := sSql ||'  fixo         bool,';
                          sSql := sSql ||'  tipo         char';

                        when aTabelas[iPosicao]::VARCHAR = 'jornadahoras' then

                          sSql := sSql ||'  jornada      integer not null,';
                          sSql := sSql ||'  tiporegistro integer not null,';
                          sSql := sSql ||'  hora         varchar(5)';

                        when aTabelas[iPosicao]::VARCHAR = 'rhvisavalecadgradeefetividade' then
                          
                          sSql := sSql ||'  rhvisavalecad  int4,';
                          sSql := sSql ||'  formapagamento char(1)';
                          
                        when aTabelas[iPosicao]::VARCHAR = 'tipoassedb_depart' then
                          
                          sSql := sSql ||'  db_depart  integer not null,';
                          sSql := sSql ||'  tipoasse   integer not null';

                        when aTabelas[iPosicao]::VARCHAR = 'tiporegistro' then

                          sSql := sSql ||'  descricao   varchar(50)';

                        else sSql := sSql;
                      end case;
                  end case;

                  sSql := sSql ||');';

                end if;
                
                perform fc_debug('-- Query que cria o banco de dados: w_bkp_'||sSql);
                perform fc_debug('-- Criando tabela de backup: w_bkp_' || recordTabelas.tablename ||'_'||iIssue);

                sNomeTabelaCriar = 'w_bkp_'|| aTabelas[iPosicao]::VARCHAR ||'_'|| iIssue;

                SELECT CASE WHEN COUNT(*) > 0
                            THEN 1
                            ELSE 0 END AS existe_tabela 
                  INTO lExisteTabela
                  FROM pg_tables 
                 WHERE tablename = sNomeTabelaCriar;

                if lExisteTabela = 0 then
                  EXECUTE sSql;
                end if;

                perform fc_debug('');

              end loop;

              perform fc_debug('---- BACKUP CONCLUÍDO ----', true, false, true);

              return true;

            end

            $$ LANGUAGE plpgsql
        ");

    $this->execute("SELECT * FROM fc_backup_tabelas_plugin_efetividade()");
  }

  public function migrarDados()
  {
    $this->fazerBackupDadosTabelasEsquemaPlugin();

    $this->execute("ALTER TABLE tipoassedb_depart DISABLE TRIGGER ALL");
    $this->execute("INSERT INTO tipoassedb_depart               SELECT * FROM w_bkp_tipoassedb_depart_7315");
    $this->execute("ALTER TABLE tipoassedb_depart ENABLE TRIGGER ALL");

    $this->execute("ALTER TABLE db_departrhlocaltrab DISABLE TRIGGER ALL");
    $this->execute("INSERT INTO db_departrhlocaltrab            SELECT * FROM w_bkp_db_departrhlocaltrab_7315");
    $this->execute("ALTER TABLE db_departrhlocaltrab ENABLE TRIGGER ALL");

    $this->execute("ALTER TABLE configuracoesdatasefetividade DISABLE TRIGGER ALL");
    $this->execute("INSERT INTO configuracoesdatasefetividade SELECT * FROM w_bkp_configuracoesdatasefetividade_7315");
    $this->execute("ALTER TABLE configuracoesdatasefetividade ENABLE TRIGGER ALL");

    $this->execute("ALTER TABLE tiporegistro DISABLE TRIGGER ALL");
    $this->execute("INSERT INTO tiporegistro                    SELECT * FROM w_bkp_tiporegistro_7315");
    $this->execute("ALTER TABLE tiporegistro ENABLE TRIGGER ALL");

    $this->execute("ALTER TABLE jornada DISABLE TRIGGER ALL");
    $this->execute("INSERT INTO jornada                         SELECT * FROM w_bkp_jornada_7315");
    $this->execute("ALTER TABLE jornada ENABLE TRIGGER ALL");

    $this->execute("ALTER TABLE jornadahoras DISABLE TRIGGER ALL");
    $this->execute("INSERT INTO jornadahoras                    SELECT * FROM w_bkp_jornadahoras_7315");
    $this->execute("ALTER TABLE jornadahoras ENABLE TRIGGER ALL");

    $this->execute("ALTER TABLE gradeshorarios DISABLE TRIGGER ALL");
    $this->execute("INSERT INTO gradeshorarios                  SELECT * FROM w_bkp_gradeshorarios_7315");
    $this->execute("ALTER TABLE gradeshorarios ENABLE TRIGGER ALL");

    $this->execute("ALTER TABLE gradeshorariosjornada DISABLE TRIGGER ALL");
    $this->execute("INSERT INTO gradeshorariosjornada           SELECT * FROM w_bkp_gradeshorariosjornada_7315");
    $this->execute("ALTER TABLE gradeshorariosjornada ENABLE TRIGGER ALL");

    $this->execute("ALTER TABLE escalaservidor DISABLE TRIGGER ALL");
    $this->execute("INSERT INTO escalaservidor                  SELECT * FROM w_bkp_escalaservidor_7315");
    $this->execute("ALTER TABLE escalaservidor ENABLE TRIGGER ALL");

    $this->execute("ALTER TABLE assentamentofuncional DISABLE TRIGGER ALL");
    $this->execute("INSERT INTO assentamentofuncional           SELECT * FROM w_bkp_assentamentofuncional_7315");
    $this->execute("ALTER TABLE assentamentofuncional ENABLE TRIGGER ALL");


    /**
     * Caso as tabelas tiporegistro e jornada estejam vazias incluir dados básicos para poder utilizar as rotinas
     */
    $tiposRegistro = $this->fetchAll("SELECT * FROM tiporegistro");

    if(empty($tiposRegistro)) {

      $idTiporegistro = $this->fetchRow("SELECT nextval('recursoshumanos.tiporegistro_rh187_sequencial_seq')");
      $this->table('tiporegistro', array('schema'=>'recursoshumanos'))->insert(array('rh187_sequencial', 'rh187_descricao'), array(array($idTiporegistro[0], 'ENTRADA 1')))->saveData();

      $idTiporegistro = $this->fetchRow("SELECT nextval('recursoshumanos.tiporegistro_rh187_sequencial_seq')");
      $this->table('tiporegistro', array('schema'=>'recursoshumanos'))->insert(array('rh187_sequencial', 'rh187_descricao'), array(array($idTiporegistro[0], 'SAIDA 1')))->saveData();

      $idTiporegistro = $this->fetchRow("SELECT nextval('recursoshumanos.tiporegistro_rh187_sequencial_seq')");
      $this->table('tiporegistro', array('schema'=>'recursoshumanos'))->insert(array('rh187_sequencial', 'rh187_descricao'), array(array($idTiporegistro[0], 'ENTRADA 2')))->saveData();

      $idTiporegistro = $this->fetchRow("SELECT nextval('recursoshumanos.tiporegistro_rh187_sequencial_seq')");
      $this->table('tiporegistro', array('schema'=>'recursoshumanos'))->insert(array('rh187_sequencial', 'rh187_descricao'), array(array($idTiporegistro[0], 'SAIDA 2')))->saveData();
    }

    $jornadas = $this->fetchAll("SELECT * FROM jornada");

    if(empty($jornadas)) {

      $idJornada = $this->fetchRow("SELECT nextval('recursoshumanos.jornada_rh188_sequencial_seq')");
      $this->table('jornada', array('schema'=>'recursoshumanos'))->insert(array('rh188_sequencial', 'rh188_descricao', 'rh188_fixo', 'rh188_tipo'), array(array($idJornada[0], 'DSR', 't', 'D')))->saveData();

      $idJornada = $this->fetchRow("SELECT nextval('recursoshumanos.jornada_rh188_sequencial_seq')");
      $this->table('jornada', array('schema'=>'recursoshumanos'))->insert(array('rh188_sequencial', 'rh188_descricao', 'rh188_fixo', 'rh188_tipo'), array(array($idJornada[0], 'FOLGA', 't', 'F')))->saveData();
    }

    $this->execute("SELECT setval('escalaservidor_rh192_sequencial_seq',        (SELECT max(rh192_sequencial) FROM escalaservidor))");
    $this->execute("SELECT setval('gradeshorariosjornada_rh191_sequencial_seq', (SELECT max(rh191_sequencial) FROM gradeshorariosjornada))");
    $this->execute("SELECT setval('gradeshorarios_rh190_sequencial_seq',        (SELECT max(rh190_sequencial) FROM gradeshorarios))");
    $this->execute("SELECT setval('jornadahoras_rh189_sequencial_seq',          (SELECT max(rh189_sequencial) FROM jornadahoras))");
    $this->execute("SELECT setval('jornada_rh188_sequencial_seq',               (SELECT max(rh188_sequencial) FROM jornada))");
    $this->execute("SELECT setval('tiporegistro_rh187_sequencial_seq',          (SELECT max(rh187_sequencial) FROM tiporegistro))");
    $this->execute("SELECT setval('db_departrhlocaltrab_rh185_sequencial_seq',  (SELECT max(rh185_sequencial) FROM db_departrhlocaltrab))");
    $this->execute("SELECT setval('tipoassedb_depart_rh184_sequencial_seq',     (SELECT max(rh184_sequencial) FROM tipoassedb_depart))");
  }

  public function removerPlugin()
  {
    //Excluir da tabela plugins o plugin da efetividade
    $this->execute("DELETE FROM db_pluginmodulos WHERE db152_db_plugin IN (SELECT db145_sequencial FROM db_plugin WHERE db145_nome = 'CadastroEfetividade')");
    $this->execute("DELETE FROM db_plugin WHERE db145_nome = 'CadastroEfetividade'");

    //Excluir chave estrangeira da tabela historicoefetividade do plugin EscolaEfetividade
    $this->execute("ALTER TABLE IF EXISTS plugins.historicoefetividade DROP CONSTRAINT IF EXISTS historicoefetividade_exercicio_fkey");

    //Excluindo tabelas do plugin
    $this->execute("DROP TABLE IF EXISTS plugins.tipoassedb_depart");
    $this->execute("DROP TABLE IF EXISTS plugins.db_departrhlocaltrab");
    $this->execute("DROP TABLE IF EXISTS plugins.configuracoesdatasefetividade");
    $this->execute("DROP TABLE IF EXISTS plugins.escalaservidor");
    $this->execute("DROP TABLE IF EXISTS plugins.jornadahoras");
    $this->execute("DROP TABLE IF EXISTS plugins.gradeshorariosjornada");
    $this->execute("DROP TABLE IF EXISTS plugins.gradeshorarios");
    $this->execute("DROP TABLE IF EXISTS plugins.jornada");
    $this->execute("DROP TABLE IF EXISTS plugins.tiporegistro");
    $this->execute("DROP TABLE IF EXISTS plugins.assentamentofuncional");
  }

  public function manterDadosParaPlugin()
  {
    $this->execute("DELETE FROM w_bkp_tipoassedb_depart_7315");
    $this->execute("DELETE FROM w_bkp_db_departrhlocaltrab_7315");
    $this->execute("DELETE FROM w_bkp_configuracoesdatasefetividade_7315");
    $this->execute("DELETE FROM w_bkp_tiporegistro_7315");
    $this->execute("DELETE FROM w_bkp_jornada_7315");
    $this->execute("DELETE FROM w_bkp_jornadahoras_7315");
    $this->execute("DELETE FROM w_bkp_gradeshorarios_7315");
    $this->execute("DELETE FROM w_bkp_gradeshorariosjornada_7315");
    $this->execute("DELETE FROM w_bkp_escalaservidor_7315");
    $this->execute("DELETE FROM w_bkp_assentamentofuncional_7315");

    $this->execute("INSERT INTO w_bkp_tipoassedb_depart_7315               SELECT * FROM tipoassedb_depart");
    $this->execute("INSERT INTO w_bkp_db_departrhlocaltrab_7315            SELECT * FROM db_departrhlocaltrab");
    $this->execute("INSERT INTO w_bkp_configuracoesdatasefetividade_7315   SELECT * FROM configuracoesdatasefetividade");
    $this->execute("INSERT INTO w_bkp_tiporegistro_7315                    SELECT * FROM tiporegistro");
    $this->execute("INSERT INTO w_bkp_jornada_7315                         SELECT * FROM jornada");
    $this->execute("INSERT INTO w_bkp_jornadahoras_7315                    SELECT * FROM jornadahoras");
    $this->execute("INSERT INTO w_bkp_gradeshorarios_7315                  SELECT * FROM gradeshorarios");
    $this->execute("INSERT INTO w_bkp_gradeshorariosjornada_7315           SELECT * FROM gradeshorariosjornada");
    $this->execute("INSERT INTO w_bkp_escalaservidor_7315                  SELECT * FROM escalaservidor");
    $this->execute("INSERT INTO w_bkp_assentamentofuncional_7315           SELECT * FROM assentamentofuncional");
  }

  public function adicionarLayoutPontoEletronico()
  {

    $this->execute("insert into db_layouttxtgrupotipo values(3, 'ARQUIVOS RH')");
    $this->execute("insert into db_layouttxtgrupo values(8, 3, 'ARQUIVOS PONTO ELETRÔNICO')");

    $this->execute("insert into db_layouttxt( db50_codigo ,db50_layouttxtgrupo ,db50_descr ,db50_quantlinhas ,db50_obs ) values ( 278 ,8 ,'PONTO ELETRÔNICO - AFD' ,0 ,'' )");

    $sSqlLayoutLinha  = "insert into db_layoutlinha( db51_codigo ,db51_layouttxt ,db51_descr ,db51_tipolinha ,db51_tamlinha ,db51_linhasantes ,db51_linhasdepois ,db51_obs ,db51_separador ,db51_compacta )";
    $sSqlLayoutLinha .= "                   values ( 893 ,278 ,'CABEÇALHO' ,1 ,0 ,0 ,0 ,'' ,'' ,'0' ),";
    $sSqlLayoutLinha .= "                          ( 894 ,278 ,'IDENTIFICAÇÃO DA EMPRESA NO REP' ,3 ,0 ,0 ,0 ,'1.2. Registro de inclusão ou alteração da identificação da empresa no REP' ,'' ,'0' ),";
    $sSqlLayoutLinha .= "                          ( 895 ,278 ,'MARCAÇÃO DE PONTO' ,3 ,0 ,0 ,0 ,'1.3. Registro de marcação de ponto' ,'' ,'0' ),";
    $sSqlLayoutLinha .= "                          ( 896 ,278 ,'AJUSTE RELÓGIO REP' ,3 ,0 ,0 ,0 ,'1.4. Registro de ajuste do relógio de tempo real do REP' ,'' ,'0' ),";
    $sSqlLayoutLinha .= "                          ( 897 ,278 ,'EMPREGADO DA MT DO REP' ,3 ,0 ,0 ,0 ,'1.5. Registro de inclusão ou alteração ou exclusão de empregado da MT do REP' ,'' ,'0' ),";
    $sSqlLayoutLinha .= "                          ( 898 ,278 ,'TRAILER' ,5 ,0 ,0 ,0 ,'1.6. Trailer' ,'' ,'0' );";

    $this->execute($sSqlLayoutLinha);

    $sSqlLayoutCampo  = "insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos )";
    $sSqlLayoutCampo .= "                    values ( 15368 ,893 ,'REGISTRO_INICIAL' ,'REGISTRO_INICIAL' ,1 ,1 ,'' ,9 ,'f' ,'t' ,'d' ,'' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15369 ,893 ,'TIPO_REGISTRO' ,'TIPO_REGISTRO' ,1 ,10 ,'1' ,1 ,'t' ,'t' ,'d' ,'' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15370 ,893 ,'IDENTIFICADOR_EMPREGADOR' ,'IDENTIFICADOR_EMPREGADOR' ,1 ,11 ,'' ,1 ,'f' ,'t' ,'d' ,'Tipo de identificador do empregador, 1 para CNPJ ou 2 para CPF.' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15371 ,893 ,'CNPJ_CPF_EMPREGADOR' ,'CNPJ_CPF_EMPREGADOR' ,1 ,12 ,'' ,14 ,'f' ,'t' ,'d' ,'CNPJ ou CPF do empregador' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15372 ,893 ,'CEI_EMPREGADOR' ,'CEI_EMPREGADOR' ,1 ,26 ,'' ,12 ,'f' ,'t' ,'d' ,'CEI do empregador, quando existir.' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15373 ,893 ,'RAZAO_SOCIAL_EMPREGADOR' ,'RAZAO_SOCIAL_EMPREGADOR' ,1 ,38 ,'' ,150 ,'f' ,'t' ,'d' ,'Razão social ou nome do empregador.' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15374 ,893 ,'NUMERO_FABRICACAO_REP' ,'NUMERO_FABRICACAO_REP' ,1 ,188 ,'' ,17 ,'f' ,'t' ,'d' ,'Número de fabricação do REP' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15375 ,893 ,'DATA_INICIAL' ,'DATA_INICIAL' ,1 ,205 ,'' ,8 ,'f' ,'t' ,'d' ,'Data inicial dos registros no arquivo, no formato ddmmaaaa' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15376 ,893 ,'DATA_FINAL' ,'DATA_FINAL' ,1 ,213 ,'' ,8 ,'f' ,'t' ,'d' ,'Data final dos registr formato ddmmaaaa' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15377 ,893 ,'DATA_GERACAO' ,'DATA_GERACAO' ,1 ,221 ,'' ,8 ,'f' ,'t' ,'d' ,'Data de geração do arquivo, no formato ddmmaaaa.' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15378 ,893 ,'HORARIO_GERACAO' ,'HORARIO_GERACAO' ,1 ,229 ,'' ,4 ,'f' ,'t' ,'d' ,'Horário da geração do arquivo, no formato hhmm' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15379 ,894 ,'NSR' ,'NSR' ,1 ,1 ,'' ,9 ,'f' ,'t' ,'d' ,'NSR.' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15380 ,894 ,'TIPO_REGISTRO' ,'TIPO_REGISTRO' ,1 ,10 ,'2' ,1 ,'t' ,'t' ,'d' ,'Tipo do registro, 2.' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15381 ,894 ,'DATA_GRAVACAO' ,'DATA_GRAVACAO' ,1 ,11 ,'' ,8 ,'f' ,'t' ,'d' ,'Data da gravação, no formata ddmmaaaa' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15382 ,894 ,'HORARIO_GRAVACAO' ,'HORARIO_GRAVACAO' ,1 ,19 ,'' ,4 ,'f' ,'t' ,'d' ,'Horário da gravação, no formato hhmm' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15383 ,894 ,'IDENTIFICADOR_EMPREGADOR' ,'IDENTIFICADOR_EMPREGADOR' ,1 ,23 ,'' ,1 ,'f' ,'t' ,'d' ,'Tipo de identificador do empregador, 1 para CNPJ ou 2 para CPF.' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15384 ,894 ,'CNPJ_CPF_EMPREGADOR' ,'CNPJ_CPF_EMPREGADOR' ,1 ,24 ,'' ,14 ,'f' ,'t' ,'d' ,'CNPJ ou CPF do empregador.' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15385 ,894 ,'CEI_EMPREGADOR' ,'CEI_EMPREGADOR' ,1 ,38 ,'' ,12 ,'f' ,'t' ,'d' ,'CEI do empregador, quando existir.' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15386 ,894 ,'RAZAO_SOCIAL_EMPREGADOR' ,'RAZAO_SOCIAL_EMPREGADOR' ,1 ,50 ,'' ,150 ,'f' ,'t' ,'d' ,'Razão social ou nome do empregador.' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15387 ,894 ,'LOCAL_PRESTACAO_SERVICOS' ,'LOCAL_PRESTACAO_SERVICOS' ,1 ,200 ,'' ,100 ,'f' ,'t' ,'d' ,'Local de prestação de serviços.' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15388 ,895 ,'NSR' ,'NSR' ,1 ,1 ,'' ,9 ,'f' ,'t' ,'d' ,'NSR' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15389 ,895 ,'TIPO_REGISTRO' ,'TIPO_REGISTRO' ,1 ,10 ,'3' ,1 ,'t' ,'t' ,'d' ,'Tipo do registro, 3' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15390 ,895 ,'DATA_MARCACAO' ,'DATA_MARCACAO' ,1 ,11 ,'' ,8 ,'f' ,'t' ,'d' ,'Data da marcação de ponto, no formato ddmmaaaa' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15391 ,895 ,'HORARIO_MARCACAO' ,'HORARIO_MARCACAO' ,1 ,19 ,'' ,4 ,'f' ,'t' ,'d' ,'Horário da marcação de ponto, no Formato hhmm.' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15392 ,895 ,'PIS_EMPREGADO' ,'PIS_EMPREGADO' ,1 ,23 ,'' ,12 ,'f' ,'t' ,'d' ,'Número do PIS do empregado.' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15393 ,896 ,'NSR' ,'NSR' ,1 ,1 ,'' ,9 ,'f' ,'t' ,'d' ,'NSR' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15394 ,896 ,'TIPO_REGISTRO' ,'TIPO_REGISTRO' ,1 ,10 ,'4' ,1 ,'t' ,'t' ,'d' ,'Tipo do registro, 4' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15395 ,896 ,'DATA_ANTERIOR_AJUSTE' ,'DATA_ANTERIOR_AJUSTE' ,1 ,11 ,'' ,8 ,'f' ,'t' ,'d' ,'Data antes do ajuste, no formato ddmmaaaa.' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15396 ,896 ,'HORARIO_ANTERIOR_AJUSTE' ,'HORARIO_ANTERIOR_AJUSTE' ,1 ,19 ,'' ,4 ,'f' ,'t' ,'d' ,'Horário antes do ajuste, no formato hhmm.' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15397 ,896 ,'DATA_AJUSTADA' ,'DATA_AJUSTADA' ,1 ,23 ,'' ,8 ,'f' ,'t' ,'d' ,'Data ajustada, no formato ddmmaaaa' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15398 ,896 ,'HORARIO_AJUSTADO' ,'HORARIO_AJUSTADO' ,1 ,31 ,'' ,4 ,'f' ,'t' ,'d' ,'Horário ajustado, no formato hhmm' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15399 ,897 ,'NSR' ,'NSR' ,1 ,1 ,'' ,9 ,'f' ,'t' ,'d' ,'NSR' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15400 ,897 ,'TIPO_REGISTRO' ,'TIPO_REGISTRO' ,1 ,10 ,'5' ,1 ,'t' ,'t' ,'d' ,'Tipo do registro, 5.' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15401 ,897 ,'DATA_GRAVACAO' ,'DATA_GRAVACAO' ,1 ,11 ,'' ,8 ,'f' ,'t' ,'d' ,'Data da gravação do registro, no formato ddmmaaaa.' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15402 ,897 ,'HORARIO_GRAVACAO' ,'HORARIO_GRAVACAO' ,1 ,19 ,'' ,4 ,'f' ,'t' ,'d' ,'Horário da gravação do registro, no formato hhmm.' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15403 ,897 ,'TIPO_OPERACAO' ,'TIPO_OPERACAO' ,1 ,23 ,'' ,1 ,'f' ,'t' ,'d' ,'Tipo de operação, I para inclusão, A para alteração e E para exclusão.' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15404 ,897 ,'PIS_EMPREGADO' ,'PIS_EMPREGADO' ,1 ,24 ,'' ,12 ,'f' ,'t' ,'d' ,'Número do PIS do empregado.' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15405 ,897 ,'NOME_EMPREGADO' ,'NOME_EMPREGADO' ,1 ,36 ,'' ,52 ,'f' ,'t' ,'d' ,'Nome do empregado' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15406 ,898 ,'REGISTRO_INICIAL' ,'REGISTRO_INICIAL' ,1 ,1 ,'' ,9 ,'f' ,'t' ,'d' ,'999999999' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15407 ,898 ,'REGISTROS_TIPO_2' ,'REGISTROS_TIPO_2' ,1 ,10 ,'' ,9 ,'f' ,'t' ,'d' ,'Quantidade de registros tipo 2 no arquivo.' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15408 ,898 ,'REGISTROS_TIPO_3' ,'REGISTROS_TIPO_3' ,1 ,19 ,'' ,9 ,'f' ,'t' ,'d' ,'Quantidade de registros tipo 3 no arquivo.' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15409 ,898 ,'REGISTROS_TIPO_4' ,'REGISTROS_TIPO_4' ,1 ,28 ,'' ,9 ,'f' ,'t' ,'d' ,'Quantidade de registros tipo 4 no arquivo' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15410 ,898 ,'REGISTROS_TIPO_5' ,'REGISTROS_TIPO_5' ,1 ,37 ,'' ,9 ,'f' ,'t' ,'d' ,'Quantidade de registros tipo 5 no arquivo' ,0 ),";
    $sSqlLayoutCampo .= "                           ( 15411 ,898 ,'TIPO_REGISTRO' ,'TIPO_REGISTRO' ,1 ,46 ,'9' ,1 ,'t' ,'t' ,'d' ,'Tipo do registro, 9.' ,0 );";

    $this->execute($sSqlLayoutCampo);
  }

  public function adicionarNaturezaAssentamento()
  {

    $naturezasTipoAssentamentos = $this->fetchAll("SELECT * FROM pessoal.naturezatipoassentamento 
                                                           WHERE rh159_descricao ilike 'ponto%'
                                                              OR rh159_descricao ilike 'justificativa'
                                                              OR rh159_descricao ilike 'dia_extra'");

    if(empty($naturezasTipoAssentamentos)) {
    
      $this->table('naturezatipoassentamento', array('schema'=>'pessoal'))->insert(array('rh159_sequencial', 'rh159_descricao'), array(
        array(4, 'Ponto Eletrônico'),
        array(5, 'Justificativa'),
        array(6, 'Dia Extra')
      ))->saveData();
    }

    $this->execute("SELECT setval('naturezatipoassentamento_rh159_sequencial_seq', (SELECT max (rh159_sequencial) FROM naturezatipoassentamento))");
  }

  public function adicionarFeriadosPadrao()
  {

    $this->execute("ALTER TABLE rhcadcalend ALTER COLUMN rh53_calend SET DEFAULT nextval('rhcadcalend_rh53_calend_seq')");
    $this->execute("SELECT setval('rhcadcalend_rh53_calend_seq', (SELECT max(rh53_calend) FROM rhcadcalend))");

    $instituicoes = $this->fetchAll("SELECT * FROM db_config;");
    
    foreach ($instituicoes as $instituicao) {
      
      $codigoInstituicao = $instituicao['codigo'];
      $calendario        = $this->fetchRow("SELECT rh53_calend FROM rhcadcalend WHERE rh53_instit = {$codigoInstituicao}
                                                                                  AND rh53_descr LIKE 'FERIADOS PONTO ELETRÔNICO';");
      
      if(empty($calendario)) {

        $this->table('rhcadcalend', array('schema'=>'pessoal'))->insert(array('rh53_instit', 'rh53_descr'), array(
          array($codigoInstituicao, 'FERIADOS PONTO ELETRÔNICO')
        ))->saveData();
      
        $calendario = $this->fetchRow("SELECT rh53_calend FROM rhcadcalend WHERE rh53_instit = {$codigoInstituicao} AND rh53_descr LIKE 'FERIADOS PONTO ELETRÔNICO';");
      
        if(!empty($calendario)) {

          $datasFeriados = $this->fetchRow("SELECT * FROM calendf WHERE r62_calend = ". $calendario['rh53_calend']);

          if(empty($datasFeriados)) {

            $this->table('calendf', array('schema'=>'pessoal'))->insert(array('r62_calend', 'r62_data'), array(
              array($calendario['rh53_calend'], '2017-01-01'), // CONFRATERNIZAÇÃO UNIVERSAL
              array($calendario['rh53_calend'], '2017-04-14'), // PAIXÃO DE CRISTO
              array($calendario['rh53_calend'], '2017-04-21'), // TIRADENTES
              array($calendario['rh53_calend'], '2017-05-01'), // DIA MUNDIAL DO TRABALHO
              array($calendario['rh53_calend'], '2017-09-07'), // INDEPENDÊNCIA DO BRASIL
              array($calendario['rh53_calend'], '2017-10-12'), // NOSSA SENHORA APARECIDA
              array($calendario['rh53_calend'], '2017-11-02'), // FINADOS
              array($calendario['rh53_calend'], '2017-11-15'), // PROCLAMAÇÃO DA REPÚBLICA
              array($calendario['rh53_calend'], '2017-12-25')  // NATAL
            ))->saveData();
          }
        }
      }
    }

    $this->execute("ALTER TABLE rhcadcalend ALTER COLUMN rh53_calend DROP DEFAULT;");
  }

  public function incluirFormulaPadraoHorasPonto()
  {
    $formulasHorasPonto = $this->fetchAll("SELECT * FROM db_formulas WHERE db148_nome ILIKE 'PONTO_HORA'");

    if(empty($formulasHorasPonto)) {
      $this->table('db_formulas', array('schema'=>'configuracoes'))->insert(array('db148_nome','db148_descricao','db148_formula'), array(
        array('PONTO_HORA','RETORNA AS HORAS EXTRAS OU FALTAS OU DE ADICIONAL NOTURNO','select h16_perc from assenta where h16_codigo = [CODIGO_ASSENTAMENTO]')
      ))->saveData();
    }
  }
}
