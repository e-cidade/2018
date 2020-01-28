<?php

/**
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

//MODULO: pessoal
$clcfpess->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh27_descr");
$clrotulo->label("r08_codigo");
$clrotulo->label("r08_descr");
?>
<form name="form1" method="post" action="" class="container">
<style>
.selectRubricas {

  width: 80px;
  margin-top:8px;
}
.selectRubricas:first-child {
  margin-top:0px;
}

#oRubricasConsignada {
  width: 100%;
}
</style>
<fieldset>
<legend>Rubricas Especiais</legend>
<table border="0">
  <?php
  $r11_anousu = db_anofolha();
  $r11_mesusu = db_mesfolha();
  db_input('r11_anousu',4,$Ir11_anousu,true,'hidden',$db_opcao,"");
  db_input('r11_mesusu',2,$Ir11_mesusu,true,'hidden',$db_opcao,"");
  ?>
  <tr>
    <td>
      <fieldset id="13oSalario">
        <legend><strong>13º Salário</strong></legend>
        <table width="100%">
          <tr>
            <td nowrap title="<?=@$Tr11_rubdec?>" width="40%">
              <?php
              db_ancora(@$Lr11_rubdec,"js_pesquisar11_rubdec(true)",$db_opcao);
              ?>
            </td>
            <td nowrap> 
              <?php
              db_input('r11_rubdec',4,$Ir11_rubdec,true,'text',$db_opcao,"onchange='js_pesquisar11_rubdec(false)'");
              db_input("rh27_descr",30,$Irh27_descr,true,"text",3,"","rh27_descr1");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td>
      <fieldset id="Ferias">
        <legend><strong>Férias</strong></legend>
        <table width="100%">
          <tr>
            <td nowrap title="<?=@$Tr11_ferias?>" width="40%">
              <?php
              db_ancora(@$Lr11_ferias,"js_pesquisar11_ferias(true)",$db_opcao);
              ?>
            </td>
            <td nowrap> 
              <?php
              db_input('r11_ferias',4,$Ir11_ferias,true,'text',$db_opcao,"onchange='js_pesquisar11_ferias(false)'");
              db_input("rh27_descr",30,$Irh27_descr,true,"text",3,"","rh27_descr2");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tr11_fer13?>">
              <?php
              db_ancora(@$Lr11_fer13,"js_pesquisar11_fer13(true)",$db_opcao);
              ?>
            </td>
            <td nowrap>
              <?php
              db_input('r11_fer13',4,$Ir11_fer13,true,'text',$db_opcao,"onchange='js_pesquisar11_fer13(false)'");
              db_input("rh27_descr",30,$Irh27_descr,true,"text",3,"","rh27_descr3");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tr11_ferabo?>">
              <?php
              db_ancora(@$Lr11_ferabo,"js_pesquisar11_ferabo(true)",$db_opcao);
              ?>
            </td>
            <td nowrap>
              <?php
              db_input('r11_ferabo',4,$Ir11_ferabo,true,'text',$db_opcao,"onchange='js_pesquisar11_ferabo(false)'");
              db_input("rh27_descr",30,$Irh27_descr,true,"text",3,"","rh27_descr4");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tr11_fer13a?>">
              <?php
              db_ancora(@$Lr11_fer13a,"js_pesquisar11_fer13a(true)",$db_opcao);
              ?>
            </td>
            <td nowrap>
              <?php
              db_input('r11_fer13a',4,$Ir11_fer13a,true,'text',$db_opcao,"onchange='js_pesquisar11_fer13a(false)'");
              db_input("rh27_descr",30,$Irh27_descr,true,"text",3,"","rh27_descr10");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tr11_feradi?>">
              <?php
              db_ancora(@$Lr11_feradi,"js_pesquisar11_feradi(true)",$db_opcao);
              ?>
            </td>
            <td nowrap>
              <?php
              db_input('r11_feradi',4,$Ir11_feradi,true,'text',$db_opcao,"onchange='js_pesquisar11_feradi(false)'");
              db_input("rh27_descr",30,$Irh27_descr,true,"text",3,"","rh27_descr5");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tr11_fadiab?>">
              <?php
              db_ancora(@$Lr11_fadiab,"js_pesquisar11_fadiab(true)",$db_opcao);
              ?>
            </td>
            <td nowrap>
              <?php
              db_input('r11_fadiab',4,$Ir11_fadiab,true,'text',$db_opcao,"onchange='js_pesquisar11_fadiab(false)'");
              db_input("rh27_descr",30,$Irh27_descr,true,"text",3,"","rh27_descr6");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tr11_ferant?>">
              <?php
              db_ancora(@$Lr11_ferant,"js_pesquisar11_ferant(true)",$db_opcao);
              ?>
            </td>
            <td nowrap>
              <?php
              db_input('r11_ferant',4,$Ir11_ferant,true,'text',$db_opcao,"onchange='js_pesquisar11_ferant(false)'");
              db_input("rh27_descr",30,$Irh27_descr,true,"text",3,"","rh27_descr7");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tr11_feabot?>">
              <?php
              db_ancora(@$Lr11_feabot,"js_pesquisar11_feabot(true)",$db_opcao);
              ?> 
            </td>
            <td nowrap>
              <?php
              db_input('r11_feabot',4,$Ir11_feabot,true,'text',$db_opcao,"onchange='js_pesquisar11_feabot(false)'");
              db_input("rh27_descr",30,$Irh27_descr,true,"text",3,"","rh27_descr8");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td>
      <fieldset id="DescontoConsignado">
        <legend><strong>Desconto Consignado</strong></legend>
        <table style=" width: 100%">
          
          <tr>
            <td>
              <?php
                db_ancora("<strong>Rubrica:</strong>", "js_pesquisadesconto_consignado(true)", $db_opcao);
                db_input('desconto_consignado', 4, '', true, 'text', $db_opcao, "onchange='js_pesquisadesconto_consignado(false)'");
                db_input("desconto_consignado_descr", 40, '', true, "text", 3, "", "desconto_consignado_descr");
              ?>
            </td>
            <td>
              <input id="adicionarRubrica" class="selectRubricas" type="button" value="Adicionar" onclick="oSelecaoRubricas.adicionar();" /><br />
            </td>
          </tr>
          <tr>
            <td>
            <?php
              $oDaoRubricaDescontoConsignado  = db_utils::getDao('rubricadescontoconsignado');
              $sSqlRubricaDescontoConsignado  = $oDaoRubricaDescontoConsignado->sql_query(null, 'rh27_rubric,  rh27_rubric || \' - \' || rh27_descr ', 'rh140_ordem', 'rh140_instit = ' . db_getsession("DB_instit"));
              $rsSqlRubricaDescontoConsignado = db_query($sSqlRubricaDescontoConsignado);

              db_selectmultiple("oRubricasConsignada",           $rsSqlRubricaDescontoConsignado, 6, 1);
              db_selectmultiple("oRubricasConsignadaAnteriores", $rsSqlRubricaDescontoConsignado, 6, 1, "style=\"display: none;\" ");
            ?> 
            </td>
            <td style="width:80px">
              <input class="selectRubricas" type="button" value="Cima"    onclick="oSelecaoRubricas.mover('cima');" /><br />
              <input class="selectRubricas" type="button" value="Baixo"   onClick="oSelecaoRubricas.mover('baixo');" /><br />
              <input class="selectRubricas" type="button" value="Remover" onClick="oSelecaoRubricas.remover();" /><br />
            </td>
          </tr> 
        </table>         
      </fieldset>
    </td>
  </tr>
  <tr>
    <td>
      <fieldset id="AbatimentoConsignavel">
        <legend>Base de Rubricas de Recebimento Integral</legend>
        <table border="0">
          <tr>
            <td colspan="2" id="msgboard"></td>
          </tr>
          <tr>
            <td width="40">
              <label><a href="javascript:void(0)" id="oLabelBases"><?=$Lr08_codigo?></a></label>
            </td>
            <td>
              <?php
                db_input("r08_codigo",4, $Ir08_codigo, true,"text",2,"lang='r08_codigo'","r08_codigo");
                db_input("r08_descr", 30, $Ir08_descr,  true,"text",2,"lang='r08_descr'","r08_descr");
              ?>
            </td> 
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td>
      <fieldset id="CalculoLiquido">
        <legend><strong>Cálculo Sobre o Líquido (Bruto Obrigatório)</strong></legend>
        <table width="100%">
          <tr>
            <td nowrap title="<?=@$Tr11_desliq?>" width="40%">
              <?php
              db_ancora(@$Lr11_desliq,"",3);
              ?>
            </td>
            <td nowrap>
              <?php
              db_input("r11_desliq",38,$Ir11_desliq,true,"text",$db_opcao,"");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td>
      <fieldset id="CalculoIntegral">
        <legend><strong>Cálculo Sobre o Valor Integral</strong></legend>
        <table width="100%">
          <tr>
            <td nowrap title="<?=@$Tr11_rubpgintegral?>" width="40%">
              <?php
              db_ancora(@$Lr11_rubpgintegral,"",3);
              ?>
            </td>
            <td nowrap> 
              <?
              db_input("r11_rubpgintegral",38,$Ir11_rubpgintegral,true,"text",$db_opcao,"");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td>
      <fieldset id="AbonoPermanencia">
        <legend><strong>Abono de Permanência para Previdência</strong></legend>
        <table width="100%">
          <tr>
            <td>
              <label id="oLabelPermanencia"><a href="javascript:void(0)" title="<?php echo $Tr11_abonoprevidencia ?>"><?php echo $Lr11_abonoprevidencia ?></a></label>
            </td>
            <td nowrap> 
              <?php
                db_input("r11_abonoprevidencia", null, $Ir11_abonoprevidencia, true, "text", $db_opcao, "lang='rh27_rubric'");
                db_input("rh27_descr", null, $Irh27_descr, true, "text", 3, "lang='rh27_descr'", "rh27_descr11");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td>
      <fieldset id="PensaoAlimenticia">
        <legend><strong>Pensão alimentícia</strong></legend>
        <table width="100%">
          <tr>
            <td nowrap align="right" title="<?=@$Tr11_palime?>" width="40%">
              <?php
              db_ancora(@$Lr11_palime,"js_pesquisar11_palime(true)",$db_opcao);
              ?>
            </td>
            <td nowrap>
              <?php
              db_input('r11_palime',4,$Ir11_palime,true,'text',$db_opcao,"onchange='js_pesquisar11_palime(false)'");
              db_input("rh27_descr",30,$Irh27_descr,true,"text",3,"","rh27_descr9");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td>
      <fieldset id="RubricaSubstituicao">
        <legend><strong>Rubricas para Substituição de Função</strong></legend>
        <table width="100%">
          <tr>
            <td title="<?=$Tr11_rubricasubstituicaoanterior?>" width="110">
              <a href="javascript:void(0)" id="oLabelSubstituicaoAnterior" title=""><?=$Lr11_rubricasubstituicaoanterior?></a>
            </td>
            <td>
            <?php

              if ($r11_rubricasubstituicaoanterior) {
                $oDescricaoRubricaAnterior = RubricaRepository::getInstanciaByCodigo($r11_rubricasubstituicaoanterior)->getDescricao();
              }

              db_input('r11_rubricasubstituicaoanterior',4,$Ir11_rubricasubstituicaoanterior,true,'text',$db_opcao,"lang='rh27_rubric'");
              db_input("oDescricaoRubricaAnterior",30,$Irh27_descr,true,"text",3,"lang='rh27_descr'");             
              ?>
            </td>
          </tr>
          <tr>
            <td title="<?=$Tr11_rubricasubstituicaoatual?>" width="110">
              <a href="javascript:void(0)" id="oLabelSubstituicaoAtual" title=""><?=$Lr11_rubricasubstituicaoatual?></a>
            </td>
            <td>
            <?php
              if($r11_rubricasubstituicaoatual){
                $oDescricaoRubricaAtual = RubricaRepository::getInstanciaByCodigo($r11_rubricasubstituicaoatual)->getDescricao();
              }

              db_input('r11_rubricasubstituicaoatual',4,$Ir11_rubricasubstituicaoatual,true,'text',$db_opcao,"lang='rh27_rubric'");
              db_input("oDescricaoRubricaAtual",30,$Irh27_descr,true,"text",3,"lang='rh27_descr'");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
</table>
</fieldset>
</br>
<input type="hidden" name="<?=($db_opcao==1?'incluir':($db_opcao==2||$db_opcao==22?'alterar':'excluir'))?>" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"/>
<input name="botao" type="button" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="submitForm( this.form );" >
</form>
<script>

(function(){

  var oLookUpBases = new DBLookUp($('oLabelBases'), $('r08_codigo'), $('r08_descr'), {
      "sArquivo"      : "func_bases.php",
      "sObjetoLookUp" : "db_iframe_bases"
  });

  var oLookUoAbono = new DBLookUp($('oLabelPermanencia'), $('r11_abonoprevidencia'), $('rh27_descr11'), {
      "sArquivo"      : "func_rhrubricas.php",
      "sObjetoLookUp" : "db_iframe_rhrubricas"
  });

  $('desconto_consignado').ondrop = $('desconto_consignado').onpaste = function(){
    return false;
  };

  var oToogle13oSalario            = new DBToogle('13oSalario', false);
  var oToogleFerias                = new DBToogle('Ferias', false);
  var oToogleDescontoConsignado    = new DBToogle('DescontoConsignado', false);
  var oToogleAbatimentoConsignavel = new DBToogle('AbatimentoConsignavel', false);
  var oToogleCalculoLiquido        = new DBToogle('CalculoLiquido', false);
  var oToogleCalculoIntegral       = new DBToogle('CalculoIntegral', false);
  var oToogleAbonoPermanencia      = new DBToogle('AbonoPermanencia', false);
  var oTooglePensaoAlimenticia     = new DBToogle('PensaoAlimenticia', false);
  var oToogleRubricaSubstituicao   = new DBToogle('RubricaSubstituicao', false);

  // Mensagem para sugestão da base de rubricas do recebimento integral
  const ARQUIVO_MENSAGEM = 'recursoshumanos.pessoal.pes1_cfpessrubricasespeciais.';

  var sMensagem     = _M(ARQUIVO_MENSAGEM + 'base_rubricas_recebimento_integral_suggest');
  var oMessageBoard = new DBMessageBoard('msgboard1','Atenção!', sMensagem + "</br>&nbsp;",$('msgboard'));
  oMessageBoard.divContent.style.height = '80px';
  oMessageBoard.divContent.style.width  = '460px';
  oMessageBoard.divContent.style.border = '2px groove white';
  oMessageBoard.show();


  var oParametrosSubstituicao = {
                                  "sArquivo" : "func_rhrubricas.php",
                                  "sLabel"   : "Pesquisar Rubricas" 
                                }

  var oLookupExercicioAnterior = new DBLookUp($('oLabelSubstituicaoAnterior'), $('r11_rubricasubstituicaoanterior'), $('oDescricaoRubricaAnterior'), oParametrosSubstituicao);
  var oLookupExercicioAtual    = new DBLookUp($('oLabelSubstituicaoAtual'), $('r11_rubricasubstituicaoatual'), $('oDescricaoRubricaAtual'), oParametrosSubstituicao);
})();

function js_pesquisar11_rubdec(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostrarubdec1|rh27_rubric|rh27_descr','Pesquisa',true);
  }else{
    if(document.form1.r11_rubdec.value != ''){ 
      quantcaracteres = document.form1.r11_rubdec.value.length;
      for(i=quantcaracteres;i<4;i++){
        document.form1.r11_rubdec.value = "0"+document.form1.r11_rubdec.value;        
      }
      js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+document.form1.r11_rubdec.value+'&funcao_js=parent.js_mostrarubdec','Pesquisa',false);
    }else{
      document.form1.rh27_descr1.value = ''; 
    }
  }
}
function js_mostrarubdec(chave,erro){
  document.form1.rh27_descr1.value = chave; 
  if(erro==true){ 
    document.form1.r11_rubdec.focus(); 
    document.form1.r11_rubdec.value = ''; 
  }
}
function js_mostrarubdec1(chave1,chave2){
  document.form1.r11_rubdec.value = chave1;
  document.form1.rh27_descr1.value = chave2;
  db_iframe_rhrubricas.hide();
}
function js_pesquisar11_ferias(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostraferias1|rh27_rubric|rh27_descr','Pesquisa',true);
  }else{
    if(document.form1.r11_ferias.value != ''){ 
      quantcaracteres = document.form1.r11_ferias.value.length;
      for(i=quantcaracteres;i<4;i++){
        document.form1.r11_ferias.value = "0"+document.form1.r11_ferias.value;        
      }
      js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+document.form1.r11_ferias.value+'&funcao_js=parent.js_mostraferias','Pesquisa',false);
    }else{
      document.form1.rh27_descr2.value = ''; 
    }
  }
}
function js_mostraferias(chave,erro){
  document.form1.rh27_descr2.value = chave; 
  if(erro==true){ 
    document.form1.r11_ferias.focus(); 
    document.form1.r11_ferias.value = ''; 
  }
}
function js_mostraferias1(chave1,chave2){
  document.form1.r11_ferias.value = chave1;
  document.form1.rh27_descr2.value = chave2;
  db_iframe_rhrubricas.hide();
}
function js_pesquisar11_fer13(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostrafer131|rh27_rubric|rh27_descr','Pesquisa',true);
  }else{
    if(document.form1.r11_fer13.value != ''){ 
      quantcaracteres = document.form1.r11_fer13.value.length;
      for(i=quantcaracteres;i<4;i++){
        document.form1.r11_fer13.value = "0"+document.form1.r11_fer13.value;        
      }
      js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+document.form1.r11_fer13.value+'&funcao_js=parent.js_mostrafer13','Pesquisa',false);
    }else{
      document.form1.rh27_descr3.value = ''; 
    }
  }
}
function js_mostrafer13(chave,erro){
  document.form1.rh27_descr3.value = chave; 
  if(erro==true){ 
    document.form1.r11_fer13.focus(); 
    document.form1.r11_fer13.value = ''; 
  }
}
function js_mostrafer131(chave1,chave2){
  document.form1.r11_fer13.value = chave1;
  document.form1.rh27_descr3.value = chave2;
  db_iframe_rhrubricas.hide();
}
function js_pesquisar11_ferabo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostraferabo1|rh27_rubric|rh27_descr','Pesquisa',true);
  }else{
    if(document.form1.r11_ferabo.value != ''){ 
      quantcaracteres = document.form1.r11_ferabo.value.length;
      for(i=quantcaracteres;i<4;i++){
        document.form1.r11_ferabo.value = "0"+document.form1.r11_ferabo.value;        
      }
      js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+document.form1.r11_ferabo.value+'&funcao_js=parent.js_mostraferabo','Pesquisa',false);
    }else{
      document.form1.rh27_descr4.value = ''; 
    }
  }
}
function js_mostraferabo(chave,erro){
  document.form1.rh27_descr4.value = chave; 
  if(erro==true){ 
    document.form1.r11_ferabo.focus(); 
    document.form1.r11_ferabo.value = ''; 
  }
}
function js_mostraferabo1(chave1,chave2){
  document.form1.r11_ferabo.value = chave1;
  document.form1.rh27_descr4.value = chave2;
  db_iframe_rhrubricas.hide();
}
function js_pesquisar11_fer13a(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostrafer13a1|rh27_rubric|rh27_descr','Pesquisa',true);
  }else{
    if(document.form1.r11_fer13a.value != ''){ 
      quantcaracteres = document.form1.r11_fer13a.value.length;
      for(i=quantcaracteres;i<4;i++){
        document.form1.r11_fer13a.value = "0"+document.form1.r11_fer13a.value;        
      }
      js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+document.form1.r11_fer13a.value+'&funcao_js=parent.js_mostrafer13a','Pesquisa',false);
    }else{
      document.form1.rh27_descr10.value = ''; 
    }
  }
}
function js_mostrafer13a(chave,erro){
  document.form1.rh27_descr10.value = chave; 
  if(erro==true){ 
    document.form1.r11_fer13a.focus(); 
    document.form1.r11_fer13a.value = ''; 
  }
}
function js_mostrafer13a1(chave1,chave2){
  document.form1.r11_fer13a.value = chave1;
  document.form1.rh27_descr10.value = chave2;
  db_iframe_rhrubricas.hide();
}
function js_pesquisar11_feradi(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostraferadi1|rh27_rubric|rh27_descr','Pesquisa',true);
  }else{
    if(document.form1.r11_feradi.value != ''){ 
      quantcaracteres = document.form1.r11_feradi.value.length;
      for(i=quantcaracteres;i<4;i++){
        document.form1.r11_feradi.value = "0"+document.form1.r11_feradi.value;        
      }
      js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+document.form1.r11_feradi.value+'&funcao_js=parent.js_mostraferadi','Pesquisa',false);
    }else{
      document.form1.rh27_descr5.value = ''; 
    }
  }
}
function js_mostraferadi(chave,erro){
  document.form1.rh27_descr5.value = chave; 
  if(erro==true){ 
    document.form1.r11_feradi.focus(); 
    document.form1.r11_feradi.value = ''; 
  }
}
function js_mostraferadi1(chave1,chave2){
  document.form1.r11_feradi.value = chave1;
  document.form1.rh27_descr5.value = chave2;
  db_iframe_rhrubricas.hide();
}
function js_pesquisar11_fadiab(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostrafadiab1|rh27_rubric|rh27_descr','Pesquisa',true);
  }else{
    if(document.form1.r11_fadiab.value != ''){ 
      quantcaracteres = document.form1.r11_fadiab.value.length;
      for(i=quantcaracteres;i<4;i++){
        document.form1.r11_fadiab.value = "0"+document.form1.r11_fadiab.value;        
      }
      js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+document.form1.r11_fadiab.value+'&funcao_js=parent.js_mostrafadiab','Pesquisa',false);
    }else{
      document.form1.rh27_descr6.value = ''; 
    }
  }
}
function js_mostrafadiab(chave,erro){
  document.form1.rh27_descr6.value = chave; 
  if(erro==true){ 
    document.form1.r11_fadiab.focus(); 
    document.form1.r11_fadiab.value = ''; 
  }
}
function js_mostrafadiab1(chave1,chave2){
  document.form1.r11_fadiab.value = chave1;
  document.form1.rh27_descr6.value = chave2;
  db_iframe_rhrubricas.hide();
}
function js_pesquisar11_ferant(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostraferant1|rh27_rubric|rh27_descr','Pesquisa',true);
  }else{
    if(document.form1.r11_ferant.value != ''){ 
      quantcaracteres = document.form1.r11_ferant.value.length;
      for(i=quantcaracteres;i<4;i++){
        document.form1.r11_ferant.value = "0"+document.form1.r11_ferant.value;        
      }
      js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+document.form1.r11_ferant.value+'&funcao_js=parent.js_mostraferant','Pesquisa',false);
    }else{
      document.form1.rh27_descr7.value = ''; 
    }
  }
}
function js_mostraferant(chave,erro){
  document.form1.rh27_descr7.value = chave; 
  if(erro==true){ 
    document.form1.r11_ferant.focus(); 
    document.form1.r11_ferant.value = ''; 
  }
}
function js_mostraferant1(chave1,chave2){
  document.form1.r11_ferant.value = chave1;
  document.form1.rh27_descr7.value = chave2;
  db_iframe_rhrubricas.hide();
}
function js_pesquisar11_feabot(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostrafeabot1|rh27_rubric|rh27_descr','Pesquisa',true);
  }else{
    if(document.form1.r11_feabot.value != ''){ 
      quantcaracteres = document.form1.r11_feabot.value.length;
      for(i=quantcaracteres;i<4;i++){
        document.form1.r11_feabot.value = "0"+document.form1.r11_feabot.value;        
      }
      js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+document.form1.r11_feabot.value+'&funcao_js=parent.js_mostrafeabot','Pesquisa',false);
    }else{
      document.form1.rh27_descr8.value = ''; 
    }
  }
}
function js_mostrafeabot(chave,erro){
  document.form1.rh27_descr8.value = chave; 
  if(erro==true){ 
    document.form1.r11_feabot.focus(); 
    document.form1.r11_feabot.value = ''; 
  }
}
function js_mostrafeabot1(chave1,chave2){
  document.form1.r11_feabot.value = chave1;
  document.form1.rh27_descr8.value = chave2;
  db_iframe_rhrubricas.hide();
}
function js_pesquisadesconto_consignado(mostra){


  if (mostra==true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?tipo_rubrica=2&funcao_js=parent.js_mostradesconto_consignado1|rh27_rubric|rh27_descr','Pesquisa',true);
   
  } else {

    $('desconto_consignado').disabled = true;
    var value = escape(document.form1.desconto_consignado.value);

    if (value != '') {  

      quantcaracteres = value.length;

      for (i = quantcaracteres;i < 4; i++) {
         value = "0"+value;        
      }

      document.form1.desconto_consignado.value = value;
      js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?tipo_rubrica=2&pesquisa_chave='+value+'&funcao_js=parent.js_mostradesconto_consignado','Pesquisa',false);

    } else {
      $('desconto_consignado').disabled = false;
      $('desconto_consignado_descr').value   = '';
    }
  }
}

function js_mostradesconto_consignado(chave,erro) {

  document.form1.desconto_consignado_descr.value = chave; 
  if (erro==true) {  

    document.form1.desconto_consignado.focus(); 
    document.form1.desconto_consignado.value = ''; 
  }
  $('desconto_consignado').disabled = false;
}
function js_mostradesconto_consignado1(chave1,chave2) {

  console.log(chave1,chave2);

  $('desconto_consignado').disabled = false;
  document.form1.desconto_consignado.value = chave1;
  document.form1.desconto_consignado_descr.value = chave2;
  db_iframe_rhrubricas.hide();
}

/**
 * Seleção de Várias Rubricas para Pensões Alimentícias
 */
