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

//MODULO: educação
$oDaoEfetividadeRh->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed18_i_codigo");
$ed98_i_escola = db_getsession("DB_coddepto");
$ed18_c_nome   = db_getsession("DB_nomedepto");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Ted98_i_codigo?>">
   <?=@$Led98_i_codigo?>
  </td>
  <td>
   <?db_input('ed98_i_codigo', 15, $Ied98_i_codigo, true, 'text', 3, "")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted98_i_escola?>">
   <?db_ancora(@$Led98_i_escola, "", 3);?>
  </td>
  <td>
   <?db_input('ed98_i_escola', 15, $Ied98_i_escola, true, 'text', 3, "")?>
   <?db_input('ed18_c_nome', 50, @$Ied18_c_nome, true, 'text', 3, '')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted98_c_tipo?>">
   <?=@$Led98_c_tipo?>
  </td>
  <td>
   <?
   $x = array(""=>"", "P"=>"PROFESSORES", "F"=>"FUNCIONÁRIOS");
   db_select('ed98_c_tipo', $x, true, $db_opcao1, "onchange='js_pesquisaultimo()'")
   ?>
  </td>
 </tr>
 <tr>
  <td nowrap colspan="2">
   <div id="ultimo_registro"></div>
  </td>
 </tr>
 <tr>
  <td nowrap colspan="2">
   <?$visible = isset($ed98_c_tipocomp)?"visible":"hidden"?>
   <div id="tipo_comp" style="visibility:<?=$visible?>;">
    <table>
     <tr>
      <td nowrap title="<?=@$Ted98_c_tipocomp?>">
       <?=@$Led98_c_tipocomp?>
      </td>
      <td>
       <?
       $x = array(""=>"", "M"=>"MENSAL", "P"=>"PERIÓDICA");
       db_select('ed98_c_tipocomp', $x, true, $db_opcao1, "onchange='js_competencia(this.value)'");
       ?>
      </td>
     </tr>
    </table>
   </div>
  </td>
 </tr>
 <tr>
  <td nowrap colspan="2">
   <?$visible = isset($ed98_c_tipocomp)&&$ed98_c_tipocomp=="M"?"visible":"hidden"?>
   <div id="comp_mensal" style="position:absolute;visibility:<?=$visible?>;">
    <table>
     <tr>
      <td nowrap title="<?=@$Ted98_i_mes?>">
       <?=@$Led98_i_mes?>
       <?
       $x = array(""=>"", "1"=>"JANEIRO", "2"=>"FEVEREIRO", "3"=>"MARÇO", "4"=>"ABRIL", "5"=>"MAIO", "6"=>"JUNHO",
                  "7"=>"JULHO", "8"=>"AGOSTO", "9"=>"SETEMBRO", "10"=>"OUTUBRO", "11"=>"NOVEMBRO", "12"=>"DEZEMBRO"
                 );
       db_select('ed98_i_mes', $x, true, $db_opcao1, "onchange='js_mesano()'");
       ?>
      </td>
      <td>
       <?=@$Led98_i_ano?>
       <?
       $arr_anos[""] = "";
       for($y=(date("Y")+1);$y>(date("Y")-30);$y--){
        $arr_anos[$y] = $y;
       }
       $x = $arr_anos;
       db_select('ed98_i_ano', $x, true, $db_opcao1, "onchange='js_mesano()'");
       ?>
     </td>
    </tr>
    </table>
   </div>
   <?$visible = isset($ed98_c_tipocomp)&&$ed98_c_tipocomp=="P"?"visible":"hidden"?>
   <div id="comp_periodo" style="position:absolute;visibility:<?=$visible?>;">
    <table>
     <tr>
      <td nowrap title="<?=@$Ted98_d_dataini?>">
       <?=@$Led98_d_dataini?>
       <?db_inputdata('ed98_d_dataini', @$ed98_d_dataini_dia, @$ed98_d_dataini_mes,
                      @$ed98_d_dataini_ano, true, 'text', $db_opcao1, ""
                     )
       ?>
      </td>
      <td>
       <?=@$Led98_d_datafim?>
       <?db_inputdata('ed98_d_datafim', @$ed98_d_datafim_dia, @$ed98_d_datafim_mes,
                      @$ed98_d_datafim_ano, true, 'text', $db_opcao1, ""
                     )
       ?>
     </td>
    </tr>
    </table>
   </div>
  </td>
 </tr>
