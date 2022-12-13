<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("classes/db_leitor_classe.php");
require_once ("classes/db_leitorcidadao_classe.php");
require_once ("dbforms/db_funcoes.php");

db_app::import("social.*");

db_postmemory($_POST);

$clleitor        = new cl_leitor;
$clleitorcidadao = new cl_leitorcidadao;

$db_opcao  = 1;
$db_opcao1 = 1;
$db_botao  = true;
$lErro     = false;
$sMsg      = '';

/**
 * Salva os dados de um cidadao e um leitorcidadao
 * @param stdClass $oDadosCidadao
 */
function salvarCidadao($oDadosCidadao, $iCodigoLeitor) {
  
  $iNumero = null;
  if (is_numeric($oDadosCidadao->numero)) {
    $iNumero = $oDadosCidadao->numero;
  }
  
  $oCidadao = new Cidadao();
  $oCidadao->setAtivo(true);
  $oCidadao->setBairro($oDadosCidadao->bairro);
  $oCidadao->setCEP($oDadosCidadao->cep);
  $oCidadao->setComplemento($oDadosCidadao->complemento);
  $oCidadao->setCpfCnpj($oDadosCidadao->cpf);
  $oCidadao->setEndereco($oDadosCidadao->endereco);
  $oCidadao->setIdentidade($oDadosCidadao->identidade);
  $oCidadao->setMunicipio($oDadosCidadao->municipio);
  $oCidadao->setNome($oDadosCidadao->nome);
  $oCidadao->setNumero($iNumero);
  $oCidadao->setUF($oDadosCidadao->uf);
  $oCidadao->setSituacaoCidadao(1);
  $oCidadao->salvar();
  
  $iCidadaoSequencial = $oCidadao->getCodigo();
  $iCidadaoSeq        = $oCidadao->getSequencialInterno();
  
  $oDaoLeitorCidadao                          = db_utils::getDao("leitorcidadao");
  $oDaoLeitorCidadao->bi28_leitor             = $iCodigoLeitor;
  $oDaoLeitorCidadao->bi28_cidadao_sequencial = $iCidadaoSequencial;
  $oDaoLeitorCidadao->bi28_cidadao_seq        = $iCidadaoSeq;
  $oDaoLeitorCidadao->incluir(null);
  
  unset($oCidadao);
  unset($oDaoLeitorCidadao);
}

