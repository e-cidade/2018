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

//MODULO: tributario
$clmeievento->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("q100_descricao");
?>
<form name="form1" method="post" action="">
<center>

<table align=center style="margin-top: 25px;">
<tr><td>

<fieldset>
        <legend><strong>Eventos</strong></legend>

<table border="0">
  <tr>
    <td nowrap title="<?=@$Tq101_sequencial?>">
       <?=@$Lq101_sequencial?>
    </td>
    <td> 
<?
db_input('q101_sequencial',10,$Iq101_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq101_codigo?>">
       <?=@$Lq101_codigo?>
    </td>
    <td> 
<?
db_input('q101_codigo',10,$Iq101_codigo,true,'text',1,"")
?>
    </td>
  </tr>  
  <tr>
    <td nowrap title="<?=@$Tq101_meigrupoevento?>">
       <?
       db_ancora(@$Lq101_meigrupoevento,"js_pesquisaq101_meigrupoevento(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q101_meigrupoevento',10,$Iq101_meigrupoevento,true,'text',$db_opcao," onchange='js_pesquisaq101_meigrupoevento(false);'")
?>
       <?
db_input('q100_descricao',40,$Iq100_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq101_descricao?>">
       <?=@$Lq101_descricao?>
    </td>
    <td> 
<?
db_input('q101_descricao',54,$Iq101_descricao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq101_obs?>">
       <?=@$Lq101_obs?>
    </td>
    <td> 
<?
db_textarea('q101_obs',5,52,$Iq101_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq101_versao?>">
       <?=@$Lq101_versao?>
    </td>
    <td> 
<?
db_input('q101_versao',20,$Iq101_versao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq101_dataini?>">
       <?=@$Lq101_dataini?>
    </td>
    <td> 
<?
db_inputdata('q101_dataini',@$q101_dataini_dia,@$q101_dataini_mes,@$q101_dataini_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq101_datafin?>">
       <?=@$Lq101_datafin?>
    </td>
    <td> 
<?
db_inputdata('q101_datafin',@$q101_datafin_dia,@$q101_datafin_mes,@$q101_datafin_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  
</fieldset>
  
</td></tr>
</table>
  
  
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >

<? if($db_opcao > 1) { ?>
  	<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<? } ?>


</form>
<script>
function js_pesquisaq101_meigrupoevento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_meigrupoevento','func_meigrupoevento.php?funcao_js=parent.js_mostrameigrupoevento1|q100_sequencial|q100_descricao','Pesquisa',true);
  }else{
     if(document.form1.q101_meigrupoevento.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_meigrupoevento','func_meigrupoevento.php?pesquisa_chave='+document.form1.q101_meigrupoevento.value+'&funcao_js=parent.js_mostrameigrupoevento','Pesquisa',false);
     }else{
       document.form1.q100_descricao.value = ''; 
     }
  }
}
function js_mostrameigrupoevento(chave,erro){
  document.form1.q100_descricao.value = chave; 
  if(erro==true){ 
    document.form1.q101_meigrupoevento.focus(); 
    document.form1.q101_meigrupoevento.value = ''; 
  }
}
function js_mostrameigrupoevento1(chave1,chave2){
  document.form1.q101_meigrupoevento.value = chave1;
  document.form1.q100_descricao.value = chave2;
  db_iframe_meigrupoevento.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_meievento','func_meievento.php?funcao_js=parent.js_preenchepesquisa|q101_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_meievento.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>