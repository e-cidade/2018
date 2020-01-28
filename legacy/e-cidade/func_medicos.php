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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_jsplibwebseller.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_medicos_classe.php"));
require_once(modification("classes/db_cgm_classe.php"));

db_postmemory($_POST);

if ( !empty($lFiltraDptoLogado) && !isset($chave_sd06_i_unidade)) {
  $chave_sd06_i_unidade = db_getsession('DB_coddepto');
}

$oDaoMedicos = new cl_medicos;
$oRotulo     = new rotulocampo;
$oRotulo->label('sd03_i_codigo');
$oRotulo->label('z01_nome');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td height="63" align="center" valign="top">
      <form name="form2" method="post" action="" >
      <table width="35%" border="0" align="center" cellspacing="0">
        <tr>
          <td width="4%" align="right" nowrap title="<?=$Tsd03_i_codigo?>">
            <?=$Lsd03_i_codigo?>
          </td>
          <td width="96%" align="left" nowrap>
            <?
            db_input("sd03_i_codigo", 5, $Isd03_i_codigo, true, "text", 4, "", "chave_sd03_i_codigo");
            ?>
          </td>
        </tr>
        <tr>
          <td width="4%" align="right" nowrap title="<?=$Tz01_nome?>">
            <?=$Lz01_nome?>
          </td>
          <td width="96%" align="left" nowrap>
            <?
            db_input("z01_nome", 80, $Iz01_nome, true, "text", 4,
                     " onFocus=\"nextfield='pesquisar2'\" ", "chave_z01_nome"
                    );
            ?>
          </td>
        </tr>
        <tr>
          <td colspan="2" align="center">
            <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar" onfocus="nextfield='done'" >
            <input name="limpar" type="button" id="limpar" value="Limpar" onClick="js_limpar();">
            <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="js_fechar('<?=@$campoFoco?>');">
          </td>
        </tr>
      </table>
      </form>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?
      $sWhere = '';
      $sSep   = '';
      if (!isset($lTodosTiposProf)) { // Trago somente profissionais da rede

        $sWhere = ' sd03_i_tipo = 1 ';
        $sSep   = ' and ';

      }
      if (isset($prof_ativo)) {

       $sWhere .= $sSep." sd27_c_situacao = 'A' ";
       $sSep    = ' and ';
       $sQuery  = 'sql_query_ativo';

      } else {
        $sQuery = 'sql_query_cgm_fora_rede';
      }

      if (!empty($chave_sd06_i_unidade)) {

        $sWhere .= $sSep." sd04_i_unidade = $chave_sd06_i_unidade ";
        $sSep    = ' and ';

      }

      //Busca profissionais pela especialidade
      if ( !empty($iRhcboSequencial)) {

        $sWhere .= $sSep." sd27_i_rhcbo = $iRhcboSequencial ";
        $sSep    = ' and ';
      }

      $sSep = '';
      if (!empty($sWhere)) {
        $sSep = ' and ';
      }

      if (!isset($pesquisa_chave)) {

        if (isset($campos) == false) {

          if (file_exists("funcoes/db_func_medicos.php") == true) {
            require_once(modification("funcoes/db_func_medicos.php"));
          } else {
            $campos = "medicos.*, cgm.z01_nome";
          }

        }
        if (isset($campo_sd04_i_codigo)) {
          $campos .= ', unidademedicos.sd04_i_codigo ';
        }

        if (isset($chave_sd03_i_codigo) && (trim($chave_sd03_i_codigo) != '') ) {

          $sSql = $oDaoMedicos->{$sQuery}(null, $campos, 'sd03_i_codigo',
                                          "medicos.sd03_i_codigo = $chave_sd03_i_codigo $sSep ".$sWhere
                                         );

        } elseif (isset($chave_z01_nome) && trim($chave_z01_nome) != '') {

          if (!isset($lTodosTiposProf)) { // Trago somente profissionais da rede

            $sSql = $oDaoMedicos->{$sQuery}(null, $campos, 'z01_nome',
                                            " z01_nome like '$chave_z01_nome%' $sSep $sWhere"
                                           );

          } else {

            $sSql = $oDaoMedicos->{$sQuery}(null, $campos, 'z01_nome',
                                            " (z01_nome like '$chave_z01_nome%'
                                               or s154_c_nome like '$chave_z01_nome%')
                                            $sSep $sWhere"
                                           );

          }

        } else {
          $sSql = $oDaoMedicos->{$sQuery}(null, $campos, 'sd03_i_codigo', $sWhere);
        }

        $repassa = array();
        if (isset($chave_sd03_i_crm)) {
          $repassa = array('chave_sd03_i_codigo' => $chave_sd03_i_codigo, 'chave_z01_nome' => $chave_z01_nome);
        }

        if (isset($nao_mostra)) {

          $sSep    = '';
          $aFuncao = explode('|', $funcao_js);
          $rs      = $oDaoMedicos->sql_record($sSql);
           if ($oDaoMedicos->numrows == 0) {
	           die('<script>'.$aFuncao[0]."('','Chave(".$chave_sd03_i_codigo.") não Encontrado');</script>");
           } else {

             db_fieldsmemory($rs, 0);
             $sFuncao = $aFuncao[0].'(';
             for ($iCont = 1; $iCont < count($aFuncao); $iCont++) {

               $sFuncao .= $sSep.'"'.eval('return @$'.$aFuncao[$iCont].';').'"';
               $sSep     = ', ';

             }
             $sFuncao  = substr($sFuncao, 0, strlen($sFuncao));
             $sFuncao .= ');';
             die("<script>".$sFuncao.'</script>');

          }
        }

        db_lovrot($sSql, 15, '()', '',$funcao_js, '', 'NoMe', $repassa);

      } else {

        if ($pesquisa_chave != null && $pesquisa_chave != '') {

          if (isset($sd27_i_codigo)) {
            $sSql = $oDaoMedicos->{$sQuery}('', '*', '', "sd27_i_codigo = $sd27_i_codigo $sSep $sWhere ");
          } else {

             $sSql = $oDaoMedicos->{$sQuery}($pesquisa_chave, '*', '',
                                             "medicos.sd03_i_codigo = $pesquisa_chave $sSep $sWhere "
                                            );
          }

          $rs = $oDaoMedicos->sql_record($sSql);
          if ($oDaoMedicos->numrows != 0) {

            db_fieldsmemory($rs, 0);

            if ($sd03_i_tipo == 1) {
              $sNome = $z01_nome;
            } else {
              $sNome = $s154_c_nome;
            }
            if (isset($sd27_i_codigo) && !empty($sd27_i_codigo)) {
              echo "<script>".$funcao_js."('$sNome', false, $sd03_i_codigo,$sd27_i_codigo);</script>";
            } elseif (isset($sd03_i_crm) && !empty($sd03_i_crm)) {
              echo "<script>".$funcao_js."('$sNome',false,$sd03_i_crm);</script>";
            } else {
              echo "<script>".$funcao_js."('$sNome',false);</script>";
            }

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
</body>
</html>

<script>

/**
 * Botoão Fechar
 * campoFoco = foco de retorno quando fechar
 */
function js_fechar( campoFoco ) {

  if ( campoFoco != undefined && campoFoco != '' ) {

    eval( "parent.document.getElementById('"+campoFoco+"').focus(); " );
    eval( "parent.document.getElementById('"+campoFoco+"').select(); " );
  }
  parent.db_iframe_medicos.hide();
}
function js_limpar() {

  document.form2.chave_sd03_i_codigo.value="";
  document.form2.chave_z01_nome.value="";
}
 js_tabulacaoforms("form2","chave_z01_nome",true,1,"chave_z01_nome",true);
</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
