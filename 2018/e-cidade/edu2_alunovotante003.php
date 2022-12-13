<?
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
 
require_once("libs/db_stdlibwebseller.php");
require_once("fpdf151/scpdf.php");
require_once("classes/db_calendario_classe.php");
require_once("classes/db_periodocalendario_classe.php");
require_once("classes/db_escoladiretor_classe.php");
require_once("classes/db_escola_classe.php");
require_once("classes/db_edu_parametros_classe.php");
require_once("classes/db_matricula_classe.php");  
require_once("classes/db_edu_relatmodel_classe.php"); 
require_once("classes/db_telefoneescola_classe.php");
require_once("libs/db_utils.php");   
$iEscola            = db_getsession("DB_coddepto");
$sNomeEscola        = db_getsession("DB_nomedepto");
$clMatricula        = new cl_matricula();
$clEduRelatmodel    = new cl_edu_relatmodel();
$clTelefoneEscola   = new cl_telefoneescola();
$clEduParametros    = new cl_edu_parametros();
$clEscola           = new cl_escola();
$sSecret = $sSecretario;
$sPresid = $sPresidente;

function maiusculo(&$string) {
	
  $string = strtoupper($string);
  $string = str_replace("Ã¡","Ã",$string);
  $string = str_replace("Ã©","Ã",$string);
  $string = str_replace("Ã­","Ã",$string);
  $string = str_replace("Ã³","Ã",$string);
  $string = str_replace("Ãº","Ã",$string);
  $string = str_replace("Ã¢","Ã",$string);
  $string = str_replace("Ãª","Ã",$string);
  $string = str_replace("Ã´","Ã",$string);
  $string = str_replace("Ã®","Ã",$string);
  $string = str_replace("Ã»","Ã",$string);
  $string = str_replace("Ã£","Ã",$string);
  $string = str_replace("Ãµ","Ã",$string);
  $string = str_replace("Ã§","Ã",$string);
  $string = str_replace("Ã ","Ã",$string);
  $string = str_replace("Ã¨","Ã",$string);
  return $string;
  
}
$sDataVotacao       = substr($iData,6,4)."-".substr($iData,3,2)."-".substr($iData,0,2);

 $sSqlDadosTelEscola = $clTelefoneEscola->sql_query("",
                                                   "ed26_i_ddd,ed26_i_numero,ed26_i_ramal",
                                                   "",
                                                   "ed26_i_escola = $iEscola LIMIT 1"
                                                  );
                                                  
$rsDadosTelEscola   = db_query($sSqlDadosTelEscola);
                                        
if ($clTelefoneEscola->numrows == 0) {
  
  $sTelEscola = "";
} 
  $oDadosTelEscola    = db_utils::fieldsMemory($rsDadosTelEscola,0); 
  $iDdd               = $oDadosTelEscola->ed26_i_ddd;
  $iNumero            = $oDadosTelEscola->ed26_i_numero;
  $sTelEscola         =  $iDdd." ".$iNumero ;	



$sCampos         = " ed18_c_nome as nome_escola,j14_nome as rua_escola,ed18_c_cep as cep_escola, ed18_codigoreferencia, ";
$sCampos        .= " ed18_i_numero as num_escola,ed261_c_nome as mun_escola,ed260_c_sigla as uf_escola, ";
$sCampos        .= " ed18_c_email as email_escola, j13_descr as bairro_escola";
$sSqlDadosEscola = $clEscola->sql_query("",$sCampos,"","ed18_i_codigo = $iEscola");
$rsDadosEscola   = db_query($sSqlDadosEscola);
$oDadosEscola    = db_utils::fieldsMemory($rsDadosEscola,0);
$nome_escola     = $oDadosEscola->nome_escola;
$rua_escola      = $oDadosEscola->rua_escola;
$cep_escola      = $oDadosEscola->cep_escola;
$num_escola      = $oDadosEscola->num_escola;
$mun_escola      = $oDadosEscola->mun_escola;
$uf_escola       = $oDadosEscola->uf_escola;
$bairro_escola   = $oDadosEscola->bairro_escola;
$email_escola    = $oDadosEscola->email_escola;
$iCodigoReferencia = $oDadosEscola->ed18_codigoreferencia;

