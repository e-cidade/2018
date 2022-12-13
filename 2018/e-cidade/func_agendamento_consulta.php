<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

include("classes/db_agendamentos_ext_classe.php");
include("classes/db_undmedhorario_ext_classe.php");
include("classes/db_ausencias_ext_classe.php");

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
.opcoes {
  font-size: 16;
  font-weight: bold;
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

$ano           = substr( $sd23_d_consulta, 6, 4 );
$mes           = substr( $sd23_d_consulta, 3, 2 );
$dia           = substr( $sd23_d_consulta, 0, 2 );

$clagendamentos  = new cl_agendamentos_ext;
$clundmedhorario = new cl_undmedhorario_ext;
$clausencias     = new cl_ausencias_ext;

$sAusenciaPorCodGradeHorario = " and sd30_i_codigo not in (select sd06_i_undmedhorario from ausencias
                                   inner join undmedhorario on sd06_i_undmedhorario = sd30_i_codigo
                                      where sd06_i_especmed = $sd27_i_codigo
                                      and sd30_i_diasemana = $chave_diasemana
                                      and '$ano/$mes/$dia' between sd06_d_inicio and sd06_d_fim) ";

$sCodigoGradeHorario = ' and sd30_i_codigo = '.$sd06_i_undmedhorario;

$sSql = $clundmedhorario->sql_query_ext(null,
				 									"*, (select count(sd23_d_consulta)
															from agendamentos
															where sd23_d_consulta = '$ano/$mes/$dia'
		            										and not exists ( select *
		            														from agendaconsultaanula
		            														where s114_i_agendaconsulta = sd23_i_codigo
		            														)
		            										and sd23_i_undmedhor  = undmedhorario.sd30_i_codigo
													    )as total_agendado
													",
													"sd30_i_diasemana, sd30_c_horaini",
													"sd27_i_codigo = $sd27_i_codigo
													and sd30_i_diasemana = $chave_diasemana
										             and ( sd30_d_valfinal is null or
													       ( sd30_d_valfinal is not null and sd30_d_valfinal >= '$ano/$mes/$dia' )
													      )
													  and ( sd30_d_valinicial is null or
													       ( sd30_d_valinicial is not null and sd30_d_valinicial <= '$ano/$mes/$dia' )
													      )	$sAusenciaPorCodGradeHorario $sCodigoGradeHorario
																");

