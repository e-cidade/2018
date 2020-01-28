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
$clprocedimentos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd11_c_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tsd09_i_codigo?>">
       <?=@$Lsd09_i_codigo?>
    </td>
    <td> 
<?
db_input('sd09_i_codigo',10,$Isd09_i_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd09_c_grupoproc?>">
       <?
       db_ancora(@$Lsd09_c_grupoproc,"js_pesquisasd09_c_grupoproc(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd09_c_grupoproc',10,$Isd09_c_grupoproc,true,'text',$db_opcao," onchange='js_pesquisasd09_c_grupoproc(false);'")
?>
       <?
db_input('sd11_c_descr',60,$Isd11_c_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd09_c_descr?>">
       <?=@$Lsd09_c_descr?>
    </td>
    <td> 
<?
db_input('sd09_c_descr',80,$Isd09_c_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd09_f_valor?>">
       <?=@$Lsd09_f_valor?>
    </td>
    <td> 
<?
db_input('sd09_f_valor',6,$Isd09_f_valor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd09_b_pab?>">
       <?=@$Lsd09_b_pab?>
    </td>
    <td> 
<?
$Isd09_b_pab = array('f'=>'Não','t'=>'Sim');
db_select('sd09_b_pab',$Isd09_b_pab,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd09_c_comp?>">
       <?=@$Lsd09_c_comp?>
    </td>
    <td> 
<?
db_input('sd09_c_comp',80,$Isd09_c_comp,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd09_d_validade?>">
       <?=@$Lsd09_d_validade?>
    </td>
    <td> 
<?
db_inputdata('sd09_d_validade',@$sd09_d_validade_dia,@$sd09_d_validade_mes,@$sd09_d_validade_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisasd09_c_grupoproc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_grupoproc','func_grupoproc.php?funcao_js=parent.js_mostragrupoproc1|sd11_c_codigo|sd11_c_descr','Pesquisa',true);
  }else{
     if(document.form1.sd09_c_grupoproc.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_grupoproc','func_grupoproc.php?pesquisa_chave='+document.form1.sd09_c_grupoproc.value+'&funcao_js=parent.js_mostragrupoproc','Pesquisa',false);
     }else{
       document.form1.sd11_c_descr.value = ''; 
     }
  }
}
function js_mostragrupoproc(chave,erro){
  document.form1.sd11_c_descr.value = chave; 
  if(erro==true){ 
    document.form1.sd09_c_grupoproc.focus(); 
    document.form1.sd09_c_grupoproc.value = ''; 
  }
}
function js_mostragrupoproc1(chave1,chave2){
  document.form1.sd09_c_grupoproc.value = chave1;
  document.form1.sd11_c_descr.value = chave2;
  db_iframe_grupoproc.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_procedimentos','func_procedimentos.php?funcao_js=parent.js_preenchepesquisa|sd09_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_procedimentos.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>