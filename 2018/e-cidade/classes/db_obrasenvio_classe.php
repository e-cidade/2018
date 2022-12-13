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

//MODULO: projetos
//CLASSE DA ENTIDADE obrasenvio
class cl_obrasenvio { 
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
   var $ob16_codobrasenvio = 0; 
   var $ob16_data_dia = null; 
   var $ob16_data_mes = null; 
   var $ob16_data_ano = null; 
   var $ob16_data = null; 
   var $ob16_hora = null; 
   var $ob16_login = 0; 
   var $ob16_dtini_dia = null; 
   var $ob16_dtini_mes = null; 
   var $ob16_dtini_ano = null; 
   var $ob16_dtini = null; 
   var $ob16_dtfim_dia = null; 
   var $ob16_dtfim_mes = null; 
   var $ob16_dtfim_ano = null; 
   var $ob16_dtfim = null; 
   var $ob16_nomearq = null; 
   var $ob16_arq = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ob16_codobrasenvio = int8 = Código 
                 ob16_data = date = Data 
                 ob16_hora = varchar(8) = Hora 
                 ob16_login = int4 = Cod. Usuário 
                 ob16_dtini = date = Data inicial da geração 
                 ob16_dtfim = date = Data final da geração 
                 ob16_nomearq = varchar(50) = Nome do arquivo da geração 
                 ob16_arq = text = Conteúdo do arquivo TXT enviado 
                 ";
   //funcao construtor da classe 
   function cl_obrasenvio() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("obrasenvio"); 
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
       $this->ob16_codobrasenvio = ($this->ob16_codobrasenvio == ""?@$GLOBALS["HTTP_POST_VARS"]["ob16_codobrasenvio"]:$this->ob16_codobrasenvio);
       if($this->ob16_data == ""){
         $this->ob16_data_dia = ($this->ob16_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ob16_data_dia"]:$this->ob16_data_dia);
         $this->ob16_data_mes = ($this->ob16_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ob16_data_mes"]:$this->ob16_data_mes);
         $this->ob16_data_ano = ($this->ob16_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ob16_data_ano"]:$this->ob16_data_ano);
         if($this->ob16_data_dia != ""){
            $this->ob16_data = $this->ob16_data_ano."-".$this->ob16_data_mes."-".$this->ob16_data_dia;
         }
       }
       $this->ob16_hora = ($this->ob16_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["ob16_hora"]:$this->ob16_hora);
       $this->ob16_login = ($this->ob16_login == ""?@$GLOBALS["HTTP_POST_VARS"]["ob16_login"]:$this->ob16_login);
       if($this->ob16_dtini == ""){
         $this->ob16_dtini_dia = ($this->ob16_dtini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ob16_dtini_dia"]:$this->ob16_dtini_dia);
         $this->ob16_dtini_mes = ($this->ob16_dtini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ob16_dtini_mes"]:$this->ob16_dtini_mes);
         $this->ob16_dtini_ano = ($this->ob16_dtini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ob16_dtini_ano"]:$this->ob16_dtini_ano);
         if($this->ob16_dtini_dia != ""){
            $this->ob16_dtini = $this->ob16_dtini_ano."-".$this->ob16_dtini_mes."-".$this->ob16_dtini_dia;
         }
       }
       if($this->ob16_dtfim == ""){
         $this->ob16_dtfim_dia = ($this->ob16_dtfim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ob16_dtfim_dia"]:$this->ob16_dtfim_dia);
         $this->ob16_dtfim_mes = ($this->ob16_dtfim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ob16_dtfim_mes"]:$this->ob16_dtfim_mes);
         $this->ob16_dtfim_ano = ($this->ob16_dtfim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ob16_dtfim_ano"]:$this->ob16_dtfim_ano);
         if($this->ob16_dtfim_dia != ""){
            $this->ob16_dtfim = $this->ob16_dtfim_ano."-".$this->ob16_dtfim_mes."-".$this->ob16_dtfim_dia;
         }
       }
       $this->ob16_nomearq = ($this->ob16_nomearq == ""?@$GLOBALS["HTTP_POST_VARS"]["ob16_nomearq"]:$this->ob16_nomearq);
       $this->ob16_arq = ($this->ob16_arq == ""?@$GLOBALS["HTTP_POST_VARS"]["ob16_arq"]:$this->ob16_arq);
     }else{
       $this->ob16_codobrasenvio = ($this->ob16_codobrasenvio == ""?@$GLOBALS["HTTP_POST_VARS"]["ob16_codobrasenvio"]:$this->ob16_codobrasenvio);
     }
   }
   // funcao para inclusao
   function incluir ($ob16_codobrasenvio){ 
      $this->atualizacampos();
     if($this->ob16_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "ob16_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob16_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "ob16_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob16_login == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "ob16_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob16_dtini == null ){ 
       $this->erro_sql = " Campo Data inicial da geração nao Informado.";
       $this->erro_campo = "ob16_dtini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob16_dtfim == null ){ 
       $this->erro_sql = " Campo Data final da geração nao Informado.";
       $this->erro_campo = "ob16_dtfim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob16_nomearq == null ){ 
       $this->erro_sql = " Campo Nome do arquivo da geração nao Informado.";
       $this->erro_campo = "ob16_nomearq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ob16_codobrasenvio == "" || $ob16_codobrasenvio == null ){
       $result = db_query("select nextval('obrasenvio_ob16_codobrasenvio_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: obrasenvio_ob16_codobrasenvio_seq do campo: ob16_codobrasenvio"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ob16_codobrasenvio = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from obrasenvio_ob16_codobrasenvio_seq");
       if(($result != false) && (pg_result($result,0,0) < $ob16_codobrasenvio)){
         $this->erro_sql = " Campo ob16_codobrasenvio maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ob16_codobrasenvio = $ob16_codobrasenvio; 
       }
     }
     if(($this->ob16_codobrasenvio == null) || ($this->ob16_codobrasenvio == "") ){ 
       $this->erro_sql = " Campo ob16_codobrasenvio nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into obrasenvio(
                                       ob16_codobrasenvio 
                                      ,ob16_data 
                                      ,ob16_hora 
                                      ,ob16_login 
                                      ,ob16_dtini 
                                      ,ob16_dtfim 
                                      ,ob16_nomearq 
                                      ,ob16_arq 
                       )
                values (
                                $this->ob16_codobrasenvio 
                               ,".($this->ob16_data == "null" || $this->ob16_data == ""?"null":"'".$this->ob16_data."'")." 
                               ,'$this->ob16_hora' 
                               ,$this->ob16_login 
                               ,".($this->ob16_dtini == "null" || $this->ob16_dtini == ""?"null":"'".$this->ob16_dtini."'")." 
                               ,".($this->ob16_dtfim == "null" || $this->ob16_dtfim == ""?"null":"'".$this->ob16_dtfim."'")." 
                               ,'$this->ob16_nomearq' 
                               ,'$this->ob16_arq' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Envio de Obras ($this->ob16_codobrasenvio) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Envio de Obras já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Envio de Obras ($this->ob16_codobrasenvio) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ob16_codobrasenvio;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ob16_codobrasenvio));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6430,'$this->ob16_codobrasenvio','I')");
       $resac = db_query("insert into db_acount values($acount,1055,6430,'','".AddSlashes(pg_result($resaco,0,'ob16_codobrasenvio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1055,6426,'','".AddSlashes(pg_result($resaco,0,'ob16_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1055,6427,'','".AddSlashes(pg_result($resaco,0,'ob16_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1055,6429,'','".AddSlashes(pg_result($resaco,0,'ob16_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1055,6438,'','".AddSlashes(pg_result($resaco,0,'ob16_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1055,6439,'','".AddSlashes(pg_result($resaco,0,'ob16_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1055,6440,'','".AddSlashes(pg_result($resaco,0,'ob16_nomearq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1055,6441,'','".AddSlashes(pg_result($resaco,0,'ob16_arq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ob16_codobrasenvio=null) { 
      $this->atualizacampos();
     $sql = " update obrasenvio set ";
     $virgula = "";
     if(trim($this->ob16_codobrasenvio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob16_codobrasenvio"])){ 
       $sql  .= $virgula." ob16_codobrasenvio = $this->ob16_codobrasenvio ";
       $virgula = ",";
       if(trim($this->ob16_codobrasenvio) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ob16_codobrasenvio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob16_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob16_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ob16_data_dia"] !="") ){ 
       $sql  .= $virgula." ob16_data = '$this->ob16_data' ";
       $virgula = ",";
       if(trim($this->ob16_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "ob16_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ob16_data_dia"])){ 
         $sql  .= $virgula." ob16_data = null ";
         $virgula = ",";
         if(trim($this->ob16_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "ob16_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ob16_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob16_hora"])){ 
       $sql  .= $virgula." ob16_hora = '$this->ob16_hora' ";
       $virgula = ",";
       if(trim($this->ob16_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "ob16_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob16_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob16_login"])){ 
       $sql  .= $virgula." ob16_login = $this->ob16_login ";
       $virgula = ",";
       if(trim($this->ob16_login) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "ob16_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob16_dtini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob16_dtini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ob16_dtini_dia"] !="") ){ 
       $sql  .= $virgula." ob16_dtini = '$this->ob16_dtini' ";
       $virgula = ",";
       if(trim($this->ob16_dtini) == null ){ 
         $this->erro_sql = " Campo Data inicial da geração nao Informado.";
         $this->erro_campo = "ob16_dtini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ob16_dtini_dia"])){ 
         $sql  .= $virgula." ob16_dtini = null ";
         $virgula = ",";
         if(trim($this->ob16_dtini) == null ){ 
           $this->erro_sql = " Campo Data inicial da geração nao Informado.";
           $this->erro_campo = "ob16_dtini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ob16_dtfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob16_dtfim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ob16_dtfim_dia"] !="") ){ 
       $sql  .= $virgula." ob16_dtfim = '$this->ob16_dtfim' ";
       $virgula = ",";
       if(trim($this->ob16_dtfim) == null ){ 
         $this->erro_sql = " Campo Data final da geração nao Informado.";
         $this->erro_campo = "ob16_dtfim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ob16_dtfim_dia"])){ 
         $sql  .= $virgula." ob16_dtfim = null ";
         $virgula = ",";
         if(trim($this->ob16_dtfim) == null ){ 
           $this->erro_sql = " Campo Data final da geração nao Informado.";
           $this->erro_campo = "ob16_dtfim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ob16_nomearq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob16_nomearq"])){ 
       $sql  .= $virgula." ob16_nomearq = '$this->ob16_nomearq' ";
       $virgula = ",";
       if(trim($this->ob16_nomearq) == null ){ 
         $this->erro_sql = " Campo Nome do arquivo da geração nao Informado.";
         $this->erro_campo = "ob16_nomearq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob16_arq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob16_arq"])){ 
       $sql  .= $virgula." ob16_arq = '$this->ob16_arq' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ob16_codobrasenvio!=null){
       $sql .= " ob16_codobrasenvio = $this->ob16_codobrasenvio";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ob16_codobrasenvio));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6430,'$this->ob16_codobrasenvio','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob16_codobrasenvio"]))
           $resac = db_query("insert into db_acount values($acount,1055,6430,'".AddSlashes(pg_result($resaco,$conresaco,'ob16_codobrasenvio'))."','$this->ob16_codobrasenvio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob16_data"]))
           $resac = db_query("insert into db_acount values($acount,1055,6426,'".AddSlashes(pg_result($resaco,$conresaco,'ob16_data'))."','$this->ob16_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob16_hora"]))
           $resac = db_query("insert into db_acount values($acount,1055,6427,'".AddSlashes(pg_result($resaco,$conresaco,'ob16_hora'))."','$this->ob16_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob16_login"]))
           $resac = db_query("insert into db_acount values($acount,1055,6429,'".AddSlashes(pg_result($resaco,$conresaco,'ob16_login'))."','$this->ob16_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob16_dtini"]))
           $resac = db_query("insert into db_acount values($acount,1055,6438,'".AddSlashes(pg_result($resaco,$conresaco,'ob16_dtini'))."','$this->ob16_dtini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob16_dtfim"]))
           $resac = db_query("insert into db_acount values($acount,1055,6439,'".AddSlashes(pg_result($resaco,$conresaco,'ob16_dtfim'))."','$this->ob16_dtfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob16_nomearq"]))
           $resac = db_query("insert into db_acount values($acount,1055,6440,'".AddSlashes(pg_result($resaco,$conresaco,'ob16_nomearq'))."','$this->ob16_nomearq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob16_arq"]))
           $resac = db_query("insert into db_acount values($acount,1055,6441,'".AddSlashes(pg_result($resaco,$conresaco,'ob16_arq'))."','$this->ob16_arq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Envio de Obras nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ob16_codobrasenvio;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Envio de Obras nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ob16_codobrasenvio;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ob16_codobrasenvio;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ob16_codobrasenvio=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ob16_codobrasenvio));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6430,'$ob16_codobrasenvio','E')");
         $resac = db_query("insert into db_acount values($acount,1055,6430,'','".AddSlashes(pg_result($resaco,$iresaco,'ob16_codobrasenvio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1055,6426,'','".AddSlashes(pg_result($resaco,$iresaco,'ob16_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1055,6427,'','".AddSlashes(pg_result($resaco,$iresaco,'ob16_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1055,6429,'','".AddSlashes(pg_result($resaco,$iresaco,'ob16_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1055,6438,'','".AddSlashes(pg_result($resaco,$iresaco,'ob16_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1055,6439,'','".AddSlashes(pg_result($resaco,$iresaco,'ob16_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1055,6440,'','".AddSlashes(pg_result($resaco,$iresaco,'ob16_nomearq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1055,6441,'','".AddSlashes(pg_result($resaco,$iresaco,'ob16_arq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from obrasenvio
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ob16_codobrasenvio != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ob16_codobrasenvio = $ob16_codobrasenvio ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Envio de Obras nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ob16_codobrasenvio;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Envio de Obras nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ob16_codobrasenvio;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ob16_codobrasenvio;
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
        $this->erro_sql   = "Record Vazio na Tabela:obrasenvio";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ob16_codobrasenvio=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from obrasenvio ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = obrasenvio.ob16_login";
     $sql2 = "";
     if($dbwhere==""){
       if($ob16_codobrasenvio!=null ){
         $sql2 .= " where obrasenvio.ob16_codobrasenvio = $ob16_codobrasenvio "; 
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
   function sql_query_file ( $ob16_codobrasenvio=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from obrasenvio ";
     $sql2 = "";
     if($dbwhere==""){
       if($ob16_codobrasenvio!=null ){
         $sql2 .= " where obrasenvio.ob16_codobrasenvio = $ob16_codobrasenvio "; 
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
}
?>