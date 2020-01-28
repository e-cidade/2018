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

//MODULO: ITBI
$clitbiparespecie->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j32_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tit11_grupo?>">
    <input name="oid" type="hidden" value="<?=@$oid?>">
       <?
       db_ancora(@$Lit11_grupo,"js_pesquisait11_grupo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('it11_grupo',4,$Iit11_grupo,true,'text',$db_opcao," onchange='js_pesquisait11_grupo(false);'")
?>
       <?
db_input('j32_descr',40,$Ij32_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisait11_grupo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cargrup','func_cargrup.php?funcao_js=parent.js_mostracargrup1|j32_grupo|j32_descr','Pesquisa',true);
  }else{
     if(document.form1.it11_grupo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cargrup','func_cargrup.php?pesquisa_chave='+document.form1.it11_grupo.value+'&funcao_js=parent.js_mostracargrup','Pesquisa',false);
     }else{
       document.form1.j32_descr.value = ''; 
     }
  }
}
function js_mostracargrup(chave,erro){
  document.form1.j32_descr.value = chave; 
  if(erro==true){ 
    document.form1.it11_grupo.focus(); 
    document.form1.it11_grupo.value = ''; 
  }
}
function js_mostracargrup1(chave1,chave2){
  document.form1.it11_grupo.value = chave1;
  document.form1.j32_descr.value = chave2;
  db_iframe_cargrup.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_itbiparespecie','func_itbiparespecie.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_itbiparespecie.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>