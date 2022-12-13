<?
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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/classes/educacao/escola/ListaEscola.classe.js"></script>
</head>
<body bgcolor="#CCCCCC" >

<div class="container">

  <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>

  <fieldset style="width: 500px;">
    <legend>Relatório Dados do Professor</legend>

    <table class="form-container">
      <tr>
        <td nowrap="nowrap" >Escola:</td>
        <td nowrap="nowrap" id='listaEscola'></td>
      </tr>
      <tr>
        <td nowrap='nowrap'>Professor:</td>
        <td nowrap='nowrap'>
          <select id="professor" >
          </select>
        </td>
      </tr>
    </table>
  </fieldset>
  <input type="button" value="Imprimir" id="imprimir" name="imprimir" disabled="disabled" />
</div>

</body>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</html>

<script type="text/javascript">

  const MSG_EDU2PROFESSOR = 'educacao.escola.edu2prossefor.';
  var oEscola     = new DBViewFormularioEducacao.ListaEscola();

  var fFuncaoLoadEscola = function() {

    if (this.oCboEscola.options.length > 2) {
      this.oCboEscola.value = '';
    } else {
      buscaProfessorEscola();
    }
  };

  var fFuncionChange = function() {

    var oEscolaSelecionada   = oEscola.getSelecionados();
    js_limpaProfessor();
    if (oEscolaSelecionada.codigo_escola != '') {
      buscaProfessorEscola();
    }
  }

  oEscola.setCallBackLoad(fFuncaoLoadEscola);
  oEscola.setCallbackOnChange(fFuncionChange);
  oEscola.habilitarOpcaoTodas(false);
  oEscola.show($('listaEscola'));


  function js_limpaProfessor() {

    $('professor').innerHTML = '';
    $('imprimir').setAttribute('disabled', 'disabled');
  }

  function buscaProfessorEscola() {

    var oEscolaSelecionada = oEscola.getSelecionados();

    var oParametro     = {};
    oParametro.exec    = 'getProfessorEscola';
    oParametro.iEscola = oEscolaSelecionada.codigo_escola;

    js_divCarregando( _M(MSG_EDU2PROFESSOR+'aguarde_buscando_professor'), "msgBox");

    var oObjeto        = {};
    oObjeto.method     = 'post';
    oObjeto.parameters = 'json='+Object.toJSON(oParametro);
    oObjeto.onComplete = js_retornoProfessorEscola;

    new Ajax.Request('edu4_regente.RPC.php', oObjeto);
  }

  function js_retornoProfessorEscola(oAjax) {

    js_removeObj('msgBox');
    var oRetorno = eval( "(" + oAjax.responseText + ")" );

    oRetorno.aProfessores.each( function (oProfessor) {

      var oOption = new
      $('professor').add( new Option(oProfessor.z01_nome.urlDecode(), oProfessor.z01_numcgm) );
    });

    if (oRetorno.aProfessores.length > 0) {
      $('imprimir').removeAttribute('disabled');
    }
  }

  $('imprimir').observe('click', function() {

    var oEscolaSelecionada = oEscola.getSelecionados();

    var sUrl  = 'edu2_dadosprofessor002.php?iEscola='+oEscolaSelecionada.codigo_escola;
        sUrl += '&iProfessor='+$F('professor');
    jan = window.open(sUrl, '', 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
    jan.moveTo(0,0);
  });
</script>