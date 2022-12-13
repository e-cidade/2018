<?php

use Classes\PostgresMigration;

class M9195RemoverTriggersAguaLeitura extends PostgresMigration
{

    public function up() {
      $this->execute("alter table agualeitura disable trigger user");
      $this->execute("drop trigger if exists tr_agua_atualizaultimaleitura on agualeitura");
      $this->execute("drop trigger if exists tr_agua_atualizaultimaleitura_beforedelete on agualeitura");
      $this->execute("drop trigger if exists tr_agua_calculaconsumo on agualeitura");
      $this->execute("drop trigger if exists tr_agua_calculasaldoposterior on agualeitura");
    }

    public function down() { /* Sem volta */ }
}
