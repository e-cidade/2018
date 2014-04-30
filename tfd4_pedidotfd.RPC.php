<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once('libs/db_stdlib.php');
require_once('libs/db_utils.php');
require_once('libs/db_conecta.php');
require_once('libs/db_sessoes.php');
require_once('libs/JSON.php');
require_once('dbforms/db_funcoes.php');
require_once('libs/db_stdlibwebseller.php');

function formataData($dData, $iTipo = 1) {

  if(empty($dData)) {
    return '';
  }

  if($iTipo == 1) {

    $dData = explode('/',$dData);
    $dData = $dData[2].'-'.$dData[1].'-'.$dData[0];
    return $dData;
  
  }
 
 $dData = explode('-',$dData);
 $dData = @$dData[2].'/'.@$dData[1].'/'.@$dData[0];

 return $dData;

}

$oJson               = new services_json();
$oParam              = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMessage  = '';

if ($oParam->exec == 'getCgsCns') {

  $oDaoCgsCartaoSus = db_utils::getdao('cgs_cartaosus');
  
  $sSql             = $oDaoCgsCartaoSus->sql_query(null, 'z01_i_cgsund, z01_v_nome', 
                                                   null, ' s115_c_cartaosus = \''.$oParam->iCns.'\''
                                                  );
  $rsCgsCartaoSus   = $oDaoCgsCartaoSus->sql_record($sSql);
  
  if ($oDaoCgsCartaoSus->numrows > 0) { // se encontrou o cgs

    $oDadosCgsCartaoSus     = db_utils::fieldsmemory($rsCgsCartaoSus, 0);
    $oRetorno->z01_i_cgsund = $oDadosCgsCartaoSus->z01_i_cgsund;
    $oRetorno->z01_v_nome   = urlencode($oDadosCgsCartaoSus->z01_v_nome);

  } else {
 
    $oRetorno->z01_i_cgsund = '';
    $oRetorno->z01_v_nome   = '';

  }

}

elseif ($oParam->exec == 'getInfoCgs') {
  
  $oDaoCgsUnd       = db_utils::getdao('cgs_und');
  $oDaoCgsCartaoSus = db_utils::getdao('cgs_cartaosus');
  
  $sSql     = $oDaoCgsUnd->sql_query($oParam->iCgs);
  $rsCgsUnd = $oDaoCgsUnd->sql_record($sSql);
  
  $oRetorno->z01_i_cgsund = $oParam->iCgs;
  if ($oDaoCgsUnd->numrows > 0) { // se encontrou o cgs

    $oDadosCgsUnd           = db_utils::fieldsmemory($rsCgsUnd, 0);
    $oRetorno->z01_v_ender  = urlencode($oDadosCgsUnd->z01_v_ender);
    $oRetorno->z01_v_bairro = urlencode($oDadosCgsUnd->z01_v_bairro);
    $oRetorno->z01_v_munic  = urlencode($oDadosCgsUnd->z01_v_munic);
    $oRetorno->z01_v_cep    = urlencode($oDadosCgsUnd->z01_v_cep);
    $oRetorno->z01_v_uf     = urlencode($oDadosCgsUnd->z01_v_uf);
    $oRetorno->z01_i_numero = $oDadosCgsUnd->z01_i_numero;
    $oRetorno->z01_v_sexo   = urlencode($oDadosCgsUnd->z01_v_sexo);
    $oRetorno->z01_v_compl  = urlencode($oDadosCgsUnd->z01_v_compl);
    $oRetorno->z01_v_email  = urlencode($oDadosCgsUnd->z01_v_email);
    $oRetorno->z01_v_telef  = urlencode($oDadosCgsUnd->z01_v_telef);
    $oRetorno->z01_v_telcel = urlencode($oDadosCgsUnd->z01_v_telcel);
    $oRetorno->z01_d_nasc   = $oDadosCgsUnd->z01_d_nasc;
    $oRetorno->z01_v_cgccpf = urlencode($oDadosCgsUnd->z01_v_cgccpf);
    $oRetorno->z01_v_ident  = urlencode($oDadosCgsUnd->z01_v_ident);
    $oRetorno->z01_v_mae    = urlencode($oDadosCgsUnd->z01_v_mae);
    $oRetorno->z01_v_pai    = urlencode($oDadosCgsUnd->z01_v_pai);
    $oRetorno->z01_v_nome   = urlencode($oDadosCgsUnd->z01_v_nome);

  } else {

    $oRetorno->z01_v_ender  = ''; 
    $oRetorno->z01_v_bairro = '';
    $oRetorno->z01_v_munic  = '';
    $oRetorno->z01_v_cep    = '';
    $oRetorno->z01_v_uf     = '';
    $oRetorno->z01_v_email  = '';
    $oRetorno->z01_v_sexo   = '';
    $oRetorno->z01_v_telef  = '';
    $oRetorno->z01_i_numero = '';
    $oRetorno->z01_v_compl  = '';
    $oRetorno->z01_v_telcel = '';
    $oRetorno->z01_d_nasc   = '';
    $oRetorno->z01_v_cgccpf = '';
    $oRetorno->z01_v_ident  = '';
    $oRetorno->z01_v_mae    = '';
    $oRetorno->z01_v_pai    = '';

  }

  /* pega o cartão sus */
  $sSql           = $oDaoCgsCartaoSus->sql_query(null, ' s115_c_cartaosus, s115_c_tipo, s115_i_codigo ',
                                                 ' s115_c_tipo asc ', ' s115_i_cgs = '.$oParam->iCgs
                                                );
  $rsCgsCartaoSus = $oDaoCgsCartaoSus->sql_record($sSql);

  if ($oDaoCgsCartaoSus->numrows != 0) { // se o paciente tem um cartao sus

    $oDadosCgsCartaoSus         = db_utils::fieldsmemory($rsCgsCartaoSus, 0);
    $oRetorno->s115_c_cartaosus = urlencode($oDadosCgsCartaoSus->s115_c_cartaosus);
    $oRetorno->s115_c_tipo      = urlencode($oDadosCgsCartaoSus->s115_c_tipo);
    $oRetorno->s115_i_codigo    = urlencode($oDadosCgsCartaoSus->s115_i_codigo);

  } else {

    $oRetorno->s115_c_cartaosus = '';
    $oRetorno->s115_c_tipo      = '';
    $oRetorno->s115_i_codigo    = '';

  }

}

