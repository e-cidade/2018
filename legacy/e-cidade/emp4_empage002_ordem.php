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


require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

include("classes/db_pagordem_classe.php");
include("classes/db_pagordemele_classe.php");
include("classes/db_pagordemconta_classe.php");
include("classes/db_empagetipo_classe.php");
include("classes/db_empord_classe.php");
include("classes/db_empagemov_classe.php");
include("classes/db_empagepag_classe.php");
include("classes/db_pcfornecon_classe.php");
include("classes/db_empageconfche_classe.php");

$clempagetipo = new cl_empagetipo;
$clpagordem   = new cl_pagordem;
$clpagordemele   = new cl_pagordemele;
$clpagordemconta   = new cl_pagordemconta;
$clempord     = new cl_empord;
$clempagemov  = new cl_empagemov;
$clempagepag  = new cl_empagepag;
$clpcfornecon  = new cl_pcfornecon;
$clempageconfche  = new cl_empageconfche;

//echo ($HTTP_SERVER_VARS["QUERY_STRING"]);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
//db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = false;

$clrotulo = new rotulocampo;
$clrotulo->label("e50_codord");
$clrotulo->label("o15_descr");
$clrotulo->label("o15_codigo");
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("e60_emiss");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("e83_codtipo");
$clrotulo->label("e53_valor");
$clrotulo->label("e53_vlranu");
$clrotulo->label("e53_vlrpag");

$dbwhere = "(round(e53_valor,2)-round(e53_vlranu,2)-round(e53_vlrpag,2))>0 ";

if(isset($e50_codord) && $e50_codord != '' && isset($e50_codord02) && $e50_codord02 != '' ){
  $dbwhere .=" and e50_codord >=$e50_codord and e50_codord <= $e50_codord02 ";
}else if(  (empty($e50_codord) || ( isset($e50_codord) && $e50_codord == '')   )  && isset($e50_codord02) && $e50_codord02 != '' ){
  $dbwhere .=" and e50_codord <= $e50_codord02 ";
}else if(isset($e50_codord) && $e50_codord != '' ){
  $dbwhere .=" and e50_codord=$e50_codord ";
}

if(isset($e60_codemp) && $e60_codemp != '' ){
  $dbwhere .=" and e60_codemp = $e60_codemp ";
  if (isset($e60_anousu)) {

    $dbwhere .= " and e60_anousu = '{$e60_anousu}'";
  } else {
    $dbwhere .= " and e60_anousu= '".db_getsession("DB_anousu")."'";
  }
}

if(isset($e60_emiss) && $e60_emiss != '' ){
  $dbwhere .=" and e60_emiss = $e60_emiss ";
}
if(isset($e60_numemp) && $e60_numemp != '' ){
  $dbwhere .=" and e60_numemp = $e60_numemp ";
}
if(isset($z01_numcgm) && $z01_numcgm != '' ){
  $dbwhere .=" and z01_numcgm = $z01_numcgm ";
}

if(isset($dtin) && $dtin !=''){
  $dtin =  str_replace("_","-",$dtin);
  $dbwhere .= " and e60_emiss >= '$dtin'";
}

if(isset($dtfi) && $dtfi !=''){
  $dtfi =  str_replace("_","-",$dtfi);
  $dbwhere .= " and e60_emiss <= '$dtfi'";
}
/*
//-------------------------------
//rotina para trazer apenas os que podem ser pagos pelo tipo fornecido
$dbinner='';
if(isset($e83_codtipo)){
  //rotina que pega os bancos
  $sqlbanco = "select c63_banco 
  from conplanoreduz  
  inner join conplanoconta on c61_codcon=c63_codcon 
  inner join empagetipo on e83_conta = c61_reduz
  where e83_codtipo = $e83_codtipo
  ";
  $resbanco = pg_query($sqlbanco);
  db_fieldsmemory($resbanco,0);
  
  $dbinner = "  inner join pcfornecon on pc63_numcgm = z01_numcgm and pc63_banco = '$c63_banco' ";
}
//---------------------------------------------------------------------
*/
if(isset($e83_codtipo) && $e83_codtipo != ''){
  $result09 =   $clempagetipo->sql_record($clempagetipo->sql_query_rec(null,'c61_codigo',"","e83_codtipo=$e83_codtipo"));
  db_fieldsmemory($result09,0);
  $dbwhere .= " and o15_codigo = $c61_codigo"; 
}
/*rotina que traz o recurso do tipo fornecido*/

