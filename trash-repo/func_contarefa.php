<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("classes/db_db_usuarios_classe.php");
include("classes/db_tarefa_classe.php");
include("classes/db_tarefa_lanc_classe.php");
include("classes/db_tarefa_lancprorrog_classe.php");
include("classes/db_tarefalog_classe.php");
include("classes/db_tarefalido_classe.php");
include("classes/db_tarefalidolog_classe.php");
include("classes/db_clientes_classe.php");
include("classes/db_db_proced_classe.php");
include("classes/db_db_sysmodulo_classe.php");
include("classes/db_db_syscadproced_classe.php");
include("classes/db_atendcadarea_classe.php");
include("classes/db_db_procedcadgrupos_classe.php");
include("classes/db_tarefacadmotivo_classe.php");
include("classes/db_tarefacadsituacao_classe.php");
include("classes/db_db_versaotarefa_classe.php");
include("classes/db_db_depart_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clrotulo      		= new rotulocampo;

$cltarefa             = new cl_tarefa;
$cltarefa_lanc        = new cl_tarefa_lanc;
$cltarefa_lancprorrog = new cl_tarefa_lancprorrog;
$cltarefalog          = new cl_tarefalog;
$cltarefalido         = new cl_tarefalido;
$cltarefalidolog      = new cl_tarefalidolog;
$cldb_usuarios        = new cl_db_usuarios;
$clclientes           = new cl_clientes;
$cldb_proced          = new cl_db_proced;
$cldb_sysmodulo       = new cl_db_sysmodulo;
$cldb_syscadproced    = new cl_db_syscadproced;
$cl_atendcadarea      = new cl_atendcadarea;
$cldb_procedcadgrupos = new cl_db_procedcadgrupos;
$cltarefacadmotivo    = new cl_tarefacadmotivo;
$cltarefacadsituacao  = new cl_tarefacadsituacao;
$cldb_versaotarefa    = new cl_db_versaotarefa;
$cldb_depart          = new cl_db_depart;

$clrotulo->label('at40_sequencial');
$clrotulo->label('at40_diaini');
$clrotulo->label('at40_diafim');

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>

function js_enviar_agenda(){
  usuario = document.form1.at40_responsavel.value;
  nome    = document.form1.at40_responsaveldescr.options[document.form1.at40_responsaveldescr.selectedIndex].text;
  js_OpenJanelaIframe('top.corpo.iframe_tarefa','db_iframe_tarefa_agenda_geral','func_calendario_atendimento_consulta.php?tecnico_solicitado='+usuario+'&tecnico_solicitado_nome='+nome,'Pesquisa',true);
}

function js_abre_agendamento(tarefa){
  js_OpenJanelaIframe('top.corpo.iframe_tarefa','db_iframe_tarefa_agenda','func_calendario_atendimento.php?tarefa='+tarefa,'Pesquisa',true);
}

function js_enviar() {
  return true;
  //	document.form1.submit();
}
function js_enviar_submit() {
  document.form1.submit();
}
function js_enviarlido(tarefa, sequencial, tarefalog, lido) {
  document.form1.at40_tarefalido.value = sequencial;
  document.form1.at40_tarefalidologtarefa.value = tarefa;
  document.form1.at40_tarefalog.value = tarefalog;
  document.form1.at40_lido.value = lido;
  document.form1.submit();
}
function js_urgente(tarefa) {
  document.form1.at40_tarefaurgente.value = tarefa;
  document.form1.submit();
}
</script>   
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table border="0" width="100%" align="center" cellspacing="0" bgcolor="#CCCCCC">
<form name="form1" action="<?=$PHP_SELF?>" method="POST">	

<?

db_input("at40_lido",10,@$at40_lido,true,'hidden',2,"");
db_input("at40_tarefalido",10,@$at40_tarefalido,true,'hidden',2,"");
db_input("at40_tarefalog",10,@$at40_tarefalog,true,'hidden',2,"");
db_input("at40_tarefalidologtarefa",10,@$at40_tarefalidologtarefa,true,'hidden',2,"");
db_input("at40_tarefaurgente",10,@$at40_tarefaurgente,true,'hidden',2,"");
db_input("item_proc",10,@$item_proc,true,'hidden',2,"");

if (isset($at40_tarefaurgente) and $at40_tarefaurgente != 0) {
  $result_tarefa = $cltarefa->sql_record($cltarefa->sql_query($at40_tarefaurgente,"at40_sequencial as at40_sequencial_urg, at40_responsavel as at40_responsavel_urg, at40_descr as at40_descr_urg, at40_diaini as at40_diaini_urg, at40_diafim as at40_diafim_urg, at40_previsao as at40_previsao_urg, at40_tipoprevisao as at40_tipoprevisao_urg, at40_horainidia as at40_horainidia_urg, at40_horafim as at40_horafim_urg, at40_progresso as at40_progresso_urg, at40_prioridade as at40_prioridade_urg, at40_obs as at40_obs_urg, at40_autorizada as at40_autorizada_urg, at40_tipo as at40_tipo_urg, at40_ativo as at40_ativo_urg"));
  db_fieldsmemory($result_tarefa, 0);
  
  $cltarefa->at40_sequencial       = $at40_sequencial_urg;
  $cltarefa->at40_responsavel      = $at40_responsavel_urg;
  $cltarefa->at40_descr            = $at40_descr_urg;
  $cltarefa->at40_diaini           = $at40_diaini_urg;
  $cltarefa->at40_diafim           = $at40_diafim_urg;
  $cltarefa->at40_previsao         = $at40_previsao_urg;
  $cltarefa->at40_tipoprevisao     = $at40_tipoprevisao_urg;
  $cltarefa->at40_horainidia       = $at40_horainidia_urg;
  $cltarefa->at40_horafim          = $at40_horafim_urg;
  $cltarefa->at40_progresso        = $at40_progresso_urg;
  $cltarefa->at40_prioridade       = $at40_prioridade_urg;
  $cltarefa->at40_obs              = $at40_obs_urg;
  $cltarefa->at40_autorizada       = $at40_autorizada_urg;
  $cltarefa->at40_tipo             = $at40_tipo_urg;
  $cltarefa->at40_ativo            = $at40_ativo_urg;
  
  global $at40_urgente_urg;
  $varurgente = "urgente_" . $at40_tarefaurgente;
  if (isset($$varurgente)) {
    $at40_urgente_urg = "1";
  } else {
    $at40_urgente_urg = "0";
  }
  $cltarefa->at40_urgente          = $at40_urgente_urg;
  
  $cltarefa->alterar($at40_tarefaurgente);
  if ($cltarefa->erro_status == 0) {
    $sqlerro = true;
    $erro_msg = $cltarefa->erro_msg;
  }
  
}

if (isset($at40_tarefalidologtarefa) and $at40_tarefalidologtarefa != 0) {
  
  if ($at40_lido == "1") {
    
    if ($at40_tarefalog > 0) {
      $resultlidolog = $cltarefalidolog->sql_record($cltarefalidolog->sql_query(null,"at60_sequencial, at60_tarefalido","at60_sequencial desc","at60_tarefalog = $at40_tarefalog and at36_usuario = " . db_getsession("DB_id_usuario")));
      if ($cltarefalidolog->numrows > 0) {
        db_fieldsmemory($resultlidolog, 0);
        $cltarefalidolog->at60_sequencial = $at60_sequencial;
        $cltarefalidolog->excluir($at60_sequencial);
        if($cltarefalidolog->erro_status==0) {
          $sqlerro = true;
        }
        
        $cltarefalido->at59_sequencial = $at60_tarefalido;
        $cltarefalido->excluir($at60_tarefalido);
        if($cltarefalido->erro_status==0) {
          $sqlerro = true;
        }
        
      }
      
    } else {
      $resultloglido = $cltarefalido->sql_record($cltarefalido->sql_query(null,"at59_sequencial","at59_sequencial desc","at36_tarefa = $at40_tarefalidologtarefa and at36_usuario = " . db_getsession("DB_id_usuario")));
      if ($cltarefalido->numrows > 0) {
        db_fieldsmemory($resultloglido, 0);
        $cltarefalido->at59_sequencial = $at59_sequencial;
        $cltarefalido->excluir($at59_sequencial);
        if($cltarefalido->erro_status==0) {
          $sqlerro = true;
        }
        
      }
    }
    
  } else {
    
    $cltarefa_lanc->at36_data    = date("Y", db_getsession("DB_datausu"))."-".
    date("m", db_getsession("DB_datausu"))."-".
    date("d", db_getsession("DB_datausu"));
    $cltarefa_lanc->at36_hora    = db_hora();
    $cltarefa_lanc->at36_ip      = db_getsession("DB_ip");
    $cltarefa_lanc->at36_tarefa  = $at40_tarefalidologtarefa;
    $cltarefa_lanc->at36_usuario = db_getsession("DB_id_usuario");
    $cltarefa_lanc->at36_tipo    = "L";
    $cltarefa_lanc->incluir(null);
    if($cltarefa_lanc->erro_status==0) {
      $sqlerro = true;
    }
    
    $cltarefalido->at59_tarefalanc 	= $cltarefa_lanc->at36_sequencia;
    $cltarefalido->incluir(null);
    if($cltarefalido->erro_status==0) {
      $sqlerro = true;
    }
    
    if (isset($at40_tarefalog) and $at40_tarefalog > 0) {
      
      $cltarefalidolog->at60_tarefalido 	= $cltarefalido->at59_sequencial;
      $cltarefalidolog->at60_tarefalog	= $at40_tarefalog;
      $cltarefalidolog->incluir(null);
      if($cltarefalidolog->erro_status==0) {
        $sqlerro = true;
      }
      
    }
    
  }
  
}

?>

<tr>
<td><b>Usuário:</b>&nbsp;&nbsp;
<?

if (!isset($at40_responsavel)) {
  global $at40_responsavel;
  $at40_responsavel = db_getsession("DB_id_usuario");
  $primeiravez=true;
} else {
  $primeiravez=false;
}
db_selectrecord('at40_responsavel',($cldb_usuarios->sql_record($cldb_usuarios->sql_query(null,"id_usuario,nome","nome"," usuarioativo = '1'"))),true,1,"", "", "", "0-Todos", "js_enviar()");
?>
<b>Cliente:</b>&nbsp;&nbsp;
<?
db_selectrecord('at40_cliente',($clclientes->sql_record($clclientes->sql_query(null,"*","at01_nomecli"," at01_status is true"))),true,1,"", "", "", "Todos", "js_enviar()");

$resultautorizadas = $cldb_proced->sql_record($cldb_proced->sql_query_usu(null,"*",""," db_procedusu.at31_usuario = " . db_getsession("DB_id_usuario") . " and db_procedgrupos.at52_grupo <> 5"));
if ($cldb_proced->numrows > 0 and !isset($at40_autoriza)) {
	$at40_autoriza="T";
}

?>

<select name="at40_autoriza" onchange="js_enviar();">
<option value="T"<? if(isset($at40_autoriza)&&$at40_autoriza=="T") { echo " SELECTED"; } ?>>Todas</option>
<option value="S"<? if(isset($at40_autoriza)&&$at40_autoriza=="S") { echo " SELECTED"; } else { if(!isset($at40_autoriza)) { echo " SELECTED"; } } ?>>Autorizadas</option>
<option value="N"<? if(isset($at40_autoriza)&&$at40_autoriza=="N") { echo " SELECTED"; } ?>>Não autorizadas</option>
</select>


<b><br>Envolvimento:</b>&nbsp;&nbsp;
<?
if (!isset($at40_progressoini)) {
  $at40_progressoini = 100;
}
if (!isset($at40_progressofim)) {
  $at40_progressofim = 100;
}
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
db_select("at40_progressoini", $matriz,true,1,"onchange='js_enviar()'"); 
?>

<b>a</b>&nbsp;&nbsp;
<?
db_select("at40_progressofim", $matriz,true,1,"onchange='js_enviar()'");

$primeira=@$ordem;

if (!isset($ordem)) {
  $ordem = 1;
}

if (!isset($leitura)) {
  $leitura = "T";
}
if (!isset($tipodatafinal)) {
  $tipodatafinal = "P";
}
if (1 == 2) {
  ?>
  <b>Tarefas:</b>&nbsp;&nbsp;
  <select name="at40_progresso" onchange="js_enviar();">
<option value="T"<? if(isset($at40_progresso)&&$at40_progresso=="T") { echo " SELECTED"; } ?>>Todas</option>
<option value="A"<? if(isset($at40_progresso)&&$at40_progresso=="A") { echo " SELECTED"; } else { if(!isset($at40_progresso)) { echo " SELECTED"; } } ?>>Ativas</option>
<option value="F"<? if(isset($at40_progresso)&&$at40_progresso=="F") { echo " SELECTED"; } ?>>Finalizadas</option>
  </select>
  <?
}
$at40_progresso = "T";
?>
<b>Ordem:</b>&nbsp;&nbsp;
<select name="ordem" onchange="js_enviar();">

<option value="1"<? if($ordem=="1") { echo " SELECTED"; } ?>>Data, prioridade e dias de pendencia</option>
<option value="2"<? if($ordem=="2") { echo " SELECTED"; } ?>>Prioridade e dias de pendencia</option>
<option value="3"<? if($ordem=="3") { echo " SELECTED"; } ?>>Ordem definida pelo moderador</option>
</select>

<select name="leitura" onchange="js_enviar();">
<option value="T"<? if(isset($leitura)&&$leitura=="T") { echo " SELECTED"; } else { if(!isset($leitura)) { echo " SELECTED"; } } ?>>Todas</option>
<option value="L"<? if(isset($leitura)&&$leitura=="L") { echo " SELECTED"; } ?>>Lidas</option>
<option value="N"<? if(isset($leitura)&&$leitura=="N") { echo " SELECTED"; } ?>>Nao lidas</option>
<option value="N0"<? if(isset($leitura)&&$leitura=="N0") { echo " SELECTED"; } ?>>Nao lidas hj</option>
<option value="N1"<? if(isset($leitura)&&$leitura=="N1") { echo " SELECTED"; } ?>>Nao lidas a + de 1d</option>
<option value="N2"<? if(isset($leitura)&&$leitura=="N2") { echo " SELECTED"; } ?>>Nao lidas a + de 2d</option>
<option value="N3"<? if(isset($leitura)&&$leitura=="N3") { echo " SELECTED"; } ?>>Nao lidas a + de 3d</option>
<option value="N4"<? if(isset($leitura)&&$leitura=="N4") { echo " SELECTED"; } ?>>Nao lidas a + de 4d</option>
<option value="N5"<? if(isset($leitura)&&$leitura=="N5") { echo " SELECTED"; } ?>>Nao lidas a + de 5d</option>
</select>
<?
//if (isset($item_proc) && trim($item_proc) != ""){
  if (!isset($andamento)){
    $andamento = "T";
  }
  //if($andamento=="F"){
  //  echo "fechado.......... <br>";
  //}
  //if($andamento=="A"){
  //  echo "aberto.......... <br>";
  //}
  db_input("funcao_js",50,@$funcao_js,true,'hidden',2,"");
  ?>
  <select name="andamento" OnChange="js_enviar()">
<option value="T"<? if (isset($andamento)&&$andamento=="T"){ echo " SELECTED"; }?>>Todos</option>
<option value="A"<? if (isset($andamento)&&$andamento=="A"){ echo " SELECTED"; }?>>Andamentos abertos</option>
<option value="F"<? if (isset($andamento)&&$andamento=="F"){ echo " SELECTED"; }?>>Andamentos fechados</option>
  </select>
  <?
//}
?>
</td>
</tr>

<tr>
<td><b>Motivo:</b>&nbsp;&nbsp;
<?
global $at40_motivo;
db_selectrecord('at40_motivo',($cltarefacadmotivo->sql_record($cltarefacadmotivo->sql_query(null,"at54_sequencial, at54_descr","at54_descr"))),true,1,"", "", "", "0-Todos", "js_enviar()");
?>



<b>Grupo:</b>&nbsp;&nbsp;
<?
db_selectrecord('at40_grupoproced',($cldb_procedcadgrupos->sql_record($cldb_procedcadgrupos->sql_query(null,"*","at51_descr"))),true,1,"", "", "", "0-Todos", "js_enviar()");
?>

<b>Tarefas da Agenda:</b>
<select name="todasprocedencias" onchange="js_enviar();">
<option value="N"<? if(isset($todasprocedencias)&&$todasprocedencias=="N") { echo " SELECTED"; } else { if(!isset($todasprocedencias)) { echo " SELECTED"; } } ?>>Não</option>
<option value="S"<? if(isset($todasprocedencias)&&$todasprocedencias=="S") { echo " SELECTED"; } ?>>Sim</option>
</select>

<b>Área:</b>&nbsp;&nbsp;
<?
db_selectrecord('at40_area',($cl_atendcadarea->sql_record($cl_atendcadarea->sql_query(null,"*","at25_descr"))),true,1,"", "", "", "0-Todos", "js_enviar()");
?>

</td>

</tr>

<tr>
<td>
<?
db_ancora("$Lat40_sequencial"," js_pesquisa_usuario(true) ",2);
db_input("at40_sequencial",10,$Iat40_sequencial,true,'text',2," onchange='js_pesquisa_usuario(false)'");
?>

<b>Tipo:</b>&nbsp;&nbsp;
<?
db_selectrecord('at40_proced',($cldb_proced->sql_record($cldb_proced->sql_query(null,"*","at30_descr",""))),true,1,"", "", "", "0-Todos", "js_enviar()");

$resultsituacao = $cltarefacadsituacao->sql_record($cltarefacadsituacao->sql_query(null,"*","at46_codigo",""));

for ($situacao=0; $situacao < $cltarefacadsituacao->numrows; $situacao++) {
  db_fieldsmemory($resultsituacao, $situacao);
  
  $varnamesituacao = "check_" . strtolower(str_replace(" ","",$at46_descr));
  
  if (isset($at40_situacao)) {
    
    if (gettype(strpos($at40_situacao, $at46_codigo)) == "integer") {
      $$varnamesituacao = $at46_codigo;
    }
    
  }
  
  $array_disable = array(3,5,6);
  
  echo "
  <input type='checkbox' name='$varnamesituacao' id='$varnamesituacao' ".(!isset($primeira)?(!in_array($at46_codigo,$array_disable)?" checked ":""):(isset($$varnamesituacao)?"checked":"")).">
  $at46_descr";
  
}

?>


</td>


</tr>

<tr>
<td>
<b>Data final</b>
<?
db_inputdata('at40_diaini',@$at40_diaini_dia,@$at40_diaini_mes,@$at40_diaini_ano,true,'text','1',"");
?>
a
<?
//if (!isset($at40_diafim_dia)) {
  //  $at40_diafim_dia = date('d',db_getsession("DB_datausu"));
  //  $at40_diafim_mes = date('m',db_getsession("DB_datausu"));
  //  $at40_diafim_ano = date('Y',db_getsession("DB_datausu"));
//}
db_inputdata('at40_diafim',@$at40_diafim_dia,@$at40_diafim_mes,@$at40_diafim_ano,true,'text','1',"");
?>
<select name="tipodatafinal" onchange="js_enviar();">
<option value="P"<? if(isset($tipodatafinal)&&$tipodatafinal=="P") { echo " SELECTED"; } else { if(!isset($tipodatafinal)) { echo " SELECTED"; } } ?>>Previsao</option>
<option value="E"<? if(isset($tipodatafinal)&&$tipodatafinal=="E") { echo " SELECTED"; } ?>>Execução</option>
<option value="C"<? if(isset($tipodatafinal)&&$tipodatafinal=="C") { echo " SELECTED"; } ?>>Criação</option>
<option value="A"<? if(isset($tipodatafinal)&&$tipodatafinal=="A") { echo " SELECTED"; } ?>>Agenda</option>
<option value="CUSU"<? if(isset($tipodatafinal)&&$tipodatafinal=="CUSU") { echo " SELECTED"; } ?>>Criado pelo usuário</option>
<option value="CAREA"<? if(isset($tipodatafinal)&&$tipodatafinal=="CAREA") { echo " SELECTED"; } ?>>Criado pelo área</option>
</select>
<?
$arr_tipo=array("i"=>"Interno","c"=>"Cliente");
db_select("tipo_rel",$arr_tipo,true,"text");

$arr_opcao_rel=array("A"=>"Analítico","F"=>"Ficha","S"=>"Sintético","C"=>"Conferencia");
db_select("opcao_rel",$arr_opcao_rel,true,"text");
?>
<input name="consulta" type="button" value="Relatório" onClick="js_relatorio()">
<input name="atualizar" type="button" value="Atualizar" onClick="js_enviar_submit()">
</td>
</tr>

<tr>
<td>

<b>Módulo:</b>&nbsp;&nbsp;
<?
db_selectrecord('at40_modulo',($cldb_sysmodulo->sql_record($cldb_sysmodulo->sql_query(null,"*","nomemod"))),true,1,"", "", "", "0-Todos", "js_enviar()");
?>
<b>Procedimento:</b>&nbsp;&nbsp;
<?
db_selectrecord('at40_syscadproced',($cldb_syscadproced->sql_record($cldb_syscadproced->sql_query(null,"*","descrproced",(@$at40_modulo != ""?"db_syscadproced.codmod = $at40_modulo":"")))),true,1,"", "", "", "0-Todos", "js_enviar()");
?>

<input name="agendageral" type="button" value="Minha Agenda" onClick="js_enviar_agenda()">
<input name="semagenda" id="semagenda1" type="radio" value="1" title="Sem Agenda" <?=(isset($semagenda) && $semagenda=="1"?"checked":"")?> >S
<input name="semagenda" id="semagenda2" type="radio" value="2" title="Com Agenda" <?=(isset($semagenda) && $semagenda=="2"?"checked":"")?> >C
<input name="semagenda" id="semagenda3" type="radio" value="3" title="Todos"      <?=(isset($semagenda) && $semagenda=="3"?"checked":"")?> >T

<b>Depto.:</b>&nbsp;&nbsp;
<?
db_selectrecord('coddepto',($cldb_depart->sql_record($cldb_depart->sql_query(null,"*","descrdepto"))),true,1,"", "", "", "0-Todos");
?>

</td>
</tr>

<tr>
<td align="center" valign="top">
<?

$where = " 1=1 ";

if (!isset($at40_autoriza)) {
  $at40_autoriza = "S";
}

if (isset($at40_autoriza) and $at40_autoriza == "S") {
  $where .= " and at40_autorizada is true ";
} elseif (isset($at40_autoriza) and $at40_autoriza == "N") {
  $where .= " and at40_autorizada is false";
}

if (isset($at40_responsavel) and $at40_responsavel != "0" and ($tipodatafinal != "CUSU" and $tipodatafinal != "CAREA")) {
  $where .= " and at40_ativo is true and tarefaenvol.at45_usuario = $at40_responsavel ";
} elseif (isset($at40_responsavel) and $at40_responsavel == "0") {
  $where .= " ";
}

if (isset($at40_motivo) and $at40_motivo != "0") {
  $where .= " and at55_motivo = $at40_motivo";
} elseif (isset($at40_motivo) and $at40_motivo == "0") {
  $where .= " ";
}

if (isset($at40_grupoproced) and $at40_grupoproced != "0") {
  $where .= " and at52_grupo = $at40_grupoproced";
} elseif (isset($at40_grupoproced) and $at40_grupoproced == "0") {
  $where .= " ";
}

if (isset($at40_area) and $at40_area != "0") {
  $where .= " and db_syscadproced.codarea = $at40_area ";
} elseif (isset($at40_area) and $at40_area == "0") {
  $where .= " ";
}

if (isset($coddepto) and $coddepto != "0") {
  $where .= " and coddepto = $coddepto ";
} elseif (isset($at40_area) and $at40_area == "0") {
  $where .= " ";
}

if (isset($at40_modulo) and $at40_modulo != "0") {
  $where .= " and tarefamodulo.at49_modulo = $at40_modulo ";
} elseif (isset($at40_modulo) and $at40_modulo == "0") {
  $where .= " ";
}

if (isset($at40_syscadproced) and $at40_syscadproced != "0") {
  $where .= " and db_syscadproced.codproced = $at40_syscadproced";
} elseif (isset($at40_syscadproced) and $at40_syscadproced == "0") {
  $where .= " ";
}
$at40_situacao = "";

if (isset($check_execucao)) {
  $at40_situacao .= ($at40_situacao == ""?"":",") . "1";
}

if (isset($check_analise)) {
  $at40_situacao .= ($at40_situacao == ""?"":",") . "2";
}

if (isset($check_finalizada)) {
  $at40_situacao .= ($at40_situacao == ""?"":",") . "3";
}

if (isset($check_teste)) {
  $at40_situacao .= ($at40_situacao == ""?"":",") . "4";
}

if (isset($check_release)) {
  $at40_situacao .= ($at40_situacao == ""?"":",") . "5";
}

if (isset($check_off)) {
  $at40_situacao .= ($at40_situacao == ""?"":",") . "6";
}

if (isset($check_conflitotag)) {
  $at40_situacao .= ($at40_situacao == ""?"":",") . "7";
}

if ($at40_situacao == "") {
  $at40_situacao = "1,2,4";
}

if (isset($at40_situacao) and $at40_situacao != "") {
  $where .= " and tarefasituacao.at47_situacao in ($at40_situacao)";
}

if (isset($at40_cliente) and $at40_cliente != "Todos") {
  $where .= " and tarefaclientes.at70_cliente = $at40_cliente ";
}

if (isset($at40_proced) and $at40_proced != "0") {
  $where .= " and tarefaproced.at41_proced = $at40_proced ";
}

$where_tarefalog = "";
if (isset($andamento) && trim($andamento) != "" && $andamento != "T"){
  if ($andamento == "A"){
    $where_tarefalog  = " and (select count(*) "; 
    $where_tarefalog .= "        from tarefalog a "; 
    $where_tarefalog .= "       where a.at43_tarefa = tarefa.at40_sequencial ";
    $where_tarefalog .= "         and (a.at43_horafim is null or trim(a.at43_horafim) = '')) > 0  ";
  }
  
  if ($andamento == "F"){
    $where_tarefalog  = " and (select count(*) "; 
    $where_tarefalog .= "        from tarefalog a "; 
    $where_tarefalog .= "       where a.at43_tarefa = tarefa.at40_sequencial ";
    $where_tarefalog .= "         and (a.at43_horafim is null or trim(a.at43_horafim) = '')) = 0  ";
  }
}

$where .= $where_tarefalog;
$where_envol = " where 1=1 ";

if (isset($at40_progressoini) and (isset($at40_progressofim))) {
  $where_envol .= " and dl_Envolvimento between $at40_progressoini and $at40_progressofim";
} 

if (isset($at40_diaini_dia) and $at40_diaini_dia != "") {
  $at40_diaini = $at40_diaini_ano . "-" . $at40_diaini_mes . "-" . $at40_diaini_dia;
} else {
  $at40_diaini = "";
}

if (isset($at40_diafim_dia) and $at40_diafim_dia != "") {
  $at40_diafim = $at40_diafim_ano . "-" . $at40_diafim_mes . "-" . $at40_diafim_dia;
} else {
  $at40_diafim = "";
}

if ($tipodatafinal == "P") {
  if ($at40_diaini != "" and $at40_diafim != "") {
    $where_envol .= " and (at40_diafim between '$at40_diaini' and '$at40_diafim') or at40_diafim is null ";
  } elseif ($at40_diaini != "" and $at40_diafim == "") {
    $where_envol .= " and at40_diafim >= '$at40_diaini' or at40_diafim is null ";
  } elseif ($at40_diaini == "" and $at40_diafim != "") {
    $where_envol .= " and at40_diafim <= '$at40_diafim' or at40_diafim is null ";
  }
}else if ($tipodatafinal == "C" or $tipodatafinal == "CAREA") {
  if ($at40_diaini != "" and $at40_diafim != "") {
    $where_envol .= " and ( at40_sequencial = tarefa_lanc.at36_tarefa and tarefa_lanc.at36_tipo = 'I' and tarefa_lanc.at36_data between '$at40_diaini' and '$at40_diafim') ";
  } elseif ($at40_diaini != "" and $at40_diafim == "") {
    $where_envol .= " and ( at40_sequencial = tarefa_lanc.at36_tarefa and tarefa_lanc.at36_tipo = 'I' and  tarefa_lanc.at36_data >= '$at40_diaini' ) ";
  } elseif ($at40_diaini == "" and $at40_diafim != "") {
    $where_envol .= " and ( at40_sequencial = tarefa_lanc.at36_tarefa and tarefa_lanc.at36_tipo = 'I' and  tarefa_lanc.at36_data <= '$at40_diafim' ) ";
  }
}else if ($tipodatafinal == "CUSU") {

  if (isset($at40_responsavel) and $at40_responsavel != "0") {
    $where_envol .= " and ( tarefa_lanc.at36_usuario = $at40_responsavel ";
  }

  if ($at40_diaini != "" and $at40_diafim != "") {
    $where_envol .= " and at40_sequencial = tarefa_lanc.at36_tarefa and tarefa_lanc.at36_tipo = 'I' and tarefa_lanc.at36_data between '$at40_diaini' and '$at40_diafim') ";
  } elseif ($at40_diaini != "" and $at40_diafim == "") {
    $where_envol .= " and at40_sequencial = tarefa_lanc.at36_tarefa and tarefa_lanc.at36_tipo = 'I' and  tarefa_lanc.at36_data >= '$at40_diaini' ) ";
  } elseif ($at40_diaini == "" and $at40_diafim != "") {
    $where_envol .= " and at40_sequencial = tarefa_lanc.at36_tarefa and tarefa_lanc.at36_tipo = 'I' and  tarefa_lanc.at36_data <= '$at40_diafim' ) ";
  }
}else if ($tipodatafinal == "A") {
  if ($at40_diaini != "" and $at40_diafim != "") {
    $where_envol .= " and ((select min(at13_dia) from tarefa_agenda where at13_tarefa = at40_sequencial) between '$at40_diaini' and '$at40_diafim' or ";
    $where_envol .= "      (select max(at13_dia) from tarefa_agenda where at13_tarefa = at40_sequencial) between '$at40_diaini' and '$at40_diafim') ";
  } elseif ($at40_diaini != "" and $at40_diafim == "") {
    $where_envol .= " and ((select min(at13_dia) from tarefa_agenda where at13_tarefa = at40_sequencial) >= '$at40_diaini' or ";
    $where_envol .= "      (select max(at13_dia) from tarefa_agenda where at13_tarefa = at40_sequencial) >= '$at40_diaini') ";
  } elseif ($at40_diaini == "" and $at40_diafim != "") {
    $where_envol .= " and ((select min(at13_dia) from tarefa_agenda where at13_tarefa = at40_sequencial) <= '$at40_diafim' or ";
    $where_envol .= "      (select max(at13_dia) from tarefa_agenda where at13_tarefa = at40_sequencial) <= '$at40_diafim') ";
  }
  
} else {
  if ($at40_diaini != "" and $at40_diafim != "") {
    $where_envol .= " and (db_dia100 between '$at40_diaini' and '$at40_diafim') or db_dia100 is null ";
  } elseif ($at40_diaini != "" and $at40_diafim == "") {
    $where_envol .= " and db_dia100 >= '$at40_diaini' or db_dia100 is null ";
  } elseif ($at40_diaini != "" and $at40_diafim == "") {
    $where_envol .= " and db_dia100 <= '$at40_diafim' or db_dia100 is null ";
  }
}

if(!isset($pesquisa_chave)){
  
  if (isset($at40_responsavel) and $at40_responsavel != "0" or 1==1) {
    //$campos = "tarefa.at40_sequencial, tarefa.at40_urgente, (select max(at43_diaini) as at43_diaini from tarefalog where at43_tarefa = tarefa.at40_sequencial and at43_progresso = 100) as db_dia100, tarefa.at40_autorizada as dl_aut, tarefa.at40_progresso::integer,case tarefa.at40_prioridade when 1 then 'Baixa' when 2 then 'M?dia' when 3 then 'Alta' end as at40_prioridade,tarefa.at40_diaini,tarefa.at40_previsao||'/'||tarefa.at40_tipoprevisao as dl_Dura??o,tarefa.at40_diafim,(tarefa.at40_diafim::date - '".date("Y-m-d",db_getsession("DB_datausu"))."'::date) as dl_Pendente,tarefaenvol.at45_perc as dl_Envolvimento,clientes.at01_nomecli as nome_cliente,tarefa_lanc.at36_usuario as db_usulanc,tarefa_lanc.at36_tarefa as db_tarefa,db_usuarios.nome as dl_Envolvido,db_usuarios_lanc.nome as dl_Criador,tarefa.at40_descr || '-'||at40_obs||'/'||db_proced.at30_descr as dl_Tarefa,tarefa.at40_descr as db_descr";
    $campos = "tarefa.at40_sequencial, tarefa.at40_urgente, (select max(at43_diaini) as at43_diaini from tarefalog where at43_tarefa = tarefa.at40_sequencial and at43_progresso = 100) as db_dia100, tarefa.at40_autorizada as dl_aut, tarefa.at40_progresso::integer, tarefa.at40_descr || '/'||case when db_proced.at30_descr is null then '' else db_proced.at30_descr end as dl_Tarefa, case tarefa.at40_prioridade when 1 then 'Baixa' when 2 then 'Média' when 3 then 'Alta' end as at40_prioridade,tarefa.at40_diaini,tarefa.at40_previsao||'/'||tarefa.at40_tipoprevisao as dl_Duração,tarefa.at40_diafim,(tarefa.at40_diafim::date - '".date("Y-m-d",db_getsession("DB_datausu"))."'::date) as dl_Pendente,tarefaenvol.at45_perc as dl_Envolvimento,clientes.at01_nomecli as nome_cliente,tarefa_lanc.at36_usuario as db_usulanc,tarefa_lanc.at36_tarefa as db_tarefa,db_usuarios.nome as dl_Envolvido,db_usuarios_lanc.nome as dl_Criador";
  } elseif (isset($at40_responsavel) and $at40_responsavel == "0") {
    //$campos = "tarefa.at40_sequencial, tarefa.at40_urgente, (select max(at43_diaini) as at43_diaini from tarefalog where at43_tarefa = tarefa.at40_sequencial and at43_progresso = 100) as db_dia100, tarefa.at40_autorizada as dl_aut, tarefa.at40_progresso::integer,case tarefa.at40_prioridade when 1 then 'Baixa' when 2 then 'M?dia' when 3 then 'Alta' end as at40_prioridade,tarefa.at40_diaini,tarefa.at40_previsao||'/'||tarefa.at40_tipoprevisao as dl_Dura??o,tarefa.at40_diafim,(tarefa.at40_diafim::date - '".date("Y-m-d",db_getsession("DB_datausu"))."'::date) as dl_Pendente,clientes.at01_nomecli as nome_cliente,tarefa_lanc.at36_usuario as db_usulanc,tarefa_lanc.at36_tarefa as db_tarefa,db_usuarios_lanc.nome as dl_Criador, tarefa.at40_descr||'-'||at40_obs||'/'||db_proced.at30_descr as dl_Tarefa,tarefa.at40_descr as db_descr";
    $campos = "tarefa.at40_sequencial, tarefa.at40_urgente, (select max(at43_diaini) as at43_diaini from tarefalog where at43_tarefa = tarefa.at40_sequencial and at43_progresso = 100) as db_dia100, tarefa.at40_autorizada as dl_aut, tarefa.at40_progresso::integer,case tarefa.at40_prioridade when 1 then 'Baixa' when 2 then 'Média' when 3 then 'Alta' end as at40_prioridade,tarefa.at40_diaini,tarefa.at40_previsao||'/'||tarefa.at40_tipoprevisao as dl_Duração,tarefa.at40_diafim,(tarefa.at40_diafim::date - '".date("Y-m-d",db_getsession("DB_datausu"))."'::date) as dl_Pendente,clientes.at01_nomecli as nome_cliente,tarefa_lanc.at36_usuario as db_usulanc,tarefa_lanc.at36_tarefa as db_tarefa,db_usuarios_lanc.nome as dl_Criador, tarefa.at40_descr||'/'||case when db_proced.at30_descr is null then '' else db_proced.at30_descr end as dl_Tarefa";
  }
  
  if(isset($chave_at40_sequencial) && (trim($chave_at40_sequencial)!="" or 1==2) ){
    //$sql = $cltarefa->sql_query($chave_at40_sequencial,$campos,"dl_pendente, at40_sequencial","at40_autorizada is true");
    $sql = $cltarefa->sql_query($chave_at40_sequencial,$campos,"dl_pendente, at40_sequencial"," at40_ativo is true");
  }else if(isset($chave_at40_descr) && (trim($chave_at40_descr)!="") or 1==2){
    $sql = $cltarefa->sql_query("",$campos,"dl_pendente, at40_descr"," at40_descr like '$chave_at40_descr%' and at40_ativo is true");
  } else {
    
    if(isset($at40_sequencial) && ($at40_sequencial != "") ){
      $where = " at40_sequencial = $at40_sequencial";
      $sql = $cltarefa->sql_query_cons_envol("",$campos,"dl_pendente, tarefa.at40_prioridade desc",$where);
    } elseif (isset($at40_responsavel) and $at40_responsavel != "0") {
      $sql = $cltarefa->sql_query_cons_envol("",$campos,"dl_pendente, tarefa.at40_prioridade desc",$where);
    } elseif (isset($at40_responsavel) and $at40_responsavel == "0") {
      //$sql = $cltarefa->sql_query_cons_tarefa("",$campos,"dl_pendente, tarefa.at40_prioridade desc",$where);
      $sql = $cltarefa->sql_query_cons_envol("",$campos,"dl_pendente, tarefa.at40_prioridade desc",$where);
    }
    //$sql = "select db_descr, at40_sequencial, at40_urgente, db_dia100, dl_aut, at40_progresso,at40_prioridade,at40_diaini,dl_Dura??o,at40_diafim,dl_Pendente,max(dl_Envolvimento) as dl_Envolvimento,max(nome_cliente) as nome_cliente,db_usulanc,db_tarefa,max(dl_Envolvido) as dl_Envolvido,dl_Criador,dl_Tarefa from (select * from (select distinct * from ($sql) as x) as y $where_envol order by " . ($ordem == 1?"at40_diafim,":"") . " at40_prioridade, dl_pendente * -1 desc) as x group by at40_sequencial,at40_urgente, db_dia100, dl_aut, at40_progresso, at40_prioridade, at40_diaini, dl_Dura??o, at40_diafim, dl_Pendente, db_usulanc, db_tarefa, dl_Criador, dl_Tarefa, db_descr order by at40_urgente desc, " . ($ordem == 1?"at40_diafim,":"") . " at40_prioridade, dl_pendente * -1 desc";

    if (db_getsession("DB_id_usuario")==101 or db_getsession("DB_id_usuario")==157 or db_getsession("DB_id_usuario")==11136001 or db_getsession("DB_id_usuario")==8){
      $ordem_agendamento = " ( select min(at77_dataagenda||at77_hora) from tarefaagenda where at77_tarefa = at40_sequencial and at77_datavalidade is null and at77_id_usuario = ".db_getsession("DB_id_usuario") . "), ";
//    if (db_getsession("DB_id_usuario")==101 ){
//      $ordem_agendamento = " max(nome_cliente),$ordem_agendamento ";
//    }
    } else {
      $ordem_agendamento = " ";
    }

    $sql = "select at40_sequencial, at40_urgente, db_dia100, dl_aut, at40_progresso,dl_Tarefa,max(nome_cliente) as nome_cliente,at40_prioridade,at40_diaini,dl_Duração,at40_diafim,dl_Pendente,max(dl_Envolvimento) as dl_Envolvimento,db_usulanc,db_tarefa,max(dl_Envolvido) as dl_Envolvido,dl_Criador from (select * from (select distinct * from ($sql) as x) as y $where_envol order by " . ($ordem == 1?"at40_diafim,":"") . " at40_prioridade, dl_pendente * -1 desc) as x group by at40_sequencial,at40_urgente, db_dia100, dl_aut, at40_progresso,at40_prioridade,at40_diaini,dl_Duração,at40_diafim,dl_Pendente,db_usulanc,db_tarefa,dl_Criador,dl_Tarefa order by $ordem_agendamento at40_urgente desc, " . ($ordem == 1?"at40_diafim,":"") . " at40_prioridade, dl_pendente * -1 desc";
//    echo "<br>$sql<br><br>";exit;
    
  }
  
  $opcoes ="'x=1" . (@$at40_sequencial == ""?"":"\&at40_sequencial=" . @$at40_sequencial) . (@$leitura == ""?"":"\&leitura=" . @$leitura) . (@$at40_autoriza == ""?"":"\&at40_autoriza=" . @$at40_autoriza) . (@$at40_responsavel == ""?"":"\&at40_responsavel=" . @$at40_responsavel) . (@$at40_cliente == ""?"":"\&at40_cliente=" . @$at40_cliente) . (@$at40_progressoini == ""?"":"\&at40_progressoini=" . @$at40_progressoini) . (@$at40_progressofim == ""?"":"\&at40_progressofim=" . @$at40_progressofim) . (@$ordem == ""?"":"\&ordem=" . @$ordem) . (@$at40_grupoproced == ""?"":"\&at40_grupoproced=" . @$at40_grupoproced). (@$at40_proced == ""?"":"\&at40_proced=" . @$at40_proced) . (@$at40_area == ""?"":"\&at40_area=" . @$at40_area) . (@$at40_modulo == ""?"":"\&at40_modulo=" . @$at40_modulo) . (@$at40_syscadproced == ""?"":"\&at40_syscadproced=" . @$at40_syscadproced) . (@$at40_situacao == ""?"":"\&at40_situacao=" . @$at40_situacao) . (@$at40_diaini_ano == ""?"":"\&at40_diaini_ano=" . @$at40_diaini_ano . "\&at40_diaini_mes=" . @$at40_diaini_mes . "\&at40_diaini_dia=" . @$at40_diaini_dia) . (@$at40_diafim_ano == ""?"":"\&at40_diafim_ano=" . @$at40_diafim_ano . "\&at40_diafim_mes=" . @$at40_diafim_mes . "\&at40_diafim_dia=" . @$at40_diafim_dia) . (@$tipo_rel == ""?"":"\&tipo_rel=" . @$tipo_rel) . (@$opcao_rel == ""?"":"\&opcao_rel=" . @$opcao_rel) .  (@$todasprocedencias == ""?"":"\&todasprocedencias=" . @$todasprocedencias).  (@$at40_motivo == ""?"":"\&at40_motivo=" . @$at40_motivo) .  (@$tipodatafinal == ""?"":"\&tipodatafinal=" . @$tipodatafinal) . (isset($semagenda)?"\&semagenda=$semagenda":""). (isset($coddepto)?"\&coddepto=$coddepto":"")."'"; 
  //echo "<tr><td>Em <blink><b><font color=red>verde</font></b></blink>, tarefas com mais de 3 dias sem registros...</td><td><b>Total de " . pg_numrows(pg_exec("select distinct at40_sequencial from ($sql) as xxx")) . " registros...</b></td></tr>";
  
//  $totalderegistros=pg_numrows(pg_exec("select distinct at40_sequencial from ($sql) as xxx"));
  
  echo "<tr><td>Em <blink><b><font color=green>verde</font></b></blink>, tarefas com mais de 3 dias sem registros...       <b>Total de ";
  ?> 
  <input type="text" size="5" style="border-style: none; background-color: transparent; text-align: right" name="totalderegistros" value="...">
  <?
  echo " registros... ";
  ?> 
  <input type="text" size="5" style="border-style: none; background-color: transparent; text-align: right" name="perctotal" value="...">
  <?
	echo "% - tempo: ";
  ?> 
  <input type="text" size="5" style="border-style: none; background-color: transparent; text-align: right" name="tempototal" value="...">
  <?
	echo " segundos...</b></td></tr>";
  
  //echo "<br> $sql <br>";
  //die($sql);

  if ($primeiravez == true) {
    $sql = "";
  } else {
    db_grid($sql, $leitura, $opcoes, @$funcao_js,@$todasprocedencias);
  }
  
}else{
  if($pesquisa_chave!=null && $pesquisa_chave!=""){
    //$result = $cltarefa->sql_record($cltarefa->sql_query($pesquisa_chave,"*",null,"at40_ativo is true"));
    $result = $cltarefa->sql_record($cltarefa->sql_query(null,"*",null,"at40_sequencial = $pesquisa_chave and at40_ativo is true"));
    if($cltarefa->numrows!=0){
      db_fieldsmemory($result,0);
      echo "<script>".$funcao_js."('$db_descr',false);</script>";
    }else{
      echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
    }
  }else{
    echo "<script>".$funcao_js."('',false);</script>";
  }
}
?>
</td>
</tr>
</form>
</table>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <?
}
global $semagenda;

