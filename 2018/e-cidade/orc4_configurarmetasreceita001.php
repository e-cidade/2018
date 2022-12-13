<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
/**
 * @revision $Author: dbricardo.lopes $
 * @version $Revision: 1.17 $
 */
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
$db_opcao = 1;
$clrotulo = new rotulocampo;
$clrotulo->label("o124_descricao");
$clrotulo->label("o124_sequencial");
$clrotulo->label("o15_descr");
$clrotulo->label("o15_codigo");
unset($_SESSION["cronogramabases"]);
unset($_SESSION["cronogramabasestotal"]);
unset($_SESSION["cronogramabasespages"]);
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
     db_app::load("scripts.js, strings.js, arrays.js, prototype.js, widgets/windowAux.widget.js, widgets/messageboard.widget.js");
     db_app::load("datagrid.widget.js, estilos.css, grid.style.css");
    ?>
    <style>
    .incorreto {
                background-color: #FFFFCC;
               }
    .incorreto input {
               }
    .incorreto span {
      color: red;
    }
    </style>
  </head>
<body class="body-default" >
  <div class="container">
    <form name="form1" method="post">
      <fieldset>
        <legend>
          <b>Configurar Metas da Receita</b>
        </Legend>
        <table>
          <tr>
            <td>
              <?
               db_ancora("<b>Perspectiva:</b>","js_pesquisao125_cronogramaperspectiva(true);",$db_opcao);
              ?>
            </td>
            <td>
              <?
              db_input('o124_sequencial',10,$Io124_sequencial,true,'text',
                      $db_opcao," onchange='js_pesquisao125_cronogramaperspectiva(false);'");
              db_input('o124_descricao',40,$Io124_descricao,true,'text',3,'')
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <b>Ano:</b>
            </td>
            <td>
              <?
                db_input('ano',10,0,true,'text',3,'');
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input type="button" value='Processar Metas' id='processameta' onclick="js_processaMeta()">
      <input type="button" value='Edição' id='visualizaMeta' onclick="js_abreMeta()">
      <input type="button" value='Receitas Inconsistentes' id='visualizaReceitas'
              onclick="ja_mostraReceitasNegativas()">
    </form>
  </div>
</center>
<div id='divWndDadosMeta' style='display: none'>
  <fieldset>
    <legend>
      <b>Filtros</b>
    </legend>
  <table>
    <tr>
      <td>
         <b>Estrutural:</b>
      </td>
      <td>
        <?
         db_input('fonte',15,"",true,'text',1);
        ?>
      </td>
     <td>
       <input type='button' value='Pesquisar' onclick="js_getDadosMeta(1, true)">
     </td>
  </table>
  </fieldset>
  <fieldset>
  <table cellpadding="0"  id='tbpai' style='border:2px inset white;background: white' cellspacing="0" width="">
    <tr>
      <td width="30%" ID='LABEL' border=0 valign="top">
        <table id='table' border=0  cellpadding="0"  cellspacing="0" style="table-layout: fixed; width: 300px">
        <thead>
          <tr>
            <th width="200"  class='table_header' id='teste0' style='text-align:right;border-right:0px' colspan="1">
              Receita
            </th>
            <th width="100"  style='text-align:left;' class='table_header' id='teste0' colspan="1">
              &nbsp;
            </th>
          </tr>
          <tr>
             <th class='table_header' style='width:200px' id='teste1s' nowrap="nowrap">
               Descrição
             </th>
             <th class='table_header' style='width:100px' id='teste2s' nowrap="nowrap">
               Valor Orc.
             </th>
          </tr>
          </thead>
          <tbody id='tbodyDescr' style="height: 400px; overflow: hidden; overflow-x:hidden">
            <tr style='height:auto'>
              <td>&nbsp;</td>
            </tr>
          </tbody>
        </table>
      </td>
      <td  valign="top" width="100%">
         <div style='overflow: scroll; overflow-y:hidden; width:100%' id='tableRol'>
          <table border=0 cellpadding="0" cellspacing="0" width="3000">
            <tr>
              <td class='table_header' style="width: 250px"  colspan="2">Jan</td>
              <td class='table_header' style="width: 250px"  colspan="2">Fev</td>
              <td class='table_header' style="width: 250px"  colspan="2">Mar</td>
              <td class='table_header' style="width: 250px"  colspan="2">Abr</td>
              <td class='table_header' style="width: 250px"  colspan="2">Mai</td>
              <td class='table_header' style="width: 250px"  colspan="2">Jun</td>
              <td class='table_header' style="width: 250px"  colspan="2">Jul</td>
              <td class='table_header' style="width: 250px"  colspan="2">Ago</td>
              <td class='table_header' style="width: 250px" colspan="2">Set</td>
              <td class='table_header' style="width: 250px" colspan="2">Out</td>
              <td class='table_header' style="width: 250px" colspan="2">Nov</td>
              <td class='table_header' style="width: 250px" colspan="2">Dez</td>
              <td class='table_header' style="width: 17px" width="17px">&nbsp;</td>
            </tr>
            <tr>
            <?
            for ($i = 1; $i <=12; $i++) {
              echo " <td class='table_header' style='width:50px'><b>%</b></td> ";
              echo " <td class='table_header' style='width:200px'>Valor</td> ";
            }
             ?>
             </tr>
            <tbody style="height: 400px; background-color:white;
                          overflow: scroll; overflow-x:hidden" id='tBodyFields' onscroll="scrollTable(event)">
            </tbody>
          </table>
        </div>
      </td>
    </tr>
    <tr>
      <td colspan="3" style='text-align:left; padding: 0px' class='table_footer' valign="top">
         <input type='button' value='Início'   id='btninicio'>
         <input type='button' value='Anterior' id='btnanterior'>
         <span style='font-weight: bold; border:1px  solid #999999;padding:1px; background-color:white';>
           <input size='1'  type='text'
                 style='border:0px;height:16px;text-align:right;font-weight: bold;'
                 readonly value='' id='paginaAtiva'>/
           <input size='1'  type='text' style='border:0px;height:16px;text-align:left;font-weight: bold;'
                  readonly value='' id='totalDePaginas'>
         </span>
        <input type='button' value='Próximo'  id='btnproximo'>
        <input type='button' value='Último'   id='btnultimo'>
      </td>
    </tr>
  </table>
  </fieldset>
  <center>
 <input type="button" value="Salvar" id='salvar' onclick="js_salvarReceita()">

  </center>