$sCampoMatricula  = "ed47_c_nomeresp,ed47_v_mae,ed47_v_pai,ed29_i_codigo,ed10_c_abrev,ed11_c_abrev,";
$sCampoMatricula .= " fc_idade(ed47_d_nasc,'$sDataVotacao'::date) as idadealuno,ed47_v_nome, ed47_v_mae, ed47_d_nasc,";
$sCampoMatricula .= "  turma.ed57_i_codigo,aluno.ed47_i_codigo,turma.ed57_c_descr, ed11_c_descr, ed29_c_descr" ;
$sOrderMatricula  = "turma.ed57_i_codigo,ed29_c_descr,ed11_c_descr,turma.ed57_c_descr,to_ascii(aluno.ed47_v_nome)";
$sWhereMatricula  = "ed60_c_ativa='S' and ed60_c_situacao='MATRICULADO' and ed220_i_codigo in ($turmas)";
$sResult          = $clMatricula->sql_record($clMatricula->sql_query("",
                                                                     $sCampoMatricula,
                                                                     $sOrderMatricula,
                                                                     $sWhereMatricula
                                                                    )                                                               
                                            );
if ($clMatricula->numrows == 0) {
	
  db_fieldsmemory($sResult,0);
  ?>
  <table width='100%'>
   <tr>
    <td align='center'>
     <font color='#FF0000' face='arial'>
      <b>Nenhum registro encontrado.<br>
      <input type='button' value='Fechar' onclick='window.close()'></b>
     </font>
    </td>
   </tr>
  </table>
  <?
  exit;
  
}

$sResultParametros = $clEduParametros->sql_record($clEduParametros->sql_query("",
                                                                                "ed233_i_idadevotacao",
                                                                                "",
                                                                                "ed233_i_escola = $iEscola"
                                                                               )
                                                  );
                                                  
$sCampo              = "ed217_t_cabecalho,ed217_t_rodape,ed217_t_obs";
$sSqlDadosRelatModel = $clEduRelatmodel->sql_query("",$sCampo,"","ed217_i_codigo = {$iTipoModelo}");
$rsDadosRelatModel   = $clEduRelatmodel->sql_record($sSqlDadosRelatModel);
if ($clEduRelatmodel->numrows > 0) {
    
  $oDadosRelatModel  = db_utils::fieldsMemory($rsDadosRelatModel,0);
  $sCabecalho        = $oDadosRelatModel->ed217_t_cabecalho;
  $sObservacao       = $oDadosRelatModel->ed217_t_obs;
  
}
                                                  
$fpdf = new FPDF();
$fpdf->Open();
$fpdf->AliasNbPages();
$fpdf->ln(5);
$fpdf->SetAutoPageBreak(false,1);
$fpdf->Addpage('L');  

$data         = date($sDataVotacao);
$sDataExtenso        = db_dataextenso(db_strtotime($sDataVotacao));
$mes_extenso  = array("01"=>"janeiro",
                      "02"=>"fevereiro",
                      "03"=>"março",
                      "04"=>"abril",
                      "05"=>"maio",
                      "06"=>"junho",
                      "07"=>"julho",
                      "08"=>"agosto",
                      "09"=>"setembro",
                      "10"=>"outubro",
                      "11"=>"novembro",
                      "12"=>"dezembro"
                     );
$cidade = strtolower($mun_escola);     
$cidadeescola =   ucfirst($cidade);       
$sDataFinal = $cidadeescola.", ".$sDataExtenso;
db_fieldsmemory($sResult,0);

if ( trim($iCodigoReferencia) != null ) {
  $sNomeEscola = "{$iCodigoReferencia} - {$sNomeEscola}";
}

