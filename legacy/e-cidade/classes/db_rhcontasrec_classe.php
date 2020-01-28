<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
//CLASSE DA ENTIDADE rhcontasrec
class cl_rhcontasrec { 
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
   var $rh41_conta = 0; 
   var $rh41_codigo = 0; 
   var $rh41_instit = 0; 
   var $rh41_anousu = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh41_conta = int4 = Código Conta 
                 rh41_codigo = int4 = Recurso 
                 rh41_instit = int4 = Instituição 
                 rh41_anousu = int4 = Ano 
                 ";
   //funcao construtor da classe 
   function cl_rhcontasrec() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhcontasrec"); 
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
       $this->rh41_conta = ($this->rh41_conta == ""?@$GLOBALS["HTTP_POST_VARS"]["rh41_conta"]:$this->rh41_conta);
       $this->rh41_codigo = ($this->rh41_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh41_codigo"]:$this->rh41_codigo);
       $this->rh41_instit = ($this->rh41_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh41_instit"]:$this->rh41_instit);
       $this->rh41_anousu = ($this->rh41_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh41_anousu"]:$this->rh41_anousu);
     }else{
       $this->rh41_conta = ($this->rh41_conta == ""?@$GLOBALS["HTTP_POST_VARS"]["rh41_conta"]:$this->rh41_conta);
       $this->rh41_codigo = ($this->rh41_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh41_codigo"]:$this->rh41_codigo);
       $this->rh41_instit = ($this->rh41_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh41_instit"]:$this->rh41_instit);
       $this->rh41_anousu = ($this->rh41_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh41_anousu"]:$this->rh41_anousu);
     }
   }
   // funcao para inclusao
   function incluir ($rh41_conta,$rh41_codigo,$rh41_instit,$rh41_anousu){ 
      $this->atualizacampos();
       $this->rh41_conta = $rh41_conta; 
       $this->rh41_codigo = $rh41_codigo; 
       $this->rh41_instit = $rh41_instit; 
       $this->rh41_anousu = $rh41_anousu; 
     if(($this->rh41_conta == null) || ($this->rh41_conta == "") ){ 
       $this->erro_sql = " Campo rh41_conta nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->rh41_codigo == null) || ($this->rh41_codigo == "") ){ 
       $this->erro_sql = " Campo rh41_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->rh41_instit == null) || ($this->rh41_instit == "") ){ 
       $this->erro_sql = " Campo rh41_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->rh41_anousu == null) || ($this->rh41_anousu == "") ){ 
       $this->erro_sql = " Campo rh41_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhcontasrec(
                                       rh41_conta 
                                      ,rh41_codigo 
                                      ,rh41_instit 
                                      ,rh41_anousu 
                       )
                values (
                                $this->rh41_conta 
                               ,$this->rh41_codigo 
                               ,$this->rh41_instit 
                               ,$this->rh41_anousu 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Contas de recursos ($this->rh41_conta."-".$this->rh41_codigo."-".$this->rh41_instit."-".$this->rh41_anousu) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Contas de recursos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Contas de recursos ($this->rh41_conta."-".$this->rh41_codigo."-".$this->rh41_instit."-".$this->rh41_anousu) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh41_conta."-".$this->rh41_codigo."-".$this->rh41_instit."-".$this->rh41_anousu;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh41_conta,$this->rh41_codigo,$this->rh41_instit,$this->rh41_anousu  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7873,'$this->rh41_conta','I')");
         $resac = db_query("insert into db_acountkey values($acount,7874,'$this->rh41_codigo','I')");
         $resac = db_query("insert into db_acountkey values($acount,20473,'$this->rh41_instit','I')");
         $resac = db_query("insert into db_acountkey values($acount,20472,'$this->rh41_anousu','I')");
         $resac = db_query("insert into db_acount values($acount,1318,7873,'','".AddSlashes(pg_result($resaco,0,'rh41_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1318,7874,'','".AddSlashes(pg_result($resaco,0,'rh41_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1318,20473,'','".AddSlashes(pg_result($resaco,0,'rh41_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1318,20472,'','".AddSlashes(pg_result($resaco,0,'rh41_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh41_conta=null,$rh41_codigo=null,$rh41_instit=null,$rh41_anousu=null) { 
      $this->atualizacampos();
     $sql = " update rhcontasrec set ";
     $virgula = "";
     if(trim($this->rh41_conta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh41_conta"])){ 
       $sql  .= $virgula." rh41_conta = $this->rh41_conta ";
       $virgula = ",";
       if(trim($this->rh41_conta) == null ){ 
         $this->erro_sql = " Campo Código Conta não informado.";
         $this->erro_campo = "rh41_conta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh41_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh41_codigo"])){ 
       $sql  .= $virgula." rh41_codigo = $this->rh41_codigo ";
       $virgula = ",";
       if(trim($this->rh41_codigo) == null ){ 
         $this->erro_sql = " Campo Recurso não informado.";
         $this->erro_campo = "rh41_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh41_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh41_instit"])){ 
       $sql  .= $virgula." rh41_instit = $this->rh41_instit ";
       $virgula = ",";
       if(trim($this->rh41_instit) == null ){ 
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "rh41_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh41_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh41_anousu"])){ 
       $sql  .= $virgula." rh41_anousu = $this->rh41_anousu ";
       $virgula = ",";
       if(trim($this->rh41_anousu) == null ){ 
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "rh41_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh41_conta!=null){
       $sql .= " rh41_conta = $this->rh41_conta";
     }
     if($rh41_codigo!=null){
       $sql .= " and  rh41_codigo = $this->rh41_codigo";
     }
     if($rh41_instit!=null){
       $sql .= " and  rh41_instit = $this->rh41_instit";
     }
     if($rh41_anousu!=null){
       $sql .= " and  rh41_anousu = $this->rh41_anousu";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh41_conta,$this->rh41_codigo,$this->rh41_instit,$this->rh41_anousu));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,7873,'$this->rh41_conta','A')");
           $resac = db_query("insert into db_acountkey values($acount,7874,'$this->rh41_codigo','A')");
           $resac = db_query("insert into db_acountkey values($acount,20473,'$this->rh41_instit','A')");
           $resac = db_query("insert into db_acountkey values($acount,20472,'$this->rh41_anousu','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh41_conta"]) || $this->rh41_conta != "")
             $resac = db_query("insert into db_acount values($acount,1318,7873,'".AddSlashes(pg_result($resaco,$conresaco,'rh41_conta'))."','$this->rh41_conta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh41_codigo"]) || $this->rh41_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1318,7874,'".AddSlashes(pg_result($resaco,$conresaco,'rh41_codigo'))."','$this->rh41_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh41_instit"]) || $this->rh41_instit != "")
             $resac = db_query("insert into db_acount values($acount,1318,20473,'".AddSlashes(pg_result($resaco,$conresaco,'rh41_instit'))."','$this->rh41_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh41_anousu"]) || $this->rh41_anousu != "")
             $resac = db_query("insert into db_acount values($acount,1318,20472,'".AddSlashes(pg_result($resaco,$conresaco,'rh41_anousu'))."','$this->rh41_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Contas de recursos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh41_conta."-".$this->rh41_codigo."-".$this->rh41_instit."-".$this->rh41_anousu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Contas de recursos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh41_conta."-".$this->rh41_codigo."-".$this->rh41_instit."-".$this->rh41_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh41_conta."-".$this->rh41_codigo."-".$this->rh41_instit."-".$this->rh41_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh41_conta=null,$rh41_codigo=null,$rh41_instit=null,$rh41_anousu=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($rh41_conta,$rh41_codigo,$rh41_instit,$rh41_anousu));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,7873,'$rh41_conta','E')");
           $resac  = db_query("insert into db_acountkey values($acount,7874,'$rh41_codigo','E')");
           $resac  = db_query("insert into db_acountkey values($acount,20473,'$rh41_instit','E')");
           $resac  = db_query("insert into db_acountkey values($acount,20472,'$rh41_anousu','E')");
           $resac  = db_query("insert into db_acount values($acount,1318,7873,'','".AddSlashes(pg_result($resaco,$iresaco,'rh41_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1318,7874,'','".AddSlashes(pg_result($resaco,$iresaco,'rh41_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1318,20473,'','".AddSlashes(pg_result($resaco,$iresaco,'rh41_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1318,20472,'','".AddSlashes(pg_result($resaco,$iresaco,'rh41_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhcontasrec
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh41_conta != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh41_conta = $rh41_conta ";
        }
        if($rh41_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh41_codigo = $rh41_codigo ";
        }
        if($rh41_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh41_instit = $rh41_instit ";
        }
        if($rh41_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh41_anousu = $rh41_anousu ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Contas de recursos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh41_conta."-".$rh41_codigo."-".$rh41_instit."-".$rh41_anousu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Contas de recursos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh41_conta."-".$rh41_codigo."-".$rh41_instit."-".$rh41_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh41_conta."-".$rh41_codigo."-".$rh41_instit."-".$rh41_anousu;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhcontasrec";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh41_conta=null,$rh41_codigo=null,$rh41_instit=null,$rh41_anousu=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhcontasrec ";
     $sql .= "      inner join db_config  on  db_config.codigo = rhcontasrec.rh41_instit";
     $sql .= "      inner join saltes  on  saltes.k13_conta = rhcontasrec.rh41_conta";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = rhcontasrec.rh41_codigo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql .= "      inner join db_estruturavalor  on  db_estruturavalor.db121_sequencial = orctiporec.o15_db_estruturavalor";
     $sql2 = "";
     if($dbwhere==""){
       if($rh41_conta!=null ){
         $sql2 .= " where rhcontasrec.rh41_conta = $rh41_conta "; 
       } 
       if($rh41_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhcontasrec.rh41_codigo = $rh41_codigo "; 
       } 
       if($rh41_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhcontasrec.rh41_instit = $rh41_instit "; 
       } 
       if($rh41_anousu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhcontasrec.rh41_anousu = $rh41_anousu "; 
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
   function sql_query_file ( $rh41_conta=null,$rh41_codigo=null,$rh41_instit=null,$rh41_anousu=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhcontasrec ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh41_conta!=null ){
         $sql2 .= " where rhcontasrec.rh41_conta = $rh41_conta "; 
       } 
       if($rh41_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhcontasrec.rh41_codigo = $rh41_codigo "; 
       } 
       if($rh41_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhcontasrec.rh41_instit = $rh41_instit "; 
       } 
       if($rh41_anousu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhcontasrec.rh41_anousu = $rh41_anousu "; 
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

  function sql_query_contas ( $rh41_conta=null,$rh41_codigo=null,$rh41_instit=null,$rh41_anousu=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhcontasrec ";
     $sql .= "      inner join db_config  on  db_config.codigo = rhcontasrec.rh41_instit";
     $sql .= "      inner join saltes  on  saltes.k13_conta = rhcontasrec.rh41_conta";
     $sql .= "      inner join conplanoreduz on conplanoreduz.c61_reduz = saltes.k13_reduz and c61_anousu=".db_getsession("DB_anousu");
     $sql .= "      inner join conplanoconta on conplanoconta.c63_codcon = conplanoreduz.c61_codcon and c63_anousu=c61_anousu ";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = rhcontasrec.rh41_codigo";
     $sql .= "      inner join db_bancos  on  db_bancos.db90_codban = conplanoconta.c63_banco ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql .= "      inner join db_estruturavalor  on  db_estruturavalor.db121_sequencial = orctiporec.o15_db_estruturavalor";

     $sql2 = "";
     if($dbwhere==""){
       if($rh41_conta!=null ){
         $sql2 .= " where rhcontasrec.rh41_conta = $rh41_conta "; 
       } 
       if($rh41_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhcontasrec.rh41_codigo = $rh41_codigo "; 
       } 
       if($rh41_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhcontasrec.rh41_instit = $rh41_instit "; 
       } 
       if($rh41_anousu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhcontasrec.rh41_anousu = $rh41_anousu "; 
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
