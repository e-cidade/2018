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
 * Classe para retorno dos dados de exportação da situação do aluno censo 2013
 * @author     trucolo@dbseller.com.br
 * @revision   Andrio Costa <andrio.costa@dbseller.com.br>
 * @package    Educacao
 * @subpackage Censo
 */
class ExportacaoSituacaoAlunoCenso2013 {

  /**
   * Constante com o código do layout
   * @var integer
   */
  Const LAYOUT = 216;

  /**
   * Instância de Layout
   * @var DBLayoutReader
   */
  private $oLayout;

  /**
   * Instância de Log
   * @var DBLogJSON
   */
  private $oLog;

  /**
   * Ano do censo
   * @var interger
   */
  private $iAno;

  /**
   * Escola
   * @var Escola
   */
  private $oEscola;


  /**
   * Etapas NÃO permitidas no aquivo Situação do Aluno Censo
   * @var array
   */
  private $aEtapasNaoPermitidasNoArquivo = array(3, 12, 13, 22, 23, 24, 51, 56, 58, 64, 66);

  /**
   * Não podemos trazer no arquivo do censo, as matriculas que estão nas seguintes condições
   * -- Visto com Tiago
   * @var array
   */
  private $aSituacoesMatriculaNaoPermitidas = array('MATRICULA INDEVIDA', 'MATRICULA INDEFERIDA', 'TROCA DE TURMA');

  /**
   * Etapas que definem conclusão de curso
   * @var array
   */
  private $aEtapasConcluintes = array(11, 41, 27, 28, 29, 32, 33, 34, 37, 38, 39, 40, 44,45, 47, 48, 60, 61, 62, 63, 65);

  /**
   * Etapas em andamento
   * @var array
   */
  private $aEtapasEmAndamento = array(1, 2, 39, 40, 43, 44, 45, 46, 47, 48, 60, 61, 62, 63, 65);

  private $aEtapasEnsinoInfantil = array(1, 2);

  /**
   * Movimentos permitidos para campo:
   *  -> 10 do registro 90
   *  -> 13 do registro 91
   *
   * Situacoes
   *   -> 1 - Transferido
   *   -> 2 - Deixou de Frequentar
   *   -> 3 - Falecido
   *
   * @var array
   */
  private $aMovimento = array('TRANSFERIDO REDE' => 1, 'TRANSFERIDO FORA' => 1,
                              'EVADIDO' => 2, 'MATRICULA TRANCADA' => 2,'CANCELADO' => 2,'EVADIDO' => 2,
                              'FALECIDO' => 3);

  /**
   * Identifica se tem inconsistencia
   * @var boolean
   */
  private $lTemInconsistencia = false;

  /**
   * Nome do arquivo do censo
   * @var string
   */
  private $sNomeArquivo;

  /**
   * Lista dos alunos listados no registro 90 (com matrícula antes do censo)
   * @var array
   */
  private $aAlunosAntesCenso = array();

   /**
   * Método construtor
   *
   * @param DBLogJSON log
   * @param $iAno     ano do censo
   * @return
   */
  public function __construct(DBLogJSON $oLog, $iAno = 2013) {

    $this->sNomeArquivo = "tmp/situacao_aluno_censo_{$iAno}.txt";
    $this->oLayout      = new db_layouttxt(self::LAYOUT, $this->sNomeArquivo, "", 1, true);
    $this->oLog         = $oLog;
    $this->iAno         = $iAno;
  }

  /**
   * Método para escrever log de erro
   * @param String $MensagemLog
   */
  private function logErro($MensagemLog, $iIdentificador) {

    $this->lTemInconsistencia  = true;
    $oMensagem                 = new stdClass();
    $oMensagem->sErro          = utf8_encode($MensagemLog);
    $oMensagem->iIdentificador = $iIdentificador;
    $this->oLog->log($oMensagem, DBLog::LOG_ERROR);
  }