var oSelecaoRubricas = {

  /**
   *  Adiciona Item ao Combo
   */
  adicionar: function () {

    $('desconto_consignado').disabled = true;
    $('adicionarRubrica').disabled = true;

    js_pesquisadesconto_consignado(false);

    /**
     * Utiliza o setInterval para aguardar o 
     * retorno da função js_pesquisadesconto_consignado.
     */
    var adicionaRubrica = setInterval(function() {

      if (!$('desconto_consignado').disabled) {

        $('adicionarRubrica').disabled = false;
        clearInterval(adicionaRubrica);
        oSelecaoRubricas.adicionarRubrica();

      }
    }, 100);
  },
  /**
   * Remove um Item da Lista
   */
  remover  : function () {

    var aOpcoes        = oSelecaoRubricas.oElementoPrincipal.options;
    var aOpcoesExcluir = [];
    
    for ( var iOpcao = 0; iOpcao < aOpcoes.length; iOpcao++ ) {

      var oOpcao = aOpcoes[iOpcao];
      
      if ( oOpcao.selected ) {
        aOpcoesExcluir.push( oOpcao ); 
      }
    }

    for ( var iExclusao in aOpcoesExcluir ) {

      var oItem = aOpcoesExcluir[iExclusao];
      
      if ( !aOpcoesExcluir[iExclusao] ) {
        continue;
      }

      delete( oSelecaoRubricas.oOpcoes[oItem.value] );
      
      oSelecaoRubricas.oElementoPrincipal.removeChild(oItem);
    }
  },

  /**
   * ReOrganiza o Item na Lista
   */
  mover    : function ( sDirecao ) {

    var aOpcoes            = oSelecaoRubricas.oElementoPrincipal.options;
    var oItemMover         = new Object();
    var oItemSubstituido   = new Object();
    var iTotalItens        = aOpcoes.length;
    var iIndiceItemAtual   = null;
    var iIndiceProximoItem = 0;
    
    for ( var iOpcao = 0; iOpcao < iTotalItens; iOpcao++ ) {

      var oOpcao = aOpcoes[iOpcao];
      
      if ( oOpcao.selected ) {

        oItemMover         = oOpcao;
        iIndiceItemAtual   = iOpcao;
        break; 
      }
    }

    if ( iIndiceItemAtual == null ) {
      return;
    }
    
    if ( sDirecao == "cima" ) {

      if ( iIndiceItemAtual == 0 ) {
        return;  
      } 
      oItemSubstituido = oSelecaoRubricas.oElementoPrincipal.options[ iIndiceItemAtual - 1 ];
    } else if ( sDirecao == "baixo" ) {

      if ( iIndiceItemAtual > iTotalItens ) {
        return;  
      } 
      oItemSubstituido = oSelecaoRubricas.oElementoPrincipal.options[ iIndiceItemAtual + 2 ];
    } else {
      return;
    }

    oSelecaoRubricas.oElementoPrincipal.removeChild(oItemMover);
    oSelecaoRubricas.oElementoPrincipal.insertBefore(oItemMover, oItemSubstituido);
  },
  /**
   * Adiciona a Rubrica a grid
   */
  adicionarRubrica: function(){
    var oElementoCodigoRubrica    = document.getElementById('desconto_consignado');
    var oElementoDescricaoRubrica = document.getElementById('desconto_consignado_descr');
    $('desconto_consignado').disabled = false;

    if ( oElementoCodigoRubrica.value == "" ) {
      return;
    }

    if ( oSelecaoRubricas.oOpcoes[oElementoCodigoRubrica.value] ) {

      alert('Esta rubrica já foi adicionada.');
      return;
    }
    
    var oNovaOpcao           = document.createElement("option");
        oNovaOpcao.value     = oElementoCodigoRubrica.value;
        oNovaOpcao.innerHTML = oElementoCodigoRubrica.value + ' - ' + oElementoDescricaoRubrica.value; 
        oNovaOpcao.selected  = true;

    oSelecaoRubricas.oElementoPrincipal.appendChild( oNovaOpcao );
    
    oSelecaoRubricas.oOpcoes[oElementoCodigoRubrica.value] = oElementoCodigoRubrica.value + ' - ' + oElementoDescricaoRubrica.value;
    oElementoCodigoRubrica.value    = '';
    oElementoDescricaoRubrica.value = '';

    return true;
  }
}
oSelecaoRubricas.oElementoPrincipal = document.getElementById('oRubricasConsignada');
oSelecaoRubricas.oOpcoes            = new Object();   


