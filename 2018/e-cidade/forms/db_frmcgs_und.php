<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
//MODULO: saude
$clcgs_und->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j14_codigo");
$clrotulo->label("j13_cod");
$clrotulo->label("j13_codi");
$clrotulo->label("DBtxt1");
$clrotulo->label("DBtxt5");
$clrotulo->label("s115_c_cartaosus");
$clrotulo->label("s115_i_codigo");
$clrotulo->label("s115_c_tipo");
$clrotulo->label("z01_v_municnasc");
$clrotulo->label("z01_v_ufnasc");
$clrotulo->label("z01_codigoibgenasc");

$oDaoPais    = new cl_pais;
$sCamposPais = "ed228_i_codigo,ed228_c_descr";
$sSqlPais    = $oDaoPais->sql_query_file( "", $sCamposPais, "ed228_c_descr", "" );
$rsPais      = db_query( $sSqlPais );
$aPaises     = array();

if ( $rsPais && pg_num_rows($rsPais) > 0 ) {

  for( $iContador = 0; $iContador < pg_num_rows($rsPais); $iContador++ ) {

    $oDadosPais = db_utils::fieldsMemory( $rsPais, $iContador );
    $aPaises[$oDadosPais->ed228_i_codigo] = $oDadosPais->ed228_c_descr;
  }
}

if ( !isset($z01_registromunicipio)) {
  $z01_registromunicipio = 't';
}

$retornacgs  = isset( $retornacgs ) ? $retornacgs : "";
$retornanome = isset( $retornanome ) ? $retornanome : "";
$oSaudeConfiguracao = new SaudeConfiguracao();
$lObrigarCns        = $oSaudeConfiguracao->obrigarCns();

$sInativo = '';
if ( isset($z01_b_inativo) && $z01_b_inativo == 't' ) {
  $sInativo = "checked = 'checked'";
}

$sFaleceu = '';
if ( isset($z01_b_faleceu) && $z01_b_faleceu == 't' ) {
  $sFaleceu = "checked = 'checked'";
}

$z01_d_falecimento_dia = '';
$z01_d_falecimento_mes = '';
$z01_d_falecimento_ano = '';

if ( isset($z01_d_falecimento) && !empty($z01_d_falecimento) ) {

  $iDataFalecimento      = strtotime($z01_d_falecimento);
  $z01_d_falecimento_dia = date("d",$iDataFalecimento);
  $z01_d_falecimento_mes = date("m",$iDataFalecimento);
  $z01_d_falecimento_ano = date("Y",$iDataFalecimento);
}
$sNaturalidadeBrasileiro   = "checked = 'checked'";
$sNaturalidadeNaturalizado = '';
$sNaturalidadeEstrangeiro  = '';
if ( isset($z01_i_naturalidade) ) {

  switch ($z01_i_naturalidade) {

    case 1:

      $sNaturalidadeNaturalizado = "checked = 'checked'";
      break;

    case 2:

      $sNaturalidadeEstrangeiro = "checked = 'checked'";
      break;

    default:

      $sNaturalidadeBrasileiro = "checked = 'checked'";
      break;
  }
}

$sDesconheceNomeMae = '';
if ( isset($z01_b_descnomemae) && $z01_b_descnomemae == 't' ) {
  $sDesconheceNomeMae = "checked = 'checked'";
}