</div>
<div style='position:absolute;top: 200px; left:15px;
            border:1px solid black;
            width:500px;
            padding:3px;
            background-color: #FFFFCC;z-index:10000;
            display:none;' id='ajudaItem'>

</div>
<?php db_menu(); ?>
</body>
</html>
<script type="text/javascript">
lNecessitaSalvar = false;
var lPerspectivaValida = false;

function js_pesquisao125_cronogramaperspectiva(mostra) {

  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo',
                        'db_iframe_cronogramaperspectiva',
                        'func_cronogramaperspectiva.php?tipo=1&funcao_js='+
                        'parent.js_mostracronogramaperspectiva1|o124_sequencial|o124_descricao|o124_ano&aberto=1',
                        'Perspectivas do Cronograma',true);
  }else{
     if(document.form1.o124_sequencial.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo',
                            'db_iframe_cronogramaperspectiva',
                            'func_cronogramaperspectiva.php?tipo=1&pesquisa_chave='+
                            document.form1.o124_sequencial.value+
                            '&funcao_js=parent.js_mostracronogramaperspectiva&aberto=1',
                            'Perspectivas do Cronograma',
                            false);
     }else{

       document.form1.o124_descricao.value = '';
       document.form1.ano.value             = ''
       lPerspectivaValida = false;
     }
  }
}

function js_mostracronogramaperspectiva(chave,erro, ano){

  document.form1.o124_descricao.value = chave;
  document.form1.ano.value = ano;
  lPerspectivaValida = true;

  if(erro==true) {

    document.form1.o124_sequencial.focus();
    document.form1.o124_sequencial.value = '';
    document.form1.ano.value             = '';
    lPerspectivaValida = false;
  }
}

function js_mostracronogramaperspectiva1(chave1,chave2,chave3) {

  document.form1.o124_sequencial.value = chave1;
  document.form1.o124_descricao.value  = chave2;
  document.form1.ano.value             = chave3;
  lPerspectivaValida = true;

  db_iframe_cronogramaperspectiva.hide();
}

