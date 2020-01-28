<?
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

include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_turma_classe.php");
include("classes/db_procavaliacao_classe.php");
include("dbforms/db_funcoes.php");

$sMsgTitle = "Para selecionar mais de uma turma, mantenha pressionada a tecla CTRL e clique sobre o nome das turmas. ";
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/DBFormCache.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/DBFormSelectCache.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#CCCCCC">
<div class='container'>
  <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
  <form action="" ></form>
  <fieldset>
    <legend>Relatório de Conselho de Classe</legend>
    <table class='form-container'>
      <tr>
        <td class='bold field-size4' nowrap="nowrap">Calendário:</td>
        <td nowrap="nowrap">
          <select id='cntCalendario' onchange="js_buscaPeriodos(); js_buscaTurmas();">
            <option value=''>Selecione</option>
          </select>
        </td>
      </tr>
      <tr>
        <td class='bold' nowrap="nowrap">Período:</td>
        <td nowrap="nowrap">
          <select id='cntPeriodo' >
            <option value=''>Selecione</option>
          </select>
        </td>
      </tr>
    </table>
    <fieldset class="separator">
      <legend>Turmas:</legend>
      <select id='cntTurmas' title="<?php echo $sMsgTitle;?>" multiple="multiple" >
      </select>
    </fieldset>
    <table class='form-container'>   
      <tr>
        <td class='bold field-size4' nowrap="nowrap">Exibir Troca de Turma:</td>
        <td nowrap="nowrap">
          <select id='exibeTrocaTurma'>
            <option value='Sim' >Sim</option>
            <option value='Não' selected="selected">Não</option>
          </select>
        </td>
      </tr>
      <tr>
        <td class='bold' nowrap="nowrap">Exibir Classificação Aluno:</td>
        <td nowrap="nowrap">
          <select id='exibeClassificacao'>
            <option value='Sim' selected="selected">Sim</option>
            <option value='Não' >Não</option>
          </select>
          </select>
        </td>
      </tr>
      <tr>
        <td class='bold' nowrap="nowrap">Fonte Quadro de Notas:</td>
        <td nowrap="nowrap">
          <select id='tamanhoFonte'>
            <option value='6' selected="selected">6</option>
            <option value='7' >7</option>
            <option value='8' >8</option>
            <option value='9' >9</option>
          </select>
          </select>
        </td>
      </tr>
      <tr>
        <td class='bold' nowrap="nowrap">Mostrar Legenda:</td>
        <td nowrap="nowrap">
          <select id='comLegenda'>
            <option value='Sim' >Sim</option>
            <option value='Não' selected="selected">Não</option>
          </select>
          </select>
        </td>
      </tr>
    </table>
  </fieldset>
  <input type="button" value='Imprimir' name='imprimir' id='imprimir'>
</div>
<?php 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script type="text/javascript">

var oDBFormCache = new DBFormCache('oDBFormCache', 'edu2_resultparcial001.php');

oDBFormCache.setElements(new Array($('exibeTrocaTurma')));
oDBFormCache.setElements(new Array($('exibeClassificacao')));
oDBFormCache.setElements(new Array($('tamanhoFonte')));
oDBFormCache.setElements(new Array($('comLegenda')));

oDBFormCache.load();


(function () {

  $('cntTurmas').style.height    = '180px'; 
  $('cntTurmas').style.width     = '300px';
  $('cntTurmas').options.length  = 0;
  
  var oParametro = {};
  oParametro.exec = 'pesquisaCalendario';

  var oObjeto        = new Object();
  oObjeto.method     = 'post';
  oObjeto.parameters = 'json='+Object.toJSON(oParametro);
  oObjeto.onComplete = function(oAjax) {
                         js_retornoCalendarios(oAjax);
                       };
  js_divCarregando("Aguarde, buscando calendários.", "msgBox");
  new Ajax.Request('edu_educacaobase.RPC.php', oObjeto);
  
})();

function js_retornoCalendarios(oAjax) {
  
  js_removeObj('msgBox');
  var oRetorno = eval('('+oAjax.responseText+')');

  if (oRetorno.dados.length == 0) {

    alert('Nenhum calendário para escola');
    return false;
  }
  oRetorno.dados.each( function (oCalendario) {

    $('cntCalendario').add(new Option(oCalendario.ed52_c_descr.urlDecode(), oCalendario.ed52_i_codigo));
  });
}

