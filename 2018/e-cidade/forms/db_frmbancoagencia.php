<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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


 //MODULO: configuracoes
 
 $clbancoagencia->rotulo->label();
 $clrotulo = new rotulocampo;
 $clrotulo->label("db90_descr");
 
?>
<form name="form1" method="post" action="">
  <center>
    <fieldset>
      <legend align="center">
      	<b>Cadastro de Agencias</b>
      </legend>
    <table border="0">
	  <tr>
	    <td nowrap title="<?=@$Tdb89_sequencial?>">
	       <?=@$Ldb89_sequencial?>
	    </td>
	    <td> 
		  <?
		    db_input('db89_sequencial',10,$Idb89_sequencial,true,'text',3,"");
		  ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tdb89_db_bancos?>">
	      <?
	        db_ancora(@$Ldb89_db_bancos,"js_pesquisadb89_db_bancos(true);",$db_opcao);
	      ?>
	    </td>
	    <td> 
		  <?
		    db_input('db89_db_bancos',10,$Idb89_db_bancos,true,'text',$db_opcao," onchange='js_pesquisadb89_db_bancos(false);'");
			db_input('db90_descr',40,$Idb90_descr,true,'text',3,'');
	      ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tdb89_codagencia?>">
	      <?=@$Ldb89_codagencia?>
	    </td>
	    <td> 
		  <?
			db_input('db89_codagencia',5,$Idb89_codagencia,true,'text',$db_opcao,"");
			db_input('db89_digito',1,$Idb89_digito,true,'text',$db_opcao,"");
		  ?>
	    </td>
	  </tr>
    <tr>
      <td>
        <b>Endereço:</b>
      </td>
      <td colspan="3">
        <?
        db_input ('db92_endereco', 10, '', true, 'hidden', $db_opcao );
        db_input ('endereco_agencia', 54, '', true, 'text', 3 );
        ?>
        <input type="button" value="Lançar" id="btnLancarEndereco" onclick="js_lancaEndereco();"
          <?=$btnDisabled?> />
        <input type="button" value="Limpar Endereço" id="btnExcluirEndenreco" onclick="excluirEndereco();"
          <?=$btnDisabled?> />
      </td>
    </tr>
    </table>
    </fieldset>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisadb89_db_bancos(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_bancos','func_db_bancos.php?funcao_js=parent.js_mostradb_bancos1|db90_codban|db90_descr','Pesquisa',true);
  }else{
     if(document.form1.db89_db_bancos.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_bancos','func_db_bancos.php?pesquisa_chave='+document.form1.db89_db_bancos.value+'&funcao_js=parent.js_mostradb_bancos','Pesquisa',false);
     }else{
       document.form1.db90_descr.value = ''; 
     }
  }
}
function js_mostradb_bancos(chave,erro){
  document.form1.db90_descr.value = chave; 
  if(erro==true){ 
    document.form1.db89_db_bancos.focus(); 
    document.form1.db89_db_bancos.value = ''; 
  }
}
function js_mostradb_bancos1(chave1,chave2){
  document.form1.db89_db_bancos.value = chave1;
  document.form1.db90_descr.value = chave2;
  db_iframe_db_bancos.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_bancoagencia','func_bancoagencia.php?funcao_js=parent.js_preenchepesquisa|db89_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_bancoagencia.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function js_lancaEndereco() {

  var iCodigoEndereco = $F('db92_endereco');
  oEnderecoAgencia = new DBViewCadastroEndereco('pri', 'oEnderecoAgencia', iCodigoEndereco);
  oEnderecoAgencia.setObjetoRetorno($('db92_endereco'));
  oEnderecoAgencia.setTipoValidacao(2);
  oEnderecoAgencia.setEnderecoMunicipio(false);
  oEnderecoAgencia.setCallBackFunction(buscarEndereco);
  oEnderecoAgencia.show();
}

function buscarEndereco() {

  var iCodigoEndereco = $F('db92_endereco');
  if (iCodigoEndereco == '') {
    return;
  }
  $('endereco_agencia').value = '';
  var oEndereco               = new Object();
  oEndereco.exec              = 'findEnderecoByCodigo';
  oEndereco.iCodigoEndereco   = iCodigoEndereco;
  js_divCarregando('Aguarde, buscando endereço da agência', 'msgBox');
  new Ajax.Request ('prot1_cadgeralmunic.RPC.php',
     {method:'post',
      parameters:'json='+Object.toJSON(oEndereco),
      onComplete:function(oResponse) {

         js_removeObj('msgBox');
         var oRetorno = eval('('+oResponse.responseText+')');
         if (oRetorno.endereco) {

           var sEndereco = oRetorno.endereco[0].srua.urlDecode();
           sEndereco += ",  nº " +oRetorno.endereco[0].snumero.urlDecode();
           sEndereco += " "      +oRetorno.endereco[0].scomplemento.urlDecode();
           sEndereco += " - "    +oRetorno.endereco[0].sbairro.urlDecode();
           sEndereco += " - "    +oRetorno.endereco[0].smunicipio.urlDecode();
           sEndereco += " - "    +oRetorno.endereco[0].ssigla.urlDecode();
           $('endereco_agencia').value = sEndereco;
          }
        }
      }
    );
 }
function excluirEndereco() {

  if ($F('db92_endereco') != '') {

    var sMensagemLimpar = 'usuário:\n\n Deseja excluir o endereço ?\n\n';
    if ($('db_opcao').value == 'Alterar') {
      sMensagemLimpar +='Para confirma a remoção do endereço, após o endereço ser limpo clique em '+$('db_opcao').value;
    }
    if (confirm(sMensagemLimpar)) {

      $('db92_endereco').value    = '';
      $('endereco_agencia').value = '';
    }
  }
}

buscarEndereco();
</script>