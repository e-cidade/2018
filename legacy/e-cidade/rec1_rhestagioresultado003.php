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
require("libs/db_utils.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_rhestagioresultado_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$oGet = db_utils::postmemory($_GET);
$clrhestagioresultado = new cl_rhestagioresultado;
$db_botao = false;
$db_opcao = 33;
if(isset($excluir)){
  db_inicio_transacao();
  $db_opcao = 3;
  $clrhestagioresultado->excluir($h65_sequencial);
  db_fim_transacao();
}else if(isset($chavepesquisa)){
    $campos     = "distinct h65_sequencial,h65_data,h65_observacao,";
    $campos    .= " h57_sequencial, h57_regist, h50_minimopontos,z01_nome, rh01_admiss,h55_nroaval,fc_calculapontosestagio(h57_sequencial,'t') as pontos";
    $rResultado = $clrhestagioresultado->sql_record(
                   $clrhestagioresultado->sql_query_resultado(null,"$campos",null,"h57_sequencial = {$oGet->chavepesquisa}"));
   if ($clrhestagioresultado->numrows > 0){

     $db_opcao            = 3;
     $oResultado          = db_utils::fieldsMemory($rResultado,0);
     $z01_nome            = $oResultado->z01_nome;
     $h65_rhestagioagenda = $oResultado->h57_sequencial;
     $h65_pontos          = $oResultado->pontos;
     $db_botao            = true;
     $h65_sequencial      = $oResultado->h65_sequencial;
     if ($oResultado->h65_sequencial == null){
         $db_botao       = false;
     }
     if ($oResultado->h50_minimopontos > $oResultado->pontos){
        $h65_resultado = "R"; 
     }else{
        $h65_resultado = "A"; 
     }
   }else{
     db_msgbox('Nao foi encontrado dados do estágio ');
   }
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
    <center>
	<?
	include("forms/db_frmrhestagioresultado.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($excluir)){
  if($clrhestagioresultado->erro_status=="0"){
    $clrhestagioresultado->erro(true,false);
  }else{
    $clrhestagioresultado->erro(true,true);
  }
}
?>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>