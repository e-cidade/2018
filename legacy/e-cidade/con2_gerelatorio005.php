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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");


 if($limite==0){
  $limit="";
 }else{
   $limit=" limit ".$limite;
 }
  $resultsql = @db_query(str_replace('\\','',$sql.@$limit ));
  $result = db_query("select max(codger) + 1 from db_gerador");

  if(@$libera==true){
      db_query("update  db_gerador set  nomeger='$nome', tituloger='$titulo', finalidadeger='$finalidade', sqlger='$sql', limiteger=$limite,visualizacao='$visualizacao',intercalar1='$intercalar1', intercalar2='$intercalar2', pcabecaltura=$pcabecaltura,pcorpaltura=$pcorpaltura  where codger=$codigo ");
  }else{
     $resultsql = @db_query(str_replace('\\','',$sql.@$limit ));
     $result = db_query("select max(codger) + 1 from db_gerador");
     $codger = pg_result($result,0,0);
     $codger = $codger==""?"1":$codger;
      db_query("insert into db_gerador values($codger,'$nome','$titulo','$finalidade','$sql',$limite,'$visualizacao','$intercalar1','$intercalar2',$pcabecaltura,$pcorpaltura)");
  }  

 
 $HTTP_SERVER_VARS['SCRIPT_FILENAME'];
 $root = substr($HTTP_SERVER_VARS['SCRIPT_FILENAME'],0,strrpos($HTTP_SERVER_VARS['SCRIPT_FILENAME'],"/"));
 $nomeok=ereg_replace(" ","",$nome);
 $arquivo = ($root."/"."gerador/".$nomeok."002.php");
 $fd = fopen($arquivo,"w");
 fputs($fd,'<? '."\n");
 fputs($fd,'  include("fpdf151/pdfger.php");'."\n");
 fputs($fd,'  // variaveis de cabeçalho;'."\n");
 fputs($fd,'  db_postmemory($HTTP_SERVER_VARS);'."\n");
 fputs($fd,'if($limite==0||$limite=="" ){'."\n");
 fputs($fd,'  unset($limit);'."\n");
 fputs($fd,'}else{'."\n");
 fputs($fd,'  $limit=" limit ".$limite;'."\n");
 fputs($fd,'}'."\n");
 fputs($fd,'  $resultsql = db_query(str_replace(\'\\\\\',\'\',\''.$sql.'\'.@$limit));'."\n");
 fputs($fd,'  if($resultsql==false){'."\n");
 fputs($fd,'    echo "Verifique os dados a serem gerados.<br>";'."\n");
 fputs($fd,'    echo "'.$sql.'";'."\n");
 fputs($fd,'  }'."\n");
 fputs($fd,'  $head1 = $nome;'."\n");
 fputs($fd,'  $head2 = "";'."\n");
 fputs($fd,'  $head3 = "";'."\n");
 fputs($fd,'  $head4 = $titulo;'."\n");
 fputs($fd,'  $head5 = "";'."\n");
 fputs($fd,'  $head6 = "";'."\n");
 fputs($fd,'  $head7 = "";'."\n");
 fputs($fd,'  $head8 = substr($finalidade, 0,30);'."\n");
 fputs($fd,'  $head9 = substr($finalidade,30,30);'."\n");
 fputs($fd,'  $DB_instit = db_getsession("DB_instit");'."\n");
 fputs($fd,'  $pdf = new PDF();'."\n");
 fputs($fd,'  $pdf->Open();'."\n");
 fputs($fd,'  $pdf->AliasNbPages();'."\n");
 fputs($fd,' $pdf->AddPage($visualizacao);'."\n");
 fputs($fd,' // monta cabecalho do relatório'."\n");
 fputs($fd,' $pdf->setY(40);'."\n");
 fputs($fd,' $pdf->setX(5);'."\n");
 fputs($fd,' $clrotulolov = new rotulolov;'."\n");  
 fputs($fd,' $fm_numfields = pg_numfields($resultsql);'."\n");
 fputs($fd,' $tamanho = array();'."\n");
 fputs($fd,' if($visualizacao=="L"){'."\n");
 fputs($fd,'   $maxlargura=300;'."\n");
 fputs($fd,' }else{'."\n");
 fputs($fd,'       $maxlargura=185;'."\n");
 fputs($fd,' }'."\n");
 fputs($fd,' $nova="";'."\n");
  
  $resultsql = db_query(str_replace('\\','',$sql));
  for ($i = 0;$i < $fm_numfields;$i++){
    $v="cabecfonte_".$i;
    fputs($fd,' $cabecfonte_'.$i.'="'.$$v.'";'."\n");
    $cabecfonte=$$v;
  
    $v="cabeccortexto_".$i;
    fputs($fd,' $cabeccortexto_'.$i.'="'.$$v.'";'."\n");
    $cabeccortexto=$$v;    

    $v="cabectamanho_".$i;
    fputs($fd,' $cabectamanho_'.$i.'="'.$$v.'";'."\n");
    $cabectamanho=$$v;   
    
    $v="cabecn_".$i;
    fputs($fd,' $cabecn_'.$i.'="'.@$$v.'";'."\n");
    $cabecn=@$$v;
   
    $v="cabeci_".$i;
    fputs($fd,' $cabeci_'.$i.'="'.@$$v.'";'."\n");
    $cabeci=@$$v;

    $v="cabecs_".$i;
    fputs($fd,' $cabecs_'.$i.'="'.@$$v.'";'."\n");
    $cabecs=@$$v;

    $v="cabeccortexto_".$i;
    fputs($fd,' $cabeccortexto_'.$i.'="'.$$v.'";'."\n");
    $cabeccortexto=$$v; 
       
    $v="cabeccorborda_".$i;
    fputs($fd,' $cabeccorborda_'.$i.'="'.$$v.'";'."\n");
    $cabeccorborda=$$v;    

    $v="cabeccorfundo_".$i;
    fputs($fd,' $cabeccorfundo_'.$i.'="'.$$v.'";'."\n");
    $cabeccorfundo=$$v;    

    $v="cabecaltura_".$i;
    fputs($fd,' $cabecaltura_'.$i.'="'.$$v.'";'."\n");
    $cabecaltura=$$v;    

    $v="cabeclargura_".$i;
    fputs($fd,' $cabeclargura_'.$i.'="'.$$v.'";'."\n");
    $cabeclargura=$$v;

//corpo

    $v="corpfonte_".$i;
    fputs($fd,' $corpfonte_'.$i.'="'.$$v.'";'."\n");
    $corpfonte=$$v;    

    $v="corpopcao_".$i;
    fputs($fd,' $corpopcao_'.$i.'="'.$$v.'";'."\n");
    $corpopcao=$$v;
 
    $v="corpcortexto_".$i;
    fputs($fd,' $corpcortexto_'.$i.'="'.$$v.'";'."\n");
    $corpcortexto=$$v; 
     
    $v="corptamanho_".$i;
    fputs($fd,' $corptamanho_'.$i.'="'.$$v.'";'."\n");
    $corptamanho=$$v;      

    $v="corpn_".$i;
    fputs($fd,' $corpn_'.$i.'="'.@$$v.'";'."\n");
    $corpn=@$$v;

    $v="corpi_".$i;
    fputs($fd,' $corpi_'.$i.'="'.@$$v.'";'."\n");
    $corpi=@$$v;

    $v="corps_".$i;
    fputs($fd,' $corps_'.$i.'="'.@$$v.'";'."\n");
    $corps=@$$v;
    
    $v="corpcorborda_".$i;
    fputs($fd,' $corpcorborda_'.$i.'="'.$$v.'";'."\n");
    $corpcorborda=$$v;
    
    $v="corpcorfundo_".$i;
    fputs($fd,' $corpcorfundo_'.$i.'="'.$$v.'";'."\n");
    $corpcorfundo=$$v;    

    $v="corpaltura_".$i;
    fputs($fd,' $corpaltura_'.$i.'="'.$$v.'";'."\n");
    $corpaltura=$$v;

    $v="corplargura_".$i;
    fputs($fd,' $corplargura_'.$i.'="'.$$v.'";'."\n");
    $corplargura=$$v;
    
    $coluna = pg_fieldname($resultsql,$i);

    if(@$libera==true){
      //db_query("update  db_gerpref set coluna ='$coluna',cabecfonte='$cabecfonte', cabectamanho=$cabectamanho, cabecn='$cabecn', cabeci='$cabeci', cabecs='$cabecs',cabeccortexto='$cabeccortexto', cabeccorborda='$cabeccorborda', cabeccorfundo='$cabeccorfundo', cabecaltura=$cabecaltura, cabeclargura='$cabeclargura', corpfonte='$corpfonte',corpopcao='$corpopcao',corptamanho=$corptamanho, corpn='$corpn', corpi='$corpi',corps='$corps', corpcortexto='$corpcortexto', corpcorborda='$corpcorborda', corpcorfundo='$corpcorfundo', corpaltura='$corpaltura', corplargura='$corplargura' where codger=$codigo");
      if($i==0){   
        db_query("delete from db_gerpref where codger=$codigo");
      }
      db_query("insert into db_gerpref values ($codigo,'$coluna','$cabecfonte',$cabectamanho,'$cabecn','$cabeci','$cabecs','$cabeccortexto','$cabeccorborda','$cabeccorfundo',$cabecaltura,$cabeclargura,'$corpfonte','$corpopcao',$corptamanho,'$corpn','$corpi','$corps','$corpcortexto','$corpcorborda','$corpcorfundo',$corpaltura,$corplargura)");
       
    }else{	
      db_query("insert into db_gerpref values ($codger,'$coluna','$cabecfonte',$cabectamanho,'$cabecn','$cabeci','$cabecs','$cabeccortexto','$cabeccorborda','$cabeccorfundo',$cabecaltura,$cabeclargura,'$corpfonte','$corpopcao',$corptamanho,'$corpn','$corpi','$corps','$corpcortexto','$corpcorborda','$corpcorfundo',$corpaltura,$corplargura)");
    }
 }
 fputs($fd,' for ($i = 0;$i < $fm_numfields;$i++){'."\n");
 fputs($fd,'   $cabecfonte="cabecfonte_".$i;'."\n");
 fputs($fd,'   $cabectamanho="cabectamanho_".$i;'."\n");
 fputs($fd,'   $n="cabecn_".$i;'."\n");
 fputs($fd,'   $it="cabeci_".$i;'."\n");
 fputs($fd,'   $s="cabecs_".$i;'."\n");
 fputs($fd,'   $pdf->SetFont($$cabecfonte,@$$s.@$$n.@$$it ,$$cabectamanho);'."\n");
   
 fputs($fd,'   $cabeccortexto="cabeccortexto_".$i;'."\n");
 fputs($fd,'   $cor=split("#", $$cabeccortexto);'."\n");
 fputs($fd,'   $pdf->SetTextColor(@$cor[0],@$cor[1],@$cor[2]);'."\n");
 fputs($fd,'   $cabeccorborda="cabeccorborda_".$i;'."\n");
 fputs($fd,'   $cor=split("#", $$cabeccorborda);'."\n");
 fputs($fd,'   $pdf->SetDrawColor(@$cor[0],@$cor[1],@$cor[2]);'."\n");
 fputs($fd,'   $cabeccorfundo="cabeccorfundo_".$i;'."\n");
 fputs($fd,'   $cor=split("#", $$cabeccorfundo);'."\n");
 fputs($fd,'   $pdf->SetFillColor(@$cor[0],@$cor[1],@$cor[2]);'."\n");



 fputs($fd,'   $clrotulolov->label(pg_fieldname($resultsql,$i));'."\n");
 fputs($fd,'   $cabecaltura="cabecaltura_".$i;'."\n");     
 fputs($fd,'   $cabeclargura="cabeclargura_".$i;'."\n");     
 fputs($fd,'   $x=$pdf->getx();'."\n"); 
 fputs($fd,'   if($$cabeclargura+$x > $maxlargura){'."\n");
 fputs($fd,'     $pdf->ln($$cabecaltura);//não funciona '."\n");  
 fputs($fd,'     $pdf->setX(5);'."\n");
 fputs($fd,'   }'."\n");
 fputs($fd,'   $pdf->Cell($$cabeclargura,$$cabecaltura,$clrotulolov->titulo,"LRBT",($i==($fm_numfields-1)?1:0),"",1);'."\n");
 fputs($fd,'   if($clrotulolov->tamanho==""){'."\n");
 fputs($fd,' 	  $tamanho[$i] = 20;'."\n");
 fputs($fd,'   }else{'."\n");
 fputs($fd,' 	  $tamanho[$i] = (($clrotulolov->tamanho>strlen($clrotulolov->titulo)?$clrotulolov->tamanho:strlen($clrotulolov->titulo))*2)+2;'."\n");
 fputs($fd,'   }'."\n");
 fputs($fd,' }'."\n");
 fputs($fd,'  $pdf->ln(2);'."\n");
 fputs($fd,' // corpo do relatório'."\n");
 fputs($fd,' $linha = 0;'."\n");
 fputs($fd,' for ($xi=0;$xi<pg_numrows($resultsql);$xi++){'."\n");
 fputs($fd,'   $pdf->setX(5);'."\n");
 fputs($fd,'   //db_fieldsmemory($resultsql,$i);'."\n");
 fputs($fd,'   for ($c=0;$c<$fm_numfields;$c++){'."\n");
 fputs($fd,'     $corpfonte="corpfonte_".$c;'."\n");
 fputs($fd,'     $corptamanho="corptamanho_".$c;'."\n");
 fputs($fd,'     $n="corpn_".$c;'."\n");
 fputs($fd,'     $it="corpi_".$c;'."\n");
 fputs($fd,'     $s="corps_".$c;'."\n");
 fputs($fd,'     $pdf->SetFont($$corpfonte,$$s.$$n.$$it ,$$corptamanho);'."\n");
 
 fputs($fd,'   $corpcortexto="corpcortexto_".$c;'."\n");
 fputs($fd,'   $cor=split("#", $$corpcortexto);'."\n");
 fputs($fd,'   $pdf->SetTextColor(@$cor[0],@$cor[1],@$cor[2]);'."\n");

 fputs($fd,'   $corpcorborda="corpcorborda_".$c;'."\n");
 fputs($fd,'   $cor=split("#", $$corpcorborda);'."\n");
 fputs($fd,'   $pdf->SetDrawColor(@$cor[0],@$cor[1],@$cor[2]);'."\n");

 fputs($fd,'   //cores que irão intercalar'."\n");
 fputs($fd,'   $corpcorfundo="corpcorfundo_".$c;'."\n");
 fputs($fd,'   if($$corpcorfundo =="255#255#255"){'."\n");
 fputs($fd,'     if($xi % 2!=0){'."\n");
 fputs($fd,'      $cor=split("#","'.$intercalar1.'");'."\n");
 fputs($fd,'      $pdf->SetFillColor(@$cor[0],@$cor[1],@$cor[2]);'."\n");
 fputs($fd,'     }else{'."\n");
 fputs($fd,'      $cor=split("#","'.$intercalar2.'");'."\n");
 fputs($fd,'      $pdf->SetFillColor(@$cor[0],@$cor[1],@$cor[2]);'."\n");
 fputs($fd,'     }'."\n");
 fputs($fd,'   }else{'."\n");
 fputs($fd,'         $corpcorfundo="corpcorfundo_".$c;'."\n");
 fputs($fd,'         $cor=split("#",$$corpcorfundo);'."\n");
 fputs($fd,'         $pdf->SetFillColor(@$cor[0],@$cor[1],@$cor[2]);'."\n");
 fputs($fd,'   }'."\n");




 fputs($fd,'     $corpaltura="corpaltura_".$c;'."\n");     
 fputs($fd,'     $corplargura="corplargura_".$c;'."\n");     
 fputs($fd,'     $x=$pdf->getx();'."\n"); 
 fputs($fd,'     if($$corplargura+$x > $maxlargura) {'."\n");
 fputs($fd,'      $pdf->ln($$corpaltura);'."\n");   
 fputs($fd,'      $pdf->setX(5);'."\n");
 fputs($fd,'     }'."\n");

 fputs($fd,'     $y=$pdf->getY();'."\n");
 fputs($fd,'     if($visualizacao=="L"){'."\n");
 fputs($fd,'       if($y>=180){'."\n");
 fputs($fd,'         $nova=true; '."\n");
 fputs($fd,'       }'."\n");
 fputs($fd,'     }else{'."\n");
 fputs($fd,'           if($y>=270){'."\n");
 fputs($fd,'              $nova=true; '."\n");
 fputs($fd,'            }'."\n");
 fputs($fd,'     }'."\n");
 fputs($fd,'     if($c<($fm_numfields-1)){'."\n");
 fputs($fd,'       $quebra=0;'."\n");
 fputs($fd,'     }else{'."\n");
 fputs($fd,'          $quebra=1;'."\n");
 fputs($fd,'     } '."\n");
 fputs($fd,'     $pdf->Cell($$corplargura,$$corpaltura,pg_result($resultsql,$xi,$c),1,$quebra,"",1);'."\n");
 fputs($fd,'   } '."\n");
 fputs($fd,'   if($nova==true){'."\n");
 fputs($fd,'     $nova = false;'."\n");
 fputs($fd,'     //Imprime cabeçalho, caso pdf tenha mais de uam pagina '."\n");
 fputs($fd,'     $pdf->AddPage($visualizacao);'."\n");
 fputs($fd,'     $pdf->setX(5);'."\n");
 fputs($fd,'     for ($cabec=0;$cabec < $fm_numfields;$cabec++){'."\n");
 fputs($fd,'       $cabecfonte="cabecfonte_".$cabec;'."\n");
 fputs($fd,'       $cabectamanho="cabectamanho_".$cabec;'."\n");
 fputs($fd,'       $n="cabecn_".$cabec;'."\n");
 fputs($fd,'       $it="cabeci_".$cabec;'."\n");
 fputs($fd,'       $s="cabecs_".$cabec;'."\n");
 fputs($fd,'       $pdf->SetFont($$cabecfonte,$$s.$$n.$$it ,$$cabectamanho);'."\n");
 
 fputs($fd,'       $cabeccortexto="cabeccortexto_".$cabec;'."\n");
 fputs($fd,'       $cor=split("#", $$cabeccortexto);'."\n");
 fputs($fd,'       $pdf->SetTextColor(@$cor[0],@$cor[1],@$cor[2]);'."\n");
 fputs($fd,'       $cabeccorborda="cabeccorborda_".$cabec;'."\n");
 fputs($fd,'       $cor=split("#", $$cabeccorborda);'."\n");
 fputs($fd,'       $pdf->SetDrawColor(@$cor[0],@$cor[1],@$cor[2]);'."\n");
 fputs($fd,'       $cabeccorfundo="cabeccorfundo_".$cabec;'."\n");
 fputs($fd,'       $cor=split("#", $$cabeccorfundo);'."\n");
 fputs($fd,'       $pdf->SetFillColor(@$cor[0],@$cor[1],@$cor[2]);'."\n");
 fputs($fd,'       $clrotulolov->label(pg_fieldname($resultsql,$cabec));'."\n");
 fputs($fd,'       $cabecaltura="cabecaltura_".$cabec;     '."\n");
 fputs($fd,'       $cabeclargura="cabeclargura_".$cabec;     '."\n");
 fputs($fd,'       $x=$pdf->getx(); '."\n");
 fputs($fd,'       if($$cabeclargura+$x > $maxlargura){'."\n");
 fputs($fd,'         $pdf->ln($$altura);//não funciona   '."\n");
 fputs($fd,'         $pdf->setX(5);'."\n");
 fputs($fd,'       } '."\n");
 fputs($fd,'       $clrotulolov->label(pg_fieldname($resultsql,$cabec));'."\n");
 fputs($fd,'       $pdf->Cell($$cabeclargura,$$cabecaltura,$clrotulolov->titulo,"LRBT",($cabec==($fm_numfields-1)?1:0),"L",1);'."\n");
 fputs($fd,'       //$pdf->Cell((($clrotulolov->tamanho>strlen($clrotulolov->titulo)?$clrotulolov->tamanho:strlen($clrotulolov->titulo))*2)+2,4,$clrotulolov->titulo,"LRBT",($cabec==($fm_numfields-1)?1:0),"L",0);'."\n");
 fputs($fd,'     }'."\n");
 fputs($fd,'     $pdf->ln(1);	   '."\n");
 fputs($fd,'   } '."\n");
 fputs($fd,' }'."\n");
 fputs($fd,' $pdf->setX(5);'."\n");
 fputs($fd,' for($s=0;$s<$fm_numfields;$s++){'."\n");
 fputs($fd,'       $corpopcao="corpopcao_".$s;'."\n");
 fputs($fd,'       $corplargura="corplargura_".$s;'."\n");
 fputs($fd,'       $pdf->SetFillColor(200,200,200);'."\n");
 fputs($fd,'       if($s<($fm_numfields-1)){'."\n");
 fputs($fd,'         $quebra=0;'."\n");
 fputs($fd,'       }else{'."\n");
 fputs($fd,'             $quebra=1;'."\n");
 fputs($fd,'       }'."\n");
 fputs($fd,'       if($$corpopcao!="nenhuma"){'."\n");
 fputs($fd,'         $pdf->Cell($$corplargura,4,"Total",1,$quebra,"LRBT",1);'."\n");
 fputs($fd,'       }else{'."\n");
 fputs($fd,'         $pdf->Cell($$corplargura,4,"",1,$quebra,"LRBT",0);'."\n");
 fputs($fd,'       }'."\n");
 fputs($fd,'     }'."\n");
 fputs($fd,'       $pdf->setX(5);'."\n");
 fputs($fd,'       $soma=0;'."\n");
 fputs($fd,'       $contador=0;'."\n");
 fputs($fd,'       $preenchido=0;'."\n");
 fputs($fd,'       for($cc=0;$cc<$fm_numfields;$cc++){'."\n");
 fputs($fd,'         if($cc<($fm_numfields-1)){'."\n");
 fputs($fd,'           $quebra=0;'."\n");
 fputs($fd,'         }else{'."\n");
 fputs($fd,'            $quebra=1;'."\n");
 fputs($fd,'         }'."\n");
 fputs($fd,'         $corplargura="corplargura_".$cc;'."\n");
 fputs($fd,'         $pdf->SetFillColor(200,200,200);'."\n");
 fputs($fd,'         $corpopcao="corpopcao_".$cc;'."\n");
 fputs($fd,'         switch($$corpopcao){'."\n");
 fputs($fd,'                case "somar":'."\n");
 fputs($fd,'                       for($d=0; $d< pg_numrows($resultsql) ; $d++){'."\n");
 fputs($fd,'                         $result=pg_result($resultsql, $d, $cc);'."\n");
 fputs($fd,'                         $soma+=$result;'."\n");
 fputs($fd,'                       }'."\n");
 fputs($fd,'                      $pdf->Cell($$corplargura,4,$soma,1,$quebra,"LRBT",1);'."\n");
 fputs($fd,'                       break;'."\n");
 fputs($fd,'                case "preenchidos":'."\n");
 fputs($fd,'                      for($d=0; $d< pg_numrows($resultsql) ; $d++){'."\n");
 fputs($fd,'                       $result=pg_result($resultsql, $d, $cc);'."\n");
 fputs($fd,'                        if($result!=""){'."\n");
 fputs($fd,'                           $preenchido+=1;'."\n");
 fputs($fd,'                        }'."\n");
 fputs($fd,'                      }'."\n");
 fputs($fd,'                      $pdf->Cell($$corplargura,4,$preenchido,1,$quebra,"LRBT",1);'."\n");
 fputs($fd,'                      break;'."\n");
 fputs($fd,'                case "contar":'."\n");
 fputs($fd,'                    for($d=0; $d< pg_numrows($resultsql) ; $d++){'."\n");
 fputs($fd,'                       $result=pg_result($resultsql, $d, $cc);'."\n");
 fputs($fd,'                       $contador+=1;'."\n");
 fputs($fd,'                     }'."\n");
 fputs($fd,'                     $pdf->Cell($$corplargura,4,$contador,1,$quebra,"LRBT",1);'."\n");
 fputs($fd,'                     break;'."\n");
 fputs($fd,'                case "nenhuma";'."\n");
 fputs($fd,'                     $pdf->Cell($$corplargura,4,"",0,$quebra,"LRBT",0);'."\n");
 fputs($fd,'                     break;'."\n");
 fputs($fd,'       }'."\n");
 fputs($fd,'     }'."\n");



 fputs($fd,' //$pdf->Cell(200,2,"","",1,"L",0);'."\n");
 fputs($fd,' //$pdf->Cell(200,0,"","LRBT",1,"L",0);'."\n");
 fputs($fd,'  //$tmpfile=tempnam("tmp","tmp.pdf");'."\n");
 fputs($fd,' $pdf->Output();'."\n");
 fputs($fd,'?>'."\n");
 fclose($fd);
