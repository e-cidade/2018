<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("model/educacao/DBEducacaoTermo.model.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$oDaoMatricula = db_utils::getdao("matricula");

$sCampos      = " to_char(ed60_d_datasaida, 'DD/MM/YYYY') as datasaida,  ";
$sCampos      .= " case";
$sCampos      .= "  when ed60_c_situacao = 'TRANSFERIDO REDE' then";
$sCampos      .= "   (select escoladestino.ed18_c_nome from transfescolarede";
$sCampos      .= "           inner join atestvaga  on  atestvaga.ed102_i_codigo = transfescolarede.ed103_i_atestvaga";
$sCampos      .= "           inner join escola  as escoladestino on  escoladestino.ed18_i_codigo = atestvaga.ed102_i_escola";
$sCampos      .= "    where ed103_i_matricula = ed60_i_codigo order by ed103_d_data desc limit 1)";
$sCampos      .= "  when ed60_c_situacao = 'TRANSFERIDO FORA' then";
$sCampos      .= "   (select escolaproc.ed82_c_nome from transfescolafora";
$sCampos      .= "           inner join escolaproc  on  escolaproc.ed82_i_codigo = transfescolafora.ed104_i_escoladestino";
$sCampos      .= "    where ed104_i_matricula = ed60_i_codigo order by ed104_d_data desc limit 1)";
$sCampos      .= " else null";
$sCampos      .= " end as destinosaida, ";
$sCampos      .= " turma.ed57_c_descr, serie.ed11_c_descr, calendario.ed52_c_descr, cursoedu.ed29_c_descr, ed60_i_numaluno, ";
$sCampos      .= " ed47_v_nome, ed47_i_codigo, ed60_c_situacao, ed60_i_codigo, ed60_d_datamatricula, ed60_c_rfanterior";
$sCampos      .= " , ed60_i_turmaant";
$sOrder        = " ed60_i_numaluno, to_ascii(ed47_v_nome), ed60_c_ativa";
$sWhere        = " ed60_i_turma = $turma AND ed221_i_serie = $etapaturma";
$sSqlMatricula = $oDaoMatricula->sql_query("", $sCampos, $sOrder, $sWhere);
$rsMatricula   = $oDaoMatricula->sql_record($sSqlMatricula);
?>
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
   <style>
    .cabec {
            text-align: left;
            font-size: 10;
            font-weight: bold;
            color: #DEB887;
            background-color:#444444;
            border:1px solid #CCCCCC;
          }
    .aluno {
            font-size: 10;
           }
   </style>
   <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
 </head>
 <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <form name="form2" method="post" action="">
   <table border="0" cellspacing="2px" width="100%" height="100%" cellpadding="1px" bgcolor="#cccccc">
    <tr>
     <td align="center" valign="top">
      <table border='1px' width="100%" bgcolor="#cccccc" style="" cellspacing="0px">
       <tr class='cabec'>
        <td align='center' colspan='8'>
          Turma: <?=pg_result($rsMatricula, 0, "ed57_c_descr")?>&nbsp;&nbsp;&nbsp;&nbsp;
          Etapa: <?=pg_result($rsMatricula, 0, "ed11_c_descr")?>&nbsp;&nbsp;&nbsp;&nbsp;
          Calendário: <?=pg_result($rsMatricula, 0, "ed52_c_descr")?><br>
          Ensino: <?=pg_result($rsMatricula, 0, "ed29_c_descr")?>
        </td>
       </tr>
       <tr><td height='2' colspan='8' bgcolor='#444444'></td></tr>
       <tr bgcolor="#DBDBDB" align="center">
        <td width="5%"><b>N°</b></td>
        <td><b>Aluno</b></td>
        <td><b>Código</b></td>
        <td><b>Situação</b></td>
        <td><b>Data Matrícula</b></td>
        <td><b>Data Saída</b></td>
        <td><b>RF Anterior</b></td>
        <td><b>Destino Saída</b></td>
       </tr>
       <?
       for ($iCont = 0; $iCont < $oDaoMatricula->numrows; $iCont++) {
         	
         db_fieldsmemory($rsMatricula, $iCont);
         $inf_ant  = explode("|", RFanterior($ed60_i_codigo));
         $turmaant = $inf_ant[0];
         $rfant    = $inf_ant[1];
         
         /**
          * Verificamos se a matricula do aluno possui 'rfanterior' e 'turmaant'
          * Caso sim, buscamos o ano e o ensino referente a turma anterior
          */
         if (!empty($ed60_c_rfanterior) && !empty($ed60_i_turmaant)) {
           
           $oDaoTurma    = db_utils::getDao('turma');
           $sCamposTurma = "ed52_i_ano, ed29_i_ensino";
           $sWhereTurma  = "ed57_i_codigo = {$ed60_i_turmaant}";
           $sSqlTurma    = $oDaoTurma->sql_query(null, $sCamposTurma, null, $sWhereTurma);
           $rsTurma      = $oDaoTurma->sql_record($sSqlTurma);
           
           /**
            * Caso retorne algum resultado, buscamos o termo relacionado ao 'rfanterior'
            */
           if ($oDaoTurma->numrows > 0) {
             
             $iEnsino = db_utils::fieldsMemory($rsTurma, 0)->ed29_i_ensino;
             $sAno    = db_utils::fieldsMemory($rsTurma, 0)->ed52_i_ano;
             
             if ($ed60_c_rfanterior == 'A' || $ed60_c_rfanterior == 'R') {
               
               $aDadosTermo = DBEducacaoTermo::getTermoEncerramento($iEnsino, $ed60_c_rfanterior, $sAno);
               if (isset($aDadosTermo[0])) {
                 $rfant = $aDadosTermo[0]->sDescricao;
               }
             }
           }
         }
         
       ?>
         <tr bgcolor="#f3f3f3">
          <td class="aluno" width="5%" align="center"><?=$ed60_i_numaluno==""?"&nbsp;":$ed60_i_numaluno?></td>
          <td class="aluno"><?=$ed47_v_nome?></td>
          <td class="aluno" align="right"><?=$ed47_i_codigo?></td>
          <td class="aluno" align="center"><?=Situacao($ed60_c_situacao, $ed60_i_codigo)?></td>
          <td class="aluno" align="center"><?=db_formatar($ed60_d_datamatricula, 'd')?></td>
          <td class="aluno" align="center"><?=$datasaida==""?"&nbsp;":$datasaida?></td>
          <td class='aluno' align='center'><?=$rfant==""?"&nbsp;":$rfant?></td>
          <td class="aluno" align="center"><?=$destinosaida==""?"&nbsp;":$destinosaida?></td>
         </tr>
       <?
       }
      ?>
     </table>
    </td>
   </tr>
  </table>
 </body>
</html>