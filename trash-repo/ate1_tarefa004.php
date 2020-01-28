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

define("TAREFA",true);

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_tarefa_lanc_classe.php");
include("classes/db_tarefa_aut_classe.php");
include("classes/db_tarefaparam_classe.php");
include("classes/db_atendimento_classe.php");
include("classes/db_tecnico_classe.php");
include("classes/db_tarefa_classe.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_tarefamodulo_classe.php");
include("classes/db_tarefaproced_classe.php");
include("classes/db_tarefasituacao_classe.php");
include("classes/db_tarefausu_classe.php");
include("classes/db_atenditem_classe.php");
include("classes/db_tarefaitem_classe.php");
include("classes/db_tarefaenvol_classe.php");
include("classes/db_tarefamotivo_classe.php");
include("classes/db_tarefaclientes_classe.php");
include("classes/db_tarefa_agenda_classe.php");
include("classes/db_clientesenvol_classe.php");
include("classes/db_tarefasyscadproced_classe.php");
//include("classes/db_db_syscadproced_classe.php");
$cltarefa         = new cl_tarefa;
$cltarefamodulo   = new cl_tarefamodulo;
$cltarefaproced   = new cl_tarefaproced;
$cltarefaparam    = new cl_tarefaparam;
$cltarefasituacao = new cl_tarefasituacao;
$clatenditem      = new cl_atenditem;
$cltarefaitem     = new cl_tarefaitem;
$cltarefausu      = new cl_tarefausu;
$cltarefaenvol    = new cl_tarefaenvol;
$cltarefamotivo   = new cl_tarefamotivo;
$cltarefaclientes = new cl_tarefaclientes;
$cltecnico        = new cl_tecnico;
$cltarefa_agenda  = new cl_tarefa_agenda;
$cltarefa_aut     = new cl_tarefa_aut;
$cltarefa_lanc    = new cl_tarefa_lanc;
$clclientesenvol  = new cl_clientesenvol;
$cl_tarefasyscadproced = new cl_tarefasyscadproced;
//$cl_db_syscadproced = new cl_db_syscadproced;

db_postmemory($HTTP_POST_VARS);
//db_msgbox('004');
$db_opcao = 11;
$db_botao = true;

