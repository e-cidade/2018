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
include("libs/db_usuariosonline.php");
include("classes/db_histocorrencia_classe.php");
include("classes/db_histocorrenciamatric_classe.php");
include("dbforms/db_funcoes.php");
require("libs/db_utils.php");

db_postmemory($_GET);
db_postmemory($_POST);

$clhistocorrencia         = new cl_histocorrencia;
$clhistocorrenciamatric   = new cl_histocorrenciamatric;
$db_opcao   = 1;
$db_botao   = true;

$j01_matric = $ar25_matric;

$ar23_data_dia = date("d");
$ar23_data_mes = date("m");
$ar23_data_ano = date("Y");

$ar23_hora = date("H:i");



if(isset($incluir)) {
  $clhistocorrencia->ar23_id_usuario   = db_getsession("DB_id_usuario");
  $clhistocorrencia->ar23_instit       = db_getsession("DB_instit");
  $clhistocorrencia->ar23_modulo       = db_getsession("DB_modulo");
  $clhistocorrencia->ar23_id_itensmenu = db_getsession("DB_itemmenu_acessado");
  
  db_inicio_transacao();
  $clhistocorrencia->incluir($ar23_sequencial);
  $erro_msg = $clhistocorrencia->erro_msg;
  db_fim_transacao();
  
  $clhistocorrenciamatric = new cl_histocorrenciamatric;
  $clhistocorrenciamatric->ar25_matric         = $j01_matric;
  $clhistocorrenciamatric->ar25_histocorrencia = $clhistocorrencia->ar23_sequencial;
  
  db_inicio_transacao();
  $clhistocorrenciamatric->incluir($ar25_sequencial);
  db_fim_transacao();
  
  $db_opcao = 2;
  
}elseif(isset($alterar)) {
  
  $clhistocorrencia->ar23_id_usuario   = db_getsession("DB_id_usuario");
  $clhistocorrencia->ar23_instit       = db_getsession("DB_instit");
  $clhistocorrencia->ar23_modulo       = db_getsession("DB_modulo");
  $clhistocorrencia->ar23_id_itensmenu = db_getsession("DB_itemmenu_acessado");
  db_inicio_transacao();
  $clhistocorrencia->alterar($ar23_sequencial);
  $erro_msg = $clhistocorrencia->erro_msg;
  db_fim_transacao();
  
  $db_opcao = 2;
  
}elseif(isset($excluir)) {
  
  $clhistocorrenciamatric = new cl_histocorrenciamatric;
  db_inicio_transacao();
  $clhistocorrenciamatric->excluir("", "ar25_histocorrencia = $ar23_sequencial");
  db_fim_transacao();
  
  db_inicio_transacao();
  $clhistocorrencia->excluir($ar23_sequencial);
  $erro_msg = $clhistocorrencia->erro_msg;
  db_fim_transacao();
  
  $db_opcao = 1;
  
}elseif(isset($opcao)) {
  
  $campos  = "ar23_sequencial		, ";
  $campos .= "ar23_id_usuario		, ";
  $campos .= "ar23_instit				, ";
  $campos .= "ar23_modulo				, ";
  $campos .= "ar23_id_itensmenu	, ";
  $campos .= "ar23_data					, ";
  $campos .= "ar23_hora					, ";
  $campos .= "ar23_tipo					, ";
  $campos .= "ar23_descricao		, ";
  $campos .= "ar23_ocorrencia		";
  $result = $clhistocorrencia->sql_record($clhistocorrencia->sql_query($ar23_sequencial, $campos));
  db_fieldsmemory($result, 0);
}
/*
if(isset($db_opcaoal)){
  $db_opcao = 33;
}*/
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
     <center>
     <table width="790" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
            <center>
              <?
                include("forms/db_frmaguahistocorrencia.php");
              ?>
            </center>
          </td>
        </tr>
      </table>
      </center>
</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
    db_msgbox($erro_msg);
    if($clhistocorrecia->erro_campo!=""){
        echo "<script> document.form1.".$clhistocorrencia->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clhistocorrencia->erro_campo.".focus();</script>";
    }
}
?>