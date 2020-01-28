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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/verticalTab.widget.php"));

$k00_matric = null;
$k00_inscr  = null;

$oArrebanco = new stdClass;

$mat_numpre = split("#",base64_decode(@$HTTP_SERVER_VARS['QUERY_STRING']));
//var_dump(base64_decode(@$HTTP_SERVER_VARS['QUERY_STRING']));
//echo $mat_numpre[0]."-";
//echo $mat_numpre[1]."-";
//echo $mat_numpre[2];
$tipo   = $mat_numpre[0];
$numpre = $mat_numpre[1];
$numpar = $mat_numpre[2];

$iNumpreAbatimento = 0;
if (!empty($mat_numpre[3])) {

  $iNumpreAbatimento = $mat_numpre[3];
  $iNumpreOriginal   = $numpre;
}

$lista = true;
$gera = true;
//die ($tipo);
$sql = "select k03_tipo
        from arretipo
        where k00_tipo = $tipo and k00_instit = ".db_getsession('DB_instit') ;
//die($sql);
$result = db_query($sql);
$numrows = pg_numrows($result);
if($numrows > 0){
  db_fieldsmemory($result,0);
}else{
  $k03_tipo = null;
}

//echo "<br>k03_tipo=$k03_tipo<br>";

$sql_arrebanco_nbant = "select array_to_string( array_accum(arrebanco.k00_nbant), ', ' ) as k00_nbant from caixa.recibopaga inner join caixa.arrebanco on arrebanco.k00_numpre = recibopaga.k00_numnov where recibopaga.k00_numpre = $numpre";
//echo $sql_arrebanco_nbant;
$rs_arrebanco_nbant = db_query($sql_arrebanco_nbant) or die($sql_arrebanco_nbant);
$oArrebanco->k00_nbant = pg_result($rs_arrebanco_nbant,0,0);

/**
 * Legend dos fieldset
 */
switch ( $k03_tipo ) {

  case 1 :
    $sLegend = 'IPTU';
  break;

  case 2 :
    $sLegend = 'ISSQN FIXO';
  break;

	case 3 :
		$sLegend = 'ISSQN VARIÁVEL';
	break;

  case 4 :
    $sLegend = 'Contribuição de melhoria';
  break;

	case 5 :
		$sLegend = 'Divida ativa';
	break;

  case 7 :
    $sLegend = 'Módulo diversos';
  break;

  case 9 :
    $sLegend = 'ALVARÁ';
  break;

	case 15 :
		$sLegend = 'Certidão do foro';
	break;

  case 6  :
  case 13 :
  case 17 :
  case 30 :
    $sLegend = 'Parcelamento';
  break;

	case 14 :
  default :
		$sLegend = 'Protocolo geral';
  break;
}

/**
 * ------------------------------------------------------------------------------------------------
 * IPTU
 * ------------------------------------------------------------------------------------------------
 */
