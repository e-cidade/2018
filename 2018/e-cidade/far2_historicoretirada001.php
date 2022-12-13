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
require_once(modification("dbforms/db_classesgenericas.php"));
require_once(modification("classes/db_far_farmacia_classe.php"));

db_postmemory($_POST);

$clrotulo = new rotulocampo;
$clrotulo->label("z01_i_cgsund");
$clrotulo->label("fa06_i_matersaude");
$clrotulo->label("fa13_i_departamento");
$clrotulo->label("fa13_i_codigo");
$clrotulo->label("descrdepto");
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <div class="container">
    <form name="form1" method="post" action="">
      <fieldset>
        <legend>Período de Retirada</legend>
        <table class="form-container">
          <tr>
            <td align='center' nowrap>
              <label for="data_inicio">Início:</label>
              <?php
              db_inputdata('data_inicio','','','',true,'text',1,"");
              ?>
              <label for="data_fim">Fim:</label>
              <?php
              db_inputdata('data_fim','','','',true,'text',1,"");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>

      <table class="form-container">
        <tr>
          <td style="display: none;">
            <fieldset>
              <legend align='left'>Ordenar por</legend>
              <table class="subtable">
                <tr>
                  <td align='center'>
                    <?
                    $aX = array('1'=>'CGS', '2'=>'MEDICAMENTO');
                    db_select('ordem',$aX,true, 1, '');
                    ?>
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
        </tr>

        <tr>
          <td colspan="2">
            <fieldset>
              <legend>Pacientes</legend>
              <table class="subtable">
                <tr>
                  <td >
                    <?php
                      db_ancora($Lz01_i_cgsund, "js_pesquisaz01_i_cgsund(true);", "");
                    ?>
                  </td>
                  <td nowrap>
                    <?php
                    db_input('z01_i_cgsund',10, $Iz01_i_cgsund, true, 'text', 1, " onchange='js_pesquisaz01_i_cgsund(false);'");
                    db_input('z01_v_nome',  55, $Iz01_v_nome,   true, 'text', 3);
                    ?>
                    <input type='button' value='Lançar' name='lancar_cgs' id='lancar_cgs'>
                  </td>
                </tr>

                <tr>
                  <td>
                    &nbsp;
                  </td>
                  <td>
                    <select multiple size='8' name='select_cgs[]' id='select_cgs' style="width: 80%;" onDblClick="js_excluir_item_cgs();">
                    </select>
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
        </tr>

        <tr>
          <td colspan="2">
            <fieldset>
              <legend>Medicamentos</legend>
              <table class="subtable">
                <tr>
                  <td>
                    <?php
                      db_ancora($Lfa06_i_matersaude, "js_pesquisafa01_i_medicamento(true);", "");
                    ?>
                  </td>
                  <td nowrap>
                    <?php
                      db_input('fa01_i_codigo', 10, $Ifa01_i_codigo, true, 'text', 1, " onchange='js_pesquisafa01_i_medicamento(false);'");
                      db_input('m60_descr',     55, $Im60_descr,     true, 'text', 3);
                    ?>
                    <input type='button' value='Lançar' name='lancar_medicamento' id='lancar_medicamento'>
                  </td>
                </tr>

                <tr>
                  <td>
                    &nbsp;
                  </td>
                  <td>
                    <select multiple size='8' name='select_medicamento[]' id='select_medicamento' style="width: 80%;" onDblClick="js_excluir_item_medicamento();">
                    </select>
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
        </tr>

        <tr>
          <td colspan="2">
            <fieldset>
              <legend>Departamento</legend>
              <table class="subtable">
                <tr>
                  <td>
                    <?php
                      db_ancora("Departamento:", "js_pesquisaDepartamento(true);", "");
                    ?>
                  </td>
                  <td nowrap>
                    <?php
                    $sScript = " onchange='js_pesquisaDepartamento(false);'";
                    db_input('fa13_i_departamento', 10, $Ifa13_i_departamento, true, 'text', 1, $sScript);
                    db_input('descrdepto',          55, $Idescrdepto,          true, 'text', 3);
                    ?>
                    <input type='button' value='Lançar' name='lancar_farmacia' id='lancar_farmacia'>
                  </td>
                </tr>

                <tr>
                  <td>
                    &nbsp;
                  </td>
                  <td>
                    <select multiple size='8' name='select_farmacia[]' id='select_farmacia'
                            style="width: 80%;" onDblClick="js_excluir_farmacia();">
                    </select>
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
        </tr>
      </table>
      <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_mandaDados();" >
    </form>
  </div>
<?php
db_menu();
?>
</body>
</html>
<script>
function js_incluir_item_medicamento() {

  var texto = document.form1.m60_descr.value;
  var valor = document.form1.fa01_i_codigo.value;

  if(texto != "" && valor != "") {

    var F                         = document.getElementById("select_medicamento");
    var valor_default_novo_option = F.length;
    var testa                     = false;

    for(var x = 0; x < F.length; x++) {

      if(F.options[x].value == valor) {

        testa = true;
        break;
      }
    }

    if(testa == false) {

      F.options[valor_default_novo_option] = new Option(texto,valor);
      for(i = 0; i < F.length; i++) {
        F.options[i].selected = false;
      }

      F.options[valor_default_novo_option].selected = true;
    }
  }

  texto = document.form1.m60_descr.value     = "";
  valor = document.form1.fa01_i_codigo.value = "";
  document.form1.lancar_medicamento.onclick  = '';
}

