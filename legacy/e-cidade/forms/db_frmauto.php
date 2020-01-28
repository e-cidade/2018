<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

require_once("classes/db_tipofiscaliza_classe.php");
require_once("classes/db_db_depart_classe.php");
$cltipofiscaliza = new cl_tipofiscaliza;
$cldb_depart     = new cl_db_depart;
$clauto->rotulo->label();
$clautolocal->rotulo->label();
$clautoexec->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("descrdepto");
$clrotulo->label("y30_codnoti");
$clrotulo->label("y77_descricao");
$clrotulo->label("y39_codandam");
$clrotulo->label("y80_codsani");
$clrotulo->label("z01_numcgm");
$clrotulo->label("j01_matric");
$clrotulo->label("q02_inscr");
$clrotulo->label("y10_codigo");
$clrotulo->label("y10_codi");
$clrotulo->label("y10_numero");
$clrotulo->label("y10_compl");
$clrotulo->label("y11_codigo");
$clrotulo->label("y11_codi");
$clrotulo->label("y11_numero");
$clrotulo->label("y11_compl");
$clrotulo->label("j14_nome");
$clrotulo->label("j13_descr");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("y29_tipofisc");
$clrotulo->label("y27_descr");
$clrotulo->label("y100_sequencial");
$clrotulo->label("z01_nome");
$get            = "";
$naotemnot      = 0;
$j14_nome_exec  = '';
$j13_descr_exec = '';
?>

<form name="form1" method="post" action="">
<?php
if(isset($y50_codauto) && $y50_codauto != "" && $db_opcao != 1){

  $result = $clauto->sql_record($clauto->sql_querycgm($y50_codauto));
  if($clauto->numrows > 0){
    db_fieldsmemory($result,0);
  }
}

