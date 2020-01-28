<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
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
 * Classe responsável por executar o cáculo dos módulos onze e doze, que compôem o NC,
 * na camada que do banco de dados
 *
 * @author Roberto Carneiro <roberto@dbseller.com.br>
 */
class NumeroControle
{
  /**
   * Função que executa o cálculo do módulo onze no banco
   *
   * @param  integer $iNumeracao
   * @return integer
   */
  public function calcularModuloOnze($iNumeracao, $iDigitoVerificador, $iPeso)
  {
    $sSqlModuloOnze = " select fc_modulo11('{$iNumeracao}', {$iDigitoVerificador}, {$iPeso}) as moduloonze ";
    $rsModuloOnze   = db_query($sSqlModuloOnze);

    if ( !$rsModuloOnze ) {
      throw new DBException("Erro ao calcular o Módulo Onze");
    }

    $aModuloOnze = db_utils::getCollectionByRecord($rsModuloOnze);

    return $aModuloOnze[0]->moduloonze;
  }

    /**
   * Função que executa o cálculo do módulo dez no banco
   *
   * @param  integer $iNumeracao
   * @return integer
   */
  public function calcularModuloDez($iNumeracao)
  {
    $sSqlModuloDez = " select fc_modulo10('{$iNumeracao}') as modulodez ";
    $rsModuloDez   = db_query($sSqlModuloDez);


    if ( !$rsModuloDez ) {
      throw new DBException("Erro ao calcular o Módulo Dez");
    }

    $aModuloDez = db_utils::getCollectionByRecord($rsModuloDez);

    return $aModuloDez[0]->modulodez;
  }
}