elseif ($oParam->exec == 'atualizarCgs') {
 
  db_inicio_transacao();
  $oDaoCgsUnd       = db_utils::getdao('cgs_und');
  $oDaoCgsCartaoSus = db_utils::getdao('cgs_cartaosus');

  if (empty($oParam->iCgs) || $oParam->iCgs == 'undefined') {

   $oRetorno->iStatus  = 2;
   $oRetorno->sMessage = urlencode('Código CGS não encontrado para ser atualizado.');
   echo $oJson->encode($oRetorno);
   exit;

  }
  if (!empty($oParam->z01_d_nasc)) {

    $oParam->z01_d_nasc = explode('/', $oParam->z01_d_nasc);
    $oParam->z01_d_nasc = $oParam->z01_d_nasc[2].'-'.$oParam->z01_d_nasc[1].'-'.$oParam->z01_d_nasc[0];

  }
  if (!empty($oParam->z01_i_numero) && !empty($oParam->z01_v_compl)) {
    
    $oDaoCgsUnd->z01_v_compl  = $oParam->z01_v_compl;
    $oDaoCgsUnd->z01_i_numero = $oParam->z01_i_numero;
    
  }
  $oDaoCgsUnd->z01_v_ender  = $oParam->z01_v_ender;
  $oDaoCgsUnd->z01_v_bairro = $oParam->z01_v_bairro;
  $oDaoCgsUnd->z01_v_munic  = $oParam->z01_v_munic; 
  $oDaoCgsUnd->z01_v_cep    = $oParam->z01_v_cep;
  $oDaoCgsUnd->z01_v_uf     = $oParam->z01_v_uf;
  $oDaoCgsUnd->z01_v_email  = $oParam->z01_v_email; 
  $oDaoCgsUnd->z01_v_sexo   = $oParam->z01_v_sexo; 
  $oDaoCgsUnd->z01_v_telef  = $oParam->z01_v_telef; 
  $oDaoCgsUnd->z01_v_telcel = $oParam->z01_v_telcel;
  $oDaoCgsUnd->z01_d_nasc   = $oParam->z01_d_nasc;
  $oDaoCgsUnd->z01_v_cgccpf = $oParam->z01_v_cgccpf;
  $oDaoCgsUnd->z01_v_ident  = $oParam->z01_v_ident; 
  $oDaoCgsUnd->z01_v_mae    = $oParam->z01_v_mae;
  $oDaoCgsUnd->z01_v_pai    = $oParam->z01_v_pai;
  $oDaoCgsUnd->z01_i_cgsund = $oParam->iCgs;

  $oDaoCgsUnd->alterar($oParam->iCgs);

  /* atualiza o cartão sus */
  if (!empty($oParam->s115_c_cartaosus)) {
    
    if ($oParam->s115_c_tipo == 'P') {

      if (validaCnsProvisorio($oParam->s115_c_cartaosus)) {

        $lRepetido      = false;
        $sSql           = $oDaoCgsCartaoSus->sql_query(null, ' s115_i_codigo, s115_c_cartaosus ', '',
                                                       ' s115_i_cgs = '.$oParam->iCgs.' and s115_c_tipo = \'P\''
                                                      );
        $rsCgsCartaoSus = $oDaoCgsCartaoSus->sql_record($sSql);

        // se o paciente já tem cartão sus provisório, então, verifico se o número do cns já existe
        if ($oDaoCgsCartaoSus->numrows > 0) { 
        
          for ($iCont = 0; $iCont < $oDaoCgsCartaoSus->numrows; $iCont++) {

            $oDadosCgsCartaoSus = db_utils::fieldsmemory($rsCgsCartaoSus, $iCont);
            if ($oParam->s115_c_cartaosus == $oDadosCgsCartaoSus->s115_c_cartaosus) { // se o cartão sus é repetido
            
              $lRepetido = true;
              break;

            }

          }

        }
      
        if (!$lRepetido) { // só inclui o provisório, se o número passado ainda não foi incluído

          $rsCgsCartaoSus                     = $oDaoCgsCartaoSus->sql_record($sSql);
          $oDaoCgsCartaoSus->s115_i_entrada   = 1; // a entrada é manual
          $oDaoCgsCartaoSus->s115_c_tipo      = 'P';
          $oDaoCgsCartaoSus->s115_c_cartaosus = $oParam->s115_c_cartaosus;
          $oDaoCgsCartaoSus->s115_i_cgs       = $oParam->iCgs;
          $oDaoCgsCartaoSus->incluir(null);

        }

      } else {
 
        $oDaoCgsCartaoSus->erro_status = '0';
        $oDaoCgsCartaoSus->erro_msg    = 'Número de cartão sus inválido.';

      }

    } else {
  
      if (validaCnsDefinitivo($oParam->s115_c_cartaosus)) {

        $sSql           = $oDaoCgsCartaoSus->sql_query(null, ' s115_i_codigo, s115_c_cartaosus ', '',
                                                       ' s115_i_cgs = '.$oParam->iCgs.
                                                       ' and s115_c_tipo = \'D\' limit 1'
                                                      );
        $rsCgsCartaoSus = $oDaoCgsCartaoSus->sql_record($sSql);

        if ($oDaoCgsCartaoSus->numrows > 0) { // se o paciente já tem um cartao sus definitivo, então, só altero ele

          $oDadosCgsCartaoSus = db_utils::fieldsmemory($rsCgsCartaoSus, 0);

          // se o cartao sus realmente foi alterado no formulário
          if ($oParam->s115_c_cartaosus != $oDadosCgsCartaoSus->s115_c_cartaosus) { 
   
            $oDaoCgsCartaoSus->s115_i_entrada   = 1; // a entrada é manual
            $oDaoCgsCartaoSus->s115_c_tipo      = 'D';
            $oDaoCgsCartaoSus->s115_c_cartaosus = $oParam->s115_c_cartaosus;
            $oDaoCgsCartaoSus->s115_i_codigo    = $oDadosCgsCartaoSus->s115_i_codigo;
            $oDaoCgsCartaoSus->alterar($oDadosCgsCartaoSus->s115_i_codigo);

          }

        } else {

          $oDaoCgsCartaoSus->s115_i_entrada   = 1; // a entrada é manual
          $oDaoCgsCartaoSus->s115_c_tipo      = 'D';
          $oDaoCgsCartaoSus->s115_c_cartaosus = $oParam->s115_c_cartaosus;
          $oDaoCgsCartaoSus->s115_i_cgs       = $oParam->iCgs;
          $oDaoCgsCartaoSus->incluir(null);

        }

      } else {

        $oDaoCgsCartaoSus->erro_status = '0';
        $oDaoCgsCartaoSus->erro_msg    = 'Número de cartão sus inválido.';

      }

    }

  }
  
  if ($oDaoCgsUnd->erro_status == '0') {

    $oRetorno->iStatus  = 2;
    $oRetorno->sMessage = urlencode($oDaoCgsUnd->erro_msg);

  } else if (!empty($oParam->s115_c_cartaosus) && $oDaoCgsCartaoSus->erro_status == '0') {
    
    $oRetorno->iStatus  = 2;
    $oRetorno->sMessage = urlencode($oDaoCgsCartaoSus->erro_msg);

  }

  db_fim_transacao($oRetorno->iStatus == 2 ? true : false);

}

elseif ($oParam->exec == 'getCgsCns') {
  
  $oDaoCgsUnd = db_utils::getdao('cgs_und_ext');
	$sSql       = $oDaoCgsUnd->sql_query_ext(null, 'z01_i_cgsund', '', " s115_c_cartaosus = '".$oParam->iCns."' ");
  $rsCgsUnd   = $oDaoCgsUnd->sql_record($sSql);
  if ($oDaoCgsUnd->numrows > 0) {

    $oDadosCgsUnd           = db_utils::fieldsmemory($rsCgsUnd, 0);
    $oRetorno->z01_i_cgsund = $oDadosCgsUnd->z01_i_cgsund;

  } else {
    
    if ($oDaoCgsUnd->erro_status == '0') {
      
      $oRetorno->iStatus  = 2;
      $oRetorno->sMessage = urlencode($oDaoCgsUnd->erro_msg);

    } else {

      $oRetorno->iStatus  = 2;
      $oRetorno->sMessage = urlencode('Nenhum CGS encontrado para este CNS.');

    }

  }

}


elseif ($oParam->exec == 'existeDocObrigatorio') {
  
  $oDaoTfdTipoTratamentoDoc = db_utils::getdao('tfd_tipotratamentodoc');
	$sSql                     = $oDaoTfdTipoTratamentoDoc->sql_query_file(null, '*', null, 
                                                                        ' tf06_i_tipotratamento = '.
                                                                         $oParam->iTipoTratamento.
                                                                         ' and tf06_i_obrigatorio = 1 '
                                                                       );
  $rsTfdTipoTratamentoDoc   = $oDaoTfdTipoTratamentoDoc->sql_record($sSql);
  if ($oDaoTfdTipoTratamentoDoc->numrows > 0) {
    $oRetorno->lPossuiDocObrigatorio = true;
  } else {
    $oRetorno->lPossuiDocObrigatorio = false;
  }
    
}

