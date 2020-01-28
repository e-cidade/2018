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
<script>
  parent.disciplina.location.href = "edu1_historicodisciplina.php?ed65_i_historicomps=<?=@$ed65_i_historicomps?>";
</script>
<?
require_once(modification("libs/db_app.utils.php"));
db_app::import("educacao.*");

if (isset($situacao) && $situacao == "CONCLUÍDO") {
  $db_botao = false;
}

if ( $ed62_c_situacao != "CONCLUÍDO" && $ed62_c_situacao != "RECLASSIFICADO" ) {

  $db_botao = false;
  db_msgbox("{$ed11_c_descr} ano {$ed62_i_anoref} com situação {$ed62_c_situacao}. Não é possível incluir disciplinas!");
}

$oDaoHistMpsDisc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed62_i_codigo");
$clrotulo->label("ed12_i_codigo");
$clrotulo->label("ed62_i_anoref");
$clrotulo->label("ed62_i_periodoref");
$clrotulo->label("ed11_i_ensino");
$clrotulo->label("ed11_i_sequencia");
$clrotulo->label("ed11_c_descr");
$clrotulo->label("ed62_c_minimo");
$clrotulo->label("ed62_i_serie");
$clrotulo->label("ed61_i_codigo");
$clrotulo->label("ed29_c_descr");

