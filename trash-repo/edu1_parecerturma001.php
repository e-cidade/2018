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
include("classes/db_parecerturma_classe.php");
include("classes/db_parecer_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clparecerturma = new cl_parecerturma;
$clparecer = new cl_parecer;
$db_opcao = 2;
$db_botao = true;
if(isset($alterar)){
 $result = $clparecer->sql_record($clparecer->sql_query("","*","ed92_c_descr",""));
 for($x=0;$x<$clparecer->numrows;$x++){
  db_fieldsmemory($result,$x);
  $ed105_i_codigo = "";
  $codigo = $ed92_i_codigo;
  if(@$_POST[$codigo] == "ativo"){
   $marcado = "S";
  } else {
   $marcado = "N";
  }
  $sql1 = "SELECT ed105_i_codigo FROM parecerturma where ed105_i_turma = $ed57_i_codigo AND ed105_i_parecer = $codigo";
  $result1 = pg_query($sql1);
  $linhas1 = pg_num_rows($result1);
  db_inicio_transacao();
  if($linhas1==0 && $marcado=="S"){
   $clparecerturma->ed105_i_turma = $ed57_i_codigo;
   $clparecerturma->ed105_i_parecer = $codigo;
   $clparecerturma->incluir($ed105_i_codigo);
  }elseif($linhas1>0 && $marcado=="S"){
   db_fieldsmemory($result1,0);
   $clparecerturma->ed105_i_turma = $ed57_i_codigo;
   $clparecerturma->ed105_i_parecer = $codigo;
   $clparecerturma->ed105_i_codigo = $ed105_i_codigo;
   $clparecerturma->alterar($ed105_i_codigo);
  }
  if($marcado=="N"){
   $clparecerturma->excluir(""," ed105_i_turma = $ed57_i_codigo AND ed105_i_parecer = $codigo");
  }
  db_fim_transacao();
 }
 db_msgbox("Dados salvos com sucesso!");
 db_redireciona("edu1_parecerturma001.php?ed105_i_turma=$ed57_i_codigo&ed57_c_descr=$ed57_c_descr&ed52_c_descr=$ed52_c_descr");
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
<style>
.titulo{
 font-size: 11;
 color: #DEB887;
 background-color:#444444;
 font-weight: bold;
}
.cabec1{
 font-size: 11;
 color: #000000;
 background-color:#999999;
 font-weight: bold;
}
.aluno{
 color: #000000;
 font-family : Tahoma;
 font-size: 10;
}
.aluno1{
 color: #000000;
 font-family : Verdana;
 font-size: 11;
 font-weight :bold;
}

</style>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Definições dos pareceres desta turma</b></legend>
    <?include("forms/db_frmparecerturma.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","todos",true,1,"todos",true);
</script>