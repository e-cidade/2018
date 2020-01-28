<?php

use Classes\PostgresMigration;

class M9418MultasTransito extends PostgresMigration
{

  public function up() {
    $this->criarMenu();
    $this->addDicionarioDados();
    $this->criarTabela();
    $this->criarLayout();
  }

  public function down() {
    $this->removeritensMenu();
    $this->removerDicionarioDados();
    $this->removerTabela();
    $this->removerLayout();
  }

  private function criarmenu() {
     // Cria o item de MENU
    $aColumns   =  array('id_item' ,'descricao' ,'help' ,'funcao' ,'itemativo' ,'manutencao' ,'desctec' ,'libcliente');
    $aValues    =  array(
      array(10448 ,'Cadastro de Infrações de Trânsito' ,'Cadastro de Infrações de Trânsito' ,'' ,'1' ,'1' ,'Menu referente ao cadastro de infrações de trânsito.' ,'true' ),
      array(10449 ,'Inclusão' ,'Incluir infrações de trânsito' ,'inf1_infracaotransito.php?iOpcao=1' ,'1' ,'1' ,'Inclusão de Infrações de trânsito' ,'true' ),
      array(10450 ,'Alteração','Alterar infrações de trânsito' ,'inf1_infracaotransito.php?iOpcao=2' ,'1' ,'1' ,'Alteração de Infrações de trânsito' ,'true'),
      array(10451 ,'Exclusão' ,'Excluir infrações de trânsito' ,'inf1_infracaotransito.php?iOpcao=3' ,'1' ,'1' ,'Excluir infrações de trânsito' ,'true'),
      array(10452 ,'Infrações de Trânsito ' ,'Infrações de Trânsito ' ,'' ,'1' ,'1' ,'Importação do arquivo de multas e as configurações das receitas para cada nivel de multa. ' ,'true'),
      array(10453 ,'Configuração de Receita das Infrações' ,'Configuração de Receita das Infrações' ,'inf4_receitasinfracaotransito004.php' ,'1' ,'1' ,'Configurações de receita das infrações, conforme o nível de gravidade. ' ,'true'),
      array(10454 ,'Importação do Arquivo de Multas' ,'Importação do Arquivo de Multas' ,'inf4_importacaoinfracaotransito005.php' ,'1' ,'1' ,'Importação do arquivo de multas' ,'true'),
      array(10455 ,'Relatórios de Infrações de Trânsito' ,'Relatórios de Infrações de Trânsito' ,'' ,'1' ,'1' ,'Relatórios referente a importação dos arquivos de multas de trânsito' ,'false' ),
      array(10456 ,'Arrecadação de Multas de Trânsito' ,'Arrecadação de Multas de Trânsito' ,'inf2_arrecmultastransito002.php' ,'1' ,'1' ,'Relatório que demonstra as arrecadações das multas de trânsito.' ,'false'),
      array(10457 ,'Pagamentos em Duplicidade' ,'Pagamentos em Duplicidade' ,'inf2_pagamentoduplicidade002.php' ,'1' ,'1' ,'Relatório que demonstra os pagamentos feitos em duplicidades.' ,'false'),

    );

    $table      = $this->table('db_itensmenu', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    // Víncula item de menu
    $aColumns   =    array('id_item', 'id_item_filho', 'menusequencia', 'modulo');
    $aValues    =    array(
      array(29 ,10448 ,279 ,39),
      array(10448 ,10449 ,1 ,39),
      array(10448 ,10450 ,2 ,39),
      array(10448 ,10451 ,3 ,39),
      array(32 ,10452 ,489 ,39),
      array(10452 ,10453 ,1 ,39),
      array(10452 ,10454 ,2 ,39),
      array(30 ,10455 ,468 ,39),
      array(10455 ,10456 ,1 ,39),
      array(10455 ,10457 ,2 ,39),
    );

    $table      =  $this->table('db_menu', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();
  }

  private function addDicionarioDados() {

    $this->execute(
<<<SQL
      insert into db_sysarquivo values (1010224, 'infracaotransito', 'Tabela de infração de Transito. ', 'i05', '2017-09-08', 'infracaotransito', 0, 'f', 'f', 'f', 'f' );
      insert into db_sysarqmod values (5,1010224);
      insert into db_syscampo values(1009422,'i05_sequencial','int4','Cadastro sequencial','0', 'Cadastro sequencial',10,'f','f','f',1,'text','Cadastro sequencial');
      insert into db_syscampo values(1009423,'i05_gravidade','int4','Código da Gravidade','0', 'Código da Gravidade',10,'f','f','f',1,'text','Código da Gravidade');
      insert into db_syscampo values(1009424,'i05_descricao','varchar(100)','Descrição da infração de Trânsito.','', 'Descrição',100,'f','t','f',0,'text','Descrição');
      insert into db_syscampo values(1009425,'i05_codigo','varchar(10)','Código da Infração de Trânsito.','', 'Código da Infração',10,'f','t','f',0,'text','Código da Infração');
      delete from db_sysarqcamp where codarq = 1010224;
      insert into db_sysarqcamp values(1010224,1009422,1,0);
      insert into db_sysarqcamp values(1010224,1009425,2,0);
      insert into db_sysarqcamp values(1010224,1009423,3,0);
      insert into db_sysarqcamp values(1010224,1009424,4,0);
      delete from db_sysprikey where codarq = 1010224;
      delete from db_sysarqcamp where codarq = 3964;
      insert into db_sysarqcamp values(3964,22024,1,1000598);
      insert into db_sysarqcamp values(3964,22025,2,0);
      insert into db_sysarqcamp values(3964,22023,3,0);
      insert into db_sysarqcamp values(3964,22022,4,0);
      insert into db_sysarqcamp values(3964,22045,5,0);
      delete from db_sysarqcamp where codarq = 1010224;
      insert into db_sysarqcamp values(1010224,1009422,1,0);
      insert into db_sysarqcamp values(1010224,1009425,2,0);
      insert into db_sysarqcamp values(1010224,1009423,3,0);
      insert into db_sysarqcamp values(1010224,1009424,4,0);
      delete from db_sysprikey where codarq = 1010224;
      delete from db_sysprikey where codarq = 1010224;
      insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010224,1009422,1,1009422);
      insert into db_syssequencia values(1000688, 'infracaotransito_i05_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
      update db_sysarqcamp set codsequencia = 1000688 where codarq = 1010224 and codcam = 1009422;
      delete from db_sysarqcamp where codarq = 1010224;
      insert into db_sysarqcamp values(1010224,1009422,1,1000688);
      insert into db_sysarqcamp values(1010224,1009425,2,0);
      insert into db_sysarqcamp values(1010224,1009423,3,0);
      insert into db_sysarqcamp values(1010224,1009424,4,0);
      delete from db_sysarqcamp where codarq = 3964;
      insert into db_sysarqcamp values(3964,22024,1,1000598);
      insert into db_sysarqcamp values(3964,22025,2,0);
      insert into db_sysarqcamp values(3964,22023,3,0);
      insert into db_sysarqcamp values(3964,22022,4,0);
      insert into db_sysarqcamp values(3964,22045,5,0);
      insert into db_sysarquivo values (1010225, 'receitainfracao', 'Tabela de vinculo entre receitas e infrações.', 'i06', '2017-09-08', 'receitainfracao', 0, 'f', 'f', 'f', 'f' );
      insert into db_sysarqmod values (5,1010225);
      insert into db_syscampo values(1009426,'i06_sequencial','int4','Cadastro sequencial','0', 'Cadastro sequencial',10,'f','f','f',1,'text','Cadastro sequencial');
      insert into db_syscampo values(1009427,'i06_receitaprincipal','int4','Código da Receita Principal','0', 'Código da Receita Principal',10,'f','f','f',1,'text','Código da Receita Principal');
      insert into db_syscampo values(1009428,'i06_gravidade','int4','Código da Gravidade.','0', 'Código da Gravidade',10,'f','f','f',1,'text','Código da Gravidade');
      insert into db_syscampo values(1009429,'i06_receitaduplicidade','int4','Código da Receita de Duplicidade','0', 'Código da Receita de Duplicidade',10,'f','f','f',1,'text','Código da Receita de Duplicidade');
      insert into db_syscampo values(1009430,'i06_anousu','int4','Exercicio','0', 'Exercicio',10,'f','f','f',1,'text','Exercicio');
      delete from db_sysarqcamp where codarq = 1010225;
      insert into db_sysarqcamp values(1010225,1009426,1,0);
      insert into db_sysarqcamp values(1010225,1009427,2,0);
      insert into db_sysarqcamp values(1010225,1009429,3,0);
      insert into db_sysarqcamp values(1010225,1009430,4,0);
      insert into db_sysarqcamp values(1010225,1009428,5,0);
      delete from db_sysprikey where codarq = 1010225;
      insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010225,1009426,1,1009426);
      insert into db_syssequencia values(1000689, 'receitainfracao_i06_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
      update db_sysarqcamp set codsequencia = 1000689 where codarq = 1010225 and codcam = 1009426;
      delete from db_sysarqcamp where codarq = 1010225;
      insert into db_sysarqcamp values(1010225,1009426,1,1000689);
      insert into db_sysarqcamp values(1010225,1009427,2,0);
      insert into db_sysarqcamp values(1010225,1009429,3,0);
      insert into db_sysarqcamp values(1010225,1009430,4,0);
      insert into db_sysarqcamp values(1010225,1009428,5,0);


      insert into db_sysarquivo values (1010226, 'arquivoinfracao', 'Tabela de arquivos de infração de trânsito', 'i07', '2017-09-11', 'Arquivo Infração', 0, 'f', 'f', 'f', 'f' );
      insert into db_sysarqmod values (5,1010226);
      insert into db_syscampo values(1009431,'i07_sequencial','int4','Código sequencial.','0', 'Código Sequencial',10,'f','f','f',1,'text','Código Sequencial');
      insert into db_syscampo values(1009432,'i07_dtimportacao','date','Data da importação','null', 'Data da importação',10,'f','f','f',1,'text','Data da importação');
      insert into db_syscampo values(1009433,'i07_dtpagamento','date','Data de Pagamento','null', 'Data de Pagamento',10,'f','f','f',1,'text','Data de Pagamento');
      insert into db_syscampo values(1009434,'i07_repasse','date','Data de Repasse','null', 'Data de Repasse',10,'f','f','f',1,'text','Data de Repasse');
      insert into db_syscampo values(1009435,'i07_vlbruto','float8','Valor Bruto','0', 'Valor Bruto',15,'f','f','f',4,'text','Valor Bruto');
      insert into db_syscampo values(1009436,'i07_vlprestacaocontas','float8','Valor da Prestação de Contas','0', 'Valor da Prestação de Contas',15,'f','f','f',4,'text','Valor da Prestação de Contas');
      insert into db_syscampo values(1009437,'i07_vldetran','float8','Valor do Detran','0', 'Valor do Detran',15,'f','f','f',4,'text','Valor do Detran');
      insert into db_syscampo values(1009438,'i07_vlfunset','float8','Valor do Funset','0', 'Valor do Funset',15,'f','f','f',4,'text','Valor do Funset');
      insert into db_syscampo values(1009439,'i07_vloutros','float8','Valor de Outros','0', 'Valor de Outros',15,'f','f','f',4,'text','Valor de Outros');
      insert into db_syscampo values(1009440,'i07_vlprefeitura','float8','Valor da Prefeitura','0', 'Valor da Prefeitura',15,'f','f','f',4,'text','Valor da Prefeitura');
      insert into db_syscampo values(1009441,'i07_vlduplicado','float8','Valor de Pagamento Duplicado','0', 'Valor de Pagamento Duplicado',15,'f','f','f',4,'text','Valor de Pagamento Duplicado');
      insert into db_syscampo values(1009442,'i07_registro','int4','Quantidade de Registros','0', 'Quantidade de Registros',10,'f','f','f',1,'text','Quantidade de Registros');
      update db_syscampo set nomecam = 'i07_dtrepasse', conteudo = 'date', descricao = 'Data de Repasse', valorinicial = 'null', rotulo = 'Data de Repasse', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Data de Repasse' where codcam = 1009434;
      delete from db_syscampodep where codcam = 1009434;
      delete from db_syscampodef where codcam = 1009434;
      update db_syscampo set nomecam = 'i06_nivel', conteudo = 'int4', descricao = 'Código do Nivel', valorinicial = '0', rotulo = 'Código do Nível', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Código do Nível' where codcam = 1009428;
      delete from db_syscampodep where codcam = 1009428;
      delete from db_syscampodef where codcam = 1009428;

      insert into db_syscampo values(1009444,'i06_conta','int4','Código da Conta Bancária','0', 'Código da Conta',10,'f','f','f',1,'text','Código da Conta');
      delete from db_sysarqcamp where codarq = 1010225;
      insert into db_sysarqcamp values(1010225,1009426,1,1000689);
      insert into db_sysarqcamp values(1010225,1009427,2,0);
      insert into db_sysarqcamp values(1010225,1009429,3,0);
      insert into db_sysarqcamp values(1010225,1009430,4,0);
      insert into db_sysarqcamp values(1010225,1009428,5,0);
      insert into db_sysarqcamp values(1010225,1009444,6,0);
      update db_syscampo set nomecam = 'i05_gravidade', conteudo = 'int4', descricao = 'Código do Nível', valorinicial = '0', rotulo = 'Código do Nível', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Código do Nível' where codcam = 1009423;
      delete from db_syscampodep where codcam = 1009423;
      delete from db_syscampodef where codcam = 1009423;
      delete from db_sysarqcamp where codarq = 1010224;
      insert into db_sysarqcamp values(1010224,1009422,1,1000688);
      insert into db_sysarqcamp values(1010224,1009425,2,0);
      insert into db_sysarqcamp values(1010224,1009423,3,0);
      insert into db_sysarqcamp values(1010224,1009424,4,0);
      update db_syscampo set nomecam = 'i05_nivel', conteudo = 'int4', descricao = 'Código do Nível', valorinicial = '0', rotulo = 'Código do Nível', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Código do Nível' where codcam = 1009423;
      delete from db_syscampodep where codcam = 1009423;
      delete from db_syscampodef where codcam = 1009423;

      insert into db_syscampo values(1009445,'i07_convenio','char(6)','Código do Convênio','', 'Código do Convênio',6,'f','t','f',0,'text','Código do Convênio');
      insert into db_syscampo values(1009446,'i07_remessa','char(5)','Código de Remessa','', 'Código de Remessa',5,'f','t','f',0,'text','Código de Remessa');
      insert into db_syscampo values(1009447,'i07_dtmovimento','date','Data do Movimento','null', 'Data do Movimento',10,'f','f','f',1,'text','Data do Movimento');
      delete from db_sysarqcamp where codarq = 1010226;
      insert into db_sysarqcamp values(1010226,1009431,1,1000690);
      insert into db_sysarqcamp values(1010226,1009432,2,0);
      insert into db_sysarqcamp values(1010226,1009433,3,0);
      insert into db_sysarqcamp values(1010226,1009434,4,0);
      insert into db_sysarqcamp values(1010226,1009442,5,0);
      insert into db_sysarqcamp values(1010226,1009435,6,0);
      insert into db_sysarqcamp values(1010226,1009440,7,0);
      insert into db_sysarqcamp values(1010226,1009441,8,0);
      insert into db_sysarqcamp values(1010226,1009438,9,0);
      insert into db_sysarqcamp values(1010226,1009437,10,0);
      insert into db_sysarqcamp values(1010226,1009436,11,0);
      insert into db_sysarqcamp values(1010226,1009439,12,0);
      insert into db_sysarqcamp values(1010226,1009447,13,0);
      insert into db_sysarqcamp values(1010226,1009446,14,0);
      insert into db_sysarqcamp values(1010226,1009445,15,0);     
      update db_syscampo set nomecam = 'i07_dtrepasse', conteudo = 'date', descricao = 'Data de Repasse', valorinicial = 'null', rotulo = 'Data de Repasse', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Data de Repasse' where codcam = 1009434;
      update db_syscampo set nomecam = 'i07_dtpagamento', conteudo = 'date', descricao = 'Data de Pagamento', valorinicial = 'null', rotulo = 'Data de Pagamento', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Data de Pagamento' where codcam = 1009433;
      update db_syscampo set nomecam = 'i07_vlbruto', conteudo = 'float8', descricao = 'Valor Bruto', valorinicial = '0', rotulo = 'Valor Bruto', nulo = 't', tamanho = 15, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Valor Bruto' where codcam = 1009435;
      update db_syscampo set nomecam = 'i07_vlprestacaocontas', conteudo = 'float8', descricao = 'Valor da Prestação de Contas', valorinicial = '0', rotulo = 'Valor da Prestação de Contas', nulo = 't', tamanho = 15, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Valor da Prestação de Contas' where codcam = 1009436;

SQL
      );
  }

  private function criarTabela() {
    $this->execute(
<<<SQL
      --DROP TABLE:
      DROP TABLE IF EXISTS caixa.arquivoinfracao CASCADE;
      DROP TABLE IF EXISTS caixa.infracaotransito CASCADE;
      DROP TABLE IF EXISTS caixa.receitainfracao CASCADE;
      --Criando drop sequences
      DROP SEQUENCE IF EXISTS caixa.arquivoinfracao_i07_sequencial_seq;
      DROP SEQUENCE IF EXISTS caixa.infracaotransito_i05_sequencial_seq;
      DROP SEQUENCE IF EXISTS caixa.receitainfracao_i06_sequencial_seq;


      -- Criando  sequences
      CREATE SEQUENCE caixa.arquivoinfracao_i07_sequencial_seq
      INCREMENT 1
      MINVALUE 1
      MAXVALUE 9223372036854775807
      START 1
      CACHE 1;


      CREATE SEQUENCE caixa.infracaotransito_i05_sequencial_seq
      INCREMENT 1
      MINVALUE 1
      MAXVALUE 9223372036854775807
      START 1
      CACHE 1;


      CREATE SEQUENCE caixa.receitainfracao_i06_sequencial_seq
      INCREMENT 1
      MINVALUE 1
      MAXVALUE 9223372036854775807
      START 1
      CACHE 1;

      -- TABELAS E ESTRUTURA

      -- Módulo: caixa
      CREATE TABLE caixa.arquivoinfracao(
      i07_sequencial    int4 NOT NULL default 0,
      i07_dtimportacao    date NOT NULL default null,
      i07_dtpagamento   date  default null,
      i07_dtrepasse   date  default null,
      i07_registro    int4 NOT NULL default 0,
      i07_vlbruto   float8 NOT NULL default 0,
      i07_vlprefeitura    float8 NOT NULL default 0,
      i07_vlduplicado   float8 NOT NULL default 0,
      i07_vlfunset    float8 NOT NULL default 0,
      i07_vldetran    float8 NOT NULL default 0,
      i07_vlprestacaocontas   float8  default 0,
      i07_vloutros    float8  default 0,
      i07_dtmovimento   date NOT NULL default null,
      i07_remessa   char(5) NOT NULL ,
      i07_convenio    char(6) ,
      CONSTRAINT arquivoinfracao_sequ_pk PRIMARY KEY (i07_sequencial));

      -- Módulo: caixa
      CREATE TABLE caixa.infracaotransito(
      i05_sequencial    int4 NOT NULL default 0,
      i05_codigo    varchar(10) NOT NULL ,
      i05_nivel   int4 NOT NULL default 0,
      i05_descricao   varchar(100) ,
      CONSTRAINT infracaotransito_sequ_pk PRIMARY KEY (i05_sequencial));


      -- Módulo: caixa
      CREATE TABLE caixa.receitainfracao(
      i06_sequencial    int4 NOT NULL default 0,
      i06_receitaprincipal    int4 NOT NULL default 0,
      i06_receitaduplicidade    int4 NOT NULL default 0,
      i06_anousu    int4 NOT NULL default 0,
      i06_nivel   int4 NOT NULL default 0,
      i06_conta   int4 default 0,
      CONSTRAINT receitainfracao_sequ_pk PRIMARY KEY (i06_sequencial));
SQL
      );
    }

  private function removeritensMenu() {

    $this->execute("delete from configuracoes.db_menu where id_item_filho in (10448,10449,10450,10451,10452,10453,10454,10455,10456,10457) AND modulo = 39");
    $this->execute("delete from configuracoes.db_itensmenu where id_item in (10448,10449,10450,10451,10452,10453,10454,10455,10456,10457)");
  }

  private function removerDicionarioDados() {

    $this->execute(
<<<SQL
      delete from db_syscampodep  where codcam       = 1009434;
      delete from db_syscampodef  where codcam       = 1009434;
      delete from db_sysarqcamp   where codarq       = 1010226;
      delete from db_syscampo     where codcam in ( 1009431, 1009432, 1009433, 1009434, 1009435, 1009436, 1009437, 1009438, 1009439, 1009440, 1009441, 1009442, 1009445, 1009446, 1009447);
      delete from db_sysarqmod    where codarq       = 1010226;
      delete from db_sysarquivo   where codarq       = 1010226;
      delete from db_sysarqcamp   where codarq       = 1010225;
      delete from db_syssequencia where codsequencia = 1000689;
      delete from db_sysprikey    where codarq       = 1010225;
      delete from db_sysarqcamp   where codarq       = 1010225;
      delete from db_syscampo     where codcam in ( 1009426, 1009427, 1009428, 1009429, 1009430, 1009444);
      delete from db_sysarqmod    where codarq       = 1010225;
      delete from db_sysarquivo   where codarq       = 1010225;
      delete from db_sysarqcamp   where codarq       = 3964;
      delete from db_sysarqmod    where codarq       = 1010224;
      delete from db_sysarqcamp   where codarq       = 1010224;
      delete from db_syssequencia where codsequencia = 1000688;
      delete from db_sysprikey    where codarq       = 1010224;
      delete from db_sysarqcamp   where codarq       = 1010224;
      delete from db_sysarqcamp   where codarq       = 3964;
      delete from db_sysprikey    where codarq       = 1010224;
      delete from db_sysarqcamp   where codarq       = 1010224;
      delete from db_syscampo     where codcam in ( 1009422, 1009423, 1009424, 1009425);
      delete from db_sysarquivo   where codarq       = 1010224;
      
      delete from db_syscampodep where codcam = 1009434;
      delete from db_syscampodef where codcam = 1009434;
      delete from db_syscampodep where codcam = 1009433;
      delete from db_syscampodef where codcam = 1009433;
      delete from db_syscampodep where codcam = 1009435;
      delete from db_syscampodef where codcam = 1009435;
      delete from db_syscampodep where codcam = 1009436;
      delete from db_syscampodef where codcam = 1009436;


SQL
    );
  }

  private function removerTabela() {

    $this->execute(
<<<SQL
    --DROP TABLE:
    DROP TABLE IF EXISTS caixa.arquivoinfracao CASCADE;
    DROP TABLE IF EXISTS caixa.infracaotransito CASCADE;
    DROP TABLE IF EXISTS caixa.receitainfracao CASCADE;
    --Criando drop sequences
    DROP SEQUENCE IF EXISTS caixa.arquivoinfracao_i07_sequencial_seq;
    DROP SEQUENCE IF EXISTS caixa.infracaotransito_i05_sequencial_seq;
    DROP SEQUENCE IF EXISTS caixa.receitainfracao_i06_sequencial_seq;
SQL
    );
  }

  private function criarLayout() {

    $this->execute(
<<<SQL
      insert into db_layouttxt( db50_codigo ,db50_layouttxtgrupo ,db50_descr ,db50_quantlinhas ,db50_obs ) values ( 282 ,1 ,'ARQUIVO INFRAÇÃO DE TRÂNSITO' ,0 ,'Layout do arquivo de infrações de trânsito' );
      insert into db_layoutlinha( db51_codigo ,db51_layouttxt ,db51_descr ,db51_tipolinha ,db51_tamlinha ,db51_linhasantes ,db51_linhasdepois ,db51_obs ,db51_separador ,db51_compacta ) values ( 930 ,282 ,'HEADER' ,1 ,290 ,0 ,0 ,'Header do layout do arquivo de infrações de trânsito.' ,'' ,'0' );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16050 ,930 ,'identificador' ,'IDENTIFICADOR DO REGISTRO' ,2 ,1 ,'0' ,1 ,'f' ,'t' ,'e' ,'Conteúdo = 0' ,0 );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16051 ,930 ,'datamovimento' ,'DATA DO MOVIMENTO' ,10 ,2 ,'' ,8 ,'f' ,'t' ,'d' ,'Data do processamento' ,0 );
      update db_layoutcampos set db52_codigo = 16050 , db52_layoutlinha = 930 , db52_nome = 'identificadorregistro' , db52_descr = 'IDENTIFICADOR DO REGISTRO' , db52_layoutformat = 2 , db52_posicao = 1 , db52_default = '0' , db52_tamanho = 1 , db52_ident = 't' , db52_imprimir = 't' , db52_alinha = 'e' , db52_obs = 'Conteúdo = 0' , db52_quebraapos = 0 where db52_codigo = 16050;
      update db_layoutcampos set db52_posicao = db52_posicao+0 where db52_layoutlinha = 930 and db52_posicao >= 1 and db52_codigo <> 16050;
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16052 ,930 ,'identificacao' ,'IDENTIFICAÇÃO' ,2 ,10 ,'' ,6 ,'f' ,'t' ,'e' ,'56 concatenado com o código do convênio' ,0 );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16053 ,930 ,'sequencialremessa' ,'NÚMERO SEQUENCIAL DE REMESSA' ,2 ,16 ,'' ,5 ,'f' ,'t' ,'e' ,'Número sequencial da remessa' ,0 );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16054 ,930 ,'fillerheader' ,'FILLER DO HEADER' ,1 ,21 ,'' ,270 ,'f' ,'t' ,'d' ,'Espaços em branco' ,0 );
      insert into db_layoutlinha( db51_codigo ,db51_layouttxt ,db51_descr ,db51_tipolinha ,db51_tamlinha ,db51_linhasantes ,db51_linhasdepois ,db51_obs ,db51_separador ,db51_compacta ) values ( 931 ,282 ,'REGISTRO DETALHE' ,3 ,290 ,0 ,0 ,'Registro de detalhe do arquivo de multas de trânsito' ,'' ,'0' );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16055 ,931 ,'identificadorregistro' ,'IDENTIFICADOR DO REGISTRO' ,2 ,1 ,'1' ,1 ,'t' ,'t' ,'e' ,'Registro 1 = Detalhe' ,0 );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16056 ,931 ,'datamovimento' ,'DATA DO MOVIMENTO' ,10 ,2 ,'' ,0 ,'f' ,'t' ,'d' ,'Data do Processamento' ,0 );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16057 ,931 ,'bancoarrecadador' ,'NÚMERO DO BANCO ARRECADADOR' ,2 ,2 ,'' ,4 ,'f' ,'t' ,'e' ,'Número do banco que arrecadou o pagamento' ,0 );
      update db_layoutcampos set db52_posicao = db52_posicao+4 where db52_layoutlinha = 931 and db52_posicao >= 2 and db52_codigo <> 16057;
      update db_layoutcampos set db52_codigo = 16056 , db52_layoutlinha = 931 , db52_nome = 'datamovimento' , db52_descr = 'DATA DO MOVIMENTO' , db52_layoutformat = 10 , db52_posicao = 6 , db52_default = '' , db52_tamanho = 8 , db52_ident = 'f' , db52_imprimir = 't' , db52_alinha = 'd' , db52_obs = 'Data do Processamento' , db52_quebraapos = 0 where db52_codigo = 16056;
      delete from db_layoutcampos where db52_codigo = 16056;
      delete from db_layoutcampos where db52_codigo = 16057;
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16058 ,931 ,'datamovimento' ,'DATA MOVIMENTO' ,10 ,2 ,'' ,8 ,'f' ,'t' ,'d' ,'Data do processamento.' ,0 );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16059 ,931 ,'bancoarrecadador' ,'NÚMERO DO BANCO ARRECADADOR' ,2 ,10 ,'' ,4 ,'f' ,'t' ,'e' ,'Número do Banco que arrecadou o pagamento.' ,0 );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16060 ,931 ,'agenciaarrecadador' ,'NÚMERO AGENCIA ARRECADADORA' ,2 ,14 ,'' ,4 ,'f' ,'t' ,'e' ,'Número da agencia que arrecadou o pagamento' ,0 );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16061 ,931 ,'formaarrecadacao' ,'FORMA DE ARRECADAÇÃO' ,2 ,18 ,'' ,2 ,'f' ,'t' ,'e' ,'Forma de Arrecadação/Canal 01=Guichê de Caixa 02=Arrecadação Eletrônica(Terminais de Auto-atendimento, ATM, home/Office banking) 03=Internet 05=Correspondentes não bancários 06=Telefone 99=Outros Bancos' ,0 );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16062 ,931 ,'nossonumero' ,'IDENTIFICADOR DO TITULO NO BANCO' ,1 ,20 ,'' ,11 ,'f' ,'t' ,'d' ,'Número de Identificação do título no Banco - Nosso número sem DV' ,0 );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16063 ,931 ,'nossonumerodv' ,'DIGITO VERIFICADOR DO NOSSO NÚMERO' ,1 ,31 ,'P' ,1 ,'f' ,'t' ,'d' ,'Digito verificador do nosso número' ,0 );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16064 ,931 ,'datapagamento' ,'DATA DO PAGAMENTO' ,10 ,32 ,'' ,8 ,'f' ,'t' ,'d' ,'' ,0 );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16065 ,931 ,'filler1' ,'FILLER 1' ,1 ,40 ,'' ,8 ,'f' ,'t' ,'d' ,'Fixo 00000000' ,0 );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16066 ,931 ,'tipodocumento' ,'TIPO DO DOCUMENTO' ,2 ,48 ,'' ,1 ,'f' ,'t' ,'e' ,'Multas = 2' ,0 );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16067 ,931 ,'autoinfracao' ,'AUTO DE INFRAÇÃO' ,1 ,49 ,'' ,13 ,'f' ,'t' ,'d' ,'Auto de Infração (Letra e Número)' ,0 );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16068 ,931 ,'codigoinfracao' ,'CÓDIGO DA INFRAÇÃO' ,1 ,62 ,'' ,5 ,'f' ,'t' ,'d' ,'Código da Infração. Cada tipo de infração é uma receita dentro do e-cidade. Esses códigos atualizam, tem q dar manutenção quando vem a atualização do DETRAN' ,0 );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16069 ,931 ,'datarepasse' ,'DATA DO REPASSE' ,10 ,67 ,'' ,8 ,'f' ,'t' ,'d' ,'' ,0 );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16070 ,931 ,'valorbruto' ,'VALOR BRUTO' ,3 ,75 ,'' ,14 ,'f' ,'t' ,'e' ,'Numérico com12 inteiros e 2 decimais Valor Total Pago' ,0 );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16071 ,931 ,'diasbloqueio' ,'DIAS BLOQUEIO' ,2 ,89 ,'' ,2 ,'f' ,'t' ,'e' ,'00=Pagamento em dinheiro ' ,0 );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16072 ,931 ,'valorprefeitura' ,'VALOR LIQUIDO' ,3 ,91 ,'' ,14 ,'f' ,'t' ,'e' ,'Numérico com12 inteiros e 2 decimais Valor repasse para o conveniado. ESSE É O VALOR QUE ENTRA PARA A PREFEITURA.' ,0 );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16073 ,931 ,'filler2' ,'FILLER2' ,1 ,105 ,'' ,14 ,'f' ,'t' ,'d' ,'Fixo = 00000000000000' ,0 );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16074 ,931 ,'codigodetran' ,'CÓDIGO DETRAN' ,1 ,119 ,'' ,4 ,'f' ,'t' ,'d' ,'Fixo = 0001' ,0 );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16075 ,931 ,'valordetran' ,'VALOR DETRAN' ,3 ,123 ,'' ,14 ,'f' ,'t' ,'e' ,'Valor de repasse para o DETRAN' ,0 );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16076 ,931 ,'codigofunset' ,'CÓDIGO FUNSET' ,1 ,137 ,'' ,4 ,'f' ,'t' ,'d' ,'Fixo = 1121' ,0 );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16077 ,931 ,'valorfunset' ,'VALOR FUNSET' ,3 ,141 ,'' ,14 ,'f' ,'t' ,'e' ,'Valor de repasse para o FUNSET' ,0 );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16078 ,931 ,'filler3' ,'FILLER3' ,1 ,155 ,'' ,136 ,'f' ,'t' ,'d' ,'Fixo = ZEROS' ,0 );
      update db_layoutcampos set db52_codigo = 16066 , db52_layoutlinha = 931 , db52_nome = 'tipodocumento' , db52_descr = 'TIPO DO DOCUMENTO' , db52_layoutformat = 2 , db52_posicao = 48 , db52_default = '' , db52_tamanho = 1 , db52_ident = 'f' , db52_imprimir = 't' , db52_alinha = 'e' , db52_obs = 'Multas = 2' , db52_quebraapos = 0 where db52_codigo = 16066;
      update db_layoutcampos set db52_posicao = db52_posicao+0 where db52_layoutlinha = 931 and db52_posicao >= 48 and db52_codigo <> 16066;
      update db_layoutcampos set db52_codigo = 16050 , db52_layoutlinha = 930 , db52_nome = 'identificadorregistro' , db52_descr = 'IDENTIFICADOR DO REGISTRO' , db52_layoutformat = 2 , db52_posicao = 1 , db52_default = '0' , db52_tamanho = 1 , db52_ident = 't' , db52_imprimir = 't' , db52_alinha = 'e' , db52_obs = 'Conteúdo = 0' , db52_quebraapos = 0 where db52_codigo = 16050;
      update db_layoutcampos set db52_posicao = db52_posicao+0 where db52_layoutlinha = 930 and db52_posicao >= 1 and db52_codigo <> 16050;
      insert into db_layoutlinha( db51_codigo ,db51_layouttxt ,db51_descr ,db51_tipolinha ,db51_tamlinha ,db51_linhasantes ,db51_linhasdepois ,db51_obs ,db51_separador ,db51_compacta ) values ( 932 ,282 ,'TRAILLER' ,5 ,290 ,0 ,0 ,'Trailler do arquivo de multas de trânsito' ,'' ,'0' );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16079 ,932 ,'identificadorregistro' ,'IDENTIFICADOR DO REGISTRO' ,2 ,1 ,'9' ,0 ,'t' ,'t' ,'e' ,'9 = Trailler' ,0 );
      update db_layoutcampos set db52_codigo = 16079 , db52_layoutlinha = 932 , db52_nome = 'identificadorregistro' , db52_descr = 'IDENTIFICADOR DO REGISTRO' , db52_layoutformat = 2 , db52_posicao = 1 , db52_default = '9' , db52_tamanho = 1 , db52_ident = 't' , db52_imprimir = 't' , db52_alinha = 'e' , db52_obs = '9 = Trailler' , db52_quebraapos = 0 where db52_codigo = 16079;
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16080 ,932 ,'datamovimento' ,'DATA DO MOVIMENTO' ,10 ,2 ,'' ,8 ,'f' ,'t' ,'d' ,'Data do Processamento' ,0 );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16081 ,932 ,'totalregistro' ,'TOTAL DE REGISTROS' ,2 ,10 ,'' ,6 ,'f' ,'t' ,'e' ,'Total de registros sem o Header e Trailler' ,0 );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16082 ,932 ,'valorbruto' ,'TOTAL VALOR BRUTO' ,3 ,16 ,'' ,15 ,'f' ,'t' ,'e' ,'' ,0 );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16083 ,932 ,'totalregistrogrupo' ,'TOTAL DE REGISTROS DO GRUPO' ,2 ,31 ,'' ,6 ,'f' ,'t' ,'e' ,'Gerado apenas para a Prefeitura do Rio de Janeiro no último Trailler. A Prefeitura do Rio de Janeiro corresponde a todos os convênios em que o campo. Código do órgão para gerar arquivo = 01 no arquivo "MULTAS" VALORES CONVENIOS - Este campo conterá o total de registros gravados sem os Header´s e Trailler´s de cada convênio da Prefeitura do Rio de Janeiro.' ,0 );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16084 ,932 ,'valorbrutogrupo' ,'VALOR BRUTO TOTAL DO GRUPO' ,3 ,37 ,'' ,15 ,'f' ,'t' ,'e' ,'' ,0 );
      insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 16085 ,932 ,'filler' ,'FILLER' ,1 ,52 ,'' ,239 ,'f' ,'t' ,'d' ,'Fixo = Branco ' ,0 );

SQL

    );
  }

  private function removerLayout() {

    $this->execute(
<<<SQL
  delete from db_layoutcampos where db52_layoutlinha in (932, 931, 930);
  delete from db_layoutlinha  where db51_codigo in (932, 931, 930);
  delete from db_layouttxt    where db50_codigo in (282);
SQL
    );
  }

}
