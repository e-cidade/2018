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

//MODULO: configuracoes
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$cldb_versaousu->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db30_codversao");
$clrotulo->label("db30_codrelease");
$clrotulo->label("descricao");
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
    if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){
     $db32_codver = "";
     $db32_id_item = "";
     $db32_obs = "";
     $db32_obsdb = "";
     $db32_data = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
<?
db_input('db32_codusu',6,$Idb32_codusu,true,'hidden',3,"")
?>
  <tr>
    <td nowrap title="<?=@$Tdb30_codversao?>">
       <?=@$Ldb30_codversao?>
    </td>
    <td> 
<?
db_input('db30_codversao',6,$Idb30_codversao,true,'text',3)
?>/
       <?
db_input('db30_codrelease',6,$Idb30_codrelease,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb32_id_item?>">
       <?
       db_ancora(@$Ldb32_id_item,"js_pesquisadb32_id_item(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('db32_id_item',5,$Idb32_id_item,true,'text',$db_opcao," onchange='js_pesquisadb32_id_item(false);'")
?>
       <?
//       echo @$descricao.' descricao  ';
db_input('descricao',40,$Idescricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb32_obsdb?>">
       <?=@$Ldb32_obsdb?>
    </td>
    <td> 
<?
db_textarea('db32_obsdb',5,70,$Idb32_obsdb,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb32_obs?>">
       <?=@$Ldb32_obs?>
    </td>
    <td> 
<?
db_textarea('db32_obs',5,70,$Idb32_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb32_data?>">
       <?=@$Ldb32_data?>
    </td>
    <td> 
<?
if(!isset($db32_data_dia)){
   $db32_data_dia     = date('d',db_getsession("DB_datausu") );
   $db32_data_mes  = date('m',db_getsession("DB_datausu") );
   $db32_data_ano   = date('Y',db_getsession("DB_datausu") );
}

db_inputdata('db32_data',@$db32_data_dia,@$db32_data_mes,@$db32_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </tr>
    <td colspan="2" align="center">
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
 <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
  </table>
 <table>
  <tr>
    <td valign="top"  align="center">  
    <?
//echo $cldb_versaousu->sql_query(null,"*","","db32_codver=$db30_codver");
	 $chavepri= array("db32_codusu"=>@$db32_codusu);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
//	 echo ' codver '.$db32_codver."\n";
	 $cliframe_alterar_excluir->sql     = $cldb_versaousu->sql_query(null,"*","","db32_codver=$db30_codver");
	 $cliframe_alterar_excluir->campos  ="db30_codversao,db30_codrelease,db32_id_item,db32_obs,db32_obsdb,db32_data";
	 $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";
	 $cliframe_alterar_excluir->iframe_height ="160";
	 $cliframe_alterar_excluir->iframe_width ="700";
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
function js_pesquisadb32_codver(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_db_versaousu','db_iframe_db_versao','func_db_versao.php?funcao_js=parent.js_mostradb_versao1|db30_codver|db30_codversao','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.db32_codver.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_db_versaousu','db_iframe_db_versao','func_db_versao.php?pesquisa_chave='+document.form1.db32_codver.value+'&funcao_js=parent.js_mostradb_versao','Pesquisa',false);
     }else{
       document.form1.db30_codversao.value = ''; 
     }
  }
}
function js_mostradb_versao(chave,erro){
  document.form1.db30_codversao.value = chave; 
  if(erro==true){ 
    document.form1.db32_codver.focus(); 
    document.form1.db32_codver.value = ''; 
  }
}
function js_mostradb_versao1(chave1,chave2){
  document.form1.db32_codver.value = chave1;
  document.form1.db30_codversao.value = chave2;
  db_iframe_db_versao.hide();
}

function js_pesquisaitemcad(item,modulo){
   document.form1.db32_id_item.value = item;
   js_OpenJanelaIframe('top.corpo.iframe_db_versaousu','db_iframe_db_itensmenu','func_db_itensmenu.php?pesquisa_chave='+item+'&funcao_js=parent.js_mostradb_itensmenu1','Pesquisa',false);
}

function js_pesquisadb32_id_item(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_db_versaousu','db_iframe_db_itensmenu','con1_caditens002.php','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.db32_id_item.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_db_versaousu','db_iframe_db_itensmenu','con1_cadites002.php','Pesquisa',false);
     }else{
       document.form1.descricao.value = ''; 
     }
  }
}

function js_mostradb_itensmenu(chave,erro){
  document.form1.descricao.value = chave; 
  if(erro==true){ 
    document.form1.db32_id_item.focus(); 
    document.form1.db32_id_item.value = ''; 
  }
}
function js_mostradb_itensmenu1(chave1,chave2){
  document.form1.descricao.value = chave1;
  db_iframe_db_itensmenu.hide();
}
</script>