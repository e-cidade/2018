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

//MODULO: Habitacao
include(modification("dbforms/db_classesgenericas.php"));
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrotulo                 = new rotulocampo;

$clavaliacaopergunta->rotulo->label();
$clrotulo->label("db102_sequencial");
$clrotulo->label("eso01_db_formulas");
$clrotulo->label("db148_nome");
$clrotulo->label("db50_codigo");
$clrotulo->label("db50_descr");
if (isset($oPost->db_opcaoal)) {
	
  $db_opcao = 33;
  $db_botao = false;
} else if (isset($oPost->opcao) && $oPost->opcao == "alterar") {
	
  $db_botao = true;
  $db_opcao = 2;
} else if(isset($oPost->opcao) && $oPost->opcao == "excluir") {
	
  $db_opcao = 3;
  $db_botao = true;
} else {
	  
  $db_opcao = 1;
  $db_botao = true;
  
  if (isset($oPost->incluir) || isset($oPost->alterar) && $sqlerro == false) {
    $db_opcao = 2;  	
  } 
  
  if (isset($oPost->novo) || isset($oPost->excluir)) {

    $db103_descricao              = "";
    $db103_obrigatoria            = "";
    $db103_ativo                  = "";
    $db103_sequencial             = "";
    $db103_ordem                  = "";
    $db103_avaliacaotiporesposta  = "";
    $db103_identificador          = "";
    $eso01_db_formulas            = "";
    $db148_nome                   = "";
    $db103_tipo                   = "";
    $db103_mascara                = "";
    $db103_camposql               = "";
    $db103_perguntaidentificadora = "";
  }
}

