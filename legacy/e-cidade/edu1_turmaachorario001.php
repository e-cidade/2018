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
include("classes/db_rechumano_classe.php");
include("classes/db_periodoescola_classe.php");
include("classes/db_diasemana_classe.php");
include("classes/db_turmaac_classe.php");
include("classes/db_turmaachorario_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clrechumano = new cl_rechumano;
$cldiasemana = new cl_diasemana;
$clperiodoescola = new cl_periodoescola;
$clturmaac = new cl_turmaac;
$clturmaachorario = new cl_turmaachorario;
$db_opcao = 1;
$db_botao = true;
$escola = db_getsession("DB_coddepto");
$erro = false;
$result_sala = $clturmaachorario->sql_record($clturmaachorario->sql_query("","ed270_i_codigo as codturmaadd,ed52_i_codigo as codcalendario","ed270_i_codigo","ed17_i_turno = $ed268_i_turno and  ed270_i_turmaac!='$ed270_i_turmaac'"));
$maisturmas = "";
$sep = "";
for($r=0;$r<$clturmaachorario->numrows;$r++){
 db_fieldsmemory($result_sala,$r);
 $maisturmas .= $sep.$codturmaadd;
 $sep = ",";
}
if(isset($incluir)){
 $db_botao = true;
 db_inicio_transacao();
 for($x=0;$x<$contp;$x++){
  for($y=0;$y<$contd;$y++){
   $valores = "valorQ".$x.$y;
   $valores = $$valores;
   $marcados = "marcadoQ".$x.$y;
   $marcados = $$marcados;
   if(trim($valores)=="" && trim($marcados)!=""){
    $clturmaachorario->excluir($marcados);
   }elseif(trim($valores)!="" && trim($marcados)!=""){
    $dados = explode("|",$valores);
    $clturmaachorario->ed270_i_turmaac = $ed270_i_turmaac;    
    $clturmaachorario->ed270_i_diasemana = $dados[0];
    $clturmaachorario->ed270_i_periodo = $dados[1];
    $clturmaachorario->ed270_i_rechumano = $dados[2];    
    $clturmaachorario->ed270_i_codigo = $marcados;
    $clturmaachorario->alterar($marcados);    
   }elseif(trim($valores)!="" && trim($marcados)==""){
    $dados = explode("|",$valores);
    $clturmaachorario->ed270_i_turmaac = $ed270_i_turmaac;
    $clturmaachorario->ed270_i_rechumano = $dados[0];
    $clturmaachorario->ed270_i_diasemana = $dados[1];
    $clturmaachorario->ed270_i_periodo = $dados[2];
    $clturmaachorario->incluir(null);
   }
   unset($valores);
   unset($marcados);
  }
 }
 db_fim_transacao();
 $clturmaachorario->erro_msg = "Dados salvos com sucesso!";
 $clturmaachorario->erro(true,false);
 db_redireciona("edu1_turmaachorario001.php?ed270_i_turmaac=$ed270_i_turmaac&ed268_i_turno=$ed268_i_turno&ed268_c_descr=$ed268_c_descr&codcalendario=$codcalendario");
 exit;
}
if(isset($limpar)){
 $clturmaachorario->erro_msg = "Dados salvos com sucesso!";
 db_redireciona("edu1_turmaachorario001.php?ed270_i_turmaac=$ed270_i_turmaac&ed268_i_turno=$ed268_i_turno&ed268_c_descr=$ed268_c_descr&codcalendario=$codcalendario");
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
   <fieldset style="width:95%"><legend><b>Horários de Regências na Turma <?=@$ed268_c_descr?></b></legend>
    <?if(!isset($excluir)){?>
     <?include("forms/db_frmturmaachorario.php");?>
    <?}?>
   </fieldset>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed270_i_rechumano",true,1,"ed270_i_rechumano",true);
</script>