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

  //MODULO: agua
  $claguacorresp->rotulo->label();
  
  $clrotulo = new rotulocampo;
  $clrotulo->label("j13_descr");
  $clrotulo->label("j14_nome");
?>
<form name="form1" method="POST" onSubmit="return js_retorna_orientacao_rua();">
  <fieldset style="width: 700px; margin-top: 35px; margin-bottom: 10px;">
    <legend>
      <strong>Cadastro de endereço de entrega</strong>
    </legend>
    <table>
      <tr>
        <td nowrap title="<?php echo @$Tx02_codcorresp; ?>">
          <?php echo @$Lx02_codcorresp; ?>
        </td>
        <td>
          <?php
            db_input('x02_codcorresp', 10, $Ix02_codcorresp, true, 'text', $db_opcao, "");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo @$Tx02_codbairro; ?>">
          <?php
            db_ancora(@$Lx02_codbairro, "js_pesquisax02_codbairro(true);", $db_opcao);
          ?>
        </td>
        <td>
          <?php
            db_input('x02_codbairro', 10, $Ix02_codbairro, true, 'text', $db_opcao, " onchange='js_pesquisax02_codbairro(false);'");
            
            db_input('j13_descr', 40, $Ij13_descr, true, 'text', 3, '');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo @$Tx02_codrua; ?>">
          <?php
            db_ancora(@$Lx02_codrua, "js_pesquisax02_codrua(true);", $db_opcao);
          ?>
        </td>
        <td>
          <?php
            db_input('x02_codrua', 10, $Ix02_codrua, true,
              'text', $db_opcao, " onchange='js_pesquisax02_codrua(false);'");
            
            db_input('j14_nome', 40, $Ij14_nome, true, 'text', 3, '');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo @$Tx02_numero; ?>">
          <?php echo @$Lx02_numero;?>
        </td>
        <td>
          <?php
            db_input('x02_numero', 4, $Ix02_numero, true, 'text', $db_opcao, " onchange='js_pesquisa_rota();'");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo @$Tx02_complemento; ?>">
          <?php echo @$Lx02_complemento; ?>
        </td>
        <td>
          <?php
            db_input('x02_complemento', 54, $Ix02_complemento, true, 'text', $db_opcao, "");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo @$Tx02_rota?>">
          <?php echo @$Lx02_rota; ?>
        </td>
        <td>
          <?php
            db_input('x02_rota', 4, $Ix02_rota, true, 'text', '3', "");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo @$Tx02_orientacao; ?>">
          <?php echo @$Lx02_orientacao; ?>
        </td>
        <td> 
          <?
            $oRetornoPesquisa = array("-"=>"---------",
                                      "D"=>"DIREITA",
                                      "E"=>"ESQUERDA",
                                      "S"=>"SUL");
            
            db_select('x02_orientacao',$oRetornoPesquisa,true,$db_opcao, "onchange='js_retorna_orientacao_rua()'");
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input 
    name="<?php echo ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>"
    type="submit" id="db_opcao"
    value="<?php echo ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"))?>"
    <?php echo ($db_botao == false ? "disabled" : "")?> />
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
</form>

