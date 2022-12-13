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
$cldb_projetosativcli->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("at61_descr");
$clrotulo->label("at60_codcli");
$clrotulo->label("at62_descr");
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
     $at64_codativ = "";
     $at64_dtini = "";
     $at64_dtfim = "";
     $at64_situacao = "";
   }
   unset($at64_sequencial);
   
} 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tat64_sequencial?>">
       <?=@$Lat64_sequencial?>
    </td>
    <td> 
<?
db_input('at64_sequencial',10,$Iat64_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat64_codproj?>">
       <?
       db_ancora(@$Lat64_codproj,"js_pesquisaat64_codproj(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('at64_codproj',10,$Iat64_codproj,true,'text',3," onchange='js_pesquisaat64_codproj(false);'")
?>
       <?
db_input('at60_codcli',5,$Iat60_codcli,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat64_codativ?>">
       <?
       db_ancora(@$Lat64_codativ,"js_pesquisaat64_codativ(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('at64_codativ',4,$Iat64_codativ,true,'text',$db_opcao," onchange='js_pesquisaat64_codativ(false);'")
?>
       <?
db_input('at62_descr',40,$Iat62_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat64_dtini?>">
       <?=@$Lat64_dtini?>
    </td>
    <td> 
<?
db_inputdata('at64_dtini',@$at64_dtini_dia,@$at64_dtini_mes,@$at64_dtini_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat64_dtfim?>">
       <?=@$Lat64_dtfim?>
    </td>
    <td> 
<?
db_inputdata('at64_dtfim',@$at64_dtfim_dia,@$at64_dtfim_mes,@$at64_dtfim_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat64_situacao?>">
       <?
       db_ancora(@$Lat64_situacao,"js_pesquisaat64_situacao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('at64_situacao',10,$Iat64_situacao,true,'text',$db_opcao," onchange='js_pesquisaat64_situacao(false);'")
?>
       <?
db_input('at61_descr',40,$Iat61_descr,true,'text',3,'')
       ?>
    </td>
  </tr>


  <tr>
    <td nowrap title="<?=@$Tat64_descricao?>">
       <?=@$Lat64_descricao?>
    </td>
    <td> 
<?
db_textarea('at64_descricao',5,60,$Iat64_descricao,true,'text',$db_opcao,"")
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
	 $chavepri= array("at64_sequencial"=>@$at64_sequencial,"at64_codproj"=>@$at64_codproj);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $cldb_projetosativcli->sql_query_file(null,"*",null," at64_codproj = $at64_codproj");
	 $cliframe_alterar_excluir->campos  ="at64_sequencial,at64_codproj,at64_codativ,at64_dtini,at64_dtfim,at64_situacao";
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
function js_pesquisaat64_situacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_db_projetosativcli','db_iframe_db_projetosituacao','func_db_projetosituacao.php?funcao_js=parent.js_mostradb_projetosituacao1|at61_codigo|at61_descr','Pesquisa',true,'0','1');
  }else{
     if(document.form1.at64_situacao.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_db_projetosativcli','db_iframe_db_projetosituacao','func_db_projetosituacao.php?pesquisa_chave='+document.form1.at64_situacao.value+'&funcao_js=parent.js_mostradb_projetosituacao','Pesquisa',false);
     }else{
       document.form1.at61_descr.value = ''; 
     }
  }
}
function js_mostradb_projetosituacao(chave,erro){
  document.form1.at61_descr.value = chave; 
  if(erro==true){ 
    document.form1.at64_situacao.focus(); 
    document.form1.at64_situacao.value = ''; 
  }
}
function js_mostradb_projetosituacao1(chave1,chave2){
  document.form1.at64_situacao.value = chave1;
  document.form1.at61_descr.value = chave2;
  db_iframe_db_projetosituacao.hide();
}
function js_pesquisaat64_codproj(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_db_projetosativcli','db_iframe_db_projetoscliente','func_db_projetoscliente.php?funcao_js=parent.js_mostradb_projetoscliente1|at60_codproj|at60_codcli','Pesquisa',true,'0','1');
  }else{
     if(document.form1.at64_codproj.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_db_projetosativcli','db_iframe_db_projetoscliente','func_db_projetoscliente.php?pesquisa_chave='+document.form1.at64_codproj.value+'&funcao_js=parent.js_mostradb_projetoscliente','Pesquisa',false);
     }else{
       document.form1.at60_codcli.value = ''; 
     }
  }
}
function js_mostradb_projetoscliente(chave,erro){
  document.form1.at60_codcli.value = chave; 
  if(erro==true){ 
    document.form1.at64_codproj.focus(); 
    document.form1.at64_codproj.value = ''; 
  }
}
function js_mostradb_projetoscliente1(chave1,chave2){
  document.form1.at64_codproj.value = chave1;
  document.form1.at60_codcli.value = chave2;
  db_iframe_db_projetoscliente.hide();
}
function js_pesquisaat64_codativ(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_db_projetosativcli','db_iframe_db_projetosativid','func_db_projetosativid.php?funcao_js=parent.js_mostradb_projetosativid1|at62_codigo|at62_descr','Pesquisa',true,'0','1');
  }else{
     if(document.form1.at64_codativ.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_db_projetosativcli','db_iframe_db_projetosativid','func_db_projetosativid.php?pesquisa_chave='+document.form1.at64_codativ.value+'&funcao_js=parent.js_mostradb_projetosativid','Pesquisa',false);
     }else{
       document.form1.at62_descr.value = ''; 
     }
  }
}
function js_mostradb_projetosativid(chave,erro){
  document.form1.at62_descr.value = chave; 
  if(erro==true){ 
    document.form1.at64_codativ.focus(); 
    document.form1.at64_codativ.value = ''; 
  }
}
function js_mostradb_projetosativid1(chave1,chave2){
  document.form1.at64_codativ.value = chave1;
  document.form1.at62_descr.value = chave2;
  db_iframe_db_projetosativid.hide();
}
</script>