<?php

use Classes\PostgresMigration;

class CadastroDepensaoM7676 extends PostgresMigration
{
  public function up()
  {
     $this->table('pensao',  array('schema'=>'pessoal'))
                ->addColumn('r52_relacaodependencia', 'integer', array('null' => true, 'default' => '0'))
                ->save();

     $this->execute("insert into configuracoes.db_syscampo values(22304, 'r52_relacaodependencia', 'int4', 'Rela��o de Depend�ncia para a dirf','0', 'Rela��o de Depend�ncia',10,'t','f','f',1,'text','Rela��o de Depend�ncia')");
     $this->execute("insert into configuracoes.db_syscampodef values(22304, '3', 'C�njuge / Companheiro(a)'),
                                                     (22304, '4', 'Filho (a)'),
                                                     (22304, '6', 'Enteado(a)'),
                                                     (22304, '8', 'Pai/M�e'),
                                                     (22304, '10','Agregado / Outros');"
                   );

    $this->execute("insert into configuracoes.db_sysarqcamp values(570, 22304 ,29, 0);");
  }

  public function down()
  {

    $this->execute("delete from configuracoes.db_sysarqcamp where codcam = 22304");
    $this->execute("delete from configuracoes.db_syscampodef where codcam = 22304");
    $this->execute("delete from configuracoes.db_syscampo where codcam = 22304");
    $this->table('pensao',  array('schema'=>'pessoal'))
      ->removeColumn('r52_relacaodependencia')
      ->save();
  }
}
