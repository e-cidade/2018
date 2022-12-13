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

//MODULO: educação
$clrotulo = new rotulocampo;
$clrotulo->label("ed55_i_codigo");

?>
<form name="form1" method="post" action="">
  <table>
    <tr>
      <td nowrap title="<?=$Ted52_i_codigo?>">
        <label for="ed52_i_codigo"><?=$Led52_i_codigo?></label>
      </td>
      <td>
        <?php
        db_input( 'ed52_i_codigo', 15, $Ied52_i_codigo, true, 'text', 3, "" );
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=$Ted52_c_descr?>">
        <label for="ed52_c_descr"><?=$Led52_c_descr?></label>
      </td>
      <td>
        <?php
        db_input( 'ed52_c_descr', 20, $Ied52_c_descr, true, 'text', $db_opcao, "" );
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=$Ted52_i_duracaocal?>">
        <label for="ed52_i_duracaocal">
          <?php
          db_ancora( $Led52_i_duracaocal, "js_pesquisaed52_i_duracaocal(true);", @$db_opcao );
          ?>
        </label>
      </td>
      <td>
       <?php
       db_input( 'ed52_i_duracaocal', 15, $Ied52_i_duracaocal, true, 'text', @$db_opcao, " onchange='js_pesquisaed52_i_duracaocal(false);'" );
       db_input( 'ed55_c_descr',      20, @$Ied55_c_descr,     true, 'text', 3, '' );
       ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=$Ted52_i_ano?>">
        <label for="ed52_i_ano"><?=$Led52_i_ano?></label>
      </td>
      <td>
        <?php
        db_input( 'ed52_i_ano', 4, $Ied52_i_ano, true, 'text', $db_opcao, " onchange=\"js_ano();\"" );
        ?>
        <span id="periodos" style="visibility:hidden">
          <?php
          echo $Led52_i_periodo;
          db_input( 'ed52_i_periodo', 10, $Ied52_i_periodo, true, 'text', $db_opcao, "" );
          ?>
        </span>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=$Ted52_d_inicio?>">
        <label for="ed52_d_inicio"><?=$Led52_d_inicio?></label>
      </td>
      <td>
        <?php
        db_inputdata(
                      'ed52_d_inicio',
                      @$ed52_d_inicio_dia,
                      @$ed52_d_inicio_mes,
                      @$ed52_d_inicio_ano,
                      true,
                      'text',
                      $db_opcao,
                      " onchange=\"js_datainicio();\"", "", "",
                      "parent.js_datainicio();"
                    );
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=$Ted52_d_fim?>">
        <label for="ed52_d_fim"><?=$Led52_d_fim?></label>
      </td>
      <td>
        <?php
        db_inputdata(
                      'ed52_d_fim',
                      @$ed52_d_fim_dia,
                      @$ed52_d_fim_mes,
                      @$ed52_d_fim_ano,
                      true,
                      'text',
                      $db_opcao,
                      " onchange=\"js_datafim();\"", "", "",
                      "parent.js_datafim();",
                      "js_datafim();"
                    );
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=$Ted52_d_resultfinal?>">
        <label for="ed52_d_resultfinal"><?=$Led52_d_resultfinal?></label>
      </td>
      <td>
        <?php
        db_inputdata(
                      'ed52_d_resultfinal',
                      @$ed52_d_resultfinal_dia,
                      @$ed52_d_resultfinal_mes,
                      @$ed52_d_resultfinal_ano,
                      true,
                      'text',
                      $db_opcao,
                      " onchange=\"js_resfinal();\"", "", "",
                      "parent.js_resfinal();","js_resfinal();"
                    );
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=$Ted52_i_diasletivos?>">
        <label for="ed52_i_diasletivos"><?=$Led52_i_diasletivos?></label>
      </td>
      <td>
        <?php
        db_input( 'ed52_i_diasletivos', 10, $Ied52_i_diasletivos, true, 'text', 3, " readonly " );
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=$Ted52_i_semletivas?>">
        <label for="ed52_i_semletivas"><?=$Led52_i_semletivas?></label>
      </td>
      <td>
        <?php
        db_input( 'ed52_i_semletivas', 10, $Ied52_i_semletivas, true, 'text', 3, "" );
        ?>
      </td>
    </tr>
    <?php
    
    $iOpcaoCalendAnterior = $db_opcao;

    if (db_getsession("DB_modulo") != 1100747) {
      $iOpcaoCalendAnterior = 3;
    }
    ?>
    <tr>
      <td nowrap title="<?=$Ted52_i_calendant?>">
        <label for="ed52_i_calendant">
          <?php
          db_ancora( $Led52_i_calendant, "js_pesquisaed52_i_calendant(true);", $iOpcaoCalendAnterior );
          ?>
        </label>
      </td>
      <td>
       <?php
       db_input( 'ed52_i_calendant', 15, $Ied52_i_calendant, true, 'text', 3, " onchange='js_pesquisaed52_i_calendant(false);'" );
       db_input( 'ed52_c_descrant',  20, 'ed52_c_descrant',  true, 'text', 3, '' );
       ?>
      </td>
    </tr>
    <tr>
      <td>
        <?php 
        db_input( 'ed52_c_passivo',     1, $Ied52_c_passivo,     true, 'hidden', 3 );
        db_input( 'ed52_i_codigo_base',10, "ed52_i_codigo_base", true, 'hidden', 3 );
        ?>
      </td>
    </tr>
  </table>
  
  <input name="<?=( $db_opcao == 1 ? "incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir" ) )?>" type="button" id="db_opcao"
         value="<?=( $db_opcao == 1 ? "Incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir" ) )?>"
         <?=($db_botao) ? "" : "disabled='disabled'"?>
         onclick="<?=( $db_opcao == 1 ? "js_carregaCalendario" : ( $db_opcao == 2 || $db_opcao == 22 ? "js_carregaCalendario" : "js_excluiCalendario" ) ) ?>();" />
  
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
  <input name="novo" type="button" id="novo" value="Novo Registro" onclick="js_novo()" <?=$db_opcao==1?"disabled":""?>>
  <?php
    if (db_getsession("DB_modulo") == 1100747) {
    
      $sDisabled = "";
      if ( $db_opcao == 2 || $db_opcao == 3 ) {
      	$sDisabled = "disabled = 'disabled'";
      }
    
      ?>
      <input name="novo" type="button" id="importar" value="Importar Calendario" onclick="js_importarCalendario()"
             <?php echo $sDisabled;?> />
      <?php
    }
  ?>
