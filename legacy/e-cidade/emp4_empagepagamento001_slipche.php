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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

require_once("classes/db_pagordem_classe.php");
require_once("classes/db_empagetipo_classe.php");
require_once("classes/db_empord_classe.php");
require_once("classes/db_empagemov_classe.php");
require_once("classes/db_empageconf_classe.php");
require_once("classes/db_empagepag_classe.php");

require_once("classes/db_slip_classe.php");
require_once("classes/db_slipnum_classe.php");

$clempagetipo = new cl_empagetipo;
$clpagordem   = new cl_pagordem;
$clempord     = new cl_empord;
$clempagemov  = new cl_empagemov;
$clempagepag  = new cl_empagepag;
$clempageconf  = new cl_empageconf;

$clslip       = new cl_slip;
$clslipnum    = new cl_slipnum;


require_once("dbforms/db_classesgenericas.php");
$cliframe_seleciona = new cl_iframe_seleciona;


parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
//db_postmemory($HTTP_SERVER_VARS,2);

$db_opcao = 1;
$db_botao = false;

$clrotulo = new rotulocampo;
$clrotulo->label("k17_codigo");
$clrotulo->label("k17_valor");
$clrotulo->label("k17_data");
$clrotulo->label("k17_debito");
$clrotulo->label("k17_credito");
$clrotulo->label("c50_descr");
$clrotulo->label("c60_descr");
$clrotulo->label("z01_nome");
$clrotulo->label("e83_conta");

  
// $dbwhere = " k17_instit = ".db_getsession("DB_instit"); 

$dbwhere  = " e80_instit = " . db_getsession("DB_instit") . " and k17_dtanu is null ";
$dbwhere .= " and c60_anousu=".db_getsession("DB_anousu");
$dbwhere .= " and empageconfche.e91_codmov is not null and e91_ativo is true ";
$dbwhere .= " and  k17_dtaut is null ";

if(isset($k17_codigo) && $k17_codigo != '' && isset($k17_codigo2) && $k17_codigo2 != '' ){
  $dbwhere .=" and s.k17_codigo between $k17_codigo and $k17_codigo2 ";
}else if(  (empty($k17_codigo) || ( isset($k17_codigo) && $k17_codigo == '')   )  && isset($k17_codigo2) && $k17_codigo2 != '' ){
  $dbwhere .=" and s.k17_codigo <= $k17_codigo2 ";
}else if(isset($k17_codigo) && $k17_codigo != ''){
  $dbwhere .= " and s.k17_codigo = $k17_codigo";
}

if(isset($z01_numcgm) && $z01_numcgm != ''){
  $dbwhere .= " and z01_numcgm = $z01_numcgm";
}
if(isset($cheque) && $cheque != ''){
  $dbwhere .= " and e91_cheque = '$cheque'";
}
if(isset($e83_codtipo) && $e83_codtipo != ''){
  $dbwhere .= " and e85_codtipo = $e83_codtipo";
}
if(isset($e80_codage) && $e80_codage != ''){
  $dbwhere .= " and e81_codage = $e80_codage";
}

if(isset($dtfi) && trim($dtfi)!="" and (!isset($dtDataChequeFinal))){
  $arr_data = split("_",$dtfi);
  $dbwhere .= " and e86_data = '".$arr_data[0]."-".$arr_data[1]."-".$arr_data[2]."'";
}
if (isset($dtDataChequeFinal) && isset($dtfi) && trim($dtfi)!="") {

   $arr_data      = split("_",$dtfi);
   $dtChequeFinal = implode("-",array_reverse(explode("/", $dtDataChequeFinal))); 
   $dbwhere      .= " and e86_data between '".$arr_data[0]."-".$arr_data[1]."-".$arr_data[2]."'";
   $dbwhere      .= " and '{$dtChequeFinal}'";
  
}
if (isset($dtDataSlipInicial) && !isset($dtDataSlipFinal)) {

   $dtSlipInicial = implode("-",array_reverse(explode("/", $dtDataSlipInicial))); 
   $dbwhere      .= " and k17_data =  '{$dtSlipInicial}'";
  
} else if (isset($dtDataSlipInicial) && isset($dtDataSlipFinal)) {

   $dtSlipFinal   = implode("-",array_reverse(explode("/", $dtDataSlipFinal)));
   $dtSlipInicial = implode("-",array_reverse(explode("/", $dtDataSlipInicial))); 
   $dbwhere      .= " and k17_data between  '{$dtSlipInicial}'";
   $dbwhere      .= " and '{$dtSlipFinal}'";
  
}
$oParametro = db_stdClass::getParametro("empparametro",array(db_getsession("DB_anousu")));
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
function js_calcula(){

  chaves =  js_retorna_chaves();

  if(chaves != ''){

    tot = new Number(0);
    arr = chaves.split("#"); 
    parent.document.form1.registros.value = arr.length;

    for(i=0; i<arr.length; i++){

      dad = arr[i];
      arr_dad = dad.split("-"); 
      valor = new Number(arr_dad[3]);
      tot = new Number(tot+valor);
    }

  }else{
    tot = '0.00';
  } 
 
  tot = new Number(tot);
  parent.document.form1.tot.value = js_formatar(tot, 'f');
}
</script>
<script language="javascript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="javascript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="javascript" type="text/javascript" src="scripts/strings.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
<?

          $sql_disabled = $clempageconf->sql_query_cancslip(null,"e91_codcheque","",$dbwhere." and ((k17_autent>0 and k12_codmov is not null) or (k12_codmov is not null))");
	  
          $sql = $clempageconf->sql_query_cancslip(null,"s.k17_codigo,e91_codcheque,e91_cheque,e86_data, e91_valor,k17_credito,k17_debito,k17_data,c50_descr,c60_descr,z01_nome,e89_codmov,k17_instit",'s.k17_codigo',$dbwhere." and e90_cancelado is false");
