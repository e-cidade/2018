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
include("classes/db_db_config_classe.php");

$cldbconfig = new cl_db_config;

?>

<html>
<head>
<script>
	
	// chama arquivo que processará o relatório passando a instituição por get
	function js_processa_rel() {
		var instituicao = document.form1.instit.value;
		var listabens   = document.form1.listabens.value;
		jan = window.open('cus2_custoplanorelatorio002.php?instit='+instituicao+'&listabens='+listabens,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
	}
</script>

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

<center>
  <form name="form1" style="padding-top:15px; width:650px">
  <fieldset>
  <legend> <b> Relatório de custos </b> </legend>

<table>
  <tr>    	
    <td>
	    <label> <b> Instituição: </b> </label>
			<?			
			  $rsConsultaInst = $cldbconfig->sql_record($cldbconfig->sql_query_file(null,"codigo, nomeinst","codigo")); 
			  db_selectrecord("instit",$rsConsultaInst,false,2);
			?>
    </td>
  </tr>
  <tr>
    <td>
		<label> <b> Listar bens: </b> </label>
    <?			
      $aOpcoes = array("n" => "Não", "s"=> "Sim");
      db_select("listabens", $aOpcoes, false, 2);
    ?>	
	  </td>		
	</tr>    
</table>

  </fieldset>
	  <input type="button" value="Processar" onclick="js_processa_rel();"/>
  </form>
</center>

<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

</body>
</html>