function js_processaMeta() {


  if ($F('o124_sequencial') == "") {

    alert('Informe a perspectiva!');
    $('o124_sequencial').focus();
    return false;

  }

  if (!lPerspectivaValida) {
    return false;
  }

  var sMsgUsuario  = "Será calculado as metas de arrecadação para o cronograma das receitas de "+$F('ano')+".\n";
      sMsgUsuario += "Qualquer Edição Manual será perdida.\nClique em OK para Continuar.";
  if (!confirm(sMsgUsuario)) {
    return false;
  }
  var oParametros          = new Object();
  oParametros.exec         = "processarMeta";
  oParametros.iPerspectiva = $F('o124_sequencial');
  js_divCarregando("Aguarde, Processando Metas de arrecadação.<br>Esse procedimento pode levar Algum tempo.","msgBox");
  var oAjax = new Ajax.Request(
                        'orc4_cronogramafinanceiro.RPC.php',
                         {
                          method    : 'post',
                          parameters: 'json='+Object.toJSON(oParametros),
                          onComplete: js_retornoProcessaMeta
                          }
                        );

}

function js_retornoProcessaMeta(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oResponse.responseText+")");
  if (oRetorno.status == 1) {

    if (oRetorno.aReceitasInconsistente.length > 0) {
       var sMsg  = "Durante o processamento das Metas da Receita foi detectado que, em alguns meses, ";
       sMsg     += "o valor das previsões ficou negativo. Isto é normal visto que nos períodos calculados pode ";
       sMsg     += "ter havido mais estornos que arrecadações em algumas receitas. No entanto, não é viável ter ";
       sMsg     += "previsões mensais negativas. Você pode fazer com que o sistema lance estas previsões de maneira ";
       sMsg     += "uniforme (divididas por doze) clicando em 'OK'. ";
       sMsg     += "Caso queira ajustá-las manualmente, clique em 'Cancelar' e posteriormente acesse os registros ";
       sMsg     += "através do botão 'Editar'.";
       if (confirm(sMsg)) {
         ja_mostraReceitasNegativas();
       }
    } else {
      alert('Procedimento ok');
    }
  } else {
    alert(oRetorno.message.urlDecode());
  }
}
var iSizeTable = $('table').getWidth();
iSize = ((document.body.getWidth()-450)-iSizeTable);
$('tableRol').style.width = "100px";
function scrollTable(event) {

   outroscrollTop = event.target.scrollTop;
   $('tbodyDescr').scrollTop = outroscrollTop

};

function js_abreMeta() {
  js_getDadosMeta(1, true);
}

function js_createJanelaMeta() {

  var iSizeTable = $('table').getWidth();
  var iSize = ((document.body.getWidth()-350)-iSizeTable);
  $('tableRol').style.width   = new String(iSize)+"px";
  $('tbpai').style.width      = document.width-50;
  if (!$('wndMetaCalculo')) {

    windowMetaCalculo = new windowAux('wndMetaCalculo', 'Metas de Arrecadação de Receita', 0, 0);
    windowMetaCalculo.setObjectForContent($('divWndDadosMeta'));
    windowMetaCalculo.allowCloseWithEsc(false);

    $("windowwndMetaCalculo_btnclose").stopObserving("click");
    $("windowwndMetaCalculo_btnclose").observe("click",function() {

      if (lNecessitaSalvar) {
        if (!confirm('há Informações não Salvas.\nClique OK para sair sem Salvar.')) {;
          return false;
        } else {
          windowMetaCalculo.hide();
        }
      } else {
        windowMetaCalculo.hide();
      }
    });
  }
  $('tbodyDescr').style.height      = new String((document.body.scrollHeight/1.8))+"px";
  $('tBodyFields').style.height     = new String((document.body.scrollHeight/1.8))+"px";
  $('divWndDadosMeta').style.display='';

}

function js_getDadosMeta(iPagina, lClearSession) {

  if (iPagina == null) {
    iPagina  = 1;
  }
  if ($F('o124_sequencial') == "") {

    alert('Informe a perspectiva!');
    $('o124_sequencial').focus();
    return false;

  }

  if (!lPerspectivaValida) {
    return false;
  }

  if (lClearSession == null) {
    lClearSession = false;
  }
  var oParametros           = new Object();
  oParametros.exec          = "getDados";
  oParametros.iPagina       = iPagina;
  oParametros.iTipo         = 2;
  oParametros.iRecurso      = "";
  oParametros.sEstrutural   = $F('fonte');
  oParametros.lClearSession = lClearSession;
  oParametros.iFetch        = iPagina==1?0:iPagina*100;
  oParametros.iPerspectiva  = $F('o124_sequencial');
  js_divCarregando("Aguarde, Pesquisando Metas de Arrecadação.","msgBox");
  var oAjax = new Ajax.Request(
                        'orc4_cronogramafinanceiro.RPC.php',
                         {
                          method    : 'post',
                          parameters: 'json='+Object.toJSON(oParametros),
                          onComplete: js_retornoGetMeta
                          }
                        );
}

