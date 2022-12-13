<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

/*
AS FUNÇÕES RETORNAM VALORES BOOLEANOS QUE INDICAM SE A IMPORTAÇÃO FOI REALIZADA COM SUCESSO OU HOUVE ERRO.
   TRUE -> SUCESSSO;
   FALSE -> NÃO SUCESSO
*/


// sau_subgrupo
function funcSubgrupo($sOrigem, $sCompetencia) {
  
  global $iContRegInseridos;

  $oDaoSauGrupo    = db_utils::getdao('sau_grupo');
  $oDaoSauSubGrupo = db_utils::getdao('sau_subgrupo');
  
  $aFile           = file($sOrigem);
  $iLinhas         = count($aFile);

  for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

    $sCampo1    = trim(substr($aFile[$iCont], 2, 2)); // código do subgrupo
    $sCampo2    = strtoupper(TiraAcento(trim(str_replace("'", '', substr($aFile[$iCont], 4, 100))))); // nome
    $sCampo3    = trim(substr($aFile[$iCont], 104, 4)); // ano
    $sCampo4    = trim(substr($aFile[$iCont], 108, 2)); // mês
    
    /* Bloco para obter o grupo */
    $sSql       = $oDaoSauGrupo->sql_query_file(null, 'sd60_i_codigo', null, "sd60_c_grupo = '".
                                                trim(substr($aFile[$iCont], 0, 2))."'".
                                                " and sd60_i_anocomp = $sCampo3 ".
                                                " and sd60_i_mescomp = $sCampo4 "
                                               );

    $rsSauGrupo = $oDaoSauGrupo->sql_record($sSql);
    if ($oDaoSauGrupo->erro_status == '0') {
      return false;
    }

    $iGrupo = db_utils::fieldsmemory($rsSauGrupo, 0)->sd60_i_codigo;

    /* Verifico se o registro já foi incluído. */
    $sSql = $oDaoSauSubGrupo->sql_query_file(null, 'sd61_i_codigo', '', "sd61_c_subgrupo = '$sCampo1' ".
                                             " and sd61_i_grupo = $iGrupo and sd61_c_nome = '$sCampo2' ".
                                             " and sd61_i_anocomp = $sCampo3 and sd61_i_mescomp = $sCampo4 "
                                            );
    $oDaoSauSubGrupo->sql_record($sSql);
    if ($oDaoSauSubGrupo->numrows > 0) { // Se já foi incluído, vou para o próximo registro
      continue;
    }

    $oDaoSauSubGrupo->sd61_c_subgrupo = $sCampo1;
    $oDaoSauSubGrupo->sd61_i_grupo    = $iGrupo;
    $oDaoSauSubGrupo->sd61_c_nome     = $sCampo2;
    $oDaoSauSubGrupo->sd61_i_anocomp  = $sCampo3;
    $oDaoSauSubGrupo->sd61_i_mescomp  = $sCampo4;

    $oDaoSauSubGrupo->incluir(null);
    if ($oDaoSauSubGrupo->erro_status == '0') {

      $oDaoSauSubGrupo->erro(true, false);
      return false;

    }

    $iContRegInseridos++; // Incremento o número de registros inseridos

  }

  return true;
  
}

// sau_formaorganizacao
function funcFormaOrganizacao($sOrigem, $sCompetencia) {

  global $iContRegInseridos;

  $oDaoSauGrupo            = db_utils::getdao('sau_grupo');
  $oDaoSauSubGrupo         = db_utils::getdao('sau_subgrupo');
  $oDaoSauFormaOrganizacao = db_utils::getdao('sau_formaorganizacao');
  
  $aFile                   = file($sOrigem);
  $iLinhas                 = count($aFile);

  for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

    $sCampo1    = trim(substr($aFile[$iCont], 4, 2)); // código da forma de organização
    $sCampo2    = strtoupper(TiraAcento(trim(str_replace("'", '', substr($aFile[$iCont], 6, 100))))); // nome
    $sCampo3    = trim(substr($aFile[$iCont], 106, 4)); // ano
    $sCampo4    = trim(substr($aFile[$iCont], 110, 2)); // mês
    
    /* Bloco para obter o grupo */
    $sSql       = $oDaoSauGrupo->sql_query_file(null, 'sd60_i_codigo', null, "sd60_c_grupo = '".
                                                trim(substr($aFile[$iCont], 0, 2))."'".
                                                " and sd60_i_anocomp = $sCampo3 ".
                                                " and sd60_i_mescomp = $sCampo4 "
                                               );

    $rsSauGrupo = $oDaoSauGrupo->sql_record($sSql);
    if ($oDaoSauGrupo->erro_status == '0') {
      return false;
    }

    $iGrupo        = db_utils::fieldsmemory($rsSauGrupo, 0)->sd60_i_codigo;

    /* Bloco para obter o subgrupo */
    $sSql          = $oDaoSauSubGrupo->sql_query_file(null, 'sd61_i_codigo', null, "sd61_c_subgrupo = '".
                                                      trim(substr($aFile[$iCont], 2, 2))."'".
                                                      " and sd61_i_anocomp = $sCampo3 ".
                                                      " and sd61_i_mescomp = $sCampo4 "
                                                     );

    $rsSauSubGrupo = $oDaoSauSubGrupo->sql_record($sSql);
    if ($oDaoSauSubGrupo->erro_status == '0') {
      return false;
    }

    $iSubGrupo = db_utils::fieldsmemory($rsSauSubGrupo, 0)->sd61_i_codigo;

    /* Verifico se o registro já foi incluído. */
    $sSql = $oDaoSauFormaOrganizacao->sql_query_file(null, 'sd62_i_codigo', '', "sd62_i_grupo = $iGrupo ".
                                                     " and sd62_i_subgrupo = $iSubGrupo ".
                                                     " and sd62_c_formaorganizacao = '$sCampo1' ".
                                                     " and sd62_i_anocomp = $sCampo3 and sd62_i_mescomp = $sCampo4"
                                                    );
    $oDaoSauFormaOrganizacao->sql_record($sSql);
    if ($oDaoSauFormaOrganizacao->numrows > 0) { // Se já foi incluído, vou para o próximo registro
      continue;
    }

    $oDaoSauFormaOrganizacao->sd62_i_grupo            = $iGrupo;
    $oDaoSauFormaOrganizacao->sd62_i_subgrupo         = $iSubGrupo;
    $oDaoSauFormaOrganizacao->sd62_c_formaorganizacao = $sCampo1;
    $oDaoSauFormaOrganizacao->sd62_c_nome             = $sCampo2;
    $oDaoSauFormaOrganizacao->sd62_i_anocomp          = $sCampo3;
    $oDaoSauFormaOrganizacao->sd62_i_mescomp          = $sCampo4;

    $oDaoSauFormaOrganizacao->incluir(null);
    if ($oDaoSauFormaOrganizacao->erro_status == '0') {

      $oDaoSauFormaOrganizacao->erro(true, false);
      return false;

    }

    $iContRegInseridos++; // Incremento o número de registros inseridos

  }

  return true;
  
}


//01 Importa CID
function funcCid($sOrigem, $sCompetencia) {

  global $iContRegInseridos;

  $oDaoSauAgravo = db_utils::getdao('sau_agravo');
  $oDaoSauCid    = db_utils::getdao('sau_cid');
  
  $sSql          = $oDaoSauAgravo->sql_query_file(null, 'sd71_i_codigo');
  $oDaoSauAgravo->sql_record($sSql);

  /* Se não houver registros na sau_agravo, devem ser incluídos */
  if ($oDaoSauAgravo->numrows == 0) {

    $oDaoSauAgravo->sd71_c_nome = 'SEM AGRAVO';
    $oDaoSauAgravo->incluir('0'); // tem que passar como string, senão não inclui
    if ($oDaoSauAgravo->erro_status == '0') {

      $oDaoSauAgravo->erro(true, false);
      return false;

    }
    
    /* Execucao do nextval para atualizar a sequencia e nao dar erro no metodo incluir */
    $oDaoSauAgravo->sql_record("select nextval('sau_agravo_sd71_i_codigo_seq')");

    $oDaoSauAgravo->sd71_c_nome = 'AGRAVO DE NOTIFICACAO';
    $oDaoSauAgravo->incluir(1);
    if ($oDaoSauAgravo->erro_status == '0') {

      $oDaoSauAgravo->erro(true, false);
      return false;

    }

    /* Execucao do nextval para atualizar a sequencia e nao dar erro no metodo incluir */
    $oDaoSauAgravo->sql_record("select nextval('sau_agravo_sd71_i_codigo_seq')");

    $oDaoSauAgravo->sd71_c_nome = 'AGRAVO DE BLOQUEIO';
    $oDaoSauAgravo->incluir(2);
    if ($oDaoSauAgravo->erro_status == '0') {

      $oDaoSauAgravo->erro(true, false);
      return false;

    }

  }
  
  $aFile   = file($sOrigem);
  $iLinhas = count($aFile);

  for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

    $sCampo1 = trim(substr($aFile[$iCont], 0, 4)); // cid (estrutural)
    $sCampo2 = strtoupper(TiraAcento(trim(str_replace("'", '', substr($aFile[$iCont], 4, 100)))));
    $sCampo3 = trim(substr($aFile[$iCont], 104, 1));
    $sCampo4 = trim(substr($aFile[$iCont], 105, 1));
    
    // verifico se o CID ainda não foi incluído
    $sSql    = $oDaoSauCid->sql_query_file(null, 'sd70_i_codigo', null, " sd70_c_cid = '$sCampo1'");
    $oDaoSauCid->sql_record($sSql);
    if ($oDaoSauCid->numrows > 0) {
      continue; // não inclui
    } else { 
      $oDaoSauCid->erro_status = null; // tiro o estado de erro
    }

    $oDaoSauCid->sd70_c_cid    = $sCampo1;
    $oDaoSauCid->sd70_c_nome   = $sCampo2;
    $oDaoSauCid->sd70_i_agravo = $sCampo3;
    $oDaoSauCid->sd70_c_sexo   = $sCampo4;

    $oDaoSauCid->incluir(null);
    if ($oDaoSauCid->erro_status == '0') {

      $oDaoSauCid->erro(true, false);
      return false;

    }

    $iContRegInseridos++; // Incremento o número de registros inseridos

  }

  return true;
  
}

