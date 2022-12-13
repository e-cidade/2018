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


require_once("fpdf151/scpdf.php");
require_once("fpdf151/impcarne.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_libtributario.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_issbase_classe.php");
require_once("classes/db_isscalc_classe.php");
require_once("classes/db_arrecad_classe.php");
require_once("dbforms/db_funcoes.php");

require_once("classes/db_iptucalc_classe.php");
require_once("classes/db_db_config_classe.php");
require_once("classes/db_iptunump_classe.php");
require_once("classes/db_iptubase_classe.php");
require_once("classes/db_massamat_classe.php");
require_once("classes/db_iptuender_classe.php");

require_once("classes/db_db_docparag_classe.php");
require_once("classes/db_arrematric_classe.php");
require_once("classes/db_listadoc_classe.php");
require_once("model/convenio.model.php");
require_once("model/regraEmissao.model.php");

db_postmemory($HTTP_SERVER_VARS);

$cliptucalc    = new cl_iptucalc;
$cliptuender   = new cl_iptuender;
$cliptunump    = new cl_iptunump;
$clmassamat    = new cl_massamat;
$cldb_config   = new cl_db_config;
$cldb_docparag = new cl_db_docparag;
$clarrematric  = new cl_arrematric;
$cllistadoc    = new cl_listadoc;
$cldb_config   = new cl_db_config;
$iCountErro    = 0;
$nomearquivos  = "";
$wheretipo     = "";

$resul = $cldb_config->sql_record($cldb_config->sql_query(db_getsession("DB_instit"), "numbanco, logo, nomeinst as prefeitura, munic"));
db_fieldsmemory($resul, 0);

$munic2    = $munic;
$numbanco  = $numbanco;
$nomeinst2 = $prefeitura;

$sOrder = null;

switch ($ordem)  {

case "endereco":
  $sOrder = "j23_ender, j23_bairro, j23_numero, j23_compl";
  break;
case "bairroender":
  $sOrder = "j23_bairro, j23_ender, j23_numero, j23_compl";
  break;
case "alfabetica":
  $sOrder = "z01_nome";
  break;
case "zonaentrega": 
  $sOrder = "j86_iptucadzonaentrega";       
  break;
case "refant":
  $sOrder = "j40_refant";
  break;
case "setorquadralote":
  $sOrder = "j34_setor, j34_quadra, j34_lote";
  break;
case "bairroalfa":
  $sOrder = " j23_bairro ";
  break;  
default : 
  $sOrder = "z01_nome";
  break;
}

$sql  = " select j20_matric,";
$sql .= "        j23_vlrter, ";
$sql .= "        j23_aliq, ";
$sql .= "        j86_iptucadzonaentrega, ";
$sql .= "        z01_nome, ";
$sql .= "        j01_idbql, ";
$sql .= "        j14_nome as j23_ender, ";
$sql .= "        j39_numero as j23_numero, ";
$sql .= "        j39_compl as j23_compl, ";
$sql .= "        j13_descr as j23_bairro, ";
$sql .= "        substr(fc_iptuender,156,02) as j23_uf, ";
$sql .= "        substr(fc_iptuender,159,08) as j23_cep, ";
$sql .= "     	 j20_matric, ";
$sql .= "     	 j34_setor, ";    
$sql .= "     	 j34_quadra, ";    
$sql .= "     	 j34_lote, ";    
$sql .= "  	 	   j20_numpre ";
$sql .= "   from (select j23_matric, ";
$sql .= "                j23_vlrter, ";
$sql .= "                j23_aliq, ";
$sql .= "                j86_iptucadzonaentrega, ";
$sql .= "                z01_nome, ";
$sql .= "                j01_idbql, ";
$sql .= "                fc_iptuender(j23_matric), ";
$sql .= "             	 j20_matric, ";    
$sql .= "             	 j34_setor, ";    
$sql .= "             	 j34_quadra, ";    
$sql .= "             	 j34_lote, ";    
$sql .= "             	 j20_numpre, ";
$sql .= "             	 ruas.*, ";
$sql .= "             	 iptuconstr.*, ";
$sql .= "             	 bairro.* ";
$sql .= "           from iptucalc  ";
$sql .= "                inner join iptunump            on iptunump.j20_matric           = iptucalc.j23_matric ";
$sql .= "                                              and iptunump.j20_anousu           = iptucalc.j23_anousu ";   
$sql .= "                inner join iptubase            on iptubase.j01_matric           = iptucalc.j23_matric ";
$sql .= "                inner join iptuconstr          on iptubase.j01_matric           = iptuconstr.j39_matric ";
$sql .= "                                              and iptuconstr.j39_idprinc is true ";
$sql .= "                inner join ruas                on iptuconstr.j39_codigo         = ruas.j14_codigo "; 
$sql .= "                inner join lote 		            on lote.j34_idbql                = iptubase.j01_idbql ";
$sql .= "                inner join bairro              on bairro.j13_codi               = lote.j34_bairro ";
$sql .= "                inner join cgm                 on cgm.z01_numcgm                = iptubase.j01_numcgm ";
$sql .= "                left  join iptumatzonaentrega 	on iptumatzonaentrega.j86_matric = iptubase.j01_matric ";
$sql .= "         where iptucalc.j23_anousu = $anousu $wheretipo " . ($quantidade != ""?" limit {$quantidade}":"") . ") as x ";
$sql .= " order by $sOrder "; 

