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

//MODULO: marcas
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clmarcaloc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ma04_c_descr");
$db_botao1 = false;
if(isset($opcao) && $opcao=="alterar"){
 $db_opcao = 2;
 $db_botao1 = true;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
 $db_botao1 = true;
 $db_opcao = 3;
 if(isset($db_opcaol)){
  $db_opcao=33;
 }
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
  <td nowrap title="<?=@$Tma05_i_codigo?>">
   <?db_ancora(@$Lma05_i_codigo,"",3);?>
  </td>
  <td>
   <?db_input('ma05_i_codigo',10,@$ma05_i_codigo,true,'text',3)?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tma05_i_marca?>">
   <?db_ancora(@$Lma05_i_marca,"",3);?>
  </td>
  <td>
   <?db_input('ma05_i_marca',10,@$ma05_i_marca,true,'text',3)?>
   <?db_input('z01_nome',40,@$z01_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tma05_i_local?>">
   <?db_ancora(@$Lma05_i_local,"js_pesquisama05_i_local(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ma05_i_local',10,@$Ima05_i_local,true,'text',$db_opcao," onchange='js_pesquisama05_i_local(false);'")?>
   <?db_input('ma04_c_descr',40,@$Ima04_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td align="center" colspan="2">
   <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
   <input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
  </td>
 </tr>
</table>
<table width="100%">
 <tr>
  <td valign="top"><br>
  <?
   $chavepri= array("ma05_i_codigo"=>@$ma05_i_codigo,"ma05_i_marca"=>@$ma05_i_marca,"ma05_i_local"=>@$ma05_i_local,"ma04_c_descr"=>@$ma04_c_descr);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   $cliframe_alterar_excluir->sql = $clmarcaloc->sql_query("","marcaloc.ma05_i_codigo,marcaloc.ma05_i_marca,marcaloc.ma05_i_local,localmarca.ma04_i_codigo,localmarca.ma04_c_descr,localmarca.ma04_c_subdistrito","","marcaloc.ma05_i_marca = ".@$ma05_i_marca);
   //$cliframe_alterar_excluir->sql_disabled  = $clautoracervo->sql_query("","*","","bi21_acervo = $bi21_acervo");
   $cliframe_alterar_excluir->campos  ="ma04_i_codigo,ma04_c_descr,ma04_c_subdistrito";
   $cliframe_alterar_excluir->legenda="LOCALIDADES CADASTRADAS - MARCA N° ".@$ma05_i_marca;
   $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="darkblue";
   $cliframe_alterar_excluir->textocorpo ="black";
   $cliframe_alterar_excluir->fundocabec ="#aacccc";
   $cliframe_alterar_excluir->fundocorpo ="#ccddcc";
   $cliframe_alterar_excluir->iframe_height ="100";
   $cliframe_alterar_excluir->formulario = false;
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
</table>
</center>
</form>
<script>
function js_pesquisama05_i_local(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_localmarca','func_localmarca.php?funcao_js=parent.js_mostralocalmarca1|ma04_i_codigo|ma04_c_descr','Pesquisa',true);
 }else{
  if(document.form1.ma05_i_local.value != ''){
     js_OpenJanelaIframe('','db_iframe_localmarca','func_localmarca.php?pesquisa_chave='+document.form1.ma05_i_local.value+'&funcao_js=parent.js_mostralocalmarca','Pesquisa',false);
  }else{
    document.form1.ma04_c_descr.value = '';
  }
 }
}
function js_mostralocalmarca(chave,erro){
 document.form1.ma04_c_descr.value = chave;
 if(erro==true){
  document.form1.ma05_i_local.focus();
  document.form1.ma05_i_local.value = '';
 }
}
function js_mostralocalmarca1(chave1,chave2){
 document.form1.ma05_i_local.value = chave1;
 document.form1.ma04_c_descr.value = chave2;
 db_iframe_localmarca.hide();
}
</script>