<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
$clsepultamentos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("cm14_i_codigo");
$clrotulo->label("nome");
$clrotulo->label("sd03_c_nome");
$clrotulo->label("cm04_c_descr");
?>
<form name="form1" method="post" action="">
<fieldset>
  <legend>Sepultamento</legend>
  <table border="0">
    <tr>
      <td nowrap title="<?php echo $Tcm01_i_cemiterio; ?>">
        <?php
          db_ancora($Lcm01_i_cemiterio, "js_pesquisacm01_i_cemiterio(true);", $db_opcao);
        ?>
      </td>
      <td>
        <?php
          db_input('cm01_i_cemiterio', 10, $Icm01_i_cemiterio, true, 'text', $db_opcao, " onchange='js_pesquisacm01_i_cemiterio(false);'");
          db_input('nome_cemiterio', 40, $Icm14_i_codigo, true, 'text', 3, '');
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?php echo $Tcm01_i_codigo; ?>">
        <?php
          db_ancora($Lcm01_i_codigo, "js_pesquisacm01_i_codigo(true);", $db_opcao);
        ?>
      </td>
      <td>
        <?php
          db_input('cm01_i_codigo', 10, $Icm01_i_codigo, true, 'text', $db_opcao, " onchange='js_pesquisacm01_i_codigo(false);'");
          db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3, '');
        ?>
      </td>
    </tr>
    <tr>
      <td>
         <strong>Pai:</strong>
      </td>
      <td>
        <?php
          db_input('z01_pai',54,@$z01_pai,true,'text',3);
        ?>
      </td>
    </tr>
    <tr>
      <td>
        <strong>Mãe:</strong>
      </td>
      <td>
        <?php
          db_input('z01_mae',54,@$z01_mae,true,'text',3);
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?php echo $Tcm01_c_conjuge; ?>">
        <strong>Cônjuge:</strong>
      </td>
      <td>
        <?php
          db_input('cm01_c_conjuge', 54, $Icm01_c_conjuge, true, 'text', $db_opcao, "");
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?php echo $Tcm01_d_falecimento; ?>">
         <?php echo $Lcm01_d_falecimento; ?>
      </td>
      <td>
        <?php
          if (!isset($cm01_d_falecimento_dia)) {
            $cm01_d_falecimento_dia = '';
            $cm01_d_falecimento_mes = '';
            $cm01_d_falecimento_ano = '';
          }
          db_inputdata('cm01_d_falecimento', $cm01_d_falecimento_dia, $cm01_d_falecimento_mes, $cm01_d_falecimento_ano, true, 'text', $db_opcao, "");
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?php echo $Tcm01_c_cor; ?>">
         <?php echo $Lcm01_c_cor; ?>
      </td>
      <td>
        <?php
          $x = array('0' => 'Branco', '1' => 'Pardo', '2' => 'Indio', '4' => 'Negro', '5' => 'Outra');
          db_select('cm01_c_cor', $x, true, $db_opcao, "");
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?php echo $Tcm01_observacoes; ?>">
         <?php echo $Lcm01_observacoes; ?>
      </td>
      <td>
        <?php
          db_textarea('cm01_observacoes', 7, 52, $Icm01_observacoes, true, 'text', $db_opcao, "");
        ?>
      </td>
    </tr>
    </table>
</fieldset>
  <?php if(!isset($sepultamento)) { ?>
     <center>
     <input name="processar" type="button" id="db_opcao" value="Processar" <?=($db_botao==false?"disabled":"")?> onclick="js_valida();" />
     <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" <?=($db_opcao==1?"disabled":"")?> />
     </center>
  <?php } ?>
