<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

//MODULO: Biblioteca
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clautoracervo->rotulo->label();
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
}?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Tbi21_acervo?>">
   <?=@$Lbi21_acervo?>
  </td>
  <td>
   <?db_input('bi21_acervo',10,$Ibi21_acervo,true,'text',3,"")?>
   <?db_input('bi06_titulo',80,@$Ibi06_titulo,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tbi21_autor?>">
   <?db_ancora(@$Lbi21_autor,"js_pesquisabi21_autor(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('bi21_autor',10,$Ibi21_autor,true,'text',$db_opcao," onchange='js_pesquisabi21_autor(false);'")?>
   <?db_input('bi01_nome',50,@$Ibi01_nome,true,'text',3,'')?>
  </td>
 </tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table>
 <tr>
  <td valign="top">
  <?
   $chavepri= array("bi21_acervo"=>@$bi21_acervo,"bi06_titulo"=>@$bi06_titulo,"bi21_autor"=>@$bi21_autor,"bi01_nome"=>@$bi01_nome);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clautoracervo->sql_query("","*","bi01_nome"," bi21_acervo = $bi21_acervo");
   $cliframe_alterar_excluir->campos  ="bi01_nome";
   $cliframe_alterar_excluir->legenda="Registros";
   $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->iframe_height ="160";
   $cliframe_alterar_excluir->iframe_width ="650";
   $cliframe_alterar_excluir->tamfontecabec = 9;
   $cliframe_alterar_excluir->tamfontecorpo = 9;
   $cliframe_alterar_excluir->opcoes = 3;
   $cliframe_alterar_excluir->formulario = false;
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
</table>
</form>
</center>
<script>
function js_pesquisabi21_autor(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_autor','func_autor.php?funcao_js=parent.js_mostraautor1|bi01_codigo|bi01_nome','Pesquisa',true);
 }else{
  if(document.form1.bi21_autor.value != ''){
   js_OpenJanelaIframe('','db_iframe_autor','func_autor.php?pesquisa_chave='+document.form1.bi21_autor.value+'&funcao_js=parent.js_mostraautor','Pesquisa',false);
  }else{
   document.form1.bi01_nome.value = '';
  }
 }
}
function js_mostraautor(chave,erro){
 document.form1.bi01_nome.value = chave;
 if(erro==true){
  document.form1.bi21_autor.focus();
  document.form1.bi21_autor.value = '';
 }
}
function js_mostraautor1(chave1,chave2){
 document.form1.bi21_autor.value = chave1;
 document.form1.bi01_nome.value = chave2;
 db_iframe_autor.hide();
}
</script>