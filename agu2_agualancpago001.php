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
include("classes/db_aguacalc_classe.php");
$claguacalc = new cl_aguacalc;
$claguacalc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("x22_exerc");

db_postmemory($HTTP_POST_VARS);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>

function js_emite(){
  js_OpenJanelaIframe('','db_iframe_relatorio','agu2_agualancpago002.php?anousu='+document.form1.anousu.value+'&mesfinal='+document.form1.mesfinal.value+'&tipo='+document.form1.tipo.value+'&inadimbaseada='+document.form1.inadimbaseada.value,'',true);}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
<tr>
<td width="360" height="18">&nbsp;</td>
<td width="263">&nbsp;</td>
<td width="25">&nbsp;</td>
<td width="140">&nbsp;</td>
</tr>
</table>

<table border="0" width="600" align="center">
<form name="form1" method="post" action="">
<tr>
<td >&nbsp;</td>
<td >&nbsp;</td>
</tr>
<tr>
<td ><b>Exercicio:</b></td>
<td >
<select name="anousu" >
<?
$sqlano = "select x22_exerc as anoini from aguacalc order by x22_exerc desc limit 1;";
$resultano = pg_query($sqlano) or die($sqlano);
db_fieldsmemory($resultano);

$sqlano = "select x22_exerc as anofim from aguacalc order by x22_exerc limit 1;";
$resultano = pg_query($sqlano) or die($sqlano);
db_fieldsmemory($resultano);

for($i=$anoini;$i >= $anofim;$i--){
  echo "<option value=$i>$i</option>\n";
}

?>
</select>

<td><b>Mês final:</b></td>
<td>
<select name="mesfinal" >
<?
$mesfinal=3;
for($i=1;$i<=12;$i++){
  echo "<option value=$i " . ($i == date("m",db_getsession("DB_datausu"))?" selected":"") . " >$i</option>\n";
}
?>
</select>

</td>
</tr>
<tr >
  <td >
     <strong>Tipo:&nbsp;&nbsp;</strong>
  </td>
  <td>
     <?
       $tipolista = array("a"=>"Analitico","s"=>"Sintetico");
       db_select("tipo",$tipolista,true,2);
     ?>
  </td>


  <td >
     <strong>Inadimplência baseada no valor:&nbsp;&nbsp;</strong>
  </td>
  <td>
     <?
       $inadimbaseada = array("c"=>"Calculado","a"=>"Arrecadado");
       db_select("inadimbaseada",$inadimbaseada,true,2);
     ?>
  </td>



	
</tr>

      <tr>
        <td colspan="2" align = "center"> 
          <br><input  name="emite2" id="emite2" type="button" value="Emitir Relatório" onclick="js_emite();" >
        </td>
      </tr>

</form>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>