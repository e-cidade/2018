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

//include("classes/db_empageconfche_classe.php");
//$clempageconfche  = new cl_empageconfche;
include("classes/db_pagordem_classe.php");
include("classes/db_empagetipo_classe.php");

$clpagordem    = new cl_pagordem;
$clempagetipo  = new cl_empagetipo;

include("dbforms/db_classesgenericas.php");
$cliframe_seleciona = new cl_iframe_seleciona;
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
//db_postmemory($HTTP_SERVER_VARS,2);

$clrotulo = new rotulocampo;
$clrotulo->label("e50_codord");
$clrotulo->label("z01_nome");
$clrotulo->label("e60_codemp");
$clrotulo->label("e53_valor");
$clrotulo->label("e53_vlranu");
$clrotulo->label("e53_vlrpag");
$clrotulo->label('z01_numcgm');
$clrotulo->label('e83_codtipo');

$db_opcao = 1;
$db_botao = false;

$clrotulo = new rotulocampo;

//$dbwhere = "  empord.e82_codord is null ";
$dbwhere   = "";
$dbwhere  .= "(e53_valor - e53_vlranu - e53_vlrpag) > 0 ";
$dbwhere  .= " and e60_instit = ".db_getsession("DB_instit");

$flag_filtros = false;
if(isset($e50_codord) && $e50_codord != '' && isset($e50_codord02) && $e50_codord02 != '' ){
  $dbwhere .=" and e50_codord >=$e50_codord and e50_codord <= $e50_codord02 ";
  $flag_filtros = true;
}else if(  (empty($e50_codord) || ( isset($e50_codord) && $e50_codord == '')   )  && isset($e50_codord02) && $e50_codord02 != '' ){
  $dbwhere .=" and e50_codord <= $e50_codord02 ";
  $flag_filtros = true;
}else if(isset($e50_codord) && $e50_codord != '' ){
  $dbwhere .=" and e50_codord=$e50_codord ";
  $flag_filtros = true;
}

if(isset($e60_codemp) && $e60_codemp != '' ){
  $dbwhere .=" and e60_codemp = $e60_codemp ";
  $flag_filtros = true;
}

if(isset($e60_numemp) && $e60_numemp != '' ){
  $dbwhere .=" and e60_numemp = $e60_numemp ";
  $flag_filtros = true;
}

if(isset($z01_numcgm) && $z01_numcgm != '' ){
  $dbwhere .=" and z01_numcgm = $z01_numcgm ";
  $flag_filtros = true;
}

if(isset($dtemp) && $dtemp !=''){
  $dtemp =  str_replace("_","-",$dtemp);
  $dbwhere .= " and e60_emiss = '$dtemp'";
  $flag_filtros = true;
}

if(isset($e80_codage) && $e80_codage != ''){
  $sql03="     select e82_codord 
  from empage
  inner join empagemov     on e81_codage = e80_codage
  inner join empord        on e82_codmov = e81_codmov
  left  join empageconfche on empageconfche.e91_codmov = empagemov.e81_codmov
  left  join empageconf    on e86_codmov               = empageconfche.e91_codcheque and e91_ativo is true
  where e80_instit = " . db_getsession("DB_instit") . " and e80_codage=$e80_codage ";
  //  $dbwhere .= " and e81_codage =  $e80_codage ";
  $flag_filtros = true;
}

if ($flag_filtros == false){  // Nao foi selecionado nenhum filtro
  $dbwhere = "e53_vlrpag = 0 and e60_anousu = ".db_getsession("DB_anousu")." and e60_instit = ".db_getsession("DB_instit");
}
//echo $dbwhere."<br><br>";
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_marca(obj){ 
  var OBJ = document.form1;
  for(i=0;i<OBJ.length;i++){
    if(OBJ.elements[i].type == 'checkbox' && OBJ.elements[i].disabled==false){
      OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);            
    }
    if(OBJ.elements[i].type == 'checkbox' && OBJ.elements[i].disabled==false){
      if(OBJ.elements[i].checked==true){
        valor = new Number(eval('document.form1.disponivel_'+OBJ.elements[i].value+'.value')); 
        v = valor.toFixed(2);
        eval('document.form1.valor_'+OBJ.elements[i].value+'.value="'+v+'"');
      }else{
        eval('document.form1.valor_'+OBJ.elements[i].value+'.value="0.00"');
      }
    }    	 
  }
  
  js_calcula();
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
    eval("document.form1."+campo.name+".focus()");
    eval("document.form1."+campo.name+".value = vlrlimite.toFixed(2);");
    return false;
  }  
  js_calcula();
  
}

