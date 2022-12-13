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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");

$oRotulo = new rotulocampo;
$oRotulo->label("as19_sequencial");
$oRotulo->label("as19_nome");
$oRotulo->label("ov02_sequencial");
$oRotulo->label("as02_nis");


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link rel="stylesheet" type="text/css" href="estilos/grid.style.css"/>
<?php
    db_app::load("prototype.js, scripts.js, strings.js, arrays.js, datagrid.widget.js");
    db_app::load("estilos.css");
?>
</head>
<body>
<div class='container'>
  <form method="post" name='form1'>
    <fieldset>
      <legend>Matricular cidadão em Curso/Oficina</legend>
      <table class='form-container'>
        <tr>
          <td class='bold'>
            <?php db_ancora("Curso / Oficina:", "js_buscaCursoSocial(true); ", 1); ?>
          </td>
          <td colspan="3">
            <?php
 			      db_input('as19_sequencial', 10, $Ias19_sequencial, true, 'text', 1, " onchange='js_buscaCursoSocial(false);'");
 			      db_input('as19_nome', 55, '', true,'text', 3,'');
 			    ?>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap" style="font-weight: bold;">
            <? db_ancora("Cidadão: ","js_pesquisaCidadao(true, false);", 1);?>
          <td nowrap="nowrap">
            <?php
              db_input("codigoCidadao", 10, $Iov02_sequencial, true, "text", 1, 
                       "onchange='js_pesquisaCidadao(false, false);'");
              db_input("nome",  55, '', true, "text", 3);
              db_input("as02_nis", 10, $Ias02_nis, true, "hidden", 3);
            ?>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <fieldset class='separator'>
              <legend>Observação</legend>
              <?php 
                db_textarea('observacao', 5, 80, '', true, 'text', 1);
              ?>               
            </fieldset>
          </td>
        </tr>
      </table>
    </fieldset>
    <input type="button" id='salvarCurso' name='salvar' value='Salvar' />
    <input type="button" id='excluirCurso' name='excluir' value='Excluir Selecionados' />
  </form>
</div>
<div class='container' style="width: 800px;">
    <fieldset>
      <legend>Cidadãos matriculados no curso selecionado</legend>
      <div id='ctnGridMatricula'>
      
      </div>
    </fieldset>
  </div>
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script type="text/javascript">

var sRPC = "soc4_cursosocial.RPC.php";
var oGet = js_urlToObject();
  
var oGridMatriculaCurso = new DBGrid("gridMatriculaCurso");
oGridMatriculaCurso.nameInstance = "oGridMatriculaCurso";

oGridMatriculaCurso.setCheckbox(0);
oGridMatriculaCurso.setCellWidth(new Array('0','95%'));
oGridMatriculaCurso.setCellAlign(new Array('left', 'left'));
oGridMatriculaCurso.setHeader(new Array('Matricula', 'Cidadão'));
oGridMatriculaCurso.setHeight(300);
oGridMatriculaCurso.aHeaders[1].lDisplayed = false;

oGridMatriculaCurso.show($('ctnGridMatricula'));

/**
 * Função de pesquisa para curso social
 */
function js_buscaCursoSocial(lMostra) {

  var sUrl = 'func_cursosocial.php?lAtivos&';
  if(lMostra) {
    
    sUrl += 'funcao_js=parent.js_mostraCurso1|as19_sequencial|as19_nome';
    js_OpenJanelaIframe('', 'db_iframe_cursosocial', sUrl, 'Pesquisa Cursos', true);
  } else if($F('as19_sequencial') != '') {

      sUrl += 'pesquisa_chave='+$F('as19_sequencial');
      sUrl += '&funcao_js=parent.js_mostraCurso';
      js_OpenJanelaIframe('','db_iframe_cursosocial', sUrl,'Pesquisa Cursos', false);
  } else {
      
    $('as19_sequencial').value = "";
    $('as19_nome').value       = "";
  }
}

function js_mostraCurso(sNome, lErro) {

  $('as19_nome').value = sNome;
  if (lErro) {

    $('as19_sequencial').value = ''; 
    $('as19_sequencial').focus(); 
  } else {
    js_buscaCidadaosMatriculados($F('as19_sequencial'));
  }
}

function js_mostraCurso1(iCurso, sNome) {

  $('as19_sequencial').value  = iCurso;
  $('as19_nome').value        = sNome;
  db_iframe_cursosocial.hide();

  js_buscaCidadaosMatriculados(iCurso);
}

/**
 * Função de pesquisa para Cidadao
 */
