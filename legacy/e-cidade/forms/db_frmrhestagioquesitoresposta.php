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

//MODULO: recursoshumanos
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrhestagioquesitoresposta->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("h53_sequencial");
$clrotulo->label("h52_descr");
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
     $h54_rhestagiocriterio = "";
     $h54_descr      = "";
     $h52_descr      = "";
     $h54_sequencial = "";


   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table>
<tr>
<td>
<fieldset>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Th54_sequencial?>">
       <?=@$Lh54_sequencial?>
    </td>
    <td> 
<?
db_input('h54_sequencial',10,$Ih54_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr style='display:none'>
    <td nowrap title="<?=@$Th54_rhestagioquesitopergunta?>">
       <?
       db_ancora(@$Lh54_rhestagioquesitopergunta,"js_pesquisah54_rhestagioquesitopergunta(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('h54_rhestagioquesitopergunta',10,$Ih54_rhestagioquesitopergunta,true,'text',$db_opcao," onchange='js_pesquisah54_rhestagioquesitopergunta(false);'")
?>
       <?
db_input('h53_sequencial',10,$Ih53_sequencial,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th54_rhestagiocriterio?>">
       <?
       db_ancora(@$Lh54_rhestagiocriterio,"js_pesquisah54_rhestagiocriterio(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('h54_rhestagiocriterio',10,$Ih54_rhestagiocriterio,true,'text',$db_opcao," onchange='js_pesquisah54_rhestagiocriterio(false);'")
?>
       <?
db_input('h52_descr',40,$Ih52_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th54_descr?>">
       <?=@$Lh54_descr?>
    </td>
    <td> 
<?
db_textarea('h54_descr',6,60,$Ih54_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </fieldset>
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
	 $chavepri= array("h54_sequencial"=>@$h54_sequencial);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $clrhestagioquesitoresposta->sql_query_file(null,"*",null,"h54_rhestagioquesitopergunta =".@$h54_rhestagioquesitopergunta);
	 $cliframe_alterar_excluir->campos  ="h54_sequencial,h54_rhestagioquesitopergunta,h54_rhestagiocriterio,h54_descr";
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
function js_pesquisah54_rhestagioquesitopergunta(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_rhestagioquesitoresposta','db_iframe_rhestagioquesitopergunta','func_rhestagioquesitopergunta.php?funcao_js=parent.js_mostrarhestagioquesitopergunta1|h53_sequencial|h53_sequencial','Pesquisa',true,'0');
  }else{
     if(document.form1.h54_rhestagioquesitopergunta.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_rhestagioquesitoresposta','db_iframe_rhestagioquesitopergunta','func_rhestagioquesitopergunta.php?pesquisa_chave='+document.form1.h54_rhestagioquesitopergunta.value+'&funcao_js=parent.js_mostrarhestagioquesitopergunta','Pesquisa',false);
     }else{
       document.form1.h53_sequencial.value = ''; 
     }
  }
}
function js_mostrarhestagioquesitopergunta(chave,erro){
  document.form1.h53_sequencial.value = chave; 
  if(erro==true){ 
    document.form1.h54_rhestagioquesitopergunta.focus(); 
    document.form1.h54_rhestagioquesitopergunta.value = ''; 
  }
}
function js_mostrarhestagioquesitopergunta1(chave1,chave2){
  document.form1.h54_rhestagioquesitopergunta.value = chave1;
  document.form1.h53_sequencial.value = chave2;
  db_iframe_rhestagioquesitopergunta.hide();
}
function js_pesquisah54_rhestagiocriterio(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_rhestagioquesitoresposta','db_iframe_rhestagiocriterio','func_rhestagiocriterio.php?funcao_js=parent.js_mostrarhestagiocriterio1|h52_sequencial|h52_descr','Pesquisa',true,'0');
  }else{
     if(document.form1.h54_rhestagiocriterio.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_rhestagioquesitoresposta','db_iframe_rhestagiocriterio','func_rhestagiocriterio.php?pesquisa_chave='+document.form1.h54_rhestagiocriterio.value+'&funcao_js=parent.js_mostrarhestagiocriterio','Pesquisa',false);
     }else{
       document.form1.h52_descr.value = ''; 
     }
  }
}
function js_mostrarhestagiocriterio(chave,erro){
  document.form1.h52_descr.value = chave; 
  if(erro==true){ 
    document.form1.h54_rhestagiocriterio.focus(); 
    document.form1.h54_rhestagiocriterio.value = ''; 
  }
}
function js_mostrarhestagiocriterio1(chave1,chave2){
  document.form1.h54_rhestagiocriterio.value = chave1;
    document.form1.h52_descr.value = chave2;
  db_iframe_rhestagiocriterio.hide();
}
</script>