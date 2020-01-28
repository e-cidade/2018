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

require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_edu_relatmodel_classe.php"));
require_once(modification("libs/db_utils.php"));

$escola          = db_getsession("DB_coddepto");
$clEduRelatmodel = new cl_edu_relatmodel();

$oRotulo = new rotulocampo();
$oRotulo->label("ed20_i_codigo");
$oRotulo->label("z01_numcgm");
$oRotulo->label("z01_nome");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
    db_app::load("scripts.js, prototype.js, strings.js, arrays.js, windowAux.widget.js, datagrid.widget.js,
                  dbmessageBoard.widget.js, dbcomboBox.widget.js, dbtextField.widget.js, webseller.js,
                  DBVisualizadorImpressaoTexto.js, DBFormCache.js, DBFormSelectCache.js, DBFormCheckBoxCache.js");
    db_app::load("estilos.css, grid.style.css, dbVisualizadorImpressaoTexto.style.css");
    ?>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">

  .legend-hr {
    border:none;
    border-top: outset 1px #000;
  }
  .bold {
    font-weight: bold;
  }
</style>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
<div style="margin-top: 25px;">
  <form name="form1" method="post">
    <?php
      if (db_getsession("DB_modulo") == 1100747) {
        MsgAviso(db_getsession("DB_coddepto"),"escola");
      }
    ?>
    <fieldset style="width:500px">
      <legend><b>Relatório Ata de Resultados Finais</b></legend>
      <fieldset class='legend-hr'>
        <legend class='bold'>Filtros</legend>
        <table align="left">
        <tr>
            <td nowrap = "nowrap" style = "width: 150px;">
              <b>Escola:</b>
            </td>
            <td nowrap="nowrap" id="ctnCboEscola"></td>
          </tr>
          <tr>
            <td nowrap = "nowrap" style = "width: 150px;">
              <b>Calendário:</b>
            </td>
            <td nowrap="nowrap" id="ctnCboCalendario"></td>
          </tr>
          <tr title="Para selecionar mais de uma turma mantenha pressionada a tecla CTRL e clique sobre o nome das turmas.">
            <td nowrap = "nowrap" colspan="2">
              <fieldset>
                <legend class="bold">Turma(s)</legend>
                <div id="ctnCboTurma" style="width: 100%;"> </div>
              </fieldset>
            </td>
          </tr>
        </table>
      </fieldset>
      <fieldset class='legend-hr'>
        <legend class='bold'>Opções de Visualização</legend>
        <table align="left">
          <tr>
            <td nowrap = "nowrap" style = "width: 150px;"><b>Modelo:</b></td>
            <td nowrap = "nowrap">
              <select name="modelo" id="modelo" style="width: 330px;" onChange="js_mudautilizacao(this.value);">
                <option value=''></option>
                <optgroup label="Cabeçalho Padrão">
                  <option value='1'>Sem assinatura dos regentes.</option>
                  <option value='2'>Com assinatura dos regentes</option>
                </optgroup>
                <optgroup label="Configurado pelo Cliente">
                  <option value='3'>Sem assinatura dos regentes</option>
                  <option value='4'>Com assinatura dos regentes</option>
                </optgroup>
              </select>
            </td>
          </tr>
          <tr>
            <td nowrap = "nowrap" style = "width: 150px;"><b>Tipo do Modelo:</b></td>
            <td nowrap = "nowrap" >
              <?
              $sSqlDadosEduRelModel = $clEduRelatmodel->sql_query("",
                                                                   "ed217_i_codigo, ed217_c_nome, ed217_i_tipomodelo",
                                                                   "ed217_c_nome",
                                                                   " ed217_i_relatorio = 3"
                                                                  );
              $rsDadosEduRelModel   = $clEduRelatmodel->sql_record($sSqlDadosEduRelModel);

              ?>
              <select name="ed217_i_codigo" id="ed217_i_codigo" style="width: 330px;" >
                <option value=''></option>
                <option value='m1'>Modelo Padrão</option>
                <?if ($clEduRelatmodel->numrows == 0) {?>
                    <option value=''>Nenhum modelo cadastrado</option>
                <?} else {
                    for ($x = 0; $x < $clEduRelatmodel->numrows; $x++) {

                      $oDadosRelatModel  = db_utils::fieldsMemory($rsDadosEduRelModel,$x);
                      $iCodigo           = $oDadosRelatModel->ed217_i_codigo;
                      $sNome             = $oDadosRelatModel->ed217_c_nome;
                      $sModelo           = "Modelo {$oDadosRelatModel->ed217_i_tipomodelo}";
                      echo "<option value='$iCodigo'>$sNome - $sModelo</option>";
                    }
                  }
                ?>
              </select>
            </td>
          </tr>
          <tr>
            <td nowrap = "nowrap" style = "width: 150px;"><b>Brasão:</b></td>
            <td nowrap = "nowrap" >
              <select name="brasao" id= "brasao" style="width: 330px;">
                <option value='b1'>Sim</option>
                <option value='b2'>Não</option>
              </select>
            </td>
          </tr>
          <tr>
            <td nowrap = "nowrap" style = "width: 150px;"><b>Diretor:</b></td>
            <td nowrap = "nowrap" >
              <?

                $oDaoEscolaDiretor = db_utils::getdao('escoladiretor');

                $sCamposDiretor    = " 'DIRETOR' as funcao, ";
  	            $sCamposDiretor   .= "          case when ed20_i_tiposervidor = 1 then ";
  	            $sCamposDiretor   .= "                  cgmrh.z01_nome ";
  	            $sCamposDiretor   .= "               else cgmcgm.z01_nome ";
  	            $sCamposDiretor   .= "            end as nome,";
  	            $sCamposDiretor   .= " ed83_c_descr||' n°: '||ed05_c_numero::varchar as descricao,'D' as tipo";
  	            $sWhereDiretor     = " ed254_i_escola = ".$escola." AND ed254_c_tipo = 'A' AND ed01_i_funcaoadmin = 2 ";
  	            $sSqlDiretor       = $oDaoEscolaDiretor->sql_query_resultadofinal("", $sCamposDiretor, "", $sWhereDiretor);

  	            $rsDiretor         = $oDaoEscolaDiretor->sql_record($sSqlDiretor);
  	            $iLinhasDiretor    = $oDaoEscolaDiretor->numrows;

              ?>
              <select name="diretor" style="width:330px;">
                <option value="">Selecione o Diretor(a)</option>
                <?
                  for ($iCont = 0; $iCont < $iLinhasDiretor; $iCont++) {

                    $oDadosDiretor = db_utils::fieldsmemory($rsDiretor, $iCont);

                    $sValor     = $oDadosDiretor->funcao."|".$oDadosDiretor->nome."|".$oDadosDiretor->descricao;
                    $sTexto     = $oDadosDiretor->funcao." - ".$oDadosDiretor->nome;
                    $sTexto    .= ($oDadosDiretor->descricao != "" ? " ($oDadosDiretor->descricao)" : "");
                    echo "<option value='$sValor'>$sTexto</option>";

                  }
                ?>
              </select>
            </td>
          </tr>
          <tr>
            <td nowrap = "nowrap" style = "width: 150px;"><b>Secretário:</b></td>
            <td>
              <?
                $oDaoRechumanoAtiv = db_utils::getdao('rechumanoativ');

                $sCamposSec        = " DISTINCT ed01_c_descr as funcao, ";
          	    $sCamposSec       .= "          case when ed20_i_tiposervidor = 1 then ";
          	    $sCamposSec       .= "                  cgmrh.z01_nome ";
          	    $sCamposSec       .= "               else cgmcgm.z01_nome ";
          	    $sCamposSec       .= "            end as nome,";
          	    $sCamposSec       .= " ed83_c_descr||' n°: '||ed05_c_numero::varchar as descricao,'O' as tipo";
          	    $sWhereSec         = " ed75_i_escola = ".$escola." AND ed01_i_funcaoadmin = 3 ";
          	    $sSqlSec           = $oDaoRechumanoAtiv->sql_query_resultadofinal("", $sCamposSec, "", $sWhereSec);
          	    $rsSecretario      = $oDaoRechumanoAtiv->sql_record($sSqlSec);
          	    $iLinhasSecretario = $oDaoRechumanoAtiv->numrows;
              ?>
              <select name="secretario" style="width:330px;">
                <option value="">Selecione o Secretário(a)</option>
                <?
                  for ($iCont = 0; $iCont < $iLinhasSecretario; $iCont++) {

                    $oDadosSec  = db_utils::fieldsmemory($rsSecretario, $iCont);

                    $sValor     = $oDadosSec->funcao."|".$oDadosSec->nome."|".$oDadosSec->descricao;
                    $sTexto     = $oDadosSec->funcao." - ".$oDadosSec->nome;
                    $sTexto    .= ($oDadosSec->descricao != "" ? " ($oDadosSec->descricao)" : "");
                    echo "<option value='$sValor'>$sTexto</option>";

                  }
                ?>
              </select>
            </td>
         </tr>
         <tr>
           <td nowrap = "nowrap" style = "width: 150px;"> <b>Ordenação:</b></td>
           <td>
             <select id="ordenacao" name="ordenacao" style="width:330px;">
               <option value="1">Conforme diário de classe</option>
               <option value="2">Sequencial conforme ordem alfabética</option>
               <option value="3">Alfabética com sequencial do diário de classe</option>
             </select>
           </td>
         </tr>
         <tr title="Este filtro só será aplicado se a turma possuir cálculo de frequência no procedimento de avaliação.">
           <td nowrap = "nowrap" style = "width: 150px;"><b>Frequência:</b></td>
           <td>
             <select id="frequencia" name="frequencia" style="width:330px;">
               <option value="1">Não</option>
               <option value="2">Porcentagem da Frequência</option>
               <option value="3">Número de Faltas</option>
               <option value="4">Número de Presenças</option>
             </select>
           </td>
         </tr>
         <tr>
           <td><b>Exibir Trocas de Turma:</b></td>
           <td>
             <select id='trocaTurma' name='trocaTurma' style="width:100%;">
               <option value="1" selected="selected">Não</option>
               <option value="2">Sim</option>
             </select>
           </td>
         </tr>
         <tr>
           <td><b>Imprimir Nome do Regente:</b></td>
           <td>
             <select id='imprimirNomeRegente' name='imprimirNomeRegente' style='width:100%'>
               <option value="1" selected="selected">Não</option>
               <option value="2" >Sim</option>
             </select>
           </td>
         </tr>
          <tr>
            <td>
              <?
                db_ancora("<b>Assinatura Adicional: </b>", "js_pesquisaRecHumano(true);", 1);
              ?>
            </td>
            <td>
              <?
                db_input("ed20_i_codigo", 6, $Ied20_i_codigo, true, "text", 1, "onChange='js_pesquisaRecHumano(false);'");
                db_input("z01_numcgm", 6, $Iz01_numcgm, true, "hidden", 3);
                db_input("z01_nome", 34, $Iz01_nome, true, "text", 3);
              ?>
            </td>
          </tr>
          <tr>
            <td style="width: 150px"><b>Atividades: </b></td>
            <td id='ctnAtividades'></td>
          </tr>
          <tr>
            <td nowrap = "nowrap" colspan="2" style="text-align: center">
              <input id='transfer' name="transfer" type="checkbox" value="">
              <label for='transfer'><b>Mostrar informações de Troca de Turma nas observações da ata.</b></label>
            </td>
          </tr>
       </table>
     </fieldset>
   </fieldset>
   <div>
     <input name="pesquisar" type="button" id="pesquisar" value="Processar" onclick="js_pesquisa();" disabled>
   </div>
</form>
</div>
</center>
<?db_menu(db_getsession("DB_id_usuario"),
          db_getsession("DB_modulo"),
          db_getsession("DB_anousu"),
          db_getsession("DB_instit")
         );
?>
</body>
</html>
<script type="text/javascript">

sUrlRpc = "edu4_escola.RPC.php";

var oDBFormCache = new DBFormCache('oDBFormCache', 'edu2_ataresultadofinal001.php');
oDBFormCache.setElements(new Array($('modelo'),
                                   $('ordenacao'),
                                   $('frequencia'),
                                   $('trocaTurma'),
                                   $('transfer'),
                                   $('imprimirNomeRegente')
                                  ));
oDBFormCache.load();

oCboEscola = new DBComboBox("cboEscola", "oCboEscola", null, "330px");
oCboEscola.addEvent("onChange", "js_pesquisarCalendario()");
oCboEscola.show($('ctnCboEscola'));

oCboCalendario = new DBComboBox("cboCalendario", "oCboCalendario", null, "330px");
oCboCalendario.addItem("", "Selecione");
oCboCalendario.addEvent("onChange", "js_pesquisarTurmas()");
oCboCalendario.show($('ctnCboCalendario'));

oCboTurma = new DBComboBox("cboTurma", "oCboTurma", null, "100%", '10');
oCboTurma.setMultiple(true);
oCboTurma.addEvent("onChange", "js_botao(this.value)");
oCboTurma.show($('ctnCboTurma'));

oCboAtividades = new DBComboBox("cboAtividades", "oCboAtividades", null, "330px");
oCboAtividades.addItem("", "");
oCboAtividades.setDisable(true);
oCboAtividades.show($('ctnAtividades'));

js_pesquisaEscola();

function js_pesquisaEscola() {

  js_divCarregando("Aguarde, pesquisando as escolas", "msgBox");

  var oParametro          = new Object();
  oParametro.exec         = 'getEscola';
  oParametro.filtraModulo = true;

  var oAjax = new Ajax.Request(sUrlRpc,
                               {
                                 method:     'post',
                                 parameters: 'json='+Object.toJSON(oParametro),
                                 onComplete: js_retornaPesquisaEscola
                               }
                              );
}

function js_retornaPesquisaEscola(oResponse) {

  oCboEscola.clearItens();
  oCboEscola.addItem("", "Selecione");
  oCboCalendario.clearItens();

  var oRetorno = eval('('+oResponse.responseText+')');
  js_removeObj('msgBox');
  oRetorno.itens.each(function(oEscola, iSeq) {

    oCboEscola.addItem(oEscola.codigo_escola, oEscola.nome_escola.urlDecode());
  });

  if (oRetorno.itens.length == 1) {

    oCboEscola.setValue(oRetorno.itens[0].codigo_escola);
    js_pesquisarCalendario();
  }
}

function js_pesquisarCalendario() {

  oCboCalendario.clearItens();
  oCboCalendario.addItem("", "Selecione");
  oCboTurma.clearItens();

  js_divCarregando('Aguarde, pesquisando calendarios', 'msgBox');

  var oParametros               = new Object();
      oParametros.exec          = "PesquisaCalendarioEncerrado";
      oParametros.escola        = oCboEscola.getValue();

  var oAjax = new Ajax.Request(sUrlRpc ,
                               {
                                 method:'post',
                                 parameters: 'json='+Object.toJSON(oParametros),
                                 onComplete: js_retornoPesquisarCalendario
                               });
}

function js_retornoPesquisarCalendario(oResponse) {

  if (oCboEscola.getValue() == '') {
    js_botao('');
  }

  js_removeObj('msgBox');
  var oRetorno = eval("("+oResponse.responseText+")");
  oRetorno.aResult.each(function(oCalendario, iSeq) {
    oCboCalendario.addItem(oCalendario.ed52_i_codigo, oCalendario.ed52_c_descr.urlDecode());
  });

  if (oRetorno.aResult.length == 1) {

    oCboCalendario.setValue(oRetorno.aResult[0].ed52_i_codigo);
    js_pesquisarTurmas();
  }
}

function js_pesquisarTurmas() {

  js_botao('');
  oCboTurma.clearItens();
  if (oCboCalendario.getValue() == "") {
    return false;
  }

  js_divCarregando('Aguarde, pesquisando turmas', 'msgBox');
  var oParametros                       = new Object();
      oParametros.exec                  = "getTurmas";
      oParametros.lEncerrada            = "true";
      oParametros.lListarTurmasComEtapa = "true";
      oParametros.iCalendario           = oCboCalendario.getValue();

  var oAjax = new Ajax.Request(sUrlRpc ,
                             {
                               method:'post',
                               parameters: 'json='+Object.toJSON(oParametros),
                               onComplete: js_retornoGetTurmas
                             });
}

function js_retornoGetTurmas(oResponse) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oResponse.responseText+")");
  oRetorno.aTurmas.each(function(oTurma, iSeq) {
    oCboTurma.addItem(oTurma.codigo_turma,
                      oTurma.nome_turma.urlDecode()+" - "+oTurma.nome_etapa.urlDecode(),
                      null,
                      [{
                        'nome':'etapa',
                        'valor':oTurma.codigo_etapa
                       }
                      ]
                     );
  });

  if (oRetorno.aTurmas.length == 1) {

  	js_botao(this.value);
    oCboTurma.setValue(oRetorno.aTurmas[0].codigo_turma);
  }
}

