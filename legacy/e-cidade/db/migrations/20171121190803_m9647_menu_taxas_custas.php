<?php

use Classes\PostgresMigration;

class M9647MenuTaxasCustas extends PostgresMigration
{
    public function up()
    {
        $this->execute("insert into db_itensmenu (id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente) values (10468, 'Inicial do Foro', 'Inicial do Foro', 'jur4_manutencaotaxacusta001.php', '1', '1', 'Manutenção de taxas/custas para iniciais do foro.', 'true')");
        $this->execute("insert into db_itensmenu (id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente) values (10469, 'Manutenção de Taxas/Custas', 'Manutenção de Taxas/Custas', '', '1', '1', 'Manutenção de Taxas/Custas', 'true')");
        $this->execute("insert into db_menu (id_item, id_item_filho, menusequencia, modulo) values (1818, 10469, 116, 313)");
        $this->execute("insert into db_menu (id_item, id_item_filho, menusequencia, modulo) values (10469, 10468, 1, 313)");
        
        $this->execute("update db_itensmenu set descricao = 'Taxas / Custas' where id_item = 8883");
    }

    public function down()
    {
        $this->execute("delete from db_itensmenu where id_item = 10468");
        $this->execute("delete from db_itensmenu where id_item = 10469");
        $this->execute("delete from db_menu where id_item_filho = 10469 and modulo = 313");
        $this->execute("delete from db_menu where id_item_filho = 10468 and modulo = 313");

        $this->execute("update db_itensmenu set descricao = 'Tarifas' where id_item = 8883");
    }
}
