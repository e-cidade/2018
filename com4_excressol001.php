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
include("classes/db_solicita_classe.php");
include("classes/db_orcreservasol_classe.php");
include("classes/db_orcreserva_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
db_postmemory($HTTP_POST_VARS);
$cliframe_seleciona = new cl_iframe_seleciona;
$clsolicita = new cl_solicita;
$clorcreserva = new cl_orcreserva;
$clorcreservasol = new cl_orcreservasol;
$clrotulo = new rotulocampo;
$clrotulo->label("");
if (isset($processar)){
  $arr_info = split("#",$chaves);
  $sqlerro=false;
  db_inicio_transacao();
  for($w=0;$w<count($arr_info);$w++){
    $solicita = $arr_info[$w]; 
    $result = $clsolicita->sql_record($clsolicita->sql_query_reserv($solicita,"distinct o82_codres as codres",null,"pc10_numero = $solicita and o82_codres is not null and pc81_solicitem is null"));
    $numrows = $clsolicita->numrows;
    for ($i=0;$i<$numrows;$i++){
      db_fieldsmemory($result,$i);
      if ($sqlerro==false){
	$clorcreservasol->o82_codres = $codres;
	$clorcreservasol->excluir($codres);
	if ($clorcreservasol->erro_status=='0'){
  	  $sqlerro = true;
	  $erro_msg = $clorcreservasol->erro_msg;
	  break;
	}
      }
      if ($sqlerro==false){
	$clorcreserva->o80_codres = $codres;
	$clorcreserva->excluir($codres);
	if ($clorcreserva->erro_status=='0'){
  	  $sqlerro = true;
	  $erro_msg = $clorcreserva->erro_msg;
	  break;
	}
      }
    }
  }  
  db_fim_transacao($sqlerro);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_submit_form(){
  js_gera_chaves();
  return true;
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr>
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
<center>
<form name="form1" method="post">

<table border='0'>
<tr height="20px">
<td ></td>
<td ></td>
</tr>
  <tr>
    <td colspan=2>
    <?
           $cliframe_seleciona->campos  = "pc10_numero,pc10_data,pc10_resumo,pc10_depto,descrdepto,pc10_instit,nomeinst,pc10_login,nome";
           $cliframe_seleciona->legenda="Solicitações";
           $cliframe_seleciona->sql=$clsolicita->sql_query_reserv(null,"distinct pc10_numero,pc10_data,pc10_resumo,pc10_depto,descrdepto,pc10_instit,nomeinst,pc10_login,nome",null,"o82_codres is not null and pc81_solicitem is null and (current_date-pc10_data)>=30 and pc10_depto in (select coddepto from db_depusu where id_usuario = ".db_getsession("DB_id_usuario").") ");
           $cliframe_seleciona->iframe_height ="300";
           $cliframe_seleciona->iframe_width ="650";
           $cliframe_seleciona->iframe_nome ="solicita"; 
           $cliframe_seleciona->chaves = "pc10_numero";
           $cliframe_seleciona->iframe_seleciona(1);    
    ?>
    </td>
  </tr>
  <tr height="20px">
  <td ></td>
  <td ></td>
  </tr>
  <tr>
  <td colspan="2" align="center">
    <input name="processar" type="submit" value="Processar" onclick='return js_submit_form();';>
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
<script>
</script>
<?
if (isset($processar)){
  if ($sqlerro==true){    
    db_msgbox($erro_msg);
  }else{
    db_msgbox("Processamento concluido com sucesso!!");
    echo "<script>location.href='com4_excressol001.php';</script>";
  }
}
?>