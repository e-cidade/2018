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
$clarrehist->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k01_descr");
$clrotulo->label("nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk00_idhist?>">
       <?=@$Lk00_idhist?>
    </td>
    <td> 
<?
db_input('k00_idhist',10,$Ik00_idhist,true,'text',3,"")
?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tk00_numpre?>">
       <?=@$Lk00_numpre?>
    </td>
    <td> 
<?
db_input('k00_numpre',10,$Ik00_numpre,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_numpar?>">
       <?=@$Lk00_numpar?>
    </td>
    <td> 
<?
db_input('k00_numpar',10,$Ik00_numpar,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_hist?>">
       <?
       db_ancora(@$Lk00_hist,"js_pesquisak00_hist(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k00_hist',10,$Ik00_hist,true,'text',$db_opcao," onchange='js_pesquisak00_hist(false);'")
?>
       <?
db_input('k01_descr',40,$Ik01_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_id_usuario?>">
       <?
       db_ancora(@$Lk00_id_usuario,"js_pesquisak00_id_usuario(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k00_id_usuario',10,$Ik00_id_usuario,true,'text',$db_opcao," onchange='js_pesquisak00_id_usuario(false);'")
?>
       <?
db_input('nome',40,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_histtxt?>">
       <?=@$Lk00_histtxt?>
    </td>
    <td> 
<?
db_textarea('k00_histtxt',10,52,$Ik00_histtxt,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_limithist?>">
       <?=@$Lk00_limithist?>
    </td>
    <td> 
<?
db_inputdata('k00_limithist',@$k00_limithist_dia,@$k00_limithist_mes,@$k00_limithist_ano,true,'text',$db_opcao,"")
?>
  </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisak00_hist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_histcalc','func_histcalc.php?funcao_js=parent.js_mostrahistcalc1|k01_codigo|k01_descr','Pesquisa',true);
  }else{
     if(document.form1.k00_hist.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_histcalc','func_histcalc.php?pesquisa_chave='+document.form1.k00_hist.value+'&funcao_js=parent.js_mostrahistcalc','Pesquisa',false);
     }else{
       document.form1.k01_descr.value = ''; 
     }
  }
}
function js_mostrahistcalc(chave,erro){
  document.form1.k01_descr.value = chave; 
  if(erro==true){ 
    document.form1.k00_hist.focus(); 
    document.form1.k00_hist.value = ''; 
  }
}
function js_mostrahistcalc1(chave1,chave2){
  document.form1.k00_hist.value = chave1;
  document.form1.k01_descr.value = chave2;
  db_iframe_histcalc.hide();
}
function js_pesquisak00_id_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.k00_id_usuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.k00_id_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.k00_id_usuario.focus(); 
    document.form1.k00_id_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.k00_id_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_arrehist','func_arrehist.php?funcao_js=parent.js_preenchepesquisa|k00_idhist','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_arrehist.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>