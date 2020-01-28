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
include("classes/db_carne.php");

$retono = "select 0";

if(isset($HTTP_POST_VARS["verifica"])){
  $numpre = $HTTP_POST_VARS["numpre"];
  $parini = $HTTP_POST_VARS["parini"];
  $parfim = $HTTP_POST_VARS["parfim"];
  $clcarne = new cl_carne($numpre,$parini,$parfim);
  // verifica se numpre esta correto
  if($clcarne->verifica()==false) echo $clcarne->db_erro; 
  $retorno = $clcarne->sql;
}

if(isset($HTTP_POST_VARS["emite"])){
  $numpre = $HTTP_POST_VARS["numpre"];
  $parini = $HTTP_POST_VARS["parini"];
  $parfim = $HTTP_POST_VARS["parfim"];
  $clcarne = new cl_carne($numpre,$parini,$parfim);
  // verifica se numpre esta correto
  if($clcarne->verifica()==false) echo $clcarne->db_erro; 
  if($clcarne->calcula_valores()==false){
    echo $clcarne->db_erro; 
  }
  $retorno = $clcarne->sql;
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
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
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> <center>
<form name="form1" action="" method="post">
        <table width="36%" border="0" cellspacing="0">
          <tr> 
            <td colspan="2">Emissao de carnes</td>
          </tr>
          <tr> 
            <td width="53%">Numpre: </td>
            <td width="47%"><input name="numpre" type="text" id="numpre" value="11113308"></td>
          </tr>
          <tr> 
            <td>Parcela Inicial e Final: </td>
            <td><input name="parini" type="text" id="parini" value="1" size="4" maxlength="3"> 
              <input name="parfim" type="text" id="parfim" value="4" size="4" maxlength="3"></td>
          </tr>
          <tr align="center"> 
            <td colspan="2"><input name="verifica" type="submit" id="verifica" value="Verifica Carne">
                <input name="emite" type="submit" id="emite" value="Emite"></td>
          </tr>
        </table>
</form>
        <textarea name="textarea" cols="100" rows="10"><?=$retorno?></textarea>
 <?
   db_lov($retorno,30);
 ?>
      </center>
	  </td>
    <td align="left" valign="top" bgcolor="#CCCCCC">&nbsp;</td>
  </tr>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>