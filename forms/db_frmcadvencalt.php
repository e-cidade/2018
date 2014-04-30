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

include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

$clcadvenc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("q92_descr");
$clrotulo->label("k01_descr");
if(isset($db_opcaoal)){
    $db_opcao=3;
      $db_botao=false;
}else{
  $db_botao=true;
}
if(isset($opcao) && $opcao=="alterar"){
    $db_opcao = 2;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
    $db_opcao = 3;
    if(isset($db_opcaoal)){
	$db_opcao=33;
    }
}else{  
    $db_opcao = 1;
    $db_botao=true;
    if(isset($novo)){
      $q82_parc="";
      $q82_venc_dia="";
      $q82_venc_mes="";
      $q82_venc_ano="";
      $q82_desc="";
      $q82_perc="";
      $q82_hist="";
      $k01_descr="";
    }
    
} 
if(empty($excluir) && empty($alterar) && isset($opcao) && $opcao!="" && empty($db_opcaoal)){
  $result19=$clcadvenc->sql_record($clcadvenc->sql_query_file($q82_codigo,$q82_parc,'cadvenc.*'));
  db_fieldsmemory($result19,0);
}  
?>
<form name="form1" method="post" action="">
<table border="0">
<tr>
  <td>
<center>
<table border="0" width="">
  <tr>
    <td nowrap title="<?=@$Tq82_codigo?>">
       <?=@$Lq82_codigo?>
    </td>
    <td> 
<?
db_input('q82_codigo',10,$Iq82_codigo,true,'text',3);
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq82_parc?>">
       <?=@$Lq82_parc?>
    </td>
    <td> 
<?
db_input('q82_parc',10,$Iq82_parc,true,'text',3)
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq82_venc?>">
       <?=@$Lq82_venc?>
    </td>
    <td> 
<?
db_inputdata('q82_venc',@$q82_venc_dia,@$q82_venc_mes,@$q82_venc_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq82_desc?>">
       <?=@$Lq82_desc?>
    </td>
    <td> 
<?
db_input('q82_desc',40,$Iq82_desc,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq82_perc?>">
       <?=@$Lq82_perc?>
    </td>
    <td> 
<?
db_input('q82_perc',10,$Iq82_perc,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq82_hist?>">
       <?
       db_ancora(@$Lq82_hist,"js_pesquisaq82_hist(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q82_hist',10,$Iq82_hist,true,'text',$db_opcao," onchange='js_pesquisaq82_hist(false);'")
?>
       <?
db_input('k01_descr',26,$Ik01_descr,true,'text',3,'')
       ?>
    </td>
  </tr>

<tr>
    <td nowrap title="<?=@$Tq82_calculaparcvenc?>">
       <?=$Lq82_calculaparcvenc?>
    </td>
    <td>
      <?
        $xw = array(
                     't'=>"Sim",
                     'f'=>"Nao"
                   );
        db_select('q82_calculaparcvenc',$xw,true,$db_opcao,"");
      ?>
    </td>
  </tr>




  <tr>
    <td colspan="2" align="center">
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
      
      <input name="soma_ano" type="submit" id="soma_ano" value="Soma Ano" <?=($db_botao==false?"disabled":"")?> >
      
      <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
<table>
</td>
</tr>
<tr>
  <td valign="top" colspan="2">  
   <?
    $chavepri= array("q82_codigo"=>$q82_codigo,"q82_parc"=>@$q82_parc);
    $cliframe_alterar_excluir->chavepri=$chavepri;
    $cliframe_alterar_excluir->sql     = $clcadvenc->sql_query_file($q82_codigo,"","q82_codigo,q82_parc,q82_venc,q82_desc,q82_perc,q82_hist, case when q82_calculaparcvenc is true then 'Sim' else 'Nao' end as q82_calculaparcvenc","q82_parc");
    $cliframe_alterar_excluir->campos  ="q82_codigo,q82_parc,q82_venc,q82_desc,q82_perc,q82_hist,q82_calculaparcvenc ";
    $cliframe_alterar_excluir->legenda="DATAS DOS VENCIMENTOS";
    $cliframe_alterar_excluir->iframe_height ="170";
    $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);    
   ?>
   </td>
  </tr>
</table>
</center>
</form>
<script>
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
function js_pesquisaq82_hist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_cadvenc','db_iframe_histcalc','func_histcalc.php?funcao_js=parent.js_mostrahistcalc1|k01_codigo|k01_descr','Pesquisa',true,"0");
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_cadvenc','db_iframe_histcalc','func_histcalc.php?pesquisa_chave='+document.form1.q82_hist.value+'&funcao_js=parent.js_mostrahistcalc','Pesquisa',false,"0");
  }
}
function js_mostrahistcalc(chave,erro){
  document.form1.k01_descr.value = chave; 
  if(erro==true){ 
    document.form1.q82_hist.focus(); 
    document.form1.q82_hist.value = ''; 
  }
}
function js_mostrahistcalc1(chave1,chave2){
  document.form1.q82_hist.value = chave1;
  document.form1.k01_descr.value = chave2;
  db_iframe_histcalc.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_cadvenc','db_iframe_cadvenc','func_cadvenc.php?funcao_js=parent.js_preenchepesquisa|q82_codigo|1','Pesquisa',true,"0");
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_cadvenc.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>
}  
</script>