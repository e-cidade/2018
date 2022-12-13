<?
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_cissqn_classe.php"));
include(modification("dbforms/db_funcoes.php"));
db_postmemory($HTTP_POST_VARS);
$clcissqn = new cl_cissqn;
if (isset($calc)&&$calc!="false"){
	$query = "";
	if ($tipo==2&&isset($perc1)){
		$query = "&data1=$data1_ano-$data1_mes-$data1_dia&perc1=$perc1&data2=$data2_ano-$data2_mes-$data2_dia&perc2=$perc2&data3=$data3_ano-$data3_mes-$data3_dia&perc3=$perc3";
	}
	echo "<script>location.href='iss4_calcissgeral002.php?calcular=calcular&tipo=$tipo&anousu=$anousu$query';</script>";
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<center>
    <BR>
    <BR>
      <form name="form1" action="" method="post">
        <fieldset style="width: 400px">
        <legend><b>Cálculo Geral de ISSQN:</b></legend>
        <table  border="0" cellpadding="0" cellspacing="0">
            <tr>
            <td width="50" title="<?=$Tz01_nunmcgm?>">
              <strong>Tipo:</strong>
              </td>
            <td width="">
              <?
          		  $x   = array("2"=>"Fixo","3"=>"Variavel");
          		  db_select("tipo",$x,true,1,"onchange='js_mostradata();'");
          		  db_input("calc",10,"",true,"hidden");
          		?>
              </td>
            </tr>
            <tr>
              <td >
              <strong>Ano:</strong>
              </td>
              <td >
              <?
                $result = $clcissqn->sql_record($clcissqn->sql_query_file(null,"distinct q04_anousu,q04_anousu", " q04_anousu desc"));
                db_selectrecord("anousu", $result,true,0);
              ?>
              </td>
            </tr>
          </table>
          <? if ( !isset($tipo) || ( isset($tipo) && $tipo==2 ) ) { ?>
            <fieldset>
              <legend><strong>Dados Para Únicas: </strong></legend>
              <table>
                <tr>
                  <td><b>Venc. Unica:</b>
                  </td>
                  <td><b>Percentual:</b>
                  </td>
                </tr>
                <tr>
                  <td><?db_inputdata("data1","","","",true,"text",1);?>
                  </td>
                  <td><?db_input("perc1",10,"",true,"text",1);?>
                  </td>
                </tr>
                <tr>
                  <td><?db_inputdata("data2","","","",true,"text",1);?>
                  </td>
                  <td><?db_input("perc2",10,"",true,"text",1);?>
                  </td>
                </tr>
                <tr>
                  <td><?db_inputdata("data3","","","",true,"text",1);?>
                  </td>
                  <td><?db_input("perc3",10,"",true,"text",1);?>
                  </td>
                </tr>
              </table>
            </fieldset>
            <?
      	 	  }
      	 	?>
        </fieldset>
      
        <input name="calcular" type="button" id="calcular" value="Calcular" onclick='js_calcula();'>
      </form>
    
</center>
    <?
		  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
		?>
  </body>
</html>
    <script>
    function js_mostradata(){
      document.form1.calc.value = "false";
      document.form1.submit();
    }
    function js_calcula(){
      document.form1.calc.value = "true";
      document.form1.submit();
    }
    document.getElementById("anousudescr").style.display ="none";
    </script>