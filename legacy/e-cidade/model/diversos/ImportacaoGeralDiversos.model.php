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
 * ImportacaoGeralDiversos
 *
 * @author Rafael Nery  <rafael.nery@dbseller.com.br>
 * @author Tales Baz    <tales.baz@dbseller.com.br>
 * @uses ImportacaoDiversos
 * @package Diversos
 * @final
 */
class ImportacaoGeralDiversos extends ImportacaoDiversos {


  /**
   * Vencimentos guarda os vencimentos de cada receita
   *
   * @var array
   * @access protected
   */
  protected $aReceitaVencimento  = array();

  /**
   * Vencimentos guarda os vencimentos de cada receita
   *
   * @var ProcedenciaDiversos[]
   * @access protected
   */
  protected $aReceitaProcedencia  = array();

  /**
   * Adiciona receita a ser processada
   *
   * @param integer $iReceita
   * @param DBDate $oDataVencimento
   * @param ProcedenciaDiversos $oProcedenciaDiversos
   * @access public
   * @return void
   */
  public function adicionarReceita ($iReceita, DBDate $oDataVencimento = null, ProcedenciaDiversos $oProcedenciaDiversos) {

    $this->aReceitaProcedencia[$iReceita] = $oProcedenciaDiversos;
    $this->aReceitaVencimento[$iReceita] = $oDataVencimento;
    return;
  }

  /**
   * Define observação a importação a ser efetuada
   *
   * @param string $sObservacoes
   * @access public
   * @return void
   */
  public function setObservacoes($sObservacoes) {
    $this->sObservacoes = $sObservacoes;
    return;
  }

  /**
   * Executa o processamento da importação
   * @return bool
   * @throws DBException
   */
  public function processar ($sObservacoes = '') {

    $iCodDiverImporta = $this->salvarDiverImporta(ImportacaoDiversos::PROCESSAMENTO_GERAL);
    $oJson = new Services_JSON();

    if (!db_utils::inTransaction()) {

      throw new DBException(
        _M('tributario.diversos.ImportacaoGeralDiversos.sem_transacao_ativa')
      );
    }

    foreach ($this->aReceitaVencimento as $iReceita => $oDataVencimento) {

      $oProcedencia = $this->aReceitaProcedencia[$iReceita];
      $oDaoArrematric = new cl_arrematric();

      $sCampos  = " arrecad.k00_numpre,                                                 \n";
      $sCampos .= " k00_receit,                                                         \n";
      $sCampos .= " array_to_string(array_accum(distinct k00_numpar), ',') as parcelas, \n";
      $sCampos .= " array_accum(distinct k00_matric || ':' || k00_perc) as matriculas   \n";

      $sWhere = " k00_receit = {$iReceita} \n";

      /**
       * APENAS IPTU - Modificar aqui se for necessario outro tipo
       */
      $sWhere .= " and cadtipo.k03_tipo = 1                                                                                          \n";
      $sWhere .= " and not exists (select 1 from arrepaga where k00_numpre = arrecad.k00_numpre and k00_receit = arrecad.k00_receit) \n";
      $sWhere .= " and not exists (select 1 from arrecant where k00_numpre = arrecad.k00_numpre and k00_receit = arrecad.k00_receit) \n";
      $sWhere .= " and not exists (select 1 from arreold where k00_numpre = arrecad.k00_numpre and k00_receit = arrecad.k00_receit)  \n";
      $sWhere .= " and not exists (select 1 from arresusp where k00_numpre = arrecad.k00_numpre and k00_receit = arrecad.k00_receit) \n";

      $sSqlDebitos    = $oDaoArrematric->sql_query_info( null, null, $sCampos,"arrecad.k00_numpre, k00_receit",
                                                        $sWhere . " group by arrecad.k00_numpre, k00_receit ");
      $rsDebitos = db_query($sSqlDebitos);

      if (!$rsDebitos) {

        throw new DBException(
          _M('tributario.diversos.ImportacaoGeralDiversos.falha_buscar_debitos', (object)array("sErro" => pg_last_error() ) )
        );
      }

      /**
       * Enquanto houver registro de Débitos encontrados
       * Lança os registros de Diversos
       */
      while ($oDadosDebitos = pg_fetch_object($rsDebitos)) {

        $sMatriculas = $oDadosDebitos->matriculas;
        $oMatriculas = $oJson->decode($sMatriculas);
        $aParcelas = explode(",", $oDadosDebitos->parcelas);
        /**
         * Como o Numpar não é informado,
         * Pegamos todas as parcelas onde estã as receitas do débito
         */
        foreach ($aParcelas as $iParcela) {

          $oDiverso = $this->salvarDiversos($oDadosDebitos->k00_numpre,
                                            $iParcela,
                                            $iReceita,
                                            $oProcedencia->getProcedenciaDiverso(),
                                            $iCodDiverImporta);

          $this->adicionarDiverImportaOld($oDiverso->iCodDiverso,
                                          $oDadosDebitos->k00_numpre,
                                          $iParcela,
                                          $iReceita);


          $aDados[0][0] = (object)array("k00_numpre" => $oDadosDebitos->k00_numpre,
                                        "k00_numpar" => $iParcela,
                                        "k00_receit" => $iReceita);
          $this->processaArrecad($aDados,
                                 $this->aReceitaProcedencia[$iReceita],
                                 null,
                                 $oDiverso->iNumpreGerado,
                                 $oDataVencimento);

          foreach ((array)$oMatriculas as $iMatricula => $nPercentual) {
            $oDados = (object)array("k00_numpre" => $oDiverso->iNumpreGerado,
                                    "k00_matric" => $iMatricula,
                                    "k00_perc"   => $nPercentual);
            $this->aDataManager['arrematric']->setByLineOfDBUtils($oDados, true);
          }
        }
      }//FIM DO WHILE
    }//FIM DO FOREACH

    $this->aDataManager['arrecad']->persist();
    $this->aDataManager['arrematric']->persist();
    $this->aDataManager['arreinscr']->persist();
    return true;
  }//FIM DA FUNCAO
}