elseif ($oParam->exec == 'getPedidosTfdCgs') {

  $oDaoTfdPedidoTfd = db_utils::getdao('tfd_pedidotfd');

  $sSql             = $oDaoTfdPedidoTfd->sql_query_grid(null, '*', ' tf01_i_codigo desc ', 
                                                        ' tf01_i_cgsund = '.$oParam->iCgs
                                                       );
  $rs               = $oDaoTfdPedidoTfd->sql_record($sSql);

  for ($iCont = 0; $iCont < $oDaoTfdPedidoTfd->numrows; $iCont++) {

    $oDados                                             = db_utils::fieldsmemory($rs, $iCont);
    $oRetorno->oPedidos[$iCont]->tf01_i_codigo          = $oDados->tf01_i_codigo;
    $oRetorno->oPedidos[$iCont]->tf16_d_dataagendamento = $oDados->tf16_d_dataagendamento;
    $oRetorno->oPedidos[$iCont]->tf17_d_datasaida       = $oDados->tf17_d_datasaida;
    $oRetorno->oPedidos[$iCont]->z01_nomeprestadora     = urlencode($oDados->z01_nome);
    $oRetorno->oPedidos[$iCont]->tf03_i_codigo          = $oDados->tf03_i_codigo;
    $oRetorno->oPedidos[$iCont]->tf03_c_descr           = urlencode($oDados->tf03_c_descr);
    $oRetorno->oPedidos[$iCont]->tf26_c_descr           = urlencode($oDados->tf26_c_descr);
    $oRetorno->oPedidos[$iCont]->tf01_d_datapedido      = urlencode($oDados->tf01_d_datapedido);

  }

  if ($oDaoTfdPedidoTfd->numrows == 0) {

    $oRetorno->iStatus  = 2;
    $oRetorno->sMessage = urlencode('CGS não possui pedidos de TFD.');

  }

}

elseif ($oParam->exec == 'getProcedimentosPedidoTfd') {

  $oDaoTfdProcPedidoTfd = db_utils::getdao('tfd_procpedidotfd');
  $sCampos              = ' tf23_i_procedimento, sd63_c_procedimento, sd63_c_nome';
  $sSql                 = $oDaoTfdProcPedidoTfd->sql_query2(null, $sCampos, ' sd63_c_nome ', 
                                                            ' tf23_i_pedidotfd = '.$oParam->iPedido
                                                           );
  $rs                   = $oDaoTfdProcPedidoTfd->sql_record($sSql);

  for ($iCont = 0; $iCont < $oDaoTfdProcPedidoTfd->numrows; $iCont++) {

    $oDados                                                = db_utils::fieldsmemory($rs, $iCont);
    $oRetorno->oProcedimentos[$iCont]->tf23_i_procedimento = $oDados->tf23_i_procedimento;
    $oRetorno->oProcedimentos[$iCont]->sd63_c_procedimento = $oDados->sd63_c_procedimento;
    $oRetorno->oProcedimentos[$iCont]->sd63_c_nome         = urlencode($oDados->sd63_c_nome);

  }

  if ($oDaoTfdProcPedidoTfd->numrows == 0) {

    $oRetorno->iStatus  = 2;
    $oRetorno->sMessage = urlencode('Não foram encontrados procedimentos para este pedido.');

  }

}

elseif ($oParam->exec == 'getAcompanhantesPedidoTfd') {

  $oDaoTfdAcompanhantes = db_utils::getdao('tfd_acompanhantes');
  $sCampos              = ' tf13_i_codigo, cgs_und.z01_i_cgsund, cgs_und.z01_v_nome, cgs_und.z01_v_ident, ';
  $sCampos             .= 'cgs_und.z01_v_cgccpf, tf13_i_anulado ';
  $sSql                 = $oDaoTfdAcompanhantes->sql_query(null, $sCampos, ' tf13_i_codigo ', 
                                                            ' tf13_i_pedidotfd = '.$oParam->iPedido
                                                           );
  $rs                   = $oDaoTfdAcompanhantes->sql_record($sSql);

  for ($iCont = 0; $iCont < $oDaoTfdAcompanhantes->numrows; $iCont++) {

    $oDados                                           = db_utils::fieldsmemory($rs, $iCont);
    $oRetorno->oAcompanhantes[$iCont]->tf13_i_codigo  = $oDados->tf13_i_codigo;
    $oRetorno->oAcompanhantes[$iCont]->tf13_i_anulado = $oDados->tf13_i_anulado;
    $oRetorno->oAcompanhantes[$iCont]->z01_i_cgsund   = $oDados->z01_i_cgsund;
    $oRetorno->oAcompanhantes[$iCont]->z01_v_nome     = urlencode($oDados->z01_v_nome);
    $oRetorno->oAcompanhantes[$iCont]->z01_v_ident    = urlencode($oDados->z01_v_ident);
    $oRetorno->oAcompanhantes[$iCont]->z01_v_cgccpf   = urlencode($oDados->z01_v_cgccpf);

  }

  if ($oDaoTfdAcompanhantes->numrows == 0) {

    $oRetorno->iStatus  = 2;
    $oRetorno->sMessage = urlencode('Não foram encontrados acompanhates neste pedido de TFD.');

  }

}

elseif ($oParam->exec == 'anularAcompanhante') {

  $oDaoTfdAcompanhantes                = db_utils::getdao('tfd_acompanhantes');

  $oDaoTfdAcompanhantes->tf13_i_codigo = $oParam->iCodigo;
  if ($oParam->lAnular) {
    $oDaoTfdAcompanhantes->tf13_i_anulado = 1;
  } else {
    $oDaoTfdAcompanhantes->tf13_i_anulado = 2;
  }

  $oDaoTfdAcompanhantes->alterar($oParam->iCodigo);

  if ($oDaoTfdAcompanhantes->erro_status == '0') {

    $oRetorno->iStatus  = 2;
    $oRetorno->sMessage = urlencode($oDaoTfdAcompanhantes->erro_msg);

  }

}

elseif ($oParam->exec == 'excluirAcompanhante') {

  $oDaoTfdAcompanhantes                = db_utils::getdao('tfd_acompanhantes');

  $oDaoTfdAcompanhantes->tf13_i_codigo = $oParam->iCodigo;
  $oDaoTfdAcompanhantes->excluir($oParam->iCodigo);

  if ($oDaoTfdAcompanhantes->erro_status == '0') {

    $oRetorno->iStatus  = 2;
    $oRetorno->sMessage = urlencode($oDaoTfdAcompanhantes->erro_msg);

  }

}

elseif ($oParam->exec == 'getInfoCgm') {
  
  $oDaoCgm = db_utils::getdao('cgm');
  
  $sSql    = $oDaoCgm->sql_query($oParam->iCgm);
  $rsCgm   = $oDaoCgm->sql_record($sSql);
  
  $oRetorno->z01_numcgm = $oParam->iCgm;
  if ($oDaoCgm->numrows > 0) { // se encontrou o cgm

    $oDadosCgm            = db_utils::fieldsmemory($rsCgm, 0);
    $oRetorno->z01_ender  = urlencode($oDadosCgm->z01_ender);
    $oRetorno->z01_bairro = urlencode($oDadosCgm->z01_bairro);
    $oRetorno->z01_munic  = urlencode($oDadosCgm->z01_munic);
    $oRetorno->z01_uf     = urlencode($oDadosCgm->z01_uf);
    $oRetorno->z01_compl  = urlencode($oDadosCgm->z01_compl);
    $oRetorno->z01_numero = urlencode($oDadosCgm->z01_numero);

  } else {

    $oRetorno->z01_ender  = ''; 
    $oRetorno->z01_bairro = '';
    $oRetorno->z01_munic  = '';
    $oRetorno->z01_uf     = '';
    $oRetorno->z01_compl  = '';
    $oRetorno->z01_numero = '';
    $oRetorno->z01_numcgm = '';

  }

}

