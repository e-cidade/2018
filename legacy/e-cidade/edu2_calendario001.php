<?php
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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
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
  ?>
  <script type="text/javascript"></script>
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
        <legend>Relatório de Calendário</legend>
        <table class="form-container">
          <tr>
            <td nowrap="nowrap" class='bold'>Escola:</td>
            <td nowrap="nowrap" id='listaEscola'></td>
          </tr>
          <tr>
            <td nowrap="nowrap" class='bold'>Calendário:</td>
            <td nowrap="nowrap" id='listaCalendario'></td>
          </tr>
        </table>
      </fieldset>
      <input type="button" disabled='disabled' id='imprimir' value='Imprimir' name='imprimir' />
    </form>
  </div>
</body>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>

var oEscola     = new DBViewFormularioEducacao.ListaEscola();
var oCalendario = new DBViewFormularioEducacao.ListaCalendario();

var fFuncaoLoadEscola = function() {

  if (this.oCboEscola.options.length > 2) {
    this.oCboEscola.value = 0;
  }

  var oEscolaSelecionada = oEscola.getSelecionados();
  
  if (oEscolaSelecionada.codigo_escola != '') {

    oCalendario.setEscola(oEscolaSelecionada.codigo_escola);
    oCalendario.getCalendarios();
  }

};

var fFuncaoChangeEscola = function () {

  var oEscolaSelecionada = oEscola.getSelecionados();
  if (oEscolaSelecionada.codigo_escola == '') {

    oCalendario.limpar();
    $('imprimir').setAttribute("disabled", "disabled");
  } else {

    oCalendario.setEscola(oEscolaSelecionada.codigo_escola);
    oCalendario.getCalendarios();
   }
};

var fFunctionLoadCalendario = function() {
  $('imprimir').setAttribute("disabled", "disabled");
};

var fFunctionChangeCalendario = function() {

  var oEscolaSelecionada     = oEscola.getSelecionados();
  var mCalendarioSelecionado = oCalendario.getSelecionados();
  var aListaCalendarios      = new Array();

  if (oCalendario.lAgruparPorAno) {

    if (mCalendarioSelecionado.length == 0) {

      $('imprimir').setAttribute("disabled", "disabled");
      return false;
    } else {
      console.log(mCalendarioSelecionado);
      if (mCalendarioSelecionado.iCalendario != '') {
        $('imprimir').removeAttribute("disabled");
      }
    }

    for (var i = 0; i < mCalendarioSelecionado.length; i++) {
      aListaCalendarios.push(mCalendarioSelecionado[i].iCalendario);
    }
  } else {

    if (mCalendarioSelecionado.iCalendario == '') {

      $('imprimir').setAttribute("disabled", "disabled");
      return false;
    } else {

      if (mCalendarioSelecionado.iCalendario != '') {
        $('imprimir').removeAttribute("disabled");
      }
    }

    aListaCalendarios.push(mCalendarioSelecionado.iCalendario);
  }

};

/**
 * seta os callback da escola
 */
oEscola.setCallBackLoad(fFuncaoLoadEscola);
oEscola.setCallbackOnChange(fFuncaoChangeEscola); 

oEscola.habilitarOpcaoTodas(true); 
oEscola.show($('listaEscola'));

/**
 * seta os callback do calendário
 */
oCalendario.setCallBackLoad(fFunctionLoadCalendario);
oCalendario.setOnChangeCallBack(fFunctionChangeCalendario);
oCalendario.lSomenteCalendarioComTurmaVinculada = false;

oCalendario.show($('listaCalendario'));

/**
 * Função para imprimir os dados do formulário1
 * @return
 */
$('imprimir').observe("click", function () {

  var oEscolaSelecionada       = oEscola.getSelecionados();
  var aCalendariosSelecionados = oCalendario.getSelecionados();  

  var sUrl  = "edu2_calendario002.php";
      sUrl += "?iEscola="        +oEscolaSelecionada.codigo_escola;
      sUrl += "&iCalendario="    +aCalendariosSelecionados.iCalendario;
      sUrl += "&sNomeCalendario="+aCalendariosSelecionados.sDescricao;
      sUrl += "&iAno="           +aCalendariosSelecionados.iAno;

  jan = window.open(sUrl,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  jan.moveTo(0,0);
});

</script>
</html>