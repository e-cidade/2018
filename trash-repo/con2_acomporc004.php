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
include("libs/db_liborcamento.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_orcparamrel_classe.php");
include("classes/db_orcparamrelnota_classe.php");

$clorcparamrel            = new cl_orcparamrel;
$clorcparamrelnota        = new cl_orcparamrelnota;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

$clorcparamrelnota->rotulo->label();

$clrotulo   = new rotulocampo;
$clrotulo->label('c83_codrel');
$clrotulo->label('o42_descrrel');

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$instit   = db_getsession("DB_instit");
$anousu   = db_getsession("DB_anousu");
$sqlerro  = false;
$erro_msg = "";

$db_opcao = 1;
if (!isset($opcao)){
  $opcao = "gravar";
}

if (isset($opcao) && $opcao == "alterar" || $opcao == "excluir"){
  $res = $clorcparamrelnota->sql_record($clorcparamrelnota->sql_query_file($c83_codrel,$anousu,$instit,@$o42_periodo));
  if ($clorcparamrelnota->numrows > 0 ) {
    db_fieldsmemory($res,0);
  } else {
    $o42_periodo = "0";
    $o42_nota    = "";
    $o42_fonte   = "";
  }
}

if (isset($opcao) && $opcao == "alterar"){
  $db_opcao = 2;
}

if (isset($opcao) && $opcao == "excluir"){
  $db_opcao = 3;
}

if (isset($gravar)) {
  db_inicio_transacao();

  if (trim($o42_nota) == "" && trim($o42_fonte) == ""){
    $sqlerro  = true;
    $erro_msg = "Informe Fonte/Nota Explicativa. Verifique.";
  }

  if ($sqlerro == false){
    $clorcparamrelnota->o42_nota    = $o42_nota;
    $clorcparamrelnota->o42_fonte   = $o42_fonte;
  
    $clorcparamrelnota->o42_periodo = $o42_periodo;
    $clorcparamrelnota->o42_instit  = $instit;
    $clorcparamrelnota->o42_anousu  = $anousu;
    $clorcparamrelnota->o42_codparrel = $c83_codrel;
 
    $clorcparamrelnota->incluir($c83_codrel,$anousu,$instit,$o42_periodo);
    $erro_msg = $clorcparamrelnota->erro_msg;
    if ($clorcparamrelnota->erro_status == 0 ) {
      $sqlerro = true;
    }
  }

  db_fim_transacao($sqlerro);
}

if (isset($alterar)){
  db_inicio_transacao();

  $clorcparamrelnota->o42_nota      = $o42_nota;
  $clorcparamrelnota->o42_fonte     = $o42_fonte;
  
  $clorcparamrelnota->o42_periodo   = $o42_periodo;
  $clorcparamrelnota->o42_instit    = $instit;
  $clorcparamrelnota->o42_anousu    = $anousu;
  $clorcparamrelnota->o42_codparrel = $c83_codrel;

  $clorcparamrelnota->alterar($c83_codrel,$anousu,$instit,$o42_periodo);
  $erro_msg = $clorcparamrelnota->erro_msg;
  if ($clorcparamrelnota->erro_status == 0 ) {
    $sqlerro = true;
    db_msgbox($clorcparamrelnota->erro_msg);
  }

  db_fim_transacao($sqlerro);

  if ($sqlerro == false){
    $opcao    = "gravar";
    $db_opcao = 1;
  }
}

if (isset($excluir)){
  db_inicio_transacao();

  $clorcparamrelnota->o42_periodo   = $o42_periodo;
  $clorcparamrelnota->o42_instit    = $instit;
  $clorcparamrelnota->o42_anousu    = $anousu;
  $clorcparamrelnota->o42_codparrel = $c83_codrel;

  $clorcparamrelnota->excluir($c83_codrel,$anousu,$instit,$o42_periodo);
  $erro_msg = $clorcparamrelnota->erro_msg;
  if ($clorcparamrelnota->erro_status == 0 ) {
    $sqlerro = true;
    db_msgbox($clorcparamrelnota->erro_msg);
  }

  db_fim_transacao($sqlerro);

  if ($sqlerro == false){
    $opcao    = "gravar";
    $db_opcao = 1;
  }
}

