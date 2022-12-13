<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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


class contribuicaoModel {
  
  /*
   * @description Método Construtor
   *
   * @param   integer  $iContribuicao  codigo da contribuição de melhoria
   * @return  void
   *
   */
  
  var $iContribuicao      = "";
  var $sErroMsg           = "";
  var $lSqlErro           = false;
  var $oContribuicaoDAO   = null;
  var $aMatriculasContrib = array();
  var $aServicos          = array();
  var $nTotalTestada      = 0;  
  
  function contribuicaoModel($iContribuicao) {
    
    $this->iContribuicao = $iContribuicao;

    require_once("libs/db_utils.php");
    $this->oContribuicaoDAO = db_utils::getDao('contrib');
    
  }
  
  function getiContribuicao() {
    return $this->iContribuicao;
  }
  
  function setiContribuicao( $iContribuicao ) {
    $this->iContribuicao = $iContribuicao;
  }
  
  function getDadosEdital() {
      
    $this->sErroMsg = "";
    $this->lSqlErro = false;
    
    $oEditalRua        = db_utils::getDao('editalrua');
    $sCampos           = "*";
    $sSqlContribuicao  = $oEditalRua->sql_query(null, "{$sCampos}", "j14_nome", " d02_contri = {$this->iContribuicao} ");  
    $rsContribuicao    = $this->oContribuicaoDAO->sql_record($sSqlContribuicao);
    
    if ($this->oContribuicaoDAO->numrows == 0 ) {
      
      $this->sErroMsg = "Não Foi possível buscar dados do edital da contribuição : {$this->iContribuicao}. ";
      $this->lSqlErro = true;
      return false;
      
    } else {      
      /*
       * Retornando dados do edital
       */        
      return db_utils::fieldsMemory($rsContribuicao,0 );      
    }
    
  }
  
  function getMatriculasContrib() {

    $sCampos  = " distinct j01_matric, j40_refant, z01_nome, lote.j34_idbql, lote.j34_area, d40_trecho, ";
    $sCampos .= " j34_setor, j34_quadra, j34_lote, j34_zona,( d41_testada + d41_eixo) as d05_testad ";
    
    $sSqlContribuicao  = " select {$sCampos}                                                 ";
    $sSqlContribuicao .= "   from contlot                                                    ";
    $sSqlContribuicao .= "        inner join lote                on j34_idbql  = d05_idbql   ";
    $sSqlContribuicao .= "        inner join iptubase            on j34_idbql  = j01_idbql   ";
    $sSqlContribuicao .= "        left  join iptuant             on j40_matric = j01_matric  ";
    $sSqlContribuicao .= "        inner join cgm                 on j01_numcgm = z01_numcgm  ";
    $sSqlContribuicao .= "        inner join editalruaproj       on d11_contri = d05_contri  ";
    $sSqlContribuicao .= "        inner join projmelhoriasmatric on d41_codigo = d11_codproj ";
    $sSqlContribuicao .= "                                      and d41_matric = j01_matric  ";
    $sSqlContribuicao .= "        inner join projmelhorias       on d40_codigo = d41_codigo  ";
    $sSqlContribuicao .= "  where d05_contri = {$this->iContribuicao}                        ";
    $sSqlContribuicao .= "  order by j40_refant                                              ";
    $rsContribuicao    = $this->oContribuicaoDAO->sql_record($sSqlContribuicao );
    
    if ($this->oContribuicaoDAO->numrows == 0 ) {
      
      $this->sErroMsg = "Não Foi possível buscar dados da contribuição : {$this->iContribuicao} .";
      $this->lSqlErro = true;
      return false;
      
    } else {
      
      /*
       * Percorre as matriculas com contribuicao
       */
      for ($iInd = 0; $iInd < $this->oContribuicaoDAO->numrows; $iInd++) {
        
        $this->aMatriculasContrib[] = db_utils::fieldsMemory($rsContribuicao,$iInd );
        
      }
      
      return $this->aMatriculasContrib;
      
    }
    
  }

