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

require_once(modification("dbforms/db_classesgenericas.php"));

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clatividaderh->rotulo->label();
$oDaoFuncaoAtividade->rotulo->label();
$db_botao1 = false;

$aFuncoes            = array();
$sSqlFuncaoAtividade = $oDaoFuncaoAtividade->sql_query_file( null, "ed119_sequencial, ed119_descricao" );
$rsFuncaoAtividade   = db_query( $sSqlFuncaoAtividade );

if( $rsFuncaoAtividade && pg_num_rows( $rsFuncaoAtividade ) > 0 ) {

  $iTotalFuncaoAtividade = pg_num_rows( $rsFuncaoAtividade );
  for( $iContador = 0; $iContador < $iTotalFuncaoAtividade; $iContador++ ) {

    $oDadosFuncaoAtividade                                = db_utils::fieldsMemory( $rsFuncaoAtividade, $iContador );
    $aFuncoes[ $oDadosFuncaoAtividade->ed119_sequencial ] = mb_strtoupper( $oDadosFuncaoAtividade->ed119_descricao );
  }
}

if( isset($opcao) && ($opcao == "alterar" || $opcao == "excluir" ) ) {

  if( isset( $ed01_i_funcaoadmin ) && $ed01_i_funcaoadmin == "DIRETOR(A)" ) {
  	$ed01_i_funcaoadmin = 2;
  }	else if( isset( $ed01_i_funcaoadmin ) && $ed01_i_funcaoadmin == "SECRETÁRIO(A)" ) {
  	$ed01_i_funcaoadmin = 3;
  } else {
  	$ed01_i_funcaoadmin = 1;
  }

  if( isset( $ed01_funcaoatividade ) ) {
    $ed01_funcaoatividade = array_search( $ed01_funcaoatividade, $aFuncoes );
  }
}

