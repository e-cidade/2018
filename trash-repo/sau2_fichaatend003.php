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

include("fpdf151/scpdf.php");

include("libs/db_sql.php");
include("libs/db_stdlibwebseller.php");
include("libs/db_utils.php");

include("classes/db_prontuarios_ext_classe.php");
include("classes/db_agendamentos_ext_classe.php");
include("classes/db_prontagendamento_classe.php");
include("classes/db_sau_config_ext_classe.php");
include("classes/db_sau_proccbo_classe.php");
include("classes/db_prontproced_ext_classe.php");
include("classes/db_prontprofatend_ext_classe.php");

include("dbforms/db_funcoes.php");


parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

set_time_limit(0);

$clprontuarios      = new cl_prontuarios_ext;
$clagendamentos     = new cl_agendamentos_ext;
$clprontagendamento = new cl_prontagendamento;
$clsau_config       = new cl_sau_config_ext;
$clsau_proccbo      = new cl_sau_proccbo;
$clprontproced      = new cl_prontproced_ext;
$clprontprofatend   = new cl_prontprofatend_ext;

try {
	$result_und = $clprontuarios->sql_record( "select unidades.*, cgm.z01_nome as estabelecimento,
	                                              cgm.z01_ender as est_ender,
	                                              cgm.z01_bairro as est_bairro,
	                                              cgm.z01_munic as est_munic,
	                                              cgm.z01_uf as est_uf
	                                         from unidades
	                                        inner join db_depart on db_depart.coddepto = unidades.sd02_i_codigo
	                                        inner join cgm on cgm.z01_numcgm = unidades.sd02_i_numcgm
	                                        where unidades.sd02_i_codigo = $unidade
	                                      "
	                                    );
	$obj_sau_config = db_utils::fieldsMemory( $clsau_config->sql_record( $clsau_config->sql_query_ext() ), 0 );
}catch ( Exception $e ){
	die("erro");
}

if( $clprontuarios->numrows == 0 ){
 echo "<table width='100%'>
        <tr>
         <td align='center'><font color='#FF0000' face='arial'><b>Verifique se o código $unidade esta cadastrada como unidade.<br><input type='button' value='Fechar' onclick='window.close()'></b></font></td>
        </tr>
       </table>";
 exit;
}

$pdf = new SCPDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(243);
$pdf->addpage('P');
$pdf->ln(0);
$pdf->SetLineWidth(0.5);

