<?php
/**
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

db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);

$clprontagendamento = new cl_prontagendamento;
$clprontagendamento->rotulo->label();

$oRotulo = new rotulocampo();
$oRotulo->label("z01_v_nome");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body class="body-default">
  <div class="container">
    <form name="form2" method="post" action="">
      <fieldset>
        <legend>Filtros</legend>
        <table class="form-container">
          <tr>
            <td title="FAA">
              <label for="s102_i_prontuario">FAA:</label>
            </td>
            <td>
              <?php
              db_input("s102_i_prontuario", 10, $Is102_i_prontuario, true, "text", 4, "", "chave_s102_i_prontuario");
              ?>
            </td>
          </tr>
          <tr>
            <td title="<?=$Ts102_i_agendamento?>">
              <label for="s102_i_agendamento">
                <?=$Ls102_i_agendamento?>
              </label>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
              db_input("s102_i_agendamento", 10, $Is102_i_agendamento, true, "text", 4, "", "chave_s102_i_agendamento");
              ?>
            </td>
          </tr>
          <tr>
            <td title="<?=$Tz01_v_nome?>">
              <label for="z01_v_nome">Paciente:</label>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
              db_input("z01_v_nome", 10, $Iz01_v_nome, true, "text", 4, "", "chave_z01_v_nome");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar"    type="reset"  id="limpar"     value="Limpar" >
      <input name="Fechar"    type="button" id="fechar"     value="Fechar" onClick="parent.db_iframe_prontagendamento.hide();">
    </form>
  </div>
  <div class="container">
    <table>
      <tr>
        <td align="center" valign="top">
          <?php
          $aWhere   = array();
          $aWhere[] = "sd02_i_codigo = " . db_getsession("DB_coddepto");
          $aWhere[] = "sd23_d_consulta = current_date";

          $sWhereSetor  = "not exists(select 1";
          $sWhereSetor .= "             from prontagendamento";
          $sWhereSetor .= "                  inner join prontuarios        on sd24_i_codigo = s102_i_prontuario";
          $sWhereSetor .= "                  inner join setorambulatorial  on sd91_codigo   = sd24_setorambulatorial";
          $sWhereSetor .= "            where sd91_local in(2, 3)";
          $sWhereSetor .= "              and s102_i_agendamento = sd23_i_codigo)";

          $aWhere[] = $sWhereSetor;

          if(!isset($pesquisa_chave)) {

            if(isset($campos) == false) {

              if(file_exists("funcoes/db_func_prontagendamento.php")==true) {
                include(modification("funcoes/db_func_prontagendamento.php"));
              } else {
                $campos = "prontagendamento.*";
              }
            }

            if(isset($chave_s102_i_prontuario) && (trim($chave_s102_i_prontuario) != "")) {
              $aWhere[] = "s102_i_prontuario = {$chave_s102_i_prontuario}";
            }

            if(isset($chave_s102_i_agendamento) && (trim($chave_s102_i_agendamento) != "")) {
              $aWhere[] = "s102_i_agendamento = {$chave_s102_i_agendamento}";
            }

            if(isset($chave_z01_v_nome) && (trim($chave_z01_v_nome) != "")) {
              $aWhere[] = "z01_v_nome ilike '{$chave_z01_v_nome}%'";
            }

            $sWhere = implode(" AND ", $aWhere);
            $sSql   = $clprontagendamento->sql_query_ext("", $campos, "s102_i_codigo", $sWhere);

            $repassa = array();

            if(isset($chave_s102_i_codigo)) {
              $repassa = array("chave_s102_i_codigo" => $chave_s102_i_codigo);
            }

            db_lovrot($sSql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
          } else {

            if($pesquisa_chave != null && $pesquisa_chave != "") {

              $result = $clprontagendamento->sql_record($clprontagendamento->sql_query($pesquisa_chave));

              if($clprontagendamento->numrows != 0) {

                db_fieldsmemory($result, 0);
                echo "<script>".$funcao_js."('$s102_i_codigo',false);</script>";
              } else {
                echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
              }
            } else {
              echo "<script>".$funcao_js."('',false);</script>";
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
$('chave_s102_i_prontuario').className  = 'field-size2';
$('chave_s102_i_agendamento').className = 'field-size2';
$('chave_z01_v_nome').className         = 'field-size7';

(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>