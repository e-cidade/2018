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
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_proced_classe.php"));
require_once(modification('libs/db_utils.php'));
require_once(modification("libs/db_libpostgres.php"));

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_SERVER_VARS);

$clpostgresqlutils = new PostgreSQLUtils;
$clproced          = new cl_proced;
$clrotulo          = new rotulocampo;
$clrotulo->label('DBtxt10');
$clrotulo->label('DBtxt11');
$clrotulo->label('k60_codigo');
$clrotulo->label('k60_descr');
$clrotulo->label('k51_procede');
$clrotulo->label('k51_descr');
$clrotulo->label('v03_descr');

$instit     = db_getsession("DB_instit");
if (count($clpostgresqlutils->getTableIndexes('debitos')) == 0) {

  db_msgbox("Problema nos índices da tabela débitos. Entre em contato com CPD.");
  $db_botao = false;
  $db_opcao = 3;
} else {

  $db_botao = true;
  $db_opcao = 4;
}
?>
  <html>
    <head>
      <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
      <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
      <meta http-equiv="Expires" CONTENT="0">
      <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
      <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
      <script>
        function js_marca() {
          var ID = document.getElementById('marca');
          //var BT = document.getElementById('btmarca');
          if(!ID)
            return false;
            var F = document.form1;
            if(ID.innerHTML == 'D') {
              var dis = false;
              ID.innerHTML = 'M';
            } else {
              var dis = true;
              ID.innerHTML = 'D';
            }
            for(i = 0;i < F.elements.length;i++) {
              if(F.elements[i].type == "checkbox"){
                F.elements[i].checked = dis;
              }
            }
            js_verifica();
          }
          function js_verifica(){
            var marcas = false;
            var F = document.form1;
            for(i = 0;i < F.elements.length;i++) {
              if(F.elements[i].type == "checkbox" &&  F.elements[i].checked ){
                marcas = true;
              }
            }
          }
          function js_emite(tipo){
            var exerc = '';
            var xvirg = '';
            var F = document.form1;
            for(i = 0;i < F.elements.length;i++) {
              if(F.elements[i].type == "checkbox" &&  F.elements[i].checked ){
                exerc += xvirg+F.elements[i].value;
                xvirg  = '-';
              }
            }
            if (exerc != ''){
               exerc = 'exerc='+exerc;
            }
            jan = window.open('div2_exercproced002.php?xdata='+document.form1.xdata.value+
                              '&analitico='+'&procedencias='+document.form1.v03_descr.value+
                              '&tiporel='+document.form1.tiporel.value+
                              '&'+exerc+
                              '&tipo='+tipo,
                              '',
                              'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
            jan.moveTo(0,0);
          }
      </script>
      <link href="estilos.css" rel="stylesheet" type="text/css">
    </head>
    <body bgcolor=#CCCCCC onLoad="a=1">
      <form class="container"  name="form1" method="post" action="" >
        <fieldset>
          <legend>Posição de Dívida Exercício/Procedência</legend>
          <table  class="form-container" >
            <tr>
              <td>Data do calculo:</td>
		  	<td>
		  	  <?php
            $arrayDatas = array();
            $sqlDataDebitos  = "select distinct k115_data from datadebitos where k115_instit = $instit order by k115_data desc";
            $rsDataDebitos   = db_query($sqlDataDebitos);
            $intDatas        = pg_num_rows($rsDataDebitos);

            if ($intDatas > 0) {
    		  		for($i=0;$i<$intDatas;$i++){
                db_fieldsmemory($rsDataDebitos,$i);
    		  		  $arrayDatas[$k115_data] = db_formatar($k115_data,'d');
    		  		}
              db_fieldsmemory($rsDataDebitos,0);
            }

		    		db_select("xdata",$arrayDatas,true,$db_opcao,"");
		  	  ?>
		      </td>
		    </tr>
            <tr>
              <td>Tipo:</td>
              <td>
                <?
           		  $matriz = array("r"=>"Resumido",
		                          "c"=>"Completo",
		                         );
		          db_select("tiporel", $matriz,true,"");
           	    ?>
          	</td>
            </tr>
	        <tr>
              <td nowrap title="Lista de procedencias separadas por vírgula" >
                Lista de procedencias (separadas por vírgula):
              </td>
              <td>
                <?
                  db_input('v03_descr', 100, $Iv03_descr, true, 'text', $db_opcao);
                ?>
              </td>
		    </tr>
          </table>

		  <br><br>

          <fieldset class="separator">
         	<legend>Exercícios</legend>
            <table  align="center" border="0" cellspacing="1" >
              <tr height="20" bgcolor="#FFCC66">
                <th class="borda" align="center" style="font-size:12px" nowrap>
                  <a id="marca" href="#" style="color:black" onclick="js_marca();return false">D</a>
                </th>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
		      <?
                $cor = '#E4F471';
                $sqlmenor = "select distinct v01_exerc as exercicio from divida order by v01_exerc";
                $resultmenor = db_query($sqlmenor) or die($sqlmenor);
                $cont=0;
                for($x = 0;$x < pg_num_rows($resultmenor);$x++){
                  db_fieldsmemory($resultmenor,$x);
                  if ($cont==0){
                  	echo "<tr style='cursor: hand; height: 20px' bgcolor='$cor'>";
                  	if ($cor == '#E4F471'){
                       $cor = '#EFE029';
                    }elseif ($cor == '#EFE029'){
                       $cor = '#E4F471';
                    }
                  }
                  $cont++;
              ?>
                <td height="20px" width="25%" class="borda" style="font-size:11px" align="center" id="check<?=$i?>" nowrap>
                  <input type="checkbox" value="<?=$exercicio?>"
                  name="check<?=$i?>" checked onclick="js_verifica()"><?=$exercicio?>
                </td>
              <?
                if ($cont==4){
                	echo "</tr>";
                  	$cont=0;
                }
                }
              ?>
            </table>
          </fieldset>
        </fieldset>
        <input name="db_opcao" type="button" id="db_opcao" value="Imprimir" onClick="js_emite(1);"
        <?=($db_botao ? '' : 'disabled')?>>
        <input name="db_opcao" type="button" id="db_opcao" value="Gerar TXT" onClick="js_emite(2);"
        <?=($db_botao ? '' : 'disabled')?>>
      </form>
      <?
       db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
      ?>
    </body>
  </html>
<script>

$("xdata").setAttribute("rel","ignore-css");
$("xdata").addClassName("field-size3");
$("tiporel").setAttribute("rel","ignore-css");
$("tiporel").addClassName("field-size3");
$("v03_descr").addClassName("field-size9");

</script>
