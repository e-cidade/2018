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
    db_app::load("prototype.js, scripts.js, strings.js, arrays.js, datagrid.widget.js, webseller.js");
    db_app::load("estilos.css");
?>
<script type="text/javascript" >
require_once('scripts/widgets/DBToggleList.widget.js');
</script>
</head>
<body>
<div class='container'>
  <form method="post" name='form1'>
    <fieldset>
      <legend>Lista de Presença</legend>
      <table class='form-container' >
        <tr>
          <td class='bold' class='field-size2'>
            <?php db_ancora("Ministrante:", "js_buscaMinistrante(true); ", 1); ?>
          </td>
          <td colspan="3">
            <?php
				      db_input('iCgmMinistrante', 10, $Iz01_numcgm, true, 'text', 1, " onchange='js_buscaMinistrante(false);'");
				      db_input('sNomeMinistrante', 61, '', true,'text', 3,'');
				    ?>
          </td>
        </tr>
        <tr>
          <td class='bold'>Curso:</td>
          <td>
            <select id='cursosMinistrante' onchange="buscaDadosCurso();">
              <option value='' selected="selected">Selecione</option>
            </select>
          </td>
        </tr>
        <tr>
          <td class='bold'>Imprime Assinatura:</td>
          <td>
            <select id='assinaturaMinistrante'>
              <option value='S' selected="selected">Sim</option>
              <option value='F' >Não</option>
            </select>
          </td>
        </tr>
        <tr>
          <td class='bold'>Total de Aulas:</td>
          <td><b><?php db_input("iTotalAulas", 10, '', true, 'text',3); ?></b></td>
        </tr>
        <tr>
          <td class='bold'>Modelo:</td>
          <td>
            <?php
              $aModelos = array(1=>'Por Mês',2=>'Total');
              db_select('iModelo', $aModelos, true, 1);
            ?>
          </td>
        </tr>
      </table>
      <fieldset class='separator'>
        <legend><b>Mês</b></legend>
        <div id="ctnMeses"> </div>
      </fieldset>
    </fieldset>
    <input type="button" id='btnImprimir' name='btnImprimir' value='Imprimir' />
  </form>
</div>
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>

<script type="text/javascript">

var sUrlRPC = 'soc4_cursosocial.RPC.php';
/**
 * Função de pesquisa para o Ministrante
 */
