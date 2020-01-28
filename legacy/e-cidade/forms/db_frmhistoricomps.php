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
?>
<form name="form2">
 <table>
  <tr>
   <td valign="top" bgcolor="#CCCCCC">
    <input id="escolaRede" type="radio" name="opcao" value="ER" onclick="js_direciona1();"
            <?=$db_opcao == 1 ? "checked" : ""?>><label for="escolaRede">Escolas da rede municipal</label><br>
   </td>
   <td>
    <input id="escolaFora" type="radio" name="opcao" value="EF" onclick="js_direciona2();"> <label for="escolaFora">Outras escolas</label><br><br>
   </td>
  </tr>
 </table>
</form>
<script>
var iEnsino = <?php echo isset( $ed11_i_ensino ) ? $ed11_i_ensino : null;?>

function js_direciona1() {
  var redireciona  = "edu1_historicomps001.php?ed62_i_historico=<?=$ed62_i_historico?>";
      redireciona += "&ed29_c_descr=<?=$ed29_c_descr?>&ed29_i_codigo=<?=$ed29_i_codigo?>";
      redireciona += "&ed61_i_aluno=<?=$ed61_i_aluno?>&ed47_v_nome=<?=$ed47_v_nome?>";
      redireciona += "&ed11_i_ensino=" + iEnsino;
  location.href = redireciona;
}

function js_direciona2() {
  var redireciona  = "edu1_historicompsfora001.php?ed99_i_historico=<?=$ed62_i_historico?>";
      redireciona += "&ed29_c_descr=<?=$ed29_c_descr?>&ed29_i_codigo=<?=$ed29_i_codigo?>";
      redireciona += "&ed61_i_aluno=<?=$ed61_i_aluno?>&ed47_v_nome=<?=$ed47_v_nome?>";
      redireciona += "&ed11_i_ensino=" + iEnsino;
  location.href = redireciona;
}

parent.disciplina.location.href = "edu1_historicodisciplina.php?ed65_i_historicomps=<?=@$chavepesquisa?>";
</script>

<?
if (isset($situacao) && $situacao == "CONCLUÍDO") {
  $db_botao = false;
}

$oDaoHistoricoMps->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed61_i_codigo");
$clrotulo->label("ed18_i_codigo");
$clrotulo->label("ed11_i_codigo");
$clrotulo->label("ed57_i_codigo");
$clrotulo->label("ed06_i_codigo");
$clrotulo->label("ed11_i_ensino");
$clrotulo->label("ed11_i_sequencia");
$clrotulo->label("ed62_i_codigo");
$clrotulo->label("ed62_i_historico");
$clrotulo->label("ed29_c_descr");
$clrotulo->label("ed29_i_codigo");
$clrotulo->label("ed62_i_serie");
$clrotulo->label("ed11_c_descr");
$clrotulo->label("ed62_i_anoref");
$clrotulo->label("ed62_i_periodoref");
$clrotulo->label("Led62_i_turma");
$clrotulo->label("ed62_i_escola");
$clrotulo->label("ed18_c_nome");
$clrotulo->label("ed62_c_situacao");
$clrotulo->label("ed62_i_justificativa");
$clrotulo->label("ed06_c_descr");
$clrotulo->label("ed62_c_resultadofinal");
$clrotulo->label("ed62_i_diasletivos");
$clrotulo->label("ed62_i_qtdch");
$clrotulo->label("ed62_c_minimo");
$clrotulo->label("ed62_c_termofinal");
$clrotulo->label("ed62_percentualfrequencia");

