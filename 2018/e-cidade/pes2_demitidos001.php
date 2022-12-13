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
include("classes/db_cfautent_classe.php");
include("classes/db_saltes_classe.php");
$rotulocampo = new rotulocampo;
$rotulocampo->label("k11_id");
$rotulocampo->label("k13_conta");
$k00_dtoper = date('Y-m-d',db_getsession("DB_datausu"));
$k00_dtoper_diai = '01';
$k00_dtoper_dia = date('d',db_getsession("DB_datausu"));
$k00_dtoper_mes = date('m',db_getsession("DB_datausu"));
$k00_dtoper_ano = date('Y',db_getsession("DB_datausu"));
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_relatorio(){
  var F = document.form1;
  var datai = F.datai_ano.value+'-'+F.datai_mes.value+'-'+F.datai_dia.value;
  var dataf = F.dataf_ano.value+'-'+F.dataf_mes.value+'-'+F.dataf_dia.value;
  jan = window.open('pes2_demitidos002.php?datai='+datai+'&ordem='+document.form1.ordem.value+'&dataf='+dataf,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="if(document.form1) document.form1.elements[0].focus()" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<form name="form1" method="post" action="">
<table>
  <tr>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
  <tr> 
    <td align="right" nowrap><strong>Demitidos entre:</strong></td>
    <td align="left"  nowrap>
      <?
      db_inputdata('datai',@$k00_dtoper_dia,@$k00_dtoper_mes,@$k00_dtoper_ano,true,'text',1,"")
      ?>
      &nbsp;<strong>e</strong>&nbsp;
      <?
      db_inputdata('dataf',@$k00_dtoper_dia,@$k00_dtoper_mes,@$k00_dtoper_ano,true,'text',1,"")
      ?>
    </td>
  </tr>
  <tr>
    <td align="right" nowrap title="Ordem para a emissão do relatório" ><strong>Ordem : </strong>
    </td>
    <td align="left">
    <?
     $xx = array("a"=>"Alfabética","n"=>"Numérica","d"=>"Admissão");
     db_select('ordem',$xx,true,4,"");
    ?>
    </td>
  </tr>
  <tr> 
    <td colspan = "2" align="center" > 
      <input name="imprimir" type="button" id="imprimir" onClick="js_relatorio()" value="Imprimir">
    </td>
  </tr>
</table>
</form>
</center>
<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>