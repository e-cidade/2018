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

//MODULO: diversos
$clprocdiver->rotulo->label();
$clprocdiver->rotulo->tlabel();
$clrotulo = new rotulocampo;

$clrotulo->label("k02_descr");
$clrotulo->label("k01_descr");
$clrotulo->label("v03_descr");
$clrotulo->label("k00_descr");

if ( $db_opcao == 1 ) {
  $db_action = "div1_procdiver004.php";
} elseif ( $db_opcao == 2 || $db_opcao == 22 ) {
  $db_action = "div1_procdiver005.php";
} elseif ( $db_opcao == 3 || $db_opcao == 33 ){
  $db_action = "div1_procdiver006.php";
} 

$sSqlArretipo  = "select k00_tipo, ";
$sSqlArretipo .= "       k00_descr ";
$sSqlArretipo .= "  from arretipo ";
$sSqlArretipo .= " where k03_tipo = 7 ";
$sSqlArretipo .= "   and k00_instit = ".db_getsession('DB_instit');

$rsTipos       = db_query($sSqlArretipo);
$iNumrowsTipo  = pg_num_rows($rsTipos);
$aTipos        = array();

$aTiposDebitos = db_utils::getCollectionByRecord($rsTipos); 

foreach ( $aTiposDebitos as $oTipoDebito ) {
  $aTipos[$oTipoDebito->k00_tipo] = $oTipoDebito->k00_tipo." - ".$oTipoDebito->k00_descr;
}

if ( count($aTipos) == 0 ) {
  
  db_msgbox(_M("tributario.diversos.db_frmprocdiver.sem_tipo_de_debito"));
  $db_opcao = 3;
  $db_botao = false;
}
?>
<BR>
<BR>

<style>
#dv09_tipo, #dv09_tipo_select_descr {
  width:100% !important;
}
</style>

<form class="container" name="form1" method="post" action="<?=$db_action?>">

