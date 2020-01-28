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

$oGet = db_utils::postMemory($_GET);

$oRotulo = new rotulocampo;

$oRotulo->label("z01_numcgm");
$oRotulo->label("as19_nome");
$oRotulo->label("as19_sequencial");
$oRotulo->label("as19_horaaulasdia");

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link rel="stylesheet" type="text/css" href="estilos/grid.style.css"/>
<?php
    db_app::load("prototype.js, scripts.js, strings.js, arrays.js");
    db_app::load("datagrid.widget.js, widgets/DBAbasItem.widget.js, widgets/DBAbas.widget.js");
    db_app::load("estilos.css");
?>
</head>
<body>

<div style="margin-top: 15px;"  id='ctnAbas'></div>

<!--             CONTAINER DA ABA CURSO SOCIAL - FORMULÁRIO DE INCLUSÃO                 -->
<div id='ctnAbaCursoSocial'>
  <div class='container'>
    <form method="post" name='form1'>
      <fieldset>
        <legend>Cadastro de Curso Social</legend>
        <table class='form-container'>
          <tr>
            <td class='bold'>Curso / Oficina:</td>
            <td colspan="3">
              <?php 
              db_input('as19_nome', 75, $Ias19_nome, true, 'text', $oGet->db_opcao);
              db_input('codigoCurso', 10, $Ias19_sequencial, true, 'hidden', 3);
              ?>
            </td>
          </tr>
          <tr>
            <td class='bold'>Categoria:</td>
            <td colspan="3">
              <select id='categoriaCurso'>
                <option value="" selected="selected">Selecione</option>
              </select>
            </td>
          </tr>
          <tr>
            <td class='bold'>Data de Início: </td>
            <td> <?php db_inputdata('periodoInicial', "", "", "", true, 'text', $oGet->db_opcao,""); ?></td>
            <td class='bold'>Data de Fim: </td>
            <td> 
              <?php db_inputdata('periodoFinal', "", "", "", true, 'text', $oGet->db_opcao, 
                                 "onchange='js_validaData()'", '', '', '', '', '', "js_validaData()"); ?></td>
          </tr>
          <tr title='Número de horas por aula'>
            <td class='bold'>Horas aula:</td>
            <td colspan="3">
              <?php 
                db_input('horaAula', 10, $Ias19_horaaulasdia, true, 'text', $oGet->db_opcao);
              ?>
            </td>
          </tr>
          <tr>
            <td class='bold'>
              <?php db_ancora("Ministrante:", "js_buscaMinistrante(true); ", $oGet->db_opcao); ?>
            </td>
            <td colspan="3">
              <?php
  				      db_input('iCgmMinistrante', 10, $Iz01_numcgm, true, 'text', $oGet->db_opcao, 
  				               " onchange='js_buscaMinistrante(false);'");
  				      db_input('sNomeMinistrante', 61, '', true,'text', 3,'');
  				    ?>
            </td>
          </tr>
          <tr>
            <td class='bold'>
              <?php db_ancora("Responsável:", "js_buscaResponsavel(true); ", $oGet->db_opcao); ?>
            </td>
            <td colspan="3">
            <?php
  				      db_input('iCgmResponsavel', 10, $Iz01_numcgm, true, 'text', $oGet->db_opcao, 
  				               " onchange='js_buscaResponsavel(false);'");
  				      db_input('sNomeResponsavel', 61, '', true,'text', 3,'');
  				    ?>
            </td>
          </tr>
          <tr>
            <td colspan="4" title="Marque os dias da semana em que o curso possui aula.">
              <fieldset class='separator' style="text-align: center;">
                <legend>Dias da Semana</legend>
                <input class='diaSemana' id='domingo' type="checkbox" name="Domingo" value="1">
                <label for='domingo'>Domingo</label>
                <input class='diaSemana' id='segunda' type="checkbox" name="segunda" value="2">
                <label for='segunda'>Segunda-feira</label>
                <input class='diaSemana' id='terca'   type="checkbox" name="terca"   value="3">
                <label for='terca'>Terça-feira</label>
                <input class='diaSemana' id='quarta'  type="checkbox" name="quarta"  value="4">
                <label for='quarta'>Quarta-feira</label>
                <input class='diaSemana' id='quinta'  type="checkbox" name="quinta"  value="5">
                <label for='quinta'>Quinta-feira</label>
                <input class='diaSemana' id='sexta'   type="checkbox" name="sexta"   value="6">
                <label for='sexta'>Sexta-feira</label>
                <input class='diaSemana' id='sabado'  type="checkbox" name="sabado"  value="7">
                <label for='sabado'>Sábado</label>
                
              </fieldset>
            </td>
          </tr>
          <tr>
            <td colspan="4">
              <fieldset class='separator'>
                <legend>Detalhamento do Curso</legend>
                <?php 
                  db_textarea('detalhamento', 5, 84, '', true, 'text', $oGet->db_opcao);
                ?>               
              </fieldset>
            </td>
          </tr>
        </table>
      </fieldset>
      <input type="button" id='salvarCurso' name='salvar' value='Salvar' />
      <input type="button" id='excluirCurso' name='excluir' value='Excluir' />
      <input type="button" id='pesquisar' name='pesquisar' value='Pesquisar' onclick="js_pesquisaCurso();" />
    </form>
  </div>
