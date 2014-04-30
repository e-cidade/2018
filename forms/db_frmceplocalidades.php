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

//MODULO: protocolo
$clceplocalidades->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("cp03_sigla");
$clrotulo->label("cp03_estado");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tcp05_codlocalidades?>">
       <?=@$Lcp05_codlocalidades?>
    </td>
    <td> 
<?
db_input('cp05_codlocalidades',10,$Icp05_codlocalidades,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcp05_sigla?>">
       <?
       db_ancora(@$Lcp05_sigla,"js_pesquisacp05_sigla(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('cp05_sigla',2,$Icp05_sigla,true,'text',$db_opcao," onchange='js_pesquisacp05_sigla(false);'")
?>
       <?
db_input('cp03_estado',70,$Icp03_estado,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcp05_localidades?>">
       <?=@$Lcp05_localidades?>
    </td>
    <td> 
<?
db_input('cp05_localidades',72,$Icp05_localidades,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcp05_cepinicial?>">
       <?=@$Lcp05_cepinicial?>
    </td>
    <td> 
<?
db_input('cp05_cepinicial',8,$Icp05_cepinicial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcp05_cepfinal?>">
       <?=@$Lcp05_cepfinal?>
    </td>
    <td> 
<?
db_input('cp05_cepfinal',8,$Icp05_cepfinal,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcp05_tipo?>">
       <?=@$Lcp05_tipo?>
    </td>
    <td> 
<?
db_input('cp05_tipo',1,$Icp05_tipo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcp05_situacao?>">
       <?=@$Lcp05_situacao?>
    </td>
    <td> 
<?
db_input('cp05_situacao',1,$Icp05_situacao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcp05_codsubordinacao?>">
       <?=@$Lcp05_codsubordinacao?>
    </td>
    <td> 
<?
db_input('cp05_codsubordinacao',10,$Icp05_codsubordinacao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisacp05_sigla(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cepestados','func_cepestados.php?funcao_js=parent.js_mostracepestados1|cp03_sigla|cp03_estado','Pesquisa',true);
  }else{
     if(document.form1.cp05_sigla.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cepestados','func_cepestados.php?pesquisa_chave='+document.form1.cp05_sigla.value+'&funcao_js=parent.js_mostracepestados','Pesquisa',false);
     }else{
       document.form1.cp03_estado.value = ''; 
     }
  }
}
function js_mostracepestados(chave,erro){
  document.form1.cp03_estado.value = chave; 
  if(erro==true){ 
    document.form1.cp05_sigla.focus(); 
    document.form1.cp05_sigla.value = ''; 
  }
}
function js_mostracepestados1(chave1,chave2){
  document.form1.cp05_sigla.value = chave1;
  document.form1.cp03_estado.value = chave2;
  db_iframe_cepestados.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_ceplocalidades','func_ceplocalidades.php?funcao_js=parent.js_preenchepesquisa|cp05_codlocalidades','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_ceplocalidades.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>