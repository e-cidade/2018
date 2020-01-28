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

include ("fpdf151/pdf.php");
include ("libs/db_sql.php");
//include("libs/db_sessoes.php");
include("dbforms/db_funcoes.php");
include ("libs/db_usuariosonline.php");
include "classes/db_far_retirada_classe.php";
include "classes/db_far_farmacia_classe.php";
include('libs/db_stdlibwebseller.php');
parse_str ( $HTTP_SERVER_VARS ['QUERY_STRING'] );
db_postmemory ( $HTTP_POST_VARS );
$departamento = db_getsession ( "DB_coddepto" );
$clrotulo = new rotulocampo ( );
$clfar_retirada = new cl_far_retirada ( );
$clfar_farmacia = new cl_far_farmacia ( );

//Selecionando dados da farmacia
$sql=$clfar_farmacia->sql_query("","*","","fa13_i_departamento=$departamento");
$result_farmacia=$clfar_farmacia->sql_record($sql);
if ($clfar_farmacia->numrows == 0) {
	echo "<table width='100%'>
        <tr>
         <td align='center'><font color='#FF0000' face='arial'><b>Dados da farmácia não encontrados verifique os cadastros!<br><input type='button' value='Fechar' onclick='window.close()'></b></font></td>
        </tr>
       </table>";
	exit ();
}

//monta periodo
$x   = data_farmacia($ano,$periodo);
$ini = db_formatar($x[0],'d');
$fim = db_formatar($x[1],'d');
$inisql = $x[0];
$fimsql = $x[1];
//die("Datas - $ini á $fim ");

$sql = "select distinct
          far_codigodcb.fa28_c_numero          as numero_dcb,
          far_tipodc.fa27_c_denominacao        as descr_dcb,
          matmater.m60_descr                   as nome_medicamento,
          far_concentracao.fa30_c_concentracao as apreset_concentra,
          matfabricante.m76_nome               as nome_empresa,
          cgm.z01_cgccpf                       as cnpj,
          empnota.e69_numero                   as numero_nota,
          matestoqueinimei.m82_quant           as quant
        from matestoqueini
          inner join matestoquetipo        on  m81_codtipo           = matestoqueini.m80_codtipo
          inner join matestoqueinimei      on  m82_matestoqueini     = matestoqueini.m80_codigo
          inner join matestoqueitem        on  m71_codlanc           = matestoqueinimei.m82_matestoqueitem
          inner join matestoque            on  m70_codigo            = matestoqueitem.m71_codmatestoque
          inner join matmater              on  m60_codmater          = matestoque.m70_codmatmater
          left  join far_matersaude        on  fa01_i_codmater       = matestoqueitem.m71_codmatestoque
          left  join far_concentracaomed   on  fa37_i_codigo         = far_matersaude.fa01_i_concentracaomed
          left  join far_concentracao      on  fa30_i_codigo         = far_concentracaomed.fa37_i_concentracao
          left  join far_codigodcb         on  fa28_i_medanvisa      = far_matersaude.fa01_i_medanvisa
          left  join far_tipodc            on  fa27_i_codigo         = far_codigodcb.fa28_i_tipodcb
          left  join matestoqueitemfabric  on  m78_matestoqueitem    = matestoqueitem.m71_codmatestoque
          left  join matfabricante         on  m76_sequencial        = matestoqueitemfabric.m78_matfabricante
          left  join cgm                   on  z01_numcgm            = matfabricante.m76_numcgm
          left  join matestoqueitemoc      on  m73_codmatestoqueitem = matestoqueitem.m71_codlanc
          left  join matordemitem          on  m52_codlanc           = matestoqueitemoc.m73_codmatordemitem
          left  join matordem              on  m51_codordem          = matordemitem.m52_codordem
          left  join empnotaord            on  m72_codordem          = matordem.m51_codordem
          left  join empnota               on  e69_codnota           = empnotaord.m72_codnota
       where
          m80_data between '$inisql' and '$fimsql' 
          and m81_entrada='t'
          and m80_coddepto=$departamento";
$result = pg_query ( $sql );
//die("SQL = [ $sql ]");
$linhas=pg_num_rows ( $result );
if ($linhas == 0) {
	echo "<table width='100%'>
        <tr>
         <td align='center'><font color='#FF0000' face='arial'><b>Nenhum Registro para o Relatório<br><input type='button' value='Fechar' onclick='window.close()'></b></font></td>
        </tr>
       </table>";
	exit ();
}
db_fieldsmemory ( $result_farmacia, 0 );

$pdf = new PDF ( );
$pdf->Open ();
$pdf->AliasNbPages ();
$head1 = "BALANÇO DAS AQUISIÇÕES DE MEDICAMENTOS";
$head2 = "";

$pdf->ln ( 5 );
$pdf->addpage ( 'L' );
$total = 0;
$cont = 0;
$pdf->setfont ( 'arial', 'b', 8 );
$pdf->cell ( 280, 4, "BALANÇO DAS AQUISIÇÕES DE MEDICAMENTOS", 0, 1, "C", 0 );
$pdf->setfont ( 'arial', 'b', 6 );
$pdf->cell ( 200, 4, "C.N.P.J : $fa13_c_cnpj", 0, 0, "L", 0 );
$pdf->cell ( 200, 4, "Exercício : $ano", 0, 1, "L", 0 );
$pdf->cell ( 200, 4, "N° da licença de funcionamento : $fa13_c_numlicenca", 0, 0, "L", 0 );

$pdf->cell ( 20, 4, "Periocidade Trimestral : $ini á $fim", 0, 1, "L", 0 );

$pdf->cell ( 30, 4, "N° do Código na DCB", 1, 0, "L", 0 );
$pdf->cell ( 30, 4, "Descriminação DCB", 1, 0, "L", 0 );
$pdf->cell ( 30, 4, "Nome do Medicamento", 1, 0, "L", 0 );
$pdf->cell ( 35, 4, "Apresentação e Concentração", 1, 0, "L", 0 );
$pdf->cell ( 35, 4, "Nome da Empresa Fornecedora", 1, 0, "L", 0 );
$pdf->cell ( 30, 4, "C.N.P.J", 1, 0, "L", 0 );
$pdf->cell ( 30, 4, "N° da Nota Fiscal", 1, 0, "L", 0 );
$pdf->cell ( 30, 4, "Quantidade Adquirida", 1, 1, "L", 0 );
for($i = 0; $i <= $linhas; $i ++) {
	db_fieldsmemory ( $result, $i );
	$pdf->cell ( 30, 4, "$numero_dcb", 1, 0, "L", 0 );
	$pdf->cell ( 30, 4, "$nome_dcb", 1, 0, "L", 0 );
	$pdf->cell ( 30, 4, "".substr($nome_medicamento,0,20), 1, 0, "L", 0 );
	$pdf->cell ( 35, 4, "$apreset_concentra", 1, 0, "L", 0 );
	$pdf->cell ( 35, 4, "$nome_empresa", 1, 0, "L", 0 );
	$pdf->cell ( 30, 4, "$cnpj", 1, 0, "L", 0 );
	$pdf->cell ( 30, 4, "$numero_nota", 1, 0, "L", 0 );
	$pdf->cell ( 30, 4, "$quant", 1, 1, "L", 0 );
}
$pdf->cell ( 30, 4, "Assinatura do Responsável Técnico_________________________________________________________________________________________", 0, 0, "L", 0 );

$pdf->Output ();
?>