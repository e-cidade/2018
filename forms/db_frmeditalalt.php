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

//MODULO: contrib
$cledital->rotulo->label();
$cleditaldoc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("k02_descr");
$clrotulo->label("d40_codigo");
$clrotulo->label("d40_trecho");
$clrotulo->label("db03_descr");

?>
<script>
function js_marca(obj){ 

   var OBJ = document.form1;

   for(i=0;i<OBJ.length;i++){
     if(OBJ.elements[i].type == 'checkbox'){
       OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);            
     }
   }

   return false;

}
  function js_confirma(){
    var obj = listas.document.form1;
    var ob = listas.document;
    var codigo="";
    for(i=0;i<obj.length;i++){
      if(obj.elements[i].type=='checkbox' && obj.elements[i].checked ){
	codigo += "XX"+obj.elements[i].name.substr(6);
      }
    }	
    document.form1.codigo.value=codigo;
    return true;
  }
</script>
<form name="form1" method="post" action="">
<center>
<table><tr><td valign="top">
<table border="0">
  <tr>
    <td nowrap title="<?=@$Td01_codedi?>">
       <input name="codigo" type="hidden">    
       <?=@$Ld01_codedi?>
    </td>
    <td> 
<?
db_input('d01_codedi',10,$Id01_codedi,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Td01_numero?>">
       <?=@$Ld01_numero?>
    </td>
    <td> 
<?
if($db_opcao==22){
  $dbb=2;
}else{
  $dbb=$db_opcao;
}  
db_input('d01_numero',10,$Id01_numero,true,'text',$dbb,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Td01_perc?>">
       <?=@$Ld01_perc?>
    </td>
    <td> 
<?
if(!isset($d01_perc)){
  $d01_perc="0";
}
db_input('d01_perc',10,$Id01_perc,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Td01_receit?>">
       <?
       db_ancora(@$Ld01_receit,"js_pesquisad01_receit(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('d01_receit',10,$Id01_receit,true,'text',$db_opcao," onchange='js_pesquisad01_receit(false);'")?>
       <?
db_input('k02_descr',40,$Ik02_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  
   <tr>
    <td nowrap title="<?=@$Td13_db_documento?>">
       <?
       db_ancora(@$Ld13_db_documento,"js_pesquisad13_db_documento(true);",$db_opcao);
       ?>
    </td>
    <td> 
    <?
      db_input('d13_db_documento',10,$Id13_db_documento,true,'text',$db_opcao," onchange='js_pesquisad13_db_documento(false);'")
    ?>
    <?
      db_input('db03_descr',40,$Idb03_descr,true,'text',3,'')
    ?>
    </td>
  </tr>
  
  
  <tr>
    <td nowrap title="<?=@$Td01_numtot?>">
       <?=@$Ld01_numtot?>
    </td>
    <td> 
<?
db_input('d01_numtot',10,$Id01_numtot,true,'text',$db_opcao)?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Td01_privenc?>">
       <?=@$Ld01_privenc?>
    </td>
    <td> 
     <?
     db_inputdata('d01_privenc',@$d01_privenc_dia,@$d01_privenc_mes,@$d01_privenc_ano,true,'text',$db_opcao,"");
   ?>  
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Td01_perunica?>">
       <?=@$Ld01_perunica?>
    </td>
    <td> 
<?
db_input('d01_perunica',10,$Id01_perunica,true,'text',$db_opcao)?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Td01_descr?>" valign="top">
       <?=@$Ld01_descr?>
    </td>
    <td> 
<?
db_textarea('d01_descr',5,52,$Id01_descr,true,'text',$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
      <td colspan="2" align="center">
      <input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> <?=($db_opcao==3?"":"onclick='return js_confirma();'")?>>
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    </td>  
  </tr>  
 <tr>
 </table>
 <table>
   <tr>
   <td>
      <?
      if(isset($chavepesquisa) || isset($HTTP_POST_VARS["db_opcao"]) || $db_opcao=="1"){
      ?>  
      <fieldset><Legend><b>Selecione as listas</b></legend>
       <iframe id="listas"  frameborder="0" name="listas" src="forms/db_frmeditaliframe.php?db_opcao=<?=$db_opcao?><?=(isset($d01_codedi)?"&d01_codedi=$d01_codedi":"")?>" height="200" width="850" scrolling="auto" >

       </iframe>
      </fieldset>
     <?
     }
     ?> 
     </td>
     </tr>
     </table>
</tr>
</table>
  </center>
</form>
<script>

function js_pesquisad13_db_documento( mostra ){  
  if( mostra == true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_documento','func_editaldocalt.php?funcao_js=parent.js_mostradb_documento1|db03_docum|db03_descr','Pesquisa',true);
  }else{
     if(document.form1.d13_db_documento.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_db_documento','func_editaldocalt.php?pesquisa_chave='+document.form1.d13_db_documento.value+'&funcao_js=parent.js_mostradb_documento','Pesquisa',false);
     }else{
       document.form1.db03_descr.value = ''; 
     }
  }
}

function js_mostradb_documento(chave,erro){

  document.form1.db03_descr.value = chave; 
  
  if(erro==true){ 
    document.form1.d13_db_documento.focus(); 
    document.form1.d13_db_documento.value = ''; 
  }
}

function js_mostradb_documento1(chave1,chave2){
  document.form1.d13_db_documento.value = chave1;
  document.form1.db03_descr.value = chave2;
  db_iframe_db_documento.hide();
}
  
function js_lista(mostra){
  document.form1.lanca.onclick = "";
  if(mostra==true){
    db_iframe.jan.location.href = 'func_projmelhorias.php?funcao_js=parent.js_mostralista1|d40_codigo';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_projmelhorias.php?pesquisa_chave='+document.form1.d40_codigo.value+'&funcao_js=parent.js_mostralista';
  }
}
function js_mostralista(chave,erro){
  if(erro==true){ 
    alert("Lista inválida.");
    document.form1.d40_codigo.focus(); 
  }else{
    document.form1.lanca.onclick = js_insere;
  }  
}
function js_mostralista1(chave1,chave2){
  document.form1.d40_codigo.value = chave1;
  db_iframe.hide();
  document.form1.lanca.onclick = js_insere;
}
function js_pesquisad01_receit(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_tabrec.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_descr';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_tabrec.php?pesquisa_chave='+document.form1.d01_receit.value+'&funcao_js=parent.js_mostratabrec';
  }
}
function js_mostratabrec(chave,erro){
  document.form1.k02_descr.value = chave; 
  if(erro==true){ 
    document.form1.d01_receit.focus(); 
    document.form1.d01_receit.value = ''; 
  }
}
function js_mostratabrec1(chave1,chave2){
  document.form1.d01_receit.value = chave1;
  document.form1.k02_descr.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_edital.php?funcao_js=parent.js_preenchepesquisa|0';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  <?
    if($db_opcao!=1){
  ?>  
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
  <?
    }
  ?>
}

</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
if(($db_opcao==33 || $db_opcao==22) && empty($chavepesquisa)){
  echo "\n<script>js_pesquisa();</script>";  
}
?>