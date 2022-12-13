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

require_once(modification('fpdf151/pdf.php'));
require_once(modification('libs/db_utils.php'));
require_once(modification('libs/db_stdlibwebseller.php'));

function getInfoCgs($iCgs) {

  $oDaoCgsUnd = db_utils::getdao('cgs_und');
  $sSql       = $oDaoCgsUnd->sql_query_file($iCgs);
  $rs         = $oDaoCgsUnd->sql_record($sSql);
  $aDados     = array();
  if ($oDaoCgsUnd->numrows > 0) {
    $aDados[0] = db_utils::fieldsmemory($rs, 0);
  }

  return $aDados;

}

function getDocumentosCgs($iCgs, $iTipo, $dIni, $dFim) {
 
  global $aDadosCgs; // Dados do Cgs já buscados pela função getInfoCgs
  return $aDadosCgs;

}

function getCartaoSusCgs($iCgs, $iTipo, $dIni, $dFim) {

  $oDaoCgsCartaoSus = db_utils::getdao('cgs_cartaosus');

  /* pega os cartões sus */
  $sSql           = $oDaoCgsCartaoSus->sql_query(null, ' s115_c_cartaosus, s115_c_tipo, s115_i_codigo ',
                                                 ' s115_c_tipo asc ', ' s115_i_cgs = '.$iCgs
                                                );
  $rsCgsCartaoSus = $oDaoCgsCartaoSus->sql_record($sSql);

  $aCartoes       = array();
  if ($oDaoCgsCartaoSus->numrows > 0) { // se o paciente tem um cartao sus

    for ($iCont = 0; $iCont < $oDaoCgsCartaoSus->numrows; $iCont++) {

      $oDadosCgsCartaoSus                 = db_utils::fieldsmemory($rsCgsCartaoSus, $iCont);
      $aCartoes[$iCont]->s115_c_cartaosus = $oDadosCgsCartaoSus->s115_c_cartaosus;
      $aCartoes[$iCont]->s115_c_tipo      = $oDadosCgsCartaoSus->s115_c_tipo;
      $aCartoes[$iCont]->s115_i_codigo    = $oDadosCgsCartaoSus->s115_i_codigo;

    }

  }
  
  return $aCartoes;

}

function getAgendamentosCgs($iCgs, $iTipo, $dIni, $dFim) {

  $oDaoAgendamentos = db_utils::getdao('agendamentos');
  $dDataAtual       = date('Y-m-d', db_getsession('DB_datausu'));

  $sSubAtendido     = 'select sd29_i_codigo from prontagendamento inner join prontproced ';
  $sSubAtendido    .= ' on s102_i_prontuario = sd29_i_prontuario where s102_i_agendamento = sd23_i_codigo ';

  $sSubAnulado      = ' select * from agendaconsultaanula where s114_i_agendaconsulta = sd23_i_codigo limit 1';

  $sSubAnulado2     = ' select * from agendaconsultaanula inner join db_usuarios as a on ';
  $sSubAnulado2    .= ' a.id_usuario = s114_i_login where s114_i_agendaconsulta = sd23_i_codigo limit 1 ';

  $sCampos          = " sd23_d_agendamento, id_usuario, login, ";
  $sCampos         .= " sd101_c_descr, sd23_d_consulta, sd23_c_hora, ";
  $sCampos         .= " sd03_i_codigo , z01_nome, ";
  $sCampos         .= " case when exists($sSubAtendido) then 'Atendido' ";
  $sCampos         .= "   else case when sd23_d_consulta >= '$dDataAtual' then 'Agendado'";
  $sCampos         .= "          else '' end";
  $sCampos         .= " end as situacao, ";
  $sCampos         .= " case when exists($sSubAnulado) then 'true' else 'false' end as anulado, ";
  $sCampos         .= " (select s114_d_data from  ($sSubAnulado) as tmp) as data_anulacao, ";
  $sCampos         .= " (select s114_v_motivo from  ($sSubAnulado) as tmp2) as motivo_anulacao, ";
  $sCampos         .= " (select login from  ($sSubAnulado2) as tmp3) as usuario_anulacao ";

  $sOrderBy         = ' sd23_d_consulta desc, sd23_c_hora desc ';
  
  $sWhere           = ' sd23_i_numcgs = '.$iCgs;
  if ($dIni != '') {
    $sWhere .= " and sd23_d_consulta >= '".$dIni."'";
  }
  if ($dFim != '') {
    $sWhere .= " and sd23_d_consulta <= '".$dFim."'";
  }
  $sSql          = $oDaoAgendamentos->sql_query_consulta_geral(null, $sCampos, $sOrderBy, $sWhere);
  $rs            = $oDaoAgendamentos->sql_record($sSql);

  $aAgendamentos = array();
  if ($oDaoAgendamentos->numrows > 0) { // se o paciente possui agendamentos

    for ($iCont = 0; $iCont < $oDaoAgendamentos->numrows; $iCont++) {

      $oDados = db_utils::fieldsmemory($rs, $iCont);

      /* Verifico qual a situação do agendamentos */
      $sSituacao = $oDados->anulado == 'true' ? 'Anulado' : 
                                                 (empty($oDados->situacao) ? 'Não compareceu' :
                                                                             $oDados->situacao);
                                                                            
      $aAgendamentos[$iCont]->sd23_d_agendamento = $oDados->sd23_d_agendamento;
      $aAgendamentos[$iCont]->id_usuario         = $oDados->id_usuario;
      $aAgendamentos[$iCont]->login              = $oDados->login;
      $aAgendamentos[$iCont]->sd101_c_descr      = $oDados->sd101_c_descr;
      $aAgendamentos[$iCont]->sd23_d_consulta    = $oDados->sd23_d_consulta;
      $aAgendamentos[$iCont]->sd23_c_hora        = $oDados->sd23_c_hora;
      $aAgendamentos[$iCont]->sd03_i_codigo      = $oDados->sd03_i_codigo;
      $aAgendamentos[$iCont]->z01_nome           = $oDados->z01_nome;
      $aAgendamentos[$iCont]->situacao           = $sSituacao;
      $aAgendamentos[$iCont]->data_anulacao      = $oDados->data_anulacao;
      $aAgendamentos[$iCont]->motivo_anulacao    = $oDados->motivo_anulacao;
      $aAgendamentos[$iCont]->usuario_anulacao   = $oDados->usuario_anulacao;

    }

  } 
  
  return $aAgendamentos;

}

