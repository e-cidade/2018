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
include("dbforms/db_funcoes.php");
include("classes/db_tarefa_agenda_classe.php");
include("classes/db_tarefaparam_classe.php");
include("classes/db_tarefa_classe.php");
include("classes/db_db_usuarios_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cltarefa = new cl_tarefa;
$cldb_usuarios = new cl_db_usuarios;
//set_time_limit(30);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_pesq_data() {
	var data     = new String(document.form1.chave_pesquisa.value);
	var vet_data = data.split("/");
	
	js_data_prox(vet_data[2]+"-"+vet_data[1]+"-"+vet_data[0]);
}
function js_data_prox(data) {
	document.form1.data_ini.value=data;
	document.form1.submit();
}
function js_data_ant(data) {
	document.form1.data_ini.value=data;
	document.form1.submit();
}
function js_enviar() {
  document.form1.submit();
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
<form name="form1" action="func_agendamentotarefas.php">
  <tr>
    <td><b>Usuário:</b>&nbsp;&nbsp;
    <?
    if (!isset($at40_responsavel)) {
      global $at40_responsavel;
      $at40_responsavel = db_getsession("DB_id_usuario");
    }
    db_selectrecord('at40_responsavel',($cldb_usuarios->sql_record($cldb_usuarios->sql_query(null,"id_usuario,nome","nome"," usuarioativo = '1'"))),true,1,"alert(1);", "", "", "", "js_enviar()");
    ?>
    </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if(isset($data_ini)&&$data_ini!="") {
      	  $vet_data = explode("-",$data_ini);
		  $ano      = $vet_data[0];
	      $mes      = $vet_data[1];
	      $dia      = $vet_data[2];
      	  
		  $data_ini = retorna_data($ano, $mes, $dia, "inc");
		        	  
      	  $dbwhere  = "at45_usuario=$at40_responsavel and at40_diaini>='$data_ini' ";
      	  db_input("data_ini",10,"",true,"hidden",3,"");
//      	  db_input("at40_responsavel",10,"",true,"hidden",3,"");
	  }
	  else {
	  	  $dbwhere = "";	
	  }
  	  $sql = "select at40_diaini, case when at40_diafim is null then '2006-12-31' else at40_diafim end as at40_diafim
			  from tarefa
			  where at40_autorizada is true and 
                    to_char(at40_diaini,'MM')::integer = to_char(date('$data_ini'),'MM')::integer  and
      				to_char(at40_diaini,'DD')::integer >= to_char(date('$data_ini'),'DD')::integer and 
                    not at40_diafim is null
			  order by at40_diaini,at40_diafim ";
	  
  	  $result = $cltarefa->sql_record($sql);

	  for($i = 0; $i < $cltarefa->numrows; $i++) {
		  db_fieldsmemory($result,$i);
		  $vet_dataini = explode("-", $at40_diaini);
		  if(empty($at40_diafim)) {
  		    $vet_datafim = explode("-", '2006-12-31');
		  }
		  else {
  		    $vet_datafim = explode("-", $at40_diafim);
		  }
		    
		  $dia_ini           = $vet_dataini[2];  
		  $dia_fim           = $vet_datafim[2];  
		  $vet_periodo_ini[] = $dia_ini;
		  $vet_periodo_fim[] = $dia_fim;
	  }	

      $sql = $cltarefa->sql_query_envol("","at40_diaini,at40_horainidia,at40_sequencial,at40_descr","at40_diaini#at40_horainidia asc",$dbwhere);
	  db_grid($sql,$data_ini,$at40_responsavel,@$vet_periodo_ini,@$vet_periodo_fim);
      ?>
     </td>
   </tr>
</form>
</table>
<?
function db_grid($sql,$data,$id_usuario,$vet_periodo_ini=null,$vet_periodo_fim=null) {
	global $at53_horaini_manha, $at53_horafim_manha, $at53_horaini_tarde, $at53_horafim_tarde;  // parametros para controle de expediente
	global $at40_sequencial,$at40_horainidia,$at40_horafim,$at40_previsao,$at40_tipoprevisao;	// resumo de tarefas
	global $nome;	// nome do responsavel

	$cltarefa_agenda = new cl_tarefa_agenda;


	$result       = pg_exec($sql);
	$NumRows      = pg_numrows($result);
	$NumFields    = pg_numfields($result);

	$vet_data     = explode("-",$data);

	$ano_ant      = $vet_data[0];
	$mes_ant      = $vet_data[1];
	$dia_ant      = $vet_data[2] - 1;

	$ano_prox     = $vet_data[0];
	$mes_prox     = $vet_data[1];
	$dia_prox     = $vet_data[2] + 1; 

	$data_ant     = retorna_data($ano_ant, $mes_ant, $dia_ant, "dec");
	$data_prox    = retorna_data($ano_prox, $mes_prox, $dia_prox, "inc");

	$data_atual   = sprintf("%02d/%02d/%04d",$vet_data[2],$vet_data[1],$vet_data[0]);
	$data_inicial =  sprintf("%04d-%02d-%02d",$vet_data[0],$vet_data[1],$vet_data[2]);

	$cldb_usuarios = new cl_db_usuarios;
	$rs_usuarios   = $cldb_usuarios->sql_record($cldb_usuarios->sql_query($id_usuario,"nome",null,""));
	if($cldb_usuarios->numrows > 0) {
		db_fieldsmemory($rs_usuarios,0);
	}
	
	$cltarefa     = new cl_tarefa;
	$rs_tarefa    = $cltarefa->sql_record($cltarefa->sql_query_envol(null,"at40_sequencial,at40_diaini,at40_diafim,at40_horainidia,at40_horafim,at40_previsao,at40_tipoprevisao,at40_descr","at40_horainidia#at40_horafim",
                                                                     "at45_usuario=$id_usuario and at40_diaini>='$data_inicial'"));
	if($cltarefa->numrows > 0) {
		$NumRegs   = pg_numrows($rs_tarefa);
		$NumCampos = pg_numfields($rs_tarefa);
	}
	else {
		$NumRegs   = 0;
		$NumCampos = 0;
	}

	$rs_horafim    = $cltarefa->sql_record($cltarefa->sql_query_envol(null,"at40_diaini,at40_horafim","at40_horainidia#at40_horafim",
                                                                      "at45_usuario=$id_usuario and at40_diaini>='$data_inicial'"));
	if($cltarefa->numrows > 0) {
		$NumRegHfim   = pg_numrows($rs_horafim);
		$NumCampoHfim = pg_numfields($rs_horafim);
	}
	else {
		$NumRegHfim   = 0;
		$NumCampoHfim = 0;
	}
	
	$cltarefaparam = new cl_tarefaparam;
	$rs_param      = $cltarefaparam->sql_record($cltarefaparam->sql_query(null,"*",null,null));
	if($cltarefaparam->numrows > 0) {
		db_fieldsmemory($rs_param,0);
	}

	$vet_h_manha           = array();
	$vet_h_tarde           = array();
	$vet_horarios          = array();
	$vet_dbhorarios_ini    = array();
	$vet_dbhorarios_fim    = array();
	$vet_horarios_livres   = array();

	$vet_horarios_ocupados = array();
	$ind_horarios_ocupados = 0;

	$hora = 0;
	$min  = substr($at53_horaini_manha,3,2);
	while($hora < substr($at53_horafim_manha,0,2)) {
		if($hora == 0) {
			$hora = substr($at53_horaini_manha,0,2);
		}

		$horario = $hora . ":" . $min;
		if($min == "30") {
			$min   = "00";			
			$hora += 1;
			$hora  = db_formatar($hora,'s','0',2,'e',0);
		}
		else {
			$min  += 30;
		}

		if($hora > substr($at53_horafim_manha,0,2)) {
			break;
		}

		$vet_h_manha[] = $horario;
	}

	$vet_h_manha[] = $at53_horafim_manha;

	$hora = 0;
	$min  = substr($at53_horaini_tarde,3,2);
	while($hora < substr($at53_horafim_tarde,0,2)) {
		if($hora == 0) {
			$hora = substr($at53_horaini_tarde,0,2);
		}

		$horario = $hora . ":" . $min;
		if($min == "00") {
			$min   = "30";			
		}
		else {
			$min   = substr($at53_horaini_tarde,3,2);
			$hora += 1;
			$hora  = db_formatar($hora,'s','0',2,'e',0);
		}

		if($hora > substr($at53_horafim_tarde,0,2)) {
			break;
		}

		$vet_h_tarde[] = $horario;
	}

	$vet_h_tarde[] = $at53_horafim_tarde;

	$vet_horarios = array_merge($vet_h_manha,$vet_h_tarde);
	
	$cols         = $NumFields;
	$contar_reg   = 0;

	$cor_livre    = "#0EB01F";
	$cor_ocupado  = "#FF0000";
	$cor_fora     = "#6e77e8";

	$ini          = "";
	$fim          = "";
?>
<script>
  function js_mostra_text(liga,nomediv,evt){
	  evt = (evt)?evt:(window.event)?window.event:''; 
	  if(liga==true){
	      document.getElementById(nomediv).style.top = 0; //evt.clientY;
		  document.getElementById(nomediv).style.left = 0; //(evt.clientX+20);
		  document.getElementById(nomediv).style.visibility = 'visible';
	  }else
	      document.getElementById(nomediv).style.visibility = 'hidden';
  }
</script>
<?
	echo "<table id=\"TabDbLov\" border=\"1\" cellspacing=\"1\" cellpadding=\"0\">\n";
	echo "<tr>\n";
	echo "<td colspan=\"3\"><b>Responsável:&nbsp;&nbsp;<font size=\"2\">$id_usuario&nbsp;&nbsp;-&nbsp;&nbsp;$nome</font></b></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=\"3\"><b>Data:&nbsp;&nbsp;</b><input type=\"text\" name=\"chave_pesquisa\" value=\"$data_atual\" size=\"10\">";
	echo "&nbsp;&nbsp;<input type=\"button\" value=\"Pesquisar\" onclick=\"js_pesq_data();\"></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td><table id=\"TabDbLov\" border=\"1\" cellspacing=\"1\" cellpadding=\"0\">\n";
	$cols--;
	echo "<tr>\n";
	echo "  <td colspan='$cols'><input type='button' value='<<< Anterior'  onclick='js_data_ant(\"$data_ant\");'></td>\n";
	echo "  <td align='right'><input type='button' value='Próximo >>>' onclick='js_data_prox(\"$data_prox\");'></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";

	$cols++;
	echo "<tr>\n";
	echo "<td colspan='$cols' align='center'><h2>Data atual: $data_atual</h2></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	$clrotulocab = new rotulolov();
	for($i = 0; $i < $NumFields; $i++) {
		$clrotulocab->label(pg_fieldname($result, $i));
		echo "<td nowrap bgcolor=\"#6e77e8\" title=\"".$clrotulocab->title."\" align=\"center\">".ucfirst($clrotulocab->titulo)."</td>\n";
	}		
	echo "</tr>\n";

	if($NumRows > 0) {
		for($i = 0; $i < $NumRows; $i++) {
			for($j = 0; $j < $NumFields; $j++) {
				if($data != pg_result($result, $i, 0)) {
					break;
				}

				if(pg_fieldname($result,$j) == "at40_horainidia") {
					$vet_dbhorarios_ini[] = pg_result($result, $i, $j);
				}
			}
			$contar_reg++;
		}
	}

	if($NumRegHfim > 0) {
		for($i = 0; $i < $NumRegHfim; $i++) {
			for($j = 0; $j < $NumCampoHfim; $j++) {
				if($data != pg_result($rs_horafim, $i, 0)) {
					break;
				}

				if(pg_fieldname($rs_horafim,$j) == "at40_horafim") {
					$vet_dbhorarios_fim[] = pg_result($rs_horafim, $i, $j);
				}
			}
		}
	}

	$cont_livres = 0;
	for($i=0; $i < count($vet_horarios); $i++) {
		$vet_horarios_livres[$cont_livres] = $vet_horarios[$i];
		$cont_livres++;
	}

	if($contar_reg == 0) {
		for($i=0; $i < $cont_livres; $i++) {
			if($vet_horarios_livres[$i] == $at53_horafim_manha) {
				echo "<tr bgcolor=\"$cor_fora\">\n";
				echo "<td align=\"center\" style=\"text-decoration:none;color:#000000;\" nowrap>$data_atual&nbsp;</td>";
				echo "<td align=\"left\"   style=\"text-decoration:none;color:#000000;\" nowrap>$vet_horarios_livres[$i]&nbsp;</td>";
				echo "<td colspan=\"2\"align=\"left\"   style=\"text-decoration:none;color:#000000;\" nowrap>Fora de horário de expediente</td>";
				echo "</tr>\n";
			}
			else {
				$rs_tarefa_agenda = $cltarefa_agenda->sql_record($cltarefa_agenda->sql_query_envol(null,"at40_horainidia, at40_horafim","at13_dia,at13_horaini","at45_usuario=$id_usuario and at13_dia = '$data'"));
				
				if($cltarefa_agenda->numrows > 0) {
				    db_fieldsmemory($rs_tarefa_agenda,0);
				}  

				if($cltarefa_agenda->numrows == 0) {
					echo "<tr bgcolor=\"$cor_livre\">\n";
					echo "<td align=\"center\" style=\"text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"#\" onClick=\"parent.js_incluirtarefa('$vet_data[2]','$vet_data[1]','$vet_data[0]','$vet_horarios_livres[$i]')\">$data_atual</a>&nbsp;</td>";
					echo "<td align=\"left\"   style=\"text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"#\" onClick=\"parent.js_incluirtarefa('$vet_data[2]','$vet_data[1]','$vet_data[0]','$vet_horarios_livres[$i]')\">$vet_horarios_livres[$i]</a>&nbsp;</td>";
					echo "<td colspan=\"2\"align=\"left\"   style=\"text-decoration:none;color:#000000;\" nowrap>Livre</td>";
					echo "</tr>\n";
				}
				else {
					if($at40_horainidia >= $vet_horarios_livres[$i]||
					   $at40_horafim    <= $vet_horarios_livres[$i]) {
						echo "<tr bgcolor=\"$cor_livre\">\n";
						echo "<td align=\"center\" style=\"text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"#\" onClick=\"parent.js_incluirtarefa('$vet_data[2]','$vet_data[1]','$vet_data[0]','$vet_horarios_livres[$i]')\">$data_atual</a>&nbsp;</td>";
						echo "<td align=\"left\"   style=\"text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"#\" onClick=\"parent.js_incluirtarefa('$vet_data[2]','$vet_data[1]','$vet_data[0]','$vet_horarios_livres[$i]')\">$vet_horarios_livres[$i]</a>&nbsp;</td>";
						echo "<td colspan=\"2\"align=\"left\"   style=\"text-decoration:none;color:#000000;\" nowrap>Livre</td>";
						echo "</tr>\n";
					}
					else {
						echo "<tr bgcolor=\"$cor_ocupado\">\n";
						echo "<td align=\"center\" style=\"text-decoration:none;color:#000000;\" nowrap>$data_atual&nbsp;</td>";
						echo "<td align=\"left\"   style=\"text-decoration:none;color:#000000;\" nowrap>$vet_horarios_livres[$i]$fim&nbsp;</td>";
						echo "<td colspan=\"2\"align=\"left\"   style=\"text-decoration:none;color:#000000;\" nowrap>Ocupado</td>";
						echo "</tr>\n";
						$ocupado = true;
					}
				}
			}
		}

		if($cols > 0) {
//			$cols++;
//			echo "<tr>\n";
//			echo "<td colspan='$cols' align='center'><h3><font color='#FF0000'>Data sem tarefas cadastradas!</font></h3></td>\n";
//			echo "</tr>\n";
		}
	}
	else {
		for($i=0; $i < $cont_livres; $i++) {
			if($vet_horarios_livres[$i] == $at53_horafim_manha) {
				echo "<tr bgcolor=\"$cor_fora\">\n";
				echo "<td align=\"center\" style=\"text-decoration:none;color:#000000;\" nowrap>$data_atual&nbsp;</td>";
				echo "<td align=\"left\"   style=\"text-decoration:none;color:#000000;\" nowrap>$vet_horarios_livres[$i]&nbsp;</td>";
				echo "<td colspan=\"2\"align=\"left\"   style=\"text-decoration:none;color:#000000;\" nowrap>Fora de horário de expediente</td>";
				echo "</tr>\n";
			}
			else {
				$achou = false;
				$ini   = "";
				for($ii = 0; $ii < count($vet_dbhorarios_ini); $ii++) {
					if($vet_horarios_livres[$i] == $vet_dbhorarios_ini[$ii]) {
						$achou = true;
						$ini   = " - I";
					}
				}
				if(!$achou) {
					$ocupado = false;
					$fim     = "";
					for($jj = 0; $jj < count($vet_dbhorarios_fim); $jj++) {
						if($vet_horarios_livres[$i] == $vet_dbhorarios_fim[$jj]) {
							$fim = " - F";
						}					
							
						if($vet_horarios_livres[$i] >= $vet_dbhorarios_ini[$jj] &&
						   $vet_horarios_livres[$i] <= $vet_dbhorarios_fim[$jj]) {
							$ocupado = true;
						    break;	
						}
					}

					if(!$ocupado) {
						$rs_tarefa_agenda = $cltarefa_agenda->sql_record($cltarefa_agenda->sql_query_envol(null,"at40_horainidia, at40_horafim","at13_dia,at13_horaini","at45_usuario=$id_usuario and at13_dia = '$data'"));
						if($cltarefa_agenda->numrows > 0) {
						    db_fieldsmemory($rs_tarefa_agenda,0);
						}  

						if($cltarefa_agenda->numrows == 0) {
							echo "<tr bgcolor=\"$cor_livre\">\n";
							echo "<td align=\"center\" style=\"text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"#\" onClick=\"parent.js_incluirtarefa('$vet_data[2]','$vet_data[1]','$vet_data[0]','$vet_horarios_livres[$i]')\">$data_atual</a>&nbsp;</td>";
							echo "<td align=\"left\"   style=\"text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"#\" onClick=\"parent.js_incluirtarefa('$vet_data[2]','$vet_data[1]','$vet_data[0]','$vet_horarios_livres[$i]')\">$vet_horarios_livres[$i]</a>&nbsp;</td>";
							echo "<td colspan=\"2\"align=\"left\"   style=\"text-decoration:none;color:#000000;\" nowrap>Livre</td>";
							echo "</tr>\n";
						}
						else {
							if(@$at40_horainidia >= $vet_horarios_livres[$i]||
							   @$at40_horafim    <= $vet_horarios_livres[$i]) {
								echo "<tr bgcolor=\"$cor_livre\">\n";
								echo "<td align=\"center\" style=\"text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"#\" onClick=\"parent.js_incluirtarefa('$vet_data[2]','$vet_data[1]','$vet_data[0]','$vet_horarios_livres[$i]')\">$data_atual</a>&nbsp;</td>";
								echo "<td align=\"left\"   style=\"text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"#\" onClick=\"parent.js_incluirtarefa('$vet_data[2]','$vet_data[1]','$vet_data[0]','$vet_horarios_livres[$i]')\">$vet_horarios_livres[$i]</a>&nbsp;</td>";
								echo "<td colspan=\"2\"align=\"left\"   style=\"text-decoration:none;color:#000000;\" nowrap>Livre</td>";
								echo "</tr>\n";
							}
							else {
								echo "<tr bgcolor=\"$cor_ocupado\">\n";
								echo "<td align=\"center\" style=\"text-decoration:none;color:#000000;\" nowrap>$data_atual&nbsp;</td>";
								echo "<td align=\"left\"   style=\"text-decoration:none;color:#000000;\" nowrap>$vet_horarios_livres[$i]$fim&nbsp;</td>";
								echo "<td colspan=\"2\"align=\"left\"   style=\"text-decoration:none;color:#000000;\" nowrap>Ocupado</td>";
								echo "</tr>\n";
								$ocupado = true;
							}
						}
					}
					else {
						echo "<tr bgcolor=\"$cor_ocupado\">\n";
						echo "<td align=\"center\" style=\"text-decoration:none;color:#000000;\" nowrap>$data_atual&nbsp;</td>";
						echo "<td align=\"left\"   style=\"text-decoration:none;color:#000000;\" nowrap>$vet_horarios_livres[$i]$fim&nbsp;</td>";
						echo "<td colspan=\"2\"align=\"left\"   style=\"text-decoration:none;color:#000000;\" nowrap>Ocupado</td>";
						echo "</tr>\n";
					}
				}
				else {
					echo "<tr bgcolor=\"$cor_ocupado\">\n";
					for($k = 0; $k < $NumRows; $k++) {
						$achou = false;
						for($j = 0; $j < $NumFields; $j++) {
							if($data != pg_result($result, $k, 0)) {
								break;
							}
							
							if(pg_result($result, $k, 1) == $vet_horarios_livres[$i]) {
								$achou = true;
							}
							if($achou) {
								if(pg_fieldtype($result, $j) == "date") {
									if(pg_result($result, $k, $j) != "") {
										$matriz_data = split("-", pg_result($result, $k, $j));
										$var_data = $matriz_data[2]."/".$matriz_data[1]."/".$matriz_data[0];
									} else {
										$var_data = "//";
									}
					// Testa se jah tem tarefa no mesmo horario, 
					// se existir coloca a proxima tarefa abaixo do mesmo horario
						
                		        $achou_horario = false;
					for($ii = 0; $ii < count($vet_horarios_ocupados); $ii++) {
				        	if($vet_horarios_livres[$i] == $vet_horarios_ocupados[$ii]) {
						    $achou_ocupado = true;
						    echo "</tr>";
					            echo "<tr bgcolor=\"$cor_ocupado\">\n";
						}
					}

					if(!$achou_horario) {
					    $vet_horarios_ocupados[$ind_horarios_ocupados] = $vet_horarios_livres[$i];
					    $ind_horarios_ocupados++;
					}
					else {
					    print_r($vet_horarios_ocupados);
					}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
									echo "<td align=\"center\" id=\"I".$k.$j."\" style=\"text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"#\" onClick=\"parent.js_mostratarefas(".pg_result($result, $k, 2).")\">".trim($var_data)."</a>&nbsp;</td>\n";
								}
								else { 
									if(pg_fieldtype($result, $j) == "float8") {
								        $var_data = db_formatar(pg_result($result, $k, $j), 'f', ' ');
				
										echo "<td align=\"right\" id=\"I".$k.$j."\" style=\"text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"#\" onClick=\"parent.js_mostratarefas(".pg_result($result, $k, 2).")\">".trim($var_data)."</a>&nbsp;</td>\n";
									}
									else {
									    if(pg_fieldtype($result, $j) == "bool") {
									        $var_data = (pg_result($result, $k, $j) == 'f' || pg_result($result, $k, $j) == '' ? 'Não' : 'Sim');
				
											echo "<td align=\"center\" id=\"I".$k.$j."\" style=\"text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"#\" onClick=\"parent.js_mostratarefas(".pg_result($result, $k, 2).")\">".trim($var_data)."</a>&nbsp;</td>\n";
								        }
								        else {
											if(pg_fieldtype($result, $j) == "text") {
												$tam_data = strlen(pg_result($result, $k, $j));
												if($tam_data > 40) {
													$var_data = "";
													$qtd      = round($tam_data/40);
													$start    = 0;
													for($kk = 0; $kk < $qtd; $kk++) {
														$var_data .= substr(pg_result($result, $k, $j),$start,40) . "<br>";
														if($kk == 0) {
															$start++;
														} 
														$start += 40;
													}
													$var_data = substr($var_data,0,strlen($var_data)-4);
												}
												else {
										   			$var_data = pg_result($result, $k, $j);
												}
				
												echo "<td id=\"I".$k.$j."\" style=\"text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"#\" onClick=\"parent.js_mostratarefas(".pg_result($result, $k, 2).")\">".trim($var_data)."</a>&nbsp;</td>\n";
											}
											else {
												if(pg_fieldname($result, $j) == "at40_sequencial") {
													echo "<td id=\"I".$k.$j."\" style=\"text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"#\" onClick=\"parent.js_mostratarefas(".pg_result($result, $k, 2).")\">".trim(pg_result($result, $k, $j))."$ini</a>&nbsp;</td>\n";
												}
												else {
													echo "<td id=\"I".$k.$j."\" style=\"text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"#\" onClick=\"parent.js_mostratarefas(".pg_result($result, $k, 2).")\">".trim(pg_result($result, $k, $j))."</a>&nbsp;</td>\n";
												}
											}				        	
								        }
								    }
								}
							}
						}
					}	

					echo "</tr>\n";
				}
			}
		}
	}

	echo "</table></td>\n";
	echo "<td valign=\"top\"><table border=\"1\" cellspacing=\"1\" cellpadding=\"0\" width=\"454\">\n";
			
	$cols = $NumCampos;
	
	if($cols == 0) {
		$cols = 7;
	}
	echo "<tr><td colspan=\"$cols\" height=\"20\">&nbsp;</td></tr>";
	echo "<tr><td colspan=\"$cols\"><h2>Resumo de Tarefas(Resp./Envolvido)</h2></td></tr>";
	echo "<tr>\n";
	
	if($NumCampos == 0) {
		echo "<tr><td colspan='$cols' align='center'><h3><font color='#FF0000'>Data sem tarefas cadastradas!</font></h3></td>\n</tr>";
	}
	
	for($i = 0; $i < $NumCampos; $i++) {
		if(pg_fieldname($rs_tarefa, $i) == "at40_tipoprevisao") {
			$clrotulocab->title  = "Prev. em";
			$clrotulocab->titulo = "Prev. em";
		}
		else if(pg_fieldname($rs_tarefa, $i) == "at40_sequencial") {
				 $clrotulocab->title  = "Tarefa";
			     $clrotulocab->titulo = "Tarefa";
		}
		else {
			if(pg_fieldname($rs_tarefa, $i) != "at40_descr") {
				$clrotulocab->label(pg_fieldname($rs_tarefa, $i));
			}
		}
		if(pg_fieldname($rs_tarefa, $i) != "at40_descr") {
			echo "<td nowrap bgcolor=\"$cor_fora\" title=\"".$clrotulocab->title."\" align=\"center\">".ucfirst($clrotulocab->titulo)."</td>\n";
		}
	}		
	echo "</tr>\n";
	for($i = 0; $i < $NumRegs; $i++) {
		echo "<tr bgcolor=\"$cor_ocupado\">\n";
		for($j = 0; $j < $NumCampos; $j++) {
			if($data != pg_result($rs_tarefa, $i, 1)) {
				break;
			}
			if(pg_fieldtype($rs_tarefa, $j) == "date") {
				if(pg_result($rs_tarefa, $i, $j) != "") {
					$matriz_data = split("-", pg_result($rs_tarefa, $i, $j));
					$var_data = $matriz_data[2]."/".$matriz_data[1]."/".$matriz_data[0];
				} else {
					$var_data = "//";
				}
				
				echo "<td onMouseOver=\"js_mostra_text(true,'div_text_".pg_result($rs_tarefa, $i, 0)."',event);\" onMouseOut=\"js_mostra_text(false,'div_text_".pg_result($rs_tarefa, $i, 0)."',event);\" align=\"center\" id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"#\" onClick=\"parent.js_mostratarefas(".pg_result($rs_tarefa, $i, 0).")\">".trim($var_data)."</a>&nbsp;</td>\n";
			}
			else { 
				if(pg_fieldtype($rs_tarefa, $j) == "float8") {
			        $var_data = db_formatar(pg_result($rs_tarefa, $i, $j), 'f', ' ');
						echo "<td align=\"right\" id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"#\" onClick=\"parent.js_mostratarefas(".pg_result($rs_tarefa, $i, 0).")\">".trim($var_data)."</a>&nbsp;</td>\n";
				}
				else {
				    if(pg_fieldtype($rs_tarefa, $j) == "bool") {
				        $var_data = (pg_result($rs_tarefa, $i, $j) == 'f' || pg_result($rs_tarefa, $i, $j) == '' ? 'Não' : 'Sim');
							echo "<td align=\"center\" id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"#\" onClick=\"parent.js_mostratarefas(".pg_result($rs_tarefa, $i, 0).")\">".trim($var_data)."</a>&nbsp;</td>\n";
			        }
			        else {
			        	if(pg_fieldname($rs_tarefa, $j) != "at40_descr") {
							if(pg_fieldtype($rs_tarefa, $j) == "text") {
								$var_data = pg_result($rs_tarefa, $i, $j);
								echo "<td valign=\"top\" id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"\" onClick=\"parent.js_mostratarefas(".pg_result($rs_tarefa, $i, 0).")\">".trim($var_data)."</a>&nbsp;</td>\n";
							}
							else {
								echo "<td onMouseOver=\"js_mostra_text(true,'div_text_".pg_result($rs_tarefa, $i, 0)."',event);\" onMouseOut=\"js_mostra_text(false,'div_text_".pg_result($rs_tarefa, $i, 0)."',event);\" style=\"text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"#\" onClick=\"parent.js_mostratarefas(".pg_result($rs_tarefa, $i, 0).")\">".trim(pg_result($rs_tarefa, $i, $j))."</a>&nbsp;</td>\n";
							}
			        	}				        	
			        }
				}
			}
		}
		echo "</tr>\n";
	}		

	echo "<tr><td colspan=\"$cols\" height=\"20\">&nbsp;</td></tr>";
	echo "</table>\n";
	for ($i = 0; $i < $NumRegs; $i ++) {
		for ($j = 0; $j < $NumCampos; $j ++) {
			if(pg_fieldname($rs_tarefa, $j) == "at40_descr") {
				if (pg_fieldtype($rs_tarefa, $j) == "text") {
					$clrotulocab->label(pg_fieldname($rs_tarefa, $j));
					echo "<div id='div_text_".pg_result($rs_tarefa, $i, 0)."' style='position:absolute;left:10px; top:10px; visibility:hidden ; background-color:#6699CC ; border:2px outset #cccccc; align:left'>
					       <table>
						       <tr>
 					    	     <td align='left'>
						           <font color='black' face='arial' size='2'><strong>".$clrotulocab->titulo."</strong>:</font><br>
						           <font color='black' face='arial' size='1'>".str_replace("\n", "<br>", pg_result($rs_tarefa, $i, $j))."</font>
							     </td>
							   </tr>
						   </table>
					      </div>\n";
				}
			}
		}
	}
?>
<iframe frameborder="1" name="calendario" src="func_calendar.php?cols=<?=$cols?>&id_usuario=<?=$id_usuario?>&data_inicial=<?=$data_inicial?>" height="400" width="450">	
<?
}
function retorna_data($ano, $mes, $dia, $executar) {
	global $k13_data;
	
	$dia_final = retorna_diafinal($mes,$ano); 

	if($dia > $dia_final) {
		$dia = 1;
	    if($mes == 12) {
	   	    $mes = 1;
	  	  	$ano++;
	  	}
	  	else {
	  	    $mes++;
	  	}
	}
	else if($dia == 0) {		// menor que dia final
			 if($mes == 12) {
			 	 $ano--;
			 }
			 
			 $mes--;
			 $dia = retorna_diafinal($mes,$ano); 
	}
	
	if(checkdate($mes,$dia,$ano)) {		
	    $dia_semana = getdate(mktime(0,0,0,$mes,$dia,$ano));
		while($dia_semana["wday"]==0||$dia_semana["wday"]==6) {
			if($executar == "inc") {
				$dia++;
			}
			if($executar == "dec") {
				$dia--;
			}
			
			if($dia > $dia_final) {
				$dia = 1;
			    if($mes == 12) {
			   	    $mes = 1;
			  	  	$ano++;
			  	}
			  	else {
			  	    $mes++;
			  	}
			}
			else if($dia == 0) {		// menor que dia final
					 if($mes == 12) {
					 	 $ano--;
					 }
					 
					 $mes--;
					 $dia = retorna_diafinal($mes,$ano); 
			}

		    $dia_semana = getdate(mktime(0,0,0,$mes,$dia,$ano));

			if(checkdate($mes,$dia,$ano)&&$dia_semana["wday"] < 0&&$dia_semana["wday"] > 6) {
				break;		
			}
		}
		$data = sprintf("%04d-%02d-%02d",$ano,$mes,$dia);

		$sql       = "select k13_data 
                      from calend
					  where to_char(k13_data,'MM')::integer = $mes
                      order by k13_data";
		$rs_calend = pg_exec($sql);

//		echo $sql."<br>";
		
		for($i = 0; $i < pg_numrows($rs_calend); $i++) {
			db_fieldsmemory($rs_calend, $i);
			
			if($data == $k13_data) {
				$vet_data = explode("-",$data);
				$ano      = $vet_data[0];
				$mes      = $vet_data[1];
				$dia      = $vet_data[2]; 

				if($executar == "inc") {
					$dia++;
				}
				if($executar == "dec") {
					$dia--;
				}

				$dia_final = retorna_diafinal($mes,$ano); 

				if($dia > $dia_final) {
					$dia = 1;
				    if($mes == 12) {
				   	    $mes = 1;
				  	  	$ano++;
				  	}
				  	else {
				  	    $mes++;
				  	}
				}
				else if($dia == 0) {		// menor que dia final
						 if($mes == 12) {
						 	 $ano--;
						 }
						 
						 $mes--;
						 $dia = retorna_diafinal($mes,$ano); 
				}

				if(checkdate($mes,$dia,$ano)) {		
				    $dia_semana = getdate(mktime(0,0,0,$mes,$dia,$ano));
					while($dia_semana["wday"]==0||$dia_semana["wday"]==6) {
						if($executar == "inc") {
							$dia++;
						}
						if($executar == "dec") {
							$dia--;
						}
						
						if($dia > $dia_final) {
							$dia = 1;
						    if($mes == 12) {
						   	    $mes = 1;
						  	  	$ano++;
						  	}
						  	else {
						  	    $mes++;
						  	}
						}
						else if($dia == 0) {		// menor que dia final
								 if($mes == 12) {
								 	 $ano--;
								 }
								 
								 $mes--;
								 $dia = retorna_diafinal($mes,$ano); 
						}
			
					    $dia_semana = getdate(mktime(0,0,0,$mes,$dia,$ano));
			
						if(checkdate($mes,$dia,$ano)&&$dia_semana["wday"] < 0&&$dia_semana["wday"] > 6) {
							break;		
						}
					}
				}
				
				$data = sprintf("%04d-%02d-%02d",$ano,$mes,$dia);
			}
		}
	}
	else {
		$data = "";
	}
	
	return($data);
}
// retorna o ultimo dia do mes
function retorna_diafinal($mes,$ano) {
	switch ($mes) {
  		case  1 :
  		case  3 :
  		case  5 :
  		case  7 :
  		case  8 :
   		case 10 :
  		case 12 : $dia = 31;       
  				  break;
  		case  2 : if(($ano%4) == 0) {
  					  $dia = 29;	
  				  }
  				  else {
  					  $dia = 28;	
  				  }
  				  break;
  		default : $dia = 30;
  	}
  	
  	return($dia);
}
?>
</body>
</html>