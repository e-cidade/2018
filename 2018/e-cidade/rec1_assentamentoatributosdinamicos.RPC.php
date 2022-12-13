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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno = new stdClass();
$oRetorno->erro      = false;
$oRetorno->sMensagem = '';

try {
  
  switch ($oParam->sAcao) {

    case 'getDados':

      $oRetorno->iCodigoFormulario = null;
      $oRetorno->iCodigoGrupo      = null;

      $oDaoAssentaGrupo          = new cl_assentadb_cadattdinamicovalorgrupo();
      $oDaoTipoAssentaFormulario = new cl_tipoassedb_cadattdinamico();
      $sSqlFormulario            = $oDaoTipoAssentaFormulario->sql_query(null, null, "h79_db_cadattdinamico", null, "h12_assent = '{$oParam->sTipoAssentamento}';");
      $rsSqlFormulario           = db_query($sSqlFormulario);

      if ( !$rsSqlFormulario ) {
        throw new DBException("Não foi possivel retornar formulário do .");
      }

      if ( pg_num_rows($rsSqlFormulario) > 0 ) {
        $oRetorno->iCodigoFormulario = db_utils::fieldsMemory($rsSqlFormulario,0)->h79_db_cadattdinamico;
      }

      if ( !empty($oParam->iCodigoAssentamento) ) {

       $sWhere      = "h12_assent = '{$oParam->sTipoAssentamento}'";
       $sWhere     .= " and h16_codigo = '{$oParam->iCodigoAssentamento}';";
       $sSqlGrupo   = $oDaoAssentaGrupo->sql_query(null,null,"db_cadattdinamicovalorgrupo", null, $sWhere);

       $rsSqlGrupo  = db_query($sSqlGrupo);

       if ( !$rsSqlGrupo ) {
         throw new DBException("Erro ao pesquisar campos personalizados do Tipo de Assentamento.");
       }

       if ( pg_num_rows($rsSqlGrupo) > 0 ) {
         $oRetorno->iCodigoGrupo = db_utils::fieldsMemory($rsSqlGrupo,0)->db_cadattdinamicovalorgrupo;
       }    
      }
    break;

    case 'getDadosPortaria':

      $oRetorno->iCodigoFormulario = null;
      $oRetorno->iCodigoGrupo      = null;
      $oRetorno->iAssenta          = null;

      $oDaoAssentaGrupo            = new cl_assentadb_cadattdinamicovalorgrupo();
      $oDaoTipoAssentaFormulario   = new cl_tipoassedb_cadattdinamico();
      $oDaoPortariaTipo            = new cl_portariatipo();      

      $sSqlPortariaTipo    = $oDaoPortariaTipo->sql_query_file ($oParam->iTipoPortaria, "h30_tipoasse");
      $rsPortariaTipo      = db_query($sSqlPortariaTipo);
      $iTipoAssentamento = db_utils::fieldsMemory($rsPortariaTipo,0)->h30_tipoasse;

      $sSqlFormulario              = $oDaoTipoAssentaFormulario->sql_query(null, null, "h79_db_cadattdinamico", null, "h12_codigo = {$iTipoAssentamento}");

      $rsSqlFormulario             = db_query($sSqlFormulario);

      if ( !$rsSqlFormulario ) {
        throw new DBException("Não foi possivel retornar formulário do .");
      }

      if ( pg_num_rows($rsSqlFormulario) > 0 ) {
        $oRetorno->iCodigoFormulario = db_utils::fieldsMemory($rsSqlFormulario,0)->h79_db_cadattdinamico;
      }

      if (!empty($oParam->iCodigoAssentamento)) {

        $sSqlGrupo                   = $oDaoAssentaGrupo->sql_query(null,null, "h80_db_cadattdinamicovalorgrupo", null, "h16_codigo = '{$oParam->iCodigoAssentamento}'");
        $rsSqlGrupo                  = db_query($sSqlGrupo);

        if ( !$rsSqlGrupo ) {
          throw new DBException("Erro ao pesquisar campos personalizados do Tipo de Assentamento.");
        }

        if ( pg_num_rows($rsSqlGrupo) > 0 ) {
          $oRetorno->iCodigoGrupo = db_utils::fieldsMemory($rsSqlGrupo,0)->h80_db_cadattdinamicovalorgrupo;
        }    
      }

      $oRetorno->iAssenta = $iTipoAssentamento;
    break;
  }
} catch ( Exception $eErro ) {
  
  $oRetorno->erro      = true;
  $oRetorno->sMensagem = $eErro->getMessage();
}

echo $oJson->encode($oRetorno);