?>
<form name="form1">
  <center>
    <table border="0" width="100%">
      <tr>
        <td nowrap>
          <?php
          db_ancora( "<label class='bold'>Etapa:</label>", "", 3 );
          ?>
        </td>
        <td>
          <?php
          db_input( 'ed65_i_historicomps',   15, $Ied65_i_historicomps, true, 'text',   3 );
          db_input( 'ed11_c_descr',          40, $Ied11_c_descr,        true, 'text',   3 );
          db_input( 'ed62_i_codigo',         40, $Ied62_i_codigo,       true, 'hidden', 3 );
          db_input( 'ed62_c_minimo',         40, $Ied62_c_minimo,       true, 'hidden', 3 );
          db_input( 'ed62_c_resultadofinal', 40, $Ied62_c_minimo,       true, 'hidden', 3 );
          db_input( 'ed61_i_curso',          40, $Ied62_c_minimo,       true, 'hidden', 3 );
          db_input( 'ed62_i_serie',          40, $Ied62_i_serie,        true, 'hidden', 3 );
          db_input( 'ed11_i_ensino',         40, $Ied11_i_ensino,       true, 'hidden', 3 );
          db_input( 'ed11_i_sequencia',      40, $Ied11_i_sequencia,    true, 'hidden', 3 );
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=$Ted65_i_historicomps?>">
          <?php
          db_ancora( $Led65_i_historicomps, "", 3 );
          ?>
        </td>
        <td>
          <?php
          db_input( 'ed61_i_codigo', 15, $Ied61_i_codigo, true, 'text', 3 );
          db_input( 'ed29_c_descr',  40, $Ied29_c_descr,  true, 'text', 3 );
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=$Ted62_i_anoref?>">
          <?=$Led62_i_anoref?>
        </td>
        <td>
          <?php
          db_input( 'ed62_i_anoref', 4, $Ied62_i_anoref, true, 'text', 3 );
          echo $Led62_i_periodoref;
          db_input( 'ed62_i_periodoref', 4, $Ied62_i_periodoref, true, 'text', 3 );
          ?>
          <input type="button"
                 id="btnLancarDisciplina"
                 name="btnLancarDisciplina"
                 value="Lançar Disciplina"
                 <?=($db_botao==false?"disabled":"")?>
                 onclick="parent.js_lancarDisciplina($F('ed65_i_historicomps'), 1, iEnsino, iHistoricomps, $F('ed61_i_curso'), $F('ed62_i_anoref'));" />
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <table border="1" cellspacing="0" cellpading="0" width="100%">
           <!--
            não remover a linhas abaixo.
            os campos estão como hidden, para as funções javascrip da rotina,
            aceitar os campos como array.
          -->
          <input type="checkbox" name="individual" style='display:none' value="true">
          <input type="hidden" name="ed65_i_codigo" value="<?php if ( isset($ed65_i_codigo) )  echo $ed65_i_codigo; else echo "";?>">
          <input type="hidden" name="ed65_i_disciplina" value="<?php if ( isset($ed12_i_codigo) )  echo $ed12_i_codigo; else echo "";?>">
          <input type="hidden" name="ed65_c_tiporesultado" value="<?php if ( isset($ed65_c_tiporesultado) )  echo $ed65_c_tiporesultado; else echo "";?>">
          <input type="hidden" name="ed65_c_situacao"  />
          <input type="hidden" name="ed65_i_qtdch"  />
          <input type="hidden" name="ed65_c_resultadofinal"  />
          <input type="hidden" name="ed65_i_justificativa"  />
          <input type="hidden" name="ed65_t_resultobtido"  />
          <input type="hidden" name="ed65_c_termofinal"  />
          <input type="hidden" name="ed06_c_descr"  />
          <input type="hidden" name="sTipoRede" id="sTipoRede" value="1" />
          <tr class="titulo">
            <td>Tipo da Base</td>
            <td>Disciplina</td>
            <td>Situação</td>
            <td>CH</td>
            <td width="110">Resultado</td>
            <td width="95">Aproveit.</td>
            <td width="95">Termo Final</td>
          </tr>
          <?php
          $ed65_i_codigo        = "";
          $ed65_c_tiporesultado = "";

          $sWhereHistMpsDisc    = "ed65_i_historicomps = {$ed65_i_historicomps}";
          $sSqlHistMpsDisc      = $oDaoHistMpsDisc->sql_query("", "*", " ed65_basecomum desc, ed65_i_ordenacao", $sWhereHistMpsDisc);
          $rsHistMpsDisc        = $oDaoHistMpsDisc->sql_record($sSqlHistMpsDisc);
          $iLinhasHistMpsDisc   = $oDaoHistMpsDisc->numrows;

          for ($iTotalLinhas = 0; $iTotalLinhas < $iLinhasHistMpsDisc; $iTotalLinhas++) {

            db_fieldsmemory($rsHistMpsDisc, $iTotalLinhas);
            $ed65_basecomum == 't' ? $sBaseComum = "BASE COMUM" : $sBaseComum = "DIVERSIFICADA";
          ?>

            <tr onmouseover="Mostra('disc<?=$iTotalLinhas?>')" onmouseout="Oculta('disc<?=$iTotalLinhas?>')">
              <td><?php echo $sBaseComum; ?></td>
              <td style="font-size:10px;">
                <input type="checkbox"
                       name="individual"
                       value="true"
                       <?=$ed65_i_codigo!=""?"checked":""?>
                       onclick="MarcaIndividual(this.value, <?=$iTotalLinhas?>)">
                <input type="hidden" name="ed65_i_codigo" value="<?=$ed65_i_codigo?>">
                <input type="hidden" name="ed65_i_disciplina" value="<?=$ed12_i_codigo?>">
                <input type="hidden" name="ed65_c_tiporesultado" value="<?=$ed65_c_tiporesultado?>">
                <?=$ed232_c_abrev?>
              </td>
              <td>
                <?php
                $x = array(
                            ''            => '',
                            'CONCLUÍDO'   => 'CONCLUÍDO',
                            'AMPARADO'    => 'AMPARADO',
                            'NÃO OPTANTE' => 'NÃO OPTANTE'
                          );

                $sScript = " onchange='js_situacao(this, $iTotalLinhas);' style='width:100px;height:15px;font-size:10px;padding:0px;' ".
                          ( $ed65_i_codigo == "" ? "disabled" : "" ) . "";
                db_select( 'ed65_c_situacao', $x, true, $db_opcao, $sScript );
                ?>
              </td>
              <td>
                <?php
                $sScript = "" . ( $ed65_i_codigo == "" ? "disabled" : "" ) . "";

                $ed65_i_qtdch = isset( $ed65_i_qtdch ) && !empty( $ed65_i_qtdch ) ? DBNumber::truncate( $ed65_i_qtdch ) : '';
                db_input( 'ed65_i_qtdch', 4, $Ied65_i_qtdch, true, 'text', $db_opcao, $sScript );
                ?>
              </td>
              <td valign="top">
                <?php
                if (@$ed65_c_situacao == "CONCLUÍDO" || @$ed65_c_situacao == "NÃO OPTANTE") {
                  $visivel = "visible";
                } else if (@$ed65_c_situacao == "AMPARADO") {
                  $visivel = "hidden";
                } else {
                  $visivel = "hidden";
                }
                ?>
                <table id="resultado<?=$iTotalLinhas?>"
                       style="visibility:<?=$visivel?>;position:absolute;"
                       border="0"
                       cellspacing="2"
                       cellpading="2">
                  <tr>
                    <td>
                      <?php
                      $sOnchange = 'onchange="js_alunoAprovado(this, \''.$ed62_c_resultadofinal.'\');"';

                      /**
                       * Buscamos os termos referentes ao ensino e ano solicitado
                       */
                      $aTermos = DBEducacaoTermo::getTermoEncerramentoDoEnsino($ed11_i_ensino, $ed62_i_anoref);
                      if (count($aTermos) > 0) {

                        $r =  array('' => '');
                        foreach ($aTermos as $oTermo) {
                          $r[$oTermo->sReferencia] = $oTermo->sDescricao;
                        }
                      }

                      $sScript = " style='width:110px;height:15px;font-size:10px;padding:0px;' ".
                                ( $ed65_i_codigo == "" ? "disabled" : "" ) . " $sOnchange ";
                      db_select( 'ed65_c_resultadofinal', $r, true, $db_opcao, $sScript );
                      ?>
                    </td>
                    <td>
                      <?php
                      $aprov_resultado = @$ed65_t_resultobtido;
                      ?>
                     <input type="text"
                            name="ed65_t_resultobtido"
                            value="<?=@$aprov_resultado?>"
                            size="10"
                            <?=@$ed65_i_codigo == "" ? "disabled" : ""?>>
                    </td>
                    <td>
                      <?php
                      db_input( 'ed65_c_termofinal', 10, $Ied65_c_termofinal, true, 'text', $db_opcao );
                      ?>
                    </td>
                  </tr>
                </table>
                <?php
                if (@$ed65_c_situacao == "CONCLUÍDO" || @$ed65_c_situacao == "NÃO OPTANTE") {
                  $visivel = "hidden";
                } else if (@$ed65_c_situacao == "AMPARADO") {
                  $visivel = "visible";
                } else {
                  $visivel = "hidden";
                }
                ?>
                <table id="justificativa<?=$iTotalLinhas?>"
                       style="visibility:<?=$visivel?>;position:absolute;"
                       border="0"
                       cellspacing="0"
                       cellpading="0">
                  <tr>
                    <td nowrap title="<?=@$Ted65_i_justificativa?>">
                     <?php
                     $sScript = "js_pesquisaed65_i_justificativa(true, $iTotalLinhas);";
                     db_ancora( "<label class='bold'>Just.:</label>", $sScript, $db_opcao );
                     ?>
                    </td>
                    <td nowrap="nowrap">
                     <?php

                     $sScript = " onchange='js_pesquisaed65_i_justificativa(false, $iTotalLinhas);'";
                     db_input( 'ed65_i_justificativa',  5, $Ied65_i_justificativa, true, 'text', $db_opcao, $sScript );
                     db_input( 'ed06_c_descr',         12, @$Ied06_c_descr,        true, 'text',         3 );
                     ?>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td colspan="4">
                <table id="disc<?=$iTotalLinhas?>"
                       name="disc<?=$iTotalLinhas?>"
                       bgcolor="#f3f3f3"
                       style="border:2px outset #CCCCCC;position:absolute;visibility:hidden;"
                       border="1"
                       cellspacing="0"
                       cellpading="0">
                  <tr>
                    <td>
                    <label class="bold"><?=$ed232_c_descr?></label>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <?php
            $ed65_i_codigo         = "";
            $ed65_c_situacao       = "";
            $ed65_c_resultadofinal = "";
            $ed65_t_resultobtido   = "";
            $ed65_i_qtdch          = "";
            $ed65_c_tiporesultado  = "";
            $ed65_i_disciplina     = "";
            $ed65_i_justificativa  = "";
            $ed65_c_termofinal     = "";
            $ed06_c_descr          = "";
          }
          ?>
          </table>
        </td>
      </tr>
      <input type="hidden" name="justlinha" value="">
    </table>
  </center>
