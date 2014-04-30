<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */


require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
include("libs/db_jsplibwebseller.php");

include("classes/db_sau_agendaexames_classe.php");
include("classes/db_sau_prestadorhorarios_classe.php");
include("classes/db_agendamentos_ext_classe.php" );

include("dbforms/db_funcoes.php");

?>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js">


</script>
<link href="estilos.css" rel="stylesheet" type="text/css">

<style>
a:hover {
  color:blue;
}
a:visited {
  color: black;
  font-weight: bold;
}
a:active {
  color: black;
  font-weight: bold;
}
.cabec {
  text-align: center;
  font-size: 11;
  color: darkblue;
  background-color:#aacccc ;
  border:1px solid $FFFFFF;
  font-weight: bold;
}
.corpo {
  font-size: 9;
  color: black;
  background-color:#ccddcc;
}

</style>
<link href="estilos.css" rel="stylesheet" type="text/css">

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >


<?


db_postmemory($HTTP_POST_VARS);

$sd02_i_codigo = db_getsession("DB_coddepto");
$ano           = substr( $s113_d_exame, 6, 4 );
$mes           = substr( $s113_d_exame, 3, 2 );
$dia           = substr( $s113_d_exame, 0, 2 );

$clsau_agendaexames      = new cl_sau_agendaexames;
$clsau_prestadorhorarios = new cl_sau_prestadorhorarios;
//$clausencias     = new cl_ausencias_ext;

$str_query =  $clsau_prestadorhorarios->sql_query	("",
													"*, (select count(s113_d_exame)
															from sau_agendaexames
															where s113_d_exame = '$ano/$mes/$dia'
		            										and s113_i_situacao = 1
		            										and s113_i_prestadorhorarios = sau_prestadorhorarios.s112_i_codigo															
													    )as total_agendado 
													",
													"s112_i_diasemana, s112_c_horaini", 
													"s112_i_prestadorvinc = $s111_i_codigo 
													and s112_i_diasemana = $chave_diasemana
										             and ( s112_d_valfinal is null or 
													       ( s112_d_valfinal is not null and s112_d_valfinal > '$ano/$mes/$dia' ) 
													      )
													");
													      
$result_prestadorhorarios = $clsau_prestadorhorarios->sql_record( $str_query ) ;

if( $clsau_prestadorhorarios->numrows == 0  ){
	echo "<script>
			alert('Data inválida para esse profisisonal.');
			parent.document.form1.s113_d_exame.value='';
			parent.document.form1.diasemana.value='';
			parent.document.form1.s113_d_exame.focus();
		</script>";
	exit;
}


$reservadas    = 0;
$nro_fichas    = 0;
$nro_agendados = 0;
$str_tipograde = "";
$str_separador = "";
$linhas=0; //26/02
//Calcula nro de fichas/reserva
for( $xHora=0; $xHora < $clsau_prestadorhorarios->numrows; $xHora++ ){	
	$obj_prestadorhorarios    = db_utils::fieldsMemory( $result_prestadorhorarios, $xHora );
	
	$reservadas    += $obj_prestadorhorarios->s112_i_reservas;
	$nro_fichas    += $obj_prestadorhorarios->s112_i_fichas;
	$nro_agendados += $obj_prestadorhorarios->total_agendado;
	$str_tipograde .= $str_separador.($obj_prestadorhorarios->s112_c_tipograde=="I"?" Intervalo ":" Período ");
	$str_separador = "/";
}
$int_size = strlen($str_tipograde)>80?80:strlen($str_tipograde);
echo "<script>";
echo " parent.document.form1.saldo.value=".($nro_fichas-$nro_agendados);
echo " ;parent.document.form1.s112_i_fichas.value=".($nro_fichas); 
echo " ;parent.document.form1.s112_i_reservas.value=".($reservadas); 
echo " ;parent.document.getElementById('s112_c_tipograde').setAttribute('maxlength', 100);";
echo " ;parent.document.getElementById('s112_c_tipograde').setAttribute('size', $int_size);";
echo " ;parent.document.form1.s112_c_tipograde.value='".$str_tipograde."'";
//echo " ;parent.document.form1.s112_c_tipograde.value='".($obj_prestadorhorarios->s112_c_tipograde=="I"?"Intervalo":"PeríodXXX")."'";
echo "</script>";
?>
<table border="0" cellspacing="2px" width="100%" height="100%" cellpadding="1px" bgcolor="#cccccc">
<?	


