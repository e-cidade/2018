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

/**
 * Model para controle dos encaminhamentos 
 * @author Tony Farney B. M. Ribeiro  
 */
class controleExamesLaboratorio {
  
  protected $iSetorExame            = null;
  protected $iLaboratorio           = null;
  protected $iExame                 = null;
  protected $iTipoControle          = null;
  protected $iCodigoControle        = null;
  protected $sProcedimento          = '';
  protected $nValorProcedimento     = 0.0;
  protected $nAcrescimoProcedimento = 0.0;

  /*
  * Construtor da classe.
  * @param int $iSetorExame Código da tabela lab_setorexame. 
  * É necessário para determinar o exame e o laboratório
  */
  function __construct($iSetorExame) {

    $this->setSetorExame($iSetorExame);
    $this->setLaboratorio($this->getLaboratorioSetorExame($iSetorExame));
    $this->setExame($this->getExameSetorExame($iSetorExame));
    $this->setTipoControle($this->getTipoControleSetorExame($iSetorExame));
    $this->setProcedimento($this->getProcedimentoSetorExame($iSetorExame));
    $this->setValorProcedimento($this->getValorProcedimentoEstrutural($this->getProcedimento()));
    $this->setAcrescimoProcedimento($this->getAcrescimoProcedimentoSetorExame($iSetorExame));
  }
  
  /*
  * Função que seta o valor de $iSetorExame
  *
  * @param int $iValor Código do 
  * @return void
  */
  function setSetorExame($iValor) {
    $this->iSetorExame = $iValor;
  }

  /*
  * Função para obter o valor de $iSetorExame
  *
  * @return int Código do setorexame
  */
  function getSetorExame() {
    return $this->iSetorExame;
  }

  /*
  * Função que seta o valor de $iLaboratorio
  *
  * @param int $iValor Código do Laboratório
  * @return void
  */
  function setLaboratorio($iValor) {
    $this->iLaboratorio = $iValor;
  }

  /*
  * Função para obter o valor de $iLaboratorio
  *
  * @return int Código do laboratório
  */
  function getLaboratorio() {
    return $this->iLaboratorio;
  }

  /*
  * Função que seta o valor de $iExame
  *
  * @param int $iValor Código do exame
  * @return void
  */
  function setExame($iValor) {
    $this->iExame = $iValor;
  }

  /*
  * Função para obter o valor de $iExame
  *
  * @return int Código do exame
  */
  function getExame() {
    return $this->iExame;
  }

  /*
  * Função que seta o valor de $sProcedimento
  *
  * @param string $sValor Código estrutural do procedimento
  * @return void
  */
  function setProcedimento($sValor) {
    $this->sProcedimento = $sValor;
  }

  /*
  * Função para obter o valor de $sProcedimento
  *
  * @return string Código estrutural do procedimento
  */
  function getProcedimento() {
    return $this->sProcedimento;
  }

  /*
  * Função que seta o valor de $nValorProcedimento
  *
  * @param float $nValor Valor do procedimento
  * @return void
  */
  function setValorProcedimento($nValor) {
    $this->nValorProcedimento = $nValor;
  }

  /*
  * Função para obter o valor de $nValorProcedimento
  *
  * @return float Valor do procedimento
  */
  function getValorProcedimento() {
    return $this->nValorProcedimento;
  }

  /*
  * Função que seta o valor de $nAcrescimoProcedimento
  *
  * @param float $nValor Acréscimo ao valor do procedimento
  * @return void
  */
  function setAcrescimoProcedimento($nValor) {
    $this->nAcrescimoProcedimento = $nValor;
  }

  /*
  * Função para obter o valor de $nAcrescimoProcedimento
  *
  * @return float Acrescimo ao valor do procedimento
  */
  function getAcrescimoProcedimento() {
    return $this->nAcrescimoProcedimento;
  }

  /*
  * Função que seta o valor de $iTipoControle
  *
  * @param int $iValor Código do tipo de controle
  * @return void
  */
  function setTipoControle($iValor) {
    $this->iTipoControle = $iValor;
  }

  /*
  * Função para obter o valor de $iTipoControle
  *
  * @return int Código do tipo de controle
  */
  function getTipoControle() {
    return $this->iTipoControle;
  }

