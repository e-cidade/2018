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

require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_jsplibwebseller.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$claluno           = new cl_aluno;
$clalunoalt        = new cl_alunoalt;
$clalunoaltera     = new cl_alunoaltera;
$clalunobairro     = new cl_alunobairro;
$clalunoprimat     = new cl_alunoprimat;
$clpais            = new cl_pais;
$clcensouf         = new cl_censouf;
$clcensomunic      = new cl_censomunic;
$clcensoorgemissrg = new cl_censoorgemissrg;

$db_opcao       = 22;
$db_opcao1      = 3;
$db_botao       = false;
$_rollback      = false;
$iOpcaoFiliacao = 2;

function TiraEspacoNome($nome){
 $sep = "";
 $str = "";
 $parte=explode(" ",$nome);
 //echo print_r($parte);
 for($i=0;$i<count($parte);$i++){
  if(trim($parte[$i])!=""){
   $str .= $sep.trim($parte[$i]);
   $sep=" ";
  }
 }
 return $str;
}

if(isset($alterar)){
  
  $sBairro = pg_escape_string($ed47_v_bairro);
 if ($ed47_v_nome != "") {
   
   $erroconf         = false;
   $ed47_v_nome      = TiraEspacoNome($ed47_v_nome);
   $ed47_v_mae       = TiraEspacoNome($ed47_v_mae);
   $ed47_v_pai       = TiraEspacoNome($ed47_v_pai);
   $ed47_c_nomeresp  = TiraEspacoNome($ed47_c_nomeresp);
   $sCampos          = "ed47_i_codigo as jatem, ed47_d_nasc as datatem, ed47_c_certidaonum as certidaonumexiste";
   $sCampos         .= ", ed47_v_mae as maesim";
   $result2 = $claluno->sql_record($claluno->sql_query("",$sCampos,""," ed47_v_nome = '$ed47_v_nome'"));
   
   if ($claluno->numrows > 0) {
     
     db_fieldsmemory($result2,0);
     if($jatem!=$ed47_i_codigo){
       
       if($ed47_d_nasc_ano."-".$ed47_d_nasc_mes."-".$ed47_d_nasc_dia==$datatem){
         
         $erroconf=true;
         db_msgbox("Este nome ($ed47_v_nome) j� possui cadastro com a mesma data de nascimento digitada ($ed47_d_nasc)! Redirecionando para visualiza��o...");
         db_redireciona("edu1_alunodados002.php?chavepesquisa=$jatem");
         exit;
       }
       
       if(trim($ed47_v_mae)==trim($maesim) && trim($ed47_v_mae)!="" && trim($maesim)!=""){
         
         $erroconf=true;
         db_msgbox("Este nome ($ed47_v_nome) j� possui cadastro com o mesmo nome da mae digitado ($maesim)! Redirecionando para visualiza��o...");
         db_redireciona("edu1_alunodados002.php?chavepesquisa=$jatem");
         exit;
       }
     }
   }
   
   if($erroconf == false){
     
     $db_opcao    = 2;
     $db_opcao1   = 3;
     $ed47_c_foto = @trim($GLOBALS["HTTP_POST_VARS"]["ed47_o_oid"]);
     $ed47_o_oid  = "tmp/".@trim($GLOBALS["HTTP_POST_VARS"]["ed47_o_oid"]);
     
     if($ed47_c_foto != "") {
       
       db_query("begin");
       $oid_imagem = pg_loimport($conn,$ed47_o_oid) or die("Erro(15) importando imagem");
       db_query("end");
       $ed47_o_oid = $oid_imagem;
     } else {
       $oid_imagem = "0";
     }
     
     db_inicio_transacao();
     
     $claluno->ed47_c_foto             = $ed47_c_foto;
     $claluno->ed47_o_oid              = $oid_imagem;
     $claluno->ed47_v_nome             = $ed47_v_nome;
     $claluno->ed47_v_mae              = $ed47_v_mae;
     $claluno->ed47_v_pai              = $ed47_v_pai;
     $claluno->ed47_celularresponsavel =  "{$dddcelularresponsavel}{$ed47_celularresponsavel}";
     $claluno->ed47_c_nomeresp         = $ed47_c_nomeresp;
     $claluno->ed47_d_ultalt           = date("Y-m-d");
     $clalunoaltera->logalterar($ed47_i_codigo,1);
     $claluno->alterar($ed47_i_codigo);
     $iAluno = $claluno->ed47_i_codigo;
     
     if ($claluno->erro_status == "0") {
       $_rollback = true;
     }
     
     if($j13_codi!=""){
       
       $result_bairro = $clalunobairro->sql_record($clalunobairro->sql_query("","ed225_i_codigo",""," ed225_i_aluno = $ed47_i_codigo"));
       $clalunobairro->ed225_i_aluno  = $ed47_i_codigo;
       $clalunobairro->ed225_i_bairro = $j13_codi;
       if($clalunobairro->numrows>0){
         
         db_fieldsmemory($result_bairro,0);
         $clalunobairro->ed225_i_codigo = $ed225_i_codigo;
         $clalunobairro->alterar($ed225_i_codigo);
       } else{
         $clalunobairro->incluir(null);
       }
     }
     
     $result_pri = $clalunoprimat->sql_record($clalunoprimat->sql_query("","ed76_i_codigo",""," ed76_i_aluno = $ed47_i_codigo"));
     $clalunoprimat->ed76_i_aluno = $ed47_i_codigo;
     
     if ($clalunoprimat->numrows > 0) {
       
       if($ed76_i_escola!=""){
         
         db_fieldsmemory($result_pri,0);
         $clalunoprimat->alterar($ed76_i_codigo);
       } else{
         $clalunoprimat->excluir($ed76_i_codigo);
       }
     } else {
       
      if($ed76_i_escola!=""){
        $clalunoprimat->incluir(null);
      }
     }
     
     /**
      * Verifica se o aluno j� possui um cadastro de cidad�o, buscando pelo nome, data de nascimento e nome da m�e.
      * Caso n�o exista, cria um novo cidad�o
      */
     $oDataNascimento     = new DBDate($ed47_d_nasc);
     $aCidadao            = CidadaoRepository::getCidadaoPorNomeDataNascimento($ed47_v_nome, $oDataNascimento, $ed47_v_mae);
     $oCidadao            = CidadaoRepository::getCidadaoPeloCodigoAluno($ed47_i_codigo);
     $oCidadaoMae         = null;
     $oCidadaoPai         = null;
     $oCidadaoResponsavel = null;
     
     if (empty($aCidadao) && empty($oCidadao)) {
     
       $oCidadao = new Cidadao();
       $oCidadao->setNome($ed47_v_nome);
       $oCidadao->setDataNascimento($oDataNascimento->getDate(DBDate::DATA_PTBR));
       $oCidadao->setSexo($ed47_v_sexo);
       $oCidadao->setAtivo(true);
       $oCidadao->setEndereco($ed47_v_ender);
       $oCidadao->setComplemento($ed47_v_compl);
       $oCidadao->setCEP($ed47_v_cep);
       $oCidadao->setBairro($sBairro);
       $oCidadao->setNumero($ed47_c_numero);
       $oCidadao->setSituacaoCidadao(2);
       
       /**
        * Busca a descri��o do munic�pio para setar no cidad�o
       */
       $oDaoCensoMunicipio = new cl_censomunic();
       $sSqlCensoMunicipio = $oDaoCensoMunicipio->sql_query_file($ed47_i_censomunicnat, "ed261_c_nome");
       $rsCensoMunicipio   = $oDaoCensoMunicipio->sql_record($sSqlCensoMunicipio);
       	
       if ($oDaoCensoMunicipio->numrows > 0) {
         $oCidadao->setMunicipio(db_utils::fieldsMemory($rsCensoMunicipio, 0)->ed261_c_nome);
       }
       	
       /**
        * Busca a descri��o do estado para setar no cidad�o
        */
       $oDaoCensoUF = new cl_censouf();
       $sSqlCensoUF = $oDaoCensoUF->sql_query_file($ed47_i_censomunicnat, "ed260_c_nome");
       $rsCensoUF   = $oDaoCensoUF->sql_record($sSqlCensoUF);
     
       if ($oDaoCensoUF->numrows > 0) {
         $oCidadao->setUF(db_utils::fieldsMemory($rsCensoUF, 0)->ed260_c_nome);
       }
     } else if (empty($oCidadao)) {
     
       /**
        * Caso retorne mais de um cidad�o utiliza o de c�digo menor
        */
       $iCidadaoMenor = null;
       foreach ($aCidadao as $oCidadaoTemporario) {
     
         if (empty($iCidadaoMenor) || $iCidadaoMenor > $oCidadaoTemporario->getCodigo()) {
           $oCidadao = $oCidadaoTemporario;
         }
       }
     }
     
     /**
      * Caso o c�digo de cidad�o da m�e e do pai seja diferente de vazio, setamos o pai e m�e do aluno como cidad�o
      */
     if (!empty($oInputCodigoMae)) {
       $oCidadaoMae = CidadaoRepository::getCidadaoByCodigo($oInputCodigoMae);
     }
      
     $oCidadao->setMae($oCidadaoMae);
     
     if (!empty($oInputCodigoPai)) {
       $oCidadaoPai = CidadaoRepository::getCidadaoByCodigo($oInputCodigoPai);
     }
      
     $oCidadao->setPai($oCidadaoPai);
     $oCidadao->setBairro( pg_escape_string($oCidadao->getBairro()) );
     $oCidadao->salvar();
     
     $sWhere           = "ed330_cidadao = {$oCidadao->getCodigo()} and ed330_cidadao_seq = {$oCidadao->getSequencialInterno()}";
     $oDaoAlunoCidadao = new cl_alunocidadao();
     $sSqlAlunoCidadao = $oDaoAlunoCidadao->sql_query_file(null, 'ed330_sequencial', null, $sWhere);
     $rsAlunoCidadao   = $oDaoAlunoCidadao->sql_record($sSqlAlunoCidadao);
     
     if ($oDaoAlunoCidadao->numrows <= 0) {
     
       $oDaoAlunoCidadao->ed330_aluno       = $iAluno;
       $oDaoAlunoCidadao->ed330_cidadao     = $oCidadao->getCodigo();
       $oDaoAlunoCidadao->ed330_cidadao_seq = $oCidadao->getSequencialInterno();
       $oDaoAlunoCidadao->incluir(null);
     
       if ($oDaoAlunoCidadao->erro_status == 0) {
         throw new BusinessException($oDaoAlunoCidadao->erro_msg);
       }
     }
     
     /**
      * Salva o vinculo do alunoCidadao com o Responsavel
      */
     $oDaoAlunoResponsavel = new cl_alunocidadaoresponsavel();
     $oDaoAlunoResponsavel->excluir(null, "ed331_aluno = {$iAluno}");
     
     if ($oDaoAlunoResponsavel->erro_status == 0) {
       throw new BusinessException($oDaoAlunoResponsavel->erro_msg);
     }
     
     if (!empty($oInputCodigoResponsavel)) {
       
       $oCidadaoResponsavel                     = CidadaoRepository::getCidadaoByCodigo($oInputCodigoResponsavel);
       $oDaoAlunoResponsavel->ed331_aluno       = $iAluno;
       $oDaoAlunoResponsavel->ed331_cidadao     = $oCidadaoResponsavel->getCodigo();
       $oDaoAlunoResponsavel->ed331_cidadao_seq = $oCidadaoResponsavel->getSequencialInterno();
       $oDaoAlunoResponsavel->incluir(null);
     
       if ($oDaoAlunoResponsavel->erro_status == 0) {
         throw new BusinessException($oDaoAlunoResponsavel->erro_msg);
       }
     }
     
     /**
      * Verifica se algum contato foi selecionado, caso tenha sido verifica qual foi selecionado e se possui um cidad�o.
      * Caso exista o cidadao salva a vincula��o
      */
     $oDaoAlunoContato = new cl_alunocidadaocontato();
     $oDaoAlunoContato->excluir(null, "ed332_aluno = {$iAluno}");
     
     if ($oDaoAlunoContato->erro_status == 0) {
       throw new BusinessException($oDaoAlunoContato->erro_msg);
     }
     
     if (!empty($oSelectContato)) {
     
       $oDaoAlunoContato->ed332_aluno = $iAluno;
     
       if ($oSelectContato == 1 && !empty($oCidadaoMae)) {
     
         $oDaoAlunoContato->ed332_cidadao     = $oCidadaoMae->getCodigo();
         $oDaoAlunoContato->ed332_cidadao_seq = $oCidadaoMae->getSequencialInterno();
       }
     
       if ($oSelectContato == 2 && !empty($oCidadaoPai)) {
     
         $oDaoAlunoContato->ed332_cidadao     = $oCidadaoPai->getCodigo();
         $oDaoAlunoContato->ed332_cidadao_seq = $oCidadaoPai->getSequencialInterno();
       }
     
       if ($oSelectContato == 3 && !empty($oCidadaoResponsavel)) {
     
         $oDaoAlunoContato->ed332_cidadao     = $oCidadaoResponsavel->getCodigo();
         $oDaoAlunoContato->ed332_cidadao_seq = $oCidadaoResponsavel->getSequencialInterno();
       }
       
       if (!empty($oDaoAlunoContato->ed332_cidadao)) {
     
         $oDaoAlunoContato->incluir(null);
     
         if ($oDaoAlunoContato->erro_status == 0) {
           throw new BusinessException($oDaoAlunoContato->erro_msg);
         }
       }
     }
     
     db_fim_transacao($_rollback);
   }
 }else{
  $db_opcao = 2;
  $db_opcao1 = 3;
  $ed47_c_foto = @trim($GLOBALS["HTTP_POST_VARS"]["ed47_o_oid"]);
  $ed47_o_oid = "tmp/".@trim($GLOBALS["HTTP_POST_VARS"]["ed47_o_oid"]);
  if($ed47_c_foto!=""){
   db_query("begin");
   $oid_imagem = pg_loimport($conn,$ed47_o_oid) or die("Erro(15) importando imagem");
   db_query("end");
   $ed47_o_oid = $oid_imagem;
  }else{
   $oid_imagem = "0";
  }
  db_inicio_transacao();
  $claluno->ed47_c_foto = $ed47_c_foto;
  $claluno->ed47_o_oid = $oid_imagem;
  $claluno->ed47_d_ultalt = date("Y-m-d");
  $claluno->ed47_i_login = $iUsuarioLogado;
  $clalunoaltera->logalterar($ed47_i_codigo,1);
  $claluno->alterar($ed47_i_codigo);
  if($claluno->erro_status=="0"){
   $_rollback = true;
  }else{
   $_rollback = false;
  }
  if($j13_codi!=""){
   $result_bairro = $clalunobairro->sql_record($clalunobairro->sql_query("","ed225_i_codigo",""," ed225_i_aluno = $ed47_i_codigo"));
   $clalunobairro->ed225_i_aluno = $ed47_i_codigo;
   $clalunobairro->ed225_i_bairro = $j13_codi;
   if($clalunobairro->numrows>0){
    db_fieldsmemory($result_bairro,0);
    $clalunobairro->ed225_i_codigo = $ed225_i_codigo;
    $clalunobairro->alterar($ed225_i_codigo);
   }else{
    $clalunobairro->incluir(null);
   }
  }
  $result_pri = $clalunoprimat->sql_record($clalunoprimat->sql_query("","ed76_i_codigo",""," ed76_i_aluno = $ed47_i_codigo"));
  $clalunoprimat->ed76_i_aluno = $ed47_i_codigo;
  if($clalunoprimat->numrows>0){
   if($ed76_i_escola!=""){
    db_fieldsmemory($result_pri,0);
    $clalunoprimat->alterar($ed76_i_codigo);
   }else{
    $clalunoprimat->excluir($ed76_i_codigo);
   }
  }else{
   if($ed76_i_escola!=""){
    $clalunoprimat->incluir(null);
   }
  }
  db_fim_transacao($_rollback);
 }
 $db_botao = true;
}else if(isset($chavepesquisa)){
 $db_opcao = 2;
 $db_opcao1 = 3;
 $result = $claluno->sql_record($claluno->sql_query($chavepesquisa));
 db_fieldsmemory($result,0);
 $result_bairro = $clalunobairro->sql_record($clalunobairro->sql_query("","*",""," ed225_i_aluno = $chavepesquisa"));
 if($clalunobairro->numrows>0){
  db_fieldsmemory($result_bairro,0);
  $j13_codi = $ed225_i_bairro;
 }
 $campos = "ed76_i_codigo,
            ed76_i_escola,
            ed76_d_data,
            ed76_c_tipo,
            case when ed76_c_tipo = 'M'
             then ed18_c_nome else ed82_c_nome end as nomeescola
           ";
 $result1 = $clalunoprimat->sql_record($clalunoprimat->sql_query("",$campos,""," ed76_i_aluno = $chavepesquisa"));
 if($clalunoprimat->numrows>0){
  db_fieldsmemory($result1,0);
 }
 $db_botao = true;
 $ed47_d_ultalt_dia = $ed47_d_ultalt_dia==""?date("d"):$ed47_d_ultalt_dia;
 $ed47_d_ultalt_mes = $ed47_d_ultalt_mes==""?date("m"):$ed47_d_ultalt_mes;
 $ed47_d_ultalt_ano = $ed47_d_ultalt_ano==""?date("Y"):$ed47_d_ultalt_ano;
 $ed47_d_cadast_dia = $ed47_d_cadast_dia==""?date("d"):$ed47_d_cadast_dia;
 $ed47_d_cadast_mes = $ed47_d_cadast_mes==""?date("m"):$ed47_d_cadast_mes;
 $ed47_d_cadast_ano = $ed47_d_cadast_ano==""?date("Y"):$ed47_d_cadast_ano;
 ?>
 <script>
  parent.document.formaba.a2.disabled = false;
  parent.document.formaba.a2.style.color = "black";
  parent.document.formaba.a3.disabled = false;
  parent.document.formaba.a3.style.color = "black";
  parent.document.formaba.a4.disabled = false;
  parent.document.formaba.a4.style.color = "black";
  parent.document.formaba.a5.disabled = false;
  parent.document.formaba.a5.style.color = "black";
  parent.document.formaba.a6.disabled = false;
  parent.document.formaba.a6.style.color = "black";
  parent.document.formaba.a7.disabled = false;
  parent.document.formaba.a7.style.color = "black";
  top.corpo.iframe_a2.location.href='edu1_aluno002.php?chavepesquisa=<?=$ed47_i_codigo?>';
  top.corpo.iframe_a3.location.href='edu1_alunocurso001.php?ed56_i_aluno=<?=$ed47_i_codigo?>&ed47_v_nome=<?=$ed47_v_nome?>';
  top.corpo.iframe_a4.location.href='edu1_docaluno001.php?ed49_i_aluno=<?=$ed47_i_codigo?>&ed47_v_nome=<?=$ed47_v_nome?>';
  top.corpo.iframe_a5.location.href='edu1_alunonecessidade001.php?ed214_i_aluno=<?=$ed47_i_codigo?>&ed47_v_nome=<?=$ed47_v_nome?>';
  top.corpo.iframe_a6.location.href='edu1_alunomatcenso001.php?ed280_i_aluno=<?=$ed47_i_codigo?>&ed47_v_nome=<?=$ed47_v_nome?>';
  top.corpo.iframe_a7.location.href='edu1_alunotransporteutilizado.php?iAluno=<?=$ed47_i_codigo?>'
                                  +'&ed47_v_nome=<?=$ed47_v_nome?>'
                                  +'&iUtilizaTransporte=<?=$ed47_i_transpublico?>';
 </script>
 <?
}
if(isset($excluirfoto)){
 $sql = "UPDATE aluno SET
          ed47_c_foto = '',
          ed47_o_oid = 0
         WHERE ed47_i_codigo = $chavepesquisa
        ";
 $result = db_query($sql);
 db_redireciona("edu1_alunodados002.php?chavepesquisa=$chavepesquisa");
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
<?php
  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("strings.js");
  db_app::load("dbmessageBoard.widget.js");
  db_app::load("windowAux.widget.js");
  db_app::load("dbtextFieldData.widget.js");
  db_app::load("datagrid.widget.js");
?>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <center>
   <fieldset style="width:95%"><legend><b>Altera��o de Aluno</b></legend>
    <?include("forms/db_frmalunodados.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<?
if(isset($alterar)){
 if($claluno->erro_status=="0"){
  $claluno->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($claluno->erro_campo!=""){
   echo "<script> document.form1.".$claluno->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$claluno->erro_campo.".focus();</script>";
  };
 }else{
  $claluno->erro(true,false);
  db_redireciona("edu1_alunodados002.php?chavepesquisa=$ed47_i_codigo");
 }
}
if(isset($chavepesquisa)){
 $sql3 = "SELECT *
          FROM alunoalt
          WHERE ed275_i_aluno = $chavepesquisa
          AND ed275_i_usuario != ".db_getsession("DB_id_usuario")."
          AND not exists(select * from alunoaltconf
                         where ed277_i_alunoalt = ed275_i_codigo
                         and ed277_i_usuario = ".db_getsession("DB_id_usuario")."
                        )
         ";
 $result3 = db_query($sql3);
 if(pg_num_rows($result3)>0){
  ?>
  <script>
    js_OpenJanelaIframe('','db_iframe_alunoalterado','edu1_alunodados004.php?aluno=<?=$chavepesquisa?>','Altera��es no Cadastro de Aluno',true,0,0,screen.availWidth-40,1000);
  </script>
  <?
 }
 ?>
 <script>
   js_buscaCidadaoFiliacao();
 </script>
 <?php 
}
if($db_opcao==22){
 echo "<script>document.form1.pesquisar.click();</script>";
}
?>