<?php

use Classes\PostgresMigration;

/**
 * Adiciona complemento para endereço das economias vinculadas ao contrato (módulo Água)
 */
class M8268AguaComplementoEnderecoEconomia extends PostgresMigration
{

  public function up() {

    $this->execute("
      insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1009274 ,'x38_complemento' ,'varchar(200)' ,'Complemento do endereço.' ,'' ,'Complemento' ,200 ,'true' ,'false' ,'false' ,0 ,'text' ,'Complemento' );
      insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3983 ,1009274 ,8 ,0 );
    ");

    $this->execute("alter table aguacontratoeconomia add column x38_complemento varchar(200)");
  }

  public function down() {

    $this->execute("
      delete from db_sysarqcamp where codcam = 1009274;
      delete from db_syscampo where codcam = 1009274;
    ");

    $this->execute("alter table aguacontratoeconomia drop column x38_complemento");
  }
}
