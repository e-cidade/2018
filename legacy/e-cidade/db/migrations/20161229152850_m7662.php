<?php

use Classes\PostgresMigration;

class M7662 extends PostgresMigration
{
   public function up()
   {
     $this->execute("update configuracoes.db_syscampo set rotulo = 'Per�odo de Lan�amento', rotulorel = 'Per�odo de Lan�amento', descricao = 'Informa se a rubrica poder� ser lan�ada nos pontos por um per�odo especifico.' where codcam = 22106;");
   }
}