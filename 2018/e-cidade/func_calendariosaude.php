<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
 
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

parse_str($HTTP_SERVER_VARS['QUERY_STRING']); // ta com o globals desativado no php -- Crestani

class calendario{

  var $sem;//Array com os dias da semana como índice
  var $mes;//Array com os meses do ano
  var $nome_objeto_data;
  var $shutdown_function = "";

  function inicializa() {//Atribui valores para $sem e $mes.

    $this->sem = array(
      'Sun' => 1,
      'Mon' => 2,
      'Tue' => 3,
      'Wed' => 4,
      'Thu' => 5,
      'Fri' => 6,
      'Sat' => 7
    );

    $this->mes = array(
      '1'  => 'JANEIRO',
      '2'  => 'FEVEREIRO',
      '3'  => 'MARÇO',
      '4'  => 'ABRIL',
      '5'  => 'MAIO',
      '6'  => 'JUNHO',
      '7'  => 'JULHO',
      '8'  => 'AGOSTO',
      '9'  => 'SETEMBRO',
      '10' => 'OUTUBRO',
      '11' => 'NOVEMBRO',
      '12' => 'DEZEMBRO'
    );
  }

  function aux($i){//Complementa a tabela com espaços em branco

    $retval = "";

    for($k = 0; $k < $i; $k++) {
      $retval.="<td width=\"20\">&nbsp;</td>";
    }

    return $retval;
  }

  function arr_search( $array, $valor ) {

    for( $x = 0; $x < count($array); $x++) {

    	if( $array[$x] == $valor ){
        return true;
    	}
    }

    return false;
  }
   
