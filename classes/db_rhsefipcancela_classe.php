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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhsefipcancela
class cl_rhsefipcancela { 
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
   var $rh91_sequencial = 0; 
   var $rh91_rhsefip = 0; 
   var $rh91_id_usuario = 0; 
   var $rh91_data_dia = null; 
   var $rh91_data_mes = null; 
   var $rh91_data_ano = null; 
   var $rh91_data = null; 
   var $rh91_hora = null; 
   var $rh91_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh91_sequencial = int4 = Sequencial 
                 rh91_rhsefip = int4 = Geração da SEFIP 
                 rh91_id_usuario = int4 = Usuário 
                 rh91_data = date = Data Cancelamento 
                 rh91_hora = char(5) = Hora Cancelamento 
                 rh91_obs = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_rhsefipcancela() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhsefipcancela"); 
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
       $this->rh91_sequencial = ($this->rh91_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh91_sequencial"]:$this->rh91_sequencial);
       $this->rh91_rhsefip = ($this->rh91_rhsefip == ""?@$GLOBALS["HTTP_POST_VARS"]["rh91_rhsefip"]:$this->rh91_rhsefip);
       $this->rh91_id_usuario = ($this->rh91_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["rh91_id_usuario"]:$this->rh91_id_usuario);
       if($this->rh91_data == ""){
         $this->rh91_data_dia = ($this->rh91_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh91_data_dia"]:$this->rh91_data_dia);
         $this->rh91_data_mes = ($this->rh91_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh91_data_mes"]:$this->rh91_data_mes);
         $this->rh91_data_ano = ($this->rh91_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh91_data_ano"]:$this->rh91_data_ano);
         if($this->rh91_data_dia != ""){
            $this->rh91_data = $this->rh91_data_ano."-".$this->rh91_data_mes."-".$this->rh91_data_dia;
         }
       }
       $this->rh91_hora = ($this->rh91_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["rh91_hora"]:$this->rh91_hora);
       $this->rh91_obs = ($this->rh91_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["rh91_obs"]:$this->rh91_obs);
     }else{
       $this->rh91_sequencial = ($this->rh91_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh91_sequencial"]:$this->rh91_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh91_sequencial){ 
      $this->atualizacampos();
     if($this->rh91_rhsefip == null ){ 
       $this->erro_sql = " Campo Geração da SEFIP nao Informado.";
       $this->erro_campo = "rh91_rhsefip";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh91_id_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "rh91_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh91_data == null ){ 
       $this->erro_sql = " Campo Data Cancelamento nao Informado.";
       $this->erro_campo = "rh91_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh91_hora == null ){ 
       $this->erro_sql = " Campo Hora Cancelamento nao Informado.";
       $this->erro_campo = "rh91_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh91_sequencial == "" || $rh91_sequencial == null ){
       $result = db_query("select nextval('rhsefipcancela_rh91_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhsefipcancela_rh91_sequencial_seq do campo: rh91_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh91_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhsefipcancela_rh91_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh91_sequencial)){
         $this->erro_sql = " Campo rh91_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh91_sequencial = $rh91_sequencial; 
       }
     }
     if(($this->rh91_sequencial == null) || ($this->rh91_sequencial == "") ){ 
       $this->erro_sql = " Campo rh91_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhsefipcancela(
                                       rh91_sequencial 
                                      ,rh91_rhsefip 
                                      ,rh91_id_usuario 
                                      ,rh91_data 
                                      ,rh91_hora 
                                      ,rh91_obs 
                       )
                values (
                                $this->rh91_sequencial 
                               ,$this->rh91_rhsefip 
                               ,$this->rh91_id_usuario 
                               ,".($this->rh91_data == "null" || $this->rh91_data == ""?"null":"'".$this->rh91_data."'")." 
                               ,'$this->rh91_hora' 
                               ,'$this->rh91_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cancelamento da Geração do SEFIP ($this->rh91_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cancelamento da Geração do SEFIP já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cancelamento da Geração do SEFIP ($this->rh91_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh91_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh91_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17548,'$this->rh91_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3099,17548,'','".AddSlashes(pg_result($resaco,0,'rh91_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3099,17549,'','".AddSlashes(pg_result($resaco,0,'rh91_rhsefip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3099,17550,'','".AddSlashes(pg_result($resaco,0,'rh91_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3099,17551,'','".AddSlashes(pg_result($resaco,0,'rh91_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3099,17552,'','".AddSlashes(pg_result($resaco,0,'rh91_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3099,17553,'','".AddSlashes(pg_result($resaco,0,'rh91_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh91_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhsefipcancela set ";
     $virgula = "";
     if(trim($this->rh91_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh91_sequencial"])){ 
       $sql  .= $virgula." rh91_sequencial = $this->rh91_sequencial ";
       $virgula = ",";
       if(trim($this->rh91_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "rh91_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh91_rhsefip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh91_rhsefip"])){ 
       $sql  .= $virgula." rh91_rhsefip = $this->rh91_rhsefip ";
       $virgula = ",";
       if(trim($this->rh91_rhsefip) == null ){ 
         $this->erro_sql = " Campo Geração da SEFIP nao Informado.";
         $this->erro_campo = "rh91_rhsefip";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh91_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh91_id_usuario"])){ 
       $sql  .= $virgula." rh91_id_usuario = $this->rh91_id_usuario ";
       $virgula = ",";
       if(trim($this->rh91_id_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "rh91_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh91_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh91_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh91_data_dia"] !="") ){ 
       $sql  .= $virgula." rh91_data = '$this->rh91_data' ";
       $virgula = ",";
       if(trim($this->rh91_data) == null ){ 
         $this->erro_sql = " Campo Data Cancelamento nao Informado.";
         $this->erro_campo = "rh91_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh91_data_dia"])){ 
         $sql  .= $virgula." rh91_data = null ";
         $virgula = ",";
         if(trim($this->rh91_data) == null ){ 
           $this->erro_sql = " Campo Data Cancelamento nao Informado.";
           $this->erro_campo = "rh91_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->rh91_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh91_hora"])){ 
       $sql  .= $virgula." rh91_hora = '$this->rh91_hora' ";
       $virgula = ",";
       if(trim($this->rh91_hora) == null ){ 
         $this->erro_sql = " Campo Hora Cancelamento nao Informado.";
         $this->erro_campo = "rh91_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh91_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh91_obs"])){ 
       $sql  .= $virgula." rh91_obs = '$this->rh91_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh91_sequencial!=null){
       $sql .= " rh91_sequencial = $this->rh91_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh91_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17548,'$this->rh91_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh91_sequencial"]) || $this->rh91_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3099,17548,'".AddSlashes(pg_result($resaco,$conresaco,'rh91_sequencial'))."','$this->rh91_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh91_rhsefip"]) || $this->rh91_rhsefip != "")
           $resac = db_query("insert into db_acount values($acount,3099,17549,'".AddSlashes(pg_result($resaco,$conresaco,'rh91_rhsefip'))."','$this->rh91_rhsefip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh91_id_usuario"]) || $this->rh91_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3099,17550,'".AddSlashes(pg_result($resaco,$conresaco,'rh91_id_usuario'))."','$this->rh91_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh91_data"]) || $this->rh91_data != "")
           $resac = db_query("insert into db_acount values($acount,3099,17551,'".AddSlashes(pg_result($resaco,$conresaco,'rh91_data'))."','$this->rh91_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh91_hora"]) || $this->rh91_hora != "")
           $resac = db_query("insert into db_acount values($acount,3099,17552,'".AddSlashes(pg_result($resaco,$conresaco,'rh91_hora'))."','$this->rh91_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh91_obs"]) || $this->rh91_obs != "")
           $resac = db_query("insert into db_acount values($acount,3099,17553,'".AddSlashes(pg_result($resaco,$conresaco,'rh91_obs'))."','$this->rh91_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cancelamento da Geração do SEFIP nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh91_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cancelamento da Geração do SEFIP nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh91_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh91_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh91_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh91_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17548,'$rh91_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3099,17548,'','".AddSlashes(pg_result($resaco,$iresaco,'rh91_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3099,17549,'','".AddSlashes(pg_result($resaco,$iresaco,'rh91_rhsefip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3099,17550,'','".AddSlashes(pg_result($resaco,$iresaco,'rh91_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3099,17551,'','".AddSlashes(pg_result($resaco,$iresaco,'rh91_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3099,17552,'','".AddSlashes(pg_result($resaco,$iresaco,'rh91_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3099,17553,'','".AddSlashes(pg_result($resaco,$iresaco,'rh91_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhsefipcancela
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh91_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh91_sequencial = $rh91_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cancelamento da Geração do SEFIP nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh91_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cancelamento da Geração do SEFIP nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh91_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh91_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhsefipcancela";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh91_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhsefipcancela ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = rhsefipcancela.rh91_id_usuario";
     $sql .= "      inner join rhsefip  on  rhsefip.rh90_sequencial = rhsefipcancela.rh91_rhsefip";
     $sql .= "      inner join db_config  on  db_config.codigo = rhsefip.rh90_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = rhsefip.rh90_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($rh91_sequencial!=null ){
         $sql2 .= " where rhsefipcancela.rh91_sequencial = $rh91_sequencial "; 
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
   // funcao do sql 
   function sql_query_file ( $rh91_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhsefipcancela ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh91_sequencial!=null ){
         $sql2 .= " where rhsefipcancela.rh91_sequencial = $rh91_sequencial "; 
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