</form>
<table>
  <tr>
    <td>
      <iframe src="" name="iframe_sabado" id="iframe_sabado" width="1" height="1" style="visibility:hidden;" frameborder="1"></iframe>
    </td>
  </tr>
</table>
<script>
var iModulo         = <?=db_getsession("DB_modulo")?>;
var iDbOpcao        = <?=$db_opcao?>;
var sFuncaoPesquisa = 'func_calendarioescola2.php';
var sRpc            = 'edu4_calendario.RPC.php';

if (iDbOpcao == 1) {
  $('pesquisar').disabled = true;
}

/**
 * Verificamos o modulo que o usuario esta logado, para que seja apresentada a funcao de pesquisa com os calendarios
 * corretos
 */
if (iModulo != 1100747) {
  sFuncaoPesquisa = 'func_calendariobase.php';
}

function js_pesquisaed52_i_calendant(mostra) {

  if (mostra == true) {
    js_OpenJanelaIframe('',
                        'db_iframe_calendario',
                        sFuncaoPesquisa+'?calend='+document.form1.ed52_i_codigo.value+
                                        '&funcao_js=parent.js_mostracalendario1|ed52_i_codigo|ed52_c_descr',
                        'Pesquisa Calendários',
                        true
                       );
  } else {

    if (document.form1.ed52_i_calendant.value != '') {
      js_OpenJanelaIframe('',
                          'db_iframe_calendario',
                          sFuncaoPesquisa+'?calend='+document.form1.ed52_i_codigo.value+
                                          '&pesquisa_chave='+document.form1.ed52_i_calendant.value+
                                          '&funcao_js=parent.js_mostracalendario',
                          'Pesquisa',
                          false
                         );
    } else {
      document.form1.ed52_c_descrant.value = '';
    }
  }
}

