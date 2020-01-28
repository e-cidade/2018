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

//MODULO: patrim
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clbenscorr->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("t52_descr");

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
}
if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){
  $t63_codbem = "";
  $t63_valcor = "";
  $t63_deprec = "";
  $t52_descr = "";
}
/*
if(isset($opcao)){
  $result = $clbenscorr->sql_record($clbenscorr->sql_query($t63_codcor,$t63_codbem));
//  db_criatabela($result);
//  die($clbenscorr->sql_query($t63_codcor,$t63_codbem));
  if($result!=false && $clbenscorr->numrows>0){
    db_fieldsmemory($result,0);
  }
}*/
 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tt63_codcor?>">
       <?=@$Lt63_codcor?>
    </td>
    <td> 
<?
db_input('t63_codcor',8,$It63_codcor,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt63_codbem?>">
       <?
       db_ancora(@$Lt63_codbem,"js_pesquisat63_codbem(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('t63_codbem',8,$It63_codbem,true,'text',$db_opcao," onchange='js_pesquisat63_codbem(false);'")
?>
<?
db_input('t52_descr',40,$It52_descr,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt63_valcor?>">
       <?=@$Lt63_valcor?>
    </td>
    <td> 
<?
db_input('t63_valcor',15,$It63_valcor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt63_deprec?>">
       <?=@$Lt63_deprec?>
    </td>
    <td> 
<?
db_input('t63_deprec',15,$It63_deprec,true,'text',$db_opcao,"")
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
	 $chavepri= array("t63_codcor"=>@$t63_codcor,"t63_codbem"=>null);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $clbenscorr->sql_query_file($t63_codcor,null);
	 $cliframe_alterar_excluir->campos  ="t63_codcor,t63_codbem,t63_valcor,t63_deprec";
	 $cliframe_alterar_excluir->legenda="ITENS LANÇADAS";
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
function js_pesquisat63_codbem(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_benscorr','db_iframe_bens','func_bens.php?funcao_js=parent.js_mostrabens1|t52_bem|t52_descr','Pesquisa',true);
  }else{
     if(document.form1.t63_codbem.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_benscorr','db_iframe_bens','func_bens.php?pesquisa_chave='+document.form1.t63_codbem.value+'&funcao_js=parent.js_mostrabens','Pesquisa',false);
     }else{
       document.form1.t52_descr.value = '';
     } 
  }
}
function js_mostrabens(chave,erro){
  document.form1.t52_descr.value = chave;
  if(erro==true){ 
    document.form1.t63_codbem.focus(); 
    document.form1.t63_codbem.value = ''; 
  }
}
function js_mostrabens1(chave1,chave2){
  document.form1.t63_codbem.value = chave1;
  document.form1.t52_descr.value = chave2;
  db_iframe_bens.hide();
}
</script>