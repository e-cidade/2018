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
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

include("classes/db_pagordem_classe.php");
include("classes/db_pagordemconta_classe.php");
include("classes/db_empagetipo_classe.php");
include("classes/db_empord_classe.php");
include("classes/db_empagemov_classe.php");
include("classes/db_empagemovconta_classe.php");
include("classes/db_empagepag_classe.php");
include("classes/db_pcfornecon_classe.php");
include("classes/db_empageforma_classe.php");
include("classes/db_empagemovforma_classe.php");
include("classes/db_empageconf_classe.php");
include("model/agendaPagamento.model.php");

$clempagetipo = new cl_empagetipo;
$clpagordem   = new cl_pagordem;
$clpagordemconta   = new cl_pagordemconta;
$clempord     = new cl_empord;
$clempagemov  = new cl_empagemov;
$clempagemovconta  = new cl_empagemovconta;
$clempagepag  = new cl_empagepag;
$clpcfornecon  = new cl_pcfornecon;
$clempageforma = new cl_empageforma;
$clempagemovforma = new cl_empagemovforma;
$clempageconf = new cl_empageconf;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$oGet     = db_utils::postMemory($_GET);
$db_opcao = 1;
$db_botao = false;
$numrows_pagordemconta = 0;
$clrotulo = new rotulocampo;
$clrotulo->label("e50_codord");
$clrotulo->label("o15_descr");
$clrotulo->label("o15_codigo");
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("e60_emiss");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("e80_codage");
$clrotulo->label("e83_codtipo");
$clrotulo->label("e53_valor");
$clrotulo->label("e53_vlranu");
$clrotulo->label("e53_vlrpag");
$clrotulo->label("e96_codigo");
$clrotulo->label("pc63_conta");

