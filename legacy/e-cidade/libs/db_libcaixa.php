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

//MODULO: configuracoes
//CLASSE PARA AUTENTICAR IMPRESSORA
class cl_autenticar { 
   var $tipoimp   = 'bematech';  
   var $erro_msg  = 'ok';
   var $erro      = false;
   var $testando  = false;
   var $transacao = '0';
   var $ip        = null;
   var $data_dia  = null;
   var $data_mes  = null;
   var $data_ano  = null;
   
   function cancelar($msg){
	      switch($this->transacao) {
		case SEM_PAPEL:
		  $this->erro_msg  = 'Impressora sem papel!';
		  break;
		case OFFLINE:
		  $this->erro_msg  = 'Impressora OffLine!';
		  break;
		case ERRO:
		  $this->erro_msg  = 'Ocorreu um erro indeterminado!';
		  break;
		default:  
                   $this->erro_msg = $msg;
	      }							  
      	      
      $this->fechar();
      $this->erro=true;
   }
   function cabecalho (){
      $sql = "select nomeinst,cgc from  db_config where codigo= ".db_getsession('DB_instit');
      $result = pg_query($sql);
      global $nomeinst,$cgc;
      db_fieldsmemory($result,0);
     $this->condensado(true);
     $this->imprimir_ln("------------------------------------------------------------");
     $this->condensado(false);
     $this->negrito(true);
     $this->imprimir_ln($nomeinst);  
     $this->negrito(false);
     $this->imprimir_ln("CNPJ: $cgc");
     if($this->data_dia!=null && $this->data_mes!=null && $this->data_ano!=null){
       $this->imprimir_ln("Data :".$this->data_dia."/".$this->data_mes."/".$this->data_ano."  Hora:".db_hora());
     }else{
       $this->imprimir_ln("Data :".date("d/m/Y",db_getsession("DB_datausu"))."  Hora:".db_hora());
     }
     $this->condensado(true);
     $this->imprimir_ln("------------------------------------------------------------");
      return true;
   }
   function erro_imp(){
     $this->erro_msg = "Tipo de impressora não definida.";
     $this->erro=true;
   }

   //método para verificar se a impressora esta ok
     function verifica($ip,$porta){ 
       if($this->tipoimp=='bematech'){
	   $this->conectar($ip,$porta);
	   if($this->erro==true){
	      return false;
           }
 	   $this->transacao = im_verifica();
	   if($this->transacao!=-1){
	     $this->cancelar($this->transacao);
	     return false;
	   }else{ 	 
	       $sql     = "select k11_id from cfautent where k11_instit = " . db_getsession('DB_instit') . " and k11_ipterm = '$ip'"; 
	       $result  = pg_query($sql);
	       $numrows = pg_numrows($result);
	       if($numrows<1){
		   $this->cancelar("IP ".db_getsession('DB_ip')." não autorizado! ");
		   return false;
	       }else{
                  $this->fechar();	     
	           return true;
	       }
	   }    
       }else{
 	  $this->erro_imp();
          return false;
       }
     }

   /*método que verifica se não é a primeira impressão do dia*/
   function verifica_sessao(){
       $this->testando=true;
       global $k11_id;
       $sql    = "select k11_id from cfautent where k11_instit = " . db_getsession('DB_instit') . " and k11_ipterm ='".$this->ip."'"; 
       $result = pg_query($sql);
       db_fieldsmemory($result,0);
       if(empty($HTTP_SESSION_VARS['autenticando'])){
	  $sql = "select 0 from corrente where k12_instit = " . db_getsession('DB_instit') . " and k12_id = $k11_id and k12_data ='".date("Y-m-d",db_getsession('DB_datausu'))."' limit 2";
	  $result  = pg_query($sql);
	  $numrows = pg_numrows($result);
	  if($numrows==1){
	    db_putsession('autenticando',true);
	    $this->cabecalho();
	  }
          $this->testando=false;
           return true;
       }else{
	    echo 'existe autenticando';
            return true;
        }	
   }
   
   //método para conectar
   function conectar($ip,$porta){ 
    /*rotina que verifica se ocorreu algum erro*/ 
     if($this->tipoimp=='bematech'){
	 $this->transacao = im_conectar($ip,$porta);
         if($this->transacao == 1){
	   $this->ip = $ip;
	   return true;
	 }else{
	   $this->cancelar("Erro ao conectar! Verifique se a impressora esta instalada corretamente.");
	   return false;
	 } 	 
       }else{
	   $this->erro_imp();
	   return false;
       }
     }
     //método para fechar
     function fechar(){ 
    //     db_msgbox('fechar');
      /*rotina que verifica se ocorreu algum erro*/ 
       /*fim*/
       if($this->tipoimp=='bematech'){
	 $this->transacao = im_fechar();
	 if($this->transacao == 0){
	   return true;
	 }else{
	   $this->cancelar("Erro ao fechar.");
	   return false;
	 } 	 
       }else{
	   $this->erro_imp();
	   return false;
	}
     }
     
