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


parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

include("fpdf151/scpdf.php");
//include("libs/db_stdlib.php");
require("libs/db_sql.php");
$k00_recibodbpref = 1;
			  $sqlmostra = "select k00_tipo, k00_descr,k00_recibodbpref from arretipo where k00_tipo = $tipo";
			  $resultmostra = db_query($sqlmostra);
			  $linhasmostra = pg_num_rows($resultmostra);
			  if($linhasmostra>0){
			    db_fieldsmemory($resultmostra,0);
			   // echo "<br> $k00_descr  = $k00_recibodbpref..tipo. $k00_tipo";
			  }
if(isset($HTTP_POST_VARS["inicial"])) {

  global $HTTP_SESSION_VARS;

  if(isset($db_datausu)){

    if(!checkdate(substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4))){
       echo "Data para cálculo inválida. <br><br>";
       echo "Data deverá se superior a : ".date('Y-m-d',$HTTP_SESSION_VARS["DB_datausu"]);
    }
    
    $sTimeParamGet     = mktime(0,0,0,substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4));
    $sTimeParamSession = mktime(0,0,0,date('m',db_getsession("DB_datausu")),date('d',db_getsession("DB_datausu")),date('Y',db_getsession("DB_datausu")));
    
    if($sTimeParamGet < $sTimeParamSession){
       echo "Data não permitida para cálculo. <br><br>";
       echo "Data deverá se superior a : ".date('Y-m-d',db_getsession("DB_datausu"));
    }
    
    $DB_DATACALC = mktime(0,0,0,substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4));
  } else {
    $DB_DATACALC = db_getsession("DB_datausu");
  }
  include("cai3_gerfinanc003.php");
  exit;
}

$clrotulo = new rotulocampo;
$clrotulo->label("v50_inicial");
$clrotulo->label("v50_advog");
$clrotulo->label("v50_data");
$clrotulo->label("nome");
$clrotulo->label("v50_id_login");
$clrotulo->label("v50_codlocal");
$clrotulo->label("v70_vara");
$clrotulo->label("v50_codmov");
$clrotulo->label("v53_descr");
$clrotulo->label("v54_descr");
$clrotulo->label("v70_codforo");
$clrotulo->label("v52_descr");

if(isset($matric) and !empty($matric)){
  $tabela="  inner join arrematric on arrematric.k00_numpre = arrecad.k00_numpre ";
  $campo='k00_matric';
  $valor=$matric;
}else if(isset($inscr) and !empty($inscr)){
  $tabela="inner join arreinscr   on arreinscr.k00_numpre = arrecad.k00_numpre ";
  $campo='k00_inscr';
  $valor=$inscr;
}else if(isset($numcgm) and !empty($numcgm)){
  $tabela = "  inner join arrenumcgm   on arrenumcgm.k00_numpre = arrecad.k00_numpre ";
  $campo='arrenumcgm.k00_numcgm';
  $valor=$numcgm;

}else{
  $tabela='';
  $campo='k00_numpre ';
  $valor=$numpre;
}
   $sql="
          select distinct 
                  v50_inicial,
                v50_advog,
                v50_data,
                v50_id_login,
                nome,
                v50_codlocal,
                v54_descr,
                v70_vara,
                v53_descr,
                v50_codmov,
                v70_codforo,
                v52_descr
                  from arrecad
                    $tabela
                    inner join inicialnumpre       on inicialnumpre.v59_numpre          = arrecad.k00_numpre
                    inner join inicial             on inicial.v50_inicial               = inicialnumpre.v59_inicial
                    inner join db_usuarios         on db_usuarios.id_usuario            = inicial.v50_id_login
                    inner join localiza            on inicial.v50_codlocal              = localiza.v54_codlocal
                    inner join inicialmov          on inicial.v50_codmov                = inicialmov.v56_codmov
                    inner join situacao            on inicialmov.v56_codsit             = situacao.v52_codsit
                    left  join processoforoinicial on processoforoinicial.v71_inicial   = inicial.v50_inicial
                                                  and processoforoinicial.v71_anulado is false
                    left  join processoforo        on processoforo.v70_sequencial       = processoforoinicial.v71_processoforo
                    left  join vara                on vara.v53_codvara                  = processoforo.v70_vara 
                  where $campo = $valor
                    and v50_instit = 1";
   
   $result = db_query($sql);
   $numrows= pg_numrows($result);