$lExcluir = false;
?>

 <form name="form1" method="post" action="" class="container">
   <table class="form-container">
     <tr>
       <td nowrap title="<?=$Ted62_i_codigo?>">
         <?=$Led62_i_codigo?>
       </td>
       <td>
         <?php
         db_input( 'ed62_i_codigo', 20, $Ied62_i_codigo, true, 'text', 3 );
         ?>
       </td>
     </tr>
     <tr>
       <td nowrap title="<?=$Ted62_i_historico?>">
         <?php
         db_ancora( $Led62_i_historico, "js_pesquisaed62_i_historico(true);", 3 );
         ?>
       </td>
       <td>
         <?php
         $sScript = " onchange='js_pesquisaed62_i_historico(false);'";
         db_input( 'ed62_i_historico', 15, $Ied62_i_historico, true, 'text',   3, $sScript );
         db_input( 'ed29_c_descr',     40, $Ied29_c_descr,    true, 'text',   3 );
         db_input( 'ed29_i_codigo',    40, $Ied29_i_codigo,   true, 'hidden', 3 );
         db_input( 'ed11_i_ensino',    40, $Ied11_i_ensino,     true, 'hidden', 3 );
         db_input( 'ed11_i_sequencia', 40, $Ied11_i_sequencia,  true, 'hidden', 3 );
         ?>
       </td>
     </tr>
     <tr>
       <td nowrap title="<?=$Ted62_i_serie?>">
         <?php
         db_ancora( $Led62_i_serie, "js_pesquisaed62_i_serie(true);", $db_opcao1 );
         ?>
       </td>
       <td>
         <?php
         db_input( 'ed62_i_serie', 15, $Ied62_i_serie,  true, 'text', 3, " onchange='js_pesquisaed62_i_serie(false);'" );
         db_input( 'ed11_c_descr', 20, $Ied11_c_descr, true, 'text', 3 );
         ?>
       </td>
     </tr>
     <tr>
       <td nowrap title="<?=$Ted62_i_anoref?>">
         <?=$Led62_i_anoref?>
       </td>
       <td>
         <?php
         db_input( 'ed62_i_anoref', 4, $Ied62_i_anoref, true, 'text', $db_opcao );
         echo $Led62_i_periodoref;
         db_input( 'ed62_i_periodoref', 4, 1, true, 'text', $db_opcao );
         echo $Led62_i_turma;
         db_input( 'ed62_i_turma', 30, $Ied62_i_turma, true, 'text', $db_opcao );
         ?>
       </td>
     </tr>
     <tr>
       <td nowrap title="<?=$Ted62_i_escola?>">
         <?php
         db_ancora( $Led62_i_escola, "js_pesquisaed62_i_escola(true);", $db_opcao );
         ?>
       </td>
       <td>
         <?php
         db_input( 'ed62_i_escola', 15, $Ied62_i_escola, true, 'text', $db_opcao, " onchange='js_pesquisaed62_i_escola(false);'" );
         db_input( 'ed18_c_nome',   30, $Ied18_c_nome,  true, 'text', 3 );
         ?>
       </td>
     </tr>
     <tr>
       <td nowrap title="<?=$Ted62_c_situacao?>">
         <?=$Led62_c_situacao?>
       </td>
       <td>
         <?php
         $x = array(
                     ''               => '',
                     'CONCLUÍDO'      => 'CONCLUÍDO',
                     'AMPARADO'       => 'AMPARADO',
                     'TRANSFERIDO'    => 'TRANSFERIDO',
                     'CANCELADO'      => 'CANCELADO',
                     'EVADIDO'        => 'EVADIDO',
                     'FALECIDO'       => 'FALECIDO',
                     'RECLASSIFICADO' => 'RECLASSIFICADO'
                   );
         db_select( 'ed62_c_situacao', $x, true, $db_opcao, " onchange='js_situacao(this);'" );
         ?>
       </td>
     </tr>
     <tr>
       <td colspan="2">
         <fieldset>
           <legend class="bold">Observação</legend>
           <?php
             db_textarea( 'ed62_observacao', 4, 10, $Ied62_observacao, true, '', 1, '', '', '', 500 );
           ?>
         </fieldset>
       </td>
     </tr>
     <tr>
       <td colspan="2">
         <div name="situacao" id="situacao">
           <fieldset>
             <legend>
               <?php
               $ed62_c_situacao = isset( $ed62_c_situacao ) ? $ed62_c_situacao : "";
               ?>
               <input type="text"
                      name="legenda"
                      value="<?=$ed62_c_situacao?>"
                      size="15"
                      style="border: 0px; background: #cccccc; font-weight: bold; text-align: center;">
             </legend>
             <table>
               <tr id="justificativa">
                 <td nowrap title="<?=$Ted62_i_justificativa?>">
                   <?php
                   db_ancora( $Led62_i_justificativa, "js_pesquisaed62_i_justificativa(true);", $db_opcao );
                   ?>
                 </td>
                 <td>
                   <?php
                   $sScript = " onchange='js_pesquisaed62_i_justificativa(false);'";
                   db_input( 'ed62_i_justificativa', 10, $Ied62_i_justificativa, true, 'text', $db_opcao, $sScript );
                   db_input( 'ed06_c_descr',         30, $Ied06_c_descr,        true, 'text',         3 );
                   ?>
                 </td>
               </tr>
               <tr id="resultado">
                 <td nowrap title="<?=$Ted62_c_resultadofinal?>">
                   <?=$Led62_c_resultadofinal?>
                 </td>
                 <td>
                   <?php
                   $x = array( ''=>'', 'A'=>'APROVADO', 'R'=>'REPROVADO', 'P'=>'APROVADO PARCIALMENTE' );
                   db_select( 'ed62_c_resultadofinal', $x, true, $db_opcao );
                   ?>
                 </td>
                 <td>
                   <?=$Led62_i_diasletivos?>
                 </td>
                 <td>
                   <?php
                   db_input( 'ed62_i_diasletivos', 10, $Ied62_i_diasletivos, true, 'text', $db_opcao );
                   ?>
                 </td>
               </tr>
               <tr>
                 <td nowrap title="<?=$Ted62_i_qtdch?>">
                   <?=$Led62_i_qtdch?>&nbsp;&nbsp;&nbsp;
                 </td>
                 <td>
                   <?php
                   $ed62_i_qtdch = isset( $ed62_i_qtdch ) && !empty( $ed62_i_qtdch ) ? DBNumber::truncate( $ed62_i_qtdch ) : '';
                   db_input( 'ed62_i_qtdch', 10, $Ied62_i_qtdch, true, 'text', $db_opcao );
                   ?>
                 </td>
                 <td nowrap title="<?=$Ted62_c_minimo?>" id="labelMinimo">
                   <?=$Led62_c_minimo?>
                 </td>
                 <td id="inputMinimo">
                   <?php
                   db_input( 'ed62_c_minimo', 20, $Ied62_c_minimo, true, 'text', $db_opcao );
                   ?>
                 </td>
               </tr>
               <tr>
                 <td>
                   <?=$Led62_c_termofinal?>
                 </td>
                 <td>
                   <?php
                   db_input( 'ed62_c_termofinal', 10, $Ied62_c_termofinal, true, 'text', $db_opcao );
                   ?>
                 </td>
                 <td>
                   <?=$Led62_percentualfrequencia?>
                 </td>
                 <td>
                   <?php
                   db_input( 'ed62_percentualfrequencia', 6, $Ied62_percentualfrequencia, true, 'text', $db_opcao, 'onblur="js_validaFrequencia(this)"', '', '', '', '5' );
                   ?>
                 </td>
               </tr>
             <tr style="display: none;">
               <td>
                 <input id="lExcluir" name="lExcluir" value="<?=$lExcluir;?>">
               </td>
             </tr>
             </table>
           </fieldset>
         </div>
       </td>
     </tr>
   </table>
   <?php
   if ($db_opcao == 1) {
   ?>

     <input name="incluir"
            type="submit"
            id="btnIncluir"
            value="Incluir"
            <?=($db_botao==false?"disabled":"")?>
            onclick="return js_minimoaprov();">
   <?php
   } else {
   ?>

     <input name="alterar"
            type="submit"
            id="btnAlterar"
            value="Alterar"
            <?=($db_botao==false?"disabled":"")?>
            onclick="return js_minimoaprov();">

     <input name="excluir"
            type="button"
            id="btnExcluir"
            value="Excluir"
            <?=($db_botao==false?"disabled":"")?>
            onclick="js_confirmaExclusao();" >
   <?php
   }
   ?>
