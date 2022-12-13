<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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


//MODULO: educação
require_once("dbforms/db_classesgenericas.php");

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrotulo      = new rotulocampo;
$clcaddisciplina->rotulo->label();
$clrotulo->label("ed265_i_codigo");
$clrotulo->label("ed232_c_descrcompleta");

$db_botao1 = false;

/* 
 * Verifica se a disciplina possui registros na tabela censocaddisciplina
 * Caso encontre significa que a disciplina é global
 */
function checaGlobal($iCodigo) {

  $oDaoCensoCadDisciplina = db_utils::getdao('censocaddisciplina');

  /* Busco na tabela censocaddisciplina registros para descobrir se a disciplina é global */
  $sSql  = $oDaoCensoCadDisciplina->sql_query("", "*", "", "ed294_caddisciplina = $iCodigo");
  $rsSql = $oDaoCensoCadDisciplina->sql_record($sSql);
  
  if ($oDaoCensoCadDisciplina->numrows > 0) {
    return true;
  }

  return false;

}

if (isset($opcao) && $opcao == "alterar") {
  
  $lDiscGlobal = checaGlobal($ed232_i_codigo);

  

  $db_opcao    = 2;
  $db_botao1   = true;

} elseif (isset($opcao) && $opcao == "excluir" 
          || isset($db_opcao) && $db_opcao == 3) {
  
  $lDiscGlobal = checaGlobal($ed232_i_codigo);
  $db_botao1   = true;
  $db_opcao    = 3;

} else {

  if (isset($alterar)) {

    $db_opcao  = 2;
    $db_botao1 = true;
  
  } else {
    $db_opcao = 1;
  }

}
?>
<form name="form1" method="post" action="">
<center>
  <table border="0">
    <tr>
      <td nowrap title="<?=@$Ted232_i_codigo?>">
        <?=@$Led232_i_codigo?>
      </td>
      <td>
        <?db_input('ed232_i_codigo', 15, $Ied232_i_codigo, true, 'text', 3, "")?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Ted232_areaconhecimento?>">
        <?db_ancora(@$Led232_areaconhecimento, "js_pesquisaAreaConhecimento(true);", $db_opcao)?>
      </td>
      <td>
        <?db_input('ed232_areaconhecimento', 15, $Ied232_areaconhecimento, true, 'text', 1, "onchange='js_pesquisaAreaConhecimento(false);'");?>
        <?db_input('ed293_descr', 60, @$Ied295_descr, true, 'text', 3, '');?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Ted232_c_descr?>">
        <?=@$Led232_c_descr?>
      </td>
      <td>
        <?db_input('ed232_c_descr', 78, $Ied232_c_descr, true, 'text', $db_opcao, "")?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Ted232_c_descrcompleta?>">
        <?=@$Led232_c_descrcompleta?>
      </td>
      <td>
        <?db_input('ed232_c_descrcompleta', 78, $Ied232_c_descrcompleta, true, 'text', $db_opcao, "")?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Ted232_c_abrev?>">
        <?=@$Led232_c_abrev?>
      </td>
      <td>
        <?db_input('ed232_c_abrev', 10, $Ied232_c_abrev, true, 'text', $db_opcao, "")?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Ted265_i_codigo?>">
        <?db_ancora(@$Led265_i_codigo, "js_pesquisacodcenso(true);", $db_opcao);?>
      </td>
      <td>
        <div id="arquivoAux">
          <?db_input('webauxilia', 50, '', true, 'hidden', 3, '')?>
          <select multiple size="5" name="oAux" id="oAux" style="width:39em;"
                  onDblClick="js_apagarLinha(this);" 
                  <?=($db_opcao == 1 || $db_opcao == 2 || $db_opcao == 22 ?"" : "disabled")?> >
          </select>
          <p align="center"><b>Dois cliques sobre o item exclui!</b></p>
        </div>
      </td>
    </tr>
    <tr>
      <td></td>
      <td>
        <?db_input('ed265_i_codigo', 15, @$Ied265_i_codigo, true, 'hidden', 3, "")?>
        <?db_input('ed265_c_descr', 60, @$Ied265_c_descr, true, 'hidden', 3, '')?>
      </td>
    </tr>
  </table>
  
  <input name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>" 
         type="submit" id="db_opcao" onClick="return js_valida();" 
         value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"))?>" 
         <?=($db_botao == false ? "disabled" : "")?> >
  
  <input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1 == false ? "disabled" : "")?> >
  <table width='100%'>
    <tr>
      <td valign="top">
        <?
          $chavepri = array(
                             "ed232_i_codigo"         => @$ed232_i_codigo,
                             "ed232_c_descr"          => @$ed232_c_descr,
                             "ed232_areaconhecimento" => @$ed232_areaconhecimento,
                             "ed232_c_abrev"          => @$ed232_c_abrev,
                             "ed265_i_codigo"         => @$ed265_i_codigo,
                             "ed265_c_descr"          => @$ed265_c_descr,
                             "ed232_c_descrcompleta"  => @$ed232_c_descrcompleta,
                             "ed293_descr"            => @$ed293_descr
                           );
          $cliframe_alterar_excluir->chavepri      = $chavepri;
          $cliframe_alterar_excluir->sql           = $clcaddisciplina->sql_query_censo(@$ed232_i_codigo, "*", "ed232_i_codigo");
          $cliframe_alterar_excluir->campos        = "ed232_i_codigo,ed232_c_descr,ed232_c_descrcompleta,ed232_c_abrev";
          $cliframe_alterar_excluir->campos       .= ",ed232_areaconhecimento,ed265_i_codigo";
          $cliframe_alterar_excluir->legenda       = "Registros";
          $cliframe_alterar_excluir->msg_vazio     = "Não foi encontrado nenhum registro.";
          $cliframe_alterar_excluir->textocabec    = "#DEB887";
          $cliframe_alterar_excluir->textocorpo    = "#444444";
          $cliframe_alterar_excluir->fundocabec    = "#444444";
          $cliframe_alterar_excluir->fundocorpo    = "#eaeaea";
          $cliframe_alterar_excluir->iframe_height = "300";
          $cliframe_alterar_excluir->iframe_width  = "100%";
          $cliframe_alterar_excluir->tamfontecabec = 9;
          $cliframe_alterar_excluir->tamfontecorpo = 9;
          $cliframe_alterar_excluir->formulario    = false;
          $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
        ?>
      </td>
    </tr>
  </table>