  /*
  * Função que seta o valor de $iCodigoControle
  *
  * @param int $iValor Código da tabela lab_controlefisicofinanceiro
  * @return void
  */
  function setCodigoControle($iValor) {
    $this->iCodigoControle = $iValor;
  }

  /*
  * Função para obter o valor de $iCodigoControle
  *
  * @return int Código da tabela lab_controlefisicofinanceiro
  */
  function getCodigoControle() {
    return $this->iCodigoControle;
  }

  /* Função que retorna o tipo de controle físico / financeiro utilizado (valores de 1 a 9).
  * Esta função já seta automaticamente o código do tipo de controle
  * Como a rotina foi modificada e agora em vez de um único tipo de controle físico / financeiro,
  * podemos ter vários, mas todos dentro de um dos grupos:
  * 1) Departamento solicitante:
  *   1 - Departamento solicitante
  *   2 - Departamento solicitante X exame
  *   3 - Departamento solicitante X grupo de exames
  *   9 - Departamento solicitante X laboratório
  * 2) Laboratório:
  *   4 - Laboratório
  *   5 - Laboratório X exame
  *   6 - Laboratório X grupo de exames
  * 3) Exame:
  *   7 - Exame
  * 4) Grupo de exames:
  *   8 - Grupo de exames
  *
  * Retorna 0 em caso de nenhum controle físico / financeiro esteja sendo usado.
  * Retorna -1 em caso haver controle físico / financeiro, mas nenhum se enquadre para o departamento / laboratório
  * em questão
  *
  * *Obs: Uma coisa que não pode acontecer é ter mais de um tipo de controle para o MESMO departamento / laboratório.
  * Tipos de controle diferentes somente para departamentos / laboratórios diferentes.
  *
  * @param int $iSetorExame Código do setorexame
  * @return int $iTipoControle Código do tipo de controle utilizado.
  */
  function getTipoControleSetorExame($iSetorExame) {

    $oDaoLabControleFisicoFinanceiro = db_utils::getdao('lab_controlefisicofinanceiro');
    $sSql                            = $oDaoLabControleFisicoFinanceiro->sql_query_file(null, 
                                                                                        'la56_i_tipocontrole, '.
                                                                                        'la56_i_codigo'
                                                                                       );
    $rs                              = $oDaoLabControleFisicoFinanceiro->sql_record($sSql);
    if ($oDaoLabControleFisicoFinanceiro->numrows > 0) {
  
      /* De acordo com o grupo do primeiro tipo de controle retornado, 
         eu procuro qual tipo de controle foi realmente definido */
      $iTmp = db_utils::fieldsMemory($rs, 0)->la56_i_tipocontrole;
      switch ($iTmp) {
  
        // Controles por departamento solicitante
        case 1: 
        case 2:
        case 3:
        case 9:
  
          $sWhere = ' la56_i_depto = '.db_getsession('DB_coddepto');
          $sSql   = $oDaoLabControleFisicoFinanceiro->sql_query_file(null, 'la56_i_tipocontrole, la56_i_codigo',
                                                                     '', $sWhere
                                                                    );
          $rs     = $oDaoLabControleFisicoFinanceiro->sql_record($sSql);
          if ($oDaoLabControleFisicoFinanceiro->numrows > 0) {

            $this->setCodigoControle(db_utils::fieldsmemory($rs, 0)->la56_i_codigo);
            return db_utils::fieldsmemory($rs, 0)->la56_i_tipocontrole;

          }
          break;
  
        // Controles por laboratório
        case 4: 
        case 5: 
        case 6: 
  
          $sWhere = ' la56_i_laboratorio = '.$this->getLaboratorio();
          $sSql   = $oDaoLabControleFisicoFinanceiro->sql_query_file(null, 'la56_i_tipocontrole, la56_i_codigo',
                                                                     '', $sWhere
                                                                    );
          $rs     = $oDaoLabControleFisicoFinanceiro->sql_record($sSql);
          if ($oDaoLabControleFisicoFinanceiro->numrows > 0) {

            $this->setCodigoControle(db_utils::fieldsmemory($rs, 0)->la56_i_codigo);
            return db_utils::fieldsmemory($rs, 0)->la56_i_tipocontrole;

          }
          break;
  
        case 7: // Controles por exame
        case 8: // Controles por grupo de exames
           
           // Como nestes dois grupos, existe somente um tipo de controle, ele é o próprio
           $this->setCodigoControle(db_utils::fieldsmemory($rs, 0)->la56_i_codigo);
           return $iTmp;
  
        default:
  
      }
  
      return -1; // Existe controle mas nenhum se encaixa, ou seja, o saldo deve ser bloqueado
  
    } else {
      return 0; // Nenhum tipo de controle informado. Saldo liberado.
    }
  }

