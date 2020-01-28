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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oRotulo = new rotulocampo;
$oRotulo->label('s115_c_cartaosus');
$oRotulo->label('z01_i_cgsund');
$oRotulo->label('z01_v_nome');
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/dbautocomplete.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <form name='form1' class="container">
    <fieldset>
      <legend>Consulta Geral da Saúde</legend>
      <table class="form-container">
        <tr>
          <td nowrap title="<?=$Ts115_c_cartaosus?>">
            <label for="s115_c_cartaosus">
              <?=$Ls115_c_cartaosus?>
            </label>
          </td>
          <td nowrap>
            <?php
            db_input('s115_c_cartaosus', 10, $Is115_c_cartaosus, true, 'text', 1, ' onchange="js_getCgsCns();"');
            ?>
          </td>
        </tr>
        <tr>
          <td title="<?=$Tz01_i_cgsund?>">
            <label for="z01_i_cgsund">
              <?php
              db_ancora($Lz01_i_cgsund, 'js_pesquisaz01_i_cgsund(true);', 1);
              ?>
            </label>
          </td>
          <td>
            <?php
            db_input('z01_i_cgsund', 10, $Iz01_i_cgsund, true, 'text', 1, 'onchange="js_pesquisaz01_i_cgsund(false);"');
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <label for="z01_v_nome">Nome:</label>
          </td>
          <td>
            <?php
            db_input('z01_v_nome', 50, $Iz01_v_nome, true, 'text', 1, '');
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input name="confirmar" id="confirmar" type="button" value="Confirmar" onclick="js_confirmar();">
    <input name="limpar"    id="limpar"    type="button" value="Limpar"    onclick="js_limpar();">
  </form>
  <?php
  db_menu();
  ?>
</body>
</html>

<script>
function js_confirmar() {

  if( empty( $F('z01_i_cgsund') ) ) {

    alert( 'Nenhum CGS informado para consulta.' );
    return;
  }

  if ((screen.width >= 900) && (screen.height >= 700)) {
    iLinhas = 8;
  } else {
    iLinhas = 5;
  }

  iTop    = 20;
  iLeft   = 5;
  iHeight = screen.availHeight-210;
  iWidth  = screen.availWidth-35;

  sChave  = 'z01_i_cgsund='+$F('z01_i_cgsund');
  sChave += '&iLinhas='+iLinhas;

  js_OpenJanelaIframe('', 'db_iframe_consulta', 'sau3_consultasaude002.php?'+sChave,
                      'Consulta Geral da Saúde', true, iTop, iLeft, iWidth, iHeight
                     );
}

function js_limpar() {

  $('s115_c_cartaosus').value = '';
	$('z01_i_cgsund').value     = '';
  $('z01_v_nome').value       = '';
}

function js_pesquisaz01_i_cgsund(mostra) {

  var sTitulo = 'Pesquisa CGS';

  if(mostra == true) {

    js_OpenJanelaIframe(
                         '',
                         'db_iframe_cgs_und',
                         'func_cgs_und.php?funcao_js=parent.js_mostracgs1|z01_i_cgsund|z01_v_nome',
                         sTitulo,
                         mostra
                       );
  } else {

    if($F('z01_i_cgsund') != '') {

       js_OpenJanelaIframe(
                            '',
                            'db_iframe_cgs_und',
                            'func_cgs_und.php?pesquisa_chave='+$F('z01_i_cgsund')+'&funcao_js=parent.js_mostracgs',
                            sTitulo,
                            mostra
                          );
    } else {
      $('z01_v_nome').value = '';
    }
  }
}

function js_mostracgs(chave, erro) {

  $('z01_v_nome').value = chave;
  if(erro == true) {

    $('z01_i_cgsund').focus(); 
    $('z01_i_cgsund').value = '';
  }
}

function js_mostracgs1(chave1, chave2) {

  $('z01_i_cgsund').value = chave1;
  $('z01_v_nome').value   = chave2;
  db_iframe_cgs_und.hide();
}

function js_getCgsCns() {

  if ($F('s115_c_cartaosus') == '') {
    return false;
  }

  if ($F('s115_c_cartaosus').length != 15 || isNaN($F('s115_c_cartaosus'))) {

    alert('Número de CNS inválido para busca.');
    $('s115_c_cartaosus').value = '';
    return false;
  }

  var oParam      = new Object();
      oParam.exec = "getCgsCns";
      oParam.iCns = $F('s115_c_cartaosus');

  AjaxRequest.create('sau4_ambulatorial.RPC.php', oParam, function(oRetorno, lErro) {

    if(empty(oRetorno.z01_i_cgsund)) {

      alert('Nenhum CGS encontrado com o cartão SUS informado.');
      $('s115_c_cartaosus').focus();
      return;
    }
  }).execute();
}

function js_retornogetCgsCns(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");
  if (oRetorno.z01_i_cgsund == '') {

    alert('CNS não encontrado.');
    return false;
  }

  $('z01_i_cgsund').value = oRetorno.z01_i_cgsund;
  $('z01_v_nome').value   = oRetorno.z01_v_nome.urlDecode();
  return true;
}

// Autocomplete do CGS
oAutoComplete = new dbAutoComplete($('z01_v_nome'), 'sau4_pesquisanome.RPC.php?tipo=1');
oAutoComplete.setTxtFieldId($('z01_i_cgsund'));
oAutoComplete.setHeightList(390);
oAutoComplete.show();

$('s115_c_cartaosus').className = 'field-size3';
$('z01_i_cgsund').className     = 'field-size3';
$('z01_v_nome').className       = 'field-size7';

$('s115_c_cartaosus').oninput = function() {
  js_ValidaCampos($('s115_c_cartaosus'), 1, 'Cartão SUS', true, true);
}
</script>