//13 Importa Grupo Habilitação
function funcGrupoHabilitacao($sOrigem, $sCompetencia) {
  
  global $iContRegInseridos;

  $oDaoSauHabilitacao      = db_utils::getdao('sau_habilitacao');
  $oDaoSauGrupoHabilitacao = db_utils::getdao('sau_grupohabilitacao');

  $iAno                    = substr($sCompetencia, 0, 4);
  $iMes                    = substr($sCompetencia, 4, 2);
  
  $aFile                   = file($sOrigem);
  $iLinhas                 = count($aFile);

  for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

    $aHabilitacao = explode(' e ', trim(substr($aFile[$iCont], 4, 20)));
    $iLinhas2     = count($aHabilitacao);
    if ($iLinhas2 == 1) {
       
      // o separador pode ser '-'
      $aHabilitacao = explode('-', trim(substr($aFile[$iCont], 4, 20)));
      $iLinhas2 = count($aHabilitacao);

    }
    for ($iCont2 = 0; $iCont2 < $iLinhas2; $iCont2++) {

      $sCampo1  = trim(substr($aFile[$iCont], 0, 4));
      $sCampo2  = strtoupper(TiraAcento(trim(str_replace("'", '', substr($aFile[$iCont], 24, 250)))));

      $sSql     = $oDaoSauHabilitacao->sql_query_file(null, 'sd75_i_codigo', null, " sd75_c_habilitacao = '".
                                                      $aHabilitacao[$iCont2]."' and sd75_i_anocomp = $iAno".
                                                      " and sd75_i_mescomp = $iMes"
                                                     );

      $rsSauHab = $oDaoSauHabilitacao->sql_record($sSql);


      if ($oDaoSauHabilitacao->erro_status == '0') {
        return false;
      }

      $iHabilitacao = db_utils::fieldsmemory($rsSauHab, 0)->sd75_i_codigo;


      /* Verifico se o registro já foi incluído. */
      $sSql = $oDaoSauGrupoHabilitacao->sql_query_file(null, 'sd76_i_codigo', '', 
                                                       " sd76_c_grupohabilitacao = '$sCampo1' ".
                                                       " and sd76_c_descricao = '$sCampo2' ".
                                                       " and sd76_i_habilitacao = $iHabilitacao"
                                                      );
      $oDaoSauGrupoHabilitacao->sql_record($sSql);
      if ($oDaoSauGrupoHabilitacao->numrows > 0) { // Se já foi incluído, vou para o próximo registro
        continue;
      }

      $oDaoSauGrupoHabilitacao->sd76_c_grupohabilitacao = $sCampo1;
      $oDaoSauGrupoHabilitacao->sd76_i_habilitacao      = $iHabilitacao;
      $oDaoSauGrupoHabilitacao->sd76_c_descricao        = $sCampo2;

      $oDaoSauGrupoHabilitacao->incluir(null);
      if ($oDaoSauGrupoHabilitacao->erro_status == '0') {

        $oDaoSauGrupoHabilitacao->erro(true, false);
        return false;

      }

      $iContRegInseridos++; // Incremento o número de registros inseridos

    }
      
  }

  return true;  

}

//14 Importa Procedimentos
function funcProcedimento($sOrigem, $sCompetencia) {

  global $iContRegInseridos;

  $oDaoSauComplexidade  = db_utils::getdao('sau_complexidade');
  $oDaoSauFinanciamento = db_utils::getdao('sau_financiamento');
  $oDaoSauRubrica       = db_utils::getdao('sau_rubrica');
  $oDaoSauProcedimento  = db_utils::getdao('sau_procedimento');

  $sSql                 = $oDaoSauComplexidade->sql_query_file(null, 'sd69_i_codigo');
  $oDaoSauComplexidade->sql_record($sSql);

  /* Se não houver registros na sau_complexidade, devem ser incluídos */
  if ($oDaoSauComplexidade->numrows == 0) {

    $oDaoSauComplexidade->sd69_c_nome = 'NÃO SE APLICA';
    $oDaoSauComplexidade->incluir('0'); // tem que passar como string, senão não inclui
    if ($oDaoSauComplexidade->erro_status == '0') {
      $oDaoSauComplexidade->erro(true, false);
      return false;

    }
    
    /* Execucao do nextval para atualizar a sequencia e nao dar erro no metodo incluir */
    $oDaoSauComplexidade->sql_record("select nextval('sau_complexidade_sd69_i_codigo_seq')");

    $oDaoSauComplexidade->sd69_c_nome = 'ATENÇÃO BÁSICA COMPLEXIDADE';
    $oDaoSauComplexidade->incluir(1);
    if ($oDaoSauComplexidade->erro_status == '0') {
      $oDaoSauComplexidade->erro(true, false);
      return false;

    }
    
    /* Execucao do nextval para atualizar a sequencia e nao dar erro no metodo incluir */
    $oDaoSauComplexidade->sql_record("select nextval('sau_complexidade_sd69_i_codigo_seq')");

    $oDaoSauComplexidade->sd69_c_nome = 'MÉDIA COMPLEXIDADE';
    $oDaoSauComplexidade->incluir(2);
    if ($oDaoSauComplexidade->erro_status == '0') {
      $oDaoSauComplexidade->erro(true, false);
      return false;

    }
    
    /* Execucao do nextval para atualizar a sequencia e nao dar erro no metodo incluir */
    $oDaoSauComplexidade->sql_record("select nextval('sau_complexidade_sd69_i_codigo_seq')");

    $oDaoSauComplexidade->sd69_c_nome = 'ALTA COMPLEXIDADE';
    $oDaoSauComplexidade->incluir(3);
    if ($oDaoSauComplexidade->erro_status == '0') {
      $oDaoSauComplexidade->erro(true, false);
      return false;

    }
    
  }

  $aFile   = file($sOrigem);
  $iLinhas = count($aFile);

  for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

    $iComplexidade = trim(substr($aFile[$iCont], 260, 1));
    $sCampo1       = trim(substr($aFile[$iCont], 0, 10));
    $sCampo2       = strtoupper(TiraAcento(trim(str_replace("'", '', substr($aFile[$iCont], 10, 250)))));
    $sCampo3       = trim(substr($aFile[$iCont], 261, 1));
    $sCampo4       = trim(substr($aFile[$iCont], 262, 4));
    $sCampo5       = trim(substr($aFile[$iCont], 266, 4));
    $sCampo6       = trim(substr($aFile[$iCont], 270, 4));
    $sCampo7       = trim(substr($aFile[$iCont], 274, 4));
    $sCampo8       = trim(substr($aFile[$iCont], 278, 4));
    $sCampo9       = trim(substr($aFile[$iCont], 282, 10));
    $sCampo10      = trim(substr($aFile[$iCont], 292, 10));
    $sCampo11      = trim(substr($aFile[$iCont], 302, 10));
    $sCampo14      = trim(substr($aFile[$iCont], 324, 4));
    $sCampo15      = trim(substr($aFile[$iCont], 328, 2));

    /* Bloco para obter o tipo de financiamento */
    $sSql          = $oDaoSauFinanciamento->sql_query_file(null, 'sd65_i_codigo', null,
                                                           " sd65_c_financiamento = '".
                                                           trim(substr($aFile[$iCont], 312, 2))."'".
                                                           " and sd65_i_anocomp = $sCampo14 ".
                                                           " and sd65_i_mescomp = $sCampo15 "
                                                          );
                                                           
    $rsSauFinanc   = $oDaoSauFinanciamento->sql_record($sSql);
    if ($oDaoSauFinanciamento->erro_status == '0') {
      return false;
    }

    $iFinanciamento = db_utils::fieldsmemory($rsSauFinanc, 0)->sd65_i_codigo;
    

    /* Bloco para obter o tipo de rúbrica */
    if (trim(substr($aFile[$iCont], 314, 6)) == "") {
      $iRubrica = 'null';
    } else {

      $sSql         = $oDaoSauRubrica->sql_query_file(null, 'sd64_i_codigo', null, " sd64_c_rubrica = '".
                                                      trim(substr($aFile[$iCont], 314, 6))."'".
                                                      " and sd64_i_anocomp = $sCampo14 ".
                                                      " and sd64_i_mescomp = $sCampo15 "
                                                     );

      $rsSauRubrica = $oDaoSauRubrica->sql_record($sSql);

      if ($oDaoSauRubrica->erro_status == '0') {
        return false;
      }

      $iRubrica                                = db_utils::fieldsmemory($rsSauRubrica, 0)->sd64_i_codigo;

    }
    
    /* Verifico se o registro já foi incluído. */
    $sSql = $oDaoSauProcedimento->sql_query_file(null, 'sd63_i_codigo', '', "sd63_c_procedimento = '$sCampo1' ".
                                                 " and sd63_i_anocomp = $sCampo14 and sd63_i_mescomp = $sCampo15"
                                                );
    $oDaoSauProcedimento->sql_record($sSql);
    if ($oDaoSauProcedimento->numrows > 0) { // Se já foi incluído, vou para o próximo registro
      continue;
    }


    $oDaoSauProcedimento->sd63_c_procedimento  = $sCampo1;  
    $oDaoSauProcedimento->sd63_c_nome          = $sCampo2;  
    $oDaoSauProcedimento->sd63_i_complexidade  = $iComplexidade;  
    $oDaoSauProcedimento->sd63_c_sexo          = $sCampo3;  
    $oDaoSauProcedimento->sd63_i_execucaomax   = $sCampo4;  
    $oDaoSauProcedimento->sd63_i_maxdias       = $sCampo5;  
    $oDaoSauProcedimento->sd63_i_pontos        = $sCampo6;
    $oDaoSauProcedimento->sd63_i_idademin      = $sCampo7;
    $oDaoSauProcedimento->sd63_i_idademax      = $sCampo8;
    
    $fValor                                    = $sCampo9/100;  
    $oDaoSauProcedimento->sd63_f_sh            = '0';  
    $fValor                                    = $sCampo10/100;
    $oDaoSauProcedimento->sd63_f_sa            = number_format($fValor,2,'.','');  
    $fValor                                    = $sCampo11/100;
    $oDaoSauProcedimento->sd63_f_sp            = number_format($fValor,2,'.','');
      
    $oDaoSauProcedimento->sd63_i_financiamento = $iFinanciamento;  
    $oDaoSauProcedimento->sd63_i_rubrica       = $iRubrica;  
    $oDaoSauProcedimento->sd63_i_anocomp       = $sCampo14;  
    $oDaoSauProcedimento->sd63_i_mescomp       = $sCampo15;

    $oDaoSauProcedimento->incluir(null);
    if ($oDaoSauProcedimento->erro_status == '0') {
      $oDaoSauProcedimento->erro(true, false);
      return false;

    }

    $iContRegInseridos++; // Incremento o número de registros inseridos

  }

  return true;

}