<?php
if ($iLinhasHistMpsDisc > 0) {

  if ($db_opcao == 1) {
?>

  <input name="incluir"
         id= "botao"
         type="button"
         value="Incluir"
         onclick="Salvar(<?=$iLinhasHistMpsDisc?>, true);"
         <?=($db_botao == false ? "disabled" : "")?> >
<?php
  } else {
?>
  <input name="alterar"
         id= "botao"
         type="button"
         value="Alterar"
         onclick="Salvar(<?=$iLinhasHistMpsDisc?>, true);"
         <?=($db_botao == false ? "disabled" : "")?> >
<?php
  }
}
?>
</form>
<script>
const MENSAGENS_FORMULARIO_HISTMPSDISC = 'educacao.escola.db_frmhistmpsdisc.';

var aCargasHorarias = document.getElementsByName('ed65_i_qtdch');
for( var iContador = 0; iContador < aCargasHorarias.length; iContador++ ) {
  aCargasHorarias[iContador].setAttribute('maxlength', '7');
}

var iEnsino       = "<?=$ed11_i_ensino;?>";
var iHistoricomps = "<?=$ed65_i_historicomps;?>";
var iTotalLinhas  = <?=$iTotalLinhas;?>;

function MarcaIndividual(valor, i) {

  var i = i+1;
  var iResultado  = i -1;

  if (document.form1.individual[i].checked == true) {

    document.form1.ed65_c_situacao[i].disabled              = false;
    document.form1.ed65_c_resultadofinal[i].disabled        = false;
    document.form1.ed65_t_resultobtido[i].disabled          = false;
    document.form1.ed65_i_qtdch[i].disabled                 = false;
    document.getElementById("resultado"+iResultado).style.visibility = "visible";
  } else {

    document.form1.ed65_c_situacao[i].disabled              = true;
    document.form1.ed65_c_resultadofinal[i].disabled        = true;
    document.form1.ed65_t_resultobtido[i].disabled          = true;
    document.form1.ed65_i_qtdch[i].disabled                 = true;
    document.getElementById("resultado"+iResultado).style.visibility = "hidden";
  }
}