?>
<form name="form1" method="post" action="" class="container">

  <fieldset>
    <legend>Dados CGS</legend>

    <fieldset class='separator'>
      <legend>Dados Pessoais</legend>
      <table class="form-container">
        <tr>
          <td title="<?=@$Tz01_i_cgsund?>">
            <?=$Lz01_i_cgsund?>
          </td>
          <td width="400">
            <?php db_input('z01_i_cgsund',15,$Iz01_i_cgsund,true,'text',3);?>
            <input type="checkbox" name="z01_b_inativo" id="z01_b_inativo" <?php echo $sInativo?>>
            <label for="z01_b_inativo" style="font-weight:normal;">Cadastro inativo</label>
          </td>

          <td title="<?=$Lz01_i_cgsund?>" class='bold'>
            CGS do Município:
          </td>
          <td>
            <?php
              $x = array( 't' => "SIM", 'f' => "NÃO");
              db_select('z01_registromunicipio',$x,true,$db_opcao,'onChange="js_zerac(this.value);"');
            ?>
          </td>
        </tr>
        <tr>
          <td title="">
            <?php db_ancora("CGM:", "js_buscaCGM();", $db_opcao);?>
          </td>
          <td>
            <?php db_input('z01_i_cgm',15,'',true,'text',3);?>
          </td>

          <td title="">
            <?php db_ancora("CGE:", "js_buscaCGE();", $db_opcao);?>
          </td>
          <td>
            <?php db_input('z01_i_cge',15,'',true,'text',3);?>
          </td>
        </tr>
        <tr>
          <td title="">
            <?php db_ancora("CIDADÃO:", "js_buscaCidadao();", $db_opcao);?>
          </td>
          <td>
            <?php db_input('z01_i_cidadao',15,'',true,'text',3);?>
          </td>


        </tr>
        <tr>
          <td title='<?=$Tz01_v_cgccpf?>'>
            <?=@$Lz01_v_cgccpf?>
          </td>
          <td>
            <?php
              db_input('z01_v_cgccpf',15,@$Iz01_v_cgccpf,true,'text',$db_opcao,"onBlur='js_verificaCGCCPF(this);js_testanome(\"\",this.value,\"\")'");
            ?>
          </td>

          <td>
            <?=@$Lz01_v_ident?>
          </td>
          <td>
            <?db_input('z01_v_ident',15,$Iz01_v_ident,true,'text',$db_opcao);?>
          </td>
        </tr>
        <tr>
          <td title=<?=@$Tz01_v_nome?>>
            <?=@$Lz01_v_nome?>
          </td>
          <td title="<?=@$Tz01_v_nome?>">
            <?php db_input('z01_v_nome',52,$Iz01_v_nome,true,'text',$db_opcao," onChange=\"js_ValidaCamposEdu(this,1,'Campo Nome','f','t',event);\"");?>
          </td>

          <td title="<?=$Tz01_d_nasc?>">
            <?=$Lz01_d_nasc?>
          </td>
          <td title="<?=$Tz01_d_nasc?>">
            <?php db_inputdata('z01_d_nasc',@$z01_d_nasc_dia,@$z01_d_nasc_mes,@$z01_d_nasc_ano,true,'text',$db_opcao);?>
          </td>
        </tr>
        <tr>
          <td title=<?=@$Tz01_v_pai?>>
            <?=@$Lz01_v_pai?>
          </td>
          <td title="<?=@$Tz01_v_pai?>">
            <?php db_input('z01_v_pai',52,$Iz01_v_pai,true,'text',$db_opcao," onChange=\"js_ValidaCamposEdu(this,1,'Campo Pai','f','t',event);\"");?>
          </td>

          <td>
            <input type="checkbox" name="z01_b_faleceu" id="z01_b_faleceu" value="t"<?php echo $sFaleceu?>> Faleceu

          </td>
          <td>
            <?php db_inputdata('z01_d_falecimento',$z01_d_falecimento_dia,$z01_d_falecimento_mes,$z01_d_falecimento_ano,true,'text',$db_opcao);?>
          </td>

        </tr>
        <tr>
          <td title=<?=@$Tz01_v_mae?>>
            <?=@$Lz01_v_mae?>
          </td>
          <td title="<?=@$Tz01_v_mae?>">
            <?php db_input('z01_v_mae',52,$Iz01_v_mae,1,'text',$db_opcao," onChange=\"js_ValidaCamposEdu(this,1,'Campo Mãe','f','t',event);\"");?>
          </td>

          <td title="<?=$Tz01_i_estciv?>">
            <?=$Lz01_i_estciv?>
          </td>
          <td>
            <?php
            $x = array("1"=>"Solteiro",
                "2"=>"Casado",
                "3"=>"Viúvo",
                "4"=>"Separado ",
                "5"=>"União C.",
                "9"=>"Ignorado");
            db_select('z01_i_estciv',$x,true,$db_opcao);
            ?>
          </td>

        </tr>
        <tr>
          <td></td>
          <td>
            <input type="checkbox" name="z01_b_descnomemae" id="z01_b_descnomemae" value="t"<?php echo $sDesconheceNomeMae?>> Desconhece o nome da mãe
          </td>


          <td><strong>Nacionalidade:</strong></td>
          <td>
            <input type="radio" name="z01_i_naturalidade" value="0"<?php echo $sNaturalidadeBrasileiro ?>> Brasileiro
            <input type="radio" name="z01_i_naturalidade" value="1"<?php echo $sNaturalidadeNaturalizado ?>> Naturalizado
            <input type="radio" name="z01_i_naturalidade" value="2"<?php echo $sNaturalidadeEstrangeiro ?>> Estrangeiro
          </td>

        </tr>

        <tr>
          <td nowrap="nowrap" class="bold">
            Cartão SUS:
          </td>
          <td>
            <?php
            db_input('s115_i_codigo',15,@$Is115_i_codigo,true,'hidden',$db_opcao);
            db_input('s115_c_cartaosus',17,@$Is115_c_cartaosus,true,'text',$db_opcao, "onChange=validaCNS();");
            db_input('s115_c_tipo',17, $Is115_c_tipo,true,'hidden',$db_opcao);
            ?>
          </td>

          <td><strong>País origem:</strong></td>
          <td>
            <?php db_select("z01_i_paisorigem",$aPaises,true,$db_opcao) ?>
          </td>

        </tr>

        <tr>

          <td title="<?=@$Tz01_v_telef?>">
            <?=@$Lz01_v_telef?>
          </td>
          <td >
            <?php db_input('z01_v_telef',15,$Iz01_v_telef,true,'text',$db_opcao);?>
          </td>

          <td>
            <?=$Lz01_v_sexo?>
          </td>
          <td>
            <?php
            $sex = array("M"=>"Masculino","F"=>"Feminino");
            db_select('z01_v_sexo',$sex,true,$db_opcao);
            ?>
          </td>


        </tr>

        <tr>

          <td title="<?=@$Tz01_v_email?>" class='bold'>
            E-mail:
          </td>
          <td>
            <?php db_input('z01_v_email',20,$Iz01_v_email,true,'text',$db_opcao);?>
          </td>
          <!--
          <td  class='bold' >
            Tipo Cartao SUS:
          </td>
          <td>
            <?php
            $x = array("D"=>"Definitivo","P"=>"Provisório");
            db_select('s115_c_tipo',$x,true,$db_opcao);
            ?>
          </td>
          -->
          <td title="<?=@$Tz01_v_telcel?>">
            <?=@$Lz01_v_telcel?>
          </td>
          <td >
            <?php db_input('z01_v_telcel',15,$Iz01_v_telcel,true,'text',$db_opcao);?>
          </td>
        </tr>

        <tr>
          <td title="<?=@$Tz01_v_munic?>">
            <?php db_ancora("Município de nascimento:", "js_verificaMunicipio();", $db_opcao);?>
          </td>
          <td>
            <?php db_input('z01_v_municnasc', 30, $Iz01_v_municnasc, true, 'text', 3);?>
            <?php db_input('z01_v_ufnasc', 2, $Iz01_v_uf, true, 'text', 3);?>
          </td>

          <td title="">

          </td>
          <td>

          </td>

        </tr>

        <tr>
          <td title="<?=@$Tz01_codigoibgenasc?>" class='bold'>
            IBGE:
          </td>
          <td >
            <?php db_input('z01_codigoibgenasc', 15, $Iz01_codigoibgenasc, true, 'text', 3);?>
          </td>
        </tr>
      </table>
    </fieldset>


    <fieldset class='separator'>
      <legend>Endereço</legend>
      <table class="form-container">
        <tr>
          <td title="<?=@$Tz01_v_cep?>">
            <?php
              db_ancora(@$Lz01_v_cep,"js_cepcon(true);",$db_opcao);
            ?>
          </td>
          <td>
            <?php db_input('z01_v_cep',8,$Iz01_v_cep,true,'text',$db_opcao);?>
            <input type="button" name="buscacep" value="Pesquisar" onClick="js_cepcon(false);">
          </td>
        </tr>
        <tr>
          <td title="<?=@$Tz01_v_ender?>">
            <?php db_ancora(@$Lz01_v_ender,"js_ruas();",$db_opcao);?>
          </td>
          <td>
           <?db_input('z01_v_ender',52,$Iz01_v_ender,true,'text',$db_opcao);?>
          </td>
        </tr>
        <tr>
          <td title="<?=@$Tz01_i_numero?>">
            <?=@$Lz01_i_numero?>
          </td>
          <td>
            <a name="AN3">
              <?php db_input('z01_i_numero',8,$Iz01_i_numero,true,'text',$db_opcao);?>
          </td>
          <td>
            <?=@$Lz01_v_compl?>
          </td>
          <td>
            <?php db_input('z01_v_compl',15,$Iz01_v_compl,true,'text',$db_opcao);?>
            </a>
          </td>
        </tr>
        <tr>
          <td title="<?=$Tz01_v_bairro?>">
            <?php db_ancora(@$Lz01_v_bairro,"js_bairro();",$db_opcao);?>
          </td>
          <td >
            <?php
              db_input('j13_codi',10,$Ij13_codi,true,'hidden',$db_opcao);
              db_input('z01_v_bairro',52,$Iz01_v_bairro,true,'text',3);
            ?>
          </td>
          <td title="<?=@$Tz01_d_cadast?>">
            <?=@$Lz01_d_cadast?>
          </td>
          <td>
            <?php db_inputdata('z01_d_cadast',@$z01_d_cadast_dia,@$z01_d_cadast_mes,@$z01_d_cadast_ano,true,'text',$db_opcao);?>
          </td>
        </tr>
        <tr>
          <td title="<?=@$Tz01_v_munic?>">
            <?php db_ancora("Município:", "js_buscaMunicipio();", $db_opcao);?>
          </td>
          <td>
            <?php db_input('z01_v_munic', 30, $Iz01_v_munic, true, 'text', 3);?>
          </td>
          <td title="<?=@$Tz01_v_uf?>">
            <?=@$Lz01_v_uf?>
          </td>
          <td>
            <?php db_input('z01_v_uf', 2, $Iz01_v_uf, true, 'text', 3);?>
         </td>
        </tr>

        <tr>
          <td title="<?=@$Tz01_codigoibge?>" class='bold'>
            IBGE:
          </td>
          <td >
            <?php db_input('z01_codigoibge', 15, $Iz01_codigoibge, true, 'text', 3);?>
          </td>

          <td title="<?=@$Tz01_v_cxpostal?>">
            <?=@$Lz01_v_cxpostal?>
          </td>
          <td >
            <?php db_input('z01_v_cxpostal',15,$Iz01_v_cxpostal,true,'text',$db_opcao);?>
          </td>
        </tr>

      </table>
    </fieldset>
  </fieldset>
  <input type="hidden" id="lObrigarCns" name="lObrigarCns" value="<?=$lObrigarCns ? 1 : 0;?>">
  <input type="hidden"
         value="<?=( $db_opcao == 1 ? "incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir" ) )?>"
         name="<?=( $db_opcao == 1 ? "incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir" ) )?>" />
  <input name="<?=( $db_opcao == 1 ? "incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir" ) )?>"
         type="button"
         id="db_opcao"
         value="<?=( $db_opcao == 1 ? "Incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir" ) )?>"
         <?=( $db_botao == false ? "disabled" : "" )?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" <?=$db_opcao==1?"disabled":""?>>
  <input name="novo" type="button" id="novo" value="Novo Registro" onclick="js_novo()" <?=$db_opcao==1?"disabled":""?>>
  <?php
    if ( isset( $retornacgs ) ) {
     echo '<input name="fechar" type="button" value="Fechar" onclick="parent.db_iframe_cgs_und.hide();">';
    } elseif ( isset( $funcao_js ) ) {

      echo '<input name="fechar" id="fechar" type="button" value="Fechar" onclick="parent.db_iframe_cgs_und.hide();">';
      echo '<input name="funcaojs" id="funcaojs" type="hidden" value="'.$funcao_js.'">';
    }
  ?>
  <input name="exportar-csv" type="button" id="exportar-csv" value="Exportar para CSV" onclick="abrirCSV()" <?=$db_opcao==1?"disabled":""?>>
  <input name="exportar" type="button" id="exportar" value="Exportar para PDF" onclick="abrirPDF()" <?=$db_opcao==1?"disabled":""?>>
