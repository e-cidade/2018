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
include("classes/db_unidademedicos_classe.php");
include("classes/db_medicos_classe.php");
include("classes/db_agendamentos_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clunidademedicos = new cl_unidademedicos;
$clmedicos = new cl_medicos;
$clagendamentos = new cl_agendamentos;
$db_botao = true;
$db_opcao = 2;
$sd04_i_unidade =db_getsession("DB_coddepto");

if(isset($alterar)){
 $data_ini = $sd04_d_folgaini_ano."-".$sd04_d_folgaini_mes."-".$sd04_d_folgaini_dia;
 $data_fim = $sd04_d_folgafim_ano."-".$sd04_d_folgafim_mes."-".$sd04_d_folgafim_dia;
 if($data_ini>$data_fim){
  db_msgbox("Data inicial deve ser menor que data final!");
 }else{
  $data1 = db_formatar($data_ini,'d');
  $data2 = db_formatar($data_fim,'d');
  $result = $clagendamentos->sql_record($clagendamentos->sql_query("","sd23_i_codigo","",
  																" sd04_i_medico = $sd03_i_codigo and 
  																	not exists ( select *
		            													from agendaconsultaanula
		            													where s114_i_agendaconsulta = sd23_i_codigo
		            												) and 
  																	sd23_d_consulta between '$data_ini' and '$data_fim'"));
  if($clagendamentos->numrows>0){
   db_msgbox("Médico $z01_nome já tem agendamentos \\n no intervalo entre $data1 e $data2. \\n Transfira os agendamentos entre estas datas para depois atualizar a folga!");
  }else{  	
   db_inicio_transacao();
   //die($clunidademedicos->sql_query("","sd04_i_codigo","","sd04_i_unidade = $sd04_i_unidade and sd04_i_medico = $sd03_i_codigo"));
   $result1 = $clunidademedicos->sql_record($clunidademedicos->sql_query("","sd04_i_codigo","","sd04_i_unidade = $sd04_i_unidade and sd04_i_medico = $sd03_i_codigo"));
   db_fieldsmemory($result1,0);   
   $clunidademedicos->sd04_i_codigo=$sd04_i_codigo;
   $clunidademedicos->alterar($sd04_i_codigo);
   db_fim_transacao();
  }
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top" bgcolor="#CCCCCC">
    <center>
        <?
        include("forms/db_frmfolgas.php");
        ?>
    </center>
        </td>
  </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","sd04_d_folgaini_dia",true,1,"sd04_d_folgaini_dia",true);
</script>
<?
if(isset($alterar)){
 if($clunidademedicos->erro_status=="0"){
  $clunidademedicos->erro(true,true);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clunidademedicos->erro_campo!=""){
   echo "<script> document.form1.".$clunidademedicos->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clunidademedicos->erro_campo.".focus();</script>";
  };
 }else{
  $clunidademedicos->erro(true,false);
  db_redireciona("sau1_folgas001.php?sd03_i_codigo=$sd03_i_codigo&z01_nome=$z01_nome");
 };
}
?>