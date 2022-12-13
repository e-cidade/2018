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
include("libs/db_libpessoal.php");
include("classes/db_infla_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clinfla = new cl_infla;
$db_opcao = 22;
$db_botao = false;
if(isset($incluir)){
  $sqlerro = false;
  // Nome do novo arquivo
  $nomearq = $_FILES["arquivo"]["name"];
  
  // Nome do arquivo temporário gerado no /tmp
  $nometmp = $_FILES["arquivo"]["tmp_name"];
  // Seta o nome do arquivo destino do upload
  $arquivo = "tmp/$nomearq";
  
  // Faz um upload do arquivo para o local especificado
  move_uploaded_file($nometmp,$arquivo) or $erro_msg = "ERRO: Contate o suporte.";

  // Abre o arquivo
  $ponteiro = fopen("$arquivo","r") or $erro_msg = "ERRO: Arquivo não abre.";

  if(!isset($erro_msg)){
    db_inicio_transacao();

    db_sel_cfpess(db_anofolha(), db_mesfolha(), "r11_infla");

    while(!feof($ponteiro)){
      $poslinha = fgets($ponteiro,4096);
      if(trim($poslinha) == "" || trim($poslinha) == ";;"){
        continue;
      }
      $poslinha = substr($poslinha,1,strlen($poslinha));
      $arr_poslinha = split($separador,$poslinha);
      $datas = $arr_poslinha[0];
      $valor = $arr_poslinha[1];

      $arr_datas = split("/",$datas);
      $datas = $arr_datas[2]."-".$arr_datas[1]."-".$arr_datas[0];
      $valor = str_replace(",",".",$valor);

      $clinfla->i02_valor  = $valor;
      $result_infla = $clinfla->sql_record($clinfla->sql_query_file($r11_infla,$datas));
      if($clinfla->numrows > 0){
        $clinfla->i02_codigo = $r11_infla;
        $clinfla->i02_data   = $datas;
	$clinfla->alterar($r11_infla,$datas);
      }else{
        $clinfla->incluir($r11_infla,$datas);
      }
      $erro_msg = $clinfla->erro_msg;
      if($clinfla->erro_status==0){
        $sqlerro=true;
	break;
      }

    }

    db_fim_transacao($sqlerro);

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
<table width="100%" height="18"  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
      <?
      include("forms/db_frmimportainfla.php");
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
if(isset($incluir)){
  db_msgbox($erro_msg);
}
?>