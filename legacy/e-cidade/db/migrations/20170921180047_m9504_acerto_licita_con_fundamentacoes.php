<?php

use Classes\PostgresMigration;

class M9504AcertoLicitaConFundamentacoes extends PostgresMigration
{

  public function down() {}

  public function up()
  {

    $buscaFundamentacao = $this->fetchRow("select db109_sequencial from db_cadattdinamicoatributos where db109_nome = 'codigofundamentacao'");

    $codigoFundamentacao = $buscaFundamentacao['db109_sequencial'];

    $this->execute("delete from db_cadattdinamicoatributosopcoes where db18_cadattdinamicoatributos = {$codigoFundamentacao}");

    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('LF13019  '), trim('    Lei Federal no 13.019/14'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('OUTC     '), trim('  Outra'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A24I     '), trim('  Art. 24, inc. I, da Lei no 8.666/93'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A24II    '), trim('  Art. 24, inc. II, da Lei no 8.666/93'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A24IV    '), trim('  Art. 24, inc. IV, da Lei no 8.666/93'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A24V     '), trim('  Art. 24, inc. V, da Lei no 8.666/93'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A24VII   '), trim('    Art. 24, inc. VII, da Lei no 8.666/93'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A24VIII  '), trim('    Art. 24, inc. VIII, da Lei no 8.666/93'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A24X     '), trim('  Art. 24, inc. X, da Lei no 8.666/93'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A24XI    '), trim('  Art. 24, inc. XI, da Lei no 8.666/93'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A24XII   '), trim('    Art. 24, inc. XII, da Lei no 8.666/93'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A24XIII  '), trim('    Art. 24, inc. XIII, da Lei no 8.666/93'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A24XVI   '), trim('    Art. 24, inc. XVI, da Lei no 8.666/93'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A24XX    '), trim('  Art. 24, inc. XX, da Lei no 8.666/93'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A24XXII  '), trim('    Art. 24, inc. XXII, da Lei no 8.666/93'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A28I     '), trim('  Art. 28, § 3o, inc. I, da Lei no 13.303/2016'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A28II    '), trim('  Art. 28, § 3o, inc. II, da Lei no 13.303/2016'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A29I     '), trim('  Art. 29, inc. I, da Lei no 13.303/2016'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A29II    '), trim('  Art. 29, inc. II, da Lei no 13.303/2016'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A29III   '), trim('    Art. 29, inc. III, da Lei no 13.303/2016'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A29IV    '), trim('  Art. 29, inc. IV, da Lei no 13.303/2016'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A29IX    '), trim('  Art. 29, inc. IX, da Lei no 13.303/2016'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A29V     '), trim('  Art. 29, inc. V, da Lei no 13.303/2016'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A29VI    '), trim('  Art. 29, inc. VI, da Lei no 13.303/2016'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A29VII   '), trim('    Art. 29, inc. VII, da Lei no 13.303/2016'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A29VIII  '), trim('    Art. 29, inc. VIII, da Lei no 13.303/2016'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A29X     '), trim('  Art. 29, inc. X, da Lei no 13.303/2016'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A29XI    '), trim('  Art. 29, inc. XI, da Lei no 13.303/2016'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A29XII   '), trim('    Art. 29, inc. XII, da Lei no 13.303/2016'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A29XIII  '), trim('    Art. 29, inc. XIII, da Lei no 13.303/2016'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A29XIV   '), trim('    Art. 29, inc. XIV, da Lei no 13.303/2016'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A29XV    '), trim('  Art. 29, inc.XV, da Lei no 13.303/2016'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A29XVI   '), trim('    Art. 29, inc. XVI, da Lei no 13.303/2016'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A29XVII  '), trim('    Art. 29, inc. XVII, da Lei no 13.303/2016'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A29XVIII '), trim('      Art. 29, inc. XVIII, da Lei no 13.303/2016'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A30      '), trim('Art.30 da Lei no 13.019/14'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('OUTD     '), trim('  Outra'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A25CAPT  '), trim('    Art. 25, \"caput\", da Lei no 8.666/93'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A25I     '), trim('  Art. 25, inc. I, da Lei no 8.666/93'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A25II    '), trim('  Art. 25, inc. II, da Lei no 8.666/93'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A25III   '), trim('    Art. 25, inc. III, da Lei no 8.666/93'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A30I     '), trim('  Art. 30, inc. I da Lei no 13.303/2016'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A30IIA   '), trim('    Art. 30, inc. II, alínea \"a\" da Lei no 13.303/2016'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A30IIB   '), trim('    Art. 30, inc. II, alínea \"b\" da Lei no 13.303/2016'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A30IIC   '), trim('    Art. 30, inc. II, alínea \"c\" da Lei no 13.303/2016'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A30IID   '), trim('    Art. 30, inc. II, alínea \"d\" da Lei no 13.303/2016'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A30IIE   '), trim('    Art. 30, inc. II, alínea \"e\" da Lei no 13.303/2016'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A30IIF   '), trim('    Art. 30, inc. II, alínea \"f\" da Lei no 13.303/2016'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A30IIG   '), trim('    Art. 30, inc. II, alínea \"g\" da Lei no 13.303/2016'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('A31      '), trim('Art.31 da Lei no 13.019/14'))");
    $this->execute("insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), {$codigoFundamentacao}, trim('OUTI     '), trim('  Outra'))");

    $this->execute("
      update db_cadattdinamicoatributosopcoes set db18_opcao = trim(db18_opcao), db18_valor = trim(db18_valor) where db18_cadattdinamicoatributos = {$codigoFundamentacao}
    ");
  }

}
