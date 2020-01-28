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

//MODULO: material
//CLASSE DA ENTIDADE posicaoestoqueprocessamento
class cl_posicaoestoqueprocessamento { 
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
   var $m05_sequencial = 0; 
   var $m05_usuario = 0; 
   var $m05_data_dia = null; 
   var $m05_data_mes = null; 
   var $m05_data_ano = null; 
   var $m05_data = null; 
   var $m05_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m05_sequencial = int4 = Sequencial do Processamento 
                 m05_usuario = int4 = Código do Usuário 
                 m05_data = date = Data do Processamento 
                 m05_instit = int4 = Instituição 
                 ";
   //funcao construtor da classe 
   function cl_posicaoestoqueprocessamento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("posicaoestoqueprocessamento"); 
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
       $this->m05_sequencial = ($this->m05_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m05_sequencial"]:$this->m05_sequencial);
       $this->m05_usuario = ($this->m05_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["m05_usuario"]:$this->m05_usuario);
       if($this->m05_data == ""){
         $this->m05_data_dia = ($this->m05_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["m05_data_dia"]:$this->m05_data_dia);
         $this->m05_data_mes = ($this->m05_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["m05_data_mes"]:$this->m05_data_mes);
         $this->m05_data_ano = ($this->m05_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["m05_data_ano"]:$this->m05_data_ano);
         if($this->m05_data_dia != ""){
            $this->m05_data = $this->m05_data_ano."-".$this->m05_data_mes."-".$this->m05_data_dia;
         }
       }
       $this->m05_instit = ($this->m05_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["m05_instit"]:$this->m05_instit);
     }else{
       $this->m05_sequencial = ($this->m05_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m05_sequencial"]:$this->m05_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($m05_sequencial){ 
      $this->atualizacampos();
     if($this->m05_usuario == null ){ 
       $this->erro_sql = " Campo Código do Usuário não informado.";
       $this->erro_campo = "m05_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m05_data == null ){ 
       $this->erro_sql = " Campo Data do Processamento não informado.";
       $this->erro_campo = "m05_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m05_instit == null ){ 
       $this->erro_sql = " Campo Instituição não informado.";
       $this->erro_campo = "m05_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m05_sequencial == "" || $m05_sequencial == null ){
       $result = db_query("select nextval('posicaoestoqueprocessamento_m05_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: posicaoestoqueprocessamento_m05_sequencial_seq do campo: m05_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m05_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from posicaoestoqueprocessamento_m05_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $m05_sequencial)){
         $this->erro_sql = " Campo m05_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m05_sequencial = $m05_sequencial; 
       }
     }
     if(($this->m05_sequencial == null) || ($this->m05_sequencial == "") ){ 
       $this->erro_sql = " Campo m05_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into posicaoestoqueprocessamento(
                                       m05_sequencial 
                                      ,m05_usuario 
                                      ,m05_data 
                                      ,m05_instit 
                       )
                values (
                                $this->m05_sequencial 
                               ,$this->m05_usuario 
                               ,".($this->m05_data == "null" || $this->m05_data == ""?"null":"'".$this->m05_data."'")." 
                               ,$this->m05_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "posicaoestoqueprocessamento ($this->m05_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "posicaoestoqueprocessamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "posicaoestoqueprocessamento ($this->m05_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m05_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->m05_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20397,'$this->m05_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3664,20397,'','".AddSlashes(pg_result($resaco,0,'m05_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3664,20398,'','".AddSlashes(pg_result($resaco,0,'m05_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3664,20399,'','".AddSlashes(pg_result($resaco,0,'m05_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3664,20404,'','".AddSlashes(pg_result($resaco,0,'m05_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m05_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update posicaoestoqueprocessamento set ";
     $virgula = "";
     if(trim($this->m05_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m05_sequencial"])){ 
       $sql  .= $virgula." m05_sequencial = $this->m05_sequencial ";
       $virgula = ",";
       if(trim($this->m05_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial do Processamento não informado.";
         $this->erro_campo = "m05_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m05_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m05_usuario"])){ 
       $sql  .= $virgula." m05_usuario = $this->m05_usuario ";
       $virgula = ",";
       if(trim($this->m05_usuario) == null ){ 
         $this->erro_sql = " Campo Código do Usuário não informado.";
         $this->erro_campo = "m05_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m05_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m05_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["m05_data_dia"] !="") ){ 
       $sql  .= $virgula." m05_data = '$this->m05_data' ";
       $virgula = ",";
       if(trim($this->m05_data) == null ){ 
         $this->erro_sql = " Campo Data do Processamento não informado.";
         $this->erro_campo = "m05_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["m05_data_dia"])){ 
         $sql  .= $virgula." m05_data = null ";
         $virgula = ",";
         if(trim($this->m05_data) == null ){ 
           $this->erro_sql = " Campo Data do Processamento não informado.";
           $this->erro_campo = "m05_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->m05_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m05_instit"])){ 
       $sql  .= $virgula." m05_instit = $this->m05_instit ";
       $virgula = ",";
       if(trim($this->m05_instit) == null ){ 
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "m05_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m05_sequencial!=null){
       $sql .= " m05_sequencial = $this->m05_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->m05_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20397,'$this->m05_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["m05_sequencial"]) || $this->m05_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3664,20397,'".AddSlashes(pg_result($resaco,$conresaco,'m05_sequencial'))."','$this->m05_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["m05_usuario"]) || $this->m05_usuario != "")
             $resac = db_query("insert into db_acount values($acount,3664,20398,'".AddSlashes(pg_result($resaco,$conresaco,'m05_usuario'))."','$this->m05_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["m05_data"]) || $this->m05_data != "")
             $resac = db_query("insert into db_acount values($acount,3664,20399,'".AddSlashes(pg_result($resaco,$conresaco,'m05_data'))."','$this->m05_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["m05_instit"]) || $this->m05_instit != "")
             $resac = db_query("insert into db_acount values($acount,3664,20404,'".AddSlashes(pg_result($resaco,$conresaco,'m05_instit'))."','$this->m05_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "posicaoestoqueprocessamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m05_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "posicaoestoqueprocessamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m05_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m05_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m05_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($m05_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20397,'$m05_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3664,20397,'','".AddSlashes(pg_result($resaco,$iresaco,'m05_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3664,20398,'','".AddSlashes(pg_result($resaco,$iresaco,'m05_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3664,20399,'','".AddSlashes(pg_result($resaco,$iresaco,'m05_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3664,20404,'','".AddSlashes(pg_result($resaco,$iresaco,'m05_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from posicaoestoqueprocessamento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m05_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m05_sequencial = $m05_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "posicaoestoqueprocessamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m05_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "posicaoestoqueprocessamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m05_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m05_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:posicaoestoqueprocessamento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $m05_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from posicaoestoqueprocessamento ";
     $sql .= "      inner join db_config  on  db_config.codigo = posicaoestoqueprocessamento.m05_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = posicaoestoqueprocessamento.m05_usuario";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if($dbwhere==""){
       if($m05_sequencial!=null ){
         $sql2 .= " where posicaoestoqueprocessamento.m05_sequencial = $m05_sequencial "; 
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
   function sql_query_file ( $m05_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from posicaoestoqueprocessamento ";
     $sql2 = "";
     if($dbwhere==""){
       if($m05_sequencial!=null ){
         $sql2 .= " where posicaoestoqueprocessamento.m05_sequencial = $m05_sequencial "; 
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

  function sql_query_posicaoestoque ( $m05_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from posicaoestoqueprocessamento ";
     $sql .= "      inner join db_config  on  db_config.codigo = posicaoestoqueprocessamento.m05_instit";
     $sql .= "      inner join posicaoestoque on posicaoestoque.m06_posicaoestoqueprocessamento = posicaoestoqueprocessamento.m05_sequencial";
     $sql2 = "";
     if($dbwhere==""){
       if($m05_sequencial!=null ){
         $sql2 .= " where posicaoestoqueprocessamento.m05_sequencial = $m05_sequencial "; 
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