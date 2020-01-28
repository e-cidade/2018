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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("libs/db_utils.php");
require_once("classes/db_db_depart_classe.php");

$oDaoBensBaixa = db_utils::getDao("bensbaix");
$oDaoBensBaixa->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('t51_descr');
$clrotulo->label('t52_bem');
$clrotulo->label('t52_descr');
$clrotulo->label('t52_depart');
$clrotulo->label("descrdepto");
$db_opcao  = 1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
  db_app::load("scripts.js, prototype.js, strings.js, estilos.css, widgets/windowAux.widget.js");
  db_app::load('widgets/dbmessageBoard.widget.js,
                datagrid.widget.js,
                grid.style.css,
                widgets/dbtextField.widget.js,
                dbtextFieldData.widget.js,
                widgets/datagrid/plugins/DBHint.plugin.js');
  $dtAtual = date('d/m/Y', db_getsession('DB_datausu'));
?>

<style type="text/css">

  #ctnDivisao {
    width: 540px;;
  }

  #ctnDadosAdicionais{
    width : 100%;
    height: 50px;
  }

  #viewBens{

    z-index: 0 !important ;
  }


  .table_header .checkbox {

    width: 10px !important;
  }

  .link {
    text-decoration:underline;
    cursor: pointer;
  }


</style>


</head>
<body bgcolor="#CCCCCC" >


  <div class="container">

    <fieldset style="width: 700px; margin-top: 50px;">
      <legend>Baixa de Bens por Lote</legend>

      <table style="width: 100%;">

        <tr>
            <td title="<?=$Tt52_depart?>"> <? db_ancora(@$Lt52_depart,"js_pesquisa_depart(true);",1);?>  </td>
            <td>
              <?
               db_input("t52_depart",10,$It52_depart,true,"text",4,"onchange='js_pesquisa_depart(false);'");
               db_input("descrdepto",60,$Idescrdepto,true,"text",3);
              ?>
            </td>
        </tr>

        <tr>
          <td><strong>Divisão:</strong></td>
          <td>
            <select id='ctnDivisao'>
              <option value=''>Selecione...</option>
            </select>
          </td>
        </tr>

        <tr>
          <td>
            <?php
              db_ancora("<strong>Classificação:</strong>", "js_abreLookupClassificacaoInicial(true);", 1);
            ?>
          </td>
          <td>
            <?php
              db_input("t64_classInicial", 10, false, true, "text", 1, "onchange='js_abreLookupClassificacaoInicial(false);'");

              db_ancora("<strong>Até:</strong>", "js_abreLookupClassificacaoFinal(true);", 1);

              db_input("t64_classFinal", 10, false, true, "text", 1, "onchange='js_abreLookupClassificacaoFinal(false);'");

              // deixamos por enquanto hidden a descricao da class.
              // caso o cliente queira, é só organizar o html....  ;)
              db_input("t64_descrInicial", 60, false, true, "hidden", 3);
              db_input("t64_descrFinal", 60, false, true, "hidden", 3);
            ?>
          </td>
        </tr>


        <tr>
          <td title="<?=$Tt52_bem?>"> <? db_ancora(@$Lt52_bem,"js_pesquisa_bemInicial(true);",1);?>  </td>
          <td>
            <?
               db_input("t52_bemInicial", 10,$It52_bem,true,"text",4,"onchange='js_pesquisa_bemInicial(false);'");
               db_ancora("<strong>Até:</strong>", "js_pesquisa_bemFinal(true);", 1);
               db_input("t52_bemFinal", 10,$It52_bem,true,"text",4,"onchange='js_pesquisa_bemFinal(false);'");

               db_input("t52_descrInicial",60,$It52_descr,true,"hidden",3);
               db_input("t52_descrFinal",60,$It52_descr,true,"hidden",3);
            ?>
          </td>
        </tr>

          <tr>
            <td><? db_ancora("Placa: ", "js_pesquisaPlacaInicial(true);",1); ?></td>
            <td>
              <?
                db_input('placaini',10, true, 1, 'text', 1, "onchange='js_pesquisaPlacaInicial(false)'");
                db_ancora('Até:', "js_pesquisaPlacaFinal(true);",1);
                db_input('placafim', 10, true, 1, 'text', 1, "onchange='js_pesquisaPlacaFinal(false)'");
              ?>
            </td>
          </tr>


      </table>

    </fieldset>

    <div style="margin-top: 10px;">
      <input type='button' id='btnFiltrar' value='Filtrar' />
    </div>
  </div>

