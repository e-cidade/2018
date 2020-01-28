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

//MODULO: atendimento
include("classes/db_tarefasolic_classe.php");
include("classes/db_tarefacadmotivo_classe.php");
include("classes/db_db_proced_classe.php");
include("classes/db_tarefacadsituacao_classe.php");
include("classes/db_db_modulos_classe.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_tarefaparam_classe.php");
include("classes/db_db_syscadproced_classe.php");
include("classes/db_db_projetosativcli_classe.php");

$cltarefa->rotulo->label();
$clrotulo = new rotulocampo;
//$clrotulo->label("nome");
$clrotulo->label("at41_proced");
$clrotulo->label("at47_situacao");
//$clrotulo->label("at46_descr");
$clrotulo->label("at49_modulo");
$clrotulo->label("nome_modulo");
$clrotulo->label("at05_seq");
$clrotulo->label("at54_sequencial");
$clrotulo->label("at36_usuario");
$clrotulo->label("at36_data");
$clrotulo->label("at36_hora");
$clrotulo->label("at36_ip");
$clrotulo->label("at40_autorizada");
$cl_db_syscadproced       = new cl_db_syscadproced;
$cltarefasolic       = new cl_tarefasolic;
$cl_tarefacadmotivo  = new cl_tarefacadmotivo;
$cltarefacadsituacao = new cl_tarefacadsituacao;
$cldb_proced         = new cl_db_proced;
$cldb_modulos        = new cl_db_modulos;
$cldb_usuarios       = new cl_db_usuarios;
$cldb_projetosativcli= new cl_db_projetosativcli;

$cltarefaparam = new cl_tarefaparam;
$result        = $cltarefaparam->sql_record($cltarefaparam->sql_query(null,"*",null,null));
if($cltarefaparam->numrows > 0) {
	db_fieldsmemory($result,0);
}

$where = "";

if($db_opcao==1||$db_opcao==11) {
	$where = "at05_seq = $at05_seq";
}
else {
	$where = "at44_tarefa = " . @$at40_sequencial;
}

      if($db_opcao==1||$db_opcao==11){
 	   $db_action="ate1_tarefa007.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="ate1_tarefa008.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="ate1_tarefa009.php";
      }  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<?
	$result = $cltarefasolic->sql_record("select at20_usuario, at10_nome, max(tipo) as tipo from (" . $cltarefasolic->sql_query_file($db_opcao,$where) . ") as x group by at20_usuario, at10_nome");

	if($cltarefasolic->numrows > 0) {
?>
	<table border="0">
		<tr><td colspan="3" height="20"><b>Solicitante(s):</b></td></tr>
<?
		$NumFields = pg_numfields($result);
		for($i = 0; $i < $cltarefasolic->numrows; $i++) {
			db_fieldsmemory($result,$i);
			for($j = 0; $j < $NumFields; $j++) {
				if(pg_fieldname($result,$j) == "at20_usuario"||pg_fieldname($result,$j) == "at21_usuario") {
					$at10_usuario = pg_result($result, $i, $j);
					break;
				}
			}
?>	
			<tr>
				<td width="80" align="right"><?=$at10_usuario?> - </td>
				<td width="250"><?=$at10_nome?></td>
				<td><? if($tipo == "S") { echo "Solicitante"; } else { echo "Envolvido"; } ?></td>
			</tr>
<?
		}
?>	
	</table>
<?
	}
?>
<table border="0">
  <tr>
  	<td nowrap colspan="2" align="right">
		<input name="bt_voltar" type="button" value="Voltar" title="Voltar" onClick="js_pesquisa_tarefa();">
  	</td>
  </tr>	
  <tr>
    <td nowrap title="<?=@$Tat40_sequencial?>">
       <?=@$Lat40_sequencial?>
    </td>
    <td> 
<?
db_input('at40_sequencial',10,$Iat40_sequencial,true,'text',3,"");