     //método para resetar
     function resetar(){ 
      /*rotina que verifica se ocorreu algum erro*/ 
       if($this->erro==true){
	 return false;
       }
       /*fim*/

       if($this->tipoimp=='bematech'){
	 $this->transacao= im_reset();
	 if($this->transacao==0){
	   return true;
	 }else{
	   $this->cancelar("Erro ao resetar.");
	   return false;
	 } 	 
       }else{
	  $this->erro_imp();
	  return false;
       }
     }
        //método para autenticar
   function autenticar($texto){ 
     if($this->erro==true){
       return false;
     }
     if($this->tipoimp=='bematech'){
       $this->transacao = im_autenticar("$texto");
       if($this->transacao==0){
         return true;
       }else{
	 $this->cancelar("Erro ao autenticar.");
	 return false;
       } 	 
     }else{
 	$this->erro_imp();
        return false;
     }
   }
   //método para imprimir
   function imprimir($texto){ 
     if($this->erro==true){
       return false;
     }
     if($this->testando==false){
       $this->verifica_sessao();
     }
     if($this->tipoimp == 'bematech'){
       $this->transacao = im_imp("$texto");
       if($this->transacao == 0) {
         return true;
       }else{
	 $this->cancelar("Erro ao imprimir.");
	 return false;
       } 	 
     }else{
 	$this->erro_imp();
        return false;
     }
   }
 
   //método para imprimir com um \n
   function imprimir_ln($texto){ 
     if($this->erro==true){
       return false;
     }
     if($this->testando==false){
       $this->verifica_sessao();
     }
     if($this->tipoimp == 'bematech'){
       $this->transacao = im_impln("$texto");
       if($this->transacao == 0) {
         return true;
       }else{
	 $this->cancelar("Erro ao imprimir com ln.");
	 return false;
       } 	 
     }else{
 	$this->erro_imp();
        return false;
     }
   }

   //método para colocar sublinhado
   function sublinhado($sublinhado=false){ 
     if($this->erro==true){
       return false;
     }
     if($this->tipoimp == 'bematech'){
       $this->transacao = im_sublinhado($sublinhado);
       if($this->transacao == 0) {
         return true;
       }else{
	 $this->cancelar("Erro no metódo sublinhado.");
	 return false;
       } 	 
     }else{
 	$this->erro_imp();
        return false;
     }
   }

   //método para colocar em negrito
   function negrito($negrito=false){ 
     if($this->erro==true){
       return false;
     }
     if($this->tipoimp == 'bematech'){
       $this->transacao = im_negrito($negrito);
       if($this->transacao == 0) {
         return true;
       }else{
	 $this->cancelar("Erro no método negrito.");
	 return false;
       } 	 
     }else{
 	$this->erro_imp();
        return false;
     }
   }

   //método para colocar em italico
   function italico($italico=false){ 
     if($this->erro==true){
       return false;
     }
     if($this->tipoimp == 'bematech'){
       $this->transacao = im_italico($italico);
       if($this->transacao == 0) {
         return true;
       }else{
	 $this->cancelar("Erro no método itálico.");
	 return false;
       } 	 
     }else{
 	$this->erro_imp();
        return false;
     }
   }
   //método para colocar expandido
   function expandido($expandido=false){ 
     if($this->erro==true){
       return false;
     }
     if($this->tipoimp == 'bematech'){
       $this->transacao = im_expandido($expandido);
       if($this->transacao == 0) {
         return true;
       }else{
	 $this->cancelar("Erro no método expandido.");
	 return false;
       } 	 
     }else{
 	$this->erro_imp();
        return false;
     }
   }
   
   //método para colocar condensado
   function condensado($condensado=false){ 
     if($this->erro==true){
       return false;
     }
     if($this->tipoimp == 'bematech'){
       $this->transacao = im_condensado($condensado);
       if($this->transacao == 0) {
         return true;
       }else{
	 $this->cancelar("Erro no método condesado.");
	 return false;
       } 	 
     }else{
 	$this->erro_imp();
        return false;
     }
   }

   //método para fonte normal
   function fonte_normal(){ 
     if($this->erro==true){
       return false;
     }
     if($this->tipoimp == 'bematech'){
       $this->transacao = im_fonteNormal();
       if($this->transacao == 0) {
         return true;
       }else{
	 $this->cancelar("Erro no método fonteNormal.");
	 return false;
       } 	 
     }else{
 	$this->erro_imp();
        return false;
     }
   }

