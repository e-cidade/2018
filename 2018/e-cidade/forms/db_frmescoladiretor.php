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

require_once ("dbforms/db_classesgenericas.php");

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clescoladiretor->rotulo->label();

$oRotuloCampo = new rotulocampo();
$oRotuloCampo->label( "ed01_c_exigeato" );
$oRotuloCampo->label( "ed254_i_rechumano" );
$oRotuloCampo->label( "z01_nome" );
$oRotuloCampo->label( "ed18_c_nome" );
$oRotuloCampo->label( "z01_cgccpf" );
$oRotuloCampo->label( "rh37_descr" );
$oRotuloCampo->label( "ed15_c_nome" );

$db_botao1 = false;

$ed254_d_dataini_dia = isset( $ed254_d_dataini ) && !empty( $ed254_d_dataini ) ? substr( $ed254_d_dataini, 0, 2 ) : "";
$ed254_d_dataini_mes = isset( $ed254_d_dataini ) && !empty( $ed254_d_dataini ) ? substr( $ed254_d_dataini, 3, 2 ) : "";
$ed254_d_dataini_ano = isset( $ed254_d_dataini ) && !empty( $ed254_d_dataini ) ? substr( $ed254_d_dataini, 6, 4 ) : "";
$ed254_d_datafim_dia = isset( $ed254_d_datafim ) && !empty( $ed254_d_datafim ) ? substr( $ed254_d_datafim, 0, 2 ) : "";
$ed254_d_datafim_mes = isset( $ed254_d_datafim ) && !empty( $ed254_d_datafim ) ? substr( $ed254_d_datafim, 3, 2 ) : "";
$ed254_d_datafim_ano = isset( $ed254_d_datafim ) && !empty( $ed254_d_datafim ) ? substr( $ed254_d_datafim, 6, 4 ) : "";
$ed254_d_datacad_dia = isset( $ed254_d_datacad ) && !empty( $ed254_d_datacad ) ? substr( $ed254_d_datacad, 0, 2 ) : "";
$ed254_d_datacad_mes = isset( $ed254_d_datacad ) && !empty( $ed254_d_datacad ) ? substr( $ed254_d_datacad, 3, 2 ) : "";
$ed254_d_datacad_ano = isset( $ed254_d_datacad ) && !empty( $ed254_d_datacad ) ? substr( $ed254_d_datacad, 6, 4 ) : "";