</form>
<script>

var iEnsinoSelecionado        = $F('ed11_i_ensino');
var iOrdemEtapaSelecionada    = $F('ed11_i_sequencia');
var iOrdemEtapaAtual          = CurrentWindow.corpo.oDadosManutencaoHistorico.aSenquenciaEtapas[iEnsinoSelecionado];
var iStatusAlteracaoHistorico = CurrentWindow.corpo.oDadosManutencaoHistorico.iStatusAlteracaoHistorico;

$('ed62_i_qtdch').setAttribute('maxlength', '7');
$('ed62_i_diasletivos').setAttribute('maxlength', '3');

var sCaminhoMensagem = "educacao.escola.db_frmhistoricomps.";

function js_validaFrequencia(oPercentualFrequencia) {

  var aPercentualFrequencia = oPercentualFrequencia.value.split('-');
  if (aPercentualFrequencia.length > 1) {

    alert( _M( sCaminhoMensagem + 'percentual_frequencia_negativo' ) );
    return false;
  }

  if (oPercentualFrequencia.value > 100.00) {

    alert( _M( sCaminhoMensagem + 'percentual_frequencia_maximo' ) );
    return false;
  }

  if (oPercentualFrequencia.value < 0.00) {

    alert( _M( sCaminhoMensagem + 'percentual_frequencia_negativo' ) );
    return false;
  }
}

