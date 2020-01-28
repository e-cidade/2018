<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require("std/db_stdClass.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

include("classes/db_pagordem_classe.php");
include("classes/db_empagetipo_classe.php");
include("classes/db_empord_classe.php");
include("classes/db_empagemov_classe.php");
include("classes/db_empageconf_classe.php");
include("classes/db_empagepag_classe.php");

include("classes/db_slip_classe.php");
include("classes/db_slipnum_classe.php");

$clempagetipo = new cl_empagetipo;
$clpagordem   = new cl_pagordem;
$clempord     = new cl_empord;
$clempagemov  = new cl_empagemov;
$clempagepag  = new cl_empagepag;
$clempageconf  = new cl_empageconf;

$clslip       = new cl_slip;
$clslipnum    = new cl_slipnum;


include("dbforms/db_classesgenericas.php");
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

  
$dbwhere = '(e97_codforma = 1 or  e97_codforma = 4) and k17_instit = '.db_getsession("DB_instit"); 
$dbwhere.= ' and k17_dtaut is null and c60_anousu = '.db_getsession("DB_anousu");
$dbwhere.= ' and k17_situacao = 1 ';

if(isset($k17_codigo) && $k17_codigo != '' && isset($k17_codigo2) && $k17_codigo2 != '' ){
  $dbwhere .=" and s.k17_codigo >= $k17_codigo and s.k17_codigo <= $k17_codigo2 ";
}else if(  (empty($k17_codigo) || ( isset($k17_codigo) && $k17_codigo == '')   )  && isset($k17_codigo2) && $k17_codigo2 != '' ){
  $dbwhere .=" and s.k17_codigo <= $k17_codigo2 ";
}else if(isset($k17_codigo) && $k17_codigo != ''){
  $dbwhere .= " and s.k17_codigo = $k17_codigo";
}

if(isset($z01_numcgm) && $z01_numcgm != ''){
  $dbwhere .= " and z01_numcgm = $z01_numcgm";
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
      valor = new Number(arr_dad[1]);
      tot = new Number(tot+valor);
    }

  } else{
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

          $sql_disabled = $clslip->sql_query_tipo(null,"s.k17_codigo,k17_valor","",$dbwhere." and k17_autent>0");
          $sql = $clslip->sql_query_tipo(null,"s.k17_codigo,k17_situacao,k17_valor,k17_credito,k17_debito,k17_data,c50_descr,c60_descr,z01_nome,k17_instit",'s.k17_codigo',$dbwhere);


          $cliframe_seleciona->textocabec ="black";
	  $cliframe_seleciona->textocorpo ="black";
	  $cliframe_seleciona->fundocabec ="#999999";
	  $cliframe_seleciona->fundocorpo ="#cccccc";
	  $cliframe_seleciona->iframe_height ="300";
	  $cliframe_seleciona->iframe_width ="100%";
	  $cliframe_seleciona->iframe_nome ="canc";
	  $cliframe_seleciona->fieldset =false;

	  $cliframe_seleciona->js_marcador = "parent.js_calcula()";
	  $cliframe_seleciona->dbscript = "onclick='parent.js_calcula();'";
	  $cliframe_seleciona->desabilitados = false;
	  $cliframe_seleciona->checked = true;
	  
          $campos  = "k17_codigo,k17_valor,k17_credito,k17_debito,k17_data,c50_descr,c60_descr,z01_nome,k17_instit,k17_situacao";
	  $cliframe_seleciona->campos  = $campos; 
	  $cliframe_seleciona->sql = $sql;
	  $cliframe_seleciona->sql_disabled = $sql_disabled;
	  $cliframe_seleciona->chaves ="k17_codigo,k17_valor";
	  $cliframe_seleciona->iframe_seleciona(1);    


         
          $sql = $clslip->sql_query_tipo(null,"sum(k17_valor) as tot,count(k17_valor) as registros",'',$dbwhere);
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
   parent.document.form1.tipo.value = 'slip';
   <?
    if ($oParametro[0]->e30_agendaautomatico == "f") {
     echo "  parent.document.form1.atualizar.disabled = false;\n";
    }
  ?>
   
</script>