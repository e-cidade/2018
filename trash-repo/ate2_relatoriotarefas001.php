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
 

require ('fpdf151/pdf.php');
include ("dbforms/db_funcoes.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_db_proced_classe.php");
include("classes/db_tarefacadsituacao_classe.php");
include("classes/db_tarefacadmotivo_classe.php");
include("classes/db_clientes_classe.php");
include("classes/db_tarefa_classe.php");
include("classes/db_tarefalog_classe.php");

$cltarefa      = new cl_tarefa;
$clclientes = new cl_clientes;
$cldb_usuarios = new cl_db_usuarios;
$cldb_proced = new cl_db_proced;
$cltarefacadsituacao = new cl_tarefacadsituacao;
$cltarefacadmotivo = new cl_tarefacadmotivo;
$cltarefalog         = new cl_tarefalog;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//echo $HTTP_SERVER_VARS['QUERY_STRING'];
$varwhere = " 1=1 ";

if (isset($at40_autoriza) and $at40_autoriza == "S") {
  $varwhere .= " and at40_autorizada is true ";
  $filtro1 = "AUTORIZADAS: SIM\n";
} elseif (isset($at40_autoriza) and $at40_autoriza == "N") {
  $varwhere .= " and at40_autorizada is false";
  $filtro1 = "AUTORIZADAS: NAO\n";
} else {
  $filtro1 = "AUTORIZADAS: TODAS\n\n";
}

if (isset($at40_responsavel) and $at40_responsavel != "0") {
  $varwhere .= " and at40_ativo is true and tarefaenvol.at45_usuario = $at40_responsavel";
  $result_usuarios = $cldb_usuarios->sql_record($cldb_usuarios->sql_query($at40_responsavel,"nome","",""));
  if ($cldb_usuarios->numrows > 0) {
    db_fieldsmemory($result_usuarios,0);
  } else {
    $nome = "";
  }
  $filtro2 = "RESPONSAVEL: $nome\n";
} elseif (isset($at40_responsavel) and $at40_responsavel == "0") {
  $varwhere .= " ";
  $filtro2 = "SEM RESPONSAVEL ESPECIFICADO\n";
}

if (isset($at40_motivo) and $at40_motivo != "0") {
  $varwhere .= " and tarefamotivo.at55_motivo = $at40_motivo";
  $result_motivo = $cltarefacadmotivo->sql_record($cltarefacadmotivo->sql_query($at40_motivo,"at54_descr","",""));
  if ($cltarefacadmotivo->numrows > 0) {
    db_fieldsmemory($result_motivo,0);
  } else {
    $at54_descr = "";
  }
  $filtro2 = "MOTIVO: $at54_descr\n";
} elseif (isset($at40_responsavel) and $at40_responsavel == "0") {
  $varwhere .= " ";
  $filtro2 = "SEM MOTIVO ESPECIFICADO\n";
}


if (isset($at40_cliente) and $at40_cliente != "Todos") {
  $varwhere .= " and tarefaclientes.at70_cliente = $at40_cliente ";
  
  $result_clientes = $clclientes->sql_record($clclientes->sql_query($at40_cliente,"at01_nomecli","",""));
  if ($clclientes->numrows > 0) {
    db_fieldsmemory($result_clientes,0);
  } else {
    $at01_nomecli= "";
  }
  $filtro3 = "CLIENTE: $at01_nomecli\n";
  
} else {
  $filtro3 = "SEM CLIENTE ESPECIFICADO\n";
}

$filtro4 = "";
if (1==2) {
  if(isset($at40_progresso)&&$at40_progresso!="") {
    if($at40_progresso=="A") {
      $varwhere .= " and tarefa.at40_progresso < 100";
      $filtro4 = "COM PROGRESSO MENOR QUE 100";
    }	
    if($at40_progresso=="F") {
      $varwhere .= " and tarefa.at40_progresso = 100";
      $filtro4 .= ($filtro4 != ""?" E ":"") . "COM PROGRESSO IGUAL A 100";
    }	
  } else {
    $varwhere .= " and tarefa.at40_progresso < 100";
    $filtro4 = " COM PROGRESSO MENOR QUE 100";
  }
}

$varwhere_envol = " where 1=1 ";