//15 Importa Proccid
function funcProcCid($sOrigem, $sCompetencia) {
  
  global $iContRegInseridos;

  $oDaoSauProcedimento = db_utils::getdao('sau_procedimento');
  $oDaoSauCid          = db_utils::getdao('sau_cid');
  $oDaoSauProcCid      = db_utils::getdao('sau_proccid');

  $aFile               = file($sOrigem);
  $iLinhas             = count($aFile);

  for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

    $sCampo1   = trim(substr($aFile[$iCont], 14, 1)); //principal
    $sCampo2   = trim(substr($aFile[$iCont], 15, 4)); //ano comp
    $sCampo3   = trim(substr($aFile[$iCont], 19, 2)); //mes comp
   
    /* Bloco para obter o procedimento */
    $sSql      = $oDaoSauProcedimento->sql_query_file(null, 'sd63_i_codigo', null, "sd63_c_procedimento = '".
                                                      trim(substr($aFile[$iCont], 0, 10))."'".
                                                      " and sd63_i_anocomp = $sCampo2 ".
                                                      " and sd63_i_mescomp = $sCampo3 "
                                                     );

    $rsSauProc = $oDaoSauProcedimento->sql_record($sSql);
    if ($oDaoSauProcedimento->erro_status == '0') {
      return false;
    }

    $iProcedimento = db_utils::fieldsmemory($rsSauProc, 0)->sd63_i_codigo;

    /* Bloco para obter o CID */
    $sSql          = $oDaoSauCid->sql_query_file(null, 'sd70_i_codigo', null, " sd70_c_cid = '".
                                                 trim(substr($aFile[$iCont], 10, 4))."'"
                                                );

    $rsSauCid      = $oDaoSauCid->sql_record($sSql);
    if ($oDaoSauCid->erro_status == '0') {
      return false;
    }

    $iCid = db_utils::fieldsmemory($rsSauCid, 0)->sd70_i_codigo;

    /* Verifico se o registro já foi incluído. */
    $sSql = $oDaoSauProcCid->sql_query_file(null, 'sd72_i_codigo', '', "sd72_i_procedimento = $iProcedimento ".
                                            " and sd72_i_cid = $iCid and sd72_c_principal = '$sCampo1' ".
                                            " and sd72_i_anocomp = $sCampo2 and sd72_i_mescomp = $sCampo3"
                                           );
    $oDaoSauProcCid->sql_record($sSql);
    if ($oDaoSauProcCid->numrows > 0) { // Se já foi incluído, vou para o próximo registro
      continue;
    }

    $oDaoSauProcCid->sd72_i_procedimento = $iProcedimento;
    $oDaoSauProcCid->sd72_i_cid          = $iCid;
    $oDaoSauProcCid->sd72_c_principal    = $sCampo1;
    $oDaoSauProcCid->sd72_i_anocomp      = $sCampo2;
    $oDaoSauProcCid->sd72_i_mescomp      = $sCampo3;

    $oDaoSauProcCid->incluir(null);
    if ($oDaoSauProcCid->erro_status == '0') {

      $oDaoSauProcCid->erro(true, false);
      return false;

    }

    $iContRegInseridos++; // Incremento o número de registros inseridos

  }

  return true;
  
}

//16 Import Procdetalhe
function funcProcDetalhe($sOrigem, $sCompetencia) {

  global $iContRegInseridos;

  $oDaoSauProcedimento = db_utils::getdao('sau_procedimento');
  $oDaoSauDetalhe      = db_utils::getdao('sau_detalhe');
  $oDaoSauProcDetalhe  = db_utils::getdao('sau_procdetalhe');

  $aFile               = file($sOrigem );
  $iLinhas             = count($aFile);

  for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

    $sCampo2   = trim(substr($aFile[$iCont], 13, 4)); //ano comp
    $sCampo3   = trim(substr($aFile[$iCont], 17, 2)); //mes comp
 
    /* Bloco para obter o procedimento */
    $sSql      = $oDaoSauProcedimento->sql_query_file(null, 'sd63_i_codigo', null, "sd63_c_procedimento = '".
                                                      trim(substr($aFile[$iCont], 0, 10))."'".
                                                      " and sd63_i_anocomp = $sCampo2 ".
                                                      " and sd63_i_mescomp = $sCampo3 "
                                                     );

    $rsSauProc = $oDaoSauProcedimento->sql_record($sSql);
    if ($oDaoSauProcedimento->erro_status == '0') {
      return false;
    }

    $iProcedimento = db_utils::fieldsmemory($rsSauProc, 0)->sd63_i_codigo;

    /* Bloco para obter o detalhe */
    $sSql          = $oDaoSauDetalhe->sql_query_file(null, 'sd73_i_codigo', null,
                                                     " sd73_c_detalhe = '".trim(substr($aFile[$iCont], 10, 3))."'".
                                                     " and sd73_i_anocomp = $sCampo2 ".
                                                     " and sd73_i_mescomp = $sCampo3 "
                                                    );

    $rsSauDeta     = $oDaoSauDetalhe->sql_record($sSql);
    if ($oDaoSauDetalhe->erro_status == '0') {
      return false;
    }

    $iDetalhe                                = db_utils::fieldsmemory($rsSauDeta, 0)->sd73_i_codigo;

    /* Verifico se o registro já foi incluído. */
    $sSql = $oDaoSauProcDetalhe->sql_query_file(null, 'sd74_i_codigo', '', "sd74_i_procedimento = $iProcedimento ".
                                                " and sd74_i_detalhe = $iDetalhe and sd74_i_anocomp = $sCampo2 ".
                                                " and sd74_i_mescomp = $sCampo3"
                                               );
    $oDaoSauProcDetalhe->sql_record($sSql);
    if ($oDaoSauProcDetalhe->numrows > 0) { // Se já foi incluído, vou para o próximo registro
      continue;
    }
   
    $oDaoSauProcDetalhe->sd74_i_procedimento = $iProcedimento;
    $oDaoSauProcDetalhe->sd74_i_detalhe      = $iDetalhe;
    $oDaoSauProcDetalhe->sd74_i_anocomp      = $sCampo2;
    $oDaoSauProcDetalhe->sd74_i_mescomp      = $sCampo3;

    $oDaoSauProcDetalhe->incluir(null);
    if ($oDaoSauProcDetalhe->erro_status == '0') {

      $oDaoSauProcDetalhe->erro(true, false);
      return false;

    }

    $iContRegInseridos++; // Incremento o número de registros inseridos

  }

  return true;

}

//17 Importa Procincremento
function funcProcIncremento($sOrigem, $sCompetencia) {

  global $iContRegInseridos;

  $oDaoSauProcedimento   = db_utils::getdao('sau_procedimento');
  $oDaoSauHabilitacao    = db_utils::getdao('sau_habilitacao');
  $oDaoSauProcIncremento = db_utils::getdao('sau_procincremento');

  $aFile                 = file($sOrigem );
  $iLinhas               = count($aFile);

  for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

    $sCampo1   = trim(substr($aFile[$iCont], 14, 7));
    $sCampo2   = trim(substr($aFile[$iCont], 21, 7));
    $sCampo3   = trim(substr($aFile[$iCont], 28, 7));
    $sCampo4   = trim(substr($aFile[$iCont], 35, 4)); //ano comp
    $sCampo5   = trim(substr($aFile[$iCont], 39, 2)); //mes comp
 
    /* Bloco para obter o procedimento */
    $sSql      = $oDaoSauProcedimento->sql_query_file(null, 'sd63_i_codigo', null, "sd63_c_procedimento = '".
                                                      trim(substr($aFile[$iCont], 0, 10))."'".
                                                      " and sd63_i_anocomp = $sCampo4 ".
                                                      " and sd63_i_mescomp = $sCampo5 "
                                                     );

    $rsSauProc = $oDaoSauProcedimento->sql_record($sSql);
    if ($oDaoSauProcedimento->erro_status == '0') {
      return false;
    }

    $iProcedimento = db_utils::fieldsmemory($rsSauProc, 0)->sd63_i_codigo;

    /* Bloco para obter a habilitação */
    $sSql          = $oDaoSauHabilitacao->sql_query_file(null, 'sd75_i_codigo', null, " sd75_c_habilitacao  = '".
                                                         trim(substr($aFile[$iCont],10, 4))."'".
                                                         "  and sd75_i_anocomp = $sCampo4 ".
                                                         "  and sd75_i_mescomp = $sCampo5 "
                                                        );

    $rsSauHab      = $oDaoSauHabilitacao->sql_record($sSql);
    if ($oDaoSauHabilitacao->erro_status == '0') {
      return false;
    }

    $iHabilitacao = db_utils::fieldsmemory($rsSauHab, 0)->sd75_i_codigo;

    /* Verifico se o registro já foi incluído. */
    $sSql = $oDaoSauProcIncremento->sql_query_file(null, 'sd79_i_codigo', '', "sd79_i_procedimento = $iProcedimento ".
                                                   " and sd79_i_habilitacao = $iHabilitacao ".
                                                   " and sd79_i_anocomp = $sCampo4 and sd79_i_mescomp = $sCampo5"
                                                  );
    $oDaoSauProcIncremento->sql_record($sSql);
    if ($oDaoSauProcIncremento->numrows > 0) { // Se já foi incluído, vou para o próximo registro
      continue;
    }
    
    $oDaoSauProcIncremento->sd79_i_procedimento = $iProcedimento;
    $oDaoSauProcIncremento->sd79_i_habilitacao  = $iHabilitacao;
    $oDaoSauProcIncremento->sd79_f_sh           = $sCampo1;
    $oDaoSauProcIncremento->sd79_f_sa           = $sCampo2;
    $oDaoSauProcIncremento->sd79_f_sp           = $sCampo3;
    $oDaoSauProcIncremento->sd79_i_anocomp      = $sCampo4;
    $oDaoSauProcIncremento->sd79_i_mescomp      = $sCampo5;

    $oDaoSauProcIncremento->incluir(null);
    if ($oDaoSauProcIncremento->erro_status == '0') {

      $oDaoSauProcIncremento->erro(true, false);
      return false;

    }

    $iContRegInseridos++; // Incremento o número de registros inseridos

  }

  return true;

}