//---------------------------------------------
/*
$sql03 = "select e82_codord 
from empage
inner join empagemov    on empagemov.e81_codage     = empage.e80_codage
inner join empord       on empord.e82_codmov        = empagemov.e81_codmov
//left join empageconfche on empageconfche.e91_codmov = empagemov.e81_codmov
//left join empageconf    on empageconf.e86_codmov    = empageconfche.e91_codcheque
left join empageconf    on empageconf.e86_codmov    = empagemov.e81_codmov 
where e80_codage = $e80_codage ";
*/
$sql03 = "select e82_codord 
from empage
inner join empagemov on empagemov.e81_codage  = empage.e80_codage
inner join empord    on empord.e82_codmov     = empagemov.e81_codmov
left join empageconf on empageconf.e86_codmov = empagemov.e81_codmov 
where e80_codage = $e80_codage and e80_instit= " . db_getsession("DB_instit");

//$result = $clpagordem->sql_record($sql03);
//db_criatabela($result);

//           where e80_codage=$e80_codage  and ((e86_codmov is not null and e90_codmov is null) or e86_codmov is null)
$sql = $clpagordem->sql_query_pagordemele(null,"
e50_data,
o15_codigo,
o15_descr,
e60_emiss,
e60_anousu,
e60_numemp,
e60_codemp,
e50_codord,
z01_numcgm,
z01_nome,
e53_valor,
e53_vlranu,
e53_vlrpag,
e60_vlrliq,
e60_vlrpag
/*,
e86_codmov,
e90_codmov,
e90_correto
*/",
"",
"1=1 and e60_instit = " . db_getsession("DB_instit") . "
group by e60_numemp,
e60_codemp,
e50_codord,
e50_data,
z01_numcgm,
z01_nome,
e60_emiss,
o15_codigo,
o15_descr,
e60_anousu,
/*
e86_codmov,
e90_codmov,
e90_correto,
*/
e53_valor,
e53_vlranu,
e53_vlrpag,
e60_vlrliq,
e60_vlrpag");
//$result = $clpagordem->sql_record($sql);
//db_criatabela($result);

if (isset($ordens) && $ordens == 'n') {
  $not = "not";
} else {
  $not = "";
}

// echo "<BR><BR>".$sql;
$sql02  =  "select * from ($sql) as x ";
$sql02 .=  "where $dbwhere and (round(e60_vlrliq,2) - round(e60_vlrpag,2) > 0) and (round(e53_valor,2) - round(e53_vlranu,2) > 0) ";
$sql02 .=  (isset($ordens) && $ordens == 't'?"":" and e50_codord $not in ($sql03)");
$sql02 .=  "order by e50_codord";
$result09 = $clpagordem->sql_record($sql02);
$numrows09= $clpagordem->numrows;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
<?$cor="#999999"?>
.bordas02{
  border: 2px solid #cccccc;
  border-top-color: <?=$cor?>;
  border-right-color: <?=$cor?>;
  border-left-color: <?=$cor?>;
  border-bottom-color: <?=$cor?>;
  background-color: #999999;
}
.bordas{
  border: 1px solid #cccccc;
  border-top-color: <?=$cor?>;
  border-right-color: <?=$cor?>;
  border-left-color: <?=$cor?>;
  border-bottom-color: <?=$cor?>;
  background-color: #cccccc;
}
</style>
<script>
function js_marca(obj){ 
  var OBJ = document.form1;
  for(i=0;i<OBJ.length;i++){
    if(OBJ.elements[i].type == 'checkbox' && OBJ.elements[i].disabled==false){
      OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);            
    }
    if(OBJ.elements[i].checked==true){
      js_colocaval(OBJ.elements[i]);      
    }
  }
  return false;
}
function js_confere(campo){
  erro     = false;
  erro_msg = '';
  
  vlrgen= new Number(campo.value);
  
  
  if(isNaN(vlrgen)){
    erro = true;
  }
  nome = campo.name.substring(6);
  
  vlrlimite = new Number(eval("document.form1.disponivel_"+nome+".value"));
  if(vlrgen > vlrlimite){
    erro_msg = "Valor digitado é maior do que o disponível!";
    erro=true;
  }  
  
  if(vlrgen == ''){
    eval("document.form1."+campo.name+".value = '0.00';");
  }
  if(vlrgen == 0){
    eval("document.form1.CHECK_"+nome+".checked=false");
  }else{
    eval("document.form1.CHECK_"+nome+".checked=true");
  }
  
  if(erro==false){
    eval("document.form1."+campo.name+".value = vlrgen.toFixed(2);");
  }else{  
    if(erro_msg != ''){
      //alert(erro_msg);
    }
    eval("document.form1."+campo.name+".focus()");
    eval("document.form1."+campo.name+".value = vlrlimite.toFixed(2);");
    return false;
  }  
  
}

