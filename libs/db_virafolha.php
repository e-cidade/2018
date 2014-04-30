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

set_time_limit(0);
require("../libs/db_stdlib.php");
require("../libs/db_conecta.php");
include("../libs/db_sessoes.php");
include("../libs/db_usuariosonline.php");
include("../classes/db_lotacao_classe.php");
include("../classes/db_padroes_classe.php");
include("../classes/db_cargo_classe.php");
include("../classes/db_cfpess_classe.php");
include("../classes/db_cedulas_classe.php");
include("../classes/db_pessoal_classe.php");
include("../classes/db_codmovsefip_classe.php");
include("../classes/db_movcasadassefip_classe.php");
include("../classes/db_afasta_classe.php");
include("../classes/db_cadferia_classe.php");
include("../classes/db_cheques_classe.php");
include("../classes/db_desconto_classe.php");
include("../classes/db_rubricas_classe.php");
include("../classes/db_pesdiver_classe.php");
include("../classes/db_bases_classe.php");
include("../classes/db_basesr_classe.php");
include("../classes/db_efetiv_classe.php");
include("../classes/db_historic_classe.php");
include("../classes/db_eventos_classe.php");
include("../classes/db_folhaemp_classe.php");
include("../classes/db_inssirf_classe.php");
include("../classes/db_landesc_classe.php");
include("../classes/db_lotativ_classe.php");
include("../classes/db_pensao_classe.php");
include("../classes/db_pontofa_classe.php");
include("../classes/db_pontofx_classe.php");
include("../classes/db_depend_classe.php");
include("../classes/db_progress_classe.php");
include("../classes/db_rescisao_classe.php");
include("../classes/db_reposic_classe.php");
include("../classes/db_rhpessoalmov_classe.php");
include("../classes/db_rhpesbanco_classe.php");
include("../classes/db_rhpespadrao_classe.php");
include("../classes/db_rhpesrescisao_classe.php");
include("../classes/db_gerfsal_classe.php");
include("../classes/db_gerffer_classe.php");
include("../classes/db_gerfs13_classe.php");
include("db_funcoes.php");
$cllotacao = new cl_lotacao;
$clpadroes = new cl_padroes;
$clcargo = new cl_cargo;
$clcfpess = new cl_cfpess;
$clcedulas = new cl_cedulas;
$clpessoal = new cl_pessoal;
$clcodmovsefip = new cl_codmovsefip;
$clmovcasadassefip = new cl_movcasadassefip;
$clafasta = new cl_afasta;
$clcadferia = new cl_cadferia;
$clcheques = new cl_cheques;
$cldesconto = new cl_desconto;
$clrubricas = new cl_rubricas;
$clpesdiver = new cl_pesdiver;
$clbases = new cl_bases;
$clbasesr = new cl_basesr;
$clefetiv = new cl_efetiv;
$clhistoric = new cl_historic;
$cleventos = new cl_eventos;
$clfolhaemp = new cl_folhaemp;
$clinssirf = new cl_inssirf;
$cllandesc = new cl_landesc;
$cllotativ = new cl_lotativ;
$clpensao = new cl_pensao;
$clpontofa = new cl_pontofa;
$clpontofx = new cl_pontofx;
$cldepend = new cl_depend;
$clprogress = new cl_progress;
$clrescisao = new cl_rescisao;
$clreposic = new cl_reposic;
$clrhpessoalmov = new cl_rhpessoalmov;
$clrhpesbanco = new cl_rhpesbanco;
$clrhpespadrao = new cl_rhpespadrao;
$clrhpesrescisao = new cl_rhpesrescisao;
$clgerfsal = new cl_gerfsal;
$clgerffer = new cl_gerffer;
$clgerfs13 = new cl_gerfs13;

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

db_inicio_transacao();

$periodoini1 = $dataii_dia."/".$dataii_mes."/".$dataii_ano;
$periodoini2 = $dataif_dia."/".$dataif_mes."/".$dataif_ano;
$periodofim1 = $datafi_dia."/".$datafi_mes."/".$datafi_ano;
$periodofim2 = $dataff_dia."/".$dataff_mes."/".$dataff_ano;

