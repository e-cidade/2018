<?
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_conecta.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");

$clrotulo = new rotulocampo();
$clrotulo->label('q02_inscr');
$clrotulo->label('z01_nome');

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<? 
db_app::load('scripts.js, prototype.js, strings.js, datagrid.widget.js, windowAux.widget.js, DBViewImportacaoDiversos.classe.js, dbmessageBoard.widget.js');
db_app::load('estilos.css, grid.style.css');
?>
</head>
<body bgcolor=#CCCCCC>
  <form class="container" name="form1" id="form1">
    <fieldset>
      <legend>Importação de Débitos de Alvará para Diversos:</legend>
      <table class="form-container">
        <tr> 
			    <td title="<?=$Tq02_inscr?>"> 
			    <?php
			    	db_ancora($Lq02_inscr, 'js_pesquisaInscricao(true);', 4);
			    ?>
			    </td>
			    <td>
			    	<?php 
			    	  db_input('q02_inscr', 10, $Iq02_inscr, true, 'text', 1, "onchange='js_pesquisaInscricao(false)'");
			    		db_input("z01_nome"  , 40, $Iz01_nome  , true, 'text', 3);
			    	?>			    
			    </td>
			  </tr>
      </table>
    </fieldset>
    	<input type="button" name="pesquisar" id="pesquisar" value="Visualizar Débitos" onclick="js_pesquisaDebitos()" />
  </form>

  <?php
  	db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>

	<script type="text/javascript">
	
	function js_pesquisaInscricao(mostra) {
	  if (mostra==true) {
	    js_OpenJanelaIframe('top.corpo','db_iframe','func_issbase.php?funcao_js=parent.js_mostraInscricao|q02_inscr|z01_nome','Pesquisa',true);
	  }else{
	    js_OpenJanelaIframe('top.corpo','db_iframe','func_issbase.php?pesquisa_chave='+document.form1.q02_inscr.value+'&funcao_js=parent.js_mostraInscricaoHide','Pesquisa',false);
	  }
	}

	function js_mostraInscricao(iInscricao, sNome) {

		$('q02_inscr').value = iInscricao;
		$('z01_nome').value  = sNome;

		db_iframe.hide();
		
	}

	function js_mostraInscricaoHide(sNome, lErro) {

		$('z01_nome').value = sNome;
		
		if (lErro == true) {
			$('q02_inscr').value = '';
		}	
		
	}	

	function js_pesquisaDebitos() {
		    
		oImportacao = new DBViewImportacaoDiversos('oImportacao', 'importacao');
		
		oImportacao.setTipoPesquisa(5); //inscricao

    oImportacao.setCallBackFunction( function(){ window.location.reload(); } );        
    
    var aChavesPesquisa = new Array();
    
    aChavesPesquisa.push($F('q02_inscr'));
       
    oImportacao.setChavePesquisa(aChavesPesquisa);
       
    oImportacao.show();
    
  }

	</script>
</body>
</html>
<script>

$("q02_inscr").addClassName("field-size2");
$("z01_nome").addClassName("field-size7");

</script>