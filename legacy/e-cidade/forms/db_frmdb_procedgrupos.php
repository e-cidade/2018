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

//MODULO: atendimento
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$cldb_procedgrupos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("at51_descr");
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
	  	$at52_sequencial = "";
	  	$at52_grupo      = "";
    	$at51_descr      = "";
   }
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
<?
db_input('at52_sequencial',10,$Iat52_sequencial,true,'hidden',$db_opcao,"")
?>
  <tr>
    <td nowrap title="<?=@$Tat52_proced?>">
       <?=@$Lat52_proced?>
    </td>
    <td> 
<?
db_input('at52_proced',10,$Iat52_proced,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat52_grupo?>">
       <?
       db_ancora(@$Lat52_grupo,"js_pesquisaat52_grupo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('at52_grupo',10,$Iat52_grupo,true,'text',$db_opcao," onchange='js_pesquisaat52_grupo(false);'")
?>
       <?
db_input('at51_descr',40,$Iat51_descr,true,'text',3,'')
       ?>
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
	 if(isset($db_opcao)&&$db_opcao==2) {
	 	$chavepri= array("at52_proced"=>@$at52_proced);
	 }
	 else if(isset($db_opcao)&&$db_opcao==3) {
	 	$chavepri= array("at52_proced"=>@$at52_proced);
	 }
     else if(isset($db_opcao)&&$db_opcao==1) {
	 	$chavepri= array("at52_sequencial"=>@$at52_sequencial,"at52_proced"=>@$at52_proced);
	 }

	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $cldb_procedgrupos->sql_query(null,"at52_sequencial,at52_proced,at51_descr","at52_sequencial","at52_proced=$at52_proced");
	 $cliframe_alterar_excluir->campos  ="at52_sequencial,at51_descr";
	 $cliframe_alterar_excluir->legenda="GRUPOS";
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
function js_pesquisaat52_grupo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_db_procedgrupos','db_iframe_db_procedcadgrupos','func_db_procedcadgrupos.php?funcao_js=parent.js_mostradb_procedcadgrupos1|at51_codigo|at51_descr','Pesquisa',true,'0');
  }else{
     if(document.form1.at52_grupo.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_db_procedgrupos','db_iframe_db_procedcadgrupos','func_db_procedcadgrupos.php?pesquisa_chave='+document.form1.at52_grupo.value+'&funcao_js=parent.js_mostradb_procedcadgrupos','Pesquisa',false);
     }else{
       document.form1.at51_descr.value = ''; 
     }
  }
}
function js_mostradb_procedcadgrupos(chave,erro){
  document.form1.at51_descr.value = chave; 
  if(erro==true){ 
    document.form1.at52_grupo.focus(); 
    document.form1.at52_grupo.value = ''; 
  }
}
function js_mostradb_procedcadgrupos1(chave1,chave2){
  document.form1.at52_grupo.value = chave1;
  document.form1.at51_descr.value = chave2;
  db_iframe_db_procedcadgrupos.hide();
}
</script>