  /*
  * Função que retorna o código do laboratório a partir do código da lab_setorexame
  *
  * @param int $iSetorExame Código do setorexame
  * @return int $iLaboratorio Código do laboratório
  */

  function getLaboratorioSetorExame($iSetorExame) {

    $iLaboratorio      = '';
  
    $oDaoLabSetorExame = db_utils::getdao('lab_setorexame');
    $sSql              = $oDaoLabSetorExame->sql_query_setorexame(null, 'la24_i_laboratorio', '', 
                                                                  "la09_i_codigo = $iSetorExame"
                                                                 );
    $rs                = $oDaoLabSetorExame->sql_record($sSql);
    if ($oDaoLabSetorExame->numrows > 0) {
      $iLaboratorio = db_utils::fieldsmemory($rs, 0)->la24_i_laboratorio;
    }
  
    return $iLaboratorio;

  }

  /*
  * Função que retorna o código do exame a partir do código da lab_setorexame.
  *
  * @param int $iSetorExame Código do setorexame
  * @return int $iExame Código do exame
  */
  function getExameSetorExame($iSetorExame) {

    $iExame            = '';
  
    $oDaoLabSetorExame = db_utils::getdao('lab_setorexame');
    $sSql              = $oDaoLabSetorExame->sql_query_file(null, 'la09_i_exame', '', 
                                                            "la09_i_codigo = $iSetorExame"
                                                           );
    $rs                = $oDaoLabSetorExame->sql_record($sSql);
    if ($oDaoLabSetorExame->numrows > 0) {
      $iExame = db_utils::fieldsmemory($rs, 0)->la09_i_exame;
    }
  
    return $iExame;

  }

  /*
  * Função para obter o código estrutural do procedimento ativo vinculado ao exame através
  * código da tabela lab_setorexame. Em caso de nenhum registro ser retornado, retorna '' (vazio).
  *
  * @param int $iSetorExame Código do setorexame
  * @return string $sProcedimento Código do procedimento
  */
  function getProcedimentoSetorExame($iSetorExame) {

    $sProcedimento     = '';
  
    $oDaoLabSetorExame = db_utils::getdao('lab_setorexame');
    $sSql              = $oDaoLabSetorExame->sql_query_exameproced(null, 'sd63_c_procedimento', '', 
                                                                   "la09_i_codigo = $iSetorExame".
                                                                   ' and la53_i_ativo = 1'
                                                                  );
    $rs                = $oDaoLabSetorExame->sql_record($sSql);
    if ($oDaoLabSetorExame->numrows > 0) {
      $sProcedimento = db_utils::fieldsmemory($rs, 0)->sd63_c_procedimento;
    }
  
    return $sProcedimento;

  }

  /*
  * Função que retorna o valor do procedimento em sua última competência. Em caso de nenhum
  * registro ser retornado, retorna 0.0.
  *
  * @param int $iSetorExame Código do setorexame
  * @return float $nValor Valor do procedimento em sua última competência
  */
  function getValorProcedimentoEstrutural($sProcedimento) {

    if (empty($sProcedimento)) {
      return 0.0;
    }

    $nValor              = 0.0;
    
    $oDaoSauProcedimento = db_utils::getdao('sau_procedimento');
    $sSql                =  $oDaoSauProcedimento->sql_query_file(null, 'sd63_f_sa as total ',
                                                                 'sd63_i_anocomp desc, sd63_i_mescomp desc limit 1', 
                                                                 "sd63_c_procedimento = '$sProcedimento' "
                                                                );
    $rs                  = $oDaoSauProcedimento->sql_record($sSql);
    if ($oDaoSauProcedimento->numrows > 0) {
      $nValor = db_utils::fieldsmemory($rs, 0)->total;
    }
    
    return $nValor;

  }

