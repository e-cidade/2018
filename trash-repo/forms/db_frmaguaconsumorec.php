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
$claguaconsumorec->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("x25_descr");
$clrotulo->label("x19_descr");
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
     //$x20_codconsumo = "";
     $x20_valor = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tx20_codconsumo?>">
       <?
       db_ancora(@$Lx20_codconsumo,"js_pesquisax20_codconsumo(true);", 3);
       ?>
    </td>
    <td> 
<?
db_input('x20_codconsumo',5,$Ix20_codconsumo,true,'text', 3," onchange='js_pesquisax20_codconsumo(false);'")
?>
       <?
db_input('x19_descr',40,$Ix19_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx20_codconsumotipo?>">
       <?
       db_ancora(@$Lx20_codconsumotipo,"js_pesquisax20_codconsumotipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('x20_codconsumotipo',5,$Ix20_codconsumotipo,true,'text',$db_opcao," onchange='js_pesquisax20_codconsumotipo(false);'")
?>
       <?
db_input('x25_descr',40,$Ix25_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx20_valor?>">
       <?=@$Lx20_valor?>
    </td>
    <td> 
<?
db_input('x20_valor',15,$Ix20_valor,true,'text',$db_opcao,"")
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
	 $chavepri= array("x20_codconsumo"=>@$x20_codconsumo, "x20_codconsumotipo"=>@$x20_codconsumotipo);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $claguaconsumorec->sql_query(@$x20_codconsumo);
	 $cliframe_alterar_excluir->campos  ="x20_codconsumo,x19_descr,x20_codconsumotipo,x25_descr,x20_valor";
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
function js_pesquisax20_codconsumotipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_aguaconsumorec','db_iframe_aguaconsumotipo','func_aguaconsumotipo.php?funcao_js=parent.js_mostraaguaconsumotipo1|x25_codconsumotipo|x25_descr','Pesquisa',true,'0');
  }else{
     if(document.form1.x20_codconsumotipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_aguaconsumorec','db_iframe_aguaconsumotipo','func_aguaconsumotipo.php?pesquisa_chave='+document.form1.x20_codconsumotipo.value+'&funcao_js=parent.js_mostraaguaconsumotipo','Pesquisa',false);
     }else{
       document.form1.x25_descr.value = ''; 
     }
  }
}
function js_mostraaguaconsumotipo(chave,erro){
  document.form1.x25_descr.value = chave; 
  if(erro==true){ 
    document.form1.x20_codconsumotipo.focus(); 
    document.form1.x20_codconsumotipo.value = ''; 
  }
}
function js_mostraaguaconsumotipo1(chave1,chave2){
  document.form1.x20_codconsumotipo.value = chave1;
  document.form1.x25_descr.value = chave2;
  db_iframe_aguaconsumotipo.hide();
}
function js_pesquisax20_codconsumo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_aguaconsumorec','db_iframe_aguaconsumo','func_aguaconsumo.php?funcao_js=parent.js_mostraaguaconsumo1|x19_codconsumo|x19_descr','Pesquisa',true,'0');
  }else{
     if(document.form1.x20_codconsumo.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_aguaconsumorec','db_iframe_aguaconsumo','func_aguaconsumo.php?pesquisa_chave='+document.form1.x20_codconsumo.value+'&funcao_js=parent.js_mostraaguaconsumo','Pesquisa',false);
     }else{
       document.form1.x19_descr.value = ''; 
     }
  }
}
function js_mostraaguaconsumo(chave,erro){
  document.form1.x19_descr.value = chave; 
  if(erro==true){ 
    document.form1.x20_codconsumo.focus(); 
    document.form1.x20_codconsumo.value = ''; 
  }
}
function js_mostraaguaconsumo1(chave1,chave2){
  document.form1.x20_codconsumo.value = chave1;
  document.form1.x19_descr.value = chave2;
  db_iframe_aguaconsumo.hide();
}
</script>