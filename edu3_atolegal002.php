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
$oDaoAnexoAtoLegal->rotulo->label();
$oDaoTipoAtoLegal->rotulo->label();

function DBFormataData($dData) {

  $aData = explode('-', $dData);
  return $aData[2]."/".$aData[1]."/".$aData[0];

}

function DBCompetencia($sCompetencia) {

  if ($sCompetencia == "M") {

    return "MUNICIPAL";

  } elseif ($sCompetencia == "E") {

    return "ESTADUAL";

  } elseif ($sCompetencia == "F") {

    return "FEDERAL";

  } else {
    return $sCompetencia;
  }

}

function DBTipoAto($iTipo) {
  
  $oDaoTipoAtoLegal  = db_utils::getdao('tipoato');
  $sWhere            = " ed83_i_codigo = ".$iTipo;
  $sSql              = $oDaoTipoAtoLegal->sql_query("", "*", "", $sWhere);
  $rsTipo            = $oDaoTipoAtoLegal->sql_record($sSql);

  if ($oDaoTipoAtoLegal->numrows > 0) {
    
    return db_utils::fieldsmemory($rsTipo, 0)->ed83_c_descr;

  } else {
    return 0;
  }

}

if (!isset($iAtoLegal)) {
  
  echo("<center>Selecione um ato legal.</center>");

} else {

  $sWhereAto    = " ed05_i_codigo = ".$iAtoLegal;
  $sSqlAtoLegal = $oDaoAtoLegal->sql_query("", "*", "", $sWhereAto);
  $rsAtoLegal   = $oDaoAtoLegal->sql_record($sSqlAtoLegal);

  db_fieldsmemory($rsAtoLegal, 0);

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
  <body>
    <!-- Dados dos Atos Legais -->
    <fieldset><legend><b>Dados dos Atos Legais</b></legend>
    <center>  
      <table>
        <tr>
          <td width="100" nowrap title="<?=$Ted05_i_codigo?>">
            <?=$Led05_i_codigo?>
          </td>
          <td width="300">
            <?db_input('ed05_i_codigo', '10', $ed05_i_codigo, true, 'text', '3', '')?>
          </td>
          <td width="100" nowrap title="<?=$Ted05_i_ano?>">
            <?=$Led05_i_ano?>
          </td>
          <td>
            <?db_input('ed05_i_ano', '10', $ed05_i_ano, true, 'text', '3', '')?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Ted05_c_numero?>">
            <?=$Led05_c_numero?>
          </td>
          <td>
            <?db_input('ed05_c_numero', '20', $ed05_c_numero, true, 'text', '3', '')?>
          </td>
          <td nowrap title="<?=$Ted05_d_vigora?>">
            <?=$Led05_d_vigora?>
          </td>
          <td>
            <? $ed05_d_vigora = DBFormataData($ed05_d_vigora); ?>
            <?db_input('ed05_d_vigora', '10', $ed05_d_vigora, true, 'text', '3', '')?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Ted05_i_tipoato?>">
            <?=$Led05_i_tipoato?>
          </td>
          <td>
            <? $ed05_i_tipoato = DBTipoAto($ed05_i_tipoato); ?>
            <?db_input('ed05_i_tipoato', '20', $ed05_i_tipoato, true, 'text', '3', '')?>
          </td>
          <td nowrap title="<?=$Ted05_d_aprovado?>">
            <?=$Led05_d_aprovado?>
          </td>
          <td>
            <? $ed05_d_aprovado = DBFormataData($ed05_d_aprovado); ?>
            <?db_input('ed05_d_aprovado', '10', $ed05_d_aprovado, true, 'text', '3', '')?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Ted05_c_competencia?>">
            <?=$Led05_c_competencia?>
          </td>
          <td>
            <? $ed05_c_competencia = DBCompetencia($ed05_c_competencia); ?>
            <?db_input('ed05_c_competencia', '20', $ed05_c_competencia, true, 'text', '3', '')?>
          </td>
          <td nowrap title="<?=$Ted05_d_publicado?>">
            <?=$Led05_d_publicado?>
          </td>
          <td>
            <? $ed05_d_publicado = DBFormataData($ed05_d_publicado); ?>
            <?db_input('ed05_d_publicado', '10', $ed05_d_publicado, true, 'text', '3', '')?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Ted05_c_finalidade?>">
            <?=$Led05_c_finalidade?>
          </td>
          <td>
            <?db_input('ed05_c_finalidade', '40', $ed05_c_finalidade, true, 'text', '3', '')?>
          </td>
          <td nowrap title="<?=$Ted05_c_orgao?>">
            <?=$Led05_c_orgao?>
          </td>
          <td>
            <?db_input('ed05_c_orgao', '40', $ed05_c_orgao, true, 'text', '3', '')?>
          </td>
        </tr>
        <tr>
          <td title="<?=$Ted05_t_texto?>">
            <?=$Led05_t_texto?>
          </td>
          <td colspan="3">
            <?db_textarea('ed05_t_texto', 3, 97, $ed05_t_texto, true, 'text', '3', '')?>
          </td>
        </tr>
      </table>
    </center>
    </fieldset>
    <!-- Fim dos Dados dos Atos Legais -->

    <!-- Detalhamento do Ato Legal -->
    <fieldset><legend><b>Detalhamento</b></legend>
    <center>
      <?
        
        $oMenuLateral = new verticalTab('menus', '200');

        $oMenuLateral->add('cursos', 'Cursos', 'edu3_atolegal003.php?iAtoLegal='.$ed05_i_codigo);
        $oMenuLateral->add('rh', 'Recursos Humanos', 'edu3_atolegal004.php?iAtoLegal='.$ed05_i_codigo);
        $oMenuLateral->add('anexos', 'Anexos', 'edu3_atolegal005.php?iAtoLegal='.$ed05_i_codigo);

        $oMenuLateral->show();

      ?>
    </center>
    </fieldset>
    <!-- Fim do Detalhamento do Ato Legal -->
  </body>
</html>
<?

} //Fecha o isset($iAtoLegal)

?>