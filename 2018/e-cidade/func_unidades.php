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

db_postmemory( $_POST );
parse_str( $_SERVER["QUERY_STRING"] );

$clunidades = new cl_unidades;
$clrotulo   = new rotulocampo;
$clunidades->rotulo->label("sd02_i_codigo");
$clrotulo->label("descrdepto");
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
    <form name="form2" method="post" action="" >
      <fieldset>
        <legend>Filtros</legend>
        <table class="form-container">
          <tr>
            <td title="<?=$Tsd02_i_codigo?>">
              <label for="chave_sd02_i_codigo">
                <?=$Lsd02_i_codigo?>
              </label>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
              db_input( "sd02_i_codigo", 10, $Isd02_i_codigo, true, "text", 4, "", "chave_sd02_i_codigo" );
              ?>
            </td>
          </tr>
          <tr>
            <td title="<?=$Tdescrdepto?>">
              <label for="chave_descrdepto">
                <?=$Ldescrdepto?>
              </label>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
              db_input( "descrdepto", 50, $Idescrdepto, true, "text", 4, "", "chave_descrdepto" );
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="button" id="limpar" value="Limpar" onClick="js_limpar()">
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_unidades.hide();">
    </form>
  </div>
  <div class="container">
    <table>
      <tr>
        <td align="center" valign="top">
          <?php
          $sAnd                     = "";
          $sWhere                   = "";
          $sWhereDepartamentoLogado = "";

          if (isset($iCotas)) {

            $iDepart = db_getsession("DB_coddepto");
            $sAnd    = " and ";
            $sWhere  = " EXISTS (select * from sau_cotasagendamento where s163_i_upssolicitante = {$iDepart} ";
            $sWhere .= " and s163_i_upsprestadora = sd02_i_codigo)";

            if(!isset($chave_sd02_i_codigo)) {
              $sWhereDepartamentoLogado .= " or sd02_i_codigo = {$iDepart} ";
            }
          }

          if (isset($iIssetCotas)) {

            $iDepart = db_getsession("DB_coddepto");
            $sAnd    = " and ";
            $sWhere  = " EXISTS (select * from sau_cotasagendamento where s163_i_upsprestadora = sd02_i_codigo)";
          }

          /* PLUGIN PSF - Condição CNES */

          if( !isset( $pesquisa_chave ) ) {

            if( isset( $campos ) == false ) {

              if( file_exists( "funcoes/db_func_unidades.php" ) == true ) {
                include(modification("funcoes/db_func_unidades.php"));
              } else {
                $campos = "unidades.*,db_depart.descrdepto";
              }
            }

            $sWhere = $sWhere . $sWhereDepartamentoLogado;

            if(isset($chave_sd02_i_codigo) && (trim($chave_sd02_i_codigo)!="") ) {
              $sql = $clunidades->sql_query("", $campos, "descrdepto", "sd02_i_codigo = $chave_sd02_i_codigo $sAnd $sWhere");
            } else if(isset($chave_descrdepto) && (trim($chave_descrdepto)!="") ) {
              $sql = $clunidades->sql_query("", $campos, "descrdepto", "descrdepto like '$chave_descrdepto%' $sAnd $sWhere");
            } else {
              $sql = $clunidades->sql_query("", $campos, "descrdepto", $sWhere);
            }

            $repassa = array();
            if( isset( $chave_sd02_c_nome ) ) {
              $repassa = array(
                "chave_sd02_i_codigo" => $chave_sd02_i_codigo,
                "chave_descrdepto"    => $chave_descrdepto
              );
            }

            db_lovrot( $sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa );
          } else {

            if( $pesquisa_chave != null && $pesquisa_chave != "" ) {

              $sSql   = $clunidades->sql_query( "", "*", "", "sd02_i_codigo = $pesquisa_chave $sAnd $sWhere" );
              $result = $clunidades->sql_record( $sSql );

              if( $clunidades->numrows != 0 ) {

                db_fieldsmemory( $result, 0 );
                echo "<script>".$funcao_js."('$descrdepto',false);</script>";
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
<script>
$('chave_sd02_i_codigo').className = 'field-size2';
$('chave_descrdepto').className    = 'field-size7';
$('chave_sd02_i_codigo').focus();

function js_limpar() {

  $('chave_sd02_i_codigo').value = "";
  $('chave_descrdepto').value    = "";
}

(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
