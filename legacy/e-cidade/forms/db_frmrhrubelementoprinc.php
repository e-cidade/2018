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

//MODULO: pessoal
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrhrubelementoprinc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh27_descr");
$clrotulo->label("o56_elemento");
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
     $rh24_codele = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Trh24_rubric?>">
       <?
       db_ancora(@$Lrh24_rubric,"js_pesquisarh24_rubric(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('rh24_rubric',4,$Irh24_rubric,true,'text',3," onchange='js_pesquisarh24_rubric(false);'")
?>
       <?
db_input('rh27_descr',30,$Irh27_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh24_codele?>">
       <?
       db_ancora(@$Lrh24_codele,"js_pesquisarh24_codele(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('rh24_codele',6,$Irh24_codele,true,'text',$db_opcao," onchange='js_pesquisarh24_codele(false);'")
?>
       <?
db_input('o56_elemento',13,$Io56_elemento,true,'text',3,'')
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
	 $chavepri= array("rh24_rubric"=>@$rh24_rubric,"rh24_codele"=>@$rh24_codele);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $clrhrubelementoprinc->sql_query_file($rh24_rubric);
	 $cliframe_alterar_excluir->campos  ="rh24_rubric,rh24_codele";
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
function js_pesquisarh24_rubric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_rhrubelementoprinc','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostrarhrubricas1|rh27_rubric|rh27_descr','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.rh24_rubric.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_rhrubelementoprinc','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+document.form1.rh24_rubric.value+'&funcao_js=parent.js_mostrarhrubricas','Pesquisa',false);
     }else{
       document.form1.rh27_descr.value = ''; 
     }
  }
}
function js_mostrarhrubricas(chave,erro){
  document.form1.rh27_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh24_rubric.focus(); 
    document.form1.rh24_rubric.value = ''; 
  }
}
function js_mostrarhrubricas1(chave1,chave2){
  document.form1.rh24_rubric.value = chave1;
  document.form1.rh27_descr.value = chave2;
  db_iframe_rhrubricas.hide();
}
function js_pesquisarh24_codele(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_rhrubelementoprinc','db_iframe_orcelemento','func_orcelemento.php?funcao_js=parent.js_mostraorcelemento1|o56_codele|o56_elemento','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.rh24_codele.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_rhrubelementoprinc','db_iframe_orcelemento','func_orcelemento.php?pesquisa_chave='+document.form1.rh24_codele.value+'&funcao_js=parent.js_mostraorcelemento','Pesquisa',false);
     }else{
       document.form1.o56_elemento.value = ''; 
     }
  }
}
function js_mostraorcelemento(chave,erro){
  document.form1.o56_elemento.value = chave; 
  if(erro==true){ 
    document.form1.rh24_codele.focus(); 
    document.form1.rh24_codele.value = ''; 
  }
}
function js_mostraorcelemento1(chave1,chave2){
  document.form1.rh24_codele.value = chave1;
  document.form1.o56_elemento.value = chave2;
  db_iframe_orcelemento.hide();
}
</script>