<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("classes/db_liclicita_classe.php"));
require_once(modification("classes/db_liclicitem_classe.php"));
require_once(modification("classes/db_pcorcamforne_classe.php"));
require_once(modification("classes/db_pcorcamitem_classe.php"));
require_once(modification("classes/db_pcorcamval_classe.php"));
require_once(modification("libs/db_libdocumento.php"));
require_once(modification("libs/db_utils.php"));
$clliclicita = new cl_liclicita;
$clliclicitem = new cl_liclicitem;
$clpcorcamforne = new cl_pcorcamforne;
$clpcorcamitem = new cl_pcorcamitem;
$clpcorcamval = new cl_pcorcamval;
$clrotulo = new rotulocampo;
$clrotulo->label('');

parse_str($_SERVER['QUERY_STRING']);
db_postmemory($_SERVER);

$dbinstit=db_getsession("DB_instit");
$sSituacoes = SituacaoLicitacao::SITUACAO_ADJUDICADA.",".SituacaoLicitacao::SITUACAO_JULGADA.",".SituacaoLicitacao::SITUACAO_HOMOLOGADA;
$aWhere = array(
  "l20_codigo = {$l20_codigo}",
  "l20_instit = {$dbinstit}",
  "l20_licsituacao in ({$sSituacoes})"
);


$sSqlBuscaLicitacao = $clliclicita->sql_query(null,"*","l20_codigo", implode(' and ', $aWhere));
$rsLicitacao        = $clliclicita->sql_record($sSqlBuscaLicitacao);

if ($clliclicita->numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existe registro cadastrado, ou licitação não Julgada, ou licitação revogada');
   exit;
}

$oPDF = new PDF();
$oPDF->Open();
$oPDF->AliasNbPages();
$total = 0;
$oPDF->setfillcolor(235);
$oPDF->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;
$p=0;
$quant_forne = 0;
$val_forne = 0;
$quant_tot = 0;
$val_tot = 0;
$valortot=0;
$z01_nomeant="";

$oLibDocumento = new libdocumento(1704,null);
if ( $oLibDocumento->lErro ){
     die($oLibDocumento->sMsgErro);
}

  db_fieldsmemory($rsLicitacao,0);
  $l20_datacria=substr($l20_datacria,0,4);

  $head3 = "ADJUDICAÇÃO DE PROCESSO ";
  $head4 = "LICITAÇÃO : $l20_numero/$l20_datacria";
  $head5 = "SEQUENCIAL: $l20_codigo";
  $oPDF->addpage();
  $oPDF->ln();
  $oPDF->setfont('arial','b',14);
  $oPDF->cell(0,8,"ADJUDICAÇÃO DE PROCESSO",0,1,"C",0);
  $oPDF->cell(0,8,"LICITAÇÃO : $l20_numero/$l20_datacria",0,1,"C",0);
  $oPDF->ln();
  $oPDF->ln();
 $oPDF->setfont('arial','',8);
  $olicitacao = db_utils::fieldsMemory($rsLicitacao,0);
  $oLibDocumento->l20_numero    = $olicitacao->l20_numero;
  $oLibDocumento->l03_descr     = trim($olicitacao->l03_descr);
  $oLibDocumento->l20_datacria  = substr($olicitacao->l20_datacria,0,4);
  $oLibDocumento->l20_codigo    = $olicitacao->l20_codigo;
  $oLibDocumento->l30_portaria  = $olicitacao->l30_portaria;

  $result_munic=db_query("select * from db_config where codigo=$dbinstit");
  db_fieldsmemory($result_munic,0);



  $aParagrafos = $oLibDocumento->getDocParagrafos();
  //
  // for percorrendo os paragrafos do documento
  //
  //var_dump($aParagrafos);exit;
  foreach ($aParagrafos as $oParag) {
      if ($oParag->oParag->db02_tipo == "3" ){
            eval($oParag->oParag->db02_texto);
          }else{
            $oParag->writeText( $oPDF );
         }

  }


$oPDF->Output();


?>