/**
 * Pesquisamos os recursos humanos vinculados a escola
 */
function js_pesquisaRecHumano(lMostra) {

  if (lMostra) {

    js_OpenJanelaIframe('CurrentWindow.corpo',
                        'db_iframe_rechumano',
                        'func_rechumanoescolanovo.php?funcao_js=parent.js_mostraRecHumano|ed20_i_codigo|z01_nome|z01_numcgm',
                        'Pesquisa Recurso Humano',
                        true
                       );
  } else if ($F('ed20_i_codigo') != '') {

    js_OpenJanelaIframe('CurrentWindow.corpo',
                        'db_iframe_rechumano',
                        'func_rechumanoescolanovo.php?funcao_js=parent.js_mostraRecHumano1&pesquisa_chave='+$F('ed20_i_codigo'),
                        'Pesquisa Recurso Humano',
                        false
                       );
  } else {

    $('ed20_i_codigo').value = '';
    $('z01_nome').value      = '';
    $('z01_numcgm').value    = '';
    oCboAtividades.clearItens();
    oCboAtividades.setDisable(true);
  }
}

function js_mostraRecHumano() {

  $('ed20_i_codigo').value = arguments[0];
  $('z01_nome').value      = arguments[1];
  $('z01_numcgm').value    = arguments[2];
  db_iframe_rechumano.hide();
  js_atividadesDocente();
}

