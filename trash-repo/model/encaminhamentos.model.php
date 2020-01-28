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

/**
 * Model para controle dos encaminhamentos 
 * @author Tony Farney B. M. Ribeiro  
 */
class encaminhamento {
  
  function __construct() {

  }

  /*  
   * Obtem o CGS de uma FAA.
   * 
   * @param   integer  $iSd24_i_codigo  codigo da FAA
   * @return  object   contem numero do CGS e nome
   */
  function getCgsFaa($iSd24_i_codigo) {
 
    $oDaoProntuarios = db_utils::getdao('prontuarios');
    $sSql = $oDaoProntuarios->sql_query($iSd24_i_codigo,' sd24_i_numcgs, z01_v_nome ');
    $rsProntuarios = $oDaoProntuarios->sql_record($sSql);

    if($oDaoProntuarios->numrows > 0) {
      $oDadosProntuarios = db_utils::fieldsmemory($rsProntuarios,0);
    } else {
      return null;
    }

    return $oDadosProntuarios;

  }
 
  /*  
   * Obtem as unidades de um medico.
   * 
   * @param   integer  $iSd04_i_medico  codigo do medico
   * @return  object   contem as unidades do medico com codigo e descricao
   */
  function getUnidadesMedico($iSd04_i_medico) {

    $oUnidades = new stdClass();
    $oDaoUnidademedicos = db_utils::getdao('unidademedicos');
    $sSql = $oDaoUnidademedicos->sql_query('',
                                           " sd04_i_unidade, trim(sd04_i_unidade || ' - ' 
                                           || descrdepto) as descr_unidade ",
                                           '',
                                           " sd04_i_medico = $iSd04_i_medico "
                                          );
    $rsUnidademedicos = $oDaoUnidademedicos->sql_record($sSql);
    $iLinhas = $oDaoUnidademedicos->numrows;
    if($iLinhas > 0) {
  
     /**
      * Obtem todas as unidades do profissional, adicionando-as ao objeto unidade
      */
      for($iCont = 0; $iCont < $iLinhas; $iCont++) {

         $oDadosUnidademedicos = db_utils::fieldsmemory($rsUnidademedicos,$iCont);
         $oUnidades->oUnidades[$iCont]->iCodigo = $oDadosUnidademedicos->sd04_i_unidade;
         $oUnidades->oUnidades[$iCont]->sDescr = urlencode($oDadosUnidademedicos->descr_unidade);

      }

    } else {

      $oUnidades->oUnidades = '';
      return $oUnidades;

    }