// FUNÇÃO PARA INCLUSÃO NAS TABELAS.
// $sql1    = SQL de onde vem os dados para virada de mês da folha.
// $table   = Variável em que foram instanciados os métodos da classe.
// $trigger = Nome da tabela para desabilitar as triggers.

// RETORNOS.
// 1 - Quando não existem dados para serem incluídos.
// 2 - Inclusões OK.
// Ou retorno de mensagem de erro na inclusão ou que fechamento já foi efetuado.
function db_incluir_sql($sql1,$table,$trigger){
  flush();
  echo "<script>parent.js_mostrardiv(true,'Aguarde, processando tabela <font color=\"red\">".$trigger."</font>');</script>";

  global $clrhpesbanco;
  global $clrhpespadrao;
  global $clrhpesrescisao;

  global $dataii_dia;
  global $dataii_mes;
  global $dataii_ano;

  global $dataif_dia;
  global $dataif_mes;
  global $dataif_ano;

  global $datafi_dia;
  global $datafi_mes;
  global $datafi_ano;

  global $dataff_dia;
  global $dataff_mes;
  global $dataff_ano;

  global $periodoini1;
  global $periodoini2;
  global $periodofim1;
  global $periodofim2;
  
  $mes = $datafi_mes;
  $ano = $datafi_ano;
  
  $datai = $datafi_ano."-".$datafi_mes."-".$datafi_dia;
  $dataf = $dataff_ano."-".$dataff_mes."-".$dataff_dia;

  // Testa se existem dados para serem incluídos.
  $result_tabela1 = $table->sql_record($sql1);
  $numrows_tabela1 = $table->numrows;
  if($numrows_tabela1 == 0){
  	return '1';
  }

  // Desativa triggers.
  $des_trigger = @pg_exec("UPDATE \"pg_class\" SET \"reltriggers\" = 0 WHERE \"relname\" = '".$trigger."'");
  // Testa se ocorreu erro ao desativar triggers.
  if($des_trigger == false){
  	return "Erro ao desativar triggers da tabela ".$trigger.".\\n\\nFechamento da folha cancelado.\\nPeríodo: ".$periodoini1." a ".$periodoini2.".\\n\\nContate o suporte.";
  }

  // FOR que busca nome e seta o valor dos campos.
  // Aqui, ele setará os campos para buscar no select de inclusão.
  // $campos  = Variável que receberá campos e / ou valores.
  // $virgula = Variavel que receberá uma ','.
  // $colunas = Quantidade de colunas que o select retornou.
  $campos  = "";
  $virgula = "";
  $colunas = pg_num_fields($result_tabela1);
  for($ii=0; $ii<$colunas; $ii++){
    $dcoluna = pg_fieldname($result_tabela1, $ii);   // Nome do campo corrente.
    // Testa se o campo é o anousu, se for, seta o ano do novo período da folha.
 	if(strpos($dcoluna,"anousu")){
 	  $dcoluna = $ano;
    // Testa se o campo é o mesusu, se for, seta o mês do novo período da folha.
  	}else if(strpos($dcoluna,"mesusu")){
      $dcoluna = $mes;
    // Testa para campos do CFPESS, ao incluir a data inicial e a data final.
  	}else if($dcoluna == "r11_datai"){
  	  $dcoluna = "'".$datai."'";
  	}else if($dcoluna == "r11_dataf"){
  	  $dcoluna = "'".$dataf."'";
  	// Testa se é a tabela rhpessoalmov para pegar a sequencia do seqpes.
  	}else if($dcoluna == "rh02_seqpes"){
  	  $dcoluna = "nextval('rhpessoalmov_rh02_seqpes_seq')";
  	// Testa se é a tabela rhpesbanco, rhpesrescisao ou rhpespadrao para pegar o novo seqpes.
  	}else if($dcoluna == "rh44_seqpes" || $dcoluna == "rh03_seqpes" || $dcoluna == "rh05_seqpes"){
  	  $dcoluna = "a.rh02_seqpes";
  	}
    $campos.= $virgula.$dcoluna;
    $virgula = ",";
  }

  // SQL de inserção.
  if($trigger == "rhpesbanco"){
    $sql_insert = "insert into ".$trigger." ".str_replace("rhpesbanco.*",$campos,$sql1);
  }else if($trigger == "rhpespadrao"){
    $sql_insert = "insert into ".$trigger." ".str_replace("rhpespadrao.*",$campos,$sql1);
  }else if($trigger == "rhpesrescisao"){
    $sql_insert = "insert into ".$trigger." ".str_replace("rhpesrescisao.*",$campos,$sql1);
  }else{
    $sql_insert = "insert into ".$trigger." ".str_replace("*",$campos,$sql1);
  }
  
  // Executa SQL.
  $res_insert = @pg_exec($sql_insert);
  // Testa se deu erro ao inserir.
  if($res_insert == false){
  	return "Erro ao incluir na tabela ".$trigger.".\\n\\nFechamento da folha cancelado.\\nPeríodo: ".$periodoini1." a ".$periodoini2.".";
  }
  // Ativa triggers.
  $ati_trigger = @pg_exec("UPDATE pg_class SET reltriggers = (SELECT count(*) FROM pg_trigger where pg_class.oid = tgrelid) WHERE relname = '".$trigger."'");
  // Testa se ocorreu erro ao ativar triggers.
  if($ati_trigger == false){
  	return "Erro ao ativar triggers da tabela ".$trigger.".\\n\\nFechamento da folha cancelado.\\nPeríodo: ".$periodoini1." a ".$periodoini2.".\\n\\nContate o suporte.";
  }

  // Retorna que inclusões foram efetuadas com sucesso.
  return '2';
}

