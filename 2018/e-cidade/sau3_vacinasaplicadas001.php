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
include("dbforms/db_funcoes.php");
include("classes/db_vacinasaplicadas_classe.php");
$cl_vacinasaplicadas = new cl_vacinasaplicadas;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<table width="790" height='18' border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table valign="top" marginwidth="0" width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
    <form name='form1'>
    <table>
    <tr>
     <td align="right">
       <b>Período:</b>
     </td>
     <td>
       <?db_inputdata('data1',@$dia1,@$mes1,@$ano1,true,'text',1,"")?>
        A
       <?db_inputdata('data2',@$dia2,@$mes2,@$ano2,true,'text',1,"")?>
     </td>
     </tr>
     <tr>
      <td colspan='6' align='center' >
       <input name='Processar' type='button' value='Processar' onclick="EnviaForm()">
      </td>
     </tr>
    </table>
    </form>
    <?
     if(isset($Processar)){
      db_lovrot($cl_vacinasaplicadas->sql_query("","","","*",$ordem,"sd08_d_data BETWEEN '$data1' AND '$data2'"),15,"()");
     }else{
      echo "<center><br><br>Informe o período e clique em <b>Processar</b>...</center>";
     }
    ?>
    <script>
     function EnviaForm(){
      if(document.form1.data1_dia.value==""){
       alert("Preencha a data corretamente!");
       document.form1.data1_dia.focus();
       return false;
      }
      if(document.form1.data1_mes.value==""){
       alert("Preencha a data corretamente!");
       document.form1.data1_mes.focus();
       return false;
      }
      if(document.form1.data1_ano.value==""){
       alert("Preencha a data corretamente!");
       document.form1.data1_ano.focus();
       return false;
      }
      if(document.form1.data2_dia.value==""){
       alert("Preencha a data corretamente!");
       document.form1.data2_dia.focus();
       return false;
      }
      if(document.form1.data2_mes.value==""){
       alert("Preencha a data corretamente!");
       document.form1.data2_mes.focus();
       return false;
      }
      if(document.form1.data2_ano.value==""){
       alert("Preencha a data corretamente!");
       document.form1.data2_ano.focus();
       return false;
      }
      data1 = document.form1.data1_ano.value+"-"+document.form1.data1_mes.value+"-"+document.form1.data1_dia.value;
      data2 = document.form1.data2_ano.value+"-"+document.form1.data2_mes.value+"-"+document.form1.data2_dia.value;
      location.href="sau3_vacinasaplicadas001.php?Processar&data1="+data1+"&data2="+data2;
     }
    </script>
  </td>
 </tr>
</table>
<?
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>