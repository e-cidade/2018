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
$cldb_syscadproceditem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("descricao");
$clrotulo->label("descrproced");
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
     $id_item = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tseqproitem?>">
       <?=@$Lseqproitem?>
    </td>
    <td> 
<?
if ( $db_opcao == 1 ){
	$seqproitem = null;
}
db_input('seqproitem',6,$Iseqproitem,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcodproced?>">
       <?
       db_ancora(@$Lcodproced,"js_pesquisacodproced(true);",3);
       ?>
    </td>
    <td> 
<?
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tid_item?>">
       <?
       db_ancora("Selecione os Itens","js_pesquisaid_item(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('id_item',5,$Iid_item,true,'hidden',$db_opcao," onchange='js_pesquisaid_item(false);'");
?>
<input name="itens" value="" type="hidden">
       <?
db_input('descricao',40,$Idescricao,true,'hidden',3,'')
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
    if( isset ($codproced)){
	 $chavepri= array("seqproitem"=>@$seqproitem,"codproced"=>@$codproced);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $cldb_syscadproceditem->sql_query(null,"*",null," db_syscadproceditem.codproced = $codproced");
	 $cliframe_alterar_excluir->campos  ="seqproitem,codproced,id_item,descricao";
	 $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";
	 $cliframe_alterar_excluir->iframe_height ="160";
	 $cliframe_alterar_excluir->iframe_width ="700";
	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    }
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
function js_pesquisaitemcad(item,modulo){
	document.form1.itens.value = document.form1.itens.value+"-"+item;
}
function js_pesquisaid_item(mostra){
    js_OpenJanelaIframe('top.corpo.iframe_db_syscadproceditem','db_iframe_db_syscadproceditem','con1_caditens002.php?proced=true','Pesquisa',true);
}
function js_mostradb_itensmenu(chave,erro){
  document.form1.descricao.value = chave; 
  if(erro==true){ 
    document.form1.id_item.focus(); 
    document.form1.id_item.value = ''; 
  }
}
function js_mostradb_itensmenu1(chave1,chave2){
  document.form1.id_item.value = chave1;
  document.form1.descricao.value = chave2;
  db_iframe_db_itensmenu.hide();
}
function js_pesquisacodproced(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_db_syscadproceditem','db_iframe_db_syscadproced','func_db_syscadproced.php?funcao_js=parent.js_mostradb_syscadproced1|codproced|descrproced','Pesquisa',true);
  }else{
     if(document.form1.codproced.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_db_syscadproceditem','db_iframe_db_syscadproced','func_db_syscadproced.php?pesquisa_chave='+document.form1.codproced.value+'&funcao_js=parent.js_mostradb_syscadproced','Pesquisa',false);
     }else{
       document.form1.descrproced.value = ''; 
     }
  }
}
function js_mostradb_syscadproced(chave,erro){
  document.form1.descrproced.value = chave; 
  if(erro==true){ 
    document.form1.codproced.focus(); 
    document.form1.codproced.value = ''; 
  }
}
function js_mostradb_syscadproced1(chave1,chave2){
  document.form1.codproced.value = chave1;
  document.form1.descrproced.value = chave2;
  db_iframe_db_syscadproced.hide();
}
</script>