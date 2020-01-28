<?php

use Classes\PostgresMigration;

class M8268MenuAguaConfiguracoesExercicio extends PostgresMigration
{

    public function up()
    {
        $this->execute("insert into db_itensmenu (id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente) values (10422, 'Configurações do Exercício', 'Configurações do Exercício', 'agu4_configuracoesexercicio.php', '1', '1', 'Configurações do Exercício', 'true')");
        $this->execute("insert into db_menu (id_item, id_item_filho, menusequencia, modulo) values (4615, 10422, 7, 4555)");
    }

    public function down()
    {
        $this->execute("delete from db_menu where id_item_filho = 10422");
        $this->execute("delete from db_itensmenu where id_item = 10422");
    }
}
