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
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>


<style type="text/css">
<!--
.tabcols {
  font-size:11px;
}
.tabcols1 {
  text-align: right;
  font-size:11px;
}
.btcols {
	height: 17px;
	font-size:10px;
}
.links {
	font-weight: bold;
	color: #0033FF;
	text-decoration: none;
	font-size:10px;
}
a.links:hover {
    color:black;
	text-decoration: underline;
}
.links2 {
	font-weight: bold;
	color: #0587CD;
	text-decoration: none;
	font-size:10px;
}
a.links2:hover {
    color:black;
	text-decoration: underline;
}
.nome {
  color:black;  
}
a.nome:hover {
  color:blue;
}
-->
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0"><tr><td height="450" align="left" valign="top" bgcolor="#CCCCCC">
  <center>    
    <form name="form1" method="post">
          <table border="0" cellspacing="0" cellpadding="0">
            <tr> 
              <td height="25"> 
  <input onClick="   document.getElementById('consulta').style.visibility = 'hidden';
"  type="button" value="Pesquisar" name="tira">
                <?=db_label("cgm","nome")?>
              </td>
              <td height="25"> 
                <?=db_text("nome",40,40,"","",3)?>
              </td>
            </tr>
            <tr> 
              <td height="25"> 
                <?=db_label("cgm","numcgm")?>
              </td>
              <td height="25"> 
                <?=db_text("numcgm",6,10,"","",1)?>
              </td>
            </tr>
            <tr> 
              <td height="25"> 
                <?=db_label("iptubase","matricula")?>
              </td>
              <td height="25"> 
                <?=db_text("matricula",6,10,"","",1)?>
              </td>
            </tr>
            <tr> 
              <td height="25"> 
                <?=db_label_blur("ruas","Rua/Avenida","codigoruas","nomeruas")?>
              </td>
              <td height="25"> 
                <?=db_text_blur("ruas","codigoruas","nomeruas",6,10,"","",1)?>
                <?=db_text_blur("ruas","nomeruas","codigoruas",40,10,"","",1)?>
              </td>
            </tr>
            <tr> 
              <td height="25"> 
                <?=db_label("bairro","Bairro","codigobai")?>
              </td>
              <td height="25"> 
                <?=db_text("codigobai",6,10,"","",1)?>
              </td>
            </tr>
            <tr>
              <td height="25">
		       <?=db_label("setqualot","Setor/Quadra/Lote","setqualot")?>
			  </td>
              <td height="25">
                <?=db_text("setqualot",6,10,"","",1)?>
			  </td>
            </tr>
            <tr> 
              <td height="25">&nbsp;</td>
              <td height="25">
			  <input onClick="if((this.form.dbh_nome.value=='' && this.form.db_numcgm.value=='' && this.form.db_matricula.value=='' && this.form.db_codigoruas.value=='' && this.form.db_codigobai.value=='' && this.form.db_setqualot.value=='')) { alert('Informe Nome, Numcgm, Matricula, Ruas/Avenida, Bairro ou Setor/Quadra/Lote.');return false; }"  type="submit" value="Pesquisar" name="pesquisar"></td>
            </tr>
          </table>
        </form>
  <?    
	//Executa um SELECT e pagina na tela

  $sql="select j01_matric as Matricula, z01_nome as Proprietário,j34_setor as Setor, j34_quadra as Quadra, j34_lote as Lote         from iptubase 		     inner join  cgm  on j01_numcgm = z01_numcgm		     inner join  lote on j34_idbql = j01_idbql";
   // db_brlovv($sql,15,"/tmp/x.html",$HTTP_POST_VARS["filtro"]) ;
?>
  <input onClick="js_execute();"  type="button" value="Pesquisar" name="pesquisar">
	 <iframe style="position:absolute; left:30px; top:40px; z-index:1; visibility: hidden; border: 1px none #000000; background-color: #666699; layer-background-color: #666699;"  id="consulta" name="consulta" scrolling="auto" width="700" height="300">
     </iframe>
  </center>
</td></tr>
</table>
<? 
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_execute() {
   alert('ok');
   document.getElementById("consulta").style.visibility = 'visible';
   consulta.location.href = "libs/db_browse.php?query=<?=$sql?>&numlinhas=13&arquivo=&filtro=%&aonde=_self&mensagem=Clique Aqui&NomeForm=NoMe";
}
</script>