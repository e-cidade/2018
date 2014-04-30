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

//MODULO: pessoal
//CLASSE DA ENTIDADE relrubrelrubcampos
class cl_relrubrelrubcampos { 
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
   var $rh121_sequencial = 0; 
   var $rh121_instit = 0; 
   var $rh121_relrub = 0; 
   var $rh121_relrubcampos = 0; 
   var $rh121_ordem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh121_sequencial = int4 = Sequencial 
                 rh121_instit = int4 = Instituição 
                 rh121_relrub = int4 = Relatorio Rubrica 
                 rh121_relrubcampos = int4 = Campos 
                 rh121_ordem = int4 = Ordem 
                 ";
   //funcao construtor da classe 
   function cl_relrubrelrubcampos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("relrubrelrubcampos"); 
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
       $this->rh121_sequencial = ($this->rh121_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh121_sequencial"]:$this->rh121_sequencial);
       $this->rh121_instit = ($this->rh121_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh121_instit"]:$this->rh121_instit);
       $this->rh121_relrub = ($this->rh121_relrub == ""?@$GLOBALS["HTTP_POST_VARS"]["rh121_relrub"]:$this->rh121_relrub);
       $this->rh121_relrubcampos = ($this->rh121_relrubcampos == ""?@$GLOBALS["HTTP_POST_VARS"]["rh121_relrubcampos"]:$this->rh121_relrubcampos);
       $this->rh121_ordem = ($this->rh121_ordem == ""?@$GLOBALS["HTTP_POST_VARS"]["rh121_ordem"]:$this->rh121_ordem);
     }else{
       $this->rh121_sequencial = ($this->rh121_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh121_sequencial"]:$this->rh121_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh121_sequencial){ 
      $this->atualizacampos();
     if($this->rh121_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "rh121_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh121_relrub == null ){ 
       $this->erro_sql = " Campo Relatorio Rubrica nao Informado.";
       $this->erro_campo = "rh121_relrub";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh121_relrubcampos == null ){ 
       $this->erro_sql = " Campo Campos nao Informado.";
       $this->erro_campo = "rh121_relrubcampos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh121_ordem == null ){ 
       $this->erro_sql = " Campo Ordem nao Informado.";
       $this->erro_campo = "rh121_ordem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh121_sequencial == "" || $rh121_sequencial == null ){
       $result = db_query("select nextval('relrubrelrubcampos_rh121_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: relrubrelrubcampos_rh121_sequencial_seq do campo: rh121_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh121_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from relrubrelrubcampos_rh121_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh121_sequencial)){
         $this->erro_sql = " Campo rh121_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh121_sequencial = $rh121_sequencial; 
       }
     }
     if(($this->rh121_sequencial == null) || ($this->rh121_sequencial == "") ){ 
       $this->erro_sql = " Campo rh121_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into relrubrelrubcampos(
                                       rh121_sequencial 
                                      ,rh121_instit 
                                      ,rh121_relrub 
                                      ,rh121_relrubcampos 
                                      ,rh121_ordem 
                       )
                values (
                                $this->rh121_sequencial 
                               ,$this->rh121_instit 
                               ,$this->rh121_relrub 
                               ,$this->rh121_relrubcampos 
                               ,$this->rh121_ordem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Relação do Relatorio de Rubricas com os campos ($this->rh121_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Relação do Relatorio de Rubricas com os campos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Relação do Relatorio de Rubricas com os campos ($this->rh121_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh121_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh121_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20038,'$this->rh121_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3590,20038,'','".AddSlashes(pg_result($resaco,0,'rh121_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3590,20042,'','".AddSlashes(pg_result($resaco,0,'rh121_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3590,20039,'','".AddSlashes(pg_result($resaco,0,'rh121_relrub'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3590,20040,'','".AddSlashes(pg_result($resaco,0,'rh121_relrubcampos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3590,20041,'','".AddSlashes(pg_result($resaco,0,'rh121_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh121_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update relrubrelrubcampos set ";
     $virgula = "";
     if(trim($this->rh121_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh121_sequencial"])){ 
       $sql  .= $virgula." rh121_sequencial = $this->rh121_sequencial ";
       $virgula = ",";
       if(trim($this->rh121_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "rh121_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh121_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh121_instit"])){ 
       $sql  .= $virgula." rh121_instit = $this->rh121_instit ";
       $virgula = ",";
       if(trim($this->rh121_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "rh121_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh121_relrub)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh121_relrub"])){ 
       $sql  .= $virgula." rh121_relrub = $this->rh121_relrub ";
       $virgula = ",";
       if(trim($this->rh121_relrub) == null ){ 
         $this->erro_sql = " Campo Relatorio Rubrica nao Informado.";
         $this->erro_campo = "rh121_relrub";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh121_relrubcampos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh121_relrubcampos"])){ 
       $sql  .= $virgula." rh121_relrubcampos = $this->rh121_relrubcampos ";
       $virgula = ",";
       if(trim($this->rh121_relrubcampos) == null ){ 
         $this->erro_sql = " Campo Campos nao Informado.";
         $this->erro_campo = "rh121_relrubcampos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh121_ordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh121_ordem"])){ 
       $sql  .= $virgula." rh121_ordem = $this->rh121_ordem ";
       $virgula = ",";
       if(trim($this->rh121_ordem) == null ){ 
         $this->erro_sql = " Campo Ordem nao Informado.";
         $this->erro_campo = "rh121_ordem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh121_sequencial!=null){
       $sql .= " rh121_sequencial = $this->rh121_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh121_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20038,'$this->rh121_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh121_sequencial"]) || $this->rh121_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3590,20038,'".AddSlashes(pg_result($resaco,$conresaco,'rh121_sequencial'))."','$this->rh121_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh121_instit"]) || $this->rh121_instit != "")
             $resac = db_query("insert into db_acount values($acount,3590,20042,'".AddSlashes(pg_result($resaco,$conresaco,'rh121_instit'))."','$this->rh121_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh121_relrub"]) || $this->rh121_relrub != "")
             $resac = db_query("insert into db_acount values($acount,3590,20039,'".AddSlashes(pg_result($resaco,$conresaco,'rh121_relrub'))."','$this->rh121_relrub',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh121_relrubcampos"]) || $this->rh121_relrubcampos != "")
             $resac = db_query("insert into db_acount values($acount,3590,20040,'".AddSlashes(pg_result($resaco,$conresaco,'rh121_relrubcampos'))."','$this->rh121_relrubcampos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh121_ordem"]) || $this->rh121_ordem != "")
             $resac = db_query("insert into db_acount values($acount,3590,20041,'".AddSlashes(pg_result($resaco,$conresaco,'rh121_ordem'))."','$this->rh121_ordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Relação do Relatorio de Rubricas com os campos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh121_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Relação do Relatorio de Rubricas com os campos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh121_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh121_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh121_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($rh121_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20038,'$rh121_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3590,20038,'','".AddSlashes(pg_result($resaco,$iresaco,'rh121_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3590,20042,'','".AddSlashes(pg_result($resaco,$iresaco,'rh121_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3590,20039,'','".AddSlashes(pg_result($resaco,$iresaco,'rh121_relrub'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3590,20040,'','".AddSlashes(pg_result($resaco,$iresaco,'rh121_relrubcampos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3590,20041,'','".AddSlashes(pg_result($resaco,$iresaco,'rh121_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from relrubrelrubcampos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh121_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh121_sequencial = $rh121_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Relação do Relatorio de Rubricas com os campos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh121_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Relação do Relatorio de Rubricas com os campos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh121_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh121_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:relrubrelrubcampos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh121_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from relrubrelrubcampos ";
     $sql .= "      inner join relrub  on  relrub.rh45_codigo = relrubrelrubcampos.rh121_relrub and  relrub.rh45_instit = relrubrelrubcampos.rh121_instit";
     $sql .= "      inner join relrubcampos  on  relrubcampos.rh120_sequencial = relrubrelrubcampos.rh121_relrubcampos";
     $sql .= "      inner join db_config  on  db_config.codigo = relrub.rh45_instit";
     $sql2 = "";
     if($dbwhere==""){
       if($rh121_sequencial!=null ){
         $sql2 .= " where relrubrelrubcampos.rh121_sequencial = $rh121_sequencial "; 
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
   function sql_query_file ( $rh121_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from relrubrelrubcampos ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh121_sequencial!=null ){
         $sql2 .= " where relrubrelrubcampos.rh121_sequencial = $rh121_sequencial "; 
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

  /**
   * Busca campos por relatorio
   *
   * @param string $sCampos
   * @param string $sWhere
   * @return string
   */
  public function sql_queryCamposPorRelatorio($sCampos = '*', $sOrdem = null, $sWhere = null) { 

    $sSql  = "select {$sCampos}                                                                                      ";
    $sSql .= " from relrubrelrubcampos                                                                               ";
    $sSql .= "      inner join relrub       on relrub.rh45_codigo            = relrubrelrubcampos.rh121_relrub       "; 
    $sSql .= "                             and relrub.rh45_instit            = relrubrelrubcampos.rh121_instit       ";
    $sSql .= "      inner join relrubcampos on relrubcampos.rh120_sequencial = relrubrelrubcampos.rh121_relrubcampos ";
    $sSql .= "      inner join db_syscampo  on nomecam                       = rh120_campo                           ";

    if ( !empty($sWhere) ) {
      $sSql .= " where {$sWhere} ";
    }

    if ( !empty($sOrdem) ) {
      $sSql .= " order by {$sOrdem} ";
    }

    return $sSql;
  }

}