<script>
  js_pesquisa_rota();
  function js_pesquisax02_codbairro(mostra) {
    
	  if (mostra == true) {
    
	    js_OpenJanelaIframe('top.corpo', 'db_iframe_bairro',
	    	'func_bairro.php?funcao_js=parent.js_mostrabairro1|j13_codi|j13_descr', 'Pesquisa', true);
    } else {
    
      if (document.form1.x02_codbairro.value != '') {
   
        js_OpenJanelaIframe('top.corpo', 'db_iframe_bairro', 
          'func_bairro.php?pesquisa_chave=' + document.form1.x02_codbairro.value +
          '&funcao_js=parent.js_mostrabairro', 'Pesquisa', false);
      } else {
      
        document.form1.j13_descr.value = ''; 
      }
    }
  }

  function js_mostrabairro(chave, erro) {

	  document.form1.j13_descr.value = chave; 

	  if (erro == true) { 

	    document.form1.x02_codbairro.focus(); 
      document.form1.x02_codbairro.value = ''; 
    }
  }
  
  function js_mostrabairro1(chave1, chave2) {

	  document.form1.x02_codbairro.value = chave1;
    document.form1.j13_descr.value = chave2;
    db_iframe_bairro.hide();
  }
  
  function js_pesquisax02_codrua(mostra) {

	  if (mostra == true) {

	    js_OpenJanelaIframe('top.corpo', 'db_iframe_ruas',
	    	'func_ruas.php?funcao_js=parent.js_mostraruas1|j14_codigo|j14_nome', 'Pesquisa', true);
    } else {

      if (document.form1.x02_codrua.value != '') { 
        js_OpenJanelaIframe('top.corpo', 'db_iframe_ruas',
          'func_ruas.php?pesquisa_chave=' + document.form1.x02_codrua.value + '&funcao_js=parent.js_mostraruas',
          'Pesquisa', false);
      } else {

        document.form1.j14_nome.value = ''; 
      }
    }
  }
  
  function js_mostraruas(chave, erro) {
  
	  document.form1.j14_nome.value = chave; 
	  
    if (erro == true) { 
  
      document.form1.x02_codrua.focus(); 
      document.form1.x02_codrua.value = ''; 
    }
    js_pesquisa_rota();
  }
  
  function js_mostraruas1(chave1, chave2) {
	  document.form1.x02_codrua.value = chave1;
    document.form1.j14_nome.value   = chave2;
    js_pesquisa_rota();
	  db_iframe_ruas.hide();
  }
  
  function js_pesquisa() {
	  
    js_OpenJanelaIframe('top.corpo', 'db_iframe_aguacorresp', 
    	'func_aguacorresp.php?funcao_js=parent.js_preenchepesquisa|x02_codcorresp', 'Pesquisa', true);
  }
  
  function js_preenchepesquisa(chave) {
    
	  db_iframe_aguacorresp.hide();
    <?php
      if ($db_opcao != 1) {
        
      	echo " location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa='+chave";
      }
    ?>
  }

  function js_retorna_orientacao_rua() {
	  
	  var oParam          = new Object();
	  
	  oParam.exec         = 'getRotaOrientacao';
	  oParam.codRota      = $F('x02_rota');
	  oParam.codRua       = $F('x02_codrua');
	  oParam.nroLog       = $F('x02_numero');
	      
	  js_divCarregando('Aguarde, verificando orientação do logradouro.', 'msgbox');

	  var oAjax = new Ajax.Request(
	                               'agua_rota_rua.RPC.php', 
	                               {
	                                method: 'POST',
	                                asynchronous: false,
	                                parameters: 'json='+Object.toJSON(oParam),
	                                onComplete: js_retorno_pesquisa_orientacao
	                               });
      
     
	  if (sValida == false) {
      alert('Rota não possui a orientação selecionada do logradouro!');
      return false;
    }
	}  

  
	function js_retorno_pesquisa_orientacao(oAjax) {
    
	  js_removeObj('msgbox');
      
	  var oRetorno = eval("("+oAjax.responseText+")");

	  sValida = false;
	  
	  if (oRetorno.status == 1) { 
		  
		  for(i = 0; i < oRetorno.aRotaOrientacao.length; i++) {

			  if (oRetorno.aRotaOrientacao[i].value == document.form1.x02_orientacao.value) {
				  sValida = true;
    	  }
		  }
	  }
	}

	function js_pesquisa_rota() {
		  
	  var iRua   = $F('x02_codrua');
	  var iNro   = $F('x02_numero');
	  var sUrl   = 'agu4_pesquisarotarua.RPC.php';
	  var oParam = new Object();
	  var oAjax; 

	  if ((iRua == '') || (iNro == '')) 
		  return false;

	  oParam.exec = 'perquisarPorRota';
	  oParam.rua  = iRua;
	  oParam.nro  = iNro;
	 
	  oAjax = new Ajax.Request(sUrl, {
	  	                              method    : 'post',
	  	                              asynchronous: false,
	                                  parameters: 'json=' + Object.toJSON(oParam), 
	                                  onComplete: js_retorna_rota
	                                 }
	                          );	
	}


	function js_retorna_rota(oAjax) {
	
	 var oRetorno =  eval("(" + oAjax.responseText + ")");

	 if (oRetorno.status == 1) {

	   $('x02_rota').value  = oRetorno.iCodRota;
	   $('x06_descr').value = oRetorno.sDescricao;

	 } else {

	   $('x02_rota').value  = '';
	   $('x06_descr').value = 'Nenhuma rota definida para numeração dessa rua.';
	    
	 }
	}
  
</script>