    return $oUnidades;

  }

  /*  
   * Obtem o procedimento de acordo com o procedimento e a especialidade informados
   * 
   * @param   string   $sSd63_c_procedimento    procedimento
   * @param   int      $iRh70_sequencial        codigo da especialidade
   * @param   int      $iUnidade                usada para filtrar por servicos da unidade, caso seja informada
   * @return  object   contem codigo e descricao do procedimento
   */
  function getProcedimento($sSd63_c_procedimento, $iRh70_sequencial, $iUnidade = 0) {
    
    if($iUnidade == 0) {
      $lFiltraServico = false;
    } else {
      $lFiltraServico = true;
    }

    $oDaoSau_proccbo = db_utils::getdao('sau_proccbo_ext');
    $sSql = $oDaoSau_proccbo->sql_query_ext(null, ' sd63_c_nome, sd96_i_procedimento ', null, 
                                            " sd63_c_procedimento = '$sSd63_c_procedimento' ", $iUnidade, 
                                            $lFiltraServico);
    $rsSau_proccbo = $oDaoSau_proccbo->sql_record($sSql);
    $iLinhas = $oDaoSau_proccbo->numrows;
    if ($iLinhas > 0) {
      $oDadosSau_proccbo = db_utils::fieldsmemory($rsSau_proccbo,0);
    } else {
      return null;
    }

    return $oDadosSau_proccbo;

  }

  /*  
   * Obtem a especialidade de acordo com a especialidade e medico, se informado
   * 
   * @param   int      $iRh70_estrurutal   codigo estrutrutal da especialidade
   * @param   boolean  $lFiltraPorMedico   Determina se filtra por medico ou nao
   * @param   int      $iSd04_i_medico     codigo do medico
   * @param   int      $iSd04_i_unidade    codigo da unidade, se filtra por medico, filtra por unidade tambem
   * @return  object   contem a descricao e o codigo da especialidade
   */
  function getEspecialidade($iRh70_estrutural, $lFiltraPorMedico = false, $iSd04_i_medico = '',
                            $iSd04_i_unidade = '') {

    $sSql  = 'select distinct rh70_descr, ';
    $sSql .= '                rh70_sequencial ';
    $sSql .= '  from especmedico ';
    $sSql .= '    inner join rhcbo on rhcbo.rh70_sequencial = especmedico.sd27_i_rhcbo ';
    $lFiltraPorMedico ? 
      $sSql .= '  inner join unidademedicos on unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed ' : '';
    $sSql .= "      where sd27_c_situacao = 'A' ";
    $sSql .= "        and rh70_estrutural = '$iRh70_estrutural' ";
    $lFiltraPorMedico ?
      $sSql .= "      and sd04_i_unidade = $iSd04_i_unidade and sd04_i_medico = $iSd04_i_medico " : '';
   
    $oDaoEspecmedico = db_utils::getdao('especmedico');
    $sSql = $oDaoEspecmedico->sql_query_especialidade($iRh70_estrutural, $lFiltraPorMedico,
                                                      $iSd04_i_medico, $iSd04_i_unidade,
                                                      'rh70_sequencial, rh70_descr');
    $rsEspecmedico = $oDaoEspecmedico->sql_record($sSql);
    $iLinhas = $oDaoEspecmedico->numrows;
    if ($iLinhas > 0) {   
      $oDadosEspecmedico = db_utils::fieldsmemory($rsEspecmedico,0);
    } else {
      return null;
    }

    return $oDadosEspecmedico;

  }
  
  /*  
   * Obtem os procedimentos relacionados ao encaminhamento indicado
   * 
   * @param   integer  $iSd04_i_medico  codigo do medico
   * @return  object   contem as unidades do medico com codigo e descricao
   */
  function getProcedimentosEncaminhamento($iS142_i_codigo) {

    $oDaosau_procencaminhamento = db_utils::getdao('sau_procencaminhamento');
    $sSql = $oDaosau_procencaminhamento->sql_query2('',
                                                    " s143_i_procedimento, sd63_c_nome, sd63_c_procedimento ",'',
                                                    " s143_i_encaminhamento = $iS142_i_codigo ", ' s143_i_codigo '
                                                   );
    $rsSau_procencaminhamento = $oDaosau_procencaminhamento->sql_record($sSql);
    $iLinhas = $oDaosau_procencaminhamento->numrows;

    if($iLinhas > 0) {

      /**
      * Obtem todos os procedimentos, adicionando-as ao objeto oProcedimentos
      */
      for($iCont = 0; $iCont < $iLinhas; $iCont++) {

        $oDadosSau_procencaminhamento = db_utils::fieldsmemory($rsSau_procencaminhamento,$iCont);
        $oProcedimentos->oProcedimentos[$iCont]->iCodigo = $oDadosSau_procencaminhamento->s143_i_procedimento;
        $oProcedimentos->oProcedimentos[$iCont]->sDescr = urlencode($oDadosSau_procencaminhamento->sd63_c_nome);
        $oProcedimentos->oProcedimentos[$iCont]->sProcedimento = $oDadosSau_procencaminhamento->sd63_c_procedimento;

      }

    } else {

      $oProcedimentos->oProcedimentos = '';
      return $oProcedimentos;

    }

    return $oProcedimentos;

  }


  /*  
   * Deleta os procedimentos relacionados ao encaminhamento indicado e insere os novos procedimentos
   * 
   * @param   integer  $iSd04_i_medico  codigo do encaminhamento
   * @param   array    $aProcedimentos  vetor como os codigos dos novos procedimentos a serem incluidos
   * @return  boolean  indica se os encaminhamentos foram ou nao excluidos
   */
  function alteraProcedimentosEncaminhamento($iS142_i_codigo, $aProcedimentos) {

    if(empty($iS142_i_codigo) || empty($aProcedimentos) || !db_utils::inTransaction()) {
      return false;
    }

    $oDaosau_procencaminhamento = db_utils::getdao('sau_procencaminhamento');
    $sSql = $oDaosau_procencaminhamento->sql_query2(null, ' s143_i_codigo ', null,
                                                    " s143_i_encaminhamento = $iS142_i_codigo ");
    $rsSau_procencaminhamento = $oDaosau_procencaminhamento->sql_record($sSql);
    $iLinhas = $oDaosau_procencaminhamento->numrows;
    /*
    * Laco que exclui todos os procedimentos relacionados ao encaminhamento
    */
    for($iCont = 0; $iCont < $iLinhas; $iCont++) {
     
      $oDadosSau_procencaminhamento = db_utils::fieldsmemory($rsSau_procencaminhamento, $iCont);
      $oDaosau_procencaminhamento->excluir($oDadosSau_procencaminhamento->s143_i_codigo);
      if($oDaosau_procencaminhamento->erro_status == "0") {
        return false;
      }

    }

    /*
    * Laco que inclui todos os novos procedimentos relacionados ao encaminhamento
    */
    $oDaosau_procencaminhamento->s143_i_encaminhamento = $iS142_i_codigo;
    for($iCont = 0; $iCont < count($aProcedimentos); $iCont++) {

      $oDaosau_procencaminhamento->s143_i_procedimento = $aProcedimentos[$iCont];
      $oDaosau_procencaminhamento->incluir(null);
      if($oDaosau_procencaminhamento->erro_status == "0") {
        return false;
      }
 

    }

    return true;

  }

}