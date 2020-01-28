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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_calendario_classe.php");
include("classes/db_periodocalendario_classe.php");
include("classes/db_periodoavaliacao_classe.php");
include("classes/db_regencia_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clcalendario = new cl_calendario;
$clperiodocalendario = new cl_periodocalendario;
$clperiodoavaliacao = new cl_periodoavaliacao;
$clregencia = new cl_regencia;
$db_opcao = 1;
$db_opcao1 = 1;
$db_botao = true;
$erro_data = false;
if(isset($incluir)){
 $data_inicio_dig = $ed53_d_inicio_ano."-".$ed53_d_inicio_mes."-".$ed53_d_inicio_dia;
 $data_fim_dig = $ed53_d_fim_ano."-".$ed53_d_fim_mes."-".$ed53_d_fim_dia;
 $sql2 = $clperiodoavaliacao->sql_query("","ed09_i_sequencia,ed09_c_somach",""," ed09_i_codigo = $ed53_i_periodoavaliacao");
 $result2 = $clperiodoavaliacao->sql_record($sql2);
 db_fieldsmemory($result2,0);
 if($ed09_c_somach=="N"){
  $sql = $clcalendario->sql_query("","ed52_d_inicio,ed52_d_resultfinal as ed52_d_fim",""," ed52_i_codigo = $ed53_i_calendario");
  $result = $clcalendario->sql_record($sql);
  db_fieldsmemory($result,0);
 }else{
  $sql = $clcalendario->sql_query("","ed52_d_inicio,ed52_d_fim",""," ed52_i_codigo = $ed53_i_calendario");
  $result = $clcalendario->sql_record($sql);
  db_fieldsmemory($result,0);
 }
 if($data_inicio_dig<$ed52_d_inicio){
  ?><script>alert("Data inicial de <?=$ed09_c_descr?> é anterior a data inicial das aulas do calendário <?=$ed52_c_descr?>!");</script><?
  $erro_data = true;
  $campo_erro = "inicio";
 }elseif($data_fim_dig>$ed52_d_fim){
  ?><script>alert("Data final de <?=$ed09_c_descr?> é posterior a data final das aulas do calendário <?=$ed52_c_descr?>!");</script><?
  $erro_data = true;
  $campo_erro = "fim";
 }elseif($data_inicio_dig>$ed52_d_fim){
  ?><script>alert("Data inicial de <?=$ed09_c_descr?> é posterior a data final das aulas do calendário <?=$ed52_c_descr?>!");</script><?
  $erro_data = true;
  $campo_erro = "inicio";
 }elseif($data_fim_dig<$ed52_d_inicio){
  ?><script>alert("Data final de <?=$ed09_c_descr?> é anterior a data inicial das aulas do calendário <?=$ed52_c_descr?>!");</script><?
  $erro_data = true;
  $campo_erro = "fim";
 }elseif($data_inicio_dig>$data_fim_dig){
  ?><script>alert("Data final de <?=$ed09_c_descr?> é anterior a data inicial!");</script><?
  $erro_data = true;
  $campo_erro = "fim";
 }else{
  $sql1 = $clperiodocalendario->sql_query("","ed53_d_inicio as inicio,ed53_d_fim as fim,ed09_c_descr as existente",""," ed53_i_calendario = $ed53_i_calendario and ed09_i_sequencia < $ed09_i_sequencia");
  $result1 = $clperiodocalendario->sql_record($sql1);
  if($clperiodocalendario->numrows>0){
   db_fieldsmemory($result1,0);
   if($data_inicio_dig<=@$fim){
    ?><script>alert("Data inicial de <?=$ed09_c_descr?> é anterior ou igual a data final do <?=$existente?>!");</script><?
    $erro_data = true;
    $campo_erro = "inicio";
   }
  }
  $sql3 = $clperiodocalendario->sql_query("","ed53_d_inicio as inicio,ed53_d_fim as fim,ed09_c_descr as existente",""," ed53_i_calendario = $ed53_i_calendario and ed09_i_sequencia > $ed09_i_sequencia");
  $result3 = $clperiodocalendario->sql_record($sql3);
  if($clperiodocalendario->numrows>0){
   db_fieldsmemory($result3,0);
   if($data_fim_dig>=@$inicio){
    ?><script>alert("Data final de <?=$ed09_c_descr?> é posterior ou igual a data inicial do <?=$existente?>!");</script><?
    $erro_data = true;
    $campo_erro = "fim";
   }
  }
  if(@$erro_data==false){
   db_inicio_transacao();
   $clperiodoescola->ed53_d_inicio = $data_inicio_dig;
   $clperiodoescola->ed53_d_fim = $data_fim_dig;
   $clperiodocalendario->incluir($ed53_i_codigo);
   db_fim_transacao();
  }
 }
}
if(isset($alterar)){
 $data_inicio_dig = $ed53_d_inicio_ano."-".$ed53_d_inicio_mes."-".$ed53_d_inicio_dia;
 $data_fim_dig = $ed53_d_fim_ano."-".$ed53_d_fim_mes."-".$ed53_d_fim_dia;
 $sql2 = $clperiodoavaliacao->sql_query("","ed09_i_sequencia,ed09_c_somach",""," ed09_i_codigo = $ed53_i_periodoavaliacao");
 $result2 = $clperiodoavaliacao->sql_record($sql2);
 db_fieldsmemory($result2,0);
 if($ed09_c_somach=="N"){
  $sql = $clcalendario->sql_query("","ed52_d_inicio,ed52_d_resultfinal as ed52_d_fim",""," ed52_i_codigo = $ed53_i_calendario");
  $result = $clcalendario->sql_record($sql);
  db_fieldsmemory($result,0);
 }else{
  $sql = $clcalendario->sql_query("","ed52_d_inicio,ed52_d_fim",""," ed52_i_codigo = $ed53_i_calendario");
  $result = $clcalendario->sql_record($sql);
  db_fieldsmemory($result,0);
 }
 if($data_inicio_dig<$ed52_d_inicio){
  ?><script>alert("Data inicial de <?=$ed09_c_descr?> é anterior a data inicial das aulas do calendário <?=$ed52_c_descr?>!");</script><?
  $erro_data = true;
  $campo_erro = "inicio";
 }elseif($data_fim_dig>$ed52_d_fim){
  ?><script>alert("Data final de <?=$ed09_c_descr?> é posterior a data final das aulas do calendário <?=$ed52_c_descr?>!");</script><?
  $erro_data = true;
  $campo_erro = "fim";
 }elseif($data_inicio_dig>$ed52_d_fim){
  ?><script>alert("Data inicial de <?=$ed09_c_descr?> é posterior a data final das aulas do calendário <?=$ed52_c_descr?>!");</script><?
  $erro_data = true;
  $campo_erro = "inicio";
 }elseif($data_fim_dig<$ed52_d_inicio){
  ?><script>alert("Data final de <?=$ed09_c_descr?> é anterior a data inicial das aulas do calendário <?=$ed52_c_descr?>!");</script><?
  $erro_data = true;
  $campo_erro = "fim";
 }elseif($data_inicio_dig>$data_fim_dig){
  ?><script>alert("Data final de <?=$ed09_c_descr?> é menor a data inicial!");</script><?
  $erro_data = true;
  $campo_erro = "fim";
 }else{
  $sql1 = $clperiodocalendario->sql_query("","ed53_d_inicio as inicio,ed53_d_fim as fim,ed09_c_descr as existente",""," ed53_i_calendario = $ed53_i_calendario and ed09_i_sequencia < $ed09_i_sequencia");
  $result1 = $clperiodocalendario->sql_record($sql1);
  if($clperiodocalendario->numrows>0){
   db_fieldsmemory($result1,0);
   if($data_inicio_dig<=@$fim){
    ?><script>alert("Data inicial de <?=$ed09_c_descr?> é anterior ou igual a data final do <?=$existente?>!");</script><?
    $erro_data = true;
    $campo_erro = "inicio";
   }
  }
  $sql3 = $clperiodocalendario->sql_query("","ed53_d_inicio as inicio,ed53_d_fim as fim,ed09_c_descr as existente",""," ed53_i_calendario = $ed53_i_calendario and ed09_i_sequencia > $ed09_i_sequencia");
  $result3 = $clperiodocalendario->sql_record($sql3);
  if($clperiodocalendario->numrows>0){
   db_fieldsmemory($result3,0);
   if($data_fim_dig>=@$inicio){
    ?><script>alert("Data final de <?=$ed09_c_descr?> é posterior ou igual a data inicial do <?=$existente?>!");</script><?
    $erro_data = true;
    $campo_erro = "fim";
   }
  }
  if(@$erro_data==false){
   db_inicio_transacao();
   $db_opcao = 2;
   $clperiodoescola->ed53_d_inicio = $data_inicio_dig;
   $clperiodoescola->ed53_d_fim = $data_fim_dig;
   $clperiodocalendario->alterar($ed53_i_codigo);
   db_fim_transacao();
  }
 }
}
if(isset($excluir)){
  db_inicio_transacao();
  $db_opcao = 3;
  $clperiodocalendario->excluir($ed53_i_codigo);
  db_fim_transacao();
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Períodos de Avaliação do Calendário <?=$ed52_c_descr?></b></legend>
    <?include("forms/db_frmperiodocalendario.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<?
if(@$erro_data==true){
 echo "<script> document.form1.ed53_d_".@$campo_erro."_dia.style.backgroundColor='#99A9AE';</script>";
 echo "<script> document.form1.ed53_d_".@$campo_erro."_mes.style.backgroundColor='#99A9AE';</script>";
 echo "<script> document.form1.ed53_d_".@$campo_erro."_ano.style.backgroundColor='#99A9AE';</script>";
 echo "<script> document.form1.ed53_d_".@$campo_erro."_dia.focus();</script>";
}
if(isset($incluir)){
 if(@$erro_data==false){
  if($clperiodocalendario->erro_status=="0"){
   $clperiodocalendario->erro(true,false);
   $db_botao=true;
   echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
   if($clperiodocalendario->erro_campo!=""){
    echo "<script> document.form1.".$clperiodocalendario->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$clperiodocalendario->erro_campo.".focus();</script>";
   };
  }else{
   $sql1 = $clperiodocalendario->sql_query("","sum(ed53_i_diasletivos) as dias,sum(ed53_i_semletivas) as semanas",""," ed53_i_calendario = $ed53_i_calendario AND ed09_c_somach = 'S'");
   $result1 = $clperiodocalendario->sql_record($sql1);
   if($clperiodocalendario->numrows>0){
    db_fieldsmemory($result1,0);
    $sql2 = "UPDATE calendario SET
              ed52_i_diasletivos = $dias,
              ed52_i_semletivas = $semanas
             WHERE ed52_i_codigo = $ed53_i_calendario
            ";
    $query2 = pg_query($sql2);
    ?>
    <script>
     top.corpo.iframe_a1.location.href='edu1_calendario002.php?chavepesquisa=<?=$ed53_i_calendario?>';
    </script>
    <?
    $clperiodocalendario->erro(true,true);
   }
  };
 }
};
if(isset($alterar)){
 if(@$erro_data==false){
  if($clperiodocalendario->erro_status=="0"){
    $clperiodocalendario->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clperiodocalendario->erro_campo!=""){
      echo "<script> document.form1.".$clperiodocalendario->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clperiodocalendario->erro_campo.".focus();</script>";
    };
  }else{
   $sql1 = $clperiodocalendario->sql_query("","sum(ed53_i_diasletivos) as dias,sum(ed53_i_semletivas) as semanas",""," ed53_i_calendario = $ed53_i_calendario AND ed09_c_somach = 'S'");
   $result1 = $clperiodocalendario->sql_record($sql1);
   if($clperiodocalendario->numrows>0){
    db_fieldsmemory($result1,0);
    $sql2 = "UPDATE calendario SET
              ed52_i_diasletivos = $dias,
              ed52_i_semletivas = $semanas
             WHERE ed52_i_codigo = $ed53_i_calendario
            ";
    $query2 = pg_query($sql2);
    ?>
    <script>
     top.corpo.iframe_a1.location.href='edu1_calendario002.php?chavepesquisa=<?=$ed53_i_calendario?>';
    </script>
    <?
    $clperiodocalendario->erro(true,true);
   }
  };
 }
};
if(isset($excluir)){
  if($clperiodocalendario->erro_status=="0"){
    $clperiodocalendario->erro(true,false);
  }else{
   $sql1 = $clperiodocalendario->sql_query("","sum(ed53_i_diasletivos) as dias,sum(ed53_i_semletivas) as semanas",""," ed53_i_calendario = $ed53_i_calendario AND ed09_c_somach = 'S'");
   $result1 = $clperiodocalendario->sql_record($sql1);
   if($clperiodocalendario->numrows>0){
    db_fieldsmemory($result1,0);
    if($dias==""){
     $dias = 0;
     $semanas = 0;
    }
    $sql2 = "UPDATE calendario SET
              ed52_i_diasletivos = $dias,
              ed52_i_semletivas = $semanas
             WHERE ed52_i_codigo = $ed53_i_calendario
            ";
    $query2 = pg_query($sql2);
    ?>
    <script>
     top.corpo.iframe_a1.location.href='edu1_calendario002.php?chavepesquisa=<?=$ed53_i_calendario?>';
    </script>
    <?
    $clperiodocalendario->erro(true,true);
   }
  };
};
if(isset($cancelar)){
 echo "<script>location.href='".$clperiodocalendario->pagina_retorno."'</script>";
}
?>