function getProntuariosCgs($iCgs, $iTipo, $dIni, $dFim) {

  $oDaoProntProced = new cl_prontproced();

  if ($iTipo == 1) {
    
    $sCampos  = 'sd24_i_codigo, s102_i_agendamento, sd29_d_data, sd29_c_hora, coddepto, ';
    $sCampos .= 'descrdepto, sd03_i_codigo, z01_nome, rh70_estrutural, rh70_descr, ';
    $sCampos .= 'sd29_i_usuario, login, sd29_d_cadastro, sd29_c_cadastro ';
    $sGroupBy = '';
    $sOrderBy = ' sd29_d_data desc, sd29_c_hora desc, sd24_i_codigo desc';
    $sWhere   = ' sd24_i_numcgs = '.$iCgs; 
    if ($dIni != '') {
      $sWhere .= " and sd29_d_data >= '".$dIni."'";
    }
    if ($dFim != '') {
      $sWhere .= " and sd29_d_data <= '".$dFim."'";
    }
  
  } else {
    
    $sCampos  = ' sd63_c_procedimento,sd63_c_nome, (sd63_f_sh + sd63_f_sa + sd63_f_sp) as valor_unitario, ';
    $sCampos .= ' count(sd63_i_codigo) as quantidade,sum(sd63_f_sh + sd63_f_sa + sd63_f_sp) as valor_total ';
    $sGroupBy = ' group by sd63_c_procedimento, sd63_c_nome, sd63_f_sh, sd63_f_sa, sd63_f_sp'; 
    $sOrderBy = '';
    $sWhere   = ' sd24_i_numcgs = '.$iCgs;
    if ($dIni != "" || $dFim != "") {
      
      $sWhere2  = ''; 
      if ($dIni != "") {
        $sWhere2 .= " and sd29_d_data >= '".$dIni."'";  
      }
      if ($dFim != "") {
        $sWhere2 .= " and sd29_d_data <= '".$dFim."'";
      }
      $sWhere .= ' and sd63_i_codigo in ('.$oDaoProntProced->sql_query("","sd63_i_codigo","",$sWhere.$sWhere2).')';
      $sWhere .= $sWhere2;
      
    } 
    
  }

  $sSql         = $oDaoProntProced->sql_query_consulta_geral(null, $sCampos, $sOrderBy, 
                                                             $sWhere.$sGroupBy
                                                            );

  $rs           = $oDaoProntProced->sql_record($sSql);
  
  $aProntuarios = array();
  if ($oDaoProntProced->numrows > 0) { // Se o paciente possui prontuários

    for ($iCont = 0; $iCont < $oDaoProntProced->numrows; $iCont++) {

      $oDados = db_utils::fieldsmemory($rs, $iCont);

      if ($iTipo == 1) {
        
        $aProntuarios[$iCont]->sd24_i_codigo      = $oDados->sd24_i_codigo;
        $aProntuarios[$iCont]->s102_i_agendamento = $oDados->s102_i_agendamento;
        $aProntuarios[$iCont]->sd29_d_data        = $oDados->sd29_d_data;
        $aProntuarios[$iCont]->sd29_c_hora        = $oDados->sd29_c_hora;
        $aProntuarios[$iCont]->coddepto           = $oDados->coddepto;
        $aProntuarios[$iCont]->descrdepto         = $oDados->descrdepto;
        $aProntuarios[$iCont]->sd03_i_codigo      = $oDados->sd03_i_codigo;
        $aProntuarios[$iCont]->z01_nome           = $oDados->z01_nome;
        $aProntuarios[$iCont]->rh70_estrutural    = $oDados->rh70_estrutural;
        $aProntuarios[$iCont]->rh70_descr         = $oDados->rh70_descr;
        $aProntuarios[$iCont]->sd29_i_usuario     = $oDados->sd29_i_usuario;
        $aProntuarios[$iCont]->login              = $oDados->login;
        $aProntuarios[$iCont]->sd29_d_cadastro    = $oDados->sd29_d_cadastro;
        $aProntuarios[$iCont]->sd29_c_cadastro    = $oDados->sd29_c_cadastro;
        
      } else {
        
        $aProntuarios[$iCont]->sd63_c_procedimento = $oDados->sd63_c_procedimento;
        $aProntuarios[$iCont]->sd63_c_nome         = $oDados->sd63_c_nome;
        $aProntuarios[$iCont]->valor_unitario      = $oDados->valor_unitario;
        $aProntuarios[$iCont]->quantidade          = $oDados->quantidade;
        $aProntuarios[$iCont]->valor_total         = $oDados->valor_total;
        
      }

    }

  }

  return $aProntuarios;

}

function getRetiradasCgs($iCgs, $iTipo, $dIni, $dFim) {

  $oDaoFarRetiradaItens = db_utils::getdao('far_retiradaitens');

  $sCampos  = 'fa04_d_data, fa01_i_codigo, m60_descr, fa06_f_quant, m77_lote, fa07_i_matrequi, login, tipo,';
  $sCampos .= "case when tipo = 1 then 'Retirada' else case when fa23_i_cancelamento = 1 then 'Cancelamento '";
  $sCampos .= "else 'Devolução' end end as stipo, m77_dtvalidade, fa23_c_motivo, ";
  $sCampos .= 'fa22_d_data, fa23_i_quantidade ';
  
  $sWhereRetirada  = " z01_i_cgsund = $iCgs ";
  if ($dIni != '') {
    $sWhereRetirada .= " and fa04_d_data >= '".$dIni."'";
  }
  if ($dFim != '') {
    $sWhereRetirada .= " and fa04_d_data <= '".$dFim."'";
  }
  $sWhereDevolucao = " z01_i_cgsund = $iCgs ";
  if ($dIni != '') {
    $sWhereDevolucao .= " and fa22_d_data >= '".$dIni."'";
  }
  if ($dFim != '') {
    $sWhereDevolucao .= " and fa22_d_data <= '".$dFim."'";
  }
  
  $sSql     = $oDaoFarRetiradaItens->sql_query_historicoretiradasdevolucoes($iCgs, 
                                                                            $sCampos, 
                                                                            'fa06_i_codigo desc, tipo asc',
                                                                            $sWhereRetirada,
                                                                            $sWhereDevolucao
                                                                           );
  $rs       = $oDaoFarRetiradaItens->sql_record($sSql);

  $aRetiradas = array();
  for ($iCont = 0; $iCont < $oDaoFarRetiradaItens->numrows; $iCont++) {

    $oDados = db_utils::fieldsmemory($rs, $iCont);
    if ($oDados->tipo == 2) {
    
      $oDados->fa04_d_data  = $oDados->fa22_d_data;
      $oDados->fa06_f_quant = $oDados->fa23_i_quantidade;
    
    }

    $aRetiradas[$iCont] = $oDados;

  }

  return $aRetiradas;

}

function getExamesCgs($iCgs, $iTipo, $dIni, $dFim) {

  $oDaoLabRequiItem = db_utils::getdao('lab_requiitem');

  if ($iTipo == 1) {
  
    $sCampos  = 'la21_d_data, la02_c_descr, la08_c_descr, la32_d_data, la31_d_data';
    $sOrderBy = 'la21_d_data';
    $sGroupBy = '';
    $sWhere   = ' la22_i_cgs = '.$iCgs; 
    if ($dIni != '') {
      $sWhere .= " and la21_d_data >= '".$dIni."'";
    }
    if ($dFim != '') {
      $sWhere .= " and la21_d_data <= '".$dFim."'";
    }
  
  } else {

    $sCampos  = ' sd63_c_procedimento,sd63_c_nome, ';
    $sCampos .= ' (sd63_f_sh + sd63_f_sa + sd63_f_sp + la53_n_acrescimo) as valor_unitario, ';
    $sCampos .= ' sum(la21_i_quantidade) as quantidade, ';
    $sCampos .= ' (sd63_f_sh + sd63_f_sa + sd63_f_sp + la53_n_acrescimo) * sum(la21_i_quantidade) ';
    $sCampos .= ' as valor_total ';
    $sWhere   = ' la22_i_cgs = '.$iCgs;
    if ($dIni != "" || $dFim != "") {
      
      $sWhere2  = ' la22_i_cgs = '.$iCgs; 
      if ($dIni != "") {
        $sWhere2 .= " and la21_d_data >= '".$dIni."'";  
      }
      if ($dFim != "") {
        $sWhere2 .= " and la21_d_data <= '".$dFim."'";
      }
      $sWhere .= ' and la21_i_codigo in ('.$oDaoLabRequiItem->sql_query_consulta_geral("", "la21_i_codigo","", 
                                                                                       $sWhere2).')';
      
    }
    $sGroupBy = ' group by sd63_c_procedimento, sd63_c_nome, valor_unitario '; 
    $sOrderBy = ' sd63_c_procedimento ';
    
  }
  $sSql    = $oDaoLabRequiItem->sql_query_consulta_geral(null, $sCampos, $sOrderBy, 
                                                         $sWhere.$sGroupBy
                                                        );
  $rs      = $oDaoLabRequiItem->sql_record($sSql);

  $aExames = array();
  if ($oDaoLabRequiItem->numrows > 0) { // se o paciente possui agendamentos

    for ($iCont = 0; $iCont < $oDaoLabRequiItem->numrows; $iCont++) {

      $oDados = db_utils::fieldsmemory($rs, $iCont);
      
      if ($iTipo == 1) {
        
        $aExames[$iCont]->la21_d_data  = $oDados->la21_d_data;
        $aExames[$iCont]->la02_c_descr = $oDados->la02_c_descr;
        $aExames[$iCont]->la08_c_descr = $oDados->la08_c_descr;
        $aExames[$iCont]->la32_d_data  = $oDados->la32_d_data;
        $aExames[$iCont]->la31_d_data  = $oDados->la31_d_data;
      
      } else {
      
        $aExames[$iCont]->sd63_c_procedimento = $oDados->sd63_c_procedimento;
        $aExames[$iCont]->sd63_c_nome         = $oDados->sd63_c_nome;
        $aExames[$iCont]->valor_unitario      = $oDados->valor_unitario;
        $aExames[$iCont]->quantidade          = $oDados->quantidade;
        $aExames[$iCont]->valor_total         = $oDados->valor_total;
       
      }
      
    }

  }

  return $aExames;

}