$dbwhere = " (round(e53_valor,2)-round(e53_vlranu,2)-round(e53_vlrpag,2)) > 0 and corempagemov.k12_codmov is null and e60_instit = " . db_getsession("DB_instit") . " and e80_instit = " . db_getsession("DB_instit");
$sJoin   = ""; 
if (isset($oGet->lOk)) {
  
  //Filtros para ordem de pagamento
  if ($oGet->iOrdemIni != '' && $oGet->iOrdemFim == "") {
    $dbwhere .= " and e50_codord = {$oGet->iOrdemIni}";
  } else if ($oGet->iOrdemIni != '' && $oGet->iOrdemFim != "") {
    $dbwhere .= " and e50_codord between  {$oGet->iOrdemIni} and {$oGet->iOrdemFim}";
  }
  
  if ($oGet->dtDataIni != "" && $oGet->dtDataFim == "") {
    $dbwhere .= " and e50_data = '".implode("-",array_reverse(explode("/",$oGet->dtDataIni)))."'";
  } else if ($oGet->dtDataIni != "" && $oGet->dtDataFim != "") {
    
    $dtDataIni = implode("-",array_reverse(explode("/",$oGet->dtDataIni)));
    $dtDataFim = implode("-",array_reverse(explode("/",$oGet->dtDataFim)));
    $dbwhere .= " and e50_data between '{$dtDataIni}' and '{$dtDataFim}'";
     
  }
  
  //Filtro para Empenho
  if ($oGet->iCodEmp!= '') {
    
    if (strpos($oGet->iCodEmp,"/")) {
      
      $aEmpenho = explode("/",$oGet->iCodEmp);
      $dbwhere .= " and e60_codemp = '{$aEmpenho[0]}' and e60_anousu={$aEmpenho[1]}";
      
    } else {
      $dbwhere .= " and e60_codemp = '{$oGet->iCodEmp}' and e60_anousu=".db_getsession("DB_anousu");
    }
    
  }
  
  //filtro para filtrar por credor
  if ($oGet->iNumCgm != '') {
    $dbwhere .= " and (e60_numcgm = {$oGet->iNumCgm} or e49_numcgm = {$oGet->iNumCgm})";
  }
  if ($oGet->iCodBanco != 0 ) {
    
    $sJoin   .= " inner join empagepag     on e81_codmov  = e85_codmov ";
    $sJoin   .= " inner join empagetipo    on e85_codtipo = e83_codtipo ";
    $sJoin   .= " inner join conplanoreduz on c61_reduz   = e83_conta ";
    $sJoin   .= "                         and c61_anousu  = ".db_getsession("DB_anousu");
    $sJoin   .= " inner join conplanoconta on c61_codcon  = c63_codcon ";
    $sJoin   .= "                         and c61_anousu  = c63_anousu";
    $dbwhere .= " and c63_banco = '{$oGet->iCodBanco}'";
    
  }
}
if (isset($oGet->sRecursos)) {
  $recursos = $oGet->sRecursos;
} else {
  $recursos = "todos";
}
$iNumRows = 0;
$sql_pagordem = $clpagordem->sql_query_empagemovforma(
         null,
         "
		 distinct empagemov.e81_codage as e80_codage, 
		          empagemov.e81_codmov,
		          case when trim(a.z01_numcgm) is not null then a.z01_numcgm else cgm.z01_numcgm end as z01_numcgm,
		          case when trim(a.z01_nome)   is not null then a.z01_nome   else cgm.z01_nome   end as z01_nome,
		          case when trim(a.z01_cgccpf) is not null then a.z01_cgccpf else cgm.z01_cgccpf end as z01_cgccpf,
		          e50_data,
		          o15_codigo,
		          o15_descr,
		          e60_emiss,
		          e60_anousu,
		          e60_numemp,
		          e60_codemp,
		          e50_codord,
		          sum(e53_valor)  as e53_valor,
		          sum(e53_vlranu) as e53_vlranu,
		          sum(e53_vlrpag) as e53_vlrpag,
		          sum(e81_valor)  as e81_valor",
         "
                  e50_codord,
                  e81_codmov
         ",
         "
         $dbwhere
         group by e60_numemp, 
                  e60_codemp, 
                  e50_codord,
                  e50_data,
                  cgm.z01_numcgm,
                  a.z01_numcgm,
                  cgm.z01_nome,
                  a.z01_nome,
                  e60_emiss,
                  o15_codigo,
                  o15_descr,
                  e60_anousu,
                  e81_codage,
                  cgm.z01_cgccpf,
                  a.z01_cgccpf,
                  empagemov.e81_codmov
         ",$sJoin);
if (isset($oGet->lOk)) {
  
  $result_pagordemconta = $clpagordem->sql_record($sql_pagordem); 
  $numrows_pagordemconta= $clpagordem->numrows;
 
}

//db_criatabela($result_pagordemconta);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">

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
     // js_colocaval(OBJ.elements[i]);
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
  //   eval('document.form1.valor_'+campo.value+'.value='+valor);
  }
}

