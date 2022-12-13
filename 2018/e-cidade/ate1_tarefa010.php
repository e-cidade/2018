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
include("classes/db_tarefa_lanc_classe.php");
include("classes/db_tarefa_lancprorrog_classe.php");
include("classes/db_tarefalog_classe.php");
include("classes/db_tarefalogsituacao_classe.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_tarefa_classe.php");
include("classes/db_tarefamodulo_classe.php");
include("classes/db_tarefaproced_classe.php");
include("classes/db_tarefasituacao_classe.php");
include("classes/db_tarefausu_classe.php");
include("classes/db_tarefaenvol_classe.php");
include("classes/db_tarefamotivo_classe.php");
include("classes/db_tarefaclientes_classe.php");
include("classes/db_tarefasyscadproced_classe.php");
include("classes/db_tarefaitem_classe.php");
include("classes/db_tarefaanexos_classe.php");

$cltarefa         	= new cl_tarefa;
$cltarefamodulo   	= new cl_tarefamodulo;
$cltarefaproced   	= new cl_tarefaproced;
$cltarefasituacao 	= new cl_tarefasituacao;
$cltarefaenvol    	= new cl_tarefaenvol;
$cltarefausu      	= new cl_tarefausu;
$cltarefamotivo   	= new cl_tarefamotivo;
$cltarefaclientes 	= new cl_tarefaclientes;
$cltarefalog      	= new cl_tarefalog;
$cltarefalogsituacao= new cl_tarefalogsituacao;
$cltarefa_lanc    	= new cl_tarefa_lanc;
$cltarefa_lancprorrog 	= new cl_tarefa_lancprorrog;
$cldb_usuarios 	  	= new cl_db_usuarios;
$cl_tarefasyscadproced = new cl_tarefasyscadproced;
$cltarefaitem 	  	= new cl_tarefaitem;
$cltarefaanexos     = new cl_tarefaanexos;

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
$ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
$usuario = db_getsession("DB_id_usuario");
//echo "tarefa = $tarefa <br> ip = $ip... usu= $usuario";


// pegar todos os dados desta tarefa e incluir uma nova.
$sqlerro ='false';
$sqltarefa = "select * from tarefa where at40_sequencial = $tarefa ";
$resulttarefa = pg_query($sqltarefa);
$linhatarefa = pg_num_rows($resulttarefa);
if($linhatarefa>0){
	db_fieldsmemory($resulttarefa, 0);
	$data = date("Y-m-d");
	$hora = date("H:i");
	$cltarefa -> at40_responsavel = $at40_responsavel;
	$cltarefa ->at40_descr        = $at40_descr;
	$cltarefa ->at40_diaini       = $data;
	$cltarefa ->at40_diafim       = $data;
	$cltarefa ->at40_previsao     = '';
	$cltarefa ->at40_tipoprevisao = $at40_tipoprevisao;
	$cltarefa ->at40_horainidia   = $hora;
	$cltarefa ->at40_horafim      = $hora;
	$cltarefa ->at40_progresso    = '0';
	$cltarefa ->at40_prioridade   = $at40_prioridade;
	$cltarefa ->at40_obs          = $at40_obs;
	$cltarefa ->at40_autorizada   = 'false';
	$cltarefa ->at40_tipo         = $at40_tipo;
	$cltarefa ->at40_ativo        = $at40_ativo;
	$cltarefa ->at40_urgente      = $at40_urgente;
	$cltarefa ->incluir(null);
	
	$at40_sequencial = $cltarefa -> at40_sequencial;
	// echo "incluiu tarefa = ". $at40_sequencial;
	if ($cltarefa->erro_status == 0) {
	          $erro_msg = $cltarefa->erro_msg;
	          echo "$erro_msg";
	          $sqlerro = true;
	}
}else{
	echo "Não encontrado a tarefa $tarefa";
	exit;
}
if($sqlerro=='false'){
	$sqltarsyspro = "select * from tarefasyscadproced where at37_tarefa= $tarefa ";
	$resulttarsyspro = pg_query($sqltarsyspro);
	$linhatarsyspro = pg_num_rows($resulttarsyspro);
	if($linhatarsyspro>0){
		db_fieldsmemory($resulttarsyspro, 0); 
		$cl_tarefasyscadproced->at37_tarefa       = $at40_sequencial;
		$cl_tarefasyscadproced->at37_syscadproced = $at37_syscadproced;
		$cl_tarefasyscadproced->incluir(null);
		if ($cl_tarefasyscadproced->erro_status == 0) {
		   $erro_msg = $cl_tarefasyscadproced->erro_msg;
		   echo "$erro_msg";
		   $sqlerro = true;
		}
	}
}

