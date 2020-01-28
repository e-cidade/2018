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
  parent.disciplina.location.href = "edu1_historicodisciplinafora.php?ed100_i_historicompsfora=<?=@$ed99_i_codigo?>";
</script>
<?php
  $sDesabilitar = "";

  if ((isset($situacao) && $situacao == "CONCLUÍDO")) {

    $db_botao     = false;
    $sDesabilitar = "disabled = 'disabled'";
  }

  if ( $ed99_c_situacao != "CONCLUÍDO" && $ed99_c_situacao != "RECLASSIFICADO" ) {

    $db_botao     = false;
    $sDesabilitar = "disabled = 'disabled'";
    db_msgbox("{$ed11_c_descr} ano {$ed99_i_anoref} com situação {$ed99_c_situacao}. Não é possível incluir disciplinas!");
  }

  $oDaoHistMpsDiscFora->rotulo->label();
  $clrotulo = new rotulocampo;
  $clrotulo->label("ed99_i_codigo");
  $clrotulo->label("ed12_i_codigo");
  $clrotulo->label("ed99_i_anoref");
  $clrotulo->label("ed99_i_periodoref");
?>
<form name="form1">
  <center>
  <table border="0" width="100%">
    <tr>
      <td nowrap>
        <?db_ancora("<b>Etapa:</b>","",3);?>
      </td>
      <td>
        <?php
        db_input( 'ed100_i_historicompsfora', 15, $Ied100_i_historicompsfora, true, 'text',   3 );
        db_input( 'ed11_c_descr',             40, @$Ied11_c_descr,            true, 'text',   3 );
        db_input( 'ed99_i_codigo',            40, @$Ied99_i_codigo,           true, 'hidden', 3 );
        db_input( 'ed99_c_minimo',            40, @$Ied99_c_minimo,           true, 'hidden', 3 );
        db_input( 'ed99_c_resultadofinal',    40, @$Ied99_c_minimo,           true, 'hidden', 3 );
        db_input( 'ed61_i_curso',             40, @$Ied99_c_minimo,           true, 'hidden', 3 );
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Ted100_i_historicompsfora?>">
        <?php
        db_ancora( @$Led100_i_historicompsfora, "", 3 );
        ?>
      </td>
      <td>
        <?php
        db_input( 'ed61_i_codigo', 15, @$Ied61_i_codigo, true, 'text', 3 );
        db_input( 'ed29_c_descr',  40, @$Ied29_c_descr,  true, 'text', 3 );
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Ted99_i_anoref?>">
       <?=@$Led99_i_anoref?>
      </td>
      <td>
        <?php
        db_input( 'ed99_i_anoref', 4, $Ied99_i_anoref, true, 'text', 3 );
        echo @$Led99_i_periodoref;
        db_input( 'ed99_i_periodoref', 4, $Ied99_i_periodoref, true, 'text', 3 );
        ?>
        <input type="button"
               id="btnLancarDisciplina"
               name="btnLancarDisciplina"
               value="Lançar Disciplina"
               onclick="parent.js_lancarDisciplina($F('ed100_i_historicompsfora'), 2, iEnsino, iHistoricomps, $F('ed61_i_curso'), $F('ed99_i_anoref'));"
         <?php echo $sDesabilitar;?> />
      </td>
    </tr>
    <tr>
      <td colspan="2">
       <!--
        não remover a linhas abaixo.
        os campos estão como hidden, para as funções javascrip da rotina,
        aceitar os campos como array.
      -->
      <input type="checkbox" name="individual" style='display:none' value="true">
      <input type="hidden" name="ed100_i_codigo" value="<?php if( isset( $ed100_i_codigo ) ) echo $ed100_i_codigo; else echo ""; ?>">
      <input type="hidden" name="ed100_i_disciplina" value="<?php if( isset( $ed12_i_codigo ) ) echo $ed12_i_codigo; else echo ""; ?>">
      <input type="hidden" name="ed100_c_tiporesultado" value="<?php if( isset( $ed100_c_tiporesultado ) ) echo $ed100_c_tiporesultado; else echo ""; ?>">
      <input type="hidden" name="ed100_c_situacao"  />
      <input type="hidden" name="ed100_i_qtdch"  />
      <input type="hidden" name="ed100_c_resultadofinal"  />
      <input type="hidden" name="ed100_i_justificativa"  />
      <input type="hidden" name="ed100_t_resultobtido"  />
      <input type="hidden" name="ed100_c_termofinal"  />
      <input type="hidden" name="ed06_c_descr" />
      <input type="hidden" name="sTipoRede" id="sTipoRede" value="2" />

      <table border="1" cellspacing="0" cellpading="0" width="100%">
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
        $ed100_i_codigo        = "";
        $ed100_c_tiporesultado = "";

        $sWhereHistMpsDiscFora   = "ed100_i_historicompsfora  = {$ed100_i_historicompsfora}";
        $sSqlHistMpsDiscFora     = $oDaoHistMpsDiscFora->sql_query("", "*", " ed100_basecomum desc, ed100_i_ordenacao", $sWhereHistMpsDiscFora);
        $rsHistMpsDiscFora       = $oDaoHistMpsDiscFora->sql_record($sSqlHistMpsDiscFora);
        $iLinhasHistMpsDiscFora  = $oDaoHistMpsDiscFora->numrows;

        for ($iTotalLinhas = 0; $iTotalLinhas < $iLinhasHistMpsDiscFora; $iTotalLinhas++) {

          db_fieldsmemory($rsHistMpsDiscFora, $iTotalLinhas);
          $ed100_basecomum == 't'? $sBaseComum = "BASE COMUM" : $sBaseComum = "DIVERSIFICADA";
        ?>
        <tr onmouseover="Mostra('disc<?=$iTotalLinhas?>')" onmouseout="Oculta('disc<?=$iTotalLinhas?>')">
          <td> <?php echo $sBaseComum; ?> </td>
          <td style="font-size:10px;">
            <input type="checkbox"
                   name="individual"
                   value="true"
                   <?=$ed100_i_codigo!=""?"checked":""?>
                   onclick="MarcaIndividual(this.value, <?=$iTotalLinhas?>)">
            <input type="hidden" name="ed100_i_codigo" value="<?=$ed100_i_codigo?>">
            <input type="hidden" name="ed100_i_disciplina" value="<?=$ed12_i_codigo?>">
            <input type="hidden" name="ed100_c_tiporesultado" value="<?=$ed100_c_tiporesultado?>">
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

            $sScript = " onchange='js_situacao(this,$iTotalLinhas);' style='width:100px;height:15px;font-size:10px;padding:0px;' ".
                      ( $ed100_i_codigo == "" ? "disabled" : "" )."";
            db_select( 'ed100_c_situacao', $x, true, $db_opcao, $sScript );
            ?>
          </td>
          <td>
            <?php
            $sScript = "".($ed100_i_codigo==""?"disabled":"")."";
            db_input( 'ed100_i_qtdch', 5, $Ied100_i_qtdch, true, 'text', $db_opcao, $sScript );
            ?>
          </td>
          <td valign="top">
            <?php
            if( @$ed100_c_situacao == "CONCLUÍDO" || @$ed100_c_situacao == "NÃO OPTANTE" ) {
              $visivel = "visible";
            } else if( @$ed100_c_situacao == "AMPARADO" ) {
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
                  $sOnchange = '';
                  if ($ed99_c_resultadofinal == 'A') {
                    $sOnchange = 'onchange="js_alunoAprovado(this);"';
                  }

                  $r = array(
                              ''  => '',
                              'A' => 'APROVADO',
                              'R' => 'REPROVADO'
                            );
                  $sScript = " style='width:100px;height:15px;font-size:10px;padding:0px;' ".
                           ($ed100_i_codigo==""?"disabled":"")." $sOnchange ";
                  db_select( 'ed100_c_resultadofinal', $r, true, $db_opcao, $sScript );
                  ?>
                </td>
                <td>
                  <?php
                  $aprov_resultado = @$ed100_t_resultobtido;
                  ?>
                  <input type="text"
                         name="ed100_t_resultobtido"
                         value="<?=@$aprov_resultado?>"
                         size="6" <?=@$ed100_i_codigo == "" ? "disabled" : ""?>>
                </td>
                <td>
                  <?php
                  db_input( 'ed100_c_termofinal', 10, $Ied100_c_termofinal, true, 'text', $db_opcao );
                  ?>
                </td>
              </tr>
            </table>
            <?php
            if (@$ed100_c_situacao == "CONCLUÍDO" || @$ed100_c_situacao == "NÃO OPTANTE") {
              $visivel = "hidden";
            } else if (@$ed100_c_situacao == "AMPARADO") {
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
                <td nowrap title="<?=@$Ted100_i_justificativa?>">
                  <?php
                  db_ancora( "<label class='bold'>Just:</label>", "js_pesquisaed100_i_justificativa(true, $iTotalLinhas);", $db_opcao );
                  ?>
                </td>
                <td nowrap="nowrap">
                  <?php
                  $sScript = " onchange='js_pesquisaed100_i_justificativa(false, $iTotalLinhas);'";
                  db_input( 'ed100_i_justificativa',  5, $Ied100_i_justificativa, true, 'text', $db_opcao, $sScript );
                  db_input( 'ed06_c_descr',          12, @$Ied06_c_descr,         true, 'text', 3 );
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
                   style="border: 2px outset #CCCCCC; position: absolute; visibility: hidden;"
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
        $ed100_i_codigo         = "";
        $ed100_c_situacao       = "";
        $ed100_c_resultadofinal = "";
        $ed100_t_resultobtido   = "";
        $ed100_i_qtdch          = "";
        $ed100_c_tiporesultado  = "";
        $ed100_i_disciplina     = "";
        $ed100_i_justificativa  = "";
        $ed100_c_termofinal     = "";
        $ed06_c_descr           = "";
        }
        ?>
        </table>
      </td>
    </tr>
   <input type="hidden" name="justlinha" value="">
  </table>
  </center>
  <?php
  if( $iLinhasHistMpsDiscFora > 0 ) {

    if( $db_opcao == 1 ) {
  ?>
      <input name="incluir"
             id="botao"
             type="button"
             value="Incluir"
             onclick="Salvar(<?=$iLinhasHistMpsDiscFora?>, true);"
             <?=( $db_botao == false ? "disabled" : "" )?> >
    <?php
    } else {
    ?>
      <input name="alterar"
             id="botao"
             type="button"
             value="Alterar"
             onclick="Salvar(<?=$iLinhasHistMpsDiscFora?>, true);"
             <?=( $db_botao == false ? "disabled" : "" )?> >
    <?php
    }
  }
  ?>
</form>
<script>
const MENSAGENS_FORMULARIO_HISTMPSDISCFORA = 'educacao.escola.db_frmhistmpsdiscfora.';

var aCargasHorarias = document.getElementsByName('ed100_i_qtdch');
for( var iContador = 0; iContador < aCargasHorarias.length; iContador++ ) {
  aCargasHorarias[iContador].setAttribute('maxlength', '7');
}

var iEnsino         = "<?=$ed11_i_ensino;?>";
var iSequenciaEtapa = "<?=$ed11_i_sequencia;?>";
var iHistoricomps   = "<?=$ed100_i_historicompsfora;?>";
var iTotalLinhas    = <?=$iTotalLinhas;?>;

function MarcaIndividual( valor, i ) {

  var i          = i+1;
  var iResultado = i -1;

  if( document.form1.individual[i].checked == true ) {

    document.form1.ed100_c_situacao[i].disabled             = false;
    document.form1.ed100_c_resultadofinal[i].disabled       = false;
    document.form1.ed100_t_resultobtido[i].disabled         = false;
    document.form1.ed100_i_qtdch[i].disabled                = false;
    document.getElementById("resultado"+iResultado).style.visibility = "visible";
  } else {

    document.form1.ed100_c_situacao[i].disabled             = true;
    document.form1.ed100_c_resultadofinal[i].disabled       = true;
    document.form1.ed100_t_resultobtido[i].disabled         = true;
    document.form1.ed100_i_qtdch[i].disabled                = true;
    document.getElementById("resultado"+iResultado).style.visibility = "hidden";
  }
}

function js_situacao( campo, linha ) {

  if (campo.value == "CONCLUÍDO") {

    document.getElementById("justificativa"+linha).style.visibility = "hidden";
    document.getElementById("resultado"+linha).style.visibility     = "visible";
  } else if(campo.value == "AMPARADO") {

    document.getElementById("justificativa"+linha).style.visibility = "visible";
    document.getElementById("resultado"+linha).style.visibility     = "hidden";
  } else if (campo.value == "NÃO OPTANTE") {

    document.getElementById("justificativa"+linha).style.visibility = "hidden";
    document.getElementById("resultado"+linha).style.visibility     = "visible";
    document.form1.ed100_c_resultadofinal[linha+1].disabled         = true;
    document.form1.ed100_i_qtdch[linha+1].disabled                  = true;
  } else {

    document.getElementById("justificativa"+linha).style.visibility = "hidden";
    document.getElementById("resultado"+linha).style.visibility     = "hidden";
  }
}

function js_pesquisaed100_i_justificativa(mostra, linha) {

  linha++;
  document.form1.justlinha.value = linha;
  if (mostra == true) {

    js_OpenJanelaIframe('parent','db_iframe_justificativa',
    	                'func_justificativa.php?funcao_js=parent.dados.js_mostrajustificativa1|'+
    	                'ed06_i_codigo|ed06_c_descr',
    	                'Pesquisa de Justificativas',true,0,0
    	               );
  } else {

    if (document.form1.ed100_i_justificativa[linha].value != '') {

      js_OpenJanelaIframe('parent','db_iframe_justificativa',
    	                  'func_justificativa.php?pesquisa_chave='+document.form1.ed100_i_justificativa[linha].value+
    	                  '&funcao_js=parent.dados.js_mostrajustificativa','Pesquisa',false
    	                 );
    } else {
      document.form1.ed06_c_descr.value = '';
    }
  }
}

function js_mostrajustificativa( chave, erro ) {

  document.form1.ed06_c_descr[document.form1.justlinha.value].value = chave;
  if (erro == true) {

    document.form1.ed100_i_justificativa[document.form1.justlinha.value].focus();
    document.form1.ed100_i_justificativa[document.form1.justlinha.value].value = '';
  }

  document.form1.justlinha.value = "";
}

function js_mostrajustificativa1(chave1,chave2) {

  document.form1.ed100_i_justificativa[document.form1.justlinha.value].value = chave1;
  document.form1.ed06_c_descr[document.form1.justlinha.value].value          = chave2;
  document.form1.justlinha.value                                             = "";
  parent.db_iframe_justificativa.hide();
}

function js_novadisciplina() {
  js_OpenJanelaIframe('parent','db_iframe_novadisciplina','edu1_disciplinanova001.php','Nova Disciplina',true);
}

var registrodisc = "";
function Salvar(linhas, lMensagem) {

  iMinimoAprov    = '<?=str_replace("'", "\'", $ed99_c_minimo)?>';
  sResultadoFinal = '<?=$ed99_c_resultadofinal?>';
  var lReprovado  = false;
  var alguem      = false;

  for( i = 1; i < document.form1.individual.length; i++ ) {

    if (document.form1.individual[i].checked == true) {

      alguem = true;
      break;
    }
  }

  sep                = "";
  //registrodisc       = "";
  var iTotalMarcados = 0;
  var lExibeMensagemMinimoAproveitamento = true;

  for(i =  1; i <= linhas; i++) {

    if (document.form1.individual[i].checked == true) {

      marcado       = "true";
      iTotalMarcados++;
    } else {
      marcado = "false";
    }

    if( !empty( document.form1.ed100_i_qtdch[i].value ) ) {

      var aValorCargaHoraria = document.form1.ed100_i_qtdch[i].value.split( '.' );

      if(    aValorCargaHoraria[0] == ''
          || ( aValorCargaHoraria[0] != '' && aValorCargaHoraria[0].length > 4 )
        ) {

        alert( _M( MENSAGENS_FORMULARIO_HISTMPSDISCFORA + 'valor_invalido_carga_horaria' ) );
        document.form1.ed100_i_qtdch[i].focus();
        return false;
      }

      if( aValorCargaHoraria.length > 1 ) {

        if(     aValorCargaHoraria[1] == ''
             || ( aValorCargaHoraria[1] != '' && ( aValorCargaHoraria[1].length == 0 || aValorCargaHoraria[1].length > 2 ) )
          ) {

          alert( _M( MENSAGENS_FORMULARIO_HISTMPSDISCFORA + 'valor_invalido_carga_horaria' ) );
          document.form1.ed100_i_qtdch[i].focus();
          return false;
        }
      }
    }

    registrodisc += sep+marcado+";"+document.form1.ed100_i_codigo[i].value+
                                ";"+document.form1.ed100_i_disciplina[i].value+
                                ";"+document.form1.ed100_i_justificativa[i].value+
                                ";"+document.form1.ed100_i_qtdch[i].value+
                                ";"+document.form1.ed100_c_resultadofinal[i].value+
                                ";"+document.form1.ed100_t_resultobtido[i].value+
                                ";"+document.form1.ed100_c_situacao[i].value+
                                ";"+document.form1.ed100_c_tiporesultado[i].value+
                                ";"+document.form1.ed100_c_termofinal[i].value;
    sep = "|";

    if (marcado == 'true') {

      if (document.form1.ed100_c_situacao[i].value == '') {

        document.form1.ed100_c_situacao[i].style.backgroundColor = '#99A9AE';

        alert( _M( MENSAGENS_FORMULARIO_HISTMPSDISCFORA + 'informe_situacao_disciplina' ) );
        document.form1.ed100_c_situacao[i].style.backgroundColor = '';
        document.form1.ed100_c_situacao[i].focus();
        return false;
      }

      if (document.form1.ed100_c_situacao[i].value == 'CONCLUÍDO') {

        if (document.form1.ed100_c_resultadofinal[i].value == '') {

          document.form1.ed100_c_resultadofinal[i].style.backgroundColor = '#99A9AE';

          alert( _M( MENSAGENS_FORMULARIO_HISTMPSDISCFORA + 'informe_resultado_disciplina' ) );
          document.form1.ed100_c_resultadofinal[i].style.backgroundColor = '';
          document.form1.ed100_c_resultadofinal[i].focus();
          return false;
        }

        if (document.form1.ed100_t_resultobtido[i].value == '') {

          document.form1.ed100_t_resultobtido[i].style.backgroundColor = '#99A9AE';

          alert( _M( MENSAGENS_FORMULARIO_HISTMPSDISCFORA + 'informe_aproveitamento_disciplina' ) );
          document.form1.ed100_t_resultobtido[i].style.backgroundColor = '';
          document.form1.ed100_t_resultobtido[i].focus();
          return false;
        }
      } else if (document.form1.ed100_c_situacao[i].value == 'AMPARADO') {

        if (document.form1.ed100_i_justificativa[i].value == '') {

          document.form1.ed100_i_justificativa[i].style.backgroundColor = '#99A9AE';

          alert( _M( MENSAGENS_FORMULARIO_HISTMPSDISCFORA + 'informe_justificativa_amparo' ) );
          document.form1.ed100_i_justificativa[i].style.backgroundColor = '';
          document.form1.ed100_i_justificativa[i].focus();
          return false;
        }
      }

      /**
       * Valida o minimo informado para disciplina com a sistuação do resultado informado
       */
      if (document.form1.ed100_c_resultadofinal[i].value == 'A'
          && !isNaN(parseFloat(document.form1.ed100_t_resultobtido[i].value))
          && !isNaN(parseFloat(iMinimoAprov))
          && parseFloat(document.form1.ed100_t_resultobtido[i].value) < iMinimoAprov
          && document.form1.ed100_c_situacao[i].value == 'CONCLUÍDO'
          && lExibeMensagemMinimoAproveitamento == true) {

        var oMensagem                       = {};
            oMensagem.iMinimoAproveitamento = iMinimoAprov;

        var sMensagem = _M( MENSAGENS_FORMULARIO_HISTMPSDISCFORA + 'situacao_aprovado', oMensagem );

        if ( !confirm(sMensagem+="\nDeseja continuar?") ) {

          lExibeMensagemMinimoAproveitamento = true;
          document.form1.ed100_t_resultobtido[i].focus();
          document.form1.ed100_t_resultobtido[i].style.backgroundColor = '#99A9AE';
          return false;
        } else {
          lExibeMensagemMinimoAproveitamento = false;
        }
      }



      if (   document.form1.ed100_c_resultadofinal[i].value == 'A'
          && !isNaN(parseFloat(document.form1.ed100_t_resultobtido[i].value))
          && !isNaN(parseFloat(iMinimoAprov))
          && parseFloat(document.form1.ed100_t_resultobtido[i].value) < iMinimoAprov
          && document.form1.ed100_c_situacao[i].value == 'NÃO OPTANTE') {

        document.form1.ed100_t_resultobtido[i].focus();
        document.form1.ed100_t_resultobtido[i].style.backgroundColor = '#99A9AE';

        var oMensagem                       = {};
            oMensagem.iMinimoAproveitamento = iMinimoAprov;
        alert( _M( MENSAGENS_FORMULARIO_HISTMPSDISCFORA + 'situacao_aprovado', oMensagem ) );
        document.form1.ed100_t_resultobtido[i].style.backgroundColor = '';
        document.form1.ed100_t_resultobtido[i].select();
        return false;

      }

      if (   document.form1.ed100_c_resultadofinal[i].value == 'R'
          && !isNaN(parseFloat(document.form1.ed100_t_resultobtido[i].value))
          && !isNaN(parseFloat(iMinimoAprov))
          && parseFloat(document.form1.ed100_t_resultobtido[i].value) >= iMinimoAprov
          && document.form1.ed100_c_situacao[i].value == 'NÃO OPTANTE') {

        document.form1.ed100_t_resultobtido[i].focus();
        document.form1.ed100_t_resultobtido[i].style.backgroundColor = '#99A9AE';

        var oMensagem                       = {};
            oMensagem.iMinimoAproveitamento = iMinimoAprov;
        alert( _M( MENSAGENS_FORMULARIO_HISTMPSDISCFORA + 'situacao_reprovado', oMensagem ) );
        document.form1.ed100_t_resultobtido[i].style.backgroundColor = '';
        document.form1.ed100_t_resultobtido[i].select();
        return false;

      }

      if (document.form1.ed100_c_resultadofinal[i].value == 'R') {
        lReprovado = true;
      }
    }
  }

  if (lReprovado && sResultadoFinal == 'A' && iTotalMarcados > 0) {

    alert( _M( MENSAGENS_FORMULARIO_HISTMPSDISCFORA + 'resultado_final_aprovado' ) );
    return false;
  }

  if (!lReprovado && sResultadoFinal == 'R' && iTotalMarcados > 0) {

    alert( _M( MENSAGENS_FORMULARIO_HISTMPSDISCFORA + 'resultado_final_reprovado' ) );
    return false;
  }

  location.href = "edu1_histmpsdiscfora002.php?ed100_i_historicompsfora=<?=$ed100_i_historicompsfora?>"+
                  "&registrodisc="+registrodisc+'&lMensagem='+lMensagem;;
}

function js_alunoAprovado(oSel) {

  if (oSel.value != 'A') {

    alert( _M( MENSAGENS_FORMULARIO_HISTMPSDISCFORA + 'resultado_final_aprovado_etapa' ) );
    oSel.selectedIndex = 1;
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
 * ed100_c_resultadofinal e ed100_i_qtdch
 */
function js_verificaSituacaoInicial(iTotalLinhas) {

  for (iTotal = 1; iTotal <= iTotalLinhas; iTotal++) {

    if (document.form1.ed100_c_situacao[iTotal].value == "NÃO OPTANTE") {

      document.form1.ed100_c_resultadofinal[iTotal].disabled = true;
      document.form1.ed100_i_qtdch[iTotal].disabled          = true;
    }
  }
}

js_verificaSituacaoInicial(iTotalLinhas);

<?php
if (isset($lFechou) && $lFechou && isset($iQtdDisciplinas) && $iQtdDisciplinas > 0) {
?>
  Salvar(<?=$iLinhasHistMpsDiscFora?>, 'false');
<?php
}
?>
if (<?php echo $iLinhasHistMpsDiscFora; ?> == 0 && parent.lPrimeiroAcessoView && '<?php echo $ed99_c_situacao; ?>' == 'CONCLUÍDO') {

  document.getElementById('btnLancarDisciplina').click();
  parent.lPrimeiroAcessoView = false;
}

  (function () {

    var oDadosManutencaoHistorico  = CurrentWindow.corpo.oDadosManutencaoHistorico,
        iStatusManutencaoHistorico = oDadosManutencaoHistorico.iStatusAlteracaoHistorico,
        iOrdemEtapaAtual           = oDadosManutencaoHistorico.aSenquenciaEtapas[iEnsino],
        oHistoricoEscolar          = new HistoricoEscolar(iStatusManutencaoHistorico, iOrdemEtapaAtual, iSequenciaEtapa);

        if( !oHistoricoEscolar.permiteManutencao() ) {

          $('btnLancarDisciplina').disabled = true;
          $('botao').disabled               = true;
        }
  })();

</script>