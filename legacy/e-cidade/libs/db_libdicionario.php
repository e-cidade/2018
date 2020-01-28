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
 * Função para retornar os valores defaults de um campo
 * @param string $sNomeCam nome do campo
 * @return array
 */

function getValoresPadroesCampo($sNomeCam) {

  $aRetorno     = array();
  $sSqlDefault  = "select defcampo, ";
  $sSqlDefault .= "       defdescr  ";
  $sSqlDefault .= "  from db_syscampodef ";
  $sSqlDefault .= "       inner join db_syscampo on db_syscampo.codcam = db_syscampodef.codcam ";
  $sSqlDefault .= " where nomecam = '{$sNomeCam}'";
  $sSqlDefault .= " order by defcampo";
  $rsCampos     = pg_query($sSqlDefault);
  if (pg_num_rows($rsCampos) > 0) {
    
    $iTotRows = pg_num_rows($rsCampos);
    for ($i = 0; $i < $iTotRows; $i++) {

       $aRetorno[pg_result($rsCampos,$i,0)] = pg_result($rsCampos,$i,1); 
    }
  }
  return $aRetorno;
}