//18 Importa Procleito
function funcProcLeito($sOrigem, $sCompetencia) {

  global $iContRegInseridos;

  $oDaoSauProcedimento = db_utils::getdao('sau_procedimento');
  $oDaoSauTipoLeito    = db_utils::getdao('sau_tipoleito');
  $oDaoSauProcLeito    = db_utils::getdao('sau_procleito');

  $aFile               = file($sOrigem);
  $iLinhas             = count($aFile);

  for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

    $sCampo1   = trim(substr($aFile[$iCont], 12, 4)); //ano comp
    $sCampo2   = trim(substr($aFile[$iCont], 16, 2)); //mes comp


    /* Bloco para obter o procedimento */
    $sSql      = $oDaoSauProcedimento->sql_query_file(null, 'sd63_i_codigo', null, "sd63_c_procedimento = '".
                                                      trim(substr($aFile[$iCont], 0, 10))."'".
                                                      " and sd63_i_anocomp = $sCampo1 ".
                                                      " and sd63_i_mescomp = $sCampo2 "
                                                     );

    $rsSauProc = $oDaoSauProcedimento->sql_record($sSql);
    if ($oDaoSauProcedimento->erro_status == '0') {
      return false;
    }

    $iProcedimento = db_utils::fieldsmemory($rsSauProc, 0)->sd63_i_codigo;

    /* Bloco para obter o tipo de leito */
    $sSql          = $oDaoSauTipoLeito->sql_query_file(null, 'sd80_i_codigo', null, " sd80_c_leito = '".
                                                       trim(substr($aFile[$iCont], 10, 2))."'".
                                                        " and sd80_i_anocomp = $sCampo1 ".
                                                        " and sd80_i_mescomp = $sCampo2 "
                                                       );

    $rsSauLeito    = $oDaoSauTipoLeito->sql_record($sSql);
    if ($oDaoSauTipoLeito->erro_status == '0') {
      return false;
    }

    $iLeito = db_utils::fieldsmemory($rsSauLeito, 0)->sd80_i_codigo;

    /* Verifico se o registro já foi incluído. */
    $sSql = $oDaoSauProcLeito->sql_query_file(null, 'sd81_i_codigo', '', "sd81_i_procedimento = $iProcedimento ".
                                              " and sd81_i_leito = $iLeito ".
                                              " and sd81_i_anocomp = $sCampo1 and sd81_i_mescomp = $sCampo2"
                                             );
    $oDaoSauProcLeito->sql_record($sSql);
    if ($oDaoSauProcLeito->numrows > 0) { // Se já foi incluído, vou para o próximo registro
      continue;
    }

    $oDaoSauProcLeito->sd81_i_procedimento = $iProcedimento;
    $oDaoSauProcLeito->sd81_i_leito        = $iLeito;   
    $oDaoSauProcLeito->sd81_i_anocomp      = $sCampo1;   
    $oDaoSauProcLeito->sd81_i_mescomp      = $sCampo2;

    $oDaoSauProcLeito->incluir(null);
    if ($oDaoSauProcLeito->erro_status == '0') {

      $oDaoSauProcLeito->erro(true, false);
      return false;

    }

    $iContRegInseridos++; // Incremento o número de registros inseridos

  }

  return true;

}  

//19 Importa Procmodalidade
function funcProcModalidade($sOrigem, $sCompetencia) {

  global $iContRegInseridos;

  $oDaoSauProcedimento   = db_utils::getdao('sau_procedimento');
  $oDaoSauModalidade     = db_utils::getdao('sau_modalidade');
  $oDaoSauProcModalidade = db_utils::getdao('sau_procmodalidade');

  $aFile                 = file($sOrigem);
  $iLinhas               = count($aFile);

  for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

    $sCampo1   = trim(substr($aFile[$iCont], 12, 4)); //ano comp
    $sCampo2   = trim(substr($aFile[$iCont], 16, 2)); //mes comp

    /* Bloco para obter o procedimento */
    $sSql      = $oDaoSauProcedimento->sql_query_file(null, 'sd63_i_codigo', null, "sd63_c_procedimento = '".
                                                      trim(substr($aFile[$iCont], 0, 10))."'".
                                                      " and sd63_i_anocomp = $sCampo1 ".
                                                      " and sd63_i_mescomp = $sCampo2 "
                                                     );

    $rsSauProc = $oDaoSauProcedimento->sql_record($sSql);
    if ($oDaoSauProcedimento->erro_status == '0') {
      return false;
    }

    $iProcedimento = db_utils::fieldsmemory($rsSauProc, 0)->sd63_i_codigo;

    /* Bloco para obter a modalidade */
    $sSql          = $oDaoSauModalidade->sql_query_file(null, 'sd82_i_codigo', null, " sd82_c_modalidade = '".
                                                        trim(substr($aFile[$iCont], 10, 2))."'".
                                                        " and sd82_i_anocomp = $sCampo1 ".
                                                        " and sd82_i_mescomp = $sCampo2 "
                                                       );

    $rsSauMod      = $oDaoSauModalidade->sql_record($sSql);
    if ($oDaoSauModalidade->erro_status == '0') {
      return false;
    }

    $iModalidade                                = db_utils::fieldsmemory($rsSauMod, 0)->sd82_i_codigo;


    /* Verifico se o registro já foi incluído. */
    $sSql = $oDaoSauProcModalidade->sql_query_file(null, 'sd83_i_codigo', '', "sd83_i_procedimento = $iProcedimento".
                                                   " and sd83_i_modalidade = $iModalidade ".
                                                   " and sd83_i_anocomp = $sCampo1 and sd83_i_mescomp = $sCampo2"
                                                  );
    $oDaoSauProcModalidade->sql_record($sSql);
    if ($oDaoSauProcModalidade->numrows > 0) { // Se já foi incluído, vou para o próximo registro
      continue;
    }
 
    $oDaoSauProcModalidade->sd83_i_procedimento = $iProcedimento;
    $oDaoSauProcModalidade->sd83_i_modalidade   = $iModalidade;
    $oDaoSauProcModalidade->sd83_i_anocomp      = $sCampo1;
    $oDaoSauProcModalidade->sd83_i_mescomp      = $sCampo2;

    $oDaoSauProcModalidade->incluir(null);
    if ($oDaoSauProcModalidade->erro_status == '0') {

      $oDaoSauProcModalidade->erro(true, false);
      return false;

    }

    $iContRegInseridos++; // Incremento o número de registros inseridos

  }

  return true;

}

// 20 Importa Procorigem
function funcProcOrigem($sOrigem, $sCompetencia) {

  global $iContRegInseridos;

  $oDaoSauProcedimento = db_utils::getdao('sau_procedimento');
  $oDaoSauProcOrigem   = db_utils::getdao('sau_procorigem');

  $aFile               = file($sOrigem );
  $iLinhas             = count($aFile);

  for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

    $sCampo1   = trim(substr($aFile[$iCont], 20, 4));
    $sCampo2   = trim(substr($aFile[$iCont], 24, 2));

    /* Bloco para obter o procedimento */
    $sSql      = $oDaoSauProcedimento->sql_query_file(null, 'sd63_i_codigo', null, "sd63_c_procedimento = '".
                                                      trim(substr($aFile[$iCont], 0, 10))."'".
                                                      " and sd63_i_anocomp = $sCampo1 ".
                                                      " and sd63_i_mescomp = $sCampo2 "
                                                     );

    $rsSauProc = $oDaoSauProcedimento->sql_record($sSql);
    if ($oDaoSauProcedimento->erro_status == '0') {
      return false;
    }

    $iProcedimento = db_utils::fieldsmemory($rsSauProc, 0)->sd63_i_codigo;

    /* Bloco para obter a origem */
    $sSql          = $oDaoSauProcedimento->sql_query_file(null, 'sd63_i_codigo', null, "sd63_c_procedimento = '".
                                                          trim(substr($aFile[$iCont], 10, 10))."'".
                                                          " and sd63_i_anocomp = $sCampo1 ".
                                                          " and sd63_i_mescomp = $sCampo2 "
                                                         );

    $rsSauOrig     = $oDaoSauProcedimento->sql_record($sSql);
    if ($oDaoSauProcedimento->erro_status == '0') {
      return false;
    }

    $iOrigem                                = db_utils::fieldsmemory($rsSauOrig, 0)->sd63_i_codigo;

    /* Verifico se o registro já foi incluído. */
    $sSql = $oDaoSauProcOrigem->sql_query_file(null, 'sd95_i_codigo', '', "sd95_i_procedimento = $iProcedimento ".
                                               " and sd95_i_origem = $iOrigem ".
                                               " and sd95_i_anocomp = $sCampo1 and sd95_i_mescomp = $sCampo2"
                                              );
    $oDaoSauProcOrigem->sql_record($sSql);
    if ($oDaoSauProcOrigem->numrows > 0) { // Se já foi incluído, vou para o próximo registro
      continue;
    }
    
    $oDaoSauProcOrigem->sd95_i_procedimento = $iProcedimento;
    $oDaoSauProcOrigem->sd95_i_origem       = $iOrigem;
    $oDaoSauProcOrigem->sd95_i_anocomp      = $sCampo1;
    $oDaoSauProcOrigem->sd95_i_mescomp      = $sCampo2;

    $oDaoSauProcOrigem->incluir(null);
    if ($oDaoSauProcOrigem->erro_status == '0') {

      $oDaoSauProcOrigem->erro(true, false);
      return false;

    }

    $iContRegInseridos++; // Incremento o número de registros inseridos

  }

  return true;

}

