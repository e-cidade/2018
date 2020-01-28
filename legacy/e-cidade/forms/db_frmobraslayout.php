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

//MODULO: projetos
$clobraslayout->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ob01_nomeobra");
$clrotulo->label("ob09_habite");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
<?

db_input('ob14_seq',10,$Iob14_seq,true,'hidden',$db_opcao,"")
?>
  <tr>
    <td nowrap title="<?=@$Tob14_codobra?>">
       <?
       db_ancora(@$Lob14_codobra,"js_pesquisaob14_codobra(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ob14_codobra',10,$Iob14_codobra,true,'text',3," onchange='js_pesquisaob14_codobra(false);'")
?>
       <?
db_input('ob01_nomeobra',55,$Iob01_nomeobra,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td>
<?
$ob14_data_dia = date("d",db_getsession("DB_datausu"));
$ob14_data_mes = date("m",db_getsession("DB_datausu"));
$ob14_data_ano = date("Y",db_getsession("DB_datausu"));
db_inputdata('ob14_data',@$ob14_data_dia,@$ob14_data_mes,@$ob14_data_ano,true,'hidden',3,"");
?>
      </td>
    </tr>  
  </table>
  </center>
<input name="imprimir" type="submit" id="db_opcao" value="Gerar">
</form>
<script>
function js_pesquisaob14_codobra(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_obras','func_obraslayout.php?funcao_js=parent.js_mostraobras1|ob01_codobra|ob01_nomeobra','Pesquisa',true);
  }else{
     if(document.form1.ob14_codobra.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_obras','func_obraslayout.php?pesquisa_chave='+document.form1.ob14_codobra.value+'&funcao_js=parent.js_mostraobras','Pesquisa',false);
     }else{
       document.form1.ob01_nomeobra.value = ''; 
     }
  }
}
function js_mostraobras(chave,erro){
  document.form1.ob01_nomeobra.value = chave; 
  if(erro==true){ 
    document.form1.ob14_codobra.focus(); 
    document.form1.ob14_codobra.value = ''; 
  }
}
function js_mostraobras1(chave1,chave2){
  document.form1.ob14_codobra.value = chave1;
  document.form1.ob01_nomeobra.value = chave2;
  db_iframe_obras.hide();
}
function js_pesquisaob14_codhab(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_obrashabite','func_obrashabite.php?funcao_js=parent.js_mostraobrashabite1|ob09_codhab|ob09_habite','Pesquisa',true);
  }else{
     if(document.form1.ob14_codhab.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_obrashabite','func_obrashabite.php?pesquisa_chave='+document.form1.ob14_codhab.value+'&funcao_js=parent.js_mostraobrashabite','Pesquisa',false);
     }else{
       document.form1.ob09_habite.value = ''; 
     }
  }
}
function js_mostraobrashabite(chave,erro){
  document.form1.ob09_habite.value = chave; 
  if(erro==true){ 
    document.form1.ob14_codhab.focus(); 
    document.form1.ob14_codhab.value = ''; 
  }
}
function js_mostraobrashabite1(chave1,chave2){
  document.form1.ob14_codhab.value = chave1;
  document.form1.ob09_habite.value = chave2;
  db_iframe_obrashabite.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_obraslayout','func_obraslayout.php?funcao_js=parent.js_preenchepesquisa|ob14_seq','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_obraslayout.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>