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
$cldb_relatquebra->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db91_descr");
$clrotulo->label("nomecam");
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
     $db97_codrel = "";
     $db97_codcam = "";
     $db97_totaliza = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb97_codigo?>">
       <?=@$Ldb97_codigo?>
    </td>
    <td> 
<?
db_input('db97_codigo',10,$Idb97_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb97_codrel?>">
       <?
       db_ancora(@$Ldb97_codrel,"js_pesquisadb97_codrel(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('db97_codrel',10,$Idb97_codrel,true,'text',$db_opcao," onchange='js_pesquisadb97_codrel(false);'")
?>
       <?
db_input('db91_descr',40,$Idb91_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb97_codcam?>">
       <?
       db_ancora(@$Ldb97_codcam,"js_pesquisadb97_codcam(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('db97_codcam',5,$Idb97_codcam,true,'text',$db_opcao," onchange='js_pesquisadb97_codcam(false);'")
?>
       <?
db_input('nomecam',40,$Inomecam,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb97_totaliza?>">
       <?=@$Ldb97_totaliza?>
    </td>
    <td> 
<?
$x = array('t'=>'Sim','f'=>'Não');
db_select('db97_totaliza',$x,true,$db_opcao,"");
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
	 $chavepri= array("db97_codigo"=>@$db97_codigo);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $cldb_relatquebra->sql_query_file($db97_codigo);
	 $cliframe_alterar_excluir->campos  ="db97_codigo,db97_codrel,db97_codcam,db97_totaliza";
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
function js_pesquisadb97_codrel(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_db_relatquebra','db_iframe_db_relat','func_db_relat.php?funcao_js=parent.js_mostradb_relat1|db91_codrel|db91_descr','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.db97_codrel.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_db_relatquebra','db_iframe_db_relat','func_db_relat.php?pesquisa_chave='+document.form1.db97_codrel.value+'&funcao_js=parent.js_mostradb_relat','Pesquisa',false);
     }else{
       document.form1.db91_descr.value = ''; 
     }
  }
}
function js_mostradb_relat(chave,erro){
  document.form1.db91_descr.value = chave; 
  if(erro==true){ 
    document.form1.db97_codrel.focus(); 
    document.form1.db97_codrel.value = ''; 
  }
}
function js_mostradb_relat1(chave1,chave2){
  document.form1.db97_codrel.value = chave1;
  document.form1.db91_descr.value = chave2;
  db_iframe_db_relat.hide();
}
function js_pesquisadb97_codcam(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_db_relatquebra','db_iframe_db_syscampo','func_db_syscampo.php?funcao_js=parent.js_mostradb_syscampo1|codcam|nomecam','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.db97_codcam.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_db_relatquebra','db_iframe_db_syscampo','func_db_syscampo.php?pesquisa_chave='+document.form1.db97_codcam.value+'&funcao_js=parent.js_mostradb_syscampo','Pesquisa',false);
     }else{
       document.form1.nomecam.value = ''; 
     }
  }
}
function js_mostradb_syscampo(chave,erro){
  document.form1.nomecam.value = chave; 
  if(erro==true){ 
    document.form1.db97_codcam.focus(); 
    document.form1.db97_codcam.value = ''; 
  }
}
function js_mostradb_syscampo1(chave1,chave2){
  document.form1.db97_codcam.value = chave1;
  document.form1.nomecam.value = chave2;
  db_iframe_db_syscampo.hide();
}
</script>