</body>
</html>
<?PHP
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>


<script>

const URL_MENSAGEM = 'patrimonial.patrimonio.pat4_baixarbemLote001.';
var   sUrlRPC      = "pat1_bensnovo.RPC.php";

$('btnFiltrar').observe('click', function(){
  js_montaView();
});


function consultaBem(  iCodigoBem  ){

  var uRlOpen = 'pat1_consbens002.php?t52_ident=&t52_bem=' + iCodigoBem;
  js_OpenJanelaIframe('top.corpo','db_iframe_consultaBem',uRlOpen ,'Consulta de Bem',true);
}


function getBensSelecionados(){

  var aListaCheckbox = oGridBens.getSelection();
  var aListaBens     = [];

  aListaCheckbox.each( function ( aRow,  iLinha) {
    aListaBens.push(aRow[1].trim());
  });
  return  aListaBens;
}

function js_processar(){

  var aBens   = getBensSelecionados();
  var msgDiv  = _M( URL_MENSAGEM + "processandoBaixa");
  var dBaixa  = $F("oTxtData");
  var iMotivo = $F("oTxtCodMotivo");
  var sObs    = $F("ctnDadosAdicionais");

  // VALIDACOES ANTES DE PROCESSAR
  if (dBaixa == '') {
    alert( _M(URL_MENSAGEM + "dataBranco")); return false;
  }
  if (iMotivo == '') {
    alert( _M(URL_MENSAGEM + "motivoBranco"));return false;
  }
  if ( sObs == '') {
    alert( _M( URL_MENSAGEM + "dadosAdicionaisBranco")); return false;
  }
  if (aBens.length <= 0) {
    alert( _M( URL_MENSAGEM + "nenhumBemSelecionado" ) ); return false;
  }


  var oParametros             = new Object();
      oParametros.exec        = 'baixarBem';
      oParametros.aBens       = aBens;
      oParametros.iMotivo     = iMotivo;
      oParametros.dtBaixa     = dBaixa;
      oParametros.sObservacao = encodeURIComponent(tagString(sObs));

  if (confirm(_M(URL_MENSAGEM  + "confirmaBaixa"))) {

    js_divCarregando(msgDiv, 'msgBox');
    new Ajax.Request(sUrlRPC,
        {method     : "post",
         parameters : 'json=' + Object.toJSON(oParametros),
         onComplete : js_retornoProcessar
        });
  };
}

function js_retornoProcessar(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.status == 2 ) {

    alert(oRetorno.message.urlDecode());
    return false;
  }
  alert( _M( URL_MENSAGEM + "processadoComSucesso") );
  js_buscaDadosBem();

}

function js_buscaDadosBem(){

  var iCodigoDepartamento          = $F("t52_depart");
  var iCodigoDivisao               = $F("ctnDivisao");
  var iCodigoBemInicial            = $F("t52_bemInicial");
  var iCodigoBemFinal              = $F("t52_bemFinal");
  var iCodigoClassificacaoInicial  = $F("t64_classInicial");
  var iCodigoClassificacaoFinal    = $F("t64_classFinal");
  var iPlacaInicial                = $F("placaini");
  var iPlacaFinal                  = $F("placafim");

  var oParametros                             = {};
      oParametros.exec                        = 'getBens';
      oParametros.iCodigoDepartamento         = iCodigoDepartamento        ;
      oParametros.iCodigoDivisao              = iCodigoDivisao             ;
      oParametros.iCodigoBemInicial           = iCodigoBemInicial          ;
      oParametros.iCodigoBemFinal             = iCodigoBemFinal            ;
      oParametros.iCodigoClassificacaoInicial = iCodigoClassificacaoInicial;
      oParametros.iCodigoClassificacaoFinal   = iCodigoClassificacaoFinal  ;
      oParametros.iPlacaInicial               = iPlacaInicial;
      oParametros.iPlacaFinal                 = iPlacaFinal  ;


  var msgDiv = _M( URL_MENSAGEM + "buscarBens");

      js_divCarregando(msgDiv, 'msgBox');

  new Ajax.Request(sUrlRPC,
                  {method     : "post",
                   parameters : 'json=' + Object.toJSON(oParametros),
                   onComplete : js_retornoBuscaBem
                  });
}

