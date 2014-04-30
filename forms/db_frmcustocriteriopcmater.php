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

//MODULO: Custos
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clcustocriteriopcmater->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("pc01_descrmater");
$clrotulo->label("cc08_instit");
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
     
     $cc10_pcmater    = "";
     $pc01_descrmater = "";
     $cc10_sequencial = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table>
<tr>
<td>
<fieldset>
 <legend><b>Materiais</b></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tcc10_sequencial?>">
       <?=@$Lcc10_sequencial?>
    </td>
    <td> 
<?
db_input('cc10_sequencial',10,$Icc10_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcc10_pcmater?>">
       <?
       db_ancora(@$Lcc10_pcmater,"js_pesquisacc10_pcmater(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('cc10_pcmater',10,$Icc10_pcmater,true,'text',$db_opcao," onchange='js_pesquisacc10_pcmater(false);'")
?>
       <?
db_input('pc01_descrmater',60,$Ipc01_descrmater,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr style='display: none;'>
    <td nowrap title="<?=@$Tcc10_custocriteriorateio?>">
       <?
       db_ancora(@$Lcc10_custocriteriorateio,"js_pesquisacc10_custocriteriorateio(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('cc10_custocriteriorateio',10,$Icc10_custocriteriorateio,true,'text',$db_opcao," onchange='js_pesquisacc10_custocriteriorateio(false);'")
?>
       <?
db_input('cc08_instit',10,$Icc08_instit,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </fieldset>
  </td>
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
	 $chavepri= array("cc10_custocriteriorateio"=>@$cc10_custocriteriorateio);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $clcustocriteriopcmater->sql_query_file(null,
	                                                                              "*",
	                                                                              "cc10_sequencial",
	                                                                              "cc10_custocriteriorateio = {$cc10_custocriteriorateio}");
	 $cliframe_alterar_excluir->campos  ="cc10_sequencial,cc10_pcmater,cc10_custocriteriorateio";
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
function js_pesquisacc10_pcmater(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_custocriteriopcmater',
                        'db_iframe_pcmater',
                        'func_pcmater.php?funcao_js=parent.js_mostrapcmater1|pc01_codmater|pc01_descrmater',
                        'Materiais',
                        true,'0','1',
                        (document.body.scrollWidth-10),
                        (document.body.scrollHeight-80));
  }else{
     if(document.form1.cc10_pcmater.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_custocriteriopcmater','db_iframe_pcmater','func_pcmater.php?pesquisa_chave='+document.form1.cc10_pcmater.value+'&funcao_js=parent.js_mostrapcmater','Pesquisa',false);
     }else{
       document.form1.pc01_descrmater.value = ''; 
     }
  }
}
function js_mostrapcmater(chave,erro){
  document.form1.pc01_descrmater.value = chave; 
  if(erro==true){ 
    document.form1.cc10_pcmater.focus(); 
    document.form1.cc10_pcmater.value = ''; 
  }
}
function js_mostrapcmater1(chave1,chave2){
  document.form1.cc10_pcmater.value = chave1;
  document.form1.pc01_descrmater.value = chave2;
  db_iframe_pcmater.hide();
}
function js_pesquisacc10_custocriteriorateio(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_custocriteriopcmater','db_iframe_custocriteriorateio','func_custocriteriorateio.php?funcao_js=parent.js_mostracustocriteriorateio1|cc08_sequencial|cc08_instit','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.cc10_custocriteriorateio.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_custocriteriopcmater','db_iframe_custocriteriorateio','func_custocriteriorateio.php?pesquisa_chave='+document.form1.cc10_custocriteriorateio.value+'&funcao_js=parent.js_mostracustocriteriorateio','Pesquisa',false);
     }else{
       document.form1.cc08_instit.value = ''; 
     }
  }
}
function js_mostracustocriteriorateio(chave,erro){
  document.form1.cc08_instit.value = chave; 
  if(erro==true){ 
    document.form1.cc10_custocriteriorateio.focus(); 
    document.form1.cc10_custocriteriorateio.value = ''; 
  }
}
function js_mostracustocriteriorateio1(chave1,chave2){
  document.form1.cc10_custocriteriorateio.value = chave1;
  document.form1.cc08_instit.value = chave2;
  db_iframe_custocriteriorateio.hide();
}
</script>