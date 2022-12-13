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

//MODULO: agua
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$claguaisencaorec->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("x10_matric");
$clrotulo->label("x25_descr");
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
     //$x26_codisencao = $x10_codisencao;
     $x26_codconsumotipo = "";
     $x26_percentual = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tx26_codisencaorec?>">
       <?=@$Lx26_codisencaorec?>
    </td>
    <td> 
<?
db_input('x26_codisencaorec',5,$Ix26_codisencaorec,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx26_codisencao?>">
       <?
       db_ancora(@$Lx26_codisencao,"js_pesquisax26_codisencao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('x26_codisencao',5,$Ix26_codisencao,true,'text',3," onchange='js_pesquisax26_codisencao(false);'")
?>
       <?
//db_input('x10_matric',10,$Ix10_matric,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx26_codconsumotipo?>">
       <?
       db_ancora(@$Lx26_codconsumotipo,"js_pesquisax26_codconsumotipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('x26_codconsumotipo',5,$Ix26_codconsumotipo,true,'text',$db_opcao," onchange='js_pesquisax26_codconsumotipo(false);'")
?>
       <?
db_input('x25_descr',40,$Ix25_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx26_percentual?>">
       <?=@$Lx26_percentual?>
    </td>
    <td> 
<?
db_input('x26_percentual',6,$Ix26_percentual,true,'text',$db_opcao,"")
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
	 $chavepri= array("x26_codisencaorec"=>@$x26_codisencaorec);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 //$cliframe_alterar_excluir->sql     = $claguaisencaorec->sql_query_file($x26_codisencaorec);
	 $cliframe_alterar_excluir->sql     = $claguaisencaorec->sql_query(null, "*", "x26_codconsumotipo", "x26_codisencao=$x26_codisencao");
	 $cliframe_alterar_excluir->campos  ="x26_codisencaorec,x26_codisencao,x26_codconsumotipo,x25_descr,x26_percentual";
	 $cliframe_alterar_excluir->legenda="RECEITAS";
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
function js_pesquisax26_codisencao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_aguaisencaorec','db_iframe_aguaisencao','func_aguaisencao.php?funcao_js=parent.js_mostraaguaisencao1|x10_codisencao|x10_matric','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.x26_codisencao.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_aguaisencaorec','db_iframe_aguaisencao','func_aguaisencao.php?pesquisa_chave='+document.form1.x26_codisencao.value+'&funcao_js=parent.js_mostraaguaisencao','Pesquisa',false);
     }else{
       document.form1.x10_matric.value = ''; 
     }
  }
}
function js_mostraaguaisencao(chave,erro){
  document.form1.x10_matric.value = chave; 
  if(erro==true){ 
    document.form1.x26_codisencao.focus(); 
    document.form1.x26_codisencao.value = ''; 
  }
}
function js_mostraaguaisencao1(chave1,chave2){
  document.form1.x26_codisencao.value = chave1;
  document.form1.x10_matric.value = chave2;
  db_iframe_aguaisencao.hide();
}
function js_pesquisax26_codconsumotipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_aguaisencaorec','db_iframe_aguaconsumotipo','func_aguaconsumotipo.php?funcao_js=parent.js_mostraaguaconsumotipo1|x25_codconsumotipo|x25_descr','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.x26_codconsumotipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_aguaisencaorec','db_iframe_aguaconsumotipo','func_aguaconsumotipo.php?pesquisa_chave='+document.form1.x26_codconsumotipo.value+'&funcao_js=parent.js_mostraaguaconsumotipo','Pesquisa',false);
     }else{
       document.form1.x25_descr.value = ''; 
     }
  }
}
function js_mostraaguaconsumotipo(chave,erro){
  document.form1.x25_descr.value = chave; 
  if(erro==true){ 
    document.form1.x26_codconsumotipo.focus(); 
    document.form1.x26_codconsumotipo.value = ''; 
  }
}
function js_mostraaguaconsumotipo1(chave1,chave2){
  document.form1.x26_codconsumotipo.value = chave1;
  document.form1.x25_descr.value = chave2;
  db_iframe_aguaconsumotipo.hide();
}
</script>