if( isset( $opcao ) && $opcao == "alterar" ) {

 $db_opcao  = 2;
 $db_botao1 = true;
} else if( isset( $opcao ) && $opcao == "excluir" || isset($db_opcao) && $db_opcao == 3 ) {

  $db_botao1 = true;
  $db_opcao  = 3;
} else {

  if( isset( $alterar ) ) {

   $db_opcao  = 2;
   $db_botao1 = true;
  } else {
    $db_opcao = 1;
  }
}
?>
<form name="form1" method="post" action="">
  <div class="container">
    <fieldset>
      <legend>Diretores da Escola</legend>
      <table border="0" class="form-container">
        <tr>
          <td nowrap title="<?=$Ted254_i_codigo?>">
           <label for="ed254_i_codigo"><?=$Led254_i_codigo?></label>
          </td>
          <td>
            <?php
            db_input( 'ed254_i_codigo', 20, $Ied254_i_codigo, true, 'text', 3 );
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Ted254_i_escola?>">
            <label for="ed254_i_escola">
              <?php
              db_ancora( $Led254_i_escola, "", 3 );
              ?>
            </label>
          </td>
          <td>
            <?php
            db_input( 'ed254_i_escola', 20, $Ied254_i_escola, true, 'text', 3 );
            db_input( 'ed18_c_nome',    50, $Ied18_c_nome,    true, 'text', 3 );
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Ted254_i_rechumano?>">
            <label for="identificacao">
              <?php
              db_ancora( $Led254_i_rechumano, "js_pesquisaed254_i_rechumano(true);", $db_opcao );
              ?>
            </label>
          </td>
          <td>
            <?php
            db_input( 'ed254_i_rechumano', 20, $Ied254_i_rechumano, true, 'hidden', 3 );
            db_input( 'identificacao',     20, 'identificacao',     true, 'text',   3 );
            db_input( 'z01_nome',          50, $Iz01_nome,          true, 'text',   3 );
            db_input( 'ed01_c_exigeato',   20, $Ied01_c_exigeato,   true, 'hidden', 3 );
            ?>
          </td>
        </tr>
        <tr>
          <td>
           <label for="z01_cgccpf">CPF:</label>
          </td>
          <td>
            <?php
            db_input( 'z01_cgccpf', 12, $Iz01_cgccpf, true, 'text', 3 );
            ?>
            <label for="rh37_descr">Cargo:</label>
            <?php
            db_input( 'rh37_descr', 40, $Irh37_descr, true, 'text', 3 );
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Ted254_i_turno?>">
            <label for="ed254_i_turno">
              <?php
              db_ancora( $Led254_i_turno, "js_pesquisaed254_i_turno(true);", $db_opcao );
              ?>
            </label>
          </td>
          <td>
            <?php
            $sOnChange = "onchange='js_pesquisaed254_i_turno(false);'";
            db_input( 'ed254_i_turno', 20, $Ied254_i_turno, true, 'text', $db_opcao, $sOnChange );
            db_input( 'ed15_c_nome',   20, $Ied15_c_nome,   true, 'text', 3 );
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Ted254_i_atolegal?>">
            <label for="ed254_i_atolegal">
              <?php
              db_ancora( $Led254_i_atolegal, "js_pesquisaed254_i_atolegal(true);", $db_opcao );
              ?>
            </label>
          </td>
          <td>
            <?php
              $sOnChange = "onchange='js_pesquisaed254_i_atolegal(false);'";
              db_input( 'ed254_i_atolegal',  20, $Ied254_i_atolegal,   true, 'text', $db_opcao, $sOnChange );
              db_input( 'ed05_c_finalidade', 50, @$Ied05_c_finalidade, true, 'text', 3 );
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Ted254_c_email?>">
            <label for="ed254_c_email"><?=$Led254_c_email?></label>
          </td>
          <td>
            <?php
              $sOnKeyUp = "onKeyUp=\"js_ValidaCamposEdu(this,4,'{$GLOBALS['Sed254_c_email']}','f','t',event);\"";
              db_input( 'ed254_c_email', 50, $Ied254_c_email, true, 'text', $db_opcao, $sOnKeyUp );
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Ted254_d_dataini?>">
            <label for="ed254_d_dataini"><?=$Led254_d_dataini?></label>
          </td>
          <td>
            <?php
            db_inputdata(
                          'ed254_d_dataini',
                          $ed254_d_dataini_dia,
                          $ed254_d_dataini_mes,
                          $ed254_d_dataini_ano,
                          true,
                          'text',
                          $db_opcao
                        );
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Ted254_d_datafim?>">
            <label for="ed254_d_datafim"><?=$Led254_d_datafim?></label>
          </td>
          <td>
            <?php
            db_inputdata(
                          'ed254_d_datafim',
                          $ed254_d_datafim_dia,
                          $ed254_d_datafim_mes,
                          $ed254_d_datafim_ano,
                          true,
                          'text',
                          $db_opcao
                        );
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Ted254_c_tipo?>">
            <label for="ed254_c_tipo"><?=$Led254_c_tipo?></label>
          </td>
          <td>
           <?php
           $x = array( 'A'=> 'ABERTO', 'F' => 'FECHADO' );
           db_select( 'ed254_c_tipo', $x, true, $db_opcao );
           ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Ted254_d_datacad?>">
            <label for="ed254_d_datacad"><?=$Led254_d_datacad?></label>
          </td>
          <td>
            <?php
            db_inputdata( 'ed254_d_datacad', @$ed254_d_datacad_dia, @$ed254_d_datacad_mes, @$ed254_d_datacad_ano, true, 'text', 3 );
            db_input( 'ed254_i_usuario', 20, $Ied254_i_usuario, true, 'hidden', 3 );
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <?php
            $sBotao = ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"));
            ?>
            <input id="<?=$sBotao;?>" name="<?=$sBotao;?>" type="hidden" value="<?=$sBotao;?>" />
          </td>
        </tr>
      </table>
    </fieldset>
    <input name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>"
           type="button"
           id="db_opcao"
           value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"))?>"
           <?=($db_botao == false ? "disabled" : "")?>
           onclick="return js_valida();">
    <input name="cancelar" type="button" value="Cancelar" <?=($db_botao1 == false ? "disabled" : "")?> onclick="js_cancelar();">

  </div>

  <table width='100%'>
    <tr>
      <td valign="top">
        <?php
          $campossql = "ed254_i_codigo,
                        ed254_i_escola,
                        ed18_c_nome,
                        ed254_i_rechumano,
                        case
                             when ed20_i_tiposervidor = 1
                             then cgmrh.z01_nome
                             else cgmcgm.z01_nome
                         end as z01_nome,
                        case
                             when ed20_i_tiposervidor = 1
                             then cgmrh.z01_cgccpf
                             else cgmcgm.z01_cgccpf
                         end as z01_cgccpf,
                        rh37_descr,
                        ed254_i_turno,
                        ed15_c_nome,
                        ed254_i_atolegal,
                        ed05_c_finalidade,
                        ed254_c_email,
                        ed254_d_dataini,
                        ed254_d_datafim,
                        ed254_c_tipo,
                        ed254_i_usuario,
                        ed254_d_datacad,

                        (select distinct ed01_c_exigeato
                           from atividaderh
                          inner join rechumanoativ   on rechumanoativ.ed22_i_atividade = atividaderh.ed01_i_codigo
                          inner join rechumanoescola on rechumanoescola.ed75_i_codigo = rechumanoativ.ed22_i_rechumanoescola
                                                    and rechumanoescola.ed75_i_escola = " .db_getsession('DB_coddepto')."
                                                    and rechumanoescola.ed75_i_rechumano = rechumano.ed20_i_codigo
                          where ed01_c_regencia = 'N'
                            and ed01_c_docencia = 'N'
                            and ed01_i_funcaoadmin = 2
                          ) as ed01_c_exigeato,

                        case
                             when ed20_i_tiposervidor = 1
                             then rechumanopessoal.ed284_i_rhpessoal
                             else rechumanocgm.ed285_i_cgm
                         end as identificacao";
          $chavepri= array(
                            "ed254_i_codigo"    => @$ed254_i_codigo,
                            "ed254_i_escola"    => @$ed254_i_escola,
                            "ed18_c_nome"       => @$ed18_c_nome,
                            "ed254_i_rechumano" => @$ed254_i_rechumano,
                            "z01_nome"          => @$z01_nome,
                            "z01_cgccpf"        => @$z01_cgccpf,
                            "rh37_descr"        => @$rh37_descr,
                            "ed254_i_turno"     => @$ed254_i_turno,
                            "ed15_c_nome"       => @$ed15_c_nome,
                            "ed254_i_atolegal"  => @$ed254_i_atolegal,
                            "ed05_c_finalidade" => @$ed05_c_finalidade,
                            "ed254_c_email"     => @$ed254_c_email,
                            "ed254_d_dataini"   => @$ed254_d_dataini,
                            "ed254_d_datafim"   => @$ed254_d_datafim,
                            "ed254_c_tipo"      => @$ed254_c_tipo,
                            "ed254_i_usuario"   => @$ed254_i_usuario,
                            "ed254_d_datacad"   => @$ed254_d_datacad,
                            "ed01_c_exigeato"   => @$ed01_c_exigeato,
                            "identificacao"     => @$identificacao
                          );

          $sOrdernacao = "ed15_i_sequencia, ed254_d_dataini desc";
          $sWhere      = "ed254_i_escola = {$ed254_i_escola}";
          $sCampos     = "ed254_i_rechumano, z01_nome, ed15_c_nome, ed254_d_dataini, ed254_d_datafim, ed254_c_tipo";

          $cliframe_alterar_excluir->chavepri      = $chavepri;
          $cliframe_alterar_excluir->sql           = $clescoladiretor->sql_query( "", $campossql, $sOrdernacao, $sWhere );
          $cliframe_alterar_excluir->campos        = $sCampos;
          $cliframe_alterar_excluir->legenda       = "Registros";
          $cliframe_alterar_excluir->msg_vazio     = "Não foi encontrado nenhum registro.";
          $cliframe_alterar_excluir->textocabec    = "#DEB887";
          $cliframe_alterar_excluir->textocorpo    = "#444444";
          $cliframe_alterar_excluir->fundocabec    = "#444444";
          $cliframe_alterar_excluir->fundocorpo    = "#eaeaea";
          $cliframe_alterar_excluir->iframe_height = "200";
          $cliframe_alterar_excluir->iframe_width  = "100%";
          $cliframe_alterar_excluir->tamfontecabec = 9;
          $cliframe_alterar_excluir->tamfontecorpo = 9;
          $cliframe_alterar_excluir->formulario    = false;
          $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
        ?>
      </td>
    </tr>
  </table>