function js_retornoGetMeta(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oResponse.responseText+")");
  if (oRetorno.status == 1) {


     /**
      * Prenchemos os dados das metas
      */
     var sDescricao = "";
     var sCampos    = "";
     for (var i = 0; i < oRetorno.itens.length; i++) {

       var sClassName = 'normal';
       with (oRetorno.itens[i]) {

         aMetas.dados.each(function(aItem, id) {

           if (deducao == 't' && aItem.valor > 0) {
             sClassName = "incorreto";
           } else if (deducao == 'f' && aItem.valor < 0) {
             sClassName = "incorreto";
           }

         });
         var sFontNegrito  = '';
         if (aDesdobramentos.length > 0) {
           sFontNegrito  = "font-weight:bold;";
         }
         sDescrRec          = o57_codfon+"-"+o57_descr.urlDecode();
         var sIndiceReceita = o57_codfon+"_"+o70_concarpeculiar;

         sDescricao += "<tr style='height:1px' class='"+sClassName+"' id='descricaoreceita"+sIndiceReceita+"'>";
         sDescricao += "<td class='linhagrid' nowrap style='height:20px;text-align:left;'>";
         sDescricao += "<div style='overflow:hidden;with:200px;padding:1px;"+sFontNegrito+"'>";
         sDescricao += "<span onmouseover='js_setAjuda(this.innerHTML,true)' onmouseout='js_setAjuda(\"\",false)'>"+o57_fonte;
         sDescricao += " - "+sDescrRec+"- recurso ("+o70_codigo+")</span></div></td>";
         sDescricao += "<td class='linhagrid' style='width:100px;height:20px;text-align:right;"+sFontNegrito+";padding:0px'>";
         sDescricao += js_formatar(o70_valor,"f");
         sDescricao += "</td>";
         sDescricao += "</tr>";
         sCampos    += "<tr style='height:1px' class='"+sClassName+"'  id='valoresreceita"+sIndiceReceita+"'>";

         aMetas.dados.each(function(aItem, id) {

           var sInputDisabled   = '';
           var sColorTdDisabled = "";
           if (new Number(aItem.valormedia) == 0) {

             sInputDisabled   = " disabled ";
             sColorTdDisabled = " color:#BCB1A2;";

           }
           sCampos += "<td class='linhagrid' ";
           sCampos += "style='height:20px;text-align:right;padding:1px; "+sColorTdDisabled+"'";
           sCampos += "id='perc_"+o57_codfon+"_"+aItem.mes+"_"+o70_concarpeculiar+"'>";
           sCampos += js_formatar(aItem.percentual,"f")+"</td>";
           sCampos += "<td class='linhagrid' style='text-align:right;padding:0px'>";
           sCampos += "<input type='text' style='height:99%;width:100%;";
           sCampos += "border:1px solid transparent;text-align:right;"+sFontNegrito;
           if (sInputDisabled != " disabled ") {
             sCampos += "background: transparent'";
           } else {
            sCampos += "'";
           }
           sCampos += " id='valor_"+o57_codfon+"_"+aItem.mes+"_"+o70_concarpeculiar+"'" + sInputDisabled;
           sCampos += " onKeyPress=\"return js_mask(event,'0-9|.|-')\" codigoreceita='"+o57_codfon+"'";
           sCampos += " codigocaracteristica='"+o70_concarpeculiar+"'";
           sCampos += " readonly value='"+js_formatar(aItem.valor,"f")+"'";
           if (!desdobra) {

            sListaItens =  aItem.sequencial;
            if (aDesdobramentos.length > 0) {
              sListaItens = aDesdobramentos.implode(",");
            }
            sListaIndices = index;
            if (aIndices.length > 0) {
              sListaIndices = aIndices.implode(",");
            }

            sCampos += " onkeyDown='js_verifica(this,event, "+desdobra+")'" ;
            var lBold = false;
            if (aDesdobramentos.length > 0) {
              var lBold = true;
            }
            sCampos += " onfocus='js_liberaDigitacao(this)' onblur='js_bloqueiaDigitacao(this,"+lBold+");";
            sCampos += "js_alterarValorMes(["+sListaItens+"], ["+sListaIndices+"],"+aItem.mes+",";
            sCampos += "this.value, "+index+","+lBold+")'";
           }
           sCampos +="></td>";

         })
         sCampos    += "</tr>";
       }

     }
     sDescricao += "<tr style='height:auto'><td>&nbsp</td>";
     sCampos    += "<tr style='height:auto'><td>&nbsp</td>";
     $('tbodyDescr').innerHTML   = sDescricao;
     $('tBodyFields').innerHTML  = sCampos;

     if (!$('wndMetaCalculo')) {
      js_createJanelaMeta();
    }
    windowMetaCalculo.show(25,10);
    $('divWndDadosMeta').style.display='';
    $('btninicio').disabled   = false;
    $('btnanterior').disabled = false;
    $('btnproximo').disabled  = false;
    $('btnultimo').disabled   = false;
    if (oRetorno.pagina == 1) {

      $('btninicio').disabled   = true;
      $('btnanterior').disabled = true;
      $('btnproximo').disabled  = false;
      $('btnultimo').disabled   = false;

    } else if (oRetorno.pagina == oRetorno.totalPaginas) {

      $('btninicio').disabled  = false;
      $('btnanterior').disabled = false;
      $('btnproximo').disabled = true;
      $('btnultimo').disabled  = true;

    } else {

      $('btninicio').disabled   = false;
      $('btnanterior').disabled = false;
      $('btnproximo').disabled  = false;
      $('btnultimo').disabled   = false;
    }

    if (oRetorno.totalPaginas == 1 || oRetorno.totalPaginas == 1) {

      $('btninicio').disabled   = true;
      $('btnanterior').disabled = true;
      $('btnproximo').disabled  = true;
      $('btnultimo').disabled   = true;

    }
    $('btninicio').onclick   = function(){js_getDadosMeta(1)};
    $('btnanterior').onclick = function(){js_getDadosMeta(oRetorno.pagina-1)};
    $('btnproximo').onclick  = function(){js_getDadosMeta(oRetorno.pagina+1)};
    $('btnultimo').onclick   = function(){js_getDadosMeta(oRetorno.totalPaginas)};

    $('paginaAtiva').value    =  oRetorno.pagina;
    $('totalDePaginas').value =  oRetorno.totalPaginas;

  } else {
    alert(oRetorno.message.urlDecode());
  }
}



