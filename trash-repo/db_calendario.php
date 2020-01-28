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


class db_calendario {
  
  var $pagina_original = null;
  var $clcalend     = null; 
  var $anousu       = null;
  var $mesatual     = null;
  var $diaatual     = null;
  var $matriz_layer = array();
  var $sql_cruzamento= null;
  var $sql_segundoacesso = null;
  var $pagina_alvo  = null;
  var $pagina_alvo_relatorio  = null;


  var $titulo_primeira_coluna_anual_sem = 'Módulos';

  
  var $javascript   = null;
  var $qualmes      = array("Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"); 
  var $trocalinha   = false;
  var $monta_menu   = false;

  function db_calendario (){
  	
  	$this->pagina_original = basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]); 
  	
  }
  

  function monta_calendario ($exercicio=null, $metodo=null){

    $this->clcalend = new cl_calend;
  	
    if( $exercicio == null ){
      $this->anousu = date("Y",db_getsession("DB_datausu"));
    }else{
      $this->anousu = $exercicio;    	
      if($metodo=='mais'){
        $this->anousu = $exercicio+1;
      }else if($metodo=='menos'){
        $this->anousu = $exercicio-1;
      }
    }
    $this->mesatual = date("m",db_getsession("DB_datausu"));
    $this->diaatual = date("d",db_getsession("DB_datausu"));
    
    if($this->sql_cruzamento != null){
      $this->sql_cruzamento = "select * from (".$this->sql_cruzamento.") as tab 
                               where dl_datacalend ";
      $this->sql_segundoacesso = "select * from (".$this->sql_segundoacesso.") as tab 
                               where dl_datacalend ";
      db_putsession("cruzamento",$this->sql_cruzamento);
      db_putsession("segundoacesso",$this->sql_segundoacesso);
    }else{
      db_putsession("cruzamento",'');
      db_putsession("segundoacesso",'');
    }
  
    echo "<table cellspacing=0>\n";  
    echo "<script>\n";
    echo "function js_mostra_alvo(data){
            js_OpenJanelaIframe('','db_iframe_calend','".$this->pagina_alvo."?data='+data);
          }\n";
    echo "function js_mostra_text(liga,nomediv,evt){
		    evt = (evt)?evt:(window.event)?window.event:''; 
		    if(liga==true) {
		      document.getElementById(nomediv).style.top = 0; //evt.clientY;
		      document.getElementById(nomediv).style.left = 0; //(evt.clientX+20);
		      document.getElementById(nomediv).style.visibility = 'visible';
		    }else{
		      document.getElementById(nomediv).style.visibility = 'hidden';
	        }
          } " ;
    echo "</script>\n";

    echo "<tr>\n";
    echo "<td align=center> <a href='".$this->pagina_original."?metodo=menos&exercicio=".$this->anousu."' >Anterior</a> &nbsp&nbsp ".$this->anousu."&nbsp&nbsp <a href='".$this->pagina_original."?metodo=mais&exercicio=".$this->anousu."' >Próximo</a>\n";
    echo "</td >\n";