function js_mostracalendario( chave, erro ) {
  
  document.form1.ed52_c_descrant.value = chave;
  if ( erro == true ) {
    
    document.form1.ed52_i_calendant.focus();
    document.form1.ed52_i_calendant.value = '';
  }
}

function js_mostracalendario1( chave1, chave2 ) {
  
  document.form1.ed52_i_calendant.value = chave1;
  document.form1.ed52_c_descrant.value  = chave2;
  db_iframe_calendario.hide();
}

function js_pesquisaed52_i_duracaocal( mostra ) {
  
  if ( mostra == true ) {
    js_OpenJanelaIframe(
                         '',
                         'db_iframe_duracaocal',
                         'func_duracaocal.php?funcao_js=parent.js_mostraduracaocal1|ed55_i_codigo|ed55_c_descr',
                         'Pesquisa Duração de Calendário',
                         true
                       );
  } else {
    
    if ( document.form1.ed52_i_duracaocal.value != '' ) {
      js_OpenJanelaIframe(
                           '',
                           'db_iframe_duracaocal',
                           'func_duracaocal.php?pesquisa_chave='+document.form1.ed52_i_duracaocal.value+'&funcao_js=parent.js_mostraduracaocal',
                           'Pesquisa',
                           false
                         );
    } else {
      document.form1.ed55_c_descr.value = '';
    }
  }
}

function js_mostraduracaocal( chave, erro ) {
  
  document.form1.ed55_c_descr.value = chave;
  
  if ( erro == true ) {
    
    document.form1.ed52_i_duracaocal.focus();
    document.form1.ed52_i_duracaocal.value = '';
  } else {
    
    if ( document.form1.ed52_i_duracaocal.value == 1 ) {
      document.getElementById("periodos").style.visibility = "hidden";
    } else {
      document.getElementById("periodos").style.visibility = "visible";
    }
  }
}

function js_mostraduracaocal1( chave1, chave2 ) {
  
  document.form1.ed52_i_duracaocal.value = chave1;
  document.form1.ed55_c_descr.value      = chave2;
  db_iframe_duracaocal.hide();
  
  if ( document.form1.ed52_i_duracaocal.value == 1 ) {
    document.getElementById("periodos").style.visibility = "hidden";
  } else {
    document.getElementById("periodos").style.visibility = "visible";
  }
}

function js_pesquisa() {
  js_OpenJanelaIframe('',
                      'db_iframe_calendario',
                      sFuncaoPesquisa+'?funcao_js=parent.js_preenchepesquisa|ed52_i_codigo',
                      'Pesquisa Calendários',
                      true
                     );
}

