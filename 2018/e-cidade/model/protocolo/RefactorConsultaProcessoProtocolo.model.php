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
 * Refactor da consulta de processo do protocolo
 *
 * @package protocolo
 * @author Jeferson Belmiro <jeferson.belmiro@dbseller.com.br>
 */
class RefactorConsultaProcessoProtocolo {

  private $iCodigoProcesso;
  private $iUsuarioLogado;
  private $aMovimentacoes;

  public function __construct($iCodigoProcesso) {

    $this->iCodigoProcesso = $iCodigoProcesso;
    $this->iUsuarioLogado = db_getsession("DB_id_usuario");
    $this->processarMovimentacoes();
  }

  public function processarMovimentacoes() {

    $codproc = $this->iCodigoProcesso;


    $aTiposTextoDespachos   = array(
                                    1 => "Interno",
                                    2 => ""
                                  );
    $clprotprocesso          = db_utils::getDao('protprocesso');
    $clprotprocessodoc       = db_utils::getDao('procprocessodoc');
    $clprotprocessoapensados = db_utils::getDao('processosapensados');
    $clprocandam             = db_utils::getDao('procandam');
    $clproctransfer          = db_utils::getDao('proctransfer');
    $clproctransferproc      = db_utils::getDao('proctransferproc');
    $clproctransand          = db_utils::getDao('proctransand');
    $clproctransferintand    = db_utils::getDao('proctransferintand');
    $clproctransferint       = db_utils::getDao('proctransferint');
    $clprocandamint          = db_utils::getDao('procandamint');
    $clprocandamintand       = db_utils::getDao('procandamintand');
    $clarqproc               = db_utils::getDao('arqproc');
    $clarqandam              = db_utils::getDao('arqandam');
    $clprotparam             = db_utils::getDao('protparam');
    $clprotprocessodocumento = db_utils::getDao('protprocessodocumento');

    $cod_procandamint = 0;
    $lProcessoArquivado = false;

    $result_param = $clprotparam->sql_record($clprotparam->sql_query(null,"*",null,"p90_instit=".db_getsession("DB_instit")));

    if ( $clprotparam->numrows > 0 ) {
      extract( (array) db_utils::fieldsMemory($result_param, 0));
    }

    $result_protprocesso = $clprotprocesso->sql_record($clprotprocesso->sql_query($codproc));

    /**
     * Não encontrou processo
     */
    if ($clprotprocesso->numrows == 0) {
      throw new Exception("Processo não encontrado: {$this->iCodigoProcesso}");
    }

    extract( (array) db_utils::fieldsMemory($result_protprocesso, 0));

    $sSqlTransferencias      = $clproctransferproc->sql_query_transferencia(null, null, "*", "p62_dttran, p62_hora, p63_codtran", "p63_codproc = $codproc");
    $result_proctransferproc = $clproctransferproc->sql_record($sSqlTransferencias);

    /**
     * Processo com transferencias
     */
    if ( $clproctransferproc->numrows > 0 ) {

      /**
       * Processo criado
       */
      $oDadosMovimentacao = new RefactorDadosMovimentacaoProcessoProtocolo();
      $oDadosMovimentacao->sData = db_formatar($p58_dtproc, 'd');
      $oDadosMovimentacao->sHora = $p58_hora;
      $oDadosMovimentacao->iDepartamento = $p58_coddepto;
      $oDadosMovimentacao->sDepartamento = $descrdepto;
      $oDadosMovimentacao->iInstituicao = $p58_instit;
      $oDadosMovimentacao->sInstituicao = $nomeinstabrev;
      $oDadosMovimentacao->sLogin = $nome;
      $oDadosMovimentacao->sObservacoes = 'Processo Criado';
      $oDadosMovimentacao->sDespacho = "";

      $this->aMovimentacoes[] = $oDadosMovimentacao;

      $lTramiteInicial = true;

      for ($y = 0; $y < $clproctransferproc->numrows; $y ++) {

        extract( (array) db_utils::fieldsMemory($result_proctransferproc, $y));

        $sCamposProcessoTransf  = " atual.instit, ";
        $sCamposProcessoTransf .= " instiatual.nomeinstabrev, ";
        $sCamposProcessoTransf .= " p62_codtran, ";
        $sCamposProcessoTransf .= " p62_dttran, ";
        $sCamposProcessoTransf .= " p62_hora, ";
        $sCamposProcessoTransf .= " p62_coddepto, ";
        $sCamposProcessoTransf .= " p62_coddeptorec, ";
        $sCamposProcessoTransf .= " atual.descrdepto as deptoatual, ";
        $sCamposProcessoTransf .= " destino.descrdepto as deptodestino, ";
        $sCamposProcessoTransf .= " destino.coddepto as coddeptodestino, ";
        $sCamposProcessoTransf .= " usu_atual.nome as nome, ";
        $sCamposProcessoTransf .= " proctransfer.p62_id_usorec as idusuariodestino, ";
        $sCamposProcessoTransf .= " usu_destino.login as loginusuariodestino ";
        $sWhereProcessoTranf    = "p62_codtran = $p63_codtran";

        $sSqlProcessoTransf  = $clproctransfer->sql_query_deps(null, $sCamposProcessoTransf, null, $sWhereProcessoTranf);
        $result_proctransfer = $clproctransfer->sql_record($sSqlProcessoTransf);

        /**
         * Transferencias do processo
         */
        if ($clproctransfer->numrows > 0) {

          extract( (array) db_utils::fieldsMemory($result_proctransfer, 0));

          /**
           * Tramiite inicial
           */
          if ($lTramiteInicial) {

            $oDadosMovimentacao = new RefactorDadosMovimentacaoProcessoProtocolo();
            $oDadosMovimentacao->sData = db_formatar($p62_dttran, 'd');
            $oDadosMovimentacao->sHora = $p62_hora;
            $oDadosMovimentacao->iDepartamento = $p62_coddepto;
            $oDadosMovimentacao->sDepartamento = $deptoatual;
            $oDadosMovimentacao->iInstituicao = $instit;
            $oDadosMovimentacao->sInstituicao = $nomeinstabrev;
            $oDadosMovimentacao->sLogin = $nome;
            $oDadosMovimentacao->sObservacoes = "Tramite Inicial $p62_codtran p/ Departamento: $coddeptodestino - $deptodestino ";

            if ( (int) $idusuariodestino > 0 ) {
              $oDadosMovimentacao->sObservacoes .= " - usuário especificado: $idusuariodestino - $loginusuariodestino";
            } else {
              $oDadosMovimentacao->sObservacoes .= " (sem usuário especificado)";
            }

            $oDadosMovimentacao->sDespacho = "";

            $this->aMovimentacoes[] = $oDadosMovimentacao;

            $lTramiteInicial = false;

          } else {

            $sWhereProcessoTransand = " p64_codtran = $p62_codtran and p61_codproc = $codproc ";
            $sSqlProcessoTransand   = $clproctransand->sql_query_consandam("", "p64_codandam", null, $sWhereProcessoTransand);
            $result_proctransand    = $clproctransand->sql_record($sSqlProcessoTransand);

            if ($clproctransand->numrows > 0) {

              extract( (array) db_utils::fieldsMemory($result_proctransand, 0));

              $result_procandam = $clprocandam->sql_record($clprocandam->sql_query_com(null, "procandam.*", null, "p61_codandam = $p64_codandam"));

              if ($clprocandam->numrows > 0) {
                extract( (array) db_utils::fieldsMemory($result_procandam, 0));
              }
            }

            $result_arqandam = $clarqandam->sql_record($clarqandam->sql_query_file(null, "*", null, "p69_codandam = ".@$p61_codandam));

            if ($p62_coddepto == $p62_coddeptorec && $clarqandam->numrows > 0) {

              $lProcessoArquivado = true;

            } else {

              $oDadosMovimentacao = new RefactorDadosMovimentacaoProcessoProtocolo();
              $oDadosMovimentacao->sData = db_formatar($p62_dttran, 'd');
              $oDadosMovimentacao->sHora = $p62_hora;
              $oDadosMovimentacao->iDepartamento = $p62_coddepto;
              $oDadosMovimentacao->sDepartamento = $deptoatual;
              $oDadosMovimentacao->iInstituicao = $instit;
              $oDadosMovimentacao->sInstituicao = $nomeinstabrev;
              $oDadosMovimentacao->sLogin = $nome;
              $oDadosMovimentacao->sObservacoes = "Transferência $p62_codtran p/ o Departamento: $coddeptodestino - $deptodestino";

              if ( (int) $idusuariodestino > 0 ) {
                $oDadosMovimentacao->sObservacoes .= " - usuário especificado: $idusuariodestino - $loginusuariodestino";
              } else {
                $oDadosMovimentacao->sObservacoes .= " (sem usuário especificado)";
              }

              $oDadosMovimentacao->sDespacho = "";

              $this->aMovimentacoes[] = $oDadosMovimentacao;
            }
          }

          $result_proctransand = $clproctransand->sql_record($clproctransand->sql_query_consandam("", "*", null, "p64_codtran = $p62_codtran and p61_codproc = $codproc  "));

          if ($clproctransand->numrows > 0) {

            extract( (array) db_utils::fieldsMemory($result_proctransand, 0));

            $result_procandam = $clprocandam->sql_record($clprocandam->sql_query_com(null, "*", null, "p61_codandam = $p64_codandam"));

            if ($clprocandam->numrows > 0) {

              extract( (array) db_utils::fieldsMemory($result_procandam, 0));

              $oDadosMovimentacao = new RefactorDadosMovimentacaoProcessoProtocolo();
              $oDadosMovimentacao->sData = db_formatar($p61_dtandam, 'd');
              $oDadosMovimentacao->sHora = $p61_hora;
              $oDadosMovimentacao->iDepartamento = $p61_coddepto;
              $oDadosMovimentacao->sDepartamento = $descrdepto;
              $oDadosMovimentacao->iInstituicao = $instit;
              $oDadosMovimentacao->sInstituicao = $nomeinstabrev;
              $oDadosMovimentacao->sLogin = $nome;

              /**
               * Processo arquivado
               */
              if ($lProcessoArquivado == true) {

                $result_arqandam = $clarqandam->sql_record($clarqandam->sql_query_file(null, "*", null, "p69_codandam = $p61_codandam"));

                if ($clarqandam->numrows > 0) {

                  extract( (array) db_utils::fieldsMemory($result_arqandam, 0));

                  if ($p69_arquivado == 't') {
                    $oDadosMovimentacao->sObservacoes = "Processo Arquivado";
                  } else {
                    $oDadosMovimentacao->sObservacoes = "Desarquivamento";
                  }
                }

              /**
               * Processo recebeu transferencia
               */
              } else {
                $oDadosMovimentacao->sObservacoes = "Recebeu Transferência - $p62_codtran";
              }

              $oDadosMovimentacao->sDespacho = "$p61_despacho";

              $this->aMovimentacoes[]  = $oDadosMovimentacao;
              $sQueryDespacho          = $clprocandamint->sql_query_sim(null, "*,
                                                                        coalesce(p100_descricao,'Despacho') as tipo_despacho,
                                                                        coalesce(p100_sequencial, 1) as codigo_tipo_despacho",
                                                                        "p78_sequencial", "p78_codandam = $p61_codandam  ");
              $result_procandamint_des = $clprocandamint->sql_record($sQueryDespacho);

              /**
               * Despacho interno
               */
              if ($clprocandamint->numrows > 0) {

                for ($x = 0; $x < $clprocandamint->numrows; $x ++) {

                  extract( (array) db_utils::fieldsMemory($result_procandamint_des, $x));

                  /**
                   * Despacho interno transferido
                   */
                  if ($p78_transint == 't') {
                    break;
                  }

                  $oDadosMovimentacao = new RefactorDadosMovimentacaoProcessoProtocolo();
                  $oDadosMovimentacao->sData = db_formatar($p78_data, 'd');
                  $oDadosMovimentacao->sHora = $p78_hora;
                  $oDadosMovimentacao->iDepartamento = $p61_coddepto;
                  $oDadosMovimentacao->sDepartamento = $descrdepto;
                  $oDadosMovimentacao->iInstituicao = $instit;
                  $oDadosMovimentacao->sInstituicao = $nomeinstabrev;
                  $oDadosMovimentacao->sLogin = $nome;
                  $oDadosMovimentacao->sObservacoes = "{$tipo_despacho} {$aTiposTextoDespachos[$codigo_tipo_despacho]}";
                  $oDadosMovimentacao->sDespacho = "$p78_despacho";
                  $oDadosMovimentacao->iAndamentoInterno = $p78_sequencial;

                  if ($p78_usuario == $this->iUsuarioLogado) {
                    $oDadosMovimentacao->lImprimir = true;
                  }

                  $this->aMovimentacoes[] = $oDadosMovimentacao;

                  $cod_procandamint = $p78_sequencial;

                  /**
                   * Verifica se há documentos anexados.
                   */
                  $sQueryDocumentosDespacho = $clprotprocessodocumento->sql_query_file(null, '*', 'p01_sequencial', "p01_procandamint = $p78_sequencial");
                  $rsProcessoDocumento      = $clprotprocessodocumento->sql_record($sQueryDocumentosDespacho);

                  if($clprotprocessodocumento->numrows > 0) {
                    $oDadosMovimentacao->lAnexos = true;
                  }
                }
              }

              $result_proctransferintand = $clproctransferintand->sql_record($clproctransferintand->sql_query_file(null, "*", "p87_codtransferint", "p87_codandam = $p61_codandam"));

              /**
               * Transferncia interna
               */
              if ($clproctransferintand->numrows > 0) {

                for ($yy = 0; $yy < $clproctransferintand->numrows; $yy ++) {

                  extract( (array) db_utils::fieldsMemory($result_proctransferintand, $yy));

                  $sCamposTransfInt  = "p88_codigo, p88_data, p88_hora, p88_despacho, p88_publico, atual.nome as usuatual, ";
                  $sCamposTransfInt .= "destino.nome as usudestino, destino.id_usuario as idusudestino";

                  $sSqlProcTransfInt      = $clproctransferint->sql_query_andusu(null, $sCamposTransfInt, null, "p88_codigo=$p87_codtransferint");
                  $result_proctransferint = $clproctransferint->sql_record($sSqlProcTransfInt);

                  /**
                   * Transferencia interna
                   */
                  if ($clproctransferint->numrows > 0) {

                    extract( (array) db_utils::fieldsMemory($result_proctransferint, 0));

                    $oDadosMovimentacao = new RefactorDadosMovimentacaoProcessoProtocolo();
                    $oDadosMovimentacao->sData = db_formatar($p88_data, 'd');
                    $oDadosMovimentacao->sHora = $p88_hora;
                    $oDadosMovimentacao->iDepartamento = $p61_coddepto;
                    $oDadosMovimentacao->sDepartamento = $descrdepto;
                    $oDadosMovimentacao->iInstituicao = $instit;
                    $oDadosMovimentacao->sInstituicao = $nomeinstabrev;
                    $oDadosMovimentacao->sLogin = $usuatual;
                    $oDadosMovimentacao->sObservacoes = "Transferência Interna - $p87_codtransferint para: $idusudestino - $usudestino";
                    $oDadosMovimentacao->sDespacho = "$p88_despacho";

                    $this->aMovimentacoes[] = $oDadosMovimentacao;

                    $sSqlProcandamintand    = $clprocandamintand->sql_query_file(null, "*", "p86_codtrans", "p86_codtrans=$p88_codigo and p86_codandam = $p87_codandam ");
                    $result_procandamintand = $clprocandamintand->sql_record($sSqlProcandamintand);

                    /**
                     * Recebeu transferencia interna
                     */
                    if ($clprocandamintand->numrows > 0) {

                      extract( (array) db_utils::fieldsMemory($result_procandamintand, 0));

                      $sWhereProcandamint_trans  = "p78_sequencial > $cod_procandamint  and p78_codandam = $p86_codandam ";
                      $sSqlProcandamint_trans    = $clprocandamint->sql_query_sim(null,
                                                                                  "*,
                                                                                  coalesce(p100_descricao,'Despacho') as tipo_despacho,
                                                                                  coalesce(p100_sequencial, 1) as codigo_tipo_despacho",
                                                                                  "p78_sequencial", $sWhereProcandamint_trans);
                      $result_procandamint_trans = $clprocandamint->sql_record($sSqlProcandamint_trans);

                      if ($clprocandamint->numrows > 0) {

                        for ($xx = 0; $xx < $clprocandamint->numrows; $xx ++) {

                          extract( (array) db_utils::fieldsMemory($result_procandamint_trans, $xx));

                          if ($xx > 0) {
                            if ($cod_usu != $p78_usuario) {
                              break;
                            }
                          }

                          $oDadosMovimentacao = new RefactorDadosMovimentacaoProcessoProtocolo();
                          $oDadosMovimentacao->sData = db_formatar($p78_data, 'd');
                          $oDadosMovimentacao->sHora = $p78_hora;
                          $oDadosMovimentacao->iDepartamento = $p61_coddepto;
                          $oDadosMovimentacao->sDepartamento = $descrdepto;
                          $oDadosMovimentacao->iInstituicao = $instit;
                          $oDadosMovimentacao->sInstituicao = $nomeinstabrev;
                          $oDadosMovimentacao->sLogin = $nome;
                          $oDadosMovimentacao->sDespacho = "$p78_despacho";
                          $oDadosMovimentacao->iAndamentoInterno = $p78_sequencial;

                          /**
                           * Recebeu transferencia interna
                           */
                          if ($p78_transint == 't') {

                            $oDadosMovimentacao->sObservacoes = "Recebeu Transferência Interna";

                          /**
                           * Despacho interno
                           */
                          } else {

                            $oDadosMovimentacao->sObservacoes = "{$tipo_despacho} {$aTiposTextoDespachos[$codigo_tipo_despacho]}";

                            if ($p78_usuario == $this->iUsuarioLogado) {
                              $oDadosMovimentacao->lImprimir = true;
                            }
                          }

                          $this->aMovimentacoes[] = $oDadosMovimentacao;
                          $cod_usu = $p78_usuario;
                          $cod_procandamint = $p78_sequencial;
                        }
                      }
                    }
                  }
                }
              }
            }
          }
        }

        $lProcessoArquivado = false;

        if (isset ($oDadosParametros->p90_andatual) && $oDadosParametros->p90_andatual == "t") {

          /**
           * Andamento anual
           */
          if ($y == $clproctransferproc->numrows - 1) {

            $oDadosMovimentacao = new RefactorDadosMovimentacaoProcessoProtocolo();
            $oDadosMovimentacao->sData = db_formatar($p61_dtandam, 'd');
            $oDadosMovimentacao->sHora = $p61_hora;
            $oDadosMovimentacao->iDepartamento = $p61_coddepto;
            $oDadosMovimentacao->sDepartamento = $descrdepto;
            $oDadosMovimentacao->iInstituicao = $instit;
            $oDadosMovimentacao->sInstituicao = $nomeinstabrev;
            $oDadosMovimentacao->sLogin = $nome;
            $oDadosMovimentacao->sObservacoes = "Andamento atual";
            $oDadosMovimentacao->sDespacho = "$p58_despacho";

            $this->aMovimentacoes[] = $oDadosMovimentacao;
          }
        }

      } // for transferencias

    /**
     * Recebeu processo
     */
    } else {

      $result_procandam = $clprocandam->sql_record($clprocandam->sql_query_com(null, "*", "p61_codandam", "p61_codproc = $codproc"));

      if ($clprocandam->numrows > 0) {

        for ($xy = 0; $xy < $clprocandam->numrows; $xy ++) {

          extract( (array) db_utils::fieldsMemory($result_procandam, $xy));

          $oDadosMovimentacao = new RefactorDadosMovimentacaoProcessoProtocolo();
          $oDadosMovimentacao->sData = db_formatar($p61_dtandam, 'd');
          $oDadosMovimentacao->sHora = $p61_hora;
          $oDadosMovimentacao->iDepartamento = $p61_coddepto;
          $oDadosMovimentacao->sDepartamento = $descrdepto;
          $oDadosMovimentacao->iInstituicao = $instit;
          $oDadosMovimentacao->sInstituicao = $nomeinstabrev;
          $oDadosMovimentacao->sLogin = $nome;
          $oDadosMovimentacao->sObservacoes = "Recebeu Processo";
          $oDadosMovimentacao->sDespacho = "$p61_despacho";

          $this->aMovimentacoes[] = $oDadosMovimentacao;

          $sSqlProcandamint_des = $clprocandamint->sql_query_sim(null,
                                                                 "*,
                                                                 coalesce(p100_descricao,'Despacho') as tipo_despacho,
                                                                 coalesce(p100_sequencial, 1) as codigo_tipo_despacho",
                                                                "p78_sequencial", "p78_codandam = $p61_codandam  ");
          $result_procandamint_des = $clprocandamint->sql_record($sSqlProcandamint_des);

          /**
           *  Dispacho interno
           */
          if ($clprocandamint->numrows > 0) {

            for ($x = 0; $x < $clprocandamint->numrows; $x ++) {

              extract( (array) db_utils::fieldsMemory($result_procandamint_des, $x));

              /**
               * Despacho interno transferido
               */
              if ($p78_transint == 't') {
                break;
              }

              $oDadosMovimentacao = new RefactorDadosMovimentacaoProcessoProtocolo();
              $oDadosMovimentacao->sData = db_formatar($p78_data, 'd');
              $oDadosMovimentacao->sHora = $p78_hora;
              $oDadosMovimentacao->iDepartamento = $p61_coddepto;
              $oDadosMovimentacao->sDepartamento = $descrdepto;
              $oDadosMovimentacao->iInstituicao = $instit;
              $oDadosMovimentacao->sInstituicao = $nomeinstabrev;
              $oDadosMovimentacao->sLogin = $nome;
              $oDadosMovimentacao->sObservacoes = "{$tipo_despacho} {$aTiposTextoDespachos[$codigo_tipo_despacho]}";
              $oDadosMovimentacao->sDespacho = "$p78_despacho";
              $oDadosMovimentacao->iAndamentoInterno = $p78_sequencial;

              if ($p78_usuario == $this->iUsuarioLogado) {
                $oDadosMovimentacao->lImprimir = true;
              }

              $this->aMovimentacoes[] = $oDadosMovimentacao;

              $cod_procandamint = $p78_sequencial;
            }

          }

          $result_proctransferintand = $clproctransferintand->sql_record($clproctransferintand->sql_query_file(null, "*", "p87_codtransferint", "p87_codandam = $p61_codandam"));

          if ($clproctransferintand->numrows > 0) {

            for ($yy = 0; $yy < $clproctransferintand->numrows; $yy ++) {

              extract( (array) db_utils::fieldsMemory($result_proctransferintand, $yy));

              $result_proctransferint = $clproctransferint->sql_record($clproctransferint->sql_query_andusu(null, "p88_codigo,p88_data,p88_hora,p88_despacho,p88_publico,atual.nome as usuatual,destino.nome as usudestino", null, "p88_codigo=$p87_codtransferint"));

              if ($clproctransferint->numrows > 0) {

                extract( (array) db_utils::fieldsMemory($result_proctransferint, 0));

                $oDadosMovimentacao = new RefactorDadosMovimentacaoProcessoProtocolo();
                $oDadosMovimentacao->sData = db_formatar($p88_data, 'd');
                $oDadosMovimentacao->sHora = $p88_hora;
                $oDadosMovimentacao->iDepartamento = $p61_coddepto;
                $oDadosMovimentacao->sDepartamento = $descrdepto;
                $oDadosMovimentacao->iInstituicao = $instit;
                $oDadosMovimentacao->sInstituicao = $nomeinstabrev;
                $oDadosMovimentacao->sLogin = $usuatual;
                $oDadosMovimentacao->sObservacoes = "Transferência Interna para $usudestino";
                $oDadosMovimentacao->sDespacho = "$p88_despacho";

                $this->aMovimentacoes[] = $oDadosMovimentacao;

                $result_procandamintand = $clprocandamintand->sql_record($clprocandamintand->sql_query_file(null, "*", "p86_codtrans", "p86_codtrans=$p88_codigo and p86_codandam = $p87_codandam "));

                if ($clprocandamintand->numrows > 0) {

                  extract( (array) db_utils::fieldsMemory($result_procandamintand, 0));
                  $sSqlDadosDespacho         =  $clprocandamint->sql_query_sim(null,
                                                                               "*,  coalesce(p100_descricao,'Despacho') as tipo_despacho,
                                                                               coalesce(p100_sequencial, 1) as codigo_tipo_despacho", "p78_sequencial",
                                                                               "p78_sequencial > {$cod_procandamint}
                                                                               and p78_codandam = {$p86_codandam}");
                  $result_procandamint_trans = $clprocandamint->sql_record($sSqlDadosDespacho);

                  if ($clprocandamint->numrows > 0) {

                    for ($xx = 0; $xx < $clprocandamint->numrows; $xx ++) {

                      extract( (array) db_utils::fieldsMemory($result_procandamint_trans, $xx));

                      if ($xx > 0) {
                        if ($cod_usu != $p78_usuario) {
                          break;
                        }
                      }

                      $oDadosMovimentacao = new RefactorDadosMovimentacaoProcessoProtocolo();
                      $oDadosMovimentacao->sData = db_formatar($p78_data, 'd');
                      $oDadosMovimentacao->sHora = $p78_hora;
                      $oDadosMovimentacao->iDepartamento = $p61_coddepto;
                      $oDadosMovimentacao->sDepartamento = $descrdepto;
                      $oDadosMovimentacao->iInstituicao = $instit;
                      $oDadosMovimentacao->sInstituicao = $nomeinstabrev;
                      $oDadosMovimentacao->sLogin = $nome;
                      $oDadosMovimentacao->sDespacho = "$p78_despacho";
                      $oDadosMovimentacao->iAndamentoInterno = $p78_sequencial;

                      if ($p78_transint == 't') {

                        $oDadosMovimentacao->sObservacoes = "Recebeu Transferência Interna";

                      } else {

                        $oDadosMovimentacao->sObservacoes = "{$tipo_despacho} {$aTiposTextoDespachos[$codigo_tipo_despacho]}";

                        if ($p78_usuario == $this->iUsuarioLogado) {
                          $oDadosMovimentacao->lImprimir = true;
                        }

                      }

                      $this->aMovimentacoes[] = $oDadosMovimentacao;

                      $cod_usu = $p78_usuario;
                      $cod_procandamint = $p78_sequencial;
                    }
                  }
                }
              }
            }
          }
        }
      }

    } // else

  } // processarMovimentacoes

  public function getMovimentacoes() {
    return $this->aMovimentacoes;
  }

}

/**
 * Refactor com dados da movimentacao do processo
 *
 * @package protocolo
 * @author Jeferson Belmiro <jeferson.belmiro@dbseller.com.br>
 */
class RefactorDadosMovimentacaoProcessoProtocolo {

  public $sData;
  public $sHora;
  public $iDepartamento;
  public $sDepartamento;
  public $iInstituicao;
  public $sInstituicao;
  public $sLogin;
  public $sObservacoes;
  public $sDespacho;
  public $iAndamentoInterno;
  public $lImprimir = false;
  public $lAnexos   = false;

  /**
   * Valida antes de declarar propriedades do refactor
   * - nao permite usar propriedades nao declaradas
   *
   * @param string $sVariavel
   * @param mixed $mValor
   * @access public
   * @exception - variavel nao declarada
   * @return void
   */
  public function __set($sVariavel, $mValor) {

    if ( !property_exists($this, $sVariavel) ) {
      throw new Exception(__CLASS__ . ": Propriedade {$sVariavel} não encontrada.");
    }

    $this->{$sVariavel} = $mValor;
  }

}
