<?
/*
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_tarefa_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cor_livre   = "#0EB01F";
$cor_ocupado = "#FF0000";

$cltarefa    = new cl_tarefa;
?>
<link href="estilos.css" rel="stylesheet" type="text/css">
	<form name="form2" method="post" action="<?=$PHP_SELF?>"> 
		<table border="0" cellspacing="1" cellpadding="0" height="20" width="200">
<?
	db_input("cols",10,"",true,"hidden",3,"");
	db_input("id_usuario",10,"",true,"hidden",3,"");
	db_input("data_inicial",10,"",true,"hidden",3,"");

	$vet_data  = explode("-",$data_inicial);
	$mes       = $vet_data[1];

	if(!isset($ano)) {
		$ano   = $vet_data[0];
	}

    $ano_atual = $ano;
    
	retorna_meses($mes, $mes_ant, $mes_prox);	

    $sql = "select at40_diaini, at40_diafim
	  	    from tarefa
			where at40_autorizada is true and 
                  to_char(at40_diaini,'MM')::integer = to_char(date('$data_inicial'),'MM')::integer
		    order by at40_diaini,at40_diafim";

    $result = $cltarefa->sql_record($sql);

	for($i = 0; $i < $cltarefa->numrows; $i++) {
	    db_fieldsmemory($result,$i);
	    $vet_dataini = explode("-", $at40_diaini);
	    $vet_datafim = explode("-", $at40_diafim);
		if(empty($at40_diafim)) {
			continue;
		}

	    $dia_ini           = $vet_dataini[2];  
	    $dia_fim           = $vet_datafim[2];  
	    $vet_periodo_ini[] = $dia_ini;
	    $vet_periodo_fim[] = $dia_fim;
	}	
	
	echo "<tr>
			<td><a style=\"text-decoration:none;color:#000000;\" href=\"#\" onclick='js_dt_ant(\"$mes_ant\",\"$ano\");'><<<</a></td>
            <td><h2><a style=\"text-decoration:none;color:#000000;\" href=\"#\" onclick='js_dt_ant(\"$mes_ant\",\"$ano\");'>".ucfirst(db_mes($mes_ant))."</a></h2></td>\n
			<td><h2>&nbsp;&nbsp;||&nbsp;&nbsp;</h2></td>
            <td><h2><a style=\"text-decoration:none;color:#000000;\" href=\"#\" onclick='js_dt_prox(\"$mes_prox\",\"$ano\");'>".ucfirst(db_mes($mes_prox))."</a></h2></td>\n
			<td><a style=\"text-decoration:none;color:#000000;\" href=\"#\" onclick='js_dt_prox(\"$mes_prox\",\"$ano\");'>>>></a></td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td><h2>Ano:</h2></td>
			<td><select name=\"ano\" onchange=\"js_troca_ano();\">";
	for($i=0; $i < 11; $i++) {
		if($ano_atual == $ano) {
			$sel = " selected";
		}
		else {
			$sel = " ";
		}
		if($i == 0) {
			$ano_atual = date("Y");
			$ano_atual--;
		} 
		if($i == 1) {
			$ano_atual = date("Y");
		} 
		echo "<option value=\"$ano_atual\"$sel>$ano_atual</option>\n";
		$ano_atual++;
	}
	echo "</select></td></tr>\n";		
?>
		</table>
		<table border="1" cellspacing="1" cellpadding="0" height="300" width="400">
<?
mostra_calendario($cltarefa,$cols,$id_usuario,$mes,$ano,$cor_livre,$cor_ocupado,@$vet_periodo_ini,@$vet_periodo_fim);

function mostra_calendario($cltarefa,$cols,$id_usuario,$mes,$ano,$cor_livre,$cor_ocupado,$vet_periodo_ini=null,$vet_periodo_fim=null) {
	$vet_dia_semana = array();

	$rs_tarefa      = $cltarefa->sql_record($cltarefa->sql_query_envol(null,"at40_diaini","at40_diaini","at45_usuario=$id_usuario and to_char(at40_diaini,'mm') = '$mes' and to_char(at40_diaini,'yyyy') = '$ano'"));
	
	echo "<tr><td colspan=\"$cols\" height=\"35\" valign=\"bottom\"><h2>Calendário - ".ucfirst(db_mes($mes))."</h2></td></tr>\n";
//	echo "<tr><td>Seg</td><td>Ter</td><td>Qua</td><td>Qui</td><td>Sex</td></tr>\n"; 
	
	if($cltarefa->numrows > 0) {
		$NumRegs   = pg_numrows($rs_tarefa);
		$NumCampos = pg_numfields($rs_tarefa);
	}
	else {
		$NumRegs   = 0;
		$NumCampos = 0;
	}
	
	$dia = "";
	for($i = 0; $i < $NumRegs; $i++) {
		$dif = false;
		for($j = 0; $j < $NumCampos; $j++) {
			if(pg_fieldtype($rs_tarefa, $j) == "date") {
				if(pg_result($rs_tarefa, $i, $j) != "") {
					$matriz_data = split("-", pg_result($rs_tarefa, $i, $j));
					$var_data = $matriz_data[2];
				} else {
					$var_data = "//";
				}
				if($dia != $var_data) {
					$dia = $var_data;
				}
				else {
					$dif = true;
				}
				if($dif == false) {
					$vet_dias[$var_data] = db_formatar($var_data,'s','0',2,'e',0);
				}
			}
		}
	}

	$vet_datas = gera_calendario($mes,$ano, $vet_dia_semana);

	echo "<tr>\n";
	for($i=0; $i < count($vet_datas); $i++) {
		if(($i%7) == 0) {
			echo "</tr>\n";
			echo "<tr>\n";
		}
		if(@$vet_dias[$vet_datas[$i]]) {
			$cor = $cor_ocupado;
		}
		else {
			$cor = $cor_livre;
		}

		for($j=0; $j < count($vet_periodo_ini); $j++) {
			if(@$vet_datas[$i] == db_formatar(@$vet_periodo_ini[$j],'s','0',2,'e',0)) {
				$cor = $cor_ocupado;
				break;
			}
		}

		for($j=0; $j < count($vet_periodo_fim); $j++) {
			if(@$vet_datas[$i] == db_formatar(@$vet_periodo_fim[$j],'s','0',2,'e',0)) {
				$cor = $cor_ocupado;
				break;
			}
		}

		$nome_semana = retorna_dia_semana($vet_dia_semana[$vet_datas[$i]]);

		$data_dia         = sprintf("%04d-%02d-%02d",$ano,$mes,@$vet_datas[$i]);
    	$sql              = "select at13_dia
	  		                 from tarefa_agenda
				             where at13_dia = '$data_dia' 
	    	    	         order by at13_dia";
		$rs_tarefa_agenda =  db_query($sql);
		$tem_agenda       =  pg_numrows($rs_tarefa_agenda);

		if($tem_agenda > 0) {
			$cor = $cor_ocupado;
		}
	
//		echo $sql . "<br>";					
		echo "<td bgcolor=\"$cor\" align=\"center\">$vet_datas[$i]$nome_semana</td>\n";
	}
	echo "</tr>\n";
	echo "</table>\n";
}
function gera_calendario($mes,$ano, &$vet_dia_semana) {
   global $k13_data;
   
   $data      = getdate(mktime(0,0,0,$mes+1,0,$ano));
   $ult_dia   = $data["mday"];
   $dia       = 1;
   $vet_datas = array();
   $contador  = 0;

   $sql       = "select k13_data 
                 from calend
				 where to_char(k13_data,'MM')::integer = $mes
                 order by k13_data";
   $rs_calend = db_query($sql);

   for($i = 0; $i < $ult_dia; $i++) {
	   $data_dia  = sprintf("%04d-%02d-%02d",$ano,$mes,$dia);
   	   $achou     = false;	
	   for($j = 0; $j < pg_numrows($rs_calend); $j++) {
			db_fieldsmemory($rs_calend, $j);
			
			if($data_dia == $k13_data) {
				$achou = true;
				break;
			}
	   }
   	
   	   if($achou==false) {	
		   $data = getdate(mktime(0,0,0,$mes,$dia,$ano));
		   if($data["wday"] > 0&&$data["wday"] < 6) {
	   	       $vet_datas[$contador] = db_formatar($dia,'s','0',2,'e',0);
	   	       $contador++;
	   	       $vet_dia_semana[db_formatar($dia,'s','0',2,'e',0)] = $data["wday"];
		   }
   	   } 	
   	   
	   $dia++;
   }   
   
   return($vet_datas);
}
function retorna_meses($mes, &$mes_ant, &$mes_prox) {
	switch($mes) {
		case 1   :
				   $mes_ant  = 12;
				   $mes_prox = "02";
				   break;
		case 2   :
				   $mes_ant  = "01";
				   $mes_prox = "03";
				   break;
		case 3   :
				   $mes_ant  = "02";
				   $mes_prox = "04";
				   break;
		case 4   :
				   $mes_ant  = "03";
				   $mes_prox = "05";
				   break;
		case 5   :
				   $mes_ant  = "04";
				   $mes_prox = "06";
				   break;
		case 6   :
				   $mes_ant  = "05";
				   $mes_prox = "07";
				   break;
		case 7   :
				   $mes_ant  = "06";
				   $mes_prox = "08";
				   break;
		case 8   :
				   $mes_ant  = "07";
				   $mes_prox = "09";
				   break;
		case 9   :
				   $mes_ant  = "08";
				   $mes_prox = "10";
				   break;
		case 10  :
				   $mes_ant  = "09";
				   $mes_prox = "11";
				   break;
		case 11  :
				   $mes_ant  = "10";
				   $mes_prox = "12";
				   break;
		case 12  :
				   $mes_ant  = "11";
				   $mes_prox = "01";
				   break;
		default :  
				   $mes_ant   = db_formatar($mes - 1,'s','0',2,'e',0);
		           $mes_prox  = db_formatar($mes + 1,'s','0',2,'e',0);
	}
}
function retorna_dia_semana($dia_semana) {
	switch($dia_semana) {
		case 1: $nome_semana = "(Seg)";
				break;
		case 2: $nome_semana = "(Ter)";
				break;
		case 3: $nome_semana = "(Qua)";
				break;
		case 4: $nome_semana = "(Qui)";
				break;
		case 5: $nome_semana = "(Sex)";
				break;
	}
	
	return($nome_semana);
}
?>
	</form>
		</table>
<script>
function js_troca_ano() {
	document.form2.submit();
}
function js_dt_prox(mes,ano) {
	document.form2.data_inicial.value=ano+"-"+mes+"-"+"01";
	document.form2.submit();
}
function js_dt_ant(mes,ano) {
	document.form2.data_inicial.value=ano+"-"+mes+"-"+"01";
	document.form2.submit();
}
</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
