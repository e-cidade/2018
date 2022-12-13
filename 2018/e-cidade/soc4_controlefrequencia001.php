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
    db_app::load("classes/social/DBViewControleFrequencia.classe.js");
    db_app::load("estilos.css");
?>
</head>
<body>
<div class='container'>
  <form method="post" name='form1'>
    <fieldset>
      <legend>Controle de Frequência</legend>
      <table class='form-container'>
        <tr>
          <td class='bold'>
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
          <td class='bold'>Curso</td>
          <td>
            <select id='cursosMinistrante' >
              <option value='' selected="selected">Selecione</option>
            </select>
          </td>
        </tr>
      </table>
    </fieldset>
    <input type="button" id='btnFrequencia' name='btnFrequencia' value='Controle de Frequência' >
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
 * Monta a agenda do curso 
 * só chamada após inclusão
 */
function js_buscarCursosMinistrante(iMinistrante) {

  var oParametro          = new Object();
  oParametro.exec         = 'getCursoMinistrante';
  oParametro.iMinistrante = iMinistrante;
  
  js_divCarregando("Aguarde, gerando agenda de aulas.", "msgBox");
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
  } 
  
}

form1.reset();

$('btnFrequencia').observe("click", function () {

  if ($F('cursosMinistrante') == "") {

    alert("Selecione o curso para poder lançar as ausências.");
    return false;
  }

  var sCurso          = $('cursosMinistrante').options[$('cursosMinistrante').selectedIndex].innerHTML;
  var sMinistrante    = $('sNomeMinistrante').value;
  oControleFrequencia = new DBViewControleFrequencia($F('cursosMinistrante'), sCurso, sMinistrante);
  oControleFrequencia.show();
  
});

</script>
</html>