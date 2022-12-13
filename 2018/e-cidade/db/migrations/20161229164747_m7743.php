<?php

use Classes\PostgresMigration;

class M7743 extends PostgresMigration
{

   public function up() 
   {

     $this->table('eventofinanceiroautomatico', array('schema'=>'pessoal'))
          ->addIndex(array('rh181_rubrica','rh181_mes','rh181_selecao','rh181_instituicao'), array('unique' => true, 'name' => 'eventofinanceiroautomatico_rubrica_mes_selecao_instituicao_un'))
          ->save();
   }

   public function down() 
   {
     $this->table('eventofinanceiroautomatico', array('schema'=>'pessoal'))
          ->removeIndexByName('eventofinanceiroautomatico_rubrica_mes_selecao_instituicao_un')
          ->save();
   }
}
