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

include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
$escola = db_getsession("DB_coddepto");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load("scripts.js, prototype.js, strings.js, arrays.js, windowAux.widget.js, dbmessageBoard.widget.js,
              dbcomboBox.widget.js, dbtextField.widget.js, webseller.js");

db_app::load("estilos.css");
?>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
<form name="form1" method="post" action="">
<fieldset style="width: 870px;" class="container">
  <legend>Alunos da Rede Municipal</legend>
  <fieldset class="separator">
    <legend>Filtros</legend>
    <table class="form-container" >
      <tr>
        <td class="bold">Período de Matrícula: </td>
        <td>
         <?php db_inputdata('dDataMatriculaInicial','','','',true,'text',1,"","","",""); ?>&nbsp;
        </td>
        <td class="bold">&nbsp;até&nbsp;</td>
        <td><?php db_inputdata('dDataMatriculaFinal','','','',true,'text',1," ","","",""); ?></td>
        <td class="field-size9">
      </tr>
      <tr>
        <td title="" nowrap="nowrap" class="bold">Escolas : </td>
        <td nowrap="nowrap" id="ctnCboEscola" colspan="4"></td>
      </tr>
      <tr>
        <td title="" nowrap="nowrap" class="bold">
          <b>Ensino : </b>
        </td>
        <td nowrap="nowrap" id="ctnCboEnsino" colspan="4"></td>
      </tr>
      <tr>
        <td title="" nowrap="nowrap" class="bold">Etapa :</td>
        <td nowrap="nowrap" id="ctnCboEtapa" colspan="4"></td>
      </tr>
    </table>
  </fieldset>

  <!-- **************************************************
         Seleção dos campos que apareceção no relatório
       ************************************************** -->
  <div id='campos' style="float:left; width:400px; ">
    <fieldset class="separator">
      <legend>Selecione os campos para aparecerem no relatório. </legend>
      <table class="form-container" >
        <tr>
          <td class="bold">Campos</td>
          <td class="bold">Alinhamento</td>
        </tr>
        <tr>
          <td nowrap="nowrap">
            <input type="checkbox" name="campos" value="ed47_i_codigo" onclick="VerificaTamanho(0);" checked disabled> Código Aluno
            <input type="hidden" name="cabecalho" value="Código"><br>
          </td>
          <td >
            <select name="alinhamento">
              <option value="L">ESQUERDO</option>
              <option value="C" selected>CENTRALIZADO</option>
              <option value="R">DIREITO</option>
            </select>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap">
            <input type="checkbox" name="campos" value="ed47_v_nome" onclick="VerificaTamanho(1);" checked disabled> Nome do Aluno
            <input type="hidden" name="cabecalho" value="Nome do Aluno"><br>
          </td>
          <td>
            <select name="alinhamento">
              <option value="L" selected>ESQUERDO</option>
              <option value="C">CENTRALIZADO</option>
              <option value="R">DIREITO</option>
            </select>
          </td>
        </tr>
        <tr style="display: none;">
          <td nowrap="nowrap">
            <input type="checkbox" name="campos" value="ed82_i_codigo" onclick="VerificaTamanho(2);" checked> Escola
            <input type="hidden" name="cabecalho" value="Escola"><br>
          </td>
          <td>
            <select name="alinhamento">
              <option value="L">ESQUERDO</option>
              <option value="C" selected>CENTRALIZADO</option>
              <option value="R">DIREITO</option>
            </select>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap">
            <input type="checkbox" name="campos" value="ed10_i_codigo" onclick="VerificaTamanho(3);" checked> Curso
            <input type="hidden" name="cabecalho" value="Curso"><br>
          </td>
          <td>
            <select name="alinhamento">
              <option value="L">ESQUERDO</option>
              <option value="C" selected>CENTRALIZADO</option>
              <option value="R">DIREITO</option>
            </select>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap">
            <input type="checkbox" name="campos" value="ed11_i_codigo" onclick="VerificaTamanho(4);" checked> Etapa
            <input type="hidden" name="cabecalho" value="Etapa"><br>
          </td>
          <td>
           <select name="alinhamento">
             <option value="L">ESQUERDO</option>
             <option value="C" selected>CENTRALIZADO</option>
             <option value="R">DIREITO</option>
           </select>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap">
            <input type="checkbox" name="campos" value="ed60_d_datamatricula" onclick="VerificaTamanho(5);" checked> Data Matrícula
            <input type="hidden" name="cabecalho" value="Dt. Matric"><br>
          </td>
          <td>
            <select name="alinhamento">
              <option value="L">ESQUERDO</option>
              <option value="C" selected>CENTRALIZADO</option>
              <option value="R">DIREITO</option>
            </select>
          </td>
        </tr>
      </table>
    </fieldset>
  </div>

  <!-- **************************************************************************
         Seleção da Orientação da Página e Ordenação dos campos selecionados
       ************************************************************************** -->
  <div style="float:right; width:400px;">

    <fieldset class="separator">
      <legend>Orientação e Ordenação</legend>
        <table class="form-container" >
          <tr>
            <td class='bold'>Orientação:</td>
            <td colspan="2">
             <select name="orientacao" style="width:200px" onchange="js_limite(this.value);">
              <option value="P" selected>RETRATO</option>
              <option value="L">PAISAGEM</option>
             </select>
             <input type="hidden" size="3" name="marcados" readonly>
             <span style="display:none;" id="limite">192</span>
            </td>
          </tr>
          <tr>
            <td class='bold'>Tamanho da Fonte:</td>
            <td colspan="2">
              <select name="tamfonte" style="width:200px">
                <option value="6">6</option>
                <option value="7" selected>7</option>
                <option value="8">8</option>
                <option value="9">9</option>
              </select>
            </td>
          </tr>
          <tr>
            <td class='bold'>Ordem dos campos <br>no relatório:</td>
            <td >
             <select name="camposordenados" id="camposordenados" size="6" >
             </select>
            </td>
            <td style="text-align: left;">
             <br>
             <img style="cursor:hand" onClick="js_sobe();return false;" src="skins/img.php?file=Controles/seta_up.png" />
             <br/>
             <img style="cursor:hand" onClick="js_desce()" src="skins/img.php?file=Controles/seta_down.png" />
             <br>
            </td>
          </tr>
        </table>
    </fieldset>
  </div>
</fieldset>
</form>
<input name="pesquisar" type="button" id="pesquisar" value="Processar" onclick="js_pesquisa();">
</center>
</form>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>

sUrlRpc = "edu4_escola.RPC.php";

init = function () {

  oCboEscola   = new DBComboBox("cboEscola", "oCboEscola",null, "100%");
  oCboEscola.addItem("0", "Todos");
  oCboEscola.addEvent("onChange", "js_pesquisarEnsino()");
  oCboEscola.show($('ctnCboEscola'));

  oCboEnsino = new DBComboBox("cboEnsino", "oCboEnsino", null, "100%");
  oCboEnsino.addItem("0", "Todos");
  oCboEnsino.addEvent("onChange", "js_pesquisarEtapas()");
  oCboEnsino.show($('ctnCboEnsino'));

  oCboEtapa = new DBComboBox("cboEtapa", "oCboEtapa", null, "100%");
  oCboEtapa.addItem("0", "Todos");
  oCboEtapa.show($('ctnCboEtapa'));


  var oParametros          = new Object();
  oParametros.exec         = 'getEscola';
  oParametros.filtraModulo = true;

  js_divCarregando('Aguarde, pesquisando Escolas...<br>Esse procedimento pode levar algum tempo.', 'msgBox')
  var oAjax = new Ajax.Request(sUrlRpc ,
                                         {
                                           method:'post',
                                           parameters: 'json='+Object.toJSON(oParametros),
                                           onComplete: js_retornoPreencheEscolas
                                         }
                                      );

}

//Insere as escolas na respectiva combo.
js_retornoPreencheEscolas = function (oAjax) {

 js_removeObj('msgBox');
 var oRetorno = eval("("+oAjax.responseText+")");

   oCboEscola.clearItens();
   oCboEscola.addItem("0", "Todos");
   oRetorno.itens.each(function(oEscola, iSeq) {
      oCboEscola.addItem(oEscola.codigo_escola, oEscola.nome_escola.urlDecode());
   });

 js_pesquisarEnsino();

}

//Busca os cursos
js_pesquisarEnsino = function() {

  oCboEnsino.clearItens();
  oCboEnsino.addItem("0", "Todos");
  oCboEtapa.clearItens();
  oCboEtapa.addItem("0", "Todos");

  js_divCarregando('Aguarde, pesquisando ensino', 'msgBox');
  var oParametros               = new Object();
      oParametros.exec          = "PesquisaCurso";
      oParametros.iCodigoEscola = oCboEscola.getValue();
  var oAjax = new Ajax.Request(sUrlRpc ,
                               {
                                 method:'post',
                                 parameters: 'json='+Object.toJSON(oParametros),
                                 onComplete: js_retornoPesquisarEnsino
                               });
};

//Insere os cursos na respectiva combo
function js_retornoPesquisarEnsino(oResponse) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oResponse.responseText+")");
  oRetorno.aResultCursoEscola.each(function(oEnsino, iSeq) {
    oCboEnsino.addItem(oEnsino.codigo_curso, oEnsino.nome_curso.urlDecode());
  });

  js_pesquisarEtapas();
}