<fieldset>
  <legend><?=$Lprocdiver; ?></legend>
  <table class="form-contianer">
      <tr>
        <td nowrap title="<?=@$Tdv09_procdiver?>">
          <?=@$Ldv09_procdiver?>
        </td>
        <td> 
          <?
            db_input('dv09_procdiver',6,$Idv09_procdiver,true,'text',3,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tdv09_descra?>">
           <?=@$Ldv09_descra?>
        </td>
        <td> 
          <?
            db_input('dv09_descra',40,$Idv09_descra,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tdv09_descr?>">
          <?=@$Ldv09_descr?>
        </td>
        <td> 
          <?
            db_input('dv09_descr',40,$Idv09_descr,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tdv09_receit?>">
           <?
             db_ancora(@$Ldv09_receit,"js_pesquisadv09_receit(true);",$db_opcao);
           ?>
        </td>
        <td> 
          <?
            db_input('dv09_receit',6 , $Idv09_receit, true, 'text', $db_opcao, " onchange='js_pesquisadv09_receit(false);'");
            db_input('k02_descr'  ,32, $Ik02_descr  , true, 'text', 3        , '')
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tdv09_hist?>">
           <?
           db_ancora(@$Ldv09_hist,"js_pesquisadv09_hist(true);",$db_opcao);
           ?>
        </td>
        <td> 
          <?
            db_input('dv09_hist',6 , $Idv09_hist, true, 'text', $db_opcao, " onchange='js_pesquisadv09_hist(false);'");
            db_input('k01_descr',32, $Ik01_descr, true, 'text', 3        , '')
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tdv09_proced?>">
          <?
            db_ancora(@$Ldv09_proced,"js_pesquisadv09_proced(true);",$db_opcao);
          ?>
        </td>
        <td> 
          <?
            db_input('dv09_proced', 6 , $Idv09_proced, true, 'text', $db_opcao, " onchange='js_pesquisadv09_proced(false);'");
            db_input('v03_descr'  , 32, $Iv03_descr  , true, 'text', 3        , '')
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tdv09_tipo?>">
          <?
            db_ancora(@$Ldv09_tipo,"js_pesquisadv09_tipo(true);",3);
          ?>
        </td>
        <td>
          <?
            db_select('dv09_tipo',$aTipos,true,$db_opcao);
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tdv09_dtlimite?>">
          <?
            echo @$Ldv09_dtlimite;
          ?>
        </td>
        <td>
          <?
            db_inputdata('dv09_dtlimite',@$dv09_dtlimite_dia,@$dv09_dtlimite_mes,@$dv09_dtlimite_ano, true,'text',$db_opcao);
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="<?= ( $db_opcao == 1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>

<script>

function js_pesquisadv09_receit(mostra){
  
  if ( mostra == true ) {
    js_OpenJanelaIframe('top.corpo.iframe_procdiver','db_iframe_tabrec','func_tabrec_todas.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_descr','Pesquisa',true,'0');
  } else {
    
     if ( document.form1.dv09_receit.value != '' ) { 
        js_OpenJanelaIframe('top.corpo.iframe_procdiver','db_iframe_tabrec','func_tabrec_todas.php?pesquisa_chave='+document.form1.dv09_receit.value+'&funcao_js=parent.js_mostratabrec','Pesquisa',false,'0','1','775','390');
     }else{
       document.form1.k02_descr.value = ''; 
     }
  }
}

function js_mostratabrec(chave,erro){
  
  document.form1.k02_descr.value = chave;
   
  if( erro == true ){
     
    document.form1.dv09_receit.focus(); 
    document.form1.dv09_receit.value = ''; 
  }
}

function js_mostratabrec1(chave1,chave2) {
  
  document.form1.dv09_receit.value = chave1;
  document.form1.k02_descr.value = chave2;
  db_iframe_tabrec.hide();
}

function js_pesquisadv09_hist( mostra ) {
  
  if( mostra == true ){
    js_OpenJanelaIframe('top.corpo.iframe_procdiver','db_iframe_histcalc','func_histcalc.php?funcao_js=parent.js_mostrahistcalc1|k01_codigo|k01_descr','Pesquisa',true,'0');
  }else{
    
     if ( document.form1.dv09_hist.value != '' ) { 
        js_OpenJanelaIframe('top.corpo.iframe_procdiver','db_iframe_histcalc','func_histcalc.php?pesquisa_chave='+document.form1.dv09_hist.value+'&funcao_js=parent.js_mostrahistcalc','Pesquisa',false,'0','1','775','390');
     }else{
       document.form1.k01_descr.value = ''; 
     }
  }
}

function js_mostrahistcalc(chave,erro) {
  
  document.form1.k01_descr.value = chave; 

  if( erro == true ) {
     
    document.form1.dv09_hist.focus(); 
    document.form1.dv09_hist.value = ''; 
  }
}

function js_mostrahistcalc1( chave1, chave2 ) {
  
  document.form1.dv09_hist.value = chave1;
  document.form1.k01_descr.value = chave2;
  db_iframe_histcalc.hide();
}

function js_pesquisadv09_proced( mostra ) {
  
  if( mostra == true ) {
    js_OpenJanelaIframe('top.corpo.iframe_procdiver','db_iframe_proced','func_proced.php?funcao_js=parent.js_mostraproced1|v03_codigo|v03_descr','Pesquisa',true,'0');
  }else{
    
     if ( document.form1.dv09_proced.value != '' ) { 
        js_OpenJanelaIframe('top.corpo.iframe_procdiver','db_iframe_proced','func_proced.php?pesquisa_chave='+document.form1.dv09_proced.value+'&funcao_js=parent.js_mostraproced','Pesquisa',false,'0','1','775','390');
     }else{
       document.form1.v03_descr.value = ''; 
     }
  }
}

function js_mostraproced( chave, erro ) {
  
  document.form1.v03_descr.value = chave;
   
  if( erro == true ) { 
    
    document.form1.dv09_proced.focus(); 
    document.form1.dv09_proced.value = ''; 
  }
}

function js_mostraproced1( chave1, chave2 ) {
  
  document.form1.dv09_proced.value = chave1;
  document.form1.v03_descr.value = chave2;
  db_iframe_proced.hide();
}

function js_pesquisadv09_tipo( mostra ) {
  
  if( mostra == true ){
    js_OpenJanelaIframe('top.corpo.iframe_procdiver','db_iframe_arretipo','func_arretipo.php?funcao_js=parent.js_mostraarretipo1|k00_tipo|k00_descr','Pesquisa',true,'0');
  }else{
    
     if ( document.form1.dv09_tipo.value != '' ) { 
       js_OpenJanelaIframe('top.corpo.iframe_procdiver','db_iframe_arretipo','func_arretipo.php?pesquisa_chave='+document.form1.dv09_tipo.value+'&funcao_js=parent.js_mostraarretipo','Pesquisa',false,'0','1','775','390');
     }else{
       document.form1.k00_descr.value = ''; 
     }
  }
}

function js_mostraarretipo( chave, erro ) {
  
  document.form1.k00_descr.value = chave;
   
  if( erro == true ) {
     
    document.form1.dv09_tipo.focus(); 
    document.form1.dv09_tipo.value = ''; 
  }
}

function js_mostraarretipo1( chave1, chave2 ) {
  
  document.form1.dv09_tipo.value = chave1;
  document.form1.k00_descr.value = chave2;
  db_iframe_arretipo.hide();
}

function js_pesquisa() {
  js_OpenJanelaIframe('top.corpo.iframe_procdiver','db_iframe_procdiver','func_procdiver.php?chave_mostratodas=true&funcao_js=parent.js_preenchepesquisa|dv09_procdiver','Pesquisa',true,'0');
}

function js_preenchepesquisa( chave ) {
  
  db_iframe_procdiver.hide();
  <?
  if( $db_opcao != 1 ) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chave_mostratodas=true&chavepesquisa='+chave";
  }
  ?>
}
</script>
<script>

$("dv09_procdiver").addClassName("field-size2");
$("dv09_descra").addClassName("field-size9");
$("dv09_descr").addClassName("field-size9");
$("dv09_receit").addClassName("field-size2");
$("k02_descr").addClassName("field-size7");
$("dv09_hist").addClassName("field-size2");
$("k01_descr").addClassName("field-size7");
$("dv09_proced").addClassName("field-size2");
$("v03_descr").addClassName("field-size7");
$("dv09_dtlimite").addClassName("field-size2");

</script>