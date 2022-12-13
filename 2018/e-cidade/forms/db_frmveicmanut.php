<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
$clveicmanut->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ve28_descr");
$clrotulo->label("ve01_placa");
$clrotulo->label("ve65_veicretirada");
$clrotulo->label("ve60_codigo");
$clrotulo->label("z01_nome");
$clrotulo->label("ve66_veiccadoficinas");
$clrotulo->label("ve07_sigla");

if ($db_opcao == 1) {
  $db_action = "vei1_veicmanut004.php";
} else if ($db_opcao == 2 || $db_opcao == 22) {
  $db_action = "vei1_veicmanut005.php";
} else if ($db_opcao == 3 || $db_opcao == 33) {
  $db_action = "vei1_veicmanut006.php";
}

$sHora = db_hora();

$sPessoal = "false";

$clveicparam = new cl_veicparam();
$sSqlParametros = $clveicparam->sql_query_file( null,
                                                "ve50_integrapessoal",
                                                null,
                                                "ve50_instit = " . db_getsession("DB_instit") );
$rsParametros = $clveicparam->sql_record($sSqlParametros);

if ($clveicparam->numrows > 0) {
  $pessoal = (db_utils::fieldsMemory($rsParametros, 0)->ve50_integrapessoal == 1) ? "true" : "false";
}

