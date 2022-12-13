<?php
/**
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
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));

$clrotulo                   = new rotulocampo;
$oDaoPortaria               = new cl_portaria;
$oDaoAgendassentamento      = new cl_agendaassentamento;
$oDaoPortariaassinatura     = new cl_portariaassinatura;
$oDaoAssentamento           = new cl_assenta;
$oDaoRhParam                = new cl_rhparam;
$oDaoPortariaAssenta        = new cl_portariaassenta;
$db_opcao                   = 1;
$sEsconderNumeracaoPortaria = '';

db_postmemory($HTTP_POST_VARS);

if( !empty($_POST) ) {

  db_inicio_transacao();

  try {

    $sSqlAssentamento = $oDaoAssentamento->sql_query_file(null, 'h16_assent', null, " h16_codigo = {$h16_codigo}");
    $rsAssentamento   = db_query($sSqlAssentamento);

    if(!$rsAssentamento) {
      throw new DBException("Não foi possível buscar o assentamento.");
    }

    if(pg_num_rows($rsAssentamento) == 0) {
      throw new DBException("Não foi encontrado o assentamento informado.");
    }

    $h12_tipoasse = db_utils::fieldsMemory($rsAssentamento, 0)->h16_assent;

    if(empty($h12_tipoasse)) {
      throw new BusinessException("Não é possível salvar uma portaria sem o tipo de assentamento.");
    }

    $oDaoPortariaTipo  = new cl_portariatipo();
    $sSqlDadosPortaria = $oDaoPortariaTipo->sql_query_file(null,"h30_sequencial", null, "h30_tipoasse = {$h12_tipoasse}");
    $rsDadosPortaria   = db_query($sSqlDadosPortaria);

    if(!$rsDadosPortaria) {
      throw new DBException("Não foi possível buscar o tipo de portaria.");
    }

    if(pg_num_rows($rsDadosPortaria) == 0) {
      throw new DBException("Não foi encontrado o tipo de portaria informado.");
    }

    $h31_portariatipo = db_utils::fieldsMemory($rsDadosPortaria, 0)->h30_sequencial;

    /**
     * Pesquisa parametro da numeracao da portaria, caso encontre pega proxima numeracao, nextval()
     */
    $sWhereRhParam  = " h36_ultimaportaria > 0 and h36_instit = ".db_getsession("DB_instit");
    $sSqlRhParam    = $oDaoRhParam->sql_query_file(null,"h36_ultimaportaria",null,$sWhereRhParam);
    $rsDadosRhParam = $oDaoRhParam->sql_record($sSqlRhParam);

    if ( $oDaoRhParam->numrows > 0 ) {
      $sSqlSequence       = " select nextval('rhparam_h36_ultimaportaria_seq') as seq ";  
      $rsConsultaSequence = db_query($sSqlSequence);
      $oSeqPortaria       = db_utils::fieldsMemory($rsConsultaSequence,0);
      $h31_numero         = $oSeqPortaria->seq;
    }

    if ( $oDaoRhParam->numrows == 0 && empty($h31_numero)) {
      throw new BusinessException("Número de portaria não informado.");
    }

    $oDaoPortaria->h31_numero              = $h31_numero;
    $oDaoPortaria->h31_dtportaria          = $h31_dtportaria;
    $oDaoPortaria->h31_dtinicio            = $h31_dtinicio;
    $oDaoPortaria->h31_dtlanc              = $h31_dtlanc;
    $oDaoPortaria->h31_amparolegal         = $h31_amparolegal;
    $oDaoPortaria->h31_portariaassinatura  = $h31_portariaassinatura;
    $oDaoPortaria->h31_portariatipo        = $h31_portariatipo;
    $oDaoPortaria->h31_anousu              = $h31_anousu;
    $oDaoPortaria->h31_usuario             = db_getsession("DB_id_usuario");

    $oDaoPortaria->incluir(null);

    if ($oDaoPortaria->erro_status == "0"){

      throw new DBException($oDaoPortaria->erro_msg);

    } else {

      $h31_sequencial = $oDaoPortaria->h31_sequencial;

      $sSqlSequence       = " select last_value as seq from rhparam_h36_ultimaportaria_seq";  
      $rsConsultaSequence = db_query($sSqlSequence);
      $oSeqPortaria       = db_utils::fieldsMemory($rsConsultaSequence,0)->seq;
  
      $oDaoRhParam->h36_ultimaportaria = $oSeqPortaria;
      $oDaoRhParam->h36_instit         = db_getsession("DB_instit");
      $oDaoRhParam->alterar(db_getsession("DB_instit"));
  
      if ( $oDaoRhParam->erro_status == "0" ) {
        throw new DBException($oDaoRhParam->erro_msg);
      }
    }

    $oDaoPortariaAssenta->h33_portaria = $h31_sequencial;
    $oDaoPortariaAssenta->h33_assenta  = $h16_codigo;
    $oDaoPortariaAssenta->incluir(null);

    if ($oDaoPortariaAssenta->erro_status == "0"){
      throw new DBException($oDaoPortariaAssenta->erro_msg);
    }

    $lStatus = true;
    db_msgbox("Portaria vinculada com sucesso.");
    db_fim_transacao(false);

  } catch (Exception $e) {

    $lStatus = false;
    db_msgbox($e->getMessage());
    db_fim_transacao(true);
    unset($_POST);
  }


} else {

  /**
   * Pesquisa parametro da numeracao da portaria, caso encontre pega proxima numeracao, nextval()
   */
  $sWhereRhParam  = " h36_ultimaportaria > 0 and h36_instit = ".db_getsession("DB_instit");
  $sSqlRhParam    = $oDaoRhParam->sql_query_file(null,"h36_ultimaportaria",null,$sWhereRhParam);
  $rsDadosRhParam = $oDaoRhParam->sql_record($sSqlRhParam);

  if ( $oDaoRhParam->numrows > 0 ) {
    $sEsconderNumeracaoPortaria = 'style="display: none;"';
    $h31_numero = ((int)db_utils::fieldsMemory($rsDadosRhParam, 0)->h36_ultimaportaria)+1;
  }
}

require_once(modification('forms/db_frmvincularassentamentoportaria.php'));
