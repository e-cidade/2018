<?php
/**
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


function certidao_comprobatorio($regist,$data_cert,$numcert,$emissor){

  global $nacion,$vinculos,$head1,$head2,$head3,$head4,$head5,$pessoal_emissor, $funcao,$depend,$subpes,$pai, $mae,$cgm, $emissor,$emissor_regist,$emissor_nome,$emissor_funcao;

  $cab_vant = true;

  define('MENSAGEM', 'recursoshumanos.rh.rec2_certcomprobatorio.');

  $sCampos  = "cgm.*, rh02_anousu as r01_anousu, rh02_mesusu as r01_mesusu, rh01_regist as r01_regist,rh01_numcgm as r01_numcgm,                           ";
  $sCampos .= "rh01_natura as r01_natura, rh01_sexo as r01_sexo, trim(to_char(rh02_lota,'9999')) as r01_lotac,                                             ";
  $sCampos .= "rh01_admiss as r01_admiss, rh05_recis    as r01_recis, rh02_tbprev as r01_tbprev,rh30_regime as r01_regime,                                 ";
  $sCampos .= "rh30_vinculo as r01_tpvinc, rh02_salari as r01_salari, rh03_padrao as r01_padrao,rh02_hrssem as r01_hrssem,                                 ";
  $sCampos .= "rh02_hrsmen as r01_hrsmen, rh01_nasc as r01_nasc, rh65_rubric as r01_rubric, rh65_valor as r01_arredn,                                      ";
  $sCampos .= "rh02_equip as r01_equip, rh01_progres as r01_anter, rh01_trienio as r01_trien, rh15_data as r01_fgts,                                       ";
  $sCampos .= "(case when rh01_progres is not null then 's' else 'n' end) as r01_progr, rh05_causa as r01_causa, rh05_caub as r01_caub,                    ";
  $sCampos .= "rh05_mremun as r01_mremun, rh01_funcao as r01_funcao, rh01_clas1 as r01_clas1, rh01_clas2 as r01_clas2,                                     ";
  $sCampos .= "rh02_tpcont as r01_tpcont, rh02_ocorre as r01_ocorre, rh51_b13fo as r01_b13fo, rh51_basefo as r01_basefo,                                   ";
  $sCampos .= "rh51_descfo as r01_descfo, rh51_d13fo as r01_d13fo, rh02_tipsal as r01_tipsal, rh19_propi as r01_propi,                                     ";
  $sCampos .= "rh01_depirf as r01_depirf, rh01_vale as r01_vale, rh01_depsf as r01_depsf,rh37_funcao as r37_funcao, rh37_descr as r37_descr,               ";
  $sCampos .= "r70_estrut as r13_codigo, r70_descr as r13_descr,rh01_nacion as r01_nacion, rh16_titele as r01_titele, rh16_zonael as r01_zonael,           ";
  $sCampos .= "rh16_secaoe as r01_sescaoe, rh16_reserv as r01_reserv, rh16_catres as r01_catres, rh16_pis as r01_pis, rh16_carth_n  as r01_carth,r02_descr, ";
  $sCampos .= "lpad(rh16_ctps_n,7,'0')||lpad(rh16_ctps_s,5,'0')||'-'||rh16_ctps_uf as r01_ctps, rh16_zonael, rh16_secaoe, rh16_reserv || ' ' ||rh16_catres as rh16_catres ";

  $oDaoRhPessoalmov = db_utils::getDao('rhpessoalmov');

  $sSqlRhPessoalmov = $oDaoRhPessoalmov->sql_getDadosServidoresTempoServico( $sCampos, 
    db_anofolha(), 
    db_mesfolha(), 
    $regist
  );

  $rsRhPessoalmov = db_query($sSqlRhPessoalmov);

  if (!$rsRhPessoalmov) {

    $sErro = _M(MENSAGEM . 'erro_sql_pessoal');
    db_redireciona('db_erros.php?fechar=true&db_erro='.$sErro);
    exit; 
  }

  if (pg_numrows($rsRhPessoalmov) == 0) {

    $sErro = _M(MENSAGEM . 'nenhum_dado_servidor');
    db_redireciona('db_erros.php?fechar=true&db_erro='.$sErro);
    exit;
  }

  $oDadosPessoal = db_utils::fieldsMemory($rsRhPessoalmov, 0);

  $oDaoRhPessoal = db_utils::getDao('rhpessoal');
  $sSqlEmissor   = $oDaoRhPessoal->sql_query_func_rhpessoal($emissor);
  $rsEmissor     = db_query($sSqlEmissor);

  if (!$rsEmissor) {

    $sErro = _M(MENSAGEM . 'erro_sql_emissor');
    db_redireciona('db_erros.php?fechar=true&db_erro='.$sErro);
    exit;
  }

  if (pg_numrows($rsEmissor) == 0) {

    $sErro = _M(MENSAGEM . 'nenhum_dado_emissor');
    db_redireciona('db_erros.php?fechar=true&db_erro='.$sErro);
    exit;
  }

  $oDadosEmissor = db_utils::fieldsMemory($rsEmissor, 0); 

  $emissor_regist = trim($oDadosEmissor->rh01_regist);
  $emissor_nome   = trim($oDadosEmissor->z01_nome);
  $emissor_funcao = trim($oDadosEmissor->rh37_descr);

  $condicaoaux = " and r37_funcao = ".db_sqlformat(db_str($oDadosPessoal->r01_funcao,5));
  $funcao = trim($oDadosPessoal->r37_descr);

  db_selectmax("nacion","select * from rhnacionalidade where rh06_nacionalidade = ".$oDadosPessoal->r01_nacion);

  $numcert1 = $numcert;
  $sPai = $oDadosPessoal->z01_pai;
  $sMae = $oDadosPessoal->z01_mae;

  $condicaoaux = " and r03_regist = ".db_sqlformat(db_str($regist,6));
  global $depend;
  if( db_selectmax("depend","select rh31_regist as r03_regist,
    rh31_gparen as r03_gparen,
    rh31_nome   as r03_nome 
    from rhdepend
    where rh31_regist = $regist ")){

      // leandro oliveira em 14/01/2003;
      for($Idepend=0;$Idepend<count($depend);$Idepend++){
        if( strtolower($depend[$Idepend]["r03_gparen"]) == "p"){
          $sPai = $depend[$Idepend]["r03_nome"];
        }
        if ( strtolower($depend[$Idepend]["r03_gparen"]) == "m"){
          $sMae = $depend[$Idepend]["r03_nome"];
        }
      }
    }


  $nome = $oDadosPessoal->z01_nome;

  // impressao da pagina principal da certidao;
  $head1 ="\nCertidão Comprobatória do Tempo de Serviço/Contribuição\ne das Alterações Ocorridas Durante a\nVida Funcional do Servidor" ;
  //$head5 = "PERÍODO : ".db_formatar($dataini,'d')." A ".db_formatar($datafin,'d');
  $pdf = new PDF1();
  $pdf->Open();
  $pdf->AliasNbPages();
  $total = 0;
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',8);
  $alt = 4;
  $troca = true;
  if($pdf->gety() > $pdf->h - 30 || $troca ){
    $pdf->addpage();
    $pdf->setfont('arial','b',10);
    $pdf->cell(0,$alt,"Certidão N".chr(176).db_str($numcert1,4,0,"0"),0,1,"C",1);
    $pdf->ln($alt*2);
    $pdf->cell(0,$alt,"DADOS PESSOAIS",0,1,"C",1);
    $pdf->ln($alt);
    $pdf->setfont('arial','b',8);
    $pdf->cell(25,$alt,"Matrícula",0,0,"L",0);
    $pdf->setfont('arial','',8);
    $pdf->cell(0,$alt,":  ".db_str($oDadosPessoal->r01_regist,6),0,1,"L",0);
    $pdf->setfont('arial','b',8);
    $pdf->cell(25,$alt,"Nome",0,0,"L",0);
    $pdf->setfont('arial','',8);
    $pdf->cell(0,$alt,":  ".$oDadosPessoal->z01_nome,0,1,"L",0);
    $pdf->setfont('arial','B',8);
    $pdf->cell(25,$alt,"Data Nascimento",0,0,"L",0);
    $pdf->setfont('arial','',8);
    $pdf->cell(0,$alt,":  ".db_formatar($oDadosPessoal->r01_nasc,'d'),0,1,"L",0);
    $pdf->setfont('arial','b',8);
    $pdf->cell(25,$alt,"Nome do Pai",0,0,"L",0);
    $pdf->setfont('arial','',8);
    $pdf->cell(0,$alt,":  ".$sPai,0,1,"L",0);
    $pdf->setfont('arial','b',8);
    $pdf->cell(25,$alt,"Nome da Mãe",0,0,"L",0);
    $pdf->setfont('arial','',8);
    $pdf->cell(0,$alt,":  ".$sMae,0,1,"L",0);
    $pdf->setfont('arial','b',8);
    $pdf->cell(25,$alt,"Nacionalidade",0,0,"L",0);
    $pdf->setfont('arial','',8);
    $pdf->cell(0,$alt,":  ".$nacion[0]["rh06_descr"],0,1,"L",0);
    $pdf->setfont('arial','b',8);
    $pdf->cell(25,$alt,"Naturalidade",0,0,"L",0);
    $pdf->setfont('arial','',8);
    $pdf->cell(0,$alt,":  ".$oDadosPessoal->r01_natura,0,1,"L",0);
    $pdf->setfont('arial','b',8);
    $pdf->cell(25,$alt,"Sexo",0,0,"L",0);
    $pdf->setfont('arial','',8);
    $pdf->cell(0,$alt,":  ".($oDadosPessoal->r01_sexo == "M"? "MASCULIN0": "FEMININO" ),0,1,"L",0);
    $pdf->ln($alt*2);

    $pdf->setfont('arial','b',10);
    $pdf->cell(0,$alt,"DOCUMENTOS",0,1,"C",1);
    $pdf->ln($alt);
    $pdf->setfont('arial','b',8);
    $pdf->cell(25,$alt,"Cic/Cpf",0,0,"L",0);
    $pdf->setfont('arial','',8);
    $pdf->cell(0,$alt,":  ".db_formatar($oDadosPessoal->z01_cgccpf,'cpf'),0,1,"L",0);
    $pdf->setfont('arial','b',8);
    $pdf->cell(25,$alt,"RG",0,0,"L",0);
    $pdf->setfont('arial','',8);
    $pdf->cell(0,$alt,":  ".$oDadosPessoal->z01_ident,0,1,"L",0);
    $pdf->setfont('arial','b',8);
    $pdf->cell(25,$alt,"CTPS ",0,0,"L",0);
    $pdf->setfont('arial','',8);
    $pdf->cell(0,$alt,":  ".$oDadosPessoal->r01_ctps,0,1,"L",0);
    $pdf->setfont('arial','b',8);
    $pdf->cell(25,$alt,"Pis/Pasep",0,0,"L",0);
    $pdf->setfont('arial','',8);
    $pdf->cell(0,$alt,":  ".$oDadosPessoal->r01_pis,0,1,"L",0);
    $pdf->setfont('arial','b',8);
    $pdf->cell(25,$alt,"Título Eleitor",0,0,"L",0);
    $pdf->setfont('arial','',8);
    $pdf->cell(0,$alt,":  ".$oDadosPessoal->r01_titele,0,1,"L",0);
    $pdf->setfont('arial','b',8);
    $pdf->cell(25,$alt,"Zona",0,0,"L",0);
    $pdf->setfont('arial','',8);
    $pdf->cell(0,$alt,":  ".$oDadosPessoal->rh16_zonael,0,1,"L",0);
    $pdf->setfont('arial','b',8);
    $pdf->cell(25,$alt,"Seção",0,0,"L",0);
    $pdf->setfont('arial','',8);
    $pdf->cell(0,$alt,":  ".$oDadosPessoal->rh16_secaoe,0,1,"L",0);
    $pdf->setfont('arial','b',8);
    $pdf->cell(25,$alt,"Cert. Reservista",0,0,"L",0);
    $pdf->setfont('arial','',8);
    $pdf->cell(0,$alt,":  ".$oDadosPessoal->rh16_catres,0,1,"L",0);


    $pdf->ln($alt*2);
    $pdf->setfont('arial','b',10);
    $pdf->cell(0,$alt,"DADOS FUNCIONAIS NESTA DATA",0,1,"C",1);
    $pdf->ln($alt);
    $pdf->setfont('arial','b',8);
    $pdf->cell(25,$alt,"Regime Jurídico",0,0,"L",0);
    $pdf->setfont('arial','',8);
    $pdf->cell(0,$alt,":  " .mb_strtoupper(regime($oDadosPessoal->r01_regime)),0,1,"L",0);
    if( $oDadosPessoal->r01_regime == 1){
      $pdf->setfont('arial','b',8);
      $pdf->cell(25,$alt,"Nomeação",0,0,"L",0);
      $pdf->setfont('arial','',8);
      $pdf->cell(0,$alt,":  ".db_formatar($oDadosPessoal->r01_admiss,'d'),0,1,"L",0);
    }else{
      $pdf->setfont('arial','b',8);
      $pdf->cell(25,$alt,"Admissão",0,0,"L",0);
      $pdf->setfont('arial','',8);
      $pdf->cell(0,$alt,":  ".db_formatar($oDadosPessoal->r01_admiss,'d'),0,1,"L",0);
    }
    $pdf->setfont('arial','b',8);
    $pdf->cell(25,$alt,"Cargo",0,0,"L",0);
    $pdf->setfont('arial','',8);
    $pdf->cell(0,$alt,":  ".$oDadosPessoal->r37_descr,0,1,"L",0);
    $pdf->setfont('arial','b',8);
    $pdf->cell(25,$alt,"Lotação",0,0,"L",0);
    $pdf->setfont('arial','',8);
    $pdf->cell(0,$alt,":  ".$oDadosPessoal->r13_codigo." ".$oDadosPessoal->r13_descr,0,1,"L",0);
    $pdf->setfont('arial','b',8);
    $pdf->cell(25,$alt,"Carga Horária",0,0,"L",0);
    $pdf->setfont('arial','',8);
    $pdf->cell(0,$alt,":  ".db_str($oDadosPessoal->r01_hrsmen,3)."  HORAS MENSAIS",0,1,"L",0);
    $pdf->setfont('arial','',8);

    $pdf->ln($alt*33);
    $troca = false;
  }
  $pdf = imprime_assenta_fastamento(2,$oDadosPessoal->r01_regist,$data_cert,$pdf, $oDadosPessoal);

  //echo "<BR> passou aqui !!!";   
  //exit;
  // impressao das vantagens;
  $desc_vant = "a";

  $pdf = imprime_vantagem($desc_vant,$data_cert,$regist,$cab_vant,"arq4",$pdf, $oDadosPessoal);

  // solucao paliativa para o erro ocorrido em rio grande.;
  // ocorria quando o funcionario estava com a data de recisao preenchida;
  // gerando erro pois nao dava select no vinculos.;
  // leandro oliveira em 17/01/2003 - inicio;
  if( ( !db_empty($oDadosPessoal->r01_recis) && db_mktime($oDadosPessoal->r01_recis) < db_mktime($data_cert) ) ||
    $oDadosPessoal->r01_tpvinc == "p"){

      $lei_avanc   = bb_space(200);
      $lei_avanc2  = bb_space(200);

    }else{

      $lei_avanc = $vinculos[0]["h11_cert01"];
      $lei_avanc2 = $vinculos[0]["h11_cert02"];

    }
  // leandro oliveira em 17/01/2003 - fim;


  $desc_vant = "g";
  $pdf = imprime_vantagem($desc_vant,$data_cert,$regist,$cab_vant,"arq5",$pdf, $oDadosPessoal);

  // idem ao if acima;
  // leandro oliveira em 17/01/2003 - inicio;
  if( ( !db_empty($oDadosPessoal->r01_recis) && db_mktime($oDadosPessoal->r01_recis) < db_mktime($data_cert) ) ||
    strtolower($oDadosPessoal->r01_tpvinc) == "p"){

      $lei_grat   = bb_space(200);
      $lei_grat2  = bb_space(200);

    }else{

      $lei_grat = $vinculos[0]["h11_cert01"];
      $lei_grat2 = $vinculos[0]["h11_cert02"];

    }
  // leandro oliveira em 17/01/2003 - fim;


  if( !db_empty($lei_avanc)){
    $pdf->cell(0,$alt,db_substr($lei_avanc,1,130),1,0,"C",1);
    $pdf->cell(0,$alt,db_substr($lei_avanc,131,70)." ".db_substr($lei_avanc2,1,60),1,0,"C",1);
    if( !db_empty($lei_avanc2)){
      $pdf->cell(0,$alt,db_substr($lei_avanc2,60,130),1,0,"C",1);
      $pdf->cell(0,$alt,db_substr($lei_avanc2,190,12),1,0,"C",1);
    }
  }
  if( !db_empty($lei_grat)){
    $pdf->cell(0,$alt, db_substr($lei_grat,1,130),1,0,"C",1);
    $pdf->cell(0,$alt, db_substr($lei_grat,131,70)." ".db_substr($lei_grat2,1,60)+" ",1,0,"C",1);
    if( !db_empty($lei_grat2)){
      $pdf->cell(0,$alt, db_substr($lei_grat2,60,130),1,0,"C",1);
      $pdf->cell(0,$alt, db_substr($lei_grat2,190,10),1,0,"C",1);
    }
  }
  $pdf = imp_gradeefetividade(2,$regist,$data_cert,null,true,true,true,null,null,$pdf);
  $sqlparag = "select *
    from db_documento 
    inner join db_docparag on db03_docum = db04_docum
    inner join db_tipodoc on db08_codigo  = db03_tipodoc
    inner join db_paragrafo on db04_idparag = db02_idparag 
    where db03_tipodoc = 11 and db03_instit = " . db_getsession("DB_instit")." order by db04_ordem ";
  db_sel_instit();
  $resparag = db_query($sqlparag);
  if ( pg_numrows($resparag) == 0 ) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Configure o documento 11 (Certidão de Tempo de Serviço!)');
    exit; 
  }
  $numrows = pg_numrows($resparag);
  $texto = '';
  global $db02_texto, $emissor_regist, $emissor_nome, $emissor_funcao,$munic;
  for($i=0;$i<$numrows;$i++){
    db_fieldsmemory($resparag,$i);
    $texto .= $db02_texto;
  }
  $data= date("Y-m-d",db_getsession("DB_datausu"));
  $data=split('-',$data);
  $dia=$data[2];
  $mes=$data[1];
  $ano=$data[0];
  $mes=db_mes($mes);
  $data=" $dia de $mes de $ano ";
  $pdf->setfont('arial','',8);
  $pdf->ln(3);												
  $pdf->multicell(0,$alt,db_geratexto($texto),0,"J",0,40);
  $pdf->multicell(0,$alt,ucfirst(strtolower($munic)).', '.$data.'.',0,"J",0,40);
  $pdf->ln(8);												
  $pdf->multicell(0,$alt,$emissor_nome.'                  ',0,"R",0,40);
  $pdf->ln(5);												
  $pdf->multicell(0,$alt,'Visado por : ',0,"L",0);

  $sNomeArquivo = 'Certidao_Tempo_Servico.pdf';

  $pdf->Output($sNomeArquivo, 'tmp/' . $sNomeArquivo);

}

function imprime_vantagem($desc_vant,$data_cert,$regist,$cab_vant,$arq_work,$pdf, $oDadosPessoal){

  global $vinculos,$pessoal,$funcao,$depend,$subpes,$pai, $mae,$anos_ant;

  global $assenta,$Iassenta,$alt,$tipo_vantagem;

  $data_vantagem = array();
  $dias_prot     = array();

  // cria arquivo work ;

  $nome     = array();
  $tipo     = array();
  $tamanho  = array();
  $decimais = array();

  $nome[1] = "wv_regist";
  $nome[2] = "wv_dtbase";
  $nome[3] = "wv_dtvant";
  $nome[4] = "wv_lei";
  $nome[5] = "wv_anos";
  $nome[6] = "wv_perc";
  $nome[7] = "wv_tipo";
  $nome[8] = "wv_protel";
  $nome[9] = "wv_nome";
  $nome[10] = "wv_lotac";
  $nome[11] = "wv_inf";

  $tipo[1] = "n";
  $tipo[2] = "d";
  $tipo[3] = "d";
  $tipo[4] = "c";
  $tipo[5] = "n";
  $tipo[6] = "n";
  $tipo[7] = "c";
  $tipo[8] = "n";
  $tipo[9] = "c";
  $tipo[10] = "c";
  $tipo[11] = "c";

  $tamanho[1] = 6;
  $tamanho[2] = 8;
  $tamanho[3] = 8;
  $tamanho[4] = 6;
  $tamanho[5] = 2;
  $tamanho[6] = 6;
  $tamanho[7] = 1;
  $tamanho[8] = 10;
  $tamanho[9] = 40;
  $tamanho[10] = 4 ;
  $tamanho[11] = 3;

  $decimais = array_fill(1,11,0);
  $decimais[6] = 2;

  db_criatemp($arq_work,$nome,$tipo,$tamanho,$decimais);

  global $work;
  db_selectmax( "work", "select * from ".$arq_work );

  // carrega tabela de protelacoes para memoria somente dos avancoes ou gratifica-;
  // coes. a tabela tab_soma funciona em conjunto com a tab_vantagem, pois em al-;
  // guns casos, quando o conteudo do campo tipoasse->h12_efetiv estiver com ;
  // conteudo "+", as protelacoes funcionam ao contrario: a data da vantagem nao;
  // prorroga e sim retorna.;
  // (tab_soma = 1 soma ) (tab_soma = 2 diminui)  (tab_soma = 3 dobra ) ;

  $tab_protelac = array();
  $tab_soma     = array();
  $i = 0;
  $condicaoaux = " where h19_tipo = ".db_sqlformat( $tipo_vantagem );
  global $protelac,$tipoasse;
  db_selectmax("protelac","select * from protelac ".$condicaoaux." order by h19_assent, h19_tipo" );

  for($Iprotelac=0;$Iprotelac< count($protelac);$Iprotelac++){
    $i++;
    $tab_protelac[$i] = $protelac[$Iprotelac]["h19_assent"];
    $tab_soma[$i] = 1;
    if(db_selectmax("tipoasse","select * from tipoasse where h12_assent = " .db_sqlformat($protelac[$Iprotelac]["h19_assent"]))){
      return $pdf;
    }else{
      if( $tipoasse[0]["h12_efetiv"] == "+"){
        $tab_soma[$i] = 2;
      }else{
        $tab_soma[$i] = 3;
      }
    }
  }

  // testa se o processamento eh geral ou so de um funcionario *******************;


  if( db_empty($oDadosPessoal->r01_regist)){
    // somente na opcao de relatorios de avanco/gratificao este item e zerado;

    $condicaoaux  = " and r01_tpvinc != 'p'";
    $condicaoaux .= " and ( r01_regime = ".db_sqlformat( $oDadosPessoal->r01_regime) ;
    $condicaoaux .= "      or '".db_str($oDadosPessoal->r01_regime,1,0)."' = '0' )";
    $condicaoaux .= " and ( r01_recis is null ";
    $condicaoaux .= "      or r01_recis is not null and r01_recis >= ".db_sqlformat( $data_cert ) .")";
    $condicaoaux .= " and r01_regist <= 10 ";


    $campos_pess = "r01_regist,r01_numcgm,r01_recis,r01_tpvinc,r01_funcao,r01_regime,r01_admiss,r01_lotac ";
    global $pessoal;
    db_selectmax("pessoal","select ".$campos_pess." from pessoal ". bb_condicaosubpes("r01_").$condicaoaux );
    $totreg = count($pessoal);
  }
  for($Ipessoal=0;$Ipessoal<count($pessoal);$Ipessoal++){
    if( $regist != $pessoal[$Ipessoal]["r01_regist"]){
      break;
    }
    // se funcionario demitido antes da data de fim ignora **********************;

    if( !db_empty($pessoal[$Ipessoal]["r01_recis"]) && db_mktime($pessoal[$Ipessoal]["r01_recis"]) < db_mktime($data_cert)){
      continue;
    }

    // ignora funcionarios pensionistas *****************************************;

    if( strtolower($pessoal[$Ipessoal]["r01_tpvinc"]) == "p"){
      continue;
    }

    // pega data de admissao do funcionario ou a data de contagem de trienio ****;

    $data_admissao = "";


    $condicaoaux  = "select h16_assent,h16_regist,h16_dtconc ";
    $condicaoaux .= "  from assenta ";

    $condicaoaux .= "  left outer join tipoasse ";
    $condicaoaux .= "    on h12_codigo = h16_assent";
    $condicaoaux .= "   and h12_efetiv = 'I' ";

    $condicaoaux .= "where h16_regist = ".db_sqlformat( db_str($pessoal[$Ipessoal]["r01_regist"],6) );
    $condicaoaux .= "  and h12_assent is not null ";
    $condicaoaux .= "order by h16_dtconc, h16_assent ";
    global $assenta;
    if(db_selectmax( "assenta", $condicaoaux )){
      $data_admissao = $assenta[0]["h16_dtconc"];
    }


    if( db_empty($data_admissao)){
      $data_admissao = $pessoal[$Ipessoal]["r01_admiss"];
    }

    // verifica se funcionario esta enquadrado em alguma lei, de acordo com *****;
    // seu regime e funcao.;
    global $vinculos;
    if( db_selectmax("vinculos","select * from vinculos where h11_tipo = " . db_sqlformat($tipo_vantagem) . " and h11_regime = " . db_sqlformat(db_str($pessoal[$Ipessoal]["r01_regime"],1))." and h11_funcao = " . db_sqlformat(db_str($pessoal[$Ipessoal]["r01_funcao"],5))) 
      || db_selectmax("vinculos","select * from vinculos where h11_tipo = " . db_sqlformat($tipo_vantagem) . " and h11_regime = " . db_sqlformat(db_str($pessoal[$Ipessoal]["r01_regime"],1))." and h11_funcao = '99999'")){

        if( busca_lei($vinculos[0]["h11_lei1"],$tipo_vantagem)){
          $anos_ant    = 0;
          $anos_perc   = 0;

          // se funcionario inativo a data de fim sera a de admissao, a de inicio;
          // sera pega a do trienio, que normalmente esta preenchida no cadastro.;

          if( strtolower($pessoal[$Ipessoal]["r01_tpvinc"]) == "a" ){
            $data_fim    = $data_cert;
          }else{
            if( db_mktime($data_cert) < db_mktime($pessoal[$Ipessoal]["r01_admiss"])){
              $data_fim = $data_cert;
            }else{
              $data_fim = $pessoal[$Ipessoal]["r01_admiss"];
            }
          }

          $data_fim  = date("Y-m-d",db_mktime($data_fim)+(5*365*86400));

          // calcula numero de anos. a matriz data_vantagem fica com todas as ***;
          // em que o funcionario completa um ano.;

          $data_vantagem = array_fill(1,99," ");
          $dias_prot     = array_fill(1,99,0);
          $anos = 0;
          $data_calculo = $data_admissao;
          if( db_day($data_calculo) == 29 && db_month($data_calculo) == 2){
            $data_calculo = date("Y-m-d",db_mktime($data_calculo) - (1*86400));
          }
          $dia_vantagem = db_str(db_day($data_calculo),2,0,"0");
          while(db_mktime($data_calculo) <= db_mktime($data_fim)){
            $anos++;
            if( db_val(db_str(bcdiv(db_year($data_calculo),4,0),13,2)) == db_val(db_str(db_year($data_calculo)/4,13,2)) ){
              $bisexto = 365; // 366;
            }else{
              $bisexto = 365;
            }
            $data_calculo  = date("Y-m-d",db_mktime($data_calculo) + ($bisexto*86400));
            $data_vantagem[$anos] = $data_calculo ;
          }
          $anos -= 1;

          // continua processamento somente se o numero de anos do funcionario **;
          // for maior que zero.;

          $data_ultima_nomeacao = "";
          if( !db_empty($anos)){

            // arruma datas da tabela data_vantagem de acordo com as protelacoes.;
            global $assenta;
            if( db_selectmax("assenta","select * from assenta inner join assentamentofuncional on rh193_assentamento_funcional = h16_codigo where h16_regist = " . db_sqlformat(db_str($oDadosPessoal->r01_regist,6))." order by h16_dtconc, h16_assent ")){
              $assentamento_final = false;
              $data_inicio_protelar = "";
              $data_final_protelar  = "";
              $Iassenta = 0;
              while($Iassenta < count($assenta) ){

                // verifica se o tipo de assentamento conta para protelacao **;
                // e se a data inicial do assentamento eh posterior a data de ;
                // inicio data_admissao;
                global $tipoasse;
                if( db_selectmax("tipoasse","select h12_assent,h12_efetiv from tipoasse where h12_codigo = " . db_sqlformat($assenta[$Iassenta]["h16_assent"]))){
                  if( !$assentamento_final){
                    if( strtolower($tipoasse[0]["h12_efetiv"]) == "f" ){
                      $data_inicio_protelar = $assenta[$Iassenta]["h16_dtconc"];
                      $assentamento_final = true;
                    }
                  }else{
                    if( strtolower($tipoasse[0]["h12_efetiv"] == "i") && !db_empty($data_inicio_protelar)){
                      $data_final_protelar =  $assenta[$Iassenta]["h16_dtconc"] ;
                      $data_ultima_nomeacao = $assenta[$Iassenta]["h16_dtconc"];

                      $dias_protelar = db_datedif($data_final_protelar,$data_inicio_protelar) - 1;
                      for($i=1;$i <=$anos;$i++){
                        if( (db_mktime($data_inicio_protelar)+(1*86400)) <= db_mktime($data_vantagem[$i])){
                          break;
                        }
                      }

                      // soma os dias a protelar nas proximas ocorrencias da ***;
                      // tabela.;

                      for($j=$i;$j<=$anos;$j++){
                        $data_vantagem[$j] = date("Y-m-d",db_mktime($data_vantagem[$j]) + ($dias_protelar*86400));
                        $dias_prot[$j]    += $dias_protelar;
                      }
                      $assentamento_final = false;
                      $data_inicio_protelar = "";
                      $data_final_protelar  = "";
                    }
                  }
                }
                $posicao = db_ascan($tab_protelac,$assenta[$Iassenta]["h16_assent"]);
                if( !db_empty($posicao) ){

                  // busca em data_vantagem o periodo em que a protelacao ***;
                  // enquadra.;

                  if( db_empty($assenta[$Iassenta]["h16_dtterm"])){
                    $termo = date('Y-m-d',db_getsession('DB_datausu'));
                  }else{
                    $termo = $assenta[$Iassenta]["h16_dtterm"];
                  }
                  $dias_protelar = db_datedif($termo,$assenta[$Iassenta]["h16_dtconc"]) + 1;

                  if( $tab_soma[$posicao] == 2){
                    $dias_protelar = $dias_protelar * -1;
                  }else{
                    $dias_protelar = ( $dias_protelar * 2 ) * -1;
                  }

                  $condicaoaux  = " where h19_assent = " . db_sqlformat($assenta[$Iassenta]["h16_assent"]) ;
                  $condicaoaux .= "   and h19_tipo = " . db_sqlformat($tipo_vantagem);
                  $condicaoaux .= "   and h19_dia01 > 0 ";
                  global $protelac;
                  if( db_selectmax("protelac","select * from protelac ".$condicaoaux )){
                    for($iprot=1;$iprot<=10;$iprot++){
                      if( $dias_protelar < $protelac[0]["h19_dia".db_str($iprot,2,0,"0")]){
                        $oper_pro = $protelac[0]["h19_op".db_str($iprot,2,0,"0")];
                        $perc_pro = $protelac[0]["h19_per".db_str($iprot,2,0,"0")];
                        if( $oper_pro == "="){
                          $dias_protelar = $perc_pro;
                        }else{
                          $operador = "$dias_protelar .$oper_pro . db_val('".db_str($perc_pro,5,2)."');";
                          $dias_protelar = eval($operador);
                        }
                        break;
                      }
                    }
                  }
                  for($i=1;$i<= $anos;$i++){
                    if( db_mktime($assenta[$Iassenta]["h16_dtconc"]) <= db_mktime($data_vantagem[$i])){
                      if( db_at(db_str($tab_soma[$posicao],1),"2-3") > 0 ){
                        $diferenca_diminuir = db_datedif($data_vantagem[$i],$assenta[$Iassenta]["h16_dtconc"]);
                      }else{
                        $diferenca_diminuir = 0;
                      }
                      break;
                    }
                  }

                  // soma os dias a protelar nas proximas ocorrencias da ***;
                  // tabela.;

                  for($j=$i;$j < count($anos);$j++){
                    if( db_empty($diferenca_diminuir)){
                      $data_vantagem[$j] = date("Y-m-d",(db_mktime($data_vantagem[$j]) + ($dias_protelar*86400)));
                    }else{
                      $data_vantagem[$j] = date("Y-m-d",(db_mktime($data_vantagem[$j]) - ($diferenca_diminuir*86400)));
                      $diferenca_diminuir = 0;
                    }
                    $dias_prot[$j] += $dias_protelar;
                  }

                }
                $Iassenta++;
              }
            }
            global $work; 
            db_selectmax("work","select * from " .$arq_work);

            enquadra_na_lei();
            if( busca_lei($vinculos[0]["h11_lei2"],$tipo_vantagem)){
              enquadra_na_lei();
              if( busca_lei($vinculos[0]["h11_lei3"],$tipo_vantagem)){
                enquadra_na_lei();
                if( busca_lei($vinculos[0]["h11_lei4"],$tipo_vantagem)){
                  enquadra_na_lei();
                  if( busca_lei($vinculos[0]["h11_lei5"],$tipo_vantagem)){
                    enquadra_na_lei();
                  }
                }
              }
            }
          }
        }
      }
  }

  $indice = " order by wv_dtvant ";
  global $work_;
  if( !db_selectmax("work_","select * from " .$arq_work .$indice )){
    return $pdf;
  }

  $passou = false;

  $max_conta = count();
  $Iwork = 0;
  for($Iwork=0;$Iwork< count($work_);$Iwork++){

    $campos_pesso  = " r01_regist,r01_numcgm,r01_admiss,r01_recis,r01_funcao,";
    $campos_pesso .= " r01_regime,r01_tpvinc,r01_lotac ";
    $condicaoaux   = " and r01_regist = " . db_sqlformat(db_str($work_[$Iwork]["wv_regist"],6));
    if( !db_selectmax("pessoal","select ".$campos_pesso." from pessoal ".bb_condicaosubpes("r01_") .$condicaoaux )){
      return "";
    }

    if(strtolower($oDadosPessoal->r01_tpvinc) == "a" ){
      $data_fim  = $data_cert;
    }else{
      if( db_mktime($data_cert) < db_mktime($oDadosPessoal->r01_admiss)){
        $data_fim = $data_cert;
      }else{
        $data_fim = $oDadosPessoal->r01_admiss;
      }
    }


    if( db_mktime($work_[$Iwork]["wv_dtvant"]) > db_mktime($data_fim)){
      $posic = $Iwork;
      db_delete($arq_work," where wv_regist = " . db_sqlformat($work_[$Iwork]["wv_regist"]). " and wv_dtvant = " . db_sqlformat( $work_[$Iwork]["wv_dtvant"]));

      if( count($work_) < $posic){
        break;
      }

      $Iwork = $posic;
    }

  }
  global $work;
  db_selectmax("work","select * from " .$arq_work);
  if( $cab_vant ){
    if($pdf->gety() > $pdf->h - 30 || $cab_vant ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->ln($alt*8);
      $cab_vant = false;
    }   
    $pdf->cell(0,$alt,"Relacao De Vantagens",1,0,"C",1);; 
    $pdf->ln($alt*2);
    $pdf->cell(3,$alt,"Vantagem",0,0,"C",1);
    $pdf->cell(25,$alt,"Anos",0,0,"C",1);
    $pdf->cell(32,$alt,"Perc.",0,0,"C",1);
    $pdf->cell(40,$alt,"A/C de",0,0,"C",1);
    $pdf->cell(52,$alt,"Lei",0,0,"C",1);
    $pdf->cell(60,$alt,"Data Inicio",0,0,"C",1);
    $pdf->cell(74,$alt,"Data Final",0,0,"C",1);
    $pdf->ln($alt*2);
  }

  $contador = 0;
  $Iwork=0;
  for($Iwork=0;$Iwork<count($work);$Iwork++){
    $contador++;
    if($pdf->gety() > $pdf->h - 30){
      $pdf->addpage();
      $pdf->setfont('arial','b',8) ;
      $pdf->ln($alt*8);
    }
    $pdf->cell(03,$alt, ($desc_vant == "a"?"AV - Avanco":"GR - Gratificacao"),0,0,"C",1);
    $pdf->cell(25,$alt, db_formatar($work[$Iwork]["wv_anos"],"f"),0,0,"C",1);
    if( !db_empty($work[$Iwork]["wv_inf"])){
      $pdf->cell(32,$alt, $work[$Iwork]["wv_inf"],0,0,"C",1);
    }else{
      $pdf->cell(32,$alt, db_formatar($work[$Iwork]["wv_perc"],"f"),0,0,"C",1);;
    }
    $pdf->cell(40,$alt, $work[$Iwork]["wv_dtvant"],0,0,"C",1);
    $pdf->cell(52,$alt, $work[$Iwork]["wv_lei"],0,0,"C",1);
    global $leis;
    if(db_selectmax("leis","select * from pesleis where h08_numero = ".db_sqlformat($work[$Iwork]["wv_lei"])) ){
      $pdf->cell(60,$alt, $leis[0]["h08_dtini"],0,0,"C",1);
      $pdf->cell(74,$alt, $leis[0]["h08_dtfim"],0,0,"C",1);
    }
  }
  return $pdf;
}

function regime($par){
  if($par == 1){
    $resp = "estatutário";
  }else if($par == 2){
    $resp = "clt";
  }else if($par == 3){
    $resp = "extra-quadro";
  }

  return $resp;

}

function imprime_assenta_fastamento($cparam,$matric,$datafim,$pdf, $oDadosPessoal){
  global $vinculos,$pessoal,$funcao,$depend,$subpes,$pai, $mae;

  //  descricao : relatorio assentamento e afastamento por funcionario ;

  if( $cparam > 0){
    $cert = true;
  }


  $regime = $oDadosPessoal->r01_regime;
  $cargo  =  $oDadosPessoal->r37_descr;
  $dt_admiss = $oDadosPessoal->r01_admiss;
  $ordem = "d";
  $qual_tipo = "g";

  /////////////////////////////////////////////////////////////////////////////////////////////////////////;
  global $work,$cgm;
  cria_work_assent_afast_func($matric,$datafim,$cparam,$qual_tipo);
  ///////////////////////////////////////////////////////////////////////////////////////////////////ç;

  //   $head3 ="Assentamentos/Afastamentos por Funcionario";
  //   $head5 = "Matriculas : ".db_str($matric,6)."-".$cgm[0]["z01_nome"];

  //   $pdf = new PDF();
  //   $pdf->Open();
  $pdf->AliasNbPages();
  $total = 0;
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',8);
  $alt = 4;
  $regenc = false;
  $troca = true;
  for($Iwork=0;$Iwork<count($work);$Iwork++){
    if ($pdf->gety() > $pdf->h - 35 || $troca ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      //                 99/99/9999 99/99/9999999,999 xxxxxx xxxxxx xxxxx xxxxxxxxxxxxxxxxxxxxxxxxx    xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx ;
      $pdf->cell(15,$alt,"DT Inicial",0,0,"C",1);
      $pdf->cell(15,$alt,"DT Final",0,0,"C",1);
      $pdf->cell(15,$alt,"Dias",0,0,"C",1);
      $pdf->cell(20,$alt,"Ato",0,0,"C",1);
      $pdf->cell(15,$alt,"N. ato",0,0,"C",1);
      $pdf->cell(100,$alt,"Afastamento/assentamento",0,1,"C",1);
      $troca = false;
    }
    global $tipoasse;
    db_selectmax("tipoasse","select * from tipoasse where h12_codigo = ".db_sqlformat($work[$Iwork]["c_tipo"]));

    $pdf->setfont('arial','',7);
    if( !db_boolean($tipoasse[0]["h12_regenc"])){
      if( db_boolean($work[$Iwork]["conver"])){
        $pdf->cell(15,$alt,db_formatar(db_substr( db_dtoc($work[$Iwork]["datini"]), -7 ),'d'),0,0,"C",0);
        $pdf->cell(15,$alt,db_formatar(db_substr( db_dtoc($work[$Iwork]["datfim"]), -7 ),'d'),0,0,"C",0);
      }else{
        $pdf->cell(15,$alt,db_formatar($work[$Iwork]["datini"],'d'),0,0,"C",0);
        $pdf->cell(15,$alt,db_formatar($work[$Iwork]["datfim"],'d'),0,0,"C",0);
      }

      $pdf->cell(15,$alt,$work[$Iwork]["dias"],0,0,"C",0);
      $pdf->cell(20,$alt,db_substr($work[$Iwork]["ato"],1,6),0,0,"C",0);
      $pdf->cell(15,$alt,$work[$Iwork]["nport"],0,0,"L",0);
      $pdf->cell(100,$alt,$tipoasse[0]["h12_assent"]." ".db_substr($tipoasse[0]["h12_descr"],1,26),0,0,"L",0);
      $pdf->ln($alt);
      $pdf->cell(15,$alt,"Historico :",0,0,"C",1);
      $pdf->multicell(150,$alt,$work[$Iwork]["histor"].$work[$Iwork]["hist2"],0,"J",0,25);
    }else{
      $regenc = true;
    }
  }
  // retirar a mudanca de pagina do grupo de regencia de classe. processo 264/03;
  if( $regenc){
    $troca = true;
    for($Iwork=0;$Iwork<count($work);$Iwork++){
      if ($pdf->gety() > $pdf->h - 30 || $troca ){
        $pdf->addpage();
        $pdf->setfont('arial','b',8);
        $pdf->ln($alt*9);
        // subcabec de regencia de classe - sem o bb_cabec ;
        $pdf->cell(0,$alt," Reg. de classe",0,1,"C",1);
        $pdf->cell(15,$alt,"DT Inicial",0,0,"C",1);
        $pdf->cell(15,$alt,"DT Final",0,0,"C",1);
        $pdf->cell(15,$alt,"Dias",0,0,"C",1);
        $pdf->cell(20,$alt,"Ato",0,0,"C",1);
        $pdf->cell(25,$alt,"N. ato Regencia",0,0,"C",1);
        $pdf->cell(100,$alt,"Tipo",0,0,"C",1);
        $pdf->ln($alt*2);
        $troca = false;

      }
      db_selectmax("tipoasse","select * from tipoasse where h12_codigo = ".db_sqlformat($work[$Iwork]["c_tipo"]));
      if( db_boolean($tipoasse[0]["h12_regenc"])){
        if( db_boolean($work[$Iwork]["conver"]) ){
          $pdf->cell(15,$alt,db_substr( db_dtoc($work[$Iwork]["datini"]), -7 ),0,0,"C",0);
          $pdf->cell(15,$alt,db_substr( db_dtoc($work[$Iwork]["datfim"]), -7 ),0,0,"C",0);
        }else{
          $pdf->cell(15,$alt,$work[$Iwork]["datini"],0,0,"C",0);
          $pdf->cell(15,$alt,$work[$Iwork]["datfim"],0,0,"C",0);
        }
        $pdf->cell(15,$alt,$work[$Iwork]["dias"],0,0,"C",0);
        $pdf->cell(20,$alt,db_substr($work[$Iwork]["ato"],1,6),0,0,"C",0);
        $pdf->cell(25,$alt,$work[$Iwork]["nport"],0,0,"C",0);
        $pdf->cell(100,$alt,$tipoasse[0]["h12_assent"]." ".db_substr($tipoasse[0]["h12_descr"],1,26),0,0,"C",0);
        $pdf->ln($alt);
        $pdf->cell(15,$alt,"Historico :",0,1,"C",1);
        $pdf->multicell(150,$alt,$work[$Iwork]["histor"].$work[$Iwork]["hist2"],0,"J",0,25);
      }
    }
  }
  return $pdf;
}

function regime_553($regime){

  if(      $regime == 1){
    $retorno = "estatutario";
  }else if($regime == 2){
    $retorno = "celetista";
  }else if($regime == 3){
    $retorno = "extra quadro";
  }
  return $retorno;
}

function cria_work_assent_afast_func($matric,$datafim,$cparam,$qual_tipo){

  global $cert,$tipoasse,$qual_tipo;

  $cert = true;
  $arquivo = "arq3";
  $regime = 0;
  $cargo = bb_space(30);
  $dt_admiss = "";
  $ordem = "d";
  $chave      = true;
  $chave1     = true;

  $m_nome  = array();
  $m_tipo  = array();
  $tamanho = array();
  $decimal = array();

  $m_nome[1] = "datini";
  $m_nome[2] = "datfim";
  $m_nome[3] = "ato";
  $m_nome[4] = "nport";
  $m_nome[5] = "c_tipo";
  $m_nome[6] = "descr";
  $m_nome[7] = "histor";
  $m_nome[8] = "hist2";
  $m_nome[9] = "dias";
  $m_nome[10] = "conver";

  $m_tipo[1] = "d";
  $m_tipo[2] = "d";
  $m_tipo[3] = "c";
  $m_tipo[4] = "c";
  $m_tipo[5] = "n";
  $m_tipo[6] = "c";
  $m_tipo[7] = "m";
  $m_tipo[8] = "c";
  $m_tipo[9] = "n";
  $m_tipo[10] = "l";

  $tamanho[1] = 8;
  $tamanho[2] = 8;
  $tamanho[3] = 15;
  $tamanho[4] = 10;
  $tamanho[5] = 5;
  $tamanho[6] = 40;
  $tamanho[7] = 240;
  $tamanho[8] = 240;
  $tamanho[9] = 10;
  $tamanho[10] = 1;

  $decimal[1] = 0;
  $decimal[2] = 0;
  $decimal[3] = 0;
  $decimal[4] = 0;
  $decimal[5] = 0;
  $decimal[6] = 0;
  $decimal[7] = 0;
  $decimal[8] = 0;
  $decimal[9] = 0;
  $decimal[10] = 0;

  db_criatemp($arquivo,$m_nome,$m_tipo,$tamanho,$decimal);

  $condicaoaux  = " where h16_regist = ".db_sqlformat(db_str($matric,6));
  $condicaoaux .= " and h16_dtconc <= ".db_sqlformat( $datafim );
  global $assenta;
  if( db_selectmax("assenta","select * from assenta inner join assentamentofuncional on rh193_assentamento_funcional = h16_codigo ". $condicaoaux )){

    for($Iassenta=0;$Iassenta < count($assenta);$Iassenta++){

      if($cparam == 0){


        if( $qual_tipo != "g"){

          // filtra pelo tipo selecionado;
          $csql  = "select * from tipoasse ";
          $csql .= " where h12_codigo = " . db_sqlformat($assenta[$Iassenta]["h16_assent"]);
          $csql .=   " and h12_tipo=" . $qual_tipo;

        }else{
          $csql  = "select * from tipoasse where h12_codigo = ".db_sqlformat($assenta[$Iassenta]["h16_assent"]);
        }

      }else{
        $csql  = "select * from tipoasse where h12_codigo = ".db_sqlformat($assenta[$Iassenta]["h16_assent"]);
      }

      global $tipoasse;
      db_selectmax("tipoasse",$csql);

      if( !db_empty($assenta[$Iassenta]["h16_quant"])){
        $quant = $assenta[$Iassenta]["h16_quant"];
      }else{
        if( db_empty($assenta[$Iassenta]["h16_dtterm"])){
          $dtterm = $datafim;
        }else{
          $dtterm = $assenta[$Iassenta]["h16_dtterm"];
        }
        $quant = db_datedif($dtterm,$assenta[$Iassenta]["h16_dtconc"]);
      }

      $chave = false;

      $mat_campos  = array();
      $mat_valores = array();

      $mat_campos[1]  = "datini";
      $mat_campos[2]  = "datfim";
      $mat_campos[3]  = "ato";
      $mat_campos[4]  = "nport";
      $mat_campos[5]  = "c_tipo";
      $mat_campos[6]  = "descr";
      $mat_campos[7]  = "histor";
      $mat_campos[8]  = "hist2";
      $mat_campos[9]  = "dias";
      $mat_campos[10] = "conver";

      $mat_valores[1]  = $assenta[$Iassenta]["h16_dtconc"];
      if( db_empty($assenta[$Iassenta]["h16_dtterm"])){
        $mat_valores[2]  = $datafim;
      }else{
        $mat_valores[2]  = $assenta[$Iassenta]["h16_dtterm"];
      }
      $mat_valores[3]  = $assenta[$Iassenta]["h16_atofic"];
      $mat_valores[4]  = pg_escape_string($assenta[$Iassenta]["h16_nrport"]);
      $mat_valores[5]  = $assenta[$Iassenta]["h16_assent"];
      $mat_valores[6]  = pg_escape_string($tipoasse[0]["h12_descr"]);
      $mat_valores[7]  = pg_escape_string(($assenta[$Iassenta]["h16_histor"]));
      $mat_valores[8]  = pg_escape_string(($assenta[$Iassenta]["h16_hist2"]));
      $mat_valores[9]  = $quant;
      $mat_valores[10] = $assenta[$Iassenta]["h16_conver"];

      db_insert($arquivo,$mat_campos,$mat_valores);

    }

    global $work;
    db_selectmax("work","select * from " .$arquivo." order by datini,c_tipo");
  }
}

function enquadra_na_lei(){

  global $leis,$tipo_vantagem,$data_ultima_nomeacao,$anos,$work,$Iwork,$data_fim,$pessoal,$arq_work,$anos_ant,$anos_perc;

  if( strtolower($tipo_vantagem) == strtolower($leis[0]["h08_tipo"]) ){

    // enquadra numero de anos para buscar percentual da vantagem;

    if( !db_empty($data_ultima_nomeacao)){
      if( !db_empty($leis[0]["h08_dtfim"])){
        if( db_mktime($data_ultima_nomeacao) > db_mktime($leis[0]["h08_dtfim"])){
          return;
        }
      }
    }

    $grava_codigo_lei = true;
    $percentual = 0;
    for($i=1;$i<=18;$i++){

      if( $i < 10){
        $campo = db_str($i,1);
      }else{
        $campo = db_str($i,2,0,"0");
      }
      if( db_empty($leis[0]["h08_perc".$campo]) || db_empty($leis[0]["h08_anos".$campo])){
        break;
      }
      $percentual  = $leis[0]["h08_perc".$campo];
      $inform      = $leis[0]["h08_car".$campo];
      $anos_perc   = $leis[0]["h08_anos".$campo];
      if( $anos >= $anos_perc && !db_empty($anos_perc) && $anos_perc > $anos_ant){
        $dt_vantagem   = $data_vantagem[$anos_perc];
        $dias_protelar = $dias_prot[$anos_perc];

        // grava registro calculado se funcionario tem percentual a receber e a;
        // lei ainda eh valida.;
        if( db_mktime($data_ultima_nomeacao) > db_mktime($dt_vantagem)){
          $data_consessao = $data_ultima_nomeacao;
        }else{
          $data_consessao = $dt_vantagem;
        }

        if( db_mktime($data_consessao) < db_mktime($leis[0]["h08_dtini"]) && $leis[0]["h08_numero"] == $work[$Iwork]["wv_lei"]){
          if( count($work) > 0){
            if( !db_empty($oDadosPessoal->r01_regist)){
              db_delete($arq_work," where wv_regist = " . db_sqlformat($work[$Iwork]["wv_regist"]) . " and wv_dtvant = ".db_sqlformat( $work[$Iwork]["wv_dtvant"] ));
            }

            $grava_codigo_lei = true;
          }
          $data_consessao = $leis[0]["h08_dtini"];
        }

        if( ( db_mktime($dt_vantagem) <= db_mktime($leis[0]["h08_dtfim"] || db_empty($leis[0]["h08_dtfim"]) ) && db_mktime($data_fim) >= db_mktime($data_consessao)) ){
          $condicaoaux = " where z01_numcgm = ".db_sqlformat( $oDadosPessoal->r01_numcgm );
          global $cgm;
          db_selectmax( "cgm", "select z01_nome from cgm ".$condicaoaux );
          $mar1 = array();
          $mar2 = array();

          $mar1[01] = "wv_regist";
          $mar1[02] = "wv_lotac";
          $mar1[03] = "wv_dtbase";
          $mar1[04] = "wv_dtvant";
          $mar1[05] = "wv_anos";
          $mar1[06] = "wv_perc";
          $mar1[07] = "wv_tipo";
          $mar1[08] = "wv_protel";
          $mar1[09] = "wv_inf";
          $mar1[10] = "wv_lei";
          $mar1[11] = "wv_nome";

          $mar2[01] = $oDadosPessoal->r01_regist;
          $mar2[02] = $oDadosPessoal->r01_lotac ;
          $mar2[03] = $data_admissao;
          $mar2[04] = $data_consessao;
          $mar2[05] = $anos_perc;
          $mar2[06] = $percentual;
          $mar2[07] = $tipo_vantagem;
          $mar2[08] = $dias_protelar;
          $mar2[09] = $inform;
          $mar2[10] = bb_space(6);
          $mar2[11] = $cgm[0]["z01_nome"];

          if( $grava_codigo_lei || db_empty($registro)){
            $mar2[10] = $leis[0]["h08_numero"];
            $grava_codigo_lei = false;
            if( db_mktime($data_consessao) < db_mktime($leis[0]["h08_dtini"])){
              $mar2[4] = $leis[0]["h08_dtini"];
            }
          }
          db_insert($arq_work,$mar1,$mar2);
          $anos_ant = $anos_perc;
        }
        //**********************************************************************;

      }

      db_selectmax($work, "select * from ".$arq_work );

    }

  }
}

function busca_lei($lei,$tipo_vantagem){

  global $leis;
  $retornar = false;

  if(!db_empty($lei)){
    if(!db_selectmax("leis","select * from pesleis where h08_numero = " . db_sqlformat($lei). " and h08_tipo = " . db_sqlformat($tipo_vantagem))){
      $retornar = false;
    }else{
      $retornar = true;
    }
  }else{
    $retornar = false;
  }
  return $retornar;
}			

include(modification("fpdf151/pdf1.php"));
include(modification("libs/db_libpessoal.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("libs/db_sql.php"));
include(modification("rec2_gradeefetividade003.php"));

global $cfpess,$subpes,$d08_carnes;

db_postmemory($HTTP_GET_VARS);

$subpes = db_anofolha().'/'.db_mesfolha();

certidao_comprobatorio($regist,$datacert,$numcert,$emissor);
