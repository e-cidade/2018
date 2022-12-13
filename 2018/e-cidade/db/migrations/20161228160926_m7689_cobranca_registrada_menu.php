<?php

use Classes\PostgresMigration;

class M7689CobrancaRegistradaMenu extends PostgresMigration
{
    public function up()
    {
        $oDbItensMenu = $this->table('db_itensmenu', array('schema'=>'configuracoes'));
        $oDbMenu = $this->table('db_menu', array('schema'=>'configuracoes'));

        $aDbItensMenuColunas = array('id_item', 'descricao', 'help',
                                     'funcao', 'itemativo', 'manutencao',
                                     'desctec', 'libcliente');

        $aDbItensMenuDados = array(
            array(10386, 'Remessas Geradas', 'Remessas Geradas da Cobran�a Registrada',
                  'arr4_cobrancaregistradaexportacao002.php', '1', '1',
                  'Menu para visualiza��o e aquisi��o das remessas geradas da cobran�a registrada.', 'true')
        );

        $oDbItensMenu->insert($aDbItensMenuColunas, $aDbItensMenuDados);
        $oDbItensMenu->saveData();

        $aDbMenuColunas = array('id_item', 'id_item_filho', 'menusequencia', 'modulo');

        $aDbMenuDados = array(array(10324, 10386, 2, 1985522));

        $oDbMenu->insert($aDbMenuColunas, $aDbMenuDados);
        $oDbMenu->saveData();

        $sSql  = "update db_itensmenu                                     ";
        $sSql .= "   set descricao = 'Gera��o de Remessa',                ";
        $sSql .= "       help = 'Gera��o de Remessa Cobran�a Registrada', ";
        $sSql .= "       desctec = 'Gera��o de Remessa dos dados de Recibos emitidos com o conv�nio de Cobran�a Registrada.' ";
        $sSql .= " where id_item = 10325 ";

        $this->execute($sSql);
    }

    public function down()
    {
        $this->execute("delete from db_menu where id_item_filho = 10386 and modulo = 1985522");

        $this->execute("delete from db_itensmenu where id_item = 10386");

        $sSql  = "update db_itensmenu                                ";
        $sSql .= "   set descricao = 'Exporta��o',                   ";
        $sSql .= "       help = 'Exporta��o de Cobran�a Registrada', ";
        $sSql .= "       desctec = 'Exporta��o dos dados de Recibos emitidos com o conv�nio de Cobran�a Registrada.' ";
        $sSql .= " where id_item = 10325 ";

        $this->execute($sSql);
    }
}
