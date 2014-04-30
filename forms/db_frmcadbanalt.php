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

//MODULO: caixa
$clcadban->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk15_codigo?>">
       <?=@$Lk15_codigo?>
    </td>
    <td> 
<?
db_input('k15_codigo',6,$Ik15_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_numcgm?>">
       <?
       db_ancora(@$Lk15_numcgm,"js_pesquisak15_numcgm(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('k15_numcgm',10,$Ik15_numcgm,true,'text',3," onchange='js_pesquisak15_numcgm(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_codbco?>">
       <?=@$Lk15_codbco?>
    </td>
    <td> 
<?
db_input('k15_codbco',4,$Ik15_codbco,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_conv1?>">
       <?=@$Lk15_conv1?>
    </td>
    <td> 
<?
db_input('k15_conv1',7,$Ik15_conv1,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_conv2?>">
       <?=@$Lk15_conv2?>
    </td>
    <td> 
<?
db_input('k15_conv2',7,$Ik15_conv2,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_conv3?>">
       <?=@$Lk15_conv3?>
    </td>
    <td> 
<?
db_input('k15_conv3',7,$Ik15_conv3,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_conv4?>">
       <?=@$Lk15_conv4?>
    </td>
    <td> 
<?
db_input('k15_conv4',7,$Ik15_conv4,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_conv5?>">
       <?=@$Lk15_conv5?>
    </td>
    <td> 
<?
db_input('k15_conv5',7,$Ik15_conv5,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_seq1?>">
       <?=@$Lk15_seq1?>
    </td>
    <td> 
<?
db_input('k15_seq1',4,$Ik15_seq1,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_seq2?>">
       <?=@$Lk15_seq2?>
    </td>
    <td> 
<?
db_input('k15_seq2',4,$Ik15_seq2,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_seq3?>">
       <?=@$Lk15_seq3?>
    </td>
    <td> 
<?
db_input('k15_seq3',4,$Ik15_seq3,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_seq4?>">
       <?=@$Lk15_seq4?>
    </td>
    <td> 
<?
db_input('k15_seq4',4,$Ik15_seq4,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_seq5?>">
       <?=@$Lk15_seq5?>
    </td>
    <td> 
<?
db_input('k15_seq5',4,$Ik15_seq5,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_ceden1?>">
       <?=@$Lk15_ceden1?>
    </td>
    <td> 
<?
db_input('k15_ceden1',6,$Ik15_ceden1,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_ceden2?>">
       <?=@$Lk15_ceden2?>
    </td>
    <td> 
<?
db_input('k15_ceden2',6,$Ik15_ceden2,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_ceden3?>">
       <?=@$Lk15_ceden3?>
    </td>
    <td> 
<?
db_input('k15_ceden3',6,$Ik15_ceden3,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_ceden4?>">
       <?=@$Lk15_ceden4?>
    </td>
    <td> 
<?
db_input('k15_ceden4',6,$Ik15_ceden4,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_ceden5?>">
       <?=@$Lk15_ceden5?>
    </td>
    <td> 
<?
db_input('k15_ceden5',6,$Ik15_ceden5,true,'text',$db_opcao,"")
?>
    </td>
  </tr>	
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisak15_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.k15_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.k15_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.k15_numcgm.focus(); 
    document.form1.k15_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.k15_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_cadban','func_cadban.php?funcao_js=parent.js_preenchepesquisa|k15_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_cadban.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>