</table>
</center>
<br><br>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
       type="submit" id="db_opcao"
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
       <?=($db_opcao==1||$db_opcao==2?"disabled":"")?>
       <?=($db_opcao==1?"onclick='return js_inclusao()'":($db_opcao==3?"onclick=\"return confirm('Atenção! Todos os registros informados na aba Efetividade serão excluídos. Confirmar Exclusão?')\"":""))?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar"
       onclick="js_pesquisa();" <?=$db_opcao==1?"disabled":""?>>
<input name="novo" type="button" id="novo" value="Novo Registro" onclick="js_novo()" <?=$db_opcao==1?"disabled":""?>>
<input name="retorno" type="hidden" id="retorno" value="">
</form>
<script>
function js_pesquisa() {

  js_OpenJanelaIframe('', 'db_iframe_efetividaderh',
		              'func_efetividaderh.php?funcao_js=parent.js_preenchepesquisa|ed98_i_codigo',
		              'Pesquisa de Efetividades', true
		             );

}

function js_preenchepesquisa(chave) {

  db_iframe_efetividaderh.hide();
  <?
   if ($db_opcao != 1) {
     echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
   }
  ?>

}

function js_novo() {
  parent.location.href="edu1_efetividaderhabas001.php";
}

function js_limpatudo() {

  $('ed98_i_mes').value         = "";
  $('ed98_i_ano').value         = "";
  $('ed98_d_dataini').value     = "";
  $('ed98_d_dataini_dia').value = "";
  $('ed98_d_dataini_mes').value = "";
  $('ed98_d_dataini_ano').value = "";
  $('ed98_d_datafim').value     = "";
  $('ed98_d_datafim_dia').value = "";
  $('ed98_d_datafim_mes').value = "";
  $('ed98_d_datafim_ano').value = "";

}

function js_limpadatas() {

  $('ed98_d_dataini').value     = "";
  $('ed98_d_dataini_dia').value = "";
  $('ed98_d_dataini_mes').value = "";
  $('ed98_d_dataini_ano').value = "";
  $('ed98_d_datafim').value     = "";
  $('ed98_d_datafim_dia').value = "";
  $('ed98_d_datafim_mes').value = "";
  $('ed98_d_datafim_ano').value = "";

}

function js_pesquisaultimo(valor) {

  if ($('ed98_c_tipo').value == "") {

    $('tipo_comp').style.visibility    = "hidden";
    $('ed98_c_tipocomp').value         = "";
    $('comp_mensal').style.visibility  = "hidden";
    $('comp_periodo').style.visibility = "hidden";
    $('ultimo_registro').innerHTML     = "";
    $('db_opcao').disabled             = true;
    js_limpatudo();

 } else {

   $('tipo_comp').style.visibility    = "hidden";
   $('ed98_c_tipocomp').value         = "";
   $('comp_mensal').style.visibility  = "hidden";
   $('comp_periodo').style.visibility = "hidden";
   $('ultimo_registro').innerHTML     = "";
   $('db_opcao').disabled             = true;
   js_limpatudo();
   js_divCarregando("Aguarde,  buscando registros", "msgBox");
   var sAction = 'PesquisaUltimo';
   var url     = 'edu1_efetividaderhRPC.php';
   parametros  = 'iEscola='+$('ed98_i_escola').value+'&tipo='+$('ed98_c_tipo').value;
   var oAjax = new Ajax.Request(url, {method    : 'post',
                                     parameters: parametros+'&sAction='+sAction,
                                     onComplete: js_retornoPesquisaUltimo
                                    });

 }

}