function db_grid($sql, $leitura, $opcoes, $funcao_js,$todasprocedencias) {
  $result    = pg_exec($sql) or die($sql);
  $NumRows   = pg_numrows($result);
  $NumFields = pg_numfields($result);
  $codtarefa = 0;
  
  //cria nome da funcao com parametros
  if (isset($funcao_js) && trim($funcao_js) != "") {
    $arrayFuncao = split("\|", $funcao_js);
    $quantidadeItemsArrayFuncao = sizeof($arrayFuncao);
  }

  if ($NumRows == 0) {
    db_msgbox("Nenhum registro encontrado!");
    return true;
  }
  
  ?>
  <script>
  
  function js_pesquisa_usuario(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo.iframe_tarefa','db_iframe_usuario','func_tarefa.php?funcao_js=parent.db_iframe_tarefa.jan.js_mostratarefa1|at40_sequencial','Pesquisa',true,'20');
    }else{
      js_OpenJanelaIframe('top.corpo.iframe_tarefa','db_iframe_usuario','func_tarefa.php?pesquisa_chave='+document.form1.at40_sequencial.value+'&funcao_js=parent.db_iframe_tarefa.jan.js_mostratarefa1','Pesquisa',true,'20');
    }
  }
  
  function js_mostratarefa1(chave1) {
    parent.db_iframe_usuario.hide();
    document.form1.at40_sequencial.value = chave1;
    document.form1.submit();
  }
  
  function js_mostra_text(liga,nomediv,evt){
    return true;
  }
  function js_mostra_text2(liga,nomediv,evt){
    evt = (evt)?evt:(window.event)?window.event:''; 
    if(liga==true){
      document.getElementById(nomediv).style.top = 0; //evt.clientY;
      document.getElementById(nomediv).style.left = 0; //(evt.clientX+20);
      document.getElementById(nomediv).style.visibility = 'visible';
    }else
    document.getElementById(nomediv).style.visibility = 'hidden';
  }
  function js_relatorio(){
    situacao = '';
    virgula = '';
    if(document.form1.check_execucao.checked) {
      situacao += virgula+'1';
      virgula = ',';
    }
    
    if(document.form1.check_analise.checked) {
      situacao += virgula+'2';
      virgula = ',';
    }
    
    if(document.form1.check_finalizada.checked) {
      situacao += virgula+'3';
      virgula = ',';
    }
    
    if(document.form1.check_teste.checked) {
      situacao += virgula+'4';
    }
    
    if(document.form1.check_release.checked) {
      situacao += virgula+'5';
    }
    
    if(document.form1.check_off.checked) {
      situacao += virgula+'6';
    }

    if(document.form1.check_conflitotag.checked) {
      situacao += virgula+'7';
    }

    var sSemAgenda = '';

    if (document.getElementById('semagenda1').checked) {
      sSemAgenda = '1';
    }

    if (document.getElementById('semagenda2').checked) {
      sSemAgenda = '2';  
    }

    if (document.getElementById('semagenda3').checked) {
      sSemAgenda = '3';  
    }

    window.open('ate2_relatoriotarefas001.php?at40_sequencial='+document.form1.at40_sequencial.value+
                '&at40_autoriza='+document.form1.at40_autoriza.value+
                '&at40_responsavel='+document.form1.at40_responsavel.value+
                '&at40_motivo='+document.form1.at40_motivo.value+
                '&at40_grupoproced='+document.form1.at40_grupoproced.value+
                '&at40_cliente='+document.form1.at40_cliente.value+
                '&at40_progressoini='+document.form1.at40_progressoini.value+
                '&at40_progressofim='+document.form1.at40_progressofim.value+
                '&ordem='+document.form1.ordem.value+
                '&at40_proced='+document.form1.at40_proced.value+
                '&at40_situacao='+situacao+
                '&at40_diaini='+document.form1.at40_diaini_ano.value+
                '-'+document.form1.at40_diaini_mes.value+
                '-'+document.form1.at40_diaini_dia.value+
                '&at40_diafim='+document.form1.at40_diafim_ano.value+
                '-'+document.form1.at40_diafim_mes.value+
                '-'+document.form1.at40_diafim_dia.value+
                '&tipodatafinal='+document.form1.tipodatafinal.value+
                '&tipo_rel='+document.form1.tipo_rel.value+
                '&todasprocedencias='+document.form1.todasprocedencias.value+
                '&opcao_rel='+document.form1.opcao_rel.value+
                '&semagenda='+sSemAgenda,
                '','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  }
  </script>
  <?		
  echo "<table id=\"TabDbLov\" border=\"1\" cellspacing=\"1\" cellpadding=\"0\">\n";
  
  if($NumRows == 0) {
    echo "<tr>\n";
    echo "<td>\n";
    echo "<br><br>Nenhum registro encontrado";
    echo "</td>\n";
  } else {
    
    echo "<tr wrap>\n";
    $clrotulocab 		= new rotulolov();
    $cltarefa		= new cl_tarefa;
    $cltarefalog		= new cl_tarefalog;
    $cltarefalido		= new cl_tarefalido;
    $cltarefalidolog	= new cl_tarefalidolog;
    $cltarefa_lancprorrog	= new cl_tarefa_lancprorrog;
    
    if (1 == 1) {
      
      for($i = 0; $i < $NumFields; $i++) {
        
        if ($i == 0) {
          echo "<td wrap bgcolor=\"#6e77e8\" title=\"Seq\" align=\"center\">Seq</td>\n";
          echo "<td wrap bgcolor=\"#6e77e8\" title=\"Versão do Sistema\" align=\"center\">Versão</td>\n";
          echo "<td wrap bgcolor=\"#6e77e8\" title=\"Se já foi lido\" align=\"center\">Lido</td>\n";
          echo "<td wrap bgcolor=\"#6e77e8\" title=\"Urgente\" align=\"center\">Lido</td>\n";
          echo "<td wrap bgcolor=\"#6e77e8\" title=\"Quantidade de vezes que foi prorrogado\" align=\"center\">U</td>\n";
          echo "<td nowrap bgcolor=\"#6e77e8\" title=\"Agenda\" align=\"center\">Agenda</td>\n";
          echo "<td nowrap bgcolor=\"#6e77e8\" title=\"Versão do Cliente\" align=\"center\">Cliente</td>\n";
        }
        
        if(strlen(strstr(pg_fieldname($result, $i), "db_")) > 0) {
          continue;
        }
        
        if(strlen(strstr(pg_fieldname($result, $i), "db_")) == 0) {
          $clrotulocab->label(pg_fieldname($result, $i));
        }
        
        if(pg_fieldname($result, $i) == "dl_resp") {
          echo "<td nowrap bgcolor=\"#6e77e8\" title=\"Tipo de Responsabilidade\" align=\"center\">Tipo de Responsabilidade</td>\n";
        } else if(pg_fieldname($result, $i) == "at40_sequencial") {
          echo "<td nowrap bgcolor=\"#6e77e8\" title=\"Cod\" align=\"center\">Cod</td>\n";
        } else if(pg_fieldname($result, $i) == "at40_prioridade") {
          echo "<td nowrap bgcolor=\"#6e77e8\" title=\"Priori\" align=\"center\">Priori</td>\n";
        } else if(pg_fieldname($result, $i) == "dl_duração") {
          echo "<td nowrap bgcolor=\"#6e77e8\" title=\"Dura\" align=\"center\">Dura</td>\n";
        } else if(pg_fieldname($result, $i) == "at40_progresso") {
          echo "<td nowrap bgcolor=\"#6e77e8\" title=\"Progr\" align=\"center\">Progr</td>\n";
        } else if(pg_fieldname($result, $i) == "at40_diaini") {
          echo "<td nowrap bgcolor=\"#6e77e8\" title=\"Inicio\" align=\"center\">Inicio</td>\n";
        } else if(pg_fieldname($result, $i) == "at40_diafim") {
          echo "<td nowrap bgcolor=\"#6e77e8\" title=\"Final\" align=\"center\">Final</td>\n";
        } else if(pg_fieldname($result, $i) == "dl_envolvimento") {
          echo "<td nowrap bgcolor=\"#6e77e8\" title=\"Envolv\" align=\"center\">Envol</td>\n";
        } else if(pg_fieldname($result, $i) == "dl_tarefa") {
          echo "<td wrap bgcolor=\"#6e77e8\" title=\"Descricao\" width=500px align=\"center\">Descricao</td>\n";
        } else if(pg_fieldname($result, $i) == "nome_cliente") {
          echo "<td nowrap bgcolor=\"#6e77e8\" title=\"Cliente\" align=\"center\">Cliente</td>\n";
        } else {
          echo "<td nowrap bgcolor=\"#6e77e8\" title=\"".$clrotulocab->title."\" align=\"center\">".ucfirst($clrotulocab->titulo)."</td>\n";
        }
      }
      
    }
    
    echo "</tr>\n";
    $contacorfundo = 0;
		$totalderegistros=0;

		list($usec, $sec) = explode(" ", microtime());
    $time_start = ((float)$usec + (float)$sec);

    for($i = 0; $i < $NumRows; $i++) {
      if (isset($funcao_js) && trim($funcao_js) != "") {
        $loop     = "";
        $caracter = "";
        if ($quantidadeItemsArrayFuncao > 1) {
          for ($cont = 1; $cont < $quantidadeItemsArrayFuncao; $cont++) {
            if (strlen($arrayFuncao[$cont]) > 3) {
              for ($luup = 0; $luup < pg_NumFields($result); $luup ++) {
                if (pg_FieldName($result, $luup) == $arrayFuncao[$cont]) {
                  $arrayFuncao[$cont] = $arrayFuncao[$cont];
                }
              }
            }
            
            $loop .= $caracter."'".addslashes(@ pg_result($result, $i, (strlen($arrayFuncao[$cont]) < 4 ? (int) $arrayFuncao[$cont] : $arrayFuncao[$cont])))."'";
            $caracter = ",";
          }
          $resultadoRetorno = $arrayFuncao[0]."(".$loop.")";
        } else {
          $resultadoRetorno = $arrayFuncao[0]."()";
        }
      }
      
      $imprime = true;
      
      if(pg_result($result, $i, 0) == $codtarefa and 1==2) {
        continue;
      } else {
        $codtarefa = pg_result($result, $i, 0);
      }
      
      if ($contacorfundo == 0) {
        $corfundo = "#008080";
        $corfundo = "#CDCDCD";
        $corfundo = "#CCCCCC";
        $corfundo = "#FFFFFF";
        $contacorfundo = 1;
      } else {
        $corfundo = "#800080";
        $corfundo = "#236B8E";
        $corfundo = "#999999";
        $corfundo = "#FFFFFF";
        $contacorfundo = 0;
      }
      
      $at40_sequencial = pg_result($result, $i, "at40_sequencial");
      

      // verifica agenda
      $sqla = " select at77_id_usuario,at77_dataagenda,at77_hora from tarefaagenda 
               where at77_tarefa = $at40_sequencial
                 and at77_datavalidade is null 
                 and at77_id_usuario = ".db_getsession("DB_id_usuario");
      $result_agenda = pg_exec($sqla);
  
      global $semagenda;
      if( isset($semagenda) && $semagenda == '1' && pg_numrows($result_agenda) > 0 ){
        continue;
      }
      if( isset($semagenda) && $semagenda == '2' && pg_numrows($result_agenda) == 0 ){
        continue;
      }



      if( !isset($todasprocedencias) || (isset($todasprocedencias) && $todasprocedencias == 'N')){
        // nao mostrar tarefas com procedencia de reuniao e visitas
        $sql = "select * 
        from tarefaproced 
        where at41_tarefa = $at40_sequencial and at41_proced in (9,16,17)";
        $rest = pg_exec($sql);
        if(pg_numrows($rest)>0){
          continue;
        }
        
      }
      
      
      global $tempo;
      global $at40_diafimreg;
      $sqlreg = "select at40_diafim as at40_diafimreg, current_date - max as tempo from (
      select  at43_tarefa, 
      max(at43_diafim) 
      from tarefalog 
      where at43_tarefa = $at40_sequencial
      group by at43_tarefa) as x 
      left join tarefa on at40_sequencial = at43_tarefa
      where at40_diafim < '" . date("Y-m-d",db_getsession("DB_datausu")) . "'";
      $result_reg = pg_exec($sqlreg) or die($sqlreg);
      if (pg_numrows($result_reg) == 0) {
        $sqlreg = "select at40_diafim as at40_diafimreg, current_date - at40_diafim as tempo from tarefa where at40_sequencial = $at40_sequencial";
        $result_reg = pg_exec($sqlreg) or die($sqlreg);
        db_fieldsmemory($result_reg, 0);
      } else {
        db_fieldsmemory($result_reg, 0);
      }
      
      if (abs($tempo) > 3 and $at40_diafimreg < date("Y-m-d",db_getsession("DB_datausu"))) {
        $corfundo = "lightGreen";
      }
      
      global $at40_urgente;
      $result_tarefa = $cltarefa->sql_record($cltarefa->sql_query_file($at40_sequencial,"at40_urgente"));
      db_fieldsmemory($result_tarefa, 0);
      if ($at40_urgente == 1) {
        $corfundo = "red";
      }
      
      
      $sql = "select at47_tarefa
      from tarefasituacao
      where at47_tarefa = $at40_sequencial and at47_situacao = 4";
      $rest = pg_exec($sql);
      if(pg_numrows($rest)>0){
        $corfundo = "yellow";
      }    
      
      echo "<tr wrap bgcolor=\"$corfundo\">\n";
      
      for($j = 0; $j < $NumFields; $j++) {
        
        if ($imprime == false) {
          continue;
        }
        
        if ($j == 0) {
          global $at43_sequencial;
          global $at60_sequencial;
          global $at43_sequencial;
          global $lido;
          $at60_sequencial 	= 0;
          $at43_sequencial	= 0;
          
          $datalido = date("Y-m-d",db_getsession("DB_datausu"));
          $where_datalido = "1=1";
          
					if (substr($leitura,0,1) == "N" and strlen($leitura) == 2) {
						$dias=(int) substr($leitura,1,1);
						$sqlsoma = "select '$datalido'::date - '$dias day'::interval as datalido";
						$resultsoma = pg_exec($sqlsoma) or die($sqlsoma);
						$datalido = pg_result($resultsoma,0,0);
						$where_datalido = " at36_data >= '$datalido'";
					}
          $at40_sequencial = pg_result($result, $i, "at40_sequencial");
          $resultlog = $cltarefalog->sql_record($cltarefalog->sql_query(null,"at43_sequencial","at43_sequencial desc","at43_tarefa = $at40_sequencial"));
          $lido = false;
          if ($cltarefalog->numrows == 0) {
            $resultloglido = $cltarefalido->sql_record($cltarefalido->sql_query(null,"*",null,"at36_tarefa = $at40_sequencial and $where_datalido and at36_usuario = " . db_getsession("DB_id_usuario")));
            if ($cltarefalido->numrows > 0) {
              $lido = true;
            }
          }
          
          if ($cltarefalog->numrows > 0) {
            db_fieldsmemory($resultlog, 0);
            $resultlidolog = $cltarefalidolog->sql_record($cltarefalidolog->sql_query(null,"at60_sequencial",null,"at60_tarefalog = $at43_sequencial and $where_datalido and at36_usuario = " . db_getsession("DB_id_usuario")));
            if ($cltarefalidolog->numrows > 0) {
              db_fieldsmemory($resultlidolog, 0);
              $lido = true;
            }
          }

          if ($leitura == "L") {
            if ($lido == false) {
              $imprime = false;
              continue;
            }
          } elseif (substr($leitura,0,1) == "N" and strlen($leitura) == 2) {
            if ($lido == true) {
              $imprime = false;
              continue;
            }
          } elseif (substr($leitura,0,1) == "N" and strlen($leitura) == 1) {
            if ($lido == true) {
              $imprime = false;
              continue;
            }
          }
          
					$totalderegistros++;

					echo "<td>$totalderegistros</td>";
			
          global $cldb_versaotarefa, $db30_codversao,$db30_codrelease; 
          $result_versao = $cldb_versaotarefa->sql_record($cldb_versaotarefa->sql_query(null,"db30_codversao,db30_codrelease",null," db29_tarefa = $at40_sequencial"));
          if($cldb_versaotarefa->numrows>0){
            db_fieldsmemory($result_versao, 0);
            echo "<td>2.$db30_codversao.$db30_codrelease</td>";
          }else{

            $resultlog = $cltarefalog->sql_record($cltarefalog->sql_query(null,"at43_sequencial","at43_sequencial desc","at43_tarefa = $at40_sequencial and at43_tipomov = 5 "));
            if ($cltarefalog->numrows > 0) {
              $resultlog = $cltarefalog->sql_record($cltarefalog->sql_query(null,"at43_sequencial","at43_sequencial desc","at43_tarefa = $at40_sequencial and at43_tipomov = 6 "));
              if ($cltarefalog->numrows > 0) {
                echo "<td title='Manual Atualizado'>*Manual</td>";
              }else{
                echo "<td title='Atualizar Manual'>Manual</td>";
              }
            }else{
              echo "<td></td>";
            }

          }
          
          echo "<td><input type=\"checkbox\" name=\"chaves$at40_sequencial\" value=\"$at40_sequencial\" onClick=\"js_enviarlido($at40_sequencial, $at60_sequencial, $at43_sequencial, " . ($lido == true?1:0) . ")\" ";
          if ($lido == true) {
            echo "checked";
          }
          echo "></td>";
          
          global $quant_prorrog;                                  
          $resultprorrog = $cltarefa_lancprorrog->sql_record($cltarefa_lancprorrog->sql_query(null,"count(*) as quant_prorrog","","at40_sequencial = $at40_sequencial"));
          db_fieldsmemory($resultprorrog, 0);
          
          echo "<td>$quant_prorrog</td>";
          
          global $at40_urgente;
          $result_tarefa = $cltarefa->sql_record($cltarefa->sql_query_file($at40_sequencial,"at40_urgente"));
          
          db_fieldsmemory($result_tarefa, 0);
          echo "<td><input type=\"checkbox\" name=\"urgente_$at40_sequencial\" value=\"$at40_sequencial\" onClick=\"js_urgente($at40_sequencial)\" ";
          if ($at40_urgente == 1) {
            echo "checked";
          }
          echo "></td>";
          
          
         if( pg_numrows($result_agenda) > 0 ){
            echo "<td><input name='agenda' type='button' value='".db_formatar(pg_result($result_agenda,0,1),'d') . "-" . pg_result($result_agenda,0,2) ."' onclick='js_abre_agendamento($at40_sequencial)'></td>";
          }else{
            echo "<td><input name='agenda' type='button' value='' onclick='js_abre_agendamento($at40_sequencial)'></td>";
          }
          //echo "<td>$tempo</td>";
          
          $sql = "
          select case when v.db30_codver is null then '' else '2.'||v.db30_codversao||'.'||v.db30_codrelease end::varchar as versao_cliente,db_itensmenu.descricao||'-'||db_itensmenu.help||'-'||db_itensmenu.funcao as descricao
          from tarefaitem
          left join atenditem          on at05_seq    = at44_atenditem
          left join atenditemmenu      on at23_atenditem = at05_seq
          left join db_itensmenu       on id_item  = at23_itemenu
          left join atendimentoversao  on at05_codatend = at67_codatend
          left join db_versao v on at67_codver = v.db30_codver
          where  at44_tarefa = $at40_sequencial
          union all
          select case when v.db30_codver is null then '' else '2.'||v.db30_codversao||'.'||v.db30_codrelease end::varchar as versao_cliente,db_itensmenu.descricao||'-'||db_itensmenu.help||'-'||db_itensmenu.funcao as descricao                 from atenditemtarefa
          left join atenditem          on at05_seq    = at18_atenditem
          left join atenditemmenu      on at23_atenditem = at05_seq
          left join db_itensmenu       on id_item  = at23_itemenu
          left join atendimentoversao  on at05_codatend = at67_codatend
          left join db_versao v on at67_codver = v.db30_codver
          where  at18_tarefa = $at40_sequencial
          
          ";
          $rest = pg_exec($sql) or die($sql);
          if(pg_numrows($rest)>0){
            echo "<td title='".pg_result($rest,0,1)."'>".pg_result($rest,0,0)."</td>";
          }else{
            echo "<td ></td>";
          }
          
        }
        
        if(strlen(strstr(pg_fieldname($result, $j), "db_")) == 0) {
          if(pg_fieldtype($result, $j) == "date") {
            if(pg_result($result, $i, $j) != "") {
              $matriz_data = split("-", pg_result($result, $i, $j));
              $var_data = $matriz_data[2]."/".$matriz_data[1]."/".$matriz_data[0];
              $var_data = $matriz_data[2]."/".$matriz_data[1];
              $cor = "#FFFFFF";
              $cor = $corfundo;
            } else {
              $cor = "#F8EC07";
              $cor = $corfundo;
              $var_data = "//";
            }
            echo "<td valign=\"top\" align=\"center\" id=\"I".$i.$j."\" style=\"background-color:$cor;text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"\" onClick=\"".(isset($funcao_js)&&trim($funcao_js)!=""?$resultadoRetorno.";return false\">":"parent.js_mostratarefas(".pg_result($result, $i, 0).",$opcoes)\">").trim($var_data)."</a>&nbsp;</td>\n";
          } else { 
            if(pg_fieldtype($result, $j) == "float8") {
              $var_data = db_formatar(pg_result($result, $i, $j), 'f', ' ');
              echo "<td valign=\"top\" align=\"right\" id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"\" onClick=\"".(isset($funcao_js)&&trim($funcao_js)!=""?$resultadoRetorno.";return false\">":"parent.js_mostratarefas(".pg_result($result, $i, 0).",$opcoes)\">").trim($var_data)."</a>&nbsp;</td>\n";
            } else {
              if(pg_fieldtype($result, $j) == "bool") {
                $var_data = (pg_result($result, $i, $j) == 'f' || pg_result($result, $i, $j) == '' ? 'Não' : 'Sim');
                echo "<td valign=\"top\" align=\"center\" id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"\" onClick=\"".(isset($funcao_js)&&trim($funcao_js)!=""?$resultadoRetorno.";return false\">":"parent.js_mostratarefas(".pg_result($result, $i, 0).",$opcoes)\">").trim($var_data)."</a>&nbsp;</td>\n";
              } else {
                if(pg_fieldtype($result, $j) == "text") {
                  if(pg_fieldname($result, $j) == "dl_tarefa") {
                    $var_data = pg_result($result, $i, $j);
                    $var_data = substr(pg_result($result, $i, $j),0,75) . "...";
                  } else {
                    $var_data = pg_result($result, $i, $j);
                  }
                  if(pg_fieldname($result, $j) == "dl_tarefa") {
                    if (1 == 1) {
                      echo "<td onMouseOver=\"js_mostra_text(true,'div_text_".$i."_".$j."',event);\" onMouseOut=\"js_mostra_text(false,'div_text_".$i."_".$j."',event);\" valign=\"top\" id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"\" onClick=\"".(isset($funcao_js)&&trim($funcao_js)!=""?$resultadoRetorno.";return false\">":"parent.js_mostratarefas(".pg_result($result, $i, 0).",$opcoes)\">").trim($var_data)."</a>&nbsp;&nbsp;&nbsp;</td>\n";
                      $corfundoteste = "#000000";
                      $corfundoteste = "#FFFFFF";
                      $corfundoteste = "#CCCCCC";
                    }
                  } else {
                    echo "<td valign=\"top\" id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"\" onClick=\"".(isset($funcao_js)&&trim($funcao_js)!=""?$resultadoRetorno.";return false\">":"parent.js_mostratarefas(".pg_result($result, $i, 0).",$opcoes)\">").trim($var_data)."</a>&nbsp;</td>\n";
                  }
                } else {
                  if(pg_fieldname($result, $j) == "dl_resp") {
                    if(pg_result($result, $i, $j) == pg_result($result, $i, 11)&&
                    pg_result($result, $i, 0)  == pg_result($result, $i, 12)) {
                      $desc_resp = "CRIADOR DA TAREFA";
                    } else {
                      if(pg_result($result, $i, $j) == db_getsession("DB_id_usuario")) {
                        $desc_resp = "RESPONSAVEL";
                      } else {
                        $desc_resp = "ENVOLVIDO";
                      }
                    }
                    echo "<td valign=\"top\" id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"\" onClick=\"".(isset($funcao_js)&&trim($funcao_js)!=""?$resultadoRetorno.";return false\">":"parent.js_mostratarefas(".pg_result($result, $i, 0).",$opcoes)\">").$desc_resp."</a>&nbsp;</td>\n";
                  } else {
                    if(pg_fieldname($result, $j) == "dl_pendente") {
                      if(pg_result($result, $i, $j) == 0) {
                        $cor_texto = "#FFFFFF";
                      }
                      else if(pg_result($result, $i, $j) > 0) {
                        $cor_texto = "#6e77e8";
                      }
                      else {
                        $cor_texto = "#FF0000";
                        $cor_texto = "#CD5C5C";
                      }
                      if(pg_result($result, $i, $j) > 0 || pg_result($result, $i, $j) < 0) {
                        $var_data = db_formatar(pg_result($result, $i, $j),'s','0',2,'e',0);
                      }
                      else {
                        $var_data = 0;
                      }
                      echo "<td bgcolor=\"".$cor_texto."\" align=\"center\" valign=\"top\" id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"\" onClick=\"".(isset($funcao_js)&&trim($funcao_js)!=""?$resultadoRetorno.";return false\">":"parent.js_mostratarefas(".pg_result($result, $i, 0).",$opcoes)\">").trim($var_data)."</a>&nbsp;</td>\n";
                    } else {
                      if(pg_fieldname($result, $j) == "dl_criador" or pg_fieldname($result, $j) == "dl_envolvido") {
                        $mostrar = trim(pg_result($result, $i, $j));
                        $mostra2 = split(" ", $mostrar);
                        
                        if (sizeof($mostra2) == 0) {
                          $mostrar = "SEM CRIADOR";
                        } elseif (sizeof($mostra2) == 1 or 1==1) {
                          $mostrar = $mostra2[0];
                        } elseif (sizeof($mostra2) >= 2) {
                          $mostrar = $mostra2[0] . " " . $mostra2[1];
                        }
                        
                        echo "<td valign=\"top\" id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"\" onClick=\"".(isset($funcao_js)&&trim($funcao_js)!=""?$resultadoRetorno.";return false\">":"parent.js_mostratarefas(".pg_result($result, $i, 0).",$opcoes)\">").$mostrar."</a>&nbsp;</td>\n";
                      } else {
                        echo "<td valign=\"top\" id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;\" nowrap><a title=\"Clique Aqui\" style=\"text-decoration:none;color:#000000;\" href=\"\" onClick=\"".(isset($funcao_js)&&trim($funcao_js)!=""?$resultadoRetorno.";return false\">":"parent.js_mostratarefas(".pg_result($result, $i, 0).",$opcoes)\">").substr(trim(pg_result($result, $i, $j)),0,20)."</a>&nbsp;</td>\n";
                      }
                    }
                  }
                }				        	
              }
            }
          }
        }
      }

			$perctotal=$i/$NumRows*100;

			list($usec, $sec) = explode(" ", microtime());
			$time_end = ((float)$usec + (float)$sec);
			$time = $time_end - $time_start;

			echo "<script>
			document.form1.totalderegistros.value=" . $totalderegistros . ";
			document.form1.perctotal.value=" . $perctotal . ";
			document.form1.tempototal.value=" . $time . ";
			</script>";
			echo "</tr>\n";
      
    }
    
  }

	echo "<script>
	document.form1.perctotal.value=100;
	</script>";
	echo "</tr>\n";
  
  echo "</table>\n";
  if (1==1) {
    for ($i = 0; $i < $NumRows; $i ++) {
      for ($j = 0; $j < $NumFields; $j ++) {
        if(pg_fieldname($result, $j) == "dl_tarefa") {
          if (pg_fieldtype($result, $j) == "text") {
            $clrotulocab->label(pg_fieldname($result, $j));
            echo "<div id='div_text_".$i."_".$j."' style='position:absolute;left:10px; top:10px; visibility:hidden ; background-color:#6699CC ; border:2px outset #cccccc; align:left'>
            <table>
            <tr>
            <td align='left'>
            <font color='black' face='arial' size='2'><strong>".$clrotulocab->titulo."</strong>:</font><br>
            <font color='black' face='arial' size='1'>".str_replace("\n", "<br>", pg_result($result, $i, $j))."</font>
            </td>
            </tr>
            </table>
            </div>\n";
          }
        }
      }
    }
  }
}
?>