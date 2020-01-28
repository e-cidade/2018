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

//MODULO: Ambulatorial
$oSauConfig->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label("s103_i_departamentos");
$oRotulo->label("s103_c_emitirfaa");
$oRotulo->label("s103_c_cancelafa");
$oRotulo->label("s103_i_modalidade");
$oRotulo->label("sd82_c_modalidade");
$oRotulo->label("sd82_c_nome");
$oRotulo->label("sd82_i_anocomp");
$oRotulo->label("sd82_i_mescomp");
$oRotulo->label("s103_c_apareceragenda");
$oRotulo->label("s103_c_idadeproc");
$oRotulo->label("s103_c_servicoproc");
$oRotulo->label("s103_i_revisacgs");
$oRotulo->label("s103_i_datahorafaa");
$oRotulo->label("s103_i_modelofaa");
$oRotulo->label("s103_i_todacomp");
$oRotulo->label("s103_obrigarcns");

?>
<div class="container">
  <form name="form1" method="post" action="">
    <fieldset>
      <legend>Parâmetros Globais:</legend>
      <table>
        <tr>
          <td title="<?=@$Ts103_i_departamentos?>">
            <?=$Ls103_i_departamentos?>
          </td>
          <td>
            <?php
            $x = array( '1' => 'Trazer todos departamentos', '2' => 'Trazer apenas departamento do usuário' );
            db_select( 's103_i_departamentos', $x, true, $db_opcao );
            ?>
          </td>
        </tr>
        <tr>
          <td title="<?=@$Ts103_c_emitirfaa?>">
            <?=$Ls103_c_emitirfaa?>
          </td>
          <td>
            <?php
            $x = array( 'S' => 'SIM', 'N' => 'NÃO' );
            db_select( 's103_c_emitirfaa', $x, true, $db_opcao );
            ?>
          </td>
        </tr>
        <tr>
          <td title="<?=@$Ts103_c_cancelafa?>">
            <?=$Ls103_c_cancelafa?>
          </td>
          <td>
            <?php
            $x = array( 'S' => 'SIM', 'N' => 'NÃO' );
            db_select( 's103_c_cancelafa', $x, true, $db_opcao );
            ?>
          </td>
        </tr>
        <tr>
          <td title="<?=@$Ts103_i_modalidade?>">
            <?php
            db_ancora( @$Ls103_i_modalidade, "js_pesquisas103_i_modalidade(true);", $db_opcao );
            ?>
          </td>
          <td>
            <?php
            db_input( 's103_i_modalidade', 10, $Is103_i_modalidade, true, 'hidden', 3 );
            db_input( 'sd82_c_modalidade', 10, $Isd82_c_modalidade, true, 'text',   3 );
            db_input( 'sd82_c_nome',       60, $Isd82_c_nome,       true, 'text',   3 );
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <label class="bold">Mês/Ano Modalidade</label>
          </td>
          <td>
            <?php
            db_input( 'sd82_i_mescomp',    60, $Isd82_i_mescomp,    true, 'hidden', 3 );
            db_input( 'sd82_i_anocomp',    60, $Isd82_i_anocomp,    true, 'hidden', 3 );

            $sData = '';
            if( isset( $sd82_i_anocomp ) && $sd82_i_anocomp != '' && isset( $sd82_i_mescomp ) && $sd82_i_mescomp != '' ) {
              $sData = $sd82_i_mescomp.'/'.$sd82_i_anocomp;
            }
            db_input( 'sData', 10, 'sData', true, 'text', 3, '' );
            ?>
          </td>
        </tr>
        <tr>
          <td title="<?=@$Ts103_c_apareceragenda?>">
            <?=$Ls103_c_apareceragenda?>
          </td>
          <td nowrap >
            <?php
            $x = array( 'S' => 'SIM', 'N' => 'NÃO' );
            db_select( 's103_c_apareceragenda', $x, true, $db_opcao );
            ?>
          </td>
        </tr>
        <tr>
          <td title="<?=@$Ts103_c_idadeproc?>">
            <?=$Ls103_c_idadeproc?>
          </td>
          <td>
            <?php
            $x = array( 'N' => 'NÃO', 'S' => 'SIM' );
            db_select( 's103_c_idadeproc', $x, true, $db_opcao );
            ?>
          </td>
        </tr>
        <tr>
          <td title="<?=@$Ts103_c_servicoproc?>">
            <?=$Ls103_c_servicoproc?>
          </td>
          <td nowrap >
            <?php
            $x = array( 'N' => 'NÃO', 'S' => 'SIM' );
            db_select( 's103_c_servicoproc', $x, true, $db_opcao );
            ?>
          </td>
        </tr>
        <tr>
          <td title="<?=@$Ts103_i_revisacgs?>">
            <?=$Ls103_i_revisacgs?>
          </td>
          <td>
            <?php
            db_input( 's103_i_revisacgs', 10, $Is103_i_revisacgs, true, 'text', $db_opcao );
            ?>
          </td>
        </tr>
        <tr>
          <td title="<?=@$Ts103_i_datahorafaa?>">
            <?=$Ls103_i_datahorafaa?>
          </td>
          <td>
            <?php
            $aX = array( '1' => 'Atendimento', '2' => 'Emissão' );
            db_select( 's103_i_datahorafaa', $aX, true, 1 );
            ?>
          </td>
        </tr>
        <tr>
          <td title="<?=@$Ts103_i_modelorafaa?>">
            <?=$Ls103_i_modelofaa?>
          </td>
          <td>
            <?php
            if( empty( $s103_i_modelofaa ) || !isset( $s103_i_modelofaa ) ) {
              $s103_i_modelofaa = null;
            }

            selectModelosFaa( $s103_i_modelofaa );
            ?>
          </td>
        </tr>
        <tr>
          <td title="<?=@$Ts103_i_todacomp?>">
           <b> <?=$Ls103_i_todacomp?></b>
          </td>
          <td>
            <?php
            $x = array( '2' => 'NÃO', '1' => 'SIM' );
            db_select( 's103_i_todacomp', $x, true, $db_opcao );
            ?>
          </td>
        </tr>
        <tr>
          <td title="<?=@$Ts103_obrigarcns?>">
           <b> <?=$Ls103_obrigarcns?></b>
          </td>
          <td>
            <?php
            $aOpcoes = array( 'f' => 'NÃO', 't' => 'SIM' );
            db_select( 's103_obrigarcns', $aOpcoes, true, $db_opcao );
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input name="<?=( $db_opcao == 1 ? "incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir" ) )?>"
           type="submit" id="db_opcao"
           value="<?=( $db_opcao == 1 ? "Incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir" ) )?>"
           style="margin-top: 10px;">
  </form>
