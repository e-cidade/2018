<?php

use Classes\PostgresMigration;

class M9217TarifaFormaArrecadacaoDebito extends PostgresMigration
{
  public function up ()
  {
    $this->criarEstruturaEcidade();

    $tabelaFormaArrecadacao = $this->table('formaarrecadacao', array('schema' => 'caixa', 'id' => 'k178_sequencial', 'primary_key' => array('k178_sequencial')));
    $tabelaFormaArrecadacao
      ->addColumn('k178_codigo'   , 'string', array('limit' => 5))
      ->addColumn('k178_descricao', 'string', array('limit' => 200))
      ->create();

    $tabelaDisbanco = $this->table('disbancotarifa', array('schema' => 'caixa', 'id' => 'k179_sequencial', 'primary_key' => array('k179_sequencial')));
    $tabelaDisbanco
      ->addColumn('k179_idret', 'integer', array('null' => false))
      ->addColumn('k179_formaarrecadacao', 'integer', array('null' => false))
      ->addColumn('k179_valor', 'decimal', array('null' => false))
      ->addForeignKey('k179_idret', 'disbanco', 'idret')
      ->addForeignKey('k179_formaarrecadacao', 'formaarrecadacao', 'k178_sequencial')
      ->addIndex('k179_idret', array('unique' => true))
      ->create();


    $this->execute(
<<<SQLINSERTFORMAARRECADACAO
insert into caixa.formaarrecadacao
     values (nextval('formaarrecadacao_k178_sequencial_seq'), '1', 'Guich� de Caixa com fatura/guia de arrecada��o'),
            (nextval('formaarrecadacao_k178_sequencial_seq'), '2', 'Arrecada��o Eletr�nica com fatura/guia de arrecada��o (terminais de auto - atendimento, ATM, home/office banking)'),
            (nextval('formaarrecadacao_k178_sequencial_seq'), '3', 'Internet com fatura/guia de arrecada��o'),
            (nextval('formaarrecadacao_k178_sequencial_seq'), '4', 'Outros meios com fatura/guia de arrecada��o'),
            (nextval('formaarrecadacao_k178_sequencial_seq'), '5', 'Correspondentes banc�rios com fatura/guia de arrecada��o'),
            (nextval('formaarrecadacao_k178_sequencial_seq'), '6', 'Telefone com fatura/guia de arrecada��o'),
            (nextval('formaarrecadacao_k178_sequencial_seq'), '7', 'Casas lot�ricas com fatura/guia de arrecada��o'),
            (nextval('formaarrecadacao_k178_sequencial_seq'), 'a', 'Guich� de Caixa sem fatura/guia de arrecada��o'),
            (nextval('formaarrecadacao_k178_sequencial_seq'), 'b', 'Arrecada��o Eletr�nica sem fatura/guia de arrecada��o (terminais de auto - atendimento, ATM, home/office banking)'),
            (nextval('formaarrecadacao_k178_sequencial_seq'), 'c', 'Internet sem fatura/guia de arrecada��o'),
            (nextval('formaarrecadacao_k178_sequencial_seq'), 'd', 'Correspondentes banc�rios sem fatura/guia de arrecada��o'),
            (nextval('formaarrecadacao_k178_sequencial_seq'), 'e', 'Telefone sem fatura/guia de arrecada��o'),
            (nextval('formaarrecadacao_k178_sequencial_seq'), 'f', 'Outros meios sem fatura/guia de arrecada��o'),
            (nextval('formaarrecadacao_k178_sequencial_seq'), 'g', 'Casas lot�ricas sem fatura/guia de arrecada��o');
SQLINSERTFORMAARRECADACAO

    );

    $this->execute("update db_itensmenu set libcliente = 'false' where id_item = 5062;");
  }


  public function down ()
  {
    $this->downEstruturaECidade();

    $tabela = $this->table('disbancotarifa', array('schema' => 'caixa'));
    $tabela->drop();
    $tabela = $this->table('formaarrecadacao', array('schema' => 'caixa'));
    $tabela->drop();

    $this->execute("update db_itensmenu set libcliente = 'true' where id_item = 5062;");
  }


