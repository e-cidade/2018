<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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

//MODULO: issqn
$clissvar->rotulo->label();
$clrotulo= new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("z01_numcgm");
$clrotulo->label("q02_inscr");
$clrotulo->label("q06_nota");
$clrotulo->label("q06_valor");
$clrotulo->label("j14_tipo");

$substitui = false;
?>
<script type="text/javascript">

function js_ControlaAnoMes(mes,ano,anoant){
  if(mes == 12 && anoant == ''){
     ano = ano-1;
     mes = 1;
  }
  document.form1.q05_mes.value = mes;
  document.form1.q05_ano.value = ano;
  anoant = 'true';
  document.form1.anoant.value  = anoant;
}

function js_confirmar(){
  obj=document.form1;
  ano = new Number(obj.q05_ano.value);
  if(isNaN(ano) || ano<1){
     alert('Verifique o ano digitado.');
     return false;
  }
  q05_aliq = new Number(obj.q05_aliq.value);
  if(isNaN(q05_aliq) || q05_aliq<1){
     alert('Verifique a aliquota digitada.');
     return false;
  }
}

function js_excluir(){

  coluna=criatabela.document.getElementById('tab');
  valtot=0;
  for(i=1; i<coluna.rows.length; i++){
     valtot=valtot+new Number(coluna.rows[i].cells[1].innerHTML);
  }
  document.form1.q05_bruto.value=valtot;
  var aliq   = document.form1.q05_aliq.value;
  document.form1.q05_bruto.value=new Number(valtot);
  document.form1.q05_valor.value=(valtot*aliq)/100;
}

function js_incluir(){
  var nota   = document.form1.q06_nota.value;
  var valor  = new Number(document.form1.q06_valor.value);
  var coluna = criatabela.document.getElementById('tab');
  var aliq   = document.form1.q05_aliq.value;
  valtot=0;
  for(i=1; i<coluna.rows.length; i++){
    if(coluna.rows[i].cells[0].innerHTML=="&nbsp;"){
      alert('Para lançamentos sem nota, só pode haver um registro.');
      return false;
    }else{
       if(nota==""){
         alert('Já foi lançado um registo com nota, portanto para cadastrar outros é preciso informar o numero da nota.');
         return false;
       }else{
         if(coluna.rows[i].cells[0].innerHTML==nota){
	         alert("Nota já lançada.");
           return false;
	       }
       }
    }
  }
  if(isNaN(valor) || valor==""){
    alert("Verifique o valor.");
    document.form1.q06_valor.focus();
    return false;
  }
  valtot=new Number(document.form1.q05_bruto.value);
  bruto =(valtot+valor);
  document.form1.q05_bruto.value=bruto.toFixed(2);
  va=(bruto*aliq)/100;
  document.form1.q05_valor.value=va.toFixed(2);
  valor=valor.toFixed(2);
  js_incluirlinhas(nota,valor);
  document.form1.q06_nota.value="";
  document.form1.q06_valor.value="";
}

function js_alterar(nota,valor){

  if (document.form1.q06_nota.value!="") {
    document.form1.lancar.click();
  }

  if(nota=="&nbsp;"){
    nota="";
  }

  document.form1.q06_nota.value=nota;
  document.form1.q06_valor.value=valor;
  coluna=criatabela.document.getElementById('tab');
  valtot=0;
  for(i=1; i<coluna.rows.length; i++){
     valtot=valtot+new Number(coluna.rows[i].cells[1].innerHTML);
  }
  ///////////////////////////////////////////////////////////////////
  var aliq   = document.form1.q05_aliq.value;
  document.form1.q05_bruto.value=new Number(valtot);
  document.form1.q05_valor.value=(valtot*aliq)/100;
}

function js_verifica(){

  coluna=criatabela.document.getElementById('tab');
  if(coluna.rows.length==1){
   return (confirm("Deseja criar um registro sem valor?"));
  }else{

    js_dados();
    return true;
  }

}
function js_trocaaliq(aliq){

  valtot=document.form1.q05_bruto.value;
  document.form1.q05_valor.value=(valtot*aliq.value)/100;
  document.form1.q05_aliq.value=aliq.value;
}
</script>
<?if($db_opcao==2|| $db_opcao==22){?>
    <form name="form1" method="post" action="iss1_issvar015.php">
<?}else if($db_opcao==1){?>
    <form name="form1" method="post" action="iss1_issvar014.php">
<?}else if($db_opcao==3||$db_opcao==33){?>
    <form name="form1" method="post" action="iss1_issvar016.php">
<?}?>
<?=db_input('q05_codigo',10,$Iq05_codigo,true,'hidden',1)?>
<center>
<table border="0">
  <tr>
    <td align="center" colspan="3">
