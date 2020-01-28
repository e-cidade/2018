<?php

use Classes\PostgresMigration;

/**
 * Adiciona colunas para configura��o da emiss�o dos d�bitos de �gua.
 */
class M8268ConfiguracaoEmissaoAgua extends PostgresMigration
{

    public function up()
    {
      $this->execute("
        insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
          values (1009271, 'x54_emitiroutrosdebitos', 'bool', 'Informa se o contrato deve enviar outros d�bitos na emiss�o, al�m dos d�bitos do c�lculo de �gua.', 'false', 'Emitir Outros D�bitos', 1, 'true', 'false', 'false', 5,'text', 'Emitir Outros D�bitos');
        insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3966 ,1009271 ,13 ,0 );

        insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
          values (1009272, 'x38_emitiroutrosdebitos', 'bool', 'Informa se a economia deve enviar outros d�bitos na emiss�o, al�m dos d�bitos do c�lculo de �gua.', 'false', 'Emite Outros D�bitos', 1, 'true', 'false', 'false', 5, 'text', 'Emitir Outros D�bitos');
        insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3983 ,1009272 ,7 ,0 );
      ");

      $this->execute("
        alter table aguacontrato add column x54_emitiroutrosdebitos boolean default false;
        alter table aguacontratoeconomia add column x38_emitiroutrosdebitos boolean default false;
      ");
    }

    public function down()
    {
      $this->execute("
        delete from db_sysarqcamp where codcam in(1009271, 1009272);
        delete from db_syscampo where codcam in(1009271, 1009272);
      ");

      $this->execute("
        alter table aguacontrato drop column x54_emitiroutrosdebitos;
        alter table aguacontratoeconomia drop column x38_emitiroutrosdebitos;
      ");
    }
}
