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

//MODULO: veiculos
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clveicmanutitem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ve62_codigo");
$clrotulo->label("pc01_descrmater");
$clrotulo->label("ve64_pcmater");
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
     $ve63_codigo = "";
     $ve63_descr = "";
     $ve63_quant = "";
     $ve63_vlruni = "";
     $ve64_pcmater= "";
     $pc01_descrmater="";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?//=@$Tve63_codigo?>">
       <?//=@$Lve63_codigo?>
    </td>
    <td> 
<?
db_input('ve63_codigo',10,$Ive63_codigo,true,'hidden',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve63_veicmanut?>">
       <?
       db_ancora(@$Lve63_veicmanut,"js_pesquisave63_veicmanut(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('ve63_veicmanut',10,$Ive63_veicmanut,true,'text',3," onchange='js_pesquisave63_veicmanut(false);'")
?>
       <?
//db_input('ve62_codigo',10,$Ive62_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve63_descr?>">
       <?=@$Lve63_descr?>
    </td>
    <td> 
<?
db_input('ve63_descr',40,$Ive63_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve63_quant?>">
       <?=@$Lve63_quant?>
    </td>
    <td> 
<?
db_input('ve63_quant',10,$Ive63_quant,true,'text',$db_opcao,"")
?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tve63_vlruni?>">
       <?=@$Lve63_vlruni?>
    </td>
    <td> 
<?
db_input('ve63_vlruni',15,$Ive63_vlruni,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve64_pcmater?>">
       <?
       db_ancora(@$Lve64_pcmater,"js_pesquisave64_pcmater(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ve64_pcmater',10,$Ive64_pcmater,true,'text',$db_opcao," onchange='js_pesquisave64_pcmater(false);'")
?>
       <?
db_input('pc01_descrmater',40,$Ipc01_descrmater,true,'text',3,'')
       ?>
    </td>
  </tr>
  
  <tr>
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
	 $chavepri = array("ve63_codigo" => @$ve63_codigo);
	 $cliframe_alterar_excluir->chavepri      = $chavepri;
	 $cliframe_alterar_excluir->sql           = $clveicmanutitem->sql_query_pcmater(null,"*",null,"ve63_veicmanut = $ve63_veicmanut");
	 $cliframe_alterar_excluir->campos        = "ve63_codigo,ve63_veicmanut,ve63_descr,ve63_quant,ve63_vlruni,ve64_pcmater,pc01_descrmater";
	 $cliframe_alterar_excluir->legenda       = "ITENS LANÇADOS";
	 $cliframe_alterar_excluir->iframe_height = "160";
	 $cliframe_alterar_excluir->iframe_width  = "700";
   $cliframe_alterar_excluir->strFormatar   = ""; // Nao formatar valores do Iframe Altera/Exclui pois Quantidade podera ter mais de 2 casas decimais
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
function js_pesquisave63_veicmanut(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_veicmanutitem','db_iframe_veicmanut','func_veicmanut.php?funcao_js=parent.js_mostraveicmanut1|ve62_codigo|ve62_codigo','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.ve63_veicmanut.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_veicmanutitem','db_iframe_veicmanut','func_veicmanut.php?pesquisa_chave='+document.form1.ve63_veicmanut.value+'&funcao_js=parent.js_mostraveicmanut','Pesquisa',false);
     }else{
       document.form1.ve62_codigo.value = ''; 
     }
  }
}
function js_mostraveicmanut(chave,erro){
  document.form1.ve62_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ve63_veicmanut.focus(); 
    document.form1.ve63_veicmanut.value = ''; 
  }
}
function js_mostraveicmanut1(chave1,chave2){
  document.form1.ve63_veicmanut.value = chave1;
  document.form1.ve62_codigo.value = chave2;
  db_iframe_veicmanut.hide();
}
function js_pesquisave64_pcmater(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_veicmanutitem','db_iframe_pcmater','func_pcmater.php?funcao_js=parent.js_mostrapcmater1|pc01_codmater|pc01_descrmater','Pesquisa',true,'0');
  }else{
     if(document.form1.ve64_pcmater.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_veicmanutitem','db_iframe_pcmater','func_pcmater.php?pesquisa_chave='+document.form1.ve64_pcmater.value+'&funcao_js=parent.js_mostrapcmater','Pesquisa',false);
     }else{
       document.form1.pc01_descrmater.value = ''; 
     }
  }
}
function js_mostrapcmater(chave,erro){
  document.form1.pc01_descrmater.value = chave; 
  if(erro==true){ 
    document.form1.ve64_pcmater.focus(); 
    document.form1.ve64_pcmater.value = ''; 
  }
}
function js_mostrapcmater1(chave1,chave2){
  document.form1.ve64_pcmater.value = chave1;
  document.form1.pc01_descrmater.value = chave2;
  db_iframe_pcmater.hide();
}
</script>