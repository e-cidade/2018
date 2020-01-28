<?
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

//MODULO: Pessoal
$clrhresponsavel->rotulo->label();
$clcgm->rotulo->label();

if ($db_opcao == 1) {
  $db_action = "pes4_rhresponsavel004.php";
} else if ($db_opcao == 2 || $db_opcao == 22) {
 	$db_action = "pes4_rhresponsavel005.php";
} else if ($db_opcao == 3 || $db_opcao == 33) {
 	$db_action = "pes4_rhresponsavel006.php";
}  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<fieldset>
  <legend>
    <b>Cadastro de Responsável</b>
  </legend>
  <table border="0">
	  <tr>
	    <td title="<?=@$Trh107_sequencial?>">
	       <?=@$Lrh107_sequencial;?>
	    </td>
	    <td colspan="3"> 
	      <?
	        db_input('rh107_sequencial', 10, $Irh107_sequencial, true, 'text', 3);
	      ?>
	    </td>
	  </tr>
	  <tr>
	    <td title="<?=@$Trh107_nome?>">
	       <?=@$Lrh107_nome?>
	    </td>
	    <td colspan="3"> 
	      <?
	        db_input('rh107_nome', 40, $Irh107_nome, true, 'text', $db_opcao);
	      ?>
	    </td>
	  </tr>
	  <tr>
	    <td title="<?=@$Trh107_numcgm?>">
	       <?
	         db_ancora(@$Lrh107_numcgm, "js_pesquisarh107_numcgm(true);", $db_opcao);
	       ?>
	    </td>
	    <td> 
	      <?
	        db_input('rh107_numcgm', 10, $Irh107_numcgm, true, 'text', $db_opcao, "onchange='js_pesquisarh107_numcgm(false);'");
	      ?>
	    </td>
	    <td colspan="2">
	      <?
	        db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3);
	      ?>
	    </td>
	  </tr>
  </table>
</fieldset>
<table>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
             type="submit" id="db_opcao" 
             value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
             <?=($db_botao==false?"disabled":"")?> onclick="return js_validarCampos();" >   
    </td>
    <td>
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
    </td>
  </tr>
</table>
</form>
<script>
function js_validarCampos() {

  var sNomeResponsavel = $('rh107_nome').value;
  var iNumCgm          = $('rh107_numcgm').value;
  if (sNomeResponsavel == '') {
  
    alert('Informe o nome do responsável!');
    return false;
  }
  
  if (iNumCgm == '') {
  
    alert('Informe o número do cgm!');
    return false;
  }
}

function js_pesquisarh107_numcgm(mostra) {

  if (mostra == true) {
  
    var sUrl = 'func_nome.php?funcao_js=parent.js_mostrah107_numcgm1|z01_numcgm|z01_nome';
    js_OpenJanelaIframe('top.corpo.iframe_rhresponsavel', 'db_iframe_cgm', sUrl, 'Pesquisa', true, '0');
  } else {
  
    if ($('rh107_numcgm').value != '') {
     
      var sUrl = 'func_nome.php?pesquisa_chave='+$('rh107_numcgm').value
                                                +'&funcao_js=parent.js_mostrah107_numcgm';
      js_OpenJanelaIframe('top.corpo.iframe_rhresponsavel', 'db_iframe_cgm', sUrl, 'Pesquisa', false, '0');
    } else {
      $('z01_nome').value = ''; 
    }
  }
}

function js_mostrah107_numcgm(erro, chave1) {

  $('z01_nome').value = chave1; 
  if (erro == true) { 
  
    $('rh107_numcgm').focus(); 
    $('rh107_numcgm').value = '';
  }
}

function js_mostrah107_numcgm1(chave1, chave2) {

  $('rh107_numcgm').value = chave1;
  $('z01_nome').value     = chave2;
  db_iframe_cgm.hide();
}

function js_pesquisa() {

  var sUrlPesquisa = 'func_rhresponsavel.php?funcao_js=parent.js_preenchePesquisa|rh107_sequencial';
  js_OpenJanelaIframe('top.corpo.iframe_rhresponsavel', 'db_iframe_rhresponsavel', sUrlPesquisa, 
                      'Pesquisa', true,  '0');
}

function js_preenchePesquisa(chave) {

  db_iframe_rhresponsavel.hide();
  <?
    if ($db_opcao != 1) {
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
  ?>
}
</script>