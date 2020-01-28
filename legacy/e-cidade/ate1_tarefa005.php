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


define("TAREFA",true);

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("libs/smtp.class.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_tarefa_lanc_classe.php"));
include(modification("classes/db_tarefa_lancprorrog_classe.php"));
include(modification("classes/db_tarefalog_classe.php"));
include(modification("classes/db_tarefa_autcanc_classe.php"));
include(modification("classes/db_tarefa_aut_classe.php"));
include(modification("classes/db_tarefaparam_classe.php"));
include(modification("classes/db_db_usuarios_classe.php"));
include(modification("classes/db_tarefa_classe.php"));
include(modification("classes/db_tarefamodulo_classe.php"));
include(modification("classes/db_tarefaproced_classe.php"));
include(modification("classes/db_tarefasituacao_classe.php"));
include(modification("classes/db_tarefausu_classe.php"));
include(modification("classes/db_tarefaenvol_classe.php"));
include(modification("classes/db_tarefamotivo_classe.php"));
include(modification("classes/db_tarefaclientes_classe.php"));
include(modification("classes/db_tarefa_agenda_classe.php"));
include(modification("classes/db_tarefasyscadproced_classe.php"));
include(modification("classes/db_tarefaprojetoativcli_classe.php"));



$cltarefa         	= new cl_tarefa;
$cltarefamodulo   	= new cl_tarefamodulo;
$cltarefaproced   	= new cl_tarefaproced;
$cltarefasituacao 	= new cl_tarefasituacao;
$cltarefaenvol    	= new cl_tarefaenvol;
$cltarefausu      	= new cl_tarefausu;
$cltarefamotivo   	= new cl_tarefamotivo;
$cltarefaclientes 	= new cl_tarefaclientes;
$cltarefa_agenda  	= new cl_tarefa_agenda;
$cltarefaparam    	= new cl_tarefaparam;
$cltarefa_aut     	= new cl_tarefa_aut;
$cltarefa_autcanc 	= new cl_tarefa_autcanc;
$cltarefalog      	= new cl_tarefalog;
$cltarefa_lanc    	= new cl_tarefa_lanc;
$cltarefa_lancprorrog 	= new cl_tarefa_lancprorrog;
$cldb_usuarios 	  	= new cl_db_usuarios;
$cl_tarefasyscadproced = new cl_tarefasyscadproced;
$cltarefaprojetoativcli = new cl_tarefaprojetoativcli;
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
//db_msgbox('005');
if(@$tipotar=='T' and @$chavepesquisa!=""){
  // chama o ate1_tarefausu010.php para inclui a tarefa automatica 
  
  echo "
  <script>
  function js_db_libera(){
    parent.document.formaba.tarefa.disabled=false;
    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_tarefa.location.href='ate1_tarefa010.php?tarefa=".$chavepesquisa."';
  }  
  js_db_libera();
  </script>\n
  ";
  
  exit;
}
//echo "...at40_sequencial = $at40_sequencial...";
if(@$at40_sequencial!=""){
  $chavepesquisa = $at40_sequencial;
}

$db_botao = false;

if(@$trocamodulo != 't' ){
  $db_opcao = 22;
}else{
  $db_opcao = 2;	
  $db_botao = true;
}