function js_colocaval(campo){
  if(campo.checked==true){
    valor = new Number(eval('document.form1.disponivel_'+campo.value+'.value')); 
    v = valor.toFixed(2);
    eval('document.form1.valor_'+campo.value+'.value='+v);
  }else{
    // eval('document.form1.valor_'+campo.value+'.value='+valor);
  }
}

function js_padrao(val){
  var OBJ = document.form1;
  for(i=0;i<OBJ.length;i++){
    nome = OBJ.elements[i].name;
    tipo = OBJ.elements[i].type;
    if( tipo.substr(0,6) == 'select' && nome.substr(0,11)=='e83_codtipo'){
      ord = nome.substr(12);
      checa = eval("document.form1.CHECK_"+ord+".checked");
      if(checa==false){
        continue;
      } 
      for(q=0; q<OBJ.elements[i].options.length; q++){
        if(OBJ.elements[i].options[q].value==val){
          OBJ.elements[i].options[q].selected=true;
          break;
        }
      }
    }
  }
}

</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr> 
<td height="100%" align="left" valign="top" bgcolor="#CCCCCC"> 
<form name="form1" method="post" action="">
<center>
<table  class='bordas'>
<tr>
<td class='bordas02' align='center'><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>
<td class='bordas02' align='center' ><small><b><?=$RLe60_codemp?></b></small></td>
<td class='bordas02' align='center'><small><b><?=$RLe50_codord?></b></small></td>
<td class='bordas02' align='center'><small><b><?=$RLo15_descr?></b></small></td>
<td class='bordas02' align='center'><small><b><?=$RLz01_nome?></b></small></td>
<td class='bordas02' align='center'><small><b><?/*=$RLe60_emiss*/?>Conta</b></small></td>
<td class='bordas02' align='center'><small><b>Total</b></small></td>
<td class='bordas02' align='center'><small><b>Disp.</b></small></td>
<td class='bordas02' align='center'><small><b>Valor</b></small></td>
<td class='bordas02' align='center'><small><b><?=$RLe83_codtipo?></b></small></td>
</tr>
<?
$nords =  '';
$nvirg ='';

//db_criatabela($result09);

