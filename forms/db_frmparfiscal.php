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

//MODULO: fiscal
$clparfiscal->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k00_descr");
$clrotulo->label("k01_descr");
$clrotulo->label("k02_descr");
$clrotulo->label("v03_descr");
$clrotulo->label("nomeinst");
$clrotulo->label("p51_descr");
?>
<form name="form1" method="post" action="">
<fieldset>
<legend><b>Parâmetros</b></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty32_instit?>">
       <?
       db_ancora(@$Ly32_instit,"js_pesquisay32_instit(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('y32_instit',10,$Iy32_instit,true,'text',3," onchange='js_pesquisay32_instit(false);'")
?>
       <?
db_input('nomeinst',50,$Inomeinst,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty32_tipo?>">
       <?
       db_ancora(@$Ly32_tipo,"js_pesquisay32_tipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y32_tipo',10,$Iy32_tipo,true,'text',$db_opcao," onchange='js_pesquisay32_tipo(false);'")
?>
       <?
db_input('k00_descr',50,$Ik00_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty32_hist?>">
       <?
       db_ancora(@$Ly32_hist,"js_pesquisay32_hist(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y32_hist',10,$Iy32_hist,true,'text',$db_opcao," onchange='js_pesquisay32_hist(false);'")
?>
       <?
db_input('k01_descr',50,$Ik01_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
    <tr>
    <td nowrap title="<?=@$Ty32_tipoprocpadrao?>">
       <?
       db_ancora(@$Ly32_tipoprocpadrao,"js_pesquisay32_tipoprocpadrao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y32_tipoprocpadrao',10,$Iy32_tipoprocpadrao,true,'text',$db_opcao," onchange='js_pesquisay32_tipoprocpadrao(false);'")
?>
       <?
db_input('p51_descr',50,$Ip51_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty32_receit?>">
       <?
       db_ancora(@$Ly32_receit,"js_pesquisay32_receit(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y32_receit',10,$Iy32_receit,true,'text',$db_opcao," onchange='js_pesquisay32_receit(false);'")
?>
       <?
db_input('k02_descr',50,$Ik02_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty32_receitexp?>">
       <?
         db_ancora(@$Ly32_receitexp,"js_pesquisay32_receitexp(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y32_receitexp',10,$Iy32_receitexp,true,'text',$db_opcao," onchange='js_pesquisay32_receitexp(false);'")
?>
       <?
db_input('k02_descrexp',50,$Ik02_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty32_proced?>">
       <?
       db_ancora(@$Ly32_proced,"js_pesquisay32_proced(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y32_proced',10,$Iy32_proced,true,'text',$db_opcao," onchange='js_pesquisay32_proced(false);'")
?>
       <?
db_input('v03_descr',50,$Iv03_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty32_procedexp?>">
       <?
         db_ancora(@$Ly32_procedexp,"js_pesquisay32_procedexp(true);",$db_opcao);
       ?>
    </td>
    <td> 
			<?
			  db_input('y32_procedexp',10,$Iy32_procedexp,true,'text',$db_opcao," onchange='js_pesquisay32_procedexp(false);'")
			?>
      <?
        db_input('v03_descrexp',50,$Iv03_descr,true,'text',3,'')
      ?>
    </td>
  </tr>
  
  <tr>
    <td nowrap title="<?=@$Ty32_impdatas?>">
       <?=@$Ly32_impdatas?>
    </td>
    <td> 
<?
$x = array('f'=>'Não','t'=>'Sim');
db_select('y32_impdatas',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty32_impcodativ?>">
       <?=@$Ly32_impcodativ?>
    </td>
    <td> 
<?
$x = array('f'=>'Não','t'=>'Sim');
db_select('y32_impcodativ',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty32_impobs?>">
       <?=@$Ly32_impobs?>
    </td>
    <td> 
<?
$x = array('f'=>'Não','t'=>'Sim');
db_select('y32_impobs',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty32_impobslanc?>">
       <?=@$Ly32_impobslanc?>
    </td>
    <td> 
<?
$x = array('f'=>'Não','t'=>'Sim');
db_select('y32_impobslanc',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty32_modalvara?>">
       <?=@$Ly32_modalvara?>
    </td>
    <td> 
<?
$x = array('1'=>'Metade A4','2'=>'A4');
db_select('y32_modalvara',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty32_modaidof?>">
       <b><?=@$Ly32_modaidof?></b>
    </td>
    <td> 
<?
$x = array('1'=>'AIDOF Padrão','2'=>'AIDOF sem Pedido');
db_select('y32_modaidof',$x,true,$db_opcao,"");
?>
    </td>
  </tr>  
  <tr>
    <td nowrap title="<?=@$Ty32_formvist?>">
       <?=@$Ly32_formvist?>
    </td>
    <td> 
<?
db_input('y32_formvist',10,$Iy32_formvist,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty32_sanidepto?>">
       <?=@$Ly32_sanidepto?>
    </td>
    <td> 
<?
$x = array('0'=>'Não','1'=>'Sim');
db_select('y32_sanidepto',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty32_sanbaixadiv?>">
       <?=@$Ly32_sanbaixadiv?>
    </td>
    <td> 
<?
$x = array('0'=>'Não','1'=>'Sim');
db_select('y32_sanbaixadiv',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
 <tr>
    <td nowrap title="<?=@$Ty32_calcvistanosanteriores?>">
       <?=@$Ly32_calcvistanosanteriores?>
    </td>
    <td> 
<?
$x = array('f'=>'Não','t'=>'Sim');
db_select('y32_calcvistanosanteriores',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty32_procprotbaixaauto?>">
      <?=@$Ly32_procprotbaixaauto?>
    </td>
    <td> 
			<?
			  $aProcProtBaixaAuto = array('1'=>'Sim','2'=>'Não');
			  db_select('y32_procprotbaixaauto',$aProcProtBaixaAuto,true,$db_opcao,"");
			?>
    </td>
  </tr>
  </table>
</fieldset>
<table align="center" border="0">
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
             type="submit" id="db_opcao" 
             value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
             <?=($db_botao==false?"disabled":"")?> >    
    </td>
    <td>
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    </td>
  </tr>
</table>
</form>
<script>
function js_pesquisay32_tipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_arretipo','func_arretipo.php?funcao_js=parent.js_mostraarretipo1|k00_tipo|k00_descr','Pesquisa',true);
  }else{
     if(document.form1.y32_tipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_arretipo','func_arretipo.php?pesquisa_chave='+document.form1.y32_tipo.value+'&funcao_js=parent.js_mostraarretipo','Pesquisa',false);
     }else{
       document.form1.k00_descr.value = ''; 
     }
  }
}
function js_mostraarretipo(chave,erro){
  document.form1.k00_descr.value = chave; 
  if(erro==true){ 
    document.form1.y32_tipo.focus(); 
    document.form1.y32_tipo.value = ''; 
  }
}
function js_mostraarretipo1(chave1,chave2){
  document.form1.y32_tipo.value = chave1;
  document.form1.k00_descr.value = chave2;
  db_iframe_arretipo.hide();
}
function js_pesquisay32_hist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_histcalc','func_histcalc.php?funcao_js=parent.js_mostrahistcalc1|k01_codigo|k01_descr','Pesquisa',true);
  }else{
     if(document.form1.y32_hist.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_histcalc','func_histcalc.php?pesquisa_chave='+document.form1.y32_hist.value+'&funcao_js=parent.js_mostrahistcalc','Pesquisa',false);
     }else{
       document.form1.k01_descr.value = ''; 
     }
  }
}
function js_mostrahistcalc(chave,erro){
  document.form1.k01_descr.value = chave; 
  if(erro==true){ 
    document.form1.y32_hist.focus(); 
    document.form1.y32_hist.value = ''; 
  }
}
function js_mostrahistcalc1(chave1,chave2){
  document.form1.y32_hist.value = chave1;
  document.form1.k01_descr.value = chave2;
  db_iframe_histcalc.hide();
}

function js_pesquisay32_receit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tabrec','func_tabrec.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_descr','Pesquisa',true);
  }else{
     if(document.form1.y32_receit.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tabrec','func_tabrec.php?pesquisa_chave='+document.form1.y32_receit.value+'&funcao_js=parent.js_mostratabrec','Pesquisa',false);
     }else{
       document.form1.k02_descr.value = ''; 
     }
  }
}
function js_mostratabrec(chave,erro){
  document.form1.k02_descr.value = chave; 
  if(erro==true){ 
    document.form1.y32_receit.focus(); 
    document.form1.y32_receit.value = ''; 
  }
}
function js_mostratabrec1(chave1,chave2){
  document.form1.y32_receit.value = chave1;
  document.form1.k02_descr.value = chave2;
  db_iframe_tabrec.hide();
}
function js_pesquisay32_proced(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_proced','func_proced.php?funcao_js=parent.js_mostraproced1|v03_codigo|v03_descr','Pesquisa',true);
  }else{
     if(document.form1.y32_proced.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_proced','func_proced.php?pesquisa_chave='+document.form1.y32_proced.value+'&funcao_js=parent.js_mostraproced','Pesquisa',false);
     }else{
       document.form1.v03_descr.value = ''; 
     }
  }
}
function js_mostraproced(chave,erro){
  document.form1.v03_descr.value = chave; 
  if(erro==true){ 
    document.form1.y32_proced.focus(); 
    document.form1.y32_proced.value = ''; 
  }
}
function js_mostraproced1(chave1,chave2){
  document.form1.y32_proced.value = chave1;
  document.form1.v03_descr.value = chave2;
  db_iframe_proced.hide();
}
function js_pesquisay32_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true);
  }else{
     if(document.form1.y32_instit.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.y32_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
     }else{
       document.form1.nomeinst.value = ''; 
     }
  }
}
function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave; 
  if(erro==true){ 
    document.form1.y32_instit.focus(); 
    document.form1.y32_instit.value = ''; 
  }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.y32_instit.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}
function js_pesquisay32_tipoprocpadrao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tipoproc','func_tipoproc.php?funcao_js=parent.js_mostratipoproc1|p51_codigo|p51_descr','Pesquisa',true);
  }else{
     if(document.form1.y32_tipoprocpadrao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tipoproc','func_tipoproc.php?pesquisa_chave='+document.form1.y32_tipoprocpadrao.value+'&funcao_js=parent.js_mostratipoproc','Pesquisa',false);
     }else{
       document.form1.p51_descr.value = ''; 
     }
  }
}
function js_mostratipoproc(chave,erro){
  document.form1.p51_descr.value = chave; 
  if(erro==true){ 
    document.form1.y32_tipoprocpadrao.focus(); 
    document.form1.y32_tipoprocpadrao.value = ''; 
  }
}
function js_mostratipoproc1(chave1,chave2){
  document.form1.y32_tipoprocpadrao.value = chave1;
  document.form1.p51_descr.value = chave2;
  db_iframe_tipoproc.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_parfiscal','func_parfiscal.php?funcao_js=parent.js_preenchepesquisa|y32_instit','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_parfiscal.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_pesquisay32_receitexp(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tabrecexp','func_tabrec.php?funcao_js=parent.js_mostratabrec1exp|k02_codigo|k02_descr','Pesquisa',true);
  }else{
     if(document.form1.y32_receit.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tabrecexp','func_tabrec.php?pesquisa_chave='+document.form1.y32_receitexp.value+'&funcao_js=parent.js_mostratabrecexp','Pesquisa',false);
     }else{
       document.form1.k02_descrexp.value = ''; 
     }
  }
}
function js_mostratabrecexp(chave,erro){
  document.form1.k02_descrexp.value = chave; 
  if(erro==true){ 
    document.form1.y32_receitexp.focus(); 
    document.form1.y32_receitexp.value = ''; 
  }
}
function js_mostratabrec1exp(chave1,chave2){
  document.form1.y32_receitexp.value = chave1;
  document.form1.k02_descrexp.value = chave2;
  db_iframe_tabrecexp.hide();
}

function js_pesquisay32_procedexp(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_procedexp','func_proced.php?funcao_js=parent.js_mostraproced1exp|v03_codigo|v03_descr','Pesquisa',true);
  }else{
     if(document.form1.y32_proced.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_procedexp','func_proced.php?pesquisa_chave='+document.form1.y32_procedexp.value+'&funcao_js=parent.js_mostraprocedexp','Pesquisa',false);
     }else{
       document.form1.v03_descrexp.value = ''; 
     }
  }
}
function js_mostraprocedexp(chave,erro){
  document.form1.v03_descrexp.value = chave; 
  if(erro==true){ 
    document.form1.y32_procedexp.focus(); 
    document.form1.y32_procedexp.value = ''; 
  }
}
function js_mostraproced1exp(chave1,chave2){
  document.form1.y32_procedexp.value = chave1;
  document.form1.v03_descrexp.value = chave2;
  db_iframe_procedexp.hide();
}
</script>