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
include("libs/db_stdlib.php");
include("libs/db_sql.php");
$result = db_query("SELECT distinct m_publico,m_arquivo,m_descricao
                   FROM db_menupref
                   WHERE m_arquivo = 'digitaissqn.php'
                   ORDER BY m_descricao
                   ");
db_fieldsmemory($result,0);
if($m_publico != 't'){
  if(!session_is_registered("DB_acesso"))
    echo "<script>location.href='centro_pref.php?".base64_encode('erroscripts=3')."'</script>";
}
mens_help();
$dblink="digitaissqn.php";
db_logs("","",0,"Digita Codigo do Contribuinte.");
$clquery = new cl_query;
parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));
postmemory($HTTP_POST_VARS);
$anocorreto = @$ano;
$mescorreto = @$mes;
$clquery->sql_query("db_confplan"," * ","","");
$clquery->sql_record($clquery->sql);
if(pg_numrows($clquery->result)==0){
  $w10_valor = 0;
}
db_fieldsmemory($clquery->result,0);


if(isset($alterplan) && $alterplan!=""){
  $clquery->sql_query("issplan","issplan.*","q20_mes","q20_planilha= $alterplan");
  $clquery->sql_record($clquery->sql);
  db_fieldsmemory($clquery->result,0);
  echo "<script>location.href=\"opcoesissqn.php?".base64_encode("modificando=true&alter=true&nomecontri=".$q20_nomecontri."&fonecontri=".$q20_fonecontri."&mes=".$altermes."&ano=".$alterano."&numcgm=".$q20_numcgm."&nomes=".$alternome."&inscricaow=".$q20_inscr."&planilha=".$alterplan)."\"</script>";
}  
$matri= array("1"=>"janeiro","2"=>"Fevereiro","3"=>"Março","4"=>"Abril","5"=>"Maio","6"=>"Junho","7"=>"Julho","8"=>"Agosto","9"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro");

if(isset($planilha)){
  if($inscricao!=0)
   $clquery->sql_query("issplan inner join issbase on q02_inscr = q20_inscr inner join cgm on z01_numcgm = q20_numcgm ","*",""," q20_planilha = $planilha");
  else
   $clquery->sql_query("issplan inner join cgm on z01_numcgm = q20_numcgm ","*",""," q20_planilha = $planilha");

  $clquery->sql_record($clquery->sql);

  $num=$clquery->numrows;
  db_fieldsmemory($clquery->result,0);
  
}

if(isset($comp_planilha)){
  //$clquery->sql_query("issplan inner join issbase on q02_inscr = q20_inscr inner join cgm on z01_numcgm = q20_numcgm ","*",""," q20_planilha = $comp_planilha");
  $clquery->sql_query("issplan inner join cgm on z01_numcgm = q20_numcgm ","*",""," q20_planilha = $comp_planilha");
 // die ($clquery->sql_query("issplan inner join cgm on z01_numcgm = q20_numcgm ","*",""," q20_planilha = $comp_planilha"));
  $clquery->sql_record($clquery->sql);
  $num=$clquery->numrows; 
  db_fieldsmemory($clquery->result,0);
}

?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>

function js_altera(planilha){
  document.form1.alterplan.value=planilha; 
  document.form1.submit();
}

function js_planilha(vplan){
  window.open('relatoriopdf.php?planilha='+vplan+'&contato=<?=@$nomecontri?>&telcontato=<?=@$fonecontri?>','','toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,directories=no,status=no');
}

function js_emiterecibo(){

  var dthoje = new Date('<?=date("Y")?>','<?=date("m")?>','<?=date("d")?>','24');
  var dt = new Date(document.form1.dtvenc_ano.value,document.form1.dtvenc_mes.value,document.form1.dtvenc_dia.value,'24');

  if(isNaN(dt)){

    alert('Data Inválida. Verifique');
    document.form1.dtvenc_dia.select();
    document.form1.dtvenc_dia.focus();
  
  } else {

    var dti = new Number(dt.getTime());
    var dtf = new Number(dthoje.getTime());
   
    if( dti < dtf ){
    
      alert('Data de Pagamento Inválida. Deverá ser data de hoje ou maior que hoje.');
    
    }else{

      var num = document.form1.nump.value;
      var soma=new Number();
      var ma="";
      var qplan="";
      var virgula="";
      var quantas = 0;
      for(i=0;i<num;i++){
        if(eval("document.form1.checa_"+i)){  
          if(eval("document.form1.checa_"+i+".checked")){  
            soma  += new Number(eval("document.form1.checa_"+i+".value"));
            qplan += virgula+eval("document.form1.plan_"+i+".value");
            virgula = "#";
            quantas += 1;
          }  
        }
      }



      //if(soma<<?=$w10_valor?>){
     //   if(quantas>1)
    //      alert("Valor mínimo do recibo é <?=db_formatar($w10_valor,'f')?>. Os valores destas Planilhas serão acumulados na próxima retenção.");
   //     else
   //       alert("Valor mínimo do recibo é <?=db_formatar($w10_valor,'f')?>. O valor desta Planilha será acumulado na próxima retenção.");
  //    }else{
   //      var retorno = confirm('Confirma emissão do recibo?');
   //   }
      var retorno = confirm('Confirma emissão do recibo?');
      if(retorno==true){
        jan = window.open('recibopdf.php?qplan='+qplan+'&dtpaga='+document.form1.dtvenc_ano.value+"-"+document.form1.dtvenc_mes.value+"-"+document.form1.dtvenc_dia.value,'','toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,directories=no,status=no');
        jan.focus();
        //location.href="index.php";
      }
    }
  }
}

function js_checkpaga(obj){
  var valor = Number(obj.value);
  var stot = new String(document.getElementById("totrec").innerHTML);
  stot = stot.replace(".","");
  stot = stot.replace(".","");
  stot = stot.replace(".","");
  stot = stot.replace(",","");
  stot = stot / 100;
  tot = new Number(stot);
  if(obj.checked==false){  
    tot = tot - valor;
  }else{
    tot = tot + valor;
  }
  var xtot = new String(tot.toFixed(2));
  document.getElementById("totrec").innerHTML = tot.toFixed(2);
}

</script>
<style type="text/css">
<?
db_estilosite();
?>
.planilha {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 8px;
        color: white;
}
</style>
<link href="config/estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
<?//mens_div();?>
<form name="form1" method="post" action="opcoesissqn001.php">
<center>
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr align="center">
    <td>
     <table width="100%" border="0" cellspacing="0" class="texto">
    <?

    if(isset($comp_planilha) && $comp_planilha != ""){
       $clquery->sql_query("issplanit"," distinct q21_inscr,q21_cnpj,q21_nome,q21_servico,sum(q21_valor) as q21_valor",""," q21_planilha = $comp_planilha group by q21_cnpj,q21_inscr,q21_nome,q21_servico");
       $clquery->sql_record($clquery->sql);
       $result    = $clquery->result;
       $numrows   = $clquery->numrows;
       $numfields = $clquery->numfields;
         echo "<table width=\"100%\" border=\"1\"cellpadding=\"2\" cellspacing=\"0\" class='texto'>";
         echo "  <tr>";
         echo "    <td align=\"center\" bgcolor=\"#00436E\"><b><font color=\"#FFFFFF\">CNPJ</font></b></td> ";
         echo "    <td align=\"center\" bgcolor=\"#00436E\"><b><font color=\"#FFFFFF\">INSCRIÇÃO</font></b></td> ";
         echo "    <td align=\"center\" bgcolor=\"#00436E\"><b><font color=\"#FFFFFF\">NOME/RAZÃO SOCIAL</font></b></td> ";
         echo "    <td align=\"center\" bgcolor=\"#00436E\"><b><font color=\"#FFFFFF\">SERVIÇO PRESTADO</font></b></td> ";
         echo "    <td align=\"center\" bgcolor=\"#00436E\"><b><font color=\"#FFFFFF\">TOTAL</font></b></td> ";
         echo "    <td align=\"center\" bgcolor=\"#00436E\"><b><font color=\"#FFFFFF\">EMITE</font></b></td> ";
         echo "  </tr>";
       for($x=0; $x < $numrows; $x++){
         echo "  <tr>";
         db_fieldsmemory($result,$x);
         echo "    <td align=\"center\"><b>$q21_cnpj</b></td> ";
         echo "    <td align=\"center\"><b>".@$q21_inscr."</b></td> ";
         echo "    <td align=\"center\"><b>$q21_nome</b></td> ";
         echo "    <td align=\"center\"><b>$q21_servico</b></td> ";
         echo "    <td align=\"center\"><b>$q21_valor</b></td> ";
         echo "    <td width=\"15%\" nowrap align=\"center\" valign=\"top\" ><input class=\"botao\" type=\"button\" name=\"tiras_$x\" value=\"Comprovante\" onclick=\"js_tiras('$comp_planilha','$q21_inscr','$q21_cnpj')\" ></td>";
         echo "</tr>";
       }
       ?>
       <tr height="50">
        <td colspan="6" align="center">
         <input type="button" value="Voltar" onclick="history.back()">
        </td>
       </tr>
       <?
     }else{
       ?>
           <tr><td><input type="hidden" name="alterplan" value=""></td></tr>
           <tr>
             <td width="21%" align="right"><font color="<?=$w01_corfontesite?>">Nome:</font></td>
             <td width="79%">&nbsp;
               <?=$z01_nome?>
               <input type="hidden" name="alternome" value="<?=$z01_nome?>">
             </td>
           </tr>
           <tr>
             <td align="right"><font color="<?=$w01_corfontesite?>">Compet&ecirc;ncia:</font></td>
             <td>&nbsp;
               <?=$q20_ano?>
               <input type="hidden" name="alterano" value="<?=$q20_ano?>">
               <?=$matri[$q20_mes]?>
               <input type="hidden" name="altermes" value="<?=$q20_mes?>">
             </td>
           </tr>
           <tr>
             <td align="right">&nbsp;</td>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td align="right" valign="top">&nbsp;<font color="<?=$w01_corfontesite?>" face="Arial, Helvetica, sans-serif">Observa&ccedil;&atilde;o:</font></td>
             <td>
               <?
               
               if($q20_numpre != 0){
               ?>
               <font color="<?=$w01_corfontesite?>" face="Arial, Helvetica, sans-serif">
               Esta Planilha já possui recibo emitido, portanto sua alteração
               não é permitida.
               O recibo poder&aacute; ser emitido at&eacute; a data
               de seu vencimento.<br>
               </font>
               <?
               }else{
               ?>
               <font color="<?=$w01_corfontesite?>" face="Arial, Helvetica, sans-serif">Após
               a Emissão do recibo para pagamento o sistema bloqueia
               a planilha, não permitindo alteração da mesma. Para
               correção da planilha após e emissão do bloqueto, entre
               em contato com a prefeitura.<br>
               </font>
               <?
               }
               ?>
             </td>
           </tr>
           <tr>
             <td align="center">&nbsp;</td>
             <td align="left"><font color="<?=$w01_corfontesite?>" face="Arial, Helvetica, sans-serif">Entre
               em contato com a Prefeitura para maiores esclarecimentos.</font>
             </td>
           </tr>
           <tr>
             <td colspan="2" align="center">&nbsp;</td>
           </tr>
           <tr>
             <td colspan="2" >
       <?
       $clquery->sql_query("issplan","q20_planilha, q20_ano, q20_mes","q20_mes","q20_inscr= $inscricao and q20_planilha = $planilha");
       $clquery->sql_record($clquery->sql);
       $result = $clquery->result;
       $numrows=$clquery->numrows;
       $numfields=$clquery->numfields;
         echo "<input name=\"comp_planilha\" value=\"\" type=\"hidden\"";
         echo "<table width=\"100%\" border=\"1\"cellpadding=\"0\" cellspacing=\"0\" >";
         echo "  <tr>";
         echo "    <td align=\"center\" width=\"15%\" bgcolor=\"#cccccc\" ><small><b>Planilha</b></small></td> ";
         echo "    <td align=\"center\" width=\"15%\" bgcolor=\"#cccccc\" ><small><b>Ano</b></small></td> ";
         echo "    <td align=\"center\" width=\"15%\" bgcolor=\"#cccccc\" ><small><b>Mês</b></small></td> ";
         echo "    <td align=\"center\" width=\"15%\" bgcolor=\"#cccccc\" ><small><b>Emite</b></small></td> ";
         echo "    <td align=\"center\" width=\"15%\" bgcolor=\"#cccccc\" ><small><b>Valor</b></small></td> ";
         echo "    <td align=\"center\" width=\"10%\" bgcolor=\"#cccccc\" ><small><b>Selecionados</b></small></td> ";
         echo "  </tr>";
       $tvalo = 0;
       for($x=0; $x < $numrows; $x++){
         $clquery->sql_result($result,$x,0);
         $planilha = $clquery->resultado;

         $re=db_query("select q20_numpre from issplan where q20_planilha=$planilha and q20_planilha is not null");
         $numpr=pg_result($re,0,0);
         echo "  <tr>";
         for($i=0; $i<$numfields; $i++){
           $clquery->sql_result($result,$x,$i);
           $col = $clquery->resultado;
           echo " <td align=\"center\"><small>".$col."</small></td>";
         }
         echo "<td nowrap width=\"15%\" valign=\"top\" align=\"center\"  ><input type=\"button\" class=\"botao\" name=\"planilha_$x\" value=\"Planilha\" onclick=\"js_planilha('$planilha')\" ><input type=\"button\" class=\"botao\" name=\"tiras_$x\" value=\"Comprovante\" onclick=\"document.form1.comp_planilha.value = '$planilha';document.form1.submit()\" ></td>";

         $resu=db_query("select sum(q21_valor) from issplan inner join issplanit on q20_planilha=q21_planilha where q20_planilha=$planilha group by q20_planilha");
         if(pg_numrows($resu) > 0){
           $valo=pg_result($resu,0,0);
           echo "    <td align=\"right\"><small>&nbsp;".db_formatar($valo,'f')."</small></td>";
           echo "    <td align=\"center\"><small><input type=\"hidden\" value='".$planilha."' name=\"plan_$x\" ><input type=\"checkbox\" value='".$valo."'  onclick='js_checkpaga(this)' name=\"checa_$x\" checked></small></td>";
           echo "  </tr>";
           $tvalo += $valo;
         }else{
           echo "    <td align=\"center\"><small>&nbsp;</small></td>";
           echo "    <td align=\"center\"><small>&nbsp;</small></td>";
           echo "  </tr>";
         }
       }

?>
<tr>
  <td align="right" colspan="4"><b>Total à Pagar</b></td>
  <td align="right">&nbsp;<?=db_formatar($tvalo,'f')?></td>
  <td align="center">&nbsp;</td>
</tr>
<tr>
  <td align="right" colspan="4"><b>Valor do Recibo</b></td>
  <td align="right" id="totrec"><?=db_formatar($tvalo,'f')?></td>
  <td align="center">&nbsp;</td>
</tr>

</table>
         <input type="hidden" name="nump" value="<?=@$numrows?>">
          </td>
        </tr>
    </table>
  </td>
</tr>
<tr>
  <td colspan="2" align="center">
    Data Pagamento:
    <?
    $mescorreto += 1;
    if($mescorreto>12){
      $mescorreto = 1;
      $anocorreto += 1;
    }
    if(($anocorreto < date("Y") ) || ($anocorreto==date("Y") && $mescorreto < date("m"))){

      $mescorreto = date("m");
      $anocorreto = date("Y");
      if($w10_dia<date("d"))
        $w10_dia = date("d");

    }else{
      if( ($anocorreto==date("Y") && $mescorreto == date("m") &&  $w10_dia<date("d"))){
        $w10_dia = date("d");
      }
    }

    db_data("dtvenc",db_formatar($w10_dia,'s','0',2),db_formatar($mescorreto,'s','0',2),$anocorreto);
    ?>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input class="botao" name="recibo" type="button" id="recibo" value="Recibo Pagamento"  onclick="js_emiterecibo();">
    <input class="botao" name="novaplan" type="button" id="novaplan" value="Nova Planilha"  onclick="js_novaplan();">

  </td>
</tr>
<tr>
  <td height="60" align="" colspan="2">&nbsp;
  </td>
</tr>
<? if (@$inscricaow==""){
	$inscricaow = 0;
} 
?>
 	<input name="ano" type="hidden" value="<?=$ano?>">
    <input name="mes" type="hidden" value="<?=$mes?>">
    <input name="numcgm" type="hidden" value="<?=$numcgm?>">
    <input name="inscricaow" type="hidden" value="<?=@$inscricaow?>">
    <input name="nomecontri" type="hidden" value="<?=@$nomecontri?>">
    <input name="fonecontri" type="hidden" value="<?=@$fonecontri?>">
<?
}
?>
</center>
</form>
</body>
</html>
<script>
function js_novaplan(){
 mes =document.form1.mes.value;
 ano =document.form1.ano.value;
 numcgm =document.form1.numcgm.value;
 inscricaow =document.form1.inscricaow.value;
 nomecontri =document.form1.nomecontri.value;
 fonecontri =document.form1.fonecontri.value;
 location.href="opcoesissqn.php?nova=true&mes="+mes+"&ano="+ano+"&numcgm="+numcgm+"&inscricaow="+inscricaow;
}

function js_planilha(planilha){
  window.open('relatoriopdf.php?planilha='+planilha,'','toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,directories=no,status=no');
}  
function js_tiras(planilha,inscr,cnpj){
  window.open('tiras.php?planilha='+planilha+'&cnpjprestador='+cnpj+'&q21_inscr='+inscr+'&contato=<?=@$q20_nomecontri?>&telcontato=<?=$q20_fonecontri?>','','toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,directories=no,status=no');
} 
</script>