$aTipo = array(
  1 => 'Texto',
  2 => 'CEP',
  3 => 'CNPJ',
  4 => 'CPF',
  5 => 'Data',
  6 => 'Inteiro',
  7 => 'Telefone',
  8 => 'Valor',
  9 => 'Hora'
); 
?>
<form name="form1" method="post" action="">
<fieldset><legend><b>Pergunta</b></legend>
<table border="0" align="left" width="100%">
	<tr>
		<td nowrap title="<?=@$Tdb103_avaliacaogrupopergunta?>">
      <label for="db103_avaliacaogrupopergunta">
		    <b>CÛdigo do Grupo:</b>
      </label>
		</td>
		<td colspan="3"> 
			<?
			  db_input('db103_sequencial',10,$Idb103_sequencial,true,'hidden',3,"");
			  db_input('db103_avaliacaogrupopergunta',10,$Idb103_avaliacaogrupopergunta,true,'text',3,"");
			  db_input('db102_descricao',40,@$Idb102_descricao,true,'text',3,'');
			?>
    </td>
	</tr>
	<tr>
		<td nowrap title="<?=@$Tdb103_descricao?>">
      <label for="db103_descricao">
        <?=@$Ldb103_descricao?>
      </label>
    </td>
		<td colspan="4"> 
			<?
        db_textarea('db103_descricao', 5, 70, $Idb103_descricao, true, 'text', $db_opcao, "");
			?>
    </td>
	</tr>
	<tr>
		<td nowrap title="<?=@$Tdb103_identificador?>">
      <label for="db103_identificador">
        <?=@$Ldb103_identificador?>
      </label>
    </td>
		<td colspan="4"> 
			<?
			  db_input('db103_identificador',62,$Idb103_identificador,true,'text',$db_opcao,"");
			?>
    </td>
	</tr>
  <tr>
    <td nowrap title="<?=@$Tdb103_avaliacaotiporesposta?>">
      <label for="db103_avaliacaotiporesposta">
        <?=@$Ldb103_avaliacaotiporesposta?>
      </label>
    </td>
    <td width="30%"> 
      <?
        db_input('iTipoRespostaAnt',10,$iTipoRespostaAnt,true,'hidden',3,"");
        $sSqlAvaliacaoTipoResposta  = $clavaliacaotiporesposta->sql_query(null, "*", "db105_sequencial", "");
        $rsSqlAvaliacaoTipoResposta = $clavaliacaotiporesposta->sql_record($sSqlAvaliacaoTipoResposta);
      
        $aAvaliacaoTipoResposta     = array();
        $aAvaliacaoTipoResposta[0]  = "Selecione ...";
        for ($iInd = 0; $iInd < $clavaliacaotiporesposta->numrows; $iInd++) {
          
          $oAvaliacaoTipoResposta = db_utils::fieldsMemory($rsSqlAvaliacaoTipoResposta, $iInd);
          $aAvaliacaoTipoResposta[$oAvaliacaoTipoResposta->db105_sequencial] = $oAvaliacaoTipoResposta->db105_descricao; 
        }
        
        db_select('db103_avaliacaotiporesposta', $aAvaliacaoTipoResposta, true, $db_opcao, " onchange='js_desabilitaselecionar(); toogleTipoMascara(this);'");
       ?>
    </td>
    <td nowrap title="<?=@$Tdb103_ordem?>" width="10%">
      <label for="db103_ordem">
        <?=@$Ldb103_ordem?>
      </label>
    </td>
    <td> 
      <?
        db_input('db103_ordem',10,$Idb103_ordem,true,'text',$db_opcao,"");
      ?>
    </td>
  </tr>
	<tr>
		<td nowrap title="<?=@$Tdb103_obrigatoria?>">
      <label for="db103_obrigatoria">
        <?=@$Ldb103_obrigatoria?>
      </label>
    </td>
		<td width="30%"> 
			<?
			  $x = array("f"=>"N√O","t"=>"SIM");
			  db_select('db103_obrigatoria',$x,true,$db_opcao,"");
			?>
    </td>
		<td nowrap title="<?=@$Tdb103_ativo?>" width="10%">
      <label for="db103_ativo">
        <?=@$Ldb103_ativo?>
      </label>
    </td>
		<td> 
      <?
        $x = array("t"=>"SIM","f"=>"N√O");
        db_select('db103_ativo',$x,true,$db_opcao,"");
      ?>
    </td>
	</tr>
  
  <tr>
    <td nowrap title="<?php echo $Tdb103_tipo; ?>">
      <label id="lbl_db103_tipo" for="db103_tipo"><?php echo $Ldb103_tipo; ?></label>
    </td>
    <td><?php db_select('db103_tipo', $aTipo, true, $db_opcao, "onchange='desabilitarMascara(this)'"); ?></td>
    <td nowrap title="<?php echo $Tdb103_mascara; ?>">
      <label id="lbl_db103_mascara" for="db103_mascara"><?php echo $Ldb103_mascara; ?></label>
    </td>
    <td><?php db_input('db103_mascara', 30, $Idb103_mascara, true, "text", $db_opcao); ?></td>
  </tr>
  <tr>
    <td nowrap title="<?php echo $Teso01_db_formulas; ?>">
      <label id="lbl_eso01_db_formulas" for="eso01_db_formulas"><?php echo $Leso01_db_formulas; ?></label>
    </td>
    <td colspan="5">
      <?php db_input('eso01_db_formulas', 10, $Ieso01_db_formulas, true, "text", $db_opcao, "lang=db148_sequencial"); ?>
      <?php db_input('db148_nome', 50, $Idb148_nome, true, "text", $db_opcao); ?>
    </td>
  </tr>
  <tr>
    <td colspan="5" style="display: none">
      <fieldset class="separator">
        <legend>
          VÌnculo com Layout
        </legend>
        <table>
           <tr>
             <td>
               <label for="db50_codigo" id="lblLayout">
                 Layout:
               </label>
             </td>
             <td>
               <?php db_input('db50_codigo', 10, $Idb50_codigo, true, "text", $db_opcaolayout); ?>
               <?php db_input('db50_descr', 50, $Idb50_descr, true, "text", $db_opcaolayout); ?>         
             </td>
           </tr>
          <tr>
            <td>
              <label for="db51_codigo" id="lblLayoutLinha" class="bold">
                Linha:
              </label>
            </td>
            <td>
             <select id="db51_codigo" name="db51_codigo" style="width: 100%;" onChange="getCamposDaLinha(this.value)">
                <option value="0">Selecione</option>
             </select>
            </td>
          </tr>
          <tr>
            <td>
              <label for="db52_codigo" id="lblLayoutLinhacampo" class="bold">
                Campo:
              </label>
            </td>
            <td>
              <select id="db52_codigo" name="db52_codigo" style="width: 100%;" >
                <option value="0">Selecione</option>
              </select>
            </td>
          </tr>
        </table>
      </fieldset>  
  </tr>
  <tr>
    <td colspan="5">
      <fieldset class="separator" id="fldCarga">
        <legend>
          Carga de Dados
        </legend>
        <table>
          <tr>
            <td nowrap title="Campo de vinculo pra carga">
              <label id="lbldb103_camposql" for="db103_camposql"><b>Campo da Carga:</b></label>
            </td>          
            <td>
              <?php db_input('db103_camposql', 25, $Idb103_camposql, true, "text", $db_opcao, '', null, null, null, 40);
              $sMarcado = '';
              if (!empty($db103_perguntaidentificadora)) {
                $sMarcado = $db103_perguntaidentificadora == 't' ? ' checked ' : '';
              }
              ?>
            </td>          
            <td>
              <label id="lbldb103_perguntaidentificadora" for="db103_perguntaidentificadora"><b>Pergunta Identificadora do Formul·rio:</b></label>
           </td>
            <td>
              <input type="checkbox" id="db103_perguntaidentificadora" name="db103_perguntaidentificadora" value="true" <?=$sMarcado;?>>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
