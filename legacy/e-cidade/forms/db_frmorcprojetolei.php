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
$clorcprojetolei->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nomeinst");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td>
      <fieldset>
         <legend>
          <b>Projeto de Lei</b>
         </legend> 
         <table> 
            <tr>
              <td nowrap title="<?=@$To138_sequencial?>">
                 <?=@$Lo138_sequencial?>
              </td>
              <td> 
                <?
                db_input('o138_sequencial',10,$Io138_sequencial,true,'text', 3,"");
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$To138_numerolei?>">
                 <?=@$Lo138_numerolei?>
              </td>
              <td> 
              <?
              db_input('o138_numerolei',25,$Io138_numerolei,true,'text',$db_opcao,"")
              ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$To138_data?>">
                 <?=@$Lo138_data?>
              </td>
              <td> 
              <?
              db_inputdata('o138_data',@$o138_data_dia,@$o138_data_mes,@$o138_data_ano,true,'text',$db_opcao,"")
              ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$To138_textolei?>" colspan="2">
                <fieldset>
                 <legend>
                 <?=@$Lo138_textolei?>
                 </legend>
                 <?  
                 db_textarea('o138_textolei',6,0,$Io138_textolei,true,'text',$db_opcao," style='width:100%'");
                 ?>
              </fieldset>
              </td>
            </tr>
         </table>
       </fieldset>
     </td>
   </tr>
</table>         
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisao138_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true);
  }else{
     if(document.form1.o138_instit.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.o138_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
     }else{
       document.form1.nomeinst.value = ''; 
     }
  }
}
function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave; 
  if(erro==true){ 
    document.form1.o138_instit.focus(); 
    document.form1.o138_instit.value = ''; 
  }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.o138_instit.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_orcprojetolei','func_orcprojetolei.php?funcao_js=parent.js_preenchepesquisa|o138_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_orcprojetolei.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>