elseif ($oParam->exec == 'getHorariosData') {
    
  $aData                = explode('/', $oParam->dData);
  $dData                = @$aData[2].'-'.@$aData[1].'-'.@$aData[0];
  // somo 1 pq na tab diasemana dom é 1
  $iDiasemana           = date('w', mktime(0, 0, 0, $aData[1], $aData[0], $aData[2])) + 1;

  $oDaoTfdGradeHorarios = db_utils::getdao('tfd_gradehorarios');
  $sCampos              = 'tf02_c_horario, tf02_c_localsaida, tf02_i_lotacao ';
  $sSql                 = $oDaoTfdGradeHorarios->sql_query(null,  $sCampos, 'tf02_c_horario',
                                                           "(('$dData' >= tf02_d_validadeini and".
                                                           " tf02_d_validadefim is null) or".
                                                           " (tf02_d_validadefim is not null and '$dData' between ".
                                                           "tf02_d_validadeini and tf02_d_validadefim)) and".
                                                           " tf02_i_destino = ".$oParam->iDestino.
                                                           " and tf02_i_diasemana = $iDiasemana"
                                                          );
  $rs                  = $oDaoTfdGradeHorarios->sql_record($sSql);
  $iLinhas             = $oDaoTfdGradeHorarios->numrows;
  if ($iLinhas > 0) {
  
   /*
    * Obtém todos os horários da data
    */
    for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

       $oDados                                   = db_utils::fieldsmemory($rs, $iCont);
       $oRetorno->oHorarios[$iCont]->sHora       = urlencode($oDados->tf02_c_horario);
       $oRetorno->oHorarios[$iCont]->sLocalSaida = urlencode($oDados->tf02_c_localsaida);
       $oRetorno->oHorarios[$iCont]->iLotacao    = $oDados->tf02_i_lotacao;

    }

  } else {

    $oRetorno->iStatus  = 2;
    $oRetorno->sMessage = 'Nenhum registro retornado.';
   
  }

}

elseif ($oParam->exec == 'getCgsDataSaida') {
  
  $oDaoCgsUnd = new cl_cgs_und();

  $sCampos    = ' tf01_i_codigo, z01_i_cgsund, z01_v_nome, z01_v_ident, z01_v_cgccpf, z01_nome, tipo ';

  /* Obtenho todas as pessoas que estão com a saída agendada para o destino e data informada e que ainda
    não foram vinculadas a nehum veículo de destino 
  */
  $sCamposSub  = ' select tf19_i_cgsund ';
  $sSubSelect  = 'from tfd_veiculodestino ';
  $sSubSelect .= '   inner join tfd_passageiroveiculo on tf19_i_veiculodestino = tf18_i_codigo ';
  $sSubSelect .= "     where tf18_d_datasaida = '".$oParam->sData."'"; 
  $sSubSelect .= "       and tf18_c_horasaida = '".$oParam->sHora."'"; 
  $sSubSelect .= '       and tf18_i_destino = '.$oParam->iDestino;
  $sSubSelect .= '       and tf19_i_valido = 1 ';
  /* o Subselect possui todos as pessoas que já foram vinculadas a algum veículo */

  $sSql        = $oDaoCgsUnd->sql_query_cgs_beneficiadosajudacusto(null, $sCampos.', 2 as vinculado, '.
                                                                   '2 as tf19_i_colo, 2 as tf19_i_fica', null, 
                                                                   " tf17_d_datasaida = '".$oParam->sData."'".
                                                                   " and tf17_c_horasaida = '".$oParam->sHora."'".
                                                                   " and tf25_i_destino = ".$oParam->iDestino.
                                                                   " and tf13_i_anulado = 2 ".
                                                                   " and z01_i_cgsund not in ($sCamposSub$sSubSelect)"
                                                                  );
  $rsSemVeic   = $oDaoCgsUnd->sql_record($sSql);
  $iNumSemVeic = $oDaoCgsUnd->numrows; 
      

  /* Obtenho todas as pessoas que estão com a saída agendada para o destino e data informada e que foram
     vinculadas para o veículo informado 
  */
  $sCamposSub  = ' select tf19_i_cgsund ';
  $sSubSelect  = 'from tfd_veiculodestino ';
  $sSubSelect .= '   inner join tfd_passageiroveiculo on tf19_i_veiculodestino = tf18_i_codigo ';
  $sSubSelect .= "     where tf18_d_datasaida = '".$oParam->sData."'"; 
  $sSubSelect .= "       and tf18_c_horasaida = '".$oParam->sHora."'"; 
  $sSubSelect .= '       and tf18_i_destino = '.$oParam->iDestino;
  $sSubSelect .= '       and tf19_i_valido = 1';  
  $sSubSelect .= '       and tf18_i_veiculo = '.$oParam->iVeiculo;
  /* o Subselect possui todos as pessoas que já foram vinculadas ao veículo informado */

  $sCampos    .= ", (select tf19_i_fica $sSubSelect and tf19_i_cgsund = z01_i_cgsund limit 1) as tf19_i_fica ";
  $sCampos    .= ", (select tf19_i_colo $sSubSelect and tf19_i_cgsund = z01_i_cgsund limit 1) as tf19_i_colo ";

  $sSql        = $oDaoCgsUnd->sql_query_cgs_beneficiadosajudacusto(null, $sCampos.', 1 as vinculado', null, 
                                                                   " tf17_d_datasaida = '".$oParam->sData."'".
                                                                   " and tf17_c_horasaida = '".$oParam->sHora."'".
                                                                   " and tf25_i_destino = ".$oParam->iDestino.
                                                                   " and tf13_i_anulado = 2 ".
                                                                   " and z01_i_cgsund in ($sCamposSub$sSubSelect)"
                                                                  );
  
  $rsJaVinc    = $oDaoCgsUnd->sql_record($sSql);
  $iNumJaVinc  = $oDaoCgsUnd->numrows;


  for ($iCont = 0; $iCont < $iNumSemVeic; $iCont++) {

    $oRetorno->aListaCgs[] = db_utils::fieldsmemory($rsSemVeic, $iCont);

  }

  for ($iCont = 0; $iCont < $iNumJaVinc; $iCont++) {

    $oRetorno->aListaCgs[] = db_utils::fieldsmemory($rsJaVinc, $iCont);

  }
  
  if ($iNumSemVeic == 0 && $iNumJaVinc == 0) {

    $oRetorno->iStatus = 2;
    $oRetorno->sMessage = 'Nenhum registro retornado.';

  }

  /* Se já existe alguém vinculado, obtenho o código da tfd_veiculodestino e mais algumas informações */
  if ($iNumJaVinc > 0) {

  	$oDaoVeiculoDestino        = new cl_tfd_veiculodestino();
  	$sCampos                   = 'tf18_i_codigo, tf18_d_dataretorno, tf18_c_horaretorno';
  	
  	if (isset($oParam->iCodigo) && !empty($oParam->iCodigo)){
  		
  		$sSql                      = $oDaoVeiculoDestino->sql_query2($oParam->iCodigo, $sCampos, null,null);
  	}else{
  		
  		$sSql                      = $oDaoVeiculoDestino->sql_query2(null, $sCampos, '',
																																							  				'tf18_i_veiculo   = '.$oParam->iVeiculo.' and '.
																																							  				'tf18_i_destino   = '.$oParam->iDestino.' and '.
																																							  				"tf18_d_datasaida = '".$oParam->sData."' and ".
																																							  				"tf18_c_horasaida = '".$oParam->sHora."'"
  		);
  	}
    $rs                        = $oDaoVeiculoDestino->sql_record($sSql);
    $oDados                    = db_utils::fieldsmemory($rs, 0);
    $oRetorno->iVeiculoDestino = $oDados->tf18_i_codigo;
    $oRetorno->dDataRetorno    = formataData($oDados->tf18_d_dataretorno, 2);
    $oRetorno->sHoraRetorno    = $oDados->tf18_c_horaretorno;

  } else {
    $oRetorno->iVeiculoDestino = '';
  }

}

