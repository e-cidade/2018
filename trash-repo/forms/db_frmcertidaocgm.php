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
$clcertidaocgm->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("p50_tipo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tp49_sequencial?>">
    <input name="oid" type="hidden" value="<?=@$oid?>">
       <?
       db_ancora(@$Lp49_sequencial,"js_pesquisap49_sequencial(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('p49_sequencial',10,$Ip49_sequencial,true,'text',$db_opcao," onchange='js_pesquisap49_sequencial(false);'")
?>
       <?
db_input('p50_tipo',1,$Ip50_tipo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp49_numcgm?>">
       <?
       db_ancora(@$Lp49_numcgm,"js_pesquisap49_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('p49_numcgm',10,$Ip49_numcgm,true,'text',$db_opcao," onchange='js_pesquisap49_numcgm(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisap49_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.p49_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?pesquisa_chave='+document.form1.p49_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.p49_numcgm.focus(); 
    document.form1.p49_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.p49_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisap49_sequencial(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_certidao','func_certidao.php?funcao_js=parent.js_mostracertidao1|p50_sequencial|p50_tipo','Pesquisa',true);
  }else{
     if(document.form1.p49_sequencial.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_certidao','func_certidao.php?pesquisa_chave='+document.form1.p49_sequencial.value+'&funcao_js=parent.js_mostracertidao','Pesquisa',false);
     }else{
       document.form1.p50_tipo.value = ''; 
     }
  }
}
function js_mostracertidao(chave,erro){
  document.form1.p50_tipo.value = chave; 
  if(erro==true){ 
    document.form1.p49_sequencial.focus(); 
    document.form1.p49_sequencial.value = ''; 
  }
}
function js_mostracertidao1(chave1,chave2){
  document.form1.p49_sequencial.value = chave1;
  document.form1.p50_tipo.value = chave2;
  db_iframe_certidao.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_certidaocgm','func_certidaocgm.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_certidaocgm.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>