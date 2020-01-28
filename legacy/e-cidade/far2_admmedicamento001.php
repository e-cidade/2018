<?
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

db_postmemory($HTTP_POST_VARS);
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<br><br>
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
  </tr>
</table>

<form name="form1" method="post" action="">
  <center>
    <table width='70%'>
      <tr>
        <td>
          <fieldset style="width:100%"><legend align='left'><b>Almoxarifados</b></legend>
            <table  border="0"  align="center" width='100%'>
              <tr>
                <td width='15%' align='right'>
                  <?
                  db_ancora("<b>Almoxarifado:</b>", "js_pesquisacoddepto(true);", "");
                  ?>
                </td>
                <td nowrap>
                  <?
                  db_input('coddepto', 10, '', true, 'text', 1, " onchange='js_pesquisacoddepto(false);'");
                  db_input('descrdepto', 55, '', true, 'text', 3, '');
                  ?>
                  &nbsp;&nbsp;&nbsp;&nbsp;
                  <input type='button' value='Lan&ccedil;ar' name='lancar_departamento' id='lancar_departamento'>
                </td>
              </tr>

              <tr>
                <td>
                  &nbsp;
                </td>
                <td>
                 <select multiple size='8' name='select_departamento[]' id='select_departamento' style="width: 80%;"
                   onDblClick="js_excluir_item_departamento();">
                  </select>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
 
      <tr> 
        <td>
          <fieldset style="width:100%"><legend align='left'><b>Medicamentos</b></legend>
            <table  border="0"  align="center" width='100%'>
              <tr>
                <td width='15%' align='right'>
                  <?
                  db_ancora('<b>Medicamento:</b>', "js_pesquisafa01_i_medicamento(true);", "");
                  ?>
                </td>
                <td>
                  <?
                  db_input('fa01_i_codigo', 10, @$Ifa01_i_codigo, true, 'text', 1, 
                           " onchange='js_pesquisafa01_i_medicamento(false);'"
                          );
                  db_input('m60_descr', 55, @$Im60_descr, true, 'text', 3, '');
                  ?>
                  &nbsp;&nbsp;&nbsp;&nbsp;
                  <input type='button' value='Lan&ccedil;ar' name='lancar_medicamento' id='lancar_medicamento'>
                </td>
              </tr>

              <tr>
                <td>
                  &nbsp;
                </td>
                <td>
                  <select multiple size='8' name='select_medicamento[]' id='select_medicamento' 
                    style="width: 80%;" onDblClick="js_excluir_item_medicamento();">
                  </select>
                </td>
              </tr>
            </table>
          </fieldset>    
        </td>
      </tr>

      <tr>
        <td>
          <b>Tratamento sem movimentação: </b>
          <?
          $aX = array('1' => 'Apresentar junto com os outros', '2' => 'Não apresente os pacientes', 
                      '3' => 'Apresentar somente os sem movimentação'
                     );
          db_select('iMovimentacao', $aX, true, ""); 
          ?>
          &nbsp;&nbsp;  </nbr><b>Relatório exato conforme os medicamentos: </b>
          <?
          $aY = array('1' => 'NÃO', '2' => 'SIM' );
          db_select('iExato', $aY, true, ""); 
          ?>
        </td>
      </tr>
    </table>
  </center>

  <center>
    <br>
    <input name="emite" id="emite" type="button" value="Processar" onclick="js_mandadados();">
  </center>


  </center>
</form>
<?
        db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), 
                db_getsession("DB_anousu"), db_getsession("DB_instit")
               );
?>

</body>
</html>
<script>

function js_incluir_item_departamento() {

  var texto = document.form1.descrdepto.value;
  var valor = document.form1.coddepto.value;
  if (texto != "" && valor != "") {

    var F = document.getElementById("select_departamento");
    var valor_default_novo_option = F.length;
    var testa = false;
    for(var x = 0; x < F.length; x++) {

      if (F.options[x].value == valor) {

        testa = true;
        break;

      }
    }

    if (testa == false) {

      F.options[valor_default_novo_option] = new Option(texto, valor);
      for(i = 0; i < F.length; i++) {
        F.options[i].selected = false;
      }
      F.options[valor_default_novo_option].selected = true;

    }

  }
  texto = document.form1.coddepto.value      = '';
  valor = document.form1.descrdepto.value    = '';
  document.form1.lancar_departamento.onclick = '';

}

function js_excluir_item_departamento() {

  var F = document.getElementById("select_departamento");
  if (F.length == 1) {
    F.options[0].selected = true;
  }
  var SI = F.selectedIndex;
  if (F.selectedIndex != -1 && F.length > 0) {

    F.options[SI] = null;
    if (SI <= (F.length - 1)) {
      F.options[SI].selected = true;
    }

  }

}
  
