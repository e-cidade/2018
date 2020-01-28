<?php

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

$oDaoAvaliacaoEstruturaFrequencia = new cl_avaliacaoestruturafrequencia();

if ($sqlerro == false) {

  db_atutermometro(0, 2, 'termometroitem', 1, $sMensagemTermometroItem);

  try {
    
    $sWhere = "ed328_ano = {$iAnoOrigem}";
    $sSqlAvaliacoesEstruturaFrequencia = $oDaoAvaliacaoEstruturaFrequencia->sql_query_file(null, "*", null, $sWhere);
    $rsDadosAvaliacoesEstruturaFrequencia = db_query($sSqlAvaliacoesEstruturaFrequencia);
    
    if( !$rsDadosAvaliacoesEstruturaFrequencia ) {
      throw new DBException('Falha ao buscar os dados referentes a estrutura de arredondamento das frequências.');
    }
    
    $iTotalAvaliacoesEstruturaFrequencia = pg_num_rows($rsDadosAvaliacoesEstruturaFrequencia);
    
    for ($iContador = 0; $iContador < $iTotalAvaliacoesEstruturaFrequencia; $iContador++) {

      $oDados = db_utils::fieldsMemory($rsDadosAvaliacoesEstruturaFrequencia, $iContador);

      // Valida se já existe configuração para o ano migrado
      $sWhereValida  = " ed328_ano = {$iAnoDestino}";
      $sWhereValida .= " and ed328_escola = {$oDados->ed328_escola}";
      $sSqlValida    = $oDaoAvaliacaoEstruturaFrequencia->sql_query_file(null, "1", null, $sWhereValida);
      $rsValida      = db_query($sSqlValida);
      
      if( !$rsValida ) {
        throw new DBException("Falha ao verificar se já existe configuração de arredondamento das frequências para o ano migrado ({$iAnoDestino}).");
      }
      
      if (pg_num_rows($rsValida) > 0) {
        continue;
      }
      
      $oDaoAvaliacaoEstruturaFrequencia->ed328_escola              = $oDados->ed328_escola;
      $oDaoAvaliacaoEstruturaFrequencia->ed328_db_estrutura        = $oDados->ed328_db_estrutura;
      $oDaoAvaliacaoEstruturaFrequencia->ed328_ativo               = $oDados->ed328_ativo == 't' ? 'true' : 'false';
      $oDaoAvaliacaoEstruturaFrequencia->ed328_arredondafrequencia = $oDados->ed328_arredondafrequencia == 't' ? 'true' : 'false';
      $oDaoAvaliacaoEstruturaFrequencia->ed328_observacao          = 'Configuração migrada';
      $oDaoAvaliacaoEstruturaFrequencia->ed328_ano                 = $iAnoDestino;
      $oDaoAvaliacaoEstruturaFrequencia->incluir(null);
      
      if( $oDaoAvaliacaoEstruturaFrequencia->erro_status == 0 ) {
        throw new DBException("Falha ao migrar as configuração de arredondamento das frequências do ano {$iAnoOrigem} para o ano {$iAnoDestino}.");
      }      
    }
    
  } catch (Exception $oErro) {
    
    $sqlerro  = true;
    $erro_msg = $oErro->getMessage();
  }
  
  db_atutermometro(1, 2, 'termometroitem', 1, $sMensagemTermometroItem);
}