</div>
<script>

function js_lancar() {
	
  var obj     = document.form1;
  var Tam     = obj.unidadecentral.length;
  var codigos = '';
  var sep     = '';

  for(var x = 0; x < Tam; x++) {
	  
    codigos += sep + obj.unidadecentral.options[x].value;
    sep      = ',';
  }

  obj.lancaunidades.value = codigos;
}

function js_incluir() {

  var Tam = document.form1.unidades.length;
  var F   = document.form1;

  for(var x = 0; x< Tam; x++) {
	  
    if (F.unidades.options[x].selected == true) {
        
      F.elements['unidadecentral'].options[F.elements['unidadecentral'].options.length] = 
                         new Option(F.unidades.options[x].text, F.unidades.options[x].value);
      F.unidades.options[x] = null;
      Tam--;
      x--;
    }
  }

  if (document.form1.unidades.length > 0) {
    document.form1.unidades.options[0].selected = true;
  } else {
	  
    document.form1.incluirum.disabled    = true;
    document.form1.incluirtodos.disabled = true;
  }

  document.form1.excluirtodos.disabled = false;
  document.form1.unidades.focus();
}

function js_incluirtodos() {

  var Tam = document.form1.unidades.length;
  var F   = document.form1;

  for(var i = 0; i < Tam; i++) {
	  
    F.elements['unidadecentral'].options[F.elements['unidadecentral'].options.length] = 
                       new Option(F.unidades.options[0].text,F.unidades.options[0].value); 
    F.unidades.options[0] = null;
  }

  document.form1.incluirum.disabled    = true;
  document.form1.incluirtodos.disabled = true;
  document.form1.excluirtodos.disabled = false;
  document.form1.unidadecentral.focus();
}