</table>
</fieldset>
<table border="0" width="100%">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr align="center">
    <td>
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
             type="submit" id="db_opcao" onclick="return js_validarcampos();"
             value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
             <?=($db_botao==false?"disabled":"")?>>

      <? if ($db_opcao != 1) { ?>
	      <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();"
	             <?=($db_opcao==1||isset($oPost->db_opcaoal)?"style='visibility:hidden;'":"")?>>
	
	      <input name="adicionarrespostas" type="button" id="adicionarrespostas" value="Adicionar Respostas"
	             onclick="js_adicionarrespostas();"
	             <?=($db_opcao==1||isset($oPost->db_opcaoal)?"style='visibility:hidden;'":"")?>
	             <?=($db_opcao==3?"disabled":"")?>>

        <input name="limparRespostas" type="button" id="limparRespostas" value="Limpar Respostas"
               onclick="js_limparRespostas();">
      <? } ?>

    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<table>
	<tr>
		<td valign="top" align="center">  
	    <?
	      $sWhere    = "db103_avaliacaogrupopergunta = {$db103_avaliacaogrupopergunta}";
	      $sCampos   = "db103_sequencial, db103_avaliacaotiporesposta, db103_avaliacaogrupopergunta, db103_descricao";
	      $sCampos  .= ", db103_identificador, db103_obrigatoria, db103_ativo, db103_ordem";
			  $chavepri  = array("db103_sequencial"=>@$db103_sequencial);
			  $cliframe_alterar_excluir->chavepri      = $chavepri;
			  $cliframe_alterar_excluir->sql           = $clavaliacaopergunta->sql_query_file(null,'avaliacaopergunta.*','db103_ordem',$sWhere);
			  $cliframe_alterar_excluir->campos        = $sCampos;
			  $cliframe_alterar_excluir->legenda       = "ITENS LAN«ADOS";
			  $cliframe_alterar_excluir->iframe_height = "160";
			  $cliframe_alterar_excluir->iframe_width  = "600";
			  $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
	    ?>
    </td>
	</tr>
</table>
</form>
<script>
$('db102_descricao').style.width   = '79%';
$('db103_descricao').style.width   = '100%';

require('scripts/widgets/DBLookUp.widget.js');

var oParametrosLookUpFormula = {
  sArquivo : "func_db_formulas.php",
  sLabel   : "Pesquisa F?rmula"
};
var oLookUpFormula = new DBLookUp($('lbl_eso01_db_formulas'), $('eso01_db_formulas'), $('db148_nome'), oParametrosLookUpFormula);

var oLookUpLayout = new DBLookUp($('lblLayout'), $('db50_codigo'), $('db50_descr'), {
  sArquivo : "func_db_layouttxt.php",
  sLabel   : "Pesquisar Layouts",
  callBack : function () {
  
    getLinhasDoLayout($F('db50_codigo'));
   
  }
});

function getLinhasDoLayout(iCodigo) {
  
  new AjaxRequest('hab1_cadastroavaliacao.RPC.php', {'exec':'getLinhasDoLayout', 'codigo_layout':iCodigo}, 
                   function (response, lErro) {
    
    $('db51_codigo').options.length = 1;
    for (oLinha of response.linhas) {
      $('db51_codigo').add(new Option(oLinha.db51_descr, oLinha.db51_codigo));
    }   
    
    if (iCodigoLinha != '') {
      
      $('db51_codigo').value = iCodigoLinha;
      delete iCodigoLinha;
    }
  }).setMessage('Aguarde, carregando linhas...').execute();  
}

