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
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_libdocumento.php");
require_once modification("fpdf151/scpdf.php");

try {

  define("MSG", "saude.ambulatorial.sau2_declaracaocomprovante.");

  $oGet    = db_utils::postMemory($_GET);
  $oUPS    = new UnidadeProntoSocorro(db_getsession('DB_coddepto'));

  $oDados                         = new stdClass();
  $oDados->sEstado                = null;
  $oDados->sUPS                   = $oUPS->getDepartamento()->getNomeDepartamento();
  $oDados->sUF                    = $oUPS->getDepartamento()->getInstituicao()->getUf();
  $oDados->sMunicipio             = $oUPS->getDepartamento()->getInstituicao()->getMunicipio();
  $oDados->sHorarioComparecimento = "";
  $oDados->sHoraFinal             = !isset($oGet->sHoraFinal)   || !$oGet->sHoraFinal   ? null : $oGet->sHoraFinal;
  $oDados->sHoraInicial           = !isset($oGet->sHoraInicial) || !$oGet->sHoraInicial ? null : $oGet->sHoraInicial;
  $oDados->sObservacao            = !isset($oGet->sObservacao)  || !$oGet->sObservacao  ? null : $oGet->sObservacao;
  $oDados->dtAtendimento          = "";
  $oDados->sNomePaciente          = "";

  if (!$oDados->sHoraInicial xor !$oDados->sHoraFinal) {
    throw new \BusinessException( _M( MSG . "informe_hora" ) );
  }

  if($oDados->sHoraInicial) {

    $oDados->sHorarioComparecimento = _M( 
      MSG . "intervalo_comparecimento", 
      (object)array(
        "hora_inicial" => $oDados->sHoraInicial, 
        "hora_final"   => $oDados->sHoraFinal
      ) 
    );
  }

  if ( empty($oGet->iProntuario) ) {
    throw new Exception( _M( MSG . "informe_ficha_atendimento" ) );
  }

  $oProntuario = new Prontuario($oGet->iProntuario);

  if (!$oCGS = $oProntuario->getCGS()) {
    throw new BusinessException(MSG . "ficha_de_atendimento_sem_cgs");
  }
  $oDados->sNomePaciente = $oCGS->getNome();
  $oDados->sCPF          = $oCGS->getCpf();
  $oDados->sCNS          = $oCGS->getCartaoSusAtivo();
  $oDados->dtAtendimento = $oProntuario->getDataAtendimento()->getDate( DBDate::DATA_PTBR );

  $DAOEstado = new cl_cadenderestado();
  $sWhere    = "db71_sigla = '" . $oDados->sUF . "'";
  $sQuery    = $DAOEstado->sql_query_file(null, "db71_descricao", null, $sWhere);
  $rsEstado  = db_query($sQuery);

  $oMsgErro  = new stdClass();
  if (!$rsEstado) {

    $oMsgErro->sErro = pg_last_error();
    throw new DBException( _M( MSG . "erro_buscar_estado", $oMsgErro ) );
  }

  if ( pg_num_rows($rsEstado) == 0 ) {

    $oMsgErro->sErro = $oDados->sUF;
    throw new DBException( _M( MSG . "estado_nao_encontrado", $oMsgErro ) );
  }

  $oDados->sEstado = db_utils::fieldsMemory($rsEstado, 0)->db71_descricao;

  $sWhere     = " sd27_i_codigo in ( select s104_i_profissional from prontprofatend where s104_i_prontuario = {$oGet->iProntuario}) ";
  $sCampos    = " z01_nome, sd04_v_registroconselho, rh70_estrutural, rh70_descr, sd51_v_descricao ";
  $oDaoMedico = new cl_medicos();
  $sSqlMedico = $oDaoMedico->sql_query_vinculos(null, $sCampos, null, $sWhere);
  $rsMedico   = db_query($sSqlMedico);

  if ( !$rsMedico ) {

    $oMsgErro->sErro = pg_last_error();
    throw new DBException(_M( MSG . "erro_buscar_dados_medico", $oMsgErro ));
  }

  if ( pg_num_rows($rsMedico) == 0 ) {
    throw new DBException(_M( MSG . "dados_medicos_nao_encontrados"));
  }

  $oData = null;

  $oDadosMedico          = db_utils::fieldsMemory($rsMedico, 0);
  $oDados->sMedico       = $oDadosMedico->z01_nome;
  $oDados->iDocumentos   = $oDadosMedico->sd04_v_registroconselho;
  $oDados->sOrgaoEmissor = $oDadosMedico->sd51_v_descricao;
  $oDados->iCBO          = $oDadosMedico->rh70_estrutural;
  $oDados->sCBO          = $oDadosMedico->rh70_descr;
  $oDados->sDataExtenso  = "{$oDados->sMunicipio}-{$oDados->sUF}";

  if ($oDados->dtAtendimento) {

    $oData = new DBDate($oDados->dtAtendimento);
    $oDados->sDataExtenso .= ', ' . $oData->dataPorExtenso();
  }

  /**
   * 5025 - ATESTADO MÉDICO
   *
   * ------------ Variáveis ---------------
   * #nomePaciente
   * #nomeUnidade 
   * #dataAtendimento 
   * #horario 
   * #observacao
   * ---------------------------------------
   */
  $oLibDocumento                  = new libdocumento(5025,null);
  $oLibDocumento->nomePaciente    = $oDados->sNomePaciente;
  $oLibDocumento->nomeUnidade     = $oDados->sUPS;
  $oLibDocumento->dataAtendimento = $oDados->dtAtendimento;
  $oLibDocumento->horario         = $oDados->sHorarioComparecimento;

  if( !empty( $oDados->sObservacao ) ) {
    $oLibDocumento->observacao = utf8_decode("\n\nObs.: " . $oDados->sObservacao);
  }

  $oDados->aParagrafos = $oLibDocumento->getDocParagrafos();

  $oPdf = new scpdf();

  $oPdf->Open();
  $oPdf->setMargins(10, 8);
  $oPdf->SetFont('Arial','B',8);

  $oPdf->addPage();
  $oPdf->Cell(0, 3.2, "MINISTÉRIO DA SAÚDE", 0, 1);
  $oPdf->Cell(0, 3.2, "ESTADO DE {$oDados->sEstado}", 0, 1);
  $oPdf->Cell(0, 3.2, "MUNICÍPIO DE {$oDados->sMunicipio}", 0, 1);
  $oPdf->Cell(0, 3.2, "{$oDados->sUPS}", 0, 1);
  $oPdf->Line(10, 22, 195, 22);

  /**
   * cabeçalho
   */
  $oPdf->SetY(40);
  $oPdf->SetFont('Arial', 'B', 15);
  $oPdf->Cell(0, 5, $oDados->aParagrafos[1]->oParag->db02_texto, 0, 1, 'C');

  /**
   * texto
   */
  $oPdf->SetY( $oPdf->GetY() + 15);
  $oPdf->SetFont('Arial', '', 10);
  $oPdf->MultiCell(0, 5, $oDados->aParagrafos[2]->oParag->db02_texto, 0, 'J');


  /**
   * data
   */
  $oPdf->SetY( $oPdf->GetY() + 30);
  $oPdf->Cell(0, 5, $oDados->sDataExtenso, 0, 1, 'R');

  /**
   * assinatura
   */

  $oPdf->SetY( $oPdf->GetY() + 15);
  $oPdf->SetFont('Arial', '', 8);

  $iPosicaoLinhaAssinatura =  $oPdf->w / 3;
  $oPdf->Line($iPosicaoLinhaAssinatura, $oPdf->GetY(), $iPosicaoLinhaAssinatura * 2, $oPdf->GetY());
  $oPdf->Cell(0, 4, $oDados->sMedico, 0, 1, 'C');
  $oPdf->Cell(0, 4, "{$oDados->sCBO}", 0, 1, 'C');
  $oPdf->Cell(0, 4, "{$oDados->sOrgaoEmissor} {$oDados->iDocumentos}", 0, 1, 'C');
  $oPdf->Output();

} catch (\Exception $oErro) {

  $sErro = str_replace("\n", "",  $oErro->getMessage() );
  db_redireciona('db_erros.php?fechar=true&db_erro='.$sErro);
}