?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/scripts.js"></script>
<style type="text/css">
<!--
.borda {
        border-right-width: 1px;
        border-right-style: solid;
        border-right-color: #000000;
}
-->
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="parent.document.getElementById('processando').style.visibility = 'hidden'">
<center>
<form name="form1" method="post" action="" target="reciboweb2">
<table id="tabdebitos" name="tabdebitos">
<?
  if($numrows>0){
  
    /**
     *  Monta o cabeçalho que lista as iniciais.
     */
    echo "
          <tr bgcolor=\"#FFCC66\">   \n          
            <th class=\"borda\" style=\"font-size:11px\" nowrap>O</td>\n            
            <th title=\"Marca/Desmarca Todas\" class=\"borda\" style=\"font-size:12px\" nowrap><a id=\"marca\" href=\"\" style=\"color:black\" onclick=\"js_marca();return false\">M</a>
            <th class=\"borda\" style=\"font-size:11px\" title='".@$Tv50_inicial."' nowrap>Inicial Número</th>\n
            <th class=\"borda\" style=\"font-size:11px\" title='Valor Total' nowrap>Valor Total</th>\n
            <th class=\"borda\" style=\"font-size:11px\" title='".@$Tv50_advog."' nowrap>Data inicial</th>\n
            <th class=\"borda\" style=\"font-size:11px\" title='".@$Tv70_codforo."' nowrap>Código do Processo</th>\n
            <th class=\"borda\" style=\"font-size:11px\" title='".@$Tnome."' nowrap>Nome do usuário</th>\n
            <th class=\"borda\" style=\"font-size:11px\" title='".@$Tv54_descr."' nowrap>Localização</th>\n
            <th class=\"borda\" style=\"font-size:11px\" title='".@$Tv53_descr."' nowrap>Vara</th>\n
            <th class=\"borda\" style=\"font-size:11px\" title='".@$Tv50_codmov."' nowrap>Movimento</th>\n
            <th class=\"borda\" style=\"font-size:11px\" title='".@$Tv52_descr."' nowrap>Descrição</th>\n
          </tr>  
            ";          
    $valor_total = 0;
    
    for ($i = 0; $i < $numrows; $i++) {
      
      db_fieldsmemory($result,$i);
    //      if($i == 0){ 
       $sql  = "select v59_numpre as numpres ";
       $sql .= "  from inicialnumpre ";
       $sql .= " where v59_inicial = {$v50_inicial}";
              
       $result1     = db_query($sql);
       $numrows1    = pg_numrows($result1); 
       $virgula     = "";
       $numpre1     = "";
       $valor_geral = 0; 
       $valor_corr  = 0;
       //$valor_juros  = 0;
      // $valor_multa  = 0;
       $juros  = 0;
       $multa  = 0;
       $desconto =0;
       
       for ($j = 0; $j < $numrows1; $j++) {
         
         db_fieldsmemory($result1,$j);
         $numpre1 .= $virgula.$numpres;
        
         $result_valinicial = debitos_numpre($numpres,0,0,db_getsession("DB_datausu"),db_getsession("DB_anousu"),0,true);
         if ($result_valinicial){
           $linhas_valinicial = pg_num_rows($result_valinicial);    
	         
	         if($linhas_valinicial>0){
  	         db_fieldsmemory($result_valinicial,0);
  	         $valor_geral += $total;
  	         $valor_corr  += $vlrcor;

  	         //$valor_juros += $vlrjuros;
  	        // $valor_multa += $vlrmulta;
  	         $juros += $vlrjuros;
  	         $multa += $vlrmulta;
  	         $virgula = ",";
	         }       
         }
       }
       $valor_total += $valor_geral; 

    //      }
      if($i%2==0){
        $color='#E4F471';
      }else{
        $color='#EFE029';
      }
      $funcao="js_inicial($v50_inicial);";
      if (!empty($v70_codforo)) {
      
        $total = $valor_geral;
        echo "
            <tr bgcolor=\"$color\">   \n 
              <input type='hidden' name='valor$i' value='0' id='valor$i'>
              <input type='hidden' name='valorcorr$i' value='$valor_corr' id='valorcorr$i'>
              <input type='hidden' name='juros$i' value='$juros' id='juros$i'>
              <input type='hidden' name='multa$i' value='$multa' id='multa$i'>
              <input type='hidden' name='desconto$i' value='$desconto' id='desconto$i'>
              <input type='hidden' name='total$i' value='$total' id='total$i'>
              <input type='hidden' name='vcto_parcela$i' value='' id='vcto_parcela$i'>
              
              
              <td class=\"borda\" style=\"font-size:11px\" nowrap><a href='#' onclick=\"$funcao return false;\">MI</a></td>\n            
              <td class=\"borda\" style=\"font-size:11px\" id=\"coluna$i\" nowrap><input style=\"visibility:'visible'\" type=\"checkbox\" value=\"".$v50_inicial."\" ".($v70_codforo == ""?"disabled":"")." id=\"CHECK$i\" name=\"CHECK$i\"  onClick='js_soma(this);'></td>
              <td  style=\"font-size:11px\" title='$Tv50_inicial' nowrap>$v50_inicial</td>\n
              <td  style=\"font-size:11px\" title='Valor Total' nowrap>".db_formatar($valor_geral,'f')."</td>\n
              <td  style=\"font-size:11px\" title='$Tv50_advog' nowrap>" . db_formatar($v50_data,"d") . "</td>\n
              <td  style=\"font-size:11px\" title='$Tv70_codforo' nowrap>$v70_codforo</td>\n
              <td  style=\"font-size:11px\" title='$Tnome' nowrap>$nome</td>\n
              <td  style=\"font-size:11px\" title='$Tv54_descr' nowrap>$v54_descr</td>\n
              <td  style=\"font-size:11px\" title='$Tv53_descr' nowrap>$v53_descr</td>\n
              <td  style=\"font-size:11px\" title='$Tv50_codmov' nowrap>$v50_codmov</td>\n
              <td  style=\"font-size:11px\" title='$Tv52_descr' nowrap>$v52_descr</td>\n
            </tr>  
              ";    
      } else {

        echo "
            <tr bgcolor=\"$color\">   \n 
              <input type='hidden' name='valor$i' value='$valor_geral' id='valor$i'>
              <input type='hidden' name='valorcorr$i' value='$valor_corr' id='valorcorr$i'>
              <input type='hidden' name='juros$i' value='$juros' id='juros$i'>
              <input type='hidden' name='multa$i' value='$multa' id='multa$i'>
							<input type='hidden' name='desconto$i' value='$desconto' id='desconto$i'>
							<input type='hidden' name='total$i' value='$total' id='total$i'>
							<input type='hidden' name='vcto_parcela$i' value='' id='vcto_parcela$i'>
							
              
              <td class=\"borda\" style=\"font-size:11px\" nowrap><a href='#' onclick=\"$funcao return false;\">MI</a></td>\n            
              <td class=\"borda\" style=\"font-size:11px\" id=\"coluna$i\" nowrap><input style=\"visibility:'visible'\" type=\"checkbox\" value=\"".$v50_inicial."\" ".($v70_codforo == ""?"disabled":"")." id=\"CHECK$i\" name=\"CHECK$i\"  onClick='js_soma(this);'></td>
              <td  style=\"font-size:11px\" title='$Tv50_inicial' nowrap>$v50_inicial</td>\n
              <td  style=\"font-size:11px\" title='Valor Total' nowrap>".db_formatar($valor_geral,'f')."</td>\n
              <td  style=\"font-size:11px\" title='$Tv50_advog' nowrap>" . db_formatar($v50_data,"d") . "</td>\n
              <td  style=\"font-size:11px\" title='$Tv70_codforo' nowrap>$v70_codforo</td>\n
              <td  style=\"font-size:11px\" title='$Tnome' nowrap>$nome</td>\n
              <td  style=\"font-size:11px\" title='$Tv54_descr' nowrap>$v54_descr</td>\n
              <td  style=\"font-size:11px\" title='$Tv53_descr' nowrap>$v53_descr</td>\n
              <td  style=\"font-size:11px\" title='$Tv50_codmov' nowrap>$v50_codmov</td>\n
              <td  style=\"font-size:11px\" title='$Tv52_descr' nowrap>$v52_descr</td>\n
            </tr>  
              ";          
      }
    }

    
    echo "<input type='hidden' name='k03_tipo' value=''>";
    echo "<input type='hidden' name='inicial' value='t'>";
    echo "<input type='hidden' name='dt_agrupadebitos' value='' id='dt_agrupadebitos'>";
    echo "<form name=\"form1\" id=\"form1\" method=\"post\" target=\"reciboweb2\">\n";


    if (isset($matric)) { 
    } elseif (isset($inscr)) {
    }
    if (isset($numcgm)) { 
    }

    echo "<input type=\"hidden\" name=\"ver_inscr\" value=\"".@$inscr."\">\n";
    echo "<input type=\"hidden\" name=\"ver_matric\" value=\"".@$matric."\">\n";
    echo "<input type=\"hidden\" name=\"ver_numcgm\" value=\"".@$numcgm."\">\n";
    echo "<input type=\"hidden\" name=\"totregistros\" value=\"".@$numrows."\">\n";
    echo "<input type=\"hidden\" name=\"numpre_unica\" value=\"\">\n";
    echo "<input type=\"hidden\" name=\"inicial\" value=\"\">\n";
    echo "<input type=\"hidden\" name=\"var_vcto\" value=\"\">\n";


  }else{
?>
  <tr><td><small>Nenhum registro encontrado</small></td></tr> 
<?
  }
