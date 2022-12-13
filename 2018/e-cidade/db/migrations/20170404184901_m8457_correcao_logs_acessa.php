<?php

use Classes\PostgresMigration;

class M8457CorrecaoLogsAcessa extends PostgresMigration
{
    public function up(){

        $sSql = <<<SQL
        CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_particao_cria (
          sEsquema         TEXT,
          sTabela          TEXT,
          sEsquemaParticao TEXT,
          sTabelaParticao  TEXT,
          sCheck           TEXT
        ) RETURNS void AS
        $$
        DECLARE
          sSQL TEXT;
        BEGIN

          IF fc_clone_table(sEsquema||'.'||sTabela, sEsquemaParticao||'.'||sTabelaParticao, null, true) IS TRUE THEN

            sSQL := 'ALTER TABLE '||sEsquemaParticao||'.'||sTabelaParticao;
            sSQL := sSQL || ' ADD CONSTRAINT '||sTabelaParticao||'_datahora_servidor_ck';
            sSQL := sSQL || ' CHECK ('||sCheck||');';

            EXECUTE sSQL;

          END IF;

          RETURN;
        END;
        $$
        LANGUAGE plpgsql;
SQL;

        $this->execute($sSql);
    }

    public function down() {}
}
