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

require("libs/db_stdlib.php");
require("libs/db_stdlibwebseller.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_devolucaoacervo_classe.php");
include("classes/db_acervo_classe.php");
include("classes/db_reserva_classe.php");
include("classes/db_leitor_classe.php");
include("classes/db_bib_parametros_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$cldevolucaoacervo = new cl_devolucaoacervo;
$clacervo          = new cl_acervo;
$clreserva         = new cl_reserva;
$clleitor          = new cl_leitor;
$clbib_parametros  = new cl_bib_parametros;
$db_opcao          = 1;
$db_botao          = true;
$depto             = db_getsession("DB_coddepto");
$sql               = "SELECT bi17_codigo,bi17_nome FROM biblioteca WHERE bi17_coddepto = $depto";
$result            = db_query($sql);;
$linhas            = pg_num_rows($result);
if ($linhas != 0) {
	
  db_fieldsmemory($result,0);
  $result1 = $clbib_parametros->sql_record($clbib_parametros->sql_query("",
                                                                        "bi26_leitorbarra",
                                                                        "",
                                                                        " bi26_biblioteca = $bi17_codigo"
                                                                       )
                                          );
  if ($clbib_parametros->numrows > 0) { 
    db_fieldsmemory($result1,0);
  } else {
    $bi26_leitorbarra = "N";
  }
}

if (isset($devolvolucao) && $devolvolucao != "") {

  $array_devolve = explode("|",$devolvolucao);
  $codacervos    = "";
  $sep           = "";
  for ($x = 0; $x < count($array_devolve)-1; $x++) {
  	
    $array_x                                  = explode(";",$array_devolve[$x]);
    $cldevolucaoacervo->bi21_emprestimoacervo = $array_x[0];
    $cldevolucaoacervo->bi21_entrega          = date("Y-m-d",db_getsession("DB_datausu"));
    $cldevolucaoacervo->bi21_usuario          = db_getsession("DB_id_usuario");
    $codacervos                              .= $sep.$array_x[3];
    $sep                                      = ",";
    db_inicio_transacao();
    $cldevolucaoacervo->incluir($array_x[0]);
    db_fim_transacao();
    
    if ($cldevolucaoacervo->erro_status == "0") {
    	
      $cldevolucaoacervo->erro(true,false);
      db_redireciona("bib1_devolucao001.php");
      
    }
  }
  
  $result1 = $clreserva->sql_record($clreserva->sql_query("",
                                                          "bi14_acervo",
                                                          "",
                                                          " bi14_acervo in ($codacervos) AND bi14_retirada is null"
                                                         )
                                   );
  if ($clreserva->numrows > 0) {
  	
    $codacervos = "";
    $sep        = "";
    for ($x = 0; $x < $clreserva->numrows; $x++) {
    	
      db_fieldsmemory($result1,$x);
      $codacervos .= $sep.$bi14_acervo;
      $sep         = ",";
      
    }
    db_redireciona("bib1_reserva004.php?codacervos=$codacervos");
  } else {
    $cldevolucaoacervo->erro(true,true);
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
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
<?MsgAviso(db_getsession("DB_coddepto"),"biblioteca",""," bi17_coddepto = ".db_getsession("DB_coddepto")."");?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Devolução de Acervos</b></legend>
    <?include("forms/db_frmdevolucao.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),
          db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
 //js_tabulacaoforms("form1","bi18_carteira",true,1,"bi18_carteira",true);
</script>