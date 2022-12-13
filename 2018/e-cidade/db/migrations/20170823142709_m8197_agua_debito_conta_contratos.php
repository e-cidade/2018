<?php

use Classes\PostgresMigration;

class M8197AguaDebitoContaContratos extends PostgresMigration
{
  public function up()
  {
    $this->upDicionarioDados();
    $this->upDDL();
  }

  public function down()
  {
    $this->downDDL();
    $this->downDicionarioDados();
  }

  public function upDicionarioDados()
  {
    $this->execute("
      INSERT INTO db_sysarquivo   VALUES (1010220, 'debcontapedidoaguacontrato', 'Tabela para cadastrar débito em conta para contrato de água', 'd81', '2017-08-23', 'Cadastro de débito em conta para contrato de água', 0, 'f', 'f', 'f', 'f' );
      INSERT INTO db_sysarqmod    VALUES (5,1010220);
      INSERT INTO db_sysarquivo   VALUES (1010221, 'debcontapedidoaguacontratoeconomia', 'Tabela para cadastrar débito em conta para uma economia de um contrato de água', 'd82', '2017-08-23', 'Débito em conta para economia de contrato de água', 0, 'f', 'f', 'f', 'f' );
      INSERT INTO db_sysarqmod    VALUES (5,1010221);
      INSERT INTO db_syscampo     VALUES (1009404,'d81_codigo','int4','Código do pedido','0', 'Código',19,'f','f','f',1,'text','Código');
      INSERT INTO db_syscampo     VALUES (1009405,'d81_contrato','int4','Código do contrato de água','0', 'Contrato',19,'f','f','f',1,'text','Contrato');
      INSERT INTO db_syscampo     VALUES (1009406,'d82_codigo','int4','Código do pedido','0', 'Código',19,'f','f','f',1,'text','Código');
      INSERT INTO db_syscampo     VALUES (1009407,'d82_economia','int4','Economia de um contrato de água.','0', 'Economia',19,'f','f','f',1,'text','Economia');
      INSERT INTO db_sysarqcamp   VALUES (1010220,1009404,1,0);
      INSERT INTO db_sysarqcamp   VALUES (1010220,1009405,2,0);

      INSERT INTO db_sysforkey    VALUES (1010220,1009405,1,3966,0);
      INSERT INTO db_sysindices   VALUES (1008219,'debcontapedidoaguacontrato_contrato_in',1010220,'0');
      INSERT INTO db_syscadind    VALUES (1008219,1009405,1);
      INSERT INTO db_sysarqcamp   VALUES (1010221,1009406,1,0);
      INSERT INTO db_sysarqcamp   VALUES (1010221,1009407,2,0);

      INSERT INTO db_sysforkey    VALUES (1010221,1009407,1,3983,0);
      INSERT INTO db_sysindices   VALUES (1008220,'debcontapedidoaguacontratoeconomia_economia_in',1010221,'0');
      INSERT INTO db_syscadind    VALUES (1008220,1009407,1);

      INSERT INTO db_sysforkey values(1010220, 1009404, 1, 1330, 0);
      INSERT INTO db_sysforkey values(1010221, 1009406, 1, 1330, 0);

      INSERT INTO db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 10439 ,'Contrato de Água' ,'Cadastra o débito em conta para um contrato de água' ,'cai4_debcontapedidoaguacontratoeconomia.php' ,'1' ,'1' ,'Cadastra o débito em conta para um contrato de água' ,'true' );
      INSERT INTO db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 5057 ,10439 ,7 ,1985522 );

