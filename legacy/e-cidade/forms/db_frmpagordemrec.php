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

include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

//MODULO: empenho
$clpagordemrec->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("e50_numemp");
$clrotulo->label("k02_descr");


if(isset($opcao) && $opcao=="alterar"){
    $db_botao=true;
    $db_opcao = 2;
}elseif(isset($opcao) && $opcao=="excluir"){
    $db_opcao = 3;
    if(isset($db_opcaoal)){
	$db_opcao=33;
        $db_botao=false;
    }
    $db_botao=true;
}else{  
    $db_opcao = 1;
    $db_botao=true;
    if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){
      $e52_receit = "";
      $e52_valor  = "";
    }
    if(isset($db_opcaoal)){
	$db_opcao=33;
        $db_botao=false;
    }
} 


      



  $result02 = $clpagordemrec->sql_record($clpagordemrec->sql_query_file($e52_codord,null,"count(e52_receit) as tot_receit")); 
   db_fieldsmemory($result02,0);

  if($tot_receit>0){
    $result02 = $clpagordemrec->sql_record($clpagordemrec->sql_query_file($e52_codord,null,"sum(e52_valor) as tot_valor")); 
     db_fieldsmemory($result02,0);

     if(empty($tot_valor) ||  $tot_valor==""){
       $tot_valor='0.00';
       $tot_receit='0';
     }else{
       $tot_valor= number_format($tot_valor,2,".","");
     }
  }else{
    $tot_valor='0.00';
    $tot_receit='0';
  }


?>
<script>
function js_verifica(){
  obj=document.form1;
  valor     =  new Number(obj.e52_valor.value);
  tot_valor =  new Number("<?=$tot_valor?>");
  vlrdis    =  new Number(obj.vlrdis.value);
  
  if(valor  > vlrdis){
    alert('Valor digitado é maior que o saldo disponivel da ordem!');
    return false;
  }
<?
   if($db_opcao==2){
      echo " total =  ($tot_valor-$e52_valor)+valor;\n";
   }else{  
?>  
             total = new Number(tot_valor+valor);
<? } ?>

  if( total > vlrdis){
    alert('Valor digitado, somado com os já incluidos é maior que o saldo disponivel da ordem!');
      return false;
  }


  return true;
}
</script>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Te52_codord?>">
       <?=$Le52_codord?>
    </td>
    <td> 
<?
db_input('e52_codord',6,$Ie52_codord,true,'text',3);
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te52_receit?>">
       <?
if(isset($opcao) && ($opcao=='alterar' || $opcao=="excluir" )){
  $db_op='3';
}else{
  $db_op=$db_opcao;
}
       
       db_ancora(@$Le52_receit,"js_pesquisae52_receit(true);",$db_op);
       ?>
    </td>
    <td> 
<?
db_input('e52_receit',4,$Ie52_receit,true,'text',$db_op," onchange='js_pesquisae52_receit(false);'")
?>
       <?
db_input('k02_descr',40,$Ik02_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te52_valor?>">
       <b>Total da ordem:</b>
    </td>
    <td> 
<?
db_input('vlrdis',15,0,true,'text',3);
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te52_valor?>">
       <?=@$Le52_valor?>
    </td>
    <td> 
<?
db_input('e52_valor',15,$Ie52_valor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td colspan='2' align='center'>
	  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> <?=($db_opcao!=3?" onclick='return js_verifica();'":"")?> >
	  <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
      </td>
  </tr>
  </table>
  <table>
    <tr>
      <td valign="top"  align='center'>  
       <?
	$chavepri= array("e52_codord"=>$e52_codord,"e52_receit"=>@$e52_receit);
	$cliframe_alterar_excluir->chavepri=$chavepri;
	$cliframe_alterar_excluir->sql     = $clpagordemrec->sql_query($e52_codord,null,"e52_codord,e52_receit,e52_valor,k02_descr");
	$cliframe_alterar_excluir->campos  ="e52_receit,e52_valor,k02_descr";
	$cliframe_alterar_excluir->legenda="RECEITAS LANÇADAS";
	$cliframe_alterar_excluir->iframe_height ="140";
	$cliframe_alterar_excluir->iframe_width ="700";
	$cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);    
       ?>
      </td>
    </tr>
    <tr>
      <td><b>Total de receitas:</b>
  <?
  db_input('tot_receit',8,0,true,'text',3);
  ?>
      <b>Total dos valores:</b>
  <?
  db_input('tot_valor',13,0,true,'text',3)
  ?>
      
      </td>
    </tr>
    </table>
  </center>
</form>
<script>
function js_pesquisae52_receit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_pagordemrec','db_iframe_tabrec','func_tabrec.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_descr','Pesquisa',true,0);
  }else{
     if(document.form1.e52_receit.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_pagordemrec','db_iframe_tabrec','func_tabrec.php?pesquisa_chave='+document.form1.e52_receit.value+'&funcao_js=parent.js_mostratabrec','Pesquisa',false,0);
     }else{
       document.form1.k02_descr.value = ''; 
     }
  }
}
function js_mostratabrec(chave,erro){
  document.form1.k02_descr.value = chave; 
  if(erro==true){ 
    document.form1.e52_receit.focus(); 
    document.form1.e52_receit.value = ''; 
  }
}
function js_mostratabrec1(chave1,chave2){
  document.form1.e52_receit.value = chave1;
  document.form1.k02_descr.value = chave2;
  db_iframe_tabrec.hide();
}
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
</script>