if($sqlerro=='false'){
	$sqlmodulo = "select * from tarefamodulo where at49_tarefa= $tarefa ";
	$resultmodulo = pg_query($sqlmodulo);
	$linhamodulo = pg_num_rows($resultmodulo);
	if($linhamodulo>0){
		db_fieldsmemory($resultmodulo, 0); 
		$cltarefamodulo->at49_tarefa = $at40_sequencial;
		$cltarefamodulo->at49_modulo = $at49_modulo;
		$cltarefamodulo->incluir(null);
		if ($cltarefamodulo->erro_status == 0) {
		   $erro_msg = $cltarefamodulo->erro_msg;
		   echo "$erro_msg";
		   $sqlerro = true;
		}
	}
}

if($sqlerro=='false'){
	$sqlmotivo = "select * from tarefamotivo where at55_tarefa= $tarefa ";
	$resultmotivo = pg_query($sqlmotivo);
	$linhamotivo = pg_num_rows($resultmotivo);
	if($linhamotivo>0){
		db_fieldsmemory($resultmotivo, 0);
		$cltarefamotivo ->at55_tarefa = $at40_sequencial;
		$cltarefamotivo ->at55_motivo = $at55_motivo;
		$cltarefamotivo ->incluir(null);
		if ($cltarefamotivo->erro_status == 0) {
		   $erro_msg = $cltarefamotivo->erro_msg;
		   echo "$erro_msg";
		   $sqlerro = true;
		}
	}
}

if($sqlerro=='false'){
	/*
	$sqlsit = "select * from tarefasituacao where at47_tarefa= $tarefa ";
	$resultsit = pg_query($sqlsit);
	$linhasit = pg_num_rows($resultsit);
	if($linhasit>0){
		db_fieldsmemory($resultsit, 0);
*/
		$cltarefasituacao ->at47_tarefa   = $at40_sequencial;
		$cltarefasituacao ->at47_situacao = 2;
		$cltarefasituacao ->incluir(null);
		if ($cltarefasituacao->erro_status == 0) {
		   $erro_msg = $cltarefasituacao->erro_msg;
		   echo "$erro_msg";
		   $sqlerro = true;
		}
}

if($sqlerro=='false'){
	$sqlitem = "select * from tarefaitem where at44_tarefa= $tarefa ";
	$resultitem = pg_query($sqlitem);
	$linhaitem = pg_num_rows($resultitem);
	if($linhaitem>0){
		db_fieldsmemory($resultitem, 0);
		$cltarefaitem ->at44_tarefa    = $at40_sequencial;
		$cltarefaitem ->at44_atenditem = $at44_atenditem;
		$cltarefaitem ->incluir($at40_sequencial,$at44_atenditem);
		if ($cltarefaitem->erro_status == 0) {
		   $erro_msg = $cltarefaitem->erro_msg;
		   echo "$erro_msg";
		   $sqlerro = true;
		}
	}
}
if($sqlerro=='false'){
	$sqlusu= "select * from tarefausu where at42_tarefa= $tarefa ";
	$resultusu = pg_query($sqlusu);
	$linhausu = pg_num_rows($resultusu);
	if($linhausu>0){
		db_fieldsmemory($resultusu, 0);
		$cltarefausu ->at42_tarefa    = $at40_sequencial;
		$cltarefausu ->at42_usuario   = $at42_usuario;
		$cltarefausu ->at42_perc      = $at42_perc;
		$cltarefausu ->incluir(null);
		if ($cltarefausu->erro_status == 0) {
		   $erro_msg = $cltarefausu->erro_msg;
		   echo "$erro_msg";
		   $sqlerro = true;
		}
	}
}

