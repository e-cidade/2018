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

  //MODULO: agua
  $claguabase->rotulo->label();
  $clrotulo = new rotulocampo;
  $clrotulo->label("j14_nome");
  $clrotulo->label("j13_descr");
  $clrotulo->label("z01_nome");
  $clrotulo->label("z01_nomepromit");
  $clrotulo->label("j50_descr");
  $clrotulo->label("j85_ender");

  if ($db_opcao == 1) {
 	  $db_action="agu1_aguabase004.php";
  } else if($db_opcao == 2 || $db_opcao == 22) {
 	  $db_action="agu1_aguabase005.php";
  } else if($db_opcao == 3 || $db_opcao == 33) {
 	  $db_action="agu1_aguabase006.php";
  }
  
?>
<fieldset style="margin-top: 20px;">
<legend><b>Cadastro de Imóveis/Terrenos - Cadastro</b></legend>
<form name="form1" method="post" action="<?=$db_action?>" onSubmit="return js_retorna_orientacao_rua();">
  <center>
    <table>
      <tr>
        <td nowrap title="<?=@$Tx01_matric?>">
          <?=@$Lx01_matric?>
        </td>
        <td> 
          <?
            db_input('x01_matric', 10, $Ix01_matric, true, 'text',
                     ($db_opcao == 2 || $db_opcao == 22) ? 3 : $db_opcao, "");
          ?>
        </td>
        </tr>
      <tr>
        <td nowrap title="<?=@$Tx01_numcgm?>">
          <?
            db_ancora(@$Lx01_numcgm, "js_pesquisax01_numcgm(true);", $db_opcao);
          ?>
        </td>
        <td> 
          <?
            db_input('x01_numcgm', 10, $Ix01_numcgm, true, 'text',
                     $db_opcao, " onchange='js_pesquisax01_numcgm(false);'");
                     
            db_input('z01_nome', 50, $Iz01_nome, true, 'text', 3, '');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tx01_promit?>">
          <?
            db_ancora(@$Lx01_promit, "js_pesquisax01_promit(true);", $db_opcao);
          ?>
        </td>
        <td> 
          <?
            db_input('x01_promit', 10, $Ix01_promit, true, 'text',
                     $db_opcao, " onchange='js_pesquisax01_promit(false);'");
                     
            db_input('z01_nomepromit', 50, $Iz01_nomepromit, true, 'text', 3, '');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tx01_codrua?>">
          <?
            db_ancora(@$Lx01_codrua, "js_pesquisax01_codrua(true);", $db_opcao);
          ?>
        </td>
        <td>
          <?
            db_input('x01_codrua', 10, $Ix01_codrua, true, 'text',
                     $db_opcao, " onchange='js_pesquisax01_codrua(false);js_pesquisa_rota();'  ");
                     
            db_input('j14_nome', 50, $Ij14_nome, true, 'text', 3, "onchange='js_pesquisa_rota();'");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tx01_codbairro?>" >
          <?
            db_ancora(@$Lx01_codbairro, "js_pesquisax01_codbairro(true);", $db_opcao);
          ?>
        </td>
        <td> 
		      <?
			      db_input('x01_codbairro', 10, $Ix01_codbairro, true, 'text',
			               $db_opcao, " onchange='js_pesquisax01_codbairro(false);'");
			               
			      db_input('j13_descr', 50, $Ij13_descr, true, 'text', 3, '');
		      ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tx01_numero?>">
          <?=@$Lx01_numero?>
        </td>
        <td> 
          <?
            db_input('x01_numero', 10, $Ix01_numero, true, 'text', $db_opcao, "onchange='js_pesquisa_rota()'");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tx01_rota?>">
          <?=@$Lx01_rota ?>
        </td>
        <td> 
          <?
            db_input('x01_rota', 10, $Ix01_rota, true, 'text', 3, '');
            db_input('x06_descr', 50, '', true, 'text', 3, '');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tx01_letra?>">
          <?=@$Lx01_letra?>
        </td>
        <td> 
          <?
            db_input('x01_letra', 10, $Ix01_letra, true, 'text', $db_opcao, "");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tx01_quadra?>">
          <?=@$Lx01_quadra?>
        </td>
        <td> 
          <?
            db_input('x01_quadra', 10, $Ix01_quadra, true, 'text', $db_opcao, "");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tx01_distrito?>">
          <?=@$Lx01_distrito?>
        </td>
        <td> 
          <?
            db_input('x01_distrito', 10, $Ix01_distrito, true, 'text', $db_opcao, "");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tx01_zona?>">
          <?
            db_ancora(@$Lx01_zona, "js_pesquisax01_zona(true);", $db_opcao);
          ?>
        </td>
        <td> 
          <?
            db_input('x01_zona', 10, $Ix01_zona, true, 'text', $db_opcao, " onchange='js_pesquisax01_zona(false);'");
            db_input('j50_descr', 50, $Ij50_descr, true, 'text', 3, '');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tx01_entrega?>">
          <?
            db_ancora(@$Lx01_entrega, "js_pesquisax01_entrega(true);", $db_opcao);
          ?>
        </td>
        <td> 
	        <?
	          db_input('x01_entrega', 10, $Ix01_entrega, true, 'text',
	                   $db_opcao, " onchange='js_pesquisax01_entrega(false);'");
	                   
	          db_input('j85_descr', 50, $Ij85_ender, true, 'text', 3, '');
	        ?>
        </td>
      </tr>
      <tr>
        <td>
        </td>
        <td> 
          <?
            db_input('j85_ender', 50, $Ij85_ender, true, 'text', 3, '');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tx01_orientacao?>">
          <?=@$Lx01_orientacao?>
        </td>
        <td> 
          <?
            $oRetornoPesquisa = array("-"=>"---------",
                                      "D"=>"DIREITA",
                                      "E"=>"ESQUERDA",
                                      "S"=>"SUL");
            
            db_select('x01_orientacao',$oRetornoPesquisa,true,$db_opcao, "onchange='js_retorna_orientacao_rua()'");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tx01_qtdeconomia?>">
          <?=@$Lx01_qtdeconomia?>
        </td>
        <td> 
	        <?
	          db_input('x01_qtdeconomia', 10, $Ix01_qtdeconomia, true, 'text', $db_opcao, "");
	        ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tx01_multiplicador?>">
          <?=@$Lx01_multiplicador?>
        </td>
        <td>
          <?
            $x = array("f"=>"NAO","t"=>"SIM");
            db_select('x01_multiplicador', $x, true, $db_opcao, "");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tx01_dtcadastro?>">
          <?=@$Lx01_dtcadastro?>
        </td>
        <td> 
          <?
            db_inputdata('x01_dtcadastro', @$x01_dtcadastro_dia, @$x01_dtcadastro_mes,
                         @$x01_dtcadastro_ano, true, 'text', 3, "");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tx01_qtdponto?>">
          <?=@$Lx01_qtdponto?>
        </td>
        <td> 
          <?
            db_input('x01_qtdponto', 10, $Ix01_qtdponto, true, 'text', $db_opcao, "");
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <b>
            <?
              db_ancora("Caracteristicas", "js_mostracaracteristica();", 1);
            ?>
          </b>
        </td>
        <td>
          <?
            db_input('caracteristica', 15, 1, true, 'hidden', 1, "");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tx01_obs?>">
          <?=@$Lx01_obs?>
        </td>
        <td> 
          <?
            if (db_getsession('DB_administrador') == 1) {
              $db_opcao_obs = $db_opcao;
            } else {
              $db_opcao_obs = 3;
            }

            db_textarea('x01_obs', 4, 60, $Ix01_obs, true, 'text', $db_opcao_obs, "");
          ?>
        </td>
      </tr>
    </table>
  </center>
  
  <input name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>"
         type="submit" id="db_opcao"
         value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"))?>"
            <?=($db_botao == false ? "disabled" : "")?>
  />
            
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="return js_pesquisa();" >