function js_pesquisac62_codrec(mostra){
   if(mostra==true){
       js_OpenJanelaIframe('CurrentWindow.corpo',
                           'db_iframe_orctiporec',
                           'func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1|o15_codigo|o15_descr',
                           'Pesquisar Recursos',
                           true,
                           22,
                           0,
                          document.width-12,
                          document.body.scrollHeight-35);
   } else {

       if($('o15_codigo').value != ''){
           js_OpenJanelaIframe('CurrentWindow.corpo',
                               'db_iframe_orctiporec',
                               'func_orctiporec.php?pesquisa_chave='+$('o15_codigo').value+
                               '&funcao_js=parent.js_mostraorctiporec',
                               'Pesquisar Recursos',
                               false,
                               22,
                               0,
                               document.width-12,
                               document.body.scrollHeight-30);
       }else{
           $('o15_descr').value = '';
       }
   }
}

/**
 * Libera  o input passado como parametro para a digitacao.
 * é Retirado a mascara do valor e liberado para Edição
 * é Colocado a Variavel nValorObjeto no escopo GLOBAL
 */
function js_liberaDigitacao(object) {

  nValorObjeto        = object.value;
  object.value        = js_strToFloat(object.value).valueOf();
  object.style.border = '1px solid black';
  object.readOnly     = false;
  object.style.fontWeight = "bold";
  object.select();
  js_marcaReceita(object.getAttribute("codigoreceita"), true, object.getAttribute("codigocaracteristica"));

}

/**
 * bloqueia  o input passado como parametro para a digitacao.
 * É colocado  a mascara do valor e bloqueado para Edição
 */
function js_bloqueiaDigitacao(object, lBold) {


  object.readOnly         = true;
  object.style.border     ='0px';
  object.style.fontWeight = "normal";
  if (lBold) {
    object.style.fontWeight = "bold";
  }
  object.value            = js_formatar(object.value,'f');
  js_marcaReceita(object.getAttribute("codigoreceita"), false, object.getAttribute("codigocaracteristica"));


}

