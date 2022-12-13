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

include("libs/db_stdlib.php");
include("libs/db_sql.php");
include("libs/db_conecta.php");
mens_help();
$dblink="index.php";
db_logs("","",0,"Digita Codigo do Contribuinte.");
db_mensagem("contribuinte_cab","contribuinte_rod");

postmemory($HTTP_POST_VARS);
  
  $matri= array("1"=>"janeiro","2"=>"Fevereiro","3"=>"Março","4"=>"Abril","5"=>"Maio","6"=>"Junho","7"=>"Julho","8"=>"Agosto","9"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro");
  $mesx= $matri[$mes];
  $clquery = new cl_query;

if(isset($alter)||isset($nova)){


}else{
  $cpf =  str_replace(".","",$cpf);
  $cpf =  str_replace("-","",$cpf);
  $cgc =  str_replace("-","",$cgc);
  $cgc =  str_replace(".","",$cgc);
  $cgc =  str_replace("/","",$cgc);
  $cgc =  str_replace(",","",$cgc);


  if($cpf!=""){
    $clquery->sql_query("db_cgmcpf","z01_cpf,z01_numcgm","z01_cpf"," z01_cpf = $cpf");
    $clquery->sql_record($clquery->sql);
    $num=$clquery->numrows;
    if($num==0){
      redireciona("digitaissqn.php");
    }else{
      $clquery->sql_query("cgm","z01_nome","z01_nome"," z01_numcgm = $z01_numcgm");
      $clquery->sql_record($clquery->sql);
      db_fieldsmemory($clquery->result,0);
    } 
  }else{
    if($cgc!=""){
      $clquery->sql_query("db_cgmcgc","z01_cgc,z01_numcgm","z01_cgc"," z01_cgc = $cgc");
      $clquery->sql_record($clquery->sql);
      $num=$clquery->numrows;

      if($num==0){
        redireciona("digitaissqn.php");
      }else{
        $clquery->sql_query("cgm","z01_nome","z01_nome"," z01_numcgm = $z01_numcgm");
        $clquery->sql_record($clquery->sql);
        db_fieldsmemory($clquery->result,0);
  
      }  
    }else{
      if($inscricaow!=""){
  
        $clquery->sql_query("issbase"," q02_numcgm ","","q02_inscr  = $inscricaow");
        $clquery->sql_record($clquery->sql);
        $num=$clquery->numrows;
  
        if($num==0){
          redireciona("digitaissqn.php");
        }else{
          $z01_numcgm=pg_result($clquery->result,0,0);
          
          $clquery->sql_query("cgm","z01_nome","z01_nome"," z01_numcgm = $z01_numcgm");
          $clquery->sql_record($clquery->sql);
          db_fieldsmemory($clquery->result,0);
        }  
      } 
    }
  }

  if(isset($primeiravez)){

    $clquery->sql_query("issplan","q20_mes, q20_ano","q20_mes"," q20_ano = $ano and q20_mes=$mes and q20_numcgm= $z01_numcgm");
    $clquery->sql_record($clquery->sql);

      $num=$clquery->numrows;
    if($num!=0){
      redireciona("planilha.php?inscricaow=".$inscricaow."&mesx=".$mesx."&mes=".$mes."&ano=".$ano."&numcgm=".$z01_numcgm."&z01_nome=".$nome);
    }
  }
  if(isset($grava)){
    if($modificando==true){
       $sqlx1="delete from issplanit where q21_planilha=$plani";
       $sqlx2="delete from issplan where q20_planilha=$plani";
      
       pg_exec($sqlx1)or die($sqlx1);
       pg_exec($sqlx2)or die($sqlx2);
       
       $planilha=$plani;

    }else{
      $clquery->sql_query("issplan","max(q20_planilha) + 1");
      $clquery->sql_record($clquery->sql);
      $planilha=pg_result($clquery->result,0,0);
      $planilha = $planilha==""?"1":$planilha;
    }
    
    $clquery->sql_insert(" issplan ","$planilha,$z01_numcgm,$inscricaow, $ano, $mes");

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
	$c = "liquota_".$i;
	$tliquota = $$c;
	$c = "total_".$i;
	$ttotal = $$c;
         $clquery->sql_insert("issplanit","$planilha,'$tcnpj',$tinscricao, '$tnomerazao','$tsprestado','$tnumnota','$tnumserie','$tvalservico','$tliquota', '$ttotal'");
      }
    }
     redireciona("digitaissqn.php");
  
  }
}
?>

