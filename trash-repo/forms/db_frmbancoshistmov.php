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
$clbancoshistmov->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k67_descricao");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk66_sequencial?>">
       <?=@$Lk66_sequencial?>
    </td>
    <td> 
<?
db_input('k66_sequencial',10,$Ik66_sequencial,true,'text',3,"")
?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tk66_codbco?>">
		   <?
       db_ancora(@$Lk66_codbco,"js_pesquisak66_codbco(true);",$db_opcao);
			 ?>
    </td>
    <td> 
    <?
      db_input('k66_codbco',10,$Ik66_codbco,true,'text',$db_opcao," onchange='js_pesquisak66_codbco(false);'")
    ?>
    <?
      db_input('nomebco',50,'',true,'text',3,"")
    ?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tk66_bancoshistmovcategoria?>">
       <?
       db_ancora(@$Lk66_bancoshistmovcategoria,"js_pesquisak66_bancoshistmovcategoria(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k66_bancoshistmovcategoria',10,$Ik66_bancoshistmovcategoria,true,'text',$db_opcao," onchange='js_pesquisak66_bancoshistmovcategoria(false);'")
?>
       <?
db_input('k67_descricao',50,$Ik67_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk66_historico?>">
       <?=@$Lk66_historico?>
    </td>
    <td> 
<?
db_input('k66_historico',10,$Ik66_historico,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk66_descricao?>">
       <?=@$Lk66_descricao?>
    </td>
    <td> 
<?
db_input('k66_descricao',50,$Ik66_descricao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk66_vigencia?>">
       <?=@$Lk66_vigencia?>
    </td>
    <td> 
<?
db_inputdata('k66_vigencia',@$k66_vigencia_dia,@$k66_vigencia_mes,@$k66_vigencia_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk66_sigla?>">
       <?=@$Lk66_sigla?>
    </td>
    <td> 
<?
db_input('k66_sigla',3,$Ik66_sigla,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>

function js_pesquisak66_codbco(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_codbco','func_bancos.php?funcao_js=parent.js_mostrabancos1|codbco|nomebco','Pesquisa',true);
  }else{
     if(document.form1.k66_codbco.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_codbco','func_bancos.php?pesquisa_chave='+document.form1.k66_codbco.value+'&funcao_js=parent.js_mostrabancos','Pesquisa',false);
     }else{
       document.form1.k66_codbco.value = ''; 
     }
  }
}

function js_mostrabancos(chave,erro){
  document.form1.k66_codbco.value = chave; 
  if(erro == true){ 
    document.form1.k66_codbco.focus(); 
    document.form1.k66_codbco.value = ''; 
  }
}
function js_mostrabancos1(chave1,chave2){
//	alert(chave1+' -- '+chave2);
  document.form1.k66_codbco.value = chave1;
  document.form1.nomebco.value    = chave2;
  db_iframe_codbco.hide();
}





function js_pesquisak66_bancoshistmovcategoria(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_bancoshistmovcategoria','func_bancoshistmovcategoria.php?funcao_js=parent.js_mostrabancoshistmovcategoria1|k67_sequencial|k67_descricao','Pesquisa',true);
  }else{
     if(document.form1.k66_bancoshistmovcategoria.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_bancoshistmovcategoria','func_bancoshistmovcategoria.php?pesquisa_chave='+document.form1.k66_bancoshistmovcategoria.value+'&funcao_js=parent.js_mostrabancoshistmovcategoria','Pesquisa',false);
     }else{
       document.form1.k67_descricao.value = ''; 
     }
  }
}
function js_mostrabancoshistmovcategoria(chave,erro){
  document.form1.k67_descricao.value = chave; 
  if(erro==true){ 
    document.form1.k66_bancoshistmovcategoria.focus(); 
    document.form1.k66_bancoshistmovcategoria.value = ''; 
  }
}
function js_mostrabancoshistmovcategoria1(chave1,chave2){
  document.form1.k66_bancoshistmovcategoria.value = chave1;
  document.form1.k67_descricao.value = chave2;
  db_iframe_bancoshistmovcategoria.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_bancoshistmov','func_bancoshistmov.php?funcao_js=parent.js_preenchepesquisa|k66_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_bancoshistmov.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>