	function cria($dia, $mes, $ano, $marca = 0, $sd27_i_codigo, $datafinal, $result_unidademedico, $fechar = false) {

		$this->inicializa();
		$last  = date ("d", mktime (0,0,0,$mes+1,0,$ano));/*Inteiro do ultimo dia do mês*/

		if($last < $dia) {
			$dia = $last;
		}

		$verf   = date ("d/n/Y", mktime (0,0,0,$mes,$dia,$ano));/*Corrige qualquer data invalida*/
		$pieces = explode("/",$verf);
		$dia    = $pieces[0];
		$mes    = $pieces[1];
		$ano    = $pieces[2];
		$diasem = date ("D", mktime (0,0,0,$mes,1,$ano));/*String com dia da semana em inglês*/
		$str    = "";

		if($this->sem[$diasem] != 1) {/*Se dia semana diferente de domingo,completa com colunas em branco*/

			$valor = $this->sem[$diasem] - 1;
			$str   = "<tr align=center >".$this->aux($valor);
		}
		
		//Pega os dias da semana
		for( $x = 0; $x < pg_num_rows( $result_unidademedico ); $x++) {

			$obj_result   = db_utils::fieldsMemory($result_unidademedico,$x);
			$arr_diasem[] = $obj_result->sd30_i_diasemana;
		}
		
		for($i = 1; $i < ($last + 1); $i++) {       //; pega todos os dias do mes informado....

			$diasem = date ("D", mktime (0,0,0,$mes,$i,$ano));

			if($this->sem[$diasem] == 1) {

				$str .= "<tr align=\"center\" >";
				$s    = "$i";
			} else {
				$s = "$i";
			}

			$data_script = "$ano-$mes-$s";
			$str        .= "<td     ";
			
			if($marca != 0) {  // marca o dia atual em laranja
				$str .= "onmouseover=\"js_mostra('parecerr".$i."',event); \" onmouseout=\"js_oculta('parecerr".$i."',event);\" ";
			}

			if($this->sem[$diasem] == 1 || $this->sem[$diasem] == 7) {
				$str.="  bgcolor=#CCCCCC ";
			}
			
			$str .=" width=\"25\" ";
			
			if( ( $ano.str_pad($mes,2,'0',STR_PAD_LEFT).str_pad($i,2,'0',STR_PAD_LEFT) >= date("Ymd") ) &&
				( isset($arr_diasem) && $this->arr_search( $arr_diasem, $this->sem[$diasem] ) )
			) {

        $sCampos  = "sd30_i_turno, ";
				$sCampos .= "sd30_i_fichas, ";
				$sCampos .= "sd30_i_reservas, ";
				$sCampos .= "sd30_c_horaini, ";
				$sCampos .= "sd30_c_horafim, ";
				$sCampos .= "sd04_i_codigo, ";
				$sCampos .= "sd101_c_descr, ";
				$sCampos .= "sd30_i_codigo, ";
				$sCampos .= "( select count(sd23_d_consulta) ";
				$sCampos .= "    from agendamentos ";
				$sCampos .= "    where sd23_d_consulta = '{$ano}/{$mes}/{$s}' ";
				$sCampos .= "     and not exists ( select * ";
		    $sCampos .= "										    from agendaconsultaanula ";
		    $sCampos .= "										   where s114_i_agendaconsulta = sd23_i_codigo ) ";
				$sCampos .= "     and sd23_i_undmedhor= sd30_i_codigo ";
				$sCampos .= "   group by sd23_d_consulta)::integer as total_agendado";

				$str_query = "select {$sCampos} ";
				$str_query .= "  from undmedhorario ";
				$str_query .= "       inner join especmedico    on sd27_i_codigo                = sd30_i_undmed ";
				$str_query .= "       inner join unidademedicos on sd04_i_codigo                = sd27_i_undmed ";
				$str_query .= "       inner join medicos        on sd03_i_codigo                = sd04_i_medico ";
				$str_query .= "       left  join sau_tipoficha  on sau_tipoficha.sd101_i_codigo = undmedhorario.sd30_i_tipoficha ";
				$str_query .= " where sd27_i_codigo    = {$sd27_i_codigo} ";
				$str_query .= "   and sd30_i_diasemana = {$this->sem[$diasem]} ";
				$str_query .= "   and (    sd30_d_valfinal is null ";
				$str_query .= "         or ( sd30_d_valfinal is not null and sd30_d_valfinal > '{$ano}/{$mes}/{$s}' ) ) ";
				$str_query .= "   and (    sd30_d_valinicial is null ";
				$str_query .= "         or ( sd30_d_valinicial is not null and sd30_d_valinicial <= '{$ano}/{$mes}/{$s}' ) )";
             	
				$result_undmedhorario = db_query( $str_query ) or die( "ERRO: <p> $str_query ");
				
				$str_query  = "select *, case when sd06_i_tipo = 1 then ";
        $str_query .= "                  'Folga' ";
        $str_query .= "               else ";
        $str_query .= "                  'Férias' ";
        $str_query .= "               end as sd06_c_tipo ";
        $str_query .= " from ausencias ";
				$str_query .= "where sd06_i_especmed = {$sd27_i_codigo} ";
        $str_query .= "  and '{$ano}/{$mes}/{$s}' between sd06_d_inicio and sd06_d_fim";
            				  
				$result_ausencias = db_query( $str_query );
				$booMostradiv     = false;

				if( pg_num_rows($result_ausencias) > 0 ) {

					$obj_ausencias  = db_utils::fieldsMemory($result_ausencias,0);
					$str           .= " bgcolor=white > ";  // marcar o dia atual
					$str           .=" <a href=\"\" onclick=\"return janela($s,$mes,$ano,$fechar);\"><font size='4'> $s</a> ";
					$str_msg        = "Profissional não esta atendendo.<br> Situação: {$obj_ausencias->sd06_c_tipo} ";
					$booMostradiv   = true;
				} else if( pg_num_rows($result_undmedhorario) != 0 ) {

					//Pega mais de uma hora para o mesmo dia
					$str_msg            = "";
					$int_sd30_i_fichas  = 0;
					$int_total_agendado = 0;
					$str_br             = "";

					for( $xHora = 0; $xHora < pg_num_rows($result_undmedhorario); $xHora++) {

						$obj_undmedhorario   = db_utils::fieldsMemory($result_undmedhorario,$xHora);
						$int_total_agendado += $obj_undmedhorario->total_agendado;
						$int_sd30_i_fichas  += $obj_undmedhorario->sd30_i_fichas;
						$str_msg            .= $str_br."{$obj_undmedhorario->sd101_c_descr} {$obj_undmedhorario->sd30_c_horaini} as {$obj_undmedhorario->sd30_c_horafim} - Saldo: ".($obj_undmedhorario->sd30_i_fichas - $obj_undmedhorario->total_agendado );
						$str_br              = "<br>";
					}

					if( $this->arr_search( $arr_diasem, $this->sem[$diasem] ) ) {

            $str_cor = " bgcolor=#00FF00 > ";  // marcar o dia atual

						if( ( $int_sd30_i_fichas - $int_total_agendado ) == 0 ) {
							$str_cor = " bgcolor=red > ";  // marcar o dia atual
						}else if( $int_total_agendado > 0 ){
							$str_cor = " bgcolor=yellow > ";  // marcar o dia atual
						}
					}
					
					$str    .= $str_cor;
					$str    .=" <a href=\"\" onclick=\"return janela($s,$mes,$ano,$fechar,".($obj_undmedhorario->sd30_i_fichas - $obj_undmedhorario->total_agendado ).");\"><font size='4'> $s</a> ";
					$booMostradiv = true;
				}
				
				if( $booMostradiv ) {

					//*Div
					$str .= "
			               <div  onmouseover=js_oculta('parecerr$i',event)  name='parecerr$i' id='parecerr$i' style='position:absolute;visibility:hidden;left: 0px;top: 0px'>
			                <table  height='100%' width='100%' border='0' cellspacing='0' cellpadding='0'>
			                 <tr>
			                  <td height='1'>
			                  </td>
			                 </tr>
			                </table>
			                <table align='left' height='100%' width='100%' border='1' cellspacing='1' cellpadding='1'>
			                 <tr>
			                  <td  bgcolor='#f3f3f3'   align='justify'>
			                    <FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>
			                    <center>Data: $s/$mes/$ano  &nbsp;&nbsp;&nbsp; <a title='Fechar' href='' onclick=js_oculta('parecerr$i',event)>F</a> </center> <hr>
			                    $str_msg
			                    </FONT>
			                   </td>
			                 </tr>
			                </table>
			               </div>
			               ";
			         	//Fim Div
				} else {
          $str .="><font size='4'> $s";
				}
			} else {
        $str .="><font size='4'> $s";
			}
			
			$str .= "</font> </td>";
			
			if($this->sem[$diasem] == 7) {
				$str .= "</tr>";
			}
		}
		
		$diasem = date ("D", mktime (0,0,0,$mes,$last,$ano));

		if($this->sem[$diasem] != 7) {

			$valor = 7 - $this->sem[$diasem];
			$str   = $str.$this->aux($valor)."</tr>";
		}

		$str = "
			       <center>
			  <table border=\"1\"  cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" height=\"100%\">
			  <tr>
			   <td colspan='4'>
			     <table border=\"1\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" height=\"100%\" >
			     <tr align=\"center\">
			       <td width=\"100%\" colspan=\"7\" nowrap>
			        <FONT SIZE='1' FACE='Verdana' COLOR='black'>
			           <a href=\"func_calendariosaude.php?".($this->shutdown_function!=""?"shutdown_function=".$this->shutdown_function."&":"")."nome_objeto_data=".$this->nome_objeto_data."&mes_solicitado=".($mes)."&ano_solicitado=".($ano-1)."&sd27_i_codigo=".@$sd27_i_codigo." \"> << </a>
			           	   $ano
				         <a href=\"func_calendariosaude.php?".($this->shutdown_function!=""?"shutdown_function=".$this->shutdown_function."&":"")."nome_objeto_data=".$this->nome_objeto_data."&mes_solicitado=".($mes)."&ano_solicitado=".($ano+1)."&sd27_i_codigo=".@$sd27_i_codigo." \"> >> </a>   
			        </font>
			       </td>
			     </tr>
			     <tr align=\"center\">
			       <td width=\"100%\" colspan=\"7\" nowrap>
			        <FONT SIZE='1' FACE='Verdana' COLOR='black'>
			         <a href=\"func_calendariosaude.php?".($this->shutdown_function!=""?"shutdown_function=".$this->shutdown_function."&":"")."nome_objeto_data=".$this->nome_objeto_data."&ano_solicitado=".($ano)."&mes_solicitado=".($mes-1)."&sd27_i_codigo=".@$sd27_i_codigo." \"> << </a>
			         ".$this->mes[$mes]."
			         <a href=\"func_calendariosaude.php?".($this->shutdown_function!=""?"shutdown_function=".$this->shutdown_function."&":"")."nome_objeto_data=".$this->nome_objeto_data."&ano_solicitado=".($ano)."&mes_solicitado=".($mes+1)."&sd27_i_codigo=".@$sd27_i_codigo." \"> >> </a>
			
				</FONT> 
			       </td>
			     </tr>
			     <tr align=\"center\">
			       <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>D </font></td>
			       <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>S </font></td>
			       <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>T </font></td>
			       <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>Q </font></td>
			       <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>Q </font></td>
			       <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>S </font></td>
			       <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>S </font></td>
			       </tr>
			       ".$str."
			     </table>
			     </td>
			     </tr>
			     <tr>
			       <td bgcolor='white'><font size=1>Férias</td>
			       <td bgcolor='#00FF00'><font size=1>Liberado</td>
			       <td bgcolor='yellow'><font size=1>Marcado</td>
			       <td bgcolor='red'><font size=1>Lotado</td>			       			       			       
			     </tr>
			    </table>
			     ";
		echo $str; 
	} //fim function 
} 