?>
</table>
</form>
</center>
<script>
  function js_inicial(inicial){
      js_OpenJanelaIframe('top.corpo','db_iframe12','cai3_gerfinanc040.php?tabela=<?=$tabela?>&campo=<?=$campo?>&valor=<?=$valor?>&origem=inicial&inicial='+inicial+'&tipo=<?=$tipo?>','Pesquisa',true);
  }

  function js_marca() {
    var ID = document.getElementById('marca');
    if(!ID)
      return false;
    var F = document.form1;
    if(ID.innerHTML == 'M') {
      var dis = true;
      ID.innerHTML = 'D';
    } else {
      var dis = false;
      ID.innerHTML = 'M';
    }
    for(i = 0;i < F.elements.length;i++) {
      if(F.elements[i].type == "checkbox"){
        if(F.elements[i].style.visibility!="hidden"){
         	if(F.elements[i].name == "NM") { // se o name do checkbox for NM(Não Marcar) não deixa somar os valor tbem
         		F.elements[i].checked = false;
         	}else {   
          	F.elements[i].checked = dis;
         	}
        }
      }
    }
    js_soma(this);
  }
    
  function js_soma(linha) {
	
    linha = ((typeof(linha)=="undefined") || (typeof(linha)=="object")?2:linha);
    var F = document.form1;
    var numpres=0;
    var valor = 0;
    var valorcorr = 0;
    var juros = 0;
    var multa = 0;
    var desconto = 0;
    var total = 0;
    var emrec = 0;
    var vcto_atraso = false;
    var data_hoje = "<?=date('Ymd',db_getsession('DB_datausu'))?>";
    var var_vcto = "";
    var vcto_calc = "";
    var mostraemite = <?=$k00_recibodbpref?>;
    if(mostraemite==2){
    alert('Este tipo de debito não permite emitir recibo.');
      parent.document.getElementById("enviar").disabled = true;//botao emite recibo
    }else{
   		parent.document.getElementById("enviar").disabled = false;//botao emite recibo
    }
    for(var i = 0;i < F.length;i++){
      if((F.elements[i].type == "checkbox" || F.elements[i].type == "submit") && (F.elements[i].checked == true || linha == 1)){
        var indi = js_parse_int(F.elements[i].id);
        valor += new Number(document.getElementById('valor'+indi).value.replace(",",""));
        valorcorr += new Number(document.getElementById('valorcorr'+indi).value.replace(",",""));
        juros += new Number(document.getElementById('juros'+indi).value.replace(",",""));
        multa += new Number(document.getElementById('multa'+indi).value.replace(",",""));
        desconto += new Number(document.getElementById('desconto'+indi).value.replace(",",""));
        total += new Number(document.getElementById('total'+indi).value.replace(",",""));
        numpres += 'N'+document.getElementById('CHECK'+indi).value ;
        parent.document.getElementById('numpres').value = numpres;
        parent.document.getElementById('debito').disabled = false;
        //data do vencimento
        var_vcto = document.getElementById('vcto_parcela'+indi).innerHTML;
        var_vcto2 = var_vcto.substr(6,4)+var_vcto.substr(3,2)+var_vcto.substr(0,2);
        if(vcto_atraso==false && (var_vcto2 < data_hoje)){
         vcto_atraso = true;
         vcto_calc = data_hoje;
        }
        if(vcto_atraso==false){
         vcto_atraso = true;
         vcto_calc = var_vcto2;
        }
       ///fim vcto
      }
    }
    parent.document.getElementById('dia_vcto').value = vcto_calc.substr(6,2);
    parent.document.getElementById('mes_vcto').value = vcto_calc.substr(4,2);
    parent.document.getElementById('ano_vcto').value = vcto_calc.substr(0,4);
    parent.document.getElementById('valor'+linha).innerHTML     = valor.toFixed(2);
    parent.document.getElementById('valorcorr'+linha).innerHTML = valorcorr.toFixed(2);
    parent.document.getElementById('juros'+linha).innerHTML     = juros.toFixed(2);
    parent.document.getElementById('multa'+linha).innerHTML     = multa.toFixed(2);
    parent.document.getElementById('desconto'+linha).innerHTML  = desconto.toFixed(2);
    parent.document.getElementById('total'+linha).innerHTML     = total.toFixed(2);

    if(linha == 2){

      if (!empty($v70_codforo)) { 

        valor     = 0;
        valorcorr = 0;
        juros     = 0;
        multa     = 0;
        desconto  = 0;
        total     = 0;

      } else {

        valor     = Number(parent.document.getElementById('valor1').innerHTML) - valor;
        valorcorr = Number(parent.document.getElementById('valorcorr1').innerHTML) - valorcorr;
        juros     = Number(parent.document.getElementById('juros1').innerHTML) - juros;
        multa     = Number(parent.document.getElementById('multa1').innerHTML) - multa;
        desconto  = Number(parent.document.getElementById('desconto1').innerHTML) - desconto;
        total     = Number(parent.document.getElementById('total1').innerHTML) - total;
      }



      parent.document.getElementById('valor3').innerHTML     = valor.toFixed(2);
      parent.document.getElementById('valorcorr3').innerHTML = valorcorr.toFixed(2);
      parent.document.getElementById('juros3').innerHTML     = juros.toFixed(2);
      parent.document.getElementById('multa3').innerHTML     = multa.toFixed(2);
      parent.document.getElementById('desconto3').innerHTML  = desconto.toFixed(2);
      parent.document.getElementById('total3').innerHTML     = total.toFixed(2);

    }

    if(emrec == 't') {
      var aux = 0;
      for(i = 0;i < F.length;i++) {
        if(F.elements[i].type == "checkbox")
         if(F.elements[i].checked == true)
          aux = 1;
      }
      if(aux == 0) {
       
        parent.document.getElementById("enviar").disabled = true;
        document.getElementById('marca').innerHTML = "M";
        document.getElementById('btmarca').value = "Marcar Todas";
      }
    }

   if(Number(parent.document.getElementById('total2').innerHTML)==0)
    parent.document.getElementById("enviar").disabled = true;
  }
  
</script>
</body>
</html>