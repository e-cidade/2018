<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

//MODULO: educação
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($_POST);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clturma      = new cl_turma;
$clserie      = new cl_serie;
$clserieequiv = new cl_serieequiv;
$escola       = db_getsession("DB_coddepto");

$sCamposTurma = "turma.*, calendario.ed52_i_ano, ensino.ed10_i_codigo, ensino.ed10_c_descr";
$sSqlTurma    = $clturma->sql_query("", $sCamposTurma, "", " ed57_i_codigo = {$turma}");
$result       = $clturma->sql_record($sSqlTurma);

db_fieldsmemory($result,0);
$sSqlSerie = $clserie->sql_query_file( "", "ed11_c_descr as nomeetapa", "", "ed11_i_codigo = {$etapaorig}" );
$result2   = $clserie->sql_record( $sSqlSerie );
db_fieldsmemory($result2,0);


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="scripts/scripts.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td height="63" align="center" valign="top">
   <br>
    <b>Turmas compatíveis com a turma <?=$ed57_c_descr?> em
    <select name="ensinos" onchange="js_trocaensino(this.value);" style="height:15px;font-size:9px;">
     <option value="1" <?=@$pesquisa_chave == 1 ? "selected" : ""?>><?=$ed10_c_descr?></option>
     <?php
       if (!isset($apenasensinodaturma)) {
         echo "<option value='2' ".(isset( $pesquisa_chave ) && $pesquisa_chave == 2 ? "selected" : "" ).">OUTROS ENSINOS</option>";
       }
     ?>
    </select>
  </td>
   <td>
     <input id="iMatricula" name="iMatricula" type="hidden" value="<?=isset( $matricula ) ? $matricula : '';?>" />
   </td>
 </tr>
 <tr>
  <td align="center" valign="top">
   <?php
   $sWhere = '';
   if (isset($turmasprogressao) && $turmasprogressao == 'f') {
     $sWhere .= " and ed57_i_tipoturma <> 6";
   }

   $sListaEtapas = $etapaorig;
   if ( !empty($matricula)  && (isset($ensinos) && $ensinos == 2)) {

     $oMatricula   = MatriculaRepository::getMatriculaByCodigo($matricula);
     $aProgressoes = $oMatricula->getAluno()->getProgressaoParcial();

     $aEtapasProgressao = array();

     foreach ($aProgressoes as $oProgressao) {

       $aEtapasProgressao[] = $oProgressao->getEtapa()->getCodigo();
       $aEtapasProximas     = EtapaRepository::getEtapasPosteriores($oProgressao->getEtapa());
       foreach($aEtapasProximas as $oProximaEtapa) {

         if ($oProgressao->getEtapa()->getOrdem() + 1 == $oProximaEtapa->getOrdem()) {
           $aEtapasProgressao[] = $oProximaEtapa->getCodigo();
         }
       }
     }

     if (count($aEtapasProgressao) > 0) {
       $sListaEtapas .= ", ". implode(", ", $aEtapasProgressao);
     }
   }

   $sSqlEquivalentes  = "SELECT array_to_string(array_accum(ed234_i_serieequiv), ',') as seriesequivalentes";
   $sSqlEquivalentes .= "  FROM serieequiv ";
   $sSqlEquivalentes .= " WHERE ed234_i_serie in ({$sListaEtapas})";

   $result = db_query($sSqlEquivalentes);
   db_fieldsmemory($result,0);


   $seriesequivalentes = !empty($seriesequivalentes) ? "{$sListaEtapas} , {$seriesequivalentes}" : $sListaEtapas;

   $campos = "DISTINCT turma.ed57_i_codigo,
              turma.ed57_c_descr,
              fc_nomeetapaturma(ed57_i_codigo) as nomeetapa,
              calendario.ed52_c_descr as ed57_i_calendario,
              trim(ed10_c_descr) as ed10_c_descr,
              cursoedu.ed29_c_descr as ed31_i_curso,
              turno.ed15_c_nome as ed57_i_turno,
              sala.ed16_c_descr as ed57_i_sala,
              formaavaliacao.ed37_c_descr as dl_Avaliação,
              fc_codetapaturma(ed57_i_codigo) as db_codetapa
             ";
   $repassa = array();
   if(isset($chave_ed217_i_codigo)){
     $repassa = array("ensinos"=>$ensinos);
   }
   if(!isset($pesquisa_chave)){

     $sWhereTurma  = "     ed57_i_escola = {$escola} AND ed57_i_calendario = {$ed57_i_calendario}";
     $sWhereTurma .= " AND ed10_i_codigo = {$ed10_i_codigo} AND ed223_i_serie in ({$seriesequivalentes})";
     $sWhereTurma .= " AND ed57_i_codigo not in ({$turma}) AND ed59_c_encerrada = 'N' {$sWhere}";
     $sql          = $clturma->sql_query_turmaserie_regencia( "", $campos, "ed57_c_descr", $sWhereTurma );
     db_lovrot( $sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa, false );
   } else {

     $sWhereTurma  = "     ed57_i_escola = {$escola} AND ed52_i_ano = {$ed52_i_ano} AND ed10_i_codigo != {$ed10_i_codigo}";
     $sWhereTurma .= " AND ed223_i_serie in ({$seriesequivalentes}) AND ed59_c_encerrada = 'N' {$sWhere}";
     $sql          = $clturma->sql_query_turmaserie_regencia( "", $campos, "ed57_c_descr", $sWhereTurma );
     db_lovrot( $sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa, false );
   }
   ?>
  </td>
 </tr>
</table>
</body>
</html>
<script>
function js_trocaensino( valor ) {

  var sMatricula = '';

  if ( !empty( $F('iMatricula') ) ) {
    sMatricula = "&matricula=" + $F('iMatricula');
  }

  if ( valor == 1 ) {

    location.href = "func_turmatransf.php?ensinos=1"
                                       +"&turma=<?=$turma?>"
                                       +"&etapaorig=<?=$etapaorig?>"
                                       +"&funcao_js=parent.js_mostraturma1|ed57_i_codigo|ed57_c_descr|nomeetapa"
                                                                        +"|ed10_c_descr|ed57_i_calendario|codetapa"
                                       +sMatricula;


  } else {

    location.href = "func_turmatransf.php?ensinos=2"
                                       +"&pesquisa_chave=" + valor
                                       +"&turma=<?=$turma?>"
                                       +"&etapaorig=<?=$etapaorig?>"
                                       +"&codensino=<?=$ed10_i_codigo?>&funcao_js=parent.js_mostraturma1|ed57_i_codigo"
                                                                                                      +"|ed57_c_descr"
                                                                                                      +"|nomeetapa"
                                                                                                      +"|ed10_c_descr"
                                                                                                      +"|ed57_i_calendario"
                                                                                                      +"|codetapa"
                                       +sMatricula;
  }
}
</script>