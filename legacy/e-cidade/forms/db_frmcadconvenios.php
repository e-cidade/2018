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


$clcadconvenio->rotulo->label();
$clconveniocobranca->rotulo->label();
$clconvenioarrecadacao->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("ar12_nome");

?>
<div class="container">
  <form name="form1" method="post" action="">
      <fieldset>
        <legend>Cadastro de Convênios</legend>
       <table class="form-container">
         <tr>
           <td id="tdWidthControll" nowrap title="<?=@$Tar11_sequencial?>" width="124px;">
            <?=@$Lar11_sequencial?>
          </td>
          <td>
            <?
              db_input('ar11_sequencial',10,$Iar11_sequencial,true,'text',3,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tar11_cadtipoconvenio?>">
            <?=@$Lar11_cadtipoconvenio?>
          </td>
          <td>
            <?
              $rsConsultaTipo  = $clcadtipoconvenio->sql_record($clcadtipoconvenio->sql_query_file(null,"ar12_sequencial,ar12_nome"));
               db_selectrecord("ar11_cadtipoconvenio",$rsConsultaTipo,true,$db_opcao,"","","","","js_validaTipo(this.value);");
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tar11_nome?>">
             <?=@$Lar11_nome?>
          </td>
          <td>
          <?
            db_input('ar11_nome',51,$Iar12_nome,true,'text',$db_opcao,"");
          ?>
           </td>
        </tr>
        <tr>
           <td nowrap title="<?=@$Tar11_instit?>">
             <?=@$Lar11_instit?>
           </td>
           <td>
           <?
             db_input('ar11_instit',7,$Iar11_instit,true,'text',3,"");
             db_input('nomeinst'   ,40,"",true,'text',3,"");
           ?>
           </td>
        </tr>
      </table>
       <table id="formCobranca" class="form-container">
         <tr>
           <td nowrap title="<?=@$Tar13_bancoagencia?>"  width="124px">
             <?
              db_ancora("<b>Agência:</b>","js_pesquisaBancoAgenciaCobranc(true)",$db_opcao);
             ?>
           </td>
           <td>
            <?
              db_input('ar13_sequencial',10,"",true,'hidden',3,"");
              db_input('ar13_bancoagencia',7,$Iar13_bancoagencia,true,'text',$db_opcao,"onChange='js_pesquisaBancoAgenciaCobranc(false)'");
              db_input('agencia13',40,"",true,'text',3,"");
            ?>
           </td>
         </tr>
        <tr>
          <td nowrap title="<?php echo $Tar13_contabancaria; ?>" width="124px">
            <a href="javascript:;" id="ancora_conta">Conta Bancária:</a>
          </td>
          <td>
            <?php
              db_input('ar13_contabancaria',7,$Iar13_contabancaria,true,'text',$db_opcao, 'data="0"');
              db_input('descricao_conta', 40, "", true, 'text', 3, 'data="1" class="field-size7"');
            ?>
          </td>
        </tr>
         <tr>
           <td nowrap title="<?=@$Tar13_carteira?>">
             <?=@$Lar13_carteira?>
           </td>
           <td id="carteiraNormal">
            <?
              db_input('ar13_carteira',7,$Iar13_carteira,true,'text',$db_opcao,"")
            ?>
           </td>
           <td id="carteiraSicob" style="display:none">
             <?
               $aCarteiraSicob = array("0" =>"Selecione",
                                       "82"=>"Sem Registro",
                                       "9" =>"Rápida");
               db_select('ar13_carteira_selsicob',$aCarteiraSicob,true,$db_opcao,"");
             ?>
           </td>
           <td id="carteiraSigcb" style="display:none">
             <?
               $aCarteiraSigcb = array("0" =>"Selecione",
                                       "11"=>"Com registros impressos pela CEF",
                                       "14"=>"Com registros impressos pelo cedente",
                                       "21"=>"Sem registros impressos pela CEF",
                                       "24"=>"Sem registros impressos pelo cedente");
               db_select('ar13_carteira_selsigcb',$aCarteiraSigcb,true,$db_opcao,"onchange='js_validaCarteiraSigcb(this.value);'");
             ?>
           </td>
        </tr>
        <tr>
           <td nowrap title="<?=@$Tar13_variacao?>">
             <?=@$Lar13_variacao?>
           </td>
           <td>
            <?
              db_input('ar13_variacao',7,$Iar13_variacao,true,'text',$db_opcao,"")
            ?>
           </td>
         </tr>
          <tr id="nossonumero" >
            <td nowrap title="<?=@$Tar13_responsavelnossonumero?>">
              <?=@$Lar13_responsavelnossonumero?>
            </td>
            <td id="responsavelNossoNumero">
              <?
                $aResponsaveis = array("t" =>"Instituição",
                                        "f"=>"Banco");
                db_select('ar13_responsavelnossonumero',$aResponsaveis,true,$db_opcao,"");
              ?>
            </td>
         </tr>
         <tr>
           <td nowrap title="<?=@$Tar13_cedente?>">
             <?=@$Lar13_cedente?>
           </td>
           <td>
            <?
              db_input('ar13_cedente',7,$Iar13_cedente,true,'text',$db_opcao,"");
              db_input('ar13_digcedente',1,$Iar13_digcedente,true,'text',$db_opcao,"");
            ?>
           </td>
         </tr>
         <tr>
           <td nowrap title="<?=@$Tar13_convenio?>">
             <?=@$Lar13_convenio?>
           </td>
           <td>
             <?
             db_input('ar13_convenio',7,$Iar13_convenio,true,'text',$db_opcao,"")
             ?>
           </td>
         </tr>
         <tr>
           <td nowrap title="<?=@$Tar13_especie?>">
             <?=@$Lar13_especie?>
           </td>
           <td>
            <?
              db_input('ar13_especie',7,$Iar13_especie,true,'text',$db_opcao,"")
            ?>
           </td>
        </tr>
         <tr>
           <td nowrap title="<?=@$Tar13_operacao?>">
             <?=@$Lar13_operacao?>
           </td>
           <td>
             <?
               db_input('ar13_operacao',7,$Iar13_operacao,true,'text',$db_opcao,"")
             ?>
           </td>
         </tr>
       </table>
       <table class="form-container" id="cadconveniogrupotaxa" style="display: none;">
         <tr>
           <td nowrap  width="124px">
             <b><?db_ancora("Grupo Taxa", "js_pesquisagrupotaxa(true);", 4);?></b>
           </td>
           <td>
             <?
               db_input("ar37_sequencial",  7, "ar37_sequencial", true, "text", 4, "onchange='js_pesquisagrupotaxa(false);'");
               db_input("ar37_descricao",  40, "ar37_descricao",  true, "text", 3, "");
             ?>
           </td>
         </tr>
      </table>
       <table class="form-container"  id="formArrecadacao" style="display:none;">
        <tr>
           <td nowrap title="<?=@$Tar14_bancoagencia?>" width="124px">
             <?
               db_ancora("<b>Agencia</b>","js_pesquisaBancoAgenciaArrecad(true)",$db_opcao);
             ?>
           </td>
           <td>
            <?
              db_input('ar14_sequencial',10,"",true,'hidden',3,"");
              db_input('ar14_bancoagencia',7,$Iar14_bancoagencia,true,'text',$db_opcao,"onChange='js_pesquisaBancoAgenciaArrecad(false)'");
              db_input('agencia14',40,"",true,'text',3,"");
            ?>
           </td>
        </tr>
       </table>
      </fieldset>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_valida()" >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  </form>
</div>
<script type="text/javascript">

//=====================================ancora cadconveniogrupotaxa ===================================================

function js_pesquisagrupotaxa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_grupotaxa','func_grupotaxa.php?funcao_js=parent.js_mostragrupotaxa1|ar37_sequencial|ar37_descricao','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_grupotaxa','func_grupotaxa.php?pesquisa_chave='+document.form1.ar37_sequencial.value+'&funcao_js=parent.js_mostragrupotaxa','Pesquisa','false');
  }
}

