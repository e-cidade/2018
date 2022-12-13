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

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("db_calendario.php");
include("dbforms/db_funcoes.php");
include("classes/db_calend_classe.php");
include("classes/db_clientes_classe.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_tarefacadsituacao_classe.php");
include("classes/db_db_proced_classe.php");


$clclientes          = new cl_clientes;
$cldb_usuarios       = new cl_db_usuarios;
$cltarefacadsituacao = new cl_tarefacadsituacao;
$clrotulo            = new rotulocampo; 
$cldb_proced         = new cl_db_proced;
$cale                = new db_calendario;
$cale->pagina_alvo = "con2_calendario002.php";
$cale->pagina_alvo_relatorio = "con2_calendario003.php";
$cale->monta_javascript("js_troca_cliente",""," location.href = 'con2_calendario001.php?cliente='+document.form1.cliente.value+'&tecnico='+document.form1.tecnico.value+'&situacao='+document.form1.situacao.value+'&at40_sequencial='+document.form1.at40_sequencial.value+'&tipo_pesquisa='+document.form1.tipo_pesquisa.value+'&at30_codigo='+document.form1.at30_codigo.value;");
$cale->monta_javascript("js_pesquisa_tarefa",""," js_OpenJanelaIframe('','db_iframe_tarefa','func_tarefa.php?funcao_js=parent.js_mostra_tarefa|at40_sequencial','Pesquisa',true) ");
$cale->monta_javascript("js_mostra_tarefa","tarefa"," document.form1.at40_sequencial.value = tarefa; db_iframe_tarefa.hide(); js_troca_cliente();");
$cale->monta_inicio_pagina(true);
if(isset($cliente)){
  global $cliente,$tecnico,$situacao,$at40_sequencial,$tipo_pesquisa,$at30_codigo;	
}
$result          = $clclientes->sql_record($clclientes->sql_query_file());
$result_tecnico  = $cldb_usuarios->sql_record($cldb_usuarios->sql_query_file(null,'*','nome'));
$result_situacao = $cltarefacadsituacao->sql_record($cltarefacadsituacao->sql_query_file());
$result_proced   = $cldb_proced->sql_record($cldb_proced->sql_query_file());
echo "<form name='form1' method='post' ><table><tr>
      <td><strong>Cliente:</strong></td><td>";