//Busca as etapas
js_pesquisarEtapas = function() {

  oCboEtapa.clearItens();
  oCboEtapa.addItem("0", "Todos");

  js_divCarregando('Aguarde, pesquisando etapa', 'msgBox');
  var oParametros               = new Object();
      oParametros.exec          = "getEtapas";
      oParametros.iEscola = oCboEscola.getValue();
      oParametros.iCurso  = oCboEnsino.getValue();
  var oAjax = new Ajax.Request(sUrlRpc ,
                               {
                                 method:'post',
                                 parameters: 'json='+Object.toJSON(oParametros),
                                 onComplete: js_retornoPesquisarEtapa
                               });
};

//Insere as etapas na respectiva combo.
function js_retornoPesquisarEtapa(oResponse) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oResponse.responseText+")");
  oRetorno.aResultado.each(function(oEtapa, iSeq) {
    oCboEtapa.addItem(oEtapa.ed11_i_codigo, oEtapa.ed11_c_descr.urlDecode());
  });
}


init();



function VerificaTamanho(atual) {

  F          = document.form1;
  jamarcados = 0;
  limite     = parseInt(document.getElementById("limite").innerHTML);
  if (jamarcados > limite) {

    alert("Limite máximo de "+limite+" pixels ultrapassado!");
    document.form1.campos[atual].checked = false;
  } else {

    document.form1.marcados.value = jamarcados;
    if(document.form1.campos[atual].checked==true){
      F.elements['camposordenados'].options[F.elements['camposordenados'].options.length] = new Option(F.cabecalho[atual].value,F.campos[atual].value);
    } else {

      for (i = 0; i < document.form1.camposordenados.length; i++) {

        if (document.form1.camposordenados.options[i].value == document.form1.campos[atual].value) {
          document.form1.camposordenados.options[i] = null;
        }
      }
    }
  }
}
VerificaTamanho(0);
VerificaTamanho(1);
VerificaTamanho(3);
VerificaTamanho(4);
VerificaTamanho(5);