//21 Importa Procregistro
function funcProcRegistro($sOrigem, $sCompetencia) {

  global $iContRegInseridos;

  $oDaoSauProcedimento = db_utils::getdao('sau_procedimento');
  $oDaoSauRegistro     = db_utils::getdao('sau_registro');
  $oDaoSauProcRegistro = db_utils::getdao('sau_procregistro');

  $aFile               = file($sOrigem );
  $iLinhas             = count($aFile);

  for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

    $sCampo1   = trim(substr($aFile[$iCont], 12, 4));
    $sCampo2   = trim(substr($aFile[$iCont], 16, 2));

    /* Bloco para obter o procedimento */
    $sSql      = $oDaoSauProcedimento->sql_query_file(null, 'sd63_i_codigo', null, "sd63_c_procedimento = '".
                                                      trim(substr($aFile[$iCont], 0, 10))."'".
                                                      " and sd63_i_anocomp = $sCampo1 ".
                                                      " and sd63_i_mescomp = $sCampo2 "
                                                     );

    $rsSauProc = $oDaoSauProcedimento->sql_record($sSql);
    if ($oDaoSauProcedimento->erro_status == '0') {
      return false;
    }

    $iProcedimento = db_utils::fieldsmemory($rsSauProc, 0)->sd63_i_codigo;

    /* Bloco para obter o registro */
    $sSql          = $oDaoSauRegistro->sql_query_file(null, 'sd84_i_codigo', null, " sd84_c_registro = '".
                                                      trim(substr($aFile[$iCont],10, 2))."'".
                                                      " and sd84_i_anocomp = $sCampo1 ".
                                                      " and sd84_i_mescomp = $sCampo2 "
                                                     );

    $rsSauReg      = $oDaoSauRegistro->sql_record($sSql);
    if ($oDaoSauRegistro->erro_status == '0') {
      return false;
    }

    $iRegistro                                = db_utils::fieldsmemory($rsSauReg, 0)->sd84_i_codigo;

    /* Verifico se o registro já foi incluído. */
    $sSql = $oDaoSauProcRegistro->sql_query_file(null, 'sd85_i_codigo', '', "sd85_i_procedimento = $iProcedimento ".
                                                 " and sd85_i_registro = $iRegistro ".
                                                 " and sd85_i_anocomp = $sCampo1 and sd85_i_mescomp = $sCampo2"
                                                );
    $oDaoSauProcRegistro->sql_record($sSql);  
    if ($oDaoSauProcRegistro->numrows > 0) { // Se já foi incluído, vou para o próximo registro
      continue;
    }

    $oDaoSauProcRegistro->sd85_i_procedimento = $iProcedimento;
    $oDaoSauProcRegistro->sd85_i_registro     = $iRegistro;
    $oDaoSauProcRegistro->sd85_i_anocomp      = $sCampo1;
    $oDaoSauProcRegistro->sd85_i_mescomp      = $sCampo2;

    $oDaoSauProcRegistro->incluir(null);
    if ($oDaoSauProcRegistro->erro_status == '0') {

      $oDaoSauProcRegistro->erro(true, false);
      return false;

    }

    $iContRegInseridos++; // Incremento o número de registros inseridos
          
  }

  return true;

}

//22 Importa servclassificacao
function funcServClassificacao($sOrigem, $sCompetencia) {

  global $iContRegInseridos;

  $oDaoSauServico           = db_utils::getdao('sau_servico');
  $oDaoSauServClassificacao = db_utils::getdao('sau_servclassificacao');

  $aFile                    = file($sOrigem);
  $iLinhas                  = count($aFile);

  for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

     $sCampo1 = trim(substr($aFile[$iCont], 3, 3));
     $sCampo2 = strtoupper(TiraAcento(trim(str_replace("'", '', substr($aFile[$iCont], 6, 150)))));
     $sCampo3 = trim(substr($aFile[$iCont], 156, 4));
     $sCampo4 = trim(substr($aFile[$iCont], 160, 2));
 
    /* Bloco para obter o servico */
    $sSql     = $oDaoSauServico->sql_query_file(null, 'sd86_i_codigo', null, "sd86_c_servico = '".
                                                trim(substr($aFile[$iCont], 0, 3))."'".
                                                " and   sd86_i_anocomp = $sCampo3 ".
                                                " and   sd86_i_mescomp = $sCampo4 "
                                               );

    $rsSauServ = $oDaoSauServico->sql_record($sSql);
    if ($oDaoSauServico->erro_status == '0') {
      return false;
    }

    $iServico                                       = db_utils::fieldsmemory($rsSauServ, 0)->sd86_i_codigo;

    /* Verifico se o registro já foi incluído. */
    $sSql = $oDaoSauServClassificacao->sql_query_file(null, 'sd87_i_codigo', '', "sd87_c_classificacao = '$sCampo1'".
                                                      " and sd87_i_servico = $iServico ".
                                                      " and sd87_i_anocomp = $sCampo3 and sd87_i_mescomp = $sCampo4"
                                                     );
    $oDaoSauServClassificacao->sql_record($sSql);  
    if ($oDaoSauServClassificacao->numrows > 0) { // Se já foi incluído, vou para o próximo registro
      continue;
    }

    $oDaoSauServClassificacao->sd87_c_classificacao = $sCampo1;
    $oDaoSauServClassificacao->sd87_c_nome          = $sCampo2;
    $oDaoSauServClassificacao->sd87_i_servico       = $iServico;
    $oDaoSauServClassificacao->sd87_i_anocomp       = $sCampo3;
    $oDaoSauServClassificacao->sd87_i_mescomp       = $sCampo4;

    $oDaoSauServClassificacao->incluir(null);
    if ($oDaoSauServClassificacao->erro_status == '0') {

      $oDaoSauServClassificacao->erro(true, false);
      return false;

    }

    $iContRegInseridos++; // Incremento o número de registros inseridos

  }

  return true;

}

//23 Importa procservico
function funcProcServico($sOrigem, $sCompetencia) {

  global $iContRegInseridos;

  $oDaoSauProcedimento      = db_utils::getdao('sau_procedimento');
  $oDaoSauServico           = db_utils::getdao('sau_servico');
  $oDaoSauServClassificacao = db_utils::getdao('sau_servclassificacao');
  $oDaoSauProcServico       = db_utils::getdao('sau_procservico');

  $aFile                    = file($sOrigem);
  $iLinhas                  = count($aFile);

  for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

    $sCampo1   = trim(substr($aFile[$iCont], 16, 4));
    $sCampo2   = trim(substr($aFile[$iCont], 20, 2));
     
    /* Bloco para obter o procedimento */
    $sSql      = $oDaoSauProcedimento->sql_query_file(null, 'sd63_i_codigo', null, "sd63_c_procedimento = '".
                                                      trim(substr($aFile[$iCont], 0, 10))."'".
                                                      " and sd63_i_anocomp = $sCampo1 ".
                                                      " and sd63_i_mescomp = $sCampo2 "
                                                     );

    $rsSauProc = $oDaoSauProcedimento->sql_record($sSql);
    if ($oDaoSauProcedimento->erro_status == '0') {
      return false;
    }

    $iProcedimento = db_utils::fieldsmemory($rsSauProc, 0)->sd63_i_codigo;

    /* Bloco para obter o servico */
    $sSql     = $oDaoSauServico->sql_query_file(null, 'sd86_i_codigo', null, "sd86_c_servico = '".
                                                trim(substr($aFile[$iCont], 10, 3))."'".
                                                " and   sd86_i_anocomp = $sCampo1 ".
                                                " and   sd86_i_mescomp = $sCampo2 "
                                               );

    $rsSauServ = $oDaoSauServico->sql_record($sSql);
    if ($oDaoSauServico->erro_status == '0') {
      return false;
    }

    $iServico      = db_utils::fieldsmemory($rsSauServ, 0)->sd86_i_codigo;

    /* Bloco para obter a classificacao do serviço  */
    $sSql          = $oDaoSauServClassificacao->sql_query_file(null, 'sd87_i_codigo', null, 
                                                               " sd87_c_classificacao = '".
                                                               trim(substr($aFile[$iCont], 13, 3))."'".
                                                               " and sd87_i_servico = $iServico ".
                                                               " and sd87_i_anocomp = $sCampo1 ".
                                                               " and sd87_i_mescomp = $sCampo2 "
                                                              );

    $rsSauServClass = $oDaoSauServClassificacao->sql_record($sSql);
    if ($oDaoSauServClassificacao->erro_status == '0') {
      return false;
    }

    $iClassificacao                        = db_utils::fieldsmemory($rsSauServClass, 0)->sd87_i_codigo;

    /* Verifico se o registro já foi incluído. */
    $sSql = $oDaoSauProcServico->sql_query_file(null, 'sd88_i_codigo', '', "sd88_i_procedimento = $iProcedimento".
                                                " and sd88_i_classificacao = $iClassificacao ".
                                                " and sd88_i_servico = $iServico ".
                                                " and sd88_i_anocomp = $sCampo1 and sd88_i_mescomp = $sCampo2"
                                               );
    $oDaoSauProcServico->sql_record($sSql);  
    if ($oDaoSauProcServico->numrows > 0) { // Se já foi incluído, vou para o próximo registro
      continue;
    }

    $oDaoSauProcServico->sd88_i_procedimento  = $iProcedimento;
    $oDaoSauProcServico->sd88_i_classificacao = $iClassificacao;
    $oDaoSauProcServico->sd88_i_servico       = $iServico;
    $oDaoSauProcServico->sd88_i_anocomp       = $sCampo1;
    $oDaoSauProcServico->sd88_i_mescomp       = $sCampo2;

    $oDaoSauProcServico->incluir(null);
    if ($oDaoSauProcServico->erro_status == '0') {

      $oDaoProcServico->erro(true, false);
      return false;

    }

    $iContRegInseridos++; // Incremento o número de registros inseridos

  }

  return true;

}

