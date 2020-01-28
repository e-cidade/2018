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
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
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
	<table align="center" border="0">
		<tr>
			<td>&nbsp;</td>
		</tr>  
		<tr>
			<td>
		    <form name="form1" method="post" action="">
					<fieldset><legend><b> Per�odo de Pagamento  </b></legend>
					  <table align="center" > 
								  <?
										$dtd = date("d",db_getsession("DB_datausu"));
										$dtm = date("m",db_getsession("DB_datausu"));
										$dta = date("Y",db_getsession("DB_datausu"));
									?>		
							<tr>
							  <td align="right">
							 	 <b>Data Inicial :</b>
							  </td>  
							  <td>	
							 		 <?
							 				db_inputdata("datai","$dtd","$dtm","$dta","true","text",2);
							 		 ?>   
							  </td>
							</tr>
							<tr>
							  <td align="right">
							 	 <b>Data Final &nbsp; :</b>  
							  </td>
							  <td>
							 		 <?
							 				db_inputdata("dataf","$dtd","$dtm","$dta","true","text",2);      
							 		 ?> 
							  </td>
							</tr>
						</table> 
					</fieldset>
					<table align="center">
						<tr>
						  <td align="left" nowrap title="" >
								<strong>Tipo : </strong>
              </td>
              <td align="left">
							  <?
                  $xx = array("a"=>"Anal�tico","s"=>"Sint�tico");
								  db_select('seltipo',$xx,true,4,"");
                ?>&nbsp;&nbsp;&nbsp;
              </td>
					  </tr> 
					</table>
            <tr>
              <td colspan="2" align = "center"> 
                <input  name="emite2" id="emite2" type="button" value="Emitir Relat�rio" onclick="js_emite();" >
              </td>
            </tr>
		    
				</form>
			</td>
		</tr>
	</table>
	 <?
			db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
	 ?>
</body>
</html>
<script>
	function js_emite(){
 
		qry  =	'?seltipo='+document.form1.seltipo.value;
	  qry +=	'&datai='+document.form1.datai_ano.value+'-'+document.form1.datai_mes.value+'-'+document.form1.datai_dia.value;
	  qry +=  '&dataf='+document.form1.dataf_ano.value+'-'+document.form1.dataf_mes.value+'-'+document.form1.dataf_dia.value;
    
		jan = window.open('cai2_reldescontos002.php'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
	  jan.moveTo(0,0);

	}
</script>