</div>

<!--             CONTAINER DA ABA AGENDA - LISTA DOS DIAS DE AULA DO CURSO                 -->
<div id='ctnAbaAgendaCurso'>
  <div class='container'>
    <form method="post" name='form2'>
      <fieldset>
        <legend>Agenda de aulas</legend>
        <table class='form-container'>
          <tr>
            <td class="bold">Adicionar Dia:</td>
            <td>
            <?php db_inputdata('novoDia', "", "", "", true, 'text', $oGet->db_opcao); ?></td>
            </td>
            <td>
              <input type="button" id='adicionarDia' name='adicionar' value='Adicionar' />
            </td>
          </tr>
        </table>
      </fieldset>
    </form>
    <fieldset>
      <legend>Dias lançados</legend>
      <div id='ctnGridAgenda'>
      </div>
    </fieldset>
  </div>
</div>
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script type="text/javascript">

var sUrlRPC = 'soc4_cursosocial.RPC.php';
var oGet    = js_urlToObject();

var oGridDiasAula = new DBGrid("gridDiasAula");
oGridDiasAula.nameInstance = "oGridDiasAula";
oGridDiasAula.setCellWidth(new Array('40%', '40%', '20%'));
oGridDiasAula.setCellAlign(new Array('left', 'left', 'center'));
oGridDiasAula.setHeader(new Array('Dia Aula', 'Dia Semana', 'Ação'));
oGridDiasAula.setHeight(400);
oGridDiasAula.show($('ctnGridAgenda'));

function js_buscaCategorias() {

  var oParametro  = new Object();
  oParametro.exec = 'buscaCategoriaCurso';
  js_divCarregando("Aguarde, buscando categoria de curso.", "msgBox");

  new Ajax.Request(sUrlRPC,
                   {
                    method:     'post',
                    parameters: 'json='+Object.toJSON(oParametro),
                    onComplete: js_retornoCategoria
                   }
                  );
}

function js_retornoCategoria(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oAjax.responseText+')');

  // Limpa o combo de categoria
  $('categoriaCurso').options.length = 0;

  // Recria o option selecione
  var oOption = document.createElement('option');
  oOption.setAttribute('value', '');
  oOption.update('Selecione');
  $('categoriaCurso').appendChild(oOption);

  
  oRetorno.aCategorias.each(function(oCategoria) {

    var oOption = document.createElement('option');
    oOption.setAttribute('value', oCategoria.iCodigo);
    oOption.update(oCategoria.sDescricao.urlDecode());
    $('categoriaCurso').appendChild(oOption);  
  });
}

