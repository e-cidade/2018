<?php
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


require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_pagordem_classe.php");
require_once("classes/db_pagordemconta_classe.php");
require_once("classes/db_empagetipo_classe.php");
require_once("classes/db_empord_classe.php");
require_once("classes/db_empagemov_classe.php");
require_once("classes/db_empageslip_classe.php");
require_once("classes/db_empagemovconta_classe.php");
require_once("classes/db_empagepag_classe.php");
require_once("classes/db_pcfornecon_classe.php");
require_once("classes/db_empageforma_classe.php");
require_once("classes/db_empagemovforma_classe.php");
require_once("std/DBString.php");
$lMovimentosBloqueados = false;
$clempagetipo     = new cl_empagetipo;
$clpagordem       = new cl_pagordem;
$clpagordemconta  = new cl_pagordemconta;
$clempord         = new cl_empord;
$clempagemov      = new cl_empagemov;
$clempageslip     = new cl_empageslip;
$clempagemovconta = new cl_empagemovconta;
$clempagepag      = new cl_empagepag;
$clpcfornecon     = new cl_pcfornecon;
$clempageforma    = new cl_empageforma;
$clempagemovforma = new cl_empagemovforma;

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
$clrotulo->label("e80_codage");
$clrotulo->label("e83_codtipo");
$clrotulo->label("e53_valor");
$clrotulo->label("e53_vlranu");
$clrotulo->label("e53_vlrpag");
$clrotulo->label("e96_codigo");


$dbwhere  = " e80_instit = " .db_getsession("DB_instit") . " and e97_codforma = 3";
$dbwhere .= " and (e90_codmov is null or e90_cancelado is true) ";
$dbwhere .= " and (case ";
$dbwhere .= "       when e90_codgera is not null";
$dbwhere .= "         then e90_codgera = (select max(e90_codgera)";
$dbwhere .= "                               from empageconfgera confgera";
$dbwhere .= "                              where confgera.e90_codmov = empageconfgera.e90_codmov)";
$dbwhere .= "       else ";
$dbwhere .= "         true ";
$dbwhere .= "      end )";

if(isset($db_banco) && trim($db_banco)!=""){

  $dbwhere .= " and conplanoconta.c63_banco='$db_banco' ";

  if (!empty($comboboxCNPJ) && $comboboxCNPJ != 0) {
//     $dbwhere .= " and contabancaria.db83_identificador = '{$comboboxCNPJ}' ";
  }
}

$sWhereOrdem   = " ((round(e53_valor,2)-round(e53_vlranu,2)-round(e53_vlrpag,2)) > 0 ";
$sWhereOrdem  .= " and (round(e60_vlremp,2)-round(e60_vlranu,2)-round(e60_vlrpag,2)) > 0) ";

$sWhereOrdem  .= " and not exists( select 1 from empageconfgera confvalidar where confvalidar.e90_codmov  = e81_codmov and e90_cancelado is false)";

