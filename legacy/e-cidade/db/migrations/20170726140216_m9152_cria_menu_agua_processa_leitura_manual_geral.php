<?php

use Classes\PostgresMigration;
/**
 * Cria menu Agua > Procedimentos > Leituras de Hidrometros
 *   > Processar Leituras Manuais Geral
 */
class M9152CriaMenuAguaProcessaLeituraManualGeral extends PostgresMigration
{
    public function up()
    {
      $sSql  = "INSERT INTO db_itensmenu (id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente) VALUES (10432 , 'Processar Médias de Leituras Manuais' , 'Executa o processamento pós Importação dos Coletores nas Leituras Manuais.' , 'agu4_processaleituramanualgeral.php' , '1' , '1' , 'Executa o processamento pós Importação dos Coletores nas Leituras Manuais.' , 'true');";
      $sSql .= "INSERT INTO db_menu (id_item ,id_item_filho ,menusequencia ,modulo) VALUES (4676 , 10432 , 5 , 4555)";

      $this->execute($sSql);


      /* Insere situacoes de leitura já cadastradas manualmente */
      $aguasitleitura = $this->fetchRow('select * from agua.aguasitleitura where x17_codigo = 33');

      if (count($aguasitleitura) == 0) {
        $this->execute("INSERT INTO agua.aguasitleitura (33, 'MEDIA LEITURA', 4)");
      }

      $aguasitleitura = $this->fetchRow('select * from agua.aguasitleitura where x17_codigo = 34');

      if (count($aguasitleitura) == 0) {
        $this->execute("INSERT INTO agua.aguasitleitura (34, 'PENALIDADE', 5)");
      }

    }

    public function down()
    {
      $sSql  = "DELETE FROM db_menu WHERE id_item_filho = 10432 AND modulo = 4555;";
      $sSql .= "DELETE FROM db_itensmenu WHERE id_item = 10432;";

      $this->execute($sSql);
    }
}
