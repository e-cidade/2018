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

//MODULO: caixa
$clplacaixa->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nomeinst");
      if($db_opcao==1){
 	   $db_action="cai1_placaixa004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="cai1_placaixa005.php";
      }else if($db_opcao==3||$db_opcao==33){
	  if(isset($autenticar)){
 	    $db_action="cai1_placaixa008.php";
	  }else if(isset($autenticar_estorno)){
 	    $db_action="cai1_placaixa010.php";
	  }else{
 	    $db_action="cai1_placaixa006.php";
	  }
      }  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk80_codpla?>">
       <?=@$Lk80_codpla?>
    </td>
    <td> 
<?
db_input('k80_codpla',6,$Ik80_codpla,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk80_data?>">
       <?=@$Lk80_data?>
    </td>
    <td> 
<?
if($db_opcao==1){
  $k80_data_dia = date("d",db_getsession("DB_datausu"));
  $k80_data_mes = date("m",db_getsession("DB_datausu"));
  $k80_data_ano = date("Y",db_getsession("DB_datausu"));
}
db_inputdata('k80_data',@$k80_data_dia,@$k80_data_mes,@$k80_data_ano,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk80_instit?>">
       <?
       //db_ancora(@$Lk80_instit,"js_pesquisak80_instit(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
if($db_opcao==1){
  $k80_instit = db_getsession("DB_instit");
}
db_input('k80_instit',2,$Ik80_instit,true,'hidden',3," onchange='js_pesquisak80_instit(false);'")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk80_dtaut?>">
       <?=@$Lk80_dtaut?>
    </td>
    <td> 
<?
db_inputdata('k80_dtaut',@$k80_dtaut_dia,@$k80_dtaut_mes,@$k80_dtaut_ano,true,'text',3,"")
?>
    </td>
  </tr>


  <tr>
    <td nowrap>
    <?
     if ($db_opcao == 3) {
      echo "<b>Total:</b>";
     }
      ?>
    </td>
    <td> 
<?
if ($db_opcao == 3 and isset($k80_codpla)) {
  $result = pg_exec("select sum(k81_valor) from placaixarec where k81_codpla = $k80_codpla");
  db_fieldsmemory($result,0,true);
  echo "<b>" . db_formatar($sum,'f') . "</b>";
}
?>
    </td>
  </tr>

  
  </table>
  </center>
<?
if(!isset($autenticar) && !isset($autenticar_estorno)){
?>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<?
}else if(isset($autenticar_estorno)){
  echo "<input name='estorna' type='submit' id='db_opcao' value='Estornar' ".($db_botao==false?"disabled":"")." >";
}else{
  echo "<input name='autentica' type='submit' id='db_opcao' value='Autenticar' ".($db_botao==false?"disabled":"")." >";
}
?>
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisak80_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_placaixa','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true,'0','1');
  }else{
     if(document.form1.k80_instit.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_placaixa','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.k80_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false,'0','1');
     }else{
       document.form1.nomeinst.value = ''; 
     }
  }
}
function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave; 
  if(erro==true){ 
    document.form1.k80_instit.focus(); 
    document.form1.k80_instit.value = ''; 
  }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.k80_instit.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}
function js_pesquisa(){
  <?
  if(!isset($autenticar) && !isset($autenticar_estorno)){
    ?>
    js_OpenJanelaIframe('top.corpo.iframe_placaixa','db_iframe_placaixa','func_placaixaaut.php?funcao_js=parent.js_preenchepesquisa|k80_codpla','Pesquisa',true,'0','1');
    <?
  }else if(isset($autenticar_estorno)){
    ?>
    js_OpenJanelaIframe('top.corpo.iframe_placaixa','db_iframe_placaixa','func_placaixaest.php?funcao_js=parent.js_preenchepesquisa|k80_codpla','Pesquisa',true,'0','1');
    <?
  }else{
    ?>
    js_OpenJanelaIframe('top.corpo.iframe_placaixa','db_iframe_placaixa','func_placaixaaut.php?funcao_js=parent.js_preenchepesquisa|k80_codpla','Pesquisa',true,'0','1');
    <?
  }
  ?>
}
function js_preenchepesquisa(chave){
  db_iframe_placaixa.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>