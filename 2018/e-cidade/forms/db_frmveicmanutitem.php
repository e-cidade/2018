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
//MODULO: veiculos

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clveicmanutitem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ve62_codigo");
$clrotulo->label("ve62_vlrmobra");
$clrotulo->label("ve62_vlrpecas");
$clrotulo->label("pc01_descrmater");
$clrotulo->label("ve64_pcmater");

if(isset($db_opcaoal)){
   $db_opcao=33;
    $db_botao=false;
}else if(isset($opcao) && $opcao=="alterar"){
    $db_botao=true;
    $db_opcao = 2;
}else if(isset($opcao) && $opcao=="excluir"){
    $db_opcao = 3;
    $db_botao=true;
}else{
    $db_opcao = 1;
    $db_botao=true;
    if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){
     $ve63_codigo = "";
     $ve63_descr = "";
     $ve63_quant = "";
     $ve63_vlruni = "";
     $ve64_pcmater= "";
     $pc01_descrmater="";
     $ve63_unidade = "";
     $m61_descr = "";
     $ve63_tipoitem = 1;
     $ve63_numeronota = "";
     $ve63_datanota = "";
     $ve63_datanota_dia = "";
     $ve63_datanota_mes = "";
     $ve63_datanota_ano = "";
     $ve63_proximatroca = "";
   }
}
?>
<div class="container">
  <form name="form1" method="post" action="" accept-charset="ISO-8859-1">
    <fieldset>
      <legend>Itens da Manutenção</legend>
    <table>
      <tr>
        <td nowrap title="<?php echo $Tve63_veicmanut; ?>">
          <label class="bold" for="ve63_veicmanut"><?php echo $Lve63_veicmanut; ?></label>
        </td>
        <td>
          <?php
            db_input('numero_manutencao', 10, 1, true, 'text', 3);
            db_input('ve63_veicmanut', 10, 1, true, 'hidden', 3);
            db_input('ve63_codigo', 10, 1, true, 'hidden', 3);
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tve62_vlrmobra?>">
           <?=@$Lve62_vlrmobra?>
        </td>
        <td>
          <?php db_input('valor_mao_obra',15,$Ive62_vlrmobra,true,'text',3,""); ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tve62_vlrpecas?>">
           <?=@$Lve62_vlrpecas?>
        </td>
        <td>
          <?php db_input('valor_pecas',15,$Ive62_vlrpecas,true,'text',3,""); ?>
        </td>
      </tr>
      <tr>
        <td nowrap>
          <label class="bold" for="valor_lavagem">Valor de Lavagem:</label>
        </td>
        <td>
          <?php db_input('valor_lavagem', 15, 1, true, 'text', 3); ?>
        </td>
      </tr>
      <tr>
        <td nowrap>
          <label class="bold" for="desconto">% de Desconto:</label>
        </td>
        <td>
          <?php
            $Sdesconto = "% de Desconto";
            db_input('desconto', 15, 4, true, 'text', ($lBloquearDesconto ? 3 : $db_opcao), "");
          ?>
        </td>
      </tr>
    </table>
    <fieldset class="separator">
      <legend>Dados do Item</legend>
      <table>
        <tr>
          <td nowrap title="<?php echo $Tve63_descr; ?>">
            <label class="bold" for="ve63_descr"><?php echo $Lve63_descr; ?></label>
          </td>
          <td>
            <?php db_input('ve63_descr',40,$Ive63_descr,true,'text',$db_opcao,""); ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Tve63_quant; ?>">
            <label class="bold" for="ve63_quant"><?php echo $Lve63_quant; ?></label>
          </td>
          <td>
            <?php db_input('ve63_quant',10,$Ive63_quant,true,'text',$db_opcao,""); ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Tve63_vlruni; ?>">
            <label class="bold" for="ve63_vlruni"><?php echo $Lve63_vlruni; ?></label>
          </td>
          <td>
            <?php db_input('ve63_vlruni',10,$Ive63_vlruni,true,'text',$db_opcao,""); ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Tve63_numeronota; ?>">
            <label class="bold" for="ve63_numeronota"><?php echo $Lve63_numeronota; ?></label>
          </td>
          <td>
            <?php db_input('ve63_numeronota',10,$Ive63_numeronota,true,'text',$db_opcao,""); ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Tve63_datanota; ?>">
            <label class="bold" for="ve63_datanota"><?php echo $Lve63_datanota; ?></label>
          </td>
          <td>
            <?php $ve63_datanota_dia = !empty($ve63_datanota_dia) ? $ve63_datanota_dia : null ?>
            <?php $ve63_datanota_mes = !empty($ve63_datanota_mes) ? $ve63_datanota_mes : null ?>
            <?php $ve63_datanota_ano = !empty($ve63_datanota_ano) ? $ve63_datanota_ano : null ?>
            <?php db_inputdata('ve63_datanota', $ve63_datanota_dia, $ve63_datanota_mes, $ve63_datanota_ano, true, 'text', $db_opcao, "") ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Tve63_proximatroca; ?>">
            <label class="bold" for="ve63_proximatroca"><?php echo $Lve63_proximatroca; ?></label>
          </td>
          <td>
            <?php db_input('ve63_proximatroca',10,$Ive63_proximatroca,true,'text',$db_opcao,""); ?>
          </td>
        </tr>
        <tr>
          <td title="<?php echo $Tve63_tipoitem; ?>">
            <label class="bold" for="ve63_tipoitem"><?php echo $Lve63_tipoitem; ?></label>
          </td>
          <td>
            <?php
              $aTipos = array(
                  VeiculoManutencaoItem::TIPO_SERVICO_PECA => "Peça",
                  VeiculoManutencaoItem::TIPO_SERVICO_MAO_DE_OBRA => "Mão de Obra",
                  VeiculoManutencaoItem::TIPO_SERVICO_LAVAGEM => "Lavagem"
                );

              db_select("ve63_tipoitem", $aTipos, true, $db_opcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Tve64_pcmater; ?>">
            <?php db_ancora(@$Lve64_pcmater,"js_pesquisave64_pcmater(true);",$db_opcao); ?>
          </td>
          <td>
            <?php
              db_input('ve64_pcmater',10,$Ive64_pcmater,true,'text',$db_opcao," onchange='js_pesquisave64_pcmater(false);'");
              db_input('pc01_descrmater',40,$Ipc01_descrmater,true,'text',3,'');
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Tve63_unidade; ?>">
            <?php db_ancora($Lve63_unidade,"js_pesquisave63_unidade(true);", $db_opcao); ?>
          </td>
          <td>
            <?php
              db_input('ve63_unidade',10,$Ive63_unidade,true,'text', $db_opcao, " onchange='js_pesquisave63_unidade(false);'");
              db_input('m61_descr',40,$Ipc01_descrmater,true,'text',3,'');
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    </fieldset>
    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> />
    <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='display:none;'":"")?> />
    <input name="imprimir" type="button" id="imprimir" value="Imprimir" />
    <div style="margin-top: 15px;">
      <?php

        $chavepri = array("ve63_codigo" => (!empty($ve63_codigo) ? $ve63_codigo : ''));

        $cliframe_alterar_excluir->chavepri      = $chavepri;
        $cliframe_alterar_excluir->sql           = $clveicmanutitem->sql_query( null,
                                                                                        "ve63_codigo, ve63_descr, ve63_quant, ve63_vlruni, ve63_valortotalcomdesconto, ve63_numeronota, to_char(ve63_datanota, 'DD/MM/YYYY') as ve63_datanota, ve63_proximatroca,"
                                                                                        . "case ve63_tipoitem when " . VeiculoManutencaoItem::TIPO_SERVICO_PECA . " then 'Peça' "
                                                                                        . "when " . VeiculoManutencaoItem::TIPO_SERVICO_MAO_DE_OBRA . " then 'Mão de Obra' "
                                                                                        . "when " . VeiculoManutencaoItem::TIPO_SERVICO_LAVAGEM . " then 'Lavagem' end as ve63_tipoitem",
                                                                                        null,
                                                                                        "ve63_veicmanut = {$ve63_veicmanut}");
        $cliframe_alterar_excluir->campos        = "ve63_descr, ve63_quant, ve63_vlruni, ve63_valortotalcomdesconto, ve63_tipoitem, ve63_numeronota, ve63_datanota, ve63_proximatroca";
        $cliframe_alterar_excluir->legenda       = "Itens Cadastrados";
        $cliframe_alterar_excluir->iframe_height = "200";
        $cliframe_alterar_excluir->iframe_width  = "700";
        $cliframe_alterar_excluir->strFormatar   = ""; // Nao formatar valores do Iframe Altera/Exclui pois Quantidade podera ter mais de 2 casas decimais
        $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
      ?>
    </div>
  </form>
</div>
<script type="text/javascript">

  $('imprimir').observe('click', function() {

    var sUrl = "vei4_manutencaoordemservico002.php?iCodigoManutencao=" + $F('ve63_veicmanut');

    jan = window.open(sUrl,'','width=' + (screen.availWidth - 5 ) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  });

  function js_pesquisave63_unidade(lMostra) {

    var sFuncao = "func_matunid.php?";

    if (lMostra) {
      sFuncao += "funcao_js=parent.js_mostramatunid|m61_codmatunid|m61_descr";
    } else {

      var iUnidade = $F('ve63_unidade');

      if (empty(iUnidade)) {
        $('m61_descr').value = '';
        return false;
      }

      sFuncao += "funcao_js=parent.js_mostramatunid1&pesquisa_chave=" + iUnidade;
    }

    js_OpenJanelaIframe( "top.corpo.iframe_veicmanutitem",
                         "db_iframe_matunid",
                         sFuncao,
                         "Pesquisa de Unidade",
                         lMostra );
  }

  function js_mostramatunid(iCodigo, sDescricao) {

    db_iframe_matunid.hide();

    $('ve63_unidade').value = iCodigo;
    $('m61_descr').value = sDescricao;
  }

  function js_mostramatunid1(sDescricao, lErro) {

    $('m61_descr').value = sDescricao;

    if (lErro) {
      $('ve63_unidade').value = '';
    }
  }

function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
function js_mostraveicmanut(chave,erro){
  document.form1.ve62_codigo.value = chave;
  if(erro==true){
    document.form1.ve63_veicmanut.focus();
    document.form1.ve63_veicmanut.value = '';
  }
}
function js_mostraveicmanut1(chave1,chave2){
  document.form1.ve63_veicmanut.value = chave1;
  document.form1.ve62_codigo.value = chave2;
  db_iframe_veicmanut.hide();
}
function js_pesquisave64_pcmater(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_veicmanutitem','db_iframe_pcmater','func_pcmater.php?funcao_js=parent.js_mostrapcmater1|pc01_codmater|pc01_descrmater','Pesquisa',true,'0');
  }else{
     if(document.form1.ve64_pcmater.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_veicmanutitem','db_iframe_pcmater','func_pcmater.php?pesquisa_chave='+document.form1.ve64_pcmater.value+'&funcao_js=parent.js_mostrapcmater','Pesquisa',false);
     }else{
       document.form1.pc01_descrmater.value = '';
     }
  }
}
function js_mostrapcmater(chave,erro){
  document.form1.pc01_descrmater.value = chave;
  if(erro==true){
    document.form1.ve64_pcmater.focus();
    document.form1.ve64_pcmater.value = '';
  }
}
function js_mostrapcmater1(chave1,chave2){
  document.form1.ve64_pcmater.value = chave1;
  document.form1.pc01_descrmater.value = chave2;
  db_iframe_pcmater.hide();
}
</script>