function js_mostragrupotaxa(chave,erro){
  document.form1.ar37_descricao.value = chave;
  if(erro==true){
    document.form1.ar37_descricao.focus();
    document.form1.ar37_descricao.value = '';
  }
  db_iframe_grupotaxa.hide();
}

function js_mostragrupotaxa1(chave1,chave2){
  document.form1.ar37_sequencial.value = chave1;
  document.form1.ar37_descricao.value = chave2;
  db_iframe_grupotaxa.hide();
}

var oLookupConta = new DBLookUp($('ancora_conta'), $('ar13_contabancaria'), $('descricao_conta'), {
  "sArquivo" : "func_contabancariacadastro.php",
  "sObjetoLookUp" : "db_iframe_contabancaria",
  "sLabel" : "Pesquisar Contas Bancárias"
});

var oAbrirJanela = oLookupConta.abrirJanela;

oLookupConta.abrirJanela = function(lAbre) {

  var iCodigoAgencia = $F('ar13_bancoagencia');

  if (iCodigoAgencia == '') {

    alert("Campo agência deve ser informado.");

    limparContaBancaria();
    return false;
  }

  this.setParametrosAdicionais(["bancoagencia=" + iCodigoAgencia + "&tp=1&lImplantacao=1"]);
  oAbrirJanela.call(this, lAbre);
}

