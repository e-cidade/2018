<?php

/**
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
require_once("classes/db_cfpess_classe.php");
require_once("classes/db_inssirf_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");

db_postmemory( $HTTP_SERVER_VARS );
db_postmemory( $HTTP_POST_VARS );

$clcfpess  = new cl_cfpess;
$clinssirf = new cl_inssirf;

$db_opcao  = 2;
$db_botao  = true;

if (isset($alterar)) {

  $GLOBALS["HTTP_POST_VARS"]["r11_baseconsignada"] = $r08_codigo;
  include("pes1_cfpess002.php");
}

$sql = $clcfpess->sql_query_parametro(db_anofolha(),
                                      db_mesfolha(),
                                      db_getsession("DB_instit"),
                                      "r11_rubdec,           a.rh27_descr as rh27_descr1,
                                       r11_ferias,           b.rh27_descr as rh27_descr2,
                                       r11_fer13,            c.rh27_descr as rh27_descr3,
                                       r11_ferabo,           d.rh27_descr as rh27_descr4,
                                       r11_feradi,           e.rh27_descr as rh27_descr5,
                                       r11_fadiab,           f.rh27_descr as rh27_descr6,
                                       r11_ferant,           g.rh27_descr as rh27_descr7,
                                       r11_feabot,           h.rh27_descr as rh27_descr8,
                                       r11_palime,           i.rh27_descr as rh27_descr9,
                                       r11_fer13a,           j.rh27_descr as rh27_descr10,
                                       r11_abonoprevidencia, l.rh27_descr as rh27_descr11,
                                       r11_desliq, r11_rubpgintegral,
                                       r08_codigo, r08_descr,
                                       r11_rubricasubstituicaoatual, r11_rubricasubstituicaoanterior");

$result = $clcfpess->sql_record($sql);
if ($result != false && $clcfpess->numrows > 0) {
  db_fieldsmemory($result,0);
}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>
    <script src="scripts/widgets/dbmessageBoard.widget.js"></script>
  </head>
  <body>

    <?php
    include("forms/db_frmcfpessrubricasespeciais.php");
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>

  </body>
</html>
<?php
// r11_abonoprevidencia
if (isset($alterar)) {

  if ($sqlerro) {
    db_msgbox($erro_msg);
  } else {
    db_msgbox("Rubricas alteradas com sucesso.");
  }

  if ($clcfpess->erro_status == "0") {

    $clcfpess->erro(true, false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($clcfpess->erro_campo != "") {

      echo "<script> document.form1." . $clcfpess->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1." . $clcfpess->erro_campo . ".focus();</script>";
    }
  } else {
//    $clcfpess->erro(true,true);
  }
}

if ($db_opcao == 22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}