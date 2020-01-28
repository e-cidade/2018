<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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

namespace ECidade\Tributario\NumeroControle\Calculo;

use ECidade\Tributario\NumeroControle\NumeroControle;

/**
 * Classe que representa o cálculo do módulo dez
 *
 * @author Roberto Carneiro <roberto@dbseller.com.br>
 */
class ModuloDez implements NumeroControle
{
  /**
   * Numeração que será usada no cálculo
   *
   * @var string
   */
  private $sNumeracao;

  /**
   * Numeração calculada a partir do módulo onze
   *
   * @var string
   */
  private $sNumeracaoCalculada;

  /**
   * Função responsável para calcular o número de controle
   */
  public function calcular()
  {
    if ( empty($this->sNumeracao) ) {
      throw new \BusinessException("Numeração para o cálculo do Módulo 10 não foi definida.");
    }

    $oNumeroControle     = new \NumeroControle();
    $iResultadoModuloDez = $oNumeroControle->calcularModuloDez($this->sNumeracao);

    if (empty($iResultadoModuloDez)) {
      $iResultadoModuloDez = 0;
    }

    $this->sNumeracaoCalculada = $iResultadoModuloDez;
  }

  /**
   * Função para alterar a numeração usada no cálculo
   *
   * @param string $sNumeracao
   */
  public function setNumeracao($sNumeracao)
  {
    $this->sNumeracao = $sNumeracao;
  }

  /**
   * Função para alterar a numeração usada no cálculo
   *
   * @return string
   */
  public function getNumeracao()
  {
    return $this->sNumeracao;
  }

  /**
   * Função para buscar a numeração calculada a partir do modulo onze
   *
   * @return string
   */
  public function getNumeracaoCalculada()
  {
    return $this->sNumeracaoCalculada;
  }
}