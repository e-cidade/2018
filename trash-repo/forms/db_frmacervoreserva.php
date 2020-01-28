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
$clacervoreserva->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("bi06_titulo");
$db_botao1 = false;
if(isset($opcao) && $opcao=="alterar"){
 $db_opcao = 2;
 $db_opcao1 = 3;
 $db_botao1 = true;
 $bi20_data_dia = substr($bi20_data,0,2);
 $bi20_data_mes = substr($bi20_data,3,2);
 $bi20_data_ano = substr($bi20_data,6,4);
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
 $db_botao1 = true;
 $db_opcao = 3;
 $db_opcao1 = 3;
 $bi20_data_dia = substr($bi20_data,0,2);
 $bi20_data_mes = substr($bi20_data,3,2);
 $bi20_data_ano = substr($bi20_data,6,4);
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
  <td nowrap title="<?=@$Tbi20_codigo?>">
   <?=@$Lbi20_codigo?>
  </td>
  <td>
   <?db_input('bi20_codigo',10,$Ibi20_codigo,true,'text',3,"")?>
   <?db_input('bi20_reserva',10,$Ibi20_reserva,true,'hidden',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tbi20_data?>">
   <?=@$Lbi20_data?>
  </td>
  <td>
   <?db_inputdata('bi20_data',@$bi20_data_dia,@$bi20_data_mes,@$bi20_data_ano,true,'text',$db_opcao,"")?>
   <?=@$Lbi20_hora?>
   <?db_input('bi20_hora',5,$bi20_hora,true,'text',$db_opcao,"OnKeyUp=\"mascara_hora(this.value,6)\"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tbi20_acervo?>">
   <?db_ancora(@$Lbi20_acervo,"js_pesquisabi20_acervo(true);",$db_opcao1);?>
  </td>
  <td>
   <?db_input('bi20_acervo',10,$Ibi20_acervo,true,'text',$db_opcao1," onchange='js_pesquisabi20_acervo(false);'")?>
   <?db_input('bi06_titulo',30,$Ibi06_titulo,true,'text',3,'')?>
  </td>
 </tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>>
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<input name="emprestimo" type="submit" value="Empréstimo" style="visibility:hidden;position:absolute;" <?=($db_botao==false?"disabled":"")?>>
<table>
 <tr>
  <td valign="top">
  <?
   $campos = "bi20_codigo,
              bi20_reserva,
              bi20_acervo,
              bi06_titulo,
              bi20_data,
              bi20_hora,
              case
               when bi20_retirada is null
                then 'NÂO RETIRADO'
                else 'RETIRADO'
              end as situacao
             ";
   $chavepri= array("bi20_codigo"=>@$bi20_codigo,"bi20_reserva"=>@$bi20_reserva,"bi20_acervo"=>@$bi20_acervo,"bi06_titulo"=>@$bi06_titulo,"bi20_data"=>@$bi20_data,"bi20_hora"=>@$bi20_hora);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clacervoreserva->sql_query("",$campos,""," bi20_reserva = $bi20_reserva");
   $cliframe_alterar_excluir->campos  ="bi20_acervo,bi06_titulo,bi20_data,bi20_hora,situacao";
   $cliframe_alterar_excluir->legenda="Registros";
   $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->iframe_height ="110";
   $cliframe_alterar_excluir->iframe_width ="600";
   $cliframe_alterar_excluir->tamfontecabec = 9;
   $cliframe_alterar_excluir->tamfontecorpo = 9;
   $cliframe_alterar_excluir->formulario = false;
   /*
   if(isset($bi14_situacao) && $bi14_situacao!="A"){
    $cliframe_alterar_excluir->opcoes = 4;
   }else{
    $cliframe_alterar_excluir->opcoes = 3;
   }
   */
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
</table>
</form>
</center>
<script>
function js_pesquisabi20_acervo(mostra){
 if(document.form1.bi20_data_dia.value==""||document.form1.bi20_data_mes.value==""||document.form1.bi20_data_ano.value==""){
  alert("Preencha a data da reserva primeiro!");
  document.form1.bi20_acervo.value = "";
 }else{
  datareserva = document.form1.bi20_data_ano.value+"-"+document.form1.bi20_data_mes.value+"-"+document.form1.bi20_data_dia.value;
  if(mostra==true){
   js_OpenJanelaIframe('top.corpo','db_iframe_acervo','func_exemplarreserva.php?datareserva='+datareserva+'&reserva=<?=$bi20_reserva?>&funcao_js=parent.acervoreserva.js_mostraacervo1|bi06_seq|bi06_titulo','Pesquisa',true);
  }else{
   if(document.form1.bi20_acervo.value != ''){
    js_OpenJanelaIframe('top.corpo','db_iframe_acervo','func_exemplarreserva.php?datareserva='+datareserva+'&reserva=<?=$bi20_reserva?>&pesquisa_chave3='+document.form1.bi20_acervo.value+'&funcao_js=parent.acervoreserva.js_mostraacervo','Pesquisa',false);
   }else{
    document.form1.bi06_titulo.value = '';
   }
  }
 }
}
function js_mostraacervo(chave,erro){
 document.form1.bi06_titulo.value = chave;
 if(erro==true){
  document.form1.bi20_acervo.focus();
  document.form1.bi20_acervo.value = '';
 }
}
function js_mostraacervo1(chave1,chave2){
 document.form1.bi20_acervo.value = chave1;
 document.form1.bi06_titulo.value = chave2;
 parent.db_iframe_acervo.hide();
}
function mascara_hora(hora,x){
 var myhora = '';
 myhora = myhora + hora;
 if(myhora.length == 2){
  myhora = myhora + ':';
  document.form1[x].value = myhora;
 }
 if(myhora.length == 5){
  verifica_hora(x);
 }
}

function verifica_hora(x){
 hrs = (document.form1[x].value.substring(0,2));
 min = (document.form1[x].value.substring(3,5));
 situacao = "";
 // verifica hora
 if ( (hrs < 00 ) || (hrs > 23) || ( min < 00) || ( min > 59) ) {
  alert("E R R O !!!\n\nHora inválida!\nPreencha corretamente o campo!");
  document.form1[x].value="";
  document.form1[x].focus();
 }
}
</script>