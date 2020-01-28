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

//MODULO: educação
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clmsgaviso->rotulo->label();
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
  <td nowrap title="<?=@$Ted90_i_codigo?>">
   <?=@$Led90_i_codigo?>
  </td>
  <td>
   <?db_input('ed90_i_codigo',10,$Ied90_i_codigo,true,'text',3,"")?>
  </td>
  <td nowrap title="<?=@$Ted90_c_arqdestino?>">
   <?=@$Led90_c_arqdestino?>
  </td>
  <td>
   <?db_input('ed90_c_arqdestino',40,$Ied90_c_arqdestino,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted90_c_tabela?>">
   <?db_ancora(@$Led90_c_tabela,"js_pesquisaed90_c_tabela();",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed90_c_tabela',30,$Ied90_c_tabela,true,'text',3,"")?>
  </td>
  <td nowrap title="<?=@$Ted90_c_descrlink?>">
   <?=@$Led90_c_descrlink?>
  </td>
  <td>
   <?db_input('ed90_c_descrlink',40,$Ied90_c_descrlink,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted90_c_arquivo?>">
   <?=@$Led90_c_arquivo?>
  </td>
  <td>
   <?db_input('ed90_c_arquivo',40,$Ied90_c_arquivo,true,'text',$db_opcao,"")?>
  </td>
  <td nowrap title="<?=@$Ted90_c_titulolink?>">
   <?=@$Led90_c_titulolink?>
  </td>
  <td>
   <?db_input('ed90_c_titulolink',40,$Ied90_c_titulolink,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted90_c_arquivo?>">
   <?=@$Led90_c_modulo?>
  </td>
  <td>
   <?db_input('ed90_c_modulo',40,@$Ied90_c_modulo,true,'text',3,"")?>
  </td>
  <td></td>
  <td></td>
 </tr>
 <tr>
  <td colspan="4">
   <table>
    <tr>
     <td nowrap title="<?=@$Ted90_t_msg?>" valign="top">
      <?=@$Led90_t_msg?>
     </td>
     <td nowrap title="<?=@$Ted90_t_msg?>">
     <?db_textarea('ed90_t_msg',2,80,$Ied90_t_msg,true,'text',$db_opcao,"")?>
     </td>
    </tr
   </table>
  </td>
 </tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table width="100%">
 <tr>
  <td valign="top">
  <?
   $chavepri= array("ed90_i_codigo"=>@$ed90_i_codigo,"ed90_c_arquivo"=>@$ed90_c_arquivo,"ed90_c_tabela"=>@$ed90_c_tabela,"ed90_c_descrlink"=>@$ed90_c_descrlink,"ed90_c_arqdestino"=>@$ed90_c_arqdestino,"ed90_c_titulolink"=>@$ed90_c_titulolink,"ed90_t_msg"=>@$ed90_t_msg,"ed90_c_modulo"=>@$ed90_c_modulo);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clmsgaviso->sql_query("","*","ed90_c_tabela"," ed90_c_modulo = 'educacao'");
   $cliframe_alterar_excluir->campos  ="ed90_i_codigo,ed90_c_tabela,ed90_c_arquivo,ed90_c_modulo";
   $cliframe_alterar_excluir->legenda="Registros";
   $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->iframe_height ="180";
   $cliframe_alterar_excluir->iframe_width ="100%";
   $cliframe_alterar_excluir->tamfontecabec = 11;
   $cliframe_alterar_excluir->tamfontecorpo = 11;
   $cliframe_alterar_excluir->formulario = false;
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
</table>
</form>
</center>
<script>
function js_pesquisaed90_c_tabela(){
 js_OpenJanelaIframe('top.corpo','db_iframe_tabela','func_tabela_edu.php?funcao_js=parent.js_mostratabela|nomearq|nomemod','Pesquisa de Tabelas',true);
}
function js_mostratabela(chave1,chave2){
 document.form1.ed90_c_tabela.value = chave1;
 document.form1.ed90_c_modulo.value = chave2;
 db_iframe_tabela.hide();
}
</script>