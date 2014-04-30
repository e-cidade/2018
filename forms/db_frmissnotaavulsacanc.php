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
$clissnotaavulsacanc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("q51_sequencial");
$clrotulo->label("q51_numnota");
?>
<form name="form1" method="post" action="">
<center>
<table>
 <tr>
 <td>
   <fieldset><legend><b>Cancelamento de Nota Avulsa</b></legend>
<table>
  
  <tr>
    <td nowrap title="<?=@$Tq63_sequencial?>">
       <?=@$Lq63_sequencial?>
    </td>
    <td> 
<?
db_input('q63_sequencial',10,$Iq63_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq63_issnotaavulsa?>">
       <?
       db_ancora(@$Lq63_issnotaavulsa,"js_pesquisaq63_issnotaavulsa(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q63_issnotaavulsa',10,$Iq63_issnotaavulsa,true,'hidden',$db_opcao," onchange='js_pesquisaq63_issnotaavulsa(false);'");
db_input('q51_numnota',10,$Iq51_numnota,true,'text',$db_opcao," onchange='js_pesquisaq63_issnotaavulsa(false);'");
?>
       <?
db_input('z01_nome',40,'',true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq63_motivo?>">
       <?=@$Lq63_motivo?>
    </td>
    <td> 
<?
db_textarea('q63_motivo',6,50,$Iq63_motivo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </fieldset>
  </td></tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaq63_issnotaavulsa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_issnotaavulsa','func_issnotaavulsaalt.php?filtrabaixa=1&filtraarrecad=1&funcao_js=parent.js_mostraissnotaavulsa1|q51_sequencial|z01_nome|q51_numnota','Pesquisa',true);
  }else{
     if(document.form1.q51_numnota.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_issnotaavulsa','func_issnotaavulsaalt.php?filtrabaixa=1&filtraarrecad=1&pesquisa_chave='+document.form1.q51_numnota.value+'&funcao_js=parent.js_mostraissnotaavulsa','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostraissnotaavulsa(chave,erro,chave2){
  document.form1.q63_issnotaavulsa.value = chave2; 
    document.form1.z01_nome.value     = chave; 
  if(erro==true){ 
    document.form1.q63_issnotaavulsa.focus(); 
    document.form1.q63_issnotaavulsa.value = ''; 
    document.form1.q51_numnota.value       = ''; 
    document.form1.z01_nome.value       = ''; 
  }
}
function js_mostraissnotaavulsa1(chave1,chave2, chave3){
  document.form1.q63_issnotaavulsa.value = chave1;
  document.form1.q51_numnota.value = chave1;
  document.form1.z01_nome.value          = chave2;
  db_iframe_issnotaavulsa.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_issnotaavulsacanc','func_issnotaavulsacanc.php?funcao_js=parent.js_preenchepesquisa|q63_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_issnotaavulsacanc.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>