function js_retornoBuscaBem(oAjax){

  js_removeObj('msgBox');

  var oRetorno = eval("("+oAjax.responseText+")");

  oTxtData.setReadOnly(oRetorno.lPossuiIntegracaoPatrimonial);

  oGridBens.clearAll(true);
  if (oRetorno.status == 2 ) {

    alert(oRetorno.message.urlDecode());
    $('windowviewBens_btnclose').click();
    return false;
  }

  oRetorno.aDadosRetorno.each( function(oDados, iIndice){

    var aRow     = [];
        aRow[0]  = '<a href="#" onclick="consultaBem('+oDados.iCodigoBem+')" >' + oDados.iCodigoBem + '</a>';
        aRow[1]  = oDados.sPlaca         ;
        aRow[2]  = "&nbsp;" + oDados.sDescricao.urlDecode()     ;
        aRow[3]  = "&nbsp;" + oDados.sClassificacao.urlDecode() ;
        aRow[4]  = "&nbsp;" + oDados.sDepartamento.urlDecode()  ;

    oGridBens.addRow(aRow);
  });

  oGridBens.renderRows();

  oRetorno.aDadosRetorno.each(function (oDado, iLinha) {
    oGridBens.setHint(iLinha, 3, oDado.sDescricao.urlDecode());
    oGridBens.setHint(iLinha, 4, oDado.sClassificacao.urlDecode());
    oGridBens.setHint(iLinha, 5, oDado.sDepartamento.urlDecode());

  });
}

function js_criaGridBens(){

  oGridBens = new DBGrid('IBens');
  oGridBens.nameInstance = 'oGridBens';
  oGridBens.setCheckbox(0);
  oGridBens.setCellWidth([ '50px' ,
                           '50px' ,
                           '250px' ,
                           '200px',
                           '200px'
                           ]);

  oGridBens.setCellAlign(['right'  ,
                           'right'  ,
                           'left'  ,
                           'left'    ,
                           'left'
                           ]);


  oGridBens.setHeader(['Código',
                        'Placa',
                        'Descrição',
                        'Classificação',
                        'Departamento'
                        ]);

  oGridBens.setHeight(300);
  oGridBens.show($('ctnBens'));
  oGridBens.clearAll(true);
  js_buscaDadosBem();

}

function js_montaView() {

	var iLarguraJanela = screen.availWidth  - 100;
	var iAlturaJanela  = screen.availHeight - 200;
	var viewBens       = new windowAux('viewBens',
                                     'Lista de Bens',
                                     iLarguraJanela,
                                     iAlturaJanela
                                     );
	var sConteudowinAux  = '<div style="margin-left:10px;">                                                             ';
      sConteudowinAux += '  <fieldset style="margin-top:5px; width:97%;">                                             ';
      sConteudowinAux += '    <legend><b>Dados da Baixa</b></legend>                                                  ';
      sConteudowinAux += '    <table style="width:100%" >                                                             ';


      sConteudowinAux += '      <tr>                                                                                  ';
      sConteudowinAux += '        <td width="10%"nowrap="nowrap"><strong>Data:</strong></td>                          ';
      sConteudowinAux += '        <td nowrap="nowrap"> <span id="inputData"></span></td>                              ';
      sConteudowinAux += '      </tr>                                                                                 ';

	    sConteudowinAux += '      <tr>                                                                                  ';
	    sConteudowinAux += '        <td nowrap="nowrap">                                                                ';
			sConteudowinAux += '          <a href="#" class="dbancora" style="text-decoration:underline;"                   ';
			sConteudowinAux += '             onclick="js_pesquisaMotivo(true);"><strong>Motivo:</strong></a>                ';
	    sConteudowinAux += '        </td>                                                                               ';
	    sConteudowinAux += '        <td>                                                                                ';
	    sConteudowinAux += '          <span id="inputcodigomotivo"></span>                                              ';
	    sConteudowinAux += '          <span id="inputdescricaomotivo"></span>                                           ';
	    sConteudowinAux += '        </td>                                                                               ';
      sConteudowinAux += '      </tr>                                                                                 ';

      sConteudowinAux += '      <tr>                                                                                  ';
      sConteudowinAux += '        <td colspan="2">                                                                    ';
      sConteudowinAux += '          <fieldset style="width:98%; margin-top:10px;">                                    ';
      sConteudowinAux += '            <legend>Dados Adicionais</legend>                                               ';
      sConteudowinAux += '            <textarea id="ctnDadosAdicionais"> </textarea>                                  ';
      sConteudowinAux += '          </fieldset>                                                                       ';
      sConteudowinAux += '        </td>                                                                               ';
      sConteudowinAux += '      </tr>                                                                                 ';

      sConteudowinAux += '      <tr>                                                                                  ';
      sConteudowinAux += '        <td colspan="2">                                                                    ';
      sConteudowinAux += '          <fieldset style="margin-top:10px;">                                               ';
      sConteudowinAux += '            <legend>Lista de Bens Encontrados</legend>                                      ';
      sConteudowinAux += '              <div id="ctnBens"></div>                                                      ';
      sConteudowinAux += '          </fieldset>                                                                       ';
      sConteudowinAux += '        </td>                                                                               ';
      sConteudowinAux += '      </tr>                                                                                 ';

      sConteudowinAux += '    </table>                                                                                ';
      sConteudowinAux += '  </fieldset>                                                                               ';

      sConteudowinAux += '  <div style="margin-top:10px">                                                             ';
      sConteudowinAux += '    <center>                                                                                ';
      sConteudowinAux += '      <input type="button" id="processar" onclick="js_processar();" value="Processar" />    ';
      sConteudowinAux += '    </center>                                                                               ';
      sConteudowinAux += '  </div>                                                                                    ';

      sConteudowinAux += '</div>                                                                                      ';
      viewBens.setContent(sConteudowinAux);

      oTxtData = new DBTextFieldData('oTxtData','oTxtData', '<?php echo $dtAtual ?>');
      oTxtData.show($('inputData'));

      oTxtCodMotivo = new DBTextField('oTxtCodMotivo', 'oTxtCodMotivo', '', 10);
      oTxtCodMotivo.addEvent("onChange", ";js_pesquisaMotivo(false);");
      oTxtCodMotivo.show($('inputcodigomotivo'));
      oTxtCodMotivo.setReadOnly(false);

      oTxtDescrMotivo = new DBTextField('oTxtDescrMotivo', 'oTxtDescrMotivo', '', 60);
      oTxtDescrMotivo.show($('inputdescricaomotivo'));
      oTxtDescrMotivo.setReadOnly(true);

      //    funcao para corrigir a exibição do window aux, apos fechar a primeira vez
      viewBens.setShutDownFunction(function () {
        viewBens.destroy();
      });

      viewBens.show();
      js_criaGridBens();
}

