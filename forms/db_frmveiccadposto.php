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

//MODULO: veiculos
$clveiccadposto->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("descrdepto");
$clrotulo->label("ve35_depart");
$clrotulo->label("ve34_numcgm");
$clrotulo->label("z01_nome");
if(!isset($ve29_tipo)){
	$ve29_tipo=1;
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tve29_codigo?>">
       <?=@$Lve29_codigo?>
    </td>
    <td> 
<?
db_input('ve29_codigo',10,$Ive29_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve29_tipo?>">
       <?=@$Lve29_tipo?>
    </td>
    <td> 
<?
$x = array('1'=>'Interno','2'=>'Externo');
db_select('ve29_tipo',$x,true,$db_opcao,"onchange='document.form1.submit()'");
?>
    </td>
  </tr>
<?
if(isset($ve29_tipo)&&$ve29_tipo==1){
?>
<tr>
    <td nowrap title="<?=@$Tve35_depart?>">
       <?
       db_ancora(@$Lve35_depart,"js_pesquisave35_depart(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ve35_depart',5,$Ive35_depart,true,'text',$db_opcao," onchange='js_pesquisave35_depart(false);'")
?>
       <?
db_input('descrdepto',40,$Idescrdepto,true,'text',3,'')
       ?>
    </td>
  </tr>  
<?
}else if(isset($ve29_tipo)&&$ve29_tipo==2){
?>
  <tr>
    <td nowrap title="<?=@$Tve34_numcgm?>">
       <?
       db_ancora(@$Lve34_numcgm,"js_pesquisave34_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ve34_numcgm',10,$Ive34_numcgm,true,'text',$db_opcao," onchange='js_pesquisave34_numcgm(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  
<?
}
?>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisave35_depart(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_mostradb_depart1|coddepto|descrdepto','Pesquisa',true);
  }else{
     if(document.form1.ve35_depart.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_depart','func_db_depart.php?pesquisa_chave='+document.form1.ve35_depart.value+'&funcao_js=parent.js_mostradb_depart','Pesquisa',false);
     }else{
       document.form1.descrdepto.value = ''; 
     }
  }
}
function js_mostradb_depart(chave,erro){
  document.form1.descrdepto.value = chave; 
  if(erro==true){ 
    document.form1.ve35_depart.focus(); 
    document.form1.ve35_depart.value = ''; 
  }
}
function js_mostradb_depart1(chave1,chave2){
  document.form1.ve35_depart.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_db_depart.hide();
}
function js_pesquisave34_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.ve34_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.ve34_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.ve34_numcgm.focus(); 
    document.form1.ve34_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.ve34_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_veiccadposto','func_veiccadposto.php?funcao_js=parent.js_preenchepesquisa|ve29_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_veiccadposto.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>