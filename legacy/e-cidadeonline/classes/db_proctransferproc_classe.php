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

//MODULO: protocolo
//CLASSE DA ENTIDADE proctransferproc
class cl_proctransferproc { 
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
   var $p63_codtran = 0; 
   var $p63_codproc = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 p63_codtran = int4 = Transferencia 
                 p63_codproc = int4 = Processo 
                 ";
   //funcao construtor da classe 
   function cl_proctransferproc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("proctransferproc"); 
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
       $this->p63_codtran = ($this->p63_codtran == ""?@$GLOBALS["HTTP_POST_VARS"]["p63_codtran"]:$this->p63_codtran);
       $this->p63_codproc = ($this->p63_codproc == ""?@$GLOBALS["HTTP_POST_VARS"]["p63_codproc"]:$this->p63_codproc);
     }else{
       $this->p63_codtran = ($this->p63_codtran == ""?@$GLOBALS["HTTP_POST_VARS"]["p63_codtran"]:$this->p63_codtran);
       $this->p63_codproc = ($this->p63_codproc == ""?@$GLOBALS["HTTP_POST_VARS"]["p63_codproc"]:$this->p63_codproc);
     }
   }
   // funcao para inclusao
   function incluir ($p63_codtran,$p63_codproc){ 
      $this->atualizacampos();
       $this->p63_codtran = $p63_codtran; 
       $this->p63_codproc = $p63_codproc; 
     if(($this->p63_codtran == null) || ($this->p63_codtran == "") ){ 
       $this->erro_sql = " Campo p63_codtran nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->p63_codproc == null) || ($this->p63_codproc == "") ){ 
       $this->erro_sql = " Campo p63_codproc nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into proctransferproc(
                                       p63_codtran 
                                      ,p63_codproc 
                       )
                values (
                                $this->p63_codtran 
                               ,$this->p63_codproc 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->p63_codtran."-".$this->p63_codproc) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->p63_codtran."-".$this->p63_codproc) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p63_codtran."-".$this->p63_codproc;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->p63_codtran,$this->p63_codproc));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,2480,'$this->p63_codtran','I')");
       $resac = db_query("insert into db_acountkey values($acount,2481,'$this->p63_codproc','I')");
       $resac = db_query("insert into db_acount values($acount,409,2480,'','".AddSlashes(pg_result($resaco,0,'p63_codtran'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,409,2481,'','".AddSlashes(pg_result($resaco,0,'p63_codproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($p63_codtran=null,$p63_codproc=null) { 
      $this->atualizacampos();
     $sql = " update proctransferproc set ";
     $virgula = "";
     if(trim($this->p63_codtran)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p63_codtran"])){ 
       $sql  .= $virgula." p63_codtran = $this->p63_codtran ";
       $virgula = ",";
       if(trim($this->p63_codtran) == null ){ 
         $this->erro_sql = " Campo Transferencia nao Informado.";
         $this->erro_campo = "p63_codtran";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p63_codproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p63_codproc"])){ 
       $sql  .= $virgula." p63_codproc = $this->p63_codproc ";
       $virgula = ",";
       if(trim($this->p63_codproc) == null ){ 
         $this->erro_sql = " Campo Processo nao Informado.";
         $this->erro_campo = "p63_codproc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($p63_codtran!=null){
       $sql .= " p63_codtran = $this->p63_codtran";
     }
     if($p63_codproc!=null){
       $sql .= " and  p63_codproc = $this->p63_codproc";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->p63_codtran,$this->p63_codproc));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2480,'$this->p63_codtran','A')");
         $resac = db_query("insert into db_acountkey values($acount,2481,'$this->p63_codproc','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p63_codtran"]))
           $resac = db_query("insert into db_acount values($acount,409,2480,'".AddSlashes(pg_result($resaco,$conresaco,'p63_codtran'))."','$this->p63_codtran',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p63_codproc"]))
           $resac = db_query("insert into db_acount values($acount,409,2481,'".AddSlashes(pg_result($resaco,$conresaco,'p63_codproc'))."','$this->p63_codproc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->p63_codtran."-".$this->p63_codproc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->p63_codtran."-".$this->p63_codproc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p63_codtran."-".$this->p63_codproc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($p63_codtran=null,$p63_codproc=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($p63_codtran,$p63_codproc));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2480,'$p63_codtran','E')");
         $resac = db_query("insert into db_acountkey values($acount,2481,'$p63_codproc','E')");
         $resac = db_query("insert into db_acount values($acount,409,2480,'','".AddSlashes(pg_result($resaco,$iresaco,'p63_codtran'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,409,2481,'','".AddSlashes(pg_result($resaco,$iresaco,'p63_codproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from proctransferproc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($p63_codtran != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " p63_codtran = $p63_codtran ";
        }
        if($p63_codproc != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " p63_codproc = $p63_codproc ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$p63_codtran."-".$p63_codproc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$p63_codtran."-".$p63_codproc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$p63_codtran."-".$p63_codproc;
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
        $this->erro_sql   = "Record Vazio na Tabela:proctransferproc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $p63_codtran=null,$p63_codproc=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from proctransferproc ";
     $sql .= "      inner join protprocesso  on  protprocesso.p58_codproc = proctransferproc.p63_codproc";
     $sql .= "      inner join proctransfer  on  proctransfer.p62_codtran = proctransferproc.p63_codtran";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = protprocesso.p58_numcgm";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = protprocesso.p58_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = protprocesso.p58_coddepto";
     $sql .= "      inner join tipoproc  on  tipoproc.p51_codigo = protprocesso.p58_codigo";
     $sql .= "      inner join db_depart  as a on   a.coddepto = proctransfer.p62_coddepto";
     $sql2 = "";
     if($dbwhere==""){
       if($p63_codtran!=null ){
         $sql2 .= " where proctransferproc.p63_codtran = $p63_codtran "; 
       } 
       if($p63_codproc!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " proctransferproc.p63_codproc = $p63_codproc "; 
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
   function sql_query_file ( $p63_codtran=null,$p63_codproc=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from proctransferproc ";
     $sql2 = "";
     if($dbwhere==""){
       if($p63_codtran!=null ){
         $sql2 .= " where proctransferproc.p63_codtran = $p63_codtran "; 
       } 
       if($p63_codproc!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " proctransferproc.p63_codproc = $p63_codproc "; 
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
   function sql_query_andam( $p63_codtran=null,$p63_codproc=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from proctransferproc ";
     $sql .= "      inner join protprocesso   on protprocesso.p58_codproc = proctransferproc.p63_codproc ";
     $sql .= "      inner join proctransfer   on proctransfer.p62_codtran = proctransferproc.p63_codtran ";
     $sql .= "      inner join proctransand   on proctransand.p64_codtran = proctransfer.p62_codtran     ";
     $sql .= "      inner join procandam      on procandam.p61_codandam   = proctransand.p64_codandam    ";
     $sql .= "                               and procandam.p61_codproc    = proctransferproc.p63_codproc ";
     $sql .= "      left  join db_usuarios    on db_usuarios.id_usuario   = procandam.p61_id_usuario     ";
     $sql .= "      left  join db_depart      on db_depart.coddepto       = procandam.p61_coddepto       ";

     $sql2 = "";
     if($dbwhere==""){
       if($p63_codtran!=null ){
         $sql2 .= " where proctransferproc.p63_codtran = $p63_codtran "; 
       } 
       if($p63_codproc!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " proctransferproc.p63_codproc = $p63_codproc "; 
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
  
  
  function sql_query_andamento_processo($p63_codtran=null,$p63_codproc=null,$campos="*",$ordem=null,$dbwhere="") {
    
    $sql = "select ";
    if($campos != "*" ) {
      
      $campos_sql = split("#",$campos);
      $virgula = "";
      for ($i=0;$i<sizeof($campos_sql);$i++) {
        
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from proctransferproc ";
    $sql .= "      inner join protprocesso   on protprocesso.p58_codproc = proctransferproc.p63_codproc ";
    $sql .= "       left join proctransfer   on proctransfer.p62_codtran = proctransferproc.p63_codtran ";
    $sql .= "       left join proctransand   on proctransand.p64_codtran = proctransfer.p62_codtran     ";
    $sql .= "       left join procandam      on procandam.p61_codandam   = proctransand.p64_codandam    ";
    $sql .= "                               and procandam.p61_codproc    = proctransferproc.p63_codproc ";
    $sql .= "       left  join arqandam                       on p69_codandam   = p61_codandam  ";
    $sql .= "       left  join procarquiv                     on p67_codarquiv  = p69_codarquiv";
    $sql .= "       left  join db_usuarios as usuario_origem  on usuario_origem.id_usuario    = proctransfer.p62_id_usuario     ";
    $sql .= "       left  join db_depart   as depto_origem    on depto_origem.coddepto        = proctransfer.p62_coddepto       ";
    $sql .= "       left  join db_depart   as depto_transferencia on depto_transferencia.coddepto    = proctransfer.p62_coddeptorec  ";
    $sql .= "       left  join db_usuarios as usuario_destino     on usuario_destino.id_usuario   = procandam.p61_id_usuario     ";
    $sql .= "       left  join db_depart   as depto_destino       on depto_destino.coddepto       = procandam.p61_coddepto       ";
  
    $sql2 = "";
    if($dbwhere==""){
      if($p63_codtran!=null ){
        $sql2 .= " where proctransferproc.p63_codtran = $p63_codtran ";
      }
      if($p63_codproc!=null ){
      if($sql2!=""){
      $sql2 .= " and ";
      }else{
      $sql2 .= " where ";
      }
        $sql2 .= " proctransferproc.p63_codproc = $p63_codproc ";
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