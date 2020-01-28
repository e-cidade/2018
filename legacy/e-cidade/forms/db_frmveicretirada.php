<?
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
$clveicretirada->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ve01_codigo");
$clrotulo->label("ve01_placa");
$clrotulo->label("z01_nome");
$clrotulo->label("descrdepto");
$clrotulo->label("nome");

if (!isset($ve60_datasaida)||@$ve60_datasaida==""){
  $ve60_datasaida_dia=date("d",db_getsession("DB_datausu"));
  $ve60_datasaida_mes=date("m",db_getsession("DB_datausu"));
  $ve60_datasaida_ano=date("Y",db_getsession("DB_datausu"));
}
if (!isset($ve60_horasaida)||@$ve60_horasaida==""){
  $ve60_horasaida=db_hora();
}

$res_param = $clveicparam->sql_record($clveicparam->sql_query_file(null,"ve50_integrapessoal",null,"ve50_instit = ".db_getsession("DB_instit")));
if ($clveicparam->numrows > 0){
  db_fieldsmemory($res_param,0);
} else {
  db_msgbox("Parametros nao configurados. Verifique.");
}

if(isset($ve60_veiculo)){

  $devolucao = $clveiculos->sql_record($clveiculos->sql_query_ultimamedida($ve60_veiculo));

  if ($clveiculos->numrows>0){
    db_fieldsmemory($devolucao, 0);
    if ($ultimamedida==null){
      $ultimamedida = 0;
    }
  } else {
    $ultimamedida = 0;
  }

}

db_app::load("scripts.js");
db_app::load("prototype.js");

?>
<style>
  .textAreaVeiculos {

    height: 80px;
    width: 100%;

  }