  /**
   * Método que busca os dados de cabeçalho da escola necessários conforme o leiaute do arquivo.
   */
  private function escreverDadosEscola() {

    $oDaoEscolaGestorCenso = new cl_escolagestorcenso();

    $sCamposDadosEscola  = "distinct escolagestorcenso.ed325_email as email_gestor_escolar ";
    $sCamposDadosEscola .= ",escola.ed18_c_codigoinep as codigo_escola_inep               ";
    $sCamposDadosEscola .= ",case                                  ";
    $sCamposDadosEscola .= "   when  cgmrh.z01_cgccpf is not null  ";
    $sCamposDadosEscola .= "     then cgmrh.z01_cgccpf             ";
    $sCamposDadosEscola .= "   else cgmcgm.z01_cgccpf              ";
    $sCamposDadosEscola .= " end as cpf_gestor_escolar            ";
    $sCamposDadosEscola .= ",case                                  ";
    $sCamposDadosEscola .= "   when cgmrh.z01_nome is not null     ";
    $sCamposDadosEscola .= "     then cgmrh.z01_nome               ";
    $sCamposDadosEscola .= "   else cgmcgm.z01_nome                ";
    $sCamposDadosEscola .= " end as nome_gestor_escolar         ";
    $sCamposDadosEscola .= ",case when trim(atividaderh.ed01_c_descr) = 'DIRETOR' then 1 else 2 end as cargo_gestor_escolar";
    $sCamposDadosEscola .= ",'' as separador_final ";

    $sWhereDadosEscola     = "escola.ed18_i_codigo = {$this->oEscola->getCodigo()} ";//db_getsession("DB_coddepto");
    $sSqlEscolaGestorCenso = $oDaoEscolaGestorCenso->sql_query_dados_gestor(null, $sCamposDadosEscola, null, $sWhereDadosEscola);
    $rsEscolaGestorCenso   = $oDaoEscolaGestorCenso->sql_record($sSqlEscolaGestorCenso);

    if ( !$rsEscolaGestorCenso || pg_num_rows( $rsEscolaGestorCenso ) == 0 ) {

      $sMensagem = "Dados do gestor da escola não cadastrados. Acesse: Cadastros -> Dados da Escola -> aba Gestor.";
      $this->logErro($sMensagem, 89);
    } else {

      $oEscolaGestorCenso    = db_utils::fieldsMemory($rsEscolaGestorCenso, 0);
      $oEscolaGestorCenso->tipo_registro = 89;

      if ( $this->validaDados($oEscolaGestorCenso) ) {
        $this->oLayout->setByLineOfDBUtils($oEscolaGestorCenso, 1, "89");
      }
    }

  }

