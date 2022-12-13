<?php

use Classes\PostgresMigration;

class M7268AguaCalculoTarifa extends PostgresMigration
{

    public function up() {

        $this->execute("insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10381 ,'Cálculo de Tarifas' ,'Cálculo de Tarifas' ,'' ,'1' ,'1' ,'Cálculo de Tarifas' ,'true' );");
        $this->execute("insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 3332 ,10381 ,26 ,4555 );");
        $this->execute("insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10382 ,'Cálculo Parcial' ,'Cálculo Parcial' ,'agu4_calculotarifasparcial.php' ,'1' ,'1' ,'Cálculo Parcial' ,'true' );");
        $this->execute("insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10381 ,10382 ,1 ,4555 );");
        $this->execute("insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10383 ,'Cálculo Geral' ,'Cálculo Geral' ,'agu4_calculotarifasgeral.php' ,'1' ,'1' ,'Cálculo Geral' ,'true' )");
        $this->execute("insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10381 ,10383 ,2 ,4555 )");

        $this->execute("update db_syscampo set nulo = 'true' where codcam = 8492");
        $this->execute("update db_syscampo set nulo = 'true' where codcam = 8495");
        $this->execute("update db_syscampo set nulo = 'true' where codcam = 8496");

        $this->execute("alter table aguacalc alter column x22_codconsumo drop not null");
        $this->execute("alter table aguacalc alter column x22_matric drop not null");
        $this->execute("alter table aguacalc alter column x22_area drop not null");

        $this->execute("alter table aguacalc add column x22_aguacontrato int4");
        $this->execute("alter table aguacalc add constraint aguacalc_aguacontrato_fk foreign key (x22_aguacontrato) references aguacontrato");

        $aSyscampoDados = array(
            'codcam'       => 22252,
            'nomecam'      => 'x22_aguacontrato',
            'conteudo'     => 'int4',
            'descricao'    => 'Contrato',
            'valorinicial' => '',
            'rotulo'       => 'Contrato',
            'tamanho'      => 10,
            'nulo'         => 'true',
            'maiusculo'    => 'false',
            'autocompl'    => 'false',
            'aceitatipo'   => 1,
            'tipoobj'      => 'text',
            'rotulorel'    => 'Contrato',
        );
        $oSyscampo = $this->table('db_syscampo', array('schema' => 'configuracoes'));
        $oSyscampo->insert(
            array_keys($aSyscampoDados),
            array(
                array_values($aSyscampoDados)
            )
        );
        $oSyscampo->saveData();

        $oSysarqcamp = $this->table('db_sysarqcamp', array('schema' => 'configuracoes'));
        $aSysarqcampDados = array(
            'codarq'       => 1443,
            'codcam'       => 22252,
            'seqarq'       => 13,
            'codsequencia' => 0,
        );
        $oSysarqcamp->insert(
            array_keys($aSysarqcampDados),
            array(
                array_values($aSysarqcampDados)
            )
        );
        $oSysarqcamp->saveData();

        $oSysforkey = $this->table('db_sysforkey', array('schema' => 'configuracoes'));
        $aSysforkeyDados = array(
            'codarq'     => 1443,
            'codcam'     => 22252,
            'sequen'     => 1,
            'referen'    => 3966,
            'tipoobjrel' => 0,
        );
        $oSysforkey->insert(
            array_keys($aSysforkeyDados),
            array(
                array_values($aSysforkeyDados)
            )
        );
        $oSysforkey->saveData();
    }

    public function down() {

        $this->execute("delete from db_menu      where id_item_filho in(10383, 10382, 10381)");
        $this->execute("delete from db_itensmenu where id_item       in(10383, 10382, 10381)");

        $this->execute("delete from db_sysforkey  where codcam = 22252");
        $this->execute("delete from db_sysarqcamp where codcam = 22252");
        $this->execute("delete from db_syscampo   where codcam = 22252");

        $this->execute("update db_syscampo set nulo = 'false' where codcam = 8492");
        $this->execute("update db_syscampo set nulo = 'false' where codcam = 8495");
        $this->execute("update db_syscampo set nulo = 'false' where codcam = 8496");

        $this->execute("alter table aguacalc alter column x22_codconsumo set not null");
        $this->execute("alter table aguacalc alter column x22_matric set not null");
        $this->execute("alter table aguacalc alter column x22_area set not null");

        $this->execute("alter table aguacalc drop constraint aguacalc_aguacontrato_fk");
        $this->execute("alter table aguacalc drop column x22_aguacontrato");
    }

}
