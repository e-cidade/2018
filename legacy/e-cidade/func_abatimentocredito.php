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

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clabatimento = new cl_abatimento;
$clabatimento->rotulo->label("k125_sequencial");
$clabatimento->rotulo->label("k125_sequencial");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tk125_sequencial?>">
              <?php echo $Lk125_sequencial; ?>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
		            db_input("k125_sequencial",10,$Ik125_sequencial,true,"text",4,"","chave_k125_sequencial");
		          ?>
            </td>
          </tr>
         <tr>
           <td width="4%" align="right" nowrap title="Número no Cadastro Geral do Município">
             <a id="label_numcgm" for="z01_numcgm">Nome/Razão Social:</a>
           </td>
           <td width="96%" align="left" nowrap>
             <?php db_input("z01_numcgm", 10, 1, true, "text", 4, "", "z01_numcgm"); ?>
             <?php db_input("z01_nome", 30, 1, true, "text", 4, "", "z01_nome"); ?>
           </td>
         </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_abatimento.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?php

      $iDBInstit = (integer) db_getsession("DB_instit");
      $sDataUsuario = date("Y-m-d", db_getsession("DB_datausu"));
      $iAnoUsuario = (integer) date("Y", db_getsession("DB_datausu"));

      $sCampos = isset($campos) ? $campos : "abatimento.*";
      $aWhere  = array(
        "k125_valordisponivel > 0",
        "k125_instit = {$iDBInstit}"
      );

      if (!empty($z01_numcgm)) {
        $aWhere[] = "arrenumcgm.k00_numcgm = " . (integer) $z01_numcgm;
      }

      $campos = "abatimento.k125_sequencial,abatimento.k125_tipoabatimento,abatimento.k125_datalanc,abatimento.k125_hora,abatimento.k125_usuario,abatimento.k125_instit,abatimento.k125_valor,abatimento.k125_perc";

      // Correção de crédito
      $sCampos .= ",
        case
          when exists(select 1 from abatimentocorrecao where abatimentocorrecao.k167_abatimento = abatimento.k125_sequencial) then
            (select fc_corre(
                (select recibo.k00_receit from abatimentorecibo inner join recibo on recibo.k00_numpre = abatimentorecibo.k127_numprerecibo where k127_abatimento = abatimento.k125_sequencial limit 1),
                (select abatimentocorrecao.k167_data from abatimentocorrecao where k167_abatimento = abatimento.k125_sequencial order by k167_data desc limit 1),
                abatimento.k125_valordisponivel,
                '{$sDataUsuario}',
                {$iAnoUsuario},
                (select abatimentocorrecao.k167_data from abatimentocorrecao where k167_abatimento = abatimento.k125_sequencial order by k167_data desc limit 1)
            ))
          else
            (select fc_corre(
                (select recibo.k00_receit from abatimentorecibo inner join recibo on recibo.k00_numpre = abatimentorecibo.k127_numprerecibo where k127_abatimento = abatimento.k125_sequencial limit 1),
                abatimento.k125_datalanc,
                abatimento.k125_valordisponivel,
                '{$sDataUsuario}',
                {$iAnoUsuario},
                abatimento.k125_datalanc
            ))
        end k125_valordisponivel
      ";

      if (isset($tipo)) {
      	$aWhere[] = "k125_tipoabatimento = {$tipo}";
      }

      $sWhereTipoAbatimento = implode(" and ", $aWhere);

      if (!isset($pesquisa_chave)) {

        $sql = $clabatimento->sql_query("", $sCampos, "k125_sequencial", $sWhereTipoAbatimento);
        if (!empty($chave_k125_sequencial)) {

           $sql = $clabatimento->sql_query(
             "", $sCampos, "k125_sequencial",
             " k125_sequencial = {$chave_k125_sequencial} " . ($sWhereTipoAbatimento != "" ? " and " . $sWhereTipoAbatimento:" and k125_instit = {$iDBInstit}")
           );
        }

        $repassa = array();
        if (isset($chave_k125_sequencial)) {
          $repassa = array("chave_k125_sequencial" => $chave_k125_sequencial, "chave_k125_sequencial" => $chave_k125_sequencial);
        }

        db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);

      } else {

        if (!empty($pesquisa_chave)) {

        	$sWhere = "k125_sequencial = {$pesquisa_chave}".($sWhereTipoAbatimento!=""?" and ".$sWhereTipoAbatimento:" and k125_instit = {$iDBInstit}");

        	$result = $clabatimento->sql_record($clabatimento->sql_query(null,$sCampos,"k125_sequencial",$sWhere));
          if ($clabatimento->numrows!=0) {
          	db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$k125_sequencial',false);</script>";

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
  <script type="text/javascript">
    var oLookupCgm = new DBLookUp($('label_numcgm'), $('z01_numcgm'), $('z01_nome'), {
      "sArquivo" : "func_nome.php",
      "sObjetoLookUp" : "db_iframe_nome",
      "sLabel" : "Pesquisar",
    });

    js_tabulacaoforms("form2","chave_k125_sequencial",true,1,"chave_k125_sequencial",true);

    (function() {
      var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
      input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
    })();
  </script>
</body>
</html>
