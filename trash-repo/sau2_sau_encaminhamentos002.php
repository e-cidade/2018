<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require_once('fpdf151/pdf.php');
require_once('libs/db_utils.php');
require_once('model/encaminhamentos.model.php');
require_once('libs/db_stdlibwebseller.php');

$oDaoSau_encaminhamentos = db_utils::getdao('sau_encaminhamentos');
$oDaoDb_usuacgm = db_utils::getdao('db_usuacgm');
$oDaoMedicos = db_utils::getdao('medicos');
$oDaoUnidades = db_utils::getdao('unidades');
$oDaoProntuarios = db_utils::getdao('prontuarios');
$oDaoCgs_cartaosus = db_utils::getdao('cgs_cartaosus');
$oEncaminhamento = new encaminhamento();

function novoCabecalho($oPDF, $sTitulo) {

  $lCor = true;
  $oPDF->setFont('arial','B',12);
  $oPDF->setFillColor(191, 195, 202);
  $oPDF->cell(190,5,$sTitulo,1,1,'C',$lCor);
  $oPDF->cell(190,2,'','LR',1,'C',false);

}

/*
* Imprime uma linha para preenchimento
*/
function linhaPreenchimento($oPDF, $nLinhas) {

  $fTamFonteAtual = $oPDF->FontSizePt;
  $sFamiliaFonteAtual = $oPDF->FontFamily;
  $sEstiloFonteAtual = $oPDF->FontStyle;
  $oPDF->setFont('arial','',11);
  for($i = 0; $i < $nLinhas; $i++) {

    $oPDF->cell(190,5,'______________________________________________________________________________________','LR',1,'L',false);

  }
  $oPDF->setFont($sFamiliaFonteAtual, $sEstiloFonteAtual, $fTamFonteAtual);

}

function finalizaBox($oPDF) {
   
  $lCor = false;
  $oPDF->cell(190,2,'','T',1,'C',$lCor);
}


function boxIdentificacaoPaciente($oPDF, $sNome, $iCgs, $dDtNasc, $sSexo, $sCartaoSus, $sCpf, $sRg,
                                  $sRua, $iNum, $sBairro, $sCidade, $sCep, $sTelefone, $sNomeMae, $sNaturalidade) {
 
  novoCabecalho($oPDF, converteCodificacao('Identificação do Paciente'));
  $lCor = false;
  $oPDF->setFont('arial','',10);

  $oPDF->cell(160,4,'Nome completo: '.$sNome,'L',0,'L',$lCor);
  $oPDF->cell(30,4,'CGS: '.$iCgs,'R',1,'L',$lCor);
  $oPDF->cell(75,4,'Data de Nascimento: '.$dDtNasc,'L',0,'L',$lCor);
  $oPDF->cell(50,4,'Sexo: '.$sSexo,0,0,'L',$lCor);
  $oPDF->cell(65,4,converteCodificacao('Cartão SUS: ').$sCartaoSus,'R',1,'L',$lCor);
  $oPDF->cell(40,4,'CPF: '.$sCpf,'L',0,'L',$lCor);
  $oPDF->cell(40,4,'RG: '.$sRg,0,0,'L',$lCor);
  $oPDF->cell(54,4,converteCodificacao('Data da emissão: '),0,0,'L',$lCor);
  $oPDF->cell(56,4,'Telefone: '.$sTelefone,'R',1,'L',$lCor);
  $oPDF->cell(120,4,converteCodificacao('Nome da mãe: ').$sNomeMae,'L',0,'L',$lCor);
  $oPDF->cell(70,4,'Naturalidade: '.$sNaturalidade,'R',1,'L',$lCor);
  $oPDF->cell(160,4,converteCodificacao('Endereço: ').$sRua,'L',0,'L',$lCor);
  $oPDF->cell(30,4,converteCodificacao('N°: ').$iNum,'R',1,'L',$lCor);
  $oPDF->cell(190,4,'Bairro: '.$sBairro,'LR',1,'L',$lCor);
  $oPDF->cell(160,4,'Cidade: '.$sCidade,'L',0,'L',$lCor);
  $oPDF->cell(30,4,'CEP: '.$sCep,'R',1,'L',$lCor);
  $oPDF->cell(190,2,'','LR',1,'C',false);
  finalizaBox($oPDF);
  
}

