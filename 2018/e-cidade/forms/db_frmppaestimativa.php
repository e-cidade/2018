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

//MODULO: orcamento
$clppaestimativa->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o01_descricao");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To05_sequencial?>">
       <?=@$Lo05_sequencial?>
    </td>
    <td> 
<?
db_input('o05_sequencial',10,$Io05_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To05_ppalei?>">
       <?
       db_ancora(@$Lo05_ppalei,"js_pesquisao05_ppalei(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o05_ppalei',10,$Io05_ppalei,true,'text',$db_opcao," onchange='js_pesquisao05_ppalei(false);'")
?>
       <?
db_input('o01_descricao',100,$Io01_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To05_anoreferencia?>">
       <?=@$Lo05_anoreferencia?>
    </td>
    <td> 
<?
db_input('o05_anoreferencia',4,$Io05_anoreferencia,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To05_base?>">
       <?=@$Lo05_base?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('o05_base',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To05_valor?>">
       <?=@$Lo05_valor?>
    </td>
    <td> 
<?
db_input('o05_valor',10,$Io05_valor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisao05_ppalei(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_ppalei','func_ppalei.php?funcao_js=parent.js_mostrappalei1|o01_sequencial|o01_descricao','Pesquisa',true);
  }else{
     if(document.form1.o05_ppalei.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_ppalei','func_ppalei.php?pesquisa_chave='+document.form1.o05_ppalei.value+'&funcao_js=parent.js_mostrappalei','Pesquisa',false);
     }else{
       document.form1.o01_descricao.value = ''; 
     }
  }
}
function js_mostrappalei(chave,erro){
  document.form1.o01_descricao.value = chave; 
  if(erro==true){ 
    document.form1.o05_ppalei.focus(); 
    document.form1.o05_ppalei.value = ''; 
  }
}
function js_mostrappalei1(chave1,chave2){
  document.form1.o05_ppalei.value = chave1;
  document.form1.o01_descricao.value = chave2;
  db_iframe_ppalei.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_ppaestimativa','func_ppaestimativa.php?funcao_js=parent.js_preenchepesquisa|o05_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_ppaestimativa.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>