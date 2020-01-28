<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/verticalTab.widget.php");
require_once("libs/db_app.utils.php");

$oDaoAtoLegal      = db_utils::getdao('atolegal');
$oDaoAnexoAtoLegal = db_utils::getdao('edu_anexoatolegal');
$oDaoTipoAtoLegal  = db_utils::getdao('tipoato');

$oDaoAtoLegal->rotulo->label();

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">

    <?
      $sLib  = "scripts.js,prototype.js,webseller.js,strings.js,";
      $sLib .= "estilos.css,tab.style.css";
      db_app::load($sLib);
    ?>

  </head>
  <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
    <table width="790" height="18"  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>
    <center>
    <br /><br />
      <!-- Consulta Atos Legais -->
      <fieldset style="margin-top: 5px; width:60%;"><legend><b>Consulta de Atos Legais</b></legend>
        <form name="form1" action="">
          <table>
            <tr>
              <td nowrap title="<?=$Ted05_i_codigo?>">
                <?=$Led05_i_codigo?>
              </td>
              <td>
                <?db_input('ed05_i_codigo', '10', '', true, 'text', 1, '')?>
              </td>
              <td nowrap title="<?=$Ted05_i_ano?>">
                <?=$Led05_i_ano?>
              </td>
              <td>
                <?db_input('ed05_i_ano', '10', '', true, 'text', 1, '')?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=$Ted05_c_competencia?>">
                <?=$Led05_c_competencia?>
              </td>
              <td>
                <?
                  $aCompetencias = array(
                                          "0" => "TODAS",
                                          "M" => "MUNICIPAL",
                                          "E" => "ESTADUAL",
                                          "F" => "FEDERAL"
                                        );
                  db_select('ed05_c_competencia', $aCompetencias, true, '1', '');
                ?>
              </td>
              <td nowrap title="<?=$Ted05_i_tipoato?>">
                <?=$Led05_i_tipoato?>
              </td>
              <td>
                <?
                  $sSql      = $oDaoTipoAtoLegal->sql_query('', '*', 'ed83_c_descr ASC', '');
                  $rsTipos   = $oDaoTipoAtoLegal->sql_record($sSql);
                  $aTipoAtos = array("0" => "TODOS");
                  
                  if ($oDaoTipoAtoLegal->numrows > 0) {
                    
                    for ($iCont = 0; $iCont < $oDaoTipoAtoLegal->numrows; $iCont++) {
                      
                      $oDados                            = db_utils::fieldsmemory($rsTipos, $iCont);
                      $aTipoAtos[$oDados->ed83_i_codigo] = $oDados->ed83_c_descr;

                    }

                  }

                  db_select('ed05_i_tipoato', $aTipoAtos, true, '1', '');
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="4" style="text-align:center; padding-top:5px;">
                <input type="submit" value="Pesquisar" name="pesquisar" onclick="" />
                <input type="reset" value="Limpar" name="limpar" />
              </td>
            </tr>
          </table>
        </form>
      </fieldset>
      <!-- Fim da Consulta de Atos Legais -->

      <? if (isset($pesquisar)) { ?>
      <!-- Registros da Pesquisa -->
      <fieldset style="width:60%;"><legend><b>Registros<b></legend>
      <?
        $iEscola  = db_getsession('DB_coddepto');
        $sCampos  = " ed05_i_codigo,ed05_c_numero,ed05_c_finalidade,ed83_c_descr,ed05_c_competencia,ed05_i_ano ";
        $sOrderBy = " ed05_i_codigo ASC ";
        $sWhere   = "";

        if (isset($ed05_i_codigo)) {
          $repassa = array("ed05_i_codigo" => $ed05_i_codigo);
        }

        if (isset($ed05_i_codigo) && trim($ed05_i_codigo) != "") {
          
          $sWhere .= (empty($sWhere) ? '' : ' AND ');
          $sWhere .= " ed05_i_codigo = ".$ed05_i_codigo;

        }

        if (isset($ed05_i_ano) && !empty($ed05_i_ano)) {
          
          $sWhere .= (empty($sWhere) ? '' : ' AND ');
          $sWhere .= " ed05_i_ano = ".$ed05_i_ano;
        
        }

        if (isset($ed05_c_competencia) && trim($ed05_c_competencia) != "0") {

          $sWhere .= (empty($sWhere) ? '' : ' AND ');
          $sWhere .= " ed05_c_competencia = '".$ed05_c_competencia."'";
        
        }

        if (isset($ed05_i_tipoato) && trim($ed05_i_tipoato) != 0) {

          $sWhere .= (empty($sWhere) ? '' : ' AND ');
          $sWhere .= " ed05_i_tipoato = ".$ed05_i_tipoato;

        }
        
        $sWhere  .= (empty($sWhere) ? '' : ' AND ');
        $sWhere  .= " ed19_i_escola = $iEscola ";
        $sSql     = $oDaoAtoLegal->sql_query("", $sCampos, $sOrderBy, $sWhere);
        db_lovrot(@$sSql, 12, "()", "", "js_redireciona|ed05_i_codigo", "", "NoMe", $repassa);
      ?>
      </fieldset>
      <!-- Fim Registros da Pesquisa -->
      <? } ?>
    </center>
  </body>
</html>

<?
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"),
          db_getsession("DB_anousu"), db_getsession("DB_instit")
         );
?>

<script language="JavaScript">

function js_redireciona(iAtoLegal) {

  js_OpenJanelaIframe('', 'db_iframe_atolegal', 'edu3_atolegal002.php?iAtoLegal='+iAtoLegal, 
                      'Dados do Ato Legal', true);

}

</script>