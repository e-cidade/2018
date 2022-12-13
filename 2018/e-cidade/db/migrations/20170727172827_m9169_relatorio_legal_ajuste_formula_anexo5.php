<?php

use Classes\PostgresMigration;

class M9169RelatorioLegalAjusteFormulaAnexo5 extends PostgresMigration
{
    public function up()
    {
        $this->execute( <<<SQL
update orcparamseqorcparamseqcoluna set o116_formula = 'L[1]->vlrexanter - (F[3] + L[6]->vlrexanter) > 0 ? L[1]->vlrexanter - (F[3] + L[6]->vlrexanter) : 0 '
 where o116_codparamrel = 162 and o116_orcparamseqcoluna = 178 and o116_codseq = 7;

update orcparamseqorcparamseqcoluna set o116_formula = 'L[1]->saldo_bimestre_anterior - (F[3] + L[6]->saldo_bimestre_anterior) > 0 ? L[1]->saldo_bimestre_anterior - (F[3] + L[6]->saldo_bimestre_anterior) : 0 '
 where o116_codparamrel = 162 and o116_orcparamseqcoluna = 56 and o116_codseq = 7;

update orcparamseqorcparamseqcoluna set o116_formula = 'L[1]->saldo_bimestre_atual - (F[3] + L[6]->saldo_bimestre_atual) > 0 ? L[1]->saldo_bimestre_atual - (F[3] + L[6]->saldo_bimestre_atual) : 0 '
 where o116_codparamrel = 162 and o116_orcparamseqcoluna = 57 and o116_codseq = 7;


update orcparamseqorcparamseqcoluna set o116_formula = 'L[1]->saldo_exercicio_anterior-L[20]->saldo_exercicio_anterior > 0 ? L[1]->saldo_exercicio_anterior-L[20]->saldo_exercicio_anterior : 0 '
 where o116_codparamrel = 167 and o116_orcparamseqcoluna = 223 and o116_codseq = 25;

 update orcparamseqorcparamseqcoluna set o116_formula = 'L[1]->primeiro_periodo-L[20]->primeiro_periodo > 0 ? L[1]->primeiro_periodo-L[20]->primeiro_periodo : 0 '
 where o116_codparamrel = 167 and o116_orcparamseqcoluna = 219 and o116_codseq = 25;

 update orcparamseqorcparamseqcoluna set o116_formula = 'L[1]->segundo_periodo-L[20]->segundo_periodo > 0 ? L[1]->segundo_periodo-L[20]->segundo_periodo : 0 '
 where o116_codparamrel = 167 and o116_orcparamseqcoluna = 220 and o116_codseq = 25;

 update orcparamseqorcparamseqcoluna set o116_formula = 'L[1]->segundo_periodo-L[20]->segundo_periodo > 0 ? L[1]->segundo_periodo-L[20]->segundo_periodo : 0 '
 where o116_codparamrel = 167 and o116_orcparamseqcoluna = 221 and o116_codseq = 25;

SQL
        );
    }

    public function down()
    {
        $this->execute( <<<SQL
update orcparamseqorcparamseqcoluna set o116_formula = 'L[1]->vlrexanter - (F[3] + L[6]->vlrexanter) '
 where o116_codparamrel = 162 and o116_orcparamseqcoluna = 178 and o116_codseq = 7;

update orcparamseqorcparamseqcoluna set o116_formula = 'L[1]->saldo_bimestre_anterior - (F[3] + L[6]->saldo_bimestre_anterior) '
 where o116_codparamrel = 162 and o116_orcparamseqcoluna = 56 and o116_codseq = 7;

update orcparamseqorcparamseqcoluna set o116_formula = 'L[1]->saldo_bimestre_atual - (F[3] + L[6]->saldo_bimestre_atual) '
 where o116_codparamrel = 162 and o116_orcparamseqcoluna = 57 and o116_codseq = 7;


update orcparamseqorcparamseqcoluna set o116_formula = 'L[1]->saldo_exercicio_anterior-L[20]->saldo_exercicio_anterior '
 where o116_codparamrel = 167 and o116_orcparamseqcoluna = 223 and o116_codseq = 25;

 update orcparamseqorcparamseqcoluna set o116_formula = 'L[1]->primeiro_periodo-L[20]->primeiro_periodo '
 where o116_codparamrel = 167 and o116_orcparamseqcoluna = 219 and o116_codseq = 25;

 update orcparamseqorcparamseqcoluna set o116_formula = 'L[1]->segundo_periodo-L[20]->segundo_periodo '
 where o116_codparamrel = 167 and o116_orcparamseqcoluna = 220 and o116_codseq = 25;

 update orcparamseqorcparamseqcoluna set o116_formula = 'L[1]->segundo_periodo-L[20]->segundo_periodo '
 where o116_codparamrel = 167 and o116_orcparamseqcoluna = 221 and o116_codseq = 25;

SQL
        );
    }
}
