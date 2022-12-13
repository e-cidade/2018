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


$sDataVotacao       = substr($iData,6,4)."-".substr($iData,3,2)."-".substr($iData,0,2);

$sSqlDadosTelEscola = $clTelefoneEscola->sql_query("",
                                                   "ed26_i_ddd,ed26_i_numero,ed26_i_ramal",
                                                   "",
                                                   "ed26_i_escola = $iEscola LIMIT 1"
                                                  );

                                     
if ($clTelefoneEscola->numrows == 0) {    
  $sTelEscola = "";  
} 
  $rsDadosTelEscola   = db_query($sSqlDadosTelEscola);
  $oDadosTelEscola    = db_utils::fieldsMemory($rsDadosTelEscola,0);
  $iDdd               = $oDadosTelEscola->ed26_i_ddd;
  $iNumero            = $oDadosTelEscola->ed26_i_numero;
  $sTelEscola         =  $iDdd." ".$iNumero ;


$sCampos           = " ed18_c_nome as nome_escola,j14_nome as rua_escola,ed18_c_cep as cep_escola, ";
$sCampos          .= " j13_descr as bairro_escola, ed18_i_numero as num_escola, ed18_codigoreferencia,";
$sCampos          .= " ed261_c_nome as mun_escola,ed260_c_sigla as uf_escola,ed18_c_email as email_escola ";
$sSqlDadosEscola   = $clEscola->sql_query("",$sCampos,"","ed18_i_codigo = $iEscola");
$rsDadosEscola     = db_query($sSqlDadosEscola);
$oDadosEscola      = db_utils::fieldsMemory($rsDadosEscola,0);
$nome_escola       = $oDadosEscola->nome_escola;
$rua_escola        = $oDadosEscola->rua_escola;
$cep_escola        = $oDadosEscola->cep_escola;
$num_escola        = $oDadosEscola->num_escola;
$mun_escola        = $oDadosEscola->mun_escola;
$uf_escola         = $oDadosEscola->uf_escola;
$bairro_escola     = $oDadosEscola->bairro_escola;
$email_escola      = $oDadosEscola->email_escola;
$iCodigoReferencia = $oDadosEscola->ed18_codigoreferencia;

/**
 * Verifica se a escola possui código referência e o adiciona na frente do nome
 */
if ( $iCodigoReferencia != null ) {
  $nome_escola = "{$iCodigoReferencia} - {$nome_escola}";
}

$sCampoMatricula  = " ed29_i_codigo,fc_idade(ed47_d_nasc,'$sDataVotacao'::date) as idadealuno,ed47_v_nome,ed11_c_descr,ed10_c_abrev,";
$sCampoMatricula .= " ed47_v_mae, ed47_d_nasc, turma.ed57_i_codigo,aluno.ed47_i_codigo,turma.ed57_c_descr,ed29_c_descr";
$sOrderMatricula  = " turma.ed57_i_codigo,ed29_c_descr,ed11_c_descr,turma.ed57_c_descr,to_ascii(aluno.ed47_v_nome)";
$sWhereMatricula  = " ed60_c_ativa='S' and ed60_c_situacao='MATRICULADO' and turma.ed57_i_calendario = $calendario";

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
                                                  
$sCampos             = "ed217_t_cabecalho,ed217_t_rodape,ed217_t_obs";
$sSqlDadosRelatModel = $clEduRelatmodel->sql_query("",$sCampos,"","ed217_i_codigo = 3");
$rsDadosRelatModel   = $clEduRelatmodel->sql_record($sSqlDadosRelatModel);

$sCabecalho = "";
if ($clEduRelatmodel->numrows > 0) {
    
  $oDadosRelatModel  = db_utils::fieldsMemory($rsDadosRelatModel,0);
  $sCabecalho        = $oDadosRelatModel->ed217_t_cabecalho;
  
}
                                          
