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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_matrequi_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clmatrequi = new cl_matrequi;
$clmatrequi->rotulo->label("m40_codigo");
$clmatrequi->rotulo->label("m40_codigo");
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
        <table width="35%" border="0" align="center" cellspacing="0">
          <form name="form2" method="post" action="" >
            <tr>
              <td width="4%" align="right" nowrap title="<?=$Tm40_codigo?>" nowrap>
                <?=$Lm40_codigo?>
              </td>
              <td width="96%" align="left" nowrap>
                <?
                db_input("m40_codigo",10,$Im40_codigo,true,"text",4,"","chave_m40_codigo");
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap>
                <b>Exibir Requisições de Exercícios Anteriores:</b>
              </td>
              <td>
                <?
                db_select("trazoutrozexercicios", array('n'=>'Não','s'=>'Sim'), true,1);
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="2" align="center">
                <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar" />
                <input name="limpar" type="reset" id="limpar" value="Limpar" />
                <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_atendrequi.hide();" />
              </td>
            </tr>
          </form>
        </table>
      </td>
    </tr>
    <tr>
      <td align="center" valign="top">
        <?

        // filtro para trazer requisição não automáticas
        $where  = "";
        if (!isset($trazoutrozexercicios)) {
          $trazoutrozexercicios = 'n';
        }
        // filtro por departamento
        if (isset($sFiltro)) {
          if ($sFiltro == "almox") {


            $iDepartamentoSessao = db_getsession("DB_coddepto");
            $where .= "    (m40_depto = {$iDepartamentoSessao} ";
            $where .= " or m40_almox = (select m91_codigo from db_almox where m91_depto = {$iDepartamentoSessao})) ";
          }
        }
        if ($trazoutrozexercicios == 'n') {
          $where .= " and cast(extract(year from m40_data) as integer) = ".db_getsession('DB_anousu');
        }
        // filtra todas as requisições pelas não atendidas e as parcialmente atendidas
        $where .= " group by matrequi.m40_codigo,";
        $where .= "		    matrequi.m40_login,";
        $where .= "		    matrequi.m40_auto,";
        $where .= " 	        matrequi.m40_data,";
        $where .= " 	        matrequi.m40_depto,";
        $where .= "		    matrequi.m40_hora,";
        $where .= "		    matrequi.m40_obs,";
        $where .= "		    matrequiitem.m41_quant,";
        $where .= "		    matrequiitem.m41_codigo";
        $where .= "		    having";
        $where .= "          coalesce(matrequiitem.m41_quant - ";
        $where .= "                   ((select coalesce(sum(atendrequiitem.m43_quantatend-coalesce(m46_quantdev,0)),0)";
        $where .= "                     from atendrequiitem";
        $where .= "                      left join matestoquedevitem on atendrequiitem.m43_codigo = m46_codatendrequiitem";
        $where .= "                     where m43_codmatrequiitem = m41_codigo) + ";
        $where .= "                    (select coalesce(sum(m103_quantanulada),0) from matanulitem";
        $where .= "                     left join matanulitemrequi on matanulitemrequi.m102_matanulitem = matanulitem.m103_codigo";
        $where .= "                     where m102_matrequiitem = m41_codigo))";
        $where .= "                   ,0) > 0";

        /**
         * Verifica se foi informado o parametro de saldo gerado, se for passado irá exibir
         * somente as requisições com Quantidades requisitadas menor que as quantidades atendidas.
         */
        if (isset($lSaldoZerado) && $lSaldoZerado) {
          $where .= ' and m41_quant >
                      (select coalesce(sum(m43_quantatend),0)
                       from atendrequiitem
                       left join matestoquedevitem on atendrequiitem.m43_codigo = m46_codatendrequiitem
                       where m43_codmatrequiitem = m41_codigo)';
        }


        if(!isset($pesquisa_chave)){

          if(isset($campos)==false){

            if(file_exists("funcoes/db_func_matrequi.php")==true){
              include("funcoes/db_func_matrequi.php");
            }else{
              $campos = "matrequi.*";
            }
          }

          if(isset($chave_m40_codigo) && (trim($chave_m40_codigo)!="") ){
            $sql = $clmatrequi->sql_query_atentimentos($chave_m40_codigo,$campos,"m40_codigo desc","m40_codigo=$chave_m40_codigo and $where");
          }else{
            $sql = $clmatrequi->sql_query_atentimentos("",$campos,"m40_codigo desc","$where");
          }

          db_lovrot($sql,15,"()","",$funcao_js);
        }else{
          if($pesquisa_chave!=null && $pesquisa_chave!=""){
            $result = $clmatrequi->sql_record($clmatrequi->sql_query_atentimentos($pesquisa_chave,"*","m40_codigo desc","m40_codigo=$pesquisa_chave and $where"));
            if($clmatrequi->numrows!=0){
              db_fieldsmemory($result,0);
              echo "<script>".$funcao_js."('$m40_codigo',false);</script>";
            }else{
              echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
            }
          }else{
            echo "<script>".$funcao_js."('',false);</script>";
          }
        }
        ?>
      </td>
    </tr>
  </table>
  </body>
  </html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>