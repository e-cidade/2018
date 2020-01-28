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

class LicitaConTipoDocumentoAcordo {

  public static $aTipos = array(
    1  => "Anula��o de of�cio",
    2  => "Anula��o por determina��o judicial",
    3  => "Apostilamento",
    4  => "Contrato",
    5  => "Ordem de in�cio",
    6  => "Outros documentos",
    7  => "Planilha Modelo de Aditivo",
    8  => "Retorno dos efeitos do contrato",
    9  => "S�mula do contrato",
    10 => "Suspens�o cautelar",
    11 => "Suspens�o de of�cio",
    12 => "Suspens�o por determina��o judicial",
    13 => "Termo aditivo",
    14 => "Termo de recebimento Definitivo",
    15 => "Termo de recebimento Provis�rio",
    16 => "Termo de rescis�o"
  );

  public static $aSiglas = array(
     1 => 'AOC',
     2 => 'ADC',
     3 => 'APO',
     4 => 'CTR',
     5 => 'ORD',
     6 => 'ODC',
     7 => 'PMA',
     8 => 'REC',
     9 => 'PUC',
    10 => 'SCC',
    11 => 'SCO',
    12 => 'SCD',
    13 => 'TAD',
    14 => 'TRD',
    15 => 'TRP',
    16 => 'RES',
  );

  public static function getSiglas() {
    return self::$aSiglas;
  }

  public static function getTipos() {
    return self::$aTipos;
  }
}