</form>
<script>
var oGet   = js_urlToObject();
var iOpcao = <?=$db_opcao;?>;

$("z01_i_paisorigem").value   = 10;

const MENSAGEM_DB_FRMCGS_UND = 'saude.ambulatorial.db_frmcgs_und.';

$('db_opcao').onclick = function() {
  validaDados();
};

function validaDados() {

  if ( iOpcao != 3 ) {

    if ( $F('lObrigarCns') == 1 && $F('s115_c_cartaosus') == '' ) {

      alert( _M( MENSAGEM_DB_FRMCGS_UND + 'informe_cartao_sus') );
      return;
    }

    if( empty( $F('z01_codigoibge') ) ) {

      alert(  _M( MENSAGEM_DB_FRMCGS_UND + 'informe_ibge') );
      return;
    }

    if( $F('z01_v_telef') != '' ) {

      var iTelefone = $('z01_v_telef').value.replace(/[^\d]+/g,'');

      if( iTelefone.length != 10 && iTelefone.length != 11 ) {

        alert( _M( MENSAGEM_DB_FRMCGS_UND + 'telefone_invalido') );
        return;
      }
      $('z01_v_telef').value = iTelefone;
    }

    if( $F('z01_v_telcel') != '' ) {

      var iTelefone = $('z01_v_telcel').value.replace(/[^\d]+/g,'');

      if( iTelefone.length != 10 && iTelefone.length != 11 ) {

        alert( _M( MENSAGEM_DB_FRMCGS_UND + 'celular_invalido') );
        return;
      }
      $('z01_v_telcel').value = iTelefone;
    }
  }

  document.form1.submit();
}

