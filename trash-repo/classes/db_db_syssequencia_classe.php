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
//CLASSE DA ENTIDADE db_syssequencia
class cl_db_syssequencia { 
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
   var $codsequencia = 0; 
   var $nomesequencia = null; 
   var $incrseq = 0; 
   var $minvalueseq = 0; 
   var $maxvalueseq = 0; 
   var $startseq = 0; 
   var $cacheseq = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 codsequencia = int4 = Código 
                 nomesequencia = varchar(100) = Nome 
                 incrseq = int4 = Incrementa 
                 minvalueseq = int4 = Valor Mínimo 
                 maxvalueseq = int8 = Valor Máximo 
                 startseq = int4 = Numero para Inicial 
                 cacheseq = int4 = Cache 
                 ";
   //funcao construtor da classe 
   function cl_db_syssequencia() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_syssequencia"); 
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
       $this->codsequencia = ($this->codsequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["codsequencia"]:$this->codsequencia);
       $this->nomesequencia = ($this->nomesequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["nomesequencia"]:$this->nomesequencia);
       $this->incrseq = ($this->incrseq == ""?@$GLOBALS["HTTP_POST_VARS"]["incrseq"]:$this->incrseq);
       $this->minvalueseq = ($this->minvalueseq == ""?@$GLOBALS["HTTP_POST_VARS"]["minvalueseq"]:$this->minvalueseq);
       $this->maxvalueseq = ($this->maxvalueseq == ""?@$GLOBALS["HTTP_POST_VARS"]["maxvalueseq"]:$this->maxvalueseq);
       $this->startseq = ($this->startseq == ""?@$GLOBALS["HTTP_POST_VARS"]["startseq"]:$this->startseq);
       $this->cacheseq = ($this->cacheseq == ""?@$GLOBALS["HTTP_POST_VARS"]["cacheseq"]:$this->cacheseq);
     }else{
       $this->codsequencia = ($this->codsequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["codsequencia"]:$this->codsequencia);
     }
   }
   // funcao para inclusao
   function incluir ($codsequencia){ 
      $this->atualizacampos();
     if($this->nomesequencia == null ){ 
       $this->erro_sql = " Campo Nome nao Informado.";
       $this->erro_campo = "nomesequencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->incrseq == null ){ 
       $this->erro_sql = " Campo Incrementa nao Informado.";
       $this->erro_campo = "incrseq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->minvalueseq == null ){ 
       $this->erro_sql = " Campo Valor Mínimo nao Informado.";
       $this->erro_campo = "minvalueseq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->maxvalueseq == null ){ 
       $this->erro_sql = " Campo Valor Máximo nao Informado.";
       $this->erro_campo = "maxvalueseq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->startseq == null ){ 
       $this->erro_sql = " Campo Numero para Inicial nao Informado.";
       $this->erro_campo = "startseq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cacheseq == null ){ 
       $this->erro_sql = " Campo Cache nao Informado.";
       $this->erro_campo = "cacheseq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->codsequencia = $codsequencia; 
     if(($this->codsequencia == null) || ($this->codsequencia == "") ){ 
       $this->erro_sql = " Campo codsequencia nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_syssequencia(
                                       codsequencia 
                                      ,nomesequencia 
                                      ,incrseq 
                                      ,minvalueseq 
                                      ,maxvalueseq 
                                      ,startseq 
                                      ,cacheseq 
                       )
                values (
                                $this->codsequencia 
                               ,'$this->nomesequencia' 
                               ,$this->incrseq 
                               ,$this->minvalueseq 
                               ,$this->maxvalueseq 
                               ,$this->startseq 
                               ,$this->cacheseq 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Sequencias para campos ($this->codsequencia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Sequencias para campos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Sequencias para campos ($this->codsequencia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->codsequencia;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->codsequencia));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,766,'$this->codsequencia','I')");
       $resac = db_query("insert into db_acount values($acount,150,766,'','".AddSlashes(pg_result($resaco,0,'codsequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,150,767,'','".AddSlashes(pg_result($resaco,0,'nomesequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,150,768,'','".AddSlashes(pg_result($resaco,0,'incrseq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,150,769,'','".AddSlashes(pg_result($resaco,0,'minvalueseq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,150,770,'','".AddSlashes(pg_result($resaco,0,'maxvalueseq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,150,771,'','".AddSlashes(pg_result($resaco,0,'startseq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,150,772,'','".AddSlashes(pg_result($resaco,0,'cacheseq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($codsequencia=null) { 
      $this->atualizacampos();
     $sql = " update db_syssequencia set ";
     $virgula = "";
     if(trim($this->codsequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codsequencia"])){ 
       $sql  .= $virgula." codsequencia = $this->codsequencia ";
       $virgula = ",";
       if(trim($this->codsequencia) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "codsequencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->nomesequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["nomesequencia"])){ 
       $sql  .= $virgula." nomesequencia = '$this->nomesequencia' ";
       $virgula = ",";
       if(trim($this->nomesequencia) == null ){ 
         $this->erro_sql = " Campo Nome nao Informado.";
         $this->erro_campo = "nomesequencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->incrseq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["incrseq"])){ 
       $sql  .= $virgula." incrseq = $this->incrseq ";
       $virgula = ",";
       if(trim($this->incrseq) == null ){ 
         $this->erro_sql = " Campo Incrementa nao Informado.";
         $this->erro_campo = "incrseq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->minvalueseq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["minvalueseq"])){ 
       $sql  .= $virgula." minvalueseq = $this->minvalueseq ";
       $virgula = ",";
       if(trim($this->minvalueseq) == null ){ 
         $this->erro_sql = " Campo Valor Mínimo nao Informado.";
         $this->erro_campo = "minvalueseq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->maxvalueseq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["maxvalueseq"])){ 
       $sql  .= $virgula." maxvalueseq = $this->maxvalueseq ";
       $virgula = ",";
       if(trim($this->maxvalueseq) == null ){ 
         $this->erro_sql = " Campo Valor Máximo nao Informado.";
         $this->erro_campo = "maxvalueseq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->startseq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["startseq"])){ 
       $sql  .= $virgula." startseq = $this->startseq ";
       $virgula = ",";
       if(trim($this->startseq) == null ){ 
         $this->erro_sql = " Campo Numero para Inicial nao Informado.";
         $this->erro_campo = "startseq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cacheseq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cacheseq"])){ 
       $sql  .= $virgula." cacheseq = $this->cacheseq ";
       $virgula = ",";
       if(trim($this->cacheseq) == null ){ 
         $this->erro_sql = " Campo Cache nao Informado.";
         $this->erro_campo = "cacheseq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($codsequencia!=null){
       $sql .= " codsequencia = $this->codsequencia";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->codsequencia));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,766,'$this->codsequencia','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["codsequencia"]))
           $resac = db_query("insert into db_acount values($acount,150,766,'".AddSlashes(pg_result($resaco,$conresaco,'codsequencia'))."','$this->codsequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["nomesequencia"]))
           $resac = db_query("insert into db_acount values($acount,150,767,'".AddSlashes(pg_result($resaco,$conresaco,'nomesequencia'))."','$this->nomesequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["incrseq"]))
           $resac = db_query("insert into db_acount values($acount,150,768,'".AddSlashes(pg_result($resaco,$conresaco,'incrseq'))."','$this->incrseq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["minvalueseq"]))
           $resac = db_query("insert into db_acount values($acount,150,769,'".AddSlashes(pg_result($resaco,$conresaco,'minvalueseq'))."','$this->minvalueseq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["maxvalueseq"]))
           $resac = db_query("insert into db_acount values($acount,150,770,'".AddSlashes(pg_result($resaco,$conresaco,'maxvalueseq'))."','$this->maxvalueseq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["startseq"]))
           $resac = db_query("insert into db_acount values($acount,150,771,'".AddSlashes(pg_result($resaco,$conresaco,'startseq'))."','$this->startseq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cacheseq"]))
           $resac = db_query("insert into db_acount values($acount,150,772,'".AddSlashes(pg_result($resaco,$conresaco,'cacheseq'))."','$this->cacheseq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Sequencias para campos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->codsequencia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Sequencias para campos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->codsequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->codsequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($codsequencia=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($codsequencia));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,766,'$codsequencia','E')");
         $resac = db_query("insert into db_acount values($acount,150,766,'','".AddSlashes(pg_result($resaco,$iresaco,'codsequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,150,767,'','".AddSlashes(pg_result($resaco,$iresaco,'nomesequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,150,768,'','".AddSlashes(pg_result($resaco,$iresaco,'incrseq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,150,769,'','".AddSlashes(pg_result($resaco,$iresaco,'minvalueseq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,150,770,'','".AddSlashes(pg_result($resaco,$iresaco,'maxvalueseq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,150,771,'','".AddSlashes(pg_result($resaco,$iresaco,'startseq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,150,772,'','".AddSlashes(pg_result($resaco,$iresaco,'cacheseq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_syssequencia
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($codsequencia != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " codsequencia = $codsequencia ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Sequencias para campos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$codsequencia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Sequencias para campos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$codsequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$codsequencia;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_syssequencia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>