<?php

use Classes\PostgresMigration;

class PensionistasRraM7676 extends PostgresMigration
{
  public function up()
  {

    $this->execute("insert into configuracoes.db_sysarquivo values (4018, 'lancamentorrapensionista', 'Pensionistas do RRA', 'rh201', '2016-12-29', 'Pensionistas do RRA', 0, 'f', 'f', 'f', 'f' );");
    $this->execute("insert into configuracoes.db_sysarqmod values (28,4018);");
    $this->execute("insert into configuracoes.db_syscampo values(22307,'rh201_sequencial','int4','Código Sequencial','0', 'Código Sequencial',10,'f','f','f',1,'text','Código Sequencial');");
    $this->execute("insert into configuracoes.db_syscampo values(22308,'rh201_lancamentorra','int4','Lançamento ','0', 'Lançamento ',10,'f','f','f',1,'text','Lançamento ');");
    $this->execute("insert into configuracoes.db_syscampo values(22309,'rh201_numcgm','int4','Cgm','0', 'Cgm',10,'f','f','f',1,'text','Cgm');");
    $this->execute("insert into configuracoes.db_syscampo values(22310,'rh201_valor','float4','Valor do Pensionista','0', 'Valor do Pensionista',20,'f','f','f',4,'text','Valor do Pensionista');");
    $this->execute("insert into configuracoes.db_sysarqcamp values(4018,22307,1,0);");
    $this->execute("insert into configuracoes.db_sysarqcamp values(4018,22308,2,0);");
    $this->execute("insert into configuracoes.db_sysarqcamp values(4018,22309,3,0);");
    $this->execute("insert into configuracoes.db_sysarqcamp values(4018,22310,4,0);");
    $this->execute("insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(4018,22307,1,22309);");
    $this->execute("insert into configuracoes.db_sysforkey values(4018,22308,1,3891,0);");
    $this->execute("insert into configuracoes.db_sysforkey values(4018,22309,1,42,0);");
    $this->execute("insert into configuracoes.db_sysindices values(4406,'lancamentorrapensionista_lancamentorra_in',4018,'0');");
    $this->execute("insert into configuracoes.db_syscadind values(4406,22308,1);");
    $this->execute("insert into configuracoes.db_sysindices values(4407,'lancamentorrapensionista_cgm_in',4018,'0');");
    $this->execute("insert into configuracoes.db_syscadind values(4407,22309,1);");
    $this->execute("insert into configuracoes.db_syssequencia values(1000644, 'lancamentorrapensionista_rh201_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);");
    $this->execute("update configuracoes.db_sysarqcamp set codsequencia = 1000644 where codarq = 4018 and codcam = 22307;");

    $this->execute("CREATE SEQUENCE pessoal.lancamentorrapensionista_rh201_sequencial_seq");
    $tabela = $this->table('lancamentorrapensionista',  array('schema'=>'pessoal', 'id'=> false, 'primary_key'=>'rh201_sequencial', 'constraint'=>' lancamentorrapensionista_rh201_sequencial_pk'));
    $tabela->addColumn('rh201_sequencial', 'integer')
           ->addColumn('rh201_lancamentorra', 'integer')
           ->addColumn('rh201_numcgm', 'integer')
           ->addColumn("rh201_valor", 'float', array('scale' => 2, 'precision'=> 30))
           ->addForeignKey('rh201_lancamentorra', 'pessoal.lancamentorra', 'rh173_sequencial', array('constraint'=>'lancamentorrapensionista_rh201_lancamentorra_fk'))
           ->addForeignKey('rh201_numcgm', 'protocolo.cgm', 'z01_numcgm',  array('constraint'=>'lancamentorrapensionista_rh201_numcgm'))
           ->addIndex(array('rh201_lancamentorra'), array('name' => 'lancamentorrapensionista_rh201_lancamentorra_in'))
           ->addIndex(array('rh201_numcgm'), array('name' => 'lancamentorrapensionista_cgm_in'))
           ->create();
    $this->execute("ALTER TABLE pessoal.lancamentorrapensionista ALTER COLUMN rh201_sequencial SET DEFAULT nextval('pessoal.lancamentorrapensionista_rh201_sequencial_seq')");
  }
  public function down()
  {

    $this->execute("delete from db_syssequencia where codsequencia = 1000644");
    $this->execute("delete from configuracoes.db_sysarqcamp where codarq = 4018");
    $this->execute("delete from db_sysprikey where codarq = 4018;");
    $this->execute("delete from db_syscadind where codind in(4406, 4407);");
    $this->execute("delete from db_sysindices where codind in(4406, 4407);");
    $this->execute("delete from db_sysforkey where codarq = 4018");
    $this->execute("delete from db_syscampo where codcam in(22307, 22308,22309,22310); ");
    $this->execute("delete from db_sysarqmod where codarq = 4018;");
    $this->execute("delete from db_sysarquivo where codarq = 4018;");
    $this->table('lancamentorrapensionista',  array('schema'=>'pessoal'))->drop();
    $this->execute('drop sequence pessoal.lancamentorrapensionista_rh201_sequencial_seq');

  }
}
