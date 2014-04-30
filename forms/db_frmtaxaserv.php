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

//MODULO: cemiterio
$cltaxaserv->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("i01_descr");
$clrotulo->label("k02_descr");
$clrotulo->label("dv09_descr");
$clrotulo->label("k00_descr");
$clrotulo->label("k01_descr");
?>
<form name="form1" method="post" action="">
<fieldset>
  <legend><b>Dados Taxa de Serviço</b></legend>
  <table border="0" align="center">
    <tr>
      <td nowrap title="<?=@$Tcm11_i_codigo?>">
        <?=@$Lcm11_i_codigo?>
      </td>
      <td> 
        <?
          db_input('cm11_i_codigo',10,$Icm11_i_codigo,true,'text',3,"")
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tcm11_c_descr?>">
        <?=@$Lcm11_c_descr?>
      </td>
      <td> 
        <?
          db_input('cm11_c_descr',30,$Icm11_c_descr,true,'text',$db_opcao,"")
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tcm11_d_datalimite?>">
        <?=@$Lcm11_d_datalimite?>
      </td>
      <td> 
        <?
          db_inputdata('cm11_d_datalimite',@$cm11_d_datalimite_dia,@$cm11_d_datalimite_mes,@$cm11_d_datalimite_ano,true,'text',$db_opcao,"")
        ?>
      </td>
    </tr>    
    
    <tr>
      <td nowrap title="<?=@$Tcm11_i_receita?>">
        <?
          db_ancora(@$Lcm11_i_receita,"js_pesquisacm11_i_receita(true);",$db_opcao);
        ?>
      </td>
      <td> 
        <?
          db_input('cm11_i_receita',10,$Icm11_i_receita,true,'text',$db_opcao," onchange='js_pesquisacm11_i_receita(false);'")
        ?>
        <?
          db_input('k02_descr',15,$Ik02_descr,true,'text',3,'')
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tcm11_i_proced?>">
        <?
          db_ancora(@$Lcm11_i_proced,"js_pesquisacm11_i_proced(true);",$db_opcao);
        ?>
      </td>
      <td> 
        <?
          db_input('cm11_i_proced',10,$Icm11_i_proced,true,'text',$db_opcao," onchange='js_pesquisacm11_i_proced(false);'")
        ?>
        <?
          db_input('dv09_descr',20,$Idv09_descr,true,'text',3,'')
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tcm11_i_historico?>">
        <?
          db_ancora(@$Lcm11_i_historico,"js_pesquisacm11_i_historico(true);",$db_opcao);
        ?>
      </td>
      <td> 
        <?
          db_input('cm11_i_historico',10,$Icm11_i_historico,true,'text',$db_opcao," onchange='js_pesquisacm11_i_historico(false);'")
        ?>
        <?
          db_input('k01_descr',20,$Ik01_descr,true,'text',3,'')
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tcm11_i_tipo?>">
        <?
          db_ancora(@$Lcm11_i_tipo,"js_pesquisacm11_i_tipo(true);",$db_opcao);
        ?>
      </td>
      <td> 
        <?
          db_input('cm11_i_tipo',10,$Icm11_i_tipo,true,'text',$db_opcao," onchange='js_pesquisacm11_i_tipo(false);'")
        ?>
        <?
          db_input('k00_descr',40,$Ik00_descr,true,'text',3,'')
        ?>
      </td>
    </tr>
  </table>
</fieldset>
  <table border="0" align="center">
    <tr>
      <td>
        <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" 
               id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
               <?=($db_botao==false?"disabled":"")?> >
      </td>
      <td>
        <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();"
               <?=($db_opcao==1?"disabled":"")?> >
      </td>
    </tr>    
  </table>
</form>
<script>
function js_pesquisacm11_c_inflator(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_inflan','func_inflan.php?funcao_js=parent.js_mostrainflan1|i01_codigo|i01_descr','Pesquisa',true,0);
  }else{
     if(document.form1.cm11_c_inflator.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_inflan','func_inflan.php?pesquisa_chave='+document.form1.cm11_c_inflator.value+'&funcao_js=parent.js_mostrainflan','Pesquisa',false,0);
     }else{
       document.form1.i01_descr.value = ''; 
     }
  }
}

function js_mostrainflan(chave,erro){
  document.form1.i01_descr.value = chave; 
  if(erro==true){ 
    document.form1.cm11_c_inflator.focus(); 
    document.form1.cm11_c_inflator.value = ''; 
  }
}

