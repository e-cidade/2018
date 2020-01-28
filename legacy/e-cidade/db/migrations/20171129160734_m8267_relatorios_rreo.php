<?php

use Classes\PostgresMigration;

class M8267RelatoriosRreo extends PostgresMigration
{

    public function up ()
    {

        $this->upNotasExplicativasPadrao();
        $this->upRelatorioAnexoXI();
    }


    public function down ()
    {

        $this->downNotaExplicativaPadrao();
        $this->downRelatorioAnexoXI();
    }


    private function upNotasExplicativasPadrao()
    {

        $notaExplicativaPadrao = "FONTE: Sistema E-Cidade, Unidade Responsável: [nome_departamento]. Emissão: [data_emissao], às [hora_emissao]. Assinado Digitalmente no dia [data_emissao], às [hora_emissao].";
        $this->execute("update orcamento.orcparamrel set o42_notapadrao = '{$notaExplicativaPadrao}' where o42_codparrel in (106, 160)");
        $notaExplicativaPadrao .= "\n\n1  Operações de Crédito descritas na CF, art. 167, inciso III";
        $this->execute("update orcamento.orcparamrel set o42_notapadrao = '{$notaExplicativaPadrao}' where o42_codparrel in (159)");

    }

    private function downNotaExplicativaPadrao()
    {
        $notaExplicativaPadrao = "FONTE: Sistema E-Cidade, Unidade Responsável: [nome_departamento]. Emissão: [data_emissao], às [hora_emissao].";
        $this->execute("update orcamento.orcparamrel set o42_notapadrao = '{$notaExplicativaPadrao}' where o42_codparrel in (106, 159, 160)");
    }

    private function downRelatorioAnexoXI()
    {
        $this->execute("delete from orcparamseqfiltropadrao where o132_orcparamrel = 173;");
        $this->execute("delete from orcparamseqorcparamseqcoluna where o116_codparamrel = 173;");
        $this->execute("delete from orcparamseq where o69_codparamrel = 173;");
        $this->execute("delete from orcparamrelperiodos where o113_orcparamrel = 173;");
        $this->execute("delete from orcparamrel where o42_codparrel = 173;");
    }

