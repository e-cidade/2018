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

//MODULO: saude
//CLASSE DA ENTIDADE cids
class cl_cids { 
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
   var $sd22_c_codigo = null; 
   var $sd22_v_descr = null; 
   var $sd22_d_validade_dia = null; 
   var $sd22_d_validade_mes = null; 
   var $sd22_d_validade_ano = null; 
   var $sd22_d_validade = null; 
   var $sd22_c_restrsexo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd22_c_codigo = char(6) = Código 
                 sd22_v_descr = varchar(300) = Descrição 
                 sd22_d_validade = date = Validade 
                 sd22_c_restrsexo = char(1) = Restrição por Sexo 
                 ";
   //funcao construtor da classe 
   function cl_cids() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cids"); 
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
       $this->sd22_c_codigo = ($this->sd22_c_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd22_c_codigo"]:$this->sd22_c_codigo);
       $this->sd22_v_descr = ($this->sd22_v_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["sd22_v_descr"]:$this->sd22_v_descr);
       if($this->sd22_d_validade == ""){
         $this->sd22_d_validade_dia = ($this->sd22_d_validade_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd22_d_validade_dia"]:$this->sd22_d_validade_dia);
         $this->sd22_d_validade_mes = ($this->sd22_d_validade_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd22_d_validade_mes"]:$this->sd22_d_validade_mes);
         $this->sd22_d_validade_ano = ($this->sd22_d_validade_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd22_d_validade_ano"]:$this->sd22_d_validade_ano);
         if($this->sd22_d_validade_dia != ""){
            $this->sd22_d_validade = $this->sd22_d_validade_ano."-".$this->sd22_d_validade_mes."-".$this->sd22_d_validade_dia;
         }
       }
       $this->sd22_c_restrsexo = ($this->sd22_c_restrsexo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd22_c_restrsexo"]:$this->sd22_c_restrsexo);
     }else{
       $this->sd22_c_codigo = ($this->sd22_c_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd22_c_codigo"]:$this->sd22_c_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd22_c_codigo){ 
      $this->atualizacampos();
     if($this->sd22_v_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "sd22_v_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd22_d_validade == null ){ 
       $this->sd22_d_validade = "null";
     }
     if($this->sd22_c_restrsexo == null ){ 
       $this->sd22_c_restrsexo = "G";
     }
       $this->sd22_c_codigo = $sd22_c_codigo; 
     if(($this->sd22_c_codigo == null) || ($this->sd22_c_codigo == "") ){ 
       $this->erro_sql = " Campo sd22_c_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cids(
                                       sd22_c_codigo 
                                      ,sd22_v_descr 
                                      ,sd22_d_validade 
                                      ,sd22_c_restrsexo 
                       )
                values (
                                '$this->sd22_c_codigo' 
                               ,'$this->sd22_v_descr' 
                               ,".($this->sd22_d_validade == "null" || $this->sd22_d_validade == ""?"null":"'".$this->sd22_d_validade."'")." 
                               ,'$this->sd22_c_restrsexo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cids ($this->sd22_c_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cids já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cids ($this->sd22_c_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd22_c_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd22_c_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,100028,'$this->sd22_c_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,100009,100028,'','".AddSlashes(pg_result($resaco,0,'sd22_c_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100009,100029,'','".AddSlashes(pg_result($resaco,0,'sd22_v_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100009,100030,'','".AddSlashes(pg_result($resaco,0,'sd22_d_validade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100009,1008810,'','".AddSlashes(pg_result($resaco,0,'sd22_c_restrsexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($sd22_c_codigo=null) { 
      $this->atualizacampos();
     $sql = " update cids set ";
     $virgula = "";
     if(trim($this->sd22_c_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd22_c_codigo"])){ 
       $sql  .= $virgula." sd22_c_codigo = '$this->sd22_c_codigo' ";
       $virgula = ",";
       if(trim($this->sd22_c_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "sd22_c_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd22_v_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd22_v_descr"])){ 
       $sql  .= $virgula." sd22_v_descr = '$this->sd22_v_descr' ";
       $virgula = ",";
       if(trim($this->sd22_v_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "sd22_v_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd22_d_validade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd22_d_validade_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd22_d_validade_dia"] !="") ){ 
       $sql  .= $virgula." sd22_d_validade = '$this->sd22_d_validade' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd22_d_validade_dia"])){ 
         $sql  .= $virgula." sd22_d_validade = null ";
         $virgula = ",";
       }
     }
     if(trim($this->sd22_c_restrsexo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd22_c_restrsexo"])){ 
       $sql  .= $virgula." sd22_c_restrsexo = '$this->sd22_c_restrsexo' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($sd22_c_codigo!=null){
       $sql .= " sd22_c_codigo = '$this->sd22_c_codigo'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd22_c_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,100028,'$this->sd22_c_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd22_c_codigo"]))
           $resac = db_query("insert into db_acount values($acount,100009,100028,'".AddSlashes(pg_result($resaco,$conresaco,'sd22_c_codigo'))."','$this->sd22_c_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd22_v_descr"]))
           $resac = db_query("insert into db_acount values($acount,100009,100029,'".AddSlashes(pg_result($resaco,$conresaco,'sd22_v_descr'))."','$this->sd22_v_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd22_d_validade"]))
           $resac = db_query("insert into db_acount values($acount,100009,100030,'".AddSlashes(pg_result($resaco,$conresaco,'sd22_d_validade'))."','$this->sd22_d_validade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd22_c_restrsexo"]))
           $resac = db_query("insert into db_acount values($acount,100009,1008810,'".AddSlashes(pg_result($resaco,$conresaco,'sd22_c_restrsexo'))."','$this->sd22_c_restrsexo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cids nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd22_c_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cids nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd22_c_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd22_c_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($sd22_c_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd22_c_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,100028,'$sd22_c_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,100009,100028,'','".AddSlashes(pg_result($resaco,$iresaco,'sd22_c_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100009,100029,'','".AddSlashes(pg_result($resaco,$iresaco,'sd22_v_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100009,100030,'','".AddSlashes(pg_result($resaco,$iresaco,'sd22_d_validade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100009,1008810,'','".AddSlashes(pg_result($resaco,$iresaco,'sd22_c_restrsexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cids
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd22_c_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd22_c_codigo = '$sd22_c_codigo' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cids nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd22_c_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cids nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd22_c_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd22_c_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:cids";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $sd22_c_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cids ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd22_c_codigo!=null ){
         $sql2 .= " where cids.sd22_c_codigo = '$sd22_c_codigo' "; 
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
   function sql_query_file ( $sd22_c_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cids ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd22_c_codigo!=null ){
         $sql2 .= " where cids.sd22_c_codigo = '$sd22_c_codigo' "; 
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