function js_padrao(val){
  var OBJ = document.form1;
  for(i=0;i<OBJ.length;i++){
    nome = OBJ.elements[i].name;
    tipo = OBJ.elements[i].type;
    if( tipo.substr(0,6) == 'select' && nome.substr(0,11)=='e83_codtipo'){
      ord = nome.substr(12);
      for(q=0; q<OBJ.elements[i].options.length; q++){
        if(OBJ.elements[i].options[q].value==val){
          OBJ.elements[i].options[q].selected=true;
          break;
        }
      }
    }
  }
}
function js_verificacampo(nomecampo,valforma){
  arr_nome = nomecampo.split("_");  
  numordem = arr_nome[1];
  valorcam = eval("document.form1."+nomecampo+".value");
  if(valforma=="2" && valorcam=="3"){
    alert("Fornecedor sem conta cadastrada.\n Selecione outra forma de pagamento.");
    eval("document.form1."+nomecampo+".selectedIndex = 0;");
  }
}
function js_conta(cgm,opcao,nomecampo){
  var OBJ = document.form1;
  erro = 0;
  if(opcao == 'n' || opcao == 'button'){
    for(i=0;i<OBJ.length;i++){
      if(OBJ.elements[i].checked==true && OBJ.elements[i].type == 'checkbox'){
        erro++;
      }
    }
    if(erro == 0){
      js_OpenJanelaIframe('top.corpo','db_iframe_pcfornecon','com1_pcfornecon001.php?novo=true&reload=true&z01_numcgm='+cgm,'Pesquisa',true);
    }else{
      alert("Atualize os movimentos selecionados antes de cadastrar nova conta.");
    }
  }
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" height="100" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC"> 
      <form name="form1" method="post" action="">
      <center>
      <table  cellspacing="0" background="white" width="100%" style="border:2px inset white;background-color: white">
        <tr>
          <th class='table_header' align='center'><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></th>
          <th class='table_header' align='center'>
            <small><b><?=$RLe60_codemp?></b></small>
          </th>
          <th class='table_header' align='center'>
            <small><b><?=$RLe50_codord?></b></small>
          </th>
          <th class='table_header' align='center'>
            <small><b>Conta pagadora</b></small>
          </th>
          <th class='table_header' align='center'>
            <small><b>Recurso</b></small>
          </th>
          <th class='table_header' align='center'>
            <small><b><?=$RLz01_nome?></b></small>
          </th>
          <th class='table_header' align='center' nowrap>
            <small><b><?/*=$RLe60_emiss*/?>Banco - Agência - Conta (credor)</b></small>
          </th>
          <th class='table_header' align='center'>
            <small><b>Forma pgto.</b></small>
          </th>
          <th class='table_header' align='center'>
            <small><b>Total OP</b></small>
          </th>
          <th class='table_header' align='center'>
            <small><b>Liberado OP</b></small>
          </th>
          <th class='table_header' align='center'>
            <small><b>Valor a pagar</b></small>
          </th>
          <th class='table_header' align='center'>
            <small><b><?=$RLe80_codage?></b></small>
          </th>
	    </tr>
        <?
	    $nords =  '';
	    $nvirg ='';
	    $arr_forma = Array();
	    $result_forma = $clempageforma->sql_record($clempageforma->sql_query_file(null,"e96_codigo,e96_descr","e96_codigo"));
	    $arr_forma[0] = "NDA";
	    for($i=0;$i<$clempageforma->numrows;$i++){
	      db_fieldsmemory($result_forma,$i);
	      $arr_forma[$e96_codigo] = $e96_descr;
	    } 
         $index = 0;
	    for($i=0;$i<$numrows_pagordemconta;$i++){
	      db_fieldsmemory($result_pagordemconta,$i);
            $lVinculadas = false;

//          if ($e60_numemp != 11808 and $e60_numemp != 8707) continue;

            $codigodaforma = "for_$e50_codord";
            $$codigodaforma = 0;

            $x= "e60_numemp_$e50_codord";
            $$x = $e60_numemp;



           //--------------------------------------
           //rotina que verifica se tem movimento para a ordem nesta agenda.. se tiver ele marca o campo checkbox
           $result01 = $clempagemov->sql_record($clempagemov->sql_query_ord(null,'e81_codmov,e81_valor','',"e82_codord=$e50_codord and e81_codage=$e80_codage and e80_instit = " . db_getsession("DB_instit")));
           if($clempagemov->numrows>0){
             db_fieldsmemory($result01,0);

             $result_empagemovforma = $clempagemovforma->sql_record($clempagemovforma->sql_query_file($e81_codmov,"e97_codforma"));
             if($clempagemovforma->numrows>0){
               db_fieldsmemory($result_empagemovforma,0);
               $$codigodaforma = $e97_codforma;
             }
                         //rotina que verifica quais movimentos eh para trazer.. se todos,selecionados e naum selecionados
               //---------------------------------------------------------
               //pega o tipo do movimento
                 $result01 = $clempagepag->sql_record($clempagepag->sql_query_file($e81_codmov,null,"e85_codtipo"));
                 if($clempagepag->numrows>0){
                   db_fieldsmemory($result01,0);
                   $x= "e83_codtipo_$e50_codord";
                   $$x = $e85_codtipo;

                   $lVinculadas = true;

                 }
              //-------------------------------------------------------------

              $result_empageconf = $clempageconf->sql_record($clempageconf->sql_query_file(null,"e86_codmov","","e86_codmov=$e81_codmov and e86_correto<>'f'"));
              if($clempageconf->numrows>0){
                continue;
              }
           }else{
             //verifica se eh para trazer apenas os selecionados
             $e81_valor = '0.00';
             
             
          }

         //coloca o valor com campo
           $x= "valor_$e50_codord";
           $$x = number_format($e81_valor,"2",".","");

          //rotina que verifica se existe valor disponivel
             $result03  = $clempagemov->sql_record($clempagemov->sql_query_ord(null,"e82_codord,sum(e81_valor) as tot_valor",""," e82_codord = $e50_codord and e80_instit = " . db_getsession("DB_instit") . " group by e82_codord "));
             $numrows03 = $clempagemov->numrows;
             if($numrows03 > 0){
             	              db_fieldsmemory($result03,0);
             }else{
               $tot_valor ='0.00';
             }

             $result033  = $clempagemov->sql_record($clempagemov->sql_query_ord(null,"e82_codord,sum(e81_valor) as tot_valor_desta",""," e82_codord = $e50_codord and e81_codage = $e80_codage and e80_instit = " . db_getsession("DB_instit") . " group by e82_codord "));
             $numrows033 = $clempagemov->numrows;
             if($numrows033 > 0){
               db_fieldsmemory($result033,0);
             } else {
               $tot_valor_desta = 0;
             }

            $total = $e53_valor - $e53_vlrpag - $e53_vlranu;
            if ($total==$tot_valor_desta){ // Quando op for parcial, jÃ¡existe um pgto confirmado
                 $disponivel = $tot_valor_desta;    
            }else {
                 $disponivel = $total - $tot_valor + $tot_valor_desta;
            }     

            /*
            echo "<BR><BR>
            $total = $e53_valor - $e53_vlrpag - $e53_vlranu;
            $disponivel = $total - $tot_valor + $tot_valor_desta;
            ";
            */
//          $disponivel = $total - ($tot_valor - $e81_valor);

            $x= "disponivel_$e50_codord";
            $$x = number_format($disponivel,"2",".","");
           //=-------------------------------------------

//            die("ord: $e50_codord - disp: $disponivel\n");

           //echo $disponivel."<br><br>"; 
           //echo $total." => ".$tot_valor." => ".$tot_valor_desta."<br><br>";

           if( ($disponivel == 0 || $disponivel < 0)){
              //echo $e50_codord." sem valor disponivel!";
              $nords .= $nvirg.$e50_codord;
              $nvirg = " ,";
              continue;
           }

          //pega os tipos
          $zero = false;
          if($e60_anousu == db_getsession("DB_anousu") && $recursos != 'todos'){
              $result05  = $clempagetipo->sql_record($clempagetipo->sql_query_contas_vinculadas(null,
                         " e83_codtipo as codtipo,e83_descr, e83_conta, c61_codigo","e83_descr",
                         "e60_numemp=$e60_numemp", $lVinculadas));
              $numrows05 = $clempagetipo->numrows;
              if($numrows05 == 0){
                $zero = true;
              }
          }
          if($e60_anousu < db_getsession("DB_anousu") or $recursos == 'todos' or $zero==true){
//              $result05  = $clempagetipo->sql_record($clempagetipo->sql_query(null,"e83_codtipo as codtipo, e83_descr, e83_conta, c61_codigo","e83_descr"));

							$result05 = $clempagetipo->sql_record	("select * from (" .
																											$clempagetipo->sql_query_emprec(null,"distinct 1 as tipo_conta, e83_conta, e83_codtipo as codtipo, e83_descr, c61_codigo",""," e60_numemp=$e60_numemp")
																											. " union " .
																											$clempagetipo->sql_query_contapaga(null,"distinct 2 as tipo_conta, e83_conta, e83_codtipo as codtipo, e83_descr, c61_codigo",""," c61_codigo = 1") . "
																											) as x order by tipo_conta, e83_conta"
																											);

							
              $numrows05 = $clempagetipo->numrows;
          }
          $arr = Array();
          $arr['0']="Nenhum";
          for($r=0; $r<$numrows05; $r++){
            db_fieldsmemory($result05,$r);
            $arr[$codtipo] = $e83_conta . " - " . $e83_descr . " - " . str_pad($c61_codigo, 4, "0", STR_PAD_LEFT);
            if($numrows05==1 && !isset($e83_codtipo)){
              $t = "e83_codtipo_$e50_codord";
              $$t = $codtipo;
            }
          }
          flush();



            if(isset($e83_codtipo)){
              $t = "e83_codtipo_$e50_codord";
              $$t = $e83_codtipo;
            }

         //rotina que verifica se o fornecedor possui conta cadastrada para pagamento eletrônico
         $outr = '';
         $result = $clpagordemconta->sql_record($clpagordemconta->sql_query($e50_codord,"e49_numcgm"));
         if($clpagordemconta->numrows>0){
          db_fieldsmemory($result,0);
           $numcgm = $e49_numcgm;
             $outr = "<span style=\"color:red;\">**</span>";
         }else{
           $numcgm = $z01_numcgm;
         }

         $arr_contas = Array();
         $dataconf   = "";
         $result_contasforn = $clpcfornecon->sql_record($clpcfornecon->sql_query_file(null,"pc63_agencia,pc63_agencia_dig,pc63_banco,pc63_conta,pc63_conta_dig,pc63_contabanco,pc63_cnpjcpf,pc63_dataconf",'',"pc63_numcgm=$numcgm"));
         $numrows_contasforn = $clpcfornecon->numrows;
         $arr_contas['n']="Nova conta";
         $arr_contas[0] = "Nenhum";
         $index = 0;
         $conferido = "";
        if($numrows_contasforn>0){
           for($ii=0;$ii<$clpcfornecon->numrows;$ii++){
             db_fieldsmemory($result_contasforn,$ii);
//             pc63_agencia,pc63_agencia_dig,pc63_banco,pc63_conta,pc63_contabanco
             if(trim($pc63_agencia_dig)!=""){
               $pc63_agencia_dig = "/".$pc63_agencia_dig;
             }
             if(($pc63_conta_dig)!=""){
               $pc63_conta_dig = "/".$pc63_conta_dig;
             }

             if(isset($pc63_dataconf) && trim($pc63_dataconf)!=""){
               $contapad = "con_$e50_codord";
               $$contapad = $pc63_contabanco;
               $conferido = "**";
             }

             $arr_contas[$pc63_contabanco] = $pc63_banco.' - '.$pc63_agencia.$pc63_agencia_dig.' - '.$pc63_conta.$pc63_conta_dig;
             $arr_cnpjcpf[$pc63_contabanco] = $pc63_cnpjcpf;
             $arr_index[$index] = $pc63_contabanco;
             $index++;
           }
         }
         
         if(isset($e81_codmov)){
           $result_movconta = $clempagemovconta->sql_record($clempagemovconta->sql_query_file($e81_codmov,"e98_contabanco as con_$e50_codord"));
           $numrows_movconta = $clempagemovconta->numrows;
           if($numrows_movconta>0){
             db_fieldsmemory($result_movconta,0);
           }
         }else{
           $numrows_movconta = 0;
         }

         if($numrows_movconta==0){
           $result_contapad = $clpcfornecon->sql_record($clpcfornecon->sql_query_padrao(null,"pc63_contabanco as con_".$e50_codord,'',"pc63_numcgm=$numcgm"));
           $numrows_contapad = $clpcfornecon->numrows;
           if($numrows_contapad > 0){
             db_fieldsmemory($result_contapad,0);
           }else{
             $contapad = "con_$e50_codord";
             $$contapad = 0;
           }
         }             
                ?>


        <tr>
          <td class='linhagrid' align='right'><input value="<?=$e50_codord?>" name="CHECK_<?=$e50_codord?>" type='checkbox'></td>
          <td class='linhagrid' align='right' title="<?=($RLe60_codemp)?> - Data de emissão:<?=$e60_emiss?>">
          <?
          $codigoempenho = 'e60_numemp_'.$e50_codord;
          $$codigoempenho = $e60_numemp;
          db_input('e60_numemp_'.$e50_codord,5,$Ie60_numemp,true,'hidden',3);
          echo "{$e60_codemp}/{$e60_anousu}";
          ?>
          </td>
          <td class='linhagrid' align='right' title="<?=$RLe50_codord?> - Data de emissão:<?=$e50_data?>">
            <small><?=$e50_codord?></small>
          </td>
          <td class='linhagrid' title='Conta pagadora' align='left'><?=db_select("e83_codtipo_$e50_codord",$arr,true,1,"style='width:300px'")?></td>
          <td class='linhagrid' align='left' title="Recurso">
            <small><?=($o15_codigo." - ".$o15_descr)?></small>
          </td>
          <td class='linhagrid' title="<?=$RLz01_nome?>" label="Numcgm:<?=$z01_numcgm?>" id="ord_<?=$e50_codord?>">
            <small><strong><span style="color:red;"><?=($conferido)?></span></strong></small>
            <small><?=$z01_nome?></small>
          </td>
          <?
          $cpfcgc = "cpfcgc_$e50_codord";
          $$cpfcgc = $z01_cgccpf;
          if(sizeof($arr_contas)>2){
            echo "<td class='linhagrid' align='left' title='Banco - Agência - Conta (credor)'>";
            echo "<input type='hidden' name='conta_$e50_codord'>";
            db_select("con_$e50_codord",$arr_contas,$Ipc63_conta,1,"onchange='js_conta(\"$numcgm\",this.value,\"con_$e50_codord\");'style='width:100%'");
            db_input("cpfcgc_$e50_codord",6,0,true,'hidden',3);
            echo "</td>";
            $verificacampo = 1;
            $formapagto = "for_$e50_codord";
            $$formapagto = 3;
          }else{
            echo "<td class='linhagrid' align='center' title='Banco - Agência - Conta (credor)'>";
            echo "<input type='button' name='con_$e50_codord' value='Cadastrar conta' onclick='js_conta(\"$numcgm\",\"button\",\"con_$e50_codord\");'>";
            db_input("con_$e50_codord",6,$Ipc63_conta,true,'hidden',3);
            db_input("cpfcgc_$e50_codord",6,0,true,'hidden',3);
            echo "</td>";
            $verificacampo = 2;
          }
          ?>
          <td class='linhagrid' nowrap title='Forma de pagamento'><small>
          <?
            echo "
                  <script>
                  function js_vercpfcgc$e50_codord(campo,valor,cgccpf){
                    arr_valores = new Array();
                    TouF = false;
            ";
              for($iq=0;$iq<$index;$iq++){
                $valorarray = $arr_index[$iq];
                $cpfcnpj    = $arr_cnpjcpf[$valorarray];
//              echo "alert('$iq. -- .$valorarray. -- .$cpfcnpj');\n";
                echo "arr_valores['$cpfcnpj'] = '$cpfcnpj';\n";
                if($iq==0){
                  echo "TouF = true;\n";
                }
              }
            echo "
                    if(valor==3){
                      if(TouF == true){
                        if(js_verificaCGCCPF(eval('document.form1.'+cgccpf))==false){
                          alert('Fornecedor com CGC/CPF inválido');
                        }
                      }else{
                        alert('Fornecedor sem CGC/CPF cadastrado.');
                      }
                    }
                  }
                  </script>
            ";
          $oAgenda = new agendaPagamento();
          ${"for_$e50_codord"} = $oAgenda->getformaPagamentoCGM($z01_numcgm);
          db_select("for_$e50_codord",$arr_forma,$Ie96_codigo,1,"onchange='js_verificacampo(this.name,\"$verificacampo\");js_vercpfcgc$e50_codord(this.name,this.value,\"$cpfcgc\");'");
          unset($oAgenda);
          ?>
          </small></td>
          <td class='linhagrid' title='Valor total OP' align='right'  style='cursor:help' onMouseOut='parent.js_label(false);'onMouseOver="parent.js_label(true,'<?=$e53_vlrpag?>','<?=$e53_vlranu?>');"><?=(db_formatar($e53_valor,"f"))?></td>
          <td class='linhagrid' title='Valor liberado OP' align='right'>
            <small><?=db_input("disponivel_$e50_codord",10,$Iz01_numcgm,true,'text',3)?></small>
          </td>
          <td class='linhagrid' title='Valor a pagar' align='right'><?=db_input("valor_$e50_codord",10,$Ie53_valor,true,'text',$db_opcao,"onChange='js_confere(this);'")?></td>
          <td class='linhagrid' align='center' title='<?=($RLe80_codage)?>'>
          <?
          $codigodaagenda = 'e80_codage_'.$e50_codord;
          $$codigodaagenda = $e80_codage;
          db_input('e80_codage_'.$e50_codord,4,$Ie80_codage,true,'text',3);
          ?>
          </td>
        </tr>
        <?
        $iNumRows++;
	    }
	     echo "<script>parent.document.getElementById('totalregistros').innerHTML = {$iNumRows};</script>"; 
	    ?>
      </table>
      </center>
      </form>
    </td>
  </tr>
</table>
</body>
</html>