// echo "<script> location.href=\"con2_gerelatorio001.php\"</script>";
// $nome=basename($arquivo);
//system ("cp /home/dbpref/public_html/dbportal2/tmp/pdf_GTxfxS  /home/dbpref/public_html/dbportal2/tmp/xxx.php" );
// echo "<script> location.href=\"tmp/regua.php\"</script>";

  if(@$libera==true){
   system("rm -f gerador/".$nomeok."001.php");
 }
 $visualizacaook=$visualizacao=="P"?"Retrato":"Paisagem";
 $HTTP_SERVER_VARS['SCRIPT_FILENAME'];
 $root = substr($HTTP_SERVER_VARS['SCRIPT_FILENAME'],0,strrpos($HTTP_SERVER_VARS['SCRIPT_FILENAME'],"/"));
 $arquivo = ($root."/"."gerador/".$nomeok."001.php");
 $fd = fopen($arquivo,"w");
 fputs($fd,'<?'."\n");
 fputs($fd,'require("libs/db_stdlib.php");'."\n");
 fputs($fd,'require("libs/db_conecta.php");'."\n");
 fputs($fd,'?>'."\n");
 fputs($fd,'<html>'."\n");
 fputs($fd,'<head>'."\n");
 fputs($fd,'  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>'."\n");
 fputs($fd,'   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">'."\n");
 fputs($fd,' <meta http-equiv="Expires" CONTENT="0">'."\n");
 fputs($fd,' <script language="JavaScript" type="text/javascript" src="../scripts/scripts.js"></script>'."\n");
 fputs($fd,' <link href="../estilos.css" rel="stylesheet" type="text/css">'."\n");
 fputs($fd,'</head>'."\n");
 fputs($fd,'<body bgcolor=#CCCCCC bgcolor="#cccccc" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >'."\n");
 fputs($fd,'<script>'."\n");
 fputs($fd,' function nova(){'."\n");
 fputs($fd,'  location.href="../con2_gerelatorio006.php?vaite=true";'."\n");
 fputs($fd,'}'."\n");
 fputs($fd,'  function abra(){'."\n");
 fputs($fd,' var   titulo=document.form1.titulo.value;   '."\n");
 fputs($fd,' var   finalidade=document.form1.finalidade.value;   '."\n");
 fputs($fd,' var   visualizacao=document.form1.visualizacao.value;   '."\n");
 fputs($fd,' var   limite=document.form1.limite.value;   '."\n");
 fputs($fd,' window.open("'.$nomeok.'002.php?nome='.$nome.'&titulo="+titulo+"&finalidade="+finalidade+"&limite="+limite+"&visualizacao="+visualizacao+"&veri=res","Relatório","toolbar=no,menubar=no,scrollbars=no,resizable=yes,location=no,directories=no,status=no");'."\n");
 fputs($fd,' }'."\n");
 fputs($fd,'</script>'."\n");
 fputs($fd,'<br>'."\n");
 fputs($fd,'<table border="0" cellpadding="0" align="center" cellspacing="0" bgcolor="#cccccc"><br><br>'."\n");
 fputs($fd,'<form name="form1" method="post">'."\n");
 fputs($fd,'  <tr align="100%">'."\n");
 fputs($fd,'   <td height="35" align="center" colspan="2"><h5>Dados Referentes ao Relatório</h5></td>'."\n");
 fputs($fd,' </tr>'."\n");
 fputs($fd,'  <tr>'."\n");
 fputs($fd,'   <td height="35" width="10%"><b>Nome:</b></td>'."\n");
 fputs($fd,'   <td>'.$nome.'</td>'."\n");
 fputs($fd,' </tr> '."\n");
 fputs($fd,' <tr > '."\n");
 fputs($fd,'   <td height="35"><b>Sintaxe SQL:</b></td>'."\n");
 fputs($fd,'   <td>'.$sql.'</td>'."\n");
 fputs($fd,' </tr> '."\n");
 fputs($fd,' <tr> '."\n");
 fputs($fd,'   <td height="35"><b>Titulo:</b></td>'."\n");
 fputs($fd,'   <td><input type="text" name="titulo" value="'.$titulo.'"></td>'."\n");
 fputs($fd,' </tr> '."\n");
 fputs($fd,' <tr> '."\n");
 fputs($fd,'   <td height="35"><b>Finalidade:</b></td>'."\n");
 fputs($fd,'   <td><input type="text" name="finalidade" value="'.$finalidade.'"></td>'."\n");
 fputs($fd,' <tr> '."\n");
 fputs($fd,'   <td height="35"><b>Pagina:</b></td> '."\n"); 
 fputs($fd,'   <td><select  name="visualizacao">'."\n");
                if($visualizacao=="P"){    
 fputs($fd,'      <option value="P" selected >Retrato</option>'."\n");
 fputs($fd,'      <option value="L" >Paisagem</option>'."\n");
                } 
                if($visualizacao=="L"){    
 fputs($fd,'      <option value="P">Retrato</option>'."\n");
 fputs($fd,'      <option value="L" selected>Paisagem</option>'."\n");
                } 
 fputs($fd,'   </td> '."\n");
 fputs($fd,' </tr> '."\n");
 fputs($fd,'   <td nowrap ><b>Limite de linhas:</b></td>'."\n");
   fputs($fd,'   <td><input  type="text" name="limite" value="'.$limite.'"></td>'."\n");
 fputs($fd,' </tr> '."\n");
 fputs($fd,' <tr>'."\n");
 fputs($fd,'   <td height="40" align="center" colspan="2">'."\n");
 fputs($fd,'      <input type="button" name="abrir" value="Gerar Relatório" onclick="abra()">'."\n");
fputs($fd,'   </td>'."\n");
 fputs($fd,' </tr>'."\n");
 fputs($fd,'</form1>'."\n");
 fputs($fd,'</table>  '."\n");
 fputs($fd,'</body>'."\n");
 fputs($fd,'</html>'."\n");
 fclose($fd);
  echo "<script> location.href=\"con2_gerelatorio006.php?nome=".$nomeok."001.php\"</script>";
?>