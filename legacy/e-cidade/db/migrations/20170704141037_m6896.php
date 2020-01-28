<?php

use Classes\PostgresMigration;

class M6896 extends PostgresMigration
{

public function up(){
    $sql = <<<'SQL'

    create temporary table w_up_6896_db_syscampo as select * from db_syscampo limit 0;
           insert into w_up_6896_db_syscampo
                 values(22005,'s152_perimetrocefalico','int4','Per�metro Cef�lico','0', 'Per�metro Cef�lico',10,'t','f','f',1,'text','Per�metro Cef�lico'),
                       (22006,'s152_frequenciarespiratoria','int4','Frequ�ncia Respirat�ria','0', 'Frequ�ncia Respirat�ria',10,'t','f','f',1,'text','Frequ�ncia Respirat�ria'),
                       (22007,'s152_frequenciacardiaca','int4','Frequ�ncia Card�aca','0', 'Frequ�ncia Card�aca',10,'t','f','f',1,'text','Frequ�ncia Card�aca'),
                       (22009,'s152_dum','date','DUM','null', 'DUM',10,'t','f','f',1,'text','DUM'),
                       (22010,'s152_saturacao','int4','Satura��o de O2','0', 'Satura��o de O2',10,'t','f','f',1,'text','Satura��o de O2');

insert into db_syscampo
     select *
       from w_up_6896_db_syscampo
      where not exists ( select 1
                           from db_syscampo
                          where db_syscampo.codcam = w_up_6896_db_syscampo.codcam);

create temporary table w_up_6896_db_sysarqcamp as select * from db_sysarqcamp limit 0;
           insert into w_up_6896_db_sysarqcamp
                values(3043,22005,17,0),
                      (3043,22006,18,0),
                      (3043,22007,19,0),
                      (3043,22009,21,0),
                      (3043,22010,22,0);

insert into db_sysarqcamp
     select *
       from w_up_6896_db_sysarqcamp
      where not exists ( select 1
                           from db_sysarqcamp
                          where db_sysarqcamp.codcam = w_up_6896_db_sysarqcamp.codcam);

create temporary table w_up_6896_db_syscampodef as select * from db_syscampodef limit 0;
           insert into w_up_6896_db_syscampodef
                values(17222,'0','N�O ESPECIFICADO'),
                      (17222,'1','JEJUM'),
                      (17222,'2','P�S-PRANDIAL'),
                      (17222,'3','PR�-PRANDIAL');

update db_syscampo set nomecam = 's152_i_alimentacaoexameglicemia', conteudo = 'int4', descricao = 'Alimenta��o do paciente ao realizar o exame de glicemia. 0 - N�o informado; 1 - Em jejum; 2 - P�s-prandial; 3 - Pr�-prandial;', valorinicial = '0', rotulo = 'Momento da Coleta', nulo = 't', tamanho = 1, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Momento da Coleta' where codcam = 17222;

select fc_executa_ddl('alter table sau_triagemavulsa add column s152_perimetrocefalico int4 null');
select fc_executa_ddl('alter table sau_triagemavulsa add column s152_frequenciarespiratoria int4 null');
select fc_executa_ddl('alter table sau_triagemavulsa add column s152_frequenciacardiaca int4 null');
select fc_executa_ddl('alter table sau_triagemavulsa add column s152_dum date null');
select fc_executa_ddl('alter table sau_triagemavulsa add column s152_saturacao int4 null');    

SQL;
  
    $this->execute($sql);
  }

public function down(){}

}
