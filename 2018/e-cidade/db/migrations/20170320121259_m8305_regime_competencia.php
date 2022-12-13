<?php

use Classes\PostgresMigration;

class M8305RegimeCompetencia extends PostgresMigration
{
   public function up()
   {

      $this->cadastroMenus();
      $this->executaPre();
      $this->executaDDL();
      $this->criarDocumentos();
   }

   public function executaPre()
   {
      /**
       * inserção dos dados na tabela db_syscampo
       */
      $aColunas   = array('codcam', 'nomecam', 'conteudo', 'descricao', 'valorinicial', 'rotulo', 'tamanho', 'nulo', 'maiusculo', 'autocompl', 'aceitatipo', 'tipoobj', 'rotulorel');
      $aValores   = array();
      // programacaofinanceira
      $aValores[] = array(22410,'k117_despesaantecipada','bool','Informa se o contrato é uma despesa antecipada ou nõ','f', 'Despesa Antecipada',1,'f','f','f',5,'text','Despesa Antecipada');
      $aValores[] = array(22411,'k117_conta','int4','Conta contábil onde será debitado o valor','0', 'Conta',10,'f','f','f',1,'text','Conta');

      // programacaofinanceiraitem
      $aValores[] = array(22412,'k175_sequencial','int4','Código sequencial da tabela programacaofinanceiraitem','0', 'Código',10,'f','f','f',1,'text','Código');
      $aValores[] = array(22413,'k175_item','int4','Código de relação com o item de contrato','0', 'Item',10,'f','f','f',1,'text','Item');
      $aValores[] = array(22414,'k175_programaacaofinanceira','int4','Código da programação financeira. Tabela: programacaofinanceira','0', 'Programação Financeira',10,'f','f','f',1,'text','Programação Financeira');
      $aValores[] = array(22415,'k175_valortotal','float8','Valor total do item do contrato','0', 'Valor Total',10,'f','f','f',4,'text','Valor Total');

      //conlancamprogramacaofinanceiraparcela
      $aValores[] = array(22417,'c118_conlancam','int4','Código do lançamento contabil','0', 'Lançamento Contabil',10,'f','f','f',1,'text','Lançamento Contabil');
      $aValores[] = array(22418,'c118_programacaofinanceiraparcela','int4','Vinculo com a tabela programacaofinanceiraparcela','0', 'Programação Financeira Parcela',10,'f','f','f',1,'text','Programação Financeira Parcela');
      $aValores[] = array(22420 ,'k118_ano' ,'int4' ,'Ano' ,'' ,'Ano' ,4 ,'false' ,'false' ,'false' ,1 ,'text' ,'Ano' );
      $aValores[] = array(22421 ,'k118_mes' ,'int4' ,'Mês' ,'' ,'Mês' ,2 ,'false' ,'false' ,'false' ,1 ,'text' ,'Mês');
      $aValores[] = array(22422 ,'k118_reconhecido' ,'bool' ,'Parcela já reconhecida' ,'false' ,'Reconhecido' ,1 ,'true' ,'false' ,'false' ,5 ,'text' ,'Reconhecido');

      $oSysCampo = $this->table('db_syscampo', array('schema' => 'configuracoes'));
      $oSysCampo->insert($aColunas, $aValores);
      $oSysCampo->saveData();

      $aColunas = array('codarq', 'nomearq', 'descricao', 'sigla', 'dataincl', 'rotulo', 'tipotabela', 'naolibclass', 'naolibfunc', 'naolibprog', 'naolibform');
      $aValores = array();
      // tabela programacaofinanceiraitem
      $aValores[]  = array(4034, 'programacaofinanceiraitem', 'vinculo do item de contrato com a programação financeira', 'k175', '2017-03-21', 'Programação Financeira Item', 0, 'f', 'f', 'f', 'f' );
      // tabela conlancamprogramacaofinanceiraparcela
      $aValores[]  = array(4035, 'conlancamprogramacaofinanceiraparcela', 'Tabela de vinculo entra a conlancam e programacaofinanceiraparcela', 'c118', '2017-03-22', 'conlancam_programacaofinanceiraparcela', 0, 'f', 'f', 'f', 'f' );
      $oSysArquivo = $this->table('db_sysarquivo', array('schema' => 'configuracoes'));
      $oSysArquivo->insert($aColunas, $aValores);
      $oSysArquivo->saveData();

      $oSysArquivo = $this->table('db_sysarqmod', array('schema' => 'configuracoes'));
      $aValores = array();
      //Vinculando a tabela programacaofinanceiraitem ao modulo
      $aValores[] = array(5,4034);
      //Vinculando a tabela conlancamprogramacaofinanceiraparcela ao modulo
      $aValores[] = array(32,4035);
      $oSysArquivo->insert(array('codmod', 'codarq'), $aValores);
      $oSysArquivo->saveData();

      $this->execute('delete from db_sysarqcamp where codarq = 3025 and codcam in (17128, 17129, 17130)');

      // inserindo colunas db_sysarqcamp
      $aColunas   = array('codarq', 'codcam', 'seqarq', 'codsequencia');
      $aValores   = array();
      // programacaofinanceira
      $aValores[] = array(3025,22410,4,0);
      $aValores[] = array(3025,22411,5,0);
      // programacaofinanceiraitem
      $aValores[] = array(4034,22412,1,1000658);
      $aValores[] = array(4034,22413,2,0);
      $aValores[] = array(4034,22414,3,0);
      $aValores[] = array(4034,22415,4,0);
      //programacaofinanceiraparcela
//      $aValores[] = array(3026,22416,2,0);
      //conlancamprogramacaofinanceiraparcela
      $aValores[] = array(4035,22417,1,0);
      $aValores[] = array(4035,22418,2,0);
      $aValores[] = array(3026 ,22420 ,5 ,0 );
      $aValores[] = array(3026 ,22421 ,5 ,0);
      $aValores[] = array(3026 ,22422 ,5 ,0);

      $oSysArqCamp = $this->table('db_sysarqcamp', array('schema' => 'configuracoes'));
      $oSysArqCamp->insert($aColunas, $aValores);
      $oSysArqCamp->saveData();

      // sequencia tabela programacaofinanceiraitem
      $aColunas   = array('codsequencia', 'nomesequencia', 'incrseq', 'minvalueseq', 'maxvalueseq', 'startseq', 'cacheseq');
      $aValores   = array(array(1000658, 'programacaofinanceiraitem_k175_sequencial_seq', 1, 1, 9223372036854775807, 1, 1));

      $oSysSequencia = $this->table('db_syssequencia', array('schema' => 'configuracoes'));
      $oSysSequencia->insert($aColunas, $aValores);
      $oSysSequencia->saveData();

      $aColunas   = array('codarq','codcam','sequen','camiden');
      $aValores   = array();

      // primary tabela programacaofinanceiraitem
      $aValores[] = array(4034,22412,1,22412);
      // primary tabela conlancamprogramacaofinanceiraparcela
      $aValores[] = array(4035,22417,1,22417);
      $aValores[] = array(4035,22418,2,22417);

      $oSysPriKey = $this->table('db_sysprikey', array('schema' => 'configuracoes'));
      $oSysPriKey->insert($aColunas, $aValores);
      $oSysPriKey->saveData();

      // Chaves estrangeiras
      $aColunas   = array('codarq', 'codcam', 'sequen', 'referen', 'tipoobjrel');
      $aValores   = array();
      // chave estrangeira da tabela programacaofinanceira
      $aValores[] = array(3025,22411,1,774,0);
      // chave estrangeira da tabela programacaofinanceiraitem
      $aValores[] = array(4034,22414,1,3025,0);
      $aValores[] = array(4034,22413,1,2837,0);
      //chave estrangeira da tabela programacaofinanceiraparcela
//      $aValores[] = array(3026,22416,1,4034,0);
      //chave estrangeira da tabela conlancamprogramacaofinanceiraparcela
      $aValores[] = array(4035,22417,1,760,0);
      $aValores[] = array(4035,22418,1,3026,0);

      $oSysForKey = $this->table('db_sysforkey', array('schema' => 'configuracoes'));
      $oSysForKey->insert($aColunas, $aValores);
      $oSysForKey->saveData();

     /**
      * Indices
      */
     $aColumns = array('codind', 'nomeind', 'codarq', 'campounico');
     $aValues  = array(
       array(4432,'programacaofinanceiraparcela_ano_in',3026,'0'),
       array(4433,'programacaofinanceiraparcela_mes_in',3026,'0'),
     );
     $table    = $this->table('db_sysindices', array('schema' => 'configuracoes'));
     $table->insert($aColumns, $aValues);
     $table->saveData();
      //Alterando a coluna da tabela k118_competencia de k18_datapagamento para k118_competencia
     $this->execute('delete from configuracoes.db_sysarqcamp where codcam = 17134');

     $this->execute(
<<<STRING
  delete from configuracoes.db_sysforkey where codarq = 3026;
  delete from configuracoes.db_sysarqcamp where codarq = 3026;
  insert into configuracoes.db_sysarqcamp values(3026,17131,1,1924);
  insert into configuracoes.db_sysarqcamp values(3026,17132,2,0);
  insert into configuracoes.db_sysarqcamp values(3026,17133,3,0);
  insert into configuracoes.db_sysarqcamp values(3026,17135,4,0);
  insert into configuracoes.db_sysarqcamp values(3026,22420,5,0);
  insert into configuracoes.db_sysarqcamp values(3026,22421,6,0);
  insert into configuracoes.db_sysarqcamp values(3026,22422,7,0);
  delete from configuracoes.db_sysforkey where codarq = 3026 and referen = 3025;
  insert into configuracoes.db_sysforkey values(3026,17132,1,3025,0);
STRING


     );
   }

