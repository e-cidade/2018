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
include("classes/db_alunobairro_classe.php");
include("classes/db_alunoprimat_classe.php");
include("classes/db_pais_classe.php");
include("classes/db_censouf_classe.php");
include("classes/db_censomunic_classe.php");
include("classes/db_censoorgemissrg_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_jsplibwebseller.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$claluno = new cl_aluno;
$clalunoaltera = new cl_alunoaltera;
$clalunobairro = new cl_alunobairro;
$clalunoprimat = new cl_alunoprimat;
$clpais = new cl_pais;
$clcensouf = new cl_censouf;
$clcensomunic = new cl_censomunic;
$clcensoorgemissrg = new cl_censoorgemissrg;
$db_opcao = 2;
$db_opcao1 = 3;
$db_botao = false;
function TiraEspacoNome($nome){
 $sep = "";
 $str = "";
 $parte=explode(" ",$nome);
 //echo print_r($parte);
 for($i=0;$i<count($parte);$i++){
  if(trim($parte[$i])!=""){
   $str .= $sep.trim($parte[$i]);
   $sep=" ";
  }
 }
 return $str;
}
if(isset($alterar)){
 if($ed47_v_nome!=""){
  $erroconf=false;
  $ed47_v_nome = TiraEspacoNome($ed47_v_nome);
  $result2 = $claluno->sql_record($claluno->sql_query("","ed47_i_codigo as jatem,ed47_d_nasc as datatem,ed47_c_certidaonum as certidaonumexiste,ed47_v_mae as maesim",""," ed47_v_nome = '$ed47_v_nome'"));
  if($claluno->numrows>0){
   db_fieldsmemory($result2,0);
   if($jatem!=$ed47_i_codigo){
    if($ed47_d_nasc_ano."-".$ed47_d_nasc_mes."-".$ed47_d_nasc_dia==$datatem){
     $erroconf=true;
     db_msgbox("Este nome ($ed47_v_nome) já possui cadastro (código $jatem) com a mesma data de nascimento digitada ($ed47_d_nasc)!");
     db_redireciona("bib1_alunodados002.php?leitor&chavepesquisa=$ed47_i_codigo");
     exit;
    }
    if(trim($ed47_v_mae)==trim($maesim) && trim($ed47_v_mae)!="" && trim($maesim)!=""){
     $erroconf=true;
     db_msgbox("Este nome ($ed47_v_nome) já possui cadastro (código $jatem) com o mesmo nome da mae digitado ($maesim)!");
     db_redireciona("bib1_alunodados002.php?leitor&chavepesquisa=$ed47_i_codigo");
     exit;
    }
   }
  }
  if($erroconf==false){
   $db_opcao = 2;
   $db_opcao1 = 3;
   $ed47_c_foto = @trim($GLOBALS["HTTP_POST_VARS"]["ed47_o_oid"]);
   $ed47_o_oid = "tmp/".@trim($GLOBALS["HTTP_POST_VARS"]["ed47_o_oid"]);
   if($ed47_c_foto!=""){
    pg_exec("begin");
    $oid_imagem = pg_loimport($conn,$ed47_o_oid) or die("Erro(15) importando imagem");
    pg_exec("end");
    $ed47_o_oid = $oid_imagem;
   }else{
    $oid_imagem = "0";
   }
   db_inicio_transacao();
   $claluno->ed47_c_foto = $ed47_c_foto;
   $claluno->ed47_o_oid = $oid_imagem;
   $claluno->ed47_v_nome = $ed47_v_nome;
   $claluno->ed47_d_ultalt = date("Y-m-d");
   $clalunoaltera->logalterar($ed47_i_codigo,2);
   $claluno->alterar($ed47_i_codigo);
   if($claluno->erro_status=="0"){
    $_rollback = true;
   }else{
    $_rollback = false;
   }
   if($j13_codi!=""){
    $result_bairro = $clalunobairro->sql_record($clalunobairro->sql_query("","ed225_i_codigo",""," ed225_i_aluno = $ed47_i_codigo"));
    $clalunobairro->ed225_i_aluno = $ed47_i_codigo;
    $clalunobairro->ed225_i_bairro = $j13_codi;
    if($clalunobairro->numrows>0){
     db_fieldsmemory($result_bairro,0);
     $clalunobairro->ed225_i_codigo = $ed225_i_codigo;
     $clalunobairro->alterar($ed225_i_codigo);
    }else{
     $clalunobairro->incluir(null);
    }
   }
   $result_pri = $clalunoprimat->sql_record($clalunoprimat->sql_query("","ed76_i_codigo",""," ed76_i_aluno = $ed47_i_codigo"));
   $clalunoprimat->ed76_i_aluno = $ed47_i_codigo;
   if($clalunoprimat->numrows>0){
    if($ed76_i_escola!=""){
     db_fieldsmemory($result_pri,0);
     $clalunoprimat->alterar($ed76_i_codigo);
    }else{
     $clalunoprimat->excluir($ed76_i_codigo);
    }
   }else{
    if($ed76_i_escola!=""){
     $clalunoprimat->incluir(null);
    }
   }
   db_fim_transacao($_rollback);
  }
 }else{
  $db_opcao = 2;
  $db_opcao1 = 3;
  $ed47_c_foto = @trim($GLOBALS["HTTP_POST_VARS"]["ed47_o_oid"]);
  $ed47_o_oid = "tmp/".@trim($GLOBALS["HTTP_POST_VARS"]["ed47_o_oid"]);
  if($ed47_c_foto!=""){
   pg_exec("begin");
   $oid_imagem = pg_loimport($conn,$ed47_o_oid) or die("Erro(15) importando imagem");
   pg_exec("end");
   $ed47_o_oid = $oid_imagem;
  }else{
   $oid_imagem = "0";
  }
  db_inicio_transacao();
  $claluno->ed47_c_foto = $ed47_c_foto;
  $claluno->ed47_o_oid = $oid_imagem;
  $claluno->ed47_d_ultalt = date("Y-m-d");
  $clalunoaltera->logalterar($ed47_i_codigo,2);
  $claluno->alterar($ed47_i_codigo);
  if($claluno->erro_status=="0"){
   $_rollback = true;
  }else{
   $_rollback = false;
  }
  if($j13_codi!=""){
   $result_bairro = $clalunobairro->sql_record($clalunobairro->sql_query("","ed225_i_codigo",""," ed225_i_aluno = $ed47_i_codigo"));
   $clalunobairro->ed225_i_aluno = $ed47_i_codigo;
   $clalunobairro->ed225_i_bairro = $j13_codi;
   if($clalunobairro->numrows>0){
    db_fieldsmemory($result_bairro,0);
    $clalunobairro->ed225_i_codigo = $ed225_i_codigo;
    $clalunobairro->alterar($ed225_i_codigo);
   }else{
    $clalunobairro->incluir(null);
   }
  }
  $result_pri = $clalunoprimat->sql_record($clalunoprimat->sql_query("","ed76_i_codigo",""," ed76_i_aluno = $ed47_i_codigo"));
  $clalunoprimat->ed76_i_aluno = $ed47_i_codigo;
  if($clalunoprimat->numrows>0){
   if($ed76_i_escola!=""){
    db_fieldsmemory($result_pri,0);
    $clalunoprimat->alterar($ed76_i_codigo);
   }else{
    $clalunoprimat->excluir($ed76_i_codigo);
   }
  }else{
   if($ed76_i_escola!=""){
    $clalunoprimat->incluir(null);
   }
  }
  db_fim_transacao($_rollback);
 }
 $db_botao = true;
}else if(isset($chavepesquisa)){
 $db_opcao = 2;
 $db_opcao1 = 3;
 $result = $claluno->sql_record($claluno->sql_query($chavepesquisa));
 db_fieldsmemory($result,0);
 $result_bairro = $clalunobairro->sql_record($clalunobairro->sql_query("","*",""," ed225_i_aluno = $chavepesquisa"));
 if($clalunobairro->numrows>0){
  db_fieldsmemory($result_bairro,0);
  $j13_codi = $ed225_i_bairro;
 }
 $campos = "ed76_i_codigo,
            ed76_i_escola,
            ed76_d_data,
            ed76_c_tipo,
            case when ed76_c_tipo = 'M'
             then ed18_c_nome else ed82_c_nome end as nomeescola
           ";
 $result1 = $clalunoprimat->sql_record($clalunoprimat->sql_query("",$campos,""," ed76_i_aluno = $chavepesquisa"));
 if($clalunoprimat->numrows>0){
  db_fieldsmemory($result1,0);
 }
 $db_botao = true;
 $ed47_d_ultalt_dia = $ed47_d_ultalt_dia==""?date("d"):$ed47_d_ultalt_dia;
 $ed47_d_ultalt_mes = $ed47_d_ultalt_mes==""?date("m"):$ed47_d_ultalt_mes;
 $ed47_d_ultalt_ano = $ed47_d_ultalt_ano==""?date("Y"):$ed47_d_ultalt_ano;
 $ed47_d_cadast_dia = $ed47_d_cadast_dia==""?date("d"):$ed47_d_cadast_dia;
 $ed47_d_cadast_mes = $ed47_d_cadast_mes==""?date("m"):$ed47_d_cadast_mes;
 $ed47_d_cadast_ano = $ed47_d_cadast_ano==""?date("Y"):$ed47_d_cadast_ano;
 ?>
 <script>
  parent.document.formaba.a2.disabled = false;
  parent.document.formaba.a2.style.color = "black";
  parent.iframe_a2.location.href='bib1_aluno002.php?chavepesquisa=<?=$ed47_i_codigo?>';
 </script>
 <?
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
if(isset($excluirfoto)){
 $sql = "UPDATE aluno SET
          ed47_c_foto = '',
          ed47_o_oid = 0
         WHERE ed47_i_codigo = $chavepesquisa
        ";
 $result = pg_query($sql);
  ?>
  <script>
   parent.parent.db_iframe_alteradados.hide();
   parent.parent.dadosleitor.location.href = "bib1_leitor004.php?chavepesquisa=<?=$ed47_i_codigo?>&tipo=ALUNO";
  </script>
  <?
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
   <center>
   <fieldset style="width:95%"><legend><b>Alteração de Aluno</b></legend>
    <?include("forms/db_frmbibalunodados.php");?>
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
    parent.parent.dadosleitor.location.href = "bib1_leitor004.php?chavepesquisa=<?=$ed47_i_codigo?>&tipo=ALUNO";
    parent.parent.db_iframe_alteradados.hide();
   }else{
    parent.parent.document.form1.codigo.value = <?=$ed47_i_codigo?>;
    parent.parent.document.form1.nome.value = "<?=$ed47_v_nome?>";
    parent.parent.document.form1.tipo.value = "ALUNO";
    parent.parent.db_iframe_alteradados.hide();
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