$clcalendario = new calendario;

if(!isset($mes_solicitado)) {
  $mes_solicitado = date("n",db_getsession("DB_datausu"));
} else if( $mes_solicitado > 12 || $mes_solicitado < 1 ) {

	$ano_solicitado = $mes_solicitado > 12 ? ($ano_solicitado + 1) : ($ano_solicitado - 1);
	$mes_solicitado = $mes_solicitado > 12 ? 1 : 12;
}

if (!isset($ano_solicitado)) {
  $ano_solicitado = date("Y",db_getsession("DB_datausu"));
}

if(isset($shutdown_function)) {
  $clcalendario->shutdown_function = $shutdown_function;
}

$clcalendario->nome_objeto_data = $nome_objeto_data;

if( isset($sd27_i_codigo) && (int)@$sd27_i_codigo != 0 ) {

	//Pega total de dias para agendamentos
	$strData  = date("$ano_solicitado")."/";
	$strData .= date("$mes_solicitado")."/";
	$strData .= date( "t", mktime(0, 0, 0, $mes_solicitado, 1, $ano_solicitado) );
	//Pega total de dias para agendamentos
	
	$str_query  = "select *, current_date + sd04_i_numerodias as datafinal ";
  $str_query .= "  from undmedhorario ";
	$str_query .= "       inner join especmedico    on sd27_i_codigo = sd30_i_undmed ";
	$str_query .= "       inner join unidademedicos on sd04_i_codigo = sd27_i_undmed ";
	$str_query .= "       inner join unidades       on sd02_i_codigo = sd04_i_unidade ";
	$str_query .= " where sd27_i_codigo = {$sd27_i_codigo}";
	$str_query .= "   and ( ( sd30_d_valinicial is null or sd30_d_valfinal is null ) or ";
	$str_query .= "     '{$strData}' between sd30_d_valinicial and sd30_d_valfinal) ";
	$str_query .= " order by sd30_i_diasemana";
	$result     = db_query($str_query) or die("ERRO: nos horários do profissional.");

	if( pg_num_rows($result) > 0 ) {

		$obj_result = db_utils::fieldsMemory($result,0);
		$fechar     = isset($fechar)?true:0;

		$clcalendario->cria(
      date("d",db_getsession("DB_datausu")),
      date("$mes_solicitado"),
      date("$ano_solicitado"),
      1,
      $sd27_i_codigo,
      $obj_result->datafinal,
      $result,
      $fechar
    );
	} else {
		echo "Nenhuma informação encontrada";
	}
} else {
	echo "Não foi informado Profissional."; 
}
?>