<?php
if (
         isset($ed62_c_situacao) && trim($ed62_c_situacao) == "CONCLUÍDO"
      || isset($ed62_c_situacao) && trim($ed62_c_situacao) == "RECLASSIFICADO"
   ) {
?>

  document.getElementById("resultado").style.display     = "";
  document.getElementById("labelMinimo").style.display   = "";
  document.getElementById("inputMinimo").style.display   = "";
  document.getElementById("justificativa").style.display = "none";
<?php
} else if(isset($ed62_c_situacao) && trim($ed62_c_situacao) == "AMPARADO") {
?>

  document.getElementById("justificativa").style.display = "";
  document.getElementById("resultado").style.display     = "none";
  document.getElementById("labelMinimo").style.display   = "none";
  document.getElementById("inputMinimo").style.display   = "none";
<?php
} else {
?>
  document.getElementById("situacao").style.display = "none";
<?php
}
?>

function js_situacao(campo) {

  if (campo.value == "CONCLUÍDO" || campo.value == "RECLASSIFICADO") {

    document.getElementById("situacao").style.display      = "";
    document.getElementById("justificativa").style.display = "none";
    document.getElementById("resultado").style.display     = "";
    document.getElementById("labelMinimo").style.display   = "";
    document.getElementById("inputMinimo").style.display   = "";
    document.form1.legenda.value                           = campo.value;
    document.getElementById("ed62_c_resultadofinal").value = "A";
  } else if (campo.value == "AMPARADO") {

    document.getElementById("situacao").style.display      = "";
    document.getElementById("justificativa").style.display = "";
    document.getElementById("resultado").style.display     = "none";
    document.getElementById("labelMinimo").style.display   = "none";
    document.getElementById("inputMinimo").style.display   = "none";
    document.form1.legenda.value                           = "AMPARADO";
    document.getElementById("ed62_c_resultadofinal").value = "";
  } else {

    document.getElementById("situacao").style.display      = "none";
    document.getElementById("justificativa").style.display = "none";
    document.getElementById("resultado").style.display     = "none";
    document.getElementById("labelMinimo").style.display   = "none";
    document.getElementById("inputMinimo").style.display   = "none";
    document.getElementById("ed62_c_resultadofinal").value = "";
  }
}

function js_pesquisaed62_i_escola(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('parent', 'db_iframe_escola', 'func_escola.php?funcao_js=parent.dados.js_mostraescola1|'+
    	                'ed18_i_codigo|ed18_c_nome', 'Pesquisa de Escolas', true
    	               );
  } else {

    if (document.form1.ed62_i_escola.value != '') {

      js_OpenJanelaIframe('parent', 'db_iframe_escola',
    	                  'func_escola.php?pesquisa_chave='+document.form1.ed62_i_escola.value+
    	                  '&funcao_js=parent.dados.js_mostraescola', 'Pesquisa', false
    	                 );
    } else {
      document.form1.ed18_c_nome.value = '';
    }
  }
}

function js_mostraescola(chave, erro) {

  document.form1.ed18_c_nome.value = chave;

  if (erro == true) {

    document.form1.ed62_i_escola.focus();
    document.form1.ed62_i_escola.value = '';
  }
}

function js_mostraescola1(chave1, chave2) {

  document.form1.ed62_i_escola.value = chave1;
  document.form1.ed18_c_nome.value   = chave2;
  parent.db_iframe_escola.hide();
}