elseif ($oParam->exec == 'getPedidosTfdDataRhcbo') {

  $oDaoTfdPedidoTfd = db_utils::getdao('tfd_pedidotfd');

  $sCampos          = ' tf01_i_codigo, tf04_c_abreviatura, tf01_d_datapedido, ';
  $sCampos         .= " case when tf01_i_emergencia = 1 then 'SIM' else 'NÃO' end as emergencia, ";
  $sCampos         .= " tf01_d_datapreferencia, z01_i_cgsund || ' - ' || z01_v_nome as paciente, ";
  $sCampos         .= ' z01_i_cgsund, z01_v_nome, z01_v_ident, z01_v_cgccpf, cgm.z01_nome, ';
  $sCampos         .= ' tf16_d_dataagendamento, tf16_c_horaagendamento, ';
  $sCampos         .= ' cgm.z01_numcgm as codprest, cgm.z01_nome as nomeprest ';

  @$dDataIni        = substr($oParam->dDataIni,6,4).'-'.substr($oParam->dDataIni,3,2).'-'.substr($oParam->dDataIni,0,2);
  @$dDataFim        = substr($oParam->dDataFim,6,4).'-'.substr($oParam->dDataFim,3,2).'-'.substr($oParam->dDataFim,0,2);
  $sWhere           = " tf01_d_datapedido between '$dDataIni' and '$dDataFim'";
  
  if (isset($oParam->iTipo) && $oParam->iTipo == 0) {
    $sWhere .= " and cgm.z01_numcgm is null ";
  } elseif (isset($oParam->iTipo) && $oParam->iTipo == 1) {
    $sWhere .= " and cgm.z01_numcgm is not null ";
  } 
  if (isset($oParam->sRhcbo)) {
    $sWhere .= " and rh70_estrutural = '$oParam->sRhcbo'";
  }
  $sOrdem = '';
  if (isset($oParam->iOrdem) && $oParam->iOrdem == 1) {
    $sOrdem = ' tf01_i_emergencia, tf01_d_datapedido ';
  } else {
    $sOrdem = ' tf01_d_datapedido, tf01_i_emergencia ';
  }
  if ($oParam->iNumRegistros > 0) {
    $sOrdem .= ' LIMIT '.$oParam->iNumRegistros;
  }
  $sSql = $oDaoTfdPedidoTfd->sql_query_protocolo('', $sCampos, $sOrdem, $sWhere);
  $rs   = $oDaoTfdPedidoTfd->sql_record($sSql);

  for ($iCont = 0; $iCont < $oDaoTfdPedidoTfd->numrows; $iCont++) {

    $oDados = db_utils::fieldsmemory($rs, $iCont);
    $oRetorno->oPedidos[$iCont]->tf01_i_codigo          = $oDados->tf01_i_codigo;
    $oRetorno->oPedidos[$iCont]->tf04_c_abreviatura     = urlencode($oDados->tf04_c_abreviatura);
    $oRetorno->oPedidos[$iCont]->tf01_d_datapedido      = urlencode($oDados->tf01_d_datapedido);
    $oRetorno->oPedidos[$iCont]->emergencia             = urlencode($oDados->emergencia);
    $oRetorno->oPedidos[$iCont]->tf01_d_datapreferencia = urlencode($oDados->tf01_d_datapreferencia);
    $oRetorno->oPedidos[$iCont]->paciente               = urlencode($oDados->paciente);
    $oRetorno->oPedidos[$iCont]->z01_v_ident            = urlencode($oDados->z01_v_ident);
    $oRetorno->oPedidos[$iCont]->z01_v_cgccpf           = urlencode($oDados->z01_v_cgccpf);
    $oRetorno->oPedidos[$iCont]->z01_nome               = urlencode($oDados->z01_nome);
    $oRetorno->oPedidos[$iCont]->tf16_d_dataagendamento = urlencode($oDados->tf16_d_dataagendamento);
    $oRetorno->oPedidos[$iCont]->tf16_c_horaagendamento = urlencode($oDados->tf16_c_horaagendamento);
    $oRetorno->oPedidos[$iCont]->z01_i_cgsund           = $oDados->z01_i_cgsund;
    $oRetorno->oPedidos[$iCont]->z01_v_nome             = urlencode($oDados->z01_v_nome);
    $oRetorno->oPedidos[$iCont]->codPrestadora          = $oDados->codprest;
    $oRetorno->oPedidos[$iCont]->nomePrestadora         = urlencode($oDados->nomeprest);
    
  }

  if ($oDaoTfdPedidoTfd->numrows == 0) {

    $oRetorno->iStatus  = 2;
    $oRetorno->sMessage = urlencode('Nenhum pedido de TFD encontrado.');

  }

}

elseif ($oParam->exec == 'getLotacaoDataHora') {

  $oDaoCgsUnd  = db_utils::getdao('cgs_und');
  $sCampos     = ' tf01_i_codigo, z01_i_cgsund, z01_v_nome, z01_v_ident, z01_v_cgccpf, z01_nome, tipo ';

  $sCamposSub  = 'select tf19_i_cgsund ';

  $sSubSelect  = 'from tfd_veiculodestino ';
  $sSubSelect .= '  inner join tfd_passageiroveiculo on tf19_i_veiculodestino = tf18_i_codigo ';
  $sSubSelect .= "    where tf18_d_datasaida = '".$oParam->sData."'"; 
  $sSubSelect .= "      and tf18_c_horasaida = '".$oParam->sHora."'"; 
  $sSubSelect .= '      and tf18_i_destino = '.$oParam->iDestino;
  $sSubSelect .= '      and tf19_i_valido = 1';
  if (isset($oParam->iVeiculo)) {
    $sSubSelect .= '      and tf18_i_veiculo = '.$oParam->iVeiculo;
  }

  $sCampos    .= ", (select tf19_i_colo $sSubSelect and tf19_i_cgsund = z01_i_cgsund limit 1) as tf19_i_colo ";

  $sSql        = $oDaoCgsUnd->sql_query_cgs_beneficiadosajudacusto(null, $sCampos, null, " tf17_d_datasaida='".
                                                                   $oParam->sData."' and tf17_c_horasaida = '".
                                                                   $oParam->sHora."' and tf25_i_destino = ".
                                                                   $oParam->iDestino." and tf13_i_anulado = 2 ".
                                                                   " and z01_i_cgsund in ($sCamposSub$sSubSelect)"
                                                                  );

  $rs          = $oDaoCgsUnd->sql_record($sSql);
      
  if ($oDaoCgsUnd->numrows > 0) {

    $oDados = db_utils::getColectionByRecord($rs);
    $iPos   = count($oDados);
    $iPac   = 0;
    $iAcomp = 0;
    $iColo  = 0;
    for ($iCont = 0; $iCont < $iPos; $iCont++) {

      if ($oDados[$iCont]->tf19_i_colo == 1) {
        $iColo++;
      } 
      if ($oDados[$iCont]->tipo == 1) {
        $iPac++;
      } else {
        $iAcomp++;
      }

    }
    $oRetorno->iPac   = $iPac;
    $oRetorno->iAcomp = $iAcomp;
    $oRetorno->iColo  = $iColo;

  } else {
    
    $oRetorno->iStatus  = 2;
    $oRetorno->sMessage = 'Nenhum registro retornado.';

  }
  
}