function js_buscaMinistrante(lMostra) {

  var sUrl = 'func_ministrantecursosocial.php?';
  if(lMostra) {

    sUrl += 'funcao_js=parent.js_mostraMinistrante1|as19_ministrante|z01_nome';
    js_OpenJanelaIframe('', 'db_iframe_ministrantecursosocial', sUrl, 'Pesquisa Ministrante', true);
  } else  {

    if($F('iCgmMinistrante') != '') {

      sUrl += 'pesquisa_chave='+$F('iCgmMinistrante');
      sUrl += '&funcao_js=parent.js_mostraMinistrante';
      js_OpenJanelaIframe('','db_iframe_ministrantecursosocial', sUrl,'Pesquisa Ministrante',false);
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
  } else {
    js_buscarCursosMinistrante($F('iCgmMinistrante'));
  }
}

function js_mostraMinistrante1(iCgm, sNome) {

  $('iCgmMinistrante').value  = iCgm;
  $('sNomeMinistrante').value = sNome;
  db_iframe_ministrantecursosocial.hide();
  js_buscarCursosMinistrante(iCgm);
}

/**
 * Busca os cursos do ministrante
 */
function js_buscarCursosMinistrante(iMinistrante) {

  var oParametro          = new Object();
  oParametro.exec         = 'getCursoMinistrante';
  oParametro.iMinistrante = iMinistrante;

  js_divCarregando("Aguarde, buscando cursos.", "msgBox");
  new Ajax.Request(sUrlRPC,
      {method:'post',
       parameters: 'json='+Object.toJSON(oParametro),
       onComplete: js_retornoCursosMinistrante
      }
     );
}

function js_retornoCursosMinistrante(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval('('+oAjax.responseText+')');

  $('cursosMinistrante').options.length = 0;

  var oOption       = document.createElement('option');
  oOption.value     = '';
  oOption.innerHTML = 'Selecione';
  $('cursosMinistrante').appendChild(oOption);

  var iPosicao = null;
  oRetorno.aCursos.each(function (oCurso) {

    var oOption       = document.createElement('option');
    oOption.value     = oCurso.as19_sequencial;
    oOption.innerHTML = oCurso.as19_nome.urlDecode();
    $('cursosMinistrante').appendChild(oOption);
    iPosicao = oCurso.as19_sequencial;

  });

  if (oRetorno.aCursos.length == 1) {
    
    $('cursosMinistrante').value = iPosicao;
    buscaDadosCurso();
  }

}

/**
 * Busca o total de aulas do curso
 */
function buscaDadosCurso () {

  var oParametro    = new Object();
  oParametro.exec   = 'getMesesDeAbrangencia';
  oParametro.iCurso = $F('cursosMinistrante');

  if (oParametro.iCurso == 0) {
    $('iTotalAulas').value = '';
    oToggle.clearAll();
    return false;
  }

  js_divCarregando("Aguarde, buscando meses de aulas.", "msgBox");
  new Ajax.Request(sUrlRPC,
                  {method:'post',
                   parameters: 'json='+Object.toJSON(oParametro),
                   onComplete: js_retornoMeses
                  }
                 );
 }
function js_retornoMeses(oAjax) {

  js_removeObj('msgBox');

  aCamposSelecionar = new Array();
  var oRetorno      = eval('('+oAjax.responseText+')');

  oToggle.clearAll();

	for (var iIndice in oRetorno.aMeses) {

		for (var iIndice2 in oRetorno.aMeses[iIndice]) {
			aCamposSelecionar.push({iAno:iIndice, sMes:oRetorno.aMeses[iIndice][iIndice2].urlDecode(), iMes:iIndice2});
		}
	}

	aCamposSelecionar.each(function(oCampo) {
	  oToggle.addSelect(oCampo);
	});

  oToggle.renderRows();
  $('iTotalAulas').value = oRetorno.iTotalAulas;
}

form1.reset();

$('btnImprimir').observe("click", function () {

  if ($F('cursosMinistrante') == "") {

    alert("Selecione o curso para poder emitir o relatório.");
    return false;
  }

  if (oToggle.getSelected().length == 0) {

    alert('Escolha ao menos um mês para emissão da lista de presença.');
    return false;
  }

  var iMinistrante = $F('iCgmMinistrante');
  var iCurso       = $F('cursosMinistrante');
  var iModelo      = $F('iModelo');
  var iTotalAulas  = $F('iTotalAulas');
  var aMeses       = Object.toJSON(oToggle.getSelected());
  var lAssinatura  = $F('assinaturaMinistrante');

  var sUrl  = 'soc2_listapresenca002.php?iMinistrante='+iMinistrante+'&iCurso='+iCurso+'&iModelo='+iModelo;
      sUrl += '&iTotalAulas='+iTotalAulas+'&aMeses='+aMeses+'&lAssinatura='+lAssinatura;
  
  jan = window.open(sUrl, '', 
                    'width='+(screen.availWidth-5)+
                    ',height='+(screen.availHeight-40)+
                    ',scrollbars=1,location=0');
  jan.moveTo(0,0);
});


//------------------------------------------------------------------------------------------------------------
var oToggle = new DBToggleList([{
                                  sId:    'iAno',
                                  sLabel: 'Ano'
                                 },
                                 {
                                   sId:    'sMes',
                                   sLabel: 'Mês'
                                 }
                                 ]);

oToggle.closeOrderButtons();
oToggle.show($('ctnMeses'));
</script>
</html>