  function getTotalTestada() {

    $this->sErroMsg = "";
    $this->lSqlErro = false;

    $sSqlSomaTestada  = " select sum(d41_testada + d41_eixo) as total_testada               ";
    $sSqlSomaTestada .= "   from contlot                                                    ";
    $sSqlSomaTestada .= "        inner join lote                on j34_idbql  = d05_idbql   ";
    $sSqlSomaTestada .= "        inner join iptubase            on j34_idbql  = j01_idbql   ";
    $sSqlSomaTestada .= "        inner join editalruaproj       on d11_contri = d05_contri  ";
    $sSqlSomaTestada .= "        inner join projmelhoriasmatric on d41_codigo = d11_codproj ";
    $sSqlSomaTestada .= "                                      and d41_matric = j01_matric  ";
    $sSqlSomaTestada .= "  where d05_contri = $this->iContribuicao ";
    $rsSomaTestada    = $this->oContribuicaoDAO->sql_record($sSqlSomaTestada);
    
    if ($this->oContribuicaoDAO->numrows > 0 ) {
      
      $this->sErroMsg = "Não Foi possível buscar soma das testadas dos lotes da contribuição : {$this->iContribuicao}.";
      $this->lSqlErro = true;
      return false;
      
    } else {

      $this->nTotalTestada = db_utils::fieldsMemory($rsSomaTestada,0)->total_testada ;
      unset($rsSomaTestada);
      return (float)$this->nTotalTestada;

    }

  }

  function getServicos() {
      
    $this->sErroMsg    = "";
    $this->lSqlErro    = false;    
    $oEditalServ       = db_utils::getDao('editalserv');
    $sCampos           = "*";
    $sSqlContribuicao  = $oEditalServ->sql_query(null,null,"{$sCampos}",null," d04_contri = {$this->iContribuicao}" ); 
    $rsContribuicao    = $this->oContribuicaoDAO->sql_record($sSqlContribuicao);
    
    if ($this->oContribuicaoDAO->numrows == 0 ) {
      
      $this->sErroMsg = "Não Foi possível buscar Serviços da contribuição : {$this->iContribuicao}. ";
      $this->lSqlErro = true;
      return false;
      
    } else {
      
      /*
       * Percorre as matriculas com contribuicao
       */
      for ($iInd = 0; $iInd < $this->oContribuicaoDAO->numrows; $iInd++) {
        $this->aServicos[] = db_utils::fieldsMemory($rsContribuicao,$iInd );
      }

      return $this->aServicos;
      
    }
    
  }
  
  function getEnderecoMatricula($iMatricula) {

    $sCampos  = " substr(endereco,001,40) as endereco, ";
    $sCampos .= " substr(endereco,042,10) as numero, ";
    $sCampos .= " substr(endereco,053,20) as complemento, ";
    $sCampos .= " substr(endereco,074,40) as bairro, ";
    $sCampos .= " substr(endereco,115,40) as municipio, ";
    $sCampos .= " substr(endereco,156,02) as uf, ";
    $sCampos .= " substr(endereco,159,08) as cep, ";
    $sCampos .= " substr(endereco,168,20) as cxpostal ";
    
    $sSqlEndereco  = " select {$sCampos} ";
    $sSqlEndereco .= "   from ( ";
    $sSqlEndereco .= "          select fc_iptuender(j01_matric) as endereco";
    $sSqlEndereco .= "            from iptubase ";
    $sSqlEndereco .= "           where j01_matric = {$iMatricula} ";
    $sSqlEndereco .= "        ) as endereco_matricula";

    $rsEndereco    = $this->oContribuicaoDAO->sql_record($sSqlEndereco);
    
    if ($this->oContribuicaoDAO->numrows == 0 ) {
      
      $this->sErroMsg = "Não Foi possível buscar endereco matricula : {$iMatricula} .";
      $this->lSqlErro = true;
      return false;
      
    } 
      
    return db_utils::fieldsMemory($rsEndereco,0);
    
  }

  function calculaContribuicaoPorMatricula($iTipo=null, $aParametros=null) {

    $this->sErroMsg = "";
    $this->lSqlErro = false;

    if ($iTipo == null) {

      $this->sErroMsg = "Parametros invalidos ! \n Não Foi possível executar calculo para matricula ";
      $this->lSqlErro = true;
      return false;

    }

    switch ( (int)$iTipo ) {
      
      case 1:

        return $this->calculoPorValor($aParametros);
        break;
        
      case 2:

        return $this->calculoPorValorValorizacao($aParametros);        
        break;
        
      case 3:
        
        return $this->calculoPorTestadaProporcional($aParametros);
        break;
        
    }
    
  }

