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

//MODULO: compras
$clpctipocompra->rotulo->label();
$clpctipocompratribunal->rotulo->label();
?>
<style>
td {
  white-space: nowrap
}

fieldset table td:first-child {
              width: 180px;
              white-space: nowrap
}
</style>
<form name="form1" method="post" action="">
<fieldset>
<legend><b>Tipo de Compras</b></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tpc50_codcom?>">
       <?=@$Lpc50_codcom?>
    </td>
    <td> 
			<?
			  db_input('pc50_codcom',10,$Ipc50_codcom,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc50_descr?>">
       <?=@$Lpc50_descr?>
    </td>
    <td> 
			<?
			  db_input('pc50_descr',50,$Ipc50_descr,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tl44_descricao?>">
       <b>Descrição do Tribunal:</b>
    </td>
    <td> 
      <?
        $oDaoDbConfig              = db_utils::getDao("db_config");
        $sWhere                    = "codigo = {$iInstit}";
        $sSqlDbConfig              = $oDaoDbConfig->sql_query_file(null, "uf", null, $sWhere);
        $rsSqlDbConfig             = $oDaoDbConfig->sql_record($sSqlDbConfig);
        
	      $aPcTipoCompraTribunal[0]  = "Selecione";
        if ($oDaoDbConfig->numrows > 0) {
        	
        	$oDbConfig                 = db_utils::fieldsMemory($rsSqlDbConfig, 0);
        	
	        $oDaoPcTipoCompraTribunal  = db_utils::getDao("pctipocompratribunal");
	        $sWhere                    = "l44_uf = '{$oDbConfig->uf}'";
	        $sSqlPcTipoCompraTribunal  = $oDaoPcTipoCompraTribunal->sql_query_file(null, "*", "l44_sequencial", $sWhere);
	        $rsSqlPcTipoCompraTribunal = $oDaoPcTipoCompraTribunal->sql_record($sSqlPcTipoCompraTribunal);
	        $aPcTipoCompraTribunal[0]  = "Selecione";
	        for($i = 0; $i < $oDaoPcTipoCompraTribunal->numrows; $i ++) {
	            
	          $oPcTipoCompraTribunal = db_utils::fieldsMemory($rsSqlPcTipoCompraTribunal, $i);
	          $aPcTipoCompraTribunal[$oPcTipoCompraTribunal->l44_sequencial] = $oPcTipoCompraTribunal->l44_descricao;
	        
	        }
        }
        
	      db_select('pc50_pctipocompratribunal', $aPcTipoCompraTribunal, true, $db_opcao, "onchange='js_desabilitaSelecionar();'");
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
      <input name="db_opcao" type="submit" id="db_opcao" 
             value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
             <?=($db_botao==false?"disabled":"")?> >
    <td>
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    </td>
  </tr>
</table>
</form>
<script>
function js_pesquisa() {

  var sUrl = 'func_pctipocompra.php?funcao_js=parent.js_preenchepesquisa|pc50_codcom';
  js_OpenJanelaIframe('top.corpo.iframe_tipocompras','db_iframe_pctipocompra',sUrl,'Pesquisa',true,'0');
}

function js_preenchepesquisa(chave) {
  db_iframe_pctipocompra.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function js_desabilitaSelecionar() {

  var iCodigoTipoCompraTribunal = $('pc50_pctipocompratribunal').value;
  if (iCodigoTipoCompraTribunal != 0) {
    $('pc50_pctipocompratribunal').options[0].disabled = true; 
  }
}
</script>