if($sqlerro=='false'){
	$sqlcli= "select * from tarefaclientes where at70_tarefa= $tarefa ";
	$resultcli = pg_query($sqlcli);
	$linhacli = pg_num_rows($resultcli);
	if($linhacli>0){
		db_fieldsmemory($resultcli, 0);
		$cltarefaclientes ->at70_tarefa    = $at40_sequencial;
		$cltarefaclientes ->at70_cliente  = $at70_cliente;
		$cltarefaclientes ->incluir(null);
		if ($cltarefaclientes->erro_status == 0) {
		   $erro_msg = $cltarefaclientes->erro_msg;
		   echo "$erro_msg";
		   $sqlerro = true;
		}
	}
}

//varios
if($sqlerro=='false'){
	$sqlenvol= "select * from tarefaenvol where at45_tarefa= $tarefa ";
	$resultenvol = pg_query($sqlenvol);
	$linhaenvol = pg_num_rows($resultenvol);
	if($linhaenvol>0){
		for($i = 0;$i < $linhaenvol; $i++){
			db_fieldsmemory($resultenvol, $i);
			$cltarefaenvol ->at45_tarefa    = $at40_sequencial;
			$cltarefaenvol ->at45_usuario   = $at45_usuario;
			$cltarefaenvol ->at45_perc      = $at45_perc;
			$cltarefaenvol ->incluir(null);
			if ($cltarefaenvol->erro_status == 0) {
			   $erro_msg = $cltarefaenvol->erro_msg;
			   echo "$erro_msg";
			   $sqlerro = true;
			}
		
		}
	}
}

if($sqlerro=='false'){
	$sqllanc= "select * from tarefa_lanc where at36_tarefa= $tarefa ";
	$resultlanc = pg_query($sqllanc);
	$linhalanc = pg_num_rows($resultlanc);
	if($linhalanc>0){
		db_fieldsmemory($resultlanc, 0);
		$cltarefa_lanc ->at36_tarefa  = $at40_sequencial;
		$cltarefa_lanc ->at36_usuario = $usuario;
		$cltarefa_lanc ->at36_data    = $data;
		$cltarefa_lanc ->at36_hora    = $hora;
		$cltarefa_lanc ->at36_ip      = $ip;
		$cltarefa_lanc ->at36_tipo    ='T';
		$cltarefa_lanc ->incluir(null);
		if ($cltarefa_lanc->erro_status == 0) {
		   $erro_msg = $cltarefa_lanc->erro_msg;
		   echo "$erro_msg";
		   $sqlerro = true;
		}
	}
}
if($sqlerro=='false'){
	$sqllog= "select * from tarefalog where at43_tarefa= $tarefa ";
	$resultlog = pg_query($sqllog);
	$linhalog = pg_num_rows($resultlog);
	if($linhalog>0){
		for($i = 0;$i < $linhalog; $i++){
			db_fieldsmemory($resultlog, $i);
			$cltarefalog ->at43_tarefa     = $at40_sequencial;
			$cltarefalog ->at43_descr      = $at43_descr ;
			$cltarefalog ->at43_obs        = $at43_obs;
			$cltarefalog ->at43_problema   = $at43_problema;
			$cltarefalog ->at43_avisar     = $at43_avisar;
			$cltarefalog ->at43_progresso  = $at43_progresso;
			$cltarefalog ->at43_usuario    = $at43_usuario;
			$cltarefalog ->at43_diaini     = $at43_diaini;
			$cltarefalog ->at43_diafim     = $at43_diafim;
			$cltarefalog ->at43_horainidia = $at43_horainidia;
			$cltarefalog ->at43_horafim    = $at43_horafim;
			$cltarefalog ->at43_tipomov    = $at43_tipomov;
			$cltarefalog ->incluir(null);
			if ($cltarefalog->erro_status == 0) {
			   $erro_msg = $cltarefalog->erro_msg;
			   echo "$erro_msg";
			   $sqlerro = true;
			}
			$log = $cltarefalog ->at43_sequencial;
			$sqllogsit= "select * from tarefalogsituacao where at48_tarefalog = $at43_sequencial";
			$resultlogsit = pg_query($sqllogsit);
			$linhalogsit = pg_num_rows($resultlogsit);
			if($linhalogsit>0){
				for($x = 0;$x < $linhalogsit; $x++){
					db_fieldsmemory($resultlogsit, $x);
					$cltarefalogsituacao ->at48_tarefalog = $log;
					$cltarefalogsituacao ->at48_situacao  =$at48_situacao;
					$cltarefalogsituacao ->incluir(null);
					if ($cltarefalogsituacao->erro_status == 0) {
					   $erro_msg = $cltarefalogsituacao->erro_msg;
					   echo "$erro_msg";
					   $sqlerro = true;
					}
				}
			}
		}
	}
}