function js_situacao(campo, linha) {

  document.form1.ed65_c_resultadofinal[linha+1].disabled = false;
  document.form1.ed65_i_qtdch[linha+1].disabled          = false;

  if (campo.value == "CONCLUÍDO") {

    document.getElementById("justificativa"+linha).style.visibility = "hidden";
    document.getElementById("resultado"+linha).style.visibility     = "visible";
  } else if(campo.value == "AMPARADO") {

    document.getElementById("justificativa"+linha).style.visibility = "visible";
    document.getElementById("resultado"+linha).style.visibility     = "hidden";
  } else if (campo.value == "NÃO OPTANTE") {

    document.getElementById("justificativa"+linha).style.visibility = "hidden";
    document.getElementById("resultado"+linha).style.visibility     = "visible";
    document.form1.ed65_c_resultadofinal[linha+1].disabled          = true;
    document.form1.ed65_i_qtdch[linha+1].disabled                   = true;
  } else {

    document.getElementById("justificativa"+linha).style.visibility = "hidden";
    document.getElementById("resultado"+linha).style.visibility     = "hidden";
  }
}

function js_pesquisaed65_i_justificativa(mostra, linha) {

  document.form1.justlinha.value = linha+1;
  linha++;
  if (mostra == true) {

    js_OpenJanelaIframe('parent', 'db_iframe_justificativa',
    	                'func_justificativa.php?funcao_js=parent.dados.js_mostrajustificativa1|ed06_i_codigo|ed06_c_descr',
    	                'Pesquisa de Justificativas', true, 0, 0
    	               );
  } else {

    if (document.form1.ed65_i_justificativa[linha].value != '') {

      js_OpenJanelaIframe('parent', 'db_iframe_justificativa',
    	                  'func_justificativa.php?pesquisa_chave='+document.form1.ed65_i_justificativa[linha].value+
    	                  '&funcao_js=parent.dados.js_mostrajustificativa', 'Pesquisa', false
    	                 );
    } else {
      document.form1.ed06_c_descr.value = '';
    }
  }
}

