<?php

use Classes\PostgresMigration;

class M8894AguaAdicionaObservacoesEconomia extends PostgresMigration
{

  public function up() {
    $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1009393 ,'x38_observacoes' ,'varchar(200)' ,'Observações' ,'' ,'Observações' ,200 ,'true' ,'false' ,'false' ,0 ,'text' ,'Observações' )");
    $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3983 ,1009393 ,9 ,0 )");
    $this->execute("alter table aguacontratoeconomia add column x38_observacoes varchar(200) default null");
    $this->execute("update aguacontratoeconomia set x38_observacoes = x38_complemento");
    $this->execute("update aguacontratoeconomia set x38_complemento = null");
  }

  public function down() {
    $this->execute("delete from db_sysarqcamp where codcam = 1009393");
    $this->execute("delete from db_syscampo where codcam = 1009393");
    $this->execute("alter table aguacontratoeconomia drop column x38_observacoes");
  }

}