   public function executaDDL() {

      /**
       * Criando a tabela programacaofinanceiraitem.
       */
      $oProgramacaofinanceiraitem = $this->table('programacaofinanceiraitem', array('schema' => 'caixa', 'id' => false, 'primary_key' => 'k175_sequencial'));
      $oProgramacaofinanceiraitem->addColumn('k175_sequencial', 'integer')
                                 ->addColumn('k175_item', 'integer')
                                 ->addColumn('k175_programaacaofinanceira', 'integer')
                                 ->addColumn('k175_valortotal', 'float')
                                 ->addForeignKey('k175_programaacaofinanceira', 'caixa.programacaofinanceira', 'k117_sequencial')
                                 ->addForeignKey('k175_item', 'acordos.acordoitem', 'ac20_sequencial')
                                 ->save();

      $this->execute('create sequence caixa.programacaofinanceiraitem_k175_sequencial_seq increment 1 minvalue 1 maxvalue 9223372036854775807 start 1 cache 1');
      $this->execute("ALTER TABLE caixa.programacaofinanceiraitem ALTER COLUMN k175_sequencial SET DEFAULT nextval('caixa.programacaofinanceiraitem_k175_sequencial_seq')");

      // realizando alteracoes necessarias na tabela programacaofinanceira
      $oProgramacaofinanceira = $this->table('programacaofinanceira', array('schema' => 'caixa'));
      $oProgramacaofinanceira->removeColumn('k117_periodicidade')
                             ->removeColumn('k117_valortotal')
                             ->removeColumn('k117_diapagamento')
                             ->addColumn('k117_despesaantecipada','boolean', array('default' => 'f'))
                             ->addColumn('k117_conta', 'integer', array('null' => true))
                             ->save();

      // realizando alteracoes necessarias na tabela programacaofinanceiraparcela
      $oProgramacaofinanceiraParcela = $this->table('programacaofinanceiraparcela', array('schema' => 'caixa'));
      $oProgramacaofinanceiraParcela->removeColumn('k118_datapagamento')
                                    ->addColumn('k118_ano', 'integer', array('null' => true))
                                    ->addColumn('k118_mes', 'integer', array('null' => true))
                                    ->addColumn('k118_reconhecido', 'boolean', array('default'=>false, 'null'=>true))
                                    ->addIndex(array('k118_ano'), array('name' => 'programacaofinanceiraparcela_ano_in'))
                                    ->addIndex(array('k118_mes'), array('name' => 'programacaofinanceiraparcela_mes_in'))
                                    ->save();

      $this->execute('alter table programacaofinanceiraparcela alter column k118_valor type numeric;');

      $oConlanParcela = $this->table('conlancamprogramacaofinanceiraparcela', array('schema' => 'contabilidade', 'id' => false, 'primary_key' => array('c118_conlancam', 'c118_programacaofinanceiraparcela')));
      $oConlanParcela->addColumn('c118_conlancam', 'integer')
                     ->addColumn('c118_programacaofinanceiraparcela', 'integer')
                     ->addForeignKey('c118_conlancam', 'contabilidade.conlancam', 'c70_codlan')
                     ->addForeignKey('c118_programacaofinanceiraparcela', 'caixa.programacaofinanceiraparcela', 'k118_sequencial')
                     ->save();
   }