if ($db_opcao == 1){
  $o42_periodo = "0";
  $o42_nota    = "";
  $o42_fonte   = "";
}
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_limpar(){
  document.location.href="con2_anexo12rpps_004.php?c83_codrel=<?=$c83_codrel?>";
}
function js_verifica(opcao){
  return true;
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">

<form name="form1" method="post" action="" onSubmit="return js_verifica('<?=$opcao?>');">
<center>
 <table align="center" border=0>
<? 
   db_input('c83_codrel',5,$Ic83_codrel,true,'hidden',3,"")
?>
 <tr>
    <td colspan=2 align=center title="<?=$To42_periodo?>"><?=$Lo42_periodo?>
		<?
      $result1=array("01"=>"Janeiro","02"=>"Fevereiro","03"=>"Março","04"=>"Abril","05"=>"Maio","06"=>"Junho","07"=>"Julho","08"=>"Agosto","09"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro");
      db_select("o42_periodo",$result1,true,$db_opcao,"");
      if ($db_opcao == 3){
        $readonly = " readonly ";
        $style    = ";background-color:#DEB887;";
      } else {
        $readonly = "";
        $style    = "";
      }
  	?>
    </td>
 </tr>
 <tr><td colspan=2>&nbsp;</td></tr>
 <tr><td colspan=2 align="left"><?=$Lo42_nota?></td></tr>   
 <tr>
   <td colspan=2 width="100%"> 
     <textarea title="<?=$To42_nota?>" <?=$readonly?> style="font-family:Arial;font-size:12pt<?=$style?>" name=o42_nota rows=8 cols=100 value=<?=$o42_nota?> ><?=$o42_nota?></textarea>
   </td>
 </tr>
 <tr><td colspan=2>&nbsp;</td></tr>
 <tr><td colspan=2 align="left"><?=$Lo42_fonte?></td></tr>   
 <tr>
   <td colspan=2 width="100%">
     <textarea title="<?=$To42_fonte?>" <?=$readonly?> style="font-family:Arial;font-size:12pt<?=$style?>" name=o42_fonte rows=5 cols=100 value=<?=$o42_fonte?> ><?=$o42_fonte?></textarea>
   </td>
 </tr>
 <tr><td colspan=2>&nbsp;</td></tr>
 <tr>
   <td colspan=2 align="center"><input type=submit name="<?=$opcao?>" value=<?=ucfirst($opcao)?>>
   <?
      if ($db_opcao != 1) {
   ?>
   &nbsp;&nbsp;<input type=button name="novo" value=Novo onClick="js_limpar();">
   <?
      }
   ?>
   </td>
 </tr>
 <tr><td colspan=2>&nbsp;</td></tr>
 <tr>
   <td colspan=2>
     <table border=0 width=790>
       <tr>
         <td>
					 <?
						 $chavepri= array("o42_codparrel"=>$c83_codrel,"o42_anousu"=>$anousu,"o42_instit"=>$instit,"o42_periodo"=>@$o42_periodo);
						 $cliframe_alterar_excluir->chavepri= $chavepri;
						 $cliframe_alterar_excluir->sql     = $clorcparamrelnota->sql_query_file($c83_codrel,$anousu,$instit); 
						 $cliframe_alterar_excluir->campos  = "o42_periodo,o42_nota,o42_fonte";
						 $cliframe_alterar_excluir->legenda = "Fonte/Notas Explicativas";
						 $cliframe_alterar_excluir->iframe_height = "200";
						 $cliframe_alterar_excluir->iframe_width  = "100%";

						 $clorcparamrelnota->sql_record($cliframe_alterar_excluir->sql); 
						 if ($clorcparamrelnota->numrows == 0){
							 $cliframe_alterar_excluir->msg_vazio = "Nenhum registro cadastrado.";
						 }

						 $cliframe_alterar_excluir->iframe_alterar_excluir(1);
					 ?>
          </td>
       </tr> 
     </table>
   </td>
 </tr>
</table>
</center>
</form>
<?
   if (trim($erro_msg)){
     db_msgbox($erro_msg);
   }
?>
</body>
</html>