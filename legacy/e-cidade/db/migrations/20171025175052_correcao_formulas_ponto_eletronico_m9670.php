<?php

use Classes\PostgresMigration;

class CorrecaoFormulasPontoEletronicoM9670 extends PostgresMigration
{
    public function up()
    {
        $this->execute('
            CREATE OR REPLACE FUNCTION fc_converte_hora_trabalho_hora_pagamento(numeric(10,2)) RETURNS numeric(10,2) AS
            $$ 
            DECLARE
                quantidadeHorasTrabalhadas  alias for $1;
                quantidadeHorasPagamento    numeric(10,2) := 0;
            BEGIN

            SELECT round(
                                        (
                                            ((quantidadeHorasTrabalhadas-trunc(quantidadeHorasTrabalhadas))*100)
                                            +
                                            (trunc(quantidadeHorasTrabalhadas)*60)
                                        )/60,2
                                    )
                INTO quantidadeHorasPagamento;

                RETURN quantidadeHorasPagamento;
            END;
            $$ 
            LANGUAGE \'plpgsql\';

            CREATE OR REPLACE FUNCTION fc_converte_hora_trabalho(numeric(10,2)) RETURNS numeric(10,2) AS
            $$ 
            DECLARE
                quantidadeHorasPagamento    numeric(10,2) := 0;
            BEGIN

            SELECT fc_converte_hora_trabalho_hora_pagamento($1)
                INTO quantidadeHorasPagamento;

                RETURN quantidadeHorasPagamento;
            END;
            $$ 
            LANGUAGE \'plpgsql\';
        ');

        $this->execute("
            UPDATE db_formulas 
            SET db148_formula = 'select fc_converte_hora_trabalho_hora_pagamento((select replace(coalesce((case when trim(h16_hora) = '''' then null else h16_hora end), ''0''), '':'', ''.'') from assenta where h16_codigo = [CODIGO_ASSENTAMENTO])::numeric)' 
            WHERE db148_nome = 'PONTO_HORA'
        ");
    }

    public function down()
    {
        $this->execute("
            UPDATE db_formulas 
            SET db148_formula = 'select fc_converte_hora_trabalho_hora_pagamento((select replace(h16_hora, '':'', ''.'') from assenta where h16_codigo = [CODIGO_ASSENTAMENTO])::numeric)' 
            WHERE db148_nome = 'PONTO_HORA'
        ");
    }
}
