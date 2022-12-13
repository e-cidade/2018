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
$clrhvisavalecad->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("rh01_numcgm");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <td>
 <fieldset><legend><b>Cadastro de servidor</b></legend>
 <table>
  <tr>
    <td nowrap title="<?=@$Trh49_codigo?>" align="right">
      <?=@$Lrh49_codigo?>
    </td>
    <td> 
      <?
      db_input('rh49_codigo',10,$Irh49_codigo,true,'text',3,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="Ano / Mês de competência" align="right">
      <b>Ano / Mês:</b>
    </td>
    <td> 
      <?
      if(!isset($rh49_anousu)){
        $rh49_anousu = db_anofolha();
      }
      if(!isset($rh49_mesusu)){
        $rh49_mesusu = db_mesfolha();
      }
      db_input('rh49_anousu',4,$Irh49_anousu,true,'text',3,"")
      ?>
      &nbsp;/&nbsp;
      <?
      db_input('rh49_mesusu',2,$Irh49_mesusu,true,'text',3,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh49_regist?>" align="right">
      <?
      db_ancora(@$Lrh49_regist,"js_pesquisarh49_regist(true);",$db_opcao);
      ?>
    </td>
    <td> 
      <?
      db_input('rh49_regist',10,$Irh49_regist,true,'text',$db_opcao," onchange='js_pesquisarh49_regist(false);'")
      ?>
      <?
      db_input('rh49_numcgm',10,$Irh49_numcgm,true,'text',3," onchange='js_pesquisarh49_numcgm(false);'")
      ?>
      <?
      db_input('z01_nome',40,$Iz01_nome,true,'text',3," ")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh49_valor?>" align="right">
      <?=@$Lrh49_valor?>
    </td>
    <td> 
      <?
      if(!isset($rh49_valor)){
      	$rh49_valor = 0;
      }
      db_input('rh49_valor',10,$Irh49_valor,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>


<tr>
    <td nowrap title="<?=@$Trh49_perc?>" align="right">
       <?=@$Lrh49_perc?>
    </td>
    <td>
<?
db_input('rh49_perc',10,$Irh49_perc,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  
<tr>
    <td nowrap title="<?=@$Trh49_percdep?>" align="right">
       <?=@$Lrh49_percdep?>
    </td>
    <td>
		<?
		  db_input('rh49_percdep',10,$Irh49_percdep,true,'text',$db_opcao,"");
		?>
    </td>
  </tr>
  
  <tr>
    <td nowrap title="<?=@$Trh49_diasafasta?>" align="right">
       <?=@$Lrh49_diasafasta?>
    </td>
    <td>
<?
db_input('rh49_diasafasta',10,$Irh49_diasafasta,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh49_valormes?>" align="right">
       <?=@$Lrh49_valormes?>
    </td>
    <td>
<?
db_input('rh49_valormes',10,$Irh49_valormes,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
</table>
</fieldset>
</td>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_testacampos();">
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_testacampos(){
  if(document.form1.rh49_regist.value == ""){
    alert("Informe a matrícula do funcionário.");
    document.form1.rh49_regist.focus();
  }else if(document.form1.rh49_valor.value == ""){
    alert("Informe o valor.");
    document.form1.rh49_valor.focus();
  }else{
    return true;
  }
  return false;
}

function js_pesquisarh49_regist(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoalnome.php?instit=<?=(db_getsession("DB_instit"))?>&funcao_js=parent.js_mostrarhpessoal1|rh01_regist|rh01_numcgm|z01_nome','Pesquisa',true);
  }else{
    if(document.form1.rh49_regist.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoalnome.php?instit=<?=(db_getsession("DB_instit"))?>&pesquisa_chave='+document.form1.rh49_regist.value+'&funcao_js=parent.js_mostrarhpessoal','Pesquisa',false);
    }else{
      document.form1.rh49_numcgm.value = '';
      document.form1.z01_nome.value    = '';
    }
  }
}

function js_mostrarhpessoal(chave,chave2,erro){
  document.form1.rh49_numcgm.value = chave;
  document.form1.z01_nome.value    = chave2;
  if(erro==true){ 
    document.form1.rh49_regist.focus(); 
    document.form1.rh49_regist.value = ''; 
  }
  document.form1.submit();
 
}

function js_mostrarhpessoal1(chave1,chave2,chave3){
  document.form1.rh49_regist.value = chave1;
  document.form1.rh49_numcgm.value = chave2;
  document.form1.z01_nome.value    = chave3;
  db_iframe_rhpessoal.hide();
  document.form1.submit();
}

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rhvisavalecad','func_rhvisavalecad.php?instit=<?=db_getsession("DB_instit")?>&funcao_js=parent.js_preenchepesquisa|rh49_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_rhvisavalecad.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>