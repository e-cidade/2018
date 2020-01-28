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

require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_rhlocaltrab_classe.php"));
require_once(modification("classes/db_db_departrhlocaltrab_classe.php"));

$oJson                = new services_json(0,true);
$oParametros          = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno             = new stdClass();
$oRetorno->status     = true;
$oRetorno->erro       = false;
$oRetorno->message    = '';

try {

  switch ($oParametros->exec) {

    case 'carregarLocaisDeTrabalho':

      $sSqlRhlocaltrab          = "select rhlocaltrab.*, 
                                          case when db_departrhlocaltrab.rh185_sequencial is not null 
                                            then true 
                                            else false 
                                          end as marcado 
                                     from rhlocaltrab 
                                          left join db_departrhlocaltrab on db_departrhlocaltrab.rh185_rhlocaltrab = rhlocaltrab.rh55_codigo 
                                                                        and db_departrhlocaltrab.rh185_instit      = rhlocaltrab.rh55_instit
                                                                        and db_departrhlocaltrab.rh185_db_depart   = {$oParametros->iCodigoDepartamento}
                                    where rh55_instit = ". db_getsession("DB_instit") ."
                                    order by rhlocaltrab.rh55_codigo";

      $rsRhlocaltrab            = db_query($sSqlRhlocaltrab);
      $aLocaisDeTrabalho        = array();

      for ($iLocalDeTrabalho = 0; $iLocalDeTrabalho < pg_num_rows($rsRhlocaltrab); $iLocalDeTrabalho++) {

        $oLocalDeTrabalho       = db_utils::fieldsmemory($rsRhlocaltrab, $iLocalDeTrabalho);

        $oResultado             = new stdClass();
        $oResultado->iCodigo    = $oLocalDeTrabalho->rh55_codigo;
        $oResultado->sDescricao = $oLocalDeTrabalho->rh55_descr;
        $oResultado->lMarcado   = $oLocalDeTrabalho->marcado == 't' ? true : false;

        $aLocaisDeTrabalho[]    = $oResultado;

      }

      $oRetorno->aLocaisDeTrabalho = $aLocaisDeTrabalho;

      break;

    case 'salvar':

      db_inicio_transacao();

      $oDaoDb_departrhlocaltrab = new cl_db_departrhlocaltrab;
      $oDaoDb_departrhlocaltrab->excluir(null, "rh185_db_depart = {$oParametros->iCodigoDepartamento}");

      if ($oDaoDb_departrhlocaltrab->erro_status == "0") {
        throw new Exception ('Erro ao atualizar dados. ERRO: ' . $oDaoDb_departrhlocaltrab->erro_msg);
      }

      $aSelecionados = $oParametros->aSelecionados;

      foreach ($aSelecionados as $oSelecionado) {

        $oDaoDb_departrhlocaltrab->rh185_sequencial  = null;
        $oDaoDb_departrhlocaltrab->rh185_db_depart   = $oParametros->iCodigoDepartamento;
        $oDaoDb_departrhlocaltrab->rh185_rhlocaltrab = $oSelecionado->iCodigo;
        $oDaoDb_departrhlocaltrab->rh185_instit      = db_getsession('DB_instit');
        $oDaoDb_departrhlocaltrab->incluir(null);

        if($oDaoDb_departrhlocaltrab->erro_status == "0") {
          throw new Exception('Erro ao incluir dados. ERRO: '. $oDaoDb_departrhlocaltrab->erro_msg);
        }
      }

      $oRetorno->message = 'Processamento executado com sucesso.';

      db_fim_transacao();

      break;

  }
} catch (Exception $eException) {
  $oRetorno->message = urlencode($eException->getMessage());
}

echo $oJson->encode($oRetorno);