function js_preenchepesquisa(chave){

  db_iframe_calendario.hide();
  <?
  if ( $db_opcao != 1 ) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function js_novo() {
  parent.location.href="edu1_calendarioabas001.php";
}

function js_sabado( valor, calendario ) {
  
  if ( calendario != "" ) {
    
    if ( confirm( "O sistema irá recalcular os dias e semanas. Confirma?" ) ) {
      iframe_sabado.location.href = "edu1_calendario004.php?calendario="+calendario+"&sabado="+valor;
    }
  }
}

function js_datainicio() {
  
  if (document.form1.ed52_i_ano.value == "") {
    
    alert( "Informe o Ano do calendário!" );
    
    document.form1.ed52_d_inicio_dia.value = "";
    document.form1.ed52_d_inicio_mes.value = "";
    document.form1.ed52_d_inicio_ano.value = "";
    document.form1.ed52_d_inicio.value     = "";
    document.form1.ed52_i_ano.style.backgroundColor='#99A9AE';
    document.form1.ed52_i_ano.focus();
  } else {
    
    if (    document.form1.ed52_d_inicio_ano.value != "" 
         && document.form1.ed52_i_ano.value        != document.form1.ed52_d_inicio_ano.value ) {
      
      alert( "Ano da Data Inicial está diferente do Ano do calendário" );
      
      document.form1.ed52_d_inicio_dia.value = "";
      document.form1.ed52_d_inicio_mes.value = "";
      document.form1.ed52_d_inicio_ano.value = "";
      document.form1.ed52_d_inicio.value     = "";
      document.form1.ed52_d_inicio_dia.focus();
    }
  }
}

function js_datafim() {
  
  if ( document.form1.ed52_i_ano.value == "" ) {
    
    alert( "Informe o Ano do calendário!" );
    
    document.form1.ed52_d_fim_dia.value = "";
    document.form1.ed52_d_fim_mes.value = "";
    document.form1.ed52_d_fim_ano.value = "";
    document.form1.ed52_d_fim.value     = "";
    document.form1.ed52_i_ano.style.backgroundColor='#99A9AE';
    document.form1.ed52_i_ano.focus();
  } else {
    
    if (    document.form1.ed52_d_fim_ano.value != "" 
         && document.form1.ed52_i_ano.value > document.form1.ed52_d_inicio_ano.value ) {
      
      alert( "Ano da Data Final está diferente do Ano do calendário" );
      
      document.form1.ed52_d_fim_dia.value = "";
      document.form1.ed52_d_fim_mes.value = "";
      document.form1.ed52_d_fim_ano.value = "";
      document.form1.ed52_d_fim.value     = "";
      document.form1.ed52_d_fim_dia.focus();
    } else {
      
      if (    document.form1.ed52_d_inicio_dia.value == "" 
           || document.form1.ed52_d_inicio_mes.value == "" 
           || document.form1.ed52_d_inicio_ano.value == "") {
        alert( "Preencha todos os campos da Data Inicial!" );
      } else {
        
        dataini = document.form1.ed52_d_inicio_ano.value+document.form1.ed52_d_inicio_mes.value+document.form1.ed52_d_inicio_dia.value;
        datafim = document.form1.ed52_d_fim_ano.value+document.form1.ed52_d_fim_mes.value+document.form1.ed52_d_fim_dia.value;
        
        if (    dataini > datafim 
             && document.form1.ed52_d_fim_dia.value != "" 
             && document.form1.ed52_d_fim_mes.value != "" 
             && document.form1.ed52_d_fim_ano.value != "" ) {

          alert( "Data Final deve ser maior que a Data Inicial!" );

          document.form1.ed52_d_fim_dia.value = "";
          document.form1.ed52_d_fim_mes.value = "";
          document.form1.ed52_d_fim_ano.value = "";
          document.form1.ed52_d_fim.value     = "";
          document.form1.ed52_d_fim_dia.focus();
        }
      } 
    }
  }
}

function js_resfinal() {
  
  if ( document.form1.ed52_i_ano.value == "" ) {
    
    alert( "Informe o Ano do calendário!" );
    
    document.form1.ed52_d_resultfinal_dia.value     = "";
    document.form1.ed52_d_resultfinal_mes.value     = "";
    document.form1.ed52_d_resultfinal_ano.value     = "";
    document.form1.ed52_d_resultfinal.value         = "";
    document.form1.ed52_i_ano.style.backgroundColor = '#99A9AE';
    document.form1.ed52_i_ano.focus();
  } else {
    
    if (    document.form1.ed52_d_resultfinal_ano.value != "" 
         && (    document.form1.ed52_i_ano.value > document.form1.ed52_d_resultfinal_ano.value 
              || document.form1.ed52_i_ano.value < document.form1.ed52_d_resultfinal_ano.value ) 
       ) {
      
      alert( "Ano da Data Resultado Final está diferente do Ano do calendário!" );
      
      document.form1.ed52_d_resultfinal_dia.value = "";
      document.form1.ed52_d_resultfinal_mes.value = "";
      document.form1.ed52_d_resultfinal_ano.value = "";
      document.form1.ed52_d_resultfinal.value     = "";
      document.form1.ed52_d_resultfinal_dia.focus();
    } else {
      
      datafim    = document.form1.ed52_d_fim_ano.value + document.form1.ed52_d_fim_mes.value + document.form1.ed52_d_fim_dia.value;
      dataresfim = document.form1.ed52_d_resultfinal_ano.value + document.form1.ed52_d_resultfinal_mes.value + document.form1.ed52_d_resultfinal_dia.value;
      
      if (    datafim > dataresfim && document.form1.ed52_d_resultfinal_dia.value != "" 
           && document.form1.ed52_d_resultfinal_mes.value                         != "" 
           && document.form1.ed52_d_resultfinal_ano.value                         != "" ) {
        
        alert( "Data Resultado Final deve ser maior que a Data Final!" );
        
        document.form1.ed52_d_resultfinal_dia.value = "";
        document.form1.ed52_d_resultfinal_mes.value = "";
        document.form1.ed52_d_resultfinal_ano.value = "";
        document.form1.ed52_d_resultfinal.value     = "";
        document.form1.ed52_d_resultfinal_dia.focus();
      }
    }
  }
}

function js_ano() {
  
  if (    document.form1.ed52_d_inicio_ano.value != "" 
       && document.form1.ed52_i_ano.value        != "" 
       && document.form1.ed52_d_inicio_ano.value != document.form1.ed52_i_ano.value ) {
    
    alert( "Ano do calendário está diferente do ano da Data Inicial!" );
    document.form1.ed52_i_ano.value = "";
    document.form1.ed52_i_ano.focus();
  }
}

/**
 * Pesquisa os calendarios Base (sem vinculo com escola)
 */
function js_importarCalendario() {
  
  js_OpenJanelaIframe(
                       '',
                       'db_iframe_calendario',
                       "func_calendariobase.php?funcao_js=parent.js_carregaDadosCalendario|ed52_i_codigo",
                       'Pesquisa Calendário',
                       true
                    );
}

/**
 * carrega os dados do calendário
 */
function js_carregaDadosCalendario (iCalendario) {

  js_divCarregando('Carregando, aguarde.', 'msgbox');

  var oObject              = new Object();
      oObject.exec         = "carregaDadosCalendario";
      oObject.iCalendario  = iCalendario;

  var objAjax = new Ajax.Request (sRpc, {
                                         method:     'post',
                                         parameters: 'json='+Object.toJSON(oObject),
                                         onComplete: js_retornoDadosCalendarioClone
                                        }
                                   );
}

/**
 * Retorno do vinculo
 */
function js_retornoDadosCalendarioClone(oJson) {

  js_removeObj('msgbox');

  var oRetorno = eval("(" + oJson.responseText + ")");

  if ( oRetorno.status == 1 ) {

    db_iframe_calendario.hide();

    $('ed52_c_descr').value        = oRetorno.oDadosCalendarioClone.sDescricao.urlDecode();
    $('ed52_i_duracaocal').value   = oRetorno.oDadosCalendarioClone.iPeriodicidade;
    $('periodos').style.visibility = "hidden";
    
    if ($('ed52_i_duracaocal').value == 2) {

      $('periodos').style.visibility = "visible";
      $('ed52_i_periodo').value      = oRetorno.oDadosCalendarioClone.iPeriodo;
    }

    $('ed55_c_descr').value        = oRetorno.oDadosCalendarioClone.sDescricaoPeriodicidade.urlDecode();
    $('ed52_i_ano').value          = oRetorno.oDadosCalendarioClone.iAno;

    var aDataInicio                = oRetorno.oDadosCalendarioClone.dDataInicio.split("/");
    $('ed52_d_inicio').value       = oRetorno.oDadosCalendarioClone.dDataInicio;
    $('ed52_d_inicio_dia').value   = aDataInicio[0];
    $('ed52_d_inicio_mes').value   = aDataInicio[1];
    $('ed52_d_inicio_ano').value   = aDataInicio[2];

    var aDataFim                   = oRetorno.oDadosCalendarioClone.dDataFim.split("/");
    $('ed52_d_fim').value          = oRetorno.oDadosCalendarioClone.dDataFim;
    $('ed52_d_fim_dia').value      = aDataFim[0];
    $('ed52_d_fim_mes').value      = aDataFim[1];
    $('ed52_d_fim_ano').value      = aDataFim[2];

    var aDataResultadoFinal           = oRetorno.oDadosCalendarioClone.dDataResultadoFinal.split("/");
    $('ed52_d_resultfinal').value     = oRetorno.oDadosCalendarioClone.dDataResultadoFinal;
    $('ed52_d_resultfinal_dia').value = aDataResultadoFinal[0];
    $('ed52_d_resultfinal_mes').value = aDataResultadoFinal[1];
    $('ed52_d_resultfinal_ano').value = aDataResultadoFinal[2];

    $('ed52_i_diasletivos').value  = oRetorno.oDadosCalendarioClone.iDiasLetivos;
    $('ed52_i_semletivas').value   = oRetorno.oDadosCalendarioClone.iSemanasLetivas;
    $('ed52_i_calendant').value    = oRetorno.oDadosCalendarioClone.iCodigoCalendarioAnterior;
    $('ed52_c_descrant').value     = oRetorno.oDadosCalendarioClone.sDescricaoCalendarioAnterior.urlDecode();

    $('ed52_i_codigo_base').value = oRetorno.iCodigoBaseCalendario;
  } else {
    alert( oRetorno.message.urlDecode() );
  }
}

/**
 * Vincula o Calendario a escola atual
 */
function js_carregaCalendario () {

  if ( !validaCampos() ) {
    return false;
  }
  
	$('db_opcao').disabled = true;

  var oObject                   = new Object();
      oObject.exec              = "vinculaCalendarioBaseEscola";
      oObject.sOpcao            = $F('db_opcao');
      oObject.iCodigoCalendario = $F('ed52_i_codigo');
      oObject.sDescricao        = encodeURIComponent(tagString($F('ed52_c_descr')));
      oObject.iPeriodicidade    = $F('ed52_i_duracaocal');
      oObject.iPeriodo          = $F('ed52_i_periodo');

  if (oObject.iPeriodicidade == 1) {
    oObject.iPeriodo = "0";
  }

  oObject.iAno                      = $F('ed52_i_ano');
  oObject.dDataInicio               = $F('ed52_d_inicio');
  oObject.dDataFim                  = $F('ed52_d_fim');
  oObject.dDataResultadoFinal       = $F('ed52_d_resultfinal');
  oObject.iDiasLetivos              = $F('ed52_i_diasletivos');
  oObject.iSemanasLetivas           = $F('ed52_i_semletivas');
  oObject.iCodigoCalendarioAnterior = $F('ed52_i_calendant');
  oObject.iCodigoCalendarioBase     = $F('ed52_i_codigo_base');

  var objAjax   = new Ajax.Request (sRpc,{
                                           method:'post',
                                           parameters:'json='+Object.toJSON(oObject),
                                           onComplete:js_retornoVinculoCalendario
                                          }
                                    );

}

/**
 * Valida se os campos foram preenchidos
 */
function validaCampos() {

  if ( empty( $F('ed52_c_descr') ) ) {

    alert( 'É necessário informar o nome do calendário.' );
    $('ed52_c_descr').focus();
    return false;
  }

  if ( empty( $F('ed52_i_duracaocal') ) || empty( $F('ed55_c_descr') ) ) {

    alert( 'É necessário informar a duração do calendário.' );
    $('ed52_i_duracaocal').focus();
    return false;
  }

  if ( empty( $F('ed52_i_ano') ) ) {

    alert( 'É necessário informar o ano do calendário.' );
    $('ed52_i_ano').focus();
    return false;
  }
  
  return true;
}

/**
 * Retorno do vinculo
 */
function js_retornoVinculoCalendario(oJson) {

	var oRetorno = eval('('+oJson.responseText+')');

  if (oRetorno.status == 1) {

    if ( $F('ed52_i_codigo') != "" ) {
      alert( "Calendário alterado com sucesso!" );
    } else {
      alert( "Calendário incluído com sucesso!" );
    }

    top.corpo.iframe_a1.location.href = 'edu1_calendario002.php?chavepesquisa='+oRetorno.iCalendario;

  } else {

	  $('db_opcao').disabled = false;
    alert( oRetorno.message.urlDecode() );
  }
}


/**
* excluir calendario
*/
function js_excluiCalendario () {

 var oObject                   = new Object();
     oObject.exec              = "excluirCalendario";
     oObject.iCodigoCalendario = $F('ed52_i_codigo');

 new Ajax.Request ( sRpc, {
                            method:     'post',
                            parameters: 'json='+Object.toJSON(oObject),
                            onComplete: js_retornoExcluiCalendario
                          } );
}

/**
* Retorno do vinculo
*/
function js_retornoExcluiCalendario(oJson) {

 var oRetorno = eval( "(" + oJson.responseText + ")" );

 alert( oRetorno.message.urlDecode() );
 if ( oRetorno.status == 1 ) {
   js_pesquisa();
 }
}

</script>