<?php
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

/**
 * 
 * @author Luiz Marcelo Schmitt
 * @revision $Author: dbluizmarcelo $
 * @version $Revision: 1.2 $
 * 
 */

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
require("std/db_stdClass.php");
require("libs/db_app.utils.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, prototype.js, strings.js");
  db_app::load("estilos.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
	<form action="" method="get">
		<table width="790" border="0" cellspacing="0" cellpadding="0">
		  <tr>
		    <td>&nbsp;</td>
		  </tr>
		  <tr>
		    <td>&nbsp;</td>
		  </tr>
		</table>
		<table align="center" width="590" border="0" cellspacing="0" cellpadding="0">
		 <tr>
		   <td>
        <fieldset>
         <legend><b>Relatório Anulação de Entrada de Ordem de Compra - Filtros</b></legend>
		     <table>
			     <tr>
			       <td nowrap title="">
			         <?
			           db_ancora('<b>Almoxarifado:</b>',"js_pesquisam91_depto(true);",1);
			         ?>
			       </td>
			       <td> 
			         <?  
			           db_input('m91_depto',10,'',true,'text',1,"onchange='js_pesquisam91_depto(false);'");
			           db_input('descrdepto',40,'',true,'text',3)
			         ?>
			       </td>
			     </tr>
           <tr>
             <td nowrap title="">
               <b>Período:</b>
             </td>
             <td> 
               <? 
                 db_inputdata('dtInicial',null,null,null,true,'text',1,"");                
                  echo "<b> a </b> ";
                 db_inputdata('dtFinal',null,null,null,true,'text',1,"");
               ?>
             </td>
           </tr>
		     </table>
        </fieldset>
		   </td>
		 </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
		 <tr align="center">
		   <td>
		     <input  name="visualizar" id="visualizar" type="button" value="Visualizar" onclick="js_visualizarRelatorio();" >
		   </td>
		 </tr>
		</table>
	</form>
</center>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
function js_visualizarRelatorio() {
  
  var coddpto    = $F('m91_depto');
  var dtInicial = $F('dtInicial');
  var dtFinal   = $F('dtFinal');
  
  if (dtInicial == "" || dtFinal == "") {
  
    alert('Informe do período!');
    if (dtInicial == "") {
      $('dtInicial').focus();
      return false;
    }
    
    if (dtFinal == "") {
      $('dtFinal').focus();
      return false;
    }
  }
  
  var sUrl  = "mat2_anulacaoentradaordemcompra002.php?";
      sUrl += "&coddpto="+coddpto;
      sUrl += "&dtInicial="+dtInicial;
      sUrl += "&dtFinal="+dtFinal;
      
  var jan   = window.open(sUrl,'','width='+(screen.availWidth-5)+',height='+
                         (screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
function js_pesquisam91_depto(mostra) {

  var sUrl1 = 'func_db_almox.php?funcao_js=parent.js_mostram91_depto1|m91_depto|descrdepto';
  var sUrl2 = 'func_db_almox.php?pesquisa_chave='+$F('m91_depto')+'&dpto=true&funcao_js=parent.js_mostram91_depto';
  if ( mostra == true ) {
    js_OpenJanelaIframe('','db_iframe_almox',sUrl1,'Pesquisa',true);
  } else {
  
    if ($F('m91_depto') != '') {
      js_OpenJanelaIframe('','db_iframe_almox',sUrl2,'Pesquisa',false);
    } else {
    
       $('m91_depto').value   = "";
       $('descrdepto').value  = "";
    }
  }
}

function js_mostram91_depto(chave,erro) {

 if (erro == true) {

   $('m91_depto').value   = "";
   $('descrdepto').value  = chave;
 } else {
   $('descrdepto').value  = chave;
 }
 
}

function js_mostram91_depto1(chave1,chave2) {

  $('m91_depto').value  = chave1;
  $('descrdepto').value = chave2;
  db_iframe_almox.hide();
}
</script>
</body>
</html>