function js_mostrainflan1(chave1,chave2){
  document.form1.cm11_c_inflator.value = chave1;
  document.form1.i01_descr.value = chave2;
  db_iframe_inflan.hide();
}

function js_pesquisacm11_i_receita(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tabrec','func_tabrec.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_descr','Pesquisa',true,0);
  }else{
     if(document.form1.cm11_i_receita.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_tabrec','func_tabrec.php?pesquisa_chave='+document.form1.cm11_i_receita.value+'&funcao_js=parent.js_mostratabrec','Pesquisa',false,0);
     }else{
       document.form1.k02_descr.value = ''; 
     }
  }
}

function js_mostratabrec(chave,erro){
  document.form1.k02_descr.value = chave; 
  if(erro==true){ 
    document.form1.cm11_i_receita.focus(); 
    document.form1.cm11_i_receita.value = ''; 
  }
}

function js_mostratabrec1(chave1,chave2){
  document.form1.cm11_i_receita.value = chave1;
  document.form1.k02_descr.value = chave2;
  db_iframe_tabrec.hide();
}

function js_pesquisacm11_i_proced(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_proced','func_procdiver.php?funcao_js=parent.js_mostraproced1|dv09_procdiver|dv09_descr','Pesquisa',true,0);
  }else{
     if(document.form1.cm11_i_proced.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_proced','func_procdiver.php?pesquisa_chave='+document.form1.cm11_i_proced.value+'&funcao_js=parent.js_mostraproced','Pesquisa',false,0);
     }else{
       document.form1.dv09_descr.value = ''; 
     }
  }
}

function js_mostraproced(chave,erro){
  document.form1.dv09_descr.value = chave; 
  if(erro==true){ 
    document.form1.cm11_i_proced.focus(); 
    document.form1.cm11_i_proced.value = ''; 
  }
}
function js_mostraproced1(chave1,chave2){
  document.form1.cm11_i_proced.value = chave1;
  document.form1.dv09_descr.value = chave2;
  db_iframe_proced.hide();
}

function js_pesquisacm11_i_tipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_arretipo','func_arretipo.php?funcao_js=parent.js_mostraarretipo1|k00_tipo|k00_descr','Pesquisa',true,0);
  }else{
     if(document.form1.cm11_i_tipo.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_arretipo','func_arretipo.php?pesquisa_chave='+document.form1.cm11_i_tipo.value+'&funcao_js=parent.js_mostraarretipo','Pesquisa',false,0);
     }else{
       document.form1.k00_descr.value = ''; 
     }
  }
}

function js_mostraarretipo(chave,erro){
  document.form1.k00_descr.value = chave; 
  if(erro==true){ 
    document.form1.cm11_i_tipo.focus(); 
    document.form1.cm11_i_tipo.value = ''; 
  }
}

function js_mostraarretipo1(chave1,chave2){
  document.form1.cm11_i_tipo.value = chave1;
  document.form1.k00_descr.value = chave2;
  db_iframe_arretipo.hide();
}

function js_pesquisacm11_i_historico(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_histcalc','func_histcalc.php?funcao_js=parent.js_mostrahistcalc1|k01_codigo|k01_descr','Pesquisa',true,0);
  }else{
     if(document.form1.cm11_i_historico.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_histcalc','func_histcalc.php?pesquisa_chave='+document.form1.cm11_i_historico.value+'&funcao_js=parent.js_mostrahistcalc','Pesquisa',false,0);
     }else{
       document.form1.k01_descr.value = ''; 
     }
  }
}

function js_mostrahistcalc(chave,erro){
  document.form1.k01_descr.value = chave; 
  if(erro==true){ 
    document.form1.cm11_i_historico.focus(); 
    document.form1.cm11_i_historico.value = ''; 
  }
}

function js_mostrahistcalc1(chave1,chave2){
  document.form1.cm11_i_historico.value = chave1;
  document.form1.k01_descr.value = chave2;
  db_iframe_histcalc.hide();
}

function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_taxaserv','func_taxaserv.php?funcao_js=parent.js_preenchepesquisa|cm11_i_codigo','Pesquisa',true,0);
}

function js_preenchepesquisa(chave){
  db_iframe_taxaserv.hide();
  <?
    if($db_opcao!=1){
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
  ?>
}
</script>