if ( $k03_tipo == 1 ) {
  // iptu
  $sql = "select *
          from arrematric
          where k00_numpre = $numpre";
  $result = db_query($sql);
  if(pg_numrows($result)==0){
    echo "Código de Arrecadacao nao cadastrado no arrematric.";
    exit;
  }else{
    db_fieldsmemory($result,0,'1');
  }
  $sql = "select proprietario.*
          from proprietario
          where j01_matric = $k00_matric";
  $result = db_query($sql);
  if(pg_numrows($result)==0){
    echo "Matrícula nao cadastrada em proprietario.";
    exit;
  }else{
    db_fieldsmemory($result,0,'1');
  }
}else if($k03_tipo==7 ){
  // diversos
  $sql = "select diversos.*, z01_nome, k00_inscr,k00_matric , dv09_descr
          from diversos
          inner join arreinstit on arreinstit.k00_numpre = diversos.dv05_numpre
                                and arreinstit.k00_instit = ".db_getsession('DB_instit')."
         left outer join arrematric on arrematric.k00_numpre = diversos.dv05_numpre
         left outer join arreinscr  on arreinscr.k00_numpre = diversos.dv05_numpre
         inner join procdiver       on procdiver.dv09_procdiver = diversos.dv05_procdiver
               inner join cgm on dv05_numcgm = z01_numcgm
          where diversos.dv05_numpre = $numpre and dv05_instit = ".db_getsession('DB_instit') ;
  $result = db_query($sql);
  if(pg_numrows($result)==0){
    echo "Código de Arrecadacao nao cadastrado no diversos.";
    exit;
  }else{
    db_fieldsmemory($result,0,'1');
  }
}else if($k03_tipo == 16 and 1==2){
  // parcelamento diversos
  $sql = "select parcdiver.*
          from parcdiver
           inner join cgm on numcgm = z01_numcgm
          where k00_numpre = $numpre";
  echo $sql;
  $result = db_query($sql);
  if(pg_numrows($result)==0){
    echo "Parcelamento nao cadastrada no diversos.";
    exit;
  }else{
    db_fieldsmemory($result,0,'1');
  }
}else if($k03_tipo==9 ||$k03_tipo==2 || $k03_tipo==3) {
  if($k03_tipo==3){
    //db_msgbox("arreinscr");
    // variavel

    $sql = "select arrepaga.*,
                   issvar.*,
                   arreinscr.k00_inscr,
                   arrecant.k00_tipo,
                   issplan.*
              from arreinscr
                   inner join arrepaga   on arrepaga.k00_numpre   = arreinscr.k00_numpre
                   inner join arrecant   on arrecant.k00_numpre   = arreinscr.k00_numpre
                   inner join arreinstit on arreinstit.k00_numpre = arrepaga.k00_numpre
                                         and arreinstit.k00_instit = ".db_getsession('DB_instit')."
                   inner join issvar     on arrepaga.k00_numpre   = issvar.q05_numpre and arrepaga.k00_numpar = issvar.q05_numpar
                   left join issplan     on q05_numpre            = q20_numpre
                                        and arrepaga.k00_numcgm   = q20_numcgm
             where arreinscr.k00_numpre = $numpre";

    $sql .= " union all ";
    $sql .= "select distinct arrepaga.*,
                             issvar.*,
                             0 as k00_inscr,
                             arrecant.k00_tipo,
                             issplan.*
                        from arrenumcgm
                             inner join arrepaga   on arrepaga.k00_numpre   = arrenumcgm.k00_numpre
                             inner join arrecant   on arrecant.k00_numpre   = arrenumcgm.k00_numpre
                             inner join arreinstit on arreinstit.k00_numpre = arrepaga.k00_numpre
                                                  and arreinstit.k00_instit = ".db_getsession('DB_instit')."
                             inner join issvar     on arrepaga.k00_numpre   = issvar.q05_numpre
                                                  and arrepaga.k00_numpar   = issvar.q05_numpar
                             left join issplan     on q05_numpre            = q20_numpre
                                                  and  arrepaga.k00_numcgm  = q20_numcgm
                       where arrenumcgm.k00_numpre = $numpre";


  }else{
    $sql = "select *
          from arreinscr
           inner join arrepaga on arrepaga.k00_numpre = arreinscr.k00_numpre
           inner join arreinstit on arreinstit.k00_numpre = arrepaga.k00_numpre
                                 and arreinstit.k00_instit = ".db_getsession('DB_instit')."
         inner join isscalc on arrepaga.k00_numpre = isscalc.q01_numpre
          where arreinscr.k00_numpre = $numpre";
  }

  $sql .= ($numpar>0?" and arrepaga.k00_numpar=$numpar":"");
  //die($sql);
  $result = db_query($sql);
  if(pg_numrows($result)==0){
    echo "Código de Arrecadacao nao cadastrado no arreinscr.";
    exit;
  }else{
    db_fieldsmemory($result,0,'1');
  }

  if ( $k00_inscr > 0 ) {
    $sql = "select empresa.*
            from empresa
            where q02_inscr = $k00_inscr";
    $result = db_query($sql);
    if(pg_numrows($result)==0){
      echo "Empresa nao cadastrada no issbase.";
      exit;
    }else{
      db_fieldsmemory($result,0,'1');
    }
  }

}else if($k03_tipo==4){

  $sql = "select *
          from arrematric
          where k00_numpre = $numpre";
  $result = db_query($sql);
  if(pg_numrows($result)==0){
    echo "Código de Arrecadacao nao cadastrado no arrematric.";
    exit;
  }else{
    db_fieldsmemory($result,0,'1');
  }
  $sql = "select proprietario.*
          from proprietario
          where j01_matric = $k00_matric";
  $result = db_query($sql);
  if(pg_numrows($result)==0){
    echo "Matrícula nao cadastrada em proprietario.";
    exit;
  }else{
    db_fieldsmemory($result,0,'1');
  }
  $sql  = " select edital.d01_numero,contrib.d07_data,contrib.d07_contri,contrib.d07_valor,contr.j14_nome";
  $sql .= " from arrematric";
  $sql .= "      inner join arreinstit on arreinstit.k00_numpre = arrematric.k00_numpre   ";
  $sql .= "                            and arreinstit.k00_instit = ".db_getsession('DB_instit');
  $sql .= "      left join contricalc on d09_numpre = k00_numpre \n";
  $sql .= "      left join contrib on d07_contri = d09_contri and d07_matric = d09_matric \n";
  $sql .= "      left join editalrua on d07_contri = d02_contri \n";
  $sql .= "      left join ruas  contr on d02_codigo = contr.j14_codigo \n";
  $sql .= "      left join edital on d02_codedi = d01_codedi \n";
  $sql .= " where k00_numpre = $numpre";
  $result = db_query($sql);
  if(pg_numrows($result)==0){
    echo "Código de Arrecadacao nao cadastrado Na Constribuição.  ";
    exit;
  }else{
    db_fieldsmemory($result,0,'1');
  }

}else if($k03_tipo==5){
  $sql = "select d.*,p.*,c.z01_nome, m.v01_matric, i.v01_inscr
           from divida d
            left outer join cgm       c on c.z01_numcgm = d.v01_numcgm
        left outer join proced    p on d.v01_proced = p.v03_codigo
        left outer join divmatric m on m.v01_coddiv = d.v01_coddiv
        left outer join divinscr  i on i.v01_coddiv = d.v01_coddiv
       where d.v01_numpre = $numpre and v01_instit = ".db_getsession('DB_instit') ;
  if($numpar!=0)
  $sql .= " and d.v01_numpar = $numpar ";

  $result = db_query($sql);
  if(pg_numrows($result)==0){
    echo "Código de Arrecadacao nao cadastrado.";
    exit;
  }else{
    db_fieldsmemory($result,0,'1');
  }
}else if($k03_tipo==6 or $k03_tipo==17 or $k03_tipo==13 or $k03_tipo==30 or $k03_tipo == 16){
  $v01_proced="&nbsp;" ;
  $k00_matric="&nbsp;" ;
  $k00_inscr="&nbsp;" ;
  $v01_exerc="&nbsp;" ;
  $v03_descr="&nbsp;" ;
  $certid   ="&nbsp;" ;
  // parcelamento divida ativa
  $sql  = "select t.*,c.z01_nome as nome_resp, c.z01_nome
           from termo t
            left outer join cgm c on c.z01_numcgm = t.v07_numcgm
       where t.v07_numpre = $numpre and v07_instit = ".db_getsession('DB_instit') ;
  $result = db_query($sql);
  if(pg_numrows($result)==0){
    echo "Código de Arrecadacao nao cadastrado.";
    exit;
  }else{
    db_fieldsmemory($result,0,true);
    $sql = "select distinct d.v01_proced,d.v01_exerc,k00_matric,k00_inscr, z01_nome, v03_descr
             from termodiv t
            inner join divida d on d.v01_coddiv = t.coddiv and v01_instit = ".db_getsession('DB_instit')."
            left outer join proced     p on p.v03_codigo = d.v01_proced
            left outer join cgm        c on d.v01_numcgm = c.z01_numcgm
                  left outer join arrematric a on d.v01_numpre = a.k00_numpre
                left outer join arreinscr  i on d.v01_numpre = i.k00_numpre
         where t.parcel = $v07_parcel";
    if($numpar!=0)
    $sql .= " and d.v01_numpar = $numpar ";
    $result = db_query($sql);
    if(pg_numrows($result)>0){
      db_fieldsmemory($result,0,true);
    }else{

      //       $z01_nome = "Não existe dados de referencia no termodiv.";

       $sql = "select * from termoini where parcel = $v07_parcel";
       $result = db_query($sql);
       if(pg_numrows($result)>0){

       } else {

         $sql  = "select k00_matric as j01_matric, k00_inscr as q02_inscr from termo
        left outer join arrematric on v07_numpre = arrematric.k00_numpre
          left outer join arreinscr  on v07_numpre = arreinscr.k00_numpre
           where v07_numpre = $numpre and v07_instit = ".db_getsession('DB_instit') ;
         $result = db_query($sql);
         if(pg_numrows($result)>0){
           db_fieldsmemory($result,0,true);
         } else {
           $v01_proced="&nbsp;" ;
           $k00_matric="&nbsp;" ;
           $k00_inscr="&nbsp;" ;
           $v01_exerc="&nbsp;" ;
           $v03_descr="&nbsp;" ;
         }
       }
    }
  }
}else if($k03_tipo==15){
  // certidao foro
  $sql  = "select ce.*,d.v01_obs
           from divida d
            inner join certdiv c on c.v14_coddiv = d.v01_coddiv
            inner join certid ce on ce.v13_certid = c.v14_certid
       where d.v01_numpre = $numpre and v01_instit = ".db_getsession('DB_instit') ." limit 1";
  $result = db_query($sql);
  if(pg_numrows($result)==0){
    echo "Código de Arrecadacao nao cadastrado.";
    exit;
  }else{
    db_fieldsmemory($result,0,true);
  }
}else if($k03_tipo==13){
  // inicial
  $sql  = "select k00_matric as j01_matric, k00_inscr as q02_inscr from termo
      left outer join arrematric on v07_numpre = arrematric.k00_numpre
      left outer join arreinscr  on v07_numpre = arreinscr.k00_numpre
       where v07_numpre = $numpre and v07_instit = ".db_getsession('DB_instit') ;
  $result = db_query($sql);
  if(pg_numrows($result)>0){
    db_fieldsmemory($result,0,true);
  } else {
    $v01_proced="&nbsp;" ;
    $k00_matric="&nbsp;" ;
    $k00_inscr="&nbsp;" ;
    $v01_exerc="&nbsp;" ;
    $v03_descr="&nbsp;" ;
  }
  $sql  = "select t.*,c.z01_nome as nome_resp
           from termo t
            left outer join cgm c on c.z01_numcgm = t.v07_numcgm
       where t.v07_numpre = $numpre and v07_instit = ".db_getsession('DB_instit') ;
  $result = db_query($sql);
  db_fieldsmemory($result,0,true);
}else if($k03_tipo==14){
  $sql  = "select * from recibo
                inner join arreinstit on arreinstit.k00_numpre = recibo.k00_numpre
                                     and arreinstit.k00_instit = ".db_getsession('DB_instit')."
                inner join cgm on z01_numcgm = k00_numcgm
           where recibo.k00_numpre = $numpre";
  $result = db_query($sql);
  if(pg_numrows($result)>0) {
    db_fieldsmemory($result,0,true);
  } else {

    $sql  = "select * from arrepaga
                  inner join arreinstit on arreinstit.k00_numpre = arrepaga.k00_numpre
                                       and arreinstit.k00_instit = ".db_getsession('DB_instit')."
                  inner join cgm on z01_numcgm = k00_numcgm
             where arrepaga.k00_numpre = $numpre";

    $result = db_query($sql);
    if(pg_numrows($result)>0) {
      db_fieldsmemory($result,0,true);
    } else {
      $z01_nome = null;
      $k00_dtoper = null;
      $k00_dtvenc = null;
      $k00_numpre = $numpre;
      $k00_valor  = null;
    }
  }

}else{

  $sql  = "select * from arrepaga
                inner join arreinstit on arreinstit.k00_numpre = arrepaga.k00_numpre
                                     and arreinstit.k00_instit = ".db_getsession('DB_instit')."
                inner join cgm on z01_numcgm = k00_numcgm
           where arrepaga.k00_numpre = $numpre";

  //echo "<br>$sql<br>";

  $result = db_query($sql);
  if(pg_numrows($result)>0) {
    db_fieldsmemory($result,0,true);
  } else {
    $z01_nome = null;
    $k00_dtoper = null;
    $k00_dtvenc = null;
    $k00_numpre = $numpre;
    $k00_valor  = null;
  }

}