function js_mostrajustificativa(chave, erro) {

  document.form1.ed06_c_descr[document.form1.justlinha.value].value = chave;
  if (erro == true) {

    document.form1.ed65_i_justificativa[document.form1.justlinha.value].focus();
    document.form1.ed65_i_justificativa[document.form1.justlinha.value].value = '';
  }

  document.form1.justlinha.value = "";
}

function js_mostrajustificativa1(chave1, chave2) {

  document.form1.ed65_i_justificativa[document.form1.justlinha.value].value = chave1;
  document.form1.ed06_c_descr[document.form1.justlinha.value].value         = chave2;
  document.form1.justlinha.value                                            = "";
  parent.db_iframe_justificativa.hide();
}

function js_novadisciplina() {
  js_OpenJanelaIframe('parent', 'db_iframe_novadisciplina', 'edu1_disciplinanova001.php',
		               'Nova Disciplina', true, 0, 0, 780, 405
		             );
}

var registrodisc = "";


var lConfirmaAlteracaoNota = false;
function Salvar(linhas, lMensagem) {

  iMinimoAprov    = $F('ed62_c_minimo').replace(",", "."); //'<?=str_replace("'",  "\'",  $ed62_c_minimo)?>';
  sResultadoFinal = $F('ed62_c_resultadofinal');

  var lReprovado  = false;
  var alguem      = false;

  var lDeveConfirmarAlteracao = false;

  for (i = 1; i < document.form1.individual.length; i++) {

    if (document.form1.individual[i].checked == true) {

      alguem = true;
      break;
    }
  }

  sep                = "";
  var iTotalMarcados = 0;

  for (i = 1; i <= linhas; i++) {

    if (document.form1.individual[i].checked == true) {

      marcado = "true";
      iTotalMarcados++;
    } else {
      marcado = "false";
    }

    if( !empty( document.form1.ed65_i_qtdch[i].value ) ) {

      var aValorCargaHoraria = document.form1.ed65_i_qtdch[i].value.split( '.' );

      if(    aValorCargaHoraria[0] == ''
          || ( aValorCargaHoraria[0] != '' && aValorCargaHoraria[0].length > 4 )
        ) {

        alert( _M( MENSAGENS_FORMULARIO_HISTMPSDISC + 'valor_invalido_carga_horaria' ) );
        document.form1.ed65_i_qtdch[i].focus();
        return false;
      }

      if( aValorCargaHoraria.length > 1 ) {

        if(     aValorCargaHoraria[1] == ''
             || ( aValorCargaHoraria[1] != '' && ( aValorCargaHoraria[1].length == 0 || aValorCargaHoraria[1].length > 2 ) )
          ) {

          alert( _M( MENSAGENS_FORMULARIO_HISTMPSDISC + 'valor_invalido_carga_horaria' ) );
          document.form1.ed65_i_qtdch[i].focus();
          return false;
        }
      }
    }

    registrodisc += sep+marcado+";"+document.form1.ed65_i_codigo[i].value+
                                ";"+document.form1.ed65_i_disciplina[i].value+
                                ";"+document.form1.ed65_i_justificativa[i].value+
                                ";"+document.form1.ed65_i_qtdch[i].value+
                                ";"+document.form1.ed65_c_resultadofinal[i].value+
                                ";"+document.form1.ed65_t_resultobtido[i].value+
                                ";"+document.form1.ed65_c_situacao[i].value+
                                ";"+document.form1.ed65_c_tiporesultado[i].value+
                                ";"+document.form1.ed65_c_termofinal[i].value;
    sep           = "|";

    if (marcado == 'true') {

      if (document.form1.ed65_c_situacao[i].value == '') {

        document.form1.ed65_c_situacao[i].style.backgroundColor = '#99A9AE';

        alert( _M( MENSAGENS_FORMULARIO_HISTMPSDISC + 'informe_situacao_disciplina' ) );
        document.form1.ed65_c_situacao[i].style.backgroundColor = '';
        document.form1.ed65_c_situacao[i].focus();
        return false;
      }

      if (document.form1.ed65_c_situacao[i].value == 'CONCLUÍDO') {

        if (document.form1.ed65_c_resultadofinal[i].value == '') {

          document.form1.ed65_c_resultadofinal[i].style.backgroundColor = '#99A9AE';

          alert( _M( MENSAGENS_FORMULARIO_HISTMPSDISC + 'informe_resultado_disciplina' ) );
          document.form1.ed65_c_resultadofinal[i].style.backgroundColor = '';
          document.form1.ed65_c_resultadofinal[i].focus();
          return false;
        }

        if (document.form1.ed65_t_resultobtido[i].value == '') {

          document.form1.ed65_t_resultobtido[i].style.backgroundColor = '#99A9AE';

          alert( _M( MENSAGENS_FORMULARIO_HISTMPSDISC + 'informe_aproveitamento_disciplina' ) );
          document.form1.ed65_t_resultobtido[i].style.backgroundColor = '';
          document.form1.ed65_t_resultobtido[i].focus();
          return false;
        }
      } else if (document.form1.ed65_c_situacao[i].value == 'AMPARADO') {

        if (document.form1.ed65_i_justificativa[i].value == '') {

          document.form1.ed65_i_justificativa[i].style.backgroundColor = '#99A9AE';

          alert( _M( MENSAGENS_FORMULARIO_HISTMPSDISC + 'informe_justificativa_amparo' ) );
          document.form1.ed65_i_justificativa[i].style.backgroundColor = '';
          document.form1.ed65_i_justificativa[i].focus();
          return false;
        }
      }

      /**
       * Valida o minimo informado para disciplina com a sistuação do resultado informado
       */
      if (document.form1.ed65_c_resultadofinal[i].value == 'A'
          && !isNaN(parseFloat(document.form1.ed65_t_resultobtido[i].value))
          && !isNaN(parseFloat(iMinimoAprov))
          && parseFloat(document.form1.ed65_t_resultobtido[i].value) < iMinimoAprov
          && document.form1.ed65_c_situacao[i].value == 'CONCLUÍDO') {

        lDeveConfirmarAlteracao = true;

      }

      if (document.form1.ed65_c_resultadofinal[i].value == 'A'
          && !isNaN(parseFloat(document.form1.ed65_t_resultobtido[i].value))
          && !isNaN(parseFloat(iMinimoAprov))
          && parseFloat(document.form1.ed65_t_resultobtido[i].value) < iMinimoAprov
          && document.form1.ed65_c_situacao[i].value == 'NÃO OPTANTE') {

        document.form1.ed65_t_resultobtido[i].focus();
        document.form1.ed65_t_resultobtido[i].style.backgroundColor = '#99A9AE';

        var oMensagem              = {};
            oMensagem.iMinimoAprov = iMinimoAprov;
        alert( _M( MENSAGENS_FORMULARIO_HISTMPSDISC + 'situacao_aprovado', oMensagem ) );
        document.form1.ed65_t_resultobtido[i].style.backgroundColor = '';
        document.form1.ed65_t_resultobtido[i].select();
        return false;
      }

      if (document.form1.ed65_c_resultadofinal[i].value == 'R'
          && !isNaN(parseFloat(document.form1.ed65_t_resultobtido[i].value))
          && !isNaN(parseFloat(iMinimoAprov))
          && parseFloat(document.form1.ed65_t_resultobtido[i].value) >= iMinimoAprov
          && document.form1.ed65_c_situacao[i].value == 'NÃO OPTANTE') {

        document.form1.ed65_t_resultobtido[i].focus();
        document.form1.ed65_t_resultobtido[i].style.backgroundColor = '#99A9AE';

        var oMensagem              = {};
            oMensagem.iMinimoAprov = iMinimoAprov;
        alert( _M( MENSAGENS_FORMULARIO_HISTMPSDISC + 'situacao_reprovado', oMensagem ) );
        document.form1.ed65_t_resultobtido[i].style.backgroundColor = '';
        document.form1.ed65_t_resultobtido[i].select();
        return false;
      }

      if (document.form1.ed65_c_resultadofinal[i].value == 'R') {
        lReprovado = true;
      }
    }
  }

  if ( !lConfirmaAlteracaoNota && lDeveConfirmarAlteracao ) {

    var oMensagem              = {};
        oMensagem.iMinimoAprov = iMinimoAprov;

    var sMsg = _M( MENSAGENS_FORMULARIO_HISTMPSDISC + 'situacao_aprovado', oMensagem );

    if ( !confirm(sMsg+="\nDeseja continuar?") ) {

      document.form1.ed65_t_resultobtido[i].focus();
      document.form1.ed65_t_resultobtido[i].style.backgroundColor = '#99A9AE';
      return false;
    }
  }

  if (lReprovado && sResultadoFinal == 'A' && iTotalMarcados > 0) {

    alert( _M( MENSAGENS_FORMULARIO_HISTMPSDISC + 'resultado_final_aprovado' ) );
    return false;
  }

  if (!lReprovado && sResultadoFinal == 'R' && iTotalMarcados > 0) {

    alert( _M( MENSAGENS_FORMULARIO_HISTMPSDISC + 'resultado_final_reprovado' ) );
    return false;
  }

  document.form1.botao.disabled = true;
  location.href = "edu1_histmpsdisc002.php?ed65_i_historicomps=<?=$ed65_i_historicomps?>&registrodisc="+registrodisc+'&lMensagem='+lMensagem;
}

