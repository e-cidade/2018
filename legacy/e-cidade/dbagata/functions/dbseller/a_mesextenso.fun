<?
# function a_mesextenso
# $string_column � a coluna selecionada 
# $array_row � a tupla corrente do relat�rio
# $array_row � a tupla anterior do relat�rio
# $row_num � o n�mero da linha atual do relat�rio 
# $col_num � o n�mero da coluna atual do relat�rio 

function a_mesextenso($string_column, $array_row)
{

  $sNomeMes = '';

  switch ($string_column) {

	case '1' :
	  $sNomeMes = 'Janeiro';	
	break;
	case '2' :
	  $sNomeMes = 'Fevereiro';	
	break;
	case '3' :
	  $sNomeMes = 'Mar�o';	
	break;
	case '4' :
	  $sNomeMes = 'Abril';	
	break;
	case '5' :
	  $sNomeMes = 'Maio';	
	break;
	case '6' :
	  $sNomeMes = 'Junho';	
	break;
	case '7' :
	  $sNomeMes = 'Julho';	
	break;
	case '8' :
	  $sNomeMes = 'Agosto';	
	break;
	case '9' :
	  $sNomeMes = 'Setembro';	
	break;
	case '10':
	  $sNomeMes = 'Outubro';	
	break;
	case '11':
	  $sNomeMes = 'Novembro';	
	break;
	case '12':
	  $sNomeMes = 'Dezembro';	
	break;


  }

   return $sNomeMes;	
}
?>

