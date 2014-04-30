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

//MODULO: material
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$cldb_almoxdepto->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("m91_codigo");
$clrotulo->label("descrdepto");
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
     $m92_depto = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<fieldset>
<table border="0">
  <tr>
    <td> 
      <?
      //db_input($nome,$dbsize,$dbvalidatipo,$dbcadastro,$dbhidden='text',$db_opcao=3,$js_script="",$nomevar="",$bgcolor="");
        db_input('m92_codalmox',6,$Im92_codalmox,true,'hidden',$db_opcao," onchange='js_pesquisam92_codalmox(false);'");
        db_input('m91_codigo',6,$Im91_codigo,true,'hidden',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm92_depto?>">
      <?
        db_ancora(@$Lm92_depto,"js_pesquisam92_depto(true);",$db_opcao);
      ?>
    </td>
    <td> 
      <?
        db_input('m92_depto', 5, $Im92_depto,true,'text', $db_opcao, " onchange='js_pesquisam92_depto(false);'");
        db_input('descrdepto', 40, $Idescrdepto,true, 'text', 3 ,'');
      ?>
    </td>
  </tr>

  <tr>
    <td colspan="2" align="center">
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
      <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
  </table>
  </fieldset>
  </center>
    
 <table>
  <tr>
    <td valign="top" style="padding-top:10px" align="center">  
    <?
	  $chavepri= array("m92_codalmox"=>@$m92_codalmox,"m92_depto"=>@$m92_depto);
	  $cliframe_alterar_excluir->chavepri      = $chavepri;
	  $cliframe_alterar_excluir->sql           = $cldb_almoxdepto->sql_query($m92_codalmox,null,"db_almoxdepto.m92_codalmox,db_almoxdepto.m92_depto, db_depart.descrdepto");
	  $cliframe_alterar_excluir->campos        = "m92_codalmox,m92_depto,descrdepto";
	  $cliframe_alterar_excluir->legenda       = "ITENS LANÇADOS";
	  $cliframe_alterar_excluir->iframe_height = "300";
	  $cliframe_alterar_excluir->iframe_width  = "700";
	  $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
    </td>
   </tr>
 </table>
  
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
function js_pesquisam92_codalmox(mostra){
  if(mostra == true){
    js_OpenJanelaIframe('','db_iframe_db_almox','func_db_almox.php?funcao_js=parent.js_mostradb_almox1|m91_codigo|m91_codigo','Pesquisa',true,0);
  }else{
     if(document.form1.m92_codalmox.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_db_almox','func_db_almox.php?pesquisa_chave='+document.form1.m92_codalmox.value+'&funcao_js=parent.js_mostradb_almox','Pesquisa',false);
     } else {
       document.form1.m91_codigo.value = ''; 
     }
  }
}
function js_mostradb_almox(chave,erro){
  document.form1.m91_codigo.value = chave; 
  if(erro==true){ 
    document.form1.m92_codalmox.focus(); 
    document.form1.m92_codalmox.value = ''; 
  }
}
function js_mostradb_almox1(chave1,chave2){
  document.form1.m92_codalmox.value = chave1;
  document.form1.m91_codigo.value = chave2;
  db_iframe_db_almox.hide();
}
function js_pesquisam92_depto(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_mostradb_depart1|coddepto|descrdepto','Pesquisa',true,0);
  }else{
     if(document.form1.m92_depto.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_db_depart','func_db_depart.php?pesquisa_chave='+document.form1.m92_depto.value+'&funcao_js=parent.js_mostradb_depart','Pesquisa',false);
     }else{
       document.form1.descrdepto.value = ''; 
     }
  }
}
function js_mostradb_depart(chave,erro){
  document.form1.descrdepto.value = chave; 
  if(erro==true){ 
    document.form1.m92_depto.focus(); 
    document.form1.m92_depto.value = ''; 
  }
}
function js_mostradb_depart1(chave1,chave2){
  document.form1.m92_depto.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_db_depart.hide();
}
</script>