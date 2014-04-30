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

require_once("libs/db_utils.php");
require_once("libs/db_stdlibwebseller.php");
require_once("model/educacao/ArredondamentoNota.model.php");
require_once("model/educacao/DBEducacaoTermo.model.php");
require_once("fpdf151/pdfwebseller.php");
require_once("dbforms/db_funcoes.php");

$oGet                 = db_utils::postMemory($_GET);
$oDaoMatricula        = db_utils::getdao('matricula');
$oDaoMatriculamov     = db_utils::getdao('matriculamov');
$oDaoAluno            = db_utils::getdao('aluno');
$oDaoAlunoprimat      = db_utils::getdao('alunoprimat');
$oDaoAlunonecessidade = db_utils::getdao('alunonecessidade');
$oDaoProcResultado    = db_utils::getdao('procresultado');
$oDaoHistmpsdisc      = db_utils::getdao('histmpsdisc');
$oDaoHistmpsdiscfora  = db_utils::getdao('histmpsdiscfora');
$resultedu            = eduparametros(db_getsession("DB_coddepto"));
$permitenotaembranco  = VerParametroNota(db_getsession("DB_coddepto"));

$oDaoAluno->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed76_i_escola");
$clrotulo->label("ed76_d_data");

$escola = db_getsession("DB_coddepto");// variavél $escola não está sendo usada na classe @matheus;

$sCampos  = " aluno.*,   ";
$sCampos .= " censoufident.ed260_c_nome as ufident,  ";
$sCampos .= " censoufnat.ed260_c_nome as ufnat,   ";
$sCampos .= " censoufcert.ed260_c_nome as ufcert,   ";
$sCampos .= " censoufend.ed260_c_nome as ufend,   ";
$sCampos .= " censomunicnat.ed261_c_nome as municnat,   ";
$sCampos .= " censomuniccert.ed261_c_nome as municcert,   ";
$sCampos .= " censomunicend.ed261_c_nome as municend,   ";
$sCampos .= " censoorgemissrg.ed132_c_descr as orgemissrg,   ";
$sCampos .= " pais.ed228_c_descr ";
$sSql     = $oDaoAluno->sql_query("",  $sCampos, "ed47_v_nome", " ed47_i_codigo IN ($alunos) ");
$rsResult = $oDaoAluno->sql_record($sSql);

