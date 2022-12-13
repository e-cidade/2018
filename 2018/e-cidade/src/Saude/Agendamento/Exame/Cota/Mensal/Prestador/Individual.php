<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2017  DBSeller Servicos de Informatica
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

namespace ECidade\Saude\Agendamento\Exame\Cota\Mensal\Prestador;

use ECidade\Saude\Agendamento\Exame\Cota\Mensal;

class Individual extends Mensal
{
  const TIPO_COTA = 1;

  /**
   * Código que identifica o prestador e o exame pertencentes da cota
   */
  protected $iPrestadorExame;

  /**
   * Busca Código que identifica o prestador e o exame pertencentes da cota.
   *
   * @return integer
   */
  public function getPrestadorExame()
  {
    return $this->iPrestadorExame;
  }

  /**
   * Busca Código que identifica o prestador e o exame pertencentes da cota.
   *
   * @return integer
   */
  public function getPrestadorExameArray()
  {
    return array($this->iPrestadorExame);
  }

  /**
   * Altera Código que identifica o prestador e o exame pertencentes da cota.
   *
   * @param mixed $iPrestadorExame
   *
   * @return self
   */
  public function setPrestadorExame($prestadorExame)
  {
    $this->iPrestadorExame = $prestadorExame;

    if ( is_array($prestadorExame) ) {

      if (count($prestadorExame) > 1) {
        throw new ParameterException("Cota Individual não pode ter mais de um exame.");
      }

      $this->iPrestadorExame = $prestadorExame[0];
    }

    return $this;
  }
}