// ############################### ALTERAR #################################
if(isset($alterar)||isset($autorizar)) {
  //die("alterar");
  if (((int) @$at40_previsao == 0 and isset($autorizar))) {
    $erro_msg = "Campo previsao deve ser preenchido";
    $sqlerro = true;
  } else if(@$at41_proced == 0 || $codproced == 0) {
  	$erro_msg = "Campo Procedimento deve ser informado";
    $sqlerro = true;
  } else {
    
    $sqlerro = false;
    
    $resulttarefa = $cltarefa->sql_record($cltarefa->sql_query_file($at40_sequencial,"at40_autorizada, at40_diaini as at58_diaini, at40_diafim as at58_diafim, at40_previsao as at58_previsao, at40_tipoprevisao as at58_tipoprevisao, at40_horainidia as at58_horainidia, at40_horafim as at58_horafim"));
    
    $result = $cltarefa->sql_record($cltarefa->sql_query_envol(null,"*","at40_sequencial,at45_usuario","at40_progresso < 100 and at40_sequencial<>$at40_sequencial and at45_usuario=$at40_responsavel"));
    if($cltarefa->numrows > 0) {
      $cltarefa->at40_sequencial = $at40_sequencial;
      if (@$at40_diaini_ano != "") {
        $cltarefa->at40_diaini = $at40_diaini_ano . "-" . db_formatar($at40_diaini_mes,'s','0',2,'e',0) . "-" . db_formatar($at40_diaini_dia,'s','0',2,'e',0);
      }
      if (@$at40_diafim_ano != "") {
        $cltarefa->at40_diafim = $at40_diafim_ano . "-" . db_formatar($at40_diafim_mes,'s','0',2,'e',0) . "-" . db_formatar($at40_diafim_dia,'s','0',2,'e',0);
      }
      
      if(strlen(@$at40_horainidia) == 2) {
        $at40_horainidia .= ":00";
      }
      
      if(strlen(@$at40_horafim) == 2) {
        $at40_horafim .= ":00";
      }
      
      $cltarefa->at40_horainidia = @$at40_horainidia;
      $cltarefa->at40_horafim    = @$at40_horafim;
      
      $erro_horario = testa_horarios($result, $cltarefa);
      
    }
    
    db_inicio_transacao();
    
    if($sqlerro == false) {
      if(@$at40_previsao=="") {
        $cltarefa->at40_previsao = 0;
        $at40_previsao           = 0;
      }
      if($aut==1) {
        $cltarefa->at40_autorizada = "true";
      } else {
        db_fieldsmemory($resulttarefa, 0);
        $cltarefa->at40_autorizada = $at40_autorizada;
      }
      $cltarefa->alterar($at40_sequencial);
      if($cltarefa->erro_status == 0) {
        $sqlerro = true;
        $erro_msg = $cltarefa->erro_msg;
      }
      $erro_msg = $cltarefa->erro_msg;
    }
    
    if($sqlerro == true) { 
      $erro_msg = $cltarefa->erro_msg;
    } 
    
    if ($sqlerro == false) {
      
      if($prorrogar==1) {
        
        $cltarefa_lanc->at36_data    = date("Y", db_getsession("DB_datausu"))."-".
        date("m", db_getsession("DB_datausu"))."-".
        date("d", db_getsession("DB_datausu"));
        $cltarefa_lanc->at36_hora    = db_hora();
        $cltarefa_lanc->at36_ip      = db_getsession("DB_ip");
        $cltarefa_lanc->at36_tarefa  = $cltarefa->at40_sequencial;
        $cltarefa_lanc->at36_usuario = db_getsession("DB_id_usuario");
        $cltarefa_lanc->at36_tipo    = "P";
        $cltarefa_lanc->incluir(null);
        if($cltarefa_lanc->erro_status==0) {
          $sqlerro = true;
          $erro_msg = $cltarefa_lanc->erro_msg;
        }
        
        db_fieldsmemory($resulttarefa,0);
        
        $cltarefa_lancprorrog->at58_tarefalanc 	= $cltarefa_lanc->at36_sequencia;
        $cltarefa_lancprorrog->at58_tarefa		= $cltarefa->at40_sequencial;
        $cltarefa_lancprorrog->at58_diaini		= $at58_diaini;
        $cltarefa_lancprorrog->at58_diafim		= $at58_diafim;
        $cltarefa_lancprorrog->at58_previsao	= $at58_previsao;
        $cltarefa_lancprorrog->at58_tipoprevisao	= $at58_tipoprevisao;
        $cltarefa_lancprorrog->at58_horainidia	= $at58_horainidia;
        $cltarefa_lancprorrog->at58_horafim		= $at58_horafim;
        
        $cltarefa_lancprorrog->incluir(null);
        if($cltarefa_lancprorrog->erro_status==0) {
          $sqlerro = true;
          $erro_msg = $cltarefa_lancprorrog->erro_msg;
        }
        
      } elseif($aut==0) {
        // Inclui lançamento de quem esta alterando a tarefa 
        $cltarefa_lanc->at36_data    = date("Y", db_getsession("DB_datausu"))."-".
        date("m", db_getsession("DB_datausu"))."-".
        date("d", db_getsession("DB_datausu"));
        $cltarefa_lanc->at36_hora    = db_hora();
        $cltarefa_lanc->at36_ip      = db_getsession("DB_ip");
        $cltarefa_lanc->at36_tarefa  = $cltarefa->at40_sequencial;
        $cltarefa_lanc->at36_usuario = db_getsession("DB_id_usuario");
        $cltarefa_lanc->at36_tipo    = "A";
        $cltarefa_lanc->incluir(null);
        ///////////////////////////////////////////////////////////////////////////////////	  
        if($cltarefa_lanc->erro_status==0) {
          $sqlerro = true;
          $erro_msg = $cltarefa_lanc->erro_msg;
        }
      }
      
      
      
      // tem que pegar o motivo e area
      // pega a area.....
      $sqlteste = "select codarea from db_syscadproced where codproced = $codproced";
      $resultteste = db_query($sqlteste) or die($sqlteste);
      $linhasteste = pg_num_rows($resultteste);
      if ($linhasteste>0){
        db_fieldsmemory($resultteste, 0);
      }
      
      // pegar motivo   $at34_tarefacadmotivo
          
      $at41_proced = "";
      
      if ($codproced == 278 or $codproced == 328) { // agenda de visitas ou reuniao
        $at41_proced = 9;
      } else {
        // buscar o proced no tarefamotivoarea
        $sqlmotarea = "	select at33_proced as at41_proced
        from tarefamotivoarea 
        where at33_tarefacadmotivo = $at54_sequencial 
        and at33_atendcadarea = $codarea";
        $resultmotarea = db_query($sqlmotarea) or die($sqlmotarea);
        $linhasmotarea = pg_num_rows($resultmotarea);
        if ($linhasmotarea>0){
          db_fieldsmemory($resultmotarea, 0);
        }
      }
      
      if ($at41_proced == "") {
        $sqlerro = true;
        $erro_msg = "Sem procedimento especificado!";
      } else {
        $cltarefaproced->at41_proced = $at41_proced;
        $cltarefaproced->at41_tarefa = $at40_sequencial;
        $cltarefaproced->alterar($at40_sequencial, null);
        if ($cltarefaproced->erro_status == 0) {
          $sqlerro = true;
          $erro_msg = $cltarefaproced->erro_msg;
        }
      }
    }
    
    if ($sqlerro == false) {
      
      if($aut==1) {
        
        $cltarefa_aut->at39_data      = date("Y", db_getsession("DB_datausu"))."-".
        date("m", db_getsession("DB_datausu"))."-".
        date("d", db_getsession("DB_datausu"));
        $cltarefa_aut->at39_hora      = db_hora();
        $cltarefa_aut->at39_ip        = db_getsession("DB_ip");
        $cltarefa_aut->at39_tarefa    = $cltarefa->at40_sequencial;
        $cltarefa_aut->at39_usuario   = db_getsession("DB_id_usuario");
        $cltarefa_aut->at39_cancelada = "false";
        $cltarefa_aut->incluir(null);
        
        $rs_autorizador  = $cldb_usuarios->sql_record($cldb_usuarios->sql_query($cltarefa_aut->at39_usuario,"nome as nome_aut","id_usuario"));
        db_fieldsmemory($rs_autorizador, 0);
        
        if($cltarefa_aut->erro_status==0) {
          $sqlerro = true;
          $erro_msg = $cltarefa_aut->erro_msg;
        }	  	  
        
        
        
        
        // Envio de e-mail para envolvidos
        $rs_tarefa = $cltarefa->sql_record($cltarefa->sql_query_envol($cltarefausu->at42_tarefa,"at45_usuario,
        at40_responsavel,
        at40_descr,
        at40_diaini,
        at40_diafim,
        at40_previsao,
        at40_tipoprevisao,
        at40_prioridade,
        at40_obs,
        db_usuarios2.nome as nome_criado,
        at36_data,
        at36_hora",
        "at40_sequencial,at45_usuario","at45_tarefa=$cltarefa->at40_sequencial"));
        
        if($cltarefa->numrows > 0) {
          
          for($i=0; $i < $cltarefa->numrows; $i++) {
            db_fieldsmemory($rs_tarefa,$i);
            $rs_usuario = $cldb_usuarios->sql_record($cldb_usuarios->sql_query($at45_usuario,"email,nome","id_usuario"));
            if($cldb_usuarios->numrows > 0) {
              db_fieldsmemory($rs_usuario,0);
              if($at40_prioridade == 1) {
                $prioridade = "Baixa";
              }
              else if($at40_prioridade == 2) {
                $prioridade = "Media";
              }
              else if($at40_prioridade == 3) {
                $prioridade = "Alta";
              }
              $rs_resp  = $cldb_usuarios->sql_record($cldb_usuarios->sql_query($at40_responsavel,"nome as nome_resp","id_usuario"));
              if($cldb_usuarios->numrows > 0) {	
                db_fieldsmemory($rs_resp,0);
                
                $mensagem  = $nome . ":<br><br>";
                $mensagem .= "Uma nova tarefa foi criada e autorizada para você:<br>";
                $mensagem .= "Criado por:          " . $nome_criado . " - em: $at36_data - $at36_hora<br>";
                $mensagem .= "Responsavel:         " . $at40_responsavel . " - " . $nome_resp . "<br>";
                $mensagem .= "Resumo:              " . $at40_descr       . "<br>";
                $mensagem .= "Observações:         " . $at40_obs         . "<br>";
                $mensagem .= "Data inicial prevista: " . db_formatar($at40_diaini,"d") . " - ";
                $mensagem .= "Data final prevista: " . db_formatar($at40_diafim,"d") . "<br>";
                $mensagem .= "Previsto em        : " . $at40_previsao    . "/" . $at40_tipoprevisao . "<br>";
                $mensagem .= "Prioridade         : " . $prioridade       . "<br><br>";
                $mensagem .= "Autorizada por     : " . $cltarefa_aut->at39_usuario . " - $nome_aut - Data: " . $cltarefa_aut->at39_data . " - Hora: " . $cltarefa_aut->at39_hora . "<br>";
                
                $envio = $cltarefa->enviar_email($email,"Nova tarefa: ".$cltarefa->at40_sequencial . " - " . $at40_descr,$mensagem);
                if($envio == false) {
                  db_msgbox("Erro ao enviar e-mail para " . $email);
                }
                
              }
            }
          } 
        }
        // Fim do Envio de e-mail		  
        
        
      }
      
    }
    
    if ($sqlerro == false) {
      $result = $cltarefausu->sql_record($cltarefausu->sql_query(null, "at42_sequencial", null, "at42_tarefa=$at40_sequencial"));
      if ($cltarefausu->numrows > 0) {
        db_fieldsmemory($result,0);
        $cltarefausu->excluir($at42_sequencial);
        if ($cltarefausu->erro_status == 0) {
          $erro_msg = $cltarefausu->erro_msg;
          $sqlerro = true;
        }
      }
      if ($sqlerro == false) {
        $cltarefausu->at42_tarefa = $at40_sequencial;
        $cltarefausu->at42_usuario = $at40_responsavel;
        $cltarefausu->at42_perc    = 100;
        $cltarefausu->incluir(null);
        if ($cltarefausu->erro_status == 0) {
          $erro_msg = $cltarefausu->erro_msg;
          $sqlerro = true;
        }
      }
    }
    
    if ($sqlerro == false) {
      $result = $cltarefaenvol->sql_record($cltarefaenvol->sql_query(null, "at45_sequencial", null, "at45_tarefa=$at40_sequencial and at45_usuario=$at40_usuant"));
      if ($cltarefaenvol->numrows > 0) {
        db_fieldsmemory($result, 0);
        $cltarefaenvol->at45_usuario = $at40_responsavel;
        $cltarefaenvol->at45_perc    = 100;
        $cltarefaenvol->at45_sequencial = $at45_sequencial;
        $cltarefaenvol->alterar($at45_sequencial);
        if ($cltarefaenvol->erro_status == 0) {
          $erro_msg = $cltarefaenvol->erro_msg;
          $sqlerro = true;
        }
      }
    }
    if($sqlerro == false) {
      // Verifica se trocou motivo da tarefa
      $resultmotivo = $cltarefamotivo->sql_record($cltarefamotivo->sql_query_file(null, "*", null, "at55_tarefa = $at40_sequencial"));
      if($cltarefamotivo->numrows>0) {
        db_fieldsmemory($resultmotivo, 0);
        // Se trocou remove autorizacao da tarefa e passa Situacao pra Analise
        if($at54_sequencial<>$at55_motivo) {
          // Desautoriza tarefa
          if($aut<>1) {
            $cltarefa->at40_autorizada = "false";
            $cltarefa->alterar($at40_sequencial);
            if ($cltarefa->erro_status == 0) {
              $erro_msg = $cltarefa->erro_msg;
              $sqlerro = true;
            }
            // Passa tarefa para 2-Análise 
            $at47_situacao = 2;
          }
          
        }
      }
      
      if($sqlerro == false) {
        $cltarefamotivo->at55_tarefa = $at40_sequencial;
        $cltarefamotivo->at55_motivo = $at54_sequencial;
        $cltarefamotivo->alterar($at40_sequencial);
        if ($cltarefamotivo->erro_status == 0) {
          $erro_msg = $cltarefamotivo->erro_msg;
          $sqlerro = true;
        }
      }
      
    }
    
    if ($sqlerro == false) {
      $result = $cltarefasituacao->sql_record($cltarefasituacao->sql_query(null, "at47_sequencial", null, "at47_tarefa=$at40_sequencial"));
      if ($cltarefasituacao->numrows > 0) {
        db_fieldsmemory($result, 0);
        $cltarefasituacao->at47_situacao = $at47_situacao;
        $cltarefasituacao->at47_sequencial = $at47_sequencial;
        $cltarefasituacao->alterar($at47_sequencial);
        if ($cltarefasituacao->erro_status == 0) {
          $erro_msg = $cltarefasituacao->erro_msg;
          $sqlerro = true;
        }
      }
    }
    
    
    if($sqlerro == false) {
      //if (@$at49_modulo != "") {
        $result = $cltarefamodulo->sql_record($cltarefamodulo->sql_query_file(null, "at49_sequencial", null, "at49_tarefa=$at40_sequencial"));
        if ($cltarefamodulo->numrows > 0) {
          db_fieldsmemory($result, 0);
          $cltarefamodulo->at49_sequencial = $at49_sequencial;
          $cltarefamodulo->at49_modulo     = $codmod;
          $cltarefamodulo->alterar($at49_sequencial);
          if ($cltarefamodulo->erro_status == 0) {
            $erro_msg = $cltarefamodulo->erro_msg;
            $sqlerro = true;
          }
        }
      //}
    }
    
    if($sqlerro==false) {
      $sqlproced = "select * from tarefasyscadproced where at37_tarefa = $at40_sequencial";
      
      $resultproced = db_query($sqlproced);
      $linhasproced = pg_num_rows($resultproced);
      if ($linhasproced>0){
        db_fieldsmemory($resultproced, 0);
        //$cl_tarefasyscadproced->at37_sequencial=$at37_sequencial;
        
        $cl_tarefasyscadproced->excluir($at37_sequencial);
      }
      //$cl_tarefasyscadproced->at37_sequencial=$at37_sequencial;
      //$cl_tarefasyscadproced->excluir($at37_sequencial);
      if($codproced>0){
        
        $cl_tarefasyscadproced->at37_tarefa       = $at40_sequencial;
        $cl_tarefasyscadproced->at37_syscadproced = $codproced;
        $cl_tarefasyscadproced->incluir(null);
        
        if($cl_tarefasyscadproced->erro_status == 0) {
          $sqlerro = true;
          $erro_msg = $cl_tarefasyscadproced->erro_msg;
        }
        
      }
      
    }
    
    if($sqlerro == false) {
      $cltarefa_agenda->excluir(null,"at13_tarefa=$at40_sequencial");
      $sqlerro = $cltarefa_agenda->gera_agenda($cltarefaparam,$cltarefa,$erro_msg);
    }
    
    if($sqlerro == false) {

      if ( $at64_sequencial > 0 ){

       $result = $cltarefaprojetoativcli->sql_record($cltarefaprojetoativcli->sql_query_file(null, "at69_sequencial", null, "at69_seqtarefa=$at40_sequencial"));
       if ($cltarefaprojetoativcli->numrows > 0) {
          db_fieldsmemory($result,0);
          $cltarefaprojetoativcli->at69_sequencial = $at69_sequencial;
          $cltarefaprojetoativcli->at69_seqprojeto = $at64_sequencial;
          $cltarefaprojetoativcli->at69_seqtarefa  = $at40_sequencial;
          $cltarefaprojetoativcli->alterar($at69_sequencial);
          if ($cltarefaprojetoativcli->erro_status == 0) {
            $erro_msg = $cltarefaprojetoativcli->erro_msg;
            $sqlerro = true;
          }
       }else{
          $cltarefaprojetoativcli->at69_sequencial = 0;
          $cltarefaprojetoativcli->at69_seqprojeto = $at64_sequencial;
          $cltarefaprojetoativcli->at69_seqtarefa  = $at40_sequencial;
          $cltarefaprojetoativcli->incluir($cltarefaprojetoativcli->at69_sequencial);
          if ($cltarefaprojetoativcli->erro_status == 0) {
            $erro_msg = $cltarefaprojetoativcli->erro_msg;
            $sqlerro = true;
          }
       }

      }else{
       $result = $cltarefaprojetoativcli->sql_record($cltarefaprojetoativcli->sql_query_file(null, "at69_sequencial", null, "at69_seqtarefa=$at40_sequencial"));
       if ($cltarefaprojetoativcli->numrows > 0) {
          db_fieldsmemory($result,0);
          $cltarefaprojetoativcli->excluir($at69_sequencial);
          if ($cltarefaprojetoativcli->erro_status == 0) {
            $erro_msg = $cltarefaprojetoativcli->erro_msg;
            $sqlerro = true;
          }
       }
      }
    }


    db_fim_transacao($sqlerro);
    
  }
  
  $db_opcao = 2;
  $db_botao = true;
  // ############################### CANCELAR #################################
} else if(isset($cancelar)) {
  //die("cancelar");
  $sqlerro = false;
  
  db_inicio_transacao();
  
  if($sqlerro==false) {
    $rs_registros = $cltarefalog->sql_record($cltarefalog->sql_query_file(null,"at43_tarefa","at43_tarefa","at43_tarefa=$at40_sequencial"));
    if($cltarefalog->numrows>0) {
      $sqlerro  = true;
      $erro_msg = "Autorização de tarefa com registros de andamento";
    } 
    else {
      $rs_autoriza  = $cltarefa_aut->sql_record($cltarefa_aut->sql_query_file(null,"at39_sequencia","at39_sequencia","at39_tarefa=$at40_sequencial"));
      if($cltarefa_aut->numrows>0) {
        db_fieldsmemory($rs_autoriza,0);
        $rs_autcanc = $cltarefa_autcanc->sql_record($cltarefa_autcanc->sql_query_file($at39_sequencia));
        if($cltarefa_autcanc->numrows==0) {
          $cltarefa_autcanc->at38_tarefaaut = $at39_sequencia;
          $cltarefa_autcanc->at38_data      = date("Y", db_getsession("DB_datausu"))."-".
          date("m", db_getsession("DB_datausu"))."-".
          date("d", db_getsession("DB_datausu"));
          $cltarefa_autcanc->at38_hora      = db_hora();
          $cltarefa_autcanc->at38_ip        = db_getsession("DB_ip");
          $cltarefa_autcanc->at38_usuario   = db_getsession("DB_id_usuario");
          $cltarefa_autcanc->incluir($at39_sequencia);
          
          if($cltarefa_autcanc->erro_status!=0) {
            $cltarefa_aut->at39_sequencia = $at39_sequencia;
            $cltarefa_aut->at39_cancelada = "true";
            $cltarefa_aut->alterar($at39_sequencia);
            
            $cltarefa->at40_autorizada = "false";			
            $cltarefa->alterar($at40_sequencial);
            if($cltarefa->erro_status==0) {
              $sqlerro  = true;
              $erro_msg = $cltarefa->erro_msg;
            }
          }
          else {
            $sqlerro  = true;
            $erro_msg = $cltarefa_autcanc->erro_msg;
          }
        }
        else {
          $sqlerro  = true;
          $erro_msg = "Autorização já cancelada";
        }
      }
      
      if($sqlerro==false) {
        if($cltarefa_aut->erro_status==0) {
          $sqlerro  = true;
          $erro_msg = $cltarefa_aut->erro_msg;
        }
      }	  	  
    }
  }
  
  db_fim_transacao($sqlerro);
  
  $db_opcao = 2;
  $db_botao = true;
  // ############################### CHAVEPESQUISA #################################
} else if (isset ($chavepesquisa)) { 
  
  $db_opcao = 2;
  $db_botao = true;
  $result = $cltarefa->sql_record($cltarefa->sql_query($chavepesquisa));
  if ($cltarefa->numrows > 0) {
    db_fieldsmemory($result, 0);
  }
  $result = $cltarefamodulo->sql_record($cltarefamodulo->sql_query(null, "*", null, "at49_tarefa=$chavepesquisa"));
  if ($cltarefamodulo->numrows > 0) {
    db_fieldsmemory($result, 0);
    
  }
  $result = $cltarefaproced->sql_record($cltarefaproced->sql_query(null, null, "*", null, "at41_tarefa=$chavepesquisa"));
  if ($cltarefaproced->numrows > 0) {
    db_fieldsmemory($result, 0);
  }
  $result = $cltarefasituacao->sql_record($cltarefasituacao->sql_query(null, "*", null, "at47_tarefa=$chavepesquisa"));
  if ($cltarefasituacao->numrows > 0) {
    db_fieldsmemory($result, 0);
  }
  
  $result = $cltarefa->sql_record($cltarefa->sql_query_envol(null,"*","at40_sequencial,at45_usuario","at40_progresso < 100 and at40_sequencial<>$at40_sequencial and at45_usuario=$at40_responsavel"));
  if($cltarefa->numrows > 0) {
    $cltarefa->at40_sequencial = $chavepesquisa;
    $cltarefa->at40_diaini     = $at40_diaini_ano . "-" . db_formatar($at40_diaini_mes,'s','0',2,'e',0) . "-" . db_formatar($at40_diaini_dia,'s','0',2,'e',0);
    $cltarefa->at40_diafim     = $at40_diafim_ano . "-" . db_formatar($at40_diafim_mes,'s','0',2,'e',0) . "-" . db_formatar($at40_diafim_dia,'s','0',2,'e',0);
    
    if(strlen($at40_horainidia) == 2) {
      $at40_horainidia .= ":00";
    }
    
    if(strlen($at40_horafim) == 2) {
      $at40_horafim .= ":00";
    }
    
    $cltarefa->at40_horainidia = $at40_horainidia;
    $cltarefa->at40_horafim    = $at40_horafim;
    
    $erro_horario = testa_horarios($result, $cltarefa);
  }
  
  $result = $cltarefamotivo->sql_record($cltarefamotivo->sql_query(null,"at55_motivo","at55_tarefa","at55_tarefa=$at40_sequencial"));
  if ($cltarefamotivo->numrows > 0) {
    db_fieldsmemory($result, 0);
    $at54_sequencial = $at55_motivo;
  }
  
  $result = $cltarefaclientes->sql_record($cltarefaclientes->sql_query(null,"*","at70_tarefa","at70_tarefa=$at40_sequencial"));
  if ($cltarefaclientes->numrows > 0) {
    db_fieldsmemory($result, 0);
  }
  
  $result = $cltarefaprojetoativcli->sql_record($cltarefaprojetoativcli->sql_query(null,"at64_sequencial","","at69_seqtarefa=$at40_sequencial"));
  if ($cltarefaprojetoativcli->numrows > 0) {
    db_fieldsmemory($result, 0);
  }
  //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
  
  
  
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
<tr> 
<td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
<center>
<?
if (isset ($chavepesquisa)) {
  
  $result_tarefa = $cltarefa->sql_record($cltarefa->sql_query($chavepesquisa,"at40_autorizada",null,null));
  if ($cltarefa->numrows == 0) {
    $at40_autorizada = 'f';
  } else {
    db_fieldsmemory($result_tarefa, 0);
  }
  
} else {
  $at40_autorizada = 'f';
}

include(modification("forms/db_frmtarefa.php"));

?>
</center>
</td>
</tr>
</table>
</body>
</html>
<?

if (isset($alterar)||isset($autorizar)||isset($cancelar)) {
  
  if ($sqlerro == true) {
    db_msgbox($erro_msg);
    if ($cltarefa->erro_campo != "") {
      echo "<script> document.form1.".$cltarefa->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cltarefa->erro_campo.".focus();</script>";
    };
  } else {
    if(isset($alterar)) {
      db_msgbox($erro_msg);
    }
    if(isset($autorizar)) {
      db_msgbox("Tarefa ".$at40_sequencial." autorizada");	
    }
    if(isset($cancelar)) {
      db_msgbox("Autorização ".$at39_sequencia." da Tarefa ".$at40_sequencial." cancelada");	
    }
    echo "<script>(window.CurrentWindow || parent.CurrentWindow).corpo.iframe_tarefausu.location.href='ate1_tarefausu001.php?at42_tarefa=".@$at40_sequencial."'</script>";
  }
}
if (isset ($chavepesquisa)) {
  $rs_registros = $cltarefalog->sql_record($cltarefalog->sql_query_file(null,"at43_tarefa","at43_tarefa","at43_tarefa=$at40_sequencial"));
  if($cltarefalog->numrows>0 and 1==2) {
    $liberado = "\nparent.document.formaba.tarefausu.disabled=true;";
  }else {
    /*$liberado = "	parent.document.formaba.tarefausu.disabled=false;
    parent.document.formaba.tarefaobs.disabled=false;
    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_tarefausu.location.href='ate1_tarefausu001.php?at42_tarefa=".@$at40_sequencial."';
    parent.document.formaba.tarefaanexos.disabled=false;
    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_tarefaanexos.location.href='ate1_tarefaanexos001.php?at25_tarefa=".@$at40_sequencial."';
    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_tarefaobs.location.href='ate1_tarefaobs001.php?at02_codatend=$at02_codatend&at05_seq=$at05_seq&at42_tarefa=".@$at40_sequencial."';
    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_tarefalog.location.href='ate1_tarefalogand002.php?at43_tarefa=".@$at40_sequencial."&at43_usuario=".db_getsession("DB_id_usuario")."';
    ";		*/		
  }
  //die($liberado);	
  echo "
  <script>
  function js_db_libera(){
    parent.document.formaba.tarefaclientes.disabled=false;
    parent.document.formaba.tarefausu.disabled=false;
    parent.document.formaba.tarefaobs.disabled=false;
    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_tarefausu.location.href='ate1_tarefausu001.php?at42_tarefa=".@$at40_sequencial."';
    parent.document.formaba.tarefaanexos.disabled=false;
    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_tarefaanexos.location.href='ate1_tarefaanexos001.php?at25_tarefa=".@$at40_sequencial."';
    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_tarefaobs.location.href='ate1_tarefaobs001.php?at02_codatend=".@$at02_codatend."&at05_seq=".@$at05_seq."&at42_tarefa=".@$at40_sequencial."';
    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_tarefaclientes.location.href='ate1_tarefaclientes001.php?at70_tarefa=".@$at40_sequencial."';
    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_tarefalog.location.href='ate1_tarefalogand002.php?at43_tarefa=".@$at40_sequencial."';
    ";
    if (isset ($liberaaba)) {
      if(isset($erro_horario)&&$erro_horario==false) {
        if($cltarefalog->numrows==0) {
          echo "  parent.mo_camada('tarefaobs');";
        }
      }
    }
  echo "}\n
  js_db_libera();
  </script>\n
  ";
}
if (($db_opcao == 22 || $db_opcao == 33) && ($trocamodulo!='t')&&(@$abrefunc!='f')) {
  //db_msgbox('chamou o click do pesquisar dbopcao = '.$db_opcao.'abrefunc = '.$abrefunc);
  echo "<script>document.form1.pesquisar.click();</script>";
}

function testa_horarios($result, $cltarefa) {
  $retorno    = false;
  $NumRows    = $cltarefa->numrows;
  $NumFields  = pg_numfields($result);
  $db_diaini  = "";
  $db_diafim  = "";
  $db_horaini = "";
  $db_horafim = "";
  
  for($i= 0; $i < $NumRows; $i++) {
    for($j = 0; $j < $NumFields; $j++) {
      if(pg_fieldname($result, $j) == "at40_diaini") {
        $db_diaini = db_formatar(pg_result($result, $i, $j),'d');
      }
      if(pg_fieldname($result, $j) == "at40_diafim") {
        $db_diafim = db_formatar(pg_result($result, $i, $j),'d');
      }
      if(pg_fieldname($result, $j) == "at40_horainidia") {
        $db_horaini = pg_result($result, $i, $j);
        if(strlen(trim($db_horaini)) == 2) {
          $db_horaini  = substr(pg_result($result, $i, $j),0,2);
          $db_horaini .= ":00";
        }
      }
      if(pg_fieldname($result, $j) == "at40_horafim") {
        $db_horafim = pg_result($result, $i, $j);
        if(strlen(trim($db_horafim)) == 2) {
          $db_horafim  = substr(pg_result($result, $i, $j),0,2);
          $db_horafim .= ":00";
        }
      }
      
      if(strlen($db_diaini)  > 0 &&
      strlen($db_diafim)  > 0 &&
      strlen($db_horaini) > 0 &&
      strlen($db_horafim) > 0) {
        if($db_diaini <= $cltarefa->at40_diafim ||	// Ex.: 01/02/2006 <= 03/02/2006 or 
        $db_diafim >= $cltarefa->at40_diaini) {  //      02/02/2006 >= 02/02/2006
          if($cltarefa->at40_horainidia <= $db_horafim &&
          $cltarefa->at40_horafim    >= $db_horaini) {
            $retorno = true;
            break;
          }
        }
      }
    }
  }
  
  return($retorno);
}

?>