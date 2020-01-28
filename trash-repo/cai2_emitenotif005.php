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

include("libs/db_sql.php");
include("libs/db_utils.php");
require_once('fpdf151/PDF_Label.php');

db_postmemory($HTTP_SERVER_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$instit = db_getsession("DB_instit");
$sqlinst = "select * from db_config where codigo = ".db_getsession("DB_instit");
db_fieldsmemory(db_query($sqlinst),0,true);



if (isset($notifparc)){

	$sSqlNotifParc = " select k55_matric   as j01_matric,
					          k56_inscr	   as q02_inscr,
					          k57_numcgm   as z01_numcgm, 
					          case when k55_matric is not null
					               then (select lpad(z01_numcgm,6,0)||' '||z01_nome
					                     from proprietario_nome
					                     where j01_matric = k55_matric limit 1)
					               else case when k56_inscr is not null
					                         then (select lpad(q02_numcgm,6,0)||' '||z01_nome
					                               from empresa
					                               where q02_inscr = k56_inscr limit 1)
					                    else (select lpad(z01_numcgm,6,0)||' '||z01_nome
					                          from cgm
					                          where z01_numcgm = k57_numcgm limit 1)
					                    end
					           end as z01_nome,
					           sum(k43_vlrcor+k43_vlrjur+k43_vlrmul-k43_vlrdes) as valor
					   from notidebitosreg
					        left  join notimatric  on k43_notifica = k55_notifica
					        left  join notiinscr   on k43_notifica = k56_notifica
					        left  join notinumcgm  on k43_notifica = k57_notifica
					  where k43_notifica = {$notifica}	     
					   group by k43_notifica,
					        k55_matric,
					        k56_inscr,
					        k57_numcgm,
					        z01_nome ";

     $result      = db_query($sSqlNotifParc) or die($sSqlNotifParc);
     $oNotifParc  = db_utils::fieldsMemory($result,0);
     
		if (isset($oNotifParc->j01_matric) && trim($oNotifParc->j01_matric) != ""){
			
			$k60_tipo = "M";
		    $xtipo    = 'Matrícula';
		    $xcodigo  = 'matric';
		    $xcodigo1 = 'j01_matric';
		    $xxcodigo1 = 'k55_matric';
		    $xxmatric = ' inner join notimatric on matric = k55_matric ';
		    $xxcodigo = 'k55_notifica';

		} else if(isset($oNotifParc->q02_inscr) && trim($oNotifParc->q02_inscr) != ""){
			
			$k60_tipo = "I";
		    $xtipo    = 'Inscrição';
		    $xcodigo  = 'inscr';
		    $xcodigo1 = 'q02_inscr';
		    $xxcodigo1 = 'k56_inscr';
		    $xxmatric = ' inner join notiinscr on inscr = k56_inscr ';
		    $xxcodigo = 'k56_notifica';
		
		} else {
			$k60_tipo = "N";
		    $xtipo    = 'Numcgm';
		    $xcodigo  = 'numcgm';
		    $xcodigo1 = 'j01_numcgm';
		    $xxcodigo1 = 'k57_numcgm';
		    $xxmatric = ' inner join notinumcgm on numcgm = k57_numcgm ';
		    $xxcodigo = 'k57_notifica';
		    
		}
  
  $lim1 = 0;
  $lim2 = pg_num_rows($result);	
		
	
} else {

$sql = "select * from lista where k60_codigo = $lista and k60_instit = $instit ";

$result = db_query($sql);
db_fieldsmemory($result,0);

$sqllistatipo = "select listatipos.*, k03_descr 
					from listatipos 
					inner join arretipo on k00_tipo = k62_tipodeb 
					inner join cadtipo on arretipo.k03_tipo = cadtipo.k03_tipo 
					where k62_lista = $lista";
$resultlistatipo = db_query($sqllistatipo);
$virgula = '';
$tipos = '';
$descrtipo = '';
for($yy = 0;$yy < pg_numrows($resultlistatipo);$yy++ ){
   db_fieldsmemory($resultlistatipo,$yy);
   $tipos .= $virgula.$k62_tipodeb;
   $descrtipo .= $virgula.trim($k03_descr);
   $virgula = ' , ';
}

if ($k60_tipo == 'M'){
    $xtipo    = 'Matrícula';
    $xcodigo  = 'matric';
    $xcodigo1 = 'j01_matric';
    $xxcodigo1 = 'k55_matric';
    $xxmatric = ' inner join notimatric on matric = k55_matric ';
    $xxcodigo = 'k55_notifica';
    if (isset($campo)){
       if ($tipo == 2){
          $contr = 'and matric in ('.str_replace("-",", ",$campo).') ';
       }elseif ($tipo == 3){
          $contr = 'and matric not in ('.str_replace("-",", ",$campo).') ';
       }
    }else{
       $contr = '';
    }
}elseif($k60_tipo == 'I'){
    $xtipo    = 'Inscrição';
    $xcodigo  = 'inscr';
    $xcodigo1 = 'q02_inscr';
    $xxcodigo1 = 'k56_inscr';
    $xxmatric = ' inner join notiinscr on inscr = k56_inscr ';
    $xxcodigo = 'k56_notifica';
    if (isset($campo)){
       if ($tipo == 2){
          $contr = 'and inscr in ('.str_replace("-",", ",$campo).') ';
       }elseif ($tipo == 3){
          $contr = 'and inscr not in ('.str_replace("-",", ",$campo).') ';
       }
    }else{
       $contr = '';
    }

}elseif($k60_tipo == 'N'){
    $xtipo    = 'Numcgm';
    $xcodigo  = 'numcgm';
    $xcodigo1 = 'j01_numcgm';
    $xxcodigo1 = 'k57_numcgm';
    $xxmatric = ' inner join notinumcgm on numcgm = k57_numcgm ';
    $xxcodigo = 'k57_notifica';
    if (isset($campo)){
       if ($tipo == 2){
          $contr = 'and numcgm in ('.str_replace("-",", ",$campo).') ';
       }elseif ($tipo == 3){
          $contr = 'and numcgm not in ('.str_replace("-",", ",$campo).') ';
       }
    }else{
       $contr = '';
    }
}

if($ordem == 'a'){
  $xxordem = ' order by z01_nome ';
}elseif($ordem == 't'){
  $xxordem = ' order by '.$xxcodigo;
}else{
  $xxordem = ' order by '.$xxcodigo1;
}
if($fim > 0 && $intervalo == 'n'){
  $limite = 'and '.$xxcodigo.' >= '.$inicio.' and '.$xxcodigo.' <= '.$fim;
}else{
  $limite = '';
}

$sql999 = "select $xxcodigo as notifica,$xcodigo1,z01_numcgm,z01_nome,sum(valor_vencidos) as xvalor
        from 
             (select distinct $xcodigo as $xcodigo1,
                     $xxcodigo,
                     z01_numcgm,
                     z01_nome,
                     valor_vencidos
              from 
                   (select distinct k61_numpre,k61_codigo,k60_datadeb 
 	            from listadeb 
	 	         inner join lista on k60_codigo = k61_codigo and k60_instit = $instit
                    where k61_codigo = $lista ) as a
              inner join devedores b on a.k61_numpre = b.numpre and b.data = a.k60_datadeb
              $xxmatric $limite $contr
              inner join cgm on z01_numcgm = b.numcgm
        where k61_codigo = $lista ) as y
        group by $xxcodigo,
                 $xcodigo1,
                 z01_numcgm,
                 z01_nome
        $xxordem
        ";

$sql = "select $xxcodigo as notifica,$xxcodigo1 as $xcodigo1,z01_numcgm,z01_nome,sum(valor_vencidos) as xvalor
        from lista
             inner join listanotifica on k63_codigo = k60_codigo
             inner join devedores on k63_numpre = numpre and k60_datadeb = data
             $xxmatric $limite $contr and $xxcodigo = k63_notifica
             inner join cgm on z01_numcgm = numcgm
        where k60_codigo = $lista 
        group by $xxcodigo,
                 $xxcodigo1,
                 z01_numcgm,
                 z01_nome
        $xxordem
        ";
//die($sql);
$result = db_query($sql);
if (pg_numrows($result) == 0){
  
   $oParms = new stdClass();
   $oParms->sLista = $lista;
   $sMsg = _M('tributario.notificacoes.cai2_emitenotif005.nenhuma_notificacao', $oParms);
   db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
   exit; 
} 


if($fim > 0 && $intervalo != 'n'){
  if($inicio > 0){
    $lim1 = $inicio - 1;
  }else{
    $lim = 0;
  }
  if($fim > pg_numrows($result)){
    $lim2 = pg_numrows($result);
  }else{
    $lim2 = $fim;
  }
}else{
  $lim1 = 0;
  $lim2 = pg_numrows($result);
}

}

$pdf = new PDF_Label (array('name'=>'5161','paper-size'=>'A4','metric'=>'mm','marginLeft'=>9,'marginTop'=>1,'NX'=>1,'NY'=>8,'SpaceX'=>1,'SpaceY'=>3,'width'=>100,'height'=>36,'font-size'=>9),1,1);
$pdf->Open();


for($x=$lim1;$x < $lim2;$x++) {
  db_fieldsmemory($result,$x);

  if ($k60_tipo == 'M'){

    $imprime = "MATRIC: $j01_matric";

    $sqlpropri = "select proprietario from proprietario where j01_matric = $j01_matric";
    $resultpropri = db_query($sqlpropri);
    if (pg_numrows($resultpropri) > 0) {
      db_fieldsmemory($resultpropri,0);
      $z01_nome = $proprietario;
    }

    $sqlender = "select fc_iptuender($j01_matric)";
    $resultender = db_query($sqlender);
    db_fieldsmemory($resultender,0);

    $endereco = split("#",$fc_iptuender);

    $z01_ender    = @$endereco[0];
    $z01_numero   = @$endereco[1];
    $z01_compl    = @$endereco[2];
    $z01_bairro   = @$endereco[3];
    $z01_munic    = @$endereco[4];
    $z01_uf       = @$endereco[5];
    $z01_cep      = @$endereco[6];
    $z01_cxpostal = @$endereco[7];
    
  } elseif ($k60_tipo == 'I'){

    $imprime = "INSCRICAO: $q02_inscr";

    $sqlempresa = "select * from empresa where q02_inscr = $q02_inscr";
    $resultempresa = db_query($sqlempresa);
    if (pg_numrows($resultempresa) > 0) {
      db_fieldsmemory($resultempresa,0);
    }

  } else {
   
    $imprime = "CGM: $z01_numcgm";

    $sqlender = "select z01_ender, z01_numero, z01_compl, z01_bairro, z01_munic, z01_uf, z01_cep, z01_cxpostal from cgm where z01_numcgm = $z01_numcgm";
    $resultender = db_query($sqlender);
    db_fieldsmemory($resultender,0,true);
  }

  $pdf->Add_PDF_Label(sprintf("%s\n%s, %s - %s\nBAIRRO: %s\n%s - %s\n%s", "$z01_nome", "$z01_ender","$z01_numero","$z01_compl","$z01_bairro","$z01_munic","$z01_uf - CEP: $z01_cep","NOTIFICAÇÃO: $notifica - $imprime"));

}

$pdf->Output();