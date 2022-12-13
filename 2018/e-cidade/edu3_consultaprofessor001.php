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

//MODULO: educação
include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

$clrotulo = new rotulocampo();
$clrotulo->label("ed284_i_rhpessoal");
$clrotulo->label("ed285_i_cgm");
$clrotulo->label("z01_nome");
$clrotulo->label("ed18_i_escola");
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link rel="stylesheet" type="text/css" href="estilos.css" />
  <link rel="stylesheet" type="text/css" href="estilos/grid.style.css" />
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/classes/educacao/escola/ListaEscola.classe.js"></script>
</head>
<script>

</script>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<div class="container">
  <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>

  <form >
    <fieldset>
      <legend>Consulta de Professores</legend>
      <table class="form-container">
        <tr>
          <td class='bold' nowrap='nowrap'>Matrícula:</td>
          <td nowrap='nowrap'>
            <?php
              db_input("iMatricula", 10, @$Ied284_i_rhpessoal, true, "text", 1);
            ?>
          </td>
        </tr>
        <tr>
          <td class='bold' nowrap='nowrap'>CGM:</td>
          <td nowrap='nowrap'>
            <?php
              db_input("iCgm", 10, @$Ied285_i_cgm, true, "text", 1);
            ?>
          </td>
        </tr>
        <tr>
          <td class='bold' nowrap='nowrap'>Nome/Razão Social:</td>
          <td nowrap='nowrap'>
            <?php
              db_input("sNomeProfessor",50,$Iz01_nome,true,"text",1,"onFocus=\"nextfield='pesquisar2'\"");
            ?>
          </td>
        </tr>
        <tr>
          <td class='bold' nowrap="nowrap" >Escola:</td>
          <td nowrap="nowrap" id='listaEscola'></td>
        </tr>
      </table>
    </fieldset>
    <input type="button" name="pesquisar" id='pesquisar' value="Pesquisar" />
    <input type="Reset"  name="limpar" value="Limpar" />
  </form>
</div>
<fieldset class="subcontainer" style="width: 70%;">
  <legend>Professor(es)</legend>
  <div id="ctnGridProfessores"></div>
</fieldset>
<?php
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit") );
?>
</body>
</html>
<script type="text/javascript" >

  var oGridProfessores = new DBGrid('gridProfessores')
  oGridProfessores.nameInstance = 'oGridProfessores';
  oGridProfessores.setCellAlign(['left', 'center', 'center']);
  oGridProfessores.setCellWidth(['65%', '10%', '25%']);
  oGridProfessores.setHeader(['Nome', 'CGM', 'CPF/CNPJ']);
  oGridProfessores.setHeight(150);
  oGridProfessores.show($('ctnGridProfessores'));


  var oEscola     = new DBViewFormularioEducacao.ListaEscola();

  var fFuncaoLoadEscola = function() {

    if (this.oCboEscola.options.length > 2) {
      this.oCboEscola.value = 0;
    }
  };

  oEscola.setCallBackLoad(fFuncaoLoadEscola); // Opcional
  oEscola.habilitarOpcaoTodas(true);          // Opcional
  oEscola.show($('listaEscola'));


  $('pesquisar').observe("click", function() {

    var oEscolaSelecionada = oEscola.getSelecionados();

    var oParametro            = new Object();
    oParametro.exec           = 'getProfessorEscola';
    oParametro.iEscola        = oEscolaSelecionada.codigo_escola;
    oParametro.iMatricula     = $F('iMatricula');
    oParametro.iCgm           = $F('iCgm');
    oParametro.sNomeProfessor = encodeURIComponent(tagString($F('sNomeProfessor')));

    js_divCarregando("Aguarde, pesquisando professores.", "msgBox");

    var oObjeto        = {};
    oObjeto.method     = 'post';
    oObjeto.parameters = 'json='+Object.toJSON(oParametro);
    oObjeto.onComplete = js_retornoPesquisaProfessores;

    new Ajax.Request('edu4_regente.RPC.php', oObjeto);
  });

  function js_retornoPesquisaProfessores(oAjax) {

    js_removeObj('msgBox');
    var oRetorno = eval( "(" + oAjax.responseText + ")");

    oGridProfessores.clearAll(true);

    oRetorno.aProfessores.each( function (oProfessor) {

      var oLinkNome = new Element('a', { 'href':'#'}).update(oProfessor.z01_nome.urlDecode());
      oLinkNome.setAttribute('onclick', 'js_abreConsultaProfessor('+oProfessor.z01_numcgm+');');

      var oLinkCgm = new Element('a', { 'href':'#'}).update(oProfessor.z01_numcgm);
      oLinkCgm.setAttribute('onclick', 'js_JanelaAutomatica("cgm", '+oProfessor.z01_numcgm+');');


      var aLinha = [];
      aLinha.push( oLinkNome.outerHTML);
      aLinha.push( oLinkCgm.outerHTML );
      aLinha.push( oProfessor.z01_cgccpf );
      oGridProfessores.addRow(aLinha);
    });

    if ( oRetorno.aProfessores.length == 1 ) {
      js_abreConsultaProfessor(oRetorno.aProfessores[0].z01_numcgm);
    }

    oGridProfessores.renderRows();
  }

  function js_abreConsultaProfessor(iCgm) {

    js_OpenJanelaIframe('','db_iframe_professor','edu3_consultaprofessor002.php?chavepesquisa='+iCgm,
                        'Consulta de Professores',true
    );
  }

</script>