?>
<div class="container">
  <form name="form1" method="post" action="<?=$db_action?>">
    <fieldset>
      <legend>Dados da Manutenção</legend>
      <table>
        <tr>
          <td nowrap title="<?php echo $Tve62_numero; ?>">
            <label class="bold" for="ve62_numero"><?php echo $Lve62_numero; ?></label>
          </td>
          <td>
            <?php
              if (!empty($ve62_numero)) {
                $numero_ano = $ve62_numero . "/" . $ve62_anousu;
              }

              db_input('numero_ano', 10, $Ive62_numero, true, 'text', 3, "");
              db_input('ve62_codigo',10,$Ive62_codigo,true,'hidden',3,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tve62_veiculos?>">
             <?
             db_ancora(@$Lve62_veiculos,"js_pesquisave62_veiculos(true);",$db_opcao);
             ?>
          </td>
          <td>
            <?
            db_input('ve62_veiculos',10,$Ive62_veiculos,true,'text',$db_opcao,
                     " onchange='js_pesquisave62_veiculos(false);'");
            db_input('ve01_placa',10,$Ive01_placa,true,'text',3,'')
             ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?=@$Tve62_dtmanut?>">
             <?=@$Lve62_dtmanut?>
          </td>
          <td>
          <?
          db_inputdata('ve62_dtmanut',@$ve62_dtmanut_dia,@$ve62_dtmanut_mes,@$ve62_dtmanut_ano,
                       true,'text',$db_opcao,
                       "onchange='js_pesquisa_medida();'","","","none","","", "js_pesquisa_medida();")
          ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Tve62_hora; ?>">
             <?php echo $Lve62_hora; ?>
          </td>
          <td>
            <?php db_input('ve62_hora', 5, $Ive62_hora, true, 'text', $db_opcao); ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tve62_descr?>">
             <?=@$Lve62_descr?>
          </td>
          <td>
            <?
            db_input('ve62_descr',60,$Ive62_descr,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tve62_notafisc?>">
             <?=@$Lve62_notafisc?>
          </td>
          <td>
          <?
          db_input('ve62_notafisc',10,$Ive62_notafisc,true,'text',$db_opcao,"")
          ?>
          </td>
        </tr>
        <tr>
         <td nowrap title="Última Medida"><b>Última Medida:</b></td>
          <td>
           <?
           $ultimamedida = 0;
           if (isset($ve62_veiculos) && $ve62_veiculos != "" && !empty($ve62_dtmanut)) {

             $dData        = substr($ve62_dtmanut, 6, 4) . '-' . substr($ve62_dtmanut, 3, 2) . '-' . substr($ve62_dtmanut, 0, 2);
             $oVeiculo     = new Veiculo($ve62_veiculos);
             $ultimamedida = $oVeiculo->getUltimaMedidaUso($dData);
           }
           db_input("ultimamedida",15,0,true,"text",3);
           if (isset($ve07_sigla) && trim($ve07_sigla) != ""){
             echo " ".db_input("ve07_sigla",3,0,true,"text",3);
           }
           ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tve62_medida?>">
             <?=@$Lve62_medida?>
          </td>
          <td>
            <?
              db_input('ve62_medida',15,$Ive62_medida,true,'text',$db_opcao,"");
              db_input("ve07_sigla",3,0,true,"text",3);
            ?>
          </td>
        </tr>
        <tr id='tr_proximamedida' style="display:none">
          <td nowrap title="Próxima Medida"><b>Próxima Medida:</b></td>
          <td>
            <?php
              if (isset($ve62_veiculos)) {

                $Queryproximamedida = $clveiculos->sql_record($clveiculos->sql_query_proximamedida($ve62_veiculos, $dData, $sHora));
                if($clveiculos->numrows > 0){
                 db_fieldsmemory($Queryproximamedida,0);
                } else {
                  $proximamedida = 0;
                }
              }
              db_input("proximamedida",15,0,true,"text",3);
              if (isset($ve07_sigla) && trim($ve07_sigla) != ""){
               echo " ".db_input("ve07_sigla",3,0,true,"text",3);
              }
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tve62_veiccadtiposervico?>">
             <?
             db_ancora(@$Lve62_veiccadtiposervico,"js_pesquisave62_veiccadtiposervico(true);",$db_opcao);
             ?>
          </td>
          <td>
            <?
            db_input('ve62_veiccadtiposervico',10,$Ive62_veiccadtiposervico,true,'text',$db_opcao,
            " onchange='js_pesquisave62_veiccadtiposervico(false);'");
            db_input('ve28_descr',40,$Ive28_descr,true,'text',3,'');
             ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tve66_veiccadoficinas?>">
             <?
             db_ancora(@$Lve66_veiccadoficinas,"js_pesquisave66_veiccadoficinas(true);",$db_opcao);
             ?>
          </td>
          <td>
            <?
            db_input('ve66_veiccadoficinas',10,$Ive66_veiccadoficinas,true,'text',$db_opcao,
                     "onchange='js_pesquisave66_veiccadoficinas(false);'");
            db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
             ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tve65_veicretirada?>">
             <?
             db_ancora(@$Lve65_veicretirada,"js_pesquisave65_veicretirada(true);",$db_opcao);
             ?>
          </td>
          <td>
            <?php
              db_input('ve65_veicretirada',10,$Ive65_veicretirada,true,'text',$db_opcao,
                       " onchange='js_pesquisave65_veicretirada(false);'");
              db_input('ve60_codigo',10,$Ive60_codigo,true,'hidden',3,'');
            ?>
          </td>
        </tr>
        <tr id="campo-motorista">
          <td nowrap title="<?php echo $Tve62_veicmotoristas; ?>">
            <label class="bold" for="ve62_veicmotoristas"><?php db_ancora($Lve62_veicmotoristas, "js_pesquisave62_veicmotoristas(true);", $db_opcao); ?></label>
          </td>
          <td>
            <?php
              db_input('ve62_veicmotoristas', 10, $Ive62_veicmotoristas, true, 'text', $db_opcao, " onchange='js_pesquisave62_veicmotoristas(false);'");
              db_input('descricao_motorista', 40, 3, true, 'text', 3);
            ?>
          </td>
        </tr>
        <tr>
          <td title="<?php echo $Tve62_situacao; ?>">
            <label class="bold" for="ve62_situacao"><?php echo $Lve62_situacao; ?></label>
          </td>
          <td>
            <?php

              if (empty($ve62_situacao)) {
                $ve62_situacao = VeiculoManutencao::SITUACAO_REALIZADO;
              }

              $aSituacoes = array(
                  VeiculoManutencao::SITUACAO_PENDENTE => "Pendente",
                  VeiculoManutencao::SITUACAO_REALIZADO => "Realizado"
                );

              db_select("ve62_situacao", $aSituacoes, true, $db_opcao);
            ?>
          </td>
        </tr>
        <tr>
        	<td nowrap="nowrap" title="<?=@$Tve62_observacao?>" colspan="2">
        		<fieldset>
        			<legend><?=$Lve62_observacao?></legend>
        			<?php db_textarea('ve62_observacao', 4, 80, $Ive62_observacao, true, 'text', $db_opcao); ?>
        		</fieldset>
        	</td>
        </tr>
      </table>
    </fieldset>
    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
           type="submit" id="db_opcao"
           value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
           <?=($db_botao==false?"disabled":"")?> onclick="return js_valida();">
    <?php if ($db_opcao != 1) { ?>
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    <?php } ?>
  </form>
</div>
<script type="text/javascript">

/**
 * Formata e validar campo com hora
 *
 * @param Object elemento
 * @return void
 */
(function js_formatarHora(elemento) {

  var self = this;

  this.change = function() {

    if (this.value == '') {
      return;
    }

    while(this.value.length < 5) {

      if (this.value.substr(0, 2).length == 1 || this.value.substr(0, this.value.indexOf(':')).length == 1) {
        this.value = '0' + this.value;
      }

      if (this.value.length == 2) {
        this.value += ':';
      }

      if (this.value.length < 5) {
        this.value += '0';
      }
    }

    self.validar();
  }

  this.keyPres = function(event) {

    /**
     * 8  - backspace
     * 58 - :
     * 46 - del
     */
    var key = event.keyCode ? event.keyCode : event.charCode;

    if (key != 8 && key != 46) {

      if (key == 58 && this.value.length != 2) {
        return false;
      }

      if (key != 58 && this.value.length == 2) {
        this.value += ':';
      }
    }

    return js_mask(event, "0-9|:|0-9");
  }

  this.validar = function() {

    var iHoras = new Number(elemento.value.substr(0, 2));
    var iMinutos = new Number(elemento.value.substr(3, 5));

    try {

      if (elemento.value.indexOf(':') != 2) {
        throw 'Hora inválida.';
      }

      if (iHoras > 24) {
        throw 'Hora inválida.';
      }

      if (iHoras == 24 && iMinutos > 0) {
        throw 'Hora inválida.';
      }

      if (iMinutos >= 60) {
        throw 'Hora inválida.';
      }

    } catch (erro) {

      elemento.value = '';
      alert(erro);
    }
  }

  elemento.onkeypress = this.keyPres;
  elemento.onchange = this.change;

})(document.getElementById('ve62_hora'));

function js_verificamotorista() {

  $('campo-motorista').show();

  if (!empty($('ve60_codigo').value)) {
    $('campo-motorista').hide();
  }
}

js_verificamotorista();

function js_pesquisave62_veicmotoristas(lMostra) {

  var sFuncao = "func_veicmotoristasalt.php?pessoal=<?php echo $pessoal; ?>";

  if (lMostra) {
    sFuncao += "&funcao_js=parent.js_mostraveicmotoristas1|ve05_codigo|z01_nome";
  } else {

    var iCodigo = $F('ve62_veicmotoristas');

    if (empty(iCodigo)) {

      $('descricao_motorista').value = '';
      return false;
    }

    sFuncao += "&pesquisa_chave=" + iCodigo + "&funcao_js=parent.js_mostraveicmotoristas";
  }

  js_OpenJanelaIframe( 'top.corpo.iframe_veicmanut',
                       'db_iframe_veicmotoristas',
                       sFuncao,
                       'Pesquisa de Motorista',
                       lMostra );
}

function js_mostraveicmotoristas(sDescricao, lErro) {

  $('descricao_motorista').value = sDescricao;

  if (lErro) {
    $('ve62_veicmotoristas').value = '';
  }
}

function js_mostraveicmotoristas1(iCodigo, sDescricao) {

  db_iframe_veicmotoristas.hide();

  $('ve62_veicmotoristas').value = iCodigo;
  $('descricao_motorista').value = sDescricao;
}

function js_pesquisave65_veicretirada(mostra) {

  var iCodigoVeiculo = $F('ve62_veiculos');

  if (iCodigoVeiculo == '') {


    document.form1.ve60_codigo.value = '';
    document.form1.ve65_veicretirada.value = '';

    alert('Selecione um Veículo.');
    return;
  }

  if(mostra==true){
    js_OpenJanelaIframe( 'top.corpo.iframe_veicmanut',
                         'db_iframe_veicretirada',
                         'func_veicretirada.php?codigoveiculo=' + iCodigoVeiculo + '&funcao_js=parent.js_mostraveicretirada1|ve60_codigo|ve60_codigo',
                         'Pesquisa de Retirada',
                         true);
  }else{
     if(document.form1.ve65_veicretirada.value != ''){
        js_OpenJanelaIframe( 'top.corpo.iframe_veicmanut',
                             'db_iframe_veicretirada',
                             'func_veicretirada.php?codigoveiculo=' + iCodigoVeiculo + '&pesquisa_chave='+document.form1.ve65_veicretirada.value+'&funcao_js=parent.js_mostraveicretirada',
                             'Pesquisa de Retirada',
                             false);
     }else{
       document.form1.ve60_codigo.value = '';
     }
  }

  js_verificamotorista();
}
function js_mostraveicretirada(chave,erro){
  document.form1.ve60_codigo.value = chave;
  if(erro==true){
    document.form1.ve65_veicretirada.focus();
    document.form1.ve65_veicretirada.value = '';
  }

  js_verificamotorista();
}
function js_mostraveicretirada1(chave1,chave2){
  document.form1.ve65_veicretirada.value = chave1;
  document.form1.ve60_codigo.value = chave2;
  db_iframe_veicretirada.hide();

  js_verificamotorista();
}
function js_pesquisave66_veiccadoficinas(mostra){
  if(mostra==true){
    js_OpenJanelaIframe( 'top.corpo.iframe_veicmanut',
                         'db_iframe_veiccadoficinas',
                         'func_veiccadoficinasalt.php?funcao_js=parent.js_mostraveiccadoficinas1|ve27_codigo|z01_nome',
                         'Pesquisa de Oficina',
                         true);
  }else{
     if(document.form1.ve66_veiccadoficinas.value != ''){
        js_OpenJanelaIframe( 'top.corpo.iframe_veicmanut',
                             'db_iframe_veiccadoficinas',
                             'func_veiccadoficinasalt.php?pesquisa_chave='+document.form1.ve66_veiccadoficinas.value+'&funcao_js=parent.js_mostraveiccadoficinas',
                             'Pesquisa de Oficina',
                             false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostraveiccadoficinas(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.ve66_veiccadoficinas.focus();
    document.form1.ve66_veiccadoficinas.value = '';
  }
}
function js_mostraveiccadoficinas1(chave1,chave2){
  document.form1.ve66_veiccadoficinas.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_veiccadoficinas.hide();
}
function js_pesquisave62_veiccadtiposervico(mostra){
  if(mostra==true){
    js_OpenJanelaIframe( 'top.corpo.iframe_veicmanut',
                         'db_iframe_veiccadtiposervico',
                         'func_veiccadtiposervico.php?funcao_js=parent.js_mostraveiccadtiposervico1|ve28_codigo|ve28_descr',
                         'Pesquisa de Tipo de Serviço',
                         true,
                         '0');
  }else{
     if(document.form1.ve62_veiccadtiposervico.value != ''){
        js_OpenJanelaIframe( 'top.corpo.iframe_veicmanut',
                             'db_iframe_veiccadtiposervico',
                             'func_veiccadtiposervico.php?pesquisa_chave='+document.form1.ve62_veiccadtiposervico.value+'&funcao_js=parent.js_mostraveiccadtiposervico',
                             'Pesquisa de Tipo de Serviço',
                             false,
                             '0','1','775','390');
     }else{
       document.form1.ve28_descr.value = '';
     }
  }
}
function js_mostraveiccadtiposervico(chave,erro){
  document.form1.ve28_descr.value = chave;
  if(erro==true){
    document.form1.ve62_veiccadtiposervico.focus();
    document.form1.ve62_veiccadtiposervico.value = '';
  }
}
function js_mostraveiccadtiposervico1(chave1,chave2){
  document.form1.ve62_veiccadtiposervico.value = chave1;
  document.form1.ve28_descr.value = chave2;
  db_iframe_veiccadtiposervico.hide();
}
function js_pesquisave62_veiculos(mostra){
  if(mostra==true){
    js_OpenJanelaIframe( 'top.corpo.iframe_veicmanut',
                         'db_iframe_veiculos',
                         'func_veiculosalt.php?funcao_js=parent.js_mostraveiculos1|ve01_codigo|ve01_placa',
                         'Pesquisa de Veículo',
                         true,
                         '0');
  }else{
     if(document.form1.ve62_veiculos.value != ''){
        js_OpenJanelaIframe( 'top.corpo.iframe_veicmanut',
                             'db_iframe_veiculos',
                             'func_veiculosalt.php?pesquisa_chave='+document.form1.ve62_veiculos.value+'&funcao_js=parent.js_mostraveiculos',
                             'Pesquisa de Veículo',
                             false,
                             '0');
     }else{
       document.form1.ve01_placa.value = '';
       document.form1.ve60_codigo.value = '';
       document.form1.ve65_veicretirada.value = '';

       limpaMedida();
     }
  }
}

function limpaMedida() {

  $('ve62_dtmanut').value = '';
  $('ve62_hora').value = '';
  $('ultimamedida').value = '';
}

function js_mostraveictipoabast(chave,erro){

  limpaMedida();

  document.form1.ve07_sigla.value = chave;
  if(erro==true){
    document.form1.ve07_sigla.value = '';
  }
}
function js_mostraveiculos(chave,erro){

  limpaMedida();

  document.form1.ve60_codigo.value = '';
  document.form1.ve65_veicretirada.value = '';
  document.form1.ve01_placa.value = chave;
  if(erro==true){
    document.form1.ve62_veiculos.focus();
    document.form1.ve62_veiculos.value = '';
  } else {
    js_OpenJanelaIframe('top.corpo.iframe_veicmanut','db_iframe_veiculos','func_veiculos.php?sigla=true&pesquisa_chave='+document.form1.ve62_veiculos.value+'&funcao_js=parent.js_mostraveictipoabast','Pesquisa de Veículo',false);
  }
}
function js_mostraveiculos1(chave1,chave2){

  document.form1.ve60_codigo.value = '';
  document.form1.ve65_veicretirada.value = '';
  document.form1.ve62_veiculos.value = chave1;
  document.form1.ve01_placa.value = chave2;
  js_OpenJanelaIframe('top.corpo.iframe_veicmanut','db_iframe_veiculos','func_veiculos.php?sigla=true&pesquisa_chave='+document.form1.ve62_veiculos.value+'&funcao_js=parent.js_mostraveictipoabast','Pesquisa de Veículo',false,'0');
  db_iframe_veiculos.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_veicmanut','db_iframe_veicmanut','func_veicmanut.php?funcao_js=parent.js_preenchepesquisa|ve62_codigo','Pesquisa de Manutenção',true,'0');
}

function js_preenchepesquisa(chave){
  db_iframe_veicmanut.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function validaCampo(oCampo, sMensagem) {

  oCampo.style.backgroundColor = '';

  if (empty(oCampo.value)) {
    alert(sMensagem);
    oCampo.style.backgroundColor = '#99A9AE';
    oCampo.value = '';
    oCampo.focus();
    return false;
  }

  return true;
}

function js_valida(){

  var medidamanut = new Number(document.form1.ve62_medida.value);
  var ultimamanut = new Number(document.form1.ultimamedida.value);
  var proxima     = new Number(document.form1.proximamedida.value);

  var oServicoExecutado = $('ve62_descr');
  var oHora             = $('ve62_hora');

 <? if ($db_opcao !=3 ) { ?>
  document.form1.ve62_medida.style.backgroundColor = '';

  if (!validaCampo(oServicoExecutado, 'Campo Serviço Executado é de preenchimento obrigatório.')) {
    return false;
  }

  if (!validaCampo(oHora, 'Campo Hora da Inclusão/Alteração é de preenchimento obrigatório.')) {
    return false;
  }

  if (empty(medidamanut.toString())) {
    alert ("Campo Medida é de preenchimento obrigatório.");
    document.form1.ve62_medida.style.backgroundColor='#99A9AE';
    document.form1.ve62_medida.value='';
    document.form1.ve62_medida.focus();
    return false;
  }

  if(ultimamanut > medidamanut){

    alert ("O valor do campo Medida não pode ser menor que o valor do campo Última Medida.");
    document.form1.ve62_medida.style.backgroundColor='#99A9AE';
    document.form1.ve62_medida.value='';
    document.form1.ve62_medida.focus();
    return false;
  }

  if(proxima > 0) {

    if(proxima < medidamanut){

      alert ("Valor da medida maior que o valor da próxima medida.");
      document.form1.ve62_medida.style.backgroundColor='#99A9AE';
      document.form1.ve62_medida.value='';
      document.form1.ve62_medida.focus();
      return false;
    }
  }

 <? } ?>

  return true;
}

function js_pesquisa_medida() {
  var databanco = document.form1.ve62_dtmanut_ano.value + '-' +
                  document.form1.ve62_dtmanut_mes.value + '-' +
                  document.form1.ve62_dtmanut_dia.value;
  var manutencao = document.form1.ve62_codigo.value;
  js_OpenJanelaIframe('top.corpo.iframe_veicmanut', 'db_iframe_ultimamedida',
    'func_veiculos_medida.php?metodo=ultimamedida&veiculo='+document.form1.ve62_veiculos.value+
                                                '&data='+databanco+
                                                '&manutencao='+manutencao+
                                                '&funcao_js=parent.js_mostraultimamedida', 'Pesquisa Última Medida', false);

  js_OpenJanelaIframe('top.corpo.iframe_veicmanut', 'db_iframe_proximamedida',
    'func_veiculos_medida.php?metodo=proximamedida&veiculo='+document.form1.ve62_veiculos.value+
                                                '&data='+databanco+
                                                '&manutencao='+manutencao+
                                                '&funcao_js=parent.js_mostraproximamedida', 'Pesquisa Proxima Medida', false);
  return true;
}

function js_mostraultimamedida(ultimamedida,outro) {
  document.form1.ultimamedida.value = ultimamedida;
  return true;
}

function js_mostraproximamedida(proximamedida,outro) {
  document.form1.proximamedida.value = proximamedida;

  if(proximamedida != '0') {
    document.getElementById('tr_proximamedida').style.display = '';
  } else {
    document.getElementById('tr_proximamedida').style.display = 'none';
  }

  return true;
}
</script>