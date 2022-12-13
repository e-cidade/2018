<?php

use Classes\PostgresMigration;

class M8515 extends PostgresMigration
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
    }

    public function down()
    {
    	$this->execute("
	    	DROP FUNCTION IF EXISTS fc_converte_hora_trabalho_hora_pagamento(numeric(10,2));
				DROP FUNCTION IF EXISTS fc_converte_hora_trabalho(numeric(10,2));
  		");
    }
}
