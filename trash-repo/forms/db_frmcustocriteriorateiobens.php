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
$clcustocriteriorateiobens->rotulo->label();
$clrotulo = new rotulocampo;
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
      
     $cc06_custoplanoanaliticabens = "";
     $cc06_sequencial = "";
     $cc06_ativo = "";
     
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table>
<tr>
<td>
<fieldset>
<legend><b>Bens</b></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tcc06_sequencial?>">
       <?=@$Lcc06_sequencial?>
    </td>
    <td> 
<?
db_input('cc06_sequencial',10,$Icc06_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcc06_custoplanoanaliticabens?>">
       <?=@$Lcc06_custoplanoanaliticabens?>
    </td>
    <td> 
<?
db_input('cc06_custoplanoanaliticabens',10,$Icc06_custoplanoanaliticabens,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr style='display: none;'>
    <td nowrap title="<?=@$Tcc06_custocriteriorateio?>">
       <?
       db_ancora(@$Lcc06_custocriteriorateio,"js_pesquisacc06_custocriteriorateio(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('cc06_custocriteriorateio',10,$Icc06_custocriteriorateio,true,'text',$db_opcao," onchange='js_pesquisacc06_custocriteriorateio(false);'")
?>
       <?
db_input('cc08_instit',10,$Icc08_instit,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcc06_ativo?>">
       <?=@$Lcc06_ativo?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('cc06_ativo',$x,true,$db_opcao,"");
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
	 $chavepri= array("cc06_custocriteriorateio"=>@$cc06_custocriteriorateio);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $clcustocriteriorateiobens->sql_query(null,"*","cc06_sequencial",
	                                                                            "cc06_custocriteriorateio ={$cc06_custocriteriorateio}");
	 $cliframe_alterar_excluir->campos  ="cc06_sequencial,t52_descr,cc06_custocriteriorateio,cc06_ativo";
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
function js_pesquisacc06_custocriteriorateio(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_custocriteriorateiobens','db_iframe_custocriteriorateio','func_custocriteriorateio.php?funcao_js=parent.js_mostracustocriteriorateio1|cc08_sequencial|cc08_instit','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.cc06_custocriteriorateio.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_custocriteriorateiobens','db_iframe_custocriteriorateio','func_custocriteriorateio.php?pesquisa_chave='+document.form1.cc06_custocriteriorateio.value+'&funcao_js=parent.js_mostracustocriteriorateio','Pesquisa',false);
     }else{
       document.form1.cc08_instit.value = ''; 
     }
  }
}
function js_mostracustocriteriorateio(chave,erro){
  document.form1.cc08_instit.value = chave; 
  if(erro==true){ 
    document.form1.cc06_custocriteriorateio.focus(); 
    document.form1.cc06_custocriteriorateio.value = ''; 
  }
}
function js_mostracustocriteriorateio1(chave1,chave2){
  document.form1.cc06_custocriteriorateio.value = chave1;
  document.form1.cc08_instit.value = chave2;
  db_iframe_custocriteriorateio.hide();
}
</script>