/*
 * função para controlar as opçoes de divisao, dependendo do depto selecionado
 */

function getDivisoes(){

  var iDepartamento = $F('t52_depart');
  var msgDiv        = _M( URL_MENSAGEM + "buscarDivisao" );
  $("ctnDivisao").options.length = 0;
  $("ctnDivisao").options[0] = new Option('Selecione...', '');

  js_divCarregando(msgDiv,'msgBox');

  var oParametros              = new Object();
      oParametros.exec         = 'buscaDivisao';
      oParametros.departamento = iDepartamento;

  new Ajax.Request(sUrlRPC,
                  {method     : "post",
                   parameters : 'json='+Object.toJSON(oParametros),
                   asynchronous : false,
                   onComplete : js_retornogetDivisoes
                  });
}

function js_retornogetDivisoes(oAjax){

  var oRetorno = eval("("+oAjax.responseText+")");
  js_removeObj('msgBox');

  if (oRetorno.status == '2') {

    alert(oRetorno.message.urlDecode());
    return false;
  }
  iDivisoes = 1;
  oRetorno.departamento.each( function( oDados , iIndice ){

    var iDivisao = oDados.t30_codigo;
    var sDivisao = oDados.t30_descr.urlDecode();
    $("ctnDivisao").options[iDivisoes] = new Option(sDivisao, iDivisao);
    iDivisoes++;

  });

}


//=================  PESQUISA MOTIVO
function js_pesquisaMotivo(mostra) {


  if ( mostra == true) {

    js_OpenJanelaIframe('','db_iframe_bensmotbaixa',
                        'func_bensmotbaixa.php?funcao_js=parent.js_mostramotivo1|t51_motivo|t51_descr','Pesquisa Motivo', true);
  }else{

    if ($('oTxtCodMotivo').value != ''){
      js_OpenJanelaIframe('',
                          'db_iframe_bensmotbaixa',
                          'func_bensmotbaixa.php?pesquisa_chave=' + $F('oTxtCodMotivo') +
                          '&funcao_js=parent.js_mostramotivo','Pesquisa Motivo',false);
    }else{
      $('oTxtDescrMotivo').value = '';
    }
  }
}
function js_mostramotivo(chave,erro){

  $('oTxtDescrMotivo').value = chave;

  if ( erro == true){

    $('oTxtCodMotivo').focus();
    $('oTxtCodMotivo').value = '';
  }
}
function js_mostramotivo1(chave1,chave2){

  $('oTxtCodMotivo').value = chave1;
  $('oTxtDescrMotivo').value = chave2;
  db_iframe_bensmotbaixa.hide();
}