$filtro5 = "";
if (isset($at40_progressoini) and (isset($at40_progressofim))) {
  if (isset($at40_responsavel) and $at40_responsavel != "0") {
    $varwhere_envol .= " and dl_Envolvimento between $at40_progressoini and $at40_progressofim";
    $filtro5 = "PERCENTUAL DE ENVOLVIMENTO INICIAL: $at40_progressoini - FINAL: $at40_progressofim";
  }
} 

if (isset($at40_proced) and $at40_proced != "0") {
  $varwhere .= " and tarefaproced.at41_proced = $at40_proced ";
  
  $result_proced = $cldb_proced->sql_record($cldb_proced->sql_query($at40_proced,"at30_descr","",""));
  if ($cldb_proced->numrows > 0) {
    db_fieldsmemory($result_proced,0);
  } else {
    $at30_descr = "";
  }
  $filtro6 = "PROCEDIMENTO: $at30_descr\n";
  
} else {
  $filtro6 = "SEM PROCEDIMENTO ESPECIFICADO\n";
}

if (isset($at40_situacao) and $at40_situacao != "0") {
  $varwhere .= " and tarefasituacao.at47_situacao in ($at40_situacao) ";
  
  $result_situacao = $cltarefacadsituacao->sql_record($cltarefacadsituacao->sql_query(null,"at46_descr","","at46_codigo in ($at40_situacao)"));
  $descr = "";
  if ($cltarefacadsituacao->numrows > 0) {
    $virgula = "";
    for($x=0; $x<$cltarefacadsituacao->numrows; $x++) {
      db_fieldsmemory($result_situacao, $x);
      $descr .= $virgula." ".trim($at46_descr);
      $virgula = ",";
    }
  }  
  if ($cltarefacadsituacao->numrows > 1) {
    $filtro7 = "SITUACOES: $descr\n";
  } else {  
    $filtro7 = "SITUACAO: $descr\n";
  }
  
} else {
  $filtro7 = "SEM SITUACAO ESPECIFICADA\n";
}

// filtro datas
if (isset($at40_diaini) and $at40_diaini == "--") {
  $at40_diaini = "";
}

if (isset($at40_diafim) and $at40_diafim == "--") {
  $at40_diafim = "";
}
$varwhere1  = "1=1";
if (isset($tipodatafinal)&&$tipodatafinal=="P"){
  if ($at40_diaini != "" and $at40_diafim != "") {
    $varwhere1 .= " and at40_diafim between '$at40_diaini' and '$at40_diafim' ";
  } elseif ($at40_diaini != "" and $at40_diafim == "") {
    $varwhere1 .= " and at40_diafim >= '$at40_diaini' ";
  } elseif ($at40_diaini != "" and $at40_diafim == "") {
    $varwhere1 .= " and at40_diafim <= '$at40_diafim' ";
  }
}else if ($tipodatafinal == "C") {
  if ($at40_diaini != "" and $at40_diafim != "") {
    $varwhere1 .= " and ( at40_sequencial = tarefa_lanc.at36_tarefa and tarefa_lanc.at36_tipo = 'I' and tarefa_lanc.at36_data between '$at40_diaini' and '$at40_diafim') ";
  } elseif ($at40_diaini != "" and $at40_diafim == "") {
    $varwhere1 .= " and ( at40_sequencial = tarefa_lanc.at36_tarefa and tarefa_lanc.at36_tipo = 'I' and  tarefa_lanc.at36_data >= '$at40_diaini' ) ";
  } elseif ($at40_diaini == "" and $at40_diafim != "") {
    $varwhere1 .= " and ( at40_sequencial = tarefa_lanc.at36_tarefa and tarefa_lanc.at36_tipo = 'I' and  tarefa_lanc.at36_data <= '$at40_diafim' ) ";
  }
} else 
if (isset($tipodatafinal)&&$tipodatafinal=="E"){
  if ($at40_diaini != "" and $at40_diafim != "") {
    $varwhere1 .= " and db_dia100 between '$at40_diaini' and '$at40_diafim' ";
  } elseif ($at40_diaini != "" and $at40_diafim == "") {
    $varwhere1 .= " and db_dia100 >= '$at40_diaini' ";
  } elseif ($at40_diaini != "" and $at40_diafim == "") {
    $varwhere1 .= " and db_dia100 <= '$at40_diafim' ";
  }
}else{
  if ($at40_diaini != "" and $at40_diafim != "") {
    $varwhere .= " and at40_diafim between '$at40_diaini' and '$at40_diafim' ";
    $filtro8 = "PERIODO DE DATA FINAL: $at40_diaini a $at40_diafim";
  } elseif ($at40_diaini != "" and $at40_diafim == "") {
    $varwhere .= " and at40_diafim >= '$at40_diaini' ";
    $filtro8 = "PERIODO DE DATA FINAL MAIOR OU IGUAL QUE $at40_diaini";
  } elseif ($at40_diaini != "" and $at40_diafim == "") {
    $varwhere .= " and at40_diafim <= '$at40_diafim' ";
    $filtro8 = "PERIODO DE DATA FINAL MENOR OU IGUAL QUE $at40_diafim";
  } else {
    $filtro8 = "SEM FILTRO DE PERIODO FINAL ESPECIFICADO";
  }
}
// fim filtro datas

