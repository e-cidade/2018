<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: Agua
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$claguacalcval->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("x22_matric");
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
     $x23_codconsumotipo = "";
     $x23_valor = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tx23_codcalc?>">
       <?
       db_ancora(@$Lx23_codcalc,"js_pesquisax23_codcalc(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('x23_codcalc',5,$Ix23_codcalc,true,'text',$db_opcao," onchange='js_pesquisax23_codcalc(false);'")
?>
       <?
db_input('x22_matric',10,$Ix22_matric,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx23_codconsumotipo?>">
       <?
       db_ancora(@$Lx23_codconsumotipo,"js_pesquisax23_codconsumotipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('x23_codconsumotipo',5,$Ix23_codconsumotipo,true,'text',$db_opcao," onchange='js_pesquisax23_codconsumotipo(false);'")
?>
       <?
db_input('x25_descr',40,$Ix25_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx23_valor?>">
       <?=@$Lx23_valor?>
    </td>
    <td> 
<?
db_input('x23_valor',15,$Ix23_valor,true,'text',$db_opcao,"")
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
	 $chavepri= array("x23_codcalc"=>@$x23_codcalc,"x23_codconsumotipo"=>@$x23_codconsumotipo);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $claguacalcval->sql_query_file($x23_codcalc);
	 $cliframe_alterar_excluir->campos  ="x23_codcalc,x23_codconsumotipo,x23_valor";
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
function js_pesquisax23_codcalc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_aguacalcval','db_iframe_aguacalc','func_aguacalc.php?funcao_js=parent.js_mostraaguacalc1|x22_codcalc|x22_matric','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.x23_codcalc.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_aguacalcval','db_iframe_aguacalc','func_aguacalc.php?pesquisa_chave='+document.form1.x23_codcalc.value+'&funcao_js=parent.js_mostraaguacalc','Pesquisa',false);
     }else{
       document.form1.x22_matric.value = ''; 
     }
  }
}
function js_mostraaguacalc(chave,erro){
  document.form1.x22_matric.value = chave; 
  if(erro==true){ 
    document.form1.x23_codcalc.focus(); 
    document.form1.x23_codcalc.value = ''; 
  }
}
function js_mostraaguacalc1(chave1,chave2){
  document.form1.x23_codcalc.value = chave1;
  document.form1.x22_matric.value = chave2;
  db_iframe_aguacalc.hide();
}
function js_pesquisax23_codconsumotipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_aguacalcval','db_iframe_aguaconsumotipo','func_aguaconsumotipo.php?funcao_js=parent.js_mostraaguaconsumotipo1|x25_codconsumotipo|x25_descr','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.x23_codconsumotipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_aguacalcval','db_iframe_aguaconsumotipo','func_aguaconsumotipo.php?pesquisa_chave='+document.form1.x23_codconsumotipo.value+'&funcao_js=parent.js_mostraaguaconsumotipo','Pesquisa',false);
     }else{
       document.form1.x25_descr.value = ''; 
     }
  }
}
function js_mostraaguaconsumotipo(chave,erro){
  document.form1.x25_descr.value = chave; 
  if(erro==true){ 
    document.form1.x23_codconsumotipo.focus(); 
    document.form1.x23_codconsumotipo.value = ''; 
  }
}
function js_mostraaguaconsumotipo1(chave1,chave2){
  document.form1.x23_codconsumotipo.value = chave1;
  document.form1.x25_descr.value = chave2;
  db_iframe_aguaconsumotipo.hide();
}
</script>