</style>
<form name="form1" method="post" action="">
  <center>
    <table border="0">
      <tr>
        <td>
          <fieldset>
            <legend><b>Dados da Retirada de Veículo</b></legend>
            <table>
              <tr>
                <td nowrap title="<?=@$Tve60_codigo?>">
                  <label for='ve60_codigo'><?=@$Lve60_codigo?></label>
                </td>
                <td>
                  <?
                  db_input('ve60_codigo',8,$Ive60_codigo,true,'text',3,"")
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Tve60_veiculo?>">
                  <label for="ve60_veiculo">
                    <?
                    db_ancora(@$Lve60_veiculo,"js_pesquisaveiculo();",$db_opcao);
                    ?>
                  </label>
                </td>
                <td>
                  <?
                  db_input('ve60_veiculo',8,$Ive60_veiculo,true,'text',3," onchange='js_pesquisave60_veiculo(false);'");
                  db_input('ve01_placa',10,$Ive01_placa,true,'text',3,'')
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Tve60_veicmotoristas?>">
                  <label for="ve60_veicmotoristas">
                    <?
                    db_ancora(@$Lve60_veicmotoristas,"js_pesquisave60_veicmotoristas(true);",$db_opcao);
                    ?>
                  </label>
                </td>
                <td>
                  <?
                  db_input('ve60_veicmotoristas',8,$Ive60_veicmotoristas,true,'text',$db_opcao,
                           " onchange='js_pesquisave60_veicmotoristas(false);'");
                  if (isset($ve50_integrapessoal) && trim(@$ve50_integrapessoal) != "") {
                    if ($ve50_integrapessoal == 1) {
                      $pessoal = "true";
                    }

                    if ($ve50_integrapessoal == 2) {
                      $pessoal = "false";
                    }
                  }
                  db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <label for='ve60_coddepto'>
                    <b>
                      <?php
                      db_ancora("Departamento Solicitante:", "js_pesquisave60_coddepto(true)" ,$db_opcao);
                      ?>
                    </b>
                  </label>
                </td>
                <td>
                  <?php
                  db_input('ve60_coddepto', 8,$Ive60_coddepto, true, 'text',$db_opcao,'onchange="js_pesquisave60_coddepto(false)"');
                  db_input('descrdepto', 40, false, true, 'text', 3);
                  ?>
                </td>
              </tr>


              <tr>
                <td nowrap title="<?=@$Tve60_datasaida?>">
                  <label for='ve60_datasaida'><?=@$Lve60_datasaida?></label>
                </td>
                <td>
                  <?
                  db_inputdata('ve60_datasaida', @$ve60_datasaida_dia, @$ve60_datasaida_mes, @$ve60_datasaida_ano, true, 'text', $db_opcao,
                               "onchange='js_pesquisa_medida();'", "", "", "none", "", "", "js_pesquisa_medida();")
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Tve60_horasaida?>">
                  <label for='ve60_horasaida'><?=@$Lve60_horasaida?></label>
                </td>
                <td>
                  <?
                  db_input('ve60_horasaida', 5, $Ive60_horasaida, true, 'text', $db_opcao,
                           "onchange='js_verifica_hora(this.value,this.name);js_pesquisa_medida();' onkeypress='return js_mask(event, \"0-9|:|0-9\"); '  ");
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="Última Medida"><label for='ultimamedida'><b>Última Medida:</b></label></td>
                <td>
                  <?
                  /*
                   * caso nao seja setado a data e hora pelo usuario, o sistema buscava a data e hora da seção,
                  * o que ocorria problema, pois as vezes o veiculo estava devolvido ex:
                  * 18/04/2013 10:00 , e o usuario entraria para proxima devolução
                  * 18/04/2013 9:45  o sistema buscava a medida errada.
                  * @todo validar possivel refatoramento no sql para desconsiderar data e hora e buscar diferente a ultima medida.
                  */
                  //$dData = date('Y-m-d', db_getsession("DB_datausu"));
                  $dData = '3000-12-31';
                  if (isset($ve60_datasaida) && strpos($ve60_datasaida, "-") > 0) {

                    $dData = $ve60_datasaida;
                  } else if (isset($ve60_datasaida)) {
                    $dData = substr(@$ve60_datasaida,6,4).'-'.substr(@$ve60_datasaida,3,2).'-'.substr(@$ve60_datasaida,0,2);
                  }
                  $ultimamedida = 0;
                  if (isset($ve60_veiculo) && $ve60_veiculo != "") {

                    $oVeiculo     = new Veiculo($ve60_veiculo);
                    $ultimamedida = $oVeiculo->getUltimaMedidaUso($dData, $ve60_horasaida);
                  }
                  db_input("ultimamedida",15,0,true,"text",3);
                  if (isset($ve07_sigla) && trim($ve07_sigla) != "") {
                    echo " ".db_input("ve07_sigla",3,0,true,"text",3);
                  }
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="Medida de Saída"><label for='ve60_medidasaida'><b>Medida de Saída:</b></label></td>
                <td>
                  <?
                  db_input('ve60_medidasaida',15,$Ive60_medidasaida,true,'text',$db_opcao,"");
                  if (isset($ve07_sigla) && trim($ve07_sigla) != ""){
                    echo " ".db_input("ve07_sigla",3,0,true,"text",3);
                  }
                  ?>
                </td>
              </tr>
              <tr id='tr_proximamedida' style="display:none">
                <td nowrap title="Próxima Medida"><label for='proximamedida'><b>Próxima Medida:</b></label></td>
                <td>
                  <?
                  if (isset($ve60_datasaida) && strpos($ve60_datasaida, "-") > 0) {
                    $dData = $ve60_datasaida;
                  } else {
                    $dData = substr(@$ve60_datasaida,6,4).'-'.substr(@$ve60_datasaida,3,2).'-'.substr(@$ve60_datasaida,0,2);
                  }
                  $Queryproximamedida = $clveiculos->sql_record($clveiculos->sql_query_proximamedida(@$ve60_veiculo,@$dData,@$ve60_horasaida));
                  if($clveiculos->numrows > 0){
                    db_fieldsmemory($Queryproximamedida,0);
                  }else{
                    $proximamedida = 0;
                  }
                  db_input("proximamedida",15,0,true,"text",3);
                  if (isset($ve07_sigla) && trim($ve07_sigla) != ""){
                    echo " ".db_input("ve07_sigla",3,0,true,"text",3);
                  }
                  ?>
                </td>
              </tr>
            </table>
            <fieldset>
              <legend class="bold"><label for='ve60_destino'>Destino</label></legend>
              <?php
              db_textarea('ve60_destino', 1, 1, 0, true, 'text', $db_opcao, "class='textAreaVeiculos'");
              ?>
            </fieldset>
            <fieldset>
              <legend class="bold"><label for="ve60_passageiro">Passageiro</label></legend>
              <?php
              db_textarea('ve60_passageiro', 1, 1, 0, true, 'text', $db_opcao, "class='textAreaVeiculos'");
              ?>
            </fieldset>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td colspan="2" style="text-align: center;">
          <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
                 type="submit" id="db_opcao"
                 value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
            <?=($db_botao==false?"disabled":"")?> onclick="return js_verificamedida();">
          <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
        </td>
      </tr>
    </table>
  </center>