function js_pesquisaCurso() {

  var sUrl  = 'func_cursosocial.php?';
      sUrl += 'funcao_js=parent.js_buscaDadosCurso|as19_sequencial';
  js_OpenJanelaIframe('', 'db_iframe_cursosocial', sUrl, 'Pesquisa Cursos Sociais', true);
}

/**
 * Busca os dados do curso 
 * @param iCurso código do curso
 */
function js_buscaDadosCurso(iCurso) {

   db_iframe_cursosocial.hide();
   
   var oParametro          = new Object();
   oParametro.exec         = 'getDadosCurso';
   oParametro.iCodigoCurso = iCurso;

   js_divCarregando("Aguarde... buscando informações do curso.", "msgBox");

   new Ajax.Request(sUrlRPC,
                    {method:'post',
                     parameters: 'json='+Object.toJSON(oParametro),
                     onComplete: js_retornoDadosCurso
                    }
                   );
}
 
function js_retornoDadosCurso(oAjax) {
  
  js_removeObj("msgBox");
  var oRetorno = eval('('+oAjax.responseText+')');

  $('as19_nome').value        = oRetorno.oCursoSocial.sNomeCurso.urlDecode();
  $('codigoCurso').value      = oRetorno.oCursoSocial.iCodigo;
  $('categoriaCurso').value   = oRetorno.oCursoSocial.iCategoria;
  $('periodoInicial').value   = oRetorno.oCursoSocial.dtInicio;
  $('periodoFinal').value     = oRetorno.oCursoSocial.dtFim;
  $('horaAula').value         = oRetorno.oCursoSocial.nHoraAula;
  $('iCgmMinistrante').value  = oRetorno.oCursoSocial.iMinistrante;
  $('sNomeMinistrante').value = oRetorno.oCursoSocial.sMinistrante.urlDecode();
  $('iCgmResponsavel').value  = oRetorno.oCursoSocial.iResponsavel;
  $('sNomeResponsavel').value = oRetorno.oCursoSocial.sResponsavel.urlDecode();
  $('detalhamento').value     = oRetorno.oCursoSocial.sDetalhamento.urlDecode();

  /**
   * Marca os checkbox 
   */
  $$('input[type="checkbox"]').each(function (oElemento, i) {

    if (oRetorno.oCursoSocial.aDiasSemana.in_array(oElemento.value)) {
      oElemento.checked = true;
    }
  });

  js_buscaDiasAgendaCurso();
  oAbaAgenda.lBloqueada = false;
}

/**
 * Valida a acção do menu (inclusão, alteração, exclusão) bloqueando ou liberando campos
 */
function js_init() {

  $('excluirCurso').style.display = 'none';
  $('pesquisar').style.display    = 'none';
  js_buscaCategorias();
  
  switch (oGet.db_opcao) {
    
    case '2':

      $('pesquisar').style.display = '';  
      js_pesquisaCurso();
      break;

    case '3':

      js_pesquisaCurso();
      $('pesquisar').style.display    = '';
      $('salvarCurso').style.display  = 'none';
      $('excluirCurso').style.display = '';
      $('categoriaCurso').setAttribute("disabled", "disabled");
      $('adicionarDia').setAttribute("disabled", "disabled");

      $$('input[type="checkbox"]').each(function (oElemento, i) {

        oElemento.disabled = true;
      });
      break;
  }
}

js_init();

/**
 * Função de pesquisa para o Ministrante
 */
function js_buscaMinistrante(lMostra) {

  var sUrl = 'func_nome.php?';
  if(lMostra) {

    sUrl += 'funcao_js=parent.js_mostraMinistrante1|z01_numcgm|z01_nome';
    js_OpenJanelaIframe('', 'func_nome', sUrl, 'Pesquisa Ministrante', true);
  } else  {
    
    if($F('iCgmMinistrante') != '') {

      sUrl += 'pesquisa_chave='+$F('iCgmMinistrante');
      sUrl += '&funcao_js=parent.js_mostraMinistrante';
      js_OpenJanelaIframe('','func_nome', sUrl,'Pesquisa Ministrante',false);
    } else {
      $('iCgmMinistrante').value = "";
    }
  }
}

