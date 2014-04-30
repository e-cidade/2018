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

  //MODULO: Agua
  include("dbforms/db_classesgenericas.php");
  require ("libs/db_app.utils.php");

  $cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
  $claguarotarua->rotulo->label();
  $clrotulo = new rotulocampo;
  $clrotulo->label("x06_descr");
  $clrotulo->label("j14_nome");
  
  if (isset($db_opcaoal)) {

    $db_opcao = 33;
    $db_botao = false;

  } else if(isset($opcao) && $opcao == "alterar") {

    $db_botao = true;
    $db_opcao = 2;

  } else if(isset($opcao) && $opcao == "excluir") {

    $db_opcao = 3;
    $db_botao = true;

  } else {
    
    $db_opcao = 1;
    $db_botao=true;

    if (isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro == false )) {
      $x07_codrotarua = "";
      //$x07_codrota = "";
      $x07_codrua    = "";
      $j14_nome      = "";
      $x07_ordem     = "";
      $x07_nroini    = "";
      $x07_nrofim    = "";
    }
  }

  db_app::load('prototype.js, strings.js');

?>
<fieldset style="margin-top: 20px;">
  <legend><b>Cadastro de Rotas - Logradouros</b></legend>
<form name="form1" method="post" action="">
  <center>
    <table border="0">
      <tr>
        <td nowrap title="<?=@$Tx07_codrotarua?>"><?=@$Lx07_codrotarua?></td>
		    <td>
		      <?
		        db_input('x07_codrotarua', 10, $Ix07_codrotarua, true, 'text', 3, "")
		      ?>
		    </td>
      </tr>
	    <tr>
		    <td nowrap title="<?=@$Tx07_codrota?>"><?=@$Lx07_codrota?> 
		      <?
		        //db_ancora(@$Lx07_codrota,"js_pesquisax07_codrota(true);",$db_opcao);
		      ?>
		    </td>
		    <td>
		      <?
		        db_input('x07_codrota', 10, $Ix07_codrota, true, 'text', 3, " onchange='js_pesquisax07_codrota(false);'");
		        db_input('x06_descr', 40, $Ix06_descr, true, 'text', 3, '');
		      ?> 
		    </td>
      </tr>
      <tr>
		    <td nowrap title="<?=@$Tx07_codrua?>">
		      <?
		        db_ancora(@$Lx07_codrua, "js_pesquisax07_codrua(true);", $db_opcao);
		      ?>
		    </td>
		    <td>
		      <?
		        db_input('x07_codrua', 10, $Ix07_codrua, true,
		                 'text', $db_opcao, " onchange='js_pesquisax07_codrua(false); js_verifica_numeracao_rua(); js_retorna_orientacao_rua();'");
		        
		        db_input('j14_nome', 40, $Ij14_nome, true, 'text', 3, '');
		      ?> 
		    </td>
	    </tr>
	    <!-- 
	      <tr>
		      <td nowrap title="<?=@$Tx07_ordem?>"><?=@$Lx07_ordem?></td>
		      <td>
		        <?
		          db_input('x07_ordem', 3, $Ix07_ordem, true, 'text', $db_opcao, "")
		        ?>
		      </td>
	      </tr> 
	    -->
	    <tr>
		    <td nowrap title="<?=@$Tx07_nroini?>"><?=@$Lx07_nroini?></td>
		    <td>
		      <?
		        db_input('x07_nroini', 10, $Ix07_nroini, true, 'text', $db_opcao,"onchange = 'js_verifica_numeracao_rua()'");
		      ?>
		    </td>
	    </tr>
	    <tr>
		    <td nowrap title="<?=@$Tx07_nrofim?>"><?=@$Lx07_nrofim?></td>
		    <td>
		      <?
		        db_input('x07_nrofim', 10, $Ix07_nrofim, true, 'text', $db_opcao, "onchange = 'js_verifica_numeracao_rua()'");
		      ?>
		    </td>
	    </tr>
      <tr>
        <td nowrap title="<?=@$Tx07_orientacao?>"><?=@$Lx07_orientacao?></td>
        <td>
          <?
            $x = array("-"=>"---------",
                       "D"=>"DIREITA",
                       "E"=>"ESQUERDA",
                       "S"=>"SUL");
            
            db_select('x07_orientacao',$x,true,$db_opcao,  "onchange = 'js_verifica_numeracao_rua()'");
          ?>
        </td>
      </tr>
	    <tr>
  	    <td colspan="2" align="center">
          <input name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>"
                 type="submit" id="db_opcao"
                 value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"))?>"
                 <?=($db_botao == false ? "disabled" : "")?>> 
  	      
  	      <input name="novo" type="button" id="cancelar" value="Novo"
  	             onclick="js_cancelar();" <?=($db_opcao == 1 || isset($db_opcaoal) ? "style='visibility:hidden;'" : "")?>>
  	      
  	      <input name="verifica" id="verifica" value="Verifica numeração" type="button"
  	             onclick="js_verifica_numeracao_rua()" title="Verifica se numeração não pertence a nenhuma outra rota."/>
  	    </td>
	    </tr>
    </table>

    <table>
	    <tr>
		    <td valign="top" align="center">
		      <?
            $chavepri= array("x07_codrotarua"=>@$x07_codrotarua);
            $cliframe_alterar_excluir->chavepri      = $chavepri;
            $cliframe_alterar_excluir->sql           = $claguarotarua->sql_query(null, "*", null, "x07_codrota = $x07_codrota");
            //$cliframe_alterar_excluir->campos      = "x07_codrotarua, x07_codrota, x07_codrua, x07_ordem, x07_nroini, x07_nrofim";
            $cliframe_alterar_excluir->campos        = "x07_codrua, j14_nome, x07_nroini, x07_nrofim, x07_orientacao";
            $cliframe_alterar_excluir->legenda       = "LOGRADOUROS";
            $cliframe_alterar_excluir->iframe_height = "160";
            $cliframe_alterar_excluir->iframe_width  = "700";
            
            $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
		      ?>
		    </td>
      </tr>
    </table>
  </center>
