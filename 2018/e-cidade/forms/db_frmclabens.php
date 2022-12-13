<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: patrim
require_once("model/contabilidade/planoconta/ContaCorrente.model.php");
require_once("classes/db_clabens_classe.php");
require_once("classes/db_clabensconplano_classe.php");

$clclabens->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("descricaoconta");
$clrotulo->label("t86_conplanodepreciacao");
$clrotulo->label("t86_conplano");
$clrotulo->label("c60_descr");


if($db_opcao==1){
    $ac="pat1_clabens001.php";
}else if($db_opcao==22 || $db_opcao==2){
    $ac="pat1_clabens002.php";
}else if($db_opcao==33 || $db_opcao==3){
    $ac="pat1_clabens003.php";
}

$sSql 						 = $clcfpatri->sql_query_file(null,"t06_codcla");
$result_t06_codcla = db_query($sSql);

db_fieldsmemory($result_t06_codcla,0); 
?>
<form class="container" name="form1" id="form1" method="post" action="<?=$ac;?>">
  <fieldset>
    <legend>Classificação</legend>
    <table class="form-container">
      <tr>
        <td nowrap title="<?=@$Tt64_codcla?>">
          <?=@$Lt64_codcla?>
        </td>
        <td>
          <?
            db_input('t64_codcla',10,$It64_codcla,true,'text',3,"");
          ?>
        </td>
      </tr>
      <?
        if(isset($estrutura_altera) || isset($chavepesquisa) && isset($t64_class)){
          if(empty($estrutura_altera)){
            $estrutura_altera=$t64_class;
          }
           db_input('estrutura_altera',4,$It64_codcla,true,'hidden',3);
        }
        
        //if(isset($db_atualizar) && (empty($estrutura_altera) || (isset($estrutura_altera) && str_replace(".","",$t64_class) != $estrutura_altera))){ 
        //  $cldb_estrut->db_estrut_inclusao($t64_class,$mascara,"clabens","t64_class","t64_analitica");
        //  if($cldb_estrut->erro_status==0){
        //    $err_estrutural = $cldb_estrut->erro_msg;
        //  }else{
        //    $focar=true;
        //  }
        // }
        
        $cldb_estrut->autocompletar = true;
        $cldb_estrut->mascara = true;
        $cldb_estrut->reload  = true;
        $cldb_estrut->input   = false;
        $cldb_estrut->size    = 10;
        $cldb_estrut->nome    = "t64_class";
        $cldb_estrut->db_opcao= $db_opcao==1?1:3;
        $cldb_estrut->db_mascara("$t06_codcla");
      ?>
      <tr>
        <td nowrap title="<?=@$Tt64_descr?>">
          <?=@$Lt64_descr?>
        </td>
        <td> 
          <?
            db_input('t64_descr',50,$It64_descr,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tt64_obs?>" colspan="2">
          <fieldset class="separator">
            <legend><?=@$Lt64_obs?></legend>
            <?
              db_textarea('t64_obs',3,47,$It64_obs,true,'text',$db_opcao,"")
            ?>
          </fieldset>
        </td>
      </tr>
      <!-- Conta  -->
      <tr>
        <td nowrap title="<?=@$Tt86_conplano?>">
          <?
            db_ancora("Conta Plano","js_pesquisat86_conplano(true);",$db_opcao);
          ?>
        </td>
        <td> 
          <?
            db_input('t86_conplano',6,$It86_conplano,true,'text',$db_opcao," onchange='js_pesquisat86_conplano(false);'")
          ?>
          <?
            db_input('descricaoconta',38,$Ic60_descr,true,'text',3,'')
          ?>
        </td>
      </tr>
      <!-- Conta  Depreciacao -->
      <tr>
        <td nowrap title="<?=@$Tt86_conplanodepreciacao?>">
          <?
            db_ancora("Conta Depreciação","js_pesquisat86_conplanodepreciacao(true);",$db_opcao);
          ?>
        </td>
        <td> 
          <?
            db_input('t86_conplanodepreciacao',6,$It86_conplanodepreciacao,true,'text',$db_opcao," onchange='js_pesquisat86_conplanodepreciacao(false);'")
          ?>
          <?
            db_input('descricaocontadepreciacao',38,$Ic60_descr,true,'text',3,'')
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="Nível">
          Nível:
        </td>
        <td>
          <?
            $x = array("f"=>"Sintéticas","t"=>"Analíticas");
            db_select('t64_analitica',$x,true,$db_opcao,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tt64_bemtipos?>">
          <?=@$Lt64_bemtipos?>
        </td>
        <td>
          <?
            $x = array();
            $oDaoBemTipos = db_utils::getDao('bemtipos');
            $rsSql =  $oDaoBemTipos->sql_record($oDaoBemTipos->sql_query(null,"*","t24_sequencial",null));
            if ($oDaoBemTipos->numrows  > 0) {
            	$x[0] = "Nenhum";
            	for ($i = 0; $i < $oDaoBemTipos->numrows; $i++) {
            		$x[db_utils::fieldsMemory($rsSql,$i)->t24_sequencial] = db_utils::fieldsMemory($rsSql,$i)->t24_descricao;
            	}
            }
            
            db_select('t64_bemtipos',$x,true,$db_opcao,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tt64_benstipodepreciacao?>">
          <? db_ancora($Lt64_benstipodepreciacao, "js_pesquisaTipoDepreciacao(true)", $db_opcao);?>
        </td>
        <td>
        	<?php 
        	  db_input("t64_benstipodepreciacao", 8, $It64_benstipodepreciacao, true, "text", $db_opcao, "onchange='js_pesquisaTipoDepreciacao(false);'");
        	  db_input("t46_descricao", 38, '', true, "text", 3);
        	?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tt64_vidautil?>">
          <?=$Lt64_vidautil;?>
        </td>
        <td>
        	<?php 
        	  db_input("t64_vidautil", 8, $It64_vidautil, true, "text", $db_opcao);
        	?>
        </td>
      </tr> 
    </table>
  </fieldset>
  <input onclick="return js_validaFormularioClassificacao();" 
         name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
         type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>


function js_validaFormularioClassificacao() {

  var iClassificacao   = document.getElementsByName("t64_class")[0].value;
  var sDescricao       =  $F("t64_descr");
  var sObservacao      =  $F("t64_obs");
  var iPlano           =  $F("t86_conplano");
  var iTipoBem         =  $F("t64_bemtipos");
  var iTipoDepreciacao =  $F("t64_benstipodepreciacao");
  var iVidaUtil        =  $F("t64_vidautil");

  if (iClassificacao == "") {

    alert(_M("patrimonial.patrimonio.db_frmclabens.informe_classificacao"));
    return false;
  }

  if (sDescricao == "") {

    alert(_M("patrimonial.patrimonio.db_frmclabens.informe_descricao"));
    return false;
  }

  if (sObservacao == "") {

    alert(_M("patrimonial.patrimonio.db_frmclabens.informe_observacao"));
    return false;
  }
  
  if (iPlano == "") {

    alert(_M("patrimonial.patrimonio.db_frmclabens.informe_plano_conta"));
    return false;
  }

  if (iTipoBem == "" || iTipoBem == "0") {

    alert(_M("patrimonial.patrimonio.db_frmclabens.informe_tipo_bem"));
    return false;
  }

  if (iTipoDepreciacao == "") {

    alert(_M("patrimonial.patrimonio.db_frmclabens.informe_tipo_depreciacao"));
    return false;
  }

  if (iVidaUtil == "") {

    alert(_M("patrimonial.patrimonio.db_frmclabens.informe_vida_util"));
    return false;
  }
  return true; 
}


function js_pesquisaTipoDepreciacao(lMostra) {

  if (lMostra) {
    var sUrlOpen = "func_benstipodepreciacao.php?limita=true&funcao_js=parent.js_preencheTipoDepreciacao|t46_sequencial|t46_descricao";
    js_OpenJanelaIframe('top.corpo', 'db_iframe_benstipodepreciacao', sUrlOpen, 'Pesquisa Depreciação', true);
  }else{
     if($("t64_benstipodepreciacao").value != ''){

 			 var sUrlOpen = "func_benstipodepreciacao.php?limita=true&pesquisa_chave="+$('t64_benstipodepreciacao').value+"&funcao_js=parent.js_completaTipoDepreciacao";
       js_OpenJanelaIframe('top.corpo', 'db_iframe_benstipodepreciacao', sUrlOpen, 'Pesquisa Depreciação', false); 
     }else{
       $("t64_benstipodepreciacao").value = ''; 
     }
  }
}

function js_preencheTipoDepreciacao(iSequencial, sDescricao) {

  $("t64_benstipodepreciacao").value = iSequencial;
  $("t46_descricao").value				   = sDescricao;
  db_iframe_benstipodepreciacao.hide();
}

function js_completaTipoDepreciacao(sDescricao, lErro) {

  $("t46_descricao").value = sDescricao;
  if (lErro) {
    $("t64_benstipodepreciacao").focus(); 
    $("t64_benstipodepreciacao").value = ""; 
  }
}


function js_pesquisat86_conplano(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_conplano','func_conplano.php?funcao_js=parent.js_mostraconplano1|c60_codcon|c60_descr','Pesquisa',true);
  }else{
     if(document.form1.t86_conplano.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_conplano','func_conplano.php?pesquisa_chave='+document.form1.t86_conplano.value+'&funcao_js=parent.js_mostraconplano','Pesquisa',false);
     }else{
       document.form1.descricaoconta.value = ''; 
     }
  }
}
function js_mostraconplano(chave,erro){
  document.form1.descricaoconta.value = chave; 
  if(erro==true){ 
    document.form1.t86_conplano.focus(); 
    document.form1.t86_conplano.value = ''; 
  }
}
function js_mostraconplano1(chave1,chave2){
  document.form1.t86_conplano.value = chave1;
  document.form1.descricaoconta.value = chave2;
  db_iframe_conplano.hide();
}

/**
 * métodos para pesquisa da conta para depreciacao
 */
function js_pesquisat86_conplanodepreciacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_conplano','func_conplano.php?funcao_js=parent.js_mostraconplanodepreciacao1|c60_codcon|c60_descr','Pesquisa',true);
  }else{
     if(document.form1.t86_conplanodepreciacao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_conplano','func_conplano.php?pesquisa_chave='+$F('t86_conplanodepreciacao')+'&funcao_js=parent.js_mostraconplanodepreciacao','Pesquisa',false);
     }else{
       document.form1.descricaocontadepreciacao.value = ''; 
     }
  }
}
function js_mostraconplanodepreciacao(sDescricao,lErro) {
  
  document.form1.descricaocontadepreciacao.value = sDescricao;
   
  if(lErro){ 
    $('t86_conplanodepreciacao').focus(); 
    $('t86_conplanodepreciacao').value = ''; 
  }
}

