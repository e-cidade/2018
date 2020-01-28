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
require_once(modification("libs/db_libpessoal.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_afasta_classe.php"));

db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);

$clafasta = new cl_afasta;
$clrotulo = new rotulocampo;
$clafasta->rotulo->label();
$clrotulo->label("DBtxt23");
$clrotulo->label("DBtxt25");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_numcgm");

if (isset($valor_testa_rescisao)) {
    $chave_r45_regist = $valor_testa_rescisao;
    $retorno          = db_alerta_dados_func($testarescisao, $valor_testa_rescisao, db_anofolha(), db_mesfolha());

    if ($retorno != "") {
        db_msgbox($retorno);
    }

    unset($retorno);
}

$oGet = db_utils::postMemory($_GET);

?>
<html>
<head>
<?php
if (!isset($pesquisa_chave)) {
    ?>
    <script>
      function js_recebe_click(value, afast) {
        obj = document.createElement('input');
        obj.setAttribute('type', 'hidden');
        obj.setAttribute('name', 'funcao_js');
        obj.setAttribute('id', 'funcao_js');
        obj.setAttribute('value', '<?=$funcao_js?>');
        document.form2.appendChild(obj);

        obj = document.createElement('input');
        obj.setAttribute('type', 'hidden');
        obj.setAttribute('name', 'valor_testa_rescisao');
        obj.setAttribute('id', 'valor_testa_rescisao');
        obj.setAttribute('value', value);
        document.form2.appendChild(obj);

        obj = document.createElement('input');
        obj.setAttribute('type', 'hidden');
        obj.setAttribute('name', 'chave_r45_dtafas');
        obj.setAttribute('id', 'chave_r45_dtafas');
        obj.setAttribute('value', afast);
        document.form2.appendChild(obj);

        document.form2.submit();
      }
    </script>
    <?php
}
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body class="body-default">
  <div class="container">
    <form name="form2" method="post" action="" >
      <fieldset>
        <legend>Filtros</legend>
        <table class="form-container">
          <tr>
            <td title="Digite o Ano / Mes de competência" >
              <label for="DBtxt23">Ano / Mês :</label>
            </td>
            <td colspan='3'>
            <?php
            if (!isset($chave_r45_anousu)) {
                $chave_r45_anousu = db_anofolha();
            }
            db_input('DBtxt23', 4, $IDBtxt23, true, 'text', 3, "", 'chave_r45_anousu');
            ?>
            &nbsp;/&nbsp;
            <?php
            if (!isset($chave_r45_mesusu)) {
                $chave_r45_mesusu = db_mesfolha();
            }
            db_input('DBtxt25', 2, $IDBtxt25, true, 'text', 3, "", 'chave_r45_mesusu');
            ?>
            </td>
          </tr>
          <tr>
            <td title="<?=$Tr45_regist?>">
              <label for="r45_regist"><?=$Lr45_regist?></label>
            </td>
            <td nowrap>
            <?php
            db_input("r45_regist", 8, $Ir45_regist, true, "text", 4, "", "chave_r45_regist");
            ?>
            </td>
          </tr>
          <tr>
            <td title="<?=$Tz01_numcgm?>">
              <label for="z01_numcgm"><?=$Lz01_numcgm?></label>
            </td>
            <td nowrap>
            <?php
            db_input("z01_numcgm", 8, $Iz01_numcgm, true, "text", 4, "", "chave_z01_numcgm");
            ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=$Tz01_nome?>">
              <label for="z01_nome"><?=$Lz01_nome?></label>
            </td>
            <td nowrap colspan='3'>
            <?php
            db_input("z01_nome", 80, $Iz01_nome, true, "text", 4, "", "chave_z01_nome");
            ?>
            </td>
          </tr>
        </table>
      </fieldset>

      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_afasta.hide();">
    </form>
  </div>

  <div class="container">
    <table>
      <tr>
        <td align="center" valign="top">
            <?php
            $dbwhere = "r45_anousu = {$chave_r45_anousu} and r45_mesusu = {$chave_r45_mesusu} ";

            if (isset($chave_r45_dtafas) && trim($chave_r45_dtafas) != "") {
                $dbwhere .= " and r45_dtafas = '{$chave_r45_dtafas}' ";
            }

            /**
             * Verifica se existe no get testarescisao
             * Procura somente servidores sem rescisao
             */
            if (!empty($oGet->testarescisao)) {
                $dbwhere .= " and not exists(select 1                          ";
                $dbwhere .= "                  from rhpesrescisao              ";
                $dbwhere .= "                 where rh05_seqpes = rh02_seqpes  ";
                $dbwhere .= "               )                                  ";
            }

            $dbwhere .= ' and not exists(select 1 from afastaassenta where h81_afasta = r45_codigo)';


            if (!isset($pesquisa_chave)) {
                if (isset($campos) == false) {
                    if (file_exists("funcoes/db_func_afasta.php") == true) {
                        include(modification("funcoes/db_func_afasta.php"));
                    } else {
                        $campos = "afasta.oid as db_oid,afasta.*";
                    }
                }

                if ((isset($chave_r45_regist) && (trim($chave_r45_regist) != ""))) {
                    $sql = $clafasta->sql_query_func(null, $campos, "r45_regist", " r45_regist = $chave_r45_regist and " . $dbwhere);
                } else {
                    if ((isset($chave_z01_numcgm) && (trim($chave_z01_numcgm) != ""))) {
                        $sql = $clafasta->sql_query_func(null, $campos, "z01_numcgm", " z01_numcgm = $chave_z01_numcgm and " . $dbwhere);
                    } else {
                        if ((isset($chave_z01_nome) && (trim($chave_z01_nome) != ""))) {
                            $sql = $clafasta->sql_query_func(null, $campos, "z01_nome", " z01_nome like '$chave_z01_nome%' and " . $dbwhere);
                        } else {
                            $sql = $clafasta->sql_query_func(null, $campos, "", $dbwhere);
                        }
                    }
                }

                $sFuncao = (isset($testarescisao) && !isset($valor_testa_rescisao) ? "js_recebe_click|r45_regist|r45_dtafas" : $funcao_js);
                db_lovrot($sql, 15, "()", "", $sFuncao);
            } else {
                if ($pesquisa_chave != null && $pesquisa_chave != "") {
                    $result = $clafasta->sql_record($clafasta->sql_query_func(null, "*", "", $dbwhere . " and r45_codigo = " . $chave_pesquisa));
                    if ($clafasta->numrows != 0) {
                        db_fieldsmemory($result, 0);

                        if (isset($testarescisao)) {
                            $retorno = db_alerta_dados_func($testarescisao, $pesquisa_chave, db_anofolha(), db_mesfolha());

                            if ($retorno != "") {
                                db_msgbox($retorno);
                            }
                        }
                        echo "<script>" . $funcao_js . "('$r45_codigo',false);</script>";
                    } else {
                        echo "<script>" . $funcao_js . "('Chave(" . $pesquisa_chave . ") não Encontrado',true);</script>";
                    }
                } else {
                    echo "<script>" . $funcao_js . "('',false);</script>";
                }
            }
            ?>
         </td>
       </tr>
    </table>
  </div>
</body>
</html>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