   public function cadastroMenus()
   {
      //Atualização do nome do menu de Programação Financeira para Programação de Regime de COmpetência.
      $this->execute("update db_itensmenu set descricao='Programação do Regime de Competência' , help='Programação de Regime de Competência', funcao='con4_programacaoregimecompetencia001.php' where id_item=8580");

      $oDBItensMenu = $this->table('db_itensmenu', array('schema' => 'configuracoes'));
      $oDBMenu      = $this->table('db_menu', array('schema' => 'configuracoes'));

      $aColunas   = array('id_item','descricao','help','funcao','itemativo','manutencao','desctec','libcliente');
      $aValores   = array();

      // Menu procedimentos > Regime de Competência.
      $aValores[] = array(10414, 'Regime de competência', 'Regime de competência', 'cai4_regimecompetencia001.php', '1', '1', 'rotina para realização do reconhecimento de divida', 'true');

      // Menu relatório > Regime de Competência.
      $aValores[] = array(10415 ,' Regime de competência' ,' Regime de competência' ,'cai2_regimecompetencia001.php' ,'1' ,'1' ,'Relatório para o Regime de competência.' ,'true');

      $oDBItensMenu->insert($aColunas, $aValores);
      $oDBItensMenu->saveData();


      $aDbMenu = array(
        'id_item' =>  9828,
        'id_item_filho' =>  10414,
        'menusequencia' =>  7,
        'modulo' => 209
      );

      $aColunas   = array('id_item' ,'id_item_filho' ,'menusequencia' ,'modulo');
      $aValores   = array();
      // Menu procedimentos > Regime de Competência.
      $aValores[] = array(9828, 10414, 7, 209);
      // Menu relatório > Regime de Competência.
      $aValores[] = array(3331 ,10415 ,50 ,209);

      $oDBMenu->insert($aColunas, $aValores);
      $oDBMenu->saveData();
   }