  /**
   * Retorna os alunos matriculados na escola no ano do censo
   * @param boolean $lMatriculadoAposCenso
   * @return stdClass[]
   */
  private function getAlunos($lMatriculadoAposCenso = true) {

    $sCampos  = "  distinct ";
    $sCampos .= "  ed18_c_codigoinep     as codigo_escola_inep  ";
    $sCampos .= " ,ed57_i_codigo         as codigo_turma_escola  ";
    $sCampos .= " ,ed57_i_codigoinep     as codigo_turma_inep  ";
    $sCampos .= " ,trim(ed57_c_descr)    as turma      ";
    $sCampos .= " ,ed47_c_codigoinep     as codigo_aluno_inep  ";
    $sCampos .= " ,ed47_i_codigo         as codigo_aluno_escola  ";
    $sCampos .= " ,trim(ed47_v_nome)     as aluno  ";
    $sCampos .= " ,trim(ed47_v_mae)      as nome_mae  ";
    $sCampos .= " ,ed47_d_nasc           as data_nascimento  ";
    $sCampos .= " ,ed280_i_matcenso      as codigo_matricula_inep  ";
    $sCampos .= " ,ed10_i_tipoensino     as codigo_modalidade  ";
    $sCampos .= " ,ed11_i_codcenso       as codigo_etapa_censo  ";
    $sCampos .= " ,trim(ed11_c_descr)    as etapa  ";
    $sCampos .= " ,ed11_i_codigo         as codigo_etapa_interno  ";
    $sCampos .= " ,trim(ed11_c_descr)    as etapa  ";
    $sCampos .= " ,ed60_i_codigo         as codigo_matricula_aluno  ";
    $sCampos .= " ,trim(ed60_c_situacao) as situacao  ";
    $sCampos .= " ,ed60_d_datasaida      as data_saida  ";
    $sCampos .= " ,ed52_i_ano            as ano  ";

    $aWhere   = array();
    $aWhere[] = " serie.ed11_i_codcenso not in (".implode(", ", $this->aEtapasNaoPermitidasNoArquivo).") ";
    $aWhere[] = " ed60_c_situacao not in ('". implode("', '", $this->aSituacoesMatriculaNaoPermitidas)."') ";
    $aWhere[] = " escola.ed18_i_codigo  = ". $this->oEscola->getCodigo();
    $aWhere[] = " calendario.ed52_i_ano = {$this->iAno} ";
    $aWhere[] = " ed10_i_tipoensino in (1,2,3) ";
    $aWhere[] = " ed221_c_origem = 'S' ";

    $sWhereDataCenso = " matricula.ed60_d_datamatricula <= '" . $this->getDataCenso()->getDate(). "'";
    $sWhereDadaSaida = " ( matricula.ed60_d_datasaida > '" . $this->getDataCenso()->getDate(). "' or ed60_c_situacao = 'MATRICULADO')";
    if ($lMatriculadoAposCenso) {

      $sWhereDadaSaida = "";
      $sWhereDataCenso = " matricula.ed60_d_datamatricula > '" . $this->getDataCenso()->getDate(). "'";
    }

    $aWhere[] = $sWhereDataCenso;
    if ( !empty($sWhereDadaSaida) ) {
      $aWhere[] = $sWhereDadaSaida;
    }

    $sWhere = implode(" and ", $aWhere);

    $sSql  = " select {$sCampos}  ";
    $sSql .= "   from aluno       ";
    $sSql .= "   left join alunomatcenso  on alunomatcenso.ed280_i_aluno      = aluno.ed47_i_codigo ";
    $sSql .= "                           and alunomatcenso.ed280_i_ano        = {$this->iAno} ";
    $sSql .= "  inner join matricula      on matricula.ed60_i_aluno           = aluno.ed47_i_codigo ";
    $sSql .= "  inner join matriculaserie on matriculaserie.ed221_i_matricula = matricula.ed60_i_codigo ";
    $sSql .= "  inner join serie          on serie.ed11_i_codigo              = matriculaserie.ed221_i_serie  ";
    $sSql .= "  inner join turma          on turma.ed57_i_codigo              = matricula.ed60_i_turma ";
    $sSql .= "  inner join base           on base.ed31_i_codigo               = turma.ed57_i_base ";
    $sSql .= "  inner join cursoedu       on cursoedu.ed29_i_codigo           = base.ed31_i_curso ";
    $sSql .= "  inner join calendario     on calendario.ed52_i_codigo         = turma.ed57_i_calendario ";
    $sSql .= "  inner join escola         on escola.ed18_i_codigo             = turma.ed57_i_escola ";
    $sSql .= "  inner join ensino         on ensino.ed10_i_codigo             = serie.ed11_i_ensino ";
    $sSql .= "  ";
    $sSql .= "  where {$sWhere}";

    $rs      = db_query($sSql);
    $iLinhas = pg_num_rows($rs);

    $aAlunosFiltrados = array();
    for ($i = 0; $i < $iLinhas; $i++) {

      $oDadosAluno = db_utils::fieldsMemory($rs, $i);

      /**
       * Se aluno ja esta no array, significa que já foi encontrado qual o dado correto para valida-lo
       */
      if ( array_key_exists($oDadosAluno->codigo_aluno_escola, $aAlunosFiltrados) ) {
        continue;
      }

      $oDadosAluno->resultado = '';

      /**
       * Se situação da matrícula for avanço ou classificado aluno é considerado aprovado
       * Não há necessidade de ver o diário de classe
       */
      if ( in_array( $oDadosAluno->situacao, array('AVANÇADO', 'CLASSIFICADO') ) ) {

        $oDadosAluno->resultado = 'A';
        $aAlunosFiltrados[$oDadosAluno->codigo_aluno_escola] = $oDadosAluno;
        continue;
      }

      /**
       * Neste caso devemos verificar se aluno possui uma matrícula posterior no mesmo ano e escola
       */
      if ( in_array( $oDadosAluno->situacao, array('TRANSFERIDO REDE', 'TRANSFERIDO FORA') ) ) {

        $sSqlValidaProximaMatricula  = " select max(ed60_i_codigo) as matricula, trim(ed60_c_situacao) as situacao ";
        $sSqlValidaProximaMatricula .= "   from matricula ";
        $sSqlValidaProximaMatricula .= "  inner join turma      on ed57_i_codigo = ed60_i_turma ";
        $sSqlValidaProximaMatricula .= "  inner join calendario on ed52_i_codigo = ed57_i_calendario";
        $sSqlValidaProximaMatricula .= "  where ed60_c_situacao in ('TROCA DE MODALIDADE', 'MATRICULADO', 'AVANÇADO', 'CLASSIFICADO') ";
        $sSqlValidaProximaMatricula .= "    and ed60_i_aluno          = {$oDadosAluno->codigo_aluno_escola} ";
        $sSqlValidaProximaMatricula .= "    and ed57_i_escola         = {$this->oEscola->getCodigo()} ";
        $sSqlValidaProximaMatricula .= "    and ed60_c_ativa          = 'S'  ";
        $sSqlValidaProximaMatricula .= "    and ed60_d_datamatricula >= '{$oDadosAluno->data_saida}'  ";
        $sSqlValidaProximaMatricula .= "    and ed52_i_ano            = {$oDadosAluno->ano} ";
        $sSqlValidaProximaMatricula .= "  group by ed60_c_situacao  ";

        $rsValida = db_query($sSqlValidaProximaMatricula);
        if ($rsValida && pg_num_rows($rsValida) > 0) {

          $oMatriculaCerta = db_utils::fieldsMemory($rsValida, 0);
          $oDadosAluno->codigo_matricula_aluno = $oMatriculaCerta->matricula;
          $oDadosAluno->situacao               = $oMatriculaCerta->situacao;
        }
      }

      $oMatricula = MatriculaRepository::getMatriculaByCodigo($oDadosAluno->codigo_matricula_aluno);

      db_inicio_transacao();
      $oDiario = new DiarioClasse($oMatricula, false);

      if ( count( $oDiario->getDisciplinas() ) == 0 ) {

        $aAlunosFiltrados[$oDadosAluno->codigo_aluno_escola] = $oDadosAluno;
        continue;
      }

      $sResultadoFinal = $oDiario->getResultadoFinal();
      if ($oDiario->getResultadoFinal() == "R") {
        $sResultadoFinal = $oDiario->aprovadoComProgressaoParcial() ? 'A' : "R";
      }
      db_fim_transacao(true);

      $oDadosAluno->resultado = $sResultadoFinal;
      $aAlunosFiltrados[$oDadosAluno->codigo_aluno_escola] = $oDadosAluno;

    }

    if (!$lMatriculadoAposCenso) {
      $this->aAlunosAntesCenso = $aAlunosFiltrados;
    }

    return $aAlunosFiltrados;
  }


