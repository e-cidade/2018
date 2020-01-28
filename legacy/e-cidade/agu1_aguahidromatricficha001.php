<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
  require("libs/db_app.utils.php");

  $cRotulo = new rotulocampo();
  $cRotulo->label('x01_matric');
  $cRotulo->label('z01_nome');
  $cRotulo->label('j14_nome');
  $cRotulo->label('x01_numero');
  $cRotulo->label('x01_codrua');
  $cRotulo->label('x11_complemento');

?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
      db_app::load('scripts.js, estilos.css');
    ?>
  </head>
  <body bgcolor="#CCCCCC">
    <center>
	    <table width="790" border="0" cellpadding="0" cellspacing="0">
	      <tr> 
	        <td> 
				    <fieldset style="margin-top: 50px;">
				      <legend><b>Cadastro de Hidrometros - Emissão Ficha</b></legend>
					    <form name="form1" id="form1" method="post" action="">
					      <table style="margin: 25 auto;">
					        <tr>
					          <td title="<?=$Tx01_matric?>"><?=$Lx01_matric?></td>
					          <td title="<?=$Tx01_matric?>">
					            <?
					              db_input('x01_matric', 8, $Ix01_matric, true, 'text', 3);
					            ?>
					          </td>
					          <td>
					            <?
					              db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3);
					            ?>
					          </td>
					        </tr>
					        <tr>
					          <td title="<?=$Tj14_nome?>"><?=$Lj14_nome?></td>
					          <td>
					            <?
					              db_input('x01_codrua', 8, $Ix01_codrua, true, 'text', 3);
					            ?>
					          </td>
					          <td>
					            <?
					              db_input('j14_nome', 40, $Ij14_nome, true, 'text', 3);
					            ?>
					          </td>
					        </tr>
					        <tr>
					          <td title="<?=$Tx01_numero?>"><?=$Lx01_numero?></td>
					          <td colspan="2">
					            <?
					              db_input('x01_numero', 8, $Ix01_numero, true, 'text', 3);
					            ?>
					          </td>
					        </tr>
					        <tr>
					          <td title="<?=$Tx11_complemento?>"><?=$Lx11_complemento?></td>
					          <td colspan="2">
					            <?
					              db_input('x11_complemento', 30, $Ix11_complemento, true, 'text', 3);
					            ?>
					          </td>
					        </tr>
					        <tr>
					          <td colspan="3" align="center">
					            <input type="button" name="pesquisar" id="pesquisar" value="Pesquisar" onclick="js_pesquisa_aguabase(true)"/>
					            <input type="button" name="imprimir" id="imprimir" value="Imprimir" onclick="js_imprimir()"/>
					          </td>
					        </tr>
					      </table>
					    </form>
				    </fieldset>
	        </td>
	      </tr>
	    </table>
    </center>
    <? 
      db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
    ?>

		<script>
		
		  function js_pesquisa_aguabase(lMostra) {
			  var sQueryString = 'func_aguabase.php?';
		
			  if(lMostra) {
				  sQueryString += 'funcao_js=parent.js_mostra_matricula_iframe|x01_matric|z01_nome|x01_codrua|j14_nome|x01_numero|x11_complemento|x04_codhidrometro';
			  } 
		
				js_OpenJanelaIframe('top.corpo', 'db_iframe_aguabase', sQueryString, 'Pesquisa', lMostra, 20);
		  }
		
		  
		  function js_mostra_matricula_iframe(matricula, nome, codlogradouro, logradouro, numero, complemento, codhidrometro) {
        
			  if (codhidrometro) {
          
				  alert('A matrícula ' + matricula + ' já possui hidrômetro instalado.');
				  document.getElementById('imprimir').disabled = true;
			  } else {
          
				  document.getElementById('imprimir').disabled = false;
			  }
			  
			  document.form1.x01_matric.value      = matricula;
		 	  document.form1.z01_nome.value        = nome;
		 	  document.form1.x01_codrua.value      = codlogradouro;
			  document.form1.j14_nome.value        = logradouro;
			  document.form1.x01_numero.value      = numero;
			  document.form1.x11_complemento.value = complemento;
			  db_iframe_aguabase.hide();
		  }
      
      
		  function js_imprimir() {
		
			  var iMatricula   = document.form1.x01_matric.value;
		
			  var sQueryString = 'agu1_aguahidromatricficha002.php?matricula='+iMatricula;
		
			  if(iMatricula == '') {
				  alert('MatrÃ­cula nÃ£o informada.');
				  return false;
			  }
		
			  jan = window.open(sQueryString, '', 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
		    jan.moveTo(0,0);
			
		  }

		  document.form1.pesquisar.click();
		  
		</script>
  </body>
</html>