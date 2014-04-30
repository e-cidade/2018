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

require("libs/db_stdlibwebseller.php");
include("fpdf151/pdfwebseller.php");
include("classes/db_matricula_classe.php");
include("classes/db_matriculamov_classe.php");
include("classes/db_aluno_classe.php");
include("classes/db_turma_classe.php");
include("classes/db_alunoprimat_classe.php");
include("classes/db_alunonecessidade_classe.php");
include("classes/db_escola_classe.php");
include("classes/db_regencia_classe.php");
include("classes/db_regenciaperiodo_classe.php");
include("classes/db_diarioavaliacao_classe.php");
include("classes/db_procavaliacao_classe.php");
include("classes/db_procresultado_classe.php");
include("classes/db_histmpsdiscfora_classe.php");
include("classes/db_histmpsdisc_classe.php");
require_once("libs/db_utils.php");
require_once("model/educacao/DBEducacaoTermo.model.php");
require_once("dbforms/db_funcoes.php");
$resultedu           = eduparametros(db_getsession("DB_coddepto"));
$permitenotaembranco = VerParametroNota(db_getsession("DB_coddepto"));
$clmatricula         = new cl_matricula;
$clmatriculamov      = new cl_matriculamov;
$clescola            = new cl_escola;
$claluno             = new cl_aluno;
$clturma             = new cl_turma;
$clalunoprimat       = new cl_alunoprimat;
$clalunonecessidade  = new cl_alunonecessidade;
$clregencia          = new cl_regencia;
$clregenciaperiodo   = new cl_regenciaperiodo;
$cldiarioavaliacao   = new cl_diarioavaliacao;
$clprocavaliacao     = new cl_procavaliacao;
$clprocresultado     = new cl_procresultado;
$clhistmpsdisc       = new cl_histmpsdisc;
$clhistmpsdiscfora   = new cl_histmpsdiscfora;
$claluno->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed76_i_escola");
$clrotulo->label("ed76_d_data");
$escola   = db_getsession("DB_coddepto");
$sCampos  = " aluno.*, ";
$sCampos .= " censoufident.ed260_c_nome as ufident, ";
$sCampos .= " censoufnat.ed260_c_nome as ufnat, ";
$sCampos .= " censoufcert.ed260_c_nome as ufcert, ";
$sCampos .= " censoufend.ed260_c_nome as ufend, ";
$sCampos .= " censomunicnat.ed261_c_nome as municnat, ";
$sCampos .= " censomuniccert.ed261_c_nome as municcert, ";
$sCampos .= " censomunicend.ed261_c_nome as municend, ";
$sCampos .= " censoorgemissrg.ed132_c_descr as orgemissrg, ";
$sCampos .= " pais.ed228_c_descr ";
$result   = $claluno->sql_record($claluno->sql_query("",$sCampos,"ed47_v_nome"," ed47_i_codigo = $chavepesquisa "));
if ($claluno->numrows == 0) {?>

  <table width='100%'>
   <tr>
    <td align='center'>
     <font color='#FF0000' face='arial'>
      <b>Nenhuma registro encontrado.<br>
      <input type='button' value='Fechar' onclick='window.close()'></b>
     </font>
    </td>
   </tr>
  </table>
  <?
  exit;
  
}

db_fieldsmemory($result,0);
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(223);
$head1 = "FICHA DO ALUNO";
$head2 = "$ed47_i_codigo - $ed47_v_nome";
$pdf->addpage('P');

/////////////////////////////////////////////DADOS PESSOAIS

$pdf->setfont('arial','b',7);
$pdf->cell(160,4,"   DADOS PESSOAIS","LBT",0,"L",1);
$pdf->cell(34,4,"   FOTO",1,1,"L",1);
$pdf->cell(160,2,"","LR",0,"C",0);
$pdf->cell(34,2,"","LR",1,"C",0);

$pdf->cell(3,4,"","L",0,"C",0);
$pdf->setfont('arial','',7);
$pdf->cell(35,4,strip_tags($Led47_v_nome),0,0,"L",0);
$pdf->setfont('arial','b',7);
$pdf->cell(120,4,$ed47_v_nome,0,0,"L",0);
$pdf->cell(2,4,"","R",1,"C",0);

$pdf->cell(3,4,"","L",0,"C",0);
$pdf->setfont('arial','',7);
$pdf->cell(35,4,strip_tags($Led47_i_codigo),0,0,"L",0);
$pdf->setfont('arial','b',7);
$pdf->cell(20,4,$ed47_i_codigo,0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_c_codigoinep),0,0,"R",0);
$pdf->setfont('arial','b',7);
$pdf->cell(20,4,$ed47_c_codigoinep,0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(25,4,strip_tags($Led47_c_nis),0,0,"R",0);
$pdf->setfont('arial','b',7);
$pdf->cell(25,4,$ed47_c_nis,0,0,"L",0);
$pdf->cell(2,4,"","R",1,"C",0);

if ($ed47_o_oid != 0) {
	
  $arquivo = "tmp/".$ed47_c_foto;
  db_query("begin");
  pg_lo_export($ed47_o_oid, $arquivo, $conn);
  db_query("end");
  $pdf->Image($arquivo,177,43,20);
  
}

$pdf->cell(3,4,"","L",0,"C",0);
$pdf->setfont('arial','',7);
$pdf->cell(35,4,strip_tags($Led47_d_nasc),0,0,"L",0);
$pdf->setfont('arial','b',7);
$pdf->cell(20,4,db_formatar($ed47_d_nasc,'d'),0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_v_sexo),0,0,"R",0);
$pdf->setfont('arial','b',7);
$pdf->cell(20,4,$ed47_v_sexo=="M"?"MASCULINO":"FEMININO",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(25,4,strip_tags($Led47_i_estciv),0,0,"R",0);

if ($ed47_i_estciv == 1) {
  $ed47_i_estciv = "SOLTEIRO";
} else if ($ed47_i_estciv == 2) {
  $ed47_i_estciv = "CASADO";
} else if ($ed47_i_estciv == 3) {
  $ed47_i_estciv = "VIÚVO";
} else {
  $ed47_i_estciv = "DIVORCIADO";
}

$pdf->setfont('arial','b',7);
$pdf->cell(25,4,$ed47_i_estciv,0,0,"L",0);
$pdf->cell(2,4,"","R",1,"C",0);

$pdf->cell(3,4,"","L",0,"C",0);
$pdf->setfont('arial','',7);
$pdf->cell(35,4,strip_tags($Led47_i_filiacao),0,0,"L",0);
$pdf->setfont('arial','b',7);
$pdf->cell(65,4,$ed47_i_filiacao=="0"?"NÃO DECLARADO / IGNORADO":"PAI E/OU MÃE",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_c_raca),0,0,"R",0);
$pdf->setfont('arial','b',7);
$pdf->cell(25,4,$ed47_c_raca,0,0,"L",0);
$pdf->cell(2,4,"","R",1,"C",0);

