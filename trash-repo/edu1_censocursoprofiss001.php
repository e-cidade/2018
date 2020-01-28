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

//MODULO: educação
include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_censocursoprofiss_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clcensocursoprofiss = new cl_censocursoprofiss;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
<table width="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td align="center" valign="top">
   <br>
   <fieldset style="width:95%"><legend><b>Curso da Educação Profissional</b></legend>
   <br>
   <?
   $campos = "ed247_i_codigo,
              ed247_c_descr,
              case
               when ed247_i_tipo = 1
                then 'AMBIENTE, SAÚDE E SEGURANÇA'
               when ed247_i_tipo = 2
                then 'APOIO EDUCACIONAL'
               when ed247_i_tipo = 3
                then 'CONTROLE E PROCESSOS INDUSTRIAIS'
               when ed247_i_tipo = 4
                then 'GESTÃO E NEGÓCIOS'
               when ed247_i_tipo = 5
                then 'HOSPITALIDADE E LAZER'
               when ed247_i_tipo = 6
                then 'INFORMAÇÃO E COMUNICAÇÃO'
               when ed247_i_tipo = 7
                then 'INFRA-ESTRUTURA'
               when ed247_i_tipo = 8
                then 'MILITAR'
               when ed247_i_tipo = 9
                then 'PRODUÇÃO ALIMENTÍCIA'
               when ed247_i_tipo = 10
                then 'PRODUÇÃO CULTURAL E DESIGN'
               when ed247_i_tipo = 11
                then 'PRODUÇÃO INDUSTRIAL'
               when ed247_i_tipo = 12
                then 'RECURSOS NATURAIS'
              end as dl_area,
              ed247_i_tipo
             ";
   $sql = $clcensocursoprofiss->sql_query("",$campos,"ed247_i_tipo,ed247_c_descr","");
   $repassa = array();
   if(isset($chave_ed247_i_codigo)){
    $repassa = array("chave_ed247_i_codigo"=>@$chave_ed247_i_codigo);
   }
   db_lovrot($sql,25,"","","","","NoMe",$repassa);
   ?>
   </fieldset>
  </td>
 </tr>
</table>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>