/**
 * Retorna os campos da linhas do layout
 */ 
function getCamposDaLinha(iCodigoParametro) {
  
  $('db52_codigo').options.length = 1;
  var iCodigo = iCodigoParametro;
  
  if (iCodigo == 0) {
    return;
  }
  new AjaxRequest('hab1_cadastroavaliacao.RPC.php', {'exec':'getCamposDaLinha', 'codigo_linha':iCodigo},
    function (response, lErro) {
      
      $('db52_codigo').options.length = 1;
      for (campo of response.campos) {
        $('db52_codigo').add(new Option(campo.db52_descr, campo.db52_codigo));
      }      
      if (iCodigoCampo != '') {
        $('db52_codigo').value = iCodigoCampo;
      }
    }).setMessage('Aguarde, carregando campos...').execute();  
}

function toogleTipoMascara (oSelectAvaliacaoTipoResposta) {

  var oLinhaTipoMascara = document.getElementById('db103_mascara').parentNode.parentNode;
  oLinhaTipoMascara.style.display = 'table-row';

  if(typeof oSelectAvaliacaoTipoResposta != "object") {
    var oSelectAvaliacaoTipoResposta = document.getElementById('db103_avaliacaotiporesposta');
  }

  if(oSelectAvaliacaoTipoResposta.value != 2) { // 2 - Dissertativa
    oLinhaTipoMascara.style.display = 'none';
  }
}

function desabilitarMascara (oSelectTipo) {

  var oMascara            = document.getElementById('db103_mascara');
  var oCelulaMascara      = oMascara.parentNode;
  var oCelulaLabelMascara = oMascara.parentNode.previousElementSibling;

  oCelulaMascara.style.display      = 'table-cell';
  oCelulaLabelMascara.style.display = 'table-cell';

  oMascara.removeClassName('readonly');
  oMascara.removeAttribute('readonly');

  if(typeof oSelectTipo != "object") {
    var oSelectTipo = document.getElementById('db103_tipo');
  }

  if(oSelectTipo.value != 1) { // 1 - Texto
    
    oCelulaMascara.style.display      = 'none';
    oCelulaLabelMascara.style.display = 'none';
    
    oMascara.addClassName('readonly');
    oMascara.setAttribute('readonly', true);
    oMascara.value = '';
  }
}

function js_disbledadicionarresp() {

  var iAvaliacaoTipoResposta = $('db103_avaliacaotiporesposta').value;
  var sBotao = $('db_opcao').value;
  
  if ($('adicionarrespostas')) {
    $('adicionarrespostas').disabled = false;
  }
  
  if (iAvaliacaoTipoResposta == 2) {
  
	  if ($('adicionarrespostas')) {
	    $('adicionarrespostas').disabled = true;
	  }
  }
  
  if (sBotao == 'Excluir') {
    
    if ($('adicionarrespostas')) {
      $('adicionarrespostas').disabled = true;
    }
  }
  
}

function js_desabilitaselecionar() {

  var iAvaliacaoTipoResposta  = $('db103_avaliacaotiporesposta').value; 
  if (iAvaliacaoTipoResposta != 0) {
    $('db103_avaliacaotiporesposta').options[0].disabled = true; 
  }
  
  js_disbledadicionarresp();
}

function js_validarcampos() {

  var iAvaliacaoTipoResposta = $('db103_avaliacaotiporesposta').value;
  var iOrdem                 = $('db103_ordem').value;
  var sOpcao                 = $('db_opcao').value;
  
  if (iAvaliacaoTipoResposta == 0) {
  
    var sMsg  = "Usuario:\n\n";
        sMsg += " Informe o Tipo de Resposta!\n\n";
    alert(sMsg);
    return false;
  }
  
  if (iOrdem == '') {
  
    var sMsg  = "Usuario:\n\n";
        sMsg += " Informe a Ordem!\n\n";
    alert(sMsg);
    return false;
  }

  return js_validaCaracteres();
}

