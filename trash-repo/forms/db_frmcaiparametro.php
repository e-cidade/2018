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
$clcaiparametro->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nomeinst");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  
  <tr>
    <td nowrap title="<?=@$Tk29_boletimzerado?>">
       <?=@$Lk29_boletimzerado?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('k29_boletimzerado',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk29_modslipnormal?>">
       <?=@$Lk29_modslipnormal?>
    </td>
    <td> 
<?
$x = array('36'=>'Normal/2 partes','37'=>'Com assinaturas/1 parte');
db_select('k29_modslipnormal',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk29_modsliptransf?>">
       <?=@$Lk29_modsliptransf?>
    </td>
    <td> 
<?
$x = array('36'=>'Normal/2 partes','37'=>'2 partes/com assinatura');
db_select('k29_modsliptransf',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk29_chqduplicado?>">
       <?=@$Lk29_chqduplicado?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('k29_chqduplicado',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk29_chqemitidonaoautent?>">
       <?=@$Lk29_chqemitidonaoautent?>
    </td>
    <td> 
<?
db_inputdata('k29_chqemitidonaoautent',@$k29_chqemitidonaoautent_dia,@$k29_chqemitidonaoautent_mes,@$k29_chqemitidonaoautent_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk29_saldoemitechq?>">
       <?=@$Lk29_saldoemitechq?>
    </td>
    <td> 
<?
$x = array('1'=>'Sim','2'=>'N�o');
db_select('k29_saldoemitechq',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
   <tr>
    <td nowrap title="<?=@$Tk29_datasaldocontasextra?>">
       <?=@$Lk29_datasaldocontasextra?>
    </td>
    <td> 
<?
db_inputdata('k29_datasaldocontasextra',
             @$k29_datasaldocontasextra_dia,
             @$k29_datasaldocontasextra_mes,@$k29_datasaldocontasextra_ano,
             true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk29_trazdatacheque?>">
       <?=@$Lk29_trazdatacheque?>
    </td>
    <td> 
      <?
      $x = array('f'=>'N�o','t'=>'Sim');
      db_select('k29_trazdatacheque',$x,true,$db_opcao,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk29_contassemmovimento?>">
       <?=@$Lk29_contassemmovimento?>
    </td>
    <td> 
      <?
      $x = array('f'=>'N�o','t'=>'Sim');
      db_select('k29_contassemmovimento',$x,true,$db_opcao,"");
      ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisak29_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true);
  }else{
     if(document.form1.k29_instit.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.k29_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
     }else{
       document.form1.nomeinst.value = ''; 
     }
  }
}
function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave; 
  if(erro==true){ 
    document.form1.k29_instit.focus(); 
    document.form1.k29_instit.value = ''; 
  }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.k29_instit.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_caiparametro','func_caiparametro.php?funcao_js=parent.js_preenchepesquisa|k29_instit','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_caiparametro.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>