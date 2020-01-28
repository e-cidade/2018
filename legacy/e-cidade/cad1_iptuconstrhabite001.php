<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_classesgenericas.php"));
require_once(modification("classes/db_iptuconstrhabite_classe.php"));
require_once(modification("classes/db_protprocesso_classe.php"));
require_once(modification("classes/db_obrashabite_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("std/DBDate.php"));

db_postmemory($_POST);
$oGet = db_utils::postMemory($_GET);

$cliptuconstrhabite       = new cl_iptuconstrhabite;
$clobrashabite            = new cl_obrashabite;
$clprotprocesso           = new cl_protprocesso;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

$cliptuconstrhabite->rotulo->label();

$db_opcao = 1;
$db_botao = true;

$lProcesso = "S";
$lHabite   = "S";

$z01_nome = DBString::urldecode_all($oGet->z01_nome);

if (isset($opcao) && $opcao=="alterar") {
  $db_opcao = 2;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
  $db_opcao = 3;
  if (isset($db_opcaoal)) {
	  $db_opcao=33;
  }
} else {
  $db_opcao = 1;
}

if( isset($j131_dthabite) && $j131_dthabite != null) {

  $oData         = new DBDate($j131_dthabite);
  $j131_dthabite = $oData->getDate();
}

if( isset($j131_dtprot) && $j131_dtprot != null) {

  $oData        = new DBDate($j131_dtprot);
  $j131_dtprot  = $oData->getDate();
}

if(isset($incluir)){

  db_inicio_transacao();
  $cliptuconstrhabite->j131_dtprot   = $j131_dtprot;
  $cliptuconstrhabite->j131_dthabite = $j131_dthabite;
  $cliptuconstrhabite->j131_usuario  = db_getsession("DB_id_usuario");
  $cliptuconstrhabite->j131_data     = date("Y-m-d",db_getsession("DB_datausu"));
  $cliptuconstrhabite->j131_hora     = date("H:i",db_getsession("DB_uol_hora"));
  $cliptuconstrhabite->incluir(null);
  db_fim_transacao();

} else if(isset($alterar)) {

  db_inicio_transacao();
  $cliptuconstrhabite->j131_dtprot   = $j131_dtprot;
  $cliptuconstrhabite->j131_dthabite = $j131_dthabite;
  $cliptuconstrhabite->alterar($j131_sequencial);
  db_fim_transacao();

} else if(isset($excluir)) {

  db_inicio_transacao();
  $cliptuconstrhabite->excluir($j131_sequencial);
  db_fim_transacao();

}


if (isset($opcao) && ($opcao=="alterar" || $opcao=="excluir")){
   $rsHabite = $cliptuconstrhabite->sql_record($cliptuconstrhabite->sql_query(null,"*","","j131_sequencial = {$j131_sequencial} "));
   db_fieldsmemory($rsHabite,0, true);

   //verificamos se o processo de protocolo é do sistema ou não
   if (!empty($j131_codprot)) {
     $rsProcesso = $clprotprocesso->sql_record($clprotprocesso->sql_query($j131_codprot,"cgm.z01_nome as p58_requer",null));
     if ( $clprotprocesso->numrows > 0 ){
       db_fieldsmemory($rsProcesso, 0);
     } else {
       $lProcesso = "N";
     }
   }

   //verificamos se o habite-se é do sistema ou não
   if (!empty($j131_cadhab)) {
     $rsHabite = $clobrashabite->sql_record($clobrashabite->sql_query($j131_cadhab,"ob09_habite",null));
     if ( $clobrashabite->numrows > 0 ){
       db_fieldsmemory($rsHabite, 0);
     } else {
       $lHabite = "N";
     }
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1; js_montaCampoHabite(); js_montaCampoProcesso();" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top" bgcolor="#CCCCCC">
    <br><br>
    <center>
    <fieldset style='width: 650px'>
    <legend><b>Cadastro de Habite-se</b></legend>
    <br><br>
	<?
	include(modification("forms/db_frmiptuconstrhabite.php"));
	?>
    </center>
    </fieldset>
	</td>
  </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","j131_idcons",true,1,"j131_idcons",true);
</script>
<?
if(isset($incluir) || isset($alterar) || isset($excluir)){
    $cliptuconstrhabite->erro(true,false);

    if($cliptuconstrhabite->erro_status != 0) {
      echo "<script>
            js_cancelar();
          </script>";
    }
}
?>