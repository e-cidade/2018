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
	       $sql     = "select k11_id from cfautent where k11_instit = " . db_getsession("DB_instit") . " k11_ipterm = '$ip'"; 
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

class cl_layouts_bs { 

/*
		CABEÇALHO
*/  
    var  $cabec101 = null;
    var  $cabec102 = null;
    var  $cabec103 = null;  
    var  $cabec104 = null;
    var  $cabec105 = null;
    var  $cabec106 = null;
    var  $cabec107 = null;
    var  $cabec108 = null;
    var  $cabec109 = null;
    var  $cabec110 = null;
    var  $cabec111 = null;
    var  $cabec112 = null;
    var  $cabec113 = null;
    var  $cabec114 = null;
    var  $cabec115 = null;
    var  $cabec116 = null;
    var  $cabec117 = null;
    var  $cabec118 = null;
    var  $cabec119 = null;
    var  $cabec120 = null;
    var  $cabec121 = null;
    var  $cabec122 = null;
    var  $cabec123 = null;
    var  $cabec124 = null;
    var  $cabec125 = null;
    var  $cabec126 = null;
    var  $cabec127 = null;
                    
    var  $cabec201 = null;
    var  $cabec202 = null;
    var  $cabec203 = null;
    var  $cabec204 = null;
    var  $cabec205 = null;
    var  $cabec206 = null;
    var  $cabec207 = null;
    var  $cabec208 = null;
    var  $cabec209 = null;
    var  $cabec210 = null;
    var  $cabec211 = null;
    var  $cabec212 = null;
    var  $cabec213 = null;
    var  $cabec214 = null;
    var  $cabec215 = null;
    var  $cabec216 = null;
    var  $cabec217 = null;
    var  $cabec218 = null;
    var  $cabec219 = null;
    var  $cabec220 = null;
    var  $cabec221 = null;
    var  $cabec222 = null;
    var  $cabec223 = null;
    var  $cabec224 = null;
    var  $cabec225 = null;
    var  $cabec226 = null; 
    var  $cabec227 = null; 

/*
      FINAL CABEÇALHO
*/




/*
        CORPO
*/
   var     $detalhe01 =  null;
   var     $detalhe02 =  null;
   var     $detalhe03 =  null;
   var     $detalhe04 =  null;
   var     $detalhe05 =  null;
   var     $detalhe06 =  null;
   var     $detalhe07 =  null;
   var     $detalhe08 =  null;
   var     $detalhe09 =  null;
   var     $detalhe10 =  null;
   var     $detalhe11 =  null;
   var     $detalhe12 =  null;
   var     $detalhe13 =  null;
   var     $detalhe14 =  null;
   var     $detalhe15 =  null;
   var     $detalhe16 =  null;
   var     $detalhe17 =  null;
   var     $detalhe18 =  null;
   var     $detalhe19 =  null;
   var     $detalhe20 =  null;
   var     $detalhe21 =  null;
   var     $detalhe22 =  null;
   var     $detalhe23 =  null;
   var     $detalhe24 =  null;
   var     $detalhe25 =  null;
   var     $detalhe26 =  null; 
   var     $detalhe27 =  null; 
   var     $detalhe28 =  null;
   var     $detalhe29 =  null;
   var     $detalhe30 =  null; 


/*
        TRAILLER
*/
   var     $roda101 =  null;
   var     $roda102 =  null;
   var     $roda103 =  null;
   var     $roda104 =  null;
   var     $roda105 =  null;
   var     $roda106 =  null;
   var     $roda107 =  null;
   var     $roda108 =  null;
   var     $roda109 =  null;
   var     $roda110 =  null;
   var     $roda111 =  null;
   var     $roda112 =  null;
   var     $roda113 =  null;
   var     $roda114 =  null;
   var     $roda115 =  null;

   var     $roda201 =  null;
   var     $roda202 =  null;
   var     $roda203 =  null;
   var     $roda204 =  null;
   var     $roda205 =  null;
   var     $roda206 =  null;
   var     $roda207 =  null;
   var     $roda208 =  null;
   var     $roda209 =  null;
   var     $roda210 =  null;
   var     $roda211 =  null;
   var     $roda212 =  null;
   var     $roda213 =  null;
   var     $roda214 =  null;
   var     $roda215 =  null;



/*
	FINAL CORPO
*/


   var $arquivo  = null;

       
   var $nomearq  = '/tmp/modelo.txt';
   