function js_pesquisaed62_i_serie(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('parent', 'db_iframe_serie',
                        'func_seriehist.php?historico=' + $F('ed62_i_historico') +
                        '&iStatusAlteracaoHistorico='   + iStatusAlteracaoHistorico +
                        '&iOrdemEtapaAtual='            + iOrdemEtapaAtual          +
    	                '&funcao_js=parent.dados.js_mostraserie1|ed11_i_codigo|ed11_c_descr|db_ensino|ed11_i_sequencia', 'Pesquisa de Etapas', true
    	               );
  } else {

    if (document.form1.ed62_i_serie.value != '') {

      js_OpenJanelaIframe('parent', 'db_iframe_serie',
    	                  'func_seriehist.php?historico=' + $F('ed62_i_historico') +
                        '&iStatusAlteracaoHistorico='   + iStatusAlteracaoHistorico +
                        '&iOrdemEtapaAtual='            + iOrdemEtapaAtual          +
    	                  '&pesquisa_chave='+document.form1.ed62_i_serie.value+'&funcao_js=parent.dados.js_mostraserie',
    	                  'Pesquisa', false
    	                 );
    } else {
      document.form1.ed11_c_descr.value = '';
    }
  }
}

function js_mostraserie(chave, erro) {

  document.form1.ed11_c_descr.value = chave;
  if (erro == true) {

    document.form1.ed62_i_serie.focus();
    document.form1.ed62_i_serie.value = '';
  }
}

function js_mostraserie1(chave1, chave2) {


  document.form1.ed62_i_serie.value = chave1;
  document.form1.ed11_c_descr.value = chave2;
  $('ed11_i_ensino').value          = arguments[2];
  $('ed11_i_sequencia').value       = arguments[3];
  iEnsinoSelecionado                = arguments[2];
  iOrdemEtapaSelecionada            = arguments[3];

  validaBloquearBotao();
  parent.db_iframe_serie.hide();
}

function js_pesquisaed62_i_turma(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('parent', 'db_iframe_turma', 'func_turma.php?funcao_js=parent.dados.js_mostraturma1|'+
    	                'ed57_i_codigo|ed57_c_descr', 'Pesquisa de Turmas', true
    	               );
  } else {

    if (document.form1.ed62_i_turma.value != '') {

      js_OpenJanelaIframe('parent', 'db_iframe_turma', 'func_turma.php?pesquisa_chave='+document.form1.ed62_i_turma.value+
    	                  '&funcao_js=parent.dados.js_mostraturma', 'Pesquisa', false
    	                 );
    } else {
      document.form1.ed57_c_descr.value = '';
    }
  }
}

function js_mostraturma(chave, erro) {

  document.form1.ed57_c_descr.value = chave;
  if (erro == true) {

    document.form1.ed62_i_turma.focus();
    document.form1.ed62_i_turma.value = '';
  }
}

function js_mostraturma1(chave1, chave2) {

  document.form1.ed62_i_turma.value = chave1;
  document.form1.ed57_c_descr.value = chave2;
  parent.db_iframe_turma.hide();
}

function js_pesquisaed62_i_justificativa(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('parent', 'db_iframe_justificativa',
    	                'func_justificativa.php?funcao_js=parent.dados.js_mostrajustificativa1|'+
    	                'ed06_i_codigo|ed06_c_descr', 'Pesquisa de Justificativas', true
    	               );
  } else {

    if (document.form1.ed62_i_justificativa.value != '') {

      js_OpenJanelaIframe('parent', 'db_iframe_justificativa',
    	                  'func_justificativa.php?pesquisa_chave='+document.form1.ed62_i_justificativa.value+
    	                  '&funcao_js=parent.dados.js_mostrajustificativa', 'Pesquisa', false
    	                 );
    } else {
      document.form1.ed06_c_descr.value = '';
    }
  }
}

function js_mostrajustificativa(chave, erro) {

  document.form1.ed06_c_descr.value = chave;
  if (erro == true) {

    document.form1.ed62_i_justificativa.focus();
    document.form1.ed62_i_justificativa.value = '';
  }
}

function js_mostrajustificativa1(chave1, chave2) {

  document.form1.ed62_i_justificativa.value = chave1;
  document.form1.ed06_c_descr.value         = chave2;
  parent.db_iframe_justificativa.hide();
}

