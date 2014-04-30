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
db_postmemory($HTTP_POST_VARS);
$clrotulo = new rotulocampo;
$clrotulo->label("x21_exerc");
$clrotulo->label("x21_mes");
$clrotulo->label("x21_excesso");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_emite(){
  query = "";
  query	+= "&ano="+document.form1.ano.value;
  query	+= "&mes="+document.form1.mes.value;
  query	+= "&exc_ini="+document.form1.exc_ini.value;
  query	+= "&exc_fim="+document.form1.exc_fim.value;
//  query	+= "&ordem="+document.form1.ordem.value;
  jan = window.open('agu2_relconexc002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
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
  <table  align="center">
    <form name="form1" method="post" action="">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr>
         <td ><b>Ano/Mês :</b>&nbsp;</td>
         <?
         $ano = date("Y",db_getsession("DB_datausu"));
         $mes = date("m",db_getsession("DB_datausu"))
         ?>
         <td ><?db_input("ano",4,@$Ix21_exerc,true,"text",1); ?><b>/</b><? db_input("mes",2,@$Ix21_mes,true,"text",1); ?>&nbsp;</td>
      </tr>
      <tr>
         <td ><b>Excesso :</b>&nbsp;</td>
         <td ><?db_input("exc_ini",12,@$Ix21_excesso,true,"text",1); ?><b>a</b><? db_input("exc_fim",12,@$Ix21_excesso,true,"text",1); ?>&nbsp;</td>
      </tr>      

<?
/*
      <tr>
        <td >
        <strong>Ordem Excesso:&nbsp;&nbsp;</strong>
        </td>
        <td>
	  	<? 
	  	$tipo_ordem = array("c"=>"Crescente","d"=>"Decrescente");
	  	db_select("ordem",$tipo_ordem,true,2); 
	  	?>
        </td>
      </tr>
*/
?>




      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
        </td>
      </tr>
    </form>
  </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>