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

class LicitaConTipoPublicacao {

  public static $aTipos = array(
    EventoLicitacao::PUBLICACAO_DIARIO_ESTADO       => EventoLicitacao::SIGLA_PUBLICACAO_DIARIO_ESTADO,
    EventoLicitacao::PUBLICACAO_INTERNET            => EventoLicitacao::SIGLA_PUBLICACAO_INTERNET,
    EventoLicitacao::PUBLICACAO_JORNAL              => EventoLicitacao::SIGLA_PUBLICACAO_JORNAL,
    EventoLicitacao::PUBLICACAO_MURAL_ENTIDADE      => EventoLicitacao::SIGLA_PUBLICACAO_MURAL_ENTIDADE,
    EventoLicitacao::PUBLICACAO_DIARIO_MUNICIPIO    => EventoLicitacao::SIGLA_PUBLICACAO_DIARIO_MUNICIPIO,
    EventoLicitacao::PUBLICACAO_DIARIO_MUNICIPIO_RS => EventoLicitacao::SIGLA_PUBLICACAO_DIARIO_MUNICIPIO_RS,
    EventoLicitacao::PUBLICACAO_DIARIO_UNIAO        => EventoLicitacao::SIGLA_PUBLICACAO_DIARIO_UNIAO,
    EventoLicitacao::PUBLICACAO_NAO_PUBLICADO       => EventoLicitacao::SIGLA_PUBLICACAO_NAO_PUBLICADO,
  );

  /**
   * Retorna a sigla do tipo de publica��o equivalente ao c�digo n�merico
   *
   * @param  integer $iTipo
   * @return string
   */
  public static function getSigla($iTipo) {
    return isset(self::$aTipos[$iTipo]) ? self::$aTipos[$iTipo] : null;
  }

}