// VARIÁVEIS FIXAS:
// $sql1 = SQL de onde vem os dados para virada de mês da folha.
// $dataii_ano = Ano corrente da folha.
// $dataii_mes = Mês corrente da folha.
// $datafi_ano = Ano novo da folha.
// $datafi_mes = Mês novo da folha.
// $retorno    = Retorna: 1, se não existem dados para incluir.
//                        2, se inclusões foram efetuadas com sucesso.
//                        Mensagens informando erros.
// $sqlerro    = True, caso tenha ocorrido erro em alguma inclusão.

$sqlerro = false;

$sql1 = $cllotacao->sql_query_file($dataii_ano,$dataii_mes,null);
$retorno = db_incluir_sql($sql1,$cllotacao,"lotacao");
if($retorno != '1' && $retorno != '2'){
  $sqlerro = true;
}

if($sqlerro == false){
  $sql1 = $clpadroes->sql_query_file ($dataii_ano,$dataii_mes,null,null);
  $retorno = db_incluir_sql($sql1,$clpadroes,"padroes");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  $sql1 = $clcargo->sql_query_file ($dataii_ano,$dataii_mes,null);
  $retorno = db_incluir_sql($sql1,$clcargo,"cargo");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  $sql1 = $clcfpess->sql_query_file ($dataii_ano,$dataii_mes);
  $retorno = db_incluir_sql($sql1,$clcfpess,"cfpess");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  $sql1 = $clcedulas->sql_query_file (null,"*","","r05_anousu = ".$dataii_ano." and r05_mesusu = ".$dataii_mes);
  $retorno = db_incluir_sql($sql1,$clcedulas,"cedulas");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  $sql1 = $clpessoal->sql_query_file ($dataii_ano,$dataii_mes,null);
  $retorno = db_incluir_sql($sql1,$clpessoal,"pessoal");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  $sql1 = $clcodmovsefip->sql_query_file ($dataii_ano,$dataii_mes,null);
  $retorno = db_incluir_sql($sql1,$clcodmovsefip,"codmovsefip");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  $sql1 = $clmovcasadassefip->sql_query_file ($dataii_ano,$dataii_mes,null,null);
  $retorno = db_incluir_sql($sql1,$clmovcasadassefip,"movcasadassefip");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  $sql1 = $clafasta->sql_query_file (null,"*","","r45_anousu = ".$dataii_ano." and r45_mesusu = ".$dataii_mes);
  $retorno = db_incluir_sql($sql1,$clafasta,"afasta");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  $sql1 = $clcadferia->sql_query_file (null,"*","","r30_anousu = ".$dataii_ano." and r30_mesusu = ".$dataii_mes);
  $retorno = db_incluir_sql($sql1,$clcadferia,"cadferia");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  $sql1 = $clcheques->sql_query_file (null,"*","","r12_anousu = ".$dataii_ano." and r12_mesusu = ".$dataii_mes);
  $retorno = db_incluir_sql($sql1,$clcheques,"cheques");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  $sql1 = $cldesconto->sql_query_file ($dataii_ano,$dataii_mes,null);
  $retorno = db_incluir_sql($sql1,$cldesconto,"desconto");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  $sql1 = $clrubricas->sql_query_file ($dataii_ano,$dataii_mes,null);
  $retorno = db_incluir_sql($sql1,$clrubricas,"rubricas");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  $sql1 = $clpesdiver->sql_query_file ($dataii_ano,$dataii_mes,null);
  $retorno = db_incluir_sql($sql1,$clpesdiver,"pesdiver");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  $sql1 = $clbases->sql_query_file ($dataii_ano,$dataii_mes,null);
  $retorno = db_incluir_sql($sql1,$clbases,"bases");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  $sql1 = $clbasesr->sql_query_file ($dataii_ano,$dataii_mes,null,null);
  $retorno = db_incluir_sql($sql1,$clbasesr,"basesr");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  $sql1 = $clefetiv->sql_query_file ($dataii_ano,$dataii_mes,null,null);
  $retorno = db_incluir_sql($sql1,$clefetiv,"efetiv");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  $sql1 = $clhistoric->sql_query_file ($dataii_ano,$dataii_mes,null);
  $retorno = db_incluir_sql($sql1,$clhistoric,"historic");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  $sql1 = $cleventos->sql_query_file ($dataii_ano,$dataii_mes,null,null);
  $retorno = db_incluir_sql($sql1,$cleventos,"eventos");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  $sql1 = $clfolhaemp->sql_query_file (null,"*","","r42_anousu = ".$dataii_ano." and r42_mesusu = ".$dataii_mes);
  $retorno = db_incluir_sql($sql1,$clfolhaemp,"folhaemp");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  $sql1 = $clinssirf->sql_query_file ($dataii_ano,$dataii_mes,null);
  $retorno = db_incluir_sql($sql1,$clinssirf,"inssirf");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  $sql1 = $cllandesc->sql_query_file (null,"*","","r28_anousu = ".$dataii_ano." and r28_mesusu = ".$dataii_mes);
  $retorno = db_incluir_sql($sql1,$cllandesc,"landesc");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  $sql1 = $cllotativ->sql_query_file ($dataii_ano,$dataii_mes,null);
  $retorno = db_incluir_sql($sql1,$cllotativ,"lotativ");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  $sql1 = $clpensao->sql_query_file ($dataii_ano,$dataii_mes,null,null);
  $retorno = db_incluir_sql($sql1,$clpensao,"pensao");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  $sql1 = $clpontofa->sql_query_file ($dataii_ano,$dataii_mes,null,null);
  $retorno = db_incluir_sql($sql1,$clpontofa,"pontofa");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  $sql1 = $clpontofx->sql_query_file ($dataii_ano,$dataii_mes,null,null);
  $retorno = db_incluir_sql($sql1,$clpontofx,"pontofx");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  $sql1 = $cldepend->sql_query_file ($dataii_ano,$dataii_mes,null,null);
  $retorno = db_incluir_sql($sql1,$cldepend,"depend");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  $sql1 = $clprogress->sql_query_file ($dataii_ano,$dataii_mes,null,null,null);
  $retorno = db_incluir_sql($sql1,$clprogress,"progress");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  $sql1 = $clrescisao->sql_query_file ($dataii_ano,$dataii_mes,null,null,null,null);
  $retorno = db_incluir_sql($sql1,$clrescisao,"rescisao");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  $sql1 = $clreposic->sql_query_file ($dataii_ano,$dataii_mes,null,null);
  $retorno = db_incluir_sql($sql1,$clreposic,"reposic");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  $sql1 = $clrhpessoalmov->sql_query_file (null,"*","","rh02_anousu = ".$dataii_ano." and rh02_mesusu = ".$dataii_mes);
  $retorno = db_incluir_sql($sql1,$clrhpessoalmov,"rhpessoalmov");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  $sql1 = $clrhpesbanco->sql_query_retorno(null,"rhpesbanco.*",""," rhpessoalmov.rh02_anousu=".$dataii_ano." and rhpessoalmov.rh02_mesusu=".$dataii_mes,$datafi_ano,$datafi_mes);
  $retorno = db_incluir_sql($sql1,$clrhpesbanco,"rhpesbanco");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  $sql1 = $clrhpespadrao->sql_query_retorno(null,"rhpespadrao.*",""," rhpessoalmov.rh02_anousu=".$dataii_ano." and rhpessoalmov.rh02_mesusu=".$dataii_mes,$datafi_ano,$datafi_mes);
  $retorno = db_incluir_sql($sql1,$clrhpespadrao,"rhpespadrao");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  $sql1 = $clrhpesrescisao->sql_query_retorno(null,"rhpesrescisao.*",""," rhpessoalmov.rh02_anousu=".$dataii_ano." and rhpessoalmov.rh02_mesusu=".$dataii_mes,$datafi_ano,$datafi_mes);
  $retorno = db_incluir_sql($sql1,$clrhpesrescisao,"rhpesrescisao");
  if($retorno != '1' && $retorno != '2'){
    $sqlerro = true;
  }
}