</form>
<script>
  function js_validaHora(){

    var sHora = $F("");
  }

  function js_pesquisave60_veiculo(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo','db_iframe_veiculos','func_veiculos.php?funcao_js=parent.js_mostraveiculos1|ve01_codigo|ve01_placa','Pesquisa',true);
    }else{
      if(document.form1.ve60_veiculo.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_veiculos','func_veiculos.php?pesquisa_chave='+document.form1.ve60_veiculo.value+'&funcao_js=parent.js_mostraveiculos','Pesquisa',false);
      }else{
        document.form1.ve01_placa.value = '';
      }
    }
  }
  function js_mostraveiculos(chave,erro) {

    document.form1.ve01_placa.value = chave;
    if (erro==true) {

      document.form1.ve60_veiculo.focus();
      document.form1.ve60_veiculo.value = '';
    } else {
      js_pesquisa_medida();
    }
  }
  function js_mostraveiculos1(chave1,chave2){
    document.form1.ve60_veiculo.value = chave1;
    document.form1.ve01_placa.value = chave2;
    db_iframe_veiculos.hide();
    js_pesquisa_medida();
  }
  function js_pesquisave60_veicmotoristas(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo','db_iframe_veicmotoristas','func_veicmotoristasalt.php?pessoal=<?=$pessoal?>&funcao_js=parent.js_mostraveicmotoristas1|ve05_codigo|z01_nome','Pesquisa',true);
    }else{
      if(document.form1.ve60_veicmotoristas.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_veicmotoristas','func_veicmotoristasalt.php?pessoal=<?=$pessoal?>&pesquisa_chave='+document.form1.ve60_veicmotoristas.value+'&funcao_js=parent.js_mostraveicmotoristas','Pesquisa',false);
      }else{
        document.form1.z01_nome.value = '';
      }
    }
  }
  function js_mostraveicmotoristas(chave,erro){
    document.form1.z01_nome.value = chave;
    if(erro==true){
      document.form1.ve60_veicmotoristas.focus();
      document.form1.ve60_veicmotoristas.value = '';
    }
  }
  function js_mostraveicmotoristas1(chave1,chave2){
    document.form1.ve60_veicmotoristas.value = chave1;
    document.form1.z01_nome.value = chave2;
    db_iframe_veicmotoristas.hide();
  }
  function js_pesquisave60_coddepto(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_mostradb_depart1|coddepto|descrdepto','Pesquisa',true);
    }else{
      if(document.form1.ve60_coddepto.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_db_depart','func_db_depart.php?pesquisa_chave='+document.form1.ve60_coddepto.value+'&funcao_js=parent.js_mostradb_depart','Pesquisa',false);
      }else{
        document.form1.descrdepto.value = '';
      }
    }
  }
  function js_mostradb_depart(chave,erro){
    document.form1.descrdepto.value = chave;
    if(erro==true){
      document.form1.ve60_coddepto.focus();
      document.form1.ve60_coddepto.value = '';
    }
  }
  function js_mostradb_depart1(chave1,chave2){
    document.form1.ve60_coddepto.value = chave1;
    document.form1.descrdepto.value = chave2;
    db_iframe_db_depart.hide();
  }
  function js_pesquisave60_usuario(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
    }else{
      if(document.form1.ve60_usuario.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.ve60_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
      }else{
        document.form1.nome.value = '';
      }
    }
  }
  function js_mostradb_usuarios(chave,erro){
    document.form1.nome.value = chave;
    if(erro==true){
      document.form1.ve60_usuario.focus();
      document.form1.ve60_usuario.value = '';
    }
  }
  function js_mostradb_usuarios1(chave1,chave2){
    document.form1.ve60_usuario.value = chave1;
    document.form1.nome.value = chave2;
    db_iframe_db_usuarios.hide();
  }
  function js_pesquisa(){
    js_OpenJanelaIframe('top.corpo','db_iframe_veicretirada','func_veicretiradaalt.php?&devol=false&funcao_js=parent.js_preenchepesquisa|ve60_codigo','Pesquisa',true);
  }
  function js_preenchepesquisa(chave){
    db_iframe_veicretirada.hide();
    <?
    if($db_opcao!=1){
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
    ?>
  }
  function js_pesquisaveiculo(){
    baixa="1";
    js_OpenJanelaIframe('top.corpo','db_iframe_veiculos','func_veiculosalt.php?baixa='+baixa+'&funcao_js=parent.js_preencheveiculo|ve01_codigo','Pesquisa',true);
  }
  function js_preencheveiculo(chave){
    db_iframe_veiculos.hide();
    <?
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?veiculo='+chave";
    ?>
  }

  <? if ($db_opcao != 3) { ?>
  function js_verificamedida(){
    var medidasaida = new Number(document.form1.ve60_medidasaida.value);
    var ultimasaida = new Number(document.form1.ultimamedida.value);
    var proxima     = new Number(document.form1.proximamedida.value);

    if(ultimasaida > medidasaida){
      alert ("Valor da medida menor que o valor da última medida");
      document.form1.ve60_medidasaida.style.backgroundColor='#99A9AE';
      document.form1.ve60_medidasaida.value='';
      document.form1.ve60_medidasaida.focus();
      return false;
    }

    if(proxima > 0) {
      if(proxima < medidasaida){
        alert ("Valor da medida maior que o valor da proxima medida");
        document.form1.ve60_medidasaida.style.backgroundColor='#99A9AE';
        document.form1.ve60_medidasaida.value='';
        document.form1.ve60_medidasaida.focus();
        return false;
      }
    }

    return true;
  }

  <? } ?>

  function js_verifica_hora(valor,campo) {
    erro= 0;
    ms  = "";
    hs  = "";

    tam = "";
    pos = "";
    tam = valor.length;
    pos = valor.indexOf(":");
    if (pos!=-1) {
      if (pos==0 || pos>2) {
        erro++;
      } else {
        if (pos==1) {
          hs = "0"+valor.substr(0,1);
          ms = valor.substr(pos+1,2);
        } else if (pos==2) {
          hs = valor.substr(0,2);
          ms = valor.substr(pos+1,2);
        }
        if (ms=="") {
          ms = "00";
        }
      }
    } else {
      if (tam>=4) {
        hs = valor.substr(0,2);
        ms = valor.substr(2,2);
      } else if (tam==3) {
        hs = "0"+valor.substr(0,1);
        ms = valor.substr(1,2);
      } else if (tam==2) {
        hs = valor;
        ms = "00";
      } else if (tam==1) {
        hs = "0"+valor;
        ms = "00";
      }
    }
    if (ms!="" && hs!="") {
      if (hs>24 || hs<0 || ms>60 || ms<0) {
        erro++
      } else {
        if (ms==60) {
          ms = "59";
        }
        if (hs==24) {
          hs = "00";
        }
        hora = hs;
        minu = ms;
      }
    }

    if (erro>0) {
      alert("Informe uma hora válida.");
    }
    if (valor!="") {
      eval("document.form1."+campo+".focus();");
      eval("document.form1."+campo+".value='"+hora+":"+minu+"';");
    }
  }

  function js_pesquisa_medida() {
    var databanco = document.form1.ve60_datasaida_ano.value + '-' +
      document.form1.ve60_datasaida_mes.value + '-' +
      document.form1.ve60_datasaida_dia.value;
    var retirada = document.form1.ve60_codigo.value;
    js_OpenJanelaIframe('top.corpo', 'db_iframe_ultimamedida',
      'func_veiculos_medida.php?metodo=ultimamedida&veiculo='+document.form1.ve60_veiculo.value+
      '&data='+databanco+
      '&hora='+document.form1.ve60_horasaida.value+
      '&retirada='+retirada+
      '&funcao_js=parent.js_mostraultimamedida', 'Pesquisa Ultima Medida', false);

    js_OpenJanelaIframe('top.corpo', 'db_iframe_proximamedida',
      'func_veiculos_medida.php?metodo=proximamedida&veiculo='+document.form1.ve60_veiculo.value+
      '&data='+databanco+
      '&hora='+document.form1.ve60_horasaida.value+
      '&retirada='+retirada+
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

  function js_buscaDepartamentoPadrao() {

    if (document.form1.ve60_coddepto.value == '') {
      document.form1.ve60_coddepto.value = <?= db_getsession("DB_coddepto") ?>;
      js_pesquisave60_coddepto(false);
    }
  }

  <?php
  if($db_opcao == 1){
    echo "js_buscaDepartamentoPadrao();";
  }
  ?>

</script>