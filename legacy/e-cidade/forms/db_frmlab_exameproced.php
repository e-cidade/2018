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

include(modification("dbforms/db_classesgenericas.php"));
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir ( );
//MODULO: Laboratório
$cllab_exameproced->rotulo->label();
$clrotulo = new rotulocampo();
$clrotulo->label("la08_c_descr");
$clrotulo->label("sd63_c_nome");
$clrotulo->label("sd63_c_procedimento");
$clrotulo->label("la53_i_codigo");
$clrotulo->label("la08_i_codigo");

?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=$Tla53_i_codigo?>">
       <?=$Lla53_i_codigo?>
    </td>
    <td>
      <?php
        db_input('la53_i_codigo',10,$Ila53_i_codigo,true,'text',3,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=$Tla53_i_exame?>">
       <?php
        db_ancora($Lla53_i_exame,"js_pesquisala53_i_exame(true);",3);
       ?>
    </td>
    <td>
      <?
        db_input('la53_i_exame',10,$Ila53_i_exame,true,'text',3," onchange='js_pesquisala53_i_exame(false);'");
        db_input('la08_c_descr',50,$Ila08_c_descr,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=$Tla53_i_procedimento?>">
       <?php
        db_ancora($Lla53_i_procedimento,"js_pesquisala53_i_procedimento(true);",$db_opcao);
       ?>
    </td>
    <td>
      <?php
        db_input('sd63_c_procedimento',10,$Isd63_c_procedimento,true,'text',$db_opcao," onchange='js_pesquisala53_i_procedimento(false);'");
        db_input('la53_i_procedimento',10,$Ila53_i_procedimento,true,'hidden',$db_opcao,"");
        db_input('sd63_c_nome', 50, $Isd63_c_nome,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <fieldset> <legend><b>Valor do Procedimento:</b></legend>
        <table>
          <tr>
            <td>
              <b>
              Valor SUS:
              </b>
              <?php
                $valorsus = isset($valorsus) ? $valorsus : 0;
                db_input('valorsus', 10, '', true, 'text', 3, '');
              ?>
              <b>
              +
              </b>
              <?php
                echo $Lla53_n_acrescimo;
                $la53_n_acrescimo = isset($la53_n_acrescimo) ? $la53_n_acrescimo : 0;
                db_input('la53_n_acrescimo', 10, $Ila53_n_acrescimo, true, 'text', $db_opcao,
                         'onchange="js_calculaValorProcedimento();"'
                        );
              ?>
              <b>
              =
              Total:
              </b>
              <?php
                $valortotal = isset($valortotal) ? $valortotal : 0;
                db_input('valortotal', 10, '', true, 'text', 3, '');
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=$Tla53_i_ativo?>">
       <?=$Lla53_i_ativo?>
    </td>
    <td>
      <?php
        $x = array('1'=>'SIM','2'=>'NÃO');
        db_select('la53_i_ativo',$x,true,$db_opcao,"");
      ?>
    </td>
  </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="button" value="Cancelar" <?=($db_botao1 == false ? "disabled" : "")?> onclick="location.href='lab1_lab_exameproced001.php?la53_i_exame=<?=$la53_i_exame?>&la08_c_descr=<?=$la08_c_descr?>'">

<table width="100%">
	<tr>
		<td valign="top"><br>
      <?php
		  $chavepri = array ('la08_i_codigo'       => @$la08_i_codigo,
                         'la08_c_descr'        => @$la08_c_descr,
                         "la53_i_codigo"       => @$la53_i_codigo,
                         "la53_i_procedimento" => @$la53_i_procedimento,
                         "la53_i_exame"        => @$la53_i_exame,
                         "la53_n_acrescimo"    => @$la53_n_acrescimo,
                         "la53_i_ativo"        => @$la53_i_ativo,
                         "sd63_c_nome"         => @$sd63_c_nome,
                         "sd63_c_procedimento" => @$sd63_c_procedimento
                        );
		  $cliframe_alterar_excluir->chavepri = $chavepri;
		  $sCampos  = ' la08_i_codigo, la08_c_descr, la53_i_codigo, la53_i_procedimento,';
      $sCampos .= ' la53_i_exame,la53_i_ativo,sd63_c_nome, sd63_c_procedimento, la53_n_acrescimo ';
		  @$cliframe_alterar_excluir->sql = $cllab_exameproced->sql_query2 ("", $sCampos, "la08_c_descr", "la53_i_exame = $la53_i_exame" );
		  $cliframe_alterar_excluir->campos = "la53_i_codigo,sd63_c_nome,la53_i_ativo";
		  $cliframe_alterar_excluir->legenda = "Registros";
      $cliframe_alterar_excluir->msg_vazio = "Não foi encontrado nenhum registro.";
		  $cliframe_alterar_excluir->textocabec = "#DEB887";
		  $cliframe_alterar_excluir->textocorpo = "#444444";
		  $cliframe_alterar_excluir->fundocabec = "#444444";
		  $cliframe_alterar_excluir->fundocorpo = "#eaeaea";
		  $cliframe_alterar_excluir->iframe_height = "200";
		  $cliframe_alterar_excluir->iframe_width = "100%";
		  $cliframe_alterar_excluir->tamfontecabec = 9;
		  $cliframe_alterar_excluir->tamfontecorpo = 9;
		  $cliframe_alterar_excluir->formulario = false;
		  $cliframe_alterar_excluir->opcoes = 3;
		  $cliframe_alterar_excluir->iframe_alterar_excluir ( $db_opcao );
		?>
   </td>
  </tr>
</table>

</form>
<script>

js_getValorProcedimento();

function js_ajax(oParam, jsRetorno, sUrl, lAsync) {

  var mRetornoAjax;

  if (sUrl == undefined) {
    sUrl = 'lab4_laboratorio.RPC.php';
  }

  if (lAsync == undefined) {
    lAsync = false;
  }

  var oAjax = new Ajax.Request(sUrl,
                               {
                                 method: 'post',
                                 asynchronous: lAsync,
                                 parameters: 'json='+Object.toJSON(oParam),
                                 onComplete: function(oAjax) {

                                               var evlJS    = jsRetorno+'(oAjax);';
                                               return mRetornoAjax = eval(evlJS);

                                           }
                              }
                             );

  return mRetornoAjax;

}

function js_pesquisala53_i_procedimento(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('', 'db_iframe_sau_procedimento', 'func_sau_procedimento.php?'+
                        'funcao_js=parent.js_mostrasau_procedimento1|sd63_c_procedimento|'+
                        'sd63_c_nome|sd63_i_codigo&lVinculaProcedimentos=true', 'Pesquisa', true
                       );

  } else {

     if (document.form1.sd63_c_procedimento.value != '') {

        js_OpenJanelaIframe('', 'db_iframe_sau_procedimento', 'func_sau_procedimento.php?pesquisa_chave='+
                            document.form1.sd63_c_procedimento.value+
                            '&funcao_js=parent.js_mostrasau_procedimento&lVinculaProcedimentos=true', 'Pesquisa', false
                           );

     } else {

       document.form1.sd63_c_nome.value = '';
       document.form1.la53_i_procedimento.value = '';
       document.form1.sd63_c_procedimento.value = '';
       $('valorsus').value                      = 0;
       js_calculaValorProcedimento();

     }

  }

}
function js_mostrasau_procedimento(chave, erro, codigo) {

  document.form1.sd63_c_nome.value = chave;
  if (erro == true) {

    document.form1.la53_i_procedimento.focus();
    document.form1.la53_i_procedimento.value = '';
    document.form1.sd63_c_procedimento.value = '';
    $('valorsus').value                      = 0;
    js_calculaValorProcedimento();

  } else {

	  document.form1.la53_i_procedimento.value = codigo;
    js_getValorProcedimento();

  }

}
function js_mostrasau_procedimento1(chave1, chave2, codigo) {

  document.form1.sd63_c_procedimento.value = chave1;
  document.form1.sd63_c_nome.value         = chave2;
  document.form1.la53_i_procedimento.value = codigo;
  db_iframe_sau_procedimento.hide();
  js_getValorProcedimento();

}

function js_getValorProcedimento() {

  if ($F('sd63_c_procedimento').trim() == '') {
    return false;
  }

  var oParam           = new Object();
	oParam.exec          = 'getValorProcedimento';
	oParam.sProcedimento = $F('sd63_c_procedimento');

  js_ajax(oParam, 'js_retornoGetValorProcedimento');

}

function js_retornoGetValorProcedimento(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus != 1) {

    alert(oRetorno.sMessage.urlDecode());
    $('valorsus').value = 0;
    return false;

  } else {

    if (oRetorno.nValor != '') {
      $('valorsus').value = oRetorno.nValor;
    } else {

      alert('Nenhum valor encontrado para o procedimento.');
      $('valorsus').value = 0;

    }

  }
  js_calculaValorProcedimento();

}

function js_calculaValorProcedimento() {

  var nValorSus = 0.0;
  var nValorSms = 0.0;
  if ($F('valorsus') != '') {
    nValorSus = parseFloat($F('valorsus'));
  }
  if ($F('la53_n_acrescimo') != '') {
    nValorSms = parseFloat($F('la53_n_acrescimo'));
  }

  $('valortotal').value = (nValorSus + nValorSms).toFixed(2);

}

</script>