if($sqlerro == false){
  echo "<script>parent.js_mostrardiv(true,'Atualizando dados da tabela <font color=\"red\">pessoal</font>');</script>";
  $update = "update pessoal set r01_rubric = '', r01_arredn = 0 where r01_rubric = 'R929' and r01_anousu = ".$datafi_ano." and r01_mesusu = ".$datafi_mes;
  $execut = @pg_exec($update);
  if($execut == false){
  	$retorno = "Erro ao atualizar tabela pessoal.";
  	$sqlerro = true;
  }else{
    $result_gerf = $clgerfsal->sql_record($clgerfsal->sql_query_file(null,null,null,null,null,"r14_regist,r14_rubric,r14_valor","r14_regist,r14_rubric","r14_anousu = ".$dataii_ano." and r14_mesusu = ".$dataii_mes." and (r14_rubric = 'R926' or r14_rubric = 'R928')"));
    $arr_valores = Array();
    $arr_rubrica = Array();
    for($i=0; $i < $clgerfsal->numrows; $i++){
      db_fieldsmemory($result_gerf, $i);
  	  if(!isset($arr_valores[$r14_regist])){
  	    $arr_valores[$r14_regist] = 0;
  	  }
  	  $arr_valores[$r14_regist]+= $r14_valor;
  	  $arr_rubrica[$r14_regist] = $r14_rubric;
    }
    reset($arr_valores);
    for($i=0; $i<count($arr_valores); $i++){
  	  $regis = key($arr_valores);
  	  $valor = $arr_valores[$regis];
  	  $rubri = $arr_rubrica[$regis];
  	  $rmais = substr($rubri, 3, 1);
  	  $rmais ++;
  	  $update = "update pessoal set r01_rubric = 'R92".$rmais."', r01_arredn = ".$valor." where r01_regist = ".$regis." and r01_anousu = ".$datafi_ano." and r01_mesusu = ".$datafi_mes;
  	  $execut = @pg_exec($update);
  	  if($execut == false){
  	    $retorno = "Erro ao atualizar tabela pessoal.";
  	    $sqlerro = true;
  	    break;
      }
      next($arr_valores);
    }
  }
}

