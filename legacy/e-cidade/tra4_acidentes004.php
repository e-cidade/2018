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
   function mo_camada(idtabela){
	    var camada="div_"+idtabela;
       var tabela = document.getElementById(idtabela);
       var divs = document.getElementsByTagName('DIV');
       var tab  = document.getElementsByTagName('TABLE');
       var aba = eval('document.formaba.'+idtabela+'.name');
       var input = eval('document.formaba.'+idtabela);
       var alvo = document.getElementById(camada);
       for (var j = 0; j < divs.length; j++){
            if(alvo.id == divs[j].id){
               divs[j].style.visibility = 'visible' ;
            }else{
	            if(divs[j].className == 'tabela'){
	               divs[j].style.visibility = 'hidden';
	            }
 	        }
        }
        for (var x = 0; x < tab.length; x++){
            if (tab[x].className == 'bordas'){
               for (y=0; y < document.forms['formaba'].length; y++){
     	             tab[x].style.border                          = '1px outset #cccccc';
 	                tab[x].style.borderBottomColor               = '#000000';
       	          document.formaba.condutores.style.color      = 'black';;
  	                document.formaba.condutores.style.fontWeight = 'normal';
       	          document.formaba.vitimas.style.color         = 'black';;
  	                document.formaba.vitimas.style.fontWeight    = 'normal';

                }
                if(aba == tab[x].id){
	          tab[x].style.border            = '3px outset #999999';
	          tab[x].style.borderBottomWidth = '0px';
 	          tab[x].style.borderRightWidth  = '1px';
 	          tab[x].style.borderLeftColor   =  '#000000';
 	          tab[x].style.borderTopColor    =  '#3c3c3c';
	          tab[x].style.borderRightColor  =  '#000000';
 	          tab[x].style.borderRightStyle  =  'inset';
                }
                input.style.color = 'black';
                input.style.fontWeight = 'bold';
              }
            }
          }
        </script>
        <style>
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
             border:none;
             text-align: center;
             font-size: 11px;
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
          .bordas{
	     border: 1px outset #cccccc;
             border-bottom-color: #000000;
          }
          .bordasi{
	     border: 0px outset #cccccc;
          }
          .novamat{
	     border: 2px outset #cccccc;
             border-right-color: darkblue;
             border-bottom-color: darkblue;
             background-color: #999999;
          }
 </style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#cccccc">
<table width="790" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
<table valign="top" marginwidth="0" width="100%" border="0" cellspacing="0" cellpadding="0" >
      <form name="formaba" method="post" id="formaba" >
      <tr> 
        <td height="" align="left" valign="top" bgcolor="#CCCCCC">
	  <table border="0" cellpadding="0" cellspacing="0" marginwidth="0">
   	    <tr>

      
	      <td>
                <table class="bordas" id="condutores" border="0" style="border: 3px outset #666666; border-bottom-width: 0px; border-right-width: 1px ;border-right-color: #000000; border-top-color: #3c3c3c; border-right-style: inset; " id="lote"  cellpadding="3" cellspacing="0" width="">
                  <tr>
                    <td nowrap>
			<input readonly   name="condutores" class="nomes"  style="font-weight:bold; color:black;cursor:default; background-color:#cccccc;" type="text" value="Condutores" title="Cadastro dos veiculos e Condutores" size="10"  onClick="mo_camada('condutores');">
	            </td>
                  </tr>
                </table>
              </td>
     
	      <td>

                <table class="bordas" id="vitimas" border="0" style="border: 3px outset #666666; border-bottom-width: 0px; border-right-width: 1px ;border-right-color: #000000; border-top-color: #3c3c3c; border-right-style: inset; " id="lote"  cellpadding="3" cellspacing="0" width="">
                  <tr>
                    <td nowrap>
			<input readonly   name="vitimas" class="nomes"  style="font-weight:bold; color:black;cursor:default; background-color:#cccccc;" type="text" value="Vítimas" title="Cadastro das Vitimas do Acidente" size="10"  onClick="mo_camada('vitimas');">
	            </td>
                  </tr>
                </table>
              </td>
	      
        	      
	    </tr>
 	  </table>

        </td>
      </tr>
      </form>
      <form name="form1" method="post" id="form1" >
      <tr>
        <td height="360">   
      
          <div class="tabela" id="div_condutores" style="position:absolute; left:0px; top:44px;  visibility: visible;">
            <iframe  id='condutores' name="iframe_condutores" class="bordasi" src="tra4_veiculos001.php?tr08_idacidente=<?=$tr07_id;?>" frameborder="1" marginwidth="0" leftmargin="0" topmargin="0"   height="405" scrolling="no"  width="790">
	      </iframe>

	  </div>

          <div class="tabela" id="div_vitimas" style="position:absolute; left:0px; top:44px;  visibility: visible;">
            <iframe  id='vitimas' name="iframe_vitimas" class="bordasi" src="tra4_vitimas001.php?tr10_idacidente=<?=$tr07_id;?>" frameborder="1" marginwidth="0" leftmargin="0" topmargin="0"   height="405" scrolling="no"  width="790">
	      </iframe>
	  </div>    
	    
	  </div>
        </td>
      </tr>
      </form>
      </table>

 
       <script>
       mo_camada("condutores");
       </script>

     </td>
  </tr>
  <tr>
</tr>
</table>
</body>
</html>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>