$fpdf->setXY(100,5);  
$fpdf->setfont('times','',12);
$fpdf->setXY(10,5);
$fpdf->multicell(180,4,"Estabelecimento: ".$sNomeEscola,0,"L",0,0);
$fpdf->setXY(121,5);
$fpdf->setfont('times','',12);
$fpdf->cell(220,4,"ELEIÇÃO DO DIRETOR ESCOLAR - Data da votação: $iData",0,1,"C",0);
$fpdf->setXY(20,15);
$fpdf->setfont('times','',12);
$fpdf->multicell(260,5,"                           ".$sCabecalho,0,"L",0,0);
$fpdf->setXY(90,28);
$fpdf->setfont('times','b',14);
$fpdf->cell(50,5,"Turma  $ed57_c_descr ",0,0,0);
$fpdf->setfont('times','',10);
$fpdf->cell(35,5," $ed11_c_descr    $ed10_c_abrev",0,1,0);
$fpdf->setfillcolor(223);
$fpdf->setY(35);
$fpdf->setfont('times','b',10);
$fpdf->cell(10,5,"Cód",1,0,"C",1);
$fpdf->cell(90,5,"Alunos / Assinaturas dos Responsáveis ",1,0,"C",1);
$fpdf->cell(90,5,"Pai",1,0,"C",1);
$fpdf->cell(90,5,"Mãe",1,1,"C",1);
db_fieldsmemory($sResult,0);
$iCodigo = $ed57_i_codigo;
$iTotal  = 0;
$iCount  = 0;

