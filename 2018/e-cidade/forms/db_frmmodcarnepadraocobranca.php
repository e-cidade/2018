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
$clmodcarnepadraocobranca->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k48_cadmodcarne");
$clrotulo->label("k15_numcgm");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk22_sequencial?>">
       <?=@$Lk22_sequencial?>
    </td>
    <td> 
<?
db_input('k22_sequencial',10,$Ik22_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk22_modcarnepadrao?>">
       <?
       db_ancora(@$Lk22_modcarnepadrao,"js_pesquisak22_modcarnepadrao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k22_modcarnepadrao',10,$Ik22_modcarnepadrao,true,'text',$db_opcao," onchange='js_pesquisak22_modcarnepadrao(false);'")
?>
       <?
db_input('k48_cadmodcarne',10,$Ik48_cadmodcarne,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk22_cadban?>">
       <?
       db_ancora(@$Lk22_cadban,"js_pesquisak22_cadban(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k22_cadban',6,$Ik22_cadban,true,'text',$db_opcao," onchange='js_pesquisak22_cadban(false);'")
?>
       <?
db_input('k15_numcgm',10,$Ik15_numcgm,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk22_datafim?>">
       <?=@$Lk22_datafim?>
    </td>
    <td> 
<?
db_inputdata('k22_datafim',@$k22_datafim_dia,@$k22_datafim_mes,@$k22_datafim_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk22_dataini?>">
       <?=@$Lk22_dataini?>
    </td>
    <td> 
<?
db_inputdata('k22_dataini',@$k22_dataini_dia,@$k22_dataini_mes,@$k22_dataini_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisak22_modcarnepadrao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_modcarnepadrao','func_modcarnepadrao.php?funcao_js=parent.js_mostramodcarnepadrao1|k48_sequencial|k48_cadmodcarne','Pesquisa',true);
  }else{
     if(document.form1.k22_modcarnepadrao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_modcarnepadrao','func_modcarnepadrao.php?pesquisa_chave='+document.form1.k22_modcarnepadrao.value+'&funcao_js=parent.js_mostramodcarnepadrao','Pesquisa',false);
     }else{
       document.form1.k48_cadmodcarne.value = ''; 
     }
  }
}
function js_mostramodcarnepadrao(chave,erro){
  document.form1.k48_cadmodcarne.value = chave; 
  if(erro==true){ 
    document.form1.k22_modcarnepadrao.focus(); 
    document.form1.k22_modcarnepadrao.value = ''; 
  }
}
function js_mostramodcarnepadrao1(chave1,chave2){
  document.form1.k22_modcarnepadrao.value = chave1;
  document.form1.k48_cadmodcarne.value = chave2;
  db_iframe_modcarnepadrao.hide();
}
function js_pesquisak22_cadban(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cadban','func_cadban.php?funcao_js=parent.js_mostracadban1|k15_codigo|k15_numcgm','Pesquisa',true);
  }else{
     if(document.form1.k22_cadban.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cadban','func_cadban.php?pesquisa_chave='+document.form1.k22_cadban.value+'&funcao_js=parent.js_mostracadban','Pesquisa',false);
     }else{
       document.form1.k15_numcgm.value = ''; 
     }
  }
}
function js_mostracadban(chave,erro){
  document.form1.k15_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.k22_cadban.focus(); 
    document.form1.k22_cadban.value = ''; 
  }
}
function js_mostracadban1(chave1,chave2){
  document.form1.k22_cadban.value = chave1;
  document.form1.k15_numcgm.value = chave2;
  db_iframe_cadban.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_modcarnepadraocobranca','func_modcarnepadraocobranca.php?funcao_js=parent.js_preenchepesquisa|k22_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_modcarnepadraocobranca.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>