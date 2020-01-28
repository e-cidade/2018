<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
$clrotulo->label("db77_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
<?
$r11_anousu = db_anofolha();
$r11_mesusu = db_mesfolha();
db_input('r11_anousu',4,$Ir11_anousu,true,'hidden',$db_opcao,"");
db_input('r11_mesusu',2,$Ir11_mesusu,true,'hidden',$db_opcao,"");
?>
  <tr>
    <td nowrap title="<?=@$Tr11_codaec?>">
       <?=@$Lr11_codaec?>
    </td>
    <td> 
<?
db_input('r11_codaec',5,$Ir11_codaec,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_natest?>">
       <?=@$Lr11_natest?>
    </td>
    <td> 
<?
db_input('r11_natest',4,$Ir11_natest,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_cdfpas?>">
       <?=@$Lr11_cdfpas?>
    </td>
    <td> 
<?
db_input('r11_cdfpas',4,$Ir11_cdfpas,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_cdactr?>">
       <?=@$Lr11_cdactr?>
    </td>
    <td> 
<?
db_input('r11_cdactr',7,$Ir11_cdactr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_peactr?>">
       <?=@$Lr11_peactr?>
    </td>
    <td> 
<?
db_input('r11_peactr',15,$Ir11_peactr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_pctemp?>">
       <?=@$Lr11_pctemp?>
    </td>
    <td> 
<?
db_input('r11_pctemp',15,$Ir11_pctemp,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_pcterc?>">
       <?=@$Lr11_pcterc?>
    </td>
    <td> 
<?
db_input('r11_pcterc',15,$Ir11_pcterc,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_fgts12?>">
       <?=@$Lr11_fgts12?>
    </td>
    <td> 
<?
db_input('r11_fgts12',1,$Ir11_fgts12,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_cdcef?>">
       <?=@$Lr11_cdcef?>
    </td>
    <td> 
<?
db_input('r11_cdcef',5,$Ir11_cdcef,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_cdfgts?>">
       <?=@$Lr11_cdfgts?>
    </td>
    <td> 
<?
db_input('r11_cdfgts',8,$Ir11_cdfgts,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_ultger?>">
       <?=@$Lr11_ultger?>
    </td>
    <td> 
<?
db_inputdata('r11_ultger',@$r11_ultger_dia,@$r11_ultger_mes,@$r11_ultger_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_ultfec?>">
       <?=@$Lr11_ultfec?>
    </td>
    <td> 
<?
db_inputdata('r11_ultfec',@$r11_ultfec_dia,@$r11_ultfec_mes,@$r11_ultfec_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_arredn?>">
       <?=@$Lr11_arredn?>
    </td>
    <td> 
<?
db_input('r11_arredn',1,$Ir11_arredn,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_sald13?>">
       <?=@$Lr11_sald13?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('r11_sald13',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_datai?>">
       <?=@$Lr11_datai?>
    </td>
    <td> 
<?
db_inputdata('r11_datai',@$r11_datai_dia,@$r11_datai_mes,@$r11_datai_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_dataf?>">
       <?=@$Lr11_dataf?>
    </td>
    <td> 
<?
db_inputdata('r11_dataf',@$r11_dataf_dia,@$r11_dataf_mes,@$r11_dataf_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_fecha?>">
       <?=@$Lr11_fecha?>
    </td>
    <td> 
<?
db_input('r11_fecha',12,$Ir11_fecha,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_ultreg?>">
       <?=@$Lr11_ultreg?>
    </td>
    <td> 
<?
db_input('r11_ultreg',6,$Ir11_ultreg,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_codipe?>">
       <?=@$Lr11_codipe?>
    </td>
    <td> 
<?
db_input('r11_codipe',3,$Ir11_codipe,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_mes13?>">
       <?=@$Lr11_mes13?>
    </td>
    <td> 
<?
db_input('r11_mes13',2,$Ir11_mes13,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_tbprev?>">
       <?=@$Lr11_tbprev?>
    </td>
    <td> 
<?
db_input('r11_tbprev',1,$Ir11_tbprev,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_confer?>">
       <?=@$Lr11_confer?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('r11_confer',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_valor?>">
       <?=@$Lr11_valor?>
    </td>
    <td> 
<?
db_input('r11_valor',15,$Ir11_valor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_dtipe?>">
       <?=@$Lr11_dtipe?>
    </td>
    <td> 
<?
db_input('r11_dtipe',1,$Ir11_dtipe,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_implan?>">
       <?=@$Lr11_implan?>
    </td>
    <td> 
<?
db_input('r11_implan',7,$Ir11_implan,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_subpes?>">
       <?=@$Lr11_subpes?>
    </td>
    <td> 
<?
db_input('r11_subpes',7,$Ir11_subpes,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_rubmat?>">
       <?=@$Lr11_rubmat?>
    </td>
    <td> 
<?
db_input('r11_rubmat',4,$Ir11_rubmat,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_eleina?>">
       <?=@$Lr11_eleina?>
    </td>
    <td> 
<?
db_input('r11_eleina',12,$Ir11_eleina,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_elepen?>">
       <?=@$Lr11_elepen?>
    </td>
    <td> 
<?
db_input('r11_elepen',12,$Ir11_elepen,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_rubnat?>">
       <?=@$Lr11_rubnat?>
    </td>
    <td> 
<?
db_input('r11_rubnat',4,$Ir11_rubnat,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_rubdec?>">
       <?=@$Lr11_rubdec?>
    </td>
    <td> 
<?
db_input('r11_rubdec',4,$Ir11_rubdec,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_qtdcal?>">
       <?=@$Lr11_qtdcal?>
    </td>
    <td> 
<?
db_input('r11_qtdcal',3,$Ir11_qtdcal,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_palime?>">
       <?=@$Lr11_palime?>
    </td>
    <td> 
<?
db_input('r11_palime',4,$Ir11_palime,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_altfer?>">
       <?=@$Lr11_altfer?>
    </td>
    <td> 
<?
db_input('r11_altfer',7,$Ir11_altfer,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_ferias?>">
       <?=@$Lr11_ferias?>
    </td>
    <td> 
<?
db_input('r11_ferias',4,$Ir11_ferias,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_fer13?>">
       <?=@$Lr11_fer13?>
    </td>
    <td> 
<?
db_input('r11_fer13',4,$Ir11_fer13,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_ferant?>">
       <?=@$Lr11_ferant?>
    </td>
    <td> 
<?
db_input('r11_ferant',4,$Ir11_ferant,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_fer13o?>">
       <?=@$Lr11_fer13o?>
    </td>
    <td> 
<?
db_input('r11_fer13o',4,$Ir11_fer13o,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_fer13a?>">
       <?=@$Lr11_fer13a?>
    </td>
    <td> 
<?
db_input('r11_fer13a',4,$Ir11_fer13a,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_ferabo?>">
       <?=@$Lr11_ferabo?>
    </td>
    <td> 
<?
db_input('r11_ferabo',4,$Ir11_ferabo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_feabot?>">
       <?=@$Lr11_feabot?>
    </td>
    <td> 
<?
db_input('r11_feabot',4,$Ir11_feabot,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_feradi?>">
       <?=@$Lr11_feradi?>
    </td>
    <td> 
<?
db_input('r11_feradi',4,$Ir11_feradi,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_fadiab?>">
       <?=@$Lr11_fadiab?>
    </td>
    <td> 
<?
db_input('r11_fadiab',4,$Ir11_fadiab,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_recalc?>">
       <?=@$Lr11_recalc?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('r11_recalc',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_pagaab?>">
       <?=@$Lr11_pagaab?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('r11_pagaab',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_fersal?>">
       <?=@$Lr11_fersal?>
    </td>
    <td> 
<?
db_input('r11_fersal',1,$Ir11_fersal,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_vtprop?>">
       <?=@$Lr11_vtprop?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('r11_vtprop',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_desliq?>">
       <?=@$Lr11_desliq?>
    </td>
    <td> 
<?
db_input('r11_desliq',20,$Ir11_desliq,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_propae?>">
       <?=@$Lr11_propae?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('r11_propae',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_propac?>">
       <?=@$Lr11_propac?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('r11_propac',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_codestrut?>">
       <?
       db_ancora(@$Lr11_codestrut,"js_pesquisar11_codestrut(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('r11_codestrut',6,$Ir11_codestrut,true,'text',$db_opcao," onchange='js_pesquisar11_codestrut(false);'")
?>
       <?
db_input('db77_descr',40,$Idb77_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_geracontipe?>">
       <?=@$Lr11_geracontipe?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('r11_geracontipe',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_13ferias?>">
       <?=@$Lr11_13ferias?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('r11_13ferias',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_pagarferias?>">
       <?=@$Lr11_pagarferias?>
    </td>
    <td> 
<?
$x = array('S'=>'Salário','C'=>'Complementar');
db_select('r11_pagarferias',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_vtfer?>">
       <?=@$Lr11_vtfer?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('r11_vtfer',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_vtcons?>">
       <?=@$Lr11_vtcons?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('r11_vtcons',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_vtmpro?>">
       <?=@$Lr11_vtmpro?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('r11_vtmpro',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_localtrab?>">
       <?=@$Lr11_localtrab?>
    </td>
    <td> 
<?
db_input('r11_localtrab',8,$Ir11_localtrab,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr11_databaseatra?>">
       <?=@$Lr11_databaseatra?>
    </td>
    <td> 
<?
db_inputdata('r11_databaseatra',@$r11_databaseatra_dia,@$r11_databaseatra_mes,@$r11_databaseatra_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisar11_codestrut(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_estrutura','func_db_estrutura.php?funcao_js=parent.js_mostradb_estrutura1|db77_codestrut|db77_descr','Pesquisa',true);
  }else{
     if(document.form1.r11_codestrut.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_estrutura','func_db_estrutura.php?pesquisa_chave='+document.form1.r11_codestrut.value+'&funcao_js=parent.js_mostradb_estrutura','Pesquisa',false);
     }else{
       document.form1.db77_descr.value = ''; 
     }
  }
}
function js_mostradb_estrutura(chave,erro){
  document.form1.db77_descr.value = chave; 
  if(erro==true){ 
    document.form1.r11_codestrut.focus(); 
    document.form1.r11_codestrut.value = ''; 
  }
}
function js_mostradb_estrutura1(chave1,chave2){
  document.form1.r11_codestrut.value = chave1;
  document.form1.db77_descr.value = chave2;
  db_iframe_db_estrutura.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_cfpess','func_cfpess.php?funcao_js=parent.js_preenchepesquisa|r11_anousu|r11_mesusu','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_cfpess.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>