if (isset($incluir)) {
  
  if ($codigo == "") {
    
    db_msgbox("Informe o Leitor!");
    db_redireciona("bib1_leitor001.php");
    exit;
  } else {
    
    db_inicio_transacao();
    
    $clleitor->incluir($bi10_codigo);
    $iCodigoLeitor = $clleitor->bi10_codigo;
    
    if ($tipo == 'CIDADAO') {
      
      $oDaoLeitor    = db_utils::getDao("leitor");
      $sWhereLeitor  = "bi28_cidadao_sequencial = {$codigo} AND bi28_cidadao_seq = {$seq}";
      $sCamposLeitor = "bi10_codigo as codleitor"; 
      $sSqlLeitor    = $oDaoLeitor->sql_query_leitorcidadao("", $sCamposLeitor, "", $sWhereLeitor);
      $rsLeitor      = $oDaoLeitor->sql_record($sSqlLeitor);
      $iLinhasLeitor = $oDaoLeitor->numrows;
      
      if ($iLinhasLeitor > 0) {
        
        $iCodLeitorCadastrado = db_utils::fieldsMemory($rsLeitor, 0)->codleitor;
        $lErro                = true;
        $sMsg                 = '';
      } else {
        
        $clleitorcidadao->bi28_leitor             = $iCodigoLeitor;
        $clleitorcidadao->bi28_cidadao_sequencial = $codigo;
        $clleitorcidadao->bi28_cidadao_seq        = $seq;
        $clleitorcidadao->incluir();
      }
    } else if ($tipo == 'ALUNO') {
      
      /**
       * Caso nao exista registro na tabela leitoraluno, inserimos o aluno
       */
      $oDaoLeitorAluno   = db_utils::getDao("leitoraluno");
      $sWhereLeitorAluno = "bi11_aluno = {$codigo}";
      $sSqlLeitorAluno   = $oDaoLeitorAluno->sql_query_file(null, "bi11_leitor as codleitor", null, $sWhereLeitorAluno);
      
      $rsLeitorAluno     = $oDaoLeitorAluno->sql_record($sSqlLeitorAluno);
      
      if ($oDaoLeitorAluno->numrows == 0) {
        
        $oDaoLeitorAlunoInclusao              = db_utils::getDao("leitoraluno");
        $oDaoLeitorAlunoInclusao->bi11_leitor = $iCodigoLeitor;
        $oDaoLeitorAlunoInclusao->bi11_aluno  = $codigo;
        $oDaoLeitorAlunoInclusao->incluir(null);
        
        $oDaoAluno     = db_utils::getDao("aluno");
        $sCamposAluno  = "ed47_i_codigo as codigo, ed47_v_nome as nome, ed47_v_ender as endereco, ed47_c_numero as numero";
        $sCamposAluno .= ", ed47_v_compl as complemento, ed47_v_bairro as bairro, censomunicend.ed261_c_nome as municipio";
        $sCamposAluno .= ", censoufend.ed260_c_sigla as uf, ed47_v_cep as cep, ed47_v_ident as identidade, ed47_v_cpf as cpf";
        $sWhereAluno   = "ed47_i_codigo = {$codigo}";
        $sSqlAluno     = $oDaoAluno->sql_query(null, $sCamposAluno, null, $sWhereAluno);
        $rsAluno       = $oDaoAluno->sql_record($sSqlAluno);
        
        if ($oDaoAluno->numrows > 0) {
          
          $oDadosAluno = db_utils::fieldsMemory($rsAluno, 0);
          salvarCidadao($oDadosAluno, $iCodigoLeitor);
          unset($oDadosAluno);
        }
        unset($oDaoAluno);
        unset($oDaoLeitorAluno);
        unset($oDaoLeitorAlunoInclusao);
      } else {

        $iCodLeitorCadastrado = db_utils::fieldsMemory($rsLeitorAluno, 0)->codleitor;
        $lErro                = true;
        $sMsg                 = '';
      }
    } else if ($tipo == 'PUBLICO') {
      
      /**
       * Caso nao exista registro na tabela leitorpublico, inserimos o cgm publico
       */
      $oDaoLeitorPublico   = db_utils::getDao("leitorpublico");
      $sWhereLeitorPublico = "bi13_numcgm = {$codigo}";
      $sSqlLeitorPublico   = $oDaoLeitorPublico->sql_query_file(null, "bi13_leitor as codleitor", null, $sWhereLeitorPublico);
      $rsLeitorPublico     = $oDaoLeitorPublico->sql_record($sSqlLeitorPublico);

      if ($oDaoLeitorPublico->numrows == 0) {
        
        $oDaoLeitorPublicoInclusao              = db_utils::getDao("leitorpublico");
        $oDaoLeitorPublicoInclusao->bi13_leitor = $iCodigoLeitor;
        $oDaoLeitorPublicoInclusao->bi13_numcgm = $codigo;
        $oDaoLeitorPublicoInclusao->incluir(null);
        
        $oDaoCgm     = db_utils::getDao("cgm");
        $sWhereCgm   = "z01_numcgm = {$codigo}";
        $sCamposCgm  = "z01_numcgm as codigo, z01_nome as nome, z01_ender as endereco, z01_numero as numero";
        $sCamposCgm .= ", z01_compl as complemento, z01_bairro as bairro, z01_munic as municipio, z01_uf as uf";
        $sCamposCgm .= ", z01_cep as cep, z01_ident as identidade, z01_cgccpf as cpf";
        $sSqlCgm     = $oDaoCgm->sql_query_file(null, $sCamposCgm, null, $sWhereCgm);
        $rsCgm       = $oDaoCgm->sql_record($sSqlCgm);
        
        if ($oDaoCgm->numrows > 0) {
          
          $oDadosCgm = db_utils::fieldsMemory($rsCgm, 0);
          salvarCidadao($oDadosCgm, $iCodigoLeitor);
          unset($oDadosCgm);
        }
        unset($oDaoCgm);
        unset($oDaoLeitorPublico);
        unset($oDaoLeitorPublicoInclusao);
      } else {
        
        $iCodLeitorCadastrado = db_utils::fieldsMemory($rsLeitorPublico, 0)->codleitor;
        $lErro                = true;
        $sMsg                 = '';
      }
    } else if ($tipo == "FUNCIONARIO") {
      
      /**
       * Caso nao exista registro na tabela leitorfunc, inserimos o funcionario
       */
      $oDaoLeitorFunc   = db_utils::getDao("leitorfunc");
      $sWhereLeitorFunc = "bi12_rechumano = {$codigo}";
      $sSqlLeitorFunc   = $oDaoLeitorFunc->sql_query_file(null, "bi12_leitor as codleitor", null, $sWhereLeitorFunc);
      $rsLeitorFunc     = $oDaoLeitorFunc->sql_record($sSqlLeitorFunc);
      
      if ($oDaoLeitorFunc->numrows == 0) {
        
        $oDaoLeitorFuncInclusao                 = db_utils::getDao("leitorfunc");
        $oDaoLeitorFuncInclusao->bi12_leitor    = $iCodigoLeitor;
        $oDaoLeitorFuncInclusao->bi12_rechumano = $codigo;
        $oDaoLeitorFuncInclusao->incluir(null);
        
        $oDaoRecHumano      = db_utils::getDao("rechumano");
        $sCamposLeitorFunc  = "case when ed20_i_tiposervidor = 1 then cgmrh.z01_numcgm else cgmcgm.z01_numcgm end as codigo,";
        $sCamposLeitorFunc .= "case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as nome,";
        $sCamposLeitorFunc .= "case when ed20_i_tiposervidor = 1 then cgmrh.z01_ender else cgmcgm.z01_ender end as endereco,";
        $sCamposLeitorFunc .= "case when ed20_i_tiposervidor = 1 then cgmrh.z01_numero else cgmcgm.z01_numero end as numero,";
        $sCamposLeitorFunc .= "case when ed20_i_tiposervidor = 1 then cgmrh.z01_compl else cgmcgm.z01_compl end as complemento,";
        $sCamposLeitorFunc .= "case when ed20_i_tiposervidor = 1 then cgmrh.z01_bairro else cgmcgm.z01_bairro end as bairro,";
        $sCamposLeitorFunc .= "case when ed20_i_tiposervidor = 1 then cgmrh.z01_munic else cgmcgm.z01_munic end as municipio,";
        $sCamposLeitorFunc .= "case when ed20_i_tiposervidor = 1 then cgmrh.z01_uf else cgmcgm.z01_uf end as uf,";
        $sCamposLeitorFunc .= "case when ed20_i_tiposervidor = 1 then cgmrh.z01_cep else cgmcgm.z01_cep end as cep,";
        $sCamposLeitorFunc .= "case when ed20_i_tiposervidor = 1 then cgmrh.z01_ident else cgmcgm.z01_ident end as identidade,";
        $sCamposLeitorFunc .= "case when ed20_i_tiposervidor = 1 then cgmrh.z01_cgccpf else cgmcgm.z01_cgccpf end as cpf";
        $sWhereRecHumano    = "ed20_i_codigo = {$codigo}";
        $sSqlRecHumano      = $oDaoRecHumano->sql_query(null, $sCamposLeitorFunc, null, $sWhereRecHumano);
        $rsRecHumano        = $oDaoRecHumano->sql_record($sSqlRecHumano);
        
        if ($oDaoRecHumano->numrows > 0) {
          
          $oDadosRecHumano = db_utils::fieldsMemory($rsRecHumano, 0);
          salvarCidadao($oDadosRecHumano, $iCodigoLeitor);
          unset($oDadosRecHumano);
        }
        unset($oDaoRecHumano);
        unset($oDaoLeitorFuncInclusao);
        unset($oDaoLeitorFunc);
      } else {
        
        $iCodLeitorCadastrado = db_utils::fieldsMemory($rsLeitorFunc, 0)->codleitor;
        $lErro                = true;
        $sMsg                 = '';
      }
    }
    
    db_fim_transacao(false);
  }
  
  if ($lErro) {
    
    db_msgbox("Leitor já cadastrado pelo código $iCodLeitorCadastrado.\\nRedirecionando para o cadastro deste leitor.");
    db_redireciona("bib1_leitor002.php?chavepesquisa=$iCodLeitorCadastrado");
    exit;
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Inclusão de Leitor</b></legend>
     <?
       require_once ("forms/db_frmleitor.php");
     ?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<?
if (isset($incluir)) {
  
  if ($clleitor->erro_status == "0") {

    $clleitor->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    
    if ($clleitor->erro_campo != "") {

      echo "<script> document.form1.".$clleitor->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clleitor->erro_campo.".focus();</script>";
    };
  } else {

    $clleitor->erro(true,false);
    ?>
     <script>
      top.corpo.iframe_acervo1.location.href='bib1_leitor002.php?chavepesquisa=<?=$iCodigoLeitor?>';
     </script>
    <?
  };
};
?>