if ($oDaoAluno->numrows == 0) {?>

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

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->setfillcolor(223);
$oPdf->SetAutoPageBreak(false, 10);

for ($iCont = 0; $iCont < $oDaoAluno->numrows; $iCont++) {

  db_fieldsmemory($rsResult,  $iCont);
  $head1 = "FICHA DO ALUNO";
  $head2 = "$ed47_i_codigo - $ed47_v_nome";
  $oPdf->addpage('P');

/** DADOS PESSOAIS */

  $oPdf->setfont('arial',  'b',  7);
  $oPdf->cell(160,  4, "   DADOS PESSOAIS",  "LBT",  0,  "L",  1);
  $oPdf->cell(34,  4, "   FOTO",  1,  1,  "L",  1);
  $oPdf->cell(160,  2,  "",  "LR",  0,  "C",  0);
  $oPdf->cell(34,  2,  "",  "LR" , 1,  "C" , 0);

  $oPdf->cell(3, 4 , "",  "L",  0,  "C",  0);
  $oPdf->setfont('arial',  '',  7);
  $oPdf->cell(35,  4, strip_tags($Led47_v_nome), 0, 0, "L", 0);
  $oPdf->setfont('arial',  'b', 7);
  $oPdf->cell(120,  4, $ed47_v_nome, 0, 0, "L", 0);
  $oPdf->cell(2,  4, "", "R", 1, "C", 0);

  $oPdf->cell(3,  4, "", "L", 0, "C", 0);
  $oPdf->setfont('arial',  '', 7);
  $oPdf->cell(35,  4, strip_tags($Led47_i_codigo), 0, 0, "L", 0);
  $oPdf->setfont('arial',  'b', 7);
  $oPdf->cell(20,  4, $ed47_i_codigo, 0, 0, "L", 0);
  $oPdf->setfont('arial',  '', 7);
  $oPdf->cell(30,  4, strip_tags($Led47_c_codigoinep), 0, 0, "R", 0);
  $oPdf->setfont('arial',  'b', 7);
  $oPdf->cell(20,  4, $ed47_c_codigoinep, 0, 0, "L", 0);
  $oPdf->setfont('arial',  '', 7);
  $oPdf->cell(25,  4, strip_tags($Led47_c_nis), 0, 0, "R", 0);
  $oPdf->setfont('arial',  'b', 7);
  $oPdf->cell(25,  4, $ed47_c_nis, 0, 0, "L", 0);
  $oPdf->cell(2,  4, "", "R", 1, "C", 0);

  if ($ed47_o_oid != 0) {

    $pArquivo = "tmp/".$ed47_c_foto;

    db_query("begin");
    $lResultExport = pg_lo_export($ed47_o_oid,   $pArquivo,  $conn);
    db_query("end");
    $oPdf->Image($pArquivo,  177, 43, 20);

  }

  $oPdf->cell(3,  4, "", "L", 0, "C", 0);
  $oPdf->setfont('arial',  '', 7);
  $oPdf->cell(35,  4, strip_tags($Led47_d_nasc), 0, 0, "L", 0);
  $oPdf->setfont('arial',  'b', 7);
  $oPdf->cell(20,  4, db_formatar($ed47_d_nasc, 'd'), 0, 0, "L", 0);
  $oPdf->setfont('arial',  '', 7);
  $oPdf->cell(30,  4, strip_tags($Led47_v_sexo), 0, 0, "R", 0);
  $oPdf->setfont('arial',  'b', 7);
  $oPdf->cell(20,  4, $ed47_v_sexo=="M"?"MASCULINO":"FEMININO", 0, 0, "L", 0);
  $oPdf->setfont('arial',  '', 7);
  $oPdf->cell(25,  4, strip_tags($Led47_i_estciv), 0, 0, "R", 0);

  if ($ed47_i_estciv == 1) {
    $ed47_i_estciv = "SOLTEIRO";
  } else if ($ed47_i_estciv == 2) {
    $ed47_i_estciv = "CASADO";
  } else if ($ed47_i_estciv == 3) {
    $ed47_i_estciv = "VIÚVO";
  } else {
    $ed47_i_estciv = "DIVORCIADO";
  }

  $oPdf->setfont('arial',  'b', 7);
  $oPdf->cell(25,  4, $ed47_i_estciv, 0, 0, "L", 0);
  $oPdf->cell(2,  4, "", "R", 1, "C", 0);
  $oPdf->cell(3, 4, "", "L", 0, "C", 0);
  $oPdf->setfont('arial', '', 7);
  $oPdf->cell(35,  4,  strip_tags($Led47_i_filiacao),  0,  0,  "L",  0);
  $oPdf->setfont('arial',  'b', 7);
  $oPdf->cell(65,  4,  $ed47_i_filiacao == "0" ? "NÃO DECLARADO / IGNORADO" : "PAI E/OU MÃE", 0, 0, "L", 0);
  $oPdf->setfont('arial' , '',  7);
  $oPdf->cell(30,  4,  strip_tags($Led47_c_raca),  0,  0,  "R",  0);
  $oPdf->setfont('arial', 'b', 7);
  $oPdf->cell(25,  4,  $ed47_c_raca,  0,  0,  "L",  0);
  $oPdf->cell(2,  4,  "",  "R",  1,  "C",  0);
  $oPdf->cell(3,  4,  "",  "L",  0,  "C",  0);
  $oPdf->setfont('arial',  '',  7);
  $oPdf->cell(35,  4,  strip_tags($Led47_v_pai),  0,  0,  "L",  0);
  $oPdf->setfont('arial',  'b',  7);
  $oPdf->cell(120,  4,  $ed47_v_pai,  0,  0,  "L",  0);
  $oPdf->cell(2,  4,  "",  "R",  1,  "C",  0);

  $oPdf->cell(3,  4,  "",  "L",  0,  "C",  0);
  $oPdf->setfont('arial',  '',  7);
  $oPdf->cell(35,  4,  strip_tags($Led47_v_mae),  0,  0,  "L",  0);
  $oPdf->setfont('arial',  'b',  7);
  $oPdf->cell(120,  4,  $ed47_v_mae,  0,  0,  "L",  0);
  $oPdf->cell(2,  4,  "",  "R",  1,  "C",  0);

  $oPdf->cell(3,  4,  "",  "L",  0,  "C",  0);
  $oPdf->setfont('arial',  '',  7);
  $oPdf->cell(35,  4,  strip_tags($Led47_c_nomeresp),  0,  0,  "L",  0);
  $oPdf->setfont('arial',  'b',  7);
  $oPdf->cell(120,  4,  $ed47_c_nomeresp,  0,  0,  "L",  0);
  $oPdf->cell(2,  4,  "",  "R",  1,  "C",  0);

  $oPdf->cell(3,  4,  "",  "L",  0,  "C",  0);
  $oPdf->setfont('arial',  '',  7);
  $oPdf->cell(35,  4,  strip_tags($Led47_c_emailresp),  0,  0,  "L",  0);
  $oPdf->setfont('arial',  'b',  7);
  $oPdf->cell(122,  4,  $ed47_c_emailresp,  'R',  0,  "L",  0);
  $oPdf->Ln();
  
  $oPdf->setfont('arial',  '',  7);
  $oPdf->cell(3,  4,  "",  "L",  0,  "C",  0);
  $oPdf->cell(35,  4,  strip_tags($Led47_celularresponsavel),  0,  0,  "L",  0);
  $oPdf->setfont('arial',  'b',  7);
  $oPdf->cell(120,  4,  $ed47_celularresponsavel,  0,  0,  "L",  0);
  $oPdf->cell(2,  4,  "",  "R",  1,  "C",  0);
  $oPdf->line(204,  35,  204,  80);

 //////////////////////////////////////////////////////////

  $oPdf->cell(160,  2 ,  "" ,  "LR" , 0 , "C" , 0);
  $oPdf->cell(34,  2,  "",  "LR",  1,  "C",  0);
  $oPdf->cell(194,  4,  "ENDEREÇO / CONTATOS",  1,  1,  "L",  1);
  $oPdf->cell(194,  2,  "",  "LR",  1,  "C",  0);

  $oPdf->cell(3,  4,  "",  "L",  0,  "C",  0);
  $oPdf->setfont('arial',  '',  7);
  $oPdf->cell(30,  4,  strip_tags($Led47_v_ender),  0,  0,  "L",  0);
  $oPdf->setfont('arial',  'b',  7);
  $oPdf->cell(40,  4,  substr($ed47_v_ender,  0,  37),  0,  0,  "L",  0);
  $oPdf->setfont('arial',  '',  7);
  $oPdf->cell(30,  4,  strip_tags($Led47_c_numero),  0,  0,  "R",  0);
  $oPdf->setfont('arial',  'b',  7);
  $oPdf->cell(30,  4,  $ed47_c_numero,  0,  0,  "L",  0);
  $oPdf->setfont('arial',  '',  7);
  $oPdf->cell(25,  4,  strip_tags($Led47_v_compl),  0,  0,  "R",  0);
  $oPdf->setfont('arial',  'b',  7);
  $oPdf->cell(35,  4,  $ed47_v_compl,  0,  0,  "L",  0);
  $oPdf->cell(1,  4,  "",  "R",  1,  "C",  0);

  $oPdf->cell(3,  4,  "",  "L",  0,  "C",  0);
  $oPdf->setfont('arial',  '',  7);
  $oPdf->cell(30,  4,  strip_tags($Led47_i_censoufend),  0,  0,  "L",  0);
  $oPdf->setfont('arial',  'b',  7);
  $oPdf->cell(40,  4,  $ufend,  0,  0,  "L",  0);
  $oPdf->setfont('arial',  '',  7);
  $oPdf->cell(30,  4,  strip_tags($Led47_i_censomunicend),  0,  0,  "R",  0);
  $oPdf->setfont('arial',  'b',  7);
  $oPdf->cell(30,  4,  $municend,  0,  0,  "L",  0);
  $oPdf->setfont('arial', '', 7);
  $oPdf->cell(25, 4, strip_tags($Led47_v_bairro), 0, 0, "R", 0);
  $oPdf->setfont('arial',  'b',  7);
  $oPdf->cell(35,  4,  substr($ed47_v_bairro,  0,  23),  0,  0,  "L",  0);
  $oPdf->cell(1,  4,  "",  "R",  1,  "C",  0);

  $oPdf->cell(3,  4,  "",  "L",  0,  "C",  0);
  $oPdf->setfont('arial',  '',  7);
  $oPdf->cell(30,  4,  strip_tags($Led47_c_zona),  0,  0,  "L",  0);
  $oPdf->setfont('arial',  'b',  7);
  $oPdf->cell(40,  4,  $ed47_c_zona,  0,  0,  "L",  0);
  $oPdf->setfont('arial',  '',  7);
  $oPdf->cell(30,  4,  strip_tags($Led47_v_cep),  0,  0,  "R",  0);
  $oPdf->setfont('arial',  'b',  7);
  $oPdf->cell(90,  4,  $ed47_v_cep,  0,  0,  "L",  0);
  $oPdf->cell(1,  4,  "",  "R",  1,  "C",  0);

  $oPdf->cell(3,  4,  "",  "L",  0,  "C",  0);
  $oPdf->setfont('arial',  '',  7);
  $oPdf->cell(30,  4,  strip_tags($Led47_v_telef),  0,  0,  "L",  0);
  $oPdf->setfont('arial',  'b',  7);
  $oPdf->cell(40,  4,  $ed47_v_telef,  0,  0,  "L",  0);
  $oPdf->setfont('arial',  '',  7);
  $oPdf->cell(30,  4,  strip_tags($Led47_v_telcel),  0,  0,  "R",  0);
  $oPdf->setfont('arial',  'b',  7);
  $oPdf->cell(30,  4,  $ed47_v_telcel,  0,  0,  "L",  0);
  $oPdf->setfont('arial',  '',  7);
  $oPdf->cell(25,  4,  strip_tags($Led47_v_fax),  0,  0,  "R",  0);
  $oPdf->setfont('arial',  'b',  7);
  $oPdf->cell(35,  4,  $ed47_v_fax,  0,  0,  "L",  0);
  $oPdf->cell(1,  4,  "",  "R",  1,  "C",  0);

  $oPdf->cell(3,  4,  "",  "L",  0,  "C",  0);
  $oPdf->setfont('arial',  '',  7);
  $oPdf->cell(30,  4,  strip_tags($Led47_v_email),  0,  0,  "L",  0);
  $oPdf->setfont('arial',  'b',  7);
  $oPdf->cell(40,  4,  $ed47_v_email,  0,  0,  "L",  0);
  $oPdf->setfont('arial',  '',  7);
  $oPdf->cell(30,  4,  strip_tags($Led47_v_cxpostal),  0,  0,  "R",  0);
  $oPdf->setfont('arial',  'b',  7);
  $oPdf->cell(90,  4,  $ed47_v_cxpostal,  0,  0,  "L",  0);
  $oPdf->cell(1,  4,  "",  "R",  1,  "C",  0);

//////////////////////////////////////////////////////////

  $oPdf->cell(194,  2,  "",  "LR",  1,  "C",  0);
  $oPdf->cell(194,  4,   "OUTRAS INFORMAÇÕES" , 1 ,  1,  "L",  1);
  $oPdf->cell(194,  2,  "",  "LR",  1,  "C",  0);

  $oPdf->cell(3,  4,  "",  "L",  0,  "C",  0);
  $oPdf->setfont('arial',  '',  7);
  $oPdf->cell(30,  4,  strip_tags($Led47_i_nacion),  0,  0,  "L",  0);

  if ($ed47_i_nacion == 1) {
    $ed47_i_nacion = "BRASILEIRA";
  } else if ($ed47_i_nacion == 2) {
    $ed47_i_nacion = "BRASILEIRA NO EXTERIOR OU NATURALIZADO";
  } else if ($ed47_i_nacion == 3) {
    $ed47_i_nacion = "ESTRANGEIRA";
  }

  $oPdf->setfont('arial',  'b',  7);
  $oPdf->cell(40, 4, $ed47_i_nacion,  0,  0,  "L",  0);
  $oPdf->setfont('arial',  '',  7);
  $oPdf->cell(30,  4,  strip_tags($Led47_i_pais),  0,  0,  "R",  0);
  $oPdf->setfont('arial',  'b',  7);
  $oPdf->cell(90,  4,  $ed228_c_descr,  0,  0,  "L",  0);
  $oPdf->cell(1,  4,  "",  "R",  1,  "C",  0);

  $oPdf->cell(3,  4,  "",  "L",  0,  "C",  0);
  $oPdf->setfont('arial',  '',  7);
  $oPdf->cell(30,  4,  strip_tags($Led47_i_censoufnat),  0,  0,  "L",  0);
  $oPdf->setfont('arial',  'b',  7);
  $oPdf->cell(40,  4,  $ufnat,  0,  0,  "L",  0);
  $oPdf->setfont('arial',  '',  7);
  $oPdf->cell(30,  4,  strip_tags($Led47_i_censomunicnat),  0,  0,  "R",  0);
  $oPdf->setfont('arial',  'b',  7);
  $oPdf->cell(90,  4,  $municnat,  0,  0,  "L",  0);
  $oPdf->cell(1,  4,  "",  "R",  1,  "C",  0);

  $oPdf->cell(3,  4,  "",  "L",  0,  "C",  0);
  $oPdf->setfont('arial',  '',  7);
  $oPdf->cell(30,  4,  strip_tags($Led47_i_transpublico),  0,  0,  "L",  0);
  $oPdf->setfont('arial',  'b',  7);
  $oPdf->cell(40, 4, $ed47_i_transpublico=="0"?"NÃO UTILIZA":"UTILIZA", 0, 0, "L", 0);
  $oPdf->setfont('arial', '', 7);
  $oPdf->cell(30, 4, strip_tags($Led47_c_transporte), 0, 0, "R", 0);

  if ($ed47_c_transporte == 1) {
    $ed47_c_transporte = "ESTADUAL";
  } else if ($ed47_c_transporte == 2) {
    $ed47_c_transporte = "MUNICIPAL";
  } else {
    $ed47_c_transporte = "";
  }

  $oPdf->setfont('arial', 'b', 7);
  $oPdf->cell(90, 4, $ed47_c_transporte, 0, 0, "L", 0);
  $oPdf->cell(1, 4, "", "R", 1, "C", 0);

  $oPdf->cell(3, 4, "", "L", 0, "C", 0);
  $oPdf->setfont('arial', '', 7);
  $oPdf->cell(30, 4, strip_tags($Led47_c_bolsafamilia), 0, 0, "L", 0);
  $oPdf->setfont('arial', 'b', 7);
  $oPdf->cell(23, 4, $ed47_c_bolsafamilia=='N'?'NÃO':'SIM', 0, 0, "L", 0);
  $oPdf->setfont('arial', '', 7);
  $oPdf->cell(47, 4, strip_tags($Led47_c_atenddifer), 0, 0, "L", 0);

  if ($ed47_c_atenddifer == 1) {
    $ed47_c_atenddifer = "EM HOSPITAL";
  } else if ($ed47_c_atenddifer == 2) {
    $ed47_c_atenddifer = "EM DOMICÍLIO";
  } else if ($ed47_c_atenddifer == 3) {
    $ed47_c_atenddifer = "NÃO RECEBE";
  }

  $oPdf->setfont('arial',  'b',  7);
  $oPdf->cell(30,  4,  $ed47_c_atenddifer,  0,  0,  "L",  0);
  $oPdf->setfont('arial',  '',  7);
  $oPdf->cell(25,  4,  strip_tags($Led47_v_profis),  0,  0,  "R",  0);
  $oPdf->setfont('arial', 'b', 7);
  $oPdf->cell(35,  4,  substr($ed47_v_profis, 0, 23),  0,  0,  "L",  0);
  $oPdf->cell(1,  4,  "" ,  "R",  1,  "C",  0);
  $sCampos1   = " ed76_d_data,  ";
  $sCampos1  .= " case when ed76_c_tipo = 'M' ";
  $sCampos1  .= " then ed18_c_nome else ed82_c_nome end as nomeescola ";
  $sSql11     = $oDaoAlunoprimat->sql_query("", $sCampos1, "", " ed76_i_aluno = $ed47_i_codigo");
  $rsResult11 = $oDaoAlunoprimat->sql_record($sSql11);

  if ($oDaoAlunoprimat->numrows > 0) {
    db_fieldsmemory($rsResult11, 0);
  } else {
    $ed76_d_data = "";
    $nomeescola  = "";
  }

  $oPdf->cell(3,  4,  "",  "L",  0,  "C",  0);
  $oPdf->setfont('arial',  '',  7);
  $oPdf->cell(30,  4,  strip_tags($Led76_i_escola),  0,  0,  "L",  0);
  $oPdf->setfont('arial', 'b',  7);
  $oPdf->cell(160,  4,  substr($nomeescola,  0,  30),  0,  0,  "L",  0);
  $oPdf->cell(1,  4,  "",  "R",  1,  "C",  0);
  
  $oPdf->cell(3,  4,  "",  "L",  0,  "C",  0);
  $oPdf->setfont('arial',  '',  7);
  $oPdf->cell(30,  4,  strip_tags($Led76_d_data),  0,  0,  "L",  0);
  $oPdf->setfont('arial', 'b', 7);
  $oPdf->cell(160,  4,  db_formatar($ed76_d_data, 'd'),  0,  0,  "L",  0);
  $oPdf->cell(1,  4,  "",  "R",  1,  "C",  0);

 //////////////////////////////////////////////////////////

  $oPdf->cell(194,  2,  "",  "LR",  1,  "C",  0);
  $oPdf->cell(194,  4,  " DOCUMENTOS",  1,  1,  "L",  1);
  $oPdf->cell(194,  2,  "",  "LR",  1,  "C",  0);

  $oPdf->cell(3,  4,  "",  "L",  0,  "C",  0);
  $oPdf->setfont('arial',  '',  7);
  $oPdf->cell(30,  4,  strip_tags($Led47_c_certidaotipo),  0,  0,  "L",  0);

  if ($ed47_c_certidaotipo == "N") {
    $ed47_c_certidaotipo = "NASCIMENTO";
  } else if ($ed47_c_certidaotipo == "C") {
    $ed47_c_certidaotipo = "CASAMENTO";
  } else {
    $ed47_c_certidaotipo = "";
  }

  $oPdf->setfont('arial',  'b',  7);
  $oPdf->cell(40,  4,  $ed47_c_certidaotipo,  0,  0,  "L",  0);
  $oPdf->setfont('arial', '', 7);
  $oPdf->cell(30, 4, strip_tags($Led47_c_certidaonum), 0, 0, "R", 0);
  $oPdf->setfont('arial', 'b', 7);
  $oPdf->cell(90, 4, $ed47_c_certidaonum, 0, 0, "L", 0);
  $oPdf->cell(1, 4, "", "R", 1, "C", 0);

  $oPdf->cell(3, 4, "", "L", 0, "C", 0);
  $oPdf->setfont('arial', '', 7);
  $oPdf->cell(30, 4, strip_tags($Led47_c_certidaofolha), 0, 0, "L", 0);
  $oPdf->setfont('arial', 'b', 7);
  $oPdf->cell(40, 4, $ed47_c_certidaofolha, 0, 0, "L", 0);
  $oPdf->setfont('arial', '', 7);
  $oPdf->cell(30, 4, strip_tags($Led47_c_certidaolivro), 0, 0, "R", 0);
  $oPdf->setfont('arial', 'b', 7);
  $oPdf->cell(30, 4, $ed47_c_certidaolivro, 0, 0, "L", 0);
  $oPdf->setfont('arial', '', 7);
  $oPdf->cell(25, 4, strip_tags($Led47_c_certidaodata), 0, 0, "R", 0);
  $oPdf->setfont('arial', 'b', 7);
  $oPdf->cell(35, 4, db_formatar($ed47_c_certidaodata, 'd'), 0, 0, "L", 0);
  $oPdf->cell(1, 4, "", "R", 1, "C", 0);

  $oPdf->cell(3, 4, "", "L", 0, "C", 0);
  $oPdf->setfont('arial', '', 7);
  $oPdf->cell(30, 4, strip_tags($Led47_i_censoufcert), 0, 0, "L", 0);
  $oPdf->setfont('arial', 'b', 7);
  $oPdf->cell(40, 4, $ufcert, 0, 0, "L", 0);
  $oPdf->setfont('arial', '', 7);
  $oPdf->cell(30, 4, strip_tags($Led47_i_censomuniccert), 0, 0, "R", 0);
  $oPdf->setfont('arial', 'b', 7);
  $oPdf->cell(90, 4, $municcert, 0, 0, "L", 0);
  $oPdf->cell(1, 4, "", "R", 1, "C", 0);

  $oPdf->cell(3, 4, "", "L", 0, "C", 0);
  $oPdf->setfont('arial', '', 7);
  $oPdf->cell(30, 4, strip_tags($Led47_c_certidaocart), 0, 0, "L", 0);
  $oPdf->setfont('arial', 'b', 7);
  $oPdf->cell(160, 4, substr($ed47_c_certidaocart, 0, 90), 0, 0, "L", 0);
  $oPdf->cell(1, 4, "", "R", 1, "C", 0);

  $oPdf->cell(3, 4, "", "L", 0, "C", 0);
  $oPdf->cell(188, 0.5, "", 1, 0, "C", 1);
  $oPdf->cell(3, 4, "", "R", 1, "C", 0);

  $oPdf->cell(3, 4, "", "L", 0, "C", 0);
  $oPdf->setfont('arial', '', 7);
  $oPdf->cell(30, 4, strip_tags($Led47_v_ident), 0, 0, "L", 0);
  $oPdf->setfont('arial', 'b', 7);
  $oPdf->cell(40, 4, $ed47_v_ident, 0, 0, "L", 0);
  $oPdf->setfont('arial', '', 7);
  $oPdf->cell(30, 4, strip_tags($Led47_v_identcompl), 0, 0, "R", 0);
  $oPdf->setfont('arial', 'b', 7);
  $oPdf->cell(30, 4, $ed47_v_identcompl, 0, 0, "L", 0);
  $oPdf->setfont('arial', '', 7);
  $oPdf->cell(25, 4, strip_tags($Led47_i_censoufident), 0, 0, "R", 0);
  $oPdf->setfont('arial', 'b', 7);
  $oPdf->cell(35, 4, $ufident, 0, 0, "L", 0);
  $oPdf->cell(1, 4, "", "R", 1, "C", 0);

  $oPdf->cell(3, 4, "", "L", 0, "C", 0);
  $oPdf->setfont('arial', '', 7);
  $oPdf->cell(30, 4, strip_tags($Led47_i_censoorgemissrg), 0, 0, "L", 0);
  $oPdf->setfont('arial', 'b', 7);
  $oPdf->cell(100, 4, $orgemissrg, 0, 0, "L", 0);
  $oPdf->setfont('arial', '', 7);
  $oPdf->cell(25, 4, strip_tags($Led47_d_identdtexp), 0, 0, "R", 0);
  $oPdf->setfont('arial', 'b', 7);
  $oPdf->cell(35, 4, db_formatar($ed47_d_identdtexp, 'd'), 0, 0, "L", 0);
  $oPdf->cell(1, 4, "", "R", 1, "C", 0);

  $oPdf->cell(3, 4, "", "L", 0, "C", 0);
  $oPdf->cell(188, 0.5, "", 1, 0, "C", 1);
  $oPdf->cell(3, 4, "", "R", 1, "C", 0);

  $oPdf->cell(3, 4, "", "L", 0, "C", 0);
  $oPdf->setfont('arial', '', 7);
  $oPdf->cell(30, 4, strip_tags($Led47_v_cnh), 0, 0, "L", 0);
  $oPdf->setfont('arial', 'b', 7);
  $oPdf->cell(40, 4, $ed47_v_cnh, 0, 0, "L", 0);
  $oPdf->setfont('arial', '', 7);
  $oPdf->cell(30, 4, strip_tags($Led47_v_categoria), 0, 0, "R", 0);
  $oPdf->setfont('arial', 'b', 7);
  $oPdf->cell(90, 4, $ed47_v_categoria, 0, 0, "L", 0);
  $oPdf->cell(1, 4, "", "R", 1, "C", 0);

  $oPdf->cell(3, 4, "", "L", 0, "C", 0);
  $oPdf->setfont('arial', '', 7);
  $oPdf->cell(30, 4, strip_tags($Led47_d_dtemissao), 0, 0, "L", 0);
  $oPdf->setfont('arial', 'b', 7);
  $oPdf->cell(40, 4, db_formatar($ed47_d_dtemissao, 'd'), 0, 0, "L", 0);
  $oPdf->setfont('arial', '', 7);
  $oPdf->cell(30, 4, strip_tags($Led47_d_dthabilitacao), 0, 0, "R", 0);
  $oPdf->setfont('arial', 'b', 7);
  $oPdf->cell(30, 4, db_formatar($ed47_d_dthabilitacao, 'd'), 0, 0, "L", 0);
  $oPdf->setfont('arial', '', 7);
  $oPdf->cell(25, 4, strip_tags($Led47_d_dtvencimento), 0, 0, "R", 0);
  $oPdf->setfont('arial', 'b', 7);
  $oPdf->cell(35, 4, db_formatar($ed47_d_dtvencimento, 'd'), 0, 0, "L", 0);
  $oPdf->cell(1, 4, "", "R", 1, "C", 0);

  $oPdf->cell(3, 4, "", "L", 0, "C", 0);
  $oPdf->cell(188, 0.5, "", 1, 0, "C", 1);
  $oPdf->cell(3, 4, "", "R", 1, "C", 0);

  $oPdf->cell(3, 4, "", "L", 0, "C", 0);
  $oPdf->setfont('arial', '', 7);
  $oPdf->cell(30, 4, strip_tags($Led47_v_cpf), 0, 0, "L", 0);
  $oPdf->setfont('arial', 'b', 7);
  $oPdf->cell(40, 4, $ed47_v_cpf, 0, 0, "L", 0);
  $oPdf->setfont('arial', '', 7);
  $oPdf->cell(30, 4, strip_tags($Led47_c_passaporte), 0, 0, "R", 0);
  $oPdf->setfont('arial', 'b', 7);
  $oPdf->cell(90, 4, $ed47_c_passaporte, 0, 0, "L", 0);
  $oPdf->cell(1, 4, "", "R", 1, "C", 0);

 //////////////////////////////////////////////////////////

  $oPdf->cell(194, 2, "", "LR", 1, "C", 0);
  $oPdf->cell(194, 4, "   NECESSIDADES ESPECIAIS", 1, 1, "L", 1);
  $oPdf->cell(194, 2, "", "LR", 1, "C", 0);

  $sSql22     = $oDaoAlunonecessidade->sql_query("", "*", "ed48_c_descr LIMIT 2", " ed214_i_aluno = $ed47_i_codigo");
  $rsResult22 = $oDaoAlunonecessidade->sql_record($sSql22);
  $iCont = 0;

  if ($oDaoAlunonecessidade->numrows > 0) {

    $oPdf->cell(3, 4, "", "L", 0, "C", 0);
    $oPdf->setfont('arial', 'b', 7);
    $oPdf->cell(70, 4, "Descrição:", 0, 0, "L", 0);
    $oPdf->cell(120, 4, "Necessidade Maior:", 0, 0, "L", 0);
    $oPdf->cell(1, 4, "", "R", 1, "C", 0);

    for ($iCont = 0; $iCont < $oDaoAlunonecessidade->numrows; $iCont++) {

      db_fieldsmemory($rsResult22, $iCont);
      $oPdf->cell(3, 4, "", "L", 0, "C", 0);
      $oPdf->setfont('arial', '', 7);
      $oPdf->cell(70, 4, $ed48_c_descr, 0, 0, "L", 0);
      $oPdf->cell(120, 4, $ed214_c_principal, 0, 0, "L", 0);
      $oPdf->cell(1, 4, "", "R", 1, "C", 0);
      $iCont++;

    }

  } else {

    $oPdf->cell(3, 4, "", "L", 0, "C", 0);
    $oPdf->setfont('arial', '', 7);
    $oPdf->cell(190, 4, "Nenhum registro.", 0, 0, "L", 0);
    $oPdf->cell(1, 4, "", "R", 1, "C", 0);
    $iCont++;

  }

  for ($iFor = $iCont; $iFor < 2; $iFor++) {

    $oPdf->cell(3, 4, "", "L", 0, "C", 0);
    $oPdf->cell(190, 4, "", 0, 0, "C", 0);
    $oPdf->cell(1, 4, "", "R", 1, "C", 0);

  }

 //////////////////////////////////////////////////////////
  $oPdf->setfont('arial', 'b', 7);
  $oPdf->cell(194, 2, "", "LR", 1, "C", 0);
  $oPdf->cell(97, 4, "   OBSERVAÇÔES", 1, 0, "L", 1);
  $oPdf->cell(97, 4, "   CONTATO", 1, 1, "L", 1);
  $oPdf->cell(97, 2, "", "LR", 0, "C", 0);
  $oPdf->cell(97, 2, "", "LR", 1, "C", 0);

  $alt_obs = $oPdf->getY();
  $oPdf->setfont('arial', '', 7);
  $oPdf->cell(3, 50, "", "L", 0, "C", 0);
  $oPdf->multicell(91, 4, trim($ed47_t_obs)==""?"Nenhum registro.":substr(trim($ed47_t_obs), 0, 600), 0, "J", 0, 0);
  $oPdf->setXY(104, $alt_obs);
  $oPdf->cell(3, 50, "", "R", 0, "C", 0);

  $oPdf->setXY(107, $alt_obs);
  $oPdf->cell(3, 50, "", "L", 0, "C", 0);
  $oPdf->multicell(91, 4, trim($ed47_v_contato)==""?"Nenhum registro.":substr(trim($ed47_v_contato), 0, 600), 0, "J", 0, 0);
  $oPdf->setXY(201, $alt_obs);
  $oPdf->cell(3, 50, "", "R", 1, "C", 0);

  $oPdf->cell(97, 2, "", "LBR", 0, "C", 0);
  $oPdf->cell(97, 2, "", "LBR", 1, "C", 0);
  $oPdf->cell(194, 2, "", 0, 1, "C", 0);
  
  if ($oGet->imp_matricula == "no" && $oGet->imp_historico == "no" && $oGet->imp_movimentacao == "no") {

    $oPdf->cell(60, 4, "Recebi e estou ciente das regras da escola.", 0, 0, "L", 0);
    $oPdf->cell(60, 4, "Assinatura do Responsável:", 0, 0, "R", 0);
    $oPdf->cell(74, 4, "..............................................................................".
                       "........................", 0, 1, "L", 0);
    $oPdf->cell(120, 4, "", 0, 0, "L", 0);
    $oPdf->cell(74, 4, trim($ed47_c_nomeresp)!=""?trim($ed47_c_nomeresp):"", 0, 1, "C", 0);
  }

 //////////////////////////////////////////////////////////
 //MATRÌCULAS
 //////////////////////////////////////////////////////////
  $iContGeral = 0;

  if ($oGet->imp_matricula == "yes") {

    $oPdf->addpage('P');
    $oPdf->ln(5);
    $altini = $oPdf->getY();
    $oPdf->setfont('arial', 'b', 7);
    $oPdf->cell(190, 4, "MATRÍCULAS", 1, 1, "C", 1);
    $oPdf->setfont('arial', '', 7);
    $oPdf->cell(190, 4, "", 0, 1, "C", 0);

    $sCamp  = "ed60_d_datasaida as datasaida,  ";
    $sCamp .= "case ";
    $sCamp .= " when ed60_c_situacao = 'TRANSFERIDO REDE' then ";
    $sCamp .= "  (select escoladestino.ed18_c_nome from transfescolarede ";
    $sCamp .= "    inner join atestvaga  on  atestvaga.ed102_i_codigo = transfescolarede.ed103_i_atestvaga ";
    $sCamp .= "    inner join escola  as escoladestino on  escoladestino.ed18_i_codigo = atestvaga.ed102_i_escola ";
    $sCamp .= "   where ed103_i_matricula = ed60_i_codigo order by ed103_d_data desc limit 1) ";
    $sCamp .= " when ed60_c_situacao = 'TRANSFERIDO FORA' then ";
    $sCamp .= "  (select escolaproc1.ed82_c_nome from transfescolafora ";
    $sCamp .= "   inner join escolaproc as escolaproc1 on  escolaproc1.ed82_i_codigo = transfescolafora.ed104_i_escoladestino ";
    $sCamp .= "    where ed104_i_matricula = ed60_i_codigo order by ed104_d_data desc limit 1) ";
    $sCamp .= " else null ";
    $sCamp .= "end as destinosaida,  ";
    $sCamp .= "matricula.*, turma.ed57_c_descr, turmaserieregimemat.ed220_i_procedimento, turma.ed57_c_medfreq,  ";
    $sCamp .= "calendario.ed52_c_descr, calendario.ed52_i_ano,  ";
    $sCamp .= "turma.ed57_i_escola,  ";
    $sCamp .= "case when turma.ed57_i_tipoturma = 2 then ";
    $sCamp .= " fc_nomeetapaturma(ed60_i_turma) else ";
    $sCamp .= " serie.ed11_c_descr ";
    $sCamp .= "end as ed11_c_descr,  ";
    $sCamp .= "escola.ed18_c_nome, turno.ed15_c_nome, aluno.ed47_v_nome, alunoprimat.ed76_i_codigo, alunoprimat.ed76_i_escola,  ";
    $sCamp .= "alunoprimat.ed76_d_data, alunoprimat.ed76_c_tipo,  ";
    $sCamp .= "case when ed76_c_tipo = 'M' ";
    $sCamp .= " then escolaprimat.ed18_c_nome else escolaproc.ed82_c_nome end as nomeescola ";

    $sSql1          = $oDaoMatricula->sql_query("",$sCamp,"ed60_d_datamatricula desc"," ed60_i_aluno = $ed47_i_codigo");
    $rsResult1      = $oDaoMatricula->sql_record($sSql1);
    $iLinhas11      = $oDaoMatricula->numrows;
    $iEscolaEmissao = db_getsession("DB_coddepto");
    if ($iLinhas11 > 0) {

      $iContador = 0;
      for ($iCont = 0; $iCont < $iLinhas11; $iCont++) {

        db_fieldsmemory($rsResult1, $iCont);
        db_putsession("DB_coddepto", $ed57_i_escola);
        if ($iContador == 3) {

          $oPdf->addpage('P');
          $oPdf->ln(5);
          $oPdf->setfont('arial', 'b', 7);
          $oPdf->cell(190, 4, "MATRÍCULAS", 1, 1, "C", 1);
          $oPdf->setfont('arial', '', 7);
          $oPdf->cell(190, 4, "", "LR", 1, "C", 0);
          $iContador = 0;
          $iContGeral = 0;

        }

        $iContador++;
        $iContGeral++;
        $oPdf->setfont('arial', '', 7);
        $oPdf->cell(35, 4, "Matrícula N°:", "LT", 0, "L", 1);
        $oPdf->setfont('arial', 'b', 7);
        $oPdf->cell(40, 4, $ed60_matricula, "T", 0, "L", 1);
        $oPdf->setfont('arial', '', 7);
        $oPdf->cell(30, 4, "Escola:", "T", 0, "L", 1);
        $oPdf->setfont('arial', 'b', 7);
        $oPdf->cell(85, 4, $ed18_c_nome, "RT", 1, "L", 1);

        $oPdf->setfont('arial', '', 7);
        $oPdf->cell(35, 4, "Data da Matrícula:", "L", 0, "L", 1);
        $oPdf->setfont('arial', 'b', 7);
        $oPdf->cell(40, 4, db_formatar($ed60_d_datamatricula, 'd'), 0, 0, "L", 1);
        $oPdf->setfont('arial', '', 7);
        $oPdf->cell(30, 4, "Situação:", 0, 0, "L", 1);
        $oPdf->setfont('arial', 'b', 7);

        if (trim($ed60_c_situacao) == "AVANÇADO" || trim($ed60_c_situacao) == "CLASSIFICADO") {
          $sitt = 'Aprovado através de progressão';
        } else {

          if ($ed60_c_concluida == "S") {
            $sitt = Situacao($ed60_c_situacao, $ed60_i_codigo)."(CONCLUÍDA)";
          } else {
            $sitt = Situacao($ed60_c_situacao, $ed60_i_codigo);

          }

        }
        $oPdf->cell(85, 4, $sitt, "R", 1, "L", 1);

        if (trim(Situacao($ed60_c_situacao, $ed60_i_codigo)) != "MATRICULADO"
           && trim(Situacao($ed60_c_situacao, $ed60_i_codigo)) != "REMATRICULADO") {

          $sCamp  = "ed60_d_datasaida as datasaida,  ";
          $sCamp .= " case ";
          $sCamp .= " when ed60_c_situacao = 'TRANSFERIDO REDE' then ";
          $sCamp .= "  (select escoladestino.ed18_c_nome from transfescolarede ";
          $sCamp .= "    inner join atestvaga  on  atestvaga.ed102_i_codigo = transfescolarede.ed103_i_atestvaga ";
          $sCamp .= "    inner join escola  as escoladestino on  escoladestino.ed18_i_codigo = atestvaga.ed102_i_escola ";
          $sCamp .= "   where ed103_i_matricula = ed60_i_codigo order by ed103_d_data desc limit 1) ";
          $sCamp .= " when ed60_c_situacao = 'TRANSFERIDO FORA' then ";
          $sCamp .= "  (select escolaproc.ed82_c_nome from transfescolafora ";
          $sCamp .= "    inner join escolaproc  on  escolaproc.ed82_i_codigo = transfescolafora.ed104_i_escoladestino ";
          $sCamp .= "   where ed104_i_matricula = ed60_i_codigo order by ed104_d_data desc limit 1) ";
          $sCamp .= " else null ";
          $sCamp .= " end as destinosaida ";

          $rsResult221 = $oDaoMatricula->sql_record($oDaoMatricula->sql_query("", $sCamp, "", " ed60_i_codigo = $ed60_i_codigo"));
          db_fieldsmemory($rsResult221, 0);
          $oPdf->setfont('arial', '', 7);
          $oPdf->cell(35, 4, "Data Saída:", "L", 0, "L", 1);
          $oPdf->setfont('arial', 'b', 7);
          $oPdf->cell(40, 4, db_formatar(@$datasaida, 'd'), 0, 0, "L", 1);
          $oPdf->setfont('arial', '', 7);
          $oPdf->cell(30, 4, "Destino Saída:", 0, 0, "L", 1);
          $oPdf->setfont('arial', 'b', 7);
          $oPdf->cell(85, 4, @$destinosaida, "R", 1, "L", 1);

        }

        $oPdf->setfont('arial', '', 7);
        $oPdf->cell(35, 4, "Nome da Turma:", "L", 0, "L", 1);
        $oPdf->setfont('arial', 'b', 7);
        $oPdf->cell(40, 4, $ed57_c_descr, 0, 0, "L", 1);
        $oPdf->setfont('arial', '', 7);
        $oPdf->cell(30, 4, "Etapa:", 0, 0, "L", 1);
        $oPdf->setfont('arial', 'b', 7);
        $oPdf->cell(85, 4, $ed11_c_descr, "R", 1, "L", 1);

        $oPdf->setfont('arial', '', 7);
        $oPdf->cell(35, 4, "Turno:", "L", 0, "L", 1);
        $oPdf->setfont('arial', 'b', 7);
        $oPdf->cell(40, 4, $ed15_c_nome, 0, 0, "L", 1);
        $oPdf->setfont('arial', '', 7);
        $oPdf->cell(30, 4, "Calendário:", 0, 0, "L", 1);
        $oPdf->setfont('arial', 'b', 7);
        $oPdf->cell(85, 4, $ed52_c_descr." / ".$ed52_i_ano, "R", 1, "L", 1);

        GradeAproveitamentoBOLETIM($ed60_i_codigo, 190, $oPdf, "", "P", 0, "S");

        $oPdf->cell(190, 4, "", "T", 1, "C", 0);
      }

    } else {

      $oPdf->setfont('arial', '', 7);
      $oPdf->cell(190, 4, "", "LRT", 1, "C", 0);
      $oPdf->cell(5, 4, "", "L", 0, "C", 0);
      $oPdf->cell(180, 4, "Nenhum registro.", 1, 0, "C", 0);
      $oPdf->cell(5, 4, "", "R", 1, "C", 0);
      $oPdf->cell(190, 4, "", "LRB", 1, "C", 0);

    }
    db_putsession("DB_coddepto", $iEscolaEmissao);
    if ($oGet->imp_historico == "no" && $oGet->imp_movimentacao == "no") {

      $oPdf->setfont('arial', '', 7);
      $oPdf->setY($oPdf->h - 35);
      $oPdf->cell(190, 2, "", 0, 1, "C", 0);
      $oPdf->cell(60, 4, "Recebi e estou ciente das regras da escola.", 0, 0, "L", 0);
      $oPdf->cell(60, 4, "Assinatura do Responsável:", 0, 0, "R", 0);
      $oPdf->cell(70, 4, ".................................................................................................", 0, 1, "L", 0);
      $oPdf->cell(120, 4, "", 0, 0, "L", 0);
      $oPdf->cell(70, 4, trim($ed47_c_nomeresp)!=""?trim($ed47_c_nomeresp):"", 0, 1, "C", 0);
      $oPdf->setfont('arial', 'b', 7);

    }
  } else {

    $oDaoMatricula->numrows = 0;
  }

 //////////////////////////////////////////////////////////
 //HISTÓRICOS
 //////////////////////////////////////////////////////////

  if ($oGet->imp_historico == "yes") {

    if ($oGet->imp_matricula == "no" || ($oDaoMatricula->numrows > 0 && $iContGeral > 0)) {
      $oPdf->addpage('P');
      $oPdf->ln(5);

    } else {
      $oPdf->cell(190, 4, "", 0, 1, "C", 0);
    }

    $oPdf->setfont('arial', 'b', 7);
    $oPdf->cell(190, 4, "HISTÓRICO", 1, 1, "C", 1);
    $oPdf->setfont('arial', '', 7);
    $oPdf->cell(190, 4, "", "LR", 1, "C", 0);
    $sCamposHist      = " ed29_c_descr, ed62_i_codigo, ed62_i_qtdch, ed11_c_descr, ed11_i_codigo, ed62_i_anoref, ";
    $sCamposHist     .= " ed62_i_periodoref, ed18_c_nome, ed11_i_sequencia, ed11_i_ensino, 'REDE' as tipo";
    $sCamposHist     .= " , ed62_c_situacao as situacao";
    $sCamposHistFora  = " ed29_c_descr, ed99_i_codigo, ed99_i_qtdch, ed11_c_descr, ed11_i_codigo, ed99_i_anoref, ";
    $sCamposHistFora .= " ed99_i_periodoref, ed82_c_nome, ed11_i_sequencia, ed11_i_ensino, 'FORA' as tipo";
    $sCamposHistFora .= " , ed99_c_situacao as situacao";
    $sSql3            = " SELECT $sCamposHist ";
    $sSql3           .= "   FROM historicomps ";
    $sSql3           .= "     inner join serie on ed11_i_codigo = ed62_i_serie ";
    $sSql3           .= "     inner join historico on ed61_i_codigo = ed62_i_historico ";
    $sSql3           .= "     inner join cursoedu on ed29_i_codigo = ed61_i_curso ";
    $sSql3           .= "     inner join escola on ed18_i_codigo = ed62_i_escola ";
    $sSql3           .= "   WHERE ed61_i_aluno = $ed47_i_codigo ";
    $sSql3           .= "   UNION ";
    $sSql3           .= " SELECT $sCamposHistFora";
    $sSql3           .= "   FROM historicompsfora ";
    $sSql3           .= "     inner join serie on ed11_i_codigo = ed99_i_serie ";
    $sSql3           .= "     inner join historico on ed61_i_codigo = ed99_i_historico ";
    $sSql3           .= "     inner join cursoedu on ed29_i_codigo = ed61_i_curso ";
    $sSql3           .= "     inner join escolaproc on ed82_i_codigo = ed99_i_escolaproc ";
    $sSql3           .= "   WHERE ed61_i_aluno = $ed47_i_codigo ";
    $sSql3           .= " ORDER BY ed11_i_ensino, ed11_i_sequencia DESC ";
    $rsResult3        = db_query($sSql3);
    $iLinhas3         = pg_num_rows($rsResult3);
    $iContador        = 0;

    if ($iLinhas3 > 0) {

      $sPrimeiro = "";
      for ($t = 0; $t < $iLinhas3; $t++) {

        db_fieldsmemory($rsResult3, $t);
        if ($iContador == 3) {

          $oPdf->cell(190, 1, "", "LRB", 1, "C", 0);
          $oPdf->addpage('P');
          $oPdf->ln(5);
          $oPdf->setfont('arial', 'b', 7);
          $oPdf->cell(190, 4, "HISTÓRICO", 1, 1, "C", 1);
          $oPdf->setfont('arial', '', 7);
          $oPdf->cell(190, 4, "", "LR", 1, "C", 0);
          $oPdf->cell(5, 4, "", "L", 0, "L", 0);
          $oPdf->cell(180, 4, $ed29_c_descr, 1, 0, "L", 1);
          $oPdf->cell(5, 4, "", "R", 1, "L", 0);
          $oPdf->cell(190, 4, "", "LR", 1, "C", 0);
          
          $iContador  = 0;
          $iContGeral = 0;
        }

        $iContador++;
        $iContGeral++;

        if ($sPrimeiro != $ed29_c_descr) {

          $oPdf->cell(5, 4, "", "L", 0, "L", 0);
          $oPdf->cell(180, 4, $ed29_c_descr, 1, 0, "L", 1);
          $oPdf->cell(5, 4, "", "R", 1, "L", 0);
          $oPdf->cell(190, 4, "", "LR", 1, "C", 0);
          $sPrimeiro = $ed29_c_descr;
        }

        $alt_geral = $oPdf->getY();
        $oPdf->setfont('arial', '', 7);
        
        if ($situacao == 'TRANSFERIDO') {
          $oPdf->cell(5, 12, "", "L", 0, "C", 0);
        } else {
          $oPdf->cell(5, 16, "", "LR", 0, "C", 0);
        }
        $oPdf->cell(13, 4, "Etapa:", "LT", 0, "L", 1);
        
        $oPdf->setfont('arial', 'b', 7);
        $oPdf->cell(62, 4, $ed11_c_descr, "T", 0, "L", 1);
        
        $oPdf->setfont('arial', '', 7);
        $oPdf->cell(7, 4, "Ano:", "T", 0, "L", 1);
        
        $oPdf->setfont('arial', 'b', 7);
        $oPdf->cell(8, 4, $ed62_i_anoref, "TR", 1, "L", 1);
        
        //$oPdf->setY($alt_geral);
        $oPdf->setX(15);
        $oPdf->setfont('arial', '', 7);
        $oPdf->cell(13, 4, 'Situação:', "LTB", 0, "L", 1);
        
        $oPdf->setfont('arial', 'b', 7);
        $oPdf->cell(77, 4, $situacao, "TRB", 1, "L", 1);
        
        $oPdf->setY($alt_geral);
        $oPdf->setX(105);
        $oPdf->setfont('arial', '', 7);
        $oPdf->cell(40, 4, "Escola:", "T", 2, "L", 0);
        $oPdf->cell(40, 4, "Carga Horária:", "LB", 2, "L", 0);
        $oPdf->setY($alt_geral);
        $oPdf->setX(125);
        $oPdf->setfont('arial', 'b', 7);
        $oPdf->cell(70, 4, substr($ed18_c_nome, 0, 45), "RT", 2, "L", 0);
        $oPdf->cell(70, 4, $ed62_i_qtdch , "RB", 2, "L", 0);
        $oPdf->setY($alt_geral);
        $oPdf->setX(195);
        $oPdf->cell(5, 8, "", "LR", 1, "C", 0);
        
        if ($situacao != 'TRANSFERIDO') {

          if ($tipo == "REDE") {
  
            $sCampos    = "ed65_i_codigo, ed232_c_descr, ed65_c_situacao, ed65_i_ordenacao, ";
            $sCampos   .= "case when ed65_c_situacao!='CONCLUÍDO' then '' else ed65_t_resultobtido end as ed65_t_resultobtido, ";
            $sCampos   .= "ed65_c_resultadofinal, ed65_i_qtdch, ed65_c_tiporesultado, ed65_i_historicomps, ed29_c_descr, ";
            $sCampos   .= "ed65_lancamentoautomatico";
            $sSql       = $oDaoHistmpsdisc->sql_query("",  $sCampos,  "ed65_i_ordenacao",  " ed65_i_historicomps = $ed62_i_codigo");
            $rsResult11 = $oDaoHistmpsdisc->sql_record($sSql);
  
            if ($rsResult11) {
  
              $oPdf->cell(5, 4, "", "L", 0, "C", 0);
              $oPdf->cell(55, 4, "Disciplina", 1, 0, "C", 0);
              $oPdf->cell(30, 4, "Situação", 1, 0, "C", 0);
              $oPdf->cell(30, 4, "Aproveitamento", 1, 0, "C", 0);
              $oPdf->cell(25, 4, "RF", 1, 0, "C", 0);
              $oPdf->cell(10, 4, "CH", 1, 0, "C", 0);
              $oPdf->cell(30, 4, "TIPO RESULTADO", 1, 0, "C", 0);
              $oPdf->cell(5, 4, "", "R", 1, "C", 0);
              $oPdf->setfont('arial', '', 7);
  
              if ($oDaoHistmpsdisc->numrows > 0) {
  
                $sCor1 = "#f3f3f3";
                $sCor2 = "#DBDBDB";
                $sCor = "";
                $lMostrarLegenda = false;
                for ($iCont = 0; $iCont < $oDaoHistmpsdisc->numrows; $iCont++) {
  
                  db_fieldsmemory($rsResult11, $iCont);
  
                  if ($sCor == $sCor1) {
                    $sCor = $sCor2;
                  } else {
                    $sCor = $sCor1;
                  }
  
                  if (trim($ed65_c_situacao) == "AMPARADO") {
                    $ed65_t_resultobtido = "";
                  } else if ($ed65_c_tiporesultado == 'N') {
                    $ed65_t_resultobtido = $ed65_t_resultobtido;
                  }
  
                  if ($ed65_c_tiporesultado == "N") {
                    $ed65_c_tiporesultado = "NOTA";
                  } else if ($ed65_c_tiporesultado == "P") {
                    $ed65_c_tiporesultado = "PARECER";
                  } else if ($ed65_c_tiporesultado == "A") {
                    $ed65_c_tiporesultado = "AVANÇO";
                  } else if ($ed65_c_tiporesultado == "C") {
                    $ed65_c_tiporesultado = "CLASSIF";
                  }
  
                  $iAlt = ceil(strlen($ed232_c_descr)/45)*4;
                  $oPdf->cell(5, $iAlt, "", "L", 0, "C", 0);
  
                  $aDados = array();
  
                  $aDados[0] = $ed232_c_descr;
                  $aDados[1] = $ed65_c_situacao;
                  $aDados[2] = $ed65_t_resultobtido;
                  $aDados[3] = "";
  
                  $lProgressaoParcial = false;
                  if ($ed65_c_resultadofinal == "D") {
  
                    $lProgressaoParcial    = true;
                    $lMostrarLegenda       = true;
                    $ed65_c_resultadofinal = "A";
                  }
                  if (!empty($ed11_i_ensino) && ($ed65_c_resultadofinal == "A" || $ed65_c_resultadofinal == "R")) {
  
                    $aDadosTermo = DBEducacaoTermo::getTermoEncerramento($ed11_i_ensino, $ed65_c_resultadofinal);
                    if (isset($aDadosTermo[0])) {
  
                      $aDados[3] = $aDadosTermo[0]->sDescricao;
                      if ($lProgressaoParcial) {
                        $aDados[3] .= "*";
                      }
                    }
                  }
  
                  if ($ed65_lancamentoautomatico == 'f') {
                    $ed65_i_qtdch = db_formatar($ed65_i_qtdch, 'p');
                  }
  
                  $aDados[4] = $ed65_i_qtdch == "" ? 0 : $ed65_i_qtdch;
                  $aDados[5] = trim($ed65_c_tiporesultado);
  
                  $oPdf->SetWidths(array(55, 30, 30, 25, 10, 30));
                  $oPdf->SetAligns(array("L", "C", "C", "C", "C", "C"));
                  $alturaRow = $oPdf->h - 32;
                  $oPdf->Row_multicell($aDados,  3.8,  true,  4,  0,  false,  true,  2,  $alturaRow,  0);
  
                  $oPdf->setXY($oPdf->getX()+185,  $oPdf->getY() - $iAlt);
  
                  $oPdf->cell(5, $iAlt, "", "R", 1, "C", 0);
  
                }
                $sTextoAjuda = '';
                if ($lMostrarLegenda) {
                  $sTextoAjuda = "* - Aprovado com Progressão Parcial /Dependência";
                }
                $oPdf->cell(190, 4, $sTextoAjuda, "LR", 1, "L", 0);
  
              } else {
  
                $oPdf->cell(5, 4, "", "L", 0, "C", 0);
                $oPdf->cell(180, 4, "Nenhuma disciplina cadastrada para esta etapa.", 1, 0, "C", 0);
                $oPdf->cell(5, 4, "", "R", 1, "C", 0);
  
              }
  
            }
  
          } else {
  
            $sCampos  = " ed100_i_codigo, ed232_c_descr, ed100_c_situacao, ed100_i_ordenacao,  ";
            $sCampos .= " case when ed100_c_situacao!='CONCLUÍDO' OR ed100_t_resultobtido = '' then '' else";
            $sCampos .= "  ed100_t_resultobtido end as ed100_t_resultobtido, ";
            $sCampos .= " ed100_c_resultadofinal, ed100_i_qtdch, ed100_c_tiporesultado, ";
            $sCampos .= " ed100_i_historicompsfora, ed29_c_descr ";
            $sSql22     = $oDaoHistmpsdiscfora->sql_query("", $sCampos, "ed100_i_ordenacao",
                                                          " ed100_i_historicompsfora = $ed62_i_codigo"
                                                         );
            $rsResult22 = $oDaoHistmpsdiscfora->sql_record($sSql22);
  
            if ($rsResult22) {
  
              $oPdf->cell(5, 4, "", "L", 0, "C", 0);
              $oPdf->cell(55, 4, "Disciplina", 1, 0, "C", 0);
              $oPdf->cell(30, 4, "Situação", 1, 0, "C", 0);
              $oPdf->cell(30, 4, "Aproveitamento", 1, 0, "C", 0);
              $oPdf->cell(25, 4, "RF", 1, 0, "C", 0);
              $oPdf->cell(10, 4, "CH", 1, 0, "C", 0);
              $oPdf->cell(30, 4, "TIPO RESULTADO", 1, 0, "C", 0);
              $oPdf->cell(5, 4, "", "R", 1, "C", 0);
              $oPdf->setfont('arial', '', 7);
  
              $lMostrarLegenda = false;
              if ($oDaoHistmpsdiscfora->numrows > 0) {
               for ($iCont = 0; $iCont < $oDaoHistmpsdiscfora->numrows; $iCont++) {
  
                 db_fieldsmemory($rsResult22, $iCont);
                 if (trim($ed100_c_situacao) == "AMPARADO") {
                   $ed100_t_resultobtido = "&nbsp;";
                 } else if ($ed100_c_tiporesultado == 'N') {
                   $ed100_t_resultobtido = $ed100_t_resultobtido;
                 }
  
                 $iAlt = ceil(strlen($ed232_c_descr)/45)*4;
                 $oPdf->cell(5, $iAlt, "", "L", 0, "C", 0);
  
                 $aDados = array();
  
                 $aDados[0] = $ed232_c_descr;
                 $aDados[1] = $ed100_c_situacao;
                 $aDados[2] = $ed100_t_resultobtido;
                 $aDados[3] = "";
                 if ($ed100_c_resultadofinal == "D") {
  
                   $lProgressaoParcial    = true;
                   $lMostrarLegenda       = true;
                   $ed65_c_resultadofinal = "A";
                 }
                 if (!empty($ed11_i_ensino) && ($ed100_c_resultadofinal == "A" || $ed100_c_resultadofinal == "R")) {
  
                   $aDadosTermo = DBEducacaoTermo::getTermoEncerramento($ed11_i_ensino, $ed100_c_resultadofinal);
                   if (isset($aDadosTermo[0])) {
  
                     $aDados[3] = $aDadosTermo[0]->sDescricao;
                     if ($lProgressaoParcial) {
                       $aDados[3] .= "*";
                     }
                   }
                 }
                 $aDados[4] = $ed100_i_qtdch == "" ? 0 : $ed100_i_qtdch;
                 $aDados[5] = trim($ed100_c_tiporesultado);
  
                 $oPdf->SetWidths(array(55, 30, 30, 25, 10, 30));
                 $oPdf->SetAligns(array("L", "C", "C", "C", "C", "C"));
                 $alturaRow = $oPdf->h - 32;
                 $oPdf->Row_multicell($aDados,  3.8,  true,  4,  0,  false,  true,  2,  $alturaRow,  0);
  
                 $oPdf->setXY($oPdf->getX()+185,  $oPdf->getY() - $iAlt);
                 $oPdf->cell(5, $iAlt, "", "R", 1, "L", 0);
  
               }
               $sTextoAjuda = '';
               if ($lMostrarLegenda) {
                 $sTextoAjuda = "* - Aprovado com Progressão Parcial /Dependência";
               }
               $oPdf->cell(190, 4, $sTextoAjuda, "LR", 1, "C", 0);
  
              } else {
  
                $oPdf->cell(5, 4, "", "L", 0, "C", 0);
                $oPdf->cell(180, 4, "Nenhuma disciplina cadastrada para esta etapa.", 1, 0, "C", 0);
                $oPdf->cell(5, 4, "", "R", 1, "C", 0);
  
              }
            }
          }
        }
      }
      
      $oPdf->cell(190, 4, "", "LRB", 1, "C", 0);
    } else {

      $oPdf->cell(5, 4, "", "L", 0, "C", 0);
      $oPdf->setfont('arial', '', 7);
      $oPdf->cell(180, 4, "Nenhum registro.", 1, 0, "C", 0);
      $oPdf->cell(5, 4, "", "R", 1, "C", 0);
      $oPdf->cell(190, 4, "", "LR", 1, "C", 0);
      $oPdf->cell(190, 1, "", "LRB", 1, "C", 0);

    }
    if ($oGet->imp_movimentacao == "no") {

      $oPdf->setfont('arial', '', 7);
      $oPdf->setY($oPdf->h - 35);
      $oPdf->cell(190, 2, "", 0, 1, "C", 0);
      $oPdf->cell(60, 4, "Recebi e estou ciente das regras da escola.", 0, 0, "L", 0);
      $oPdf->cell(60, 4, "Assinatura do Responsável:", 0, 0, "R", 0);
      $oPdf->cell(70, 4, "..................................................................................................", 0, 1, "L", 0);
      $oPdf->cell(120, 4, "", 0, 0, "L", 0);
      $oPdf->cell(70, 4, trim($ed47_c_nomeresp)!=""?trim($ed47_c_nomeresp):"", 0, 1, "C", 0);

    }

  } else {
    $iLinhas3 = 0;
  }

 //////////////////////////////////////////////////////////
 //MOVIMENTAÇÃO ESCOLAR
 //////////////////////////////////////////////////////////

  if ($oGet->imp_movimentacao == "yes") {

    if (($oDaoMatricula->numrows > 0 && $iContGeral > 0)
         || ($iLinhas3 > 0 && $iContGeral > 1)
         || ($oGet->imp_matricula == "no" && $oGet->imp_historico == "no")) {

      $oPdf->addpage('P');
      $oPdf->ln(5);

    } else {
      $oPdf->cell(190, 4, "", 0, 1, "C", 0);
    }

    $oPdf->setfont('arial', 'b', 7);
    $oPdf->cell(190, 4, "MOVIMENTAÇÃO ESCOLAR", 1, 1, "C", 1);
    $oPdf->setfont('arial', '', 7);
    $oPdf->cell(190, 4, "", "LR", 1, "C", 0);
    $iAltInicio = $oPdf->getY();
    $aArrayMov  = array();

    $sCampos    = "ed229_i_codigo, ed229_d_dataevento, ed18_i_codigo, ed18_c_nome, ";
    $sCampos   .= "ed60_i_codigo, ed57_c_descr, ed52_i_ano, ed60_matricula,";
    $sCampos   .= "ed11_c_descr, ed229_c_procedimento, ed229_t_descr";
    $sOrder     = "ed229_d_dataevento , ed229_i_codigo ";
    $sWhere     = " ed60_i_aluno = $ed47_i_codigo AND ";
    $sWhere    .= "ed229_c_procedimento NOT LIKE  'CANCELAR ENCERRAMENTO%' AND ";
    $sWhere    .= "ed229_c_procedimento NOT LIKE  'ENCERRAR%'";
    $rsResult   = $oDaoMatriculamov->sql_record($oDaoMatriculamov->sql_query("", $sCampos, $sOrder, $sWhere));

    if ($oDaoMatriculamov->numrows > 0) {

      for ($iCont = 0; $iCont < $oDaoMatriculamov->numrows; $iCont++) {

        db_fieldsmemory($rsResult, $iCont);
        $aArrayMov[]  = str_replace("-", "", $ed229_d_dataevento).$ed229_i_codigo;
        $iContador    = count($aArrayMov)-1;
        $aArrayMov[$iContador] .= "|".db_formatar($ed229_d_dataevento, 'd')."#".$ed18_i_codigo." - ".substr($ed18_c_nome, 0, 30);
        $aArrayMov[$iContador] .= "#".$ed60_matricula."#".$ed57_c_descr."#".$ed52_i_ano."#".$ed11_c_descr;
        $aArrayMov[$iContador] .="#".substr($ed229_c_procedimento, 0, 35)."#".$ed229_t_descr;

      }

    }

    $sCamposMatri  = "ed229_i_codigo, ed229_d_dataevento, ed18_i_codigo, ed18_c_nome, ed60_i_codigo, ed57_c_descr, ";
    $sCamposMatri .= "ed52_i_ano, ed11_c_descr, ed229_c_procedimento, ed229_t_descr, ed60_matricula";
    $sOrderMatri   = "ed229_d_dataevento DESC, ed229_i_codigo DESC LIMIT 1";
    $sWhereMatri   = " ed60_i_aluno = $ed47_i_codigo AND ed229_c_procedimento LIKE  'CANCELAR ENCERRAMENTO%'";
    $sSql1         = $oDaoMatriculamov->sql_query("", $sCamposMatri, $sOrderMatri, $sWhereMatri);
    $rsResult1     = $oDaoMatriculamov->sql_record($sSql1);

    if ($oDaoMatriculamov->numrows > 0) {

      db_fieldsmemory($rsResult1, 0);
      $aArrayMov[] = str_replace("-", "", $ed229_d_dataevento).$ed229_i_codigo."|".db_formatar($ed229_d_dataevento, 'd');
      $iContador    = count($aArrayMov)-1;
      $aArrayMov[$iContador] .="#".$ed18_i_codigo." - ".substr($ed18_c_nome, 0, 30)."#".$ed60_matricula."#".$ed57_c_descr;
      $aArrayMov[$iContador] .= "#".$ed52_i_ano."#".$ed11_c_descr."#".substr($ed229_c_procedimento, 0, 35)."#".$ed229_t_descr;

    }

    $sCamposMat  = "ed229_i_codigo, ed229_d_dataevento, ed18_i_codigo, ed18_c_nome, ed60_i_codigo, ed57_c_descr, ";
    $sCamposMat .= "ed52_i_ano, ed11_c_descr, ed229_c_procedimento, ed229_t_descr, ed60_matricula";
    $sOrderMat   = "ed229_d_dataevento DESC, ed229_i_codigo DESC LIMIT 1";
    $sWhereMat   = " ed60_i_aluno = $ed47_i_codigo AND ed229_c_procedimento LIKE  'ENCERRAR%'";
    $rsResult2     = $oDaoMatriculamov->sql_record($oDaoMatriculamov->sql_query("", $sCamposMat, $sOrderMat, $sWhereMat));

    if ($oDaoMatriculamov->numrows > 0) {

       db_fieldsmemory($rsResult2, 0);
       $aArrayMov[]  = str_replace("-", "", $ed229_d_dataevento).$ed229_i_codigo."|".db_formatar($ed229_d_dataevento, 'd');
       $iContador    = count($aArrayMov)-1;
       $aArrayMov[$iContador] .= "#".$ed18_i_codigo." - ".substr($ed18_c_nome, 0, 30)."#".$ed60_matricula."#".$ed57_c_descr;
       $aArrayMov[$iContador] .= "#".$ed52_i_ano."#".$ed11_c_descr."#".substr($ed229_c_procedimento, 0, 35)."#".$ed229_t_descr;

    }

    $aArrayOrdem = SORT_ASC;
    array_multisort($aArrayMov, $aArrayOrdem);

    if (count($aArrayMov) > 0) {

      $oPdf->setfillcolor(210);
      $oPdf->setfont('arial', 'b', 7);
      $oPdf->cell(5, 4, "", "L", 0, "C", 0);
      $oPdf->cell(15, 4, "Data", 1, 0, "C", 1);
      $oPdf->cell(55, 4, "Escola", 1, 0, "C", 1);
      $oPdf->cell(10, 4, "Matr.", 1, 0, "C", 1);
      $oPdf->cell(20, 4, "Turma", 1, 0, "C", 1);
      $oPdf->cell(10, 4, "Ano", 1, 0, "C", 1);
      $oPdf->cell(15, 4, "Etapa", 1, 0, "C", 1);
      $oPdf->cell(55, 4, "Procedimento", 1, 0, "C", 1);
      $oPdf->cell(5, 4, "", "R", 1, "C", 0);
      $oPdf->setfillcolor(230);

      for($iCont = 0; $iCont < count($aArrayMov); $iCont++){

        $aArrayMov1 = explode("|", $aArrayMov[$iCont]);
        $aArrayMov2 = explode("#", $aArrayMov1[1]);
        $oPdf->setfont('arial', 'b', 7);
        $oPdf->cell(5, 4, "", "L", 0, "C", 0);
        $oPdf->cell(15, 4, $aArrayMov2[0], 1, 0, "C", 1);
        $oPdf->cell(55, 4, $aArrayMov2[1], 1, 0, "L", 1);
        $oPdf->cell(10, 4, $aArrayMov2[2], 1, 0, "C", 1);
        $oPdf->cell(20, 4, $aArrayMov2[3], 1, 0, "C", 1);
        $oPdf->cell(10, 4, $aArrayMov2[4], 1, 0, "C", 1);
        $oPdf->cell(15, 4, $aArrayMov2[5], 1, 0, "C", 1);
        $oPdf->cell(55, 4, substr($aArrayMov2[6], 0, 33), 1, 0, "L", 1);
        $oPdf->setfont('arial', '', 7);
        $oPdf->cell(5, 4, "", "R", 1, "C", 0);
        $iAltDescrIni = $oPdf->getY();
        $oPdf->cell(190, 1, "", "LR", 1, "C", 0);
        $oPdf->cell(5, 4, "", "L", 0, "C", 0);
        $oPdf->cell(15, 4, "", 0, 0, "C", 0);
        $oPdf->multicell(165, 4, $aArrayMov2[7], 0, "L", 0, 0);
        $oPdf->cell(190, 1, "", "LR", 1, "C", 0);
        $iAltDescrFim = $oPdf->getY();
        $oPdf->rect(15, $iAltDescrIni, 180, $iAltDescrFim-$iAltDescrIni, 'D');//retangulo
        $oPdf->cell(190, 2, "", "LR", 1, "C", 0);

      }

      $oPdf->cell(190, 4, "", "LR", 1, "C", 0);
      $oPdf->cell(190, 1, "", "LRB", 1, "C", 0);
      $iAltFinal = $oPdf->getY();
      $oPdf->line(200, $iAltInicio, 200, $iAltFinal);
      $oPdf->line(10, $iAltInicio, 10, $iAltFinal);

    } else {

      $oPdf->cell(5, 4, "", "L", 0, "C", 0);
      $oPdf->setfont('arial', '', 7);
      $oPdf->cell(180, 4, "Nenhum registro.", 1, 0, "C", 0);
      $oPdf->cell(5, 4, "", "R", 1, "C", 0);
      $oPdf->cell(190, 4, "", "LR", 1, "C", 0);
      $oPdf->cell(190, 1, "", "LRB", 1, "C", 0);
      $oPdf->setfont('arial', 'b', 7);

    }

    $oPdf->setfont('arial', '', 7);
    $oPdf->setY($oPdf->h - 35);
    $oPdf->cell(190, 2, "", 0, 1, "C", 0);
    $oPdf->cell(60, 4, "Recebi e estou ciente das regras da escola.", 0, 0, "L", 0);
    $oPdf->cell(60, 4, "Assinatura do Responsável:", 0, 0, "R", 0);
    $oPdf->cell(70, 4, "......................................................................".
                       "...........................", 0, 1, "L", 0);
    $oPdf->cell(120, 4, "", 0, 0, "L", 0);
    $oPdf->cell(70, 4, trim($ed47_c_nomeresp) != "" ? trim($ed47_c_nomeresp) : "", 0, 1, "C", 0);
  }

}

$oPdf->Output();

?>