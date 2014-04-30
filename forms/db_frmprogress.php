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
$clprogress->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("r02_descr");
if(!isset($r24_anousu) || (isset($r24_anousu) && trim($r24_anousu) == "")){
  $r24_anousu = db_anofolha();
}
if(!isset($r24_mesusu) || (isset($r24_mesusu) && trim($r24_mesusu) == "")){
  $r24_mesusu = db_mesfolha();
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tr24_regime?>">
      <?=@$Lr24_regime?>
    </td>
    <td colspan="2"> 
      <?
      $r24_progr = "  ";
      $r24_ano = "0";
      $result_regime = $clrhcadregime->sql_record($clrhcadregime->sql_query_file(null,"*"));
      db_selectrecord("r24_regime",$result_regime,true,($db_opcao == 1?1:3));
      db_input('r24_anousu',4,$Ir24_anousu,true,'hidden',3,"");
      db_input('r24_mesusu',2,$Ir24_mesusu,true,'hidden',3,"");
      db_input('r24_progr',2,$Ir24_progr,true,'hidden',3,"");
      db_input('r24_ano',2,$Ir24_ano,true,'hidden',3,"");
      ?>
 	  <input type='hidden' value='<?=$r24_meses?>' name='r24_mes'>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr24_padrao?>">
      <?
      db_ancora($Lr24_padrao,"js_pesquisar24_padrao(true)",($db_opcao == 1?1:3));
      ?>
    </td>
    <td colspan="2"> 
      <?
      db_input('r24_padrao',10,$Ir24_padrao,true,'text',($db_opcao == 1?1:3),"onchange='js_pesquisar24_padrao(false);'");
      db_input('r02_descr',30,$Ir02_descr,true,'text',3,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr24_descr?>">
      <?=@$Lr24_descr?>
    </td>
    <td colspan="2"> 
      <?
      db_input('r24_descr',43,$Ir24_descr,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr24_meses?>">
      <?=@$Lr24_meses?>
    </td>
    <td> 
      <?
      db_input('r24_meses',10,$Ir24_meses,true,'text',$db_opcao,"onchange='js_calculaano(this.value);'");
      ?>
    </td>
    <td nowrap align="left" id="anos">
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr24_perc?>">
      <?=@$Lr24_perc?>
    </td>
    <td colspan="2"> 
      <?
      db_input('r24_perc',10,$Ir24_perc,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr24_valor?>">
      <?=@$Lr24_valor?>
    </td>
    <td colspan="2"> 
      <?
      db_input('r24_valor',10,$Ir24_valor,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_testarcampos();">
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" onblur="js_tabular('<?=$db_opcao?>')">
</form>
<script>
function js_testarcampos(){
  if(document.form1.r24_padrao.value == ""){
    alert("Informe o código do padrão.");
    document.form1.r24_padrao.focus();
  }else if(document.form1.r24_descr.value == ""){
    alert("Informe a descrição da progressão.");
    document.form1.r24_descr.focus();
  }else if(document.form1.r24_meses.value == ""){
    alert("Informe a quantidade de meses para cálculo da progressão.");
    document.form1.r24_meses.focus();
  }else if(document.form1.r24_perc.value == ""){
    alert("Informe o percentual incidente na progressão.");
    document.form1.r24_perc.focus();
  }else{
    if(document.form1.r24_valor.value == ""){
      document.form1.r24_valor.value = "0";
    }
    return true;
  }
  return false;
}
function js_pesquisar24_padrao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_padroes','func_padroes.php?funcao_js=parent.js_mostrapadrao1|r02_codigo|r02_descr&regime='+document.form1.r24_regime.value+'&chave_r02_anousu='+document.form1.r24_anousu.value+'&chave_r02_mesusu='+document.form1.r24_mesusu.value,'Pesquisa',true,'20');
  }else{
    if(document.form1.r24_padrao.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_padroes','func_padroes.php?pesquisa_chave='+document.form1.r24_padrao.value+'&funcao_js=parent.js_mostrapadrao&regime='+document.form1.r24_regime.value+'&chave_r02_anousu='+document.form1.r24_anousu.value+'&chave_r02_mesusu='+document.form1.r24_mesusu.value,'Pesquisa',false,'0');
    }else{
      document.form1.r24_padrao.value = '';
      document.form1.r02_descr.value  = '';
    }
  }
}
function js_mostrapadrao(chave,erro){
  document.form1.r02_descr.value = chave; 
  if(erro==true){ 
    document.form1.r24_padrao.focus(); 
    document.form1.r24_padrao.value = ''; 
  }else{
    if(document.form1.r24_descr.value == ""){
      document.form1.r24_descr.value = chave;
    }
  }
}
function js_mostrapadrao1(chave1,chave2){
  document.form1.r24_padrao.value = chave1;
  document.form1.r02_descr.value  = chave2;
  if(document.form1.r24_descr.value == ""){
    document.form1.r24_descr.value = chave2;
  }
  db_iframe_padroes.hide();
}
function js_tabular(opcao){
  if(opcao == 1){
    js_tabulacaoforms("form1","r24_regime",true,1,"r24_regime",true);
  }else if(opcao == 2 || opcao == 22){
    js_tabulacaoforms("form1","r24_descr",true,1,"r24_descr",true);
  }else{
    js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
  }
}
function js_calculaano(ano){
  ano = new Number(ano);
  if(!isNaN(ano) && ano > 0){
    ano /= 12.0;
    document.getElementById("anos").innerHTML = "<font color='red'>"+ano.toFixed(2)+" anos</font>";
  }else{
    document.getElementById("anos").innerHTML = "";
  }
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_progress','func_progress.php?funcao_js=parent.js_preenchepesquisa|r24_anousu|r24_mesusu|r24_regime|r24_padrao|r24_meses','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1,chave2,chave3,chave4){
  db_iframe_progress.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1+'&chavepesquisa2='+chave2+'&chavepesquisa3='+chave3+'&chavepesquisa4='+chave4";
  }
  ?>
}
js_calculaano(document.form1.r24_meses.value);
</script>