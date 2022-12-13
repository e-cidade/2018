<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

//MODULO: contabilidade
$clcontcearquivo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nomeinst");
$clrotulo->label("c10_nome");
if($db_opcao==1){
  $db_action="con1_contcearquivo004.php";
}else if($db_opcao==2||$db_opcao==22){
  $db_action="con1_contcearquivo005.php";
}else if($db_opcao==3||$db_opcao==33){  
  $db_action="con1_contcearquivo006.php";
} 

if ($db_opcao == 1) {
  $rsInstit   = $oInstit->sql_record($oInstit->sql_query_file(db_getsession('DB_instit'),'codigo,nomeinst'));
  $oDados     = db_utils::fieldsMemory($rsInstit,0);
  $c11_instit =  $oDados->codigo;
  $nomeinst   =  $oDados->nomeinst;
  
  $c11_datageracao_ano = date('Y',db_getsession('DB_datausu'));
  $c11_datageracao_mes = date('m',db_getsession('DB_datausu'));
  $c11_datageracao_dia = date('d',db_getsession('DB_datausu'));
  $c11_datageracao     = date('d/m/Y',db_getsession('DB_datausu'));
  
}
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<br><br>

<table>
<tr>
<td>
<fieldset>
  <legend>
    <b>Filtros para Geração</b>
  </legend>
