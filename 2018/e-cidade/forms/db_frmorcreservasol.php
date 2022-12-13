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
$clorcreservasol->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o80_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To82_codres?>">
       <?
       db_ancora(@$Lo82_codres,"js_pesquisao82_codres(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o82_codres',8,$Io82_codres,true,'text',$db_opcao," onchange='js_pesquisao82_codres(false);'")
?>
       <?
db_input('o80_descr',1,$Io80_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To82_codsol?>">
       <?=@$Lo82_codsol?>
    </td>
    <td> 
<?
db_input('o82_codsol',6,$Io82_codsol,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisao82_codres(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcreserva','func_orcreserva.php?funcao_js=parent.js_mostraorcreserva1|o80_codres|o80_descr','Pesquisa',true);
  }else{
     if(document.form1.o82_codres.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcreserva','func_orcreserva.php?pesquisa_chave='+document.form1.o82_codres.value+'&funcao_js=parent.js_mostraorcreserva','Pesquisa',false);
     }else{
       document.form1.o80_descr.value = ''; 
     }
  }
}
function js_mostraorcreserva(chave,erro){
  document.form1.o80_descr.value = chave; 
  if(erro==true){ 
    document.form1.o82_codres.focus(); 
    document.form1.o82_codres.value = ''; 
  }
}
function js_mostraorcreserva1(chave1,chave2){
  document.form1.o82_codres.value = chave1;
  document.form1.o80_descr.value = chave2;
  db_iframe_orcreserva.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_orcreservasol','func_orcreservasol.php?funcao_js=parent.js_preenchepesquisa|o82_codres|o82_codsol','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_orcreservasol.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>