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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
$resultedu = eduparametros(db_getsession("DB_coddepto"));
$db_opcao = 1;
$sql1 = "SELECT ((coalesce(sum(ed78_i_aulasdadas),0)-coalesce(sum(ed72_i_numfaltas),0)+coalesce(sum(ed80_i_numfaltas),0) )
                          / coalesce(sum(ed78_i_aulasdadas),1)::float
                        )*100 as perc_disciplina,
                coalesce(sum(ed78_i_aulasdadas),0) as aulasdadas,
                coalesce(sum(ed72_i_numfaltas),0) as faltas,
                coalesce(sum(ed80_i_numfaltas),0) as abonos,
                ed47_i_codigo,ed47_v_nome,ed232_c_descr,ed59_i_ordenacao
         FROM diarioavaliacao
          inner join procavaliacao on ed41_i_codigo = ed72_i_procavaliacao
          inner join periodoavaliacao on ed09_i_codigo = ed41_i_periodoavaliacao
          inner join avalfreqres on ed67_i_procavaliacao = ed41_i_codigo
          inner join diario on ed95_i_codigo = ed72_i_diario
          inner join aluno on ed47_i_codigo = ed95_i_aluno
          inner join regencia on ed59_i_codigo = ed95_i_regencia
          inner join disciplina on ed12_i_codigo = ed59_i_disciplina
          inner join caddisciplina on ed232_i_codigo = ed12_i_caddisciplina
          inner join regenciaperiodo on ed78_i_procavaliacao = ed41_i_codigo
                                     and ed78_i_regencia = ed95_i_regencia
          left join abonofalta on ed80_i_diarioavaliacao = ed72_i_codigo
         WHERE ed67_i_procresultado = $codresultado
         AND ed95_i_regencia in (select ed59_i_codigo from regencia where ed59_i_turma = $codturma and ed59_c_condicao = 'OB')
         AND ed95_i_aluno = $codaluno
         AND ed72_c_amparo = 'N'
         AND ed09_c_somach = 'S'
         GROUP BY ed47_i_codigo,ed47_v_nome,ed232_c_descr,ed59_i_ordenacao
         ORDER BY ed59_i_ordenacao
        ";
$result1 = db_query($sql1);
//db_criatabela($result1);
$oTurma = new Turma($codturma);
$iAno   = $oTurma->getCalendario()->getAnoExecucao();
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<style>
.titulo{
 font-size: 11;
 color: #DEB887;
 background-color:#444444;
 font-weight: bold;
}
.cabec1{
 font-size: 13;
 color: #000000;
 background-color:#999999;
 font-weight: bold;
}
.aluno{
 color: #000000;
 font-family : Tahoma;
 font-size: 10;
}
</style>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<table valign="top" marginwidth="0" width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left" valign="top" bgcolor="#CCCCCC">
   <fieldset><legend><b>Quadro Geral Cálculo Frequência - <?=pg_result($result1,0,'ed47_i_codigo')?> - <?=pg_result($result1,0,'ed47_v_nome')?></b></legend>
    <table width="100%" border="1" cellspacing="0" cellpadding="0">
     <tr align="center">
      <td class="titulo">Disciplina</td>
      <td class="titulo">Aulas Dadas/Dias Letivos</td>
      <td class="titulo">N° Faltas</td>
      <td class="titulo">N° Abonos</td>
      <td class="titulo">Percentual por Disciplina</td>
     </tr>
     <?
     $soma_aulas = 0;
     $soma_faltas = 0;
     $soma_abonos = 0;
     for($r=0;$r<pg_num_rows($result1);$r++){
      db_fieldsmemory($result1,$r);
      $soma_aulas += $aulasdadas;
      $soma_faltas += $faltas;
      $soma_abonos += $abonos;
      ?>
      <tr class="aluno" bgcolor="#f3f3f3">
       <td><?=trim($ed232_c_descr)?></td>
       <td align="center"><?=$aulasdadas?></td>
       <td align="center"><?=$faltas?></td>
       <td align="center"><?=$abonos?></td>
       <td align="right"><?=ArredondamentoFrequencia::arredondar($perc_disciplina, $iAno)?>%</td>
      </tr>
      <?
     }
     ?>
     <tr class="cabec1">
      <td align="right"><b>TOTAIS</b></td>
      <td align="center"><?=$soma_aulas?></td>
      <td align="center"><?=$soma_faltas?></td>
      <td align="center"><?=$soma_abonos?></td>
      <td align="right"><?=$perctotal?>%</td>
     </tr>
    </table>
   </fieldset>
  </td>
 </tr>
</table>
</body>
</html>