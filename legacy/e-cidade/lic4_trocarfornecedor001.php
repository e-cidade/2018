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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));
$clrotulo = new rotulocampo;
$clrotulo->label("pc10_numero");
$clrotulo->label("l20_codigo");
$clrotulo->label("pc80_codproc");

db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("datagrid.widget.js");
  db_app::load("strings.js");
  db_app::load("grid.style.css");
  db_app::load("estilos.css");
  db_app::load("classes/dbViewAvaliacoes.classe.js");
  db_app::load("widgets/windowAux.widget.js");
  db_app::load("widgets/dbmessageBoard.widget.js");
  db_app::load("dbcomboBox.widget.js");
?>
</head>
<style>

 .link_botao {
   color           : blue;
   cursor          : pointer;
   text-decoration : underline;
   font-weight     : bold;
 }
</style>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc" onload="js_gridItens();">


<center>

<fieldset style="margin-top: 50px; width: 400px;">

<legend><strong>Troca de Fornecedores da Licitação</strong></legend>


<form name="form1" method="post">


<table border='0'>


  <tr>
    <td  align="left" nowrap title="<?=$Tl20_codigo?>">
      <b> <?db_ancora('Licitação:',"js_pesquisa_liclicita(true);",1);?></b>
    </td>
    <td align="left" nowrap>
      <? db_input("l20_codigo",8,$Il20_codigo,true,"text",3,"onchange='js_pesquisa_liclicita(false);'");?>
    </td>
  </tr>


</table>

</form>

</fieldset>

<div style="margin-top: 10px;">
 <!--  <input name="pesquisar" type="button" onclick="js_pesquisaItens();"  value="Pesquisar" style=""> -->
</div>

<div style="width: 800px; margin-top: 10px;" id= "ctnGridItens">



</div>




</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>


</body>
</html>

<script>



function js_troca(nome, cgm, item, obs, valor, solicita, iOrcamento, iPontuacao) {

  var iLicitacao = $F('l20_codigo');

  if (iLicitacao == '') {

    alert('Selecione uma Licitação.');
    return false;
  }

  js_janelatroca(nome, cgm, item, obs, valor, solicita, iOrcamento, iPontuacao);

}


function js_janelatroca(nome, cgm, item, obs, valor, solicita, iOrcamento, iPontuacao){

  var iLarguraJanela = screen.availWidth  - 700;
  var iAlturaJanela  = screen.availHeight - 450;

  windowTroca   = new windowAux( 'windowTroca',
                                 'Trocar Fornecedores do Julgamento de Licitação',
                                 iLarguraJanela,
                                 iAlturaJanela
    );

  var sFormHtml  = "<div id='sTituloWindow'></div> ";

      sFormHtml += "<fieldset><legend><strong>Troca de Fornecedores</strong></legend>";
      sFormHtml += "<table style='margin-top:10px;'>";
      sFormHtml += "  <tr>";
      sFormHtml += "    <td>";
      sFormHtml += "      <strong>Ítem no Orçamento: </strong> ";
      sFormHtml += "    </td>";
      sFormHtml += "    <td>";
      sFormHtml += "      <input type='text' id='iItemOrcamento' value='"+item+"' readonly='readonly' style='width:80px;background-color: #DEB887' />";
      sFormHtml += "    </td>";
      sFormHtml += "  </tr>";

      sFormHtml += "  <tr>";
      sFormHtml += "    <td>";
      sFormHtml += "      <strong>Fornecedor Cotado: </strong> ";
      sFormHtml += "    </td>";
      sFormHtml += "    <td>";
      sFormHtml += "      <input type='text' id='iFornecedorCotado' value='"+cgm+"'  readonly='readonly' style='width:80px; background-color: #DEB887' />";
      sFormHtml += "      <input type='text' id='sFornecedorCotado' value='"+nome+"' readonly='readonly' style='width:400px;background-color: #DEB887' />";
      sFormHtml += "    </td>";
      sFormHtml += "  </tr>";

      sFormHtml += "  <tr>";
      sFormHtml += "    <td>";
      sFormHtml += "      <strong>Observações do Ítem: </strong> ";
      sFormHtml += "    </td>";
      sFormHtml += "    <td>";
      sFormHtml += "      <input type='text' id='sObsItem' value='"+obs+"' readonly='readonly' style='width:484px;background-color: #DEB887' />";
      sFormHtml += "    </td>";
      sFormHtml += "  </tr>";

      sFormHtml += "  <tr>";
      sFormHtml += "    <td>";
      sFormHtml += "      <strong>Valor Cotado: </strong> ";
      sFormHtml += "    </td>";
      sFormHtml += "    <td>";
      sFormHtml += "      <input type='text' id='sValorTroca' value='"+valor+"' readonly='readonly' style='width:80px;background-color: #DEB887' />";
      sFormHtml += "    </td>";
      sFormHtml += "  </tr>";

      sFormHtml += "  <tr>";
      sFormHtml += "    <td>";
      sFormHtml += "      <strong>Fornecedor para Troca: </strong> ";
      sFormHtml += "    </td>";
      sFormHtml += "    <td>";
      sFormHtml += "      <select id='listafornecedor'style='width:484px;' >  </select> ";
      sFormHtml += "    </td>";
      sFormHtml += "  </tr>";


      sFormHtml += "  <tr>";
      sFormHtml += "    <td>";
      sFormHtml += "      <strong>Motivo para Troca do Fornecedor: </strong> ";
      sFormHtml += "    </td>";
      sFormHtml += "    <td>";
      sFormHtml += "      <textarea id='sMotivoTroca' rows='5' cols='63'></textarea> ";
      sFormHtml += "    </td>";
      sFormHtml += "  </tr>";

      sFormHtml += "</table>";
      sFormHtml += "</fieldset>";

      sFormHtml += "<center> ";
      sFormHtml += "<input type='button' value='Trocar Fornecedor' onclick='salvarTroca("+item+", "+cgm+", "+iPontuacao+");' style='margin-top:10px;' />";
      sFormHtml += "</center>";
      sFormHtml += "";

      windowTroca.setContent(sFormHtml);

      //============  MESAGE BORD PARA TITULO da JANELA de ERROS
      var sTextoMessageBoard  = 'Selecione um novo fornecedor. <br> ';
          sTextoMessageBoard += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Omesmo deve estar participando da licitação.';
          messageBoard        = new DBMessageBoard('msgboard1',
                                                   'Dados da Licitação com o atual fornecedor.',
                                                    sTextoMessageBoard,
                                                    $('sTituloWindow'));

         /*funcao para corrigir a exibição do window aux, apos fechar a primeira vez
          */
          windowTroca.setShutDownFunction(function () {
            windowTroca.destroy();
          });


          js_getFornecedores(solicita, iOrcamento, item);

         windowTroca.show();
         messageBoard.show();
}

