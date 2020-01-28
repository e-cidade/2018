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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_aluno_classe.php");
include("classes/db_alunoaltera_classe.php");
include("classes/db_alunocurso_classe.php");
include("classes/db_censouf_classe.php");
include("classes/db_censomunic_classe.php");
include("classes/db_censoorgemissrg_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_jsplibwebseller.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$claluno = new cl_aluno;
$clalunoaltera = new cl_alunoaltera;
$clalunocurso = new cl_alunocurso;
$clcensouf = new cl_censouf;
$clcensomunic = new cl_censomunic;
$clcensoorgemissrg = new cl_censoorgemissrg;
$db_opcao = 2;
$db_botao = true;
if(isset($alterar)){
 $db_opcao = 2;
 $db_botao = true;
 db_inicio_transacao();
 $clalunoaltera->logalterar($ed47_i_codigo,2);
 $claluno->alterar($ed47_i_codigo);
 if($claluno->erro_status=="0"){
  $_rollback = true;
 }else{
  $_rollback = false;
 }
 db_fim_transacao($_rollback);
}
if(isset($chavepesquisa)){
 $db_opcao = 2;
 $result = $claluno->sql_record($claluno->sql_query($chavepesquisa));
 db_fieldsmemory($result,0);
 $db_botao = true;
 $sql_vinculo = "SELECT DISTINCT ed18_i_codigo as codvinculo,ed18_c_nome as nomevinculo
                 FROM aluno
  		  left join alunocurso       on ed47_i_codigo = ed56_i_aluno
		  left join historico        on ed47_i_codigo = ed61_i_aluno
		  left join matricula        on ed47_i_codigo = ed60_i_aluno
		  left join turma            on ed57_i_codigo = ed60_i_turma
		  left join diario           on ed47_i_codigo = ed95_i_aluno
		  left join escola           on ed18_i_codigo = ed61_i_escola
                                             or ed18_i_codigo = ed57_i_escola
                                             or ed18_i_codigo = ed95_i_escola
                                             or ed18_i_codigo = ed56_i_escola
                 WHERE (ed56_i_aluno is not null
                 OR ed61_i_aluno is not null
	         OR ed60_i_aluno is not null
	         OR ed95_i_aluno is not null)
                 AND ed47_i_codigo = $chavepesquisa
	         ORDER BY ed18_c_nome
	        ";
 $result_vinculo = pg_query($sql_vinculo);
 $linhas_vinculo = pg_num_rows($result_vinculo);
 if($linhas_vinculo>0){
  $db_botao = false;
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Documentação do Aluno</b></legend>
    <?include("forms/db_frmbibaluno.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<?
if(isset($alterar)){
 if($claluno->erro_status=="0"){
  $claluno->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($claluno->erro_campo!=""){
   echo "<script> document.form1.".$claluno->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$claluno->erro_campo.".focus();</script>";
  };
 }else{
  $claluno->erro(true,false);
  ?>
  <script>
   if(parent.parent.document.form1.codigo.value!=""){
    parent.parent.db_iframe_alteradados.hide();
    parent.parent.dadosleitor.location.href = "bib1_leitor004.php?chavepesquisa=<?=$ed47_i_codigo?>&tipo=ALUNO";
   }else{
    parent.parent.db_iframe_alteradados.hide();
    parent.parent.document.form1.codigo.value = <?=$ed47_i_codigo?>;
    parent.parent.document.form1.nome.value = "<?=$ed47_v_nome?>";
    parent.parent.document.form1.tipo.value = "ALUNO";
   }
  </script>
  <?
 }
}
if($linhas_vinculo>0){
 $esc_vinculo = "ALTERAÇÃO NÃO PERMITIDA<br><br>";
 $esc_vinculo .= "Aluno já teve ou ainda tem vínculo com a(s) escola(s) abaixo relacionada(s):<br><br>";
 for($t=0;$t<$linhas_vinculo;$t++){
  db_fieldsmemory($result_vinculo,$t);
  $esc_vinculo .= "<font color=red>".$codvinculo." - ".$nomevinculo."</font><br>";
 }
 $esc_vinculo .= "<br>Entre em contato com a secretaria desta(s) escola(s)<br>para solicitar alguma alteração no cadastro deste aluno.";
}
?>
<script>
function js_msg_nao_altera(mensagem,id){
 var expReg = /\\n\\n/gm;
 mensagem = mensagem.replace(expReg,'<br>');
 var camada = document.createElement("DIV");
 camada.setAttribute("id",id);
 camada.setAttribute("align","center");
 camada.style.position        = "absolute";
 //mensagem no meio da tela
 camada.style.left       = ((screen.availWidth-450)/2)+'px';
 camada.style.top        = ((screen.availHeight-650)/2)+'px';
 camada.style.zIndex     = "1000";
 camada.style.visibility = 'visible';
 camada.style.width      = "450px";
 camada.style.height     = "250px";
 camada.style.fontFamily = 'Verdana, Arial, Helvetica, sans-serif';
 camada.style.fontSize   = '15px';
 camada.style.border     = '1px solid';
 camada.innerHTML = ' <table border="0" width= "100%" height="100%" style="background-color: #FFFFCC; border-collapse: collapse;"> '
                    +'    <tr> '
                    +'      <td align= "center" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #000000; font-weight: bold;"> '
                    +'        <br>'+mensagem+''
                    +'        <br><br><input type="button" onclick="js_removeMsg()" value="Fechar"><br><br>'
                    +'      </td> '
                    +'    </tr> '
                    +' </table> ';
 document.body.appendChild(camada);
}
function js_removeMsg(idObj){
 obj = document.getElementById("MsgBox");
 document.body.removeChild(obj);
}
<?if($linhas_vinculo>0){?>
 js_msg_nao_altera("<?=$esc_vinculo?>","MsgBox");
<?}?>
</script>