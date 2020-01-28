<?php

use Classes\PostgresMigration;

class M9200BalancoFinanceiro extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $this->execute("
            insert into orcparamrel values (172, 'BALANÇO FINANCEIRO DCASP 2017', 4, '');
            insert into orcparamrelperiodos values
               (nextval('orcparamrelperiodos_o113_sequencial_seq'), 17, 172)
              ,(nextval('orcparamrelperiodos_o113_sequencial_seq'), 18, 172)
              ,(nextval('orcparamrelperiodos_o113_sequencial_seq'), 19, 172)
              ,(nextval('orcparamrelperiodos_o113_sequencial_seq'), 20, 172)
              ,(nextval('orcparamrelperiodos_o113_sequencial_seq'), 21, 172)
              ,(nextval('orcparamrelperiodos_o113_sequencial_seq'), 22, 172)
              ,(nextval('orcparamrelperiodos_o113_sequencial_seq'), 23, 172)
              ,(nextval('orcparamrelperiodos_o113_sequencial_seq'), 24, 172)
              ,(nextval('orcparamrelperiodos_o113_sequencial_seq'), 25, 172)
              ,(nextval('orcparamrelperiodos_o113_sequencial_seq'), 26, 172)
              ,(nextval('orcparamrelperiodos_o113_sequencial_seq'), 27, 172)
              ,(nextval('orcparamrelperiodos_o113_sequencial_seq'), 28, 172);

            insert into orcparamseq values
               (172, 1, 'Receita Orçamentária (I)', 1, 1, 1, false, false, false, false, false, 'Receita Orçamentária (I)', false, true, 1, 1, '', false, 0)
              ,(172, 2, 'Ordinária', 1, 0, 1, false, false, false, false, false, 'Ordinária', true, false, 2, 2, '', false, 1)
              ,(172, 3, 'Vinculada', 1, 1, 1, false, false, false, false, false, 'Vinculada', false, true, 3, 2, '', false, 0)
              ,(172, 4, 'Recursos Vinculados à Educação', 1, 0, 1, false, false, false, false, false, 'Recursos Vinculados à Educação', true, false, 4, 3, '', false, 1)
              ,(172, 5, 'Recursos Vinculados à Saúde', 1, 0, 1, false, false, false, false, false, 'Recursos Vinculados à Saúde', true, false, 5, 3, '', false, 1)
              ,(172, 6, 'Recursos Vinculados à Previdência Social - RPPS', 1, 0, 1, false, false, false, false, false, 'Recursos Vinculados à Previdência Social - RPPS', true, false, 6, 3, '', false, 1)
              ,(172, 7, 'Recursos Vinculados à Previdência Social - RGPS', 1, 0, 1, false, false, false, false, false, 'Recursos Vinculados à Previdência Social - RGPS', true, false, 7, 3, '', false, 1)
              ,(172, 8, 'Recursos Vinculados à Assistência Social', 1, 0, 1, false, false, false, false, false, 'Recursos Vinculados à Assistência Social', true, false, 8, 3, '', false, 1)
              ,(172, 9, 'Outras Destinações de Recursos', 1, 0, 1, false, false, false, false, false, 'Outras Destinações de Recursos', true, false, 9, 3, '', false, 1)
              ,(172, 10, 'Transferências Financeiras Recebidas (II)', 1, 1, 1, false, false, false, false, false, 'Transferências Financeiras Recebidas (II)', false, true, 10, 1, '', false, 0)
              ,(172, 11, 'Transferências Recebidas  para a Execução Orçamentária', 1, 0, 1, false, false, false, false, false, 'Transferências Recebidas  para a Execução Orçamentária', true, false, 11, 2, '', false, 3)
              ,(172, 12, 'Transferências Recebidas Independentes da Execução Orçamentá', 1, 0, 1, false, false, false, false, false, 'Transferências Recebidas Independentes da Execução Orçamentá', true, false, 12, 2, '', false, 3)
              ,(172, 13, 'Transferências Recebidas para Aportes de recursos para o RPP', 1, 0, 1, false, false, false, false, false, 'Transferências Recebidas para Aportes de recursos para o RPP', true, false, 13, 2, '', false, 3)
              ,(172, 14, 'Transferências Recebidas para Aportes de recursos para o RGP', 1, 0, 1, false, false, false, false, false, 'Transferências Recebidas para Aportes de recursos para o RGP', true, false, 14, 2, '', false, 3)
              ,(172, 15, 'Recebimentos Extraorçamentários (III)', 1, 1, 1, false, false, false, false, false, 'Recebimentos Extraorçamentários (III)', false, true, 15, 1, '', false, 0)
              ,(172, 16, 'Inscrição de Restos a Pagar  Não Processados', 1, 0, 1, false, false, false, false, false, 'Inscrição de Restos a Pagar  Não Processados', true, false, 16, 2, '', false, 2)
              ,(172, 17, 'Inscrição de Restos a Pagar  Processados', 1, 0, 1, false, false, false, false, false, 'Inscrição de Restos a Pagar  Processados', true, false, 17, 2, '', false, 2)
              ,(172, 18, 'Depósitos Restituíveis e Valores Vinculados', 1, 0, 1, false, false, false, false, false, 'Depósitos Restituíveis e Valores Vinculados', true, false, 18, 2, '', false, 3)
              ,(172, 19, 'Outros Recebimentos Extraorçamentários', 1, 0, 1, false, false, false, false, false, 'Outros Recebimentos Extraorçamentários', true, false, 19, 2, '', false, 3)
              ,(172, 20, 'Saldo do Exercício Anterior (IV)', 1, 1, 1, false, false, false, false, false, 'Saldo do Exercício Anterior (IV)', false, true, 20, 1, '', false, 0)
              ,(172, 21, 'Caixa e Equivalentes de Caixa', 1, 0, 1, false, false, false, false, false, 'Caixa e Equivalentes de Caixa', true, false, 21, 2, '', false, 3)
              ,(172, 22, 'Depósitos Restituíveis e Valores Vinculados', 1, 0, 1, false, false, false, false, false, 'Depósitos Restituíveis e Valores Vinculados', true, false, 22, 2, '', false, 3)
              ,(172, 23, 'TOTAL(V) = ( I+II+III + IV )', 1, 1, 1, false, false, false, false, false, 'TOTAL(V) = ( I+II+III + IV )', false, true, 23, 1, '', false, 0)
              ,(172, 24, 'Despesa Orçamentária (VI)', 1, 1, 1, false, false, false, false, false, 'Despesa Orçamentária (VI)', false, true, 24, 1, '', false, 0)
              ,(172, 25, 'Ordinária', 1, 0, 1, false, false, false, false, false, 'Ordinária', true, false, 25, 2, '', false, 2)
              ,(172, 26, 'Vinculada', 1, 1, 1, false, false, false, false, false, 'Vinculada', false, true, 26, 2, '', false, 0)
              ,(172, 27, 'Recursos Destinados à Educação', 1, 0, 1, false, false, false, false, false, 'Recursos Destinados à Educação', true, false, 27, 3, '', false, 2)
              ,(172, 28, 'Recursos Destinados à Saúde', 1, 0, 1, false, false, false, false, false, 'Recursos Destinados à Saúde', true, false, 28, 3, '', false, 2)
              ,(172, 29, 'Recursos Destinados à Previdência Social - RPPS', 1, 0, 1, false, false, false, false, false, 'Recursos Destinados à Previdência Social - RPPS', true, false, 29, 3, '', false, 2)
              ,(172, 30, 'Recursos Destinados à Previdência Social - RGPS', 1, 0, 1, false, false, false, false, false, 'Recursos Destinados à Previdência Social - RGPS', true, false, 30, 3, '', false, 2)
              ,(172, 31, 'Recursos Destinados à Assistência Social', 1, 0, 1, false, false, false, false, false, 'Recursos Destinados à Assistência Social', true, false, 31, 3, '', false, 2)
              ,(172, 32, 'Outras Destinações de Recursos', 1, 0, 1, false, false, false, false, false, 'Outras Destinações de Recursos', true, false, 32, 3, '', false, 2)
              ,(172, 33, 'Transferências Financeiras Concedidas (VII)', 1, 1, 1, false, false, false, false, false, 'Transferências Financeiras Concedidas (VII)', false, true, 33, 1, '', false, 0)
              ,(172, 34, 'Transferências Concedidas para a Execução Orçamentária', 1, 0, 1, false, false, false, false, false, 'Transferências Concedidas para a Execução Orçamentária', true, false, 34, 2, '', false, 3)
              ,(172, 35, 'Transferências Concedidas Independentes de Execução Orçament', 1, 0, 1, false, false, false, false, false, 'Transferências Concedidas Independentes de Execução Orçament', true, false, 35, 2, '', false, 3)
              ,(172, 36, 'Transferências Concedidas para Aportes de recursos para o RP', 1, 0, 1, false, false, false, false, false, 'Transferências Concedidas para Aportes de recursos para o RP', true, false, 36, 2, '', false, 3)
              ,(172, 37, 'Transferências Concedidas para Aportes de recursos para o RG', 1, 0, 1, false, false, false, false, false, 'Transferências Concedidas para Aportes de recursos para o RG', true, false, 37, 2, '', false, 3)
              ,(172, 38, 'Pagamentos Extraorçamentários (VIII)', 1, 1, 1, false, false, false, false, false, 'Pagamentos Extraorçamentários (VIII)', false, true, 38, 1, '', false, 0)
              ,(172, 39, 'Pagamentos de Restos a Pagar Não Processados', 1, 0, 1, false, false, false, false, false, 'Pagamentos de Restos a Pagar Não Processados', true, false, 39, 2, '', false, 4)
              ,(172, 40, 'Pagamentos de Restos a Pagar Processados', 1, 0, 1, false, false, false, false, false, 'Pagamentos de Restos a Pagar Processados', true, false, 40, 2, '', false, 4)
              ,(172, 41, 'Depósitos Restituíveis e Valores Vinculados', 1, 0, 1, false, false, false, false, false, 'Depósitos Restituíveis e Valores Vinculados', true, false, 41, 2, '', false, 3)
              ,(172, 42, 'Outros Pagamentos Extraorçamentários', 1, 0, 1, false, false, false, false, false, 'Outros Pagamentos Extraorçamentários', true, false, 42, 2, '', false, 3)
              ,(172, 43, 'Saldo para o Exercício Seguinte (IX)', 1, 1, 1, false, false, false, false, false, 'Saldo para o Exercício Seguinte (IX)', false, true, 43, 1, '', false, 0)
              ,(172, 44, 'Caixa e Equivalentes de Caixa', 1, 0, 1, false, false, false, false, false, 'Caixa e Equivalentes de Caixa', true, false, 44, 2, '', false, 3)
              ,(172, 45, 'Depósitos Restituíveis e Valores Vinculados', 1, 0, 1, false, false, false, false, false, 'Depósitos Restituíveis e Valores Vinculados', true, false, 45, 2, '', false, 3)
              ,(172, 46, 'TOTAL (X) = ( VI+VII+VIII+IX)', 1, 1, 1, false, false, false, false, false, 'TOTAL (X) = ( VI+VII+VIII+IX)', false, true, 46, 1, '', false, 0);

            insert into orcparamseqorcparamseqcoluna values
               (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 172, 177, 1, 17, 'L[2]->vlrexatual+F[3]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 172, 177, 1, 19, 'L[2]->vlrexatual+F[3]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 172, 177, 1, 20, 'L[2]->vlrexatual+F[3]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 172, 177, 1, 21, 'L[2]->vlrexatual+F[3]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 172, 177, 1, 22, 'L[2]->vlrexatual+F[3]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 172, 177, 1, 23, 'L[2]->vlrexatual+F[3]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 172, 177, 1, 24, 'L[2]->vlrexatual+F[3]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 172, 177, 1, 25, 'L[2]->vlrexatual+F[3]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 172, 177, 1, 26, 'L[2]->vlrexatual+F[3]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 172, 177, 1, 27, 'L[2]->vlrexatual+F[3]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 172, 177, 1, 28, 'L[2]->vlrexatual+F[3]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 172, 178, 1, 17, 'L[2]->vlrexanter+F[3]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 172, 178, 1, 18, 'L[2]->vlrexanter+F[3]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 172, 178, 1, 19, 'L[2]->vlrexanter+F[3]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 172, 178, 1, 20, 'L[2]->vlrexanter+F[3]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 172, 178, 1, 21, 'L[2]->vlrexanter+F[3]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 172, 178, 1, 22, 'L[2]->vlrexanter+F[3]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 172, 178, 1, 23, 'L[2]->vlrexanter+F[3]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 172, 178, 1, 24, 'L[2]->vlrexanter+F[3]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 172, 178, 1, 25, 'L[2]->vlrexanter+F[3]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 172, 178, 1, 26, 'L[2]->vlrexanter+F[3]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 172, 177, 1, 18, 'L[2]->vlrexatual+F[3]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 172, 178, 1, 27, 'L[2]->vlrexanter+F[3]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 172, 178, 1, 28, 'L[2]->vlrexanter+F[3]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 172, 177, 2, 18, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 172, 178, 2, 23, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 172, 178, 2, 26, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 172, 178, 2, 27, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 172, 178, 2, 28, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 172, 178, 2, 22, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 172, 178, 2, 21, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 172, 178, 2, 20, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 172, 178, 2, 19, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 172, 178, 2, 18, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 172, 178, 2, 17, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 172, 177, 2, 28, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 172, 177, 2, 27, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 172, 177, 2, 26, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 172, 177, 2, 25, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 172, 177, 2, 24, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 172, 177, 2, 23, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 172, 177, 2, 22, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 172, 177, 2, 21, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 172, 178, 2, 25, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 172, 177, 2, 20, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 172, 177, 2, 19, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 172, 178, 2, 24, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 172, 177, 2, 17, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 172, 178, 3, 27, 'L[4]->vlrexanter+L[5]->vlrexanter+L[6]->vlrexanter+L[7]->vlrexanter+L[8]->vlrexanter+L[9]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 172, 177, 3, 17, 'L[4]->vlrexatual+L[5]->vlrexatual+L[6]->vlrexatual+L[7]->vlrexatual+L[8]->vlrexatual+L[9]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 172, 178, 3, 26, 'L[4]->vlrexanter+L[5]->vlrexanter+L[6]->vlrexanter+L[7]->vlrexanter+L[8]->vlrexanter+L[9]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 172, 178, 3, 25, 'L[4]->vlrexanter+L[5]->vlrexanter+L[6]->vlrexanter+L[7]->vlrexanter+L[8]->vlrexanter+L[9]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 172, 178, 3, 24, 'L[4]->vlrexanter+L[5]->vlrexanter+L[6]->vlrexanter+L[7]->vlrexanter+L[8]->vlrexanter+L[9]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 172, 178, 3, 23, 'L[4]->vlrexanter+L[5]->vlrexanter+L[6]->vlrexanter+L[7]->vlrexanter+L[8]->vlrexanter+L[9]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 172, 178, 3, 22, 'L[4]->vlrexanter+L[5]->vlrexanter+L[6]->vlrexanter+L[7]->vlrexanter+L[8]->vlrexanter+L[9]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 172, 178, 3, 21, 'L[4]->vlrexanter+L[5]->vlrexanter+L[6]->vlrexanter+L[7]->vlrexanter+L[8]->vlrexanter+L[9]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 172, 178, 3, 20, 'L[4]->vlrexanter+L[5]->vlrexanter+L[6]->vlrexanter+L[7]->vlrexanter+L[8]->vlrexanter+L[9]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 172, 178, 3, 19, 'L[4]->vlrexanter+L[5]->vlrexanter+L[6]->vlrexanter+L[7]->vlrexanter+L[8]->vlrexanter+L[9]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 172, 178, 3, 18, 'L[4]->vlrexanter+L[5]->vlrexanter+L[6]->vlrexanter+L[7]->vlrexanter+L[8]->vlrexanter+L[9]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 172, 178, 3, 17, 'L[4]->vlrexanter+L[5]->vlrexanter+L[6]->vlrexanter+L[7]->vlrexanter+L[8]->vlrexanter+L[9]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 172, 177, 3, 28, 'L[4]->vlrexatual+L[5]->vlrexatual+L[6]->vlrexatual+L[7]->vlrexatual+L[8]->vlrexatual+L[9]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 172, 177, 3, 27, 'L[4]->vlrexatual+L[5]->vlrexatual+L[6]->vlrexatual+L[7]->vlrexatual+L[8]->vlrexatual+L[9]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 172, 177, 3, 26, 'L[4]->vlrexatual+L[5]->vlrexatual+L[6]->vlrexatual+L[7]->vlrexatual+L[8]->vlrexatual+L[9]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 172, 177, 3, 25, 'L[4]->vlrexatual+L[5]->vlrexatual+L[6]->vlrexatual+L[7]->vlrexatual+L[8]->vlrexatual+L[9]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 172, 177, 3, 24, 'L[4]->vlrexatual+L[5]->vlrexatual+L[6]->vlrexatual+L[7]->vlrexatual+L[8]->vlrexatual+L[9]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 172, 177, 3, 23, 'L[4]->vlrexatual+L[5]->vlrexatual+L[6]->vlrexatual+L[7]->vlrexatual+L[8]->vlrexatual+L[9]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 172, 177, 3, 22, 'L[4]->vlrexatual+L[5]->vlrexatual+L[6]->vlrexatual+L[7]->vlrexatual+L[8]->vlrexatual+L[9]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 172, 177, 3, 21, 'L[4]->vlrexatual+L[5]->vlrexatual+L[6]->vlrexatual+L[7]->vlrexatual+L[8]->vlrexatual+L[9]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 172, 177, 3, 20, 'L[4]->vlrexatual+L[5]->vlrexatual+L[6]->vlrexatual+L[7]->vlrexatual+L[8]->vlrexatual+L[9]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 172, 177, 3, 19, 'L[4]->vlrexatual+L[5]->vlrexatual+L[6]->vlrexatual+L[7]->vlrexatual+L[8]->vlrexatual+L[9]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 172, 177, 3, 18, 'L[4]->vlrexatual+L[5]->vlrexatual+L[6]->vlrexatual+L[7]->vlrexatual+L[8]->vlrexatual+L[9]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 172, 178, 3, 28, 'L[4]->vlrexanter+L[5]->vlrexanter+L[6]->vlrexanter+L[7]->vlrexanter+L[8]->vlrexanter+L[9]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 172, 177, 4, 21, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 172, 178, 4, 28, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 172, 178, 4, 27, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 172, 178, 4, 26, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 172, 178, 4, 25, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 172, 178, 4, 24, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 172, 178, 4, 23, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 172, 178, 4, 22, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 172, 178, 4, 21, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 172, 178, 4, 20, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 172, 178, 4, 19, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 172, 178, 4, 18, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 172, 178, 4, 17, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 172, 177, 4, 28, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 172, 177, 4, 27, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 172, 177, 4, 26, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 172, 177, 4, 25, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 172, 177, 4, 24, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 172, 177, 4, 23, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 172, 177, 4, 22, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 172, 177, 4, 20, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 172, 177, 4, 19, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 172, 177, 4, 18, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 172, 177, 4, 17, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 172, 177, 5, 17, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 172, 178, 5, 28, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 172, 178, 5, 27, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 172, 178, 5, 26, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 172, 178, 5, 25, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 172, 178, 5, 24, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 172, 178, 5, 23, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 172, 178, 5, 22, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 172, 178, 5, 21, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 172, 178, 5, 20, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 172, 178, 5, 19, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 172, 178, 5, 18, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 172, 178, 5, 17, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 172, 177, 5, 28, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 172, 177, 5, 27, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 172, 177, 5, 26, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 172, 177, 5, 25, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 172, 177, 5, 24, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 172, 177, 5, 23, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 172, 177, 5, 22, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 172, 177, 5, 21, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 172, 177, 5, 20, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 172, 177, 5, 19, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 172, 177, 5, 18, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 172, 177, 6, 20, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 172, 177, 6, 25, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 172, 178, 6, 28, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 172, 178, 6, 27, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 172, 178, 6, 26, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 172, 178, 6, 25, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 172, 178, 6, 24, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 172, 178, 6, 23, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 172, 178, 6, 22, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 172, 178, 6, 21, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 172, 178, 6, 20, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 172, 178, 6, 19, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 172, 178, 6, 18, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 172, 178, 6, 17, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 172, 177, 6, 28, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 172, 177, 6, 27, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 172, 177, 6, 26, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 172, 177, 6, 24, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 172, 177, 6, 23, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 172, 177, 6, 22, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 172, 177, 6, 21, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 172, 177, 6, 19, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 172, 177, 6, 18, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 172, 177, 6, 17, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 172, 178, 7, 27, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 172, 177, 7, 17, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 172, 177, 7, 18, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 172, 177, 7, 19, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 172, 177, 7, 20, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 172, 177, 7, 21, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 172, 177, 7, 22, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 172, 177, 7, 23, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 172, 177, 7, 24, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 172, 177, 7, 25, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 172, 177, 7, 26, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 172, 177, 7, 27, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 172, 177, 7, 28, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 172, 178, 7, 17, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 172, 178, 7, 18, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 172, 178, 7, 19, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 172, 178, 7, 20, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 172, 178, 7, 21, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 172, 178, 7, 22, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 172, 178, 7, 23, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 172, 178, 7, 24, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 172, 178, 7, 28, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 172, 178, 7, 25, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 172, 178, 7, 26, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 172, 177, 8, 26, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 172, 178, 8, 28, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 172, 178, 8, 27, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 172, 178, 8, 26, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 172, 178, 8, 25, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 172, 178, 8, 24, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 172, 178, 8, 23, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 172, 178, 8, 22, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 172, 178, 8, 21, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 172, 178, 8, 20, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 172, 178, 8, 19, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 172, 178, 8, 18, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 172, 178, 8, 17, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 172, 177, 8, 28, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 172, 177, 8, 27, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 172, 177, 8, 25, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 172, 177, 8, 24, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 172, 177, 8, 23, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 172, 177, 8, 22, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 172, 177, 8, 21, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 172, 177, 8, 20, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 172, 177, 8, 19, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 172, 177, 8, 18, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 172, 177, 8, 17, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 172, 177, 9, 25, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 172, 177, 9, 26, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 172, 177, 9, 21, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 172, 177, 9, 22, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 172, 177, 9, 23, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 172, 177, 9, 27, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 172, 177, 9, 24, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 172, 177, 9, 17, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 172, 177, 9, 18, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 172, 178, 9, 28, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 172, 178, 9, 27, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 172, 178, 9, 26, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 172, 178, 9, 25, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 172, 177, 9, 19, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 172, 178, 9, 24, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 172, 178, 9, 23, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 172, 178, 9, 22, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 172, 178, 9, 21, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 172, 178, 9, 20, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 172, 178, 9, 19, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 172, 178, 9, 18, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 172, 178, 9, 17, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 172, 177, 9, 28, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 172, 177, 9, 20, '#saldo_arrecadado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 172, 178, 10, 19, 'L[11]->vlrexanter+L[12]->vlrexanter+L[13]->vlrexanter+L[14]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 172, 178, 10, 23, 'L[11]->vlrexanter+L[12]->vlrexanter+L[13]->vlrexanter+L[14]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 172, 178, 10, 24, 'L[11]->vlrexanter+L[12]->vlrexanter+L[13]->vlrexanter+L[14]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 172, 178, 10, 25, 'L[11]->vlrexanter+L[12]->vlrexanter+L[13]->vlrexanter+L[14]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 172, 178, 10, 26, 'L[11]->vlrexanter+L[12]->vlrexanter+L[13]->vlrexanter+L[14]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 172, 178, 10, 22, 'L[11]->vlrexanter+L[12]->vlrexanter+L[13]->vlrexanter+L[14]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 172, 178, 10, 21, 'L[11]->vlrexanter+L[12]->vlrexanter+L[13]->vlrexanter+L[14]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 172, 178, 10, 20, 'L[11]->vlrexanter+L[12]->vlrexanter+L[13]->vlrexanter+L[14]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 172, 178, 10, 18, 'L[11]->vlrexanter+L[12]->vlrexanter+L[13]->vlrexanter+L[14]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 172, 178, 10, 17, 'L[11]->vlrexanter+L[12]->vlrexanter+L[13]->vlrexanter+L[14]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 172, 177, 10, 28, 'L[11]->vlrexatual+L[12]->vlrexatual+L[13]->vlrexatual+L[14]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 172, 177, 10, 27, 'L[11]->vlrexatual+L[12]->vlrexatual+L[13]->vlrexatual+L[14]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 172, 177, 10, 26, 'L[11]->vlrexatual+L[12]->vlrexatual+L[13]->vlrexatual+L[14]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 172, 177, 10, 25, 'L[11]->vlrexatual+L[12]->vlrexatual+L[13]->vlrexatual+L[14]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 172, 177, 10, 24, 'L[11]->vlrexatual+L[12]->vlrexatual+L[13]->vlrexatual+L[14]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 172, 177, 10, 23, 'L[11]->vlrexatual+L[12]->vlrexatual+L[13]->vlrexatual+L[14]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 172, 177, 10, 22, 'L[11]->vlrexatual+L[12]->vlrexatual+L[13]->vlrexatual+L[14]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 172, 177, 10, 21, 'L[11]->vlrexatual+L[12]->vlrexatual+L[13]->vlrexatual+L[14]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 172, 177, 10, 20, 'L[11]->vlrexatual+L[12]->vlrexatual+L[13]->vlrexatual+L[14]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 172, 177, 10, 19, 'L[11]->vlrexatual+L[12]->vlrexatual+L[13]->vlrexatual+L[14]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 172, 177, 10, 18, 'L[11]->vlrexatual+L[12]->vlrexatual+L[13]->vlrexatual+L[14]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 172, 177, 10, 17, 'L[11]->vlrexatual+L[12]->vlrexatual+L[13]->vlrexatual+L[14]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 172, 178, 10, 27, 'L[11]->vlrexanter+L[12]->vlrexanter+L[13]->vlrexanter+L[14]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 172, 178, 10, 28, 'L[11]->vlrexanter+L[12]->vlrexanter+L[13]->vlrexanter+L[14]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 172, 178, 11, 22, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 172, 178, 11, 21, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 172, 177, 11, 24, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 172, 177, 11, 25, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 172, 177, 11, 26, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 172, 177, 11, 27, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 172, 177, 11, 28, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 172, 178, 11, 17, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 172, 178, 11, 18, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 172, 178, 11, 20, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 172, 178, 11, 24, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 172, 177, 11, 17, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 172, 177, 11, 18, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 172, 177, 11, 19, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 172, 177, 11, 20, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 172, 177, 11, 23, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 172, 177, 11, 21, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 172, 177, 11, 22, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 172, 178, 11, 19, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 172, 178, 11, 28, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 172, 178, 11, 27, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 172, 178, 11, 26, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 172, 178, 11, 25, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 172, 178, 11, 23, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 172, 177, 12, 24, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 172, 178, 12, 28, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 172, 178, 12, 27, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 172, 178, 12, 26, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 172, 178, 12, 25, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 172, 178, 12, 24, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 172, 178, 12, 23, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 172, 178, 12, 22, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 172, 178, 12, 21, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 172, 178, 12, 20, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 172, 178, 12, 19, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 172, 178, 12, 18, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 172, 178, 12, 17, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 172, 177, 12, 28, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 172, 177, 12, 27, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 172, 177, 12, 26, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 172, 177, 12, 17, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 172, 177, 12, 18, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 172, 177, 12, 19, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 172, 177, 12, 20, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 172, 177, 12, 21, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 172, 177, 12, 22, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 172, 177, 12, 23, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 172, 177, 12, 25, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 172, 178, 13, 17, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 172, 177, 13, 28, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 172, 177, 13, 27, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 172, 177, 13, 26, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 172, 177, 13, 25, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 172, 177, 13, 24, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 172, 177, 13, 23, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 172, 177, 13, 22, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 172, 177, 13, 21, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 172, 177, 13, 20, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 172, 177, 13, 19, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 172, 177, 13, 18, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 172, 177, 13, 17, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 172, 178, 13, 22, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 172, 178, 13, 21, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 172, 178, 13, 20, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 172, 178, 13, 19, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 172, 178, 13, 23, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 172, 178, 13, 18, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 172, 178, 13, 28, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 172, 178, 13, 27, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 172, 178, 13, 26, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 172, 178, 13, 25, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 172, 178, 13, 24, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 172, 177, 14, 18, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 172, 178, 14, 23, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 172, 178, 14, 24, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 172, 178, 14, 25, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 172, 178, 14, 20, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 172, 178, 14, 21, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 172, 178, 14, 22, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 172, 178, 14, 26, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 172, 178, 14, 27, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 172, 178, 14, 28, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 172, 178, 14, 19, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 172, 178, 14, 18, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 172, 178, 14, 17, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 172, 177, 14, 28, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 172, 177, 14, 27, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 172, 177, 14, 26, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 172, 177, 14, 25, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 172, 177, 14, 24, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 172, 177, 14, 23, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 172, 177, 14, 22, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 172, 177, 14, 21, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 172, 177, 14, 20, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 172, 177, 14, 19, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 172, 177, 14, 17, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 172, 177, 15, 25, 'L[16]->vlrexatual+L[17]->vlrexatual+L[18]->vlrexatual+L[19]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 172, 177, 15, 17, 'L[16]->vlrexatual+L[17]->vlrexatual+L[18]->vlrexatual+L[19]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 172, 177, 15, 18, 'L[16]->vlrexatual+L[17]->vlrexatual+L[18]->vlrexatual+L[19]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 172, 177, 15, 19, 'L[16]->vlrexatual+L[17]->vlrexatual+L[18]->vlrexatual+L[19]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 172, 177, 15, 20, 'L[16]->vlrexatual+L[17]->vlrexatual+L[18]->vlrexatual+L[19]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 172, 177, 15, 21, 'L[16]->vlrexatual+L[17]->vlrexatual+L[18]->vlrexatual+L[19]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 172, 177, 15, 26, 'L[16]->vlrexatual+L[17]->vlrexatual+L[18]->vlrexatual+L[19]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 172, 177, 15, 27, 'L[16]->vlrexatual+L[17]->vlrexatual+L[18]->vlrexatual+L[19]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 172, 177, 15, 28, 'L[16]->vlrexatual+L[17]->vlrexatual+L[18]->vlrexatual+L[19]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 172, 178, 15, 17, 'L[16]->vlrexanter+L[17]->vlrexanter+L[18]->vlrexanter+L[19]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 172, 178, 15, 18, 'L[16]->vlrexanter+L[17]->vlrexanter+L[18]->vlrexanter+L[19]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 172, 178, 15, 19, 'L[16]->vlrexanter+L[17]->vlrexanter+L[18]->vlrexanter+L[19]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 172, 178, 15, 20, 'L[16]->vlrexanter+L[17]->vlrexanter+L[18]->vlrexanter+L[19]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 172, 178, 15, 21, 'L[16]->vlrexanter+L[17]->vlrexanter+L[18]->vlrexanter+L[19]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 172, 178, 15, 22, 'L[16]->vlrexanter+L[17]->vlrexanter+L[18]->vlrexanter+L[19]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 172, 178, 15, 23, 'L[16]->vlrexanter+L[17]->vlrexanter+L[18]->vlrexanter+L[19]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 172, 178, 15, 24, 'L[16]->vlrexanter+L[17]->vlrexanter+L[18]->vlrexanter+L[19]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 172, 178, 15, 25, 'L[16]->vlrexanter+L[17]->vlrexanter+L[18]->vlrexanter+L[19]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 172, 178, 15, 26, 'L[16]->vlrexanter+L[17]->vlrexanter+L[18]->vlrexanter+L[19]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 172, 178, 15, 27, 'L[16]->vlrexanter+L[17]->vlrexanter+L[18]->vlrexanter+L[19]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 172, 178, 15, 28, 'L[16]->vlrexanter+L[17]->vlrexanter+L[18]->vlrexanter+L[19]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 172, 177, 15, 24, 'L[16]->vlrexatual+L[17]->vlrexatual+L[18]->vlrexatual+L[19]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 172, 177, 15, 23, 'L[16]->vlrexatual+L[17]->vlrexatual+L[18]->vlrexatual+L[19]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 172, 177, 15, 22, 'L[16]->vlrexatual+L[17]->vlrexatual+L[18]->vlrexatual+L[19]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 172, 177, 16, 23, '#empenhado - #anulado - #liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 172, 177, 16, 22, '#empenhado - #anulado - #liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 172, 177, 16, 21, '#empenhado - #anulado - #liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 172, 177, 16, 20, '#empenhado - #anulado - #liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 172, 177, 16, 19, '#empenhado - #anulado - #liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 172, 177, 16, 18, '#empenhado - #anulado - #liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 172, 177, 16, 17, '#empenhado - #anulado - #liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 172, 178, 16, 28, '#empenhado - #anulado - #liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 172, 178, 16, 27, '#empenhado - #anulado - #liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 172, 178, 16, 26, '#empenhado - #anulado - #liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 172, 178, 16, 25, '#empenhado - #anulado - #liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 172, 178, 16, 24, '#empenhado - #anulado - #liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 172, 178, 16, 23, '#empenhado - #anulado - #liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 172, 178, 16, 22, '#empenhado - #anulado - #liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 172, 178, 16, 21, '#empenhado - #anulado - #liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 172, 178, 16, 20, '#empenhado - #anulado - #liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 172, 178, 16, 19, '#empenhado - #anulado - #liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 172, 178, 16, 18, '#empenhado - #anulado - #liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 172, 178, 16, 17, '#empenhado - #anulado - #liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 172, 177, 16, 28, '#empenhado - #anulado - #liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 172, 177, 16, 27, '#empenhado - #anulado - #liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 172, 177, 16, 26, '#empenhado - #anulado - #liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 172, 177, 16, 25, '#empenhado - #anulado - #liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 172, 177, 16, 24, '#empenhado - #anulado - #liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 172, 178, 17, 18, '#atual_a_pagar_liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 172, 178, 17, 20, '#atual_a_pagar_liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 172, 178, 17, 21, '#atual_a_pagar_liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 172, 178, 17, 22, '#atual_a_pagar_liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 172, 178, 17, 23, '#atual_a_pagar_liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 172, 178, 17, 24, '#atual_a_pagar_liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 172, 178, 17, 25, '#atual_a_pagar_liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 172, 178, 17, 28, '#atual_a_pagar_liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 172, 178, 17, 27, '#atual_a_pagar_liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 172, 178, 17, 26, '#atual_a_pagar_liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 172, 177, 17, 17, '#atual_a_pagar_liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 172, 177, 17, 18, '#atual_a_pagar_liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 172, 177, 17, 19, '#atual_a_pagar_liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 172, 177, 17, 20, '#atual_a_pagar_liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 172, 177, 17, 21, '#atual_a_pagar_liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 172, 177, 17, 22, '#atual_a_pagar_liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 172, 177, 17, 23, '#atual_a_pagar_liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 172, 177, 17, 24, '#atual_a_pagar_liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 172, 177, 17, 25, '#atual_a_pagar_liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 172, 177, 17, 26, '#atual_a_pagar_liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 172, 177, 17, 27, '#atual_a_pagar_liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 172, 177, 17, 28, '#atual_a_pagar_liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 172, 178, 17, 17, '#atual_a_pagar_liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 172, 178, 17, 19, '#atual_a_pagar_liquidado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 172, 178, 18, 17, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 172, 178, 18, 18, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 172, 178, 18, 25, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 172, 178, 18, 24, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 172, 178, 18, 23, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 172, 178, 18, 22, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 172, 178, 18, 28, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 172, 178, 18, 27, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 172, 177, 18, 27, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 172, 177, 18, 26, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 172, 177, 18, 25, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 172, 177, 18, 24, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 172, 177, 18, 23, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 172, 178, 18, 21, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 172, 178, 18, 20, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 172, 178, 18, 19, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 172, 177, 18, 22, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 172, 177, 18, 21, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 172, 177, 18, 20, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 172, 177, 18, 19, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 172, 177, 18, 18, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 172, 177, 18, 17, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 172, 178, 18, 26, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 172, 177, 18, 28, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 172, 178, 19, 22, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 172, 177, 19, 18, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 172, 177, 19, 17, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 172, 178, 19, 26, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 172, 178, 19, 27, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 172, 178, 19, 28, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 172, 177, 19, 20, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 172, 177, 19, 21, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 172, 177, 19, 22, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 172, 177, 19, 23, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 172, 177, 19, 24, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 172, 177, 19, 25, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 172, 177, 19, 26, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 172, 177, 19, 27, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 172, 177, 19, 28, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 172, 178, 19, 17, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 172, 178, 19, 18, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 172, 178, 19, 19, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 172, 178, 19, 20, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 172, 178, 19, 21, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 172, 178, 19, 25, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 172, 177, 19, 19, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 172, 178, 19, 23, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 172, 178, 19, 24, '#saldo_anterior_credito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 172, 177, 20, 20, 'L[21]->vlrexatual+L[22]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 172, 177, 20, 19, 'L[21]->vlrexatual+L[22]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 172, 177, 20, 18, 'L[21]->vlrexatual+L[22]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 172, 177, 20, 17, 'L[21]->vlrexatual+L[22]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 172, 178, 20, 28, 'L[21]->vlrexanter+L[22]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 172, 178, 20, 27, 'L[21]->vlrexanter+L[22]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 172, 178, 20, 26, 'L[21]->vlrexanter+L[22]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 172, 178, 20, 25, 'L[21]->vlrexanter+L[22]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 172, 178, 20, 24, 'L[21]->vlrexanter+L[22]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 172, 178, 20, 23, 'L[21]->vlrexanter+L[22]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 172, 178, 20, 22, 'L[21]->vlrexanter+L[22]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 172, 178, 20, 21, 'L[21]->vlrexanter+L[22]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 172, 178, 20, 20, 'L[21]->vlrexanter+L[22]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 172, 178, 20, 19, 'L[21]->vlrexanter+L[22]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 172, 178, 20, 18, 'L[21]->vlrexanter+L[22]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 172, 178, 20, 17, 'L[21]->vlrexanter+L[22]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 172, 177, 20, 28, 'L[21]->vlrexatual+L[22]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 172, 177, 20, 27, 'L[21]->vlrexatual+L[22]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 172, 177, 20, 26, 'L[21]->vlrexatual+L[22]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 172, 177, 20, 25, 'L[21]->vlrexatual+L[22]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 172, 177, 20, 24, 'L[21]->vlrexatual+L[22]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 172, 177, 20, 23, 'L[21]->vlrexatual+L[22]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 172, 177, 20, 22, 'L[21]->vlrexatual+L[22]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 172, 177, 20, 21, 'L[21]->vlrexatual+L[22]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 172, 178, 21, 24, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 172, 177, 21, 25, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 172, 177, 21, 24, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 172, 177, 21, 23, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 172, 177, 21, 22, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 172, 177, 21, 21, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 172, 177, 21, 20, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 172, 178, 21, 25, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 172, 178, 21, 26, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 172, 178, 21, 27, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 172, 178, 21, 28, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 172, 177, 21, 27, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 172, 177, 21, 28, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 172, 178, 21, 17, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 172, 178, 21, 18, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 172, 178, 21, 19, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 172, 178, 21, 20, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 172, 177, 21, 26, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 172, 178, 21, 21, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 172, 178, 21, 22, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 172, 178, 21, 23, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 172, 177, 21, 17, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 172, 177, 21, 18, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 172, 177, 21, 19, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 172, 178, 22, 25, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 172, 177, 22, 17, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 172, 177, 22, 18, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 172, 177, 22, 19, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 172, 177, 22, 20, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 172, 177, 22, 21, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 172, 177, 22, 22, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 172, 177, 22, 23, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 172, 177, 22, 24, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 172, 177, 22, 25, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 172, 177, 22, 26, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 172, 177, 22, 27, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 172, 177, 22, 28, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 172, 178, 22, 17, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 172, 178, 22, 18, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 172, 178, 22, 19, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 172, 178, 22, 20, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 172, 178, 22, 23, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 172, 178, 22, 21, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 172, 178, 22, 22, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 172, 178, 22, 24, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 172, 178, 22, 26, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 172, 178, 22, 27, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 172, 178, 22, 28, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 172, 178, 23, 19, 'F[1] + F[10] + F[15] + F[20]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 172, 177, 23, 17, 'F[1] + F[10] + F[15] + F[20]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 172, 177, 23, 18, 'F[1] + F[10] + F[15] + F[20]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 172, 177, 23, 19, 'F[1] + F[10] + F[15] + F[20]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 172, 177, 23, 20, 'F[1] + F[10] + F[15] + F[20]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 172, 177, 23, 21, 'F[1] + F[10] + F[15] + F[20]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 172, 177, 23, 22, 'F[1] + F[10] + F[15] + F[20]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 172, 177, 23, 23, 'F[1] + F[10] + F[15] + F[20]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 172, 177, 23, 24, 'F[1] + F[10] + F[15] + F[20]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 172, 177, 23, 25, 'F[1] + F[10] + F[15] + F[20]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 172, 177, 23, 26, 'F[1] + F[10] + F[15] + F[20]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 172, 177, 23, 27, 'F[1] + F[10] + F[15] + F[20]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 172, 177, 23, 28, 'F[1] + F[10] + F[15] + F[20]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 172, 178, 23, 17, 'F[1] + F[10] + F[15] + F[20]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 172, 178, 23, 18, 'F[1] + F[10] + F[15] + F[20]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 172, 178, 23, 20, 'F[1] + F[10] + F[15] + F[20]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 172, 178, 23, 21, 'F[1] + F[10] + F[15] + F[20]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 172, 178, 23, 22, 'F[1] + F[10] + F[15] + F[20]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 172, 178, 23, 23, 'F[1] + F[10] + F[15] + F[20]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 172, 178, 23, 24, 'F[1] + F[10] + F[15] + F[20]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 172, 178, 23, 25, 'F[1] + F[10] + F[15] + F[20]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 172, 178, 23, 26, 'F[1] + F[10] + F[15] + F[20]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 172, 178, 23, 27, 'F[1] + F[10] + F[15] + F[20]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 172, 178, 23, 28, 'F[1] + F[10] + F[15] + F[20]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 172, 177, 24, 23, 'L[25]->vlrexatual+F[26]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 172, 177, 24, 24, 'L[25]->vlrexatual+F[26]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 172, 177, 24, 17, 'L[25]->vlrexatual+F[26]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 172, 178, 24, 22, 'L[25]->vlrexanter+F[26]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 172, 178, 24, 23, 'L[25]->vlrexanter+F[26]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 172, 178, 24, 24, 'L[25]->vlrexanter+F[26]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 172, 178, 24, 25, 'L[25]->vlrexanter+F[26]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 172, 178, 24, 26, 'L[25]->vlrexanter+F[26]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 172, 177, 24, 25, 'L[25]->vlrexatual+F[26]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 172, 177, 24, 26, 'L[25]->vlrexatual+F[26]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 172, 177, 24, 27, 'L[25]->vlrexatual+F[26]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 172, 178, 24, 27, 'L[25]->vlrexanter+F[26]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 172, 178, 24, 28, 'L[25]->vlrexanter+F[26]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 172, 178, 24, 21, 'L[25]->vlrexanter+F[26]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 172, 178, 24, 17, 'L[25]->vlrexanter+F[26]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 172, 177, 24, 28, 'L[25]->vlrexatual+F[26]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 172, 178, 24, 20, 'L[25]->vlrexanter+F[26]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 172, 178, 24, 19, 'L[25]->vlrexanter+F[26]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 172, 178, 24, 18, 'L[25]->vlrexanter+F[26]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 172, 177, 24, 18, 'L[25]->vlrexatual+F[26]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 172, 177, 24, 19, 'L[25]->vlrexatual+F[26]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 172, 177, 24, 20, 'L[25]->vlrexatual+F[26]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 172, 177, 24, 21, 'L[25]->vlrexatual+F[26]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 172, 177, 24, 22, 'L[25]->vlrexatual+F[26]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 172, 178, 25, 19, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 172, 177, 25, 17, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 172, 177, 25, 18, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 172, 177, 25, 19, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 172, 177, 25, 20, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 172, 177, 25, 21, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 172, 177, 25, 22, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 172, 177, 25, 23, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 172, 177, 25, 24, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 172, 177, 25, 25, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 172, 177, 25, 26, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 172, 177, 25, 27, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 172, 177, 25, 28, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 172, 178, 25, 28, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 172, 178, 25, 27, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 172, 178, 25, 26, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 172, 178, 25, 25, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 172, 178, 25, 24, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 172, 178, 25, 23, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 172, 178, 25, 22, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 172, 178, 25, 21, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 172, 178, 25, 20, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 172, 178, 25, 18, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 172, 178, 25, 17, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 172, 178, 26, 19, 'L[27]->vlrexanter+L[28]->vlrexanter+L[29]->vlrexanter+L[30]->vlrexanter+L[31]->vlrexanter+L[32]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 172, 178, 26, 28, 'L[27]->vlrexanter+L[28]->vlrexanter+L[29]->vlrexanter+L[30]->vlrexanter+L[31]->vlrexanter+L[32]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 172, 178, 26, 27, 'L[27]->vlrexanter+L[28]->vlrexanter+L[29]->vlrexanter+L[30]->vlrexanter+L[31]->vlrexanter+L[32]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 172, 178, 26, 26, 'L[27]->vlrexanter+L[28]->vlrexanter+L[29]->vlrexanter+L[30]->vlrexanter+L[31]->vlrexanter+L[32]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 172, 178, 26, 25, 'L[27]->vlrexanter+L[28]->vlrexanter+L[29]->vlrexanter+L[30]->vlrexanter+L[31]->vlrexanter+L[32]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 172, 178, 26, 24, 'L[27]->vlrexanter+L[28]->vlrexanter+L[29]->vlrexanter+L[30]->vlrexanter+L[31]->vlrexanter+L[32]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 172, 178, 26, 23, 'L[27]->vlrexanter+L[28]->vlrexanter+L[29]->vlrexanter+L[30]->vlrexanter+L[31]->vlrexanter+L[32]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 172, 178, 26, 22, 'L[27]->vlrexanter+L[28]->vlrexanter+L[29]->vlrexanter+L[30]->vlrexanter+L[31]->vlrexanter+L[32]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 172, 178, 26, 21, 'L[27]->vlrexanter+L[28]->vlrexanter+L[29]->vlrexanter+L[30]->vlrexanter+L[31]->vlrexanter+L[32]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 172, 178, 26, 20, 'L[27]->vlrexanter+L[28]->vlrexanter+L[29]->vlrexanter+L[30]->vlrexanter+L[31]->vlrexanter+L[32]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 172, 178, 26, 18, 'L[27]->vlrexanter+L[28]->vlrexanter+L[29]->vlrexanter+L[30]->vlrexanter+L[31]->vlrexanter+L[32]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 172, 178, 26, 17, 'L[27]->vlrexanter+L[28]->vlrexanter+L[29]->vlrexanter+L[30]->vlrexanter+L[31]->vlrexanter+L[32]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 172, 177, 26, 28, 'L[27]->vlrexatual+L[28]->vlrexatual+L[29]->vlrexatual+L[30]->vlrexatual+L[31]->vlrexatual+L[32]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 172, 177, 26, 27, 'L[27]->vlrexatual+L[28]->vlrexatual+L[29]->vlrexatual+L[30]->vlrexatual+L[31]->vlrexatual+L[32]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 172, 177, 26, 26, 'L[27]->vlrexatual+L[28]->vlrexatual+L[29]->vlrexatual+L[30]->vlrexatual+L[31]->vlrexatual+L[32]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 172, 177, 26, 25, 'L[27]->vlrexatual+L[28]->vlrexatual+L[29]->vlrexatual+L[30]->vlrexatual+L[31]->vlrexatual+L[32]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 172, 177, 26, 24, 'L[27]->vlrexatual+L[28]->vlrexatual+L[29]->vlrexatual+L[30]->vlrexatual+L[31]->vlrexatual+L[32]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 172, 177, 26, 23, 'L[27]->vlrexatual+L[28]->vlrexatual+L[29]->vlrexatual+L[30]->vlrexatual+L[31]->vlrexatual+L[32]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 172, 177, 26, 22, 'L[27]->vlrexatual+L[28]->vlrexatual+L[29]->vlrexatual+L[30]->vlrexatual+L[31]->vlrexatual+L[32]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 172, 177, 26, 21, 'L[27]->vlrexatual+L[28]->vlrexatual+L[29]->vlrexatual+L[30]->vlrexatual+L[31]->vlrexatual+L[32]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 172, 177, 26, 20, 'L[27]->vlrexatual+L[28]->vlrexatual+L[29]->vlrexatual+L[30]->vlrexatual+L[31]->vlrexatual+L[32]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 172, 177, 26, 19, 'L[27]->vlrexatual+L[28]->vlrexatual+L[29]->vlrexatual+L[30]->vlrexatual+L[31]->vlrexatual+L[32]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 172, 177, 26, 18, 'L[27]->vlrexatual+L[28]->vlrexatual+L[29]->vlrexatual+L[30]->vlrexatual+L[31]->vlrexatual+L[32]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 172, 177, 26, 17, 'L[27]->vlrexatual+L[28]->vlrexatual+L[29]->vlrexatual+L[30]->vlrexatual+L[31]->vlrexatual+L[32]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 172, 177, 27, 22, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 172, 177, 27, 18, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 172, 177, 27, 17, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 172, 177, 27, 21, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 172, 177, 27, 20, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 172, 178, 27, 19, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 172, 178, 27, 20, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 172, 178, 27, 21, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 172, 178, 27, 22, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 172, 178, 27, 23, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 172, 178, 27, 24, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 172, 178, 27, 25, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 172, 177, 27, 19, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 172, 178, 27, 26, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 172, 178, 27, 18, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 172, 178, 27, 27, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 172, 178, 27, 28, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 172, 178, 27, 17, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 172, 177, 27, 28, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 172, 177, 27, 27, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 172, 177, 27, 26, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 172, 177, 27, 25, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 172, 177, 27, 24, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 172, 177, 27, 23, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 172, 177, 28, 25, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 172, 178, 28, 28, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 172, 178, 28, 27, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 172, 178, 28, 26, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 172, 178, 28, 25, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 172, 178, 28, 24, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 172, 178, 28, 23, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 172, 178, 28, 22, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 172, 178, 28, 21, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 172, 178, 28, 20, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 172, 178, 28, 19, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 172, 178, 28, 18, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 172, 178, 28, 17, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 172, 177, 28, 28, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 172, 177, 28, 27, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 172, 177, 28, 26, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 172, 177, 28, 24, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 172, 177, 28, 23, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 172, 177, 28, 22, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 172, 177, 28, 21, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 172, 177, 28, 20, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 172, 177, 28, 19, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 172, 177, 28, 18, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 172, 177, 28, 17, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 172, 177, 29, 25, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 172, 177, 29, 17, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 172, 177, 29, 28, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 172, 178, 29, 17, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 172, 178, 29, 18, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 172, 178, 29, 19, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 172, 178, 29, 20, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 172, 178, 29, 21, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 172, 178, 29, 22, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 172, 178, 29, 23, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 172, 178, 29, 24, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 172, 178, 29, 25, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 172, 178, 29, 26, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 172, 178, 29, 27, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 172, 178, 29, 28, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 172, 177, 29, 18, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 172, 177, 29, 19, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 172, 177, 29, 27, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 172, 177, 29, 26, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 172, 177, 29, 20, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 172, 177, 29, 21, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 172, 177, 29, 22, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 172, 177, 29, 23, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 172, 177, 29, 24, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 172, 178, 30, 18, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 172, 178, 30, 28, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 172, 178, 30, 27, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 172, 178, 30, 26, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 172, 178, 30, 25, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 172, 178, 30, 24, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 172, 178, 30, 23, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 172, 178, 30, 22, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 172, 178, 30, 21, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 172, 178, 30, 20, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 172, 178, 30, 19, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 172, 178, 30, 17, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 172, 177, 30, 28, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 172, 177, 30, 27, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 172, 177, 30, 26, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 172, 177, 30, 25, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 172, 177, 30, 24, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 172, 177, 30, 23, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 172, 177, 30, 22, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 172, 177, 30, 21, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 172, 177, 30, 20, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 172, 177, 30, 19, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 172, 177, 30, 18, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 172, 177, 30, 17, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 172, 177, 31, 20, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 172, 177, 31, 19, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 172, 177, 31, 17, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 172, 177, 31, 18, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 172, 178, 31, 28, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 172, 178, 31, 27, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 172, 178, 31, 26, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 172, 178, 31, 25, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 172, 178, 31, 24, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 172, 178, 31, 23, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 172, 178, 31, 22, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 172, 178, 31, 21, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 172, 178, 31, 20, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 172, 178, 31, 19, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 172, 178, 31, 18, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 172, 178, 31, 17, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 172, 177, 31, 28, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 172, 177, 31, 27, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 172, 177, 31, 26, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 172, 177, 31, 25, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 172, 177, 31, 24, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 172, 177, 31, 23, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 172, 177, 31, 22, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 172, 177, 31, 21, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 172, 177, 32, 20, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 172, 178, 32, 28, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 172, 178, 32, 27, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 172, 178, 32, 26, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 172, 178, 32, 25, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 172, 178, 32, 24, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 172, 178, 32, 23, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 172, 178, 32, 22, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 172, 178, 32, 21, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 172, 178, 32, 20, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 172, 178, 32, 19, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 172, 178, 32, 18, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 172, 178, 32, 17, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 172, 177, 32, 28, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 172, 177, 32, 27, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 172, 177, 32, 26, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 172, 177, 32, 25, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 172, 177, 32, 24, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 172, 177, 32, 23, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 172, 177, 32, 22, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 172, 177, 32, 21, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 172, 177, 32, 19, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 172, 177, 32, 18, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 172, 177, 32, 17, '#empenhado - #anulado')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 172, 177, 33, 20, 'L[34]->vlrexatual+L[35]->vlrexatual+L[36]->vlrexatual+L[37]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 172, 177, 33, 19, 'L[34]->vlrexatual+L[35]->vlrexatual+L[36]->vlrexatual+L[37]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 172, 177, 33, 17, 'L[34]->vlrexatual+L[35]->vlrexatual+L[36]->vlrexatual+L[37]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 172, 177, 33, 18, 'L[34]->vlrexatual+L[35]->vlrexatual+L[36]->vlrexatual+L[37]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 172, 178, 33, 28, 'L[34]->vlrexanter+L[35]->vlrexanter+L[36]->vlrexanter+L[37]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 172, 178, 33, 27, 'L[34]->vlrexanter+L[35]->vlrexanter+L[36]->vlrexanter+L[37]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 172, 178, 33, 26, 'L[34]->vlrexanter+L[35]->vlrexanter+L[36]->vlrexanter+L[37]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 172, 178, 33, 25, 'L[34]->vlrexanter+L[35]->vlrexanter+L[36]->vlrexanter+L[37]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 172, 178, 33, 24, 'L[34]->vlrexanter+L[35]->vlrexanter+L[36]->vlrexanter+L[37]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 172, 178, 33, 23, 'L[34]->vlrexanter+L[35]->vlrexanter+L[36]->vlrexanter+L[37]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 172, 178, 33, 22, 'L[34]->vlrexanter+L[35]->vlrexanter+L[36]->vlrexanter+L[37]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 172, 178, 33, 21, 'L[34]->vlrexanter+L[35]->vlrexanter+L[36]->vlrexanter+L[37]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 172, 178, 33, 20, 'L[34]->vlrexanter+L[35]->vlrexanter+L[36]->vlrexanter+L[37]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 172, 178, 33, 19, 'L[34]->vlrexanter+L[35]->vlrexanter+L[36]->vlrexanter+L[37]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 172, 178, 33, 18, 'L[34]->vlrexanter+L[35]->vlrexanter+L[36]->vlrexanter+L[37]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 172, 178, 33, 17, 'L[34]->vlrexanter+L[35]->vlrexanter+L[36]->vlrexanter+L[37]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 172, 177, 33, 28, 'L[34]->vlrexatual+L[35]->vlrexatual+L[36]->vlrexatual+L[37]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 172, 177, 33, 27, 'L[34]->vlrexatual+L[35]->vlrexatual+L[36]->vlrexatual+L[37]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 172, 177, 33, 26, 'L[34]->vlrexatual+L[35]->vlrexatual+L[36]->vlrexatual+L[37]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 172, 177, 33, 25, 'L[34]->vlrexatual+L[35]->vlrexatual+L[36]->vlrexatual+L[37]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 172, 177, 33, 24, 'L[34]->vlrexatual+L[35]->vlrexatual+L[36]->vlrexatual+L[37]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 172, 177, 33, 23, 'L[34]->vlrexatual+L[35]->vlrexatual+L[36]->vlrexatual+L[37]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 172, 177, 33, 22, 'L[34]->vlrexatual+L[35]->vlrexatual+L[36]->vlrexatual+L[37]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 172, 177, 33, 21, 'L[34]->vlrexatual+L[35]->vlrexatual+L[36]->vlrexatual+L[37]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 172, 177, 34, 20, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 172, 177, 34, 19, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 172, 177, 34, 18, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 172, 177, 34, 17, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 172, 178, 34, 24, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 172, 178, 34, 23, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 172, 178, 34, 22, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 172, 178, 34, 21, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 172, 178, 34, 20, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 172, 178, 34, 19, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 172, 178, 34, 18, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 172, 178, 34, 25, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 172, 178, 34, 17, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 172, 177, 34, 28, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 172, 177, 34, 27, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 172, 177, 34, 26, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 172, 177, 34, 25, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 172, 177, 34, 24, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 172, 177, 34, 23, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 172, 177, 34, 22, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 172, 177, 34, 21, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 172, 178, 34, 28, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 172, 178, 34, 27, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 172, 178, 34, 26, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 172, 177, 35, 25, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 172, 177, 35, 17, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 172, 177, 35, 18, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 172, 177, 35, 19, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 172, 177, 35, 20, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 172, 177, 35, 21, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 172, 177, 35, 22, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 172, 177, 35, 23, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 172, 177, 35, 24, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 172, 177, 35, 26, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 172, 177, 35, 27, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 172, 177, 35, 28, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 172, 178, 35, 17, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 172, 178, 35, 18, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 172, 178, 35, 19, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 172, 178, 35, 20, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 172, 178, 35, 21, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 172, 178, 35, 22, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 172, 178, 35, 23, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 172, 178, 35, 24, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 172, 178, 35, 25, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 172, 178, 35, 26, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 172, 178, 35, 27, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 172, 178, 35, 28, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 172, 178, 36, 25, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 172, 178, 36, 24, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 172, 178, 36, 23, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 172, 178, 36, 22, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 172, 178, 36, 21, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 172, 178, 36, 20, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 172, 177, 36, 27, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 172, 177, 36, 26, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 172, 177, 36, 25, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 172, 177, 36, 24, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 172, 177, 36, 23, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 172, 177, 36, 22, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 172, 177, 36, 21, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 172, 177, 36, 20, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 172, 177, 36, 19, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 172, 177, 36, 18, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 172, 177, 36, 17, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 172, 178, 36, 17, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 172, 177, 36, 28, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 172, 178, 36, 28, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 172, 178, 36, 18, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 172, 178, 36, 27, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 172, 178, 36, 26, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 172, 178, 36, 19, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 172, 177, 37, 27, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 172, 178, 37, 17, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 172, 178, 37, 18, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 172, 178, 37, 19, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 172, 178, 37, 20, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 172, 178, 37, 21, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 172, 178, 37, 22, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 172, 178, 37, 23, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 172, 178, 37, 24, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 172, 178, 37, 25, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 172, 178, 37, 26, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 172, 178, 37, 27, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 172, 178, 37, 28, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 172, 177, 37, 19, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 172, 177, 37, 18, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 172, 177, 37, 17, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 172, 177, 37, 20, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 172, 177, 37, 21, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 172, 177, 37, 22, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 172, 177, 37, 23, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 172, 177, 37, 24, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 172, 177, 37, 25, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 172, 177, 37, 26, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 172, 177, 37, 28, '#saldo_anterior')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 172, 177, 38, 27, 'L[39]->vlrexatual+L[40]->vlrexatual+L[41]->vlrexatual+L[42]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 172, 178, 38, 28, 'L[39]->vlrexanter+L[40]->vlrexanter+L[41]->vlrexanter+L[42]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 172, 178, 38, 27, 'L[39]->vlrexanter+L[40]->vlrexanter+L[41]->vlrexanter+L[42]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 172, 178, 38, 26, 'L[39]->vlrexanter+L[40]->vlrexanter+L[41]->vlrexanter+L[42]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 172, 178, 38, 25, 'L[39]->vlrexanter+L[40]->vlrexanter+L[41]->vlrexanter+L[42]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 172, 178, 38, 24, 'L[39]->vlrexanter+L[40]->vlrexanter+L[41]->vlrexanter+L[42]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 172, 178, 38, 23, 'L[39]->vlrexanter+L[40]->vlrexanter+L[41]->vlrexanter+L[42]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 172, 178, 38, 22, 'L[39]->vlrexanter+L[40]->vlrexanter+L[41]->vlrexanter+L[42]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 172, 178, 38, 21, 'L[39]->vlrexanter+L[40]->vlrexanter+L[41]->vlrexanter+L[42]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 172, 178, 38, 20, 'L[39]->vlrexanter+L[40]->vlrexanter+L[41]->vlrexanter+L[42]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 172, 178, 38, 19, 'L[39]->vlrexanter+L[40]->vlrexanter+L[41]->vlrexanter+L[42]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 172, 178, 38, 18, 'L[39]->vlrexanter+L[40]->vlrexanter+L[41]->vlrexanter+L[42]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 172, 178, 38, 17, 'L[39]->vlrexanter+L[40]->vlrexanter+L[41]->vlrexanter+L[42]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 172, 177, 38, 17, 'L[39]->vlrexatual+L[40]->vlrexatual+L[41]->vlrexatual+L[42]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 172, 177, 38, 18, 'L[39]->vlrexatual+L[40]->vlrexatual+L[41]->vlrexatual+L[42]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 172, 177, 38, 19, 'L[39]->vlrexatual+L[40]->vlrexatual+L[41]->vlrexatual+L[42]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 172, 177, 38, 20, 'L[39]->vlrexatual+L[40]->vlrexatual+L[41]->vlrexatual+L[42]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 172, 177, 38, 21, 'L[39]->vlrexatual+L[40]->vlrexatual+L[41]->vlrexatual+L[42]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 172, 177, 38, 22, 'L[39]->vlrexatual+L[40]->vlrexatual+L[41]->vlrexatual+L[42]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 172, 177, 38, 23, 'L[39]->vlrexatual+L[40]->vlrexatual+L[41]->vlrexatual+L[42]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 172, 177, 38, 24, 'L[39]->vlrexatual+L[40]->vlrexatual+L[41]->vlrexatual+L[42]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 172, 177, 38, 25, 'L[39]->vlrexatual+L[40]->vlrexatual+L[41]->vlrexatual+L[42]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 172, 177, 38, 26, 'L[39]->vlrexatual+L[40]->vlrexatual+L[41]->vlrexatual+L[42]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 172, 177, 38, 28, 'L[39]->vlrexatual+L[40]->vlrexatual+L[41]->vlrexatual+L[42]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 172, 177, 39, 26, '#vlrpagnproc')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 172, 178, 39, 28, '#vlrpagnproc')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 172, 177, 39, 17, '#vlrpagnproc')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 172, 177, 39, 28, '#vlrpagnproc')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 172, 177, 39, 27, '#vlrpagnproc')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 172, 177, 39, 25, '#vlrpagnproc')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 172, 177, 39, 24, '#vlrpagnproc')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 172, 177, 39, 23, '#vlrpagnproc')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 172, 177, 39, 22, '#vlrpagnproc')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 172, 177, 39, 21, '#vlrpagnproc')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 172, 177, 39, 20, '#vlrpagnproc')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 172, 177, 39, 19, '#vlrpagnproc')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 172, 177, 39, 18, '#vlrpagnproc')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 172, 178, 39, 27, '#vlrpagnproc')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 172, 178, 39, 26, '#vlrpagnproc')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 172, 178, 39, 25, '#vlrpagnproc')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 172, 178, 39, 24, '#vlrpagnproc')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 172, 178, 39, 23, '#vlrpagnproc')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 172, 178, 39, 22, '#vlrpagnproc')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 172, 178, 39, 21, '#vlrpagnproc')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 172, 178, 39, 20, '#vlrpagnproc')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 172, 178, 39, 19, '#vlrpagnproc')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 172, 178, 39, 18, '#vlrpagnproc')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 172, 178, 39, 17, '#vlrpagnproc')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 172, 178, 40, 20, '#vlrpag')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 172, 178, 40, 18, '#vlrpag')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 172, 178, 40, 17, '#vlrpag')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 172, 177, 40, 23, '#vlrpag')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 172, 177, 40, 28, '#vlrpag')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 172, 177, 40, 27, '#vlrpag')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 172, 177, 40, 26, '#vlrpag')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 172, 177, 40, 25, '#vlrpag')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 172, 177, 40, 24, '#vlrpag')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 172, 177, 40, 22, '#vlrpag')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 172, 177, 40, 21, '#vlrpag')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 172, 177, 40, 20, '#vlrpag')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 172, 178, 40, 19, '#vlrpag')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 172, 178, 40, 21, '#vlrpag')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 172, 177, 40, 19, '#vlrpag')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 172, 177, 40, 18, '#vlrpag')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 172, 177, 40, 17, '#vlrpag')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 172, 178, 40, 27, '#vlrpag')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 172, 178, 40, 26, '#vlrpag')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 172, 178, 40, 25, '#vlrpag')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 172, 178, 40, 24, '#vlrpag')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 172, 178, 40, 23, '#vlrpag')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 172, 178, 40, 22, '#vlrpag')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 172, 178, 40, 28, '#vlrpag')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 172, 178, 41, 18, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 172, 178, 41, 28, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 172, 177, 41, 17, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 172, 177, 41, 18, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 172, 177, 41, 19, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 172, 177, 41, 20, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 172, 177, 41, 21, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 172, 177, 41, 22, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 172, 177, 41, 23, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 172, 177, 41, 24, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 172, 177, 41, 25, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 172, 177, 41, 26, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 172, 177, 41, 27, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 172, 177, 41, 28, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 172, 178, 41, 17, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 172, 178, 41, 19, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 172, 178, 41, 20, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 172, 178, 41, 21, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 172, 178, 41, 22, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 172, 178, 41, 23, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 172, 178, 41, 24, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 172, 178, 41, 25, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 172, 178, 41, 26, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 172, 178, 41, 27, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 172, 178, 42, 28, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 172, 178, 42, 27, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 172, 178, 42, 26, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 172, 178, 42, 25, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 172, 178, 42, 24, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 172, 178, 42, 23, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 172, 178, 42, 22, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 172, 178, 42, 21, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 172, 178, 42, 20, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 172, 178, 42, 19, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 172, 178, 42, 18, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 172, 178, 42, 17, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 172, 177, 42, 17, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 172, 177, 42, 18, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 172, 177, 42, 19, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 172, 177, 42, 20, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 172, 177, 42, 21, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 172, 177, 42, 22, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 172, 177, 42, 23, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 172, 177, 42, 24, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 172, 177, 42, 25, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 172, 177, 42, 26, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 172, 177, 42, 27, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 172, 177, 42, 28, '#saldo_anterior_debito')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 172, 178, 43, 21, 'L[44]->vlrexanter+L[45]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 172, 177, 43, 17, 'L[44]->vlrexatual+L[45]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 172, 177, 43, 18, 'L[44]->vlrexatual+L[45]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 172, 177, 43, 19, 'L[44]->vlrexatual+L[45]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 172, 177, 43, 20, 'L[44]->vlrexatual+L[45]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 172, 177, 43, 21, 'L[44]->vlrexatual+L[45]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 172, 177, 43, 22, 'L[44]->vlrexatual+L[45]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 172, 177, 43, 23, 'L[44]->vlrexatual+L[45]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 172, 177, 43, 24, 'L[44]->vlrexatual+L[45]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 172, 177, 43, 25, 'L[44]->vlrexatual+L[45]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 172, 177, 43, 26, 'L[44]->vlrexatual+L[45]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 172, 177, 43, 27, 'L[44]->vlrexatual+L[45]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 172, 177, 43, 28, 'L[44]->vlrexatual+L[45]->vlrexatual')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 172, 178, 43, 17, 'L[44]->vlrexanter+L[45]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 172, 178, 43, 18, 'L[44]->vlrexanter+L[45]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 172, 178, 43, 19, 'L[44]->vlrexanter+L[45]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 172, 178, 43, 20, 'L[44]->vlrexanter+L[45]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 172, 178, 43, 25, 'L[44]->vlrexanter+L[45]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 172, 178, 43, 26, 'L[44]->vlrexanter+L[45]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 172, 178, 43, 27, 'L[44]->vlrexanter+L[45]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 172, 178, 43, 28, 'L[44]->vlrexanter+L[45]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 172, 178, 43, 24, 'L[44]->vlrexanter+L[45]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 172, 178, 43, 23, 'L[44]->vlrexanter+L[45]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 172, 178, 43, 22, 'L[44]->vlrexanter+L[45]->vlrexanter')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 172, 178, 44, 21, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 172, 178, 44, 22, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 172, 178, 44, 23, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 172, 178, 44, 24, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 172, 178, 44, 25, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 172, 178, 44, 26, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 172, 178, 44, 27, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 172, 178, 44, 28, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 172, 177, 44, 26, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 172, 177, 44, 27, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 172, 177, 44, 25, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 172, 177, 44, 28, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 172, 178, 44, 17, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 172, 177, 44, 24, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 172, 177, 44, 23, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 172, 177, 44, 22, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 172, 177, 44, 21, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 172, 177, 44, 20, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 172, 177, 44, 19, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 172, 178, 44, 18, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 172, 177, 44, 18, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 172, 177, 44, 17, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 172, 178, 44, 19, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 172, 178, 44, 20, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 172, 177, 45, 19, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 172, 177, 45, 17, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 172, 177, 45, 18, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 172, 177, 45, 20, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 172, 177, 45, 21, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 172, 177, 45, 22, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 172, 177, 45, 23, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 172, 177, 45, 24, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 172, 177, 45, 25, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 172, 177, 45, 26, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 172, 177, 45, 27, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 172, 177, 45, 28, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 172, 178, 45, 17, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 172, 178, 45, 18, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 172, 178, 45, 19, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 172, 178, 45, 20, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 172, 178, 45, 21, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 172, 178, 45, 25, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 172, 178, 45, 26, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 172, 178, 45, 27, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 172, 178, 45, 28, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 172, 178, 45, 22, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 172, 178, 45, 23, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 172, 178, 45, 24, '#saldo_final')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 172, 178, 46, 27, 'F[24] + F[33] + F[38] + F[43]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 172, 177, 46, 24, 'F[24] + F[33] + F[38] + F[43]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 172, 177, 46, 23, 'F[24] + F[33] + F[38] + F[43]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 172, 177, 46, 22, 'F[24] + F[33] + F[38] + F[43]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 172, 177, 46, 21, 'F[24] + F[33] + F[38] + F[43]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 172, 177, 46, 20, 'F[24] + F[33] + F[38] + F[43]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 172, 177, 46, 19, 'F[24] + F[33] + F[38] + F[43]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 172, 177, 46, 18, 'F[24] + F[33] + F[38] + F[43]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 172, 177, 46, 17, 'F[24] + F[33] + F[38] + F[43]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 172, 177, 46, 26, 'F[24] + F[33] + F[38] + F[43]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 172, 177, 46, 27, 'F[24] + F[33] + F[38] + F[43]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 172, 177, 46, 28, 'F[24] + F[33] + F[38] + F[43]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 172, 178, 46, 17, 'F[24] + F[33] + F[38] + F[43]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 172, 178, 46, 18, 'F[24] + F[33] + F[38] + F[43]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 172, 178, 46, 19, 'F[24] + F[33] + F[38] + F[43]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 172, 178, 46, 20, 'F[24] + F[33] + F[38] + F[43]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 172, 178, 46, 28, 'F[24] + F[33] + F[38] + F[43]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 172, 178, 46, 21, 'F[24] + F[33] + F[38] + F[43]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 172, 178, 46, 22, 'F[24] + F[33] + F[38] + F[43]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 172, 178, 46, 23, 'F[24] + F[33] + F[38] + F[43]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 172, 178, 46, 24, 'F[24] + F[33] + F[38] + F[43]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 172, 178, 46, 25, 'F[24] + F[33] + F[38] + F[43]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 172, 177, 46, 25, 'F[24] + F[33] + F[38] + F[43]')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 172, 178, 46, 26, 'F[24] + F[33] + F[38] + F[43]');

            insert into orcparamseqfiltropadrao values
               (nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 2, 2017, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
            <filter>
            <contas>
            <conta estrutural=\"400000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            <conta estrutural=\"900000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            </contas>
            <orgao operador=\"in\" valor=\"\" id=\"orgao\"/>
            <unidade operador=\"in\" valor=\"\" id=\"unidade\"/>
            <funcao operador=\"in\" valor=\"\" id=\"funcao\"/>
            <subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/>
            <programa operador=\"in\" valor=\"\" id=\"programa\"/>
            <projativ operador=\"in\" valor=\"\" id=\"projativ\"/>
            <recurso operador=\"in\" valor=\"\" id=\"recurso\"/>
            <recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/>
            <observacao valor=\"\"/>
            <desdobrarlinha valor=\"false\"/>
            </filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 2, 2016, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
            <filter>
            <contas>
            <conta estrutural=\"400000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            <conta estrutural=\"900000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            </contas>
            <orgao operador=\"in\" valor=\"\" id=\"orgao\"/>
            <unidade operador=\"in\" valor=\"\" id=\"unidade\"/>
            <funcao operador=\"in\" valor=\"\" id=\"funcao\"/>
            <subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/>
            <programa operador=\"in\" valor=\"\" id=\"programa\"/>
            <projativ operador=\"in\" valor=\"\" id=\"projativ\"/>
            <recurso operador=\"in\" valor=\"\" id=\"recurso\"/>
            <recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/>
            <observacao valor=\"\"/>
            <desdobrarlinha valor=\"false\"/>
            </filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 2, 2015, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
            <filter>
            <contas>
            <conta estrutural=\"400000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            <conta estrutural=\"900000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            </contas>
            <orgao operador=\"in\" valor=\"\" id=\"orgao\"/>
            <unidade operador=\"in\" valor=\"\" id=\"unidade\"/>
            <funcao operador=\"in\" valor=\"\" id=\"funcao\"/>
            <subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/>
            <programa operador=\"in\" valor=\"\" id=\"programa\"/>
            <projativ operador=\"in\" valor=\"\" id=\"projativ\"/>
            <recurso operador=\"in\" valor=\"\" id=\"recurso\"/>
            <recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/>
            <observacao valor=\"\"/>
            <desdobrarlinha valor=\"false\"/>
            </filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 4, 2016, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
            <filter>
            <contas>
            <conta estrutural=\"400000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            <conta estrutural=\"900000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            </contas>
            <orgao operador=\"in\" valor=\"\" id=\"orgao\"/>
            <unidade operador=\"in\" valor=\"\" id=\"unidade\"/>
            <funcao operador=\"in\" valor=\"\" id=\"funcao\"/>
            <subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/>
            <programa operador=\"in\" valor=\"\" id=\"programa\"/>
            <projativ operador=\"in\" valor=\"\" id=\"projativ\"/>
            <recurso operador=\"in\" valor=\"\" id=\"recurso\"/>
            <recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/>
            <observacao valor=\"\"/>
            <desdobrarlinha valor=\"false\"/>
            </filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 4, 2017, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
            <filter>
            <contas>
            <conta estrutural=\"400000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            <conta estrutural=\"900000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            </contas>
            <orgao operador=\"in\" valor=\"\" id=\"orgao\"/>
            <unidade operador=\"in\" valor=\"\" id=\"unidade\"/>
            <funcao operador=\"in\" valor=\"\" id=\"funcao\"/>
            <subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/>
            <programa operador=\"in\" valor=\"\" id=\"programa\"/>
            <projativ operador=\"in\" valor=\"\" id=\"projativ\"/>
            <recurso operador=\"in\" valor=\"\" id=\"recurso\"/>
            <recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/>
            <observacao valor=\"\"/>
            <desdobrarlinha valor=\"false\"/>
            </filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 4, 2015, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
            <filter>
            <contas>
            <conta estrutural=\"400000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            <conta estrutural=\"900000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            </contas>
            <orgao operador=\"in\" valor=\"\" id=\"orgao\"/>
            <unidade operador=\"in\" valor=\"\" id=\"unidade\"/>
            <funcao operador=\"in\" valor=\"\" id=\"funcao\"/>
            <subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/>
            <programa operador=\"in\" valor=\"\" id=\"programa\"/>
            <projativ operador=\"in\" valor=\"\" id=\"projativ\"/>
            <recurso operador=\"in\" valor=\"\" id=\"recurso\"/>
            <recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/>
            <observacao valor=\"\"/>
            <desdobrarlinha valor=\"false\"/>
            </filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 5, 2017, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
            <filter>
            <contas>
            <conta estrutural=\"400000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            <conta estrutural=\"900000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            </contas>
            <orgao operador=\"in\" valor=\"\" id=\"orgao\"/>
            <unidade operador=\"in\" valor=\"\" id=\"unidade\"/>
            <funcao operador=\"in\" valor=\"\" id=\"funcao\"/>
            <subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/>
            <programa operador=\"in\" valor=\"\" id=\"programa\"/>
            <projativ operador=\"in\" valor=\"\" id=\"projativ\"/>
            <recurso operador=\"in\" valor=\"\" id=\"recurso\"/>
            <recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/>
            <observacao valor=\"\"/>
            <desdobrarlinha valor=\"false\"/>
            </filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 5, 2016, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
            <filter>
            <contas>
            <conta estrutural=\"400000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            <conta estrutural=\"900000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            </contas>
            <orgao operador=\"in\" valor=\"\" id=\"orgao\"/>
            <unidade operador=\"in\" valor=\"\" id=\"unidade\"/>
            <funcao operador=\"in\" valor=\"\" id=\"funcao\"/>
            <subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/>
            <programa operador=\"in\" valor=\"\" id=\"programa\"/>
            <projativ operador=\"in\" valor=\"\" id=\"projativ\"/>
            <recurso operador=\"in\" valor=\"\" id=\"recurso\"/>
            <recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/>
            <observacao valor=\"\"/>
            <desdobrarlinha valor=\"false\"/>
            </filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 5, 2015, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
            <filter>
            <contas>
            <conta estrutural=\"400000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            <conta estrutural=\"900000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            </contas>
            <orgao operador=\"in\" valor=\"\" id=\"orgao\"/>
            <unidade operador=\"in\" valor=\"\" id=\"unidade\"/>
            <funcao operador=\"in\" valor=\"\" id=\"funcao\"/>
            <subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/>
            <programa operador=\"in\" valor=\"\" id=\"programa\"/>
            <projativ operador=\"in\" valor=\"\" id=\"projativ\"/>
            <recurso operador=\"in\" valor=\"\" id=\"recurso\"/>
            <recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/>
            <observacao valor=\"\"/>
            <desdobrarlinha valor=\"false\"/>
            </filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 6, 2015, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
            <filter>
            <contas>
            <conta estrutural=\"400000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            <conta estrutural=\"900000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            </contas>
            <orgao operador=\"in\" valor=\"\" id=\"orgao\"/>
            <unidade operador=\"in\" valor=\"\" id=\"unidade\"/>
            <funcao operador=\"in\" valor=\"\" id=\"funcao\"/>
            <subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/>
            <programa operador=\"in\" valor=\"\" id=\"programa\"/>
            <projativ operador=\"in\" valor=\"\" id=\"projativ\"/>
            <recurso operador=\"in\" valor=\"\" id=\"recurso\"/>
            <recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/>
            <observacao valor=\"\"/>
            <desdobrarlinha valor=\"false\"/>
            </filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 6, 2017, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
            <filter>
            <contas>
            <conta estrutural=\"400000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            <conta estrutural=\"900000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            </contas>
            <orgao operador=\"in\" valor=\"\" id=\"orgao\"/>
            <unidade operador=\"in\" valor=\"\" id=\"unidade\"/>
            <funcao operador=\"in\" valor=\"\" id=\"funcao\"/>
            <subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/>
            <programa operador=\"in\" valor=\"\" id=\"programa\"/>
            <projativ operador=\"in\" valor=\"\" id=\"projativ\"/>
            <recurso operador=\"in\" valor=\"\" id=\"recurso\"/>
            <recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/>
            <observacao valor=\"\"/>
            <desdobrarlinha valor=\"false\"/>
            </filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 6, 2016, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
            <filter>
            <contas>
            <conta estrutural=\"400000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            <conta estrutural=\"900000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            </contas>
            <orgao operador=\"in\" valor=\"\" id=\"orgao\"/>
            <unidade operador=\"in\" valor=\"\" id=\"unidade\"/>
            <funcao operador=\"in\" valor=\"\" id=\"funcao\"/>
            <subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/>
            <programa operador=\"in\" valor=\"\" id=\"programa\"/>
            <projativ operador=\"in\" valor=\"\" id=\"projativ\"/>
            <recurso operador=\"in\" valor=\"\" id=\"recurso\"/>
            <recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/>
            <observacao valor=\"\"/>
            <desdobrarlinha valor=\"false\"/>
            </filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 7, 2015, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
            <filter>
            <contas>
            <conta estrutural=\"400000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            <conta estrutural=\"900000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            </contas>
            <orgao operador=\"in\" valor=\"\" id=\"orgao\"/>
            <unidade operador=\"in\" valor=\"\" id=\"unidade\"/>
            <funcao operador=\"in\" valor=\"\" id=\"funcao\"/>
            <subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/>
            <programa operador=\"in\" valor=\"\" id=\"programa\"/>
            <projativ operador=\"in\" valor=\"\" id=\"projativ\"/>
            <recurso operador=\"in\" valor=\"\" id=\"recurso\"/>
            <recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/>
            <observacao valor=\"\"/>
            <desdobrarlinha valor=\"false\"/>
            </filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 7, 2016, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
            <filter>
            <contas>
            <conta estrutural=\"400000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            <conta estrutural=\"900000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            </contas>
            <orgao operador=\"in\" valor=\"\" id=\"orgao\"/>
            <unidade operador=\"in\" valor=\"\" id=\"unidade\"/>
            <funcao operador=\"in\" valor=\"\" id=\"funcao\"/>
            <subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/>
            <programa operador=\"in\" valor=\"\" id=\"programa\"/>
            <projativ operador=\"in\" valor=\"\" id=\"projativ\"/>
            <recurso operador=\"in\" valor=\"\" id=\"recurso\"/>
            <recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/>
            <observacao valor=\"\"/>
            <desdobrarlinha valor=\"false\"/>
            </filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 7, 2017, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
            <filter>
            <contas>
            <conta estrutural=\"400000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            <conta estrutural=\"900000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            </contas>
            <orgao operador=\"in\" valor=\"\" id=\"orgao\"/>
            <unidade operador=\"in\" valor=\"\" id=\"unidade\"/>
            <funcao operador=\"in\" valor=\"\" id=\"funcao\"/>
            <subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/>
            <programa operador=\"in\" valor=\"\" id=\"programa\"/>
            <projativ operador=\"in\" valor=\"\" id=\"projativ\"/>
            <recurso operador=\"in\" valor=\"\" id=\"recurso\"/>
            <recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/>
            <observacao valor=\"\"/>
            <desdobrarlinha valor=\"false\"/>
            </filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 8, 2017, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
            <filter>
            <contas>
            <conta estrutural=\"400000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            <conta estrutural=\"900000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            </contas>
            <orgao operador=\"in\" valor=\"\" id=\"orgao\"/>
            <unidade operador=\"in\" valor=\"\" id=\"unidade\"/>
            <funcao operador=\"in\" valor=\"\" id=\"funcao\"/>
            <subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/>
            <programa operador=\"in\" valor=\"\" id=\"programa\"/>
            <projativ operador=\"in\" valor=\"\" id=\"projativ\"/>
            <recurso operador=\"in\" valor=\"\" id=\"recurso\"/>
            <recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/>
            <observacao valor=\"\"/>
            <desdobrarlinha valor=\"false\"/>
            </filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 8, 2015, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
            <filter>
            <contas>
            <conta estrutural=\"400000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            <conta estrutural=\"900000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            </contas>
            <orgao operador=\"in\" valor=\"\" id=\"orgao\"/>
            <unidade operador=\"in\" valor=\"\" id=\"unidade\"/>
            <funcao operador=\"in\" valor=\"\" id=\"funcao\"/>
            <subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/>
            <programa operador=\"in\" valor=\"\" id=\"programa\"/>
            <projativ operador=\"in\" valor=\"\" id=\"projativ\"/>
            <recurso operador=\"in\" valor=\"\" id=\"recurso\"/>
            <recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/>
            <observacao valor=\"\"/>
            <desdobrarlinha valor=\"false\"/>
            </filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 8, 2016, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
            <filter>
            <contas>
            <conta estrutural=\"400000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            <conta estrutural=\"900000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            </contas>
            <orgao operador=\"in\" valor=\"\" id=\"orgao\"/>
            <unidade operador=\"in\" valor=\"\" id=\"unidade\"/>
            <funcao operador=\"in\" valor=\"\" id=\"funcao\"/>
            <subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/>
            <programa operador=\"in\" valor=\"\" id=\"programa\"/>
            <projativ operador=\"in\" valor=\"\" id=\"projativ\"/>
            <recurso operador=\"in\" valor=\"\" id=\"recurso\"/>
            <recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/>
            <observacao valor=\"\"/>
            <desdobrarlinha valor=\"false\"/>
            </filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 9, 2016, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
            <filter>
            <contas>
            <conta estrutural=\"400000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            <conta estrutural=\"900000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            </contas>
            <orgao operador=\"in\" valor=\"\" id=\"orgao\"/>
            <unidade operador=\"in\" valor=\"\" id=\"unidade\"/>
            <funcao operador=\"in\" valor=\"\" id=\"funcao\"/>
            <subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/>
            <programa operador=\"in\" valor=\"\" id=\"programa\"/>
            <projativ operador=\"in\" valor=\"\" id=\"projativ\"/>
            <recurso operador=\"in\" valor=\"\" id=\"recurso\"/>
            <recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/>
            <observacao valor=\"\"/>
            <desdobrarlinha valor=\"false\"/>
            </filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 9, 2015, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
            <filter>
            <contas>
            <conta estrutural=\"400000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            <conta estrutural=\"900000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            </contas>
            <orgao operador=\"in\" valor=\"\" id=\"orgao\"/>
            <unidade operador=\"in\" valor=\"\" id=\"unidade\"/>
            <funcao operador=\"in\" valor=\"\" id=\"funcao\"/>
            <subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/>
            <programa operador=\"in\" valor=\"\" id=\"programa\"/>
            <projativ operador=\"in\" valor=\"\" id=\"projativ\"/>
            <recurso operador=\"in\" valor=\"\" id=\"recurso\"/>
            <recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/>
            <observacao valor=\"\"/>
            <desdobrarlinha valor=\"false\"/>
            </filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 9, 2017, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
            <filter>
            <contas>
            <conta estrutural=\"400000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            <conta estrutural=\"900000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
            </contas>
            <orgao operador=\"in\" valor=\"\" id=\"orgao\"/>
            <unidade operador=\"in\" valor=\"\" id=\"unidade\"/>
            <funcao operador=\"in\" valor=\"\" id=\"funcao\"/>
            <subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/>
            <programa operador=\"in\" valor=\"\" id=\"programa\"/>
            <projativ operador=\"in\" valor=\"\" id=\"projativ\"/>
            <recurso operador=\"in\" valor=\"\" id=\"recurso\"/>
            <recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/>
            <observacao valor=\"\"/>
            <desdobrarlinha valor=\"false\"/>
            </filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 11, 2016, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"451100000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 11, 2017, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"451100000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 11, 2015, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"451100000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 12, 2016, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"451200000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 12, 2017, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"451200000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 12, 2015, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"451200000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 13, 2015, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"451300000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 13, 2017, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"451300000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 13, 2016, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"451300000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 14, 2015, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"451400000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 14, 2016, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"451400000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 14, 2017, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"451400000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 16, 2016, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 16, 2015, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 16, 2017, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 17, 2016, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 17, 2017, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 17, 2015, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 18, 2015, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"218800000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 18, 2017, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"218800000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 18, 2016, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"218800000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 21, 2015, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
            <filter>
             <contas>
              <conta estrutural=\"111000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
              <conta estrutural=\"114100000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
              <conta estrutural=\"114200000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
              <conta estrutural=\"114300000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
             </contas>
             <orgao operador=\"in\" valor=\"\" id=\"orgao\"/>
             <unidade operador=\"in\" valor=\"\" id=\"unidade\"/>
             <funcao operador=\"in\" valor=\"\" id=\"funcao\"/>
             <subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/>
             <programa operador=\"in\" valor=\"\" id=\"programa\"/>
             <projativ operador=\"in\" valor=\"\" id=\"projativ\"/>
             <recurso operador=\"in\" valor=\"\" id=\"recurso\"/>
             <recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/>
             <observacao valor=\"\"/>
             <desdobrarlinha valor=\"false\"/>
            </filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 21, 2016, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
            <filter>
             <contas>
              <conta estrutural=\"111000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
              <conta estrutural=\"114100000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
              <conta estrutural=\"114200000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
              <conta estrutural=\"114300000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
             </contas>
             <orgao operador=\"in\" valor=\"\" id=\"orgao\"/>
             <unidade operador=\"in\" valor=\"\" id=\"unidade\"/>
             <funcao operador=\"in\" valor=\"\" id=\"funcao\"/>
             <subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/>
             <programa operador=\"in\" valor=\"\" id=\"programa\"/>
             <projativ operador=\"in\" valor=\"\" id=\"projativ\"/>
             <recurso operador=\"in\" valor=\"\" id=\"recurso\"/>
             <recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/>
             <observacao valor=\"\"/>
             <desdobrarlinha valor=\"false\"/>
            </filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 21, 2017, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
            <filter>
             <contas>
              <conta estrutural=\"111000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
              <conta estrutural=\"114100000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
              <conta estrutural=\"114200000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
              <conta estrutural=\"114300000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
             </contas>
             <orgao operador=\"in\" valor=\"\" id=\"orgao\"/>
             <unidade operador=\"in\" valor=\"\" id=\"unidade\"/>
             <funcao operador=\"in\" valor=\"\" id=\"funcao\"/>
             <subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/>
             <programa operador=\"in\" valor=\"\" id=\"programa\"/>
             <projativ operador=\"in\" valor=\"\" id=\"projativ\"/>
             <recurso operador=\"in\" valor=\"\" id=\"recurso\"/>
             <recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/>
             <observacao valor=\"\"/>
             <desdobrarlinha valor=\"false\"/>
            </filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 22, 2015, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"113500000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 22, 2016, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"113500000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 22, 2017, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"113500000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 25, 2016, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 25, 2015, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 25, 2017, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 27, 2017, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 27, 2015, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 27, 2016, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 28, 2017, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 28, 2015, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 28, 2016, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 29, 2016, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 29, 2015, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 29, 2017, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 30, 2017, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 30, 2015, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 30, 2016, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 31, 2015, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 31, 2016, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 31, 2017, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 32, 2017, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 32, 2015, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 32, 2016, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 34, 2015, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"351100000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 34, 2017, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"351100000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 34, 2016, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"351100000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 35, 2016, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"351200000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 35, 2017, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"351200000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 35, 2015, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"351200000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 36, 2017, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"351300000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 36, 2015, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"351300000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 36, 2016, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"351300000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 37, 2016, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"351400000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 37, 2017, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"351400000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 37, 2015, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"351400000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 39, 2017, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 39, 2015, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 39, 2016, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 40, 2016, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 40, 2017, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 40, 2015, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"300000000000000\" nivel=\"1\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 41, 2015, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"218800000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 41, 2016, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"218800000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 41, 2017, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"218800000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 44, 2016, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
            <filter>
             <contas>
              <conta estrutural=\"111000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
              <conta estrutural=\"114100000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
              <conta estrutural=\"114200000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
              <conta estrutural=\"114300000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
             </contas>
             <orgao operador=\"in\" valor=\"\" id=\"orgao\"/>
             <unidade operador=\"in\" valor=\"\" id=\"unidade\"/>
             <funcao operador=\"in\" valor=\"\" id=\"funcao\"/>
             <subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/>
             <programa operador=\"in\" valor=\"\" id=\"programa\"/>
             <projativ operador=\"in\" valor=\"\" id=\"projativ\"/>
             <recurso operador=\"in\" valor=\"\" id=\"recurso\"/>
             <recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/>
             <observacao valor=\"\"/>
             <desdobrarlinha valor=\"false\"/>
            </filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 44, 2017, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
            <filter>
             <contas>
              <conta estrutural=\"111000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
              <conta estrutural=\"114100000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
              <conta estrutural=\"114200000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
              <conta estrutural=\"114300000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
             </contas>
             <orgao operador=\"in\" valor=\"\" id=\"orgao\"/>
             <unidade operador=\"in\" valor=\"\" id=\"unidade\"/>
             <funcao operador=\"in\" valor=\"\" id=\"funcao\"/>
             <subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/>
             <programa operador=\"in\" valor=\"\" id=\"programa\"/>
             <projativ operador=\"in\" valor=\"\" id=\"projativ\"/>
             <recurso operador=\"in\" valor=\"\" id=\"recurso\"/>
             <recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/>
             <observacao valor=\"\"/>
             <desdobrarlinha valor=\"false\"/>
            </filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 44, 2015, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
            <filter>
             <contas>
              <conta estrutural=\"111000000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
              <conta estrutural=\"114100000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
              <conta estrutural=\"114200000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
              <conta estrutural=\"114300000000000\" nivel=\"\" exclusao=\"false\" indicador=\"\"/>
             </contas>
             <orgao operador=\"in\" valor=\"\" id=\"orgao\"/>
             <unidade operador=\"in\" valor=\"\" id=\"unidade\"/>
             <funcao operador=\"in\" valor=\"\" id=\"funcao\"/>
             <subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/>
             <programa operador=\"in\" valor=\"\" id=\"programa\"/>
             <projativ operador=\"in\" valor=\"\" id=\"projativ\"/>
             <recurso operador=\"in\" valor=\"\" id=\"recurso\"/>
             <recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/>
             <observacao valor=\"\"/>
             <desdobrarlinha valor=\"false\"/>
            </filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 45, 2016, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"113500000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 45, 2015, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"113500000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>')
              ,(nextval('orcparamelementospadrao_o132_sequencial_seq'), 172, 45, 2017, '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><filter><contas><conta estrutural=\"113500000000000\" nivel=\"\" exclusao=\"false\" /></contas><orgao operador=\"in\" valor=\"\" id=\"orgao\"/><unidade operador=\"in\" valor=\"\" id=\"unidade\"/><funcao operador=\"in\" valor=\"\" id=\"funcao\"/><subfuncao operador=\"in\" valor=\"\" id=\"subfuncao\"/><programa operador=\"in\" valor=\"\" id=\"programa\"/><projativ operador=\"in\" valor=\"\" id=\"projativ\"/><recurso operador=\"in\" valor=\"\" id=\"recurso\"/><recursocontalinha numerolinha=\"\" id=\"recursocontalinha\"/><observacao valor=\"\"/><desdobrarlinha valor=\"false\"/></filter>');
        ");
    }

    public function down()
    {
        $this->execute("
            delete from orcparamseqfiltropadrao where o132_orcparamrel = 172;
            delete from orcparamseqorcparamseqcoluna where o116_codparamrel = 172;
            delete from orcparamseq where o69_codparamrel = 172;
            delete from orcparamrelperiodos where o113_orcparamrel = 172;
            delete from orcparamrel where o42_codparrel = 172;
        ");
    }
}