function salvarTroca(iItem, iFornecedorAntigo, iPontuacao){

  var sMotivo         = $('sMotivoTroca').value;
  var iFornecedorNovo = $('listafornecedor').value;

  if (iFornecedorNovo == "") {
    alert('Fornecedor Novo não Selecionado.');
    return false;
  }

  if (iFornecedorAntigo == iFornecedorNovo) {

    alert('Selecione um fornecedor diferente do atual.');
    return false;
  }

  if (sMotivo == '') {

    alert('Motivo da troca não declarado.');
    return false;
  }

  var sUrlRPC                       = "lic4_licitacao.RPC.php";
  var oParametros                   = new Object();
      oParametros.exec              = "salvarTrocaFornecedor";
      oParametros.iItem             = iItem;
      oParametros.iFornecedorAntigo = iFornecedorAntigo;
      oParametros.iPontuacao        = iPontuacao;
      oParametros.sMotivo           = encodeURIComponent(sMotivo);
      oParametros.iFornecedorNovo   = iFornecedorNovo;

      js_divCarregando("Alterando Dados.",'msgBox');

      var oAjaxLista  = new Ajax.Request(sUrlRPC,
          {method: "post",
           parameters:'json='+Object.toJSON(oParametros),
           onComplete: js_retornoTroca
          });


}

function js_retornoTroca(oAjax){

  js_removeObj('msgBox');

  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == "1") {

    alert(oRetorno.message.urlDecode());
    js_pesquisa_liclicita(true);
    windowTroca.destroy();
  } else {
    alert(oRetorno.message.urlDecode());
  }
}


/** /////////////////////////////////////
 * busca de fornecedores
 */
function js_getFornecedores(iSolicita, iOrcamento, iItem){

  var sUrlRPC                  = "lic4_licitacao.RPC.php";
  var oParametros              = new Object();
      oParametros.exec         = "getFornecedoresItemTroca";
      oParametros.iSolicitacao = iSolicita;
      oParametros.iOrcamento   = iOrcamento;
      oParametros.iItem        = iItem;

      js_divCarregando("Pesquisando Fornecedores",'msgBox');

      var oAjaxLista  = new Ajax.Request(sUrlRPC,
          {method: "post",
           parameters:'json='+Object.toJSON(oParametros),
           onComplete: js_retornoCompletaFornecedores
          });

}
function js_retornoCompletaFornecedores(oAjax) {

  js_removeObj('msgBox');

  var oRetorno = eval("("+oAjax.responseText+")");
  var iFornecedorAtual = $F('iFornecedorCotado');

  if (oRetorno.status == 1) {

    if ( oRetorno.itens.length == 0 ) {

      alert('Nenhum fornecedor encontrado!');
      return false;
    }

    $("listafornecedor").options[0] = new Option("Selecione...", "");
    var iFornecedor = 1;

    oRetorno.itens.each(
                  function (oDado, iInd) {

                      var nome    = oDado.nome.urlDecode();
                      var codigo  = oDado.codigofornecedor;
                      //var codigo  = oDado.codigocgm;

                      // percorremos os fornecedores e só acrescenta na options os que não forem iguais ao atual
                      if (codigo != iFornecedorAtual) {
                        $("listafornecedor").options[iFornecedor] = new Option(nome, codigo);
                        iFornecedor ++;
                      }
                 });
    }

}