function js_alunoAprovado(oSel, sResultado) {

  if (sResultado=='R' && oSel.value=='P'){

    alert( _M( MENSAGENS_FORMULARIO_HISTMPSDISC + 'resultado_final_reprovado_etapa' ) );
    oSel.selectedIndex = 1;
  } else if(sResultado=='A' && (oSel.value=='R' || oSel.value == 'P')){

    alert( _M( MENSAGENS_FORMULARIO_HISTMPSDISC + 'resultado_final_aprovado_etapa' ) );
    oSel.selectedIndex = 1;
  } else if(sResultado == 'P' && (oSel.value=='A' || oSel.value =='R')){

    alert( _M( MENSAGENS_FORMULARIO_HISTMPSDISC + 'resultado_final_parcialmente_aprovado_etapa' ) );
    oSel.selectedIndex = 3;
  }
}

function Mostra(campo) {
  document.getElementById(campo).style.visibility = "visible";
}

function Oculta(campo) {
  document.getElementById(campo).style.visibility = "hidden";
}

/**
 * Verifica a situacao de cada disciplina, verifica se trata-se de 'NÃO OPTANTE', para bloqueio dos campos:
 * ed65_c_resultadofinal e ed65_i_qtdch
 */
function js_verificaSituacaoInicial(iTotalLinhas) {

  for (iTotal = 1; iTotal <= iTotalLinhas; iTotal++) {

    if (document.form1.ed65_c_situacao[iTotal].value == "NÃO OPTANTE") {

      document.form1.ed65_c_resultadofinal[iTotal].disabled = true;
      document.form1.ed65_i_qtdch[iTotal].disabled          = true;
    }
  }
}

