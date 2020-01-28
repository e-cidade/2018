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

require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

parse_str( $_SERVER["QUERY_STRING"] );

db_postmemory($_POST);
db_postmemory($_GET);

$clcalendario = new cl_calendario;
$clcalendario->rotulo->label();

$clcalendario = new cl_calendario;
$clferiado    = new cl_feriado;
$clregencia   = new cl_regencia;
$db_opcao     = 22;
$db_opcao1    = 3;
$db_botao     = false;

if(isset($chavepesquisa)) {
  
  $result    = $clregencia->sql_record($clregencia->sql_query("","ed59_i_codigo",""," ed57_i_calendario = $chavepesquisa AND ed59_c_encerrada = 'S' AND ed59_c_condicao = 'OB'"));
  $db_opcao  = 2;
  $db_opcao1 = 3;
  $result    = $clcalendario->sql_record($clcalendario->sql_query($chavepesquisa));
  db_fieldsmemory($result,0);
  if($ed52_i_calendant!="" && $ed52_i_calendant!=0) {
    
    $result1 = $clcalendario->sql_record($clcalendario->sql_query("","ed52_c_descr as ed52_c_descrant",""," ed52_i_codigo = $ed52_i_calendant"));
    db_fieldsmemory($result1,0);
  }
  $db_botao = true;
  ?>
  <script>
    parent.document.formaba.a2.disabled = false;
    parent.document.formaba.a2.style.color = "black";
    parent.document.formaba.a3.disabled = false;
    parent.document.formaba.a3.style.color = "black";
    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a2.location.href='edu1_periodocalendario001.php?ed53_i_calendario=<?=$ed52_i_codigo?>&ed52_c_descr=<?=$ed52_c_descr?>';
    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a3.location.href='edu1_feriado001.php?ed54_i_calendario=<?=$ed52_i_codigo?>&ed52_c_descr=<?=$ed52_c_descr?>';
  </script>
  <?
  if($clregencia->numrows>0) {
  
   db_msgbox("Calendário não pode ser mais alterado,\\npois já existem turmas encerradas vinculadas a este calendário!");
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
   <fieldset style="width:95%">
     <legend><b>Alteração de Calendário</b></legend>
     <?php
     include(modification("forms/db_frmcalendario.php"));
     ?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<?

if($db_opcao==22){
 echo "<script>document.form1.pesquisar.click();</script>";
}
if(@$ed52_i_duracaocal!=1){
 ?><script>document.getElementById("periodos").style.visibility = "visible";</script><?
}
?>