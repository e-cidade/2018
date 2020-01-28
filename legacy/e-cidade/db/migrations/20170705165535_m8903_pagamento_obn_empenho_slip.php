<?php

use Classes\PostgresMigration;

class M8903PagamentoObnEmpenhoSlip extends PostgresMigration
{
  public function up()
  {
    $this->execute("insert into configuracoes.db_syscampo values(1009339,'e74_finalidade','varchar(3)','Finalidade para pagamento/transferencia','', 'Finalidade',3,'t','t','f',0,'text','Finalidade')");
    $this->execute("insert into configuracoes.db_sysarqcamp values(3595,1009339,10,0);");
    $this->execute(
     <<<String
      insert into configuracoes.db_sysarquivo values (1010209, 'cgmnatureza', 'Tabela da CGM da Natureza ', 'c05', '2017-07-05', 'CGM da Natureza ', 0, 'f', 'f', 'f', 'f' );
      insert into configuracoes.db_sysarqmod values (5,1010209);
      insert into configuracoes.db_syscampo values(1009348,'c05_tipo','int4','Tipo da natureza','0', 'Tipo da natureza',10,'f','f','f',1,'text','Tipo da natureza');
      insert into configuracoes.db_syscampo values(1009349,'c05_sequencial','int4','Código CGM da Natureza','0', 'Código CGM da Natureza',10,'f','f','f',1,'text','Código CGM da Natureza');
      insert into configuracoes.db_syscampo values(1009350,'c05_numcgm','int4','Código do CGM','0', 'Código do CGM',10,'f','f','f',1,'text','Código do CGM');
      insert into configuracoes.db_sysarqcamp values(1010209,1009349,1,0);
      insert into configuracoes.db_sysarqcamp values(1010209,1009350,2,0);
      insert into configuracoes.db_sysarqcamp values(1010209,1009348,3,0);
      insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1010209,1009349,1,1009350);
      insert into configuracoes.db_sysforkey values(1010209,1009350,1,42,0);
      insert into configuracoes.db_sysindices values(1008206,'cgmnatureza_numcgm_in',1010209,'0');
      insert into configuracoes.db_syscadind values(1008206,1009350,1);
      insert into configuracoes.db_syssequencia values(1000674, 'cgmnatureza_c05_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
      insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10431 ,'Natureza do CGM' ,'Natureza do CGM ' ,'' ,'1' ,'1' ,'Vincula natureza com CGM, para podermos saber qual natureza de finalidade esse cgm faz parte.' ,'true' );
      insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,10431 ,485 ,39 );
String

    );

    $this->execute("update configuracoes.db_sysarqcamp set codsequencia = 1000674 where codarq = 1010209 and codcam = 1009349;");
    $this->execute("update configuracoes.db_itensmenu set id_item = 10431 , descricao = 'Cadastro de Órgão Público' , help = 'Cadastro de Órgão Público' , funcao = 'cai4_cgmnatureza001.php' , itemativo = '1' , manutencao = '1' , desctec = 'Vincula natureza com CGM, para podermos saber qual natureza de finalidade esse cgm faz parte.' , libcliente = 'true' where id_item = 10431;");
    $this->execute("update configuracoes.db_itensmenu set id_item = 10431 , descricao = 'Cadastro de Órgão Público' , help = 'Cadastro de Órgão Público' , funcao = 'cai4_cgmnatureza001.php' , itemativo = '1' , manutencao = '1' , desctec = 'Vincula natureza com CGM, para podermos saber qual natureza de finalidade esse cgm faz parte.' , libcliente = 'true' where id_item = 10431;");

    $detalhetransmissao = $this->table('empagemovdetalhetransmissao', array('schema' => 'empenho'));
    $detalhetransmissao->addColumn('e74_finalidade', 'string', array('limit' => 3, 'null' => true))->update();

    $this->execute("CREATE SEQUENCE caixa.cgmnatureza_c05_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;");
    $this->execute("CREATE TABLE caixa.cgmnatureza(c05_sequencial int4 NOT NULL default 0,c05_numcgm    int4 NOT NULL default 0,c05_tipo    int4 default 0, CONSTRAINT cgmnatureza_sequ_pk PRIMARY KEY (c05_sequencial));");
    $this->execute("ALTER TABLE caixa.cgmnatureza ADD CONSTRAINT cgmnatureza_numcgm_fk FOREIGN KEY (c05_numcgm) REFERENCES cgm;");
    $this->execute("CREATE  INDEX cgmnatureza_numcgm_in ON caixa.cgmnatureza(c05_numcgm);");
  }

  public function down()
  {

    $this->execute(
   <<<String
   delete from configuracoes.db_syssequencia where codsequencia = 1000674;
   delete from configuracoes.db_syscadind    where codcam in (1009339, 1009348,1009349,1009350);
   delete from configuracoes.db_sysindices   where codarq = 1010209;
   delete from configuracoes.db_sysforkey    where codcam in (1009339, 1009348,1009349,1009350);
   delete from configuracoes.db_sysprikey    where codarq = 1010209;
   delete from configuracoes.db_sysarqcamp   where codcam in (1009339, 1009348,1009349,1009350);
   delete from configuracoes.db_sysarqcamp   where codarq = 1010209;
   delete from configuracoes.db_syscampo     where codcam in (1009339, 1009348,1009349,1009350);
   delete from configuracoes.db_sysarqmod    where codarq = 1010209;
   delete from configuracoes.db_sysarquivo   where codarq = 1010209;
   delete from configuracoes.db_menu         where id_item_filho = 10431 AND modulo = 39;
   delete from configuracoes.db_itensmenu    where id_item = 10431;
String
    );

    $detalhetransmissao = $this->table('empagemovdetalhetransmissao', array('schema' => 'empenho'));
    $detalhetransmissao->removeColumn('e74_finalidade')->update();
    $this->execute("DROP TABLE IF EXISTS cgmnatureza CASCADE;");
    $this->execute("DROP SEQUENCE IF EXISTS cgmnatureza_c05_sequencial_seq;");
  }
}