<?
$substitui=false;
if(isset($z01_numcgm) && $z01_numcgm>0 && empty($q02_inscr)){
   echo "<fieldset><legend align=\"center\"><b><small><b>CONTRIBUINTE DE FORA DO MUNICÍPIO</b></small></b></legend>";
}else{
   echo "<fieldset><legend>Dados da Inscrição</legend>";
}
?>
      <table>
        <tr>
<?
if(isset($z01_numcgm) && $z01_numcgm>0 && empty($q02_inscr)){
?>
          <td>
          <?=db_ancora($Lz01_numcgm,"js_JanelaAutomatica('cgm','$z01_numcgm')",2)?>
	  <?=db_input('z01_numcgm',10,$Iz01_numcgm,true,'text',3)?>
         <?=@$Lz01_nome?><?
         $result_nome=db_query(" select z01_nome from cgm where z01_numcgm = $z01_numcgm ");
         if (pg_numrows($result_nome)>0){
         	db_fieldsmemory($result_nome,0);
         }
         db_input('z01_nome',50,@$Iz01_nome,true,'text',3)?>

<?
}else {
?>
    <td>
      <?
      db_ancora($Lq02_inscr,"js_JanelaAutomatica('issbase','".@$q02_inscr."')",2);
      ?>
      <?=db_input('q02_inscr',6,$Iq02_inscr,true,'text',3)?>
      <?=@$Lz01_nome?><?=db_input('z01_nome',50,@$Iz01_nome,true,'text',3)?>
    </td>
<?
}
?>
      </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td align="center"  valign="top">
      <fieldset><legend align="center"><b> Dados para o calculo</b></legend>
      <table border="0">
        <tr>
          <td nowrap title="Dados para o calculo" colspan="">
            <?=@$Lq05_ano?>
	  </td>
	  <td>
<?
$anos=array();
$anoatual=date("Y",db_getsession("DB_datausu"));
for($i=$anoatual; $i>($anoatual-15); $i--){
  $anos[$i] = $i;
}

if(empty($q05_mes)){
  $q05_mes=date("m",db_getsession('DB_datausu'));
}
$mes_atual=date("m",db_getsession('DB_datausu'));
if($mes_atual-1 == 0 && $db_opcao==1){
  $q05_ano = $anos[$anoatual-1];
  $q05_mes = 12;
}


if (isset($q05_ano) && $q05_ano != ""){
  if(isset($anoant) && $anoant != ""){
    $q05_ano = $anoant;
  }
}

db_select("q05_ano",$anos,true,$db_opcao,"onChange='js_controla();'","","","","");

if (!isset($q05_ano)){
  reset($anos);
  $q05_ano=key($anos);
}

?>
  </td>
	</tr>
	<tr>
 <td>
    <?=@$Lq05_mes?>
 </td>
<td>
<?

