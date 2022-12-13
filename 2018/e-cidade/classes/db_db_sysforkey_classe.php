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
//CLASSE DA ENTIDADE db_sysforkey
class cl_db_sysforkey { 
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
   var $codarq = 0; 
   var $codcam = 0; 
   var $sequen = 0; 
   var $referen = 0; 
   var $tipoobjrel = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 codarq = int4 = Codigo Arquivo 
                 codcam = int4 = Código 
                 sequen = int4 = Sequencia 
                 referen = int4 = Referencia 
                 tipoobjrel = int4 = Tipo Objeto 
                 ";
   //funcao construtor da classe 
   function cl_db_sysforkey() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_sysforkey"); 
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
       $this->codarq = ($this->codarq == ""?@$GLOBALS["HTTP_POST_VARS"]["codarq"]:$this->codarq);
       $this->codcam = ($this->codcam == ""?@$GLOBALS["HTTP_POST_VARS"]["codcam"]:$this->codcam);
       $this->sequen = ($this->sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["sequen"]:$this->sequen);
       $this->referen = ($this->referen == ""?@$GLOBALS["HTTP_POST_VARS"]["referen"]:$this->referen);
       $this->tipoobjrel = ($this->tipoobjrel == ""?@$GLOBALS["HTTP_POST_VARS"]["tipoobjrel"]:$this->tipoobjrel);
     }else{
       $this->codarq = ($this->codarq == ""?@$GLOBALS["HTTP_POST_VARS"]["codarq"]:$this->codarq);
       $this->codcam = ($this->codcam == ""?@$GLOBALS["HTTP_POST_VARS"]["codcam"]:$this->codcam);
       $this->sequen = ($this->sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["sequen"]:$this->sequen);
       $this->referen = ($this->referen == ""?@$GLOBALS["HTTP_POST_VARS"]["referen"]:$this->referen);
     }
   }
   // funcao para inclusao
   function incluir ($codcam,$codarq,$sequen,$referen){ 
      $this->atualizacampos();
     if($this->tipoobjrel == null ){ 
       $this->erro_sql = " Campo Tipo Objeto nao Informado.";
       $this->erro_campo = "tipoobjrel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->codcam = $codcam; 
       $this->codarq = $codarq; 
       $this->sequen = $sequen; 
       $this->referen = $referen; 
     if(($this->codcam == null) || ($this->codcam == "") ){ 
       $this->erro_sql = " Campo codcam nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->codarq == null) || ($this->codarq == "") ){ 
       $this->erro_sql = " Campo codarq nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->sequen == null) || ($this->sequen == "") ){ 
       $this->erro_sql = " Campo sequen nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->referen == null) || ($this->referen == "") ){ 
       $this->erro_sql = " Campo referen nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_sysforkey(
                                       codarq 
                                      ,codcam 
                                      ,sequen 
                                      ,referen 
                                      ,tipoobjrel 
                       )
                values (
                                $this->codarq 
                               ,$this->codcam 
                               ,$this->sequen 
                               ,$this->referen 
                               ,$this->tipoobjrel 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Chaves estrangeiras ($this->codcam."-".$this->codarq."-".$this->sequen."-".$this->referen) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Chaves estrangeiras já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Chaves estrangeiras ($this->codcam."-".$this->codarq."-".$this->sequen."-".$this->referen) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->codcam."-".$this->codarq."-".$this->sequen."-".$this->referen;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->codcam,$this->codarq,$this->sequen,$this->referen));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,752,'$this->codcam','I')");
       $resac = db_query("insert into db_acountkey values($acount,759,'$this->codarq','I')");
       $resac = db_query("insert into db_acountkey values($acount,765,'$this->sequen','I')");
       $resac = db_query("insert into db_acountkey values($acount,773,'$this->referen','I')");
       $resac = db_query("insert into db_acount values($acount,145,759,'','".AddSlashes(pg_result($resaco,0,'codarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,145,752,'','".AddSlashes(pg_result($resaco,0,'codcam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,145,765,'','".AddSlashes(pg_result($resaco,0,'sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,145,773,'','".AddSlashes(pg_result($resaco,0,'referen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,145,4791,'','".AddSlashes(pg_result($resaco,0,'tipoobjrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($codcam=null,$codarq=null,$sequen=null,$referen=null) { 
      $this->atualizacampos();
     $sql = " update db_sysforkey set ";
     $virgula = "";
     if(trim($this->codarq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codarq"])){ 
       $sql  .= $virgula." codarq = $this->codarq ";
       $virgula = ",";
       if(trim($this->codarq) == null ){ 
         $this->erro_sql = " Campo Codigo Arquivo nao Informado.";
         $this->erro_campo = "codarq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->codcam)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codcam"])){ 
       $sql  .= $virgula." codcam = $this->codcam ";
       $virgula = ",";
       if(trim($this->codcam) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "codcam";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sequen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sequen"])){ 
       $sql  .= $virgula." sequen = $this->sequen ";
       $virgula = ",";
       if(trim($this->sequen) == null ){ 
         $this->erro_sql = " Campo Sequencia nao Informado.";
         $this->erro_campo = "sequen";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->referen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["referen"])){ 
       $sql  .= $virgula." referen = $this->referen ";
       $virgula = ",";
       if(trim($this->referen) == null ){ 
         $this->erro_sql = " Campo Referencia nao Informado.";
         $this->erro_campo = "referen";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tipoobjrel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tipoobjrel"])){ 
       $sql  .= $virgula." tipoobjrel = $this->tipoobjrel ";
       $virgula = ",";
       if(trim($this->tipoobjrel) == null ){ 
         $this->erro_sql = " Campo Tipo Objeto nao Informado.";
         $this->erro_campo = "tipoobjrel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($codcam!=null){
       $sql .= " codcam = $this->codcam";
     }
     if($codarq!=null){
       $sql .= " and  codarq = $this->codarq";
     }
     if($sequen!=null){
       $sql .= " and  sequen = $this->sequen";
     }
     if($referen!=null){
       $sql .= " and  referen = $this->referen";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->codcam,$this->codarq,$this->sequen,$this->referen));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,752,'$this->codcam','A')");
         $resac = db_query("insert into db_acountkey values($acount,759,'$this->codarq','A')");
         $resac = db_query("insert into db_acountkey values($acount,765,'$this->sequen','A')");
         $resac = db_query("insert into db_acountkey values($acount,773,'$this->referen','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["codarq"]))
           $resac = db_query("insert into db_acount values($acount,145,759,'".AddSlashes(pg_result($resaco,$conresaco,'codarq'))."','$this->codarq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["codcam"]))
           $resac = db_query("insert into db_acount values($acount,145,752,'".AddSlashes(pg_result($resaco,$conresaco,'codcam'))."','$this->codcam',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sequen"]))
           $resac = db_query("insert into db_acount values($acount,145,765,'".AddSlashes(pg_result($resaco,$conresaco,'sequen'))."','$this->sequen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["referen"]))
           $resac = db_query("insert into db_acount values($acount,145,773,'".AddSlashes(pg_result($resaco,$conresaco,'referen'))."','$this->referen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tipoobjrel"]))
           $resac = db_query("insert into db_acount values($acount,145,4791,'".AddSlashes(pg_result($resaco,$conresaco,'tipoobjrel'))."','$this->tipoobjrel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Chaves estrangeiras nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->codcam."-".$this->codarq."-".$this->sequen."-".$this->referen;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Chaves estrangeiras nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->codcam."-".$this->codarq."-".$this->sequen."-".$this->referen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->codcam."-".$this->codarq."-".$this->sequen."-".$this->referen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($codcam=null,$codarq=null,$sequen=null,$referen=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($codcam,$codarq,$sequen,$referen));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,752,'$codcam','E')");
         $resac = db_query("insert into db_acountkey values($acount,759,'$codarq','E')");
         $resac = db_query("insert into db_acountkey values($acount,765,'$sequen','E')");
         $resac = db_query("insert into db_acountkey values($acount,773,'$referen','E')");
         $resac = db_query("insert into db_acount values($acount,145,759,'','".AddSlashes(pg_result($resaco,$iresaco,'codarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,145,752,'','".AddSlashes(pg_result($resaco,$iresaco,'codcam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,145,765,'','".AddSlashes(pg_result($resaco,$iresaco,'sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,145,773,'','".AddSlashes(pg_result($resaco,$iresaco,'referen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,145,4791,'','".AddSlashes(pg_result($resaco,$iresaco,'tipoobjrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_sysforkey
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($codcam != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " codcam = $codcam ";
        }
        if($codarq != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " codarq = $codarq ";
        }
        if($sequen != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sequen = $sequen ";
        }
        if($referen != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " referen = $referen ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Chaves estrangeiras nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$codcam."-".$codarq."-".$sequen."-".$referen;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Chaves estrangeiras nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$codcam."-".$codarq."-".$sequen."-".$referen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$codcam."-".$codarq."-".$sequen."-".$referen;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_sysforkey";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>