function js_ruas() {
  js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?rural=1&funcao_js=parent.js_preenchepesquisaruas|j14_codigo|j14_nome','Pesquisa',true);
}

function js_preenchepesquisaruas( chave, chave1 ) {

  document.form1.z01_v_ender.value = chave1;
  db_iframe_ruas.hide();
}

function js_bairro() {
  js_OpenJanelaIframe('','db_iframe_bairro','func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro|j13_codi|j13_descr','Pesquisa',true);
}

function js_preenchebairro( chave, chave1 ) {

  document.form1.j13_codi.value = chave;
  document.form1.z01_v_bairro.value = chave1;
  db_iframe_bairro.hide();
}

function js_ruas1() {
  js_OpenJanelaIframe('','db_iframe_ruas1','func_ruas.php?rural=1&funcao_js=parent.js_preenchepesquisaruas1|j14_codigo|j14_nome','Pesquisa',true);
}

function js_preenchepesquisaruas1( chave, chave1 ) {
  document.form1.z01_v_endcon.value = chave1;
  db_iframe_ruas1.hide();
}

function js_bairro1() {
  js_OpenJanelaIframe('','db_iframe_bairro1','func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro1|j13_codi|j13_descr','Pesquisa',true);
}

function js_preenchebairro1( chave, chave1 ) {
  document.form1.z01_v_baicon.value = chave1;
  db_iframe_bairro1.hide();
}

