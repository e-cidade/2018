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
$clprocnivel->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd09_c_descr");
$clrotulo->label("sd21_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tsd28_i_seq?>">
       <?=@$Lsd28_i_seq?>
    </td>
    <td> 
<?
db_input('sd28_i_seq',5,$Isd28_i_seq,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd28_i_procedimento?>">
       <?
       db_ancora(@$Lsd28_i_procedimento,"js_pesquisasd28_i_procedimento(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd28_i_procedimento',5,$Isd28_i_procedimento,true,'text',$db_opcao," onchange='js_pesquisasd28_i_procedimento(false);'")
?>
       <?
db_input('sd09_c_descr',100,$Isd09_c_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd28_i_nivel?>">
       <?
       db_ancora(@$Lsd28_i_nivel,"js_pesquisasd28_i_nivel(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd28_i_nivel',5,$Isd28_i_nivel,true,'text',$db_opcao," onchange='js_pesquisasd28_i_nivel(false);'")
?>
       <?
db_input('sd21_i_codigo',10,$Isd21_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisasd28_i_procedimento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_procedimentos','func_procedimentos.php?funcao_js=parent.js_mostraprocedimentos1|sd09_i_codigo|sd09_c_descr','Pesquisa',true);
  }else{
     if(document.form1.sd28_i_procedimento.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_procedimentos','func_procedimentos.php?pesquisa_chave='+document.form1.sd28_i_procedimento.value+'&funcao_js=parent.js_mostraprocedimentos','Pesquisa',false);
     }else{
       document.form1.sd09_c_descr.value = ''; 
     }
  }
}
function js_mostraprocedimentos(chave,erro){
  document.form1.sd09_c_descr.value = chave; 
  if(erro==true){ 
    document.form1.sd28_i_procedimento.focus(); 
    document.form1.sd28_i_procedimento.value = ''; 
  }
}
function js_mostraprocedimentos1(chave1,chave2){
  document.form1.sd28_i_procedimento.value = chave1;
  document.form1.sd09_c_descr.value = chave2;
  db_iframe_procedimentos.hide();
}
function js_pesquisasd28_i_nivel(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_nivelhierar','func_nivelhierar.php?funcao_js=parent.js_mostranivelhierar1|sd21_i_codigo|sd21_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.sd28_i_nivel.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_nivelhierar','func_nivelhierar.php?pesquisa_chave='+document.form1.sd28_i_nivel.value+'&funcao_js=parent.js_mostranivelhierar','Pesquisa',false);
     }else{
       document.form1.sd21_i_codigo.value = ''; 
     }
  }
}
function js_mostranivelhierar(chave,erro){
  document.form1.sd21_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.sd28_i_nivel.focus(); 
    document.form1.sd28_i_nivel.value = ''; 
  }
}
function js_mostranivelhierar1(chave1,chave2){
  document.form1.sd28_i_nivel.value = chave1;
  document.form1.sd21_i_codigo.value = chave2;
  db_iframe_nivelhierar.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_procnivel','func_procnivel.php?funcao_js=parent.js_preenchepesquisa|sd28_i_seq','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_procnivel.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>