function js_retornoPesquisaUltimo(oAjax) {

  mes_ext = new Array("JANEIRO", "FEVEREIRO", "MARÇO", "ABRIL", "MAIO", "JUNHO",
		              "JULHO", "AGOSTO", "SETEMBRO", "OUTUBRO", "NOVEMBRO", "DEZEMBRO"
		             );
  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno == "0") {

    sHtml  = '<b>Último registro:</b> Nenhum registro.';
    sHtml += '<input type="hidden" name="ult_mes" id="ult_mes" value="" size="10">';
    sHtml += '<input type="hidden" name="ult_ano" id="ult_ano" value="" size="10">';
    sHtml += '<input type="hidden" name="ult_dtini" id="ult_dtini" value="" size="10">';
    sHtml += '<input type="hidden" name="ult_dtfim" id="ult_dtfim" value="" size="10">';
    $('ultimo_registro').innerHTML = sHtml;
    $('tipo_comp').style.visibility = "visible";

  } else {

    with (oRetorno[0]) {

      if (ed98_c_tipocomp.urlDecode() == "M") {

        mes_indice  = parseInt(ed98_i_mes.urlDecode())-1;
        competencia = "<b>MENSAL</b> -> Mês/Ano: <b>"+mes_ext[mes_indice]+" / "+ed98_i_ano.urlDecode()+"</b>";

      } else {

        data_inicial = ed98_d_dataini.urlDecode().substr(8, 2)+"/"+ed98_d_dataini.urlDecode().substr(5, 2)+
                       "/"+ed98_d_dataini.urlDecode().substr(0, 4);
        data_final   = ed98_d_datafim.urlDecode().substr(8, 2)+"/"+ed98_d_datafim.urlDecode().substr(5, 2)+
                       "/"+ed98_d_datafim.urlDecode().substr(0, 4);
        competencia  = "<b>PERIÓDICA</b> Data Inicial: <b>"+data_inicial+"</b> Data Final: <b>"+data_final+"</b>";

      }

      sHtml = '<b>Último registro:</b> Tipo de Competência: '+competencia;
      sHtml += '<input type="hidden" name="ult_mes" id="ult_mes" value="'+ed98_i_mes.urlDecode()+'" size="10">';
      sHtml += '<input type="hidden" name="ult_ano" id="ult_ano" value="'+ed98_i_ano.urlDecode()+'" size="10">';
      sHtml += '<input type="hidden" name="ult_dtini" id="ult_dtini" value="'+ed98_d_dataini.urlDecode()+'" size="10">';
      sHtml += '<input type="hidden" name="ult_dtfim" id="ult_dtfim" value="'+ed98_d_datafim.urlDecode()+'" size="10">';
      $('ultimo_registro').innerHTML  = sHtml;
      $('tipo_comp').style.visibility = "visible";
      $('ed98_c_tipocomp').value      = ed98_c_tipocomp.urlDecode();
      js_competencia(ed98_c_tipocomp.urlDecode());
    }
  }
}

function js_competencia(valor) {

  if (valor == "") {

    $('comp_mensal').style.visibility  = "hidden";
    $('comp_periodo').style.visibility = "hidden";
    $('db_opcao').disabled             = true;
    js_limpatudo();

  } else {

    if (valor == "M") {

      var sAction = 'PesquisaProxMensal';
      var url     = 'edu1_efetividaderhRPC.php';
      parametros  = 'ult_mes='+$('ult_mes').value+'&ult_ano='+$('ult_ano').value+'&ult_dtfim='+$('ult_dtfim').value;
      var oAjax   = new Ajax.Request(url, {method    : 'post',
                                          parameters: parametros+'&sAction='+sAction,
                                          onComplete: js_retornoPesquisaProxMensal
                                         }
                                    );

    }

    if (valor == "P") {

      var sAction = 'PesquisaProxPeriodo';
      var url     = 'edu1_efetividaderhRPC.php';
      parametros  = 'ult_dtfim='+$('ult_dtfim').value;
      var oAjax   = new Ajax.Request(url, {method    : 'post',
                                          parameters: parametros+'&sAction='+sAction,
                                          onComplete: js_retornoPesquisaProxPeriodo
                                         }
                                    );

    }

  }

}

function js_retornoPesquisaProxMensal(oAjax) {

  var oRetorno                       = eval("("+oAjax.responseText+")");
  arr_retorno                        = oRetorno.split("|");
  dia_ini                            = arr_retorno[2].substr(8, 2);
  mes_ini                            = arr_retorno[2].substr(5, 2);
  ano_ini                            = arr_retorno[2].substr(0, 4);
  dia_fim                            = arr_retorno[3].substr(8, 2);
  mes_fim                            = arr_retorno[3].substr(5, 2);
  ano_fim                            = arr_retorno[3].substr(0, 4);
  $('ed98_i_mes').value              = arr_retorno[0];
  $('ed98_i_ano').value              = arr_retorno[1];
  $('ed98_d_dataini').value          = dia_ini+"/"+mes_ini+"/"+ano_ini;
  $('ed98_d_datafim').value          = dia_fim+"/"+mes_fim+"/"+ano_fim;
  $('ed98_d_dataini_dia').value      = dia_ini;
  $('ed98_d_dataini_mes').value      = mes_ini;
  $('ed98_d_dataini_ano').value      = ano_ini;
  $('ed98_d_datafim_dia').value      = dia_fim;
  $('ed98_d_datafim_mes').value      = mes_fim;
  $('ed98_d_datafim_ano').value      = ano_fim;
  $('comp_mensal').style.visibility  = "visible";
  $('comp_periodo').style.visibility = "hidden";
  $('db_opcao').disabled             = false;

}

