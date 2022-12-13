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

//MODULO: saude
$clcgs_und->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j14_codigo");
$clrotulo->label("j13_cod");
$clrotulo->label("j13_codi");
$clrotulo->label("DBtxt1");
$clrotulo->label("DBtxt5");
?>
<form name="form1" method="post" action="">
  <div class="container">
    <fieldset style="width: 730px;">
      <legend>Documentos</legend>
      <table width="100%">
        <!-- LINHA DOS FIELDSET'S GERAIS E IDENTIDADE -->
        <tr>
          <td colspan="2">
            <fieldset class="separator">
              <legend>Gerais</legend>
              <table>
                <tr>
                  <td nowrap title="<?=$Tz01_c_pis?>">
                    <?=$Lz01_c_pis?>
                  </td>
                  <td nowrap title="<?=$Tz01_c_pis?>">
                    <?php
                    db_input( 'z01_c_pis', 10, $Iz01_c_pis, true, 'text', $db_opcao );
                    ?>
                  </td>
                  <td nowrap title="<?=$Tz01_v_uf?>">
                    <?=$Lz01_v_uf?>
                  </td>
                  <td nowrap title="<?=$Tz01_v_uf?>">
                    <?php
                    db_input( 'z01_v_uf', 5, $Iz01_v_uf, true, 'text', $db_opcao );
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=$Tz01_d_datapais?>">
                    <label class="bold">Data Entrada:</label>
                  </td>
                  <td nowrap title="<?=$Tz01_d_datapais?>">
                    <?php
                    db_inputdata( 'z01_d_datapais', @$z01_d_datapais_dia, @$z01_d_datapais_mes, @$z01_d_datapais_ano, true, 'text', $db_opcao );
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=$Tz01_c_escolaridade?>">
                    <?=$Lz01_c_escolaridade?>
                  </td>
                  <td nowrap colspan="3" title="<?=$Tz01_c_escolaridade?>">
                    <select id="z01_c_escolaridade" name="z01_i_escolaridade">
                      <option value="">Selecione</option>
                      <option value="51">Creche</option>
                      <option value="52">Pré-escola (exceto CA)</option>
                      <option value="53">Classe Alfabetizada - CA</option>
                      <option value="54">Ensino Fundamental 1ª a 4ª séries</option>
                      <option value="55">Ensino Fundamental 5ª a 8ª séries</option>
                      <option value="56">Ensino Fundamental Completo</option>
                      <option value="61">Ensino Fundamental Especial</option>
                      <option value="58">Ensino Fundamental EJA - séries iniciais (Supletivo 1ª a 4ª)</option>
                      <option value="59">Ensino Fundamental EJA - séries iniciais (Supletivo 5ª a 8ª)</option>
                      <option value="60">Ensino Médio, Médio 2º Ciclo (Científico, Técnico e etc)</option>
                      <option value="57">Ensino Médio Especial</option>
                      <option value="62">Ensino Médio EJA (Supletivo)</option>
                      <option value="63">Superior, Aperfeiçoamento, Especialização, Mestrado, Doutorado</option>
                      <option value="64">Alfabetização para Adultos (Mobral, etc)</option>
                      <option value="65">Nenhum</option>
                    </select>
                    <?php if(isset($GLOBALS['z01_i_escolaridade'])): ?>
                    <script>
                      document.form1.z01_i_escolaridade.value ='<?=$GLOBALS['z01_i_escolaridade']?>';
                    </script>
                    <?php endif; ?>
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
        </tr>
        <tr>
          <td>
            <fieldset class="separator">
              <legend>Identidade</legend>
              <table>
                <tr>
                  <td nowrap width=60% title="<?=@$Tz01_v_ident?>">
                    <?=@$Lz01_v_ident?>
                  </td>
                  <td nowrap title="<?=@$Tz01_v_ident?>">
                    <?php
                    db_input( 'z01_v_ident', 15, $Iz01_v_ident, true, 'text', $db_opcao );
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Tz01_d_dtemissao?>">
                    <?=@$Lz01_d_dtemissao?>
                  </td>
                  <td nowrap title="<?=@$Tz01_d_dtemissao?>">
                    <?php
                    db_inputdata(
                      'z01_d_dtemissao',
                      @$z01_d_dtemissao_dia,
                      @$z01_d_dtemissao_mes,
                      @$z01_d_dtemissao_ano,
                      true,
                      'text',
                      $db_opcao
                    );
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Tsd51_v_descricao?>">
                    <label class="bold">Órgão Emissor:</label>
                  </td>
                  <td nowrap title="<?=@$Tsd51_v_descricao?>">
                    <?php
                    db_input( 'sd51_v_descricao', 15, @$Isd51_v_descricao, true, 'text', $db_opcao );
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Tz01_c_ufident?>">
                    <?=@$Lz01_c_ufident?>
                  </td>
                  <td nowrap title="<?=@$Tz01_c_ufident?>">
                    <?php
                    db_input( 'z01_c_ufident', 15, $Iz01_c_ufident, true, 'text', $db_opcao );
                    ?>
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
          <td>
            <fieldset class="separator">
              <legend>CTPS</legend>
              <table>
                <tr>
                  <td nowrap title="<?=@$Tz01_c_numctps?>">
                    <?=@$Lz01_c_numctps?>
                  </td>
                  <td nowrap title="<?=@$Tz01_c_numctps?>">
                    <?php
                    db_input( 'z01_c_numctps', 15, $Iz01_c_numctps, true, 'text', $db_opcao );
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title=<?=@$Tz01_c_seriectps?>>
                    <?=@$Lz01_c_seriectps?>
                  </td>
                  <td nowrap title="<?=@$Tz01_c_seriectps?>">
                    <?php
                    db_input( 'z01_c_seriectps', 15, $Iz01_c_seriectps, true, 'text', $db_opcao );
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Tz01_d_dtemissaoctps?>">
                    <?=@$Lz01_d_dtemissaoctps?>
                  </td>
                  <td nowrap title="<?=@$Tz01_d_dtemissaoctps?>">
                    <?php
                    db_inputdata(
                      'z01_d_dtemissaoctps',
                      @$z01_d_dtemissaoctps_dia,
                      @$z01_d_dtemissaoctps_mes,
                      @$z01_d_dtemissaoctps_ano,
                      true,
                      'text',
                      $db_opcao
                    );
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Tz01_c_ufctps?>">
                    <?=@$Lz01_c_ufctps?>
                  </td>
                  <td nowrap title="<?=@$Tz01_c_ufctps?>">
                    <?php
                    db_input( 'z01_c_ufctps', 15, $Iz01_c_ufctps, true, 'text', $db_opcao );
                    ?>
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
        </tr>
        <!-- LINHA DOS FIELDSET'S CERTIDÃO E CNH -->
        <tr>
          <td colspan="2">
            <fieldset class="separator">
              <legend>Certidão</legend>
              <table style="float:left; display:block; width:51%;">

                <tr>
                  <td nowrap title="<?=@$Tz01_c_certidaotipo?>">
                    <?=@$Lz01_c_certidaotipo?>
                  </td>
                  <td nowrap title="<?=@$Tz01_c_certidaotipo?>">
                    <?php
                    db_input( 'z01_c_certidaotipo', 15, $Iz01_c_certidaotipo, true, 'text', $db_opcao );
                    ?>
                  </td>
                </tr>
                <tr>

                  <td nowrap title="<?=@$Tz01_c_certidaolivro?>">
                    <?=@$Lz01_c_certidaolivro?>
                  </td>
                  <td nowrap title="<?=@$Tz01_c_certidaolivro?>">
                    <?php
                    db_input( 'z01_c_certidaolivro', 15, $Iz01_c_certidaolivro, true, 'text', $db_opcao );
                    ?>
                  </td>
                </tr>
                <tr>

                  <td nowrap title="<?=@$Tz01_c_certidaotermo?>">
                    <label class="bold">Termo:</label>
                  </td>
                  <td nowrap title="<?=@$Tz01_c_certidaotermo?>">
                    <?php
                    db_input( 'z01_c_certidaotermo', 15, @$Iz01_c_certidaotermo, true, 'text', $db_opcao );
                    ?>
                  </td>
                 
                </tr>
                </table>

              <table style="float:left; display:block; width:47%;">

                <tr>
                   <td nowrap title="<?=@$Tz01_c_certidaocart?>">
                    <?=@$Lz01_c_certidaocart?>
                  </td>
                  <td nowrap title="<?=@$Tz01_c_certidaocart?>">
                    <?php
                    db_input( 'z01_c_certidaocart', 15, $Iz01_c_certidaocart, true, 'text', $db_opcao );
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Tz01_c_certidaofolha?>">
                    <?=@$Lz01_c_certidaofolha?>
                  </td>
                  <td nowrap title="<?=@$Tz01_c_certidaofolha?>">
                    <?php
                    db_input( 'z01_c_certidaofolha', 15, $Iz01_c_certidaofolha, true, 'text', $db_opcao );
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Tz01_c_certidaodata?>">
                    <?=@$Lz01_c_certidaodata?>
                  </td>
                  <td nowrap title="<?=@$Tz01_c_certidaodata?>">
                    <?php
                    db_inputdata(
                                  'z01_c_certidaodata',
                                  @$z01_c_certidaodata_dia,
                                  @$z01_c_certidaodata_mes,
                                  @$z01_c_certidaodata_ano,
                                  true,
                                  'text',
                                  $db_opcao
                                );
                    ?>
                  </td>
                </tr>
              </table>

            </fieldset>
          </td>
        </tr>
        <tr>
          <td>
            <fieldset class="separator">
              <legend>CNH</legend>
              <table>
                <tr>
                  <td nowrap title="<?=@$Tz01_v_cnh?>">
                    <?=@$Lz01_v_cnh?>
                  </td>
                  <td nowrap title="<?=@$Tz01_v_cnh?>">
                    <?php
                    db_input( 'z01_v_cnh', 15, $Iz01_v_cnh, true, 'text', $db_opcao );
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>
                    <?=@$Lz01_v_categoria?>
                  </td>
                  <td>
                    <?php
                    $y = array(
                      ""   => "",
                      "A"  => "A",
                      "B"  => "B",
                      "C"  => "C",
                      "D"  => "D",
                      "E"  => "E",
                      "AB" => "AB",
                      "AC" => "AC",
                      "AD" => "AD",
                      "AE" => "AE"
                    );
                    db_select( 'z01_v_categoria', $y, true, $db_opcao );
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Tz01_d_dtemissaocnh?>">
                    <?=@$Lz01_d_dtemissao?>
                  </td>
                  <td nowrap title="<?=@$Tz01_d_dtemissaocnh?>">
                    <?php
                    db_inputdata(
                      'z01_d_dtemissaocnh',
                      @$z01_d_dtemissaocnh_dia,
                      @$z01_d_dtemissaocnh_mes,
                      @$z01_d_dtemissaocnh_ano,
                      true,
                      'text',
                      $db_opcao
                    );
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Tz01_d_dthabilitacao?>">
                    <?=@$Lz01_d_dthabilitacao?>
                  </td>
                  <td nowrap title="<?=@$Tz01_d_dthabilitacao?>">
                    <?php
                    db_inputdata(
                      'z01_d_dthabilitacao',
                      @$z01_d_dthabilitacao_dia,
                      @$z01_d_dthabilitacao_mes,
                      @$z01_d_dthabilitacao_ano,
                      true,
                      'text',
                      $db_opcao
                    );
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Tz01_d_dtvencimento?>">
                    <?=@$Lz01_d_dtvencimento?>
                  </td>
                  <td nowrap title="<?=@$Tz01_d_dtvencimento?>">
                    <?php
                    db_inputdata(
                      'z01_d_dtvencimento',
                      @$z01_d_dtvencimento_dia,
                      @$z01_d_dtvencimento_mes,
                      @$z01_d_dtvencimento_ano,
                      true,
                      'text',
                      $db_opcao
                    );
                    ?>
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
          <td>
            <fieldset class="separator" style="height: 125px;">
              <legend>Dados Bancários</legend>
              <table>
                <tr>
                  <td nowrap title="<?=@$Tz01_c_banco?>">
                    <?=@$Lz01_c_banco?>
                  </td>
                  <td nowrap colspan="3" title="<?=@$Tz01_c_banco?>">
                    <?php
                    db_input( 'z01_c_banco', 15, $Iz01_c_banco, true, 'text', $db_opcao );
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Tz01_c_agencia?>">
                    <?=@$Lz01_c_agencia?>
                  </td>
                  <td nowrap title="<?=@$Tz01_c_agencia?>">
                    <?php
                    db_input( 'z01_c_agencia', 15, $Iz01_c_agencia, true, 'text', $db_opcao );
                    ?>
                  </td>
                  <td nowrap title="<?=@$Tz01_c_conta?>">
                    <?=@$Lz01_c_conta?>
                  </td>
                  <td nowrap title="<?=@$Tz01_c_conta?>">
                    <?php
                    db_input( 'z01_c_conta', 15, $Iz01_c_conta, true, 'text', $db_opcao );
                    ?>
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
        </tr>
      </table>
    </fieldset>
    <?php
    if( !isset( $lReadOnly ) || !$lReadOnly ) {
    ?>
      <input name="<?=( $db_opcao == 1 ? "incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir" ) );?>"
             type="submit"
             id="db_opcao"
             value="<?=( $db_opcao == 1 ? "Incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir" ) );?>"
             <?=( $db_botao == false ? "disabled" : "" );?> >
    <?php
    }
    ?>

    <input name="z01_i_cgsund" type="hidden"  value="<?=$chavepesquisa?>">
    <input name="z01_v_nome"   type="hidden"  value="<?=$z01_v_nome?>">
    <?php
    if( isset( $retornacgs ) ){
      echo "<input name='fechar' type='submit' value='Fechar''";
    }
    ?>
  </div>