$result_cfpe = $clcfpess->sql_record($clcfpess->sql_query_file($dataii_ano,$dataii_mes));
if($clcfpess->numrows > 0){
  db_fieldsmemory($result_cfpe, 0);
}

if($sqlerro == false){
  $result_gerf = $clgerffer->sql_record($clgerffer->sql_query_file(null,null,null,null,null,"r31_regist,r31_rubric,r31_valor","r31_regist,r31_rubric","r31_anousu = ".$dataii_ano." and r31_mesusu = ".$dataii_mes." and r31_rubric in ('R926', 'R928', 'R983', 'R933')"));
  for($i=0; $i < $clgerffer->numrows; $i++){
    db_fieldsmemory($result_gerf, $i);
    if($r31_rubric == "R983"){
    }else{
      $rmais = substr($r31_rubric, 3, 1);
      $rmais ++;
      $campoval = "r01_rubric = 'R92".$rmais."', r01_arredn = ".$r31_valor;
      if($r31_rubric == 'R933'){
        if($r11_ultger != ""){
          $r11_ultger = "'".$r11_ultger."'";
        }else{
          $r11_ultger = "null";
        }
        $campoval = "r01_adia13 = ".$r31_valor.", r01_dadi13 = ".$r11_ultger;
      }
      $update = "update pessoal set ".$campoval." where r01_regist = ".$regis." and r01_anousu = ".$datafi_ano." and r01_mesusu = ".$datafi_mes;
      $execut = @pg_exec($update);
      if($execut == false){
    	$retorno = "Erro ao atualizar tabela pessoal.";
        $sqlerro = true;
        break;
      }
    }
  }
}

