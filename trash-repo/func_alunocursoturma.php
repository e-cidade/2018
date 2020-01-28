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
require_once('libs/db_utils.php');
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_turma_classe.php");
require_once("classes/db_calendario_classe.php");
require_once("classes/db_turno_classe.php");
require_once("classes/db_cursoedu_classe.php");
require_once("classes/db_procedimento_classe.php");
require_once("classes/db_sala_classe.php");
require_once("classes/db_edu_parametros_classe.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clturma = new cl_turma;
$clcalendario = new cl_calendario;
$clturno = new cl_turno;
$clcurso = new cl_curso;
$clprocedimento = new cl_procedimento;
$clsala = new cl_sala;
$clrotulo = new rotulocampo;
$clturma->rotulo->label("ed57_i_codigo");
$clturma->rotulo->label("ed57_c_descr");
$clturma->rotulo->label("ed57_i_calendario");
$clturma->rotulo->label("ed57_i_turno");
$clrotulo->label("ed31_i_curso");
$clrotulo->label("ed220_i_procedimento");
$clturma->rotulo->label("ed57_i_sala");
$escola = db_getsession("DB_coddepto");

/**
 * Elimina series anteriores
 */
$oDaoEduParametros  = new cl_edu_parametros;
$sSqlEduparametros  = $oDaoEduParametros->sql_query_file(null, "ed233_c_consistirmat", null, "ed233_i_escola = {$escola}");
$rsSqlEduparametros = $oDaoEduParametros->sql_record($sSqlEduparametros);

if ($oDaoEduParametros->numrows > 0) {
  $lEliminarSeriesAnteriores = db_utils::fieldsMemory($rsSqlEduparametros, 0)->ed233_c_consistirmat;
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
  <td height="63" align="center" valign="top">
   <table width="35%" border="0" align="center" cellspacing="0">
    <form name="form1" method="post" action="" >
    <tr>
     <td width="4%" nowrap title="<?=$Ted57_i_codigo?>">
      <?=$Led57_i_codigo?>
      <?db_input("ed57_i_codigo",10,$Ied57_i_codigo,true,"text",4,"","chave_ed57_i_codigo");?>
     </td>
     <td width="4%" nowrap title="<?=$Ted57_c_descr?>">
      <?=$Led57_c_descr?>
      <?db_input("ed57_c_descr",10,$Ied57_c_descr,true,"text",4,"","chave_ed57_c_descr");?>
     </td>
     <td width="4%" nowrap title="<?=$Ted31_i_curso?>">
      <?=$Led31_i_curso?>
      <?
      $result_cur = $clcurso->sql_record($clcurso->sql_query_file("","ed29_i_codigo,ed29_c_descr","ed29_c_descr"));
      db_selectrecord("ed31_i_curso",$result_cur,"","","","chave_ed31_i_curso","","  ","",1);
      ?>
     </td>
     <td width="4%" nowrap title="<?=$Ted57_i_sala?>">
      <!--
      <?=$Led57_i_sala?>
      <?
      $result_sala = $clsala->sql_record($clsala->sql_query_file("","ed16_i_codigo,ed16_c_descr","ed16_c_descr"," ed16_i_escola = $escola"));
      if ($clsala->numrows==0) {
       $x = array(''=>'NENHUM REGISTRO');
       db_select('ed57_i_sala',$x,true,1,"");
      } else {
       db_selectrecord("ed57_i_sala",$result_sala,"","","","chave_ed57_i_sala","","  ","",1);
      }
      ?>
      -->
     </td>
    </tr>
    <tr>
     <td width="4%" nowrap title="<?=$Ted57_i_turno?>">
      <?=$Led57_i_turno?>
      <?
      $sql_tur = "SELECT ed15_i_codigo,ed15_c_nome,ed15_i_sequencia
                  FROM turno
                   inner join periodoescola on periodoescola.ed17_i_turno = turno.ed15_i_codigo
                  WHERE periodoescola.ed17_i_escola = $escola
                  GROUP BY ed15_i_codigo,ed15_c_nome,ed15_i_sequencia
                  ORDER BY ed15_i_sequencia
                  ";
      $result_tur = pg_query($sql_tur);
      $linhas_tur = pg_num_rows($result_tur);
      if ($linhas_tur==0) {
       $x = array(''=>'NENHUM REGISTRO');
       db_select('ed57_i_turno',$x,true,1,"");
      } else {
       db_selectrecord("ed57_i_turno",$result_tur,"","","","chave_ed57_i_turno","","  ","",1);
      }
      ?>
     </td>
     <td width="4%" nowrap title="<?=$Ted57_i_calendario?>">
      <?=$Led57_i_calendario?>
      <?
      $result_cal = $clcalendario->sql_record($clcalendario->sql_query_calescola("","ed52_i_codigo,ed52_c_descr","ed52_i_ano desc","  ed52_c_passivo = 'N' AND ed38_i_escola = $escola"));
      if ($clcalendario->numrows==0) {
       $x = array(''=>'NENHUM REGISTRO');
       db_select('ed57_i_calendario',$x,true,1,"");
      } else {
       db_selectrecord("ed57_i_calendario",$result_cal,"","","","chave_ed57_i_calendario","","  ","",1);
      }
      ?>
     </td>
     <td width="4%" nowrap title="<?=$Ted220_i_procedimento?>">
      <?=$Led220_i_procedimento?>
      <?
      $result_proc = $clprocedimento->sql_record($clprocedimento->sql_query_procturma("","ed40_i_codigo,ed40_c_descr","ed40_c_descr"," ed86_i_escola = $escola GROUP BY ed40_i_codigo,ed40_c_descr"));
      if ($clprocedimento->numrows==0) {
       $x = array(''=>'NENHUM REGISTRO');
       db_select('ed220_i_procedimento',$x,true,1,"");
      } else {
       db_selectrecord("ed220_i_procedimento",$result_proc,"","","","chave_ed220_i_procedimento","","  ","",1);
      }
      ?>
     </td>
    </tr>
    <tr>
     <td colspan="4" align="center">
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_turma.hide();">
     </td>
    </tr>
    </form>
   </table>
  </td>
 </tr>
 <tr>
  <td align="center" valign="top">
   <?
   $campos = "DISTINCT turma.ed57_i_codigo,
              turma.ed57_c_descr,
              fc_nomeetapaturma(ed57_i_codigo) as ed11_c_descr,
              calendario.ed52_c_descr,
              calendario.ed52_i_ano,
              cursoedu.ed29_c_descr,
              base.ed31_c_descr,
              turno.ed15_c_nome,
              turma.ed57_i_nummatr,
              turma.ed57_i_numvagas,
              fc_codetapaturma(ed57_i_codigo) as ed11_i_codigo,
              fc_seqetapaturma(ed57_i_codigo) as ed11_i_sequencia,
              calendario.ed52_i_codigo ,
              cursoedu.ed29_i_codigo,
              base.ed31_i_codigo,
              turno.ed15_i_codigo,
              calendario.ed52_d_inicio,
              calendario.ed52_d_fim
             ";

   $sWhere = '';
   $sSep   = '';
   if (isset($turmasprogressao) && $turmasprogressao == 'f') {

     $sWhere .= " ed57_i_tipoturma <> 6";
     $sSep    = " and ";
   }
   /* Rotina igual a de func_turmamatrtransffora.php */
   if ($lEliminarSeriesAnteriores == "S") { // Elimina s�ries j� cursadas e com aprova��o do aluno

     $aEnsino     = array();
     $aOrdemSerie = array();

     /* Descubro a �ltima s�rie que o aluno cursou e que foi aprovado */
     $oDaoHistorico = db_utils::getdao('historico');

     $sSubAux       = '  from serie as s ';
     $sSubAux      .= '    where s.ed11_i_ensino = a.ed11_i_ensino ';
     $sSubAux      .= '       and s.ed11_i_sequencia < a.ed11_i_sequencia ';
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
     $sSubAux      .= '       and s.ed11_i_sequencia < serie.ed11_i_sequencia ';
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
           //echo  $oDadosUltimaEtapa;
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
       $sTmp    = trim($sAndEns.$sWhereEns) == '' ? '' : $sAndEns.'('.str_replace('>=', '=', $sWhereEns).')';
       $sSqlTmp = $clturma->sql_query_turma(null, 'distinct (select ed77_i_basecont from escolabase '.
                                            'where escolabase.ed77_i_base = base.ed31_i_codigo) as ed77_i_basecont ',
                                            '', "ed52_c_passivo = 'N' and ed18_i_codigo = $escola $sTmp ".
                                            'and exists(select ed77_i_basecont from escolabase '.
                                            'where escolabase.ed77_i_base = base.ed31_i_codigo '.
                                            'and ed77_i_basecont is not null) '
                                           );
       $rs      = $clturma->sql_record($sSqlTmp);
//echo $sSqlTmp;
       $sBases  = '';
       $sVir    = '';
       for ($iCont = 0; $iCont < $clturma->numrows; $iCont++) {

         $sBases .= $sVir.db_utils::fieldsmemory($rs, $iCont)->ed77_i_basecont;
         $sVir    = ', ';

       }
       $sBases = empty($sBases) ? '' : "base.ed31_i_codigo in ($sBases)";
       $sWhereEns .= empty($sWhereEns) ? $sBases : (empty($sBases) ? '' : 'or '.$sBases);

       if (!empty($sWhereEns)) {

         $sWhere .= $sSep.'('.$sWhereEns.')';
         $sSep    = ' and ';

       }

     } // Fim if existe registro da �ltima etapa cursada e conclu�da

   } // Fim if $lEliminarSeriesAnteriores

   if (!isset($pesquisa_chave)) {

     //echo "<br><br> SHOLIUKEN";

     $esc   = false;
     if (isset($chave_ed57_i_codigo) && (trim($chave_ed57_i_codigo) != '')) {

       $sWhere .= $sSep."ed57_i_codigo = $chave_ed57_i_codigo";
       $esc     = true;
       $sSep    = ' and ';

     }
     if (isset($chave_ed57_c_descr) && (trim($chave_ed57_c_descr) != '')) {

       $sWhere .= $sSep."ed57_c_descr like '$chave_ed57_c_descr%'";
       $esc     = true;
       $sSep    = ' and ';

     }
     if (isset($chave_ed57_i_calendario) && (trim($chave_ed57_i_calendario) != '')) {

       $sWhere .= $sSep."ed57_i_calendario = $chave_ed57_i_calendario";
       $esc     = true;
       $sSep    = ' and ';

     }
     if (isset($chave_ed57_i_turno) && (trim($chave_ed57_i_turno) != '')) {

       $sWhere .= $sSep."ed57_i_turno = $chave_ed57_i_turno";
       $esc     = true;
       $sSep    = ' and ';

     }
     if (isset($chave_ed31_i_curso) && (trim($chave_ed31_i_curso) != '')) {

       $sWhere .= $sSep."ed31_i_curso = $chave_ed31_i_curso";
       $esc     = true;
       $sSep    = ' and ';

     }
     if (isset($chave_ed220_i_procedimento) && (trim($chave_ed220_i_procedimento) != '')) {

       $sWhere .= $sSep."ed220_i_procedimento = $chave_ed220_i_procedimento";
       $esc     = true;
       $sSep    = ' and ';

     }
     if (isset($chave_ed57_i_sala) && (trim($chave_ed57_i_sala) != '')) {

       $sWhere .= $sSep."ed57_i_sala = $chave_ed57_i_sala";
       $esc     = true;
       $sSep    = ' and ';

     }
     if ($esc) {

       $oDaoAlunopossib = db_utils::getdao('alunopossib');
       $sSqlPossib = $oDaoAlunopossib->sql_query(null, "ed79_i_serie", ""," ed56_i_aluno = $aluno ");
       $rsPossib   = $oDaoAlunopossib->sql_record($sSqlPossib);
       if($oDaoAlunopossib->numrows > 0){

       $oDadosPossib = db_utils::fieldsmemory($rsPossib, 0);
       $sSqlReg  = " AND not exists";
       $sSqlReg .= " (SELECT * ";
       $sSqlReg .= " FROM histmpsdisc";
       $sSqlReg .= "  inner join historicomps on ed62_i_codigo = ed65_i_historicomps  ";
       $sSqlReg .= "  inner join historico on ed61_i_codigo = ed62_i_historico        ";
       $sSqlReg .= " WHERE ed61_i_aluno  = $aluno                                     ";
       $sSqlReg .= " AND ed61_i_curso = ed29_i_codigo                                 ";
       $sSqlReg .= " AND ed62_i_serie = ".$oDadosPossib->ed79_i_serie                  ;
       $sSqlReg .= " AND ed62_c_resultadofinal = 'P'                                  ";
       $sSqlReg .= " AND exists(select * from regencia                                ";
       $sSqlReg .= "            where ed59_i_turma = ed57_i_codigo                    ";
       $sSqlReg .= "            and ed59_i_serie = ".$oDadosPossib->ed79_i_serie       ;
       $sSqlReg .= "            and ed59_i_disciplina = ed65_i_disciplina))           ";

       }else{
       	 $sSqlReg = '';
       }


       $sql = $clturma->sql_query_turmaserie('', $campos, 'ed57_c_descr',
                                             " ed52_c_passivo = 'N' AND ed57_i_escola = $escola ".
                                             $sSep.$sWhere.$sSqlReg
                                            );


     }

     db_lovrot(@$sql,12,"()","",$funcao_js);

   } else {

     if ($pesquisa_chave!=null && $pesquisa_chave != '') {
    echo "<br>TURMA".$sSql = $clturma->sql_query_turmaserie('', $campos, 'ed57_c_descr', " ed52_c_passivo = 'N' ".
                                             "and ed57_i_codigo = $pesquisa_chave and ed57_i_escola = $escola".
                                             $sSep.$sWhere
                                            );
      $result = $clturma->sql_record($sSql);
      if ($clturma->numrows!=0) {
       db_fieldsmemory($result,0);
       echo "<script>".$funcao_js."($ed57_i_codigo,'$ed57_c_descr','$ed11_c_descr','$ed52_c_descr','$ed29_c_descr','$ed31_c_descr','$ed15_c_nome','$ed11_i_codigo',$ed52_i_codigo,$ed29_i_codigo,$ed31_i_codigo,$ed15_i_codigo,$ed57_i_nummatr,$ed57_i_numvagas,'$ed11_i_sequencia',$ed52_i_ano,'$ed52_d_inicio','$ed52_d_fim');</script>";
      } else {
       echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") n�o Encontrado','','','','','','','','','','',true);</script>";
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