function js_mostraMinistrante(lErro, sNome) {

  $('sNomeMinistrante').value = sNome;
  if (lErro) {

    $('iCgmMinistrante').value  = '';
    $('sNomeMinistrante').value = sNome; 
    $('sNomeMinistrante').focus(); 
  }
}

function js_mostraMinistrante1(iCgm, sNome) {
  
  $('iCgmMinistrante').value  = iCgm;
  $('sNomeMinistrante').value = sNome;
  func_nome.hide();
}


/**
 * Função de pesquisa para o Responsável
 */
function js_buscaResponsavel(lMostra) {

  var sUrl = 'func_nome.php?';
  if(lMostra) {

    sUrl += 'funcao_js=parent.js_mostraResponsavel1|z01_numcgm|z01_nome';
    js_OpenJanelaIframe('', 'func_nome', sUrl, 'Pesquisa Ministrante', true);
  } else  {
    
    if($F('iCgmResponsavel') != '') {

      sUrl += 'pesquisa_chave='+$F('iCgmResponsavel');
      sUrl += '&funcao_js=parent.js_mostraResponsavel';
      js_OpenJanelaIframe('top.corpo','func_nome', sUrl,'Pesquisa Ministrante',false);
    } else {
      $('iCgmResponsavel').value = "";
    }
  }
}

function js_mostraResponsavel(lErro, sNome) {

  $('sNomeResponsavel').value = sNome;
  if (lErro) {

    $('iCgmResponsavel').value  = '';
    $('sNomeResponsavel').value = sNome; 
    $('sNomeResponsavel').focus(); 
  }
}

function js_mostraResponsavel1(iCgm, sNome) {
  
  $('iCgmResponsavel').value  = iCgm;
  $('sNomeResponsavel').value = sNome;
  func_nome.hide();
}

/**
 * Valida todos os campos obrigatórios do formulário
 */
function js_validaFormularioCurso() {

  if ($F('as19_nome') == '') {

    alert('Defina o nome do curso.');
    return false;
  }        
  if ($F('categoriaCurso') == '') {

    alert('Selecione uma categoria para o curso.');
    return false;
  }   

  if (!js_validaData()) {
    return false;  
  }
  
  if ($F('horaAula') == '') {
    
    alert('Informe a carga horária para um dia de aula.');
    return false;
  }          
  
  if ($F('iCgmMinistrante') == '') {

    alert('Selecione o ministrante para o curso.');
    return false;
  } 

  if ($F('iCgmResponsavel') == '') {
    
    alert('Selecione o responsável para o curso.');
    return false;
  }
    
  if ($F('detalhamento') == '') {

    alert('Informe um detalhamento para o curso.');
    return false;
  }     

  var lTemUmSelecionado = false;
  $$('input[type="checkbox"]').each(function (oElemento, i) {

    if (oElemento.checked) {
      lTemUmSelecionado = true;
    }
  });

  if (!lTemUmSelecionado) {
    
    alert('Selecione ao menos um dia de aula na semana.');
    return false;
  }
  return true;
}

/**
 * Valida o intervalo de datas 
 */
function js_validaData() {

  if ($F('periodoInicial') == '') {
    
    alert('Informe o período inicial.');
    return false;
  }

  if ($F('periodoFinal') == '') {

    alert('Informe o período final.');
    return false;
  }
  
  if (js_comparadata($F('periodoInicial'), $F('periodoFinal'), '>')) {

    $('periodoFinal').value = '';
    alert('Período inicial não pode ser maior que o período final.');
    return false;
  }
  return true;
}

/**
 * Salva os dados do curso
 */