function getPedidosTfdCgs($iCgs, $iTipo, $dIni, $dFim) {

  $oDaoTfdPedidoTfd = db_utils::getdao('tfd_pedidotfd');
  $sWhere           = ' tf01_i_cgsund = '.$iCgs; 
  if ($dIni != '') {
    $sWhere .= " and tf16_d_dataagendamento >= '".$dIni."'";
  }
  if ($dFim != '') {
    $sWhere .= " and tf16_d_dataagendamento <= '".$dFim."'";
  }
  $sSql     = $oDaoTfdPedidoTfd->sql_query_grid(null, '*', ' tf16_d_dataagendamento desc ', 
                                                $sWhere
                                               );
  $rs       = $oDaoTfdPedidoTfd->sql_record($sSql);

  $aPedidos = array();
  for ($iCont = 0; $iCont < $oDaoTfdPedidoTfd->numrows; $iCont++) {

    $oDados                                   = db_utils::fieldsmemory($rs, $iCont);

    $aPedidos[$iCont]->tf01_i_codigo          = $oDados->tf01_i_codigo;
    $aPedidos[$iCont]->tf16_d_dataagendamento = $oDados->tf16_d_dataagendamento;
    $aPedidos[$iCont]->tf17_d_datasaida       = $oDados->tf17_d_datasaida;
    $aPedidos[$iCont]->z01_nomeprestadora     = $oDados->z01_nome;
    $aPedidos[$iCont]->tf03_i_codigo          = $oDados->tf03_i_codigo;
    $aPedidos[$iCont]->tf03_c_descr           = $oDados->tf03_c_descr;
    $aPedidos[$iCont]->tf26_c_descr           = $oDados->tf26_c_descr;
    $aPedidos[$iCont]->tf01_d_datapedido      = $oDados->tf01_d_datapedido;

  }

  return $aPedidos;

}

function getVacinasCgs($iCgs, $iTipo, $dIni, $dFim) {
  
  global $aDadosCgs;
  $oDaoVacVacinadose = db_utils::getdao('vac_vacinadose');
  $oDaoCgsUnd        = db_utils::getdao('cgs_und');

  /* Pego a data de nascimento do CGS */
  $dNasc  = $aDadosCgs[0]->z01_d_nasc;

  $dAtual = date('d/m/Y', db_getsession('DB_datausu'));
  $aAtual = explode('/', $dAtual);
  
  /* Bloco que busca a informação das vacinas e doses */
  $sCampos  = ' vc07_i_codigo,';
  $sCampos .= ' vc05_c_descr,';
  $sCampos .= ' vc03_c_descr,';
  $sCampos .= ' vc07_c_nome,';
  $sCampos .= ' vc07_i_faixainidias,';
  $sCampos .= ' vc07_i_faixainimes,';
  $sCampos .= ' vc07_i_faixainiano,';
  $sCampos .= ' vc07_i_faixafimdias,';
  $sCampos .= ' vc07_i_faixafimmes,';
  $sCampos .= ' vc07_i_faixafimano,';
  $sCampos .= ' vc07_i_diasatraso,';
  $sCampos .= ' vc07_i_diasantecipacao,';
  $sCampos .= " (select vc16_d_dataaplicada || ' || ' ||";
  $sCampos .= "         vc16_t_obs || ' || ' ||";
  $sCampos .= "         vc16_i_usuario || ' || ' ||";
  $sCampos .= "         login || ' || ' ||";
  $sCampos .= "         vc16_i_codigo || ' || ' ||";
  $sCampos .= '         case when vc17_i_codigo is null';
  $sCampos .= '           then';
  $sCampos .= "             'true'";
  $sCampos .= '           else';
  $sCampos .= "             'false'";
  $sCampos .= "         end as lforarede";
  $sCampos .= '  from vac_aplica';
  $sCampos .= '    left join vac_aplicaanula on vac_aplicaanula.vc18_i_aplica = vac_aplica.vc16_i_codigo';
  $sCampos .= '    left join vac_aplicalote on vac_aplicalote.vc17_i_aplica = vac_aplica.vc16_i_codigo';
  $sCampos .= '    inner join db_usuarios on db_usuarios.id_usuario = vac_aplica.vc16_i_usuario';
  $sCampos .= "      where vc16_i_cgs = $iCgs";
  $sCampos .= '        and vc16_i_dosevacina = vc07_i_codigo';
  $sCampos .= '        and vc18_i_codigo is null';
  $sCampos .= '            order by vc16_i_codigo desc';
  $sCampos .= '              limit 1)';
  $sCampos .= ' as aplicacao';
  $sOrderBy = ' vc07_i_faixainiano, vc07_i_faixainimes, vc07_i_faixainidias ';
  $sSql     = $oDaoVacVacinadose->sql_query(null, $sCampos, $sOrderBy);
  $rs       = $oDaoVacVacinadose->sql_record($sSql);
  
  $aDadosVacinas = array();
  for ($iCont = 0; $iCont < $oDaoVacVacinadose->numrows; $iCont++) {
   
    $oDados = db_utils::fieldsmemory($rs, $iCont);
 
    $dDataAplicacao = '';
    $sObsAplicacao  = '';
    $iLogin         = '';
    $sLogin         = '';
    $iCodigoAplic   = '';
    $sForaRede      = '';
   
    /* A variável $oDados->aplicacao contém as informações da aplicação da vacina concatenadas com ' || ',
       que são buscadas no select acima. Caso a vacina (dose) ainda não tenha sido aplicada, a variável estará vazia */
    if (!empty($oDados->aplicacao)) {
     
      $aAplicacao     = explode(' || ', $oDados->aplicacao);
      $dDataAplicacao = $aAplicacao[0]; // data da aplicacao
      $sObsAplicacao  = $aAplicacao[1]; // obs da aplicacao
      $iLogin         = $aAplicacao[2]; // codigo do usuario que lancou a aplicacao
      $sLogin         = $aAplicacao[3]; // login do usuario que lancou a aplicacao
      $iCodigoAplic   = $aAplicacao[4]; // codigo da aplicacao
      $sForaRede      = $aAplicacao[5]; // foi ou nao realizada fora da rede
 
    }
   
    $aNasc = explode('-', $dNasc);
 
    /* Cálculo da data de vencimento (último dia em que é permitido tomar a vacina)*/
    $dVencimento = somaDataDiaMesAno($aNasc[2], $aNasc[1], $aNasc[0], 
                                     $oDados->vc07_i_faixafimdias + $oDados->vc07_i_diasatraso, 
                                     $oDados->vc07_i_faixafimmes, $oDados->vc07_i_faixafimano
                                    ); 
    /* Cálculo do primeiro dia em que é possível tomar a vacina */
    $dInicio     = somaDataDiaMesAno($aNasc[2], $aNasc[1], $aNasc[0], 
                                     $oDados->vc07_i_faixainidias - $oDados->vc07_i_diasantecipacao, 
                                     $oDados->vc07_i_faixainimes, $oDados->vc07_i_faixainiano
                                    );
 
    /* Verifica se a pessoa já podia ter tomado a vacina, ou seja, se a data atual
       é maior ou igual a data de início do periodo para a pessoa tomar a vacina */
    $aInicio     = explode('/', $dInicio);
    if(adodb_mktime(0, 0, 0, $aAtual[1], $aAtual[0], $aAtual[2]) 
       <= adodb_mktime(0, 0, 0, $aInicio[1], $aInicio[0], $aInicio[2])) {
      $sPassouInicio = 'false';
    } else {
      $sPassouInicio = 'true';
    }
 
    $aDadosVacinas[$iCont]->vc07_i_codigo  = $oDados->vc07_i_codigo;
    $aDadosVacinas[$iCont]->vc05_c_descr   = $oDados->vc05_c_descr;
    $aDadosVacinas[$iCont]->vc03_c_descr   = $oDados->vc03_c_descr;
    $aDadosVacinas[$iCont]->vc07_c_nome    = $oDados->vc07_c_nome;
    $aDadosVacinas[$iCont]->dataAplicacao  = $dDataAplicacao;
    $aDadosVacinas[$iCont]->obsAplicacao   = $sObsAplicacao;
    $aDadosVacinas[$iCont]->foraRede       = $sForaRede;
    $aDadosVacinas[$iCont]->vc16_i_usuario = "$iLogin";
    $aDadosVacinas[$iCont]->login          = $sLogin;
    $aDadosVacinas[$iCont]->vc16_i_codigo  = $iCodigoAplic;
    if ($oDados->vc07_i_faixafimdias == 0
        && $oDados->vc07_i_faixafimmes  == 0
        && $oDados->vc07_i_faixafimano  == 0) {
      $aDadosVacinas[$iCont]->periodo = "$dInicio - indefinida";
    } else {
      $aDadosVacinas[$iCont]->periodo = "$dInicio - $dVencimento";
    }
    $aDadosVacinas[$iCont]->passouinicio = $sPassouInicio;
 
  }

  return $aDadosVacinas;

}
function getHiperdiaCgs($iCgs, $iTipo, $dIni, $dFim) {

  $oDaoHiperdia = db_utils::getdao('far_cadacomppachiperdia');
  $sCampos  = " s152_i_codigo, s152_i_pressaosistolica, s152_i_pressaodiastolica, s152_i_cintura, s152_n_peso,";
  $sCampos .= " s152_i_altura, s152_i_glicemia, "; 
  $sCampos .= " case when s152_i_alimentacaoexameglicemia=0 then "; 
  $sCampos .= "   'Não informado' ";
  $sCampos .= " else case when s152_i_alimentacaoexameglicemia=1 then ";
  $sCampos .= "        'Em jejum' ";
  $sCampos .= "      else ";
  $sCampos .= "        'Pós prandial' ";
  $sCampos .= "      end ";
  $sCampos .= " end as s152_i_alimentacaoexameglicemia , ";
  $sCampos .= " z01_nome, ";
  $sCampos .= " s152_d_dataconsulta ";
  $sWhere   = ' fa50_i_cgsund = '.$iCgs; 
  if ($dIni != '') {
    $sWhere .= " and s152_d_dataconsulta >= '".$dIni."'";
  }
  if ($dFim != '') {
    $sWhere .= " and s152_d_dataconsulta <= '".$dFim."'";
  }
  $sSql     = $oDaoHiperdia->sql_query2(null, $sCampos, ' s152_d_dataconsulta desc ', $sWhere);
  $rs       = $oDaoHiperdia->sql_record($sSql);

  $aHiperdia = array();
  for ($iCont = 0; $iCont < $oDaoHiperdia->numrows; $iCont++) {

    $oDados                                             = db_utils::fieldsmemory($rs, $iCont);

    $aHiperdia[$iCont]->s152_i_codigo                   = $oDados->s152_i_codigo;
    $aHiperdia[$iCont]->s152_i_pressaosistolica         = $oDados->s152_i_pressaosistolica;
    $aHiperdia[$iCont]->s152_i_pressaodiastolica        = $oDados->s152_i_pressaodiastolica;
    $aHiperdia[$iCont]->s152_i_cintura                  = $oDados->s152_i_cintura;
    $aHiperdia[$iCont]->s152_n_peso                     = $oDados->s152_n_peso;
    $aHiperdia[$iCont]->s152_i_altura                   = $oDados->s152_i_altura;
    $aHiperdia[$iCont]->s152_i_glicemia                 = $oDados->s152_i_glicemia;
    $aHiperdia[$iCont]->s152_i_alimentacaoexameglicemia = $oDados->s152_i_alimentacaoexameglicemia;
    $aHiperdia[$iCont]->z01_nome                        = $oDados->z01_nome;
    $aHiperdia[$iCont]->s152_d_dataconsulta             = $oDados->s152_d_dataconsulta;
    
  }
  return $aHiperdia;

}
function getCidsCgs($iCgs, $iTipo, $dIni, $dFim) {

  $oDaoProntprocedcid = db_utils::getdao('prontprocedcid'); 
  
  $sCampos = "sd29_d_data, sd70_c_cid, sd70_c_nome";
  $sWhere  = " sd24_i_numcgs = $iCgs ";
  if ($dIni != '') {
    $sWhere .= " and sd29_d_data >= '".$dIni."'";
  }
  if ($dFim != '') {
    $sWhere .= " and sd29_d_data <= '".$dFim."'";
  }
  $sSql = $oDaoProntprocedcid->sql_query("", $sCampos, "sd29_d_data desc", $sWhere);
  $rs   = $oDaoProntprocedcid->sql_record($sSql);

  $aCids = array();
  for ($iCont = 0; $iCont < $oDaoProntprocedcid->numrows; $iCont++) {

    $oDados                     = db_utils::fieldsmemory($rs, $iCont);

    $aCids[$iCont]->sd29_d_data = $oDados->sd29_d_data;
    $aCids[$iCont]->sd70_c_cid  = $oDados->sd70_c_cid;
    $aCids[$iCont]->sd70_c_nome = $oDados->sd70_c_nome;
    
  }
  return $aCids;

}