/*
* Imprime todos os procedimentos
*/
function boxProcedimentos($oPDF, $oProcedimentos) {
 
  $fTamFonteAtual = $oPDF->FontSizePt;
  $sFamiliaFonteAtual = $oPDF->FontFamily;
  $sEstiloFonteAtual = $oPDF->FontStyle;
  $lCor = false;

  $oPDF->cell(190,4,'Procedimento(s): ','LR',1,'L',$lCor);
  $oPDF->cell(190,1,'','LR',1,'L',$lCor);
  $oPDF->setFont('arial','',8);
  $iNumProced = count($oProcedimentos);
  for($iCont = 0; $iCont < $iNumProced; $iCont++) { 

    $oPDF->cell(5,4,'','L',0,'L',$lCor);
    $oPDF->cell(180,4,@$oProcedimentos[$iCont]->sProcedimento. ' - '.
                substr(urldecode(@$oProcedimentos[$iCont]->sDescr),0,90),1,0,'L',$lCor);
    $oPDF->cell(5,4,'','R',1,'L',$lCor);

  }
  $oPDF->setFont($sFamiliaFonteAtual, $sEstiloFonteAtual, $fTamFonteAtual);

}

/*
* Imprime os dados do encaminhamento
*/
function boxDadosEncaminhamento($oPDF, $dEncaminhamento, $dValidade, $dRetorno, $sTipo, $sDadosClinicos, $iProntuario,
                                $sUnidOrig, $sUnidDest, $sPrestadora, $sEspecialidade, $oProcedimentos, $iCodigo) {
 
  novoCabecalho($oPDF, 'Dados do Encaminhamento'); // Conversao de codificacao para nao dar problema na acentuacao
  $lCor = false;
  $oPDF->setFont('arial','',10);

  $oPDF->cell(71,4,converteCodificacao('Código do Encaminhamento: ').$iCodigo,'L',0,'L',$lCor);
  $oPDF->cell(59,4,'Tipo: '.$sTipo,0,0,'L',$lCor);
  $oPDF->cell(60,4,converteCodificacao('N° prontuário: ').$iProntuario,'R',1,'L',$lCor);
  $oPDF->cell(71,4,'Data do Encaminhamento: '.$dEncaminhamento,'L',0,'L',$lCor);
  $oPDF->cell(59,4,'Validade: '.$dValidade,0,0,'L',$lCor);
  $oPDF->cell(60,4,'Data de retorno: '.$dRetorno,'R',1,'L',$lCor);
  $oPDF->cell(190,4,'Unidade de origem: '.$sUnidOrig,'LR',1,'L',$lCor);
  $oPDF->cell(190,4,'Unidade de destino: '.$sUnidDest,'LR',1,'L',$lCor);
  $oPDF->cell(190,4,'Prestadora: '.$sPrestadora,'LR',1,'L',$lCor);
  $oPDF->MultiCell(190,4,converteCodificacao('Dados Clínicos: ').$sDadosClinicos,'LR',1,'L',$lCor);
  $oPDF->cell(190,4,'Especialidade: '.$sEspecialidade,'RL',1,'L',$lCor);
  boxProcedimentos($oPDF, $oProcedimentos);
  $oPDF->cell(190,2,'','LR',1,'L',$lCor);
  finalizaBox($oPDF);

}