//24 Importa siasih_tipoproc
function funcSiasihTipoproc($sOrigem, $sCompetencia) {

  global $iContRegInseridos;

  $oDaoSauTipoProc = db_utils::getdao('sau_tipoproc');
  $oDaoSauSiaSih   = db_utils::getdao('sau_siasih');

  /* Bloco para incluir os tipos de procedimento, se ainda não foram incluídos  */
  $sSql            = $oDaoSauTipoProc->sql_query_file(null, 'sd93_i_codigo');

  $rsSauTipoProc   = $oDaoSauTipoProc->sql_record($sSql);
  if ($oDaoSauTipoProc->numrows == 0) {
    
    /* Execucao do nextval para atualizar a sequencia e nao dar erro no metodo incluir */
    $oDaoSauTipoProc->sql_record("select nextval('sau_tipoproc_sd93_i_codigo_seq')");
    $oDaoSauTipoProc->sd93_c_nome = 'AMBULATORIAL';
    $oDaoSauTipoProc->incluir(1);
    if ($oDaoSauTipoProc->erro_status == '0') {

      $oDaoSauTipoProc->erro(true, false);
      return false;

    }

    /* Execucao do nextval para atualizar a sequencia e nao dar erro no metodo incluir */
    $oDaoSauTipoProc->sql_record("select nextval('sau_tipoproc_sd93_i_codigo_seq')");
    $oDaoSauTipoProc->sd93_c_nome = 'HOSPITALAR';
    $oDaoSauTipoProc->incluir(2);
    if ($oDaoSauTipoProc->erro_status == '0') {

      $oDaoSauTipoProc->erro(true, false);
      return false;

    }

  }

  $aFile   = file($sOrigem );
  $iLinhas = count($aFile);

  for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

    $sCampo1 = trim(substr($aFile[$iCont], 0, 10));
    $sCampo2 = strtoupper(TiraAcento(trim(str_replace("'", '', substr($aFile[$iCont], 10, 100)))));

    if (trim(substr($aFile[$iCont], 110, 1)) == 'H') {
      $iTipoProc = 2;
    } else {
      $iTipoProc = 1;
    }

    $sCampo3 = trim(substr($aFile[$iCont], 111, 4));
    $sCampo4 = trim(substr($aFile[$iCont], 115, 2));

    /* Verifico se o registro já foi incluído. */
    $sSql = $oDaoSauSiaSih->sql_query_file(null, 'sd92_i_codigo', '', "sd92_c_siasih = '$sCampo1' ".
                                           " and sd92_i_tipoproc = $iTipoProc ".
                                           " and sd92_i_anocomp = $sCampo3 and sd92_i_mescomp = $sCampo4"
                                          );
    $oDaoSauSiaSih->sql_record($sSql);  
    if ($oDaoSauSiaSih->numrows > 0) { // Se já foi incluído, vou para o próximo registro
      continue;
    }
 
    $oDaoSauSiaSih->sd92_c_siasih   = $sCampo1;
    $oDaoSauSiaSih->sd92_c_nome     = $sCampo2;
    $oDaoSauSiaSih->sd92_i_tipoproc = $iTipoProc;
    $oDaoSauSiaSih->sd92_i_anocomp  = $sCampo3;
    $oDaoSauSiaSih->sd92_i_mescomp  = $sCampo4;

    $oDaoSauSiaSih->incluir(null);
    if ($oDaoSauSiaSih->erro_status == '0') {

      $oDaoSauSiaSih->erro(true, false);
      return false;

    }

    $iContRegInseridos++; // Incremento o número de registros inseridos

  }

  return true;

}

//25 Importa Procsisasih
function funcProcSiasih($sOrigem, $sCompetencia) {

  global $iContRegInseridos;

  $oDaoSauProcedimento = db_utils::getdao('sau_procedimento');
  $oDaoSauSiaSih       = db_utils::getdao('sau_siasih');
  $oDaoSauProcSiaSih   = db_utils::getdao('sau_procsiasih');

  $aFile               = file($sOrigem);
  $iLinhas             = count($aFile);

  for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

    $sCampo1 = trim(substr($aFile[$iCont], 21, 4));
    $sCampo2 = trim(substr($aFile[$iCont], 25, 2));


    /* Bloco para obter o procedimento */
    $sSql      = $oDaoSauProcedimento->sql_query_file(null, 'sd63_i_codigo', null, "sd63_c_procedimento = '".
                                                      trim(substr($aFile[$iCont], 0, 10))."'".
                                                      " and sd63_i_anocomp = $sCampo1 ".
                                                      " and sd63_i_mescomp = $sCampo2 "
                                                     );

    $rsSauProc = $oDaoSauProcedimento->sql_record($sSql);
    if ($oDaoSauProcedimento->erro_status == '0') {
      return false;
    }

    $iProcedimento = db_utils::fieldsmemory($rsSauProc, 0)->sd63_i_codigo;

    /* Bloco para obter o siasih */
    $sSql          = $oDaoSauSiaSih->sql_query_file(null, 'sd92_i_codigo', null, " sd92_c_siasih = '".
                                                    trim(substr($aFile[$iCont], 10, 10))."'".
                                                    " and sd92_i_anocomp = $sCampo1 ".
                                                    " and sd92_i_mescomp = $sCampo2 "
                                                   );

    $rsSauSiaSih   = $oDaoSauSiaSih->sql_record($sSql);
    if ($oDaoSauSiaSih->erro_status == '0') {
      return false;
    }

    $iSiaSih = db_utils::fieldsmemory($rsSauSiaSih, 0)->sd92_i_codigo;

    if (trim(substr($aFile[$iCont], 20, 1)) == 'H') {
      $iTipoProc = 2;
    } else {
      $iTipoProc = 1;
    }

    /* Verifico se o registro já foi incluído. */
    $sSql = $oDaoSauProcSiaSih->sql_query_file(null, 'sd94_i_codigo', '', "sd94_i_procedimento = $iProcedimento ".
                                               " and sd94_i_anocomp = $sCampo1 and sd94_i_mescomp = $sCampo2 ".
                                               " and sd94_i_siasih = $iSiaSih and sd94_i_tipoproc = $iTipoProc"
                                              );
    $oDaoSauProcSiaSih->sql_record($sSql);  
    if ($oDaoSauProcSiaSih->numrows > 0) { // Se já foi incluído, vou para o próximo registro
      continue;
    }

    $oDaoSauProcSiaSih->sd94_i_procedimento = $iProcedimento;
    $oDaoSauProcSiaSih->sd94_i_siasih       = $iSiaSih;
    $oDaoSauProcSiaSih->sd94_i_tipoproc     = $iTipoProc;
    $oDaoSauProcSiaSih->sd94_i_anocomp      = $sCampo1;
    $oDaoSauProcSiaSih->sd94_i_mescomp      = $sCampo2;

    $oDaoSauProcSiaSih->incluir(null);
    if ($oDaoSauProcSiaSih->erro_status == '0') {

      $oDaoSauProcSiaSih->erro(true, false);
      return false;

    }

    $iContRegInseridos++; // Incremento o número de registros inseridos

  }

  return true;

}

//26 Importa Proccbo
function funcProcCbo($sOrigem, $sCompetencia ) {
  
  global $iContRegInseridos;

  $oDaoSauProcedimento = db_utils::getdao('sau_procedimento');
  $oDaoRhcbo           = db_utils::getdao('rhcbo');
  $oDaoSauProcCbo      = db_utils::getdao('sau_proccbo');

  $aFile               = file(dirname($sOrigem).'/rl_procedimento_ocupacao.txt');
  $aFile2              = file(dirname($sOrigem).'/tb_ocupacao.txt');
  $iLinhas             = count($aFile);
  
  for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

    $sCampo1   = trim(substr($aFile[$iCont], 16, 4));
    $sCampo2   = trim(substr($aFile[$iCont], 20, 2));
 
    /* Bloco para obter o procedimento */
    $sSql      = $oDaoSauProcedimento->sql_query_file(null, 'sd63_i_codigo', null, "sd63_c_procedimento = '".
                                                      trim(substr($aFile[$iCont], 0, 10))."'".
                                                      " and sd63_i_anocomp = $sCampo1 ".
                                                      " and sd63_i_mescomp = $sCampo2 "
                                                     );

    $rsSauProc = $oDaoSauProcedimento->sql_record($sSql);
    if ($oDaoSauProcedimento->erro_status == '0') {
      return false;
    }

    $iProcedimento = db_utils::fieldsmemory($rsSauProc, 0)->sd63_i_codigo;

    /* Bloco para obter a especialidade */
    $sSql          = $oDaoRhcbo->sql_query_file(null, 'rh70_sequencial', 'rh70_sequencial', 
                                                " rh70_estrutural  = '".
                                                trim(substr($aFile[$iCont], 10, 6))."'".
                                                ' and rh70_tipo = 4'
                                               );

    $rsRhcbo       = $oDaoRhcbo->sql_record($sSql);
    if ($oDaoRhcbo->numrows == 0) {
      
      $iLinhas2 = count($aFile2);
      for ($iCont2 = 0; $iCont2 < $iLinhas2; $iCont2++) {

        if (trim(substr($aFile[$iCont], 10, 6)) == trim(substr($aFile2[$iCont2], 0, 6))) {

          $sEstrutural                = trim(substr($aFile2[$iCont2], 0, 6));
          $sNome                      = strtoupper(TiraAcento(trim(str_replace("'", '', substr($aFile2[$iCont2], 
                                                                                               6, 
                                                                                               156
                                                                                              )
                                                                              )
                                                                  )
                                                             )
                                                  );

          $oDaoRhcbo->rh70_estrutural = $sEstrutural;
          $oDaoRhcbo->rh70_descr      = $sNome;
          $oDaoRhcbo->rh70_tipo       = 4;

          $oDaoRhcbo->incluir(null);
          if ($oDaoRhcbo->erro_status == '0') {

            $oDaoRhcbo->erro(true, false);
            return false;

          }

          $iCbo = $oDaoRhcbo->rh70_sequencial;

        }

      } // fim for

    } else {
      $iCbo = db_utils::fieldsmemory($rsRhcbo, 0)->rh70_sequencial;
    }

    /* Verifico se o registro já foi incluído. */
    $sSql = $oDaoSauProcCbo->sql_query_file(null, 'sd96_i_codigo', '', "sd96_i_procedimento = $iProcedimento".
                                            " and sd96_i_cbo = $iCbo ".
                                            " and sd96_i_anocomp = $sCampo1 and sd96_i_mescomp = $sCampo2"
                                           );
    $oDaoSauProcCbo->sql_record($sSql);  
    if ($oDaoSauProcCbo->numrows > 0) { // Se já foi incluído, vou para o próximo registro
      continue;
    }

    $oDaoSauProcCbo->sd96_i_procedimento = $iProcedimento;
    $oDaoSauProcCbo->sd96_i_cbo          = $iCbo;
    $oDaoSauProcCbo->sd96_i_anocomp      = $sCampo1;
    $oDaoSauProcCbo->sd96_i_mescomp      = $sCampo2;

    $oDaoSauProcCbo->incluir(null);
    if ($oDaoSauProcCbo->erro_status == '0') {

      $oDaoSauProcCbo->erro(true, false);
      return false;

    }

    $iContRegInseridos++; // Incremento o número de registros inseridos

  }
    
  return true;
  
}

