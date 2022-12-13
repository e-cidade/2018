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


define("TAREFA", true);

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/smtp.class.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_tarefa_classe.php");
include("classes/db_tarefalog_classe.php");
include("classes/db_tarefalogenvol_classe.php");
include("classes/db_tarefalogsituacao_classe.php");
include("classes/db_tarefasituacao_classe.php");
include("classes/db_tarefalogitem_classe.php");
include("classes/db_tarefaenvol_classe.php");
include("classes/db_tarefaclientes_classe.php");
include("classes/db_db_versaotarefa_classe.php");
include("classes/db_clientes_classe.php");
include("classes/db_tarefalogclientes_classe.php");
include("classes/db_tarefacadsituacaousu_classe.php");
include("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$cldb_usuarios          = new cl_db_usuarios;
$cltarefa               = new cl_tarefa;
$cltarefalog            = new cl_tarefalog;
$cltarefalogenvol       = new cl_tarefalogenvol;
$cltarefalogsituacao    = new cl_tarefalogsituacao;
$cltarefasituacao       = new cl_tarefasituacao;
$cltarefalogitem        = new cl_tarefalogitem;
$cltarefaenvol          = new cl_tarefaenvol;
$cltarefaclientes       = new cl_tarefaclientes;
$cldb_versaotarefa      = new cl_db_versaotarefa;
$clclientes             = new cl_clientes;
$cltarefalogclientes    = new cl_tarefalogclientes;
$cltarefacadsituacaousu = new cl_tarefacadsituacaousu;
$db_opcao = 22;
$db_botao = false;

if(isset($alterar) || isset($excluir) || isset($incluir)){
  $sqlerro = false;
  $cltarefalog->at43_tarefa  = $at43_tarefa;
  $cltarefalog->at43_usuario = $at43_usuario;
  $cltarefalog->at43_tipomov = $at43_tipomov;
  
  if (!isset($at43_problema)) {
    global $at43_problema;
    $at43_problema='f';
    $GLOBALS["HTTP_POST_VARS"]["at43_problema"]=$at43_problema;
    $cltarefalog->at43_problema = $at43_problema;
  }
  
}

if(isset($incluir)){

  if($sqlerro==false){
    db_inicio_transacao();
    
    if( $sqlerro == false){
      if( isset($at45_usuario) && $at45_usuario > 0 ){
        if( isset($usuario_unico) ){
          $result = $cltarefaenvol->sql_record($cltarefaenvol->sql_query_file(null,'at45_sequencial as seque,at45_usuario as usu',null," at45_tarefa = $at43_tarefa "));
          if($cltarefaenvol->numrows>0){
            $numrows = $cltarefaenvol->numrows;
            for($xx=0;$xx<$numrows;$xx++){
              
              db_fieldsmemory($result,$xx);
              
              $cltarefaenvol->at45_perc = 10 ;
              $cltarefaenvol->at45_sequencial = $seque ;
              $cltarefaenvol->at45_usuario = $usu ;
              
              $cltarefaenvol->alterar($seque);
              if($cltarefaenvol->erro_status == '0'){
                $erro_msg= $cltarefaenvol->erro_msg;
                $sqlerro = true;
                break;
              }
              
            }
          }
        }
        
        if( $sqlerro == false ){
          $cltarefaenvol = new cl_tarefaenvol;
          $result = $cltarefaenvol->sql_record($cltarefaenvol->sql_query_file(null,'at45_sequencial as seque',null," at45_tarefa = $at43_tarefa and at45_usuario = $at45_usuario "));
          if($cltarefaenvol->numrows>0){
            $numrows = $cltarefaenvol->numrows;
            for($xx=0;$xx<$numrows;$xx++){
              db_fieldsmemory($result,$xx);
              $cltarefaenvol->at45_sequencial = $seque ;
              $cltarefaenvol->excluir($seque);           
              if($cltarefaenvol->erro_status == '0'){
                $erro_msg= $cltarefaenvol->erro_msg;
                $sqlerro = true;
              }
            }
          }
          
          $cltarefaenvol = new cl_tarefaenvol;
          $cltarefaenvol->at45_sequencial = 0;
          $cltarefaenvol->at45_tarefa     = $at43_tarefa;
          $cltarefaenvol->at45_usuario    = $at45_usuario;
          $cltarefaenvol->at45_perc       = (isset($usuario_unico)?'100':$at45_perc);
          $cltarefaenvol->incluir(0);
          if($cltarefaenvol->erro_status == '0'){
            $erro_msg= $cltarefaenvol->erro_msg;
            $sqlerro = true;
          }
        }
        
      }
      
    }
   
    if( $sqlerro == false){
      
      $cltarefaclientes->excluir(null," at70_tarefa = $at43_tarefa and at70_cliente = $at70_cliente");
      if( $cltarefaclientes->erro_status == '0'){
        $erro_msg= $cltarefaclientes->erro_msg;
        $sqlerro = true;
      }else{
        
        $cltarefaclientes->at70_sequencial = 0;
        $cltarefaclientes->at70_tarefa     = $at43_tarefa;
        $cltarefaclientes->at70_cliente    = $at70_cliente;
        $cltarefaclientes->incluir(0);
        if($cltarefaclientes->erro_status == '0'){
          $erro_msg= $cltarefaclientes->erro_msg;
          $sqlerro = true;
        }
      }
    }
   
    //$cltarefalog->incluir($at43_sequencial);
    $cltarefalog->incluir(null);
    $erro_msg = $cltarefalog->erro_msg;
    $at43_sequencial = $cltarefalog->at43_sequencial;

    $usuario_movimento = $at45_usuario;

    if($cltarefalog->erro_status==0){
      $sqlerro=true;
    } else {
      
      $at43_sequencial_novo = $at43_sequencial;

      if($cltarefalog->at43_avisar==3||$cltarefalog->at43_avisar==2) {
			
        $rs_tarefa = $cltarefa->sql_record($cltarefa->sql_query_envol($at43_tarefa,"	   		   at45_usuario,
        tarefa.*,
        tarefaenvol.*,
        tarefa_lanc.*,
        db_usuarios2.nome as nome_criado,
        at36_data,
        at36_hora",
        "at40_sequencial,tarefaenvol.at45_usuario",null));
        
        if($cltarefa->numrows > 0) {
          for($i=0; $i < $cltarefa->numrows; $i++) {
            db_fieldsmemory($rs_tarefa,$i);
            
            if ($at45_usuario == $at43_usuario) {
              continue;
            }
            
            $rs_usuario = $cldb_usuarios->sql_record($cldb_usuarios->sql_query($at45_usuario,"email,nome as nome_criador","id_usuario"));
            if($cldb_usuarios->numrows > 0) {
              db_fieldsmemory($rs_usuario,0);
              
              $emailenviar = $email;
              
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
              db_fieldsmemory($rs_resp,0);
              
              $mensagem  = $nome . "<br><br>";
              $mensagem .= "Um novo registro foi acrescentado a tarefa $at43_tarefa:<br>";
              $mensagem .= "Usuário    : $at43_usuario - $nome            <br>";
              $mensagem .= "Descricao  :         " . $at43_descr       . "<br>";
              $mensagem .= "Observações:         " . $at43_obs         . "<br>";
              $mensagem .= "Percentual de envolvimento: " . $at45_perc . "%<br>";
              $mensagem .= "Data inicial:        $at43_diaini_dia/$at43_diaini_mes/$at43_diaini_ano - Hora Inicial: $at43_horainidia - ";
              $mensagem .= "Data final prevista: $at43_diafim_dia/$at43_diafim_mes/$at43_diafim_ano - Hora final: $at43_horafim<br>";
              $mensagem .= "Progresso          : " . $at43_progresso. "<br>";
              
              $rs_log = $cltarefalog->sql_record($cltarefalog->sql_query_usua(null,"tarefalog.*, db_usuarios.*","at43_diaini, at43_horainidia"," at43_tarefa = $at43_tarefa"));
              
              $mensagem .= "<br><br>" . str_repeat("=",60) ."<br>";
              
              $mensagem .= "<br>DADOS DA TAREFA<br><br>";
              $mensagem .= "Tarefa: $at40_sequencial<br>";
              $mensagem .= "Responsavel: $at40_responsavel - $nome_resp<br>";
              if ($at36_usuario != "") {
                $mensagem .= "Criada por : $at36_usuario - $nome_criado - Data: $at36_data - Hora: $at36_hora<br>";
              }
              $mensagem .= "Descricao  : $at40_descr<br>";
              $mensagem .= "Observações: $at40_obs<br>";
              
              $mensagem .= "<br><br>" . str_repeat("=",60) ."<br>";
              
              $mensagem .= "<br>LISTA DE TODOS OS ANDAMENTOS<br><br>";
              
              for ($log=0; $log < $cltarefalog->numrows; $log++) {
                db_fieldsmemory($rs_log, $log);
                
                if ($at43_sequencial_novo <> $at43_sequencial) {
                  
                  $mensagem .= "Sequencia  : $at43_sequencial<br>";
                  $mensagem .= "Descricao  : $at43_descr<br>";
                  $mensagem .= "Observações: $at43_obs<br>";
                  $mensagem .= "Usuario    : $at43_usuario - $nome<br>";
                  $mensagem .= "Inicio     : $at43_diaini - $at43_horainidia<br>";
                  $mensagem .= "Final      : $at43_diafim - $at43_horafim<br>";
                  $mensagem .= "Progresso  : $at43_progresso<br><br>";
                  
                  $mensagem .= "<br><br>" . str_repeat("=",60) ."<br>";
                  
                }
                
              }
              $envio = false;
              if($at48_situacao==2){
                $envio = $cltarefalog->enviar_email($emailenviar,"Tarefa Análise: ".$cltarefalog->at43_tarefa . " - " . substr($at43_descr,0,40),$mensagem);                
              }else{
                $envio = $cltarefalog->enviar_email($emailenviar,"Tarefa: ".$cltarefalog->at43_tarefa . " - " . substr($at43_descr,0,40),$mensagem);
              }
              if($envio == false) {
                db_msgbox("Erro ao enviar e-mail para " . $email);
              }
            }
          }
        }
      }
     
      // Gerar tarefalogenvol
      if(isset($usuario_unico)) {
        $cltarefalogenvol->at35_tarefalog = $cltarefalog->at43_sequencial;
        $cltarefalogenvol->at35_usuario   = $usuario_movimento;
        $cltarefalogenvol->at35_perc      = 100;
        $cltarefalogenvol->incluir(null);
        if($cltarefalogenvol->erro_status=="0"){
          $erro_msg= $cltarefalogenvol->erro_msg;
          $sqlerro = true;
        }
      }
      
      $cltarefalogsituacao->at48_tarefalog = $cltarefalog->at43_sequencial;
      $cltarefalogsituacao->at48_situacao  = $at48_situacao;
      $cltarefalogsituacao->incluir(null);
      $erro_msg = $cltarefalogsituacao->erro_msg;
    
      if($cltarefalogsituacao->erro_status!=0) {
        
        $cltarefa->at40_progresso  = $cltarefalog->at43_progresso;
        $cltarefa->at40_sequencial = $cltarefalog->at43_tarefa;
        //$cltarefa->at40_autorizada = "true";
        
        $result = $cltarefasituacao->sql_record($cltarefasituacao->sql_query(null,"at47_sequencial",null,"at47_tarefa=" . $cltarefalog->at43_tarefa));
        if ($cltarefasituacao->numrows > 0) {
          db_fieldsmemory($result,0);
          $cltarefasituacao->at47_sequencial = $at47_sequencial;
        }
        
        if ($cltarefalog->at43_progresso == 100) {
          $cltarefasituacao->at47_situacao = 3;
        } else {
          //db_msgbox("sit=$at48_situacao");
          $cltarefasituacao->at47_situacao = $at48_situacao;
        }

        // Somente altera a situacao da tarefa se o usuario tem permissao de lancar registros
        // na situacao ATUAL da tarefa
        $sqlsituacaousu  = "select * ";
        $sqlsituacaousu .= "  from tarefasituacao ";
        $sqlsituacaousu .= "       left join tarefacadsituacaousu on at17_tarefacadsituacao = at47_situacao ";
        $sqlsituacaousu .= " where at47_tarefa  = {$cltarefalog->at43_tarefa} ";
        $sqlsituacaousu .= "   and at17_usuario = {$cltarefalog->at43_usuario} ";

        $rsSituacaoUsu = $cltarefacadsituacaousu->sql_record($sqlsituacaousu);

        if ($cltarefacadsituacaousu->numrows > 0) {
          if ($cltarefasituacao->numrows > 0) {
            $cltarefasituacao->alterar($at47_sequencial);
          } else {
            $cltarefasituacao->at47_tarefa = $cltarefalog->at43_tarefa;
            $cltarefasituacao->incluir(null);
          }

          if ($cltarefasituacao->erro_status == 0) {
            $sqlerro = true;
          }
        } else {
          db_msgbox("AVISO! Vc não tem permissão para alterar a situação atual da tarefa. Procedendo com a modificação no registro mesmo assim...");
        }

        // grava os items de menus ligados ao movimento da tarefa
        if ($sqlerro == false) {
          
          $tarefaslogitem = split('-',$itens_menu_escolhidos);
          
          if($cltarefalog->at43_tipomov == 3){
            if (count($tarefaslogitem) == 1) {
              $sqlerro = true;
              $erro_msg = "Deverá ser informado o item de menu que foi alterados!";
              echo "<script> alert('$erro_msg'); </script>";
            } elseif ($at43_obs == "") {
              $sqlerro = true;
              $erro_msg = "Deverá ser informado a observação obrigatoriamente para o tipo de movimento [Usuário]!";
              echo "<script> alert('$erro_msg'); </script>";
            } else {
              //print_r($tarefaslogitem);exit;
              
              $cltarefalogitem->excluir(null,' at66_codmov = '.$cltarefalog->at43_sequencial);
              for($grava_itens=1;$grava_itens<count($tarefaslogitem);$grava_itens++){
                $cltarefalogitem->at66_sequencial = 0;
                $cltarefalogitem->at66_codmov     = $cltarefalog->at43_sequencial;
                $cltarefalogitem->at66_id_item    = $tarefaslogitem[$grava_itens];
                $cltarefalogitem->incluir(0);
                if ($cltarefalogitem->erro_status == 0) {
                  $sqlerro = true;
                  $erro_msg = $cltarefalogitem->erro_msg;
                  echo "<script> alert('$erro_msg'); </script>";
                  break;
                }
                
              }
            }
          }
          
        }
       
        // grava os clientes envolvidos no movimento da tarefa
        if ($sqlerro == false) {
          

          if( $cltarefalog->at43_tipomov == 1 || $cltarefalog->at43_tipomov == 3 || $cltarefalog->at43_tipomov == 4  || $cltarefalog->at43_tipomov == 7){

            if(trim($itens_clientes_escolhidos) <> "") {
              $tarefalogclientes = explode('-',$itens_clientes_escolhidos);
            } else {
              $tarefalogclientes = array();
            }
            $countclientes     = count($tarefalogclientes);


            if ( $countclientes > 0  ) {

              $cltarefalogclientes->excluir(null,' at68_codmov = '.$cltarefalog->at43_sequencial);
              for($grava_itens=0; $grava_itens<$countclientes;$grava_itens++){
                //$cltarefalogclientes->at68_sequen = 0;
		if ( $tarefalogclientes[$grava_itens] ==""){
		  continue;
		}
                $cltarefalogclientes->at68_codmov = $cltarefalog->at43_sequencial;
                $cltarefalogclientes->at68_codcli = $tarefalogclientes[$grava_itens];
                $cltarefalogclientes->incluir(null);
                if ($cltarefalogclientes->erro_status == 0) {
                  $sqlerro = true;
                  $erro_msg = $cltarefalogclientes->erro_msg;
                  //echo "<script> alert('$erro_msg'); </script>";
                  break;
                }
              }
            } else {
              $sqlerro = true;
              $erro_msg = "Voce deve selecionar pelo menos um cliente para incluir esse registro!";
              //echo "<script> alert('$erro_msg'); </script>";
            }
          }
          
        }

        if ($sqlerro == false) {
          $cltarefa->at40_descr = addslashes($at40_descr);
          $cltarefa->alterar($cltarefalog->at43_tarefa);
          if($cltarefa->erro_status!=0 and $sqlerro == false) {
            db_fim_transacao($sqlerro);
            echo "<script>top.corpo.iframe_tarefalog.location.href='ate1_tarefalogand002.php?a=1&at43_tarefa=".@$at43_tarefa."&xxx=2&at43_usuario=".@$at43_usuario."&opcao=" . 'alterar' . "&at43_sequencial=" . $cltarefalog->at43_sequencial . "'</script>";
          } else {
            $sqlerro  = true;
            $erro_msg = $cltarefa->erro_msg;
          }
          
        }
      } else {
        $sqlerro  = true;
        $erro_msg = $cltarefalogsituacao->erro_msg;
      }
    }
    
    db_fim_transacao($sqlerro);
    
  }

}else if(isset($alterar)){
  if($sqlerro == false) {
    
    db_inicio_transacao();
    $cltarefalog->alterar($at43_sequencial);
    
    $erro_msg = $cltarefalog->erro_msg;
    if($cltarefalog->erro_status == 0) {
      $sqlerro = true;
    }
    // grava os items de menus ligados ao movimento da tarefa
    if ($sqlerro == false) {
      $cltarefalogitem->excluir(null,' at66_codmov = '.$at43_sequencial);
      
      if($cltarefalog->at43_tipomov == 3){
        $tarefaslogitem = split('-',$itens_menu_escolhidos);
        if( count($tarefaslogitem) == 1 ){
          
          $sqlerro = true;
          $erro_msg = " Devera ser informado os itens de menus que foram alterados.";
          echo "<script> alert('$erro_msg'); </script>";
          
        }else{
          //print_r($tarefaslogitem);exit;
          
          for($grava_itens=1;$grava_itens<count($tarefaslogitem);$grava_itens++){
            $cltarefalogitem->at66_sequencial = 0;
            $cltarefalogitem->at66_codmov     = $at43_sequencial;
            $cltarefalogitem->at66_id_item    = $tarefaslogitem[$grava_itens];
            $cltarefalogitem->incluir(0);
            if ($cltarefalogitem->erro_status == 0) {
              $sqlerro = true;
              $erro_msg = $cltarefalogitem->erro_msg;
              //echo "<script> alert('$erro_msg'); </script>";
              //break;
            }
            
          }
        }
      }
      
    }
    
    // grava os clientes ligados ao movimento da tarefa
    if ($sqlerro == false) {
      
      if($cltarefalog->at43_tipomov == 1 || $cltarefalog->at43_tipomov == 3 || $cltarefalog->at43_tipomov == 4  || $cltarefalog->at43_tipomov == 7 ){
        if(trim($itens_clientes_escolhidos) <> "") {
          $tarefalogclientes = explode('-',$itens_clientes_escolhidos);
        } else {
          $tarefalogclientes = array();
        }
        $countclientes     = count($tarefalogclientes);
        if( $countclientes > 0 ){
          
          $cltarefalogclientes->excluir(null,' at68_codmov = '.$at43_sequencial);
          for($grava_itens=0; $grava_itens<$countclientes; $grava_itens++){
            if ( $tarefalogclientes[$grava_itens] ==""){
	      continue;
	    }
            $cltarefalogclientes->at68_sequen     = 0;
            $cltarefalogclientes->at68_codmov     = $at43_sequencial;
            $cltarefalogclientes->at68_codcli     = $tarefalogclientes[$grava_itens];
            $cltarefalogclientes->incluir(null);
            if ($cltarefalogclientes->erro_status == 0) {
              $sqlerro = true;
              $erro_msg = $cltarefalogclientes->erro_msg;
              //echo "<script> alert('$erro_msg'); </script>";
              break;
            }
          }
        } else {
          $sqlerro  = true;
          $erro_msg = "Voce deve selecionar pelo menos um cliente para alterar esse registro!";
          //echo "<script> alert('$erro_msg'); </script>";
        }
      }
      
    }

    
    if($sqlerro == false) {
      $result = $cltarefalogsituacao->sql_record($cltarefalogsituacao->sql_query(null,"at48_sequencial",null,"at48_tarefalog=$cltarefalog->at43_sequencial"));
      if($cltarefalogsituacao->numrows > 0) {
        db_fieldsmemory($result,0);
        
        $cltarefalogsituacao->at48_sequencial = $at48_sequencial;
        $cltarefalogsituacao->at48_tarefalog  = $cltarefalog->at43_sequencial;
        $cltarefalogsituacao->at48_situacao   = $at48_situacao;
        $cltarefalogsituacao->alterar($at48_sequencial);
        
        if($cltarefalogsituacao->erro_status == 0) {
          $erro_msg = $cltarefalogsituacao->erro_msg;
          $sqlerro = true;
        } else {
          echo "<script>top.corpo.iframe_tarefalog.location.href='ate1_tarefalogand002.php?a=3&at43_tarefa=".@$at43_tarefa."&xxx=1&at43_usuario=".@$at43_usuario."'</script>";
        }
      }
    }
    
    if($sqlerro == false) {
      
      $cltarefa->at40_progresso  = $cltarefalog->at43_progresso;
      // $cltarefa->at40_autorizada = "true";
      $cltarefa->at40_sequencial = $cltarefalog->at43_tarefa;
      $cltarefa->alterar($cltarefalog->at43_tarefa);
      
      if($cltarefa->erro_status==0) {
        $sqlerro = true;
        $erro_msg = $cltarefa->erro_msg;
      }
      
      if($sqlerro == false) {
        $result = $cltarefasituacao->sql_record($cltarefasituacao->sql_query(null,"at47_sequencial",null,"at47_tarefa=" . $cltarefalog->at43_tarefa));
        if ($cltarefasituacao->numrows > 0) {
          db_fieldsmemory($result,0);
          $cltarefasituacao->at47_sequencial = $at47_sequencial;
          if ($cltarefalog->at43_progresso == 100 ) {
            $cltarefasituacao->at47_situacao = 3;
          }else{
            $cltarefasituacao->at47_situacao = $at48_situacao;	
          }
          $cltarefasituacao->alterar($at47_sequencial);
          if ($cltarefasituacao->erro_status == 0) {
            $sqlerro  = true;
            $erro_msg = $cltarefasituacao->erro_msg;
          }
        }
      }
    }
    
  } else {
    echo "<script>top.corpo.iframe_tarefalog.location.href='ate1_tarefalogand002.php?a=4&at43_tarefa=".@$at43_tarefa."&xxx=3&at43_usuario=".@$at43_usuario."'</script>";
  }

  db_fim_transacao($sqlerro);
}else if(isset($excluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $cltarefalogclientes->excluir(null,' at68_codmov = '.$at43_sequencial);
    
    if ($cltarefalogclientes->erro_status == 0) {
      $sqlerro = true;
      $erro_msg = $cltarefalogclientes->erro_msg;
      echo "<script> alert('$erro_msg'); </script>";
    }
    
    $cltarefalogitem->excluir(null,' at66_codmov = '.$at43_sequencial);
    
    if ($cltarefalogitem->erro_status == 0) {
      $sqlerro = true;
      $erro_msg = $cltarefalogitem->erro_msg;
      echo "<script> alert('$erro_msg'); </script>";
    }
    
    
    $cltarefalogsituacao->at48_tarefalog = $at43_sequencial;
    $cltarefalogsituacao->excluir(null,"at48_tarefalog=$at43_sequencial");
    
    if($cltarefalogsituacao->erro_status==0){
      $sqlerro=true;
    } 
    $erro_msg = $cltarefalogsituacao->erro_msg; 
    
    $cltarefalog->excluir($at43_sequencial);
    if($cltarefalog->erro_status==0){
      $sqlerro=true;
    }
    else {
      echo "<script>top.corpo.iframe_tarefalog.location.href='ate1_tarefalogand002.php?a=5&at43_tarefa=".@$at43_tarefa."&xxx=4&at43_usuario=".@$at43_usuario."'</script>";
    } 
    $erro_msg = $cltarefalog->erro_msg; 
    
    db_fim_transacao($sqlerro);
  }
}else if(isset($opcao)){
  $result = $cltarefalog->sql_record($cltarefalog->sql_query($at43_sequencial));
  if($result!=false && $cltarefalog->numrows>0){
    db_fieldsmemory($result,0);
  }
  
  $result = $cltarefalogsituacao->sql_record($cltarefalogsituacao->sql_query(null,"*",null,"at48_tarefalog=$at43_sequencial"));
  if($cltarefalogsituacao->numrows > 0) {
    db_fieldsmemory($result,0);
  } 

  
  $itens_clientes_escolhidos = "";
  $result = $cltarefalogclientes->sql_record($cltarefalogclientes->sql_query(null,"at68_codcli",null,"at68_codmov=$at43_sequencial"));
  if($cltarefalogclientes->numrows>0){
    $separador = "-";
    for($i=0;$i<$cltarefalogclientes->numrows;$i++){
      db_fieldsmemory($result,$i);
      $itens_clientes_escolhidos .= $separador.$at68_codcli;
      $separador = "-";
    }
    //$itens_clientes_escolhidos .= "-";
  }
  $itens_menu_escolhidos = "";
  $result = $cltarefalogitem->sql_record($cltarefalogitem->sql_query_file(null,"at66_id_item",null,"at66_codmov=$at43_sequencial"));
  if($cltarefalogitem->numrows>0){
    for($i=0;$i<$cltarefalogitem->numrows;$i++){
      db_fieldsmemory($result,$i);
      $itens_menu_escolhidos .= "-".$at66_id_item;
    }
  }

} else {

  $itens_clientes_escolhidos = "";
  $result = $clclientes->sql_record($clclientes->sql_query_file(null,"at01_codcli","at01_nomecli","at01_ativo is true"));
  if($clclientes->numrows>0){
    $separador = "-";
    for($i=0;$i<$clclientes->numrows;$i++){
      db_fieldsmemory($result,$i);
      $itens_clientes_escolhidos .= $separador.$at01_codcli;
      $separador = "-";
    }
  }

}

//echo "<br><pre>$itens_clientes_escolhidos</pre><br>";


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
include("forms/db_frmcontarefalogand.php");
?>
</center>
</td>
</tr>
</table>
</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
	
	if ($sqlerro == true) {
		$db_opcao=1;
	}
  
  db_msgbox($erro_msg);
  if($cltarefalog->erro_campo!=""){
    echo "<script> document.form1.".$cltarefalog->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$cltarefalog->erro_campo.".focus();</script>";
  }
}
?>