elseif ($oParam->exec == 'getLotacaoRetorno') {

  $oDaoTfdVeiculoDestino    = db_utils::getdao('tfd_veiculodestino');
  $oDaoTfdPassageiroVeiculo = db_utils::getdao('tfd_passageiroveiculo');
  $oDaoTfdPassageiroRetorno = db_utils::getdao('tfd_passageiroretorno');
  $oDaoTfdGradeHorarios     = db_utils::getdao('tfd_gradehorarios');

  $sSql                     = $oDaoTfdVeiculoDestino->sql_query_file($oParam->iVeiculoDestino);
  $rs                       = $oDaoTfdVeiculoDestino->sql_record($sSql);
  $oDados                   = db_utils::fieldsmemory($rs, 0);

  /* Bloco que obtém a lotação à partir das informações da tfd_veiculodestino */
  $aData      = explode('-', $oDados->tf18_d_datasaida);
  $dData      = $oDados->tf18_d_datasaida;
  $iDiasemana = date('w', mktime(0, 0, 0, $aData[1], $aData[2], $aData[0])) + 1; // somo 1 pq na tab diasemana dom é 1

  $sSql       = $oDaoTfdGradeHorarios->sql_query(null,  'tf02_i_lotacao', '',
                                                 "(('$dData' >= tf02_d_validadeini and tf02_d_validadefim is null) or".
                                                 " (tf02_d_validadefim is not null and '$dData' between ".
                                                 "tf02_d_validadeini and tf02_d_validadefim)) and".
                                                 " tf02_i_destino = ".$oDados->tf18_i_destino.
                                                 " and tf02_i_diasemana = $iDiasemana".
                                                 " and tf02_c_horario = '".$oDados->tf18_c_horasaida."'"
                                                );

  $rs         = $oDaoTfdGradeHorarios->sql_record($sSql);
  if ($oDaoTfdGradeHorarios->numrows > 0) {

    $oDados             = db_utils::fieldsmemory($rs, 0);
    $oRetorno->iLotacao = $oDados->tf02_i_lotacao;

  } else {
    $oRetorno->iLotacao = 0;
  }

  /* Bloco que obtém os espaços que já estão ocupados para o retorno (os pac. que irão voltar e não são de colo) */
  $sSql       = $oDaoTfdPassageiroVeiculo->sql_query_file(null,  'count(*) as reservados', '',
                                                          'tf19_i_veiculodestino = '.$oParam->iVeiculoDestino.
                                                          ' and tf19_i_valido = 1 and tf19_i_colo = 2 '.
                                                          ' and tf19_i_fica = 2'
                                                         );

  $rs          = $oDaoTfdPassageiroVeiculo->sql_record($sSql);
  if ($oDaoTfdPassageiroVeiculo->numrows > 0) {

    $oDados                = db_utils::fieldsmemory($rs, 0);
    $oRetorno->iReservados = $oDados->reservados;

  } else {
    $oRetorno->iReservados = 0;
  }

  /* Bloco que obtém o numero de pacientes que já estão com o retorno marcado */
  $sSql     = $oDaoTfdPassageiroRetorno->sql_query_file(null,  'count(*) as retorno', '',
                                                        'tf31_i_veiculodestino = '.$oParam->iVeiculoDestino.
                                                        ' and tf31_i_valido = 1'
                                                       );

  $rs       = $oDaoTfdPassageiroRetorno->sql_record($sSql);
  if ($oDaoTfdPassageiroRetorno->numrows > 0) {

    $oDados             = db_utils::fieldsmemory($rs, 0);
    $oRetorno->iRetorno = $oDados->retorno;

  } else {
    $oRetorno->iRetorno = 0;
  }

}

elseif ($oParam->exec == 'getHorariosDataSaida') {
    
  $aData                 = explode('/', $oParam->dData);
  $dData                 = @$aData[2].'-'.@$aData[1].'-'.@$aData[0];

  $oDaoTfdVeiculoDestino = db_utils::getdao('tfd_veiculodestino');
  $sCampos               = 'distinct tf18_c_horasaida';
  $sSql                  = $oDaoTfdVeiculoDestino->sql_query2(null,  $sCampos, 'tf18_c_horasaida',
                                                              ' tf18_i_veiculo ='.$oParam->iVeiculo.' and '.
                                                              ' tf18_i_destino = '.$oParam->iDestino.' and '.
                                                              " tf18_d_datasaida = '".$dData."'"
                                                             );

  $rs                    = $oDaoTfdVeiculoDestino->sql_record($sSql);
  $iLinhas               = $oDaoTfdVeiculoDestino->numrows;
  if ($iLinhas > 0) {
  
   /*
    * Obtém todos os horários da data
    */
    for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

       $oDados                      = db_utils::fieldsmemory($rs, $iCont);
       $oRetorno->aHorarios[$iCont] = urlencode($oDados->tf18_c_horasaida);

    }

  } else {

    $oRetorno->iStatus  = 2;
    $oRetorno->sMessage = 'Nenhum registro retornado.';
   
  }

}

elseif ($oParam->exec == 'getPedidosTfdDataRhcboRegulado') {

  $oDaoTfdPedidoTfd = db_utils::getdao('tfd_pedidotfd');

  $sCampos          = ' tf01_i_codigo, tf04_c_abreviatura, tf01_d_datapedido, ';
  $sCampos         .= " case when tf01_i_emergencia = 1 then 'SIM' else 'NÃO' end as emergencia, ";
  $sCampos         .= " tf01_d_datapreferencia, z01_i_cgsund || ' - ' || z01_v_nome as paciente, ";
  $sCampos         .= ' z01_i_cgsund, z01_v_nome, z01_v_ident, z01_v_cgccpf, z01_nome, ';
  $sCampos         .= ' tf34_i_codigo, tf34_i_especmedico, tf34_i_login, tf17_i_codigo ';

  @$dDataIni        = substr($oParam->dDataIni,6,4).'-'.substr($oParam->dDataIni,3,2).'-'.substr($oParam->dDataIni,0,2);
  @$dDataFim        = substr($oParam->dDataFim,6,4).'-'.substr($oParam->dDataFim,3,2).'-'.substr($oParam->dDataFim,0,2);
  $sWhere           = " tf01_d_datapedido between '$dDataIni' and '$dDataFim'";
  $sWhere          .= ' and (tf34_i_login is null or tf34_i_login = '.db_getsession('DB_id_usuario').') ';
 
  if (isset($oParam->sRhcbo)) {
    $sWhere .= " and rhcbo.rh70_estrutural = '$oParam->sRhcbo'";
  }

  $sOrderBy = 'tf34_i_codigo desc, tf01_d_datapedido, tf01_i_emergencia';
  $sSql     = $oDaoTfdPedidoTfd->sql_query_regulado('', $sCampos, $sOrderBy, $sWhere);
  $rs       = $oDaoTfdPedidoTfd->sql_record($sSql);

  for ($iCont = 0; $iCont < $oDaoTfdPedidoTfd->numrows; $iCont++) {

    $oDados = db_utils::fieldsmemory($rs, $iCont);
    $oRetorno->oPedidos[$iCont]->tf01_i_codigo          = $oDados->tf01_i_codigo;
    $oRetorno->oPedidos[$iCont]->tf04_c_abreviatura     = urlencode($oDados->tf04_c_abreviatura);
    $oRetorno->oPedidos[$iCont]->tf01_d_datapedido      = urlencode($oDados->tf01_d_datapedido);
    $oRetorno->oPedidos[$iCont]->emergencia             = urlencode($oDados->emergencia);
    $oRetorno->oPedidos[$iCont]->tf01_d_datapreferencia = urlencode($oDados->tf01_d_datapreferencia);
    $oRetorno->oPedidos[$iCont]->paciente               = urlencode($oDados->paciente);
    $oRetorno->oPedidos[$iCont]->z01_v_ident            = urlencode($oDados->z01_v_ident);
    $oRetorno->oPedidos[$iCont]->z01_v_cgccpf           = urlencode($oDados->z01_v_cgccpf);
    $oRetorno->oPedidos[$iCont]->z01_nome               = urlencode($oDados->z01_nome);
    $oRetorno->oPedidos[$iCont]->z01_i_cgsund           = $oDados->z01_i_cgsund;
    $oRetorno->oPedidos[$iCont]->z01_v_nome             = urlencode($oDados->z01_v_nome);
    $oRetorno->oPedidos[$iCont]->tf34_i_codigo          = $oDados->tf34_i_codigo;
    $oRetorno->oPedidos[$iCont]->tf34_i_especmedico     = $oDados->tf34_i_especmedico;
    $oRetorno->oPedidos[$iCont]->tf34_i_login           = $oDados->tf34_i_login;
    $oRetorno->oPedidos[$iCont]->tf17_i_codigo          = $oDados->tf17_i_codigo;

  }

  if ($oDaoTfdPedidoTfd->numrows == 0) {

    $oRetorno->iStatus  = 2;
    $oRetorno->sMessage = urlencode('Nenhum pedido de TFD encontrado.');

  }

}

elseif ($oParam->exec == 'getEspecMedico') {

  $oDaoEspecMedico = db_utils::getdao('especmedico');

  $sCampos         = ' sd27_i_codigo, rh70_descr ';

  $sWhere          = " sd27_c_situacao = 'A' and sd03_i_codigo = ".$oParam->iMedico;
 
  $sSql            = $oDaoEspecMedico->sql_query_especmedico('', $sCampos, 'rh70_descr', 
                                                             $sWhere
                                                            );
 // echo $sSql;
  $rs              = $oDaoEspecMedico->sql_record($sSql);

  for ($iCont = 0; $iCont < $oDaoEspecMedico->numrows; $iCont++) {

    $oDados                                            = db_utils::fieldsmemory($rs, $iCont);
    $oRetorno->aEspecialidades[$iCont]->sEspecialidade = urlencode($oDados->rh70_descr);
    $oRetorno->aEspecialidades[$iCont]->iEspecMedico   = $oDados->sd27_i_codigo;

  }

  if ($oDaoEspecMedico->numrows == 0) {

    $oRetorno->iStatus  = 2;
    $oRetorno->sMessage = urlencode('Nenhuma especialidade encontrada.');

  }

} 

