<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
$clperiodoaula = new cl_periodoaula;
$clperiodoescola = new cl_periodoescola;
$db_opcao = 1;
$db_opcao2 = 1;
$db_botao = true;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <?php
    db_app::load("prototype.js, prototype.maskedinput.js");
  ?>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Períodos da Escola</b></legend>
    <?include("forms/db_frmperiodoescola.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed17_i_turno",true,1,"ed17_i_turno",true);
</script>
<?
if(isset($incluir)){
 $result_tur_per = $clperiodoescola->sql_record($clperiodoescola->sql_query("","*","","ed17_i_escola = $ed17_i_escola and ed17_i_turno = $ed17_i_turno and ed17_i_periodoaula = $ed17_i_periodoaula"));
 if($clperiodoescola->numrows>0){
  ?><script>alert("Período de Aula informado já esta cadastrado para este turno");</script><?
  $erro_horario = true;
  $campo = "ed17_i_periodoaula";
 }else{
  $erro_horario = false;
  $ed17_h_inicio_dig = $ed17_h_inicio;
  $ed17_h_fim_dig = $ed17_h_fim;
  $ed08_c_descr_dig = $ed08_c_descr;
  $ed17_i_periodoaula_dig = $ed17_i_periodoaula;
  $result_max = $clperiodoescola->sql_record($clperiodoescola->sql_query("","max(ed08_i_sequencia)","","ed17_i_escola = $ed17_i_escola and ed17_i_turno = $ed17_i_turno"));
  db_fieldsmemory($result_max,0);
  if($max!=""){
   $result_seq = $clperiodoaula->sql_record($clperiodoaula->sql_query("","ed08_i_sequencia as seq_atual","","ed08_i_codigo = $ed17_i_periodoaula"));
   db_fieldsmemory($result_seq,0);
   if($ed17_h_inicio_dig>$ed17_h_fim_dig){
    ?><script>alert("Hora inicial deve ser menor que a final!");</script><?
    $erro_horario = true;
    $db_opcao = 1;
    $db_botao = true;
    $campo = "ed17_h_inicio";
   }else{
    @$result_pos = $clperiodoescola->sql_record($clperiodoescola->sql_query("","ed17_h_inicio,ed17_h_fim,ed08_c_descr","ed15_i_sequencia,ed08_i_sequencia","ed17_i_escola = $ed17_i_escola and ed17_i_turno = $ed17_i_turno and ed08_i_sequencia < $seq_atual"));
    if($clperiodoescola->numrows>0){
     for($x=0;$x<$clperiodoescola->numrows;$x++){
      db_fieldsmemory($result_pos,$x);
      if($ed17_h_inicio_dig < $ed17_h_fim){
       ?><script>alert("Horário informado colide com o <?=$ed08_c_descr?> período!");</script><?
       $erro_horario = true;
       $db_opcao = 1;
       $db_botao = true;
       $campo = "ed17_h_inicio";
       break;
      }
     }
    }
   }
   if(@$erro_horario==true){
    $ed17_h_inicio = $ed17_h_inicio_dig;
    $ed17_h_fim = $ed17_h_fim_dig;
    $ed08_c_descr = $ed08_c_descr_dig;
   }else{
    db_inicio_transacao();
    $clperiodoescola->incluir($ed17_i_codigo);
    db_fim_transacao();
    if($clperiodoescola->erro_status=="0"){
     $clperiodoescola->erro(true,false);
     $db_botao=true;
     echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
     if($clperiodoescola->erro_campo!=""){
      echo "<script> document.form1.".$clperiodoescola->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clperiodoescola->erro_campo.".focus();</script>";
     };
    }else{
     $clperiodoescola->erro(true,true);
    };
   }
  }else{
   db_inicio_transacao();
   $clperiodoescola->incluir($ed17_i_codigo);
   db_fim_transacao();
   if($clperiodoescola->erro_status=="0"){
    $clperiodoescola->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clperiodoescola->erro_campo!=""){
     echo "<script> document.form1.".$clperiodoescola->erro_campo.".style.backgroundColor='#99A9AE';</script>";
     echo "<script> document.form1.".$clperiodoescola->erro_campo.".focus();</script>";
    };
   }else{
    $clperiodoescola->erro(true,true);
   };
  }
 }
}
if(isset($alterar)){
  $erro_horario = false;
  $ed17_h_inicio_dig = $ed17_h_inicio;
  $ed17_h_fim_dig = $ed17_h_fim;
  $ed08_c_descr_dig = $ed08_c_descr;
  $result_max = $clperiodoescola->sql_record($clperiodoescola->sql_query("","max(ed08_i_sequencia)","","ed17_i_escola = $ed17_i_escola and ed17_i_turno = $ed17_i_turno"));
  db_fieldsmemory($result_max,0);
  $result_seq = $clperiodoaula->sql_record($clperiodoaula->sql_query("","ed08_i_sequencia as seq_atual","","ed08_i_codigo = $ed17_i_periodoaula"));
  db_fieldsmemory($result_seq,0);
  if($ed17_h_inicio_dig>$ed17_h_fim_dig){
   ?><script>alert("Hora inicial deve ser menor que a final!");</script><?
   $erro_horario = true;
   $db_opcao = 2;
   $db_botao = true;
   $campo = "ed17_h_inicio";
  }else{
   if($seq_atual==1){
    $result_pos = $clperiodoescola->sql_record($clperiodoescola->sql_query("","ed17_h_inicio,ed17_h_fim,ed08_c_descr","ed15_i_sequencia,ed08_i_sequencia","ed17_i_escola = $ed17_i_escola and ed17_i_turno = $ed17_i_turno and ed08_i_sequencia > $seq_atual"));
    if($clperiodoescola->numrows>0){
     for($x=0;$x<$clperiodoescola->numrows;$x++){
      db_fieldsmemory($result_pos,$x);
      if($ed17_h_fim_dig > $ed17_h_inicio){
       ?><script>alert("Horário informado colide com o <?=$ed08_c_descr?> período!");</script><?
       $erro_horario = true;
       $db_opcao = 2;
       $db_botao = true;
       $campo = "ed17_h_fim";
       break;
      }
     }
    }
   }elseif($seq_atual==$max){
    $result_ant = $clperiodoescola->sql_record($clperiodoescola->sql_query("","ed17_h_inicio,ed17_h_fim,ed08_c_descr","ed15_i_sequencia,ed08_i_sequencia desc","ed17_i_escola = $ed17_i_escola and ed17_i_turno = $ed17_i_turno and ed08_i_sequencia < $seq_atual"));
    if($clperiodoescola->numrows>0){
     for($x=0;$x<$clperiodoescola->numrows;$x++){
      db_fieldsmemory($result_ant,$x);
      if($ed17_h_inicio_dig < $ed17_h_fim){
       ?><script>alert("Horário informado colide com o <?=$ed08_c_descr?> período!");</script><?
       $erro_horario = true;
       $db_opcao = 2;
       $db_botao = true;
       $campo = "ed17_h_inicio";
       break;
      }
     }
    }
   }else{
    $result_ant = $clperiodoescola->sql_record($clperiodoescola->sql_query("","ed17_h_inicio,ed17_h_fim,ed08_c_descr","ed15_i_sequencia,ed08_i_sequencia desc","ed17_i_escola = $ed17_i_escola and ed17_i_turno = $ed17_i_turno and ed08_i_sequencia < $seq_atual"));
    if($clperiodoescola->numrows>0){
     for($x=0;$x<$clperiodoescola->numrows;$x++){
      db_fieldsmemory($result_ant,$x);
      if($ed17_h_inicio_dig < $ed17_h_fim){
       ?><script>alert("Horário informado colide com o <?=$ed08_c_descr?> período!");</script><?
       $erro_horario = true;
       $db_opcao = 2;
       $db_botao = true;
       $campo = "ed17_h_inicio";
       break;
      }
     }
    }
    $result_pos = $clperiodoescola->sql_record($clperiodoescola->sql_query("","ed17_h_inicio,ed17_h_fim,ed08_c_descr","ed15_i_sequencia,ed08_i_sequencia","ed17_i_escola = $ed17_i_escola and ed17_i_turno = $ed17_i_turno and ed08_i_sequencia > $seq_atual"));
    if($clperiodoescola->numrows>0){
     for($x=0;$x<$clperiodoescola->numrows;$x++){
      db_fieldsmemory($result_pos,$x);
      if($ed17_h_fim_dig > $ed17_h_inicio){
       ?><script>alert("Horário informado colide com o <?=$ed08_c_descr?> período!");</script><?
       $erro_horario = true;
       $db_opcao = 2;
       $db_botao = true;
       $campo = "ed17_h_fim";
       break;
      }
     }
    }
   }
   if(@$erro_horario==true){
    $ed17_h_inicio = $ed17_h_inicio_dig;
    $ed17_h_fim = $ed17_h_fim_dig;
    $ed08_c_descr = $ed08_c_descr_dig;
   }
   if(@$erro_horario==false){
    db_inicio_transacao();
    //$clperiodoescola->ed17_h_inicio = $ed17_h_inicio_dig;
    //$clperiodoescola->ed17_h_fim = $ed17_h_fim_dig;
    $db_opcao = 2;
    $clperiodoescola->alterar($ed17_i_codigo);
    db_fim_transacao();
    if($clperiodoescola->erro_status=="0"){
     $clperiodoescola->erro(true,false);
     $db_botao=true;
     echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
     if($clperiodoescola->erro_campo!=""){
      echo "<script> document.form1.".$clperiodoescola->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clperiodoescola->erro_campo.".focus();</script>";
     };
    }else{
     $clperiodoescola->erro(true,true);
    };
   }
  }
}
if(isset($excluir)){
 db_inicio_transacao();
 $db_opcao = 3;
 $clperiodoescola->excluir($ed17_i_codigo);
 db_fim_transacao();
 if($clperiodoescola->erro_status=="0"){
  $clperiodoescola->erro(true,false);
 }else{
  $clperiodoescola->erro(true,true);
 };
}
if(@$erro_horario==true){
 ?>
 <script>
  document.form1.<?=@$campo?>.style.backgroundColor='#99A9AE';
  document.form1.<?=@$campo?>.focus();
 </script>
 <?
}
if(isset($cancelar)){
 echo "<script>location.href='".$clperiodoescola->pagina_retorno."'</script>";
}
?>