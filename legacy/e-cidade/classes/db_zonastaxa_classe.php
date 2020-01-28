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

//MODULO: cadastro
//CLASSE DA ENTIDADE zonastaxa
class cl_zonastaxa { 
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
   var $j57_zona = 0; 
   var $j57_receit = 0; 
   var $j57_anousu = 0; 
   var $j57_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j57_zona = int8 = Zona Fiscal 
                 j57_receit = int4 = Receita 
                 j57_anousu = int4 = Execício 
                 j57_valor = float8 = Valor 
                 ";
   //funcao construtor da classe 
   function cl_zonastaxa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("zonastaxa"); 
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
       $this->j57_zona = ($this->j57_zona == ""?@$GLOBALS["HTTP_POST_VARS"]["j57_zona"]:$this->j57_zona);
       $this->j57_receit = ($this->j57_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["j57_receit"]:$this->j57_receit);
       $this->j57_anousu = ($this->j57_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j57_anousu"]:$this->j57_anousu);
       $this->j57_valor = ($this->j57_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["j57_valor"]:$this->j57_valor);
     }else{
       $this->j57_zona = ($this->j57_zona == ""?@$GLOBALS["HTTP_POST_VARS"]["j57_zona"]:$this->j57_zona);
       $this->j57_receit = ($this->j57_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["j57_receit"]:$this->j57_receit);
       $this->j57_anousu = ($this->j57_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j57_anousu"]:$this->j57_anousu);
     }
   }
   // funcao para inclusao
   function incluir ($j57_zona,$j57_receit,$j57_anousu){ 
      $this->atualizacampos();
     if($this->j57_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "j57_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->j57_zona = $j57_zona; 
       $this->j57_receit = $j57_receit; 
       $this->j57_anousu = $j57_anousu; 
     if(($this->j57_zona == null) || ($this->j57_zona == "") ){ 
       $this->erro_sql = " Campo j57_zona nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->j57_receit == null) || ($this->j57_receit == "") ){ 
       $this->erro_sql = " Campo j57_receit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->j57_anousu == null) || ($this->j57_anousu == "") ){ 
       $this->erro_sql = " Campo j57_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into zonastaxa(
                                       j57_zona 
                                      ,j57_receit 
                                      ,j57_anousu 
                                      ,j57_valor 
                       )
                values (
                                $this->j57_zona 
                               ,$this->j57_receit 
                               ,$this->j57_anousu 
                               ,$this->j57_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Taxas por zona/ano ($this->j57_zona."-".$this->j57_receit."-".$this->j57_anousu) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Taxas por zona/ano já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Taxas por zona/ano ($this->j57_zona."-".$this->j57_receit."-".$this->j57_anousu) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j57_zona."-".$this->j57_receit."-".$this->j57_anousu;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j57_zona,$this->j57_receit,$this->j57_anousu));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6014,'$this->j57_zona','I')");
       $resac = db_query("insert into db_acountkey values($acount,6015,'$this->j57_receit','I')");
       $resac = db_query("insert into db_acountkey values($acount,6016,'$this->j57_anousu','I')");
       $resac = db_query("insert into db_acount values($acount,965,6014,'','".AddSlashes(pg_result($resaco,0,'j57_zona'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,965,6015,'','".AddSlashes(pg_result($resaco,0,'j57_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,965,6016,'','".AddSlashes(pg_result($resaco,0,'j57_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,965,6017,'','".AddSlashes(pg_result($resaco,0,'j57_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j57_zona=null,$j57_receit=null,$j57_anousu=null) { 
      $this->atualizacampos();
     $sql = " update zonastaxa set ";
     $virgula = "";
     if(trim($this->j57_zona)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j57_zona"])){ 
       $sql  .= $virgula." j57_zona = $this->j57_zona ";
       $virgula = ",";
       if(trim($this->j57_zona) == null ){ 
         $this->erro_sql = " Campo Zona Fiscal nao Informado.";
         $this->erro_campo = "j57_zona";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j57_receit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j57_receit"])){ 
       $sql  .= $virgula." j57_receit = $this->j57_receit ";
       $virgula = ",";
       if(trim($this->j57_receit) == null ){ 
         $this->erro_sql = " Campo Receita nao Informado.";
         $this->erro_campo = "j57_receit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j57_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j57_anousu"])){ 
       $sql  .= $virgula." j57_anousu = $this->j57_anousu ";
       $virgula = ",";
       if(trim($this->j57_anousu) == null ){ 
         $this->erro_sql = " Campo Execício nao Informado.";
         $this->erro_campo = "j57_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j57_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j57_valor"])){ 
       $sql  .= $virgula." j57_valor = $this->j57_valor ";
       $virgula = ",";
       if(trim($this->j57_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "j57_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j57_zona!=null){
       $sql .= " j57_zona = $this->j57_zona";
     }
     if($j57_receit!=null){
       $sql .= " and  j57_receit = $this->j57_receit";
     }
     if($j57_anousu!=null){
       $sql .= " and  j57_anousu = $this->j57_anousu";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j57_zona,$this->j57_receit,$this->j57_anousu));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6014,'$this->j57_zona','A')");
         $resac = db_query("insert into db_acountkey values($acount,6015,'$this->j57_receit','A')");
         $resac = db_query("insert into db_acountkey values($acount,6016,'$this->j57_anousu','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j57_zona"]))
           $resac = db_query("insert into db_acount values($acount,965,6014,'".AddSlashes(pg_result($resaco,$conresaco,'j57_zona'))."','$this->j57_zona',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j57_receit"]))
           $resac = db_query("insert into db_acount values($acount,965,6015,'".AddSlashes(pg_result($resaco,$conresaco,'j57_receit'))."','$this->j57_receit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j57_anousu"]))
           $resac = db_query("insert into db_acount values($acount,965,6016,'".AddSlashes(pg_result($resaco,$conresaco,'j57_anousu'))."','$this->j57_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j57_valor"]))
           $resac = db_query("insert into db_acount values($acount,965,6017,'".AddSlashes(pg_result($resaco,$conresaco,'j57_valor'))."','$this->j57_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Taxas por zona/ano nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j57_zona."-".$this->j57_receit."-".$this->j57_anousu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Taxas por zona/ano nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j57_zona."-".$this->j57_receit."-".$this->j57_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j57_zona."-".$this->j57_receit."-".$this->j57_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j57_zona=null,$j57_receit=null,$j57_anousu=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j57_zona,$j57_receit,$j57_anousu));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6014,'$j57_zona','E')");
         $resac = db_query("insert into db_acountkey values($acount,6015,'$j57_receit','E')");
         $resac = db_query("insert into db_acountkey values($acount,6016,'$j57_anousu','E')");
         $resac = db_query("insert into db_acount values($acount,965,6014,'','".AddSlashes(pg_result($resaco,$iresaco,'j57_zona'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,965,6015,'','".AddSlashes(pg_result($resaco,$iresaco,'j57_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,965,6016,'','".AddSlashes(pg_result($resaco,$iresaco,'j57_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,965,6017,'','".AddSlashes(pg_result($resaco,$iresaco,'j57_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from zonastaxa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j57_zona != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j57_zona = $j57_zona ";
        }
        if($j57_receit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j57_receit = $j57_receit ";
        }
        if($j57_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j57_anousu = $j57_anousu ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Taxas por zona/ano nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j57_zona."-".$j57_receit."-".$j57_anousu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Taxas por zona/ano nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j57_zona."-".$j57_receit."-".$j57_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j57_zona."-".$j57_receit."-".$j57_anousu;
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
        $this->erro_sql   = "Record Vazio na Tabela:zonastaxa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j57_zona=null,$j57_receit=null,$j57_anousu=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from zonastaxa ";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = zonastaxa.j57_receit";
     $sql .= "      inner join zonas  on  zonas.j50_zona = zonastaxa.j57_zona";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql2 = "";
     if($dbwhere==""){
       if($j57_zona!=null ){
         $sql2 .= " where zonastaxa.j57_zona = $j57_zona "; 
       } 
       if($j57_receit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " zonastaxa.j57_receit = $j57_receit "; 
       } 
       if($j57_anousu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " zonastaxa.j57_anousu = $j57_anousu "; 
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
   function sql_query_file ( $j57_zona=null,$j57_receit=null,$j57_anousu=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from zonastaxa ";
     $sql2 = "";
     if($dbwhere==""){
       if($j57_zona!=null ){
         $sql2 .= " where zonastaxa.j57_zona = $j57_zona "; 
       } 
       if($j57_receit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " zonastaxa.j57_receit = $j57_receit "; 
       } 
       if($j57_anousu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " zonastaxa.j57_anousu = $j57_anousu "; 
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