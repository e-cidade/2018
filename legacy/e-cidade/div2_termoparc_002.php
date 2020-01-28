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


include(modification("libs/db_sql.php"));
include(modification("fpdf151/pdf1.php"));
include(modification("libs/db_utils.php"));
include(modification("libs/db_libdocumento.php"));
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if ( !isset($parcel) || $parcel == '' ) {
	db_redireciona('db_erros.php?fechar=true&db_erro=Parcelamento não encontrado!');
	exit;
}

//arreprescr k30_anulado is false

$alt = "5";
$exercicio = db_getsession("DB_anousu");
$borda = 1;
$bordat = 1;
$preenc = 0;
$TPagina = 57;
$xnumpre = 0;
$reparcelamento = false;
$sqlmunic = "select munic, cgc from db_config where codigo = ".db_getsession('DB_instit');
$resultmunic = db_query($sqlmunic);
$linhasmunic = pg_num_rows($resultmunic);
if($linhasmunic > 0){
	db_fieldsmemory($resultmunic,0);
}

$sqlVerificaInstit = "select v07_parcel,v07_desconto from termo where v07_parcel = $parcel and v07_instit = ".db_getsession('DB_instit');
$rsVerificaInstit  = db_query($sqlVerificaInstit);
if (pg_num_rows($rsVerificaInstit) == 0 ){
	db_redireciona('db_erros.php?fechar=true&db_erro=Parcelamento no. '.$parcel. ' não encontrado !');
	exit;
}else{
	db_fieldsmemory($rsVerificaInstit,0);
}

$sql  = " select termo.*, ";
$sql .= "        v03_descr, ";
$sql .= "        to_char(length(z01_cgccpf),'99') as leng, ";
$sql .= "        z01_numcgm,  ";
$sql .= "        z01_nome, ";
$sql .= "        case when trim(z01_cgccpf) = '' ";
$sql .= "             then '000000000' ";
$sql .= "        else z01_cgccpf ";
$sql .= "        end as z01_cgccpf,  ";
$sql .= "        z01_ender,  ";
$sql .= "        z01_bairro,  ";
$sql .= "        z01_munic,  ";
$sql .= "        z01_telef,  ";
$sql .= "        z01_compl, ";
$sql .= "        case when trim(z01_ident) = '' ";
$sql .= "             then '0000000000' ";
$sql .= "        else z01_ident ";
$sql .= "       end as z01_ident, ";
$sql .= "       z01_email,  ";
$sql .= "       z01_uf, ";
$sql .= "       z01_numero, ";
$sql .= "       case z01_estciv  ";
$sql .= "         when 1 then 'estado civil solteiro,' ";
$sql .= "         when 2 then 'estado civil casado,' ";
$sql .= "         when 3 then 'estado civil viúvo,' ";
$sql .= "         when 4 then 'estado civil divorciado,' ";
$sql .= "       else '' ";
$sql .= "       end as estciv,  ";
$sql .= "       z01_cep, ";
$sql .= "       nome, ";
$sql .= "       k40_descr as lei, ";
$sql .= "       v27_protprocesso ";
$sql .= "  from termo ";
$sql .= "       inner join cgm               on z01_numcgm = v07_numcgm ";
$sql .= "       left outer join termodiv     on v07_parcel = parcel ";
$sql .= "       left outer join divida       on coddiv = v01_coddiv ";
$sql .= "                                   and v01_instit = ".db_getsession('DB_instit') ;
$sql .= "       left outer join proced       on v03_codigo              = v01_proced ";
$sql .= "       left outer join db_usuarios  on db_usuarios.id_usuario  = termo.v07_login ";
$sql .= "       left join cadtipoparc        on k40_codigo              = v07_desconto ";
$sql .= "                            and k40_instit = ".db_getsession('DB_instit');
$sql .= "       left join termoprotprocesso  on v27_termo               = v07_parcel ";
$sql .= "                                   and k40_instit              = ".db_getsession('DB_instit');
$sql .= "  where v07_parcel = $parcel and v07_instit = ".db_getsession('DB_instit');

//die($sql);
//pdf
$result=db_query($sql);
if ( pg_numrows($result) == 0 ) {
	db_redireciona('db_erros.php?fechar=true&db_erro=Parcelamento no. '.$parcel. ' não encontrado!');
	exit;
}
db_fieldsmemory($result,0);
$responsavel = $z01_nome;
$numprecerto = $v07_numpre;
if ($leng == '14' ) {
	$cpf = db_formatar($z01_cgccpf,'cnpj');
} else {
	$cpf = db_formatar($z01_cgccpf,'cpf');
}

$sqlparag = "select db02_texto
from db_documento
inner join db_docparag on db03_docum = db04_docum
inner join db_tipodoc on db08_codigo  = db03_tipodoc
inner join db_paragrafo on db04_idparag = db02_idparag
where db03_tipodoc = 1017 and db03_instit = " . db_getsession("DB_instit")." order by db04_ordem ";

$resparag = db_query($sqlparag);

if ( pg_numrows($resparag) == 0 ) {
	//     $head1 = 'Departamento de Fazenda';
	$head1 = 'SECRETARIA DE FINANÇAS';
}else{
	db_fieldsmemory( $resparag, 0 );
	$head1 = $db02_texto;
}