function js_novaescola() {

  js_OpenJanelaIframe('parent', 'db_iframe_novaescola', 'edu1_escolaprocnova001.php',
		              'Nova Escola de Procedência', true, 0, 0, 780, 405
		             );
}

function js_minimoaprov() {

  if (document.form1.ed62_c_situacao.value == "CONCLUÍDO" && document.form1.ed62_c_minimo.value == "") {

    alert( _M( sCaminhoMensagem + 'preencha_minimo_aprovacao' ) );
    return false;
  }

  if (document.getElementById('ed62_i_escola').value == '') {

    alert( _M( sCaminhoMensagem + 'preencha_escola' ) );
    return false;
  }

  if (document.getElementById('ed62_i_serie').value == '') {

    alert( _M( sCaminhoMensagem + 'preencha_etapa' ) );
    return false;
  }

  if (document.getElementById('ed62_i_anoref').value == '') {

    alert( _M( sCaminhoMensagem + 'preencha_ano' ) );
    return false;
  }

  if( !empty( $F('ed62_i_qtdch') ) ) {

    var aValorCargaHoraria = $F('ed62_i_qtdch').split( '.' );

    if(    aValorCargaHoraria[0] == ''
        || ( aValorCargaHoraria[0] != '' && aValorCargaHoraria[0].length > 4 )
      ) {

      alert( _M( sCaminhoMensagem + 'valor_invalido_carga_horaria' ) );
      $('ed62_i_qtdch').focus();
      return false;
    }

    if( aValorCargaHoraria.length > 1 ) {

      if(     aValorCargaHoraria[1] == ''
           || ( aValorCargaHoraria[1] != '' && ( aValorCargaHoraria[1].length == 0 || aValorCargaHoraria[1].length > 2 ) )
        ) {

        alert( _M( sCaminhoMensagem + 'valor_invalido_carga_horaria' ) );
        $('ed62_i_qtdch').focus();
        return false;
      }
    }
  }

  return true;
}

function $ ( sElemento ) {
  return document.getElementById(sElemento);
}

$('ed62_i_codigo')      .setAttribute("class", 'field-size2');
$('ed62_i_historico')   .setAttribute("class", 'field-size2');
$('ed29_c_descr')       .setAttribute("class", 'field-size7');
$('ed62_i_serie')       .setAttribute("class", 'field-size2');
$('ed11_c_descr')       .setAttribute("class", 'field-size7');
$('ed62_i_anoref')      .setAttribute("class", 'field-size2');
$('ed62_i_periodoref')  .setAttribute("class", 'field-size2');
$('ed62_i_turma')       .style.width = '118px';
$('ed62_i_escola')      .setAttribute("class", 'field-size2');
$('ed18_c_nome')        .setAttribute("class", 'field-size7');
$('ed62_c_situacao')    .setAttribute("rel", 'ignore-css');
$('ed62_c_situacao')    .setAttribute("class", 'field-size9');

function js_confirmaExclusao() {

  if ( confirm( _M( sCaminhoMensagem + "confirma_exclusao" ) ) ) {

    $('lExcluir').value = true;
    document.form1.submit();
  }
}


/*
 * Valida se Escola selecionada tem permissão de manutenção do histórico do aluno.
 */
function validaBloquearBotao() {

  liberarBotao();
  var oHistorico = new HistoricoEscolar( iStatusAlteracaoHistorico,
                                       iOrdemEtapaAtual,
                                       iOrdemEtapaSelecionada );

  if ( !oHistorico.permiteManutencao() ) {
    <?php if ($db_opcao == 1) { ?>
      $('btnIncluir').setAttribute('disabled', 'disabled');
    <?php } else { ?>
      $('btnAlterar').setAttribute('disabled', 'disabled');
      $('btnExcluir').setAttribute('disabled', 'disabled');
    <?php } ?>
  }
};

function liberarBotao() {

  if ( $('btnIncluir') ){
    $('btnIncluir').removeAttribute('disabled');
  }
  if ( $('btnAlterar') ){
    $('btnAlterar').removeAttribute('disabled');
  }
  if ( $('btnExcluir') ){
    $('btnExcluir').removeAttribute('disabled');
  }
};

validaBloquearBotao();

</script>