</form>
</fieldset>

<script>

  js_pesquisax07_codrota(false);

  if (document.getElementById("db_opcao").value == 'Excluir') {
	  document.getElementById("db_opcao").disabled = false;
  } else {
	  document.getElementById("db_opcao").disabled = true;
  }


  function js_verifica_numeracao_rua() {
	
  	var oParam          = new Object();
  	oParam.exec         = 'getRotasConflito';
  	oParam.codRota      = $F('x07_codrota'); 
  	oParam.codRua       = $F('x07_codrua');
  	oParam.nroIni       = $F('x07_nroini');
  	oParam.nroFim       = $F('x07_nrofim');
  	oParam.cOrientacao  = $F('x07_orientacao');
    

		if (parseInt($F('x07_nroini')) > parseInt($F('x07_nrofim'))) {
			alert('ERRO: Número inicial maior que número final.');
			document.getElementById("db_opcao").disabled = true;
			return false;
		}

		if (parseInt($F('x07_nroini')) < 0 || parseInt($F('x07_nrofim') < 0)) {
			alert('ERRO: Numeração inválida.');
			document.getElementById("db_opcao").disabled = true;
			return false;
		}
		
		if ($F('x07_codrua') == '') {
			document.getElementById("db_opcao").disabled = true;
			return false;
		}
		
		if (($F('x07_nroini') == '') || ($F('x07_nrofim') == '')) {
			document.getElementById("db_opcao").disabled = true;
			return false;
		}
	  if (($F('x07_orientacao') == '') || ($F('x07_orientacao') == '')) {
	    document.getElementById("db_opcao").disabled = true;
	    return false;
	  }
		
  	js_divCarregando('Aguarde, verificando numeracao do logradouro.', 'msgbox');

  	var oAjax = new Ajax.Request(
  															 'agua_rota_rua.RPC.php', 
  															 {
  																method: 'POST',
  																parameters: 'json='+Object.toJSON(oParam),
  																onComplete: js_retorno_pesquisa_numeracao
  															 });

  }


  function js_retorno_pesquisa_numeracao(oAjax) {

	  js_removeObj('msgbox');

	  var oRetorno = eval("("+oAjax.responseText+")");
	  var msg      = "";
	  var virgula  = "";
	
	  if (oRetorno.status == 1) {
		  
		  if (oRetorno.aRotasConflito > 0) {
			  alert('Essa numeração já pertence a outra rota da mesma rua e orientação.');
			  document.getElementById("db_opcao").disabled = true;

	    }else {
			  document.getElementById("db_opcao").disabled = false;
		  }
	  }
  }

  
  function js_cancelar() {
	  var opcao = document.createElement("input");
	  opcao.setAttribute("type","hidden");
	  opcao.setAttribute("name","novo");
	  opcao.setAttribute("value","true");
	  document.form1.appendChild(opcao);
	  document.form1.submit();
	}


  function js_pesquisax07_codrota(mostra) {
	  if (mostra == true) {
		  js_OpenJanelaIframe('top.corpo.iframe_aguarotarua', 'db_iframe_aguarota', 
				'func_aguarota.php?funcao_js=parent.js_mostraaguarota1|x06_codrota|x06_descr',
				'Pesquisa', true, '0', '1', '775', '390');
		} else {

		  if (document.form1.x07_codrota.value != '') { 
        js_OpenJanelaIframe('top.corpo.iframe_aguarotarua', 'db_iframe_aguarota', 
          'func_aguarota.php?pesquisa_chave=' + document.form1.x07_codrota.value +
            '&funcao_js=parent.js_mostraaguarota', 'Pesquisa', false);
      } else {
        document.form1.x06_descr.value = ''; 
      }
    }

  }

  
  function js_mostraaguarota(chave, erro) {
    document.form1.x06_descr.value = chave; 

    if (erro == true) { 
      document.form1.x07_codrota.focus(); 
      document.form1.x07_codrota.value = ''; 
    }
  }

  
  function js_mostraaguarota1(chave1, chave2){
    document.form1.x07_codrota.value = chave1;
    document.form1.x06_descr.value = chave2;
    db_iframe_aguarota.hide();
  }


  function js_pesquisax07_codrua(mostra){

	  if (mostra == true) {
		  js_OpenJanelaIframe('top.corpo.iframe_aguarotarua', 'db_iframe_ruas', 
				'func_ruas.php?funcao_js=parent.js_mostraruas1|j14_codigo|j14_nome','Pesquisa',true,'0','1','775','390');
		}else{

		  if(document.form1.x07_codrua.value != ''){ 

		    js_OpenJanelaIframe('top.corpo.iframe_aguarotarua','db_iframe_ruas','func_ruas.php?pesquisa_chave='+document.form1.x07_codrua.value+'&funcao_js=parent.js_mostraruas','Pesquisa',false);

      }else{
        document.form1.j14_nome.value = ''; 
      }
		}
	}

  
  function js_mostraruas(chave, erro){
    document.form1.j14_nome.value = chave; 
    if (erro == true) { 
      document.form1.x07_codrua.focus(); 
      document.form1.x07_codrua.value = ''; 
    }
  }

  
  function js_mostraruas1(chave1, chave2){
    document.form1.x07_codrua.value = chave1;
    document.form1.j14_nome.value = chave2;
    db_iframe_ruas.hide();
  }
  
</script>