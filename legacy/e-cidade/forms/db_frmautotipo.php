<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

include("dbforms/db_classesgenericas.php");

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clautotipo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y50_codauto");
$clrotulo->label("y50_nome");
$clrotulo->label("y29_descr");
$clrotulo->label("y39_codandam");
$clrotulo->label("y59_valor");
$clrotulo->label("y59_fator");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

if(isset($opcao) && $opcao == "alterar"){
  $result = $clautotipo->sql_record($clautotipo->sql_query_baixa(null,"*",null,"y59_codauto=$y59_codauto and y59_codtipo=$y59_codtipo and y87_dtbaixa is null"));
  if ($clautotipo->numrows!=0){
  echo "<script>parent.iframe_autotipo.location.href='fis1_autotipo002.php?chavepesquisa=$y59_codauto&chavepesquisa1=$y59_codtipo'</script>";
  }else{
    db_msgbox('Procedência já baixada!!');
    $y59_codtipo="";
  }
}
if(isset($opcao) && $opcao == "excluir"){
  $result = $clautotipo->sql_record($clautotipo->sql_query_baixa(null,"*",null,"y59_codauto=$y59_codauto and y59_codtipo=$y59_codtipo and y87_dtbaixa is null"));
  if ($clautotipo->numrows!=0){
    echo "<script>parent.iframe_autotipo.location.href='fis1_autotipo003.php?chavepesquisa=$y59_codauto&chavepesquisa1=$y59_codtipo'</script>";
  }else{
    db_msgbox('Procedência já baixada!!');
    $y59_codtipo="";
  }
}
?>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/numbers.js"></script>
<center>
<form name="form1" method="post" action="">
<input type='hidden' name='andamento' value=''>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty59_codauto?>">
       <?
       db_ancora(@$Ly59_codauto,"js_pesquisay59_codauto(true);",3);
       ?>
    </td>
    <td>
      <?
        db_input('y59_codauto',10,$Iy59_codauto,true,'text',3," onchange='js_pesquisay59_codauto(false);'");
        db_input('y50_codauto',10,$Iy50_codauto,true,'hidden',3,"");
        db_input('y45_percentual',10,$Iy50_codauto,true,'hidden',3,"");
        db_input('y39_codandam',20,$Iy39_codandam,true,'hidden',3,"");
        db_input('y50_nome',40,$Iy50_nome,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty59_codtipo?>">
       <?
       db_ancora(@$Ly59_codtipo,"js_pesquisay59_codtipo(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('y59_codtipo',10,$Iy59_codtipo,true,'text',$db_opcao," onchange='js_pesquisay59_codtipo(false);'");
db_input('y59_codtipo',10,$Iy59_codtipo,true,'hidden',$db_opcao,"","y59_codtipo_old");
echo "<script>document.form1.y59_codtipo_old.value='".@$y59_codtipo."'</script>";
?>
       <?
db_input('y29_descr',40,$Iy29_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty59_valor?>">
       <?=@$Ly59_valor?>
    </td>
    <td>
    <?php
    $fixo = false;
    if (isset($y59_codtipo)&&$y59_codtipo!=""){

      $res = $clfiscalprocrec->sql_record($clfiscalprocrec->sql_query_file($y59_codtipo));
      if ($clfiscalprocrec->numrows!=0){

        db_fieldsmemory($res,0);
        if ($y45_vlrfixo == 't'){
          $fixo = true;
        }
      }
    }

    $sMascaraValor = "return mascaraValor(event, this);";
    if( isset($y45_percentual) ){
      if( $y45_percentual == 't' ){
        $sMascaraValor = "";
      }
    }

    if( $fixo == true ){
      db_input('y59_valor',10,4,true,'text',3,"");
    }else{
      db_input('y59_valor',10,4,true,'text',$db_opcao,"onkeypress=\"$sMascaraValor\" onchange='js_validaValor();'");
    }
    ?>
    </td>
  </tr>
  <tr class="hide">
   <td nowrap title="<?=@$Ty59_tipo?>">
    <?=@$Ly59_tipo?>
   </td>
   <td>
    <?
      $x = array('0'=>'Nenhum','1'=>'Acrescimo','2'=>'Redução');
      db_select('y59_tipo',$x,true,$db_opcao,"");
     ?>
    </td>
  </tr>
    <tr class="hide">
   <td nowrap title="<?=@$Ty59_fator?>">
     <?=@$Ly59_fator?>
   </td>
    <td>
  <?
  db_input('y59_fator',8,@$Iy59_fator,true,'text',$db_opcao,"")
  ?>
  </td>
 </tr>
  <tr>
    <td colspan="2" align="center">
      <?php
        if ($db_opcao == 1) {
          $sValorBotao = "Incluir";
        }else if ($db_opcao==2||$db_opcao==22) {
          $sValorBotao = "Alterar";
        } else if ($db_opcao==3||$db_opcao==33) {
          $sValorBotao = "Excluir";
        } else {
          $sValorBotao = $db_opcao;
        }
      ?>
      <input <?($db_opcao == 1?"disabled":"")?> name="db_opcao" type="submit" id="db_opcao" value="<?php echo $sValorBotao;?>" <?=($db_botao==false?"disabled":"")?> <?=($db_opcao==1||$db_opcao==2||$db_opcao==22?"onclick='return js_validaFormulario();'":"")?> />
      <?
      if(($db_opcao==2||$db_opcao==22||$db_opcao==3||$db_opcao==33)){
      ?>
        <input name="novo" type="button" id="novo" value="Novo" onclick="location.href='fis1_autotipo001.php?y59_codauto=<?=$y59_codauto?>'">
      <?
      }
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
    <?
    $chavepri= array("y59_codauto"=>@$y59_codauto,"y59_codtipo"=>@$y59_codtipo);
    $cliframe_alterar_excluir->chavepri=$chavepri;
    $cliframe_alterar_excluir->campos="y59_codtipo,y29_descr,y29_descr_obs,y59_valor,y87_dtbaixa,y114_processo";
    $cliframe_alterar_excluir->sql=$clautotipo->sql_query_baixa(""," * ",""," y59_codauto = $y59_codauto");
    $cliframe_alterar_excluir->legenda="Procedências do Auto de Infração";
    $cliframe_alterar_excluir->msg_vazio ="<font size='1'>Nenhuma Procedência Cadastrada!</font>";
    $cliframe_alterar_excluir->textocabec ="darkblue";
    $cliframe_alterar_excluir->textocorpo ="black";
    $cliframe_alterar_excluir->fundocabec ="#aacccc";
    $cliframe_alterar_excluir->fundocorpo ="#ccddcc";
    $cliframe_alterar_excluir->iframe_height ="150";
    $cliframe_alterar_excluir->iframe_width ="700";
    $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <fieldset>
      <legend align="center"><strong>Escolha um Andamento Padrão</strong></legend>
<?php

$clautotipo1 = new cl_autotipo;
$clautoandam = new cl_autoandam;
$clautotipo1->rotulo->label();

if( !empty($y59_codauto) ){

  $result = $clautotipo1->sql_record($clautotipo1->sql_query(""," distinct(y41_codtipo),y41_descr,y59_codauto ",""," y59_codauto = $y59_codauto"));
}

if($clautotipo1->numrows > 0){

  db_fieldsmemory($result,0);
  $result1 = $clautoandam->sql_record($clautoandam->sql_query_file("",""," max(y58_codandam) as y58_codandam ",""," y58_codauto = $y59_codauto"));
  if($clautoandam->numrows > 0){

    db_fieldsmemory($result1,0);

    if( !empty($y58_codandam) ){

      $result1 = $clfandam->sql_record($clfandam->sql_query_file("","*",""," y39_codandam = $y58_codandam"));
    }

    if($clfandam->numrows > 0){

      db_fieldsmemory($result1,0);
      if($clautotipo1->numrows == 1){

        if($y41_codtipo != $y39_codtipo){
          db_inicio_transacao();
      	  $clfandam->y39_codtipo = $y41_codtipo;
      	  $clfandam->y39_codandam = $y58_codandam;
      	  $clfandam->alterar("");
          db_fim_transacao();
	      }
      }

    }else{

      if(isset($y41_codtipo) && $y41_codtipo != ""){

      	db_inicio_transacao();
      	$clfandam->y39_codtipo = $y41_codtipo;
      	$clfandam->y39_obs="0";
      	$clfandam->y39_id_usuario= db_getsession("DB_id_usuario");
      	$clfandam->y39_data=date("Y-m-d",db_getsession("DB_datausu"));
      	$clfandam->y39_hora=db_hora();
      	$clfandam->incluir(null);
      	$clautoultandam->y16_codauto = $y59_codauto;
      	$clautoultandam->y16_codandam = $clfandam->y39_codandam;
      	$clautoultandam->incluir($y59_codauto,$clfandam->y39_codandam);
      	$clautoandam->y58_codauto = $y59_codauto;
      	$clautoandam->y58_codandam = $clfandam->y39_codandam;
      	$clautoandam->incluir($y59_codauto,$clfandam->y39_codandam);
      	$y39_codtipo = $y41_codtipo;
      	db_fim_transacao();
      }
    }
  }else{
    if(isset($y41_codtipo) && $y41_codtipo != ""){

      db_inicio_transacao();
      $clfandam->y39_codtipo = $y41_codtipo;
      $clfandam->y39_obs="0";
      $clfandam->y39_id_usuario= db_getsession("DB_id_usuario");
      $clfandam->y39_data=date("Y-m-d",db_getsession("DB_datausu"));
      $clfandam->y39_hora=db_hora();
      $clfandam->incluir("");
      $clautoultandam->y16_codauto = $y59_codauto;
      $clautoultandam->y16_codandam = $clfandam->y39_codandam;
      $clautoultandam->incluir($y59_codauto,$clfandam->y39_codandam);
      $clautoandam->y58_codauto = $y59_codauto;
      $clautoandam->y58_codandam = $clfandam->y39_codandam;
      $clautoandam->incluir($y59_codauto,$clfandam->y39_codandam);
      $y39_codtipo = $y41_codtipo;
      db_fim_transacao();
    }
  }

  echo "<script>parent.document.formaba.receitas.disabled=false;</script>";
  echo "<script>parent.document.formaba.fiscais.disabled=false;</script>";
  echo "<script>parent.iframe_receitas.location.href='fis1_autorec001.php?y59_codauto=".$y59_codauto."&abas=1';</script>\n";
  echo "<script>parent.iframe_fiscais.location.href='fis1_autousu001.php?y59_codauto=".$y59_codauto."&abas=1&y39_codandam=$y39_codandam';</script>\n";
  echo "<table cellpadding='1' cellspacing='2' border='0' width='600'>";
  for($i=0;$i<$clautotipo1->numrows;$i++){
    db_fieldsmemory($result,$i);
    echo "<tr bgcolor='#999999'>
	    <td align='center' valign='center'>
	      <input  type='radio' name='tipoandam' ".($y41_codtipo == $y39_codtipo?"checked":"")." value='$y41_codtipo' onChange='document.form1.andamento.value=this.value;document.form1.action=\"fis1_autotipo001.php\";document.form1.submit();'>
	      ".($i == 0?"<script>document.form1.andamento.value='$y41_codtipo'</script>":"")."
	    </td>
	    <td><small>TIPO DE ANDAMENTO</small></td>
	    <td>$y41_descr</td>
	  </tr>";
  }
  echo "</table>";
}else{

    echo "<script>
          parent.document.formaba.receitas.disabled    = true;
          parent.document.formaba.fiscais.disabled     = true;
          </script>";
}
?>
      </fieldset>
    </td>
  </tr>
  </table>
</form>
</center>
<script type="text/javascript">

var sCaminhoMensagens = "tributario.fiscal.db_frmautotipo.";

function js_validaValor() {

  if ( $F('y45_percentual') == 't') {

    if ($F('y59_valor') > 100 || $F('y59_valor') <= 0) {

      alert( _M( sCaminhoMensagens + 'erro_percentual_invalido') );
      document.form1.y59_valor.value = "";
      return false;
    }
  }
}

function js_validaFormulario(){

  document.form1.y59_valor.disabled="";

  var oRegex = /Chave\(*[0-9]*\)/;
  if( empty($F('y29_descr')) || oRegex.test($F('y29_descr')) ){
    $('y59_codtipo').value = null;
  }

  if( !isNumeric( $F('y59_codtipo') ) || empty($F('y59_codtipo')) ){

    alert( _M( sCaminhoMensagens + 'procedencia_obrigatorio') );
    return false;
  }

  if( !isNumeric( $F('y59_valor'), true ) ){

    alert( _M( sCaminhoMensagens + 'valor_invalido') );
    return false;
  }

  return true;
}

function js_setatabulacao(){
  js_tabulacaoforms("form1","y59_codtipo",true,1,"y59_codtipo",true);
}

function js_pesquisay59_codauto(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_auto','func_auto.php?funcao_js=parent.js_mostraauto1|y50_codauto|y50_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_auto','func_auto.php?pesquisa_chave='+document.form1.y59_codauto.value+'&funcao_js=parent.js_mostraauto','Pesquisa',false);
  }
}
function js_mostraauto(chave,erro){
  document.form1.y50_nome.value = chave;
  if(erro==true){
    document.form1.y59_codauto.focus();
    document.form1.y59_codauto.value = '';
  }
}
function js_mostraauto1(chave1,chave2){
  document.form1.y59_codauto.value = chave1;
  document.form1.y50_nome.value = chave2;
  db_iframe_auto.hide();
}

function js_pesquisay59_codtipo(mostra){

  tipofisc = parent.iframe_auto.document.form1.y50_codtipo.value;
  if (tipofisc!=""){
    if(mostra==true){
      js_OpenJanelaIframe('','db_iframe','func_fiscalprocaltauto.php?tipofisc='+tipofisc+'&funcao_js=parent.js_mostrafiscalproc1|y29_codtipo|y29_descr|y45_vlrfixo|y45_valor|y45_percentual','Pesquisa',true);
    }else{
      js_OpenJanelaIframe('','db_iframe','func_fiscalprocaltauto.php?tipofisc='+tipofisc+'&pesquisa_chave='+document.form1.y59_codtipo.value+'&funcao_js=parent.js_mostrafiscalproc','Pesquisa',false);
    }
  }else{
    alert('Informe um tipo de fiscalização para o auto!');
  }
}

function js_mostrafiscalproc(chave,erro,fixo,valor,percentual){

  document.form1.y45_percentual.value = percentual;
  document.form1.y29_descr.value      = chave;
  if(erro==true){

    document.form1.y59_codtipo.focus();
    document.form1.y59_codtipo.value = '';
  }else{

    if (fixo=='t'){

      document.form1.y59_valor.value      = valor;
      document.form1.y59_valor.disabled   = "true";
    }else if (fixo=='f'){

      document.form1.y59_valor.value    = valor;
      document.form1.y59_valor.disabled = "";
    }

    js_atribuiMascara( percentual );
    js_OpenJanelaIframe('','db_iframe_autonome','func_autonome.php?pesquisa_chave='+document.form1.y59_codauto.value+'&codtipo='+document.form1.y59_codtipo.value+'&funcao_js=parent.js_mostrare','Pesquisa',false);
  }
}

function js_mostrafiscalproc1(chave1,chave2,fixo,valor,percentual){

  document.form1.y45_percentual.value = percentual;
  document.form1.y59_codtipo.value    = chave1;
  document.form1.y29_descr.value      = chave2;

  if (fixo=='t'){

    document.form1.y59_valor.value    = valor;
    document.form1.y59_valor.disabled = "true";

  }else if (fixo=='f'){

    document.form1.y59_valor.value     = "";
    document.form1.y59_valor.disabled  = "";
  }

  js_atribuiMascara( percentual );
  js_OpenJanelaIframe('','db_iframe_autonome','func_autonome.php?pesquisa_chave='+document.form1.y59_codauto.value+'&codtipo='+chave1+'&funcao_js=parent.js_mostrare','Pesquisa',false);
  db_iframe.hide();
}

function js_atribuiMascara( lPercentual ){

  $('y59_valor').stopObserving('keypress');
  $('y59_valor').onkeypress = '';
  if( lPercentual == "f" || lPercentual == ""){

    $('y59_valor').observe('keypress', function(event){
      return mascaraValor(event, $('y59_valor'));
    });
  }
}


function js_mostrare(retorna){

  if (retorna==true){
    alert('Cgm já reincidente nesta procedência!!');
  }
}

function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_autotipo','func_autotipo.php?funcao_js=parent.js_preenchepesquisa|y59_codauto|1','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_autotipo.hide();
  <?
    if($db_opcao == 2 || $db_opcao == 22){
      echo " location.href = 'fis1_autotipo002.php?abas=1&chavepesquisa='+chave;";
    }elseif($db_opcao == 33 || $db_opcao == 3){
      echo " location.href = 'fis1_autotipo003.php?abas=1&chavepesquisa='+chave;";
    }
  ?>
}
</script>
<?php
if( $db_opcao == 2 ){
  echo "js_atribuiMascara('$y45_percentual');";
}

if(isset($y59_codauto) && $y59_codauto != ""){
  echo "<script>js_OpenJanelaIframe('','db_iframe_fiscal','func_auto.php?pesquisa_chave=$y59_codauto&funcao_js=parent.js_mostraauto','Pesquisa',false);</script>";
  echo "<script>document.form1.y50_codauto.value='$y59_codauto';</script>";
}
?>