function js_excluir_item_medicamento() {

  var F = document.getElementById("select_medicamento");

  if(F.length == 1) {
    F.options[0].selected = true;
  }

  var SI = F.selectedIndex;
  if(F.selectedIndex != -1 && F.length > 0) {

    F.options[SI] = null;

    if(SI <= (F.length - 1)) {
      F.options[SI].selected = true;
    }
  }
}

function js_incluir_item_cgs() {

  var texto = document.form1.z01_v_nome.value;
  var valor = document.form1.z01_i_cgsund.value;

  if(texto != "" && valor != "") {

    var F                         = document.getElementById("select_cgs");
    var valor_default_novo_option = F.length;
    var testa                     = false;

    for(var x = 0; x < F.length; x++) {

      if(F.options[x].value == valor) {

        testa = true;
        break;
      }
    }

    if(testa == false) {

      F.options[valor_default_novo_option] = new Option(texto,valor);

      for(i = 0; i < F.length; i++) {
        F.options[i].selected = false;
      }

      F.options[valor_default_novo_option].selected = true;
    }
  }

  texto = document.form1.z01_v_nome.value   = "";
  valor = document.form1.z01_i_cgsund.value = "";
  document.form1.lancar_cgs.onclick         = '';
}

function js_excluir_item_cgs() {

  var F = document.getElementById("select_cgs");
  if(F.length == 1) {
    F.options[0].selected = true;
  }

  var SI = F.selectedIndex;
  if(F.selectedIndex != -1 && F.length > 0) {

    F.options[SI] = null;
    if(SI <= (F.length - 1)) {
      F.options[SI].selected = true;
    }
  }
}

function js_pesquisafa01_i_medicamento(mostra) {

  prescricao  = '';
  var sTitulo = 'Pesquisa Medicamento';

  if(mostra == true) {
    js_OpenJanelaIframe(
      '',
      'db_iframe_far_matersaude',
      'func_far_matersaude.php?'+prescricao+'funcao_js=parent.js_mostramatersaude1|fa01_i_codigo|m60_descr',
      sTitulo,
      mostra,
      3
    );
  } else {

    if(document.form1.fa01_i_codigo.value != '') {
      js_OpenJanelaIframe(
        '',
        'db_iframe_far_matersaude',
        'func_far_matersaude.php?'+prescricao+'pesquisa_chave='+document.form1.fa01_i_codigo.value
                              +'&funcao_js=parent.js_mostramatersaude',
        sTitulo,
        mostra
      );
    } else {
      document.form1.m60_descr.value = "";
    }
  }
}

function js_mostramatersaude(chave, descricao, erro) {

  document.form1.m60_descr.value = descricao;
  if(erro == true) {

    document.form1.fa01_i_codigo.focus();
    document.form1.fa01_i_codigo.value = '';
  } else {

    document.form1.m60_descr.value            = descricao;
    document.form1.lancar_medicamento.onclick = js_incluir_item_medicamento;
  }
}

function js_mostramatersaude1(chave1, chave2) {

  document.form1.fa01_i_codigo.value = chave1;
  document.form1.m60_descr.value     = chave2;
  db_iframe_far_matersaude.hide();
  document.form1.lancar_medicamento.onclick = js_incluir_item_medicamento;
}

function js_pesquisaz01_i_cgsund(mostra) {

  var sTitulo = 'Pesquisa CGS';

  if(mostra == true) {
    js_OpenJanelaIframe(
      '',
      'db_iframe_cgs_und',
      'func_cgs_und.php?funcao_js=parent.js_mostracgs_und1|z01_i_cgsund|z01_v_nome',
      sTitulo,
      mostra
    );
  } else {

    if(document.form1.z01_i_cgsund.value != '') {
      js_OpenJanelaIframe(
        '',
        'db_iframe_cgs_und','func_cgs_und.php?pesquisa_chave='+document.form1.z01_i_cgsund.value
                                          +'&funcao_js=parent.js_mostracgs_und',
        sTitulo,
        mostra
      );
    } else {
      document.form1.z01_v_nome.value = '';
    }
   }
}

function js_mostracgs_und(chave, erro) {

  document.form1.z01_v_nome.value = chave;

  if(erro == true) {

    document.form1.z01_i_cgsund.focus();
    document.form1.z01_i_cgsund.value = ''; //z01_v_nome
  } else {
    document.form1.lancar_cgs.onclick = js_incluir_item_cgs;
  }
}

function js_mostracgs_und1(chave1, chave2) {

  document.form1.z01_i_cgsund.value = chave1;
  document.form1.z01_v_nome.value   = chave2;
  db_iframe_cgs_und.hide();
  document.form1.lancar_cgs.onclick = js_incluir_item_cgs;
}

