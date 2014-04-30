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
$cltipovistorias->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("descrdepto");
$clrotulo->label("y41_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty77_codtipo?>">
       <?=@$Ly77_codtipo?>
    </td>
    <td> 
<?
db_input('y77_codtipo',10,$Iy77_codtipo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty77_dias?>">
       <?=@$Ly77_dias?>
    </td>
    <td> 
<?
db_input('y77_dias',10,$Iy77_dias,true,'text',$db_opcao,"")
?>
    </td>
  </tr>

<tr>
<td nowrap title="<?=@$Ty77_diasgeral?>">
<?=@$Ly77_diasgeral?>
</td>
<td>
<?
db_input('y77_diasgeral',10,$Iy77_diasgeral,true,'text',$db_opcao,"")
?>
</td>
</tr>
<tr>
<td nowrap title="<?=@$Ty77_mesgeral?>">
<?=@$Ly77_mesgeral?>
</td>
<td>
<?
db_input('y77_mesgeral',10,$Iy77_mesgeral,true,'text',$db_opcao,"")
?>
</td>
</tr>


  
  <tr>
    <td nowrap title="<?=@$Ty77_descricao?>">
       <?=@$Ly77_descricao?>
    </td>
    <td> 
<?
db_input('y77_descricao',50,$Iy77_descricao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty77_obs?>">
       <?=@$Ly77_obs?>
    </td>
    <td> 
<?
db_textarea('y77_obs',3,50,$Iy77_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty77_coddepto?>">
       <?
       db_ancora(@$Ly77_coddepto,"js_pesquisay77_coddepto(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('y77_coddepto',10,$Iy77_coddepto,true,'text',3," onchange='js_pesquisay77_coddepto(false);'");
if($db_opcao == 1){
  echo "<script>document.form1.y77_coddepto.value='".db_getsession('DB_coddepto')."';</script>";
  echo "<script>js_OpenJanelaIframe('','db_iframe_db_depart','func_db_depart.php?pesquisa_chave=".db_getsession('DB_coddepto')."&funcao_js=parent.js_mostradb_depart','Pesquisa',false);</script>";
}
?>
       <?
db_input('descrdepto',40,$Idescrdepto,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty77_tipoandam?>">
       <?
       db_ancora(@$Ly77_tipoandam,"js_pesquisay77_tipoandam(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y77_tipoandam',10,$Iy77_tipoandam,true,'text',$db_opcao," onchange='js_pesquisay77_tipoandam(false);'")
?>
       <?
db_input('y41_descr',50,$Iy41_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisay77_coddepto(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_mostradb_depart1|coddepto|descrdepto','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_db_depart','func_db_depart.php?pesquisa_chave='+document.form1.y77_coddepto.value+'&funcao_js=parent.js_mostradb_depart','Pesquisa',false);
  }
}
function js_mostradb_depart(chave,erro){
  document.form1.descrdepto.value = chave; 
  if(erro==true){ 
    document.form1.y77_coddepto.focus(); 
    document.form1.y77_coddepto.value = ''; 
  }
}
function js_mostradb_depart1(chave1,chave2){
  document.form1.y77_coddepto.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_db_depart.hide();
}
function js_pesquisay77_tipoandam(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tipoandam','func_tipoandam.php?funcao_js=parent.js_mostratipoandam1|y41_codtipo|y41_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_tipoandam','func_tipoandam.php?pesquisa_chave='+document.form1.y77_tipoandam.value+'&funcao_js=parent.js_mostratipoandam','Pesquisa',false);
  }
}
function js_mostratipoandam(chave,erro){
  document.form1.y41_descr.value = chave; 
  if(erro==true){ 
    document.form1.y77_tipoandam.focus(); 
    document.form1.y77_tipoandam.value = ''; 
  }
}
function js_mostratipoandam1(chave1,chave2){
  document.form1.y77_tipoandam.value = chave1;
  document.form1.y41_descr.value = chave2;
  db_iframe_tipoandam.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_tipovistorias','func_tipovistorias.php?funcao_js=parent.js_preenchepesquisa|y77_codtipo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_tipovistorias.hide();
  <?
  if($db_opcao == 2 || $db_opcao == 22){
    echo " location.href = 'fis1_tipovistorias002.php?abas=1&chavepesquisa='+chave;";
  }elseif($db_opcao == 33 || $db_opcao == 3){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>
}
</script>