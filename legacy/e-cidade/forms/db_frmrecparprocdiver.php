<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: diversos
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrecparprocdiver->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k02_descr");
$clrotulo->label("dv09_descr");
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
     $receita = "";
     $k02_descr = '';
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tprocdiver?>">
       <?
       db_ancora(@$Lprocdiver,"js_pesquisaprocdiver(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('procdiver',6,$Iprocdiver,true,'text',3," onchange='js_pesquisaprocdiver(false);'")
?>
       <?
//db_input('dv09_descr',40,$Idv09_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Treceita?>">
       <?
       db_ancora(@$Lreceita,"js_pesquisareceita(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('receita',6,$Ireceita,true,'text',$db_opcao," onchange='js_pesquisareceita(false);'")
?>
       <?
db_input('k02_descr',32,$Ik02_descr,true,'text',3,'')
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
	 $chavepri= array("procdiver"=>@$procdiver,"receita"=>@$receita);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
//	 echo $clrecparprocdiver->sql_query_file($procdiver);
	 $cliframe_alterar_excluir->sql     = $clrecparprocdiver->sql_query_file($procdiver);
	 $cliframe_alterar_excluir->campos  ="procdiver,receita";
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
function js_pesquisareceita(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_recparprocdiver','db_iframe_tabrec','func_tabrec_todas.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_descr','Pesquisa',true,'0');
  }else{
     if(document.form1.receita.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_recparprocdiver','db_iframe_tabrec','func_tabrec_todas.php?pesquisa_chave='+document.form1.receita.value+'&funcao_js=parent.js_mostratabrec','Pesquisa',false);
     }else{
       document.form1.k02_descr.value = ''; 
     }
  }
}
function js_mostratabrec(chave,erro){
  document.form1.k02_descr.value = chave; 
  if(erro==true){ 
    document.form1.receita.focus(); 
    document.form1.receita.value = ''; 
  }
}
function js_mostratabrec1(chave1,chave2){
  document.form1.receita.value = chave1;
  document.form1.k02_descr.value = chave2;
  db_iframe_tabrec.hide();
}
function js_pesquisaprocdiver(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_recparprocdiver','db_iframe_procdiver','func_procdiver.php?funcao_js=parent.js_mostraprocdiver1|dv09_procdiver|dv09_descr','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.procdiver.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_recparprocdiver','db_iframe_procdiver','func_procdiver.php?pesquisa_chave='+document.form1.procdiver.value+'&funcao_js=parent.js_mostraprocdiver','Pesquisa',false);
     }else{
       document.form1.dv09_descr.value = ''; 
     }
  }
}
function js_mostraprocdiver(chave,erro){
  document.form1.dv09_descr.value = chave; 
  if(erro==true){ 
    document.form1.procdiver.focus(); 
    document.form1.procdiver.value = ''; 
  }
}
function js_mostraprocdiver1(chave1,chave2){
  document.form1.procdiver.value = chave1;
  document.form1.dv09_descr.value = chave2;
  db_iframe_procdiver.hide();
}
</script>