$pdf->cell(3,4,"","L",0,"C",0);
$pdf->setfont('arial','',7);
$pdf->cell(35,4,strip_tags($Led47_v_pai),0,0,"L",0);
$pdf->setfont('arial','b',7);
$pdf->cell(120,4,$ed47_v_pai,0,0,"L",0);
$pdf->cell(2,4,"","R",1,"C",0);

$pdf->cell(3,4,"","L",0,"C",0);
$pdf->setfont('arial','',7);
$pdf->cell(35,4,strip_tags($Led47_v_mae),0,0,"L",0);
$pdf->setfont('arial','b',7);
$pdf->cell(120,4,$ed47_v_mae,0,0,"L",0);
$pdf->cell(2,4,"","R",1,"C",0);

$pdf->cell(3,4,"","L",0,"C",0);
$pdf->setfont('arial','',7);
$pdf->cell(35,4,strip_tags($Led47_c_nomeresp),0,0,"L",0);
$pdf->setfont('arial','b',7);
$pdf->cell(120,4,$ed47_c_nomeresp,0,0,"L",0);
$pdf->cell(2,4,"","R",1,"C",0);

$pdf->cell(3,4,"","L",0,"C",0);
$pdf->setfont('arial','',7);
$pdf->cell(35,4,strip_tags($Led47_c_emailresp),0,0,"L",0);
$pdf->setfont('arial','b',7);
$pdf->cell(120,4,$ed47_c_emailresp,0,0,"L",0);
$pdf->cell(2,4,"","R",1,"C",0);
$pdf->line(204,35,204,80);

//////////////////////////////////////////////////////////

$pdf->cell(160,2,"","LR",0,"C",0);
$pdf->cell(34,2,"","LR",1,"C",0);
$pdf->cell(194,4,"   ENDEREÇO / CONTATOS",1,1,"L",1);
$pdf->cell(194,2,"","LR",1,"C",0);

$pdf->cell(3,4,"","L",0,"C",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_v_ender),0,0,"L",0);
$pdf->setfont('arial','b',7);
$pdf->cell(40,4,substr($ed47_v_ender,0,37),0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_c_numero),0,0,"R",0);
$pdf->setfont('arial','b',7);
$pdf->cell(30,4,$ed47_c_numero,0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(25,4,strip_tags($Led47_v_compl),0,0,"R",0);
$pdf->setfont('arial','b',7);
$pdf->cell(35,4,$ed47_v_compl,0,0,"L",0);
$pdf->cell(1,4,"","R",1,"C",0);

$pdf->cell(3,4,"","L",0,"C",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_i_censoufend),0,0,"L",0);
$pdf->setfont('arial','b',7);
$pdf->cell(40,4,$ufend,0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_i_censomunicend),0,0,"R",0);
$pdf->setfont('arial','b',7);
$pdf->cell(30,4,$municend,0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(25,4,strip_tags($Led47_v_bairro),0,0,"R",0);
$pdf->setfont('arial','b',7);
$pdf->cell(35,4,substr($ed47_v_bairro,0,23),0,0,"L",0);
$pdf->cell(1,4,"","R",1,"C",0);

$pdf->cell(3,4,"","L",0,"C",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_c_zona),0,0,"L",0);
$pdf->setfont('arial','b',7);
$pdf->cell(40,4,$ed47_c_zona,0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_v_cep),0,0,"R",0);
$pdf->setfont('arial','b',7);
$pdf->cell(90,4,$ed47_v_cep,0,0,"L",0);
$pdf->cell(1,4,"","R",1,"C",0);

$pdf->cell(3,4,"","L",0,"C",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_v_telef),0,0,"L",0);
$pdf->setfont('arial','b',7);
$pdf->cell(40,4,$ed47_v_telef,0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_v_telcel),0,0,"R",0);
$pdf->setfont('arial','b',7);
$pdf->cell(30,4,$ed47_v_telcel,0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(25,4,strip_tags($Led47_v_fax),0,0,"R",0);
$pdf->setfont('arial','b',7);
$pdf->cell(35,4,$ed47_v_fax,0,0,"L",0);
$pdf->cell(1,4,"","R",1,"C",0);

$pdf->cell(3,4,"","L",0,"C",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_v_email),0,0,"L",0);
$pdf->setfont('arial','b',7);
$pdf->cell(40,4,$ed47_v_email,0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_v_cxpostal),0,0,"R",0);
$pdf->setfont('arial','b',7);
$pdf->cell(90,4,$ed47_v_cxpostal,0,0,"L",0);
$pdf->cell(1,4,"","R",1,"C",0);

//////////////////////////////////////////////////////////

$pdf->cell(194,2,"","LR",1,"C",0);
$pdf->cell(194,4,"   OUTRAS INFORMAÇÕES",1,1,"L",1);
$pdf->cell(194,2,"","LR",1,"C",0);

$pdf->cell(3,4,"","L",0,"C",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_i_nacion),0,0,"L",0);

if ($ed47_i_nacion == 1) {
  $ed47_i_nacion = "BRASILEIRA";
} else if ($ed47_i_nacion == 2) {
  $ed47_i_nacion = "BRASILEIRA NO EXTERIOR OU NATURALIZADO";
} else if ($ed47_i_nacion == 3) {
  $ed47_i_nacion = "ESTRANGEIRA";
}

$pdf->setfont('arial','b',7);
$pdf->cell(40,4,$ed47_i_nacion,0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_i_pais),0,0,"R",0);
$pdf->setfont('arial','b',7);
$pdf->cell(90,4,$ed228_c_descr,0,0,"L",0);
$pdf->cell(1,4,"","R",1,"C",0);

$pdf->cell(3,4,"","L",0,"C",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_i_censoufnat),0,0,"L",0);
$pdf->setfont('arial','b',7);
$pdf->cell(40,4,$ufnat,0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_i_censomunicnat),0,0,"R",0);
$pdf->setfont('arial','b',7);
$pdf->cell(90,4,$municnat,0,0,"L",0);
$pdf->cell(1,4,"","R",1,"C",0);

$pdf->cell(3,4,"","L",0,"C",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_i_transpublico),0,0,"L",0);
$pdf->setfont('arial','b',7);
$pdf->cell(40,4,$ed47_i_transpublico=="0"?"NÃO UTILIZA":"UTILIZA",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_c_transporte),0,0,"R",0);

if ($ed47_c_transporte == 1) {
  $ed47_c_transporte = "ESTADUAL";
} else if ($ed47_c_transporte == 2) {
  $ed47_c_transporte = "MUNICIPAL";
} else {
  $ed47_c_transporte = "";
}

