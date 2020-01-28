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

//MODULO: Trânsito
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clvitimas_acid->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("tr07_id");
$clrotulo->label("tr06_descr");
if(isset($db_opcaoal)){
   $db_opcao=33;
    $db_botao=false;
}else if(isset($opcao) && $opcao=="alterar"){
    $db_botao=true;
    $db_opcao = 2;
}else if(isset($opcao) && $opcao=="excluir"){
    $db_opcao = 3;
    $db_botao=true;
}else{  
    $db_opcao = 1;
    $db_botao=true;
    if(isset($novo) || isset($_self) && $_self!=""){
     $k41_arretipo = "";
     $k00_descr = "";
   }
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ttr10_id?>">
       <?=@$Ltr10_id?>
    </td>
    <td> 
<?
db_input('tr10_id',5,$Itr10_id,true,'text',3,"")
?>
    </td>
  </tr>
  
    </tr>
  <tr>
    <td nowrap title="<?=@$Ttr10_idacidente?>">
       <?=@$Ltr10_idacidente?>
    </td>
    <td>
<?
db_input('tr10_idacidente',5,$Itr10_idacidente,true,'text',3,"")
?>
    </td>
  </tr>
  
  
   <tr>
    <td nowrap title="<?=@$Ttr10_idvitima?>">
       <?
       db_ancora(@$Ltr10_idvitima,"js_pesquisatr10_idvitima(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('tr10_idvitima',5,$Itr10_idvitima,true,'text',$db_opcao," onchange='js_pesquisatr10_idvitima(false);'")
?>
       <?
db_input('tr06_descr',35,$Itr06_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttr10_nome?>">
       <?=@$Ltr10_nome?>
    </td>
    <td> 
<?
db_input('tr10_nome',30,$Itr10_nome,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttr10_sexo?>">
       <?=@$Ltr10_sexo?>
    </td>
    <td> 
<?
$x = array('1'=>'Feminino','2'=>'Masculino','NI'=>'NI');
db_select('tr10_sexo',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttr10_idade?>">
       <?=@$Ltr10_idade?>
    </td>
    <td> 
<?
db_input('tr10_idade',5,$Itr10_idade,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttr10_situacao?>">
       <?=@$Ltr10_situacao?>
    </td>
    <td> 
<?
db_input('tr10_situacao',1,$Itr10_situacao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
    <tr>
  <td colspan="2" align="center">
    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
    <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?>>
  </td>
  </tr>
  </table>
  <table>
  <tr>
    <td valign="top"  align="center">
       <? 
         $sCampos = "tr06_descr,
                     tr10_id,
                     tr10_situacao,
                     tr10_nome,
                     (case when tr10_sexo = 1 then 'Feminino'
                           when tr10_sexo = 2 then 'Masculino'
                           when tr10_sexo = 3 then 'NI' end ) as tr10_sexo,
                     tr10_idade";
         $chavepri= array("tr10_id"=>@$tr10_id);
         $cliframe_alterar_excluir->chavepri=$chavepri;
         $cliframe_alterar_excluir->sql     = $clvitimas_acid->sql_query(null,$sCampos,"tr10_id","tr10_idacidente = $tr10_idacidente");
         $cliframe_alterar_excluir->campos  = "tr06_descr,tr10_id,tr10_situacao,tr10_nome,tr10_sexo";
         $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";
         $cliframe_alterar_excluir->iframe_height ="200";
         $cliframe_alterar_excluir->iframe_width ="750";
         $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
       ?>   
    </td>
   </tr>
 </table>
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
function js_pesquisatr10_idacidente(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_acidentes','func_acidentes.php?funcao_js=parent.js_mostraacidentes1|tr07_id|tr07_id','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_acidentes','func_acidentes.php?pesquisa_chave='+document.form1.tr10_idacidente.value+'&funcao_js=parent.js_mostraacidentes','Pesquisa',false);
  }
}
function js_mostraacidentes(chave,erro){
  document.form1.tr07_id.value = chave; 
  if(erro==true){
    document.form1.tr10_idacidente.focus(); 
    document.form1.tr10_idacidente.value = ''; 
  }
}
function js_mostraacidentes1(chave1,chave2){
  document.form1.tr10_idacidente.value = chave1;
  document.form1.tr07_id.value = chave2;
  db_iframe_acidentes.hide();
}
function js_pesquisatr10_idvitima(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tipo_vitimas','func_tipo_vitimas.php?funcao_js=parent.js_mostratipo_vitimas1|tr06_id|tr06_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_tipo_vitimas','func_tipo_vitimas.php?pesquisa_chave='+document.form1.tr10_idvitima.value+'&funcao_js=parent.js_mostratipo_vitimas','Pesquisa',false);
  }
}
function js_mostratipo_vitimas(chave,erro){
  document.form1.tr06_descr.value = chave; 
  if(erro==true){
    document.form1.tr10_idvitima.focus(); 
    document.form1.tr10_idvitima.value = '';
  }
}
function js_mostratipo_vitimas1(chave1,chave2){
  document.form1.tr10_idvitima.value = chave1;
  document.form1.tr06_descr.value = chave2;
  db_iframe_tipo_vitimas.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_vitimas_acid','func_vitimas_acid.php?funcao_js=parent.js_preenchepesquisa|tr10_id','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_vitimas_acid.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>