function js_limite(valor) {

  if (valor == "P") {
    document.getElementById("limite").innerHTML = 195;
  } else {
    document.getElementById("limite").innerHTML = 280;
  }
  if (parseInt(document.getElementById("limite").innerHTML) < document.form1.marcados.value) {

    alert("Campos já selecionados ultrapassam o limite de 195 pixels.\nDesmarque alguns campos para usar a orientação RETRATO");
    document.getElementById("limite").innerHTML = 280;
    document.form1.orientacao.options[1].selected = true;
  }
}

function js_sobe() {

  var F = document.getElementById("camposordenados");
  if (F.selectedIndex != -1 && F.selectedIndex > 0) {

    var SI       = F.selectedIndex - 1;
    var auxText  = F.options[SI].text;
    var auxValue = F.options[SI].value;

    F.options[SI]          = new Option(F.options[SI + 1].text,F.options[SI + 1].value);
    F.options[SI + 1]      = new Option(auxText,auxValue);
    F.options[SI].selected = true;
  }
}

function js_desce() {
  var F = document.getElementById("camposordenados");
  if (F.selectedIndex != -1 && F.selectedIndex < (F.length - 1)) {

    var SI       = F.selectedIndex + 1;
    var auxText  = F.options[SI].text;
    var auxValue = F.options[SI].value;

    F.options[SI]          = new Option(F.options[SI - 1].text,F.options[SI - 1].value);
    F.options[SI - 1]      = new Option(auxText,auxValue);
    F.options[SI].selected = true;
  }
}

function js_pesquisa() {

  campos      = "";
  cabecalho   = "";
  alinhamento = "";
  sep         = "";
  sep1        = "";
  contador    = 0;

  var sMsgConfirm  = 'Você informou "Todos" para o filtro "Escolas".\nEmitir o relatório desta forma, consumirá ';
      sMsgConfirm += 'muito processamento no servidor, podendo haver lentidão em todo sistema. ';
      sMsgConfirm += '\nDeseja emitir o relatório?';
  if (document.form1.cboEscola.value == 0 && ! confirm(sMsgConfirm) ) {
    return false;
  }

  for (i = 0; i < document.form1.camposordenados.length; i++) {

    for (t = 0; t < document.form1.campos.length; t++) {

      if (document.form1.camposordenados.options[i].value == document.form1.campos[t].value) {

        campos      += sep+document.form1.campos[t].value;
        cabecalho   += sep1+document.form1.cabecalho[t].value;
        alinhamento += sep1+document.form1.alinhamento[t].value;
        sep          = ",";
        sep1         = "|";
        contador++;
      }
    }
  }

  if (contador == 0) {

   alert("Selecione algum campo para processar!");
   return false;
  }

  if ( document.form1.dDataMatriculaInicial.value != '' &&  document.form1.dDataMatriculaFinal.value != '' &&
      js_comparadata(document.form1.dDataMatriculaInicial.value, document.form1.dDataMatriculaFinal.value, '>') ) {

    alert("Data Inicial não pode ser maior que a Data Final.");
    return false;
  }

  jan = window.open('edu2_alunosredemunicipal002.php?dDataMatriculaInicial='+document.form1.dDataMatriculaInicial.value
                                                  +'&dDataMatriculaFinal='+document.form1.dDataMatriculaFinal.value
                                                  +'&iEscola='+document.form1.cboEscola.value
                                                  +'&iEnsino='+document.form1.cboEnsino.value
                                                  +'&iEtapa='+document.form1.cboEtapa.value
                                                  +'&orientacao='+document.form1.orientacao.value
                                                  +'&alinhamento='+alinhamento
                                                  +'&campos='+campos
                                                  +'&cabecalho='+cabecalho
                                                  +'&tamfonte='+document.form1.tamfonte.value,
                    '','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>