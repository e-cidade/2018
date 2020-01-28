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

session_start();
include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
if(isset($voltar)){
	$redireciona = "opcoesdebitospendentes.php?".base64_encode("matricula=$j01_matric&cgc=$z01_cgccpf&cpf=$z01_cgccpf&opcao=m&id_usuario=$id_usuario");
    db_redireciona($redireciona);

}
$result = db_query("SELECT distinct m_publico,m_arquivo,m_descricao
                   FROM db_menupref
                   WHERE m_arquivo = 'digitamatricula.php'
                   ORDER BY m_descricao
                   ");
db_fieldsmemory($result,0);
if($m_publico != 't'){
  if(!session_is_registered("DB_acesso"))
  echo"<script>location.href='index.php?".base64_encode('erroscripts=3')."'</script>";
}
mens_help();
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
$cod_matricula = 0 + $matricula;
if ( !is_int($cod_matricula) or $cod_matricula == "" ){
   db_logs("$cod_matricula","",0,"Consulta Bic - Acesso com Matrícula Inválida : $cod_matricula");
   msgbox("Código Matrícula Inválido.");
   redireciona("index.php");
}

$sql="select proprietario.*,
             c.z01_nome  as promitente,
             c.z01_ender as ender_promitente,
             j.z01_nome  as imobiliaria,
             j.z01_ender as ender_imobiliaria,
             p.z01_ender as ender_propri
        from proprietario
             left join cgm c on j41_numcgm              = c.z01_numcgm
             left join cgm j on j44_numcgm              = j.z01_numcgm
             left join cgm p on proprietario.z01_numcgm = p.z01_numcgm
       where j01_matric = $cod_matricula";

$result = db_query($sql);
if (pg_numrows($result) == 0){
   msgbox("Matrícula não Cadastrada.");
   db_logs("$cod_matricula","",0,"Matricula naon Cadastrada. Numero: $cod_matricula");
   redireciona("index.php");
}
db_fieldsmemory($result,0);
db_logs("$cod_matricula","",0,"Consulta Bic : $cod_matricula");
if(!isset($DB_LOGADO) && $m_publico !='t' ){
  $sql = "select fc_permissaodbpref(".db_getsession("DB_login").",3,$cod_matricula)";
  $result = db_query($sql);
  if(pg_numrows($result)==0){
    db_redireciona("centro_pref.php?".base64_encode('erroscripts=Acesso a rotina inválido.'));
    exit;
  }
  $result = pg_result($result,0,0);
  if($result=="0"){
    db_redireciona("centro_pref.php?".base64_encode('erroscripts=Acesso a rotina inválido.'));
    exit;
  }
}

?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
js_verificapagina("opcoesdebitospendentes.php,opcoesitbi.php");

</script>
<style type="text/css">
<?db_estilosite();
echo"
.tabfonte {
               font-family: $w01_fontesite;
          font-size: $w01_tamfontesite;
          }
    ";

?>
.botao {
	background-color:#006699;
	border:1px outset #000066;
	color:#FFFFFF;
	font-family:Arial,Helvetica,sans-serif;
	font-size:10px;
	font-style:normal;
	font-weight:normal;
}
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
	<form name="form1" >
<input name="z01_cgccpf" type="hidden" value=<?=$z01_cgccpf?>>
<input name="id_usuario" type="hidden" value=<?=$id_usuario?>>
<?mens_div();?>
<input type="submit" value="< Voltar" style="background-color:#eaeaea" name="voltar">
<center>

<table width="100%" border="1" bordercolor="#eaeaea" cellpadding="0" cellspacing="0">
 <tr>
  <td class="tabfonte" valign="top" align="center">
   <strong style="font-size:15px">DADOS CADASTRAIS DO IM&Oacute;VEL</strong>
  </td>
 </tr>
 <tr>
  <td class="tabfonte" hvalign="top" align="center">

	<table width="100%" border="0" cellpadding="3" cellspacing="0" bordercolor="#cccccc"  class="tabfonte">
    <tr>
        <td width="10%"><b>Matr&iacute;cula:</b> </td>
        <td width="40%"><?=$j01_matric?> <input name="j01_matric" type="hidden" value=<?=$j01_matric?> <font id="calcd" class="tabfonte">&nbsp;</font> <script>
       document.getElementById("calcd").innerText = CalculaDV("<?=$j01_matric?>",11);
       </script>
	   </td>
        <td width="20%"><b>Refer&ecirc;ncia Anterior:</b>       </td>
        <td width="30%"> <?=$j40_refant?>   &nbsp;    </td>
    </tr>
    <tr>
        <td><b>Propriet&aacute;rio:</b></td>
        <td><?=$proprietario?> &nbsp;      </td>
        <td><b>Setor:  </b>     </td>
        <td><?=$j34_setor?> &nbsp;     </td>
    </tr>
	<tr>
        <td><b>Promitente:</b></td>
        <td><?=$promitente?>  &nbsp;    </td>
        <td><b>Quadra: </b>      </td>
        <td><?=$j34_quadra?> &nbsp;     </td>
    </tr>
	<tr>
        <td><b>mobili&aacute;ria:</b></td>
        <td><?=$imobiliaria?>&nbsp; </td>
        <td><b>Lote:</b>             </td>
        <td><?=$j34_lote?>&nbsp;    </td>
    </tr>
	<tr>
        <td><b>Logradouro:</b></td>
        <td colspan="3">
        <?=$codpri?> <?=$nomepri?><?=$j39_numero?>/<?=$j39_compl?>
		s</td>
    </tr>
	<tr>
        <td><b>&Aacute;rea Lote</b></td>
        <td><?=$j34_area?>&nbsp; </td>
        <td><b>Data Baixa:</b>            </td>
        <td><?=db_date($j01_baixa,"/")?>    </td>
    </tr>
</table>

</td>
    </tr>

        </table>
        <strong><br>
        CARACTER&Iacute;STICAS DO IM&Oacute;VEL</strong> <table width="100%" border="1"  cellpadding="0" cellspacing="0" bordercolor="#cccccc">
          <?
              $controle = 1;
            $result = db_query("select * from carlote,caracter,cargrup
                                   where j35_idbql = $j01_idbql and
                                                         j35_caract = j31_codigo and j31_grupo = j32_grupo");
                if( pg_numrows($result) != 0 ) {
                  for ($contador=0;$contador < pg_numrows($result);$contador ++ ){
                    db_fieldsmemory($result,$contador);
                    if ($controle == 1 ) {
                     ?>
                            <tr>
                              <?
                     if( $contador == 0 ) {
                           ?>
                              <?
                     } else {
                           ?>
                              <?
                         }
                         ?>
                              <td class="tabfonte" width="5%">
                                <?=$j35_caract?>
                              </td>
                              <td class="tabfonte" width="37%">
                                <?=ucfirst(substr($j32_descr,0,15))." - ".ucfirst(strtolower(substr($j31_descr,0,20)))?>
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
                                <?=ucfirst(substr($j32_descr,0,15))." - ".ucfirst(strtolower(substr($j31_descr,0,20)))?>
                              </td>
                            </tr>
                            <?
                   }
         }
       }
           ?>
                          </table>
                          <font size="2" face="Arial, Helvetica, sans-serif"><strong><br>
                          ISEN&Ccedil;&Otilde;ES</strong></font> <table width="100%" border="1"  cellpadding="0" cellspacing="0" bordercolor="#cccccc">
                            <?
           $result = db_query("select distinct iptuisen.*,tipoisen.* from iptuisen
                                                inner join isenexe on iptuisen.j46_codigo = isenexe.j47_codigo
                                                                                 ,tipoisen
                                   where j46_matric = $cod_matricula and j47_anousu >=
                                                   '".date("Y")."' and tipoisen.j45_tipo = iptuisen.j46_tipo ");
                if( pg_numrows($result) != 0 ) {
                  for($contador=0;$contador < pg_numrows($result);$contador ++ ){
                    db_fieldsmemory($result,$contador);
                    $result_lim = db_query("select j47_anousu
                                               from isenexe
                                               where j47_codigo = $j46_codigo order by  j47_anousu ");
                    $numrows = pg_numrows($result_lim);
                    db_fieldsmemory($result_lim,0);
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
                          TESTADA</strong></font><br> <table width="100%" border="1"  cellpadding="0" cellspacing="0" bordercolor="#cccccc">
                            <?
                $result = db_query("select * from testada,ruas
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
                          EDIFICA&Ccedil;&Otilde;ES( Constru&ccedil;&otilde;es
                          Lan&ccedil;adas )</strong></font> <strong>
                          <?php
                $sSqlConstrucoes = "select iptuconstr.*, carconstr.*, caracter.*, ruas.*
                                      from iptubase
                                           inner join iptuconstr on j01_matric = j39_matric
                                           inner join carconstr  on j39_matric = j48_matric
                                                                and j39_idcons = j48_idcons
                                           inner join caracter   on j48_caract = j31_codigo
                                           inner join ruas       on j39_codigo = j14_codigo
                                     where j39_matric = $cod_matricula
                                       and j39_dtdemo is null
                                    order by j39_idcons";
                $result = db_query($sSqlConstrucoes);
                $numero = 0;
                if( pg_numrows($result) != 0 ) {
                  for ($contador=0;$contador < pg_numrows($result);$contador ++ ){
                    db_fieldsmemory($result,$contador);
                      if( $numero != $j39_idcons ){
                          $confere = 0;
                          $impcar = 0;
                      $numero = $j39_idcons;
                  ?>
                          </strong> <table width="100%" border="1" bordercolor="#cccccc" cellpadding="0" cellspacing="0">
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
                          <table width="100%" border="1" bordercolor="#cccccc"  cellpadding="0" cellspacing="0">
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
          echo "<table width=\"100%\" border=\"1\"  cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#cccccc\">";
          echo "<tr>";
          echo " <td class=\"tabfonte\" align=\"center\">Imóvel Territorial</td>";
                  echo "</tr>";
          echo "</table>";
            }
                ?>
                          <br>
                          <font size="2" face="Arial, Helvetica, sans-serif">
                          OUTROS PROPRIET&Aacute;RIOS</font></strong><br> <table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#cccccc   ">
                            <?
                $result = db_query("select z01_nome,z01_ender from propri,cgm
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
                    </table>
        </td>
    </tr>
    <tr>
    <td class="tabfonte" align="center">
    <?
        db_query("begin");
    $sql = "select arq from db_imgsitbi where matricula = ".@$cod_matricula." order by data desc limit 1";
    $img = db_query($sql);
    if(pg_numrows($img) > 0) {
          $oid = pg_result($img,0,0);
      $DocHome = "http://".$_SERVER["SERVER_ADDR"].substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],"/"));
      //$DocRoot = substr($_SERVER['SCRIPT_FILENAME'],0,strrpos($_SERVER['SCRIPT_FILENAME'],"/"));
      $caminho = tempnam("tmp/","img");
      pg_loexport($oid,$caminho);
    ?>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
                        <td class="tabfonte" align="center"><strong>IMAGEM</strong><br>
          <font style="font-size:11px">(Clique na foto para ampliá-la)</font>
                </td>
      </tr>
      <tr align="center">
        <td>
                  <a title="Clique para ampliar a imagem" href="" onclick="window.open('listabicimovelpopup.php?src=<? echo base64_encode($DocHome."/tmp/".basename($caminho)) ?>','','width=640,height=480');return false">
                    <img width="250" height="250" src="<?=$DocHome."/tmp/".basename($caminho)?>" border="0">
                  </a>
            </td>
      </tr>
    </table>
     <?
        }
    db_query("end");
        ?>
    </td>
  </tr>
</table>
</form>
</center>