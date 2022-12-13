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
$clveicdevolucao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ve60_codigo");
$clrotulo->label("ve60_veiculo");
$clrotulo->label("ve01_codigo");
$clrotulo->label("ve01_placa");
$clrotulo->label("z01_nome");
$clrotulo->label("nome");
$clrotulo->label("ve60_medidasaida");
$clrotulo->label("ve60_datasaida");
$clrotulo->label("ve60_horasaida");

db_app::load("scripts.js");
db_app::load("prototype.js");
?>
<form name="form1" id="form1" method="post" action="" onsubmit="return js_verificaDataDevolucao();">
<center>
<table>
  <tr>
    <td>
      <fieldset>
        <legend><b>Dados da Devolução</b></legend>
        <table>
          <tr>
            <td nowrap title="<?=@$Tve61_codigo?>">
               <?=@$Lve61_codigo?>

            </td>
            <td>
            <?
            db_input('ve61_codigo',10,$Ive61_codigo,true,'text',3,"")
            ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tve61_veicretirada?>">
               <?
               db_ancora(@$Lve61_veicretirada,"js_pesquisaretirada();",$db_opcao);
               ?>
            </td>
            <td>
              <?
              db_input('ve61_veicretirada',10,$Ive61_veicretirada,true,'text',3,
                       " onchange='js_pesquisave61_veicretirada(false);'");
              db_input('ve60_codigo',10,$Ive60_codigo,true,'hidden',3,'')
               ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tve60_veiculo?>">
               <?=@$Lve60_veiculo?>
            </td>
            <td>
              <?
              db_input('ve60_veiculo',10,$Ive60_veiculo,true,'text',3,"");
              db_input('ve01_placa',10,$Ive01_placa,true,'text',3,'')
               ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tve60_datasaida?>">
               <?=@$Lve60_datasaida?>
            </td>
            <td>
            <?
            db_inputdata('ve60_datasaida',@$ve60_datasaida_dia,@$ve60_datasaida_mes,@$ve60_datasaida_ano,true,'text',3,"")
            ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tve60_horasaida?>">
               <?=@$Lve60_horasaida?>
            </td>
            <td>
              <?
              db_input('ve60_horasaida',5,$Ive60_horasaida,true,'text',3);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tve60_medidasaida?>">
               <?=@$Lve60_medidasaida?>
            </td>
            <td>
              <?
              db_input('ve60_medidasaida',10,$Ive60_medidasaida,true,'text',3,"");
              if (isset($ve07_sigla) && trim($ve07_sigla) != ""){
                echo " ".db_input("ve07_sigla",3,0,true,"text",3);
              }
              ?>
            </td>
          </tr>

          <tr id='tr_proximamedida' style="display:none">
            <td nowrap title="Próxima Medida"><b>Próxima Medida:</b></td>
            <td>
              <?
                if (isset($ve61_datadevol) && strpos($ve61_datadevol, "-") > 0) {
                  $dData = $ve61_datadevol;
                } else {
                  $dData = substr(@$ve61_datadevol,6,4).'-'.substr(@$ve61_datadevol,3,2).'-'.substr(@$ve61_datadevol,0,2);
                }
                $proximamedida = 0;

                if (isset($ve60_veiculo) && $ve60_veiculo != "") {

                  $oVeiculo     = new Veiculo($ve60_veiculo);
                  $ultimamedida = @$oVeiculo->getUltimaMedidaUso(@$dData, @$ve61_horadevol);
                }
                db_input("proximamedida",10,0,true,"text",3);
                if (isset($ve07_sigla) && trim($ve07_sigla) != ""){
                  echo " ".db_input("ve07_sigla",3,0,true,"text",3);
                }
              ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="Última Medida"><b>Última Medida:</b></td>
            <td>
              <?
                if (isset($ve61_datadevol) && strpos($ve61_datadevol, "-") > 0) {
                  $dData = $ve61_datadevol;
                } else {
                  $dData = substr(@$ve61_datadevol,6,4).'-'.substr(@$ve61_datadevol,3,2).'-'.substr(@$ve61_datadevol,0,2);
                }

                if (isset($ve60_veiculo) && $ve60_veiculo != "") {

                  $Queryultimamedida = $clveiculos->sql_record($clveiculos->sql_query_ultimamedida(@$ve60_veiculo,@$dData,@$ve61_horadevol));
                  if($clveiculos->numrows > 0){
                     db_fieldsmemory($Queryultimamedida,0);
                  } else{
                    $ultimamedida = 0;
                  }
                }

                db_input('ultimamedida',10,$Ive60_medidasaida,true,'text',3,"");
                if (isset($ve07_sigla) && trim($ve07_sigla) != ""){
                  echo " ".db_input("ve07_sigla",3,0,true,"text",3);
                }
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tve61_veicmotoristas?>">
               <?
               db_ancora(@$Lve61_veicmotoristas,"js_pesquisave61_veicmotoristas(true);",$db_opcao);
               ?>
            </td>
            <td>
              <?
              db_input('ve61_veicmotoristas',10,$Ive61_veicmotoristas,true,'text',$db_opcao,
                      " onchange='js_pesquisave61_veicmotoristas(false);'");
              db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
               ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tve61_datadevol?>">
               <?=@$Lve61_datadevol?>
            </td>
            <td>
              <?
                db_inputdata('ve61_datadevol',@$ve61_datadevol_dia,@$ve61_datadevol_mes,@$ve61_datadevol_ano,true,'text',$db_opcao,
                  "onchange='js_pesquisa_medida();'", "", "", "none", "", "", "js_pesquisa_medida();")
              ?>
            </td>
          </tr>
        <tr>
          <td nowrap title="<?=@$Tve61_horadevol?>">
             <?=@$Lve61_horadevol?>
          </td>
          <td>
            <?
              db_input('ve61_horadevol',5,@$Ive61_horadevol,true,'text',$db_opcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tve61_medidadevol?>">
             <?=@$Lve61_medidadevol?>
          </td>
          <td>
          <?
          db_input('ve61_medidadevol',10,@$ve61_medidadevol,true,'text',$db_opcao,"onchange=\"js_verificamedida('')\"");
          if (isset($ve07_sigla) && trim($ve07_sigla) != ""){
            echo " ".db_input("ve07_sigla",3,0,true,"text",3);
          }
          ?>
          </td>
        </tr>
      </table>
    </fieldset>
  </td>
 </tr>
 <tr>
   <td colspan="2" style="text-align: center;">
     <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
            type="submit" id="db_opcao"
            value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
            <?=($db_botao==false?"disabled":"")?> onclick="return js_verificamedida(1);">
     <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
   </td>
 </tr>
</table>
</center>
</form>
<script>
function js_verificaDataDevolucao(){

  var dtRetirada     = $F("ve60_datasaida");
  var dtDevolucao    = $F("ve61_datadevol");

  var sHoraRetirada  = $F("ve60_horasaida");
  var sHoraDevolucao = $F("ve61_horadevol");

    if(js_comparadata(dtRetirada, dtDevolucao, ">")){

      alert("Data de retirada maior que data de devolução.");
      return false;

    }
    if (js_comparadata(dtRetirada, dtDevolucao, "==")) {

      var iHoraRetirada  = parseFloat(sHoraRetirada.replace(":", ""));
      var iHoraDevolucao = parseFloat(sHoraDevolucao.replace(":", ""));

      if (iHoraRetirada > iHoraDevolucao) {

        alert("Hora da retirada é maior que a hora da devolucao.");
        return false;
      }
    }

  return true;
}

function js_pesquisave61_veicretirada(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_veicretirada','func_veicretirada.php?funcao_js=parent.js_mostraveicretirada1|ve60_codigo|ve60_codigo','Pesquisa',true);
  }else{
     if(document.form1.ve61_veicretirada.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_veicretirada','func_veicretirada.php?pesquisa_chave='+document.form1.ve61_veicretirada.value+'&funcao_js=parent.js_mostraveicretirada','Pesquisa',false);
     }else{
       document.form1.ve60_codigo.value = '';
     }
  }
}
function js_mostraveicretirada(chave,erro){
  document.form1.ve60_codigo.value = chave;
  if(erro==true){
    document.form1.ve61_veicretirada.focus();
    document.form1.ve61_veicretirada.value = '';
  }
}
function js_mostraveicretirada1(chave1,chave2){
  document.form1.ve61_veicretirada.value = chave1;
  document.form1.ve60_codigo.value = chave2;
  db_iframe_veicretirada.hide();
}
function js_pesquisave61_veicmotoristas(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_veicmotoristas','func_veicmotoristasalt.php?funcao_js=parent.js_mostraveicmotoristas1|ve05_codigo|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.ve61_veicmotoristas.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_veicmotoristas','func_veicmotoristasalt.php?pesquisa_chave='+document.form1.ve61_veicmotoristas.value+'&funcao_js=parent.js_mostraveicmotoristas','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostraveicmotoristas(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.ve61_veicmotoristas.focus();
    document.form1.ve61_veicmotoristas.value = '';
  }
}
function js_mostraveicmotoristas1(chave1,chave2){
  document.form1.ve61_veicmotoristas.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_veicmotoristas.hide();
}
function js_pesquisave61_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.ve61_usuario.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.ve61_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = '';
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave;
  if(erro==true){
    document.form1.ve61_usuario.focus();
    document.form1.ve61_usuario.value = '';
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.ve61_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_veicdevolucao','func_veicdevolucao.php?funcao_js=parent.js_preenchepesquisa|ve61_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_veicdevolucao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_pesquisaretirada(){
  js_OpenJanelaIframe('top.corpo','db_iframe_veicretirada','func_veicretiradaalt.php?&devol=false&funcao_js=parent.js_preencheretirada|ve60_codigo','Pesquisa',true);
}
function js_preencheretirada(chave){
  db_iframe_veicretirada.hide();
  <?
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?retirada='+chave";
  ?>
}

<? if ($db_opcao != 3) { ?>

function js_verificamedida(op){

  var medidadevol = new Number(document.form1.ve61_medidadevol.value);
  var ultimasaida = new Number(document.form1.ve60_medidasaida.value);
  var proxima     = new Number(document.form1.proximamedida.value);

  if(ultimasaida > medidadevol){
    alert ("Valor da medida menor que o valor da medida de saida");
    document.form1.ve61_medidadevol.style.backgroundColor='#99A9AE';
    document.form1.ve61_medidadevol.value='';
    document.form1.ve61_medidadevol.focus();
    return false;
  }

  if(proxima > 0) {
    if(proxima < medidadevol){
      alert ("Valor da medida maior que o valor da proxima medida");
      document.form1.ve61_medidadevol.style.backgroundColor='#99A9AE';
      document.form1.ve61_medidadevol.value='';
      document.form1.ve61_medidadevol.focus();
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
  var databanco = document.form1.ve61_datadevol_ano.value + '-' +
                  document.form1.ve61_datadevol_mes.value + '-' +
                  document.form1.ve61_datadevol_dia.value;
  var devolucao = document.form1.ve61_codigo.value;
  js_OpenJanelaIframe('top.corpo', 'db_iframe_ultimamedida',
    'func_veiculos_medida.php?metodo=ultimamedida&veiculo='+document.form1.ve60_veiculo.value+
                                                '&data='+databanco+
                                                '&hora='+document.form1.ve61_horadevol.value+
                                                '&devolucao='+devolucao+
                                                '&funcao_js=parent.js_mostraultimamedida', 'Pesquisa Ultima Medida', false);

  js_OpenJanelaIframe('top.corpo', 'db_iframe_proximamedida',
    'func_veiculos_medida.php?metodo=proximamedida&veiculo='+document.form1.ve60_veiculo.value+
                                                 '&data='+databanco+
                                                 '&hora='+document.form1.ve61_horadevol.value+
                                                 '&devolucao='+devolucao+
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

/**
 * Validamos se eh uma hora valida
 */
function js_validaHora(oCampo) {

  var iHora    = oCampo.value.substr(0, 2);
  var iMinutos = oCampo.value.substr(3, 2);

  if (iHora == 24 && iMinutos > 0) {

    alert('Formato de hora inválido');
    $('ve61_horadevol').style.backgroundColor = '#99A9AE';
    return false;
  }

  if (iHora != 24 && (iHora > 23 || iMinutos > 59)) {

    alert('Formato de hora inválido');
    return false;
  }
  return true;
}

/**
 * Formata o campo da hora
 */
function js_formataHora() {

  new MaskedInput("#ve60_horasaida", "00:00", {placeholder:"0"});
  new MaskedInput("#ve61_horadevol", "00:00", {placeholder:"0"});
}

$('ve60_horasaida').onblur = function() {

  if (!js_validaHora(this)) {
    $('ve60_horasaida').style.backgroundColor = '#99A9AE';
  }
};

$('ve61_horadevol').onblur = function() {

  if (!js_validaHora(this)) {
    $('ve61_horadevol').style.backgroundColor = '#99A9AE';
  }
  js_pesquisa_medida();
 // alert(444);
};

js_formataHora();
</script>