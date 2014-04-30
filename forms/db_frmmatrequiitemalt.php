<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_matparam_classe.php");
require_once("classes/db_db_departorg_classe.php");
require_once("classes/db_db_almoxdepto_classe.php");
require_once("classes/db_matestoque_classe.php");
require_once("classes/db_matunid_classe.php");
require_once("classes/db_matmater_classe.php");
require_once("classes/db_matmaterunisai_classe.php");

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clmatparam               = new cl_matparam;
$cldb_departorg           = new cl_db_departorg;
$clmatestoque             = new cl_matestoque;
$cldb_almoxdepto          = new cl_db_almoxdepto;
$clmatunid                = new cl_matunid;
$clmatmater               = new cl_matmater;
$clmatmaterunisai         = new cl_matmaterunisai;
$clrotulo                 = new rotulocampo;
$rsParam                  = $clmatparam->sql_record($clmatparam->sql_query_file());
$oParam                   = db_utils::fieldsMemory($rsParam,0);
$tobserva                 = $oParam->m90_modrelsaidamat;
$sVerSaldoItem            = $oParam->m90_versaldoitemreq;

/**
 * Verifica se a opção Visualizar Saldo do Item na Requisição
 * está habilitada ou não. Caso esteja definida como NÃO a linha
 * não será exibida
 */
$sVerTrQuantidade = "";

if ($sVerSaldoItem != "t") {  
  $sVerTrQuantidade = " style=\"display:none;\"";
}


if (isset($m40_codigo)&&trim($m40_codigo)!=""){

	$res_matrequi = $clmatrequi->sql_record($clmatrequi->sql_query_file($m40_codigo,"m40_auto"));

	if ($clmatrequi->numrows > 0){

		db_fieldsmemory($res_matrequi,0);

		if ($m40_auto == "t"){
			$db_opcao = 3;
		}
	}
}

$db_botao = true;

$clrotulo->label("m60_descr");
$clrotulo->label("m60_codmater");
$clrotulo->label("m40_codigo");
$clrotulo->label("m80_codigo");
$clrotulo->label("m41_codigo");
$clrotulo->label("m41_quant");
$clrotulo->label("m41_obs");
$clrotulo->label("cc08_sequencial"); 
if(isset($opcao) && $opcao=="alterar"){
  $db_opcao = 2;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
  $db_opcao = 3;
}else{  
  $db_opcao = 1;
} 


if (isset($db_opcao)&&trim($db_opcao)==4){
     $db_opcao = 3;
}

if ( !empty($m40_codigo) ) {

  $result_atend = $clmatrequiitem->sql_record($clmatrequiitem->sql_query_atend(null,'*',null,"m41_codmatrequi=$m40_codigo and (m43_codigo is not null OR m102_sequencial is not null)"));
  if ($clmatrequiitem->numrows!=0){
    $atend=true;
    $db_botao=false;
  }
}

if ( !empty($m41_codmatmater) && $db_opcao == 1 && !isset($incluir) ) {

  $result_mat = $clmatrequiitem->sql_record($clmatrequiitem->sql_query(null,'*',null,"m41_codmatrequi = $m40_codigo and m41_codmatmater = $m41_codmatmater "));
  if ($clmatrequiitem->numrows>0){
    db_msgbox("Material já incluido nesta requisição!!");
    $m41_codmatmater = "";
    $m60_descr = "";
  }
}
?>
<form name="form1" method="post" action="<?=basename($_SERVER['PHP_SELF'])?>" >
<fieldset style="width: 90%" ><legend><b>Dados da requisição</b></legend>
<table border="0" cellspacing="0" cellpadding="0"> <tr>
<td nowrap title="<?=@$Tm40_codigo?>">
<b>Requisição: </b> 
<?//=@$Lm40_codigo?>
</td>
<td> 
<?
db_input('m40_codigo',10,$Im40_codigo,true,'text',3,"");
$m41_codigo=@$m41_codigo;
db_input('m41_codigo',10,$Im41_codigo,true,'hidden',3,"");
db_input('m80_codigo',10,$Im80_codigo,true,'hidden',3,"");
?>
</td>
</tr>
<tr>
<td nowrap title="<?=@$Tm60_codmater?>">
<?db_ancora(@$Lm60_codmater,"js_pesquisa_codmater(true);",$db_opcao);?>
</td>
<td> 
<?
db_input('m41_codmatmater',10,$Im60_codmater,true,'text',$db_opcao,"onchange='js_pesquisa_codmater(false);'");
db_input('m60_descr',40,$Im60_descr,true,'text',3,"");
?>
<script type="text/javascript">
  document.form1.m41_codmatmater.focus(); 
