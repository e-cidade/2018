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

//MODULO: social
//CLASSE DA ENTIDADE localatendimentosocial
class cl_localatendimentosocial { 
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
   var $as16_sequencial = 0; 
   var $as16_tipo = 0; 
   var $as16_descricao = null; 
   var $as16_identificadorunico = null; 
   var $as16_db_depart = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 as16_sequencial = int4 = Sequencial 
                 as16_tipo = int4 = Tipo 
                 as16_descricao = varchar(70) = Descrição 
                 as16_identificadorunico = varchar(20) = Identificar Único 
                 as16_db_depart = int4 = Depart. 
                 ";
   //funcao construtor da classe 
   function cl_localatendimentosocial() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("localatendimentosocial"); 
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
       $this->as16_sequencial = ($this->as16_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["as16_sequencial"]:$this->as16_sequencial);
       $this->as16_tipo = ($this->as16_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["as16_tipo"]:$this->as16_tipo);
       $this->as16_descricao = ($this->as16_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["as16_descricao"]:$this->as16_descricao);
       $this->as16_identificadorunico = ($this->as16_identificadorunico == ""?@$GLOBALS["HTTP_POST_VARS"]["as16_identificadorunico"]:$this->as16_identificadorunico);
       $this->as16_db_depart = ($this->as16_db_depart == ""?@$GLOBALS["HTTP_POST_VARS"]["as16_db_depart"]:$this->as16_db_depart);
     }else{
       $this->as16_sequencial = ($this->as16_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["as16_sequencial"]:$this->as16_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($as16_sequencial){ 
      $this->atualizacampos();
     if($this->as16_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "as16_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as16_descricao == null ){ 
       $this->as16_descricao = "null";
     }
     if($this->as16_db_depart == null ){ 
       $this->erro_sql = " Campo Depart. nao Informado.";
       $this->erro_campo = "as16_db_depart";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($as16_sequencial == "" || $as16_sequencial == null ){
       $result = db_query("select nextval('localatendimentosocial_as16_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: localatendimentosocial_as16_sequencial_seq do campo: as16_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->as16_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from localatendimentosocial_as16_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $as16_sequencial)){
         $this->erro_sql = " Campo as16_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->as16_sequencial = $as16_sequencial; 
       }
     }
     if(($this->as16_sequencial == null) || ($this->as16_sequencial == "") ){ 
       $this->erro_sql = " Campo as16_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into localatendimentosocial(
                                       as16_sequencial 
                                      ,as16_tipo 
                                      ,as16_descricao 
                                      ,as16_identificadorunico 
                                      ,as16_db_depart 
                       )
                values (
                                $this->as16_sequencial 
                               ,$this->as16_tipo 
                               ,'$this->as16_descricao' 
                               ,'$this->as16_identificadorunico' 
                               ,$this->as16_db_depart 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Local Atendimento Social ($this->as16_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Local Atendimento Social já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Local Atendimento Social ($this->as16_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->as16_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->as16_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19943,'$this->as16_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3574,19943,'','".AddSlashes(pg_result($resaco,0,'as16_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3574,19944,'','".AddSlashes(pg_result($resaco,0,'as16_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3574,19945,'','".AddSlashes(pg_result($resaco,0,'as16_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3574,19946,'','".AddSlashes(pg_result($resaco,0,'as16_identificadorunico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3574,19947,'','".AddSlashes(pg_result($resaco,0,'as16_db_depart'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($as16_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update localatendimentosocial set ";
     $virgula = "";
     if(trim($this->as16_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as16_sequencial"])){ 
       $sql  .= $virgula." as16_sequencial = $this->as16_sequencial ";
       $virgula = ",";
       if(trim($this->as16_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "as16_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as16_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as16_tipo"])){ 
       $sql  .= $virgula." as16_tipo = $this->as16_tipo ";
       $virgula = ",";
       if(trim($this->as16_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "as16_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as16_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as16_descricao"])){ 
       $sql  .= $virgula." as16_descricao = '$this->as16_descricao' ";
       $virgula = ",";
     }
     if(trim($this->as16_identificadorunico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as16_identificadorunico"])){ 
       $sql  .= $virgula." as16_identificadorunico = '$this->as16_identificadorunico' ";
       $virgula = ",";
     }
     if(trim($this->as16_db_depart)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as16_db_depart"])){ 
       $sql  .= $virgula." as16_db_depart = $this->as16_db_depart ";
       $virgula = ",";
       if(trim($this->as16_db_depart) == null ){ 
         $this->erro_sql = " Campo Depart. nao Informado.";
         $this->erro_campo = "as16_db_depart";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($as16_sequencial!=null){
       $sql .= " as16_sequencial = $this->as16_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->as16_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,19943,'$this->as16_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as16_sequencial"]) || $this->as16_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3574,19943,'".AddSlashes(pg_result($resaco,$conresaco,'as16_sequencial'))."','$this->as16_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as16_tipo"]) || $this->as16_tipo != "")
             $resac = db_query("insert into db_acount values($acount,3574,19944,'".AddSlashes(pg_result($resaco,$conresaco,'as16_tipo'))."','$this->as16_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as16_descricao"]) || $this->as16_descricao != "")
             $resac = db_query("insert into db_acount values($acount,3574,19945,'".AddSlashes(pg_result($resaco,$conresaco,'as16_descricao'))."','$this->as16_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as16_identificadorunico"]) || $this->as16_identificadorunico != "")
             $resac = db_query("insert into db_acount values($acount,3574,19946,'".AddSlashes(pg_result($resaco,$conresaco,'as16_identificadorunico'))."','$this->as16_identificadorunico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as16_db_depart"]) || $this->as16_db_depart != "")
             $resac = db_query("insert into db_acount values($acount,3574,19947,'".AddSlashes(pg_result($resaco,$conresaco,'as16_db_depart'))."','$this->as16_db_depart',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Local Atendimento Social nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->as16_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Local Atendimento Social nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->as16_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->as16_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($as16_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($as16_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,19943,'$as16_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3574,19943,'','".AddSlashes(pg_result($resaco,$iresaco,'as16_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3574,19944,'','".AddSlashes(pg_result($resaco,$iresaco,'as16_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3574,19945,'','".AddSlashes(pg_result($resaco,$iresaco,'as16_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3574,19946,'','".AddSlashes(pg_result($resaco,$iresaco,'as16_identificadorunico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3574,19947,'','".AddSlashes(pg_result($resaco,$iresaco,'as16_db_depart'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from localatendimentosocial
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($as16_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " as16_sequencial = $as16_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Local Atendimento Social nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$as16_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Local Atendimento Social nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$as16_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$as16_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:localatendimentosocial";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $as16_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from localatendimentosocial ";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = localatendimentosocial.as16_db_depart";
     $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
     $sql .= "      inner join db_datausuarios  on  db_datausuarios.id_usuario = db_depart.id_usuarioresp";
     $sql2 = "";
     if($dbwhere==""){
       if($as16_sequencial!=null ){
         $sql2 .= " where localatendimentosocial.as16_sequencial = $as16_sequencial "; 
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
   function sql_query_file ( $as16_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from localatendimentosocial ";
     $sql2 = "";
     if($dbwhere==""){
       if($as16_sequencial!=null ){
         $sql2 .= " where localatendimentosocial.as16_sequencial = $as16_sequencial "; 
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