function js_mostraRecHumano1() {

  $('z01_nome').value   = arguments[0];
  $('z01_numcgm').value = arguments[1];

  if (arguments[1] == true) {

    $('ed20_i_codigo').value = '';
    $('z01_nome').value      = arguments[0];
    $('z01_numcgm').value    = '';
    oCboAtividades.setDisable(true);
  } else {
    js_atividadesDocente();
  }
}

/**
 * Buscamos as atividades do docente na escola
 */
function js_atividadesDocente() {

   var oParametro     = new Object();
   oParametro.exec    = 'buscaAtividadesServidor';
   oParametro.iNumCgm = $F('z01_numcgm');

   js_divCarregando("Aguarde, carregando as atividades do funcionário.", "msgBox");
   var oAjax = new Ajax.Request(
                                'edu_educacaobase.RPC.php',
                                {
                                  method: 'post',
                                  parameters: 'json='+Object.toJSON(oParametro),
                                  onComplete: js_retornaAtividadesDocente
                                }
                               );
}

function js_retornaAtividadesDocente(oResponse) {

  oCboAtividades.setEnable(true);
  oCboAtividades.clearItens();
  oCboAtividades.addItem("", "");
  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  if (oRetorno.aAtividades.length > 0) {

    oRetorno.aAtividades.each(function(oLinha, iSeq) {

      oCboAtividades.addItem(oLinha.iCodigo, oLinha.sDescricao.urlDecode());
      if (oRetorno.aAtividades.length == 1) {
        oCboAtividades.setValue(oLinha.iCodigo);
      }
    });
  }

}

