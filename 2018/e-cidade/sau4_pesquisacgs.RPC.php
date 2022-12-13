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
require_once (modification("libs/db_utils.php"));
require_once (modification("libs/db_app.utils.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("dbforms/db_funcoes.php"));
require_once (modification("libs/JSON.php"));

$oJson = new services_json();
$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);


/**
 * O Auto load precisa ter em seu retorno, pelo menos as propriedades:
 *  - cod
 *  - label
 */
$sCampos  = " z01_i_cgsund as cod,  ";
$sCampos .= " z01_v_nome   as label ";
switch ($oGet->action) {

  /**
   * Retorna csg, nome, dt nascimento, sexo e cns de um cgs buscado pelo nome
   * Outras informações do cgs_und podem ser acrescentadas sem impactos
   */
  case "buscarDadosBasicosPorNome":

    $sName    = db_stdClass::crossUrlDecode( html_entity_decode($oPost->string) );
    $sCampos .= " ,z01_v_nome as nome,      ";
    $sCampos .= " z01_v_sexo as sexo,       ";
    $sCampos .= " z01_d_nasc as nascimento, ";
    $sCampos .= " s115_c_cartaosus as cns   ";
    $oDaoCgs = new cl_cgs_cartaosus();
    $sSql    = $oDaoCgs->sql_query(null, $sCampos, " label ", "z01_v_nome ilike '{$sName}%' ");
    $rsCgs   = db_query($sSql);

    break;

   /**
   * Retorna csg, nome, dt nascimento, sexo e cns de um cgs buscado pelo CNS
   * Outras informações do cgs_und podem ser acrescentadas sem impactos
   */
  case "buscarDadosBasicosPorCNS":

    $sCampos  = " z01_i_cgsund as cod,                            ";
    $sCampos .= " z01_v_nome as nome,                             ";
    $sCampos .= " z01_v_sexo as sexo,                             ";
    $sCampos .= " z01_d_nasc as nascimento,                       ";
    $sCampos .= " s115_c_cartaosus ||' - '|| z01_v_nome as label, ";
    $sCampos .= " s115_c_cartaosus as cns                         ";

    $oDaoCgs = new cl_cgs_cartaosus();
    $sSql    = $oDaoCgs->sql_query(null, $sCampos, " label limit 30 ", "s115_c_cartaosus ilike '{$oPost->string}%' ");
    $rsCgs   = db_query($sSql);

    break;

  default:

    $oDaoCgs = new cl_cgs_und();
    $sSql    = $oDaoCgs->sql_query_file(null, $sCampos, " label ", "z01_v_nome ilike '{$oPost->string}%' ");
    $rsCgs   = db_query($sSql);
    break;
}

$aArray = db_utils::getCollectionByRecord($rsCgs, false, false, true);
echo $oJson->encode($aArray);