//    echo "<td ><a href='".$this->pagina_alvo_relatorio."?exercicio=".$this->anousu."' >Imprimir</a></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td >\n";
    for($mes=1;$mes<13;$mes++){

      if($mes==1 || $mes == 5 || $mes == 9 ){
        echo "<table cellspacing=0>\n";  
        echo "<tr>\n";
      }

      $ultimodia=date('t',mktime(0,0,0,$mes,1));
      $linha = 0;
      $coluna = 6;
      $tamanho = 8;
      $xlinha = 6;
      if($mes<5){
        $poscol = 5;
      }else if ($mes<9){
        $poscol = 75;
        if($trocalinha==false){
	      $trocalinha = true;
        }
      }else{
        $poscol = 145;
	    if($mes == 9){ 
	      $trocalinha=false;
        }
        if($trocalinha==false){
	      $trocalinha = true;
        }
      }  
    
      if( $mes == $this->mesatual ){
        echo "<td ><fieldset><Legend><strong>".strtoupper($this->qualmes[$mes-1])."</strong></legend>\n";
      }else{
        echo "<td ><fieldset><Legend>".strtoupper($this->qualmes[$mes-1])."</legend>\n";
      }

      echo "<table cellspacing=2>\n";

      $dia = 1;
      $matriz_dia = array("Dom" ,"Seg","Ter" ,"Qua","Qui","Sex","Sab");
      for($y=0;$y<=$xlinha;$y++) {
       
        echo "<tr>\n";
    	
        for($i=0;$i<sizeof($matriz_dia)-1;$i++) {

		  if($mes<10){ 
		    $mesm = "0".$mes;
		  }else{ 
            $mesm = $mes;
		  }
		  if($matriz_dia[$i]<10){
		  	$diam = "0".$matriz_dia[$i];
		  }else{
		  	$diam = $matriz_dia[$i];
		  }
          if($mes == $this->mesatual && $diam == $this->diaatual ){
            $color = '6699FF';
          }else{
             $color = 'lightblue';
          }

          $result = $this->clcalend->sql_record($this->clcalend->sql_query($this->anousu."-".$mesm."-".$diam));
          if($this->clcalend->numrows != 0){
             
             $this->matriz_layer[$this->anousu."-".$mesm."-".$diam] = $result;
             $color = 'read';

          }
          
          if($this->sql_cruzamento != null){
            $result = $this->clcalend->sql_record($this->sql_cruzamento." = '".$this->anousu."-".$mesm."-".$diam."'");
            if($this->clcalend->numrows != 0){
               $this->matriz_layer[$this->anousu."-".$mesm."-".$diam] = $result;
               $color = 'FF0000';

            }
          }
          
          if( $color == 'FF0000' ){
            echo "<td height=20 align='right' bgcolor='$color' onclick='js_mostra_alvo(\"".$this->anousu."-".$mesm."-".$diam."\")' >";
            //echo "<td align='right' bgcolor='$color'>";
          }else{
            echo "<td height=20  align='right' bgcolor='$color'>";
          }
          echo $matriz_dia[$i];

          echo "</td>\n";
          flush();

       }

	   if($mes<10) $mesm = "0".$mes;
         else $mesm = $mes;
	   if($matriz_dia[6]<10) $diam = "0".$matriz_dia[6];
         else $diam = $matriz_dia[6];
      
       if($mes == $this->mesatual && $diam == $this->diaatual ){
         $color = '6699FF';
       }else{
         $color = 'lightGreen';
       }

       $result = $this->clcalend->sql_record($this->clcalend->sql_query($this->anousu."-".$mesm."-".$diam));
       
       if($this->clcalend->numrows != 0){
       	
         //$this->matriz_layer[$this->anousu."-".$mesm."-".$diam] = $result;
       
         $color = 'red1'; // onMouseOver="js_mostra_text(true,\'div_calend_'.$this->anousu."-".$mesm."-".$diam.'\',event);" onMouseOut="js_mostra_text(false,\'div_calend_'.$this->anousu."-".$mesm."-".$diam.'\',event)"';
       
       }

       if($this->sql_cruzamento != null){
         $result = $this->clcalend->sql_record($this->sql_cruzamento." = '".$this->anousu."-".$mesm."-".$diam."'");
         if($this->clcalend->numrows != 0){
           $this->matriz_layer[$this->anousu."-".$mesm."-".$diam] = $result;
           $color = 'FF0000';
         }
       }
 
       if( $color == 'FF0000' ){
         echo "<td height=20 align='right' bgcolor='$color' onclick='js_mostra_alvo(\"".$this->anousu."-".$mesm."-".$diam."\")' >";
       }else{
         echo "<td  bgcolor=$color align='right'  >";
       }
       echo $matriz_dia[6];
       echo "</td>\n";
       if($y==0){
          $diames = date('w',mktime(0,0,0,$mes,1));  
  	      for($m=0;$m<7;$m++){
            if($m>=$diames)
	          $matriz_dia[$m] = $dia++;
	        else
	          $matriz_dia[$m] = "" ;
	      }
       }else{
	     for($m=0;$m<7;$m++){
	       if($dia<=$ultimodia)
	         $matriz_dia[$m] = $dia++;
	       else
             $matriz_dia[$m] = "" ;
	     }
       }
       
       echo "</tr>\n";
       flush();
    		
     }
     
     echo "</table>\n";  

     echo "</fieldset></td>\n";

     if($mes==4 || $mes == 8 || $mes == 12){
       echo "</tr>\n";
       echo "</table>\n";  
     }
     
     
   }
   
   echo "</td>\n";
   echo "</tr>\n";
   echo "</table>\n";  
   
  
   $this->monta_layer();
   
  }

  function monta_calendario_semanal_turno ($exercicio=null, $metodo=null,$data=null){

    $this->clcalend = new cl_calend;
  	
    if( $exercicio == null ){
      $this->anousu   = date("Y",db_getsession("DB_datausu"));
      $this->mesatual = date("m",db_getsession("DB_datausu"));
      $this->diaatual = date("d",db_getsession("DB_datausu"));
    }else{
      $this->anousu   = substr($data,0,4);    	
      $this->mesatual = substr($data,5,2);
      $this->diaatual = substr($data,8,2);      
      if($metodo=='mais'){
      	
        $this->diaatual = substr($data,8,2)+1; 
        $datat = mktime(0,0,0,$this->mesatual,$this->diaatual,$this->anousu);
        
        if( $datat == 0){
        	
          $this->diaatual  = 1;
          $this->mesatual += 1;
          if($this->mesatual>12){
            $this->mesatual = 1;
            $this->anousu += 1;
          }
          
          $datat = mktime(0,0,0,$this->mesatual,$this->diaatual,$this->anousu);
        }
        
		    if (isset($datat) && !empty($datat)) {
		      
		      $this->diaatual = date('d', $datat);
		      $this->mesatual = date('m', $datat);
		    }

      }else if($metodo=='menos'){
      	
        $this->diaatual = substr($data,8,2)-1;      
        $datat = mktime(0,0,0,$this->mesatual,$this->diaatual,$this->anousu);
        while (date('w',$datat) != 1){
          $this->diaatual -= 1;
          if($this->diaatual<=0){
            $this->diaatual = 31;
      	    $this->mesatual -= 1;
            while ( mktime(0,0,0,$this->mesatual,$this->diaatual,$this->anousu) == 0){
              $this->diaatual -= 1;
            }
          }
          $datat = mktime(0,0,0,$this->mesatual,$this->diaatual,$this->anousu);
        }
    
      }

    }
    
    $dataini = mktime(0,0,0,$this->mesatual,$this->diaatual,$this->anousu);

    while (date('w',$dataini) != 1){
      $this->diaatual -= 1;
      if($this->diaatual<=0){
        $this->diaatual = 31;
      	$this->mesatual -= 1;
        while ( mktime(0,0,0,$this->mesatual,$this->diaatual,$this->anousu) == 0){
           $this->diaatual -= 1;
        }
      }
      $dataini = mktime(0,0,0,$this->mesatual,$this->diaatual,$this->anousu);
    }
    
    $dataini = date('Y-m-d',$dataini);
    

    $datafim = mktime(0,0,0,$this->mesatual,$this->diaatual,$this->anousu);

    while (date('w',$datafim) != 0){
      $this->diaatual += 1;
      while ( mktime(0,0,0,$this->mesatual,$this->diaatual,$this->anousu) == 0){
         $this->mesatual += 1;
         $this->diaatual = 1;
      }
      $datafim = mktime(0,0,0,$this->mesatual,$this->diaatual,$this->anousu);
    }
    
    $datafim = date('Y-m-d',$datafim);
    
    $this->diaatual = substr($dataini,8,2);
        
    if($this->sql_cruzamento != null){
      $this->sql_cruzamento = "select * from (".$this->sql_cruzamento.") as tab 
                               where dl_datacalend ";
      $this->sql_segundoacesso = "select * from (".$this->sql_segundoacesso.") as tab 
                               where dl_datacalend ";
      db_putsession("cruzamento",$this->sql_cruzamento);
      db_putsession("segundoacesso",$this->sql_segundoacesso);
    }else{
      db_putsession("cruzamento",'');
      db_putsession("segundoacesso",'');
    }


  
    echo "<table cellspacing=0 width='100%'>\n";  
    echo "<script>\n";
    echo "function js_mostra_alvo(data,identifica){
            js_OpenJanelaIframe('','db_iframe_calend','".$this->pagina_alvo."?data='+data+'&identifica='+identifica);
          }\n";
    echo "function js_mostra_text(liga,nomediv,evt){
		    evt = (evt)?evt:(window.event)?window.event:''; 
		    if(liga==true) {
		      document.getElementById(nomediv).style.top = 0; //evt.clientY;
		      document.getElementById(nomediv).style.left = 0; //(evt.clientX+20);
		      document.getElementById(nomediv).style.visibility = 'visible';
		    }else{
		      document.getElementById(nomediv).style.visibility = 'hidden';
	        }
          } " ;
    echo "</script>\n";

    echo "<tr>\n";
    echo "<td align=center> <a href='".$this->pagina_original."?metodo=menos&exercicio=".$this->anousu."&data=".$dataini."' >Anterior</a> &nbsp&nbsp Semana &nbsp&nbsp <a href='".$this->pagina_original."?metodo=mais&exercicio=".$this->anousu."&data=".$datafim."' >Próximo</a>\n";
    echo "</td >\n";
