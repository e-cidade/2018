<?php

use Classes\PostgresMigration;

class M9864RelatorioAnexo5Rgf extends PostgresMigration
{

    public function up()
    {

        $notaExplicativa  = "FONTE: Sistema E-Cidade, Unidade Responsável: [nome_departamento]. Emissão: [data_emissao], às [hora_emissao].\n\n";
        $notaExplicativa .= "Nota: 1. Essa coluna poderá apresentar valor negativo, indicando, nesse caso, insuficiência de caixa após o registro das obrigações financeiras.";

        $this->execute("insert into orcparamrel values (174, 'RGF - ANEXO V - 2017', 4, '{$notaExplicativa}');");
        $this->execute("insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 13, 174);");
        $this->execute(
            <<<SQLUP
            
insert into orcparamseqcoluna values (300, 2017, 'INSUFICIÊNCIA FINANCEIRA', 0, '', 'insuficiencia_financeira');
insert into orcparamseqcoluna values (301, 2017, 'DISPONIBILIDADE DE CAIXA LIQUIDA', 0, '', 'disp_caixa_liquida');
insert into orcparamseqcoluna values (303, 2017, 'RESTOS A PAGAR EMPENHADOS NAO PROCESSADOS', 0, '', 'rp_empenhado_nao_processado');
insert into orcparamseqcoluna values (304, 2017, 'EMPENHOS NAO LIQUIDADOS CANCELADOS', 0, '', 'empenho_nao_liquidado_cancelado');

insert into orcparamseq (
              o69_codparamrel,
              o69_codseq,         
              o69_descr,          
              o69_grupo,          
              o69_labelrel,       
              o69_manual,         
              o69_totalizador,    
              o69_ordem,          
              o69_nivellinha     
            ) 
     values (174, 1  , '', '0', 'TOTAL DOS RECURSOS VINCULADOS (I)', false, true, 1, 1),
            (174, 2  , '', '0', 'Receitas de Impostos e de Transferência de Impostos - Educação', true, false, 2, 2),
            (174, 3  , '', '0', 'Transferências do FUNDEB 60%', true, false, 3, 2),
            (174, 4  , '', '0', 'Transferências do FUNDEB 40%', true, false, 4, 2),
            (174, 5  , '', '0', 'Outros Recursos Destinados à Educação', true, false, 5, 2),
            (174, 6  , '', '0', 'Receitas de Impostos e de Transferência de Impostos - Saúde', true, false, 6, 2),
            (174, 7  , '', '0', 'Outros Recursos Destinados à Saúde', true, false, 7, 2),
            (174, 8  , '', '0', 'Recursos Destinados à Assistência Social', true, false, 8, 2),
            (174, 9  , '', '0', 'Recursos destinados ao RPPS - Plano Previdenciário', true, false, 9, 2),
            (174, 10 , '', '0', 'Recursos destinados ao RPPS - Plano Financeiro', true, false, 10, 2),
            (174, 11 , '', '0', 'Recursos de Operações de Crédito (exceto destinados à Educação e à Saúde)', true, false, 11, 2),
            (174, 12 , '', '0', 'Recursos de Alienação de Bens/Ativos', true, false, 12, 2),
            (174, 13 , '', '0', 'Outras Destinações Vinculadas de Recursos', true, false, 13, 2),
            (174, 14 , '', '0', 'TOTAL DOS RECURSOS NÃO VINCULADOS (II)', false, true, 14, 1),
            (174, 15 , '', '0', 'Recursos Ordinários', true, false, 15, 2),
            (174, 16 , '', '0', 'TOTAL (III) = (I + II)', false, true, 16, 1);

    update orcparamseq set o69_descr = substring(trim(o69_labelrel), 0, 60) where o69_codparamrel = 174;
    update orcparamseq set o69_labelrel = trim(o69_labelrel) where o69_codparamrel = 174;

SQLUP
        );

        $colunas = array(201,179,177,189,202,300,301,303,304);
        for ($linha = 1; $linha <= 16; $linha++) {

            foreach ($colunas as $indice => $codigoColuna) {

                $indice++;
                $this->execute("
                  insert into orcparamseqorcparamseqcoluna 
                       values (
                          nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'),
                          {$linha},
                          174,
                          {$codigoColuna},
                          {$indice},
                          13,
                          ''                               
                       );
                ");
            }
        }

        $nomeColunas = array('disp_caixa', 'exanterior', 'vlrexatual', 'rp_nprocexant', 'financeira',
            'insuficiencia_financeira', 'disp_caixa_liquida', 'rp_empenhado_nao_processado', 'empenho_nao_liquidado_cancelado'
        );
        foreach ($nomeColunas as $indiceColuna => $nomeColuna) {

            $formula = array();
            for ($linha = 2; $linha <= 13; $linha++) {
                $formula[] = "L[{$linha}]->{$nomeColuna}";
            }
            $formula = "(" . implode(' + ', $formula) . ")";

            $sequencialColuna = $indiceColuna+1;
            $this->execute("update orcparamseqorcparamseqcoluna set o116_formula = '{$formula}' where o116_codparamrel = 174 and o116_codseq = 1 and o116_ordem = {$sequencialColuna}");
        }

        for ($linha = 2; $linha <= 13; $linha++) {

            $formula = "(L[{$linha}]->disp_caixa - (L[{$linha}]->exanterior + L[{$linha}]->vlrexatual + L[{$linha}]->rp_nprocexant + L[{$linha}]->financeira) - L[{$linha}]->insuficiencia_financeira)";
            $this->execute("update orcparamseqorcparamseqcoluna set o116_formula = '{$formula}' where o116_codparamrel = 174 and o116_ordem = 7 and o116_codseq = {$linha}");
        }



        foreach ($nomeColunas as $indiceColuna => $nomeColuna) {
            $indiceColuna++;
            $this->execute("update orcparamseqorcparamseqcoluna set o116_formula = 'L[15]->{$nomeColuna}' where o116_codparamrel = 174 and o116_ordem = {$indiceColuna} and o116_codseq = 14;");
        }

        $formula = "(L[15]->disp_caixa - (L[15]->exanterior + L[15]->vlrexatual + L[15]->rp_nprocexant + L[15]->financeira) - L[15]->insuficiencia_financeira)";
        $this->execute("update orcparamseqorcparamseqcoluna set o116_formula = '{$formula}' where o116_codparamrel = 174 and o116_ordem = 7 and o116_codseq = 15;");

        for ($coluna = 1; $coluna <= 9; $coluna++) {
            $this->execute("update orcparamseqorcparamseqcoluna set o116_formula = '(F[1] + F[14])' where o116_codparamrel = 174 and o116_ordem = {$coluna} and o116_codseq = 16;");
        }
    }

    public function down()
    {

        $this->execute(
            <<<SQLDOWN

delete from orcparamseqorcparamseqcoluna where o116_codparamrel = 174;
delete from orcparamseqcoluna where o115_sequencial in (300, 301, 303, 304);
delete from orcparamseq where o69_codparamrel = 174;
delete from orcparamrelperiodos where o113_orcparamrel = 174;
delete from orcparamrel where o42_codparrel = 174;
SQLDOWN
        );
    }
}