$result_undmedhorario = $clundmedhorario->sql_record($sSql);
if( $clundmedhorario->numrows == 0  ){
	echo "<script>
			alert('Data inválida para esse profisisonal.');
			parent.document.form1.sd23_d_consulta.value='';
			parent.document.form1.diasemana.value='';
			parent.document.form1.sd23_d_consulta.focus();
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
for( $xHora=0; $xHora < $clundmedhorario->numrows; $xHora++ ){
	$obj_undmedhorario    = db_utils::fieldsMemory( $result_undmedhorario, $xHora );

	$reservadas    += $obj_undmedhorario->sd30_i_reservas;
	$nro_fichas    += $obj_undmedhorario->sd30_i_fichas;
	$nro_agendados += $obj_undmedhorario->total_agendado;
	$str_tipograde .= $str_separador.($obj_undmedhorario->sd30_c_tipograde=="I"?" Intervalo ":" Período ");
	$str_separador = "/";

}
$int_size = strlen($str_tipograde)>80?80:strlen($str_tipograde);
echo "<script>";
//echo " parent.document.form1.saldo.value=".($nro_fichas+$reservadas-$nro_agendados);
echo " ;parent.document.form1.sd30_i_fichas.value=".($nro_fichas);
echo " ;parent.document.form1.sd30_i_reservas.value=".($reservadas);
echo " ;parent.document.getElementById('sd30_c_tipograde').setAttribute('maxlength', 100);";
echo " ;parent.document.getElementById('sd30_c_tipograde').setAttribute('size', $int_size);";
echo " ;parent.document.form1.sd30_c_tipograde.value='".$str_tipograde."'";
echo "</script>";
?>
<table  id="tbl_agendados"
		border="0"
		cellspacing="2px"
		width="100%"
		cellpadding="1px"
		bgcolor="#cccccc"
>
<?


//Ausências
$result_ausencias = $clausencias->sql_record(
						$clausencias->sql_query_ext(null,
								"               s139_c_descr as sd06_c_tipo",null,
                                 "sd06_i_especmed = $sd27_i_codigo
	            				  and '$ano/$mes/$dia' between sd06_d_inicio and sd06_d_fim and sd06_i_undmedhorario is null") );
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
	for( $xHora=0; $xHora < $clundmedhorario->numrows; $xHora++ ){

		$obj_undmedhorario  = db_utils::fieldsMemory( $result_undmedhorario, $xHora );
		$reservadas         = $obj_undmedhorario->sd30_i_reservas;
		$nro_fichas         = $obj_undmedhorario->sd30_i_fichas+$reservadas;

		//Calcula intervalo
		$hora_ini           = $obj_undmedhorario->sd30_c_horaini;
		$hora_fim           = $obj_undmedhorario->sd30_c_horafim;
		$minutostrabalhados = $clagendamentos->minutos($hora_ini,$hora_fim);
		$intervalo          = 0;
		if($nro_fichas!=0 && $obj_undmedhorario->sd30_c_tipograde == 'I' ){
			$intervalo      = number_format($minutostrabalhados / $nro_fichas,2,'.','');
		}

	/*		            								and not exists ( select *
		            								                   from ausencias
		            							                  where sd06_i_especmed = $sd27_i_codigo
		            								                    and '$ano/$mes/$dia' between sd06_d_inicio and sd06_d_fim
		            								               )
	*/
		//Agenda
		$str_query = $clagendamentos->sql_query_ext("","sd23_i_codigo,sd23_i_ano,sd23_i_mes,sd23_i_ficha,z01_i_numcgs,z01_v_nome,sd23_i_undmedhor","sd23_i_codigo",
													"sd23_d_consulta = '$ano/$mes/$dia'
		            								and sd23_i_undmedhor = {$obj_undmedhorario->sd30_i_codigo}
		            								");

		$res_agenda = $clagendamentos->sql_record( $str_query );// or die( db_msgbox( pg_errormessage() ) );
		$linhas     = $clagendamentos->numrows;
		$linha      = 0;

		if( $linhas >= $nro_fichas ){
			$reservadas = 0;
		}else{
			$dif = $nro_fichas - $linhas;
			if( $dif < $reservadas ){
				$reservadas = $dif;
			}
		}
		if( $linhas > $nro_fichas ){
			$nro_fichas = $linhas;
		}

		$diferenca   = $nro_fichas-$reservadas;
		$mi_interva1 = 0;
		$mi_interva2 = 1;

		?>
			<tr class='cabec' id="<?=trim($obj_undmedhorario->sd30_i_codigo)?>">
				<td colspan="8" align="left">
					<img src="skins/img.php?file=Controles/seta_down.png" onclick="js_ocultar(this,<?=$obj_undmedhorario->sd30_i_codigo ?>)">
					<?=$obj_undmedhorario->sd30_i_codigo." - ".$obj_undmedhorario->sd101_c_descr ?>
				</td>
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
		    		$id_ficha = $obj_agenda->sd23_i_ficha;
		    		//if( ($id_ficha == ($h+1)) && ($obj_agenda->sd23_i_undmedhor == $obj_undmedhorario->sd30_i_codigo) ){
		    		if( ($obj_agenda->sd23_i_undmedhor == $obj_undmedhorario->sd30_i_codigo) ){
		    			$codigo   = $obj_agenda->sd23_i_codigo;
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
		 		$hora_fim = $clagendamentos->somahora($hora_ini,$intervalo+$mi_interva1);
		    }else{
		    	$hora_fim = "";
		    }

			?>
			<tr style="display:<?=$codigo!=0&&(($linhas-1)>$h)?'none':'' ?>" id="<?=$codigo!=0?($obj_undmedhorario->sd30_i_codigo.'_'.$h):'' ?>" >
				<td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=($h+1)?></td>
				<td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=substr($hora_ini,0,5) ?></td>
				<td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=substr($hora_fim,0,5) ?></td>
				<td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=$reservada ?></td>
				<td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=$obj_undmedhorario->sd30_c_tipograde=="I"?"Intervalo":"Período" ?></td>
				<td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=$cgs ?></td>
				<td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=$paciente ?></td>
				<td class='opcoes' nowrap>
					<!-- <a title='Lançar conteúdo da linha'  href='#' onclick="js_lancar(<?=$codigo?>,<?=$obj_undmedhorario->sd30_i_codigo?>,'<?=$ano?>','<?=$mes?>','<?=$dia?>',<?=($h+1)?>,'<?=substr($hora_ini,0,5) ?>','<?=substr($hora_fim,0,5) ?>');">&nbsp;L&nbsp;</a>
					&nbsp;&nbsp; -->
					<a title='Anular conteúdo da linha' href='#' onclick='js_excluir(<?=$codigo?>);return false;'>&nbsp;A&nbsp;</a>
					&nbsp;&nbsp;
					<a title='Comprovante de Agendamento' href='#' onclick='js_comprovante(<?=$codigo?>);return false;'>&nbsp;C&nbsp;</a>
					&nbsp;&nbsp;
					<a title='Emissão da FAA' href='#' onclick='js_emissaofaa(<?=$codigo?>);return false;'>&nbsp;F&nbsp;</a>
					&nbsp;&nbsp;
					<a title='Emissão do Prontuário Médico' href='#' onclick='js_emissaopm(<?=$cgs?>);return false;'>&nbsp;P&nbsp;</a>
				</td>
			</tr>
			<?
		    if( $intervalo != 0){
				$hora_ini    = $clagendamentos->somahora($hora_ini,($intervalo+$mi_interva2));
				$mi_interva1 = -1;
				$mi_interva2 = 0;
		    }

		} // fim for h

	}//fim for
}//fim if ausencias
?>
</table>
<input type="hidden" name="simples" id="simples" value="0">
</body>
</html>

<script>

//Tempo estimado para recarregar agenda
//window.setInterval( js_reload, 40000 );
function js_reload(){
	location.reload();
}


function js_ocultar(obj,id){
	var src = obj.src;
	var ultimo_id = "";
	var table = document.all ? document.all.tbl_agendados : document.getElementById('tbl_agendados');
	id = ""+id+"";

	if( src.lastIndexOf("seta_down.png") != -1 ){
		obj.src = "skins/img.php?file=Controles/seta_up.png";
		for (var r = 0; r < table.rows.length; r++){
			var id2 = table.rows[r].id;
			if( id == id2.substr(0, id.length ) && id2.length > id.length ){
				table.rows[r].style.display = '';
			}
		}

	}else{
		obj.src = "skins/img.php?file=Controles/seta_down.png";
		for (var r = 0; r < table.rows.length; r++){
			var id2 = table.rows[r].id;
			if( id == id2.substr(0, id.length ) && id2.length > id.length ){
				table.rows[r].style.display = 'none';
				ultimo_id = r;
			}
		}
		if( ultimo_id != "" ){
			table.rows[ultimo_id].style.display = '';
		}
	}

}

function js_emissaofaa( sd23_i_codigo ){
	if( sd23_i_codigo == 0 ){
		alert('Paciente não informado.');
	}else{
	 	sd23_d_consulta = parent.document.getElementById('sd23_d_consulta').value;

	  	a =  sd23_d_consulta.substr(6,4);
	 	m = (sd23_d_consulta.substr(3,2))-1;
	  	d =  sd23_d_consulta.substr(0,2);
	  	data = new Date(a,m,d);
	  	dia= data.getDay()+1;

		query  = 'sau2_fichaatend002.php';
		query += '?unidade='+parent.document.form1.sd02_i_codigo.value;
		query += '&agendamentofa=true';
		query += '&sd27_i_codigo='+parent.document.form1.sd27_i_codigo.value;
		query += '&chave_diasemana='+dia;
	  	query += '&sd23_d_consulta='+sd23_d_consulta;
    	query += '&codigos='+sd23_i_codigo;

		var WindowObjectReference;
		var strWindowFeatures = "menubar=yes,location=no,resizable=yes,scrollbars=yes,status=yes";
		WindowObjectReference = window.open(query,"CNN_WindowName", strWindowFeatures);

		//jan = window.open(query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
		//jan.moveTo(0,0);
	}
}

//function js_comprovante( sd23_i_codigo ){
//	parent.js_comprovante(sd23_i_codigo);
	//x  = 'sau2_agendamento004.php';
	//x += '?sd23_i_codigo='+sd23_i_codigo;
	//x += '&diasemana='+parent.document.form1.diasemana.value;

	//jan = window.open(x,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
	//jan.moveTo(0,0);

//}

function js_comprovante(sd23_i_codigo){
   obj=parent.document.form1;
   if(obj.rh70_estrutural.value==''){
       alert('Especialidade não informada!');
       return false;
   }
   if(obj.sd03_i_codigo.value==''){
       alert('Proficional não informado!');
       return false;
   }
   if(obj.sd23_d_consulta.value==''){
       alert('Data não informada!');
       return false;
   }
   //executa
   x  = 'sau2_agendamento004.php';
   x += '?sd23_i_codigo='+sd23_i_codigo;
   x += '&diasemana='+parent.document.form1.diasemana.value;
   jan = window.open(x,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
   jan.moveTo(0,0);

}

function js_lancar(id_ficha,sd30_i_codigo, ano, mes, dia, sd23_i_ficha, sd23_c_hora,sd23_c_fim){

	if( id_ficha == undefined || id_ficha == 0 ){
		data = new Date(ano,mes-1,dia);
		gnow = new Date(<?=date('Y',db_getsession('DB_datausu'))?>,<?=date('m',db_getsession('DB_datausu'))-1?>,<?=date('d',db_getsession('DB_datausu'))?>);
		hora = '"'+new Date(<?=date('H')?>)+'"';
		posicao = hora.indexOf(":", hora)
		hora = hora.substr(posicao-2,5);
		ok = false;

		if(sd23_c_fim != ""){
			if((data == gnow && sd23_c_fim <= hora ) || (data > gnow)){
				ok = true;
			}
		}else{
			ok = true;
		}

		if( data < gnow ){
			alert( 'Você não pode lançar uma agendamento anterior a data atual. ');
		}else if( ok = true ){
			top = ( screen.availHeight-600 ) / 2;
			left = ( screen.availWidth-600 ) / 2;
			x  = 'sau4_agendamento003.php';
			x += '?sd30_i_codigo='+sd30_i_codigo;
			x += '&sd23_d_consulta='+ano+'/'+mes+'/'+dia;
			x += '&sd23_i_ficha='+sd23_i_ficha;
			x += '&sd23_c_hora='+sd23_c_hora;
			x += '&db_opcao=1';
			x += '&rh70_sequencial='+parent.document.form1.rh70_sequencial.value;
			if( parent.document.form1.s125_i_procedimento != undefined ){
				x += '&s125_i_procedimento='+parent.document.form1.s125_i_procedimento.value;
			}

			js_OpenJanelaIframe('parent','db_iframe_agendamento',x,'Paciente',true, top, left, 600, 200);

		}else{
			alert('Impossível agendar em dia e hora indicado.');
		}
	}else{
		alert('Já foi lançado registro.');
	}
}


function js_excluir(id_ficha,z01_i_numcgs){
	if( id_ficha != undefined && id_ficha != 0 ){
		top = ( screen.availHeight-600 ) / 2;
		left = ( screen.availWidth-600 ) / 2;
		x  = 'sau1_agendaconsultaanula_simples001.php';
		x += '?s114_i_agendaconsulta='+id_ficha;
		x += '&db_opcao=1';
		x += '&sd27_i_codigo=<?=$sd27_i_codigo?>';
		x += '&chave_diasemana=<?=$chave_diasemana?>';
		x += '&sd23_d_consulta=<?=$sd23_d_consulta?>';
		js_OpenJanelaIframe('parent','db_iframe_agendamento',x,'Anulação',true, top, left, 600, 250);
	}else{
		alert('Registro não pode ser excluído.');
	}
}

function js_emissaopm( cgs ){
	if( cgs != "" ){
		window.open('sau4_prontuariomedico003.php?cgs='+cgs,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
	}else{
		alert('Deverá informar um CGS.' );
	}

}
</script>