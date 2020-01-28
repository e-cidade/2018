-- Regra de Limpeza Padrão
-- iInstit = {$iInstit}
-- iDiasManterDebitos = {$iDiasManterDebitos}
SELECT * FROM (
  SELECT *,
         row_number() OVER (),
         count(*)     OVER () FROM (
      SELECT k115_data AS data,
             k115_instit AS instit,
             'debitos_'||to_char(k115_data, 'YYYYMMDD')||'_'||k115_instit AS tabela
        FROM datadebitos
       WHERE k115_instit = {$iInstit}
         AND (current_date - k115_data + 1) >= {$iDiasManterDebitos}
         AND extract(day from k115_data)::integer <> fc_ultimodiames(extract(year from k115_data)::integer, extract(month from k115_data)::integer)
         AND EXISTS (SELECT 1
                       FROM pg_class
                      WHERE relkind = 'r'
                        AND relname = 'debitos_'||to_char(k115_data, 'YYYYMMDD')||'_'||k115_instit)
       ORDER BY data) AS x
   ) AS y
 WHERE row_number <> count

UNION

-- Manter último dia do mês dos últimos 3 meses
SELECT * FROM (
  SELECT *,
         row_number() OVER (),
         count(*)     OVER () FROM (
      SELECT k115_data AS data,
             k115_instit AS instit,
             'debitos_'||to_char(k115_data, 'YYYYMMDD')||'_'||k115_instit AS tabela
        FROM datadebitos
       WHERE k115_instit = {$iInstit}
         AND k115_data   = fc_ultimodiames_data(extract(year from k115_data)::integer, extract(month from k115_data)::integer)
         AND EXISTS (SELECT 1
                       FROM pg_class
                      WHERE relkind = 'r'
                        AND relname = 'debitos_'||to_char(k115_data, 'YYYYMMDD')||'_'||k115_instit)
       ORDER BY data DESC) AS x
   ) AS y
 WHERE row_number > 3

UNION

-- Manter último 31/12 gerado e limpar anteriores
SELECT * FROM (
  SELECT *,
         row_number() OVER (),
         count(*)     OVER () FROM (
      SELECT k115_data AS data,
             k115_instit AS instit,
             'debitos_'||to_char(k115_data, 'YYYYMMDD')||'_'||k115_instit AS tabela
        FROM datadebitos
       WHERE k115_instit = {$iInstit}
         AND extract(day from k115_data)   = 31
         AND extract(month from k115_data) = 12
         AND EXISTS (SELECT 1
                       FROM pg_class
                      WHERE relkind = 'r'
                        AND relname = 'debitos_'||to_char(k115_data, 'YYYYMMDD')||'_'||k115_instit)
       ORDER BY data DESC) AS x
   ) AS y
 WHERE row_number > 1

ORDER BY data DESC;
