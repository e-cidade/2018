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

//MODULO: fiscal
//CLASSE DA ENTIDADE autorec
class cl_autorec { 
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
   var $y57_codauto = 0; 
   var $y57_receit = 0; 
   var $y57_descr = null; 
   var $y57_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 y57_codauto = int4 = C�digo do Auto de Infra��o 
                 y57_receit = int4 = codigo da receita 
                 y57_descr = varchar(50) = Descri��o do lan�amento da receita 
                 y57_valor = float8 = Valor da Receita 
                 ";
   //funcao construtor da classe 
   function cl_autorec() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("autorec"); 
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
       $this->y57_codauto = ($this->y57_codauto == ""?@$GLOBALS["HTTP_POST_VARS"]["y57_codauto"]:$this->y57_codauto);
       $this->y57_receit = ($this->y57_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["y57_receit"]:$this->y57_receit);
       $this->y57_descr = ($this->y57_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["y57_descr"]:$this->y57_descr);
       $this->y57_valor = ($this->y57_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["y57_valor"]:$this->y57_valor);
     }else{
       $this->y57_codauto = ($this->y57_codauto == ""?@$GLOBALS["HTTP_POST_VARS"]["y57_codauto"]:$this->y57_codauto);
       $this->y57_receit = ($this->y57_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["y57_receit"]:$this->y57_receit);
     }
   }
   // funcao para inclusao
   function incluir ($y57_codauto,$y57_receit){ 
      $this->atualizacampos();
     if($this->y57_descr == null ){ 
       $this->erro_sql = " Campo Descri��o do lan�amento da receita nao Informado.";
       $this->erro_campo = "y57_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y57_valor == null ){ 
       $this->erro_sql = " Campo Valor da Receita nao Informado.";
       $this->erro_campo = "y57_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->y57_codauto = $y57_codauto; 
       $this->y57_receit = $y57_receit; 
     if(($this->y57_codauto == null) || ($this->y57_codauto == "") ){ 
       $this->erro_sql = " Campo y57_codauto nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->y57_receit == null) || ($this->y57_receit == "") ){ 
       $this->erro_sql = " Campo y57_receit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into autorec(
                                       y57_codauto 
                                      ,y57_receit 
                                      ,y57_descr 
                                      ,y57_valor 
                       )
                values (
                                $this->y57_codauto 
                               ,$this->y57_receit 
                               ,'$this->y57_descr' 
                               ,$this->y57_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "autorec ($this->y57_codauto."-".$this->y57_receit) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "autorec j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "autorec ($this->y57_codauto."-".$this->y57_receit) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y57_codauto."-".$this->y57_receit;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->y57_codauto,$this->y57_receit));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5008,'$this->y57_codauto','I')");
       $resac = db_query("insert into db_acountkey values($acount,5009,'$this->y57_receit','I')");
       $resac = db_query("insert into db_acount values($acount,707,5008,'','".AddSlashes(pg_result($resaco,0,'y57_codauto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,707,5009,'','".AddSlashes(pg_result($resaco,0,'y57_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,707,5010,'','".AddSlashes(pg_result($resaco,0,'y57_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,707,5011,'','".AddSlashes(pg_result($resaco,0,'y57_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($y57_codauto=null,$y57_receit=null) { 
      $this->atualizacampos();
     $sql = " update autorec set ";
     $virgula = "";
     if(trim($this->y57_codauto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y57_codauto"])){ 
       $sql  .= $virgula." y57_codauto = $this->y57_codauto ";
       $virgula = ",";
       if(trim($this->y57_codauto) == null ){ 
         $this->erro_sql = " Campo C�digo do Auto de Infra��o nao Informado.";
         $this->erro_campo = "y57_codauto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y57_receit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y57_receit"])){ 
       $sql  .= $virgula." y57_receit = $this->y57_receit ";
       $virgula = ",";
       if(trim($this->y57_receit) == null ){ 
         $this->erro_sql = " Campo codigo da receita nao Informado.";
         $this->erro_campo = "y57_receit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y57_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y57_descr"])){ 
       $sql  .= $virgula." y57_descr = '$this->y57_descr' ";
       $virgula = ",";
       if(trim($this->y57_descr) == null ){ 
         $this->erro_sql = " Campo Descri��o do lan�amento da receita nao Informado.";
         $this->erro_campo = "y57_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y57_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y57_valor"])){ 
       $sql  .= $virgula." y57_valor = $this->y57_valor ";
       $virgula = ",";
       if(trim($this->y57_valor) == null ){ 
         $this->erro_sql = " Campo Valor da Receita nao Informado.";
         $this->erro_campo = "y57_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($y57_codauto!=null){
       $sql .= " y57_codauto = $this->y57_codauto";
     }
     if($y57_receit!=null){
       $sql .= " and  y57_receit = $this->y57_receit";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->y57_codauto,$this->y57_receit));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5008,'$this->y57_codauto','A')");
         $resac = db_query("insert into db_acountkey values($acount,5009,'$this->y57_receit','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y57_codauto"]))
           $resac = db_query("insert into db_acount values($acount,707,5008,'".AddSlashes(pg_result($resaco,$conresaco,'y57_codauto'))."','$this->y57_codauto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y57_receit"]))
           $resac = db_query("insert into db_acount values($acount,707,5009,'".AddSlashes(pg_result($resaco,$conresaco,'y57_receit'))."','$this->y57_receit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y57_descr"]))
           $resac = db_query("insert into db_acount values($acount,707,5010,'".AddSlashes(pg_result($resaco,$conresaco,'y57_descr'))."','$this->y57_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y57_valor"]))
           $resac = db_query("insert into db_acount values($acount,707,5011,'".AddSlashes(pg_result($resaco,$conresaco,'y57_valor'))."','$this->y57_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "autorec nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y57_codauto."-".$this->y57_receit;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "autorec nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y57_codauto."-".$this->y57_receit;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y57_codauto."-".$this->y57_receit;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($y57_codauto=null,$y57_receit=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($y57_codauto,$y57_receit));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5008,'$y57_codauto','E')");
         $resac = db_query("insert into db_acountkey values($acount,5009,'$y57_receit','E')");
         $resac = db_query("insert into db_acount values($acount,707,5008,'','".AddSlashes(pg_result($resaco,$iresaco,'y57_codauto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,707,5009,'','".AddSlashes(pg_result($resaco,$iresaco,'y57_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,707,5010,'','".AddSlashes(pg_result($resaco,$iresaco,'y57_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,707,5011,'','".AddSlashes(pg_result($resaco,$iresaco,'y57_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from autorec
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($y57_codauto != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y57_codauto = $y57_codauto ";
        }
        if($y57_receit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y57_receit = $y57_receit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "autorec nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y57_codauto."-".$y57_receit;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "autorec nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y57_codauto."-".$y57_receit;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y57_codauto."-".$y57_receit;
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
     $result = db_query($sql);
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
        $this->erro_sql   = "Record Vazio na Tabela:autorec";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $y57_codauto=null,$y57_receit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from autorec ";
     $sql .= "      inner join tabrec    on tabrec.k02_codigo = autorec.y57_receit";
     $sql .= "      inner join auto      on auto.y50_codauto = autorec.y57_codauto";
     $sql .= "      inner join tabrecjm  on tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql .= "      inner join db_depart on db_depart.coddepto = auto.y50_setor";
     $sql2 = "";
     if($dbwhere==""){
       if($y57_codauto!=null ){
         $sql2 .= " where autorec.y57_codauto = $y57_codauto "; 
       } 
       if($y57_receit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " autorec.y57_receit = $y57_receit "; 
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
   function sql_query_file ( $y57_codauto=null,$y57_receit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from autorec ";
     $sql2 = "";
     if($dbwhere==""){
       if($y57_codauto!=null ){
         $sql2 .= " where autorec.y57_codauto = $y57_codauto "; 
       } 
       if($y57_receit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " autorec.y57_receit = $y57_receit "; 
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