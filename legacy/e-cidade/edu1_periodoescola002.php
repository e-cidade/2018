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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_app.utils.php");
include("classes/db_periodoescola_classe.php");
include("classes/db_periodoaula_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clperiodoescola = new cl_periodoescola;
$clperiodoaula = new cl_periodoaula;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar)){
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
    $result_pos = $clperiodoescola->sql_record($clperiodoescola->sql_query("","ed17_h_inicio,ed17_h_fim,ed08_c_descr","ed15_i_sequencia,ed08_i_sequencia desc","ed17_i_escola = $ed17_i_escola and ed17_i_turno = $ed17_i_turno and ed08_i_sequencia < $seq_atual"));
    if($clperiodoescola->numrows>0){
     for($x=0;$x<$clperiodoescola->numrows;$x++){
      db_fieldsmemory($result_pos,$x);
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
   }elseif($seq_atual>1 and $seq_atual<$max){
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
    $clperiodoescola->ed17_h_inicio = $ed17_h_inicio_dig;
    $clperiodoescola->ed17_h_fim = $ed17_h_fim_dig;
    $db_opcao = 2;
    $clperiodoescola->alterar($ed17_i_codigo);
    db_fim_transacao();
   }
  }
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $result = $clperiodoescola->sql_record($clperiodoescola->sql_query($chavepesquisa));
   db_fieldsmemory($result,0);
   $db_botao = true;
}
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <br>
    <center>
    <fieldset style="width:90%"><legend><b>Alteração de Períodos da Escola</b></legend>
        <?
        include("forms/db_frmperiodoescola.php");
        ?>
    </fieldset>
    </center>
        </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if($erro_horario==true){
 ?><script>
    document.form1.<?=@$campo?>.style.backgroundColor='#99A9AE';
    document.form1.<?=@$campo?>.focus();
   </script><?
}
if(isset($alterar)){
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
};
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>