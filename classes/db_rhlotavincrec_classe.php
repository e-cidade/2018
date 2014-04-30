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
//CLASSE DA ENTIDADE rhlotavincrec
class cl_rhlotavincrec { 
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
   var $rh43_codlotavinc = 0; 
   var $rh43_codelenov = 0; 
   var $rh43_recurso = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh43_codlotavinc = int8 = Código 
                 rh43_codelenov = int4 = Elemento Novo 
                 rh43_recurso = int4 = Recurso 
                 ";
   //funcao construtor da classe 
   function cl_rhlotavincrec() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhlotavincrec"); 
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
       $this->rh43_codlotavinc = ($this->rh43_codlotavinc == ""?@$GLOBALS["HTTP_POST_VARS"]["rh43_codlotavinc"]:$this->rh43_codlotavinc);
       $this->rh43_codelenov = ($this->rh43_codelenov == ""?@$GLOBALS["HTTP_POST_VARS"]["rh43_codelenov"]:$this->rh43_codelenov);
       $this->rh43_recurso = ($this->rh43_recurso == ""?@$GLOBALS["HTTP_POST_VARS"]["rh43_recurso"]:$this->rh43_recurso);
     }else{
       $this->rh43_codlotavinc = ($this->rh43_codlotavinc == ""?@$GLOBALS["HTTP_POST_VARS"]["rh43_codlotavinc"]:$this->rh43_codlotavinc);
       $this->rh43_codelenov = ($this->rh43_codelenov == ""?@$GLOBALS["HTTP_POST_VARS"]["rh43_codelenov"]:$this->rh43_codelenov);
     }
   }
   // funcao para inclusao
   function incluir ($rh43_codlotavinc,$rh43_codelenov){ 
      $this->atualizacampos();
     if($this->rh43_recurso == null ){ 
       $this->erro_sql = " Campo Recurso nao Informado.";
       $this->erro_campo = "rh43_recurso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->rh43_codlotavinc = $rh43_codlotavinc; 
       $this->rh43_codelenov = $rh43_codelenov; 
     if(($this->rh43_codlotavinc == null) || ($this->rh43_codlotavinc == "") ){ 
       $this->erro_sql = " Campo rh43_codlotavinc nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->rh43_codelenov == null) || ($this->rh43_codelenov == "") ){ 
       $this->erro_sql = " Campo rh43_codelenov nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhlotavincrec(
                                       rh43_codlotavinc 
                                      ,rh43_codelenov 
                                      ,rh43_recurso 
                       )
                values (
                                $this->rh43_codlotavinc 
                               ,$this->rh43_codelenov 
                               ,$this->rh43_recurso 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Empenho em outro recurso ($this->rh43_codlotavinc."-".$this->rh43_codelenov) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Empenho em outro recurso já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Empenho em outro recurso ($this->rh43_codlotavinc."-".$this->rh43_codelenov) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh43_codlotavinc."-".$this->rh43_codelenov;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh43_codlotavinc,$this->rh43_codelenov));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7366,'$this->rh43_codlotavinc','I')");
       $resac = db_query("insert into db_acountkey values($acount,7367,'$this->rh43_codelenov','I')");
       $resac = db_query("insert into db_acount values($acount,1229,7366,'','".AddSlashes(pg_result($resaco,0,'rh43_codlotavinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1229,7367,'','".AddSlashes(pg_result($resaco,0,'rh43_codelenov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1229,7368,'','".AddSlashes(pg_result($resaco,0,'rh43_recurso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh43_codlotavinc=null,$rh43_codelenov=null) { 
      $this->atualizacampos();
     $sql = " update rhlotavincrec set ";
     $virgula = "";
     if(trim($this->rh43_codlotavinc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh43_codlotavinc"])){ 
       $sql  .= $virgula." rh43_codlotavinc = $this->rh43_codlotavinc ";
       $virgula = ",";
       if(trim($this->rh43_codlotavinc) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "rh43_codlotavinc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh43_codelenov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh43_codelenov"])){ 
       $sql  .= $virgula." rh43_codelenov = $this->rh43_codelenov ";
       $virgula = ",";
       if(trim($this->rh43_codelenov) == null ){ 
         $this->erro_sql = " Campo Elemento Novo nao Informado.";
         $this->erro_campo = "rh43_codelenov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh43_recurso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh43_recurso"])){ 
       $sql  .= $virgula." rh43_recurso = $this->rh43_recurso ";
       $virgula = ",";
       if(trim($this->rh43_recurso) == null ){ 
         $this->erro_sql = " Campo Recurso nao Informado.";
         $this->erro_campo = "rh43_recurso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh43_codlotavinc!=null){
       $sql .= " rh43_codlotavinc = $this->rh43_codlotavinc";
     }
     if($rh43_codelenov!=null){
       $sql .= " and  rh43_codelenov = $this->rh43_codelenov";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh43_codlotavinc,$this->rh43_codelenov));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7366,'$this->rh43_codlotavinc','A')");
         $resac = db_query("insert into db_acountkey values($acount,7367,'$this->rh43_codelenov','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh43_codlotavinc"]))
           $resac = db_query("insert into db_acount values($acount,1229,7366,'".AddSlashes(pg_result($resaco,$conresaco,'rh43_codlotavinc'))."','$this->rh43_codlotavinc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh43_codelenov"]))
           $resac = db_query("insert into db_acount values($acount,1229,7367,'".AddSlashes(pg_result($resaco,$conresaco,'rh43_codelenov'))."','$this->rh43_codelenov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh43_recurso"]))
           $resac = db_query("insert into db_acount values($acount,1229,7368,'".AddSlashes(pg_result($resaco,$conresaco,'rh43_recurso'))."','$this->rh43_recurso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Empenho em outro recurso nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh43_codlotavinc."-".$this->rh43_codelenov;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Empenho em outro recurso nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh43_codlotavinc."-".$this->rh43_codelenov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh43_codlotavinc."-".$this->rh43_codelenov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh43_codlotavinc=null,$rh43_codelenov=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh43_codlotavinc,$rh43_codelenov));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7366,'$rh43_codlotavinc','E')");
         $resac = db_query("insert into db_acountkey values($acount,7367,'$rh43_codelenov','E')");
         $resac = db_query("insert into db_acount values($acount,1229,7366,'','".AddSlashes(pg_result($resaco,$iresaco,'rh43_codlotavinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1229,7367,'','".AddSlashes(pg_result($resaco,$iresaco,'rh43_codelenov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1229,7368,'','".AddSlashes(pg_result($resaco,$iresaco,'rh43_recurso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhlotavincrec
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh43_codlotavinc != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh43_codlotavinc = $rh43_codlotavinc ";
        }
        if($rh43_codelenov != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh43_codelenov = $rh43_codelenov ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Empenho em outro recurso nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh43_codlotavinc."-".$rh43_codelenov;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Empenho em outro recurso nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh43_codlotavinc."-".$rh43_codelenov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh43_codlotavinc."-".$rh43_codelenov;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhlotavincrec";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $rh43_codlotavinc=null,$rh43_codelenov=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhlotavincrec ";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = rhlotavincrec.rh43_recurso";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = rhlotavincrec.rh43_codelenov and orcelemento.o56_anousu = ".db_getsession("DB_anousu");
     $sql2 = "";
     if($dbwhere==""){
       if($rh43_codlotavinc!=null ){
         $sql2 .= " where rhlotavincrec.rh43_codlotavinc = $rh43_codlotavinc "; 
       } 
       if($rh43_codelenov!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhlotavincrec.rh43_codelenov = $rh43_codelenov "; 
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
   function sql_query_file ( $rh43_codlotavinc=null,$rh43_codelenov=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhlotavincrec ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh43_codlotavinc!=null ){
         $sql2 .= " where rhlotavincrec.rh43_codlotavinc = $rh43_codlotavinc "; 
       } 
       if($rh43_codelenov!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhlotavincrec.rh43_codelenov = $rh43_codelenov "; 
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