</script>
</td>
</tr>

<tr>
<td title=<?=@$Tm41_quant?>>
<?=@$Lm41_quant?>
</td>
<td>
<?
db_input('m41_quant',6,$Im41_quant,true,'text',$db_opcao,"");
?>
<?if (isset($m41_codmatmater)&&$m41_codmatmater!=""){?>
  <b>Unid. Saída:</b>
  
  <?$result_unisai=$clmatmaterunisai->sql_record($clmatmaterunisai->sql_query($m41_codmatmater));
  if ($clmatmaterunisai->numrows>0){
    db_fieldsmemory($result_unisai,0);
    $unisai=$m61_abrev;
    $codunid=$m62_codmatunid;
  }
  db_input('codunid',10,'',true,'hidden',3,"");
  db_input('unisai',10,'',true,'text',3,""); ?>
  
<?
}
db_input('m40_almox',10,'',true,'hidden',3,"");
?>
</td>
</tr>
<?
$testquan='f';
if (isset($m41_codmatmater)&&$m41_codmatmater!=""){
  $codmater=$m41_codmatmater;
  $result_param=$clmatparam->sql_record($clmatparam->sql_query_file());
  if ($clmatparam->numrows){
    db_fieldsmemory($result_param,0);
    if ($m90_reqsemest=='t'){
      $testquan='t';
    }
  }    
  $depto_almox = "";
  $result_deptoalmox = $cldb_almox->sql_record($cldb_almox->sql_query_file($m40_almox, "m91_depto"));
  if ($cldb_almox->numrows>0){
    db_fieldsmemory($result_deptoalmox,0);
    $depto_almox = $m91_depto; 
  }
  $result_matestoque=$clmatestoque->sql_record($clmatestoque->sql_query_almox(null,"sum(m70_valor)as vlrtot,sum(m70_quant)as quantot",null,"m70_codmatmater=$codmater and m70_coddepto = $depto_almox", "", "", $depto_almox));
  if ($clmatestoque->numrows!=0){
    db_fieldsmemory($result_matestoque,0);  
  }else{
  }
  
  if ($iTipoControleCustos > 0) {

          echo "<tr><td>";
          db_ancora("<b>Centro de de Custo:",'js_adicionaCentroCusto()', 1);
          echo "</td><td>";
          db_input('cc08_sequencial',10,$Icc08_sequencial,true,"text", 3);
          db_input('cc08_descricao',40,$Im60_descr,true,"text",3);
          echo "</td></tr>" ;
                           
        }
        ?>
  <tr <?=$sVerTrQuantidade;?>>
  <td title='Quant. Disponível' >
  <b>Quantidade Disponível:</b>
  </td>
  <td>
  <?
  if (isset($quantot)&&($quantot!="")){
    $quant_disp=$quantot;
  }else{
    $quant_disp='0';
  }
  db_input('quant_disp',10,$Im41_quant,true,'text',3,"");
  ?>
  </td>
  </tr>
<?}?>
<tr>
<td nowrap title="<?=@$Tm41_obs?>">
<?=@$Lm41_obs?>
</td>
<td> 
<?
db_textarea('m41_obs',0,50,$Im41_obs,true,'text',$db_opcao,"")
?>
</td>
<? if ($db_opcao==1||$db_opcao==2){?>
      <tr style='display: none'>
        <td>
            <strong>Observações:</strong>   

        </td>
         <td>
         <?
          $aObs = array(
                        18 => "Resumida",
                        181 => "Completa",
                       );
          db_select("tobserva",$aObs,true,1);
         ?>
         </td>

      </tr>
<?}?>
</tr>
<tr>
<td colspan=2 align=center>
<?
if(!isset($opcao) && isset($db_opcao) && $db_opcao==3){
  $db_botao=false;    
}
?>
</table>
</fieldset>