for ($x = 0; $x < $clMatricula->numrows; $x++) {

  db_fieldsmemory($sResult,$x);
  db_fieldsmemory($sResultParametros,0);

  if ($iCodigo != $ed57_i_codigo) {

    if ($fpdf->getY() >= $fpdf->h - 60 ) {
      
      escreveRodape($fpdf, $rua_escola, $num_escola, $bairro_escola, $mun_escola, $uf_escola, $cep_escola, $sTelEscola, $email_escola);
      $fpdf->addPage('L');
      escreveCabecalho($fpdf, $sNomeEscola, $iData, $sCabecalho);
    }

    escreveObservacao($fpdf, $sObservacao);

    if ($x != 0) {
    	
  	  $fpdf->setfont('times','',11);
  	  $final =$fpdf->getY();
      $fpdf->setY($final+4);
      $fpdf->cell(212,5,"Total : " .$iTotal,0,0,"L",0);
      $fpdf->cell(222,5,$sDataFinal ,0,0,"L",0);    
      $fpdf->setfont('times','',10);
      $fim = $fpdf->getY();
      $fpdf->setY($fim+11);    
      $fpdf->setX(10);
      $fpdf->cell(45, 5, "____________________________________________", 0, 0, "L", 0);
      $fpdf->setX(193);
      $fpdf->cell(45, 5, "____________________________________________", 0, 1, "L", 0);    
      $secretario = $fpdf->getY();
      $fpdf->setX(10);
      $fpdf->setfont('times','',10);
      $fpdf->multicell(94,4,$sSecret,0,"L",0,0);
      $fpdf->setY($secretario);
      $fpdf->setX(193);
      $fpdf->multicell(94,4,$sPresid,0,"L",0,0);
      $fpdf->setX(10);
      $fpdf->cell(80, 5, "Secretário(a) da Comissão proc. escolha Diretor Escolar", 0, 0,"L", 0);
      $fpdf->setX(193);
      $fpdf->cell(80, 5, "Presidente da Comissão proc. escolha Diretor Escolar", 0, 1, "L", 0);   
      $fpdf->setY(203);
      $fpdf->line(10, $fpdf->getY(), 285, $fpdf->getY());
      $fpdf->setX(85);
      $fpdf->setfont('times','b',7);
      $fpdf->cell(40, 5, "RUA  $rua_escola, $num_escola - $bairro_escola - $mun_escola / $uf_escola - $cep_escola 
                    Fone/Fax : $sTelEscola - e-mail: $email_escola", 0, 1, "C", 0
                 ); 
 	  $fpdf->ln(5);
      $fpdf->Addpage('L');
      	
    }  
               
     
    $fpdf->setXY(100,5);  
    $fpdf->setfont('times','',12);
    $fpdf->setXY(10,5);
    $fpdf->multicell(180,4,"Estabelecimento: ".$sNomeEscola,0,"L",0,0);
    $fpdf->setXY(121,5);
    $fpdf->setfont('times','',12);
    $fpdf->cell(220,4,"ELEIÇÃO DO DIRETOR ESCOLAR - Data da votação: $iData",0,1,"C",0);
    $fpdf->setXY(20,15);
    $fpdf->setfont('times','',12);
    $fpdf->multicell(260,5,"                           ".$sCabecalho,0,"L",0,0);
    $fpdf->setXY(90,28);
    $fpdf->setfont('times','b',14);
    $fpdf->cell(50,5,"Turma  $ed57_c_descr ",0,0,0);
    $fpdf->setfont('times','',10);
    $fpdf->cell(35,5," $ed11_c_descr    $ed10_c_abrev",0,1,0);
    $fpdf->setfillcolor(223);
    $fpdf->setY(35);
    $fpdf->setfont('times','b',10);
    $fpdf->cell(10,5,"Cód",1,0,"C",1);
    $fpdf->cell(90,5,"Alunos / Assinaturas dos Responsáveis ",1,0,"C",1);
    $fpdf->cell(90,5,"Pai",1,0,"C",1);
    $fpdf->cell(90,5,"Mãe",1,1,"C",1);
    $iCodigo = $ed57_i_codigo;    
    $iTotal = 0;
    $iTotalpagina=1;
  }
   
  
  if ($idadealuno < $ed233_i_idadevotacao) {
   	    	   	
   	$fpdf->setfont('times','',6);
    $fpdf->SetWidths(array(10,80,90,90));  
    $fpdf->SetAligns(array("L", "L","L","L"));     
          
    if ($ed47_c_nomeresp != $ed47_v_mae && $ed47_c_nomeresp != $ed47_v_pai) {
      $nomeresp = $ed47_c_nomeresp;       
    } else {
      $nomeresp = "";	
    }
    $fpdf->setfont('times','',8);
    $fpdf->cell(10,4,$ed47_i_codigo,"LRT",0,"R",0); 
    $fpdf->setfont('times','b',7);   
 	$fpdf->cell(90,4,$ed47_v_nome,"LRT",0,"L",0);
 	$fpdf->setfont('times','',7);
 	$fpdf->cell(90,4,$ed47_v_pai,"LRT",0,"L",0);
    $fpdf->cell(90,4,$ed47_v_mae,"LRT",1,"L",0);   
   	$fpdf->cell(10,4,"","LRB",0,"R",0);
 	$fpdf->cell(90,4,$nomeresp,"LRB",0,"L",0);
 	$fpdf->cell(90,4,"","LRB",0,"L",0);
    $fpdf->cell(90,4,"","LRB",1,"L",0);
     
    if ($fpdf->getY() >= $fpdf->h - 38 ) {
   	
   	  $final =$fpdf->getY();
      $fpdf->setY($final+18);
      $fpdf->line(10, $fpdf->getY(), 285, $fpdf->getY());
      $fpdf->setX(110);
      $fpdf->setfont('times','b',7);
      $fpdf->cell(20, 5, "RUA $rua_escola, $num_escola - $bairro_escola - $mun_escola / $uf_escola - $cep_escola 
                        Fone/Fax : $sTelEscola - e-mail: $email_escola", 0, 1, "C", 0
                 );   
      $fpdf->Addpage('L');
      $fpdf->setfont('times','',12);
      $fpdf->multicell(180,4,$sNomeEscola,0,"L",0,0);
      $fpdf->setXY(120,10);
      $fpdf->setfont('times','b',14);
      $fpdf->cell(50,5,"Turma  $ed57_c_descr ",0,0,0);
      $fpdf->setfont('times','',10);
      $fpdf->cell(35,5," $ed11_c_descr    $ed10_c_abrev",0,1,0);
      $fpdf->setfillcolor(223);
      $fpdf->setY(15);
      $fpdf->setfont('times','b',10);
      $fpdf->cell(10,5,"Cód",1,0,"C",1);
      $fpdf->cell(90,5,"Alunos / Assinaturas dos Responsáveis ",1,0,"C",1);
      $fpdf->cell(90,5,"Pai",1,0,"C",1);
      $fpdf->cell(90,5,"Mãe",1,1,"C",1);
      $iTotalpagina=1;
    }
    $iTotal++;
  }   
}
$fpdf->setfont('times','',11);
$final =$fpdf->getY();
$fpdf->setY($final+4);
$fpdf->cell(212,5,"Total : " .$iTotal,0,0,"L",0);
$fpdf->cell(222,5,$sDataFinal ,0,0,"L",0);
$fpdf->setfont('times','',10);
$fim = $fpdf->getY();
$fpdf->setY($fim+15);    
$fpdf->setX(10);
$fpdf->cell(45, 5, "____________________________________________", 0, 0, "L", 0);
$fpdf->setX(193);
$fpdf->cell(45, 5, "____________________________________________", 0, 1, "L", 0);    
$secretario = $fpdf->getY();
$fpdf->setX(10);
$fpdf->setfont('times','',10);
$fpdf->multicell(94,4,$sSecret,0,"L",0,0);
$fpdf->setY($secretario);
$fpdf->setX(193);
$fpdf->multicell(94,4,$sPresid,0,"L",0,0);
$fpdf->setX(10);
$fpdf->cell(80, 5, "Secretário(a) da Comissão proc. escolha Diretor Escolar", 0, 0,"L", 0);
$fpdf->setX(193);
$fpdf->cell(80, 5, "Presidente da Comissão proc. escolha Diretor Escolar", 0, 1, "L", 0);   
$fpdf->setY(203);
$fpdf->line(10, $fpdf->getY(), 285, $fpdf->getY());
$fpdf->setX(85);
$fpdf->setfont('times','b',7);
$fpdf->cell(40, 5, "RUA $rua_escola, $num_escola - $bairro_escola - $mun_escola / $uf_escola - $cep_escola 
                    Fone/Fax : $sTelEscola - e-mail: $email_escola", 0, 1, "C", 0
            ); 

