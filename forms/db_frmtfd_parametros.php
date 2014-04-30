<?
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

//MODULO: TFD
$cltfd_parametros->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label("sd03_i_codigo");
$oRotulo->label("sd02_i_codigo");
$oRotulo->label("sd27_i_codigo");
$oRotulo->label("z01_nome");
$oRotulo->label("rh70_estrutural");
$oRotulo->label("rh70_descr");
$oRotulo->label("tf11_especmedico");

$iDepart = db_getsession('DB_coddepto');
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ttf11_i_utilizagradehorario?>">
      <?=@$Ltf11_i_utilizagradehorario?>
    </td>
    <td> 
      <?
      $aX = array('1' => 'SIM', '2' => 'NÃO');
      db_select('tf11_i_utilizagradehorario', $aX, true, $db_opcao, '');
      db_input('tf11_i_codigo',10,$Itf11_i_codigo,true,'hidden',3,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf11_i_campofoco?>">
      <?=@$Ltf11_i_campofoco?>
    </td>
    <td> 
      <?
      $aX = array('1' => 'CGS', '2' => 'CARTÃO SUS', '3' => 'ENCAMINHAMENTO', '4' => 'FAA');
      db_select('tf11_i_campofoco', $aX, true, $db_opcao, '');
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <fieldset><legend><b>Regulador Padrão:</b></legend>
        <table>
          <tr>
            <td align="right">
              <?
                db_ancora("<b>Regulador:</b>", "js_pesquisa_medico(true); ","");
              ?>
            </td>
            <td> 
              <?
                db_input('sd03_i_codigo', 5, $Isd03_i_codigo, true, 'text', "", " onchange='js_pesquisa_medico(false);'");
                db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3, '');
              ?>
            </td>
          </tr>
          <tr>
            <td align="right">
              <?
                db_ancora("<b>Especialidade:</b>", "js_pesquisa_especmedico(true); ","");
              ?>
            </td>
            <td> 
              <?
                db_input('tf11_especmedico', 5, $Itf11_especmedico, true, 'hidden');
                db_input('rh70_estrutural', 5, $Irh70_estrutural, true, 'text', "", 
                         " onchange='js_pesquisa_especmedico(false);' ");
                db_input('rh70_descr', 40, $Irh70_descr, true, 'text', 3);
             ?>
            </td>
          </tr>
          <tr>
            <td align="right"><b>Unidade:</b></td>
            <td>
              <?
                db_input ('sd02_i_codigo', 5, $Isd02_i_codigo, true, 'text', 3, ''); 
                db_input ('descrdepto', 40, $Isd02_i_codigo, true, 'text', 3, ''); 
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
</table>
</center>
<input name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>" 
  type="submit" id="db_opcao" 
  value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"))?>" 
  <?=($db_botao == false ? "disabled" : "")?>>
</form>
<script>
function js_pesquisa_medico(lMostra) {

  if (lMostra == true) {

    var sTemp  = 'func_medicos.php?funcao_js=parent.js_mostramedicos|sd03_i_codigo|z01_nome&lTodosTiposProf=';
        sTemp += 'true&chave_sd06_i_unidade=<?=$iDepart?>';
    js_OpenJanelaIframe('', 'db_iframe_medicos', sTemp, 'Pesquisa', true);

  } else {

    if (document.form1.sd03_i_codigo.value != '') { 

      js_OpenJanelaIframe('',
                          'db_iframe_medicos',
                          'func_medicos.php?pesquisa_chave='+document.form1.sd03_i_codigo.value+
                          '&funcao_js=parent.js_mostramedicos1&lTodosTiposProf=true&chave_sd06_i_unidade'+
                          '=<?=$iDepart?>',
                          'Pesquisa',
                          false
                         );

    } else {

      $F('sd03_i_codigo').value = '';
      $F('z01_nome').value      = '';

    }

  }

}

function js_mostramedicos(chave1, chave2) {

  document.form1.sd03_i_codigo.value = chave1;
  document.form1.z01_nome.value      = chave2;
  db_iframe_medicos.hide();

}

function js_mostramedicos1(chave1, chave2) {

  js_limpar_combo(document.form1.tf34_i_especmedico);
  document.form1.z01_nome.value = chave1;
  if (chave2 == 'true') {

    alert("Regulador não encontrado.");
    $('sd03_i_codigo').value = "";
    $('sd03_i_codigo').focus();

  }
  
}

function js_pesquisa_especmedico(lMostra) {

  if ($('sd03_i_codigo').value == null || $('sd03_i_codigo').value == '') {

    alert('Médico não informado');
    return false;

  }
  sParam  = 'funcao_js=parent.js_mostraespecmedico|sd27_i_codigo|rh70_estrutural|rh70_descr|sd02_i_codigo|descrdepto';
  if (lMostra == false) {

    if ($('rh70_estrutural').value != null && $('rh70_estrutural').value != '') {

      sParam += '&nao_mostra=true';
      sParam += '&chave_rh70_estrutural='+$('rh70_estrutural').value;

    } else {

      $('tf11_especmedico').value = '';
      $('rh70_estrutural').value  = '';
      $('rh70_descr.value').value = '';
      $('sd02_i_codigo').value    = '';
      $('descrdepto').value       = '';
      return false;

    }

  }

  sParam += '&chave_sd04_i_medico='+$('sd03_i_codigo').value
  sUrl    = 'func_especmedico.php?'+sParam;
  js_OpenJanelaIframe('', 'db_iframe_medicos', sUrl, 'Pesquisa', lMostra);

}

function js_mostraespecmedico(chave1, chave2, chave3, chave4, chave5) {

  if (chave1 == '') {

    $('tf11_especmedico').value = '';
    $('rh70_estrutural').value  = '';
    $('rh70_descr').value       = chave2;
    $('sd02_i_codigo').value    = '';
    $('descrdepto').value       = '';
    return false;

  }
  $('tf11_especmedico').value = chave1;
  $('rh70_estrutural').value  = chave2;
  $('rh70_descr').value       = chave3;
  $('sd02_i_codigo').value    = chave4;
  $('descrdepto').value       = chave5;
  db_iframe_medicos.hide();

}
</script>