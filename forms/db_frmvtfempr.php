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

//MODULO: pessoal
$clvtfempr->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh35_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td align="left" nowrap title="Digite o Ano / Mes de competência" >
      <strong>Ano / Mês :&nbsp;&nbsp;</strong>
    </td>
    <td>
      <?
      if(!isset($r16_anousu)){
        $r16_anousu = db_anofolha();
      }
      db_input('r16_anousu',4,$Ir16_anousu,true,'text',$db_opcao,'')
      ?>
      &nbsp;/&nbsp;
      <?
      if(!isset($r16_mesusu)){
        $r16_mesusu = db_mesfolha();
      }
      db_input('r16_mesusu',2,$Ir16_mesusu,true,'text',$db_opcao,'')
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr16_codigo?>">
      <?=@$Lr16_codigo?>
    </td>
    <td> 
      <?
      db_input('r16_codigo',4,$Ir16_codigo,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr16_descr?>">
      <?=@$Lr16_descr?>
    </td>
    <td> 
      <?
      db_input('r16_descr',30,$Ir16_descr,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr16_valor?>">
      <?=@$Lr16_valor?>
    </td>
    <td> 
      <?
      db_input('r16_valor',15,$Ir16_valor,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr16_perc?>">
      <?=@$Lr16_perc?>
    </td>
    <td> 
      <?
      db_input('r16_perc',15,$Ir16_perc,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr16_empres?>">
      <?
      db_ancora(@$Lr16_empres,"js_pesquisar16_empres(true);",$db_opcao);
      ?>
    </td>
    <td> 
      <?
      db_input('r16_empres',4,$Ir16_empres,true,'text',$db_opcao," onchange='js_pesquisar16_empres(false);'")
      ?>
      <?
      db_input('rh35_descr',40,$Irh35_descr,true,'text',3,'')
      ?>
    </td>
  </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisar16_empres(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhempresavt','func_rhempresavt.php?funcao_js=parent.js_mostrarhempresavt1|rh35_codigo|rh35_descr','Pesquisa',true);
  }else{
    if(document.form1.r16_empres.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_rhempresavt','func_rhempresavt.php?pesquisa_chave='+document.form1.r16_empres.value+'&funcao_js=parent.js_mostrarhempresavt','Pesquisa',false);
    }else{
      document.form1.rh35_descr.value = ''; 
    }
  }
}
function js_mostrarhempresavt(chave,erro){
  document.form1.rh35_descr.value = chave; 
  if(erro==true){ 
    document.form1.r16_empres.focus(); 
    document.form1.r16_empres.value = ''; 
  }
}
function js_mostrarhempresavt1(chave1,chave2){
  document.form1.r16_empres.value = chave1;
  document.form1.rh35_descr.value = chave2;
  db_iframe_rhempresavt.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_vtfempr','func_vtfempr.php?funcao_js=parent.js_preenchepesquisa|r16_anousu|r16_mesusu|r16_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1,chave2){
  db_iframe_vtfempr.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1+'&chavepesquisa2='+chave2";
  }
  ?>
}
</script>