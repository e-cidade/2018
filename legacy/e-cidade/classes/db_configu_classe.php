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
//CLASSE DA ENTIDADE configu
class cl_configu { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $d09_usuari = null; 
   var $d09_bro01 = 0; 
   var $d09_bro02 = 0; 
   var $d09_bro03 = 0; 
   var $d09_bro04 = 0; 
   var $d09_bro05 = 0; 
   var $d09_bro07 = 0; 
   var $d09_bro08 = 0; 
   var $d09_bro09 = 0; 
   var $d09_bro10 = 0; 
   var $d09_bro11 = 0; 
   var $d09_bro12 = 0; 
   var $d09_bro13 = 0; 
   var $d09_bro14 = 0; 
   var $d09_bro15 = 0; 
   var $d09_bro16 = 0; 
   var $d09_bro17 = 0; 
   var $d09_bro18 = 0; 
   var $d09_bro19 = 0; 
   var $d09_bro06 = 0; 
   var $d09_pop01 = 0; 
   var $d09_pop02 = 0; 
   var $d09_pop03 = 0; 
   var $d09_pop04 = 0; 
   var $d09_pop05 = 0; 
   var $d09_pop06 = 0; 
   var $d09_pop07 = 0; 
   var $d09_pop08 = 0; 
   var $d09_pop09 = 0; 
   var $d09_popup = 0; 
   var $d09_tel01 = 0; 
   var $d09_tel02 = 0; 
   var $d09_tel03 = 0; 
   var $d09_tel04 = 0; 
   var $d09_tel05 = 0; 
   var $d09_tel06 = 0; 
   var $d09_tel19 = 0; 
   var $d09_tel20 = 0; 
   var $d09_tel21 = 0; 
   var $d09_tel07 = 0; 
   var $d09_tel08 = 0; 
   var $d09_tel09 = 0; 
   var $d09_tel10 = 0; 
   var $d09_tel11 = 0; 
   var $d09_tel12 = 0; 
   var $d09_tel13 = 0; 
   var $d09_tel14 = 0; 
   var $d09_tel15 = 0; 
   var $d09_tel16 = 0; 
   var $d09_tel18 = 0; 
   var $d09_tel17 = 0; 
   var $d09_tel22 = 0; 
   var $d09_win01 = 0; 
   var $d09_lmensa = 0; 
   var $d09_tel23 = 0; 
   var $d09_tel24 = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 d09_usuari = varchar(8) = Usuário 
                 d09_bro01 = int4 =  
                 d09_bro02 = int4 =  
                 d09_bro03 = int4 =  
                 d09_bro04 = int4 =  
                 d09_bro05 = int4 =  
                 d09_bro07 = int4 =  
                 d09_bro08 = int4 =  
                 d09_bro09 = int4 =  
                 d09_bro10 = int4 =  
                 d09_bro11 = int4 =  
                 d09_bro12 = int4 =  
                 d09_bro13 = int4 =  
                 d09_bro14 = int4 =  
                 d09_bro15 = int4 =  
                 d09_bro16 = int4 =  
                 d09_bro17 = int4 =  
                 d09_bro18 = int4 =  
                 d09_bro19 = int4 =  
                 d09_bro06 = int4 =  
                 d09_pop01 = int4 =  
                 d09_pop02 = int4 =  
                 d09_pop03 = int4 =  
                 d09_pop04 = int4 =  
                 d09_pop05 = int4 =  
                 d09_pop06 = int4 =  
                 d09_pop07 = int4 =  
                 d09_pop08 = int4 =  
                 d09_pop09 = int4 =  
                 d09_popup = int4 =  
                 d09_tel01 = int4 =  
                 d09_tel02 = int4 =  
                 d09_tel03 = int4 =  
                 d09_tel04 = int4 =  
                 d09_tel05 = int4 =  
                 d09_tel06 = int4 =  
                 d09_tel19 = int4 =  
                 d09_tel20 = int4 =  
                 d09_tel21 = int4 =  
                 d09_tel07 = int4 =  
                 d09_tel08 = int4 =  
                 d09_tel09 = int4 =  
                 d09_tel10 = int4 =  
                 d09_tel11 = int4 =  
                 d09_tel12 = int4 =  
                 d09_tel13 = int4 =  
                 d09_tel14 = int4 =  
                 d09_tel15 = int4 =  
                 d09_tel16 = int4 =  
                 d09_tel18 = int4 =  
                 d09_tel17 = int4 =  
                 d09_tel22 = int4 =  
                 d09_win01 = int4 =  
                 d09_lmensa = int4 =  
                 d09_tel23 = int4 =  
                 d09_tel24 = int4 =  
                 ";
   //funcao construtor da classe 
   function cl_configu() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("configu"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->d09_usuari = ($this->d09_usuari == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_usuari"]:$this->d09_usuari);
       $this->d09_bro01 = ($this->d09_bro01 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_bro01"]:$this->d09_bro01);
       $this->d09_bro02 = ($this->d09_bro02 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_bro02"]:$this->d09_bro02);
       $this->d09_bro03 = ($this->d09_bro03 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_bro03"]:$this->d09_bro03);
       $this->d09_bro04 = ($this->d09_bro04 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_bro04"]:$this->d09_bro04);
       $this->d09_bro05 = ($this->d09_bro05 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_bro05"]:$this->d09_bro05);
       $this->d09_bro07 = ($this->d09_bro07 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_bro07"]:$this->d09_bro07);
       $this->d09_bro08 = ($this->d09_bro08 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_bro08"]:$this->d09_bro08);
       $this->d09_bro09 = ($this->d09_bro09 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_bro09"]:$this->d09_bro09);
       $this->d09_bro10 = ($this->d09_bro10 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_bro10"]:$this->d09_bro10);
       $this->d09_bro11 = ($this->d09_bro11 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_bro11"]:$this->d09_bro11);
       $this->d09_bro12 = ($this->d09_bro12 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_bro12"]:$this->d09_bro12);
       $this->d09_bro13 = ($this->d09_bro13 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_bro13"]:$this->d09_bro13);
       $this->d09_bro14 = ($this->d09_bro14 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_bro14"]:$this->d09_bro14);
       $this->d09_bro15 = ($this->d09_bro15 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_bro15"]:$this->d09_bro15);
       $this->d09_bro16 = ($this->d09_bro16 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_bro16"]:$this->d09_bro16);
       $this->d09_bro17 = ($this->d09_bro17 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_bro17"]:$this->d09_bro17);
       $this->d09_bro18 = ($this->d09_bro18 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_bro18"]:$this->d09_bro18);
       $this->d09_bro19 = ($this->d09_bro19 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_bro19"]:$this->d09_bro19);
       $this->d09_bro06 = ($this->d09_bro06 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_bro06"]:$this->d09_bro06);
       $this->d09_pop01 = ($this->d09_pop01 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_pop01"]:$this->d09_pop01);
       $this->d09_pop02 = ($this->d09_pop02 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_pop02"]:$this->d09_pop02);
       $this->d09_pop03 = ($this->d09_pop03 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_pop03"]:$this->d09_pop03);
       $this->d09_pop04 = ($this->d09_pop04 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_pop04"]:$this->d09_pop04);
       $this->d09_pop05 = ($this->d09_pop05 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_pop05"]:$this->d09_pop05);
       $this->d09_pop06 = ($this->d09_pop06 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_pop06"]:$this->d09_pop06);
       $this->d09_pop07 = ($this->d09_pop07 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_pop07"]:$this->d09_pop07);
       $this->d09_pop08 = ($this->d09_pop08 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_pop08"]:$this->d09_pop08);
       $this->d09_pop09 = ($this->d09_pop09 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_pop09"]:$this->d09_pop09);
       $this->d09_popup = ($this->d09_popup == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_popup"]:$this->d09_popup);
       $this->d09_tel01 = ($this->d09_tel01 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_tel01"]:$this->d09_tel01);
       $this->d09_tel02 = ($this->d09_tel02 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_tel02"]:$this->d09_tel02);
       $this->d09_tel03 = ($this->d09_tel03 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_tel03"]:$this->d09_tel03);
       $this->d09_tel04 = ($this->d09_tel04 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_tel04"]:$this->d09_tel04);
       $this->d09_tel05 = ($this->d09_tel05 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_tel05"]:$this->d09_tel05);
       $this->d09_tel06 = ($this->d09_tel06 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_tel06"]:$this->d09_tel06);
       $this->d09_tel19 = ($this->d09_tel19 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_tel19"]:$this->d09_tel19);
       $this->d09_tel20 = ($this->d09_tel20 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_tel20"]:$this->d09_tel20);
       $this->d09_tel21 = ($this->d09_tel21 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_tel21"]:$this->d09_tel21);
       $this->d09_tel07 = ($this->d09_tel07 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_tel07"]:$this->d09_tel07);
       $this->d09_tel08 = ($this->d09_tel08 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_tel08"]:$this->d09_tel08);
       $this->d09_tel09 = ($this->d09_tel09 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_tel09"]:$this->d09_tel09);
       $this->d09_tel10 = ($this->d09_tel10 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_tel10"]:$this->d09_tel10);
       $this->d09_tel11 = ($this->d09_tel11 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_tel11"]:$this->d09_tel11);
       $this->d09_tel12 = ($this->d09_tel12 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_tel12"]:$this->d09_tel12);
       $this->d09_tel13 = ($this->d09_tel13 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_tel13"]:$this->d09_tel13);
       $this->d09_tel14 = ($this->d09_tel14 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_tel14"]:$this->d09_tel14);
       $this->d09_tel15 = ($this->d09_tel15 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_tel15"]:$this->d09_tel15);
       $this->d09_tel16 = ($this->d09_tel16 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_tel16"]:$this->d09_tel16);
       $this->d09_tel18 = ($this->d09_tel18 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_tel18"]:$this->d09_tel18);
       $this->d09_tel17 = ($this->d09_tel17 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_tel17"]:$this->d09_tel17);
       $this->d09_tel22 = ($this->d09_tel22 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_tel22"]:$this->d09_tel22);
       $this->d09_win01 = ($this->d09_win01 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_win01"]:$this->d09_win01);
       $this->d09_lmensa = ($this->d09_lmensa == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_lmensa"]:$this->d09_lmensa);
       $this->d09_tel23 = ($this->d09_tel23 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_tel23"]:$this->d09_tel23);
       $this->d09_tel24 = ($this->d09_tel24 == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_tel24"]:$this->d09_tel24);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->d09_usuari == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "d09_usuari";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_bro01 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_bro01";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_bro02 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_bro02";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_bro03 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_bro03";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_bro04 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_bro04";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_bro05 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_bro05";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_bro07 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_bro07";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_bro08 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_bro08";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_bro09 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_bro09";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_bro10 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_bro10";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_bro11 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_bro11";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_bro12 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_bro12";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_bro13 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_bro13";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_bro14 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_bro14";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_bro15 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_bro15";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_bro16 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_bro16";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_bro17 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_bro17";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_bro18 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_bro18";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_bro19 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_bro19";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_bro06 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_bro06";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_pop01 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_pop01";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_pop02 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_pop02";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_pop03 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_pop03";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_pop04 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_pop04";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_pop05 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_pop05";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_pop06 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_pop06";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_pop07 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_pop07";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_pop08 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_pop08";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_pop09 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_pop09";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_popup == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_popup";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_tel01 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_tel01";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_tel02 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_tel02";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_tel03 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_tel03";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_tel04 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_tel04";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_tel05 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_tel05";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_tel06 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_tel06";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_tel19 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_tel19";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_tel20 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_tel20";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_tel21 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_tel21";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_tel07 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_tel07";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_tel08 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_tel08";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_tel09 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_tel09";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_tel10 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_tel10";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_tel11 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_tel11";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_tel12 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_tel12";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_tel13 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_tel13";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_tel14 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_tel14";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_tel15 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_tel15";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_tel16 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_tel16";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_tel18 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_tel18";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_tel17 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_tel17";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_tel22 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_tel22";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_win01 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_win01";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_lmensa == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_lmensa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_tel23 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_tel23";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_tel24 == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "d09_tel24";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into configu(
                                       d09_usuari 
                                      ,d09_bro01 
                                      ,d09_bro02 
                                      ,d09_bro03 
                                      ,d09_bro04 
                                      ,d09_bro05 
                                      ,d09_bro07 
                                      ,d09_bro08 
                                      ,d09_bro09 
                                      ,d09_bro10 
                                      ,d09_bro11 
                                      ,d09_bro12 
                                      ,d09_bro13 
                                      ,d09_bro14 
                                      ,d09_bro15 
                                      ,d09_bro16 
                                      ,d09_bro17 
                                      ,d09_bro18 
                                      ,d09_bro19 
                                      ,d09_bro06 
                                      ,d09_pop01 
                                      ,d09_pop02 
                                      ,d09_pop03 
                                      ,d09_pop04 
                                      ,d09_pop05 
                                      ,d09_pop06 
                                      ,d09_pop07 
                                      ,d09_pop08 
                                      ,d09_pop09 
                                      ,d09_popup 
                                      ,d09_tel01 
                                      ,d09_tel02 
                                      ,d09_tel03 
                                      ,d09_tel04 
                                      ,d09_tel05 
                                      ,d09_tel06 
                                      ,d09_tel19 
                                      ,d09_tel20 
                                      ,d09_tel21 
                                      ,d09_tel07 
                                      ,d09_tel08 
                                      ,d09_tel09 
                                      ,d09_tel10 
                                      ,d09_tel11 
                                      ,d09_tel12 
                                      ,d09_tel13 
                                      ,d09_tel14 
                                      ,d09_tel15 
                                      ,d09_tel16 
                                      ,d09_tel18 
                                      ,d09_tel17 
                                      ,d09_tel22 
                                      ,d09_win01 
                                      ,d09_lmensa 
                                      ,d09_tel23 
                                      ,d09_tel24 
                       )
                values (
                                '$this->d09_usuari' 
                               ,$this->d09_bro01 
                               ,$this->d09_bro02 
                               ,$this->d09_bro03 
                               ,$this->d09_bro04 
                               ,$this->d09_bro05 
                               ,$this->d09_bro07 
                               ,$this->d09_bro08 
                               ,$this->d09_bro09 
                               ,$this->d09_bro10 
                               ,$this->d09_bro11 
                               ,$this->d09_bro12 
                               ,$this->d09_bro13 
                               ,$this->d09_bro14 
                               ,$this->d09_bro15 
                               ,$this->d09_bro16 
                               ,$this->d09_bro17 
                               ,$this->d09_bro18 
                               ,$this->d09_bro19 
                               ,$this->d09_bro06 
                               ,$this->d09_pop01 
                               ,$this->d09_pop02 
                               ,$this->d09_pop03 
                               ,$this->d09_pop04 
                               ,$this->d09_pop05 
                               ,$this->d09_pop06 
                               ,$this->d09_pop07 
                               ,$this->d09_pop08 
                               ,$this->d09_pop09 
                               ,$this->d09_popup 
                               ,$this->d09_tel01 
                               ,$this->d09_tel02 
                               ,$this->d09_tel03 
                               ,$this->d09_tel04 
                               ,$this->d09_tel05 
                               ,$this->d09_tel06 
                               ,$this->d09_tel19 
                               ,$this->d09_tel20 
                               ,$this->d09_tel21 
                               ,$this->d09_tel07 
                               ,$this->d09_tel08 
                               ,$this->d09_tel09 
                               ,$this->d09_tel10 
                               ,$this->d09_tel11 
                               ,$this->d09_tel12 
                               ,$this->d09_tel13 
                               ,$this->d09_tel14 
                               ,$this->d09_tel15 
                               ,$this->d09_tel16 
                               ,$this->d09_tel18 
                               ,$this->d09_tel17 
                               ,$this->d09_tel22 
                               ,$this->d09_win01 
                               ,$this->d09_lmensa 
                               ,$this->d09_tel23 
                               ,$this->d09_tel24 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Configuração () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Configuração já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Configuração () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     return true;
   } 
   // funcao para alteracao
   function alterar ( $oid=null ) { 
      $this->atualizacampos();
     $sql = " update configu set ";
     $virgula = "";
     if(trim($this->d09_usuari)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_usuari"])){ 
       $sql  .= $virgula." d09_usuari = '$this->d09_usuari' ";
       $virgula = ",";
       if(trim($this->d09_usuari) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "d09_usuari";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_bro01)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_bro01"])){ 
       $sql  .= $virgula." d09_bro01 = $this->d09_bro01 ";
       $virgula = ",";
       if(trim($this->d09_bro01) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_bro01";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_bro02)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_bro02"])){ 
       $sql  .= $virgula." d09_bro02 = $this->d09_bro02 ";
       $virgula = ",";
       if(trim($this->d09_bro02) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_bro02";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_bro03)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_bro03"])){ 
       $sql  .= $virgula." d09_bro03 = $this->d09_bro03 ";
       $virgula = ",";
       if(trim($this->d09_bro03) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_bro03";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_bro04)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_bro04"])){ 
       $sql  .= $virgula." d09_bro04 = $this->d09_bro04 ";
       $virgula = ",";
       if(trim($this->d09_bro04) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_bro04";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_bro05)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_bro05"])){ 
       $sql  .= $virgula." d09_bro05 = $this->d09_bro05 ";
       $virgula = ",";
       if(trim($this->d09_bro05) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_bro05";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_bro07)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_bro07"])){ 
       $sql  .= $virgula." d09_bro07 = $this->d09_bro07 ";
       $virgula = ",";
       if(trim($this->d09_bro07) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_bro07";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_bro08)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_bro08"])){ 
       $sql  .= $virgula." d09_bro08 = $this->d09_bro08 ";
       $virgula = ",";
       if(trim($this->d09_bro08) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_bro08";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_bro09)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_bro09"])){ 
       $sql  .= $virgula." d09_bro09 = $this->d09_bro09 ";
       $virgula = ",";
       if(trim($this->d09_bro09) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_bro09";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_bro10)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_bro10"])){ 
       $sql  .= $virgula." d09_bro10 = $this->d09_bro10 ";
       $virgula = ",";
       if(trim($this->d09_bro10) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_bro10";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_bro11)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_bro11"])){ 
       $sql  .= $virgula." d09_bro11 = $this->d09_bro11 ";
       $virgula = ",";
       if(trim($this->d09_bro11) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_bro11";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_bro12)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_bro12"])){ 
       $sql  .= $virgula." d09_bro12 = $this->d09_bro12 ";
       $virgula = ",";
       if(trim($this->d09_bro12) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_bro12";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_bro13)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_bro13"])){ 
       $sql  .= $virgula." d09_bro13 = $this->d09_bro13 ";
       $virgula = ",";
       if(trim($this->d09_bro13) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_bro13";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_bro14)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_bro14"])){ 
       $sql  .= $virgula." d09_bro14 = $this->d09_bro14 ";
       $virgula = ",";
       if(trim($this->d09_bro14) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_bro14";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_bro15)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_bro15"])){ 
       $sql  .= $virgula." d09_bro15 = $this->d09_bro15 ";
       $virgula = ",";
       if(trim($this->d09_bro15) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_bro15";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_bro16)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_bro16"])){ 
       $sql  .= $virgula." d09_bro16 = $this->d09_bro16 ";
       $virgula = ",";
       if(trim($this->d09_bro16) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_bro16";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_bro17)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_bro17"])){ 
       $sql  .= $virgula." d09_bro17 = $this->d09_bro17 ";
       $virgula = ",";
       if(trim($this->d09_bro17) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_bro17";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_bro18)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_bro18"])){ 
       $sql  .= $virgula." d09_bro18 = $this->d09_bro18 ";
       $virgula = ",";
       if(trim($this->d09_bro18) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_bro18";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_bro19)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_bro19"])){ 
       $sql  .= $virgula." d09_bro19 = $this->d09_bro19 ";
       $virgula = ",";
       if(trim($this->d09_bro19) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_bro19";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_bro06)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_bro06"])){ 
       $sql  .= $virgula." d09_bro06 = $this->d09_bro06 ";
       $virgula = ",";
       if(trim($this->d09_bro06) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_bro06";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_pop01)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_pop01"])){ 
       $sql  .= $virgula." d09_pop01 = $this->d09_pop01 ";
       $virgula = ",";
       if(trim($this->d09_pop01) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_pop01";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_pop02)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_pop02"])){ 
       $sql  .= $virgula." d09_pop02 = $this->d09_pop02 ";
       $virgula = ",";
       if(trim($this->d09_pop02) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_pop02";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_pop03)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_pop03"])){ 
       $sql  .= $virgula." d09_pop03 = $this->d09_pop03 ";
       $virgula = ",";
       if(trim($this->d09_pop03) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_pop03";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_pop04)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_pop04"])){ 
       $sql  .= $virgula." d09_pop04 = $this->d09_pop04 ";
       $virgula = ",";
       if(trim($this->d09_pop04) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_pop04";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_pop05)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_pop05"])){ 
       $sql  .= $virgula." d09_pop05 = $this->d09_pop05 ";
       $virgula = ",";
       if(trim($this->d09_pop05) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_pop05";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_pop06)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_pop06"])){ 
       $sql  .= $virgula." d09_pop06 = $this->d09_pop06 ";
       $virgula = ",";
       if(trim($this->d09_pop06) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_pop06";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_pop07)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_pop07"])){ 
       $sql  .= $virgula." d09_pop07 = $this->d09_pop07 ";
       $virgula = ",";
       if(trim($this->d09_pop07) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_pop07";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_pop08)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_pop08"])){ 
       $sql  .= $virgula." d09_pop08 = $this->d09_pop08 ";
       $virgula = ",";
       if(trim($this->d09_pop08) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_pop08";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_pop09)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_pop09"])){ 
       $sql  .= $virgula." d09_pop09 = $this->d09_pop09 ";
       $virgula = ",";
       if(trim($this->d09_pop09) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_pop09";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_popup)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_popup"])){ 
       $sql  .= $virgula." d09_popup = $this->d09_popup ";
       $virgula = ",";
       if(trim($this->d09_popup) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_popup";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_tel01)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_tel01"])){ 
       $sql  .= $virgula." d09_tel01 = $this->d09_tel01 ";
       $virgula = ",";
       if(trim($this->d09_tel01) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_tel01";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_tel02)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_tel02"])){ 
       $sql  .= $virgula." d09_tel02 = $this->d09_tel02 ";
       $virgula = ",";
       if(trim($this->d09_tel02) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_tel02";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_tel03)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_tel03"])){ 
       $sql  .= $virgula." d09_tel03 = $this->d09_tel03 ";
       $virgula = ",";
       if(trim($this->d09_tel03) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_tel03";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_tel04)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_tel04"])){ 
       $sql  .= $virgula." d09_tel04 = $this->d09_tel04 ";
       $virgula = ",";
       if(trim($this->d09_tel04) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_tel04";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_tel05)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_tel05"])){ 
       $sql  .= $virgula." d09_tel05 = $this->d09_tel05 ";
       $virgula = ",";
       if(trim($this->d09_tel05) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_tel05";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_tel06)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_tel06"])){ 
       $sql  .= $virgula." d09_tel06 = $this->d09_tel06 ";
       $virgula = ",";
       if(trim($this->d09_tel06) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_tel06";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_tel19)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_tel19"])){ 
       $sql  .= $virgula." d09_tel19 = $this->d09_tel19 ";
       $virgula = ",";
       if(trim($this->d09_tel19) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_tel19";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_tel20)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_tel20"])){ 
       $sql  .= $virgula." d09_tel20 = $this->d09_tel20 ";
       $virgula = ",";
       if(trim($this->d09_tel20) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_tel20";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_tel21)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_tel21"])){ 
       $sql  .= $virgula." d09_tel21 = $this->d09_tel21 ";
       $virgula = ",";
       if(trim($this->d09_tel21) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_tel21";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_tel07)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_tel07"])){ 
       $sql  .= $virgula." d09_tel07 = $this->d09_tel07 ";
       $virgula = ",";
       if(trim($this->d09_tel07) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_tel07";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_tel08)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_tel08"])){ 
       $sql  .= $virgula." d09_tel08 = $this->d09_tel08 ";
       $virgula = ",";
       if(trim($this->d09_tel08) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_tel08";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_tel09)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_tel09"])){ 
       $sql  .= $virgula." d09_tel09 = $this->d09_tel09 ";
       $virgula = ",";
       if(trim($this->d09_tel09) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_tel09";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_tel10)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_tel10"])){ 
       $sql  .= $virgula." d09_tel10 = $this->d09_tel10 ";
       $virgula = ",";
       if(trim($this->d09_tel10) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_tel10";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_tel11)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_tel11"])){ 
       $sql  .= $virgula." d09_tel11 = $this->d09_tel11 ";
       $virgula = ",";
       if(trim($this->d09_tel11) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_tel11";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_tel12)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_tel12"])){ 
       $sql  .= $virgula." d09_tel12 = $this->d09_tel12 ";
       $virgula = ",";
       if(trim($this->d09_tel12) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_tel12";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_tel13)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_tel13"])){ 
       $sql  .= $virgula." d09_tel13 = $this->d09_tel13 ";
       $virgula = ",";
       if(trim($this->d09_tel13) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_tel13";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_tel14)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_tel14"])){ 
       $sql  .= $virgula." d09_tel14 = $this->d09_tel14 ";
       $virgula = ",";
       if(trim($this->d09_tel14) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_tel14";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_tel15)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_tel15"])){ 
       $sql  .= $virgula." d09_tel15 = $this->d09_tel15 ";
       $virgula = ",";
       if(trim($this->d09_tel15) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_tel15";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_tel16)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_tel16"])){ 
       $sql  .= $virgula." d09_tel16 = $this->d09_tel16 ";
       $virgula = ",";
       if(trim($this->d09_tel16) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_tel16";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_tel18)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_tel18"])){ 
       $sql  .= $virgula." d09_tel18 = $this->d09_tel18 ";
       $virgula = ",";
       if(trim($this->d09_tel18) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_tel18";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_tel17)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_tel17"])){ 
       $sql  .= $virgula." d09_tel17 = $this->d09_tel17 ";
       $virgula = ",";
       if(trim($this->d09_tel17) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_tel17";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_tel22)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_tel22"])){ 
       $sql  .= $virgula." d09_tel22 = $this->d09_tel22 ";
       $virgula = ",";
       if(trim($this->d09_tel22) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_tel22";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_win01)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_win01"])){ 
       $sql  .= $virgula." d09_win01 = $this->d09_win01 ";
       $virgula = ",";
       if(trim($this->d09_win01) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_win01";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_lmensa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_lmensa"])){ 
       $sql  .= $virgula." d09_lmensa = $this->d09_lmensa ";
       $virgula = ",";
       if(trim($this->d09_lmensa) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_lmensa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_tel23)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_tel23"])){ 
       $sql  .= $virgula." d09_tel23 = $this->d09_tel23 ";
       $virgula = ",";
       if(trim($this->d09_tel23) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_tel23";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_tel24)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_tel24"])){ 
       $sql  .= $virgula." d09_tel24 = $this->d09_tel24 ";
       $virgula = ",";
       if(trim($this->d09_tel24) == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "d09_tel24";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
$sql .= "oid = '$oid'";     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Configuração nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Configuração nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ( $oid=null ,$dbwhere=null) { 
     $sql = " delete from configu
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
       $sql2 = "oid = '$oid'";
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Configuração nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Configuração nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:configu";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $oid = null,$campos="configu.oid,*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from configu ";
     $sql2 = "";
     if($dbwhere==""){
       if( $oid != "" && $oid != null){
          $sql2 = " where configu.oid = '$oid'";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_file ( $oid = null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from configu ";
     $sql2 = "";
     if($dbwhere==""){
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>