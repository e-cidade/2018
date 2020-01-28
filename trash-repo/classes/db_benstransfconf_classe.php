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

//MODULO: patrim
//CLASSE DA ENTIDADE benstransfconf
class cl_benstransfconf { 
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
   var $t96_codtran = 0; 
   var $t96_id_usuario = 0; 
   var $t96_data_dia = null; 
   var $t96_data_mes = null; 
   var $t96_data_ano = null; 
   var $t96_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t96_codtran = int8 = Transferência 
                 t96_id_usuario = int4 = Cod. Usuário 
                 t96_data = date = Data da confirmação 
                 ";
   //funcao construtor da classe 
   function cl_benstransfconf() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("benstransfconf"); 
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
       $this->t96_codtran = ($this->t96_codtran == ""?@$GLOBALS["HTTP_POST_VARS"]["t96_codtran"]:$this->t96_codtran);
       $this->t96_id_usuario = ($this->t96_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["t96_id_usuario"]:$this->t96_id_usuario);
       if($this->t96_data == ""){
         $this->t96_data_dia = ($this->t96_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["t96_data_dia"]:$this->t96_data_dia);
         $this->t96_data_mes = ($this->t96_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["t96_data_mes"]:$this->t96_data_mes);
         $this->t96_data_ano = ($this->t96_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["t96_data_ano"]:$this->t96_data_ano);
         if($this->t96_data_dia != ""){
            $this->t96_data = $this->t96_data_ano."-".$this->t96_data_mes."-".$this->t96_data_dia;
         }
       }
     }else{
       $this->t96_codtran = ($this->t96_codtran == ""?@$GLOBALS["HTTP_POST_VARS"]["t96_codtran"]:$this->t96_codtran);
     }
   }
   // funcao para inclusao
   function incluir ($t96_codtran){ 
      $this->atualizacampos();
     if($this->t96_id_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "t96_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t96_data == null ){ 
       $this->erro_sql = " Campo Data da confirmação nao Informado.";
       $this->erro_campo = "t96_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->t96_codtran = $t96_codtran; 
     if(($this->t96_codtran == null) || ($this->t96_codtran == "") ){ 
       $this->erro_sql = " Campo t96_codtran nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into benstransfconf(
                                       t96_codtran 
                                      ,t96_id_usuario 
                                      ,t96_data 
                       )
                values (
                                $this->t96_codtran 
                               ,$this->t96_id_usuario 
                               ,".($this->t96_data == "null" || $this->t96_data == ""?"null":"'".$this->t96_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Confirmação da transferência ($this->t96_codtran) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Confirmação da transferência já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Confirmação da transferência ($this->t96_codtran) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t96_codtran;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t96_codtran));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5832,'$this->t96_codtran','I')");
       $resac = db_query("insert into db_acount values($acount,932,5832,'','".AddSlashes(pg_result($resaco,0,'t96_codtran'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,932,5833,'','".AddSlashes(pg_result($resaco,0,'t96_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,932,5834,'','".AddSlashes(pg_result($resaco,0,'t96_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($t96_codtran=null) { 
      $this->atualizacampos();
     $sql = " update benstransfconf set ";
     $virgula = "";
     if(trim($this->t96_codtran)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t96_codtran"])){ 
       $sql  .= $virgula." t96_codtran = $this->t96_codtran ";
       $virgula = ",";
       if(trim($this->t96_codtran) == null ){ 
         $this->erro_sql = " Campo Transferência nao Informado.";
         $this->erro_campo = "t96_codtran";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t96_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t96_id_usuario"])){ 
       $sql  .= $virgula." t96_id_usuario = $this->t96_id_usuario ";
       $virgula = ",";
       if(trim($this->t96_id_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "t96_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t96_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t96_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["t96_data_dia"] !="") ){ 
       $sql  .= $virgula." t96_data = '$this->t96_data' ";
       $virgula = ",";
       if(trim($this->t96_data) == null ){ 
         $this->erro_sql = " Campo Data da confirmação nao Informado.";
         $this->erro_campo = "t96_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["t96_data_dia"])){ 
         $sql  .= $virgula." t96_data = null ";
         $virgula = ",";
         if(trim($this->t96_data) == null ){ 
           $this->erro_sql = " Campo Data da confirmação nao Informado.";
           $this->erro_campo = "t96_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($t96_codtran!=null){
       $sql .= " t96_codtran = $this->t96_codtran";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t96_codtran));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5832,'$this->t96_codtran','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t96_codtran"]))
           $resac = db_query("insert into db_acount values($acount,932,5832,'".AddSlashes(pg_result($resaco,$conresaco,'t96_codtran'))."','$this->t96_codtran',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t96_id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,932,5833,'".AddSlashes(pg_result($resaco,$conresaco,'t96_id_usuario'))."','$this->t96_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t96_data"]))
           $resac = db_query("insert into db_acount values($acount,932,5834,'".AddSlashes(pg_result($resaco,$conresaco,'t96_data'))."','$this->t96_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Confirmação da transferência nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t96_codtran;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Confirmação da transferência nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t96_codtran;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t96_codtran;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($t96_codtran=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t96_codtran));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5832,'$t96_codtran','E')");
         $resac = db_query("insert into db_acount values($acount,932,5832,'','".AddSlashes(pg_result($resaco,$iresaco,'t96_codtran'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,932,5833,'','".AddSlashes(pg_result($resaco,$iresaco,'t96_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,932,5834,'','".AddSlashes(pg_result($resaco,$iresaco,'t96_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from benstransfconf
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t96_codtran != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t96_codtran = $t96_codtran ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Confirmação da transferência nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t96_codtran;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Confirmação da transferência nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t96_codtran;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t96_codtran;
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
        $this->erro_sql   = "Record Vazio na Tabela:benstransfconf";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $t96_codtran=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from benstransfconf ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = benstransfconf.t96_id_usuario";
     $sql .= "      inner join benstransf  on  benstransf.t93_codtran = benstransfconf.t96_codtran";
     $sql .= "      inner join db_usuarios as a on  a.id_usuario = benstransf.t93_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = benstransf.t93_depart";
     $sql2 = "";
     if($dbwhere==""){
       if($t96_codtran!=null ){
         $sql2 .= " where benstransfconf.t96_codtran = $t96_codtran "; 
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
   function sql_query_file ( $t96_codtran=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from benstransfconf ";
     $sql2 = "";
     if($dbwhere==""){
       if($t96_codtran!=null ){
         $sql2 .= " where benstransfconf.t96_codtran = $t96_codtran "; 
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