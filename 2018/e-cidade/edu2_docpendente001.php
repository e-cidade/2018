<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_matricula_classe.php");
include("classes/db_calendario_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clmatricula = new cl_matricula;
$clcalendario = new cl_calendario;
$db_opcao = 1;
$db_botao = true;
$nomeescola = db_getsession("DB_nomedepto");
$escola = db_getsession("DB_coddepto");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.cabec{
 text-align: left;
 font-size: 10;
 color: #DEB887;
 background-color:#444444;
 border:1px solid #CCCCCC;
}
.aluno{
 font-size: 11;
}
</style>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
   <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
   <br>
   <form name="form1" method="post" action="">
   <fieldset style="width:95%"><legend><b>Relatório de Documentação Pendente</b></legend>
    <?
    $result = $clcalendario->sql_record($clcalendario->sql_query_calturma("","ed52_i_codigo,ed52_c_descr,ed52_i_ano"," ed52_i_ano desc"," ed38_i_escola = $escola AND ed52_c_passivo = 'N'"));?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
     <tr>
      <td align="left" valign="top">
       <b>Selecione o Calendário:</b>
       <select name="calendario" style="font-size:9px;width:200px;height:18px;" onchange="js_botao(this.value)">
        <option value=""></option>
        <?
        for($i=0;$i<$clcalendario->numrows;$i++) {
         db_fieldsmemory($result,$i);
         $selected = isset( $calendario ) && $ed52_i_codigo == $calendario ? "selected" : "";
         echo "<option value='$ed52_i_codigo' $selected>$ed52_c_descr</option>\n";
        }
        ?>
       </select>
       <input type="button" value="Processar" onclick="js_processar(document.form1.calendario.value)">
      </td>
     </tr>
    </table>
   </fieldset>
   </form>
  </td>
 </tr>
</table>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
function js_processar(calendario){
 if(calendario!=""){
  jan = window.open('edu2_docpendente002.php?f&calendario='+calendario,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
 }
}
<?if(pg_num_rows($result)>0){?>
 document.form1.calendario.options[1].selected = true;
<?}?>
</script>