function escreveCabecalho($fpdf, $sNomeEscola, $iData, $sCabecalho) {

  $fpdf->setXY(100,5);  
  $fpdf->setfont('times','',12);
  $fpdf->setXY(10,5);
  $fpdf->multicell(180,4,"Estabelecimento: ".$sNomeEscola,0,"L",0,0);
  $fpdf->setXY(121,5);
  $fpdf->setfont('times','',12);
  $fpdf->cell(220,4,"ELEIÇÃO DO DIRETOR ESCOLAR - Data da votação: $iData",0,1,"C",0);
  $fpdf->setXY(20,15);
  $fpdf->setfont('times','',12);
  $fpdf->multicell(260,5,"                           ".$sCabecalho,0,"L",0,0);
}

function escreveRodape($fpdf, $rua_escola, $num_escola, $bairro_escola, $mun_escola, $uf_escola, $cep_escola, $sTelEscola, $email_escola) {
  
  $fpdf->setY(203);
  $fpdf->line(10, $fpdf->getY(), 285, $fpdf->getY());
  $fpdf->setX(85);
  $fpdf->setfont('times','b',7);
  $fpdf->cell(40, 5, "RUA $rua_escola, $num_escola - $bairro_escola - $mun_escola / $uf_escola - $cep_escola 
                      Fone/Fax : $sTelEscola - e-mail: $email_escola", 0, 1, "C", 0
              ); 
}

function escreveObservacao($fpdf, $sObservacao) {

  if ( empty($sObservacao) ) {
    return;
  }

  $fpdf->Ln();
  $fpdf->setfont('times','b',7);
  $fpdf->cell(65, 5, "Observações", 0, 1,"L", 0);
  $fpdf->setfont('times','',7);
  $fpdf->multicell(280, 5, $sObservacao, 1, 1, "L");
}

$fpdf->Output();
?>