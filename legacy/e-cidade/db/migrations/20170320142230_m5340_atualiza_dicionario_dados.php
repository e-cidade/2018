<?php

use Classes\PostgresMigration;

class M5340AtualizaDicionarioDados extends PostgresMigration
{

    public function up()
    {
        $this->execute("update configuracoes.db_syscampo set nomecam = 'DBtxt14', conteudo = 'bool', descricao = 'Reemite a CDA?', valorinicial = 't', rotulo = 'Reemissão', nulo = 'f', tamanho = 1, maiusculo = 'f', autocompl = 'f', aceitatipo = 5, tipoobj = 'text', rotulorel = 'Reemissão' where codcam = 2383");
    }

    public function down()
    {
        $this->execute("update configuracoes.db_syscampo set nomecam = 'DBtxt14', conteudo = 'bool', descricao = 'Reemite o Parcelamento?', valorinicial = 't', rotulo = 'Reemissão', nulo = 'f', tamanho = 1, maiusculo = 'f', autocompl = 'f', aceitatipo = 5, tipoobj = 'text', rotulorel = 'Reemissão' where codcam = 2383");
    }
}