function js_buscaPeriodos() {

  $('cntTurmas').options.length  = 0;
  $('cntPeriodo').options.length = 0;
  if ($F('cntCalendario') == '') {
    return false;
  }
  $('cntPeriodo').add(new Option("Selecione", ""));
  
  var oParametro                 = {};
  oParametro.exec                = 'buscaPeriodosAvaliacaoEscola';
  oParametro.lFiltraEscolaLogada = true;
  oParametro.iCalendario         = $F('cntCalendario');

  var oObjeto        = new Object();
  oObjeto.method     = 'post';
  oObjeto.parameters = 'json='+Object.toJSON(oParametro);
  oObjeto.onComplete = function(oAjax) {
                         js_retornoPeriodos(oAjax);
                       };
  js_divCarregando("Aguarde, buscando períodos.", "msgBox");
  new Ajax.Request('edu_educacaobase.RPC.php', oObjeto);
}

function js_retornoPeriodos(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval('('+oAjax.responseText+')');

  if (oRetorno.dados.length == 0) {

    alert('Nenhum período cadastrado para escola.');
    return false;
  }
  oRetorno.dados.each( function (oPeriodos) {

    $('cntPeriodo').add(new Option(oPeriodos.descricao_periodo.urlDecode(), oPeriodos.codigo_periodo));
  });
}

function js_buscaTurmas() {

  $('cntTurmas').options.length  = 0;
  if ($F('cntCalendario') == '') {
    return false;
  }

  var oParametro                    = {};
  oParametro.exec                   = 'pesquisaTurmaEtapa';
  oParametro.lComAlunosMatriculados = true;
  oParametro.iCalendario            = $F('cntCalendario');
  
  var oObjeto        = new Object();
  oObjeto.method     = 'post';
  oObjeto.parameters = 'json='+Object.toJSON(oParametro);
  oObjeto.onComplete = function(oAjax) {
                         js_retornoTurmas(oAjax);
                       };
  js_divCarregando("Aguarde, buscando turmas.", "msgBox");
  new Ajax.Request('edu_educacaobase.RPC.php', oObjeto);
}

function js_retornoTurmas(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval('('+oAjax.responseText+')');

  if (oRetorno.dados.length == 0) {

    alert('Nenhuma turma vínculada ao calendário ou turmas sem alunos matriculados.');
    return false;
  }
  oRetorno.dados.each( function (oTurma) {

    var oOption = new Option(oTurma.ed57_c_descr.urlDecode(), oTurma.ed57_i_codigo);
    oOption.setAttribute('etapa', oTurma.codigo_etapa);
    $('cntTurmas').add(oOption);
  });
}


$('imprimir').observe('click', function () {

  if ($F('cntCalendario') == '') {

    alert('Selecione um calendario.');
    return false;
  }
  if ($F('cntPeriodo') == '') {

    alert('Selecione um periodo.');
    return false;
  }

  var aTurmas = new Array();
  var iTurmas = $('cntTurmas').options.length;

  for (var i = 0; i < iTurmas; i++) {

    if ($('cntTurmas').options[i].selected) {

      var oTurma = {iTurma: $('cntTurmas').options[i].value, iEtapa : $('cntTurmas').options[i].getAttribute('etapa')};
      aTurmas.push(oTurma);
    }
  }

  if (aTurmas.length == 0) {

    alert('Selecione ao menos uma turma para imprimir o relatório.');
    return false;
  }

  var sUrl  = 'edu2_resultparcial003.php?periodo='+$F('cntPeriodo');
  sUrl += '&trocaTurma='+$F('exibeTrocaTurma');
  sUrl += '&classificacaoAlunoTurma='+$F('exibeClassificacao');
  sUrl += '&oTurmas='+Object.toJSON(aTurmas);
  sUrl += '&tamanhoFonte='+$F('tamanhoFonte');
  sUrl += '&comLegenda='+$F('comLegenda');
  
  jan = window.open(sUrl,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
  oDBFormCache.save();
  
});

</script>
</html>