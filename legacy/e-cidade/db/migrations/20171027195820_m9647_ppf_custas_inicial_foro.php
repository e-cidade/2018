<?php

use Classes\PostgresMigration;

class M9647PpfCustasInicialForo extends PostgresMigration
{

    public function up()
    {

        $this->table('taxa',  array('schema' => 'arrecadacao'))
            ->addColumn('ar36_debitoscomprocesso', 'boolean', array('default' => false, 'null' => true))
            ->addColumn('ar36_debitossemprocesso', 'boolean', array('default' => false, 'null' => true))
            ->save();

        $this->criaDicionario();

        $this->execute("UPDATE arrecadacao.taxa SET ar36_debitoscomprocesso = 't', ar36_debitossemprocesso = 'f'");

        $this->table('taxa',  array('schema' => 'arrecadacao')) 
            ->changeColumn('ar36_debitoscomprocesso', 'boolean', array('default' => false, 'null' => false))
            ->changeColumn('ar36_debitossemprocesso', 'boolean', array('default' => false, 'null' => false))
            ->save();

        $this->table('processoforopartilhacusta', array('schema' => 'juridico'))
            ->addColumn('v77_dispensalancamentorecibo', 'boolean', array('default' => false, 'null' => false))
            ->save();
        
        $this->execute("select setval ('grupotaxatipo_ar38_sequencial_seq', coalesce((select max(ar38_sequencial) from grupotaxatipo), 1))");
        $this->execute("insert into grupotaxatipo values(nextval('grupotaxatipo_ar38_sequencial_seq'),'CUSTAS ADMINISTRATIVAS')");
        $this->execute("insert into grupotaxa values(nextval('grupotaxa_ar37_sequencial_seq'),currval('grupotaxatipo_ar38_sequencial_seq'),'CUSTAS ADMINISTRATIVAS')");        
        $this->execute("update db_itensmenu set descricao = 'Manutenção de Custas', help = 'Manutenção de Custas' where id_item = 8919");
        $this->execute("update db_itensmenu set descricao = 'Taxas / Custas' , help = 'Taxas / Custas' where id_item = 8883;");
    }

    public function down()
    {
        $this->table('taxa',  array('schema' => 'arrecadacao'))
            ->removeColumn('ar36_debitoscomprocesso')
            ->removeColumn('ar36_debitossemprocesso')
            ->save();

        $this->table('processoforopartilhacusta', array('schema' => 'juridico'))
             ->removeColumn('v77_dispensalancamentorecibo')
             ->save();
        
        $this->execute("delete from grupotaxa where ar37_descricao = 'CUSTAS ADMINISTRATIVAS' ");
        $this->execute("delete from grupotaxatipo where ar38_descricao = 'CUSTAS ADMINISTRATIVAS' ");

        $this->execute('DELETE FROM db_sysarqcamp where codarq = 3221 and codcam in(1009487, 1009488)');
        $this->execute('DELETE FROM db_sysarqcamp where codarq = 3230 and codcam in(20752, 1009488)');

        $this->execute('DELETE FROM db_syscampo where codcam in(1009487, 1009488)');
        $this->execute('DELETE FROM db_syscampo where codcam = 20752');
        $this->execute("update db_itensmenu set descricao = 'Tarifas' , help = 'Tarifas' where id_item = 8883;");
    }

    private function criaDicionario()
    {
        $this->table('db_syscampo', array('schema' => 'configuracoes'))
            ->insert(
                array(
                    'codcam',
                    'nomecam',
                    'conteudo',
                    'descricao',
                    'valorinicial',
                    'rotulo',
                    'tamanho',
                    'nulo',
                    'maiusculo',
                    'autocompl',
                    'aceitatipo',
                    'tipoobj',
                    'rotulorel'),
                array(
                    array(
                        1009487,
                        'ar36_debitoscomprocesso',
                        'bool',
                        'Identifica se a taxa sera aplicada em débitos com cobrança judicial',
                        'false',
                        'Cobrança Judicial',
                        1,
                        'f',
                        'f',
                        'f',
                        5,
                        'text',
                        'Cobrança Judicial'
                    ),
                    array(
                        1009488,
                        'ar36_debitossemprocesso',
                        'bool',
                        'Identifica se a taxa sera aplicada em débitos de cobrança administrativa',
                        'false',
                        'Cobrança Administrativa',
                        1,
                        'f',
                        'f',
                        'f',
                        5,
                        'text',
                        'Cobrança Administrativa'),
                    /* @note: Campo já adicionado a tabela processoforopartilhacusta */
                    array(
                        20752,
                        'v77_dispensalancamentorecibo',
                        'bool',
                        'Dispensa cobrança no recibo de custas. Custa isenta, paga ou parcelada.',
                        'false',
                        'Dispensa cobrança',
                        1,
                        'f',
                        'f',
                        'f',
                        5,
                        'text',
                        'Dispensa cobrança')
                )
            )
            ->saveData();

        $this->table('db_sysarqcamp', array('schema' => 'configuracoes'))
            ->insert(
                array(
                    'codarq', 'codcam', 'seqarq', 'codsequencia'),
                array(
                    array(3221,1009487,9,0),
                    array(3221,1009488,10,0),
                    /* @note: Campo v77_dispensalancamentorecibo da tabela processoforopartilhacusta */
                    array(3230,20752,10,0),
                ))
            ->saveData();
    }
}