$rsUnica = pg_query($sql) or die($sql);
$numrowsunica = pg_numrows($rsUnica);
if ($numrowsunica == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existe calculo para o IPTU '.$anousu);
  exit;
}

try {
  $oRegraEmissao = new regraEmissao(null,4,db_getsession('DB_instit'),date("Y-m-d",db_getsession("DB_datausu")),db_getsession('DB_ip'));
} catch (Exception $eExeption){
  db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
  exit;
}

$pdf2 = $oRegraEmissao->getObj();
$pdf2->sMensagemCaixa        = "";
$pdf2->sMensagemContribuinte = "";
  
$iunicaxxx=0;

for ($iunica=0;$iunica < $numrowsunica;$iunica++){
  $iunicaxxx++;
  db_fieldsmemory($rsUnica,$iunica);

  $matric       = $j20_matric;
  $numpre_unica = $j20_numpre;
  $numpres      = "";
  $cliptubase   = new cl_iptubase;
  $vt           = $HTTP_POST_VARS;
  $tam          = sizeof($vt);
  reset($vt);

  for ($i = 0; $i < $tam; $i ++) {
    if (db_indexOf(key($vt), "CHECK") > 0){
      $numpres .= "N".$vt[key($vt)];
    }
    next($vt);
  }
  $numpres = split("N", $numpres);

  $unica   = false;
  if (sizeof($numpres) < 2) {
    $numpres = array ("0" => "0", "1" => $numpre_unica.'P000');
    $unica   = true;
  } else {
    if (isset ($HTTP_POST_VARS["numpre_unica"])) {
      $unica = true;
    }
  }

  for ($volta = 1; $volta < sizeof($numpres); $volta ++) {
    $codigos = split("P", $numpres[$volta]);
  }

  $resultunica = pg_exec("select j23_anousu from iptucalc inner join iptunump on j20_anousu = j23_anousu and j20_matric = j23_matric where j20_numpre = $numpre_unica");
  db_fieldsmemory($resultunica, 0);
  $pdf2->iptj23_anousu = $j23_anousu;

  $resultunica = pg_exec("select * from recibounica where k00_numpre = $numpre_unica");
  if (pg_numrows($resultunica) > 0) {
    db_fieldsmemory($resultunica, 0);
    $vencunica = db_formatar($k00_dtvenc, "d");
  } else {
    $iCountErro++;
    continue;
  }

  $sqlProprietarionome = " select z01_munic, z01_nome, z01_numcgm, z01_cgccpf, proprietario, z01_ender, z01_bairro, z01_cep, z01_numero,z01_compl, z01_munic from proprietario_nome where j01_matric = $matric ";
  $resultpro = $cliptubase->proprietario_record($sqlProprietarionome);
  db_fieldsmemory($resultpro, 0);

  $pdf2->iptbairroimo    = $j23_bairro;
  $pdf2->logo			 = $logo;
  $pdf2->iptj01_matric   = $matric;
  $pdf2->iptz01_munic    = $z01_munic;
  $pdf2->iptz01_cidade   = $munic2;
  $pdf2->iptprefeitura   = $nomeinst2;
  $pdf2->iptendermatric  = $j23_ender. (isset($j23_numero)&&$j23_numero!=""?", $j23_numero":"").(isset($j23_compl)&&$j23_compl!=""?"/ $j23_compl":"");
  $pdf2->iptz01_ender    = $z01_ender. (isset($z01_numero)&&$z01_numero!=""?", $z01_numero":"").(isset($z01_compl)&&$z01_compl!=""?"/ $z01_compl":"");
  $pdf2->iptbql          = $j34_setor."/".$j34_quadra."/".$j34_lote;
  $pdf2->iptnomepri      = $j23_ender.(isset($j23_numero)&&$j23_numero!=""?", $j23_numero":"").(isset($j23_compl)&&$j23_compl!=""?"/ $j23_compl":""); //   $nomepri;
  $pdf2->iptproprietario = $proprietario;
  $pdf2->iptz01_nome     = $z01_numcgm . " - " . $z01_nome;
  $pdf2->iptz01_numcgm   = $z01_numcgm;
  $pdf2->iptz01_cgccpf   = $z01_cgccpf;
  $pdf2->iptz01_bairro   = $z01_bairro;
  $pdf2->iptz01_cep      = $z01_cep;
  $pdf2->iptj43_cep      = $j23_cep;
  $pdf2->iptdataemis     = date("d/m/Y", db_getsession("DB_datausu"));

  // modelo 1
  // 1 via
  $pdf2->prefeitura      = $nomeinst2 ;
  $pdf2->descr3_1        = $z01_nome;
  $pdf2->descr3_2        = $z01_ender. (isset($z01_numero)&&$z01_numero!=""?", $z01_numero":"").(isset($z01_compl)&&$z01_compl!=""?"/ $z01_compl":"");
  $pdf2->titulo1         = "matricula ";
  $pdf2->descr1          = $matric;
  $pdf2->descr17         = "SQL:".$j34_setor."/".$j34_quadra."/".$j34_lote;

  //2 via
  $pdf2->titulo8         = "matricula ";
  $pdf2->descr8          = $matric;
  $pdf2->descr11_1       = $z01_nome;
  $pdf2->descr11_2       = $z01_ender. (isset($z01_numero)&&$z01_numero!=""?", $z01_numero":"").(isset($z01_compl)&&$z01_compl!=""?"/ $z01_compl":"");

  $sql = "select * from arrematric 
    inner join arrecad on arrecad.k00_numpre = arrematric.k00_numpre 
    where k00_matric = $matric 
    and k00_dtvenc < '".date("Y-m-d", db_getsession("DB_datausu"))."' limit 1";
  $rsResulant = pg_query($sql);
  $numlin     = pg_numrows($rsResulant);
  if ($numlin > 0) {
    $pdf2->iptdebant = "Há Débitos Anteriores, favor procurar Setor de Dívida Ativa";
  } else {
    $pdf2->iptdebant = "";

  }
  unset ($resultpro);

  if ($unica == 't') {

    $sql = " select r.k00_numpre,r.k00_dtvenc as dtvencunic, r.k00_dtoper as dtoperunic,r.k00_percdes,
      fc_calcula(r.k00_numpre,0,0,r.k00_dtvenc,r.k00_dtvenc,".db_getsession("DB_anousu").")
      from recibounica r
      where r.k00_numpre = ".$codigos[0]." and r.k00_dtvenc >= '".date('Y-m-d', db_getsession("DB_datausu"))."'::date limit 1";

    $linha = 220;
    $resultfin = pg_query($sql) or die($sql);
    if ($resultfin != false && pg_numrows($resultfin) > 0) {
      db_fieldsmemory($resultfin, 0);

      $uvlrhis      = substr($fc_calcula,1,13);
      $uvlrcor      = substr($fc_calcula,14,13);
      $uvlrjuros    = substr($fc_calcula,27,13);
      $uvlrmulta    = substr($fc_calcula,40,13);
      $uvlrdesconto = substr($fc_calcula,53,13);

      $utotal       = @$uvlrcor+@$uvlrjuro+@$uvlrmulta-@$uvlrdesconto;

      $pdf2->iptk00_percdes = $k00_percdes;
      $uvlrcor = db_formatar($uvlrcor, 'f');
      $pdf2->iptuvlrcor = $uvlrcor;

      $vlrhis = db_formatar($uvlrhis, 'f');

      $vlrdesconto = db_formatar($uvlrdesconto, 'f');
      $pdf2->iptuvlrdesconto = $vlrdesconto;

      $vlrtotal = db_formatar($utotal, 'f');
      $vlrbar   = db_formatar(str_replace('.', '', str_pad(number_format($utotal, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');
      $pdf2->ipttotal = $vlrtotal;


      try {
        
        $oMensagem = DBTributario::getMensagensParcela( $k00_numpre, null, null );
        $pdf2->sMensagemCaixa        = $oMensagem->sMensagemCaixa;
        $pdf2->sMensagemContribuinte = $oMensagem->sMensagemContribuinte;


         $oConvenio       = new convenio($oRegraEmissao->getConvenio(),$k00_numpre,0,$vlrbar,$vlrbar,$dtvencunic,6);
         $codigo_barras   = $oConvenio->getCodigoBarra();
         $linha_digitavel = $oConvenio->getLinhaDigitavel();
      }  catch (Exception $eExeption){
         db_redireciona("db_erros.php?fechar=true&db_erro=Matricula:$matric - Erro:{$eExeption->getMessage()}");
        exit;
      }

      $dtvencunic = db_formatar($dtvencunic, 'd');
      $pdf2->iptdtvencunic = $dtvencunic;

      $pdf2->iptcodigo_barras = $codigo_barras;
      $pdf2->iptlinha_digitavel = $linha_digitavel;
    }
    pg_free_result($resultfin);
  }

  $sql = "select sum(j22_valor) as vlredi
    from iptucale
    where j22_anousu = $j23_anousu 
    and j22_matric = $matric";
  $sqlres = pg_exec($sql);
  if (pg_numrows($sqlres) > 0) {
    db_fieldsmemory($sqlres, 0);
  } else {
    $vlredi = 0;
  }

  $sql    = "select j23_vlrter, j23_aliq from iptucalc where j23_anousu = $j23_anousu and j23_matric = $matric";
  $sqlres = pg_exec($sql);
  if (pg_numrows($sqlres) > 0) {
    db_fieldsmemory($sqlres, 0);
    $pdf2->iptj23_aliq = $j23_aliq;
  } else {
    $j23_vlrter = 0;
    $j23_aliq = 0;
  }
  $j23_vlrter += $vlredi;
  $pdf2->iptj23_vlrter = db_formatar($j23_vlrter, 'f');

  $pdf2->imprime();
  if ($iunicaxxx == 250) {
    $inipag = ($iunica-$iunicaxxx)+1;
    $fimpag = $iunica+1;
    $arq           = "tmp/Carne_de_".$inipag."_a_".$fimpag.".pdf";
    $nomearquivos .= "tmp/Carne_de_".$inipag."_a_".$fimpag.".pdf#Dowload dos carnes de ".$inipag." a ".$fimpag."|";
    $pdf2->objpdf->Output($arq, false, true);

    unset($pdf2);
    unset($oRegraEmissao);

    try {
      $oRegraEmissao = new regraEmissao(null,4,db_getsession('DB_instit'),date("Y-m-d",db_getsession("DB_datausu")),db_getsession('DB_ip'));
    } catch (Exception $eExeption){
      db_redireciona("db_erros.php?fechar=true&db_erro=Matricula:$matric - Erro:{$eExeption->getMessage()}");
      exit;
    }	

    $pdf2 = $oRegraEmissao->getObj();    

    $iunicaxxx = 0;
  }

}	

if ($iCountErro == $numrowsunica) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Sem registros para gerar! Não há Cota Única");
  exit;

}

$inipag        = ($iunica-$iunicaxxx)+1;
$fimpag        = $iunica+1;
$arq           = "tmp/Carne_de_".$inipag."_a_".$fimpag.".pdf";
$nomearquivos .= "tmp/Carne_de_".$inipag."_a_".$fimpag.".pdf#Dowload dos carnes de ".$inipag." a ".$fimpag."";

$pdf2->objpdf->Output($arq,false,true);

echo "<script>";
echo "  listagem = '$nomearquivos';";
echo "  parent.js_montarlista(listagem,'form1');";
echo "</script>";	

?>
<?

require("fpdf151/scpdf.php");
include("fpdf151/impcarne.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_sql.php");
include("libs/db_utils.php");
include("libs/db_usuariosonline.php");
include("classes/db_issbase_classe.php");
include("classes/db_isscalc_classe.php");
include("classes/db_arrecad_classe.php");
include("dbforms/db_funcoes.php");

include ("classes/db_iptucalc_classe.php");
include ("classes/db_db_config_classe.php");
include ("classes/db_iptunump_classe.php");
include ("classes/db_iptubase_classe.php");
include ("classes/db_massamat_classe.php");
include ("classes/db_iptuender_classe.php");

include("classes/db_db_docparag_classe.php");
include("classes/db_arrematric_classe.php");
include("classes/db_listadoc_classe.php");
include("model/convenio.model.php");
include("model/regraEmissao.model.php");

db_postmemory($HTTP_SERVER_VARS);

$cliptucalc    = new cl_iptucalc;
$cliptuender   = new cl_iptuender;
$cliptunump    = new cl_iptunump;
$clmassamat    = new cl_massamat;
$cldb_config   = new cl_db_config;
$cldb_docparag = new cl_db_docparag;
$clarrematric  = new cl_arrematric;
$cllistadoc    = new cl_listadoc;
$cldb_config   = new cl_db_config;
$iCountErro    = 0;
$nomearquivos  = "";
$wheretipo     = "";

$resul = $cldb_config->sql_record($cldb_config->sql_query(db_getsession("DB_instit"), "numbanco, logo, nomeinst as prefeitura, munic"));
db_fieldsmemory($resul, 0);

$munic2    = $munic;
$numbanco  = $numbanco;
$nomeinst2 = $prefeitura;

$sOrder = null;

switch ($ordem)  {

  case "endereco":
    $sOrder = "j23_ender, j23_bairro, j23_numero, j23_compl";
  break;
  case "bairroender":
    $sOrder = "j23_bairro, j23_ender, j23_numero, j23_compl";
  break;
  case "alfabetica":
    $sOrder = "z01_nome";
  break;
  case "zonaentrega": 
    $sOrder = "j86_iptucadzonaentrega";       
  break;
  case "refant":
    $sOrder = "j40_refant";
  break;
  case "setorquadralote":
    $sOrder = "j34_setor, j34_quadra, j34_lote";
  break;
  case "bairroalfa":
    $sOrder = " j23_bairro ";
  break;  
  default : 
  $sOrder = "z01_nome";
  break;
}

$sql  = " select j20_matric,";
$sql .= "        j23_vlrter, ";
$sql .= "        j23_aliq, ";
$sql .= "        j86_iptucadzonaentrega, ";
$sql .= "        z01_nome, ";
$sql .= "        j01_idbql, ";
$sql .= "        j14_nome as j23_ender, ";
$sql .= "        j39_numero as j23_numero, ";
$sql .= "        j39_compl as j23_compl, ";
$sql .= "        j13_descr as j23_bairro, ";
$sql .= "        substr(fc_iptuender,156,02) as j23_uf, ";
$sql .= "        substr(fc_iptuender,159,08) as j23_cep, ";
$sql .= "     	 j20_matric, ";
$sql .= "     	 j34_setor, ";    
$sql .= "     	 j34_quadra, ";    
$sql .= "     	 j34_lote, ";    
$sql .= "  	 	   j20_numpre ";
$sql .= "   from (select j23_matric, ";
$sql .= "                j23_vlrter, ";
$sql .= "                j23_aliq, ";
$sql .= "                j86_iptucadzonaentrega, ";
$sql .= "                z01_nome, ";
$sql .= "                j01_idbql, ";
$sql .= "                fc_iptuender(j23_matric), ";
$sql .= "             	 j20_matric, ";    
$sql .= "             	 j34_setor, ";    
$sql .= "             	 j34_quadra, ";    
$sql .= "             	 j34_lote, ";    
$sql .= "             	 j20_numpre, ";
$sql .= "             	 ruas.*, ";
$sql .= "             	 iptuconstr.*, ";
$sql .= "             	 bairro.* ";
$sql .= "           from iptucalc  ";
$sql .= "                inner join iptunump            on iptunump.j20_matric           = iptucalc.j23_matric ";
$sql .= "                                              and iptunump.j20_anousu           = iptucalc.j23_anousu ";   
$sql .= "                inner join iptubase            on iptubase.j01_matric           = iptucalc.j23_matric ";
$sql .= "                inner join iptuconstr          on iptubase.j01_matric           = iptuconstr.j39_matric ";
$sql .= "                                              and iptuconstr.j39_idprinc is true ";
$sql .= "                inner join ruas                on iptuconstr.j39_codigo         = ruas.j14_codigo "; 
$sql .= "                inner join lote 		            on lote.j34_idbql                = iptubase.j01_idbql ";
$sql .= "                inner join bairro              on bairro.j13_codi               = lote.j34_bairro ";
$sql .= "                inner join cgm                 on cgm.z01_numcgm                = iptubase.j01_numcgm ";
$sql .= "                left  join iptumatzonaentrega 	on iptumatzonaentrega.j86_matric = iptubase.j01_matric ";
$sql .= "         where iptucalc.j23_anousu = $anousu $wheretipo " . ($quantidade != ""?" limit {$quantidade}":"") . ") as x ";
$sql .= " order by $sOrder "; 

$rsUnica = pg_query($sql) or die($sql);
$numrowsunica = pg_numrows($rsUnica);
if ($numrowsunica == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existe calculo para o IPTU '.$anousu);
  exit;
}

try {
  $oRegraEmissao = new regraEmissao(null,4,db_getsession('DB_instit'),date("Y-m-d",db_getsession("DB_datausu")),db_getsession('DB_ip'));
} catch (Exception $eExeption){
  db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
  exit;
}

$pdf2 = $oRegraEmissao->getObj();

$iunicaxxx=0;

for ($iunica=0;$iunica < $numrowsunica;$iunica++){
   $iunicaxxx++;
   db_fieldsmemory($rsUnica,$iunica);

   $matric       = $j20_matric;
   $numpre_unica = $j20_numpre;
   $numpres      = "";
   $cliptubase   = new cl_iptubase;
   $vt           = $HTTP_POST_VARS;
   $tam          = sizeof($vt);
   reset($vt);
  
  for ($i = 0; $i < $tam; $i ++) {
    if (db_indexOf(key($vt), "CHECK") > 0){
      $numpres .= "N".$vt[key($vt)];
    }
    next($vt);
  }
  $numpres = split("N", $numpres);
  
  $unica   = false;
  if (sizeof($numpres) < 2) {
    $numpres = array ("0" => "0", "1" => $numpre_unica.'P000');
    $unica   = true;
  } else {
    if (isset ($HTTP_POST_VARS["numpre_unica"])) {
      $unica = true;
    }
  }
  
  for ($volta = 1; $volta < sizeof($numpres); $volta ++) {
    $codigos = split("P", $numpres[$volta]);
  }
  
  $resultunica = pg_exec("select j23_anousu from iptucalc inner join iptunump on j20_anousu = j23_anousu and j20_matric = j23_matric where j20_numpre = $numpre_unica");
  db_fieldsmemory($resultunica, 0);
  $pdf2->iptj23_anousu = $j23_anousu;
  
  $resultunica = pg_exec("select * from recibounica where k00_numpre = $numpre_unica");
  if (pg_numrows($resultunica) > 0) {
    db_fieldsmemory($resultunica, 0);
    $vencunica = db_formatar($k00_dtvenc, "d");
  } else {
  	$iCountErro++;
  	continue;
  }
  
  $sqlProprietarionome = " select z01_munic, z01_nome, z01_numcgm, z01_cgccpf, proprietario, z01_ender, z01_bairro, z01_cep, z01_numero,z01_compl, z01_munic from proprietario_nome where j01_matric = $matric ";
  $resultpro = $cliptubase->proprietario_record($sqlProprietarionome);
  db_fieldsmemory($resultpro, 0);

  $pdf2->iptbairroimo    = $j23_bairro;
  $pdf2->logo			 = $logo;
  $pdf2->iptj01_matric   = $matric;
  $pdf2->iptz01_munic    = $z01_munic;
  $pdf2->iptz01_cidade   = $munic2;
  $pdf2->iptprefeitura   = $nomeinst2;
  $pdf2->iptendermatric  = $j23_ender. (isset($j23_numero)&&$j23_numero!=""?", $j23_numero":"").(isset($j23_compl)&&$j23_compl!=""?"/ $j23_compl":"");
  $pdf2->iptz01_ender    = $z01_ender. (isset($z01_numero)&&$z01_numero!=""?", $z01_numero":"").(isset($z01_compl)&&$z01_compl!=""?"/ $z01_compl":"");
  $pdf2->iptbql          = $j34_setor."/".$j34_quadra."/".$j34_lote;
  $pdf2->iptnomepri      = $j23_ender.(isset($j23_numero)&&$j23_numero!=""?", $j23_numero":"").(isset($j23_compl)&&$j23_compl!=""?"/ $j23_compl":""); //   $nomepri;
  $pdf2->iptproprietario = $proprietario;
  $pdf2->iptz01_nome     = $z01_numcgm . " - " . $z01_nome;
  $pdf2->iptz01_numcgm   = $z01_numcgm;
  $pdf2->iptz01_cgccpf   = $z01_cgccpf;
  $pdf2->iptz01_bairro   = $z01_bairro;
  $pdf2->iptz01_cep      = $z01_cep;
  $pdf2->iptj43_cep      = $j23_cep;
  $pdf2->iptdataemis     = date("d/m/Y", db_getsession("DB_datausu"));
  
  // modelo 1
  // 1 via
  $pdf2->prefeitura      = $nomeinst2 ;
  $pdf2->descr3_1        = $z01_nome;
  $pdf2->descr3_2        = $z01_ender. (isset($z01_numero)&&$z01_numero!=""?", $z01_numero":"").(isset($z01_compl)&&$z01_compl!=""?"/ $z01_compl":"");
  $pdf2->titulo1         = "matricula ";
  $pdf2->descr1          = $matric;
  $pdf2->descr17         = "SQL:".$j34_setor."/".$j34_quadra."/".$j34_lote;
  
  //2 via
  $pdf2->titulo8         = "matricula ";
  $pdf2->descr8          = $matric;
  $pdf2->descr11_1       = $z01_nome;
  $pdf2->descr11_2       = $z01_ender. (isset($z01_numero)&&$z01_numero!=""?", $z01_numero":"").(isset($z01_compl)&&$z01_compl!=""?"/ $z01_compl":"");
  
  $sql = "select * from arrematric 
	      inner join arrecad on arrecad.k00_numpre = arrematric.k00_numpre 
		  where k00_matric = $matric 
		    and k00_dtvenc < '".date("Y-m-d", db_getsession("DB_datausu"))."' limit 1";
  $rsResulant = pg_query($sql);
  $numlin     = pg_numrows($rsResulant);
  if ($numlin > 0) {
    $pdf2->iptdebant = "Há Débitos Anteriores, favor procurar Setor de Dívida Ativa";
  } else {
    $pdf2->iptdebant = "";

  }
  unset ($resultpro);
  
  if ($unica == 't') {

    $sql = " select r.k00_numpre,r.k00_dtvenc as dtvencunic, r.k00_dtoper as dtoperunic,r.k00_percdes,
                    fc_calcula(r.k00_numpre,0,0,r.k00_dtvenc,r.k00_dtvenc,".db_getsession("DB_anousu").")
                    from recibounica r
              where r.k00_numpre = ".$codigos[0]." and r.k00_dtvenc >= '".date('Y-m-d', db_getsession("DB_datausu"))."'::date limit 1";

    $linha = 220;
    $resultfin = pg_query($sql) or die($sql);
    if ($resultfin != false && pg_numrows($resultfin) > 0) {
      db_fieldsmemory($resultfin, 0);

      $uvlrhis      = substr($fc_calcula,1,13);
      $uvlrcor      = substr($fc_calcula,14,13);
      $uvlrjuros    = substr($fc_calcula,27,13);
      $uvlrmulta    = substr($fc_calcula,40,13);
      $uvlrdesconto = substr($fc_calcula,53,13);

      $utotal       = @$uvlrcor+@$uvlrjuro+@$uvlrmulta-@$uvlrdesconto;
      
      $pdf2->iptk00_percdes = $k00_percdes;
      $uvlrcor = db_formatar($uvlrcor, 'f');
      $pdf2->iptuvlrcor = $uvlrcor;
      
      $vlrhis = db_formatar($uvlrhis, 'f');
      
      $vlrdesconto = db_formatar($uvlrdesconto, 'f');
      $pdf2->iptuvlrdesconto = $vlrdesconto;
      
      $vlrtotal = db_formatar($utotal, 'f');
      $vlrbar   = db_formatar(str_replace('.', '', str_pad(number_format($utotal, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');
      $pdf2->ipttotal = $vlrtotal;
      
      
      try {
		$oConvenio       = new convenio($oRegraEmissao->getConvenio(),$k00_numpre,0,$vlrbar,$vlrbar,$dtvencunic,6);
		$codigo_barras   = $oConvenio->getCodigoBarra();
	    $linha_digitavel = $oConvenio->getLinhaDigitavel();
	  } catch (Exception $eExeption){
        db_redireciona("db_erros.php?fechar=true&db_erro=Matricula:$matric - Erro:{$eExeption->getMessage()}");
  	    exit;
	  }
          
      $dtvencunic = db_formatar($dtvencunic, 'd');
      $pdf2->iptdtvencunic = $dtvencunic;
      
      $pdf2->iptcodigo_barras = $codigo_barras;
      $pdf2->iptlinha_digitavel = $linha_digitavel;
    }
    pg_free_result($resultfin);
  }
  
  $sql = "select sum(j22_valor) as vlredi
           from iptucale
          where j22_anousu = $j23_anousu 
            and j22_matric = $matric";
  $sqlres = pg_exec($sql);
  if (pg_numrows($sqlres) > 0) {
    db_fieldsmemory($sqlres, 0);
  } else {
    $vlredi = 0;
  }
  
  $sql    = "select j23_vlrter, j23_aliq from iptucalc where j23_anousu = $j23_anousu and j23_matric = $matric";
  $sqlres = pg_exec($sql);
  if (pg_numrows($sqlres) > 0) {
    db_fieldsmemory($sqlres, 0);
    $pdf2->iptj23_aliq = $j23_aliq;
  } else {
    $j23_vlrter = 0;
    $j23_aliq = 0;
  }
  $j23_vlrter += $vlredi;
  $pdf2->iptj23_vlrter = db_formatar($j23_vlrter, 'f');
  
  $pdf2->imprime();
  if ($iunicaxxx == 250) {
     $inipag = ($iunica-$iunicaxxx)+1;
     $fimpag = $iunica+1;
     $arq           = "tmp/Carne_de_".$inipag."_a_".$fimpag.".pdf";
     $nomearquivos .= "tmp/Carne_de_".$inipag."_a_".$fimpag.".pdf#Dowload dos carnes de ".$inipag." a ".$fimpag."|";
     $pdf2->objpdf->Output($arq, false, true);
    
     unset($pdf2);
     unset($oRegraEmissao);
    
	  try {
    	  $oRegraEmissao = new regraEmissao(null,4,db_getsession('DB_instit'),date("Y-m-d",db_getsession("DB_datausu")),db_getsession('DB_ip'));
	  } catch (Exception $eExeption){
    	  db_redireciona("db_erros.php?fechar=true&db_erro=Matricula:$matric - Erro:{$eExeption->getMessage()}");
    	  exit;
	  }	

	  $pdf2 = $oRegraEmissao->getObj();    

    $iunicaxxx = 0;
  }
  
}	

if ($iCountErro == $numrowsunica) {
	 db_redireciona("db_erros.php?fechar=true&db_erro=Sem registros para gerar! Não há Cota Única");
   exit;
	
}

$inipag        = ($iunica-$iunicaxxx)+1;
$fimpag        = $iunica+1;
$arq           = "tmp/Carne_de_".$inipag."_a_".$fimpag.".pdf";
$nomearquivos .= "tmp/Carne_de_".$inipag."_a_".$fimpag.".pdf#Dowload dos carnes de ".$inipag." a ".$fimpag."";

$pdf2->objpdf->Output($arq,false,true);

echo "<script>";
echo "  listagem = '$nomearquivos';";
echo "  parent.js_montarlista(listagem,'form1');";
echo "</script>";	

?>