// ########################## INCLUIR #############################################
if(isset($incluir)) {
  $sqlerro=false;
  db_inicio_transacao();
  
  $result = $cltarefa->sql_record($cltarefa->sql_query_envol(null,"at40_diaini,at40_diafim,at40_horainidia,at40_horafim","at40_sequencial,at45_usuario","at40_progresso < 100 and at45_usuario=$at40_responsavel"));
  if($cltarefa->numrows > 0) {
    if ($at40_diaini_ano != "") {
      $cltarefa->at40_diaini = $at40_diaini_ano . "-" . db_formatar($at40_diaini_mes,'s','0',2,'e',0) . "-" . db_formatar($at40_diaini_dia,'s','0',2,'e',0);
    } else {
      $cltarefa->at40_diaini = null;
    }
    if ($at40_diafim_ano != "") {
      $cltarefa->at40_diafim = $at40_diafim_ano . "-" . db_formatar($at40_diafim_mes,'s','0',2,'e',0) . "-" . db_formatar($at40_diafim_dia,'s','0',2,'e',0);
    } else {
      $cltarefa->at40_diafim = null;
    }
    
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
  
  if($sqlerro == false) {
    if($at40_previsao=="") {
      $cltarefa->at40_previsao = 0;
      $at40_previsao           = 0;
    }
    
    $cltarefa->incluir($at40_sequencial);
    if($cltarefa->erro_status==0) {
      $sqlerro=true;
      $erro_msg = $cltarefa->erro_msg;
    }
  }
  
  if($sqlerro == false) { 
    
    if ($codmod != "0") {
      $cltarefamodulo->at49_modulo = $codmod;
      $cltarefamodulo->at49_tarefa = $cltarefa->at40_sequencial;
      $cltarefamodulo->incluir(null);
    }
    
    if (isset($at41_proced) and $at41_proced > 0 and $sqlerro == false) {
      
      $cltarefaproced->incluir($cltarefa->at40_sequencial,$at41_proced);
      
      if($cltarefaproced->erro_status==0) {
        $sqlerro = true;
        $erro_msg = $cltarefaproced->erro_msg;
      }
      
    }
    
    if ($sqlerro == false) {
      
      $cltarefasituacao->at47_situacao = $at47_situacao;
      $cltarefasituacao->at47_tarefa   = $cltarefa->at40_sequencial;
      $cltarefasituacao->incluir(null);
      if($cltarefasituacao->erro_status==0) {
        $sqlerro = true;
        $erro_msg = $cltarefasituacao->erro_msg;
      }
      
      if (isset($at05_seq) and ($at05_seq != "")) {
        $cltarefaitem->at44_atenditem = $at05_seq;
        $cltarefaitem->incluir($cltarefa->at40_sequencial);
        if($cltarefaitem->erro_status==0) {
          $sqlerro = true;
          $erro_msg = $cltarefaitem->erro_msg;
        }
      }
      
      $cltarefausu->at42_tarefa  = $cltarefa->at40_sequencial;
      $cltarefausu->at42_usuario = $cltarefa->at40_responsavel;
      $cltarefausu->at42_perc    = 100;
      $cltarefausu->incluir(null);
      if($cltarefausu->erro_status==0) {
        $sqlerro = true;
        $erro_msg = $cltarefausu->erro_msg;
      }
      
      if ($cltarefa->at40_responsavel != db_getsession("DB_id_usuario")) {
        $cltarefausu->at42_tarefa  = $cltarefa->at40_sequencial;
        $cltarefausu->at42_usuario = db_getsession("DB_id_usuario");
        $cltarefausu->at42_perc    = 10;
        $cltarefausu->incluir(null);
        if($cltarefausu->erro_status==0) {
          $sqlerro = true;
          $erro_msg = $cltarefausu->erro_msg;
        }
      }
      
      $cltarefaenvol->at45_tarefa  = $cltarefa->at40_sequencial;
      $cltarefaenvol->at45_usuario = $cltarefa->at40_responsavel;
      $cltarefaenvol->at45_perc    = 100;
      $cltarefaenvol->incluir(null);
      if($cltarefaenvol->erro_status==0) {
        $sqlerro = true;
        $erro_msg = $cltarefaenvol->erro_msg;
      }
      
      if ($cltarefa->at40_responsavel != db_getsession("DB_id_usuario")) {
        $cltarefaenvol->at45_tarefa  = $cltarefa->at40_sequencial;
        $cltarefaenvol->at45_usuario = db_getsession("DB_id_usuario");
        $cltarefaenvol->at45_perc    = 10;
        $cltarefaenvol->incluir(null);
        if($cltarefaenvol->erro_status==0) {
          $sqlerro = true;
          $erro_msg = $cltarefaenvol->erro_msg;
        }		    
      }
      
      $cltarefamotivo->at55_tarefa = $cltarefa->at40_sequencial;
      $cltarefamotivo->at55_motivo = $at54_sequencial;
      $cltarefamotivo->incluir(null);
      if($cltarefamotivo->erro_status==0) {
        $sqlerro = true;
        $erro_msg = $cltarefamotivo->erro_msg;
      }
      
      
      if(isset($at05_seq) and ($at05_seq != "")) {
        $result = $clatenditem->sql_record($clatenditem->sql_query($at05_seq,"at01_codcli","at05_seq",""));
        if($clatenditem->numrows>0) { 	
          db_fieldsmemory($result,0);
          
          $cltarefaclientes->at70_tarefa  = $cltarefa->at40_sequencial;
          $cltarefaclientes->at70_cliente = $at01_codcli;
          $cltarefaclientes->incluir(null);
          if($cltarefaclientes->erro_status==0) {
            $sqlerro = true;
            $erro_msg = $cltarefaclientes->erro_msg;
          }
          $resultenvol = $clclientesenvol->sql_record($clclientesenvol->sql_query(null, "at57_usuario, at57_percpadrao",null, " at57_cliente = $at01_codcli"));
          if ($clclientesenvol->numrows > 0) {
            
            for ($usuario = 0; $usuario < $clclientesenvol->numrows; $usuario++) {
              db_fieldsmemory($resultenvol, $usuario);
              
              $resultprocura = $cltarefaenvol->sql_record($cltarefaenvol->sql_query(null, "at45_tarefa",null, " at45_tarefa = " . $cltarefa->at40_sequencial . " and at45_usuario = $at57_usuario"));
              if ($cltarefaenvol->numrows == 0) {
                $cltarefaenvol->at45_tarefa 	= $cltarefa->at40_sequencial;
                $cltarefaenvol->at45_usuario 	= $at57_usuario;
                $cltarefaenvol->at45_perc	= $at57_percpadrao;
                $cltarefaenvol->incluir(null);
                if ($cltarefaenvol->erro_status == 0) {
                  $sqlerro = true;
                  $erro_msg = $cltarefaenvol->erro_msg;
                  exit;
                }
              }
              
            }
            
          }
          
        }
        
      }
      
      if($sqlerro==false) {
        // Inclui um lançamento de quem esta cadastrando a tarefa para mostrar o criador da tarefa na consulta de tarefas após liberação
        $cltarefa_lanc->at36_data    = date("Y", db_getsession("DB_datausu"))."-".
        date("m", db_getsession("DB_datausu"))."-".
        date("d", db_getsession("DB_datausu"));
        $cltarefa_lanc->at36_hora    = db_hora();
        $cltarefa_lanc->at36_ip      = db_getsession("DB_ip");
        $cltarefa_lanc->at36_tarefa  = $cltarefa->at40_sequencial;
        $cltarefa_lanc->at36_usuario = db_getsession("DB_id_usuario");
        $cltarefa_lanc->at36_tipo    = "I";
        $cltarefa_lanc->incluir(null);
        if($cltarefa_lanc->erro_status==0) {
          $sqlerro = true;
          $erro_msg = $cltarefa_lanc->erro_msg;
        }	  	  
      }
      
      if($sqlerro==false) {
        
        $sqlerro = $cltarefa_agenda->gera_agenda($cltarefaparam,$cltarefa,&$erro_msg);
        
        if (isset($at41_proced) and ($at41_proced == 9)) { // agenda de visitas - autorizada automatico
          if ($sqlerro == false) {
            $cltarefa->at40_autorizada = "true";
            $cltarefa->alterar($cltarefa->at40_sequencial);
            if($cltarefa->erro_status == 0) {
              $sqlerro = true;
              $erro_msg = $cltarefa->erro_msg;
            }
          }
        }
        
      }
      else {
        $erro_msg = $cltarefa_aut->erro_msg;
      }
    }
    if($sqlerro==false) {
      $cl_tarefasyscadproced->at37_tarefa = $cltarefa->at40_sequencial;
      $cl_tarefasyscadproced->at37_syscadproced = $codproced;
      $cl_tarefasyscadproced->incluir(null);
    }if($cl_tarefasyscadproced->erro_status == 0) {
      $sqlerro = true;
      $erro_msg = $cl_tarefasyscadproced->erro_msg;
    }     
    
  }
  
  db_fim_transacao($sqlerro);
  if($sqlerro == false) {
    $at40_sequencial= $cltarefa->at40_sequencial;
  }
  
  $db_opcao = 1;
  $db_botao = true;
  
}
// ######################### FIM DO INCLUIR ##############################
if(isset($funcao)) {
  $db_opcao = 1;
}
if(isset($at05_seq) and $at05_seq != "") {
  
  
  $sqlprod="select at29_syscadproced as codproced from atenditem inner join atenditemsyscadproced on at29_atenditem= at05_seq where at05_seq =$at05_seq ";
  
  $resultprod = $clatenditem->sql_record($sqlprod);
  db_fieldsmemory($resultprod,0);
  
  if(isset($codmod) && $codmod>=0){
    $moduloalt = $codmod;
    
  }
  $result = $clatenditem->sql_record($clatenditem->sql_query_mod($at05_seq));
  //	echo $clatenditem->sql_query_mod($at05_seq);
  
  db_fieldsmemory($result,0);
  if (isset($moduloalt)&&$moduloalt>0 &&$moduloalt!=$codmod){
    $codmod = $moduloalt;
    
  }
  $at40_descr  = $at05_solicitado;
  
  if(isset($codmod) and $codmod != "") {
    $at49_modulo = $codmod;
  }
  
  $result = $cltecnico->sql_record($cltecnico->sql_query($at05_codatend,null,"at03_id_usuario","at03_codatend",""));
  if($cltecnico->numrows > 0) {
    db_fieldsmemory($result,0);
    $at40_responsavel = $at03_id_usuario;
  }	
  
  $db_opcao = 1;
}

if(isset($db_opcao)&&$db_opcao==11&&$tipo=="I") {
  $db_opcao = 1;
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

include("forms/db_frmtarefa.php");
?>
</center>
</td>
</tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($cltarefa->erro_campo!=""){
      echo "<script> document.form1.".$cltarefa->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cltarefa->erro_campo.".focus();</script>";
    };
  }else{
    db_redireciona("ate1_tarefa005.php?liberaaba=true&at02_codatend=$at02_codatend&at05_seq=$at05_seq&chavepesquisa=$at40_sequencial&erro_horario=" . $erro_horario);
    //die("atend = $at02_codatend item = $at05_seq xxx $at05_codatend");
  }
}
if(isset($db_opcao)&&$db_opcao==11&&$tipo=="A") {
  //	echo $tipo; exit;
  echo "<script> js_pesquisaatend_item(); </script>";
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
        if($db_diaini <= db_formatar($cltarefa->at40_diafim,"d") ||	// Ex.: 01/02/2006 <= 03/02/2006 or 
        $db_diafim >= db_formatar($cltarefa->at40_diaini,"d")) { //      02/02/2006 >= 02/02/2006
          if($cltarefa->at40_horainidia <= $db_horafim &&
          $cltarefa->at40_horafim    >= $db_horaini) {
            $retorno = true;
            break;
          }
        }
        else {
          $retorno = false;
        }
      }
    }
  }
  
  return($retorno);
}

?>