function limparContaBancaria() {
  $('ar13_contabancaria').value = '';
  $('descricao_conta').value = '';
}

if ($F('ar13_bancoagencia') != '' && $F('ar13_contabancaria') != '') {

  var oEvento = new Event("change");
  $('ar13_contabancaria').dispatchEvent(oEvento);
}

<?php if ($db_opcao == 3) { ?>
  oLookupConta.desabilitar();
<?php } ?>

function js_valida() {

  var iTipo = document.form1.ar11_cadtipoconvenio.value;

  if ( iTipo == 5 || iTipo == 6 ) {

    if ( iTipo == 5 ) {
      var aOpt = document.form1.ar13_carteira_selsicob.options;
    } else {
      var aOpt = document.form1.ar13_carteira_selsigcb.options;
    }

    for ( var iInd=0; iInd < aOpt.length; iInd++ ) {
      if ( aOpt[iInd].selected ) {
        if( aOpt[iInd].value == 0 ){
          alert('Carteira não informada!');
          return false;
        } else {
          document.form1.ar13_carteira.value = aOpt[iInd].value;
        }
      }
    }
  }

  if ( iTipo == 5 ) {

    var sCedente = new String(document.form1.ar13_cedente.value);

    if ( sCedente.length != 8 ) {
      alert('Número de caracteres do campo cedente inválido!');
      return false;
    }

    if ( document.form1.ar13_digcedente.value == '' ) {
      alert('Dígito do cedente não informado!');
      return false;
    }
  }

  return true;

  }


function js_validaTipo(iTipo){

  $('tdWidthControll').width = '124px';

  if (iTipo == 1 || iTipo == 2 || iTipo == 6) {

    $('tdWidthControll').width = '173px';

    $('nossonumero').style.display = "";
    if (iTipo == 6) {
      $('ar13_carteira_selsigcb').onchange();
      $('ar13_responsavelnossonumero').disabled = true;
    } else {
      $('ar13_responsavelnossonumero').disabled = false;
    }

  } else {
    $('nossonumero').style.display = "none";
  }

  if ( iTipo == 1 || iTipo == 2 || iTipo == 5  || iTipo == 6 || iTipo == 7) {

     $('formCobranca').style.display    = "";
     $('formArrecadacao').style.display = "none";

     if (iTipo == 7) {
       $('cadconveniogrupotaxa').style.display = "inline";
     } else {
       $('cadconveniogrupotaxa').style.display = "none";
     }

     if ( iTipo == 5 ) {

      $("carteiraSicob").style.display      = "";
      $("carteiraSigcb").style.display      = "none";
      $("carteiraNormal").style.display     = "none";

      document.form1.ar13_cedente.maxLength = 8;

     } else {

      $("carteiraSicob").style.display      = "none";

      if ( iTipo == 6 ) {
        $("carteiraSigcb").style.display    = "";
        $("carteiraNormal").style.display   = "none";
      } else {
        $("carteiraSigcb").style.display    = "none";
        $("carteiraNormal").style.display   = "";

      }

       document.form1.ar13_cedente.maxLength = 13;

     }

     if ( iTipo == 1 || iTipo == 7) {
       document.form1.ar13_convenio.maxLength = 7;
     } else {
       document.form1.ar13_convenio.maxLength = 4;
      document.form1.ar13_convenio.value     = new String(document.form1.ar13_convenio.value).substr(0,4);
     }

  } else if ( iTipo == 3 ) {
     $('formCobranca').style.display    = "none";
     $('formArrecadacao').style.display = "";
  } else {
    $('formCobranca').style.display    = "none";
     $('formArrecadacao').style.display = "none";
  }

}


