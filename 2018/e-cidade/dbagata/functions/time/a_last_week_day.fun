<?
# function a_last_week_day
# $string_column é a coluna selecionada 
# $array_row é a linha atual do relatório

function a_last_week_day($string_column, $array_row)
{
    $wday = str_pad(date('w'), 2, '0', STR_PAD_LEFT);
    # 0 = domingo
    # 1 = segunda
    $diff = 6-$wday;
    
    $dia = str_pad(date('d',mktime(0,0,0,date('m'),date('d')+$diff,date('Y'))), 2, '0', STR_PAD_LEFT);
    $mes = str_pad(date('m',mktime(0,0,0,date('m'),date('d')+$diff,date('Y'))), 2, '0', STR_PAD_LEFT);
    $ano = str_pad(date('Y',mktime(0,0,0,date('m'),date('d')+$diff,date('Y'))), 2, '0', STR_PAD_LEFT);

	return "{$dia}/{$mes}/{$ano}";
}
?>
