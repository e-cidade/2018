<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("classes/db_tabrec_classe.php");

$cltabrec = new cl_tabrec;

$instit = db_getsession("DB_instit");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

if(isset($codtaxa) && (!empty($codtaxa)) ) {
	
	
	
  
  $result = $cltabrec->sql_record($cltabrec->sql_query_inst_taxa(0,"*","k07_valorv desc", " codsubrec = $codtaxa"));
   
  if ( gettype($result) != "boolean" ) {

    if (pg_numrows($result) > 0) {
      db_fieldsmemory($result,0);
      $result_vlrcor = pg_exec("select fc_infla(k07_codinf, k07_valorf, k07_data, '" . date("Y-m-d",db_getsession("DB_datausu")) ."') from tabdesc where k07_instit = $instit and codsubrec = $codtaxa");
      if (pg_numrows($result_vlrcor)>0){
        db_fieldsmemory($result_vlrcor,0);

        if ($fc_infla!=-1){
          $k07_valorf=$fc_infla;	    
        }
      }
    }

    if(empty($k07_valorv)){

      echo "<script>
        parent.document.form1.k02_codigo.value = '$k02_codigo';
      parent.document.form1.k02_drecei.value = '$k02_drecei';
      parent.document.form1.valor.value      = '$k07_valorf';		
      parent.document.form1.codsubrec.value  = '$codsubrec';		
      parent.document.form1.k07_descr.value  = '$k07_descr';		
      parent.document.form1.o15_codigo.value = '$recurso';
      parent.document.form1.arretipo.value = '$arretipo';
      parent.document.form1.descrarretipo.value = '$k00_descr';
      parent.document.form1.o15_codigo.onchange();		
      parent.document.form1.gravar.focus();		
      parent.func_iframe_taxas.hide(); 
      parent.js_buscaConCarPeculiar();
      </script>
        ";
      exit;
    }

    $result_vlrcor = pg_exec("select fc_infla(k07_codinf, k07_valorv, k07_data, '" . date("Y-m-d",db_getsession("DB_datausu")) ."') from tabdesc where k07_instit = $instit and codsubrec = $codtaxa");
    if (pg_numrows($result_vlrcor)>0){
      db_fieldsmemory($result_vlrcor,0);

      if ($fc_infla!=-1){
        $k07_valorv=$fc_infla;	    
      }
    }
  }else{
    echo "<script>alert('Receita não configurada corretamente ou sem ligação com o orçamento do ano atual! Contate suporte!');parent.func_iframe_taxas.hide();</script>"; 
    exit;
  }
}else{
  echo "<script>parent.func_iframe_taxas.hide();</script>";
  exit;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_calculavalor(){
  if (Number(document.form1.quant.value) <= Number(document.form1.qminimo.value)) {
    document.form1.valor.value = (Number(document.form1.vminimo.value));
    var vlrtotal = Number(document.form1.valor.value);
  } else {
    document.form1.valor.value = (Number(document.form1.quant.value)-Number(document.form1.qminimo.value)) * Number(document.form1.vvariavel.value);
    var vlrtotal = Number(document.form1.valor.value) + Number(document.form1.vminimo.value);
  }
  document.form1.valortotal.value = vlrtotal.toFixed(2);
}
function js_verificavalor(vvalor){
  // TESTA SE EXISTE O IFRAME db_recibo (CASO A TELA DE RECIBO SEJA CHAMADA DO PROGRAMA DE EMISSAO DA CGF)
  if(top.corpo.db_recibo){ 
    top.corpo.db_recibo.jan.document.form1.k02_codigo.value = '<?=$k02_codigo?>';
    top.corpo.db_recibo.jan.document.form1.k02_drecei.value='<?=$k02_drecei?>';
    top.corpo.db_recibo.jan.document.form1.codsubrec.value='<?=$codsubrec?>';
    top.corpo.db_recibo.jan.document.form1.k07_descr.value='<?=$k07_descr?>';
    top.corpo.db_recibo.jan.document.form1.o15_codigo.value='<?=$recurso?>';		
    top.corpo.db_recibo.jan.document.form1.o15_codigo.onchange();		
    top.corpo.db_recibo.jan.document.form1.valor.value = vvalor;		
    top.corpo.db_recibo.jan.document.form1.gravar.focus();		
  }else{
    parent.document.form1.k02_codigo.value = '<?=$k02_codigo?>';
    parent.document.form1.k02_drecei.value='<?=$k02_drecei?>';
    parent.document.form1.codsubrec.value='<?=$codsubrec?>';
    parent.document.form1.k07_descr.value='<?=$k07_descr?>';
    parent.document.form1.o15_codigo.value='<?=$recurso?>' ;
    parent.document.form1.arretipo.value='<?=$arretipo?>'		;
    parent.document.form1.descrarretipo.value = '<?=$k00_descr?>';
    parent.document.form1.o15_codigo.onchange();		
    parent.document.form1.valor.value = vvalor;		
    parent.document.form1.gravar.focus();		
  }	
  parent.func_iframe_taxas.hide(); 
  parent.js_buscaConCarPeculiar();
}
</script>
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin=" 0" topmargin=" 0" marginwidth=" 0" marginheight=" 0" >
<form name="form1" action="post">
<table width="100%">
<tr> 
<td width="48%" height="26" align="right">C&oacute;digo:</td>
<td width="52%"><input name="codrec" readonly type="text" id="codrec" value="<?=$k07_codigo?>" size="5"></td>
</tr>
<tr> 
<td align="right">Descri&ccedil;&atilde;o:</td>
<td><input name="descrrec" readonly type="text" id="descrrec" value="<?=$k07_descr?>" size="40"></td>
</tr>
<tr> 
<td align="right">Valor Fixo:</td>
<td><input name="vminimo" readonly type="text" id="vminimo" value="<?=$k07_valorf?>" size="20"></td>
</tr>
<tr> 
<td align="right">Valor m2 acima do M&iacute;nimo:</td>
<td><input name="vvariavel" readonly type="text" id="vvariavel" value="<?=$k07_valorv?>" size="20"></td>
</tr>
<tr> 
<td align="right">Quantidade M&iacute;nima:</td>
<td><input name="qminimo" readonly type="text" id="qminimo" value="<?=$k07_quamin?>" size="20"></td>
</tr>
<tr> 
<td height="26" align="right">Quantidade Total:</td>
<td><input name="quant" type="text" onChange="js_calculavalor()" id="quant" size="20"></td>
</tr>
<tr> 
<td align="right">Valor:</td>
<td><input name="valor" readonly type="text" id="valor" size="20"></td>
</tr>
<tr>
<td align="right">Valor Total a Pagar</td>
<td><input name="valortotal" readonly type="text" id="valortotal" size="20"></td>
</tr>
<tr> 
<td colspan="2" align="center"><input name="confirma" type="button" id="confirma" value="Confirma" onclick="js_verificavalor(document.form1.valortotal.value)"></td>
</tr>
</table>
</form>
</body>
</html>
  <script>
document.form1.quant.focus()
  </script>