function js_mudautilizacao(valor) {

  $('imprimirNomeRegente').disabled = false;
  document.form1.ed217_i_codigo.value = "";
  if (valor == 1 || valor == 2) {

    document.form1.brasao.disabled    = true;
    $('imprimirNomeRegente').disabled = true;
    for (i = 0; i < document.form1.ed217_i_codigo.length; i++) {

  	  if (i > 1) {
  	    document.form1.ed217_i_codigo[i].disabled = true;
  	  } else {
        document.form1.ed217_i_codigo[i].disabled = false;
      }
    }

  } else if (valor == 3 || valor == 4) {

    document.form1.brasao.disabled = false;
    for (i = 0; i < document.form1.ed217_i_codigo.length; i++) {

  	  if (i > 1) {
  		document.form1.ed217_i_codigo[i].disabled = false;
  	  } else {
  		document.form1.ed217_i_codigo[i].disabled = true;
  	  }
  	}
  } else {

  	document.form1.brasao.disabled = false;
  	for (i = 0; i < document.form1.ed217_i_codigo.length; i++) {
      document.form1.ed217_i_codigo[i].disabled = false;
    }
  }

  $('ed217_i_codigo').disabled = false;

  if ($('modelo').value == '') {
    $('ed217_i_codigo').disabled = true;
  }
}

