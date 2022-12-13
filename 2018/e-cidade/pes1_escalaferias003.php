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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

try {

  $db_opcao          = 3;
  $oRotulo           = new rotulocampo;
  $oRotulo->label('DBtxt23');
  $oRotulo->label('DBtxt25');
  
  $clcgm             = new cl_cgm();
  $clrhferiasperiodo = new cl_rhferiasperiodo();
  $clrhferias        = new cl_rhferias();

  $lPermiteEscolhaPeriodo = FeriasConfiguracao::isUltimoPeriodoAquisitivo();
  $clcgm->rotulo->label('z01_nome');
  $clrhferiasperiodo->rotulo->label();
  $clrhferias->rotulo->label();
  
  $oGet              = db_utils::postMemory($_GET);
  $db_botao          = false;
  $rh109_regist      = isset($oGet->rh109_regist) ? $oGet->rh109_regist : null;
  $z01_nome          = isset($oGet->z01_nome)     ? $oGet->z01_nome     : null;
  $oServidor         = new Servidor($rh109_regist);
  $z01_nome = $oServidor->getCgm()->getNome();
  $oPeriodoGozoFerias = PeriodoGozoFerias::getUltimoPeriodoGozo( $oServidor );
  $oPeriodoAquisivo   = PeriodoAquisitivoFerias::getDisponivel( $oServidor );
  if (!empty($oGet->codigo_ferias)) {

    $oPeriodoAquisivo = PeriodoAquisitivoFeriasRepository::getPeriodosPorCodigo($oGet->codigo_ferias);
    $oPeriodoGozoFerias = $oPeriodoAquisivo->getPeriodoDeFeriasPorCodigo($oGet->codigo_periodo);
  }

  $iDiasGozados                       = $oPeriodoAquisivo->getDiasGozados();
  $iDiasAbonados                      = $oPeriodoAquisivo->getDiasAbonados();
  $rh109_diasdireito                  = $oPeriodoAquisivo->getDiasDireito() - $iDiasGozados - $iDiasAbonados;

  $sTextoPeriodo         = $oPeriodoAquisivo->getDataInicial()->getDate(DBDate::DATA_PTBR)." - ";
  $sTextoPeriodo        .= $oPeriodoAquisivo->getDataFinal()->getDate(DBDate::DATA_PTBR)." Dias: {$rh109_diasdireito}";

  $aPeriodosAquisitivos = array($oPeriodoAquisivo->getCodigo() => $sTextoPeriodo);
  $oPeriodoAquisivo   = $oPeriodoGozoFerias->getPeriodoAquisitivo();
  $iCodigoPeriodoGozo = $oPeriodoGozoFerias->getCodigoPeriodo();
  
  $oDaoRhferiasperiodopontofe = new cl_rhferiasperiodopontofe;
  $sSqlRhferiasperiodopontofe = $oDaoRhferiasperiodopontofe->sql_query_file(null, "*", null, " rh112_rhferiasperiodo = {$iCodigoPeriodoGozo}");
  $rsRhferiasperiodopontofe   = db_query($sSqlRhferiasperiodopontofe);

  if(is_resource($rsRhferiasperiodopontofe) && pg_num_rows($rsRhferiasperiodopontofe) > 0) {
    throw new BusinessException("Não foi possível realizar a operação.\nA escala já foi processada no módulo pessoal.");
  }

  $oDaoRhferiasperiodoassentamento = new cl_rhferiasperiodoassentamento;
  $sSqlRhferiasperiodoassentamento = $oDaoRhferiasperiodoassentamento->sql_query_file(null, "*", null, " rh169_rhferiasperiodo = {$iCodigoPeriodoGozo}");
  $rsRhferiasperiodoassentamento   = db_query($sSqlRhferiasperiodoassentamento);

  if(is_resource($rsRhferiasperiodoassentamento) && pg_num_rows($rsRhferiasperiodoassentamento) > 0) {
    throw new BusinessException("Não foi possível realizar a operação.\nA escala possui assentamentos vinculados.");
  }

  $rh109_periodoaquisitivoinicial_dia = $oPeriodoAquisivo->getDataInicial()->getDia();  
  $rh109_periodoaquisitivoinicial_mes = $oPeriodoAquisivo->getDataInicial()->getMes();
  $rh109_periodoaquisitivoinicial_ano = $oPeriodoAquisivo->getDataInicial()->getAno();
  $rh109_periodoaquisitivofinal_dia   = $oPeriodoAquisivo->getDataFinal()->getDia();
  $rh109_periodoaquisitivofinal_mes   = $oPeriodoAquisivo->getDataFinal()->getMes();
  $rh109_periodoaquisitivofinal_ano   = $oPeriodoAquisivo->getDataFinal()->getAno();
  $rh109_diasdireito                  = $oPeriodoAquisivo->getDiasDireito();
  $rh109_faltasperiodoaquisitivo      = $oPeriodoAquisivo->getFaltasPeriodoAquisitivo();
  $rh110_sequencial                   = ($oPeriodoGozoFerias->getCodigoPeriodo()) ? $oPeriodoGozoFerias->getCodigoPeriodo() : null;
  $rh110_datainicial_dia              = $oPeriodoGozoFerias->getPeriodoInicial()->getDia();
  $rh110_datainicial_mes              = $oPeriodoGozoFerias->getPeriodoInicial()->getMes();
  $rh110_datainicial_ano              = $oPeriodoGozoFerias->getPeriodoInicial()->getAno();
  $rh110_datafinal_mes                = $oPeriodoGozoFerias->getPeriodoFinal()->getMes();
  $rh110_datafinal_ano                = $oPeriodoGozoFerias->getPeriodoFinal()->getAno();
  $rh110_datafinal_dia                = $oPeriodoGozoFerias->getPeriodoFinal()->getDia();
  $rh110_dias                         = $oPeriodoGozoFerias->getDiasGozo();
  $rh110_diasabono                    = $oPeriodoGozoFerias->getDiasAbono();
  $rh110_tipoponto_select_descr       = $oPeriodoGozoFerias->getTipoPonto() == '1' ? 'Salário' : 'Complementar';
  $rh110_pagaterco_select_descr       = ($oPeriodoGozoFerias->isPagaTerco()) ? 'Sim' : 'Não';
  $rh110_anopagamento                 = $oPeriodoGozoFerias->getAnoPagamento();
  $rh110_mespagamento                 = $oPeriodoGozoFerias->getMesPagamento();
  $rh110_observacoes                  = $oPeriodoGozoFerias->getObservacao();
  $lDireitoApuracaoMedia              = (!empty($rh110_periodoespecificoinicial_dia) ? 'E' : 'N');

  include(modification('forms/db_frmescalaferias.php'));
} catch ( Exception $eErro ) {

  db_msgbox($eErro->getMessage());
  db_redireciona("pes1_escalaferias001.php?db_opcao=$db_opcao");
}