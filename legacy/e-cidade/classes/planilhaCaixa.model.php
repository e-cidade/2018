<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

class planilhaCaixa {

  var $iPlanilha = null;
  var $iAnoUsu   = null;
  /**
   * metodo Construtor
   * @param integer $iCodPlanilha Código da planilha
   *
   */
  function planilhaCaixa($iCodPlanilha) {

    $this->iPlanilha = $iCodPlanilha;
    $this->iAnoUsu   = db_getsession("DB_anousu");

  }

  /**
   * adiciona uma receita a planilha;
   * @param object oReceita recebe como parametro um Objeto db_utils com as informações das planilhas;
   */

  function adicionarReceita($oReceita) {

    $oDaoPlaCaixaRec = db_utils::getDao("placaixarec");
    if ($oReceita->k81_origem == 2 ) {

      $oDaoIssBase = db_utils::getDao("issbase");
      $rsIssBase   = $oDaoIssBase->sql_record($oDaoIssBase->sql_query_file($oReceita->q02_inscr,"q02_numcgm"));
      if ($oDaoIssBase->numrows > 0) {
        $iNumCgm = db_utils::fieldsMemory($rsIssBase,0)->q02_numcgm;
      }

    } else if ($oReceita->k81_origem == 3) {

      $oDaoIptuBase = db_utils::getDao("iptubase");
      $rsIptuBase   = $oDaoIptuBase->sql_record($oDaoIptuBase->sql_query_file($oReceita->j01_matric,"j01_numcgm"));
      if ($oDaoIptuBase->numrows > 0) {
        $iNumCgm = db_utils::fieldsMemory($rsIptuBase,0)->j01_numcgm;
      }
    } else {
      $iNumCgm = $oReceita->k81_numcgm;
    }
    //incluimos a receita na planilha
    $oDaoPlaCaixaRec->k81_codpla         = $this->iPlanilha;
    $oDaoPlaCaixaRec->k81_conta          = $oReceita->k81_conta;
    $oDaoPlaCaixaRec->k81_receita        = $oReceita->k81_receita;
    $oDaoPlaCaixaRec->k81_valor          = $oReceita->k81_valor;
    $oDaoPlaCaixaRec->k81_obs            = $oReceita->k81_obs;
    $oDaoPlaCaixaRec->k81_codigo         = $oReceita->k81_codigo;
    $oDaoPlaCaixaRec->k81_datareceb      = implode("/",array_reverse(explode("/", $oReceita->k81_datareceb)));
    $oDaoPlaCaixaRec->k81_operbanco      = $oReceita->k81_operbanco;
    $oDaoPlaCaixaRec->k81_origem         = $oReceita->k81_origem;
    $oDaoPlaCaixaRec->k81_numcgm         = $iNumCgm;
    $oDaoPlaCaixaRec->k81_concarpeculiar = $oReceita->c58_sequencial;
    $oDaoPlaCaixaRec->incluir(null);
    if ($oDaoPlaCaixaRec->erro_status == 0) {

      $this->lSqlErro = false;
      $this->sErroMsg = "Erro ao incluir Receita.\\nErro Tecnico: {$oDaoPlaCaixaRec->erro_msg}";
      return false;

    } else {

      //aqui incluimos na placaixarecinscr, ou na placaixarecmatric, conforme origem da receita;
      if ($oReceita->k81_origem == 3) {

        $oDaoPlaCaixaRecMatric = db_utils::getDao("placaixarecmatric");
        $oDaoPlaCaixaRecMatric->k77_placaixarec = $oDaoPlaCaixaRec->k81_seqpla;
        $oDaoPlaCaixaRecMatric->k77_matric      = $oReceita->j01_matric;
        $oDaoPlaCaixaRecMatric->incluir(null);
        if ($oDaoPlaCaixaRecMatric->erro_status == 0 ) {

          $this->lSqlErro = false;
          $this->sErroMsg = "Erro ao incluir Receita.\\nErro Tecnico: {$oDaoPlaCaixaRecMatric->erro_msg}";
          return false;

        }
      } else if ($oReceita->k81_origem == 2) {
        $oDaoPlaCaixaRecInscr = db_utils::getDao("placaixarecinscr");
        $oDaoPlaCaixaRecInscr->k76_placaixarec = $oDaoPlaCaixaRec->k81_seqpla;
        $oDaoPlaCaixaRecInscr->k76_inscr       = $oReceita->q02_inscr;
        $oDaoPlaCaixaRecInscr->incluir(null);
        if ($oDaoPlaCaixaRecInscr->erro_status == 0 ) {

          $this->lSqlErro = false;
          $this->sErroMsg = "Erro ao incluir Receita.\\nErro Tecnico: {$oDaoPlaCaixaRecInscr->erro_msg}";
          return false;

        }
      }
    }
    return true;
  }