/**
 * Abre função de pesquisa das classificações Final
 */
function js_abreLookupClassificacaoFinal(lMostra) {

  if (lMostra == true) {

    var sUrlOpen = "func_clabens.php?funcao_js=parent.js_mostraclabensFinal1|t64_class|t64_descr&analitica=true";
    js_OpenJanelaIframe('top.corpo', 'db_iframe_clabensFinal', sUrlOpen, 'Pesquisa Classificação', true);

  } else {

    testa = new String($("t64_classFinal").value);
     if(testa != '' && testa != 0){
       i = 0;
       for(i = 0;i < $('t64_classFinal').value.length;i++){
         testa = testa.replace('.','');
       }
       js_OpenJanelaIframe('top.corpo','db_iframe_clabensFinal','func_clabens.php?pesquisa_chave='+testa+'&funcao_js=parent.js_mostraclabensFinal&analitica=true','Pesquisa',false);
     }else{
       $('t64_descrFinal').value = '';
     }
  }
}
function js_mostraclabensFinal(chave,erro){

  $('t64_descrFinal').value = chave;
  if( erro == true){

    $('t64_classFinal').value = '';
    $('t64_classFinal').focus();
  }
}
function js_mostraclabensFinal1(chave1,chave2){

  $('t64_classFinal').value = chave1;
  $('t64_descrFinal').value = chave2;
  db_iframe_clabensFinal.hide();
}
/**
 * Abre função de pesquisa das classificações Inicial
 */
function js_abreLookupClassificacaoInicial(lMostra) {

  if (lMostra == true) {

    var sUrlOpen = "func_clabens.php?funcao_js=parent.js_mostraclabensInicial1|t64_class|t64_descr&analitica=true";
    js_OpenJanelaIframe('top.corpo', 'db_iframe_clabensInicial', sUrlOpen, 'Pesquisa Classificação', true);
  } else {

    testa = new String($("t64_classInicial").value);
     if(testa != '' && testa != 0){
       i = 0;
       for(i = 0;i < $('t64_classInicial').value.length;i++){
         testa = testa.replace('.','');
       }
       js_OpenJanelaIframe('top.corpo','db_iframe_clabensInicial','func_clabens.php?pesquisa_chave='+testa+'&funcao_js=parent.js_mostraclabensInicial&analitica=true','Pesquisa',false);
     }else{
       $('t64_descrInicial').value = '';
     }
  }
}
function js_mostraclabensInicial(chave,erro){

  $('t64_descrInicial').value = chave;
  if(erro==true){

    $('t64_classInicial').value = '';
    $('t64_classInicial').focus();
  }
}
function js_mostraclabensInicial1(chave1,chave2){

  $('t64_classInicial').value = chave1;
  $('t64_descrInicial').value = chave2;
  db_iframe_clabensInicial.hide();
}

//------PESQUISA DEPARTAMENTOS--------------------------
function js_pesquisa_depart(mostra){

  if (mostra == true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_depart','func_db_depart.php?funcao_js=parent.js_mostradepart1|coddepto|descrdepto','Pesquisa Departamentos',true);
  } else {

     if ($F("t52_depart") != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_depart','func_db_depart.php?pesquisa_chave=' + $F("t52_depart") + '&funcao_js=parent.js_mostradepart','Pesquisa Departamentos',false);
     }else{
       $("descrdepto").value = '';
     }
  }
}
function js_mostradepart(chave,erro){

  $('descrdepto').value = chave;

  if( erro == true){

    $('t52_depart').focus();
    $('t52_depart').value = '';
  }
  getDivisoes();
}
function js_mostradepart1(chave1,chave2){

  $('t52_depart').value = chave1;
  $('descrdepto').value = chave2;
  db_iframe_depart.hide();
  getDivisoes();
}

