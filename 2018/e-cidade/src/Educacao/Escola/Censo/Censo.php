<?php
namespace ECidade\Educacao\Escola\Censo;
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

class Censo {

  /**
   * Data base do censo
   * @var DBDate
   */
  private $oDataBase = null;

  public function __construct($iAno = null){

    if ( is_null($iAno) ) {
      $iAno = date('Y');
    }

    $this->calcularDataCenso($iAno);
  }

  private function calcularDataCenso($iAno){

    if ( is_null($this->oDataBase) ) {

      for ($dia = 31; $dia > 0; $dia-- ) {

        if ( date ( "w", mktime(0, 0, 0, 5, $dia, $iAno) ) == 3 ) {

          $iDia = str_pad($dia, 2, '0', STR_PAD_LEFT);
          $this->oDataBase = new \DBDate("{$iDia}/05/{$iAno}");
          break;
        }
      }
    }
  }

  /**
   * Retorna a data base do censo para o ano
   * Data base do censo é a última quarta feira do mês de maio
   * @return DBDate
   */
  public function getDataCenso () {
    return $this->oDataBase;
  }

  public function getAno() {
    return $this->oDataBase->getAno();
  }

}