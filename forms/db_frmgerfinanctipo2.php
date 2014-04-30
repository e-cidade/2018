<?php
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


// 2 - ISSQN FIXO
// 9 - ALVARÁ

$sSqlNumpar = ($numpar > 0 ? " and arrecad.k00_numpar=$numpar" : "");

$iInstituicao = db_getsession('DB_instit') ;

$sSql  = " select arreinscr.k00_inscr,                                                   ";
$sSql .= "        arrecad.k00_numpre ,                                                   ";
$sSql .= "        empresa.q02_dtinic ,                                                   ";
$sSql .= "        empresa.z01_nome   ,                                                   ";
$sSql .= "        isscalc.q01_valor                                                      ";
$sSql .= "   from        arreinscr                                                       ";
$sSql .= "   inner join    arrecad    on arrecad.k00_numpre     = arreinscr.k00_numpre   ";
$sSql .= "   inner join arreinstit    on arreinstit.k00_numpre  = arrecad.k00_numpre     ";
$sSql .= "                           and arreinstit.k00_instit  = {$iInstituicao}        ";
$sSql .= "   inner join    isscalc    on arrecad.k00_numpre     = isscalc.q01_numpre     ";
$sSql .= "   inner join    empresa    on empresa.q02_inscr      = arreinscr.k00_inscr    ";
$sSql .= "   where                       arreinscr.k00_numpre   = $numpre  {$sSqlNumpar} ";

$rsResult = db_query($sSql);

if (pg_numrows($rsResult) == 0) {

  echo "Código de arrecadação não cadastrado ou empresa não cadastrada no issbase";
  exit;

} else {
  
  $oIssqnFixo = db_utils::fieldsMemory($rsResult,0,'1');
  
  $k00_inscr  = $oIssqnFixo->k00_inscr;
  $q02_dtinic = db_formatar($oIssqnFixo->q02_dtinic, 'd');
  $z01_nome   = $oIssqnFixo->z01_nome;
  $k00_numpre = $oIssqnFixo->k00_numpre;
  $q01_valor  = db_formatar($oIssqnFixo->q01_valor, 'f');  
  
}

?>
<fieldset>

  <legend><?php echo ($iTipo == 2 ? "ISSQN Fixo" : "Alvará"); ?></legend>
  
  <table class="linhaZebrada">
    <tr> 
      <td>Inscri&ccedil;&atilde;o:</td>
      <td><?php echo $k00_inscr; ?></td>
    </tr>
    <tr> 
      <td>Data In&iacute;cio:</td>
      <td><?php echo $q02_dtinic; ?></td>
    </tr>
    <tr> 
      <td>Nome/Empresa:</td>
      <td><?php echo $z01_nome; ?></td>
    </tr>
    <tr>
      <td>C&oacute;digo Arrecada&ccedil;&atilde;o:</td>
      <td><?php echo $k00_numpre; ?></td>
    </tr>
    <tr>
      <td>Valor Lan&ccedil;ado:</td>
      <td><?php echo $q01_valor; ?></td>
    </tr>
  </table>
  
</fieldset>