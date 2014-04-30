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
require_once("dbforms/db_funcoes.php");
list($iAno, $iMes, $iDia) = explode("-", date("Y-m-d", db_getsession("DB_datausu")));
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
<body bgcolor="#CCCCCC" leftmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" style="margin-top: 30px" >
<center>
  <form id="form1">
    <fieldset style="width: 400px;">
      <legend><b>Transferências em Aberto</b></legend>
      <table style="width: 100%">
        <tr>
          <td><b>Data Inicial:</b></td>
          <td>
            <?php
              db_inputdata("dtInicial", $iDia, $iMes, $iAno, true, "text", 1);
            ?>
          </td>
          <td><b>Data Final:</b></td>
          <td>
            <?php
              db_inputdata("dtFinal", "", "", "", true, "text", 1);
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <p>
      <input type="button" id="btnImprimir" value="Emitir Relatório" />
    </p>
  </form>

</center>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>

<script>

  $('btnImprimir').observe('click', js_emiteRelatorio);

	function js_emiteRelatorio() {

    var dtInicial = $F('dtInicial');
    var dtFinal   = $F('dtFinal');

    if (dtInicial == "" && dtFinal == "") {

      alert("É necessário informar uma data inicial ou data final.");
      return false;
    }

    var sQueryString = "mat2_transferenciasaberto002.php?dtInicial="+dtInicial+"&dtFinal="+dtFinal;
    var oJanela = window.open(sQueryString,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    oJanela.moveTo(0,0);

	}
</script>