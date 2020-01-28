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

//MODULO: issqn
$clcadcalc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("q92_descr");
$clrotulo->label("q87_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tq85_codigo?>">
       <?=@$Lq85_codigo?>
    </td>
    <td> 
<?
db_input('q85_codigo',10,$Iq85_codigo,true,'text',3)
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq85_descr?>">
       <?=@$Lq85_descr?>
    </td>
    <td> 
<?
db_input('q85_descr',40,$Iq85_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq85_uniref?>">
       <?=@$Lq85_uniref?>
    </td>
    <td> 
<?
db_input('q85_uniref',10,$Iq85_uniref,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq85_dtoper?>">
       <?=@$Lq85_dtoper?>
    </td>
    <td> 
<?
db_inputdata('q85_dtoper',@$q85_dtoper_dia,@$q85_dtoper_mes,@$q85_dtoper_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq85_codven?>">
       <?
       db_ancora(@$Lq85_codven,"js_pesquisaq85_codven(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q85_codven',10,$Iq85_codven,true,'text',$db_opcao," onchange='js_pesquisaq85_codven(false);'")
?>
       <?
db_input('q92_descr',40,$Iq92_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq85_var?>">
       <?=@$Lq85_var?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('q85_var',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq85_fixmes?>">
       <?=@$Lq85_fixmes?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('q85_fixmes',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq85_forcal?>">
       <?
       db_ancora(@$Lq85_forcal,"js_pesquisaq85_forcal(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q85_forcal',10,$Iq85_forcal,true,'text',$db_opcao," onchange='js_pesquisaq85_forcal(false);'")
?>
       <?
db_input('q87_descr',40,$Iq87_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq85_perman?>">
       <?=@$Lq85_perman?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('q85_perman',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq85_outromun?>">
       <?=@$Lq85_outromun?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('q85_outromun',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaq85_codven(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cadvencdesc','func_cadvencdesc.php?funcao_js=parent.js_mostracadvencdesc1|q92_codigo|q92_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_cadvencdesc','func_cadvencdesc.php?pesquisa_chave='+document.form1.q85_codven.value+'&funcao_js=parent.js_mostracadvencdesc','Pesquisa',false);
  }
}
function js_mostracadvencdesc(chave,erro){
  document.form1.q92_descr.value = chave; 
  if(erro==true){ 
    document.form1.q85_codven.focus(); 
    document.form1.q85_codven.value = ''; 
  }
}
function js_mostracadvencdesc1(chave1,chave2){
  document.form1.q85_codven.value = chave1;
  document.form1.q92_descr.value = chave2;
  db_iframe_cadvencdesc.hide();
}
function js_pesquisaq85_forcal(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_forcaldesc','func_forcaldesc.php?funcao_js=parent.js_mostraforcaldesc1|q87_codigo|q87_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_forcaldesc','func_forcaldesc.php?pesquisa_chave='+document.form1.q85_forcal.value+'&funcao_js=parent.js_mostraforcaldesc','Pesquisa',false);
  }
}
function js_mostraforcaldesc(chave,erro){
  document.form1.q87_descr.value = chave; 
  if(erro==true){ 
    document.form1.q85_forcal.focus(); 
    document.form1.q85_forcal.value = ''; 
  }
}
function js_mostraforcaldesc1(chave1,chave2){
  document.form1.q85_forcal.value = chave1;
  document.form1.q87_descr.value = chave2;
  db_iframe_forcaldesc.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_cadcalc','func_cadcalc.php?funcao_js=parent.js_preenchepesquisa|q85_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_cadcalc.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>
}
</script>