/**
 * Verifica se  o usuário cancelou a digitação dos valores.
 * Caso foi cancelado, voltamos ao valor do objeto, e
 * bloqueamos a digitação
 */
function js_verifica(object,event, lBold) {

  var teclaPressionada = event.which;
  if (teclaPressionada == 27) {
      object.value = nValorObjeto;
     js_bloqueiaDigitacao(object, lBold);
     event.preventDefault();
  }
}

function js_mostraorctiporec(chave,erro) {

  $('o15_descr').value = chave;
  if(erro==true) {

     $('o15_codigo').focus();
     $('o15_codigo').value = '';

   }
}

function js_mostraorctiporec1(chave1,chave2) {

    $('o15_codigo').value = chave1;
    $('o15_descr').value = chave2;
    db_iframe_orctiporec.hide();
}

function js_salvarReceita() {

   js_divCarregando("Aguarde, Salvando metas de arrecadação","msgBox");
   var oParametros            = new Object();
   oParametros.exec           = "salvarReceita";
   oParametros.iTipo          = 2;
   var oAjax = new Ajax.Request(
                        'orc4_cronogramafinanceiro.RPC.php',
                         {
                          method    : 'post',
                          parameters: 'json='+Object.toJSON(oParametros),
                          onComplete: js_retornosalvarBaseReceita
                          }
                        );

}

function js_retornosalvarBaseReceita (oResponse) {

  js_removeObj("msgBox");
  js_deveSalvar(false);
  var oRetorno = eval("("+oResponse.responseText+")");
  if (oRetorno.status == 1) {
     alert('Dados Salvos com sucesso!');
  } else {
    alert(oRetorno.message.urlDecode());
  }
}

function js_alterarValorMes(aMeta, aIndiceReceita, iIndiceMes, nValor, iSender, lDesdobra) {

  nValor = js_strToFloat(nValor).valueOf();
  if (nValor == js_strToFloat(nValorObjeto)) {
    return ;
  }
  js_deveSalvar(true);
  var oParametros                = new Object();
  oParametros.exec               = "alterarValorMes";
  oParametros.iPerspectiva       = $F('o124_sequencial');
  oParametros.iIndiceReceita     = aIndiceReceita;
  oParametros.iIndiceMes         = iIndiceMes;
  oParametros.iCodigoMetaCalculo = aMeta;
  oParametros.iTipo              = 2;
  oParametros.valor              = nValor;
  oParametros.iSender            = iSender;
  oParametros.lDesdobra          = lDesdobra;
  js_divCarregando("Aguarde, salvando Valor.","msgBox");

  var oAjax = new Ajax.Request(
                        'orc4_cronogramafinanceiro.RPC.php',
                         {
                          method    : 'post',
                          parameters: 'json='+Object.toJSON(oParametros),
                          onComplete: js_retornoAlterarMes
                          }
                        );


}

function js_retornoAlterarMes (oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oResponse.responseText+")");


  var sCaracteristica = oRetorno.sConcarpeculiar;
  if (sCaracteristica == 'undefined') {
    sCaracteristica = '';
  }
  var sIndice = oRetorno.iCodRec+"_"+oRetorno.iMes+"_"+sCaracteristica;
  if (oRetorno.status == 1) {

    var lMudaClasse = false;
    if (oRetorno.message != "") {

      lMudaClasse = true;
      alert(oRetorno.message.urlDecode());
    }

    if (oRetorno.lDesdobra) {

      $("perc_"+sIndice).innerHTML = js_formatar(oRetorno.nPercentual,"f");
      $("valor_"+sIndice).value    = js_formatar(oRetorno.nValor,"f");

      $("perc_"+oRetorno.iCodRec+"_12_"+sCaracteristica).innerHTML = js_formatar(oRetorno.nPercentualAjuste,"f");
      $("valor_"+oRetorno.iCodRec+"_12_"+sCaracteristica).value    = js_formatar(oRetorno.nValorAjuste,"f");

    }
    for (var i = 0; i < oRetorno.itens.length; i++) {

      with (oRetorno.itens[i]) {

        var sIndice = iCodRec+"_"+oRetorno.iMes+"_"+sConcarpeculiar;
        $("perc_"+sIndice).innerHTML = js_formatar(nPercentual,"f");
        $("valor_"+sIndice).value    = js_formatar(nValor,"f");

        $("perc_"+iCodRec+"_12_"+sConcarpeculiar).innerHTML    = js_formatar(nPercentualAjuste,"f");
        $("valor_"+iCodRec+"_12_"+sConcarpeculiar).value       = js_formatar(nValorAjuste,"f");
        if (lMudaClasse) {

          $('descricaoreceita'+iCodRec+"_"+sConcarpeculiar).className = "incorreto";
          $('valoresreceita'+iCodRec+"_"+sConcarpeculiar).className   = "incorreto";

        } else {

          $('descricaoreceita'+iCodRec+"_"+sConcarpeculiar).className = "normal";
          $('valoresreceita'+iCodRec+"_"+sConcarpeculiar).className   = "normal";

        }
      }
    }
  } else {

    alert(oRetorno.message.urlDecode());
    $("valor_"+sIndice).value = nValorObjeto;
    $("valor_"+sIndice).focus();

  }
}