   //método para fonte elite
   function fonte_elite(){ 
     if($this->erro==true){
       return false;
     }
     if($this->tipoimp == 'bematech'){
       $this->transacao = im_fonteElite();
       if($this->transacao == 0) {
         return true;
       }else{
	 $this->cancelar("Erro no método fonteElite.");
	 return false;
       } 	 
     }else{
 	$this->erro_imp();
        return false;
     }
   }
}

function db_imprimecheque ($nome, $codbco, $valor, $data, $modelo = 1, $ip_imprime, $porta,$municipio ){
 /*   
    echo " 
<br> nome = $nome
<br> codbco = $codbco
<br> valor = $valor
<br> data = $data
<br> modelo = $modelo
<br> ip_imprime = $ip_imprime
<br> porta =$porta
<br> municipio =$municipio
<br>
         ";

*/
    
  //global $prefeito, $tesoureiro, $municipio, $ip_imprime;
  if($municipio == ''){
    $municipio = '............';
  }
  $valor = trim(db_formatar($valor, 'p', '', 2));
  $nome = str_pad($nome,40," ", STR_PAD_RIGHT);
  $fd = fsockopen($ip_imprime, $porta);
  if(!$fd) {
		//
		db_msgbox("Impossivel conectar com impressora em $ip_imprime:$porta!");
		return;
	}
	
  // modelo 1 - sapiranga CHRONOS
  // modelo 2 - guaiba / alegrete BEMATECH (DP 20)
  if($modelo == 5){
    $data = str_replace("-", "/", $data);
    $imprimir  = chr(27).chr(177);
    $imprimir .= chr(27).chr(162).$codbco.chr(13);
    $imprimir .= chr(27).chr(163).$valor.chr(13);
    $imprimir .= chr(27).chr(160).$nome.chr(13);
    $imprimir .= chr(27).chr(161).$municipio.chr(13);
    //$imprimir .= chr(27).chr(164).$data.chr(13);
    $imprimir .= chr(27).chr(176);
    
   
    /*
    if(strtoupper($municipio) == "SAPIRANGA"){ 
      $imprimir .= chr(13).chr(10);
      $imprimir .= chr(13).chr(10);
      $imprimir .= chr(13).chr(10);
      $imprimir .= chr(13).chr(10);
      $imprimir .= chr(13).chr(10);
      $imprimir .= chr(13).chr(10);
      $imprimir .= chr(13).chr(10);
      $imprimir .= chr(13).chr(10);
      $imprimir .= "          Prefeito: $prefeito $tesoureiro".chr(10).chr(13);
    }*/
    fputs($fd, $imprimir);
    
  }elseif($modelo == 4){
    fputs($fd, chr(27).chr(160)." $nome\n");
    fputs($fd, chr(27).chr(161)." $municipio\n");
    fputs($fd, chr(27).chr(162)." $codbco\n");
    fputs($fd, chr(27).chr(163)." $valor\n");
//    fputs($fd, chr(27).chr(164)." $data\n");
    fputs($fd, chr(27).chr(176));
/*
    fputs($fd, " \n");
    fputs($fd, " \n");
    fputs($fd, " \n");
    fputs($fd, " \n");
    fputs($fd, " \n");
    fputs($fd, " \n");
    fputs($fd, " \n");
    fputs($fd, "          Prefeito: $prefeito $tesoureiro"."\n");
*/
  }elseif($modelo == 6){

    $data=str_replace("-","",$data);
    $valor=db_formatar($valor, 'p', '0', 15);
    $valor=str_replace(".","",$valor);

    fputs($fd, chr(27).chr(66)." $codbco\n");
    fputs($fd, chr(27).chr(70)." $nome\n");
    fputs($fd, chr(27).chr(67)." $municipio\n");
    fputs($fd, chr(27).chr(68)." $data\n");
    fputs($fd, chr(27).chr(86)." $valor\n");

  }
  // imprime assinatura no cheque
  $sqlass =  "select k11_id,k11_impassche,k39_documento,db04_idparag,db02_texto 
    from cfautent inner join cfautentdocasschq on k39_cfautent=k11_id 
    inner join db_docparag on db04_docum=k39_documento 
    inner join db_paragrafo on db02_idparag=db04_idparag 
    where k11_ipterm = '".$ip_imprime ."' and k11_impassche = 1";
    $resultass = pg_query($sqlass);
    $linhasass = pg_num_rows($resultass);
    if($linhasass>0){
      for($as=0;$as<$linhasass;$as++){
        db_fieldsmemory($resultass, $as); 
        $texto=db_geratexto($db02_texto);
      }
    }
    
  fclose($fd);
      
}

?>