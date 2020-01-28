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
include("classes/db_alunonecessidade_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clalunonecessidade = new cl_alunonecessidade;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
 $result2 = $clalunonecessidade->sql_record($clalunonecessidade->sql_query_file("","ed214_i_codigo as nada",""," ed214_i_aluno = $ed214_i_aluno"));
 if($clalunonecessidade->numrows==0){
  $ed214_c_principal = "SIM";
 }else{
  $ed214_c_principal = "N�O";
 }
 db_inicio_transacao();
 $clalunonecessidade->ed214_c_principal = $ed214_c_principal;
 $clalunonecessidade->incluir($ed214_i_codigo);
 db_fim_transacao();
}
if(isset($excluir)){
 db_inicio_transacao();
 $db_opcao = 3;
 $clalunonecessidade->excluir($ed214_i_codigo);
 db_fim_transacao();
}
if(isset($atualizar)){
 $result = pg_query("UPDATE alunonecessidade SET ed214_c_principal = 'N�O' WHERE ed214_i_aluno = $ed214_i_aluno");
 $result1 = pg_query("UPDATE alunonecessidade SET ed214_c_principal = 'SIM' WHERE ed214_i_codigo = $principal");
 db_redireciona("edu1_alunonecessidade001.php?ed214_i_aluno=$ed214_i_aluno&ed47_v_nome=$ed47_v_nome");
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Necessidades Especiais</b></legend>
    <?include("forms/db_frmalunonecessidade.php");?>
   </center>
   </fieldset>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed214_i_necessidade",true,1,"ed214_i_necessidade",true);
</script>
<?
if(isset($incluir)){
 if($clalunonecessidade->erro_status=="0"){
  $clalunonecessidade->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clalunonecessidade->erro_campo!=""){
   echo "<script> document.form1.".$clalunonecessidade->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clalunonecessidade->erro_campo.".focus();</script>";
  }
 }else{
  $clalunonecessidade->erro(true,false);
  db_redireciona("edu1_alunonecessidade001.php?ed214_i_aluno=$ed214_i_aluno&ed47_v_nome=$ed47_v_nome");
 }
}
if(isset($excluir)){
 if($clalunonecessidade->erro_status=="0"){
  $clalunonecessidade->erro(true,false);
 }else{
  $sql = "SELECT * FROM alunonecessidade
           inner join necessidade on ed48_i_codigo = ed214_i_necessidade
          WHERE ed214_i_aluno = $ed214_i_aluno
          ORDER BY ed48_c_descr
         ";
  $result = pg_query($sql);
  $linhas = pg_num_rows($result);
  if($linhas==1){
   $result = pg_query("UPDATE alunonecessidade SET ed214_c_principal = 'SIM' WHERE ed214_i_aluno = $ed214_i_aluno");
  }elseif($linhas>1){
   $maior = "";
   for($r=0;$r<$linhas;$r++){
    db_fieldsmemory($result,$r);
    if($ed214_c_principal=="SIM"){
     $maior = $ed214_c_principal;
    }
   }
   if($maior==""){
    $result1 = pg_query("UPDATE alunonecessidade SET ed214_c_principal = 'SIM' WHERE ed214_i_codigo = ".pg_result($result,0,'ed214_i_codigo')."");
    db_msgbox("ATEN��O! A necessidade especial ".trim(pg_result($result,0,'ed48_c_descr'))."\\nficou definida como necessidade Maior deste aluno!");
   }
  }
  $clalunonecessidade->erro(true,false);
  db_redireciona("edu1_alunonecessidade001.php?ed214_i_aluno=$ed214_i_aluno&ed47_v_nome=$ed47_v_nome");
 }
}
if(isset($cancelar)){
 db_redireciona("edu1_alunonecessidade001.php?ed214_i_aluno=$ed214_i_aluno&ed47_v_nome=$ed47_v_nome");
}

?>