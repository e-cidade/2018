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
include("dbforms/db_classesgenericas.php");
require_once("classes/db_tarefaparam_classe.php");
include("classes/db_db_syscadproced_classe.php");

include("classes/db_db_projetosativcli_classe.php");

//include("classes/db_db_usuarios_classe.php");
$cltarefa->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("at41_proced");
$clrotulo->label("at47_situacao");
$clrotulo->label("at49_modulo");
$clrotulo->label("codmod");
$clrotulo->label("nome_modulo");
$clrotulo->label("at05_seq");
$clrotulo->label("at54_sequencial");

$cltarefasolic            = new cl_tarefasolic;
$cl_tarefacadmotivo       = new cl_tarefacadmotivo;
$cldb_proced              = new cl_db_proced;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$cltarefacadsituacao      = new cl_tarefacadsituacao;
$cldb_modulos             = new cl_db_modulos;
$cldb_usuarios            = new cl_db_usuarios;
$cltarefaparam 			  = new cl_tarefaparam;
$cl_db_syscadproced       = new cl_db_syscadproced;
$cldb_projetosativcli= new cl_db_projetosativcli;

if(@$abrefunc=='f'){
  if (@$aut != 1){
       $aut = 0;
  }

  if (@$canc != 1) {
       $canc = 0;
  }
}

$result = $cltarefaparam->sql_record($cltarefaparam->sql_query(null,"*",null,null));
if($cltarefaparam->numrows > 0) {
	db_fieldsmemory($result,0);
}

$where = "";

if($db_opcao==1||$db_opcao==11) {
	if(isset($at05_seq)&&@$at05_seq!="") {
		$where = "at05_seq = $at05_seq";
	}
}
else {
	$where = "at44_tarefa = " . @$at40_sequencial;
}


      if($db_opcao==1||$db_opcao==11){
 	   $db_action="ate1_tarefa004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="ate1_tarefa005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="ate1_tarefa006.php";
      }  
      
      
?>
<form name="form1" method="post" action="<?=$db_action?>">

<?

    $sql = $cltarefasolic->sql_query_file($db_opcao,$where);
	$sql = "select at20_usuario, at10_nome, max(tipo) as tipo from ($sql) as x group by at20_usuario, at10_nome";
	
	$result = $cltarefasolic->sql_record($sql);

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
<center>
<table border="0">
<!--
  <tr>
  	<td><input type="button" value="Atualizar Tarefa Agenda" onClick="js_atualizar();"></td>
  </tr>
-->  
  <tr>
    <td nowrap title="<?=@$Tat40_sequencial?>">
       <?=@$Lat40_sequencial?>
    </td>
    <td> 
<?
if($db_opcao==3||$db_opcao==1||$db_opcao==11||$db_opcao==33) { 
  if (@$aut != 1){
       $aut = 0;
  }

  if (@$canc != 1){
	     $canc = 0;
  }
}
if(isset($aut)&&$aut==0) {
	$canc = 0;
}
if (@$trocamodulo!='t'){
$trocamodulo= "f";
}

//echo "<input type='hidden' name='tipotar' value='@$tipotar'>";

db_input('aut',1,"",false,'hidden',3,"");
db_input('canc',1,"",false,'hidden',3,"");
db_input('tipo',1,"",false,'hidden',3,"");
db_input('prorrogar',1,"",false,'hidden',3,"");
db_input("trocamodulo",10,"",false,"hidden",3);
db_input('at40_sequencial',10,$Iat40_sequencial,true,'text',3,"");
db_input('at05_seq',10,@$Iat05_seq,true,'hidden',3,"");
db_input('at40_autorizada',1,"",true,'hidden',3,"");
db_input('at40_ativo',1,"",true,'hidden',3,"");
db_input('at40_tipo',1,"",true,'hidden',3,"");

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
//db_selectrecord('at40_responsavel',($cldb_usuarios->sql_record($cldb_usuarios->sql_query(($db_opcao==2?null:@$at40_responsavel),"id_usuario,nome","id_usuario",null))),true,$db_opcao,"");
db_selectrecord('at40_responsavel',($cldb_usuarios->sql_record($cldb_usuarios->sql_query(null,"id_usuario,nome","nome", "usuarioativo = '1' and usuext = 0"))),true,$db_opcao,"","","","0-Nenhum");
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
  				
  				//echo "codmod = $codmod...modulo49 = ".@$at49_modulo." -- modulo22 = ".@$at22_modulo ;
				$sqlmod = "select codmod,nomemod from db_sysmodulo where ativo = 't' order by nomemod";
				//echo "<br>$sqlmod <br>";
  				//$sqlmod = "select id_item, nome_modulo from db_modulos order by nome_modulo";
		        $result_modulo = pg_exec($sqlmod);
		        if ((@$at49_modulo!="")&&(@$codmod=="")){
		        	$codmod = $at49_modulo;
		        }
		        db_selectrecord('codmod',$result_modulo,true,$db_opcao,"","","","0-Nenhum","js_verifica();");
		        ?>
   </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat41_proced?>"><b>Tipo de atend.:</b></td>
    <td> 