function js_retornoPesquisaProxPeriodo(oAjax) {

  var oRetorno                       = eval("("+oAjax.responseText+")");
  dia_ini                            = oRetorno.substr(8, 2);
  mes_ini                            = oRetorno.substr(5, 2);
  ano_ini                            = oRetorno.substr(0, 4);
  $('ed98_i_mes').value              = "";
  $('ed98_i_ano').value              = "";
  $('ed98_d_dataini').value          = dia_ini+"/"+mes_ini+"/"+ano_ini;
  $('ed98_d_datafim').value          = "";
  $('ed98_d_dataini_dia').value      = dia_ini;
  $('ed98_d_dataini_mes').value      = mes_ini;
  $('ed98_d_dataini_ano').value      = ano_ini;
  $('comp_mensal').style.visibility  = "hidden";
  $('comp_periodo').style.visibility = "visible";
  $('db_opcao').disabled             = false;

}

function js_mesano() {

  if ($('ed98_i_ano').value != "" && $('ed98_i_mes').value != "") {

    var sAction = 'PesquisaDatas';
    var url     = 'edu1_efetividaderhRPC.php';
    parametros  = 'mes='+$('ed98_i_mes').value+'&ano='+$('ed98_i_ano').value;
    var oAjax   = new Ajax.Request(url, {method    : 'post',
                                      parameters: parametros+'&sAction='+sAction,
                                      onComplete: js_retornoPesquisaDatas
                                     }
                                );

  } else {

    $('db_opcao').disabled = true;
    js_limpadatas();

  }
}

function js_retornoPesquisaDatas(oAjax) {

  var oRetorno                  = eval("("+oAjax.responseText+")");
  arr_datas                     = oRetorno.split("|");
  dia_ini                       = arr_datas[0].substr(8, 2);
  mes_ini                       = arr_datas[0].substr(5, 2);
  ano_ini                       = arr_datas[0].substr(0, 4);
  dia_fim                       = arr_datas[1].substr(8, 2);
  mes_fim                       = arr_datas[1].substr(5, 2);
  ano_fim                       = arr_datas[1].substr(0, 4);
  $('ed98_d_dataini').value     = dia_ini+"/"+mes_ini+"/"+ano_ini;
  $('ed98_d_datafim').value     = dia_fim+"/"+mes_fim+"/"+ano_fim;
  $('ed98_d_dataini_dia').value = dia_ini;
  $('ed98_d_dataini_mes').value = mes_ini;
  $('ed98_d_dataini_ano').value = ano_ini;
  $('ed98_d_datafim_dia').value = dia_fim;
  $('ed98_d_datafim_mes').value = mes_fim;
  $('ed98_d_datafim_ano').value = ano_fim;
  $('db_opcao').disabled        = false;

}

function js_inclusao() {

  if ($('retorno').value == "") {

    data_inicial = $('ed98_d_dataini').value;
    data_final   = $('ed98_d_datafim').value;
    tipo_comp    = $('ed98_c_tipocomp').value;

    if (data_inicial == "" || data_final == "") {

      alert("Preencha Data Inicial e Data Final!");
      return false;

    }

    dt_ini = data_inicial.substr(6, 4)+""+data_inicial.substr(3, 2)+""+data_inicial.substr(0, 2)
    dt_fim = data_final.substr(6, 4)+""+data_final.substr(3, 2)+""+data_final.substr(0, 2)

    if (parseInt(dt_fim) < parseInt(dt_ini)) {

      alert("Data Inicial maior que a Data Final!");
      return false;

    }

    var sAction = 'VerificaInclusao';
    var url     = 'edu1_efetividaderhRPC.php';
    parametros  = 'dt_ini='+data_inicial+'&dt_fim='+data_final+'&iEscola='+$('ed98_i_escola').value+
                  '&tipo='+$('ed98_c_tipo').value;
    var oAjax   = new Ajax.Request(url, {method    : 'post',
                                        parameters: parametros+'&sAction='+sAction,
                                        onComplete: js_retornoVerificaInclusao
                                       }
                                );
    return false;
  } else {
    return true;
  }
  s
}

function js_retornoVerificaInclusao(oAjax) {

  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno == "0") {

    $('retorno').value = "OK";
    $('db_opcao').click();

  } else {
    alert(oRetorno.urlDecode());
  }

}
</script>