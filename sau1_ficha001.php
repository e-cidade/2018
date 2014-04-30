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
include("classes/db_unidades_classe.php");
include("classes/db_agendamentos_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);

$clagendamentos = new cl_agendamentos;
$clunidades = new cl_unidades;

$db_opcao = 1;
$db_botao = true;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
<?
if(!isset($start)){
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="Unidade">
      <?
       db_ancora("<b>Unidade</b>","js_pesquisasd04_i_unidade(true);",$db_opcao01);
      ?>
    </td>
    <td colspan="4">
<?
 db_input('sd04_i_unidade',10,$Isd04_i_unidade,true,'text',$db_opcao," onchange='js_pesquisasd04_i_unidade(false);'")
?>
       <?
db_input('sd02_c_nome',40,$Isd02_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="Numero de Atendimento">
      <b>Numero de Atendimento</b>
    </td>
    <td>
<?
db_input('sd23_c_atendimento',10,$Isd23_c_atendimento,true,'text',$db_opcao," onchange='js_pesquisasd23_c_atendimento(false);'")
?>
    </td>
  </tr>
  <tr>
   <td>
    <input type="submit" value="Processar" name="start">
   </td>
  </tr>
 </table>
</form>
<?
 }else{
 //busca o registro do Atendiemnto
 @$result = $clagendamentos->sql_record($clagendamentos->sql_query("","sd23_i_unidade, sd02_c_razao, sd23_i_cgm, z01_nome, sd23_c_atendimento, to_char(sd23_d_consulta,'dd/mm/yyyy') as sd23_d_consulta","","sd23_c_atendimento = '$sd23_c_atendimento' and sd23_i_unidade = $sd04_i_unidade"));
 if($clagendamentos->numrows > 0){
 db_fieldsmemory($result,0);
 //mostra os dados básicos
 ?><br><br><br>
 <table>
  <tr><td>Unidade</td><td><?=$sd23_i_unidade." - ".$sd02_c_razao?></td></tr>
  <tr><td>Paciente</td><td><?=$sd23_i_cgm." - ".$z01_nome?></td></tr>
  <tr><td>Atendimento</td><td><?=$sd23_c_atendimento?></td></tr>
  <tr><td>Data</td><td><?=$sd23_d_consulta?></td></tr>
  <tr>
   <td colspan="2">
    <input type="button" value="Matricial" onclick="emite_ficha(<?=$sd23_c_atendimento?>,1)">
    <input type="button" value="Outros"    onclick="emite_ficha(<?=$sd23_c_atendimento?>,2)">
   </td>
  </tr>
 <table>
 <?
 }else{
  //aviso e retorna à página anterior
  db_msgbox("Agendamento não Cadastrado");
  echo "<script>";
  echo "location = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."'";
  echo "</script>";
 }
 }?>
<script language="javascript">
 function emite_ficha(numero,tp){
  if(tp == 1){
   jan = window.open('sau1_ficha003.php?Agenda='+numero,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  }else{
   jan = window.open('sau1_ficha002.php?Agenda='+numero,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  }
  jan.moveTo(0,0);
 }

function js_pesquisasd04_i_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_unidades','func_unidades.php?funcao_js=parent.js_mostraunidades1|sd02_i_codigo|sd02_c_nome','Pesquisa',true);
  }else{
     if(document.form1.sd04_i_unidade.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_unidades','func_unidades.php?pesquisa_chave='+document.form1.sd04_i_unidade.value+'&funcao_js=parent.js_mostraunidades','Pesquisa',false);
     }else{
       document.form1.sd02_c_nome.value = '';
     }
  }
}
function js_mostraunidades(chave,erro){
  document.form1.sd02_c_nome.value = chave;
  if(erro==true){
    document.form1.sd04_i_unidade.focus();
    document.form1.sd04_i_unidade.value = '';
  }
}
function js_mostraunidades1(chave1,chave2){
  document.form1.sd04_i_unidade.value = chave1;
  document.form1.sd02_c_nome.value = chave2;
  db_iframe_unidades.hide();
}
</script>
    </center>
        </td>
  </tr>
</table>
<?
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>