//==================================================================================

function js_pesquisaItens() {


  var iLicitacao  = $F('l20_codigo');
  var sUrlRPC     = "lic4_licitacao.RPC.php";
  var msgDiv      = "Aguarde ...";
  var oParametros = new Object();

  if (iLicitacao == '') {

    alert('Selecione uma Licitação.');
    return false;
  }

  oParametros.exec       = 'listaItensTroca';
  oParametros.iLicitacao = iLicitacao;

  js_divCarregando(msgDiv,'msgBox');

  var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                            {method: "post",
                                             parameters:'json='+Object.toJSON(oParametros),
                                             onComplete: js_retornoCompletaItens
                                            });

}

function js_retornoCompletaItens(oAjax) {

  js_removeObj('msgBox');

  var oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.status == 1) {

    oGridItens.clearAll(true);

    if ( oRetorno.dados.length == 0 ) {

      alert('Nenhum registro encontrado!');
      return false;
    }

    oRetorno.dados.each(
                  function (oDado, iInd) {

                      var aRow    = new Array();

                      var nome       = oDado.nome.urlDecode();
                      var cgm        = oDado.cgm;
                      var item       = oDado.item;
                      var obs        = oDado.obs.urlDecode();
                      var solicita   = oDado.solicita;//oDado.pc11_numero;
                      var orcamento  = oDado.iOrcamento;

                      var orcamforne = oDado.iFornecedor;
                      var tipojulg   = oDado.iTipoJulg;
                      var valor      = oDado.valor;
                      var lote       = oDado.lote;
                      var iPontuacao = oDado.pontuacao;

                          aRow[0] = "<span class='link_botao' onclick='js_troca(\""+nome+"\", "+cgm+",  "+item+", \""+obs+"\", \""+valor+"\", "+solicita+", "+orcamento+", "+iPontuacao+")' >Trocar</span>";
                          aRow[1] = oDado.item;
                          aRow[2] = oDado.material.urlDecode();
                          aRow[3] = oDado.fornecedor.urlDecode();
                          aRow[4] = oDado.quantidade;
                          aRow[5] = oDado.valorunitario;
                          oGridItens.addRow(aRow);
                 });

    oGridItens.renderRows();
  }


}


/*
 * Inicia a Montagem do grid (sem os registros)
 *
 */
function js_gridItens() {

 oGridItens = new DBGrid('Itens');
 oGridItens.nameInstance = 'oGridItens';
 oGridItens.setCellWidth(new Array( '10%',
                                    '10%',
                                    '20%',
                                    '30%',
                                    '10%',
                                    '10%'
                                  ));

 oGridItens.setCellAlign(new Array( 'center'  ,
                                    'center'  ,
                                    'left'  ,
                                    'left',
                                    'right'  ,
                                    'right'
                                   ));


 oGridItens.setHeader(new Array( 'Trocar'     ,
                                 'Item'       ,
                                 'Material'   ,
                                 'Fornecedor' ,
                                 'Quant.'     ,
                                 'Valor Unit.'
                               ));

 oGridItens.setHeight(300);
 oGridItens.show($('ctnGridItens'));
 oGridItens.clearAll(true);

}


function js_pesquisa_liclicita(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_liclicita','func_liclicitacancjulg.php?funcao_js=parent.js_mostraliclicita1|l20_codigo','Pesquisa',true);
  }else{
     if(document.form1.l20_codigo.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_liclicita','func_liclicitacancjulg.php?pesquisa_chave='+document.form1.l20_codigo.value+'&funcao_js=parent.js_mostraliclicita','Pesquisa',false);
     }else{
       document.form1.l20_codigo.value = '';
     }
  }
}
function js_mostraliclicita(chave,erro){
  document.form1.l20_codigo.value = chave;
  if(erro==true){
    document.form1.l20_codigo.value = '';
    document.form1.l20_codigo.focus();
  }

  oGridItens.clearAll(true);
  js_pesquisaItens();
}
function js_mostraliclicita1(chave1){
   document.form1.l20_codigo.value = chave1;
   db_iframe_liclicita.hide();
   oGridItens.clearAll(true);

   js_pesquisaItens();
}



js_pesquisa_liclicita(true);
</script>
