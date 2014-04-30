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

//MODULO: educação
include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_censoativcompl_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clcensoativcompl = new cl_censoativcompl;
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
   <fieldset style="width:95%"><legend><b>Atividade Complementar</b></legend>
   <br>
   <?
   $campos = "ed133_i_codigo,
              ed133_c_descr,
              case
               when ed133_i_tipo = 11
                then 'ARTES E CULTURA - Música'
               when ed133_i_tipo = 12
                then 'ARTES E CULTURA - Artes Plásticas'
               when ed133_i_tipo = 13
                then 'ARTES E CULTURA - Cinema'
               when ed133_i_tipo = 14
                then 'ARTES E CULTURA - Artes Cênicas'
               when ed133_i_tipo = 15
                then 'ARTES E CULTURA - Manifestações Culturais Regionais'
               when ed133_i_tipo = 19
                then 'ARTES E CULTURA - Outras'
               when ed133_i_tipo = 21
                then 'ESPORTE E LAZER - Recreação / Lazer'
               when ed133_i_tipo = 22
                then 'ESPORTE E LAZER - Atividades Desportivas'
               when ed133_i_tipo = 29
                then 'ESPORTE E LAZER - Outras'
               when ed133_i_tipo = 31
                then 'ACOMPANHAMENTO PEDAGÓGICO - Acompanhamento Pedagógico'
               when ed133_i_tipo = 39
                then 'ACOMPANHAMENTO PEDAGÓGICO - Outras'
               when ed133_i_tipo = 41
                then 'DIREITOS HUMANOS E CIDADANIA - Direitos Humanos'
               when ed133_i_tipo = 49
                then 'DIREITOS HUMANOS E CIDADANIA - Outras'
               when ed133_i_tipo = 51
                then 'EDUCAÇÃO AMBIENTAL'
               when ed133_i_tipo = 59
                then 'MEIO AMBIENTE E DESENVOLVIMENTO SUSTENTÁVEL - Outras'
               when ed133_i_tipo = 61
                then 'INCLUSÃO DIGITAL E COMUNICAÇÃO - Inclusão Digital'
               when ed133_i_tipo = 69
                then 'INCLUSÃO DIGITAL E COMUNICAÇÃO - Outras'
               when ed133_i_tipo = 71
                then 'SAÚDE, ALIMENTAÇÃO E PREVENÇÃO - Saúde, Alimentação e Prevenção'
               when ed133_i_tipo = 79
                then 'SAÚDE, ALIMENTAÇÃO E PREVENÇÃO - Outras'
               when ed133_i_tipo = 81
                then 'ATIVIDADES DE INICIAÇÃO PROFISSIONAL - Atividades de iniciação profissional'
               when ed133_i_tipo = 91
                then 'PROGRAMAS INTERSETORIAIS - Programas Intersetoriais'
               when ed133_i_tipo = 99
                then 'PROGRAMAS INTERSETORIAIS - Outras'
               when ed133_i_tipo = 101
                then 'EDUCAÇÃO CIENTÍFICA - Educação Científica'
               when ed133_i_tipo = 109
                then 'EDUCAÇÃO CIENTÍFICA - Outras'
              end as dl_categoria,
              ed133_i_tipo
             ";
   $sql = $clcensoativcompl->sql_query("",$campos,"ed133_i_tipo,ed133_i_codigo","");
   $repassa = array();
   if(isset($chave_ed133_i_codigo)){
    $repassa = array("chave_ed133_i_codigo"=>@$chave_ed133_i_codigo);
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