  private function criarEstruturaEcidade()
  {
    $this->execute(
<<<SQLUP
insert into configuracoes.db_syscampo values(1009386,'k178_sequencial','int4','C�digo Sequencial','0', 'C�digo Sequencial',10,'f','f','f',1,'text','C�digo Sequencial');
insert into configuracoes.db_syscampo values(1009387,'k178_codigo','varchar(10)','C�digo do Banco','', 'C�digo do Banco',10,'f','t','f',0,'text','C�digo do Banco');
insert into configuracoes.db_syscampo values(1009388,'k178_descricao','varchar(200)','Descri��o','', 'Descri��o',200,'f','t','f',0,'text','Descri��o');
insert into configuracoes.db_sysarquivo values (1010215, 'formaarrecadacao', 'Forma de arrecada��o', 'k178', '2017-08-16', 'formaarrecadacao', 0, 'f', 'f', 'f', 'f' );
insert into configuracoes.db_sysarqmod values (5,1010215);
delete from configuracoes.db_sysarqcamp where codarq = 1010215;
insert into configuracoes.db_sysarqcamp values(1010215,1009386,1,0);
insert into configuracoes.db_sysarqcamp values(1010215,1009387,2,0);
insert into configuracoes.db_sysarqcamp values(1010215,1009388,3,0);
delete from configuracoes.db_sysprikey where codarq = 1010215;
insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1010215,1009386,1,1009388);
insert into configuracoes.db_sysindices values(1008215,'formaarrecadacao_sequencial_in',1010215,'0');
insert into configuracoes.db_syscadind values(1008215,1009386,1);
insert into configuracoes.db_syssequencia values(1000679, 'formaarrecadacao_k178_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update configuracoes.db_sysarqcamp set codsequencia = 1000679 where codarq = 1010215 and codcam = 1009386;

insert into configuracoes.db_syscampo values(1009389,'k179_sequencial','int4','C�digo sequencial','0', 'C�digo',10,'f','f','f',1,'text','C�digo');
insert into configuracoes.db_syscampo values(1009390,'k179_idret','int4','C�digo Disbanco','0', 'C�digo Disbanco',10,'f','f','f',1,'text','C�digo Disbanco');
insert into configuracoes.db_syscampo values(1009391,'k179_valor','float4','Valor da Tarifa','0', 'Valor da Tarifa',10,'f','f','f',4,'text','Valor da Tarifa');
insert into configuracoes.db_syscampo values(1009392,'k179_formaarrecadacao','int4','Forma de Arrecada��o','0', 'Forma de Arrecada��o',10,'f','f','f',1,'text','Forma de Arrecada��o');
insert into configuracoes.db_sysarquivo values (1010216, 'disbancotarifa', 'Tarifas dos arquivos processados pela importa��o do arquivo de retorno de uma baixa de banco', 'k179', '2017-08-16', 'disbancotarifa', 0, 'f', 'f', 'f', 'f' );
insert into configuracoes.db_sysarqmod values (5,1010216);
delete from configuracoes.db_sysarqcamp where codarq = 1010216;
insert into configuracoes.db_sysarqcamp values(1010216,1009389,1,0);
insert into configuracoes.db_sysarqcamp values(1010216,1009390,2,0);
insert into configuracoes.db_sysarqcamp values(1010216,1009392,3,0);
insert into configuracoes.db_sysarqcamp values(1010216,1009391,4,0);
delete from configuracoes.db_sysforkey where codarq = 1010216 and referen = 0;
insert into configuracoes.db_sysforkey values(1010216,1009390,1,214,0);
delete from configuracoes.db_sysforkey where codarq = 1010216 and referen = 0;
insert into configuracoes.db_sysforkey values(1010216,1009392,1,1010215,0);
delete from configuracoes.db_sysprikey where codarq = 1010216;
insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1010216,1009389,2,1009390);
insert into configuracoes.db_sysindices values(1008216,'disbancotarifa_idret_in',1010216,'1');
insert into configuracoes.db_syscadind values(1008216,1009390,1);
insert into configuracoes.db_syssequencia values(1000680, 'disbancotarifa_k179_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update configuracoes.db_sysarqcamp set codsequencia = 1000680 where codarq = 1010216 and codcam = 1009389;

insert into configuracoes.db_itensmenu values( 10435, 'Tarifas de Arrecada��o', 'Tarifas de Arrecada��o', 'cai2_tarifaarrecadacao001.php', '1', '1', 'Relat�rio com as tarifas de arrecada��o pagas pela prefeitura', '1'	);
insert into configuracoes.db_itensfilho (id_item, codfilho) values(10435,1);
insert into configuracoes.db_menu values(30,10435,467,1985522);
SQLUP
);
  }

  private function downEstruturaECidade()
  {
    $this->execute(
<<<SQLDOWN
delete from configuracoes.db_syssequencia where codsequencia in (1000679, 1000680);
delete from configuracoes.db_syscadind where codind in (1008215, 1008216);
delete from configuracoes.db_sysindices where codind in (1008215, 1008216);
delete from configuracoes.db_sysprikey where codarq in (1010215, 1010216);
delete from configuracoes.db_sysarqcamp where codarq in (1010215, 1010216);
delete from configuracoes.db_sysarqmod where codarq in (1010215, 1010216);
delete from configuracoes.db_sysforkey where codarq = 1010216;
delete from configuracoes.db_sysarquivo where codarq in (1010215, 1010216);
delete from configuracoes.db_syscampo where codcam in (1009386, 1009387, 1009388, 1009389, 1009390, 1009391, 1009392);

delete from configuracoes.db_menu where id_item_filho = 10435;
delete from configuracoes.db_itensfilho where id_item = 10435;
delete from configuracoes.db_itensmenu where id_item = 10435;
SQLDOWN

    );
  }
}