function js_pesquisa() {
  js_OpenJanelaIframe('','db_iframe_cgs_und','func_cgs_und.php?funcao_js=parent.js_preenchepesquisa|z01_i_cgsund|s115_c_cartaosus&redireciona=parent.parent.js_preenchepesquisa(document.form1.z01_i_cgsund.value)','Pesquisa CGS',true);
}

function js_preenchepesquisa( chave, chave1 ) {

  db_iframe_cgs_und.hide();
  location.href = "<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>?chavepesquisa="+chave+"&retornacgs=<?=$retornacgs?>&retornanome=<?=$retornanome?>&sCartaoSus="+chave1;
}

function js_novo() {
  parent.location="sau1_cgs_und000.php?id=1";
}

function js_zerac( municipio ) {

  document.form1.z01_v_cep.value    = "";
  document.form1.z01_v_ender.value  = "";
  document.form1.z01_v_munic.value  = "";
  document.form1.z01_v_uf.value     = "";
  document.form1.z01_v_bairro.value = "";
  $('z01_i_numero').value           = "";
  $('z01_v_compl').value            = "";
  $('z01_v_cxpostal').value         = "";
  $('z01_codigoibge').value         = "";

  if ( $F('z01_registromunicipio') == "f" ) {

    document.form1.buscacep.disabled             = false;
    document.form1.z01_v_bairro.readOnly         = false;
    document.form1.z01_v_bairro.style.background = "#FFFFFF";
    document.links[0].style.color                = "#000000";
    document.links[0].style.textDecoration       = "none";
    document.links[0].href                       = "";
    document.links[1].style.color                = "#000000";
    document.links[1].style.textDecoration       = "none";
    document.links[1].href                       = "";
    document.links[2].style.color                = "#000000";
    document.links[2].style.textDecoration       = "none";
    document.links[2].href                       = "";
  } else if ( $F('z01_registromunicipio') == "t" ) {

    document.form1.z01_v_bairro.readOnly         = false;
    document.form1.z01_v_bairro.style.background = "#FFFFFF";
    document.links[0].style.color                = "blue";
    document.links[0].style.textDecoration       = "underline";
    document.links[0].href                       = "";
    document.links[1].style.color                = "blue";
    document.links[1].style.textDecoration       = "underline";
    document.links[1].href                       = "";
    document.links[2].style.color                = "blue";
    document.links[2].style.textDecoration       = "underline";
    document.links[2].href                       = "";
    document.form1.submit();
  }
}