if(isset($z01_numcgm) && $z01_numcgm != ""){

  db_input('z01_numcgm',5,$Iz01_numcgm,true,'hidden',1,"");
  include("classes/db_cgm_classe.php");
  $clcgm  = new cl_cgm;
	$get    = "&tipo=y101_numcgm&valor=$z01_numcgm";
  $result = $clcgm->sql_record($clcgm->sql_query_ender($z01_numcgm));
  if($clcgm->numrows > 0){

    db_fieldsmemory($result,0);
    $rua=$j14_codigo;
    $bairro=$j13_codi;
    $numero=$z01_numero;
    $compl=$z01_compl;
  }
  $dados = "<a onClick=\"js_abre('prot3_conscgm002.php?fechar=func_nome&numcgm=$z01_numcgm');return false\" href=''>CGM: ".$z01_numcgm." &nbsp;|&nbsp;".@$z01_nome."</a>";
}elseif(isset($j01_matric) && $j01_matric != ""){

  db_input('j01_matric',5,$Ij01_matric,true,'hidden',1,"");
  include("classes/db_iptubase_classe.php");
  $cliptubase = new cl_iptubase;
	$get        = "&tipo=y102_matric&valor=$j01_matric";
  $result     = $cliptubase->sql_record($cliptubase->proprietario_query($j01_matric));
  if($cliptubase->numrows > 0){

    db_fieldsmemory($result,0);
    $rua=$j14_codigo;
    $bairro=$j34_bairro;
    $numero=$j39_numero;
    $compl=$j39_compl;
  }
  $dados = "<a onClick=\"js_abre('cad3_conscadastro_002.php?cod_matricula=$j01_matric');return false\" href=''>matrícula: ".$j01_matric." &nbsp;|&nbsp;".@$z01_nome."</a>";
}elseif(isset($q02_inscr)  && $q02_inscr  != ""){

  db_input('q02_inscr',5,$Iq02_inscr,true,'hidden',1,"");
  include("classes/db_issbase_classe.php");
  $clissbase = new cl_issbase;
	$get       = "&tipo=y103_inscr&valor=$q02_inscr";
  $result    = $clissbase->sql_record($clissbase->empresa_query($q02_inscr));
  if($clissbase->numrows > 0){

    db_fieldsmemory($result,0);
    $rua=$q02_lograd;
    $bairro=$q02_bairro;
    $numero=$z01_numero;
    $compl=$z01_compl;

    include("classes/db_cgm_classe.php");
    $clcgm = new cl_cgm;
    $result = $clcgm->sql_record($clcgm->sql_query($q02_numcgm));
    if($clcgm->numrows > 0){
      db_fieldsmemory($result,0);
    }
  }
  $dados = "<a onClick=\"js_abre('iss3_consinscr003.php?numeroDaInscricao=$q02_inscr');return false\" href=''>inscrição: ".$q02_inscr." &nbsp;|&nbsp;".@$z01_nome."</a>";
}elseif(isset($y80_codsani)  && $y80_codsani  != ""){

  db_input('y80_codsani',5,$Iy80_codsani,true,'hidden',1,"");
  include("classes/db_sanitario_classe.php");
  $clsanitario = new cl_sanitario;
	$get         = "&tipo=y104_codsani&valor=$y80_codsani";
  $result      = $clsanitario->sql_record($clsanitario->sql_query($y80_codsani));
  if($clsanitario->numrows > 0){

    db_fieldsmemory($result,0);
    $rua=$y80_codrua;
    $bairro=$y80_codbairro;
    $numero=$y80_numero;
    $compl=$y80_compl;

    include("classes/db_cgm_classe.php");
    $clcgm = new cl_cgm;
    $result = $clcgm->sql_record($clcgm->sql_query($y80_numcgm));
    if($clcgm->numrows > 0){
      db_fieldsmemory($result,0);
    }
  }
  $dados = "<a onClick=\"js_abre('fis3_consultasani002.php?y80_codsani=$y80_codsani');return false;\" href=''>sanitário: ".$y80_codsani." &nbsp;|&nbsp;".@$z01_nome."</a>";
}elseif(isset($y30_codnoti)  && $y30_codnoti  != ""){

  db_input('y30_codnoti',5,$Iy30_codnoti,true,'hidden',1,"");
  include("classes/db_fiscal_classe.php");
  $clfiscal = new cl_fiscal;
  $sqlnot = "
  			select
  			y13_codnoti,

  			y13_codigo   as rua,
  			be.j13_codi  as bairro,
  			re.j14_nome  as ruanomee,
  			y13_numero   as numero,
  			be.j13_descr as bairrodescre,
  			y13_compl    as compl,

  			y12_codigo   as rual,
  			bl.j13_codi  as bairrol,
  			rl.j14_nome  as ruanomel,
  			y12_numero   as numerol,
  			bl.j13_descr as bairrodescrl,
  			y12_compl    as compll
  			from fiscexec
  			left join fiscalocal       on y13_codnoti=y12_codnoti
  			inner join ruas    as re   on re.j14_codigo =y13_codigo
  			inner join bairro  as be   on be.j13_codi   =y13_codi
  			inner join ruas    as rl   on rl.j14_codigo =y12_codigo
  			inner join bairro  as bl   on bl.j13_codi   =y12_codi
  			where y13_codnoti = $y30_codnoti";
    $resultnot = db_query ($sqlnot);
    $linhasnot = pg_num_rows($resultnot);
    if($linhasnot>0){
       db_fieldsmemory($resultnot,0);
       $notific=true;

    }else{
      db_msgbox("Não tem endereço registrado e localizado cadastrado para a notificação $y30_codnoti.");
      $naotemnot = 1;
    }

  include_once("classes/db_fiscalcgm_classe.php");
  $clfiscalcgm = new cl_fiscalcgm;
  $result = $clfiscalcgm->sql_record($clfiscalcgm->sql_query(null,"*",null," y36_codnoti = {$y30_codnoti} and y30_instit = ".db_getsession('DB_instit') ));
  if($clfiscalcgm->numrows > 0){
    db_fieldsmemory($result,0);
  }
  include_once("classes/db_fiscalinscr_classe.php");
  $clfiscalinscr = new cl_fiscalinscr;
  $result = $clfiscalinscr->sql_record($clfiscalinscr->sql_query(null,"*",null," y34_codnoti = {$y30_codnoti} and y30_instit = ".db_getsession('DB_instit')));
  if($clfiscalinscr->numrows > 0){
    db_fieldsmemory($result,0);
  }
  include_once("classes/db_fiscalmatric_classe.php");
  $clfiscalmatric = new cl_fiscalmatric;
  $result = $clfiscalmatric->sql_record($clfiscalmatric->sql_query(null,"*",null," y35_codnoti = {$y30_codnoti} and y30_instit = ".db_getsession('DB_instit')));
  if($clfiscalmatric->numrows > 0){
    db_fieldsmemory($result,0);
  }
  include_once("classes/db_fiscalsanitario_classe.php");
  $clfiscalsanitario = new cl_fiscalsanitario;
  $result = $clfiscalsanitario->sql_record($clfiscalsanitario->sql_query(null,"*",null," y37_codnoti = {$y30_codnoti} and y30_instit = ".db_getsession('DB_instit')));
  if($clfiscalsanitario->numrows > 0){
    db_fieldsmemory($result,0);
  }
  include("classes/db_cgm_classe.php");
  $clcgm = new cl_cgm;
  $result = $clcgm->sql_record($clcgm->sql_query(@$z01_numcgm));
  if($clcgm->numrows > 0){
    db_fieldsmemory($result,0);
  }
  $dados = "<a onClick=\"js_abre('fis3_fiscal006.php?y30_codnoti=$y30_codnoti');return false;\" href=''>notificação: ".$y30_codnoti." &nbsp;|&nbsp;".@$z01_nome."</a>";
}

