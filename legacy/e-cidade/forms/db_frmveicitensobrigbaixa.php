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

//MODULO: veiculos
$clveicitensobrigbaixa->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ve11_descr");
$clrotulo->label("ve08_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tve10_sequencial?>">
       <?=@$Lve10_sequencial?>
    </td>
    <td> 
<?
db_input('ve10_sequencial',10,$Ive10_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve10_veiccaditensobrigtipobaixa?>">
       <?
       db_ancora(@$Lve10_veiccaditensobrigtipobaixa,"js_pesquisave10_veiccaditensobrigtipobaixa(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ve10_veiccaditensobrigtipobaixa',10,$Ive10_veiccaditensobrigtipobaixa,true,'text',$db_opcao," onchange='js_pesquisave10_veiccaditensobrigtipobaixa(false);'")
?>
       <?
db_input('ve11_descr',40,$Ive11_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve10_veicitensobrig?>">
       <?
       db_ancora(@$Lve10_veicitensobrig,"js_pesquisave10_veicitensobrig(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ve10_veicitensobrig',10,$Ive10_veicitensobrig,true,'text',$db_opcao," onchange='js_pesquisave10_veicitensobrig(false);'")
?>
       <?
db_input('ve08_descr',40,0,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve10_data?>">
       <?=@$Lve10_data?>
    </td>
    <td> 
<?
if ($db_opcao == 1 || $db_opcao == 11){
  $ve10_data_dia = date("d",db_getsession("DB_datausu"));
  $ve10_data_mes = date("m",db_getsession("DB_datausu"));
  $ve10_data_ano = date("Y",db_getsession("DB_datausu"));
}

db_inputdata('ve10_data',@$ve10_data_dia,@$ve10_data_mes,@$ve10_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve10_hora?>">
       <?=@$Lve10_hora?>
    </td>
    <td> 
<?
if ($db_opcao == 1 || $db_opcao == 11){
  $ve10_hora = db_hora();
}
db_input('ve10_hora',5,$Ive10_hora,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve10_motivo?>">
       <?=@$Lve10_motivo?>
    </td>
    <td> 
<?
db_textarea('ve10_motivo',10,80,$Ive10_motivo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisave10_veiccaditensobrigtipobaixa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_veiccaditensobrigtipobaixa','func_veiccaditensobrigtipobaixa.php?funcao_js=parent.js_mostraveiccaditensobrigtipobaixa1|ve11_sequencial|ve11_descr','Pesquisa',true);
  }else{
     if(document.form1.ve10_veiccaditensobrigtipobaixa.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_veiccaditensobrigtipobaixa','func_veiccaditensobrigtipobaixa.php?pesquisa_chave='+document.form1.ve10_veiccaditensobrigtipobaixa.value+'&funcao_js=parent.js_mostraveiccaditensobrigtipobaixa','Pesquisa',false);
     }else{
       document.form1.ve11_descr.value = ''; 
     }
  }
}
function js_mostraveiccaditensobrigtipobaixa(chave,erro){
  document.form1.ve11_descr.value = chave; 
  if(erro==true){ 
    document.form1.ve10_veiccaditensobrigtipobaixa.focus(); 
    document.form1.ve10_veiccaditensobrigtipobaixa.value = ''; 
  }
}
function js_mostraveiccaditensobrigtipobaixa1(chave1,chave2){
  document.form1.ve10_veiccaditensobrigtipobaixa.value = chave1;
  document.form1.ve11_descr.value = chave2;
  db_iframe_veiccaditensobrigtipobaixa.hide();
}
function js_pesquisave10_veicitensobrig(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_veicitensobrig','func_veicitensobrig.php?funcao_js=parent.js_mostraveicitensobrig1|ve09_sequencial|ve08_descr','Pesquisa',true);
  }else{
     if(document.form1.ve10_veicitensobrig.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_veicitensobrig','func_veicitensobrig.php?pesquisa_chave='+document.form1.ve10_veicitensobrig.value+'&funcao_js=parent.js_mostraveicitensobrig','Pesquisa',false);
     }else{
       document.form1.ve09_veiculos.value = ''; 
     }
  }
}
function js_mostraveicitensobrig(chave,erro){
  document.form1.ve08_descr.value = chave; 
  if(erro==true){ 
    document.form1.ve10_veicitensobrig.focus(); 
    document.form1.ve10_veicitensobrig.value = ''; 
  }
}
function js_mostraveicitensobrig1(chave1,chave2){
  document.form1.ve10_veicitensobrig.value = chave1;
  document.form1.ve08_descr.value          = chave2;
  db_iframe_veicitensobrig.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_veicitensobrigbaixa','func_veicitensobrigbaixa.php?funcao_js=parent.js_preenchepesquisa|ve10_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_veicitensobrigbaixa.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>