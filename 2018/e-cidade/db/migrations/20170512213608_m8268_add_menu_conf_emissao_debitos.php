<?php

use Classes\PostgresMigration;

class M8268AddMenuConfEmissaoDebitos extends PostgresMigration
{

    /**
     * Upgrade database
     */
    public function up()
    {
        /* Cadastro menu DAEB > TRIBUTARIO > AGUA > PROCEDIMENTOS > Configuração da Emissão de Débitos */
        $sSqlItemMenu = <<<EOT
        insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10419 ,'Configuração da Emissão de Débitos' ,'Configuração da Emissão de Débitos' ,'agu4_confemissaodebitos.php' ,'1' ,'1' ,'Configuração da Emissão de Débitos' ,'true' );
        insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 3332 ,10419 ,29 ,4555 );
EOT;
        $this->execute($sSqlItemMenu);

        /** Adiciona campo de responsavel pelo pagamento na aguacalc
        *   Adiciona campo  de receitas de recalculo na aguaconf
        */
        $sSqlCadastroCampos = <<<EOT
        insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1009275 ,'x22_responsavelpagamento' ,'int4' ,'Responsável Pagamento' ,'null' ,'Responsável Pagamento' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Responsável Pagamento' );

        insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1443 ,1009275 ,15 ,0 );


        insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1009276 ,'x18_receitadebitorecalculo' ,'int4' ,'Receita Débito Recalculo' ,'null' ,'Receita Débito Recalculo' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Receita Débito Recalculo' );

        insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1440 ,1009276 ,11 ,0 );

        insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1009277 ,'x18_receitacreditorecalculo' ,'int4' ,'Receita Crédito Recalculo' ,'null' ,'Receita Crédito Recalculo' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Receita Crédito Recalculo' );

        insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1440 ,1009277 ,12 ,0 );
EOT;

        $this->execute($sSqlCadastroCampos);

        /**
         * Adiciona campo x22_responsavelpagamento a tabela aguacalc
         */
        $this->table('aguacalc',    array('schema'=>'agua'))
                ->addColumn('x22_responsavelpagamento', 'integer', array('null' => true, 'default' => null, 'comment' => 'Responsavel Pagamento'))
                ->save();

        /**
         * Adiciona campo x18_receitadebitorecalculo
         *                          x18_receitacreditorecalculo
         *  na tabela aguaconf
         */
        $this->table('aguaconf',    array('schema'=>'agua'))
                ->addColumn('x18_receitadebitorecalculo', 'integer', array('null' => true, 'default' => null, 'comment' => 'Receita Debito Recalculo'))
                ->addColumn('x18_receitacreditorecalculo', 'integer', array('null' => true, 'default' => null, 'comment' => 'Receita Credito Recalculo'))
                ->save();

    }

    /**
     * Downgrade database
     */
    public function down()
    {
        /* x18_receitacreditorecalculo */
        $this->execute("delete from configuracoes.db_sysarqcamp where codcam = 1009277;");
        $this->execute("delete from configuracoes.db_syscampo where codcam = 1009277;");

        /* x18_receitadebitorecalculo */
        $this->execute("delete from configuracoes.db_sysarqcamp where codcam = 1009276;");
        $this->execute("delete from configuracoes.db_syscampo where codcam = 1009276;");

        /* x22_responsavelpagamento */
        $this->execute("delete from configuracoes.db_sysarqcamp where codcam = 1009275;");
        $this->execute("delete from configuracoes.db_syscampo where codcam = 1009275;");

        /* deleta item de menu */
        $this->execute("delete from db_menu where id_item_filho = 10419;");
        $this->execute("delete from db_itensmenu where id_item = 10419;");


        /**
         * Remove campo x22_responsavelpagamento na tabela aguacalc
         */
        $this->table('aguacalc', array('schema' => 'agua'))
                ->removeColumn('x22_responsavelpagamento')
                ->save();

        /**
         * Remove os campos x18_receitadebitorecalculo
         *                               x18_receitacreditorecalculo
         *  na tabela aguaconf
         */
        $this->table('aguaconf', array('schema' => 'agua'))
                ->removeColumn('x18_receitadebitorecalculo')
                ->removeColumn('x18_receitacreditorecalculo')
                ->save();

    }

}
