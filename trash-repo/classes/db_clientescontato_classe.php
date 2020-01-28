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

//MODULO: Atendimento
//CLASSE DA ENTIDADE clientescontato
class cl_clientescontato { 
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
   var $at92_sequencial = 0; 
   var $at92_cliente = 0; 
   var $at92_cargo = null; 
   var $at92_nome = null; 
   var $at92_telefone = null; 
   var $at92_email = null; 
   var $at92_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 at92_sequencial = int4 = Sequencial 
                 at92_cliente = int4 = Cliente 
                 at92_cargo = varchar(100) = Cargo 
                 at92_nome = varchar(100) = Nome 
                 at92_telefone = varchar(11) = Telefone 
                 at92_email = varchar(100) = Email 
                 at92_obs = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_clientescontato() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("clientescontato"); 
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
       $this->at92_sequencial = ($this->at92_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["at92_sequencial"]:$this->at92_sequencial);
       $this->at92_cliente = ($this->at92_cliente == ""?@$GLOBALS["HTTP_POST_VARS"]["at92_cliente"]:$this->at92_cliente);
       $this->at92_cargo = ($this->at92_cargo == ""?@$GLOBALS["HTTP_POST_VARS"]["at92_cargo"]:$this->at92_cargo);
       $this->at92_nome = ($this->at92_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["at92_nome"]:$this->at92_nome);
       $this->at92_telefone = ($this->at92_telefone == ""?@$GLOBALS["HTTP_POST_VARS"]["at92_telefone"]:$this->at92_telefone);
       $this->at92_email = ($this->at92_email == ""?@$GLOBALS["HTTP_POST_VARS"]["at92_email"]:$this->at92_email);
       $this->at92_obs = ($this->at92_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["at92_obs"]:$this->at92_obs);
     }else{
       $this->at92_sequencial = ($this->at92_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["at92_sequencial"]:$this->at92_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($at92_sequencial){ 
      $this->atualizacampos();
     if($this->at92_cliente == null ){ 
       $this->erro_sql = " Campo Cliente nao Informado.";
       $this->erro_campo = "at92_cliente";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at92_cargo == null ){ 
       $this->erro_sql = " Campo Cargo nao Informado.";
       $this->erro_campo = "at92_cargo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at92_nome == null ){ 
       $this->erro_sql = " Campo Nome nao Informado.";
       $this->erro_campo = "at92_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($at92_sequencial == "" || $at92_sequencial == null ){
       $result = db_query("select nextval('clientescontato_at92_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: clientescontato_at92_sequencial_seq do campo: at92_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->at92_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from clientescontato_at92_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $at92_sequencial)){
         $this->erro_sql = " Campo at92_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->at92_sequencial = $at92_sequencial; 
       }
     }
     if(($this->at92_sequencial == null) || ($this->at92_sequencial == "") ){ 
       $this->erro_sql = " Campo at92_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into clientescontato(
                                       at92_sequencial 
                                      ,at92_cliente 
                                      ,at92_cargo 
                                      ,at92_nome 
                                      ,at92_telefone 
                                      ,at92_email 
                                      ,at92_obs 
                       )
                values (
                                $this->at92_sequencial 
                               ,$this->at92_cliente 
                               ,'$this->at92_cargo' 
                               ,'$this->at92_nome' 
                               ,'$this->at92_telefone' 
                               ,'$this->at92_email' 
                               ,'$this->at92_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Contato do Cliente ($this->at92_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Contato do Cliente já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Contato do Cliente ($this->at92_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at92_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->at92_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17053,'$this->at92_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3015,17053,'','".AddSlashes(pg_result($resaco,0,'at92_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3015,17054,'','".AddSlashes(pg_result($resaco,0,'at92_cliente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3015,17055,'','".AddSlashes(pg_result($resaco,0,'at92_cargo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3015,17056,'','".AddSlashes(pg_result($resaco,0,'at92_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3015,17057,'','".AddSlashes(pg_result($resaco,0,'at92_telefone'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3015,17058,'','".AddSlashes(pg_result($resaco,0,'at92_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3015,17059,'','".AddSlashes(pg_result($resaco,0,'at92_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($at92_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update clientescontato set ";
     $virgula = "";
     if(trim($this->at92_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at92_sequencial"])){ 
       $sql  .= $virgula." at92_sequencial = $this->at92_sequencial ";
       $virgula = ",";
       if(trim($this->at92_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "at92_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at92_cliente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at92_cliente"])){ 
       $sql  .= $virgula." at92_cliente = $this->at92_cliente ";
       $virgula = ",";
       if(trim($this->at92_cliente) == null ){ 
         $this->erro_sql = " Campo Cliente nao Informado.";
         $this->erro_campo = "at92_cliente";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at92_cargo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at92_cargo"])){ 
       $sql  .= $virgula." at92_cargo = '$this->at92_cargo' ";
       $virgula = ",";
       if(trim($this->at92_cargo) == null ){ 
         $this->erro_sql = " Campo Cargo nao Informado.";
         $this->erro_campo = "at92_cargo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at92_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at92_nome"])){ 
       $sql  .= $virgula." at92_nome = '$this->at92_nome' ";
       $virgula = ",";
       if(trim($this->at92_nome) == null ){ 
         $this->erro_sql = " Campo Nome nao Informado.";
         $this->erro_campo = "at92_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at92_telefone)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at92_telefone"])){ 
       $sql  .= $virgula." at92_telefone = '$this->at92_telefone' ";
       $virgula = ",";
     }
     if(trim($this->at92_email)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at92_email"])){ 
       $sql  .= $virgula." at92_email = '$this->at92_email' ";
       $virgula = ",";
     }
     if(trim($this->at92_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at92_obs"])){ 
       $sql  .= $virgula." at92_obs = '$this->at92_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($at92_sequencial!=null){
       $sql .= " at92_sequencial = $this->at92_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->at92_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17053,'$this->at92_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at92_sequencial"]) || $this->at92_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3015,17053,'".AddSlashes(pg_result($resaco,$conresaco,'at92_sequencial'))."','$this->at92_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at92_cliente"]) || $this->at92_cliente != "")
           $resac = db_query("insert into db_acount values($acount,3015,17054,'".AddSlashes(pg_result($resaco,$conresaco,'at92_cliente'))."','$this->at92_cliente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at92_cargo"]) || $this->at92_cargo != "")
           $resac = db_query("insert into db_acount values($acount,3015,17055,'".AddSlashes(pg_result($resaco,$conresaco,'at92_cargo'))."','$this->at92_cargo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at92_nome"]) || $this->at92_nome != "")
           $resac = db_query("insert into db_acount values($acount,3015,17056,'".AddSlashes(pg_result($resaco,$conresaco,'at92_nome'))."','$this->at92_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at92_telefone"]) || $this->at92_telefone != "")
           $resac = db_query("insert into db_acount values($acount,3015,17057,'".AddSlashes(pg_result($resaco,$conresaco,'at92_telefone'))."','$this->at92_telefone',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at92_email"]) || $this->at92_email != "")
           $resac = db_query("insert into db_acount values($acount,3015,17058,'".AddSlashes(pg_result($resaco,$conresaco,'at92_email'))."','$this->at92_email',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at92_obs"]) || $this->at92_obs != "")
           $resac = db_query("insert into db_acount values($acount,3015,17059,'".AddSlashes(pg_result($resaco,$conresaco,'at92_obs'))."','$this->at92_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Contato do Cliente nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->at92_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Contato do Cliente nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->at92_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at92_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($at92_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($at92_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17053,'$at92_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3015,17053,'','".AddSlashes(pg_result($resaco,$iresaco,'at92_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3015,17054,'','".AddSlashes(pg_result($resaco,$iresaco,'at92_cliente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3015,17055,'','".AddSlashes(pg_result($resaco,$iresaco,'at92_cargo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3015,17056,'','".AddSlashes(pg_result($resaco,$iresaco,'at92_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3015,17057,'','".AddSlashes(pg_result($resaco,$iresaco,'at92_telefone'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3015,17058,'','".AddSlashes(pg_result($resaco,$iresaco,'at92_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3015,17059,'','".AddSlashes(pg_result($resaco,$iresaco,'at92_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from clientescontato
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($at92_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at92_sequencial = $at92_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Contato do Cliente nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$at92_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Contato do Cliente nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$at92_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$at92_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:clientescontato";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $at92_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from clientescontato ";
     $sql .= "      inner join clientes  on  clientes.at01_codcli = clientescontato.at92_cliente";
     $sql2 = "";
     if($dbwhere==""){
       if($at92_sequencial!=null ){
         $sql2 .= " where clientescontato.at92_sequencial = $at92_sequencial "; 
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
   function sql_query_file ( $at92_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from clientescontato ";
     $sql2 = "";
     if($dbwhere==""){
       if($at92_sequencial!=null ){
         $sql2 .= " where clientescontato.at92_sequencial = $at92_sequencial "; 
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