$pdf->setfont('arial','b',7);
$pdf->cell(90,4,$ed47_c_transporte,0,0,"L",0);
$pdf->cell(1,4,"","R",1,"C",0);

$pdf->cell(3,4,"","L",0,"C",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_c_bolsafamilia),0,0,"L",0);
$pdf->setfont('arial','b',7);
$pdf->cell(23,4,$ed47_c_bolsafamilia=='N'?'NÃO':'SIM',0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(47,4,strip_tags($Led47_c_atenddifer),0,0,"L",0);

if ($ed47_c_atenddifer == 1) {
  $ed47_c_atenddifer = "EM HOSPITAL";
} else if ($ed47_c_atenddifer == 2) {
  $ed47_c_atenddifer = "EM DOMICÍLIO";
} else if ($ed47_c_atenddifer == 3) {
  $ed47_c_atenddifer = "NÃO RECEBE";
}

$pdf->setfont('arial','b',7);
$pdf->cell(30,4,$ed47_c_atenddifer,0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(25,4,strip_tags($Led47_v_profis),0,0,"R",0);
$pdf->setfont('arial','b',7);
$pdf->cell(35,4,substr($ed47_v_profis,0,23),0,0,"L",0);
$pdf->cell(1,4,"","R",1,"C",0);
$campos1  = " ed76_d_data, ";
$campos1 .= " case when ed76_c_tipo = 'M' ";
$campos1 .= "  then ed18_c_nome else ed82_c_nome end as nomeescola ";
$result11 = $clalunoprimat->sql_record($clalunoprimat->sql_query("",$campos1,""," ed76_i_aluno = $chavepesquisa"));

if ($clalunoprimat->numrows > 0) {
  db_fieldsmemory($result11,0);
} else {
  $ed76_d_data = "";
  $nomeescola  = "";
}

$pdf->cell(3,4,"","L",0,"C",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led76_i_escola),0,0,"L",0);
$pdf->setfont('arial','b',7);
$pdf->cell(40,4,substr($nomeescola,0,30),0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led76_d_data),0,0,"R",0);
$pdf->setfont('arial','b',7);
$pdf->cell(90,4,db_formatar($ed76_d_data,'d'),0,0,"L",0);
$pdf->cell(1,4,"","R",1,"C",0);

//////////////////////////////////////////////////////////

$pdf->cell(194,2,"","LR",1,"C",0);
$pdf->cell(194,4,"   DOCUMENTOS",1,1,"L",1);
$pdf->cell(194,2,"","LR",1,"C",0);

$pdf->cell(3,4,"","L",0,"C",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_c_certidaotipo),0,0,"L",0);

if ($ed47_c_certidaotipo == "N") {
  $ed47_c_certidaotipo = "NASCIMENTO";
} else if ($ed47_c_certidaotipo == "C") {
  $ed47_c_certidaotipo = "CASAMENTO";
} else {
  $ed47_c_certidaotipo = "";
}

$pdf->setfont('arial','b',7);
$pdf->cell(40,4,$ed47_c_certidaotipo,0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_c_certidaonum),0,0,"R",0);
$pdf->setfont('arial','b',7);
$pdf->cell(90,4,$ed47_c_certidaonum,0,0,"L",0);
$pdf->cell(1,4,"","R",1,"C",0);

$pdf->cell(3,4,"","L",0,"C",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_c_certidaofolha),0,0,"L",0);
$pdf->setfont('arial','b',7);
$pdf->cell(40,4,$ed47_c_certidaofolha,0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_c_certidaolivro),0,0,"R",0);
$pdf->setfont('arial','b',7);
$pdf->cell(30,4,$ed47_c_certidaolivro,0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(25,4,strip_tags($Led47_c_certidaodata),0,0,"R",0);
$pdf->setfont('arial','b',7);
$pdf->cell(35,4,db_formatar($ed47_c_certidaodata,'d'),0,0,"L",0);
$pdf->cell(1,4,"","R",1,"C",0);

$pdf->cell(3,4,"","L",0,"C",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_i_censoufcert),0,0,"L",0);
$pdf->setfont('arial','b',7);
$pdf->cell(40,4,$ufcert,0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_i_censomuniccert),0,0,"R",0);
$pdf->setfont('arial','b',7);
$pdf->cell(90,4,$municcert,0,0,"L",0);
$pdf->cell(1,4,"","R",1,"C",0);

$pdf->cell(3,4,"","L",0,"C",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_c_certidaocart),0,0,"L",0);
$pdf->setfont('arial','b',7);
$pdf->cell(160,4,substr($ed47_c_certidaocart,0,90),0,0,"L",0);
$pdf->cell(1,4,"","R",1,"C",0);

$pdf->cell(3,4,"","L",0,"C",0);
$pdf->cell(188,0.5,"",1,0,"C",1);
$pdf->cell(3,4,"","R",1,"C",0);

$pdf->cell(3,4,"","L",0,"C",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_v_ident),0,0,"L",0);
$pdf->setfont('arial','b',7);
$pdf->cell(40,4,$ed47_v_ident,0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_v_identcompl),0,0,"R",0);
$pdf->setfont('arial','b',7);
$pdf->cell(30,4,$ed47_v_identcompl,0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(25,4,strip_tags($Led47_i_censoufident),0,0,"R",0);
$pdf->setfont('arial','b',7);
$pdf->cell(35,4,$ufident,0,0,"L",0);
$pdf->cell(1,4,"","R",1,"C",0);

$pdf->cell(3,4,"","L",0,"C",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_i_censoorgemissrg),0,0,"L",0);
$pdf->setfont('arial','b',7);
$pdf->cell(100,4,$orgemissrg,0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(25,4,strip_tags($Led47_d_identdtexp),0,0,"R",0);
$pdf->setfont('arial','b',7);
$pdf->cell(35,4,db_formatar($ed47_d_identdtexp,'d'),0,0,"L",0);
$pdf->cell(1,4,"","R",1,"C",0);

$pdf->cell(3,4,"","L",0,"C",0);
$pdf->cell(188,0.5,"",1,0,"C",1);
$pdf->cell(3,4,"","R",1,"C",0);

$pdf->cell(3,4,"","L",0,"C",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_v_cnh),0,0,"L",0);
$pdf->setfont('arial','b',7);
$pdf->cell(40,4,$ed47_v_cnh,0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_v_categoria),0,0,"R",0);
$pdf->setfont('arial','b',7);
$pdf->cell(90,4,$ed47_v_categoria,0,0,"L",0);
$pdf->cell(1,4,"","R",1,"C",0);