  /**
   * Escreve os registros da linha 90 no arquivo
   * @return
   */
  private function escreverAlunosAdmitidosAntesCenso() {

    $aAlunos = $this->getAlunos(false);

    foreach ($aAlunos as $oAluno) {

      $oAluno->tipo_registro = 90;

      if ( !$this->validaDados($oAluno) ) {
      	continue;
      }

      $oLinha                        = new stdClass();
      $oLinha->tipo_registro         = $oAluno->tipo_registro;
      $oLinha->codigo_escola_inep    = $oAluno->codigo_escola_inep;
      $oLinha->codigo_turma_escola   = $oAluno->codigo_turma_escola;
      $oLinha->codigo_turma_inep     = $oAluno->codigo_turma_inep;
      $oLinha->codigo_aluno_inep     = $oAluno->codigo_aluno_inep;
      $oLinha->codigo_aluno_escola   = $oAluno->codigo_aluno_escola;
      $oLinha->codigo_matricula_inep = $oAluno->codigo_matricula_inep;
      $oLinha->codigo_modalidade     = $oAluno->codigo_modalidade;
      $oLinha->codigo_etapa          = $oAluno->codigo_etapa_censo;
      $oLinha->movimento             = '';
      $oLinha->rendimento            = '';
      $oLinha->concluinte            = '';
      $oLinha->em_andamento          = 1;
      $oLinha->separador_final       = '';

      if ( array_key_exists($oAluno->situacao, $this->aMovimento) ) {

        $oLinha->movimento    = $this->aMovimento[$oAluno->situacao];
        $oLinha->em_andamento = '';
      }

      // Valida o resultado do aluno
      if ( !empty($oAluno->resultado) && empty($oLinha->movimento) ) {

        $oLinha->rendimento   = $oAluno->resultado == 'A' ? 1 : 0;
        $oLinha->em_andamento = '';
      }

      if (    in_array($oAluno->codigo_etapa_censo, $this->aEtapasConcluintes) && empty($oLinha->movimento)
           && !empty($oAluno->resultado) ) {
        $oLinha->concluinte = $oAluno->resultado == 'A' ? 1 : 0;
      }

      if ( in_array($oAluno->codigo_etapa_censo , $this->aEtapasEnsinoInfantil) && empty($oLinha->movimento) ) {

        $oLinha->movimento    = '';
        $oLinha->rendimento   = '';
        $oLinha->concluinte   = '';
        $oLinha->em_andamento = 1;
      }

      $this->oLayout->setByLineOfDBUtils($oLinha, 1, "90");
    }
  }

