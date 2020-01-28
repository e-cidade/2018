<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_matestoque_classe.php");
require_once("classes/db_matestoquedevitemmei_classe.php");
require_once("classes/db_matestoquedevitem_classe.php");
require_once("classes/db_matestoquedev_classe.php");
require_once("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clmatestoque = new cl_matestoque;
$clmatestoquedevitemmei = new cl_matestoquedevitemmei;
$clmatestoquedevitem = new cl_matestoquedevitem;
$db_botao = true;
$db_opcao = 1;
$clrotulo = new rotulocampo();
$clrotulo->label("coddepto");
$clrotulo->label("descrdepto");
if(isset($excluir)){

  if ($coddepto=="") {
		$sqlerro=true;
		$clmatestoque->erro_status="1";
		$clmatestoque->erro_msg="Preencha um departamento!";
	} else {

		db_inicio_transacao();

// 		$sql = "
    $rsExcluirEstoque = db_query("ALTER TABLE matestoquedev           DISABLE TRIGGER ALL");
    $rsExcluirEstoque = db_query("ALTER TABLE matestoquedevitem       DISABLE TRIGGER ALL");
    $rsExcluirEstoque = db_query("ALTER TABLE atendrequi              DISABLE TRIGGER ALL");
    $rsExcluirEstoque = db_query("ALTER TABLE atendrequiitem          DISABLE TRIGGER ALL");
    $rsExcluirEstoque = db_query("ALTER TABLE matrequiitem            DISABLE TRIGGER ALL");
    $rsExcluirEstoque = db_query("ALTER TABLE matestoqueinimei        DISABLE TRIGGER ALL");
    $rsExcluirEstoque = db_query("ALTER TABLE matestoqueini           DISABLE TRIGGER ALL");
    $rsExcluirEstoque = db_query("ALTER TABLE empnota                 DISABLE TRIGGER ALL");
    $rsExcluirEstoque = db_query("ALTER TABLE matestoqueitemlote      DISABLE TRIGGER ALL");
    $rsExcluirEstoque = db_query("ALTER TABLE matestoqueitemfabric    DISABLE TRIGGER ALL");
    $rsExcluirEstoque = db_query("ALTER TABLE empnotaitembenspendente DISABLE TRIGGER ALL");


		$rsExcluirEstoque = db_query("DELETE FROM matestoquedev
		                                    USING matestoquedevitem
		                                          INNER JOIN matestoquedevitemmei ON matestoquedevitemmei.m47_codmatestoquedevitem = matestoquedevitem.m46_codigo
		                                          INNER JOIN matestoqueitem       ON matestoqueitem.m71_codlanc = matestoquedevitemmei.m47_codmatestoqueitem
		                                          INNER JOIN matestoque           ON matestoque.m70_codigo = matestoqueitem.m71_codmatestoque
		                                    WHERE matestoquedevitem.m46_codmatestoquedev = m45_codigo
		                                      and matestoquedevitemmei.m47_codmatestoquedevitem = matestoquedevitem.m46_codigo
		                                      and matestoquedevitemmei.m47_codmatestoqueitem = matestoqueitem.m71_codlanc
		                                      and matestoqueitem.m71_codmatestoque = matestoque.m70_codigo
		                                      and matestoque.m70_coddepto = $coddepto");

		$rsExcluirEstoque = db_query("DELETE FROM matestoquedevitem
		                               USING matestoquedevitemmei
		                                     INNER JOIN matestoqueitem ON matestoqueitem.m71_codlanc = matestoquedevitemmei.m47_codmatestoqueitem
		                                     INNER JOIN matestoque     ON matestoque.m70_codigo = matestoqueitem.m71_codmatestoque
		                               WHERE matestoquedevitemmei.m47_codmatestoquedevitem = m46_codigo
		                                 and matestoquedevitemmei.m47_codmatestoqueitem = matestoqueitem.m71_codlanc
		                                 and matestoqueitem.m71_codmatestoque = matestoque.m70_codigo
		                                 and matestoque.m70_coddepto = {$coddepto}");

		$rsExcluirEstoque = db_query("DELETE FROM matestoquedevitemmei
		                               USING matestoqueitem
		                                     INNER JOIN matestoque ON matestoque.m70_codigo = matestoqueitem.m71_codmatestoque
		                               WHERE m47_codmatestoqueitem = matestoqueitem.m71_codlanc
		                                 and matestoqueitem.m71_codmatestoque = matestoque.m70_codigo
		                                 and matestoque.m70_coddepto = {$coddepto}");

		$rsExcluirEstoque = db_query("DELETE FROM far_retiradarequi
		                               USING matrequi
		                               WHERE fa07_i_matrequi = m40_codigo
		                                 and m40_depto = {$coddepto}");

		$rsExcluirEstoque = db_query("DELETE FROM atendrequi
		                               USING atendrequiitem
		                                     INNER JOIN atendrequiitemmei ON atendrequiitemmei.m44_codatendreqitem = atendrequiitem.m43_codigo
		                                     INNER JOIN matestoqueitem    ON matestoqueitem.m71_codlanc = atendrequiitemmei.m44_codmatestoqueitem
		                                     INNER JOIN matestoque        ON matestoque.m70_codigo = matestoqueitem.m71_codmatestoque
		                               WHERE atendrequiitem.m43_codatendrequi = m42_codigo
		                                 and atendrequiitemmei.m44_codatendreqitem = atendrequiitem.m43_codigo
		                                 and atendrequiitemmei.m44_codmatestoqueitem = matestoqueitem.m71_codlanc
		                                 and matestoqueitem.m71_codmatestoque = matestoque.m70_codigo
		                                 and matestoque.m70_coddepto = {$coddepto}");

		$rsExcluirEstoque = db_query("DELETE FROM atendrequiitem
		                               USING atendrequiitemmei
		                                     INNER JOIN matestoqueitem ON matestoqueitem.m71_codlanc = atendrequiitemmei.m44_codmatestoqueitem
		                                     INNER JOIN matestoque     ON matestoque.m70_codigo = matestoqueitem.m71_codmatestoque
		                               WHERE atendrequiitemmei.m44_codatendreqitem = m43_codigo
		                                 and atendrequiitemmei.m44_codmatestoqueitem = matestoqueitem.m71_codlanc
		                                 and matestoqueitem.m71_codmatestoque = matestoque.m70_codigo
		                                 and matestoque.m70_coddepto = {$coddepto}");

		$rsExcluirEstoque = db_query("DELETE FROM atendrequiitemmei
		                               USING matestoqueitem
		                                     INNER JOIN matestoque ON matestoque.m70_codigo = matestoqueitem.m71_codmatestoque
		                               WHERE m44_codmatestoqueitem = matestoqueitem.m71_codlanc
		                                 and matestoqueitem.m71_codmatestoque = matestoque.m70_codigo
		                                 and matestoque.m70_coddepto = {$coddepto}");

		$rsExcluirEstoque = db_query("DELETE FROM matrequiitem
		                               USING matrequi
		                               WHERE matrequi.m40_codigo = m41_codmatrequi
		                                 and matrequi.m40_depto = {$coddepto}");

		$rsExcluirEstoque = db_query("DELETE FROM matrequi
		                               USING matestoquedev
		                                     INNER JOIN matestoquedevitem    ON matestoquedevitem.m46_codmatestoquedev = matestoquedev.m45_codigo
		                                     INNER JOIN matestoquedevitemmei ON matestoquedevitemmei.m47_codmatestoquedevitem = matestoquedevitem.m46_codigo
		                                     INNER JOIN matestoqueitem       ON matestoqueitem.m71_codlanc = matestoquedevitemmei.m47_codmatestoqueitem
		                                     INNER JOIN matestoque           ON matestoque.m70_codigo = matestoqueitem.m71_codmatestoque
		                               WHERE matestoque.m70_coddepto = {$coddepto}");

    $rsExcluirEstoque = db_query("DELETE FROM matestoquetransferencia
                                   USING matestoqueitem
                                         INNER JOIN matestoque ON matestoqueitem.m71_codmatestoque = matestoque.m70_codigo
                                   WHERE matestoqueitem.m71_codlanc                                = matestoquetransferencia.m84_matestoqueitem
                                     AND matestoque.m70_coddepto = $coddepto");

		$rsExcluirEstoque = db_query("DELETE FROM matestoqueini
		                               USING matestoqueinimei
		                                     INNER JOIN matestoqueitem ON matestoqueitem.m71_codlanc = matestoqueinimei.m82_matestoqueitem
		                                     INNER JOIN matestoque     ON matestoque.m70_codigo = matestoqueitem.m71_codmatestoque
		                               WHERE matestoqueinimei.m82_matestoqueini = m80_codigo
		                                 and matestoqueitem.m71_codlanc = matestoqueinimei.m82_matestoqueitem
		                                 and matestoqueitem.m71_codmatestoque = matestoque.m70_codigo
		                                 and matestoque.m70_coddepto = {$coddepto}");

		$rsExcluirEstoque = db_query("DELETE FROM matestoqueinimeiari
		                               USING matestoqueinimei
		                                     INNER JOIN matestoqueitem ON matestoqueitem.m71_codlanc = matestoqueinimei.m82_matestoqueitem
		                                     INNER JOIN matestoque     ON matestoque.m70_codigo = matestoqueitem.m71_codmatestoque
		                               WHERE m49_codmatestoqueinimei = matestoqueinimei.m82_codigo
		                                 and matestoqueinimei.m82_matestoqueitem = matestoqueitem.m71_codlanc
		                                 and matestoqueitem.m71_codmatestoque = matestoque.m70_codigo
		                                 and matestoque.m70_coddepto = {$coddepto}");

		$rsExcluirEstoque = db_query("DELETE FROM matestoqueinimeimdi
		                               USING matestoqueinimei
		                                     INNER JOIN matestoqueitem ON matestoqueitem.m71_codlanc = matestoqueinimei.m82_matestoqueitem
		                                     INNER JOIN matestoque     ON matestoque.m70_codigo = matestoqueitem.m71_codmatestoque
		                               WHERE m50_codmatestoqueinimei = matestoqueinimei.m82_codigo
		                                 and matestoqueinimei.m82_matestoqueitem = matestoqueitem.m71_codlanc
		                                 and matestoqueitem.m71_codmatestoque = matestoque.m70_codigo
		                                 and matestoque.m70_coddepto = {$coddepto}");

		$rsExcluirEstoque = db_query("DELETE FROM matestoqueinimei
		                               USING matestoqueitem
		                                     INNER JOIN matestoque ON matestoque.m70_codigo = matestoqueitem.m71_codmatestoque
		                               WHERE matestoqueitem.m71_codlanc = m82_matestoqueitem
		                                 and matestoqueitem.m71_codmatestoque = matestoque.m70_codigo
		                                 and matestoque.m70_coddepto = {$coddepto}");

		$rsExcluirEstoque = db_query("DELETE FROM matestoqueitemnotafiscalmanual
		                               USING matestoqueitem
		                                     INNER JOIN matestoque ON matestoque.m70_codigo = matestoqueitem.m71_codmatestoque
		                               WHERE matestoqueitemnotafiscalmanual.m79_matestoqueitem = matestoqueitem.m71_codlanc
		                                 and matestoqueitem.m71_codmatestoque = matestoque.m70_codigo
		                                 and matestoque.m70_coddepto = {$coddepto}");

		$rsExcluirEstoque = db_query("DELETE FROM matestoqueitemnota
		                               USING matestoqueitem
		                                     INNER JOIN matestoque ON matestoque.m70_codigo = matestoqueitem.m71_codmatestoque
		                               WHERE matestoqueitemnota.m74_codmatestoqueitem = matestoqueitem.m71_codlanc
		                                 and matestoqueitem.m71_codmatestoque = matestoque.m70_codigo
		                                 and matestoque.m70_coddepto = {$coddepto}");

		$rsExcluirEstoque = db_query("DELETE FROM matestoqueitemoc
		                               USING matestoqueitem
		                                     INNER JOIN matestoque ON matestoque.m70_codigo = matestoqueitem.m71_codmatestoque
		                               WHERE matestoqueitemoc.m73_codmatestoqueitem = matestoqueitem.m71_codlanc
		                                 and matestoqueitem.m71_codmatestoque = matestoque.m70_codigo
		                                 and matestoque.m70_coddepto = {$coddepto}");

		$rsExcluirEstoque = db_query("DELETE FROM matestoqueitemunid
		                               USING matestoqueitem
		                                     INNER JOIN matestoque ON matestoque.m70_codigo = matestoqueitem.m71_codmatestoque
		                               WHERE matestoqueitemunid.m75_codmatestoqueitem = matestoqueitem.m71_codlanc
		                                 and matestoqueitem.m71_codmatestoque = matestoque.m70_codigo
		                                 and matestoque.m70_coddepto = {$coddepto}");

		$rsExcluirEstoque = db_query("DELETE FROM matestoqueitemlote
		                               USING matestoqueitem
		                                     INNER JOIN matestoque ON matestoque.m70_codigo = matestoqueitem.m71_codmatestoque
		                               WHERE m77_matestoqueitem = matestoqueitem.m71_codlanc
		                                 and matestoqueitem.m71_codmatestoque = matestoque.m70_codigo
		                                 and matestoque.m70_coddepto = {$coddepto}");

		$rsExcluirEstoque = db_query("DELETE FROM matestoqueitemfabric
		                               USING matestoqueitem
		                                     INNER JOIN matestoque ON matestoque.m70_codigo = matestoqueitem.m71_codmatestoque
		                               WHERE m78_matestoqueitem = matestoqueitem.m71_codlanc
		                                 and matestoqueitem.m71_codmatestoque = matestoque.m70_codigo
		                                 and matestoque.m70_coddepto = {$coddepto}");

		$rsExcluirEstoque = db_query("DELETE FROM far_retiradaitemlote
		                               USING matestoqueitem
		                                     INNER JOIN matestoque ON matestoque.m70_codigo = matestoqueitem.m71_codmatestoque
		                               WHERE fa09_i_matestoqueitem = matestoqueitem.m71_codlanc
		                                 and matestoqueitem.m71_codmatestoque = matestoque.m70_codigo
		                                 and matestoque.m70_coddepto = {$coddepto}");

		$rsExcluirEstoque = db_query("DELETE FROM empnotaitembenspendente
		                               USING matestoque, matestoqueitem
                                   WHERE e137_matestoqueitem = m71_codlanc
                                     and m71_codmatestoque = matestoque.m70_codigo
		                                 and matestoque.m70_coddepto = {$coddepto}");

		$rsExcluirEstoque = db_query("DELETE FROM matestoqueitem
		                               USING matestoque
		                               WHERE m71_codmatestoque = matestoque.m70_codigo
		                                 and matestoque.m70_coddepto = {$coddepto}");

		$rsExcluirEstoque = db_query("DELETE FROM matestoque WHERE m70_coddepto = {$coddepto}");

    $rsExcluirEstoque = db_query("ALTER TABLE matestoquedev           ENABLE TRIGGER ALL");
    $rsExcluirEstoque = db_query("ALTER TABLE matestoquedevitem       ENABLE TRIGGER ALL");
    $rsExcluirEstoque = db_query("ALTER TABLE atendrequi              ENABLE TRIGGER ALL");
    $rsExcluirEstoque = db_query("ALTER TABLE atendrequiitem          ENABLE TRIGGER ALL");
    $rsExcluirEstoque = db_query("ALTER TABLE matrequiitem            ENABLE TRIGGER ALL");
    $rsExcluirEstoque = db_query("ALTER TABLE matestoqueinimei        ENABLE TRIGGER ALL");
    $rsExcluirEstoque = db_query("ALTER TABLE matestoqueini           ENABLE TRIGGER ALL");
    $rsExcluirEstoque = db_query("ALTER TABLE empnota                 ENABLE TRIGGER ALL");
    $rsExcluirEstoque = db_query("ALTER TABLE matestoqueitemlote      ENABLE TRIGGER ALL");
    $rsExcluirEstoque = db_query("ALTER TABLE matestoqueitemfabric    ENABLE TRIGGER ALL");
    $rsExcluirEstoque = db_query("ALTER TABLE empnotaitembenspendente ENABLE TRIGGER ALL");

//		$result = db_query($sql) or die($sql);

		$sqlerro = false;
		$sMsg    = "Processo efetuado com sucesso";
    if (!$rsExcluirEstoque) {

      $sMsg    = "Não foi possível zerar o estoque. Contate o suporte";
      $sqlerro = true;
    }

		db_fim_transacao($sqlerro);

		$clmatestoque->erro_status="0";
		$clmatestoque->erro_msg = $sMsg;

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
<center>
<fieldset style="width: 600px">
<legend><b>Zerar Estoque por Departamento</b></legend>
	<?php
	include("forms/db_frmzeraestdepto.php");
	?>
</fieldset>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($excluir)){
	$clmatestoque->erro(true,true);
};
?>