if(!isset($pesquisa_chave)){
  
  if (isset($at40_responsavel) and $at40_responsavel != "0" or 1==1) {
    $campos = "tarefa.at40_sequencial, (select max(at43_diaini) as at43_diaini from tarefalog where at43_tarefa = tarefa.at40_sequencial and at43_progresso = 100) as db_dia100, tarefa.at40_autorizada as dl_aut, tarefa.at40_progresso::integer,case tarefa.at40_prioridade when 1 then 'Baixa' when 2 then 'Média' when 3 then 'Alta' end as at40_prioridade,tarefa.at40_diaini,tarefa.at40_previsao||'/'||tarefa.at40_tipoprevisao as dl_Duração,tarefa.at40_diafim,(tarefa.at40_diafim::date - '".date("Y-m-d",db_getsession("DB_datausu"))."'::date) as dl_Pendente,tarefaenvol.at45_perc as dl_Envolvimento,clientes.at01_nomecli as nome_cliente,tarefa_lanc.at36_usuario as db_usulanc,tarefa_lanc.at36_tarefa as db_tarefa,db_usuarios.nome as dl_Envolvido,db_usuarios_lanc.nome as dl_Criador,tarefa.at40_descr || '-'||at40_obs||'/'||db_proced.at30_descr as dl_Tarefa,db_proced.at30_descr as dl_proced";
  } elseif (isset($at40_responsavel) and $at40_responsavel == "0") {
    $campos = "tarefa.at40_sequencial, (select max(at43_diaini) as at43_diaini from tarefalog where at43_tarefa = tarefa.at40_sequencial and at43_progresso = 100) as db_dia100, tarefa.at40_autorizada as dl_aut, tarefa.at40_progresso::integer,case tarefa.at40_prioridade when 1 then 'Baixa' when 2 then 'Média' when 3 then 'Alta' end as at40_prioridade,tarefa.at40_diaini,tarefa.at40_previsao||'/'||tarefa.at40_tipoprevisao as dl_Duração,tarefa.at40_diafim,(tarefa.at40_diafim::date - '".date("Y-m-d",db_getsession("DB_datausu"))."'::date) as dl_Pendente,clientes.at01_nomecli as nome_cliente,tarefa_lanc.at36_usuario as db_usulanc,tarefa_lanc.at36_tarefa as db_tarefa,db_usuarios_lanc.nome as dl_Criador, tarefa.at40_descr||'-'||at40_obs||'/'||db_proced.at30_descr as dl_Tarefa,db_proced.at30_descr as dl_proced ";
  }
  
  if(isset($chave_at40_sequencial) && (trim($chave_at40_sequencial)!="" or 1==2) ){
    //$sql = $cltarefa->sql_query($chave_at40_sequencial,$campos,"dl_pendente, at40_sequencial","at40_autorizada is true");
    $sql = $cltarefa->sql_query($chave_at40_sequencial,$campos,"dl_pendente, at40_sequencial"," at40_ativo is true");
  }else if(isset($chave_at40_descr) && (trim($chave_at40_descr)!="") or 1==2){
    $sql = $cltarefa->sql_query("",$campos,"dl_pendente, at40_descr"," at40_descr like '$chave_at40_descr%' and at40_ativo is true");
  } else {
    
    if(isset($at40_sequencial) && ($at40_sequencial != "") ){
      $varwhere = " at40_sequencial = $at40_sequencial ";
      $sql = $cltarefa->sql_query_cons_envol("",$campos,"dl_pendente, tarefa.at40_prioridade desc",$varwhere);
    } elseif (isset($at40_responsavel) and $at40_responsavel != "0") {
      $sql = $cltarefa->sql_query_cons_envol("",$campos,"dl_pendente, tarefa.at40_prioridade desc",$varwhere);
    } elseif (isset($at40_responsavel) and $at40_responsavel == "0") {
      //$sql = $cltarefa->sql_query_cons_tarefa("",$campos,"dl_pendente, tarefa.at40_prioridade desc",$varwhere);
      $sql = $cltarefa->sql_query_cons_envol("",$campos,"dl_pendente, tarefa.at40_prioridade desc",$varwhere);
    }
    
    $sql = "select at40_sequencial, db_dia100, dl_aut, at40_progresso,at40_prioridade,at40_diaini,dl_Duração,at40_diafim,dl_Pendente,min(dl_Envolvimento) as dl_Envolvimento,min(nome_cliente) as nome_cliente,db_usulanc,db_tarefa,min(dl_Envolvido) as dl_Envolvido,dl_Criador,dl_Tarefa,dl_proced from (select * from (select distinct * from ($sql) as x) as y $varwhere_envol order by " . ($ordem == 1?"at40_diafim,":"") . " at40_prioridade, dl_pendente * -1 desc) as x where $varwhere1 group by at40_diafim, at40_sequencial, db_dia100, dl_aut, at40_progresso,at40_prioridade,at40_diaini,dl_Duração,dl_Pendente,db_usulanc,db_tarefa,dl_Criador,dl_Tarefa,dl_proced";
    
    if (isset($tipo_rel)&&$tipo_rel=="c"){
      $sql .= " order by at40_diafim";
    }
  }
  
}
//die($sql);
$result = pg_exec($sql) or die($sql);

