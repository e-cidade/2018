<?PHP
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

require_once("fpdf151/scpdf.php");
require_once("fpdf151/impcarne.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_benstransf_classe.php");
require_once("classes/db_benstransfdes_classe.php");
require_once("classes/db_benstransfcodigo_classe.php");
require_once("classes/db_db_depart_classe.php");
$clbenstransf       = new cl_benstransf;
$clbenstransfdes    = new cl_benstransfdes;
$clbenstransfcodigo = new cl_benstransfcodigo;
$cldb_depart        = new cl_db_depart;
$clrotulo           = new rotulocampo;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$sqlinst    = "select * from db_config where codigo = ".db_getsession("DB_instit");
$resultinst = db_query($sqlinst);

db_fieldsmemory($resultinst, 0);

if (isset($t96_codtran) && $t96_codtran != "") {
  
  //rotina que traz departamento de origem e usuário q a emitiu
  $sCamposBensTransf = "nome,db_depart.descrdepto as origem,t93_data,t93_obs";
  $sWhereBensTransf  = "t93_codtran = $t96_codtran and t93_instit = ".db_getsession("DB_instit");
  $sSqlBensTransf    = $clbenstransf->sql_query(null, $sCamposBensTransf, null, $sWhereBensTransf);
  $resultorigem      = $clbenstransf->sql_record($sSqlBensTransf);
  
  if ($clbenstransf->numrows > 0) {   
    db_fieldsmemory($resultorigem, 0);
  }else{
    
    $oParms = new stdClass();
    $oParms->codigoTransferencia = $t96_codtran;
    $sMsg = _M('patrimonial.patrimonio.pat2_relbenstransf002.transferencia_invalida');
    db_redireciona("db_erros.php?fechar=true&db_erro=" . $sMsg);
  }
  //rotina que traz código,descrição e classificação dos bens
  
  $sCamposBensTransfCod  = "distinct t52_bem,                 ";
  $sCamposBensTransfCod .= "t52_descr,                        ";
  $sCamposBensTransfCod .= "t64_class,                        ";
  $sCamposBensTransfCod .= "t52_ident,                        ";
  
  //$sCamposBensTransfCod .= "origem.t30_descr as divorigem,    ";
  //$sCamposBensTransfCod .= "destino.t30_descr as divdestino,  ";
  
  $sCamposBensTransfCod .= "divisaoorigem.t30_descr as divorigem,   ";
  $sCamposBensTransfCod .= "divisaodestino.t30_descr as divdestino, ";
  
  $sCamposBensTransfCod .= "situabens.t70_descr as situacao   ";
  
  $sWhereBensTransfCod  = "t95_codtran = $t96_codtran and t52_instit = ".db_getsession("DB_instit");
  
  $sSqlBensTransfCodigo = $clbenstransfcodigo->sql_query_benstransf_origdestsitua(null,
                                                                                  null, 
                                                                                  $sCamposBensTransfCod, 
                                                                                  null, 
                                                                                  $sWhereBensTransfCod);
 // echo $sSqlBensTransfCodigo; die();
 
  //die ($sSqlBensTransfCodigo);
  $resultbens = $clbenstransfcodigo->sql_record($sSqlBensTransfCodigo);
  if($clbenstransfcodigo->numrows>0){
    db_fieldsmemory($resultbens,0);
  }else{
    
    $oParms = new stdClass();
    $oParms->codigoTransferencia = $t96_codtran;
    $sMsg = _M('patrimonial.patrimonio.pat2_relbenstransf002.nenhum_item_cadastrado');
    db_redireciona("db_erros.php?fechar=true&db_erro=" . $sMsg);
  }

  //rotina que traz departamento de destino
  $result = $clbenstransfdes->sql_record($clbenstransfdes->sql_query_file($t96_codtran,null,"t94_depart"));
  if($clbenstransfdes->numrows>0){
    db_fieldsmemory($result,0);
    $resultdestino = $cldb_depart->sql_record($cldb_depart->sql_query_file($t94_depart,"descrdepto"));
    db_fieldsmemory($resultdestino,0);
  }else{
    
    $sMsg = _M('patrimonial.patrimonio.pat2_relbenstransf002.destino_nao_informado');
    db_redireciona("db_erros.php?fechar=true&db_erro=" . $sMsg);
  }
  
//  echo $cldb_depart->sql_query_file($t94_depart,"descrdepto");
//  echo "<br><br><br><br>";
//  echo $clbenstransfdes->sql_query_file($t96_codtran,null,"t94_depart");
//  echo "<br><br><br>";
//  die ($sSqlBensTransfCodigo);
}

$pdf = new scpdf();
$pdf->Open();
$pdf1 = new db_impcarne($pdf,'8');
//$pdf1->modelo = 8;
$pdf1->objpdf->SetTextColor(0,0,0);

db_sel_instit();

$pdf1->prefeitura = $nomeinst;  
$pdf1->codtransf  = $t96_codtran;
$pdf1->logo			  = $logo;
$pdf1->destino    = $descrdepto;
$pdf1->origem     = $origem;
$pdf1->usuario    = $nome;
$pdf1->recordbens = $resultbens;
$pdf1->linhasbens = pg_numrows($resultbens);
$pdf1->bem        = "t52_bem";
$pdf1->descr_bem  = "t52_descr";
$pdf1->class_bem  = "t64_class";
$pdf1->datatransf = $t93_data;
$pdf1->obstransf  = $t93_obs;
$pdf1->t52_ident  = "t52_ident";
$pdf1->divorigem  = "divorigem";
$pdf1->divdestino = 'divdestino';
$pdf1->situacao   = "situacao";

$pdf1->imprime();

//include("fpdf151/geraarquivo.php");
$pdf1->objpdf->Output();


?>