if ( $F('z01_registromunicipio') == 't') {
  document.form1.buscacep.disabled = true;
}

function js_cepcon( abre ) {

  if ( abre == true ) {
    js_OpenJanelaIframe('','db_iframe_cep','func_cep.php?funcao_js=parent.js_preenchecepcon|cep|cp06_logradouro|cp05_localidades|cp05_sigla|cp01_bairro|z01_v_cep','Pesquisa',true);
  } else {
    js_OpenJanelaIframe('','db_iframe_cep','func_cep.php?pesquisa_chave='+document.form1.z01_v_cep.value+'&funcao_js=parent.js_preenchecepcon|cep|cp06_logradouro|cp05_localidades|cp05_sigla|cp01_bairro|z01_v_cep','Pesquisa',false);
  }
}

function js_preenchecepcon( chave, chave1, chave2, chave3, chave4 ) {

  document.form1.z01_v_cep.value    = chave;
  document.form1.z01_v_ender.value  = chave1;
  document.form1.z01_v_bairro.value = chave4;

  db_iframe_cep.hide();
}

function js_preenchecepcon1( chave, chave1, chave2, chave3, chave4 ) {

  if( chave == "" && chave1 == "" && chave2 == "" && chave3 == "" && chave4 == "" ) {

    alert('CEP não encontrado!');
    document.form1.z01_v_cep.focus();
  }

  document.form1.z01_v_cep.value    = chave;
  document.form1.z01_v_ender.value  = chave1;
  document.form1.z01_v_bairro.value = chave4;
}

/**
 * Verifica se o que foi digitado é diferente de um numero e apaga a letra digitada
 */