  /*
  * Função para obter o valor de acréscimo ao valor do procedimento ativo para o setorexame
  * (campo la53_n_acrescimo da tabela lab_exameproced). Se o exame não possuir exame ativo
  * vinculado, retornará 0.
  *
  * @param int $iSetorExame Código do setorexame
  * @return float $nAcrescimo Acréscimo ao valor do procedimento
  */
  function getAcrescimoProcedimentoSetorExame($iSetorExame) {

    $nAcrescimo        = 0;
    $oDaoLabSetorExame = db_utils::getdao('lab_setorexame');
    $sSql              = $oDaoLabSetorExame->sql_query_exameproced(null, 'la53_n_acrescimo', '', 
                                                                   "la09_i_codigo = $iSetorExame".
                                                                   ' and la53_i_ativo = 1'
                                                                  );
    $rs                = $oDaoLabSetorExame->sql_record($sSql);
    if ($oDaoLabSetorExame->numrows > 0) {
      $nAcrescimo = db_utils::fieldsmemory($rs, 0)->la53_n_acrescimo;
    }
    
    return $nAcrescimo;

  }

  /* Função que retorna um objeto com as informações do controle que se enquadrar as informações passadas,
  * buscando na tabela lab_controlefisicofianceiro o registro que se enquadrar com o exame,
  * laboratório, data, grupo, etc. Se nenhum registro se enquadrar com as informações passadas, a função
  * retorna null.
  *
  * @param date $dData Data para a qual quer se obter a informação sobre o controle utilizado.
  * @return Object $oInfoControle Objeto com as informações do controle de exames utilizado.
  */
  function getInfoControle($dData) {

    $oDaoLabControleFisicoFinanceiro = db_utils::getdao('lab_controlefisicofinanceiro');
    $sWhere                          = "la56_d_ini <= '$dData' and (la56_d_fim is null or la56_d_fim >= '$dData') ";
    $sOrderBy                        = '';
  
    switch ($this->getTipoControle()) {
  
      case 1:
        
        $sWhere .= 'and la56_i_depto = '.db_getsession('DB_coddepto');
        break;
      
      case 2:
  
        $sWhere .= 'and la56_i_depto = '.db_getsession('DB_coddepto');
        $sWhere .= ' and la56_i_exame = '.$this->getExame();
        break;
        
      case 3:
  
        $sWhere .= 'and la56_i_depto = '.db_getsession('DB_coddepto');
        $sWhere .= " and (sd60_c_grupo || case when sd61_c_subgrupo is null then '' else  sd61_c_subgrupo end ";
        $sWhere .= " || case when sd62_c_formaorganizacao is null then '' else  sd62_c_formaorganizacao end) = ";
        $sWhere .= " substr('".$this->getProcedimento()."', 1, char_length(sd60_c_grupo || ";
        $sWhere .= " case when sd61_c_subgrupo is null then '' else  sd61_c_subgrupo end ";
        $sWhere .= " || case when sd62_c_formaorganizacao is null then '' else  sd62_c_formaorganizacao end))  ";
        break;
      
      case 4:
  
        $sWhere .= 'and la56_i_laboratorio = '.$this->getLaboratorio();
        break;
      
      case 5:
  
        $sWhere .= 'and la56_i_laboratorio = '.$this->getLaboratorio();
        $sWhere .= ' and la56_i_exame = '.$this->getExame();
        break;
      
      case 6:
  
        $sWhere .= 'and la56_i_laboratorio = '.$this->getLaboratorio();
        $sWhere .= " and (sd60_c_grupo || case when sd61_c_subgrupo is null then '' else  sd61_c_subgrupo end ";
        $sWhere .= " || case when sd62_c_formaorganizacao is null then '' else  sd62_c_formaorganizacao end) = ";
        $sWhere .= " substr('".$this->getProcedimento()."', 1, char_length(sd60_c_grupo || ";
        $sWhere .= " case when sd61_c_subgrupo is null then '' else  sd61_c_subgrupo end ";
        $sWhere .= " || case when sd62_c_formaorganizacao is null then '' else  sd62_c_formaorganizacao end))  ";
        break;
      
      case 7:
  
        $sWhere .= "and (sd60_c_grupo || case when sd61_c_subgrupo is null then '' else  sd61_c_subgrupo end ";
        $sWhere .= " || case when sd62_c_formaorganizacao is null then '' else  sd62_c_formaorganizacao end) = ";
        $sWhere .= " substr('".$this->getProcedimento()."', 1, char_length(sd60_c_grupo || ";
        $sWhere .= " case when sd61_c_subgrupo is null then '' else  sd61_c_subgrupo end ";
        $sWhere .= " || case when sd62_c_formaorganizacao is null then '' else  sd62_c_formaorganizacao end))  ";
        break;
      
      case 8:
  
        $sWhere .= 'and la56_i_exame = '.$this->getExame();
        break;
  
      case 9:
  
        $sWhere .= 'and la56_i_depto = '.db_getsession('DB_coddepto');
        $sWhere .= ' and la56_i_laboratorio = '.$this->getLaboratorio();
        break;
        
      default:
        
        $sWhere = 'syntax error injection';
        break;
  
    }
  
    $sSql = $oDaoLabControleFisicoFinanceiro->sql_query_controle(null, '*', $sOrderBy, $sWhere);
    $rs   = $oDaoLabControleFisicoFinanceiro->sql_record($sSql);
    if ($oDaoLabControleFisicoFinanceiro->numrows > 0) {
      return db_utils::fieldsmemory($rs, 0);
    }
    return null;

  }