$('salvarCurso').observe("click", function () {

  if (!js_validaFormularioCurso()) {
    return false;
  }
  
  var oParametro  = new Object();
  oParametro.exec = 'salvarCurso';

  oParametro.iCodigoCurso  = $F('codigoCurso');
  oParametro.sNomeCurso    = $F('as19_nome');
  oParametro.iCategoria    = $F('categoriaCurso');   
  oParametro.sDtInicio     = $F('periodoInicial');   
  oParametro.sDtFim        = $F('periodoFinal');     
  oParametro.nHoraAula     = $F('horaAula');
  oParametro.iMinistrante  = $F('iCgmMinistrante');  
  oParametro.iResponsavel  = $F('iCgmResponsavel');
  oParametro.sDetalhamento = encodeURIComponent(tagString($F('detalhamento')));     
  oParametro.aDiaSemana    = new Array();
  
  $$('input[type="checkbox"]').each(function (oElemento, i) {

    if (oElemento.checked) {
      oParametro.aDiaSemana.push(oElemento.value);
    }
  });
  
  js_divCarregando("Aguarde, salvando os dados.", "msgBox");
  new Ajax.Request(sUrlRPC,
      {method:'post',
       parameters: 'json='+Object.toJSON(oParametro),
       onComplete: js_retornoSalvarCurso
      }
     );  
  
});

function js_retornoSalvarCurso(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oAjax.responseText+')');

  alert(oRetorno.message.urlDecode());

  /**
   * Se entramos como uma inclusão, devemos gerar os dias de aula (agenda) do curso
   */
  if (oGet.db_opcao == 1) {

    $('codigoCurso').value    = oRetorno.iCodigoCurso;
    oAbaAgenda.lBloqueada = false;
    js_gerarAgenda(oRetorno.iCodigoCurso);
  }
}


/**
 * Limpa campos do formulário
 */
function js_limpaDadosFormulario() {
  
  form1.reset();  
  form2.reset();
  oGridDiasAula.clearAll(true);
}

 
/**
 * Monta a agenda do curso 
 * só chamada após inclusão
 */
function js_gerarAgenda(iCodigoCurso) {

  var oParametro          = new Object();
  oParametro.exec         = 'gerarAgenda';
  oParametro.iCodigoCurso = iCodigoCurso;
  
  js_divCarregando("Aguarde, gerando agenda de aulas.", "msgBox");
  new Ajax.Request(sUrlRPC,
      {method:'post',
       parameters: 'json='+Object.toJSON(oParametro),
       onComplete: js_retornoGerarAgenda
      }
     );
}

function js_retornoGerarAgenda(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval('('+oAjax.responseText+')');
  js_buscaDiasAgendaCurso();
}

/**
 * Busca os dias de aula do curso (agenda)
 * Usado para montar a grid
 */
function js_buscaDiasAgendaCurso() {

  var oParametro          = new Object();
  oParametro.exec         = 'getDiasAulaCurso';
  oParametro.iCodigoCurso = $F('codigoCurso');

  js_divCarregando("Aguarde, buscando agenda.", "msgBox");
  new Ajax.Request(sUrlRPC,
      {method:'post',
       parameters: 'json='+Object.toJSON(oParametro),
       onComplete: js_retornoDiasAgendaCurso
      }
     );
}

function js_retornoDiasAgendaCurso(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval('('+oAjax.responseText+')');
  
  oGridDiasAula.clearAll(true);
  oRetorno.aDias.each(function (oDiaAula) {

    var oAcao   = document.createElement('input');
    oAcao.type  = "button";
    oAcao.value = "E";
    oAcao.name  = "excluir";
    oAcao.id    = "excluirDiaAula" + oDiaAula.iCodigo;
    oAcao.setAttribute("onclick","js_excluiDiaAgenda("+ oDiaAula.iCodigo +")");

    if (oGet.db_opcao == 3) {
      oAcao.setAttribute("disabled", "disabled");
    }

    var aLinha = new Array();
    aLinha.push(oDiaAula.dtAula);
    aLinha.push(oDiaAula.sDiaSemana.urlDecode());
    aLinha.push(oAcao.outerHTML);

    oGridDiasAula.addRow(aLinha);
  });
  oGridDiasAula.renderRows();  
}

