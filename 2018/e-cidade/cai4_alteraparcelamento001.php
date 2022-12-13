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
include("classes/db_arrecad_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);

$cltermo = new cl_termo;
$clarrecad = new cl_arrecad;

$cltermo->rotulo->label();
$clarrecad->rotulo->label();

$clrotulo = new rotulocampo;

$erro = false;

if(isset($anularparcelamento)){

  $result = $cltermo->sql_record($cltermo->sql_query($v07_parcel));
  if($cltermo->numrows>0){

    $numpre = pg_result($result,0,'v07_numpre');
    
    db_inicio_transacao();
     
    $result = $clarrecad->sql_record("update arrecad set k00_receit = k00_receit + 3000 where k00_numpre = $numpre");
    
    $result = $clarrecad->sql_record("update arrecant set k00_receit = k00_receit + 3000 where k00_numpre = $numpre");
    
    $result = $clarrecad->sql_record("update arrepaga set k00_receit = k00_receit + 3000 where k00_numpre = $numpre");

    db_fim_transacao();
  	db_msgbox('Processo concluído!');

  }else{
  	db_msgbox('Parcelamento não encontrato ou já quitado. verifique!');
  }

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
			    <input name="anularparcelamento"  type="submit" id="anularparcelamento" value="Processa Parcelamento">
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