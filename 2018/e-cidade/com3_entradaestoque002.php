<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
require_once("classes/db_empelemento_classe.php");
require_once("classes/db_conplanoreduz_classe.php");
require_once("dbforms/db_funcoes.php");


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<link href="estilos/tab.style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/classes/infoLancamentoContabil.classe.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC>
<center>
<fieldset>
  <legend>Entradas</legend>

   <div id ='ctmentrada'>

  </div>
</fieldset>

</center>
</body>
</html>
<script>

var oGet = js_urlToObject();

var sUrlRPC = 'com4_ordemdecompra001.RPC.php';
js_gridEntrada();

 function js_gridEntrada() {

  oGridEntrada = new DBGrid('Entradas');
  oGridEntrada.nameInstance = 'oGridEntrada';
  //oGridInteressados.setCheckbox(0);
  //oGridItens.allowSelectColumns(true);
  oGridEntrada.setCellWidth(new Array('50px', 
		                                  '180px',
                                      '70px' ,
                                      '80px' ,
                                      '70px' ,
                                      '180px',
                                      '180px'
                                     ));

  oGridEntrada.setCellAlign(new Array('right',
		                                'left' ,
                                    'right' ,
                                    'right' ,
                                    'right' ,
                                    'left' ,
                                    'left'
                                    ));


  oGridEntrada.setHeader(new Array( 'Cód.Mat.',
		                                'Material',
                                    'Quantidade',
                                    'Quantidade Entrada',
                                    'Valor',
                                    'Almoxarifado',
                                    'Tipo de Movimentação'
                                    ));


  oGridEntrada.setHeight(180);
  oGridEntrada.show($('ctmentrada'));
  oGridEntrada.clearAll(true);

  js_getItensEntrada();

}


 function js_getItensEntrada() {

   var oParametros              = new Object();
       oParametros.exec         = 'getEntradas';
       oParametros.iOrdemCompra = oGet.m51_codordem;

   var msgDiv = "Pesquisando itens de entrada. \nAguarde ...";

       js_divCarregando(msgDiv,'msgBox');

   new Ajax.Request(sUrlRPC,
                   {method: "post",
                    parameters:'json='+Object.toJSON(oParametros),
                    onComplete: js_retornoItensEntrada
                   });
 }

 function js_retornoItensEntrada(oAjax){

   js_removeObj('msgBox');

   var oRetorno = eval("("+oAjax.responseText+")");


   oGridEntrada.clearAll(true);

   oRetorno.aEntradas.each(

     function (oDado, iInd) {

       var aRow     = new Array();
           aRow[0]  = oDado.iMaterial;
           aRow[1]  = oDado.sMaterial.urlDecode()     ;
           aRow[2]  = oDado.iQuantidade               ;
           aRow[3]  = oDado.iQuantidadeEntrada        ;
           aRow[4]  = oDado.iValor                    ;
           aRow[5]  = oDado.sAlmoxarifado.urlDecode() ;
           aRow[6]  = oDado.sTipoMovimentacao.urlDecode() ;
           oGridEntrada.addRow(aRow);

   });
   oGridEntrada.renderRows();

 }
</script>