<?
$campos = "
  taxadiversos.y119_sequencial,
  taxadiversos.y119_natureza,
  /* upper((regexp_replace(lower(y118_descricao), '^(\\\\w{1,1})(.*)', '\\\\1')))||(regexp_replace(lower(y118_descricao), '^(\\\\w{1,1})(.*)', '\\\\2')) as dl_Grupo, */
  grupotaxadiversos.y118_descricao as dl_Grupo,
  case taxadiversos.y119_unidade 
      when '1' then 'm'
      when '2' then 'm²'
      when '3' then 'm³'
      when '4' then '100m'
      when '5' then '30m²'
      when '6' then '60m²'
      when '7' then 'Lote'
      when '8' then 'Imóvel'
      when '9' then 'Peça'
      when '10' then 'Milheiro'
      when '11' then 'Veículo'
      when '12' then 'Unidade'
      else '-------'
  end as y119_unidade,
  case taxadiversos.y119_tipo_periodo when 'A' then 'Anual' when 'M' then 'Mensal' else 'Diária' end as dl_Periodicidade,
  case taxadiversos.y119_tipo_calculo when 'U' then 'Único' else 'Geral' end as dl_Tipo
";
?>
