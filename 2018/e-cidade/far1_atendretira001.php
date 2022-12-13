<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require("fpdf151/scpdf.php");
require("libs/db_utils.php");
require("libs/db_stdlibwebseller.php");
include("fpdf151/impfarmacia.php");
include("libs/db_sql.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$sErro                       = "";
$dHoje                       = date("Y-m-d",db_getsession("DB_datausu"));
$oDaoFarRetirada             = db_utils::getdao('far_retirada');
$oDaoFarRetiradarequisitante = db_utils::getdao('far_retiradarequisitante');
$oDaoDbConfig                = db_utils::getdao('db_config');
$sWhere                      = "";

if (isset($ini) && trim($ini)!="") {
  $sWhere = " fa04_i_codigo >= $ini";
}
if (isset($fim) && trim($fim)!="") {

  if ($sWhere == "") {
    $sWhere = " fa04_i_codigo <= $fim";
  } else {
    $sWhere = " fa04_i_codigo between $ini and $fim";
  }
  
}

//Informações da farmacia
$oConfigFarmacia = loadConfig('far_parametros');
if ($oConfigFarmacia == null) {
  $lErro = "Erro ao selecionar informações da farmacia!";
} else {
 
  if ((int)$oConfigFarmacia->fa02_i_acumularsaldocontinuado == 1) {
    $lAcumularSaldo = 'true';
  } else {
    $lAcumularSaldo = 'false';  
  }
  
}

//informaçãos da Prefeitura
if ($sErro == "") {
	
  $sSql   = $oDaoDbConfig->sql_query_file(db_getsession("DB_instit"));
  $rsPref = $oDaoDbConfig->sql_record($sSql);
  if ($oDaoDbConfig->numrows > 0) {
    $oDadosPref = db_utils::fieldsmemory($rsPref, 0);
  } else {  
    $sErro = "Erro ao selecionar informações da prefeitura!"; 
  }
  
}

//Informações da retirada (Unicas)   !incluir requisitante aqui
if ($sErro == "") {

  $sCampos    = " distinct on (fa04_i_codigo) ";
  $sCampos   .= "fa04_i_codigo        as codigo,";
  $sCampos   .= "fa04_d_data          as data,";
  $sCampos   .= "fa04_i_cgsund        as paciente_cgs,";
  $sCampos   .= "cgs_und.z01_v_nome   as paciente_nome,";
  $sCampos   .= "descrdepto           as departamento_nome,";
  $sCampos   .= "fa04_c_numeroreceita as receita_numero,";
  $sCampos   .= "fa04_d_dtvalidade    as receita_validade,";
  $sCampos   .= "fa03_c_descr         as receita_tipo,";
  $sCampos   .= "fa04_i_dbusuario     as usuario_codigo,";
  $sCampos   .= "nome                 as usuario_nome,";
  $sCampos   .= "fa04_i_profissional  as prof_codigo,";
  $sCampos   .= 'case ';
  $sCampos   .= '  when sd03_i_tipo = 1 ';
  $sCampos   .= '    then cgm.z01_nome ';
  $sCampos   .= '  else ';
  $sCampos   .= '    s154_c_nome ';
  $sCampos   .= 'end                  as prof_nome, ';
  $sCampos   .= 'medicos.sd03_i_crm   as prof_crm, ';
  $sCampos   .= 'case ';
  $sCampos   .= '  when sd03_i_tipo = 1 ';
  $sCampos   .= '    then cgmdoc.z02_i_cns ';
  $sCampos   .= '  else ';
  $sCampos   .= '    s154_c_cns ';
  $sCampos   .= 'end                  as prof_cns, ';
  $sCampos   .= 'm40_codigo           as material_requicodigo,';
  $sCampos   .= 'm40_hora             as material_requihora,';
  $sCampos   .= 'm42_codigo           as material_atendirequi,';
  $sCampos   .= 'a.z01_i_cgsund       as requisitante1_cgs,';
  $sCampos   .= 'a.z01_v_nome         as requisitante1_nome,';
  $sCampos   .= 'a.z01_v_ident        as requisitante1_rg,';
  $sCampos   .= 'a.z01_v_ender        as requisitante1_ender,';
  $sCampos   .= 'a.z01_i_numero       as requisitante1_numero,';
  $sCampos   .= 'fa39_c_nome          as requisitante2_nome,';
  $sCampos   .= 'fa39_i_ident         as requisitante2_rg,';
  $sCampos   .= 'fa39_c_ender         as requisitante2_ender,';
  $sCampos   .= 'fa39_i_numero        as requisitante2_numero';
  
  $sSql       = $oDaoFarRetirada->sql_query_geral("",$sCampos,"",$sWhere);
  $rsRetirada = $oDaoFarRetirada->sql_record($sSql);
  if ($oDaoFarRetirada->numrows == 0) {
    $sErro = " Erro ao selecionar informações da requisição! ";
  } else {
    $iLinhasRetirada = $oDaoFarRetirada->numrows;	
  }
   
}

//Informações dos itens da retirada
if ($sErro == "") {

  $sCampos  = " distinct ";
  $sCampos .= "fa04_i_codigo    as retirada_codigo,";
  $sCampos .= "m60_codmater     as codigo,";
  $sCampos .= "m60_descr        as nome,";
  $sCampos .= "c.m61_abrev      as unidade,";
  $sCampos .= "fa06_f_quant     as quantidade,";
  $sCampos .= "fa06_t_posologia as posologia,";
  $sCampos .= "' '              as localizacao,";
  if (isset($iTipoRetirada) && $iTipoRetirada == 1) {
    
    $sCampos .= "  fc_infolotesrequisicao(m41_codigo::integer)||' '";
    $sCampos .= "||coalesce(m41_obs,' ') as lote";
    $sTipo    = "Normal";  
    
  } else {
  	
    $sCampos .= "  ' ' as lote";
    $sTipo    = "Não Padronizada";
    
  }
  $sSql    = $oDaoFarRetirada->sql_query_geral(null,$sCampos,'fa04_i_codigo',$sWhere);
  $rsItens = $oDaoFarRetirada->sql_record($sSql);
  if ($oDaoFarRetirada->numrows == 0) {
    $sErro = " Erro ao selecionar os itens da requisição! ";
  } else {
    $iLinhasItens = $oDaoFarRetirada->numrows; 
  }
  
}
if ($sErro != "") {
  db_redireciona("db_erros.php?fechar=true&db_erro=$sErro");
} 

$pdf  = new scpdf();
$pdf->Open();
$pdf1 = new db_impcarne($pdf,888);
$pdf1->objpdf->SetTextColor(0,0,0);
$pdf1->Snumero_ant = '';
$pdf1->Rresumo     = '';

for ($iCont = 0; $iCont < $iLinhasRetirada; $iCont++) {

  $oDadosRetirada = db_utils::fieldsmemory($rsRetirada, $iCont);
  
  //Informações da Prefeitura
  $pdf1->logo          = $oDadosPref->logo; 
  $pdf1->prefeitura    = $oDadosPref->nomeinst;
  $pdf1->enderpref     = $oDadosPref->ender;
  $pdf1->municpref     = $oDadosPref->munic;
  $pdf1->telefpref     =  $oDadosPref->telef;
  $pdf1->emailpref     = $oDadosPref->email;
  $pdf1->emissao       = date("Y-m-d",db_getsession("DB_datausu"));
  $pdf1->cgcpref       = $oDadosPref->cgc;
  
  //Informações da retirada
  $pdf1->Rnumero            = $oDadosRetirada->codigo;
  $pdf1->Rtipo              = $sTipo;
  $pdf1->Ratendrequi        = $oDadosRetirada->material_atendirequi;
  $pdf1->Rdepart            = $oDadosRetirada->departamento_nome;
  $pdf1->Rdata              = $oDadosRetirada->data;
  $pdf1->ratendente         = $oDadosRetirada->usuario_codigo;
  $pdf1->rcodatend          = $oDadosRetirada->usuario_nome;
  $pdf1->rcodprof           = $oDadosRetirada->prof_codigo;
  $pdf1->rprofissional      = $oDadosRetirada->prof_nome;
  $pdf1->Rcrm               = $oDadosRetirada->prof_crm;
  $pdf1->Rcns               = $oDadosRetirada->prof_cns;
  $pdf1->Rhora              = $oDadosRetirada->material_requihora;
  $pdf1->Rnomeus            = $oDadosRetirada->paciente_cgs." ".$oDadosRetirada->paciente_nome;
  $pdf1->Rreceita           = $oDadosRetirada->receita_numero;
  $pdf1->Rdtvalidadereceita = $oDadosRetirada->receita_validade;
  $pdf1->Rtpreceita         = $oDadosRetirada->receita_tipo;
  // Requisitante
  if ($oDadosRetirada->requisitante1_nome != "") {
      
       $pdf1->Rrequisitante = $oDadosRetirada->requisitante1_cgs." ".$oDadosRetirada->requisitante1_nome;
       $pdf1->Rident        = $oDadosRetirada->requisitante1_rg;
       $pdf1->Rendereco     = $oDadosRetirada->requisitante1_ender;
       $pdf1->Rnumeros      = $oDadosRetirada->requisitante1_numero;
       
  } else {
  
    if ($oDadosRetirada->requisitante2_nome != "") {
        
       $pdf1->Rrequisitante = $oDadosRetirada->requisitante2_nome;
       $pdf1->Rident        = $oDadosRetirada->requisitante2_rg;
       $pdf1->Rendereco     = $oDadosRetirada->requisitante2_ender;
       $pdf1->Rnumeros      = $oDadosRetirada->requisitante2_numero;
        
    }
    
  }
  
  //inicializa array que armazena a proxima data dos continuados
  $aProxDisp = Array();
  
  //Percorre os itens 
  for ($iContItens = 0; $iContItens < $iLinhasItens; $iContItens++) {
     
    $oDadosItens = db_utils::fieldsmemory($rsItens, $iContItens);
    
    //Agrupa a posologia no campos resumo (unico)
    $pdf1->Rresumo .= ' '.$oDadosItens->codigo.') '.$oDadosItens->posologia;
    
    //calcula a proxima data de retirada se o medicamento for continuado
    if ((int)$oConfigFarmacia->fa02_i_tipoperiodocontinuado == 1) {
    	
      $sSqlFunc  = "select fc_saldocontinuado_periodo_fixo(".$oDadosRetirada->paciente_cgs.",";
      $sSqlFunc .= "(select fa01_i_codigo from far_matersaude where fa01_i_codmater = ";
      $sSqlFunc .= "$oDadosItens->codigo),'$dHoje', $lAcumularSaldo) as proxdisp";
      
    } else {
    	
      $sSqlFunc  = "select fc_saldocontinuado_periodo_dinamico(".$oDadosRetirada->paciente_cgs.",";
      $sSqlFunc .= "(select fa01_i_codigo from far_matersaude where fa01_i_codmater = "; 
      $sSqlFunc .= "$oDadosItens->codigo),'$dHoje', $lAcumularSaldo) as proxdisp";
      
    }
    $rsProxDisp                      =  $oDaoDbConfig->sql_record($sSqlFunc);
    $oDadosProxDisp                  = db_utils::fieldsmemory($rsProxDisp, 0);
    $aVet                            = explode ("#",$oDadosProxDisp->proxdisp);
    $aProxDisp[$oDadosItens->codigo] = "";
    if ($aVet[1] != "") {
     	
      $aDt                             = explode ("-", $aVet[1]);
      $aProxDisp[$oDadosItens->codigo] = $aDt[2]."/".$aDt[1]."/".$aDt[0];
       
    }
    
  }
                                                                         
  $pdf1->rcodmaterial   = "codigo";
  $pdf1->rdescmaterial  = "nome";
  $pdf1->runidadesaida  = "unidade";
  $pdf1->rquantdeitens  = "quantidade";
  $pdf1->rquantatend    = "quantidade";
  $pdf1->rlocalizacao   = "localizacao";
  $pdf1->robsdositens   = "lote";
  $pdf1->Rdproxdisp     = $aProxDisp;
  $pdf1->recorddositens = $rsItens;
  $pdf1->linhasdositens = $iLinhasItens;
  $pdf1->imprime();
  $pdf1->Snumero_ant    = $oDadosRetirada->codigo;
  
}
$pdf1->objpdf->Output();
?>