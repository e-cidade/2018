<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");

require_once("dbforms/db_funcoes.php");

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

$rhcadregimefaltasperiodoaquisitivo = new cl_rhcadregimefaltasperiodoaquisitivo();
$rhcadregimefaltasperiodoaquisitivo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh52_regime");
$db_opcao = 1;
?>
<html>
  <head>
    <title>dbseller inform&aacute;tica ltda</title>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="expires" content="0">
    <?php
      db_app::load("estilos.css");
      db_app::load("scripts.js");
      db_app::load("strings.js");
      db_app::load("prototype.js");
    ?>
  </head>
  
   <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
    <div class='container' style='width:700px !important;'>
		 <form name="form1" method="post" action="">      
        <fieldset>
          <legend><strong>Seleção de Regime</strong></legend>

				      <table class='form-container'>
							  <tr>
							    <td nowrap title="<?=@$Trh125_rhcadregime?>">
							       <?
							       db_ancora(@$Lrh125_rhcadregime,"js_pesquisarh125_rhcadregime(true);",$db_opcao);
							       ?>
							    </td>
							    <td>
										 <?
										 db_input('rh125_rhcadregime',10,$Irh125_rhcadregime,true,'text',$db_opcao," onchange='js_pesquisarh125_rhcadregime(false);'",'','',"class='field-size2'");
										 ?>
							       <?
							       db_input('rh52_regime',1,$Irh52_regime,true,'text',3, "class='field-size9'");
							       ?>
							    </td>
							  </tr>
			        </table>
			        
			 </fieldset>
			 
			 <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_enviar();" >
			 
			</form>
    </div>
    <?php
    db_menu(db_getsession("DB_id_usuario"),
            db_getsession("DB_modulo"),
            db_getsession("DB_anousu"),
            db_getsession("DB_instit")
           );
    ?>
  </body>
  
  <script>
      function js_enviar(){
    	  	if ( document.form1.rh125_rhcadregime.value == '' ) {
    		    return false;
    		  }

    		  if ( document.form1.rh52_regime.value == '' ) {
    			    return false;
    			}

    		  var oRegex  = /^[0-9]+$/;
    	    if ( !oRegex.test(document.form1.rh125_rhcadregime.value) ) {
    	        return false;  
    	    }
    		      		  
    		  window.location.href = 'pes1_faixafaltasperiodoaquisitivo002.php?rh52_regime=' + document.form1.rh125_rhcadregime.value + 
    		                                                                 '&rh52_descr='  + document.form1.rh52_regime.value;
      }
      
			function js_pesquisarh125_rhcadregime(mostra){
			  if(mostra==true){
			    js_OpenJanelaIframe('top.corpo','db_iframe_rhcadregime','func_rhcadregime.php?funcao_js=parent.js_mostrarhcadregime1|rh52_regime|rh52_descr','Pesquisa',true);
			  }else{
			     if(document.form1.rh125_rhcadregime.value != ''){ 
			        js_OpenJanelaIframe('top.corpo','db_iframe_rhcadregime','func_rhcadregime.php?pesquisa_chave='+document.form1.rh125_rhcadregime.value+'&funcao_js=parent.js_mostrarhcadregime','Pesquisa',false);
			     }else{
			       document.form1.rh52_regime.value = ''; 
			     }
			  }
			}
			
			function js_mostrarhcadregime(chave,erro){
			  document.form1.rh52_regime.value = chave; 
			  if(erro==true){ 
			    document.form1.rh125_rhcadregime.focus(); 
			    document.form1.rh125_rhcadregime.value = ''; 
			  }
			}
			
			function js_mostrarhcadregime1(chave1,chave2){
			  document.form1.rh125_rhcadregime.value = chave1;
			  document.form1.rh52_regime.value 			 = chave2;
			  db_iframe_rhcadregime.hide();
			}
			
			function js_pesquisa(){
			  js_OpenJanelaIframe('top.corpo','db_iframe_rhcadregimefaltasperiodoaquisitivo','func_rhcadregimefaltasperiodoaquisitivo.php?funcao_js=parent.js_preenchepesquisa|rh125_sequencial','Pesquisa',true);
			}
			
			function js_preenchepesquisa(chave){
			  db_iframe_rhcadregimefaltasperiodoaquisitivo.hide();
			  <?
			  if($db_opcao!=1){
			    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
			  }
			  ?>
			}
			</script>
</html>