   function gera_cabecalho(){
	  $this->arquivo = fopen($this->nomearq,"w");
	  fputs($this->arquivo,
	          $this->cabec101
	         .$this->cabec102
	         .$this->cabec103
	         .$this->cabec104
	         .$this->cabec105
	         .$this->cabec106
	         .$this->cabec107
	         .$this->cabec108
	         .$this->cabec109
	         .$this->cabec110
	         .$this->cabec111
	         .$this->cabec112
	         .$this->cabec113
	         .$this->cabec114
	         .$this->cabec115
	         .$this->cabec116 
	         .$this->cabec117
	         .$this->cabec118
	         .$this->cabec119
	         .$this->cabec120
	         .$this->cabec121
	         .$this->cabec122
	         .$this->cabec123
	         .$this->cabec124
	         .$this->cabec125
	         .$this->cabec126
	         .$this->cabec127
		 ."\r\n"
		 //.chr(13).chr(10) 
	     
	  );
   }	  
   function gera_cabecalho02(){
        //segundo cabeçalho
          fputs($this->arquivo,
	          $this->cabec201
	         .$this->cabec202
	         .$this->cabec203
	         .$this->cabec204
	         .$this->cabec205
	         .$this->cabec206
	         .$this->cabec207
	         .$this->cabec208
	         .$this->cabec209
	         .$this->cabec210
	         .$this->cabec211
	         .$this->cabec212
	         .$this->cabec213
	         .$this->cabec214
	         .$this->cabec215
	         .$this->cabec216 
	         .$this->cabec217
	         .$this->cabec218
	         .$this->cabec219
	         .$this->cabec220
	         .$this->cabec221
	         .$this->cabec222
	         .$this->cabec223
	         .$this->cabec224
	         .$this->cabec225
	         .$this->cabec226
	         .$this->cabec227
		 ."\r\n"
		// .chr(13).chr(10) 
	     
	  );

	  //fclose($fd1);  
    } 

    function gera_corpo(){
	  fputs($this->arquivo,
	          $this->detalhe01
	         .$this->detalhe02
	         .$this->detalhe03
	         .$this->detalhe04
	         .$this->detalhe05
	         .$this->detalhe06
	         .$this->detalhe07
	         .$this->detalhe08
	         .$this->detalhe09
	         .$this->detalhe10
	         .$this->detalhe11
	         .$this->detalhe12
	         .$this->detalhe13
	         .$this->detalhe14
	         .$this->detalhe15
	         .$this->detalhe16 
	         .$this->detalhe17
	         .$this->detalhe18
	         .$this->detalhe19
	         .$this->detalhe20
	         .$this->detalhe21
	         .$this->detalhe22
	         .$this->detalhe23
	         .$this->detalhe24
	         .$this->detalhe25
	         .$this->detalhe26
	         .$this->detalhe27
	         .$this->detalhe28
	         .$this->detalhe29
	         .$this->detalhe30
		 ."\r\n"
		 //.chr(13).chr(10) 
	       ); 	  
    }   

    function gera_trailer1(){
	  fputs($this->arquivo,
	          $this->roda101
	         .$this->roda102
	         .$this->roda103
	         .$this->roda104
	         .$this->roda105
	         .$this->roda106
	         .$this->roda107
	         .$this->roda108
	         .$this->roda109
	         .$this->roda110
	         .$this->roda111
	         .$this->roda112
	         .$this->roda113
	         .$this->roda114
	         .$this->roda115
		 //.chr(13).chr(10)
		 ."\r\n"
	       ); 	  
    }
    function gera_trailer2(){
	  fputs($this->arquivo,
	          $this->roda201
	         .$this->roda202
	         .$this->roda203
	         .$this->roda204
	         .$this->roda205
	         .$this->roda206
	         .$this->roda207
	         .$this->roda208
	         .$this->roda209
	         .$this->roda210
	         .$this->roda211
	         .$this->roda212
	         .$this->roda213
	         .$this->roda214
	         .$this->roda215
		 //.chr(13).chr(10)
		 ."\r\n"
	       ); 	  
    }   

    function gera(){
       fclose($this->arquivo);  
    } 


}                    




//classe de layout de banco
class cl_layouts_bb { 

/*
		CABEÇALHO
*/  
   var $cabec01    = '9'; 
   //tipo do registro - informar :0(zero) 
   
   var $cabec02    = '9';
   //código da remessa - informar :1
   
   var $cabec03    = 'xxxxxxx';
   //brancos
   
   var $cabec04    = '99'; //12
   //tipo de serviço -  informar:03
   
   var $cabec05    = 'x'; 
   //indicar cgc - informar:branco
   
   var $cabec06    = '999-99';  
   //valor da tarifa a ser cobrada pelo banco para cada lançamento 
   //efetuado informar "00000"
			    
   var $cabec07    = 'xxxxxxx';
   //brancos
   
   var $cabec08    = '9999'; 
   //prefixo da agência do BB onde a empresa mantém a sua conta de depósitos
   