<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
 
<script>
function js_iniciar() {
  if(document.form1)
    document.form1.codigo_cgm.focus();
}
window.onload = js_iniciar;
</script>
<script>
function js_restaurafundo(obj) {
  document.getElementById(obj.id).style.backgroundColor = '#00436E';
}
function js_trocafundo(obj){
  document.getElementById(obj.id).style.backgroundColor = '#0065A8';
}
function js_link(arq) {
  location.href = arq;
}


function veri(){
  var tam="";
  var fim ="";

  vals=document.form1.valservico.value;
  liquota=document.form1.liquota.value;
 
  if(vals.indexOf(".")!=-1){
    partes=vals.split(".");
    parte1=partes[0];
    parte2=partes[1];
    vals=parte1+parte2;
    if(parte1==0){
      if(parte2!=0&&liquota!=0){
        desc =(vals*liquota)/100;
        tot=desc;
        tot=Math.floor(tot);
        document.form1.total.value="0",+tot;
      }else{
        document.form1.total.value="0";
      }
    }else{
      if(vals!=0&&liquota!=0){
        desc =(vals*liquota)/100;
        valt=desc;
        valto=Math.floor(valt);
        document.form1.ttt.value=valto;
        valorto=document.form1.ttt.value;
        tam=valorto.split("");
        num=tam.length;
        if(num >= 2){
          fim=valorto.slice(-2); 
          inicio=valorto.slice(0,-2); 
          if(inicio==""){
            valortotal=inicio+"0."+fim;
          }else{
            valortotal=inicio+"."+fim;
          }
          document.form1.total.value=valortotal;
        }
      }else{ 
        if(vals!=0){
          fim=vals.slice(-2); 
          inicio=vals.slice(0,-2); 
          valortotal=inicio+"."+fim;
          document.form1.total.value=valortotal;
        }else{
          document.form1.total.value=0;
        }
      } 
    }
  }
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
  jliquota=document.form1.liquota.value;
  

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





</script>
<style type="text/css">
.bordas {
	border: 1px solid white;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #666666;
	background-color: #00436E;
	cursor: hand;	
}
.linksmenu {
	font-size: 12px;
	font-weight: bold;
	color: #FFFFFF;
	text-decoration: none;
}
.links {
	font-size: 12px;
	font-weight: bold;
	color: #FFFFFF;
	text-decoration: none;
}
a.links:hover {
	font-size: 12px;
	font-weight: bold;
	color: #CCCCCC;
	text-decoration: underline;
}
body {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 13px;
	color: #000000;	
}
input {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #000000;
	background-color: #FFFFFF;
	height: 16px;
	border: 1px solid #00436E;
}
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#0F6BAA" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="" <? mens_OnHelp() ?>>
<form name="form1" method="post" action="opcoesissqn.php">
<?
mens_div();
?>
<center>
<table width="766" border="0" cellpadding="0" cellspacing="0" bgcolor="#0F6BAA">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="33%" align="left" valign="top"><img src="imagens/topo1_O.gif" width="256" height="81"></td>
          <td width="21%" align="left" valign="top"><img src="imagens/topo2_O.gif" width="163" height="81"></td>
            <td width="46%" align="left" valign="top"><img src="imagens/topo3.jpg" width="347" height="81"></td>
        </tr>
      </table></td>
  </tr>
  <tr>
      <td class="bordas" nowrap>         
	   &nbsp;<a href="index.php" class="links">Principal &gt;</a> 
		&nbsp;<font class="links">Contribuinte &gt;</font>
      </td>
  </tr>
  <tr>
    <td align="left" valign="top">
	  <table width="100%" height="313" border="0" cellpadding="0" cellspacing="0">
      <tr>
            <td width="90" align="left" valign="top"> 
              <img src="imagens/linha.gif" width="90" height="1" border="0"> 
              <table width="97%" cellpadding="0" cellspacing="0" border="0">
          <?            
		  	$result_dtw = pg_exec("SELECT * FROM db_menupref WHERE m_ativo = '1'");
	        $numrows_dtw = pg_numrows($result_dtw);
            for($i = 0;$i < $numrows_dtw;$i++) {
              $arquivo = pg_result($result_dtw,$i,"m_arquivo");
	          $nome = substr($arquivo,0,strlen($arquivo) - 4);
	          $imgs = split(";",pg_result($result_dtw,$i,"m_imgs"));
	          $descricao = pg_result($result_dtw,$i,"m_descricao");
              ?>
              <tr> 			    
                <td id="coluna<?=$i?>" align="center" height="25" class="bordas" onClick="js_link('<?=$arquivo?>')" onMouseOut="js_restaurafundo(this)" onMouseOver="js_trocafundo(this)">
				  <a class="linksmenu" href="<?=$arquivo?>" >
				    <?=$descricao?>
				  </a>
				  </td>				
              </tr>
              <?
		    }
          ?>
          </table>
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
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <input name="cpf" type="hidden" value="<?=@$cpf?>">
                      <input name="mes" type="hidden" value="<?=@$mes?>">
                      <input name="ano" type="hidden" value="<?=$ano?>">
                      <input name="cgc" type="hidden" value="<?=@$cgc?>">
                      <input name="inscricaow" type="hidden" value="<?=@$inscricaow?>">
		      <input name="modificando" type="hidden" value="<?=@$modificando?>">
		      <input name="plani" type="hidden" value="<?=@$plani?>">
                      <input name="z01_nome" type="hidden" value="<?=@$z01_nome?>">
		      <input name="z01_numcgm" type="hidden" value="<?=isset($numcgm)?$numcgm:@$z01_nuncgm?>">
                      <input name="ttt" type="hidden">
                      <tr> 
                        <td colspan="2">      
                          <table>   
                            <tr>
                              <td width="19%" colspan="2" nowrap><small><b>Nome ou Raz&atilde;o Social:</b><?=isset($nome)?$nome:@$z01_nome?></small> </td>
                              <td width="19%" nowrap><small><b>Competência:</b><?=$mesx?> de <?=$ano?></small> </td>
                            </tr> 
                          </table>
                        </td>  
                      </tr>
                      <tr>
                        <td> 
                          <table>
                            <tr>    
                              <td><b><small>CNPJ</small></b></td>
                              <td width="19%"><b><small>Inscri&ccedil;&atilde;o:</small></small></b> </td>
                            </tr>  
                            <tr>  
                              <td><input name="cnpj" type="text" id="cnpj" maxlength="14" ></td>
                              <td ><input name="inscricao" type="text"></td>
                           </tr>
                          </table> 
                        </td>
                      </tr>
                       <tr> 
                         <td><b><small>Nome ou Razão Social:</small></b></td>
                         <td><input name="nomerazao" type="text" maxlength="30" ></td>
                       </tr>
                       <tr> 
                         <td><b><small>Servi&ccedil;o Prestado:</small></b></td>
                         <td><input name="sprestado" type="text" id="sprestado"  maxlength="40"></td>
                       </tr>
                       <tr>  
                         <td><b><small>Numero da Nota:</small></b></td>
                         <td nowrap><input name="numnota" type="text" id="numnota" maxlength="10" size="10">
                           <b><small>Numero da S&eacute;rie:</small></b></td>
                         <td><input name="numserie" type="text" id="numserie" size="10" maxlength="5" ></td>
                       </tr>
                       <tr> 
                         <td>
                           <table border="0">
                             <tr>
                               <td width="85%"><b><small>Valor do Servi&ccedil;o:</small></b></td>
                               <td><small>R$</small></td>
                             </tr>
                           </table>
                         </td>
                         <td>
                           <input name="valservico" type="text"   id="valservico" OnBlur="veri()"  size="10">
                           <b><small>Liquota:</small></b>
                         </td>  
                         <td>
                           <select name="liquota" id="liquota" onchange="veri()">
                             <option value="0">0%</option>
                             <option value="1" <?=(isset($liquota)&&$liquota=="1"?"selected":"")?>>1%</option>
                             <option value="2" <?=(isset($liquota)&&$liquota=="2"?"selected":"")?>>2%</option>
                             <option value="3" <?=(isset($liquota)&&$liquota=="3"?"selected":"")?>>3%</option>
                             <option value="4" <?=(isset($liquota)&&$liquota=="4"?"selected":"")?>>4%</option>
                             <option  value="5" <?=(isset($liquota)&&$liquota=="5"?"selected":"")?>>5%</option>
                             <option value="6" <?=(isset($liquota)&&$liquota=="6"?"selected":"")?>>6%</option>
                             <option value="7" <?=(isset($liquota)&&$liquota=="7"?"selected":"")?>>7%</option>
                             <option value="8" <?=(isset($liquota)&&$liquota=="8"?"selected":"")?>>8%</option>
                             <option value="9" <?=(isset($liquota)&&$liquota=="9"?"selected":"")?>>9%</option>
                             <option value="10" <?=(isset($liquota)&&$liquota=="10"?"selected":"")?>>10%</option>
                           </select>
                         </td>
                       </tr>
                       <tr> 
                         <td>
                           <table border="0">
                             <tr>
                               <td width="85%"><b><small>Valor Total:</small></b></td>
                               <td><small>R$</small></td>
                             </tr>
                           </table>
                         </td>
                         <td><input name="total" type="text" readonly></td>
                       </tr>
                       <tr> 
                         <td colspan="2" >
                            <input name="guarda" type="submit"  value="Lança Valor" onclick="return js_vericampos() ">
                            <input name="grava" type="submit" id="grava" value="Gravar Planilha" style="visibility:'hidden'">
                         </td>
                       </tr>
                       <tr>
                         <td colspan="4">


<?
if(!isset($alter)){


if(isset($guarda)){
  $numarq=$numarq+1;  
}
if(!isset($numarq)){
   $numarq=0;
}
echo "<INPUT NAME=\"numarq\" type=\"hidden\" value=\"".$numarq."\">";

$imprime_gravar=false;
if($numarq>=1){
      echo "  <table width=\"99%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" >\n";
      echo "      <tr>\n ";
      echo "        <td width=\"6%\"><small><b>CNPJ</b></small></td>\n";
      echo "        <td width=\"9%\"><small><b>Inscrição</b></small></td>\n";
      echo "        <td width=\"15%\"><small><b>Nome/Razão</b></small></td>\n";
      echo "        <td width=\"18%\"><small><b>Serviço</b></small></td>\n";
      echo "        <td width=\"7%\"><small><b>Nota</b></small></td>\n";
      echo "        <td width=\"7%\"><small><b>Série</b></small></td>\n";
      echo "        <td width=\"10%\"><small><b>Valor</b></small></td>\n";
      echo "        <td width=\"10%\"><small><b>Aliquota</b></small></td>\n";
      echo "        <td width=\"10%\"><small><b>Total</b></small></td>\n";
      echo "        <td width=\"8%\"><small><b>Opções</b></small></td>\n";
      echo "      </tr>\n";
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
  $liquota2 = "liquota_".$i; 
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
      echo "<INPUT id=\"liquota_".$i."\" NAME=\"liquota_".$i."\" type=\"hidden\" value=\"".$$liquota2."\">\n";
      echo "<INPUT id=\"total_".$i."\" NAME=\"total_".$i."\" type=\"hidden\" value=\"".$$total2."\">\n";
      echo "      <tr>\n";
      echo "        <td width=\"6%\"><small>".$$cnpj2."</small></td>\n";
      echo "        <td width=\"9%\"><small>".$$inscricao2."</small></td>\n";
      echo "        <td width=\"15%\"><small>".$$nomerazao2."</small></td>\n";
      echo "        <td width=\"18%\"><small>".$$sprestado2."</small></td>\n";
      echo "        <td width=\"7%\"><small>".$$numnota2."</small></td>\n";
      echo "        <td width=\"7%\"><small>".$$numserie2."</small></td>\n";
      echo "        <td width=\"10%\"><small>R$ ".$$valservico2."</small></td>\n";
      echo "        <td width=\"10%\"><small>".$$liquota2."% </small></td>\n";
      echo "        <td width=\"10%\"><small>R$ ".$$total2." </small></td>\n";
      echo "        <td width=\"8%\"><input type=\"submit\" name=\"alterar_".$i."\" value=\"Alterar\"> <input type=\"submit\" name=\"excluir_".$i."\" value=\"Excluir\"></td>\n";
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
      echo "<script>document.form1.liquota.value='".$$liquota2."'</script>" ;
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
      echo "<INPUT id=\"liquota_".$numarq."\" NAME=\"liquota_".$numarq."\" type=\"hidden\" value=\"".$liquota."\">\n";
      echo "<INPUT id=\"total_".$numarq."\" NAME=\"total_".$numarq."\" type=\"hidden\" value=\"".$total."\">\n";
      echo "      <tr>\n";
      echo "        <td width=\"6%\"><small>".$cnpj."</small></td>\n";
      echo "        <td width=\"9%\"><small>".$inscricao."</small></td>\n";
      echo "        <td width=\"15%\"><small>".$nomerazao."</small></td>\n";
      echo "        <td width=\"18%\"><small>".$sprestado."</small></td>\n";
      echo "        <td width=\"7%\"><small>".$numnota."</small></td>\n";
      echo "        <td width=\"7%\"><small>".$numserie."</small></td>\n";
      echo "        <td width=\"10%\"><small>R$ ".$valservico."</small></td>\n";
      echo "        <td width=\"10%\"><small>".$liquota."% </small></td>\n";
      echo "        <td width=\"10%\"><small>R$ ".$total." </small></td>\n";
      echo "        <td width=\"8%\"><input type=\"submit\" name=\"alterar_".$numarq."\" value=\"Alterar\"> <input type=\"submit\" name=\"excluir_".$numarq."\" value=\"Excluir\"></td>\n";
      echo "      </tr>\n";
}
}
if($numarq>=1){
      echo "    </table>\n";
}
if(isset($alter)){
  $plani=$planilha;
  echo "<script>document.form1.plani.value='".$plani."'</script>" ;

  $clquery->sql_query("issplanit","*","q21_planilha","q21_planilha=$planilha");
  $clquery->sql_record($clquery->sql);
  $result2 = $clquery->result;
  $numrows2 = $clquery->numrows;
  

    echo "  <table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" >\n";
    echo "      <tr>\n ";
    echo "        <td width=\"6%\"><small><b>CNPJ</b></small></td>\n";
    echo "        <td width=\"9\"><small><b>Inscrição</b></small></td>\n";
    echo "        <td width=\"15%\"><small><b>Nome/Razão</b></small></td>\n";
    echo "        <td width=\"18%\"><small><b>Serviço</b></small></td>\n";
    echo "        <td width=\"7%\"><small><b>Nota</b></small></td>\n";
    echo "        <td width=\"7%\"><small><b>Série</b></small></td>\n";
    echo "        <td width=\"10%\"><small><b>Valor</b></small></td>\n";
    echo "        <td width=\"10%\"><small><b>Aliquota</b></small></td>\n";
    echo "        <td width=\"10%\"><small><b>Total</b></small></td>\n";
    echo "        <td width=\"8%\"><small><b>Opções</b></small></td>\n";
    echo "      </tr>\n";

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
    $clquery->sql_result($result2,$xi,"q21_liq"); 
    $liquota = $clquery->resultado;
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
    echo "<INPUT id=\"liquota_".$xi."\" NAME=\"liquota_".$xi."\" type=\"hidden\" value=\"".$liquota."\">\n";
    echo "<INPUT id=\"total_".$xi."\" NAME=\"total_".$xi."\" type=\"hidden\" value=\"".$total."\">\n";
    echo "<INPUT NAME=\"numarq\" type=\"hidden\" value=\"".$xi."\">";
   
    echo "      <tr>\n";
    echo "        <td width=\"6%\"><small>".$cnpj."</small></td>\n";
    echo "        <td width=\"9%\"><small>".$inscricao."</small></td>\n";
    echo "        <td width=\"15%\"><small>".$nomerazao."</small></td>\n";
    echo "        <td width=\"18%\"><small>".$sprestado."</small></td>\n";
    echo "        <td width=\"7%\"><small>".$numnota."</small></td>\n";
    echo "        <td width=\"7%\"><small>".$numserie."</small></td>\n";
    echo "        <td width=\"10%\"><small>R$ ".$valservico."</small></td>\n";
    echo "        <td width=\"10%\"><small>".$liquota."% </small></td>\n";
    echo "        <td width=\"10%\"><small>R$ ".$total." </small></td>\n";
    echo "        <td width=\"8%\"><input type=\"submit\" name=\"alterar_".$xi."\" value=\"Alterar\"> <input type=\"submit\" name=\"excluir_".$xi."\" value=\"Excluir\"></td>\n";
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
<!-- InstanceEnd --></html>