<?php

use Classes\PostgresMigration;

class M9647InicialPartilhaCustas extends PostgresMigration
{
    public function up()
    {
        $this->criaDicionario();

        $this->execute("CREATE SEQUENCE juridico.inicialpartilha_v35_sequencial_seq
                            INCREMENT 1
                            MINVALUE 1
                            MAXVALUE 9223372036854775807
                            START 1
                            CACHE 1;"
        );
        $this->table('inicialpartilha',
                    array('schema' => 'juridico', 'id' => false, 'primary_key' => 'v35_sequencial'))
            ->addColumn('v35_sequencial', 'integer', array('null' => false))
            ->addColumn('v35_inicial', 'integer', array('null' => false))
            ->addColumn('v35_tipolancamento', 'integer', array('null' => false, 'default' => 0))
            ->addColumn('v35_dtpagamento', 'date', array('null' => true, 'default' => null))
            ->addColumn('v35_obs', 'text', array('null' => true))
            ->addColumn('v35_valorpartilha', 'decimal', array('null' => true, 'precision' => 15, 'scale' => 2))
            ->addColumn('v35_datapartilha', 'date', array('null' => true))
            ->addForeignKey('v35_inicial', 'inicial', 'v50_inicial')
            ->create();
        $this->execute("ALTER TABLE juridico.inicialpartilha ALTER COLUMN v35_sequencial SET DEFAULT nextval('juridico.inicialpartilha_v35_sequencial_seq')");

        $this->execute("CREATE SEQUENCE juridico.inicialpartilhacustas_v36_sequencial_seq
                            INCREMENT 1
                            MINVALUE 1
                            MAXVALUE 9223372036854775807
                            START 1
                            CACHE 1;"
        );
        $this->table('inicialpartilhacustas', array('schema' => 'juridico', 'id' => false, 'primary_key' => 'v36_sequencial'))
            ->addColumn('v36_sequencial', 'integer', array('null' => false))
            ->addColumn('v36_taxa', 'integer', array('null' => false))
            ->addColumn('v36_inicialpartilha', 'integer', array('null' => false))
            ->addColumn('v36_valor', 'decimal', array('null' => false, 'precision' => 15, 'scale' => 2, 'default' => 0))
            ->addColumn('v36_numnov', 'biginteger', array('null' => true, 'default' => 0))
            ->addColumn('v36_dispensalancamentorecibo', 'boolean', array('null' => false, 'default' => 'false'))
            ->addForeignKey('v36_taxa', 'taxa', 'ar36_sequencial')
            ->addForeignKey('v36_inicialpartilha', 'inicialpartilha', 'v35_sequencial')
            ->create();
        $this->execute("ALTER TABLE juridico.inicialpartilhacustas ALTER COLUMN v36_sequencial SET DEFAULT nextval('juridico.inicialpartilhacustas_v36_sequencial_seq')");




    }

    public function down()
    {
        $this->execute("drop table inicialpartilhacustas");
        $this->execute("drop table inicialpartilha");
        $this->execute("DROP SEQUENCE IF EXISTS juridico.inicialpartilha_v35_sequencial_seq;");
        $this->execute("DROP SEQUENCE IF EXISTS juridico.inicialpartilhacustas_v36_sequencial_seq;");
        $this->apagaDicionario();
    }