   var $cabec09    = 'x';
   //dígito  verificador do prefixo da agência (módulo 11 - ver capitulo "Cálculo do digito
   //verificador" )
   
   var $cabec10    = '999999999';
   //numero de depósitos da empresa
   
   var $cabec11    = 'x'; 
   //digito verificador do numero da conta da empresa(modulo 11)
   
   var $cabec12    = 'xxxxx';
   //brancos
   
   var $cabec13    = 'xxxxxxxxxxxxxxxxxxxxxxxxxxx';
   //nome da empresa
   
   var $cabec14    = '999'; 
   //código do BB - informar: 001
   
   var $cabec15    = '999999';
   //numero do convenio. Dado fornecido pelo banco, imprescindivel para
   //o processamento do arquivo	
   
     var $cabec16    = 'xxx'; 
   //tipo de retorno desejado. informar brancos. O tipo de estorno disponibilizado
   //deverá ser configurado no cadastramento do convênio junto ao banco.
   //Será identificado no retorno, nas posições 186 a 194
   
   var $cabec17    = 'xxxxxxxxxx';
   //campo de livre uso do conveniente  
   
   var $cabec18    = '99';
   //meio fisico de retorno.Informar zeros. O meio de retorno deverá ser 
   //configurado no cadastramento do convenio no sistema do banco . Pode ser por edi ouu mainframe 
			    
   var $cabec19    = '999'; 
   //de uso do banco, para controle de remessas por meio fisico de retorno edi ou mainframe
   
   var $cabec20    = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';  //46 char 
   //brancos
   
   var $cabec21    = 'xxxxxxxxxxxxx'; 
   //uso exclusivo do sistema
   
   var $cabec22    = 'xxxx';  
   //informar novo para possibilitar o recebimento de arquivo-retorno,
   //conforme campos 3,4 e 5 do detalhe  
		    
   var $cabec23    = 'xxxxxxxxxxx';
   //brancos
   
   var $cabec24    = 'xxxxxxxxx';
   //No arquivo remessa: informar brancos
   //No arquivo retorno : tipo de retorno que esta sendo disponibilizado 
   //para o cliente, conforme informação do camo 16 no arquivo-remessa. Pode ser
   //RETPREVIA-retorno de prévias
   //RETPROCES-retorno de processamento
   //RETCONSOL-retorno de confirmação de processamento
   
   var $cabec25    = '999999'; 
   //sequencial informar: 000001

/*
      FINAL CABEÇALHO
*/

/*
        CORPO
*/
   var $corp01    = '9';  //pos: 001 a 001 
   //tipo do registro - informar :1

   var $corp02    = 'x';  //pos: 002 a 002 
   //brancos

   var $corp03    = '9';  //pos: 003 a 003
   //indicador de conferência da agênci, conta e CPF/CGC do favorecido

   var $corp04    = 'xxxxxxxxxxxx'; //pos:004 a 015 
   //CPF, CGC ou PIS/PASEP

   var $corp05    = 'xx'; //pos:016 a 017 
   //digito verificador

   var $corp06    = '9999'; //pos:018 a 021 
   //Arquivo remessa, informar:0
   //Arquivo retorno, informar: prefixo da agencia BB

   var $corp07    = 'x'; //pos:022 a 023
   //Arquivo remessa, informar:0
   //Arquivo retorno, informar: DV do prefixo da agencia BB

   var $corp08    = '999999999'; //pos:023 a 031
   //Arquivo remessa, informar:0
   //Arquivo retorno, informar: numero da conta do banco onde foi efetivado o credito

   var $corp09    = 'x'; //pos:032 a 032
   //Arquivo remessa, informar:0
   //Arquivo retorno, informar: o DV do numero da conta onde foi efetivado o credito
    
   var $corp10    = 'xxxxxxxxxx'; //pos: 033 a 042
   //livre uso do conveniente

   var $corp11    = 'xxxxxxxx'; //043 a 050 
   //BRANCOS	

   var $corp12    = 'xxxxxx'; //pos: 051 a 056
   //numero identificador -  campo de livre uso do conveniente
   
   var $corp13    = '999'; // pos: 057 a 059
   //código da camara de compensação
   
   var $corp14    = 'xxx'; //pos:060 a 062
   //código do banco destinatario do crédito(brancos se for BB)

   var $corp15    = 'xxxx'; //pos:063 a 066
   //prefixo da agencia do favorecido

   var $corp16  =  'x'; //pos 067 a 067
   //digito verificador - prefixo da agencia do favorecido 
   //em branco se for prefixo sem DV

   var $corp17  =  '999999999999'; //pos 068 a 079 
   //numero da conta de depósito do favorecido
   
