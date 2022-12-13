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
include("classes/db_iptucalc_classe.php");
include("dbforms/db_funcoes.php");
$cliptucalc=new cl_iptucalc;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_relatorio(){
  if (document.form1.mesini.value>document.form1.mesfim.value){
    alert("Mês inicial naum pode ser maior q o mês final!!");
  }else{
    query = "";
    query += "&mesini="+document.form1.mesini.value;
    query += "&mesfim="+document.form1.mesfim.value;
  	jan = window.open('cad3_iptupago002.php?exercicio='+document.form1.anousu.value+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  	jan.moveTo(0,0);
  }
}

</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<table border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC">    
        <form name="form1" method="post" >
        <br>
        <br>
          <table border="0" cellspacing="0" cellpadding="0">             
            <tr> 
              <td align="right">
              	<b>Exercício:</b>
              </td>
              <td >	      
				<select name="anousu" id="anousu">
			  	<?
	            $result = $cliptucalc->sql_record($cliptucalc->sql_query_file("","","distinct j23_anousu","j23_anousu desc"));
	            for($i = 0;$i < $cliptucalc->numrows;$i++){
	              db_fieldsmemory($result,$i);
	              echo "<option value=\"".($j23_anousu)."\">".($j23_anousu)."</option>\n";
	            }
	            ?>
                </select>	      
	          </td>
	        </tr>	        
	        <tr>
	           <td align="right"><b>Mês inicial:</b></td>
	           <td>
	             <?
	             $meses = array("01"=>"Janeiro","02"=>"Fevereiro","03"=>"Março","04"=>"Abril","05"=>"Maio","06"=>"Junho","07"=>"Julho","08"=>"Agosto","09"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro");
	             $mesini = "01" ;
	             db_select("mesini",$meses,true,"text",1);
	             ?>
	           </td>
	        </tr>
	        <tr>
	           <td align="right"><b>Mês final:</b></td>
	           <td>
	             <?
	             $mesfim = date("m");
	             db_select("mesfim",$meses,true,"text",1);
	             ?>
	           </td>
	        </tr>
	        <tr>	         
              <td colspan=2  align="center">
              <br>
               <input name="emite" onClick="return js_relatorio()" type="button" id="emite" value="Emite Relatório"> 
              </td>
            </tr>
          </table>
        </form>      
	</td>
  </tr>
</table>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>