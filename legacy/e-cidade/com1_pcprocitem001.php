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
include("libs/db_liborcamento.php");
include("dbforms/db_funcoes.php");
include("classes/db_pcparam_classe.php");
include("classes/db_solicitem_classe.php");
include("classes/db_pcproc_classe.php");
include("classes/db_pcprocitem_classe.php");
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);
$clpcparam = new cl_pcparam;
$clsolicitem = new cl_solicitem;
$clpcproc = new cl_pcproc;
$clpcprocitem = new cl_pcprocitem;
$clrotulo = new rotulocampo;
$clrotulo->label("pc81_codprocitem");
$clrotulo->label("pc11_numero");
$clrotulo->label("pc11_seq");
$clrotulo->label("pc11_codigo");
$clrotulo->label("pc01_codmater");
$clrotulo->label("pc01_descrmater");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.bordas{
         border: 1px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #cccccc;
}
.bordas01{
         border: 1px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #DEB887;
}
.bordas02{
         border: 2px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #999999;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <?
    if(isset($pc81_codproc) && trim($pc81_codproc)!=""){
      $result_itens = $clpcprocitem->sql_record($clpcprocitem->sql_query_pcmater(null,"distinct pc81_codprocitem,pc11_numero,pc11_seq,pc11_codigo,pc01_codmater,pc01_descrmater","pc81_codprocitem","pc81_codproc=$pc81_codproc and (e54_autori is null or (e54_autori is not null and e54_anulad is not null))"));
      $numrows_itens = $clpcprocitem->numrows;
      if($numrows_itens!=0){
	echo "<center>";
	echo "<table border='1' align='center'>\n";
	echo "<tr bgcolor=''>\n";
	echo "  <td nowrap class='bordas02' align='center'><strong>Item no PC</strong></td>\n";
	echo "  <td nowrap class='bordas02' align='center'><strong>Solicitação</strong></td>\n";
	echo "  <td nowrap class='bordas02' align='center'><strong>Sequencial</strong></td>\n";
	echo "  <td nowrap class='bordas02' align='center'><strong>Codigo do item</strong></td>\n";
	echo "  <td nowrap class='bordas02' align='center'><strong>Cód. Material</strong></td>\n";
	echo "  <td nowrap class='bordas02' align='center'><strong>Descr. Material</strong></td>\n";
	echo "</tr>\n";
	for($i=0;$i<$numrows_itens;$i++){
	  db_fieldsmemory($result_itens,$i);
	  echo "<tr>\n";
	  echo "  <td nowrap class='bordas01' align='center' >$pc81_codprocitem</td>\n";
	  echo "  <td nowrap class='bordas01' align='center' >$pc11_numero</td>\n";
	  echo "  <td nowrap class='bordas01' align='center' >$pc11_seq</td>\n";
	  echo "  <td nowrap class='bordas01' align='center' >$pc11_codigo</td>\n";
	  echo "  <td nowrap class='bordas01' align='center' >$pc01_codmater</td>\n";
	  echo "  <td        class='bordas01' align='left' >".ucfirst(strtolower($pc01_descrmater))."</td>\n";
	  echo "</tr>\n";
	}
	echo "</table>\n";
	echo "</center>";
	echo "<script>
	       parent.document.form1.excluir.disabled=false;
	      </script>";
      }else{
	echo "
	      <strong>Não existem itens neste processo de compras.</strong>\n
	      <script>
		parent.document.form1.excluir.disabled=true;
	      </script>
	     ";
      }
    }else{
    echo "                                                                                                                                                                                                                                                                                   <br><br><br><br><br><br><br>
	  <strong>Código do processo de compras não informado.</strong>\n
	  <script>
	    parent.document.form1.excluir.disabled=true;
	  </script>
	 ";
    }
    ?>
    </center>
    </td>
  </tr>
</table>
</form>
</body>
</html>