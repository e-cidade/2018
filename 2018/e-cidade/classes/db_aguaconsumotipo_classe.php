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

//MODULO: agua
//CLASSE DA ENTIDADE aguaconsumotipo
class cl_aguaconsumotipo { 
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
   var $x25_codconsumotipo = 0; 
   var $x25_receit = 0; 
   var $x25_descr = null; 
   var $x25_codhist = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x25_codconsumotipo = int4 = Codigo 
                 x25_receit = int4 = Receita 
                 x25_descr = varchar(40) = Descricao 
                 x25_codhist = int4 = Historico 
                 ";
   //funcao construtor da classe 
   function cl_aguaconsumotipo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguaconsumotipo"); 
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
       $this->x25_codconsumotipo = ($this->x25_codconsumotipo == ""?@$GLOBALS["HTTP_POST_VARS"]["x25_codconsumotipo"]:$this->x25_codconsumotipo);
       $this->x25_receit = ($this->x25_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["x25_receit"]:$this->x25_receit);
       $this->x25_descr = ($this->x25_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["x25_descr"]:$this->x25_descr);
       $this->x25_codhist = ($this->x25_codhist == ""?@$GLOBALS["HTTP_POST_VARS"]["x25_codhist"]:$this->x25_codhist);
     }else{
       $this->x25_codconsumotipo = ($this->x25_codconsumotipo == ""?@$GLOBALS["HTTP_POST_VARS"]["x25_codconsumotipo"]:$this->x25_codconsumotipo);
     }
   }
   // funcao para inclusao
   function incluir ($x25_codconsumotipo){ 
      $this->atualizacampos();
     if($this->x25_receit == null ){ 
       $this->erro_sql = " Campo Receita nao Informado.";
       $this->erro_campo = "x25_receit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x25_descr == null ){ 
       $this->erro_sql = " Campo Descricao nao Informado.";
       $this->erro_campo = "x25_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x25_codhist == null ){ 
       $this->erro_sql = " Campo Historico nao Informado.";
       $this->erro_campo = "x25_codhist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($x25_codconsumotipo == "" || $x25_codconsumotipo == null ){
       $result = db_query("select nextval('aguaconsumotipo_x25_codconsumotipo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: aguaconsumotipo_x25_codconsumotipo_seq do campo: x25_codconsumotipo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->x25_codconsumotipo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from aguaconsumotipo_x25_codconsumotipo_seq");
       if(($result != false) && (pg_result($result,0,0) < $x25_codconsumotipo)){
         $this->erro_sql = " Campo x25_codconsumotipo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->x25_codconsumotipo = $x25_codconsumotipo; 
       }
     }
     if(($this->x25_codconsumotipo == null) || ($this->x25_codconsumotipo == "") ){ 
       $this->erro_sql = " Campo x25_codconsumotipo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguaconsumotipo(
                                       x25_codconsumotipo 
                                      ,x25_receit 
                                      ,x25_descr 
                                      ,x25_codhist 
                       )
                values (
                                $this->x25_codconsumotipo 
                               ,$this->x25_receit 
                               ,'$this->x25_descr' 
                               ,$this->x25_codhist 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "aguaconsumotipo ($this->x25_codconsumotipo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "aguaconsumotipo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "aguaconsumotipo ($this->x25_codconsumotipo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x25_codconsumotipo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->x25_codconsumotipo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8458,'$this->x25_codconsumotipo','I')");
       $resac = db_query("insert into db_acount values($acount,1445,8458,'','".AddSlashes(pg_result($resaco,0,'x25_codconsumotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1445,8459,'','".AddSlashes(pg_result($resaco,0,'x25_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1445,8460,'','".AddSlashes(pg_result($resaco,0,'x25_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1445,8461,'','".AddSlashes(pg_result($resaco,0,'x25_codhist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($x25_codconsumotipo=null) { 
      $this->atualizacampos();
     $sql = " update aguaconsumotipo set ";
     $virgula = "";
     if(trim($this->x25_codconsumotipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x25_codconsumotipo"])){ 
       $sql  .= $virgula." x25_codconsumotipo = $this->x25_codconsumotipo ";
       $virgula = ",";
       if(trim($this->x25_codconsumotipo) == null ){ 
         $this->erro_sql = " Campo Codigo nao Informado.";
         $this->erro_campo = "x25_codconsumotipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x25_receit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x25_receit"])){ 
       $sql  .= $virgula." x25_receit = $this->x25_receit ";
       $virgula = ",";
       if(trim($this->x25_receit) == null ){ 
         $this->erro_sql = " Campo Receita nao Informado.";
         $this->erro_campo = "x25_receit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x25_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x25_descr"])){ 
       $sql  .= $virgula." x25_descr = '$this->x25_descr' ";
       $virgula = ",";
       if(trim($this->x25_descr) == null ){ 
         $this->erro_sql = " Campo Descricao nao Informado.";
         $this->erro_campo = "x25_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x25_codhist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x25_codhist"])){ 
       $sql  .= $virgula." x25_codhist = $this->x25_codhist ";
       $virgula = ",";
       if(trim($this->x25_codhist) == null ){ 
         $this->erro_sql = " Campo Historico nao Informado.";
         $this->erro_campo = "x25_codhist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($x25_codconsumotipo!=null){
       $sql .= " x25_codconsumotipo = $this->x25_codconsumotipo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->x25_codconsumotipo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8458,'$this->x25_codconsumotipo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x25_codconsumotipo"]))
           $resac = db_query("insert into db_acount values($acount,1445,8458,'".AddSlashes(pg_result($resaco,$conresaco,'x25_codconsumotipo'))."','$this->x25_codconsumotipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x25_receit"]))
           $resac = db_query("insert into db_acount values($acount,1445,8459,'".AddSlashes(pg_result($resaco,$conresaco,'x25_receit'))."','$this->x25_receit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x25_descr"]))
           $resac = db_query("insert into db_acount values($acount,1445,8460,'".AddSlashes(pg_result($resaco,$conresaco,'x25_descr'))."','$this->x25_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x25_codhist"]))
           $resac = db_query("insert into db_acount values($acount,1445,8461,'".AddSlashes(pg_result($resaco,$conresaco,'x25_codhist'))."','$this->x25_codhist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguaconsumotipo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x25_codconsumotipo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguaconsumotipo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x25_codconsumotipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x25_codconsumotipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($x25_codconsumotipo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($x25_codconsumotipo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8458,'$x25_codconsumotipo','E')");
         $resac = db_query("insert into db_acount values($acount,1445,8458,'','".AddSlashes(pg_result($resaco,$iresaco,'x25_codconsumotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1445,8459,'','".AddSlashes(pg_result($resaco,$iresaco,'x25_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1445,8460,'','".AddSlashes(pg_result($resaco,$iresaco,'x25_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1445,8461,'','".AddSlashes(pg_result($resaco,$iresaco,'x25_codhist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from aguaconsumotipo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($x25_codconsumotipo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " x25_codconsumotipo = $x25_codconsumotipo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguaconsumotipo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x25_codconsumotipo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguaconsumotipo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x25_codconsumotipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$x25_codconsumotipo;
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
        $this->erro_sql   = "Record Vazio na Tabela:aguaconsumotipo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $x25_codconsumotipo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguaconsumotipo ";
     $sql .= "      inner join histcalc  on  histcalc.k01_codigo = aguaconsumotipo.x25_codhist";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = aguaconsumotipo.x25_receit";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql2 = "";
     if($dbwhere==""){
       if($x25_codconsumotipo!=null ){
         $sql2 .= " where aguaconsumotipo.x25_codconsumotipo = $x25_codconsumotipo "; 
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
   function sql_query_file ( $x25_codconsumotipo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguaconsumotipo ";
     $sql2 = "";
     if($dbwhere==""){
       if($x25_codconsumotipo!=null ){
         $sql2 .= " where aguaconsumotipo.x25_codconsumotipo = $x25_codconsumotipo "; 
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