//          echo $sql." <BR><BR> ".$sql_disabled;


    $cliframe_seleciona->textocabec ="black";
	  $cliframe_seleciona->textocorpo ="black";
	  $cliframe_seleciona->fundocabec ="#999999";
	  $cliframe_seleciona->fundocorpo ="#cccccc";
	  $cliframe_seleciona->iframe_height ="300";
	  $cliframe_seleciona->iframe_width ="750";
	  $cliframe_seleciona->iframe_nome ="canc";
	  $cliframe_seleciona->fieldset =false;

	  $cliframe_seleciona->js_marcador = "parent.js_calcula();";
	  $cliframe_seleciona->dbscript = "onclick='parent.js_calcula();parent.verificarCheques(this)'";
	  $cliframe_seleciona->desabilitados = false;
	  $cliframe_seleciona->checked = true;
	  
    $campos  = "e91_codcheque,k17_codigo,e91_cheque,e91_valor,e86_data,k17_credito,k17_debito,k17_data,c50_descr,c60_descr,z01_nome,e89_codmov";
	  $cliframe_seleciona->campos  = $campos; 
	  $cliframe_seleciona->sql = $sql;
	  $cliframe_seleciona->sql_disabled = $sql_disabled;
	  $cliframe_seleciona->chaves ="e91_codcheque,k17_codigo,e89_codmov,e91_valor";
	  $cliframe_seleciona->iframe_seleciona(1);    

    $sql    = $clslip->sql_query_cheque(null,"sum(e91_valor) as tot,count(e91_valor) as registros",'',$dbwhere);
	  $result  = $clslip->sql_record($sql);

          db_fieldsmemory($result,0);
          
?>	  
    </center>
    </td>
  </tr>
 <?
    if ($oParametro[0]->e30_agendaautomatico == "t") {
  ?>
  <tr>
    <td>
      <input name="atualizar" type="submit" id='efetuapagamento' value="Pagamento de Slips"
            onclick='parent.document.form1.atualizar.click();'>
    </td>
  </tr>
  <?
   }
  ?>
</table>
</body>
</html>
<script>
   parent.document.form1.tot.value = js_formatar('<?=$tot?>', 'f');
   parent.document.form1.registros.value = '<?=$registros?>';
   parent.document.form1.tipo.value = 'slip_cheque';
  <?
    if ($oParametro[0]->e30_agendaautomatico == "f") {
     echo "  parent.document.form1.atualizar.disabled = false;\n";
    }
  ?>
<?