//Gera FA do agendamento
if( isset($agendamentofa) && $agendamentofa==true ){

	$ano = substr( $sd23_d_consulta, 6, 4 );
	$mes = substr( $sd23_d_consulta, 3, 2 );
	$dia = substr( $sd23_d_consulta, 0, 2 );

	if( isset($codigos) ){
		$codigos = " and sd23_i_codigo in ($codigos)";
	}

	$res_agendamento = $clagendamentos->sql_record( $clagendamentos->sql_query_ext("","*, fc_totalagendado('$ano/$mes/$dia',$sd27_i_codigo,$chave_diasemana)","sd30_c_horaini,z01_d_nasc desc",
													"sd23_d_consulta = '$ano/$mes/$dia'
													$codigos
													and not exists ( select *
		            													from agendaconsultaanula
		            													where s114_i_agendaconsulta = sd23_i_codigo
		            												)
													and sd27_i_codigo = $sd27_i_codigo") );
	$obj_agendamento = db_utils::fieldsMemory($res_agendamento,0);
	$arr_totalagenda = explode(",", $obj_agendamento->fc_totalagendado );
	$qtd = $arr_totalagenda[5] > $clagendamentos->numrows?$clagendamentos->numrows:$arr_totalagenda[5];
  $intProfissional = $sd27_i_codigo;
}


db_inicio_transacao();

for( $intQtd = 1; $intQtd <= $qtd; $intQtd++ ){
	if( $intQtd > 1){
		$pdf->AddPage();
	}

	//linca agendamento com prontuario
	if( isset($agendamentofa) && $agendamentofa==true ){
		$obj_agendamento = db_utils::fieldsMemory($res_agendamento,($intQtd-1));
		$clprontagendamento->sql_record($clprontagendamento->sql_query(null,"*",null,"s102_i_agendamento = {$obj_agendamento->sd23_i_codigo}"));

		if( $clprontagendamento->numrows > 0){
			echo "<table width='100%'>
		        <tr>
		         <td align='center'><font color='#FF0000' face='arial'><b>Agendamento:{$obj_agendamento->sd23_i_codigo} de {$obj_agendamento->z01_v_nome} ja possui prontuário. <p> Para emitir novamente o prontuário entre em [Ficha de Atendimento].<br><input type='button' value='Fechar' onclick='window.close()'></b></font></td>
		        </tr>
		       </table>";
			db_fim_transacao();
			exit;
		}
	}

	//Gerar número prontuário automático
    //gera numatend
    $sql_fc    = "select fc_numatend()";
    $query_fc  = db_query($sql_fc) or die(pg_errormessage()."<br>$sql_fc  <br> $intQtd");
    $fc_numatend = explode(",",pg_result($query_fc,0,0));

    $clprontuarios->sd24_i_ano      = trim($fc_numatend[0]);
    $clprontuarios->sd24_i_mes      = trim($fc_numatend[1]);
    $clprontuarios->sd24_i_seq      = trim($fc_numatend[2]);
    $clprontuarios->sd24_i_login    = DB_getsession("DB_id_usuario");
    $clprontuarios->sd24_i_unidade  = $unidade;
    $clprontuarios->sd24_i_numcgs   = isset($agendamentofa)?$obj_agendamento->sd23_i_numcgs:null;
    $clprontuarios->sd24_d_cadastro = date("Y-m-d",db_getsession("DB_datausu"));
    $clprontuarios->sd24_c_cadastro = db_hora();
    $clprontuarios->incluir("");
    if($clprontuarios->erro_status=="0"){
		 echo "<table width='100%'>
		        <tr>
		         <td align='center'><font color='#FF0000' face='arial'><b>Prontuários: ".$clprontuarios->erro_msg."<br><input type='button' value='Fechar' onclick='window.close()'></b></font></td>
		        </tr>
		       </table>";
		db_fim_transacao();
		exit;
	}

	//linca agendamento com prontuario
	if( isset($agendamentofa) && $agendamentofa==true ){
		$clprontagendamento->s102_i_agendamento = $obj_agendamento->sd23_i_codigo;
		$clprontagendamento->s102_i_prontuario  = $clprontuarios->sd24_i_codigo;
		$clprontagendamento->incluir("");
		//Profissional de Atendimento
		$clprontprofatend->s104_i_prontuario   = $clprontuarios->sd24_i_codigo;
		$clprontprofatend->s104_i_profissional = $intProfissional;
		$clprontprofatend->incluir("");

	}


  $result_pront = $clprontuarios->sql_record( $clprontuarios->sql_query_ext( $clprontuarios->sd24_i_codigo ) );
	db_fieldsmemory( $result_pront, 0 );
	$result_proc = db_query($clprontproced->sql_query_ext(null, "sd29_i_profissional,rh70_estrutural,sd63_c_procedimento, sd29_t_tratamento", null, "sd29_i_prontuario = {$clprontuarios->sd24_i_codigo}"));

	//Posições
	$alt = $pdf->getY();
	$lar = $pdf->getX();
	$setY = 0;

	//for( $y=1; $y<=2; $y++ ){
	//a pedido do Reis
	for( $y=1; $y<=1; $y++ ){
		$alt = $pdf->setY($setY);
	  $lar = $pdf->setX(0);

	  //Linha 1 - 1ª Retangulo SIA/SUA
	  $pdf->rect( $pdf->getX(), $pdf->getY(), 56, 16, "D");
	  $pdf->rect( $pdf->getX()+6, $pdf->getY()+1, 5, 14, "D");
	  $pdf->rect( $pdf->getX()+1, $pdf->getY()+6, 15, 5, "D");
	  $pdf->setfont('arial','b',8);
	  $pdf->text( $pdf->getX()+17,$pdf->getY()+3,"SIAA/SUS-RS");//nome biblioteca
	  $pdf->setfont('arial','b',6);
	  $pdf->text( $pdf->getX()+17,$pdf->getY()+8,"SISTEMA DE INFORMAÇÕES");//nome biblioteca
	  $pdf->text( $pdf->getX()+17,$pdf->getY()+11,"AMBULATORIAIS");//nome biblioteca
	  $pdf->text( $pdf->getX()+17,$pdf->getY()+14,"DO SISTEMA ÚNICO DE SAÚDE");//nome biblioteca
	  //Linha 1 - 2º Retangulo
	  $pdf->setfont('arial','b',7);
	  $pdf->rect( $pdf->getX()+58, $pdf->getY(), 56, 16, "D");
	  $pdf->setfont('arial','b',7);
	  // $pdf->text( $pdf->getX()+60,$pdf->getY()+3,"NÚMERO DO ATENDIMENTO");
	  $pdf->text( $pdf->getX()+70,$pdf->getY()+5,"ATENDIMENTO Nro :   ".$sd24_i_codigo);
	  $t1 = str_pad($sd24_i_codigo,10,0,'str_pad_left');//numero codigo barras
	  $pdf->setfont('arial','b',8);
	  $pdf->SetFillColor(000);//fundo codbarras
	  //$pdf->text($pdf->getX()+59,$pdf->getY()+15,str_pad($sd24_i_codigo,10," ",'str_pad_left').' - ');
	  $pdf->int25($pdf->getX()+72,$pdf->getY()+7,$t1,5,0.341);//codbarras
	  $pdf->setfont('arial','b',7);
//	  $pdf->text( $pdf->getX()+60,$pdf->getY()+11,"FAMÍLIA PSF:");
//	  $pdf->text( $pdf->getX()+60,$pdf->getY()+14,"MICROÁREA:");
	  //Linha 1 - 3° Retangulo
	  $pdf->rect( $pdf->getX()+116, $pdf->getY(), 94, 7, "D");
	  $pdf->setfont('arial','b',10);
	  $pdf->text( $pdf->getX()+116+10, $pdf->getY()+5, "FICHA DE ATENDIMENTO AMBULATORIAL");
	  $pdf->rect( $pdf->getX()+116, $pdf->getY()+9, 94, 7, "D");
	  $pdf->setfont('arial','b',6);
	  $pdf->text( $pdf->getX()+116+1, $pdf->getY()+12, "MOTIVO DO ATENDIMENTO");
	  //$pdf->text( $pdf->getX()+116+1, $pdf->getY()+14, $sd92_c_nome);
	  $pdf->text( $pdf->getX()+116+1, $pdf->getY()+14, "");


	  //Linha 2 - 1ª Retangulo Unidade prestadora
	  db_fieldsmemory($result_und,0);
	  $pdf->rect( $pdf->getX(), $pdf->getY()+18, 56, 48, "D");
	  $pdf->setfont('arial','b',6);
	  $pdf->text( $pdf->getX()+2, $pdf->getY()+21, "UNIDADE PRESTADORA DE ATENDIMENTO");
	  $alt = $pdf->getY()+5;
	  $pdf->setfont('arial','b',7);


	  $pdf->setfont('arial','b',7);
	  $pdf->text( $pdf->getX()+2, $alt+24, "NOME DA UNIDADE: ");
	  $pdf->setfont('arial','b',6);
	  $pdf->text( $pdf->getX()+2, $alt+27,  substr($descrdepto,0,40));
	  $pdf->setfont('arial','b',7);
	  $pdf->text( $pdf->getX()+2, $alt+34, "ENDEREÇO: ");
	  $pdf->setfont('arial','b',6);
	  $pdf->text( $pdf->getX()+2, $alt+37, substr($est_ender,0,40));
	  $pdf->setfont('arial','b',7);
	  $pdf->text( $pdf->getX()+2, $alt+42, "MUNICÍPIO: ");
	  $pdf->setfont('arial','b',6);
	  $pdf->text( $pdf->getX()+2, $alt+44, substr($est_munic,0,40));
	  $pdf->setfont('arial','b',6);
	  $pdf->text( $pdf->getX()+2, $alt+50, "UF:".$est_uf);
	  $pdf->setfont('arial','b',6);
	  $pdf->text( $pdf->getX()+2, $alt+55, "CÓDIGO SIA/SUS:".$sd02_c_siasus);


	  //Linha 2 - 2ª Retangulo CBO
	  $pdf->setfont('arial','b',6);
	  $pdf->rect( $pdf->getX()+58, $pdf->getY()+18, 19, 99, "D");
	  $pdf->text( $pdf->getX()+60, $pdf->getY()+21, "CBO");
	  $pdf->line( $pdf->getX()+58, $pdf->getY()+24, $pdf->getX()+58+19, $pdf->getY()+24 );
	  //Linha 2 - 3ª Retangulo Procedimentos
	  $pdf->rect( $pdf->getX()+79, $pdf->getY()+18, 35, 99, "D");
	  $pdf->text( $pdf->getX()+80, $pdf->getY()+21, "CÓDIGO TABELA DE");
	  $pdf->text( $pdf->getX()+80, $pdf->getY()+23, "PROCEDIMENTO SIA/SUS");
	  $pdf->line( $pdf->getX()+79, $pdf->getY()+24, $pdf->getX()+74+40, $pdf->getY()+24 );
	  //Linha 2 - 4ª Retangulo Procedimentos
	  $pdf->rect( $pdf->getX()+116, $pdf->getY()+18, 94, 99, "D");
	  $pdf->text( $pdf->getX()+117, $pdf->getY()+21, "PROFISSIONAL");
	  $pdf->text( $pdf->getX()+117, $pdf->getY()+23, "SETOR");
	  $pdf->line( $pdf->getX()+116, $pdf->getY()+24, $pdf->getX()+116+94, $pdf->getY()+24 );
	  //Linha 2 - 5ª Retangulo Procedimentos
	  $pdf->rect( $pdf->getX()+134, $pdf->getY()+18, 53, 99, "D");
	  $pdf->text( $pdf->getX()+135, $pdf->getY()+21, "TRATAMENTO DESCRIÇÃO");
	  $pdf->text( $pdf->getX()+135, $pdf->getY()+23, "DOS PROCEDIMENTOS");
	  $pdf->text( $pdf->getX()+188, $pdf->getY()+21, "ASSIN. E CARIMBO");
	  $pdf->text( $pdf->getX()+188, $pdf->getY()+23, "DO PROFISSIONAL");
	  $pdf->setfont('arial','b',8);

	  $pdf->text( $pdf->getX()+135, $pdf->getY()+27, "PRESSÃO:         TEMP.:         PESO:" );
	  $pdf->line( $pdf->getX()+58, $pdf->getY()+30, $pdf->getX()+58+19, $pdf->getY()+30 );
	  $pdf->line( $pdf->getX()+79, $pdf->getY()+30, $pdf->getX()+74+40, $pdf->getY()+30 );
	  $pdf->line( $pdf->getX()+116, $pdf->getY()+30, $pdf->getX()+116+94, $pdf->getY()+30 );

	  //Prontuário / Procedimentos
	  $alt=$pdf->getY();
	  $lar=$pdf->getX();
	  $pdf->setY($alt+30);
  	$result_prontprofatend = db_query( $clprontprofatend->sql_query_ext(null,"m.z01_nome as nome_profissional,
                                                                              rhcbo.rh70_estrutural as estrutural ,
                                                                              rhcbo.rh70_descr as descricao ,
                                                                              rhcbo.rh70_tipo as rh70_tipo ,
                                                                              prontproced.sd29_i_profissional as profissional", "s104_i_codigo", "s104_i_prontuario = ".$clprontuarios->sd24_i_codigo));
		if(pg_num_rows($result_prontprofatend) > 0 ){
			db_fieldsMemory($result_prontprofatend,0);
	       $pdf->setX($lar+58);
	       $pdf->setfont('arial','',7);
	       $pdf->SetWidths(array(19,38,20,53,24));
	       $pdf->SetAligns(array("C","C","L","J","L"));
	       $nbx="";

	       $pdf->Row(array( "{$estrutural}",
	       					"{$descricao}",
	       					"{$profissional}",
	       					"{$nome_profissional}",
	       					"$nbx"
	       				  ), 3,false,3 );
	       $pdf->line( $lar+58, $pdf->getY(), $lar+58+19, $pdf->getY() );
	       $pdf->line( $lar+79, $pdf->getY(), $lar+74+40, $pdf->getY() );
	       $pdf->line( $lar+116, $pdf->getY(), $lar+116+94, $pdf->getY() );
    }
	  $pdf->setY($alt);
	  $pdf->setX($lar);



	  //Linha 3 - 1ª Retangulo Unidade prestadora
	  $pdf->rect( $pdf->getX(), $pdf->getY()+68, 56, 61, "D");
	  $pdf->setfont('arial','b',6);
	  $pdf->text( $pdf->getX()+2, $pdf->getY()+71, "IDENTIFICAÇÃO DO PACIENTE");
	  $pdf->setfont('arial','b',7);
	  $alt = $pdf->getY()+50+5;

	  $alt=$pdf->getY();
	  $lar=$pdf->getX();
	  $pdf->setY($alt+73);
	  $pdf->setX($lar+1);


	  //Dados CGS
	  $pdf->MultiCell(55,4,"NOME: ".$z01_i_numcgs."-".$z01_v_nome,0,"L");
	  $pdf->setY($alt);
	  $pdf->setX($lar);

	  $alt = $pdf->getY()+50+5;
	  $sexo= array("F"=>"Feminino","M"=>"Masculino");
	  $dia_nasc = substr($z01_d_nasc,8,2);
	  $mes_nasc = substr($z01_d_nasc,5,2);
	  $ano_nasc = substr($z01_d_nasc,0,4);
	  $idade = isset($agendamentofa)?calcage( $dia_nasc, $mes_nasc, $ano_nasc, date("d"), date("m"), date("Y") ):"";

	  $pdf->text( $pdf->getX()+2, $alt+29, "ENDEREÇO: ".substr($z01_v_ender.", ".$z01_i_numero.", ".$z01_v_compl,0,21));
	  $pdf->text( $pdf->getX()+2, $alt+32, substr($z01_v_ender.", ".$z01_i_numero.", ".$z01_v_compl,21,40));
	  $pdf->text( $pdf->getX()+2, $alt+35, "BAIRRO: ".$z01_v_bairro);
	  $pdf->text( $pdf->getX()+2, $alt+41, "MUNICÍPIO:".substr($z01_v_munic,0,40));
	  $pdf->text( $pdf->getX()+2, $alt+44, substr($z01_v_munic,40,40));
	  $pdf->text( $pdf->getX()+2, $alt+47, "UF:".$z01_v_uf." IDADE:".$idade);
	  $pdf->text( $pdf->getX()+2, $alt+53, "SEXO: ".@$sexo[$z01_v_sexo]);
	  $pdf->text( $pdf->getX()+2, $alt+59, "DATA NASC:".$dia_nasc."/".$mes_nasc."/".$ano_nasc );
	  $pdf->text( $pdf->getX()+2 ,$alt+65, "FAMÍLIA PSF:".$sd33_v_descricao);
	  $pdf->text( $pdf->getX()+2 ,$alt+71, "MICROÁREA:".$sd34_v_descricao);

	  //Linha 3 - 2ª Retangulo Diagnóstico
	  $pdf->rect( $pdf->getX()+58, $pdf->getY()+119, 129, 10, "D");
	  $pdf->setfont('arial','b',6);
	  $pdf->text( $pdf->getX()+59, $pdf->getY()+122, "DIAGNÓSTICO:");
	  $pdf->text( $pdf->getX()+59, $pdf->getY()+128, "CID: ");
	  $pdf->setfont('arial','b',7);
	  $alt=$pdf->getY();
	  $lar=$pdf->getX();
	  $sd24_t_diagnostico1=substr($sd24_t_diagnostico,0,46);
	  $sd24_t_diagnostico2=substr($sd24_t_diagnostico,46,46);
	  $sd24_t_diagnostico3=substr($sd24_t_diagnostico,92,46);
	  $pdf->setY($alt+119);
	  $pdf->setX($lar+76);
	  $pdf->text($lar+78,$alt+122,$sd24_t_diagnostico1);
	  $pdf->text( $lar+78,$alt+124,$sd24_t_diagnostico2);
	  $pdf->text( $lar+78,$alt+126,$sd24_t_diagnostico3);
	  $pdf->setY($alt);
	  $pdf->setX($lar);
	  $pdf->text( $pdf->getX()+65, $pdf->getY()+128, $sd70_c_cid."   ".$sd70_c_nome);
	  //Linha 3 - 2ª Retangulo polegar
	  $pdf->rect( $pdf->getX()+65+124, $pdf->getY()+119, 21, 22, "D");
	  $pdf->setfont('arial','b',6);
	  $pdf->text( $pdf->getX()+66+124, $pdf->getY()+122, "POLEGAR DIREITO:");
	  //Linha 4 - 1ª Retangulo data do atendimento
	  $pdf->rect( $pdf->getX(), $pdf->getY()+131, 27, 10, "D");
	  //$pdf->text( $pdf->getX(), $pdf->getY()+134, "TIPO DE FICHA");
	  $pdf->setfont('arial','b',7);
	  $sd24_d_cadastro2 = substr($obj_agendamento->sd23_d_consulta,8,2)."/".substr($obj_agendamento->sd23_d_consulta,5,2)."/".substr($obj_agendamento->sd23_d_consulta,0,4);
	  $pdf->text( $pdf->getX()+5, $pdf->getY()+137, trim($obj_agendamento->sd101_c_descr));
	  //Linha 4 - 2ª Retangulo HORA
	  $pdf->rect( $pdf->getX()+29, $pdf->getY()+131, 27, 10, "D");
	  $pdf->setfont('arial','b',6);
	  $pdf->text( $pdf->getX()+35, $pdf->getY()+134, "ATENDIMENTO");
	  $pdf->setfont('arial','b',7);
	  $pdf->text( $pdf->getX()+29+3, $pdf->getY()+138,$sd24_d_cadastro2." - ".$obj_agendamento->sd23_c_hora);
	  //Linha 4 - 3ª Retangulo assinatura
	  $pdf->rect( $pdf->getX()+58, $pdf->getY()+131, 129, 10, "D");
	  $pdf->setfont('arial','b',6);
	  $pdf->text( $pdf->getX()+59, $pdf->getY()+134, "ASSINATURA DO PACIENTE OU RESPONSÁVEL:");
	  $setY = 150;
	}
}

db_fim_transacao();
$pdf->Output();
?>