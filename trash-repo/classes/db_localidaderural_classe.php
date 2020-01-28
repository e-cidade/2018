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

//MODULO: cadastro
//CLASSE DA ENTIDADE localidaderural
class cl_localidaderural { 
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
   var $j137_sequencial = 0; 
   var $j137_descricao = null; 
   var $j137_valorminimo = 0; 
   var $j137_valormaximo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j137_sequencial = int4 = Codigo sequencial 
                 j137_descricao = varchar(100) = Localidade 
                 j137_valorminimo = float8 = Valor Mínimo 
                 j137_valormaximo = float8 = Valor Máximo 
                 ";
   //funcao construtor da classe 
   function cl_localidaderural() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("localidaderural"); 
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
       $this->j137_sequencial = ($this->j137_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j137_sequencial"]:$this->j137_sequencial);
       $this->j137_descricao = ($this->j137_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["j137_descricao"]:$this->j137_descricao);
       $this->j137_valorminimo = ($this->j137_valorminimo == ""?@$GLOBALS["HTTP_POST_VARS"]["j137_valorminimo"]:$this->j137_valorminimo);
       $this->j137_valormaximo = ($this->j137_valormaximo == ""?@$GLOBALS["HTTP_POST_VARS"]["j137_valormaximo"]:$this->j137_valormaximo);
     }else{
       $this->j137_sequencial = ($this->j137_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j137_sequencial"]:$this->j137_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($j137_sequencial){ 
      $this->atualizacampos();
     if($this->j137_descricao == null ){ 
       $this->erro_sql = " Campo Localidade não informado.";
       $this->erro_campo = "j137_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j137_valorminimo == null ){ 
       $this->erro_sql = " Campo Valor Mínimo não informado.";
       $this->erro_campo = "j137_valorminimo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(!DBNumber::isFloat($this->j137_valorminimo)){
     	$this->erro_sql = " Campo Valor Mínimo deve ser preenchido somente com números!";
     	$this->erro_campo = "j137_valorminimo";
     	$this->erro_banco = "";
     	$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     	$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     	$this->erro_status = "0";
     	return false;
     }
     if($this->j137_valormaximo == null ){ 
       $this->erro_sql = " Campo Valor Máximo não informado.";
       $this->erro_campo = "j137_valormaximo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(!DBNumber::isFloat($this->j137_valormaximo)){
     	$this->erro_sql = " Campo Valor Máximo deve ser preenchido somente com números!";
     	$this->erro_campo = "j137_valormaximo";
     	$this->erro_banco = "";
     	$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     	$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     	$this->erro_status = "0";
     	return false;
     }
     if($j137_sequencial == "" || $j137_sequencial == null ){
       $result = db_query("select nextval('localidaderural_j137_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: localidaderural_j137_sequencial_seq do campo: j137_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j137_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from localidaderural_j137_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $j137_sequencial)){
         $this->erro_sql = " Campo j137_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j137_sequencial = $j137_sequencial; 
       }
     }
     if(($this->j137_sequencial == null) || ($this->j137_sequencial == "") ){ 
       $this->erro_sql = " Campo j137_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into localidaderural(
                                       j137_sequencial 
                                      ,j137_descricao 
                                      ,j137_valorminimo 
                                      ,j137_valormaximo 
                       )
                values (
                                $this->j137_sequencial 
                               ,'$this->j137_descricao' 
                               ,$this->j137_valorminimo 
                               ,$this->j137_valormaximo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de localidade rural ($this->j137_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cadastro de localidade rural já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de localidade rural ($this->j137_sequencial) não Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j137_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->j137_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20245,'$this->j137_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3637,20245,'','".AddSlashes(pg_result($resaco,0,'j137_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3637,20246,'','".AddSlashes(pg_result($resaco,0,'j137_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3637,20247,'','".AddSlashes(pg_result($resaco,0,'j137_valorminimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3637,20248,'','".AddSlashes(pg_result($resaco,0,'j137_valormaximo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j137_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update localidaderural set ";
     $virgula = "";
     if(trim($this->j137_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j137_sequencial"])){ 
       $sql  .= $virgula." j137_sequencial = $this->j137_sequencial ";
       $virgula = ",";
       if(trim($this->j137_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo sequencial não informado.";
         $this->erro_campo = "j137_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j137_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j137_descricao"])){ 
       $sql  .= $virgula." j137_descricao = '$this->j137_descricao' ";
       $virgula = ",";
       if(trim($this->j137_descricao) == null ){ 
         $this->erro_sql = " Campo Localidade não informado.";
         $this->erro_campo = "j137_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j137_valorminimo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j137_valorminimo"])){ 
       $sql  .= $virgula." j137_valorminimo = $this->j137_valorminimo ";
       $virgula = ",";
       if(trim($this->j137_valorminimo) == null ){ 
         $this->erro_sql = " Campo Valor Mínimo não informado.";
         $this->erro_campo = "j137_valorminimo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       if(!DBNumber::isFloat($this->j137_valorminimo)){
       	$this->erro_sql    = " Campo Valor Mínimo deve ser preenchido somente com números!";
       	$this->erro_campo  = "j137_valorminimo";
       	$this->erro_banco  = "";
       	$this->erro_msg    = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       	$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       	$this->erro_status = "0";
       	return false;
       }
     }
     if(trim($this->j137_valormaximo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j137_valormaximo"])){ 
       $sql    .= $virgula." j137_valormaximo = $this->j137_valormaximo ";
       $virgula = ",";
       if(trim($this->j137_valormaximo) == null ){ 
         $this->erro_sql    = " Campo Valor Máximo não informado.";
         $this->erro_campo  = "j137_valormaximo";
         $this->erro_banco  = "";
         $this->erro_msg    = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       if(!DBNumber::isFloat($this->j137_valormaximo)){
       	$this->erro_sql    = " Campo Valor Máximo deve ser preenchido somente com números!";
       	$this->erro_campo  = "j137_valormaximo";
       	$this->erro_banco  = "";
       	$this->erro_msg    = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       	$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       	$this->erro_status = "0";
       	return false;
       }
     }
     $sql .= " where ";
     if($j137_sequencial!=null){
       $sql .= " j137_sequencial = $this->j137_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->j137_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20245,'$this->j137_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["j137_sequencial"]) || $this->j137_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3637,20245,'".AddSlashes(pg_result($resaco,$conresaco,'j137_sequencial'))."','$this->j137_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["j137_descricao"]) || $this->j137_descricao != "")
             $resac = db_query("insert into db_acount values($acount,3637,20246,'".AddSlashes(pg_result($resaco,$conresaco,'j137_descricao'))."','$this->j137_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["j137_valorminimo"]) || $this->j137_valorminimo != "")
             $resac = db_query("insert into db_acount values($acount,3637,20247,'".AddSlashes(pg_result($resaco,$conresaco,'j137_valorminimo'))."','$this->j137_valorminimo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["j137_valormaximo"]) || $this->j137_valormaximo != "")
             $resac = db_query("insert into db_acount values($acount,3637,20248,'".AddSlashes(pg_result($resaco,$conresaco,'j137_valormaximo'))."','$this->j137_valormaximo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco      = str_replace("\n","",@pg_last_error());
       $this->erro_sql        = "Cadastro de localidade rural não Alterado. Alteração Abortada.\\n";
       $this->erro_sql       .= "Valores : ".$this->j137_sequencial;
       $this->erro_msg        = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg       .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status     = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco      = "";
         $this->erro_sql        = "Cadastro de localidade rural não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql       .= "Valores : ".$this->j137_sequencial;
         $this->erro_msg        = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg       .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status     = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j137_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j137_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($j137_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20245,'$j137_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3637,20245,'','".AddSlashes(pg_result($resaco,$iresaco,'j137_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3637,20246,'','".AddSlashes(pg_result($resaco,$iresaco,'j137_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3637,20247,'','".AddSlashes(pg_result($resaco,$iresaco,'j137_valorminimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3637,20248,'','".AddSlashes(pg_result($resaco,$iresaco,'j137_valormaximo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from localidaderural
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j137_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j137_sequencial = $j137_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de localidade rural não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j137_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de localidade rural não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j137_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j137_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:localidaderural";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $j137_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from localidaderural ";
     $sql2 = "";
     if($dbwhere==""){
       if($j137_sequencial!=null ){
         $sql2 .= " where localidaderural.j137_sequencial = $j137_sequencial "; 
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
   function sql_query_file ( $j137_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from localidaderural ";
     $sql2 = "";
     if($dbwhere==""){
       if($j137_sequencial!=null ){
         $sql2 .= " where localidaderural.j137_sequencial = $j137_sequencial "; 
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