function js_pesquisacoddepto(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('top.corpo', 'db_iframe_db_depart', 'func_db_almoxdepto.php?funcao_js='+
                        'parent.js_mostradepart1|coddepto|descrdepto', 'Pesquisa', true
                       );

  } else {

    if (document.form1.coddepto.value != '') { 

      js_OpenJanelaIframe('top.corpo', 'db_iframe_db_depart', 'func_db_almoxdepto.php?pesquisa_chave='+
                          document.form1.coddepto.value+'&funcao_js=parent.js_mostradepart', 
                          'Pesquisa', false
                         );

    } else {
      document.form1.descrdepto.value = ''; 
    }

  }

}

function js_mostradepart(chave, erro) {

  document.form1.descrdepto.value = chave; 
  if (erro == true) {

    document.form1.coddepto.focus(); 
    document.form1.coddepto.value = '';

  } else {
    
    document.form1.descrdepto.value            = chave;
    document.form1.lancar_departamento.onclick = js_incluir_item_departamento;

  }

}

function js_mostradepart1(chave1,  chave2) {

  document.form1.coddepto.value   = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_db_depart.hide();
  document.form1.lancar_departamento.onclick = js_incluir_item_departamento;

}


function js_incluir_item_medicamento() {

  var texto=document.form1.m60_descr.value;
  var valor=document.form1.fa01_i_codigo.value;

  if (texto != "" && valor != "") {

    var F                         = document.getElementById("select_medicamento");
    var valor_default_novo_option = F.length;
    var testa                     = false;

    for(var x = 0; x < F.length; x++) {
    
      if (F.options[x].value == valor) {

        testa = true;
        break;

      }

    }
    if (testa == false) {

      F.options[valor_default_novo_option] = new Option(texto, valor);
      for(i = 0; i < F.length; i++) {
        F.options[i].selected = false;
      }
      F.options[valor_default_novo_option].selected = true;

    }

  }
  texto = document.form1.m60_descr.value     ='';
  valor = document.form1.fa01_i_codigo.value ='';
  document.form1.lancar_medicamento.onclick  = '';

}

function js_excluir_item_medicamento() {

  var F = document.getElementById("select_medicamento");
  if (F.length == 1) {
    F.options[0].selected = true;
  }
  var SI = F.selectedIndex;

  if (F.selectedIndex != -1 && F.length > 0) {

    F.options[SI] = null;
    if (SI <= (F.length - 1)) {
      F.options[SI].selected = true;
    }

  }

}

function js_pesquisafa01_i_medicamento(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('', 'db_iframe_far_matersaude', 'func_far_matersaude.php?funcao_js='+
                        'parent.js_mostramatersaude1|fa01_i_codigo|m60_descr', 
                        'Pesquisa Medicamento', true
                       );

  } else {

    if (document.form1.fa01_i_codigo.value != '') {

      js_OpenJanelaIframe('', 'db_iframe_far_matersaude', 'func_far_matersaude.php?pesquisa_chave='+
                          document.form1.fa01_i_codigo.value+'&funcao_js=parent.js_mostramatersaude', 
                          'Pesquisa Medicamento', false
                         );

    } else {
	    document.form1.m60_descr.value = "";
    }

  }

}

function js_mostramatersaude(chave, erro) {

  document.form1.m60_descr.value = chave; 
  if (erro == true) {

    document.form1.fa01_i_codigo.focus(); 
    document.form1.fa01_i_codigo.value = '';

  } else {

    document.form1.m60_descr.value            = chave;
    document.form1.lancar_medicamento.onclick = js_incluir_item_medicamento;

  }

}

function js_mostramatersaude1(chave1, chave2) {

  document.form1.fa01_i_codigo.value = chave1;
  document.form1.m60_descr.value     = chave2;
  db_iframe_far_matersaude.hide();
  document.form1.lancar_medicamento.onclick = js_incluir_item_medicamento;

}

function js_validaenvio() {

  return true;

}

function js_mandadados() {
 
  if (js_validaenvio()) {

    vir                 = '';
    departamentos       = 'departamentos=';
    nomes_departamentos = '&nomes_departamentos='
    medicamentos        = '&medicamentos=';
    sMov                = '&iMovimentacao='+document.form1.iMovimentacao.value;
    iExato              = '&iExato='+document.form1.iExato.value;
    
    for(x = 0; x < document.form1.select_departamento.length; x++) {

      departamentos       += vir + document.form1.select_departamento.options[x].value;
      nomes_departamentos += vir + document.form1.select_departamento.options[x].value + 
                             ' - ' + document.form1.select_departamento.options[x].innerHTML;
      vir                  = ',';

    }
    
    vir = '';
    for(x = 0; x < document.form1.select_medicamento.length; x++) {

      medicamentos += vir + document.form1.select_medicamento.options[x].value;
      vir = ',';

    }
      

    jan = window.open('far2_admmedicamento002.php?'+departamentos+medicamentos+nomes_departamentos+sMov+iExato, '', 
                      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
                     );
    jan.moveTo(0, 0);

  }

}

</script>