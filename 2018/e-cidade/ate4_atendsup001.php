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

define("TAREFA", true);
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_tarefaparam_classe.php"));
include(modification("classes/db_atendimentomod_classe.php"));
include(modification("classes/db_db_usuclientes_classe.php"));
include(modification("classes/db_atendimento_classe.php"));
include(modification("classes/db_atenditem_classe.php"));
include(modification("classes/db_atenditemmod_classe.php"));
//include(modification("classes/db_atenditemproc_classe.php"));
include(modification("classes/db_atenditemusu_classe.php"));
include(modification("classes/db_tecnico_classe.php"));
include(modification("classes/db_tarefa_classe.php"));
include(modification("classes/db_tarefa_agenda_classe.php"));
include(modification("classes/db_tarefaproced_classe.php"));
include(modification("classes/db_db_proced_classe.php"));
include(modification("classes/db_tarefausu_classe.php"));
include(modification("classes/db_tarefaenvol_classe.php"));
include(modification("classes/db_tarefa_lanc_classe.php"));
include(modification("classes/db_tarefaclientes_classe.php"));
include(modification("classes/db_tarefamodulo_classe.php"));
include(modification("classes/db_tarefamotivo_classe.php"));
include(modification("classes/db_tarefasituacao_classe.php"));
include(modification("classes/db_tarefaitem_classe.php"));
include(modification("classes/db_tarefalog_classe.php"));
include(modification("classes/db_tarefalogsituacao_classe.php"));
include(modification("classes/db_db_syscadproced_classe.php"));
include(modification("classes/db_atenditemsyscadproced_classe.php"));
include(modification("classes/db_tarefasyscadproced_classe.php"));
include(modification("classes/db_tarefacadmotivo_classe.php"));
include(modification("classes/db_atenditemmotivo_classe.php"));
include(modification("classes/db_atenditemtarefa_classe.php"));
include(modification("classes/db_atenditemmenu_classe.php"));
include(modification("classes/db_atendimentoversao_classe.php"));
include(modification("classes/db_db_versao_classe.php"));
include(modification("dbforms/db_funcoes.php"));

include(modification("classes/db_atendtecnicoocupado_classe.php"));
include(modification("classes/db_atendtipoausencia_classe.php"));
include(modification("classes/db_tarefaprojetoativcli_classe.php"));

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$clatendimento = new cl_atendimento;
$cltecnico = new cl_tecnico;
$cltarefa = new cl_tarefa;
$cltarefa_agenda = new cl_tarefa_agenda;
$cltarefaparam = new cl_tarefaparam;
$cltarefamodulo = new cl_tarefamodulo;
$cltarefamotivo = new cl_tarefamotivo;
$cltarefasituacao = new cl_tarefasituacao;
$cltarefaitem = new cl_tarefaitem;
$cltarefausu = new cl_tarefausu;
$cltarefaenvol = new cl_tarefaenvol;
$cltarefaproced = new cl_tarefaproced;
$cldb_proced = new cl_db_proced;
$cltarefa_lanc = new cl_tarefa_lanc;
$cltarefaclientes = new cl_tarefaclientes;
$clatenditem = new cl_atenditem;
$clatenditemmod = new cl_atenditemmod;
$clatenditemusu = new cl_atenditemusu;
$clatendimentomod = new cl_atendimentomod;
$cltarefalog = new cl_tarefalog;
$cltarefalogsituacao = new cl_tarefalogsituacao;
$cl_db_syscadproced = new cl_db_syscadproced;
$cl_atenditemsyscadproced = new cl_atenditemsyscadproced;
$cl_tarefasyscadproced = new cl_tarefasyscadproced;
$cl_tarefacadmotivo = new cl_tarefacadmotivo;
$cl_atenditemmotivo = new cl_atenditemmotivo;
$cl_atenditemtarefa = new cl_atenditemtarefa;
$clatenditemmenu = new cl_atenditemmenu;
$clatendimentoversao = new cl_atendimentoversao;
$cltarefaprojetoativcli = new cl_tarefaprojetoativcli;

$cldb_versao = new cl_db_versao;
$db_opcao = 11;

$clatendtipoausencia = new cl_atendtipoausencia;
$clatendtecnicoocupado = new cl_atendtecnicoocupado;

//$hora_inicial = date("H:i");
if (isset ($opcao) && $opcao != "") {

  if ($opcao == "incluir") {
  	
    $db_opcao = 11;
    if (!isset($at05_feito)) {
    	
      $at05_feito = "\n";
      $at05_feito .= "INSTITUIÇÃO: \n";
      $at05_feito .= "DEPARTAMENTO: \n";
      $at05_feito .= "\n\n\n";
      $at05_feito .= "O QUE ESTÁ ERRADO? \n";
      $at05_feito .= "QUAL O FUNCIONAMENTO CORRETO? \n";
      $at05_feito .= "COMO CHEGO ATÉ O PROBLEMA? \n";
    } 
  }
  if ($opcao == "alterar") {
    $db_opcao = 22;
    //db_msgbox("$at05_seq");
  }
  if ($opcao == "excluir") {
    $db_opcao = 3;
  }

}

if( $db_opcao != 11 || isset($chavepesquisa) ){
  
  // testar chave pesquisa se existe no atendtecnicoocupado, se existir nao chama a classe.

  $result_atend = $clatendtecnicoocupado->sql_record($clatendtecnicoocupado->sql_query(null,'at72_id',null," at72_id_usuario = ".db_getsession("DB_id_usuario")." and at72_codatend = $chavepesquisa "));
  if( $clatendtecnicoocupado->numrows == 0 ){
    $clatendtecnicoocupado->atendimento_tecnico_registra(5,$clatendtecnicoocupado,$clatendtipoausencia,$chavepesquisa,0);
  }
}