//    echo "<td ><a href='".$this->pagina_alvo_relatorio."?exercicio=".$this->anousu."' >Imprimir</a></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td >\n";
    
    
    $color='';
    
    echo "<table cellspacing=0 border=1 width='100%'>\n";  
    echo "<tr >\n";
 
    $matriz_dia = array("Segunda","Terça" ,"Quarta","Quinta","Sexta","Sábado","Domingo");
    
    echo "<td height=40 align='left' bgcolor='lightgreen' width='20%' >";
    echo "Clientes";

    echo "</td>\n";
    echo "<td height=40 align='left' bgcolor='lightgreen' width='10%' >";
    echo "Turno";

    echo "</td>\n";
    
    for($dia=$this->diaatual;$dia<=($this->diaatual+6);$dia++) {

      $datat = mktime(0,0,0,$this->mesatual,$dia,$this->anousu);
      if( $datat == 0){
        $this->diaatual = 1;
        $this->mesatual += 1;
        if($this->mesatual>12){
          $this->mesatual = 1;
        }
        $datat = mktime(0,0,0,$this->mesatual,$dia,$this->anousu);
      }else{
      	$datat = mktime(0,0,0,$this->mesatual,$dia,$this->anousu);
      }
      $cor = 'lightgreen';
      $sql = "select * 
              from calend
              where k13_data = '".date('Y-m-d',$datat)."'";
      $resultc = pg_query($sql);
      if( pg_numrows($resultc) > 0 ){
        $cor = 'lightblue';
      }
      if( date('w',$datat) == 0 ||
          date('w',$datat) == 6 ){
        $cor = 'green';
      }
      
      echo "<td height=40 align='center' bgcolor='$cor' width='10%' title='".($cor == 'lightgreen'?'Expediente Normal':($cor == 'green'?'Final de Semana':'Feriado'))."'>";
      $dian = date("d",$datat);
      echo "<strong>".$matriz_dia[key($matriz_dia)]."</strong><br>".$dian."/".date("m",$datat);
      if($cor=='lightblue'){
        echo '<br>Feriado';
      }
      echo "</td>\n";
      next($matriz_dia);
    }
    
    echo "</tr>\n";
    
    $result = $this->clcalend->sql_record($this->sql_cruzamento." between '$dataini' and '$datafim'");

    if($this->clcalend->numrows>0){

      $lin  = pg_fieldname($result,0);
      $col  = pg_fieldname($result,1);
      $qcol  = pg_fieldname($result,2);
      $val  = pg_fieldname($result,3);
      global $$lin,$$col,$$val,$$qcol;

    
      $matriz_imp = array();

      $chave = "";
      $chave_1 = "";
      for($i=0;$i<$this->clcalend->numrows;$i++){
        db_fieldsmemory($result,$i);
      
        $nova_linha  = false;
        $nova_coluna = false;
        $carac = "";
        $conteudo = substr($$val,0,5);
        if( isset($matriz_imp[$$lin][$$qcol][substr($$col,8,2)]) ){
          $carac = ", ";
          if(strpos("-".$matriz_imp[$$lin][$$qcol][substr($$col,8,2)],$conteudo) > 0 ){
          	$conteudo="";
          }
        }else{
          $matriz_imp[$$lin][$$qcol][substr($$col,8,2)] = "";
        }
        $matriz_imp[$$lin][$$qcol][substr($$col,8,2)] .= $carac.$conteudo;
      
      }
      for($i=0; $i < count($matriz_imp);$i++){
      
                
        echo "<tr>\n";
        echo "<td align='left' bgcolor='lightgreen' width='20%' rowspan='2' >";
        echo key($matriz_imp);
        echo "</td>";
        //       
        // manha
        //
        echo "<td align='left' bgcolor='lightgreen' width='10%' >";
        echo "Manhã";
        echo "</td>";
        for($c=$this->diaatual;$c<$this->diaatual+7;$c++){
          
          $datat = date("Y-m-d",mktime(0,0,0,$this->mesatual,$c,$this->anousu));
          
          $identifica = split("-",key($matriz_imp));
                   
          if( isset($matriz_imp[key($matriz_imp)]['m'][substr($datat,8,2)]) ){
          	$color = "red";
          }else{
          	$color = "";
          }
          
          if( isset($matriz_imp[key($matriz_imp)]['m'][substr($datat,8,2)]) ){
            echo "<td height=20 align='center' bgcolor='$color' width='10%' onclick='js_mostra_alvo(\"".$datat."\",\"".$identifica[0]."\")' >";
            echo $matriz_imp[key($matriz_imp)]['m'][substr($datat,8,2)];
          }else{
            echo "<td height=20 align='center' bgcolor='$color' width='10%'  >";
            echo "&nbsp";
          }
          echo "</td>";
          
        }     
        //
        // tarde
        //
        echo "</tr>\n";
        echo "<tr>\n";
        echo "<td align='left' bgcolor='lightgreen' width='10%' >";
        echo "Tarde";
        echo "</td>";
        for($c=$this->diaatual;$c<$this->diaatual+7;$c++){
          
          $datat = date("Y-m-d",mktime(0,0,0,$this->mesatual,$c,$this->anousu));
          
          $identifica = split("-",key($matriz_imp));
                   
          if( isset($matriz_imp[key($matriz_imp)]["t"][substr($datat,8,2)]) ){
          	$color = "red";
          }else{
          	$color = "";
          }
          
          if( isset($matriz_imp[key($matriz_imp)]['t'][substr($datat,8,2)]) ){
            echo "<td height=20 align='center' bgcolor='$color' width='10%' onclick='js_mostra_alvo(\"".$datat."\",\"".$identifica[0]."\")' >";
            echo $matriz_imp[key($matriz_imp)]['t'][substr($datat,8,2)];
          }else{
            echo "<td height=20 align='center' bgcolor='$color' width='10%'  >";
            echo "&nbsp";
          }
          echo "</td>";
          
        }
      
        echo "</tr>\n";
        next($matriz_imp);




      }
  
    }  
    echo "</table>\n";  

    echo "</td>\n";

    echo "</tr>\n";
    echo "</table>\n";  
     
    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";  
   
  
    $this->monta_layer();
   
  }
  function monta_calendario_semanal ($exercicio=null, $metodo=null,$data=null){

    $this->clcalend = new cl_calend;
  	
    if( $exercicio == null ){
      $this->anousu   = date("Y",db_getsession("DB_datausu"));
      $this->mesatual = date("m",db_getsession("DB_datausu"));
      $this->diaatual = date("d",db_getsession("DB_datausu"));

    }else{
      $this->anousu   = substr($data,0,4);    	
      $this->mesatual = substr($data,5,2);
      
      if($metodo=='mais'){
        $this->diaatual = substr($data,8,2)+1;      
        $datat = mktime(0,0,0,$this->mesatual,$this->diaatual,$this->anousu);
      	
        if( $datat == 0){
          $this->diaatual  = 1;
          $this->mesatual += 1;
          if($this->mesatual>12){
            $this->mesatual = 1;
            $this->anousu += 1;
          }
          $datat = mktime(0,0,0,$this->mesatual,$this->diaatual,$this->anousu);
        }else{
      	  $datat = mktime(0,0,0,$this->mesatual,$this->diaatual,$this->anousu);
        }

      }else if($metodo=='menos'){
      	
        $this->diaatual = substr($data,8,2)-1;      
        $datat = mktime(0,0,0,$this->mesatual,$this->diaatual,$this->anousu);
        while (date('w',$datat) != 1){
          $this->diaatual -= 1;
          if($this->diaatual<=0){
            $this->diaatual = 31;
      	    $this->mesatual -= 1;
            while ( mktime(0,0,0,$this->mesatual,$this->diaatual,$this->anousu) == 0){
              $this->diaatual -= 1;
            }
          }
          $datat = mktime(0,0,0,$this->mesatual,$this->diaatual,$this->anousu);
        }
    
      }

    }
    
    $dataini = mktime(0,0,0,$this->mesatual,$this->diaatual,$this->anousu);

    while (date('w',$dataini) != 1){
      $this->diaatual -= 1;
      if($this->diaatual<=0){
        $this->diaatual = 31;
      	$this->mesatual -= 1;
        while ( mktime(0,0,0,$this->mesatual,$this->diaatual,$this->anousu) == 0){
           $this->diaatual -= 1;
        }
      }
      $dataini = mktime(0,0,0,$this->mesatual,$this->diaatual,$this->anousu);
    }
    
    $dataini = date('Y-m-d',$dataini);
    

    $datafim = mktime(0,0,0,$this->mesatual,$this->diaatual,$this->anousu);

    while (date('w',$datafim) != 0){
      $this->diaatual += 1;
      while ( mktime(0,0,0,$this->mesatual,$this->diaatual,$this->anousu) == 0){
         $this->mesatual += 1;
         $this->diaatual = 1;
      }
      $datafim = mktime(0,0,0,$this->mesatual,$this->diaatual,$this->anousu);
    }
    
    $datafim = date('Y-m-d',$datafim);
    
    $this->diaatual = substr($dataini,8,2);
        
    if($this->sql_cruzamento != null){
      $this->sql_cruzamento = "select * from (".$this->sql_cruzamento.") as tab 
                               where dl_datacalend ";
      $this->sql_segundoacesso = "select * from (".$this->sql_segundoacesso.") as tab 
                               where dl_datacalend ";
      db_putsession("cruzamento",$this->sql_cruzamento);
      db_putsession("segundoacesso",$this->sql_segundoacesso);
    }else{
      db_putsession("cruzamento",'');
      db_putsession("segundoacesso",'');
    }


  
    echo "<table cellspacing=0 width='100%'>\n";  
    echo "<script>\n";
    echo "function js_mostra_alvo(data,identifica){
            js_OpenJanelaIframe('','db_iframe_calend','".$this->pagina_alvo."?data='+data+'&identifica='+identifica);
          }\n";
    echo "function js_mostra_text(liga,nomediv,evt){
		    evt = (evt)?evt:(window.event)?window.event:''; 
		    if(liga==true) {
		      document.getElementById(nomediv).style.top = 0; //evt.clientY;
		      document.getElementById(nomediv).style.left = 0; //(evt.clientX+20);
		      document.getElementById(nomediv).style.visibility = 'visible';
		    }else{
		      document.getElementById(nomediv).style.visibility = 'hidden';
	        }
          } " ;
    echo "</script>\n";

    echo "<tr>\n";
    echo "<td align=center> <a href='".$this->pagina_original."?metodo=menos&exercicio=".$this->anousu."&data=".$dataini."' >Anterior</a> &nbsp&nbsp Semana &nbsp&nbsp <a href='".$this->pagina_original."?metodo=mais&exercicio=".$this->anousu."&data=".$datafim."' >Próximo</a>\n";
    echo "</td >\n";
