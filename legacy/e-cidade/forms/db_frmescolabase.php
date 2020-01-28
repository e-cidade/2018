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
include("dbforms/db_classesgenericas.php");

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clescolabase->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("ed18_i_codigo");
$clrotulo->label("ed31_i_codigo");
$clrotulo->label("ed05_i_codigo");
$clrotulo->label("ed18_c_nome");
$clrotulo->label("ed31_c_descr");

$db_botao1 = false;

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

$ed77_i_escola = db_getsession("DB_coddepto");

@$result = $clescola->sql_record( $clescola->sql_query( "", "ed18_c_nome", "", " ed18_i_codigo = {$ed77_i_escola}" ) );
if( $clescola->numrows > 0 ) {
  db_fieldsmemory( $result, 0 );
}
?>
<form name="form1" method="post" action="">
  <div class="container">
    <fieldset>
      <legend>Base Curricular <?=$ed31_c_descr?></legend>
      <table>
        <tr>
          <td nowrap title="<?=@$Ted77_i_escola?>">
            <label for="ed77_i_escola">
              <?php
              db_ancora( $Led77_i_escola, "", 3 );
              ?>
            </label>
          </td>
          <td>
            <?php
            db_input( 'ed77_i_escola', 15, $Ied77_i_escola, true, 'text', 3 );
            db_input( 'ed18_c_nome',   40, $Ied18_c_nome,   true, 'text', 3 );
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Ted77_i_base?>">
            <label for="ed77_i_base">
              <?php
              db_ancora( $Led77_i_base, "", 3 );
              ?>
            </label>
          </td>
          <td>
            <?php
            db_input( 'ed77_i_base',  15, $Ied77_i_base,  true, 'text', 3 );
            db_input( 'ed31_c_descr', 40, $Ied31_c_descr, true, 'text', 3 );
           ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Ted77_i_basecont?>">
            <label for="ed77_i_basecont">
              <?php
              db_ancora( $Led77_i_basecont, "js_pesquisaed77_i_basecont(true);", $db_opcao );
              ?>
            </label>
          </td>
          <td>
            <?php
            db_input( 'ed77_i_basecont', 15, $Ied77_i_basecont,  true, 'text', $db_opcao, "js_pesquisaed77_i_basecont(false);" );
            db_input( 'ed57_i_base',     40, 'ed31_c_descrcont', true, 'text', 3 );
           ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input name="ed77_i_codigo" type="hidden" value="<?=@$ed77_i_codigo?>">
    <input name="<?=( $db_opcao == 1 ? "incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir" ) )?>"
           type="submit"
           id="db_opcao"
           value="<?=( $db_opcao == 1 ? "Incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir" ) )?>"
           <?=( $db_botao == false ? "disabled" : "" )?>
           <?=( ( $db_opcao == 3 || $db_opcao == 33 ) ? "onclick='return Mensagem()'" : "" )?> >
    <input name="cancelar" type="submit" value="Cancelar" <?=( $db_botao1 == false ? "disabled" : "" )?> >
  </div>
  <table width="100%">
    <tr>
      <td valign="top">
        <?php
        $campos= "escolabase.ed77_i_codigo,
                  escolabase.ed77_i_escola,
                  escola.ed18_c_nome,
                  escolabase.ed77_i_base,
                  base.ed31_c_descr,
                  escolabase.ed77_i_basecont,
                  basecont.ed31_c_descr as ed57_i_base";
        $chavepri= array(
                           "ed77_i_codigo"   => @$ed77_i_codigo,
                           "ed77_i_escola"   => @$ed77_i_escola,
                           "ed18_c_nome"     => @$ed18_c_nome,
                           "ed77_i_base"     => @$ed77_i_base,
                           "ed31_c_descr"    => @$ed31_c_descr,
                           "ed77_i_basecont" => @$ed77_i_basecont,
                           "ed57_i_base"     => @$ed57_i_base
                        );

        $cliframe_alterar_excluir->chavepri      = $chavepri;
        @$cliframe_alterar_excluir->sql          = $clescolabase->sql_query("",$campos,"ed18_c_nome"," ed77_i_base = $ed77_i_base");
        @$cliframe_alterar_excluir->sql_disabled = $clescolabase->sql_query("",$campos,"ed18_c_nome"," ed77_i_escola != $ed77_i_escola");
        $cliframe_alterar_excluir->campos        = "ed18_c_nome,ed57_i_base";
        $cliframe_alterar_excluir->legenda       = "Base de Continuação";
        $cliframe_alterar_excluir->msg_vazio     = "Não foi encontrado nenhum registro.";
        $cliframe_alterar_excluir->textocabec    = "#DEB887";
        $cliframe_alterar_excluir->textocorpo    = "#444444";
        $cliframe_alterar_excluir->fundocabec    = "#444444";
        $cliframe_alterar_excluir->fundocorpo    = "#eaeaea";
        $cliframe_alterar_excluir->iframe_height = "150";
        $cliframe_alterar_excluir->iframe_width  = "100%";
        $cliframe_alterar_excluir->tamfontecabec = 9;
        $cliframe_alterar_excluir->tamfontecorpo = 9;
        $cliframe_alterar_excluir->opcoes        = 2;
        $cliframe_alterar_excluir->formulario    = false;
        $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
        ?>
      </td>
    </tr>
  </table>
  </center>
</form>
<script>
function js_pesquisaed77_i_basecont( mostra ) {

  if( mostra == true ) {
    js_OpenJanelaIframe(
                         '',
                         'db_iframe_base',
                         'func_escolabasecont.php?base='+document.form1.ed77_i_base.value
                                               +'&funcao_js=parent.js_mostrabase1|ed31_i_codigo|ed31_c_descr',
                         'Pesquisa de Bases Curriculares',
                         true
                       );
  } else {

    if( document.form1.ed77_i_basecont.value != '' ) {
      js_OpenJanelaIframe(
                           '',
                           'db_iframe_base',
                           'func_escolabasecont.php?base='+document.form1.ed77_i_base.value
                                                 +'&pesquisa_chave='+document.form1.ed77_i_basecont.value
                                                 +'&funcao_js=parent.js_mostrabase',
                           'Pesquisa Base Curricular',
                           false
                         );
    } else {
      document.form1.ed57_i_base.value = '';
    }
  }
}

function js_mostrabase( chave1, erro ) {

  document.form1.ed57_i_base.value = chave1;

  if( erro == true ) {

    document.form1.ed77_i_basecont.focus();
    document.form1.ed77_i_basecont.value = '';
  }
}

function js_mostrabase1( chave1, chave2 ) {

  document.form1.ed77_i_basecont.value = chave1;
  document.form1.ed57_i_base.value     = chave2;
  db_iframe_base.hide();
}

function Mensagem() {

  if( confirm( "Ao excluir este registro, esta base curricular não mais aparecerá para sua escola! Confirmar Exclusão?" ) ) {
    return true;
  } else {
    return false;
  }
}

$('ed77_i_escola').className   = 'field-size2';
$('ed18_c_nome').className     = 'field-size7';
$('ed77_i_base').className     = 'field-size2';
$('ed31_c_descr').className    = 'field-size7';
$('ed77_i_basecont').className = 'field-size2';
$('ed57_i_base').className     = 'field-size7';
</script>