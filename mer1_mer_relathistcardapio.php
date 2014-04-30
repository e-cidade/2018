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
include("classes/db_mer_desperdicio_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clmer_desperdicio = new cl_mer_desperdicio;
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<form name="form1" method="post" action="">
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <br><br>
    <center>
    <fieldset style="width:55%"><legend><b>Historico de Cardapios</b></legend>
    <center>
	<table border="0">
	  <tr>    
	    <td> 
	        <fieldset><legend><b> Automatico </b></legend>
	        <table border="0">
	          <tr>  
	             <td> 
	                <select name="periodo" value="0">
	                  <option value="0"> </option>
	                  <option value="1">Semana</option>
	                  <option value="2">Mês</option>
	                </select>
	             </td>
	          </tr>
	          <tr>   
	             <td> 
	                <input name="Processar" type="button" value="Processar" onclick="js_consulta1();">
	             </td>
	          <tr>
	        </table>      
	        </fieldset>
	    </td>
	    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
	    <td>
	       <fieldset><legend><b> Por Período </b></legend>
	       <table border="0">
	          <tr>
	            <td>Inicio</td>
	            <td><?db_inputdata('dataini',@$diai,@$mesi,@$anoi,true,'text',1,"");?></td>
	          </tr>
	          <tr>
	            <td>Fim</td>   
	            <td><?db_inputdata('datafim',@$diaf,@$mesf,@$anof,true,'text',1,"");?></td>
              </tr>
              <tr>  
                <td colspan="2"><input name="Processar" type="button" value="Processar" onclick="js_consulta2()"></td>	      
              </tr>
           </table>   
	       </fieldset>
	    </td>
	  </tr>    
      <tr>
         <td colspan="3" align="center">   
           <fieldset><legend>Imprimir</legend>
           <table>   
             <tr> 
              <td align="center">
               <input type="checkbox" name="tp1" id="tp1" checked>Sintético &nbsp;&nbsp;&nbsp;
               <input type="checkbox" name="tp2" id="tp2" checked>Analítico
              </td>
             </tr> 
           </table>
           </fieldset>
         </td>
      <tr>
    </table>
    </center>
	</fieldset>
</form>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),
        db_getsession("DB_modulo"),
        db_getsession("DB_anousu"),
        db_getsession("DB_instit")
       );
?>
</body>
</html>
<script>
function js_consulta1() {
	
  opcao   = 1;
  periodo = document.form1.periodo.value;
  tp1     = document.form1.tp1.checked;
  tp2     = document.form1.tp2.checked;
  if ((tp1 == false) && (tp2 == false)) {
    alert('Marque no mínimo um tipo para impressão');
  } else {
	  
    if (periodo==0) {
      alert('Selecione um período');
    } else {
         
      jan = window.open('mer1_mer_cardapiorelat.php?opcao='+opcao+'&tp1='+tp1+'&tp2='+tp2+'&periodo='+periodo,'',
    	                 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
    	               );
      jan.moveTo(0,0);
      
    }
  }  
}

function js_consulta2() {
	
  opcao  = 2;
  dat    = document.form1.dataini.value;
  inicio = dat.substr(6,4)+'-'+dat.substr(3,2)+'-'+dat.substr(0,2);
  dat    = document.form1.datafim.value;
  fim    = dat.substr(6,4)+'-'+dat.substr(3,2)+'-'+dat.substr(0,2);
  tp1    = document.form1.tp1.checked;
  tp2    = document.form1.tp2.checked;
  if ((tp1 == false) && (tp2 == false)) {
    alert('Marque no mínimo um tipo para impressão');
  } else {
	    
    if (inicio=='') {
      alert('Entre com a data inicial!');
    } else {
             
      if (fim=='') {
        alert('Entre com a data final!');
      } else {
          
        jan = window.open('mer1_mer_relathistcardapios002.php?opcao='+opcao+'&tp1='+tp1+'&tp2='+tp2+'&inicio='+inicio+
                           '&fim='+fim,'',
                           'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
                         );
        jan.moveTo(0,0);      
        
      }
    }    
  }    
}
</script>