if($sqlerro=='false'){
	$sqlproced= "select * from tarefaproced where at41_tarefa =$tarefa ";
	$resultproced = pg_query($sqlproced);
	$linhaproced = pg_num_rows($resultproced);
	if($linhaproced>0){
		db_fieldsmemory($resultproced, 0);
		$cltarefaproced ->at41_tarefa = $at40_sequencial;
		$cltarefaproced ->at41_proced = $at41_proced;
		$cltarefaproced ->incluir($at40_sequencial,$at41_proced);
		if ($cltarefaproced->erro_status == 0) {
		   $erro_msg = $cltarefaproced->erro_msg;
		   echo "$erro_msg";
		   $sqlerro = true;
		}
	}
}
if($sqlerro=='false'){
	$sqlanexo= "select * from tarefaanexos where at25_tarefa = $tarefa ";
	$resultanexo = pg_query($sqlanexo);
	$linhaanexo = pg_num_rows($resultanexo);
	if($linhaanexo>0){
		db_fieldsmemory($resultanexo, 0);
		$cltarefaanexos ->at25_tarefa = $at40_sequencial;
		$cltarefaanexos ->at25_anexo  = $at25_anexo;
		$cltarefaanexos ->at25_nomearq= $at25_obs;
		$cltarefaanexos ->at25_data   = $at25_data;
		$cltarefaanexos ->at25_hora   = $at25_hora;
		$cltarefaanexos ->at25_usuario= $at25_usuario;
		$cltarefaanexos ->at25_obs    = $at25_obs;
		$cltarefaanexos ->incluir(null);
		if ($cltarefaproced->erro_status == 0) {
		   $erro_msg = $cltarefaproced->erro_msg;
		   echo "$erro_msg";
		   $sqlerro = true;
		}
	}
}
 
//**************tenho que ver os outros arquivos que alterei....

// mostrar o frmtarefa ou char o ate1_tarefa005.php
// depois q cria a nova tarefa deve passar o codigo para as outras abas
if($sqlerro=='false'){
	//db_msgbox("tarefa $at40_sequencial incluida");
	echo "
	  <script>
	function js_db_libera(){
	    parent.document.formaba.tarefaclientes.disabled=false;
	    parent.document.formaba.tarefausu.disabled=false;
	    parent.document.formaba.tarefaobs.disabled=false;
	    parent.document.formaba.tarefaanexos.disabled=false;
		parent.document.formaba.tarefa.disabled=false;
   		top.corpo.iframe_tarefa.location.href='ate1_tarefa005.php?at40_sequencial=".@$at40_sequencial."&aut=0&db_opcao=2&abrefunc=f';
	    top.corpo.iframe_tarefausu.location.href='ate1_tarefausu001.php?at42_tarefa=".@$at40_sequencial."';
	    top.corpo.iframe_tarefaanexos.location.href='ate1_tarefaanexos001.php?at25_tarefa=".@$at40_sequencial."';
	    top.corpo.iframe_tarefaobs.location.href='ate1_tarefaobs001.php?at02_codatend=".@$at02_codatend."&at05_seq=".@$at05_seq."&at42_tarefa=".@$at40_sequencial."';
	    top.corpo.iframe_tarefaclientes.location.href='ate1_tarefaclientes001.php?at70_tarefa=".@$at40_sequencial."';
		top.corpo.iframe_tarefalog.location.href='ate1_tarefalogand002.php?at43_tarefa=".@$at40_sequencial."';
	}
	    js_db_libera();
	  </script>\n
	  ";
}else{
	db_msgbox("tarefa $at40_sequencial não incluida");
}
?>