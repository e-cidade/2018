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

//MODULO: empenho
//CLASSE DA ENTIDADE empagemovret
class cl_empagemovret { 
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
   var $e77_codmov = 0; 
   var $e77_codgera = 0; 
   var $e77_codret = 0; 
   var $e77_dataret_dia = null; 
   var $e77_dataret_mes = null; 
   var $e77_dataret_ano = null; 
   var $e77_dataret = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e77_codmov = int4 = Movimento 
                 e77_codgera = int4 = Código 
                 e77_codret = int4 = Código de retorno 
                 e77_dataret = date = Data retorno 
                 ";
   //funcao construtor da classe 
   function cl_empagemovret() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empagemovret"); 
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
       $this->e77_codmov = ($this->e77_codmov == ""?@$GLOBALS["HTTP_POST_VARS"]["e77_codmov"]:$this->e77_codmov);
       $this->e77_codgera = ($this->e77_codgera == ""?@$GLOBALS["HTTP_POST_VARS"]["e77_codgera"]:$this->e77_codgera);
       $this->e77_codret = ($this->e77_codret == ""?@$GLOBALS["HTTP_POST_VARS"]["e77_codret"]:$this->e77_codret);
       if($this->e77_dataret == ""){
         $this->e77_dataret_dia = ($this->e77_dataret_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["e77_dataret_dia"]:$this->e77_dataret_dia);
         $this->e77_dataret_mes = ($this->e77_dataret_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["e77_dataret_mes"]:$this->e77_dataret_mes);
         $this->e77_dataret_ano = ($this->e77_dataret_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["e77_dataret_ano"]:$this->e77_dataret_ano);
         if($this->e77_dataret_dia != ""){
            $this->e77_dataret = $this->e77_dataret_ano."-".$this->e77_dataret_mes."-".$this->e77_dataret_dia;
         }
       }
     }else{
       $this->e77_codmov = ($this->e77_codmov == ""?@$GLOBALS["HTTP_POST_VARS"]["e77_codmov"]:$this->e77_codmov);
       $this->e77_codgera = ($this->e77_codgera == ""?@$GLOBALS["HTTP_POST_VARS"]["e77_codgera"]:$this->e77_codgera);
     }
   }
   // funcao para inclusao
   function incluir ($e77_codmov,$e77_codgera){ 
      $this->atualizacampos();
     if($this->e77_codret == null ){ 
       $this->erro_sql = " Campo Código de retorno nao Informado.";
       $this->erro_campo = "e77_codret";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e77_dataret == null ){ 
       $this->e77_dataret = "null";
     }
       $this->e77_codmov = $e77_codmov; 
       $this->e77_codgera = $e77_codgera; 
     if(($this->e77_codmov == null) || ($this->e77_codmov == "") ){ 
       $this->erro_sql = " Campo e77_codmov nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->e77_codgera == null) || ($this->e77_codgera == "") ){ 
       $this->erro_sql = " Campo e77_codgera nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empagemovret(
                                       e77_codmov 
                                      ,e77_codgera 
                                      ,e77_codret 
                                      ,e77_dataret 
                       )
                values (
                                $this->e77_codmov 
                               ,$this->e77_codgera 
                               ,$this->e77_codret 
                               ,".($this->e77_dataret == "null" || $this->e77_dataret == ""?"null":"'".$this->e77_dataret."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Retorno dos movimentos ($this->e77_codmov."-".$this->e77_codgera) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Retorno dos movimentos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Retorno dos movimentos ($this->e77_codmov."-".$this->e77_codgera) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e77_codmov."-".$this->e77_codgera;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e77_codmov,$this->e77_codgera));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7289,'$this->e77_codmov','I')");
       $resac = db_query("insert into db_acountkey values($acount,7290,'$this->e77_codgera','I')");
       $resac = db_query("insert into db_acount values($acount,1209,7289,'','".AddSlashes(pg_result($resaco,0,'e77_codmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1209,7290,'','".AddSlashes(pg_result($resaco,0,'e77_codgera'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1209,7234,'','".AddSlashes(pg_result($resaco,0,'e77_codret'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1209,7232,'','".AddSlashes(pg_result($resaco,0,'e77_dataret'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e77_codmov=null,$e77_codgera=null) { 
      $this->atualizacampos();
     $sql = " update empagemovret set ";
     $virgula = "";
     if(trim($this->e77_codmov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e77_codmov"])){ 
       $sql  .= $virgula." e77_codmov = $this->e77_codmov ";
       $virgula = ",";
       if(trim($this->e77_codmov) == null ){ 
         $this->erro_sql = " Campo Movimento nao Informado.";
         $this->erro_campo = "e77_codmov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e77_codgera)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e77_codgera"])){ 
       $sql  .= $virgula." e77_codgera = $this->e77_codgera ";
       $virgula = ",";
       if(trim($this->e77_codgera) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "e77_codgera";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e77_codret)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e77_codret"])){ 
       $sql  .= $virgula." e77_codret = $this->e77_codret ";
       $virgula = ",";
       if(trim($this->e77_codret) == null ){ 
         $this->erro_sql = " Campo Código de retorno nao Informado.";
         $this->erro_campo = "e77_codret";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e77_dataret)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e77_dataret_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["e77_dataret_dia"] !="") ){ 
       $sql  .= $virgula." e77_dataret = '$this->e77_dataret' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["e77_dataret_dia"])){ 
         $sql  .= $virgula." e77_dataret = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($e77_codmov!=null){
       $sql .= " e77_codmov = $this->e77_codmov";
     }
     if($e77_codgera!=null){
       $sql .= " and  e77_codgera = $this->e77_codgera";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e77_codmov,$this->e77_codgera));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7289,'$this->e77_codmov','A')");
         $resac = db_query("insert into db_acountkey values($acount,7290,'$this->e77_codgera','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e77_codmov"]))
           $resac = db_query("insert into db_acount values($acount,1209,7289,'".AddSlashes(pg_result($resaco,$conresaco,'e77_codmov'))."','$this->e77_codmov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e77_codgera"]))
           $resac = db_query("insert into db_acount values($acount,1209,7290,'".AddSlashes(pg_result($resaco,$conresaco,'e77_codgera'))."','$this->e77_codgera',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e77_codret"]))
           $resac = db_query("insert into db_acount values($acount,1209,7234,'".AddSlashes(pg_result($resaco,$conresaco,'e77_codret'))."','$this->e77_codret',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e77_dataret"]))
           $resac = db_query("insert into db_acount values($acount,1209,7232,'".AddSlashes(pg_result($resaco,$conresaco,'e77_dataret'))."','$this->e77_dataret',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Retorno dos movimentos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e77_codmov."-".$this->e77_codgera;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Retorno dos movimentos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e77_codmov."-".$this->e77_codgera;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e77_codmov."-".$this->e77_codgera;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e77_codmov=null,$e77_codgera=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e77_codmov,$e77_codgera));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7289,'$e77_codmov','E')");
         $resac = db_query("insert into db_acountkey values($acount,7290,'$e77_codgera','E')");
         $resac = db_query("insert into db_acount values($acount,1209,7289,'','".AddSlashes(pg_result($resaco,$iresaco,'e77_codmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1209,7290,'','".AddSlashes(pg_result($resaco,$iresaco,'e77_codgera'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1209,7234,'','".AddSlashes(pg_result($resaco,$iresaco,'e77_codret'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1209,7232,'','".AddSlashes(pg_result($resaco,$iresaco,'e77_dataret'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from empagemovret
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e77_codmov != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e77_codmov = $e77_codmov ";
        }
        if($e77_codgera != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e77_codgera = $e77_codgera ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Retorno dos movimentos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e77_codmov."-".$e77_codgera;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Retorno dos movimentos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e77_codmov."-".$e77_codgera;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e77_codmov."-".$e77_codgera;
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
        $this->erro_sql   = "Record Vazio na Tabela:empagemovret";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $e77_codmov=null,$e77_codgera=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empagemovret ";
     $sql .= "      inner join empagemov  on  empagemov.e81_codmov = empagemovret.e77_codmov";
     $sql .= "      inner join empagegera  on  empagegera.e87_codgera = empagemovret.e77_codgera";
     $sql .= "      inner join errobanco  on  errobanco.e92_sequencia = empagemovret.e77_codret";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empagemov.e81_numemp";
     $sql .= "      inner join empage  on  empage.e80_codage = empagemov.e81_codage";
     $sql2 = "";
     if($dbwhere==""){
       if($e77_codmov!=null ){
         $sql2 .= " where empagemovret.e77_codmov = $e77_codmov "; 
       } 
       if($e77_codgera!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " empagemovret.e77_codgera = $e77_codgera "; 
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
   function sql_query_file ( $e77_codmov=null,$e77_codgera=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empagemovret ";
     $sql2 = "";
     if($dbwhere==""){
       if($e77_codmov!=null ){
         $sql2 .= " where empagemovret.e77_codmov = $e77_codmov "; 
       } 
       if($e77_codgera!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " empagemovret.e77_codgera = $e77_codgera "; 
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