    private function criaDicionario()
    {
        /* @note: Registra tabela inicialpartilha e inicialpartilhacustas */
        $this->table('db_sysarquivo', array('schema' => 'configuracoes'))
            ->insert(
                array(
                    'codarq',
                    'nomearq',
                    'descricao',
                    'sigla',
                    'dataincl',
                    'rotulo',
                    'tipotabela',
                    'naolibclass',
                    'naolibfunc',
                    'naolibprog',
                    'naolibform'),
                array(
                    array(
                        1010234,
                        'inicialpartilha',
                        'Partilha de Custas da Inicial do Foro',
                        'v35',
                        '2017-11-03',
                        'Inicial Partilha',
                        0,
                        'f',
                        'f',
                        'f',
                        'f'
                    ),
                    array(
                        1010235,
                        'inicialpartilhacustas',
                        'Custas da Partilha da Inicial',
                        'v36',
                        '2017-11-03',
                        'Inicial Partilha Custas',
                        0,
                        'f',
                        'f',
                        'f',
                        'f'
                    )
                )
            )
            ->saveData();

        /* @note: registra as tabelas ao modulo juridico */
        $this->table('db_sysarqmod', array('schema' => 'configuracoes'))
            ->insert(
                array('codmod', 'codarq'),
                array(
                    array(21, 1010234),
                    array(21, 1010235)
                )
            )
            ->saveData();

        /* @note: registra vinculos das tabelas */
        $this->table('db_sysarqarq', array('schema' => 'configuracoes'))
            ->insert(
                array('codarqpai', 'codarq'),
                array(
                    /* vinculta inicialpartilha a inicial*/
                    array(108, 1010234),
                    /* vincula inicialpartilhacustas a inicialpartilha */
                    array(1010234,1010235)
                )
            )
            ->saveData();

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
                        1009495,
                        'v35_sequencial',
                        'int4',
                        'Código',
                        '0',
                        'Código',
                        10,
                        'f',
                        'f',
                        'f',
                        1,
                        'text',
                        'Código'),
                    array(
                        1009496,
                        'v35_inicial',
                        'int4',
                        'Inicial',
                        '0',
                        'Inicial',
                        10,
                        'f',
                        'f',
                        'f',
                        1,
                        'text',
                        'Inicial'),
                    array(
                        1009497,
                        'v35_tipolancamento',
                        'int4',
                        'Tipo de Lançamento da Partilha',
                        '0',
                        'Tipo Lançamento',
                        10,
                        'f',
                        'f',
                        'f',
                        1,
                        'text',
                        'Tipo Lançamento'),
                    array(
                        1009498,
                        'v35_dtpagamento',
                        'date',
                        'Data de Pagamento',
                        'null',
                        'Data Pagamento',
                        10,
                        't',
                        'f',
                        'f',
                        0,
                        'text',
                        'Data Pagamento'),
                    array(
                        1009499,
                        'v35_obs',
                        'text',
                        'Observação da Partilha',
                        '',
                        'Observação',
                        1,
                        't',
                        't',
                        'f',
                        0,
                        'text',
                        'Observação'),
                    array(
                        1009500,
                        'v35_valorpartilha',
                        'float8',
                        'Valor da Partilha',
                        '0',
                        'Valor da Partilha',
                        15,
                        't',
                        'f',
                        'f',
                        4,
                        'text',
                        'Valor da Partilha'),
                    array(
                        1009501,
                        'v35_datapartilha',
                        'date',
                        'Data Partilha',
                        'null',
                        'Data Partilha',
                        10,
                        't',
                        'f',
                        'f',
                        0,
                        'text',
                        'Data Partilha'),
                    /* campos da tabela inicialpartilhacustas */
                    array(
                        1009505,
                        'v36_sequencial',
                        'int4',
                        'Sequencial',
                        '0',
                        'Código',
                        10,
                        'f',
                        'f',
                        't',
                        1,
                        'text',
                        'Código'),
                    array(
                        1009506,
                        'v36_taxa',
                        'int4',
                        'Taxa da Custa',
                        '0',
                        'Taxa',
                        10,
                        'f',
                        'f',
                        'f',
                        1,
                        'text',
                        'Taxa'),
                    array(
                        1009507,
                        'v36_inicialpartilha',
                        'int4',
                        'Vinculo com a Inicial Partilha',
                        '0',
                        'Inicial Partilha',
                        10,
                        'f',
                        'f',
                        'f',
                        1,
                        'text',
                        'Inicial Partilha'),
                    array(
                        1009508,
                        'v36_valor',
                        'float8',
                        'Valor da Custas',
                        '0',
                        'Valor da Custa',
                        15,
                        'f',
                        'f',
                        'f',
                        4,
                        'text',
                        'Valor da Custa'),
                    array(

                        1009509,
                        'v36_numnov',
                        'int8',
                        'Numnov',
                        '0',
                        'Numnov',
                        15,
                        't',
                        'f',
                        'f',
                        1,
                        'text',
                        'Numnov'),
                    array(
                        1009510,
                        'v36_dispensalancamentorecibo',
                        'bool',
                        'Dispensa Lançamento da Custas no Recibo',
                        'false',
                        'Dispensa Lançamento Recibo',
                        1,
                        'f',
                        'f',
                        'f',
                        5,
                        'text',
                        'Dispensa Lançamento Recibo')
                )
            )
            ->saveData();

        /* @note: vincula os campos a tabela */
        $this->table('db_sysarqcamp', array('schema' => 'configuracoes'))
            ->insert(
                array(
                    'codarq', 'codcam', 'seqarq', 'codsequencia'),
                array(
                    /* campos da tabela inicialpartilha */
                    array(1010234,1009495,1,0),
                    array(1010234,1009496,2,0),
                    array(1010234,1009497,3,0),
                    array(1010234,1009498,4,0),
                    array(1010234,1009499,5,0),
                    array(1010234,1009500,6,0),
                    array(1010234,1009501,7,0),
                    /* campos da tabela inicialpartilhacustas */
                    array(1010235,1009505,1,0),
                    array(1010235,1009506,2,0),
                    array(1010235,1009507,3,0),
                    array(1010235,1009508,4,0),
                    array(1010235,1009509,5,0),
                    array(1010235,1009510,6,0)
                )
            )
            ->saveData();


    }

    private function apagaDicionario()
    {
        $this->execute("delete from db_sysarqcamp where codarq in (1010234, 1010235)");
        /* inicialpartilha */
        $this->execute("delete from db_syscampo where codcam in
                        (1009495, 1009496, 1009497, 1009498, 1009499, 1009500, 1009501)");
        /* inicialpartilhacustas */
        $this->execute("delete from db_syscampo where codcam in 
                        (1009505, 1009506, 1009507, 1009508, 1009509, 1009510)");

        $this->execute("delete from db_sysarqarq where codarq in (1010234, 1010235)");
        $this->execute("delete from db_sysarqmod where codarq in (1010234, 1010235)");
        $this->execute("delete from db_sysarquivo where codarq in (1010234, 1010235)");
    }


}