function js_cancelar(){

  var opcao = document.createElement("input");
      opcao.setAttribute("type","hidden");
      opcao.setAttribute("name","novo");
      opcao.setAttribute("value","true");
      document.form1.appendChild(opcao);
      document.form1.submit();
}

function js_adicionarrespostas() {
  
  var sUrl = 'hab1_avaliacaoperguntaopcao001.php?db103_sequencial='+$('db103_sequencial').value;
  js_OpenJanelaIframe('','db_iframe_avaliacaotiporesposta',sUrl,'Pesquisa',true,'0');
}

function js_limparRespostas() {
  
  AjaxRequest.create('eso4_preenchimento.RPC.php', {exec: 'limparRespostas', pergunta: $F('db103_sequencial')}, function (response) {

    console.log(response);

    if(response.mensagem) {
      alert(response.mensagem);
    }

  }).setMessage('Excluindo respostas...').execute();
}

/**
 * Validamos os caracteres do identificador registrado
 * Primeiramente verificamos o caracter inicial, permitindo apenas letras
 * Em seguida, verificamos o que vem a seguir, permitindo letras, numeros e _
 */
function js_validaCaracteres() {

  var sValorInicial     = $F('db103_identificador').substring(0,1);
  var sExpressaoInicial = /[A-Za-z]/;
  var sRegExpInicial    = new RegExp(sExpressaoInicial);
  var lResultadoInicial = sRegExpInicial.test(sValorInicial);

  if (sValorInicial == '') {

    alert('… necess·rio informar um identificador.');
    $('db103_identificador').focus();
    return false;
  } 
  
  if (lResultadoInicial) {

    var sValorCaracteres      = $F('db103_identificador').substring(1);
    var sExpressaoCaracteres  = /^[A-Za-z0-9_]+?$/i;
    var sRegExpCaracteres     = new RegExp(sExpressaoCaracteres);
    var lResultadoCaracteres  = sRegExpCaracteres.test(sValorCaracteres);
    if (!lResultadoCaracteres) {

      alert('N„o s„o permitidos caracteres especiais e espaÁos no campo Identificador.');
      return false;
    }
  } else {

    alert('N„o s„o permitidos caracteres especiais e espaÁos no campo Identificador.');
    return false;
  }

  return true;
}
(function() {
  
  oToogle = new DBToogle('fldCarga', false);
  var lAbrirFieldset = $F('db103_camposql') != '' || $('db103_perguntaidentificadora').checked;
  if (lAbrirFieldset) {
    oToogle.show(true);
  }
  iCodigoLinha = '<?=$db51_codigo;?>';
  iCodigoCampo = '<?=$db103_dblayoutcampo?>';
  var bloquearCampo = <?=$bloquearCampo;?>;
  if ($F('db50_codigo') != '') {
  
    oLookUpLayout.desabilitar();
    getLinhasDoLayout($F('db50_codigo'));
    if (bloquearCampo) {
      $('db51_codigo').disable();
    }
    getCamposDaLinha(iCodigoLinha);
    
  }
})();

function montarIdentificador(textoBase) {
  
  var listaStringTrocar = "·‡„‚‰ÈËÍÎÌÏÓÔÛÚıÙˆ˙˘˚¸Á¡¿√¬ƒ…» ÀÕÃŒœ”“’÷‘⁄Ÿ€‹«";
  var listaStringSubstituir = "aaaaaeeeeiiiiooooouuuucAAAAAEEEEIIIIOOOOOUUUUC";
  var stringIdentificador = "";
  for (var i = 0; i < textoBase.length; i++) {
    if (listaStringTrocar.indexOf(textoBase.charAt(i)) != -1) {
      stringIdentificador += listaStringSubstituir.substr(listaStringTrocar.search(textoBase.substr(i, 1)), 1);
    } else {
      stringIdentificador += textoBase.substr(i, 1);
    }
  }
  
  stringIdentificador = stringIdentificador.replace(/[^a-zA-Z 0-9]/g, '');
  var identificador = stringIdentificador.replace(/ /g, '_').toLowerCase().substr(0, 50);
  return identificador;
}
$('db103_descricao').observe('blur', function() {
  if ($F('db103_identificador') == ''){
    $('db103_identificador').value = montarIdentificador(this.value);
  }
});
toogleTipoMascara();
desabilitarMascara();
</script>