for ($i=0; $i<$numrows09; $i++) {
  $dbwhere02='';
  db_fieldsmemory($result09,$i,true);

	//echo "<br><br><br><br><br>";
  
  $x= "e60_numemp_$e50_codord";
  $$x = $e60_numemp;
  
  //--------------------------------------
  // rotina que verifica se tem movimento para a ordem nesta agenda.. se tiver ele marca o campo checkbox
	$desativado=false;
  $xeque = "";
  $sqlord = $clempagemov->sql_query_ord(null,'distinct e81_codmov,e86_codmov,e81_valor,e90_correto','',"e82_codord=$e50_codord and e81_codage=$e80_codage and e80_instit= " . db_getsession("DB_instit"));
//////  echo "<br>ordens: $ordens<br><br>$sqlord<br>";
  $passou = false;
  $result01 = $clempagemov->sql_record($sqlord);
	//db_criatabela($result01);
  if ($clempagemov->numrows > 0) {
    db_fieldsmemory($result01,0,true);
		
    if (trim($e86_codmov)=="" or trim($e90_correto) == "f") {
      $xeque = "checked";
		} else {
			$desativado=true;
    }

    //rotina que verifica quais movimentos eh para trazer.. se todos,selecionados e naum selecionados
    if (isset($ordens) && $ordens == 'n') {
      if ($xeque != "") {
        continue;
      }
      $passou = true;
    }
    if (isset($ordens) && $ordens == 's') {
      if ($xeque == "") {
        //continue;
      }
      $passou = true;
    }

    
    //---------------------------------------------------------
    //pega o tipo do movimento
    $result01 = $clempagepag->sql_record($clempagepag->sql_query_file($e81_codmov,null,"e85_codtipo"));
    if ($clempagepag->numrows>0) {
      db_fieldsmemory($result01,0,true);
      $x= "e83_codtipo_$e50_codord";
      $$x = $e85_codtipo;
      
      $dbwhere02 = " or e83_codtipo=$e85_codtipo";
    }
    //-------------------------------------------------------------
  } else {
    //verifica se eh para trazer apenas os selecionados
    //echo "<br><br>Ordens=$ordens";
    if (isset($ordens) && $ordens == 's') {
      if ($passou == false) {
        continue;
      }
    }
    $e81_valor = '0.00';
  }

  //coloca o valor com campo
  $x= "valor_$e50_codord";
  $$x = number_format($e81_valor,"2",".","");
  
  // rotina que verifica se existe valor disponível
	// comentado por evandro em 11/07/2007, pois ordens que tinham cheques sem pagar estavam aparecendo para agendar novamente
	

// sem e86_codmov is null	
//  $result03  = $clempagemov->sql_record($clempagemov->sql_query_ord(null,"e82_codord,sum(e81_valor) as tot_valor",""," e82_codord = $e50_codord and                        e80_instit= " . db_getsession("DB_instit") . " group by e82_codord "));
//  $result03  = $clempagemov->sql_record($clempagemov->sql_query_ord(null,"e82_codord,sum(e81_valor) as tot_valor",""," e82_codord = $e50_codord and e86_codmov is null and e80_instit= " . db_getsession("DB_instit") . " group by e82_codord "));

  $sql3 = "	select	e81_codmov,
										e81_valor as e81_valor_mov
						from empagemov 
						inner join empage on empage.e80_codage = empagemov.e81_codage 
						inner join empord on empord.e82_codmov = empagemov.e81_codmov
						where		e82_codord = $e50_codord and 
										e80_instit = " . db_getsession("DB_instit");
	$result03  = pg_exec($sql3) or die($sql3);
  $numrows03 = pg_numrows($result03);

  $tot_valor = 0;
	
  if ($numrows03 > 0) {
		
		for ($reg=0; $reg < pg_numrows($result03); $reg++) {
			db_fieldsmemory($result03,$reg);

      // se emitir cheque pela opcao Manutencao de Agenda
			// se marcar como cheque ou transmissao em Manutencao de Pagamentos
      $sql4 = "	select * from empageconf where e86_codmov = $e81_codmov";
			$result04 = pg_exec($sql4) or die($sql4);

			if (pg_numrows($result04) == 0) {
				$tot_valor += $e81_valor_mov;
			} else {
				
				// se foi emitido cheque pelo botao emitir cheque (Manutencao de Agenda) e nao está pago este cheque
				$sql6 = "	select	empageconfche.e91_codcheque,
													empageconfche.e91_valor,
													corconf.k12_codmov
									from empageconfche
									left join corconf on empageconfche.e91_codcheque = corconf.k12_codmov and corconf.k12_ativo is true
									where e91_codmov = $e81_codmov";
				$result06 = pg_exec($sql6) or die($sql6);

				if (pg_numrows($result06) > 0) {

					for ($reg2=0; $reg2 < pg_numrows($result06); $reg2++) {
						db_fieldsmemory($result06, $reg2);

						if ($k12_codmov == "") {
							$tot_valor += $e91_valor;
						}
						
					}

				} else {

          // se foi marcado como cheque/transmissao/dinheiro (Manutencao de Pagamentos)
					$sql6 = "	select * from empagemovforma 
										inner join empagemov on e81_codmov = e97_codmov
										where e97_codmov = $e81_codmov";
					//and e97_codforma = 3";
					$result06 = pg_exec($sql6) or die($sql6);

					if (pg_numrows($result06) > 0) {
						$tot_valor += $e81_valor_mov;
					}

				}
			
//						left join empageconfche on empageconfche.e91_codmov = empagemov.e81_codmov
//						left join empageconf		on empageconf.e86_codmov = empageconfche.e91_codmov 
//						left join corconf				on empageconfche.e91_codcheque = corconf.k12_codmov

//						case when k12_codmov is null then true else e86_codmov is null end and 

			}

//////////			echo "$reg - $e81_codmov - $e81_valor_mov - " . pg_numrows($result04) . "<br>";

		}
 
//////////    echo "<br>tot_valor: $tot_valor<br>";
		
  } else {
    $tot_valor ='0.00';
  }
  
  $total = round((float) $e53_valor,2) - round((float) $e53_vlrpag,2) - round((float) $e53_vlranu,2);
  
	$e81_valor = round($e81_valor,2);
	$tot_valor = round($tot_valor,2);
  
//////////  echo "<br>total: $total - tot_valor: $tot_valor - e81_valor: $e81_valor<br>";

  if ($total == $tot_valor) {
    $disponivel = round((float) $e81_valor,2) ;
  } else {
//		if ( round((float) $total,2) < round((float) $tot_valor,2)) {
//			$disponivel = round((float) $total,2);
//		} else {
			$disponivel = round((float) $total,2) - ( round((float) $tot_valor,2) - round((float) $e81_valor,2));
//		}
  }
  //echo "<br><br>$e50_codord --- $disponivel = (float)$total - ((float)$tot_valor - (float)$e81_valor);";
  
  $x= "disponivel_$e50_codord";
  $$x = number_format($disponivel,"2",".","");

//////////	echo "desativado: " . ($desativado == true?"true":"false") . "<br>";

	//echo "disponivel: $disponivel<br>";
	
  //=-------------------------------------------
  if ($disponivel == 0 || $disponivel < 0  ) {
    // echo $e50_codord." sem valor disponivel!";
    $nords .= $nvirg.$e50_codord;
    $nvirg = " ,";
    if ($passou == false) {
      continue;
    }
  }
  
  //          echo "$disponivel = $total - ($tot_valor - $e81_valor);<br><br>";
  
  /*
  CÓDIGO COMENTADO POR MARLON
  BUSCAVA TODAS AS CONTAS QNDO ERA RP PQ EM 2005 NÃO TINHA RECURSO DE CONTAS DE 2004
  //pega os tipos
  if($e60_anousu < db_getsession("DB_anousu") || $recursos == 'todos'){
    $result05  = $clempagetipo->sql_record($clempagetipo->sql_query_file(null,"e83_codtipo as codtipo,e83_descr","e83_descr"));
  }else{
    $result05  = $clempagetipo->sql_record($clempagetipo->sql_query_emprec(null,"e83_codtipo as codtipo,e83_descr","e83_descr","e60_numemp=$e60_numemp $dbwhere02"));
  }
  */
  /**
  *  a variavel $recursos é um parametro selecionado na tela da
  *  agenda, é a opção recurso que aparece proprio ou todos na tela
  */
  if ($recursos=='todos') {
//    $result05  = $clempagetipo->sql_record($clempagetipo->sql_query(null,"distinct e83_codtipo as codtipo, e83_conta, e83_descr","e83_descr"));
    $result05 = $clempagetipo->sql_record	("select * from (" .
																						$clempagetipo->sql_query_emprec(null,"distinct 1 as tipo_conta, e83_conta, e83_codtipo as codtipo, e83_descr, c61_codigo",""," e60_numemp=$e60_numemp $dbwhere02")
																						. " union " .
																						$clempagetipo->sql_query_contapaga(null,"distinct 2 as tipo_conta, e83_conta, e83_codtipo as codtipo, e83_descr, c61_codigo",""," c61_codigo = 1 $dbwhere02") . "
																						) as x order by tipo_conta, e83_conta"
																						);


  } else {
    $result05 = $clempagetipo->sql_record	($clempagetipo->sql_query_emprec(null,"distinct e83_conta, e83_codtipo as codtipo,e83_descr, c61_codigo ","e83_descr","e60_numemp=$e60_numemp $dbwhere02"));
//    if ($clempagetipo->numrows==0) {
//      $result05  = $clempagetipo->sql_record($clempagetipo->sql_query(null,"distinct e83_codtipo as codtipo, e83_conta, e83_descr, c61_codigo","e83_descr"));
//    }
  }
  $numrows05 = $clempagetipo->numrows;
	$arr = array();
  $arr['0']="Nenhum";
  for ($r=0; $r<$numrows05; $r++) {
    db_fieldsmemory($result05,$r);
    $arr[$codtipo] = $e83_conta." - ".$e83_descr . " - " . str_pad($c61_codigo, 4, "0", STR_PAD_LEFT);
  }
  flush();

  if (isset($e83_codtipo) && $xeque == '' ) {
    $t = "e83_codtipo_$e50_codord";
    $$t = $e83_codtipo;
  } else {
		if (sizeof($arr) == 2) {
			$t = "e83_codtipo_$e50_codord";
			$$t = $codtipo;
		}
	}
  
  //rotina que verifica se o fornecedor possui conta cadastrada para pagamento eletrônico
  $outr = '';
  $result = $clpagordemconta->sql_record($clpagordemconta->sql_query($e50_codord,"e49_numcgm"));
  if ($clpagordemconta->numrows>0) {
    db_fieldsmemory($result,0);
    $numcgm = $e49_numcgm;
    $outr = "<span style=\"color:red;\">**</span>";
  } else {
    $numcgm = $z01_numcgm;
  }
  
  $result78 = $clpcfornecon->sql_record($clpcfornecon->sql_query_padrao(null,"pc63_banco,pc63_agencia,pc63_conta",'',"pc63_numcgm=$numcgm"));
  if ($clpcfornecon->numrows > 0) {
    db_fieldsmemory($result78,0);
  } else {
    $pc63_conta		=	'0';
    $pc63_banco		=	'0';
    $pc63_agencia =	'0';
  }
  
  ?>
  <tr>
  <td class='bordas' align='right'
  onMouseOut='parent.js_labelconta(false);' onMouseOver="parent.js_labelconta(true,'<?=$pc63_banco?>','<?=$pc63_agencia?>','<?=$pc63_conta?>');">
  <input value="<?=$e50_codord?>" <?=$xeque?> <?=($desativado==true?" disabled ":"")?> name="CHECK_<?=$e50_codord?>" type='checkbox' onclick='js_colocaval(this);'></td>
  <td class='bordas' align='right' title="Data de emissão:<?=$e60_emiss?>"><small id="e60_numemp_<?=$e50_codord?>" style='display:none'> <?=$e60_numemp?></small>
  <small><?=$e60_codemp;?>/<?=$e60_anousu?></small>
  </td>
  <td class='bordas' align='right' title="Data de emissão:<?=$e50_data?>"><small><?=$outr?><?=$e50_codord?></small></td>
  <td class='bordas' align='right'><small><?=$o15_descr?></small></td>
  <td class='bordas' label="Numcgm:<?=$z01_numcgm?>" style='cursor:help'  id="ord_<?=$e50_codord?>"
  onMouseOut='parent.js_labelconta(false);' onMouseOver="parent.js_labelconta(true,'<?=$pc63_banco?>','<?=$pc63_agencia?>','<?=$pc63_conta?>');"	  
  ><small><?=$z01_nome?>  </small></td>
  <td class='bordas'><small><input type='button' name='con_<?=$e50_codord?>' value="Conta" onclick="js_conta('<?=$numcgm?>');">  </small></td>
  <td class='bordas' align='right'  style='cursor:help' onMouseOut='parent.js_label(false);' onMouseOver="parent.js_label(true,'<?=$e53_vlrpag?>','<?=$e53_vlranu?>');"><small><?=$e53_valor?> </small></td>
  
  <td class='bordas' align='right'> <small><?=db_input("disponivel_$e50_codord",6,$Iz01_numcgm,true,'text',3)?></small></td>
  <td class='bordas' align='right'> <small><?=db_input("valor_$e50_codord",6,$Ie53_valor,true,'text',($desativado == true?3:$db_opcao),"onChange='js_confere(this);'")?></small></td>
  <td class='bordas' align='right'><small><?=db_select("e83_codtipo_$e50_codord",$arr,true,1)?></small></td>
  </tr>
  <?
}
?>
<!--
<tr>
<td class='bordas' align='left' colspan='11'>
<b>Ordens em outras agendas: <small></b><?=$nords?></small>
</td>
</tr>
-->
</table>
</center>
</form>
</td>
</tr>
</table>
</body>
</html>
<script>
function js_conta(cgm){
  js_OpenJanelaIframe('top.corpo','db_iframe_pcfornecon','com1_pcfornecon001.php?novo=true&z01_numcgm='+cgm,'Pesquisa',true);
}
</script>