$result=array("1"=>"Janeiro","2"=>"Fevereiro","3"=>"Março","4"=>"Abril","5"=>"Maio","6"=>"Junho","7"=>"Julho","8"=>"Agosto","9"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro");

if (isset($q05_mes) && $q05_mes != ""){
  if(isset($mesant) && $mesant != ""){
    $q05_mes = $mesant;
  }
}
db_select("q05_mes",$result,true,$db_opcao," onChange='js_controla();'","","","","");

if (!isset($q05_mes)){
  $posicao=date('m',db_getsession("DB_datausu"));
  $q05_mes=$result[$posicao];
}

if ((isset($q05_ano)&&$q05_ano!=""&&is_numeric($q05_ano))&&(isset($q05_mes)&&$q05_mes!="")&&($db_opcao==1)){

  $result_lancamento=$clissvar->sql_record($clissvar->sql_query_arrecad(null,'q05_codigo',null,"q05_ano=$q05_ano and q05_mes=$q05_mes and q05_vlrinf=0 and q05_valor=0 and k00_inscr=".@$q02_inscr.""));
  if ($clissvar->numrows!=0){
    db_fieldsmemory($result_lancamento,0);
    $substitui=true;
    $q05_codigo=$q05_codigo;
    db_input('q05_codigo',10,'',true,'hidden',3);
  }else{
    $substitui=false;
  }
}else{
  $substitui=false;
}
?>

<input type="hidden" name="mesant">
<input type="hidden" name="anoant">
<script>
  onLoad=document.form1.mesant.value = document.form1.q05_mes.value;
  onLoad=js_ControlaAnoMes(document.form1.q05_mes.value,document.form1.q05_ano.value);
</script>
          </td>
	</tr>
	<tr>
	  <td>
            <?=@$Lq05_aliq?>
	  </td>
	  <td>
<?
if(isset($numrows33)){
  $array=array();
  for($g=0; $g<$numrows33; $g++){
    db_fieldsmemory($result33,0);
    $array[$q81_valexe]=$q81_valexe;
  }
  db_select('aliq',$array,true,$db_opcao,"onchange='js_trocaaliq(this)';");
  db_input('q05_aliq',10,$Iq05_aliq,true,'text',1," onKeyUp='this.value = this.value.replace(\",\",\".\")' ","","#E6E4F1");
?>
<script>
onLoad=document.form1.q05_aliq.value = document.form1.aliq.value;
</script>
<?
}else{
	if (! isset($q05_aliq) || $q05_aliq == "") {
    $result66=$clparissqn->sql_record($clparissqn->sql_query_file("","q60_aliq as q05_aliq"));
    db_fieldsmemory($result66,0);
	}
  db_input('q05_aliq',10,$Iq05_aliq,true,'text',1," onKeyUp='this.value = this.value.replace(\",\",\".\")' ","","#E6E4F1");
}
?>

          </td>
        </tr>
      </table>
      </fieldset>
      <fieldset><legend align="center"> <b>Valores</b></legend>
      <table border="0">
        <tr>
          <td>
            <?=@$Lq05_bruto?>
	  </td>
	  <td>
<?
if(empty($q05_bruto)){
  $q05_bruto="0";
}
db_input('q05_bruto',10,$Iq05_bruto,true,'text',3)
?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tz01_incest?>">
            <?=@$Lq05_valor?>
          </td>
          <td>
<?
if(empty($q05_valor)){
  $q05_valor="0";
}
db_input('q05_valor',10,$Iq05_valor,true,'text',3)
?>
          </td>
        </tr>
      </table>
      </fieldset>
       <?=@$Lq05_histor?><br>
<?
db_textarea('q05_histor',5,30,$Iq05_histor,true,'text',$db_opcao,"")
?>

    </td>
    <td  height="100%" valign="top" align="center">
      <fieldset><legend align="center"><b>Notas fiscais</b></legend>
      <table border="0">
        <tr>
          <td nowrap width="60%">
          <?=@$Lq06_nota?>
          </td>
          <td>
            <?
              db_input('q06_nota',10,$Iq06_nota,true,'text',$db_opcao,"")
            ?>
          </td>
	  </tr>
	  <tr>
          <td>
       <?=@$Lq06_valor?>
          </td>
          <td>
            <?
              db_input('q06_valor',10,$Iq06_valor,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
	<tr>
	  <td align="center" colspan="2">
            <input name="lancar" type="button" value="Lançar" onclick="js_incluir();">
	  </td>
	</tr>
	<tr>
	<td colspan="2">
         <?
//           echo $sql_codigo;
      	   $cliframe_alterar_excluir_html->colunas       = array("q06_nota"=>$Lq06_nota,"q06_valor"=>$Lq06_valor."(R$)");
           $cliframe_alterar_excluir_html->iframe_width  = "290";
      	   $cliframe_alterar_excluir_html->iframe_nome   = "criatabela";
      	   $cliframe_alterar_excluir_html->iframe_height = "185";
           if($db_opcao==3){
  	         $cliframe_alterar_excluir_html->db_opcao = "3";
           }
      	   $cliframe_alterar_excluir_html->js_ex02 = "js_excluir();";
           if(isset($sql_codigo)){
  	         $cliframe_alterar_excluir_html->sql = $sql_codigo;
           }
      	   $cliframe_alterar_excluir_html->iframe_alterar_excluir_html();
      	 ?>
      </td>
      </tr>
      </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
     <?
       if ($substitui==true){
     ?>
      <b>Já existe um lançamento na competência informada.</b><br/>
      <input name="substituir" type="submit" value="substituir">
     <?
       }
     ?>
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" <?=($db_opcao==1||$db_opcao==2?"onclick='return js_verifica();'":"")?> id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
      <input name="voltar" type="button" value="Voltar" onclick="js_voltar();" >
    </td>
  </tr>
</table>
  </center>
</form>
<script type="text/javascript">

function js_controla(){

  document.form1.q05_valor.value = 0;
  document.form1.q05_bruto.value = 0;
  document.form1.mesant.value    = document.form1.q05_mes.value;
  document.form1.anoant.value    = document.form1.q05_ano.value;
  document.form1.submit();
}

function js_voltar(){

<?if($db_opcao==2|| $db_opcao==22){?>
  location.href="iss1_issvar002.php";
<?}else if($db_opcao==1){?>
  location.href="iss1_issvar001.php";
<?}else if($db_opcao==3||$db_opcao==33){?>
  location.href="iss1_issvar003.php";
<?}?>
}
</script>