<script>

function js_mostra(nomepar,event) {

  PosMouseX = event.layerX;
  PosMouseY = event.layerY;

  document.getElementById(nomepar).style.top = 100;

	if( PosMouseY > 80 ) {
		document.getElementById(nomepar).style.top = 0;
	}

	document.getElementById(nomepar).style.visibility = "visible";
}

function js_oculta(nomepar,event) {
  document.getElementById(nomepar).style.visibility = "hidden";
}

function janela(d, m, a, fechar, saldo) {

	data   = new Date(a,m-1,d);
	dia    = data.getDay();
	semana = new Array(6);
	semana[0] = 'Domingo';
	semana[1] = 'Segunda-Feira';
	semana[2] = 'Terça-Feira';
	semana[3] = 'Quarta-Feira';
	semana[4] = 'Quinta-Feira';
	semana[5] = 'Sexta-Feira';
	semana[6] = 'Sábado';
	parent.document.form1.diasemana.value = semana[dia];
  <?php
  echo "parent.document.getElementById('".$nome_objeto_data."_dia').value = (d<10?'0'+d:d);\n";
  echo "parent.document.getElementById('".$nome_objeto_data."_mes').value = (m<10?'0'+m:m);\n";
  echo "parent.document.getElementById('".$nome_objeto_data."_ano').value = a;\n";
  echo "parent.js_comparaDatas".$nome_objeto_data."((d<10?'0'+d:d),(m<10?'0'+m:m),a);\n";
  
  echo "parent.document.getElementById('".$nome_objeto_data."_ano').setfocus;\n";
  
  if (isset($shutdown_function) && ($shutdown_function!='none')){
    echo $shutdown_function."\n";
  }
  ?>
  
  if( fechar == true ) {

  	x = 'parent.iframe_data_<?=$nome_objeto_data?>.hide();\n';
  	eval( x );
  }

  //Trexo anexado Guilherme 01/12/2009 T29243 [tag]
  //Rotina: Agendamento de Consultas Simplificado
  if(parent.document.form1.saldo!='undefined') {

    parent.document.form1.saldo.value = saldo;
    parent.document.getElementById('saldo_div').innerHTML = saldo + ' Fichas';
    parent.document.form1.consultas.disabled=false;
    parent.js_load_consulta();
    parent.iframe_data_sd23_d_consulta.hide();
  }
}

function janela_zera() {

  <?php
  echo "parent.document.getElementById('".$nome_objeto_data."_dia').value = '';\n";
  echo "parent.document.getElementById('".$nome_objeto_data."_mes').value = '';\n";
  echo "parent.document.getElementById('".$nome_objeto_data."_ano').value = '';\n";

  if (isset($shutdown_function) && ($shutdown_function!='none')){
    echo $shutdown_function."\n";
  }
  ?>
}

(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');

	if(input != null) {
    input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
  }
})();
</script>