</center>
</form>

<script language="JavaScript">

function js_pesquisacodcenso(mostra) {
  
  js_OpenJanelaIframe('', 'db_iframe_censodisciplina',
                      'func_censodisciplina.php?funcao_js=parent.js_mostracensodisciplina1|ed265_i_codigo|ed265_c_descr',
                      'Pesquisa de Disciplinas do Censo Escolar', true
                     );

}

function js_mostracensodisciplina1(chave1, chave2) {

	if (js_checaSelect(chave1) == false) {
    $('oAux').options[$('oAux').length] = new Option(chave2, chave1);
	}

  db_iframe_censodisciplina.hide();

}

function js_pesquisaAreaConhecimento(lMostra) {

	if (lMostra) {

    js_OpenJanelaIframe('', 'db_iframe_areaconhecimento',
                        'func_areaconhecimento.php?funcao_js=parent.js_mostraAreaConhecimento|ed293_sequencial|ed293_descr',
                        'Pesquisa de Área de Conhecimento', true
                       );
	} else {

		if ($F('ed232_areaconhecimento').value != '') {
			js_OpenJanelaIframe('', 'db_iframe_areaconhecimento',
                          'func_areaconhecimento.php?pesquisa_chave='+$('ed232_areaconhecimento').value+
                                                   '&funcao_js=parent.js_mostraAreaConhecimento1',
                          'Pesquisa de Área de Conhecimento', false
                         );
		} else {
			$('ed293_descr').value = '';
		}
	}

}

function js_mostraAreaConhecimento(chave1, chave2) {

  $('ed232_areaconhecimento').value = chave1;
  $('ed293_descr').value            = chave2;
  db_iframe_areaconhecimento.hide();

}

function js_mostraAreaConhecimento1(chave1, erro, chave2) {

	$('ed293_descr').value = chave1;
	if (erro) {
		$('ed232_areaconhecimento').focus();
		$('ed232_areaconhecimento').value = '';
	}
}

/* Apaga a linha após um duplo clique na linha escolhida. */
function js_apagarLinha(oAux) {
  
  $('oAux').options[oAux.selectedIndex] = null;

}

function js_valida() {

  if ($('db_opcao').value == "Excluir") {

    return true;

  } else {
    
    var select = $('oAux')
    
    if ($('oAux').length == 0) {

      alert ("Você tem que selecionar uma disciplina do censo para incluir/alterar a disciplina.");
      return false;
    }
    var sTemp = "";

    for (var iCont = 0; iCont < select.length; iCont++) {

      sTemp += select[iCont].value+"|";

    }
    
    $('webauxilia').value = sTemp;
    return true;
  }

}

function js_buscaDisciplinas(iCodigo) {

  var oParam     = new Object();

  oParam.exec    = "getDisciplinas";
  oParam.iCodigo = iCodigo;

  sUrlRPC        = "edu4_escola.RPC.php";

  js_webajax(oParam, 'js_retornoDisciplinas', sUrlRPC);

}

function js_retornoDisciplinas(oRetorno) {
  
  var oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iTotalRegistros > 0) {

    for (var iCont = 0; iCont < oRetorno.iTotalRegistros; iCont++) {

      $('oAux').options[$('oAux').length] = new Option(oRetorno.aResultado[iCont].ed265_c_descr.urlDecode(), 
                                                       oRetorno.aResultado[iCont].ed265_i_codigo);

    }

  }

}

function js_checaSelect(iCodigo) {

  var oSelect = $('oAux');
  var iSize   = oSelect.length;
  var lResult = false;

  for (var iCont = 0; iCont < iSize; iCont++) {

    if (oSelect.options[iCont].value == iCodigo) {
      lResult = true;
    }
  
  }

  return lResult;

}

<?

if ($lDiscGlobal) {
  echo("\n js_buscaDisciplinas($ed232_i_codigo); \n");
}

?>

</script>