/*
* Imprime o box para preenchimento de dados sobre situacao do caso
*/
function boxSituacaoCaso($oPDF) {
 
  novoCabecalho($oPDF, converteCodificacao('Situação do Caso'));
  $lCor = false;
  $oPDF->setFont('arial','',10);

  $oPDF->cell(190,4,converteCodificacao('HIPÓTESE DIAGNÓSTICA (HD): ___________________________________________________________________'),'LR',1,'L',$lCor);
  linhaPreenchimento($oPDF, 2);
  $oPDF->cell(190,4,'RESUMO DO CASO: _____________________________________________________________________________','LR',1,'L',$lCor);
  linhaPreenchimento($oPDF, 2);
    $oPDF->cell(190,4,'EXAMES REALIZADOS (DATAS E RESULTADOS):_____________________________________________________','LR',1,'L',$lCor);
  linhaPreenchimento($oPDF, 2);
  $oPDF->cell(190,2,'','LR',1,'L',$lCor);
  finalizaBox($oPDF);

}

function boxSolicitante($oPDF, $sSolicitante) {
 
  novoCabecalho($oPDF,converteCodificacao('Identificação do Solicitante'));
  $lCor = false;
  $oPDF->setFont('arial','',10);

  $oPDF->cell(190,4,
              converteCodificacao('INFORMAÇÃO PARA REFERÊNCIA (dados relevantes que justifiquem o encaminhamento)'),
              'LR',1,'C',$lCor);
  linhaPreenchimento($oPDF, 3);
  $oPDF->cell(190,4,'','LR',1,'C',$lCor);
  $oPDF->cell(55,4,'DATA: ____/____/________','L',0,'C',$lCor);
  $oPDF->cell(135,4,'______________________________________________','R',1,'C',$lCor);
  $oPDF->setFont('arial','',6);
  $oPDF->cell(55,2,'','L',0,'C',$lCor);
  $oPDF->cell(135,2,$sSolicitante,'R',1,'C',$lCor);
  $oPDF->cell(55,2,'','L',0,'C',$lCor);
  $oPDF->cell(135,2,'ASSINATURA E CARIMBO ','R',1,'C',$lCor);
  finalizaBox($oPDF);

}
/*
function boxReferencia($oPDF) {
 
  novoCabecalho($oPDF,converteCodificacao('Informação para Referência (dados relevantes que justifiquem o encaminhamento)'));
  $lCor = false;
  $oPDF->setFont('arial','',10);
  linhaPreenchimento($oPDF);
  linhaPreenchimento($oPDF);
  linhaPreenchimento($oPDF);
  finalizaBox($oPDF);

}
*/
function boxContraReferencia($oPDF) {
 
  novoCabecalho($oPDF,converteCodificacao('Informação de Contra-Referência'));
  $lCor = false;
  $oPDF->setFont('arial','',10);
  linhaPreenchimento($oPDF, 3);
  $oPDF->cell(190,4,'','LR',1,'C',$lCor);
  $oPDF->cell(55,4,'DATA: ____/____/________','L',0,'C',$lCor);
  $oPDF->cell(135,4,'______________________________________________','R',1,'C',$lCor);
  $oPDF->setFont('arial','',6);
  $oPDF->cell(55,2,'','L',0,'C',$lCor);
  $oPDF->cell(135,2,'ASSINATURA E CARIMBO ','R',1,'C',$lCor);
  finalizaBox($oPDF);

}

function formataData($dData, $iTipo = 1) {

  if($iTipo == 1) {

    $dData = explode('/',$dData);
    $dData = $dData[2].'-'.$dData[1].'-'.$dData[0];
    return $dData;
  
  }
 
 $dData = explode('-',$dData);
 $dData = $dData[2].'/'.$dData[1].'/'.$dData[0];
 return $dData;

}

