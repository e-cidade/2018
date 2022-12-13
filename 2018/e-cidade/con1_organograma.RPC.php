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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("classes/db_db_organograma_classe.php"));

db_app::import("configuracao.DBEstrutura");
db_app::import("configuracao.Organograma");

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->erro    = false;
$oRetorno->message = '';

switch ($oParam->exec) {
  
  case "getCodigoEstrutural":

  	$oDomXML = new DOMDocument();
    $oDomXML->load($oParam->sUrl);
    $aConfiguracoesGerais = $oDomXML->getElementsByTagName('ConfiguracoesGerais');
    $i=0;
    foreach ($aConfiguracoesGerais as $oConfiguracoesGerais) {
    	
      $aOrganograma = $oConfiguracoesGerais->getElementsByTagName('Organograma');
      foreach ($aOrganograma as $oOrganograma) {
      	
      	 $iCodigoEstrutural = $oOrganograma->getAttribute('CodigoEstrutura');
      }
    }
    $oRetorno->iCodigoEstrutura = $iCodigoEstrutural; 
    break;


  case "getEstrutural":

    $oDomXML = new DOMDocument();
    $oDomXML->load($oParam->sUrl);
    $aConfiguracoesGerais = $oDomXML->getElementsByTagName('ConfiguracoesGerais');
    $i=0;
    $iCodigoEstrutural =16;

    foreach ($aConfiguracoesGerais as $oConfiguracoesGerais) {

      $aOrganograma = $oConfiguracoesGerais->getElementsByTagName('Organograma');
      foreach ($aOrganograma as $oOrganograma) {

         $iCodigoEstrutural = $oOrganograma->getAttribute('CodigoEstrutura');
      }
    }

    $sCamposOrganograma  = "Distinct db77_estrut as estrutural";
    $sWhere              = "db77_codestrut =" . $iCodigoEstrutural ;

    $oOrganograma        = new cl_db_organograma();
    $sSqlOrganograma     = $oOrganograma->sql_query_conta(null, $sCamposOrganograma, $sOrdemOrganograma, $sWhere);
    $rsOrganograma       = $oOrganograma->sql_record($sSqlOrganograma);

    if(!$rsOrganograma){

      throw new DBException("Estrutura nуo encontrada");      
    }

    $aEstrutural         = db_utils::getCollectionByRecord($rsOrganograma, false, false, true);
    $oRetorno->estrutural= $aEstrutural[0]->estrutural;
    break;

  case "salvarOrganograma":

    try {
      db_inicio_transacao(); 
    
      $oOrganograma = new Organograma($oParam->iCodigoOrganograma);
      $oOrganograma->setDescricao(db_stdClass::db_stripTagsJson(utf8_decode($oParam->oOrganograma->sDescricao)))
                     ->setEstrutura((int)$oParam->oOrganograma->iCodigoEstrutura)
                     ->setTipoConta($oParam->oOrganograma->iTipo)
                     ->setEstrutural(db_stdClass::db_stripTagsJson(utf8_decode($oParam->oOrganograma->sEstrutural)))
                     ->setDescricaoDepartamento($oParam->oOrganograma->sDescricao)
                     ->setCodigoDepartamento($oParam->oOrganograma->iDepartamento)
                     ->setAssociado($oParam->oOrganograma->sAssociado)
                     ->salvar();      

       db_fim_transacao(); 

    } catch (Exception $eErro) {
      
      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
      
    }
    break;

  // Estrutura vem ordenada pelo nivel, para nao ter problema de tentar salvar um filho sem existir a estrutura do no pai
  case "salvarOrganogramaFilhos":

    try {

      foreach ($oParam->filhos->nivel as $aFilhos) {

        if(!empty($aFilhos)){

          foreach ($aFilhos as $oFilho) {
            $oFilho = DBString::utf8_decode_all($oFilho);

            db_inicio_transacao();
            $oOrganograma = new Organograma($oFilho->iCodigoOrganograma);
            $oOrganograma->setDescricao(db_stdClass::db_stripTagsJson($oFilho->sDescricao))
                           ->setEstrutura((int)$oFilho->iCodigoEstrutura)
                           ->setTipoConta($oFilho->iTipo)
                           ->setEstrutural(db_stdClass::db_stripTagsJson($oFilho->sEstrutural))
                           ->setDescricaoDepartamento($oFilho->sDescricao)
                           ->setCodigoDepartamento($oFilho->iDepartamento)
                           ->setAssociado($oFIlho->sAssociado)
                           ->salvar();
            db_fim_transacao();
          }

        }
      }
    } catch (Exception $eErro) {
      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));

    }
  break;

  case "getOrganograma":
   
    $oOrganograma                     = new Organograma($oParam->iCodigoEstrutura);
    $oRetorno->iCodigoOrganograma     = urlencode($oOrganograma->getCodigoOrganograma());    
    $oRetorno->sDescricao             = urlencode($oOrganograma->getDescricao());
    $oRetorno->sAssociado             = urlencode($oOrganograma->getAssociado());
    $oRetorno->sEstrutural            = urlencode($oOrganograma->getEstrutural());
    $oRetorno->sTipoConta             = urlencode($oOrganograma->getTipoConta());
    $oRetorno->sDescricaoDepartamento = urlencode($oOrganograma->getDescricaoDepartamento());
    $oRetorno->iCodigoDepartamento    = urlencode($oOrganograma->getCodigoDepartamento());
    $oRetorno->sFilhos                = urlencode($oOrganograma->getFilhos());
    $oRetorno->iCodigoEstrutura       = '16'; 
    break;
  
  case "getOrganogramas":
    
    $sCamposOrganograma  = "coalesce(db121_descricao, 'S/G') as descricaogrupo,";
    $sCamposOrganograma .= "db121_sequencial as codigogrupo,";
    $sCamposOrganograma .= "db121_nivel as nivel,";
    $sCamposOrganograma .= "db122_associado as associado,";
    $sCamposOrganograma .= "coalesce(db121_estrutural, '00.00') as estrutural,";
    $sCamposOrganograma .= "(select count(*) from db_estruturavalor f
                              where f.db121_estruturavalorpai = db_estruturavalor.db121_estruturavalorpai) as filhos,";
    $sCamposOrganograma .= "(SELECT count(*) from db_estruturavalor f
                              where f.db121_estruturavalorpai = db_estruturavalor.db121_sequencial and f.db121_sequencial
	 												    in(
	 												      select db122_estruturavalor from db_organograma where db122_associado = 't'
	 												    )
	 											  ) as filhos_associados,";
    $sCamposOrganograma .= "coalesce(db121_estruturavalorpai, 0) as conta_pai, ";
    $sCamposOrganograma .= "CASE
                              WHEN db122_associado = 't'
                              THEN
                                (db121_nivel + 1)
                              ELSE
                                db121_nivel
                             END as nivel_associado,";

    $sCamposOrganograma .= "CASE
                              WHEN coalesce(db121_estruturavalorpai, 0) = 0
                              THEN 0
                              ELSE (select count(*) from db_estruturavalor f
                                    where f.db121_estruturavalorpai = db_estruturavalor.db121_estruturavalorpai)
                            END as total_filhos,";
    $sCamposOrganograma .= "fc_estrutural_pai(cast(db121_estruturavalorpai as text)) as estrutural_pai ";
    $sOrdemOrganograma   = "db121_estrutural";

    $oOrganograma        = new cl_db_organograma();
    $sWhere              = " db122_instit = " . db_getsession("DB_instit");
    $sSqlOrganograma     = $oOrganograma->sql_query_conta(null, $sCamposOrganograma, $sOrdemOrganograma, $sWhere);
    $rsOrganograma       = $oOrganograma->sql_record($sSqlOrganograma);

    if(!$rsOrganograma){

      throw new DBException("Nenhum Organograma Encontrado");
      
    }
    
    $aGrupos             = db_utils::getCollectionByRecord($rsOrganograma, false, false, true);
    $oRetorno->aGrupos   =  $aGrupos;
  break;

  case "getOrganogramasTreeView":

    $sCamposOrganograma  = "coalesce(db121_descricao, 'S/G') as descricaogrupo,";
    $sCamposOrganograma .= "db121_sequencial as codigogrupo,";
    $sCamposOrganograma .= "db121_nivel as nivel,";
    $sCamposOrganograma .= "db122_depart as departamento,";
    $sCamposOrganograma .= "descrdepto as descricaodepartamento,";
    $sCamposOrganograma .= "db122_sequencial as codigoorganograma,";
    $sCamposOrganograma .= "db122_associado as associado,";
    $sCamposOrganograma .= "coalesce(db121_estrutural, '00.00') as estrutural,";
    $sCamposOrganograma .= "(select
                              count(*) from db_estruturavalor f
                            where
                              f.db121_estruturavalorpai = db_estruturavalor.db121_sequencial
                              and f.db121_estruturavalorpai != 0
                           ) as filhos,";
    $sCamposOrganograma .= "(SELECT count(*) from db_estruturavalor f
                              where f.db121_estruturavalorpai = db_estruturavalor.db121_sequencial and f.db121_sequencial
                                in(
                                  select db122_estruturavalor from db_organograma where db122_associado = 't'
                                )
                            ) as filhos_associados,";
    $sCamposOrganograma .= "coalesce(db121_estruturavalorpai, 0) as conta_pai, ";
    $sCamposOrganograma .= "fc_estrutural_pai(cast(db121_estruturavalorpai as text)) as estrutural_pai ";
    $sOrdemOrganograma   = "db121_estrutural";

    $oOrganograma        = new cl_db_organograma();

    $sWhere              = " db_depart.instit = " . db_getsession("DB_instit");
    $sSqlOrganograma     = $oOrganograma->sql_query_conta(null, $sCamposOrganograma, $sOrdemOrganograma, $sWhere);
    $rsOrganograma       = $oOrganograma->sql_record($sSqlOrganograma);

    if(!$rsOrganograma){

      throw new DBException("Nenhuma informaчуo encontrada");
    }

    $aGrupos             = db_utils::getCollectionByRecord($rsOrganograma, false, false, true);
    $oRetorno->aGrupos   =  $aGrupos;
  break;
    
  case "getOrganogramaByEstruturalNivel":
  	$sCamposOrganograma  = "coalesce(db121_descricao, 'S/G') as descricaogrupo,";
    $sCamposOrganograma .= "db121_sequencial as codigogrupo,";
    $sCamposOrganograma .= "db121_nivel as nivel,";
    $sCamposOrganograma .= "db122_depart as departamento,";
    $sCamposOrganograma .= "descrdepto as descricaodepartamento,";
    $sCamposOrganograma .= "db122_sequencial as codigoorganograma,";
    $sCamposOrganograma .= "db122_associado as associado,";
    $sCamposOrganograma .= "coalesce(db121_estrutural, '00.00') as estrutural,";
    $sCamposOrganograma .= "(select
                              count(*) from db_estruturavalor f
                              where
                              f.db121_estruturavalorpai = db_estruturavalor.db121_sequencial
                              and f.db121_estruturavalorpai != 0
                           ) as filhos,";
    $sCamposOrganograma .= "(SELECT count(*) from db_estruturavalor f
                              where f.db121_estruturavalorpai = db_estruturavalor.db121_sequencial and f.db121_sequencial
                                in(
                                  select db122_estruturavalor from db_organograma where db122_associado = 't'
                                )
                            ) as filhos_associados,";
    $sCamposOrganograma .= "coalesce(db121_estruturavalorpai, 0) as conta_pai, ";
    $sCamposOrganograma .= "fc_estrutural_pai(cast(db121_estruturavalorpai as text)) as estrutural_pai ";
    $sOrdemOrganograma   = "db121_estrutural";


    $oOrganograma        = new cl_db_organograma();

    $sWhere              = " db_depart.instit = " . db_getsession("DB_instit")
      . " and db121_estrutural ILIKE '" . $oParam->sEstrutural
      . "%' and db121_nivel =" . ($oParam->iNivel+1);
    $sSqlOrganograma     = $oOrganograma->sql_query_conta(null, $sCamposOrganograma, $sOrdemOrganograma, $sWhere);
    $rsOrganograma       = $oOrganograma->sql_record($sSqlOrganograma);

    if($rsOrganograma){

      $aGrupos           = db_utils::getCollectionByRecord($rsOrganograma, false, false, true);
      $oRetorno->aGrupos =  $aGrupos;
    } else {

      $oRetorno->aGrupos = false;
    }
  break;

  case "salvaXML":

    $rArquivoXML = fopen("config/configuracao.xml","w+");
    $sArquivoXML = db_stdClass::db_stripTagsJson($oParam->sXml);
    $writeXML    = fwrite($rArquivoXML, $sArquivoXML);
    fclose($rArquivoXML);

    $oDomXML = new DOMDocument();
    $oDomXML->load('config/configuracao.xml');
    $aConfiguracoesGerais = $oDomXML->getElementsByTagName('ConfiguracoesGerais');

    foreach ($aConfiguracoesGerais as $oConfiguracoesGerais) {

      $aOrganograma = $oConfiguracoesGerais->getElementsByTagName('Organograma');

      foreach ($aOrganograma as $oOrganograma) {

        $iCodigoEstrutural = $oOrganograma->getAttribute('CodigoEstrutura');
      }
    }
    $oRetorno->iCodigoEstrutura = $iCodigoEstrutural;
  break;
}
echo $oJson->encode($oRetorno);
?>