  /**
   * Escreve os registros da linha 91 no arquivo do censo
   * @return
   */
  private function escreverAlunosAdmitidosAposCenso() {

    $aAlunos = $this->getAlunos(true) ;

    foreach ($aAlunos as $oAluno) {

      /**
       * Validamos se aluno já não foi escrito no registro 90]
       * Não podemos repetir aluno no registro 91
       */
      if ( array_key_exists($oAluno->codigo_aluno_escola, $this->aAlunosAntesCenso) ) {
        continue;
      }

      $oAluno->tipo_registro = 91;

      if ( !$this->validaDados($oAluno) ) {
        continue;
      }

      $oDataNascimento = new DBDate($oAluno->data_nascimento);

      $oLinha                        = new stdClass();
      $oLinha->tipo_registro         = $oAluno->tipo_registro;                         // 1
      $oLinha->codigo_escola_inep    = $oAluno->codigo_escola_inep;                    // 2
      $oLinha->codigo_turma_escola   = $oAluno->codigo_turma_escola;                   // 3
      $oLinha->codigo_turma_inep     = '';                                             // 4
      $oLinha->codigo_aluno_inep     = $oAluno->codigo_aluno_inep;                     // 5
      $oLinha->codigo_aluno_escola   = $oAluno->codigo_aluno_escola;                   // 6
      $oLinha->nome_aluno            = $oAluno->aluno;                                 // 7
      $oLinha->data_nascimento       = $oDataNascimento->convertTo(DBDate::DATA_PTBR); // 8
      $oLinha->nome_mae              = $oAluno->nome_mae;                              // 9
      $oLinha->codigo_matricula_inep = '';                                             // 10
      $oLinha->modalidade            = $oAluno->codigo_modalidade;                     // 11
      $oLinha->codigo_etapa          = $oAluno->codigo_etapa_censo;                    // 12
      $oLinha->movimento             = '';                                             // 13
      $oLinha->rendimento            = '';                                             // 14
      $oLinha->concluinte            = '';                                             // 15
      $oLinha->em_andamento          = 1;                                              // 16
      $oLinha->separador_final       = '';

      if ( array_key_exists($oAluno->situacao, $this->aMovimento) ) {

        $oLinha->movimento    = $this->aMovimento[$oAluno->situacao];
        $oLinha->em_andamento = '';
      }

      // Valida o resultado do aluno
      if ( !empty($oAluno->resultado) && empty($oLinha->movimento) ) {

        $oLinha->rendimento   = $oAluno->resultado == 'A' ? 1 : 0;
        $oLinha->em_andamento = '';
      }

      if (    in_array($oAluno->codigo_etapa_censo, $this->aEtapasConcluintes) && empty($oLinha->movimento)
      && !empty($oAluno->resultado) ) {
        $oLinha->concluinte = $oAluno->resultado == 'A' ? 1 : 0;
      }

      if ( in_array($oAluno->codigo_etapa_censo , $this->aEtapasEnsinoInfantil) ) {

        $oLinha->movimento    = '';
        $oLinha->rendimento   = '';
        $oLinha->concluinte   = '';
        $oLinha->em_andamento = 1;
      }

      $this->oLayout->setByLineOfDBUtils($oLinha, 1, "91");
    }
  }