<?
if (isset($at41_proced) and $at41_proced == 0) {
	unset($at41_proced);
}
db_selectrecord('at41_proced',($cldb_proced->sql_record($cldb_proced->sql_query(($db_opcao==2?null:@$at41_proced),"at30_codigo,at30_descr","at30_codigo",null))),true,$db_opcao,"","","","0-Nenhum");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat41_proced?>"><b>Procedimento:</b></td>
    <td> 
<?

if (@$codmod != 0) {
	//echo"tem modulo <br>";
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
		select codproced,descrproced 
		from db_syscadproced 
		inner join db_sysmodulo on db_sysmodulo.codmod=db_syscadproced.codmod 
		where db_sysmodulo.codmod=$codmod
		order by descrproced";
	//$result_syscadproced = $cl_db_syscadproced->sql_record($cl_db_syscadproced -> sql_query ( null,"codproced,descrproced || ' - ' || nome_modulo","descrproced","codmod=$codmod"));
	$result_syscadproced = $cl_db_syscadproced->sql_record($sqlprocedmod);
	
	
} else {
	//echo "não tem modulo";
		$sqlproced="
		select codproced,descrproced
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
    <td nowrap title="<?=@$Tat47_situacao?>"><b>Situação:</b></td>
    <td> 
<?
// comentei porque karina vai alterar essa parte.
//($cltarefacadsituacao->sql_record($cltarefacadsituacao->sql_query(($db_opcao==2?null:@$at47_situacao),"*","at46_codigo","at46_codigo <= 3")))
$usu = db_getsession("DB_id_usuario");

if($db_opcao == 2){
//if (isset($aut)&& $aut==1){
	//echo "autorizada";
/*
	$sqlsutusu ="select distinct * from (			
			select at46_codigo,
				   at46_descr 
			from tarefacadsituacaousu 
			inner join tarefacadsituacao on at17_tarefacadsituacao = at46_codigo 
			where at17_usuario = $usu
			union all
			select at46_codigo,
				   at46_descr
			from tarefacadsituacao 
			where at46_codigo = 2 ) as x
			order by at46_codigo ";

*/
	$sqlsutusu = "
			select distinct * from (
				select at46_codigo,
					   at46_descr 
			  	from tarefacadsituacaousu 
			    inner join tarefacadsituacao on at17_tarefacadsituacao = at46_codigo 
			  	where at17_usuario = $usu
			union all
				select at46_codigo,
				   	   at46_descr 
			    from tarefacadsituacao 
			    where at46_codigo = 2 
			union all
			    select at46_codigo,
					   at46_descr 
			    from tarefasituacao
			  	inner join tarefacadsituacao on at47_situacao = at46_codigo 
			    where at47_tarefa = @$at40_sequencial
			union all 
				select at46_codigo, 
					   at46_descr 
				from tarefacadsituacao 
				inner join tarefalog on at43_tarefa = @$at40_sequencial
				inner join tarefalogsituacao on at48_tarefalog = at43_sequencial
				and at48_situacao = at46_codigo			
			) as x
			order by at46_codigo
          ";

	
}else{
	//echo "não autorizada";
	$sqlsutusu = "select * from tarefacadsituacao where at46_codigo = 2 ";

}

$resultsutusu = pg_query($sqlsutusu);
//die($sqlsutusu);
db_selectrecord('at47_situacao',$resultsutusu,true,$db_opcao,"");
//db_selectrecord('at47_situacao',($cltarefacadsituacao->sql_record($cltarefacadsituacao->sql_query(($db_opcao==2?null:@$at47_situacao),"*","at46_codigo","at46_codigo = 2"))),true,$db_opcao,"");

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
db_textarea('at40_descr',5,50,$Iat40_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>

<?
// coloquei o db_opcao =2 para alterar a data e hora etc...
if (isset ($chavepesquisa) and ($at40_autorizada == 'f') or ($db_opcao==1||$db_opcao==11) or (@$prorrogar == true) or ($db_opcao==2)) {

	?>
  
  <tr>
    <td nowrap title="<?=@$Tat40_diaini?>">
       <?=@$Lat40_diaini?>
    </td>
    <td> 
<?
if($db_opcao==1||$db_opcao==11) {
	
	if(!isset($at40_diaini_dia)&&@$at40_diaini_dia=="") { 
		$at40_diaini_dia = date("d", db_getsession("DB_datausu"));
		$at40_diaini_mes = date("m", db_getsession("DB_datausu"));
		$at40_diaini_ano = date("Y", db_getsession("DB_datausu"));
	}
}

//db_inputdata('at40_diaini',@$at40_diaini_dia,@$at40_diaini_mes,@$at40_diaini_ano,true,'text',$db_opcao,"OnChange=''")
db_inputdata('at40_diaini',@$at40_diaini_dia,@$at40_diaini_mes,@$at40_diaini_ano,true,'text',$db_opcao,"onchange='js_verifica_data(document.form1.at40_diaini_ano.value+\"-\"+document.form1.at40_diafim_dia.value);'");

echo "&nbsp;&nbsp;&nbsp;&nbsp;".@$Lat40_diafim;

if(isset($at40_diafim_dia)&&$at40_diafim_dia!="") {
	if($db_opcao==1||$db_opcao==11) {
		$data            = retorna_data($at40_diafim_ano,$at40_diafim_mes,$at40_diafim_dia,"inc");
		$vet_data        = explode("-",$data);
		$at40_diafim_ano = db_formatar($vet_data[0],'s','0',2,'e',0); 
		$at40_diafim_mes = db_formatar($vet_data[1],'s','0',2,'e',0);
		$at40_diafim_dia = db_formatar($vet_data[2],'s','0',2,'e',0);
	}
	if($db_opcao==2||$db_opcao==22) {
		$data_fim        = $at40_diafim_ano."-".$at40_diafim_mes."-".$at40_diafim_dia;
		$data            = retorna_data($at40_diafim_ano,$at40_diafim_mes,$at40_diafim_dia,"inc");
		if($data != $data_fim) {
			$vet_data        = explode("-",$data);
			$at40_diafim_ano = db_formatar($vet_data[0],'s','0',2,'e',0); 
			$at40_diafim_mes = db_formatar($vet_data[1],'s','0',2,'e',0);
			$at40_diafim_dia = db_formatar($vet_data[2],'s','0',2,'e',0);
		}
	}
}

//db_inputdata('at40_diafim',@$at40_diafim_dia,@$at40_diafim_mes,@$at40_diafim_ano,true,'text',$db_opcao,"")
db_inputdata('at40_diafim',@$at40_diafim_dia,@$at40_diafim_mes,@$at40_diafim_ano,true,'text',$db_opcao,"onchange='js_verifica_data(document.form1.at40_diaini_ano.value+\"-\"+
                                                                                                                                   document.form1.at40_diaini_mes.value+\"-\"+
                                                                                                                                document.form1.at40_diafim_dia.value);'");

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
  <input title=" Calcular previsão " value="Calc. previsão" type="button" name="btat40_previsao" onclick="js_calcula_prev();">
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
//  $matriz = array("h"=>"horas");             
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
	if(!isset($at40_horainidia)&&@$at40_horainidia=="") {
		$hora = substr(db_hora(),0,2);
		$min  = retorna_minutos(substr(db_hora(),3,2)); 
		
		$at40_horainidia = $hora.":".$min;
	}
}

db_input('at40_horainidia',5,"",true,'text',$db_opcao,"onchange='js_verifica_hora(this.value,this.name)';");
//db_input('at40_horainidia',5,$Iat40_horainidia,true,'text',$db_opcao,"")
echo "&nbsp;&nbsp;&nbsp;&nbsp;".@$Lat40_horafim;
db_input('at40_horafim',5,"",true,'text',$db_opcao,"onchange='js_verifica_hora(this.value,this.name)';");
//db_input('at40_horafim',5,$Iat40_horafim,true,'text',$db_opcao,"")
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
             "3"=>"Alta"
	   );             
if($db_opcao==1||$db_opcao==11) {
	if(!isset($at40_prioridade)&&@$at40_prioridade=="") {
		$at40_prioridade = 2;
	}
}
  db_select("at40_prioridade", $x,true,$db_opcao); 
?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
<?
	if(@$aut==0) {
?>    

<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<?
	}
	
    if((@$aut==1)&&(@$tipotar!='T')) {
    	if($canc==0) {
?>
<input name="autorizar" type="submit" id="db_opcao" value="Autorizar" <?=($db_botao==false?"disabled":"")?> >
<?
		}
?>
<input name="cancelar"  type="submit" id="db_opcao" value="Cancelar autorização" <?=($db_botao==false?"disabled":"")?> >
<?
	}
		
?>    
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<?
	if($db_opcao==1||$db_opcao==11||$db_opcao==2||$db_opcao==22) {
?>
<input name="agendar"   type="button" id="agendar"   value="Agenda"    onclick="js_agenda();" >
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
function retorna_minutos($min) {
 	if($min > 0 && $min < 30) {
 		return("00");
 	}
 	else if($min > 30 && $min < 60){
 		return("30");
 	}
}
?>
  </center>
</form>
<script>
function js_verifica_data(data1,data2){
	var flag = true;
	if(data1.length > 0 && data2.length > 0){
		if(js_diferenca_datas(data1,data2,3)==true) {
			flag=false;
		}
	}
	
	if(flag==false) {
		alert("Data inicial maior que a final");
	}
	
	return(flag);
}
function js_agenda(){
  var data_ini = document.form1.at40_diaini_ano.value + "-" + 
                 document.form1.at40_diaini_mes.value + "-" + 
                 document.form1.at40_diaini_dia.value;
  js_OpenJanelaIframe('top.corpo.iframe_tarefa','db_iframe_agenda','func_agendamentotarefas.php?at40_responsavel='+document.form1.at40_responsavel.value+'&data_ini='+data_ini,'Agenda de Tarefas',true,'0');
}
function js_incluirtarefa(dia,mes,ano,hora){
  db_iframe_agenda.hide();
  document.form1.at40_diaini_dia.value = dia;
  document.form1.at40_diaini_mes.value = mes;
  document.form1.at40_diaini_ano.value = ano;
  document.form1.at40_horainidia.value = hora;
}
<?
$action = "";
if($db_opcao==1||$db_opcao==11) {
	$action = "ate1_tarefa005.php";
} 
else {
	if($db_opcao==2||$db_opcao==22) {
		$action = basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
	}
}
if(strlen($action) > 0) {
?>
function js_mostratarefas(chave,erro){
  db_iframe_agenda.hide();
<?
	echo "location.href = '".$action."?chavepesquisa='+chave;\n}\n";
}
?>
<!--
function js_atualizar() {
  js_OpenJanelaIframe('top.corpo.iframe_tarefa','db_iframe_agenda','func_atualizar_tarefa_agenda.php','Atualizando Agenda de Tarefas',true,'0');
}
-->
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
    eval("document.form1."+campo+".focus();");
    eval("document.form1."+campo+".value='"+hora+":"+minu+"';");
  }
}
function js_postegar() {
  js_OpenJanelaIframe('top.corpo.iframe_tarefa','db_iframe_tarefaenvol','func_tarefahorario.php?at40_sequencial='+document.form1.at40_sequencial.value+'&pesquisa_chave='+document.form1.at40_responsavel.value+'&funcao_js=parent.js_mostratarefahorario','Pesquisa',false,'0');
}
function js_mostratarefahorario(chave1,chave2, erro){
  if(erro == false) { 	
  	  document.form1.at40_sequencial.value  = chave1; 
  	  document.form1.at40_responsavel.value = chave2;
  	  js_retorna(chave1);
  }
}
function js_retorna(chave) {
<?
	echo "location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave\n";
?>
}
function js_pesquisa(){
<?
	if(isset($aut)&&$aut==1) {
		$texto = "Autorizar tarefas";
	}
	else {
		$texto = "Pesquisa";
	}
	if (@$trocamodulo != 't'){
	    echo "js_OpenJanelaIframe('top.corpo.iframe_tarefa','db_iframe_tarefa','func_tarefa.php?aut=".(isset($aut)&&$aut==1?"1":($aut=='t'?"t":"0"))."&canc=".(isset($canc) and $canc == 1?"1":"0")."&prorrogar=".(isset($prorrogar) and $prorrogar == 1?"1":"0")."&funcao_js=parent.js_preenchepesquisa|at40_sequencial','".$texto."',true,'0');";
    }
?>
}
function js_preenchepesquisa(chave){
  db_iframe_tarefa.hide();

  <?
  if($db_opcao!=1){
  
  	$executar = " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&abrefunc=f'";
  	if(isset($erro_horario) and $erro_horario != "") {
  		$executar .= "+'&erro_horario=".$erro_horario;
   	} else {
          $executar .= "+'";
	}
	$executar .= "&aut=".(isset($aut) and $aut == 1?"1":($aut=='t'?"t":"0"))."&canc=".(isset($canc) and $canc == 1?"1":"0")."&prorrogar=".(isset($prorrogar) and $prorrogar == 1?"1":"0");
    echo $executar."';\n";
  }
  ?>
}
function js_pesquisaatend_item(){
  js_OpenJanelaIframe('top.corpo.iframe_tarefa','db_iframe_atenditem','func_tarefa_atenditem.php?funcao_js=parent.js_mostraatenditem|at05_seq','Pesquisa',true,'0');
}
function js_pesquisatarefa(){
  js_OpenJanelaIframe('top.corpo.iframe_tarefa','db_iframe_tarefa2','func_tarefa.php?funcao_js=parent.js_mostratarefa|at40_sequencial','Pesquisa',true,'0');
}

function js_mostraatenditem(chave){
  db_iframe_atenditem.hide();
  document.form1.at05_seq.value = chave;
  document.form1.submit();	
}
function js_mostratarefa(chave){
  db_iframe_tarefa2.hide();
  document.form1.at40_sequencial.value = chave;
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

	var diaini_dia       = document.form1.at40_diaini_dia.value;
	var diaini_mes       = document.form1.at40_diaini_mes.value;
	var diaini_ano       = document.form1.at40_diaini_ano.value;

	var hora_int         = parseInt(hora,10);
	var vet_min          = hora.split(":");
    var hora_min         = "";
	var testa_hora       = "";
	var resto            = 0; 
	var hora_final       = 0;
	var diafim_dia       = parseInt(diaini_dia);
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
   
    if(diafim_dia.toString().length < 2){
     diafim_dia = '0'+diafim_dia;
    }
    
    if(diaini_mes.toString().length < 2){
     diaini_mes = '0'+diaini_mes;
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

    if(diafim_dia.toString().length < 2){
     diafim_dia = '0'+diafim_dia;
    }
    
    if(diaini_mes.toString().length < 2){
     diaini_mes = '0'+diaini_mes;
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
		
		<?
			if($db_opcao==1||$db_opcao==11) {
		?>
		document.form1.submit();
		<?
			}
		?>
	} 
}
function retorna_dia(diaini_mes,diaini_ano) {
	switch (parseInt(diaini_mes)) {
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
	document.form1.trocamodulo.value='t';
	document.form1.submit();
	
}
</script>
<?

//die("xx");
?>