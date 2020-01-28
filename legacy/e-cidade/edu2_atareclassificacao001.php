<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");

define("ARQUIVO_MSG_EDU2_ATARECLASSIFICACAO001", "educacao.escola.edu2_atareclassificacao001.");
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
<?php

  db_app::load("scripts.js, prototype.js, strings.js");
  db_app::load("estilos.css");
  db_app::load("classes/educacao/escola/ListaEscola.classe.js");
  db_app::load("classes/educacao/escola/ListaCalendario.classe.js");
  db_app::load("classes/educacao/escola/ListaEtapa.classe.js");
  ?>
  <script type="text/javascript" >
    require_once('scripts/widgets/DBToggleList.widget.js');
  </script>
</head>
<body bgcolor="#cccccc" style='margin-top: 30px'>
  <?php
    /**
     * Validamos se estamos no módulo escola
     */
    if (db_getsession("DB_modulo") == 1100747) {
    	MsgAviso(db_getsession("DB_coddepto"),"escola");
    }
  ?>
  <div class='container'>
    <form id='formPadrao' action="">
      <fieldset>
        <legend>Emissão de Ata de Reclassificação:</legend>
        <table class="form-container">
          <tr>
            <td nowrap="nowrap" class='bold'>Modelo:</td>
            <td nowrap="nowrap" >
              <select id='listaModeloAta'>
              </select>
            </td>
          </tr>
          <tr>
            <td nowrap="nowrap" class='bold'>Escola:</td>
            <td nowrap="nowrap" id='listaEscola'></td>
          </tr>
          <tr>
            <td nowrap="nowrap" class='bold'>Ano Letivo:</td>
            <td nowrap="nowrap" id='listaCalendario'></td>
          </tr>
          <tr>
            <td nowrap="nowrap" class = 'bold'>Emissor:</td>
            <td nowrap="nowrap" >
              <select id='emissor'>
                <option value="">Selecione Emissor</option>
              </select>
            </td>
          </tr>
        </table>
        <fieldset class="separator" style="width: 500px;">
          <legend>Alunos</legend>
          <div id='ctnAlunos'> </div>
        </fieldset>
      </fieldset>
      <input type="button" disabled='disabled' id='imprimir' value='Imprimir' name='imprimir' />
    </form>
  </div>
</body>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script type="text/javascript">

const ARQUIVO_MSG_EDU2_ATARECLASSIFICACAO001 = "educacao.escola.edu2_atareclassificacao001."
  
var oEscola       = new DBViewFormularioEducacao.ListaEscola();
var oCalendario   = new DBViewFormularioEducacao.ListaCalendario();
var oToggleAlunos = new DBToggleList([{sId: 'sAluno', sLabel: 'Aluno'}]);
oToggleAlunos.closeOrderButtons();
oToggleAlunos.show($('ctnAlunos'));

var fFuncaoLoadEscola = function() {

  if (this.oCboEscola.options.length > 2) {
    this.oCboEscola.value = 0;
  }

  var oEscolaSelecionada = oEscola.getSelecionados();
  
  if (oEscolaSelecionada.codigo_escola != '') {

    oCalendario.setEscola(oEscolaSelecionada.codigo_escola);
    oCalendario.getCalendarios();
    js_buscaEmissor();
  }

};

var fFuncaoChangeEscola = function () {

  var oEscolaSelecionada = oEscola.getSelecionados();
  if (oEscolaSelecionada.codigo_escola == '') {

    oCalendario.limpar();
    oToggleAlunos.clearAll();
    $('imprimir').setAttribute("disabled", "disabled");
  } else {

    oCalendario.setEscola(oEscolaSelecionada.codigo_escola);
    oCalendario.getCalendarios();
    js_buscaEmissor();
   }
};


var fFunctionLoadCalendario = function(oCalendario) {
  
  $('imprimir').setAttribute("disabled", "disabled");
};

var fFunctionChangeCalendario = function() {

  var oCalendarioSelecionado = oCalendario.getSelecionados();
  oToggleAlunos.clearAll();
  if (oCalendarioSelecionado.iCalendario == '') {
    return false;
  }

  js_buscaAlunos();
};

/**
 * Busca os alunos da escola e cursando o calendário selecionado. 
 */
function js_buscaAlunos() {

  var oEscolaSelecionada     = oEscola.getSelecionados();
  var oCalendarioSelecionado = oCalendario.getSelecionados();
  
  var oParametros = {exec        : "getAlunosReclassificados",
                     iEscola     : oEscolaSelecionada.codigo_escola, 
                     iCalendario : oCalendarioSelecionado.iCalendario};

  var oRequest        = {};
  oRequest.method     = 'post';                                        
  oRequest.parameters = 'json='+Object.toJSON(oParametros);        
  oRequest.onComplete = function (oAjax) {                         
                          js_retornoAlunos(oAjax);
                        };

  js_divCarregando("Aguarde... buscado alunos da escola e calendário selecionado.", "msgBox");
  new Ajax.Request("edu4_classificacao.RPC.php", oRequest);
}


function js_retornoAlunos(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("(" + oAjax.responseText + ")");

  if (oRetorno.status == 2) {

    alert(oRetorno.message.urlDecode());
    return false;
  }
  
  oToggleAlunos.clearAll();
  oRetorno.dados.each( function (oAluno) {
    oToggleAlunos.addSelect({iAluno:oAluno.iCodigo, sAluno:oAluno.sNome.urlDecode()});
  }); 
  oToggleAlunos.renderRows();

  $('imprimir').removeAttribute("disabled");
}