if( isset( $opcao ) && $opcao == "alterar" ) {

  $db_opcao  = 2;
  $db_botao1 = true;
} else if( isset( $opcao ) && $opcao == "excluir" || isset( $db_opcao ) && $db_opcao == 3 ) {

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
      <legend>Funções/Atividades</legend>
      <table>
        <tr style="display: none;">
          <td nowrap title="<? echo $Ted01_i_codigo; ?>">
            <label for='ed01_i_codigo'> <?php echo$Led01_i_codigo; ?></label>
          </td>
          <td>
            <?php
              db_input( 'ed01_i_codigo', 10, $Ied01_i_codigo, true, 'text', 3);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Ted01_c_descr; ?>">
            <label for='ed01_c_descr'> <?php echo $Led01_c_descr;?></label>
          </td>
          <td>
            <?php
            db_input( 'ed01_c_descr', 50, $Ied01_c_descr, true, 'text', $db_opcao );
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Ted01_c_regencia;?>">
            <label for='ed01_c_regencia'> <?php echo $Led01_c_regencia;?></label>
          </td>
          <td>
            <?php
              $x = array( 'N' => 'NÃO', 'S' => 'SIM' );
              db_select( 'ed01_c_regencia', $x, true, $db_opcao );
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <label for='ed01_funcaoatividade'> <?php echo $Led01_funcaoatividade; ?></label>
          </td>
          <td>
            <?php
            db_select( "ed01_funcaoatividade", $aFuncoes, true, $db_opcao );
            ?>
          </td>
        </tr>
        <tr  style="display: none;">
          <td nowrap title="<?php echo $Ted01_c_docencia; ?>">
            <label for='ed01_c_docencia'><?php echo $Led01_c_docencia; ?></label>
          </td>
          <td>
            <?php
            $x = array( 'N' => 'NÃO', 'S' => 'SIM' );
            db_select( 'ed01_c_docencia', $x, true, $db_opcao );
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Ted01_i_funcaoadmin; ?>">
            <label for='ed01_i_funcaoadmin'> <?php echo $Led01_i_funcaoadmin; ?> </label>
          </td>
          <td>
            <?php
            $aFuncao = array(
                              "1" => "NÃO",
                              "2" => "DIRETOR(A)",
                              "3" => "SECRETÁRIO(A)"
                            );
            db_select( 'ed01_i_funcaoadmin', $aFuncao, true, $db_opcao, "" );
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Ted01_c_exigeato; ?>">
            <label for='ed01_c_exigeato'> <?php echo $Led01_c_exigeato; ?></label>
          </td>
          <td>
            <?php
            $x = array( 'N' => 'NÃO', 'S' => 'SIM' );
            db_select( 'ed01_c_exigeato', $x, true, $db_opcao );
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Ted01_c_efetividade; ?>">
            <label for='ed01_c_efetividade'> <?php echo $Led01_c_efetividade; ?></label>
          </td>
          <td>
            <?php
            $x = array( 'FUNC' => 'FUNCIONÁRIOS', 'PROF' => 'PROFESSORES' );
            db_select( 'ed01_c_efetividade', $x, true, $db_opcao );
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Ted01_atividadeescolar; ?>">
            <label for='ed01_atividadeescolar'> <?php echo $Led01_atividadeescolar; ?></label>
          </td>
          <td>
            <?php
            $x = array( 'f' => 'NÃO', 't' => 'SIM' );
            db_select( 'ed01_atividadeescolar', $x, true, $db_opcao );
            ?>
          </td>
      </table>
    </fieldset>
    <input name="<?php echo( $db_opcao == 1 ? "incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir" ) );?>"
           type="submit"
           id="db_opcao"
           value="<?php echo ( $db_opcao == 1 ? "Incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir" ) );?>"
      <?php echo ( $db_botao == false ? "disabled" : "" );?> >
    <input name="cancelar" type="submit" value="Cancelar" <?php echo ( $db_botao1 == false ? "disabled" : "" );?> >
  </div>
</form>
<table width='100%'>
  <tr>
    <td valign="top">
      <?php
      $sCampos  = " ed01_i_codigo, ed01_c_descr, ed01_c_regencia, ed01_c_docencia, ed01_c_exigeato, ed01_c_efetividade,";
      $sCampos .= " case when ed01_i_funcaoadmin = '1' then 'NÃO' ";
      $sCampos .= "      when ed01_i_funcaoadmin = '2' then 'DIRETOR(A)' ";
      $sCampos .= "      when ed01_i_funcaoadmin = '3' then 'SECRETÁRIO(A)' ";
      $sCampos .= "   end as ed01_i_funcaoadmin ";
      $sCampos .= ", case ";
      $sCampos .= "       when ed01_funcaoatividade = '0' ";
      $sCampos .= "            then 'NENHUM' ";
      $sCampos .= "       when ed01_funcaoatividade = '1' ";
      $sCampos .= "            then 'DOCENTE' ";
      $sCampos .= "       when ed01_funcaoatividade = '2' ";
      $sCampos .= "            then 'AUXILIAR/ASSISTENTE EDUCACIONAL' ";
      $sCampos .= "       when ed01_funcaoatividade = '3' ";
      $sCampos .= "            then 'PROFISSIONAL/MONITOR DE ATIVIDADE COMPLEMENTAR' ";
      $sCampos .= "       when ed01_funcaoatividade = '4' ";
      $sCampos .= "            then 'TRADUTOR INTÉRPRETE DE LIBRAS' ";
      $sCampos .= "       when ed01_funcaoatividade = '5' ";
      $sCampos .= "            then 'DOCENTE TITULAR - COORDENADOR DE TUTORIA(DE MÓDULO OU DISCIPLINA) - EAD' ";
      $sCampos .= "       when ed01_funcaoatividade = '6' ";
      $sCampos .= "            then 'DOCENTE TUTOR - (DE MÓDULO OU DISCIPLINA)' ";
      $sCampos .= "   end as ed01_funcaoatividade , ";
      $sCampos .= "   ed01_atividadeescolar";

      $ed01_i_codigo         = '';
      $ed01_c_descr          = '';
      $ed01_c_regencia       = '';
      $ed01_c_docencia       = '';
      $ed01_c_exigeato       = '';
      $ed01_c_efetividade    = '';
      $ed01_i_funcaoadmin    = '';
      $ed01_funcaoatividade  = '';
      $ed01_atividadeescolar = '';

      $aChaves  = array(
                         "ed01_i_codigo"         => $ed01_i_codigo,
                         "ed01_c_descr"          => $ed01_c_descr,
                         "ed01_c_regencia"       => $ed01_c_regencia,
                         "ed01_c_docencia"       => $ed01_c_docencia,
                         "ed01_c_exigeato"       => $ed01_c_exigeato,
                         "ed01_c_efetividade"    => $ed01_c_efetividade,
                         "ed01_i_funcaoadmin"    => $ed01_i_funcaoadmin,
                         "ed01_funcaoatividade"  => $ed01_funcaoatividade,
                         "ed01_atividadeescolar" => $ed01_atividadeescolar
                       );

      $sCamposFrame  = "ed01_i_codigo, ed01_c_descr, ed01_c_regencia, ed01_c_docencia, ed01_c_exigeato";
      $sCamposFrame .= ", ed01_c_efetividade, ed01_i_funcaoadmin, ed01_funcaoatividade, ed01_atividadeescolar";

      $cliframe_alterar_excluir->chavepri      = $aChaves;
      $cliframe_alterar_excluir->sql           = $clatividaderh->sql_query( $ed01_i_codigo, $sCampos, "ed01_c_descr" );
      $cliframe_alterar_excluir->sql_disabled  = $clatividaderh->sql_query( "", "*", "ed01_c_descr", "ed01_c_atualiz = 'N'" );
      $cliframe_alterar_excluir->campos        = $sCamposFrame;
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
<script>
var aFuncoesDocentes = [ 1, 5, 6 ];

$('ed01_c_descr').className          = 'field-size-max';
$('ed01_c_regencia').className       = 'field-size-max';
$('ed01_funcaoatividade').className  = 'field-size-max';
$('ed01_c_docencia').className       = 'field-size-max';
$('ed01_i_funcaoadmin').className    = 'field-size-max';
$('ed01_c_exigeato').className       = 'field-size-max';
$('ed01_c_efetividade').className    = 'field-size-max';
$('ed01_atividadeescolar').className = 'field-size-max';

$('ed01_funcaoatividade').onchange = function() {

  $('ed01_c_docencia').value = 'N';

  if( js_search_in_array( aFuncoesDocentes, $F('ed01_funcaoatividade') ) ) {
    $('ed01_c_docencia').value = 'S';
  }
}

$('ed01_c_regencia').observe('change', function () {

  $('ed01_atividadeescolar').removeAttribute('disabled');
  if ( $F('ed01_c_regencia') == 'S') {

    $('ed01_atividadeescolar').value = 'f';
    $('ed01_atividadeescolar').setAttribute('disabled', 'disabled');
  }
});
</script>
