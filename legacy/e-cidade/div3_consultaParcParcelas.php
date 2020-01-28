<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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

require_once "libs/db_stdlib.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once "dbforms/db_funcoes.php";
require_once "libs/db_utils.php";

$cltermo = new cl_termo();
$oGet = db_utils::postmemory($_GET);

$iParcelamento = intval($oGet->parcelamento);
$sSqlTermo = $cltermo->sql_query_file( null,
                                       "termo.v07_numpre, exists (select * from termoanu where v09_parcel = v07_parcel) as anulado",
                                       null,
                                       " v07_parcel = {$iParcelamento}" );
$rsTermo   = $cltermo->sql_record($sSqlTermo);

if (!$rsTermo) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Parcelamento não encontrado.");
}

$oTermo = db_utils::fieldsMemory($rsTermo, 0);
$oTermo->anulado = ($oTermo->anulado == 't');
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
  <link href="estilos.css" rel="stylesheet" type="text/css"/>
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body class="body-default">
  <div class="container">
    <table>
      <tr>
        <td align="center">
          <?php
            $camposDetalhe      = " 'a' as DB_parametro,";
            $camposDetalheGroup = " DB_parametro,";

            $funcao_js          = "js_mudaFiltro|DB_parametro";

            if (isset($oGet->tipoFiltro) && $oGet->tipoFiltro == 'a') {
              $camposDetalheGroup = " DB_parametro,a.k00_receit,a.k00_descr,a.k02_descr,";
              $camposDetalhe      = "'s' as DB_parametro, a.k00_receit,a.k00_descr,a.k02_descr, ";
            }

            $sqlTermoParcelas  = " select  a.k00_numpre, a.k00_numpar, $camposDetalhe a.status, sum(a.k00_valor) as k00_valor ";
            $sqlTermoParcelas .= "   from ( ";
            $sqlTermoParcelas .= " select  arrecad.k00_numpre,  arrecad.k00_numpar, arrecad.k00_receit, k00_descr, k02_descr, 'Aberto'::varchar as status ,arrecad.k00_valor";
            $sqlTermoParcelas .= "    from arrecad   ";
            $sqlTermoParcelas .= "         inner join arretipo on arretipo.k00_tipo   = arrecad.k00_tipo ";
            $sqlTermoParcelas .= "         inner join tabrec   on tabrec.k02_codigo   = arrecad.k00_receit    ";
            $sqlTermoParcelas .= "         left  join arrepaga on arrepaga.k00_numpre = arrecad.k00_numpre ";
            $sqlTermoParcelas .= "                            and arrepaga.k00_numpar = arrecad.k00_numpar ";
            $sqlTermoParcelas .= "                            and arrepaga.k00_receit = arrecad.k00_receit ";
            $sqlTermoParcelas .= "         left join arrecant  on arrecant.k00_numpre = arrecad.k00_numpre ";
            $sqlTermoParcelas .= "                            and arrecant.k00_numpar = arrecad.k00_numpar ";
            $sqlTermoParcelas .= "                            and arrecant.k00_receit = arrecad.k00_receit ";
            $sqlTermoParcelas .= "   where arrepaga.k00_numpre is null and arrecant.k00_numpre is null and arrecad.k00_numpre = {$oTermo->v07_numpre} ";
            $sqlTermoParcelas .= "union  ";
            $sqlTermoParcelas .= "  select arreold.k00_numpre, arreold.k00_numpar, arreold.k00_receit, k00_descr, k02_descr, " .
                                   ($oTermo->anulado ? "'Anulado'" : "'Reparcelado'") . "::varchar as status, arreold.k00_valor ";
            $sqlTermoParcelas .= "    from arreold   ";
            $sqlTermoParcelas .= "         inner join arretipo on arretipo.k00_tipo   = arreold.k00_tipo ";
            $sqlTermoParcelas .= "         inner join tabrec   on tabrec.k02_codigo   = arreold.k00_receit    ";
            $sqlTermoParcelas .= "         left  join arrepaga on arrepaga.k00_numpre = arreold.k00_numpre ";
            $sqlTermoParcelas .= "                            and arrepaga.k00_numpar = arreold.k00_numpar ";
            $sqlTermoParcelas .= "                            and arrepaga.k00_receit = arreold.k00_receit ";
            $sqlTermoParcelas .= "         left join arrecant  on arrecant.k00_numpre = arreold.k00_numpre ";
            $sqlTermoParcelas .= "                            and arrecant.k00_numpar = arreold.k00_numpar ";
            $sqlTermoParcelas .= "                            and arrecant.k00_receit = arreold.k00_receit ";
            $sqlTermoParcelas .= "   where arrepaga.k00_numpre is null and arrecant.k00_numpre is null and arreold.k00_numpre = {$oTermo->v07_numpre}";
            $sqlTermoParcelas .= "union  ";
            $sqlTermoParcelas .= "  select arrecant.k00_numpre, arrecant.k00_numpar, arrecant.k00_receit, k00_descr, k02_descr, ";
            $sqlTermoParcelas .= "         case ";
            $sqlTermoParcelas .= "           when arrepaga.k00_numpre is not null ";
            $sqlTermoParcelas .= "             then 'Pago'::varchar  ";
            $sqlTermoParcelas .= "           else           ";
            $sqlTermoParcelas .= "             'Cancelado'::varchar  ";
            $sqlTermoParcelas .= "         end as status, arrecant.k00_valor ";
            $sqlTermoParcelas .= "    from arrecant         ";
            $sqlTermoParcelas .= "         inner join arretipo on arretipo.k00_tipo   = arrecant.k00_tipo   ";
            $sqlTermoParcelas .= "         inner join tabrec   on tabrec.k02_codigo   = arrecant.k00_receit ";
            $sqlTermoParcelas .= "         left  join arrepaga on arrepaga.k00_numpre = arrecant.k00_numpre ";
            $sqlTermoParcelas .= "                            and arrepaga.k00_numpar = arrecant.k00_numpar ";
            $sqlTermoParcelas .= "                            and arrepaga.k00_receit = arrecant.k00_receit ";
            $sqlTermoParcelas .= "         left join arreold   on arreold.k00_numpre  = arrecant.k00_numpre ";
            $sqlTermoParcelas .= "                            and arreold.k00_numpar  = arrecant.k00_numpar ";
            $sqlTermoParcelas .= "                            and arreold.k00_receit  = arrecant.k00_receit ";
            $sqlTermoParcelas .= "   where arreold.k00_numpre is null and arrecant.k00_numpre = {$oTermo->v07_numpre} ";
            $sqlTermoParcelas .= " ) as a ";
            $sqlTermoParcelas .= " group by a.k00_numpre, a.k00_numpar,$camposDetalheGroup a.status ";
            $sqlTermoParcelas .= " order by a.k00_numpre, a.k00_numpar,$camposDetalheGroup a.status ";

          ?>
          <form id="agrupamento" name="agrupamento" type="post">
            <label class="bold" for="tipoFiltro">Agrupar por:</label>
            <?php
              $array = array("s"=>"Parcela","a"=>"Receita");
              db_select('tipoFiltro', $array, true, "1", "onChange='js_mudaFiltro(this.value);'");

              $parcelamento = $iParcelamento;
              db_input("parcelamento", 1, 1, true, "hidden");
            ?>
          </form>
          <?php
            $arrayTot["k00_valor"]  = "k00_valor";
            $arrayTot["totalgeral"] = "status";
            db_lovrot($sqlTermoParcelas,50,"()","","$funcao_js","","NoMe", array(),false, $arrayTot);
          ?>
        </td>
      </tr>
    </table>
  </div>
  <script type="text/javascript">

    document.getElementById('tipoFiltro').addEventListener("change", function() {
        document.getElementById('agrupamento').submit();
    });
  </script>
</body>
</html>