  /**
   * altera uma receita a planilha;
   * @param integer $iSeqPlanilha Codigo sequencial da receita na planilha
   * @param object oReceita recebe como parametro um Objeto db_utils com as informações das planilhas;
   */

  function alterarReceita($iSeqPlanilha, $oReceita) {

    $oDaoPlaCaixaRec = db_utils::getDao("placaixarec");
    $rsReceitaPla    = $oDaoPlaCaixaRec->sql_record($oDaoPlaCaixaRec->sql_query_file($iSeqPlanilha));

    if ($oDaoPlaCaixaRec->numrows > 0) {
      /*
       * caso a origem seje diferente de cgm, devemos excluir os registros na placaixarecmatric 
       * e placaixarecinscr
       */ 

      $oPlanilhaRec = db_utils::fieldsMemory($rsReceitaPla, 0);
      switch ($oPlanilhaRec->k81_origem) {

        case 2:

          $oDaoInscr = db_utils::getDao("placaixarecinscr");
          $oDaoInscr->excluir(null, "k76_placaixarec = {$iSeqPlanilha}");
          break;

        case 3:

          $oDaoMatric = db_utils::getDao("placaixarecmatric");
          $oDaoMatric->excluir(null, "k77_placaixarec = {$iSeqPlanilha}");
          break;

      }

    } else {

      $this->lSqlErro = true;
      $this->sErroMsg = "receita da planilha não Encontrada";
      return false;
    }
    //definimos o CGM, e alteramos o registro.
    if ($oReceita->k81_origem == 2 ) {

      $oDaoIssBase = db_utils::getDao("issbase");
      $rsIssBase   = $oDaoIssBase->sql_record($oDaoIssBase->sql_query_file($oReceita->q02_inscr,"q02_numcgm"));
      if ($oDaoIssBase->numrows > 0) {
        $iNumCgm = db_utils::fieldsMemory($rsIssBase,0)->q02_numcgm;
      }

    } else if ($oReceita->k81_origem == 3) {

      $oDaoIptuBase = db_utils::getDao("iptubase");
      $rsIptuBase   = $oDaoIptuBase->sql_record($oDaoIptuBase->sql_query_file($oReceita->j01_matric,"j01_numcgm"));
      if ($oDaoIptuBase->numrows > 0) {
        $iNumCgm = db_utils::fieldsMemory($rsIptuBase,0)->j01_numcgm;
      }
    } else {
      $iNumCgm = $oReceita->k81_numcgm;
    }
    //alteramos a receita na planilha
    $oDaoPlaCaixaRec->k81_codpla         = $this->iPlanilha;
    $oDaoPlaCaixaRec->k81_seqpla         = $iSeqPlanilha;
    $oDaoPlaCaixaRec->k81_conta          = $oReceita->k81_conta;
    $oDaoPlaCaixaRec->k81_receita        = $oReceita->k81_receita;
    $oDaoPlaCaixaRec->k81_valor          = $oReceita->k81_valor;
    $oDaoPlaCaixaRec->k81_obs            = $oReceita->k81_obs;
    $oDaoPlaCaixaRec->k81_codigo         = $oReceita->k81_codigo;
    $oDaoPlaCaixaRec->k81_datareceb      = implode("/",array_reverse(explode("/", $oReceita->k81_datareceb)));
    $oDaoPlaCaixaRec->k81_operbanco      = $oReceita->k81_operbanco;
    $oDaoPlaCaixaRec->k81_origem         = $oReceita->k81_origem;
    $oDaoPlaCaixaRec->k81_numcgm         = $iNumCgm;
    $oDaoPlaCaixaRec->k81_concarpeculiar = $oReceita->c58_sequencial; 
    $oDaoPlaCaixaRec->alterar($iSeqPlanilha);
    if ($oDaoPlaCaixaRec->erro_status == 0) {

      $this->lSqlErro = false;
      $this->sErroMsg = "Erro ao alterar Receita.\\nErro Tecnico: {$oDaoPlaCaixaRec->erro_msg}";
      return false;

    } else {

      //aqui incluimos na placaixarecinscr, ou na placaixarecmatric, conforme origem da receita;
      if ($oReceita->k81_origem == 3) {

        $oDaoPlaCaixaRecMatric = db_utils::getDao("placaixarecmatric");
        $oDaoPlaCaixaRecMatric->k77_placaixarec = $oDaoPlaCaixaRec->k81_seqpla;
        $oDaoPlaCaixaRecMatric->k77_matric      = $oReceita->j01_matric;
        $oDaoPlaCaixaRecMatric->incluir(null);
        if ($oDaoPlaCaixaRecMatric->erro_status == 0 ) {

          $this->lSqlErro = false;
          $this->sErroMsg = "Erro ao incluir Receita.\\nErro Tecnico: {$oDaoPlaCaixaRecMatric->erro_msg}";
          return false;

        }
      } else if ($oReceita->k81_origem == 2) {
        $oDaoPlaCaixaRecInscr = db_utils::getDao("placaixarecinscr");
        $oDaoPlaCaixaRecInscr->k76_placaixarec = $oDaoPlaCaixaRec->k81_seqpla;
        $oDaoPlaCaixaRecInscr->k76_inscr       = $oReceita->q02_inscr;
        $oDaoPlaCaixaRecInscr->incluir(null);
        if ($oDaoPlaCaixaRecInscr->erro_status == 0 ) {

          $this->lSqlErro = false;
          $this->sErroMsg = "Erro ao alterar Receita.\\nErro Tecnico: {$oDaoPlaCaixaRecInscr->erro_msg}";
          return false;

        }
      }
    }
    return true;
  }
  