if ( $k03_tipo == 1 ) {
	$sLegend .= ' - '.$j01_tipoimp;
}

?>
<html>
<head>
  <title>DBSeller Informática Ltda - DBPortal Versão:  2.2.73 </title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php db_app::load("estilos.css, grid.style.css, tab.style.css, scripts.js, strings.js, prototype.js, datagrid.widget.js, DBToogle.widget.js"); ?>
  <style>
    table.linhaZebrada {
      width: 100%;
    }

    table.linhaZebrada tr td:nth-child(even) {
      background-color: #FFF;
    }

    table.linhaZebrada tr td:nth-child(odd) {
      font-weight:bold;
      width:150px;
    }
  </style>
</head>
<body bgcolor="#cccccc">

<center>

<fieldset style="margin-top:10px;">

  <legend><strong><?php echo $sLegend; ?>: </strong></legend>

  <table class="linhaZebrada">

      <tr>
        <td>Numpre:</td>
        <td><?php echo $numpre; ?></td>

        <td>Numpar</td>
        <td><?php echo $numpar; ?></td>
      </tr>

    <?php
    /**
     * ------------------------------------------------------------------------------------------------
     * IPTU
     * ------------------------------------------------------------------------------------------------
     */
    if ( $k03_tipo == 1 ) { ?>

      <tr>
        <td>Matr&iacute;cula:</td>
        <td>&nbsp; <?=$j01_matric?></td>

        <td>Propriet&aacute;rio/Promitente:</td>
        <td>&nbsp; <?=substr($z01_nome,0,35)?></td>
      </tr>

      <tr>
        <td>Endere&ccedil;o:</td>
        <td>&nbsp; <?=substr($z01_ender,0,35)?></td>

        <td>Munic&iacute;pio:</td>
        <td>&nbsp; <?=substr($z01_munic,0,35)?></td>
      </tr>

      <tr>
        <td>Propriet&aacute;rio:</td>
        <td>&nbsp; <?=substr($proprietario,0,35)?></td>

        <td>Setor/Quadra/Lote:</td>
        <td>&nbsp; <?=($j34_setor."/".$j34_quadra."/".$j34_lote)?></td>
      </tr>

      <tr>
        <td>Logradouro:</td>
        <td>&nbsp; <?=substr($nomepri,0,35)?></td>

        <td>N&uacute;mero:</td>
        <td>&nbsp; <?=$j39_numero?></td>
      </tr>

      <tr>
        <td>Complemeto:</td>
        <td>&nbsp; <?=$j39_compl?></td>

        <td>Bairro:</td>
        <td>&nbsp; <?=$j13_descr?></td>
      </tr>

    <?php
    /**
     * ------------------------------------------------------------------------------------------------
     * CONTRIBUIÇÃO DE MELHORIA
     * ------------------------------------------------------------------------------------------------
     */
    } else if ( $k03_tipo == 4 ) { ?>

      <tr>
        <td>Matr&iacute;cula:</td>
        <td>&nbsp; <?=$j01_matric?></td>

        <td>Propriet&aacute;rio/Promitente:</td>
        <td>&nbsp; <?=substr($z01_nome,0,35)?></td>
      </tr>

      <tr>
        <td>Endere&ccedil;o:</td>
        <td>&nbsp; <?=substr($z01_ender,0,35)?></td>

        <td>Munic&iacute;pio:</td>
        <td>&nbsp; <?=substr($z01_munic,0,35)?></td>
      </tr>

      <tr>
        <td>Propriet&aacute;rio:</td>
        <td>&nbsp; <?=substr($proprietario,0,35)?></td>

        <td>Setor/Quadra/Lote:</td>
        <td>&nbsp; <?=($j34_setor."/".$j34_quadra."/".$j34_lote)?></td>
      </tr>

      <tr>
        <td>Logradouro:</td>
        <td>&nbsp; <?=substr($nomepri,0,35)?></td>

        <td>N&uacute;mero:</td>
        <td>&nbsp; <?=$j39_numero?></td>
      </tr>

      <tr>
        <td>Complemeto:</td>
        <td>&nbsp; <?=$j39_compl?></td>

        <td>Bairro:</td>
        <td>&nbsp; <?=$j13_descr?></td>
      </tr>

      <tr>
        <td>Contribui&ccedil;&atilde;o:</td>
        <td>&nbsp; <?=$d07_contri?>&nbsp;Edital: <?=$d01_numero?></td>

        <td>Rua/Avenida:</td>
        <td>&nbsp; <?=substr($j14_nome,0,35)?></td>
      </tr>

      <tr>
        <td>Data Lan&ccedil;amento:</td>
        <td>&nbsp; <?=$d07_data?></td>

        <td>Valor Lan&ccedil;ado:</td>
        <td>&nbsp; <?=$d07_valor?></td>
      </tr>

    <?php
    /**
     * ------------------------------------------------------------------------------------------------
     * DIVIDA ATIVA
     * ------------------------------------------------------------------------------------------------
     */
    } else if ( $k03_tipo == 5 ) { ?>

      <tr>
        <td>Codigo D&iacute;vida:</td>
        <td>&nbsp; <?=$v01_coddiv?></td>

        <td>Nome:</td>
        <td>&nbsp; <?=substr($z01_nome,0,35)?></td>
      </tr>

      <tr>
        <td>Data inscri&ccedil;&atilde;o:</td>
        <td>&nbsp; <?=$v01_dtinsc?></td>

        <td>Exerc&iacute;cio:</td>
        <td>&nbsp; <?=$v01_exerc?></td>
      </tr>

      <tr>
        <td>Proced&ecirc;ncia:</td>
        <td><?=$v01_proced."-".$v03_descr?></td>

        <td nowrap>Matr&iacute;cula Im&oacute;vel:</td>
        <td>
          <?php
            if(pg_numrows($result)!=0){

              for($i=0;$i<pg_numrows($result);$i++){

                db_fieldsmemory($result,$i,'1');
                if($v01_matric!=""){
                  echo $v01_matric." - ";
                }
              }
           }
         ?>
        </td>
      </tr>

      <tr>
        <td>Inscri&ccedil;&atilde;o Alvar&aacute;:</td>
        <td>&nbsp;
          <?php
            if(pg_numrows($result)!=0){

              for($i=0;$i<pg_numrows($result);$i++){

                db_fieldsmemory($result,$i,'1');
                if($v01_inscr!=""){
                  echo $v01_inscr."<br>";
                }
              }
            }
          ?>
        </td>

        <td>Livro/Folha:</td>
        <td>&nbsp; <?=$v01_livro."/".$v01_folha?></td>
      </tr>

      <tr>
        <td>Valor Hist&oacute;rico:</td>
        <td>&nbsp; <?=$v01_vlrhis?></td>

        <td>Data Vencimento:</td>
        <td>&nbsp; <?=$v01_dtvenc?></td>
      </tr>

      <tr>
        <td>Data Valor:</td>
        <td>&nbsp; <?=$v01_dtoper?></td>

        <td>Numbanco anterior:</td>
        <td>&nbsp; <?=$oArrebanco->k00_nbant?></td>
      </tr>

      <tr>
        <td>Observa&ccedil;&atilde;o:</td>
        <td>&nbsp;
          <?php
            echo substr($v01_obs, 0, 50)."<br>";
            echo substr($v01_obs, 50, 50)."<br>";
            echo substr($v01_obs, 100, 17);
          ?>
        </td>
      </tr>

    <?php
    /**
     * ------------------------------------------------------------------------------------------------
     * MÓDULO DIVERSOS
     * ------------------------------------------------------------------------------------------------
     */
    } else if ( $k03_tipo == 7 ) { ?>

      <tr>
        <td>C&oacute;digo Diverso:</td>
        <td>&nbsp; <?=$dv05_coddiver?></td>

        <td>Data Inclus&atilde;o:</td>
        <td>&nbsp; <?=$dv05_dtinsc?></td>
      </tr>

      <tr>
        <td>Vencimento:</td>
        <td>&nbsp; <?=$dv05_privenc?></td>

        <td>Valor Lan&ccedil;ado:</td>
        <td>&nbsp; <?=$dv05_vlrhis?></td>
      </tr>

      <tr>
        <td>Proced&ecirc;ncia:</td>
        <td>&nbsp; <?=$dv05_procdiver.'-'.$dv09_descr?></td>

        <td>Contribu&iacute;nte:</td>
        <td>&nbsp; <?=$z01_nome?></td>
      </tr>

      <tr>
        <td>C&oacute;digo Arrecada&ccedil;&atilde;o:</td>
        <td>&nbsp; <?=$dv05_numpre?></td>

        <td>Hist&oacute;rico:</td>
        <td>&nbsp; <?=$dv05_obs?></td>
      </tr>

      <tr>
        <td>Matr&iacute;cula Im&oacute;vel:</td>
        <td> &nbsp; <?php echo $k00_matric; ?></td>

        <td>Inscri&ccedil;&atilde;o Alvar&aacute;:</td>
        <td>&nbsp; <?php echo $k00_inscr; ?></td>
      </tr>

    <?php
    /**
     * ------------------------------------------------------------------------------------------------
     * PARCELELAMENTO MÓDULO DIVERSOS
     * ------------------------------------------------------------------------------------------------
     */
    } else if ( $k03_tipo == 16 ) {

      $sSqlParcelamentoDiversos = " select *                                                                                  ";
      $sSqlParcelamentoDiversos = "   from termodiver                                                                         ";
      $sSqlParcelamentoDiversos = "        inner join termo        on v07_parcel            = dv10_parcel                     ";
      $sSqlParcelamentoDiversos = "                                and v07_instit            = ".db_getsession('DB_instit')." ";
      $sSqlParcelamentoDiversos = "        inner join cgm          on v07_numcgm            = z01_numcgm                      ";
      $sSqlParcelamentoDiversos = "        inner join arrecad      on k00_numpre            = v07_numpre                      ";
      $sSqlParcelamentoDiversos = "        inner join arreinstit   on arreinstit.k00_numpre = arrecad.k00_numpre              ";
      $sSqlParcelamentoDiversos = "                               and arreinstit.k00_instit = ".db_getsession('DB_instit')."  ";
      $sSqlParcelamentoDiversos = "                                                                                           ";
      $sSqlParcelamentoDiversos = "   left outer join arrematric a on v07_numpre            = a.k00_numpre                    ";
      $sSqlParcelamentoDiversos = "   left outer join arreinscr  i on v07_numpre            = i.k00_numpre                    ";
      $sSqlParcelamentoDiversos = " where v07_numpre = $numpre                                                                ";

      $rsParcelamentoDeiversos = db_query($sSqlParcelamentoDiversos);

      if ( pg_numrows($rsParcelamentoDeiversos) == 0 ) {

        echo "Parcelamento não cadastrado no diversos.";
        exit;
      } else {

        db_fieldsmemory($rsParcelamentoDeiversos, 0, '1'); ?>

        <tr>
          <td>C&oacute;digo do Parcelamento:</td>
          <td>&nbsp; <?=$dv10_parcel?></td>

          <td>Data Parcelamento:</td>
          <td>&nbsp; <?=$v07_dtlanc?></td>
        </tr>

        <tr>
          <td>Total Parcelas:</td>
          <td>&nbsp; <?=$v07_totpar?></td>

          <td>Valor Total Parcelado:</td>
          <td>&nbsp; <?=($k00_valor * $v07_totpar)?></td>
        </tr>

        <tr>
          <td>Valor Entrada:</td>
          <td>&nbsp; <?=$v07_vlrent?></td>

          <td>Data Primeira parcela:</td>
          <td>&nbsp; <?=$v07_datpri?></td>
        </tr>

        <tr>
          <td>Termo:</td>
          <form name="form1" method="post">
						<td>
							<input type="button" name="Submit3" value="Visualizar o Termo" onclick="js_AbreJanelaRelatorio();">
							<input type="hidden" id="v07_parcel" name="v07_parcel" value="<?=$v07_parcel?>">
						</td>
          </form>

          <td>Contribu&iacute;nte:</td>
          <td>&nbsp; <?=$z01_nome?></td>
        </tr>

        <tr>
          <td>Nome Respons&aacute;vel:</td>
          <td>&nbsp; <?=$z01_nome?></td>

          <td>C&oacute;digo Arrecada&ccedil;&atilde;o:</td>
          <td>&nbsp; <?=$k00_numpre?></td>
        </tr>

        <tr>
          <td>Matr&iacute;cula Im&oacute;vel:</td>
          <td>&nbsp; <?php echo $k00_matric; ?></td>

          <td>Inscri&ccedil;&atilde;o Alvar&aacute;:</td>
          <td>&nbsp; <?php echo $k00_inscr; ?></td>
        </tr>

      <?php
      } // fim else - possui parcelamento

    /**
     * ------------------------------------------------------------------------------------------------
     * PARCELELAMENTO
     * ------------------------------------------------------------------------------------------------
     */
    } else if( $k03_tipo == 6 || $k03_tipo == 17 || $k03_tipo == 13 || $k03_tipo == 30 ) { ?>

      <tr>
        <td>C&oacute;digo do Parcelamento:</td>
        <td>&nbsp; <?=$v07_parcel?></td>

        <td>Data Parcelamento:</td>
        <td>&nbsp; <?=$v07_dtlanc?></td>
      </tr>

      <tr>
        <td>Total Parcelas:</td>
        <td>&nbsp; <?=$v07_totpar?></td>

        <td>Valor Total Parcelado:</td>
        <td>&nbsp; <?=$v07_valor?></td>
      </tr>

      <tr>
        <td>Valor Entrada:</td>
        <td>&nbsp; <?=$v07_vlrent?></td>

        <td>Data Primeira parcela:</td>
        <td>&nbsp; <?=$v07_datpri?></td>
      </tr>

      <tr>
        <td>Contribu&iacute;nte:</td>
        <td>&nbsp; <?=$z01_nome?></td>

        <td>Nome Respons&aacute;vel:</td>
        <td>&nbsp; <?=$nome_resp?></td>
      </tr>

      <tr>
        <td>Termo:</td>
				<td align="center">
					<input type="button" name="Submit3" value="Visualizar o Termo" onclick="js_AbreJanelaRelatorio();" />
					<input type="hidden" id="v07_parcel" name="v07_parcel" value="<?=$v07_parcel?>" />
				</td>

        <td>C&oacute;digo Arrecada&ccedil;&atilde;o:</td>
        <td>&nbsp; <?=$v07_numpre?></td>
      </tr>

      <tr>
        <td>Hist&oacute;rico:</td>
        <td>&nbsp; <?=$v07_hist?></td>

        <td>Matr&iacute;cula Im&oacute;vel:</td>
				<td>
					<?php
						if ( pg_numrows( $result ) != 0 ) {

							for ( $i = 0; $i < pg_numrows($result); $i++ ) {

								db_fieldsmemory( $result ,$i, '1' );
								if ( $k03_tipo == 13 ) {
								 echo $certid."<br>";
								} else {

									if ( $k00_matric != "" ) {
										echo $k00_matric." - ".$v01_exerc." - ".$v01_proced." - ".$v03_descr."<br>";
									}
								}
							}
						}
					?>
				</td>
      </tr>

      <tr>
        <td>Inscri&ccedil;&atilde;o Alvar&aacute;:</td>
				<td>&nbsp;
					<?php
						if ( pg_numrows( $result ) != 0 ) {

							for ( $i = 0; $i < pg_numrows( $result ); $i++ ) {

								db_fieldsmemory( $result, $i, '1' );
								if ( $k00_inscr != "" ) {
									echo $k00_inscr."-".$v01_exerc."-".$v01_proced."<br>";
								}
							}
						}
					?>
				</td>
      </tr>

    <?php
    /**
     * ------------------------------------------------------------------------------------------------
     * ISSQN FIXO E ALVARÁ
     * ------------------------------------------------------------------------------------------------
     */
    } else if ( $k03_tipo == 2 || $k03_tipo == 9 ) { ?>

      <tr>
        <td>Inscri&ccedil;&atilde;o:</td>
        <td>&nbsp; <?=$k00_inscr?></td>

        <td>Data In&iacute;cio:</td>
        <td>&nbsp; <?=$q02_dtinic?></td>
      </tr>

      <tr>
        <td>Nome/Empresa:</td>
        <td>&nbsp; <?=$z01_nome?></td>

        <td>C&oacute;digo Arrecada&ccedil;&atilde;o:</td>
        <td>&nbsp; <?=$k00_numpre?></td>
      </tr>

      <tr>
        <td>Valor Lan&ccedil;ado:</td>
        <td>&nbsp; <?=$q01_valor?></td>

        <td>Numbanco anterior:</td>
        <td>&nbsp; <?=$oArrebanco->k00_nbant?></td>
      </tr>

    <?php
    /**
     * ------------------------------------------------------------------------------------------------
     * ISSQN VARIÁVEL
     * ------------------------------------------------------------------------------------------------
     */
    } else if( $k03_tipo == 3 ) { ?>

			<?php if (isset($k00_inscr) && $k00_inscr > 0) { ?>

				<tr>
					<td>Inscri&ccedil;&atilde;o:</td>
					<td>&nbsp;<?php echo $k00_inscr; ?></td>

					<td>Data In&iacute;cio:</td>
					<td>&nbsp;<?php echo $q02_dtinic; ?></td>
				</tr>

				<tr>
					<td>Nome/Empresa:</td>
					<td>&nbsp;<?php echo $z01_nome; ?></td>
				</tr>

				<tr>

			<?php
			}
			?>

      <tr>
        <td>C&oacute;digo Arrecada&ccedil;&atilde;o:</td>
        <td>&nbsp;<?php echo $k00_numpre; ?></td>

        <td>Al&iacute;quota:</td>
        <td>&nbsp;<?php echo $q05_aliq; ?>%</td>
      </tr>

      <tr>
        <td>Compet&ecirc;ncia:</td>
        <td>&nbsp;<?php echo $q05_ano." - ".$q05_mes; ?></td>
      </tr>

      <?php if( $k00_tipo == 33 ) { ?>

				<tr>
					<td>Planilha:</td>
					<td>&nbsp;<?php echo $q20_planilha; ?>
					</td>

					<td>Contato:</td>
					<td>&nbsp;<?php echo $q20_nomecontri; ?>
					</td>
				</tr>

			<?php
			}
			?>

			<tr>
				<td>Observa&ccedil;&atilde;o:</td>
				<td>&nbsp;<?php echo $q05_histor; ?></td>
			</tr>

		<?php
    /**
     * ------------------------------------------------------------------------------------------------
     * CERTIDÃO DO FORO
     * ------------------------------------------------------------------------------------------------
     */
		} else if ( $k03_tipo == 15 ) { ?>

      <tr>
        <td>C&oacute;digo da Certid&atilde;o:</td>
        <td>&nbsp; <?=$v13_certid?></td>

        <td>Data Emiss&atilde;o:</td>
        <td>&nbsp; <?=$v13_dtemis?></td>
      </tr>

      <tr>
        <td>C&oacute;digo Arrecada&ccedil;&atilde;o:</td>
        <td>&nbsp; <?=$numpre?></td>

        <td>Observa&ccedil;&atilde;o:</td>
        <td>&nbsp;</td>
      </tr>

    <?php
    /**
     * ------------------------------------------------------------------------------------------------
     * PROTOCOLO GERAL OU K03_TIPO VAZIO
     * ------------------------------------------------------------------------------------------------
     */
    } else if ( $k03_tipo == 14 || is_null($k03_tipo) ) {  ?>

      <tr>
        <td>Nome :</td>
        <td>&nbsp; <?=$z01_nome?></td>

        <td>Data Operaç&atilde;o:</td>
        <td>&nbsp; <?=$k00_dtoper?></td>
      </tr>

      <tr>
        <td>Data vcto:</td>
        <td><?=$k00_dtvenc?></td>

        <td>C&oacute;digo Arrecada&ccedil;&atilde;o:</td>
        <td>&nbsp; <?=$k00_numpre?></td>
      </tr>

      <tr>
        <td>Valor:</td>
        <td>&nbsp; <?=$k00_valor?></td>
      </tr>

    <?php
	 	}
		?>

	</table>
