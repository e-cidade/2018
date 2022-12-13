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
$clprocvalores->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd09_c_descr");

if(isset($start))
 {
   //após o preenchimento do procedimento, busca os registros
   $result = $clprocedimentos->sql_record($clprocedimentos->sql_query_file($sd10_i_procedimento));
   if($clprocedimentos->numrows == 0)
    {
      echo "<script>
              alert('$clprocedimentos->erro_msg')
              history.back()
            </script>
           ";
    }
   
   $result = $clprocvalores->sql_record($clprocvalores->sql_query($sd10_i_procedimento));
   @db_fieldsmemory($result,0);
   if($clprocvalores->numrows > 0)
    {
     $db_opcao = 2;
    }
   $db_opcao1 = 22;
 }
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tsd10_i_procedimento?>">
       <?
       db_ancora(@$Lsd10_i_procedimento,"js_pesquisasd10_i_procedimento(true);",$db_opcao1);
       ?>
    </td>
    <td> 
<?
db_input('sd10_i_procedimento',10,$Isd10_i_procedimento,true,'text',$db_opcao1," onchange='js_pesquisasd10_i_procedimento(false);'")
?>
       <?
db_input('sd09_c_descr',50,$Isd09_c_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <?if(!isset($start)){?>
  <tr>
   <td>
    <input type="submit" value="Processar" name="start">
   </td>
  </tr>
 <?}?>
</table>
<?
 if(isset($start))
 {
?>
<table border="1" cellpadding="0" cellspacing="0" width="450">
  <tr>
    <td nowrap title="<?=@$Tsd10_c_sala?>" width="400">
       <?=@$Lsd10_c_sala?>
    </td>
    <td> 
<?
db_input('sd10_c_sala',10,$Isd10_c_sala,true,'text',$db_opcao,"onBlur=\"FormataValor(this)\"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd10_f_valor?>">
       <?=@$Lsd10_f_valor?>
    </td>
    <td> 
<?
db_input('sd10_f_valor',10,$Isd10_f_valor,true,'text',$db_opcao,"onBlur=\"FormataValor(this)\"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd10_f_servico?>">
       <?=@$Lsd10_f_servico?>
    </td>
    <td> 
<?
db_input('sd10_f_servico',10,$Isd10_f_servico,true,'text',$db_opcao,"onBlur=\"FormataValor(this)\"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd10_f_anestesia?>">
       <?=@$Lsd10_f_anestesia?>
    </td>
    <td> 
<?
db_input('sd10_f_anestesia',10,$Isd10_f_anestesia,true,'text',$db_opcao,"onBlur=\"FormataValor(this)\"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd10_f_material?>">
       <?=@$Lsd10_f_material?>
    </td>
    <td> 
<?
db_input('sd10_f_material',10,$Isd10_f_material,true,'text',$db_opcao,"onBlur=\"FormataValor(this)\"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd10_f_contraste?>">
       <?=@$Lsd10_f_contraste?>
    </td>
    <td> 
<?
db_input('sd10_f_contraste',10,$Isd10_f_contraste,true,'text',$db_opcao,"onBlur=\"FormataValor(this)\"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd10_f_filme?>">
       <?=@$Lsd10_f_filme?>
    </td>
    <td> 
<?
db_input('sd10_f_filme',10,$Isd10_f_filme,true,'text',$db_opcao,"onBlur=\"FormataValor(this)\"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd10_f_gesso?>">
       <?=@$Lsd10_f_gesso?>
    </td>
    <td> 
<?
db_input('sd10_f_gesso',10,$Isd10_f_gesso,true,'text',$db_opcao,"onBlur=\"FormataValor(this)\"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd10_f_quimio?>">
       <?=@$Lsd10_f_quimio?>
    </td>
    <td> 
<?
db_input('sd10_f_quimio',10,$Isd10_f_quimio,true,'text',$db_opcao,"onBlur=\"FormataValor(this)\"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd10_f_dialise?>">
       <?=@$Lsd10_f_dialise?>
    </td>
    <td> 
<?
db_input('sd10_f_dialise',10,$Isd10_f_dialise,true,'text',$db_opcao,"onBlur=\"FormataValor(this)\"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd10_f_sadtrat?>">
       <?=@$Lsd10_f_sadtrat?>
    </td>
    <td> 
<?
db_input('sd10_f_sadtrat',10,$Isd10_f_sadtrat,true,'text',$db_opcao,"onBlur=\"FormataValor(this)\"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd10_f_sadtpc?>">
       <?=@$Lsd10_f_sadtpc?>
    </td>
    <td> 
<?
db_input('sd10_f_sadtpc',10,$Isd10_f_sadtpc,true,'text',$db_opcao,"onBlur=\"FormataValor(this)\"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd10_f_sadtout?>">
       <?=@$Lsd10_f_sadtout?>
    </td>
    <td> 
<?
db_input('sd10_f_sadtout',10,$Isd10_f_sadtout,true,'text',$db_opcao,"onBlur=\"FormataValor(this)\"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd10_f_outro?>">
       <?=@$Lsd10_f_outro?>
    </td>
    <td> 
<?
db_input('sd10_f_outro',10,$Isd10_f_outro,true,'text',$db_opcao,"onBlur=\"FormataValor(this)\"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd10_f_filme2?>">
       <?=@$Lsd10_f_filme2?>
    </td>
    <td> 
<?
db_input('sd10_f_filme2',10,number_format($Isd10_f_filme2,2,'.',''),true,'text',$db_opcao,"onBlur=\"FormataValor(this)\"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd10_f_total?>">
       <?=@$Lsd10_f_total?>
    </td>
    <td> 
<?
db_input('sd10_f_total',10,number_format($Isd10_f_total,2,'.',''),true,'text',3)
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":"alterar")?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":"Alterar")?>" <?=($db_botao==false?"disabled":"")?> >
<?
 if($db_opcao == 2)
  {
   echo "<input type=\"submit\" name=\"excluir\" value=\"Excluir\"> ";
  }
?>
<input type="reset" value="Refazer">
<input type="button" value="Voltar" onclick="history.back()">
</form>
<?
  }
?>
<script>
 function cent(amount)
 {
 //retorna o valor com 2 casas decimais
  return(amount == Math.floor(amount)) ? amount + '.00' : ( (amount*10 == Math.floor(amount*10)) ? amount + '0' : amount);
 }
 function dec(cantidad, decimales)
 {
  //arredonda o valor
  var cantidad = parseFloat(cantidad);
  var decimales = parseFloat(decimales);
  decimales = (!decimales ? 2 : decimales);
  return Math.round(cantidad * Math.pow(10, decimales)) / Math.pow(10, decimales);
 }
 //verifica soma
 function VerificaSoma()
 {
  Soma=0;
  for(i=2;i<17;i++)
  {
   Soma -= document.form1[i].value;
  }
  document.form1.sd10_f_total.value = (cent(dec(Soma,2)))*(-1);
  document.form1.sd10_f_total.value = cent(dec(document.form1.sd10_f_total.value,2));
 }

//
 function FormataValor(Campo)
 {
  //formata o valor e soma
  var vr = Campo.value;
  vr = vr.replace(",", ".");
  if(vr != "")
  {
   Campo.value = cent(dec(vr,2));
  }
  VerificaSoma();
  //Campo.disabled=true;
 }

//
function js_pesquisasd10_i_procedimento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_procedimentos','func_procedimentos.php?funcao_js=parent.js_mostraprocedimentos1|sd09_i_codigo|sd09_c_descr','Pesquisa',true);
  }else{
     if(document.form1.sd10_i_procedimento.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_procedimentos','func_procedimentos.php?pesquisa_chave='+document.form1.sd10_i_procedimento.value+'&funcao_js=parent.js_mostraprocedimentos','Pesquisa',false);
     }else{
       document.form1.sd09_c_descr.value = ''; 
     }
  }
}
function js_mostraprocedimentos(chave,erro){
  document.form1.sd09_c_descr.value = chave; 
  if(erro==true){ 
    document.form1.sd10_i_procedimento.focus(); 
    document.form1.sd10_i_procedimento.value = ''; 
  }
}
function js_mostraprocedimentos1(chave1,chave2){
  document.form1.sd10_i_procedimento.value = chave1;
  document.form1.sd09_c_descr.value = chave2;
  db_iframe_procedimentos.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_procvalores','func_procvalores.php?funcao_js=parent.js_preenchepesquisa|sd10_i_procedimento','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_procvalores.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>