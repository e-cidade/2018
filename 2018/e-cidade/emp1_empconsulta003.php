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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_empautoriza_classe.php"));

db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);

$clempautoriza = new cl_empautoriza;
$sWhere = " and e54_instit = " . db_getsession("DB_instit");
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table border="0" align="center" cellspacing="0" bgcolor="#CCCCCC">
  <!--- filtro --->
  <form name=form1 action="" method=POST>
    <tr>
      <td valign=top>
        <table border=0 align=center>
          <tr>
            <td align="center">
              <input name="Fechar" type="button" id="fechar" value="Fechar"
                     onClick="parent.db_iframe_empconsulta003.hide();">
            </td>
          </tr>

          <tr>
            <td align="center" nowrap wrap="false">
              Período:
                <? db_inputdata('dt1', @$dia, @$mes, @$ano, true, 'text', 1, "");
                echo " a ";
                db_inputdata('dt2', @$dia, @$mes, @$ano, true, 'text', 1, "");
                ?>
            </td>
          </tr>
          <tr>
            <td align=center><input type=submit value=Filtrar></td>
          </tr>
  </form>
</table>
</td>
</tr>
<!---  end filtro --->
<tr>
  <td align="center" valign="top">
      <?php
      //---
      $data1 = 0;
      $data2 = 0;
      @$data1 = $dt1;
      @$data2 = $dt2;

      if (strlen($data1) < 3) {
          unset($data1);
      }

      if (strlen($data2) < 3) {
          unset($data2);
      }
      //--

      $campos = "distinct(e54_autori),e54_emiss,e54_anulad,e54_numcgm,z01_nome, e54_instit";
      $sql = "";

      if (isset($e54_autori) and $e54_autori != "") {
          $sql = $clempautoriza->sql_query(null, $campos, null, "e54_autori = $e54_autori {$sWhere}");
          $rsAutorizacao = $clempautoriza->sql_record($sql);

          if ($clempautoriza->numrows == 0) {
              db_msgbox("Autorização {$e54_autori} não encontrada.");
              exit;
          }
          ?>
        <script>
          location.href = 'func_empempenhoaut001.php?e54_autori=<?=$e54_autori ?>';
        </script>
          <?php
          exit;
      } else {
          if (isset($o58_coddot) and $o58_coddot != "") {
              $sql = $clempautoriza->sql_query(null,
                $campos,
                null,
                "e54_autori in ( select e56_autori
                                         from empautidot
                                        where e56_coddot = $o58_coddot
                                      order by e56_autori )
                        {$sWhere} ");
              if (isset($data1) and isset($data2)) {
                  $sql = $clempautoriza->sql_query(
                    null,
                    $campos,
                    null,
                    "e54_autori in
            ( select e56_autori
            from empautidot
            where e56_coddot = $o58_coddot
            order by e56_autori
            )
            and e54_anousu=" . db_getsession("DB_anousu") . " and
            e54_emiss between '$data1' and '$data2' and {$sWhere}");
              }
          } else {
              if (isset($pc01_codmater) and $pc01_codmater != "") {

                  $sql = $clempautoriza->sql_query_itemmaterial($pc01_codmater, $campos, null);
                  if (isset($data1) and isset($data2)) {

                      $sql = $clempautoriza->sql_query_itemmaterial(
                        null,
                        $campos,
                        null,
                        " e55_item=" . $pc01_codmater . " and  e54_anousu=" . db_getsession("DB_anousu") . " and (e54_emiss between '$data1' and '$data2' )  {$sWhere}");
                  } else {

                      $sql = $clempautoriza->sql_query_itemmaterial(
                        null,
                        $campos,
                        null,
                        "e55_item=" . $pc01_codmater . " and  e54_anousu=" . db_getsession("DB_anousu") . "   {$sWhere}");
                  }
              } else {
                  if (isset($z01_numcgm) and $z01_numcgm != "") {

                      $sql = $clempautoriza->sql_query(null, $campos, null, "e54_numcgm = $z01_numcgm  {$sWhere}");

                      if (isset($data1) and isset($data2)) {

                          $sql = $clempautoriza->sql_query(
                            null,
                            $campos,
                            null,
                            " e54_numcgm = $z01_numcgm and  (e54_emiss between '$data1' and '$data2')  {$sWhere}");
                      } else {

                          $sql = $clempautoriza->sql_query(null, $campos, null, " e54_numcgm = $z01_numcgm {$sWhere}");
                      }
                  } else {
                      if (isset($dt1) and $dt1 != "") {

                          if (isset($dt2) and $dt2 != "") {
                              $sql = $clempautoriza->sql_query(null, $campos, null,
                                "e54_emiss between '$dt1' and '$dt2' {$sWhere}");
                          } else {
                              db_msgbox("Data inválida !");
                          }

                      }
                  }
              }
          }
      }
      // Se não foram informados filtros não faz a busca
      if (!empty($sql)) {

          $sql = "select e54_autori,
                       e54_emiss,
                 e54_anulad,
                 sum(e55_vltot) as e55_vltot,
                       z01_nome,e54_instit from (" . $sql . "
                       ) as x
                    inner join empautitem on e54_autori = e55_autori
                    group by e54_autori,e54_emiss,e54_anulad,z01_nome,e54_instit
                    order by e54_emiss desc,e54_autori ";
          db_lovrot($sql, 15, "()", "", "js_showAutori|e54_autori");
      }
      ?>
  </td>
</tr>
</table>
</body>
</html>
<script>
  function js_showAutori(iAutori) {

    js_OpenJanelaIframe('CurrentWindow.corpo',
      'db_iframe_empautoriza',
      'func_empempenhoaut001.php?e54_autori=' + iAutori, 'Dados da Autorização');

  }
</script>