if($result==false || pg_numrows($result)==0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Sem registros!');
  exit;
}

$pdf = new PDF(); // estancia a classe
$head1 = "RELATÓRIO DE TAREFAS";
if ($opcao_rel == "A"){
  $opcao = "ANALÍTICO";
} elseif($opcao_rel == "S"){
  $opcao = "SINTÉTICO";
} else {
  $opcao = "FICHA";
}

$head2 = $opcao;
$head4 = "";

$pdf->open("L"); // inicia a geração do documento
$total_geral = 0; // criação de uma variável para somar o total de registros
$pdf->settextcolor(0,0,0); // seta a cor do texto como preta
$pdf->setfillcolor(220); // define a cor de preenchimento
$pdf->setfont('Arial','B',9); // seta a fonte como arial, bold e tamanho 9

$ultatend = 0;
$tamanho = 4;
$numlinha = pg_numrows($result);

$p=0;
if (isset($tipo_rel)&&$tipo_rel=="c"){
  for($x=0; $x< $numlinha;$x++){
    db_fieldsmemory($result,$x);
    $nblin = $pdf->NbLines(0,$dl_tarefa); //exit;
    if (($pdf->gety() > ($pdf->h - 30)) || $x == 0){
      $pdf->addpage('L'); // adiciona uma pagina no modo paisagem
      $pdf->cell(30,$tamanho,"CODIGO",1,0,"C",0);
      $pdf->cell(30,$tamanho,"INICIO",1,0,"C",0);
      $pdf->Cell(18,$tamanho,"FIM", 1, 0, "C", 0);
      
      $pdf->Cell(50,$tamanho,"PROCEDIMENTO",1, 0, "C", 0);
      $pdf->Cell(0,$tamanho,"CLIENTE",1, 1, "C", 0);
      
      $pdf->Cell(0,$tamanho,"DESCRICAO",1, 1, "C", 0);
      
      if ($opcao_rel != "S") {
        $pdf->Cell(276,$tamanho,"ANDAMENTO",1, 0, "L", 0);
        $pdf->Ln();
      }
      $p=0;
    }
    
    $pdf->cell(30,$tamanho,$at40_sequencial,0,0,"L",$p);
    $pdf->cell(30,$tamanho,db_formatar($at40_diaini, 'd'),0,0,"L",$p);
    $pdf->cell(18,$tamanho,db_formatar($at40_diafim, 'd'),0,0,"L",$p);
    $pdf->cell(50,$tamanho,$dl_proced,0,0,"L",$p);
    
    $pdf->cell(0,$tamanho,$nome_cliente,0,1,"L",$p);
    
    $pdf->multicell(0,$tamanho,$dl_tarefa,0,"L",$p);
    if ($opcao_rel == "S") {
      $pdf->ln();
    }
    
    if ($opcao_rel != "S"){
      $sql = "select at43_descr,
      at43_obs,
      at43_diaini,
      at43_diafim,
      nome,
      at45_perc
      from tarefalog
      inner join tarefa on tarefa.at40_sequencial = tarefalog.at43_tarefa
      inner join db_usuarios on db_usuarios.id_usuario = tarefalog.at43_usuario
      left  join tarefaenvol on at45_usuario = tarefalog.at43_usuario and 
      at45_tarefa  = tarefalog.at43_tarefa
      where at43_tarefa=$at40_sequencial 
      order by at43_sequencial";
      $res_tarefalog = pg_query($sql);
      $numrows       = pg_numrows($res_tarefalog);
      if ($numrows > 0){
        for($i=0; $i < $numrows; $i++){
          db_fieldsmemory($res_tarefalog,$i);
          if ($at45_perc == ""){
            $at45_perc = 0;
          }
          $pdf->multicell(276,5,"AND. ".($i+1)." - ".db_formatar($at43_diaini,"d")." ".db_formatar($at43_diafim,"d")." - Usuário: ".$nome." - perc. envolv. - ".$at45_perc." %",0,"L",$p);
          $pdf->multicell(276,5,$at43_descr,0,"L",$p);
          $pdf->multicell(276,5,$at43_obs,0,"L",$p);
        }
      }
      $pdf->ln();
    }
    
    if ($p==1){
      $p=0;
    }else{
      $p=1;
    }
		
  } 
}else{
  for($x=0; $x < $numlinha;$x++) {
    db_fieldsmemory($result,$x); 
    if (($pdf->gety() > ($pdf->h - 30)) || $x == 0 ){
      $pdf->addpage('L');
      $pdf->cell(20,$tamanho,"CODIGO",1,0,"C",$p);
      $pdf->cell(10,$tamanho,"AUT",1,0,"C",$p);
      $pdf->cell(10,$tamanho,"PROG",1,0,"C",$p);
      $pdf->cell(10,$tamanho,"PRIO",1,0,"C",$p);
      $pdf->cell(18,$tamanho,"INICIO",1,0,"C",$p);
      $pdf->cell(10,$tamanho,"DUR",1,0,"C",$p);
      $pdf->Cell(18,$tamanho,"FIM", 1, 0, "C", $p);
      $pdf->cell(15,$tamanho,"PEND",1,0,"C",$p);
      $pdf->Cell(15,$tamanho,"ENVOLV",1, 0, "C", $p);
      $pdf->Cell(50,$tamanho,"CLIENTE",1, 0, "C", $p);
      $pdf->Cell(50,$tamanho,"ENVOLVIDO",1, 0, "C", $p);
      $pdf->Cell(50,$tamanho,"CRIADOR",1, 0, "C", $p);
      $pdf->Ln();
      
      $pdf->Cell(276,$tamanho,"DESCRICAO",1, 1, "C", $p);
      
      if ($opcao_rel != "S") {
        $pdf->Cell(276,$tamanho,"ANDAMENTO",1, 0, "L", $p);
        $pdf->Ln();
      }
			if ($p==1){
				$p=0;
			}else{
				$p=1;
			}
    }
    
    if($x % 2 == 0) {
      $corfundo = 200;
    }else{
      $corfundo = 245;
    }
//    $pdf->SetFillColor($corfundo);
    
    $pdf->cell(20,$tamanho,$at40_sequencial,0,0,"L",$p);
    $pdf->cell(10,$tamanho,($dl_aut == 't'?"SIM":"NAO"),0,0,"L",$p);
    $pdf->cell(10,$tamanho,$at40_progresso,0,0,"L",$p);
    $pdf->cell(10,$tamanho,$at40_prioridade,0,0,"L",$p);
    $pdf->cell(18,$tamanho,db_formatar($at40_diaini, 'd'),0,0,"L",$p);
    $pdf->Cell(10,$tamanho,$dl_duração, 0, 0, "L", $p);
    $pdf->cell(18,$tamanho,db_formatar($at40_diafim, 'd'),0,0,"L",$p);
    $pdf->cell(15,$tamanho,$dl_pendente,0,0,"L",$p);
    $pdf->cell(15,$tamanho,$dl_envolvimento,0,0,"L",$p);
    $pdf->cell(50,$tamanho,$nome_cliente,0,0,"L",$p);
    $pdf->cell(50,$tamanho,$dl_envolvido,0,0,"L",$p);
    $pdf->cell(50,$tamanho,substr($dl_criador,0,23),0,0,"L",$p);
    $pdf->Ln();
    
    $pdf->multicell(276,5,$dl_tarefa,0,"L",$p);
    if ($opcao_rel == "S"){
      $pdf->Ln();
    }
    if ($p==1){
      $p=0;
    }else{
      $p=1;
    }
    
    if ($opcao_rel != "S"){
      $sql = "select at43_descr,
      at43_obs,
      at43_diaini,
      at43_diafim,
			at43_horainidia,
			at43_horafim,
      nome,
      at45_perc
      from tarefalog
      inner join tarefa on tarefa.at40_sequencial = tarefalog.at43_tarefa
      inner join db_usuarios on db_usuarios.id_usuario = tarefalog.at43_usuario
      left  join tarefaenvol on at45_usuario = tarefalog.at43_usuario and 
      at45_tarefa  = tarefalog.at43_tarefa
      where at43_tarefa=$at40_sequencial 
      order by at43_sequencial";
      $res_tarefalog = pg_query($sql) or die($sql);
      $numrows       = pg_numrows($res_tarefalog);
      if ($numrows > 0){
				$pdf->Ln(5);
				$pdf->setfont('Arial','B',12); // seta a fonte como arial, bold e tamanho 9
				$pdf->Multicell(276,10,"A   N   D   A   M   E   N   T   O   S","TB","C",$p);
				$pdf->setfont('Arial','B',9); // seta a fonte como arial, bold e tamanho 9
				$pdf->Ln(3);
        for($i=0; $i < $numrows; $i++) {
          db_fieldsmemory($res_tarefalog,$i);
          if ($at45_perc == ""){
            $at45_perc = 0;
          }
          $pdf->multicell(276,5,($i+1)." - ".db_formatar($at43_diaini,"d")." A ".db_formatar($at43_diafim,"d") . " - Horário: " . $at43_horainidia . " A " . $at43_horafim . " - Usuário: ".$nome." - perc. envolv. - ".$at45_perc." %",0,"L",$p);
          $pdf->multicell(276,5,$at43_descr,0,"L",$p);
          $pdf->multicell(276,5,$at43_obs,0,"L",$p);
					$pdf->Ln(3);
					if ($p==1){
						$p=0;
					}else{
						$p=1;
					}
        }
      }
      $pdf->ln();
    }
    if ($p==1){
      $p=0;
    }else{
      $p=1;
    }
  }
}

if (!isset($at40_sequencial)) {
	$pdf->Ln(2);
	$pdf->cell(0,$tamanho,"TOTAL DE REGISTROS: " . $numlinha,"T",1,"L",0);
	$pdf->Ln(2);
	if (isset($tipo_rel)&&$tipo_rel=="c"){
	}else{
		$pdf->cell(50,$tamanho,@$filtro1,0,1,"L",0);
		$pdf->cell(50,$tamanho,@$filtro2,0,1,"L",0);
		$pdf->cell(50,$tamanho,@$filtro3,0,1,"L",0);
		$pdf->cell(50,$tamanho,@$filtro5,0,1,"L",0);
		$pdf->cell(50,$tamanho,@$filtro6,0,1,"L",0);
		$pdf->cell(50,$tamanho,@$filtro7,0,1,"L",0);
		$pdf->cell(50,$tamanho,@$filtro8,0,1,"L",0);
	}
}

$pdf->Output(); // saída do relatório direto para o browser

?>