<table>
<tr>
<td valign="top"> 
<?
$chavepri= array("m40_codigo"=>@$m40_codigo,"m41_codigo"=>@$m41_codigo,"m41_obs"=>@$m41_obs,"m41_quant"=>@$m41_quant,"m41_codunid"=>@$m41_codunid,"m61_descr"=>@$m61_descr,"m41_codmatmater"=>@$m41_codmatmater,"m60_descr"=>@$m60_descr);
$cliframe_alterar_excluir->chavepri=$chavepri;
if (isset($m40_codigo)&&@$m40_codigo!=""){
  $cliframe_alterar_excluir->sql = $clmatrequiitem->sql_query(null,'*',null,"m41_codmatrequi=$m40_codigo");
  $cliframe_alterar_excluir->sql_disabled = $clmatrequiitem->sql_query_atend(null,'*',null,"m41_codmatrequi=$m40_codigo and (m43_codigo is not null )");
}
$cliframe_alterar_excluir->campos  ="m41_codmatmater,m60_descr,m41_quant,m61_descr,m41_obs";
$cliframe_alterar_excluir->legenda="ITENS REQUISITADOS";
$cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
$cliframe_alterar_excluir->textocabec ="darkblue";
$cliframe_alterar_excluir->textocorpo ="black";
$cliframe_alterar_excluir->fundocabec ="#aacccc";
$cliframe_alterar_excluir->fundocorpo ="#ccddcc";
$cliframe_alterar_excluir->iframe_width ="710";
$cliframe_alterar_excluir->iframe_height ="130";
$lib=1;
if ($db_opcao==3||$db_opcao==33){
  $lib=4;
}
$cliframe_alterar_excluir->opcoes = @$lib;
$cliframe_alterar_excluir->iframe_alterar_excluir(@$db_opcao);   
db_input('db_opcao',10,'',true,'hidden',3);
?>
</td>
</tr>
</table>

<table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> <?=(($db_opcao==1||$db_opcao==2||$db_opcao==22)&&$testquan=='f'?"onclick='return js_testaquant();'":"")?>  >
<? if ($db_opcao==1||$db_opcao==2){?>
  <input name='pesquisar' type='button' value='Emite Requisição' onclick='js_abre();'>
<?}?>
</td>
</tr>
</table>

</form>
<script>
function js_abre(){
  obj = document.form1;
  query='';
  query += "&ini="+obj.m40_codigo.value;
  query += "&fim="+obj.m40_codigo.value;
  query += "&tObserva="+obj.tobserva.value;
  query += "&departamento=<?=db_getsession("DB_coddepto")?>";
  jan = window.open('mat2_matrequi002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
function js_pesquisa_codmater(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_mater','func_matmater.php?funcao_js=parent.js_mostra1|m60_codmater|m60_descr','Pesquisa',true);
  }else{
    if(document.form1.m41_codmatmater.value != ''){ 
      js_OpenJanelaIframe('','db_iframe_mater','func_matmater.php?pesquisa_chave='+document.form1.m41_codmatmater.value+'&funcao_js=parent.js_mostra','Pesquisa',false);
    }else{
      document.form1.pc01_descrmater.value = ""; 
    }
  }
}
function js_mostra(chave,erro){
  document.form1.m60_descr.value = chave; 
  if(erro==true){ 
    document.form1.m41_codmatmater.focus(); 
    document.form1.m41_codmatmater.value = ''; 
  }else{
    document.form1.m60_descr.value=chave;
    document.form1.submit();
  }
}
function js_mostra1(chave1,chave2){
  document.form1.m41_codmatmater.value = chave1;
  document.form1.m60_descr.value = chave2;
  db_iframe_mater.hide();
  document.form1.submit();
}
function js_testaquant(){
  m41_quant=new Number(document.form1.m41_quant.value );
  quant_disp= new Number(document.form1.quant_disp.value );
  
  if ( quant_disp == 0 ) {
    alert('Quantidade disponível igual a zero!');
    return false;    
  } else {
    if (m41_quant<=quant_disp){
      return true;
    }else{
      alert('Informe uma Quantidade Válida!!');
      document.form1.m41_quant.value="";
      document.form1.m41_quant.focus();
      return false;
    }
  }
  
}
function js_adicionaCentroCusto() {
 
  var iOrigem  = 2;
  var sUrl     = 'iOrigem='+iOrigem+'&iCodItem='+$F('m41_codmatmater')+'&iCodigoDaLinha='+$F('m41_codmatmater');
  sUrl        += '&iCodigoDepto=<?echo db_getsession("DB_coddepto")?>';
  if ($F('m41_codmatmater')) {
    
    js_OpenJanelaIframe('',
                        'db_iframe_centroCusto',
                        'cus4_escolhercentroCusto.php?'+sUrl,
                        'Centro de Custos',
                        true,
                        '25',
                        '1',
                        (document.body.scrollWidth-10),
                        (document.body.scrollHeight-100)
                       );
  }
  
   
}

function js_completaCustos(iCodigo, iCriterio, iDescr) {
  
  $('cc08_sequencial').value = iCriterio;
  $('cc08_descricao').value  = iDescr;
  db_iframe_centroCusto.hide();

}
</script>