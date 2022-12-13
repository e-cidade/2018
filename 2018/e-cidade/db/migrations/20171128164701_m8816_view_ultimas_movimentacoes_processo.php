<?php

use Classes\PostgresMigration;

class M8816ViewUltimasMovimentacoesProcesso extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            CREATE VIEW ultimas_movimentacoes_processos_vencidos AS
                SELECT DISTINCT ON (codigo_processo)
                    codigo_processo,
                    p58_numero AS numero_processo,
                    p58_ano    AS ano_processo,
                    p58_dtproc AS data_criacao,
                    data       AS ultima_data,
                    hora       AS ultima_hora,
                    codigo_departamento,
                    descrdepto AS descricao_departamento,
                    id_usuario AS codigo_usuario,
                    login,
                    nome,
                    p58_obs    AS assunto
                FROM (
            
                         SELECT
                             p64_codtran    AS codigo_movimentacao,
                             p61_codproc    AS codigo_processo,
                             p61_dtandam    AS data,
                             p61_hora       AS hora,
                             p61_coddepto   AS codigo_departamento,
                             p61_id_usuario AS codigo_usuario
                         FROM procandam
                             INNER JOIN proctransand ON p61_codandam = p64_codandam
                         WHERE p61_codproc IN (SELECT p58_codproc
                                               FROM protprocesso
                                                   LEFT JOIN arqproc ON p68_codproc = p58_codproc
                                               WHERE p68_codproc IS NULL)
            
                         UNION
            
                         SELECT
                             p63_codtran     AS codigo_movimentacao,
                             p63_codproc     AS codigo_processo,
                             p62_dttran      AS data,
                             p62_hora        AS hora,
                             p62_coddeptorec AS codigo_departamento,
                             p62_id_usorec   AS codigo_usuario
                         FROM proctransfer
                             INNER JOIN proctransferproc ON p63_codtran = p62_codtran
                         WHERE p63_codproc IN (SELECT p58_codproc
                                               FROM protprocesso
                                                   LEFT JOIN arqproc ON p68_codproc = p58_codproc
                                               WHERE p68_codproc IS NULL)
            
                         UNION
            
                         SELECT
                             p64_codtran  AS codigo_movimentacao,
                             p61_codproc  AS codigo_processo,
                             p78_data     AS data,
                             p78_hora     AS hora,
                             p61_coddepto AS codigo_departamento,
                             p78_usuario  AS codigo_usuario
                         FROM procandamint
                             INNER JOIN procandam ON p61_codandam = p78_codandam
                             INNER JOIN proctransand ON p61_codandam = p64_codandam
                         WHERE p61_codproc IN (SELECT p58_codproc
                                               FROM protprocesso
                                                   LEFT JOIN arqproc ON p68_codproc = p58_codproc
                                               WHERE p68_codproc IS NULL)
            
                         UNION
            
                         SELECT
                             p64_codtran  AS codigo_movimentacao,
                             p61_codproc  AS codigo_processo,
                             p88_data     AS data,
                             p88_hora     AS hora,
                             p61_coddepto AS codigo_departamento,
                             p89_usuario  AS codigo_usuario
                         FROM proctransferint
                             INNER JOIN proctransferintand ON p87_codtransferint = p88_codigo
                             INNER JOIN proctransferintusu ON p89_codtransferint = p88_codigo
                             INNER JOIN procandam ON p61_codandam = p87_codandam
                             INNER JOIN proctransand ON p61_codandam = p64_codandam
                         WHERE p61_codproc IN (SELECT p58_codproc
                                               FROM protprocesso
                                                   LEFT JOIN arqproc ON p68_codproc = p58_codproc
                                               WHERE p68_codproc IS NULL)
            
                     ) AS ultimas_movimentacoes
                    INNER JOIN db_depart ON coddepto = codigo_departamento
                    INNER JOIN protprocesso ON p58_codproc = codigo_processo
                    LEFT JOIN db_usuarios ON id_usuario = codigo_usuario
                ORDER BY codigo_processo, data DESC, hora DESC, codigo_movimentacao DESC
        ");
    }

    public function down()
    {
        $this->execute("DROP VIEW ultimas_movimentacoes_processos_vencidos");
    }
}