</form>
<script>
function js_ruas() {

  js_OpenJanelaIframe(
                       '',
                       'db_iframe_ruas',
                       'func_ruas.php?rural=1&funcao_js=parent.js_preenchepesquisaruas|j14_codigo|j14_nome',
                       'Pesquisa',
                       true
                     );
}

function js_preenchepesquisaruas( chave, chave1 ) {

  document.form1.z01_v_ender.value = chave1;
  db_iframe_ruas.hide();
}

function js_bairro() {

  js_OpenJanelaIframe(
                       '',
                       'db_iframe_bairro',
                       'func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro|j13_codi|j13_descr',
                       'Pesquisa',
                       true
                     );
}

function js_preenchebairro( chave, chave1 ) {

  document.form1.j13_codi.value     = chave;
  document.form1.z01_v_bairro.value = chave1;
  db_iframe_bairro.hide();
}

function js_ruas1() {

  js_OpenJanelaIframe(
                       '',
                       'db_iframe_ruas1',
                       'func_ruas.php?rural=1&funcao_js=parent.js_preenchepesquisaruas1|j14_codigo|j14_nome',
                       'Pesquisa',
                       true
                     );
}

function js_preenchepesquisaruas1( chave, chave1 ) {

  document.form1.z01_v_endcon.value = chave1;
  db_iframe_ruas1.hide();
}