if(isset($at40_sequencial)){
  global $db30_codversao,$db30_codrelease; 
  $result_versao = $cldb_versaotarefa->sql_record($cldb_versaotarefa->sql_query(null,"db30_codversao,db30_codrelease",null," db29_tarefa = $at40_sequencial "));
  if($cldb_versaotarefa->numrows>0){
    db_fieldsmemory($result_versao, 0);
    echo "<font color='red'>Versão: 2.$db30_codversao.$db30_codrelease</font>";
  }         
}

db_input('at05_seq',10,$Iat05_seq,true,'hidden',3,"");
db_input('opcoes',10,@$opcoes,true,'hidden',3,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat40_responsavel?>">
       <?=@$Lat40_responsavel?>
    </td>
    <td> 
<?
if(isset($at40_responsavel)&&$at40_responsavel!="") {
	$at40_usuant = $at40_responsavel;
	db_input('at40_usuant',10,"",true,'hidden',3,"");
}
db_selectrecord('at40_responsavel',($cldb_usuarios->sql_record($cldb_usuarios->sql_query(($db_opcao==2?null:@$at40_responsavel),"id_usuario,nome","id_usuario",null))),true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="Motivo"><b>Motivo:</b></td>
    <td> 
<?
db_selectrecord('at54_sequencial',($cl_tarefacadmotivo->sql_record($cl_tarefacadmotivo->sql_query(($db_opcao==2?null:@$at54_sequencial),"at54_sequencial,at54_descr","at54_sequencial",null))),true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td ><b>Modulo</b></td>
    <td> 
  				<?
  				
  				//echo "modulo49 = ".@$at49_modulo." -- modulo22 = ".@$at22_modulo ;
				$sqlmod = "select codmod,nomemod from db_sysmodulo where ativo = 't' order by nomemod";
				//$sqlmod = "select id_item, nome_modulo from db_modulos order by nome_modulo";
		        $result_modulo = pg_exec($sqlmod);
		        if (@$at49_modulo!=""){
		        	$codmod = $at49_modulo;
		        }
		        db_selectrecord('codmod',$result_modulo,true,$db_opcao,"","","","0-Nenhum","js_verifica();");
		        ?>
   </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat41_proced?>"><b>Procedimento:</b></td>
    <td> 
<?

if (@$codmod != 0) {

	if (@$at40_sequencial != ""){
		$sqlproced = "select * from tarefasyscadproced where at37_tarefa = $at40_sequencial";
		
		$resultproced = pg_query($sqlproced);
		$linhasproced= pg_num_rows($resultproced);
		if ($linhasproced>0){
			db_fieldsmemory($resultproced, 0);
			$codproced = $at37_syscadproced;
		}	
	}
	$sqlprocedmod="
		select codproced,descrproced , nomemod
		from db_syscadproced 
		inner join db_sysmodulo on db_sysmodulo.codmod=db_syscadproced.codmod 
		where db_sysmodulo.codmod=$codmod
		order by descrproced";
	//$result_syscadproced = $cl_db_syscadproced->sql_record($cl_db_syscadproced -> sql_query ( null,"codproced,descrproced || ' - ' || nome_modulo","descrproced","codmod=$codmod"));
	$result_syscadproced = $cl_db_syscadproced->sql_record($sqlprocedmod);
	
} else {
		$sqlproced="
		select codproced,descrproced , nomemod
		from db_syscadproced 
		inner join db_sysmodulo on db_sysmodulo.codmod=db_syscadproced.codmod 
		order by descrproced";
		
	//$result_syscadproced = $cl_db_syscadproced->sql_record($cl_db_syscadproced -> sql_query ( null,"codproced,descrproced || ' - ' || nome_modulo","descrproced",""));
$result_syscadproced = $cl_db_syscadproced->sql_record($sqlproced);
}

db_selectrecord('codproced',$result_syscadproced,true,$db_opcao,"","","","0-Nenhum");
?>
    </td>
  </tr>
  
  
  
  <tr>
    <td nowrap title="<?=@$Tat41_proced?>"><b>Tipo:</b></td>
    <td> 
<?
db_selectrecord('at41_proced',($cldb_proced->sql_record($cldb_proced->sql_query(($db_opcao==2?null:@$at41_proced),"at30_codigo,at30_descr","at30_codigo",null))),true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat47_situacao?>"><b>Situação:</b></td>
    <td> 
<?
db_selectrecord('at47_situacao',($cltarefacadsituacao->sql_record($cltarefacadsituacao->sql_query(($db_opcao==2?null:@$at47_situacao),"*","at46_codigo",null))),true,$db_opcao,"");
?>
    </td>
  </tr>

<tr>
    <td nowrap title="Atividade do Projeto que esta integrado a tarefa">
       <strong>Atividade/Projeto:</strong>
    </td>
    <td> 
<?
db_selectrecord('at64_sequencial',$cldb_projetosativcli->sql_record($cldb_projetosativcli->sql_query(null,"at64_sequencial,trim(nomemod)||'-'||substr(at64_descricao,1,40) as at64_descricao","at64_sequencial",null)),true,$db_opcao,"","","","0");
?>
    </td>
  </tr>

<tr>
    <td nowrap title="<?=@$Tat40_descr?>">
       <?=@$Lat40_descr?>
    </td>
    <td> 
<?
db_textarea('at40_descr',5,50,$Iat40_descr,true,'text',$db_opcao,"");
?>
    </td>
  </tr>

<?

if ($db_opcao == 2) {
  $db_opcao = 3;
}

if (1 == 1) {
?>
  
  <tr>
    <td nowrap title="<?=@$Tat40_diaini?>">
       <?=@$Lat40_diaini?>
    </td>
    <td> 
<?
if($db_opcao==1||$db_opcao==11) {
	$at40_diaini_dia = date("d", db_getsession("DB_datausu"));
	$at40_diaini_mes = date("m", db_getsession("DB_datausu"));
	$at40_diaini_ano = date("Y", db_getsession("DB_datausu"));
}
db_inputdata('at40_diaini',@$at40_diaini_dia,@$at40_diaini_mes,@$at40_diaini_ano,true,'text',$db_opcao)
?>
    </td>
    <td nowrap title="<?=@$Tat40_diafim?>">
       <?=@$Lat40_diafim?>
    </td>
    <td> 
<?
db_inputdata('at40_diafim',@$at40_diafim_dia,@$at40_diafim_mes,@$at40_diafim_ano,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat40_previsao?>">
       <?=@$Lat40_previsao?>
    </td>
    <td> 
<?
//db_input('at40_previsao',10,$Iat40_previsao,true,'text',$db_opcao,"");
db_input('at40_previsao',10,"",true,'text',$db_opcao,"");
?>
  <input title=" Calcular previsão " value="Calc. previsão" type="hidden" name="btat40_previsao" onclick="js_calcula_prev();">
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat40_tipoprevisao?>">
       <?=@$Lat40_tipoprevisao?>
    </td>
    <td> 
<?
//db_input('at40_tipoprevisao',1,$Iat40_tipoprevisao,true,'text',$db_opcao,"")
  $matriz = array("h"=>"horas","d"=>"dias");             
  //$matriz = array("h"=>"horas");             
  db_select("at40_tipoprevisao", $matriz,true,$db_opcao,""); 
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat40_horainidia?>">
       <?=@$Lat40_horainidia?>
    </td>
    <td> 
<?
if($db_opcao==1||$db_opcao==11) {
	$at40_horainidia = db_hora();
}
//db_input('at40_horainidia',5,$Iat40_horainidia,true,'text',$db_opcao,"")
db_input('at40_horainidia',5,"",true,'text',$db_opcao,"onchange='js_verifica_hora(this.value,this.name)';")
?>
    </td>
    <td nowrap title="<?=@$Tat40_horafim?>">
       <?=@$Lat40_horafim?>
    </td>
    <td> 
<?
//db_input('at40_horafim',5,$Iat40_horafim,true,'text',3,"")
db_input('at40_horafim',5,"",true,'text',3,"");
}
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat40_progresso?>">
       <?=@$Lat40_progresso?>
    </td>
    <td> 
<?
//db_input('at40_progresso',10,$Iat40_progresso,true,'text',$db_opcao,"")
  $matriz = array("0"=>"0%",
                  "10"=>"10%", 
                  "20"=>"20%",
                  "30"=>"30%",
                  "40"=>"40%",
                  "50"=>"50%", 
                  "60"=>"60%",
                  "70"=>"70%",
                  "80"=>"80%",
                  "90"=>"90%",
                  "100"=>"100%");             
  db_select("at40_progresso", $matriz,true,$db_opcao); 
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat40_prioridade?>">
       <?=@$Lat40_prioridade?>
    </td>
    <td> 
<?
  $x = array("1"=>"Baixa",
             "2"=>"Média", 
             "3"=>"Alta",
	   );             
  db_select("at40_prioridade", $x,true,$db_opcao); 
?>
    </td>

    <td nowrap title="<?=@$Tat40_autorizada?>">
       <?=@$Lat40_autorizada?>
    </td>

		<td>
		
		<?
			db_input('at40_autorizada',10,"",true,'text',3,"");
	  ?>

		</td>
  </tr>



  <tr>
    <td nowrap title="<?=@$Tat36_usuario?>">
       <?=@$Lat36_usuario?>
    </td>
    <td> 
		<?
			db_input('at36_usuario',10,"",true,'text',3,"");
			echo " - " . @$criador . "<br>";
	  ?>
    </td>

    <td nowrap title="<?=@$Tat36_data?>">
       <?=@$Lat36_data?>
    </td>
    <td> 
		<?
			db_input('at36_data',10,"",true,'text',3,"");
	  ?>
    </td>

		
  </tr>


  <tr>
    <td nowrap title="<?=@$Tat36_hora?>">
       <?=@$Lat36_hora?>
    </td>
    <td> 
		<?
			db_input('at36_hora',10,"",true,'text',3,"");
	  ?>
    </td>

    <td nowrap title="<?=@$Tat36_ip?>">
       <?=@$Lat36_ip?>
    </td>
    <td> 
		<?
			db_input('at36_ip',10,"",true,'text',3,"");
	  ?>
    </td>

		
  </tr>




  <tr>
    <td colspan="2" align="center">
<?
if ($db_opcao != 3) {
if(isset($menu)){
	?>
    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" disabled type="button" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar"  disabled onclick="js_pesquisa();" >
<?
}else{
	?>
    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
	<?
}
	if($db_opcao==1||$db_opcao==11||$db_opcao==2||$db_opcao==22) {
?>
<input name="agendar"   type="button" id="agendar"   value="Agenda"    onclick="js_agenda();" >
<?
	}
	}
?>
	</td>
  </tr>	
  </table>
<?
	if(isset($erro_horario)&&$erro_horario==true) {
		if($db_opcao==1||$db_opcao==11) {
			db_msgbox("Usuário com tarefa nesse horario");
		}
		if(!isset($at40_diaini)) {
			$at40_diaini = $at40_diaini_ano . "-" . $at40_diaini_mes . "-" . $at40_diaini_dia; 
		}
		if(!isset($at40_diafim)) {
			$at40_diafim = $at40_diafim_ano . "-" . $at40_diafim_mes . "-" . $at40_diafim_dia; 
		}
		db_grid($cltarefa->sql_query_envol(null,"at40_sequencial,at40_diaini,at40_diafim,at40_horainidia,at40_horafim,at40_progresso","at40_sequencial,at45_usuario,at40_horainidia,at40_horafim",
                                           "at40_progresso < 100 and at40_sequencial<>$at40_sequencial and 
		                                    at45_usuario=$at40_responsavel and 
		                                    at40_diaini <= '$at40_diafim' and at40_diafim >= '$at40_diaini' and 
		                                    at40_horainidia <= '$at40_horafim' and at40_horafim >= '$at40_horainidia'")); 
	}

function db_grid($sql) {
	$result    = @pg_exec($sql);
	$NumRows   = @pg_numrows($result);
	$NumFields = @pg_numfields($result);
	
//	echo $sql;

	echo "<br><br><table id=\"TabDbLov\" border=\"1\" cellspacing=\"1\" cellpadding=\"0\">\n";
	
	if($NumRows == 0 or 1==1) {
		echo "<tr>\n";
		echo "<td>\n";
		echo "<br><br>Nenhum registro encontrado";
		echo "</td>\n";
	} else {
		echo "<tr>\n";
		$clrotulocab = new rotulolov();
		for($i = 0; $i < $NumFields; $i++) {
			$clrotulocab->label(pg_fieldname($result, $i));
			echo "<td nowrap bgcolor=\"#6e77e8\" title=\"".$clrotulocab->title."\" align=\"center\">".ucfirst($clrotulocab->titulo)."</td>\n";
		}		
		echo "	<td nowrap><input type=\"button\" value=\"Postegar Tarefas\" onClick=\"js_postegar();\"></td>\n";
		echo "</tr>\n";
		
		for($i = 0; $i < $NumRows; $i++) {
			echo "<tr bgcolor=\"#FFFFFF\">\n";
			for($j = 0; $j < $NumFields; $j++) {
			  	$executar = "js_retorna(".pg_result($result, $i, 0).")";
				if(pg_fieldtype($result, $j) == "date") {
					if(pg_result($result, $i, $j) != "") {
						$matriz_data = split("-", pg_result($result, $i, $j));
						$var_data = $matriz_data[2]."/".$matriz_data[1]."/".$matriz_data[0];
					} else {
						$var_data = "//";
					}
					echo "<td align=\"center\" id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"#\" onClick=\"".$executar."\">".trim($var_data)."</a>&nbsp;</td>\n";
				}
				else { 
					if(pg_fieldtype($result, $j) == "float8") {
				        $var_data = db_formatar(pg_result($result, $i, $j), 'f', ' ');
						echo "<td align=\"center\" id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"#\" onClick=\"".$executar."\">".trim($var_data)."</a>&nbsp;</td>\n";
					}
					else {
					    if(pg_fieldtype($result, $j) == "bool") {
					        $var_data = (pg_result($result, $i, $j) == 'f' || pg_result($result, $i, $j) == '' ? 'Não' : 'Sim');
							echo "<td align=\"center\" id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"#\" onClick=\"".$executar."\">".trim($var_data)."</a>&nbsp;</td>\n";
				        }
				        else {
							if(pg_fieldtype($result, $j) == "text") {
								$tam_data = strlen(pg_result($result, $i, $j));
								if($tam_data > 40) {
									$var_data = "";
									$qtd      = round($tam_data/40);
									$start    = 0;
									for($k = 0; $k < $qtd; $k++) {
										$var_data .= substr(pg_result($result, $i, $j),$start,40) . "<br>";
										if($k == 0) {
											$start++;
										} 
										$start += 40;
									}
									$var_data = substr($var_data,0,strlen($var_data)-4);
								}
								else {
						   			$var_data = pg_result($result, $i, $j);
								}
								echo "<td align=\"center\" id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"#\" onClick=\"".$executar."\">".trim($var_data)."</a>&nbsp;</td>\n";
							}
							else {
					   			$var_data = pg_result($result, $i, $j);
								echo "<td align=\"center\" id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"#\" onClick=\"".$executar."\">".trim($var_data)."</a>&nbsp;</td>\n";
							}				        	
				        }
					}
				}
			}

			echo "	<td nowrap>&nbsp;</td>\n";
			echo "</tr>\n";
		}
	}

	echo "</table>\n";
}
?>
  </center>
</form>
<script>
function js_verifica_hora(valor,campo){
  erro= 0;
  ms  = "";
  hs  = "";
  
  tam = "";
  pos = "";
  tam = valor.length;
  pos = valor.indexOf(":");  
  if(pos!=-1){
    if(pos==0 || pos>2){
      erro++;
    }else{
      if(pos==1){
	hs = "0"+valor.substr(0,1);
	ms = valor.substr(pos+1,2);
      }else if(pos==2){
        hs = valor.substr(0,2);
        ms = valor.substr(pos+1,2);
      }
      if(ms==""){
	ms = "00";
      }
    }
  }else{
    if(tam>=4){
      hs = valor.substr(0,2);
      ms = valor.substr(2,2);
    }else if(tam==3){
      hs = "0"+valor.substr(0,1);
      ms = valor.substr(1,2);
    }else if(tam==2){
      hs = valor;
      ms = "00";
    }else if(tam==1){
      hs = "0"+valor;
      ms = "00";
    }
  }
  if(ms!="" && hs!=""){
    if(hs>24 || hs<0 || ms>60 || ms<0){
      erro++
    }else{
      if(ms==60){
	ms = "59";
      }
      if(hs==24){
	hs = "00";
      }
      hora = hs;
      minu = ms;
    }    
  }

  if(erro>0){
    alert("Informe uma hora válida.");
  }
  if(valor!=""){    
    eval("document.form2."+campo+".focus();");
    eval("document.form2."+campo+".value='"+hora+":"+minu+"';");
  }
}
function js_agenda(){
  var data_ini = document.form1.at40_diaini_ano.value + "-" + 
                 document.form1.at40_diaini_mes.value + "-" + 
                 document.form1.at40_diaini_dia.value;

  js_OpenJanelaIframe('top.corpo.iframe_tarefa','db_iframe_agenda','func_agendamentotarefas.php?at40_responsavel='+document.form1.at40_responsavel.value+'&data_ini='+data_ini,'Agenda de Tarefas',true,'0');
}
function js_postegar() {
  js_OpenJanelaIframe('top.corpo.iframe_tarefa','db_iframe_tarefaenvol','func_tarefahorario.php?at40_sequencial='+document.form1.at40_sequencial.value+'&pesquisa_chave='+document.form1.at40_responsavel.value+'&funcao_js=parent.js_mostratarefahorario','Pesquisa',false,'0');
}
function js_pesquisaat41_proced(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_tarefa','db_iframe_db_proced','func_db_proced.php?funcao_js=parent.js_mostradb_proced1|at30_codigo','Pesquisa',true,'0');
  }else{
     if(document.form1.at41_proced.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_tarefa','db_iframe_db_proced','func_db_proced.php?pesquisa_chave='+document.form1.at41_proced.value+'&funcao_js=parent.js_mostradb_proced','Pesquisa',false,'0');
     }
  }
}
function js_mostradb_proced(chave,erro){
  if(erro==true){ 
    document.form1.at41_proced.focus(); 
    document.form1.at41_proced.value = ''; 
  }
}
function js_mostradb_proced1(chave1){
  document.form1.at41_proced.value = chave1;
  db_iframe_db_proced.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_tarefa','func_contarefa.php','Pesquisa',true,'0');
}
function js_preenchepesquisa(chave){
  db_iframe_tarefa.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_pesquisa_tarefa(){
  js_OpenJanelaIframe('','db_iframe_tarefa','func_contarefa.php?'+document.form1.opcoes.value,'Tarefas',true,'0','0');
}
function js_mostratarefas(chave,chave2){
//	alert(chave+' -- '+chave2);
  db_iframe_tarefa.hide();
  document.form1.at40_sequencial.value = chave;
	document.form1.opcoes.value = chave2;
  document.form1.submit();	
}
function js_calcula_prev(){
	var index            = document.form1.at40_tipoprevisao.selectedIndex;
	var tipo_previsao    = document.form1.at40_tipoprevisao.options[index].value;
    var previsao         = parseInt(document.form1.at40_previsao.value, 10);
    var hora             = document.form1.at40_horainidia.value;

	var hora_trabalhadas = <? echo '"' . $at53_horasdia . '"';      ?>;
	var horaini_manha    = <? echo '"' . $at53_horaini_manha . '"'; ?>;
	var horafim_manha    = <? echo '"' . $at53_horafim_manha . '"'; ?>;
	var horaini_tarde    = <? echo '"' . $at53_horaini_tarde . '"'; ?>;
	var horafim_tarde    = <? echo '"' . $at53_horafim_tarde . '"'; ?>;

	var diaini_dia       = parseInt(document.form1.at40_diaini_dia.value);
	var diaini_mes       = parseInt(document.form1.at40_diaini_mes.value);
	var diaini_ano       = parseInt(document.form1.at40_diaini_ano.value);

	var hora_int         = parseInt(hora,10);
	var vet_min          = hora.split(":");
    var hora_min         = "";
	var testa_hora       = "";
	var resto            = 0; 
	var hora_final       = 0;
	var diafim_dia       = diaini_dia;
	var qtd_dias         = parseInt(previsao/hora_trabalhadas);

	if(isNaN(previsao)) {
		alert("Digite a previsão. Podendo ser em horas ou dias!");
		return;
	}

// Armazena os minutos
	if(vet_min.length > 1) {
		hora_min += parseInt(vet_min[1], 10);
	}

	if(hora_min.length == 1||hora_min.length == 0) {
		hora_min = "00";
	}

	if(tipo_previsao == "h") {
		hora_int += previsao;

// Tarde
		if(hora > horafim_manha) {
			if(hora_int > parseInt(horafim_tarde, 10)) {
				hora_final = parseInt(horafim_tarde, 10);
				testa_hora = hora_final + ":" + hora_min;
				if(testa_hora > horafim_tarde) {
					hora_final = parseInt(horafim_tarde, 10);
					hora_min   = "00";
				}

				diafim_dia++;

				diaini_dia = retorna_dia(diaini_mes,diaini_ano);

				resto      = diafim_dia - diaini_dia;

				if(resto > 0) {
					diafim_dia = diaini_dia;
			
					if(resto >= 1) {
						diaini_mes++;
			
						diafim_dia = resto;
					}
			
					if(diaini_mes == 12) {
						diaini_ano++;
					}
				}
			} 
			else {
				hora_final = hora_int;
			}
		}
		else {
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Manha
			if(hora_int > parseInt(horafim_manha, 10)) {
				resto      = hora_int -parseInt(horafim_manha, 10);
				hora_final = parseInt(horaini_tarde, 10) + resto;

				if(hora_final > parseInt(horafim_tarde, 10)) {
					resto      = hora_final - parseInt(horafim_tarde, 10);
					hora_final = parseInt(horaini_manha, 10) + resto;

					if(hora_final == parseInt(horafim_manha, 10)) {
						testa_hora = hora_final + ":" + hora_min;
						if(testa_hora > horafim_manha) {
							hora_final = parseInt(horaini_tarde, 10);
							hora_min   = "00";
						}
					}

					if(hora_final == parseInt(horafim_tarde, 10)) {
						testa_hora = hora_final + ":" + hora_min;
						if(testa_hora > horafim_tarde) {
							hora_final = parseInt(horafim_tarde, 10);
							hora_min   = "00";
						}
					}
					else if(hora_final > parseInt(horafim_tarde, 10)) {
							 diafim_dia += qtd_dias - 2;

							 if(qtd_dias > 1) {
								 hora_final = parseInt(horafim_tarde, 10);
							 }
							 else {
								 hora_final -= parseInt(horafim_tarde, 10);
								 hora_final += parseInt(horaini_manha, 10);
							 }
					}

					diafim_dia++;

					diaini_dia = retorna_dia(diaini_mes,diaini_ano);

					resto      = diafim_dia - diaini_dia;

					if(resto > 0) {
						diafim_dia = diaini_dia;
				
						if(resto >= 1) {
							diaini_mes++;
				
							diafim_dia = resto;
						}
			
						if(diaini_mes == 12) {
							diaini_ano++;
						}
					}
				}
				else {
					if(hora_final == parseInt(horafim_tarde, 10)) {
						testa_hora = hora_final + ":" + hora_min;
						if(testa_hora > horafim_tarde) {
							hora_final = parseInt(horafim_tarde, 10);
							hora_min   = "00";
						}
					}
				}
			}
			else {
				hora_final = hora_int;
			} 
		}

		document.form1.at40_diafim_dia.value = diafim_dia;
		document.form1.at40_diafim_mes.value = diaini_mes;
		document.form1.at40_diafim_ano.value = diaini_ano;
		document.form1.at40_diafim.value = diafim_dia+'/'+diaini_mes+'/'+diaini_ano;

		if(hora_final == parseInt(horafim_tarde, 10)) {
			testa_hora = hora_final + ":" + hora_min;
			if(testa_hora > horafim_tarde) {
				hora_final = parseInt(horafim_tarde, 10);
				hora_min   = "00";
			}
		}

		document.form1.at40_horafim.value = hora_final + ":" + hora_min;
    }
	
	if(tipo_previsao == "d") {
		diafim_dia += previsao;

		diaini_dia  = retorna_dia(diaini_mes,diaini_ano);

		resto       = diafim_dia - diaini_dia;

		if(resto > 0) {
			diafim_dia = diaini_dia;
			
			if(resto > 1) {
				diaini_mes++;
				
//				diafim_dia = parseInt(resto/2);
				diafim_dia = resto;
			}
			
			if(diaini_mes == 12) {
				diaini_ano++;
			}
		}

		document.form1.at40_diafim_dia.value = diafim_dia;
		document.form1.at40_diafim_mes.value = diaini_mes;
		document.form1.at40_diafim_ano.value = diaini_ano;
		document.form1.at40_diafim.value = diafim_dia+'/'+diaini_mes+'/'+diaini_ano;

		hora_final = hora_int;

// Tarde
		if(hora_int > parseInt(horafim_manha, 10)) {
			hora_final = parseInt(horafim_tarde, 10); 
		}
// Manha
		else {
			testa_hora = hora_final + ":" + hora_min;
			if(testa_hora > horafim_manha) {
				hora_final = parseInt(horafim_manha, 10);
				hora_min   = "00";
			}
			else {
			    hora_final = parseInt(horafim_manha, 10);
			} 
		}

		testa_hora = hora_final + ":" + hora_min;
		if(testa_hora > horafim_tarde) {
			hora_final = parseInt(horafim_tarde, 10); 
			hora_min   = "00";
		}

		document.form1.at40_horafim.value = hora_final + ":" + hora_min;
	} 
}
function retorna_dia(diaini_mes,diaini_ano) {
	switch (diaini_mes) {
		case  1 : diaini_dia = 31;
				  break;
		case  2 : if((diaini_ano%4)==0) {
					  diaini_dia = 29; 	
				  }	
				  else {
					  diaini_dia = 28;
				  }
				  break;
		case  3 : diaini_dia = 31;
				  break;
		case  4 : diaini_dia = 30;
				  break;
		case  5 : diaini_dia = 31;
				  break;
		case  6 : diaini_dia = 30;
				  break;
		case  7 : diaini_dia = 31;
				  break;
		case  8 : diaini_dia = 31;
				  break;
		case  9 : diaini_dia = 31;
				  break;
		case 10 : diaini_dia = 31;
				  break;
		case 11 : diaini_dia = 30;
				  break;
		case 12 : diaini_dia = 31;
				  break;
	}

	return(diaini_dia);	
}
function js_verifica(){
alert("aki");
	
	document.form1.submit();
	
}
</script>