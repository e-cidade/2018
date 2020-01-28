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
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$result = pg_exec("select proprietario.* , c.z01_nome as promitente, c.z01_ender as ender_promitente,j.z01_nome as imobiliaria,j.z01_ender as ender_imobiliaria
                   from proprietario
                        left outer join cgm c on j41_numcgm = c.z01_numcgm              
                        left outer join cgm j on j44_numcgm = j.z01_numcgm              
 			       where j01_matric = $cod_matricula");

if (pg_numrows($result) == 0){
   msgbox("Matrícula não Cadastrada.");
}
db_fieldsmemory($result,0);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<style type="text/css">
<!--
.tabcols {
  font-size:11px;
}
.tabcols1 {
  text-align: right;
  font-size:11px;
}
.btcols {
	height: 17px;
	font-size:10px;
}
.links {
	font-weight: bold;
	color: #0033FF;
	text-decoration: none;
	font-size:10px;
}
a.links:hover {
    color:black;
	text-decoration: underline;
}
.links2 {
	font-weight: bold;
	color: #0587CD;
	text-decoration: none;
	font-size:10px;
}
a.links2:hover {
    color:black;
	text-decoration: underline;
}
.nome {
  color:black;  
}
a.nome:hover {
  color:blue;
}
-->
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr bordercolor="#000000"> 
    <td class="tabfonte" valign="top" align="center"> <strong style="font-size:15px">DADOS 
      CADASTRAIS DO IM&Oacute;VEL</strong> </td>
  </tr>
  <tr bordercolor="#000000"> 
    <td class="tabfonte" hvalign="top" align="center"> <table width="100%"  border="0" cellpadding="3" cellspacing="0" bordercolor="#000000">
        <tr> 
          <td class="tabfonte" width="11%"> <strong>Matr&iacute;cula:</strong> 
          </td>
          <td class="tabfonte" > &nbsp; 
            <?=$j01_matric?>
            - <font id="calcd" class="tabfonte">&nbsp;</font> <script>
              document.getElementById("calcd").innerText = CalculaDV("<?=$j01_matric?>",11);
              </script> </td>
          <td class="tabfonte" width="25%" colspan="-1" align="right"> <strong>Refer&ecirc;ncia 
            Anterior: </strong></td>
          <td class="tabfonte" width="39%"> &nbsp; 
            <?=$j40_refant?>
          </td>
        </tr>
        <tr> 
          <td class="tabfonte" width="11%" height="22"> <strong>Propriet&aacute;rio:</strong> 
          </td>
          <td class="tabfonte" height="22" colspan="4"> <table class="tabfonte" width="100%">
              <tr> 
                <td class="tabfonte" width="47%"> 
                  <?=$z01_nome?>
                </td>
                <td class="tabfonte" width="53%"><strong>Endereco:&nbsp; 
                  <?=substr($z01_ender,0,30)?>
                  </strong></td>
              </tr>
            </table></td>
          <td class="tabfonte" height="22">&nbsp;</td>
        </tr>
        <tr> 
          <td class="tabfonte" height="22"> <strong>Promitente:</strong> </td>
          <td class="tabfonte" height="22" colspan="5"> <table class="tabfonte" width="100%">
              <tr> 
                <td class="tabfonte" width="46%"> 
                  <?=$promitente?>
                </td>
                <td class="tabfonte" width="54%"><strong>Endereco:&nbsp; 
                  <?=substr($ender_promitente,0,30)?>
                  </strong></td>
              </tr>
            </table></td>
        </tr>
        <tr> 
          <td width="11%" height="32" class="tabfonte"> <strong>Imobili&aacute;ria:</strong> 
          </td>
          <td class="tabfonte" colspan="5"> <table class="tabfonte" width="100%">
              <tr> 
                <td class="tabfonte" width="46%"> 
                  <?=$imobiliaria?>
                </td>
                <td class="tabfonte" width="54%"><strong>Endereco:&nbsp; 
                  <?=substr($ender_imobiliaria,0,30)?>
                  </strong></td>
              </tr>
            </table></td>
        </tr>
        <tr> 
          <td class="tabfonte" colspan="6"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td class="tabfonte" width="11%"> <strong>Setor:</strong> </td>
                <td class="tabfonte" width="20%" align="center"> 
                  <?=$j34_setor?>
                </td>
                <td class="tabfonte" width="16%" align="right"> <strong>Quadra</strong>: 
                </td>
                <td class="tabfonte" width="16%" align="center"> 
                  <?=$j34_quadra?>
                </td>
                <td class="tabfonte" width="16%" align="right"> <strong>Lote:</strong> 
                </td>
                <td class="tabfonte" width="21%" align="center"> 
                  <?=$j34_lote?>
                </td>
              </tr>
            </table></td>
        </tr>
        <tr align="center"> 
          <td class="tabfonte" colspan="6" height="22"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td class="tabfonte" width="11%"> <strong>Logradouro:</strong> 
                </td>
                <td class="tabfonte" width="11%" align="center"> 
                  <?=$codpri?>
                </td>
                <td class="tabfonte" width="51%" align="center"> 
                  <?=$nomepri?>
                </td>
                <td class="tabfonte" width="27%" align="center"> 
                  <?=$j39_numero?>
                  / 
                  <?=$j39_compl?>
                </td>
              </tr>
            </table></td>
        </tr>
        <tr align="center"> 
          <td class="tabfonte" colspan="6" height="22">&nbsp; </td>
        </tr>
        <tr align="center"> 
          <td class="tabfonte" colspan="6" > <table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
              <tr> 
                <td width="11%" nowrap class="tabfonte"> <strong>&Aacute;rea Lote:</strong> 
                </td>
                <td class="tabfonte" width="20%" align="center"> &nbsp; 
                  <?=$j34_area?>
                </td>
                <td width="14%" align="right" nowrap class="tabfonte"> <strong>Data 
                  Baixa:</strong> </td>
                <td class="tabfonte" width="12%" align="center"> 
                  <?=db_date($j01_baixa,"/")?>
                </td>
                <td class="tabfonte" width="23%" align="right"> <strong>Processo:</strong> 
                </td>
                <td class="tabfonte" width="20%" align="center"> 
                  <?=0?>
                </td>
              </tr>
              <!--tr> 
                              <td class="tabfonte" colspan="6" align="center"> 
                                <strong>VALOR VENAL DO IM&Oacute;VEL</strong></td>
                            </tr>
                            <tr> 
                              <td class="tabfonte" width="11%" height="50"> <strong>Terreno: 
                                </strong></td>
                              <td class="tabfonte" width="20%" height="50" align="center"> 
                                <?=number_format(0,2,",",".")?>
                              </td>
                              <td class="tabfonte" width="14%" height="50"> <strong>Edifica&ccedil;&otilde;es:</strong> 
                              </td>
                              <td class="tabfonte" width="12%" height="50" align="center"> 
                                <?=0?>
                                &nbsp; </td>
                              <td class="tabfonte" width="23%" height="50" align="right"> 
                                <strong>Total:</strong> </td>
                              <td class="tabfonte" width="20%" height="50" align="center"> 
                                <?=number_format((0+0),2,",",".")?>
                              </td>
                            </tr-->
            </table>
            <strong><br>
            CARACTER&Iacute;STICAS DO IM&Oacute;VEL</strong> <table width="100%" border="1"  cellpadding="0" cellspacing="0" bordercolor="#000000">
              <?
  	    $controle = 1;
	    $result = pg_exec("select * from carlote,caracter 
		                   where j35_idbql = $j01_idbql and
						         j35_caract = j31_codigo");
		if( pg_numrows($result) != 0 ) {
		  for ($contador=0;$contador < pg_numrows($result);$contador ++ ){
		    db_fieldsmemory($result,$contador);
		    if ($controle == 1 ) {
		     ?>
              <tr> 
                <?
		     if( $contador == 0 ) {
			   ?>
                <td class="tabfonte" width="20%"><strong>Caracter&iacute;sticas:</strong></td>
                <?
		     } else {
			   ?>
                <td class="tabfonte" width="20%">&nbsp;</td>
                <?
			 }
			 ?>
                <td class="tabfonte" width="5%"> 
                  <?=$j35_caract?>
                </td>
                <td class="tabfonte" width="37%"> 
                  <?=substr($j31_descr,0,20)?>
                </td>
                <?
			 $controle = 2;
		   } else {
		     $controle = 1;
			 ?>
                <td class="tabfonte" width="4%"> 
                  <?=$j35_caract?>
                </td>
                <td class="tabfonte" width="34%"> 
                  <?=substr($j31_descr,0,20)?>
                </td>
              </tr>
              <?
		   }
         }
       }
	   ?>
            </table>
            <font size="2" face="Arial, Helvetica, sans-serif"><strong><br>
            ISEN&Ccedil;&Otilde;ES</strong></font> <table width="100%" border="1"  cellpadding="0" cellspacing="0" bordercolor="#000000">
              <?
	   $result = pg_exec("select distinct iptuisen.*,tipoisen.* from iptuisen
		                                inner join isenexe on iptuisen.j46_codigo = isenexe.j47_codigo
										 ,tipoisen 
		                   where j46_matric = $cod_matricula and j47_anousu >= 
						   '".date("Y")."' and tipoisen.j45_tipo = iptuisen.j46_tipo ");
		if( pg_numrows($result) != 0 ) {
		  for ($contador=0;$contador < pg_numrows($result);$contador ++ ){
		    db_fieldsmemory($result,$contador);
	        $result_lim = pg_exec("select j47_anousu
			                       from isenexe 
		                           where j47_codigo = $j46_codigo order by  j47_anousu ");
		    $numrows = pg_numrows($result_lim);
			db_fieldsmemory($result_lim,1);
			$anoini = $j47_anousu;
			db_fieldsmemory($result_lim,$numrows-1);
			$anofim = $j47_anousu;
		    ?>
              <tr> 
                <td class="tabfonte" width="8%"><strong>Validade:</strong></td>
                <td class="tabfonte" width="12%">&nbsp; 
                  <?=$anoini?>
                  - 
                  <?=$anofim?>
                </td>
                <td class="tabfonte" width="7%"><strong>Tipo:</strong></td>
                <td class="tabfonte" width="31%"> 
                  <?=substr($j46_hist,0,20)?>
                </td>
                <td class="tabfonte" width="8%"><strong>Motivo:</strong></td>
                <td class="tabfonte" width="34%"> 
                  <?=substr($j45_descr,0,20)?>
                </td>
              </tr>
              <?
	      }
        } else {
		  ?>
              <tr> 
                <td width="8%" align="center" nowrap class="tabfonte"><strong>Sem 
                  Isenções</strong></td>
              </tr>
              <?
	    }
		?>
            </table>
            <font size="2" face="Arial, Helvetica, sans-serif"><strong><br>
            TESTADA</strong></font><br> <table width="100%" border="1"  cellpadding="0" cellspacing="0" bordercolor="#000000">
              <?
		$result = pg_exec("select * from testada,ruas 
		                   where j36_idbql  = $j01_idbql  and
								 j36_codigo = j14_codigo");
		if( pg_numrows($result) != 0 ) {
		  for ($contador=0;$contador < pg_numrows($result);$contador ++ ){
		    db_fieldsmemory($result,$contador);
		    ?>
              <tr> 
                <td class="tabfonte" width="8%">Rua:</td>
                <td class="tabfonte" width="53%"><? echo $j36_codigo." - ".substr($j14_nome,0,20)?></td>
                <td class="tabfonte" width="13%"><strong>Face:</strong></td>
                <td class="tabfonte" width="8%"> 
                  <?=$j36_face?>
                </td>
                <td class="tabfonte" width="10%"><strong>Testada</strong></td>
                <td class="tabfonte" width="8%"> 
                  <?=$j36_testad?>
                </td>
              </tr>
              <?
	       }
	     } else {
           echo "<tr>";
           echo " <td class=\"tabfonte\" align=\"center\">Sem Registro de Testadas</td>";
		   echo "</tr>";
	     }
		?>
            </table>
            <font size="2" face="Arial, Helvetica, sans-serif"><strong><br>
            EDIFICA&Ccedil;&Otilde;ES( Constru&ccedil;&otilde;es Lan&ccedil;adas 
            )</strong></font> <strong> 
            <?
		$result = pg_exec("select * from iptuconstr,carconstr,caracter,ruas
		                   where j39_matric = $cod_matricula and
						   j39_matric = j48_matric and
						   j39_idcons = j48_idcons and
						   j48_caract = j31_codigo and
						   j39_codigo = j14_codigo
						   order by j39_idcons ");
		$numero = 0;
		if( pg_numrows($result) != 0 ) {
		  for ($contador=0;$contador < pg_numrows($result);$contador ++ ){
		    db_fieldsmemory($result,$contador);
  		    if( $numero != $j39_idcons ){
			  $confere = 0;
			  $impcar = 0;
		      $numero = $j39_idcons;
	          ?>
            </strong> <table width="100%" border="1" bordercolor="#000000"  cellpadding="0" cellspacing="0">
              <tr> 
                <td class="tabfonte" width="21%"><strong>Constru&ccedil;&atilde;o:</strong> 
                  <?=$j39_idcons?>
                </td>
                <td class="tabfonte" width="6%" ><strong>&Aacute;rea:</strong></td>
                <td class="tabfonte" width="13%" > 
                  <?=$j39_area?>
                </td>
                <td class="tabfonte" width="25%" ><strong>Ano Constru&ccedil;&atilde;o:</strong> 
                  <?=$j39_ano?>
                </td>
                <td class="tabfonte" width="35%" > <strong>Frente:</strong> 
                  <?=$j14_nome?>
                  <?=$j39_numero?>
                  <?=$j39_compl?>
                </td>
              </tr>
            </table>
            <table width="100%" border="1" bordercolor="#000000"  cellpadding="0" cellspacing="0">
              <?
		    }
			if ( $confere == 0 ){
			  $confere = 1;
			  ?>
              <tr> 
                <?
			  if( $impcar == 0 ){ 
			    $impcar = 1;
				?>
                <td class="tabfonte" width="15%" height="29"> <strong>Caracter&iacute;sticas:</strong></td>
                <?
			  } else {  
			    ?>
                <td class="tabfonte" width="15%" height="29">&nbsp;</td>
                <?
		      }
			  ?>
                <td class="tabfonte" width="3%" height="29"> 
                  <?=$j48_caract?>
                </td>
                <td class="tabfonte" width="36%" height="29"> 
                  <?=substr($j31_descr,0,20)?>
                </td>
                <?
			  } else {
		        $confere = 0;
		      ?>
                <td class="tabfonte" width="3%" height="29"> 
                  <?=$j48_caract?>
                </td>
                <td class="tabfonte" width="43%" height="29"> 
                  <?=substr($j31_descr,0,20)?>
                </td>
              </tr>
              <?
  	        }			
		  }
		  ?>
            </table>
            <strong> 
            <?
		} else {
          echo "<table width=\"100%\" border=\"1\"  cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">";
          echo "<tr>";
          echo " <td class=\"tabfonte\" align=\"center\">Imóvel Territorial</td>";
		  echo "</tr>";
          echo "</table>";
	    }
		?>
            <br>
            <font size="2" face="Arial, Helvetica, sans-serif"> OUTROS PROPRIET&Aacute;RIOS</font></strong><br> 
            <table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
              <?
		$result = pg_exec("select z01_nome,z01_ender from propri,cgm
		                   where j42_matric = $cod_matricula and
						   j42_numcgm = z01_numcgm");
		if( pg_numrows($result) != 0 ) {
		  for ($contador=0;$contador < pg_numrows($result);$contador ++ ){
		    db_fieldsmemory($result,$contador);
		    ?>
              <tr> 
                <td class="tabfonte" width="9%"><strong>Nome:</strong></td>
                <td class="tabfonte" width="46%">&nbsp; 
                  <?=$z01_nome?>
                </td>
                <td class="tabfonte" width="45%">&nbsp; 
                  <?=$z01_ender?>
                </td>
              </tr>
              <?
		  }
		} else {
          echo "<tr>";
          echo " <td class=\"tabfonte\" align=\"center\">Sem Outros Proprietários</td>";
		  echo "</tr>";
		}
		?>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr bordercolor="#000000"> 
    <td class="tabfonte" align="center"> 
      <?
	pg_exec("begin");			
    $img = pg_exec("select arq from db_imgsitbi where trim(matricula) = trim('$j11_matric') order by data desc limit 1");
    if(pg_numrows($img) > 0) {
	  $oid = pg_result($img,0,0);
	  $DocHome = "http://".$_SERVER["SERVER_ADDR"].substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],"/"));
      $DocRoot = substr($_SERVER['SCRIPT_FILENAME'],0,strrpos($_SERVER['SCRIPT_FILENAME'],"/"));
      $caminho = tempnam($DocRoot."/tmp/","img");
	  pg_loexport($oid,$caminho);			  
	?>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr> 
          <td class="tabfonte" align="center"><strong>IMAGEM</strong><br> <font style="font-size:11px">(Clique 
            na foto para ampliá-la)</font> </td>
        </tr>
        <tr align="center"> 
          <td> <a title="Clique para ampliar a imagem" href="" onclick="window.open('listabicimovelpopup.php?src=<? echo base64_encode($DocHome."/tmp/".basename($caminho)) ?>','','width=640,height=480');return false"> 
            <img width="250" height="250" src="<?=$DocHome."/tmp/".basename($caminho)?>" border="0"> 
            </a> </td>
        </tr>
      </table>
      <?
	}
    pg_exec("end");
	?>
    </td>
  </tr>
  <tr>
    <td height="450" align="left" valign="top" bgcolor="#CCCCCC"> <center>
        <form name="form1" method="post">
        </form>
      </center>
    </td>
  </tr>
</table>
<? 
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_execute() {
   alert('ok');
   document.getElementById("consulta").style.visibility = 'visible';
   consulta.location.href = "libs/db_browse.php?query=<?=$sql?>&numlinhas=13&arquivo=&filtro=%&aonde=_self&mensagem=Clique Aqui&NomeForm=NoMe";
}
</script>