function js_bairro1() {

  js_OpenJanelaIframe(
                       '',
                       'db_iframe_bairro1',
                       'func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro1|j13_codi|j13_descr',
                       'Pesquisa',
                       true
                     );
}

function js_preenchebairro1( chave, chave1 ) {

  document.form1.z01_v_baicon.value = chave1;
  db_iframe_bairro1.hide();
}

function js_pesquisa() {

  js_OpenJanelaIframe(
                       '',
                       'db_iframe_cgs_und',
                       'func_cgs_und.php?funcao_js=parent.js_preenchepesquisa|z01_i_cgsund',
                       'Pesquisa CGS',
                       true
                     );
}

function js_preenchepesquisa( chave ) {

  db_iframe_cgs_und.hide();
  <?echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";?>
}

function js_novo() {
  parent.location="sau1_cgs_und000.php?id=1";
}

$('z01_c_pis').className           = 'field-size2';
$('z01_v_uf').className            = 'field-size2';
$('z01_d_datapais').className      = 'field-size2';
$('z01_c_escolaridade').className  = 'field-size-max';

$('z01_c_certidaotipo').className  = 'field-size4';
$('z01_c_certidaolivro').className = 'field-size4';
$('z01_c_certidaotermo').className = 'field-size4';
$('z01_c_certidaocart').className  = 'field-size5';
$('z01_c_certidaofolha').className = 'field-size5';
$('z01_c_certidaodata').className  = 'field-size2';

$('z01_c_banco').className   = 'field-size-max';
$('z01_c_agencia').className = 'field-size2';
$('z01_c_conta').className   = 'field-size2';

$('z01_v_ident').className      = 'field-size4';
$('z01_d_dtemissao').className  = 'field-size2';
$('sd51_v_descricao').className = 'field-size4';
$('z01_c_ufident').className    = 'field-size2';

$('z01_v_cnh').className           = 'field-size4';
$('z01_v_categoria').className     = 'field-size-max';
$('z01_d_dtemissaocnh').className  = 'field-size2';
$('z01_d_dthabilitacao').className = 'field-size2';
$('z01_d_dtvencimento').className  = 'field-size2';

$('z01_c_numctps').className       = 'field-size4';
$('z01_c_seriectps').className     = 'field-size4';
$('z01_d_dtemissaoctps').className = 'field-size2';
$('z01_c_ufctps').className        = 'field-size4';
</script>