function js_pesquisaCidadao(lMostra, lNis) {

  var sUrl = 'func_cidadaofamiliacompleto.php?';
  
  if (lMostra) {

    sUrl += 'funcao_js=parent.js_mostraCidadao|ov02_sequencial|ov02_nome|as02_nis|as04_sequencial'; 
    js_OpenJanelaIframe('top.corpo', 'db_iframe_cidadaofamilia', sUrl, 'Pesquisa Cidadão',true);
  } else {

    if ($F('as02_nis') != '' && lNis) {
      
      sUrl += 'pesquisa_chave='+$F('as02_nis');
      sUrl += '&lNis=true';
    }
    
    if ($F('codigoCidadao') != ''  && !lNis) {
      
      sUrl += 'pesquisa_chave='+$F('codigoCidadao');
      sUrl += '&lCidadao=true';
    }

    if (($F('as02_nis') == '' && lNis) || ($F('codigoCidadao') == '' && !lNis)) {
      sUrl += 'pesquisa_chave=';
    }observacao
    
    sUrl += '&funcao_js=parent.js_mostraCidadao2';

    if ($F('as02_nis') != '' || $F('codigoCidadao') != '') {
     js_OpenJanelaIframe('top.corpo', 'db_iframe_cidadaofamilia', sUrl, 'Pesquisa Cidadão', false);
    } else {
      
      $('codigoCidadao').value = "";
      $('nome').value          = "";
      $('as02_nis').value     = "";
    }
  }
}
 
function js_mostraCidadao (iCidadao, sCidadao, iNis, iCodigoFamilia) {

  if (iCidadao != "") {
    
    $('codigoCidadao').value   = iCidadao;
    $('nome').value            = sCidadao;
    $('as02_nis').value        = iNis;
  }
  db_iframe_cidadaofamilia.hide();
}

function js_mostraCidadao2(lErro, iCidadao, sCidadao, iNis, iCadUnico, iFamiliaCadUnico, iCodigoFamilia, iCodigoCadUnico) {

  
  $('nome').value            = sCidadao;
  $('codigoCidadao').value   = iCidadao;
  $('as02_nis').value        = iNis;
  
  if (lErro) {
    
    $('codigoCidadao').value = "";
    $('as02_nis').value      = "";
    $('nome').value          = iCidadao;
    
    if (iCidadao == '') {
      
      $('as02_nis').value      = iCidadao;
      $('codigoCidadao').value = iCidadao;
    }
  }
}



$("salvarCurso").observe("click", function () {

  if ($F('as19_sequencial') == '') {

    alert("Curso não informado.");
    return false;
  } 
  if ($F('codigoCidadao') == '') {

    alert("Cidadão não informado.");
    return false;
  }
  
  var oParametro         = new Object();
  oParametro.exec        = 'adicionarCidadaoCurso';
  oParametro.iCurso      = $F('as19_sequencial');
  oParametro.iCidadao    = $F('codigoCidadao');
  oParametro.sObservacao = encodeURIComponent(tagString($F('observacao')));
  
  js_divCarregando("Aguarde, matriculando cidadão no curso selecionado.", "msgBox");

  new Ajax.Request(sRPC,
                   {
                    method:     'post',
                    parameters: 'json='+Object.toJSON(oParametro),
                    onComplete: js_retornoMatricula
                   }
                  );
});


function js_retornoMatricula (oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oAjax.responseText+')');

  alert(oRetorno.message.urlDecode());
  
  if (oRetorno.status == 1) {

    $('codigoCidadao').value  = "";
    $('nome').value           = "";
    $('as02_nis').value       = "";
    $('observacao').value     = "";
    
    js_buscaCidadaosMatriculados($F('as19_sequencial'));
  }
}

function js_buscaCidadaosMatriculados(iCurso) {

  var oParametro    = new Object();
  oParametro.exec   = 'getCidadaoMatriculadoNoCurso';
  oParametro.iCurso = $F('as19_sequencial')
  js_divCarregando("Aguarde, buscando cidadãos matriculados no curso selecionado.", "msgBox");

  new Ajax.Request(sRPC,
                   {
                    method:     'post',
                    parameters: 'json='+Object.toJSON(oParametro),
                    onComplete: js_retornoCidadaoCurso
                   }
                  );
}

function js_retornoCidadaoCurso(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oAjax.responseText+')');
  
  oGridMatriculaCurso.clearAll(true);
  oRetorno.aCidadaos.each( function (oCidadao) {

    var aLinha = new Array();
    aLinha[0] = oCidadao.iMatricula;
    aLinha[1] = oCidadao.sNome.urlDecode(); 
    oGridMatriculaCurso.addRow(aLinha);
  });

  oGridMatriculaCurso.renderRows();
}


$('excluirCurso').observe("click", function () {
  
  var aSelecionados = oGridMatriculaCurso.getSelection();
  var iVertor       = aSelecionados.length;
  
  if (iVertor == 0) {
  
    alert("Nenhuma matrícula selecionada.");
    return false;
  }
  
  var aMatriculas = new Array();
  for (var i = 0; i < iVertor; i++) {
    aMatriculas.push(aSelecionados[i][0]);
  }
  
  var oParametro         = new Object();
  oParametro.exec        = 'removerCidadaoCurso';
  oParametro.iCurso      = $F('as19_sequencial');
  oParametro.aMatriculas = aMatriculas;
  js_divCarregando("Aguarde, excluindo matriculas selecionadas.", "msgBox");
  
  new Ajax.Request(sRPC,
                   {
                    method:     'post',
                    parameters: 'json='+Object.toJSON(oParametro),
                    onComplete: js_retornoExcluirMatriculaCurso
                   }
                  );
});

function js_retornoExcluirMatriculaCurso (oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oAjax.responseText+')');
  
  alert(oRetorno.message.urlDecode());
  if (oRetorno.status == 1) {
    js_buscaCidadaosMatriculados($F('as19_sequencial'));
  }
}
</script>
</html>