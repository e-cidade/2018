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
$clcadvencdescban->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("q92_descr");
$clrotulo->label("k15_numcgm");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tq93_codigo?>">
       <?
       db_ancora(@$Lq93_codigo,"js_pesquisaq93_codigo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q93_codigo',4,$Iq93_codigo,true,'text',$db_opcao," onchange='js_pesquisaq93_codigo(false);'")
?>
       <?
db_input('q92_descr',40,$Iq92_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq93_cadban?>">
       <?
       db_ancora(@$Lq93_cadban,"js_pesquisaq93_cadban(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q93_cadban',6,$Iq93_cadban,true,'text',$db_opcao," onchange='js_pesquisaq93_cadban(false);'")
?>
       <?
db_input('k15_numcgm',6,$Ik15_numcgm,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaq93_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cadvencdesc','func_cadvencdesc.php?funcao_js=parent.js_mostracadvencdesc1|q92_codigo|q92_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_cadvencdesc','func_cadvencdesc.php?pesquisa_chave='+document.form1.q93_codigo.value+'&funcao_js=parent.js_mostracadvencdesc','Pesquisa',false);
  }
}
function js_mostracadvencdesc(chave,erro){
  document.form1.q92_descr.value = chave; 
  if(erro==true){ 
    document.form1.q93_codigo.focus(); 
    document.form1.q93_codigo.value = ''; 
  }
}
function js_mostracadvencdesc1(chave1,chave2){
  document.form1.q93_codigo.value = chave1;
  document.form1.q92_descr.value = chave2;
  db_iframe_cadvencdesc.hide();
}
function js_pesquisaq93_cadban(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cadban','func_cadban.php?funcao_js=parent.js_mostracadban1|k15_codigo|k15_numcgm','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_cadban','func_cadban.php?pesquisa_chave='+document.form1.q93_cadban.value+'&funcao_js=parent.js_mostracadban','Pesquisa',false);
  }
}
function js_mostracadban(chave,erro){
  document.form1.k15_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.q93_cadban.focus(); 
    document.form1.q93_cadban.value = ''; 
  }
}
function js_mostracadban1(chave1,chave2){
  document.form1.q93_cadban.value = chave1;
  document.form1.k15_numcgm.value = chave2;
  db_iframe_cadban.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_cadvencdescban','func_cadvencdescban.php?funcao_js=parent.js_preenchepesquisa|q93_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_cadvencdescban.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>
}
</script>