function js_pesquisar11_palime(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostrapalime1|rh27_rubric|rh27_descr','Pesquisa',true);
  }else{
    if(document.form1.r11_palime.value != ''){ 
      quantcaracteres = document.form1.r11_palime.value.length;
      for(i=quantcaracteres;i<4;i++){
        document.form1.r11_palime.value = "0"+document.form1.r11_palime.value;        
      }
      js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+document.form1.r11_palime.value+'&funcao_js=parent.js_mostrapalime','Pesquisa',false);
    }else{
      document.form1.rh27_descr9.value = ''; 
    }
  }
}
function js_mostrapalime(chave,erro){
  document.form1.rh27_descr9.value = chave; 
  if(erro==true){ 
    document.form1.r11_palime.focus(); 
    document.form1.r11_palime.value = ''; 
  }
}
function js_mostrapalime1(chave1,chave2){
  document.form1.r11_palime.value = chave1;
  document.form1.rh27_descr9.value = chave2;
  db_iframe_rhrubricas.hide();
}


/**
 * Função na Inicialização da Página
 * para que as variaveis não interfiram no escopo geral do script
 */
(function () {

  /**
   * Adiciona as Opções carregadas pelo PHP no "Componente"
   */
  var aOpcoes = oSelecaoRubricas.oElementoPrincipal.children;
  for ( var iOpcao = 0; iOpcao < aOpcoes.length; iOpcao++ ) {
  
    var oOpcao                       = aOpcoes[iOpcao];
    var sLabel                       = new String(oOpcao.value);
    oSelecaoRubricas.oOpcoes[sLabel] = oOpcao.label;
  }

 var aAnteriores = $('oRubricasConsignadaAnteriores').children;

 /**
  * Seleciona Todos os item padrão do Carregamento
  */
 for ( var iOpcaoAnterior in aAnteriores ) {

   if ( !aAnteriores[iOpcaoAnterior] ) {
     continue;
   }
   aAnteriores[iOpcaoAnterior].selected = true;
 }
 
})();


 function submitForm( oForm ) {
   /**
    * Seleciona Todos os itens para postar
    */
   var aOpcoes = oSelecaoRubricas.oElementoPrincipal.children;
   for ( var iOpcaoAnterior in aOpcoes ) {

     if ( !aOpcoes[iOpcaoAnterior] ) {
       continue;
     }
     aOpcoes[iOpcaoAnterior].selected = true;
   }


   if( $F("r11_rubricasubstituicaoanterior") != '' || $F("r11_rubricasubstituicaoatual") != '') {

     if ($F("r11_rubricasubstituicaoanterior") == '') {

       alert('Favor informar o Exercício Anterior');
       return false;
     }

     if ($F("r11_rubricasubstituicaoatual") == '') {

       alert('Favor informar o Exercício Atual');
       return false;
     }
   }

   oForm.submit();
 }
</script>
