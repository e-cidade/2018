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

//MODULO: Configuracoes
include("dbforms/db_classesgenericas.php");
include("libs/db_utils.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clcadenderbairrocadenderrua->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db74_descricao");
$clrotulo->label("db73_descricao");
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
     //$db87_cadenderrua = "";
     $db87_cadenderbairro = "";
     $db73_descricao = "";
   }
} 

?>
<form name="form1" method="post" action="">
<center>

<table align=center style="margin-top:15px">
<tr><td>

<fieldset>
<legend><b>Vincular Bairro</b></legend>

<table border="0">
<?
db_input('db87_sequencial',10,$Idb87_sequencial,true,'hidden',3,"")
?>
  <tr>
    <td nowrap title="<?=@$Tdb87_cadenderrua?>">
       <?
       db_ancora(@$Ldb87_cadenderrua,"js_pesquisadb87_cadenderrua(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('db87_cadenderrua',10,$Idb87_cadenderrua,true,'text', 3," onchange='js_pesquisadb87_cadenderrua(false);'")
?>
       <?
db_input('db74_descricao',40,$Idb74_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb87_cadenderbairro?>">
       <?
       db_ancora(@$Ldb87_cadenderbairro,"js_pesquisadb87_cadenderbairro(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('db87_cadenderbairro',10,$Idb87_cadenderbairro,true,'text',$db_opcao," onchange='js_pesquisadb87_cadenderbairro(false);'")
?>
       <?
db_input('db73_descricao',40,$Idb73_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>

</fieldset>
</td></tr>
</table>
  
  
  <table border="0" align=center>
  </tr>
    <td colspan="2" align="center">
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
 <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
  </table>
  <br>
 <table align=center>
  <tr>
    <td valign="top"  align="center">  
    <?
	 $chavepri= array("db87_sequencial"=>@$db87_sequencial);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $clcadenderbairrocadenderrua->sql_query(null,"*","","db87_cadenderrua={$db87_cadenderrua}");
	 $cliframe_alterar_excluir->campos  ="db87_sequencial, db74_descricao, db73_descricao";
	 $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";
	 $cliframe_alterar_excluir->iframe_height ="160";
	 $cliframe_alterar_excluir->iframe_width ="510";
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
function js_pesquisadb87_cadenderrua(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_cadenderbairrocadenderrua','db_iframe_cadenderrua','func_cadenderrua.php?funcao_js=parent.js_mostracadenderrua1|db74_sequencial|db74_descricao','Pesquisa',true,'0','1');
  }else{
     if(document.form1.db87_cadenderrua.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_cadenderbairrocadenderrua','db_iframe_cadenderrua','func_cadenderrua.php?pesquisa_chave='+document.form1.db87_cadenderrua.value+'&funcao_js=parent.js_mostracadenderrua','Pesquisa',false);
     }else{
       document.form1.db74_descricao.value = ''; 
     }
  }
}
function js_mostracadenderrua(chave,erro){
  document.form1.db74_descricao.value = chave; 
  if(erro==true){ 
    document.form1.db87_cadenderrua.focus(); 
    document.form1.db87_cadenderrua.value = ''; 
  }
}
function js_mostracadenderrua1(chave1,chave2){
  document.form1.db87_cadenderrua.value = chave1;
  document.form1.db74_descricao.value = chave2;
  db_iframe_cadenderrua.hide();
}
function js_pesquisadb87_cadenderbairro(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_cadenderbairrocadenderrua','db_iframe_cadenderbairro','func_cadenderbairro.php?funcao_js=parent.js_mostracadenderbairro1|db73_sequencial|db73_descricao','Pesquisa',true,'0','1');
  }else{
     if(document.form1.db87_cadenderbairro.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_cadenderbairrocadenderrua','db_iframe_cadenderbairro','func_cadenderbairro.php?pesquisa_chave='+document.form1.db87_cadenderbairro.value+'&funcao_js=parent.js_mostracadenderbairro','Pesquisa',false);
     }else{
       document.form1.db73_descricao.value = ''; 
     }
  }
}
function js_mostracadenderbairro(chave,erro){
  document.form1.db73_descricao.value = chave; 
  if(erro==true){ 
    document.form1.db87_cadenderbairro.focus(); 
    document.form1.db87_cadenderbairro.value = ''; 
  }
}
function js_mostracadenderbairro1(chave1,chave2){
  document.form1.db87_cadenderbairro.value = chave1;
  document.form1.db73_descricao.value = chave2;
  db_iframe_cadenderbairro.hide();
}
</script>