</form>

<script>


function js_pesquisaed254_i_rechumano(mostra) {

  var sUrl  = 'func_diretor.php?funcao_js=parent.js_mostrarechumano1|ed20_i_codigo|z01_nome|ed01_c_exigeato';
      sUrl += '|dl_identificacao|dl_cpf|dl_atividade';
  if( mostra == true ) {
    js_OpenJanelaIframe('', 'db_iframe_diretor', sUrl, 'Pesquisa de Recursos Humanos', true);

  }
}

function js_mostrarechumano1() {

  document.form1.ed254_i_rechumano.value = arguments[0];
  document.form1.z01_nome.value          = arguments[1];
  document.form1.ed01_c_exigeato.value   = arguments[2];
  document.form1.identificacao.value     = arguments[3];
  document.form1.z01_cgccpf.value        = arguments[4];
  document.form1.rh37_descr.value        = arguments[5];
  db_iframe_diretor.hide();
}

function js_pesquisaed254_i_turno(mostra) {

  if( mostra == true ) {
    js_OpenJanelaIframe(
                         '',
                         'db_iframe_turno',
                         'func_turno.php?funcao_js=parent.js_mostraturno1|ed15_i_codigo|ed15_c_nome',
                         'Pesquisa de Turnos',
                         true
                       );
  } else {

    if( document.form1.ed254_i_turno.value != '') {
      js_OpenJanelaIframe(
                           '',
                           'db_iframe_turno',
                           'func_turno.php?pesquisa_chave='+document.form1.ed254_i_turno.value
                                        +'&funcao_js=parent.js_mostraturno',
                           'Pesquisa',
                           false
                         );
    } else {
      document.form1.ed15_c_nome.value = '';
    }
  }
}

