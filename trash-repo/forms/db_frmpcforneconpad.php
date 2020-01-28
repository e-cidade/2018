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

//MODULO: compras
$clpcforneconpad->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("pc63_numcgm");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tpc64_contabanco?>">
       <?
       db_ancora(@$Lpc64_contabanco,"js_pesquisapc64_contabanco(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('pc64_contabanco',6,$Ipc64_contabanco,true,'text',$db_opcao," onchange='js_pesquisapc64_contabanco(false);'")
?>
       <?
db_input('pc63_numcgm',8,$Ipc63_numcgm,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisapc64_contabanco(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pcfornecon','func_pcfornecon.php?funcao_js=parent.js_mostrapcfornecon1|pc63_contabanco|pc63_numcgm','Pesquisa',true);
  }else{
     if(document.form1.pc64_contabanco.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pcfornecon','func_pcfornecon.php?pesquisa_chave='+document.form1.pc64_contabanco.value+'&funcao_js=parent.js_mostrapcfornecon','Pesquisa',false);
     }else{
       document.form1.pc63_numcgm.value = ''; 
     }
  }
}
function js_mostrapcfornecon(chave,erro){
  document.form1.pc63_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.pc64_contabanco.focus(); 
    document.form1.pc64_contabanco.value = ''; 
  }
}
function js_mostrapcfornecon1(chave1,chave2){
  document.form1.pc64_contabanco.value = chave1;
  document.form1.pc63_numcgm.value = chave2;
  db_iframe_pcfornecon.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_pcforneconpad','func_pcforneconpad.php?funcao_js=parent.js_preenchepesquisa|pc64_contabanco','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_pcforneconpad.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>