function js_validadata() {

  if(document.form1.data_inicio.value == "" || document.form1.data_fim.value == "") {

    alert('Os campos data de início e de fim devem ser preenchidos.');
    document.form1.data_inicio.focus();
    return false;
  }

  inicio = new Date(document.form1.data_inicio.value.substring(6,10),
                    document.form1.data_inicio.value.substring(3,5),
                    document.form1.data_inicio.value.substring(0,2));
  fim    = new Date(document.form1.data_fim.value.substring(6,10),
                    document.form1.data_fim.value.substring(3,5),
                    document.form1.data_fim.value.substring(0,2));

  if( inicio > fim) {

    alert('A data de início está maior que a data de Fim.');
    document.form1.data_inicio.value = '';
    document.form1.data_fim.value    = '';
    document.form1.data_inicio.focus();
    return false;
  }

  return true;
}

function js_validaEnvio() {

  if(!js_validadata()) {
    return false;
  }

  return true;
}

function js_mandaDados() {

  if(js_validaEnvio()) {

    sChave = 'datas='+document.form1.data_inicio.value+','+document.form1.data_fim.value;

    if(document.form1.select_cgs.length > 0) {

      sChave += '&iCgs=';
      sVir    = '';

      for(i = 0; i < document.form1.select_cgs.length; i++) {

        sChave += sVir + document.form1.select_cgs.options[i].value;
        sVir    = ',';
      }
    }

    if(document.form1.select_medicamento.length > 0) {

      sChave += '&medicamentos=';
      sVir    = '';

      for(i = 0; i < document.form1.select_medicamento.length; i++) {

        sChave += sVir + document.form1.select_medicamento.options[i].value;
        sVir    = ',';
      }
    }

    if(document.form1.select_farmacia.length > 0) {

      sChave += '&departamentos=';
      sVir    = '';

      for(i = 0; i < document.form1.select_farmacia.length; i++) {

        sChave += sVir + document.form1.select_farmacia.options[i].value;
        sVir = ',';
      }
    }

    sChave += '&ordem='+document.form1.ordem.value;
    oJan    = window.open('far2_historicoretirada002.php?'+sChave, '', 'width='+(screen.availWidth-5)+',height='+
                       (screen.availHeight-40)+',scrollbars=1,location=0 ');
    oJan.moveTo(0, 0);
  }
}

function js_pesquisaDepartamento(lMostra) {

  var sUrl    = 'func_unidades.php';
  var sTitulo = 'Pesquisa Departamento';

  if(lMostra) {

    sUrl += "?funcao_js=parent.js_mostraDepartamento1|sd02_i_codigo|descrdepto";
    js_OpenJanelaIframe('','db_iframe_departamento', sUrl, sTitulo, lMostra);
  } else {

     if($F('fa13_i_departamento') != '') {

       sUrl += '?pesquisa_chave='+$F('fa13_i_departamento')+'&funcao_js=parent.js_mostraDepartamento';
       sUrl += '&lBuscaDescricao=true';
       js_OpenJanelaIframe('','db_iframe_departamento', sUrl, sTitulo, lMostra);
     } else {
       $('fa13_i_departamento').value = '';
     }
   }
}

function js_mostraDepartamento1(iDepartamento, sDescricao) {

  $('fa13_i_departamento').value = iDepartamento;
  $('descrdepto').value          = sDescricao;
  db_iframe_departamento.hide();
  document.form1.lancar_farmacia.onclick = js_incluir_farmacia;
}

function js_mostraDepartamento(sDescricao, lErro) {

  $('descrdepto').value = sDescricao;
  if (lErro) {

    $('fa13_i_departamento').value = '';
    $('fa13_i_departamento').focus();
  } else {
    document.form1.lancar_farmacia.onclick = js_incluir_farmacia;
  }
}

function js_incluir_farmacia() {

  var texto = $F('descrdepto');
  var valor = $F('fa13_i_departamento');

  if(texto != "" && valor != "") {

    var F                         = document.getElementById("select_farmacia");
    var valor_default_novo_option = F.length;
    var testa                     = false;

    for(var x = 0; x < F.length; x++) {

      if(F.options[x].value == valor) {

        testa = true;
        break;
      }
    }

    if(testa == false) {

      F.options[valor_default_novo_option] = new Option(texto,valor);

      for(i = 0; i < F.length; i++) {
        F.options[i].selected = false;
      }

      F.options[valor_default_novo_option].selected = true;
    }
  }

  $('descrdepto').value                  = '';
  $('fa13_i_departamento').value         = '';
  document.form1.lancar_farmacia.onclick = '';
}

function js_excluir_farmacia() {

  var F = document.getElementById("select_farmacia");

  if(F.length == 1) {
    F.options[0].selected = true;
  }

  var SI = F.selectedIndex;

  if(F.selectedIndex != -1 && F.length > 0) {

    F.options[SI] = null;

    if(SI <= (F.length - 1)) {
      F.options[SI].selected = true;
    }
  }
}
</script>