js_verificaSituacaoInicial(iTotalLinhas);

<?php if (isset($lFechou) && $lFechou && isset($iQtdDisciplinas) && $iQtdDisciplinas > 0) {?>
          Salvar(<?=$iLinhasHistMpsDisc?>, 'false');
<?php }?>

<?php if (!isset($lFechou)) { ?>
  if (   <?php echo $iLinhasHistMpsDisc; ?> == 0
      && ( '<?php echo $ed62_c_situacao; ?>' == 'CONCLUÍDO' || '<?php echo $ed62_c_situacao; ?>' == 'RECLASSIFICADO' ) ) {

    document.getElementById('btnLancarDisciplina').click();
    parent.lPrimeiroAcessoView = false;
  }
<?php } ?>

  /*
   * Valida se Escola selecionada tem permissão de manutenção do histórico do aluno.
   */
  var iEnsinoSelecionado     = $F('ed11_i_ensino');
  var iOrdemEtapaSelecionada = $F('ed11_i_sequencia');

  var oHistorico = new HistoricoEscolar( CurrentWindow.corpo.oDadosManutencaoHistorico.iStatusAlteracaoHistorico,
                                         CurrentWindow.corpo.oDadosManutencaoHistorico.aSenquenciaEtapas[iEnsinoSelecionado],
                                         iOrdemEtapaSelecionada );

  if ( !oHistorico.permiteManutencao() ) {

    $('btnLancarDisciplina').setAttribute('disabled', 'disabled');

    if ( $('botao') ) {
      $('botao').setAttribute('disabled', 'disabled');
    }

  }
</script>