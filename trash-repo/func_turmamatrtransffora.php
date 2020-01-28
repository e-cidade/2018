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

//MODULO: educa��o
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once('libs/db_utils.php');
require_once("dbforms/db_funcoes.php");
require_once("classes/db_turma_classe.php");
require_once("classes/db_serie_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clserie  = new cl_serie;

$clturma  = new cl_turma;
$clturma->rotulo->label("ed57_i_codigo");
$clturma->rotulo->label("ed57_c_descr");
$clturma->rotulo->label("ed57_i_calendario");
$clturma->rotulo->label("ed57_i_turno");
$clturma->rotulo->label("ed57_i_sala");

$clrotulo     = new rotulocampo;
$clrotulo->label("ed31_i_curso");
$escola       = db_getsession("DB_coddepto");
$sFiltroEtapa = "";
$oGet         = db_utils::postMemory($_GET);

/**
 * @todo Revisar l�gica quando for reclassifica��o, pois alunos de fora podem n�o ter matr�cula. Quando 
 * tiverem, verificar a quest�o de estar ativa ou n�o
 */
if ( isset( $oGet->lReclassificacao ) ) {

  /**
   * Turma de Reclassifica��o
   */
  $lVerificaEtapaAnterior = false;
  $oDaoturma              = new cl_turma;
  $oDaoEduParametros      = new cl_edu_parametros();

  $iEscola                = db_getsession("DB_coddepto");
  $oAluno                 = AlunoRepository::getAlunoByCodigo($oGet->aluno);
  $oMatricula             = MatriculaRepository::getMatriculaAtivaPorAluno($oAluno);
  $oEtapa                 = null;
  $sFiltroEtapa           = "";

  if ( !empty( $oMatricula ) ) {

    $oEtapa                 = $oMatricula->getEtapaDeOrigem();
    /**
     * Buscamos como esta configurado o par�metro ed233_reclassificaetapaanterior
     */
    $sSqlParametro          = $oDaoEduParametros->sql_query_file(null, "ed233_reclassificaetapaanterior", null, "ed233_i_escola = {$iEscola}");
    $rsParametro            = $oDaoEduParametros->sql_record($sSqlParametro);

    if ($oDaoEduParametros->numrows > 0) {
      $lVerificaEtapaAnterior = db_utils::fieldsMemory($rsParametro, 0)->ed233_reclassificaetapaanterior == 't';
    }

    $sFiltroEtapa  = " ed11_i_sequencia = " . ( $oEtapa->getOrdem() + 1 );

    if ($lVerificaEtapaAnterior) {
      $sFiltroEtapa = " (ed11_i_sequencia = " . ( $oEtapa->getOrdem() + 1 ) . " or ed11_i_sequencia = ". ($oEtapa->getOrdem()-1) .")";
    }

    $sFiltroEtapa  = " and {$sFiltroEtapa} ";
  }
}

?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td align="center" valign="top">
   <br><b>Turmas em <?=$anocalendario?>:</b><br><br>
   <?
   $sWhere = '';
   $sSep   = '';

   /* Rotina igual a de func_alunocursoturma.php */
   if (isset($lEliminarSeriesAnteriores)) { // Elimina s�ries j� cursadas e com aprova��o do aluno

     $aEnsino     = array();
     $aOrdemSerie = array();

     /* Descubro a �ltima s�rie que o aluno cursou e que foi aprovado */
     $oDaoHistorico = db_utils::getdao('historico');

     $sSubAux       = '  from serie as s ';
     $sSubAux      .= '    where s.ed11_i_ensino = a.ed11_i_ensino ';
     $sSubAux      .= '       and s.ed11_i_sequencia <= a.ed11_i_sequencia ';
     $sSubAux      .= '         order by s.ed11_i_sequencia desc ';
     $sSubAux      .= '           limit 1 ';

     $sCampos       = 'ed11_i_ensino, ';
     $sCampos      .= "case when resfinal = 'A' ";
     $sCampos      .= '  then ed11_i_codigo ';
     $sCampos      .= "  else (select s.ed11_i_codigo $sSubAux) ";// Reprovou, mas na s�rie anterior aprovou(�bvio)
     $sCampos      .= 'end as ed11_i_codigo, ';
     $sCampos      .= "case when resfinal = 'A' ";
     $sCampos      .= '  then ed11_i_sequencia ';
     $sCampos      .= "  else (select s.ed11_i_sequencia $sSubAux) ";// Reprovou, mas na s�rie anterior aprovou(�bvio)
     $sCampos      .= 'end as ed11_i_sequencia ';
     $sOrderBy      = 'ano desc, ed11_i_sequencia desc, resfinal asc limit 1';
     $sSqlTmp       = $oDaoHistorico->sql_query_union(null, $sCampos, $sOrderBy,
                                                      " ed61_i_aluno = $aluno ".
                                                      " and (resfinal = 'A' ".
                                                      "      or exists(select s.ed11_i_codigo $sSubAux))"
                                                     );

     $rsHist        = $oDaoHistorico->sql_record($sSqlTmp);

     /* Busco a �ltima s�rie em que o aluno foi matriculado, e obtenho a anterior, pois se ele
        estava matriculado em uma s�rie, posso garantir que ele concluiu a anterior. N�o estou fazendo verifica��o
        para descobrir se ele aprovou, pois, se sim, j� deve estar registrado no hist�rico. */
     $oDaoMatricula = db_utils::getdao('matricula');
     $sSubAux       = '  from serie as s ';
     $sSubAux      .= '    where s.ed11_i_ensino = serie.ed11_i_ensino ';
     $sSubAux      .= '       and s.ed11_i_sequencia <= serie.ed11_i_sequencia ';
     $sSubAux      .= '         order by s.ed11_i_sequencia desc ';
     $sSubAux      .= '           limit 1 ';

     $sCampos       = "(select s.ed11_i_codigo $sSubAux) as ed11_i_codigo,";
     $sCampos      .= "(select s.ed11_i_sequencia $sSubAux) as ed11_i_sequencia,";
     $sCampos      .= 'ed11_i_ensino ';
     $sOrderBy      = 'calendario.ed52_i_ano desc, matricula.ed60_i_codigo desc, ed11_i_sequencia desc limit 1';
     $sSqlTmp       = $oDaoMatricula->sql_query_bolsafamilia(null, $sCampos, $sOrderBy,
                                                             " ed60_i_aluno = $aluno ".
                                                             " and exists(select s.ed11_i_codigo $sSubAux)"
                                                            );

     $rsMat         = $oDaoMatricula->sql_record($sSqlTmp);

     if ($oDaoHistorico->numrows > 0) {
       $oDadosUltimaEtapa = db_utils::fieldsmemory($rsHist, 0);
     }

     if ($oDaoMatricula->numrows > 0) {

       $oDadosUltimaEtapaTmp = db_utils::fieldsmemory($rsMat, 0);
       /* A etapa com a maior sequ�ncia � considerada a �ltima cursada e conclu�da pelo aluno */
       if (isset($oDadosUltimaEtapa) // Encontrou registros no hist�rico e a etapa tem a seq menor que da matricula
           && $oDadosUltimaEtapa->ed11_i_sequencia < $oDadosUltimaEtapaTmp->ed11_i_sequencia) {

         $oDadosUltimaEtapa = $oDadosUltimaEtapaTmp;

       } else { // Senao, $oDadosUltimaEtapaTmp somente � uitilizado se n�o existe dados no hist�rico

         if (!isset($oDadosUltimaEtapa)) {
           $oDadosUltimaEtapa = $oDadosUltimaEtapaTmp;
         }

       }

     }

     if (isset($oDadosUltimaEtapa)) {

       $aEnsino[]     = $oDadosUltimaEtapa->ed11_i_ensino;
       $aOrdemSerie[] = $oDadosUltimaEtapa->ed11_i_sequencia;

       /* Obtenho as s�ries equivalentes � �ltima s�rie que o aluno cursou e foi aprovado */
       $oDaoSerieEquiv = db_utils::getdao('serieequiv');
       $sSqlTmp        = $oDaoSerieEquiv->sql_query_serieequiv(null, 'ed11_i_ensino, ed11_i_sequencia', '',
                                                               'ed234_i_serie = '.$oDadosUltimaEtapa->ed11_i_codigo
                                                              );

       $rs             = $oDaoSerieEquiv->sql_record($sSqlTmp);
       for ($iCont = 0; $iCont < $oDaoSerieEquiv->numrows; $iCont++) {

         $oDados        = db_utils::fieldsmemory($rs, $iCont);
         $aEnsino[]     = $oDados->ed11_i_ensino;
         $aOrdemSerie[] = $oDados->ed11_i_sequencia;

       }

       $sWhereEns = '';
       $sOrEns    = '';
       $sAndEns   = '';
       for ($iCont = 0; $iCont < count($aEnsino); $iCont++) {

         $sWhereEns .= $sOrEns.'(ed11_i_ensino = '.$aEnsino[$iCont].
                       ' and ed11_i_sequencia >= '.$aOrdemSerie[$iCont].') ';
         $sOrEns     = ' or ';
         $sAndEns    = ' and ';

       }

       // Obtenho todas as bases de continua��o do ensino que o aluno est� atualmente cursando
       $sTmp    = trim($sAndEns.$sWhereEns) == '' ? '' : $sAndEns.'('.str_replace('>=',
                                                                                  '=',
                                                                                  $sWhereEns).')';
       $sSqlTmp = $clturma->sql_query_turma(null, 'distinct (select ed77_i_basecont from escolabase '.
                                            'where escolabase.ed77_i_base = base.ed31_i_codigo) as ed77_i_basecont ',
                                            '', "ed52_c_passivo = 'N' and ed18_i_codigo = $escola $sTmp ".
                                            'and exists(select ed77_i_basecont from escolabase '.
                                            'where escolabase.ed77_i_base = base.ed31_i_codigo '.
                                            'and ed77_i_basecont is not null) '
                                           );
       $rs      = $clturma->sql_record($sSqlTmp);

       $sBases  = '';
       $sVir    = '';
       for ($iCont = 0; $iCont < $clturma->numrows; $iCont++) {

         $sBases .= $sVir.db_utils::fieldsmemory($rs, $iCont)->ed77_i_basecont;
         $sVir    = ', ';

       }
       $sBases     = empty($sBases) ? '' : "base.ed31_i_codigo in ($sBases)";
       $sWhereEns .= empty($sWhereEns) ? $sBases : (empty($sBases) ? '' : 'or '.$sBases);

       if ( !empty($sWhereEns) && !isset( $oGet->lReclassificacao ) ) {

         $sWhere .= $sSep.'('.$sWhereEns.')';
         $sSep    = ' and ';
       }

     } // Fim if existe registro da �ltima etapa cursada e conclu�da

   } // Fim if $lEliminarSeriesAnteriores

   if (isset($turmasprogressao) && $turmasprogressao == 'f') {

     $sWhere .= "{$sSep} ed57_i_tipoturma <> 6";
     $sSep    = " and ";
   }

   $campos = "DISTINCT turma.ed57_i_codigo,                        \n";
   $campos.= "turma.ed57_c_descr,                                  \n";
   $campos.= "fc_nomeetapaturma(ed57_i_codigo) as ed11_c_descr,    \n";
   $campos.= "calendario.ed52_c_descr,                             \n";
   $campos.= "calendario.ed52_i_ano,                               \n";
   $campos.= "cursoedu.ed29_c_descr,                               \n";
   $campos.= "base.ed31_c_descr,                                   \n";
   $campos.= "turno.ed15_c_nome,                                   \n";
   $campos.= "turma.ed57_i_nummatr,                                \n";
   $campos.= "turma.ed57_i_numvagas,                               \n";
   $campos.= "fc_codetapaturma(ed57_i_codigo) as ed11_i_codigo,    \n";
   $campos.= "fc_seqetapaturma(ed57_i_codigo) as ed11_i_sequencia, \n";
   $campos.= "calendario.ed52_i_codigo ,                           \n";
   $campos.= "cursoedu.ed29_i_codigo,                              \n";
   $campos.= "base.ed31_i_codigo,                                  \n";
   $campos.= "turno.ed15_i_codigo,                                 \n";
   $campos.= "calendario.ed52_d_inicio,                            \n";
   $campos.= "calendario.ed52_d_fim                                \n";
   
   if ( !isset($pesquisa_chave) ) {

     $sSqlReg  = " AND not exists                                                         \n";
     $sSqlReg .= " (SELECT *                                                              \n";
     $sSqlReg .= "    FROM histmpsdisc                                                    \n";
     $sSqlReg .= "         inner join historicomps on ed62_i_codigo = ed65_i_historicomps \n";
     $sSqlReg .= "         inner join historico on ed61_i_codigo = ed62_i_historico       \n";
     $sSqlReg .= "   WHERE ed61_i_aluno          = $aluno                                 \n";
     $sSqlReg .= "     AND ed61_i_curso          = ed29_i_codigo                          \n";
     $sSqlReg .= "     AND ed62_i_serie          = $codserietransf                        \n";
     $sSqlReg .= "     AND ed62_c_resultadofinal = 'P'                                    \n";
     $sSqlReg .= "     AND exists(select *                                                \n";
     $sSqlReg .= "                  from regencia                                         \n";
     $sSqlReg .= "                 where ed59_i_turma      = ed57_i_codigo                \n";
     $sSqlReg .= "                   and ed59_i_serie      = $codserietransf              \n";
     $sSqlReg .= "                   and ed59_i_disciplina = ed65_i_disciplina))          \n";

     $sql      = $clturma->sql_query_turmaserie('', $campos, "ed57_c_descr", " ed52_c_passivo = 'N' ".
                                                "AND ed57_i_escola = $escola AND ed52_i_ano = $anocalendario ".
                                                $sSep . $sWhere . /*$sSqlReg .*/ $sFiltroEtapa
                                          );

     db_lovrot( $sql,12,"()","",$funcao_js );

   }
   ?>
  </td>
 </tr>
</table>
</body>
</html>