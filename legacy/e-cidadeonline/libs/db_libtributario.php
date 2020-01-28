<?
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

function db_getNomeSecretaria(){
	$nomeSecretaria = "SECRETARIA DA FAZENDA";
  $sqlparag   = " select db02_texto ";
  $sqlparag  .= "   from db_documento ";
  $sqlparag  .= "        inner join db_docparag  on db03_docum   = db04_docum ";
  $sqlparag  .= "        inner join db_tipodoc   on db08_codigo  = db03_tipodoc ";
  $sqlparag  .= "        inner join db_paragrafo on db04_idparag = db02_idparag ";
  $sqlparag  .= " where db03_tipodoc = 1017 ";
  $sqlparag  .= "   and db03_instit = ".db_getsession("DB_instit")." ";
  $sqlparag  .= " order by db04_ordem ";
  $resparag  = db_query($sqlparag);
  if (pg_numrows($resparag) > 0) {
    $nomeSecretaria = pg_result($resparag,0,'db02_texto');
  }
	return $nomeSecretaria;  
}
  

function db_getcadbancobranca($arretipo,$ip,$datahj,$instit,$tipomod){

   $sSql  = "  select k48_sequencial,                                                                                                                   ";
   $sSql .= "         k48_cadconvenio,                                                                                                                  ";  
   $sSql .= "         ar12_cadconveniomodalidade                                                                                                        ";    
   $sSql .= "    from modcarnepadrao                                                                                                                    ";      
   $sSql .= "         inner join cadconvenio                on cadconvenio.ar11_sequencial                  = modcarnepadrao.k48_cadconvenio            ";
   $sSql .= "         inner join cadtipoconvenio            on cadtipoconvenio.ar12_sequencial              = cadconvenio.ar11_cadtipoconvenio          ";
   $sSql .= "         left  join conveniocobranca           on conveniocobranca.ar13_cadconvenio            = cadconvenio.ar11_sequencial               ";
   $sSql .= "         left  join modcarnepadraotipo         on modcarnepadraotipo.k49_modcarnepadrao        = modcarnepadrao.k48_sequencial             ";
   $sSql .= "         left  join modcarneexcessao           on modcarneexcessao.k36_modcarnepadrao          = modcarnepadrao.k48_sequencial             ";
   $sSql .= "         left  join modcarnepadraocadmodcarne  on modcarnepadraocadmodcarne.m01_modcarnepadrao = modcarnepadrao.k48_sequencial             ";
   $sSql .= "         left  join cadmodcarne                on cadmodcarne.k47_sequencial                   = modcarnepadraocadmodcarne.m01_cadmodcarne ";  
   $sSql .= "         left  join modcarnepadraolayouttxt    on modcarnepadraolayouttxt.m02_modcarnepadrao   = modcarnepadrao.k48_sequencial             ";
   $sSql .= "         left  join db_layouttxt               on db_layouttxt.db50_codigo                     = modcarnepadraolayouttxt.m02_db_layouttxt  ";
   $sSql .= "   where k48_dataini  <= '{$datahj}'                                                                                                       ";
   $sSql .= "     and k48_datafim  >= '{$datahj}'                                                                                                       ";
   $sSql .= "     and k48_instit     = {$instit}                                                                                                        ";
   $sSql .= "     and k48_cadtipomod = {$tipomod}                                                                                                       ";
   $sSql .= "     and ar12_cadconveniomodalidade = 1                                                                                                    ";
  
  if (!empty($iArretipo)) {
    $sSql .= "   and case                                                                 ";    
    $sSql .= "        when k49_tipo is not null then k49_tipo = {$arretipo} else true     ";
    $sSql .= "       end                                                                  ";    
  }
  
  if (!empty($sIp)) {
    $sSql .= "   and case                                                                 ";
    $sSql .= "          when k36_ip is not null then k36_ip = '{$ip}' else true           ";
    $sSql .= "       end                                                                  ";
  }

  $rsConsultaRegra = db_query($sSql);
  $iNroLinhas      = pg_num_rows($rsConsultaRegra);
  
  if ( $iNroLinhas > 0 ) {
    db_fieldsmemory($rsConsultaRegra,0);
    return true;
  } else {
    return false;      
  }   

  
}

// André TI - Prefeitura de Maricá 
abstract class DBTributario {

