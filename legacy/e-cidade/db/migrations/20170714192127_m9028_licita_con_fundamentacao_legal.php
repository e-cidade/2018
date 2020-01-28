<?php

use Classes\PostgresMigration;

class M9028LicitaConFundamentacaoLegal extends PostgresMigration
{

  public function up()
  {
    $this->excluirAtributos();
    $this->execute(
      <<<STRING

insert into configuracoes.db_cadattdinamicoatributosopcoes
     values (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'LF13019',	'Lei Federal no 13.019/14'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'OUTC',	    'Outra'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A24I',	    'Art. 24, inc. I, da Lei no 8.666/93'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A24II',	  'Art. 24, inc. II, da Lei no 8.666/93'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A24IV',	  'Art. 24, inc. IV, da Lei no 8.666/93'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A24V',	    'Art. 24, inc. V, da Lei no 8.666/93'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A24VII',	  'Art. 24, inc. VII, da Lei no 8.666/93'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A24VIII',	'Art. 24, inc. VIII, da Lei no 8.666/93'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A24X',	    'Art. 24, inc. X, da Lei no 8.666/93'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A24XI',	  'Art. 24, inc. XI, da Lei no 8.666/93'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A24XII',	  'Art. 24, inc. XII, da Lei no 8.666/93'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A24XIII',	'Art. 24, inc. XIII, da Lei no 8.666/93'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A24XVI', 	'Art. 24, inc. XVI, da Lei no 8.666/93'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A24XX',  	'Art. 24, inc. XX, da Lei no 8.666/93'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A24XXII',	'Art. 24, inc. XXII, da Lei no 8.666/93'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A28I',	    'Art. 28, § 3o, inc. I, da Lei no 13.303/2016'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A28II',	  'Art. 28, § 3o, inc. II, da Lei no 13.303/2016'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A29I',	    'Art. 29, inc. I, da Lei no 13.303/2016'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A29II',	  'Art. 29, inc. II, da Lei no 13.303/2016'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A29III',	  'Art. 29, inc. III, da Lei no 13.303/2016'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A29IV',	  'Art. 29, inc. IV, da Lei no 13.303/2016'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A29IX',	  'Art. 29, inc. IX, da Lei no 13.303/2016'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A29V',	    'Art. 29, inc. V, da Lei no 13.303/2016'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A29VI',	  'Art. 29, inc. VI, da Lei no 13.303/2016'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A29VII',	  'Art. 29, inc. VII, da Lei no 13.303/2016'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A29VIII',	'Art. 29, inc. VIII, da Lei no 13.303/2016'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A29X',	    'Art. 29, inc. X, da Lei no 13.303/2016'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A29XI',   	'Art. 29, inc. XI, da Lei no 13.303/2016'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A29XII', 	'Art. 29, inc. XII, da Lei no 13.303/2016'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A29XIII',	'Art. 29, inc. XIII, da Lei no 13.303/2016'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A29XIV',  	'Art. 29, inc. XIV, da Lei no 13.303/2016'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A29XV',    'Art. 29, inc.XV, da Lei no 13.303/2016'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A29XVI', 	'Art. 29, inc. XVI, da Lei no 13.303/2016'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A29XVII',	'Art. 29, inc. XVII, da Lei no 13.303/2016'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A29XVIII',	'Art. 29, inc. XVIII, da Lei no 13.303/2016'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A30',	    'Art.30 da Lei no 13.019/14'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'OUTD',	    'Outra'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A25CAPT',	'Art. 25, "caput", da Lei no 8.666/93'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A25I',	    'Art. 25, inc. I, da Lei no 8.666/93'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A25II',  	'Art. 25, inc. II, da Lei no 8.666/93'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A25III',  	'Art. 25, inc. III, da Lei no 8.666/93'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A30I',	    'Art. 30, inc. I da Lei no 13.303/2016'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A30IIA',	  'Art. 30, inc. II, alínea "a" da Lei no 13.303/2016'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A30IIB',	  'Art. 30, inc. II, alínea "b" da Lei no 13.303/2016'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A30IIC',	  'Art. 30, inc. II, alínea "c" da Lei no 13.303/2016'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A30IID',  	'Art. 30, inc. II, alínea "d" da Lei no 13.303/2016'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A30IIE',	  'Art. 30, inc. II, alínea "e" da Lei no 13.303/2016'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A30IIF',	  'Art. 30, inc. II, alínea "f" da Lei no 13.303/2016'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A30IIG',	  'Art. 30, inc. II, alínea "g" da Lei no 13.303/2016'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A31',	    'Art.31 da Lei no 13.019/14'),
            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'OUTI',	    'Outra');
STRING

    );

  }


  public function down()
  {
    $this->excluirAtributos();
    $this->execute(
      <<<STRING
 insert into configuracoes.db_cadattdinamicoatributosopcoes
      values (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, trim('A24IV  ') , 'Art. 24, inc. IV, da Lei no 8.666/93'),
             (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, trim('A24V   ') , 'Art. 24, inc. V, da Lei no 8.666/93'),
             (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, trim('A24VII ') , 'Art. 24, inc. VII, da Lei no 8.666/93'),
             (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, trim('A24VIII') , 'Art. 24, inc. VIII, da Lei no 8.666/93'),
             (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, trim('A24X   ') , 'Art. 24, inc. X, da Lei no 8.666/93'),
             (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, trim('A24XI  ') , 'Art. 24, inc. XI, da Lei no 8.666/93'),
             (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, trim('A24XII ') , 'Art. 24, inc. XII, da Lei no 8.666/93'),
             (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, trim('A24XIII') , 'Art. 24, inc. XIII, da Lei no 8.666/93'),
             (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, trim('A24XVI ') , 'Art. 24, inc. XVI, da Lei no 8.666/93'),
             (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, trim('A24XX  ') , 'Art. 24, inc. XX, da Lei no 8.666/93'),
             (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, trim('A24XXII') , 'Art. 24, inc. XXII, da Lei no 8.666/93'),
             (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, trim('A25CAPT') , 'Art. 25, "caput", da Lei no 8.666/93'),
             (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, trim('A25I   ') , 'Art. 25, "inc. I", da Lei no 8.666/93'),
             (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, trim('A25II  ') , 'Art. 25, "inc. II", da Lei no 8.666/93'),
             (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, trim('A25III ') , 'Art. 25, "inc. III", da Lei no 8.666/93'),
             (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, trim('OUTD   ') , 'Outra(Processo de Dispensa)'),
             (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, trim('OUTI   ') , 'Outra(Processo de Inexigibilidade)'),
             (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, trim('A24I   ') , 'Art. 24, inc. I, da Lei no 8.666/93'),
             (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, trim('A24II  ') , 'Art. 24, inc. II, da Lei no 8.666/93');

STRING
    );
  }

  /**
   * Exclui os atributos
   */
  private function excluirAtributos()
  {
    $this->execute('delete from configuracoes.db_cadattdinamicoatributosopcoes where db18_cadattdinamicoatributos = 18;');
  }
}