  /**
   * Define a escola que esta gerando o censo
   * @param Escola $oEscola
   */
  public function setEscola( Escola $oEscola) {

    $this->oEscola = $oEscola;
  }

  /**
   * Retorna a data do censo
   * @return DBDate
   */
  private function getDataCenso () {

    for ($dia = 31; $dia > 0; $dia-- ) {

      if ( date ( "w", mktime(0, 0, 0, 5, $dia, $this->iAno) ) == 3 ) {

        $iDia = str_pad($dia, 2, '0', STR_PAD_LEFT);
        $oData = new DBDate("{$iDia}/05/{$this->iAno}");
        return $oData;
      }
    }
  }


  /**
   * Gera o arquivo
   * @return boolean
   */
  public function gerarArquivo() {

    $this->escreverDadosEscola();
    $this->escreverAlunosAdmitidosAntesCenso();
    $this->escreverAlunosAdmitidosAposCenso();

    if ($this->lTemInconsistencia) {
    	unlink($this->sNomeArquivo);
    }
    return !$this->lTemInconsistencia;
  }

  /**
   * Valida os dados dos campos conforme layout
   * @param stdClass $oDados os dados variam de acordo com o identificador a ser validado
   * @return boolean
   */
  private function validaDados($oDados) {

    /**
     * Quando alizamos os dados do registro 89 não temos as propriedades setadas abaixo.
     * Setamos elas com vazi para o php não lançar um warning ao montar o array de validações.
     */
    if ($oDados->tipo_registro == 89) {

      $oDados->aluno = $oDados->turma = $oDados->etapa = "";
      $this->validaDadosEscola( $oDados );
    }


    $iCodigo      = !empty($oDados->codigo_aluno_escola) ? $oDados->codigo_aluno_escola : '';
    $dtNascimento = !empty($oDados->data_nascimento) ? $oDados->data_nascimento : '';

    $iTurmaInep   = !empty($oDados->codigo_turma_inep) ? $oDados->codigo_turma_inep : '';
    $sTurma       = !empty($oDados->turma) ? $oDados->turma : '';


    $aValidacoes = array( 89 => array( "email_gestor_escolar" => "Email do gestor não informado."
                                      ,"cargo_gestor_escolar" => "Cargo do gestor não foi informado."
                                      ,"nome_gestor_escolar" => "Nome do gestor não informado."
                                      ,"cpf_gestor_escolar" => "Cpf do gestor não informado."
                                      ,"codigo_escola_inep" => "Escola não possui código INEP."
                                     ),
                          90 => array( "codigo_turma_inep" => "Turma {$oDados->turma} - {$oDados->etapa} do aluno(a) {$oDados->aluno} está sem o código do INEP."
                                      ,"codigo_aluno_inep" => "O(a) aluno(a) {$iCodigo} - {$oDados->aluno} ({$dtNascimento}) está sem o código do INEP."
                                      ,"codigo_matricula_inep" => "O(a) aluno(a) {$iCodigo} - {$oDados->aluno} ({$dtNascimento}) não possui matrícula do INEP. Turma {$sTurma} INEP: {$iTurmaInep} "
                                      ,"codigo_modalidade" => "Modalidade não informada."
                                      ,"codigo_etapa_censo" => "Etapa do censo da turma {$oDados->turma} - {$oDados->etapa} não informada."
                                     ),
                          91  => array( "codigo_aluno_inep" => "O(a) aluno(a) {$iCodigo} - {$oDados->aluno} ({$dtNascimento}) está sem o código do INEP.")
                        );

    foreach ($aValidacoes[$oDados->tipo_registro] as $sCampoValidar => $sMensagem) {

      if (empty($oDados->{$sCampoValidar}) ) {
      	$this->logErro($sMensagem, $oDados->tipo_registro);
      }
    }

    return !$this->lTemInconsistencia;
  }