//-------------------------------/Busca Cgm e verifica se é por cgm,matricula,inscr,sanitario ou notificação/-----------------
if ($db_opcao==2||$db_opcao==3){

  $sSql = $clauto->sql_query_busca($y50_codauto);
  $result_ident = $clauto->sql_record($sSql);

  if($clauto->numrows>0){

    db_fieldsmemory($result_ident,0);
    $cod    = $dl_codigo;
    $inform = $dl_identificacao;

    if ($dl_identificacao=='Cgm'){
      $abre = "prot3_conscgm002.php?fechar=func_nome&numcgm";
    }else if ($dl_identificacao=='Inscrição'){
      $abre = "iss3_consinscr003.php?numeroDaInscricao";
    }else if ($dl_identificacao=='Matrícula'){
      $abre = "cad3_conscadastro_002.php?cod_matricula";
    }else if ($dl_identificacao=='Sanitário'){
      $abre = "fis3_consultasani002.php?y80_codsani";
    }else if ($dl_identificacao=='Notificação'){
       $abre = "fis3_fiscal006.php?y30_codnoti";
    }
  }
}
if (isset($cod)&&$cod!=""){
 $dados = "<a onClick=\"js_abre('".$abre."=$cod');return false;\" href=''>".$inform.": ".$cod." &nbsp;|&nbsp;".@$z01_nome."</a>";
}

?>
<fieldset>
  <legend>Auto de Infração</legend>