$('s115_c_cartaosus').onkeyup = function() {
  $('s115_c_cartaosus').value = $F('s115_c_cartaosus').somenteNumeros();
};

$('s115_c_cartaosus').onkeydown = function() {
  $('s115_c_cartaosus').value = $F('s115_c_cartaosus').somenteNumeros();
};

/**
 * Valida o código do cartão SUS
 * @return {boolean}
 */
function validaCNS() {

  if( $F('s115_c_cartaosus').trim() == '' ) {
    return true;
  }

  if ( !$F('s115_c_cartaosus').validaCNS() ) {

    alert("Número do cartão do SUS inválido.");
    $('s115_c_cartaosus').value = "";
    return false
  }

  return true;
}

function abrirPDF() {

  if ( oGet.chavepesquisa && oGet.chavepesquisa != '' ) {
    window.open( 'sau1_cgs_und002_pdf.php?chavepesquisa=' + oGet.chavepesquisa,
                 '',
                 'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ', scrollbars=1, location=0 ');
  }
}

function abrirCSV() {

  if ( oGet.chavepesquisa && oGet.chavepesquisa != '' ) {
    window.location.href='sau1_cgs_und002_csv.php?chavepesquisa=' + oGet.chavepesquisa ;
  }
}

function js_verificaMunicipio() {
  checked = getCheckedRadio(document.form1.z01_i_naturalidade);

  if( checked !== false && checked.value == 0) {
    js_buscaMunicipioNascimento();
  } else {
    alert('A naturalidade deve ser "Brasileiro"');
  }

}

function js_buscaMunicipioNascimento() {

  var sUrl  = "func_cadendermunicipiosistema.php";
  sUrl += "?iTipoSistema=4";
  sUrl += "&funcao_js=parent.js_retornoMunicipioNascimento|db72_descricao|db71_sigla|db125_codigosistema";
  js_OpenJanelaIframe('', 'db_iframe_cadendermunicipiosistema', sUrl, 'Pesquisa Município', true);
}

function js_retornoMunicipioNascimento() {

  $('z01_v_municnasc').value    = arguments[0];
  $('z01_v_ufnasc').value       = arguments[1];
  $('z01_codigoibgenasc').value = arguments[2];

  db_iframe_cadendermunicipiosistema.hide();
}

function js_buscaCGM() {

  var sUrl  = "func_buscacgm.php";
  sUrl += "?funcao_js=parent.js_retornoBuscaCGM|z01_numcgm";
  js_OpenJanelaIframe('', 'db_iframe_buscacgm', sUrl, 'Busca CGM', true);
}

function js_retornoBuscaCGM(cod) {

  $('z01_i_cgm').value    = cod;

  db_iframe_buscacgm.hide();
}

function js_buscaCGE() {

  var sUrl  = "func_buscacge.php";
  sUrl += "?funcao_js=parent.js_retornoBuscaCGE|ed47_i_codigo";
  js_OpenJanelaIframe('', 'db_iframe_buscacge', sUrl, 'Busca CGE', true);
}

function js_retornoBuscaCGE(cod) {

  $('z01_i_cge').value    = cod;

  db_iframe_buscacge.hide();
}

function js_buscaCidadao() {

  var sUrl  = "func_buscacidadao.php";
  sUrl += "?funcao_js=parent.js_retornoBuscaCidadao|ov02_sequencial";
  js_OpenJanelaIframe('', 'db_iframe_buscacidadao', sUrl, 'Busca cidadão', true);
}

function js_retornoBuscaCidadao(cod) {

  $('z01_i_cidadao').value    = cod;

  db_iframe_buscacidadao.hide();
}

function js_falecido() {

  if(document.form1.z01_b_faleceu.checked == true) {

    document.form1.z01_d_falecimento.disabled = false;
  } else {

    document.form1.z01_d_falecimento.disabled = true;
    document.form1.z01_d_falecimento.value    = '';
  }
}
js_falecido();
$('z01_b_faleceu').addEventListener('click', js_falecido);

function js_mae() {
  if(document.form1.z01_b_descnomemae.checked == true) {
    document.form1.z01_v_mae.disabled = true;
  } else {
    document.form1.z01_v_mae.disabled = false;
  }
}
js_mae();
$('z01_b_descnomemae').addEventListener('click', js_mae);



function getCheckedRadio(radio_group) {
  for (var i = 0; i < radio_group.length; i++) {
    var button = radio_group[i];
    if (button.checked) {
      return button;
    }
  }
  return false;
}

function mostraPaisOrigem(){

  if(this.value > 0) {
    document.form1.z01_i_paisorigem.disabled = false;
    document.form1.z01_v_municnasc.value = '';
    document.form1.z01_codigoibgenasc.value = '';
  } else {
    document.form1.z01_i_paisorigem.disabled = true;
  }
}

document.form1.z01_i_naturalidade[0].addEventListener('click', mostraPaisOrigem);
document.form1.z01_i_naturalidade[1].addEventListener('click', mostraPaisOrigem);
document.form1.z01_i_naturalidade[2].addEventListener('click', mostraPaisOrigem);

checked = getCheckedRadio(document.form1.z01_i_naturalidade);
if( checked !== false && checked.value > 0 ) {
  document.form1.z01_i_paisorigem.disabled = false;
} else {
  document.form1.z01_i_paisorigem.disabled = true;
}

<?php if(isset($GLOBALS['z01_i_paisorigem']) && !empty($GLOBALS['z01_i_paisorigem'])): ?>
  document.form1.z01_i_paisorigem.value = <?=$GLOBALS['z01_i_paisorigem']?>;
<?php endif; ?>

function js_buscaMunicipio() {

  var sUrl  = "func_cadendermunicipiosistema.php";
      sUrl += "?iTipoSistema=4";
      sUrl += "&funcao_js=parent.js_retornoMunicipio|db72_descricao|db71_sigla|db125_codigosistema";
  js_OpenJanelaIframe('', 'db_iframe_cadendermunicipiosistema', sUrl, 'Pesquisa Município', true);
}

function js_retornoMunicipio() {

  $('z01_v_munic').value    = arguments[0];
  $('z01_v_uf').value       = arguments[1];
  $('z01_codigoibge').value = arguments[2];

  db_iframe_cadendermunicipiosistema.hide();
}


/**
 * Valida somente caracteres alfa-numericos
 */
$('z01_v_ident').observe('keyup', function () {

  var oRegex = /^[A-Za-z0-9]+$/;
  if (  !oRegex.test( this.value ) ) {
    this.value = "";
  }
});

$('z01_i_cgsund').addClassName("field-size3");
$('z01_v_cgccpf').addClassName("field-size3");
$('z01_v_nome').addClassName("field-size9");
$('z01_v_pai').addClassName("field-size9");
$('z01_v_mae').addClassName("field-size9");
$('s115_c_cartaosus').addClassName("field-size3");
$('z01_v_cep').addClassName("field-size2");
$('z01_v_ender').addClassName("field-size9");
$('z01_i_numero').addClassName("field-size2");
$('z01_v_bairro').addClassName("field-size9");
$('z01_v_munic').addClassName("field-size9");
$('z01_v_email').addClassName("field-size9");
$('z01_v_cxpostal').addClassName("field-size3");
$('z01_v_ident').addClassName("field-size3");
$('z01_v_compl').addClassName("field-size3");
$('z01_v_telef').addClassName("field-size3");
$('z01_v_telcel').addClassName("field-size3");
$('z01_codigoibge').addClassName("field-size3");

$('z01_v_telef').onkeypress = function() {
  mascaraTelefone( this );
};

$('z01_v_telcel').onkeypress = function() {
  mascaraTelefone( this );
};

if( $('z01_v_telef').value != '' ) {
  tratamentoMascaraTelefone( $('z01_v_telef').value, true );
}

if( $('z01_v_telcel').value != '' ) {
  tratamentoMascaraTelefone( $('z01_v_telcel').value, true );
}
</script>