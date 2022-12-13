<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("classes/db_itinerario_classe.php");
include("classes/db_itinerarioescolaproc_classe.php");
include("classes/db_itinerarioescola_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clitinerario = new cl_itinerario;
$clitinerarioescolaproc = new cl_itinerarioescolaproc;
$clitinerarioescola = new cl_itinerarioescola;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  db_inicio_transacao();
  $clitinerario->ed218_i_sequencia=1;
  $clitinerario->ed218_i_usuario= db_getsession('DB_id_usuario');
  $clitinerario->incluir($ed218_i_codigo);
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <br>
    <fieldset style="width:95%"><legend><b>Inclusão de itinerário</b></legend>
    <center>
     <?include("forms/db_frmitinerario.php");?>
    </center>
    </fieldset>
   </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed218_d_datacad",true,1,"ed218_d_datacad",true);
</script>
<?
if(isset($incluir)){
  if($clitinerario->erro_status=="0"){
    $clitinerario->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clitinerario->erro_campo!=""){
      echo "<script> document.form1.".$clitinerario->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clitinerario->erro_campo.".focus();</script>";
    }
  }else{
  $result = @db_query("select last_value from itinerario_ed218_i_codigo_seq");
  $ultimo = pg_result($result,0,0);
  if($tipoescola=="F"){
   db_inicio_transacao();
   $clitinerarioescolaproc->ed222_i_itinerario=$ultimo;
   $clitinerarioescolaproc->ed222_i_escolaproc= $ed18_i_codigo;
   $clitinerarioescolaproc->incluir(null);
   db_fim_transacao();
  }else{
     db_inicio_transacao();
    $clitinerarioescola->ed221_i_itinerario= $ultimo;
    $clitinerarioescola->ed221_i_escola=$ed18_i_codigo;
    $clitinerarioescola->incluir(null);
  db_fim_transacao();
   }
    $clitinerario->erro(true,true);
  }
}
?>