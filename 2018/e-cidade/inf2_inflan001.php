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
include("classes/db_inflan_classe.php");
include("classes/db_infla_classe.php");

$rotulocampo = new rotulocampo;
$rotulocampo->label("i01_codigo");
$rotulocampo->label("DBtxt12");
$rotulocampo->label("DBtxt13");
$clinfla = new cl_infla;
$clinflan= new cl_inflan;
db_postmemory($HTTP_POST_VARS);
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
 
<script>
function js_verifica() {
  var exerci = document.form1.DBtxt12.value;
  var exercf = document.form1.DBtxt13.value;

  if(exerci.valueOf() == 0){
     alert('O exercício não pode ser zero (0). Verifique !');
     return false
  }
  if(exerci.valueOf() > exercf.valueOf()){
     alert('O exercício inicial não pode ser maior que o exercício final. Verifique !');
     return false
  }
}
</script>
<script>
function js_relatorio() {
  jan = window.open('inf2_inflan002.php?exercicioi='+document.form1.DBtxt12.value+'&exerciciof='+document.form1.DBtxt13.value+'&codigo='+document.form1.i01_codigo.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="if(document.form1) document.form1.elements[0].focus()" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	<center>
        <form name="form1" method="post" action="" onsubmit="return js_verifica();">
          <table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td height="25" nowrap colspan="2">&nbsp; &nbsp;</td>
            </tr>
            <tr>
              <td height="25" nowrap title="<?=$Ti01_codigo?>"><?=$Li01_codigo?></td>
              <td height="25" nowrap>&nbsp; &nbsp;
                <?
		$result = $clinflan->sql_record($clinflan->sql_query("","i01_codigo#i01_descr#i01_dm","i01_codigo")); 
                for($i=0;$i<$clinflan->numrows;$i++){
                   db_fieldsmemory($result,$i);
                }
		db_selectrecord("i01_codigo",$result,true,2,"","","","","");
                ?>
              </td>
            </tr>
            <tr>
              <td height="25" colspan="2" nowrap title="Exercício"><strong>Exercício</td>
              <td height="25" nowrap>&nbsp; &nbsp;</td>
  
            </tr>
            <tr>
              <td height="35"><strong>De:&nbsp; &nbsp;</strong>
                 <?
                    $DBtxt12 = db_getsession("DB_anousu"); 
                    db_input('DBtxt12',4,$IDBtxt12,true,'text',2);
                 ?>
              </td>
              <td><strong>Até:&nbsp; &nbsp;</strong>
                  <?
                    $DBtxt13 = db_getsession("DB_anousu"); 
                    db_input('DBtxt13',4,$IDBtxt13,true,'text',2);
                  ?>
               </td>
             </tr>
             <tr>
             <td colspan="2" align = "center">
                 <input name="emite2" id="emite" type="button" value="Processar" onClick="js_relatorio();">
             </td>
             </tr>
          </table>
        </form>
      </center>
	</td>
  </tr>
</table>
    <? 
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>