//27 Importa Proccompativel
function funcProcCompativel($sOrigem,$sCompetencia) {

  global $iContRegInseridos;

  $oDaoSauTipoCompatibilidade = db_utils::getdao('sau_tipocompatibilidade');
  $oDaoSauProcedimento        = db_utils::getdao('sau_procedimento');
  $oDaoSauRegistro            = db_utils::getdao('sau_registro');
  $oDaoSauProcCompativel      = db_utils::getdao('sau_proccompativel');

   /* Bloco para incluir os tipos de compatibilidade, se ainda não foram incluídos  */
  $sSql                       = $oDaoSauTipoCompatibilidade->sql_query_file(null, 'sd68_i_codigo');
  $oDaoSauTipoCompatibilidade->sql_record($sSql);
  if ($oDaoSauTipoCompatibilidade->numrows == 0) {
    
    /* Execucao do nextval para atualizar a sequencia e nao dar erro no metodo incluir */
    $oDaoSauTipoCompatibilidade->sql_record("select nextval('sau_tipocompatibilidade_sd68_i_codigo_seq')");
    $oDaoSauTipoCompatibilidade->sd68_c_nome = 'COMPATIVEL';
    $oDaoSauTipoCompatibilidade->incluir(1);
    if ($oDaoSauTipoCompatibilidade->erro_status == '0') {

      $oDaoSauTipoCompatibilidade->erro(true, false);
      return false;

    }

    /* Execucao do nextval para atualizar a sequencia e nao dar erro no metodo incluir */
    $oDaoSauTipoCompatibilidade->sql_record("select nextval('sau_tipocompatibilidade_sd68_i_codigo_seq')");
    $oDaoSauTipoCompatibilidade->sd68_c_nome = 'INCOMPATIVEL/EXCLUDENTE';
    $oDaoSauTipoCompatibilidade->incluir(2);
    if ($oDaoSauTipoCompatibilidade->erro_status == '0') {

      $oDaoSauTipoCompatibilidade->erro(true, false);
      return false;

    }

    /* Execucao do nextval para atualizar a sequencia e nao dar erro no metodo incluir */
    $oDaoSauTipoCompatibilidade->sql_record("select nextval('sau_tipocompatibilidade_sd68_i_codigo_seq')");
    $oDaoSauTipoCompatibilidade->sd68_c_nome = 'CONCOMITANTE';
    $oDaoSauTipoCompatibilidade->incluir(3);
    if ($oDaoSauTipoCompatibilidade->erro_status == '0') {

      $oDaoSauTipoCompatibilidade->erro(true, false);
      return false;

    }

  }
 
  $aFile   = file($sOrigem);
  $iLinhas = count($aFile);

  for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

    $sCampo1 = trim(substr($aFile[$iCont], 24, 1));
    $sCampo2 = trim(substr($aFile[$iCont], 25, 4));
    $sCampo3 = trim(substr($aFile[$iCont], 29, 4));
    $sCampo4 = trim(substr($aFile[$iCont], 33, 2));

    /* Bloco para obter o procedimento principal */
    $sSql      = $oDaoSauProcedimento->sql_query_file(null, 'sd63_i_codigo', null, "sd63_c_procedimento = '".
                                                      trim(substr($aFile[$iCont], 0, 10))."'".
                                                      " and sd63_i_anocomp = $sCampo3 ".
                                                      " and sd63_i_mescomp = $sCampo4 "
                                                     );

    $rsSauProc = $oDaoSauProcedimento->sql_record($sSql);
    if ($oDaoSauProcedimento->erro_status == '0') {
      return false;
    }

    $iProcPrincipal = db_utils::fieldsmemory($rsSauProc, 0)->sd63_i_codigo;


    /* Bloco para obter o procedimento compativel */
    $sSql           = $oDaoSauProcedimento->sql_query_file(null, 'sd63_i_codigo', null, "sd63_c_procedimento = '".
                                                           trim(substr($aFile[$iCont], 12, 10))."'".
                                                           " and sd63_i_anocomp = $sCampo3 ".
                                                           " and sd63_i_mescomp = $sCampo4 "
                                                          );

    $rsSauProc      = $oDaoSauProcedimento->sql_record($sSql);
    if ($oDaoSauProcedimento->erro_status == '0') {
      return false;
    }

    $iProcCompativel = db_utils::fieldsmemory($rsSauProc, 0)->sd63_i_codigo;

    /* Bloco para obter o registro principal */
    $sSql            = $oDaoSauRegistro->sql_query_file(null, 'sd84_i_codigo', null, " sd84_c_registro = '".
                                                        trim(substr($aFile[$iCont],10, 2))."'".
                                                        " and sd84_i_anocomp = $sCampo3 ".
                                                        " and sd84_i_mescomp = $sCampo4 "
                                                       );

    $rsSauReg        = $oDaoSauRegistro->sql_record($sSql);
    if ($oDaoSauRegistro->erro_status == '0') {
      return false;
    }

    $iRegPrincipal = db_utils::fieldsmemory($rsSauReg, 0)->sd84_i_codigo;

    /* Bloco para obter o registro compativel */
    $sSql          = $oDaoSauRegistro->sql_query_file(null, 'sd84_i_codigo', null, " sd84_c_registro = '".
                                                      trim(substr($aFile[$iCont], 22, 2))."'".
                                                      " and sd84_i_anocomp = $sCampo3 ".
                                                      " and sd84_i_mescomp = $sCampo4 "
                                                     );

    $rsSauReg      = $oDaoSauRegistro->sql_record($sSql);
    if ($oDaoSauRegistro->erro_status == '0') {
      return false;
    }

    $iRegCompativel                                = db_utils::fieldsmemory($rsSauReg, 0)->sd84_i_codigo;

    /* Verifico se o registro já foi incluído. */
    $sSql = $oDaoSauProcCompativel->sql_query_file(null, 'sd66_i_codigo', '', "sd66_i_procprincipal = $iProcPrincipal ".
                                                   " and sd66_i_regprincipal = $iRegPrincipal ".
                                                   " and sd66_i_proccompativel = $iProcCompativel ".
                                                   " and sd66_i_regcompativel = $iRegCompativel ".
                                                   " and sd66_i_anocomp = $sCampo3 and sd66_i_mescomp = $sCampo4"
                                                  );
    $oDaoSauProcCompativel->sql_record($sSql);  
    if ($oDaoSauProcCompativel->numrows > 0) { // Se já foi incluído, vou para o próximo registro
      continue;
    }

    $oDaoSauProcCompativel->sd66_i_procprincipal   = $iProcPrincipal;
    $oDaoSauProcCompativel->sd66_i_regprincipal    = $iRegPrincipal;
    $oDaoSauProcCompativel->sd66_i_proccompativel  = $iProcCompativel;
    $oDaoSauProcCompativel->sd66_i_regcompativel   = $iRegCompativel;
    $oDaoSauProcCompativel->sd66_i_compatibilidade = $sCampo1;
    $oDaoSauProcCompativel->sd66_i_qtd             = $sCampo2;
    $oDaoSauProcCompativel->sd66_i_anocomp         = $sCampo3;
    $oDaoSauProcCompativel->sd66_i_mescomp         = $sCampo4;

    $oDaoSauProcCompativel->incluir(null);
    if ($oDaoSauProcCompativel->erro_status == '0') {

      $oDaoSauProcCompativel->erro(true, false);
      return false;

    }

    $iContRegInseridos++; // Incremento o número de registros inseridos

  }

  return true;

}