if($sqlerro == false){
  $result_gerf = $clgerfs13->sql_record($clgerfs13->sql_query_file(null,null,null,null,"r35_regist,r35_rubric,r35_valor","r35_regist,r35_rubric","r35_anousu = ".$dataii_ano." and r35_mesusu = ".$dataii_mes." and r35_rubric = 'R933'"));
  for($i=0; $i < $clgerfs13->numrows; $i++){
    db_fieldsmemory($result_gerf, $i);
    if($r11_ultger != ""){
      $r11_ultger = "'".$r11_ultger."'";
    }else{
      $r11_ultger = "null";
    }
    $update = "update pessoal set r01_adia13 = ".$r14_valor.", r01_dadi13 = ".$r11_ultger." where r01_regist = ".$regis." and r01_anousu = ".$datafi_ano." and r01_mesusu = ".$datafi_mes;
    $execut = @pg_exec($update);
    if($execut == false){
      $retorno = "Erro ao atualizar tabela pessoal.";
      $sqlerro = true;
      break;
    }
  }
}

if($sqlerro == false){
  flush();
  echo "<script>parent.js_mostrardiv(true,'Atualizando dados da tabela <font color=\"red\">pensao</font>');</script>";
  $update = "update pensao set r52_valor = 0, r52_val13 = 0, r52_valcom = 0 where r52_anousu = ".$datafi_ano." and r52_mesusu = ".$datafi_mes;
  $execut = @pg_exec($update);
  if($execut == false){
  	$retorno = "Erro ao atualizar tabela pensao.";
  	$sqlerro = true;
  }
}

flush();
if($sqlerro == true){
  echo "<script>parent.js_mostrardiv(true,'Erro no processamento');</script>";
  db_msgbox($retorno);
}else{
  echo "<script>parent.js_mostrardiv(true,'Processamento concluído com sucesso');</script>";
  db_msgbox("Fechamento da folha efetuado com sucesso.\\n\\nPeríodo anterior: ".$periodoini1." a ".$periodoini2.".\\nPeríodo atual: ".$periodofim1." a ".$periodofim2."");
}
echo "<script>parent.js_mostrardiv(false,'');</script>";
echo "<script>parent.location.href = '../pes1_virafolha001.php'</script>";
db_fim_transacao(true);
?>