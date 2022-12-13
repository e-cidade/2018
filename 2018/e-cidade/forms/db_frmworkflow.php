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

//MODULO: Habitacao
$clworkflow->rotulo->label();
$cltipoproc->rotulo->label();

if ($db_opcao == 1) {
 	$db_action="hab1_workflow004.php";
} else if ($db_opcao == 2 || $db_opcao == 22) {
 	$db_action="hab1_workflow005.php";
} else if ($db_opcao == 3 || $db_opcao == 33) {
  $db_action="hab1_workflow006.php";
}  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<fieldset>
<legend><b>Cadastro Workflow</b></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb112_sequencial?>">
      <?=@$Ldb112_sequencial?>
    </td>
    <td> 
			<?
			  db_input('db112_sequencial', 10, $Idb112_sequencial, true, 'text', 3, "");
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb112_descricao?>">
      <?=@$Ldb112_descricao?>
    </td>
    <td> 
      <?
        db_input('db112_descricao', 80, $Idb112_descricao, true, 'text', $db_opcao, "onchange='js_valordescricao();'");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp51_descr?>">
      <b>Descrição do Processo de Protocolo:</b>
    </td>
    <td> 
      <?
        db_input('p51_descr', 80, $Ip51_descr, true, 'text', $db_opcao, "onchange='js_valordescricao();'");
      ?>
    </td>
  </tr>
</table>
</fieldset>
<table align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
             type="submit" id="db_opcao" onclick="return js_validarcampos();"
             value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
             <?=($db_botao==false?"disabled":"")?> >
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    </td>
  </tr>
</table>
</form>
<script>
function js_valordescricao() {

  var sDescricao         = $('db112_descricao').value;
  var sDescricaoTipoProc = $('p51_descr').value;
  
  if (sDescricaoTipoProc == '') {
    $('p51_descr').value = $('db112_descricao').value;
  }
}

function js_validarcampos() {

  var sDescricao         = $('db112_descricao').value;
  var sDescricaoTipoProc = $('p51_descr').value;
  
  if (sDescricao == '') {
  
    alert('Campo descrição não informado!');
    return false;
  }
  
  if (sDescricaoTipoProc == '') {
  
    alert('Campo descrição do processo de protocolo não informado!');
    return false;
  }
}

function js_pesquisa() {

  var sUrl = 'func_workflow.php?funcao_js=parent.js_preenchepesquisa|db112_sequencial';
  js_OpenJanelaIframe('top.corpo.iframe_workflow', 'db_iframe_workflow', sUrl, 'Pesquisa', true, '0');
}

function js_preenchepesquisa(chave) {

  db_iframe_workflow.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>