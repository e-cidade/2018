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

//MODULO: caixa
$cldebcontapedido->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nomeinst");
?>
<form name="form1" method="post" action="">
<?
db_input('tipo',10,"",true,'hidden',3,"");
db_input('codtipo',10,"",true,'hidden',3,"");
?>
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Td63_codigo?>">
       <?=@$Ld63_codigo?>
    </td>
    <td> 
<?
db_input('d63_codigo',10,$Id63_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>   
      <td>
      <?
       db_ancora($Ld63_banco,' js_bancos(true); ',$db_opcao);
      ?>
       </td>
       <td> 
      <?
       db_input('d63_banco',5,$Id63_banco,true,'text',1,"onchange='js_bancos(false)'");
       db_input('nome_banco',40,"",true,'text',3);
       
      ?>
       </td>
     </tr>
     <!--
  <tr>
    <td nowrap title="<?=@$Td63_banco?>">
       <?=@$Ld63_banco?>
    </td>
    <td> 
<?
db_input('d63_banco',3,$Id63_banco,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  -->
  <tr>
    <td nowrap title="<?=@$Td63_agencia?>">
       <?=@$Ld63_agencia?>
    </td>
    <td> 
<?
db_input('d63_agencia',4,$Id63_agencia,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Td63_conta?>">
       <?=@$Ld63_conta?>
    </td>
    <td> 
<?
db_input('d63_conta',14,$Id63_conta,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Td63_status?>">
       <?=@$Ld63_status?>
    </td>
    <td> 
<?
$x = array('1'=>'Pendente','2'=>'Ativo','3'=>'Inativo');
db_select('d63_status',$x,true,$db_opcao,"");
?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Td63_idempresa?>">
       <?=@$Ld63_idempresa?>
    </td>
    <td> 
<?
db_input('d63_idempresa',25,$Id63_idempresa,true,'text',$db_opcao,"")
?>
    </td>
  </tr>

	
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir")) ?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir")) ?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisad63_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true);
  }else{
     if(document.form1.d63_instit.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.d63_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
     }else{
       document.form1.nomeinst.value = ''; 
     }
  }
}
function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave; 
  if(erro==true){ 
    document.form1.d63_instit.focus(); 
    document.form1.d63_instit.value = ''; 
  }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.d63_instit.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}
function js_pesquisa(){
  sTipo = '';
  if ( document.form1.tipo.value == 'CGM' ) {
    sTipo = '&sTipo=CGM';
  } else if ( document.form1.tipo.value == 'MATRIC' ) {
    sTipo = '&sTipo=MATRIC';
  } else if ( document.form1.tipo.value == 'INSCR' ) {
    sTipo = '&sTipo=INSCR';
  }
  js_OpenJanelaIframe('','db_iframe_debcontapedido','func_debcontapedido.php?funcao_js=parent.js_preenchepesquisa|d63_codigo'+sTipo,'Pesquisa',true);  
  //js_OpenJanelaIframe('','db_iframe_debcontapedido','func_debcontapedido.php?funcao_js=parent.js_preenchepesquisa|d63_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_debcontapedido.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+sTipo";
  }
  ?>
}
function js_bancos(mostra){
  var bancos=document.form1.d63_banco.value;
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe2','func_bancos.php?funcao_js=parent.js_mostrabancos|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe2','func_bancos.php?pesquisa_chave='+bancos+'&funcao_js=parent.js_mostrabancos1','Pesquisa',false);
  }
}
function js_mostrabancos(chave1,chave2){
  document.form1.d63_banco.value = chave1;
  document.form1.nome_banco.value = chave2;  
  db_iframe2.hide();
}
function js_mostrabancos1(chave,erro){
  document.form1.nome_banco.value = chave;
  if(erro==true){ 
    document.form1.d63_banco.focus(); 
    document.form1.d63_banco.value = ''; 
  }
}
</script>