  protected function criarDocumentos() {

    $this->execute(
<<<STRING
        
  insert into contabilidade.conhist values (4000, true, 'RECONHECIMENTO DE COMPETENCIA');
  
  insert into contabilidade.conhistdoctipo 
       values (4000, 'RECONHECIMENTO DE COMPETENCIA'),
              (4001, 'ESTORNO RECONHECIMENTO DE COMPETENCIA');

  insert into contabilidade.conhistdoc 
       values (4000, 'COMPETÊNCIA DE OUTRAS DESPESAS', 4000),
              (4001, 'ESTORNO DE COMPETÊNCIA DE OUTRAS DESPESAS', 4001),
              (4002, 'COMPETÊNCIA DE DESPESAS ANTECIPADAS', 4000),
              (4003, 'ESTORNO DE COMPETÊNCIA DE DESPESAS ANTECIPADAS', 4001);

  insert into contabilidade.vinculoeventoscontabeis
       values (nextval('vinculoeventoscontabeis_c115_sequencial_seq'), 4000, 4001),
              (nextval('vinculoeventoscontabeis_c115_sequencial_seq'), 4002, 4003);

STRING
    );

  }

   public function down()
   {
      $this->execute("update db_itensmenu set descricao='Programação Financeira' , help='Programação Financeira', funcao='con4_programacaofinanceira001.php' where id_item=8580");

      $this->execute('delete from db_sysforkey where codarq = 3025 and codcam in (22411)');
      $this->execute('delete from db_sysforkey where codarq = 4034 and codcam in (22414, 22413)');
      $this->execute('delete from db_sysforkey where codarq = 3026 and codcam in (22416)');
      $this->execute('delete from configuracoes.db_syscadind where codind in(4432, 4433)');
      $this->execute('delete from configuracoes.db_sysindices where codind in(4432, 4433)');
      $this->execute('delete from db_sysforkey where codarq = 4035');
      $this->execute('delete from db_syssequencia where codsequencia = 1000658');
      $this->execute('delete from db_sysprikey where codarq = 4034 and codcam = 22412');
      $this->execute('delete from db_sysprikey where codarq = 4035');
      $this->execute('delete from db_sysarqcamp where codarq = 3025 and codcam in (22410, 22411)');
      $this->execute('delete from db_sysarqcamp where codarq = 4034 and codcam in (22412, 22413, 22414, 22415)');
      $this->execute('delete from db_sysarqcamp where codarq = 3026 and codcam in (22416, 22420, 22421, 22422)');
      $this->execute('delete from db_sysarqcamp where codarq = 4035');
      $this->execute('delete from db_syscampo where codcam in (22410,22411,22412,22413,22414,22415,22416,22417,22418,22420, 22421, 22422)');
      $this->execute('delete from db_sysarqmod where codarq in(4034, 4035)');
      $this->execute('delete from db_sysarquivo where codarq in(4034, 4035)');


      $oProgramacaofinanceira = $this->table('programacaofinanceira', array('schema' => 'caixa'));
      $oProgramacaofinanceira->addColumn('k117_periodicidade', 'integer', array('null' => true))
                             ->addColumn('k117_valortotal', 'float', array('null' => true))
                             ->addColumn('k117_diapagamento', 'integer', array('null' => true))
                             ->removeColumn('k117_despesaantecipada')
                             ->removeColumn('k117_conta')
                             ->save();

      $oProgramacaofinanceiraParcela = $this->table('programacaofinanceiraparcela', array('schema' => 'caixa'));
      $oProgramacaofinanceiraParcela->addColumn('k118_datapagamento', 'date', array('null' => true))
                                    ->removeColumn('k118_ano')
                                    ->removeColumn('k118_mes')
                                    ->removeColumn('k118_reconhecido')
                                    ->save();

      $this->execute('drop table if exists caixa.programacaofinanceiraitem');
      $this->execute('drop table if exists contabilidade.conlancamprogramacaofinanceiraparcela');
      $this->execute('drop sequence if exists caixa.programacaofinanceiraitem_k175_sequencial_seq');

      //Deletando os menus criados.
      $this->execute('delete from db_menu where id_item_filho in(10414, 10415) AND modulo = 209');
      $this->execute('delete from db_itensmenu where id_item in (10414, 10415)');


     $this->execute('delete from contabilidade.conhist where c50_codhist = 4000;');
     $this->execute('delete from contabilidade.vinculoeventoscontabeis where c115_conhistdocinclusao in (4000, 4002);');
     $this->execute('delete from contabilidade.conhistdoc where c53_coddoc in (4000, 4001, 4002, 4003);');
     $this->execute('delete from contabilidade.conhistdoctipo where c57_sequencial in (4000, 4001);');
   }


}