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

//MODULO: material
//CLASSE DA ENTIDADE matestoquerec
class cl_matestoquerec { 
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
   var $m84_matestoqueinitrans = 0; 
   var $m84_matestoqueinirec = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m84_matestoqueinitrans = int8 = Lan�amento 
                 m84_matestoqueinirec = int8 = Lan�amento 
                 ";
   //funcao construtor da classe 
   function cl_matestoquerec() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matestoquerec"); 
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
       $this->m84_matestoqueinitrans = ($this->m84_matestoqueinitrans == ""?@$GLOBALS["HTTP_POST_VARS"]["m84_matestoqueinitrans"]:$this->m84_matestoqueinitrans);
       $this->m84_matestoqueinirec = ($this->m84_matestoqueinirec == ""?@$GLOBALS["HTTP_POST_VARS"]["m84_matestoqueinirec"]:$this->m84_matestoqueinirec);
     }else{
       $this->m84_matestoqueinitrans = ($this->m84_matestoqueinitrans == ""?@$GLOBALS["HTTP_POST_VARS"]["m84_matestoqueinitrans"]:$this->m84_matestoqueinitrans);
     }
   }
   // funcao para inclusao
   function incluir ($m84_matestoqueinitrans){ 
      $this->atualizacampos();
     if($this->m84_matestoqueinirec == null ){ 
       $this->erro_sql = " Campo Lan�amento nao Informado.";
       $this->erro_campo = "m84_matestoqueinirec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->m84_matestoqueinitrans = $m84_matestoqueinitrans; 
     if(($this->m84_matestoqueinitrans == null) || ($this->m84_matestoqueinitrans == "") ){ 
       $this->erro_sql = " Campo m84_matestoqueinitrans nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matestoquerec(
                                       m84_matestoqueinitrans 
                                      ,m84_matestoqueinirec 
                       )
                values (
                                $this->m84_matestoqueinitrans 
                               ,$this->m84_matestoqueinirec 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Confirma recebimento de transfer�ncia ($this->m84_matestoqueinitrans) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Confirma recebimento de transfer�ncia j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Confirma recebimento de transfer�ncia ($this->m84_matestoqueinitrans) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m84_matestoqueinitrans;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m84_matestoqueinitrans));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,6935,'$this->m84_matestoqueinitrans','I')");
       $resac = pg_query("insert into db_acount values($acount,1143,6935,'','".AddSlashes(pg_result($resaco,0,'m84_matestoqueinitrans'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1143,6936,'','".AddSlashes(pg_result($resaco,0,'m84_matestoqueinirec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m84_matestoqueinitrans=null) { 
      $this->atualizacampos();
     $sql = " update matestoquerec set ";
     $virgula = "";
     if(trim($this->m84_matestoqueinitrans)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m84_matestoqueinitrans"])){ 
       $sql  .= $virgula." m84_matestoqueinitrans = $this->m84_matestoqueinitrans ";
       $virgula = ",";
       if(trim($this->m84_matestoqueinitrans) == null ){ 
         $this->erro_sql = " Campo Lan�amento nao Informado.";
         $this->erro_campo = "m84_matestoqueinitrans";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m84_matestoqueinirec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m84_matestoqueinirec"])){ 
       $sql  .= $virgula." m84_matestoqueinirec = $this->m84_matestoqueinirec ";
       $virgula = ",";
       if(trim($this->m84_matestoqueinirec) == null ){ 
         $this->erro_sql = " Campo Lan�amento nao Informado.";
         $this->erro_campo = "m84_matestoqueinirec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m84_matestoqueinitrans!=null){
       $sql .= " m84_matestoqueinitrans = $this->m84_matestoqueinitrans";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m84_matestoqueinitrans));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,6935,'$this->m84_matestoqueinitrans','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m84_matestoqueinitrans"]))
           $resac = pg_query("insert into db_acount values($acount,1143,6935,'".AddSlashes(pg_result($resaco,$conresaco,'m84_matestoqueinitrans'))."','$this->m84_matestoqueinitrans',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m84_matestoqueinirec"]))
           $resac = pg_query("insert into db_acount values($acount,1143,6936,'".AddSlashes(pg_result($resaco,$conresaco,'m84_matestoqueinirec'))."','$this->m84_matestoqueinirec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Confirma recebimento de transfer�ncia nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m84_matestoqueinitrans;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Confirma recebimento de transfer�ncia nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m84_matestoqueinitrans;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m84_matestoqueinitrans;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m84_matestoqueinitrans=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m84_matestoqueinitrans));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,6935,'$m84_matestoqueinitrans','E')");
         $resac = pg_query("insert into db_acount values($acount,1143,6935,'','".AddSlashes(pg_result($resaco,$iresaco,'m84_matestoqueinitrans'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1143,6936,'','".AddSlashes(pg_result($resaco,$iresaco,'m84_matestoqueinirec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matestoquerec
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m84_matestoqueinitrans != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m84_matestoqueinitrans = $m84_matestoqueinitrans ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Confirma recebimento de transfer�ncia nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m84_matestoqueinitrans;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Confirma recebimento de transfer�ncia nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m84_matestoqueinitrans;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m84_matestoqueinitrans;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = @pg_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:matestoquerec";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $m84_matestoqueinitrans=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoquerec ";
     $sql .= "      inner join matestoqueini  on  matestoqueini.m80_codigo = matestoquerec.m84_matestoqueinitrans and  matestoqueini.m80_codigo = matestoquerec.m84_matestoqueinirec";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = matestoqueini.m80_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = matestoqueini.m80_coddepto";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = matestoqueini.m80_matestoqueitem";
     $sql .= "      inner join matestoquetipo  on  matestoquetipo.m81_codtipo = matestoqueini.m80_codtipo";
     $sql .= "      inner join db_usuarios  as a on   a.id_usuario = matestoqueini.m80_login";
     $sql .= "      inner join db_depart  as b on   b.coddepto = matestoqueini.m80_coddepto";
     $sql .= "      inner join matestoqueitem  as c on   c.m71_codlanc = matestoqueini.m80_matestoqueitem";
     $sql .= "      inner join matestoquetipo  as d on   d.m81_codtipo = matestoqueini.m80_codtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($m84_matestoqueinitrans!=null ){
         $sql2 .= " where matestoquerec.m84_matestoqueinitrans = $m84_matestoqueinitrans "; 
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
   function sql_query_file ( $m84_matestoqueinitrans=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoquerec ";
     $sql2 = "";
     if($dbwhere==""){
       if($m84_matestoqueinitrans!=null ){
         $sql2 .= " where matestoquerec.m84_matestoqueinitrans = $m84_matestoqueinitrans "; 
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