function imprimirInfoCgs($oPdf, $oDados) {

  $lCor = false;
  $iTam = 5;
  $oPdf->setfont('arial', 'B', 11);
  $oPdf->setfillcolor(223);

  $oPdf->cell(190, 6, 'Informações do Cadastro Geral da Saúde do paciente', 0, 1, 'C', true);

  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(20, $iTam, 'CGS:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(75, $iTam, $oDados->z01_i_cgsund, 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(20, $iTam, 'Nome:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(75, $iTam, $oDados->z01_v_nome, 0, 1, 'L', $lCor);

  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(20, $iTam, 'CPF:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(75, $iTam, $oDados->z01_v_cgccpf, 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(20, $iTam, 'Identidade:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(75, $iTam, $oDados->z01_v_ident, 0, 1, 'L', $lCor);

  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(20, $iTam, 'Nascimento:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(75, $iTam, formataData($oDados->z01_d_nasc, 2), 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(20, $iTam, 'Estado civil:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(40, $iTam, estadoCivil($oDados->z01_i_estciv), 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(10, $iTam, 'Sexo:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(25, $iTam, $oDados->z01_v_sexo, 0, 1, 'L', $lCor);

  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(20, $iTam, 'Pai:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(75, $iTam, $oDados->z01_v_pai, 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(20, $iTam, 'Mãe:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(75, $iTam, $oDados->z01_v_mae, 0, 1, 'L', $lCor);

  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(20, $iTam, 'Endereço:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(75, $iTam, $oDados->z01_v_ender, 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(20, $iTam, 'Número:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(10, $iTam, $oDados->z01_i_numero, 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(25, $iTam, 'Complemento:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(40, $iTam, $oDados->z01_v_compl, 0, 1, 'L', $lCor);

  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(20, $iTam, 'Bairro:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(75, $iTam, $oDados->z01_v_bairro, 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(20, $iTam, 'Município:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(75, $iTam, $oDados->z01_v_munic, 0, 1, 'L', $lCor);

  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(20, $iTam, 'CEP:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(75, $iTam, $oDados->z01_v_cep, 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(20, $iTam, 'UF:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(75, $iTam, $oDados->z01_v_uf, 0, 1, 'L', $lCor);

  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(20, $iTam, 'Telefone:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(75, $iTam, $oDados->z01_v_telef, 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(20, $iTam, 'Celular:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(75, $iTam, $oDados->z01_v_telcel, 0, 1, 'L', $lCor);
  
}

function novoTituloCartaoSus($oPdf) {

  $lCor = false;
  $iTam = 10;
  $oPdf->setfont('arial', 'B', 11);
  
  $oPdf->cell(190, $iTam, 'Cartões SUS do Paciente', 0, 1, 'C', $lCor);

}

function novoTituloAgendamentos($oPdf) {

  $lCor = false;
  $iTam = 10;
  $oPdf->setfont('arial', 'B', 11);
  
  $oPdf->cell(190, $iTam, 'Agendamentos de Consulta', 0, 1, 'C', $lCor);
  
}

function novoTituloProntuarios($oPdf) {

  $lCor = false;
  $iTam = 10;
  $oPdf->setfont('arial', 'B', 11);
  
  $oPdf->cell(190, $iTam, 'Atendimentos Ambulatoriais', 0, 1, 'C', $lCor);

}

function novoTituloRetiradas($oPdf) {

  $lCor = false;
  $iTam = 10;
  $oPdf->setfont('arial', 'B', 11);
  
  $oPdf->cell(190, $iTam, 'Retiradas e Devoluções de Medicamentos', 0, 1, 'C', $lCor);

}

function novoTituloExames($oPdf) {

  $lCor = false;
  $iTam = 10;
  $oPdf->setfont('arial', 'B', 11);
  
  $oPdf->cell(190, $iTam, 'Exames Realizados', 0, 1, 'C', $lCor);
  
}

function novoTituloPedidosTfd($oPdf) {

  $lCor = false;
  $iTam = 10;
  $oPdf->setfont('arial', 'B', 11);
  
  $oPdf->cell(190, $iTam, 'Pedidos de Tratamento Fora de Domicílio', 0, 1, 'C', $lCor);

}

function novoTituloVacinas($oPdf) {

  $lCor = false;
  $iTam = 10;
  $oPdf->setfont('arial', 'B', 11);
  
  $oPdf->cell(190, $iTam, 'Vacinas', 0, 1, 'C', $lCor);

}
function novoTituloHiperdia($oPdf) {

  $lCor = false;
  $iTam = 10;
  $oPdf->setfont('arial', 'B', 11);
  
  $oPdf->cell(190, $iTam, 'Hiperdia', 0, 1, 'C', $lCor);

}
function novoTituloCids($oPdf) {

  $lCor = false;
  $iTam = 10;
  $oPdf->setfont('arial', 'B', 11);
  
  $oPdf->cell(190, $iTam, "CID's", 0, 1, 'C', $lCor);

}

function novoCabecalhoDocumentos($oPdf, $iTipo) {

  $lCor = true;
  $iTam = 6;
  $oPdf->setfont('arial', 'B', 11);
  $oPdf->setfillcolor(223);
  
  $oPdf->cell(190, $iTam, 'Documentos', 0, 1, 'C', $lCor);

}

function novoCabecalhoCartaoSus($oPdf, $iTipo) {

  $lCor = true;
  $iTam = 5;
  $oPdf->setfont('arial', 'B', 9);
  $oPdf->setfillcolor(223);
  
  $oPdf->cell(65, $iTam, '', 0, 0, 'C', false);
  $oPdf->cell(30, $iTam, 'Cartão SUS', 1, 0, 'C', $lCor);
  $oPdf->cell(30, $iTam, 'Tipo', 1, 0, 'C', $lCor);
  $oPdf->cell(65, $iTam, '', 0, 1, 'C', false);

}

function novoCabecalhoAgendamentos($oPdf, $iTipo) {

  $lCor = true;
  $iTam = 5;
  $oPdf->setfont('arial', 'B', 8);
  $oPdf->setfillcolor(223);
  
  $oPdf->cell(13, $iTam, 'Agenda', 1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, 'Usuário', 1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, 'Ficha', 1, 0, 'C', $lCor);
  $oPdf->cell(13, $iTam, 'Data', 1, 0, 'C', $lCor);
  $oPdf->cell(10, $iTam, 'Hora', 1, 0, 'C', $lCor);
  $oPdf->cell(33, $iTam, 'Profissional', 1, 0, 'C', $lCor);
  $oPdf->cell(18, $iTam, 'Situação', 1, 0, 'C', $lCor);
  $oPdf->cell(13, $iTam, 'Anulação', 1, 0, 'C', $lCor);
  $oPdf->cell(30, $iTam, 'Motivo', 1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, 'Usuário', 1, 1, 'C', $lCor);

}

function novoCabecalhoProntuarios($oPdf, $iTipo) {

  $lCor = true;
  $iTam = 5;
  $oPdf->setfont('arial', 'B', 8);
  $oPdf->setfillcolor(223);
  
  if ($iTipo == 1) {
    
    $oPdf->cell(20, $iTam, 'FAA', 1, 0, 'C', $lCor);
    $oPdf->cell(20, $iTam, 'Agenda', 1, 0, 'C', $lCor);
    $oPdf->cell(20, $iTam, 'Data', 1, 0, 'C', $lCor);
    $oPdf->cell(15, $iTam, 'Hora', 1, 0, 'C', $lCor);
    $oPdf->cell(115, $iTam, 'UPS', 1, 1, 'C', $lCor);
    $oPdf->cell(70, $iTam, 'Profissional', 1, 0, 'C', $lCor);
    $oPdf->cell(65, $iTam, 'Especialidade', 1, 0, 'C', $lCor);
    $oPdf->cell(20, $iTam, 'Usuário', 1, 0, 'C', $lCor);
    $oPdf->cell(20, $iTam, 'Data', 1, 0, 'C', $lCor);
    $oPdf->cell(15, $iTam, 'Hora', 1, 1, 'C', $lCor);

  } else {
    
    $oPdf->cell(145, $iTam, 'Procedimento', 1, 0, 'L', $lCor);
    $oPdf->cell(15, $iTam, 'Valor Unit.', 1, 0, 'C', $lCor);
    $oPdf->cell(10, $iTam, 'Qtde.', 1, 0, 'C', $lCor);
    $oPdf->cell(20, $iTam, 'Valor Total', 1, 1, 'C', $lCor);
    
  }
  
}

function novoCabecalhoRetiradas($oPdf, $iTipo) {

  $lCor = true;
  $iTam = 5;
  $oPdf->setfont('arial', 'B', 8);
  $oPdf->setfillcolor(223);

  $oPdf->cell(20, $iTam, 'Data', 1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, 'Tipo', 1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, 'Código', 1, 0, 'R', $lCor);
  $oPdf->cell(110, $iTam, 'Medicamento', 1, 0, 'L', $lCor);
  $oPdf->cell(20, $iTam, 'Quantidade', 1, 1, 'C', $lCor);
  $oPdf->cell(20, $iTam, 'Lote', 1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, 'Validade', 1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, 'Requisição', 1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, 'Devolução', 1, 0, 'C', $lCor);
  $oPdf->cell(80, $iTam, 'Motivo', 1, 0, 'C', $lCor);
  $oPdf->cell(30, $iTam, 'Usuário', 1, 1, 'C', $lCor);
  
}

function novoCabecalhoExames($oPdf, $iTipo) {

  $lCor = true;
  $iTam = 5;
  $oPdf->setfont('arial', 'B', 8);
  $oPdf->setfillcolor(223);
  
  if ($iTipo == 1) {
    
    $oPdf->cell(20, $iTam, 'Data', 1, 0, 'C', $lCor);
    $oPdf->cell(90, $iTam, 'Laboratório', 1, 0, 'C', $lCor);
    $oPdf->cell(40, $iTam, 'Exame', 1, 0, 'C', $lCor);
    $oPdf->cell(20, $iTam, 'Coleta', 1, 0, 'C', $lCor);
    $oPdf->cell(20, $iTam, 'Entrega', 1, 1, 'C', $lCor);
    
  } else {
   
    $oPdf->cell(145, $iTam, 'Procedimento', 1, 0, 'L', $lCor);
    $oPdf->cell(15, $iTam, 'Valor Unit.', 1, 0, 'C', $lCor);
    $oPdf->cell(10, $iTam, 'Qtde.', 1, 0, 'C', $lCor);
    $oPdf->cell(20, $iTam, 'Valor Total', 1, 1, 'C', $lCor);
    
  }
}

function novoCabecalhoPedidosTfd($oPdf, $iTipo) {

  $lCor = true;
  $iTam = 5;
  $oPdf->setfont('arial', 'B', 8);
  $oPdf->setfillcolor(223);

  $oPdf->cell(15, $iTam, 'Pedido', 1, 0, 'C', $lCor);
  $oPdf->cell(15, $iTam, 'Data', 1, 0, 'C', $lCor);
  $oPdf->cell(75, $iTam, 'Prestadora', 1, 0, 'C', $lCor);
  $oPdf->cell(50, $iTam, 'Cidade', 1, 0, 'C', $lCor);
  $oPdf->cell(15, $iTam, 'Saída', 1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, 'Situação', 1, 1, 'C', $lCor);

}

function novoCabecalhoVacinas($oPdf, $iTipo) {

  $lCor = true;
  $iTam = 5;
  $oPdf->setfont('arial', 'B', 8);
  $oPdf->setfillcolor(223);
  
  $oPdf->cell(30, $iTam, 'Calendário', 1, 0, 'C', $lCor);
  $oPdf->cell(30, $iTam, 'Vacina', 1, 0, 'C', $lCor);
  $oPdf->cell(30, $iTam, 'Dose', 1, 0, 'C', $lCor);
  $oPdf->cell(35, $iTam, 'Período de Aplicação', 1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, 'Aplicação', 1, 0, 'C', $lCor);
  $oPdf->cell(45, $iTam, 'Observação', 1, 1, 'C', $lCor);

}

function novoCabecalhoHiperdia($oPdf, $iTipo) {

  $lCor = true;
  $iTam = 5;
  $oPdf->setfont('arial', 'B', 8);
  $oPdf->setfillcolor(223);
  
  $oPdf->cell(10, $iTam, 'Cod', 1, 0, 'C', $lCor);
  $oPdf->cell(14, $iTam, 'Sistólica', 1, 0, 'C', $lCor);
  $oPdf->cell(15, $iTam, 'Diastólica', 1, 0, 'C', $lCor);
  $oPdf->cell(13, $iTam, 'Cintura', 1, 0, 'C', $lCor);
  $oPdf->cell(10, $iTam, 'Peso', 1, 0, 'C', $lCor);
  $oPdf->cell(10, $iTam, 'Altura', 1, 0, 'C', $lCor);
  $oPdf->cell(27, $iTam, 'Glicemica (MG/D)', 1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, 'Alimentação', 1, 0, 'C', $lCor);
  $oPdf->cell(50, $iTam, 'Profissional', 1, 0, 'C', $lCor);
  $oPdf->cell(15, $iTam, 'Consulta', 1, 1, 'C', $lCor);
  
}

function novoCabecalhoCids($oPdf, $iTipo) {

  $lCor = true;
  $iTam = 5;
  $oPdf->setfont('arial', 'B', 8);
  $oPdf->setfillcolor(223);
  
  $oPdf->cell(40, $iTam, '', 0, 0, 'C', false);
  $oPdf->cell(20, $iTam, 'Atendimento', 1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, 'CID', 1, 0, 'C', $lCor);
  $oPdf->cell(80, $iTam, 'Descrição', 1, 0, 'C', $lCor);
  $oPdf->cell(40, $iTam, '', 0, 1, 'C', false);
  
}

function novaLinhaDocumentos($oPdf, $oDados, $iTipo) {

  $lCor = false;
  $iTam = 5;

  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(32, $iTam, 'PIS/PASSEP:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(63, $iTam, $oDados->z01_c_pis, 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(32, $iTam, 'UF:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(63, $iTam, $oDados->z01_v_uf, 0, 1, 'L', $lCor);

  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(32, $iTam, 'Naturalidade:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(63, $iTam, $oDados->z01_c_naturalidade, 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(32, $iTam, 'Escolaridade:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(63, $iTam, $oDados->z01_c_escolaridade, 0, 1, 'L', $lCor);

  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(32, $iTam, 'Identidade:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(63, $iTam, $oDados->z01_v_ident, 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(32, $iTam, 'Data de Emissão:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(28, $iTam, formataData($oDados->z01_d_dtemissao, 2), 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(7, $iTam, 'UF:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(28, $iTam, $oDados->z01_c_ufident, 0, 1, 'L', $lCor);

  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(32, $iTam, 'CNH:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(20, $iTam, $oDados->z01_v_cnh, 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(17, $iTam, 'Categoria:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(26, $iTam, $oDados->z01_v_categoria, 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(32, $iTam, 'Data de Emissão:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(63, $iTam, formataData($oDados->z01_d_dtemissaocnh, 2), 0, 1, 'L', $lCor);

  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(32, $iTam, 'Data de Habilitação:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(63, $iTam, formataData($oDados->z01_d_dthabilitacao, 2), 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(32, $iTam, 'Data de Vencimento:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(63, $iTam, formataData($oDados->z01_d_dtvencimento, 2), 0, 1, 'L', $lCor);

  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(32, $iTam, 'Número CTPS:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(20, $iTam, $oDados->z01_c_numctps, 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(20, $iTam, 'Série CTPS:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(23, $iTam, $oDados->z01_c_seriectps, 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(32, $iTam, 'Data de Emissão:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(28, $iTam, formataData($oDados->z01_d_dtemissaoctps, 2), 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(7, $iTam, 'UF:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(28, $iTam, $oDados->z01_c_ufctps, 0, 1, 'L', $lCor);

  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(32, $iTam, 'Tipo de Certidão:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(63, $iTam, $oDados->z01_c_certidaotipo, 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(32, $iTam, 'Data de Emissão:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(63, $iTam, formataData($oDados->z01_c_certidaodata, 2), 0, 1, 'L', $lCor);

  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(32, $iTam, 'Livro:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(63, $iTam, $oDados->z01_c_certidaolivro, 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(32, $iTam, 'Folha:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(63, $iTam, $oDados->z01_c_certidaofolha, 0, 1, 'L', $lCor);

  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(32, $iTam, 'Cartório:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(63, $iTam, $oDados->z01_c_certidaocart, 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(32, $iTam, 'Termo:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(63, $iTam, $oDados->z01_c_certidaotermo, 0, 1, 'L', $lCor);

  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(32, $iTam, 'Banco:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(63, $iTam, $oDados->z01_c_banco, 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(32, $iTam, 'Conta:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(28, $iTam, $oDados->z01_c_conta, 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(15, $iTam, 'Agência:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(20, $iTam, $oDados->z01_c_agencia, 0, 1, 'L', $lCor);
  
  return false;

}

function novaLinhaCartaoSus($oPdf, $oDados, $iTipo) {

  $lCor = false;
  $iTam = 5;
  $oPdf->setfont('arial', '', 8);

  $sTipo = $oDados->s115_c_tipo == 'D' ? 'Definitivo' : 'Provisório';

  $oPdf->cell(65, $iTam, '', 0, 0, 'C', false);
  $oPdf->cell(30, $iTam, $oDados->s115_c_cartaosus, 1, 0, 'C', $lCor);
  $oPdf->cell(30, $iTam, $sTipo, 1, 0, 'C', $lCor);
  $oPdf->cell(65, $iTam, '', 0, 1, 'C', false);
  
  return false;

}

function novaLinhaAgendamentos($oPdf, $oDados, $iTipo) {

  $lCor = false;
  $iTam = 5;
  $oPdf->setfont('arial', '', 6);

  $oPdf->cell(13, $iTam, formataData($oDados->sd23_d_agendamento, 2), 1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, $oDados->login, 1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, $oDados->sd101_c_descr, 1, 0, 'C', $lCor);
  $oPdf->cell(13, $iTam, formataData($oDados->sd23_d_consulta, 2), 1, 0, 'C', $lCor);
  $oPdf->cell(10, $iTam, $oDados->sd23_c_hora, 1, 0, 'C', $lCor);
  $oPdf->cell(33, $iTam, substr($oDados->sd03_i_codigo.' - '.$oDados->z01_nome, 0, 27), 1, 0, 'C', $lCor);
  $oPdf->cell(18, $iTam, $oDados->situacao, 1, 0, 'C', $lCor);
  $oPdf->cell(13, $iTam, formataData($oDados->data_anulacao, 2), 1, 0, 'C', $lCor);
  $oPdf->cell(30, $iTam, $oDados->motivo_anulacao, 1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, $oDados->usuario_anulacao, 1, 1, 'C', $lCor);
  
  return false;

}

function novaLinhaProntuarios($oPdf, $oDados, $iTipo) {

  global $iCor;
  $lCor = true;
  $iTam = 5;
  $oPdf->setfont('arial', '', 7);
  
  if ($iCor == 0) {

    $oPdf->setfillcolor(255, 255, 210);
    $iCor = 1;

  } else {

    $oPdf->setfillcolor(254, 254, 255);
    $iCor = 0;

  }

  if ($iTipo == 1) {
    
    $oPdf->cell(20, $iTam, $oDados->sd24_i_codigo, 1, 0, 'C', $lCor);
    $oPdf->cell(20, $iTam, $oDados->s102_i_agendamento, 1, 0, 'C', $lCor);
    $oPdf->cell(20, $iTam, formataData($oDados->sd29_d_data, 2), 1, 0, 'C', $lCor);
    $oPdf->cell(15, $iTam, $oDados->sd29_c_hora, 1, 0, 'C', $lCor);
    $oPdf->cell(115, $iTam, $oDados->coddepto.' - '.$oDados->descrdepto, 1, 1, 'C', $lCor);
    $oPdf->cell(70, $iTam, $oDados->sd03_i_codigo.' - '.$oDados->z01_nome, 1, 0, 'C', $lCor);
    $oPdf->cell(65, $iTam, $oDados->rh70_estrutural.' - '.$oDados->rh70_descr, 1, 0, 'C', $lCor);
    $oPdf->cell(20, $iTam, $oDados->login, 1, 0, 'C', $lCor);
    $oPdf->cell(20, $iTam, formataData($oDados->sd29_d_cadastro, 2), 1, 0, 'C', $lCor);
    $oPdf->cell(15, $iTam, $oDados->sd29_c_cadastro, 1, 1, 'C', $lCor);

  } else {
    
    $oPdf->cell(145, $iTam, $oDados->sd63_c_nome." - ".$oDados->sd63_c_procedimento, 1, 0, 'L', $lCor);
    $oPdf->cell(15, $iTam, number_format((double)$oDados->valor_unitario, 2, ',','.'), 1, 0, 'R', $lCor);
    $oPdf->cell(10, $iTam, $oDados->quantidade, 1, 0, 'R', $lCor);
    $oPdf->cell(20, $iTam, number_format((double)$oDados->valor_total, 2, ',','.'), 1, 1, 'R', $lCor);
    
    return array($oDados->quantidade, $oDados->valor_total);
    
  }
  
  return false;
}

function novaLinhaRetiradas($oPdf, $oDados, $iTipo) {

  global $iCor;
  
  $lCor = true;
  $iTam = 5;
  $oPdf->setfont('arial', '', 7);

  if ($iCor == 0) {

    $oPdf->setfillcolor(255, 255, 210);
    $iCor = 1;

  } else {

    $oPdf->setfillcolor(254, 254, 255);
    $iCor = 0;

  }

  $oPdf->cell(20, $iTam, formataData($oDados->fa04_d_data, 2), 1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, $oDados->stipo, 1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, $oDados->fa01_i_codigo, 1, 0, 'R', $lCor);
  $oPdf->cell(110, $iTam, $oDados->m60_descr, 1, 0, 'L', $lCor);
  $oPdf->cell(20, $iTam, $oDados->fa06_f_quant, 1, 1, 'C', $lCor);
  $oPdf->cell(20, $iTam, $oDados->m77_lote, 1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, formataData($oDados->m77_dtvalidade, 2), 1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, $oDados->fa07_i_matrequi, 1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, formataData($oDados->fa22_d_data, 2), 1, 0, 'C', $lCor);
  $oPdf->cell(80, $iTam, $oDados->fa23_c_motivo, 1, 0, 'C', $lCor);
  $oPdf->cell(30, $iTam, $oDados->login, 1, 1, 'C', $lCor);
  
  return false;

}

function novaLinhaExames($oPdf, $oDados, $iTipo) {

  $lCor = false;
  $iTam = 5;
  $oPdf->setfont('arial', '', 7);
  if ($iTipo == 1) {
  
    $oPdf->cell(20, $iTam, formataData($oDados->la21_d_data, 2), 1, 0, 'C', $lCor);
    $oPdf->cell(90, $iTam, $oDados->la02_c_descr, 1, 0, 'C', $lCor);
    $oPdf->cell(40, $iTam, $oDados->la08_c_descr, 1, 0, 'C', $lCor);
    $oPdf->cell(20, $iTam, formataData($oDados->la32_d_data, 2), 1, 0, 'C', $lCor);
    $oPdf->cell(20, $iTam, formataData($oDados->la31_d_data, 2), 1, 1, 'C', $lCor);
  
  } else {
   
    $oPdf->cell(145, $iTam, $oDados->sd63_c_nome." - ".$oDados->sd63_c_procedimento, 1, 0, 'L', $lCor);
    $oPdf->cell(15, $iTam, number_format((double)$oDados->valor_unitario,2,',','.'), 1, 0, 'R', $lCor);
    $oPdf->cell(10, $iTam, $oDados->quantidade, 1, 0, 'R', $lCor);
    $oPdf->cell(20, $iTam, number_format((double)$oDados->valor_total,2,',','.'), 1, 1, 'R', $lCor);
      
    return array($oDados->quantidade, $oDados->valor_total);
    
  }
  
  return false;

}

function novaLinhaPedidosTfd($oPdf, $oDados, $iTipo) {

  $lCor = false;
  $iTam = 5;
  $oPdf->setfont('arial', '', 7);

  $oPdf->cell(15, $iTam, $oDados->tf01_i_codigo, 1, 0, 'C', $lCor);
  $oPdf->cell(15, $iTam, formataData($oDados->tf01_d_datapedido, 2), 1, 0, 'C', $lCor);
  $oPdf->cell(75, $iTam, $oDados->z01_nomeprestadora, 1, 0, 'C', $lCor);
  $oPdf->cell(50, $iTam, $oDados->tf03_c_descr, 1, 0, 'C', $lCor);
  $oPdf->cell(15, $iTam, formataData($oDados->tf17_d_datasaida, 2), 1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, $oDados->tf26_c_descr, 1, 1, 'C', $lCor);
  
  return false;
  
}

function novaLinhaVacinas($oPdf, $oDados, $iTipo) {

  $lCor = false;
  $iTam = 5;
  $oPdf->setfont('arial', '', 7);

  $oPdf->cell(30, $iTam, $oDados->vc05_c_descr, 1, 0, 'C', $lCor);
  $oPdf->cell(30, $iTam, substr($oDados->vc07_c_nome,0,20), 1, 0, 'C', $lCor);
  $oPdf->cell(30, $iTam, $oDados->vc03_c_descr, 1, 0, 'C', $lCor);
  $oPdf->cell(35, $iTam, $oDados->periodo, 1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, formataData($oDados->dataAplicacao, 2), 1, 0, 'C', $lCor);
  $oPdf->cell(45, $iTam, $oDados->obsAplicacao, 1, 1, 'C', $lCor);
  
  return false;
  
}

function novaLinhaHiperdia($oPdf, $oDados, $iTipo) {

  $lCor = false;
  $iTam = 5;
  $oPdf->setfont('arial', '', 7);
  $oPdf->cell(10, $iTam, $oDados->s152_i_codigo, 1, 0, 'C', $lCor);
  $oPdf->cell(14, $iTam, $oDados->s152_i_pressaosistolica, 1, 0, 'R', $lCor);
  $oPdf->cell(15, $iTam, $oDados->s152_i_pressaodiastolica, 1, 0, 'R', $lCor);
  $oPdf->cell(13, $iTam, $oDados->s152_i_cintura, 1, 0, 'R', $lCor);
  $oPdf->cell(10, $iTam, $oDados->s152_n_peso, 1, 0, 'R', $lCor);
  $oPdf->cell(10, $iTam, $oDados->s152_i_altura, 1, 0, 'R', $lCor);
  $oPdf->cell(27, $iTam, $oDados->s152_i_glicemia, 1, 0, 'R', $lCor);
  $oPdf->cell(20, $iTam, $oDados->s152_i_alimentacaoexameglicemia, 1, 0, 'C', $lCor);
  $oPdf->cell(50, $iTam, $oDados->z01_nome, 1, 0, 'C', $lCor);
  $oPdf->cell(15, $iTam, $oDados->s152_d_dataconsulta, 1, 1, 'C', $lCor);

  return false;
  
}

function novaLinhaCids($oPdf, $oDados, $iTipo) {

  $lCor = false;
  $iTam = 5;
  $oPdf->setfont('arial', '', 7);
  $oPdf->cell(40, $iTam, '', 0, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, $oDados->sd29_d_data, 1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, $oDados->sd70_c_cid, 1, 0, 'C', $lCor);
  $oPdf->cell(80, $iTam, $oDados->sd70_c_nome, 1, 0, 'C', $lCor);
  $oPdf->cell(40, $iTam, '', 0, 1, 'C', $lCor);
  
  return false;
  
}

function subTotalDocumentos($oPdf, $aSubtotal, $iTipo) {
  return false;
}
function subTotalCartaoSus($oPdf, $aSubtotal, $iTipo) {
  return false;
}
function subTotalAgendamentos($oPdf, $aSubtotal, $iTipo) {
  return false;
}
function subTotalProntuarios($oPdf, $aSubtotal, $iTipo) {
  
  if ($iTipo == 2) {
     
    $lCor = true;
    $iTam = 5;
    $oPdf->setfont('arial', 'B', 7);
    $oPdf->setfillcolor(223);
    
    $oPdf->cell(160, $iTam, "Total:", 1, 0, 'R', $lCor);
    $oPdf->cell(10, $iTam, $aSubtotal[0], 1, 0, 'R', $lCor);
    $oPdf->cell(20, $iTam, number_format($aSubtotal[1],2,',','.'), 1, 1, 'R', $lCor);
    
  }
   
}
function subTotalRetiradas($oPdf, $aSubtotal, $iTipo) {
  return false;
}
function subTotalExames($oPdf, $aSubtotal, $iTipo) {
  subTotalProntuarios($oPdf, $aSubtotal, $iTipo); 
}
function subTotalPedidosTfd($oPdf, $aSubtotal, $iTipo) {
  return false;
}
function subTotalVacinas($oPdf, $aSubtotal, $iTipo) {
  return false;
}
function subTotalHiperdia($oPdf, $aSubtotal, $iTipo) {
  return false;
}
function subTotalCids($oPdf, $aSubtotal, $iTipo) {
  return false;
}


function formataData($dData, $iTipo = 1) {
  
  if (empty($dData)) {
    return '';
  }

  if ($iTipo == 1) {

    $dData = explode('/', $dData);
    $dData = $dData[2].'-'.$dData[1].'-'.$dData[0];
    return $dData;
  
  }
 
  $dData = explode('-', $dData);
  $dData = @$dData[2].'/'.@$dData[1].'/'.@$dData[0];
  return $dData;

}

function estadoCivil($iCodigo) {

  switch ($iCodigo) {

    case 0:
      return 'Não informado';

    case 1:
      return 'Solteiro.';

    case 2:
      return 'Casado';

    case 3:
      return 'Viúvo';

    case 4:
      return 'Separado';

    case 5:
      return 'União C.';

    case 9:
      return 'Ignorado';

    default:
      return '';

  }

}

function imprimirOpcao($iOpcao, $iCgs, $oPdf, $iTipo, $dIni, $dFim) {

  $iLinhas    = 0; // Número de linhas do resultado da busca
  $sFinalFunc = ''; // final do nome das funcoes necessárias para geração do relatório
  switch ($iOpcao) {

    case 1: // Imprimir os documentos
     
      $sFinalFunc = 'Documentos';
      break;

    case 2: // Imprimir todos os cartoes sus do paciente
     
      $sFinalFunc = 'CartaoSus';
      break;

    case 3: // Imprimir os agendamentos
      
       $sFinalFunc = 'Agendamentos';
       break;
 
    case 4: // Imprimir imprimir os prontuários
      
       $sFinalFunc = 'Prontuarios';
       break;
 
    case 5: // Imprimir as retiradas de medicamentos
      
       $sFinalFunc = 'Retiradas';
       break;
 
    case 6: // Imprimir os exames realizados
     
      $sFinalFunc = 'Exames';
      break;
 
    case 7: // Imprimir os pedidos de TFD
      
       $sFinalFunc = 'PedidosTfd';
       break;
 
    case 8: // Imprimir as vacinas
      
       $sFinalFunc = 'Vacinas';
       break;
   
    case 9: // Imprimir as Hiperdia 
      
       $sFinalFunc = 'Hiperdia';
       break;
       
    case 10: // Imprimir os CID's
      
       $sFinalFunc = 'Cids';
       break;
       
    default: // Nenhuma opção válida
       
       return false;

  }

  $oPdf->Addpage('P');

  $aDados  = call_user_func('get'.$sFinalFunc.'Cgs', $iCgs, $iTipo, formataData($dIni), formataData($dFim));
  $iLinhas = count($aDados);

  if ($iOpcao != 1) { // Nos documentos, o cabecalho já funciona como título
    call_user_func('novoTitulo'.$sFinalFunc, $oPdf);
  }
  call_user_func('novoCabecalho'.$sFinalFunc, $oPdf, $iTipo);
  $aSubtotal = array();
  for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

    if ($oPdf->getY() >$oPdf->h - 30) {

      $oPdf->Addpage('P');
      call_user_func('novoCabecalho'.$sFinalFunc, $oPdf, $iTipo);

    }
    $aSubtotalLinha = call_user_func('novaLinha'.$sFinalFunc, $oPdf, $aDados[$iCont], $iTipo);
    if ($aSubtotalLinha != false) {
      $aSubtotal = arraySumValues($aSubtotal, $aSubtotalLinha);
    }

  }
  if ($iLinhas > 0) {
    call_user_func('subTotal'.$sFinalFunc, $oPdf, $aSubtotal, $iTipo);
  }
  
}

/* Final do bloco de funções ****/


empty($iCgs) ? die('<center><big><b>CGS não informado!</b></big></center>') : '';
$aOpcoes   = explode(',', $sOpcoesImp);

$aDadosCgs = getInfoCgs($iCgs);
$iLinhas   = count($aDadosCgs);

$lErroVac  = false;
$sMsgErro  = 'CGS não encontrado.';
// Se o CGS não tiver data de nascimento, não pode ser selecionado para imprimir as vacinas
if ($iLinhas > 0 && empty($aDadosCgs[0]->z01_d_nasc) && in_array(8, $aOpcoes)) { 

  $lErroVac = true;
  $sMsgErro  = 'O CGS não possui data de nascimento cadastrada, então, as vacinas não podem ser impressas.';

}

if ($iLinhas == 0 || $lErroVac) {
?>
  <table width='100%'>
    <tr>
      <td align='center'>
        <font color='#FF0000' face='arial'>
          <b><?=$sMsgErro?><br><br>
            <input type='button' value='Fechar' onclick='window.close()'>
          </b>
        </font>
      </td>
    </tr>
  </table>
<?
  exit;
}

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();

if ($iTipo == 1) {
  $head2 = "Tipo: Analítico";
} else {
  $head2 = "Tipo: Sintético";
}
$head3 = "";
if (isset($dIni)) {
  $head3 .= " Periodo: ".$dIni;
} else {
  $dIni = "";
}
if (isset($dFim)) {
  if($head3 == ""){
    $head3 = " Periodo: ";
  }
  $head3 .= " até ".$dFim;
} else {
  $dFim = "";
}

$head1 = 'Consulta Geral da Saúde';

$oPdf->Addpage('P'); // L deitado

imprimirInfoCgs($oPdf, $aDadosCgs[0]);

$iCor = 0; // Determina a cor de preenchimento, quando usada mais de uma mescladamente
$iTam = count($aOpcoes);
for ($iCont = 0; $iCont < $iTam; $iCont++) {
  imprimirOpcao($aOpcoes[$iCont], $iCgs, $oPdf, $iTipo, $dIni, $dFim);
}

$oPdf->Output();
?>