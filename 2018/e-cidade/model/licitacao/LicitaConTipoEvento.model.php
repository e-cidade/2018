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
 * Class LicitaConTipoEvento
 */
class LicitaConTipoEvento {

  const EVENTO_NAO_INFORMADO = 23;

  /**
   * Descri��o dos eventos dispon�veis para o LicitaCon
   * @type array
   */
  public static $aDescricaoEvento = array(
     1 => 'ALTERA��O DO EDITAL',
     2 => 'ANULA��O POR DETERMINA��O JUDICIAL',
     3 => 'ANULA��O DE OF�CIO',
     4 => 'ENCERRAMENTO POR FALTA DE PROPOSTAS CLASSIFICADAS',
     5 => 'ENCERRAMENTO POR FALTA DE LICITANTES HABILITADOS',
     6 => 'ENCERRAMENTO POR FALTA DE INTERESSADOS',
     7 => 'ENCERRAMENTO',
     8 => 'ESCLARECIMENTO',
     9 => 'IMPUGNA��O DO EDITAL',
    10 => 'PUBLICA��O',
    11 => 'PUBLICA��O DO EDITAL',
    12 => 'RECURSO DE CREDENCIAMENTO/LANCES',
    13 => 'REPUBLICA��O DO EDITAL',
    14 => 'REIN�CIO',
    15 => 'REVOGA��O DE OF�CIO',
    16 => 'RECURSO DA HABILITA��O',
    17 => 'RECURSO DE HABILITA��O/PROPOSTA',
    18 => 'RECURSO DA PROPOSTA/PROJETO',
    19 => 'RECURSO DA PROPOSTA',
    20 => 'SUSPENS�O POR DETERMINA��O JUDICIAL',
    21 => 'SUSPENS�O POR MEDIDA CAUTELAR',
    22 => 'SUSPENS�O DE OF�CIO',
    23 => 'DOCUMENTOS'
  );

  /**
   * Siglas dos eventos esperados pelo LicitaCon
   * @type array
   */
  public static $aSiglaEvento = array(
    1 => 'AED',
    2 => 'AND',
    3 => 'ANO',
    4 => 'EFC',
    5 => 'EFH',
    6 => 'EFI',
    7 => 'ENC',
    8 => 'ESC',
    9 => 'IME',
    10 => 'PUB',
    11 => 'PUE',
    12 => 'RCL',
    13 => 'REE',
    14 => 'REI',
    15 => 'REO',
    16 => 'RHA',
    17 => 'RHP',
    18 => 'RPP',
    19 => 'RPR',
    20 => 'SDJ',
    21 => 'SUM',
    22 => 'SUO',
    23 => 'NINF',
  );
}
