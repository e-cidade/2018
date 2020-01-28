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

//MODULO: inflatores
//CLASSE DA ENTIDADE infla
class cl_infla { 
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
   var $i02_codigo = null; 
   var $i02_data_dia = null; 
   var $i02_data_mes = null; 
   var $i02_data_ano = null; 
   var $i02_data = null; 
   var $i02_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 i02_codigo = varchar(5) = codigo do inflator 
                 i02_data = date = data do inflator 
                 i02_valor = float8 = valor do inflator 
                 ";
   //funcao construtor da classe 
   function cl_infla() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("infla"); 
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
       $this->i02_codigo = ($this->i02_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["i02_codigo"]:$this->i02_codigo);
       if($this->i02_data == ""){
         $this->i02_data_dia = ($this->i02_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["i02_data_dia"]:$this->i02_data_dia);
         $this->i02_data_mes = ($this->i02_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["i02_data_mes"]:$this->i02_data_mes);
         $this->i02_data_ano = ($this->i02_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["i02_data_ano"]:$this->i02_data_ano);
         if($this->i02_data_dia != ""){
            $this->i02_data = $this->i02_data_ano."-".$this->i02_data_mes."-".$this->i02_data_dia;
         }
       }
       $this->i02_valor = ($this->i02_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["i02_valor"]:$this->i02_valor);
     }else{
       $this->i02_codigo = ($this->i02_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["i02_codigo"]:$this->i02_codigo);
       $this->i02_data = ($this->i02_data == ""?@$GLOBALS["HTTP_POST_VARS"]["i02_data_ano"]."-".@$GLOBALS["HTTP_POST_VARS"]["i02_data_mes"]."-".@$GLOBALS["HTTP_POST_VARS"]["i02_data_dia"]:$this->i02_data);
     }
   }
   // funcao para inclusao
   function incluir ($i02_codigo,$i02_data){ 
      $this->atualizacampos();
     if($this->i02_valor == null ){ 
       $this->erro_sql = " Campo valor do inflator nao Informado.";
       $this->erro_campo = "i02_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->i02_codigo = $i02_codigo; 
       $this->i02_data = $i02_data; 
     if(($this->i02_codigo == null) || ($this->i02_codigo == "") ){ 
       $this->erro_sql = " Campo i02_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->i02_data == null) || ($this->i02_data == "") ){ 
       $this->erro_sql = " Campo i02_data nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into infla(
                                       i02_codigo 
                                      ,i02_data 
                                      ,i02_valor 
                       )
                values (
                                '$this->i02_codigo' 
                               ,".($this->i02_data == "null" || $this->i02_data == ""?"null":"'".$this->i02_data."'")." 
                               ,$this->i02_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->i02_codigo."-".$this->i02_data) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->i02_codigo."-".$this->i02_data) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->i02_codigo."-".$this->i02_data;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->i02_codigo,$this->i02_data));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,446,'$this->i02_codigo','I')");
       $resac = db_query("insert into db_acountkey values($acount,447,'$this->i02_data','I')");
       $resac = db_query("insert into db_acount values($acount,81,446,'','".AddSlashes(pg_result($resaco,0,'i02_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,81,447,'','".AddSlashes(pg_result($resaco,0,'i02_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,81,448,'','".AddSlashes(pg_result($resaco,0,'i02_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($i02_codigo=null,$i02_data=null) { 
      $this->atualizacampos();
     $sql = " update infla set ";
     $virgula = "";
     if(trim($this->i02_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["i02_codigo"])){ 
       $sql  .= $virgula." i02_codigo = '$this->i02_codigo' ";
       $virgula = ",";
       if(trim($this->i02_codigo) == null ){ 
         $this->erro_sql = " Campo codigo do inflator nao Informado.";
         $this->erro_campo = "i02_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->i02_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["i02_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["i02_data_dia"] !="") ){ 
       $sql  .= $virgula." i02_data = '$this->i02_data' ";
       $virgula = ",";
       if(trim($this->i02_data) == null ){ 
         $this->erro_sql = " Campo data do inflator nao Informado.";
         $this->erro_campo = "i02_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["i02_data_dia"])){ 
         $sql  .= $virgula." i02_data = null ";
         $virgula = ",";
         if(trim($this->i02_data) == null ){ 
           $this->erro_sql = " Campo data do inflator nao Informado.";
           $this->erro_campo = "i02_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->i02_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["i02_valor"])){ 
       $sql  .= $virgula." i02_valor = $this->i02_valor ";
       $virgula = ",";
       if(trim($this->i02_valor) == null ){ 
         $this->erro_sql = " Campo valor do inflator nao Informado.";
         $this->erro_campo = "i02_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($i02_codigo!=null){
       $sql .= " i02_codigo = '$this->i02_codigo'";
     }
     if($i02_data!=null){
       $sql .= " and  i02_data = '$this->i02_data'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->i02_codigo,$this->i02_data));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,446,'$this->i02_codigo','A')");
         $resac = db_query("insert into db_acountkey values($acount,447,'$this->i02_data','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["i02_codigo"]))
           $resac = db_query("insert into db_acount values($acount,81,446,'".AddSlashes(pg_result($resaco,$conresaco,'i02_codigo'))."','$this->i02_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["i02_data"]))
           $resac = db_query("insert into db_acount values($acount,81,447,'".AddSlashes(pg_result($resaco,$conresaco,'i02_data'))."','$this->i02_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["i02_valor"]))
           $resac = db_query("insert into db_acount values($acount,81,448,'".AddSlashes(pg_result($resaco,$conresaco,'i02_valor'))."','$this->i02_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->i02_codigo."-".$this->i02_data;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->i02_codigo."-".$this->i02_data;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->i02_codigo."-".$this->i02_data;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($i02_codigo=null,$i02_data=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($i02_codigo,$i02_data));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,446,'$i02_codigo','E')");
         $resac = db_query("insert into db_acountkey values($acount,447,'$i02_data','E')");
         $resac = db_query("insert into db_acount values($acount,81,446,'','".AddSlashes(pg_result($resaco,$iresaco,'i02_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,81,447,'','".AddSlashes(pg_result($resaco,$iresaco,'i02_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,81,448,'','".AddSlashes(pg_result($resaco,$iresaco,'i02_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from infla
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($i02_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " i02_codigo = '$i02_codigo' ";
        }
        if($i02_data != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " i02_data = '$i02_data' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$i02_codigo."-".$i02_data;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$i02_codigo."-".$i02_data;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$i02_codigo."-".$i02_data;
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
        $this->erro_sql   = "Record Vazio na Tabela:infla";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $i02_codigo=null,$i02_data=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from infla ";
     $sql .= "      inner join inflan  on  inflan.i01_codigo = infla.i02_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($i02_codigo!=null ){
         $sql2 .= " where infla.i02_codigo = '$i02_codigo' "; 
       } 
       if($i02_data!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " infla.i02_data = '$i02_data' "; 
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
   function sql_query_file ( $i02_codigo=null,$i02_data=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from infla ";
     $sql2 = "";
     if($dbwhere==""){
       if($i02_codigo!=null ){
         $sql2 .= " where infla.i02_codigo = '$i02_codigo' "; 
       } 
       if($i02_data!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " infla.i02_data = '$i02_data' "; 
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