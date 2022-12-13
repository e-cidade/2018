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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

/**
 * Parâmetros necessários:
 * -> codigo_aluno
 * -> funcao_js
 */
$oGet                   = db_utils::postMemory($_GET);
$lVerificaEtapaAnterior = false;
$oDaoturma              = new cl_turma;
$oDaoEduParametros      = new cl_edu_parametros();
/**
 * 
 */
$iEscola                = db_getsession("DB_coddepto");
$oAluno                 = AlunoRepository::getAlunoByCodigo($oGet->codigo_aluno);
$oMatricula             = MatriculaRepository::getMatriculaAtivaPorAluno($oAluno);
$oEtapa                 = $oMatricula->getEtapaDeOrigem();

/**
 * Buscamos como esta configurado o parâmetro ed233_reclassificaetapaanterior.
 */
$sSqlParametro = $oDaoEduParametros->sql_query_file(null, "ed233_reclassificaetapaanterior", null, "ed233_i_escola = {$iEscola}");
$rsParametro   = $oDaoEduParametros->sql_record($sSqlParametro);

if ($oDaoEduParametros->numrows > 0) {
  $lVerificaEtapaAnterior = db_utils::fieldsMemory($rsParametro, 0)->ed233_reclassificaetapaanterior == 't';
}
$sFiltroEtapa  = " ed11_i_sequencia = ". ($oEtapa->getOrdem()+1);
if ($lVerificaEtapaAnterior) {
  $sFiltroEtapa = " (ed11_i_sequencia = ". ($oEtapa->getOrdem()+1) ." or ed11_i_sequencia = ". ($oEtapa->getOrdem()-1) .")";
}

$sTurma      = $oMatricula->getTurma()->getDescricao();
$sEtapa      = $oMatricula->getEtapaDeOrigem()->getNome();
$sCalendario = $oMatricula->getTurma()->getCalendario()->getDescricao();

?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body bgcolor="#CCCCCC">
  <div class='container'>
    <table align="center" bgcolor="#CCCCCC">
    <tr>
      <td align="center" valign="top">
       <br>
       <b>
        Aluno: <?=$oAluno->getNome()?><br>
        Turma Atual: <?=$sTurma?> - Etapa: <?=$sEtapa?> - Calendário: <?=$sCalendario?><br><br>
        Turmas disponíveis para o reclassificação do aluno:
       </b>
      </td>
     </tr>
    </table>
    <center>
    
      <?php 
      
      $campos = "turma.ed57_i_codigo,
                 trim(turma.ed57_c_descr) as ed57_c_descr,
                 ed11_i_codigo,
                 trim(ed11_c_descr)          as ed11_c_descr,
                 calendario.ed52_c_descr     as ed57_i_calendario,
                 trim(cursoedu.ed29_c_descr) as ed31_i_curso,
                 trim(turno.ed15_c_nome)     as ed57_i_turno,
                 trim(sala.ed16_c_descr)     as ed57_i_sala,
                 formaavaliacao.ed37_c_descr as dl_Avaliação
                ";
      $sWhere  = " ed57_i_escola = {$iEscola} and ed52_i_ano = {$oMatricula->getTurma()->getCalendario()->getAnoExecucao()}";
      $sWhere .= " and ed10_i_codigo = {$oEtapa->getEnsino()->getCodigo()}";
      $sWhere .= " and {$sFiltroEtapa}";
      
      $sSql = $oDaoturma->sql_query_turmaserie(""," DISTINCT ".$campos, "ed57_c_descr", $sWhere);

      // die($sSql);
      
      db_lovrot($sSql, 15, "()", "",$oGet->funcao_js);
      ?>
    </center>
  </div>
</body>
</html>