$db_botao = true;
// ############################## INCLUIR ################################

if (isset ($incluir) && $incluir != "") {
  $hora_final = date("H:i");
  db_inicio_transacao();
  $sqlerro = false;
  
  if ($sqlerro == false) {
    
    if ($at05_perc == 100) {
      // se percentual for = 100 e execução estiver preenchido
      if ($at05_feito != "") {
        $clatenditem->at05_codatend   = $codatend;
        $clatenditem->at05_solicitado = addslashes($at05_solicitado);
        $clatenditem->at05_feito      = addslashes($at05_feito);
        $clatenditem->at05_tipo       = 1;
        $clatenditem->at05_horaini    = $hora_inicial;
        $clatenditem->at05_horafim    = $hora_final;
        $clatenditem->at05_perc       = $at05_perc;
        $clatenditem->at05_prioridade = $at05_prioridade;
        if (isset ($at05_data_dia) && $at05_data_dia != "") {
          $clatenditem->at05_data = $at05_data_ano."-".$at05_data_mes."-".$at05_data_dia;
        }
        $clatenditem->incluir(null, $codatend);
        $erro_msg = $clatenditem->erro_msg;
        if ($clatenditem->erro_status == "0") {
          $sqlerro = true;
        }
      } else { // se percentual for = 100 e execução não estiver preenchido
        
        $erro_msg = "Percentual 100% é obrigatório o pheenchimento do campo executado!";
        $sqlerro = true;
      }
    } else { // se percentual for != 100
    
    
      $restempo = $clatenditem->sql_record($clatenditem->sql_query_file(null,null,"at05_horafim as hora_inicial"," at05_seq desc "," at05_codatend = $codatend "));
      if( $clatenditem->numrows > 0 ){
        db_fieldsmemory($restempo,0,"hora_inicial");
      }
    
      $clatenditem->at05_codatend = $codatend;
      $clatenditem->at05_solicitado = addslashes($at05_solicitado);
      $clatenditem->at05_feito      = addslashes($at05_feito);
      $clatenditem->at05_tipo = 1;
      $clatenditem->at05_horaini = $hora_inicial;
      $clatenditem->at05_horafim = $hora_final;
      $clatenditem->at05_perc    = $at05_perc;
      $clatenditem->at05_prioridade = $at05_prioridade;
      if (isset ($at05_data_dia) && $at05_data_dia != "") {
        $clatenditem->at05_data = $at05_data_ano."-".$at05_data_mes."-".$at05_data_dia;
      }
      $clatenditem->incluir(null, $codatend);
      $erro_msg = $clatenditem->erro_msg;
      if ($clatenditem->erro_status == "0") {
        $sqlerro = true;
      }
    }
    
    if ($at40_sequencial != "" and $item_menu == "") {
			$sql = "	select at23_itemenu as item_menu 
								from tarefa
								inner join tarefaitem			on tarefaitem.at44_tarefa = tarefa.at40_sequencial
								inner join atenditemmenu	on atenditemmenu.at23_atenditem = tarefaitem.at44_atenditem
								where at40_sequencial = $at40_sequencial";
			$resultitem = db_query($sql) or die($sql);
			if (pg_num_rows($resultitem) > 0) {
				db_fieldsmemory($resultitem,0);
			}
		}

    if (($motivo == 13 or $motivo == 1) or $item_menu != "") {
			
			// grava atenditemmenu
			if( $sqlerro == false ){
				$clatenditemmenu->at23_sequencial = 0;
				$clatenditemmenu->at23_atenditem  = $clatenditem->at05_seq;
				$clatenditemmenu->at23_codatend   = $codatend;
				$clatenditemmenu->at23_itemenu    = $item_menu;
				$clatenditemmenu->incluir(null);
				$erro_msg = $clatenditemmenu->erro_msg;
				if ($clatenditemmenu->erro_status == "0") {
					$sqlerro = true;
				}
				
			}

		}
    
  }
  //
  
  //
  
  if (isset ($modulo) && $modulo > 0 and $sqlerro == false) {
    
    if ($sqlerro == false) {
      $clatenditemmod->at22_atenditem = $clatenditem->at05_seq;
      $clatenditemmod->at22_codatend = $codatend;
      $clatenditemmod->at22_modulo = $modulo;
      $clatenditemmod->incluir(null);
      if ($clatenditemmod->erro_status == "0") {
        $sqlerro = true;
        $erro_msg = $clatenditemmod->erro_msg;
      }
      
      /*if($sqlerro==false) {
        $clatendimentomod->at08_atend=$codatend; 			
        $clatendimentomod->at08_modulo=$modulo;
        $clatendimentomod->incluir();
        if ($clatendimentomod->erro_status=="0"){
          $sqlerro=true;
          $erro_msg=$clatendimentomod->erro_msg;
        }
      }*/
    }
  }
  if ($sqlerro == false) {
    if (isset ($codproced) && $codproced > 0) {
      
      $cl_atenditemsyscadproced->at29_atenditem = $clatenditem->at05_seq;
      $cl_atenditemsyscadproced->at29_syscadproced = $codproced;
      $cl_atenditemsyscadproced->incluir(null);
      if ($cl_atenditemsyscadproced->erro_status == "0") {
        $sqlerro = true;
        $erro_msg = $cl_atenditemsyscadproced->erro_msg;
      }
      
    } else {
      $sqlerro = true;
      $erro_msg = "Procedimento não informado.";
    }
  }
  
  if ($sqlerro == false) {
    if ($motivo != 0) {
      
      $cl_atenditemmotivo->at34_atenditem = $clatenditem->at05_seq;
      $cl_atenditemmotivo->at34_tarefacadmotivo = $motivo;
      $cl_atenditemmotivo->incluir(null);
      if ($cl_atenditemmotivo->erro_status == "0") {
        $sqlerro = true;
        $erro_msg = $cl_atenditemmotivo->erro_msg;
      }
    } else {
      
      $sqlerro = true;
      $erro_msg = "Campo motivo obrigatório.";

    }
  }
  
  if (isset ($usuorigem) && $usuorigem != "" and $sqlerro == false) {
    reset($usuorigem);
    for ($w = 0; $w < count($usuorigem); $w ++) {
      if ($usuorigem[$w] != "") {
        
        if ($sqlerro == false) {
          $clatenditemusu->at21_atenditem = $clatenditem->at05_seq;
          $clatenditemusu->at21_codatend = $codatend;
          $clatenditemusu->at21_usuario = $usuorigem[$w];
          $clatenditemusu->incluir(null);
          if ($clatenditemusu->erro_status == "0") {
            $sqlerro = true;
            $erro_msg = $clatenditemusu->erro_msg;
            //break;
          }
        }
      }
      next($usuorigem);
    }
  }
	
  if ($sqlerro == false) {
    if (isset ($at40_sequencial) && ($at40_sequencial > 0)) {
      $cl_atenditemtarefa->at18_atenditem = $clatenditem->at05_seq;
      $cl_atenditemtarefa->at18_tarefa = $at40_sequencial;
      $cl_atenditemtarefa->incluir(null);
      if ($cl_atenditemtarefa->erro_status == "0") {
        $sqlerro = true;
        $erro_msg = $cl_atenditemtarefa->erro_msg;
      }
      // grava se cliente for adicionado a tarefa
      
      if ($sqlerro == false) {
        
        $restarcli = $cltarefaclientes->sql_record($cltarefaclientes->sql_query_file(null,'at70_tarefa',null," at70_tarefa = $at40_sequencial and at70_cliente = $at01_codcli" ));
        if( $cltarefaclientes->numrows == 0 ){
          $cltarefaclientes->at70_sequencial = 0;
          $cltarefaclientes->at70_tarefa     = $at40_sequencial;			     
          $cltarefaclientes->at70_cliente    = $at01_codcli;			     
          $cltarefaclientes->incluir(null);
          if ($cltarefaclientes->erro_status == "0") {
            $sqlerro = true;
            $erro_msg = $cltarefaclientes->erro_msg;
          }
        }
      }
      
    }
    if ($sqlerro == false) {
      
      $restarcli = $clatendimentoversao->sql_record($clatendimentoversao->sql_query_file(null,'at67_sequencial',null," at67_codatend = $codatend " ));
      if( $clatendimentoversao->numrows == 0 ){
        $clatendimentoversao->at67_sequencial = 0;
        $clatendimentoversao->at67_codatend   = $codatend;			     
        $clatendimentoversao->at67_codver     = $at67_codver;			     
        $clatendimentoversao->incluir(null);
        if ($clatendimentoversao->erro_status == "0") {
          $sqlerro = true;
          $erro_msg = $clatendimentoversao->erro_msg;
        }
      }
    }
    
  }
  
  //************  gravar os procedimentos da db_syscadproced *************
  
  // **************** não grava mais na atenditemproced *****************
  
  //**********************************************************
  
  //**********************************************************
  db_fim_transacao($sqlerro);
  if ($sqlerro) {
    db_msgbox($erro_msg);
  }
  if ($sqlerro == false) {
    $certo = true;
    //db_msgbox("incluido");
    //echo "<script>location.href='ate4_atendsup001.php';</script>";
  }
}