//28 Importa Procrestricao
function funcProcRestricao($sOrigem, $sCompetencia) {

  global $iContRegInseridos;

  $oDaoSauProcedimento            = db_utils::getdao('sau_procedimento');
  $oDaoSauRegistro                = db_utils::getdao('sau_registro');
  $oDaoSauExecaoCompatibilidade   = db_utils::getdao('sau_execaocompatibilidade');

  $aFile                          = file($sOrigem);
  $iLinhas                        = count($aFile);

  for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

     $sCampo1  = trim(substr($aFile[$iCont], 34, 1));
     $sCampo2  = trim(substr($aFile[$iCont], 35, 4));
     $sCampo3  = trim(substr($aFile[$iCont], 39, 2));

    /* Bloco para obter o procedimento de restrição */
    $sSql      = $oDaoSauProcedimento->sql_query_file(null, 'sd63_i_codigo', null, "sd63_c_procedimento = '".
                                                      trim(substr($aFile[$iCont], 0, 10))."'".
                                                      " and sd63_i_anocomp = $sCampo2 ".
                                                      " and sd63_i_mescomp = $sCampo3 "
                                                     );

    $rsSauProc = $oDaoSauProcedimento->sql_record($sSql);
    if ($oDaoSauProcedimento->erro_status == '0') {
      return false;
    }

    $iProcRestricao = db_utils::fieldsmemory($rsSauProc, 0)->sd63_i_codigo;


    /* Bloco para obter o procedimento principal */
    $sSql           = $oDaoSauProcedimento->sql_query_file(null, 'sd63_i_codigo', null, "sd63_c_procedimento = '".
                                                           trim(substr($aFile[$iCont], 10, 10))."'".
                                                           " and sd63_i_anocomp = $sCampo2 ".
                                                           " and sd63_i_mescomp = $sCampo3 "
                                                          );

    $rsSauProc      = $oDaoSauProcedimento->sql_record($sSql);
    if ($oDaoSauProcedimento->erro_status == '0') {
      return false;
    }

    $iProcPrincipal = db_utils::fieldsmemory($rsSauProc, 0)->sd63_i_codigo;


    /* Bloco para obter o procedimento compativel */
    $sSql           = $oDaoSauProcedimento->sql_query_file(null, 'sd63_i_codigo', null, "sd63_c_procedimento = '".
                                                           trim(substr($aFile[$iCont], 22, 10))."'".
                                                           " and sd63_i_anocomp = $sCampo2 ".
                                                           " and sd63_i_mescomp = $sCampo3 "
                                                          );

    $rsSauProc      = $oDaoSauProcedimento->sql_record($sSql);
    if ($oDaoSauProcedimento->erro_status == '0') {
      return false;
    }

    $iProcCompativel = db_utils::fieldsmemory($rsSauProc, 0)->sd63_i_codigo;

 
    /* Bloco para obter o registro principal */
    $sSql            = $oDaoSauRegistro->sql_query_file(null, 'sd84_i_codigo', null, " sd84_c_registro = '".
                                                        trim(substr($aFile[$iCont], 20, 2))."'".
                                                        " and sd84_i_anocomp = $sCampo2 ".
                                                        " and sd84_i_mescomp = $sCampo3 "
                                                       );

    $rsSauReg        = $oDaoSauRegistro->sql_record($sSql);
    if ($oDaoSauRegistro->erro_status == '0') {
      return false;
    }

    $iRegPrincipal = db_utils::fieldsmemory($rsSauReg, 0)->sd84_i_codigo;


    /* Bloco para obter o registro compatível */
    $sSql          = $oDaoSauRegistro->sql_query_file(null, 'sd84_i_codigo', null, " sd84_c_registro = '".
                                                      trim(substr($aFile[$iCont], 32, 2))."'".
                                                      " and sd84_i_anocomp = $sCampo2 ".
                                                      " and sd84_i_mescomp = $sCampo3 "
                                                     );

    $rsSauReg      = $oDaoSauRegistro->sql_record($sSql);
    if ($oDaoSauRegistro->erro_status == '0') {
      return false;
    }

    $iRegCompativel                                       = db_utils::fieldsmemory($rsSauReg, 0)->sd84_i_codigo;

    /* Verifico se o registro já foi incluído. */
    $sSql = $oDaoSauExecaoCompatibilidade->sql_query_file(null, 'sd67_i_codigo', '', 
                                                          "sd67_i_procrestricao = $iProcRestricao ".
                                                          " and sd67_i_procprincipal = $iProcPrincipal ".
                                                          " and sd67_i_proccompativel = $iProcCompativel ".
                                                          " and sd67_i_anocomp = $sCampo2 and sd67_i_mescomp = $sCampo3"
                                                         );
    $oDaoSauExecaoCompatibilidade->sql_record($sSql);  
    if ($oDaoSauExecaoCompatibilidade->numrows > 0) { // Se já foi incluído, vou para o próximo registro
      continue;
    }

    $oDaoSauExecaoCompatibilidade->sd67_i_procrestricao   = $iProcRestricao;
    $oDaoSauExecaoCompatibilidade->sd67_i_procprincipal   = $iProcPrincipal;
    $oDaoSauExecaoCompatibilidade->sd67_i_regprincipal    = $iRegPrincipal;
    $oDaoSauExecaoCompatibilidade->sd67_i_proccompativel  = $iProcCompativel;
    $oDaoSauExecaoCompatibilidade->sd67_i_regcompativel   = $iRegCompativel;
    $oDaoSauExecaoCompatibilidade->sd67_i_compatibilidade = $sCampo1;
    $oDaoSauExecaoCompatibilidade->sd67_i_anocomp         = $sCampo2;
    $oDaoSauExecaoCompatibilidade->sd67_i_mescomp         = $sCampo3;

    $oDaoSauExecaoCompatibilidade->incluir(null);
    if ($oDaoSauExecaoCompatibilidade->erro_status == '0') {

      $oDaoSauExecaoCompatibilidade->erro(true, false);
      return false;

    }

    $iContRegInseridos++; // Incremento o número de registros inseridos

  }

 return true;

}

//29 Importa Prochabilitacao
function funcProcHabilitacao($sOrigem, $sCompetencia) {

  global $iContRegInseridos;

  $oDaoSauProcedimento     = db_utils::getdao('sau_procedimento');
  $oDaoSauHabilitacao      = db_utils::getdao('sau_habilitacao');
  $oDaoSauGrupoHabilitacao = db_utils::getdao('sau_grupohabilitacao');
  $oDaoSauProcHabilitacao  = db_utils::getdao('sau_prochabilitacao');
  
  // Insere na tabela sau_grupohabilitacao antes
  $aCaminho                = explode('/', $sOrigem);
  if (!funcGrupoHabilitacao($aCaminho[0].'/'.$aCaminho[1].'/tb_grupo_habilitacao.txt', $sCompetencia)) {
    return false;
  }
  
  $aFile   = file($sOrigem);
  $iLinhas = count($aFile);

  for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

    $sCampo1   = trim(substr($aFile[$iCont], 18, 4));
    $sCampo2   = trim(substr($aFile[$iCont], 22, 2));

    /* Bloco para obter o procedimento */
    $sSql      = $oDaoSauProcedimento->sql_query_file(null, 'sd63_i_codigo', null, "sd63_c_procedimento = '".
                                                      trim(substr($aFile[$iCont], 0, 10))."'".
                                                      " and sd63_i_anocomp = $sCampo1 ".
                                                      " and sd63_i_mescomp = $sCampo2 "
                                                     );

    $rsSauProc = $oDaoSauProcedimento->sql_record($sSql);
    if ($oDaoSauProcedimento->erro_status == '0') {
      $oDaoSauProcedimento->erro(true, false);
      return false;
    }

    $iProcedimento = db_utils::fieldsmemory($rsSauProc, 0)->sd63_i_codigo;

    /* Bloco para obter a habilitação */
    $sSql     = $oDaoSauHabilitacao->sql_query_file(null, 'sd75_i_codigo', null, " sd75_c_habilitacao = '".
                                                    trim(substr($aFile[$iCont], 10, 4))."'".
                                                    " and sd75_i_anocomp = $sCampo1".
                                                    " and sd75_i_mescomp = $sCampo2"
                                                   );

    $rsSauHab = $oDaoSauHabilitacao->sql_record($sSql);

    if ($oDaoSauHabilitacao->erro_status == '0') {
      return false;
    }

    $iHabilitacao  = db_utils::fieldsmemory($rsSauHab, 0)->sd75_i_codigo;


    /* Bloco para obter o grupo da habilitação */
    if (trim(substr($aFile[$iCont], 14, 4)) == '') {
      $iGrupo = 'null';
    } else {

      $sSql          = $oDaoSauGrupoHabilitacao->sql_query_file(null, 'sd76_i_codigo', null, " sd76_c_grupohabilitacao = '".
                                                                trim(substr($aFile[$iCont], 14, 4))."'".
                                                                " and sd76_i_habilitacao = ".$iHabilitacao
                                                               );
     
      $rsSauGrupoHab = $oDaoSauGrupoHabilitacao->sql_record($sSql);
      if ($oDaoSauGrupoHabilitacao->erro_status == '0') {
        return false;
      }
     
      $iGrupo = db_utils::fieldsmemory($rsSauGrupoHab, 0)->sd76_i_codigo;

    }

    if ($iGrupo == 'null') {
      $sSqlGrupoHabilitacao = " and sd77_i_grupohabilitacao is $iGrupo ";
    } else {
      $sSqlGrupoHabilitacao = " and sd77_i_grupohabilitacao = $iGrupo ";
    }
    /* Verifico se o registro já foi incluído. */
    $sSql = $oDaoSauProcHabilitacao->sql_query_file(null, 'sd77_i_codigo', '', "sd77_i_procedimento = $iProcedimento ".
                                                    " and sd77_i_habilitacao = $iHabilitacao ".
                                                    $sSqlGrupoHabilitacao.
                                                    " and sd77_i_anocomp = $sCampo1 and sd77_i_mescomp = $sCampo2"
                                                   );
    $oDaoSauProcHabilitacao->sql_record($sSql);
    if ($oDaoSauProcHabilitacao->numrows > 0) { // Se já foi incluído, vou para o próximo registro
      continue;
    }

    $oDaoSauProcHabilitacao->sd77_i_procedimento     = $iProcedimento;
    $oDaoSauProcHabilitacao->sd77_i_habilitacao      = $iHabilitacao;
    $oDaoSauProcHabilitacao->sd77_i_grupohabilitacao = $iGrupo;
    $oDaoSauProcHabilitacao->sd77_i_anocomp          = $sCampo1;
    $oDaoSauProcHabilitacao->sd77_i_mescomp          = $sCampo2;

    $oDaoSauProcHabilitacao->incluir(null);
    if ($oDaoSauProcHabilitacao->erro_status == '0') {

      $oDaoSauProcHabilitacao->erro(true, false);
      return false;

    }

    $iContRegInseridos++; // Incremento o número de registros inseridos

  }

  return true;

}
?>