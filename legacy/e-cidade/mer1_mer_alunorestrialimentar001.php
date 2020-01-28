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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

db_postmemory( $_POST );

$clmer_restriitem            = new cl_mer_restriitem;
$clmer_restricaointolerancia = new cl_mer_restricaointolerancia;
$clmer_cardapio              = new cl_mer_cardapio;

$db_opcao = 1;
$db_botao = true;

$sSqlMerCardapio = $clmer_cardapio->sql_query( "", "me27_i_ano", "", " me01_i_codigo = {$me06_i_cardapio}" );
$result          = $clmer_cardapio->sql_record( $sSqlMerCardapio );

if( $clmer_cardapio->numrows > 0 ) {
  db_fieldsmemory( $result, 0 );
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.cabec{
 text-align: left;
 font-size: 10;
 color: #DEB887;
 background-color:#444444;
 border:1px solid #CCCCCC;
}
.aluno{
 font-size: 11;
}
</style>
</head>
<body class="body-default">
  <form name="form1" method="post" action="">
    <fieldset style="width:95%">
      <legend>Alunos com restrições alimentares</legend>
      <table border="0" cellspacing="2px" width="100%" height="100%" cellpadding="1px" bgcolor="#cccccc">
        <tr>
          <td align="center" valign="top">
            <table border='1px' width="100%" bgcolor="#cccccc" style="" cellspacing="0px">
              <tr class='cabec'>
                <td align='center'>
                  <label class='bold'>Código</label>
                </td>
                <td align='center'>
                  <label class='bold'>Aluno</label>
                </td>
                <td align='center'>
                  <label class='bold'>Escola</label>
                </td>
                <td align='center'>
                  <label class='bold'>Intolerância Alimentar</label>
                </td>
                <td align='center'>
                  <label class='bold'>Alimento Substituto</label>
                </td>
                <td align='center'>
                  <label class='bold'>Trocar alimento substituto</label>
                </td>
              </tr>
              <?php
              $sCampos  = " aluno.ed47_i_codigo, escola.ed18_c_nome, aluno.ed47_v_nome";
              $sCampos .= ", mer_alimento.me35_c_nomealimento as alimento, mer_alimentosub.me35_c_nomealimento as alimentosub";
              $sCampos .= ",mer_intoleranciaalimentar.me33_c_descr, mer_restriitem.me25_i_alimento";
              $sCampos .= ",mer_restriitem.me25_i_alimentosub, mer_cardapio.me01_i_codigo, mer_restricao.me24_i_codigo";
              $sOrder   = "mer_restriitem.me25_i_alimento";
              $sWhere   = " mer_cardapioitem.me07_i_cardapio = {$me06_i_cardapio} AND calendario.ed52_i_ano = {$me27_i_ano}";
              $sWhere  .= " AND matricula.ed60_c_situacao = 'MATRICULADO' AND matriculaserie.ed221_c_origem = 'S'";

              $sSqlRestricaoIntolerancia = $clmer_restricaointolerancia->sql_query_refeicao(
                                                                                             "",
                                                                                             $sCampos,
                                                                                             $sOrder,
                                                                                             $sWhere
                                                                                           );
              $rsRestricao  = $clmer_restricaointolerancia->sql_record( $sSqlRestricaoIntolerancia );

              if( $clmer_restricaointolerancia->numrows > 0 ) {

                $cor1     = "#DBDBDB";
                $cor2     = "#f3f3f3";
                $cor      = "";
                $primeiro = "";

                for( $c = 0; $c < $clmer_restricaointolerancia->numrows; $c++ ) {

                  db_fieldsmemory( $rsRestricao, $c );

                  if( $cor == $cor1 ) {
                    $cor = $cor2;
                  } else {
                    $cor = $cor1;
                  }

                  if( $primeiro != $me25_i_alimento ) {

                    ?>
                    <tr>
                      <td bgcolor="#999999" colspan="6" class='aluno'>
                        <b><?=$me25_i_alimento?> - <?=$alimento?></b>
                      </td>
                    </tr>
                    <?
                    $primeiro = $me25_i_alimento;
                  }
                  ?>
                  <tr bgcolor="<?=$cor?>">
                    <td class='aluno' align='center'>
                      <?=$ed47_i_codigo?>
                    </td>
                    <td class='aluno' >
                      <?=$ed47_v_nome?>
                    </td>
                    <td class='aluno' >
                      <?=$ed18_c_nome?>
                    </td>
                    <td class='aluno' align='center'>
                      <?=$me33_c_descr?>
                    </td>
                    <td class='aluno' align='center'>
                      <?=$me25_i_alimentosub?> - <?=$alimentosub?>
                    </td>
                    <td class='aluno' align='center'>
                      <a href="javascript:js_abresubalimento(<?=$me01_i_codigo?>,<?=$ed47_i_codigo?>,'<?=$ed47_v_nome?>',<?=$me24_i_codigo?>);">Trocar alimento sustituto
                    </td>
                  </tr>
                  <?php
                }
              } else {

                ?>
                <table border='1px' width="100%" bgcolor="#cccccc" style="" cellspacing="0px">
                  <tr bgcolor="#EAEAEA">
                    <td class='aluno'>Nenhum aluno com restrição alimentar.</td>
                  </tr>
                </table>
                <?php
              }
              ?>
            </table>
          </td>
        </tr>
      </table>
    </fieldset>
  </form>
</body>
</html>
<script>
function js_abresubalimento(refeicao,codigo,nome,restricao) {

  js_OpenJanelaIframe(
                       '',
                       'db_iframe_alimento',
                       'mer1_mer_restriitem001.php?refeicao='+refeicao
                                                +'&me24_i_aluno='+codigo
                                                +'&ed47_v_nome='+nome
                                                +'&me25_i_restricao='+restricao,
                       'Substituição de Alimentos',
                       true,
                       50,
                       200,
                       800,
                       400
                     );
}
</script>