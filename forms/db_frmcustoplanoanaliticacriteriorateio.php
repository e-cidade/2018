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
$clcustoplanoanaliticacriteriorateio->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("cc08_instit");
$clrotulo->label("cc04_custoplano");
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
     $cc07_sequencial  = "";
     $cc07_custoplanoanalitica = "";
     $cc07_quantidade = "";
     $cc07_percentual = "";
     $cc07_automatico = "";
     $cc04_custoplano = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table>
<tr>
<td>
<fieldset><legend><b>Plano de Custo</b></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tcc07_sequencial?>">
       <?=@$Lcc07_sequencial?>
    </td>
    <td> 
<?
db_input('cc07_sequencial',10,$Icc07_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr style='display: none'>
    <td nowrap title="<?=@$Tcc07_custocriteriorateio?>">
       <?
       db_ancora(@$Lcc07_custocriteriorateio,"js_pesquisacc07_custocriteriorateio(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('cc07_custocriteriorateio',10,$Icc07_custocriteriorateio,true,'text',$db_opcao," onchange='js_pesquisacc07_custocriteriorateio(false);'")
?>
       <?
db_input('cc08_instit',10,$Icc08_instit,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcc07_custoplanoanalitica?>">
       <?
       db_ancora(@$Lcc07_custoplanoanalitica,"js_pesquisacc07_custoplanoanalitica(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('cc07_custoplanoanalitica',10,$Icc07_custoplanoanalitica,true,'text',$db_opcao," onchange='js_pesquisacc07_custoplanoanalitica(false);'")
?>
       <?
db_input('cc04_custoplano',10,$Icc04_custoplano,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcc07_quantidade?>">
       <?=@$Lcc07_quantidade?>
    </td>
    <td> 
<?
db_input('cc07_quantidade',10,$Icc07_quantidade,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcc07_percentual?>">
       <?=@$Lcc07_percentual?>
    </td>
    <td> 
<?
db_input('cc07_percentual',10,$Icc07_percentual,true,'text', 3,"");
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
	 $chavepri= array("cc07_sequencial"=>@$cc07_custocriteriorateio);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $clcustoplanoanaliticacriteriorateio->sql_query(null,
	                                                                                           "*",
	                                                                                           "cc07_sequencial",
	                                                                                           "cc07_custocriteriorateio = {$cc07_custocriteriorateio}");
	 $cliframe_alterar_excluir->campos  ="cc07_sequencial,cc07_custocriteriorateio,cc01_estrutural,cc07_quantidade,cc07_percentual";
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
function js_pesquisacc07_custocriteriorateio(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_custoplanoanaliticacriteriorateio',
                        'db_iframe_custocriteriorateio',
                        'func_custocriteriorateio.php?funcao_js=parent.js_mostracustocriteriorateio1|cc08_sequencial|cc08_instit',
                        'Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.cc07_custocriteriorateio.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_custoplanoanaliticacriteriorateio','db_iframe_custocriteriorateio','func_custocriteriorateio.php?pesquisa_chave='+document.form1.cc07_custocriteriorateio.value+'&funcao_js=parent.js_mostracustocriteriorateio','Pesquisa',false);
     }else{
       document.form1.cc08_instit.value = ''; 
     }
  }
}
function js_mostracustocriteriorateio(chave,erro){
  document.form1.cc08_instit.value = chave; 
  if(erro==true){ 
    document.form1.cc07_custocriteriorateio.focus(); 
    document.form1.cc07_custocriteriorateio.value = ''; 
  }
}
function js_mostracustocriteriorateio1(chave1,chave2){
  document.form1.cc07_custocriteriorateio.value = chave1;
  document.form1.cc08_instit.value = chave2;
  db_iframe_custocriteriorateio.hide();
}
function js_pesquisacc07_custoplanoanalitica(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_custoplanoanaliticacriteriorateio',
                        'db_iframe_custoplanoanalitica',
                        'func_custoplanoanalitica.php?funcao_js=parent.js_mostracustoplanoanalitica1|cc04_sequencial|cc01_estrutural',
                        'Plano de Custos',
                        true,'0','1',
                        (document.body.scrollWidth-10),
                        (document.body.scrollHeight-80));
  }else{
     if(document.form1.cc07_custoplanoanalitica.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_custoplanoanaliticacriteriorateio','db_iframe_custoplanoanalitica','func_custoplanoanalitica.php?pesquisa_chave='+document.form1.cc07_custoplanoanalitica.value+'&funcao_js=parent.js_mostracustoplanoanalitica','Pesquisa',false);
     }else{
       document.form1.cc04_custoplano.value = ''; 
     }
  }
}
function js_mostracustoplanoanalitica(chave,erro){
  document.form1.cc04_custoplano.value = chave; 
  if(erro==true){ 
    document.form1.cc07_custoplanoanalitica.focus(); 
    document.form1.cc07_custoplanoanalitica.value = ''; 
  }
}
function js_mostracustoplanoanalitica1(chave1,chave2){
  document.form1.cc07_custoplanoanalitica.value = chave1;
  document.form1.cc04_custoplano.value = chave2;
  db_iframe_custoplanoanalitica.hide();
}
</script>