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

//MODULO: contabilidade
$clconplanoexerecurso->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("c61_codcon");
$clrotulo->label("c61_codcon");
$clrotulo->label("o15_descr");
$clrotulo->label("c61_codcon");
$clrotulo->label("c60_descr");

?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tc89_anousu?>"><? db_ancora(@$Lc89_anousu,"",$db_opcao); ?> </td>
    <td><?
            $c89_anousu = db_getsession('DB_anousu');
            db_input('c89_anousu',4,$Ic89_anousu,true,'text',3);
        ?>
    </td>
  </tr>
  <?  if ($db_opcao==2) 
         $op = 3;
      else 
	 $op = $db_opcao;
  ?>
  <tr>
    <td nowrap title="<?=@$Tc89_recurso?>"><? db_ancora(@$Lc89_recurso,"js_pesquisac89_recurso(true);",$op); ?></td>
    <td><?  db_input('c89_recurso',4,$Ic89_recurso,true,'text',$op," onchange='js_pesquisac89_recurso(false);'"); ?>
       <?   db_input('o15_descr',60,$Io15_descr,true,'text',3,'');     ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc89_reduz?>"><? db_ancora(@$Lc89_reduz,"js_pesquisac89_reduz(true);",$op);?></td>
    <td><? db_input('c89_reduz',6,$Ic89_reduz,true,'text',$op," onchange='js_pesquisac89_reduz(false);'") ?>
        <? db_input('c60_descr',20,$Ic60_descr,true,'text',3,'');   ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc89_vlrcre?>"><?=@$Lc89_vlrcre?> </td>
    <td><? db_input('c89_vlrcre',15,$Ic89_vlrcre,true,'text',1,""); ?></td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc89_vlrdeb?>"><?=@$Lc89_vlrdeb?> </td>
    <td><? db_input('c89_vlrdeb',15,$Ic89_vlrdeb,true,'text',1,""); ?> </td>
  </tr>
  </table>

  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisac89_recurso(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orctiporec','func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1|o15_codigo|o15_descr','Pesquisa',true);
  }else{
     if(document.form1.c89_recurso.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orctiporec','func_orctiporec.php?pesquisa_chave='+document.form1.c89_recurso.value+'&funcao_js=parent.js_mostraorctiporec','Pesquisa',false);
     }else{
       document.form1.o15_descr.value = ''; 
     }
  }
}
function js_mostraorctiporec(chave,erro){
  document.form1.o15_descr.value = chave; 
  if(erro==true){ 
    document.form1.c89_recurso.focus(); 
    document.form1.c89_recurso.value = ''; 
  }
}
function js_mostraorctiporec1(chave1,chave2){
  document.form1.c89_recurso.value = chave1;
  document.form1.o15_descr.value = chave2;
  db_iframe_orctiporec.hide();
}
function js_pesquisac89_reduz(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_conplanoreduz','func_conplanoreduz.php?funcao_js=parent.js_mostraconplanoreduz1|c61_reduz|c60_descr','Pesquisa',true);
  }else{
     if(document.form1.c89_reduz.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_conplanoreduz','func_conplanoreduz.php?pesquisa_chave='+document.form1.c89_reduz.value+'&funcao_js=parent.js_mostraconplanoreduz','Pesquisa',false);
     }else{
       document.form1.c61_codcon.value = ''; 
     }
  }
}
function js_mostraconplanoreduz(chave,erro){
  document.form1.c61_codcon.value = chave; 
  if(erro==true){ 
    document.form1.c89_reduz.focus(); 
    document.form1.c89_reduz.value = ''; 
  }
}
function js_mostraconplanoreduz1(chave1,chave2){
  document.form1.c89_reduz.value = chave1;
  document.form1.c60_descr.value = chave2;
  db_iframe_conplanoreduz.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_conplanoexerecurso','func_conplanoexerecurso.php?funcao_js=parent.js_preenchepesquisa|c89_anousu|c89_recurso|c89_reduz','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1,chave2){
  db_iframe_conplanoexerecurso.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1+'&chavepesquisa2='+chave2";
  }
  ?>
}
</script>