  function excluirReceita($iSeqPlanilha) {

    $oDaoPlaCaixaRec = db_utils::getDao("placaixarec");
    $rsReceitaPla    = $oDaoPlaCaixaRec->sql_record($oDaoPlaCaixaRec->sql_query_file($iSeqPlanilha));

    if ($oDaoPlaCaixaRec->numrows > 0) {
      /*
       * caso a origem seje diferente de cgm, devemos excluir os registros na placaixarecmatric 
       * e placaixarecinscr
       */ 

      $oPlanilhaRec = db_utils::fieldsMemory($rsReceitaPla, 0);
      switch ($oPlanilhaRec->k81_origem) {

        case 2:

          $oDaoInscr = db_utils::getDao("placaixarecinscr");
          $oDaoInscr->excluir(null, "k76_placaixarec = {$iSeqPlanilha}");
          break;

        case 3:

          $oDaoMatric = db_utils::getDao("placaixarecmatric");
          $oDaoMatric->excluir(null, "k77_placaixarec = {$iSeqPlanilha}");
          break;

      }

    } else {

      $this->lSqlErro = true;
      $this->sErroMsg = "receita da planilha não Encontrada";
      return false;
    }

    $oDaoPlaCaixaRec->excluir($iSeqPlanilha);  
    if ($oDaoPlaCaixaRec->erro_status == 0) {
      
      $this->lSqlErro = true;
      $this->sErroMsg = "receita da planilha não Excluida\\nErro Técnico {$oDaoPlaCaixaRec->erro_msg}";
      return false;

    }
    return true;
  }
}