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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("classes/db_paritbi_classe.php");
require_once("classes/db_parreciboitbi_classe.php");
require_once("dbforms/db_funcoes.php");

$oPost 	         = db_utils::postMemory($_POST);
$clparitbi       = new cl_paritbi;
$clparreciboitbi = new cl_parreciboitbi;
$lSqlErro        = false;
$db_opcao        = 1;

if (isset($oPost->incluir)) {

  db_inicio_transacao();

  $clparitbi->it24_anousu  				                 = $oPost->it24_anousu;
  $clparitbi->it24_grupodistrterrarural            = $oPost->it24_grupodistrterrarural;
  $clparitbi->it24_grupoespbenfrural	             = $oPost->it24_grupoespbenfrural;
  $clparitbi->it24_grupoespbenfurbana	             = $oPost->it24_grupoespbenfurbana;
  $clparitbi->it24_grupotipobenfrural	             = $oPost->it24_grupotipobenfrural;
  $clparitbi->it24_grupotipobenfurbana             = $oPost->it24_grupotipobenfurbana;
  $clparitbi->it24_grupoutilterrarural             = $oPost->it24_grupoutilterrarural;
  $clparitbi->it24_diasvctoitbi			               = $oPost->it24_diasvctoitbi;
  $clparitbi->it24_impsituacaodeb                  = $oPost->it24_impsituacaodeb;
  $clparitbi->it24_alteraguialib                   = $oPost->it24_alteraguialib;
  $clparitbi->it24_taxabancaria			               = $oPost->it24_taxabancaria;
  $clparitbi->it24_grupopadraoconstrutivobenurbana = $oPost->it24_grupopadraoconstrutivobenurbana;
  $clparitbi->incluir($oPost->it24_anousu);

  if ( $clparitbi->erro_status == 0 ) {
  	$lSqlErro = true;
  }
   $sErroMsg = $clparitbi->erro_sql;
   $sql = $clparreciboitbi->sql_query();
   $rsParreciboitbi   = $clparreciboitbi->sql_record($sql);
   if ($clparreciboitbi->numrows != 0) {

     $clparreciboitbi->it17_codigo = $oPost->k02_codigo;
     $clparreciboitbi->it17_numcgm = $oPost->z01_numcgm;
     $clparreciboitbi->alterar();
   } else {

     $clparreciboitbi->it17_codigo = $oPost->k02_codigo;
     $clparreciboitbi->it17_numcgm = $oPost->z01_numcgm;
     $clparreciboitbi->incluir  ();
   }

  db_fim_transacao($lSqlErro);

} else if(isset($oPost->alterar)){

  db_inicio_transacao();

  $clparitbi->it24_anousu  				                 = $oPost->it24_anousu;
  $clparitbi->it24_grupodistrterrarural            = $oPost->it24_grupodistrterrarural;
  $clparitbi->it24_grupoespbenfrural	             = $oPost->it24_grupoespbenfrural;
  $clparitbi->it24_grupoespbenfurbana	             = $oPost->it24_grupoespbenfurbana;
  $clparitbi->it24_grupotipobenfrural	             = $oPost->it24_grupotipobenfrural;
  $clparitbi->it24_grupotipobenfurbana             = $oPost->it24_grupotipobenfurbana;
  $clparitbi->it24_grupoutilterrarural             = $oPost->it24_grupoutilterrarural;
  $clparitbi->it24_diasvctoitbi			               = $oPost->it24_diasvctoitbi;
  $clparitbi->it24_impsituacaodeb                  = $oPost->it24_impsituacaodeb;
  $clparitbi->it24_alteraguialib                   = $oPost->it24_alteraguialib;
  $clparitbi->it24_taxabancaria			               = $oPost->it24_taxabancaria;
  $clparitbi->it24_grupopadraoconstrutivobenurbana = $oPost->it24_grupopadraoconstrutivobenurbana;
  $clparitbi->alterar($oPost->it24_anousu);

  if ( $clparitbi->erro_status == 0 ) {
  	$lSqlErro = true;
  }

  $sErroMsg        = $clparitbi->erro_sql;
  $sql             = $clparreciboitbi->sql_query_file();
  $rsParreciboitbi = $clparreciboitbi->sql_record($sql);
  if ($clparreciboitbi->numrows != 0) {

    $clparreciboitbi->it17_codigo = $oPost->k02_codigo;
    $clparreciboitbi->it17_numcgm = $oPost->z01_numcgm;
    $clparreciboitbi->alterar();

  } else {

    $clparreciboitbi->it17_codigo = $oPost->k02_codigo;
    $clparreciboitbi->it17_numcgm = $oPost->z01_numcgm;
    $clparreciboitbi->incluir();
  }
  db_fim_transacao($lSqlErro);

  $db_opcao  = 2;

} else {

   $sCampos  = " paritbi.*, 					     ";
   $sCampos .= " a.j32_descr as nomeespbenfurbana,                ";
   $sCampos .= " b.j32_descr as nometipobenfurbana,               ";
   $sCampos .= " c.j32_descr as nomeespbenfrural,                 ";
   $sCampos .= " d.j32_descr as nometipobenfrural,                ";
   $sCampos .= " e.j32_descr as nomeutilterrarural,               ";
   $sCampos .= " f.j32_descr as nomedistrterrarural               ";
   $sCampos .= " ,g.j32_descr as nomegrupopadraoconstrutivourbana  ";

   $rsVerificaParam = $clparitbi->sql_record($clparitbi->sql_query_dados_paritbi(db_getsession('DB_anousu'),$sCampos));

   if ( $clparitbi->numrows > 0 ) {

   	  db_fieldsmemory($rsVerificaParam,0);
   	  $db_opcao    = 2;
   } else {
   	  $it24_anousu  = db_getsession('DB_anousu');
   }

   $sql             = $clparreciboitbi->sql_query();
   $rsParreciboitbi = $clparreciboitbi->sql_record($sql);
   if ($clparreciboitbi->numrows != 0) {
     db_fieldsmemory($rsParreciboitbi,0);
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
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <div class="container">
    <form name="form1" method="post" action="">
      <?php
        include("forms/db_frmparitbi.php");
      ?>
    </form>
  </div>
<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?php
if(isset($oPost->alterar) || isset($oPost->incluir)){

  if( $lSqlErro ){
    $clparitbi->erro(true,false);
    if($clparitbi->erro_campo!=""){
      echo "<script> document.form1.".$clparitbi->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clparitbi->erro_campo.".focus();</script>";
    }
  }else{
    $clparitbi->erro(true,true);
  }
}
?>
<script type="text/javascript">
  js_tabulacaoforms("form1","it24_grupoespbenfurbana",true,1,"it24_grupoespbenfurbana",true);
</script>