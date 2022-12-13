<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

session_start();
if(!session_is_registered("DB_acesso"))
    echo"<script>location.href='index.php?erroscripts=3'</script>";
include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
mens_help();
$dblink="index.php";
db_logs("","",0,"Digita Codigo do Contribuinte.");
db_mensagem("contribuinte_cab","contribuinte_rod");
postmemory($HTTP_POST_VARS);
$matri= array("1"=>"janeiro","2"=>"Fevereiro","3"=>"Março","4"=>"Abril","5"=>"Maio","6"=>"Junho","7"=>"Julho","8"=>"Agosto","9"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro");
$mesx= $matri[$mes];
$clquery = new cl_query;
if(isset($alter)){
}else if(isset($nova)){
   $result = db_query("select * from issbase inner join cgm on z01_numcgm = q02_numcgm where q02_inscr  = $inscricaow and  z01_numcgm = $numcgm");
   if(pg_numrows($result) != 0){
     db_fieldsmemory($result,0);
   }else{
    redireciona("digitaissqn.php?".base64_encode("erroscripts=Acesso a Rotina Inválido, verifique os dados digitados!"));
   }  
}elseif(isset($first)){
  $inscricaow!=""?"":$inscricaow = 0 ;
  if ( !empty($cgc) ){
    $cgccpf = $cgc;
  }else{
     if (!empty($cpf) ){
       $cgccpf = $cpf;
     }else{
       $cgccpf = "";
     }
  }
  $cgccpf = str_replace(".","",$cgccpf);
  $cgccpf = str_replace("/","",$cgccpf);
  $cgccpf = str_replace("-","",$cgccpf);  
  $result = db_query("select * from issbase inner join cgm on q02_numcgm = z01_numcgm where z01_cgccpf = '$cgccpf' and q02_inscr  = $inscricaow");
  if(pg_numrows($result) != 0){
    db_fieldsmemory($result,0);
  }else{
    redireciona("digitaissqn.php?".base64_encode("erroscripts=Acesso a Rotina Inválido, verifique os dados digitados!"));
  }  
}  
  if(isset($primeiravez)){
    $clquery->sql_query("issplan","q20_mes, q20_ano","q20_mes"," q20_ano = $ano and q20_mes=$mes and q20_numcgm= $z01_numcgm");
    $clquery->sql_record($clquery->sql);
    $num=$clquery->numrows;
    if($num!=0){
       if(isset($q02_inscr)){
         $inscricaow=$q02_inscr;
       }
       redireciona("planilha.php?".base64_encode("nomecontri=".@$nomecontri."&fonecontri=".@$fonecontri."&inscricaow=".$inscricaow."&mesx=".$mesx."&mes=".$mes."&ano=".$ano."&numcgm=".$z01_numcgm."&nomes=".$z01_nome));
    }
  }
  if(isset($grava)){
    if($modificando==true){
       $sqlx1="delete from issplanit where q21_planilha=$plani";
       $sqlx2="delete from issplan where q20_planilha=$plani";
       db_query($sqlx1)or die($sqlx1);
       $planilha=$plani;
    }else{
      $clquery->sql_query("","nextval('issplan_pla_seq')");

      $clquery->sql_record($clquery->sql);

      $planilha=pg_result($clquery->result,0,0);

      $planilha = $planilha==""?"1":$planilha;
      $clquery->sql_insert(" issplan ","$planilha,$z01_numcgm,$inscricaow, $ano, $mes,'$nomecontri','$fonecontri'");

    }
    for($i=1;$i<=$numarq;$i++){
      $arq="arq_".$i; 
      if(isset($$arq)){
	$c = "cnpj_".$i;
	$tcnpj = $$c;
	$c = "inscricao_".$i;
	$tinscricao = $$c;
	$c = "nomerazao_".$i;
	$tnomerazao = $$c;
	$c = "sprestado_".$i;
	$tsprestado = $$c;
	$c = "numnota_".$i;
	$tnumnota = $$c;
	$c = "numserie_".$i;
	$tnumserie = $$c;
	$c = "valservico_".$i;
	$tvalservico = $$c;
	$c = "aliquota_".$i;
	$taliquota = $$c;
	$c = "total_".$i;
    $ttotal = $$c;
        $clquery->sql_insert("issplanit","$planilha,'$tcnpj',$tinscricao, '$tnomerazao','$tsprestado','$tnumnota','$tnumserie','$tvalservico','$taliquota', '$ttotal'");
      }
    }
    redireciona("opcoesissqn001.php?".base64_encode("planilha=".$planilha."&ano=".$ano."&mes=".$mes."&inscricao=".$inscricaow));
  }

if(isset($nomes)){
  $z01_nome=$nomes;
}
$cabec=false;
if(isset($guarda)){
  $cabec=true ;
} 
  
//montar ou cabecalho para colocar os dados da planilha 	
  for($i=1;$i<=@$numarq;$i++){
    $arqt = "arq_$i";
    $alterar = "alterar_$i"; 
    $excluir="excluir_$i";
    if(isset($$excluir)||isset($$alterar)){

    }else{  
      if(@$$arqt!=""){
        $cabec=true ;
        break;   
      }
    }
  } 


?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
js_verificapagina("opcoesissqn.php");
function js_veri(){
  if(document.form1.valservico.value.indexOf(",")!=-1){
    var  vals= new Number(document.form1.valservico.value.replace(",","."));
  }else{
    var vals = new Number(document.form1.valservico.value);
  }
  if(isNaN(vals)){
    alert("verifique o valor do serviço!");
    document.form1.valservico.focus();
    return false;
  } 
  var aliquota = new Number(document.form1.aliquota.value);
  vals = new Number((vals *(aliquota/100))); 
  document.form1.total.value=vals.toFixed(2);
}
function maiusculo(obj) {
  var maiusc = new String(obj.value);
  obj.value = maiusc.toUpperCase();
}
function js_vericampos(){
 var alerta="";
  jcnpj=document.form1.cnpj.value;
  jinscricao=document.form1.inscricao.value;
  jnomerazao=document.form1.nomerazao.value;
  jsprestado=document.form1.cnpj.value;
  jnumnota=document.form1.numnota.value;
  jnumserie=document.form1.numserie.value;
  jvalservico=document.form1.valservico.value;
  jaliquota=document.form1.aliquota.value;

  if(jcnpj==""){
    alerta +="CNPJ\n";
  }
  if(jnomerazao==""){
    alerta +="Nome/Razão Social\n";
  }
  if(jvalservico==""){
    alerta +="Serviço Prestado\n";
  }
  if(jnumnota==""){
    alerta +="Numero da Nota\n";
  }
  if(jnumserie==""){
    alerta +="Numero da Série\n";
  }
  var expr = /[^0-9]+/;
  if(jinscricao.match(expr) != null){
    alerta+="Inscrição Inválida";
  }
  if(alerta!=""){
    alert("Verfique os seguintes campos:\n"+alerta);
    return false;
  }else{
    return true;
  }
}
function  abre(){
  window.open('relatoriopdf.php?planilha=<?=@$planilha?>' ,'Ralatorio','toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,directories=no,status=no');
  return false;
}
function js_cgccpf(obj){
  js_verificaCGCCPF(obj,'');
}
</script>
<style type="text/css">
<?db_estilosite();
?>
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
<form name="form1" method="post" action="opcoesissqn.php" >
<?
mens_div();
?>
<center>
<table width="766" border="0" cellpadding="0" cellspacing="0" bgcolor="<?$w01_corbody?>">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="100%" align="left" valign="top"><img src="imagens/cabecalho.jpg"></td>
</tr>
      </table></td>
  </tr>
  <tr>
    <td>
      <table class="bordas" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td nowrap width="90%">
            &nbsp;<a href="index.php" class="links">Principal &gt;</a>
          </td>
	  <td align="center" width="10%" onClick="MM_showHideLayers('<?=$nome_help?>','',(document.getElementById('<?=$nome_help?>').style.visibility == 'visible'?'hide':'show'));">
	    <a href="#" class="links">Ajuda</a>
          </td>
       </tr>
     </table>  
   </td>
  </tr>
  <tr>
    <td align="left" valign="top">
	  <table width="100%" height="313" border="0" cellpadding="0" cellspacing="0">
      <tr>
            <td width="90" align="left" valign="top"> 
          <?            
db_montamenus();
          ?>
		</td>
            <td align="left" valign="top"> 
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td height="60" align="<?=$DB_align1?>">
                    <?=$DB_mens1?>
                  </td>
                </tr>
                <tr align="center">
                  <td>
                    <table width="89%" border="1" cellspacing="0" cellpadding="0">
                        <input name="cpf" type="hidden" value="<?=@$cpf?>">
                        <input name="mes" type="hidden" value="<?=@$mes?>">
                        <input name="ano" type="hidden" value="<?=$ano?>">
                        <input name="cgc" type="hidden" value="<?=@$cgc?>">
                        <input name="inscricaow" type="hidden" value="<?=@$inscricaow?>">
                        <input name="nomecontri" type="hidden" value="<?=@$nomecontri?>">
                        <input name="fonecontri" type="hidden" value="<?=@$fonecontri?>">
                        <input name="modificando" type="hidden" value="<?=@$modificando?>">
                        <input name="plani" type="hidden" value="<?=@$plani?>">
                        <input name="z01_nome" type="hidden" value="<?=@$z01_nome?>">
                        <input name="z01_numcgm" type="hidden" value="<?=isset($numcgm)?$numcgm:@$z01_nuncgm?>">
                        <input name="ttt" type="hidden">
                        <tr> 
                          <td width="89%" colspan="5">
			    <table border="0"> 
                             <tr> 
                                <td width="19%" colspan="0" nowrap><small><b>Nome 
                                  ou Raz&atilde;o Social:</b> 
                                  <font color="white"> <?=$z01_nome?></font>
                                  </small>
			        </td>
                                <td width="19%" colspan="0" nowrap><small><b>Inscrição 
                                  </b> 
                                  <font color="white"> <?=$inscricaow?></font>
                                  </small>
			        </td>
                                <td width="19%" nowrap><small><b>Competência:</b> 
                                 <font color="white">  <?=$mesx?>
                                  de 
                                  <?=$ano?></font>
                                  </small>
				</td>
                              </tr>
                              <tr> 
                                <td align="left"><small><b>Contato:</b></small>
                                  <input type="text" maxlength="40" value="" name="nomecontri" size="14" >
                                  <small><b>Fone:</b></small> 
                                  <input type="text" maxlength="15" value="" name="fonecontri" size="14">
                                </td>
                                <td align="left" nowrap>
				</td>
                              </tr> 
                            </table>
			  </td>
                        </tr>
                        <tr> 
                          <td colspan="5"> <table width="457">
                              <tr> 
                                <td width="19%" align="left"><b><small>CNPJ</small></b></td>
                                <td width="19%" align="left"><b><small>Inscri&ccedil;&atilde;o</small></b></td>
                                <td width="62%" align="left"><b><small>Nome ou 
                                  Razão Social</small></b></td>
                                <td width="62%" align="left"><b><small>Servi&ccedil;o 
                                  Prestado</small></b></td>
                              </tr>
                              <tr> 
                                <td><input name="cnpj" type="text" id="cnpj" size="15" maxlength="18" onKeyDown="FormataCNPJ(this, event)" onBlur="js_cgccpf(this)" ></td>
                                <td ><input name="inscricao" type="text" size="7" maxlength=6  ></td>
                                <td align="left" ><input name="nomerazao" type="text" size="31" maxlength="30" onKeyUp="maiusculo(this)"></td>
                                <td align="left" ><input name="sprestado" type="text" id="sprestado3" size="41" onKeyUp="maiusculo(this)" maxlength="40"></td>
                              </tr>
                              <tr> 
                                <td><b><small>Nota</small></b></td>
                                <td ><b><small>S&eacute;rie</small></b></td>
                                <td align="left" ><b><small>Valor Bruto</small></b></td>
                                <td align="left" ><b><small>Aliquota&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Valor 
                                  Total</small></b></td>
                              </tr>
                              <tr> 
                                <td><input name="numnota" type="text" id="numnota3" maxlength="10" size="10"></td>
                                <td ><small> 
                                  <input name="numserie" type="text" id="numserie4" size="10" maxlength="5" >
                                  </small></td>
                                <td align="left" ><small>R$</small> <input name="valservico" type="text"   id="valservico3" onChange="return js_veri();"  size="10"> 
                                </td>
                                <td align="left" nowrap ><select name="aliquota" id="select" onChange="return js_veri()">
                                    <option value="0">0%</option>
                                    <option value="1" <?=(isset($aliquota)&&$aliquota=="1"?"selected":"")?>>1%</option>
                                    <option value="2" <?=(isset($aliquota)&&$aliquota=="2"?"selected":"")?> selected>2%</option>
                                    <option value="3" <?=(isset($aliquota)&&$aliquota=="3"?"selected":"")?>>3%</option>
                                    <option value="4" <?=(isset($aliquota)&&$aliquota=="4"?"selected":"")?>>4%</option>
                                    <option  value="5" <?=(isset($aliquota)&&$aliquota=="5"?"selected":"")?>>5%</option>
                                    <option value="6" <?=(isset($aliquota)&&$aliquota=="6"?"selected":"")?>>6%</option>
                                    <option value="7" <?=(isset($aliquota)&&$aliquota=="7"?"selected":"")?>>7%</option>
                                    <option value="8" <?=(isset($aliquota)&&$aliquota=="8"?"selected":"")?>>8%</option>
                                    <option value="9" <?=(isset($aliquota)&&$aliquota=="9"?"selected":"")?>>9%</option>
                                    <option value="10" <?=(isset($aliquota)&&$aliquota=="10"?"selected":"")?>>10%</option>
                                  </select>
                                  <b><small>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</small></b> 
                                  <small>R$ 
                                  <input name="total" type="text" size="10" readonly>
                                  </small></td>
                              </tr>
                            </table></td>
                        </tr>
                        <tr> 
                          <td colspan="5" > 
 			              <input name="guarda" class="botao" type="submit"  value="Lança Valor" onclick="return js_vericampos() "> 
                          <input name="grava" type="submit" id="grava" class="botao" value="Gravar e Gerar Planilha" style="visibility: hidden"> 
                          </td>
                        </tr>
                        <tr> 
                          <td colspan="5"> 
                            <?
$mostra_gravar = false;			    
if(!isset($alter)){
  if(isset($guarda)){
    @$numarq=@$numarq+1;  
  }
  if(!isset($numarq)){
    $numarq=0;
  }
  echo "<INPUT NAME=\"numarq\" type=\"hidden\" value=\"".$numarq."\">";
  $imprime_gravar=false;
  if(isset($numarq)&&$numarq>=1){
    if($cabec==true){
      //echo "<script> document.getElementById(grava).style.visibility:'visible'</script>";     
      echo "  <table width=\"99%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" >\n";
      echo "      <tr>\n ";
      echo "        <td align=\"center\" bgcolor=\"#00436E\" width=\"6%\"><small><b>CNPJ</b></small></td>\n";
      echo "        <td align=\"center\" bgcolor=\"#00436E\" width=\"9%\"><small><b>Inscrição</b></small></td>\n";
      echo "        <td align=\"center\" bgcolor=\"#00436E\" width=\"15%\"><small><b>Nome/Razão</b></small></td>\n";
      echo "        <td align=\"center\" bgcolor=\"#00436E\" width=\"18%\"><small><b>Serviço</b></small></td>\n";
      echo "        <td align=\"center\" bgcolor=\"#00436E\" width=\"7%\"><small><b>Nota</b></small></td>\n";
      echo "        <td align=\"center\" bgcolor=\"#00436E\" width=\"7%\"><small><b>Série</b></small></td>\n";
      echo "        <td align=\"center\" bgcolor=\"#00436E\" width=\"10%\"><small><b>Valor</b></small></td>\n";
      echo "        <td align=\"center\" bgcolor=\"#00436E\" width=\"10%\"><small><b>Aliquota</b></small></td>\n";
      echo "        <td align=\"center\" bgcolor=\"#00436E\" width=\"10%\"><small><b>Total</b></small></td>\n";
      echo "        <td align=\"center\" bgcolor=\"#00436E\" width=\"8%\"><small><b>Opções</b></small></td>\n";
      echo "      </tr>\n";
     $mostra_gravar = true;			    
    }
  }
  for($i=1;$i<=$numarq;$i++){
    $arq = "arq_$i";
    $alterar = "alterar_$i"; 
    $cnpj2 = "cnpj_".$i; 
    $inscricao2 = "inscricao_".$i; 
    $nomerazao2 = "nomerazao_".$i; 
    $sprestado2 = "sprestado_".$i;
    $numnota2 = "numnota_".$i; 
    $numserie2= "numserie_".$i; 
    $valservico2 = "valservico_".$i; 
    $aliquota2 = "aliquota_".$i; 
    $total2 = "total_".$i; 
    $excluir="excluir_$i";
    if(isset($$arq) && !isset($$alterar)){
      if(!isset($$excluir)){
        $imprime_gravar = true;
        echo "<INPUT id=\"arq_".$i."\" NAME=\"arq_".$i."\" type=\"hidden\" value=\"".$i."\">\n";
        echo "<INPUT id=\"cnpj_".$i."\" NAME=\"cnpj_".$i."\" type=\"hidden\" value=\"".$$cnpj2."\">\n";
        echo "<INPUT id=\"inscricao_".$i."\" NAME=\"inscricao_".$i."\" type=\"hidden\" value=\"".$$inscricao2."\">\n";
        echo "<INPUT id=\"nomerazao_".$i."\" NAME=\"nomerazao_".$i."\" type=\"hidden\" value=\"".$$nomerazao2."\">\n";
        echo "<INPUT id=\"sprestado_".$i."\" NAME=\"sprestado_".$i."\" type=\"hidden\" value=\"".$$sprestado2."\">\n";
        echo "<INPUT id=\"numnota_".$i."\" NAME=\"numnota_".$i."\" type=\"hidden\" value=\"".$$numnota2."\">\n";
        echo "<INPUT id=\"numserie_".$i."\" NAME=\"numserie_".$i."\" type=\"hidden\" value=\"".$$numserie2."\">\n";
        echo "<INPUT id=\"valservico_".$i."\" NAME=\"valservico_".$i."\" type=\"hidden\" value=\"".$$valservico2."\">\n";
        echo "<INPUT id=\"aliquota_".$i."\" NAME=\"aliquota_".$i."\" type=\"hidden\" value=\"".$$aliquota2."\">\n";
        echo "<INPUT id=\"total_".$i."\" NAME=\"total_".$i."\" type=\"hidden\" value=\"".$$total2."\">\n";
        echo "      <tr class=\"planilha\">\n";
        echo "        <td align=\"center\" width=\"6%\"><small>".$$cnpj2."</small></td>\n";
        echo "        <td align=\"center\" width=\"9%\"><small>".$$inscricao2."</small></td>\n";
        echo "        <td align=\"center\" width=\"15%\"><small>".substr($$nomerazao2,0,20)."</small></td>\n";
        echo "        <td align=\"center\" width=\"18%\"><small>".substr($$sprestado2,0,20)."</small></td>\n";
        echo "        <td align=\"center\" width=\"7%\"><small>".$$numnota2."</small></td>\n";
        echo "        <td align=\"center\" width=\"7%\"><small>".$$numserie2."</small></td>\n";
        echo "        <td align=\"center\" width=\"10%\"><small>R$ ".$$valservico2."</small></td>\n";
        echo "        <td align=\"center\" width=\"10%\"><small>".$$aliquota2."% </small></td>\n";
        echo "        <td align=\"center\" width=\"10%\"><small>R$ ".$$total2." </small></td>\n";
        echo "        <td align=\"center\" width=\"8%\"><input class=\"botao\" type=\"submit\" name=\"alterar_".$i."\" value=\"Alterar\"> <input class=\"botao\" type=\"submit\" name=\"excluir_".$i."\" value=\"Excluir\"></td>\n";
        echo "      </tr>\n";
	
      }
    }else{
      if(isset($$alterar)){
        echo "<script>document.form1.cnpj.value='".$$cnpj2."'</script>" ;
        echo "<script>document.form1.inscricao.value='".$$inscricao2."'</script>" ;
        echo "<script>document.form1.nomerazao.value='".$$nomerazao2."'</script>" ;
        echo "<script>document.form1.sprestado.value='".$$sprestado2."'</script>" ;
        echo "<script>document.form1.numnota.value='".$$numnota2."'</script>" ;
        echo "<script>document.form1.numserie.value='".$$numserie2."'</script>" ;
        echo "<script>document.form1.valservico.value='".$$valservico2."'</script>" ;
        echo "<script>document.form1.aliquota.value='".$$aliquota2."'</script>" ;
        echo "<script>document.form1.total.value='".$$total2."'</script>" ;
      }
    }
  }
  if(isset($guarda)){
    $imprime_gravar = true;
    echo "<INPUT id=\"arq_".$numarq."\" NAME=\"arq_".$numarq."\" type=\"hidden\" value=\"".$numarq."\">\n";
    echo "<INPUT id=\"cnpj_".$numarq."\" NAME=\"cnpj_".$numarq."\" type=\"hidden\" value=\"".$cnpj."\">\n";
    echo "<INPUT id=\"inscricao_".$numarq."\" NAME=\"inscricao_".$numarq."\" type=\"hidden\" value=\"".$inscricao."\">\n";
    echo "<INPUT id=\"nomerazao_".$numarq."\" NAME=\"nomerazao_".$numarq."\" type=\"hidden\" value=\"".$nomerazao."\">\n";
    echo "<INPUT id=\"sprestado_".$numarq."\" NAME=\"sprestado_".$numarq."\" type=\"hidden\" value=\"".$sprestado."\">\n";
    echo "<INPUT id=\"numnota_".$numarq."\" NAME=\"numnota_".$numarq."\" type=\"hidden\" value=\"".$numnota."\">\n";
    echo "<INPUT id=\"numserie_".$numarq."\" NAME=\"numserie_".$numarq."\" type=\"hidden\" value=\"".$numserie."\">\n";
    echo "<INPUT id=\"valservico_".$numarq."\" NAME=\"valservico_".$numarq."\" type=\"hidden\" value=\"".$valservico."\">\n";
    echo "<INPUT id=\"aliquota_".$numarq."\" NAME=\"aliquota_".$numarq."\" type=\"hidden\" value=\"".$aliquota."\">\n";
    echo "<INPUT id=\"total_".$numarq."\" NAME=\"total_".$numarq."\" type=\"hidden\" value=\"".$total."\">\n";
    echo "<INPUT id=\"nomecontri_".$numarq."\" NAME=\"nomecontri_".$numarq."\" type=\"hidden\" value=\"".$nomecontri."\">\n";
    echo "<INPUT id=\"fonecontri_".$numarq."\" NAME=\"fonecontri_".$numarq."\" type=\"hidden\" value=\"".$fonecontri."\">\n";
    echo "      <tr class=\"planilha\">\n";
    echo "        <td align=\"center\" width=\"6%\"><small>".$cnpj."</small></td>\n";
    echo "        <td align=\"center\" width=\"9%\"><small>".$inscricao."</small></td>\n";
    echo "        <td align=\"center\" width=\"15%\"><small>".substr($nomerazao,0,20)."</small></td>\n";
    echo "        <td align=\"center\" width=\"18%\"><small>".substr($sprestado,0,20)."</small></td>\n";
    echo "        <td align=\"center\" width=\"7%\"><small>".$numnota."</small></td>\n";
    echo "        <td align=\"center\" width=\"7%\"><small>".$numserie."</small></td>\n";
    echo "        <td align=\"center\" width=\"10%\"><small>R$ ".$valservico."</small></td>\n";
    echo "        <td align=\"center\" width=\"10%\"><small>".$aliquota."% </small></td>\n";
    echo "        <td align=\"center\" width=\"10%\"><small>R$ ".$total." </small></td>\n";
    echo "        <td align=\"center\" width=\"8%\"><input type=\"submit\" class=\"botao\" name=\"alterar_".$numarq."\" value=\"Alterar\"> <input type=\"submit\" class=\"botao\" name=\"excluir_".$numarq."\" value=\"Excluir\"></td>\n";
    echo "      </tr>\n";
  }
}
if(isset($numarq)&&$numarq>=1){
   if($cabec==true){
     echo "    </table>\n";
   }
}
if(isset($alter)){
  $plani=$planilha;
  echo "<script>document.form1.plani.value='".$plani."'</script>" ;
  $clquery->sql_query("issplanit inner join issplan on q20_planilha = q21_planilha","issplanit.*,issplan.q20_numpre","q21_planilha","q21_planilha=$planilha");
  $clquery->sql_record($clquery->sql);
  $result2 = $clquery->result;
  $numrows2 = $clquery->numrows;
  echo "  <table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" >\n";
  echo "      <tr>\n ";
  echo "        <td align=\"center\" bgcolor=\"#00436E\" width=\"6%\"><small><b>CNPJ</b></small></td>\n";
  echo "        <td align=\"center\" bgcolor=\"#00436E\" width=\"9\"><small><b>Inscrição</b></small></td>\n";
  echo "        <td align=\"center\" bgcolor=\"#00436E\" width=\"15%\"><small><b>Nome/Razão</b></small></td>\n";
  echo "        <td align=\"center\" bgcolor=\"#00436E\" width=\"18%\"><small><b>Serviço</b></small></td>\n";
  echo "        <td align=\"center\" bgcolor=\"#00436E\" width=\"7%\"><small><b>Nota</b></small></td>\n";
  echo "        <td align=\"center\" bgcolor=\"#00436E\" width=\"7%\"><small><b>Série</b></small></td>\n";
  echo "        <td align=\"center\" bgcolor=\"#00436E\" width=\"10%\"><small><b>Valor</b></small></td>\n";
  echo "        <td align=\"center\" bgcolor=\"#00436E\" width=\"10%\"><small><b>Aliquota</b></small></td>\n";
  echo "        <td align=\"center\" bgcolor=\"#00436E\" width=\"10%\"><small><b>Total</b></small></td>\n";
  echo "        <td align=\"center\" bgcolor=\"#00436E\" width=\"8%\"><small><b>Opções</b></small></td>\n";
  echo "      </tr>\n";
  $mostra_gravar = true;			    
  for($xi=0; $xi < $numrows2; $xi++){
    $clquery->sql_result($result2,$xi,"q21_cnpj"); 
    $cnpj = $clquery->resultado;
    $clquery->sql_result($result2,$xi,"q21_inscr"); 
    $inscricao = $clquery->resultado;
    $clquery->sql_result($result2,$xi,"q21_nome"); 
    $nomerazao = $clquery->resultado;
    $clquery->sql_result($result2,$xi,"q21_servico"); 
    $sprestado = $clquery->resultado;
    $clquery->sql_result($result2,$xi,"q21_nota"); 
    $numnota = $clquery->resultado;
    $clquery->sql_result($result2,$xi,"q21_serie"); 
    $numserie = $clquery->resultado;
    $clquery->sql_result($result2,$xi,"q21_valorser"); 
    $valservico = $clquery->resultado;
    $clquery->sql_result($result2,$xi,"q21_aliq"); 
    $aliquota = $clquery->resultado;
    $clquery->sql_result($result2,$xi,"q21_valor"); 
    $total = $clquery->resultado;


    $xi++;
    echo "<INPUT id=\"arq_".$xi."\" NAME=\"arq_".$xi."\" type=\"hidden\" value=\"".$xi."\">\n";
    echo "<INPUT id=\"cnpj_".$xi."\" NAME=\"cnpj_".$xi."\" type=\"hidden\" value=\"".$cnpj."\">\n";
    echo "<INPUT id=\"inscricao_".$xi."\" NAME=\"inscricao_".$xi."\" type=\"hidden\" value=\"".$inscricao."\">\n";
    echo "<INPUT id=\"nomerazao_".$xi."\" NAME=\"nomerazao_".$xi."\" type=\"hidden\" value=\"".$nomerazao."\">\n";
    echo "<INPUT id=\"sprestado_".$xi."\" NAME=\"sprestado_".$xi."\" type=\"hidden\" value=\"".$sprestado."\">\n";
    echo "<INPUT id=\"numnota_".$xi."\" NAME=\"numnota_".$xi."\" type=\"hidden\" value=\"".$numnota."\">\n";
    echo "<INPUT id=\"numserie_".$xi."\" NAME=\"numserie_".$xi."\" type=\"hidden\" value=\"".$numserie."\">\n";
    echo "<INPUT id=\"valservico_".$xi."\" NAME=\"valservico_".$xi."\" type=\"hidden\" value=\"".$valservico."\">\n";
    echo "<INPUT id=\"aliquota_".$xi."\" NAME=\"aliquota_".$xi."\" type=\"hidden\" value=\"".$aliquota."\">\n";
    echo "<INPUT id=\"total_".$xi."\" NAME=\"total_".$xi."\" type=\"hidden\" value=\"".$total."\">\n";
    echo "<INPUT NAME=\"numarq\" type=\"hidden\" value=\"".$xi."\">";
    echo "      <tr class=\"planilha\">\n";
    echo "        <td align=\"center\" width=\"6%\"><small>".$cnpj."</small></td>\n";
    echo "        <td align=\"center\" width=\"9%\"><small>".$inscricao."</small></td>\n";
    echo "        <td align=\"center\" width=\"15%\"><small>".substr($nomerazao,0,20)."</small></td>\n";
    echo "        <td align=\"center\" width=\"18%\"><small>".substr($sprestado,0,20)."</small></td>\n";
    echo "        <td align=\"center\" width=\"7%\"><small>".$numnota."</small></td>\n";
    echo "        <td align=\"center\" width=\"7%\"><small>".$numserie."</small></td>\n";
    echo "        <td align=\"center\" width=\"10%\"><small>R$ ".$valservico."</small></td>\n";
    echo "        <td align=\"center\" width=\"10%\"><small>".$aliquota."% </small></td>\n";
    echo "        <td align=\"center\" width=\"10%\"><small>R$ ".$total." </small></td>\n";
    echo "        <td align=\"center\" width=\"8%\"><input type=\"submit\" class=\"botao\" name=\"alterar_".$xi."\" value=\"Alterar\"> <input type=\"submit\" class=\"botao\" name=\"excluir_".$xi."\" value=\"Excluir\"></td>\n";
    echo "      </tr>\n";
    $xi--;
  }
    echo "    </table>\n";
}

?>
                          </td>
                        </tr>
                      </table>
                  </td>      
                </tr>   
                <tr> 
                  <td height="60" align="<?=$DB_align2?>">
                    <?=$DB_mens2?>
                  </td>
                </tr>
              </table>
           </td>
         </tr>
      </table>
    </td>
  </tr>
</table>
</center>
</form>
</body>
</html>
<?
if($mostra_gravar){
  echo "<SCRIPT>document.form1.grava.style.visibility='visible'</script>";
}