function js_mostraturno( chave, erro ) {

  document.form1.ed15_c_nome.value = chave;
  if( erro == true ) {

    document.form1.ed254_i_turno.focus();
    document.form1.ed254_i_turno.value = '';
  }
}

function js_mostraturno1( chave1, chave2 ) {

  document.form1.ed254_i_turno.value = chave1;
  document.form1.ed15_c_nome.value   = chave2;
  db_iframe_turno.hide();
}

function js_pesquisaed254_i_atolegal( mostra ) {

  if( mostra == true ) {
    js_OpenJanelaIframe(
                         '',
                         'db_iframe_atolegal',
                         'func_atolegal.php?funcao_js=parent.js_mostraatolegal1|ed05_i_codigo|ed05_c_finalidade',
                         'Pesquisa de Atos Legais',
                         true
                       );
  } else {

    if( document.form1.ed254_i_atolegal.value != '' ) {
      js_OpenJanelaIframe(
                           '',
                           'db_iframe_atolegal',
                           'func_atolegal.php?pesquisa_chave='+document.form1.ed254_i_atolegal.value
                                           +'&funcao_js=parent.js_mostraatolegal',
                           'Pesquisa',
                           false
                         );
    } else {
      document.form1.ed05_c_finalidade.value = '';
    }
  }
}