elseif ($oParam->exec == 'verificaProcedimentosEspecMedico') {

  $oDaoEspecMedico  = db_utils::getdao('especmedico');
  $oDaoTfdPedidoTfd = db_utils::getdao('tfd_pedidotfd');

  $sSql             = $oDaoEspecMedico->sql_query_especmedico($oParam->iEspecMedico, ' rh70_estrutural ');
  $rs               = $oDaoEspecMedico->sql_record($sSql);
  $sEspecialidade   = db_utils::fieldsmemory($rs, 0)->rh70_estrutural;

  /* Verifico se todos os procedimentos do pedido de tfd possuem a especialidade do médico reguladaor informada*/
  $sWhere  = ' tf01_i_codigo = '.$oParam->iPedido;
  $sWhere .= ' and (select count(*) ';
  $sWhere .= '        from tfd_procpedidotfd ';
  $sWhere .= '          where tfd_procpedidotfd.tf23_i_pedidotfd = tf01_i_codigo) ';
  $sWhere .= '                                     = ';
  $sWhere .= '      (select count(*) ';
  $sWhere .= '         from tfd_procpedidotfd ';
  $sWhere .= '           inner join sau_procedimento on sau_procedimento.sd63_i_codigo = ';
  $sWhere .= '             tfd_procpedidotfd.tf23_i_procedimento ';
  $sWhere .= '             where tfd_procpedidotfd.tf23_i_pedidotfd = tf01_i_codigo  ';
  $sWhere .= '               and exists ';
  $sWhere .= '                 (select *  ';
  $sWhere .= '                    from sau_proccbo ';
  $sWhere .= '                      inner join rhcbo as rh on rh.rh70_sequencial = sau_proccbo.sd96_i_cbo ';
  $sWhere .= '                         where sau_proccbo.sd96_i_procedimento = sd63_i_codigo  ';
  $sWhere .= "                            and rh.rh70_estrutural = '$sEspecialidade')) ";

  $sSql    = $oDaoTfdPedidoTfd->sql_query_regulado('', 'tf01_i_codigo', '', $sWhere);
  $oDaoTfdPedidoTfd->sql_record($sSql);
  if ($oDaoTfdPedidoTfd->numrows == 0) {
    $oRetorno->iStatus = 2; 
  }

  $oRetorno->sIdCkBox = $oParam->sIdCkBox;

}

elseif ($oParam->exec == 'getPedidosTfdDeLista') {
	
	$oDaoTfdPedidoTfd = db_utils::getdao('tfd_pedidotfd');

  $sCampos          = ' tf01_i_codigo,  ';
  $sCampos         .= " tf16_c_protocolo, tf16_sequencia, ";
  $sCampos         .= " z01_v_nome as paciente, tf16_sala, ";
  $sCampos         .= ' tf16_c_local,';
  $sCampos         .= ' tf16_d_dataagendamento, tf16_c_horaagendamento, tf16_c_medico';

  $sWhere           = " tf01_i_codigo in ($oParam->sPedidos)";

  $sSql = $oDaoTfdPedidoTfd->sql_query_pedido('', $sCampos, ' tf01_i_codigo ', $sWhere);
  $rs   = $oDaoTfdPedidoTfd->sql_record($sSql);

  for ($iCont = 0; $iCont < $oDaoTfdPedidoTfd->numrows; $iCont++) {

    $oDados = db_utils::fieldsmemory($rs, $iCont);
    $oRetorno->oPedidos[$iCont]->tf01_i_codigo          = $oDados->tf01_i_codigo;    
    $oRetorno->oPedidos[$iCont]->tf16_d_dataagendamento = urlencode($oDados->tf16_d_dataagendamento);
    $oRetorno->oPedidos[$iCont]->tf16_c_horaagendamento = urlencode($oDados->tf16_c_horaagendamento);
    $oRetorno->oPedidos[$iCont]->paciente               = urlencode($oDados->paciente);
    $oRetorno->oPedidos[$iCont]->tf16_c_medico          = urlencode($oDados->tf16_c_medico);
    $oRetorno->oPedidos[$iCont]->tf16_c_protocolo       = urlencode($oDados->tf16_c_protocolo);
    $oRetorno->oPedidos[$iCont]->tf16_sequencia         = urlencode($oDados->tf16_sequencia);
    $oRetorno->oPedidos[$iCont]->tf16_sala              = urlencode($oDados->tf16_sala);
    $oRetorno->oPedidos[$iCont]->tf16_c_local           = urlencode($oDados->tf16_c_local);
    
  }

  if ($oDaoTfdPedidoTfd->numrows == 0) {

    $oRetorno->iStatus  = 2;
    $oRetorno->sMessage = urlencode('Nenhum pedido de TFD encontrado.');

  }
	
}

elseif ($oParam->exec == "atualizaPrestadoraTFD") {
  
  $oDaotfd_agendamentoprestadora = db_utils::getdao('tfd_agendamentoprestadora');
  $oDaotfd_agendasaida           = db_utils::getdao('tfd_agendasaida');
  $aPedidos                      = $oParam->aPedidos;
  $aSaidaCadastrada              = array();
  $iCont                         = 0; 
  $oRetorno->iStatus             = 1;
  $oRetorno->aSaidaCadastrada    = '';
  $oRetorno->sMessage            = '';
  
  for ($iI = 0; $iI < $oParam->iNumReg; $iI++) {
  
    $tf16_i_codigo = null;
    /* VERIFICA SE O PEDIDO JA FOI AGENDADO */
    $sWhere  = " tf16_i_pedidotfd = ".$aPedidos[$iI]->tf16_i_pedidotfd;
    $sSql    = $oDaotfd_agendamentoprestadora->sql_query_file (null, '*', null, $sWhere);
    $rs      = $oDaotfd_agendamentoprestadora->sql_record($sSql);
    if ($oDaotfd_agendamentoprestadora->numrows > 0) {

      $db_opcao = 2;
      $oDados = db_utils::fieldsmemory($rs, 0);
      $tf16_i_codigo = $oDados->tf16_i_codigo;

    }
    $oDaotfd_agendamentoprestadora->tf16_i_codigo              = $tf16_i_codigo;
    $oDaotfd_agendamentoprestadora->tf16_i_prestcentralagend   = $oParam->tf16_i_prestcentralagend; 
    $oDaotfd_agendamentoprestadora->tf16_i_login               = $oParam->tf16_i_login;
    $oDaotfd_agendamentoprestadora->tf16_i_pedidotfd           = $aPedidos[$iI]->tf16_i_pedidotfd;
    $oDaotfd_agendamentoprestadora->tf16_c_protocolo           = $aPedidos[$iI]->tf16_c_protocolo;
    $oDaotfd_agendamentoprestadora->tf16_d_dataagendamento_dia = $aPedidos[$iI]->tf16_d_dataagendamento_dia;
    $oDaotfd_agendamentoprestadora->tf16_d_dataagendamento_mes = $aPedidos[$iI]->tf16_d_dataagendamento_mes;
    $oDaotfd_agendamentoprestadora->tf16_d_dataagendamento_ano = $aPedidos[$iI]->tf16_d_dataagendamento_ano;
    $oDaotfd_agendamentoprestadora->tf16_d_dataagendamento     = $aPedidos[$iI]->tf16_d_dataagendamento;
    $oDaotfd_agendamentoprestadora->tf16_c_horaagendamento     = $aPedidos[$iI]->tf16_c_horaagendamento;
    $oDaotfd_agendamentoprestadora->tf16_c_local               = $aPedidos[$iI]->tf16_c_local;
    $oDaotfd_agendamentoprestadora->tf16_c_medico              = $aPedidos[$iI]->tf16_c_medico;
    $oDaotfd_agendamentoprestadora->tf16_sequencia             = $aPedidos[$iI]->tf16_sequencia;
    $oDaotfd_agendamentoprestadora->tf16_sala                  = $aPedidos[$iI]->tf16_sala;
    $oDaotfd_agendamentoprestadora->tf16_d_datasistema         = $oParam->tf16_d_datasistema;   
    $oDaotfd_agendamentoprestadora->tf16_d_datasistema_dia     = $oParam->tf16_d_datasistema_dia;
    $oDaotfd_agendamentoprestadora->tf16_d_datasistema_mes     = $oParam->tf16_d_datasistema_mes;
    $oDaotfd_agendamentoprestadora->tf16_d_datasistema_ano     = $oParam->tf16_d_datasistema_ano;
    $oDaotfd_agendamentoprestadora->tf16_c_horasistema         = $oParam->tf16_c_horasistema;
    /* CASO EXISTA REGISTRO ALTERA */
    if ($tf16_i_codigo != '') {
      
      /* VERIFICA SE O PEDIDO JA NÃO AGENDOU SAIDA */
      $sWhere = " tf17_i_pedidotfd = ".$aPedidos[$iI]->tf16_i_pedidotfd;
      $sSql   = $oDaotfd_agendasaida->sql_query2(null, '*', null, $sWhere);
      $rs     = $oDaotfd_agendasaida->sql_record($sSql);
      if ($oDaotfd_agendasaida->numrows > 0) {
    
        $aSaidaCadastrada[$iCont] = $aPedidos[$iI]->tf16_i_pedidotfd;
        $iCont++;

      } else {
        
        db_inicio_transacao();
        $oDaotfd_agendamentoprestadora->alterar($tf16_i_codigo);
        db_fim_transacao($oDaotfd_agendamentoprestadora->erro_status == '0' ? true : false);

      }
    
    } else {
      
        db_inicio_transacao();
        $oDaotfd_agendamentoprestadora->incluir(null);
        db_fim_transacao($oDaotfd_agendamentoprestadora->erro_status == '0' ? true : false);
        
    }
    if ($oDaotfd_agendamentoprestadora->erro_status == '0') {

      $oRetorno->iStatus  = 2;
      $oRetorno->sMessage = urlencode($oDaotfd_agendamentoprestadora->erro_msg);

    }
    
  }
  $oRetorno->aSaidaCadastrada = $aSaidaCadastrada;

}

