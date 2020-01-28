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

//MODULO: educação
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_far_modelolivro_classe.php");
include("classes/db_far_fechalivro_classe.php");


db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clfar_modelolivro = new cl_far_modelolivro;
$clfar_fechalivro = new cl_far_fechalivro;
$clfar_fechalivro->rotulo->label();
$fa26_i_login   = DB_getsession("DB_id_usuario");
$db_opcao=1;

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<br>
<center>

<table width="100%" border="0" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td align="center" valign="top">
  	<fieldset style="width:67%"><legend><b>Relatório das notificações de receitas "A"</b></legend>
   <table width="100%" border="0" align="center" cellspacing="0">
    <form name="form1" method="post" action="" >
         <tr>
     <td >
       <b> Período:</b>
     </td>
     <td>
       <? db_inputdata('data1',@$dia1,@$mes1,@$ano1,true,'text',$db_opcao,"");?>
       <? db_inputdata('data2',@$dia2,@$mes2,@$ano2,true,'text',$db_opcao,"");?>
     </td>
     </tr>     
  <tr>
        <td>
       <b>livro:</b>
    </td>
    <td> 
       <?               
        $result_modlivro = $clfar_modelolivro->sql_record($clfar_modelolivro->sql_query("","fa16_i_codigo,fa16_c_livro","fa16_c_livro"));
        db_selectrecord("fa16_i_codigo",$result_modlivro,"","","","","","  ","",1);
          ?>
    </td>
        </tr>
         <tr>
        <td>
       <b>xxxxx:</b>
    </td>
    <td> 
       <?               
        $result_periodo = $clfar_modelolivro->sql_record($clfar_modelolivro->sql_query("","fa16_c_periodo","fa16_i_codigo"));
        db_selectrecord("fa16_i_codigo",$result_periodo,"","","","","","  ","",1);
          ?>
    </td>
        </tr>
   </table>
   </fieldset>
   <table>
	<tr>
     <td align="center" colspan="3">
      <input name="emitir" type="submit" id="emitir" value="Emitir Relatório" onClick= 'js_botao();'>
     </td>
    </tr>
   </table>
   </form>
  </td>
 </tr>
</table>
</center>
</body>
</html>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
function js_botao(){                                 
	parametros = '?fa16_i_codigo=<?=$fa16_i_codigo?>';
	parametros += "&data1="+document.form1.data1.value+"&data2="+document.form1.data2.value;
    jan = window.open('far2_relmensalnot002.php'+parametros,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);	
} 
</script>