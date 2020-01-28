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

//MODULO: fiscal
$clfiscbairro->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y30_data");
$clrotulo->label("j13_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty32_codnoti?>">
       <?
       db_ancora(@$Ly32_codnoti,"js_pesquisay32_codnoti(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y32_codnoti',20,$Iy32_codnoti,true,'text',$db_opcao," onchange='js_pesquisay32_codnoti(false);'")
?>
       <?
db_input('y30_data',10,$Iy30_data,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty32_codbai?>">
       <?
       db_ancora(@$Ly32_codbai,"js_pesquisay32_codbai(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y32_codbai',4,$Iy32_codbai,true,'text',$db_opcao," onchange='js_pesquisay32_codbai(false);'")
?>
       <?
db_input('j13_descr',40,$Ij13_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisay32_codnoti(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_fiscal','func_fiscal.php?funcao_js=parent.js_mostrafiscal1|y30_codnoti|y30_data','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_fiscal','func_fiscal.php?pesquisa_chave='+document.form1.y32_codnoti.value+'&funcao_js=parent.js_mostrafiscal','Pesquisa',false);
  }
}
function js_mostrafiscal(chave,erro){
  document.form1.y30_data.value = chave; 
  if(erro==true){ 
    document.form1.y32_codnoti.focus(); 
    document.form1.y32_codnoti.value = ''; 
  }
}
function js_mostrafiscal1(chave1,chave2){
  document.form1.y32_codnoti.value = chave1;
  document.form1.y30_data.value = chave2;
  db_iframe_fiscal.hide();
}
function js_pesquisay32_codbai(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_bairro','func_bairro.php?funcao_js=parent.js_mostrabairro1|j13_codi|j13_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_bairro','func_bairro.php?pesquisa_chave='+document.form1.y32_codbai.value+'&funcao_js=parent.js_mostrabairro','Pesquisa',false);
  }
}
function js_mostrabairro(chave,erro){
  document.form1.j13_descr.value = chave; 
  if(erro==true){ 
    document.form1.y32_codbai.focus(); 
    document.form1.y32_codbai.value = ''; 
  }
}
function js_mostrabairro1(chave1,chave2){
  document.form1.y32_codbai.value = chave1;
  document.form1.j13_descr.value = chave2;
  db_iframe_bairro.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_fiscbairro','func_fiscbairro.php?funcao_js=parent.js_preenchepesquisa|y32_codnoti','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_fiscbairro.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>
}
</script>