elseif ($oParam->exec == "getPacientesSaidaData") {

  $oDaoTfdPedidoTfd   = db_utils::getdao('tfd_pedidotfd');

  $aIntevalo = array();
  if (!empty($oParam->dataInicial)) {
    $aIntevalo[] = " tf16_d_dataagendamento >= '" . formataData($oParam->dataInicial) . "'";
  }
  
  if (!empty($oParam->dataFinal)) {
    $aIntevalo[] = " tf16_d_dataagendamento <= '" . formataData($oParam->dataFinal) . "'";
  }
  
  $sIntevalo = implode(" and ", $aIntevalo);
  
  $sCampos            = ' distinct tf01_i_codigo, ';
  $sCampos           .= ' z01_v_nome as paciente, z01_i_cgsund as cgs,';
  $sCampos           .= '  tf17_c_localsaida,  tf17_d_datasaida, tf17_c_horasaida,';
  $sCampos           .= ' tf16_d_dataagendamento, tf16_c_horaagendamento, ';
  $sCampos           .= ' cgmprest.z01_nome as prestadora, tf03_c_descr ';
//   $sCampos           .= ' (case when tf17_i_pedidotfd is not null then 1 else 2 end) as ajuda' ;

  $sWhere             = $sIntevalo;
  $sWhere            .= " and tf01_i_situacao = 1 "; // Situação 1 = Ativo
  $sWhere            .= " and tf16_i_pedidotfd is not null ";
  if ($oParam->sd03_i_codigo != '') {
    $sWhere            .= " and tf03_i_codigo = $oParam->sd03_i_codigo";  // Código do destino
  }
  
  /**
   * Saida = 1 => Comm agendamento de saida
   * Saida = 2 => Sem agendamento de saida 
   */
  if ($oParam->saida == 1) {
    $sWhere  .= " and  tf17_i_pedidotfd is not null ";
  } else if ($oParam->saida == 2) {
    $sWhere  .= " and  tf17_i_pedidotfd is null ";
  }
  $sSql = $oDaoTfdPedidoTfd->sql_query_pedido('', $sCampos, ' tf17_d_datasaida, tf01_i_codigo, paciente ', $sWhere);
  
  
  $rs   = $oDaoTfdPedidoTfd->sql_record($sSql);
  if ($oDaoTfdPedidoTfd->numrows > 0) {

    for ($iI = 0; $iI < $oDaoTfdPedidoTfd->numrows; $iI++) {
      
      $oDados = db_utils::fieldsmemory($rs, $iI);
      $oPedido[$iI]->pedido          = $oDados->tf01_i_codigo;
      $oPedido[$iI]->cgs             = $oDados->cgs;
      $oPedido[$iI]->paciente        = urlencode( $oDados->paciente);
      $oPedido[$iI]->localsaida      = urlencode($oDados->tf17_c_localsaida);
      $oPedido[$iI]->datasaida       = urlencode($oDados->tf17_d_datasaida);
      $oPedido[$iI]->horasaida       = urlencode($oDados->tf17_c_horasaida);
      $oPedido[$iI]->dataagendamento = urlencode($oDados->tf16_d_dataagendamento);
      $oPedido[$iI]->horaagendamento = urlencode($oDados->tf16_c_horaagendamento);
      $oPedido[$iI]->prestadora      = urlencode($oDados->prestadora);
      $oPedido[$iI]->destino         = urlencode($oDados->tf03_c_descr);
      
    }
    $oRetorno->oPedido = $oPedido;
    
  } else {
    
    $oRetorno->iStatus  = 2;
    $oRetorno->sMessage = urlencode("Não foi encontrado nenhum Pedido TFD no período informado.");
    
  }
  
}
elseif ($oParam->exec == "atualizartfagendasaida") {

  $oDaoSituacaoTFD = new cl_tfd_situacaopedidotfd();
  
  $dtSistema = date("Y-m-d", db_getsession("DB_datausu"));
  $sHora     = date("H:i");
  $iLogin    = db_getsession("DB_id_usuario");
  
  try {
  	
    db_inicio_transacao();
    
    foreach ($oParam->aPedidos as $oPedido) {
      
      /**
       * Se o Paciente viajou, devemos alterar a situação do pedido para         -> 2 - ENCERRADO
       * Se não, assumimos que houve uma desistencia e alteramos a situação para -> 4 - DESISTÊNCIA  
       */
      
      
      $iSituacao = 4;
      $sObservacao = "O PACIENTE NÃO VIAJOU PARA FAZER O TRATAMENTO.";
      if ($oPedido->lViajou) {
        
        $iSituacao = 2;
        $sObservacao = "O PACIENTE VIAJOU PARA FAZER O TRATAMENTO.";
      }
      
      $oDaoSituacaoTFD->tf28_i_pedidotfd = $oPedido->iPedido;
      $oDaoSituacaoTFD->tf28_i_situacao  = $iSituacao;
      $oDaoSituacaoTFD->tf28_c_obs       = pg_escape_string($sObservacao); 
      $oDaoSituacaoTFD->tf28_d_datasistema = $dtSistema;
      $oDaoSituacaoTFD->tf28_c_horasistema = $sHora;
      $oDaoSituacaoTFD->tf28_i_login       = $iLogin;
      
      $oDaoSituacaoTFD->incluir(null);
      
      if ($oDaoSituacaoTFD->erro_status == 0) {
        throw new DBException($oDaoSituacaoTFD->erro_msg);
      }
    }
    db_fim_transacao();
  } catch (DBException $oErro) {
  	
    db_fim_transacao(true);
    $oRetorno->iStatus  = 2;
    $oRetorno->sMessage = urlencode($oErro->getMessage());
  }
  
}

echo $oJson->encode($oRetorno);
?>