$fpdf = new FPDF();
$fpdf->Open();
$fpdf->AliasNbPages();
$fpdf->SetAutoPageBreak(false,1);
$data         = date("Y-m-d",DB_getsession("DB_datausu"));
$dia          = date("d");
$mes          = date("m");
$ano          = date("Y");
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
$data_extenso = $mun_escola.", ".$dia." de ".$mes_extenso[$mes]." de ".$ano.".";
$fpdf->setXY(100,5);	
$fpdf->setfont('times','b',8);
$fpdf->setXY(70,5);
$fpdf->multicell(90,4,"Estabelecimento: " ,0,"C",0,0);
$fpdf->setXY(60,5);
$fpdf->setfont('times','',9);
$fpdf->multicell(90,4,"                           ".$sCabecalho,0,"L",0,0);
$fpdf->setXY(60,5);
$fpdf->setfillcolor(223);
$fpdf->setfont('times','b',8);
$fpdf->cell(10,4,"Cód",1,0,"L",1);
$fpdf->cell(21,4,"Data Nasc.",1,0,"R",1);
$fpdf->cell(75,4,"Alunos Votantes",1,0,"L",1);
$fpdf->cell(70,4,"Assinaturas",1,1,1);
db_fieldsmemory($sResult,0);
db_fieldsmemory($sResultParametros,0);
$iCodigo = 0;
$iTotal  = 0;
$iCount  = 0;
	
  $iCont   = 0;       
  $iLimite = 0;  
  $iPg     = 1;
  $fpdf->ln(5);
  $fpdf->addpage('P');        
  $fpdf->setfillcolor(223);
  $fpdf->setfont('times','',8);
  $fpdf->cell(20,5,"{$nome_escola}",0,0,"L",0);
  $fpdf->cell(170,5,"Emissão:".$iData,0,1,"R",0);
  $fpdf->setfont('times','',10);
  $fpdf->cell(180,5,"Alunos ativos menores de $ed233_i_idadevotacao anos",0,1,"C",0);
  $fpdf->setfont('times','b',8);
  $fpdf->cell(10,5,"Cód.",1,0,"C",1);
  $fpdf->cell(16,5,"Dt.Nasc. ",1,0,"C",1);
  $fpdf->cell(5,5,"ID",1,0,"C",1);
  $fpdf->cell(8,5,"Tur",1,0,"C",1);
  $fpdf->cell(5,5,"RV",1,0,"C",1);
  $fpdf->cell(75,5,"Alunos",1,0,"C",1);
  $fpdf->cell(70,5,"Responsáveis votantes          Pg:".  $iPg,1,1,"C",1);
  $sCampoMatricula  = "ed47_c_nomeresp,ed47_v_mae,ed47_v_pai,ed29_i_codigo,ed10_c_abrev,ed11_c_abrev,";
  $sCampoMatricula .= " fc_idade(ed47_d_nasc,'$sDataVotacao'::date) as idadealuno,ed47_v_nome, ed47_v_mae, ed47_d_nasc,";
  $sCampoMatricula .= "  turma.ed57_i_codigo,aluno.ed47_i_codigo,turma.ed57_c_descr, ed11_c_descr, ed29_c_descr" ;
  $sOrderMatricula  = "to_ascii(aluno.ed47_v_nome)";
  $sWhereMatricula  = "ed60_c_ativa='S' and ed60_c_situacao='MATRICULADO' and turma.ed57_i_calendario = $calendario";
  $sResult          = $clMatricula->sql_record($clMatricula->sql_query("",
                                                                       $sCampoMatricula,
                                                                       $sOrderMatricula,
                                                                       $sWhereMatricula
                                                                       )                                                               
                                              );
  $iPg ++;    
          
  for ($x = 0; $x < $clMatricula->numrows; $x++) {
 	db_fieldsmemory($sResult,$x);
 	db_fieldsmemory($sResultParametros,0);
 	$fpdf->setfont('times','',7);
 	$abrev = substr($ed11_c_abrev,3,4);       
    if ($abrev == "A") {
  	  $letra = "a";
    } else {
  	  $letra = "";
    }               	
 	if ($iCont==50) {
 	  $fpdf->setY(285); 
      $fpdf->line(10, $fpdf->getY(), 200, $fpdf->getY());
      $fpdf->setX(95);
      $fpdf->setfont('times','b',7);
      $fpdf->cell(20, 5, "Rua $rua_escola, $num_escola -$bairro_escola - $mun_escola / $uf_escola - $cep_escola 
                    Fone/Fax : $sTelEscola - e-mail: $email_escola", 0, 1, "C", 0
                 ); 
 	  $fpdf->ln(5);
      $fpdf->addpage('P');        
      $fpdf->setfillcolor(223);
      $fpdf->setfont('times','b',8);
      $fpdf->cell(10,5,"Cód.",1,0,"C",1);
      $fpdf->cell(16,5,"Dt.Nasc. ",1,0,"C",1);
      $fpdf->cell(5,5,"ID",1,0,"C",1);
      $fpdf->cell(8,5,"Tur",1,0,"C",1);
      $fpdf->cell(5,5,"RV",1,0,"C",1);
      $fpdf->cell(75,5,"Alunos",1,0,"C",1);
      $fpdf->cell(70,5,"Responsáveis votantes          Pg:".  $iPg,1,1,"C",1);
      $iCont = 0;
      $iPg++;
      
      
 	}
    if ($idadealuno < $ed233_i_idadevotacao ) {  
    	
      $fpdf->setfont('times','',8);  	
      $fpdf->cell(10,5,$ed47_i_codigo,1,0,"R",0);
 	  $fpdf->cell(16,5,db_formatar($ed47_d_nasc,'d'),1,0,"R",0);
 	  $fpdf->cell(5,5,$idadealuno,1,0,"R",0);
      $fpdf->cell(8,5,$ed57_c_descr."".$letra,1,0,"R",0);
      $fpdf->cell(5,5,"",1,0,"R",0);
      $fpdf->cell(75,5,$ed47_v_nome,1,0,"L",0);
      $fpdf->cell(70,5,"",1,1,"L",0);
      $iCont++;
      $iLimite++;
 	}
    
	
}

$fpdf->setX(72);
$fpdf->setfont('times','',10);
$sLegenda  = "Total: ".$iLimite. "     ".      "ID=Idade em ". db_formatar($sDataVotacao,'d');
$sLegenda .= " RV=Responsável votante: P-pai M-mãe R-outro responsável";
$fpdf->cell(20, 5,$sLegenda, 0, 1, "C", 0);  
$fpdf->setY(285);  
$fpdf->line(10, $fpdf->getY(), 200, $fpdf->getY());
$fpdf->setX(95);
$fpdf->setfont('times','b',7);
$fpdf->cell(20, 5, "Rua $rua_escola, $num_escola -$bairro_escola - $mun_escola / $uf_escola - $cep_escola 
                    Fone/Fax : $sTelEscola - e-mail: $email_escola", 0, 1, "C", 0
           ); 
$fpdf->Output();
?>