<?php

use Classes\PostgresMigration;

class M6074 extends PostgresMigration {

  public function up() {


    $this->execute(<<<SQL

      insert into pctipocompratribunal (select 1000, l44_codigotribunal, l44_descricao, l44_uf, l44_sigla from pctipocompratribunal where l44_sequencial = 54);
      update cflicita     set l03_pctipocompratribunal  = 1000 where l03_pctipocompratribunal  = 54;
      update pctipocompra set pc50_pctipocompratribunal = 1000 where pc50_pctipocompratribunal = 54;
SQL
);

    $this->atualizaMenu();
    $this->paragrafos();
  }

  public function down() {


    $this->reverteMenu();
    $this->execute(<<<SQL

      update cflicita     set l03_pctipocompratribunal  = 54 where l03_pctipocompratribunal  = 1000;
      update pctipocompra set pc50_pctipocompratribunal = 54 where pc50_pctipocompratribunal = 1000;
      delete from pctipocompratribunal where l44_sequencial = 1000;
SQL
);
    $this->paragrafos(false);
  }

  private function atualizaMenu() {

    $this->execute("
      insert into configuracoes.db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10406 ,'Credenciamento de Fornecedores da Licitação' ,'Credenciamento de Fornecedores da Licitação' ,'lic4_credenciamentofornecedores001.php' ,'1' ,'1' ,'Credenciamento de Fornecedores da Licitação: Chamamento Público/Credenciamento' ,'false' );
      delete from configuracoes.db_menu where id_item_filho = 10406 AND modulo = 381;
      insert into configuracoes.db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 1818 ,10406 ,116 ,381 );
      update configuracoes.db_menu set menusequencia = 1 where id_item = 1818 and modulo = 381 and id_item_filho = 4689;
      update configuracoes.db_menu set menusequencia = 2 where id_item = 1818 and modulo = 381 and id_item_filho = 5478;
      update configuracoes.db_menu set menusequencia = 3 where id_item = 1818 and modulo = 381 and id_item_filho = 4680;
      update configuracoes.db_menu set menusequencia = 4 where id_item = 1818 and modulo = 381 and id_item_filho = 4685;
      update configuracoes.db_menu set menusequencia = 5 where id_item = 1818 and modulo = 381 and id_item_filho = 10406;
      update configuracoes.db_menu set menusequencia = 6 where id_item = 1818 and modulo = 381 and id_item_filho = 10215;
      update configuracoes.db_menu set menusequencia = 7 where id_item = 1818 and modulo = 381 and id_item_filho = 4686;
      update configuracoes.db_menu set menusequencia = 8 where id_item = 1818 and modulo = 381 and id_item_filho = 10206;
      update configuracoes.db_menu set menusequencia = 9 where id_item = 1818 and modulo = 381 and id_item_filho = 10209;
      update configuracoes.db_menu set menusequencia = 10 where id_item = 1818 and modulo = 381 and id_item_filho = 9401;
      update configuracoes.db_menu set menusequencia = 11 where id_item = 1818 and modulo = 381 and id_item_filho = 147886;
      update configuracoes.db_menu set menusequencia = 12 where id_item = 1818 and modulo = 381 and id_item_filho = 4718;
      update configuracoes.db_menu set menusequencia = 13 where id_item = 1818 and modulo = 381 and id_item_filho = 4719;
      update configuracoes.db_menu set menusequencia = 14 where id_item = 1818 and modulo = 381 and id_item_filho = 8983;
      update configuracoes.db_menu set menusequencia = 15 where id_item = 1818 and modulo = 381 and id_item_filho = 4750;
      update configuracoes.db_menu set menusequencia = 16 where id_item = 1818 and modulo = 381 and id_item_filho = 5624;
      update configuracoes.db_menu set menusequencia = 17 where id_item = 1818 and modulo = 381 and id_item_filho = 6813;
      update configuracoes.db_menu set menusequencia = 18 where id_item = 1818 and modulo = 381 and id_item_filho = 7985;
      update configuracoes.db_menu set menusequencia = 19 where id_item = 1818 and modulo = 381 and id_item_filho = 8056;
      update configuracoes.db_menu set menusequencia = 20 where id_item = 1818 and modulo = 381 and id_item_filho = 8131;
      update configuracoes.db_menu set menusequencia = 21 where id_item = 1818 and modulo = 381 and id_item_filho = 8602;
      update configuracoes.db_menu set menusequencia = 22 where id_item = 1818 and modulo = 381 and id_item_filho = 10204;"
    );
  }

  private function reverteMenu() {

    $this->execute("
      delete from configuracoes.db_menu where id_item_filho = 10406;
      delete from configuracoes.db_itensmenu where id_item = 10406;
      update configuracoes.db_menu set menusequencia = 1 where id_item = 1818 and modulo = 381 and id_item_filho = 4689;
      update configuracoes.db_menu set menusequencia = 2 where id_item = 1818 and modulo = 381 and id_item_filho = 5478;
      update configuracoes.db_menu set menusequencia = 3 where id_item = 1818 and modulo = 381 and id_item_filho = 4680;
      update configuracoes.db_menu set menusequencia = 4 where id_item = 1818 and modulo = 381 and id_item_filho = 4685;
      update configuracoes.db_menu set menusequencia = 5 where id_item = 1818 and modulo = 381 and id_item_filho = 10215;
      update configuracoes.db_menu set menusequencia = 6 where id_item = 1818 and modulo = 381 and id_item_filho = 4686;
      update configuracoes.db_menu set menusequencia = 7 where id_item = 1818 and modulo = 381 and id_item_filho = 10206;
      update configuracoes.db_menu set menusequencia = 8 where id_item = 1818 and modulo = 381 and id_item_filho = 10209;
      update configuracoes.db_menu set menusequencia = 9 where id_item = 1818 and modulo = 381 and id_item_filho = 9401;
      update configuracoes.db_menu set menusequencia = 10 where id_item = 1818 and modulo = 381 and id_item_filho = 147886;
      update configuracoes.db_menu set menusequencia = 11 where id_item = 1818 and modulo = 381 and id_item_filho = 4718;
      update configuracoes.db_menu set menusequencia = 12 where id_item = 1818 and modulo = 381 and id_item_filho = 4719;
      update configuracoes.db_menu set menusequencia = 13 where id_item = 1818 and modulo = 381 and id_item_filho = 8983;
      update configuracoes.db_menu set menusequencia = 14 where id_item = 1818 and modulo = 381 and id_item_filho = 4750;
      update configuracoes.db_menu set menusequencia = 15 where id_item = 1818 and modulo = 381 and id_item_filho = 5624;
      update configuracoes.db_menu set menusequencia = 16 where id_item = 1818 and modulo = 381 and id_item_filho = 6813;
      update configuracoes.db_menu set menusequencia = 17 where id_item = 1818 and modulo = 381 and id_item_filho = 7985;
      update configuracoes.db_menu set menusequencia = 18 where id_item = 1818 and modulo = 381 and id_item_filho = 8056;
      update configuracoes.db_menu set menusequencia = 19 where id_item = 1818 and modulo = 381 and id_item_filho = 8131;
      update configuracoes.db_menu set menusequencia = 20 where id_item = 1818 and modulo = 381 and id_item_filho = 8602;
      update configuracoes.db_menu set menusequencia = 21 where id_item = 1818 and modulo = 381 and id_item_filho = 10204;
    ");
  }

  private function paragrafos($lUp = true) {

    $sTexto = '$oPDF->ln(); $result_dot=$clliclicitem->sql_record($clliclicitem->sql_query_inf(null,"distinct liclicitem.*,pc01_descrmater,pc11_resum","l21_ordem","l21_codliclicita=$l20_codigo")); if ($clliclicitem->numrows>0){ for($w=0;$w<$clliclicitem->numrows;$w++){ db_fieldsmemory($result_dot,$w); $oPDF->setfont("arial","b",8); if ($cor == 0) { $cor = 1; } else { $cor = 0; } $oPDF->cell(10,$alt,"",0,0,"C",$cor); $oPDF->multicell(0,$alt,"Item ".$l21_ordem." *- " . $pc01_descrmater . " - " . $pc11_resum,0,"L",$cor); $result_itemlic=$clpcorcamitemlic->sql_record($clpcorcamitemlic->sql_query_file(null," * ",null," pc26_liclicitem=$l21_codigo")); $a = $clpcorcamitemlic->sql_query_file(null," * ",null," pc26_liclicitem=$l21_codigo"); if ($clpcorcamitemlic->numrows > 0) { db_fieldsmemory($result_itemlic,0); $result_julg=$clpcorcamjulg->sql_record($clpcorcamjulg->sql_query(null,null," z01_numcgm, z01_nome",null," pc24_orcamitem = $pc26_orcamitem and pc24_pontuacao = 1")); $iLinhas = $clpcorcamjulg->numrows; if ( $clpcorcamjulg->numrows > 0) { for ($i = 0; $i < $iLinhas; $i++ ) { db_fieldsmemory($result_julg, $i); $oPDF->cell(20,$alt,"",0,0,"C",$cor); $oPDF->multicell(0,$alt,"$z01_numcgm - $z01_nome",0,"L",$cor); } } } } } $oPDF->ln(); ';
    if ( !$lUp ) {
      $sTexto = '$oPDF->ln(); $result_dot=$clliclicitem->sql_record($clliclicitem->sql_query_inf(null,"distinct liclicitem.*,pc01_descrmater,pc11_resum","l21_ordem","l21_codliclicita=$l20_codigo")); if ($clliclicitem->numrows>0){ for($w=0;$w<$clliclicitem->numrows;$w++){ db_fieldsmemory($result_dot,$w); $oPDF->setfont("arial","b",8); if ($cor == 0) { $cor = 1; } else { $cor = 0; } $oPDF->cell(10,$alt,"",0,0,"C",$cor); $oPDF->multicell(0,$alt,"Item ".$l21_ordem." *- " . $pc01_descrmater . " - " . $pc11_resum,0,"L",$cor); $result_itemlic=$clpcorcamitemlic->sql_record($clpcorcamitemlic->sql_query_file(null," * ",null," pc26_liclicitem=$l21_codigo")); if ($clpcorcamitemlic->numrows > 0) { db_fieldsmemory($result_itemlic,0); $result_julg=$clpcorcamjulg->sql_record($clpcorcamjulg->sql_query(null,null," z01_numcgm, z01_nome",null," pc24_orcamitem = $pc26_orcamitem and pc24_pontuacao = 1")); if ($clpcorcamjulg->numrows > 0) { db_fieldsmemory($result_julg,0); $oPDF->cell(20,$alt,"",0,0,"C",$cor); $oPDF->multicell(0,$alt,"$z01_numcgm - $z01_nome",0,"L",$cor); } } } } $oPDF->ln();';
    }
    $sSql  = " UPDATE configuracoes.db_paragrafopadrao                    ";
    $sSql .= "    SET  db61_codparag    = 354               ";
    $sSql .= "        ,db61_descr       = 'ITENS HOMOLOGA'  ";
    $sSql .= "        ,db61_texto       = '". $sTexto . "'  ";
    $sSql .= "        ,db61_alinha      = 0                 ";
    $sSql .= "        ,db61_inicia      = 0                 ";
    $sSql .= "        ,db61_espaco      = 1                 ";
    $sSql .= "        ,db61_alinhamento = 'J'               ";
    $sSql .= "        ,db61_altura      = 4                 ";
    $sSql .= "        ,db61_largura     = 0                 ";
    $sSql .= "        ,db61_tipo        = 3                 ";
    $sSql .= "    WHERE db61_codparag = 354                 ";
    $this->execute($sSql);
  }

}