<table border="0">
  <tr>  
    <td> 
    <?
    db_input('c11_sequencial',10,$Ic11_sequencial,true,'hidden',3,"")
    ?>
    </td>
  </tr>
  <tr height="21">
    <td nowrap title="<?=@$Tc11_concadtce?>">
       <?
       db_ancora(@$Lc11_concadtce,"js_pesquisac11_concadtce(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('c11_concadtce',10,$Ic11_concadtce,true,'text',$db_opcao," onchange='js_pesquisac11_concadtce(false);'")
?>
       <?
db_input('c10_nome',52,$Ic10_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr height="21">
    <td nowrap title="<?=@$Tc11_instit?>">
       <?
       db_ancora(@$Lc11_instit,"js_pesquisac11_instit(true);",3);
       ?>
    </td>
    <td> 
<?


db_input('c11_instit',10,$Ic11_instit,true,'text',3," onchange='js_pesquisac11_instit(false);'")
?>
       <?
db_input('nomeinst',52,$Inomeinst,true,'text',3,'')
       ?>
    </td>
  </tr>
  
    <tr height="21">
    <td nowrap title="<?=@$Tc11_datageracao?>">
       <?=@$Lc11_datageracao?>
    </td>
    <td> 
<?
db_inputdata('c11_datageracao',@$c11_datageracao_dia,@$c11_datageracao_mes,@$c11_datageracao_ano,true,'text',3,"")
?>
    </td>
  </tr>  
  
  <tr height="21">
    <td nowrap title="<?=@$Tc11_dataini?>">
       <?=@$Lc11_dataini?>
    </td>
    <td> 
<?
db_inputdata('c11_dataini',@$c11_dataini_dia,@$c11_dataini_mes,@$c11_dataini_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr height="21">
    <td nowrap title="<?=@$Tc11_datafim?>">
       <?=@$Lc11_datafim?>
    </td>
    <td> 
<?
db_inputdata('c11_datafim',@$c11_datafim_dia,@$c11_datafim_mes,@$c11_datafim_ano,true,'text',$db_opcao,"")
?>
    </td>    
  </tr>
  <tr height="21">
    <td nowrap title="<?=@$Tc11_diapagtofolha?>">
       <?=@$Lc11_diapagtofolha?>
    </td>
    <td> 
<?
db_input('c11_diapagtofolha',10,$Ic11_diapagtofolha,true,'text',$db_opcao,'')
?>
    </td>    
  </tr>
  <tr height="21">
    <td nowrap title="<?=@$Tc11_codigoremessa?>">
       <?=@$Lc11_codigoremessa?>
    </td>
    <td> 
    <?
      db_input('c11_codigoremessa',10,$Ic11_codigoremessa,true,'text',$db_opcao,'')
    ?>
    </td>    
  </tr>
  </table>
  <fieldset>
  <legend>
    <b>Informações Adicionais Leiame</b>
  </legend>
  <table>
  <tr>
    <td> 
    <?
      db_textarea('c11_infleiame',10,83,$Ic11_infleiame,true,'text',$db_opcao,"")
    ?>
    </td>
  </tr>
  </fieldset>
  </table>
  
 
  </td>
  </tr>
  </table>
  
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick = "return js_valida();" >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<?
  if ( $db_opcao==1 ) {
    echo "<input name='importar' type='button' id='importar' value='Importar' onclick='js_importar();' >";
  }
?>
</form>
<script>

/**
 * valida antes de colar no campo valor
 */

$('c11_concadtce').onpaste = function(event) {
  return /^[0-9|.]+$/.test(event.clipboardData.getData('text/plain'));
}

$('c11_codigoremessa').onpaste = function(event) {
  return /^[0-9|.]+$/.test(event.clipboardData.getData('text/plain'));
} 

$('c11_diapagtofolha').onpaste = function(event) {
  return /^[0-9|.]+$/.test(event.clipboardData.getData('text/plain'));
}  

$('c11_dataini').onpaste = function(event) {
  return /^[0-9|.\/]+$/.test(event.clipboardData.getData('text/plain'));
}  

$('c11_datafim').onpaste = function(event) {
  return /^[0-9|.\/]+$/.test(event.clipboardData.getData('text/plain'));
} 


function js_valida() {

  var codtribu   = $F('c11_concadtce');
  var dataini    = $F('c11_dataini');
  var datafim    = $F('c11_datafim');
  var diafolha   = $F('c11_diapagtofolha');
  var codremessa = $F('c11_codigoremessa');

  if (codtribu == '') {
    alert("Campo Código do Tribunal de Contas é de preenchimento obrigatório.");
    return false;
  }
 
  if (dataini == '') {
    alert("Campo Data Inicial é de preenchimento obrigatório.");
    return false;
  }

  if (datafim == '') {
    alert("Campo Data Final é de preenchimento obrigatório.");
    return false;
  }

  if (diafolha == '') {
    alert("Campo Dia de Pagamento da Folha é de preenchimento obrigatório.");
    return false;
  }

  if (codremessa == '') {
    alert("Campo Código da Remessa do Lote é de preenchimento obrigatório.");
    return false;
  }

  if (new Number(diafolha) > 31 || new Number(diafolha) < 1) {
    alert("Não é possível informar dia menor que 01 e maior que 31 no campo Dia de Pagamento da Folha.");
    $('c11_diapagtofolha').value = '';
    $('c11_diapagtofolha').focus();
    return false;
  }

  return true;
}

function js_processar() {

  var dataini  = document.form1.datainicial.value;
  var datafim  = document.form1.datafinal.value;
  var diafolha = document.form1.diapagfolha.value;
  var remessa  = document.form1.remessa.value;
  
  if (dataini == '') {
    alert("Preencha a data inicial !");
    return false;
  }
  if (datafim == '') {
    alert("Preencha a data final !");
    return false;
  }
  if (diafolha == '') {
    alert("Preencha o dia de pagamento da folha !");
    return false;
  }
  if (remessa == '') {
    alert("Preencha o código da remessa do arquivo !");
    return false;
  }  
  if (new Number(diafolha) > 31 || new Number(diafolha) < 1) {
    alert("Dia de pagamento deve ser um dia entre 1 e 31 !");
    return false;
  }
  
  document.form1.submit();
  
}

function js_importar() {
  js_OpenJanelaIframe('top.corpo.iframe_contcearquivo','db_iframe_contcearquivo','func_contcearquivo.php?funcao_js=parent.js_importarRegistros|c11_sequencial','Pesquisa',true,0);
}

function js_importarRegistros(codigo) {
  if (codigo != '') {
    db_iframe_contcearquivo.hide();
    if (confirm('Deseja realmente importar os registros?')) {
      location.href = 'con1_contcearquivo004.php?importar=true&icodigonovo='+codigo;
    }
  }
  
}

function js_pesquisac11_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_contcearquivo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true,0);
  }else{
     if(document.form1.c11_instit.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_contcearquivo','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.c11_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false,0);
     }else{
       document.form1.nomeinst.value = ''; 
     }
  }
}
function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave; 
  if(erro==true){ 
    document.form1.c11_instit.focus(); 
    document.form1.c11_instit.value = ''; 
  }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.c11_instit.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}
function js_pesquisac11_concadtce(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_contcearquivo','db_iframe_concadtce','func_concadtce.php?funcao_js=parent.js_mostraconcadtce1|c10_sequencial|c10_nome','Pesquisa',true,0);
  }else{
     if(document.form1.c11_concadtce.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_contcearquivo','db_iframe_concadtce','func_concadtce.php?pesquisa_chave='+document.form1.c11_concadtce.value+'&funcao_js=parent.js_mostraconcadtce','Pesquisa',false);
     }else{
       document.form1.c10_nome.value = ''; 
     }
  }
}
function js_mostraconcadtce(chave,erro){
  document.form1.c10_nome.value = chave; 
  if(erro==true){ 
    document.form1.c11_concadtce.focus(); 
    document.form1.c11_concadtce.value = ''; 
  }
}
function js_mostraconcadtce1(chave1,chave2){
  document.form1.c11_concadtce.value = chave1;
  document.form1.c10_nome.value = chave2;
  db_iframe_concadtce.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_contcearquivo','db_iframe_contcearquivo','func_contcearquivo.php?funcao_js=parent.js_preenchepesquisa|c11_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_contcearquivo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>