$pdf->cell(3,4,"","L",0,"C",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_d_dtemissao),0,0,"L",0);
$pdf->setfont('arial','b',7);
$pdf->cell(40,4,db_formatar($ed47_d_dtemissao,'d'),0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_d_dthabilitacao),0,0,"R",0);
$pdf->setfont('arial','b',7);
$pdf->cell(30,4,db_formatar($ed47_d_dthabilitacao,'d'),0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(25,4,strip_tags($Led47_d_dtvencimento),0,0,"R",0);
$pdf->setfont('arial','b',7);
$pdf->cell(35,4,db_formatar($ed47_d_dtvencimento,'d'),0,0,"L",0);
$pdf->cell(1,4,"","R",1,"C",0);

$pdf->cell(3,4,"","L",0,"C",0);
$pdf->cell(188,0.5,"",1,0,"C",1);
$pdf->cell(3,4,"","R",1,"C",0);

$pdf->cell(3,4,"","L",0,"C",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_v_cpf),0,0,"L",0);
$pdf->setfont('arial','b',7);
$pdf->cell(40,4,$ed47_v_cpf,0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(30,4,strip_tags($Led47_c_passaporte),0,0,"R",0);
$pdf->setfont('arial','b',7);
$pdf->cell(90,4,$ed47_c_passaporte,0,0,"L",0);
$pdf->cell(1,4,"","R",1,"C",0);

//////////////////////////////////////////////////////////

$pdf->cell(194,2,"","LR",1,"C",0);
$pdf->cell(194,4,"   NECESSIDADES ESPECIAIS",1,1,"L",1);
$pdf->cell(194,2,"","LR",1,"C",0);

$result22 = $clalunonecessidade->sql_record($clalunonecessidade->sql_query("",
                                                                           "*",
                                                                           "ed48_c_descr LIMIT 2",
                                                                           " ed214_i_aluno = $chavepesquisa"
                                                                          )
                                           );
$cont     = 0;
if ($clalunonecessidade->numrows > 0) {
	
  $pdf->cell(3,4,"","L",0,"C",0);
  $pdf->setfont('arial','b',7);
  $pdf->cell(70,4,"Descrição:",0,0,"L",0);
  $pdf->cell(120,4,"Necessidade Maior:",0,0,"L",0);
  $pdf->cell(1,4,"","R",1,"C",0);
  
  for ($y = 0; $y < $clalunonecessidade->numrows; $y++) {
  	
    db_fieldsmemory($result22,$y);
    $pdf->cell(3,4,"","L",0,"C",0);
    $pdf->setfont('arial','',7);
    $pdf->cell(70,4,$ed48_c_descr,0,0,"L",0);
    $pdf->cell(120,4,$ed214_c_principal,0,0,"L",0);
    $pdf->cell(1,4,"","R",1,"C",0);
    $cont++;
    
  }
} else {
	
  $pdf->cell(3,4,"","L",0,"C",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(190,4,"Nenhum registro.",0,0,"L",0);
  $pdf->cell(1,4,"","R",1,"C",0);
  $cont++;
  
}

for ($y = $cont; $y < 2; $y++) {
	
  $pdf->cell(3,4,"","L",0,"C",0);
  $pdf->cell(190,4,"",0,0,"C",0);
  $pdf->cell(1,4,"","R",1,"C",0);
  
}

//////////////////////////////////////////////////////////
$pdf->setfont('arial','b',7);
$pdf->cell(194,2,"","LR",1,"C",0);
$pdf->cell(97,4,"   OBSERVAÇÔES",1,0,"L",1);
$pdf->cell(97,4,"   CONTATO",1,1,"L",1);
$pdf->cell(97,2,"","LR",0,"C",0);
$pdf->cell(97,2,"","LR",1,"C",0);

$alt_obs = $pdf->getY();
$pdf->setfont('arial','',7);
$pdf->cell(3,50,"","L",0,"C",0);
$pdf->multicell(91,4,trim($ed47_t_obs)==""?"Nenhum registro.":substr(trim($ed47_t_obs),0,500),0,"J",0,0);
$pdf->setXY(104,$alt_obs);
$pdf->cell(3,50,"","R",0,"C",0);

$pdf->setXY(107,$alt_obs);
$pdf->cell(3,50,"","L",0,"C",0);
$pdf->multicell(91,4,trim($ed47_v_contato)==""?"Nenhum registro.":substr(trim($ed47_v_contato),0,500),0,"J",0,0);
$pdf->setXY(201,$alt_obs);
$pdf->cell(3,50,"","R",1,"C",0);

$pdf->cell(97,2,"","LBR",0,"C",0);
$pdf->cell(97,2,"","LBR",1,"C",0);

//////////////////////////////////////////////////////////

$pdf->addpage('P');
$pdf->ln(5);
$altini = $pdf->getY();
$pdf->cell(190,4,"MATRÍCULAS",1,1,"C",1);
$pdf->cell(190,4,"",0,1,"C",0);
$sCampos  = " ed60_d_datasaida as datasaida, ";
$sCampos .= " case ";
$sCampos .= "  when ed60_c_situacao = 'TRANSFERIDO REDE' then ";
$sCampos .= "   (select escoladestino.ed18_c_nome from transfescolarede ";
$sCampos .= "     inner join atestvaga  on  atestvaga.ed102_i_codigo = transfescolarede.ed103_i_atestvaga ";
$sCampos .= "     inner join escola  as escoladestino on  escoladestino.ed18_i_codigo = atestvaga.ed102_i_escola ";
$sCampos .= "    where ed103_i_matricula = ed60_i_codigo order by ed103_d_data desc limit 1) ";
$sCampos .= "  when ed60_c_situacao = 'TRANSFERIDO FORA' then ";
$sCampos .= "   (select escolaproc1.ed82_c_nome from transfescolafora ";
$sCampos .= "     inner join escolaproc as escolaproc1 on  escolaproc1.ed82_i_codigo = transfescolafora.ed104_i_escoladestino ";
$sCampos .= "    where ed104_i_matricula = ed60_i_codigo order by ed104_d_data desc limit 1) ";
$sCampos .= "  else null ";
$sCampos .= "  end as destinosaida, ";
$sCampos .= "  matricula.*, ";
$sCampos .= "  turma.ed57_c_descr, ";
$sCampos .= "  turmaserieregimemat.ed220_i_procedimento, ";
$sCampos .= "  turma.ed57_c_medfreq, ";
$sCampos .= "  calendario.ed52_c_descr, ";
$sCampos .= "  calendario.ed52_i_ano, ";
$sCampos .= " case when turma.ed57_i_tipoturma = 2 then ";
$sCampos .= "  fc_nomeetapaturma(ed60_i_turma) else ";
$sCampos .= "  serie.ed11_c_descr ";
$sCampos .= "  end as ed11_c_descr, ";
$sCampos .= "  escola.ed18_c_nome, ";
$sCampos .= "  turno.ed15_c_nome, ";
$sCampos .= "  aluno.ed47_v_nome, ";
$sCampos .= "  alunoprimat.ed76_i_codigo, ";
$sCampos .= "  alunoprimat.ed76_i_escola, ";
$sCampos .= "  alunoprimat.ed76_d_data, ";
$sCampos .= "  alunoprimat.ed76_c_tipo, ";
$sCampos .= "  case when ed76_c_tipo = 'M' ";
$sCampos .= "   then escolaprimat.ed18_c_nome else escolaproc.ed82_c_nome end as nomeescola ";
$result1  = $clmatricula->sql_record($clmatricula->sql_query("",
                                                             $sCampos,
                                                             "ed60_d_datamatricula desc",
                                                             " ed60_i_aluno = $chavepesquisa"
                                                            )
                                    );
$linhas11 = $clmatricula->numrows;
if ($clmatricula->numrows > 0) {
	
  $contador = 0;
  for($x = 0; $x < $linhas11; $x++) {
  	
    db_fieldsmemory($result1,$x);
    if ($contador == 3) {
    	
      $pdf->addpage('P');
      $pdf->ln(5);
      $pdf->cell(190,4,"MATRÍCULAS",1,1,"C",1);
      $pdf->cell(190,4,"","LR",1,"C",0);
      $contador = 0;
      
    }
    
    $contador++;
    $pdf->setfont('arial','',7);
    $pdf->cell(35,4,"Matrícula N°:","LT",0,"L",1);
    $pdf->setfont('arial','b',7);
    $pdf->cell(40,4,$ed60_i_codigo,"T",0,"L",1);
    $pdf->setfont('arial','',7);
    $pdf->cell(30,4,"Escola:","T",0,"L",1);
    $pdf->setfont('arial','b',7);
    $pdf->cell(85,4,$ed18_c_nome,"RT",1,"L",1);
    $pdf->setfont('arial','',7);
    $pdf->cell(35,4,"Data da Matrícula:","L",0,"L",1);
    $pdf->setfont('arial','b',7);
    $pdf->cell(40,4,db_formatar($ed60_d_datamatricula,'d'),0,0,"L",1);
    $pdf->setfont('arial','',7);
    $pdf->cell(30,4,"Situação:",0,0,"L",1);
    $pdf->setfont('arial','b',7);
    if (trim($ed60_c_situacao) == "AVANÇADO" || trim($ed60_c_situacao) == "CLASSIFICADO") {
      $sitt = trim($ed60_c_situacao);
    } else {
    	
      if ($ed60_c_concluida == "S") {
        $sitt = Situacao($ed60_c_situacao,$ed60_i_codigo)."(CONCLUÍDA)";
      } else {
        $sitt = Situacao($ed60_c_situacao,$ed60_i_codigo);
      }
    }
    $pdf->cell(85,4,$sitt,"R",1,"L",1);

    if (trim(Situacao($ed60_c_situacao,$ed60_i_codigo)) != "MATRICULADO" 
        && trim(Situacao($ed60_c_situacao,$ed60_i_codigo)) != "REMATRICULADO") {
     	
      $sCamposMatricula = "ed60_d_datasaida as datasaida, ";
      $sCamposMatricula .= "case ";
      $sCamposMatricula .= "  when ed60_c_situacao = 'TRANSFERIDO REDE' then ";
      $sCamposMatricula .= "   (select escoladestino.ed18_c_nome from transfescolarede ";
      $sCamposMatricula .= "     inner join atestvaga  on  atestvaga.ed102_i_codigo = transfescolarede.ed103_i_atestvaga ";
      $sCamposMatricula .= "     inner join escola  as escoladestino on  escoladestino.ed18_i_codigo = atestvaga.ed102_i_escola ";
      $sCamposMatricula .= "    where ed103_i_matricula = ed60_i_codigo order by ed103_d_data desc limit 1) ";
      $sCamposMatricula .= "  when ed60_c_situacao = 'TRANSFERIDO FORA' then ";
      $sCamposMatricula .= "   (select escolaproc.ed82_c_nome from transfescolafora ";
      $sCamposMatricula .= "     inner join escolaproc  on  escolaproc.ed82_i_codigo = transfescolafora.ed104_i_escoladestino ";
      $sCamposMatricula .= "    where ed104_i_matricula = ed60_i_codigo order by ed104_d_data desc limit 1) ";
      $sCamposMatricula .= "  else null ";
      $sCamposMatricula .= "  end as destinosaida ";
      $result221         = $clmatricula->sql_record($clmatricula->sql_query("",
                                                                            $sCamposMatricula,
                                                                            "",
                                                                            " ed60_i_codigo = $ed60_i_codigo"
                                                                           )
                                                   );
      db_fieldsmemory($result221,0);
      $pdf->setfont('arial','',7);
      $pdf->cell(35,4,"Data Sáida:","L",0,"L",1);
      $pdf->setfont('arial','b',7);
      $pdf->cell(40,4,db_formatar(@$datasaida,'d'),0,0,"L",1);
      $pdf->setfont('arial','',7);
      $pdf->cell(30,4,"Destino Sáida:",0,0,"L",1);
      $pdf->setfont('arial','b',7);
      $pdf->cell(85,4,@$destinosaida,"R",1,"L",1);
      
    }

    $pdf->setfont('arial','',7);
    $pdf->cell(35,4,"Nome da Turma:","L",0,"L",1);
    $pdf->setfont('arial','b',7);
    $pdf->cell(40,4,$ed57_c_descr,0,0,"L",1);
    $pdf->setfont('arial','',7);
    $pdf->cell(30,4,"Etapa:",0,0,"L",1);
    $pdf->setfont('arial','b',7);
    $pdf->cell(85,4,$ed11_c_descr,"R",1,"L",1);

    $pdf->setfont('arial','',7);
    $pdf->cell(35,4,"Turno:","L",0,"L",1);
    $pdf->setfont('arial','b',7);
    $pdf->cell(40,4,$ed15_c_nome,0,0,"L",1);
    $pdf->setfont('arial','',7);
    $pdf->cell(30,4,"Calendário:",0,0,"L",1);
    $pdf->setfont('arial','b',7);
    $pdf->cell(85,4,$ed52_c_descr." / ".$ed52_i_ano,"R",1,"L",1);

    GradeAproveitamentoBOLETIM($ed60_i_codigo,190,$pdf,"","P",0,"S");

    $pdf->cell(190,4,"","T",1,"C",0);
    
  }
  
} else {
	
  $pdf->cell(190,4,"","LRT",1,"C",0);
  $pdf->cell(5,4,"","L",0,"C",0);
  $pdf->cell(180,4,"Nenhuma registro.",1,0,"C",0);
  $pdf->cell(5,4,"","R",1,"C",0);
  $pdf->cell(190,4,"","LRB",1,"C",0);
  
}

/////////////////////////////////////////////
if ($clmatricula->numrows != 0) {
	
  $pdf->addpage('P');
  $pdf->ln(5);
  
} else {
  $pdf->cell(190,4,"",0,1,"C",0);
}
$pdf->cell(190,4,"HISTÓRICO",1,1,"C",1);
$pdf->cell(190,4,"","LR",1,"C",0);
$sCamposHistoricomps      = " ed29_c_descr,ed62_i_codigo,ed62_i_qtdch,ed11_c_descr,ed11_i_codigo,ed62_i_anoref,";
$sCamposHistoricomps     .= " ed62_i_periodoref,ed18_c_nome,ed11_i_sequencia,ed11_i_ensino,'REDE' as tipo"; 
$sCamposHistoricompsfora  = "ed29_c_descr,ed99_i_codigo,ed99_i_qtdch,ed11_c_descr,ed11_i_codigo,ed99_i_anoref,";
$sCamposHistoricompsfora .= " ed99_i_periodoref,ed82_c_nome,ed11_i_sequencia,ed11_i_ensino,'FORA' as tipo";
$sql3                     = " SELECT  $sCamposHistoricomps";
$sql3                    .= "  FROM historicomps ";
$sql3                    .= "   inner join serie on ed11_i_codigo = ed62_i_serie ";
$sql3                    .= "   inner join historico on ed61_i_codigo = ed62_i_historico ";
$sql3                    .= "   inner join cursoedu on ed29_i_codigo = ed61_i_curso ";
$sql3                    .= "   inner join escola on ed18_i_codigo = ed62_i_escola ";
$sql3                    .= " WHERE ed61_i_aluno = $chavepesquisa ";
$sql3                    .= " UNION ";
$sql3                    .= " SELECT  $sCamposHistoricompsfora";
$sql3                    .= "  FROM historicompsfora ";
$sql3                    .= "   inner join serie on ed11_i_codigo = ed99_i_serie ";
$sql3                    .= "   inner join historico on ed61_i_codigo = ed99_i_historico ";
$sql3                    .= "   inner join cursoedu on ed29_i_codigo = ed61_i_curso ";
$sql3                    .= "   inner join escolaproc on ed82_i_codigo = ed99_i_escolaproc ";
$sql3                    .= " WHERE ed61_i_aluno = $chavepesquisa ";
$sql3                    .= " ORDER BY ed11_i_ensino,ed11_i_sequencia DESC  ";
$result3                  = db_query($sql3);
$linhas3                  = pg_num_rows($result3);
$contador                 = 0;
if ($linhas3 > 0) {
	
  $primeiro = "";
  for ($t = 0; $t < $linhas3; $t++) {
  	
    db_fieldsmemory($result3,$t);
    if ($contador == 3) {
    	
      $pdf->cell(190,1,"","LRB",1,"C",0);
      $pdf->addpage('P');
      $pdf->ln(5);
      $pdf->cell(190,4,"HISTÓRICO",1,1,"C",1);
      $pdf->cell(190,4,"","LR",1,"C",0);
      $pdf->cell(5,4,"","L",0,"L",0);
      $pdf->cell(180,4,$ed29_c_descr,1,0,"L",1);
      $pdf->cell(5,4,"","R",1,"L",0);
      $pdf->cell(190,4,"","LR",1,"C",0);
      $contador = 0;
      
    }
    
    $contador++;
    if ($primeiro != $ed29_c_descr) {
    	
      $pdf->cell(5,4,"","L",0,"L",0);
      $pdf->cell(180,4,$ed29_c_descr,1,0,"L",1);
      $pdf->cell(5,4,"","R",1,"L",0);
      $pdf->cell(190,4,"","LR",1,"C",0);
      $primeiro = $ed29_c_descr;
      
    }
    
    $alt_geral = $pdf->getY();
    $pdf->setfont('arial','',7);
    $pdf->cell(5,16,"","LR",0,"C",0);
    $pdf->cell(30,4,"Etapa:","LT",2,"L",1);
    $pdf->cell(30,4,"Ano:","L",2,"L",1);
    $pdf->setY($alt_geral);
    $pdf->setX(40);
    $pdf->setfont('arial','b',7);
    $pdf->cell(65,4,$ed11_c_descr,"TR",2,"L",1);
    $pdf->cell(65,4,$ed62_i_anoref,"R",2,"L",1);
    $pdf->setY($alt_geral);
    $pdf->setX(105);
    $pdf->setfont('arial','',7);
    $pdf->cell(40,4,"Escola:","T",2,"L",0);
    $pdf->cell(40,4,"Carga Horária:",0,2,"L",0);
    $pdf->setY($alt_geral);
    $pdf->setX(125);
    $pdf->setfont('arial','b',7);
    $pdf->cell(70,4,substr($ed18_c_nome,0,45),"RT",2,"L",0);
    $pdf->cell(70,4,$ed62_i_qtdch ,"R",2,"L",0);
    $pdf->setY($alt_geral);
    $pdf->setX(195);
    $pdf->cell(5,8,"","LR",1,"C",0);
    if ($tipo == "REDE") {
    	
      $sCampos  = "ed65_i_codigo,ed232_c_descr,ed65_c_situacao,ed65_i_ordenacao,";
      $sCampos .= "case when ed65_c_situacao!='CONCLUÍDO' then '' else ed65_t_resultobtido end as ed65_t_resultobtido,";
      $sCampos .= "ed65_c_resultadofinal,ed65_i_qtdch,ed65_c_tiporesultado,ed65_i_historicomps,ed29_c_descr";
      $result   = $clhistmpsdisc->sql_record($clhistmpsdisc->sql_query("",
                                                                       "$sCampos",
                                                                       "ed65_i_ordenacao",
                                                                       " ed65_i_historicomps = $ed62_i_codigo"
                                                                      )
                                            );
      if ($result) {
      	
        $pdf->cell(5,4,"","L",0,"C",0);
        $pdf->cell(45,4,"Disciplina",1,0,"C",0);
        $pdf->cell(30,4,"Situação",1,0,"C",0);
        $pdf->cell(30,4,"Aproveitamento",1,0,"C",0);
        $pdf->cell(25,4,"RF",1,0,"C",0);
        $pdf->cell(20,4,"CH",1,0,"C",0);
        $pdf->cell(30,4,"TIPO RESULTADO",1,0,"C",0);
        $pdf->cell(5,4,"","R",1,"C",0);
        $pdf->setfont('arial','',7);
        if ($clhistmpsdisc->numrows > 0) {
          $cor1 = "#f3f3f3";
          $cor2 = "#DBDBDB";
          $cor  = "";
          for ($x = 0; $x < $clhistmpsdisc->numrows; $x++) {
            db_fieldsmemory($result,$x);
            if ($cor == $cor1) {
              $cor = $cor2;
            } else {
              $cor = $cor1;
            }
            if (trim($ed65_c_situacao) == "AMPARADO") {
              $ed65_t_resultobtido = "";
            } /*else if ($ed65_c_tiporesultado == 'N' and is_numeric($ed65_t_resultobtido) ) {
              $ed65_t_resultobtido = ArredondamentoNota::arredondar($ed65_t_resultobtido, $ed62_i_anoref);
            }*/
            if ($ed65_c_tiporesultado == "N") {
              $ed65_c_tiporesultado = "NOTA";
            } else if ($ed65_c_tiporesultado == "P") {
              $ed65_c_tiporesultado = "PARECER";
            } else if ($ed65_c_tiporesultado == "A") {
              $ed65_c_tiporesultado = "AVANÇO";
            } else if ($ed65_c_tiporesultado == "C") {
              $ed65_c_tiporesultado = "CLASSIF";
            }
            $pdf->cell(5,4,"","L",0,"C",0);
            $pdf->cell(45,4,$ed232_c_descr,1,0,"L",0);
            $pdf->cell(30,4,$ed65_c_situacao,1,0,"C",0);
            $pdf->cell(30,4,$ed65_t_resultobtido,1,0,"C",0);
            $pdf->cell(25,4,$ed65_c_resultadofinal=="R"?"REPROVADO":"APROVADO",1,0,"C",0);
            $pdf->cell(20,4,$ed65_i_qtdch==""?0:$ed65_i_qtdch,1,0,"C",0);
            $pdf->cell(30,4,trim($ed65_c_tiporesultado),1,0,"C",0);
            $pdf->cell(5,4,"","R",1,"C",0);
          }
          $pdf->cell(190,4,"","LR",1,"C",0);
        } else {
          $pdf->cell(5,4,"","L",0,"C",0);
          $pdf->cell(180,4,"Nenhuma disciplina cadastrada para esta etapa.",1,0,"C",0);
          $pdf->cell(5,4,"","R",1,"C",0);
        }
      }
    } else {
      $sCamposHisFora  = "ed100_i_codigo,ed232_c_descr,ed100_c_situacao,ed100_i_ordenacao,";
      $sCamposHisFora .= " case when ed100_c_situacao!='CONCLUÍDO' OR";
      $sCamposHisFora .= " ed100_t_resultobtido = '' then '0' else ed100_t_resultobtido end as ed100_t_resultobtido,";
      $sCamposHisFora .= "ed100_c_resultadofinal,ed100_i_qtdch,ed100_c_tiporesultado,ed100_i_historicompsfora,ed29_c_descr";             
      $result          = $clhistmpsdiscfora->sql_record($clhistmpsdiscfora->sql_query("",
                                                                                      $sCamposHisFora,
                                                                                      "ed100_i_ordenacao",
                                                                                      " ed100_i_historicompsfora = $ed62_i_codigo"
                                                                                     )
                                                       );
      if ($result) {
        $pdf->cell(5,4,"","L",0,"C",0);
        $pdf->cell(45,4,"Disciplina",1,0,"C",0);
        $pdf->cell(30,4,"Situação",1,0,"C",0);
        $pdf->cell(30,4,"Aproveitamento",1,0,"C",0);
        $pdf->cell(25,4,"RF",1,0,"C",0);
        $pdf->cell(20,4,"CH",1,0,"C",0);
        $pdf->cell(30,4,"TIPO RESULTADO",1,0,"C",0);
        $pdf->cell(5,4,"","R",1,"C",0);
        $pdf->setfont('arial','',7);
        if ($clhistmpsdiscfora->numrows > 0) {
        	
          for ($x = 0; $x < $clhistmpsdiscfora->numrows; $x++) {
          	
            db_fieldsmemory($result,$x);
            if (trim($ed100_c_situacao) == "AMPARADO") {
              $ed100_t_resultobtido = "&nbsp;";
            } /*else if ($ed100_c_tiporesultado == 'N' and is_numeric($ed100_t_resultobtido) ) {
              $ed100_t_resultobtido = ArredondamentoNota::arredondar($ed100_t_resultobtido, $ed62_i_anoref);
            }*/
            $pdf->cell(5,4,"","L",0,"C",0);
            $pdf->cell(45,4,$ed232_c_descr,1,0,"L",0);
            $pdf->cell(30,4,$ed100_c_situacao,1,0,"C",0);
            $pdf->cell(30,4,$ed100_t_resultobtido,1,0,"C",0);
            $pdf->cell(25,4,$ed100_c_resultadofinal=="R"?"REPROVADO":"APROVADO",1,0,"C",0);
            $pdf->cell(20,4,$ed100_i_qtdch==""?0:$ed100_i_qtdch,1,0,"C",0);
            $pdf->cell(30,4,trim($ed100_c_tiporesultado),1,0,"C",0);
            $pdf->cell(5,4,"","R",1,"C",0);
            
          }
          $pdf->cell(190,4,"","LR",1,"C",0);
          
        } else {
        	
          $pdf->cell(5,4,"","L",0,"C",0);
          $pdf->cell(180,4,"Nenhuma disciplina cadastrada para esta etapa.",1,0,"C",0);
          $pdf->cell(5,4,"","R",1,"C",0);
          
        }
      }
    }
  }
  $pdf->cell(190,1,"","LRB",1,"C",0);
} else {
	
  $pdf->cell(5,4,"","L",0,"C",0);
  $pdf->cell(180,4,"Nenhuma registro.",1,0,"C",0);
  $pdf->cell(5,4,"","R",1,"C",0);
  $pdf->cell(190,4,"","LR",1,"C",0);
  $pdf->cell(190,1,"","LRB",1,"C",0);
  
}

/////////////////////////////////////////////

if($clmatricula->numrows!=0 || $linhas3>0){
 $pdf->addpage('P');
 $pdf->ln(5);
}else{
 $pdf->cell(190,4,"",0,1,"C",0);
}
$pdf->cell(190,4,"MOVIMENTAÇÃO ESCOLAR",1,1,"C",1);
$pdf->cell(190,4,"","LR",1,"C",0);
$alt_inicio = $pdf->getY();
$array_mov  = array();
$sCampos  = " ed229_i_codigo,ed229_d_dataevento,ed18_i_codigo,ed18_c_nome,ed60_i_codigo,";
$sCampos .= " ed57_c_descr,ed52_i_ano,ed11_c_descr,ed229_c_procedimento,ed229_t_descr";
$sOrder  = " ed229_d_dataevento ,ed229_i_codigo ";
$sWhere  = " ed60_i_aluno = $chavepesquisa AND ed229_c_procedimento NOT LIKE  'CANCELAR ENCERRAMENTO%' AND";
$sWhere .= " ed229_c_procedimento NOT LIKE  'ENCERRAR%'";
$result  = $clmatriculamov->sql_record($clmatriculamov->sql_query("",
                                                                  $sCampos,
                                                                  $sOrder,
                                                                  $sWhere
                                                                 )
                                      );
if ($clmatriculamov->numrows > 0) {
	
  for ($f = 0; $f < $clmatriculamov->numrows; $f++) {
  	
    db_fieldsmemory($result,$f);
    $array_mov[]  = str_replace("-","",$ed229_d_dataevento).$ed229_i_codigo;
    $iContador    = count($array_mov)-1; 
    $array_mov[$iContador] .= "|".db_formatar($ed229_d_dataevento,'d')."#".$ed18_i_codigo." - ".substr($ed18_c_nome,0,30);
    $array_mov[$iContador] .= "#".$ed60_i_codigo."#".$ed57_c_descr."#".$ed52_i_ano."#".$ed11_c_descr;
    $array_mov[$iContador] .= "#".substr($ed229_c_procedimento,0,35)."#".$ed229_t_descr;
    
  }
}

$sCamposMat  = " ed229_i_codigo,ed229_d_dataevento,ed18_i_codigo,ed18_c_nome,ed60_i_codigo,";
$sCamposMat .= " ed57_c_descr,ed52_i_ano,ed11_c_descr,ed229_c_procedimento,ed229_t_descr";
$sOrderMat  = " ed229_d_dataevento DESC,ed229_i_codigo DESC LIMIT 1 ";
$sWhereMat  = " ed60_i_aluno = $chavepesquisa AND ed229_c_procedimento LIKE  'CANCELAR ENCERRAMENTO%'";
$result1    = $clmatriculamov->sql_record($clmatriculamov->sql_query("",
                                                                     $sCamposMat,
                                                                     $sOrderMat,
                                                                     $sWhereMat 
                                                                    )
                                         );
if ($clmatriculamov->numrows > 0) {
	
  db_fieldsmemory($result1,0);
  $array_mov[]            = str_replace("-","",$ed229_d_dataevento).$ed229_i_codigo;
  $iContador              = count($array_mov)-1; 
  $array_mov[$iContador] .= "|".db_formatar($ed229_d_dataevento,'d')."#".$ed18_i_codigo." - ".substr($ed18_c_nome,0,30);
  $array_mov[$iContador] .="#".$ed60_i_codigo."#".$ed57_c_descr."#".$ed52_i_ano."#".$ed11_c_descr;
  $array_mov[$iContador] .="#".substr($ed229_c_procedimento,0,35)."#".$ed229_t_descr;
  
}

$sCamposMatri  = "ed229_i_codigo,ed229_d_dataevento,ed18_i_codigo,ed18_c_nome,ed60_i_codigo,";
$sCamposMatri .= " ed57_c_descr,ed52_i_ano,ed11_c_descr,ed229_c_procedimento,ed229_t_descr";
$sOrderMatri  = "ed229_d_dataevento DESC,ed229_i_codigo DESC LIMIT 1";
$sWhereMatri  = "  ed60_i_aluno = $chavepesquisa AND ed229_c_procedimento LIKE  'ENCERRAR%'";
$result2      = $clmatriculamov->sql_record($clmatriculamov->sql_query("",
                                                                       $sCamposMatri,
                                                                       $sOrderMatri,
                                                                       $sWhereMatri
                                                                      )
                                           );
if ($clmatriculamov->numrows > 0) {
	
 db_fieldsmemory($result2,0);
 $array_mov[]  = str_replace("-","",$ed229_d_dataevento).$ed229_i_codigo;
 $iContador    = count($array_mov)-1; 
 $array_mov[$iContador] .= "|".db_formatar($ed229_d_dataevento,'d')."#".$ed18_i_codigo." - ".substr($ed18_c_nome,0,30);
 $array_mov[$iContador] .= "#".$ed60_i_codigo."#".$ed57_c_descr."#".$ed52_i_ano."#".$ed11_c_descr;
 $array_mov[$iContador] .="#".substr($ed229_c_procedimento,0,35)."#".$ed229_t_descr;
 
}

$array_ordem = SORT_ASC;
array_multisort($array_mov,$array_ordem);

if (count($array_mov) > 0) {
	
  $pdf->setfillcolor(210);
  $pdf->cell(5,4,"","L",0,"C",0);
  $pdf->cell(15,4,"Data",1,0,"C",1);
  $pdf->cell(55,4,"Escola",1,0,"C",1);
  $pdf->cell(10,4,"Matr.",1,0,"C",1);
  $pdf->cell(20,4,"Turma",1,0,"C",1);
  $pdf->cell(10,4,"Ano",1,0,"C",1);
  $pdf->cell(15,4,"Etapa",1,0,"C",1);
  $pdf->cell(55,4,"Procedimento",1,0,"C",1);
  $pdf->cell(5,4,"","R",1,"C",0);
  $pdf->setfillcolor(230);
  
  for ($f = 0; $f < count($array_mov); $f++) {
  	
    $array_mov1 = explode("|",$array_mov[$f]);
    $array_mov2 = explode("#",$array_mov1[1]);
    $pdf->cell(5,4,"","L",0,"C",0);
    $pdf->cell(15,4,$array_mov2[0],1,0,"C",1);
    $pdf->cell(55,4,$array_mov2[1],1,0,"L",1);
    $pdf->cell(10,4,$array_mov2[2],1,0,"C",1);
    $pdf->cell(20,4,$array_mov2[3],1,0,"C",1);
    $pdf->cell(10,4,$array_mov2[4],1,0,"C",1);
    $pdf->cell(15,4,$array_mov2[5],1,0,"C",1);
    $pdf->cell(55,4,$array_mov2[6],1,0,"L",1);
    $pdf->cell(5,4,"","R",1,"C",0);
    $alt_descr_ini = $pdf->getY();
    $pdf->cell(190,1,"","LR",1,"C",0);
    $pdf->cell(5,4,"","L",0,"C",0);
    $pdf->cell(15,4,"",0,0,"C",0);
    $pdf->multicell(165,4,$array_mov2[7],0,"L",0,0);
    $pdf->cell(190,1,"","LR",1,"C",0);
    $alt_descr_fim = $pdf->getY();
    $pdf->rect(15,$alt_descr_ini,180,$alt_descr_fim-$alt_descr_ini,'D');//retangulo
    $pdf->cell(190,2,"","LR",1,"C",0);
    
  }
  
  $pdf->cell(190,4,"","LR",1,"C",0);
  $pdf->cell(190,1,"","LRB",1,"C",0);
  $alt_final = $pdf->getY();
  $pdf->line(200,$alt_inicio,200,$alt_final);
  $pdf->line(10,$alt_inicio,10,$alt_final);

} else {

  $pdf->cell(5,4,"","L",0,"C",0);
  $pdf->cell(180,4,"Nenhuma registro.",1,0,"C",0);
  $pdf->cell(5,4,"","R",1,"C",0);
  $pdf->cell(190,4,"","LR",1,"C",0);
  $pdf->cell(190,1,"","LRB",1,"C",0);

}
$pdf->Output();
?>