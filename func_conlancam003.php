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
include("classes/db_conlancamval_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_conlancam_classe.php");
include("classes/db_conlancamcompl_classe.php");
include("classes/db_conlancamdig_classe.php");
include("classes/db_conplano_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clconplano     = new cl_conplano;
$clconlancamval = new cl_conlancamval;
$clconlancamcompl = new cl_conlancamcompl;
$clconlancamdig   = new cl_conlancamdig;
$clconlancam      = new cl_conlancam;

$db_opcao = 33;
$db_botao = false;
$anousu = db_getsession("DB_anousu");


 if(isset($chavepesquisa)){
       $sql = " select c70_codlan,
		       c69_debito,
		       c1.c60_descr,
		       c69_credito,
                       c2.c60_descr,
		       c69_valor
                from conlancam		     
                     inner join conlancamval on c70_codlan = c69_codlan

		     inner join conplanoreduz red1 on red1.c61_reduz=conlancamval.c69_debito and red1.c61_anousu =conlancamval.c69_anousu
		     inner join conplano c1 on c1.c60_codcon=red1.c61_codcon and c1.c60_anousu=red1.c61_anousu

                     inner join conplanoreduz red2 on red2.c61_reduz=conlancamval.c69_credito and red2.c61_anousu =conlancamval.c69_anousu
		     inner join conplano c2 on c2.c60_codcon=red2.c61_codcon and c2.c60_anousu=red2.c61_anousu

		     
		     
		where  c70_codlan=$chavepesquisa
 	        order by c70_codlan
              ";
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_conlancam003.hide();">
 
	<?
	if (isset($sql)) {
	    $js_funcao="";
            db_lovrot($sql,15,"()","","$js_funcao");
	}   
        echo "</form>";
	?>
   </center>
	</td>
  </tr>
</table>
</body>
</html>