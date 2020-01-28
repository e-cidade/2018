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

//MODULO: recursoshumanos
include(modification("dbforms/db_classesgenericas.php"));
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrhestagioagendadata->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("h57_sequencial");
//$clrotulo->label("h64_sequencial");
$clrotulo->label("h64_estagioagenda");
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
     //$h64_estagioagenda = "";
     $h64_data       = "";
     $h64_sequencial = "";
     $h64_seqaval    = '';
   }
} 
if ($db_opcao == 33 or $db_opcao == 3){
 
   $btnOkonClick = "onclick='return confirm (\"A exclusão do agendamento ira excluir também as avaliacoes.\\nConfirmar?\")'";
   $db_botao     = true;
}else{
   $btnOkonClick = '';
}
?>
<form name="form1" method="post" action="">
<center>
<table>
  <tr>
     <td>
       <fieldset><legend><b>Datas</b></legend> 
<table border="0">
  <tr>
    <td nowrap title="<?=@$Th64_sequencial?>">
       <?=@$Lh64_sequencial?>
    </td>
    <td> 
<?
db_input('h64_sequencial',10,@$Ih64_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
    <tr style='display:none'>
    <td nowrap title="<?=@$Th64_estagioagenda?>">
       <?
       db_ancora(@$Lh64_estagioagenda,"js_pesquisah64_estagioagenda(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('h64_estagioagenda',10,@$Ih64_estagioagenda,true,'text',$db_opcao," onchange='js_pesquisah64_estagioagenda(false);'")
?>
       <?
db_input('z01_nome',40,@$Ih57_sequencial,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th64_data?>">
       <?=@$Lh64_data?>
    </td>
    <td> 
<?
db_inputdata('h64_data',@$h64_data_dia,@$h64_data_mes,@$h64_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
    <tr>
    <td nowrap title="<?=@$Th64_seqaval?>">
       <?
       echo @$Lh64_seqaval;
       ?>
    </td>
    <td> 
     <?
      db_input('h64_seqaval',10,@$Ih64_seqaval,true,'text',$db_opcao);
     ?>
    </td>
  </tr>
  </table>
  </fieldset>
  </td>
  </tr>
  <tr>
  
    <td colspan="2" align="center">
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao == false) ? "disabled" : ""?> <?=$btnOkonClick ?> >
      <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
  </table>
 <table>
  <tr>
    <td valign="top"  align="center">  
    <?
	 $chavepri= array("h64_sequencial"=>@$h64_sequencial);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $clrhestagioagendadata->sql_query_file(null,"*","h64_data","h64_estagioagenda=".@$h64_estagioagenda);
	 $cliframe_alterar_excluir->campos  ="h64_sequencial,h64_seqaval, h64_data";
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
function js_pesquisah64_estagioagenda(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_rhestagioagendadata','db_iframe_rhestagioagenda','func_rhestagioagenda.php?funcao_js=parent.js_mostrarhestagioagenda1|h57_sequencial|z01_nome','Pesquisa',true,'0');
  }else{
     if(document.form1.h64_estagioagenda.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_rhestagioagendadata','db_iframe_rhestagioagenda','func_rhestagioagenda.php?pesquisa_chave='+document.form1.h64_estagioagenda.value+'&funcao_js=parent.js_mostrarhestagioagenda','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostrarhestagioagenda(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.h64_estagioagenda.focus(); 
    document.form1.h64_estagioagenda.value = ''; 
  }
}
function js_mostrarhestagioagenda1(chave1,chave2){
  document.form1.h64_estagioagenda.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_rhestagioagenda.hide();
}
</script>