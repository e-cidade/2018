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

//MODULO: saude
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clprocservicos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd09_c_descr");
$clrotulo->label("sd12_c_descr");

$db_botao1 = false;
if(isset($opcao) && $opcao=="alterar"){
 $db_opcao = 2;
 $db_botao1 = true;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
 $db_botao1 = true;
 $db_opcao = 3;
}else{
 if(isset($alterar)){
  $db_opcao = 2;
  $db_botao1 = true;
 }else{
  $db_opcao = 1;
 }
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tsd19_i_codigo?>">
       <?=@$Lsd19_i_codigo?>
    </td>
    <td> 
<?
db_input('sd19_i_codigo',10,$Isd19_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd19_i_procedimento?>">
       <?
       db_ancora(@$Lsd19_i_procedimento,"js_pesquisasd19_i_procedimento(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('sd19_i_procedimento',10,$Isd19_i_procedimento,true,'text',3," onchange='js_pesquisasd19_i_procedimento(false);'")
?>
       <?
db_input('sd09_c_descr',100,$Isd09_c_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd19_i_servico?>">
       <?
       db_ancora(@$Lsd19_i_servico,"js_pesquisasd19_i_servico(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd19_i_servico',10,$Isd19_i_servico,true,'text',$db_opcao," onchange='js_pesquisasd19_i_servico(false);'")
?>
       <?
db_input('sd12_c_descr',40,$Isd12_c_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
</form>
<table>
 <tr>
  <td valign="top"><br>
  <?
   $chavepri= array("sd19_i_codigo"=>@$sd19_i_codigo,"sd19_i_procedimento"=>@$sd19_i_procedimento,"sd19_i_servico"=>@$sd19_i_servico,"sd12_c_descr"=>@$sd12_c_descr);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clprocservicos->sql_query($sd19_i_codigo,"sd19_i_codigo,sd12_i_codigo,sd19_i_procedimento,sd12_c_descr,sd19_i_servico","sd12_i_codigo","sd19_i_procedimento = $sd19_i_procedimento");
   $cliframe_alterar_excluir->campos  ="sd12_i_codigo,sd12_c_descr";
   $cliframe_alterar_excluir->legenda="Registros";
   $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->iframe_height ="200";
   $cliframe_alterar_excluir->iframe_width ="650";
   $cliframe_alterar_excluir->tamfontecabec = 9;
   $cliframe_alterar_excluir->tamfontecorpo = 9;
   $cliframe_alterar_excluir->formulario = false;
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
</table>
<script>
function js_pesquisasd19_i_procedimento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_procedimentos','func_procedimentos.php?funcao_js=parent.js_mostraprocedimentos1|sd09_i_codigo|sd09_c_descr','Pesquisa',true);
  }else{
     if(document.form1.sd19_i_procedimento.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_procedimentos','func_procedimentos.php?pesquisa_chave='+document.form1.sd19_i_procedimento.value+'&funcao_js=parent.js_mostraprocedimentos','Pesquisa',false);
     }else{
       document.form1.sd09_c_descr.value = ''; 
     }
  }
}
function js_mostraprocedimentos(chave,erro){
  document.form1.sd09_c_descr.value = chave; 
  if(erro==true){ 
    document.form1.sd19_i_procedimento.focus(); 
    document.form1.sd19_i_procedimento.value = ''; 
  }
}
function js_mostraprocedimentos1(chave1,chave2){
  document.form1.sd19_i_procedimento.value = chave1;
  document.form1.sd09_c_descr.value = chave2;
  db_iframe_procedimentos.hide();
}
function js_pesquisasd19_i_servico(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_servicos','func_servicos.php?funcao_js=parent.js_mostraservicos1|sd12_i_codigo|sd12_c_descr','Pesquisa',true);
  }else{
     if(document.form1.sd19_i_servico.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_servicos','func_servicos.php?pesquisa_chave='+document.form1.sd19_i_servico.value+'&funcao_js=parent.js_mostraservicos','Pesquisa',false);
     }else{
       document.form1.sd12_c_descr.value = ''; 
     }
  }
}
function js_mostraservicos(chave,erro){
  document.form1.sd12_c_descr.value = chave; 
  if(erro==true){ 
    document.form1.sd19_i_servico.focus(); 
    document.form1.sd19_i_servico.value = ''; 
  }
}
function js_mostraservicos1(chave1,chave2){
  document.form1.sd19_i_servico.value = chave1;
  document.form1.sd12_c_descr.value = chave2;
  db_iframe_servicos.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_procservicos','func_procservicos.php?funcao_js=parent.js_preenchepesquisa|sd19_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_procservicos.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
document.form1.sd19_i_procedimento.value = parent.iframe_a1.document.form1.sd09_i_codigo.value;
document.form1.sd09_c_descr.value = parent.iframe_a1.document.form1.sd09_c_descr.value;
</script>