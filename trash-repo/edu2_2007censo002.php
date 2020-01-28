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

include("libs/db_stdlibwebseller.php");
include("fpdf151/pdfwebseller.php");
include("classes/db_matricula_classe.php");
include("classes/db_alunonecessidade_classe.php");
$clmatricula = new cl_matricula;
$clalunonecessidade = new cl_alunonecessidade;
$campos = "*";
$result = $clmatricula->sql_record($clmatricula->sql_query("",$campos,""," calendario.ed52_i_ano = $censo_ano AND turma.ed57_i_escola = $censo_escola AND ensino.ed10_i_codigo = $censo_ensino"));
if($clmatricula->numrows==0){?>
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
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetFillColor(190);
$head1 = "CENSO ESCOLAR $censo_ano";
$head2 = "Formul�rio de ALUNOS";
$pdf->addpage('P');
$pdf->ln(5);
for($x=0;$x<1;$x++){
 db_fieldsmemory($result,$x);
 //////////////////////////////////////////////////////P�gina 1
 $pdf->setfont('arial','b',9);
 $pdf->cell(190,6,"C�digo INEP da Escola",0,1,"L",0);
 ////
 $pdf->setfont('arial','',9);
 $pdf->cell(190,7,"   ".$ed18_c_codigoinep,0,1,"L",0);
 ////
 $pdf->setfont('arial','b',13);
 $pdf->cell(190,7,"IDENTIFICA��O",0,1,"L",0);
 ///
 $pdf->setfont('arial','b',9);
 $pdf->cell(190,6,"1 - Identifica��o �nica ( C�digo gerado pelo INEP)",0,1,"L",1);
 $pdf->setfont('arial','',9);
 $pdf->cell(190,7,"   ".$ed47_c_codigoinep,0,1,"L",0);
 ///
 $pdf->setfont('arial','b',9);
 $pdf->cell(190,6,"2 - Nome Completo",0,1,"L",1);
 $pdf->setfont('arial','',9);
 $pdf->cell(190,7,"   ".$ed47_v_nome,0,1,"L",0);
 ///
 $pdf->setfont('arial','b',9);
 $pdf->cell(190,6,"3 - N�mero de Identifica��o Social (NIS)",0,1,"L",1);
 $pdf->setfont('arial','',9);
 $pdf->cell(190,7,"   ".$ed47_c_nis,0,1,"L",0);
 ///
 $pdf->setfont('arial','b',9);
 $pdf->cell(93,6,"4 - Data de Nascimento",0,0,"L",1);
 $pdf->cell(4,6,"",0,0,"L",0);
 $pdf->cell(93,6,"5 - Sexo",0,1,"L",1);
 $pdf->setfont('arial','',9);
 $pdf->cell(93,7,"   ".db_formatar($ed47_d_nasc,'d'),0,0,"L",0);
 $pdf->cell(4,7,"",0,0,"L",0);
 $pdf->cell(93,7,"   ".($ed47_v_sexo=="M"?"MASCULINO":"FEMININO"),0,1,"L",0);
 ///
 $pdf->setfont('arial','b',9);
 $pdf->cell(190,6,"6 - Cor / Ra�a",0,1,"L",1);
 $pdf->setfont('arial','',9);
 $pdf->cell(190,7,"   ".($ed47_c_raca==""?"N�O DECLARADA":$ed47_c_raca),0,1,"L",0);
 ///
 $pdf->setfont('arial','b',9);
 $pdf->cell(190,6,"7 - Filia��o",0,1,"L",1);
 $pdf->cell(190,7,"   Nome da M�e",0,1,"L",0);
 $pdf->setfont('arial','',9);
 $pdf->cell(190,7,"   ".$ed47_v_mae,0,1,"L",0);
 $pdf->setfont('arial','b',9);
 $pdf->cell(190,7,"   Nome do Pai",0,1,"L",0);
 $pdf->setfont('arial','',9);
 $pdf->cell(190,7,"   ".$ed47_v_pai,0,1,"L",0);
 ///
 $pdf->setfont('arial','b',9);
 $pdf->cell(190,6,"8 - Nacionalidade do Aluno",0,1,"L",1);
 $pdf->setfont('arial','',9);
 $pdf->cell(190,7,"   ".($ed47_i_nacion==0||$ed47_i_nacion==1?"BRASILEIRO":($ed47_i_nacion==2?"ESTRANGEIRO":"BRASILEIRO - NASCIDO NO EXTERIOR OU NATURALIZADO")),0,1,"L",0);
 ///
 $pdf->setfont('arial','b',9);
 $pdf->cell(93,6,"9 - Pa�s de Origem",0,0,"L",1);
 $pdf->cell(4,6,"",0,0,"L",0);
 $pdf->cell(93,6,"10 - UF de Nascimento",0,1,"L",1);
 $pdf->setfont('arial','',9);
 $pdf->cell(93,7,"   ".$ed47_i_pais,0,0,"L",0);
 $pdf->cell(4,7,"",0,0,"L",0);
 $pdf->cell(93,7,"   ".$ed47_c_naturalidadeuf,0,1,"L",0);
 ///
 $pdf->setfont('arial','b',9);
 $pdf->cell(190,6,"11 - Munic�pio de Nascimento",0,1,"L",1);
 $pdf->setfont('arial','',9);
 $pdf->cell(190,7,"   ".$ed47_c_naturalidade,0,1,"L",0);
 ///
 $pdf->setfont('arial','b',13);
 $pdf->cell(190,7,"DOCUMENTO - O aluno dever� ser cadastrado mesmo sem informa��o de documento",0,1,"L",0);
 ///
 $pdf->setfont('arial','b',9);
 $pdf->cell(93,6,"12 - N�mero de Identidade",0,0,"L",1);
 $pdf->cell(4,6,"",0,0,"L",0);
 $pdf->cell(93,6,"12a - Complemento da Identidade",0,1,"L",1);
 $pdf->setfont('arial','',9);
 $pdf->cell(93,7,"   ".$ed47_v_ident,0,0,"L",0);
 $pdf->cell(4,7,"",0,0,"L",0);
 $pdf->cell(93,7,"   ".$ed47_v_identcompl,0,1,"L",0);
 ///
 $pdf->setfont('arial','b',9);
 $pdf->cell(93,6,"12b - �rg�o Emissor da Identidade",0,0,"L",1);
 $pdf->cell(4,6,"",0,0,"L",0);
 $pdf->cell(93,6,"12c - UF da Identidade",0,1,"L",1);
 $pdf->setfont('arial','',9);
 $pdf->cell(93,7,"   ".$ed47_v_identorgao,0,0,"L",0);
 $pdf->cell(4,7,"",0,0,"L",0);
 $pdf->cell(93,7,"   ".$ed47_v_identuf,0,1,"L",0);
 ///
 $pdf->setfont('arial','b',9);
 $pdf->cell(93,6,"12d - Data de Expedi��o da Identidade",0,0,"L",1);
 $pdf->cell(4,6,"",0,0,"L",0);
 $pdf->cell(93,6,"13 - Certid�o Civil",0,1,"L",1);
 $pdf->setfont('arial','',9);
 $pdf->cell(93,7,"   ".db_formatar($ed47_d_identdtexp,'d'),0,0,"L",0);
 $pdf->cell(4,7,"",0,0,"L",0);
 $pdf->cell(93,7,"   ".$ed47_c_certidaotipo=="N"?"NASCIMENTO":($ed47_c_certidaotipo=="C"?"CASAMENTO":""),0,1,"L",0);
 ///
 $pdf->setfont('arial','b',9);
 $pdf->cell(93,6,"13a - N�mero do Termo",0,0,"L",1);
 $pdf->cell(4,6,"",0,0,"L",0);
 $pdf->cell(93,6,"13b - Folha",0,1,"L",1);
 $pdf->setfont('arial','',9);
 $pdf->cell(93,7,"   ".$ed47_c_certidaonum,0,0,"L",0);
 $pdf->cell(4,7,"",0,0,"L",0);
 $pdf->cell(93,7,"   ".$ed47_c_certidaofolha,0,1,"L",0);
 ///
 $pdf->setfont('arial','b',9);
 $pdf->cell(93,6,"13c - Livro",0,0,"L",1);
 $pdf->cell(4,6,"",0,0,"L",0);
 $pdf->cell(93,6,"13d - Data da Emiss�o da Certid�o",0,1,"L",1);
 $pdf->setfont('arial','',9);
 $pdf->cell(93,7,"   ".$ed47_c_certidaolivro,0,0,"L",0);
 $pdf->cell(4,7,"",0,0,"L",0);
 $pdf->cell(93,7,"   ".db_formatar($ed47_c_certidaodata,'d'),0,1,"L",0);
 $pdf->addpage('P');
 $pdf->ln(5);
 ///
 $pdf->setfont('arial','b',9);
 $pdf->cell(190,6,"13e - Nome do Cart�rio",0,1,"L",1);
 $pdf->setfont('arial','',9);
 $pdf->cell(190,7,"   ".$ed47_c_certidaocart,0,1,"L",0);
 ///
 $pdf->setfont('arial','b',9);
 $pdf->cell(40,6,"13f - UF do Cart�rio",0,0,"L",1);
 $pdf->cell(4,6,"",0,0,"L",0);
 $pdf->cell(60,6,"14 - N�mero do CPF",0,0,"L",1);
 $pdf->cell(4,6,"",0,0,"L",0);
 $pdf->cell(82,6,"15 - Documento Estrangeiro / Passaporte",0,1,"L",1);
 $pdf->setfont('arial','',9);
 $pdf->cell(40,7,"   ".$ed47_c_certidaouf,0,0,"L",0);
 $pdf->cell(4,7,"",0,0,"L",0);
 $pdf->cell(60,7,"   ".$ed47_v_cpf,0,0,"L",0);
 $pdf->cell(4,7,"",0,0,"L",0);
 $pdf->cell(82,7,"   ".$ed47_c_passaporte,0,1,"L",0);
 ///
 $pdf->setfont('arial','b',13);
 $pdf->cell(190,7,"ENDERE�O RESIDENCIAL",0,1,"L",0);
 ///
 $pdf->setfont('arial','b',9);
 $pdf->cell(73,6,"16 - CEP",0,0,"L",1);
 $pdf->cell(4,6,"",0,0,"L",0);
 $pdf->cell(113,6,"17 - Endere�o",0,1,"L",1);
 $pdf->setfont('arial','',9);
 $pdf->cell(73,7,"   ".$ed47_v_cep,0,0,"L",0);
 $pdf->cell(4,7,"",0,0,"L",0);
 $pdf->cell(113,7,"   ".$ed47_v_ender,0,1,"L",0);
 ///
 $pdf->setfont('arial','b',9);
 $pdf->cell(93,6,"18 - N�mero",0,0,"L",1);
 $pdf->cell(4,6,"",0,0,"L",0);
 $pdf->cell(93,6,"19 - Complemento",0,1,"L",1);
 $pdf->setfont('arial','',9);
 $pdf->cell(93,7,"   ".$ed47_i_numero,0,0,"L",0);
 $pdf->cell(4,7,"",0,0,"L",0);
 $pdf->cell(93,7,"   ".$ed47_v_compl,0,1,"L",0);
 ///
 $pdf->setfont('arial','b',9);
 $pdf->cell(143,6,"20 - Bairro",0,0,"L",1);
 $pdf->cell(4,6,"",0,0,"L",0);
 $pdf->cell(43,6,"21 - UF",0,1,"L",1);
 $pdf->setfont('arial','',9);
 $pdf->cell(143,7,"   ".$ed47_v_bairro,0,0,"L",0);
 $pdf->cell(4,7,"",0,0,"L",0);
 $pdf->cell(43,7,"   ".$ed47_v_uf,0,1,"L",0);
 ///
 $pdf->setfont('arial','b',9);
 $pdf->cell(190,6,"22 - Munic�pio",0,1,"L",1);
 $pdf->setfont('arial','',9);
 $pdf->cell(190,7,"   ".$ed47_v_munic,0,1,"L",0);
 ///
 $pdf->setfont('arial','b',13);
 $pdf->cell(190,7,"DADOS VARI�VEIS - In�cio do Ano Corrente",0,1,"L",0);
 ///
 $pdf->setfont('arial','b',9);
 $pdf->cell(93,6,"23 - Nome da Turma",0,0,"L",1);
 $pdf->cell(4,6,"",0,0,"L",0);
 $pdf->cell(93,6,"24 - Se Turma Unicada",0,1,"L",1);
 $pdf->setfont('arial','',9);
 $pdf->cell(93,7,"   ".$ed57_c_descr,0,0,"L",0);
 $pdf->cell(4,7,"",0,0,"L",0);
 $pdf->cell(93,7,"   ",0,1,"L",0);////////<----------------------------------------------------------------------
 ///
 $pdf->setfont('arial','b',9);
 $pdf->cell(190,6,"25 - Se Turma Multietapa, Multi ou Corre��o de Fluxo",0,1,"L",1);
 $pdf->setfont('arial','',9);
 $pdf->cell(190,7,"   ",0,1,"L",0);////////<----------------------------------------------------------------------
 ///
 $pdf->setfont('arial','b',9);
 $pdf->cell(190,6,"26 - Atendimento Escolar Diferenciado",0,1,"L",1);
 $pdf->setfont('arial','',9);
 $pdf->cell(190,7,"   ".(trim($ed47_c_atendesp)==""?"N�O NECESSITA":$ed47_c_atendesp),0,1,"L",0);
 ///
 $pdf->setfont('arial','b',9);
 $pdf->cell(93,6,"27 - Transporte Escolar P�blico",0,0,"L",1);
 $pdf->cell(4,6,"",0,0,"L",0);
 $pdf->cell(93,6,"27a - Poder P�blico Respons�vel Pelo Transporte Escolar",0,1,"L",1);
 $pdf->setfont('arial','',9);
 $pdf->cell(93,7,"   ".(trim($ed47_c_transporte)!=""?"UTILIZA":"N�O UTILIZA"),0,0,"L",0);
 $pdf->cell(4,7,"",0,0,"L",0);
 $pdf->cell(93,7,"   ".$ed47_c_transporte,0,1,"L",0);
 ///
 $pdf->setfont('arial','b',9);
 $pdf->cell(93,6,"28 - Localiza��o / Zona de Resid�ncia",0,0,"L",1);
 $pdf->cell(4,6,"",0,0,"L",0);
 $pdf->cell(93,6,"29 - Necessidades Educacionais Especiais",0,1,"L",1);
 $pdf->setfont('arial','',9);
 $pdf->cell(93,7,"   ".(trim($ed47_c_zona)==""||trim($ed47_c_zona)=="URBANA"?"URBANA":"RURAL"),0,0,"L",0);
 $pdf->cell(4,7,"",0,0,"L",0);
 $result1 = $clalunonecessidade->sql_record($clalunonecessidade->sql_query("","ed48_c_descr,ed214_i_apoio,",""," ed214_i_aluno = $ed60_i_aluno AND ed214_c_principal = 'S'"));
 if($clalunonecessidade->numrows==0){
  $necessidade = "N�O";
  $ed48_c_descr = "";
  $ed214_i_apoio = "";
 }else{
  $necessidade = "SIM";
  db_fieldsmemory($result1,0);
 }
 $pdf->cell(93,7,"   ".$necessidade,0,1,"L",0);
 ///
 $pdf->setfont('arial','b',9);
 $pdf->cell(190,6,"29a - Tipo de Necessidade Educacional Especial",0,1,"L",1);
 $pdf->setfont('arial','',9);
 $pdf->cell(190,7,"   ".$ed48_c_descr,0,1,"L",0);
 ///
 $pdf->setfont('arial','b',9);
 $pdf->cell(190,6,"29b - Atendimento Educacional Especializado (Apoio Pedag�gico)",0,1,"L",1);
 $pdf->setfont('arial','',9);
 $pdf->cell(190,7,"   ".($ed214_i_apoio==""||$ed214_i_apoio==1?"N�O RECEBE":"RECEBE"),0,1,"L",0);
 ///
 $pdf->setfont('arial','b',13);
 $pdf->cell(190,7,"INFORMA��ES DO ANO ANTERIOR",0,1,"L",0);
 ///
 $pdf->setfont('arial','b',9);
 $pdf->cell(93,6,"30 - Rendimento",0,0,"L",1);
 $pdf->cell(4,6,"",0,0,"L",0);
 $pdf->cell(93,6,"31 - Se Aluno de S�rie Final (Ensino Fundamental ou M�dio)",0,1,"L",1);
 $pdf->setfont('arial','',9);
 $pdf->cell(93,7,"   ".($ed60_c_rfanterior==""?"":($ed60_c_rfanterior=="A"?"APROVADO":"REPROVADO")),0,0,"L",0);
 $pdf->cell(4,7,"",0,0,"L",0);
 $pdf->cell(93,7,"   ",0,1,"L",0);////////<----------------------------------------------------------------------
 ///
 $pdf->setfont('arial','b',9);
 $pdf->cell(190,6,"32 - Movimento - Ap�s a Data de Refer�ncia do Censo",0,1,"L",1);
 $pdf->setfont('arial','',9);
 $pdf->cell(190,7,"   ".Situacao($ed60_c_situacao,$ed60_i_codigo),0,1,"L",0);
}
$pdf->Output();
?>