</form>
<script type="text/javascript">
document.getElementById("cm01_c_cor").style.width = "95px";
//busca cemiterio
function js_pesquisacm01_i_cemiterio(mostra) {
  if(mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_a1', 'db_iframe_cemiterio', 'func_cemiterio.php?funcao_js=parent.js_mostracemiterio1|cm14_i_codigo|cm16_c_nome|z01_nome', 'Pesquisa', true);
  } else {
    if(document.form1.cm01_i_cemiterio.value != '') {
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_a1', 'db_iframe_cemiterio', 'func_cemiterio.php?pesquisa_chave='+document.form1.cm01_i_cemiterio.value+'&funcao_js=parent.js_mostracemiterio', 'Pesquisa', false);
    } else {
      document.form1.cm01_i_cemiterio.value = '';
      document.form1.nome_cemiterio.value = '';
    }
  }
}
//busca cgm / falecido
function js_pesquisacm01_i_codigo(mostra) {
  if(mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_a1', 'func_nome', 'func_cgmtipo.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome|z01_pai|z01_mae&tipo=fisico', 'Pesquisa', true);
  } else {
    if(document.form1.cm01_i_codigo.value != '') {
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_a1', 'func_nome', 'func_cgmtipo.php?familia=true&pesquisa_chave='+document.form1.cm01_i_codigo.value+'&funcao_js=parent.js_mostracgm&tipo=fisico', 'Pesquisa', false);
    } else {
      document.form1.z01_nome.value = '';
      document.form1.z01_mae.value = '';
      document.form1.z01_pai.value = '';
    }
  }
}
function js_mostracgm(erro, chave1, chave2, chave3) {
  document.form1.z01_nome.value = chave1;
  if(erro == true) {
    document.form1.cm01_i_codigo.focus();
    document.form1.cm01_i_codigo.value = '';
    document.form1.z01_mae.value = '';
    document.form1.z01_pai.value = '';
  }
  if(chave2 != undefined) {
    document.form1.z01_mae.value = chave2;
  }
  if(chave3 != undefined) {
    document.form1.z01_pai.value = chave3;
  }
}
function js_mostracgm1(chave1, chave2, chave3, chave4) {
  document.form1.cm01_i_codigo.value = chave1;
  document.form1.z01_nome.value = chave2;
  if(chave3 != undefined) {
    document.form1.z01_pai.value = chave3;
  }
  if(chave4 != undefined) {
    document.form1.z01_mae.value = chave4;
  }
  func_nome.hide();
}
function js_mostracemiterio(chave, erro) {
  document.form1.nome_cemiterio.value = chave;
  if(erro == true) {
    document.form1.cm01_i_cemiterio.focus();
    document.form1.cm01_i_cemiterio.value = '';
  }
}
function js_mostracemiterio1(chave1, chave2, chave3) {
  document.form1.cm01_i_cemiterio.value = chave1;
  if(chave2 == "") {
   document.form1.nome_cemiterio.value = chave3;
  } else {
   document.form1.nome_cemiterio.value = chave2;
  }
  db_iframe_cemiterio.hide();
}
//valida e envia para outra aba
function js_valida() {
  if(document.form1.cm01_i_cemiterio.value == "") {
    alert('Campo Cemitério é de preenchimento obrigatório.');
    document.form1.cm01_i_cemiterio.focus();
    return false;
  }
  if(document.form1.cm01_i_codigo.value == "") {
    alert('Campo Sepultado é de preechimento obrigatório.');
    document.form1.cm01_i_codigo.focus();
    return false;
  }
  if(document.form1.cm01_d_falecimento.value == "") {
    alert('Campo Falecimento é de preechimento obrigatório.');
    document.form1.cm01_d_falecimento_dia.focus();
    return false;
  }
  var campos  = 'cm01_i_cemiterio=' + document.form1.cm01_i_cemiterio.value;
      campos += '&cm01_i_codigo=' + document.form1.cm01_i_codigo.value;
      campos += '&cm01_c_conjuge=' + document.form1.cm01_c_conjuge.value;
      campos += '&cm01_d_falecimento=' + document.form1.cm01_d_falecimento_ano.value+'-'+document.form1.cm01_d_falecimento_mes.value+'-'+document.form1.cm01_d_falecimento_dia.value;
      campos += '&cm01_c_cor=' + document.form1.cm01_c_cor.value;
      campos += '&cm01_observacoes=' + document.form1.cm01_observacoes.value.urlEncode();

  parent.document.formaba.a2.disabled = false;
  <?php if($db_opcao == 2){ ?>
  (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a2.location.href = 'cem1_sepultamentos006.php?chavepesquisa=<?php echo $cm01_i_codigo; ?>&'+campos;
  <?php } else { ?>
  (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a2.location.href = 'cem1_sepultamentos002.php?db_opcao='+<?php echo $db_opcao; ?>+'&'+campos;
  <?php } ?>
  parent.mo_camada('a2');
}
function js_pesquisa() {
  js_OpenJanelaIframe('CurrentWindow.corpo.iframe_a1', 'db_iframe_sepultamentos', 'func_sepultamentos.php?funcao_js=parent.js_preenchepesquisa|cm01_i_codigo', 'Pesquisa', true);
}
function js_preenchepesquisa(chave) {
  db_iframe_sepultamentos.hide();
  parent.document.formaba.a2.disabled = true;
  <?php
    if($db_opcao != 1) {
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
  ?>
}
</script>
