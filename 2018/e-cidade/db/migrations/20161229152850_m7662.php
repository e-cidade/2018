<?php

use Classes\PostgresMigration;

class M7662 extends PostgresMigration
{
   public function up()
   {
     $this->execute("update configuracoes.db_syscampo set rotulo = 'Período de Lançamento', rotulorel = 'Período de Lançamento', descricao = 'Informa se a rubrica poderá ser lançada nos pontos por um perí­odo especifico.' where codcam = 22106;");
   }
}