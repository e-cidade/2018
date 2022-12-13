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

//MODULO: juridico
$cliptutabelas->rotulo->label();
$cliptutabelasconfig->rotulo->label();
$cldbsysarquivo->rotulo->label('nomearq');
?>
<form name="form1" method="post" action="">
  <fieldset>
    <legend><b>Configuração de Tabelas</b></legend>
    <table border="0" align="center" width="400px">
      <tr>
        <td nowrap title="<?=@$Tj122_sequencial?>">
          <?=@$Lj122_sequencial?>
        </td>
        <td colspan="2"> 
          <?
            db_input('j122_sequencial', 10, $Ij122_sequencial, true, 'text', 3, "");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tj121_sequencial?>">
          <?
            db_ancora("<b>Tabela Cálculo:</b>", "js_pesquisaj121_sequencial(true);", $db_opcao);
          ?>
        </td>
        <td> 
          <?
            if (isset($j121_sequencial)) {
            	$tabelaanterior = $j121_sequencial;
            }
            
            db_input('tabelaanterior', 10, $Ij121_sequencial, true, 'hidden', 3, "");
            db_input('j121_sequencial', 10, $Ij121_sequencial, true, 
                     'text', $db_opcao, " onchange='js_pesquisaj121_sequencial(false);'");
          ?>
        </td>
        <td> 
          <?
            if (isset($nomearq)) {
            	$nometabelaanterior = $nomearq;
            }
          
            db_input('j121_codarq', 10, $Ij121_codarq, true, 'hidden', 3, "");
            db_input('nometabelaanterior', 30, $Inomearq, true, 'hidden', 3, "");
            db_input('nomearq', 30, $Inomearq, true, 'text', 3, "");
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <table align="center">
    <tr>
      <td>&nbsp;</td>
    </tr>
	  <tr>			
			<td>	
			  <?
          if (!$lDisabled) {
			  ?>
				<input name="<?=($db_opcao==1?"incluir":"excluir")?>" 
				       type="submit" id="db_opcao" onclick="return js_validar();"
				       value="<?=($db_opcao==1?"Incluir":"Excluir")?>" 
				       <?=($db_botao==false?"disabled":"")?> >
				<?
          }
          
          if ($db_opcao != 1) {
        ?>
            <input name="novo" type="button" id="novo" value="Novo" onclick="js_novo();" >
        <?
          }
				?>
				<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
			</td>	
    </tr>
	</table>
</form>
<script>
function js_novo() {

	parent.document.formaba.dadostabela.disabled    = true;
	parent.document.formaba.camposchave.disabled    = true;
	parent.document.formaba.camposcorrecao.disabled = true;
  parent.iframe_dadostabela.location.href         = 'cad4_iptutabelasconfig004.php';
}

function js_validar() {

  var j121_sequencial = $('j121_sequencial').value;
  if (j121_sequencial == '') {
    alert('Informe o código da tabela!');
    return false;
  }
  
  var sOpcao = $('db_opcao').value;  
  if (sOpcao == 'Excluir') {
    
    if (!confirm("Serão excluidos todos os campos vinculados a tabela de configuração, deseja continuar?")) {
      return false;    
    }
  }  
}

function js_pesquisaj121_sequencial(mostra) {

  if (mostra == true) {
  
    var sUrl = 'func_iptutabelas.php?funcao_js=parent.js_mostraiptutabelas1|j121_sequencial|j121_codarq|nomearq';
    js_OpenJanelaIframe('', 'db_iframe_iptutabelas', sUrl, 'Pesquisa', true, 0);
  } else {
  
    if ($('j121_sequencial').value != '') {
    
      var sUrl = 'func_iptutabelas.php?pesquisa_chave='+$('j121_sequencial').value
                +'&funcao_js=parent.js_mostraiptutabelas'; 
      js_OpenJanelaIframe('', 'db_iframe_iptutabelas', sUrl, 'Pesquisa', false, 0);
    } else {
    
      $('j121_sequencial').value = ''; 
      $('j121_codarq').value     = '';
      $('nomearq').value         = '';
    }
  }
}

function js_mostraiptutabelas(chave1,chave2,chave3,erro) {

  $('j121_sequencial').value = chave1;
  $('j121_codarq').value     = chave2;
  $('nomearq').value         = chave3; 
  if (erro == true) {
   
    $('j121_sequencial').value = '';
    $('j121_codarq').value     = ''; 
    $('nomearq').value         = chave1; 
    $('j121_sequencial').focus(); 
  }
}

function js_mostraiptutabelas1(chave1,chave2,chave3) {

  $('j121_sequencial').value = chave1;
  $('j121_codarq').value     = chave2;
  $('nomearq').value         = chave3;
  db_iframe_iptutabelas.hide();
}

function js_pesquisa() {

  var sUrl = 'func_iptutabelasconfig.php?funcao_js=parent.js_preenchepesquisa|j122_sequencial';
  js_OpenJanelaIframe('', 'db_iframe_iptutabelas', sUrl, 'Pesquisa', true, 0);
}

function js_preenchepesquisa(chave) {

  db_iframe_iptutabelas.hide();
  <?
	  if ($db_opcao != 1) {
	    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
	  }
  ?>
}
</script>