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
<script>
function js_relatorio() {
  var F = document.form1;
  var C = document.getElementById("conta");
  var datai = "";
  if(F.datai_ano.value!="" && F.datai_mes.value!="" && F.datai_dia.value!=""){
    datai = F.datai_ano.value+'-'+F.datai_mes.value+'-'+F.datai_dia.value;
  }
  var dataf = "";
  if(F.dataf_ano.value!="" && F.dataf_mes.value!="" && F.dataf_dia.value!=""){
    dataf = F.dataf_ano.value+'-'+F.dataf_mes.value+'-'+F.dataf_dia.value;
  }
  var aux  = "";
  var car  = "";
  var aux1 = "";
  var car1 = "";
  var cont = 0;
  var lista= "";
  for(var i = 0;i < C.options.length;i++) {
    if(C.options[i].selected == true) {
      aux  += car + C.options[i].value;
      car  = ",";
      cont++;
    }else{
      aux1 += car1 + C.options[i].value;
      car1 = ",";
    }
  }
  if(cont==0){
    lista = aux1;
  }else{
    lista = aux;
  }
  jan = window.open('cai3_emissbol003.php?conta='+lista+'&caixa='+F.caixa.value+'&datai='+datai+'&dataf='+dataf,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
function js_mt() {
  var F = document.getElementById("conta");
  for(var i = 0;i < F.options.length;i++)
    F.options[i].selected = document.form1.mt.checked;
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="if(document.form1) document.form1.elements[0].focus();" >
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
	<?
	/*
	  if(!isset($HTTP_POST_VARS["pesquisar"])) {
	 */
	?>
        <form name="form1" method="post">
	<br><br>
          <table width="40%" border="0" cellspacing="0" cellpadding="0">
            <tr> 
              <td width="59%" height="25" nowrap><table border="0" cellspacing="0" cellpadding="0">
                  <tr> 
                    <td width="22%" nowrap><strong>Data Inicial:</strong></td>
                    <td width="78%" nowrap> &nbsp;&nbsp; 
                     <?=db_inputdata('datai',null,null,null,true,'text',1);?>
                    </td>
                  </tr>
                  <tr> 
                    <td nowrap><strong>Data Final:</strong></td>
                    <td nowrap>&nbsp;&nbsp;
                     <?=db_inputdata('dataf',null,null,null,true,'text',1);
                    ?> 
                    </td>
                  </tr>
                </table>
                <strong> </strong></td>
              <td width="41%" height="25" nowrap><strong> &nbsp; </strong></td>
            </tr>
            <tr valign="bottom"> 
              <td height="25" nowrap><strong>Conta:</strong><br>			  
                <input type="checkbox" accesskey="m" name="mt" id="mt" onClick="js_mt()">
                <label for="mt">Marcar Todas</label>
			  </td>
              <td height="25" nowrap><strong>&nbsp;Caixa:</strong></td>
            </tr>
            <tr> 
              <td height="25" nowrap> <select name="conta[]" size="10" id="conta" multiple>
                  <?
//            	     $result = pg_exec("select k13_reduz,k13_descr from saltes inner join conplanoexe on c62_reduz = k13_reduz and c62_anousu = " . db_getsession("DB_anousu") . " inner join conplanoexe on c62_reduz = c61_reduz and c62_anousu = c61_anousu and c61_instit = " . db_getsession("DB_instit"));
            	     $result = pg_exec("select k13_reduz, k13_descr 
		                        from saltes 
					     inner join conplanoexe   on c62_reduz  = k13_reduz and 
					                                 c62_anousu = " . db_getsession("DB_anousu") . 
                                       "     inner join conplanoreduz on c62_reduz  = c61_reduz and 
				                                         c62_anousu = c61_anousu and 
								         c61_instit = " . db_getsession("DB_instit"));
                     $numrows = pg_numrows($result);
		     for($i = 0;$i < $numrows;$i++)
		          echo "<option value=\"".pg_result($result,$i,0)."\">".pg_result($result,$i,0)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".pg_result($result,$i,1)."</option>\n";
	          ?>
                </select> </td>
              <td height="25" valign="top" nowrap> &nbsp; 
			  <select name="caixa" id="caixa">
			  <option value="T">Todos</option>
                  <?
				 $result = pg_exec("select k11_id,k11_ipterm from cfautent where k11_instit = " . db_getsession("DB_instit") . " order by k11_ipterm");
				 $numrows = pg_numrows($result);
				 for($i = 0;$i < $numrows;$i++)
				   echo "<option value=\"".pg_result($result,$i,0)."\">".pg_result($result,$i,1)."</option>\n";
				  ?>
                </select> </td>
            </tr>
            <tr>
              <td height="25" nowrap><strong>
                <input name="pesquisar" onClick="js_relatorio()" type="button" id="pesquisar" value="Pesquisar">
                </strong></td>
              <td height="25" valign="top" nowrap>&nbsp;</td>
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