//    echo "<td ><a href='".$this->pagina_alvo_relatorio."?exercicio=".$this->anousu."' >Imprimir</a></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td >\n";
    
    
    $color='';
    
    echo "<table cellspacing=0 border=1 width='100%'>\n";  
    echo "<tr >\n";
 
    $matriz_dia = array("Segunda","Terça" ,"Quarta","Quinta","Sexta","Sábado","Domingo");
    
    echo "<td height=40 align='left' bgcolor='lightgreen' width='30%' >";
    echo "Clientes";

    echo "</td>\n";
    
    for($dia=$this->diaatual;$dia<=($this->diaatual+6);$dia++) {
      
      echo "<td height=40 align='center' bgcolor='lightgreen' width='10%' >";
      $datat = mktime(0,0,0,$this->mesatual,$dia,$this->anousu);
      if( $datat == 0){
        $this->diaatual = 1;
        $this->mesatual += 1;
        if($this->mesatual>12){
          $this->mesatual = 1;
        }
        $datat = mktime(0,0,0,$this->mesatual,$dia,$this->anousu);
      }else{
      	$datat = mktime(0,0,0,$this->mesatual,$dia,$this->anousu);
      }
      $dian = date("d",$datat);
      echo "<strong>".$matriz_dia[key($matriz_dia)]."</strong><br>".$dian."/".date("m",$datat);
      echo "</td>\n";
      next($matriz_dia);
    }
    
    echo "</tr>\n";
    
    $result = $this->clcalend->sql_record($this->sql_cruzamento." between '$dataini' and '$datafim'");

    if($this->clcalend->numrows>0){

      $lin  = pg_fieldname($result,0);
      $col  = pg_fieldname($result,1);
      $val  = pg_fieldname($result,2);
      global $$lin,$$col,$$val;

    
      $matriz_imp = array();

      $chave = "";
      $chave_1 = "";
      for($i=0;$i<$this->clcalend->numrows;$i++){
        db_fieldsmemory($result,$i);
      
        $nova_linha  = false;
        $nova_coluna = false;
        $carac = "";
        $conteudo = substr($$val,0,5);
        if( isset($matriz_imp[$$lin][substr($$col,8,2)]) ){
          $carac = ", ";
          if(strpos("-".$matriz_imp[$$lin][substr($$col,8,2)],$conteudo) > 0 ){
          	$conteudo="";
          }
        }else{
          $matriz_imp[$$lin][substr($$col,8,2)] = "";
        }
        $matriz_imp[$$lin][substr($$col,8,2)] .= $carac.$conteudo;
      
      }
      for($i=0; $i < count($matriz_imp);$i++){
      
        echo "<tr>\n";
        echo "<td align='left' bgcolor='lightgreen' width='30%' >";
        echo key($matriz_imp);
        echo "</td>";
      
        for($c=$this->diaatual;$c<$this->diaatual+7;$c++){
          
          $datat = date("Y-m-d",mktime(0,0,0,$this->mesatual,$c,$this->anousu));
          
          $identifica = split("-",key($matriz_imp));
                   
          if( isset($matriz_imp[key($matriz_imp)][substr($datat,8,2)]) ){
          	$color = "red";
          }else{
          	$color = "";
          }
          
          if( isset($matriz_imp[key($matriz_imp)][substr($datat,8,2)]) ){
            echo "<td height=40 align='left' bgcolor='$color' width='10%' onclick='js_mostra_alvo(\"".$datat."\",\"".$identifica[0]."\")' >";
            echo $matriz_imp[key($matriz_imp)][substr($datat,8,2)];
          }else{
            echo "<td height=40 align='left' bgcolor='$color' width='10%'  >";
            echo "&nbsp";
          }
          echo "</td>";
        }     
      
        echo "</tr>\n";
        next($matriz_imp);


      }
  
    }  
    echo "</table>\n";  

    echo "</td>\n";

    echo "</tr>\n";
    echo "</table>\n";  
     
    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";  
   
  
    $this->monta_layer();
   
  }


  function monta_calendario_anual ($exercicio=null, $metodo=null,$data=null){

    $this->clcalend = new cl_calend;
  	
    if( 1 == 2 && $exercicio == null ){
      
      $this->anousu   = date("Y",db_getsession("DB_datausu"));
      $this->mesatual = date("m",db_getsession("DB_datausu"));
      $this->diaatual = date("d",db_getsession("DB_datausu"));

    }else{
      $this->anousu   = substr($data,0,4);    	
      $this->mesatual = substr($data,5,2);
      $this->diaatual = substr($data,8,2);
      
      if($metodo=='mais'){
        $this->diaatual = substr($data,8,2)+1;      
        $datat = mktime(0,0,0,$this->mesatual,$ths->diaatual,$this->anousu);
      	
        if( $datat == 0){
          $this->diaatual  = 1;
          $this->mesatual += 1;
          if($this->mesatual>12){
            $this->mesatual = 1;
            $this->anousu += 1;
          }
          $datat = mktime(0,0,0,$this->mesatual,$dia,$this->anousu);
        }else{
      	  $datat = mktime(0,0,0,$this->mesatual,$dia,$this->anousu);
        }

      }else if($metodo=='menos'){
      	
        $this->diaatual = substr($data,8,2)-1;      
        $datat = mktime(0,0,0,$this->mesatual,$ths->diaatual,$this->anousu);
        while (date('w',$datat) != 1){
          $this->diaatual -= 1;
          if($this->diaatual<=0){
            $this->diaatual = 31;
      	    $this->mesatual -= 1;
            while ( mktime(0,0,0,$this->mesatual,$this->diaatual,$this->anousu) == 0){
              $this->diaatual -= 1;
            }
          }
          $datat = mktime(0,0,0,$this->mesatual,$this->diaatual,$this->anousu);
        }
    
      }

    }
    
    if($this->sql_cruzamento != null){
      $this->sql_cruzamento = "select * from (".$this->sql_cruzamento.") as tab 
                               where dl_datacalend ";
      $this->sql_segundoacesso = "select * from (".$this->sql_segundoacesso.") as tab 
                               where dl_datacalend ";
      db_putsession("cruzamento",$this->sql_cruzamento);
      db_putsession("segundoacesso",$this->sql_segundoacesso);
    }else{
      db_putsession("cruzamento",'');
      db_putsession("segundoacesso",'');
    }


  
    echo "<table cellspacing=0 width='100%'>\n";  
    echo "<script>\n";
    echo "function js_mostra_alvo(data,identifica){
            js_OpenJanelaIframe('','db_iframe_calend','".$this->pagina_alvo."?data='+data+'&identifica='+identifica);
          }\n";
    echo "function js_mostra_text(liga,nomediv,evt){
		    evt = (evt)?evt:(window.event)?window.event:''; 
		    if(liga==true) {
		      document.getElementById(nomediv).style.top = 0; //evt.clientY;
		      document.getElementById(nomediv).style.left = 0; //(evt.clientX+20);
		      document.getElementById(nomediv).style.visibility = 'visible';
		    }else{
		      document.getElementById(nomediv).style.visibility = 'hidden';
	        }
          } " ;
    echo "</script>\n";

    echo "<tr>\n";
    echo "<td >\n";
    
    
    $color='';
    
    echo "<table cellspacing=0 border=1 width='100%'>\n";  
    echo "<tr >\n";
 
    $matriz_mes = array("01"=>"Janeiro","02"=>"Fevereiro","03"=>"Março","04"=>"Abril","05"=>"Maio","06"=>"Junho",
                        "07"=>"Julho","08"=>"Agosto","09"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro");
    
    echo "<td height=40 align='left' bgcolor='lightgreen' width='30%' >";
    echo "Módulos";

    echo "</td>\n";
    
    for($mes=$this->mesatual;$mes<13;$mes++) {
      echo "<td height=10 align='center' bgcolor='lightgreen' title=".$matriz_mes[db_formatar($mes,'s','0',2)]." >";
      echo "<strong>".substr($matriz_mes[db_formatar($mes,'s','0',2)],0,3)."</strong>";
      echo "</td>\n";
    }
    
    echo "</tr>\n";
    
    $result = $this->clcalend->sql_record($this->sql_cruzamento." between '$exercicio-01-01' and '$exercicio-12-31'");

    if($this->clcalend->numrows>0){

      $lin  = pg_fieldname($result,0);
      $col  = pg_fieldname($result,1);
      $val  = pg_fieldname($result,2);
      global $$lin,$$col,$$val;

    
      $matriz_imp = array();

      $chave = "";
      $chave_1 = "";
      for($i=0;$i<$this->clcalend->numrows;$i++){
        db_fieldsmemory($result,$i);
      
        $nova_linha  = false;
        $nova_coluna = false;
        $carac = "";
        $conteudo = substr($$val,0,1);
        if( isset($matriz_imp[$$lin][substr($$col,5,2)]) ){
          $carac = ",";
          if(strpos("-".$matriz_imp[$$lin][substr($$col,5,2)],$conteudo) > 0 ){
          	$conteudo="";
          }
        }else{
          $matriz_imp[$$lin][substr($$col,5,2)] = "";
        }
        $matriz_imp[$$lin][substr($$col,5,2)] .= $carac.$conteudo;
      
      }
      for($i=0; $i < count($matriz_imp);$i++){
      
        echo "<tr>\n";
        echo "<td align='left' bgcolor='lightgreen' >";
        echo key($matriz_imp);
        echo "</td>";
      
        for($c=1;$c<13;$c++){
          
          if( isset($matriz_imp[key($matriz_imp)][db_formatar($c,'s','0',2)]) ){
          	$color = "red";
          }else{
          	$color = "";
          }
          
          
          
          if( isset($matriz_imp[key($matriz_imp)][db_formatar($c,'s','0',2)]) ){
            echo "<td height=10 align='left' bgcolor='$color' onclick='js_mostra_alvo(\"".db_formatar($c,'s','0',2)."\",\"".$metodo.'-'.key($matriz_imp)."\")' >";
            echo $matriz_imp[key($matriz_imp)][db_formatar($c,'s','0',2)];
          }else{
            echo "<td height=10 align='left' bgcolor='$color' >";
            echo "&nbsp";
          }
          echo "</td>";
        }     
      
        echo "</tr>\n";
        next($matriz_imp);


      }
  
    }  
    echo "</table>\n";  

    echo "</td>\n";

    echo "</tr>\n";
    echo "</table>\n";  
     
    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";  
   
  
    $this->monta_layer();
   
  }
  
  function monta_calendario_anual_semana ($exercicio=null, $metodo=null,$data=null){

    $this->clcalend = new cl_calend;
  	
    if( 1 == 2 && $exercicio == null ){
      
      $this->anousu   = date("Y",db_getsession("DB_datausu"));
      $this->mesatual = date("m",db_getsession("DB_datausu"));
      $this->diaatual = date("d",db_getsession("DB_datausu"));

    }else{
      $this->anousu   = substr($data,0,4);    	
      $this->mesatual = substr($data,5,2);
      $this->diaatual = substr($data,8,2);
      
      if($metodo=='mais'){
        $this->diaatual = substr($data,8,2)+1;      
        $datat = mktime(0,0,0,$this->mesatual,$ths->diaatual,$this->anousu);
      	
        if( $datat == 0){
          $this->diaatual  = 1;
          $this->mesatual += 1;
          if($this->mesatual>12){
            $this->mesatual = 1;
            $this->anousu += 1;
          }
          $datat = mktime(0,0,0,$this->mesatual,$dia,$this->anousu);
        }else{
      	  $datat = mktime(0,0,0,$this->mesatual,$dia,$this->anousu);
        }

      }else if($metodo=='menos'){
      	
        $this->diaatual = substr($data,8,2)-1;      
        $datat = mktime(0,0,0,$this->mesatual,$ths->diaatual,$this->anousu);
        while (date('w',$datat) != 1){
          $this->diaatual -= 1;
          if($this->diaatual<=0){
            $this->diaatual = 31;
      	    $this->mesatual -= 1;
            while ( mktime(0,0,0,$this->mesatual,$this->diaatual,$this->anousu) == 0){
              $this->diaatual -= 1;
            }
          }
          $datat = mktime(0,0,0,$this->mesatual,$this->diaatual,$this->anousu);
        }
    
      }

    }
    
    if($this->sql_cruzamento != null){
      $this->sql_cruzamento = "select * from (".$this->sql_cruzamento.") as tab 
                               where dl_datacalend ";
      $this->sql_segundoacesso = "select * from (".$this->sql_segundoacesso.") as tab 
                               where dl_datacalend ";
      db_putsession("cruzamento",$this->sql_cruzamento);
      db_putsession("segundoacesso",$this->sql_segundoacesso);
    }else{
      db_putsession("cruzamento",'');
      db_putsession("segundoacesso",'');
    }
  
    echo "<table cellspacing=0 width='100%'>\n";  
    echo "<script>\n";
    echo "function js_mostra_alvo(data,identifica){
            js_OpenJanelaIframe('','db_iframe_calend','".$this->pagina_alvo."?data='+data+'&identifica='+identifica);
          }\n";
    echo "function js_mostra_text(liga,nomediv,evt){
		    evt = (evt)?evt:(window.event)?window.event:''; 
		    if(liga==true) {
		      document.getElementById(nomediv).style.top = 0; //evt.clientY;
		      document.getElementById(nomediv).style.left = 0; //(evt.clientX+20);
		      document.getElementById(nomediv).style.visibility = 'visible';
		    }else{
		      document.getElementById(nomediv).style.visibility = 'hidden';
	        }
          } " ;
    echo "</script>\n";

    echo "<tr>\n";
    echo "<td >\n";
    
    
    $color='';
    
    echo "<table cellspacing=0 border=1 width='100%'>\n";  
    echo "<tr >\n";
 
    $matriz_mes = array("01"=>"Janeiro","02"=>"Fevereiro","03"=>"Março","04"=>"Abril","05"=>"Maio","06"=>"Junho",
                        "07"=>"Julho","08"=>"Agosto","09"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro");
    
    echo "<td height=40 align='left' bgcolor='lightgreen' width='10%' rowspan='2' nowrap>";
    echo $this->titulo_primeira_coluna_anual_sem;

    echo "</td>\n";
    
    /*for($mes=1;$mes<13;$mes++) {
      //echo "<td height=10 align='center' bgcolor='lightgreen' title=".$matriz_mes[db_formatar($mes,'s','0',2)]." >";
      //echo "<strong>".substr($matriz_mes[db_formatar($mes,'s','0',2)],0,3)."</strong>";
      //echo "</td>\n";
      echo "<td height=10 align='center' bgcolor='lightgreen'  >$mes";
      echo "</td>\n";

    }*/
    $qsemana = array();
    for($mes=$this->mesatual;$mes< 13;$mes ++){
    	
      //echo "<td height=10 align='center' bgcolor='lightgreen' title=".$matriz_mes[db_formatar($mes,'s','0',2)]." >";
      //echo "<strong>".substr($matriz_mes[db_formatar($mes,'s','0',2)],0,3)."</strong>";
      //echo "</td>\n";
      $sem = date('W',mktime(0,0,0,$mes,1,2007));
      $qt = 0;
      for($dia=1;$dia < (date('t',mktime(0,0,0,$mes,1,$this->anousu))+1);$dia++){
        $qsem = date('W',mktime(0,0,0,$mes,$dia,2007));
        if($qsem != $sem || $dia == 1){
          $sem = $qsem;
          if(!isset($qsemana[$sem])){
            $qsemana[$sem] = $sem;
            $qt += 1;
          }
        }
      }
      echo "<td height=10 align='center' bgcolor='lightgreen' colspan='$qt' nowrap>".db_mes($mes);
      echo "</td>\n";
    
    }

    echo "</tr>\n";
    echo "<tr>\n";
    $qsemana = array();
    for($mes=$this->mesatual;$mes< 13;$mes ++){
    	
      //echo "<td height=10 align='center' bgcolor='lightgreen' title=".$matriz_mes[db_formatar($mes,'s','0',2)]." >";
      //echo "<strong>".substr($matriz_mes[db_formatar($mes,'s','0',2)],0,3)."</strong>";
      //echo "</td>\n";
      $sem = date('W',mktime(0,0,0,$mes,1,$this->anousu));
      $qq = 0;
      for($dia=1;$dia < (date('t',mktime(0,0,0,$mes,1,$this->anousu))+1);$dia++){
        $qsem = date('W',mktime(0,0,0,$mes,$dia,$this->anousu));
        if($qsem != $sem || $dia == 1){
           $sem = $qsem;  	
           if(!isset($qsemana[$sem])){
             $qsemana[$sem] = $sem;
             $qq+= 1;
             echo "<td height=10 align='center' bgcolor='lightgreen' title='$sem' nowrap>$qq";
             echo "</td>\n";
           }
        }
      }
    
    }
    
    echo "</tr>\n";
    
    $result = $this->clcalend->sql_record($this->sql_cruzamento." is not null");

    if($this->clcalend->numrows>0){

      $lin  = pg_fieldname($result,0);
      $col  = pg_fieldname($result,1);
      $val  = pg_fieldname($result,2);
      global $$lin,$$col,$$val;

    
      $matriz_imp = array();

      $chave = "";
      $chave_1 = "";
      for($i=0;$i<$this->clcalend->numrows;$i++){
        db_fieldsmemory($result,$i);
      
        $nova_linha  = false;
        $nova_coluna = false;
        $carac = "";
        $conteudo = substr($$val,0,1);
        if( isset($matriz_imp[$$lin][$$col]) ){
          $carac = ",";
          if(strpos("-".$matriz_imp[$$lin][$$col],$conteudo) > 0 ){
          	$conteudo="";
          }
        }else{
          $matriz_imp[$$lin][$$col] = "";
        }
        $matriz_imp[$$lin][$$col] .= $carac.$conteudo;
      
      }
      $qq++;
      for($i=0; $i < count($matriz_imp);$i++){
      
        echo "<tr>\n";
        echo "<td align='left' bgcolor='lightgreen' nowrap>";
        echo key($matriz_imp);
        echo "</td>";
      
        for($c=1;$c<53;$c++){
          
          if ( isset($qsemana[$c]) ) {
            if( isset($matriz_imp[key($matriz_imp)][db_formatar($c,'s','0',2)]) ){
          	$color = "red";
            }else{
          	$color = "";
            }
          
          
          
            if( isset($matriz_imp[key($matriz_imp)][db_formatar($c,'s','0',2)]) ){
              echo "<td nowrap height=10 align='left' bgcolor='$color' onclick='js_mostra_alvo(\"".db_formatar($c,'s','0',2)."\",\"".$metodo.'-'.key($matriz_imp)."\")' title=\"".$matriz_imp[key($matriz_imp)][db_formatar($c,'s','0',2)]."\" >";
              echo "";
            }else{
              echo "<td nowrap height=10 align='left' bgcolor='$color' >";
              echo "&nbsp";
            }
            echo "</td>";
          }else{
            echo "<td nowrap height=10 align='left' bgcolor='$color' >&nbsp</td>";
          }
        }     
      
        echo "</tr>\n";
        next($matriz_imp);


      }
  
    }  
    echo "</table>\n";  

    echo "</td>\n";

    echo "</tr>\n";
    echo "</table>\n";  
     
    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";  
   
  
    $this->monta_layer();
   
  }

  function monta_layer(){
  	 

  	 reset($this->matriz_layer);
  	 
  	 for($ml=0;$ml<count($this->matriz_layer);$ml++){
  	
  	   $data = key($this->matriz_layer);
  	   
       echo "<div id='div_calend_".$data."' style='position:absolute;left:10px; top:10px; visibility:hidden ; background-color:#6699CC ; border:2px outset #cccccc; align:left'>
             <table>
            ";
       for($md=0;$md<pg_numrows($this->matriz_layer[key($this->matriz_layer)]);$md++){
       	  echo "
	         <tr>
	         <td align='left'>
             <font color='black' face='arial' size='2'><strong>Data:</strong>:</font><br>
	         <font color='black' face='arial' size='1'>".pg_result($this->matriz_layer[key($this->matriz_layer)],$md,0)."</font>
	         </td>
		     </tr>";
       }
		     
	   echo "</table>
             </div>";   	
       next($this->matriz_layer);

  	 }
  	
  }


  function monta_javascript($funcao,$param=null,$conteudo){
  	$this->javascript .= "function $funcao ($param){ $conteudo } \n";
  }

  function monta_inicio_pagina($monta_menu){
  	
  	echo "<html>\n";
    echo "<head>\n";
    echo "<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>\n";
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
    echo "<meta http-equiv=\"Expires\" CONTENT=\"0\">\n";
    echo "<script language=\"JavaScript\" type=\"text/javascript\" src=\"scripts/scripts.js\"></script>\n";
    echo "<link href=\"estilos.css\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<script>".$this->javascript."</script>";        
    echo "</head>\n";
    echo "<body align=\"center\" bgcolor=#CCCCCC leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" onLoad=\"a=1\" >\n";

    if($monta_menu==true){
      $this->monta_menu = true;
      echo '<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">';
      echo '<tr>';
      echo '<td width="360" height="18">&nbsp;</td>';
      echo '<td width="263">&nbsp;</td>';
      echo '<td width="25">&nbsp;</td>';
      echo '<td width="140">&nbsp;</td>';
      echo '</tr>';
      echo '</table>';
    }
  	
  }

  function monta_fim_pagina(){
    if($this->monta_menu==true){
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    }
    echo "</body>";
    echo "</html>";

  }

}