getElementsByClass = function ( searchClass, domNode, tagName) {

    if (domNode == null) {
      domNode = document;
    }

    if (tagName == null) {
      tagName = '*';
    }

    var el = new Array();
    var tags = domNode.getElementsByTagName(tagName);
    var tcl = " "+searchClass+" ";
    for (i=0,j=0; i<tags.length; i++) {

      var test = " " + tags[i].className + " ";
      if (test.indexOf(tcl) != -1) {
         el[j++] = tags[i];
       }
    }
    return el;
}
/**
 * Controla se as Metas de Arrecação foram salvos
 */

function js_deveSalvar(salvar) {

  lNecessitaSalvar = salvar;
  if (lNecessitaSalvar) {
    $('salvar').disabled = false;
    windowMetaCalculo.setTitle("Metas de Arrecadação de Receita [*]");
  } else {
    $('salvar').disabled = true;
    windowMetaCalculo.setTitle("Metas de Arrecadação de Receita");
  }
}
var sOldClassName = '';
function js_marcaReceita(iCodRec, lMarcar, sCaracteristica) {

  if (lMarcar) {

    sOldClassName = $('descricaoreceita'+iCodRec+"_"+sCaracteristica).className;
    sClassName = "marcado";

  } else {

    if (sOldClassName == '') {
     sOldClassName = "normal";
    }
    var sClassName    = sOldClassName;
  }

  $('descricaoreceita'+iCodRec+"_"+sCaracteristica).className = sClassName;
  $('valoresreceita'+iCodRec+"_"+sCaracteristica).className   = sClassName;

}

$('salvar').disabled = true;

function js_setAjuda(sTexto,lShow) {

  if (lShow) {

    el =  $('tbpai');
    var x = 0;
    var y = el.offsetHeight;
    while (el.offsetParent && el.tagName.toUpperCase() != 'BODY') {

     x += el.offsetLeft;
     y += el.offsetTop;
     el = el.offsetParent;

   }
   x += el.offsetLeft;
   y += el.offsetTop;
   $('ajudaItem').innerHTML     = sTexto;
   $('ajudaItem').style.display = '';
   $('ajudaItem').style.top     = y+10;
   $('ajudaItem').style.left    = x;

  } else {
   $('ajudaItem').style.display = 'none';
  }

}
function ja_mostraReceitasNegativas() {

  if ($F('o124_sequencial') == '') {

    alert('Informe a perspectiva.');
    return false;
  }

  if (!lPerspectivaValida) {
    return false;
  }

  var oParametros               = new Object();
  oParametros.exec              = "getReceitasNegativas";
  oParametros.iPerspectiva      = $F('o124_sequencial');
  oParametros.iRecurso          = '';
  oParametros.iTipo             = 2;
  oParametros.lNegativas        = true;
  js_divCarregando("Aguarde, Pesquisando.","msgBox");
  var oAjax = new Ajax.Request(
                        'orc4_cronogramafinanceiro.RPC.php',
                         {
                          method    : 'post',
                          parameters: 'json='+Object.toJSON(oParametros),
                          onComplete: js_retornoReceitasNegativas
                          }
                        );


}

