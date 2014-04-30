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
require("libs/db_stdlibwebseller.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$escola = db_getsession("DB_coddepto");
if (!isset($fim)) {
	
  $fim_dia    = date("d",db_getsession("DB_datausu"));
  $fim_mes    = date("m",db_getsession("DB_datausu"));
  $fim_ano    = date("Y",db_getsession("DB_datausu"));
  $fim        = $fim_dia."/".$fim_mes."/".$fim_ano;
  $inicio_dia = date("d",db_getsession("DB_datausu"));
  $inicio_mes = date("m",db_getsession("DB_datausu"));
  $inicio_ano = date("Y",db_getsession("DB_datausu"));
  $inicio     = $inicio_dia."/".$inicio_mes."/".$inicio_ano;
  
} else {
	
  $fim_dia    = substr($fim,8,2);
  $fim_mes    = substr($fim,5,2);
  $fim_ano    = substr($fim,0,4);
  $fim        = $fim_dia."/".$fim_mes."/".$fim_ano;
  $inicio_dia = substr($inicio,8,2);
  $inicio_mes = substr($inicio,5,2);
  $inicio_ano = substr($inicio,0,4);
  $inicio     = $inicio_dia."/".$inicio_mes."/".$inicio_ano;
  
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<form name="form1" method="post" action="">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left" valign="top" bgcolor="#CCCCCC">   
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Histórico de Refeições</b></legend>
    <table border="0" width="100%" cellpadding="0" cellspacing="0">
     <tr>
      <td>
       <fieldset><legend><b>Tipo</b></legend>
        <table border="0" cellpadding="0" cellspacing="0">
         <tr>
          <td>
           <input type="radio" name="tp1" value="1" checked>Sintético &nbsp;&nbsp;&nbsp;
           <input type="radio" name="tp1" value="2" >Analítico
          </td>
         </tr>
        </table>
       </fieldset>
      </td>
      <td>
       <fieldset><legend><b>Automático</b></legend>
        <table border="0" cellpadding="0" cellspacing="0">
         <tr>
          <td>
           <select name="periodo" value="0">
            <option value="0" <?=@$periodo=="0"?"selected":""?>> </option>
            <option value="1" <?=@$periodo=="1"?"selected":""?>>Semana</option>
            <option value="2" <?=@$periodo=="2"?"selected":""?>>Mês</option>
           </select>
          </td>
          <td>
           <input name="consultar" type="button" value="Processar" onclick="js_consulta1();">
          </td>
         <tr>
        </table>
       </fieldset>
      </td>
      <td>
       <fieldset><legend><b>Por Período</b></legend>
        <table border="0" cellpadding="0" cellspacing="0">
         <tr>
          <td><b>De&nbsp;&nbsp;</b></td>
          <td><?db_inputdata('inicio',@$inicio_dia,@$inicio_mes,@$inicio_ano,true,'text',1,"");?></td>
	      <td><b>&nbsp;&nbsp;até&nbsp;&nbsp;</b></td>
	      <td><?db_inputdata('fim',@$fim_dia,@$fim_mes,@$fim_ano,true,'text',1,"");?></td>
          <td><input name="consultar" type="button" value="Processar" onclick="js_consulta2()"></td>
         </tr>
        </table>
       </fieldset>
      </td>
     </tr>
    </table>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</form>
<?db_menu(db_getsession("DB_id_usuario"),
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
  if (periodo==0) {
    alert('Selecione um periodo!');
  } else {
	  
    for (x=0; x<2; x++) {
        
      if (document.form1.tp1[x].checked==true) {
        tp1=document.form1.tp1[x].value;
      }     
    }
    if (tp1==1) {
        
      jan = window.open('mer2_mer_cardapiorelat.php?opcao='+opcao+'&periodo='+periodo,'',
    	                'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);
      
    } else {
        
      jan = window.open('mer2_mer_relathistcardapios002.php?opcao='+opcao+'&tp1='+tp1+'&periodo='+periodo,'',
    	                'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
    	               );
      jan.moveTo(0,0);
      
    }    
  }
}

function js_consulta2() {
	
  opcao = 2;
  if (document.form1.inicio.value=="" || document.form1.fim.value=="") {
	  
    alert('Informe a data inicial e data final!');
    return false;
    
  }
  inicio = document.form1.inicio.value.substr(6,4)+''+
           document.form1.inicio.value.substr(3,2)+''+
           document.form1.inicio.value.substr(0,2);
  fim    = document.form1.fim.value.substr(6,4)+''+
           document.form1.fim.value.substr(3,2)+''+
           document.form1.fim.value.substr(0,2);
  if (parseInt(inicio)>parseInt(fim)) {
    alert('Data inicial deve ser menor ou igual que a data final!');
  } else {
	  
    inicio = document.form1.inicio.value.substr(6,4)+'-'+
             document.form1.inicio.value.substr(3,2)+'-'+
             document.form1.inicio.value.substr(0,2);
    fim    = document.form1.fim.value.substr(6,4)+'-'+
             document.form1.fim.value.substr(3,2)+'-'+
             document.form1.fim.value.substr(0,2);
    for (x=0; x<2; x++) {
        
      if (document.form1.tp1[x].checked==true) {
        tp1=document.form1.tp1[x].value;
      }     
    }
    if (tp1==2) {
        
      jan = window.open('mer2_mer_relathistcardapios002.php?opcao='+opcao+'&inicio='+inicio+'&fim='+fim,'',
    	                'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
		               );
      jan.moveTo(0,0);
      
    } else {
        
      jan = window.open('mer2_mer_cardapiorelat.php?opcao='+opcao+'&inicio='+inicio+'&fim='+fim,'',
    	                'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
		               );
      jan.moveTo(0,0);
      
    }
  }  
}
</script>