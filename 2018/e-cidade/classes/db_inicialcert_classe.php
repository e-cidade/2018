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

//MODULO: juridico
//CLASSE DA ENTIDADE inicialcert
class cl_inicialcert { 
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
   var $v51_inicial = 0; 
   var $v51_certidao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 v51_inicial = int4 = Inicial Numero 
                 v51_certidao = int4 = Certidão 
                 ";
   //funcao construtor da classe 
   function cl_inicialcert() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("inicialcert"); 
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
       $this->v51_inicial = ($this->v51_inicial == ""?@$GLOBALS["HTTP_POST_VARS"]["v51_inicial"]:$this->v51_inicial);
       $this->v51_certidao = ($this->v51_certidao == ""?@$GLOBALS["HTTP_POST_VARS"]["v51_certidao"]:$this->v51_certidao);
     }else{
       $this->v51_inicial = ($this->v51_inicial == ""?@$GLOBALS["HTTP_POST_VARS"]["v51_inicial"]:$this->v51_inicial);
       $this->v51_certidao = ($this->v51_certidao == ""?@$GLOBALS["HTTP_POST_VARS"]["v51_certidao"]:$this->v51_certidao);
     }
   }
   // funcao para inclusao
   function incluir ($v51_inicial,$v51_certidao){ 
      $this->atualizacampos();
       $this->v51_inicial = $v51_inicial; 
       $this->v51_certidao = $v51_certidao; 
     if(($this->v51_inicial == null) || ($this->v51_inicial == "") ){ 
       $this->erro_sql = " Campo v51_inicial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->v51_certidao == null) || ($this->v51_certidao == "") ){ 
       $this->erro_sql = " Campo v51_certidao nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into inicialcert(
                                       v51_inicial 
                                      ,v51_certidao 
                       )
                values (
                                $this->v51_inicial 
                               ,$this->v51_certidao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Inicial Certidão ($this->v51_inicial."-".$this->v51_certidao) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Inicial Certidão já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Inicial Certidão ($this->v51_inicial."-".$this->v51_certidao) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v51_inicial."-".$this->v51_certidao;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->v51_inicial,$this->v51_certidao));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,2567,'$this->v51_inicial','I')");
       $resac = db_query("insert into db_acountkey values($acount,575,'$this->v51_certidao','I')");
       $resac = db_query("insert into db_acount values($acount,111,2567,'','".AddSlashes(pg_result($resaco,0,'v51_inicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,111,575,'','".AddSlashes(pg_result($resaco,0,'v51_certidao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($v51_inicial=null,$v51_certidao=null) { 
      $this->atualizacampos();
     $sql = " update inicialcert set ";
     $virgula = "";
     if(trim($this->v51_inicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v51_inicial"])){ 
       $sql  .= $virgula." v51_inicial = $this->v51_inicial ";
       $virgula = ",";
       if(trim($this->v51_inicial) == null ){ 
         $this->erro_sql = " Campo Inicial Numero nao Informado.";
         $this->erro_campo = "v51_inicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v51_certidao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v51_certidao"])){ 
       $sql  .= $virgula." v51_certidao = $this->v51_certidao ";
       $virgula = ",";
       if(trim($this->v51_certidao) == null ){ 
         $this->erro_sql = " Campo Certidão nao Informado.";
         $this->erro_campo = "v51_certidao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($v51_inicial!=null){
       $sql .= " v51_inicial = $this->v51_inicial";
     }
     if($v51_certidao!=null){
       $sql .= " and  v51_certidao = $this->v51_certidao";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->v51_inicial,$this->v51_certidao));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2567,'$this->v51_inicial','A')");
         $resac = db_query("insert into db_acountkey values($acount,575,'$this->v51_certidao','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v51_inicial"]))
           $resac = db_query("insert into db_acount values($acount,111,2567,'".AddSlashes(pg_result($resaco,$conresaco,'v51_inicial'))."','$this->v51_inicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v51_certidao"]))
           $resac = db_query("insert into db_acount values($acount,111,575,'".AddSlashes(pg_result($resaco,$conresaco,'v51_certidao'))."','$this->v51_certidao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Inicial Certidão nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v51_inicial."-".$this->v51_certidao;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Inicial Certidão nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v51_inicial."-".$this->v51_certidao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v51_inicial."-".$this->v51_certidao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($v51_inicial=null,$v51_certidao=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($v51_inicial,$v51_certidao));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2567,'$v51_inicial','E')");
         $resac = db_query("insert into db_acountkey values($acount,575,'$v51_certidao','E')");
         $resac = db_query("insert into db_acount values($acount,111,2567,'','".AddSlashes(pg_result($resaco,$iresaco,'v51_inicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,111,575,'','".AddSlashes(pg_result($resaco,$iresaco,'v51_certidao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from inicialcert
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($v51_inicial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v51_inicial = $v51_inicial ";
        }
        if($v51_certidao != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v51_certidao = $v51_certidao ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Inicial Certidão nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v51_inicial."-".$v51_certidao;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Inicial Certidão nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v51_inicial."-".$v51_certidao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v51_inicial."-".$v51_certidao;
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
        $this->erro_sql   = "Record Vazio na Tabela:inicialcert";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $v51_inicial=null,$v51_certidao=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from inicialcert ";
     $sql .= "      inner join certid  on  certid.v13_certid = inicialcert.v51_certidao";
     $sql .= "      inner join inicial  on  inicial.v50_inicial = inicialcert.v51_inicial";
     $sql .= "      inner join advog  on  advog.v57_numcgm = inicial.v50_advog";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = inicial.v50_id_login";
     $sql .= "      inner join localiza  on  localiza.v54_codlocal = inicial.v50_codlocal";
     $sql2 = "";
     if($dbwhere==""){
       if($v51_inicial!=null ){
         $sql2 .= " where inicialcert.v51_inicial = $v51_inicial "; 
       } 
       if($v51_certidao!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " inicialcert.v51_certidao = $v51_certidao "; 
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
   function sql_query_file ( $v51_inicial=null,$v51_certidao=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from inicialcert ";
     $sql2 = "";
     if($dbwhere==""){
       if($v51_inicial!=null ){
         $sql2 .= " where inicialcert.v51_inicial = $v51_inicial "; 
       } 
       if($v51_certidao!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " inicialcert.v51_certidao = $v51_certidao "; 
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