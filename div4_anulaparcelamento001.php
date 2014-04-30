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
include("classes/db_termo_classe.php");
include("classes/db_arrecant_classe.php");
include("classes/db_arrecad_classe.php");
include("classes/db_arreold_classe.php");
include("classes/db_termodiv_classe.php");
include("classes/db_divida_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);

$cltermo = new cl_termo;
$clarrecant = new cl_arrecant;
$clarrecad = new cl_arrecad;
$clarreold = new cl_arreold;
$cltermodiv = new cl_termodiv;
$cldivida = new cl_divida;

$cltermo->rotulo->label();
$clarrecant->rotulo->label();
$clarrecad->rotulo->label();
$clarreold->rotulo->label();
$cltermodiv->rotulo->label();
$cldivida->rotulo->label();

$clrotulo = new rotulocampo;

$erro = false;

if(isset($anularparcelamento)){
 
  pg_exec("begin");
  $sql = "select fc_excluiparcelamento($v07_parcel,".db_getsession("DB_id_usuario").") as retorno";
  $result = pg_exec($sql);
  if (substr(pg_result($result,0),0,1) == "1") {
    pg_exec("commit");
    db_msgbox("Parcelamento anulado!");
  } else {
    pg_exec("rollback");
    db_msgbox("Erro durante a exclusao do parcelamento! " . pg_result($result,0));
  }

/*
  $result = $cltermo->sql_record($cltermo->sql_query("","v07_numpre",""," v07_parcel = $v07_parcel"));
  if($cltermo->numrows == 0){
    db_msgbox("Parcelamento nao encontrado!");
  } else {
    db_fieldsmemory($result,0);

    $result = $clarrecant->sql_record($clarrecant->sql_query("","*",""," k00_numpre = $v07_numpre"));
    if($clarrecant->numrows > 0){
      db_msgbox("Parcelamento com " . $clarrecant->numrows . " parcelas ja pagas! Nao pode ser anulado!");
    } else {

      db_inicio_transacao();
      $sqlerro=false;

      $result = $cltermodiv->sql_record($cltermodiv->sql_query("","","coddiv,numpreant",""," parcel = $v07_parcel"));
      if($cltermodiv->numrows > 0){
	for($contador=0;$contador < $cltermodiv->numrows;$contador++) {
	  db_fieldsmemory($result,$contador);
	  $cldivida->v01_numpre=$numpreant;
	  $cldivida->v01_coddiv=$coddiv;
	  $cldivida->alterar($coddiv);
	}
      }

      $clarrecad->k00_numpre=$v07_numpre;
      $clarrecad->excluir($v07_numpre);
  //    $clarrecad->erro(true,false);

      $result = $clarreold->sql_record($clarreold->sql_query("","*",""," k00_numpre = $v07_numpre"));
      if($clarreold->numrows > 0){
	for($contador=0;$contador < $clarreold->numrows;$contador++) {
	   db_fieldsmemory($result,$contador,true,true);
	   $clarrecad->k00_numcgm = $k00_numcgm;
	   $clarrecad->k00_dtoper = $k00_dtoper;
	   $clarrecad->k00_receit = $k00_receit;
	   $clarrecad->k00_hist   = $k00_hist;
	   $clarrecad->k00_valor  = $k00_valor;
	   $clarrecad->k00_dtvenc = $k00_dtvenc;
	   $clarrecad->k00_numpre = $k00_numpre;
	   $clarrecad->k00_numpar = $k00_numpar;
	   $clarrecad->k00_numtot = $k00_numtot;
	   $clarrecad->k00_numdig = $k00_numdig;
	   $clarrecad->k00_tipo   = $k00_tipo;
	   $clarrecad->k00_tipojm = $k00_tipojm;
	   $clarrecad->incluir();
  //         $clarrecad->erro(true,false);
	}
	$clarreold->excluir($k00_numpre);
  //      $clarreold->erro(true,false);
      }

      $cltermo->v07_parcel=$v07_parcel;
      $cltermo->excluir($v07_parcel,true,false);
      $erro=$cltermo->erro_msg;
      if($cltermo->erro_status==0){
	$sqlerro = true;
      }
      $cltermo->erro(true,false);
      db_fim_transacao($sqlerro);

    }

  }
*/
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
function  js_verificaparcelamento(){
   if(document.form1.v07_parcel.value == ""){
     alert('Informe um parcelamento!');
	 return false;
   }
   return true;
}

</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.v07_parcel.focus();" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
   <form name="form1" action="" method="post" onSubmit="return js_verificaparcelamento();">
	    <table width="292" border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td width="27" height="25" title="<?=$Tv07_parcel?>"> 
              <?
				db_ancora(@$Lv07_parcel,'js_mostratermo(true);',4)
				?>
            </td>
	    <td title="<?=$Tj14_nome?>" colspan="4">
	    <?
	     db_input('v07_parcel',10,$Iv07_parcel,true,'text',1);
	    ?>
	    </td>

          </tr>
          <tr> 
            <td height="25">&nbsp;</td>
            <td height="25">
			    <input name="anularparcelamento"  type="submit" id="anularparcelamento" value="Anular Parcelamento">
            </td>
          </tr>
        </table>
      </form>
     </td>
  </tr>
</table>
<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_mostratermo(mostra){
  if (mostra == true) {
    js_OpenJanelaIframe('','db_iframe_termo','func_termo.php?funcao_js=parent.js_preenchepesquisa|v07_parcel|z01_nome','Pesquisa',true);
  }
}
 function js_preenchepesquisa(chave){
   document.form1.v07_parcel.value = chave;
   db_iframe_termo.hide();
 }
</script>