  /*
  * Função que retorna o número de exames já agendados que contam para o controle físico / financeiro
  * em questão. Quando o teto for físico, retorna o número de agendamentos de exames já realizados que
  * se enquadrem no tipo de controle. Quando financeiro, retorna o valor já gasto com os agendamentos
  * de exames (já considerando o valor de acréscimo ao valor do procedimento).
  *
  * @param Object $oInfoControle Objeto com as informações do controle de exames utilizado.
  * @param date $dData Data para a qual quer se obter o Saldo gasto. Se o controle for mensal,
  * vai retornar o saldo gasto para todo o mês em vigência.
  * @return float $nSaldoGasto Saldo gasto.
  */
  function getSaldoGasto($oInfoControle, $dData) {

    $oDaoLabAutoriza  = new cl_lab_autoriza();
    $sWhere           = '';
    $sWhereData       = '';
    $sCampos          = '';
    $sOrderBy         = '';
    $dIni             = '';
    $dFim             = '';
    
    // Se o controle for mensal, determino as datas de início e fim do período atual
    if ($oInfoControle->la56_i_periodo == 2) { // Mensal
      
      $tAtual    = strtotime($dData);
      $sDiaIni   = substr($oInfoControle->la56_d_ini, 8, 2);
      $sDiaAtual = date('d', $tAtual);
      $sMesAtual = date('m', $tAtual);
      $sAnoAtual = date('Y', $tAtual);
  
      if ($sDiaAtual < $sDiaIni) {
        $dIni = date('Y-m-d', strtotime("$sAnoAtual-$sMesAtual-$sDiaIni -1 month"));
      } else {
        $dIni = date('Y-m-d', strtotime("$sAnoAtual-$sMesAtual-$sDiaIni"));
      }
      $dFim = date('Y-m-d', strtotime("$dIni +1 month -1 day"));
  
      $sWhereData .= " and la21_d_data between '$dIni' and '$dFim' ";
    } else {
      $sWhereData .= " and la21_d_data = '$dData' ";
    }
  
    switch ($this->getTipoControle()) {
  
      case 1:
        
        $sWhere .= ' la22_i_departamento = '.db_getsession('DB_coddepto');
        break;
      
      case 2:
  
        $sWhere .= ' la22_i_departamento = '.db_getsession('DB_coddepto');
        $sWhere .= ' and la09_i_exame = '.$this->getExame();
        break;
        
      case 3:
        
        $sTmp    = $oInfoControle->sd60_c_grupo.$oInfoControle->sd61_c_subgrupo.$oInfoControle->sd62_c_formaorganizacao;
        $sWhere .= ' la22_i_departamento = '.db_getsession('DB_coddepto');
        $sWhere .= ' and substr(sd63_c_procedimento, 1, '.strlen($sTmp).") = '$sTmp' ";
        break;
   
      case 4:
  
        $sWhere .= ' la24_i_laboratorio = '.$this->getLaboratorio();
        break;
      
      case 5:
  
        $sWhere .= '  la24_i_laboratorio = '.$this->getLaboratorio();
        $sWhere .= ' and la09_i_exame = '.$this->getExame();
        break;
      
      case 6:
  
        $sTmp    = $oInfoControle->sd60_c_grupo.$oInfoControle->sd61_c_subgrupo.$oInfoControle->sd62_c_formaorganizacao;
        $sWhere .= ' la24_i_laboratorio = '.$this->getLaboratorio();
        $sWhere .= ' and substr(sd63_c_procedimento, 1, '.strlen($sTmp).") = '$sTmp' ";
        break;
      
      case 7:
  
        $sTmp    = $oInfoControle->sd60_c_grupo.$oInfoControle->sd61_c_subgrupo.$oInfoControle->sd62_c_formaorganizacao;
        $sWhere .= ' substr(sd63_c_procedimento, 1, '.strlen($sTmp).") = '$sTmp' ";
        break;
      
      case 8:
  
        $sWhere .= ' la09_i_exame = '.$this->getExame();
        break;
  
      case 9:
  
        $sWhere .= ' la22_i_departamento = '.db_getsession('DB_coddepto');
        $sWhere .= ' and la24_i_laboratorio = '.$this->getLaboratorio();
        break;
  
      default:
        
        $sWhere = 'purposeful syntax error injection';
        break;
  
    }
  
    $sWhere .= $sWhereData;
  
    if ($oInfoControle->la56_i_teto == 1) { // Físico
      $sCampos = ' sum(la21_i_quantidade) as total ';
    } else {
  
      // Pego sempre os valores dos procedimentos na última competência
      $sCampos  = ' sum((select case ';
      $sCampos .= '               when lab_exameproced.la53_n_acrescimo  is null ';
      $sCampos .= '                 then sd63_f_sa + sd63_f_sp ';
      $sCampos .= '               else  sd63_f_sa + sd63_f_sp + lab_exameproced.la53_n_acrescimo ';
      $sCampos .= '             end ';
      $sCampos .= '        from sau_procedimento as a ';
      $sCampos .= '          where a.sd63_c_procedimento = sau_procedimento.sd63_c_procedimento ';
      $sCampos .= '            order by sd63_i_anocomp desc, sd63_i_mescomp desc limit 1)*la21_i_quantidade) as total ';
    }
   
    $sSql = $oDaoLabAutoriza->sql_query_controle(null, $sCampos, $sOrderBy, $sWhere);
    $rs   = $oDaoLabAutoriza->sql_record($sSql);

    if ($oDaoLabAutoriza->numrows > 0) {
      return db_utils::fieldsmemory($rs, 0)->total;
    }

    return 0;
  }

  /*
  * Função que retorna o número de exames já agendados para determinado exame em um determinado
  * laboratório (setorexame) na data indicada.
  * Esta função é utilizada quando nenhum controle físico / financeiro for informado.
  *
  * @param date $dData Data para a qual quer obter o número de exames já agendados.
  * @return int $iSaldoGasto Quantidade de agendamentos realizados para a data.
  */
  function getNumeroExamesAgendados($dData) {

    $oDaoLabRequiItem = db_utils::getdao('lab_requiitem');
    $sWhere           = " la21_d_data = '$dData' ";
    $sWhere          .= ' and la24_i_laboratorio = '.$this->getLaboratorio();
    $sWhere          .= ' and la09_i_exame = '.$this->getExame();
    
    $sSql             = $oDaoLabRequiItem->sql_query_controle(null, 'sum(la21_i_quantidade) as total', '', $sWhere);
    $rs               = $oDaoLabRequiItem->sql_record($sSql);
    if ($oDaoLabRequiItem->numrows > 0) {
      return db_utils::fieldsmemory($rs, 0)->total;
    }
    
    return 0;

  }

}