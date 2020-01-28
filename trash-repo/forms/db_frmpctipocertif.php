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

//MODULO: Compras
$clpctipocertif->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("db08_codigo");
$clrotulo->label("db08_descr");

if ($db_opcao==1) {
 	$db_action="com1_pctipocertif004.php";
} else if ($db_opcao==2||$db_opcao==22) {
  $db_action="com1_pctipocertif005.php";
} else if($db_opcao==3||$db_opcao==33) {
  $db_action="com1_pctipocertif006.php";
}  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table align=center style="margin-top:15px;">
  <tr>
    <td> 
			<fieldset>
			<legend><b>Tipo de Certificado</b></legend>
				<table border="0">
				  <tr>
				    <td nowrap title="<?=@$Tpc70_codigo?>">
				      <?=@$Lpc70_codigo?>
				    </td>
				    <td> 
							<?
							  db_input('pc70_codigo', 10, $Ipc70_codigo, true, 'text', 3, "");
							?>
				    </td>
				  </tr>
				  <tr>
				    <td nowrap title="<?=@$Tdb08_codigo?>">
				       <?
				         db_ancora(@$Ldb08_codigo, "js_pesquisadb08_codigo(true);", $db_opcao);
				       ?>
				    </td>
				    <td> 
							<?
							  db_input('db08_codigo', 10, $Idb08_codigo, true, 'text', $db_opcao, " onchange='js_pesquisadb08_codigo(false);'");
				        db_input('db08_descr', 40, $Idb08_descr, true, 'text', 3, '');
				      ?>
				    </td>
				  </tr>
				  <tr>
				    <td nowrap title="<?=@$Tpc70_descr?>">
				      <?=@$Lpc70_descr?>
				    </td>
				    <td> 
							<?
							  db_input('pc70_descr', 40, $Ipc70_descr, true, 'text', $db_opcao, "");
							?>
				    </td>
				  </tr>
				  <tr>
				    <td nowrap title="<?=@$Tpc70_subgrupo?>">
				      <?=@$Lpc70_subgrupo?>
				    </td>
				    <td> 
							<?
								$x = array('f'=>'Não','t'=>'Sim');
								db_select('pc70_subgrupo', $x, true, $db_opcao, "");
							?>
				    </td>
				  </tr>
				  </table>
				  <br>
				  <fieldset>
				  <legend><?=@$Lpc70_obs?></legend> 
						<?
						  db_textarea('pc70_obs', 5, 53, $Ipc70_obs, true, 'text', $db_opcao, "");
						?>
				  </fieldset>
				  <br>
				  <fieldset>
				  <legend><?=@$Lpc70_parag2?></legend>
						<?
						  db_textarea('pc70_parag2', 5, 53, $Ipc70_parag2, true, 'text', $db_opcao, "");
						?>
			  </fieldset>
		  </fieldset>
    </td>
  </tr>
</table>  
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type="submit" id="db_opcao" 
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
       <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisadb08_codigo(mostra) {

  if (mostra == true) {
  
    var sUrl = 'func_db_tipodoc.php?funcao_js=parent.js_mostradb_tipodoc1|db08_codigo|db08_descr';
    js_OpenJanelaIframe('top.corpo.iframe_pctipocertif', 'db_iframe_db_tipodoc', sUrl, 'Pesquisa', true, '0', '1');
  } else {
  
    if ($('db08_codigo').value != '') { 
    
      var sUrl = 'func_db_tipodoc.php?pesquisa_chave='+$('db08_codigo').value+'&funcao_js=parent.js_mostradb_tipodoc';
      js_OpenJanelaIframe('top.corpo.iframe_pctipocertif', 'db_iframe_db_tipodoc', sUrl, 'Pesquisa', false);
    } else {
      $('db08_descr').value = ''; 
    }
  }
}

function js_mostradb_tipodoc(chave,erro) {

  $('db08_descr').value = chave; 
  if (erro == true) { 
  
    $('db08_codigo').focus(); 
    $('db08_codigo').value = ''; 
  }
}

function js_mostradb_tipodoc1(chave1,chave2) {

  $('db08_codigo').value = chave1;
  $('db08_descr').value  = chave2;
  db_iframe_db_tipodoc.hide();
}

function js_pesquisa() {

  var sUrl = 'func_pctipocertif.php?funcao_js=parent.js_preenchepesquisa|pc70_codigo&viewAll=1';
  js_OpenJanelaIframe('top.corpo.iframe_pctipocertif', 'db_iframe_pctipocertif', sUrl, 'Pesquisa', true, '0', '1');
}

function js_preenchepesquisa(chave) {

  db_iframe_pctipocertif.hide();
  <?
	  if ($db_opcao != 1) {
	    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
	  }
  ?>
}
</script>