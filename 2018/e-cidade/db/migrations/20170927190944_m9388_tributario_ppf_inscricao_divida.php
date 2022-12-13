<?php

use Classes\PostgresMigration;

class M9388TributarioPpfInscricaoDivida extends PostgresMigration
{

    public function up()
    {

        $this->execute(
          <<<SQL
insert into configuracoes.db_itensmenu values( 10459, 'Geral', 'Inscrição em Dívida Ativa Geral', 'arr4_importacaodebitoscobrancaadministrativageral001.php?importaDividaAtiva=true', '1', '1', '', '1');
insert into configuracoes.db_itensfilho (id_item, codfilho) values(10459,1);
insert into configuracoes.db_itensmenu values( 10460, 'Parcial', 'Inscrição em Dívida Ativa Parcial', 'arr4_importacaodebitoscobrancaadministrativaparcial001.php?importaDividaAtiva=true', '1', '1', '', '1');
insert into configuracoes.db_itensfilho (id_item, codfilho) values(10460,1);
insert into configuracoes.db_menu values(4884,10459,4,81);
insert into configuracoes.db_menu values(4884,10460,5,81);

insert into configuracoes.db_itensmenu values( 10463, 'Implantação Livro/Folha', 'Implantação do livro/folha de Dívida Ativa', 'div4_processalivrofolha001.php', '1', '1', '', false);
insert into configuracoes.db_itensfilho (id_item, codfilho) values(10463,1);
insert into configuracoes.db_menu values(7865,10463,3,81);

SQL
        );

        $this->execute("update configuracoes.db_itensmenu set libcliente = false where id_item in (10459, 10460);");


        $this->execute("update configuracoes.db_itensmenu set libcliente = false where id_item in (10459, 10460);");
        $row = $this->fetchRow("select db21_codcli from db_config limit 1");

        $this->execute("insert into advog select numcgm, 'OAB99999999999999999' from db_config where prefeitura is true and not exists (select 1 from advog inner join db_config on v57_numcgm = numcgm where prefeitura is true);");

        if ((int)$row['db21_codcli'] === 7107) {
            $this->execute("update configuracoes.db_itensmenu set libcliente = false where id_item in (4446, 3834, 4118, 2549, 7866);");
            $this->execute("update configuracoes.db_itensmenu set libcliente = true where id_item in (10459, 10460, 10463);");
        }
    }

    public function down()
    {

        $this->execute(
          <<<SQLDOWN
delete from configuracoes.db_menu where id_item_filho in (10459, 10460, 10463);
delete from configuracoes.db_itensfilho where id_item in (10459, 10460, 10463);
delete from configuracoes.db_itensmenu where id_item in (10459, 10460, 10463);
update configuracoes.db_itensmenu set libcliente = true where id_item in (4446, 3834, 4118, 2549, 7866);
SQLDOWN
        );
    }
}