  /**
   * Retorna o path do arquivo gerado
   * @return string
   */
  public function getNomeArquivoCenso() {
    return $this->sNomeArquivo;
  }

  /**
   * Validações referentes ao registro 89. Retorna se encontrou alguma inconsistência
   *
   * @param  stdClass $oDados
   * @return boolean
   */
  public function validaDadosEscola( $oDados ) {

    /**
     * Valida se o CPF é valido
     */
    if ( !DBString::isCPF( $oDados->cpf_gestor_escolar ) || $oDados->cpf_gestor_escolar == "00000000191" ) {

      $sMensagem = "Número do CPF [{$oDados->cpf_gestor_escolar}] do gestor é inválido.";
      $this->logErro( $sMensagem, $oDados->tipo_registro );
    }

    /**
     * Valida se o nome do gestor foi informado
     */
    if ( $oDados->nome_gestor_escolar == '' ) {

      $sMensagem = "Nome do gestor é obrigatório.";
      $this->logErro( $sMensagem, $oDados->tipo_registro );
    }

    /**
     * Valida se o nome do gestor possui 4 letras repetidas em sequência
     */
    $sExpressao          = '/([a-zA-Z])\1{3}/';
    $lValidacaoExpressao = preg_match( $sExpressao, $oDados->nome_gestor_escolar ) ? true : false;

    if ( $lValidacaoExpressao ) {

      $sMensagem = "Nome do gestor inválido. Não é possível informar mais de 4 letras repetidas em sequência.";
      $this->logErro( $sMensagem, $oDados->tipo_registro );
    }

    /**
     * Valida se o nome possui nome e sobrenome
     */
    if ( !DBString::isNomeValido( $oDados->nome_gestor_escolar, DBString::NOME_REGRA_3 ) ) {

      $sMensagem = "Nome do gestor inválido. Deve ser composto de nome e sobrenome.";
      $this->logErro( $sMensagem, $oDados->tipo_registro );
    }

    /**
     * Valida se o nome contem somente letras
     */
    if ( !DBString::isSomenteLetras( str_replace( " ", "", $oDados->nome_gestor_escolar ) ) ) {

      $sMensagem = "Nome do gestor inválido. Deve conter somente letras.";
      $this->logErro( $sMensagem, $oDados->tipo_registro );
    }

    /**
     * Valida o email do gestor
     */
    if ( !DBString::isEmail( $oDados->email_gestor_escolar ) ) {

      $sMensagem = "E-mail do gestor inválido.";
      $this->logErro( $sMensagem, $oDados->tipo_registro );
    }
  }
}