  /**
   * Buscas os Tipos de Debitos pela Origem
   * @param string  $sTipoOrigem 
   *                | M - Matricula
   *                | I - Inscricao
   *                | C - CGM
   *                | N - Numpre
   * @param integer $iChavePesquisa - Numero base para Pesquisa
   * @return stdClass[] Com as Definições dos Tipos de Débito encontrados
   */
  public static function getTiposDebitoByOrigem( $sTipoOrigem, $iChavePesquisa, $iInstituicao = null ) {


    $oDaoArretipo = db_utils::getDao("arretipo");

    if ( empty($iInstituicao) ) {
      $iInstituicao = db_getsession('DB_instit');
    }

    $sCampos      = "distinct                         ";
    $sCampos     .= "arretipo.k00_tipo,               ";
    $sCampos     .= "arretipo.k03_tipo,               ";
    $sCampos     .= "arretipo.k00_descr,              ";
    $sCampos     .= "arretipo.k00_marcado,            ";
    $sCampos     .= "arretipo.k00_emrec,              ";
    $sCampos     .= "arretipo.k00_agnum,              ";
    $sCampos     .= "arretipo.k00_agpar,              ";

    switch ($sTipoOrigem) {

    case "M": //Matricula
      $sCampos     .= "iptubase.j01_numcgm as k00_numcgm";
      $sSqlArretipo = $oDaoArretipo->sql_query_tiposDebitosByMatricula( $iChavePesquisa, $iInstituicao, $sCampos );
      break;                                                                                                      
    case "I": //Inscricao                                                                                       
      $sCampos     .= "issbase.q02_numcgm  as k00_numcgm";                                                    
      $sSqlArretipo = $oDaoArretipo->sql_query_tiposDebitosByInscricao( $iChavePesquisa, $iInstituicao, $sCampos );
      break;                                                                                                      
    case "C": //CGM                                                                                             
      $sCampos     .= "arrenumcgm.k00_numcgm as k00_numcgm";                                                    
      $sSqlArretipo = $oDaoArretipo->sql_query_tiposDebitosByCgm      ( $iChavePesquisa, $iInstituicao, $sCampos );
      break;                                                                                                    
    case "N": //Numpre                                                                                          
      $sCampos     .= "arrenumcgm.k00_numcgm as k00_numcgm";                                                    
      $sSqlArretipo = $oDaoArretipo->sql_query_tiposDebitosByNumpre   ( $iChavePesquisa, $iInstituicao, $sCampos );
      break;
    }
    $rsTipos = db_query($sSqlArretipo);

    if (!$rsTipos) {
      throw new DBException("Erro ao Buscar dados dos Tipos de Débitos:".pg_last_error());
    }

    return db_utils::getCollectionByRecord($rsTipos);
  }

  /**
   * Retorna o Nome da Secretaria da Fazenda do Municipio
   */
  public static function getNomeSecretariaFazenda() {
    return db_getNomeSecretaria();
  }

  public static function getCadbanCobranca($arretipo,$ip,$datahj,$instit,$tipomod) {
    return db_getcadbancobranca($arretipo,$ip,$datahj,$instit,$tipomod);
  }

  public static function emitirBic($parametro,$pdf,$tipo,$geracalculo) {
    return db_emitebic($parametro,$pdf,$tipo,$geracalculo);
  }
  
  /**
   * Retorna dados Basicos Referentes a Parcela de Débito 
   * 
   * @param mixed $iNumpre 
   * @param mixed $iNumpar 
   * @static
   * @access public
   * @return stdClass
   */
  public static function getMensagensParcela( $iNumpre, $iNumpar, $dDataEmissao ) {

    $oRetorno                        = new stdClass();
    $oRetorno->sMensagemContribuinte = "";
    $oRetorno->sMensagemCaixa        = "";
    /**
     * Para Buscar valor deve-se implentar busca na função debitos_numpre
     */
    $sSql  = "select distinct                                                    ";
    if ( !empty($iNumpar) ) {
      $sSql .= "       k00_dtvenc,                                               ";
    }
    $sSql .= "       k00_msguni,                                                 ";
    $sSql .= "       k00_msguni2,                                                ";
    $sSql .= "       k00_msgparc,                                                ";
    $sSql .= "       k00_msgparc2,                                               ";
    $sSql .= "       k00_msgparcvenc,                                            ";
    $sSql .= "       k00_msgparcvenc2,                                           ";
    $sSql .= "       arrecad.k00_tipo                                            ";
    $sSql .= "  from arrecad                                                     ";
    $sSql .= "       inner join arretipo on arrecad.k00_tipo = arretipo.k00_tipo ";
    $sSql .= " where k00_numpre = $iNumpre  ";
    if ( !empty($iNumpar) ) {
      $sSql .= "   AND k00_numpar = $iNumpar  ";
    }
    $rsSql = db_query($sSql);
   
    if ( !$rsSql ) {
      throw new DBException("Erro ao Buscar os Dados da Parcela. Descrição do Erro:". pg_last_error());
    }

    $oDadosDebito = db_utils::fieldsMemory($rsSql, 0);

    if (empty($iNumpar) ) {

      $oRetorno->sMensagemContribuinte = $oDadosDebito->k00_msguni2;
      $oRetorno->sMensagemCaixa        = $oDadosDebito->k00_msguni; 
      return $oRetorno;
    }

    $oDataVencimentoDebito            = new DBDate( $oDadosDebito->k00_dtvenc );
    $oDataEmissao                     = new DBDate( $dDataEmissao );

    if ( $oDataEmissao->getTimeStamp() > $oDataVencimentoDebito->getTimeStamp() ) {
    
      $oRetorno->sMensagemContribuinte = $oDadosDebito->k00_msgparc;
      $oRetorno->sMensagemCaixa        = $oDadosDebito->k00_msgparc2;
      return $oRetorno;                                             
    }

    $oRetorno->sMensagemContribuinte = $oDadosDebito->k00_msgparcvenc;
    $oRetorno->sMensagemCaixa        = $oDadosDebito->k00_msgparcvenc2;



    return $oRetorno;
  }

}
// André TI - Prefeitura de Maricá 
?>