  function calculoPorTestadaProporcional($aParametros) {

    $this->sErroMsg = "";
    $this->lSqlErro = false;

    $sSqlValVenal  = " select sum( case  ";
    $sSqlValVenal .= "               when j22_valor is null  ";
    $sSqlValVenal .= "                 then 0  ";
    $sSqlValVenal .= "               else j22_valor  ";
    $sSqlValVenal .= "             end + j23_vlrter ) as j23_vlrter ";
    $sSqlValVenal .= "   from ( select ( select sum(j22_valor) ";
    $sSqlValVenal .= "                     from iptucale  ";
    $sSqlValVenal .= "                    where j22_anousu = {$aParametros['ano']}";
    $sSqlValVenal .= "                      and j22_matric = {$aParametros['matric']} ) as j22_valor,  ";
    $sSqlValVenal .= "                 ( select j23_vlrter  ";
    $sSqlValVenal .= "                     from iptucalc  ";
    $sSqlValVenal .= "                    where j23_anousu = {$aParametros['ano']} ";
    $sSqlValVenal .= "                      and j23_matric = {$aParametros['matric']} ) as j23_vlrter ) as j23_vlrter ";
    $rsValorVenal  = $oContribuicaoDAO->sql_record($sSqlValVenal);
    
    if ($this->oContribuicaoDAO->numrows == 0 ) {
      
      $this->sErroMsg = "Não Foi possível buscar dados de calculo para matricula : {$aParametros['matric']} para o ano : {$aParametros['ano']}. ";
      $this->lSqlErro = true;
      return false;
      
    }

    $objValorVenal = db_utils::fieldsMemory($rsValorVenal,0);

    $nTotalTestada = $this->getTotalTestada();
    $nLarguraRua   = ( $objEditalServ->d02_profun * 2 );

    $this->aServicos = $this->getServicos();

    /*
     * For percorrendo os servicos da matricula para calcular o custo individual
     *
     */

    $nCustoTotal = 0;

    foreach ($this->aServicos as $oServico) {
      
      (float)$nValorVenal    = $objValorVenal->j23_vlrter;
      // Área Real Total
      (float)$nAreaRealTotal = ( $oServico->d04_quant * $nLarguraRua );
      // area total
      (float)$nAreaTotal     = ( $nTotalTestada *  $oServico->d02_profun );
      // valor do m2
      (float)$nValorM2       = round(( $oServico->d04_vlrobra / $nAreaRealTotal ) ,2);
      // valor valorizacao
      (float)$nValorizacao   = ( $nValorVenal * $oServico->d02_valorizacao / 100 );
      // area parcial
      (float)$nAreaParcial   = ( $objMatriculas->d05_testad * $oServico->d02_profun );
      // area corrigida
      (float)$nAreaCorrigida = ( $nAreaParcial / $nAreaTotal * $nAreaRealTotal );
      // valor venal
      (float)$nValorFinal    = ( $nValorVenal + $nValorizacao );
      // Custo
      (float)$nCusto         = ( $nAreaCorrigida * $nValorM2 )  * ( $oServico->d01_perc / 100 );
      
      //
      // Se Custo maior que a valorizacao entao custo fica a valorizacao
      //
      if ($nCusto > $nValorizacao ) {
        
        (float)$nCusto = $nValorizacao;
        
      }
      
      (float)$nCustoTotal += $nCusto;

    }

    return $nCustoTotal;

  }
  
  function calculoPorValor($aParametros) {

    $valorizacaoval  = $valorizacao;
    $custoindividual = round(($valmetro - ($valmetro * $d01_perc / 100)) * $m2,2);
    
  }
  
  function calculoPorValorValorizacao($aParametros) {

    $valmetroval     = ($valmetroval - ($valmetroval * $d01_perc / 100));
    $valorizacaoval  = round(($valmetroval * $m2) + $d07_venal,2);
    $custoindividual = round(($valmetro - ($valmetro * $d01_perc / 100)) * $m2,2);
    
  }
  
}