//Ausências
/*
$result_ausencias = $clausencias->sql_record( $clausencias->sql_query_ext(null,"case when sd06_i_tipo = 1 then
                                        'Folga'
                                     else
                                        'Férias'
                                     end as sd06_c_tipo",null,"sd06_i_especmed = $sd27_i_codigo
	            								                    and '$ano/$mes/$dia' between sd06_d_inicio and sd06_d_fim") ); 
if( $clausencias->numrows > 0 ){
	$obj_ausencias = db_utils::fieldsMemory($result_ausencias,0);
	?>
		<tr class='cabec'>
			<td align="center">
				<font size="4"  color="red">Situação do Profissional: <?=$obj_ausencias->sd06_c_tipo ?></font>
			</td>
		</tr>
	<?
}else{
*/
	for( $xHora=0; $xHora < $clsau_prestadorhorarios->numrows; $xHora++ ){
		
		$obj_prestadorhorarios  = db_utils::fieldsMemory( $result_prestadorhorarios, $xHora );
		$reservadas         = $obj_prestadorhorarios->s112_i_reservas;
		$nro_fichas         = $obj_prestadorhorarios->s112_i_fichas;
	
		//Calcula intervalo
		$hora_ini           = $obj_prestadorhorarios->s112_c_horaini;
		$hora_fim           = $obj_prestadorhorarios->s112_c_horafim;
		$minutostrabalhados = cl_agendamentos_ext::minutos($hora_ini,$hora_fim);
		$intervalo          = 0;
		if($nro_fichas!=0 && $obj_prestadorhorarios->s112_c_tipograde == 'I' ){
			$intervalo      = number_format($minutostrabalhados / $nro_fichas,2,'.','');
		}
		
		//Agenda
		$str_query = $clsau_agendaexames->sql_query("","s113_i_codigo,s113_i_ficha,z01_i_numcgs,z01_v_nome,s113_i_prestadorhorarios","s113_i_ficha", 
													"s113_d_exame = '$ano/$mes/$dia'
		            								and s113_i_situacao = 1
		            								and s113_i_prestadorhorarios = {$obj_prestadorhorarios->s112_i_codigo} 
		            								");
		            								
		$res_agenda = $clsau_agendaexames->sql_record( $str_query );
		$linhas     = $clsau_agendaexames->numrows;
		$linha      = 0;
		
		if( $linhas >= $nro_fichas ){
			$reservadas = 0;
		}else{
			$dif = $nro_fichas - $linhas;
			if( $dif < $reservadas ){
				//$reservadas = $dif;
			}
		}
		if( $linhas > $nro_fichas ){
			$nro_fichas = $linhas;
		}
		
		$diferenca   = $nro_fichas-$reservadas;
		$mi_interva1 = 0;
		$mi_interva2 = 1;
	
		?>
			<tr class='cabec'>
				<td colspan="8" align="left"><?=$obj_prestadorhorarios->s112_i_codigo." - ".$obj_prestadorhorarios->sd101_c_descr ?></td>
			</tr>
			<tr class='cabec'>
				<td class='cabec' align="center">Ficha</td>
				<td class='cabec' align="center">Hs Inicial</td>
				<td class='cabec' align="center">Hs Final</td>
				<td class='cabec' align="center">Reserva</td>
				<td class='cabec' align="center">Tipo Grade</td>
				<td class='cabec' align="center">CGS</td>
				<td class='cabec' align="center">Nome do Paciente</td>
				<td class='cabec' align='center'>Opções</td>
				
			</tr>
			
		<?
		for( $h=0; $h < $nro_fichas; $h++){
		    $nro_ficha = str_pad($h,3,0,"str_pad_left");
		    $id_ficha  = 0;
		    $codigo    = 0;
		    if($h>=$diferenca && $linha>=$linhas){
		    	$reservada= "Sim";
		    	$paciente = "-- R E S E R V A D A --";
		    	$cgs      = "";
		    	$natend   = "x x x x x";
		    }else{
		    	$reservada= "Não";
		    	$paciente  = "---------";
		    	$cgs      = "";
		    	$natend    = "x x x x x";
		    	if($linha<$linhas){
		    		$obj_agenda = db_utils::fieldsMemory( $res_agenda, $linha );
		    		$id_ficha = $obj_agenda->s113_i_ficha;
		    		//echo "$h>=$diferenca   $nro_fichas-$reservadas {$obj_prestadorhorarios->s112_i_codigo}<br>";
		    		//echo( "$id_ficha == ($h+1)) && ({$obj_agenda->s113_i_prestadorhorarios} == {$obj_prestadorhorarios->s112_i_codigo})<br>");
		    		if( ($id_ficha == ($h+1)) && ($obj_agenda->s113_i_prestadorhorarios == $obj_prestadorhorarios->s112_i_codigo) ){	    		
		    			$codigo   = $obj_agenda->s113_i_codigo;
		    			$cgs      = $obj_agenda->z01_i_numcgs;
			    		$paciente = $obj_agenda->z01_v_nome;
			    		$linha++;
		    		}else if($h>=$diferenca){
		    			$id_ficha = 0;
				    	$reservada= "Sim";
				    	$paciente = "-- R E S E R V A D A --";
				    	$cgs      = "";
				    	$natend   = "x x x x x";    			
		    		}
		    	}
		    }
		    if( $intervalo != 0){
		 		$hora_fim = cl_agendamentos_ext::somahora($hora_ini,$intervalo+$mi_interva1);
		    }else{
		    	$hora_fim = "";	
		    }
		    
			?>
			<tr>
				<td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=($h+1)?></td>
				<td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=substr($hora_ini,0,5) ?></td>
				<td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=substr($hora_fim,0,5) ?></td>
				<td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=$reservada ?></td>
				<td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=$obj_prestadorhorarios->s112_c_tipograde=="I"?"Intervalo":"Período" ?></td>
				<td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=$cgs ?></td>
				<td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=$paciente ?></td>
				<td class='corpo' nowrap>
				    <a title='Lançar conteúdo da linha'  href='#' onclick="js_lancar(<?=$codigo?>,<?=$obj_prestadorhorarios->s112_i_codigo?>,'<?=$ano?>','<?=$mes?>','<?=$dia?>',<?=($h+1)?>,'<?=substr($hora_ini,0,5) ?>');">&nbsp;L&nbsp;</a>
					&nbsp;&nbsp;
					<a title='Excluir conteúdo da linha' href='#' onclick='js_excluir(<?=$codigo?>);return false;'>&nbsp;E&nbsp;</a>
					&nbsp;&nbsp;
					<a title='Comprovante de Agendamento' href='#' onclick='js_comprovante(<?=$codigo?>);return false;'>&nbsp;C&nbsp;</a>
				</td>
			</tr>
			<?
		    if( $intervalo != 0){
				$hora_ini    = cl_agendamentos_ext::somahora($hora_ini,($intervalo+$mi_interva2));	
				$mi_interva1 = -1;
				$mi_interva2 = 0;
		    }
		} // fim for h
	
	}//fim for
//}//fim if ausencias
?>
</table>
</body>
</html>

<script>

function js_comprovante( s113_i_codigo ){

	if( s113_i_codigo != 0 ){
		x  = 'sau2_sau_agendaexames001.php';
		x += '?s113_i_codigo='+s113_i_codigo;
		x += '&diasemana='+parent.document.form1.diasemana.value;
	
		jan = window.open(x,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
		jan.moveTo(0,0);
	}else{
		alert('Paciente não informado.');
	}

}

function js_lancar(id_ficha,s112_i_codigo, ano, mes, dia, sd23_i_ficha, sd23_c_hora){

	if( id_ficha == undefined || id_ficha == 0 ){
		top = ( screen.availHeight-600 ) / 2;
		left = ( screen.availWidth-600 ) / 2;
		x  = 'sau4_sau_agendaexames003.php';
		x += '?s112_i_codigo='+s112_i_codigo;
		x += '&s113_d_exame='+ano+'/'+mes+'/'+dia;
		x += '&s113_i_ficha='+sd23_i_ficha;
		x += '&s113_c_hora='+sd23_c_hora
		x += '&db_opcao=1';
		
		js_OpenJanelaIframe('parent','db_iframe_agendamento',x,'Paciente',true, top, left, 600, 200);

	}else{
		alert('Já foi lançado registro.');
	}

}


function js_excluir(id_ficha,z01_i_numcgs){
	if( id_ficha != undefined && id_ficha != 0 ){
		top = ( screen.availHeight-600 ) / 2;
		left = ( screen.availWidth-600 ) / 2;
		x  = 'sau4_sau_agendaexames003.php';
		x += '?chavepesquisaagenda='+id_ficha;
		x += '&db_opcao=3';
		
		js_OpenJanelaIframe('parent','db_iframe_agendamento',x,'Paciente',true, top, left, 600, 200);
	}else{
		alert('Registro não pode ser excluído.');
	}	
}

</script>