function js_mostraconplanodepreciacao1(iConta, sDescricao){
  
  $('t86_conplanodepreciacao').value   = iConta;
  $('descricaocontadepreciacao').value = sDescricao;
  db_iframe_conplano.hide();
}


function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_clabens','func_clabens002.php?funcao_js=parent.js_preenchepesquisa|t64_codcla','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_clabens.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
<?
if(isset($chavepesquisa)){
    echo "\njs_mascara03_t64_class(document.form1.t64_class.value);\n";
}
?>
</script>
<?
if(isset($focar)){
    echo "<script>
    document.form1.t64_descr.focus();
    </script>";
}
if(isset($err_estrutural)){
    db_msgbox($err_estrutural);
    echo "<script> document.form1.t64_class.style.backgroundColor='#99A9AE';</script>";
}
?>
<script>

$("t64_vidautil").addClassName("field-size2");
$("t64_vidautil").addClassName("field-size2");
$("t64_vidautil").addClassName("field-size2");
$("t64_vidautil").addClassName("field-size2");
$("t64_descr").addClassName("field-size9");
$("t86_conplano").addClassName("field-size2");
$("descricaoconta").addClassName("field-size7");
$("t86_conplanodepreciacao").addClassName("field-size2");
$("descricaocontadepreciacao").addClassName("field-size7");
$("t64_benstipodepreciacao").addClassName("field-size2");
$("t46_descricao").addClassName("field-size7");
$("t64_vidautil").addClassName("field-size2");

</script>