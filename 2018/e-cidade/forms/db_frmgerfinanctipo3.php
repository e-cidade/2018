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


// 3 - ISSQN VARIÁVEL

$sSqlNumpar = ($numpar > 0 ? " and arrecad.k00_numpar=$numpar" : "");

$iInstituicao = db_getsession('DB_instit');

$sSql  = "select arrecad.*,                                                                   ";
$sSql .= "       issvar.*,                                                                    ";
$sSql .= "       arreinscr.k00_inscr,                                                         ";
$sSql .= "       issplan.*  ,                                                                 ";
$sSql .= "       cgm.z01_nome,                                                                ";
$sSql .= "       issbase.q02_dtinic,                                                          ";
$sSql .= "       informacaodebito.*                                                           ";
$sSql .= "  from arreinscr                                                                    ";
$sSql .= "       inner join arrecad          on arrecad.k00_numpre    = arreinscr.k00_numpre  ";
$sSql .= "       inner join arreinstit       on arreinstit.k00_numpre = arrecad.k00_numpre    ";
$sSql .= "                                  and arreinstit.k00_instit = {$iInstituicao}       ";
$sSql .= "       inner join issvar           on arrecad.k00_numpre    = issvar.q05_numpre     ";
$sSql .= "                                  and arrecad.k00_numpar    = issvar.q05_numpar     ";
$sSql .= "        left join informacaodebito on k163_numpre           = issvar.q05_numpre     ";
$sSql .= "                                  and k163_numpar           = issvar.q05_numpar     ";
$sSql .= "        left join issplan          on q05_numpre            = q20_numpre            ";
$sSql .= "                                  and arrecad.k00_numcgm    = q20_numcgm            ";
$sSql .= "       inner join cgm              on arrecad.k00_numcgm    = cgm.z01_numcgm        ";
$sSql .= "       left  join issbase           on cgm.z01_numcgm        = issbase.q02_numcgm   ";
$sSql .= " where arreinscr.k00_numpre = $numpre {$sSqlNumpar}               							    ";

$sSql .= " union all                                                                          ";

$sSql .= "select distinct  																 													          ";
$sSql .= "       arrecad.*,                                                                   ";
$sSql .= "       issvar.*,                                                                    ";
$sSql .= "       0 as k00_inscr,                                                              ";
$sSql .= "       issplan.*,                                                                   ";
$sSql .= "       cgm.z01_nome,                                                                ";
$sSql .= "       issbase.q02_dtinic,                                                          ";
$sSql .= "       informacaodebito.*                                                           ";
$sSql .= "  from arrenumcgm                                                                   ";
$sSql .= "       inner join arrecad          on arrecad.k00_numpre    = arrenumcgm.k00_numpre ";
$sSql .= "       inner join arreinstit       on arreinstit.k00_numpre = arrecad.k00_numpre    ";
$sSql .= "                                  and arreinstit.k00_instit = {$iInstituicao}       ";
$sSql .= "       inner join issvar           on arrecad.k00_numpre    = issvar.q05_numpre     ";
$sSql .= "                                  and arrecad.k00_numpar    = issvar.q05_numpar     ";
$sSql .= "        left join issplan          on q05_numpre            = q20_numpre            ";
$sSql .= "                                  and arrecad.k00_numcgm    = q20_numcgm            ";
$sSql .= "        left join informacaodebito on k163_numpre           = issvar.q05_numpre     ";
$sSql .= "                                  and k163_numpar           = issvar.q05_numpar     ";
$sSql .= "       inner join cgm              on arrecad.k00_numcgm    = cgm.z01_numcgm        ";
$sSql .= "       left  join issbase           on cgm.z01_numcgm        = issbase.q02_numcgm   ";
$sSql .= "where arrenumcgm.k00_numpre = $numpre {$sSqlNumpar} 													      ";

$rsResult = db_query($sSql);

if (pg_num_rows($rsResult) > 0) {

  $oIssqnVariavel = db_utils::fieldsMemory($rsResult, 0);

  $k00_numpre      = $oIssqnVariavel->k00_numpre;
  $q05_aliq        = $oIssqnVariavel->q05_aliq;
  $q05_ano         = $oIssqnVariavel->q05_ano;
  $q05_mes         = $oIssqnVariavel->q05_mes;
  $q05_histor      = $oIssqnVariavel->q05_histor;
  $k00_tipo        = $oIssqnVariavel->k00_tipo;
  $k00_inscr       = $oIssqnVariavel->k00_inscr;
  $q20_planilha    = $oIssqnVariavel->q20_planilha;
  $q20_nomecontri  = $oIssqnVariavel->q20_nomecontri;
  $q02_dtinic      = db_formatar($oIssqnVariavel->q02_dtinic, 'd');
  $z01_nome        = $oIssqnVariavel->z01_nome;
  $dDataLancamento = db_formatar($oIssqnVariavel->k163_data, 'd');
  $sCompetencia    = str_pad($q05_mes, 2, '0', STR_PAD_LEFT) .'/'.$q05_ano;

}

?>

<fieldset>
  <legend>ISSQN Vari&aacute;vel</legend>
  <table class="linhaZebrada">
    <?php
      if (isset($k00_inscr) and @$k00_inscr > 0) {
    ?>
    <tr>
      <td>Inscri&ccedil;&atilde;o:</td>
      <td><?php echo $k00_inscr; ?></td>
    </tr>
    <tr>
      <td>Data In&iacute;cio:</td>
      <td><?php echo $q02_dtinic; ?></td>
    </tr>
    <tr>
      <td>Data Lançamento:</td>
      <td><?php echo $dDataLancamento; ?></td>
    </tr>
    <tr>
      <td>Nome/Empresa:</td>
      <td><?php echo $z01_nome; ?></td>
    </tr>
    <?php
      }
    ?>
    <tr>
      <td>C&oacute;digo Arrecada&ccedil;&atilde;o:</td>
      <td><?php echo $k00_numpre; ?></td>
    </tr>
    <tr>
      <td valign="top">Al&iacute;quota:</td>
      <td><?php echo $q05_aliq; ?>%</td>
    </tr>
    <tr>
      <td valign="top">Compet&ecirc;ncia:</td>
      <td><?php echo $sCompetencia; ?></td>
    </tr>
    <?php
      if ($k00_tipo == 33) {
    ?>
    <tr>
      <td valign="top">Planilha:</td>
      <td><?php echo $q20_planilha; ?></td>
    </tr>
    <tr>
      <td valign="top">Contato:</td>
      <td><?php echo $q20_nomecontri; ?></td>
    </tr>
    <?php
      }
    ?>
    <tr>
      <td valign="top">Observa&ccedil;&atilde;o:</td>
      <td><?php echo $q05_histor; ?></td>
    </tr>
  </table>
</fieldset>