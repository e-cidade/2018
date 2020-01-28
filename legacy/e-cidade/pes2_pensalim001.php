<?php
/**
 *  E-cidade Software Publico para Gestao Municipal                
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

require_once 'libs/db_stdlib.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';
require_once 'libs/db_usuariosonline.php';

require_once 'dbforms/db_funcoes.php';
require_once 'dbforms/db_classesgenericas.php';


$gform = new cl_formulario_rel_pes;
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
$clrotulo->label('r44_selec');
$clrotulo->label('r44_descr');

db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
  <title>DBSeller Informática Ltda - Página Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" content="0">
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
  <form action="" name="form1" method="post" class="container">
    <fieldset>
      <legend>Pensão Alimentícia</legend>
      <table align="center" class="form-container"> 
        <tr>
          <td>
            <label>Competência: </label>
          </td>
          <td id="competencia"></td>
        </tr>
        <tr>
          <td>
            <?php db_ancora('Seleção: ','js_pesquisaSelecao(true)', 1); ?>
          </td>
          <td>
            <?php db_input('r44_selec', 10, $Ir44_selec, true, 'text', 1, 'onchange="js_pesquisaSelecao(false)" class="field-size2"'); ?>
            <?php db_input('r44_descr', 40, $Ir44_descr, true, 'text', 3, 'class="field-size7"'); ?>
          </td>
        </tr>
        <tr>
          <td title="Tipo">
            <label>Tipo de Folha: </label>
          </td>
          <td align="left">
            <?php
              $aTipoFolha = array(
                's' => 'Salário',
                'c' => 'Complementar',
                '3' => '13o. Salário',
                'r' => 'Rescisão'
              );

              if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
                $aTipoFolha['u'] = 'Suplementar';
              }

              db_select('tipo', $aTipoFolha, true, 4, '');
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <label>Imprime Funcionário: </label>
          </td>
          <td align="left">
            <?php
            $aImprimeFuncionarios = array(
              'n' => 'Não',
              's' => 'Sim'
            );

            db_select('func', $aImprimeFuncionarios, true, 4, '');
            ?>
          </td>
        </tr>
        <tr id="tr_tipoQuebra">
          <td title="Tipo de Quebra">
            <label>Tipo de Quebra:</label>
          </td>
          <td align="left">
            <?php
              $aTipoQuebra = array(
                'b' => 'Banco',
                'a' => 'Agência'
              );

              db_select('tipoquebra', $aTipoQuebra, true, 4, '');
            ?>
          </td>
        </tr>
        <tr id="tr_ordem">
          <td title="Ordem">
            <label>Ordem: </label>
          </td>
          <td align="left">
            <?php
            $aOrdem = array(
              'a' => 'Alfabética',
              'n' => 'Numérica'
            );

            db_select('ordem', $aOrdem, true, 4, '');
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input type="button" name="emite2" value="Processar" id="emite2" onclick="js_emite()">
  </form>
  <?php db_menu(); ?>
</body>
</html>
<script src="scripts/prototype.js"></script>
<script src="scripts/scripts.js"></script>
<script src="scripts/strings.js"></script>
<script src="scripts/classes/DBViewFormularioFolha/CompetenciaFolha.js"></script>
<script>
(function() {

  $('tr_ordem').style.display = 'none';

  var oCompetencia = new DBViewFormularioFolha.CompetenciaFolha(true);
  oCompetencia.renderizaFormulario($('competencia'));

  $('func').observe('change', function() {

    if ($(this).value == 's') {

      $('tr_tipoQuebra').style.display = 'none';
      $('tr_ordem').style.display = '';
    } else {

      $('tr_tipoQuebra').style.display = '';
      $('tr_ordem').style.display = 'none';
      $('ordem').value = 'a';
    }
  })
})();

function js_emite() {

  var aParams = [
    'selecao='    + $F('r44_selec'),
    'ordem='      + $F('ordem'),
    'func='       + $F('func'),
    'tipoquebra=' + $F('tipoquebra'),
    'tipo='       + $F('tipo'),
    'ano='        + $F('ano'),
    'mes='        + $F('mes')
  ];

  var jan = window.open(
    'pes2_pensalim002.php?' + aParams.join('&'),
    '',
    'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0'
  );
  
  jan.moveTo(0, 0);
}

function js_pesquisaSelecao(lMostra) {

  var sUrl = 'func_selecao.php?funcao_js=parent.js_mostraSelecao|r44_selec|r44_descr';
  if (!lMostra) {
    sUrl = 'func_selecao.php?pesquisa_chave=' + $F('r44_selec') + '&funcao_js=parent.js_mmostraSelecaoDescricao';
  }
  js_OpenJanelaIframe('top.corpo', 'db_iframe_selecao', sUrl, 'Pesquisa de Seleção', lMostra);
}

function js_mostraSelecao(sSelecao, sDescricao) {

  $('r44_selec').value = sSelecao;
  $('r44_descr').value = sDescricao;
  db_iframe_selecao.hide();
}

function js_mmostraSelecaoDescricao(sDescricao, lErro) {

  $('r44_descr').value = sDescricao;
  if (lErro) {
    $('r44_selec').value = '';
  }
}
</script>