//-----------PESQUISA BEM FINAL--------------------
function js_pesquisa_bemFinal(mostra){

  var chaveDepto = '';
  if ($F('t52_depart') != '') {
    chaveDepto = 'chave_depto='+$F('t52_depart')+'&';
  }

  if ( mostra == true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_bensFinal','func_bens.php?' + chaveDepto + 'funcao_js=parent.js_mostrabemFinal1|t52_bem|t52_descr','Pesquisa de Bens',true);
  }else{
     if( $('t52_bemFinal').value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_bensFinal','func_bens.php?pesquisa_chave=' + $('t52_bemFinal').value+'&funcao_js=parent.js_mostrabemFinal','Pesquisa',false);
     }else{
       $('t52_descrFinal').value = '';
     }
  }
}
function js_mostrabemFinal(chave,erro){

  $('t52_descrFinal').value = chave;
  if( erro == true ) {

    $('t52_bemFinal').focus();
    $('t52_bemFinal').value = '';
  }
}
function js_mostrabemFinal1(chave1,chave2){

  $('t52_bemFinal')  .value = chave1;
  $('t52_descrFinal').value = chave2;
  db_iframe_bensFinal.hide();
}

//-----------PESQUISA BEM INICIAL--------------------
function js_pesquisa_bemInicial(mostra){

  var chaveDepto = '';
  if ($F('t52_depart') != '') {
    chaveDepto = 'chave_depto='+$F('t52_depart')+'&';
  }
  if ( mostra == true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_bensInicial','func_bens.php?' + chaveDepto + 'funcao_js=parent.js_mostrabemInicial1|t52_bem|t52_descr','Pesquisa de Bens',true);
  }else{
     if( $('t52_bemInicial').value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_bensInicial','func_bens.php?pesquisa_chave=' + $('t52_bemInicial').value+'&funcao_js=parent.js_mostrabemInicial','Pesquisa',false);
     }else{
       $('t52_descrInicial').value = '';
     }
  }
}
function js_mostrabemInicial(chave,erro){

  $('t52_descrInicial').value = chave;
  if( erro == true ) {

    $('t52_bemInicial').focus();
    $('t52_bemInicial').value = '';
  }
}
function js_mostrabemInicial1(chave1,chave2){

  $('t52_bemInicial')  .value = chave1;
  $('t52_descrInicial').value = chave2;
  db_iframe_bensInicial.hide();
}


/**
 * Funções para a seleção de Placa Inicial
 */
function js_pesquisaPlacaInicial(mostra) {

  if (mostra == true) {

    var sUrlOpenInicial = "func_bens.php?funcao_js=parent.js_preenchePlacaInicial|t52_ident";
    js_OpenJanelaIframe('top.corpo', 'db_iframe_placaini', sUrlOpenInicial, 'Pesquisa', true);
  } else {

    var sUrlOpenInicial = "func_bens.php?funcao_js=parent.js_preenchePlacaInicial|t52_ident";
    js_OpenJanelaIframe('top.corpo', 'db_iframe_placaini', sUrlOpenInicial, 'Pesquisa', false);
  }
}

function js_preenchePlacaInicial(placaInicial) {

  if (placaInicial != '') {
    $('placaini').value = placaInicial;
    db_iframe_placaini.hide();
  }
}


/**
 * Funções para a seleção de Placa Final
 */
function js_pesquisaPlacaFinal(mostra) {

  if (mostra == true) {

    var sUrlOpenFinal = "func_bens.php?funcao_js=parent.js_mostraplacafim1|t52_ident";
    js_OpenJanelaIframe('top.corpo', 'db_iframe_placafim', sUrlOpenFinal, 'Pesquisa', true);
  } else {

     if ($('placafim').value != '') {

       var sUrlOpenFinal = "func_bens.php?pesquisa_chave=" + $('placafim').value+"&lRetornoPlaca=true&funcao_js=parent.js_mostraplacafim";
       js_OpenJanelaIframe('top.corpo', 'db_iframe_placafim', sUrlOpenFinal, 'Pesquisa', false);
     } else {
       $('placafim').value = '';
     }
  }
}

function js_mostraplacafim(chave1, chave2) {

  $('placafim').value = chave1;
  if (chave2 == true) {
    $('placafim').value = '';
  }
  db_iframe_placafim.hide();
}

function js_mostraplacafim1(placaFinal) {

  var placaInicialCompara = $("placaini").value;
  if (new Number(placaInicialCompara) < new Number(placaFinal)) {

    $("placafim").value = placaFinal;
    db_iframe_placafim.hide();
  } else {

    alert (_M("patrimonial.patrimonio.pat2_bensbaix001.informe_placa_final_maior_placa_inicial", {placaInicialCompara: placaInicialCompara}));
    return false;
  }
}
</script>