db_selectrecord("cliente",$result,true,2,"","","","0"," js_troca_cliente(); ");
db_selectrecord("tecnico",$result_tecnico,true,2,"","","","0"," js_troca_cliente(); ");
db_selectrecord("situacao",$result_situacao,true,2,"","","","0"," js_troca_cliente(); ");
echo "</td></tr>";
$clrotulo->label('at40_sequencial');
echo "<tr><td>";
db_ancora("$Lat40_sequencial"," js_pesquisa_tarefa() ",2);
echo "</td><td>";
db_input("at40_sequencial",10,$Iat40_sequencial,true,'text',2," onchange='js_troca_cliente()'");
$mata = array("1"=>"Tarefas não finalizadas","2"=>"Tarefas executadas","3"=>"Tarefas encerradas");
db_select("tipo_pesquisa",$mata,true,2," onchange='js_troca_cliente()'");
db_selectrecord("at30_codigo",$result_proced,true,2,"","","","0"," js_troca_cliente(); ");
echo "</td></tr>";
echo "</table></form>";
if(!isset($cliente)){
  global $cliente;	
//  $cliente = pg_result($result,0,0);
}
if(isset($cliente) && $at30_codigo == 9 ){
  $cale->sql_cruzamento = "  
  select DISTINCT at40_sequencial,at01_nomecli,at13_dia  as dl_datacalend,at40_diaini,at40_obs,at46_descr,nome::text,at45_perc,at40_progresso
  from tarefa 
     left join tarefaclientes    on at70_tarefa = at40_sequencial 
     left join clientes          on at01_codcli = at70_cliente 
     left join tarefasituacao    on at47_tarefa = at40_sequencial 
     left join tarefacadsituacao on at46_codigo = at47_situacao
     left join tarefaenvol       on at45_tarefa = at40_sequencial and at45_perc = 100
     left join db_usuarios       on id_usuario = at45_usuario 
     left join tarefaproced      on at41_tarefa = at40_sequencial
     left join tarefa_agenda     on at13_tarefa = at40_sequencial
     
  ";
  if(isset($cliente) ){
    if( $at40_sequencial > 0 ){
      $cale->sql_cruzamento .= " where at40_sequencial = $at40_sequencial";
    }else{
      if($tipo_pesquisa == 3){
        $cale->sql_cruzamento .= " where at47_situacao = 3 ";
      }else{
        $cale->sql_cruzamento .= " where at47_situacao <> 3 ";
      }
      if(isset($cliente) && $cliente > 0 ){
        $cale->sql_cruzamento .= " and at70_cliente = $cliente";
      }
      if(isset($tecnico) && $tecnico > 0 ){
        $cale->sql_cruzamento .= " and at45_usuario = $tecnico";
      }
      if(isset($situacao) && $situacao > 0 ){
        $cale->sql_cruzamento .= " and at46_codigo = $situacao";
      }
      if(isset($at30_codigo) && $at30_codigo > 0 ){
        $cale->sql_cruzamento .= " and at41_proced = $at30_codigo";
      }
    }
  }else{
  	$cale->sql_cruzamento .= " where at47_situacao <> 3 ";
  }
  $cale->sql_segundoacesso = $cale->sql_cruzamento;

}else if(isset($cliente) && $tipo_pesquisa == 2 ){
  $cale->sql_cruzamento = "  
  select     
             at43_usuario,
             nome,
             at43_diaini as dl_datacalend,
             sum( to_timestamp(at43_diafim||' '||at43_horafim, 'YYYY-MM-DD HH24:MI') -
                  to_timestamp(at43_diaini||' '||at43_horainidia, 'YYYY-MM-DD HH24:MI')) as dl_tempo
  from tarefalog
     inner join tarefa           on at43_tarefa = at40_sequencial
     inner join db_usuarios      on id_usuario = at43_usuario
     left join tarefasituacao    on at47_tarefa = at40_sequencial"; 
  
  if(isset($cliente) && $cliente > 0 ){
    $cale->sql_cruzamento .= "  
  
     left join tarefaclientes    on at70_tarefa = at40_sequencial 
     left join clientes          on at01_codcli = at70_cliente 
    ";
  } 
  $cale->sql_cruzamento .= "  

     left join tarefaproced      on at41_tarefa = at40_sequencial
  ";

  if(isset($cliente) ){
    if( $at40_sequencial > 0 ){
      $cale->sql_cruzamento .= " where at40_sequencial = $at40_sequencial";
    }else{
      $cale->sql_cruzamento .= " where 1 = 1 ";
      if(isset($cliente) && $cliente > 0 ){
        $cale->sql_cruzamento .= " and at70_cliente = $cliente";
      }
      if(isset($tecnico) && $tecnico > 0 ){
        $cale->sql_cruzamento .= " and at43_usuario = $tecnico ";
      }
      if(isset($situacao) && $situacao > 0 ){
        $cale->sql_cruzamento .= " and at46_codigo = $situacao";
      }
      if(isset($at30_codigo) && $at30_codigo > 0 ){
        $cale->sql_cruzamento .= " and at41_proced = $at30_codigo";
      }
    }
  }

  $cale->sql_cruzamento .= "  
    group by at43_usuario,nome,at43_diaini
    order by at43_usuario
  ";

  $cale->sql_segundoacesso = $cale->sql_cruzamento;

  $cale->pagina_alvo = "con2_calendario004.php";


}else{

  $cale->sql_cruzamento = "  
  select DISTINCT at40_sequencial,at01_nomecli,at40_diafim  as dl_datacalend,at40_diaini,at40_obs,at46_descr,nome::text,at45_perc,at40_progresso
  from tarefa 
     left join tarefaclientes    on at70_tarefa = at40_sequencial 
     left join clientes          on at01_codcli = at70_cliente 
     left join tarefasituacao    on at47_tarefa = at40_sequencial 
     left join tarefacadsituacao on at46_codigo = at47_situacao
     left join tarefaenvol      on at45_tarefa = at40_sequencial and at45_perc = 100
     left join db_usuarios      on id_usuario = at45_usuario 
     left join tarefaproced      on at41_tarefa = at40_sequencial
  ";
  if(isset($cliente) ){
    if( $at40_sequencial > 0 ){
      $cale->sql_cruzamento .= " where at40_sequencial = $at40_sequencial";
    }else{
      if($tipo_pesquisa == 3){
        $cale->sql_cruzamento .= " where at47_situacao = 3 ";
      }else{
        $cale->sql_cruzamento .= " where at47_situacao <> 3 ";
      }
      if(isset($cliente) && $cliente > 0 ){
      echo  $cale->sql_cruzamento .= " and at70_cliente = $cliente";
      }
      if(isset($tecnico) && $tecnico > 0 ){
        $cale->sql_cruzamento .= " and at45_usuario = $tecnico";
      }
      if(isset($situacao) && $situacao > 0 ){
        $cale->sql_cruzamento .= " and at46_codigo = $situacao";
      }
      if(isset($at30_codigo) && $at30_codigo > 0 ){
        $cale->sql_cruzamento .= " and at41_proced = $at30_codigo";
      }
    }
  }else{
  	$cale->sql_cruzamento .= " where at47_situacao <> 3 ";
  }
  $cale->sql_segundoacesso = $cale->sql_cruzamento;
}

$cale->monta_calendario(@$exercicio,@$metodo,@$data);
$cale->monta_fim_pagina(false);

?>