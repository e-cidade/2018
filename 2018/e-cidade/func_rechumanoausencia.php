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

require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("libs/db_usuariosonline.php"));
require_once (modification("dbforms/db_funcoes.php"));

$oGet = db_utils::postMemory($_GET);

$iEscola      = db_getsession("DB_coddepto");
$sWhere       = " ed348_escola = {$iEscola} ";
$oDaoAusencia = new cl_rechumanoausente();
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  </head>
  <body>

  <div class="container">
    <?php

      $sCampos  = " ed348_sequencial as db_rechumanoausente, ";
      $sCampos .= " ( select ed321_sequencial from docenteausencia ";
      $sCampos .= "  where ed321_rechumano    = ed348_rechumano ";
      $sCampos .= "    and ed321_inicio       = ed348_inicio ";
      $sCampos .= "    and ed321_tipoausencia = ed348_tipoausencia ";
      $sCampos .= "    and ed321_escola       = ed348_escola ";
      $sCampos .= "  ) as db_docenteausente, ";
      $sCampos .= " ed348_inicio, ed348_final, ed348_rechumano, ";
      $sCampos .= " case                                   ";
      $sCampos .= "   when cgmrh.z01_numcgm is not null    ";
      $sCampos .= "    then cgmrh.z01_numcgm               ";
      $sCampos .= "    else cgmcgm.z01_numcgm              ";
      $sCampos .= " end as z01_numcgm,                     ";
      $sCampos .= " case                                   ";
      $sCampos .= "   when cgmrh.z01_numcgm is not null    ";
      $sCampos .= "    then cgmrh.z01_nome                 ";
      $sCampos .= "    else cgmcgm.z01_nome                ";
      $sCampos .= " end as z01_nome                        ";

      $sWhere   = " ed348_escola = " . db_getsession("DB_coddepto");
      $sql      = $oDaoAusencia->sql_query_profissional_cgm("", $sCampos, "ed348_sequencial", $sWhere);
      $repassa  = array();
      if(isset($chave_ed348_sequencial)){
        $repassa = array("chave_ed348_sequencial"=>$chave_ed348_sequencial,"chave_ed348_sequencial"=>$chave_ed348_sequencial);
      }

      db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
    ?>

  </div>
  </body>
</html>