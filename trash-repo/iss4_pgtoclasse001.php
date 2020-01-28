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
include("classes/db_iptubase_classe.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript">
<?
  $alterando=true;
?>
function js_parentiframe(iframe,confere){
  if(iframe=="alterando" && confere==true){
    var xxxx = 1;    
  }else if(iframe=="classes" && confere==true){
     // document.formaba.classes.style.color = "#666666";
     // document.formaba.classes.style.fontWeight = "normal";
     document.formaba.atividades.disabled = false;
     mo_camada('atividades',true,'Iframe2');
  }
}
function mo_camada(idtabela,mostra,camada){
   var tabela = document.getElementById(idtabela);
   var divs = document.getElementsByTagName("DIV");
   var tab  = document.getElementsByTagName("TABLE");
   var aba = eval('document.formaba.'+idtabela+'.name');
   var input = eval('document.formaba.'+idtabela);
   var alvo = document.getElementById(camada);
   for (var j = 0; j < divs.length; j++){
    if(mostra){
      if(alvo.id == divs[j].id){
         divs[j].style.visibility = "visible" ;
      }else{
         if(divs[j].className == 'tabela'){
           divs[j].style.visibility = "hidden";
         }
      }
    }else{	 
         if(alvo.id == divs[j].id){
          divs[j].stlert(dadosveri[1]);
         } 
    }
  }

  if(idtabela=='atividades'){
  
  // faz o botão da aba atitividade ficar marcado e desmarca botão da aba classes tirando o relevo
    document.getElementById('atividades').className = 'classClicado';
    document.getElementById('classes').className = 'bordas';

	dados = '';
    virgula = '';
    var F = iframe_classes.document.form1;
    
	for(i = 0;i < F.elements.length;i++) {
      if(F.elements[i].type == "checkbox" &&  F.elements[i].checked ){
        dados = dados+virgula+F.elements[i].value;
	    virgula = ',';
      }
    }
    
	document.getElementById('Iframe2_iframe').src = 'iss4_pgtoclasse002.php?dados='+dados;
  } else if(idtabela=='classes') {
  
   // faz o botão da aba classe ficar marcado e desmarca botão da aba atividade tirando o relevo
      document.getElementById('classes').className = 'classClicado';
	  document.getElementById('atividades').className = 'bordas';
  }
  
}

</script>
<style>

.classClicado {
  border: 3px outset #666666; 
  border-bottom-width: 0px;
  border-right-width: 1px ;
  border-right-color: #000000; 
  border-top-color: #3c3c3c;
  border-right-style: inset;	
}	
	
a { 
  text-decoration:none;
}

a:hover {
  text-decoration:none;
  color: #666666;
}

a:visited {
  text-decoration:none;
  color: #999999;
}

a:active {
  color: black;
  font-weight: bold; 
}  

.nomes {
  background-color: transparent;
  border:none;
  text-align: center;
  font-size: 11px;
  color: #666666;
  font-weight:normal;
  cursor: hand;
}

.nova {
  background-color: transparent;
  border:none;
  text-align: center;
  font-size: 11px;
  color: darkblue;
  font-weight:bold;
  cursor: hand;
  height:14px; 
}

.bordas {
  border: 1px outset #cccccc;
  border-bottom-color: #000000;
}

.bordasi {
  border: 0px outset #cccccc;
}

.novamat {
  border: 2px outset #cccccc;
  border-right-color: darkblue;
  border-bottom-color: darkblue;
  background-color: #999999;
}

</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_trocacordeselect();">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table valign="top" width="790" border="1" cellspacing="0" cellpadding="0">
<tr> 
  <form name="formaba" method="post" id="formaba" >
    <td height="" align="left" valign="top" bgcolor="#CCCCCC">
	<table border="0" cellpadding="0" cellspacing="0" marginwidth="0">
	  <tr>
	    <td>
              <table class="classClicado" border="0" id="classes" cellpadding="3" cellspacing="0" width="12%"> 
                <tr>
                  <td nowrap>
		    <input readonly name="classes" class="nomes" style="font-weight:bold; color:black" type="text" value="Classes" title="Lista de Classes" size="15" maxlength="7" onClick="mo_camada('classes',true,'Iframe1');"> 
	          </td>
                </tr>
              </table>
            </td>
	    <td>
              <table class="bordas" border="0" id="atividades" cellpadding="3" cellspacing="0" width="12%"> 
                <tr>
                  <td nowrap>
	  	    <input  readonly="false" name="atividades" type="text" style="font-weight:bold; color:black" value="Atividades" size="15" maxlength="10"  class="nomes"  title="Atividades"  onClick="mo_camada('atividades',true,'Iframe2');">
	          </td>
                </tr>
              </table>
            </td>
	  </tr>
	</table>
     </td>
  </form>
 </tr>
<tr>
<form name="form1" method="post" id="form1" >
      <td height="420"> <br> <br>   
	<div class="tabela" id="Iframe2" style="position:absolute; left:0px; top:47px; z-index:11; visibility: visible">
          <iframe id="Iframe2_iframe" name="iframe_atividades" frameborder="0"  src="" scrolling="auto"  height="410" width="775"></iframe>
	</div>
	<div class="tabela" id="Iframe1" style="position:absolute; left:0px; top:47px; z-index:11; visibility: visible">
          <iframe id="Iframe1_iframe" frameborder="0" name="iframe_classes"  src="iss4_pgtoclasse004.php" scrolling="auto"  height="410" width="775"></iframe> 
	</div>


     </td>
</form>
</tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

</body>
</html>