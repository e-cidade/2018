<?
# function a_mesextenso
# $string_column é a coluna selecionada 
# $array_row é a tupla corrente do relatório
# $array_row é a tupla anterior do relatório
# $row_num é o número da linha atual do relatório 
# $col_num é o número da coluna atual do relatório 

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
	  $sNomeMes = 'Março';	
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

