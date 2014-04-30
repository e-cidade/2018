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

include("fpdf151/scpdf.php");
include("fpdf151/impcarne.php");
include("classes/db_tabativ_classe.php");
include("classes/db_issprocesso_classe.php");
include("classes/db_ativprinc_classe.php");
include("classes/db_db_config_classe.php");
db_postmemory($HTTP_SERVER_VARS);
$sql = "select * from empresa where q02_inscr = $inscricao";
$result = pg_exec($sql);
db_fieldsmemory($result,0);
$cltabativ = new cl_tabativ;
$clativprinc = new cl_ativprinc;
$cldb_config = new cl_db_config;
$clissprocesso = new cl_issprocesso;
$pdf = new scpdf();
$pdf->Open();
$pdf1 = new db_impcarne($pdf,'20');
//$pdf1->modelo = 20;
$pdf1->objpdf->AddPage();
$pdf1->objpdf->SetTextColor(0,0,0);

//$resul = $cldb_config->sql_record($cldb_config->sql_query(db_getsession("DB_instit"),"nomeinst as prefeitura, munic"));
$result = $clsaniatividade->sql_record($clsaniatividade->sql_query("","","*",""," y83_codsani = $y80_codsani"));
db_fieldsmemory($resul,0);//pega o dados da prefa
$munic = strtoupper($munic);

//global $db02_texto;

if ( $q07_perman == 'f' ){

   $pdf1->tipoalvara = 'LICENÇA PROVISÓRIA DE ATIVIDADE';

   $sqlparag = "select db02_texto 
		from db_documento 
		inner join db_docparag on db03_docum = db04_docum 
		inner join db_paragrafo on db04_idparag = db02_idparag 
		where db03_docum = 26 and db02_descr ilike '%Paragrafo 1 Alvara Provisorio%' and db03_instit = " . db_getsession("DB_instit");
   $resparag = pg_query($sqlparag);
   if ( pg_numrows($resparag) == 0 ) {
     db_redireciona('db_erros.php?fechar=true&db_erro=Configure o documento 26 com os paragrafos do alvara!');
     exit; 
   }

   db_fieldsmemory($resparag,0);

   $pdf1->texto      = $db02_texto;
// 'A PREFEITURA MUNICIPAL DE ' . $munic . ', através do competente setor, de acordo com a Lei Municipal n'.chr(186).' 2310/97, concede licença provisória para localização e/ou funcionamento de atividades neste Município, ao contribuinte abaixo identificado.';
   $pdf1->obs        = 'Observação: A presente licença é de caráter provisório, com o prazo de vencimento de 01 ano, improrrogável.';

}else{

   $pdf1->tipoalvara = 'ALVARÁ DE LICENÇA';

   $sqlparag = "select *
                from db_documento 
		inner join db_docparag on db03_docum = db04_docum 
		inner join db_paragrafo on db04_idparag = db02_idparag 
		where db03_docum = 26 and db02_descr ilike '%Paragrafo 1 Alvara Normal%' and db03_instit = " . db_getsession("DB_instit");
   $resparag = pg_query($sqlparag);
//db_criatabela($resparag);exit;

   if ( pg_numrows($resparag) == 0 ) {
     db_redireciona('db_erros.php?fechar=true&db_erro=Configure o documento 26 com os paragrafos do alvara!');
     exit; 
   }

   db_fieldsmemory($resparag,0);

   $pdf1->texto      = $db02_texto;
   $pdf1->inicia     = $db02_inicia;
//'A PREFEITURA MUNICIPAL DE ' . $munic . ', concede a licença prevista nos artigos 94 e 95, da Lei Municipal n'.chr(186).' 3282/03 de 24 de dezembro de 2003, para o contribuinte abaixo identificado:'; 
   $pdf1->obs        = 'O presente alvará é de caráter permanente devendo ser recolhida a taxa de fiscalização e/ou vistoria do  estabelecimento ou do funcionamento da atividade, quando da notificação fiscal.';
   $sqlparag = "select *
                from db_documento 
		inner join db_docparag on db03_docum = db04_docum 
		inner join db_paragrafo on db04_idparag = db02_idparag 
		where db03_docum = 26 and db02_descr ilike '%OBSERVACAO ALVARA%' and db03_instit = " . db_getsession("DB_instit");
   $resparag = pg_query($sqlparag);
   db_fieldsmemory($resparag,0);
   $pdf1->obs        = $db02_texto;

}

$pdf1->prefeitura  = $prefeitura;
$pdf1->municpref   = $munic;
$pdf1->ativ        = $q07_ativ;
$pdf1->nrinscr     = $q02_inscr;
$pdf1->nome        = $z01_nome;
$pdf1->ender       = $z01_ender;
$pdf1->bairropri   = $z01_bairro;
$pdf1->compl       = $z01_compl;
$pdf1->numero      = $z01_numero;
$pdf1->descrativ   = $q03_descr;
$pdf1->datainc     = $q02_dtinic;
$pdf1->cnpjcpf     = $z01_cgccpf;

$result=$cltabativ->sql_record($cltabativ->sql_query($q02_inscr,"","q07_ativ,q03_descr as descr,q03_atmemo, q07_datafi, q07_databx",""));
$numrows=$cltabativ->numrows;
$arr= array();
$arr02= array();
for($i=0; $i<$numrows; $i++){
  db_fieldsmemory($result,$i);
  if ($q07_datafi == "" and $q07_databx == "") {
    if($descr!=$q03_descr){
      $arr[$q07_ativ]=$descr;
    }  
    $q03_atmemo=str_replace("\n","",$q03_atmemo);
    $q03_atmemo=str_replace("\r","",$q03_atmemo);
    $arr02[$q07_ativ]=$q03_atmemo;
  }
  //if ($q07_datafi != "") {
  //  $pdf1->datafim = $q07_datafi;
  //}
}

$pdf1->q03_atmemo = $arr02;
$pdf1->outrasativs =$arr;
if(isset($q02_memo)){
  $q02_memo=str_replace("\n","",$q02_memo);
  $q02_memo=str_replace("\r","",$q02_memo);
  $pdf1->q02_memo =$q02_memo;
}

$result=$clissprocesso->sql_record($clissprocesso->sql_query($q02_inscr,"q14_proces",""));
if ($cltabativ->numrows == 0) {
  db_fieldsmemory($result,0);
  $pdf1->processo = $q14_proces;
} else {
  $pdf1->processo = 0;
}

$pdf1->imprime();
$pdf1->objpdf->Output();
?>