function js_mostraatolegal( chave, erro ) {

  document.form1.ed05_c_finalidade.value = chave;
  if( erro == true ) {

    document.form1.ed254_i_atolegal.focus();
    document.form1.ed254_i_atolegal.value = '';
  }
}

function js_mostraatolegal1( chave1, chave2 ) {

  document.form1.ed254_i_atolegal.value  = chave1;
  document.form1.ed05_c_finalidade.value = chave2;
  db_iframe_atolegal.hide();
}

function js_valida() {

  if( document.form1.ed254_c_tipo.value == "A" && document.form1.ed254_d_datafim.value != "" ) {

    alert("Situação do Exercício ABERTO exige a Data Final do Exercício em branco!");
    return false;
  }

  if( document.form1.ed254_c_tipo.value == "F" && document.form1.ed254_d_datafim.value == "" ) {

    alert("Situação do Exercício FECHADO exige a Data Final do Exercício preenchida!");
    return false;
  }

  if( document.form1.ed254_i_turno.value == "" ) {

    alert("Campo Turno não informado!");
    return false;
  }

  if( document.form1.z01_nome.value.length < 4 ) {

    alert("Nome do Diretor deve ter no mínimo 4 dígitos!");
    document.form1.z01_nome.style.backgroundColor = '#99A9AE';
    document.form1.z01_nome.focus();
    return false;
  }

  Vemail = "<?=@$GLOBALS[Sed254_c_email]?>";
  if( jsValidaEmail( document.form1.ed254_c_email.value, Vemail ) == false ) {
    return false;
  }

  return true;
}

$('db_opcao').onclick = function() {

  if ( empty( $F('ed254_i_turno') ) ) {

    alert( 'Campo Turno não informado.' );
    return false;
  }

  if ( $F('ed01_c_exigeato') == 'S' && empty( $F('ed254_i_atolegal') ) ) {

    alert( 'Diretor(a) selecionado(a) exige a informação do Ato Legal.' );
    return false;
  }

  document.form1.submit();
}

$('ed254_i_codigo').className    = 'field-size2';
$('ed254_i_escola').className    = 'field-size2';
$('ed18_c_nome').className       = 'field-size7';
$('identificacao').className     = 'field-size2';
$('z01_nome').className          = 'field-size7';
$('z01_cgccpf').className        = 'field-size2';
$('rh37_descr').className        = 'field-size6';
$('ed254_i_turno').className     = 'field-size2';
$('ed15_c_nome').className       = 'field-size7';
$('ed254_i_atolegal').className  = 'field-size2';
$('ed05_c_finalidade').className = 'field-size7';
$('ed254_c_email').className     = 'field-size-max';
$('ed254_d_dataini').className   = 'field-size2';
$('ed254_d_datafim').className   = 'field-size2';
$('ed254_d_datacad').className   = 'field-size2';


function js_cancelar() {

  var iEscola = $F('ed254_i_escola');
  var sEscola = $F('ed18_c_nome');

  $('ed254_i_codigo').value    = '';
  $('identificacao').value     = '';
  $('z01_nome').value          = '';
  $('z01_cgccpf').value        = '';
  $('rh37_descr').value        = '';
  $('ed254_i_turno').value     = '';
  $('ed15_c_nome').value       = '';
  $('ed254_i_atolegal').value  = '';
  $('ed05_c_finalidade').value = '';
  $('ed254_c_email').value     = '';
  $('ed254_d_dataini').value   = '';
  $('ed254_d_datafim').value   = '';
  $('ed254_d_datacad').value   = '';

  location.href = "edu1_escoladiretor001.php?ed254_i_escola=" + iEscola +"&ed18_c_nome="+ sEscola;
}

</script>