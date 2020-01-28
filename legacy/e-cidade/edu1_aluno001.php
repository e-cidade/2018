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
include("classes/db_aluno_classe.php");
include("classes/db_alunocurso_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$claluno = new cl_aluno;
$clalunocurso = new cl_alunocurso;
$db_opcao = 1;
$db_opcao1 = 1;
$db_botao = true;

/**
 * Alterações realizadas afim de evitar warnings e fatal erros no form.
 */
$oDaoAluno = $claluno;
$oDaoCensoUf = new cl_censouf();
$oDaoCensoOrgEmissRg = new cl_censoorgemissrg();
$ed47_i_nacion = null;


if(isset($incluir)){
 $result = $claluno->sql_record($claluno->sql_query("","*",""," ed47_i_codigo = $ed47_i_codigo"));
 if($claluno->numrows>0){
  $result1 = $clalunocurso->sql_record($clalunocurso->sql_query("","*",""," ed56_i_aluno = $ed47_i_codigo"));
  if($clalunocurso->numrows>0){
   db_fieldsmemory($result1,0);
   db_msgbox("Aluno já cadastrado na escola $ed18_c_nome com situação $ed56_c_situacao!");
  }else{
   db_msgbox("Aluno já cadastrado no sistema!");
  }
  db_redireciona("edu1_aluno001.php");
 }else{
  db_inicio_transacao();
  $claluno->incluir($ed47_i_codigo);
  db_fim_transacao();
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
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Inclusão de Aluno</b></legend>
    <?include("forms/db_frmaluno.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
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
   top.corpo.iframe_a1.location.href='edu1_aluno002.php?chavepesquisa=<?=$ed47_i_codigo?>';
  </script>
  <?
 };
};
?>