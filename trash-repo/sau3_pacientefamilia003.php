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
include("classes/db_prontproced_ext_classe.php");
$cl_prontproced = new cl_prontproced_ext;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<br>
<?
if(isset($z01_i_cgsund)){

   $campos=" prontuarios.sd24_i_codigo,
             prontagendamento.s102_i_agendamento as dl_Agenda,
             prontuarios.sd24_i_unidade,
             prontproced.sd29_d_data,          
             cgm.z01_nome as dl_Atendido_Por,                
             rhcbo.rh70_descr as dl_Especialidade,           
             sau_cid.sd70_c_nome as dl_Problemas_Relacionados ";
	 $sql=$cl_prontproced->sql_query_prontuario2("",$campos,""," sd24_i_numcgs = $z01_i_cgsund ");
   $repassa = array("chave_sd24_i_codigo"=>@$chave_sd24_i_codigo);
   db_lovrot($sql,15,"()","","");
    
}
?>
   <br><input name="ferchar" id="fechar" value="Fechar" type="button" onclick="js_fechar();">
</center>
</body>
</html>
<script>
    function js_fechar(){
        parent.iframeprontuarios.hide();
    }
</script>