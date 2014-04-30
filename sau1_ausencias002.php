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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_ausencias_classe.php");
include("classes/db_agendamentos_classe.php");
include("classes/db_medicos_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clausencias = new cl_ausencias;
$clmedicos = new cl_medicos;
$clagendamentos = new cl_agendamentos;
$db_botao = true;
$db_opcao = 1;
if(isset($incluir)){
 $data_ausencia = $sd06_d_data_ano."-".$sd06_d_data_mes."-".$sd06_d_data_dia;
 $data2 = db_formatar($data_ausencia,'d');
 $result = $clagendamentos->sql_record($clagendamentos->sql_query("","sd23_i_codigo","",
 																" sd04_i_unidade = $sd06_i_unidade and 
 																sd04_i_medico = $sd06_i_medico and 
 																sd23_d_consulta = '$data_ausencia' and 
 																not exists ( select *
		            													from agendaconsultaanula
		            													where s114_i_agendaconsulta = sd23_i_codigo
		            												)
 																"));
 if($clagendamentos->numrows>0){
  db_fieldsmemory($result,0);
  db_msgbox("Médico $z01_nome já tem agendamentos para a data $data2. \\n Transfira os agendamentos desta data para depois cadastrar a ausência!");
 }else{
  db_inicio_transacao();
  $clausencias->incluir($sd06_i_codigo);
  db_fim_transacao();
 }
}
if(isset($alterar)){
 $data_ausencia = $sd06_d_data_ano."-".$sd06_d_data_mes."-".$sd06_d_data_dia;
 $data2 = db_formatar($data_ausencia,'d');
 $result = $clagendamentos->sql_record($clagendamentos->sql_query("","sd23_i_codigo","",
 																" sd04_i_unidade = $sd06_i_unidade and 
 																sd04_i_medico = $sd06_i_medico and 
 																sd23_d_consulta = '$data_ausencia' and 
 																not exists ( select *
		            													from agendaconsultaanula
		            													where s114_i_agendaconsulta = sd23_i_codigo
		            												) 																
 																"));
 if($clagendamentos->numrows>0){
  db_msgbox("Médico $z01_nome já tem agendamentos para a data $data2. \\n Transfira os agendamentos desta data para depois cadastrar a ausência!");
 }else{
  db_inicio_transacao();
  $clausencias->alterar($sd06_i_codigo);
  db_fim_transacao();
 }
}
if(isset($excluir)){
 db_inicio_transacao();
 $clausencias->excluir($sd06_i_codigo);
 db_fim_transacao();
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top" bgcolor="#CCCCCC">
    <center>
        <?
        include("forms/db_frmausencias2.php");
        ?>
    </center>
        </td>
  </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","sd06_d_data_dia",true,1,"sd06_d_data_dia",true);
</script>
<?
if(isset($incluir)){
  if($clausencias->erro_status=="0"){
    $clausencias->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clausencias->erro_campo!=""){
      echo "<script> document.form1.".$clausencias->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clausencias->erro_campo.".focus();</script>";
    }
  }else{
   $clausencias->erro(true,false);
   db_redireciona("sau1_ausencias002.php?sd06_i_unidade=$sd06_i_unidade&sd06_i_medico=$sd06_i_medico&descrdepto=$descrdepto&z01_nome=$z01_nome");
  }
}
if(isset($alterar)){
 if($clausencias->erro_status=="0"){
  $clausencias->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clausencias->erro_campo!=""){
   echo "<script> document.form1.".$clausencias->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clausencias->erro_campo.".focus();</script>";
  };
 }else{
  $clausencias->erro(true,false);
  db_redireciona("sau1_ausencias002.php?sd06_i_unidade=$sd06_i_unidade&sd06_i_medico=$sd06_i_medico&descrdepto=$descrdepto&z01_nome=$z01_nome");
 };
}
if(isset($excluir)){
 if($clausencias->erro_status=="0"){
  $clausencias->erro(true,false);
 }else{
  $clausencias->erro(true,false);
  db_redireciona("sau1_ausencias002.php?sd06_i_unidade=$sd06_i_unidade&sd06_i_medico=$sd06_i_medico&descrdepto=$descrdepto&z01_nome=$z01_nome");
 };
}
if(isset($cancelar)){
 db_redireciona("sau1_ausencias002.php?sd06_i_unidade=$sd06_i_unidade&sd06_i_medico=$sd06_i_medico&descrdepto=$descrdepto&z01_nome=$z01_nome");
}
?>