</fieldset>

<?php

$oTabConsultaPagamentos = new verticalTab("consultapagamentos", 300);

$oTabConsultaPagamentos->add(
	"prorrogacoes",
	"Prorrogações de vencimentos efetuados",
	"cai3_consultapagamentosefetuadosprorrogacoes.php?iNumpre=$numpre&iNumpar=$numpar"
);


$iNumpreConsultaBaixa = $numpre;
$iNumparConsultaBaixa = $numpar;
if (!empty($iNumpreAbatimento)) {

  $iNumpreConsultaBaixa = $iNumpreAbatimento;
  $iNumparConsultaBaixa = 1;
}

$oTabConsultaPagamentos->add(
	"dadosBaixa",
	"Dados da baixa",
	"cai3_consultapagamentosefetuadosdadosbaixa.php?iNumpre=$iNumpreConsultaBaixa&iNumpar=$iNumparConsultaBaixa"
);

$oTabConsultaPagamentos->add(
	"historicos",
	"Históricos",
	"cai3_consultapagamentosefetuadoshistoricos.php?iNumpre=$numpre&iNumpar=$numpar"
);

$oTabConsultaPagamentos->add(
	"lancamentos",
	"Lançamentos efetuados",
	"cai3_consultapagamentosefetuadoslancamentos.php?iNumpre=$numpre&iNumpar=$numpar"
);

?>

<fieldset>

	<legend>
		<strong>Consulta de pagamentos efetuados: </strong>
	</legend>

	<?php $oTabConsultaPagamentos->show(); ?>

</fieldset>

</center>

<script type="text/javascript">
function js_AbreJanelaRelatorio() {
  window.open('div2_termoparc_002.php?parcel='  + $F('v07_parcel'), '','width=790,height=530,scrollbars=1,location=0');
}
</script>
</body>
</html>