      INSERT INTO db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10441 ,'Contrato de Água' ,'Cadastra o débito em conta para um contrato de água' ,'cai4_debcontapedidoaguacontratoeconomia.php?lAlteracao=true' ,'1' ,'1' ,'Cadastra o débito em conta para um contrato de água' ,'true' );
      INSERT INTO db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 5061 ,10441 ,5 ,1985522 );

      INSERT INTO db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 10440 ,'Geração Arquivo Banco (Água)' ,'Aquivo para débito em conta de contratos de água' ,'cai2_geradebcontacontratoeconomia.php' ,'1' ,'1' ,'Gera um arquivo bancário para débito em conta de contratos de água' ,'false' );
      INSERT INTO db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 4890 ,10440 ,4 ,39 );
    ");
  }

  public function upDDL()
  {
    $this->execute("CREATE SEQUENCE caixa.debcontapedidoaguacontrato_codigo_seq");
    $this->table('debcontapedidoaguacontrato',          array('schema'=>'caixa', 'id'=>false))
      ->addColumn('d81_codigo',   'integer')
      ->addColumn('d81_contrato',   'integer')
      ->addForeignKey('d81_contrato',  'agua.aguacontrato',  'x54_sequencial', array('constraint'=>'debcontapedidoaguacontrato_contrato_fk'))
      ->addIndex('d81_contrato',  array('unique'=>false, 'name'=>'debcontapedidoaguacontrato_contrato_in'))
      ->addForeignKey('d81_codigo',  'caixa.debcontapedido',  'd63_codigo', array('constraint'=>'debcontapedidoaguacontrato_codigo_fk'))
      ->save();
    $this->execute("ALTER TABLE caixa.debcontapedidoaguacontrato ALTER COLUMN d81_codigo SET DEFAULT nextval('caixa.debcontapedidoaguacontrato_codigo_seq')");

    $this->execute("CREATE SEQUENCE caixa.debcontapedidoaguacontratoeconomia_codigo_seq");
    $this->table('debcontapedidoaguacontratoeconomia',  array('schema'=>'caixa', 'id'=>false))
      ->addColumn('d82_codigo',   'integer')
      ->addColumn('d82_economia',   'integer')
      ->addForeignKey('d82_economia',  'agua.aguacontratoeconomia',  'x38_sequencial', array('constraint'=>'debcontapedidoaguacontratoeconomia_economia_fk'))
      ->addForeignKey('d82_codigo',  'caixa.debcontapedido',  'd63_codigo', array('constraint'=>'debcontapedidoaguacontratoeconomia_codigo_fk'))
      ->addIndex('d82_economia',  array('unique'=>false, 'name'=>'debcontapedidoaguacontratoeconomia_economia_in'))
      ->save();
    $this->execute("ALTER TABLE caixa.debcontapedidoaguacontratoeconomia ALTER COLUMN d82_codigo SET DEFAULT nextval('caixa.debcontapedidoaguacontratoeconomia_codigo_seq')");
  }

  public function downDDL()
  {
    $this->table('debcontapedidoaguacontratoeconomia', array('schema'=>'caixa'))->drop();
    $this->execute("DROP SEQUENCE IF EXISTS caixa.debcontapedidoaguacontratoeconomia_codigo_seq");

    $this->table('debcontapedidoaguacontrato', array('schema'=>'caixa'))->drop();
    $this->execute("DROP SEQUENCE IF EXISTS caixa.debcontapedidoaguacontrato_codigo_seq");
  }

  public function downDicionarioDados()
  {
    $this->execute("
      DELETE FROM db_syscadind    WHERE codind IN (1008219,1008220);
      DELETE FROM db_sysindices   WHERE codind IN (1008219,1008220);
      DELETE FROM db_sysforkey    WHERE codcam IN (1009404, 1009405, 1009406, 1009407);
      DELETE FROM db_sysarqcamp   WHERE codcam IN (1009404, 1009405, 1009406, 1009407);
      DELETE FROM db_syscampo     WHERE codcam IN (1009404, 1009405, 1009406, 1009407);
      DELETE FROM db_sysarqmod    WHERE codarq IN (1010221, 1010220);
      DELETE FROM db_sysarquivo   WHERE codarq IN (1010221, 1010220);

      DELETE FROM db_menu      WHERE id_item_filho IN (10439, 10440, 10441);
      DELETE FROM db_itensmenu WHERE id_item IN (10439, 10440, 10441);
    ");
  }
}
