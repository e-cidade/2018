<?php
/**
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

require_once(modification("fpdf151/impcarne.php"));
require_once(modification("fpdf151/scpdf.php"));

require_once(modification("classes/db_cgm_classe.php"));
require_once(modification("classes/db_iptubase_classe.php"));
require_once(modification("classes/db_issbase_classe.php"));
require_once(modification("classes/db_rhemitecontracheque_classe.php"));
require_once(modification("classes/db_cfpess_classe.php"));

require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_libpessoal.php"));



class ContraChequeWebService {

    public function emitir($matricula, $ano, $mes, $folha, $numero) {            
        
        $oGet = new \stdClass();
        $oGet->sFiltro = "M";
        $oGet->iAno  = $ano;
        $oGet->iMes  = $mes;
        $oGet->sOpcao = $folha;
        $oGet->sLista = $matricula;
        $oGet->iNumVias =  1;
        $oGet->iSemest = $numero;

        $oDaoEmiteContraCheque = new cl_rhemitecontracheque();
        $oDaoCfPess            = new cl_cfpess();

        /**
         * Corrige caracteres especias enviados por parametro
         */
        $oGet->sMensagem = stripslashes(urldecode($oGet->sMensagem));
        $sMatricula   = $matricula;
        $iAnoFolha    = $ano;
        $iMesFolha    = $mes;
        $sFolha       = $folha;
        $iInstituicao = db_getsession('DB_instit');

        /**
         * Tipo de relatório contracheque
         * Retorna false caso der erro na consulta
         */
        $iTipoRelatorio = $oDaoCfPess->buscaCodigoRelatorio('contracheque', $iAnoFolha, $iMesFolha);

        if(!$iTipoRelatorio) {
           throw new \Exception('db_erros.php?fechar=true&db_erro=Modelo de impressão invalido, verifique parametros.');
        }

        $sSql = "
        SELECT *
            FROM db_config
        WHERE codigo = {$iInstituicao}
        ";

        $rsResult = db_query($sSql);
        $oConfig  = db_utils::fieldsMemory($rsResult, 0);

        $sTipo         = "'x'";
        $sWhereSemest  = '';
        $lNovaRotina   = false;

        switch ($oGet->sOpcao) {

        case 'salario':
            $sPrefix  = 'r14';
            $sArquivo = 'gerfsal';
            $sTitulo  = 'SALÁRIO';
            $iTipoFolha = FolhaPagamento::TIPO_FOLHA_SALARIO;

            /**
             * Controla se o salário é da nova
             * rotina.
             */
            if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
            
            $lNovaRotina = true;
            $sWhereSemest  = 'AND rh141_codigo = 0';
            }
            break;

        case 'ferias':
            $sPrefix  = 'r31';
            $sArquivo = 'gerffer';
            $sTitulo  = 'FÉRIAS';
            $sTipo    = 'r31_tpp';
            break;

        case 'rescisao':
            $sPrefix  = 'r20';
            $sArquivo = 'gerfres';
            $sTitulo  = 'RESCISÃO';
            $sTipo    = 'r20_tpp';
            break;

        case 'adiantamento':
            $sPrefix  = 'r22';
            $sArquivo = 'gerfadi';
            $sTitulo  = 'ADIANTAMENTO';
            break;

        case '13salario':
            $sPrefix  = 'r35';
            $sArquivo = 'gerfs13';
            $sTitulo  = '13o. SALÁRIO';
            break;

        case 'complementar':
            $sPrefix  = 'r48';
            $sArquivo = 'gerfcom';
            $sTitulo  = "COMPLEMENTAR {$oGet->iSemest}";
            $iTipoFolha     = FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR;
            break;

        case 'fixo':
            $sPrefix  = 'r53';
            $sArquivo = 'gerffx';
            $sTitulo  = 'FIXO';
            break;

        case 'previden':
            $sPrefix  = 'r60';
            $sArquivo = 'previden';
            $sTitulo  = 'AJUSTE DA PREVIDÊNCIA';
            break;

        case 'irf':
            $sPrefix  = 'r61';
            $sArquivo = 'ajusteir';
            $sTitulo  = 'AJUSTE DO IRRF';
            break;

        case 'suplementar':
            $sPrefix    = 'r14';
            $sArquivo   = 'gerfsal';
            $sTitulo    = "SUPLEMENTAR {$oGet->iSemest}";
            $iTipoFolha = FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR;
            break;
        }

    
    if (!empty($oGet->iSemest) && $oGet->iSemest) {

        if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {

            $sWhereSemest = "AND rh141_codigo = {$oGet->iSemest}";
            $lNovaRotina = true;
            $aWhere[] = "
            rh143_regist IN (
                SELECT rh143_regist
                FROM rhfolhapagamento
                    INNER JOIN rhhistoricocalculo ON rh143_folhapagamento = rh141_sequencial
                WHERE rh141_mesusu         = {$oGet->iMes}
                AND rh141_anousu         = {$oGet->iAno}
                AND rh141_instit         = {$iInstituicao}
                AND rh141_codigo         = {$oGet->iSemest}
                AND rh141_tipofolha      = {$iTipoFolha}
            )
            ";
        } else {

            $sWhereSemest = " AND r48_semest = {$oGet->iSemest}";
            $aWhere[]     = "r48_semest = {$oGet->iSemest}";
        }
    }
    if ($oGet->sFiltro != 'N') {

    switch ($oGet->sFiltro) {
        case 'M':
        $sCampo = "{$sPrefix}_regist";
        if ($lNovaRotina) {
            $sCampo = "rh143_regist";
        }
        break;

        case 'L':
        $sCampo = "{$sPrefix}_lotac::integer";
        if ($lNovaRotina) {
            $sCampo = "rh01_lotac::integer";
        }
        break;

        case 'T':
        $sCampo = 'rh56_localtrab';
        break;
    }

    if (isset($oGet->sLista) && !empty($oGet->sLista)) {
        $aWhere[] = "{$sCampo} IN ($oGet->sLista)";
    } elseif (isset($oGet->iCodIni) &&
                isset($oGet->iCodFim) &&
            !empty($oGet->iCodIni) &&
            !empty($oGet->iCodFim)) {
        $aWhere[] = "{$sCampo} BETWEEN {$oGet->iCodIni} AND {$oGet->iCodFim}";
    }
    }    

    $sWhere   = '';
    if (isset($aWhere)) {
    $sWhere = 'WHERE ' . implode(' AND ', $aWhere);
    }

    /**
     * Inicio de rotina parametrizada.
     */
    if (DBPessoal::verificarUtilizacaoEstruturaSuplementar() && $lNovaRotina) {

    $sSql = "
        SELECT DISTINCT z01_nome                AS nome,
                        r70_descr               AS lotacao,
                        rh37_descr               AS cargo,
                        rh04_descr               AS funcao,
                        rh44_codban              AS banco,
                        rh44_conta               AS conta,
                        rh44_agencia             AS agencia,
                        rh44_dvconta             AS dvconta,   /* Favor atualizar a documentacao. */
                        rh44_dvagencia           AS dvagencia, /* Favor atualizar a documentacao. */
                        rh01_admiss              AS admissao,
                        rh56_localtrab           AS localtrabalho,
                    rh143_regist              AS matricula,
                        substr(r70_estrut, 1, 7) AS estrutural,
                        substr(
                        db_fxxx(rh143_regist,
                                {$oGet->iAno},
                                {$oGet->iMes},
                                {$iInstituicao}), 111, 11
                        )                        AS salario, /* F010: Salário base com progreção. */
                        substr(
                        db_fxxx(rh143_regist,
                                {$oGet->iAno},
                                {$oGet->iMes},
                                {$iInstituicao}), 222, 8
                        )                        AS padrao
        FROM (
            SELECT DISTINCT rh143_regist,
                            rh141_anousu,
                            rh141_mesusu
            FROM rhhistoricocalculo
                INNER JOIN rhfolhapagamento ON rh143_folhapagamento = rh141_sequencial
                INNER JOIN rhpessoalmov     ON rh02_anousu          = rh141_anousu
                                        AND rh02_mesusu          = rh141_mesusu
                                        AND rh02_instit          = rh141_instit
                                        AND rh02_regist          = rh143_regist
            WHERE rh141_anousu    = {$oGet->iAno}
                AND rh141_mesusu    = {$oGet->iMes}
                AND rh141_instit    = {$iInstituicao}
                AND rh141_tipofolha = {$iTipoFolha}
            )                                    AS rhhistoricocalculo
            INNER JOIN rhpessoal       ON rh01_regist = rh143_regist 
            INNER JOIN rhpessoalmov    ON rh02_regist =  rh01_regist
                                    AND rh02_anousu = {$oGet->iAno}
                                    AND rh02_mesusu = {$oGet->iMes}
                                    AND rh02_instit = {$iInstituicao}
            INNER JOIN rhregime        ON rh02_codreg =  rh30_codreg
                                    AND rh02_instit =  rh30_instit
            INNER JOIN cgm             ON rh01_numcgm =   z01_numcgm
            LEFT  JOIN  rhfuncao       ON rh37_funcao =  rh02_funcao
                                    AND rh37_instit =  rh02_instit
            LEFT  JOIN  rhlota         ON  r70_codigo =  rh02_lota
                                    AND  r70_instit =  rh02_instit
            LEFT  JOIN  rhpescargo     ON rh20_seqpes =  rh02_seqpes
                                    AND rh20_instit =  rh02_instit
            LEFT  JOIN  rhpesbanco     ON rh44_seqpes =  rh02_seqpes
            LEFT  JOIN  rhcargo        ON rh04_codigo =  rh20_cargo
                                    AND rh04_instit =  rh02_instit
            LEFT  JOIN  rhpeslocaltrab ON rh56_seqpes =  rh02_seqpes
                                    AND rh56_princ  =  true
        {$sWhere}
    ";
    } else {

    $sWhereAuxiliar = bb_condicaosubpesproc(
        $sPrefix . '_',
        $oGet->iAno . "/" . $oGet->iMes
    ) . $sWhereSemest;

    $sSql = "
        SELECT DISTINCT z01_nome                 AS nome,
                        r70_descr                AS lotacao,
                        rh37_descr               AS cargo,
                        rh04_descr               AS funcao,
                        rh44_codban              AS banco,
                        rh44_agencia             AS agencia,
                        rh44_conta               AS conta,
                        rh44_dvagencia           AS dvagencia, /* Favor atualizar a documentacao. */
                        rh44_dvconta             AS dvconta,   /* Favor atualizar a documentacao. */
                        rh01_admiss              AS admissao,
                        rh56_localtrab           AS localtrabalho,
                {$sPrefix}_regist              AS matricula,
                        substr(r70_estrut, 1, 7) AS estrutural,
                        substr(
                        db_fxxx({$sPrefix}_regist,
                                {$oGet->iAno},
                                {$oGet->iMes},
                                {$iInstituicao}), 111, 11
                        )                        AS salario, /* F010: Salário base com progreção. */
                        substr(
                        db_fxxx({$sPrefix}_regist,
                                {$oGet->iAno},
                                {$oGet->iMes},
                                {$iInstituicao}), 222, 8
                        )                        AS padrao
        FROM (
            SELECT DISTINCT {$sPrefix}_regist,
                            {$sPrefix}_anousu,
                            {$sPrefix}_mesusu,
                            {$sPrefix}_lotac
            FROM {$sArquivo}
            {$sWhereAuxiliar}
            )                                    AS {$sArquivo}
            INNER JOIN rhpessoal       ON rh01_regist = {$sPrefix}_regist 
            INNER JOIN rhpessoalmov    ON rh02_regist =       rh01_regist
                                    AND rh02_anousu =      {$oGet->iAno}
                                    AND rh02_mesusu =      {$oGet->iMes}
                                    AND rh02_instit =      {$iInstituicao}
            INNER JOIN rhregime        ON rh02_codreg =       rh30_codreg
                                    AND rh02_instit =       rh30_instit
            INNER JOIN cgm             ON rh01_numcgm =        z01_numcgm
            LEFT  JOIN  rhfuncao       ON rh37_funcao =       rh02_funcao
                                    AND rh37_instit =       rh02_instit
            LEFT  JOIN  rhlota         ON  r70_codigo =       rh02_lota
                                    AND  r70_instit =       rh02_instit 
            LEFT  JOIN  rhpescargo     ON rh20_seqpes =       rh02_seqpes
                                    AND rh20_instit =       rh02_instit
            LEFT  JOIN  rhpesbanco     ON rh44_seqpes =       rh02_seqpes
            LEFT  JOIN  rhcargo        ON rh04_codigo =       rh20_cargo
                                    AND rh04_instit =       rh02_instit
            LEFT  JOIN  rhpeslocaltrab ON rh56_seqpes =       rh02_seqpes
                                    AND rh56_princ  =       true
        {$sWhere}
    ";
    }



    $sSql = "
    SELECT *
        FROM(
        {$sSql}
        ) AS xxx, generate_series(1, $oGet->iNumVias)
    ORDER BY
    ";
    switch ($oGet->sOrdem) {
    case 'L':
        $sSql .= " estrutural, nome, matricula";
        break;

    case 'N':
        $sSql .= " nome, matricula";
        break;

    case 'T':
        $sSql .= " localtrabalho, nome, matricula";
        break;

    default:
        $sSql .= " matricula";
        break;
    }

    /**
     * busca URL do cliente.
     */
    $sSqlUrl = "
    SELECT url
        FROM db_config
    WHERE prefeitura = true
    ";
    
    $rsResult = db_query($sSqlUrl);
    if (pg_num_rows($rsResult)) {
    $oUrl = db_utils::fieldsMemory($rsResult, 0);
    $sUrl = $oUrl->url;
    } else {
    $sUrl = ' ';
    }

    $rsResult = db_query($sSql);
    $iNumRow  = pg_num_rows($rsResult);

    /**
     * Se n?o trazer nenhum registro.
     */
    if (!$iNumRow) {
    throw new \Exception("db_erros.php?fechar=true&db_erro=Não existe Cálculo no período de {$oGet->iMes}/{$oGet->iAno}.");
    }

    /**
     * Grrrrrr... Globals
     */
    global $pdf;
    $pdf = new scpdf();
    $pdf->setautopagebreak(false, 0.05);
    $pdf->Open();

    $oPDF = new db_impcarne($pdf, $iTipoRelatorio);
    $oPDF->logo             = $oConfig->logo;
    $oPDF->prefeitura       = $oConfig->nomeinst;
    $oPDF->enderpref        = $oConfig->ender . ( isset($oConfig->numero ) ? (", {$oConfig->numero}"): "");
    $oPDF->cgcpref          = $oConfig->cgc;
    $oPDF->municpref        = $oConfig->munic;
    $oPDF->telefpref        = $oConfig->telef;
    $oPDF->emailpref        = $oConfig->email;
    $oPDF->ano              = $oGet->iAno;
    $oPDF->mes              = $oGet->iMes;
    $oPDF->mensagem         = utf8_decode($oGet->sMensagem);
    $oPDF->qualarquivo      = $sTitulo;

    $lin = 1;

    $aServidores = db_utils::getCollectionByRecord($rsResult);
    foreach ($aServidores as $iIndex => $oServidor) {

    $rsResult         = db_query("SELECT nextval('rhemitecontracheque_rh85_sequencial_seq') AS sequencial");
    $oSeqContraCheque = db_utils::fieldsMemory($rsResult, 0);
    $iSequencial      = str_pad($oSeqContraCheque->sequencial, 6, '0', STR_PAD_LEFT);

    $iMes             = str_pad($oGet->iMes, 2, '0', STR_PAD_LEFT);
    $iMatricula       = str_pad($oServidor->matricula, 6, '0', STR_PAD_LEFT);
    $iMod1            = db_CalculaDV($iMatricula);
    $iMod2            = db_CalculaDV($iMatricula . $iMod1 . $iMes . $oGet->iAno . $iSequencial);

    $iCodAutent       = $iMatricula . $iMod1 . $iMes . $iMod2 . $oGet->iAno . $iSequencial;
    $sDataEmissao     = date('Y-m-d', db_getsession('DB_datausu'));
    $sHoraEmissao     = db_hora();
    $sIpEmissao       = db_getsession('DB_ip');

    $oDaoEmiteContraCheque->rh85_sequencial  = $iSequencial;
    $oDaoEmiteContraCheque->rh85_regist      = $oServidor->matricula;
    $oDaoEmiteContraCheque->rh85_anousu      = $oGet->iAno;
    $oDaoEmiteContraCheque->rh85_mesusu      = $oGet->iMes;
    $oDaoEmiteContraCheque->rh85_sigla       = $sPrefix;
    $oDaoEmiteContraCheque->rh85_codautent   = $iCodAutent;
    $oDaoEmiteContraCheque->rh85_dataemissao = $sDataEmissao;
    $oDaoEmiteContraCheque->rh85_horaemissao = $sHoraEmissao;
    $oDaoEmiteContraCheque->rh85_ip          = $sIpEmissao;
    $oDaoEmiteContraCheque->rh85_externo     = 'false';

    $oDaoEmiteContraCheque->incluir($iSequencial);
    if (!$oDaoEmiteContraCheque->erro_status) {
        db_redireciona("db_erros.php?fechar=true&db_erro={$clrhemitecontracheque->erro_msg}");
    }

    if ($iIndex %2 == 0) {
        $oPDF->seq = 0;
    } else {
        $oPDF->seq = 1;
    }

    if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()  && $lNovaRotina) {

        $sSql = "
        SELECT rh143_rubrica              AS rubrica,
                round(rh143_valor,      2) AS valor,
                round(rh143_quantidade, 2) AS quantidade,
                rh27_descr                 AS descricao,
                {$sTipo}                   AS tipo,
                CASE WHEN rh143_tipoevento  = 1 THEN 'P'
                    WHEN rh143_tipoevento  = 2 THEN 'D'
                    ELSE 'B'
                END                        AS tipoevento
            FROM rhhistoricocalculo
            INNER JOIN rhfolhapagamento ON rh143_folhapagamento = rh141_sequencial
            INNER JOIN rhrubricas       ON rh143_rubrica        = rh27_rubric
                                        AND rh141_instit         = rh27_instit
        WHERE rh143_regist    = {$oServidor->matricula}
            AND rh141_anousu    = {$oGet->iAno}
            AND rh141_mesusu    = {$oGet->iMes}
            AND rh141_instit    = {$iInstituicao}
            AND rh141_tipofolha = {$iTipoFolha}
            {$sWhereSemest}
        ORDER BY rh143_rubrica
        ";
    } else {

        $sSql = "
        SELECT {$sPrefix}_rubric          AS rubrica,
                round({$sPrefix}_valor, 2) AS valor,
                round({$sPrefix}_quant, 2) AS quantidade,
                rh27_descr                 AS descricao,
                {$sTipo}                   AS tipo,
                CASE WHEN {$sPrefix}_pd = 1 THEN 'P'
                    WHEN {$sPrefix}_pd = 2 THEN 'D'
                    ELSE 'B'
                END                        AS tipoevento
            FROM {$sArquivo}
            INNER JOIN rhrubricas ON rh27_rubric = {$sPrefix}_rubric
                                AND rh27_instit = {$iInstituicao}
        WHERE {$sPrefix}_regist = {$oServidor->matricula}
            AND {$sPrefix}_anousu = {$oGet->iAno}
            AND {$sPrefix}_mesusu = {$oGet->iMes}
            AND {$sPrefix}_instit = {$iInstituicao}
            {$sWhereSemest}
        ORDER BY {$sPrefix}_rubric
        ";
    }

    $rsResult = db_query($sSql);

    $oPDF->registro         = $oServidor->matricula;
    $oPDF->admissao         = db_formatar($oServidor->admissao, 'd');
    $oPDF->nome             = $oServidor->nome;
    $oPDF->descr_funcao     = $oServidor->funcao;
    $oPDF->descr_lota       = "{$oServidor->estrutural}-{$oServidor->lotacao}";
    $oPDF->f010             = $oServidor->salario;
    $oPDF->padrao           = $oServidor->padrao;
    $oPDF->banco            = $oServidor->banco;
    $oPDF->agencia          = "{$oServidor->agencia}-{$oServidor->dvagencia}";
    $oPDF->conta            = "{$oServidor->conta}-{$oServidor->dvconta}";
    $oPDF->lotacao_idade    = 'quantidade';
    $oPDF->tipo             = 'tipoevento';
    $oPDF->rubrica          = 'rubrica';
    $oPDF->descr_rub        = 'descricao';
    $oPDF->numero           = $iIndex + 1;
    $oPDF->total            = $iNumRow;
    $oPDF->codautent        = $iCodAutent;
    $oPDF->url              = $sUrl;
    $oPDF->estrutural         = $oServidor->estrutural;
    $oPDF->recordenvelope   = $rsResult;
    $oPDF->linhasenvelope   = pg_num_rows($rsResult);
    $oPDF->valor            = 'valor';
    $oPDF->quantidade       = 'quantidade';
    $oPDF->tipo             = 'tipoevento';
    $oPDF->rubrica          = 'rubrica';
    $oPDF->descr_rub        = 'descricao';
    $oPDF->numero           = $iIndex + 1;
    $oPDF->total            = $iNumRow;
    $oPDF->codautent        = $iCodAutent;
    $oPDF->url              = $sUrl;
    $oPDF->imprime();
    }
    $nomeArquivo = '/tmp/comprovante_rendimentos.pdf';
    $oPDF->objpdf->output($nomeArquivo);
    $retorno = new \stdClass();
    $retorno->arquivo = base64_encode(file_get_contents($nomeArquivo));
    return $retorno;
  }
}