function js_calcula(){
  var OBJ = document.form1;
  var tot = new Number(0);
  for(i=0;i<OBJ.length;i++){
    if(OBJ.elements[i].type == 'checkbox' && OBJ.elements[i].disabled==false){
      ord = OBJ.elements[i].value;
      val = new Number(eval("OBJ.valor_"+ord+".value;"));
      tot = new Number(tot+val);
    }
  }
  parent.document.form1.tot.value = tot.toFixed(2); 
  
}
function js_colocaval(campo){
  if(campo.checked==true){
    valor = new Number(eval('document.form1.disponivel_'+campo.value+'.value')); 
    v = valor.toFixed(2);
    eval('document.form1.valor_'+campo.value+'.value='+v);
  }else{
    eval('document.form1.valor_'+campo.value+'.value="0.00"');
  }
  js_calcula();
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
<td class='bordas02' align='center'><small><b><?=$RLe50_codord?></b></small></td>
<td class='bordas02' align='center' ><small><b><?=$RLe60_codemp?></b></small></td>
<td class='bordas02' align='center'><small><b><?=$RLz01_nome?></b></small></td>
<td class='bordas02' align='center'><small><b><?=$RLe53_valor?></b></small></td>
<td class='bordas02' align='center'><small><b><?=$RLe53_vlrpag?></b></small></td>
<td class='bordas02' align='center'><small><b><?=$RLe53_vlranu?></b></small></td>
<td class='bordas02' align='center'><small><b>Cheque</b></small></td>
<td class='bordas02' align='center'><small><b>Disp.</b></small></td>
<td class='bordas02' align='center'><small><b>Valor</b></small></td>
<td class='bordas02' align='center'><small><b><?=$RLe83_codtipo?></b></small></td>
</tr>

<?
$result = $clpagordem->sql_record($clpagordem->sql_query_pagordemele(null,"distinct e60_numemp,e50_codord,e60_anousu,e60_codemp,z01_nome,e53_valor,e53_vlranu,e53_vlrpag","e50_codord","$dbwhere"));
/*
echo($clpagordem->sql_query_pagordemele(null,"distinct e60_numemp,e50_codord,e60_anousu,e60_codemp,z01_nome,e53_valor,e53_vlranu,e53_vlrpag","e50_codord","$dbwhere"));
exit;
*/
$numrows = $clpagordem->numrows;

for($i=0; $i<$numrows; $i++){
  db_fieldsmemory($result,$i,true);
  
  $total = $e53_valor - $e53_vlrpag - $e53_vlranu;
  
  $chqemitido=0;
	
  $disponivel = $total;
  //- ($tot_valor - $e81_valor);
  
  $x= "disponivel_$e50_codord";
  $$x = number_format($disponivel,"2",".","");
  
  $x= "valor_$e50_codord";
  $$x = number_format($disponivel,"2",".","");
  if($$x==0){
    continue;
  }
  
  //pega os tipos
  if($e60_anousu < db_getsession("DB_anousu")){
    $rp = "<b style='color:red'>RP</b>"; 		
  }else{
    $rp ='';
  }
	
	$result05 = $clempagetipo->sql_record	("select * from (" .
																					$clempagetipo->sql_query_emprec(null,"distinct 1 as tipo_conta, e83_conta, e83_codtipo as codtipo, e83_descr, c61_codigo",""," e60_numemp=$e60_numemp")
																					. " union " .
																					$clempagetipo->sql_query_contapaga(null,"distinct 2 as tipo_conta, e83_conta, e83_codtipo as codtipo, e83_descr, c61_codigo ",""," c61_codigo = 1") . "
																					) as x order by tipo_conta, e83_conta"
																					);
  $numrows05 = $clempagetipo->numrows;
  $arr = array();
  $arr['0']="Nenhum";
  for($r=0; $r<$numrows05; $r++){
    db_fieldsmemory($result05,$r);
    $arr[$codtipo] = $e83_conta . " - " . $e83_descr . " - " . str_pad($c61_codigo, 4, "0", STR_PAD_LEFT);
  }
  flush();
$tipo=$clempagetipo->sql_record($clempagetipo->sql_query_conta(null,"distinct e83_codtipo as cod_tipo",null," e81_numemp=$e60_numemp and e50_anousu=".db_getsession("DB_anousu")));
if ($clempagetipo->numrows>0){
db_fieldsmemory($tipo,0);
${"e83_codtipo_{$e50_codord}"} = $cod_tipo;
}
  ?>	    
  <tr>
  <td class='bordas' align='right'>
  <input value="<?=$e50_codord?>" checked name="CHECK_<?=$e50_codord?>" type='checkbox' onclick='js_colocaval(this);'>
  <?=$rp?>
  </td>
  <td class='bordas' align='center'><small><?=$e50_codord?></small></td>
  <td class='bordas' align='center' ><small><?=$e60_codemp?></small></td>
  <td class='bordas' align='left'><small><?=$z01_nome?></small></td>
  <td class='bordas' align='center'><small><?=$e53_valor?></small></td>
  <td class='bordas' align='center'><small><?=$e53_vlrpag?></small></td>
  <td class='bordas' align='center'><small><?=$e53_vlranu?></small></td>
  <td class='bordas' align='center'><small><?=$chqemitido?></small></td>
  <td class='bordas' align='right'><small><?=db_input("disponivel_$e50_codord",6,$Iz01_numcgm,true,'text',3)?></small></td>
  <td class='bordas' align='right'><small><?=db_input("valor_$e50_codord",6,$Ie53_valor,true,'text',$db_opcao,"onChange='js_confere(this);'")?></small></td>
  <td class='bordas' align='right'><small><?=db_select("e83_codtipo_$e50_codord",$arr,true,1)?></small></td>
  </tr>
  <?	   
} 
?>	  
</table>
</center>
</form>
</td>
</tr>
</table>
</body>
</html>
<script>
js_calcula();
parent.document.form1.registros.value = '<?=$numrows?>';
parent.document.form1.tipo.value = 'ordem';

</script>
<?
if($numrows>0){
  echo "<script>";
  echo "parent.document.form1.atualizar.disabled=false;";
  echo "</script>";
}
?>