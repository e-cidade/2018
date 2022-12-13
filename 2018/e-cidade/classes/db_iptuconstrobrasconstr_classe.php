<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: cadastro
//CLASSE DA ENTIDADE iptuconstrobrasconstr
class cl_iptuconstrobrasconstr { 
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
   var $j132_sequencial = 0; 
   var $j132_matric = 0; 
   var $j132_idconstr = 0; 
   var $j132_obrasconstr = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j132_sequencial = int4 = sequencial 
                 j132_matric = int4 = Matric 
                 j132_idconstr = int4 = idconstr 
                 j132_obrasconstr = int4 = obrasconstr 
                 ";
   //funcao construtor da classe 
   function cl_iptuconstrobrasconstr() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("iptuconstrobrasconstr"); 
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
       $this->j132_sequencial = ($this->j132_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j132_sequencial"]:$this->j132_sequencial);
       $this->j132_matric = ($this->j132_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j132_matric"]:$this->j132_matric);
       $this->j132_idconstr = ($this->j132_idconstr == ""?@$GLOBALS["HTTP_POST_VARS"]["j132_idconstr"]:$this->j132_idconstr);
       $this->j132_obrasconstr = ($this->j132_obrasconstr == ""?@$GLOBALS["HTTP_POST_VARS"]["j132_obrasconstr"]:$this->j132_obrasconstr);
     }else{
       $this->j132_sequencial = ($this->j132_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j132_sequencial"]:$this->j132_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($j132_sequencial){ 
      $this->atualizacampos();
     if($this->j132_matric == null ){ 
       $this->erro_sql = " Campo Matric nao Informado.";
       $this->erro_campo = "j132_matric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j132_idconstr == null ){ 
       $this->erro_sql = " Campo idconstr nao Informado.";
       $this->erro_campo = "j132_idconstr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j132_obrasconstr == null ){ 
       $this->erro_sql = " Campo obrasconstr nao Informado.";
       $this->erro_campo = "j132_obrasconstr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j132_sequencial == "" || $j132_sequencial == null ){
       $result = db_query("select nextval('iptuconstrobrasconstr_j132_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: iptuconstrobrasconstr_j132_sequencial_seq do campo: j132_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j132_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from iptuconstrobrasconstr_j132_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $j132_sequencial)){
         $this->erro_sql = " Campo j132_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j132_sequencial = $j132_sequencial; 
       }
     }
     if(($this->j132_sequencial == null) || ($this->j132_sequencial == "") ){ 
       $this->erro_sql = " Campo j132_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into iptuconstrobrasconstr(
                                       j132_sequencial 
                                      ,j132_matric 
                                      ,j132_idconstr 
                                      ,j132_obrasconstr 
                       )
                values (
                                $this->j132_sequencial 
                               ,$this->j132_matric 
                               ,$this->j132_idconstr 
                               ,$this->j132_obrasconstr 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "iptuconstr obrasconstr ($this->j132_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "iptuconstr obrasconstr já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "iptuconstr obrasconstr ($this->j132_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j132_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j132_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18656,'$this->j132_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3302,18656,'','".AddSlashes(pg_result($resaco,0,'j132_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3302,18653,'','".AddSlashes(pg_result($resaco,0,'j132_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3302,18654,'','".AddSlashes(pg_result($resaco,0,'j132_idconstr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3302,18655,'','".AddSlashes(pg_result($resaco,0,'j132_obrasconstr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j132_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update iptuconstrobrasconstr set ";
     $virgula = "";
     if(trim($this->j132_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j132_sequencial"])){ 
       $sql  .= $virgula." j132_sequencial = $this->j132_sequencial ";
       $virgula = ",";
       if(trim($this->j132_sequencial) == null ){ 
         $this->erro_sql = " Campo sequencial nao Informado.";
         $this->erro_campo = "j132_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j132_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j132_matric"])){ 
       $sql  .= $virgula." j132_matric = $this->j132_matric ";
       $virgula = ",";
       if(trim($this->j132_matric) == null ){ 
         $this->erro_sql = " Campo Matric nao Informado.";
         $this->erro_campo = "j132_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j132_idconstr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j132_idconstr"])){ 
       $sql  .= $virgula." j132_idconstr = $this->j132_idconstr ";
       $virgula = ",";
       if(trim($this->j132_idconstr) == null ){ 
         $this->erro_sql = " Campo idconstr nao Informado.";
         $this->erro_campo = "j132_idconstr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j132_obrasconstr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j132_obrasconstr"])){ 
       $sql  .= $virgula." j132_obrasconstr = $this->j132_obrasconstr ";
       $virgula = ",";
       if(trim($this->j132_obrasconstr) == null ){ 
         $this->erro_sql = " Campo obrasconstr nao Informado.";
         $this->erro_campo = "j132_obrasconstr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j132_sequencial!=null){
       $sql .= " j132_sequencial = $this->j132_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j132_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18656,'$this->j132_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j132_sequencial"]) || $this->j132_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3302,18656,'".AddSlashes(pg_result($resaco,$conresaco,'j132_sequencial'))."','$this->j132_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j132_matric"]) || $this->j132_matric != "")
           $resac = db_query("insert into db_acount values($acount,3302,18653,'".AddSlashes(pg_result($resaco,$conresaco,'j132_matric'))."','$this->j132_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j132_idconstr"]) || $this->j132_idconstr != "")
           $resac = db_query("insert into db_acount values($acount,3302,18654,'".AddSlashes(pg_result($resaco,$conresaco,'j132_idconstr'))."','$this->j132_idconstr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j132_obrasconstr"]) || $this->j132_obrasconstr != "")
           $resac = db_query("insert into db_acount values($acount,3302,18655,'".AddSlashes(pg_result($resaco,$conresaco,'j132_obrasconstr'))."','$this->j132_obrasconstr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "iptuconstr obrasconstr nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j132_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "iptuconstr obrasconstr nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j132_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j132_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j132_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j132_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18656,'$j132_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3302,18656,'','".AddSlashes(pg_result($resaco,$iresaco,'j132_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3302,18653,'','".AddSlashes(pg_result($resaco,$iresaco,'j132_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3302,18654,'','".AddSlashes(pg_result($resaco,$iresaco,'j132_idconstr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3302,18655,'','".AddSlashes(pg_result($resaco,$iresaco,'j132_obrasconstr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from iptuconstrobrasconstr
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j132_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j132_sequencial = $j132_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "iptuconstr obrasconstr nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j132_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "iptuconstr obrasconstr nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j132_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j132_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:iptuconstrobrasconstr";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $j132_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptuconstrobrasconstr ";
     $sql .= "      inner join iptuconstr  on  iptuconstr.j39_matric = iptuconstrobrasconstr.j132_matric and  iptuconstr.j39_idcons = iptuconstrobrasconstr.j132_idconstr";
     $sql .= "      inner join obrasconstr  on  obrasconstr.ob08_codconstr = iptuconstrobrasconstr.j132_obrasconstr";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = iptuconstr.j39_codigo";
     $sql .= "      inner join iptubase  on  iptubase.j01_matric = iptuconstr.j39_matric";
     $sql .= "      inner join caracter  on  caracter.j31_codigo = obrasconstr.ob08_ocupacao";
     $sql .= "      inner join obras  as a on   a.ob01_codobra = obrasconstr.ob08_codobra";
     $sql2 = "";
     if($dbwhere==""){
       if($j132_sequencial!=null ){
         $sql2 .= " where iptuconstrobrasconstr.j132_sequencial = $j132_sequencial "; 
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
   function sql_query_file ( $j132_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptuconstrobrasconstr ";
     $sql2 = "";
     if($dbwhere==""){
       if($j132_sequencial!=null ){
         $sql2 .= " where iptuconstrobrasconstr.j132_sequencial = $j132_sequencial "; 
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