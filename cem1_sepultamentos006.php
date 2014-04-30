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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_sepultamentos_classe.php");
require_once("classes/db_renovacoes_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clsepultamentos = new cl_sepultamentos;
$clrenovacoes 	 = new cl_renovacoes;

if(isset($chavepesquisa)){
   $result  = $clsepultamentos->sql_record($clsepultamentos->sql_query($chavepesquisa,"cm01_c_livro, cm01_i_folha, cm01_i_registro, cm01_i_medico, cm01_i_causa, cm04_c_descr, cm01_c_local, cm01_c_cartorio, cm01_i_hospital, cgm1.z01_nome as nome_hospital ,cm01_i_funeraria, cgm2.z01_nome as nome_funeraria, cm01_i_declarante, cgm3.z01_nome as nome_declarante, cgm4.z01_nome as cm32_nome"));
   db_fieldsmemory($result,0);
   $db_opcao = 2;
   $db_botao = true;
}
 //resgata os valores
 $clsepultamentos->cm01_i_codigo      = $cm01_i_codigo;
 $clsepultamentos->cm01_i_medico      = $cm01_i_medico;
 $clsepultamentos->cm01_i_cemiterio   = $cm01_i_cemiterio;
 $clsepultamentos->cm01_c_conjuge     = $cm01_c_conjuge;
 $clsepultamentos->cm01_c_cor         = $cm01_c_cor;
 $clsepultamentos->cm01_d_falecimento = $cm01_d_falecimento;
 $clsepultamentos->cm01_observacoes   = $cm01_observacoes;
if(isset($alterar)){
  db_inicio_transacao();
  //altera o sepultamento
  @$clsepultamentos->alterar($cm01_i_codigo);
  db_fim_transacao();
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
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
     <?
     include("forms/db_frmsepultamentos1.php");
     ?>
    </center>
     </td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar)){
  if($clsepultamentos->erro_status=="0"){
    db_msgbox($clsepultamentos->erro_msg);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clsepultamentos->erro_campo!=""){
      echo "<script> document.form1.".$clsepultamentos->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clsepultamentos->erro_campo.".focus();</script>";
    }
  }else{
  db_msgbox($clsepultamentos->erro_msg);
  echo "<script>";
  //echo " parent.document.formaba.a2.disabled=true; ";
  echo " parent.document.formaba.a3.disabled=false;";
  echo " top.corpo.iframe_a3.location.href='cem1_sepultamentos003.php?db_opcao=2&sepultamento={$cm01_i_codigo}';";
  echo " parent.mo_camada('a3'); ";
  echo "</script>";
  }
};
?>