$sCampos  = 'cgs_und.z01_v_nome, ';
$sCampos .= 'cgs_und.z01_i_cgsund, ';
$sCampos .= 'cgs_und.z01_d_nasc, ';
$sCampos .= 'cgs_und.z01_v_sexo, ';
$sCampos .= 'cgs_und.z01_v_cgccpf, ';
$sCampos .= 'cgs_und.z01_v_ident, ';
$sCampos .= 'cgs_und.z01_v_ender, ';
$sCampos .= 'cgs_und.z01_i_numero, ';
$sCampos .= 'cgs_und.z01_v_bairro, ';
$sCampos .= 'cgs_und.z01_v_munic, ';
$sCampos .= 'cgs_und.z01_v_cep, ';
$sCampos .= 'cgs_und.z01_v_telef, ';
$sCampos .= 'cgs_und.z01_c_naturalidade, ';
$sCampos .= 'cgs_und.z01_v_mae, ';
$sCampos .= 'sau_encaminhamentos.s142_d_encaminhamento, ';
$sCampos .= 'sau_encaminhamentos.s142_d_validade, ';
$sCampos .= 'sau_encaminhamentos.s142_d_retorno, ';
$sCampos .= 'sau_encaminhamentos.s142_t_dadosclinicos, ';
$sCampos .= 'sau_encaminhamentos.s142_d_validade, ';
$sCampos .= 'sau_encaminhamentos.s142_i_profsolicitante, ';
$sCampos .= 'b.z01_nome as sNomeSolicitante, ';
$sCampos .= 'medicosolicitante.sd03_i_crm, ';
$sCampos .= 'case when sau_encaminhamentos.s142_i_tipo = 1 then \'Consulta\' else \'Exame\' end as sTipo, ';
$sCampos .= 'cgm.z01_nome as sPrestadora, ' ;
$sCampos .= 'rhcbo.rh70_estrutural || \' - \' || rhcbo.rh70_descr as sEspecialidade, ';
$sCampos .= 'sau_encaminhamentos.s142_i_login, ';
$sCampos .= 'db_depart.descrdepto, ';
$sCampos .= 'sau_encaminhamentos.s142_i_prontuario, ';
$sCampos .= 'sau_encaminhamentos.s142_i_codigo ';

$sSql = $oDaoSau_encaminhamentos->sql_query2($iEncaminhamento, $sCampos);

//echo $sSql;
//exit;
$rsSau_encaminhamentos= $oDaoSau_encaminhamentos->sql_record($sSql);
$iLinhas = $oDaoSau_encaminhamentos->numrows;

if($iLinhas <= 0)
{
?>
  <table width='100%'>
    <tr>
      <td align='center'>
        <font color='#FF0000' face='arial'>
          <b>Nenhum registro encontrado.<br>
            <input type='button' value='Fechar' onclick='window.close()'>
          </b>
        </font>
      </td>
    </tr>
  </table>
<?
  exit;
}

$oDados = db_utils::fieldsmemory($rsSau_encaminhamentos,0);

$oPDF = new PDF();
$oPDF->Open();
$oPDF->AliasNbPages();

/*
* headers
*/
$head1 = converteCodificacao('Encaminhamento n° '.$oDados->s142_i_codigo);


$oPDF->Addpage('P');
$lCor = false;
$oPDF->setfillcolor(223);
$oPDF->setfont('arial','',10);

if(!empty($oDados->s142_i_prontuario)) { // Pega a unidade de origem
  
  $sSql = $oDaoProntuarios->sql_query($oDados->s142_i_prontuario, ' sd24_i_unidade, descrdepto ');
  $rsProntuarios = $oDaoProntuarios->sql_record($sSql);
  $oDadosProntuarios = db_utils::fieldsmemory($rsProntuarios, 0);

  $sUnidOrig = $oDadosProntuarios->descrdepto;

} else {
  
  $sSql = $oDaoUnidades->sql_query(db_getsession('DB_coddepto'), ' coddepto || \' - \' || descrdepto as descrdepto');
  $rsUnidades = $oDaoUnidades->sql_record($sSql);
  $oDadosUnidades = db_utils::fieldsmemory($rsUnidades, 0);
  $sUnidOrig = $oDadosUnidades->descrdepto;
  //$sUnidOrig = '____________________________________________';

}

