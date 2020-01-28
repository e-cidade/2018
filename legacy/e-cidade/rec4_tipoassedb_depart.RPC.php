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
require_once(modification("classes/db_tipoasse_classe.php"));
require_once(modification("classes/db_tipoassedb_depart_classe.php"));

$oJson                = new services_json(0,true);
$oParametros          = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno             = new stdClass();
$oRetorno->status     = true;
$oRetorno->erro       = false;
$oRetorno->message    = '';

try {

  switch ($oParametros->exec) {

    case 'carregarTipos': 

      $iCodigoDepartamento  = $oParametros->iCodigoDepartamento;
      $oDaotipoassedb_depart = new cl_tipoassedb_depart;

      $oDaoTipoAssentamento = new cl_tipoasse();
      $sSqlTipoAssentamento = $oDaoTipoAssentamento->sql_query_file(null, '*', 'h12_codigo');
      $rsTipoAssentamento   = $oDaoTipoAssentamento->sql_record($sSqlTipoAssentamento);
      $aTiposAssentamentos  = array();

      for ($iTipoAssentamento = 0; $iTipoAssentamento < pg_num_rows($rsTipoAssentamento); $iTipoAssentamento++) {
        
        $oTipoAssentamento         = db_utils::fieldsmemory($rsTipoAssentamento, $iTipoAssentamento);
        
        //Verifica se já está selecionado para o departamento
        $sWheretipoassedb_depart    = "rh184_db_depart = {$iCodigoDepartamento} and rh184_tipoasse = {$oTipoAssentamento->h12_codigo}";
        $sSqltipoassedb_depart      = $oDaotipoassedb_depart->sql_query_file(null, "*", null, $sWheretipoassedb_depart);
        $rstipoassedb_depart        = db_query($sSqltipoassedb_depart);

        $lMarcado = false; 
        if (pg_num_rows($rstipoassedb_depart) > 0) {
          $lMarcado = true;
        }

        $oResultado                = new stdClass();
        $oResultado->iCodigo       = $oTipoAssentamento->h12_codigo;
        $oResultado->sAssentamento = $oTipoAssentamento->h12_assent;
        $oResultado->sDescricao    = $oTipoAssentamento->h12_descr;
        $oResultado->lMarcado      = $lMarcado;
       
        $aTiposAssentamentos[]     = $oResultado; 

      }

      $oRetorno->aTiposAssentamentos = $aTiposAssentamentos;

      break;

    case 'salvar':

      db_inicio_transacao();
      
      $iCodigoDepartamento  = $oParametros->iCodigoDepartamento;
      $aSelecionados        = $oParametros->aSelecionados;

      $oDaotipoassedb_depart = new cl_tipoassedb_depart;
      $oDaotipoassedb_depart->excluir(null, "rh184_db_depart = $iCodigoDepartamento");

      if ($oDaotipoassedb_depart->erro_status == "0") {
        throw new Exception ('Erro ao atualizar dados. ERRO: ' . $oDaotipoassedb_depart->erro_msg);
      }

      foreach ($aSelecionados as $oSelecionado) {

        $oDaotipoassedb_depart->rh184_sequencial = null;
        $oDaotipoassedb_depart->rh184_db_depart  = $iCodigoDepartamento;
        $oDaotipoassedb_depart->rh184_tipoasse   = $oSelecionado->iCodigo;
        $oDaotipoassedb_depart->incluir(null);

        if($oDaotipoassedb_depart->erro_status == "0") {
          throw new Exception('Erro ao incluir dados. ERRO: '. $oDaotipoassedb_depart->erro_msg);
        }
      }

      $oRetorno->message = 'Processamento executado com sucesso.'; 

      db_fim_transacao();
      
      break;

  }

} catch (Exception $eException) {
  $oRetorno->message = $eException->getMessage();
}

echo JSON::create()->stringify($oRetorno);
