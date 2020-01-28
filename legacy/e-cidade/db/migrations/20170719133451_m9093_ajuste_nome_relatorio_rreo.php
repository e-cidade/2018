<?php

use Classes\PostgresMigration;

class M9093AjusteNomeRelatorioRreo extends PostgresMigration
{
  public function up() {
    $this->execute("update db_itensmenu set libcliente = false where id_item in (8061, 8700, 8701, 8125) ");
    $this->execute("update db_itensmenu set descricao = 'Anexo II - Dem. Função/Subfunção' where id_item = 9359 ");
    $this->execute("update db_itensmenu set descricao = 'Anexo VI - Dem. Simplificado do RGF - a partir de 2015' where id_item = 10077 ");
    $this->execute("update db_itensmenu set descricao = 'Anexo V - Dem. da Disp. de Caixa e dos RP - 2015' where id_item = 10187 ");


  }

  public function down() {
    $this->execute("update db_itensmenu set libcliente = true where id_item in (8061, 8700, 8701, 8125) ");
    $this->execute("update db_itensmenu set descricao = 'Anexo II - Dem. Função/Subfunção (Novo)' where id_item = 9359 ");
    $this->execute("update db_itensmenu set descricao = 'Anexo 6 - Dem. Simplificado do RGF - a partir de 2015' where id_item = 10077 ");
    $this->execute("update db_itensmenu set descricao = 'Anexo 5 - Dem. da Disp. de Caixa e dos RP - 2015' where id_item = 10187 ");

  }
}
