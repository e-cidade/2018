<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_syscampodef
class cl_db_syscampodef {
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
   var $codcam = 0;
   var $defcampo = null;
   var $defdescr = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 codcam = int4 = Código
                 defcampo = varchar(50) = Valor default
                 defdescr = varchar(200) = Descrição Valor
                 ";
   //funcao construtor da classe
   function cl_db_syscampodef() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_syscampodef");
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
       $this->codcam = ($this->codcam == ""?@$GLOBALS["HTTP_POST_VARS"]["codcam"]:$this->codcam);
       $this->defcampo = ($this->defcampo == ""?@$GLOBALS["HTTP_POST_VARS"]["defcampo"]:$this->defcampo);
       $this->defdescr = ($this->defdescr == ""?@$GLOBALS["HTTP_POST_VARS"]["defdescr"]:$this->defdescr);
     }else{
       $this->codcam = ($this->codcam == ""?@$GLOBALS["HTTP_POST_VARS"]["codcam"]:$this->codcam);
       $this->defcampo = ($this->defcampo == ""?@$GLOBALS["HTTP_POST_VARS"]["defcampo"]:$this->defcampo);
     }
   }
   // funcao para inclusao
   function incluir ($codcam,$defcampo){
      $this->atualizacampos();
     if($this->defdescr == null ){
       $this->erro_sql = " Campo Descrição Valor nao Informado.";
       $this->erro_campo = "defdescr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->codcam = $codcam;
       $this->defcampo = $defcampo;
     if(($this->codcam == null) || ($this->codcam == "") ){
       $this->erro_sql = " Campo codcam nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->defcampo == null) || ($this->defcampo == "") ){
       $this->erro_sql = " Campo defcampo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_syscampodef(
                                       codcam
                                      ,defcampo
                                      ,defdescr
                       )
                values (
                                $this->codcam
                               ,'$this->defcampo'
                               ,'$this->defdescr'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Default dos campos quando select ($this->codcam."-".$this->defcampo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Default dos campos quando select já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Default dos campos quando select ($this->codcam."-".$this->defcampo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->codcam."-".$this->defcampo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->codcam,$this->defcampo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,752,'$this->codcam','I')");
       $resac = db_query("insert into db_acountkey values($acount,4785,'$this->defcampo','I')");
       $resac = db_query("insert into db_acount values($acount,643,752,'','".AddSlashes(pg_result($resaco,0,'codcam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,643,4785,'','".AddSlashes(pg_result($resaco,0,'defcampo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,643,4786,'','".AddSlashes(pg_result($resaco,0,'defdescr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($codcam=null,$defcampo=null) {
      $this->atualizacampos();
     $sql = " update db_syscampodef set ";
     $virgula = "";
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
     if(trim($this->defcampo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["defcampo"])){
       $sql  .= $virgula." defcampo = '$this->defcampo' ";
       $virgula = ",";
       if(trim($this->defcampo) == null ){
         $this->erro_sql = " Campo Valor default nao Informado.";
         $this->erro_campo = "defcampo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->defdescr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["defdescr"])){
       $sql  .= $virgula." defdescr = '$this->defdescr' ";
       $virgula = ",";
       if(trim($this->defdescr) == null ){
         $this->erro_sql = " Campo Descrição Valor nao Informado.";
         $this->erro_campo = "defdescr";
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
     if($defcampo!=null){
       $sql .= " and  defcampo = '$this->defcampo'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->codcam,$this->defcampo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,752,'$this->codcam','A')");
         $resac = db_query("insert into db_acountkey values($acount,4785,'$this->defcampo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["codcam"]))
           $resac = db_query("insert into db_acount values($acount,643,752,'".AddSlashes(pg_result($resaco,$conresaco,'codcam'))."','$this->codcam',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["defcampo"]))
           $resac = db_query("insert into db_acount values($acount,643,4785,'".AddSlashes(pg_result($resaco,$conresaco,'defcampo'))."','$this->defcampo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["defdescr"]))
           $resac = db_query("insert into db_acount values($acount,643,4786,'".AddSlashes(pg_result($resaco,$conresaco,'defdescr'))."','$this->defdescr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Default dos campos quando select nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->codcam."-".$this->defcampo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Default dos campos quando select nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->codcam."-".$this->defcampo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->codcam."-".$this->defcampo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($codcam=null,$defcampo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($codcam,$defcampo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,752,'$codcam','E')");
         $resac = db_query("insert into db_acountkey values($acount,4785,'$defcampo','E')");
         $resac = db_query("insert into db_acount values($acount,643,752,'','".AddSlashes(pg_result($resaco,$iresaco,'codcam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,643,4785,'','".AddSlashes(pg_result($resaco,$iresaco,'defcampo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,643,4786,'','".AddSlashes(pg_result($resaco,$iresaco,'defdescr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_syscampodef
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($codcam != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " codcam = $codcam ";
        }
        if($defcampo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " defcampo = '$defcampo' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Default dos campos quando select nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$codcam."-".$defcampo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Default dos campos quando select nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$codcam."-".$defcampo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$codcam."-".$defcampo;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_syscampodef";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $codcam=null,$defcampo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from db_syscampodef ";
     $sql .= "      inner join db_syscampo  on  db_syscampo.codcam = db_syscampodef.codcam";
     $sql2 = "";
     if($dbwhere==""){
       if($codcam!=null ){
         $sql2 .= " where db_syscampodef.codcam = $codcam ";
       }
       if($defcampo!=null ){
       if($sql2!=""){
       $sql2 .= " and ";
       }else{
           $sql2 .= " where ";
           }
           $sql2 .= " db_syscampodef.defcampo = '$defcampo' ";
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
   function sql_query_file ( $codcam=null,$defcampo=null,$campos="*",$ordem=null,$dbwhere=""){
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
   $sql .= " from db_syscampodef ";
   $sql2 = "";
     if($dbwhere==""){
     if($codcam!=null ){
     $sql2 .= " where db_syscampodef.codcam = $codcam ";
     }
     if($defcampo!=null ){
         if($sql2!=""){
     $sql2 .= " and ";
   }else{
   $sql2 .= " where ";
   }
   $sql2 .= " db_syscampodef.defcampo = '$defcampo' ";
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