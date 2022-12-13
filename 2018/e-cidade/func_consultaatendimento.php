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
include("classes/db_atendimento_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clatendimento = new cl_atendimento;
$clatendimento->rotulo->label("at02_codatend");
$clatendimento->rotulo->label("at02_codcli");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td align="center" valign="top"> 
      <?
      $where = "";
      if(isset($at02_codcli) && $at02_codcli != "" ){
        $where = " clientes.at01_codcli = $at02_codcli";
      }
      if(isset($at03_id_usuario) && $at03_id_usuario != ""){
        if($where != ""){
          $where.= " and ";
        }
          $where.= " tecnico.at03_id_usuario = $at03_id_usuario";
      }
      if(isset($at02_dataini_dia) && $at02_dataini_dia != ""){
        if($where != ""){
          $where .= " and ";
        }
        $where .= "  atendimento.at02_dataini >= '".$at02_dataini_ano."-".$at02_dataini_mes."-".$at02_dataini_dia."'";
      }
      if(isset($at02_datafim_dia) && $at02_datafim_dia != ""){
        if($where != ""){
          $where .= " and ";
        }
          $where .= " atendimento.at02_datafim <= '".$at02_datafim_ano."-".$at02_datafim_mes."-".$at02_datafim_dia."'";
      }
      $sql = $clatendimento->sql_query_tecnico("","distinct at02_codatend#at02_dataini#at02_datafim#at01_nomecli","",$where);
      db_lovrot($sql,15,"()","",$funcao_js);
      ?>
     </td>
   </tr>
</table>
</body>
</html>