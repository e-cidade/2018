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

//MODULO: biblioteca
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clexemplar->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("bi06_titulo");
$db_botao1 = false;
if(isset($opcao) && $opcao=="alterar"){
 $db_opcao = 2;
 $db_botao1 = true;
 $bi23_dataaquisicao_dia = substr($bi23_dataaquisicao,0,2);
 $bi23_dataaquisicao_mes = substr($bi23_dataaquisicao,3,2);
 $bi23_dataaquisicao_ano = substr($bi23_dataaquisicao,6,4);
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
 $bi23_dataaquisicao_dia = substr($bi23_dataaquisicao,0,2);
 $bi23_dataaquisicao_mes = substr($bi23_dataaquisicao,3,2);
 $bi23_dataaquisicao_ano = substr($bi23_dataaquisicao,6,4);
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
  <td nowrap title="<?=@$Tbi23_codigo?>">
   <?=@$Lbi23_codigo?>
  </td>
  <td>
   <?db_input('bi23_codigo',10,@$Ibi23_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tbi23_acervo?>">
   <?db_ancora(@$Lbi23_acervo,"",3);?>
  </td>
  <td>
   <?db_input('bi23_acervo',10,@$Ibi23_acervo,true,'text',3,"")?>
   <?db_input('bi06_titulo',40,@$Ibi06_titulo,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tbi23_codbarras?>">
   <?=@$Lbi23_codbarras?>
  </td>
  <td>
   <?db_input('bi23_codbarras',20,@$Ibi23_codbarras,true,'text',3," onKeyPress='tab(event,5)'")?>
   <input name="gerabarras" type="button" id="gerabarras" value="Gerar Codigo" onclick="js_GeraBarras()" <?=($db_opcao!=1?"disabled":"")?> >
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tbi23_dataaquisicao?>">
   <?=@$Lbi23_dataaquisicao?>
  </td>
  <td>
   <?db_inputdata('bi23_dataaquisicao',@$bi23_dataaquisicao_dia,@$bi23_dataaquisicao_mes,@$bi23_dataaquisicao_ano,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tbi23_aquisicao?>">
   <?db_ancora(@$Lbi23_aquisicao,"js_pesquisabi23_aquisicao(true);",$db_opcao);?><br>
  </td>
  <td>
   <?db_input('bi23_aquisicao',10,@$Ibi06_aquisicao,true,'text',$db_opcao," onchange='js_pesquisabi23_aquisicao(false);' onKeyPress='tab(event,9)'")?>
   <?db_input('bi04_forma',40,@$Ibi04_forma,true,'text',3,"onKeyPress='tab(event,10)'")?>
  </td>
 </tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table>
 <tr>
  <td valign="top">
  <?
   $campos = "bi23_codigo,
              bi23_acervo,
              bi06_titulo,
              bi04_forma,
              bi23_codbarras,
              bi23_dataaquisicao,
              bi23_aquisicao,
              case when bi23_situacao = 'S'
               then 'ATIVO'
               else 'INATIVO'
              end as bi23_situacao
             ";
   $chavepri= array("bi23_codigo"=>@$bi23_codigo,"bi23_acervo"=>@$bi23_acervo,"bi06_titulo"=>@$bi06_titulo,"bi23_dataaquisicao"=>@$bi23_dataaquisicao,"bi23_codbarras"=>@$bi23_codbarras,"bi04_forma"=>@$bi04_forma,"bi23_aquisicao"=>@$bi23_aquisicao);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clexemplar->sql_query("",$campos,"bi23_situacao,bi23_codigo"," bi23_acervo = $bi23_acervo");
   $cliframe_alterar_excluir->campos  ="bi23_codigo,bi23_codbarras,bi04_forma,bi23_dataaquisicao,bi23_situacao";
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
   $cliframe_alterar_excluir->formulario = false;
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
</table>
</center>
</form>
<script>
function js_GeraBarras(){
 var num = new Date();
 var barras = num.getMonth()+"0"+num.getFullYear()+num.getHours()+num.getSeconds()+"0"+num.getMilliseconds();
 document.getElementById('bi23_codbarras').value = barras;
}
function js_pesquisabi23_aquisicao(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_aquisicao','func_aquisicao.php?funcao_js=parent.js_mostraaquisicao1|bi04_codigo|bi04_forma','Pesquisa',true);
 }else{
  if(document.form1.bi23_aquisicao.value != ''){
   js_OpenJanelaIframe('','db_iframe_aquisicao','func_aquisicao.php?pesquisa_chave='+document.form1.bi23_aquisicao.value+'&funcao_js=parent.js_mostraaquisicao','Pesquisa',false);
  }else{
   document.form1.bi04_forma.value = '';
  }
 }
}
function js_mostraaquisicao(chave,erro){
 document.form1.bi04_forma.value = chave;
 if(erro==true){
  document.form1.bi23_aquisicao.focus();
  document.form1.bi23_aquisicao.value = '';
 }
}
function js_mostraaquisicao1(chave1,chave2){
 document.form1.bi23_aquisicao.value = chave1;
 document.form1.bi04_forma.value = chave2;
 db_iframe_aquisicao.hide();
}
</script>