$oInstit   = db_stdClass::getDadosInstit();
$sql    = $clempagemov->sql_query_txt(null,"distinct pc63_conta,
                                                     pc63_agencia,
                                                     pc63_banco as banco,
                                                     e80_codage,
                                                     e50_codord,
                                                     e50_data,
                                                     e82_codord,
                                                     o15_codigo,
                                                     o15_descr,
                                                     e81_codmov,
                                                     e83_codtipo,
                                                     e83_descr,
                                                     e60_emiss,
                                                     e60_numemp,
                                                     e60_anousu,
                                                     e60_codemp,
                                                     z01_numcgm,
                                                     z01_nome,
                                                     e81_valor,
                                                     db83_identificador,
                                                     fc_validaretencoesmesanterior(e81_codmov,null) as validaretencao,
                                                     fc_valorretencaomov(e81_codmov,false) as vlrretencao,
                                                     1 as tipo",
                                                     "",
                                                     "$dbwhere
                                                     and {$sWhereOrdem}");
$sqlSlips  = $clempageslip->sql_query_txtbanco(null,"
                                                     (case when pc63_conta is null then descrconta.c63_conta||'/'||descrconta.c63_dvconta
                                                           else pc63_conta end ) as pc63_conta,
                                                     (case when pc63_agencia is null then descrconta.c63_agencia||'/'||descrconta.c63_dvagencia
                                                           else pc63_agencia end ) as pc63_agencia,
                                                     (case when pc63_banco is null then descrconta.c63_banco
                                                           else pc63_banco end ) as banco,
                                                     e80_codage,
                                                     s.k17_codigo,
                                                     k17_data,
                                                     e89_codigo,
                                                     pag.c61_codigo as o15_codigo,
                                                     orctiporec.o15_descr,
                                                     e81_codmov,
                                                     e83_codtipo,
                                                     e83_descr,
                                                     k17_data,
                                                     0 as e60_numemp,
                                                     '0' as e60_codemp,
                                                     '0'   as e60_anousu,
                                                    (case when z01_numcgm is  not null then z01_numcgm
                                                      else {$oInstit->z01_numcgm} end)  as z01_numcgm,
                                                    (case when z01_nome is  not null then z01_nome
                                                       else '{$oInstit->z01_nome}' end) as z01_nome,
                                                     e81_valor,
                                                     db83_identificador,
                                                     false as validaretencao,
                                                     0 as vlrretencao,
                                                     2 as tipo","","$dbwhere");
$sSqlTxt =  $sql ." union ".$sqlSlips;
$result09 = $clpagordem->sql_record($sSqlTxt);
$numrows09= $clpagordem->numrows;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
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

.linhagrid {
  height: 25px;
}
</style>
<script>
function js_marca(obj){
   var OBJ = document.form1;
   for(i=0;i<OBJ.length;i++){
     if(OBJ.elements[i].type == 'checkbox' && OBJ.elements[i].disabled==false){
       OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);
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
<form name="form1" method="post" action="">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top" bgcolor="#CCCCCC">
     <fieldset>
       <legend><b>Movimentos</b></legend>
    <center>
      <?
      if($numrows09){
      ?>
      <table style='border:2px inset white' bgcolor="white" cellspacing='0'>
        <thead>
        <tr>
          <td class='table_header' align='center'><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>
          <td class='table_header' align='center'><b><?=$RLe60_codemp?></b></td>
          <td class='table_header' align='center'><b>Ordem/Slip</b></td>
          <td class='table_header' align='center'><b>Conta pagadora</b></td>
          <td class='table_header' align='center'><b>Recurso</b></td>
          <td class='table_header' align='center'><b><?=$RLz01_nome?></b></td>
          <td class='table_header' align='center'><b>Cód. Pgto.</b></td>
          <td class='table_header' align='center' nowrap><b><?/*=$RLe60_emiss*/?>Banco - Agência - Conta (credor)</b></td>
          <td class='table_header' align='center' nowrap><b>Valor Retido</b></td>
          <td class='table_header' align='center' nowrap><b>Valor a pagar</b></td>
          <td class='table_header' align='center'><b><?=$RLe80_codage?></b></td>
          <td class='table_header' width="17">&nbsp;</td>
     	</tr>
        </thead>
        <tbody style='height:200px;overflow:scroll;overflow-x:hidden'>
        <?
	  $valortotal = 0;
	  $arr_valtipo = Array();
	  $lMovimentosBloqueados = 'false';

	  for($i=0; $i<$numrows09; $i++){

	    db_fieldsmemory($result09,$i);
	    $lBloqueia = false;
	    $comboboxCNPJ == "0" ? $comboboxCNPJ = "" : $comboboxCNPJ;
	    if ($comboboxCNPJ != $db83_identificador) {
        continue;
      }

	    $lCNPJValido = DBString::isCNPJ($db83_identificador);
 	    if (!$lCNPJValido || $db83_identificador == "") {

        $lMovimentosBloqueados = 'true';
        $lBloqueia = true;
      }

	    $digito  = "";
	    $digitoc = "";
	    $sSqlMovimentacaoConta = $clempagemovconta->sql_query_conta($e81_codmov,
                                                                  "pc63_banco as banco,
                                                                   pc63_agencia as agencia,
                                                                   pc63_agencia_dig as digito,
                                                                   pc63_conta as conta,
                                                                   pc63_conta_dig as digitoc ");
	    $result_movconta = $clempagemovconta->sql_record($sSqlMovimentacaoConta);
	    $numrows_movconta = $clempagemovconta->numrows;
	    if($numrows_movconta > 0 && $tipo == 1){

	      db_fieldsmemory($result_movconta,0);
          if(trim($digito)!=""){
		      $digito = "/$digito";
          }
          if(trim($digitoc)!=""){
	        $digitoc = "/$digitoc";
	      }
	    } else {
	      $agencia = $pc63_agencia;
	      $conta   = $pc63_conta;
	    }
	    $sDisabled = "";
	    $sChecked  = " checked ";

	    if (($agencia == "" or $conta == "")  || $lBloqueia) {

	      $sDisabled = " disabled ";
	      $sChecked  = "  ";

	    }
	    $sRetencaoMesAnterior = $validaretencao=="f"?"false":"true";
	?>
        <tr>
          <td class='linhagrid' align='right'>
          <span style='display:none' id="validarretencao<?=$e81_codmov?>"><?=$sRetencaoMesAnterior?></span>
          <input class = 'codmovimento'
                 value = "<?=$e81_codmov?>" <?=$sDisabled?>  <?=$sChecked?> name="<?=$e81_codmov?>" type='checkbox' >
          </td>
          <td class='linhagrid' style='text-align:right' title='<?=($RLe60_codemp)?> - Data de emissão:<?=db_formatar($e60_emiss,"d")?>'>
          <?
          if ($tipo == 1) {
            echo "<a onclick='js_JanelaAutomatica(\"empempenho\",{$e60_numemp});return false;' href='#'>";
            echo " {$e60_codemp}/{$e60_anousu}</a>";
          } else {
             echo "{$e60_codemp}";
          }
          ?></td>
          <td class='linhagrid' style='text-align:right' id='codord<?=$e81_codmov?>'
              title='<?=($RLe50_codord)?> - Data de emissão:<?=db_formatar($e50_data,"d")?>'><?=$e50_codord?></td>
          <td class='linhagrid' title='Conta pagadora'  style='text-align:left' align='left'><?=($e83_descr)?></td>
          <td class='linhagrid' style='text-align:left' title='Recurso' align='right'><?=($o15_codigo." - ".$o15_descr)?></td>
          <td class='linhagrid' style='text-align:left' title='<?=($RLz01_nome)?> -  Numcgm:<?=$z01_numcgm?>'><?=$z01_nome?></td>
	  <?
	  /**
	   * Configura se é TED ou DOC
	   */
	  if ( trim($db_banco) == trim($banco) ) {
	    $codigopagamento = "DEP";
	  }else if ( $e81_valor < 1000 /*3000*/ ){
	    $codigopagamento = "DOC";
	  } else {
	    $codigopagamento = "TED";
	  }
	  ?>
          <td class='linhagrid' title='Código de pagamento'  style='text-align:left' nowrap><b><?=($codigopagamento)?></b></td>
          <td class='linhagrid' title='Banco - Agência - Conta (credor)' style='text-align:left' nowrap><?=($banco)?> - <?=($agencia.$digito)?> - <?=($conta.$digitoc)?></td>
          <td class='linhagrid' title='Valo retido ' style='text-align:right' nowrap><?=db_formatar($vlrretencao,"f")?></td>
          <td class='linhagrid' title='Valor a pagar' style='text-align:right'><?=db_formatar($e81_valor - $vlrretencao,"f")?> </td>
          <td class='linhagrid' title='<?=($RLe80_codage)?>' align='center'><?=($e80_codage)?></td>
	</tr>
        <?
	  }
	  ?>
        <tr style='height:auto'><td>&nbsp;</td></tr>
        </tbody>
      </table>
      <?
      }else{
      ?>
      <BR><BR><BR><BR><BR><BR>
      <table>
      	<tr>
      	  <td nowrap align='center' width='100%' height='100%'>
            <BR><BR><BR><BR><BR><BR>
      	    <b>Nenhum registro encontrado</b>
      	  </td>
      	</tr>
      </table>
      <?
      }
      ?>
       </center>
       </fieldset>
    </td>
  </tr>
   </form>
</table>
</body>
</html>
<script>


  var lMovimentosBloqueados = '<?php echo $lMovimentosBloqueados;?>';

  if (lMovimentosBloqueados == 'true') {

    var sMensagem = "Existem movimentos cujas contas pagadoras tem CNPJ inválido ou inexistente\n";
    sMensagem    += "no cadastro de contas bancárias. Estes movimentos serão exibidos mas\n";
    sMensagem    += "desabilitados para geração do arquivo. Para corrigir, acesse o menu\n";
    sMensagem    += "CAIXA > CADASTROS > CONTAS > CONTAS BANCÁRIAS > ALTERAÇÃO e informe o CNPJ correto.\n";
    alert(sMensagem);
  }

</script>