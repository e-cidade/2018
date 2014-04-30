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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");

$db_opcao      = 1;
$oRotuloCampos = new rotulocampo();
$oRotuloCampos->label("h02_codigo");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
      db_app::load("scripts.js, prototype.js, strings.js");
      db_app::load("estilos.css");
    ?>
  </head>
  <body style="margin-top: 25px; background-color: #CCCCCC;">
    <center>
      <form action="" method="post">
        <fieldset style="width: 20%">
          <legend class="bold">Filtros do Relatório</legend>
          <table>
            <tr>
              <td nowrap="nowrap">
                <label class="bold">Período:</label>
              </td>
              <td nowrap="nowrap">
                <?php
                  db_inputdata('dataInicial', null, null, null, true, 'text', $db_opcao);
                ?>
                <b> até: </b>
                <?php
                  db_inputdata('dataFinal', null, null, null, true, 'text', $db_opcao);
                ?>
              </td>
            </tr>
            <tr>
              <td class="bold" nowrap="nowrap"><?=$Lh02_codigo?></td>
              <td nowrap="nowrap" id="ctnTipoCurso"></td>
            </tr>
            <tr>
              <td nowrap="nowrap">
                <label class="bold">Curso / Oficina</label>
              </td>
              <td nowrap="nowrap" id="ctnCursoOficina"></td>
            </tr>
          </table>
        </fieldset>
        <input id="btnImprimir" name="btnImprimir" type="button" value="Imprimir" />
        <input id="btnLimpar" name="btnLimpar" type="button" value="Limpar Filtros" />
      </form>
    </center>
  </body>
  <?php
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</html>
<script>
var sRpc = 'soc4_cursosocial.RPC.php';

var oCboTipoCurso             = document.createElement('select');
    oCboTipoCurso.id          = 'oCboTipoCurso';
    oCboTipoCurso.style.width = "100%";
$('ctnTipoCurso').appendChild(oCboTipoCurso);
    
var oCboCursos             = document.createElement('select');
    oCboCursos.id          = 'oCboCursos';
    oCboCursos.style.width = "100%";
$('ctnCursoOficina').appendChild(oCboCursos);

$('oCboTipoCurso').observe("change", function(event) {
  js_cursos();
});

/**
 * Busca os tipos de cursos cadastrados
 */
function js_tipoCurso() {

  var oParametro      = new Object();
      oParametro.exec = 'buscaCategoriaCurso';

  var oDadosRequest            = new Object();
      oDadosRequest.method     = 'post';
      oDadosRequest.parameters = 'json='+Object.toJSON(oParametro);
      oDadosRequest.onComplete = js_retornoTipoCurso;

  js_divCarregando("Aguarde, buscando os tipos de curso cadastrados.", "msgBox");
  new Ajax.Request(sRpc, oDadosRequest);
}

/**
 * Retorno da busca pelos tipos de curso
 */
function js_retornoTipoCurso(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');
  oCboTipoCurso.options[0] = new Option('Selecione', '');
      
  oRetorno.aCategorias.each(function(oLinha, iSeq) {
    oCboTipoCurso.options[iSeq+1] = new Option(oLinha.sDescricao.urlDecode(), oLinha.iCodigo);
  });
  
  $('ctnTipoCurso').appendChild(oCboTipoCurso);
}

/**
 * Busca os cursos cadastrados e que nao tenham sido concluidos
 */
function js_cursos() {

  var oParametro            = new Object();
      oParametro.exec       = 'buscaCursos';
      oParametro.iTipoCurso = oCboTipoCurso.value;

  var oDadosRequest            = new Object();
      oDadosRequest.method     = 'post';
      oDadosRequest.parameters = 'json='+Object.toJSON(oParametro);
      oDadosRequest.onComplete = js_retornoCursos;

  js_divCarregando("Aguarde, buscando os cursos cadastrados.", "msgBox");
  new Ajax.Request(sRpc, oDadosRequest);
}

/**
 * Retorno da busca pelos cursos cadastrados
 */
function js_retornoCursos(oResponse) {

  js_removeObj("msgBox");
  
  var oRetorno = eval('('+oResponse.responseText+')');

  if (oCboCursos.options.length > 0) {

    var iTamanho = oCboCursos.options.length;
    for (var iContador = 0; iContador < iTamanho; iContador++) {
      oCboCursos.options[iContador] = null;
    }
  }
  
  oCboCursos.options[0] = new Option('Selecione', '');

  oRetorno.aCursos.each(function(oLinha, iSeq) {
    oCboCursos.options[iSeq+1] = new Option(oLinha.as19_nome.urlDecode(), oLinha.as19_sequencial);
  });
  $('ctnCursoOficina').appendChild(oCboCursos);
}

/**
 * Validamos o intervalo entre as datas selecionadas
 */
function js_validaData() {

  if ($('dataInicial').value != '' && $('dataFinal').value != '') {

    var aDataInicial = new Array();
    var aDataFinal   = new Array();

    aDataInicial[0]      = $F('dataInicial').substr(0, 2);
    aDataInicial[1]      = $F('dataInicial').substr(3, 2);
    aDataInicial[2]      = $F('dataInicial').substr(6, 4);
    var sNovaDataInicial = aDataInicial[2]+'-'+aDataInicial[1]+'-'+aDataInicial[0];

    aDataFinal[0]      = $F('dataFinal').substr(0, 2);
    aDataFinal[1]      = $F('dataFinal').substr(3, 2);
    aDataFinal[2]      = $F('dataFinal').substr(6, 4);
    var sNovaDataFinal = aDataFinal[2]+'-'+aDataFinal[1]+'-'+aDataFinal[0];

    if (js_diferenca_datas(sNovaDataInicial, sNovaDataFinal, 3) == true) {

      alert('Intervalo de datas inválido. Data final menor que a data inicial.');
      return false;
    }
  }
  return true;
}

$('btnLimpar').observe("click", function(event) {

  $('dataInicial').value = '';
  $('dataFinal').value   = '';
  oCboTipoCurso.value    = '';
  oCboCursos.value       = '';
  js_cursos();
});

/**
 * Imprime o formulario caso passe na validacao
 */
$('btnImprimir').observe("click", function() {

  if (js_validaData()) {

    var sLocation  = "soc2_cursosoficinas002.php?";
        sLocation += "&sDataInicial="+$('dataInicial').value;
        sLocation += "&sDataFinal="+$('dataFinal').value;
        sLocation += "&iTipoCurso="+oCboTipoCurso.value;
        sLocation += "&iCurso="+oCboCursos.value;

    jan = window.open(sLocation, 
                      '', 
                      'width='+(screen.availWidth-5)+
                      ',height='+(screen.availHeight-40)+
                      ',scrollbars=1,location=0');
    jan.moveTo(0,0);
  }
});

js_tipoCurso();
js_cursos();
</script>