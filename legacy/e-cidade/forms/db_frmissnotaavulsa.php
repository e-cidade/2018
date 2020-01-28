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
$clissnotaavulsa->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("q02_numcgm");
      if($db_opcao==1){
 	   $db_action="iss1_issnotaavulsa004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="iss1_issnotaavulsa005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="iss1_issnotaavulsa006.php";
      }  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tq51_sequencial?>">
       <?=@$Lq51_sequencial?>
    </td>
    <td> 
<?
db_input('q51_sequencial',10,$Iq51_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq51_numnota?>">
       <?=@$Lq51_numnota?>
    </td>
    <td> 
<?
db_input('q51_numnota',10,$Iq51_numnota,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq51_inscr?>">
       <?
       db_ancora(@$Lq51_inscr,"js_pesquisaq51_inscr(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q51_inscr',10,$Iq51_inscr,true,'text',$db_opcao," onchange='js_pesquisaq51_inscr(false);'")
?>
       <?
db_input('q02_numcgm',10,$Iq02_numcgm,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq51_usuario?>">
       <?
       db_ancora(@$Lq51_usuario,"js_pesquisaq51_usuario(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q51_usuario',10,$Iq51_usuario,true,'text',$db_opcao," onchange='js_pesquisaq51_usuario(false);'")
?>
       <?
db_input('nome',40,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq51_dtemiss?>">
       <?=@$Lq51_dtemiss?>
    </td>
    <td> 
<?
db_inputdata('q51_dtemiss',@$q51_dtemiss_dia,@$q51_dtemiss_mes,@$q51_dtemiss_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq51_hora?>">
       <?=@$Lq51_hora?>
    </td>
    <td> 
<?
db_input('q51_hora',5,$Iq51_hora,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq51_data?>">
       <?=@$Lq51_data?>
    </td>
    <td> 
<?
db_inputdata('q51_data',@$q51_data_dia,@$q51_data_mes,@$q51_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq51_codautent?>">
       <?=@$Lq51_codautent?>
    </td>
    <td> 
<?
db_input('q51_codautent',100,$Iq51_codautent,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq51_pdfnota?>">
       <?=@$Lq51_pdfnota?>
    </td>
    <td> 
<?
db_input('q51_pdfnota',1,$Iq51_pdfnota,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaq51_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_issnotaavulsa','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.q51_usuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_issnotaavulsa','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.q51_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false,'0','1','775','390');
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.q51_usuario.focus(); 
    document.form1.q51_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.q51_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisaq51_inscr(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_issnotaavulsa','db_iframe_issbase','func_issbase.php?funcao_js=parent.js_mostraissbase1|q02_inscr|q02_numcgm','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.q51_inscr.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_issnotaavulsa','db_iframe_issbase','func_issbase.php?pesquisa_chave='+document.form1.q51_inscr.value+'&funcao_js=parent.js_mostraissbase','Pesquisa',false,'0','1','775','390');
     }else{
       document.form1.q02_numcgm.value = ''; 
     }
  }
}
function js_mostraissbase(chave,erro){
  document.form1.q02_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.q51_inscr.focus(); 
    document.form1.q51_inscr.value = ''; 
  }
}
function js_mostraissbase1(chave1,chave2){
  document.form1.q51_inscr.value = chave1;
  document.form1.q02_numcgm.value = chave2;
  db_iframe_issbase.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_issnotaavulsa','db_iframe_issnotaavulsa','func_issnotaavulsa.php?funcao_js=parent.js_preenchepesquisa|q51_sequencial','Pesquisa',true,'0','1','775','390');
}
function js_preenchepesquisa(chave){
  db_iframe_issnotaavulsa.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>