if($registros > 0){
  echo "parent.document.form1.atualizar.disabled=false;";
}
?>

  function verificarCheques(oCheckBoxClicado) {

    var aCheckBoxes = [];
    var aInputs     = (canc.document.getElementsByTagName('input'));

    var lChequesMesmoSlip    = false;
    var iCodigoSlipClicado   = getCodigoSlip(oCheckBoxClicado.value);
    var iNumeroChequeClicado = getNumeroCheque(oCheckBoxClicado.value);
    for (var iInput = 0; iInput  < aInputs.length; iInput++) {

      var oInput = aInputs[iInput];
      if (oInput.type != 'checkbox' || oInput.id == oCheckBoxClicado.id ) {
        continue;
      }
      var iCodigoSlip   = getCodigoSlip(oInput.value);
      var iNumeroCheque = getNumeroCheque(oInput.value);
      if (iCodigoSlip == iCodigoSlipClicado) {

        oInput.checked    = oCheckBoxClicado.checked;
        lChequesMesmoSlip = true;
      }
    }
    if (lChequesMesmoSlip) {
      alert('Há slip com mais de um cheque. Todos os cheques do slip serão marcados.');
    }
  }

  function getCodigoSlip(sLinha) {
    return sLinha.split('_')[1];
  }

  function getNumeroCheque(sLinha) {
    return sLinha.split('_')[2];
  }
</script>


<?/*
<!--
<form name="form1" method="post" action="">
    <center>
      <table  class='bordas'>
        <tr>
          <td class='bordas02' align='center'><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>
          <td class='bordas02' align='center' ><small><b><?=$RLk17_codigo?></b></small></td>
          <td class='bordas02' align='center' ><small><b><?=$RLk17_credito?></b></small></td>
          <td class='bordas02' align='center' ><small><b><?=$RLk17_debito?></b></small></td>
          <td class='bordas02' align='center' ><small><b><?=$RLc60_descr?></b></small></td>
          <td class='bordas02' align='center' ><small><b><?=$RLe40_descr?></b></small></td>
          <td class='bordas02' align='center' ><small><b><?=$RLk17_data?></b></small></td>
          <td class='bordas02' align='center' ><small><b><?=$RLk17_valor?></b></small></td>
          <td class='bordas02' align='center'><small><b><?=$RLe83_conta?></b></small></td>
          <td class='bordas02' align='center' ><small><b><?=$RLz01_nome?></b></small></td>
	</tr>
        <?
	   $nords =  '';
	   $nvirg ='';
	  for($i=0; $i<$numrows; $i++){
	    db_fieldsmemory($result,$i,true);

	    $result0  = $clempageconf->sql_record($clempageconf->sql_query_file(null,"e86_codmov","","e86_codmov=$e89_codmov"));
	    
	    $result05  = $clempagetipo->sql_record($clempagetipo->sql_query_file(null,"e83_codtipo as codtipo,e83_descr","e83_descr"));
	    $numrows05 = $clempagetipo->numrows;
	    $arr['0']="Nenhum";
	    for($r=0; $r<$numrows05; $r++){
	      db_fieldsmemory($result05,$r);
	      $arr[$codtipo] = $e83_descr;
	    }
	    flush();
        
         
	   //coloca o valor com campo
	    $x= "valor_$k17_codigo";
	    $$x = number_format($e91_valor,"2",".","");
            db_input("valor_$k17_codigo",6,'',true,'hidden',1,1);
           //------------ 

	   $xeque = '';
           if($e89_codigo != ""){
	     $xeque = 'checked';
               
	       //---------------------------------------------------------
	       //pega o tipo do movimento
		 $result01 = $clempagepag->sql_record($clempagepag->sql_query_slip($e89_codmov,null,"e83_conta"));
		 if($clempagepag->numrows>0){
		   db_fieldsmemory($result01,0,true);
		   //= "e83_codtipo_$k17_codigo";
		   //x = $e85_codtipo;
		 }
              //-------------------------------------------------------------	 

	   } 
	   
	?>
        <tr>
          <td class='bordas' align='right'><input value="<?=$k17_codigo?>"  name="CHECK_<?=$k17_codigo?>" <?=$xeque?> type='checkbox'></td>
          <td class='bordas' align='right'><small > <?=$k17_codigo?></small></td>
          <td class='bordas' align='right'><small > <?=$k17_credito?></small></td>
          <td class='bordas' align='right'><small > <?=$k17_debito?></small></td>
          <td class='bordas' align='left' nowrap ><small > <?=(substr($c60_descr,0,20))?></small></td>
          <td class='bordas' align='right'><small > <?=$e40_descr?></small></td>
          <td class='bordas' align='right'><small > <?=$k17_data?></small></td>
          <td class='bordas' align='right'><small > <?=$e91_valor?></small></td>
          <td class='bordas' align='right'><small> <?=$e83_conta?></small></td>
          <td class='bordas' align='left' nowrap><small >&nbsp; <?=$z01_nome?></small></td>
	</tr>
        <?
	  }
	?>
      </table>
    </center>
    </form>
    -->
    */?>