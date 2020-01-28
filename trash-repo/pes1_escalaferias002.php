<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");

try {

  $db_opcao                           = 1;
  $oRotulo                            = new rotulocampo;
  $clcgm                              = new cl_cgm();
  $clrhferiasperiodo                  = new cl_rhferiasperiodo();
  $clrhferias                         = new cl_rhferias();
  
  $oRotulo->label('DBtxt23');
  $oRotulo->label('DBtxt25');
  $clcgm->rotulo->label('z01_nome');
  $clrhferiasperiodo->rotulo->label();
  $clrhferias->rotulo->label();
  
  $oGet                               = db_utils::postMemory($_GET);
  
  $rh109_regist                       = isset($oGet->rh109_regist) ? $oGet->rh109_regist : null;
  $z01_nome                           = isset($oGet->z01_nome)     ? $oGet->z01_nome     : null;
  $oServidor                          = new Servidor($rh109_regist);
  $oPeriodoAquisivo                   = PeriodoAquisitivoFerias::getDisponivel( $oServidor );
  
  $rh109_periodoaquisitivoinicial_dia = $oPeriodoAquisivo->getDataInicial()->getDia();
  $rh109_periodoaquisitivoinicial_mes = $oPeriodoAquisivo->getDataInicial()->getMes();
  $rh109_periodoaquisitivoinicial_ano = $oPeriodoAquisivo->getDataInicial()->getAno();
  $rh109_periodoaquisitivofinal_dia   = $oPeriodoAquisivo->getDataFinal()->getDia();
  $rh109_periodoaquisitivofinal_mes   = $oPeriodoAquisivo->getDataFinal()->getMes();
  $rh109_periodoaquisitivofinal_ano   = $oPeriodoAquisivo->getDataFinal()->getAno();
  $iDiasGozados                       = $oPeriodoAquisivo->getDiasGozados();
  $rh109_diasdireito                  = $oPeriodoAquisivo->getDiasDireito() - $iDiasGozados;
  $rh109_faltasperiodoaquisitivo      = $oPeriodoAquisivo->getFaltasPeriodoAquisitivo();
  
  $rh110_rhferias                     = $oPeriodoAquisivo->getCodigo();

  $rh110_datainicial_dia              = "";
  $rh110_datainicial_mes              = "";
  $rh110_datainicial_ano              = "";
  
  $rh110_datafinal_dia                = "";
  $rh110_datafinal_mes                = "";
  $rh110_datafinal_ano                = "";
  
  $rh110_periodoespecificoinicial_dia = "";
  $rh110_periodoespecificoinicial_mes = "";
  $rh110_periodoespecificoinicial_ano = "";
  $rh110_periodoespecificofinal_dia   = "";
  $rh110_periodoespecificofinal_mes   = "";
  $rh110_periodoespecificofinal_ano   = "";

  $rh110_dias                         = $rh109_diasdireito;
  $rh110_diasabono                    = 0;

  $rh110_anopagamento                 = DBPessoal::getAnoFolha();
  $rh110_mespagamento                 = DBPessoal::getMesFolha();

  $ano_folha                          = DBPessoal::getAnoFolha();
  $mes_folha                          = DBPessoal::getMesFolha();

  if ($iDiasGozados > 0 ) {
    
    $oUltimoPeriodoGozo = PeriodoGozoFerias::getUltimoPeriodoGozo( $oServidor );

    if ($oUltimoPeriodoGozo->getPeriodoEspecificoInicial() instanceof DBDate) {

      $lDireitoApuracaoMedia = 'E';
      $rh110_periodoespecificoinicial_dia = $oUltimoPeriodoGozo->getPeriodoEspecificoInicial()->getDia();
      $rh110_periodoespecificoinicial_mes = $oUltimoPeriodoGozo->getPeriodoEspecificoInicial()->getMes();
      $rh110_periodoespecificoinicial_ano = $oUltimoPeriodoGozo->getPeriodoEspecificoInicial()->getAno();
      $rh110_periodoespecificofinal_dia   = $oUltimoPeriodoGozo->getPeriodoEspecificoFinal()->getDia();
      $rh110_periodoespecificofinal_mes   = $oUltimoPeriodoGozo->getPeriodoEspecificoFinal()->getMes();
      $rh110_periodoespecificofinal_ano   = $oUltimoPeriodoGozo->getPeriodoEspecificoFinal()->getAno();
    }
  }

  /**
  * Pega valores default ter�o de f�rias e tipo de ponto
  */
  $oDaoCfpess      = new cl_cfpess();
  $sSql            = $oDaoCfpess->sql_query( $rh110_anopagamento, $rh110_mespagamento, db_getsession('DB_instit'));
  $rsCfpess        = db_query($sSql);

  $oDadosCfpess    = db_utils::fieldsMemory($rsCfpess, 0);
  $rh110_pagaterco = $oDadosCfpess->r11_13ferias    == 't' ? 'true' : 'false';
  $rh110_tipoponto = $oDadosCfpess->r11_pagarferias == 'C' ? '2' : '1';

  include('forms/db_frmescalaferias.php');
  
} catch ( Exception $eErro ) {
  
  db_msgbox($eErro->getMessage());
  db_redireciona('pes1_escalaferias001.php?db_opcao=1');
}