</form>

</fieldset>

<script>
  //ajax
  js_pesquisa_rota();
  
  function js_pesquisa_rota() {
	  
	  var iRua   = $F('x01_codrua');
	  var iNro   = $F('x01_numero');
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

      $('x01_rota').value  = oRetorno.iCodRota;
      $('x06_descr').value = oRetorno.sDescricao;

	  } else {

	    $('x01_rota').value  = '';
      $('x06_descr').value = 'Nenhuma rota definida para numeração dessa rua.';
      
	  }
  }


  function js_retorna_orientacao_rua() {
	  
	  var oParam          = new Object();
	  
	  oParam.exec         = 'getRotaOrientacao';
	  oParam.codRota      = $F('x01_rota');
	    
	  oParam.codRua       = $F('x01_codrua');
	  oParam.nroLog       = $F('x01_numero');
	      
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
			  if (oRetorno.aRotaOrientacao[i].value == document.form1.x01_orientacao.value) {
				  sValida = true;
    	  }
		  }
	  }
	}

    
  function js_pesquisax01_codrua(mostra) {
	
    if (mostra == true) {

      js_OpenJanelaIframe('top.corpo.iframe_aguabase', 'db_iframe_ruas',
    	                    'func_ruas.php?funcao_js=parent.js_mostraruas1|j14_codigo|j14_nome', 'Pesquisa', true);

    } else {

      if (document.form1.x01_codrua.value != '') {
        js_OpenJanelaIframe('top.corpo.iframe_aguabase', 'db_iframe_ruas', 
                            'func_ruas.php?pesquisa_chave=' + document.form1.x01_codrua.value +
                              '&funcao_js=parent.js_mostraruas',
                            'Pesquisa', false);
      } else {
        document.form1.j14_nome.value = ''; 
      }
    }
  }

    
  function js_mostraruas(chave, erro) {
    document.form1.j14_nome.value = chave; 

    if (erro == true) { 
      document.form1.x01_codrua.focus(); 
      document.form1.x01_codrua.value = ''; 
    }

    js_pesquisa_rota();
    
  }

    
  function js_mostraruas1(chave1, chave2) {
    document.form1.x01_codrua.value = chave1;
    document.form1.j14_nome.value = chave2;
    /*colocar foco para ajax */
    db_iframe_ruas.hide();
    js_pesquisa_rota();
  }

    
  function js_pesquisax01_codbairro(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('top.corpo.iframe_aguabase', 'db_iframe_bairro', 
                          'func_bairro.php?funcao_js=parent.js_mostrabairro1|j13_codi|j13_descr', 'Pesquisa', true);

    }else{

      if (document.form1.x01_codbairro.value != '') { 
        js_OpenJanelaIframe('top.corpo.iframe_aguabase', 'db_iframe_bairro', 
                            'func_bairro.php?pesquisa_chave=' + document.form1.x01_codbairro.value +
                              '&funcao_js=parent.js_mostrabairro',
                            'Pesquisa', false);
      } else {
        document.form1.j13_descr.value = ''; 
      }
    }
  }

    
  function js_mostrabairro(chave, erro) {
    document.form1.j13_descr.value = chave; 

    if (erro == true) { 
      document.form1.x01_codbairro.focus(); 
      document.form1.x01_codbairro.value = ''; 
    }
  }

    
  function js_mostrabairro1(chave1, chave2) {

    document.form1.x01_codbairro.value = chave1;
    document.form1.j13_descr.value = chave2;
    db_iframe_bairro.hide();
  }


  function js_pesquisax01_numcgm(mostra) {

    if (mostra == true) {
      js_OpenJanelaIframe('top.corpo.iframe_aguabase', 'db_iframe_cgm',
                          'func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome', 'Pesquisa', true);

    } else {

      if (document.form1.x01_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_aguabase', 'db_iframe_cgm', 
                            'func_nome.php?pesquisa_chave=' + document.form1.x01_numcgm.value +
                              '&funcao_js=parent.js_mostracgm',
                            'Pesquisa', false);
      }else{
        document.form1.z01_nome.value = ''; 
      }
    }
  }

    
  function js_mostracgm(erro, chave) {
    document.form1.z01_nome.value = chave; 
    if (erro == true) { 
      document.form1.x01_numcgm.focus(); 
      document.form1.x01_numcgm.value = ''; 
    }
  }

    
  function js_mostracgm1(chave1, chave2) {
    document.form1.x01_numcgm.value = chave1;
    document.form1.z01_nome.value = chave2;
    db_iframe_cgm.hide();
  }


  function js_pesquisax01_promit(mostra) {

    if (mostra == true) {
      js_OpenJanelaIframe('top.corpo.iframe_aguabase', 'db_iframe_cgmpromit', 
    	                    'func_nome.php?funcao_js=parent.js_mostrapromit1|z01_numcgm|z01_nome', 'Pesquisa', true);
    } else {

      if (document.form1.x01_promit.value != '') { 
        js_OpenJanelaIframe('top.corpo.iframe_aguabase', 'db_iframe_cgmpromit', 
                            'func_nome.php?pesquisa_chave=' + document.form1.x01_promit.value + 
                              '&funcao_js=parent.js_mostrapromit',
                            'Pesquisa', false);
      } else {
        document.form1.z01_nomepromit.value = ''; 
      }
    }
  }

    
  function js_mostrapromit(erro, chave) {
        
    document.form1.z01_nomepromit.value = chave; 

    if (erro == true) { 
      document.form1.x01_promit.focus(); 
      document.form1.x01_promit.value = ''; 
    }
  }

    
  function js_mostrapromit1(chave1, chave2) {
    document.form1.x01_promit.value = chave1;
    document.form1.z01_nomepromit.value = chave2;
    db_iframe_cgmpromit.hide();
  }


  function js_pesquisax01_zona(mostra) {

    if (mostra == true) {
      js_OpenJanelaIframe('top.corpo.iframe_aguabase', 'db_iframe_zonas', 
    	                    'func_zonas.php?funcao_js=parent.js_mostrazonas1|j50_zona|j50_descr', 'Pesquisa', true);
    } else {
          
      if (document.form1.x01_zona.value != '') { 
        js_OpenJanelaIframe('top.corpo.iframe_aguabase', 'db_iframe_zonas', 
                            'func_zonas.php?pesquisa_chave=' + document.form1.x01_zona.value + 
                              '&funcao_js=parent.js_mostrazonas',
                            'Pesquisa', false);
      }else{
        document.form1.j50_descr.value = ''; 
      }
    }
  }

    
  function js_pesquisax01_entrega(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('top.corpo.iframe_aguabase', 'db_iframe_iptucadzonaentrega', 
    	                    'func_iptucadzonaentrega.php?funcao_js=parent.js_mostraiptucadzonaentrega1|j85_codigo|j85_ender|j85_descr',
    	                    'Pesquisa', true);

    } else {

    	if (document.form1.x01_entrega.value != '') { 
        js_OpenJanelaIframe('top.corpo.iframe_aguabase', 'db_iframe_iptucadzonaentrega', 
                            'func_iptucadzonaentrega.php?ender=true&pesquisa_chave=' + document.form1.x01_entrega.value + 
                              '&funcao_js=parent.js_mostraiptucadzonaentrega',
                            'Pesquisa', false);
      } else {

        document.form1.j85_ender.value = ''; 
        document.form1.j85_descr.value = ''; 
      }
    }
  }


  function js_mostrazonas(chave, erro) {
    document.form1.j50_descr.value = chave; 

    if (erro == true) { 
      document.form1.x01_zona.focus(); 
      document.form1.x01_zona.value = ''; 
    }
  }


  function js_mostrazonas1(chave1, chave2) {
    document.form1.x01_zona.value = chave1;
    document.form1.j50_descr.value = chave2;
    db_iframe_zonas.hide();
  }


  function js_mostraiptucadzonaentrega(chave, chave2, erro) {

    document.form1.j85_descr.value = chave;
    document.form1.j85_ender.value = chave2;

    if (erro == true) { 
      document.form1.x01_entrega.focus(); 
	    document.form1.x01_entrega.value = ''; 
	  }
  }


  function js_mostraiptucadzonaentrega1(chave1, chave2, chave3) {
    document.form1.x01_entrega.value = chave1;
    document.form1.j85_ender.value = chave2;
    document.form1.j85_descr.value = chave3;
    db_iframe_iptucadzonaentrega.hide();
  }


  function js_pesquisa() {
    js_OpenJanelaIframe('top.corpo.iframe_aguabase', 'db_iframe_aguabase', 
    	                  'func_aguabase.php?funcao_js=parent.js_preenchepesquisa|x01_matric', 'Pesquisa', true);
  }

    
  function js_preenchepesquisa(chave){
    db_iframe_aguabase.hide();
    <?
      if ($db_opcao != 1) {
        echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
      }
    ?>
  }


  function js_mostracaracteristica() {

	  caracteristica = document.form1.caracteristica.value;

    if (caracteristica != "") {
      js_OpenJanelaIframe('top.corpo.iframe_aguabase', 'db_iframe', 
    	                    'cad1_cargeral001.php?db_opcao=<?=$db_opcao?>&caracteristica=' + caracteristica + '&tipogrupo=A',
    	                    'Pesquisa',true);
    }else{
      js_OpenJanelaIframe('top.corpo.iframe_aguabase', 'db_iframe',
    	                    'cad1_cargeral001.php?db_opcao=<?=$db_opcao?>&tipogrupo=A&codigo=',
    	                    'Pesquisa', true);
    }
  }

  //js_pesquisax01_promit(false);

  if (document.form1.x01_zona.value != '') {
  	js_pesquisax01_zona(false);
  }

  if (document.form1.x01_entrega.value != '') {
    js_pesquisax01_entrega(false);
  }
</script>