function js_retornoReceitasNegativas (oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oResponse.responseText+")");
  if (oRetorno.status == 1) {

    windowAjusteCalculo  = new windowAux('wndAjusteCalculo', 'Ajuste de Metas de Despesa', document.body.clientWidth, document.body.clientHeight );
    var sContent         = "<fieldset>";
    sContent            += " <div id='cntGridReceitasNegativas'></div>";
    sContent            += "</fieldset>";
    sContent            += "<center>";
    sContent            += "<input type='button' value='Confirmar' onclick='js_realizaAjusteValores()'>";
    sContent            += "</center>";
    windowAjusteCalculo.setContent(sContent);
    windowAjusteCalculo.allowCloseWithEsc(false);

    $("windowwndAjusteCalculo_btnclose").stopObserving("click");
    $("windowwndAjusteCalculo_btnclose").observe("click",function() {
      windowAjusteCalculo.destroy();
    });
    var oMessageBoard = new messageBoard('msg1',
                                         'Ajuste das metas de Receita',
                                        'Escolha quais receitas serão modificadas',
                                        $('windowwndAjusteCalculo_content'));

    /**
     * Grid so pode ser mostrada apos o elemento existir
     */
    oMessageBoard.show();
    windowAjusteCalculo.show();



    oGridReceitasNegativas              = new DBGrid('gridReceitas');
    oGridReceitasNegativas.nameInstance = "oGridReceitasNegativas";
    oGridReceitasNegativas.setHeight(windowAjusteCalculo.getHeight()/1.6);
    oGridReceitasNegativas.setCheckbox(0);
    oGridReceitasNegativas.setCellWidth( ["5%"    , "45%"      ,"15%"    ,'10%'  , "15%"    ,"10%"   ] );
    oGridReceitasNegativas.setCellAlign( ["right" , "left"     ,"right"  ,"right", "right"  , "right"] );
    oGridReceitasNegativas.setHeader   ( ["Codrec", "Descricao","Jan/Nov","Perc.","Dezembro","Perc"  ] );
    oGridReceitasNegativas.show($('cntGridReceitasNegativas'));
    oGridReceitasNegativas.clearAll(true);
    for (var i = 0; i < oRetorno.itens.length; i++) {

      with ( oRetorno.itens[i]) {

         var sDescricao = o57_fonte.urlDecode()+" - "+o57_descr.urlDecode()
         var aLinha     = new Array();
         aLinha[0]      = o70_codrec;
         aLinha[1]      = sDescricao.substring(0,40);
         aLinha[2]      = js_formatar(valormes,"f");
         aLinha[3]      = js_formatar(percentual,"f");
         aLinha[4]      = js_formatar(valordezembro,"f");
         aLinha[5]      = js_formatar(percentualdezembro,"f");
         oGridReceitasNegativas.addRow(aLinha);
         oGridReceitasNegativas.aRows[i].aCells.each(function(oCell, id) {
            oCell.sStyle += "padding: 0px 1px 0px 1px";
         }
         );
      }
    }

    oGridReceitasNegativas.renderRows();
  }
}

function js_realizaAjusteValores() {

  var aValores = oGridReceitasNegativas.getSelection();
  if (aValores.length == 0) {

    alert('Nenhuma receita selecionada.');
    return false;
  }

  var oParam          = new Object();
  oParam.exec         = "realizaAjusteReceita";
  oParam.aReceitas    = new Array();
  oParam.iPerspectiva = $F('o124_sequencial');

  for (var i = 0; i < aValores.length; i++) {

    var oReceita = new Object();
    oReceita.iCodigo           = aValores[i][0];
    oReceita.nValorMes         = js_strToFloat(aValores[i][3]).valueOf();
    oReceita.nPercentual       = js_strToFloat(aValores[i][4]).valueOf();
    oReceita.nAjusteDezembro   = js_strToFloat(aValores[i][5]).valueOf();
    oReceita.nPercentualAjuste = js_strToFloat(aValores[i][6]).valueOf();
    oParam.aReceitas.push(oReceita);

  }
  js_divCarregando("Aguarde, Realizando Ajuste.","msgBox");
  var oAjax = new Ajax.Request(
                        'orc4_cronogramafinanceiro.RPC.php',
                         {
                          method    : 'post',
                          parameters: 'json='+Object.toJSON(oParam),
                          onComplete: js_retornoAjusteReceitas
                          }
                        );
}

function js_retornoAjusteReceitas(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {

     alert('Dados Salvos com sucesso!');
     windowAjusteCalculo.destroy();
  } else {
    alert(oRetorno.message.urlDecode());
  }
}
</script>