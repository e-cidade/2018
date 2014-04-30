<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
$clpcforne->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
  if ($db_opcao == 1) {
    
    $db_action       = "com1_pcforne004.php";
    $sLegendFieldset = "Cadastro";
    $iOpcaoInputNume = 1;
  } else if ($db_opcao == 2 || $db_opcao == 22) {
    
    $db_action       = "com1_pcforne005.php";
    $sLegendFieldset = "Alteração";
    $iOpcaoInputNume = 22;
  } else if ($db_opcao == 3 || $db_opcao == 33) {
    
    $db_action       = "com1_pcforne006.php";
    $sLegendFieldset = "Exclusão";
    $iOpcaoInputNume = 3;
  }  
?>

<form name="form1" method="post" action="<?=$db_action?>">
<center>
<fieldset style="width: 800px;">
<legend style="font-weight: bold;"><?=$sLegendFieldset;?> de Fornecedores</legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tpc60_numcgm?>">
       <?
       db_ancora(@$Lpc60_numcgm,"js_pesquisapc60_numcgm(true);",($db_opcao==1?$db_opcao:3));
       ?>
    </td>
    <td> 
      <?
        db_input('pc60_numcgm',8,$Ipc60_numcgm,true,'text',($db_opcao==1?$db_opcao:3)," onchange='js_pesquisapc60_numcgm(false);'")
      ?>
       <?
        db_input('z01_nome', 50, $Iz01_nome, true, 'text', $iOpcaoInputNume, '')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc60_dtlanc?>">
       <?=@$Lpc60_dtlanc?>
    </td>
    <td> 
      <?
        db_inputdata('pc60_dtlanc',date("d",db_getsession("DB_datausu")),date("m",db_getsession("DB_datausu")),date("Y",db_getsession("DB_datausu")),true,'text',3,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc60_obs?>">
       <?=@$Lpc60_obs?>
    </td>
    <td> 
<?
db_textarea('pc60_obs',2,80,$Ipc60_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc60_bloqueado?>">
       <?=@$Lpc60_bloqueado?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('pc60_bloqueado',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  </table>
</fieldset>
<br />
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</center>
</form>
<script>
function js_pesquisapc60_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_pcforne','db_iframe_nomes','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true,'0');
  }else{
     if(document.form1.pc60_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_pcforne','db_iframe_nomes','func_nome.php?pesquisa_chave='+document.form1.pc60_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false,'0','1','775','390');
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.pc60_numcgm.focus(); 
    document.form1.pc60_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.pc60_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_nomes.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_pcforne','db_iframe_pcforne','func_pcforne.php?funcao_js=parent.js_preenchepesquisa|pc60_numcgm','Pesquisa',true,'0');
}
function js_preenchepesquisa(chave){
  db_iframe_pcforne.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>