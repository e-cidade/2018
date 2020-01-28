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

class LicitaConTipoInstrumentoAcordo {

  public static $aTipos = array(
    1 => 'Termo de adesão',
    2 => 'Contrato',
    3 => 'Termo de fomento',
    4 => 'Termo de parceria',
    5 => 'Termo de credenciamento',
    6 => 'Termo de colaboração',
  );

  public static $aSiglas = array(
    1 => 'A',
    2 => 'C',
    3 => 'F',
    4 => 'P',
    5 => 'R',
    6 => 'T',
  );

  public static function getTipos() {
    return self::$aTipos;
  }

  public static function getSiglas() {
    return self::$aSiglas;
  }
}