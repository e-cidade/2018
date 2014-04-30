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
include("classes/db_cgm_classe.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$clrotulo = new rotulocampo;
$clcgm = new cl_cgm;
if(isset($q02_numcgm)){
      $result01=$clcgm->sql_record($clcgm->sql_query_file($q02_numcgm,"z01_ident,z01_munic,z01_nome,z01_incest,z01_cgccpf,z01_cep,z01_ender,z01_bairro,z01_compl as q02_compl,z01_numero as q02_numero,z01_cxpostal as q02_cxpost"));
      if($clcgm->numrows!=1){
	db_redireciona('iss1_issbase004.php?invalido=true');
	exit;    
      }else{
	db_fieldsmemory($result01,0);
	if($z01_cep==""){
	  db_redireciona('iss1_issbase004.php?cep=true');
	  exit;    
	}
	if($z01_cgccpf==""){
	  db_redireciona('iss1_issbase004.php?cgccpf=true');
   	  exit;    
	}
      }	
      $db_opcao=1;
}else if(isset($q02_inscr)){
      $result01=$clissbase->sql_record($clissbase->sql_query_file($q02_inscr));
      if($clissbase->numrows<1){
	db_redireciona('iss1_issbase005.php?invalido=true');
	exit;    
      }	
      $db_opcao=2;
}	
?>
  <html>
  <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
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
     for(var x = 0; x < tab.length; x++){
       if(tab[x].className == 'bordas'){
         for(y=0; y < document.forms['formaba'].length; y++){
     	   tab[x].style.border = "1px outset #cccccc";
 	   tab[x].style.borderBottomColor = "#000000";
	   document.formaba.issbase.style.color = "#666666";
	   document.formaba.issbase.style.fontWeight = "normal";
 	   document.formaba.atividades.style.color = "#666666";
	   document.formaba.atividades.style.fontWeight = "normal";
  	   document.formaba.socios.style.color = "#666666";
	   document.formaba.socios.style.fontWeight = "normal";
         }
         if(aba == tab[x].id){
	   tab[x].style.border = "3px outset #999999";
	   tab[x].style.borderBottomWidth = "0px";
 	   tab[x].style.borderRightWidth = "1px";
 	   tab[x].style.borderLeftColor =  "#000000";
 	   tab[x].style.borderTopColor =  "#3c3c3c";
	   tab[x].style.borderRightColor =  "#000000";
 	   tab[x].style.borderRightStyle =  "inset";
         }
         input.style.color = "black";
         input.style.fontWeight = "bold";
       }  	 
   }
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<style>
a {text-decoration:none;
  }
a:hover {text-decoration:none;
         color: #666666;
        }
a:visited {text-decoration:none;
           color: #999999;
          }
a:active {
          color: black;
          font-weight: bold; 
         }  
.nomes {background-color: transparent;
        border:none;
        text-align: center;
        font-size: 11px;
        color: #666666;
        font-weight:normal;
        cursor: hand;
       }
.nova {background-color: transparent;
       border:none;
       text-align: center;
       font-size: 11px;
       color: darkblue;
       font-weight:bold;
       cursor: hand;
       height:14px; 
       }
.bordas{border: 1px outset #cccccc;
        border-bottom-color: #000000;
       }
.bordasi{border: 0px outset #cccccc;
        }
.novamat{border: 2px outset #cccccc;
         border-right-color: darkblue;
         border-bottom-color: darkblue;
         background-color: #999999;
}
</style>
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table valign="top" marginwidth="0" width="790" border="0" cellspacing="0" cellpadding="0" >
  <tr> 
<form name="formaba" method="post" id="formaba" >
    <td height="" align="left" valign="top" bgcolor="#CCCCCC">
        <input type="hidden"  name="abcdefghij" value="teste"> 
	<table border="0" cellpadding="0" cellspacing="0" marginwidth="0">
	  <tr>
	    <td>
              <table class="bordas" border="0" style="border: 3px outset #666666; border-bottom-width: 0px; border-right-width: 1px ;border-right-color: #000000; border-top-color: #3c3c3c; border-right-style: inset; " id="lote"  cellpadding="3" cellspacing="0" width="12%"> 
                <tr>
                  <td nowrap>
		    <input readonly name="issbase" class="nomes" style="font-weight:bold; color:black" type="text" value="Inscrição" title=" Cadastro de Inscrição" size="8" maxlength="12" onClick="mo_camada('issbase',true,'Iframe1');"> 
	          </td>
                </tr>
              </table>
            </td>
	    <td>
              <table border="0" class="bordas" id="" cellpadding="3" cellspacing="0" width="12%"> 
                <tr>
                  <td  id="link_ativ" nowrap>
		    <input disabled  readonly name="atividades" type="text" value="Atividades" size="10" maxlength="10"  class="nomes"  title="Atividades"  onClick="mo_camada('atividades',true,'Iframe2');">
	          </td>
                </tr>
              </table>
            </td>
	    <td>
              <table border="0" class="bordas" id="socios" cellpadding="3" cellspacing="0" width="12%"> 
               <tr>
		  <td nowrap id="link_soc">
		    <input disabled readonly type="text" value="Sócios" size="12" maxlength="12"  class="nomes"  name="socios" title="Sócios" onClick="mo_camada('socios',true,'Iframe3');">
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
      <td nowrap>  
      </td>
      <td height="360">   
      <br><br>   
        <div class="tabela" id="Iframe1" style="position:absolute; left:0px; top:44px;  visibility: visible;">
          <iframe  id='issbase' name="iframe_issbase" class="bordasi" frameborder="0" marginwidth="0" leftmargin="0" topmargin="0"   height="410" scrolling="no"  width="785">
          </iframe>
	</div>
	<div class="tabela" id="Iframe2" style="position:absolute; left:0px; top:44px;  visibility:hidden;">
          <iframe id="atividades"  class="bordasi"  frameborder="0" name="iframe_atividades"   leftmargin="0" topmargin="0"  scrolling="no"  height="410" width="785">
          </iframe> 
   	</div>
	<div class="tabela" id="Iframe3" style="position:absolute; left:0px; top:44px; visibility: hidden;">
          <iframe name="iframe_socios"   class="bordasi" frameborder="0"  leftmargin="0" topmargin="0"   scrolling="no"  height="410" width="785">
	  </iframe>
	</div>
        <div id="load"  style="position:absolute; left:300px; top:167px; visibility: visible;">
   	   Processando.&nbsp;Aguarde...
        </div>
     </td>
     </form>
  </tr>
<tr>
</tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if($db_opcao==1){
  echo "
         <script>
	   function js_src(){
            iframe_issbase.location.href='iss1_issbase014.php?q02_numcgm=$q02_numcgm';\n
            iframe_atividades.location.href='iss1_tabativ004.php';\n
	    iframe_socios.location.href='iss1_socios004.php';\n
	   }
	   js_src();
         </script>
       "; 
}else if($db_opcao==2){
  echo "
         <script>
	   function js_src(){
            iframe_issbase.location.href='iss1_issbase015.php?chavepesquisa=$q02_inscr';\n
            iframe_atividades.location.href='iss1_tabativ004.php?chavepesquisa=$q02_inscr';\n
	    iframe_socios.location.href='iss1_socios004.php?x=$q02_inscr';\n
	   }
	   js_src();
         </script>
       "; 
}
?>