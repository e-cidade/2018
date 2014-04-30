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

//MODULO: Agua
//CLASSE DA ENTIDADE agualeiturista
class cl_agualeiturista { 
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
   var $x16_dtini_dia = null; 
   var $x16_dtini_mes = null; 
   var $x16_dtini_ano = null; 
   var $x16_dtini = null; 
   var $x16_dtfim_dia = null; 
   var $x16_dtfim_mes = null; 
   var $x16_dtfim_ano = null; 
   var $x16_dtfim = null; 
   var $x16_numcgm = 0; 
   var $x16_senha = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x16_dtini = date = Data Inicio 
                 x16_dtfim = date = Data Fim 
                 x16_numcgm = int4 = Leiturista 
                 x16_senha = varchar(50) = Senha 
                 ";
   //funcao construtor da classe 
   function cl_agualeiturista() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("agualeiturista"); 
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
       if($this->x16_dtini == ""){
         $this->x16_dtini_dia = ($this->x16_dtini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["x16_dtini_dia"]:$this->x16_dtini_dia);
         $this->x16_dtini_mes = ($this->x16_dtini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["x16_dtini_mes"]:$this->x16_dtini_mes);
         $this->x16_dtini_ano = ($this->x16_dtini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["x16_dtini_ano"]:$this->x16_dtini_ano);
         if($this->x16_dtini_dia != ""){
            $this->x16_dtini = $this->x16_dtini_ano."-".$this->x16_dtini_mes."-".$this->x16_dtini_dia;
         }
       }
       if($this->x16_dtfim == ""){
         $this->x16_dtfim_dia = ($this->x16_dtfim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["x16_dtfim_dia"]:$this->x16_dtfim_dia);
         $this->x16_dtfim_mes = ($this->x16_dtfim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["x16_dtfim_mes"]:$this->x16_dtfim_mes);
         $this->x16_dtfim_ano = ($this->x16_dtfim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["x16_dtfim_ano"]:$this->x16_dtfim_ano);
         if($this->x16_dtfim_dia != ""){
            $this->x16_dtfim = $this->x16_dtfim_ano."-".$this->x16_dtfim_mes."-".$this->x16_dtfim_dia;
         }
       }
       $this->x16_numcgm = ($this->x16_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["x16_numcgm"]:$this->x16_numcgm);
       $this->x16_senha = ($this->x16_senha == ""?@$GLOBALS["HTTP_POST_VARS"]["x16_senha"]:$this->x16_senha);
     }else{
       $this->x16_numcgm = ($this->x16_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["x16_numcgm"]:$this->x16_numcgm);
     }
   }
   // funcao para inclusao
   function incluir ($x16_numcgm){ 
      $this->atualizacampos();
     if($this->x16_dtini == null ){ 
       $this->erro_sql = " Campo Data Inicio nao Informado.";
       $this->erro_campo = "x16_dtini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x16_dtfim == null ){ 
       $this->x16_dtfim = "null";
     }
     if($this->x16_senha == null ){ 
       $this->erro_sql = " Campo Senha nao Informado.";
       $this->erro_campo = "x16_senha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->x16_numcgm = $x16_numcgm; 
     if(($this->x16_numcgm == null) || ($this->x16_numcgm == "") ){ 
       $this->erro_sql = " Campo x16_numcgm nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     
     $sql = "insert into agualeiturista(
                                       x16_dtini 
                                      ,x16_dtfim 
                                      ,x16_numcgm 
                                      ,x16_senha 
                       )
                values (
                                ".($this->x16_dtini == "null" || $this->x16_dtini == ""?"null":"'".$this->x16_dtini."'")." 
                               ,".($this->x16_dtfim == "null" || $this->x16_dtfim == ""?"null":"'".$this->x16_dtfim."'")." 
                               ,$this->x16_numcgm 
                               ,'$this->x16_senha' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Leiturista ($this->x16_numcgm) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Leiturista já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Leiturista ($this->x16_numcgm) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x16_numcgm;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->x16_numcgm));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8468,'$this->x16_numcgm','I')");
       $resac = db_query("insert into db_acount values($acount,1438,8466,'','".AddSlashes(pg_result($resaco,0,'x16_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1438,8467,'','".AddSlashes(pg_result($resaco,0,'x16_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1438,8468,'','".AddSlashes(pg_result($resaco,0,'x16_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1438,15303,'','".AddSlashes(pg_result($resaco,0,'x16_senha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($x16_numcgm=null) { 
     $this->atualizacampos();
     $sql = " update agualeiturista set ";
     $virgula = "";
     if(trim($this->x16_dtini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x16_dtini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["x16_dtini_dia"] !="") ){ 
       $sql  .= $virgula." x16_dtini = '$this->x16_dtini' ";
       $virgula = ",";
       if(trim($this->x16_dtini) == null ){ 
         $this->erro_sql = " Campo Data Inicio nao Informado.";
         $this->erro_campo = "x16_dtini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["x16_dtini_dia"])){ 
         $sql  .= $virgula." x16_dtini = null ";
         $virgula = ",";
         if(trim($this->x16_dtini) == null ){ 
           $this->erro_sql = " Campo Data Inicio nao Informado.";
           $this->erro_campo = "x16_dtini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->x16_dtfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x16_dtfim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["x16_dtfim_dia"] !="") ){ 
       $sql  .= $virgula." x16_dtfim = '$this->x16_dtfim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["x16_dtfim_dia"])){ 
         $sql  .= $virgula." x16_dtfim = null ";
         $virgula = ",";
       }
     }
     if(trim($this->x16_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x16_numcgm"])){ 
       $sql  .= $virgula." x16_numcgm = $this->x16_numcgm ";
       $virgula = ",";
       if(trim($this->x16_numcgm) == null ){ 
         $this->erro_sql = " Campo Leiturista nao Informado.";
         $this->erro_campo = "x16_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x16_senha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x16_senha"])){ 
       $sql  .= $virgula." x16_senha = '$this->x16_senha' ";
       $virgula = ",";
       if(trim($this->x16_senha) == null ){ 
         $this->erro_sql = " Campo Senha nao Informado.";
         $this->erro_campo = "x16_senha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($x16_numcgm!=null){
       $sql .= " x16_numcgm = $this->x16_numcgm";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->x16_numcgm));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8468,'$this->x16_numcgm','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x16_dtini"]) || $this->x16_dtini != "")
           $resac = db_query("insert into db_acount values($acount,1438,8466,'".AddSlashes(pg_result($resaco,$conresaco,'x16_dtini'))."','$this->x16_dtini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x16_dtfim"]) || $this->x16_dtfim != "")
           $resac = db_query("insert into db_acount values($acount,1438,8467,'".AddSlashes(pg_result($resaco,$conresaco,'x16_dtfim'))."','$this->x16_dtfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x16_numcgm"]) || $this->x16_numcgm != "")
           $resac = db_query("insert into db_acount values($acount,1438,8468,'".AddSlashes(pg_result($resaco,$conresaco,'x16_numcgm'))."','$this->x16_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x16_senha"]) || $this->x16_senha != "")
           $resac = db_query("insert into db_acount values($acount,1438,15303,'".AddSlashes(pg_result($resaco,$conresaco,'x16_senha'))."','$this->x16_senha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Leiturista nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x16_numcgm;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Leiturista nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x16_numcgm;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x16_numcgm;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($x16_numcgm=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($x16_numcgm));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8468,'$x16_numcgm','E')");
         $resac = db_query("insert into db_acount values($acount,1438,8466,'','".AddSlashes(pg_result($resaco,$iresaco,'x16_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1438,8467,'','".AddSlashes(pg_result($resaco,$iresaco,'x16_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1438,8468,'','".AddSlashes(pg_result($resaco,$iresaco,'x16_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1438,15303,'','".AddSlashes(pg_result($resaco,$iresaco,'x16_senha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from agualeiturista
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($x16_numcgm != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " x16_numcgm = $x16_numcgm ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Leiturista nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x16_numcgm;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Leiturista nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x16_numcgm;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$x16_numcgm;
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
        $this->erro_sql   = "Record Vazio na Tabela:agualeiturista";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $x16_numcgm=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from agualeiturista ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = agualeiturista.x16_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($x16_numcgm!=null ){
         $sql2 .= " where agualeiturista.x16_numcgm = $x16_numcgm "; 
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
   function sql_query_file ( $x16_numcgm=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from agualeiturista ";
     $sql2 = "";
     if($dbwhere==""){
       if($x16_numcgm!=null ){
         $sql2 .= " where agualeiturista.x16_numcgm = $x16_numcgm "; 
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