function js_pesquisaBancoAgenciaCobranc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_bancoagencia','func_bancoagencia.php?funcao_js=parent.js_mostrabancoagenciacobranc1|db89_sequencial|db89_codagencia','Pesquisa',true);
  }else{
     if(document.form1.ar13_bancoagencia.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_bancoagencia','func_bancoagencia.php?pesquisa_chave='+document.form1.ar13_bancoagencia.value+'&funcao_js=parent.js_mostrabancoagenciacobranc','Pesquisa',false);
     }else{
       document.form1.agencia13.value = '';
     }
  }
}

function js_mostrabancoagenciacobranc(chave,erro){
  limparContaBancaria();
  document.form1.agencia13.value = chave;
  if(erro==true){
    document.form1.ar13_bancoagencia.focus();
    document.form1.ar13_bancoagencia.value = '';
  }
}

function js_mostrabancoagenciacobranc1(chave1,chave2){
  limparContaBancaria();
  document.form1.ar13_bancoagencia.value = chave1;
  document.form1.agencia13.value     = chave2;
  db_iframe_bancoagencia.hide();
}


function js_pesquisaBancoAgenciaArrecad(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_bancoagencia','func_bancoagencia.php?funcao_js=parent.js_mostrabancoagenciaarrecad1|db89_sequencial|db89_codagencia','Pesquisa',true);
  }else{
     if(document.form1.ar14_bancoagencia.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_bancoagencia','func_bancoagencia.php?pesquisa_chave='+document.form1.ar14_bancoagencia.value+'&funcao_js=parent.js_mostrabancoagenciaarrecad','Pesquisa',false);
     }else{
       document.form1.agencia14.value = '';
     }
  }
}

function js_mostrabancoagenciaarrecad(chave,erro){
  document.form1.agencia14.value = chave;
  if(erro==true){
    document.form1.ar14_bancoagencia.focus();
    document.form1.ar14_bancoagencia.value = '';
  }
}

function js_mostrabancoagenciaarrecad1(chave1,chave2){
  document.form1.ar14_bancoagencia.value = chave1;
  document.form1.agencia14.value     = chave2;
  db_iframe_bancoagencia.hide();
}

function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cadconvenio','func_cadconvenio.php?funcao_js=parent.js_preenchepesquisa|ar11_sequencial','Pesquisa',true);
}

function js_preenchepesquisa(chave){
  db_iframe_cadconvenio.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function js_validaCarteiraSigcb(iCarteira){

  $('ar13_responsavelnossonumero').value = 't';
  if (iCarteira == 11 || iCarteira == 21) {
    $('ar13_responsavelnossonumero').value = 'f';
  }
}
</script>
<script>

$("ar11_sequencial")     .addClassName("field-size2");
$("ar11_cadtipoconvenio").setAttribute("rel","ignore-css");
$("ar11_cadtipoconvenio").addClassName("field-size2");
<? if($db_opcao != 3){?>
$("ar11_cadtipoconveniodescr").setAttribute("rel","ignore-css");
$("ar11_cadtipoconveniodescr").addClassName("field-size7");

<? } ?>
<? if($db_opcao==3){?>
$("ar12_nome")        .addClassName("field-size7");
<? } ?>
$("ar11_nome")        .addClassName("field-size9");
$("ar11_instit")      .addClassName("field-size2");
$("nomeinst")         .addClassName("field-size7");
$("ar13_bancoagencia").addClassName("field-size2");
$("agencia13")        .addClassName("field-size7");
$("ar13_carteira")    .addClassName("field-size2");
$("ar13_variacao")    .addClassName("field-size2");
$("ar13_convenio")    .addClassName("field-size2");
$("ar13_cedente")     .addClassName("field-size3");
$("ar13_digcedente")  .addClassName("field-size1");
$("ar13_especie")     .addClassName("field-size2");
$("ar13_operacao")    .addClassName("field-size2");

$("ar14_bancoagencia").addClassName("field-size2");
$("agencia14")        .addClassName("field-size7");

$("ar37_sequencial")  .addClassName("field-size2");
$("ar37_descricao")   .addClassName("field-size7");

</script>