   var $corp18  =  'X'; //pos 080 a 080 
   //digito verificador  da conta do favorecido para creditos no BB
   
   var $corp19  =  'XX'; //pos 081 a 082
   //brancos
   
   var $corp20  =  'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'; //pos 083 a 122 ----- 40 chars
   //nome do favorecido
   
   var $corp21  =  '999999'; //pos 123 a 128 
   //data do pagamento ddmmaa
      
   var $corp22  =  '99999999999-99' ; //pos 129 a 141
   //valor do credito
   
   var $corp23  =  '999'; //pos 142 a 144
   //codigo do servico
   
   var $corp24  =  'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'; //pos 145 a 184  --- 40chars
   //mensagem de livre uso da empresa
   
   var $corp25  =  'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'; //pos 185 a 194  --- 10chars
   //brancos
   
   var $corp26  =  'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'; //pos 195 a 184  --- 6chars
   // sequencial do registro
/*
	FINAL CORPO
*/

   var $arquivo  = null;
   var $texto = null;
       
   var $nomearq  = '/tmp/modelo.txt';
   
   function gera_cabecalho(){
	  $this->arquivo = fopen($this->nomearq,"w");

	  $this->texto .= (
	          $this->cabec01
	         .$this->cabec02
	         .$this->cabec03
	         .$this->cabec04
	         .$this->cabec05
	         .$this->cabec06
	         .$this->cabec07
	         .$this->cabec08
	         .$this->cabec09
	         .$this->cabec10
	         .$this->cabec11
	         .$this->cabec12
	         .$this->cabec13
	         .$this->cabec14
	         .$this->cabec15
	         .$this->cabec16 
	         .$this->cabec17
	         .$this->cabec18
	         .$this->cabec19
	         .$this->cabec20
	         .$this->cabec21
	         .$this->cabec22
	         .$this->cabec23
	         .$this->cabec24
	         .$this->cabec25
		 ."\r\n"
	     
	  );
	  
	  fputs($this->arquivo,
	          $this->cabec01
	         .$this->cabec02
	         .$this->cabec03
	         .$this->cabec04
	         .$this->cabec05
	         .$this->cabec06
	         .$this->cabec07
	         .$this->cabec08
	         .$this->cabec09
	         .$this->cabec10
	         .$this->cabec11
	         .$this->cabec12
	         .$this->cabec13
	         .$this->cabec14
	         .$this->cabec15
	         .$this->cabec16 
	         .$this->cabec17
	         .$this->cabec18
	         .$this->cabec19
	         .$this->cabec20
	         .$this->cabec21
	         .$this->cabec22
	         .$this->cabec23
	         .$this->cabec24
	         .$this->cabec25
		 ."\r\n"
	     
	  );

	  
	  //fclose($fd1);  
    } 

    function gera_corpo(){
      
	  $this->texto .= (
	          $this->corp01
	         .$this->corp02
	         .$this->corp03
	         .$this->corp04
	         .$this->corp05
	         .$this->corp06
	         .$this->corp07
	         .$this->corp08
	         .$this->corp09
	         .$this->corp10
	         .$this->corp11
	         .$this->corp12
	         .$this->corp13
	         .$this->corp14
	         .$this->corp15
	         .$this->corp16 
	         .$this->corp17
	         .$this->corp18
	         .$this->corp19
	         .$this->corp20
	         .$this->corp21
	         .$this->corp22
	         .$this->corp23
	         .$this->corp24
	         .$this->corp25
	         .$this->corp26
		 ."\r\n"
	       );
	  
	  fputs($this->arquivo,
	          $this->corp01
	         .$this->corp02
	         .$this->corp03
	         .$this->corp04
	         .$this->corp05
	         .$this->corp06
	         .$this->corp07
	         .$this->corp08
	         .$this->corp09
	         .$this->corp10
	         .$this->corp11
	         .$this->corp12
	         .$this->corp13
	         .$this->corp14
	         .$this->corp15
	         .$this->corp16 
	         .$this->corp17
	         .$this->corp18
	         .$this->corp19
	         .$this->corp20
	         .$this->corp21
	         .$this->corp22
	         .$this->corp23
	         .$this->corp24
	         .$this->corp25
	         .$this->corp26
		 ."\r\n"
	       ); 	  
    }
    
    function gera_trailer(){
      
          $this->texto .= (  
	          $this->rodap01
	         .$this->rodap02
	         .$this->rodap03
		 ."\r\n"
	       ); 	

	  fputs($this->arquivo,
	          $this->rodap01
	         .$this->rodap02
	         .$this->rodap03
		 ."\r\n"
	       ); 	  
    }   

    function gera(){
       fclose($this->arquivo);  
    } 
}                    
?>