    private function upRelatorioAnexoXI()
    {
        $notaExplicativaPadrao = "FONTE: Sistema E-Cidade, Unidade Responsável: [nome_departamento]. Emissão: [data_emissao], às [hora_emissao]. Assinado Digitalmente no dia [data_emissao], às [hora_emissao].";
        $this->execute("insert into orcparamrel values (173, 'RREO - ANEXO XI - 2017', 4, '{$notaExplicativaPadrao}');");
        $this->execute("insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 11, 173);");

        $this->execute(
<<<SQLUP
insert into orcparamseq values (173, 1, 'RECEITAS DE ALIENAÇÃO DE ATIVOS (I)', 1, 1, 1, false, false, false, false, false, 'RECEITAS DE ALIENAÇÃO DE ATIVOS (I)', false, true, 1, 1, '', false, 1);
insert into orcparamseq values (173, 2, 'Receita de Alienação de Bens Móveis', 1, 0, 1, false, false, false, false, false, 'Receita de Alienação de Bens Móveis', true, false, 2, 2, '', false, 1);
insert into orcparamseq values (173, 3, 'Receita de Alienação de Bens Imóveis', 1, 0, 1, false, false, false, false, false, 'Receita de Alienação de Bens Imóveis', true, false, 3, 2, '', false, 1);
insert into orcparamseq values (173, 4, 'APLICAÇÃO DOS RECURSOS DA ALIENAÇÃO DE ATIVOS (II)', 1, 1, 1, false, false, false, false, false, 'APLICAÇÃO DOS RECURSOS DA ALIENAÇÃO DE ATIVOS (II)', false, true, 4, 1, '', false, 2);
insert into orcparamseq values (173, 5, 'Despesas de Capital', 1, 1, 1, false, false, false, false, false, 'Despesas de Capital', false, true, 5, 2, '', false, 2);
insert into orcparamseq values (173, 6, 'Investimentos', 1, 0, 1, false, false, false, false, false, 'Investimentos', true, false, 6, 3, '', false, 2);
insert into orcparamseq values (173, 7, 'Inversões Financeiras', 1, 0, 1, false, false, false, false, false, 'Inversões Financeiras', true, false, 7, 3, '', false, 2);
insert into orcparamseq values (173, 8, 'Amortização da Dívida', 1, 0, 1, false, false, false, false, false, 'Amortização da Dívida', true, false, 8, 3, '', false, 2);
insert into orcparamseq values (173, 9, 'Despesas Correntes dos Regimes de Previdência', 1, 1, 1, false, false, false, false, false, 'Despesas Correntes dos Regimes de Previdência', false, true, 9, 2, '', false, 2);
insert into orcparamseq values (173, 10, 'Regime Próprio dos Servidores Públicos', 1, 0, 1, false, false, false, false, false, 'Regime Próprio dos Servidores Públicos', true, false, 10, 3, '', false, 2);
insert into orcparamseq values (173, 11, 'VALOR (III)', 1, 1, 1, false, false, false, false, false, 'VALOR (III)', true, true, 11, 1, '', false, 0);
SQLUP
        );

        $this->execute(
<<<SQL_UP
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 173, 26, 1, 11, 'L[2]->prevatu+L[3]->prevatu');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 173, 28, 2, 11, 'L[2]->recatebim+L[3]->recatebim');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 173, 184, 3, 11, 'L[2]->saldo+L[3]->saldo');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 173, 26, 1, 11, '#saldo_inicial_prevadic');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 173, 28, 2, 11, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 173, 184, 3, 11, '(#saldo_inicial_prevadic - #saldo_arrecadado)');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 173, 26, 1, 11, '#saldo_inicial_prevadic');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 173, 28, 2, 11, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 173, 184, 3, 11, '(#saldo_inicial_prevadic - #saldo_arrecadado)');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 173, 35, 5, 11, 'F[5]+F[9]');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 173, 174, 2, 11, 'F[5]+F[9]');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 173, 175, 3, 11, 'F[5]+F[9]');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 173, 176, 4, 11, 'F[5]+F[9]');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 173, 184, 7, 11, 'F[5]+F[9]');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 173, 154, 1, 11, 'F[5]+F[9]');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 173, 160, 6, 11, 'F[5]+F[9]');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 173, 35, 5, 11, 'L[6]->insc_rp_np+L[7]->insc_rp_np+L[8]->insc_rp_np');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 173, 174, 2, 11, 'L[6]->despemp+L[7]->despemp+L[8]->despemp');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 173, 175, 3, 11, 'L[6]->despliq+L[7]->despliq+L[8]->despliq');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 173, 176, 4, 11, 'L[6]->desppag+L[7]->desppag+L[8]->desppag');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 173, 184, 7, 11, 'L[6]->saldo+L[7]->saldo+L[8]->saldo');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 173, 154, 1, 11, 'L[6]->dot_atual+L[7]->dot_atual+L[8]->dot_atual');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 173, 160, 6, 11, 'L[6]->rp_apagar+L[7]->rp_apagar+L[8]->rp_apagar');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 173, 35, 5, 11, '(#empenhado_acumulado - #anulado_acumulado) - #liquidado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 173, 174, 2, 11, '(#empenhado_acumulado - #anulado_acumulado)');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 173, 175, 3, 11, '#liquidado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 173, 176, 4, 11, '#pago_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 173, 184, 7, 11, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 173, 154, 1, 11, '(#dot_ini + #suplementado_acumulado) - #reduzido_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 173, 160, 6, 11, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 173, 35, 5, 11, '(#empenhado_acumulado - #anulado_acumulado) - #liquidado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 173, 174, 2, 11, '(#empenhado_acumulado - #anulado_acumulado)');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 173, 175, 3, 11, '#liquidado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 173, 176, 4, 11, '#pago_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 173, 184, 7, 11, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 173, 154, 1, 11, '(#dot_ini + #suplementado_acumulado) - #reduzido_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 173, 160, 6, 11, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 173, 35, 5, 11, '(#empenhado_acumulado - #anulado_acumulado) - #liquidado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 173, 174, 2, 11, '(#empenhado_acumulado - #anulado_acumulado)');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 173, 175, 3, 11, '#liquidado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 173, 176, 4, 11, '#pago_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 173, 184, 7, 11, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 173, 154, 1, 11, '(#dot_ini + #suplementado_acumulado) - #reduzido_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 173, 160, 6, 11, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 173, 35, 5, 11, 'L[10]->insc_rp_np');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 173, 174, 2, 11, 'L[10]->despemp');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 173, 175, 3, 11, 'L[10]->despliq');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 173, 176, 4, 11, 'L[10]->desppag');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 173, 184, 7, 11, 'L[10]->saldo');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 173, 154, 1, 11, 'L[10]->dot_atual');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 173, 160, 6, 11, 'L[10]->rp_apagar');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 173, 35, 5, 11, '(#empenhado_acumulado - #anulado_acumulado) - #liquidado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 173, 174, 2, 11, '(#empenhado_acumulado - #anulado_acumulado)');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 173, 175, 3, 11, '#liquidado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 173, 176, 4, 11, '#pago_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 173, 184, 7, 11, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 173, 154, 1, 11, '(#dot_ini + #suplementado_acumulado) - #reduzido_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 173, 160, 6, 11, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 173, 177, 2, 11, 'L[1]->recatebim - (L[4]->desppag + L[4]->rp_apagar )');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 173, 178, 1, 11, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 173, 184, 3, 11, 'L[11]->vlrexanter + L[11]->vlrexatual');
SQL_UP
        );

        $this->execute(
            <<<SQLUP
insert into orcparamseqfiltropadrao values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 173, 2, 2015, '<?xml version="1.0" encoding="ISO-8859-1"?><filter><contas><conta estrutural="422100000000000" nivel="" exclusao="false" /><conta estrutural="922100000000000" nivel="" exclusao="false" /></contas><orgao operador="in" valor="" id="orgao"/><unidade operador="in" valor="" id="unidade"/><funcao operador="in" valor="" id="funcao"/><subfuncao operador="in" valor="" id="subfuncao"/><programa operador="in" valor="" id="programa"/><projativ operador="in" valor="" id="projativ"/><recurso operador="in" valor="" id="recurso"/><recursocontalinha numerolinha="" id="recursocontalinha"/><observacao valor=""/><desdobrarlinha valor="false"/></filter>');
insert into orcparamseqfiltropadrao values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 173, 2, 2016, '<?xml version="1.0" encoding="ISO-8859-1"?><filter><contas><conta estrutural="422100000000000" nivel="" exclusao="false" /><conta estrutural="922100000000000" nivel="" exclusao="false" /></contas><orgao operador="in" valor="" id="orgao"/><unidade operador="in" valor="" id="unidade"/><funcao operador="in" valor="" id="funcao"/><subfuncao operador="in" valor="" id="subfuncao"/><programa operador="in" valor="" id="programa"/><projativ operador="in" valor="" id="projativ"/><recurso operador="in" valor="" id="recurso"/><recursocontalinha numerolinha="" id="recursocontalinha"/><observacao valor=""/><desdobrarlinha valor="false"/></filter>');
insert into orcparamseqfiltropadrao values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 173, 2, 2017, '<?xml version="1.0" encoding="ISO-8859-1"?><filter><contas><conta estrutural="422100000000000" nivel="" exclusao="false" /><conta estrutural="922100000000000" nivel="" exclusao="false" /></contas><orgao operador="in" valor="" id="orgao"/><unidade operador="in" valor="" id="unidade"/><funcao operador="in" valor="" id="funcao"/><subfuncao operador="in" valor="" id="subfuncao"/><programa operador="in" valor="" id="programa"/><projativ operador="in" valor="" id="projativ"/><recurso operador="in" valor="" id="recurso"/><recursocontalinha numerolinha="" id="recursocontalinha"/><observacao valor=""/><desdobrarlinha valor="false"/></filter>');
insert into orcparamseqfiltropadrao values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 173, 3, 2015, '<?xml version="1.0" encoding="ISO-8859-1"?><filter><contas><conta estrutural="422200000000000" nivel="" exclusao="false" /><conta estrutural="922200000000000" nivel="" exclusao="false" /></contas><orgao operador="in" valor="" id="orgao"/><unidade operador="in" valor="" id="unidade"/><funcao operador="in" valor="" id="funcao"/><subfuncao operador="in" valor="" id="subfuncao"/><programa operador="in" valor="" id="programa"/><projativ operador="in" valor="" id="projativ"/><recurso operador="in" valor="" id="recurso"/><recursocontalinha numerolinha="" id="recursocontalinha"/><observacao valor=""/><desdobrarlinha valor="false"/></filter>');
insert into orcparamseqfiltropadrao values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 173, 3, 2016, '<?xml version="1.0" encoding="ISO-8859-1"?><filter><contas><conta estrutural="422200000000000" nivel="" exclusao="false" /><conta estrutural="922200000000000" nivel="" exclusao="false" /></contas><orgao operador="in" valor="" id="orgao"/><unidade operador="in" valor="" id="unidade"/><funcao operador="in" valor="" id="funcao"/><subfuncao operador="in" valor="" id="subfuncao"/><programa operador="in" valor="" id="programa"/><projativ operador="in" valor="" id="projativ"/><recurso operador="in" valor="" id="recurso"/><recursocontalinha numerolinha="" id="recursocontalinha"/><observacao valor=""/><desdobrarlinha valor="false"/></filter>');
insert into orcparamseqfiltropadrao values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 173, 3, 2017, '<?xml version="1.0" encoding="ISO-8859-1"?><filter><contas><conta estrutural="422200000000000" nivel="" exclusao="false" /><conta estrutural="922200000000000" nivel="" exclusao="false" /></contas><orgao operador="in" valor="" id="orgao"/><unidade operador="in" valor="" id="unidade"/><funcao operador="in" valor="" id="funcao"/><subfuncao operador="in" valor="" id="subfuncao"/><programa operador="in" valor="" id="programa"/><projativ operador="in" valor="" id="projativ"/><recurso operador="in" valor="" id="recurso"/><recursocontalinha numerolinha="" id="recursocontalinha"/><observacao valor=""/><desdobrarlinha valor="false"/></filter>');
SQLUP
        );



    }
}
