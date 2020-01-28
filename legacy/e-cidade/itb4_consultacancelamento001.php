<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
$oGet            = db_utils::postmemory($_GET);
$oDaoItbiCancela = new cl_itbicancela();
?>

<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
  .texto {background-color:white} 
</style>
</head>
<body class="body-default">
<div class="container">
  <?php  
    
    $sSqlGuiasCanceladas  = "select it16_guia, it16_data, it16_obs, login, z01_nome from itbicancela            ";
    $sSqlGuiasCanceladas .= "  inner join db_usuarios  on  db_usuarios.id_usuario = itbicancela.it16_id_usuario ";
    $sSqlGuiasCanceladas .= "  inner join itbi  on  itbi.it01_guia = itbicancela.it16_guia                      ";
    $sSqlGuiasCanceladas .= "  inner join db_depart  on  db_depart.coddepto = itbi.it01_coddepto                ";
    $sSqlGuiasCanceladas .= "  inner join db_usuacgm on  db_usuacgm.id_usuario = db_usuarios.id_usuario         ";
    $sSqlGuiasCanceladas .= "  inner join cgm  on  cgm.z01_numcgm =   db_usuacgm.cgmlogin                       ";
    $sSqlGuiasCanceladas .= " where itbicancela.it16_guia = {$oGet->guia}                                       ";

    $rsSqlGuiasCanceladas  = db_query($sSqlGuiasCanceladas);
    if(pg_num_rows($rsSqlGuiasCanceladas) == 0) {

      echo "<p>Nenhum Registro Retornado</p>";
      exit;
    }

    $oDadosGuiasCanceladas = db_utils::fieldsMemory($rsSqlGuiasCanceladas,0);
    $oData                 = new DBDate($oDadosGuiasCanceladas->it16_data);

    $oDadosGuiasCanceladas->it16_data = $oData->getDate(DBDate::DATA_PTBR);
  ?>  
  <table border="0" cellpadding="2" cellspacing="3">
    <tr>
      <th align="left">Número da Guia de ITBI :</th>
      <td class="texto"><?php echo $oDadosGuiasCanceladas->it16_guia; ?></td>
    </tr>
    <tr>
      <th align="left">Data do Cancelamento :</th>
      <td class="texto"><?php echo $oDadosGuiasCanceladas->it16_data; ?></td>
    </tr>
    <tr>  
      <th align="left">Observações :</th>
      <td class="texto"><?php echo $oDadosGuiasCanceladas->it16_obs; ?></td>
    </tr>
    <tr>
      <?php if(trim($oDadosGuiasCanceladas->z01_nome) != "") { ?>
        <th align="left">Nome do Responsável :</th>  
        <td class="texto"><?php echo $oDadosGuiasCanceladas->z01_nome; ?></td>
      <?php } else { ?>
        <th align="left">Login do Usuário:</th>
        <td class="texto"><?php echo $oDadosGuiasCanceladas->login; ?></td>
      <?php } ?> 
    </tr>
  </table>   
</div>
</body>
</html>