<table>
  <tr>
    <td nowrap title="<?=@$Ty50_codauto?>"colspan="2">
      <strong>Código:</strong>
      <?
      db_input('y50_codauto',10,$Iy50_codauto,true,'text',3,"");
      ?>
      <?=@$Ly50_numbloco?>
      <?
      db_input('y50_numbloco',10,$Iy50_numbloco,true,'text',$db_opcao,"");
      ?>
    <strong>Data:</strong>
      <?
      if(empty($y50_data_dia)){

        $y50_data_dia = date("d",db_getsession("DB_datausu"));
        $y50_data_mes = date("m",db_getsession("DB_datausu"));
        $y50_data_ano = date("Y",db_getsession("DB_datausu"));
      }
      db_inputdata('y50_data',@$y50_data_dia,@$y50_data_mes,@$y50_data_ano,true,'text',$db_opcao,"")
      ?>
             <strong>Hora:</strong>
      <?
      db_input('y50_hora',5,$Iy50_hora,true,'text',$db_opcao,"");
      if($db_opcao == 1){
        echo "<script>document.form1.y50_hora.value='".db_hora()."'</script>";
      }
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap colspan="2" >
    <?
      echo "<strong>".@$dados."&nbsp;</strong>";
      db_ancora(@$Ly29_tipofisc,"js_pesquisa_tipofisc(true);",$db_opcao);
      $result_tipofisc=$cltipofiscaliza->sql_record($cltipofiscaliza->sql_query_file(null,"*",null," y27_instit = ".db_getsession('DB_instit') ));
      db_selectrecord("y50_codtipo",$result_tipofisc,true,$db_opcao);
    ?>
 </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty50_obs?>">
       <?=@$Ly50_obs?>
    </td>
    <td>
      <?
      db_textarea('y50_obs',1,50,$Iy50_obs,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty50_setor?>">
       <?
       db_ancora(@$Ly50_setor,"js_pesquisay50_setor(true);",3);
       ?>
    </td>
    <td>
      <?
      if ($db_opcao==1){
        $y50_setor=db_getsession("DB_coddepto");
        $result_depto=$cldb_depart->sql_record($cldb_depart->sql_query_file($y50_setor));
        if ($cldb_depart->numrows>0){
          db_fieldsmemory($result_depto,0);
        }
      }
      db_input('y50_setor',10,$Iy50_setor,true,'text',3," onchange='js_pesquisay50_setor(false);'");

      db_input('descrdepto',40,$Idescrdepto,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty50_nome?>">
       <?=@$Ly50_nome?>
    </td>
    <td>
      <?
      db_input('y50_nome',50,$Iy50_nome,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty50_dtvenc?>">
       <?=@$Ly50_dtvenc?>
      </td>
    <td>
      <?
      if(empty($y50_dtvenc_dia)){

        $dia = date("d",db_getsession("DB_datausu"));
        $mes = date("m",db_getsession("DB_datausu"));
        $ano = date("Y",db_getsession("DB_datausu"));
        $y50_dtvenc_dia = substr(verifica_data($dia,$mes,$ano),8,2);
        $y50_dtvenc_mes = substr(verifica_data($dia,$mes,$ano),5,2);
        $y50_dtvenc_ano = substr(verifica_data($dia,$mes,$ano),0,4);
      }
      db_inputdata('y50_dtvenc',@$y50_dtvenc_dia,@$y50_dtvenc_mes,@$y50_dtvenc_ano,true,'text',$db_opcao,"");
      ?>
      <strong>Prazo p/ Recurso:</strong>
      <?
      db_inputdata('y50_prazorec',@$y50_prazorec_dia,@$y50_prazorec_mes,@$y50_prazorec_ano,true,'text',$db_opcao,"");
      ?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Ty100_sequencial?>">
       <?
       db_ancora(@$Ly100_sequencial,"js_pesquisaprocfiscal(true);",$db_opcao);
       ?>
    </td>
    <td>
      <?php
        db_input('procfiscal',10,$Iy100_sequencial,true,'text',$db_opcao," onchange='js_pesquisaprocfiscal(false);'");
        db_input('nome',40,$Iz01_nome,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <fieldset>
      <legend>Endereço registrado</legend>
      <table>
  <tr>
    <td nowrap width="100" title="<?=@$Ty14_codigo?>">
       <?php

        $op = 3;
      	if (isset($notific)&&$notific==true&&$db_opcao==1){

      	  $y14_codigo=$rual;
      	  $y14_codi=$bairrol;
      	  $y14_numero=$numerol;
      	  $y14_compl=$compll;
      	  $y15_codigo=$rua;
      	  $y15_codi=$bairro;
      	  $y15_numero=$numero;
      	  $y15_compl=$compl;
      	  $j14_nome=$ruanomel;
      	  $j13_descr=$bairrodescrl;
      	  $j14_nome_exec=$ruanomee;
      	  $j13_descr_exec=$bairrodescre;
      	}else if (($db_opcao==1) &&( $naotemnot != 1)){

      	  $y14_codigo=$rua;
      	  $y14_codi=$bairro;
      	  $y14_numero=$numero;
      	  $y14_compl=$compl;
      	  $y15_codigo=$rua;
      	  $y15_codi=$bairro;
      	  $y15_numero=$numero;
      	  $y15_compl=$compl;
      	  if ((isset($j14_nome)&&$j14_nome!="")&&(isset($j13_descr)&&$j13_descr!="")){

      	    $j14_nome=@$j14_nome;
      	    $j13_descr=@$j13_descr;
      	    $j14_nome_exec=@$j14_nome;
      	    $j13_descr_exec=@$j13_descr;
      	  }else{

      	    $j14_nome_exec=@$z01_ender;
      	    $j13_descr_exec=@$z01_bairro;
      	    $j14_nome=@$z01_ender;
      	    $j13_descr=@$z01_bairro;
      	  }
      	}

        if ((isset($j14_nome)&&$j14_nome!="")&&(isset($j13_descr)&&$j13_descr!="")){

      	  $j14_nome=@$j14_nome;
      	  $j13_descr=@$j13_descr;

      	  if ((isset($j14_nome_exec)&&$j14_nome_exec!="")&&(isset($j13_descr_exec)&&$j13_descr_exec!="")){

      	    $j14_nome_exec=@$j14_nome_exec;
      	    $j13_descr_exec=@$j13_descr_exec;
      	  }
      	}
      	if($db_opcao==1||$db_opcao==2){

      	  if (@$j14_nome==""||$j13_descr==""||$y14_codigo==""||$y14_codi==""){
      	    $op=1;
      	  }
      	}

       db_ancora(@$Ly14_codigo,"js_ruas1(true);",$op);
       ?>
    </td>
    <td>
      <?
      $j14_nome = (@$y14_codigo=="" or @$y14_codigo==null)?"":@$j14_nome;
      db_input('y14_codigo',10,$Iy14_codigo,true,'text',$op," onChange='js_ruas1(false)'");
      db_input('j14_nome',50,$Ij14_nome,true,'text',3,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty14_numero?>">
      <?=@$Ly14_numero?>
    </td>
    <td>
      <?
      if(!isset($y14_numero)){
       $y14_numero = 0;
      }
      db_input('y14_numero',10,$Iy14_numero,true,'text',$op,"")
      ?>
      <?=@$Ly14_compl?>
      <?
      db_input('y14_compl',20,$Iy14_compl,true,'text',$op,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty14_codi?>">
      <?
      db_ancora(@$Ly14_codi,"js_bairro1(true);",$op);
      ?>
    </td>
    <td nowrap>
      <?php
        $j13_descr = (@$y14_codi=="" or @$y14_codi==null)?"":@$j13_descr;
        db_input('y14_codi',10,$Iy14_codi,true,'text',$op," onChange='js_bairro1(false)'");
        db_input('j13_descr',50,$Ij13_descr,true,'text',3);
      ?>
    </td>
  </tr>
  </table>
  </fieldset>
  </td>
  </tr>
  <tr>
    <td colspan="2" align="left">
      <fieldset>
           <legend>Endereço localizado</legend>
      <table>
  <tr>
    <td nowrap width="100" title="<?=@$Ty15_codigo?>">
       <?
       db_ancora(@$Ly15_codigo,"js_ruas(true);",$db_opcao);
       ?>
    </td>
    <td nowrap>
      <?
        $j14_nome_exec = (@$y15_codigo=="" or @$y15_codigo==null)?"":@$j14_nome_exec;
        db_input('y15_codigo',10,$Iy15_codigo,true,'text',$db_opcao," onChange='js_ruas(false)'");
        db_input('j14_nome',50,$Ij14_nome,true,'text',3,"","j14_nome_exec");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty15_numero?>">
       <?=@$Ly15_numero?>
    </td>
    <td>
      <?
      if(!isset($y15_numero)){
       $y15_numero = 0;
      }
      db_input('y15_numero',10,$Iy15_numero,true,'text',$db_opcao,"")
      ?>
             <?=@$Ly15_compl?>
      <?
      db_input('y15_compl',20,$Iy15_compl,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty15_codi?>">
      <?
      db_ancora(@$Ly15_codi,"js_bairro(true);",$db_opcao);
      ?>
    </td>
    <td nowrap>
      <?
        $j13_descr_exec = (@$y15_codi=="" or @$y15_codi==null)?"":@$j13_descr_exec;
        db_input('y15_codi',10,$Iy15_codi,true,'text',$db_opcao," onChange='js_bairro(false)'");
        db_input('j13_descr',50,$Ij13_descr,true,'text',3,"","j13_descr_exec");
      ?>
    </td>
  </tr>
  </table>
  </fieldset>
  </td>
  </tr>
  </table>
  </fieldset>
  <input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_validaFormulario();"/>
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" />
<?
if ($db_opcao==22||$db_opcao==2){
?>
<input name="novo" type="button" id="novo" value="Incluir Novo" onclick="parent.location.href='fis1_auto005.php';" >
<?
}
if($db_opcao==1||$db_opcao==2){

  if (@$j14_nome==""||$j13_descr==""||$y14_codigo==""||$y14_codi==""){

    $op=1;
    if (isset($z01_numcgm)&&$z01_numcgm!=""){

      $sql_cgm = "select z01_munic from cgm where z01_numcgm = $z01_numcgm";
      $result_cgm = db_query($sql_cgm);
      if (pg_numrows($result_cgm)>0){

        db_fieldsmemory($result_cgm,0);
        $sql_munic = "select munic from db_config";
        $result_munic = db_query($sql_munic);
        db_fieldsmemory($result_munic,0);
      }
    }
  }
}

?>
</form>
<script type="text/javascript">

var sCaminhoMensagens  = "tributario.fiscal.db_frmauto.";

function js_validaFormulario(){


 if( !isNumeric( $F('y50_numbloco') ) || empty( $F('y50_numbloco') ) ){

   alert( _M( sCaminhoMensagens + 'numero_bloco_obrigatorio' ) );
   return false;
 }

 if( empty( $F('y50_nome') ) ){

   alert( _M( sCaminhoMensagens + 'nome_autuado_obrigatorio' ) );
   return false;
 }

 if (empty( $F('y50_dtvenc') )) {

   alert( _M( sCaminhoMensagens + 'data_vencimento_obrigatorio' ) );
   return false;
 }

 if( !isNumeric( $F('y14_codigo') ) ){

   alert( _M( sCaminhoMensagens + 'registrado_logradouro_obrigatorio' ) );
   return false;
 }

 if( !isNumeric( $F('y14_numero') ) ){

   alert( _M( sCaminhoMensagens + 'registrado_numero_obrigatorio' ) );
   return false;
 }

 if( !isNumeric( $F('y14_codi') ) ){

   alert( _M( sCaminhoMensagens + 'registrado_bairro_obrigatorio' ) );
   return false;
 }

 if( !isNumeric( $F('y15_codigo') ) ){

   alert( _M( sCaminhoMensagens + 'localizado_logradouro_obrigatorio' ) );
   return false;
 }

 if( !isNumeric( $F('y15_numero') ) ){

   alert( _M( sCaminhoMensagens + 'localizado_numero_obrigatorio' ) );
   return false;
 }

 if( !isNumeric( $F('y15_codi') ) ){

   alert( _M( sCaminhoMensagens + 'localizado_bairro_obrigatorio' ) );
   return false;
 }

 return true;
}

function js_setatabulacao(){
  js_tabulacaoforms("form1","y50_numbloco",true,1,"y50_numbloco",true);
}
function js_bairro(mostra){
  if(mostra == true){
    js_OpenJanelaIframe('','db_iframe_bairro','func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro|j13_codi|j13_descr','pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_bairro','func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro1&pesquisa_chave='+document.form1.y15_codi.value,'pesquisa',false);
  }
}
function js_preenchebairro(chave,chave1){
  document.form1.y15_codi.value = chave;
  document.form1.j13_descr_exec.value = chave1;
  db_iframe_bairro.hide();
}
function js_preenchebairro1(chave,erro){
  document.form1.j13_descr_exec.value = chave;
  if(erro == true){
    document.form1.y15_codi.focus();
    document.form1.y15_codi.value='';
  }
  db_iframe_bairro.hide();
}
function js_bairro1(mostra){
  if(mostra == true){
    js_OpenJanelaIframe('','db_iframe_bairro','func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro2|j13_codi|j13_descr','pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_bairro','func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro22&pesquisa_chave='+document.form1.y14_codi.value,'pesquisa',false);
  }
}
function js_preenchebairro2(chave,chave1){
  document.form1.y14_codi.value = chave;
  document.form1.j13_descr.value = chave1;
  db_iframe_bairro.hide();
}
function js_preenchebairro22(chave,erro){
  document.form1.j13_descr.value = chave;
  if(erro == true){
    document.form1.y14_codi.focus();
    document.form1.y14_codi.value='';
  }
  db_iframe_bairro.hide();
}
function js_ruas(mostra){
  if(mostra == true){
    js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?rural=1&funcao_js=parent.js_preencheruas|j14_codigo|j14_nome','Pesquisa',true);
  }else{
    document.form1.j14_nome_exec.value = '';
    document.form1.y15_numero.value = '';
    document.form1.y15_compl.value = '';
    document.form1.y15_codi.value = '';
    document.form1.j13_descr_exec.value = '';
    document.form1.y15_numero.focus();
    js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?rural=1&funcao_js=parent.js_preencheruas1&pesquisa_chave='+document.form1.y15_codigo.value+'','Pesquisa',false);
  }
}
function js_preencheruas(chave,chave1){
  document.form1.y15_codigo.value = chave;
  document.form1.j14_nome_exec.value = chave1;
  db_iframe_ruas.hide();
}
function js_preencheruas1(chave,erro){
  document.form1.j14_nome_exec.value = chave;
  if(erro == true){
    document.form1.y15_codigo.focus();
    document.form1.y15_codigo.value='';
  }
  db_iframe_ruas.hide();
}
function js_ruas1(mostra){
  if(mostra == true){
    js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?rural=1&funcao_js=parent.js_preencheender|j14_codigo|j14_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?rural=1&funcao_js=parent.js_preencheender1&pesquisa_chave='+document.form1.y14_codigo.value+'','Pesquisa',false);
  }
}
function js_preencheender(chave,chave1){
  document.form1.y14_codigo.value = chave;
  document.form1.j14_nome.value = chave1;
  db_iframe_ruas.hide();
}
function js_preencheender1(chave,erro){
  document.form1.j14_nome.value = chave;
  if(erro==true){
    document.form1.y14_codigo.focus();
    document.form1.y14_codigo.value = '';
  }
}
function js_pesquisay50_setor(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_mostradb_depart1|coddepto|descrdepto','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_db_depart','func_db_depart.php?pesquisa_chave='+document.form1.y50_setor.value+'&funcao_js=parent.js_mostradb_depart','Pesquisa',false);
  }
}
function js_mostradb_depart(chave,erro){
  document.form1.descrdepto.value = chave;
  if(erro==true){
    document.form1.y50_setor.focus();
    document.form1.y50_setor.value = '';
  }
}
function js_mostradb_depart1(chave1,chave2){
  document.form1.y50_setor.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_db_depart.hide();
}
function js_pesquisa_tipofisc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tipo','func_tipofiscaliza.php?funcao_js=parent.js_mostratipo1|y27_codtipo|y27_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_tipo','func_tipofiscaliza.php?pesquisa_chave='+document.form1.y50_codtipo.value+'&funcao_js=parent.js_mostratipo','Pesquisa',false);
  }
}
function js_mostratipo(chave,erro){
  document.form1.y27_descr.value = chave;
  if(erro==true){
    document.form1.y50_codtipo.focus();
    document.form1.y50_codtipo.value = '';
  }
}
function js_mostratipo1(chave1,chave2){
  document.form1.y50_codtipo.value = chave1;
  document.form1.y27_descr.value = chave2;
  db_iframe_tipo.hide();
}


function js_pesquisaprocfiscal(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_procfiscal','func_procfiscal_alt.php?funcao_js=parent.js_mostraprocfiscal1|y100_sequencial|z01_nome|db_depart_protocolo|db_descr_depart|db_depart_atual<?=$get?>','Pesquisa',true);
  }else{

     if(document.form1.procfiscal.value != ''){
        js_OpenJanelaIframe('','db_iframe_procfiscal','func_procfiscal_alt.php?pesquisa_chave='+document.form1.procfiscal.value+'&funcao_js=parent.js_mostraprocfiscal','Pesquisa',false,'0','1','775','390');
     }else{
		 	 document.form1.nome.value = '';
		 }
  }
}
function js_mostraprocfiscal(chave,erro,dep_prot,depart,dep_atual){

 if (dep_prot == dep_atual) {
  	document.form1.nome.value = chave;
    if(erro==true){
      document.form1.procfiscal.focus();
      document.form1.procfiscal.value = '';
    }
  } else {

    alert('Processo de protocolo não está neste departamento atualmente! \nDepartamento atual do processo:'+depart);
		document.form1.procfiscal.focus();
    document.form1.procfiscal.value = '';
		document.form1.nome.value = '';
		return false;
  }
}
function js_mostraprocfiscal1(chave1,chave2,dep_prot,depart,dep_atual){

  if (dep_prot == dep_atual) {

  	document.form1.procfiscal.value = chave1;
  	document.form1.nome.value = chave2;
  	db_iframe_procfiscal.hide();
  }else {

    alert('Processo de protocolo não está neste departamento atualmente! \nDepartamento atual do processo:'+depart);
		return false;
  }
}

function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_auto','func_autoalt.php?db_opcao=<?=$db_opcao?>&funcao_js=parent.js_preenchepesquisa|dl_auto','Pesquisa',true);
}

function js_pesquisare(num,origem){
   js_OpenJanelaIframe('','db_iframe_auto','func_auto001.php?db_opcao=<?=$db_opcao?>&funcao_js=parent.js_preenchepesquisa|dl_auto&origem='+origem+'&num='+num,'Pesquisa',true);
}

function js_preenchepesquisa(chave){
  db_iframe_auto.hide();
  <?
    if($db_opcao == 2 || $db_opcao == 22){
      echo " location.href = 'fis1_auto002.php?abas=1&chavepesquisa='+chave;";
    }elseif($db_opcao == 33 || $db_opcao == 3){
      echo " location.href = 'fis1_auto003.php?abas=1&chavepesquisa='+chave;";
    }
  ?>
}
function js_abre(pagina){
  js_OpenJanelaIframe('','db_iframe_consulta',pagina,'Pesquisa',true,0);
}
</script>