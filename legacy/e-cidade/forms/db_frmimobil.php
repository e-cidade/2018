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

//MODULO: cadastro
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$climobil->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j01_numcgm");
$clrotulo->label("z01_nome");
if(isset($db_opcaoal)){
    $db_opcao=33;
    $db_botao=false;
}else if(isset($opcao) && $opcao=="alterar"){
    $db_botao=true;
    $db_opcao = 2;
}else if(isset($opcao) && $opcao=="excluir"){
    $db_opcao = 3;
    $db_botao=true;
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
<tr>
	<br>
	<br>
    <td nowrap title="<?=@$Tj44_numcgm?>">
       <?
       db_ancora(@$Lj44_numcgm,"js_pesquisaj44_numcgm(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('j44_numcgm',10,$Ij44_numcgm,true,'text',3," onchange='js_pesquisaj44_numcgm(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  
  <tr>
    <td nowrap title="<?=@$Tj44_matric?>">
       <?
       db_ancora(@$Lj44_matric,"js_pesquisaj44_matric(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j44_matric',10,$Ij44_matric,true,'text',$db_opcao," onchange='js_pesquisaj44_matric(false);'");
db_input('j44_matric_ant',10,$Ij44_matric,true,'hidden',3,"");
?>
       <?
db_input('dono',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
    <?
    if ($db_opcao!=22){
    
    ?>
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
 <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
<?}?>
    </td>
  </tr>
  </table>
 <table>
  <tr>
    <td valign="top"  align="center">  
    <?
	 $chavepri= array("j44_matric"=>@$j44_matric);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $climobil->sql_query(null,"j44_matric,a.z01_nome","j44_matric","j44_numcgm=".@$j44_numcgm);	 
	 $cliframe_alterar_excluir->campos  ="j44_matric,z01_nome";
	 $cliframe_alterar_excluir->legenda="MATRICULAS";
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
function js_pesquisaj44_matric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_iptubase','func_iptubase.php?funcao_js=parent.js_mostraiptubase1|j01_matric|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.j44_matric.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_iptubase','func_iptubase.php?pesquisa_chave='+document.form1.j44_matric.value+'&funcao_js=parent.js_mostraiptubase','Pesquisa',false);
     }else{
       document.form1.dono.value = ''; 
     }
  }
}
function js_mostraiptubase(chave,erro){
  document.form1.dono.value = chave; 
  if(erro==true){ 
    document.form1.j44_matric.focus(); 
    document.form1.j44_matric.value = ''; 
  }
}
function js_mostraiptubase1(chave1,chave2){
  document.form1.j44_matric.value = chave1;
  document.form1.dono.value = chave2;
  db_iframe_iptubase.hide();
}
function js_pesquisaj44_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.j44_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.j44_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.j44_numcgm.focus(); 
    document.form1.j44_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.j44_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_cadimobil','func_cadimobil.php?funcao_js=parent.js_preenchepesquisa|j63_numcgm','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_cadimobil.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>