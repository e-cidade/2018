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

//MODULO: compras
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clpcfornecert->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("pc60_dtlanc");
$clrotulo->label("pc59_descr");
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
     $pc61_certif = "";
     $pc61_vencim = "";
     $pc61_obs = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tpc61_numcgm?>">
       <?
       db_ancora(@$Lpc61_numcgm,"js_pesquisapc61_numcgm(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('pc61_numcgm',8,$Ipc61_numcgm,true,'text',3," onchange='js_pesquisapc61_numcgm(false);'")
?>
       <?
db_input('pc60_dtlanc',10,$Ipc60_dtlanc,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc61_certif?>">
       <?
       db_ancora(@$Lpc61_certif,"js_pesquisapc61_certif(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('pc61_certif',6,$Ipc61_certif,true,'text',$db_opcao," onchange='js_pesquisapc61_certif(false);'")
?>
       <?
db_input('pc59_descr',40,$Ipc59_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc61_vencim?>">
       <?=@$Lpc61_vencim?>
    </td>
    <td> 
<?
db_inputdata('pc61_vencim',@$pc61_vencim_dia,@$pc61_vencim_mes,@$pc61_vencim_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc61_obs?>">
       <?=@$Lpc61_obs?>
    </td>
    <td> 
<?
db_textarea('pc61_obs',3,40,$Ipc61_obs,true,'text',$db_opcao,"")
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
	 $chavepri= array("pc61_numcgm"=>@$pc61_numcgm,"pc61_certif"=>@$pc61_certif);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $clpcfornecert->sql_query_file($pc61_numcgm);
	 $cliframe_alterar_excluir->campos  ="pc61_numcgm,pc61_certif,pc61_vencim,pc61_obs";
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
function js_pesquisapc61_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_pcfornecert','db_iframe_pcforne','func_pcforne.php?funcao_js=parent.js_mostrapcforne1|pc60_numcgm|pc60_dtlanc','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.pc61_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_pcfornecert','db_iframe_pcforne','func_pcforne.php?pesquisa_chave='+document.form1.pc61_numcgm.value+'&funcao_js=parent.js_mostrapcforne','Pesquisa',false);
     }else{
       document.form1.pc60_dtlanc.value = ''; 
     }
  }
}
function js_mostrapcforne(chave,erro){
  document.form1.pc60_dtlanc.value = chave; 
  if(erro==true){ 
    document.form1.pc61_numcgm.focus(); 
    document.form1.pc61_numcgm.value = ''; 
  }
}
function js_mostrapcforne1(chave1,chave2){
  document.form1.pc61_numcgm.value = chave1;
  document.form1.pc60_dtlanc.value = chave2;
  db_iframe_pcforne.hide();
}
function js_pesquisapc61_certif(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_pcfornecert','db_iframe_pccertif','func_pccertif.php?funcao_js=parent.js_mostrapccertif1|pc59_certif|pc59_descr','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.pc61_certif.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_pcfornecert','db_iframe_pccertif','func_pccertif.php?pesquisa_chave='+document.form1.pc61_certif.value+'&funcao_js=parent.js_mostrapccertif','Pesquisa',false);
     }else{
       document.form1.pc59_descr.value = ''; 
     }
  }
}
function js_mostrapccertif(chave,erro){
  document.form1.pc59_descr.value = chave; 
  if(erro==true){ 
    document.form1.pc61_certif.focus(); 
    document.form1.pc61_certif.value = ''; 
  }
}
function js_mostrapccertif1(chave1,chave2){
  document.form1.pc61_certif.value = chave1;
  document.form1.pc59_descr.value = chave2;
  db_iframe_pccertif.hide();
}
</script>