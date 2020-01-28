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

//MODULO: orcamento
$clorcsuplemretif->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o39_descr");
$clrotulo->label("o39_descr");
$clrotulo->label("nome");
?>
<form name="form1" method="post" action="">
<table border="0" align="center">
  <tr>
    <td colspan=2> &nbsp;</td>
  </tr>
  
  <tr>
    <td nowrap title="<?=@$To48_seq?>"><?=@$Lo48_seq?></td>
    <td><? db_input('o48_seq',10,$Io48_seq,true,'text',3,"") ?> </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To48_projeto?>"><? db_ancora(@$Lo48_projeto,"js_pesquisao48_projeto(true);",$db_opcao); ?></td>
    <td nowrap><? db_input('o48_projeto',10,$Io48_projeto,true,'text',$db_opcao," onchange='js_pesquisao48_projeto(false);'") ?>
                         <?db_input('o39_descr',80,$Io39_descr,true,'text',3,'')   ?>
         </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To48_retificado?>"><?  db_ancora(@$Lo48_retificado,"js_pesquisao48_retificado(true);",$db_opcao);  ?></td>
    <td nowrap><? db_input('o48_retificado',10,$Io48_retificado,true,'text',$db_opcao," onchange='js_pesquisao48_retificado(false);'") ?>
                         <? db_input('o39_descr_retificado',80,$Io39_descr,true,'text',3,'')       ?>
        </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To48_texto?>"><?=@$Lo48_texto?></td>
    <td><?db_textarea('o48_texto',0,80,$Io48_texto,true,'text',$db_opcao,"") ?>
    </td>
  </tr>  
  <tr>
    <td nowrap title="<?=@$To48_data?>"><?=@$Lo48_data?></td>
    <td><? db_inputdata('o48_data',@$o48_data_dia,@$o48_data_mes,@$o48_data_ano,true,'text',$db_opcao,"") ?>   </td>
  </tr>
  </table>
  

<input name="processar" type="submit" id="processar" value="Processar" >

</form>
<script>
function js_pesquisao48_projeto(mostra){
  if(mostra==true){    
   js_OpenJanelaIframe('top.corpo','db_iframe_orcprojeto','func_orcprojeto_np.php?funcao_js=parent.js_mostraorcprojeto1|o39_codproj|o39_descr','Pesquisa',true); 
  }else{
     if(document.form1.o48_projeto.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcprojeto','func_orcprojeto_np.php?pesquisa_chave='+document.form1.o48_projeto.value+'&funcao_js=parent.js_mostraorcprojeto','Pesquisa',false);
     }else{
       document.form1.o39_descr.value = ''; 
     }
  }
}
function js_mostraorcprojeto(chave,erro){
  document.form1.o39_descr.value = chave; 
  if(erro==true){ 
    document.form1.o48_projeto.focus(); 
    document.form1.o48_projeto.value = ''; 
  }
}
function js_mostraorcprojeto1(chave1,chave2){
  document.form1.o48_projeto.value = chave1;
  document.form1.o39_descr.value = chave2;
  db_iframe_orcprojeto.hide();
}
function js_pesquisao48_retificado(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprojeto','func_orcprojetodesprocessa.php?funcao_js=parent.js_mostra_retificado_orcprojeto1|o39_codproj|o39_descr','Pesquisa',true);
  }else{
     if(document.form1.o48_retificado.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcprojeto','func_orcprojetodesprocessa.php?pesquisa_chave='+document.form1.o48_retificado.value+'&funcao_js=parent.js_mostra_retificado_orcprojeto','Pesquisa',false);
     }else{
       document.form1.o39_descr.value = ''; 
     }
  }
}
function js_mostra_retificado_orcprojeto(chave,erro){
  document.form1.o39_descr.value = chave; 
  if(erro==true){ 
    document.form1.o48_retificado.focus(); 
    document.form1.o48_retificado.value = ''; 
  }
}
function js_mostra_retificado_orcprojeto1(chave1,chave2){
  document.form1.o48_retificado.value = chave1;
  document.form1.o39_descr_retificado.value = chave2;
  db_iframe_orcprojeto.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_orcsuplemretif','func_orcsuplemretif.php?funcao_js=parent.js_preenchepesquisa|o48_seq','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_orcsuplemretif.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>