function js_pesquisa() {

  var aTurmas = new Array();

  for (i = 0; i < document.form1.cboTurma.length; i++) {

    if (document.form1.cboTurma.options[i].selected == true) {

      var oTurma = document.form1.cboTurma.options[i];
      aTurmas.push({"turma":oTurma.value, "etapa":oTurma.getAttribute("etapa")}) ;
    }
  }
  if (document.form1.transfer.checked == true) {
    transfer = "yes";
  } else {
    transfer = "no";
  }
  if (document.form1.ed217_i_codigo.value == "") {

    alert("Informe o Tipo do Modelo!");
    return false;

  }

  if ($('ed20_i_codigo').value != '' && oCboAtividades.getValue() == '') {

    alert('Selecione a Atividade do Docente');
    return false;
  }

  oDBFormCache.save();
  var sUrl = "";

  sUrl = "edu2_ataresultadofinal002.php";
  jan = window.open(sUrl+'?transfer='+transfer+
	 	               '&modelo='+$F('modelo')+
                    '&turmas='+Object.toJSON(aTurmas)+
                    '&tipovar='+document.form1.ed217_i_codigo.value+
                    '&brasao='+document.form1.brasao.value+
                    '&diretor='+encodeURIComponent(tagString(document.form1.diretor.value))+
                    '&secretario='+ encodeURIComponent(tagString(document.form1.secretario.value))+
                    '&ordenacao='+document.form1.ordenacao.value+
                    '&frequencia='+document.form1.frequencia.value+
                    '&imprimirNomeRegente='+$F('imprimirNomeRegente')+
                    '&iRegente='+$F('ed20_i_codigo')+
                    '&iAtividade='+oCboAtividades.getValue()+
                    '&trocaTurma='+$F('trocaTurma'),'',
                    'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

function js_botao(valor) {

  if (valor != "") {
    document.form1.pesquisar.disabled = false;
  } else {
    document.form1.pesquisar.disabled = true;
  }
}

js_mudautilizacao($('modelo').value);
</script>