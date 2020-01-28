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
include("libs/db_usuariosonline.php");
include("classes/db_caracter_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
//include("classes/db_sanitario_classe.php");
include("classes/db_iptucalc_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$cliptucalc = new cl_iptucalc;

echo "<script>parent.iframe_g2.location.href = 'cad2_estatiptu004.php'</script>";
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1; js_mostratipo();" >
  <table>
		<tr height="20">
		</tr>
		<tr>
			<td width="50">
			</td>
			<td>	
				<form name="form1" id="form1" method="post">
				<fieldset><legend><b> Opções de Filtro :&nbsp;</b></legend>
					<table  border="0" cellspacing="" cellpadding="2">
            <tr> 
              <td height="20" align="left" valign="top" bgcolor="#CCCCCC"></td>
            </tr>  
		   
						<input type='hidden' name='bairro'>
						<input type='hidden' name='setor'>
						
						<tr>
            	<td colspan="2" ><b>Exercício Inicial :&nbsp;</b>
							</td>
							<td>   
								<select name="anoexei" id="anoexei">
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
               <td colspan="2" ><b>Exercício Final :&nbsp;</b>
							 </td>
							 <td>	
									<select name="anoexef" id="anoexef">
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
							<td colspan="2"><b>Agrupar por :&nbsp;</b>
							</td>
							<td> 
							 <?
									$x = array("m"=>"Matrícula","b"=>"Bairro","s"=>"Setor");
									db_select("selagrupa",$x,false,2,"onChange='js_mostratipo();'");
								?>
						  </td>
            </tr>
						
						<tr id="lnordem">
							<td colspan="2" ><b>Ordem	:&nbsp;</b>
							</td>
							<td>    
							  <?
									$x = array("m"=>"Matrícula",
														 "n"=>"Nome",
														 "b"=>"Bairro",
														 "s"=>"Setor");
									db_select("selordem",$x,false,2);
								?>
						  </td>
            </tr>
						
						<tr id="lntipo">
							<td colspan="2" ><b>Tipo	:&nbsp;</b>
							</td>
							<td>    
							  <?
									$x = array("a"=>"Analítica","s"=>"Sintética");
									db_select("seltipo",$x,false,2);
								?>
						  </td>
            </tr>
					
					</table>
				</fieldset>
				<center> 
					<table>				
						<tr>
							<td>  
								<input type="button" name="enviaRelatorio" value="Relatório" onClick="js_emite();"/>
							</td>
					  </tr>
					</table>
					</center>
						<script>
						
						function js_emite(){
								 
								 if(document.form1.anoexei.value > document.form1.anoexef.value){
									 alert ("Exercício Incial deve ser menor que Exercício Final!");
									 return false;
								 }

									 qry  = "?anoexei="+document.form1.anoexei.value;
									 qry += "&anoexef="+document.form1.anoexef.value;
									 qry += "&selagrupa="+document.form1.selagrupa.value;
									 qry += "&seltipo="+document.form1.seltipo.value;
									 qry += "&selordem="+document.form1.selordem.value;
									 qry += "&setor="+document.form1.setor.value;
									 qry += "&bairro="+document.form1.bairro.value;
              
									 js_OpenJanelaIframe('','db_iframe','cad2_estatiptu002.php'+qry,'',true);
								 }

					</script>
			  </form>
			</td>
	  </tr>
  </table>
</body>
</html>
<script>
	
	function js_mostratipo(){
	  if(document.form1.selagrupa.value  == "m"){
		  document.getElementById('lnordem').style.display = "";
	  }else{
		  document.getElementById('lnordem').style.display = "none";
	  }
  }

</script>