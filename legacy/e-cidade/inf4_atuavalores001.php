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
include("classes/db_infcab_classe.php");
include("classes/db_infcor_classe.php");
include("classes/db_tabrec_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clinfcab = new cl_infcab;
$clinfcor = new cl_infcor;
$db_opcao = 2;
if ( isset($i03_codigo) ) {
   $result = $clinfcab->sql_record($clinfcab->sql_query($i03_codigo));
   db_fieldsmemory($result,0);
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
var conta_linha = 0;

  function js_gravavalor(correcao,juro,multa,total){
  var tab = valores.document.getElementById('tab');
  var NovaLinha = tab.insertRow(tab.rows.length);
  var dados = new Array(document.form1.i04_obs.value,document.form1.i04_dtoper_dia.value+'-'+document.form1.i04_dtoper_mes.value+'-'+document.form1.i04_dtoper_ano.value,document.form1.i04_dtvenc_dia.value+'-'+document.form1.i04_dtvenc_mes.value+'-'+document.form1.i04_dtvenc_ano.value,document.form1.i04_valor.value,document.form1.i04_receit.value,correcao,juro,multa,total);
  NovaLinha.id = 'id_'+conta_linha;
  for(i=0;i<9;i++){
      NovaColuna = NovaLinha.insertCell(i);
      NovaColuna.style.fontSize = '10px';
      NovaColuna.align = 'left';
      NovaColuna.innerHTML = dados[i];
  }

  NovaColuna = NovaLinha.insertCell(9);
  NovaColuna.align = 'center';
  NovaColuna.innerHTML = '<input class="cancelapagto" value="<E>" type="button" onclick="js_removelinha(\'id_'+conta_linha+'\')">' ;

  NovaColuna = NovaLinha.insertCell(10);
  NovaColuna.align = 'center';
  NovaColuna.innerHTML = '<input class="cancelapagto" value="<A>" type="button" onclick="js_alteralinha(\'id_'+conta_linha+'\')">' ;
  conta_linha++;

//document.form1.i04_dtoper_dia.value = '';
//document.form1.i04_dtoper_mes.value = '';
//document.form1.i04_dtoper_ano.value = '';

//  document.form1.i04_dtvenc_dia.value = '';
//  document.form1.i04_dtvenc_mes.value = '';
//  document.form1.i04_dtvenc_ano.value = '';

//  document.form1.i04_valor.value = '';
  document.form1.i04_obs.value = '';
  document.form1.i04_dtvenc_dia.focus();
}
</script>

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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <?
    include("forms/db_frminfcab.php");	
	?>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>