/**
 * Remove o curso
 */
$('excluirCurso').observe("click", function () {
  
  var oParametro            = new Object();
  oParametro.exec           = 'removerCurso' ;
  oParametro.iCodigoCurso   = $F('codigoCurso');

  js_divCarregando("Aguarde, excluindo curso.", "msgBox");
  new Ajax.Request(sUrlRPC,
      {method:'post',
       parameters: 'json='+Object.toJSON(oParametro),
       onComplete: js_retornoExcluiCurso
      }
     );
});

function js_retornoExcluiCurso(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval('('+oAjax.responseText+')');
  alert(oRetorno.message.urlDecode());

  if (oRetorno.status == 1) {
    
    js_limpaDadosFormulario();
    js_pesquisaCurso();
  }
  
}


/** ************************** **
 *  Funções da ABA AGENDA       *
 ** ************************** **/

/**
 * Exclui um dia de aula 
 * @param iCodigoDiaAula é se o sequencial da tabela cursosocialaula
 */
function js_excluiDiaAgenda (iCodigoDiaAula) {

  var oParametro            = new Object();
  oParametro.exec           = 'removerDiaAula' ;
  oParametro.iCodigoCurso   = $F('codigoCurso');
  oParametro.iCodigoDiaAula = iCodigoDiaAula;

  js_divCarregando("Aguarde, excluindo dia da agenda.", "msgBox");
  new Ajax.Request(sUrlRPC,
      {method:'post',
       parameters: 'json='+Object.toJSON(oParametro),
       onComplete: js_retornoExcluiDiaAgenda
      }
     );
}

function js_retornoExcluiDiaAgenda(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval('('+oAjax.responseText+')');
  alert(oRetorno.message.urlDecode());
  js_buscaDiasAgendaCurso();
}

/**
 * Valida se o novo dia esta dentro do interválo de períodos do curso
 */
function js_validaDataEntrePeriodo(dtNova) {

  if (dtNova == '') {
    
    alert("Informe o dia.");
    return false;
  }
   
  if (js_comparadata($F('periodoInicial'), dtNova, '>')) {

    $('novoDia').value = '';
    alert('Dia:' + dtNova + ' é menor que o período inicial: ' + $F('periodoInicial') + ' do curso.');
    return false;
  }
  
  if (js_comparadata($F('periodoFinal'), dtNova, '<')) {

    $('novoDia').value = '';
    alert('Dia:' + dtNova + ' é maior que o período final: ' + $F('periodoFinal') + ' do curso.');
    return false;
  }
  
  return true;
}

/**
 * Adiciona um dia a agenda do curso
 */
$('adicionarDia').observe("click", function () {

  if ( !js_validaDataEntrePeriodo($F('novoDia')) ) {
    return false;
  }  

  var oParametro          = new Object();
  oParametro.exec         = 'adicionarDiaAula' ;
  oParametro.iCodigoCurso = $F('codigoCurso');
  oParametro.dtNova       = $F('novoDia');

  js_divCarregando("Aguarde, adicionado de aula.", "msgBox");
  new Ajax.Request(sUrlRPC,
      {method:'post',
       parameters: 'json='+Object.toJSON(oParametro),
       onComplete: js_retornoAdicionarDiaAgenda
      }
     );
});

function js_retornoAdicionarDiaAgenda(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval('('+oAjax.responseText+')');
  alert(oRetorno.message.urlDecode());
  $('novoDia').value = '';
  js_buscaDiasAgendaCurso();
}

/**
 * Cria abas
 */
var oDBAba     = new DBAbas($('ctnAbas'));
var oAbaCurso  = oDBAba.adicionarAba("Curso Social", $('ctnAbaCursoSocial'));
var oAbaAgenda = oDBAba.adicionarAba("Agenda", $('ctnAbaAgendaCurso'));

oAbaAgenda.lBloqueada = true;
</script>
</html>