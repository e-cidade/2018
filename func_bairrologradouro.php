<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("std/db_stdClass.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

$oDaoCadEnderBairroCadEnderRua = new cl_cadenderbairrocadenderrua();
$oDaoCadEnderEstado            = new cl_cadenderestado();
$oPost                         = db_utils::postMemory($_POST);
$oGet                          = db_utils::postMemory($_GET);
$oRotulo                       = new rotulocampo();
$oRotulo->label("db73_descricao");
$oRotulo->label("db74_descricao");
$oRotulo->label("db71_descricao");
$oRotulo->label("db72_descricao");
$oDadosInstituicao = db_stdClass::getDadosInstit();
$iCodigoBrasil     = 1;
$sSqlEstados       = $oDaoCadEnderEstado->sql_query_file(null, "*", "db71_descricao",
                                                         "db71_cadenderpais = {$iCodigoBrasil}"
                                                        );
$rsEstados       = $oDaoCadEnderEstado->sql_record($sSqlEstados);
$aEstados        = array();
$aDadosEstados   = db_utils::getColectionByRecord($rsEstados);
$iCodigoEstado   = '';

foreach ($aDadosEstados as $oEstado) {

  $aEstados[$oEstado->db71_sequencial] = $oEstado->db71_descricao;
  if ($oEstado->db71_sigla  == $oDadosInstituicao->uf) {
    $iCodigoEstado = $oEstado->db71_sequencial;
  }
}
if (!isset($oPost->db71_sequencial)) {
  $db71_sequencial = $iCodigoEstado;
}
if (!isset($oPost->chave_db72_descricao)) {
  $chave_db72_descricao = $oDadosInstituicao->munic;
}
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <?php
    db_app::load("scripts.js, prototype.js");
  ?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
    <tr>
      <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	        <form name="form2" method="post" action="" >
	          <tr>
              <td width="4%" align="right" nowrap title="<?=$Tdb71_descricao?>">
                <?=$Ldb71_descricao?>
              </td>
              <td width="96%" align="left" nowrap>
                <?
                  db_select('db71_sequencial', $aEstados, true, 1);
		            ?>
              </td>
            </tr>
            <tr>
              <td width="4%" align="right" nowrap title="<?=$Tdb72_descricao?>">
                <?=$Ldb72_descricao?>
              </td>
              <td width="96%" align="left" nowrap>
                <?
		              db_input("db72_descricao", 50, $Idb72_descricao, true, "text", 4, "", "chave_db72_descricao");
		            ?>
              </td>
            </tr>
            <tr>
              <td width="4%" align="right" nowrap title="<?=$Tdb73_descricao?>">
                <?=$Ldb73_descricao?>
              </td>
              <td width="96%" align="left" nowrap>
                <?
		              db_input("db73_descricao", 50, $Idb73_descricao, true, "text", 4, "", "chave_db73_descricao");
		            ?>
              </td>
            </tr>
            <tr>
              <td width="4%" align="right" nowrap title="<?=$Tdb74_descricao?>">
                <?=$Ldb74_descricao?>
              </td>
              <td width="96%" align="left" nowrap>
                <?
		              db_input("db74_descricao", 50, $Idb74_descricao, true, "text", 4, "", "chave_db74_descricao");
		            ?>
              </td>
            </tr>
            <tr>
              <td colspan="2" align="center">
                <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
                <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_bairrologradouro.hide();">
               </td>
            </tr>
          </form>
        </table>
      </td>
    </tr>
    <tr>
      <td align="center" valign="top">
        <?
          
          /**
           * Campos de retorno
           */
          $sCamposBairroLogradouro  = "db87_sequencial, db73_descricao, db74_descricao, db87_cadenderrua, db71_descricao";
          $sCamposBairroLogradouro .= ", db72_descricao";
          $aWhereBairroLogradouro   = array();
          
          /**
           * Caso tenha sido setado o codigo do municipio, buscamos apenas os logradouros e bairros vinculados ao
           * municipio informado
           */
          if (isset($oGet->iMunicipio) && !empty($oGet->iMunicipio)) {
            
            $aWhereBairroLogradouro[] = "db73_cadendermunicipio = {$oGet->iMunicipio}";
            $aWhereBairroLogradouro[] = "db74_cadendermunicipio = {$oGet->iMunicipio}";
          }
          
          if (!isset($pesquisa_chave)) {

            if (isset($chave_db73_descricao) && !empty($chave_db73_descricao)) {
              $aWhereBairroLogradouro[] = "db73_descricao ilike '%{$chave_db73_descricao}%'";
            }
            
            if (isset($chave_db74_descricao) && !empty($chave_db74_descricao)) {
              $aWhereBairroLogradouro[] = "db74_descricao ilike '%{$chave_db74_descricao}%'";
            }

            if (isset($chave_db72_descricao) && !empty($chave_db72_descricao)) {
              $aWhereBairroLogradouro[] = "db72_descricao ilike '%{$chave_db72_descricao}%'";
            }
            
            if (!empty($db71_sequencial)) {
              $aWhereBairroLogradouro[] = "db71_sequencial = {$db71_sequencial}";
            }
  
            $sWhereBairroLogradouro  = implode(" and ", $aWhereBairroLogradouro);
            $sSqlBairroLogradouro    = $oDaoCadEnderBairroCadEnderRua->sql_query_completa(
                                                                                           null,
                                                                                           $sCamposBairroLogradouro,
                                                                                           null,
                                                                                           $sWhereBairroLogradouro
                                                                                         );

            $repassa = array();
            db_lovrot($sSqlBairroLogradouro, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
          } else {

            if ($pesquisa_chave != null && $pesquisa_chave != "") {

              $aWhereBairroLogradouro[] = "db87_sequencial = {$pesquisa_chave}";
              $sWhereBairroLogradouro   = implode(" and ", $aWhereBairroLogradouro);
              
              $sSqlBairroLogradouro = $oDaoCadEnderBairroCadEnderRua->sql_query_completa(
                                                                                          null,
                                                                                          $sCamposBairroLogradouro,
                                                                                          "db87_sequencial",
                                                                                          $sWhereBairroLogradouro
                                                                                        );
              $rsBairroLogradouro = $oDaoCadEnderBairroCadEnderRua->sql_record($sSqlBairroLogradouro);
              
              if ($oDaoCadEnderBairroCadEnderRua->numrows > 0) {
                 
                db_fieldsmemory($rsBairroLogradouro, 0);
                echo "<script>".$funcao_js."(false,
                                             '$db87_sequencial',
                                             '$db73_descricao',
                                             '$db74_descricao',
                                             '$db87_cadenderrua',
                                             '$db71_descricao',
                                             '$db72_descricao'
                                            );</script>";
              } else {
                echo "<script>".$funcao_js."(true, 'Chave(".$pesquisa_chave.") não Encontrado');</script>";
              }
            } else {
              echo "<script>".$funcao_js."(true, '');</script>";
            }
          }
        ?>
      </td>
    </tr>
  </table>
</body>
</html>