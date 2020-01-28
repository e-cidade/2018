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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

$cliptucalc = new cl_iptucalc;
$cliptucalc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j23_anousu");

db_postmemory($HTTP_POST_VARS);

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script type="text/javascript">

      function js_emite(){
        vir="";
        listazona="";
        for(x=0;x<document.form1.ssel1.length;x++){
          listazona+=vir+document.form1.ssel1.options[x].value;
          vir=",";
        }

        oJanela = window.open( 'cad2_iptulancpago002.php?anousu='+document.form1.anousu.value+'&zona='+listazona+'&considerar='+document.form1.considerar.value,
                               '',
                               "width=" + (screen.availWidth - 5) + ",height=" + (screen.availHeight - 40) + ",scrollbars=1,location=0");
        oJanela.moveTo(0, 0);
      }
    </script>
  </head>
  <body class="body-default">
    <div class="container">
      <form name="form1" method="post" action="">
        <fieldset>
          <legend>IPTU - Lançamento e Pagamento</legend>
          <table>
            <tr>
              <td><b>Exercício:</b></td>
              <td>
              <select name="anousu" >
      			  	<?
      			  	$sqlano = "select distinct j23_anousu from iptucalc order by j23_anousu desc";
                      $resultano = db_query($sqlano);
                      $linhasano = pg_num_rows($resultano);
                      for($i = 0;$i < $linhasano;$i++){
      	              db_fieldsmemory($resultano,$i);
      	              echo "<option value=$j23_anousu>$j23_anousu</option>\n";
      	            }
      	            ?>
              </select>

              </td>
            </tr>
  					<tr>
  						<td><b>Considerar:</b></td>
  						<td>
  						 <?
  								$x = array("a"=>"Ambos","p"=>"Predial","t"=>"Territorial");
  								db_select("considerar",$x,false,2,"");
  							?>
  					  </td>
            </tr>
          </table>
          <fieldset class="separator">
            <legend>Zona Fiscal:</legend>
             	<?
    			  	$sqlzona    = "select * from zonas";
                    $resultzona = db_query($sqlzona);
                    db_multiploselect("j50_zona", "j50_descr", "nsel1", "ssel1", $resultzona, array(), 4, 250);
    	       ?>
          </fieldset>
        </fieldset>
        <input  name="emite2" id="emite2" type="button" value="Emitir Relatório" onclick="js_emite();" >
      </form>
    </div>
    <?php db_menu(); ?>
  </body>
</html>