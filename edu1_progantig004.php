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
require("libs/db_stdlibwebseller.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_progconfig_classe.php");
include("classes/db_progmatricula_classe.php");
include("classes/db_proginterrompe_classe.php");
include("classes/db_progfalta_classe.php");
include("classes/db_progsuspdisc_classe.php");
include("classes/db_progadvert_classe.php");
include("dbforms/db_funcoes.php");
$ed113_d_data_dia = date("d",db_getsession("DB_datausu"));
$ed113_d_data_mes = date("m",db_getsession("DB_datausu"));
$ed113_d_data_ano = date("Y",db_getsession("DB_datausu"));
db_postmemory($HTTP_POST_VARS);
$clprogconfig = new cl_progconfig;
$clprogmatricula = new cl_progmatricula;
$clproginterrompe = new cl_proginterrompe;
$clprogfalta = new cl_progfalta;
$clprogsuspdisc = new cl_progsuspdisc;
$clprogadvert = new cl_progadvert;
$result = $clprogconfig->sql_record($clprogconfig->sql_query("","*","",""));
db_fieldsmemory($result,0);
$result = $clprogmatricula->sql_record($clprogmatricula->sql_query("","ed112_i_rhpessoal",""," ed112_i_codigo = $matricula"));
db_fieldsmemory($result,0);
if(isset($interromper)){
 //muda situacao da atual matricula para I-Interrompida
 db_inicio_transacao();
 $db_opcao = 2;
 $clprogmatricula->ed112_c_situacao = "I";
 $clprogmatricula->ed112_d_datafinal = date("Y-m-d");
 $clprogmatricula->ed112_i_usuario = db_getsession("DB_id_usuario");
 $clprogmatricula->ed112_i_codigo = $matricula;
 $clprogmatricula->alterar($matricula);
 db_fim_transacao();
 $result = $clprogmatricula->sql_record($clprogmatricula->sql_query("","*",""," ed112_i_codigo = $matricula"));
 db_fieldsmemory($result,0);
 //Inclui novo registro para progressão com a data de inicio sendo a data de hoje
 db_inicio_transacao();
 $clprogmatricula->ed112_i_rhpessoal = $ed112_i_rhpessoal;
 $clprogmatricula->ed112_i_progclasse = $ed112_i_progclasse;
 $clprogmatricula->ed112_i_nivel = $ed112_i_nivel;
 $clprogmatricula->ed112_i_usuario = db_getsession("DB_id_usuario");
 $clprogmatricula->ed112_d_database = $ed112_d_database;
 $clprogmatricula->ed112_d_datainicio = date("Y-m-d");
 $clprogmatricula->ed112_d_datafinal = null;
 $clprogmatricula->ed112_c_dedicacao = $ed112_c_dedicacao;
 $clprogmatricula->ed112_c_classeesp = $ed112_c_classeesp;
 $clprogmatricula->ed112_c_situacao = "A";
 $clprogmatricula->incluir(null);
 db_fim_transacao();
 //Inclui registro de interrupção na tabela proginterrompe informando o motivo
 db_inicio_transacao();
 $clproginterrompe->ed123_i_progmatricula = $matricula;
 $clproginterrompe->ed123_i_usuario = db_getsession("DB_id_usuario");
 $clproginterrompe->ed123_d_data = date("Y-m-d");
 $clproginterrompe->ed123_t_motivo = $motivo;
 $clproginterrompe->incluir(null);
 db_fim_transacao();
 ?>
  <script>
   parent.document.form1.ed113_i_progmatricula.value = "";
   parent.document.form1.ed112_i_rhpessoal.value = "";
   parent.document.form1.z01_nome.value = "";
   parent.document.form1.ed113_i_ano.value = "";
   parent.document.form1.ed113_d_data_dia.value = "";
   parent.document.form1.ed113_d_data_mes.value = "";
   parent.document.form1.ed113_d_data_ano.value = "";
   alert("Interrupção efetuada com sucesso!");
  </script>
 <?
 exit;
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name="form2" method="post" action="">
<table align="center" border="0" cellpadding="0" cellspacing="0">
 <tr>
  <td align="center">
   <br>
   <?
   $erro = false;
   $motivo = "";
   $result1 = $clprogfalta->sql_record($clprogfalta->sql_query("","count(*) as qtdfalta",""," ed118_i_progmatricula = $matricula AND extract(year from ed118_d_data) = '$ano' AND ed118_c_abonada = 'N'"));
   db_fieldsmemory($result1,0);
   if($qtdfalta>=$ed110_i_numfaltas && $ed110_i_numfaltas>0){
    $motivo .= "Matrícula $ed112_i_rhpessoal teve $qtdfalta falta(s) não justificada(s) no ano de $ano.<br>";
    $erro = true;
   }
   $result1 = $clprogsuspdisc->sql_record($clprogsuspdisc->sql_query("","count(*) as qtdsusp",""," ed119_i_progmatricula = $matricula AND extract(year from ed119_d_data) = '$ano'"));
   db_fieldsmemory($result1,0);
   if($qtdsusp>=$ed110_i_numsuspdisc && $ed110_i_numsuspdisc>0){
    $motivo .= "Matrícula $ed112_i_rhpessoal teve $qtdsusp suspensão disciplinar no ano de $ano.<br>";
    $erro = true;
   }
   $result1 = $clprogadvert->sql_record($clprogadvert->sql_query("","count(*) as qtdadvert",""," ed120_i_progmatricula = $matricula AND extract(year from ed120_d_data) = '$ano'"));
   db_fieldsmemory($result1,0);
   if($qtdadvert>=$ed110_i_numadvert && $ed110_i_numadvert>0){
    $motivo .= "Matrícula $ed112_i_rhpessoal teve $qtdadvert penalidade(s) de advertência no ano de $ano.<br>";
    $erro = true;
   }
   if($erro==true){
    echo $motivo;
    ?>
    <input type="button" name="interromper" value="Interromper Contagem de Tempo" onclick="js_interrupcao(<?=$matricula?>,<?=$ano?>,'<?=$motivo?>')">
    <script>parent.document.form1.db_opcao.disabled = true;</script>
    <?
   }else{
    ?>
    <script>parent.document.form1.db_opcao.disabled = false;</script>
    <?
   }
   ?>
  </td>
 </tr>
</table>
</form>
</body>
</html>
<script>
 function js_interrupcao(matricula,ano,motivo){
  if(confirm("Confirmar interrupção de contagem de tempo para a matrícula n° <?=$ed112_i_rhpessoal?>?")){
   location.href = "?interromper&matricula="+matricula+"&ano="+ano+"&motivo="+motivo;
  }
 }
</script>