// ############################## ALTERAR ################################

if (isset ($alterar) && $alterar != "") {
  
  db_inicio_transacao();
  $sqlerro = false;
  if ($sqlerro == false) {
    /*$rs_atenditem = $clatenditem->sql_record($clatenditem->sql_query(null,"at05_seq","at05_seq","at05_codatend=$codatend and at05_seq = $at05_seq"));
    
    sql_query ( $at05_seq=null,$campos="*",$ordem=null,$dbwhere="") 
      $sql = $clatenditem->sql_query(null,"at05_seq",null,"at05_codatend=$codatend and at05_seq = $at05_seq");
      echo "<br>". $sql;
      */
      //if($clatenditem->numrows > 0) {
        //	echo"atenditem > 0";
        //db_fieldsmemory($rs_atenditem,0);
        $clatenditem->at05_seq        = $at05_seq;
        $clatenditem->at05_codatend   = $codatend;
        $clatenditem->at05_solicitado = addslashes($at05_solicitado);
        $clatenditem->at05_feito      = addslashes($at05_feito);
        $clatenditem->at05_perc       = $at05_perc;
        $clatenditem->at05_tipo       = 1;
        if (isset ($at05_data_dia) && $at05_data_dia != "") {
          $clatenditem->at05_data = $at05_data_ano."-".$at05_data_mes."-".$at05_data_dia;
        }
        $clatenditem->alterar($at05_seq);
        $erro_msg = $clatenditem->erro_msg;
        if ($clatenditem->erro_status == 0) {
          $sqlerro = true;
        }
        
        
        // grava atenditemmenu
        if( $sqlerro == false ){
          $clatenditemmenu->excluir(null," at23_atenditem = $at05_seq " );
          $erro_msg = $clatenditemmenu->erro_msg;
          if ($clatenditemmenu->erro_status == "0") {
            $sqlerro = true;
          }
          // grava atenditemmenu
          if( $sqlerro == false ){
            $clatenditemmenu->at23_sequencial = 0;
            $clatenditemmenu->at23_atenditem  = $clatenditem->at05_seq;
            $clatenditemmenu->at23_codatend   = $codatend;
            $clatenditemmenu->at23_itemenu    = $item_menu;
            $clatenditemmenu->incluir(null);
            $erro_msg = $clatenditemmenu->erro_msg;
            if ($clatenditemmenu->erro_status == "0") {
              $sqlerro = true;
            }
          }
          
        }
        
        
        
      }
      $rs_modulo = $clatenditemmod->sql_record("select * from atenditemmod where  at22_codatend=$codatend and at22_atenditem=$at05_seq");
      $linhasmod = $clatenditemmod->numrows;
      
      if ($sqlerro == false) {
        
        if ($linhasmod > 0) {
          db_fieldsmemory($rs_modulo, 0);
          $clatenditemmod->excluir($at22_sequencial);
        }
        if (isset ($modulo) && $modulo > 0) {
          $clatenditemmod->at22_atenditem = $clatenditem->at05_seq;
          $clatenditemmod->at22_codatend = $codatend;
          $clatenditemmod->at22_modulo = $modulo;
          $clatenditemmod->incluir(null);
          if ($clatenditemmod->erro_status == 0) {
            $sqlerro = true;
            $erro_msg = $clatenditemmod->erro_msg;
          }
        }
      }
      
      if ($sqlerro == false) {
        $cl_atenditemsyscadproced->at29_sequencial = $at29_sequencial;
        $cl_atenditemsyscadproced->at29_atenditem = $clatenditem->at05_seq;
        $cl_atenditemsyscadproced->at29_syscadproced = $codproced;
        $cl_atenditemsyscadproced->alterar($at29_sequencial);
        if ($cl_atenditemsyscadproced->erro_status == "0") {
          $sqlerro = true;
          $erro_msg = $cl_atenditemsyscadproced->erro_msg;
        }
      }
      
      if ($sqlerro == false) {
        if ($motivo != 0) {
          $cl_atenditemmotivo->at34_sequencial = $at34_sequencial;
          $cl_atenditemmotivo->at34_atenditem = $clatenditem->at05_seq;
          $cl_atenditemmotivo->at34_tarefacadmotivo = $motivo;
          $cl_atenditemmotivo->alterar($at34_sequencial);
          if ($cl_atenditemmotivo->erro_status == "0") {
            $sqlerro = true;
            $erro_msg = $cl_atenditemmotivo->erro_msg;
          }
        } else {
          
          $sqlerro = true;
          $erro_msg = "Campo motivo obrigatório.";
        }
      }
      
      if (isset ($usuorigem) && $usuorigem != "" and $sqlerro == false) {
        reset($usuorigem);
        $clatenditemusu->excluir(null, "at21_codatend = $codatend and at21_atenditem = $clatenditem->at05_seq ");
        for ($w = 0; $w < count($usuorigem); $w ++) {
          if ($usuorigem[$w] != "") {
            //echo "<br>usu = ".$usuorigem[$w];
            if ($sqlerro == false) {
              $clatenditemusu->at21_atenditem = $clatenditem->at05_seq;
              $clatenditemusu->at21_codatend = $codatend;
              $clatenditemusu->at21_usuario = $usuorigem[$w];
              $clatenditemusu->incluir(null);
              if ($clatenditemusu->erro_status == "0") {
                $sqlerro = true;
                $erro_msg = $clatenditemusu->erro_msg;
                break;
              }
            }
          }
          next($usuorigem);
        }
      }
      db_fim_transacao($sqlerro);
      if ($sqlerro) {
        db_msgbox($erro_msg);
        
      }
      $opcao = "incluir";
      $db_opcao = 1;
    }
    // ##############################EXCLUIR ################################
    
    if (isset ($excluir) && $excluir != "") {
      
      $opcao = "incluir";
      db_inicio_transacao();
      $sqlerro = false;
      
      //$rs_atenditem = $clatenditem->sql_record($clatenditem->sql_query(null,"*","at05_seq","at05_codatend=$codatend and at05_seq = $at05_seq"));
      //die($clatenditem->sql_query(null,"*","at05_seq","at05_codatend=$codatend and at05_seq = $at05_seq"));
      //if($clatenditem->numrows > 0) {
        //	db_fieldsmemory($rs_atenditem,0);
        
        // grava atenditemmenu
        if( $sqlerro == false ){
          $clatenditemmenu->excluir(null," at23_atenditem = $at05_seq " );
          $erro_msg = $clatenditemmenu->erro_msg;
          if ($clatenditemmenu->erro_status == "0") {
            $sqlerro = true;
          }
        }
        
        $cl_atenditemmotivo->excluir($at34_sequencial);
        $cl_atenditemsyscadproced->excluir($at29_sequencial);
        $clatenditemmod->excluir(null, "at22_atenditem = $at05_seq");
        $clatenditem->excluir($at05_seq);
      //}
      if ($clatenditemmod->erro_status == "0") {
        $sqlerro = true;
        $erro_msg = $clatenditemmod->erro_msg;
      }
      if ($clatenditem->erro_status == "0") {
        $sqlerro = true;
        $erro_msg = $clatenditem->erro_msg;
      }
      db_fim_transacao($sqlerro);
      
    }
    // ############################## FECHAR ################################
    $data = date("Y-m-d");
    $hora = date("H:i");
    
    if (isset ($fechar) && $fechar != "") {
      
      db_inicio_transacao();

      $sqlerro = false;
      $rs_at = $clatendimento->sql_record("select at02_codatend as atendimento,at05_seq as item,at05_perc,at18_tarefa,at05_solicitado,at05_feito,at05_horaini,at05_horafim,at05_prioridade
      from atendimento 
      inner join atenditem on at05_codatend=at02_codatend 
      left join atenditemtarefa on  at18_atenditem = at05_seq 
      where at02_codatend=$codatend");
      
      $linha_at = $clatendimento->numrows;
      
      if ($linha_at > 0) {
        
        $clatendimento->at02_codatend = $codatend;
        $clatendimento->at02_datafim = $data;
        $clatendimento->at02_horafim = $hora;
        $clatendimento->alterar($codatend);
        if ($clatendimento->erro_status == "0") {
          $sqlerro = true;
          $erro_msg = $clatendimento->erro_msg;
          
        }
        
        for ($i = 0; $i < $linha_at; $i ++) {
          db_fieldsmemory($rs_at, $i);
          
          // ########## SE TIVER TAREFA RELACIONADA AO ATENDIMENTO ############
          if ($at18_tarefa != "") {
            
            $cltarefalog->at43_tarefa = $at18_tarefa;
            $cltarefalog->at43_descr = addslashes($at05_solicitado);
            $cltarefalog->at43_obs = addslashes($at05_feito);
            $cltarefalog->at43_problema = "false";
            $cltarefalog->at43_avisar = "0";
            $cltarefalog->at43_progresso = $at05_perc;
            $cltarefalog->at43_usuario = db_getsession("DB_id_usuario");
            $cltarefalog->at43_diaini = $data;
            $cltarefalog->at43_diafim = $data;
            $cltarefalog->at43_horainidia = $at05_horaini;
            $cltarefalog->at43_horafim = $at05_horafim;
            $cltarefalog->at43_tipomov = "0";
            $cltarefalog->incluir(null);
            if ($cltarefalog->erro_status == 0) {
              $sqlerro = true;
              $erro_msg = $cltarefalog->erro_msg;
              
            }
            if($sqlerro == false){
            	 $sqlsit = "
											select at46_codigo as at48_situacao
													from tarefasituacao
											  	inner join tarefacadsituacao on at47_situacao = at46_codigo 
											    where at47_tarefa = $at18_tarefa
								";
							//die($sqlsit);
						  $ressit = db_query($sqlsit);
							$linhassit = pg_num_rows($ressit);
							if($linhassit>0){
							  db_fieldsmemory($ressit,0);
							}           
              $cltarefalogsituacao->at48_tarefalog = $cltarefalog->at43_sequencial;
              $cltarefalogsituacao->at48_situacao = $at48_situacao;
              $cltarefalogsituacao->incluir(null);
              if ($cltarefalogsituacao->erro_status == 0) {
                $sqlerro = true;
                //db_msgbox("tarefalogsituacao");
                $erro_msg = $cltarefalogsituacao->erro_msg;
              }
            }
            
            
            if ($sqlerro == false) {
              db_msgbox("Foi lançado um registro na tarefa $at18_tarefa automaticamente.");
            }
           




            // ########## SE NÃO TIVER TAREFA RELACIONADA AO ATENDIMENTO ############
          } else {
            
            /*
            if (isset($at41_proced) and ($at41_proced == "0") and $sqlerro == false) {
              $sqlerro = true;
              $erro_msg = "Preencha o procedimento!";
            }*/
            
            if ($sqlerro == false) {
              
              $clatendimento->sql_query_inc(null, "*", "", "at02_codatend = $atendimento and at05_seq =$item");
              
              $sql = "		    	
              select * from atendimento 
              left join atenditem on atenditem.at05_codatend = atendimento.at02_codatend 
              inner join atenditemsyscadproced on at29_atenditem = at05_seq
              inner join atenditemmotivo on at34_atenditem = at05_seq										
              where at02_codatend = $atendimento and at05_seq =$item";
              
              
              $resultate = $clatendimento->sql_record($sql);
              
              $linha = $clatendimento->numrows;
              if ($linha > 0) {
                db_fieldsmemory($resultate, 0);
              }
            }
            
            if ($sqlerro == false) {
              $cltarefa->at40_responsavel = db_getsession("DB_id_usuario");
              $cltarefa->at40_descr = addslashes($at05_solicitado);
              if ($at05_data == "") {
                $cltarefa->at40_diaini = date("Y-m-d", db_getsession("DB_datausu"));
                $cltarefa->at40_diafim = date("Y-m-d", db_getsession("DB_datausu"));
              } else {
                $cltarefa->at40_diaini = $at05_data;
                $cltarefa->at40_diafim = $at05_data;
              }
              $cltarefa->at40_tipoprevisao = "h";
              $cltarefa->at40_previsao = 1;
              $cltarefa->at40_horainidia = $at05_horaini;
              $cltarefa->at40_horafim = $at05_horafim;
              $cltarefa->at40_progresso = $at05_perc;
              $cltarefa->at40_prioridade = $at05_prioridade;
              $cltarefa->at40_obs = addslashes($at05_feito);
              $cltarefa->at40_tipo = 1;
              if (($at05_perc == 100) || ($at34_tarefacadmotivo == 13 || $at34_tarefacadmotivo == 16)) {
                if ($at29_syscadproced == 278 or $at29_syscadproced == 328) {
                  $cltarefa->at40_horainidia = "08:00";
                  $cltarefa->at40_horafim = "19:00";
                  $cltarefa->at40_autorizada = 'f';
                } else {
                  $cltarefa->at40_autorizada = 't';
                }
              } else {
                $cltarefa->at40_autorizada = 'f';
              }
              $cltarefa->at40_ativo = 't';
              if ($at05_prioridade==3){
                $cltarefa->at40_urgente = 1;
              }else{
                $cltarefa->at40_urgente = 0;
              }
              $cltarefa->incluir(null);
              if ($cltarefa->erro_status == 0) {
                $sqlerro = true;
                $erro_banco = $cltarefa->erro_msg;
                //db_msgbox("tarefa");
              }
            }
            if ($sqlerro == false) {
              $tar = $cltarefa->at40_sequencial;
              
              $cl_tarefasyscadproced->at37_tarefa = $tar;
              $cl_tarefasyscadproced->at37_syscadproced = $at29_syscadproced;
              $cl_tarefasyscadproced->incluir(null);
              if ($cl_tarefasyscadproced->erro_status == 0) {
                $sqlerro = true;
                $erro_banco = $cl_tarefasyscadproced->erro_msg;
                //db_msgbox("tarefasyscadproced");
              }
              
            }
            
            /*
            if ($sqlerro == false) {
              $cltarefaproced->incluir($cltarefa->at40_sequencial,$at41_proced);
              if($cltarefaproced->erro_status==0) {
                $sqlerro = true;
                $erro_msg = $cltarefaproced->erro_msg;
              }
            }*/
            $sqlmod  = "select * from atenditemmod where at22_atenditem = $item and at22_codatend = $atendimento";
            $resmod = db_query ($sqlmod);
            $linhasmod= pg_num_rows($resmod);
            if($linhasmod>0){
              db_fieldsmemory($resmod, 0);
              $cltarefamodulo->at49_tarefa = $cltarefa->at40_sequencial;
              $cltarefamodulo->at49_modulo = $at22_modulo;
              $cltarefamodulo->incluir(null);
              if ($cltarefamodulo->erro_status == 0) {
                $sqlerro = true;
                $erro_banco = $cltarefamodulo->erro_msg;
                //db_msgbox("tarefamodulo");
              }
            }
            
            
            if ($sqlerro == false) {
              
              $cltarefamotivo->at55_tarefa = $cltarefa->at40_sequencial;
              $cltarefamotivo->at55_motivo = $at34_tarefacadmotivo;
              $cltarefamotivo->incluir();
              if ($cltarefamotivo->erro_status == 0) {
                $sqlerro = true;
                $erro_banco = $cltarefamotivo->erro_msg;
                //db_msgbox("tarefamotivo");
              }
            }
            
            if ($sqlerro == false) {
              
              if ($at05_perc == 100) {
                $cltarefasituacao->at47_situacao = 3;
              } else {
                $cltarefasituacao->at47_situacao = 2;
              }
              $cltarefasituacao->at47_tarefa = $cltarefa->at40_sequencial;
              $cltarefasituacao->incluir(null);
              if ($cltarefasituacao->erro_status == 0) {
                $sqlerro = true;
                db_msgbox("tarefasituacao");
                $erro_msg = $cltarefasituacao->erro_msg;
                
              }
              
            }
            
            if ($sqlerro == false) {
              $cltarefaitem->at44_atenditem = $at05_seq;
              $cltarefaitem->at44_tarefa = $cltarefa->at40_sequencial;
              $cltarefaitem->incluir($cltarefa->at40_sequencial);
              if ($cltarefaitem->erro_status == 0) {
                $sqlerro = true;
                //db_msgbox("tarefaitem");
                $erro_msg = $cltarefaitem->erro_msg;
              }
            }
            
            if ($sqlerro == false) {
              $cltarefausu->at42_tarefa = $cltarefa->at40_sequencial;
              $cltarefausu->at42_usuario = $cltarefa->at40_responsavel;
              $cltarefausu->at42_perc = 100;
              $cltarefausu->incluir(null);
              if ($cltarefausu->erro_status == 0) {
                $sqlerro = true;
                //db_msgbox("tarefausu");
                $erro_msg = $cltarefausu->erro_msg;
              }
            }
            
            if ($sqlerro == false) {
              $cltarefaenvol->at45_tarefa = $cltarefa->at40_sequencial;
              $cltarefaenvol->at45_usuario = $cltarefa->at40_responsavel;
              $cltarefaenvol->at45_perc = 100;
              $cltarefaenvol->incluir(null);
              if ($cltarefaenvol->erro_status == 0) {
                $sqlerro = true;
                //db_msgbox("tarefaenvol");
                $erro_msg = $cltarefaenvol->erro_msg;
              }
            }
            
            if ($sqlerro == false) {
              $cltarefaclientes->at70_tarefa = $cltarefa->at40_sequencial;
              $cltarefaclientes->at70_cliente = $at02_codcli;
              $cltarefaclientes->incluir(null);
              if ($cltarefaclientes->erro_status == 0) {
                $sqlerro = true;
                //db_msgbox("tarefaclientes");
                $erro_msg = $cltarefaclientes->erro_msg;
                
              }
            }
            
            if ($sqlerro == false) {
              $cltarefa_lanc->at36_data = date("Y", db_getsession("DB_datausu"))."-".date("m", db_getsession("DB_datausu"))."-".date("d", db_getsession("DB_datausu"));
              $cltarefa_lanc->at36_hora = db_hora();
              $cltarefa_lanc->at36_ip = db_getsession("DB_ip");
              $cltarefa_lanc->at36_tarefa = $cltarefa->at40_sequencial;
              $cltarefa_lanc->at36_usuario = db_getsession("DB_id_usuario");
              $cltarefa_lanc->at36_tipo = "I";
              $cltarefa_lanc->incluir(null);
              if ($cltarefa_lanc->erro_status == 0) {
                $sqlerro = true;
                //db_msgbox("tarefa_lanc");
                $erro_msg = $cltarefa_lanc->erro_msg;
                
              }
            }
            
            if ($sqlerro == false) {
              $sqlerro = $cltarefa_agenda->gera_agenda($cltarefaparam, $cltarefa, $erro_msg);
            }
            
            if ($sqlerro == false) {
              //if ($at05_perc == 100) {
                
                $cltarefalog->at43_tarefa = $cltarefa->at40_sequencial;
                $cltarefalog->at43_descr = addslashes($at05_solicitado);
                $cltarefalog->at43_obs = addslashes($at05_feito);
                $cltarefalog->at43_problema = "false";
                $cltarefalog->at43_avisar = "0";
                $cltarefalog->at43_progresso = $at05_perc;
                $cltarefalog->at43_usuario = db_getsession("DB_id_usuario");
                $cltarefalog->at43_diaini = $cltarefa->at40_diaini;
                $cltarefalog->at43_diafim = $cltarefa->at40_diafim;
                $cltarefalog->at43_horainidia = $cltarefa->at40_horainidia;
                $cltarefalog->at43_horafim = $cltarefa->at40_horafim;
                $cltarefalog->at43_tipomov = "0";
                $cltarefalog->incluir(null);
                if ($cltarefalog->erro_status == 0) {
                  $sqlerro = true;
                  //db_msgbox("tarefalog");
                  $erro_msg = $cltarefalog->erro_msg;
                }
                if ($at05_perc == 100) {
                  $cltarefalogsituacao->at48_tarefalog = $cltarefalog->at43_sequencial;
                  $cltarefalogsituacao->at48_situacao = 3;
                  $cltarefalogsituacao->incluir(null);
                  if ($cltarefalogsituacao->erro_status == 0) {
                    $sqlerro = true;
                    //db_msgbox("tarefalogsituacao");
                    $erro_msg = $cltarefalogsituacao->erro_msg;
                  
                  }
                }                 
              //}
              
              /*
              if($sqlerro==false) {
                $cltarefalogsituacao->at48_tarefalog = $cltarefalog->at43_sequencial;
                if ($at05_perc == 100 ) {
                  $cltarefalogsituacao->at48_situacao  = 3;
                  $cltarefalogsituacao->incluir(null);
                }else{
                  $cltarefalogsituacao->at48_situacao  = 2;
                }
                
                if($cltarefalogsituacao->erro_status==0) {
                  $sqlerro = true;
                  $erro_msg = $cltarefalogsituacao->erro_msg;
                  db_msgbox("1111");
                }
              }*/
              
            }
            
            // *************** TESTE ***************
            // tem que pegar o motivo e area
            // pega a area.....
            $sqlteste = "select codarea from db_syscadproced where codproced = $at29_syscadproced";
            //die($sqlteste);
            $resultteste = db_query($sqlteste) or die($sqlteste);
            $linhasteste = pg_num_rows($resultteste);
            if ($linhasteste>0){
              db_fieldsmemory($resultteste, 0);
              //db_msgbox("codarea = $codarea  motivo =$at34_tarefacadmotivo");
              
            }
            
            // pegar motivo   $at34_tarefacadmotivo
            
            $at33_proced = "";
            if ($at29_syscadproced == 278) { // agenda de visitas
              $at33_proced = 9;
            } else {
              // buscar o proced no tarefamotivoarea
              $sqlmotarea = "	select * 
              from tarefamotivoarea 
              where at33_tarefacadmotivo = $at34_tarefacadmotivo 
              and at33_atendcadarea = $codarea";
              $resultmotarea = db_query($sqlmotarea) or die($sqlmotarea);
              $linhasmotarea = pg_num_rows($resultmotarea);
              if ($linhasmotarea>0){
                db_fieldsmemory($resultmotarea, 0);
              }
            }
            
            if ($at33_proced != "") {
              // gravar na tarefaproced
              $cltarefaproced->incluir($cltarefa->at40_sequencial,$at33_proced);
              if($cltarefaproced->erro_status==0) {
                $sqlerro = true;
                //db_msgbox("tarefaproced");
                $erro_msg = $cltarefaproced->erro_msg;
                die($erro_msg);
              }
            } else {
              $sqlerro = true;
              $erro_msg = "Procedimento nao ligado a motivo e área! (tabela tarefamotivoarea)";
            }
            
            if ($sqlerro == true) {
              db_msgbox($erro_msg);
            }
            if ($sqlerro == false) {
              db_msgbox("Foi gerado tarefa $tar automaticamente.");
            }
            
          }
          
        }
       

    if($sqlerro == false) {

      if ( $at64_sequencial > 0 ){

       $result = $cltarefaprojetoativcli->sql_record($cltarefaprojetoativcli->sql_query_file(null, "at69_sequencial", null, "at69_seqtarefa=".$cltarefa->at40_sequencial));
       if ($cltarefaprojetoativcli->numrows > 0) {
          db_fieldsmemory($result,0);
          $cltarefaprojetoativcli->at69_sequencial = $at69_sequencial;
          $cltarefaprojetoativcli->at69_seqprojeto = $at64_sequencial;
          $cltarefaprojetoativcli->at69_seqtarefa  = $cltarefa->at40_sequencial;
          $cltarefaprojetoativcli->alterar($at69_sequencial);
          if ($cltarefaprojetoativcli->erro_status == 0) {
            $erro_msg = $cltarefaprojetoativcli->erro_msg;
            $sqlerro = true;
          }
       }else{
          $cltarefaprojetoativcli->at69_sequencial = 0;
          $cltarefaprojetoativcli->at69_seqprojeto = $at64_sequencial;
          $cltarefaprojetoativcli->at69_seqtarefa  = $cltarefa->at40_sequencial;
          $cltarefaprojetoativcli->incluir($cltarefaprojetoativcli->at69_sequencial);
          if ($cltarefaprojetoativcli->erro_status == 0) {
            $erro_msg = $cltarefaprojetoativcli->erro_msg;
            $sqlerro = true;
          }
       }

      }else{
       $result = $cltarefaprojetoativcli->sql_record($cltarefaprojetoativcli->sql_query_file(null, "at69_sequencial", null, "at69_seqtarefa=".$cltarefa->at40_sequencial));
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




        echo "
        <script>
        location.href='ate4_atendsup001.php?opcao=incluir';
        </script>
        ";
      }
      //$sqlerro=true;

      $result_atend = $clatendtecnicoocupado->sql_record($clatendtecnicoocupado->sql_query(null,"at72_id as codigo",null," at72_id_usuario = ".db_getsession("DB_id_usuario")));          
      if($clatendtecnicoocupado->numrows>0){
        db_fieldsmemory($result_atend,0,0);
    
        $clatendtecnicoocupado->excluir($codigo); 
        if( $clatendtecnicoocupado->erro_status == 0){
          db_msgbox($clatendtecnicoocupado->erro_msg);
          $sqlerro = false;
        }  
    
      }







      db_fim_transacao($sqlerro);
    }
    
    if (isset ($chavepesquisa) && $chavepesquisa != "") {
      
      $db_opcao = 1;
      
      if (isset ($opcao) && $opcao != "") {
        if ($opcao == "incluir") {
          $db_opcao = 1;
        }
        if ($opcao == "alterar") {
          $db_opcao = 2;
        }
        if ($opcao == "excluir") {
          $db_opcao = 3;
        }
      }
      
      $result_atendimento = $clatendimento->sql_record($clatendimento->sql_query_file($chavepesquisa));
      if ($clatendimento->numrows > 0) {
        db_fieldsmemory($result_atendimento, 0);
        $codatend = $at02_codatend;
        $clientes = $at02_codcli;
      }
      
      if ($db_opcao == 2 || $db_opcao == 3) {
        //db_msgbox("22=$at05_seq");
        $rs_atenditem = $clatenditem->sql_record($clatenditem->sql_query(null, "*", "", "at05_codatend=$at02_codatend and at05_seq = $at05_seq"));
        if ($clatenditem->numrows > 0) {
          db_fieldsmemory($rs_atenditem, 0);
          
        }
      }
      $rs_tecnico = $cltecnico->sql_record($cltecnico->sql_query($chavepesquisa, null, "at03_id_usuario", null, ""));
      if ($cltecnico->numrows > 0) {
        
        db_fieldsmemory($rs_tecnico, 0);
        $tecnico = $at03_id_usuario;
      }
      $rs_modulo = $clatendimentomod->sql_record($clatendimentomod->sql_query($chavepesquisa, "at08_modulo", "at08_modulo", null, ""));
      if ($clatendimentomod->numrows > 0) {
        db_fieldsmemory($rs_modulo, 0);
      }

      
      $result = $cltarefaprojetoativcli->sql_record($cltarefaprojetoativcli->sql_query(null,"at64_sequencial","","at69_seqtarefa=$chavepesquisa"));
      if ($cltarefaprojetoativcli->numrows > 0) {
        db_fieldsmemory($result, 0);
      }
      
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
    <table width="95%" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC" align="center">
    <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
    </tr>
    <tr>
    <td colspan="4">
    <?
    
    
    include(modification("forms/db_frmatendsup.php"));
    ?>
    </td>
    </tr>
    <?
    
    
    db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
    ?>
    </body>
    </html>
    <?
    
    
    if ($db_opcao == 11 || $db_opcao == 22) {
      echo "<script>js_pesquisa();</script>";
    }
    if ((isset ($incluir) && $incluir != "") || (isset ($alterar) && $alterar != "") || (isset ($excluir) && $excluir != "")) {
      if ($sqlerro == false) {
        echo "
        <script>
				
        document.form1.at05_feito.value = '';
        document.form1.at05_solicitado.value = '';
				
        document.form1.codproced.value = '0';
        document.form1.codproceddescr.value = '0';
				
        document.form1.at40_sequencial.value = '0';
        document.form1.at40_sequencialdescr.value = '0';
				
        document.form1.modulo.value = '0';
        document.form1.modulodescr.value = '0';
				
        document.form1.motivo.value = '0';
        document.form1.motivodescr.value = '0';
				
        document.form1.at05_perc.value = '0';
        
        </script>
        ";
        echo "<script>document.form1.modulo.value = '0';</script>";
      }
    }
    ?>