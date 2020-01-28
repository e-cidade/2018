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
include("dbforms/db_funcoes.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_issnotaavulsa_classe.php");
include("classes/db_parissqn_classe.php");
include("classes/db_issnotaavulsaservico_classe.php");


$clissnotaavulsa = new cl_Issnotaavulsa();
$db_opcao  = 1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_show(){

  if (document.getElementById('q51_dtemissini')){

     dataini = document.getElementById('q51_dtemissini').value;  
  }else{
     dataini = document.getElementById('q51_dtemissini_dia').value+"/";
     dataini = dataini + document.getElementById('q51_dtemissini_mes').value+"/"; 
     dataini = dataini +document.getElementById('q51_dtemissini_ano').value
  }
  if (document.getElementById('q51_dtemissfim')){
      datafim = document.getElementById('q51_dtemissfim').value;  
  }else{
     datafim = document.getElementById('q51_dtemissfim_dia').value+"/";
     datafim = datafim + document.getElementById('q51_dtemissfim_mes').value+"/"; 
     datafim = datafim + document.getElementById('q51_dtemissfim_ano').value
  }
  numcgm  = document.getElementById('z01_numcgm').value;  
  inscr   = document.getElementById('q51_inscr').value;  
  nota    = document.getElementById('q51_numnota').value;  
  url     = 'dtemissini='+dataini+'&dtemissfim='+datafim+'&numcgm='+numcgm+'&inscr='+inscr+'&nota='+nota;
  js_OpenJanelaIframe('top.corpo','db_iframe_pesquisa','iss3_issnotaavulsa002.php?'+url,"Pesquisa Notas Avulsa",true);
}

</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<table width="790" height="18"  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<?
include("forms/db_frmissnotaavulsaconsulta.php");
?>
</center>
<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>