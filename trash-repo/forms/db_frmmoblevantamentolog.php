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

//MODULO: cadastro
$clmoblevantamentolog->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j95_pda");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tj98_sequen?>">
       <?=@$Lj98_sequen?>
    </td>
    <td> 
<?
db_input('j98_sequen',8,$Ij98_sequen,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj98_codimporta?>">
       <?
       db_ancora(@$Lj98_codimporta,"js_pesquisaj98_codimporta(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j98_codimporta',8,$Ij98_codimporta,true,'text',$db_opcao," onchange='js_pesquisaj98_codimporta(false);'")
?>
       <?
db_input('j95_pda',3,$Ij95_pda,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj98_matric?>">
       <?=@$Lj98_matric?>
    </td>
    <td> 
<?
db_input('j98_matric',8,$Ij98_matric,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj98_codigo?>">
       <?=@$Lj98_codigo?>
    </td>
    <td> 
<?
db_input('j98_codigo',8,$Ij98_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj98_testada?>">
       <?=@$Lj98_testada?>
    </td>
    <td> 
<?
db_input('j98_testada',15,$Ij98_testada,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj98_pavim?>">
       <?=@$Lj98_pavim?>
    </td>
    <td> 
<?
$x = array('1'=>'Com Asfalto','2'=>'Calçamento','3'=>'Sem Pavimento','4'=>'Pedra Irregular');
db_select('j98_pavim',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj98_agua?>">
       <?=@$Lj98_agua?>
    </td>
    <td> 
<?
$x = array('S'=>'SIM','N'=>'NÃO');
db_select('j98_agua',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj98_esgoto?>">
       <?=@$Lj98_esgoto?>
    </td>
    <td> 
<?
$x = array('S'=>'SIM','N'=>'NÃO');
db_select('j98_esgoto',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj98_eletrica?>">
       <?=@$Lj98_eletrica?>
    </td>
    <td> 
<?
$x = array('S'=>'SIM','N'=>'NÃO');
db_select('j98_eletrica',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj98_meiofio?>">
       <?=@$Lj98_meiofio?>
    </td>
    <td> 
<?
$x = array('S'=>'SIM','N'=>'NÃO');
db_select('j98_meiofio',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj98_iluminacao?>">
       <?=@$Lj98_iluminacao?>
    </td>
    <td> 
<?
$x = array('S'=>'SIM','N'=>'NÃO');
db_select('j98_iluminacao',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj98_telefonia?>">
       <?=@$Lj98_telefonia?>
    </td>
    <td> 
<?
$x = array('S'=>'SIM','N'=>'NÃO');
db_select('j98_telefonia',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj98_lixo?>">
       <?=@$Lj98_lixo?>
    </td>
    <td> 
<?
$x = array('S'=>'SIM','N'=>'NÃO');
db_select('j98_lixo',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaj98_codimporta(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_mobimportacao','func_mobimportacao.php?funcao_js=parent.js_mostramobimportacao1|j95_codimporta|j95_pda','Pesquisa',true);
  }else{
     if(document.form1.j98_codimporta.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_mobimportacao','func_mobimportacao.php?pesquisa_chave='+document.form1.j98_codimporta.value+'&funcao_js=parent.js_mostramobimportacao','Pesquisa',false);
     }else{
       document.form1.j95_pda.value = ''; 
     }
  }
}
function js_mostramobimportacao(chave,erro){
  document.form1.j95_pda.value = chave; 
  if(erro==true){ 
    document.form1.j98_codimporta.focus(); 
    document.form1.j98_codimporta.value = ''; 
  }
}
function js_mostramobimportacao1(chave1,chave2){
  document.form1.j98_codimporta.value = chave1;
  document.form1.j95_pda.value = chave2;
  db_iframe_mobimportacao.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_moblevantamentolog','func_moblevantamentolog.php?funcao_js=parent.js_preenchepesquisa|j98_sequen','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_moblevantamentolog.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>