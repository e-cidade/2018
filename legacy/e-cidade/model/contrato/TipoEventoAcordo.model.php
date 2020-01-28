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

class TipoEventoAcordo {

  const EVENTO_DOCUMENTOS = 15;

  //#8016 - Apostila ser� ocultada provisoriamente, o sistema nao suporta esse tipo de evento no momento.
  private static $aTipos = array(
    1  => "Anula��o por determina��o judicial",
    2  => "Anula��o de of�cio",
   // 3  => "Apostila",
    4  => "Encerramento de Contrato",
    5  => "Ordem de in�cio",
    6  => "Publica��o",
    7  => "Retorno dos efeitos do contrato",
    8  => "Rescis�o",
    9  => "Suspens�o por cautelar",
    10 => "Suspens�o por determina��o judicial",
    11 => "Suspens�o de of�cio",
    12 => "Termo aditivo",
    13 => "Termo de recebimento definitivo",
    14 => "Termo de recebimento provis�rio",
    15 => "Documentos"
  );

  private static $aSiglas = array(
    1  => "ADC",
    2  => "AOC",
    3  => "APO",
    4  => "CON",
    5  => "ORD",
    6  => "PUC",
    7  => "REC",
    8  => "RES",
    9  => "SCC",
    10 => "SCD",
    11 => "SCO",
    12 => "TAD",
    13 => "TRD",
    14 => "TRP",
    15 => "NINF"
  );

  public static function getTipos() {
    return self::$aTipos;
  }

  public static function getSiglas() {
    return self::$aSiglas;
  }
}