$iFormaCorrecao = pg_result(db_query("select k03_separajurmulparc
		from numpref
		where k03_instit = ".db_getsession("DB_instit")."
		and k03_anousu = ".db_getsession("DB_anousu")),0,0);

$pdf = new PDF1(); // abre a classe
if(!defined('DB_BIBLIOT')){
	$pdf->Open(); // abre o relatorio
	$pdf->AliasNbPages(); // gera alias para as paginas
}
//$pdf->SetAutoPageBreak(false);
$pdf->SetAutoPageBreak('on',10);
$pdf->AddPage(); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);
$pdf->SetFont('Arial','',11);
/////// TEXTOS E ASSINATURAS
$extenso = $pdf->db_extenso($v07_valor);
$valorparc = db_formatar($v07_valor,'f');

$instit = db_getsession("DB_instit");

$sDadosLocalizacao  = "";
$sDadosLocalizacao .= " select distinct 1 as tipo, k00_matric as k00_origem ";
$sDadosLocalizacao .= " from divida.termo ";
$sDadosLocalizacao .= " inner join caixa.arrematric on v07_numpre = arrematric.k00_numpre ";
$sDadosLocalizacao .= " where v07_parcel = $parcel";
$sDadosLocalizacao .= " union ";
$sDadosLocalizacao .= " select distinct 2 as tipo, k00_inscr as k00_origem ";
$sDadosLocalizacao .= " from divida.termo ";
$sDadosLocalizacao .= " inner join caixa.arreinscr on v07_numpre = arreinscr.k00_numpre ";
$sDadosLocalizacao .= " where v07_parcel = $parcel";
$sDadosLocalizacao  = " select * from ( $sDadosLocalizacao ) as x order by tipo ";
$rsDadosLocalizacao = db_query($sDadosLocalizacao) or die($sDadosLocalizacao);
$dadoslocalizacao = "";
if ( pg_numrows($rsDadosLocalizacao) == 1 ) {
	if ( pg_result($rsDadosLocalizacao,0,"tipo") == 1 ) {
		$sBusca = "select loteloc.*, setorloc.* from cadastro.loteloc inner join cadastro.setorloc on j06_setorloc = j05_codigo inner join cadastro.iptubase on j01_idbql = j06_idbql where j01_matric = " . pg_result($rsDadosLocalizacao,0,"k00_origem");
		$rsBusca = db_query($sBusca) or die($sBusca);
		if ($rsBusca and pg_num_rows($rsBusca) > 0) {
			db_fieldsmemory($rsBusca,0);
			$dadoslocalizacao = "DADOS DE LOCALIZAÇÃO: $j05_codigoproprio - " . $j05_descr . "/$j06_quadraloc/$j06_lote";
		}
	}
}

////////relatorio
$numpar  = $v07_totpar;
$entrada = $v07_vlrent;
$vencent = $v07_datpri;
$vlrpar  = $v07_vlrpar;
$vencpar = $v07_dtvenc;
$pdf->SetFont('Arial','B',11);

$sqlrepar = " select retermo.*,
k00_matric as matric,
k00_inscr as inscr
from retermo
left outer join arrematric a on a.k00_numpre = v07_numpre
left outer join arreinscr  b on b.k00_numpre = v07_numpre
where 1=2 and v07_parcel = $parcel order by retermo.v07_dtlanc limit 1";
$result = db_query($sqlrepar);

$sql = "select * from termoreparc where v08_parcel = $parcel limit 1";
$result_reparc = db_query($sql) or die($sql);

if (pg_numrows($result_reparc) > 0) {
	$reparcelamento = true;

	// select que tras os reparcelamentos corrigindo os valores com fc_calculaold
	$sql  =	" select 1 as select,                                                                                     \n"; 
	$sql .= "        debitos_old.v08_parcelorigem,                                                                    \n";
	$sql .= "        debitos_old.k00_numpar  as v01_exerc,                                                            \n";
	$sql .= "        debitos_old.k03_tipo,                                                                            \n";
	$sql .= "        debitos_old.k00_tipo    as tipo,                                                                 \n";
	$sql .= "        debitos_old.k00_descr,                                                                           \n";
	$sql .= "        debitos_old.k00_valor   as valor,                                                                \n";
	$sql .= "        debitos_old.vlrcor      as vlrcor,                                                               \n";
	$sql .= "        debitos_old.vlrjuros    as juros,                                                                \n";
	$sql .= "        debitos_old.vlrmulta    as multa,                                                                \n";
	$sql .= "        debitos_old.vlrdesconto as desconto,                                                             \n";
	$sql .= "        debitos_old.k00_dtvenc  as v01_dtvenc,                                                           \n";
	$sql .= "        debitos_old.v07_numpre,                                                                          \n";
	$sql .= "      	 debitos_old.k00_numpre,                                                                          \n";
	$sql .= " 	     debitos_old.k00_numpar,                                                                          \n";
	$sql .= "        (select coalesce(k00_matric, 0)                                                                  \n"; 
	$sql .= "           from arrematric                                                                               \n";
	$sql .= "                inner join termo on v07_numpre = k00_numpre                                              \n";
	$sql .= "                where v07_parcel = v08_parcelorigem                                                      \n";
	$sql .= "                order by k00_perc desc limit 1) as matric,                                               \n";
	$sql .= "        (select coalesce(k00_inscr, 0)                                                                   \n";
	$sql .= "           from arreinscr                                                                                \n";
	$sql .= "                inner join termo on v07_numpre = k00_numpre                                              \n";
	$sql .= "          where v07_parcel = v08_parcelorigem                                                            \n";
	$sql .= "          order by k00_perc desc limit 1) as inscr,                                                      \n";
	$sql .= "        0 as contr,                                                                                      \n";
	$sql .= "        '' as nomematric,                                                                                \n";
	$sql .= "        '' as nomeinscr,                                                                                 \n";
	$sql .= "        '' as nomecontr,                                                                                 \n";
	$sql .= "        '' as v03_descr                                                                                  \n";
	$sql .= "   from (                                                                                                \n";
	$sql .= "          select distinct                                                                                \n";
	$sql .= "                 termoreparc.*,                                                                          \n";
	$sql .= "                 arretipo.*,                                                                             \n";
	$sql .= "                 coalesce(tipoparc.descjur,0) as descjur,                                                \n";
	$sql .= "                 coalesce(tipoparc.descmul,0) as descmul,                                                \n";
	$sql .= "                 coalesce(tipoparc.descvlr,0) as desccor,                                                \n";
	$sql .= "                 termoori.v07_numpre,                                                                    \n";
	$sql .= "                 arreold.k00_numcgm ,                                                                    \n";
	$sql .= "                 arreold.k00_receit ,                                                                    \n";
	$sql .= "                 arreold.k00_tipojm,                                                                     \n";
	$sql .= "                 arreold.k00_numpre ,                                                                    \n";
	$sql .= "                 arreold.k00_numpar ,                                                                    \n";
	$sql .= "                 arreold.k00_numtot ,                                                                    \n";
	$sql .= "                 arreold.k00_numdig ,                                                                    \n";
	$sql .= "                 arreold.k00_valor ,                                                                     \n";
	$sql .= "                 arreold.k00_dtvenc,                                                                     \n";
	$sql .= "                 arreoldcalc.k00_vlrcor as vlrcor,                                                       \n";
	$sql .= "                 arreoldcalc.k00_vlrjur as vlrjuros,                                                     \n";
	$sql .= "                 arreoldcalc.k00_vlrmul as vlrmulta,                                                     \n";
	$sql .= "                 arreoldcalc.k00_vlrdes +                                                                \n";
	if($iFormaCorrecao == 1){
		$sql .= "             ( arreoldcalc.k00_vlrjur * descjur / 100) + ( arreoldcalc.k00_vlrmul * descmul / 100) + ( (arreoldcalc.k00_vlrcor - arreoldcalc.k00_vlrhis) * descvlr / 100) as vlrdesconto, \n";
	} else {
		$sql .= "             ( arreoldcalc.k00_vlrjur * descjur / 100) + ( arreoldcalc.k00_vlrmul * descmul / 100) as vlrdesconto, \n";
	}
	$sql .= "                 (arreoldcalc.k00_vlrcor + arreoldcalc.k00_vlrjur + arreoldcalc.k00_vlrmul - arreoldcalc.k00_vlrdes) as total \n";
	$sql .= "            from termoreparc                                                                             \n";
	$sql .= "                 inner join termo termoori   on v08_parcelorigem         = termoori.v07_parcel           \n";
	$sql .= "                                            and termoori.v07_instit      = ".db_getsession('DB_instit')."\n";
	$sql .= "                 inner join arreold          on termoori.v07_numpre      = arreold.k00_numpre            \n";
	$sql .= "                 inner join arreoldcalc      on arreoldcalc.k00_numpre   = arreold.k00_numpre            \n";
	$sql .= "                                            and arreoldcalc.k00_numpar   = arreold.k00_numpar            \n";
	$sql .= "                                            and arreoldcalc.k00_receit   = arreold.k00_receit            \n";
	$sql .= "                 inner join arretipo         on arreold.k00_tipo         = arretipo.k00_tipo             \n";
	$sql .= "                 inner join cadtipo          on arretipo.k03_tipo        = cadtipo.k03_tipo              \n";
	$sql .= "                 inner join termo termoatual on termoatual.v07_parcel    = termoreparc.v08_parcel        \n";
	$sql .= "                 left  join cadtipoparc      on cadtipoparc.k40_codigo   = termoatual.v07_desconto       \n";
	$sql .= "                 left  join ( select *                                                                   \n";
	$sql .= "                                from tipoparc                                                            \n";
	$sql .= "                                     inner join cadtipoparc on tipoparc.cadtipoparc   = cadtipoparc.k40_codigo        \n";
	$sql .= "                                                           and cadtipoparc.k40_instit = ".db_getsession('DB_instit')."\n";
	$sql .= "                                     inner join termo       on termo.v07_desconto     = cadtipoparc.k40_codigo        \n";
	$sql .= "                                                           and termo.v07_instit       = ".db_getsession('DB_instit')."\n";
	$sql .= "                               where termo.v07_parcel = $parcel                                                       \n";
	$sql .= "                                 and termo.v07_instit = ".db_getsession('DB_instit')."                                \n";
	$sql .= "                                 and termo.v07_dtlanc between tipoparc.dtini and tipoparc.dtfim                       \n";
	$sql .= "                                 and termo.v07_totpar between 1 and tipoparc.maxparc order by maxparc limit 1 ) as tipoparc on tipoparc.cadtipoparc = cadtipoparc.k40_codigo \n";
	$sql .= "                 left  join cadtipoparcdeb   on cadtipoparc.k40_codigo      = cadtipoparcdeb.k41_cadtipoparc \n";
	$sql .= "                                            and cadtipoparcdeb.k41_arretipo = arreold.k00_tipo               \n";
	$sql .= "                                            and arreold.k00_dtvenc between k41_vencini and k41_vencfim       \n";
	$sql .= "                 left  join arrecad          on arrecad.k00_numpre          = termoatual.v07_numpre          \n";
	$sql .= "  where v08_parcel = $parcel  ) as debitos_old                                                               \n";

	$sql .= " union all \n";

	// select que tras parcelamentos de divida
	$sql .= " select 2 as select,                                                                                     \n";
	$sql .= "        0 as v08_parcelorigem,                                                                           \n";
	$sql .= "        divida.v01_exerc,                                                                                \n";
	$sql .= "        5 as k03_tipo,                                                                                   \n";
	$sql .= "        5 as tipo,                                                                                       \n";
	$sql .= "        (select k00_descr from arretipo where k00_tipo = 5 limit 1) as k00_descr,                        \n";
	$sql .= "        termodiv.valor,                                                                                  \n";
	$sql .= "        termodiv.vlrcor,                                                                                 \n";
	$sql .= "        termodiv.juros,                                                                                  \n";
	$sql .= "        termodiv.multa,                                                                                  \n";
	if ($iFormaCorrecao == 1) {
		$sql .= "    termodiv.vlrdesccor + termodiv.vlrdescjur + termodiv.vlrdescmul + termodiv.desconto as desconto,   \n";
	} else {
		$sql .= "    termodiv.vlrdesccor + termodiv.vlrdescjur + termodiv.vlrdescmul + termodiv.desconto as desconto,   \n";
	}
	$sql .= "        divida.v01_dtvenc,                                                                               \n";
	$sql .= "        termo.v07_numpre,                                                                                \n";
	$sql .= "        termo.v07_numpre as k00_numpre,                                                                  \n";
	$sql .= "   	 0 as k00_numpar,                                                                                   \n";
	$sql .= "        coalesce(arrematric.k00_matric,0) as matric,                                                     \n";
	$sql .= "        coalesce(arreinscr.k00_inscr,0)   as inscr,                                                      \n";
	$sql .= "        coalesce(arrecontr.k00_contr,0)   as contr,                                                      \n";
	$sql .= "        case when a.j01_numcgm is not null then (select z01_nome from cgm where z01_numcgm = a.j01_numcgm) end as nomematric, \n";
	$sql .= "        case when q02_numcgm   is not null then (select z01_nome from cgm where z01_numcgm = q02_numcgm)   end as nomeinscr,  \n";
	$sql .= "        case when b.j01_numcgm is not null then (select z01_nome from cgm where z01_numcgm = b.j01_numcgm) end as nomecontr,  \n";
	$sql .= "        v03_descr                                                                                        \n";
	$sql .= "   from termodiv                                                                                         \n";
	$sql .= "        inner join termo       on v07_parcel            = parcel                                         \n"; 
	$sql .= "                              and v07_instit            = ".db_getsession('DB_instit')."                 \n";
	$sql .= "        inner join	divida	  	on v01_coddiv            = coddiv                                         \n";
	$sql .= "                              and v01_instit            = ".db_getsession('DB_instit')."                 \n";
	$sql .= "        inner join	proced	  	on v01_proced            = v03_codigo                                     \n";
	$sql .= "        left  join	arrematric	on arrematric.k00_numpre = divida.v01_numpre                              \n";
	$sql .= "        left  join	iptubase a	on arrematric.k00_matric = a.j01_matric                                   \n";
	$sql .= "        left  join	arreinscr	on arreinscr.k00_numpre  = divida.v01_numpre                                \n";
	$sql .= "        left  join	issbase		on arreinscr.k00_inscr   = issbase.q02_inscr                                \n";
	$sql .= "        left  join	arrecontr	on arrecontr.k00_numpre  = divida.v01_numpre                                \n";
	$sql .= "        left  join	contrib	  	on arrecontr.k00_contr   = contrib.d07_contri                             \n";
	$sql .= "        left  join	iptubase b	on b.j01_matric          = contrib.d07_matric                             \n";
	$sql .= " where parcel = $parcel                                                                                  \n";

	$sql .= " union all \n";

	// select que tras parcelamentos de inicial
	$sql .= " select 3 as select,                                                                                     \n";
	$sql .= "        0 as v08_parcelorigem,                                                                           \n";
	$sql .= "        divida.v01_exerc,                                                                                \n";
	$sql .= "        18 as k03_tipo,                                                                                  \n"; // ver as colunas !!!
	$sql .= "        34 as tipo,                                                                                      \n";
	$sql .= "        (select k00_descr from arretipo where k00_tipo = 30 limit 1) as k00_descr,                       \n";
	$sql .= "        case when c.k00_vlrhis is not null then c.k00_vlrhis else divida.v01_vlrhis end as valor,        \n";
	$sql .= "        case when c.k00_vlrhis is not null then c.k00_vlrcor else divida.v01_vlrhis end as vlrcor,       \n";
	$sql .= "        coalesce(c.k00_vlrjur,0)  as juros,                                                              \n";
	$sql .= "        coalesce(c.k00_vlrmul,0)  as multa,                                                              \n";
	$sql .= "        coalesce(c.k00_vlrdes,0)  as desconto,                                                           \n";
	$sql .= "        divida.v01_dtvenc,                                                                               \n";
	$sql .= "        termo.v07_numpre,                                                                                \n";
	$sql .= "        termo.v07_numpre as k00_numpre,                                                                  \n";
	$sql .= "   	   divida.v01_numpar as k00_numpar,                                                                 \n";
	$sql .= "        coalesce(arrematric.k00_matric,0) as matric,                                                     \n";
	$sql .= "        coalesce(arreinscr.k00_inscr,0)   as inscr,                                                      \n";
	$sql .= "        coalesce(arrecontr.k00_contr,0)   as contr,                                                      \n";
	$sql .= "        case when a.j01_numcgm is not null then (select z01_nome from cgm where z01_numcgm = a.j01_numcgm) end as nomematric,\n";
	$sql .= "        case when q02_numcgm   is not null then (select z01_nome from cgm where z01_numcgm = q02_numcgm)   end as nomeinscr, \n";
	$sql .= "        case when b.j01_numcgm is not null then (select z01_nome from cgm where z01_numcgm = b.j01_numcgm) end as nomecontr, \n";
	$sql .= "        v03_descr                                                                                        \n";
	$sql .= "   from termoini                                                                                         \n";
	$sql .= "        inner join termo          on v07_parcel              = parcel                                    \n"; 
	$sql .= "                                 and v07_instit              = ".db_getsession('DB_instit')."            \n";
	$sql .= "        inner join inicialcert    on inicialcert.v51_inicial = termoini.inicial                          \n";
	$sql .= "        inner join certid         on certid.v13_certid       = inicialcert.v51_certidao                  \n"; 
	$sql .= "                                 and certid.v13_instit       = ".db_getsession('DB_instit')."            \n";
	$sql .= "        inner join certdiv        on certdiv.v14_certid      = certid.v13_certid                         \n";
	$sql .= "        inner join	divida	  	   on v01_coddiv              = v14_coddiv                                \n";
	$sql .= "                                 and v01_instit              = ".db_getsession('DB_instit')."            \n";
  $sql .= "        inner join	proced	  	   on v01_proced              = v03_codigo                                \n";
  $sql .= "        left  join arreoldcalc c  on k00_numpre = v01_numpre and k00_numpar = v01_numpar                 \n";
	$sql .= "        left  join	arrematric	   on arrematric.k00_numpre   = divida.v01_numpre                         \n";
	$sql .= "        left  join	iptubase a	   on arrematric.k00_matric   = a.j01_matric                              \n";
	$sql .= "        left  join	arreinscr	     on arreinscr.k00_numpre    = divida.v01_numpre                         \n";
	$sql .= "        left  join	issbase		     on arreinscr.k00_inscr     = issbase.q02_inscr                         \n";
	$sql .= "        left  join	arrecontr	     on arrecontr.k00_numpre    = divida.v01_numpre                         \n";
	$sql .= "        left  join	contrib	  	   on arrecontr.k00_contr     = contrib.d07_contri                        \n";
	$sql .= "        left  join	iptubase b	   on b.j01_matric            = contrib.d07_matric                        \n";
	$sql .= " where parcel = {$parcel}                                                                                \n";

	$sql2  = "  select v08_parcelorigem,          \n";
	$sql2 .= "         v01_exerc,                 \n";
	$sql2 .= "         k03_tipo,                  \n";
	$sql2 .= "         tipo,                      \n";
	$sql2 .= "         k00_descr,                 \n";
	$sql2 .= "         sum(valor)    as valor,    \n";
	$sql2 .= "         sum(vlrcor)   as vlrcor,   \n";
	$sql2 .= "         sum(juros)    as juros,    \n";
	$sql2 .= "         sum(multa)    as multa,    \n";
	$sql2 .= "         sum(desconto) as desconto, \n";
	$sql2 .= "         v01_dtvenc,                \n";
	$sql2 .= "         v07_numpre,                \n";
	$sql2 .= "         k00_numpre,                \n";
	$sql2 .= "         k00_numpar,                \n";
	$sql2 .= "         matric,                    \n";
	$sql2 .= "         inscr,                     \n";
	$sql2 .= "         contr,                     \n";
	$sql2 .= "         nomematric,                \n";
	$sql2 .= "         nomeinscr,                 \n";
	$sql2 .= "         nomecontr,                 \n";
	$sql2 .= "         v03_descr                  \n";
	$sql2 .= "    from ($sql) as x                \n";
	$sql2 .= "group by v08_parcelorigem,          \n";
	$sql2 .= "         v01_exerc,                 \n";
	$sql2 .= "         k03_tipo,                  \n";
	$sql2 .= "         tipo,                      \n";
	$sql2 .= "         k00_descr,                 \n";
	$sql2 .= "         v01_dtvenc,                \n";
	$sql2 .= "         v07_numpre,                \n";
	$sql2 .= "         k00_numpre,                \n";
	$sql2 .= " 	       k00_numpar,                \n";
	$sql2 .= "         matric,                    \n";
	$sql2 .= "         inscr,                     \n";
	$sql2 .= "         contr,                     \n";
	$sql2 .= "         nomematric,                \n";
	$sql2 .= "         nomeinscr,                 \n";
	$sql2 .= "         nomecontr,                 \n";
	$sql2 .= "         v03_descr                  \n";
	$sql2 .= " order by v08_parcelorigem,         \n";
	$sql2 .= "          v01_exerc,                \n";
	$sql2 .= "	        v07_numpre,               \n";
	$sql2 .= "	    		k00_numpar                \n";

  $sql = $sql2;
  
} else {

	if ( pg_numrows($result) > 0 ) {
		// se for reparcelamento ou diversos...
		if ( pg_result($result,0,'matric') > 0 ) {
			$numero = 'Matr. : '.pg_result($result,0,'matric');
		}else if ( pg_result($result,0,'inscr') > 0 ) {
			$numero = 'Inscr.: '.pg_result($result,0,'inscr');
		}else {
			$numero = 'Cgm : '.pg_result($result,0,'v07_numcgm');
		}
		$xnumpre = pg_result($result,0,'v07_numpre');

		$sql  = "select a.*, ";
		$sql .= "       a.k00_dtvenc as v01_dtvenc, ";
		$sql .= "      k00_numpar as v01_exerc, ";
		$sql .= "      cadtipo.k03_tipo, ";
		$sql .= "      arretipo.k00_descr, ";
		$sql .= "      dv09_descr as v03_descr, ";
		$sql .= "      coalesce(b.k00_matric) as matric, ";
		$sql .= "      coalesce(c.k00_inscr)  as inscr ";
		$sql .= " from arreold a ";
		$sql .= "      inner join diversos  on dv05_numpre = a.k00_numpre ";
		$sql .= "                          and dv05_instit = ".db_getsession('DB_instit')."";
		$sql .= "      inner join procdiver on dv05_procdiver = dv09_procdiver ";
		$sql .= "                          and dv09_instit = ".db_getsession('DB_instit')."";
		$sql .= "      inner join arretipo  on a.k00_tipo = arretipo.k00_tipo ";
		$sql .= "                          and arretipo.k00_instit = ".db_getsession('DB_instit');
		$sql .= "      inner join cadtipo   on arretipo.k03_tipo = cadtipo.k03_tipo ";
		$sql .= "      left outer join arrematric b  on b.k00_numpre = a.k00_numpre ";
		$sql .= "      left outer join arreinscr  c   on c.k00_numpre = a.k00_numpre ";
		$sql .= " where a.k00_numpre = $xnumpre ";
		$k00_descr = pg_result(db_query($sql),0,"k00_descr");
		$xtipo     = pg_result(db_query($sql),0,"k00_tipo");
		$k03_tipo  = pg_result(db_query($sql),0,"k03_tipo");

		if ($k03_tipo == 4){
			$sql1  = " select b.* ";
			$sql1 .= "   from arreold a ";
			$sql1 .= "        inner join arrematric c on c.k00_numpre = a.k00_numpre ";
			$sql1 .= "        inner join proprietario b on b.j01_matric = c.k00_matric ";
			$sql1 .= " where a.k00_numpre = $xnumpre limit 1";
			$tipo = 4;
			$setorquadralote = '';
		}else{
			if ($k03_tipo == 7){
				$tipo = 28;
				$sql1 = "select z01_nome from cgm where z01_numcgm = ".pg_result(db_query($sql),0,'k00_numcgm');
				$z01_nome = pg_result(db_query($sql1),0,"z01_nome");
			}else{
				$tipo = 21;
				$sql1 = "select z01_nome from cgm where z01_numcgm = ".pg_result(db_query($sql),0,'k00_numcgm');
				$z01_nome = pg_result(db_query($sql1),0,"z01_nome");
			}
		}
	}else {
		$sql  = " select * from (  ";
		$sql .= "	  select 1 as ordem, ";
		$sql .= " 			   arrecad.k00_tipo,  ";
		$sql .= " 			   k03_tipo  ";
		$sql .= " 	  from termo ";
		$sql .= " 			   inner join arrecad    on termo.v07_numpre = arrecad.k00_numpre ";
		$sql .= " 			   inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre ";
		$sql .= " 		                          and arreinstit.k00_instit = ".db_getsession('DB_instit');
		$sql .= " 			   inner join arretipo on arrecad.k00_tipo = arretipo.k00_tipo and arretipo.k00_instit = ".db_getsession('DB_instit') ;
		$sql .= "    where v07_parcel = $parcel and v07_instit = ".db_getsession('DB_instit') ;
		$sql .= " union ";
		$sql .= "    select 2 as ordem, ";
		$sql .= " 				  arrecant.k00_tipo,  ";
		$sql .= " 				  k03_tipo  ";
		$sql .= " 	   from termo ";
		$sql .= " 				  inner join arrecant   on termo.v07_numpre = arrecant.k00_numpre ";
		$sql .= " 				  inner join arreinstit on arreinstit.k00_numpre = arrecant.k00_numpre ";
		$sql .= " 			                         and arreinstit.k00_instit = ".db_getsession('DB_instit');
		$sql .= " 				  inner join arretipo on arrecant.k00_tipo = arretipo.k00_tipo and arretipo.k00_instit = ".db_getsession('DB_instit');
		$sql .= "  	  where v07_parcel = $parcel and v07_instit = ".db_getsession('DB_instit');
		$sql .= " union ";
		$sql .= "    select 3 as ordem, ";
		$sql .= " 				  arreold.k00_tipo,  ";
		$sql .= " 				  k03_tipo  ";
		$sql .= " 	   from termo ";
		$sql .= " 				  inner join arreold   on termo.v07_numpre = arreold.k00_numpre ";
		$sql .= " 				  inner join arreinstit on arreinstit.k00_numpre = arreold.k00_numpre ";
		$sql .= " 			                         and arreinstit.k00_instit = ".db_getsession('DB_instit');
		$sql .= " 				  inner join arretipo on arreold.k00_tipo = arretipo.k00_tipo and arretipo.k00_instit = ".db_getsession('DB_instit');
		$sql .= "  	  where v07_parcel = $parcel and v07_instit = ".db_getsession('DB_instit');
		$sql .= " ) as x order by ordem limit 1  ";
		$resarrecad = db_query($sql);
		if(pg_numrows($resarrecad)>0){
			$tipo     = pg_result($resarrecad,0,'k00_tipo');
			$k03_tipo = pg_result($resarrecad,0,'k03_tipo');
		}else{
			db_redireciona('db_erros.php?fechar=true&db_erro=Parcelas em aberto e/ou pagas não encontradas.');
			exit;
		}

		if ($k03_tipo == 13) { // parcelamento do foro
			$sql  = "select distinct ";
			$sql .= "       x.z01_nome as nomematric, ";
			$sql .= "       x.v03_descr,  ";
			$sql .= "       arreold.*,  ";
			$sql .= "       k00_descr, ";
			$sql .= "       k00_matric as matric,  ";
			$sql .= "       k00_inscr as inscr, ";
			$sql .= "       coalesce(vlrdesccor,0) as vlrdesccor, ";
			$sql .= "       coalesce(vlrdescjur,0) as vlrdescjur, ";
			$sql .= "       coalesce(vlrdescmul,0) as vlrdescmul, ";
			$sql .= "       case when x.v01_exerc = 0 then arreold.k00_numpar else x.v01_exerc end as v01_exerc, ";
			$sql .= "       x.v01_dtvenc ";
			$sql .= "  from (	select distinct	 ";
			$sql .= "                case when v03_descr is null then 'Parcelamento : '||termo2.v07_parcel else v03_descr ";
			$sql .= "                end as v03_descr, ";
			$sql .= "                z01_nome, ";
			$sql .= "                vlrdesccor, ";
			$sql .= "                vlrdescjur, ";
			$sql .= "                vlrdescmul, ";
			$sql .= "                case when termo2.v07_numpre is not null then termo2.v07_numpre  ";
			$sql .= "                     else divida.v01_numpre  ";
			$sql .= "                end as numpre, ";
			$sql .= "                case when termo2.v07_numpre is not null then 0 ";
			$sql .= "                     else divida.v01_numpar ";
			$sql .= "                end as numpar, ";
			$sql .= "                case when termo2.v07_numpre is not null then 0 ";
			$sql .= "                     else divida.v01_exerc ";
			$sql .= "                end as v01_exerc, v01_dtvenc ";
			$sql .= "           from termo ";
			$sql .= "                inner join termoini         	on termoini.parcel         = termo.v07_parcel ";
			$sql .= "                inner join inicial	          on inicial.v50_inicial     = termoini.inicial ";
			$sql .= "                                            and inicial.v50_instit      = ".db_getsession('DB_instit');
			$sql .= "                inner join inicialcert	      on inicialcert.v51_inicial = inicial.v50_inicial ";
			$sql .= "                inner join certid          	on certid.v13_certid       = inicialcert.v51_certidao ";
			$sql .= "                                            and certid.v13_instit       = ".db_getsession('DB_instit');
			$sql .= "                left outer join certter	    on certter.v14_certid      = inicialcert.v51_certidao ";
			$sql .= "                left outer join certdiv	    on certdiv.v14_certid      = inicialcert.v51_certidao ";
			$sql .= "                left outer join termo termo2 on certter.v14_parcel      = termo2.v07_parcel ";
			$sql .= "                                            and termo2.v07_instit       = ".db_getsession('DB_instit') ;
			$sql .= "                left outer join divida  	    on divida.v01_coddiv       = certdiv.v14_coddiv ";
			$sql .= "                                            and divida.v01_instit       = ".db_getsession('DB_instit');
			$sql .= "                left outer join proced	      on proced.v03_codigo       = divida.v01_proced ";
			$sql .= "                inner join cgm	         	    on termo.v07_numcgm        = z01_numcgm ";
			$sql .= "        where termo.v07_parcel = $parcel ) as x  ";
			$sql .= "    inner join arreold        		on k00_numpre = x.numpre ";
			$sql .= "                                and (case when x.numpar > 0 then k00_numpar = x.numpar else true end ) ";
			$sql .= "    inner join arretipo          on arreold.k00_tipo = arretipo.k00_tipo ";
			$sql .= "                                and arretipo.k00_instit = ".db_getsession('DB_instit');
			$sql .= "    left outer join arrematric 	on arrematric.k00_numpre = arreold.k00_numpre ";
			$sql .= "    left outer join arreinscr  	on arreinscr.k00_numpre  = arreold.k00_numpre ";
			$sql .= "    order by v01_dtvenc asc ";
		}else if ($k03_tipo == 16) { // parcelamento de diversos

			$sql  = "select distinct ";
			$sql .= "       x.z01_nome as nomematric, ";
			$sql .= "       x.v03_descr,  ";
			$sql .= "       arreold.*,  ";
			$sql .= "       k00_descr, ";
			$sql .= "       k00_matric as matric,  ";
			$sql .= "       k00_inscr as inscr, ";
			$sql .= "       coalesce(vlrdescjur,0) as vlrdescjur, ";
			$sql .= "       coalesce(vlrdescmul,0) as vlrdescmul, ";
			$sql .= "       v01_exerc ";
			$sql .= "  from (	select distinct	 ";
			$sql .= "                dv09_descr as v03_descr, ";
			$sql .= "                z01_nome, ";
			$sql .= "                dv10_vlrdescjur as vlrdescjur, ";
			$sql .= "                dv10_vlrdescmul as vlrdescmul, ";
			$sql .= "                diversos.dv05_numpre as numpre, ";
			$sql .= "                0 as numpar, ";
			$sql .= "                0 as v01_exerc ";
			$sql .= "           from termo ";
			$sql .= "                inner join termodiver on termodiver.dv10_parcel   = termo.v07_parcel ";
			$sql .= "                inner join diversos   on termodiver.dv10_coddiver = dv05_coddiver ";
			$sql .= "                                     and dv05_instit = ".db_getsession('DB_instit')."";
			$sql .= "                left  join procdiver  on procdiver.dv09_procdiver = diversos.dv05_procdiver ";
			$sql .= "                                     and dv09_instit = ".db_getsession('DB_instit')."";
			$sql .= "                inner join cgm	       on termo.v07_numcgm        = z01_numcgm ";
			$sql .= "        where termo.v07_parcel = $parcel and termo.v07_instit = ".db_getsession('DB_instit')." ) as x  ";
			$sql .= "    inner join arreold        		on k00_numpre = x.numpre ";
			$sql .= "                                and (case when x.numpar > 0 then k00_numpar = x.numpar else true end ) ";
			$sql .= "    inner join arretipo          on arreold.k00_tipo = arretipo.k00_tipo and arretipo.k00_instit = ".db_getsession('DB_instit');
			$sql .= "    left outer join arrematric 	on arrematric.k00_numpre = arreold.k00_numpre ";
			$sql .= "    left outer join arreinscr  	on arreinscr.k00_numpre  = arreold.k00_numpre ";

		}else if ($k03_tipo == 17 ) { // parcelamento de contribuicao de melhorias
			$sql  = " select  ";
			$sql .= "        coalesce(termocontrib.vlrdescjur,0) as vlrdescjur,";
			$sql .= "        coalesce(termocontrib.vlrdescmul,0) as vlrdescmul,  ";
			$sql .= "        arreoldcalc.k00_vlrcor as vlrcor, ";
			$sql .= "        arreoldcalc.k00_vlrjur as vlrjuros, ";
			$sql .= "        arreoldcalc.k00_vlrmul as vlrmulta, ";
			$sql .= "        (arreoldcalc.k00_vlrcor + arreoldcalc.k00_vlrjur + arreoldcalc.k00_vlrmul - arreoldcalc.k00_vlrdes) as total, ";
			$sql .= "		  	 extract(year from arreold.k00_dtoper) as v01_exerc, ";
			$sql .= "		  	 'Contribuicao - '||d09_contri as v03_descr, ";
			$sql .= "		  	 arreold.k00_tipo, ";
			$sql .= "		  	 arreold.k00_dtvenc as v01_dtvenc, ";
			$sql .= "		  	 arreold.k00_numpre as k00_numpre, ";
			$sql .= "		 	   arreold.k00_numpar as k00_numpar, ";
			$sql .= "		 	   arreold.k00_valor as valor, ";
			$sql .= "        arretipo.k00_descr, ";
			$sql .= "        coalesce(arrematric.k00_matric,0) as matric, ";
			$sql .= "        coalesce(arreinscr.k00_inscr,0) as inscr, ";
			$sql .= "        coalesce(arrecontr.k00_contr,0) as contr, ";
			$sql .= "        case when a.j01_numcgm is not null ";
			$sql .= "             then (select z01_nome from cgm where z01_numcgm = a.j01_numcgm) ";
			$sql .= "        end as nomematric, ";
			$sql .= "        case when q02_numcgm is not null ";
			$sql .= "             then (select z01_nome from cgm where z01_numcgm = q02_numcgm) ";
			$sql .= "        end as nomeinscr ";
			$sql .= "   from termocontrib ";
			$sql .= "        inner join	termo       on v07_parcel             = parcel ";
			$sql .= "                              and v07_instit             = ".db_getsession('DB_instit');
			$sql .= "        inner join	contricalc  on d09_sequencial         = contricalc ";
			$sql .= "        inner join	arreold		  on d09_numpre             = arreold.k00_numpre ";
			$sql .= "        inner join arreoldcalc on arreoldcalc.k00_numpre = arreold.k00_numpre ";
			$sql .= "                              and arreoldcalc.k00_numpar = arreold.k00_numpar ";
			$sql .= "                              and arreoldcalc.k00_receit = arreold.k00_receit ";
			$sql .= "        inner join arretipo    on arreold.k00_tipo       = arretipo.k00_tipo ";
			$sql .= "                              and arretipo.k00_instit    = ".db_getsession('DB_instit');
			$sql .= "        left  join	arrematric  on arrematric.k00_numpre  = contricalc.d09_numpre ";
			$sql .= "        left  join	iptubase a  on arrematric.k00_matric  = a.j01_matric ";
			$sql .= "        left  join	arreinscr	  on arreinscr.k00_numpre   = contricalc.d09_numpre ";
			$sql .= "        left  join	issbase		  on arreinscr.k00_inscr    = issbase.q02_inscr ";
			$sql .= "        left  join	arrecontr	  on arrecontr.k00_numpre   = contricalc.d09_numpre ";
			$sql .= " where parcel = $parcel";
		}else {
			$sql  = " select  distinct ";
			$sql .= "        coalesce(termodiv.vlrdesccor,0) + coalesce(termodiv.vlrdescjur,0) + coalesce(termodiv.vlrdescmul,0) + coalesce(desconto,0) as desconto,";
			$sql .= "        divida.*, ";
			$sql .= "		  	 divida.v01_numpre as k00_numpre, ";
			$sql .= "		 	   divida.v01_numpar as k00_numpar, ";
			$sql .= "        v03_descr, ";
			$sql .= "        termodiv.*, ";
			$sql .= "        arretipo.k00_descr, ";
			$sql .= "        coalesce(arrematric.k00_matric,0) as matric, ";
			$sql .= "        coalesce(arreinscr.k00_inscr,0) as inscr, ";
			$sql .= "        coalesce(arrecontr.k00_contr,0) as contr, ";
			$sql .= "        case when a.j01_numcgm is not null ";
			$sql .= "             then (select z01_nome from cgm where z01_numcgm = a.j01_numcgm) ";
			$sql .= "        end as nomematric, ";
			$sql .= "        case when q02_numcgm is not null ";
			$sql .= "             then (select z01_nome from cgm where z01_numcgm = q02_numcgm) ";
			$sql .= "        end as nomeinscr, ";
			$sql .= "        case when b.j01_numcgm is not null ";
			$sql .= "             then (select z01_nome from cgm where z01_numcgm = b.j01_numcgm) ";
			$sql .= "        end as nomecontr ";
			$sql .= "   from termodiv  ";
			$sql .= "        inner join	divida		on v01_coddiv = coddiv ";
			$sql .= "                            and v01_instit = ".db_getsession('DB_instit');
			$sql .= "        inner join	arreold		on v01_numpre = arreold.k00_numpre ";
			$sql .= "                            and v01_numpar = arreold.k00_numpar ";
			$sql .= "                            and arreold.k00_valor > 0 ";
			$sql .= "        inner join arretipo  on arreold.k00_tipo = arretipo.k00_tipo ";
			$sql .= "                            and arretipo.k00_instit = ".db_getsession('DB_instit');
			$sql .= "        inner join	proced		on v01_proced = v03_codigo ";
			$sql .= "        left outer join	arrematric on arrematric.k00_numpre = divida.v01_numpre ";
			$sql .= "        left outer join	iptubase a on arrematric.k00_matric = a.j01_matric ";
			$sql .= "        left outer join	arreinscr	 on arreinscr.k00_numpre  =  divida.v01_numpre ";
			$sql .= "        left outer join	issbase		 on arreinscr.k00_inscr = issbase.q02_inscr ";
			$sql .= "        left outer join	arrecontr	 on arrecontr.k00_numpre  =  divida.v01_numpre ";
			$sql .= "        left outer join	contrib		 on arrecontr.k00_contr  =  contrib.d07_contri ";
			$sql .= "        left outer join	iptubase b on b.j01_matric = contrib.d07_matric ";
			$sql .= " where parcel = {$parcel} order by v01_dtvenc";


			$tipo = 0;
			if ( pg_result(db_query($sql),0,'matric') > 0 ) {
				$numero = 'Matr. : '.pg_result(db_query($sql),0,'matric');
			}elseif ( pg_result(db_query($sql),0,'inscr') > 0 ) {
				$numero = 'Inscr. : '.pg_result(db_query($sql),0,'inscr');
			}else {
				$numero = 'Cgm : '.pg_result(db_query($sql),0,'v01_numcgm');
			}
		}
	}
}

$result = db_query($sql) or die("Ocorreu um erro ao efetuar o seguinte SQL:<br><br>{$sql}");
db_fieldsmemory($result,0);

if ( isset($numprecerto) ) {

	$sqlnomedeb  = "select case when ( select arrecad.k00_numcgm                                                               ";
	$sqlnomedeb .= "                     from arrecad                                                                          ";
	$sqlnomedeb .= "                    where arrecad.k00_numpre  = $numprecerto limit 1) is null                              ";
	$sqlnomedeb .= "            then ( select arrecant.k00_numcgm                                                              ";
	$sqlnomedeb .= "                     from arrecant                                                                         ";
	$sqlnomedeb .= "                    where arrecant.k00_numpre = $numprecerto limit 1)                                      ";
	$sqlnomedeb .= "            else ( select arrecad.k00_numcgm                                                               ";
	$sqlnomedeb .= "                     from arrecad                                                                          ";
	$sqlnomedeb .= "                    where arrecad.k00_numpre  = $numprecerto limit 1) end as cgm,                          ";
	$sqlnomedeb .= "       z01_nome,                                                                                           ";
	$sqlnomedeb .= "       z01_cgccpf,                                                                                         ";
	$sqlnomedeb .= "       z01_ident,                                                                                          ";
	$sqlnomedeb .= "       z01_ender,                                                                                          ";
	$sqlnomedeb .= "       z01_bairro,                                                                                         ";
	$sqlnomedeb .= "       z01_numero,                                                                                         ";
	$sqlnomedeb .= "       z01_compl,                                                                                          ";
	$sqlnomedeb .= "       z01_munic,                                                                                          ";
	$sqlnomedeb .= "       z01_uf,                                                                                             ";
	$sqlnomedeb .= "       to_char(length(z01_cgccpf),'99') as leng                                                            ";
	$sqlnomedeb .= "  from cgm                                                                                                 ";
	$sqlnomedeb .= " where z01_numcgm = ( select case when ( select arrecad.k00_numcgm                                         ";
	$sqlnomedeb .= "                                           from arrecad                                                    ";
	$sqlnomedeb .= "                                          where arrecad.k00_numpre  = {$numprecerto} limit 1) is null      ";
	$sqlnomedeb .= "                                  then ( select arrecant.k00_numcgm                                        ";
	$sqlnomedeb .= "                                           from arrecant                                                   ";
	$sqlnomedeb .= "                                          where arrecant.k00_numpre = {$numprecerto} limit 1)              ";
	$sqlnomedeb .= "                                  else ( select arrecad.k00_numcgm                                         ";
	$sqlnomedeb .= "                                           from arrecad                                                    ";
	$sqlnomedeb .= "                                          where arrecad.k00_numpre  = {$numprecerto} limit 1) end ) limit 1; ";

} else {

	$sqlnomedeb  = "select k00_numcgm,                               ";
	$sqlnomedeb .= "       z01_nome,                                 ";
	$sqlnomedeb .= "       z01_cgccpf,                               ";
	$sqlnomedeb .= "       z01_ident,                                ";
	$sqlnomedeb .= "       z01_ender,                                ";
	$sqlnomedeb .= "       z01_bairro,                               ";
	$sqlnomedeb .= "       z01_numero,                               ";
	$sqlnomedeb .= "       z01_compl,                                ";
	$sqlnomedeb .= "       z01_munic,                                ";
	$sqlnomedeb .= "       z01_uf,                                   ";
	$sqlnomedeb .= "       to_char(length(z01_cgccpf),'99') as leng  ";
	$sqlnomedeb .= "  from arreold                                   ";
	$sqlnomedeb .= "       inner join cgm on k00_numcgm = z01_numcgm ";
	$sqlnomedeb .= " where k00_numpre = {$xnumpre}                   ";
	$sqlnomedeb .= " limit 1                                         ";
}

if ($matric > 0 ) {

	$sqlnomedeb  = "select proprietario.z01_cgmpri as z01_numcgm,                                   ";
	$sqlnomedeb .= "       cgm.z01_ident,                                                           ";
	$sqlnomedeb .= "       cgm.z01_ender,                                                           ";
	$sqlnomedeb .= "       cgm.z01_bairro,                                                          ";
	$sqlnomedeb .= "       cgm.z01_numero,                                                          ";
	$sqlnomedeb .= "       cgm.z01_compl,                                                           ";
	$sqlnomedeb .= "       cgm.z01_munic,                                                           ";
	$sqlnomedeb .= "       cgm.z01_uf,                                                              ";
	$sqlnomedeb .= "       proprietario.z01_nome,                                                   ";
	$sqlnomedeb .= "       proprietario.z01_cgccpf,                                                 ";
	$sqlnomedeb .= "       to_char(length(proprietario.z01_cgccpf),'99') as leng                    ";
	$sqlnomedeb .= "  from proprietario                                                             ";
	$sqlnomedeb .= "       inner join protocolo.cgm on proprietario.z01_cgmpri = cgm.z01_numcgm     ";
	$sqlnomedeb .= " where proprietario.j01_matric = {$matric}                                      ";

}elseif ( $inscr > 0 ) {

	$sqlnomedeb  = "select cgm.z01_numcgm,                                ";
	$sqlnomedeb .= "       cgm.z01_ident,                                 ";
	$sqlnomedeb .= "       cgm.z01_ender,                                 ";
	$sqlnomedeb .= "       cgm.z01_bairro,                                ";
	$sqlnomedeb .= "       cgm.z01_numero,                                ";
	$sqlnomedeb .= "       cgm.z01_compl,                                 ";
	$sqlnomedeb .= "       cgm.z01_munic,                                 ";
	$sqlnomedeb .= "       cgm.z01_uf,                                    ";
	$sqlnomedeb .= "       cgm.z01_nome,                                  ";
	$sqlnomedeb .= "       cgm.z01_cgccpf,                                ";
	$sqlnomedeb .= "       to_char(length(cgm.z01_cgccpf),'99') as leng   ";
	$sqlnomedeb .= "  from issbase                                        ";
	$sqlnomedeb .= "       inner join cgm on q02_numcgm = z01_numcgm      ";
	$sqlnomedeb .= " where q02_inscr = {$inscr}                           ";

}

$resultnomedeb = db_query($sqlnomedeb);
if ( pg_numrows($resultnomedeb) == 0 ) {

	db_redireciona('db_erros.php?fechar=true&db_erro=Numpre não encontrado.');
	exit;
}
if ( pg_numrows($result) == 0 ) {
	db_redireciona('db_erros.php?fechar=true&db_erro=Parcelamento no. '.$parcel. ' não Encontrado na Dívida.');
	exit;
}
if (pg_result($resultnomedeb,0,"leng") == '14' ) {
	$xcpf = db_formatar(pg_result($resultnomedeb,0,"z01_cgccpf"),'cnpj');
} else {
	$xcpf = db_formatar(pg_result($resultnomedeb,0,"z01_cgccpf"),'cpf');
}

$cpfcnpj_contrib  = $xcpf;
$rg_contrib       = pg_result($resultnomedeb,0,"z01_ident");
$numcgm_contrib   = @pg_result($resultnomedeb,0,"z01_numcgm");
$nome_contrib     = pg_result($resultnomedeb,0,"z01_nome");
$endereco_contrib = pg_result($resultnomedeb,0,"z01_ender") . "," . pg_result($resultnomedeb,0,"z01_numero") . "/" . pg_result($resultnomedeb,0,"z01_compl") . " - " . pg_result($resultnomedeb,0,"z01_bairro");
$cidade_contrib   = pg_result($resultnomedeb,0,"z01_munic") . "-" . pg_result($resultnomedeb,0,"z01_uf");

if ( $tipo == 21 ) {
	$nomedeb = 'Reparcelamento de Dívida Ativa em débitos de '.trim(pg_result($resultnomedeb,0,"z01_nome")).' CPF/CNPJ '.$xcpf;
	$exerc = "PARCELA";
}else if ( $tipo == 4 ){
	$nomedeb = 'Contribuição de Melhorias em débitos de '.trim(pg_result($resultnomedeb,0,"z01_nome")).' CPF/CNPJ '.$xcpf;
	$exerc = "PARCELA";
}else if ( $tipo == 28 ){
	$nomedeb = 'Parcelamento de diversos em débitos de '.trim(pg_result($resultnomedeb,0,"z01_nome")).' CPF/CNPJ '.$xcpf;
	$exerc = "PARCELA";
}else {

	if ( $k03_tipo == 13 ) {
		if ( pg_result($result,0,"matric") > 0 ) {
			$nomedeb = 'Parcelamento do Foro em débitos de '.trim(pg_result($resultnomedeb,0,"z01_nome")).' CPF/CNPJ '.$xcpf;
		} else if ( pg_result($result,0,"inscr") > 0 ) {
			$nomedeb = 'Parcelamento do Foro em débitos de '.trim(pg_result($resultnomedeb,0,"z01_nome")).' CPF/CNPJ '.$xcpf;
		} else {
			$nomedeb =  'Parcelamento do Foro em débitos de '.trim(pg_result($resultnomedeb,0,"z01_nome")).' CPF/CNPJ '.$xcpf;
		}
	} else {
	  if ( $k03_tipo == 6 ) {
      if ( pg_result($result,0,"matric") > 0 ) {
        $nomedeb = 'Divida Ativa por matricula em debitos de '.trim(pg_result($resultnomedeb,0,"z01_nome")).' CPF/CNPJ '.$xcpf;
      } else if ( pg_result($result,0,"inscr") > 0 ) {
        $nomedeb = 'Dívida Ativa por inscrição em débitos de '.trim(pg_result($resultnomedeb,0,"z01_nome")).' CPF/CNPJ '.$xcpf;
      } else if ( pg_result($result,0,"contr") > 0 ) {
        $nomedeb =  'Contribuição de Melhorias em débitos de '.trim(pg_result($resultnomedeb,0,"z01_nome")).' CPF/CNPJ '.$xcpf;
      } else {
        $nomedeb =  'Divida Ativa por nome em débitos de '.trim(pg_result($resultnomedeb,0,"z01_nome")).' CPF/CNPJ '.$xcpf;
      }
    } else {
      if ( pg_result($result,0,"matric") > 0 ) {
        $nomedeb = ' '.trim(pg_result($resultnomedeb,0,"z01_nome")).' CPF/CNPJ '.$xcpf;
      } else if ( pg_result($result,0,"inscr") > 0 ) {
        $nomedeb = 'Dívida Ativa em débitos de '.trim(pg_result($resultnomedeb,0,"z01_nome")).' CPF/CNPJ '.$xcpf;
      } else if ( pg_result($result,0,"contr") > 0 ) {
        $nomedeb =  'Contribuição de Melhorias em débitos de '.trim(pg_result($resultnomedeb,0,"z01_nome")).' CPF/CNPJ '.$xcpf;
      } else {
			  $nomedeb =  'debitos por nome em débitos de '.trim(pg_result($resultnomedeb,0,"z01_nome")).' CPF/CNPJ '.$xcpf;
      }
    }
	}
	$exerc = "EXERC";
}


$sqltexto  =" select k40_db_documento,                                              ";
$sqltexto .="        v07_desconto                                                   ";
$sqltexto .="   from termo                                                          ";
$sqltexto .="        inner join cadtipoparc  on k40_codigo       = v07_desconto     ";
$sqltexto .="        inner join db_documento on k40_db_documento = db03_docum       ";
$sqltexto .="  where v07_parcel  = {$v07_parcel}                                    ";
$resulttexto = db_query($sqltexto);
$linhastexto = pg_num_rows($resulttexto);
if ($linhastexto > 0 ) {
	db_fieldsmemory($resulttexto,0);

	$sqlparag  = "select db04_idparag                                           ";
	$sqlparag .= "  from db_documento                                           ";
	$sqlparag .= "       inner join db_docparag  on db04_docum   = db03_docum   ";
	$sqlparag .= "       inner join db_paragrafo on db02_idparag = db04_idparag ";
	$sqlparag .= " where db03_docum = {$k40_db_documento}                       ";
	$resultparag = db_query($sqlparag);
	$linhasparag = pg_num_rows($resultparag);
	if($linhasparag==0){
		db_redireciona('db_erros.php?fechar=true&db_erro=Não encontrado paragrafo cadastrado para o documento '.$k40_db_documento.' !');
		exit;
	}

} else {
	db_redireciona('db_erros.php?fechar=true&db_erro=Não encontrado documento cadastrado para regra de parcelamento ('.$v07_desconto.')!');
	exit;
}


$objteste = new libdocumento(1700,$k40_db_documento);
$objteste->getParagrafos();
$parag = $objteste->aParagrafos;
$pdf->SetFont('Arial', '', 10);
foreach ($parag as $chave ) {

	if($chave->db02_alinha==1){
		$alinhamento = "J";
	}elseif($chave->db02_alinha==2){
		$alinhamento = "C";
	}elseif($chave->db02_alinha==3){
		$alinhamento = "R";
	}elseif($chave->db02_alinha==4){
		$alinhamento = "L";
	}else{
		$alinhamento = "J";
	}

	if(strtoupper($chave->db02_descr) == "TITULO_PARCELAMENTO"){
		$pdf->SetFont('Arial', 'B', 10);
		$pdf->MultiCell(180, 6,"        ".$objteste->geratexto($chave->db02_texto),0,$alinhamento);
		$pdf->cell(180, $alt,"",0, 1, "C", 0);
	}elseif(strtoupper($chave->db02_descr) == "TABELA_VALORES"){

		$linha = 20;
		$Tv01_vlrhis = 0;
		$Tv01_valor  = 0;
		$Tmulta      = 0;
		$Tjuros      = 0;
		$Tdesconto   = 0;
		$Tv01_valor  = 0;
		$Total       = 0;

		$v01_vlrhis = 0;
		$v01_dtvenc = 0;
		$valor      = 0;
		$multa      = 0;
		$juros      = 0;
		$desconto   = 0;
		$vlrhis     = 0;
		$k00_dtvenc = 0;
		$vlrhis     = 0;
		$vlrmulta   = 0;
		$vlrjuros   = 0;
		$vlrdesccor = 0;
		$vlrdescjur = 0;
		$vlrdescmul = 0;

		$pdf->SetFont('Arial','B',11);
		$pdf->MultiCell(0,8,$nomedeb,0,1,0,0);
		$num = pg_numrows($result);
		//######################## começa a tabela ##########################
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(15,4,'MAT/INSC',1,0,"C",1);
		$pdf->Cell(15,4,$exerc,1,0,"C",1);
		$pdf->Cell(15,4,"VENC.",1,0,"C",1);
		$pdf->Cell(53,4,"PROCEDÊNCIA",1,0,"C",1);
		$pdf->Cell(18,4,"HISTÓRICO",1,0,"C",1);
		$pdf->Cell(18,4,"CORRIGIDO",1,0,"C",1);
		$pdf->Cell(18,4,"MULTA",1,0,"C",1);
		$pdf->Cell(18,4,"JUROS",1,0,"C",1);
		$pdf->Cell(20,4,"TOTAL",1,1,"C",1);

		$np = 0;
		$npa = 0;
		$primeiro  = true;
		$arrTipo   = Array();
		$V = '';
		$arrTotHis = 0;
		$arrTotVar = 0;
		$arrTotJur = 0;
		$arrTotMul = 0;
		$arrTotDes = 0;

		for($i=0;$i<$num;$i++) {
			if($pdf->GetY() > ( $pdf->h - 30 )){
				$linha = 0;
				$pdf->AddPage();
				$pdf->SetFont('Arial','B',7);
				$pdf->Cell(15,4,'MAT/INSC',1,0,"C",1);
				$pdf->Cell(15,4,$exerc,1,0,"C",1);
				$pdf->Cell(15,4,"VENC.",1,0,"C",1);
				$pdf->Cell(53,4,"PROCEDÊNCIA",1,0,"C",1);
				$pdf->Cell(18,4,"HISTÓRICO",1,0,"C",1);
				$pdf->Cell(18,4,"CORRIGIDO",1,0,"C",1);
				$pdf->Cell(18,4,"MULTA",1,0,"C",1);
				$pdf->Cell(18,4,"JUROS",1,0,"C",1);
				// $pdf->Cell(18,4,"DESCONTO",1,0,"C",1);
				$pdf->Cell(20,4,"TOTAL",1,1,"C",1);
			}
			db_fieldsmemory($result,$i);
			if ( ($xnumpre > 0 or $k03_tipo == 13 or $k03_tipo == 16 or $k03_tipo == 17) && $reparcelamento == false) {
				$desconto = 0;
				$dtlanc = mktime(0,0,0,substr($v07_dtlanc,5,2),substr($v07_dtlanc,8,2),substr($v07_dtlanc,0,4));

				$sqlArreoldCalc  = "select min(k00_dtvenc) as k00_dtvenc, ";
				$sqlArreoldCalc .= "       sum(k00_valor)  as vlrhis, ";
				$sqlArreoldCalc .= "       sum(k00_vlrcor) as vlrcor, ";
				$sqlArreoldCalc .= "       sum(k00_vlrjur) as vlrjuros, ";
				$sqlArreoldCalc .= "       sum(k00_vlrmul) as vlrmulta  ";
				$sqlArreoldCalc .= "  from arreold ";
				$sqlArreoldCalc .= "       left join arreoldcalc  on arreoldcalc.k00_numpre = arreold.k00_numpre ";
				$sqlArreoldCalc .= "                             and arreoldcalc.k00_numpar = arreold.k00_numpar ";
				$sqlArreoldCalc .= "                             and arreoldcalc.k00_receit = arreold.k00_receit ";
				$sqlArreoldCalc .= "                             and arreoldcalc.k00_hist   = arreold.k00_hist   ";
				$sqlArreoldCalc .= " where arreold.k00_numpre = $k00_numpre ";
				$sqlArreoldCalc .= "   and arreold.k00_numpar = $k00_numpar ";

				$resArreoldCalc = db_query($sqlArreoldCalc);
				if($resArreoldCalc != false){
					db_fieldsmemory($resArreoldCalc, 0);
				}
				$sqlTotal = "";
				if ($k03_tipo == 13){
					$sqlTotal  = "select ( coalesce(vlrdesccor,0) / (case when coalesce(vlrcor,0) = 0 then 1 else coalesce(vlrcor,0) end) ) as percdesccor, ";
					$sqlTotal .= "       ( coalesce(vlrdescjur,0) / (case when coalesce(juros,0) = 0 then 1 else coalesce(juros,0) end) ) as percdescjur, ";
					$sqlTotal .= "       ( coalesce(vlrdescmul,0) / (case when coalesce(multa,0) = 0 then 1 else coalesce(multa,0) end) ) as percdescmul ";
					$sqlTotal .= "  from termoini where parcel = $parcel ";
				}else if($k03_tipo == 16){
					$sqlTotal  = "select ( coalesce(dv10_vlrdescjur,0) / (case when coalesce(dv10_juros,0) = 0 then 1 else coalesce(dv10_juros,0) end) ) as percdescjur, ";
					$sqlTotal .= "       ( coalesce(dv10_vlrdescmul,0) / (case when coalesce(dv10_multa,0) = 0 then 1 else coalesce(dv10_multa,0) end) ) as percdescmul ";
					$sqlTotal .= "  from termodiver where dv10_parcel = $parcel ";
				}else if($k03_tipo == 17){
					$sqlTotal  = "select ( coalesce(vlrdescjur,0) / (case when coalesce(juros,0) = 0 then 1 else coalesce(juros,0) end) ) as percdescjur, ";
					$sqlTotal .= "       ( coalesce(vlrdescmul,0) / (case when coalesce(multa,0) = 0 then 1 else coalesce(multa,0) end) ) as percdescmul ";
					$sqlTotal .= "  from termocontrib where parcel = $parcel ";
				}

				if($sqlTotal <> "") {
					$rsTotalInicial  = db_query($sqlTotal);

					if($rsTotalInicial != false){
						$intNumrows = pg_numrows($rsTotalInicial);
						if($rsTotalInicial != false && $intNumrows > 0){
							db_fieldsmemory($rsTotalInicial,0);
							$vlrdesccor = (float)(@$vlrcor   * @$percdesccor);
							$vlrdescjur = (float)(@$vlrmulta * @$percdescmul);
							$vlrdescmul = (float)(@$vlrjuros * @$percdescjur);
						}
					}
				}
				$v01_vlrhis = $vlrhis;
				$v01_dtvenc = $k00_dtvenc;
				$valor      = $vlrhis;
				$multa      = $vlrmulta;
				$juros      = $vlrjuros;
				$desconto   = $vlrdesccor + $vlrdescjur + $vlrdescmul;
				if($np==$k00_numpre && $npa==$k00_numpar){
					continue;
				}else{
					$np=$k00_numpre;
					$npa=$k00_numpar;
				}


			}
			if ( @$matric > 0 ){
				$xnumero = 'M-'.$matric;
			}else if (@$inscr > 0){
				$xnumero = 'I-'.$inscr;
			}else{
				$xnumero = '';
			}

			$pdf->SetFont('Arial','',7);
			$pdf->Cell(15,4,$xnumero,1,0,"C",0);
			$pdf->Cell(15,4,@$v01_exerc,1,0,"C",0);
			$pdf->cell(15,4,db_formatar($v01_dtvenc,'d'),1,0,"C",0);
			$pdf->Cell(53,4,(@$v03_descr==''?"Parcelamento: ".(pg_numrows($result_reparc) > 0?$v08_parcelorigem:$parcel):$v03_descr),1,0,"L",0);
			$pdf->Cell(18,4,number_format($valor,2,",","."),1,0,"R",0);
			$pdf->Cell(18,4,number_format($vlrcor,2,",","."),1,0,"R",0);
			$pdf->Cell(18,4,number_format($multa,2,",","."),1,0,"R",0);
			$pdf->Cell(18,4,number_format($juros,2,",","."),1,0,"R",0);

			$pdf->Cell(20,4,number_format($vlrcor+$multa+$juros,2,",","."),1,1,"R",0);

			$Tv01_vlrhis += $valor;
			$Tv01_valor  += $vlrcor;
			$Tmulta      += $multa;
			$Tjuros      += $juros;
			$Tdesconto   += $desconto;

			$Total       += $vlrcor + $multa + $juros;

			if(array_key_exists($k00_descr,$arrTipo)){
				$arrTipo[$k00_descr]['vlrhist']  += ( (float)$valor );
				$arrTipo[$k00_descr]['vlrcor']   += ( (float)$vlrcor );
				$arrTipo[$k00_descr]['vlrmulta'] += ( (float)$multa);
				$arrTipo[$k00_descr]['vlrjuros'] += ( (float)$juros);
				$arrTipo[$k00_descr]['vlrdesc']  += ( (float)$desconto );
				$arrTipo[$k00_descr]['vlrtotal'] += ( (float)$vlrcor  + (float)$multa + (float)$juros - (float)$desconto );
			}else{
				$arrTipo[$k00_descr]['vlrhist']  = ( (float)$valor );
				$arrTipo[$k00_descr]['vlrcor']   = ( (float)$vlrcor );
				$arrTipo[$k00_descr]['vlrmulta'] = ( (float)$multa );
				$arrTipo[$k00_descr]['vlrjuros'] = ( (float)$juros );
				$arrTipo[$k00_descr]['vlrdesc']  = ( (float)$desconto );
				$arrTipo[$k00_descr]['vlrtotal'] = ( (float)$vlrcor  + (float)$multa + (float)$juros - (float)$desconto );
			}
			/*------------------------------------------------------*/
		}

		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(15,6,'Total',1,0,"L",0);
		$pdf->cell(83,6,'',1,0,"c",0);
		$pdf->Cell(18,6,number_format($Tv01_vlrhis,2,",","."),1,0,"R",0);
		$pdf->Cell(18,6,number_format($Tv01_valor,2,",","."),1,0,"R",0);
		$pdf->Cell(18,6,number_format($Tmulta,2,",","."),1,0,"R",0);
		$pdf->Cell(18,6,number_format($Tjuros,2,",","."),1,0,"R",0);
		$pdf->Cell(20,6,number_format($Total,2,",","."),1,1,"R",0);
		$pdf->Ln(2);

		if($pdf->GetY() > ( $pdf->h - 40 )){
			$pdf->AddPage();
		}
	}elseif(strtoupper($chave->db02_descr) == "TABELA_TOTAL"){
		 
		$pdf->SetFont('Arial','B',9);

		$sqlRegra = " select k40_descr as regra  ";
		$sqlRegra .= "  from termo ";
		$sqlRegra .= "       inner join cadtipoparc    on cadtipoparc.k40_codigo  = termo.v07_desconto  ";
		$sqlRegra .= "                                and cadtipoparc.k40_instit  = ".db_getsession('DB_instit') ;
		$sqlRegra .= "       left join cadtipoparcdeb on cadtipoparc.k40_codigo   = cadtipoparcdeb.k41_cadtipoparc ";
		$sqlRegra .= "                               and cadtipoparcdeb.k41_arretipo = $tipo ";
		$sqlRegra .= "                               and v07_dtlanc between k41_vencini and k41_vencfim  ";
		$sqlRegra .= " where v07_parcel = $parcel and v07_instit = ".db_getsession('DB_instit')." limit 1 ";
		$rsRegra = db_query($sqlRegra);

		if(pg_numrows($rsRegra)>0) {
			db_fieldsmemory($rsRegra,0);
		}else{
			$regra = "Sem Regra Definida...";
		}

		if ($pdf->GetY() > ($pdf->h - 50)) {
			$pdf->AddPage();
		}
		//segunda tabela
		$pdf->Cell(50,4,"Resumo dos débitos parcelados ",0,1,"L",0);
		$pdf->Cell(27,4,"Regra Utilizada : ",0,0,"L",0);
		$pdf->cell(60,4,"$regra",0,1,"L",0);

		$pdf->Cell(70,4," Tipo de débito " ,1,0,"C",1);
		$pdf->Cell(20,4," Vlr. His. "      ,1,0,"C",1);
		$pdf->Cell(20,4," Vlr. Corr. "     ,1,0,"C",1);
		$pdf->Cell(20,4," Vlr. Mul. "      ,1,0,"C",1);
		$pdf->Cell(20,4," Vlr. Jur. "      ,1,0,"C",1);
		$pdf->Cell(20,4," Vlr. Desc. "     ,1,0,"C",1);
		$pdf->Cell(20,4," Total "          ,1,1,"C",1);
		$pdf->SetFont('Arial','',7);

		$k00_descrnovo = "";
		$primeiro = true;
		$trocou = false;

		foreach($arrTipo as $key => $valor){
			$pdf->Cell(70,4,$key ,1,0,"C",0);
			foreach($arrTipo[$key] as $key1 => $valor1){
				$pdf->Cell(20,4,db_formatar($valor1,'f') ,1,0,"C",0);
			}
			$pdf->Cell(20,4,""  ,0,1,"C",0);
		}

		$pdf->Cell(20,4,"",0,1,"C",0);

		if($pdf->GetY() > ( $pdf->h - 40 )){
			$pdf->AddPage();
		}
		 
		 
	}elseif(strtoupper($chave->db02_descr) == "TABELA_PARCELA"){
		// terceira tabela
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(190,6,"DADOS GERAIS",1,1,"C",1);
		$y = $pdf->GetY();
		if ($entrada > 0){
			$numpar = $numpar-1;
		}
		$pdf->SetFont('Arial','',7);
		 
		$pdf->Cell(50,4,'Vencimento entrada','LRTB',0,"L",0);
		$pdf->Cell(45,4,db_formatar($vencent,'d'),'LRTB',1,"R",0);
		$pdf->Cell(50,4,'Número do Parcelamento','LRB',0,"L",0);
		$pdf->Cell(45,4,$parcel,'LRB',1,"R",0);
		$pdf->Cell(50,4,'Numero de Parcelas ',1,0,"L",0);
		$pdf->Cell(45,4,"Entrada + " . $numpar,1,1,"R",0);
		$pdf->SetXY(105,$y);
		$pdf->Cell(50,4,'Segundo vencimento',1,0,"L",0);
		$pdf->Cell(45,4,db_formatar($vencpar,'d'),1,1,"R",0);
		$pdf->SetX(105);
		$pdf->Cell(50,4,'Numpre do parcelamento',1,0,"L",0);
		$pdf->Cell(45,4,$numprecerto,1,1,"R",0);
		$pdf->SetX(105);
		/**
		 * Avalia se existe um número de protocolo do processo para ser exibido
		 * Se houver, ele o faz
		 */
		if ($v27_protprocesso) {
			$pdf->Cell(50,4,'Protocolo',1,0,"L",0);
			$pdf->Cell(45,4,$v27_protprocesso,1,1,"R",0);
		}
		$pdf->ln(10);

		$sqltipo = "select * from (select k00_tipo from arrecad where k00_numpre = $numprecerto) as x1
		union
		select * from (select k00_tipo from arrecant where k00_numpre = $numprecerto) as x2
		union
		select * from (select k00_tipo from arreold where k00_numpre = $numprecerto) as x3
		union
		select * from (select k30_tipo from arreprescr where k30_numpre = $numprecerto and k30_anulado is false) as x4 ";
		$resulttipo = db_query($sqltipo);
		$linhastipo = pg_num_rows($resulttipo);
		if($linhastipo > 0 ){
			db_fieldsmemory($resulttipo,0);
			$numtotparc = $numpar +1;
			$datad =date("Y-m-d");
			$desconto = "select  fc_recibodesconto($numprecerto, 0, $numtotparc, 0, $k00_tipo, '$datad', '$datad') as desconto";
			$resultdesc = db_query($desconto);
			$linhasdesc = pg_num_rows($resultdesc);
			if($linhasdesc > 0 and $cgc != "29131075000193"){
				db_fieldsmemory($resultdesc,0);
				 
				if($desconto > 0){
					//com desconto
					$entradades    = round($entrada -    (($entrada * $desconto)/100),2);
					$v07_ultpardes = round($v07_ultpar - (($v07_ultpar * $desconto)/100),2);
					$vlrpardes     = round($vlrpar -     (($vlrpar * $desconto)/100),2);

					$pdf->SetFont('Arial','B',7);
					$pdf->Cell(190,4,"VALORES",1,1,"C",1);
					$pdf->Cell(95,4,"SEM DESCONTO",1,0,"C",1);
					$pdf->Cell(95,4,"COM DESCONTO",1,1,"C",1);
					$pdf->SetFont('Arial','',7);
					$pdf->Cell(50,4,'Valor da entrada ',1,0,"L",0);
					$pdf->Cell(45,4,db_formatar($entrada,'f'),1,0,"R",0);
					$pdf->Cell(50,4,'Valor da entrada ',1,0,"L",0);
					$pdf->Cell(45,4,db_formatar($entradades,'f'),1,1,"R",0);

					$pdf->Cell(50,4,'Valor da ultima parcela','LRB',0,"L",0);
					$pdf->Cell(45,4,$v07_ultpar,'LRB',0,"R",0);
					$pdf->Cell(50,4,'Valor da ultima parcela','LRB',0,"L",0);
					$pdf->Cell(45,4,$v07_ultpardes,'LRB',1,"R",0);

					$pdf->Cell(50,4,'Valor das parcelas a partir de ',1,0,"L",0);
					$pdf->Cell(45,4,db_formatar($vlrpar,'f'),1,0,"R",0);
					$pdf->Cell(50,4,'Valor das parcelas a partir de ',1,0,"L",0);
					$pdf->Cell(45,4,db_formatar($vlrpardes,'f'),1,1,"R",0);
					$pdf->ln(10);
				}else{
					//sem desconto
					$pdf->SetFont('Arial','B',7);
					$pdf->Cell(95,4,"VALORES",1,1,"C",1);
					$pdf->SetFont('Arial','',7);
					$pdf->Cell(50,4,'Valor da entrada ',1,0,"L",0);
					$pdf->Cell(45,4,db_formatar($entrada,'f'),1,1,"R",0);
					$pdf->Cell(50,4,'Valor da ultima parcela','LRB',0,"L",0);
					$pdf->Cell(45,4,$v07_ultpar,'LRB',1,"R",0);
					$pdf->Cell(50,4,'Valor das parcelas a partir de ',1,0,"L",0);
					$pdf->Cell(45,4,db_formatar($vlrpar,'f'),1,1,"R",0);
					$pdf->ln(10);
					//tem q calcular o desconto para cada parcela
					 
				}
			}
		}else{
			db_msgbox("numpre sem tipo encontrado.");
		}

	}else{
		//tabela_valores

		$diaTermo = substr($v07_dtlanc,8,2);
		$mesTermo = db_mes(substr($v07_dtlanc,5,2));
		$anoTermo = substr($v07_dtlanc,0,4);
		$responsavel =trim($responsavel);
		$pdf->SetFont('Arial', '', 10);
		$pdf->MultiCell(180, 6,"        ".$objteste->geratexto($chave->db02_texto),0,$alinhamento);
		$pdf->cell(180, $alt,"",0, 1, "C", 0);
	}
}

if(!defined('DB_BIBLIOT'))

	$pdf->Output();
?>
