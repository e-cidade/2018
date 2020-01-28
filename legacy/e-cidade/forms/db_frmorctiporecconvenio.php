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

//MODULO: orcamento
$clorctiporecconvenio->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o15_descr");
$clrotulo->label("o108_numcgm");
$clrotulo->label("o100_descricao");
$clrotulo->label("o29_descricao");
$clrotulo->label("z01_nome");
$clrotulo->label("k13_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To16_sequencial?>">
       <?=@$Lo16_sequencial?>
    </td>
    <td> 
<?
db_input('o16_sequencial',10,$Io16_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To16_orctiporec?>">
       <?
       db_ancora(@$Lo16_orctiporec,"js_pesquisao16_orctiporec(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o16_orctiporec',10,$Io16_orctiporec,true,'text',$db_opcao," onchange='js_pesquisao16_orctiporec(false);'")
?>
       <?
db_input('o15_descr',50,$Io15_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To16_percentual?>">
       <?=@$Lo16_percentual?>
    </td>
    <td> 
<?
db_input('o16_percentual',10,$Io16_percentual,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To16_dtassinatura?>">
       <?=@$Lo16_dtassinatura?>
    </td>
    <td> 
<?
db_inputdata('o16_dtassinatura',@$o16_dtassinatura_dia,@$o16_dtassinatura_mes,@$o16_dtassinatura_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To16_dtvigenciaini?>">
       <?=@$Lo16_dtvigenciaini?>
    </td>
    <td> 
<?
db_inputdata('o16_dtvigenciaini',@$o16_dtvigenciaini_dia,@$o16_dtvigenciaini_mes,@$o16_dtvigenciaini_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To16_dtvigenciafim?>">
       <?=@$Lo16_dtvigenciafim?>
    </td>
    <td> 
<?
db_inputdata('o16_dtvigenciafim',@$o16_dtvigenciafim_dia,@$o16_dtvigenciafim_mes,@$o16_dtvigenciafim_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To16_dtprestacaoini?>">
       <?=@$Lo16_dtprestacaoini?>
    </td>
    <td> 
<?
db_inputdata('o16_dtprestacaoini',@$o16_dtprestacaoini_dia,@$o16_dtprestacaoini_mes,@$o16_dtprestacaoini_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To16_dtprestacaofim?>">
       <?=@$Lo16_dtprestacaofim?>
    </td>
    <td> 
<?
db_inputdata('o16_dtprestacaofim',@$o16_dtprestacaofim_dia,@$o16_dtprestacaofim_mes,@$o16_dtprestacaofim_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To16_dtprorrogacaoini?>">
       <?=@$Lo16_dtprorrogacaoini?>
    </td>
    <td> 
<?
db_inputdata('o16_dtprorrogacaoini',@$o16_dtprorrogacaoini_dia,@$o16_dtprorrogacaoini_mes,@$o16_dtprorrogacaoini_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To16_dtprorrogacaofim?>">
       <?=@$Lo16_dtprorrogacaofim?>
    </td>
    <td> 
<?
db_inputdata('o16_dtprorrogacaofim',@$o16_dtprorrogacaofim_dia,@$o16_dtprorrogacaofim_mes,@$o16_dtprorrogacaofim_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To16_convenio?>">
       <?=@$Lo16_convenio?>
    </td>
    <td> 
<?
db_input('o16_convenio',10,$Io16_convenio,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To16_observacao?>">
       <?=@$Lo16_observacao?>
    </td>
    <td> 
<?
db_textarea('o16_observacao',5,60,$Io16_observacao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To16_objeto?>">
       <?=@$Lo16_objeto?>
    </td>
    <td> 
<?
db_textarea('o16_objeto',5,60,$Io16_objeto,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To16_valor?>">
       <?=@$Lo16_valor?>
    </td>
    <td> 
<?
db_input('o16_valor',10,$Io16_valor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To16_saldoaberturacp?>">
       <?=@$Lo16_saldoaberturacp?>
    </td>
    <td> 
<?
db_input('o16_saldoaberturacp',10,$Io16_saldoaberturacp,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To16_saldoabertura?>">
       <?=@$Lo16_saldoabertura?>
    </td>
    <td> 
<?
db_input('o16_saldoabertura',10,$Io16_saldoabertura,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To16_saltes?>">
       <?
       db_ancora(@$Lo16_saltes,"js_pesquisao16_saltes(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o16_saltes',10,$Io16_saltes,true,'text',$db_opcao," onchange='js_pesquisao16_saltes(false);'")
?>
       <?
db_input('k13_descr',50,$Ik13_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To16_tipopacto?>">
       <?
       db_ancora(@$Lo16_tipopacto,"js_pesquisao16_tipopacto(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o16_tipopacto',10,$Io16_tipopacto,true,'text',$db_opcao," onchange='js_pesquisao16_tipopacto(false);'")
?>
       <?
db_input('o29_descricao',50,$Io29_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To16_orctiporecconveniosituacao?>">
       <?
       db_ancora(@$Lo16_orctiporecconveniosituacao,"js_pesquisao16_orctiporecconveniosituacao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o16_orctiporecconveniosituacao',10,$Io16_orctiporecconveniosituacao,true,'text',$db_opcao," onchange='js_pesquisao16_orctiporecconveniosituacao(false);'")
?>
       <?
db_input('o100_descricao',50,$Io100_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To16_concedente?>">
       <?
       db_ancora(@$Lo16_concedente,"js_pesquisao16_concedente(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o16_concedente',10,$Io16_concedente,true,'text',$db_opcao," onchange='js_pesquisao16_concedente(false);'")
?>
       <?
db_input('z01_nome',50,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisao16_orctiporec(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orctiporec','func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1|o15_codigo|o15_descr','Pesquisa',true);
  }else{
     if(document.form1.o16_orctiporec.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orctiporec','func_orctiporec.php?pesquisa_chave='+document.form1.o16_orctiporec.value+'&funcao_js=parent.js_mostraorctiporec','Pesquisa',true);
     }else{
       document.form1.o15_descr.value = ''; 
     }
  }
}
function js_mostraorctiporec(chave,erro){
  document.form1.o15_descr.value = chave; 
  if(erro==true){ 
    document.form1.o16_orctiporec.focus(); 
    document.form1.o16_orctiporec.value = ''; 
  }
}
function js_mostraorctiporec1(chave1,chave2){
  document.form1.o16_orctiporec.value = chave1;
  document.form1.o15_descr.value = chave2;
  db_iframe_orctiporec.hide();
}
function js_pesquisao16_concedente(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_concedente','func_concedente.php?funcao_js=parent.js_mostraconcedente1|o108_sequencial|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.o16_concedente.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_concedente','func_concedente.php?pesquisa_chave='+document.form1.o16_concedente.value+'&funcao_js=parent.js_mostraconcedente','Pesquisa',false);
     }else{
       document.form1.o108_numcgm.value = ''; 
     }
  }
}
function js_mostraconcedente(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.o16_concedente.focus(); 
    document.form1.o16_concedente.value = ''; 
  }
}
function js_mostraconcedente1(chave1,chave2){
  document.form1.o16_concedente.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_concedente.hide();
}
function js_pesquisao16_orctiporecconveniosituacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orctiporecconveniosituacao','func_orctiporecconveniosituacao.php?funcao_js=parent.js_mostraorctiporecconveniosituacao1|o100_sequencial|o100_descricao','Pesquisa',true);
  }else{
     if(document.form1.o16_orctiporecconveniosituacao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orctiporecconveniosituacao','func_orctiporecconveniosituacao.php?pesquisa_chave='+document.form1.o16_orctiporecconveniosituacao.value+'&funcao_js=parent.js_mostraorctiporecconveniosituacao','Pesquisa',false);
     }else{
       document.form1.o100_descricao.value = ''; 
     }
  }
}
function js_mostraorctiporecconveniosituacao(chave,erro){
  document.form1.o100_descricao.value = chave; 
  if(erro==true){ 
    document.form1.o16_orctiporecconveniosituacao.focus(); 
    document.form1.o16_orctiporecconveniosituacao.value = ''; 
  }
}
function js_mostraorctiporecconveniosituacao1(chave1,chave2){
  document.form1.o16_orctiporecconveniosituacao.value = chave1;
  document.form1.o100_descricao.value = chave2;
  db_iframe_orctiporecconveniosituacao.hide();
}
function js_pesquisao16_tipopacto(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tipopacto','func_tipopacto.php?funcao_js=parent.js_mostratipopacto1|o29_sequencial|o29_descricao','Pesquisa',true);
  }else{
     if(document.form1.o16_tipopacto.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tipopacto','func_tipopacto.php?pesquisa_chave='+document.form1.o16_tipopacto.value+'&funcao_js=parent.js_mostratipopacto','Pesquisa',false);
     }else{
       document.form1.o29_descricao.value = ''; 
     }
  }
}
function js_mostratipopacto(chave,erro){
  document.form1.o29_descricao.value = chave; 
  if(erro==true){ 
    document.form1.o16_tipopacto.focus(); 
    document.form1.o16_tipopacto.value = ''; 
  }
}
function js_mostratipopacto1(chave1,chave2){
  document.form1.o16_tipopacto.value = chave1;
  document.form1.o29_descricao.value = chave2;
  db_iframe_tipopacto.hide();
}
function js_pesquisao16_saltes(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_saltes','func_saltes.php?funcao_js=parent.js_mostrasaltes1|k13_conta|k13_descr','Pesquisa',true);
  }else{
     if(document.form1.o16_saltes.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_saltes','func_saltes.php?pesquisa_chave='+document.form1.o16_saltes.value+'&funcao_js=parent.js_mostrasaltes','Pesquisa',false);
     }else{
       document.form1.k13_descr.value = ''; 
     }
  }
}
function js_mostrasaltes(chave,erro){
  document.form1.k13_descr.value = chave; 
  if(erro==true){ 
    document.form1.o16_saltes.focus(); 
    document.form1.o16_saltes.value = ''; 
  }
}
function js_mostrasaltes1(chave1,chave2){
  document.form1.o16_saltes.value = chave1;
  document.form1.k13_descr.value = chave2;
  db_iframe_saltes.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_orctiporecconvenio','func_orctiporecconvenio.php?funcao_js=parent.js_preenchepesquisa|o16_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_orctiporecconvenio.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>