$sSql = $oDaoCgs_cartaosus->sql_query(null, ' s115_c_cartaosus ', ' s115_c_tipo asc ',
                                      ' s115_i_cgs = '.$oDados->z01_i_cgsund);
$rsCgs_cartaosus = $oDaoCgs_cartaosus->sql_record($sSql);
if($oDaoCgs_cartaosus->numrows != 0) { // se o paciente tem um cartao sus

  $oDadosCgs_cartaosus = db_utils::fieldsmemory($rsCgs_cartaosus, 0);
  $sCartaoSus = $oDadosCgs_cartaosus->s115_c_cartaosus;

}  else {
  $sCartaoSus = '';
}

/* Obtem os dados dos procedimentos */
$oProcedimentos = $oEncaminhamento->getProcedimentosEncaminhamento($iEncaminhamento)->oProcedimentos;

/*
*  Pega as informacoes de quem esta fazendo o encaminhamento (medico solicitante)
*/
if($oDados->sd03_i_crm == 0) {
  $oDados->sd03_i_crm = '';
}
$sNomeCrmSolicitante =  $oDados->snomesolicitante.' - CRM: '. $oDados->sd03_i_crm;


/* Evita a impressao de valores 0 na ficha */
if($oDados->z01_v_cgccpf == '0') {
  $oDados->z01_v_cgccpf = '';
} 
if($oDados->z01_v_ident == '0') {
  $oDados->z01_v_ident = '';
} 
if($oDados->z01_v_ender == '0') {
  $oDados->z01_v_ender = '';
}
if($oDados->z01_i_numero == 0) {
  $oDados->z01_i_numero = '';
} 
if($oDados->z01_v_bairro == '0') {
  $oDados->z01_v_bairro = '';
} 
if($oDados->z01_v_munic == '0') {
  $oDados->z01_v_munic = '';
} 
if($oDados->z01_c_naturalidade == '0') {
  $oDados->z01_c_naturalidade = '';
} 
if($oDados->z01_v_mae == '0') {
  $oDados->z01_v_mae = '';
} 
if($oDados->z01_v_cep == '0') {
  $oDados->z01_v_cep = '';
}

boxIdentificacaoPaciente($oPDF, substr($oDados->z01_v_nome,0,56), $oDados->z01_i_cgsund, formataData($oDados->z01_d_nasc, 2), 
                         empty($oDados->z01_v_sexo) ? '' : $oDados->z01_v_sexo == 'M' ? 'Masculino' : 'Feminino',
                         $sCartaoSus, $oDados->z01_v_cgccpf, $oDados->z01_v_ident, 
                         substr($oDados->z01_v_ender,0,60), $oDados->z01_i_numero, substr($oDados->z01_v_bairro,0,74),
                         substr($oDados->z01_v_munic,0,62), $oDados->z01_v_cep, $oDados->z01_v_telef,
                         substr($oDados->z01_v_mae,0,42), substr($oDados->z01_c_naturalidade,0,18)
                        );


boxDadosEncaminhamento($oPDF, formataData($oDados->s142_d_encaminhamento, 2), formataData($oDados->s142_d_validade, 2),
                       formataData($oDados->s142_d_retorno, 2), $oDados->stipo, $oDados->s142_t_dadosclinicos,
                       $oDados->s142_i_prontuario, $sUnidOrig, $oDados->descrdepto,
                       substr($oDados->sprestadora,0,68),substr($oDados->sespecialidade,0,71), $oProcedimentos, $oDados->s142_i_codigo
                      );

boxSituacaoCaso($oPDF);
boxSolicitante($oPDF, $sNomeCrmSolicitante);
boxContraReferencia($oPDF);

$oPDF->Output();
?>