function js_excluir() {

  var F   = document.getElementById("unidadecentral");
  var Tam = F.length;

  for (var x = 0; x < Tam; x++) {
	  
    if (F.options[x].selected == true) {
        
      document.form1.unidades.options[document.form1.unidades.length] = 
                        new Option(F.options[x].text,F.options[x].value);
      F.options[x] = null;
      Tam--;
      x--;
    }
  }

  if (document.form1.unidadecentral.length > 0) {
    document.form1.unidadecentral.options[0].selected = true;
  } else {
	  
    document.form1.excluirum.disabled    = true;
    document.form1.excluirtodos.disabled = true;
    document.form1.incluirtodos.disabled = false;
  }

  document.form1.unidadecentral.focus();
}

function js_excluirtodos() {
	
  var Tam = document.form1.unidadecentral.length;
  var F   = document.getElementById("unidadecentral");

  for(var i = 0; i < Tam; i++) {

    document.form1.unidades.options[document.form1.unidades.length] = 
                       new Option(F.options[0].text,F.options[0].value);
    F.options[0] = null;
  }

  if (F.length == 0) {
	  
    document.form1.excluirum.disabled    = true;
    document.form1.excluirtodos.disabled = true;
    document.form1.incluirtodos.disabled = false;
  }

  document.form1.unidades.focus();
}

function js_desabinc() {
	
  for (var i = 0; i < document.form1.unidades.length; i++) {
	  
    if (document.form1.unidades.length > 0 && document.form1.unidades.options[i].selected) {
        
      if (document.form1.unidadecentral.length  > 0) {
        document.form1.unidadecentral.options[0].selected = false;
      }

      document.form1.incluirum.disabled = false;
    }

    document.form1.incluirtodos.disabled = false;
  }
}

function js_desabexc(){

  for (var i = 0; i < document.form1.unidadecentral.length; i++) {
	  
    if (document.form1.unidadecentral.length > 0 && document.form1.unidadecentral.options[i].selected) {
        
      if(document.form1.unidades.length > 0) {
        document.form1.unidades.options[0].selected = false;
      }

      document.form1.excluirum.disabled = false;
    }

    document.form1.excluirtodos.disabled = false;
  }
}

function js_pesquisa() {
  js_OpenJanelaIframe(
                       'top.corpo.iframe_a1',
                       'db_iframe_sau_config',
                       'func_sau_config.php?funcao_js=parent.js_preenchepesquisa|0',
                       'Pesquisa',
                       true
                     );
}

function js_preenchepesquisa(chave) {

  db_iframe_sau_config.hide();
  <?php
  if( $db_opcao != 1 ) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function js_pesquisas103_i_modalidade( mostra ) {

  if (mostra == true) {
    js_OpenJanelaIframe(
                         'top.corpo.iframe_a1',
                         'db_iframe_sau_modalidade',
                         'func_sau_modalidade.php?funcao_js=parent.js_mostrasau_modalidade1|sd82_i_codigo'
                                                                                         +'|sd82_c_modalidade'
                                                                                         +'|sd82_c_nome'
                                                                                         +'|sd82_i_anocomp'
                                                                                         +'|sd82_i_mescomp',
                         'Pesquisa',
                         true
                       );
  }
}

function js_mostrasau_modalidade1( sd82_i_codigo, sd82_c_modalidade, sd82_c_nome, sd82_i_anocomp, sd82_i_mescomp ) {

  document.form1.s103_i_modalidade.value = sd82_i_codigo;
  document.form1.sd82_c_modalidade.value = sd82_c_modalidade;
  document.form1.sd82_c_nome.value       = sd82_c_nome;
  document.form1.sd82_i_anocomp          = sd82_i_anocomp;
  document.form1.sd82_i_mescomp          = sd82_i_mescomp; 
  document.form1.sData.value             = sd82_i_mescomp + '/' + sd82_i_anocomp;
  db_iframe_sau_modalidade.hide();
}

$('s103_i_departamentos').className  = 'field-size-max';
$('s103_c_emitirfaa').className      = 'field-size2';
$('s103_c_cancelafa').className      = 'field-size2';
$('s103_c_apareceragenda').className = 'field-size2';
$('s103_c_idadeproc').className      = 'field-size2';
$('s103_c_servicoproc').className    = 'field-size2';
$('s103_i_revisacgs').className      = 'field-size2';
$('s103_i_datahorafaa').className    = 'field-size3';
$('s103_i_modelofaa').className      = 'field-size3';
$('s103_i_todacomp').className       = 'field-size2';
$('sd82_c_modalidade').className     = 'field-size2';
$('sd82_c_nome').className           = 'field-size7';
$('sData').className                 = 'field-size2';
$('s103_obrigarcns').className       = 'field-size2';
</script>