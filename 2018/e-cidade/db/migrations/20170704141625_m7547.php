<?php

use Classes\PostgresMigration;

class M7547 extends PostgresMigration
{

public function up(){
    $sql = <<<'SQL'

    insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
     select 10337 ,'Taxas' ,'Taxas' ,'fis2_taxadiversos001.php' ,'1' ,'1' ,'Relatório referente as taxas lançadas.' ,'true'
      where not exists (select 1 from db_itensmenu where id_item = 10337);
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo )
     select 30 ,10337 ,459 ,277
      where not exists(select 1 from db_menu where id_item = 30 and id_item_filho = 10337);
----
--Adicionando NOT NULL no campo dv14_data_calculo da tabela
----
UPDATE db_syscampo SET nulo = false WHERE codcam = 22094;

----
--Rotina de calculo de taxas, alteracao de campo, adicionado obrigatoriedade de preenchimento. NOT NULL
----
--Migrando dados da tabela de ligação entre diversos e lançamentos
CREATE TEMP TABLE w_migracao_datas_calculo_taxas AS 
SELECT 
  dv14_sequencial as sequencial,
  case when dv14_data_calculo is not null
       then dv14_data_calculo
       else dv05_oper
  end as data_calculo
FROM
  diversoslancamentotaxa
INNER JOIN 
  diversos ON dv05_coddiver = dv14_diversos
WHERE
  dv14_data_calculo is null
;
--Alterando datas vazias para data de operacao do diversos
UPDATE 
  diversoslancamentotaxa
SET 
  dv14_data_calculo = w_migracao_datas_calculo_taxas.data_calculo
FROM
  w_migracao_datas_calculo_taxas
WHERE
  dv14_sequencial = w_migracao_datas_calculo_taxas.sequencial
;
--Adiciona NOT NULL na coluna da tabela
ALTER TABLE
  diversoslancamentotaxa
ALTER COLUMN dv14_data_calculo SET NOT NULL;    

SQL;
  
    $this->execute($sql);
  }

public function down(){}

}