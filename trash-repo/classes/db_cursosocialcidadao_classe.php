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
//CLASSE DA ENTIDADE cursosocialcidadao
class cl_cursosocialcidadao { 
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
   var $as22_sequencial = 0; 
   var $as22_cursosocial = 0; 
   var $as22_cidadao = 0; 
   var $as22_cidadao_seq = 0; 
   var $as22_observacao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 as22_sequencial = int4 = Código 
                 as22_cursosocial = int4 = Curso 
                 as22_cidadao = int4 = Cidadão 
                 as22_cidadao_seq = int4 = Cidadão seq 
                 as22_observacao = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_cursosocialcidadao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cursosocialcidadao"); 
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
       $this->as22_sequencial = ($this->as22_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["as22_sequencial"]:$this->as22_sequencial);
       $this->as22_cursosocial = ($this->as22_cursosocial == ""?@$GLOBALS["HTTP_POST_VARS"]["as22_cursosocial"]:$this->as22_cursosocial);
       $this->as22_cidadao = ($this->as22_cidadao == ""?@$GLOBALS["HTTP_POST_VARS"]["as22_cidadao"]:$this->as22_cidadao);
       $this->as22_cidadao_seq = ($this->as22_cidadao_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["as22_cidadao_seq"]:$this->as22_cidadao_seq);
       $this->as22_observacao = ($this->as22_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["as22_observacao"]:$this->as22_observacao);
     }else{
       $this->as22_sequencial = ($this->as22_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["as22_sequencial"]:$this->as22_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($as22_sequencial){ 
      $this->atualizacampos();
     if($this->as22_cursosocial == null ){ 
       $this->erro_sql = " Campo Curso nao Informado.";
       $this->erro_campo = "as22_cursosocial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as22_cidadao == null ){ 
       $this->erro_sql = " Campo Cidadão nao Informado.";
       $this->erro_campo = "as22_cidadao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as22_cidadao_seq == null ){ 
       $this->erro_sql = " Campo Cidadão seq nao Informado.";
       $this->erro_campo = "as22_cidadao_seq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($as22_sequencial == "" || $as22_sequencial == null ){
       $result = db_query("select nextval('cursocialcidadao_as22_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cursocialcidadao_as22_sequencial_seq do campo: as22_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->as22_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cursocialcidadao_as22_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $as22_sequencial)){
         $this->erro_sql = " Campo as22_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->as22_sequencial = $as22_sequencial; 
       }
     }
     if(($this->as22_sequencial == null) || ($this->as22_sequencial == "") ){ 
       $this->erro_sql = " Campo as22_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cursosocialcidadao(
                                       as22_sequencial 
                                      ,as22_cursosocial 
                                      ,as22_cidadao 
                                      ,as22_cidadao_seq 
                                      ,as22_observacao 
                       )
                values (
                                $this->as22_sequencial 
                               ,$this->as22_cursosocial 
                               ,$this->as22_cidadao 
                               ,$this->as22_cidadao_seq 
                               ,'$this->as22_observacao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Curso Cidadão ($this->as22_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Curso Cidadão já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Curso Cidadão ($this->as22_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->as22_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->as22_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19976,'$this->as22_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3581,19976,'','".AddSlashes(pg_result($resaco,0,'as22_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3581,19977,'','".AddSlashes(pg_result($resaco,0,'as22_cursosocial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3581,19978,'','".AddSlashes(pg_result($resaco,0,'as22_cidadao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3581,19979,'','".AddSlashes(pg_result($resaco,0,'as22_cidadao_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3581,19980,'','".AddSlashes(pg_result($resaco,0,'as22_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($as22_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cursosocialcidadao set ";
     $virgula = "";
     if(trim($this->as22_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as22_sequencial"])){ 
       $sql  .= $virgula." as22_sequencial = $this->as22_sequencial ";
       $virgula = ",";
       if(trim($this->as22_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "as22_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as22_cursosocial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as22_cursosocial"])){ 
       $sql  .= $virgula." as22_cursosocial = $this->as22_cursosocial ";
       $virgula = ",";
       if(trim($this->as22_cursosocial) == null ){ 
         $this->erro_sql = " Campo Curso nao Informado.";
         $this->erro_campo = "as22_cursosocial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as22_cidadao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as22_cidadao"])){ 
       $sql  .= $virgula." as22_cidadao = $this->as22_cidadao ";
       $virgula = ",";
       if(trim($this->as22_cidadao) == null ){ 
         $this->erro_sql = " Campo Cidadão nao Informado.";
         $this->erro_campo = "as22_cidadao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as22_cidadao_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as22_cidadao_seq"])){ 
       $sql  .= $virgula." as22_cidadao_seq = $this->as22_cidadao_seq ";
       $virgula = ",";
       if(trim($this->as22_cidadao_seq) == null ){ 
         $this->erro_sql = " Campo Cidadão seq nao Informado.";
         $this->erro_campo = "as22_cidadao_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as22_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as22_observacao"])){ 
       $sql  .= $virgula." as22_observacao = '$this->as22_observacao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($as22_sequencial!=null){
       $sql .= " as22_sequencial = $this->as22_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->as22_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,19976,'$this->as22_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as22_sequencial"]) || $this->as22_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3581,19976,'".AddSlashes(pg_result($resaco,$conresaco,'as22_sequencial'))."','$this->as22_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as22_cursosocial"]) || $this->as22_cursosocial != "")
             $resac = db_query("insert into db_acount values($acount,3581,19977,'".AddSlashes(pg_result($resaco,$conresaco,'as22_cursosocial'))."','$this->as22_cursosocial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as22_cidadao"]) || $this->as22_cidadao != "")
             $resac = db_query("insert into db_acount values($acount,3581,19978,'".AddSlashes(pg_result($resaco,$conresaco,'as22_cidadao'))."','$this->as22_cidadao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as22_cidadao_seq"]) || $this->as22_cidadao_seq != "")
             $resac = db_query("insert into db_acount values($acount,3581,19979,'".AddSlashes(pg_result($resaco,$conresaco,'as22_cidadao_seq'))."','$this->as22_cidadao_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as22_observacao"]) || $this->as22_observacao != "")
             $resac = db_query("insert into db_acount values($acount,3581,19980,'".AddSlashes(pg_result($resaco,$conresaco,'as22_observacao'))."','$this->as22_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Curso Cidadão nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->as22_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Curso Cidadão nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->as22_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->as22_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($as22_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($as22_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,19976,'$as22_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3581,19976,'','".AddSlashes(pg_result($resaco,$iresaco,'as22_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3581,19977,'','".AddSlashes(pg_result($resaco,$iresaco,'as22_cursosocial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3581,19978,'','".AddSlashes(pg_result($resaco,$iresaco,'as22_cidadao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3581,19979,'','".AddSlashes(pg_result($resaco,$iresaco,'as22_cidadao_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3581,19980,'','".AddSlashes(pg_result($resaco,$iresaco,'as22_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from cursosocialcidadao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($as22_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " as22_sequencial = $as22_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Curso Cidadão nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$as22_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Curso Cidadão nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$as22_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$as22_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cursosocialcidadao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $as22_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cursosocialcidadao ";
     $sql .= "      inner join cidadao  on  cidadao.ov02_sequencial = cursosocialcidadao.as22_cidadao and  cidadao.ov02_seq = cursosocialcidadao.as22_cidadao_seq";
     $sql .= "      inner join cursosocial  on  cursosocial.as19_sequencial = cursosocialcidadao.as22_cursosocial";
     $sql .= "      inner join situacaocidadao  on  situacaocidadao.ov16_sequencial = cidadao.ov02_situacaocidadao";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = cursosocial.as19_ministrante";
     $sql .= "      inner join tabcurritipo  on  tabcurritipo.h02_codigo = cursosocial.as19_tabcurritipo";
     $sql2 = "";
     if($dbwhere==""){
       if($as22_sequencial!=null ){
         $sql2 .= " where cursosocialcidadao.as22_sequencial = $as22_sequencial "; 
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
   function sql_query_file ( $as22_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cursosocialcidadao ";
     $sql2 = "";
     if($dbwhere==""){
       if($as22_sequencial!=null ){
         $sql2 .= " where cursosocialcidadao.as22_sequencial = $as22_sequencial "; 
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
   function sql_query_cursocidadao ( $as22_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {
     
    $sql = "select ";
    if ($campos != "*" ) {
      
      $campos_sql = split("#",$campos);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from cursosocialcidadao ";
    $sql .= "      inner join cidadao          on cidadao.ov02_sequencial         = cursosocialcidadao.as22_cidadao"; 
    $sql .= "                                 and cidadao.ov02_seq                = cursosocialcidadao.as22_cidadao_seq";
    $sql .= "      inner join situacaocidadao  on situacaocidadao.ov16_sequencial = cidadao.ov02_situacaocidadao";
    $sql .= "      inner join cursosocial      on cursosocial.as19_sequencial     = cursosocialcidadao.as22_cursosocial";
    $sql2 = "";
    
    if ($dbwhere == "") {
      
      if ($as22_sequencial != null ) {
        $sql2 .= " where cursosocialcidadao.as22_sequencial = $as22_sequencial ";
      }
    } else if($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {
      
      $sql        .= " order by ";
      $campos_sql  = split("#",$ordem);
      $virgula     = "";
      for($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    }
    return $sql;
}
}
?>