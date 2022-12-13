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
include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
if($opcao == 'n'){
  $arquivosel = 'digitacontribuinte.php';
}elseif($opcao == 'm'){
  $arquivosel = 'digitamatricula.php';
}elseif($opcao == 'i'){
  $arquivosel = 'digitainscricao.php';
}  
$result = db_query("SELECT distinct m_publico,m_arquivo,m_descricao
                       FROM db_menupref 
                 WHERE m_arquivo = '$arquivosel'
                 ORDER BY m_descricao
                 ");
db_fieldsmemory($result,0);
if($m_publico != 't'){
  if(!session_is_registered("DB_acesso"))
    echo"<script>location.href='index.php?".base64_encode("erroscripts=3")."'</script>";
}
mens_help();
if(isset($HTTP_POST_VARS["numpre_unica"]) && $HTTP_POST_VARS["numpre_unica"] != "") {
//db_msgbox("sdfgdfgsfdgsdfgsfdgsdgsdf");
  $matric = $ver_matric;
  include("cai3_gerfinanc033.php");
  exit;
} 
if(isset($HTTP_POST_VARS["emiterecibo"])) {
  include("recibo.php"); 
///////////////////////////////////////////////
} else {
///////////////////////////////////////////////
//atualiza o issqn variavel
if(isset($HTTP_POST_VARS["calculavalor"])) {
  $vt = $HTTP_POST_VARS;
  $tam = sizeof($vt);
  reset($vt);
  $j = 0;
  for($i = 0;$i < $tam;$i++) {
    if(db_indexOf(key($vt) ,"VAL") > 0) {
      $valores[$j++] = str_replace(",",".",$vt[key($vt)]);
    }
    next($vt);
  }
  $j = 0;
  reset($vt);
  for($i = 0;$i < $tam;$i++) {
    if(db_indexOf(key($vt),"CHECK") > 0)
       $numpres[$j++] = $vt[key($vt)];
     next($vt);
  }
  if(sizeof($valores) != sizeof($numpres)) {
    echo "Matriz inválida!\n";
     exit;
  }
  $tam = sizeof($valores);
  db_query("BEGIN");
  for($i = 0;$i < $tam;$i++) {
      $mat = split("P",$numpres[$i]);
      $numpre = $mat[0];       
      $numpar = $mat[1];
      $sql = "update issvar set q05_vlrinf = ".$valores[$i]." where q05_numpre = $numpre and q05_numpar = $numpar";
       db_query($sql) or die("Erro(37) atualizando issvar: ".pg_errormessage());
  }
  db_query("COMMIT");
  $tipo = 3;
}//fim do issqn variavel

//mens_help();
if(!isset($tipo)){
   msgbox("Acesso a Rotina Inválido.");
   db_logs("","",0,"Acesso a Rotina Invalida. - Variavel tipo nao setada");
   redireciona("index.php");
}
if(!isset($opcao)){
   msgbox("Acesso a Rotina Inválido.");
   db_logs("","",0,"Acesso a Rotina Invalida. - Variavel tipo nao setada");
   redireciona("index.php");
}
if($opcao == "n") {
  $Caminho = "&nbsp;<a href=\"digitacontribuinte.php\" class=\"links\">Contribuinte &gt;</a>
               &nbsp;<a href=\"opcoesdebitospendentes.php?".base64_encode("opcao=n&numcgm=".$numcgm)."\" class=\"links\">Opções Contribuinte &gt;</a>
               &nbsp;<font class=\"links\">Lista Contribuinte($descricaotipo) &gt;</font>\n";
} else if($opcao == "m") {
  $Caminho = "&nbsp;<a href=\"digitamatricula.php\" class=\"links\">Imóvel &gt;</a>
               &nbsp;<a href=\"opcoesdebitospendentes.php?".base64_encode("opcao=m&matricula=".$matricula)."\" class=\"links\">Opções Imóvel &gt;</a>
               &nbsp;<font class=\"links\">Lista Imóvel($descricaotipo) &gt;</font>\n";
} else if($opcao == "i") {
  $Caminho = "&nbsp;<a href=\"digitainscricao.php\" class=\"links\">Alvará &gt;</a>
              &nbsp;<a href=\"opcoesdebitospendentes.php?".base64_encode("opcao=i&inscricao=".@$inscricao)."\" class=\"links\">Opções Alvará &gt;</a>
               &nbsp;<font class=\"links\">Lista Alvará($descricaotipo) &gt;</font>\n";
}
//verifica o tipo e da o select dependendo se é numcgm, matric numpre ou inscr          
if(isset($tipo)) {
  if($tipo == 3) {
    if(isset($numcgm)){
      $acesso = "1";
      $campo = $numcgm;
      $result = debitos_numcgm_var($numcgm,0,$tipo,time(),date("Y"));
    }else if(isset($inscricao)){       
      $acesso = "2";
      $campo = $inscricao;
      $result = debitos_inscricao_var($inscricao,0,$tipo,time(),date("Y"));
    }else if(isset($numpre)){
       $result = debitos_numpre_var($numpre,0,$tipo,time(),date("Y"));  
    }       
  } else {
    if(isset($numcgm) && $numcgm != "") {
      $acesso = "1";
      $campo = $numcgm;
      $result = debitos_numcgm($numcgm,0,$tipo,time(),date("Y"));
    } else if(isset($matricula) && $matricula != "") {
      $acesso = "3";
      $campo = $matricula;
      $result = debitos_matricula($matricula,0,$tipo,time(),date("Y"));
    } else if(isset($inscricao) && $inscricao != "") {
      $acesso = "2";
      $campo = $inscricao;
      $result = debitos_inscricao($inscricao,0,$tipo,time(),date("Y"));
     } else if(isset($numpre)) {
      $result = debitos_numpre($numpre,0,$tipo,time(),date("Y"));
     }
  }
}
if(!isset($DB_LOGADO) && !isset($numpre)  && $m_publico !='t'){
$sql = "select fc_permissaodbpref(".db_getsession("DB_login").",$acesso,$campo)";
  $result1 = db_query($sql);
  if(pg_numrows($result1)==0){
    db_redireciona("centro_pref.php?".base64_encode("erroscripts=3"));
    exit;
  }
  $result1 = pg_result($result1,0,0);
  if($result1=="0"){
    db_redireciona("centro_pref.php?".base64_encode("erroscripts=3"));
    exit;
  }
}

?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
js_verificapagina("opcoesdebitospendentes.php,listadebitospendentes.php");
function js_emiteunica(numpre){
  document.form1.numpre_unica.value = numpre; 
//  document.form1.target="reciboweb3";
  jan = window.open('','reciboweb','width=790,height=530,scrollbars=1,location=0');
  jan.moveTo(0,0);
  document.form1.submit();
  document.form1.numpre_unica.value = ""; 
}

function js_emiterecibo() {
  emite_recibo = false;
  F = document.form1;
  for(var i = 0;i < F.length;i++) {
    if(F.elements[i].type == "checkbox" && (F.elements[i].checked == true)) {
      emite_recibo = true;
    }
  }
  if(emite_recibo==true){
    jan = window.open('','reciboweb','width=790,height=530,scrollbars=1,location=0');
    jan.moveTo(0,0);
    return true;
  }else{
    alert("Selecione o débito a ser impresso.");
  }
  return false;
}
function js_soma(linha) {
  linha = (typeof(linha)=="undefined"?2:linha);
  var F = document.form1;
  var valor = 0;
  var valorcorr = 0;
  var juros = 0;
  var multa = 0;
  var desconto = 0;
  var total = 0;
  var emrec = '<?=@$emrec?>';
  
 if(emrec == 't')
   document.getElementById("enviar").disabled = false;
  for(var i = 0;i < F.length;i++) {
    if(F.elements[i].type == "checkbox" && (F.elements[i].checked == true || linha == 1)) {
      var indi = js_parse_int(F.elements[i].id);
       valor += new Number(document.getElementById('valor'+indi).value.replace(",",""));
      valorcorr += new Number(document.getElementById('valorcorr'+indi).value.replace(",",""));
      juros += new Number(document.getElementById('juros'+indi).value.replace(",",""));
      multa += new Number(document.getElementById('multa'+indi).value.replace(",",""));
      desconto += new Number(document.getElementById('desconto'+indi).value.replace(",",""));
      total += new Number(document.getElementById('total'+indi).value.replace(",",""));
     }
  }
  document.getElementById('somavalor'+linha).innerHTML = valor.toFixed(2);
  document.getElementById('somavalorcorr'+linha).innerHTML = valorcorr.toFixed(2);
  document.getElementById('somajuros'+linha).innerHTML = juros.toFixed(2);
  document.getElementById('somamulta'+linha).innerHTML = multa.toFixed(2);
  document.getElementById('somadesconto'+linha).innerHTML = desconto.toFixed(2);
  document.getElementById('somatotal'+linha).innerHTML = total.toFixed(2);
  if(linha == 2) {
    valor = Number(document.getElementById('somavalor1').innerHTML) - valor;
    valorcorr = Number(document.getElementById('somavalorcorr1').innerHTML) - valorcorr;
    juros = Number(document.getElementById('somajuros1').innerHTML) - juros;
    multa = Number(document.getElementById('somamulta1').innerHTML) - multa;
    desconto = Number(document.getElementById('somadesconto1').innerHTML) - desconto;
    total = Number(document.getElementById('somatotal1').innerHTML) - total;
     document.getElementById('somavalor3').innerHTML = valor.toFixed(2);
    document.getElementById('somavalorcorr3').innerHTML = valorcorr.toFixed(2);
    document.getElementById('somajuros3').innerHTML = juros.toFixed(2);
    document.getElementById('somamulta3').innerHTML = multa.toFixed(2);
    document.getElementById('somadesconto3').innerHTML = desconto.toFixed(2);
    document.getElementById('somatotal3').innerHTML = total.toFixed(2);
  }
  if(emrec == 't') {
    var aux = 0;
    for(i = 0;i < F.length;i++) {
      if(F.elements[i].type == "checkbox")
         if(F.elements[i].checked == true)
           aux = 1;
    }  
    if(aux == 0) {
      //document.getElementById("enviar").disabled = true
       document.getElementById('marca').innerHTML = "M";
      //document.getElementById('btmarca').value = "Marcar Todas";
    }
  }
}
function js_marca() {
  var ID = document.getElementById('marca');
//  var BT = parent.document.getElementById('btmarca');
  if(!ID)
    return false;
  var F = document.form1;
  if(ID.innerHTML == 'M') {
    var dis = true;
     ID.innerHTML = 'D';
//     BT.value = "Desmarcar Todas";
  } else {
    var dis = false;
     ID.innerHTML = 'M';
//     BT.value = "Marcar Todas";
  }
for(i = 0;i < F.elements.length;i++) {
  if(F.elements[i].type == "checkbox")
    F.elements[i].checked = dis;
}
js_soma(2);
}
</script>
<style type="text/css">
<?
db_estilosite();
?>
td{
  color: black;
  }
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="js_soma(1)" <? mens_OnHelp() ?>>
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
          <?=@$Caminho?>
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
          <?    db_montamenus();        
          ?>
          </td>
            <td align="left" valign="top"> 
                 
  <form name="form1" method="post" target="reciboweb">
  <input type="hidden" name="tipo" value="<?=@$tipo?>">
  <input type="hidden" name="tipo_debito" value="<?=@$tipo?>">
  <input type="hidden" name="ver_matric" value="<?=@$matricula?>">
  <input type="hidden" name="ver_inscr" value="<?=@$inscricao.@$verinscr?>">
  <input type="hidden" name="ver_numcgm" value="<?=@$numcgm.@$vernumcgm?>">
  <input type="hidden" name="numpre_unica">
                <table width="100%" border="0" cellpadding="3" cellspacing="0" id="tabdebitos">
                  <tr bgcolor="#FFCC66"> 
                    <th class="borda" style="font-size:11px" nowrap>P</th>
                    <th class="borda" style="font-size:11px" nowrap>TP</th>
                    <th class="borda" style="font-size:11px" nowrap>Dt. oper.</th>
                    <th class="borda" style="font-size:11px" nowrap>Dt. Venc.</th>
                    <th class="borda" style="font-size:11px" nowrap>Descrição</th>
                    <th class="borda" style="font-size:11px" nowrap>R</th>
                    <th class="borda" style="font-size:11px" nowrap>D.R.</th>
                    <th class="borda" style="font-size:11px" nowrap>Val.</th>
                    <th class="borda" style="font-size:11px" nowrap>Val Cor.</th>
                    <th class="borda" style="font-size:11px" nowrap>Jur.</th>
                    <th class="borda" style="font-size:11px" nowrap>Mul.</th>
                    <th class="borda" style="font-size:11px" nowrap>Desc.</th>
                    <th class="borda" style="font-size:11px" nowrap>Tot.</th>
                    <th class="borda" style="font-size:11px" nowrap><a id="marca" href="" style="color:black" onclick="js_marca();return false">M</a></th>
                  </tr>
                  <?
     ////////////////////////////////////////////////////////
       //if com 3 partes. Primeiro se é pra agrupar por numpre, segundo se é pra agrupar por parcela e terceiro mostra o default
  //agrupar por numpre
  $numrows = pg_numrows($result);
  if(@$agnum == 't') {
  /******************************************************************************************/
    //cria um array com os elementos não repetidos
     $j = 0;
     $vlrtotal = 0;
     $elementos[0] = "";
    for($i = 0;$i < $numrows;$i++) {
      if(!in_array(pg_result($result,$i,"k00_numpre"),$elementos)) {
        $REGISTRO[$j] = pg_fetch_array($result,$i);
        $elementos[$j] = pg_result($result,$i,"k00_numpre");
     $j += 1;
      }
    }
    //faz a mao...
    for($i = 0;$i < sizeof($elementos);$i++) {
      $valor = 0;
      $valorcorr = 0;
      $juros = 0;
      $multa = 0;
      $desconto = 0;
      $total = 0;
      $separador = "";
      $numpres = "";
      for($j = 0;$j < $numrows;$j++) {
        if($elementos[$i] == pg_result($result,$j,"k00_numpre")) {
          if(pg_result($result,$j,"k00_numpar") != @pg_result($result,$j+1,"k00_numpar") || (   $elementos[$i] != pg_result($result,$j+1,"k00_numpre")) ) {
            $numpres .= $separador.pg_result($result,$j,"k00_numpre")."P".pg_result($result,$j,"k00_numpar");
            $separador = "N";
          }
          $valor += (float)pg_result($result,$j,"vlrhis");
          $valorcorr += (float)pg_result($result,$j,"vlrcor");
          $juros += (float)pg_result($result,$j,"vlrjuros");
          $multa += (float)pg_result($result,$j,"vlrmulta");
          $desconto += (float)pg_result($result,$j,"vlrdesconto");
          $total += (float)pg_result($result,$j,"total"); 
     }
      }       
      /**************************/
      $vlrtotal += $REGISTRO[$i]["total"];
       $dtoper = pg_result($result,$i,"k00_dtoper");
       $dtoper = mktime(0,0,0,substr($dtoper,5,2),substr($dtoper,8,2),substr($dtoper,0,4));
       //if($dtoper > time())
       //  $corDtoper = "#FF5151";
       //else
       $corDtoper = "";
       $dtvenc = $REGISTRO[$i]["k00_dtvenc"];
       $dtvenc = mktime(0,0,0,substr($dtvenc,5,2),substr($dtvenc,8,2),substr($dtvenc,0,4));
       if($dtvenc < time())
         $corDtvenc = "red";
       else
         $corDtvenc = "";       
          //*****CABEÇALHO  ;border:none
      echo "<label for=\"CHECK$i\"><tr style=\"cursor: hand\" bgcolor=\"".($cor = (@$cor=="#E4F471"?"#EFE029":"#E4F471"))."\">\n";
      echo "<td class=\"borda\" nowrap>0</td>\n";
      echo "<td class=\"borda\" nowrap>".$REGISTRO[$i]["k00_numtot"]."</td>\n";
      echo "<td class=\"borda\" ".($corDtoper==""?"":"bgcolor=$corDtoper")." nowrap>".date("d-m-Y",$dtoper)."</td>\n";
      echo "<td class=\"borda\" ".($corDtvenc==""?"":"bgcolor=$corDtvenc")." nowrap>".date("d-m-Y",$dtvenc)."</td>\n";     
      echo "<td class=\"borda\" nowrap>".(trim($REGISTRO[$i]["k01_descr"])==""?"&nbsp":$REGISTRO[$i]["k01_descr"])."</td>\n";
      echo "<td class=\"borda\" nowrap><a href=\"listadebitospendentes.php?".base64_encode("vermatric=".@$matricula."&matricula=".@$matricula."&verinscr=".@$inscricao."&vernumcgm=".@$numcgm."&numpre=".$elementos[$i]."&tipo=$tipo&verificaagrupar=1&opcao=".@$opcao."&agnump=f&agpar=t&emrec=".@$emrec."&descricaotipo=".@$descricaotipo."")."\">AP</a></td>\n";
      echo "<td class=\"borda\" nowrap>".(trim($REGISTRO[$i]["k02_descr"])==""?"&nbsp":$REGISTRO[$i]["k02_descr"])."</td>\n";

      echo "<td class=\"borda\" align=\"right\" nowrap><input type=\"hidden\" id=\"valor$i\" value=\"".$valor."\">".number_format($valor,2,".",",")."</td>\n";
      echo "<td class=\"borda\" align=\"right\" nowrap><input type=\"hidden\" id=\"valorcorr$i\" value=\"".$valorcorr."\">".number_format($valorcorr,2,".",",")."</td>\n";
      echo "<td class=\"borda\" align=\"right\" nowrap><input type=\"hidden\" id=\"juros$i\" value=\"".$juros."\">".number_format($juros,2,".",",")."</td>\n";
      echo "<td class=\"borda\" align=\"right\" nowrap><input type=\"hidden\" id=\"multa$i\" value=\"".$multa."\">".number_format($multa,2,".",",")."</td>\n";
      echo "<td class=\"borda\" align=\"right\" nowrap><input type=\"hidden\" id=\"desconto$i\" value=\"".$desconto."\">".number_format($desconto,2,".",",")."</td>\n";
      echo "<td class=\"borda\" align=\"right\" nowrap><input type=\"hidden\" id=\"total$i\" value=\"".$total."\">".number_format($total,2,".",",")."</td>\n";
      echo "<td class=\"borda\" id=\"coluna$i\" nowrap>".($tipo==3?"<input type=\"submit\" class=\"botao\" name=\"calculavalor\" id=\"calculavalor$i\" value=\"Calcular\">":"")."<input type=\"".($tipo==3?"hidden":"checkbox")."\" value=\"".$numpres."\" onclick=\"js_soma(2)\" id=\"CHECK$i\" class=\"botao\" name=\"CHECK$i\" ".((abs($REGISTRO[$i]["k00_valor"])!=0 && $tipo==3)?"disabled":"")."></td>\n";
       echo "</tr></label>\n"; 
       /***************************/
     }  
  // vai normal
  } else if(@$agnum == "nivel3") { // if($agpar == 't') {

    
  /***************/      
       //cria um array com os numpres não repetidos
     $j = 0;
     $elementos_numpres[0] = "";
    for($i = 0;$i < $numrows;$i++) {       
      if(!in_array(pg_result($result,$i,"k00_numpre"),$elementos_numpres))
         $elementos_numpres[$j++] = pg_result($result,$i,"k00_numpre");
     }                    
    for($i = 0;$i < sizeof($elementos_numpres);$i++) {
      $auxValor = 0;
       $auxValorcorr = 0;
       $auxJuros = 0;
       $auxMulta = 0;
       $auxDesconto = 0;
       $auxTotal = 0;
      for($j = 0;$j < $numrows;$j++) {
         if($elementos_numpres[$i] == pg_result($result,$j,"k00_numpre")) {
            if(pg_result($result,$j,"k00_numpar") == @pg_result($result,$j+1,"k00_numpar")) {
              $auxValor += (float)pg_result($result,$j,"vlrhis");
              $auxValorcorr += (float)pg_result($result,$j,"vlrcor");
              $auxJuros += (float)pg_result($result,$j,"vlrjuros");
              $auxMulta += (float)pg_result($result,$j,"vlrmulta");
              $auxDesconto += (float)pg_result($result,$j,"vlrdesconto");
              $auxTotal += (float)pg_result($result,$j,"total");                                                                           
               @$SomaDasParcelasValor[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")] = $auxValor;
               @$SomaDasParcelasValorcorr[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")] = $auxValorcorr;
               @$SomaDasParcelasJuros[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")] = $auxJuros;
               @$SomaDasParcelasMulta[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")] = $auxMulta;
               @$SomaDasParcelasDesconto[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")] = $auxDesconto;
               @$SomaDasParcelasTotal[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")] = $auxTotal;
               //echo $elementos_numpres[$i]." == ".pg_result($result,$j,"k00_numpar")." == ".@pg_result($result,$j+1,"k00_numpar")." == ".$aux."<br>";
            } else {
              $auxValor = 0;
             $auxValorcorr = 0;
             $auxJuros = 0;
             $auxMulta = 0;
             $auxDesconto = 0;
             $auxTotal = 0;
             @$SomaDasParcelasValor[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")] += pg_result($result,$j,"vlrhis");
             @$SomaDasParcelasValorcorr[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")] += pg_result($result,$j,"vlrcor");
             @$SomaDasParcelasJuros[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")] += pg_result($result,$j,"vlrjuros");
             @$SomaDasParcelasMulta[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")] += pg_result($result,$j,"vlrmulta");
             @$SomaDasParcelasDesconto[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")] += pg_result($result,$j,"vlrdesconto");                                                            
             @$SomaDasParcelasTotal[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")] += pg_result($result,$j,"total");
          }
          } else {                        
            continue;
          }
       }
     }     
    $vlrtotal = 0;
     $verf_parc = "";
     $cont = 0;
     $cont2 = 0;     
     $bool = 0;
     $bool2 = 0;     
    $ConfCor1 = "#EFE029";
    $ConfCor2 = "#E4F471";
    for($i = 0;$i < $numrows;$i++) {
       if($elementos_numpres[$cont] != pg_result($result,$i,"k00_numpre")) {
         $cont++;
         if($bool == 0) {
          $ConfCor1 = "#77EE20";
          $ConfCor2 = "#A9F471";
            $bool = 1;
          } else {
          $ConfCor1 = "#EFE029";
          $ConfCor2 = "#E4F471";
            $bool = 0;
          }
       }
      $vlrtotal += pg_result($result,$i,"total");
       $dtoper = pg_result($result,$i,"k00_dtoper");
       $dtoper = mktime(0,0,0,substr($dtoper,5,2),substr($dtoper,8,2),substr($dtoper,0,4));
       $corDtoper = "";
       $dtvenc = pg_result($result,$i,"k00_dtvenc");
       $dtvenc = mktime(0,0,0,substr($dtvenc,5,2),substr($dtvenc,8,2),substr($dtvenc,0,4));
       if($dtvenc < time())
         $corDtvenc = "red";
       else
         $corDtvenc = "";
       if(pg_result($result,$i,"k00_numpar") == @$salva_parcela) {
         $cor = $ConfCor1;
       } else {
           $cor = $ConfCor2;
         if(pg_result($result,$i,"k00_numpar") == @pg_result($result,$i+1,"k00_numpar"))
           $salva_parcela = "";
         else
           $salva_parcela = @pg_result($result,$i+1,"k00_numpar");          
       }     
       
       if(!isset($HTTP_POST_VARS["calculavalor"])) {
        echo "<label for=\"CHECK$i\"><tr style=\"cursor: hand\" bgcolor=\"".$cor."\">\n";     
        echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".(trim(pg_result($result,$i,"k00_numpar"))==""?"&nbsp":pg_result($result,$i,"k00_numpar"))."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".(trim(pg_result($result,$i,"k00_numtot"))==""?"&nbsp":pg_result($result,$i,"k00_numtot"))."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" ".($corDtoper==""?"":"bgcolor=$corDtoper")." nowrap>".date("d-m-Y",$dtoper)."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" ".($corDtvenc==""?"":"bgcolor=$corDtvenc")." nowrap>".date("d-m-Y",$dtvenc)."</td>\n";     
        echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".(trim(pg_result($result,$i,"k01_descr"))==""?"&nbsp":pg_result($result,$i,"k01_descr"))."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".(trim(pg_result($result,$i,"k00_receit"))==""?"&nbsp":pg_result($result,$i,"k00_receit"))."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".(trim(pg_result($result,$i,"k02_descr"))==""?"&nbsp":pg_result($result,$i,"k02_descr"))."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"valor$i\" value=\"".$SomaDasParcelasValor[pg_result($result,$i,"k00_numpre")][pg_result($result,$i,"k00_numpar")]."\">".(trim(pg_result($result,$i,"vlrhis"))==""?"&nbsp":((abs(pg_result($result,$i,"k00_valor"))==0 && $tipo==3)?"<input style=\"height: 16px;font-size=12px\" onfocus=\"//if(parent.document.getElementById('enviar').value == 'Agrupar') parent.document.getElementById('enviar').disabled = true;\" type=\"text\" name=\"VAL".$i."\" value=\"".abs(pg_result($result,$i,"valor_variavel"))."\" size=\"5\">":number_format(pg_result($result,$i,"vlrhis"),2,".",",")))."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"valorcorr$i\" value=\"".$SomaDasParcelasValorcorr[pg_result($result,$i,"k00_numpre")][pg_result($result,$i,"k00_numpar")]."\">".(trim(pg_result($result,$i,"vlrcor"))==""?"&nbsp":number_format(pg_result($result,$i,"vlrcor"),2,".",","))."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"juros$i\" value=\"".$SomaDasParcelasJuros[pg_result($result,$i,"k00_numpre")][pg_result($result,$i,"k00_numpar")]."\">".(trim(pg_result($result,$i,"vlrjuros"))==""?"&nbsp":number_format(pg_result($result,$i,"vlrjuros"),2,".",","))."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"multa$i\" value=\"".$SomaDasParcelasMulta[pg_result($result,$i,"k00_numpre")][pg_result($result,$i,"k00_numpar")]."\">".(trim(pg_result($result,$i,"vlrmulta"))==""?"&nbsp":number_format(pg_result($result,$i,"vlrmulta"),2,".",","))."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"desconto$i\" value=\"".$SomaDasParcelasDesconto[pg_result($result,$i,"k00_numpre")][pg_result($result,$i,"k00_numpar")]."\">".(trim(pg_result($result,$i,"vlrdesconto"))==""?"&nbsp":number_format(pg_result($result,$i,"vlrdesconto"),2,".",","))."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"total$i\" value=\"".$SomaDasParcelasTotal[pg_result($result,$i,"k00_numpre")][pg_result($result,$i,"k00_numpar")]."\">".(trim(pg_result($result,$i,"total"))==""?"&nbsp":number_format(pg_result($result,$i,"total"),2,".",","))."</td>\n";
         if($elementos_numpres[$cont2] == pg_result($result,$i,"k00_numpre")) {
           if($verf_parc != pg_result($result,$i,"k00_numpar")) {
            echo "<td class=\"borda\" style=\"font-size:11px\" id=\"coluna$i\" nowrap>".($tipo==3 && !isset($HTTP_POST_VARS["calculavalor"])?"<input class=\"botao\" type=\"submit\"  onclick=\"this.form.target = ''\" name=\"calculavalor\" id=\"calculavalor$i\" value=\"Calcular\">":"")."<input type=\"".($tipo==3 && !isset($HTTP_POST_VARS["calculavalor"])?"hidden":"checkbox")."\" value=\"".pg_result($result,$i,"k00_numpre")."P".pg_result($result,$i,"k00_numpar")."\" onclick=\"js_soma(2)\" id=\"CHECK$i\" name=\"CHECK$i\" ".(abs(pg_result($result,$i,"k00_valor")) != 0 && !isset($HTTP_POST_VARS["calculavalor"])?"disabled":"")."></td>\n";
             $verf_parc = pg_result($result,$i,"k00_numpar");
           } else {
             $verf_parc = pg_result($result,$i,"k00_numpar");
             echo "<td class=\"borda\" style=\"font-size:11px\" id=\"coluna$i\" nowrap>&nbsp;</td>\n";          
          }
         } else {
            $cont2++;
          $verf_parc = pg_result($result,$i,"k00_numpar");
          echo "<td class=\"borda\" style=\"font-size:11px\" id=\"coluna$i\" nowrap>".($tipo==3 && !isset($HTTP_POST_VARS["calculavalor"])?"<input type=\"submit\" onclick=\"this.form.target = ''\" name=\"calculavalor\" class=\"botao\" id=\"calculavalor$i\" value=\"Calcular\">":"")."<input type=\"".($tipo==3 && !isset($HTTP_POST_VARS["calculavalor"])?"hidden":"checkbox")."\" value=\"".pg_result($result,$i,"k00_numpre")."P".pg_result($result,$i,"k00_numpar")."\" onclick=\"js_soma(2)\" id=\"CHECK$i\" name=\"CHECK$i\" ".(abs(pg_result($result,$i,"k00_valor")) != 0 && !isset($HTTP_POST_VARS["calculavalor"])?"disabled":"")."></td>\n";
           }
         echo "</tr></label>\n"; 
      
       } else if(abs(pg_result($result,$i,"k00_valor")) != 0 || abs(pg_result($result,$i,"valor_variavel")) != 0)  {

        echo "<label for=\"CHECK$i\"><tr style=\"cursor: hand\" bgcolor=\"".$cor."\">\n";     
        echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".(trim(pg_result($result,$i,"k00_numpar"))==""?"&nbsp":pg_result($result,$i,"k00_numpar"))."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".(trim(pg_result($result,$i,"k00_numtot"))==""?"&nbsp":pg_result($result,$i,"k00_numtot"))."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" ".($corDtoper==""?"":"bgcolor=$corDtoper")." nowrap>".date("d-m-Y",$dtoper)."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" ".($corDtvenc==""?"":"bgcolor=$corDtvenc")." nowrap>".date("d-m-Y",$dtvenc)."</td>\n";     
        echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".(trim(pg_result($result,$i,"k01_descr"))==""?"&nbsp":pg_result($result,$i,"k01_descr"))."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".(trim(pg_result($result,$i,"k00_receit"))==""?"&nbsp":pg_result($result,$i,"k00_receit"))."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".(trim(pg_result($result,$i,"k02_descr"))==""?"&nbsp":pg_result($result,$i,"k02_descr"))."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"valor$i\" value=\"".$SomaDasParcelasValor[pg_result($result,$i,"k00_numpre")][pg_result($result,$i,"k00_numpar")]."\">".(trim(pg_result($result,$i,"vlrhis"))==""?"&nbsp":((abs(pg_result($result,$i,"k00_valor"))==0 && $tipo==3)?"<input style=\"height: 16px;font-size=12px\" onfocus=\"//if(parent.document.getElementById('enviar').value == 'Agrupar') parent.document.getElementById('enviar').disabled = true;\" type=\"text\" name=\"VAL".$i."\" value=\"".abs(pg_result($result,$i,"valor_variavel"))."\" size=\"5\">":number_format(pg_result($result,$i,"vlrhis"),2,".",",")))."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"valorcorr$i\" value=\"".$SomaDasParcelasValorcorr[pg_result($result,$i,"k00_numpre")][pg_result($result,$i,"k00_numpar")]."\">".(trim(pg_result($result,$i,"vlrcor"))==""?"&nbsp":number_format(pg_result($result,$i,"vlrcor"),2,".",","))."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"juros$i\" value=\"".$SomaDasParcelasJuros[pg_result($result,$i,"k00_numpre")][pg_result($result,$i,"k00_numpar")]."\">".(trim(pg_result($result,$i,"vlrjuros"))==""?"&nbsp":number_format(pg_result($result,$i,"vlrjuros"),2,".",","))."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"multa$i\" value=\"".$SomaDasParcelasMulta[pg_result($result,$i,"k00_numpre")][pg_result($result,$i,"k00_numpar")]."\">".(trim(pg_result($result,$i,"vlrmulta"))==""?"&nbsp":number_format(pg_result($result,$i,"vlrmulta"),2,".",","))."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"desconto$i\" value=\"".$SomaDasParcelasDesconto[pg_result($result,$i,"k00_numpre")][pg_result($result,$i,"k00_numpar")]."\">".(trim(pg_result($result,$i,"vlrdesconto"))==""?"&nbsp":number_format(pg_result($result,$i,"vlrdesconto"),2,".",","))."</td>\n";
        echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"total$i\" value=\"".$SomaDasParcelasTotal[pg_result($result,$i,"k00_numpre")][pg_result($result,$i,"k00_numpar")]."\">".(trim(pg_result($result,$i,"total"))==""?"&nbsp":number_format(pg_result($result,$i,"total"),2,".",","))."</td>\n";
         if($elementos_numpres[$cont2] == pg_result($result,$i,"k00_numpre")) {
           if($verf_parc != pg_result($result,$i,"k00_numpar")) {
            echo "<td class=\"borda\" style=\"font-size:11px\" id=\"coluna$i\" nowrap>".($tipo==3 && !isset($HTTP_POST_VARS["calculavalor"])?"<input type=\"submit\"  class=\"botao\" onclick=\"this.form.target = ''\" name=\"calculavalor\" id=\"calculavalor$i\" value=\"Calcular\">":"")."<input type=\"".($tipo==3 && !isset($HTTP_POST_VARS["calculavalor"])?"hidden":"checkbox")."\" value=\"".pg_result($result,$i,"k00_numpre")."P".pg_result($result,$i,"k00_numpar")."\" onclick=\"js_soma(2)\" id=\"CHECK$i\" name=\"CHECK$i\" ".(abs(pg_result($result,$i,"k00_valor")) != 0 && !isset($HTTP_POST_VARS["calculavalor"])?"disabled":"")."></td>\n";
             $verf_parc = pg_result($result,$i,"k00_numpar");
           } else {
             $verf_parc = pg_result($result,$i,"k00_numpar");
             echo "<td class=\"borda\" style=\"font-size:11px\" id=\"coluna$i\" nowrap>&nbsp;</td>\n";          
          }
         } else {
            $cont2++;
          $verf_parc = pg_result($result,$i,"k00_numpar");
          echo "<td class=\"borda\" style=\"font-size:11px\" id=\"coluna$i\" nowrap>".($tipo==3 && !isset($HTTP_POST_VARS["calculavalor"])?"<input type=\"submit\" class=\"botao\" onclick=\"this.form.target = ''\" name=\"calculavalor\" id=\"calculavalor$i\" value=\"Calcular\">":"")."<input type=\"".($tipo==3 && !isset($HTTP_POST_VARS["calculavalor"])?"hidden":"checkbox")."\" value=\"".pg_result($result,$i,"k00_numpre")."P".pg_result($result,$i,"k00_numpar")."\" onclick=\"js_soma(2)\" id=\"CHECK$i\" name=\"CHECK$i\" ".(abs(pg_result($result,$i,"k00_valor")) != 0 && !isset($HTTP_POST_VARS["calculavalor"])?"disabled":"")."></td>\n";
           }
         echo "</tr></label>\n"; 

       }
    }

  /***************/
  } else {
  /**********************************************************************************************/   
//cria um array com os numpres não repetidos
//issqnvar
    $j = 0;
    $elementos_numpres[0] = "";
    for($i = 0;$i < $numrows;$i++) {
      if(!in_array(pg_result($result,$i,"k00_numpre"),$elementos_numpres)) {
        $elementos_numpres[$j++] = pg_result($result,$i,"k00_numpre");
      }
    }
    //contador unico para nomear os inputs
    $ContadorUnico = 0;
    $bool = 1;
    //faz a mao..
    for($x = 0;$x < sizeof($elementos_numpres);$x++) {
       //cria um array com as parcelas do numpre não repetidos
       if($bool == 0) {
         $ConfCor1 = "#77EE20";
         $ConfCor2 = "#A9F471";
         $bool = 1;
       } else {
         $ConfCor1 = "#EFE029";
         $ConfCor2 = "#E4F471";
         $bool = 0;
       }       
       $f = 0;       
       $vlrtotal = 0;
       if(isset($elementos_parcelas))
          unset($elementos_parcelas);
       $elementos_parcelas[0] = "";
       for($r = 0;$r < $numrows;$r++) {
       if($elementos_numpres[$x] == pg_result($result,$r,"k00_numpre"))
            if(!in_array(pg_result($result,$r,"k00_numpar"),$elementos_parcelas)) {
           $REGISTRO[$f] = pg_fetch_array($result,$r);
              $elementos_parcelas[$f++] = pg_result($result,$r,"k00_numpar");
          }
       }
       for($i = 0;$i < sizeof($elementos_parcelas);$i++) {
       $numpres = "";
       $separador = "";
       $valor = 0;
          $valorcorr = 0;
          $juros = 0;
          $multa = 0;
          $desconto = 0;
          $total = 0;
       $ve_parcela = "";
       for($j = 0;$j < $numrows;$j++) {
         if($elementos_parcelas[$i] == pg_result($result,$j,"k00_numpar") && $elementos_numpres[$x] == pg_result($result,$j,"k00_numpre")) {             
             if((pg_result($result,$j,"k00_numpar") != @pg_result($result,$j + 1,"k00_numpar" &&  pg_result($result,$j,"k00_numpre") != @pg_result($result,$j + 1,"k00_numpre"))) || (pg_numrows($result)==1)) {
                if(strpos($ve_parcela,$elementos_numpres[$x]."P".$elementos_parcelas[$i])==0){
                  $ve_parcela .= "-".$elementos_numpres[$x]."P".$elementos_parcelas[$i];
               $numpres .= $separador.$elementos_numpres[$x]."P".$elementos_parcelas[$i];
               $separador = "N";
          }
              }
          $valor += (float)pg_result($result,$j,"vlrhis");
             $valorcorr += (float)pg_result($result,$j,"vlrcor");
             $juros += (float)pg_result($result,$j,"vlrjuros");
             $multa += (float)pg_result($result,$j,"vlrmulta");
             $desconto += (float)pg_result($result,$j,"vlrdesconto");
             $total += (float)pg_result($result,$j,"total");               
        }
     }       
     /**************************/
        $vlrtotal += $REGISTRO[$i]["total"];
         $dtoper = $REGISTRO[$i]["k00_dtoper"];
         $dtoper = mktime(0,0,0,substr($dtoper,5,2),substr($dtoper,8,2),substr($dtoper,0,4));
         //if($dtoper > time())
         //  $corDtoper = "#FF5151";
         //else
         $corDtoper = "";
         $dtvenc = $REGISTRO[$i]["k00_dtvenc"];
         $dtvenc = mktime(0,0,0,substr($dtvenc,5,2),substr($dtvenc,8,2),substr($dtvenc,0,4));
         if($dtvenc < time())
           $corDtvenc = "red";
         else
           $corDtvenc = "";       

      /*****unica*****/
       if($elementos_parcelas[$i]==1){
         $resultunica = db_query(
                  "select *,
                 substr(fc_calcula,2,13)::float8 as uvlrhis,
                 substr(fc_calcula,15,13)::float8 as uvlrcor,
                 substr(fc_calcula,28,13)::float8 as uvlrjuros,
                 substr(fc_calcula,41,13)::float8 as uvlrmulta,
                 substr(fc_calcula,54,13)::float8 as uvlrdesconto,
                 (substr(fc_calcula,15,13)::float8+
                 substr(fc_calcula,28,13)::float8+
                 substr(fc_calcula,41,13)::float8-
                 substr(fc_calcula,54,13)::float8) as utotal
                     from (
                     select r.k00_numpre,r.k00_dtvenc as dtvencunic, r.k00_dtoper as dtoperunic,r.k00_percdes,
                            fc_calcula(r.k00_numpre,0,0,r.k00_dtvenc,r.k00_dtvenc,".db_getsession("DB_anousu").")
                 from recibounica r
                     where r.k00_numpre = ".$elementos_numpres[$i]." and r.k00_dtvenc >= '".db_getsession("DB_datausu")."'::date
                     ) as unica");
          for($unicont=0;$unicont<pg_numrows($resultunica);$unicont++){
          db_fieldsmemory($resultunica,$unicont);
            $dtvencunic = db_formatar($dtvencunic,'d');
            $dtoperunic = db_formatar($dtoperunic,'d');
            $corunica = "#009933";
          $uvlrcorr = 0;
          echo "<tr bgcolor=\"$corunica\">\n";     
          //echo "<td class=\"borda\" style=\"font-size:11px\" nowrap></td>\n";       
          //echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".$k00_numpre."</td>\n";
          echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>00</td>\n";
          echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>00</td>\n";
          echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".$dtoperunic."</td>\n";
          echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".$dtvencunic."</td>\n";     
          echo "<td colspan=\"3\" class=\"borda\" style=\"font-size:11px;color:white\" nowrap>Parcena Única com $k00_percdes% desconto</td>\n";
//          echo "<td class=\"borda\" style=\"font-size:11px\" nowrap></td>\n";
 //         echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>Parcela Única</td>\n";
       
          echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap>".number_format($uvlrhis,2,".",",")."</td>\n";
          echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap>".number_format($uvlrcorr,2,".",",")."</td>\n";
          echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap>".number_format($uvlrjuros,2,".",",")."</td>\n";
          echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap>".number_format($uvlrmulta,2,".",",")."</td>\n";
          echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap>".number_format($uvlrdesconto,2,".",",")."</td>\n";
          echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap>".number_format($utotal,2,".",",")."</td>\n";
          echo "<td class=\"borda\" style=\"font-size:11px\" align=\"center\" nowrap>
          <input type=\"button\"  class=\"botao\" name=\"unica\" onclick=\"js_emiteunica('$k00_numpre')\" value=\"U\">";
            echo "  </td>\n
          </tr>";
         }     
      }

     /****fimunica******/


        echo "<label for=\"CHECK$ContadorUnico\"><tr style=\"cursor: hand\" bgcolor=\"".($cor = (@$cor==$ConfCor2?$ConfCor1:$ConfCor2))."\">\n";     
        echo "<td class=\"borda\" nowrap>".$elementos_parcelas[$i]."</td>\n";
        echo "<td class=\"borda\" nowrap>".$REGISTRO[$i]["k00_numtot"]."</td>\n";
        echo "<td class=\"borda\" ".($corDtoper==""?"":"bgcolor=$corDtoper")." nowrap>".date("d-m-Y",$dtoper)."</td>\n";
        echo "<td class=\"borda\" ".($corDtvenc==""?"":"bgcolor=$corDtvenc")." nowrap>".date("d-m-Y",$dtvenc)."</td>\n";     
        echo "<td class=\"borda\" nowrap>".(trim($REGISTRO[$i]["k01_descr"])==""?"&nbsp":$REGISTRO[$i]["k01_descr"])."</td>\n";
        echo "<td class=\"borda\" nowrap>".(trim(pg_result($result,$i,"k00_receit"))==""?"&nbsp":pg_result($result,$i,"k00_receit"))."</td>\n";
        echo "<td class=\"borda\" nowrap>".(trim($REGISTRO[$i]["k02_descr"])==""?"&nbsp":$REGISTRO[$i]["k02_descr"])."</td>\n";       
      echo "<td class=\"borda\" align=\"right\" nowrap><input type=\"hidden\" id=\"valor$ContadorUnico\" value=\"".$valor."\">".number_format($valor,2,".",",")."</td>\n";
      echo "<td class=\"borda\" align=\"right\" nowrap><input type=\"hidden\" id=\"valorcorr$ContadorUnico\" value=\"".$valorcorr."\">".number_format($valorcorr,2,".",",")."</td>\n";
      echo "<td class=\"borda\" align=\"right\" nowrap><input type=\"hidden\" id=\"juros$ContadorUnico\" value=\"".$juros."\">".number_format($juros,2,".",",")."</td>\n";
      echo "<td class=\"borda\" align=\"right\" nowrap><input type=\"hidden\" id=\"multa$ContadorUnico\" value=\"".$multa."\">".number_format($multa,2,".",",")."</td>\n";
      echo "<td class=\"borda\" align=\"right\" nowrap><input type=\"hidden\" id=\"desconto$ContadorUnico\" value=\"".$desconto."\">".number_format($desconto,2,".",",")."</td>\n";
      echo "<td class=\"borda\" align=\"right\" nowrap><input type=\"hidden\" id=\"total$ContadorUnico\" value=\"".$total."\">".number_format($total,2,".",",")."</td>\n";
      echo "<td class=\"borda\" id=\"coluna$ContadorUnico\" nowrap>".($tipo==3?"<input type=\"submit\"  class=\"botao\" name=\"calculavalor\" id=\"calculavalor$ContadorUnico\" value=\"Calcular\">":"")."<input type=\"".($tipo==3?"hidden":"checkbox")."\" value=\"".$numpres."\" onclick=\"js_soma(2)\" class=\"botao\" id=\"CHECK$ContadorUnico\" name=\"CHECK".$ContadorUnico++."\" ".((abs($REGISTRO[$i]["k00_valor"])!=0 && $tipo==3)?"disabled":"")."></td>\n";
         echo "</tr></label>\n"; 
         /***************************/
       }  
    }
  }
    ?>
                  <tr bgcolor="#FFCC66"> 
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="borda">&nbsp;</td>
                  </tr>
                  <tr> 
                    <td bgcolor="#FFCC66">&nbsp;</td>
                    <td bgcolor="#FFCC66">&nbsp;</td>
                    <td bgcolor="#FFCC66">&nbsp;</td>
                    <td bgcolor="#FFCC66">&nbsp;</td>
                    <td colspan="3" class="colrecibo" align="right"><strong>Soma:&nbsp;</strong></td>
                    <td class="borda1"><font class="colrecibo" id="somavalor1">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                    <td class="borda1"><font class="colrecibo" id="somavalorcorr1">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                    <td class="borda1"><font class="colrecibo" id="somajuros1">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                    <td class="borda1"><font class="colrecibo" id="somamulta1">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                    <td class="borda1"><font class="colrecibo" id="somadesconto1">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                    <td class="borda1"><font class="colrecibo" id="somatotal1">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                    <td bgcolor="#FFCC66" class="borda">&nbsp;</td>
                  </tr>
                  <tr> 
                    <td bgcolor="#FFCC66">&nbsp;</td>
                    <td bgcolor="#FFCC66">&nbsp;</td>
                    <td bgcolor="#FFCC66">&nbsp;</td>
                    <td bgcolor="#FFCC66">&nbsp;</td>
                    <td colspan="3" class="colrecibo" align="right"><strong>Soma/Parcial:&nbsp;</strong></td>
                    <td class="borda1"><font class="colrecibo" id="somavalor2">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                    <td class="borda1"><font class="colrecibo" id="somavalorcorr2">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                    <td class="borda1"><font class="colrecibo" id="somajuros2">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                    <td class="borda1"><font class="colrecibo" id="somamulta2">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                    <td class="borda1"><font class="colrecibo" id="somadesconto2">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                    <td class="borda1"><font class="colrecibo" id="somatotal2">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                    <td bgcolor="#FFCC66" class="borda">&nbsp;</td>
                  </tr>
                  <tr> 
                    <td bgcolor="#FFCC66">&nbsp;</td>
                    <td bgcolor="#FFCC66">&nbsp;</td>
                    <td bgcolor="#FFCC66">&nbsp;</td>
                    <td bgcolor="#FFCC66">&nbsp;</td>
                    <td colspan="3" class="colrecibo" align="right"><strong>Diferen&ccedil;a:</strong></td>
                    <td class="borda1"><font class="colrecibo" id="somavalor3">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                    <td class="borda1"><font class="colrecibo" id="somavalorcorr3">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                    <td class="borda1"><font class="colrecibo" id="somajuros3">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                    <td class="borda1"><font class="colrecibo" id="somamulta3">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                    <td class="borda1"><font class="colrecibo" id="somadesconto3">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                    <td class="borda1"><font class="colrecibo" id="somatotal3">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                    <td bgcolor="#FFCC66" class="borda">&nbsp;</td>
                  </tr>
                  <tr> 
                    <td colspan="5">
              <input type="submit"  class="botao" name="emiterecibo" onClick="return js_emiterecibo()" value="Emitir Recibo" <? echo ($tipo==3 && !isset($HTTP_POST_VARS["calculavalor"]))?"disabled":"" ?>>
              <?
              if(isset($calculavalor)){
                ?>
                <input type="button" class="botao" name="btretornar" onClick="history.back(1)" value="Retornar">
                <?
              }
              ?>
              </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                </table>                 
    </form>
                 
                 <!-- InstanceEndEditable -->     
            </td>
      </tr>
      </table>
     </td>
  </tr>
</table>
</center>
<?
db_rodape();
?>
</body>
<!-- InstanceEnd --></html>
<?
} //fim de if(isset($HTTP_POST_VARS["emiterecibo"])) {
?>