function js_validaImpressao() {

  var oEscolaSelecionada     = oEscola.getSelecionados();
  var oCalendarioSelecionado = oCalendario.getSelecionados();

  if ( $F('listaModeloAta') == '' ) {

    alert( _M(ARQUIVO_MSG_EDU2_ATARECLASSIFICACAO001 + "erro_nenhum_modelo_selecionado") );
    return false; 
  }

  if ( oEscolaSelecionada.codigo_escola == '' ) {

    alert( _M(ARQUIVO_MSG_EDU2_ATARECLASSIFICACAO001 + "erro_nenhuma_escola_selecionada") );
    return false;    
  } 
  
  if ( oCalendarioSelecionado.iCalendario == '' ) {
    
    alert( _M(ARQUIVO_MSG_EDU2_ATARECLASSIFICACAO001 + "erro_nenhum_calendario_selecionado") );
    return false;    
  } 

 //if ( $F('emissor') == '' ) {
 //  
 //  alert( _M(ARQUIVO_MSG_EDU2_ATARECLASSIFICACAO001 + "erro_nenhum_emissor_selecionado") );
 //  return false;    
 //} 

  if (oToggleAlunos.getSelected().length == 0) {

    alert( _M(ARQUIVO_MSG_EDU2_ATARECLASSIFICACAO001 + "erro_nenhum_aluno_selecionado") );
    return false;
  }
  return true; 
}

/**
 * Função para imprimir os dados
 */
$('imprimir').observe("click", function () {

  var oEscolaSelecionada     = oEscola.getSelecionados();
  var oCalendarioSelecionado = oCalendario.getSelecionados();
  
  if ( !js_validaImpressao() ) {
    return false;
  }

  var sUrl  = "edu2_atareclassificacao002.php?";
      sUrl += "iModelo="+ $F('listaModeloAta');
      sUrl += "&iEscola="+ oEscolaSelecionada.codigo_escola;
      sUrl += "&iCalendario="+ oCalendarioSelecionado.iCalendario;
      sUrl += "&sEmissor="+ $F('emissor');
      sUrl += "&aAlunos="+Object.toJSON(oToggleAlunos.getSelected());
   
  jan = window.open(sUrl,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  jan.moveTo(0,0);
  
});

/**
 * Busca os modelos de Ata cadastrados
 */
(function () {

  var oParametros = {exec : "buscaModelosAta"};

  var oRequest        = {};
  oRequest.method     = 'post';                                        
  oRequest.parameters = 'json='+Object.toJSON(oParametros);        
  oRequest.onComplete = function (oAjax) {                         
             js_retornoModelosAta(oAjax);
           };
  
  js_divCarregando("Aguarde... buscado modelos de ata.", "msgBoxB");
  new Ajax.Request("edu4_classificacao.RPC.php", oRequest);
  
})();

function js_retornoModelosAta(oAjax) {

  js_removeObj("msgBoxB");
  var oRetorno = eval("(" + oAjax.responseText + ")");

  if (oRetorno.status == 2) {

    alert(oRetorno.message.urlDecode());
    return false;
  }

  oRetorno.dados.each( function(oModelo) {
    $("listaModeloAta").add(new Option (oModelo.sDescricao.urlDecode(), oModelo.iCodigo) );
  });
}



function js_buscaEmissor() {

  var oEscolaSelecionada = oEscola.getSelecionados();
  if (oEscolaSelecionada.codigo_escola == '') {
    return false;
  }
  var oParametros     = {};
  oParametros.exec    = 'buscaEmissor';
  oParametros.iEscola = oEscolaSelecionada.codigo_escola;

  $('emissor').options.length = 0;
  $('emissor').add(new Option("Selecione Emissor", ""));
  
  js_divCarregando(_M("educacao.escola.edu2_atestadofrequencia.pesquisando_emissor"), "msgBox");

  var oObjeto        = {};
  oObjeto.method     = 'post';
  oObjeto.parameters = 'json='+Object.toJSON(oParametros);
  oObjeto.onComplete = function(oAjax) {
                       js_retornoEmissor(oAjax);
                     };
  new Ajax.Request("edu_educacaobase.RPC.php", oObjeto);
}

function js_retornoEmissor(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("(" + oAjax.responseText+ ")");

  if (oRetorno.status == 2) {
    alert(oRetorno.message.urlDecode());
  }
  
  oRetorno.dados.each( function (oEmissor) {

    var sValue  = oEmissor.funcao.urlDecode()+'|'+oEmissor.nome.urlDecode()+'|'+oEmissor.descricao.urlDecode();
    var sString = oEmissor.funcao.urlDecode()+' - '+oEmissor.nome.urlDecode();

    if (!empty(oEmissor.descricao)) {
      sString += " ("+oEmissor.descricao.urlDecode()+") ";
    }
    $('emissor').add(new Option(sString, sValue));
  });
}



oEscola.setCallBackLoad(fFuncaoLoadEscola);       
oEscola.setCallbackOnChange(fFuncaoChangeEscola); 
oEscola.show($('listaEscola'));

oCalendario.setCallBackLoad(fFunctionLoadCalendario);
oCalendario.setOnChangeCallBack(fFunctionChangeCalendario);
oCalendario.agruparPorAno(false);
oCalendario.show($('listaCalendario'));

</script>  
</html>